<?php
require __DIR__ . '/../inc/db.php';

require_api_role('admin');

json_response([
    'success' => true,
    'message' => 'API admin prête à être complétée.',
]);
