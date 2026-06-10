INSERT INTO etudiants (nom, titre, email, password_hash, telephone, ville, date_naissance, linkedin, github, photo, biographie, langues, projets, centres_interet, domaines_recherche) VALUES

('Lucas MARTIN', 'Étudiant ingénieur : 2e année JUNIA', 'lucas.martin@junia.com',
 '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
 '06 11 22 33 44', 'Lille', '2003-04-12',
 'https://www.linkedin.com/in/lucas-martin', 'https://github.com/lucasmartin',
 NULL,
 'Passionné de cybersécurité et de réseaux, je cherche à mettre mes compétences au service d\'entreprises innovantes.',
 '["Français : langue maternelle", "Anglais : courant (TOEIC 890)", "Espagnol : intermédiaire"]',
 '[{"titre":"Outil de détection d\'intrusion","dates":"2025","description":"Développement d\'un IDS en Python capable de détecter les attaques courantes sur un réseau local."},{"titre":"Application de gestion de mots de passe","dates":"2024","description":"Gestionnaire sécurisé avec chiffrement AES-256 et interface web React."}]',
 '["CTF (Capture The Flag)", "Escalade", "Lecture technique"]',
 '["stage","alternance"]'),

('Sophie BERNARD', 'Étudiante ingénieure : 3e année JUNIA — Spécialité IA', 'sophie.bernard@junia.com',
 '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
 '06 22 33 44 55', 'Paris', '2002-08-25',
 'https://www.linkedin.com/in/sophie-bernard', 'https://github.com/sophiebernard',
 NULL,
 'Étudiante en dernière année spécialisée en intelligence artificielle. J\'ai développé plusieurs modèles de ML en contexte académique et industriel.',
 '["Français : langue maternelle", "Anglais : bilingue (IELTS 8.0)", "Mandarin : notions"]',
 '[{"titre":"Modèle de prédiction de churn","dates":"2025","description":"Modèle XGBoost atteignant 94% de précision sur un dataset client de 500k lignes."},{"titre":"Chatbot NLP","dates":"2024","description":"Assistant conversationnel basé sur BERT fine-tuné pour le support client."},{"titre":"Dashboard Analytics","dates":"2023","description":"Tableau de bord interactif avec Plotly Dash pour visualiser des KPIs business."}]',
 '["Tennis", "Piano", "Kaggle competitions"]',
 '["cdi","alternance"]'),

('Thomas LECLERC', 'Étudiant ingénieur : 1re année JUNIA', 'thomas.leclerc@junia.com',
 '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
 '06 33 44 55 66', 'Lyon', '2004-01-30',
 'https://www.linkedin.com/in/thomas-leclerc', 'https://github.com/thomasleclerc',
 NULL,
 'Étudiant ingénieur généraliste avec une forte appétence pour le développement web full-stack et le cloud.',
 '["Français : langue maternelle", "Anglais : courant", "Allemand : scolaire"]',
 '[{"titre":"Site e-commerce","dates":"2025","description":"Plateforme complète avec Next.js, Stripe et déploiement sur Vercel."},{"titre":"API REST météo","dates":"2024","description":"API en Node.js consommant OpenWeatherMap avec cache Redis."}]',
 '["Football", "Gaming", "Électronique DIY"]',
 '["stage"]'),

('Emma ROUSSEAU', 'Étudiante ingénieure : 2e année JUNIA — Spécialité Systèmes embarqués', 'emma.rousseau@junia.com',
 '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
 '06 44 55 66 77', 'Bordeaux', '2003-11-05',
 'https://www.linkedin.com/in/emma-rousseau', 'https://github.com/emmarousseau',
 NULL,
 'Passionnée par les systèmes embarqués et l\'IoT, je développe des solutions matérielles et logicielles pour des objets connectés.',
 '["Français : langue maternelle", "Anglais : courant", "Japonais : débutante"]',
 '[{"titre":"Station météo connectée","dates":"2025","description":"Capteurs Arduino + Raspberry Pi avec dashboard en temps réel via MQTT."},{"titre":"Drone autonome","dates":"2024","description":"Drone quadricoptère avec navigation autonome par vision par ordinateur (OpenCV)."}]',
 '["Robotique", "Photographie", "Randonnée"]',
 '["stage","alternance"]'),

