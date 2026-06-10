const CLE_CV = "cv-junia-donnees";
const CLE_BROUILLON = "cv-junia-brouillon";
const CLE_SESSION = "cv-junia-session";
const API_BASE = window.JUNIA_API_BASE || "api";
const ROUTES = window.JUNIA_ROUTES || {
    accueil: "index.php",
    cv: "pages/detail-profil.php",
    creer: "pages/modifier-profil.php"
};
const DEFAULT_PHOTO = window.JUNIA_DEFAULT_PHOTO || "photo_profil.png";

let pagineProfils = 1;

const cvExemple = {
    prenom: "Keanu",
    nom: "GAUTHIER",
    titre: "Étudiant ingénieur : 1re année JUNIA",
    email: "keanu.gauthier@junia.com",
    telephone: "",
    ville: "Bordeaux",
    dateNaissance: "",
    linkedin: "https://www.linkedin.com/feed/",
    github: "https://github.com/keanugauthier",
    photo: DEFAULT_PHOTO,
    profil: "",
    competences: ["Programmation", "Gestion de projet", "Analyse de données"],
    langues: ["Français : langue maternelle", "Anglais : courant"],
    formations: [
        {
            titre: "Cycle ingénieur généraliste - JUNIA, Bordeaux",
            dates: "Depuis 2025",
            description: "Formation pluridisciplinaire dans le numérique."
        },
        {
            titre: "Baccalauréat général - Lycée, Bordeaux",
            dates: "2022 - 2025",
            description: "Spécialités mathématiques et physique-chimie."
        }
    ],
    experiences: [
        {
            titre: "Projet académique - Développement web",
            dates: "2026",
            description: "Conception d'un site vitrine pour présenter mon CV."
        },
        {
            titre: "Projet étudiant - Développement d'applications mobiles",
            dates: "2025",
            description: "Création d'applications mobiles avec Flutter."
        }
    ],
    projets: [
        {
            titre: "Site portfolio",
            dates: "2026",
            description: "Réalisation d'un site personnel pour présenter mes projets et mon CV."
        },
        {
            titre: "Analyse de données",
            dates: "2025",
            description: "Création d'outils de visualisation de données."
        }
    ],
    centresInteret: ["Surf", "Musculation", "IA"]
};

const selectionner = (selecteur, racine = document) => racine.querySelector(selecteur);

const lireStockage = (cle) => {
    try {
        const valeur = localStorage.getItem(cle);
        return valeur ? JSON.parse(valeur) : null;
    } catch {
        localStorage.removeItem(cle);
        return null;
    }
};

const lireSession = () => {
    try {
        return JSON.parse(sessionStorage.getItem(CLE_SESSION)) || null;
    } catch {
        return null;
    }
};

const sauvegarderSession = (session) => {
    sessionStorage.setItem(CLE_SESSION, JSON.stringify(session));
};

const effacerSession = () => {
    sessionStorage.removeItem(CLE_SESSION);
};

const estEmailJunia = (email) => /^[^\s@]+@junia\.com$/i.test(email);

const estTelephoneValide = (telephone) => telephone === "" || /^[0-9]{2}( [0-9]{2}){4}$/.test(telephone);

const formaterTelephone = (valeur) => {
    const chiffres = valeur.replace(/\D/g, "").slice(0, 10);
    return chiffres.match(/.{1,2}/g)?.join(" ") || "";
};

const formaterDate = (date) => {
    if (!date) return "";
    const dateAFormater = new Date(`${date}T00:00:00`);
    if (Number.isNaN(dateAFormater.getTime())) return "";
    return new Intl.DateTimeFormat("fr-FR").format(dateAFormater);
};

const nettoyerTexte = (valeur) => (typeof valeur === "string" ? valeur.trim() : "");

const normaliserListe = (liste) => Array.isArray(liste)
    ? liste.map(nettoyerTexte).filter(Boolean)
    : [];

const normaliserEntrees = (entrees) => Array.isArray(entrees)
    ? entrees
        .map((entree) => ({
            titre: nettoyerTexte(entree.titre),
            dates: nettoyerTexte(entree.dates),
            description: nettoyerTexte(entree.description)
        }))
        .filter((entree) => entree.titre || entree.dates || entree.description)
    : [];

