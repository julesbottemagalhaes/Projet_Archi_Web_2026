<?php
require_once __DIR__ . '/functions.php';

start_secure_session();

$requiredRoles = $requiredRoles ?? ['student', 'company', 'admin'];
$scriptDirectory = str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'] ?? '/index.php'));
$baseUrl = preg_replace('#/(pages|api|inc)(/.*)?$#', '', $scriptDirectory);
$baseUrl = $baseUrl === '/' ? '' : rtrim($baseUrl, '/');

if (empty($_SESSION['user_id']) || empty(current_user_role())) {
    header('Location: ' . $baseUrl . '/pages/connexion.php');
    exit;
}

if (!in_array(current_user_role(), $requiredRoles, true)) {
    http_response_code(403);
    echo 'Accès refusé.';
    exit;
}
