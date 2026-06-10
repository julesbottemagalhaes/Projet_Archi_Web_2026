<?php
  require __DIR__ . '/../inc/db.php';

  require_http_method('POST');
  require_api_role('company');

  $data = read_json_body();

  $etudiant_id = filter_var($data['etudiant_id'] ?? null, FILTER_VALIDATE_INT);
  $type_contrat = strtolower(clean_string($data['type_contrat'] ?? '', 50));
  $message = clean_string($data['message'] ?? '', 1200);
  $entreprise_id = $_SESSION['user_id'];

  if (!$etudiant_id || $type_contrat === '') {
    json_response(["error" => "etudiant_id et type_contrat requis"], 400);
  }

  if (!in_array($type_contrat, allowed_cv_domaines(), true)) {
    json_response(["error" => "Type de contrat invalide"], 400);
  }

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

  $stmtStudent->close();

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
    ], 201);
  } else {
    json_response(["error" => "Impossible d'envoyer la convocation"], 500);
  }

  $stmt->close();
