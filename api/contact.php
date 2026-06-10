<?php
require __DIR__ . '/../inc/db.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['error' => 'Méthode non autorisée']);
    exit;
}

$data = json_decode(file_get_contents('php://input'), true);
$nom = $data['nom'] ?? '';
$email = $data['email'] ?? '';
$message = $data['message'] ?? '';

if (empty($nom) || empty($email) || empty($message)) {
    http_response_code(400);
    echo json_encode(['error' => 'Tous les champs sont requis']);
    exit;
}

$stmt = $connection->prepare("INSERT INTO demandes_contact (nom_entreprise, email_contact, message) VALUES (?, ?, ?)");
$stmt->bind_param("sss", $nom, $email, $message);

if ($stmt->execute()) {
    http_response_code(201);
    echo json_encode(['success' => true, 'message' => 'Demande de contact envoyée avec succès.']);
} else {
    http_response_code(500);
    echo json_encode(['error' => 'Erreur lors de l\'enregistrement']);
}

$stmt->close();
?>
