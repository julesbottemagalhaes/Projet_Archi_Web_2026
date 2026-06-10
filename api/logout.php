<?php
require __DIR__ . '/../inc/functions.php';

start_secure_session();
require_http_method('POST');

$_SESSION = [];

if (ini_get('session.use_cookies')) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000, $params['path'], $params['domain'], $params['secure'], $params['httponly']);
}

session_destroy();

json_response([
    'success' => true,
    'message' => 'Déconnecté',
]);
