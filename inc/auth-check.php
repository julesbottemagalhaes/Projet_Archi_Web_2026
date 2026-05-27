<?php
require_once __DIR__ . '/db.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: ../pages/connexion.php');
    exit;
}
