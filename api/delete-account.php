<?php
require __DIR__ . '/../inc/db.php';

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

require_http_method('POST');
require_api_role('student');

$data = read_json_body();
$password = (string) ($data['password'] ?? '');
$confirmation = filter_var($data['confirmation'] ?? false, FILTER_VALIDATE_BOOL);
$etudiantId = (int) $_SESSION['user_id'];

if ($password === '') {
    json_response(['error' => 'Le mot de passe est requis.'], 400);
}

if (!$confirmation) {
    json_response(['error' => 'La confirmation de suppression est requise.'], 400);
}

$stmt = $connection->prepare('SELECT email, password_hash, photo FROM etudiants WHERE id = ?');
$stmt->bind_param('i', $etudiantId);
$stmt->execute();
$student = $stmt->get_result()->fetch_assoc();
$stmt->close();

if (!$student || !password_verify($password, $student['password_hash'])) {
    json_response(['error' => 'Mot de passe incorrect.'], 401);
}

$photoPath = $student['photo'] ?? null;

$connection->begin_transaction();

try {
    $stmt = $connection->prepare('DELETE FROM etudiants WHERE id = ?');
    $stmt->bind_param('i', $etudiantId);
    $stmt->execute();
    $stmt->close();

    $connection->commit();
} catch (Throwable $exception) {
    $connection->rollback();
    json_response(['error' => 'Impossible de supprimer le compte.'], 500);
}

if ($photoPath && str_starts_with($photoPath, 'uploads/photos/')) {
    $absolutePath = APP_ROOT . '/' . $photoPath;

    if (is_file($absolutePath)) {
        unlink($absolutePath);
    }
}

$_SESSION = [];

if (ini_get('session.use_cookies')) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000, $params['path'], $params['domain'], $params['secure'], $params['httponly']);
}

session_destroy();

json_response([
    'success' => true,
    'message' => 'Compte étudiant et données associées supprimés.',
]);
