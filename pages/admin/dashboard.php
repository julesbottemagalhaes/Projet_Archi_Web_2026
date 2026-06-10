<?php
$requiredRoles = ['admin'];
require __DIR__ . '/../../inc/auth-check.php';

$pageTitle = 'Administration — CV JUNIA';
$metaDescription = 'Tableau de bord administrateur CV JUNIA.';
$bodyClass = 'page-simple page-accueil';
$dataPage = 'admin';
$currentPage = 'accueil';
$headerKicker = 'Administration';
$headerTitle = 'Tableau de bord';
$headerSubtitle = "Vue d'ensemble des comptes, profils et demandes.";

require __DIR__ . '/../../inc/header.php';
?>

<main>
    <section class="accueil-intro">
        <h2>Administration</h2>
        <div class="stats-admin">
            <article>
                <strong>12</strong>
                <span>profils étudiants</span>
            </article>
            <article>
                <strong>4</strong>
                <span>entreprises</span>
            </article>
            <article>
                <strong>7</strong>
                <span>convocations</span>
            </article>
        </div>

        <div class="choix-accueil">
            <a class="choix" href="<?php echo htmlspecialchars($assetBase . '/pages/admin/utilisateurs.php', ENT_QUOTES, 'UTF-8'); ?>">
                <strong>Utilisateurs</strong>
                <span>Gérer les comptes étudiants et entreprises.</span>
            </a>
            <a class="choix" href="<?php echo htmlspecialchars($assetBase . '/pages/admin/entreprises.php', ENT_QUOTES, 'UTF-8'); ?>">
                <strong>Entreprises</strong>
                <span>Suivre les comptes partenaires.</span>
            </a>
            <a class="choix" href="<?php echo htmlspecialchars($assetBase . '/pages/admin/profils.php', ENT_QUOTES, 'UTF-8'); ?>">
                <strong>Profils</strong>
                <span>Consulter et modérer les profils étudiants.</span>
            </a>
            <a class="choix" href="<?php echo htmlspecialchars($assetBase . '/pages/admin/demandes-contact.php', ENT_QUOTES, 'UTF-8'); ?>">
                <strong>Demandes</strong>
                <span>Traiter les demandes des entreprises externes.</span>
            </a>
        </div>
    </section>
</main>

<?php require __DIR__ . '/../../inc/footer.php'; ?>
