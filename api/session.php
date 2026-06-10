<?php
require __DIR__ . '/../inc/functions.php';

start_secure_session();

json_response([
    'authenticated' => current_user() !== null,
    'user' => current_user(),
]);