('Hugo PETIT', 'Étudiant ingénieur : 3e année JUNIA — Spécialité Génie logiciel', 'hugo.petit@junia.com',
 '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
 '06 55 66 77 88', 'Nantes', '2002-03-18',
 'https://www.linkedin.com/in/hugo-petit', 'https://github.com/hugopetit',
 NULL,
 'Développeur full-stack avec 2 ans d\'expérience en alternance. Maîtrise des architectures microservices et des pratiques DevOps.',
 '["Français : langue maternelle", "Anglais : courant (TOEIC 920)", "Portugais : intermédiaire"]',
 '[{"titre":"Plateforme SaaS RH","dates":"2024 - 2025","description":"Application de gestion RH avec React, Spring Boot et PostgreSQL. 5000 utilisateurs actifs."},{"titre":"Pipeline CI/CD","dates":"2023","description":"Mise en place de pipelines GitHub Actions avec déploiement automatique sur AWS ECS."}]',
 '["Open source", "Trail running", "Musique (guitare)"]',
 '["cdi","mobilite"]'),

('Camille DURAND', 'Étudiante ingénieure : 2e année JUNIA — Spécialité Data Science', 'camille.durand@junia.com',
 '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
 '06 66 77 88 99', 'Strasbourg', '2003-07-22',
 'https://www.linkedin.com/in/camille-durand', 'https://github.com/camilldurand',
 NULL,
 'Data scientist en formation, je transforme des données brutes en insights actionnables. Passionnée par la visualisation et le storytelling data.',
 '["Français : langue maternelle", "Anglais : bilingue", "Espagnol : courant"]',
 '[{"titre":"Analyse des tendances Twitter","dates":"2025","description":"Pipeline de scraping et analyse de sentiment en temps réel avec Spark Streaming."},{"titre":"Prédiction des prix immobiliers","dates":"2024","description":"Régression gradient boosting avec feature engineering avancé. RMSE de 8%."}]',
 '["Yoga", "Cuisine du monde", "Bénévolat"]',
 '["stage","alternance"]'),

('Antoine LEROY', 'Étudiant ingénieur : 1re année JUNIA', 'antoine.leroy@junia.com',
 '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
 '06 77 88 99 00', 'Marseille', '2004-09-14',
 'https://www.linkedin.com/in/antoine-leroy', 'https://github.com/antoineleroy',
 NULL,
 'Étudiant motivé, passionné par les nouvelles technologies et l\'entrepreneuriat. Je cherche un stage pour valider mon projet professionnel dans le développement mobile.',
 '["Français : langue maternelle", "Anglais : courant", "Arabe : bilingue"]',
 '[{"titre":"Application de covoiturage","dates":"2025","description":"App Flutter avec backend Firebase, géolocalisation en temps réel et système de notation."},{"titre":"Jeu mobile 2D","dates":"2024","description":"Jeu de plateforme développé avec Unity et C#, publié sur Google Play."}]',
 '["Basket-ball", "Entrepreneuriat", "Voyages"]',
 '["stage"]'),

('Léa MOREAU', 'Étudiante ingénieure : 3e année JUNIA — Spécialité Cybersécurité', 'lea.moreau@junia.com',
 '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
 '06 88 99 00 11', 'Toulouse', '2002-12-03',
 'https://www.linkedin.com/in/lea-moreau', 'https://github.com/leamoreau',
 NULL,
 'Experte en sécurité offensive et défensive. Certifiée CEH, j\'ai réalisé plusieurs missions de pentest en entreprise pendant mon alternance.',
 '["Français : langue maternelle", "Anglais : bilingue (C2)", "Russe : intermédiaire"]',
 '[{"titre":"Plateforme de bug bounty interne","dates":"2024 - 2025","description":"Développement d\'une plateforme de signalement de vulnérabilités pour 3 entreprises clientes."},{"titre":"Outil d\'audit réseau","dates":"2023","description":"Scanner de vulnérabilités Python intégrant Nmap, Metasploit et reporting automatisé."}]',
 '["CTF", "Natation", "Domotique"]',
 '["cdi","alternance"]');

INSERT INTO entreprises (nom, email_contact, password_hash, secteur) VALUES
('DataVision', 'rh@datavision.fr',
 '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Data & IA'),
('CyberShield', 'recrutement@cybershield.fr',
 '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Cybersécurité'),
('CloudNine', 'jobs@cloudnine.io',
 '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Cloud & DevOps'),
('MobilePlus', 'carrieres@mobileplus.fr',
 '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Mobile & IoT');

INSERT INTO admins (nom, email, password_hash) VALUES
('Admin demo', 'admin.demo@junia.com',
 '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi');

