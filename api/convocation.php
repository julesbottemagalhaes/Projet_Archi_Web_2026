<?php
require __DIR__ . '/../inc/db.php';

require_http_method('POST');
require_api_role('company');

$data = read_json_body();

$studentId = filter_var($data['etudiant_id'] ?? null, FILTER_VALIDATE_INT);
$companyId = (int) $_SESSION['user_id'];
$contractType = strtolower(clean_string($data['type_contrat'] ?? '', 50));
$message = clean_string($data['message'] ?? '', 1200);
$date = clean_string($data['date'] ?? '', 10);
$time = clean_string($data['heure'] ?? '', 8);
$location = clean_string($data['lieu'] ?? '', 255);

if (!$studentId || $contractType === '' || $date === '' || $time === '' || $location === '') {
    json_response(['error' => 'Tous les champs de convocation sont requis.'], 400);
}

if (!in_array($contractType, allowed_cv_domaines(), true)) {
    json_response(['error' => 'Type de contrat invalide.'], 400);
}

if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $date) || !preg_match('/^\d{2}:\d{2}$/', $time)) {
    json_response(['error' => 'Date ou heure invalide.'], 400);
}

$stmt = $connection->prepare('SELECT id, email FROM etudiants WHERE id = ?');
$stmt->bind_param('i', $studentId);
$stmt->execute();
$student = $stmt->get_result()->fetch_assoc();
$stmt->close();

if (!$student) {
    json_response(['error' => 'Profil étudiant introuvable.'], 404);
}

$stmt = $connection->prepare(
    'INSERT INTO convocations (etudiant_id, entreprise_id, type_contrat, message, date_rdv, heure_rdv, lieu)
     VALUES (?, ?, ?, ?, ?, ?, ?)'
);
$stmt->bind_param('iisssss', $studentId, $companyId, $contractType, $message, $date, $time, $location);

$createdId = null;

if (!$stmt->execute()) {
    $stmt->close();
    json_response(['error' => 'Impossible d’enregistrer la convocation.'], 500);
}

$createdId = $stmt->insert_id;
$stmt->close();

error_log(sprintf(
    'Convocation CV JUNIA: entreprise #%d vers %s le %s à %s',
    $companyId,
    $student['email'],
    $date,
    $time
));

json_response([
    'success' => true,
    'id' => $createdId,
    'message' => 'Convocation enregistrée.',
], 201);
