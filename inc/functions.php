<?php
function start_secure_session(): void
{
    if (session_status() === PHP_SESSION_ACTIVE) {
        return;
    }

    $isHttps = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off')
        || (($_SERVER['HTTP_X_FORWARDED_PROTO'] ?? '') === 'https');

    session_name('junia_cv_session');
    session_set_cookie_params([
        'lifetime' => 0,
        'path' => '/',
        'domain' => '',
        'secure' => $isHttps,
        'httponly' => true,
        'samesite' => 'Lax',
    ]);
    session_start();
}

function json_response(array $data, int $status = 200): void
{
    http_response_code($status);
    header('Content-Type: application/json');
    echo json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    exit;
}

function is_api_request(): bool
{
    $script = str_replace('\\', '/', $_SERVER['SCRIPT_NAME'] ?? '');
    return str_contains($script, '/api/');
}

function current_user_role(): ?string
{
    return $_SESSION['user_role'] ?? $_SESSION['user_type'] ?? null;
}

function current_user(): ?array
{
    if (empty($_SESSION['user_id']) || empty(current_user_role())) {
        return null;
    }

    return [
        'id' => (int) $_SESSION['user_id'],
        'role' => current_user_role(),
        'type' => current_user_role(),
        'nom' => $_SESSION['nom'] ?? '',
        'email' => $_SESSION['email'] ?? '',
    ];
}

function require_role(array|string $roles, bool $api = false): void
{
    $roles = is_array($roles) ? $roles : [$roles];
    $role = current_user_role();

    if (empty($_SESSION['user_id']) || !$role) {
        if ($api) {
            json_response(['error' => 'Authentification requise'], 401);
        }

        header('Location: ../connexion.php');
        exit;
    }

    if (!in_array($role, $roles, true)) {
        if ($api) {
            json_response(['error' => 'Accès refusé'], 403);
        }

        http_response_code(403);
        echo 'Accès refusé.';
        exit;
    }
}

function require_api_role(array|string $roles): void
{
    require_role($roles, true);
}

function require_http_method(string $method): void
{
    if ($_SERVER['REQUEST_METHOD'] !== $method) {
        json_response(['error' => 'Method not allowed'], 405);
    }
}

function read_json_body(): array
{
    $payload = file_get_contents('php://input');
    $data = json_decode($payload ?: '', true);

    if (!is_array($data)) {
        json_response(['error' => 'Données JSON invalides'], 400);
    }

    return $data;
}

function clean_string(mixed $value, int $maxLength = 255): string
{
    $value = trim((string) $value);
    return substr($value, 0, $maxLength);
}

function safe_json_encode(mixed $value): string
{
    return json_encode($value, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) ?: '[]';
}

function decode_json_array(?string $json): array
{
    $decoded = json_decode($json ?? '[]', true);
    return is_array($decoded) ? $decoded : [];
}

function allowed_cv_domaines(): array
{
    return ['stage', 'alternance', 'cdi', 'mobilite'];
}

function normalise_cv_domaines(mixed $value): array
{
    if (is_string($value)) {
        $decoded = json_decode($value, true);
        $value = is_array($decoded) ? $decoded : [$value];
    }

    if (!is_array($value)) {
        return [];
    }

    $domaines = array_map(
        static fn (mixed $domaine): string => strtolower(clean_string($domaine, 30)),
        $value
    );

    return array_values(array_intersect(allowed_cv_domaines(), array_unique($domaines)));
}
