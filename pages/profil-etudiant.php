<?php
$requiredRoles = ['student'];
require __DIR__ . '/../inc/auth-check.php';

$pageTitle = 'Espace étudiant — CV JUNIA';
$metaDescription = 'Espace étudiant pour consulter ou modifier son CV JUNIA.';
$bodyClass = 'page-simple page-accueil';
$dataPage = 'student';
$currentPage = 'cv';
$headerKicker = 'Espace étudiant';
$headerTitle = 'Mon profil';
$headerSubtitle = 'Retrouvez les actions principales pour gérer votre CV.';

require __DIR__ . '/../inc/header.php';
$studentProfileHref = $assetBase . '/pages/detail-profil.php?id=' . (int) ($_SESSION['user_id'] ?? 0);
?>

<main>
    <section class="accueil-intro">
        <h2>Mon espace CV</h2>
        <p>
            Depuis cet espace, un étudiant peut consulter le rendu de son CV,
            modifier ses informations ou charger les données enregistrées sur le serveur.
        </p>

        <div class="choix-accueil">
            <a class="choix" href="<?php echo htmlspecialchars($studentProfileHref, ENT_QUOTES, 'UTF-8'); ?>">
                <strong>Voir mon CV</strong>
                <span>Afficher le CV d'exemple ou le dernier CV enregistré.</span>
            </a>

            <a class="choix" href="<?php echo htmlspecialchars($assetBase . '/pages/modifier-profil.php', ENT_QUOTES, 'UTF-8'); ?>">
                <strong>Modifier mon CV</strong>
                <span>Mettre à jour les informations, expériences et compétences.</span>
            </a>
        </div>
    </section>
</main>

<?php require __DIR__ . '/../inc/footer.php'; ?>
