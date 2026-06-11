<?php
require __DIR__ . '/../inc/db.php';

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

require_http_method('POST');
require_api_role('student');

function parse_cv_payload(): array
{
    $contentType = $_SERVER['CONTENT_TYPE'] ?? '';

    if (str_contains($contentType, 'multipart/form-data')) {
        $cvJson = $_POST['cv'] ?? '';
        $data = json_decode($cvJson, true);

        if (!is_array($data)) {
            json_response(['error' => 'Données CV invalides'], 400);
        }

        return $data;
    }

    return read_json_body();
}

function normalise_entry_list(mixed $entries, int $maxEntries = 20): array
{
    if (!is_array($entries)) {
        return [];
    }

    $normalised = [];

    foreach (array_slice($entries, 0, $maxEntries) as $entry) {
        if (!is_array($entry)) {
            continue;
        }

        $item = [
            'titre' => clean_string($entry['titre'] ?? '', 200),
            'dates' => clean_string($entry['dates'] ?? '', 100),
            'description' => clean_string($entry['description'] ?? '', 1200),
        ];

        if ($item['titre'] !== '' || $item['dates'] !== '' || $item['description'] !== '') {
            $normalised[] = $item;
        }
    }

    return $normalised;
}

function normalise_string_list(mixed $values, int $maxEntries = 30, int $maxLength = 120): array
{
    if (!is_array($values)) {
        return [];
    }

    $normalised = [];

    foreach (array_slice($values, 0, $maxEntries) as $value) {
        $value = clean_string($value, $maxLength);

        if ($value !== '') {
            $normalised[] = $value;
        }
    }

    return array_values(array_unique($normalised));
}

function year_to_date(?string $year, string $suffix): ?string
{
    if (!$year || !preg_match('/^\d{4}$/', $year)) {
        return null;
    }

    return $year . $suffix;
}

function first_year_from_text(string $value): ?string
{
    preg_match('/\d{4}/', $value, $matches);
    return $matches[0] ?? null;
}

function last_year_from_text(string $value): ?string
{
    preg_match_all('/\d{4}/', $value, $matches);
    return !empty($matches[0]) ? end($matches[0]) : null;
}

function normalise_cv_data(array $data, ?string $photo): array
{
    $prenom = clean_string($data['prenom'] ?? '', 100);
    $nom = strtoupper(clean_string($data['nom'] ?? '', 100));
    $email = strtolower(clean_string($data['email'] ?? '', 100));
    $dateNaissance = clean_string($data['dateNaissance'] ?? $data['date_naissance'] ?? '', 10);

    if ($dateNaissance !== '' && !preg_match('/^\d{4}-\d{2}-\d{2}$/', $dateNaissance)) {
        json_response(['error' => 'Date de naissance invalide'], 400);
    }

    if ($email === '' || !filter_var($email, FILTER_VALIDATE_EMAIL) || !preg_match('/^[^\s@]+@junia\.com$/i', $email)) {
        json_response(['error' => 'Une adresse email JUNIA valide est requise.'], 400);
    }

    if ($prenom === '' || $nom === '') {
        json_response(['error' => 'Le prénom et le nom sont requis.'], 400);
    }

    return [
        'prenom' => $prenom,
        'nom' => $nom,
        'titre' => clean_string($data['titre'] ?? '', 200),
        'email' => $email,
        'telephone' => clean_string($data['telephone'] ?? '', 20),
        'ville' => clean_string($data['ville'] ?? '', 100),
        'dateNaissance' => $dateNaissance,
        'linkedin' => clean_string($data['linkedin'] ?? '', 255),
        'github' => clean_string($data['github'] ?? '', 255),
        'photo' => $photo ?? '',
        'profil' => clean_string($data['profil'] ?? '', 1200),
        'domainesRecherche' => normalise_cv_domaines($data['domainesRecherche'] ?? $data['domaines_recherche'] ?? $data['domaines'] ?? []),
        'competences' => normalise_string_list($data['competences'] ?? []),
        'langues' => normalise_string_list($data['langues'] ?? []),
        'formations' => normalise_entry_list($data['formations'] ?? []),
        'experiences' => normalise_entry_list($data['experiences'] ?? []),
        'projets' => normalise_entry_list($data['projets'] ?? []),
        'centresInteret' => normalise_string_list($data['centresInteret'] ?? $data['centres_interet'] ?? []),
    ];
}

