<?php
$requiredRoles = ['company', 'admin'];
require __DIR__ . '/../inc/auth-check.php';

$pageTitle = 'Historique — CV JUNIA';
$metaDescription = 'Historique des convocations envoyées depuis la plateforme CV JUNIA.';
$bodyClass = 'page-simple page-accueil';
$dataPage = 'history';
$currentPage = 'catalogue';
$headerKicker = 'Entreprises';
$headerTitle = 'Historique des convocations';
$headerSubtitle = 'Retrouvez les profils déjà contactés.';

require __DIR__ . '/../inc/header.php';
?>

<main>
    <section class="accueil-intro">
        <h2>Convocations</h2>
        <p class="message-cv">L'historique sera alimenté après connexion d'une entreprise partenaire.</p>
        <div class="tableau-simple">
            <table>
                <thead>
                    <tr>
                        <th>Étudiant</th>
                        <th>Contrat</th>
                        <th>Statut</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>Keanu GAUTHIER</td>
                        <td>Stage</td>
                        <td>En attente</td>
                    </tr>
                    <tr>
                        <td>Alice DUPONT</td>
                        <td>Alternance</td>
                        <td>Envoyée</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </section>
</main>

<?php require __DIR__ . '/../inc/footer.php'; ?>
