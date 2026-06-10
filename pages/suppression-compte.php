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
            Cette action supprime définitivement votre compte étudiant, votre CV,
            vos expériences, formations, compétences et convocations associées.
        </p>
        <form id="form-suppression-compte">
            <label for="email">Email du compte</label>
            <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($_SESSION['email'] ?? '', ENT_QUOTES, 'UTF-8'); ?>" readonly required>

            <label for="password">Mot de passe</label>
            <input type="password" id="password" name="password" autocomplete="current-password" required>

            <label for="confirmation">
                <input type="checkbox" id="confirmation" name="confirmation" required>
                Je confirme vouloir supprimer définitivement mon compte et mes données.
            </label>

            <div class="actions-ligne" style="margin-top:1rem">
                <button type="submit">Supprimer mon compte</button>
            </div>
            <p id="suppression-message" class="message-cv" aria-live="polite"></p>
        </form>
    </section>
</main>

<?php require __DIR__ . '/../inc/footer.php'; ?>