function upload_student_photo(?array $file, ?string $existingPhoto): ?string
{
    if (!$file || ($file['error'] ?? UPLOAD_ERR_NO_FILE) === UPLOAD_ERR_NO_FILE) {
        return $existingPhoto;
    }

    $uploadError = $file['error'] ?? UPLOAD_ERR_OK;

    if ($uploadError !== UPLOAD_ERR_OK) {
        $messages = [
            UPLOAD_ERR_INI_SIZE => 'La photo ne doit pas dépasser 2 Mo.',
            UPLOAD_ERR_FORM_SIZE => 'La photo ne doit pas dépasser 2 Mo.',
            UPLOAD_ERR_PARTIAL => 'La photo n’a été envoyée que partiellement. Réessayez.',
            UPLOAD_ERR_NO_TMP_DIR => 'Le dossier temporaire d’upload est indisponible.',
            UPLOAD_ERR_CANT_WRITE => 'Impossible d’écrire la photo sur le serveur.',
            UPLOAD_ERR_EXTENSION => 'L’upload de la photo a été bloqué par PHP.',
        ];

        json_response(['error' => $messages[$uploadError] ?? 'Upload photo impossible.'], 400);
    }

    if (($file['size'] ?? 0) > UPLOAD_PHOTO_MAX_SIZE) {
        json_response(['error' => 'La photo ne doit pas dépasser 2 Mo.'], 400);
    }

    $finfo = finfo_open(FILEINFO_MIME_TYPE);
    $mime = $finfo ? finfo_file($finfo, $file['tmp_name']) : null;

    $allowed = UPLOAD_PHOTO_MIME_TYPES;

    if (!$mime || !isset($allowed[$mime])) {
        json_response(['error' => 'La photo doit être au format JPG ou PNG.'], 400);
    }

    if (!is_dir(UPLOAD_PHOTO_DIR) && !mkdir(UPLOAD_PHOTO_DIR, 0755, true)) {
        json_response(['error' => 'Le dossier upload est indisponible.'], 500);
    }

    $filename = 'student-' . (int) $_SESSION['user_id'] . '-' . bin2hex(random_bytes(12)) . '.' . $allowed[$mime];
    $destination = UPLOAD_PHOTO_DIR . '/' . $filename;

    if (!move_uploaded_file($file['tmp_name'], $destination)) {
        json_response(['error' => 'Impossible d’enregistrer la photo.'], 500);
    }

    return 'uploads/photos/' . $filename;
}

function is_duplicate_student_email(mysqli $connection, string $email, int $etudiantId): bool
{
    $stmt = $connection->prepare('SELECT id FROM etudiants WHERE email = ? AND id <> ? LIMIT 1');
    $stmt->bind_param('si', $email, $etudiantId);
    $stmt->execute();
    $exists = $stmt->get_result()->num_rows > 0;
    $stmt->close();

    return $exists;
}

function delete_uploaded_student_photo(?string $photo): void
{
    if (!$photo || !str_starts_with($photo, 'uploads/photos/')) {
        return;
    }

    $path = APP_ROOT . '/' . $photo;

    if (is_file($path)) {
        @unlink($path);
    }
}

$etudiantId = (int) $_SESSION['user_id'];
$data = parse_cv_payload();

$stmt = $connection->prepare('SELECT photo FROM etudiants WHERE id = ?');
$stmt->bind_param('i', $etudiantId);
$stmt->execute();
$existing = $stmt->get_result()->fetch_assoc();
$stmt->close();

if (!$existing) {
    json_response(['error' => 'Compte étudiant introuvable.'], 404);
}

$existingPhoto = $existing['photo'] ?? null;
$cv = normalise_cv_data($data, $existingPhoto);

if (is_duplicate_student_email($connection, $cv['email'], $etudiantId)) {
    json_response(['error' => 'Cette adresse email est déjà utilisée par un autre compte étudiant.'], 409);
}

