(() => {
    const API_BASE = window.JUNIA_API_BASE || "../api";
    const ROUTES = window.JUNIA_ROUTES || {};
    const DRAFT_KEY = "cv-junia-brouillon";
    const MAX_PHOTO_SIZE = 2 * 1024 * 1024;
    const ALLOWED_PHOTO_TYPES = ["image/jpeg", "image/png"];

    const $ = (selector, root = document) => root.querySelector(selector);
    const $$ = (selector, root = document) => Array.from(root.querySelectorAll(selector));

    const exampleCv = {
        prenom: "Keanu",
        nom: "GAUTHIER",
        titre: "Étudiant ingénieur : 1re année JUNIA",
        email: "keanu.gauthier@junia.com",
        telephone: "06 12 34 56 78",
        ville: "Bordeaux",
        dateNaissance: "",
        linkedin: "https://www.linkedin.com/in/keanu-gauthier",
        github: "https://github.com/keanugauthier",
        photo: "",
        profil: "Étudiant ingénieur passionné par le développement web, je recherche une opportunité pour renforcer mes compétences en équipe projet.",
        domainesRecherche: ["stage", "alternance"],
        competences: ["PHP", "MySQL", "JavaScript", "Gestion de projet"],
        langues: ["Français : langue maternelle", "Anglais : courant"],
        formations: [
            { titre: "Cycle ingénieur généraliste - JUNIA, Bordeaux", dates: "Depuis 2025", description: "Formation pluridisciplinaire orientée numérique." }
        ],
        experiences: [
            { titre: "Projet académique - Développement web", dates: "2026", description: "Conception d'une plateforme CV avec PHP, MySQL et JavaScript vanilla." }
        ],
        projets: [
            { titre: "Portfolio étudiant", dates: "2026", description: "Site personnel de présentation des projets et compétences." }
        ],
        centresInteret: ["Innovation", "Sport", "Veille technologique"]
    };

    const notify = (message, type = "info") => {
        $(".notification")?.remove();
        const notification = document.createElement("div");
        notification.className = `notification notification-${type}`;
        notification.setAttribute("role", "status");
        notification.textContent = message;
        document.body.append(notification);
        setTimeout(() => notification.remove(), 3200);
    };

    const text = (value) => (typeof value === "string" ? value.trim() : "");

    const formatPhone = (value) => {
        const digits = text(value).replace(/\D/g, "").slice(0, 10);
        return digits.match(/.{1,2}/g)?.join(" ") || "";
    };

    const lines = (value) => text(value).split("\n").map(text).filter(Boolean);

    const entryLines = (value) => lines(value).map((line) => {
        const parts = line.split("|").map(text);
        return {
            titre: parts[0] || "",
            dates: parts[1] || "",
            description: parts.slice(2).join(" | ")
        };
    }).filter((entry) => entry.titre || entry.dates || entry.description);

    const listToTextarea = (items = []) => items.join("\n");
    const entriesToTextarea = (items = []) => items
        .map((entry) => [entry.titre, entry.dates, entry.description].filter(Boolean).join(" | "))
        .join("\n");

    const normaliseCv = (cv = {}) => ({
        prenom: text(cv.prenom),
        nom: text(cv.nom).toUpperCase(),
        titre: text(cv.titre),
        email: text(cv.email).toLowerCase(),
        telephone: formatPhone(cv.telephone),
        ville: text(cv.ville),
        dateNaissance: text(cv.dateNaissance || cv.date_naissance),
        linkedin: text(cv.linkedin),
        github: text(cv.github),
        photo: text(cv.photo),
        profil: text(cv.profil),
        domainesRecherche: Array.isArray(cv.domainesRecherche) ? cv.domainesRecherche : [],
        competences: Array.isArray(cv.competences) ? cv.competences.map(text).filter(Boolean) : [],
        langues: Array.isArray(cv.langues) ? cv.langues.map(text).filter(Boolean) : [],
        formations: Array.isArray(cv.formations) ? cv.formations : [],
        experiences: Array.isArray(cv.experiences) ? cv.experiences : [],
        projets: Array.isArray(cv.projets) ? cv.projets : [],
        centresInteret: Array.isArray(cv.centresInteret) ? cv.centresInteret.map(text).filter(Boolean) : []
    });

    const readDraft = () => {
        try {
            return JSON.parse(localStorage.getItem(DRAFT_KEY) || "null");
        } catch {
            localStorage.removeItem(DRAFT_KEY);
            return null;
        }
    };

    const buildCv = (form) => normaliseCv({
        prenom: form.elements.prenom.value,
        nom: form.elements.nom.value,
        titre: form.elements.titre.value,
        email: form.elements.email.value,
        telephone: form.elements.telephone.value,
        ville: form.elements.ville.value,
        dateNaissance: form.elements.date_naissance.value,
        linkedin: form.elements.linkedin.value,
        github: form.elements.github.value,
        photo: form.elements.photo.value,
        profil: form.elements.profil.value,
        domainesRecherche: $$("input[name='domaines_recherche[]']:checked", form).map((input) => input.value),
        competences: lines(form.elements.competences.value),
        langues: lines(form.elements.langues.value),
        formations: entryLines(form.elements.formations.value),
        experiences: entryLines(form.elements.experiences.value),
        projets: entryLines(form.elements.projets.value),
        centresInteret: lines(form.elements.centres_interet.value)
    });

    const fillForm = (form, cv) => {
        form.elements.prenom.value = cv.prenom;
        form.elements.nom.value = cv.nom;
        form.elements.titre.value = cv.titre;
        form.elements.email.value = cv.email;
        form.elements.telephone.value = cv.telephone;
        form.elements.ville.value = cv.ville;
        form.elements.date_naissance.value = cv.dateNaissance;
        form.elements.linkedin.value = cv.linkedin;
        form.elements.github.value = cv.github;
        form.elements.photo.value = cv.photo;
        form.elements.profil.value = cv.profil;
        form.elements.competences.value = listToTextarea(cv.competences);
        form.elements.langues.value = listToTextarea(cv.langues);
        form.elements.formations.value = entriesToTextarea(cv.formations);
        form.elements.experiences.value = entriesToTextarea(cv.experiences);
        form.elements.projets.value = entriesToTextarea(cv.projets);
        form.elements.centres_interet.value = listToTextarea(cv.centresInteret);

        $$("input[name='domaines_recherche[]']", form).forEach((input) => {
            input.checked = cv.domainesRecherche.includes(input.value);
        });

        const photoInfo = $("#photo-actuelle", form);
        if (photoInfo) {
            photoInfo.textContent = cv.photo
                ? `Photo actuelle : ${cv.photo}`
                : "Formats acceptés : JPG ou PNG, 2 Mo maximum.";
        }
    };

    const updateEmailState = (form) => {
        const input = form.elements.email;
        const error = $("#erreur-email", form);
        const valid = /^[^\s@]+@junia\.com$/i.test(input.value.trim());

        input.classList.toggle("valide", valid);
        input.classList.toggle("invalide", input.value.trim() !== "" && !valid);
        input.setCustomValidity(valid ? "" : "Merci d'utiliser une adresse @junia.com.");
        error.textContent = input.value.trim() === "" || valid ? "" : "Utilisez une adresse @junia.com.";
        return valid;
    };

    const updatePhoneState = (form) => {
        const input = form.elements.telephone;
        const error = $("#erreur-telephone", form);
        input.value = formatPhone(input.value);

        const valid = input.value === "" || /^[0-9]{2}( [0-9]{2}){4}$/.test(input.value);
        input.classList.toggle("valide", input.value !== "" && valid);
        input.classList.toggle("invalide", !valid);
        input.setCustomValidity(valid ? "" : "Le téléphone doit contenir 10 chiffres.");
        error.textContent = valid ? "" : "Format attendu : 06 12 34 56 78.";
        return valid;
    };

    const updateCounter = (form) => {
        const input = form.elements.profil;
        const counter = $("#compteur-profil", form);
        const max = Number(input.getAttribute("maxlength")) || 450;
        counter.textContent = `${input.value.length} / ${max} caractères`;
        counter.classList.toggle("attention", input.value.length >= max * 0.9);
    };

    const validatePhoto = (form) => {
        const input = form.elements.photo_upload;
        const info = $("#photo-actuelle", form);
        const file = input?.files?.[0] || null;

        if (!file) {
            input?.setCustomValidity("");
            if (info && !form.elements.photo.value) {
                info.textContent = "Formats acceptés : JPG ou PNG, 2 Mo maximum.";
            }
            return true;
        }

        if (!ALLOWED_PHOTO_TYPES.includes(file.type)) {
            input.setCustomValidity("La photo doit être au format JPG ou PNG.");
            if (info) info.textContent = "La photo doit être au format JPG ou PNG.";
            return false;
        }

        if (file.size > MAX_PHOTO_SIZE) {
            input.setCustomValidity("La photo ne doit pas dépasser 2 Mo.");
            if (info) info.textContent = "La photo ne doit pas dépasser 2 Mo.";
            return false;
        }

        input.setCustomValidity("");
        if (info) info.textContent = `Photo sélectionnée : ${file.name}`;
        return true;
    };

    const renderPreview = (cv) => {
        const container = $("#apercu-contenu");
        if (!container) return;

        container.replaceChildren();
        const title = document.createElement("h3");
        title.textContent = [cv.prenom || "Prénom", cv.nom || "NOM"].join(" ");
        const subtitle = document.createElement("p");
        subtitle.textContent = cv.titre || "Titre du CV";
        container.append(title, subtitle);

        const details = [cv.email, cv.telephone, cv.ville].filter(Boolean).join(" · ");
        if (details) {
            const paragraph = document.createElement("p");
            paragraph.textContent = details;
            container.append(paragraph);
        }

        if (cv.competences.length > 0) {
            const heading = document.createElement("h4");
            heading.textContent = "Compétences";
            const list = document.createElement("ul");
            cv.competences.slice(0, 6).forEach((skill) => {
                const item = document.createElement("li");
                item.textContent = skill;
                list.append(item);
            });
            container.append(heading, list);
        }
    };

    const updateForm = (form) => {
        const cv = buildCv(form);
        updateEmailState(form);
        updatePhoneState(form);
        updateCounter(form);
        validatePhoto(form);
        renderPreview(cv);
        localStorage.setItem(DRAFT_KEY, JSON.stringify(cv));
    };

    const loadServerCv = async (form) => {
        try {
            const response = await fetch(`${API_BASE}/student-profile.php`, { credentials: "include" });
            if (!response.ok) return null;
            return normaliseCv(await response.json());
        } catch {
            return null;
        }
    };

    const saveServerCv = async (cv, photoFile) => {
        const options = {
            method: "POST",
            credentials: "include"
        };

        if (photoFile) {
            const formData = new FormData();
            formData.append("cv", JSON.stringify(cv));
            formData.append("photo", photoFile);
            options.body = formData;
        } else {
            options.headers = { "Content-Type": "application/json" };
            options.body = JSON.stringify(cv);
        }

        const response = await fetch(`${API_BASE}/enregistrer-cv.php`, options);
        const payload = await response.json().catch(() => ({}));

        if (!response.ok) {
            throw new Error(payload.error || "Impossible d'enregistrer le CV.");
        }

        return payload.cv ? normaliseCv(payload.cv) : cv;
    };

    const init = async () => {
        const form = $("#form-cv");
        if (!form) return;

        const serverCv = await loadServerCv(form);
        const initialCv = normaliseCv(readDraft() || serverCv || exampleCv);

        fillForm(form, initialCv);
        updateForm(form);

        form.addEventListener("input", () => updateForm(form));

        $("#charger-exemple")?.addEventListener("click", () => {
            fillForm(form, normaliseCv(exampleCv));
            updateForm(form);
            notify("Exemple chargé.");
        });

        $("#effacer-brouillon")?.addEventListener("click", () => {
            localStorage.removeItem(DRAFT_KEY);
            fillForm(form, normaliseCv(serverCv || exampleCv));
            updateForm(form);
            notify("Brouillon effacé.");
        });

        form.addEventListener("submit", async (event) => {
            event.preventDefault();

            const emailOk = updateEmailState(form);
            const phoneOk = updatePhoneState(form);
            const photoOk = validatePhoto(form);
            if (!emailOk || !phoneOk || !photoOk || !form.reportValidity()) return;

            const submit = $("button[type='submit']", form);
            submit.disabled = true;

            try {
                const savedCv = await saveServerCv(buildCv(form), form.elements.photo_upload.files[0] || null);
                localStorage.removeItem(DRAFT_KEY);
                fillForm(form, savedCv);
                notify("CV enregistré.");
                window.location.href = ROUTES.cv || "../pages/detail-profil.php";
            } catch (exception) {
                notify(exception.message, "error");
            } finally {
                submit.disabled = false;
            }
        });
    };

    document.addEventListener("DOMContentLoaded", init);
})();
