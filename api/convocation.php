<?php
  require __DIR__ . '/../inc/db.php';

  header("Content-Type: application/json");

  if ($_SERVER['REQUEST_METHOD'] != 'POST') {
    http_response_code(405);
    echo json_encode(["error" => "Method not allowed"]);
    exit;
  }

  if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] != 'company') {
    http_response_code(403);
    echo json_encode(["error" => "Non autorisé"]);
    exit;
  }

  $data = json_decode(file_get_contents("php://input"), true);

  $etudiant_id = $data['etudiant_id'] ?? null;
  $type_contrat = $data['type_contrat'] ?? "";
  $message = $data['message'] ?? "";
  $entreprise_id = $_SESSION['user_id'];

  if (empty($etudiant_id) || empty($type_contrat)) {
    http_response_code(400);
    echo json_encode(["error" => "etudiant_id et type_contrat requis"]);
    exit;
  }

  $stmt = $connection->prepare(
    "INSERT INTO convocations (etudiant_id, entreprise_id, type_contrat, message) VALUES (?, ?, ?, ?)"
  );
  $stmt->bind_param("iiss", $etudiant_id, $entreprise_id, $type_contrat, $message);

  if ($stmt->execute()) {
    http_response_code(201);
    echo json_encode([
      "success" => true,
      "message" => "Convocation envoyée!"
    ]);
  } else {
    http_response_code(500);
    echo json_encode(["error" => "Erreur: " . $stmt->error]);
  }

  $stmt->close();
?>