const normaliserCv = (cv) => ({
    prenom: nettoyerTexte(cv.prenom),
    nom: nettoyerTexte(cv.nom),
    titre: nettoyerTexte(cv.titre),
    email: nettoyerTexte(cv.email),
    telephone: formaterTelephone(nettoyerTexte(cv.telephone)),
    ville: nettoyerTexte(cv.ville),
    dateNaissance: nettoyerTexte(cv.dateNaissance),
    linkedin: nettoyerTexte(cv.linkedin),
    github: nettoyerTexte(cv.github),
    photo: nettoyerTexte(cv.photo) || DEFAULT_PHOTO,
    profil: nettoyerTexte(cv.profil),
    competences: normaliserListe(cv.competences),
    langues: normaliserListe(cv.langues),
    formations: normaliserEntrees(cv.formations),
    experiences: normaliserEntrees(cv.experiences),
    projets: normaliserEntrees(cv.projets),
    centresInteret: normaliserListe(cv.centresInteret)
});

const obtenirCvActif = () => normaliserCv(lireStockage(CLE_CV) || cvExemple);

const creerElement = (balise, texte, classe) => {
    const element = document.createElement(balise);
    if (classe) element.className = classe;
    if (texte) element.textContent = texte;
    return element;
};

const creerLien = (texte, href) => {
    const lien = document.createElement("a");
    lien.textContent = texte;
    lien.href = href;
    if (!href.startsWith("mailto:")) {
        lien.target = "_blank";
        lien.rel = "noopener noreferrer";
    }
    return lien;
};

const afficherNotification = (message) => {
    selectionner(".notification")?.remove();
    const notification = creerElement("div", message, "notification");
    notification.setAttribute("role", "status");
    document.body.append(notification);
    setTimeout(() => notification.remove(), 3000);
};

const remplirListeSimple = (selecteur, elements) => {
    const liste = selectionner(selecteur);
    if (!liste) return;
    liste.replaceChildren();
    elements.forEach((texte) => liste.append(creerElement("li", texte)));
};

const remplirListeLiens = (selecteur, liens) => {
    const liste = selectionner(selecteur);
    if (!liste) return;
    liste.replaceChildren();
    liens.forEach(({ texte, href }) => {
        if (!href) return;
        const item = document.createElement("li");
        item.append(creerLien(texte, href));
        liste.append(item);
    });
};

const creerArticle = ({ titre, dates, description }) => {
    const article = document.createElement("article");
    if (titre) article.append(creerElement("h3", titre));
    if (dates) {
        const paragrapheDate = creerElement("p", "", "dates");
        paragrapheDate.append(creerElement("em", dates));
        article.append(paragrapheDate);
    }
    if (description) article.append(creerElement("p", description));
    return article;
};

const remplirArticles = (selecteur, articles) => {
    const conteneur = selectionner(selecteur);
    if (!conteneur) return;
    conteneur.replaceChildren();
    articles.forEach((article) => conteneur.append(creerArticle(article)));
};

const remplirProjets = (selecteur, projets) => {
    const liste = selectionner(selecteur);
    if (!liste) return;
    liste.replaceChildren();
    projets.forEach((projet) => {
        const item = document.createElement("li");
        const titre = creerElement("strong", projet.titre || "Projet");
        item.append(titre);
        if (projet.description) item.append(document.createTextNode(` — ${projet.description}`));
        if (projet.dates) {
            const dates = creerElement("span", "", "dates");
            dates.append(creerElement("em", projet.dates));
            item.append(document.createTextNode(" "), dates);
        }
        liste.append(item);
    });
};

const reglerVisibiliteSection = (selecteur, visible) => {
    const section = selectionner(selecteur);
    if (section) section.hidden = !visible;
};

