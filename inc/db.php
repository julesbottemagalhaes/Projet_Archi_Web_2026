<?php
require_once __DIR__ . '/config.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$connection = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME, DB_PORT);

if ($connection->connect_error) {
    http_response_code(500);
    die(json_encode([
        'success' => false,
        'message' => 'Erreur de connexion: ' . $connection->connect_error,
    ]));
}

$connection->set_charset('utf8mb4');
