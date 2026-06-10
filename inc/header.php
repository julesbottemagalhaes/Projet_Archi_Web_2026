<?php
require_once __DIR__ . '/functions.php';

start_secure_session();

$siteName = 'CV JUNIA';
$pageTitle = $pageTitle ?? $siteName;
$metaDescription = $metaDescription ?? 'Plateforme web JUNIA pour consulter et créer des CV étudiants.';
$bodyClass = $bodyClass ?? 'page-simple';
$dataPage = $dataPage ?? '';
$currentPage = $currentPage ?? 'accueil';
$headerKicker = $headerKicker ?? 'TP CV';
$headerTitle = $headerTitle ?? 'CV JUNIA';
$headerSubtitle = $headerSubtitle ?? 'Consulter les profils étudiants ou créer un CV personnalisé.';
$headerContact = $headerContact ?? [];

$scriptDirectory = str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'] ?? '/index.php'));
$baseUrl = preg_replace('#/(pages|api|inc)(/.*)?$#', '', $scriptDirectory);
$baseUrl = $baseUrl === '/' ? '' : rtrim($baseUrl, '/');
$assetBase = $baseUrl === '' ? '' : $baseUrl;

$currentUser = current_user();
$currentRole = $currentUser['role'] ?? null;
$profileHref = $assetBase . '/pages/detail-profil.php';

if ($currentRole === 'student') {
    $profileHref .= '?id=' . (int) $currentUser['id'];
}

$navItems = [
    'accueil' => ['label' => 'Accueil', 'href' => $assetBase . '/index.php'],
    'catalogue' => ['label' => 'Catalogue', 'href' => $assetBase . '/pages/catalogue.php'],
];

if ($currentRole === 'student') {
    $navItems['cv'] = ['label' => 'Mon CV', 'href' => $profileHref];
    $navItems['creer'] = ['label' => 'Modifier', 'href' => $assetBase . '/pages/modifier-profil.php'];
} elseif ($currentRole === 'company') {
    $navItems['history'] = ['label' => 'Convocations', 'href' => $assetBase . '/pages/historique-convocations.php'];
} elseif ($currentRole === 'admin') {
    $navItems['admin'] = ['label' => 'Admin', 'href' => $assetBase . '/pages/admin/dashboard.php'];
}

if (!$currentRole) {
    $navItems['register'] = ['label' => 'Inscription', 'href' => $assetBase . '/pages/inscription.php'];
    $navItems['connexion'] = ['label' => 'Connexion', 'href' => $assetBase . '/pages/connexion.php'];
}

