<?php
$pageTitle = 'Utilisateurs — CV JUNIA';
$metaDescription = 'Gestion des utilisateurs de la plateforme CV JUNIA.';
$bodyClass = 'page-simple page-accueil';
$dataPage = 'admin-users';
$currentPage = 'accueil';
$headerKicker = 'Administration';
$headerTitle = 'Utilisateurs';
$headerSubtitle = 'Suivi des comptes étudiants et entreprises.';

require __DIR__ . '/../../inc/header.php';
?>

<main>
    <section class="accueil-intro">
        <h2>Comptes utilisateurs</h2>
        <p class="message-cv">La liste sera connectée à l'API d'administration.</p>
    </section>
</main>

<?php require __DIR__ . '/../../inc/footer.php'; ?>
