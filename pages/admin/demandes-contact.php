<?php
$pageTitle = 'Demandes de contact — CV JUNIA';
$metaDescription = 'Demandes reçues des entreprises non partenaires.';
$bodyClass = 'page-simple page-accueil';
$dataPage = 'admin-contact';
$currentPage = 'accueil';
$headerKicker = 'Administration';
$headerTitle = 'Demandes de contact';
$headerSubtitle = 'Suivi des entreprises souhaitant rejoindre la plateforme.';

require __DIR__ . '/../../inc/header.php';
?>

<main>
    <section class="accueil-intro">
        <h2>Demandes entreprises</h2>
        <p class="message-cv">Les demandes envoyées depuis le formulaire de contact apparaîtront ici.</p>
        <div class="tableau-simple">
            <table>
                <thead>
                    <tr>
                        <th>Entreprise</th>
                        <th>Contact</th>
                        <th>Statut</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>DataLab</td>
                        <td>contact@datalab.fr</td>
                        <td>Nouvelle</td>
                    </tr>
                    <tr>
                        <td>GreenTech</td>
                        <td>rh@greentech.fr</td>
                        <td>À traiter</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </section>
</main>

<?php require __DIR__ . '/../../inc/footer.php'; ?>
