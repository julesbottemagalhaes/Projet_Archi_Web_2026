<?php
$pageTitle = 'Accueil — CV JUNIA';
$metaDescription = 'Accueil de la plateforme CV JUNIA pour consulter les profils étudiants ou créer un CV.';
$bodyClass = 'page-simple page-accueil';
$dataPage = 'home';
$currentPage = 'accueil';
$headerKicker = 'TP CV';
$headerTitle = 'CV JUNIA';
$headerSubtitle = "Consulter l'exemple, créer une version personnalisée ou parcourir les profils.";

require __DIR__ . '/inc/header.php';
?>

<main>
    <section class="accueil-intro" id="section-connexion">
        <h2>Connexion</h2>
        <div id="connexion-contenu"></div>
    </section>

    <section class="accueil-intro">
        <h2>Que voulez-vous faire ?</h2>
        <p id="etat-cv" class="message-cv">Le CV d'exemple est disponible par défaut.</p>

        <div class="choix-accueil">
            <a class="choix" href="<?php echo htmlspecialchars($assetBase . '/pages/detail-profil.php', ENT_QUOTES, 'UTF-8'); ?>">
                <strong>Voir le CV</strong>
                <span>Afficher le CV d'exemple ou le dernier CV enregistré.</span>
            </a>

            <a class="choix" href="<?php echo htmlspecialchars($assetBase . '/pages/modifier-profil.php', ENT_QUOTES, 'UTF-8'); ?>">
                <strong>Créer un CV</strong>
                <span>Remplir les informations, les sections et les liens.</span>
            </a>
        </div>
    </section>

    <section class="accueil-intro">
        <h2>Catalogue étudiants</h2>
        <p>
            Les entreprises partenaires peuvent consulter les profils étudiants,
            filtrer les recherches et envoyer une convocation depuis le catalogue.
        </p>
        <div class="actions-ligne">
            <a class="bouton" href="<?php echo htmlspecialchars($assetBase . '/pages/catalogue.php', ENT_QUOTES, 'UTF-8'); ?>">Voir le catalogue</a>
            <a class="bouton bouton-secondaire" href="<?php echo htmlspecialchars($assetBase . '/pages/contact.php', ENT_QUOTES, 'UTF-8'); ?>">Devenir partenaire</a>
        </div>
    </section>
</main>

<?php require __DIR__ . '/inc/footer.php'; ?>
