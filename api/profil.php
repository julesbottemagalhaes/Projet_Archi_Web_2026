<?php
require_once __DIR__ . '/../inc/db.php';

$id = filter_var($_GET['id'] ?? null, FILTER_VALIDATE_INT);

if (!$id) {
    json_response(['error' => 'ID invalide'], 400);
}

$stmt = $connection->prepare(
    'SELECT id, nom, titre, email, telephone, ville, date_naissance,
            linkedin, github, photo, biographie, langues, projets,
            centres_interet, domaines_recherche, donnees_json
     FROM etudiants WHERE id = ?'
);
$stmt->bind_param('i', $id);
$stmt->execute();
$row = $stmt->get_result()->fetch_assoc();
$stmt->close();

if (!$row) {
    json_response(['error' => 'Profil non trouvé'], 404);
}

$storedCv = json_decode($row['donnees_json'] ?? '', true);
$storedCv = is_array($storedCv) ? $storedCv : [];

$stmt = $connection->prepare('SELECT competence FROM competences WHERE etudiant_id = ? ORDER BY id');
$stmt->bind_param('i', $id);
$stmt->execute();
$competences = array_column($stmt->get_result()->fetch_all(MYSQLI_ASSOC), 'competence');
$stmt->close();

$stmt = $connection->prepare(
    'SELECT entreprise, poste, date_debut, date_fin, description
     FROM experiences WHERE etudiant_id = ? ORDER BY COALESCE(date_fin, date_debut) DESC, id DESC'
);
$stmt->bind_param('i', $id);
$stmt->execute();
$experienceRows = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
$stmt->close();

$experiences = [];
foreach ($experienceRows as $experience) {
    $title = trim(($experience['entreprise'] ?? '') . (($experience['poste'] ?? '') ? ' - ' . $experience['poste'] : ''));
    $dates = '';

    if ($experience['date_debut']) {
        $dates = substr($experience['date_debut'], 0, 4);
    }

    if ($experience['date_fin']) {
        $dates .= ($dates ? ' - ' : '') . substr($experience['date_fin'], 0, 4);
    }

    $experiences[] = [
        'titre' => $title,
        'dates' => $dates,
        'description' => $experience['description'] ?? '',
    ];
}

$stmt = $connection->prepare(
    'SELECT ecole, diplome, date_fin, description
     FROM formations WHERE etudiant_id = ? ORDER BY date_fin DESC, id DESC'
);
$stmt->bind_param('i', $id);
$stmt->execute();
$formationRows = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
$stmt->close();

$formations = [];
foreach ($formationRows as $formation) {
    $title = trim(($formation['diplome'] ?? '') . (($formation['ecole'] ?? '') ? ' - ' . $formation['ecole'] : ''));
    $formations[] = [
        'titre' => $title,
        'dates' => $formation['date_fin'] ? substr($formation['date_fin'], 0, 4) : '',
        'description' => $formation['description'] ?? '',
    ];
}

$nameParts = explode(' ', $row['nom'], 2);
$role = current_user_role();
$isOwner = $role === 'student' && (int) ($_SESSION['user_id'] ?? 0) === (int) $id;
$canViewPrivate = $isOwner || in_array($role, ['company', 'admin'], true);

$profile = [
    'id' => (int) $row['id'],
    'prenom' => clean_string($storedCv['prenom'] ?? ($nameParts[0] ?? ''), 100),
    'nom' => clean_string($storedCv['nom'] ?? ($nameParts[1] ?? $row['nom']), 100),
    'titre' => clean_string($storedCv['titre'] ?? $row['titre'] ?? '', 200),
    'email' => $canViewPrivate ? clean_string($storedCv['email'] ?? $row['email'] ?? '', 100) : null,
    'telephone' => $canViewPrivate ? clean_string($storedCv['telephone'] ?? $row['telephone'] ?? '', 20) : null,
    'ville' => clean_string($storedCv['ville'] ?? $row['ville'] ?? '', 100),
    'dateNaissance' => $canViewPrivate ? clean_string($storedCv['dateNaissance'] ?? $row['date_naissance'] ?? '', 10) : null,
    'linkedin' => $canViewPrivate ? clean_string($storedCv['linkedin'] ?? $row['linkedin'] ?? '', 255) : null,
    'github' => clean_string($storedCv['github'] ?? $row['github'] ?? '', 255),
    'photo' => clean_string($row['photo'] ?? $storedCv['photo'] ?? '', 255),
    'profil' => clean_string($storedCv['profil'] ?? $row['biographie'] ?? '', 1200),
    'domainesRecherche' => normalise_cv_domaines($row['domaines_recherche'] ?? ($storedCv['domainesRecherche'] ?? [])),
    'competences' => !empty($storedCv['competences']) ? $storedCv['competences'] : $competences,
    'langues' => !empty($storedCv['langues']) ? $storedCv['langues'] : decode_json_array($row['langues'] ?? '[]'),
    'formations' => !empty($storedCv['formations']) ? $storedCv['formations'] : $formations,
    'experiences' => !empty($storedCv['experiences']) ? $storedCv['experiences'] : $experiences,
    'projets' => !empty($storedCv['projets']) ? $storedCv['projets'] : decode_json_array($row['projets'] ?? '[]'),
    'centresInteret' => !empty($storedCv['centresInteret']) ? $storedCv['centresInteret'] : decode_json_array($row['centres_interet'] ?? '[]'),
];

json_response($profile);
