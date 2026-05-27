<?php
$pageTitle = 'Contact — CV JUNIA';
$metaDescription = 'Formulaire de contact pour les entreprises souhaitant rejoindre la plateforme CV JUNIA.';
$bodyClass = 'page-simple page-formulaire';
$dataPage = 'contact';
$currentPage = 'catalogue';
$headerKicker = 'Partenaires';
$headerTitle = 'Contact';
$headerSubtitle = 'Une entreprise peut demander à rejoindre la plateforme.';

require __DIR__ . '/../inc/header.php';
?>

<main>
    <section class="accueil-intro">
        <h2>Demande entreprise</h2>
        <p class="message-cv">
            Ce formulaire permet à une entreprise non partenaire de présenter sa demande
            avant la création éventuelle d'un compte par l'administration.
        </p>
        <form>
            <div class="grille-formulaire">
                <div>
                    <label for="nom">Nom de l'entreprise</label>
                    <input type="text" id="nom" name="nom" required>
                </div>
                <div>
                    <label for="email">Email de contact</label>
                    <input type="email" id="email" name="email" required>
                </div>
                <div class="champ-large">
                    <label for="message">Message</label>
                    <textarea id="message" name="message" rows="6" required></textarea>
                </div>
            </div>
            <div class="actions-ligne" style="margin-top:1rem">
                <button type="submit">Envoyer la demande</button>
            </div>
        </form>
    </section>

    <section class="accueil-intro">
        <h2>Informations utiles</h2>
        <div class="etapes-plateforme">
            <article>
                <strong>Accès</strong>
                <p>Les identifiants entreprise sont fournis par JUNIA après validation.</p>
            </article>
            <article>
                <strong>Catalogue</strong>
                <p>L'entreprise peut ensuite consulter les profils disponibles.</p>
            </article>
            <article>
                <strong>Convocation</strong>
                <p>Les candidats peuvent être contactés depuis leur fiche profil.</p>
            </article>
        </div>
    </section>
</main>

<?php require __DIR__ . '/../inc/footer.php'; ?>
