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
</main>

<?php require __DIR__ . '/../inc/footer.php'; ?>
