(() => {
    const API_BASE = window.JUNIA_API_BASE || "../api";
    const APP_BASE = window.JUNIA_APP_BASE || "..";
    const DEFAULT_PHOTO = window.JUNIA_DEFAULT_PHOTO || "../uploads/photos/photo_profil.png";
    const ROUTES = window.JUNIA_ROUTES || {};
    const currentUser = window.JUNIA_CURRENT_USER || null;

    const $ = (selector, root = document) => root.querySelector(selector);

    const domainLabel = (domain) => ({
        stage: "Stage",
        alternance: "Alternance",
        cdi: "CDI",
        mobilite: "Mobilité internationale"
    })[domain] || domain;

    const resolvePhoto = (photo) => {
        const path = typeof photo === "string" ? photo.trim() : "";
        if (!path) return DEFAULT_PHOTO;
        if (/^(https?:|data:|\/)/i.test(path)) return path;
        if (path.startsWith("uploads/")) return `${APP_BASE}/${path}`;
        return `${APP_BASE}/uploads/photos/${path}`;
    };

    const createText = (tag, text, className = "") => {
        const element = document.createElement(tag);
        if (className) element.className = className;
        element.textContent = text || "";
        return element;
    };

    const createLink = (label, href) => {
        const link = document.createElement("a");
        link.textContent = label;
        link.href = href;
        if (!href.startsWith("mailto:")) {
            link.target = "_blank";
            link.rel = "noopener noreferrer";
        }
        return link;
    };

    const setSectionVisible = (selector, visible) => {
        const section = $(selector);
        if (section) section.hidden = !visible;
    };

    const fillList = (selector, items) => {
        const list = $(selector);
        if (!list) return;

        list.replaceChildren();
        (items || []).filter(Boolean).forEach((item) => {
            list.append(createText("li", item));
        });
    };

    const fillArticles = (selector, entries) => {
        const container = $(selector);
        if (!container) return;

        container.replaceChildren();
        (entries || []).forEach((entry) => {
            const article = document.createElement("article");
            if (entry.titre) article.append(createText("h3", entry.titre));
            if (entry.dates) {
                const date = createText("p", "", "dates");
                date.append(createText("em", entry.dates));
                article.append(date);
            }
            if (entry.description) article.append(createText("p", entry.description));
            container.append(article);
        });
    };

    const fillProjects = (selector, projects) => {
        const list = $(selector);
        if (!list) return;

        list.replaceChildren();
        (projects || []).forEach((project) => {
            const item = document.createElement("li");
            item.append(createText("strong", project.titre || "Projet"));
            if (project.description) item.append(document.createTextNode(` — ${project.description}`));
            if (project.dates) {
                const date = createText("span", "", "dates");
                date.append(createText("em", project.dates));
                item.append(document.createTextNode(" "), date);
            }
            list.append(item);
        });
    };

    const renderActions = (profile) => {
        const actions = $("#actions-profil");
        if (!actions) return;

        actions.replaceChildren();

        if (currentUser?.role === "student" && Number(currentUser.id) === Number(profile.id)) {
            const edit = document.createElement("a");
            edit.className = "bouton bouton-secondaire";
            edit.href = ROUTES.creer || "modifier-profil.php";
            edit.textContent = "Modifier le CV";

            const remove = document.createElement("a");
            remove.className = "bouton bouton-discret";
            remove.href = `${APP_BASE}/pages/suppression-compte.php`;
            remove.textContent = "Supprimer mon compte";

            actions.append(edit, remove);
        }

        if (currentUser?.role === "company") {
            const button = document.createElement("button");
            button.id = "btn-ouvrir-convocation";
            button.className = "bouton bouton-primaire";
            button.type = "button";
            button.textContent = "Convoquer";
            actions.append(button);
        }
    };

    const renderProfile = (profile) => {
        const fullName = [profile.prenom, profile.nom].filter(Boolean).join(" ") || profile.nom || "Profil étudiant";
        document.title = `CV — ${fullName}`;
        $("header h1").textContent = fullName;
        $(".identite-header p:last-child").textContent = profile.titre || "Profil étudiant JUNIA";

        const photo = $("#cv-photo");
        photo.src = resolvePhoto(profile.photo);
        photo.alt = `Photo de ${fullName}`;
        photo.onerror = () => {
            photo.onerror = null;
            photo.src = DEFAULT_PHOTO;
        };

        $("#cv-message").textContent = "Profil chargé depuis la base de données.";

        const contact = $("#cv-contact");
        if (contact) {
            contact.replaceChildren();
            if (profile.email) {
                const item = document.createElement("li");
                item.append(createLink(profile.email, `mailto:${profile.email}`));
                contact.append(item);
            }
            if (profile.telephone) contact.append(createText("li", profile.telephone));
            if (profile.ville) contact.append(createText("li", profile.ville));
            if (profile.linkedin) {
                const item = document.createElement("li");
                item.append(createLink("LinkedIn", profile.linkedin));
                contact.append(item);
            }
        }

        fillList("#cv-domaines-recherche", (profile.domainesRecherche || []).map(domainLabel));
        fillList("#cv-competences", profile.competences || []);
        fillList("#cv-langues", profile.langues || []);
        fillList("#cv-centres-interet", profile.centresInteret || []);

        const links = $("#cv-liens");
        links.replaceChildren();
        [
            ["GitHub", profile.github],
            ["LinkedIn", profile.linkedin]
        ].forEach(([label, href]) => {
            if (!href) return;
            const item = document.createElement("li");
            item.append(createLink(label, href));
            links.append(item);
        });

        const infoItems = [];
        if (profile.dateNaissance) infoItems.push(`Date de naissance : ${profile.dateNaissance}`);
        fillList("#cv-infos", infoItems);
        setSectionVisible("#section-infos", infoItems.length > 0);

        $("#cv-profil").textContent = profile.profil || "";
        setSectionVisible("#section-profil", Boolean(profile.profil));

        fillArticles("#cv-formations", profile.formations || []);
        fillArticles("#cv-experiences", profile.experiences || []);
        fillProjects("#cv-projets", profile.projets || []);

        setSectionVisible("#formations", (profile.formations || []).length > 0);
        setSectionVisible("#experiences", (profile.experiences || []).length > 0);
        setSectionVisible("#projets", (profile.projets || []).length > 0);
        setSectionVisible("#centres-interet", (profile.centresInteret || []).length > 0);

        renderActions(profile);
        window.JUNIA_PROFILE_ID = Number(profile.id);
        document.dispatchEvent(new CustomEvent("junia:profile-loaded", { detail: profile }));
    };

    const profileIdFromContext = () => {
        const params = new URLSearchParams(window.location.search);
        return params.get("id") || (currentUser?.role === "student" ? currentUser.id : null);
    };

    const init = async () => {
        const profileId = profileIdFromContext();

        if (!profileId) {
            $("#cv-message").textContent = "Aucun profil sélectionné. Retour au catalogue...";
            setTimeout(() => {
                window.location.href = ROUTES.catalogue || "catalogue.php";
            }, 1200);
            return;
        }

        try {
            const response = await fetch(`${API_BASE}/profil.php?id=${encodeURIComponent(profileId)}`, {
                credentials: "include"
            });
            const profile = await response.json();

            if (!response.ok) {
                throw new Error(profile.error || "Erreur de chargement du profil.");
            }

            renderProfile(profile);
        } catch (exception) {
            $("#cv-message").textContent = exception.message;
        }
    };

    document.addEventListener("DOMContentLoaded", init);
})();
