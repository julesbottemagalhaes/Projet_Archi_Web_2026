<?php
$requiredRoles = ['admin'];
require __DIR__ . '/../../inc/auth-check.php';

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
        <div class="tableau-simple">
            <table>
                <thead>
                    <tr>
                        <th>Nom</th>
                        <th>Type</th>
                        <th>Statut</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>Keanu GAUTHIER</td>
                        <td>Étudiant</td>
                        <td>Actif</td>
                    </tr>
                    <tr>
                        <td>TechCorp</td>
                        <td>Entreprise</td>
                        <td>Actif</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </section>
</main>

<?php require __DIR__ . '/../../inc/footer.php'; ?>
