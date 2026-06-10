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

  $stmtStudent = $connection->prepare('SELECT id FROM etudiants WHERE id = ?');
  $stmtStudent->bind_param('i', $etudiant_id);
  $stmtStudent->execute();

  if ($stmtStudent->get_result()->num_rows === 0) {
    $stmtStudent->close();
    json_response(["error" => "Étudiant introuvable"], 404);
  }

  $stmtStudent->close();

  $stmt = $connection->prepare(
    "INSERT INTO convocations (etudiant_id, entreprise_id, type_contrat, message) VALUES (?, ?, ?, ?)"
  );
  $stmt->bind_param("iiss", $etudiant_id, $entreprise_id, $type_contrat, $message);

  if ($stmt->execute()) {
    json_response([
      "success" => true,
      "message" => "Convocation envoyée!"
    ], 201);
  } else {
    json_response(["error" => "Impossible d'envoyer la convocation"], 500);
  }

  $stmt->close();
