# Projet Archi Web 2026 - CV JUNIA

## 📖 Description du Projet
CV JUNIA est une plateforme web permettant la mise en relation entre les étudiants de l'école JUNIA et des entreprises partenaires. Les étudiants peuvent créer et afficher leur profil professionnel (CV), tandis que les entreprises peuvent consulter le catalogue des étudiants, effectuer des recherches via des filtres avancés, et convoquer des candidats pour des entretiens (stage, alternance, CDI). Une interface d'administration permet de gérer les comptes et de modérer la plateforme.

## 💻 Stack Technique
- **Front-end** : HTML5, CSS3 Vanilla (sans framework externe pour plus de contrôle), JavaScript (ES6+, utilisation intensive de `fetch` pour les appels AJAX).
- **Back-end** : PHP 8+ (Architecture API-first / end-points JSON).
- **Base de données** : MySQL / MariaDB avec requêtes préparées via l'extension `mysqli`.
- **Architecture** : Répartition MVC simplifiée (dossier `pages/` pour les vues, `api/` pour les contrôleurs/endpoints, `js/` pour le client, `inc/` pour les configurations globales et composants partagés).

## ⚙️ Installation et Déploiement
1. **Cloner le projet** : Clonez ce dépôt dans le répertoire de votre serveur web local (par exemple `C:/xampp/htdocs/Projet_Archi_Web_2026` pour XAMPP).
2. **Configuration BDD** : Éditez le fichier `inc/config.php` si nécessaire avec vos identifiants MySQL locaux.
   ```php
   define('DB_HOST', 'localhost');
   define('DB_USER', 'root');
   define('DB_PASS', '');
   define('DB_NAME', 'junia_cv');
   ```

## 🗄️ Import SQL et Schéma de Base de Données
1. Créez une base de données nommée `junia_cv` dans phpMyAdmin ou via votre terminal MySQL.
2. Importez le schéma initial : `sql/junia_cv.sql`
3. Importez les données de démonstration : `sql/seed.sql`

**Architecture du Schéma (Résumé)** :
- `etudiants` : Stocke les informations de base des profils.
- `competences`, `formations`, `experiences` : Tables associées (One-to-Many avec `etudiants`).
- `entreprises` : Comptes partenaires.
- `convocations` : Table de liaison gérant les entretiens (date, heure, lieu, message, statut) liés à un étudiant et une entreprise.
- `admins` : Table des administrateurs de la plateforme.
- `demandes_contact` : Stockage des formulaires de contact entreprise.

## 🔐 Comptes de Test
Le fichier `seed.sql` intègre par défaut plusieurs comptes pour la démonstration (mot de passe identique pour tous : `password`).
- **Administrateur** : `admin@junia.com` (MDP: `password`)
- **Entreprise** : `contact@techcorp.fr` (MDP: `password`)
- **Étudiant** : `lucas.martin@junia.com` (MDP: `password`)

## ✨ Fonctionnalités Réalisées
- [x] **Catalogue et Filtres dynamiques** : Recherche asynchrone par type de contrat, compétences, mot-clé, et école.
- [x] **Détail d'un Profil** : Affichage sécurisé d'un CV (informations masquées pour les visiteurs publics).
- [x] **Convocations** : Modale permettant à une entreprise d'envoyer une offre d'entretien avec date/lieu, et de retrouver tout l'historique sur son tableau de bord.
- [x] **Administration** : Espace sécurisé (Dashboard) avec statistiques en temps réel, modération des utilisateurs, et gestion des demandes de partenariat.
- [x] **Demande de Contact** : Formulaire asynchrone stockant les demandes en BDD, avec traitement ultérieur par les administrateurs.

## 👥 Membres de l'Équipe
- **Corentin Jamet** : Développeur principal

## 🎬 Script de Démonstration (Soutenance)
1. **Connexion Admin** : Se rendre sur `pages/connexion.php`, choisir le rôle "Administration", utiliser le compte `admin@junia.com`. Montrer le tableau de bord avec les statistiques dynamiques et la liste des demandes de contact.
2. **Formulaire Contact Public** : Ouvrir la page de contact public en navigation privée, soumettre une nouvelle demande, puis retourner sur l'admin pour montrer que la demande est apparue.
3. **Connexion Entreprise & Catalogue** : Se connecter en entreprise (`contact@techcorp.fr`). Naviguer sur le "Catalogue". Utiliser les filtres (ex: "Stage", "Python") pour isoler un étudiant.
4. **Convocation** : Cliquer sur le profil de l'étudiant. Les numéros et emails sont visibles. Cliquer sur "Convoquer", remplir la modale, soumettre.
5. **Historique** : Se rendre dans l'historique de l'entreprise pour montrer que la convocation a bien été sauvegardée.