$navItems['contact'] = ['label' => 'Contact', 'href' => $assetBase . '/pages/contact.php'];
$scriptUserJson = json_encode($currentUser, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_QUOT) ?: 'null';
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="<?php echo htmlspecialchars($metaDescription, ENT_QUOTES, 'UTF-8'); ?>">
    <title><?php echo htmlspecialchars($pageTitle, ENT_QUOTES, 'UTF-8'); ?></title>
    <link rel="stylesheet" href="<?php echo htmlspecialchars($assetBase . '/css/style.css', ENT_QUOTES, 'UTF-8'); ?>">
    <link rel="stylesheet" href="<?php echo htmlspecialchars($assetBase . '/css/responsive.css', ENT_QUOTES, 'UTF-8'); ?>">
    <script>
        window.JUNIA_API_BASE = "<?php echo htmlspecialchars($assetBase . '/api', ENT_QUOTES, 'UTF-8'); ?>";
        window.JUNIA_APP_BASE = "<?php echo htmlspecialchars($assetBase, ENT_QUOTES, 'UTF-8'); ?>";
        window.JUNIA_DEFAULT_PHOTO = "<?php echo htmlspecialchars($assetBase . '/uploads/photos/photo_profil.png', ENT_QUOTES, 'UTF-8'); ?>";
        window.JUNIA_CURRENT_USER = <?php echo $scriptUserJson; ?>;
        window.JUNIA_ROUTES = {
            accueil: "<?php echo htmlspecialchars($assetBase . '/index.php', ENT_QUOTES, 'UTF-8'); ?>",
            cv: "<?php echo htmlspecialchars($profileHref, ENT_QUOTES, 'UTF-8'); ?>",
            creer: "<?php echo htmlspecialchars($assetBase . '/pages/modifier-profil.php', ENT_QUOTES, 'UTF-8'); ?>",
            login: "<?php echo htmlspecialchars($assetBase . '/pages/connexion.php', ENT_QUOTES, 'UTF-8'); ?>",
            register: "<?php echo htmlspecialchars($assetBase . '/pages/inscription.php', ENT_QUOTES, 'UTF-8'); ?>",
            student: "<?php echo htmlspecialchars($assetBase . '/pages/profil-etudiant.php', ENT_QUOTES, 'UTF-8'); ?>",
            catalogue: "<?php echo htmlspecialchars($assetBase . '/pages/catalogue.php', ENT_QUOTES, 'UTF-8'); ?>",
            admin: "<?php echo htmlspecialchars($assetBase . '/pages/admin/dashboard.php', ENT_QUOTES, 'UTF-8'); ?>"
        };
    </script>
    <?php if (in_array($dataPage, ['home', 'login', 'register', 'delete-account'], true)): ?>
    <script src="<?php echo htmlspecialchars($assetBase . '/js/auth.js', ENT_QUOTES, 'UTF-8'); ?>" defer></script>
    <?php endif; ?>
    <?php if ($dataPage === 'create'): ?>
    <script src="<?php echo htmlspecialchars($assetBase . '/js/form-cv.js', ENT_QUOTES, 'UTF-8'); ?>" defer></script>
    <?php endif; ?>
    <?php if ($dataPage === 'catalogue'): ?>
    <script src="<?php echo htmlspecialchars($assetBase . '/js/catalogue.js', ENT_QUOTES, 'UTF-8'); ?>" defer></script>
    <?php endif; ?>
    <?php if ($dataPage === 'cv'): ?>
    <script src="<?php echo htmlspecialchars($assetBase . '/js/detail-profil.js', ENT_QUOTES, 'UTF-8'); ?>" defer></script>
    <script src="<?php echo htmlspecialchars($assetBase . '/js/convocation.js', ENT_QUOTES, 'UTF-8'); ?>" defer></script>
    <?php endif; ?>
    <?php if (strpos($dataPage, 'admin') !== false): ?>
    <script src="<?php echo htmlspecialchars($assetBase . '/js/admin.js', ENT_QUOTES, 'UTF-8'); ?>" defer></script>
    <?php endif; ?>
    <?php if ($dataPage === 'history'): ?>
    <script src="<?php echo htmlspecialchars($assetBase . '/js/convocation.js', ENT_QUOTES, 'UTF-8'); ?>" defer></script>
    <?php endif; ?>
    <?php if ($dataPage === 'contact'): ?>
    <script src="<?php echo htmlspecialchars($assetBase . '/js/contact.js', ENT_QUOTES, 'UTF-8'); ?>" defer></script>
    <?php endif; ?>
</head>
<body id="top" class="<?php echo htmlspecialchars($bodyClass, ENT_QUOTES, 'UTF-8'); ?>" data-page="<?php echo htmlspecialchars($dataPage, ENT_QUOTES, 'UTF-8'); ?>">
    <header>
        <div class="identite-header">
            <p class="sur-titre"><?php echo htmlspecialchars($headerKicker, ENT_QUOTES, 'UTF-8'); ?></p>
            <h1><?php echo htmlspecialchars($headerTitle, ENT_QUOTES, 'UTF-8'); ?></h1>
            <p><?php echo htmlspecialchars($headerSubtitle, ENT_QUOTES, 'UTF-8'); ?></p>
        </div>

        <div class="header-droite">
            <nav class="navigation" aria-label="Navigation principale">
                <?php foreach ($navItems as $key => $item): ?>
                    <a href="<?php echo htmlspecialchars($item['href'], ENT_QUOTES, 'UTF-8'); ?>"<?php echo $currentPage === $key ? ' aria-current="page"' : ''; ?>>
                        <?php echo htmlspecialchars($item['label'], ENT_QUOTES, 'UTF-8'); ?>
                    </a>
                <?php endforeach; ?>
            </nav>

            <?php if (!empty($headerContact)): ?>
                <ul id="cv-contact" class="contact" aria-label="Informations de contact">
                    <?php foreach ($headerContact as $contact): ?>
                        <li><?php echo $contact; ?></li>
                    <?php endforeach; ?>
                </ul>
            <?php endif; ?>
        </div>
    </header>
