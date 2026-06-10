<?php
require __DIR__ . '/../inc/db.php';

require_http_method('POST');

$data = read_json_body();

$prenom = clean_string($data['prenom'] ?? '', 100);
$nom = clean_string($data['nom'] ?? '', 100);
$email = strtolower(clean_string($data['email'] ?? '', 100));
$password = (string) ($data['password'] ?? '');
$consentement = filter_var($data['consentement'] ?? false, FILTER_VALIDATE_BOOL);

if ($prenom === '' || $nom === '' || $email === '' || $password === '') {
    json_response(['error' => 'Tous les champs obligatoires doivent être remplis.'], 400);
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL) || !preg_match('/^[^\s@]+@junia\.com$/i', $email)) {
    json_response(['error' => 'Une adresse email JUNIA valide est requise.'], 400);
}

if (strlen($password) < 8) {
    json_response(['error' => 'Le mot de passe doit contenir au moins 8 caractères.'], 400);
}

if (!$consentement) {
    json_response(['error' => 'Le consentement est requis pour créer le compte.'], 400);
}

$stmt = $connection->prepare('SELECT id FROM etudiants WHERE email = ?');
$stmt->bind_param('s', $email);
$stmt->execute();

if ($stmt->get_result()->num_rows > 0) {
    $stmt->close();
    json_response(['error' => 'Un compte étudiant existe déjà avec cet email.'], 409);
}

$stmt->close();

$nomComplet = trim($prenom . ' ' . strtoupper($nom));
$passwordHash = password_hash($password, PASSWORD_DEFAULT);
$domainesRecherche = safe_json_encode([]);
$donneesJson = safe_json_encode([
    'prenom' => $prenom,
    'nom' => strtoupper($nom),
    'email' => $email,
    'domainesRecherche' => [],
]);

$stmt = $connection->prepare(
    'INSERT INTO etudiants (nom, email, password_hash, domaines_recherche, donnees_json)
     VALUES (?, ?, ?, ?, ?)'
);
$stmt->bind_param('sssss', $nomComplet, $email, $passwordHash, $domainesRecherche, $donneesJson);

if (!$stmt->execute()) {
    json_response(['error' => 'Impossible de créer le compte étudiant.'], 500);
}

$studentId = $stmt->insert_id;
$stmt->close();

session_regenerate_id(true);
$_SESSION['user_id'] = $studentId;
$_SESSION['user_role'] = 'student';
$_SESSION['user_type'] = 'student';
$_SESSION['nom'] = $nomComplet;
$_SESSION['email'] = $email;

json_response([
    'success' => true,
    'message' => 'Compte étudiant créé.',
    'user' => [
        'id' => $studentId,
        'nom' => $nomComplet,
        'role' => 'student',
        'type' => 'student',
    ],
], 201);