const afficherCv = () => {
    const cv = obtenirCvActif();
    const cvPersonnalise = Boolean(lireStockage(CLE_CV));
    const nomComplet = [cv.prenom, cv.nom].filter(Boolean).join(" ") || "Prénom Nom";

    selectionner("#cv-nom").textContent = nomComplet;
    selectionner("#cv-titre").textContent = cv.titre || "Titre du CV";

    const photo = selectionner("#cv-photo");
    photo.onerror = () => {
        photo.onerror = null;
        photo.src = DEFAULT_PHOTO;
    };
    photo.src = cv.photo;
    photo.alt = `Photo de ${nomComplet}`;

    const contact = selectionner("#cv-contact");
    contact.replaceChildren();
    if (cv.email) {
        const item = document.createElement("li");
        item.append(creerLien(cv.email, `mailto:${cv.email}`));
        contact.append(item);
    }
    [cv.telephone, cv.ville].filter(Boolean).forEach((texte) => {
        contact.append(creerElement("li", texte));
    });
    if (cv.linkedin) {
        const item = document.createElement("li");
        item.append(creerLien("LinkedIn", cv.linkedin));
        contact.append(item);
    }

    remplirListeSimple("#cv-competences", cv.competences);
    remplirListeSimple("#cv-langues", cv.langues);
    remplirListeSimple("#cv-centres-interet", cv.centresInteret);
    remplirListeLiens("#cv-liens", [
        { texte: "GitHub", href: cv.github },
        { texte: "LinkedIn", href: cv.linkedin }
    ]);

    const infos = [];
    if (cv.telephone) infos.push(`Téléphone : ${cv.telephone}`);
    if (cv.dateNaissance) infos.push(`Né(e) le ${formaterDate(cv.dateNaissance)}`);
    remplirListeSimple("#cv-infos", infos);
    reglerVisibiliteSection("#section-infos", infos.length > 0);

    selectionner("#cv-profil").textContent = cv.profil;
    reglerVisibiliteSection("#section-profil", cv.profil.length > 0);

    remplirArticles("#cv-formations", cv.formations);
    remplirArticles("#cv-experiences", cv.experiences);
    remplirProjets("#cv-projets", cv.projets);
    reglerVisibiliteSection("#formations", cv.formations.length > 0);
    reglerVisibiliteSection("#experiences", cv.experiences.length > 0);
    reglerVisibiliteSection("#projets", cv.projets.length > 0);
    reglerVisibiliteSection("#centres-interet", cv.centresInteret.length > 0);

    selectionner("#cv-message").textContent = cvPersonnalise
        ? "CV personnalisé affiché."
        : "CV d'exemple affiché.";

    const boutonReset = selectionner("#reinitialiser-cv");
    boutonReset.hidden = !cvPersonnalise;
    boutonReset.onclick = () => {
        localStorage.removeItem(CLE_CV);
        afficherNotification("Retour au CV d'exemple.");
        afficherCv();
    };
};

const afficherBoutonServeur = () => {
    const session = lireSession();
    const btn = selectionner("#charger-serveur");
    if (!btn) return;
    btn.hidden = !session || session.type !== "student";
    btn.addEventListener("click", chargerCvDepuisServeur);
};

const lignesTexte = (valeur) => nettoyerTexte(valeur)
    .split("\n")
    .map(nettoyerTexte)
    .filter(Boolean);

const lignesEntrees = (valeur) => lignesTexte(valeur)
    .map((ligne) => {
        const morceaux = ligne.split("|").map(nettoyerTexte);
        return {
            titre: morceaux[0] || "",
            dates: morceaux[1] || "",
            description: morceaux.slice(2).join(" | ")
        };
    })
    .filter((entree) => entree.titre || entree.dates || entree.description);

const lireChamp = (formulaire, nom) => nettoyerTexte(formulaire.elements[nom]?.value || "");

