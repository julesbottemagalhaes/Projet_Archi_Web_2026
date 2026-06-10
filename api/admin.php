<?php
require __DIR__ . '/../inc/db.php';

require_api_role('admin');

$action = clean_string($_GET['action'] ?? '', 40);

switch ($action) {
    case 'stats':
        $students = $connection->query('SELECT COUNT(*) AS total FROM etudiants')->fetch_assoc()['total'];
        $companies = $connection->query('SELECT COUNT(*) AS total FROM entreprises')->fetch_assoc()['total'];
        $convocations = $connection->query('SELECT COUNT(*) AS total FROM convocations')->fetch_assoc()['total'];
        $requests = $connection->query('SELECT COUNT(*) AS total FROM demandes_contact')->fetch_assoc()['total'];

        json_response([
            'etudiants' => (int) $students,
            'entreprises' => (int) $companies,
            'convocations' => (int) $convocations,
            'demandes' => (int) $requests,
        ]);

    case 'users':
        $type = ($_GET['type'] ?? '') === 'entreprises' ? 'entreprises' : 'etudiants';
        $select = $type === 'entreprises'
            ? 'id, nom, email_contact AS email, date_creation'
            : 'id, nom, email, date_creation';

        $result = $connection->query("SELECT $select FROM $type ORDER BY id DESC");
        $users = [];

        while ($row = $result->fetch_assoc()) {
            $users[] = [
                'id' => (int) $row['id'],
                'nom' => $row['nom'],
                'email' => $row['email'],
                'date_creation' => $row['date_creation'],
            ];
        }

        json_response($users);

    case 'delete_user':
        require_http_method('POST');
        $data = read_json_body();
        $id = filter_var($data['id'] ?? null, FILTER_VALIDATE_INT);
        $type = ($data['type'] ?? '') === 'entreprises' ? 'entreprises' : 'etudiants';

        if (!$id) {
            json_response(['error' => 'Identifiant invalide.'], 400);
        }

        $stmt = $connection->prepare("DELETE FROM $type WHERE id = ?");
        $stmt->bind_param('i', $id);
        $ok = $stmt->execute();
        $stmt->close();

        json_response($ok ? ['success' => true] : ['error' => 'Erreur lors de la suppression.'], $ok ? 200 : 500);

    case 'contact_requests':
        $result = $connection->query('SELECT id, nom_entreprise, email_contact, message, statut, date_demande
                                      FROM demandes_contact ORDER BY date_demande DESC');
        $requests = [];

        while ($row = $result->fetch_assoc()) {
            $requests[] = [
                'id' => (int) $row['id'],
                'nom_entreprise' => $row['nom_entreprise'],
                'email_contact' => $row['email_contact'],
                'message' => $row['message'],
                'statut' => $row['statut'],
                'date_demande' => $row['date_demande'],
            ];
        }

        json_response($requests);

    case 'approve_contact':
        require_http_method('POST');
        $data = read_json_body();
        $id = filter_var($data['id'] ?? null, FILTER_VALIDATE_INT);

        if (!$id) {
            json_response(['error' => 'Identifiant invalide.'], 400);
        }

        $stmt = $connection->prepare("UPDATE demandes_contact SET statut = 'validée' WHERE id = ?");
        $stmt->bind_param('i', $id);
        $ok = $stmt->execute();
        $stmt->close();

        json_response($ok ? ['success' => true] : ['error' => 'Validation impossible.'], $ok ? 200 : 500);

    default:
        json_response(['error' => 'Action inconnue.'], 400);
}
