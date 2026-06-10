<?php
require __DIR__ . '/../inc/db.php';

header("Content-Type: application/json");

$page = max(1, intval($_GET['page'] ?? 1));
$limit = 10;
$offset = ($page - 1) * $limit;
$domaine = $_GET['domaine'] ?? '';
$search = $_GET['search'] ?? '';
$competence = $_GET['competence'] ?? '';
$ecole = $_GET['ecole'] ?? '';

$query = "SELECT DISTINCT e.id, e.nom, e.titre, e.biographie, e.photo, e.domaines_recherche 
          FROM etudiants e ";
if (!empty($competence)) {
    $query .= " LEFT JOIN competences c ON e.id = c.etudiant_id ";
}
if (!empty($ecole)) {
    $query .= " LEFT JOIN formations f ON e.id = f.etudiant_id ";
}
$query .= " WHERE 1=1 ";

$params = [];
$types = "";

if (!empty($domaine)) {
    $query .= " AND e.domaines_recherche LIKE ? ";
    $params[] = "%$domaine%";
    $types .= "s";
}
if (!empty($search)) {
    $query .= " AND (e.nom LIKE ? OR e.biographie LIKE ?) ";
    $params[] = "%$search%";
    $params[] = "%$search%";
    $types .= "ss";
}
if (!empty($competence)) {
    $query .= " AND c.competence LIKE ? ";
    $params[] = "%$competence%";
    $types .= "s";
}
if (!empty($ecole)) {
    $query .= " AND (f.ecole LIKE ? OR e.titre LIKE ?) ";
    $params[] = "%$ecole%";
    $params[] = "%$ecole%";
    $types .= "ss";
}

$query .= " LIMIT ? OFFSET ?";
$params[] = $limit;
$params[] = $offset;
$types .= "ii";

$stmt = $connection->prepare($query);
if (!empty($params)) {
    $stmt->bind_param($types, ...$params);
}

$stmt->execute();
$result = $stmt->get_result();
$profils = [];

while ($row = $result->fetch_assoc()) {
    $bio = $row['biographie'] ?? '';
    $profils[] = [
        "id" => $row['id'],
        "nom" => $row['nom'],
        "titre" => $row['titre'] ?? '',
        "biographie" => mb_strlen($bio) > 150 ? mb_substr($bio, 0, 150) . '...' : $bio,
        "photo" => $row['photo'] ? '/uploads/photos/' . $row['photo'] : null,
        "domaines" => json_decode($row['domaines_recherche'] ?? '[]') ?? []
    ];
}

echo json_encode($profils);
$stmt->close();
?>
