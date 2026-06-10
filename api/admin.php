<?php
require __DIR__ . '/../inc/db.php';

header('Content-Type: application/json');

if (!isset($_SESSION['user_type']) || $_SESSION['user_type'] !== 'admin') {
    http_response_code(403);
    echo json_encode(['error' => 'Non autorisé']);
    exit;
}

$action = $_GET['action'] ?? '';

switch ($action) {
    case 'stats':
        $res_etud = $connection->query("SELECT COUNT(*) as c FROM etudiants");
        $res_entr = $connection->query("SELECT COUNT(*) as c FROM entreprises");
        $res_conv = $connection->query("SELECT COUNT(*) as c FROM convocations");
        $res_cont = $connection->query("SELECT COUNT(*) as c FROM demandes_contact");
        
        echo json_encode([
            'etudiants' => $res_etud->fetch_assoc()['c'],
            'entreprises' => $res_entr->fetch_assoc()['c'],
            'convocations' => $res_conv->fetch_assoc()['c'],
            'demandes' => $res_cont->fetch_assoc()['c']
        ]);
        break;

    case 'users':
        $type = $_GET['type'] ?? 'etudiants';
        $table = $type === 'entreprises' ? 'entreprises' : 'etudiants';
        $nom_field = $type === 'entreprises' ? 'nom, email_contact as email' : 'nom, email';
        $res = $connection->query("SELECT id, $nom_field, date_creation FROM $table ORDER BY id DESC");
        
        $users = [];
        while ($r = $res->fetch_assoc()) {
            $users[] = $r;
        }
        echo json_encode($users);
        break;

    case 'delete_user':
        $data = json_decode(file_get_contents("php://input"), true);
        $id = $data['id'] ?? 0;
        $type = $data['type'] ?? 'etudiants';
        $table = $type === 'entreprises' ? 'entreprises' : 'etudiants';
        
        $stmt = $connection->prepare("DELETE FROM $table WHERE id = ?");
        $stmt->bind_param("i", $id);
        
        if ($stmt->execute()) {
            echo json_encode(["success" => true]);
        } else {
            echo json_encode(["error" => "Erreur lors de la suppression"]);
        }
        $stmt->close();
        break;

    case 'contact_requests':
        $res = $connection->query("SELECT * FROM demandes_contact ORDER BY date_demande DESC");
        $reqs = [];
        while ($r = $res->fetch_assoc()) {
            $reqs[] = $r;
        }
        echo json_encode($reqs);
        break;
        
    case 'approve_contact':
        $data = json_decode(file_get_contents("php://input"), true);
        $id = $data['id'] ?? 0;
        $stmt = $connection->prepare("UPDATE demandes_contact SET statut = 'validée' WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        echo json_encode(["success" => true]);
        $stmt->close();
        break;

    default:
        echo json_encode(['error' => 'Action inconnue']);
}
?>
