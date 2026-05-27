<?php
$pageTitle = 'Accueil';
$activePage = 'accueil';
$bodyClass = 'home-page';

require_once __DIR__ . '/inc/header.php';
?>

<section class="home-hero" aria-labelledby="home-title">
    <div class="home-hero-inner">
        <div class="home-hero-content">
            <p class="eyebrow">Plateforme carrières JUNIA</p>
            <h1 id="home-title">JUNIA CV Hub</h1>
            <p class="lead">
                Une plateforme web pour centraliser les CV des étudiants JUNIA,
                faciliter la recherche de profils et simplifier les prises de contact
                avec les entreprises partenaires.
            </p>

            <div class="hero-actions">
                <a class="btn btn-primary" href="<?php echo htmlspecialchars($baseUrl . '/pages/inscription.php', ENT_QUOTES, 'UTF-8'); ?>">
                    Créer mon compte
                </a>
                <a class="btn btn-ghost" href="<?php echo htmlspecialchars($baseUrl . '/pages/connexion.php', ENT_QUOTES, 'UTF-8'); ?>">
                    Me connecter
                </a>
            </div>

            <div class="hero-points" aria-label="Fonctionnalités principales">
                <span>CV standardisé</span>
                <span>Catalogue filtrable</span>
                <span>Convocations suivies</span>
            </div>
        </div>

        <aside class="hero-preview" aria-label="Aperçu de la plateforme">
            <div class="preview-topbar">
                <span></span>
                <span></span>
                <span></span>
            </div>

            <div class="preview-search">
                <strong>Catalogue étudiants</strong>
                <span>36 profils actifs</span>
            </div>

            <div class="preview-filters">
                <span class="tag">Stage</span>
                <span class="tag">Alternance</span>
                <span class="tag">Data</span>
            </div>

            <article class="preview-profile">
                <div class="preview-avatar">AM</div>
                <div>
                    <h2>Amélie Martin</h2>
                    <p>Cycle ingénieur - Informatique</p>
                </div>
                <span class="badge badge-success">Disponible</span>
            </article>

            <article class="preview-profile">
                <div class="preview-avatar preview-avatar-orange">NR</div>
                <div>
                    <h2>Noah Richard</h2>
                    <p>Smart Systems - Alternance</p>
                </div>
                <span class="badge badge-warning">Entretien</span>
            </article>

            <div class="preview-note">
                <strong>Convocation envoyée</strong>
                <p>Le candidat reçoit les informations de l'entreprise et la date proposée.</p>
            </div>
        </aside>
    </div>
</section>

<section class="section section-muted" aria-labelledby="features-title">
    <div class="page-container">
        <div class="section-heading">
            <p class="eyebrow">Objectif du projet</p>
            <h2 id="features-title">Un outil clair pour les étudiants et les entreprises</h2>
        </div>

        <div class="grid grid-3">
            <article class="card feature-card">
                <div class="card-body">
                    <span class="feature-number">01</span>
                    <h3>Créer un profil complet</h3>
                    <p class="card-text">
                        Les étudiants renseignent leurs informations, leurs expériences,
                        leurs compétences et leurs objectifs de recherche.
                    </p>
                </div>
            </article>

            <article class="card feature-card">
                <div class="card-body">
                    <span class="feature-number">02</span>
                    <h3>Consulter le catalogue</h3>
                    <p class="card-text">
                        Les entreprises partenaires accèdent aux profils étudiants et
                        filtrent les résultats selon leurs besoins.
                    </p>
                </div>
            </article>

            <article class="card feature-card">
                <div class="card-body">
                    <span class="feature-number">03</span>
                    <h3>Convoquer un candidat</h3>
                    <p class="card-text">
                        Une entreprise peut proposer un entretien et garder un historique
                        des profils contactés depuis son espace.
                    </p>
                </div>
            </article>
        </div>
    </div>
</section>

<section class="section" aria-labelledby="audiences-title">
    <div class="page-container split-section">
        <div>
            <p class="eyebrow">Pour qui ?</p>
            <h2 id="audiences-title">Trois espaces, un même objectif</h2>
            <p class="lead">
                La plateforme relie les étudiants JUNIA, les entreprises partenaires
                et l'équipe administrative autour d'un parcours simple.
            </p>
        </div>

        <div class="audience-list">
            <article class="audience-item">
                <span>Étudiants</span>
                <p>Création et mise à jour du CV, choix des domaines de recherche.</p>
            </article>
            <article class="audience-item">
                <span>Entreprises</span>
                <p>Recherche de profils, consultation des fiches et convocations.</p>
            </article>
            <article class="audience-item">
                <span>Administration</span>
                <p>Gestion des comptes, validation des entreprises et modération.</p>
            </article>
        </div>
    </div>
</section>

<section class="section section-cta" aria-labelledby="cta-title">
    <div class="page-container cta-panel">
        <div>
            <p class="eyebrow">Démarrer</p>
            <h2 id="cta-title">Accéder à JUNIA CV Hub</h2>
            <p>
                Les étudiants peuvent créer leur profil. Les entreprises partenaires
                se connectent avec les identifiants fournis par JUNIA.
            </p>
        </div>
        <div class="cta-actions">
            <a class="btn btn-secondary" href="<?php echo htmlspecialchars($baseUrl . '/pages/inscription.php', ENT_QUOTES, 'UTF-8'); ?>">
                Inscription étudiant
            </a>
            <a class="btn btn-ghost" href="<?php echo htmlspecialchars($baseUrl . '/pages/connexion.php', ENT_QUOTES, 'UTF-8'); ?>">
                Connexion
            </a>
        </div>
    </div>
</section>

<?php require_once __DIR__ . '/inc/footer.php'; ?>
