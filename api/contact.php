<?php
require __DIR__ . '/../inc/db.php';

require_http_method('POST');

$data = read_json_body();
$nom = clean_string($data['nom'] ?? '', 100);
$email = strtolower(clean_string($data['email'] ?? '', 100));
$message = clean_string($data['message'] ?? '', 1500);

if ($nom === '' || $email === '' || $message === '') {
    json_response(['error' => 'Tous les champs sont requis.'], 400);
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    json_response(['error' => 'Email de contact invalide.'], 400);
}

$stmt = $connection->prepare("INSERT INTO demandes_contact (nom_entreprise, email_contact, message) VALUES (?, ?, ?)");
$stmt->bind_param("sss", $nom, $email, $message);

if ($stmt->execute()) {
    json_response(['success' => true, 'message' => 'Demande de contact envoyée avec succès.'], 201);
} else {
    json_response(['error' => 'Erreur lors de l\'enregistrement.'], 500);
}
