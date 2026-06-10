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
  $date_rdv = $data['date'] ?? null;
  $heure_rdv = $data['heure'] ?? null;
  $lieu = $data['lieu'] ?? "";
  $entreprise_id = $_SESSION['user_id'];

  if (empty($etudiant_id) || empty($type_contrat) || empty($date_rdv) || empty($heure_rdv) || empty($lieu)) {
    http_response_code(400);
    echo json_encode(["error" => "Tous les champs sont requis"]);
    exit;
  }

  $stmt = $connection->prepare(
    "INSERT INTO convocations (etudiant_id, entreprise_id, type_contrat, message, date_rdv, heure_rdv, lieu) VALUES (?, ?, ?, ?, ?, ?, ?)"
  );
  $stmt->bind_param("iisssss", $etudiant_id, $entreprise_id, $type_contrat, $message, $date_rdv, $heure_rdv, $lieu);

  if ($stmt->execute()) {
    // Simulation d'envoi d'email
    error_log("Email de convocation envoyé à l'étudiant $etudiant_id par l'entreprise $entreprise_id pour le $date_rdv à $heure_rdv");
    
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