const construireCvDepuisFormulaire = (formulaire) => normaliserCv({
    prenom: lireChamp(formulaire, "prenom"),
    nom: lireChamp(formulaire, "nom").toUpperCase(),
    titre: lireChamp(formulaire, "titre"),
    email: lireChamp(formulaire, "email"),
    telephone: lireChamp(formulaire, "telephone"),
    ville: lireChamp(formulaire, "ville"),
    dateNaissance: lireChamp(formulaire, "date_naissance"),
    linkedin: lireChamp(formulaire, "linkedin"),
    github: lireChamp(formulaire, "github"),
    photo: lireChamp(formulaire, "photo"),
    profil: lireChamp(formulaire, "profil"),
    competences: lignesTexte(lireChamp(formulaire, "competences")),
    langues: lignesTexte(lireChamp(formulaire, "langues")),
    formations: lignesEntrees(lireChamp(formulaire, "formations")),
    experiences: lignesEntrees(lireChamp(formulaire, "experiences")),
    projets: lignesEntrees(lireChamp(formulaire, "projets")),
    centresInteret: lignesTexte(lireChamp(formulaire, "centres_interet"))
});

const listeVersTextarea = (liste) => liste.join("\n");

const entreesVersTextarea = (entrees) => entrees
    .map((entree) => [entree.titre, entree.dates, entree.description].filter(Boolean).join(" | "))
    .join("\n");

const remplirFormulaire = (formulaire, cv) => {
    formulaire.elements.prenom.value = cv.prenom;
    formulaire.elements.nom.value = cv.nom;
    formulaire.elements.titre.value = cv.titre;
    formulaire.elements.email.value = cv.email;
    formulaire.elements.telephone.value = cv.telephone;
    formulaire.elements.ville.value = cv.ville;
    formulaire.elements.date_naissance.value = cv.dateNaissance;
    formulaire.elements.linkedin.value = cv.linkedin;
    formulaire.elements.github.value = cv.github;
    formulaire.elements.photo.value = cv.photo;
    formulaire.elements.profil.value = cv.profil;
    formulaire.elements.competences.value = listeVersTextarea(cv.competences);
    formulaire.elements.langues.value = listeVersTextarea(cv.langues);
    formulaire.elements.formations.value = entreesVersTextarea(cv.formations);
    formulaire.elements.experiences.value = entreesVersTextarea(cv.experiences);
    formulaire.elements.projets.value = entreesVersTextarea(cv.projets);
    formulaire.elements.centres_interet.value = listeVersTextarea(cv.centresInteret);
};

const mettreAJourEtatEmail = (champEmail, erreurEmail) => {
    const email = champEmail.value.trim();
    erreurEmail.classList.remove("succes");

    if (email === "") {
        erreurEmail.textContent = "";
        champEmail.classList.remove("invalide", "valide");
        champEmail.removeAttribute("aria-invalid");
        champEmail.setCustomValidity("");
        return false;
    }

    if (!estEmailJunia(email)) {
        erreurEmail.textContent = "Utilisez une adresse @junia.com.";
        champEmail.classList.add("invalide");
        champEmail.classList.remove("valide");
        champEmail.setAttribute("aria-invalid", "true");
        champEmail.setCustomValidity("Merci d'utiliser votre adresse JUNIA.");
        return false;
    }

    erreurEmail.textContent = "Email JUNIA valide.";
    erreurEmail.classList.add("succes");
    champEmail.classList.add("valide");
    champEmail.classList.remove("invalide");
    champEmail.removeAttribute("aria-invalid");
    champEmail.setCustomValidity("");
    return true;
};

const mettreAJourEtatTelephone = (champTelephone, erreurTelephone) => {
    champTelephone.value = formaterTelephone(champTelephone.value);

    if (!estTelephoneValide(champTelephone.value)) {
        erreurTelephone.textContent = "Format attendu : 06 12 34 56 78.";
        champTelephone.classList.add("invalide");
        champTelephone.classList.remove("valide");
        champTelephone.setAttribute("aria-invalid", "true");
        champTelephone.setCustomValidity("Le téléphone doit contenir 10 chiffres.");
        return false;
    }

    erreurTelephone.textContent = champTelephone.value ? "Téléphone valide." : "";
    erreurTelephone.classList.toggle("succes", Boolean(champTelephone.value));
    champTelephone.classList.toggle("valide", Boolean(champTelephone.value));
    champTelephone.classList.remove("invalide");
    champTelephone.removeAttribute("aria-invalid");
    champTelephone.setCustomValidity("");
    return true;
};