$photo = upload_student_photo($_FILES['photo'] ?? null, $existingPhoto);
$uploadedPhoto = $photo !== $existingPhoto ? $photo : null;
$cv['photo'] = $photo ?? '';
$nomComplet = trim($cv['prenom'] . ' ' . $cv['nom']);
$titre = $cv['titre'];
$email = $cv['email'];
$biographie = $cv['profil'];
$dateNaissance = $cv['dateNaissance'] !== '' ? $cv['dateNaissance'] : null;
$telephone = $cv['telephone'] !== '' ? $cv['telephone'] : null;
$ville = $cv['ville'] !== '' ? $cv['ville'] : null;
$linkedin = $cv['linkedin'] !== '' ? $cv['linkedin'] : null;
$github = $cv['github'] !== '' ? $cv['github'] : null;
$photoDb = $cv['photo'] !== '' ? $cv['photo'] : null;
$languesJson = safe_json_encode($cv['langues']);
$projetsJson = safe_json_encode($cv['projets']);
$centresJson = safe_json_encode($cv['centresInteret']);
$domainesJson = safe_json_encode($cv['domainesRecherche']);
$donneesJson = safe_json_encode($cv);

$connection->begin_transaction();

try {
    $stmt = $connection->prepare(
        'UPDATE etudiants
         SET nom = ?, titre = ?, email = ?, telephone = ?, ville = ?, date_naissance = ?,
             linkedin = ?, github = ?, photo = ?, biographie = ?, langues = ?, projets = ?,
             centres_interet = ?, domaines_recherche = ?, donnees_json = ?
         WHERE id = ?'
    );
    $stmt->bind_param(
        'sssssssssssssssi',
        $nomComplet,
        $titre,
        $email,
        $telephone,
        $ville,
        $dateNaissance,
        $linkedin,
        $github,
        $photoDb,
        $biographie,
        $languesJson,
        $projetsJson,
        $centresJson,
        $domainesJson,
        $donneesJson,
        $etudiantId
    );
    $stmt->execute();
    $stmt->close();

    foreach (['experiences', 'formations', 'competences'] as $table) {
        $stmtDelete = $connection->prepare("DELETE FROM $table WHERE etudiant_id = ?");
        $stmtDelete->bind_param('i', $etudiantId);
        $stmtDelete->execute();
        $stmtDelete->close();
    }

    $stmtExp = $connection->prepare(
        'INSERT INTO experiences (etudiant_id, entreprise, poste, date_debut, date_fin, description)
         VALUES (?, ?, ?, ?, ?, ?)'
    );

    foreach ($cv['experiences'] as $exp) {
        $parts = array_map('trim', explode(' - ', $exp['titre'], 2));
        $entreprise = $parts[0] ?? '';
        $poste = $parts[1] ?? '';
        $dateDebut = year_to_date(first_year_from_text($exp['dates']), '-01-01');
        $dateFin = year_to_date(last_year_from_text($exp['dates']), '-12-31');
        $description = $exp['description'];
        $stmtExp->bind_param('isssss', $etudiantId, $entreprise, $poste, $dateDebut, $dateFin, $description);
        $stmtExp->execute();
    }

    $stmtExp->close();

    $stmtForm = $connection->prepare(
        'INSERT INTO formations (etudiant_id, ecole, diplome, date_fin, description)
         VALUES (?, ?, ?, ?, ?)'
    );

    foreach ($cv['formations'] as $form) {
        $parts = array_map('trim', explode(' - ', $form['titre'], 2));
        $diplome = $parts[0] ?? '';
        $ecole = $parts[1] ?? '';
        $dateFin = year_to_date(last_year_from_text($form['dates']), '-12-31');
        $description = $form['description'];
        $stmtForm->bind_param('issss', $etudiantId, $ecole, $diplome, $dateFin, $description);
        $stmtForm->execute();
    }

    $stmtForm->close();

    $stmtComp = $connection->prepare('INSERT INTO competences (etudiant_id, competence) VALUES (?, ?)');

    foreach ($cv['competences'] as $competence) {
        $stmtComp->bind_param('is', $etudiantId, $competence);
        $stmtComp->execute();
    }

    $stmtComp->close();
    $connection->commit();
} catch (Throwable $exception) {
    $connection->rollback();
    delete_uploaded_student_photo($uploadedPhoto ?? null);
    error_log('Erreur enregistrement CV: ' . $exception->getMessage());

    if ($exception instanceof mysqli_sql_exception && (int) $exception->getCode() === 1062) {
        json_response(['error' => 'Cette adresse email est déjà utilisée par un autre compte étudiant.'], 409);
    }

    $response = ['error' => 'Impossible d’enregistrer le CV.'];

    if (APP_ENV === 'dev') {
        $response['debug'] = $exception->getMessage();
    }

    json_response($response, 500);
}

$_SESSION['nom'] = $nomComplet;
$_SESSION['email'] = $email;

json_response([
    'success' => true,
    'message' => 'CV enregistré.',
    'cv' => $cv,
]);
