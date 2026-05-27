<?php
$pageTitle = 'Mentions légales — CV JUNIA';
$metaDescription = 'Mentions légales et informations RGPD de la plateforme CV JUNIA.';
$bodyClass = 'page-simple page-accueil';
$dataPage = 'legal';
$currentPage = 'accueil';
$headerKicker = 'Informations';
$headerTitle = 'Mentions légales';
$headerSubtitle = "Informations sur la collecte et l'utilisation des données.";

require __DIR__ . '/../inc/header.php';
?>

<main>
    <section class="accueil-intro">
        <h2>Données personnelles</h2>
        <p>
            Les informations renseignées servent à créer un profil étudiant consultable
            par les entreprises partenaires de JUNIA dans le cadre du projet pédagogique.
        </p>
        <p>
            Chaque utilisateur peut demander la suppression de son compte et des données
            associées depuis la page dédiée.
        </p>
    </section>

    <section class="accueil-intro">
        <h2>Utilisation prévue</h2>
        <ul class="liste-simple">
            <li>Création d'un CV étudiant standardisé.</li>
            <li>Consultation des profils par les entreprises partenaires.</li>
            <li>Gestion des demandes et convocations dans un cadre pédagogique.</li>
        </ul>
    </section>
</main>

<?php require __DIR__ . '/../inc/footer.php'; ?>
