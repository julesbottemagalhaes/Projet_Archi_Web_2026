(() => {
    const API_BASE = window.JUNIA_API_BASE || "../api";
    const ROUTES = window.JUNIA_ROUTES || {};
    const SESSION_KEY = "cv-junia-session";

    let currentUser = window.JUNIA_CURRENT_USER || null;

    const $ = (selector, root = document) => root.querySelector(selector);

    const notify = (message, type = "info") => {
        $(".notification")?.remove();
        const notification = document.createElement("div");
        notification.className = `notification notification-${type}`;
        notification.setAttribute("role", "status");
        notification.textContent = message;
        document.body.append(notification);
        setTimeout(() => notification.remove(), 3200);
    };

    const saveSession = (user) => {
        currentUser = user || null;

        if (currentUser) {
            sessionStorage.setItem(SESSION_KEY, JSON.stringify(currentUser));
        } else {
            sessionStorage.removeItem(SESSION_KEY);
        }
    };

    const roleLabel = (role) => ({
        student: "Étudiant",
        company: "Entreprise",
        admin: "Administration"
    })[role] || "Utilisateur";

    const routeForRole = (role) => ({
        student: ROUTES.student || ROUTES.creer || ROUTES.accueil || "./",
        company: ROUTES.catalogue || ROUTES.accueil || "./",
        admin: ROUTES.admin || ROUTES.accueil || "./"
    })[role] || ROUTES.accueil || "./";

    const fetchJson = async (url, options = {}) => {
        const response = await fetch(url, {
            credentials: "include",
            ...options,
            headers: {
                ...(options.body instanceof FormData ? {} : { "Content-Type": "application/json" }),
                ...(options.headers || {})
            }
        });
        const payload = await response.json().catch(() => ({}));

        if (!response.ok) {
            throw new Error(payload.error || payload.message || "Action impossible.");
        }

        return payload;
    };

    const syncSession = async () => {
        if (currentUser) {
            saveSession(currentUser);
            return currentUser;
        }

        try {
            const data = await fetchJson(`${API_BASE}/session.php`);
            saveSession(data.user || null);
        } catch {
            saveSession(null);
        }

        return currentUser;
    };

    const logout = async () => {
        try {
            await fetchJson(`${API_BASE}/logout.php`, { method: "POST" });
        } finally {
            saveSession(null);
        }
    };

    const renderLoginBox = () => {
        const container = $("#connexion-contenu");
        if (!container) return;

        container.replaceChildren();

        if (currentUser) {
            const status = document.createElement("p");
            status.textContent = `Connecté en tant que ${currentUser.nom} (${roleLabel(currentUser.role || currentUser.type)}).`;

            const actions = document.createElement("div");
            actions.className = "actions-ligne";
            actions.style.marginTop = "1rem";

            const workspace = document.createElement("a");
            workspace.className = "bouton bouton-secondaire";
            workspace.href = routeForRole(currentUser.role || currentUser.type);
            workspace.textContent = "Ouvrir mon espace";

            const button = document.createElement("button");
            button.type = "button";
            button.className = "bouton-discret";
            button.textContent = "Se déconnecter";
            button.addEventListener("click", async () => {
                await logout();
                notify("Déconnecté.");
                renderLoginBox();
            });

            actions.append(workspace, button);
            container.append(status, actions);
            return;
        }

        const form = document.createElement("form");
        form.id = "form-connexion";
        form.innerHTML = `
            <div class="grille-formulaire">
                <div>
                    <label for="conn-email">Email</label>
                    <input type="email" id="conn-email" autocomplete="email" required>
                </div>
                <div>
                    <label for="conn-password">Mot de passe</label>
                    <input type="password" id="conn-password" autocomplete="current-password" required>
                </div>
            </div>
            <fieldset>
                <legend>Type de compte</legend>
                <label><input type="radio" name="conn-type" value="student" checked> Étudiant</label>
                <label><input type="radio" name="conn-type" value="company"> Entreprise</label>
                <label><input type="radio" name="conn-type" value="admin"> Administration</label>
            </fieldset>
            <div class="actions-ligne" style="margin-top:1rem">
                <button type="submit">Se connecter</button>
                <a class="bouton bouton-secondaire" href="${ROUTES.register || "inscription.php"}">Créer un compte étudiant</a>
            </div>
            <p id="conn-erreur" class="erreur" aria-live="polite" style="margin-top:.5rem"></p>
        `;

        form.addEventListener("submit", async (event) => {
            event.preventDefault();
            const error = $("#conn-erreur", form);
            const submit = $("button[type='submit']", form);
            const credentials = {
                email: $("#conn-email", form).value.trim(),
                password: $("#conn-password", form).value,
                user_type: $("input[name='conn-type']:checked", form).value
            };

            error.textContent = "";
            submit.disabled = true;

            try {
                const data = await fetchJson(`${API_BASE}/login.php`, {
                    method: "POST",
                    body: JSON.stringify(credentials)
                });
                saveSession(data.user);
                notify("Connexion réussie.");
                window.location.href = routeForRole(data.user.role || data.user.type);
            } catch (exception) {
                error.textContent = exception.message;
            } finally {
                submit.disabled = false;
            }
        });

        container.append(form);
    };

    const initRegister = () => {
        const form = $("#form-inscription");
        if (!form) return;

        const message = $("#inscription-message");

        form.addEventListener("submit", async (event) => {
            event.preventDefault();
            if (!form.reportValidity()) return;

            message.textContent = "Création du compte...";

            try {
                const data = await fetchJson(`${API_BASE}/register.php`, {
                    method: "POST",
                    body: JSON.stringify({
                        prenom: form.elements.prenom.value.trim(),
                        nom: form.elements.nom.value.trim(),
                        email: form.elements.email.value.trim(),
                        password: form.elements.password.value,
                        consentement: form.elements.consentement.checked
                    })
                });
                saveSession(data.user);
                message.textContent = "Compte créé. Redirection vers le formulaire CV...";
                window.location.href = ROUTES.creer || "../pages/modifier-profil.php";
            } catch (exception) {
                message.textContent = exception.message;
            }
        });
    };

    const initDeleteAccount = () => {
        const form = $("#form-suppression-compte");
        if (!form) return;

        const message = $("#suppression-message");

        form.addEventListener("submit", async (event) => {
            event.preventDefault();
            if (!form.reportValidity()) return;

            message.textContent = "Suppression du compte...";

            try {
                await fetchJson(`${API_BASE}/delete-account.php`, {
                    method: "POST",
                    body: JSON.stringify({
                        password: form.elements.password.value,
                        confirmation: form.elements.confirmation.checked
                    })
                });
                saveSession(null);
                message.textContent = "Compte supprimé. Redirection...";
                window.location.href = ROUTES.accueil || "../index.php";
            } catch (exception) {
                message.textContent = exception.message;
            }
        });
    };

    const initHomeState = () => {
        const state = $("#etat-cv");
        if (!state) return;

        if (currentUser?.role === "student") {
            state.textContent = "Votre espace étudiant permet de modifier et consulter votre CV.";
        } else if (currentUser?.role === "company") {
            state.textContent = "Votre compte entreprise permet de consulter le catalogue et les convocations.";
        } else {
            state.textContent = "Connectez-vous ou créez un compte étudiant pour commencer.";
        }
    };

    document.addEventListener("DOMContentLoaded", async () => {
        await syncSession();
        renderLoginBox();
        initRegister();
        initDeleteAccount();
        initHomeState();
    });
})();
