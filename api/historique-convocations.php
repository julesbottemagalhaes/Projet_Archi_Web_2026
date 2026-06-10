<?php
require __DIR__ . '/../inc/db.php';

header("Content-Type: application/json");

if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] != 'company') {
    http_response_code(403);
    echo json_encode(["error" => "Non autorisé"]);
    exit;
}

$entreprise_id = $_SESSION['user_id'];

$query = "SELECT c.id, c.type_contrat, c.date_rdv, c.heure_rdv, c.lieu, c.statut, e.nom as etudiant_nom, c.etudiant_id
          FROM convocations c
          JOIN etudiants e ON c.etudiant_id = e.id
          WHERE c.entreprise_id = ?
          ORDER BY c.date_convocation DESC";

$stmt = $connection->prepare($query);
$stmt->bind_param("i", $entreprise_id);
$stmt->execute();
$result = $stmt->get_result();

$convocations = [];
while ($row = $result->fetch_assoc()) {
    $convocations[] = $row;
}

echo json_encode($convocations);
$stmt->close();
?>
