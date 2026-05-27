<?php
$scriptDirectory = str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'] ?? '/index.php'));
$baseUrl = preg_replace('#/(pages|api|inc)(/.*)?$#', '', $scriptDirectory);
$baseUrl = rtrim($baseUrl, '/');
$currentYear = date('Y');
?>
    </main>

    <footer class="site-footer">
        <div class="footer-inner">
            <section class="footer-brand" aria-label="Présentation">
                <a class="brand brand-footer" href="<?php echo htmlspecialchars($baseUrl . '/index.php', ENT_QUOTES, 'UTF-8'); ?>">
                    <span class="brand-mark">J</span>
                    <span class="brand-text">
                        <strong>JUNIA</strong>
                        <span>CV Hub</span>
                    </span>
                </a>
                <p>
                    Une plateforme simple pour rapprocher les étudiants JUNIA et les entreprises partenaires.
                </p>
            </section>

            <nav class="footer-links" aria-label="Liens utiles">
                <h2>Plateforme</h2>
                <a href="<?php echo htmlspecialchars($baseUrl . '/pages/catalogue.php', ENT_QUOTES, 'UTF-8'); ?>">Catalogue des profils</a>
                <a href="<?php echo htmlspecialchars($baseUrl . '/pages/profil-etudiant.php', ENT_QUOTES, 'UTF-8'); ?>">Espace étudiant</a>
                <a href="<?php echo htmlspecialchars($baseUrl . '/pages/contact.php', ENT_QUOTES, 'UTF-8'); ?>">Contact</a>
            </nav>

            <nav class="footer-links" aria-label="Informations légales">
                <h2>Informations</h2>
                <a href="<?php echo htmlspecialchars($baseUrl . '/pages/mentions-legales.php', ENT_QUOTES, 'UTF-8'); ?>">Mentions légales</a>
                <a href="<?php echo htmlspecialchars($baseUrl . '/pages/suppression-compte.php', ENT_QUOTES, 'UTF-8'); ?>">Suppression de compte</a>
            </nav>
        </div>

        <div class="footer-bottom">
            <p>&copy; <?php echo $currentYear; ?> JUNIA CV Hub. Projet pédagogique.</p>
        </div>
    </footer>
</body>
</html>
