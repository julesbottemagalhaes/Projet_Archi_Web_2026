<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    require __DIR__ . '/enregistrer-cv.php';
    exit;
}

require __DIR__ . '/profil.php';
