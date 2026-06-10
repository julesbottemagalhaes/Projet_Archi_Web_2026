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
                        <th>Date et Heure</th>
                        <th>Lieu</th>
                        <th>Statut</th>
                    </tr>
                </thead>
                <tbody id="tbody-historique">
                    <!-- Les données seront chargées via JS -->
                </tbody>
            </table>
        </div>
    </section>
</main>

<?php require __DIR__ . '/../inc/footer.php'; ?>