const mettreAJourCompteurProfil = (champProfil, compteurProfil) => {
    const limite = Number(champProfil.getAttribute("maxlength")) || 450;
    const longueur = champProfil.value.length;
    compteurProfil.textContent = `${longueur} / ${limite} caractères`;
    compteurProfil.classList.toggle("attention", longueur >= limite * 0.9);
};

const afficherApercuRapide = (cv) => {
    const conteneur = selectionner("#apercu-contenu");
    if (!conteneur) return;
    conteneur.replaceChildren();

    const nomComplet = [cv.prenom || "Prénom", cv.nom || "NOM"].join(" ");
    conteneur.append(creerElement("h3", nomComplet));
    conteneur.append(creerElement("p", cv.titre || "Titre du CV"));

    const details = [cv.email, cv.telephone, cv.ville].filter(Boolean).join(" · ");
    if (details) conteneur.append(creerElement("p", details));

    if (cv.profil) {
        conteneur.append(creerElement("h4", "Profil"));
        conteneur.append(creerElement("p", cv.profil));
    }

    if (cv.competences.length > 0) {
        conteneur.append(creerElement("h4", "Compétences"));
        const liste = document.createElement("ul");
        cv.competences.slice(0, 5).forEach((competence) => liste.append(creerElement("li", competence)));
        conteneur.append(liste);
    }
};

// === BACKEND : AUTHENTIFICATION ===

async function login(email, password, userType) {
    try {
        const response = await fetch(`${API_BASE}/login.php`, {
            method: "POST",
            headers: { "Content-Type": "application/json" },
            credentials: "include",
            body: JSON.stringify({ email, password, user_type: userType })
        });

        const data = await response.json();

        if (response.ok) {
            sauvegarderSession(data.user);
            afficherSectionConnexion();
            chargerProfils(1);
            afficherNotification(`Connecté en tant que ${data.user.nom} !`);
        } else {
            const erreur = selectionner("#conn-erreur");
            if (erreur) erreur.textContent = data.error;
        }
    } catch {
        const erreur = selectionner("#conn-erreur");
        if (erreur) erreur.textContent = "Erreur réseau.";
    }
}

async function deconnexion() {
    effacerSession();
    afficherSectionConnexion();
    chargerProfils(1);
    afficherNotification("Déconnecté.");
}

// === BACKEND : CV ===

async function sauvegarderCvBackend(cv) {
    const session = lireSession();
    if (!session || session.type !== "student") return;

    try {
        const response = await fetch(`${API_BASE}/enregistrer-cv.php`, {
            method: "POST",
            headers: { "Content-Type": "application/json" },
            credentials: "include",
            body: JSON.stringify(cv)
        });

        const data = await response.json();
        if (response.ok) {
            afficherNotification("CV sauvegardé sur le serveur !");
        } else {
            console.error("Erreur backend:", data.error);
        }
    } catch (error) {
        console.error("Erreur réseau:", error);
    }
}

async function chargerCvDepuisServeur() {
    const session = lireSession();
    if (!session || session.type !== "student") return;

    try {
        const response = await fetch(`${API_BASE}/profil.php?id=${session.id}`, {
            credentials: "include"
        });

        if (!response.ok) throw new Error("Réponse non OK");

        const cv = await response.json();
        localStorage.setItem(CLE_CV, JSON.stringify(normaliserCv(cv)));
        afficherNotification("CV chargé depuis le serveur.");
        afficherCv();
    } catch {
        afficherNotification("Impossible de charger le CV depuis le serveur.");
    }
}

// === BACKEND : PROFILS ===

async function chargerProfils(page = 1) {
    pagineProfils = page;
    const search = selectionner("#search-input")?.value ?? "";
    const domaine = selectionner("#domaine-select")?.value ?? "";

    let url = `${API_BASE}/profils.php?page=${page}`;
    if (search) url += `&search=${encodeURIComponent(search)}`;
    if (domaine) url += `&domaine=${encodeURIComponent(domaine)}`;

    try {
        const response = await fetch(url);
        if (!response.ok) throw new Error();
        const profils = await response.json();
        afficherProfils(profils, page);
    } catch {
        const grille = selectionner("#grille-profils");
        if (grille) grille.innerHTML = "<p>Impossible de contacter le serveur. Vérifiez que XAMPP est démarré.</p>";
    }
}

