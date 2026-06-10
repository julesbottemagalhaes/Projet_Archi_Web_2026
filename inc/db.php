<?php
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/functions.php';

start_secure_session();

$connection = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME, DB_PORT);

if ($connection->connect_error) {
    http_response_code(500);
    $message = APP_ENV === 'dev'
        ? 'Erreur de connexion: ' . $connection->connect_error
        : 'Erreur de connexion à la base de données.';

    if (is_api_request()) {
        json_response([
            'success' => false,
            'message' => $message,
        ], 500);
    }

    die($message);
}

$connection->set_charset('utf8mb4');
