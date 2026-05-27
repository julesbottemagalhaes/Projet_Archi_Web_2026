<?php
$siteName = 'JUNIA CV Hub';
$pageTitle = isset($pageTitle) && $pageTitle !== '' ? $pageTitle . ' | ' . $siteName : $siteName;
$bodyClass = $bodyClass ?? '';

$scriptDirectory = str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'] ?? '/index.php'));
$baseUrl = preg_replace('#/(pages|api|inc)(/.*)?$#', '', $scriptDirectory);
$baseUrl = rtrim($baseUrl, '/');

$currentFile = basename($_SERVER['SCRIPT_NAME'] ?? 'index.php');
$activePage = $activePage ?? match ($currentFile) {
    'catalogue.php' => 'catalogue',
    'contact.php' => 'contact',
    'connexion.php' => 'connexion',
    'inscription.php' => 'inscription',
    default => 'accueil',
};

$navItems = [
    ['label' => 'Accueil', 'href' => $baseUrl . '/index.php', 'key' => 'accueil'],
    ['label' => 'Catalogue', 'href' => $baseUrl . '/pages/catalogue.php', 'key' => 'catalogue'],
    ['label' => 'Contact', 'href' => $baseUrl . '/pages/contact.php', 'key' => 'contact'],
];

$pageTitleEscaped = htmlspecialchars($pageTitle, ENT_QUOTES, 'UTF-8');
$bodyClassEscaped = htmlspecialchars($bodyClass, ENT_QUOTES, 'UTF-8');
?>
<!doctype html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Plateforme de consultation des CV étudiants JUNIA pour les entreprises partenaires.">
    <title><?php echo $pageTitleEscaped; ?></title>
    <link rel="stylesheet" href="<?php echo htmlspecialchars($baseUrl . '/css/style.css', ENT_QUOTES, 'UTF-8'); ?>">
    <link rel="stylesheet" href="<?php echo htmlspecialchars($baseUrl . '/css/responsive.css', ENT_QUOTES, 'UTF-8'); ?>">
</head>
<body class="site-body <?php echo $bodyClassEscaped; ?>">
    <a class="skip-link" href="#contenu">Aller au contenu</a>

    <header class="site-header">
        <div class="header-inner">
            <a class="brand" href="<?php echo htmlspecialchars($baseUrl . '/index.php', ENT_QUOTES, 'UTF-8'); ?>" aria-label="Retour à l'accueil JUNIA CV Hub">
                <span class="brand-mark">J</span>
                <span class="brand-text">
                    <strong>JUNIA</strong>
                    <span>CV Hub</span>
                </span>
            </a>

            <nav class="main-nav" aria-label="Navigation principale">
                <?php foreach ($navItems as $item): ?>
                    <?php $isActive = $activePage === $item['key']; ?>
                    <a
                        class="nav-link<?php echo $isActive ? ' is-active' : ''; ?>"
                        href="<?php echo htmlspecialchars($item['href'], ENT_QUOTES, 'UTF-8'); ?>"
                        <?php echo $isActive ? 'aria-current="page"' : ''; ?>
                    >
                        <?php echo htmlspecialchars($item['label'], ENT_QUOTES, 'UTF-8'); ?>
                    </a>
                <?php endforeach; ?>
            </nav>

            <div class="header-actions" aria-label="Accès rapides">
                <a class="btn btn-ghost" href="<?php echo htmlspecialchars($baseUrl . '/pages/connexion.php', ENT_QUOTES, 'UTF-8'); ?>">Connexion</a>
                <a class="btn btn-primary" href="<?php echo htmlspecialchars($baseUrl . '/pages/inscription.php', ENT_QUOTES, 'UTF-8'); ?>">Inscription</a>
            </div>
        </div>
    </header>

    <main id="contenu" class="site-main">
