<?php
$env = static function (string $key, mixed $default): mixed {
    $value = getenv($key);
    return $value === false || $value === '' ? $default : $value;
};

define('APP_ENV', $env('APP_ENV', 'dev'));
define('APP_ROOT', dirname(__DIR__));

define('DB_HOST', $env('DB_HOST', '127.0.0.1'));
define('DB_USER', $env('DB_USER', 'root'));
define('DB_PASS', $env('DB_PASS', ''));
define('DB_NAME', $env('DB_NAME', 'cv_platform'));
define('DB_PORT', (int) $env('DB_PORT', 3307));

define('UPLOAD_BASE_DIR', APP_ROOT . '/uploads');
define('UPLOAD_PHOTO_DIR', UPLOAD_BASE_DIR . '/photos');
define('UPLOAD_PHOTO_MAX_SIZE', 2 * 1024 * 1024);
define('UPLOAD_PHOTO_MIME_TYPES', [
    'image/jpeg' => 'jpg',
    'image/png' => 'png',
]);