INSERT INTO experiences (etudiant_id, entreprise, poste, date_debut, date_fin, description) VALUES
(3, 'Startup Nantes', 'Développeur web junior', '2024-06-01', '2024-08-31', 'Développement de fonctionnalités React et intégration d\'API REST.'),
(4, 'Airbus', 'Stagiaire systèmes embarqués', '2024-04-01', '2024-06-30', 'Développement firmware en C pour systèmes de contrôle avionique.'),
(5, 'Orange Business', 'Alternant développeur full-stack', '2023-09-01', '2025-08-31', 'Développement et maintenance d\'applications internes en React/Spring Boot.'),
(5, 'Freelance', 'Développeur web', '2022-07-01', '2023-08-31', 'Création de sites web pour des PME locales (WordPress, PHP, MySQL).'),
(6, 'INSEE', 'Stagiaire data analyst', '2024-06-01', '2024-08-31', 'Analyse de données socio-économiques et production de rapports statistiques avec R.'),
(8, 'Thales', 'Alternante cybersécurité', '2023-09-01', '2025-08-31', 'Réalisation de tests d\'intrusion et rédaction de rapports de sécurité.'),
(8, 'ANSSI', 'Stagiaire analyste', '2023-02-01', '2023-07-31', 'Analyse de malwares et participation à la réponse à incidents.');

INSERT INTO formations (etudiant_id, ecole, diplome, date_fin, description) VALUES
(3, 'JUNIA, Lille', 'Cycle ingénieur généraliste', '2027-06-30', 'Formation pluridisciplinaire avec spécialisation développement logiciel.'),
(3, 'Lycée Faidherbe, Lille', 'Baccalauréat général', '2023-06-30', 'Spécialités mathématiques et NSI. Mention très bien.'),
(4, 'JUNIA, Bordeaux', 'Cycle ingénieur généraliste', '2026-06-30', 'Spécialisation systèmes embarqués et IoT.'),
(5, 'JUNIA, Nantes', 'Cycle ingénieur génie logiciel', '2025-06-30', 'Formation en alternance, spécialisation architecture logicielle.'),
(5, 'IUT de Nantes', 'BUT Informatique', '2022-06-30', 'Parcours développement d\'applications communicantes. Major de promotion.'),
(6, 'JUNIA, Strasbourg', 'Cycle ingénieur Data Science', '2026-06-30', 'Double compétence mathématiques et informatique appliquée aux données.'),
(8, 'JUNIA, Toulouse', 'Cycle ingénieur cybersécurité', '2025-06-30', 'Spécialisation sécurité des systèmes d\'information, en alternance.'),
(8, 'Université Paul Sabatier', 'Licence Informatique', '2022-06-30', 'Bases solides en algorithmique, réseaux et systèmes d\'exploitation.');

INSERT INTO competences (etudiant_id, competence) VALUES
(3, 'Python'), (3, 'Cybersécurité'), (3, 'Réseaux'), (3, 'Linux'), (3, 'Wireshark'),
(4, 'Intelligence Artificielle'), (4, 'Machine Learning'), (4, 'Python'), (4, 'SQL'), (4, 'TensorFlow'), (4, 'Tableau'),
(5, 'JavaScript'), (5, 'TypeScript'), (5, 'React'), (5, 'Node.js'), (5, 'PHP'),
(6, 'JavaScript'), (6, 'React'), (6, 'Next.js'), (6, 'Docker'), (6, 'AWS'),
(7, 'React'), (7, 'Spring Boot'), (7, 'PostgreSQL'), (7, 'Docker'), (7, 'Kubernetes'), (7, 'CI/CD'),
(8, 'Python'), (8, 'R'), (8, 'SQL'), (8, 'Machine Learning'), (8, 'Spark'),
(9, 'Flutter'), (9, 'Dart'), (9, 'Firebase'), (9, 'Unity'), (9, 'C#'),
(10, 'Pentest'), (10, 'Python'), (10, 'Metasploit'), (10, 'Kali Linux'), (10, 'OSINT'), (10, 'Cryptographie');

INSERT INTO convocations (etudiant_id, entreprise_id, type_contrat, message, statut) VALUES
(1, 1, 'alternance', 'Votre profil correspond parfaitement à notre poste d\'alternant développeur web.', 'en attente'),
(2, 2, 'cdi', 'Nous avons un poste de data scientist senior qui pourrait vous correspondre.', 'accepté'),
(4, 3, 'stage', 'Nous cherchons un stagiaire passionné par l\'IA pour rejoindre notre équipe research.', 'en attente'),
(5, 4, 'stage', 'Nous recrutons pour un stage en développement d\'applications embarquées.', 'en attente'),
(7, 1, 'cdi', 'Votre expertise DevOps nous intéresse pour un poste en CDI dans notre cloud team.', 'accepté'),
(10, 3, 'alternance', 'Nous proposons une alternance en cybersécurité au sein de notre SOC.', 'en attente');
