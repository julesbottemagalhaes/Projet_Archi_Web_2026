<?php
$requiredRoles = ['admin'];
require __DIR__ . '/../../inc/auth-check.php';

$pageTitle = 'Entreprises — CV JUNIA';
$metaDescription = 'Gestion des entreprises partenaires de la plateforme CV JUNIA.';
$bodyClass = 'page-simple page-accueil';
$dataPage = 'admin-companies';
$currentPage = 'accueil';
$headerKicker = 'Administration';
$headerTitle = 'Entreprises';
$headerSubtitle = 'Création et suivi des comptes partenaires.';

require __DIR__ . '/../../inc/header.php';
?>

<main>
    <section class="accueil-intro">
        <h2>Entreprises partenaires</h2>
        <p class="message-cv">Les comptes entreprises seront créés manuellement par l'administration.</p>
        <div class="tableau-simple">
            <table>
                <thead>
                    <tr>
                        <th>Nom</th>
                        <th>Email</th>
                        <th>Date de création</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody id="tbody-utilisateurs">
                    <!-- JS -->
                </tbody>
            </table>
        </div>
    </section>
</main>

<?php require __DIR__ . '/../../inc/footer.php'; ?>
