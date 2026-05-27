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
        <p class="message-cv">
            Accédez à votre espace étudiant ou entreprise avec les identifiants fournis.
        </p>
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
        <h2>Fonctionnement</h2>
        <div class="etapes-plateforme">
            <article>
                <strong>1</strong>
                <h3>Créer son CV</h3>
                <p>Un étudiant complète un formulaire et obtient un CV standardisé.</p>
            </article>
            <article>
                <strong>2</strong>
                <h3>Publier son profil</h3>
                <p>Les informations utiles sont visibles dans le catalogue étudiant.</p>
            </article>
            <article>
                <strong>3</strong>
                <h3>Être contacté</h3>
                <p>Une entreprise partenaire peut proposer une convocation.</p>
            </article>
        </div>
    </section>

    <section class="accueil-intro">
        <h2>Catalogue étudiants</h2>
        <p>
            Les entreprises partenaires peuvent consulter les profils étudiants,
            filtrer les recherches et envoyer une convocation depuis le catalogue.
        </p>
        <div class="indicateurs-accueil" aria-label="Aperçu des fonctionnalités">
            <span>Recherche par domaine</span>
            <span>Profils consultables</span>
            <span>Convocations suivies</span>
        </div>
        <div class="actions-ligne">
            <a class="bouton" href="<?php echo htmlspecialchars($assetBase . '/pages/catalogue.php', ENT_QUOTES, 'UTF-8'); ?>">Voir le catalogue</a>
            <a class="bouton bouton-secondaire" href="<?php echo htmlspecialchars($assetBase . '/pages/contact.php', ENT_QUOTES, 'UTF-8'); ?>">Devenir partenaire</a>
        </div>
    </section>
</main>

<?php require __DIR__ . '/inc/footer.php'; ?>
