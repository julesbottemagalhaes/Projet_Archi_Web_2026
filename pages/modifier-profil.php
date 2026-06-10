<?php
$requiredRoles = ['student'];
require __DIR__ . '/../inc/auth-check.php';

$pageTitle = 'Créer un CV — CV JUNIA';
$metaDescription = 'Formulaire de création et de modification du CV étudiant JUNIA.';
$bodyClass = 'page-simple page-formulaire';
$dataPage = 'create';
$currentPage = 'creer';
$headerKicker = 'Générateur';
$headerTitle = 'Créer un CV';
$headerSubtitle = 'Remplissez les champs, puis enregistrez pour afficher le CV.';

require __DIR__ . '/../inc/header.php';
?>

<main class="creation-cv">
    <form id="form-cv" class="formulaire-cv">
        <div class="intro-formulaire">
            <h2>Informations du CV</h2>
            <p>
                Les champs ci-dessous alimentent le CV visible par les entreprises partenaires.
                Gardez des intitulés courts et faciles à lire.
            </p>
        </div>

        <fieldset>
            <legend>Identité</legend>

            <div class="grille-formulaire">
                <div>
                    <label for="prenom">Prénom</label>
                    <input type="text" id="prenom" name="prenom" autocomplete="given-name" required>
                </div>

                <div>
                    <label for="nom">Nom</label>
                    <input type="text" id="nom" name="nom" autocomplete="family-name" required>
                </div>

                <div class="champ-large">
                    <label for="titre">Titre</label>
                    <input type="text" id="titre" name="titre" placeholder="Étudiant ingénieur : 1re année JUNIA" required>
                </div>

                <div>
                    <label for="email">Email JUNIA</label>
                    <input type="email" id="email" name="email" placeholder="prenom.nom@junia.com" autocomplete="email" aria-describedby="erreur-email" required>
                    <span class="erreur" id="erreur-email" aria-live="polite"></span>
                </div>

                <div>
                    <label for="telephone">Téléphone</label>
                    <input type="tel" id="telephone" name="telephone" placeholder="06 12 34 56 78" autocomplete="tel" inputmode="numeric" maxlength="14" pattern="[0-9]{2}( [0-9]{2}){4}" aria-describedby="erreur-telephone">
                    <span class="erreur" id="erreur-telephone" aria-live="polite"></span>
                </div>

                <div>
                    <label for="ville">Ville</label>
                    <input type="text" id="ville" name="ville" autocomplete="address-level2">
                </div>

                <div>
                    <label for="date_naissance">Date de naissance</label>
                    <input type="date" id="date_naissance" name="date_naissance">
                </div>

                <div>
                    <label for="linkedin">LinkedIn</label>
                    <input type="url" id="linkedin" name="linkedin" placeholder="https://www.linkedin.com/in/...">
                </div>

                <div>
                    <label for="github">GitHub</label>
                    <input type="url" id="github" name="github" placeholder="https://github.com/...">
                </div>

                <div class="champ-large">
                    <label for="photo">Photo</label>
                    <input type="hidden" id="photo" name="photo">
                    <input type="file" id="photo_upload" name="photo_upload" accept="image/jpeg,image/png">
                    <span class="message-cv" id="photo-actuelle">Formats acceptés : JPG ou PNG, 2 Mo maximum.</span>
                </div>
            </div>
        </fieldset>

        <fieldset>
            <legend>Recherche</legend>

            <div class="grille-formulaire">
                <label>
                    <input type="checkbox" name="domaines_recherche[]" value="stage">
                    Stage
                </label>
                <label>
                    <input type="checkbox" name="domaines_recherche[]" value="alternance">
                    Alternance
                </label>
                <label>
                    <input type="checkbox" name="domaines_recherche[]" value="cdi">
                    CDI
                </label>
                <label>
                    <input type="checkbox" name="domaines_recherche[]" value="mobilite">
                    Mobilité
                </label>
            </div>
        </fieldset>

        <fieldset>
            <legend>Contenu du CV</legend>

            <label for="profil">Profil</label>
            <textarea id="profil" name="profil" rows="4" maxlength="450"></textarea>
            <span class="compteur" id="compteur-profil">0 / 450 caractères</span>

            <div class="grille-formulaire">
                <div>
                    <label for="competences">Compétences</label>
                    <textarea id="competences" name="competences" rows="5" placeholder="Programmation&#10;Gestion de projet&#10;Analyse de données"></textarea>
                </div>

                <div>
                    <label for="langues">Langues</label>
                    <textarea id="langues" name="langues" rows="5" placeholder="Français : langue maternelle&#10;Anglais : courant"></textarea>
                </div>
            </div>

            <label for="formations">Formations</label>
            <textarea id="formations" name="formations" rows="5" placeholder="Cycle ingénieur généraliste - JUNIA, Bordeaux | Depuis 2025 | Formation pluridisciplinaire dans le numérique."></textarea>

            <label for="experiences">Expériences</label>
            <textarea id="experiences" name="experiences" rows="5" placeholder="Projet académique - Développement web | 2026 | Conception d'un site vitrine pour présenter mon CV."></textarea>

            <label for="projets">Projets</label>
            <textarea id="projets" name="projets" rows="5" placeholder="Site portfolio | 2026 | Réalisation d'un site personnel pour présenter mes projets et mon CV."></textarea>

            <label for="centres_interet">Centres d'intérêt</label>
            <textarea id="centres_interet" name="centres_interet" rows="4" placeholder="Surf&#10;Musculation&#10;IA"></textarea>
        </fieldset>

        <div class="form-actions">
            <button type="submit">Enregistrer et voir le CV</button>
            <button id="charger-exemple" class="bouton-secondaire" type="button">Charger l'exemple</button>
            <button id="effacer-brouillon" class="bouton-discret" type="button">Effacer</button>
        </div>
    </form>

    <section id="apercu-cv" class="apercu" aria-live="polite">
        <h2>Aperçu rapide</h2>
        <p class="message-cv">L'aperçu se met à jour pendant la saisie.</p>
        <div id="apercu-contenu"></div>
    </section>
</main>

<?php require __DIR__ . '/../inc/footer.php'; ?>