function changerPage(delta) {
    chargerProfils(pagineProfils + delta);
}

function afficherProfils(profils, page) {
    const grille = selectionner("#grille-profils");
    if (!grille) return;

    grille.replaceChildren();

    if (profils.length === 0) {
        grille.append(creerElement("p", "Aucun profil trouvé."));
    } else {
        const session = lireSession();
        profils.forEach((profil) => grille.append(creerCarteEtudiant(profil, session)));
    }

    const btnPrev = selectionner("#btn-prev");
    const btnNext = selectionner("#btn-next");
    const pageInfo = selectionner("#page-info");

    if (btnPrev) btnPrev.hidden = page <= 1;
    if (btnNext) btnNext.hidden = profils.length < 10;
    if (pageInfo) pageInfo.textContent = profils.length > 0 ? `Page ${page}` : "";
}

function creerCarteEtudiant(profil, session) {
    const domaines = Array.isArray(profil.domaines) ? profil.domaines : [];

    const card = document.createElement("div");
    card.className = "profil-card";

    const header = document.createElement("div");
    header.className = "profil-card-header";

    const avatar = creerElement("div", profil.nom.charAt(0).toUpperCase(), "profil-card-avatar");
    header.append(avatar, creerElement("h3", profil.nom));
    card.append(header);

    if (profil.biographie) card.append(creerElement("p", profil.biographie));

    if (domaines.length > 0) {
        const tags = document.createElement("div");
        tags.className = "profil-domaines";
        domaines.forEach((d) => tags.append(creerElement("span", d, "tag")));
        card.append(tags);
    }

    const peutConvoquer = session?.type === "company";
    const btn = document.createElement("button");
    btn.textContent = "Convoquer";
    btn.className = "bouton-convoquer";
    btn.disabled = !peutConvoquer;
    btn.title = peutConvoquer ? "" : "Connexion entreprise requise";
    if (peutConvoquer) btn.addEventListener("click", () => convoquer(profil.id));
    card.append(btn);

    return card;
}

async function convoquer(etudiantId) {
    const typeContrat = prompt("Type de contrat ? (stage, alternance, cdi, mobilité)");
    if (!typeContrat) return;

    const message = prompt("Message pour l'étudiant (optionnel) :") ?? "";

    try {
        const response = await fetch(`${API_BASE}/convocation.php`, {
            method: "POST",
            headers: { "Content-Type": "application/json" },
            credentials: "include",
            body: JSON.stringify({ etudiant_id: etudiantId, type_contrat: typeContrat, message })
        });

        const data = await response.json();
        afficherNotification(response.ok ? "Convocation envoyée !" : data.error);
    } catch {
        afficherNotification("Erreur réseau.");
    }
}

// === UI : SECTION CONNEXION ===

function afficherSectionConnexion() {
    const conteneur = selectionner("#connexion-contenu");
    if (!conteneur) return;

    const session = lireSession();
    conteneur.replaceChildren();

    if (session) {
        let typeStr = "Étudiant";
        if (session.type === "company") typeStr = "Entreprise";
        if (session.type === "admin") typeStr = "Administration";
        conteneur.append(creerElement("p", `Connecté en tant que ${session.nom} (${typeStr}).`));

        const actions = document.createElement("div");
        actions.className = "actions-ligne";
        actions.style.marginTop = "1rem";

        const btn = document.createElement("button");
        btn.textContent = "Se déconnecter";
        btn.className = "bouton-discret";
        btn.type = "button";
        btn.addEventListener("click", deconnexion);
        actions.append(btn);
        conteneur.append(actions);
    } else {
        const form = document.createElement("form");
        form.id = "form-connexion";
        form.innerHTML = `
            <div class="grille-formulaire">
                <div>
                    <label for="conn-email">Email</label>
                    <input type="email" id="conn-email" required>
                </div>
                <div>
                    <label for="conn-password">Mot de passe</label>
                    <input type="password" id="conn-password" required>
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
            </div>
            <p id="conn-erreur" class="erreur" aria-live="polite" style="margin-top:.5rem"></p>
        `;

        form.addEventListener("submit", async (e) => {
            e.preventDefault();
            const email = form.querySelector("#conn-email").value;
            const password = form.querySelector("#conn-password").value;
            const userType = form.querySelector('input[name="conn-type"]:checked').value;
            await login(email, password, userType);
        });

        conteneur.append(form);
    }
}

