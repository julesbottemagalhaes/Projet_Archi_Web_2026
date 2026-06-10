<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    require __DIR__ . '/enregistrer-cv.php';
    exit;
}

require __DIR__ . '/../inc/db.php';

require_http_method('GET');
require_api_role('student');

$_GET['id'] = (int) $_SESSION['user_id'];
require __DIR__ . '/profil.php';
