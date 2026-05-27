<?php
$pageTitle = 'Catalogue — CV JUNIA';
$metaDescription = 'Catalogue des profils étudiants JUNIA avec recherche et filtres.';
$bodyClass = 'page-simple page-accueil';
$dataPage = 'catalogue';
$currentPage = 'catalogue';
$headerKicker = 'Catalogue';
$headerTitle = 'Profils des étudiants';
$headerSubtitle = 'Rechercher un étudiant, filtrer par domaine et envoyer une convocation.';

require __DIR__ . '/../inc/header.php';
?>

<main>
    <section class="accueil-intro section-profils">
        <h2>Profils des étudiants</h2>
        <p class="message-cv">
            Utilisez les filtres pour retrouver rapidement les étudiants selon leur projet :
            stage, alternance, CDI ou mobilité internationale.
        </p>

        <div class="filtres-profils">
            <input type="text" id="search-input" placeholder="Rechercher un étudiant...">
            <select id="domaine-select">
                <option value="">Tous les domaines</option>
                <option value="stage">Stage</option>
                <option value="alternance">Alternance</option>
                <option value="cdi">CDI</option>
                <option value="mobilite">Mobilité</option>
            </select>
            <button type="button" onclick="chargerProfils(1)">Rechercher</button>
        </div>

        <div id="grille-profils"></div>

        <div class="pagination-profils">
            <button type="button" id="btn-prev" onclick="changerPage(-1)" hidden>Précédent</button>
            <span id="page-info"></span>
            <button type="button" id="btn-next" onclick="changerPage(1)" hidden>Suivant</button>
        </div>
    </section>

    <section class="accueil-intro aide-catalogue">
        <h2>Lecture des profils</h2>
        <div class="etapes-plateforme">
            <article>
                <strong>Filtrer</strong>
                <p>Combinez recherche texte et domaine pour réduire la liste.</p>
            </article>
            <article>
                <strong>Consulter</strong>
                <p>Chaque carte présente l'identité, la biographie courte et les domaines.</p>
            </article>
            <article>
                <strong>Convoquer</strong>
                <p>Le bouton est actif après connexion avec un compte entreprise.</p>
            </article>
        </div>
    </section>
</main>

<?php require __DIR__ . '/../inc/footer.php'; ?>
