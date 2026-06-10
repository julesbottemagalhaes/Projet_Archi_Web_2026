<?php
require __DIR__ . '/../inc/db.php';

$page = max(1, filter_var($_GET['page'] ?? 1, FILTER_VALIDATE_INT) ?: 1);
$limit = 10;
$offset = ($page - 1) * $limit;

$filters = [
    'domaine' => strtolower(clean_string($_GET['domaine'] ?? '', 50)),
    'search' => clean_string($_GET['search'] ?? '', 100),
    'competence' => clean_string($_GET['competence'] ?? '', 100),
    'ecole' => clean_string($_GET['ecole'] ?? '', 100),
];

$query = 'SELECT DISTINCT e.id, e.nom, e.titre, e.biographie, e.photo, e.domaines_recherche
          FROM etudiants e';
$joins = [];
$where = ['1 = 1'];
$params = [];
$types = '';

if ($filters['competence'] !== '') {
    $joins[] = 'LEFT JOIN competences c ON e.id = c.etudiant_id';
    $where[] = 'c.competence LIKE ?';
    $params[] = '%' . $filters['competence'] . '%';
    $types .= 's';
}

if ($filters['ecole'] !== '') {
    $joins[] = 'LEFT JOIN formations f ON e.id = f.etudiant_id';
    $where[] = '(f.ecole LIKE ? OR f.diplome LIKE ? OR e.titre LIKE ?)';
    $params[] = '%' . $filters['ecole'] . '%';
    $params[] = '%' . $filters['ecole'] . '%';
    $params[] = '%' . $filters['ecole'] . '%';
    $types .= 'sss';
}

if ($filters['domaine'] !== '' && in_array($filters['domaine'], allowed_cv_domaines(), true)) {
    $where[] = 'e.domaines_recherche LIKE ?';
    $params[] = '%"' . $filters['domaine'] . '"%';
    $types .= 's';
}

if ($filters['search'] !== '') {
    $where[] = '(e.nom LIKE ? OR e.titre LIKE ? OR e.biographie LIKE ?)';
    $params[] = '%' . $filters['search'] . '%';
    $params[] = '%' . $filters['search'] . '%';
    $params[] = '%' . $filters['search'] . '%';
    $types .= 'sss';
}

if ($joins) {
    $query .= ' ' . implode(' ', array_unique($joins));
}

$query .= ' WHERE ' . implode(' AND ', $where);
$query .= ' ORDER BY e.id DESC LIMIT ? OFFSET ?';
$params[] = $limit;
$params[] = $offset;
$types .= 'ii';

$stmt = $connection->prepare($query);

if (!$stmt) {
    json_response(['error' => 'Requête catalogue invalide.'], 500);
}

$stmt->bind_param($types, ...$params);
$stmt->execute();
$result = $stmt->get_result();

$profiles = [];

while ($row = $result->fetch_assoc()) {
    $bio = clean_string($row['biographie'] ?? '', 180);
    if (strlen((string) ($row['biographie'] ?? '')) > 180) {
        $bio .= '...';
    }

    $profiles[] = [
        'id' => (int) $row['id'],
        'nom' => $row['nom'],
        'titre' => $row['titre'] ?? '',
        'biographie' => $bio,
        'photo' => $row['photo'] ?: null,
        'domaines' => normalise_cv_domaines($row['domaines_recherche'] ?? []),
    ];
}

$stmt->close();

json_response($profiles);
