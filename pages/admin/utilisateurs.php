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
        <div class="filtres-profils" style="margin-bottom: 1rem;">
            <select id="filtre-type">
                <option value="etudiants">Étudiants</option>
                <option value="entreprises">Entreprises</option>
            </select>
        </div>
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
