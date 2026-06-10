(() => {
    const API_BASE = window.JUNIA_API_BASE || "../api";
    const APP_BASE = window.JUNIA_APP_BASE || "..";
    const DEFAULT_PHOTO = window.JUNIA_DEFAULT_PHOTO || "../uploads/photos/photo_profil.png";
    const currentUser = window.JUNIA_CURRENT_USER || null;

    let currentPage = 1;

    const $ = (selector, root = document) => root.querySelector(selector);

    const resolvePhoto = (photo) => {
        const path = typeof photo === "string" ? photo.trim() : "";
        if (!path) return DEFAULT_PHOTO;
        if (/^(https?:|data:|\/)/i.test(path)) return path;
        if (path.startsWith("uploads/")) return `${APP_BASE}/${path}`;
        return `${APP_BASE}/uploads/photos/${path}`;
    };

    const domainLabel = (domain) => ({
        stage: "Stage",
        alternance: "Alternance",
        cdi: "CDI",
        mobilite: "Mobilité"
    })[domain] || domain;

    const createText = (tag, text, className = "") => {
        const element = document.createElement(tag);
        if (className) element.className = className;
        element.textContent = text || "";
        return element;
    };

    const buildQuery = (page) => new URLSearchParams({
        page,
        search: $("#search-input")?.value.trim() || "",
        competence: $("#competence-input")?.value.trim() || "",
        ecole: $("#ecole-input")?.value.trim() || "",
        domaine: $("#domaine-select")?.value || ""
    });

    const setLoading = (isLoading) => {
        const button = $("#btn-rechercher");
        if (!button) return;
        button.disabled = isLoading;
        button.textContent = isLoading ? "Recherche..." : "Rechercher";
    };

    const updatePagination = (profiles) => {
        $("#btn-prev").hidden = currentPage <= 1;
        $("#btn-next").hidden = profiles.length < 10;
        $("#page-info").textContent = profiles.length > 0 ? `Page ${currentPage}` : "";
    };

    const renderProfiles = (profiles) => {
        const grid = $("#grille-profils");
        grid.replaceChildren();

        if (!profiles.length) {
            grid.append(createText("p", "Aucun profil trouvé correspondant aux critères."));
            updatePagination(profiles);
            return;
        }

        profiles.forEach((profile) => {
            const card = document.createElement("article");
            card.className = "profil-card";

            const header = document.createElement("div");
            header.className = "profil-card-header";

            if (profile.photo) {
                const photo = document.createElement("img");
                photo.className = "profil-card-avatar";
                photo.src = resolvePhoto(profile.photo);
                photo.alt = `Photo de ${profile.nom}`;
                photo.loading = "lazy";
                photo.onerror = () => {
                    photo.replaceWith(createText("div", (profile.nom || "?").charAt(0), "profil-card-avatar"));
                };
                header.append(photo);
            } else {
                header.append(createText("div", (profile.nom || "?").charAt(0), "profil-card-avatar"));
            }

            const identity = document.createElement("div");
            identity.append(createText("h3", profile.nom || "Profil étudiant"));
            if (profile.titre) identity.append(createText("p", profile.titre));
            header.append(identity);
            card.append(header);

            if (profile.biographie) {
                card.append(createText("p", profile.biographie));
            }

            const domains = Array.isArray(profile.domaines) ? profile.domaines : [];
            if (domains.length) {
                const tags = document.createElement("div");
                tags.className = "profil-domaines";
                domains.forEach((domain) => tags.append(createText("span", domainLabel(domain), "tag")));
                card.append(tags);
            }

            const actions = document.createElement("div");
            actions.className = "actions-ligne";

            const detail = document.createElement("a");
            detail.className = currentUser?.role === "company" ? "bouton bouton-primaire" : "bouton bouton-secondaire";
            detail.href = `detail-profil.php?id=${encodeURIComponent(profile.id)}`;
            detail.textContent = currentUser?.role === "company" ? "Voir et convoquer" : "Consulter";
            actions.append(detail);

            card.append(actions);
            grid.append(card);
        });

        updatePagination(profiles);
    };

    async function loadProfiles(page = 1) {
        currentPage = Math.max(1, page);
        setLoading(true);

        try {
            const response = await fetch(`${API_BASE}/profils.php?${buildQuery(currentPage).toString()}`, {
                credentials: "include"
            });
            const profiles = await response.json();

            if (!response.ok) {
                throw new Error(profiles.error || "Impossible de charger les profils.");
            }

            renderProfiles(Array.isArray(profiles) ? profiles : []);
        } catch (exception) {
            const grid = $("#grille-profils");
            grid.replaceChildren(createText("p", exception.message));
            updatePagination([]);
        } finally {
            setLoading(false);
        }
    }

    const init = () => {
        const searchButton = $("#btn-rechercher");
        searchButton?.addEventListener("click", () => loadProfiles(1));

        ["#search-input", "#competence-input", "#ecole-input"].forEach((selector) => {
            $(selector)?.addEventListener("keydown", (event) => {
                if (event.key === "Enter") {
                    event.preventDefault();
                    loadProfiles(1);
                }
            });
        });

        $("#domaine-select")?.addEventListener("change", () => loadProfiles(1));
        $("#btn-prev")?.addEventListener("click", () => loadProfiles(currentPage - 1));
        $("#btn-next")?.addEventListener("click", () => loadProfiles(currentPage + 1));
        loadProfiles(1);
    };

    window.chargerProfils = loadProfiles;
    window.changerPage = (offset) => loadProfiles(currentPage + offset);

    document.addEventListener("DOMContentLoaded", init);
})();
