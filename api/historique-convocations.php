<?php
require __DIR__ . '/../inc/db.php';

require_api_role(['company', 'admin']);

$role = current_user_role();
$companyId = (int) ($_SESSION['user_id'] ?? 0);

$query = 'SELECT c.id, c.type_contrat, c.date_rdv, c.heure_rdv, c.lieu, c.statut,
                 e.nom AS etudiant_nom, c.etudiant_id, en.nom AS entreprise_nom
          FROM convocations c
          JOIN etudiants e ON c.etudiant_id = e.id
          JOIN entreprises en ON c.entreprise_id = en.id';

if ($role === 'company') {
    $query .= ' WHERE c.entreprise_id = ?';
}

$query .= ' ORDER BY c.date_convocation DESC';

$stmt = $connection->prepare($query);

if ($role === 'company') {
    $stmt->bind_param('i', $companyId);
}

$stmt->execute();
$result = $stmt->get_result();
$convocations = [];

while ($row = $result->fetch_assoc()) {
    $convocations[] = [
        'id' => (int) $row['id'],
        'type_contrat' => $row['type_contrat'],
        'date_rdv' => $row['date_rdv'],
        'heure_rdv' => substr((string) $row['heure_rdv'], 0, 5),
        'lieu' => $row['lieu'],
        'statut' => $row['statut'],
        'etudiant_nom' => $row['etudiant_nom'],
        'etudiant_id' => (int) $row['etudiant_id'],
        'entreprise_nom' => $row['entreprise_nom'],
    ];
}

$stmt->close();

json_response($convocations);
