<?php
$pageTitle = 'Profils — CV JUNIA';
$metaDescription = 'Modération des profils étudiants CV JUNIA.';
$bodyClass = 'page-simple page-accueil';
$dataPage = 'admin-profiles';
$currentPage = 'catalogue';
$headerKicker = 'Administration';
$headerTitle = 'Profils étudiants';
$headerSubtitle = 'Contrôle et modération des profils publiés.';

require __DIR__ . '/../../inc/header.php';
?>

<main>
    <section class="accueil-intro">
        <h2>Profils à modérer</h2>
        <p class="message-cv">Cette page reprendra les profils enregistrés dans la base de données.</p>
        <div class="tableau-simple">
            <table>
                <thead>
                    <tr>
                        <th>Étudiant</th>
                        <th>Domaine</th>
                        <th>État</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>Keanu GAUTHIER</td>
                        <td>Stage, alternance</td>
                        <td>Publié</td>
                    </tr>
                    <tr>
                        <td>Alice DUPONT</td>
                        <td>Stage, CDI</td>
                        <td>À relire</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </section>
</main>

<?php require __DIR__ . '/../../inc/footer.php'; ?>