// === INITIALISATION FORMULAIRE ===

const initialiserFormulaire = () => {
    const formulaire = selectionner("#form-cv");
    if (!formulaire) return;

    const champEmail = selectionner("#email");
    const erreurEmail = selectionner("#erreur-email");
    const champTelephone = selectionner("#telephone");
    const erreurTelephone = selectionner("#erreur-telephone");
    const champProfil = selectionner("#profil");
    const compteurProfil = selectionner("#compteur-profil");
    const boutonExemple = selectionner("#charger-exemple");
    const boutonEffacer = selectionner("#effacer-brouillon");
    const donneesInitiales = lireStockage(CLE_BROUILLON) || lireStockage(CLE_CV) || cvExemple;

    remplirFormulaire(formulaire, normaliserCv(donneesInitiales));

    const mettreAJourFormulaire = () => {
        const cv = construireCvDepuisFormulaire(formulaire);
        mettreAJourEtatEmail(champEmail, erreurEmail);
        mettreAJourEtatTelephone(champTelephone, erreurTelephone);
        mettreAJourCompteurProfil(champProfil, compteurProfil);
        afficherApercuRapide(cv);
        localStorage.setItem(CLE_BROUILLON, JSON.stringify(cv));
    };

    formulaire.addEventListener("input", mettreAJourFormulaire);

    formulaire.addEventListener("submit", async (event) => {
        event.preventDefault();

        const emailValide = mettreAJourEtatEmail(champEmail, erreurEmail);
        const telephoneValide = mettreAJourEtatTelephone(champTelephone, erreurTelephone);
        const formulaireValide = formulaire.reportValidity();

        if (!emailValide || !telephoneValide || !formulaireValide) {
            if (!emailValide) champEmail.focus();
            else if (!telephoneValide) champTelephone.focus();
            return;
        }

        const cv = construireCvDepuisFormulaire(formulaire);
        localStorage.setItem(CLE_CV, JSON.stringify(cv));
        localStorage.removeItem(CLE_BROUILLON);

        await sauvegarderCvBackend(cv);

        window.location.href = ROUTES.cv;
    });

    boutonExemple.addEventListener("click", () => {
        remplirFormulaire(formulaire, normaliserCv(cvExemple));
        localStorage.removeItem(CLE_BROUILLON);
        mettreAJourFormulaire();
        afficherNotification("Exemple chargé dans le formulaire.");
    });

    boutonEffacer.addEventListener("click", () => {
        formulaire.reset();
        localStorage.removeItem(CLE_BROUILLON);
        mettreAJourFormulaire();
        afficherNotification("Formulaire effacé.");
    });

    mettreAJourFormulaire();
};

const initialiserAccueil = () => {
    const etat = selectionner("#etat-cv");
    if (!etat) return;
    etat.textContent = lireStockage(CLE_CV)
        ? "Un CV personnalisé est enregistré. Vous pouvez le consulter ou le modifier."
        : "Le CV d'exemple est disponible par défaut.";
};

if (document.body.dataset.page === "cv") {
    afficherCv();
    afficherBoutonServeur();
}

if (document.body.dataset.page === "create") {
    initialiserFormulaire();
}

if (document.body.dataset.page === "home") {
    initialiserAccueil();
    afficherSectionConnexion();
    chargerProfils(1);
}

if (document.body.dataset.page === "catalogue") {
    chargerProfils(1);
}

if (document.body.dataset.page === "login") {
    afficherSectionConnexion();
}
