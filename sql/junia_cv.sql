CREATE TABLE etudiants (
  id INT PRIMARY KEY AUTO_INCREMENT,
  nom VARCHAR(200) NOT NULL,
  titre VARCHAR(200),
  email VARCHAR(100) UNIQUE NOT NULL,
  password_hash VARCHAR(255) NOT NULL,
  telephone VARCHAR(20),
  ville VARCHAR(100),
  date_naissance DATE,
  linkedin VARCHAR(255),
  github VARCHAR(255),
  photo VARCHAR(255),
  biographie TEXT,
  langues TEXT,
  projets TEXT,
  centres_interet TEXT,
  domaines_recherche VARCHAR(255),
  donnees_json MEDIUMTEXT,
  date_creation TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  date_modification TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE experiences (
  id INT PRIMARY KEY AUTO_INCREMENT,
  etudiant_id INT NOT NULL,
  entreprise VARCHAR(100),
  poste VARCHAR(100),
  date_debut DATE,
  date_fin DATE,
  description TEXT,
  FOREIGN KEY (etudiant_id) REFERENCES etudiants(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE formations (
  id INT PRIMARY KEY AUTO_INCREMENT,
  etudiant_id INT NOT NULL,
  ecole VARCHAR(100),
  diplome VARCHAR(100),
  date_fin DATE,
  description TEXT,
  FOREIGN KEY (etudiant_id) REFERENCES etudiants(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE competences (
  id INT PRIMARY KEY AUTO_INCREMENT,
  etudiant_id INT NOT NULL,
  competence VARCHAR(100),
  FOREIGN KEY (etudiant_id) REFERENCES etudiants(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE entreprises (
  id INT PRIMARY KEY AUTO_INCREMENT,
  nom VARCHAR(100) NOT NULL,
  email_contact VARCHAR(100) UNIQUE NOT NULL,
  password_hash VARCHAR(255) NOT NULL,
  logo VARCHAR(255),
  secteur VARCHAR(100),
  date_creation TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE admins (
  id INT PRIMARY KEY AUTO_INCREMENT,
  nom VARCHAR(100) NOT NULL,
  email VARCHAR(100) UNIQUE NOT NULL,
  password_hash VARCHAR(255) NOT NULL,
  date_creation TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE convocations (
  id INT PRIMARY KEY AUTO_INCREMENT,
  etudiant_id INT NOT NULL,
  entreprise_id INT NOT NULL,
  type_contrat VARCHAR(50),
  message TEXT,
  date_rdv DATE,
  heure_rdv TIME,
  lieu VARCHAR(255),
  date_convocation TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  statut VARCHAR(20) DEFAULT 'en attente',
  FOREIGN KEY (etudiant_id) REFERENCES etudiants(id) ON DELETE CASCADE,
  FOREIGN KEY (entreprise_id) REFERENCES entreprises(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE demandes_contact (
  id INT PRIMARY KEY AUTO_INCREMENT,
  nom_entreprise VARCHAR(100) NOT NULL,
  email_contact VARCHAR(100) NOT NULL,
  message TEXT,
  date_demande TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  statut VARCHAR(20) DEFAULT 'en attente'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE INDEX idx_etudiant_email ON etudiants(email);
CREATE INDEX idx_entreprise_email ON entreprises(email_contact);
CREATE INDEX idx_admin_email ON admins(email);
CREATE INDEX idx_convocations_etudiant ON convocations(etudiant_id);
CREATE INDEX idx_convocations_entreprise ON convocations(entreprise_id);

INSERT INTO etudiants (nom, titre, email, password_hash, biographie, domaines_recherche) VALUES
('Keanu GAUTHIER', 'Étudiant ingénieur : 1re année JUNIA', 'keanu.gauthier@junia.com',
 '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
 'Étudiant ingénieur passionné par le développement web et les nouvelles technologies.',
 '["stage","alternance"]'),
('Alice DUPONT', 'Étudiante ingénieure : 2e année JUNIA', 'alice.dupont@junia.com',
 '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
 'Spécialisée en intelligence artificielle et traitement des données.',
 '["stage","cdi"]');

INSERT INTO entreprises (nom, email_contact, password_hash, secteur) VALUES
('TechCorp', 'contact@techcorp.fr',
 '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Informatique'),
('InnoSoft', 'recrutement@innosoft.fr',
 '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Logiciel');

INSERT INTO admins (nom, email, password_hash) VALUES
('Admin JUNIA', 'admin@junia.com',
 '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi');

INSERT INTO competences (etudiant_id, competence) VALUES
(1, 'Programmation'), (1, 'Gestion de projet'), (1, 'Développement web'),
(2, 'Intelligence artificielle'), (2, 'Python'), (2, 'Analyse de données');
