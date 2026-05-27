<?php
$pageTitle = 'CV — Keanu Gauthier';
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
        <div class="actions-ligne">
            <a class="bouton bouton-secondaire" href="<?php echo htmlspecialchars($assetBase . '/pages/modifier-profil.php', ENT_QUOTES, 'UTF-8'); ?>">Modifier le CV</a>
            <button id="charger-serveur" class="bouton bouton-secondaire" type="button" hidden>Charger depuis le serveur</button>
            <button id="reinitialiser-cv" class="bouton bouton-discret" type="button" hidden>Revenir à l'exemple</button>
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
</main>

<?php require __DIR__ . '/../inc/footer.php'; ?>
