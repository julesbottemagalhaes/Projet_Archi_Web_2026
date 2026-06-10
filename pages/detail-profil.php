<?php
require_once __DIR__ . '/../inc/db.php';
$pageTitle = 'CV — Profil';
$metaDescription = 'CV étudiant JUNIA consultable depuis la plateforme.';
$bodyClass = 'page-cv';
$dataPage = 'cv';
$currentPage = 'cv';
$headerKicker = 'CV JUNIA';
$headerTitle = 'Keanu GAUTHIER';
$headerSubtitle = 'Étudiant ingénieur : 1re année JUNIA';
$headerContact = [
    '<a href="mailto:keanu.gauthier@junia.com">keanu.gauthier@junia.com</a>',
    'Bordeaux',
    '<a href="https://www.linkedin.com/feed/" target="_blank" rel="noopener noreferrer">LinkedIn</a>',
];

require __DIR__ . '/../inc/header.php';
?>

<aside>
    <img id="cv-photo" src="<?php echo htmlspecialchars($assetBase . '/uploads/photos/photo_profil.png', ENT_QUOTES, 'UTF-8'); ?>" alt="Photo de Keanu Gauthier">

    <section class="resume-cv">
        <h2>Recherche</h2>
        <ul>
            <li>Stage</li>
            <li>Alternance</li>
        </ul>
    </section>

    <section>
        <h2>Compétences</h2>
        <ul id="cv-competences">
            <li>Programmation</li>
            <li>Gestion de projet</li>
            <li>Analyse de données</li>
        </ul>
    </section>

    <section>
        <h2>Langues</h2>
        <ul id="cv-langues">
            <li>Français : langue maternelle</li>
            <li>Anglais : courant</li>
        </ul>
    </section>

    <section id="section-infos" hidden>
        <h2>Informations</h2>
        <ul id="cv-infos"></ul>
    </section>

    <section>
        <h2>Liens</h2>
        <ul id="cv-liens">
            <li><a href="https://github.com/keanugauthier" target="_blank" rel="noopener noreferrer">GitHub</a></li>
        </ul>
    </section>
</aside>

<main>
    <div class="barre-actions">
        <p id="cv-message" class="message-cv">CV d'exemple affiché.</p>
        <div class="actions-ligne" id="actions-profil">
            <?php if (isset($_SESSION['user_type']) && $_SESSION['user_type'] === 'etudiant'): ?>
                <a class="bouton bouton-secondaire" href="<?php echo htmlspecialchars($assetBase . '/pages/modifier-profil.php', ENT_QUOTES, 'UTF-8'); ?>">Modifier le CV</a>
            <?php elseif (isset($_SESSION['user_type']) && $_SESSION['user_type'] === 'company'): ?>
                <button id="btn-ouvrir-convocation" class="bouton bouton-primaire" type="button">Convoquer</button>
            <?php endif; ?>
        </div>
    </div>

    <section id="section-profil" hidden>
        <h2>Profil</h2>
        <article>
            <p id="cv-profil"></p>
        </article>
    </section>

    <section>
        <h2>Synthèse</h2>
        <article>
            <p>
                Profil étudiant présenté dans un format commun afin de faciliter la lecture
                par les entreprises partenaires et l'équipe JUNIA.
            </p>
        </article>
    </section>

    <section id="formations">
        <h2>Formations</h2>
        <div id="cv-formations">
            <article>
                <h3>Cycle ingénieur généraliste - JUNIA, Bordeaux</h3>
                <p class="dates"><em>Depuis 2025</em></p>
                <p>Formation pluridisciplinaire dans le numérique.</p>
            </article>

            <article>
                <h3>Baccalauréat général - Lycée, Bordeaux</h3>
                <p class="dates"><em>2022 - 2025</em></p>
                <p>Spécialités mathématiques et physique-chimie.</p>
            </article>
        </div>
    </section>

    <section id="experiences">
        <h2>Expériences</h2>
        <div id="cv-experiences">
            <article>
                <h3>Projet académique - Développement web</h3>
                <p class="dates"><em>2026</em></p>
                <p>Conception d'un site vitrine pour présenter mon CV.</p>
            </article>

            <article>
                <h3>Projet étudiant - Développement d'applications mobiles</h3>
                <p class="dates"><em>2025</em></p>
                <p>Création d'applications mobiles avec Flutter.</p>
            </article>
        </div>
    </section>

    <section id="projets">
        <h2>Projets</h2>
        <ul id="cv-projets">
            <li><strong>Site portfolio</strong> — Réalisation d'un site personnel pour présenter mes projets et mon CV. <span class="dates"><em>2026</em></span></li>
            <li><strong>Analyse de données</strong> — Création d'outils de visualisation de données. <span class="dates"><em>2025</em></span></li>
        </ul>
    </section>

    <section id="centres-interet">
        <h2>Centres d'intérêt</h2>
        <ul id="cv-centres-interet">
            <li>Surf</li>
            <li>Musculation</li>
            <li>IA</li>
        </ul>
    </section>

    <!-- Modale de convocation -->
    <dialog id="modal-convocation" class="modal">
        <div class="modal-content">
            <h2>Convoquer ce candidat</h2>
            <form id="form-convocation">
                <div class="grille-formulaire">
                    <div>
                        <label for="conv-date">Date de l'entretien</label>
                        <input type="date" id="conv-date" name="date" required>
                    </div>
                    <div>
                        <label for="conv-heure">Heure</label>
                        <input type="time" id="conv-heure" name="heure" required>
                    </div>
                    <div class="champ-large">
                        <label for="conv-lieu">Lieu / Lien visio</label>
                        <input type="text" id="conv-lieu" name="lieu" required>
                    </div>
                    <div class="champ-large">
                        <label for="conv-contrat">Type de contrat proposé</label>
                        <select id="conv-contrat" name="contrat" required>
                            <option value="">Sélectionner</option>
                            <option value="stage">Stage</option>
                            <option value="alternance">Alternance</option>
                            <option value="cdi">CDI</option>
                            <option value="cdd">CDD</option>
                        </select>
                    </div>
                    <div class="champ-large">
                        <label for="conv-message">Message d'accompagnement</label>
                        <textarea id="conv-message" name="message" rows="4" required></textarea>
                    </div>
                </div>
                <div class="actions-ligne" style="margin-top:1rem">
                    <button type="button" id="btn-fermer-modal" class="bouton bouton-discret">Annuler</button>
                    <button type="submit" class="bouton bouton-primaire">Envoyer la convocation</button>
                </div>
            </form>
            <p id="conv-resultat" style="margin-top:1rem"></p>
        </div>
    </dialog>
</main>

<?php require __DIR__ . '/../inc/footer.php'; ?>
