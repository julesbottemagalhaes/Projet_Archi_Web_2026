<?php
$requiredRoles = ['student'];
require __DIR__ . '/../inc/auth-check.php';

$pageTitle = 'Suppression de compte — CV JUNIA';
$metaDescription = 'Demande de suppression de compte et de données personnelles.';
$bodyClass = 'page-simple page-formulaire';
$dataPage = 'delete-account';
$currentPage = 'accueil';
$headerKicker = 'RGPD';
$headerTitle = 'Suppression de compte';
$headerSubtitle = 'Demander la suppression des informations associées à un compte.';

require __DIR__ . '/../inc/header.php';
?>

<main>
    <section class="accueil-intro">
        <h2>Demande de suppression</h2>
        <p class="message-cv">
            La demande sera traitée par l'administration JUNIA afin de supprimer
            le compte et les informations associées.
        </p>
        <form>
            <label for="email">Email du compte</label>
            <input type="email" id="email" name="email" required>

            <label for="message">Précision éventuelle</label>
            <textarea id="message" name="message" rows="5"></textarea>

            <div class="actions-ligne" style="margin-top:1rem">
                <button type="submit">Envoyer la demande</button>
            </div>
        </form>
    </section>
</main>

<?php require __DIR__ . '/../inc/footer.php'; ?>
