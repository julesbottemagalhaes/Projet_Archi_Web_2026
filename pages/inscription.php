<?php
$pageTitle = 'Inscription — CV JUNIA';
$metaDescription = 'Création de compte étudiant pour la plateforme CV JUNIA.';
$bodyClass = 'page-simple page-formulaire';
$dataPage = 'register';
$currentPage = 'connexion';
$headerKicker = 'Étudiants';
$headerTitle = 'Créer un compte';
$headerSubtitle = 'Préparez votre accès étudiant avec une adresse JUNIA.';

require __DIR__ . '/../inc/header.php';
?>

<main>
    <section class="accueil-intro">
        <h2>Inscription étudiant</h2>
        <form>
            <div class="grille-formulaire">
                <div>
                    <label for="prenom">Prénom</label>
                    <input type="text" id="prenom" name="prenom" autocomplete="given-name" required>
                </div>
                <div>
                    <label for="nom">Nom</label>
                    <input type="text" id="nom" name="nom" autocomplete="family-name" required>
                </div>
                <div>
                    <label for="email">Email JUNIA</label>
                    <input type="email" id="email" name="email" placeholder="prenom.nom@junia.com" required>
                </div>
                <div>
                    <label for="password">Mot de passe</label>
                    <input type="password" id="password" name="password" required>
                </div>
            </div>

            <label>
                <input type="checkbox" name="consentement" required>
                J'accepte que mes informations soient utilisées pour créer mon profil CV JUNIA.
            </label>

            <div class="actions-ligne" style="margin-top:1rem">
                <button type="submit">Créer le compte</button>
                <a class="bouton bouton-secondaire" href="<?php echo htmlspecialchars($assetBase . '/pages/connexion.php', ENT_QUOTES, 'UTF-8'); ?>">Déjà inscrit</a>
            </div>
        </form>
    </section>
</main>

<?php require __DIR__ . '/../inc/footer.php'; ?>
