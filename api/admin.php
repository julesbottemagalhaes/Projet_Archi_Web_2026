<?php
require __DIR__ . '/../inc/db.php';

header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    http_response_code(403);
    echo json_encode(['error' => 'Non autorisé']);
    exit;
}

echo json_encode([
    'success' => true,
    'message' => 'API admin prête à être complétée.',
]);
