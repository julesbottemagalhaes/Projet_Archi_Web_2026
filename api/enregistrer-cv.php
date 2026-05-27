<?php
  require __DIR__ . '/../inc/db.php';

  header("Content-Type: application/json");

  if ($_SERVER['REQUEST_METHOD'] != 'POST') {
    http_response_code(405);
    echo json_encode(["error" => "Method not allowed"]);
    exit;
  }

  if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] != 'student') {
    http_response_code(403);
    echo json_encode(["error" => "Non autorisé"]);
    exit;
  }

  $etudiant_id = $_SESSION['user_id'];
  $data = json_decode(file_get_contents("php://input"), true);

  if (!$data) {
    http_response_code(400);
    echo json_encode(["error" => "Données invalides"]);
    exit;
  }

  $nom_complet = trim(($data['prenom'] ?? '') . ' ' . ($data['nom'] ?? ''));
  $titre = $data['titre'] ?? '';
  $email = $data['email'] ?? '';
  $telephone = $data['telephone'] ?: null;
  $ville = $data['ville'] ?: null;
  $date_naissance = !empty($data['dateNaissance']) ? $data['dateNaissance'] : null;
  $linkedin = $data['linkedin'] ?: null;
  $github = $data['github'] ?: null;
  $photo = $data['photo'] ?: null;
  $biographie = $data['profil'] ?? '';
  $langues_json = json_encode($data['langues'] ?? []);
  $projets_json = json_encode($data['projets'] ?? []);
  $centres_json = json_encode($data['centresInteret'] ?? []);
  $domaines_json = json_encode([]);
  $donnees_json = json_encode($data);

  $stmt = $connection->prepare(
    "UPDATE etudiants
     SET nom=?, titre=?, email=?, telephone=?, ville=?, date_naissance=?,
         linkedin=?, github=?, photo=?, biographie=?, langues=?, projets=?,
         centres_interet=?, domaines_recherche=?, donnees_json=?
     WHERE id=?"
  );
  $stmt->bind_param(
    "sssssssssssssssi",
    $nom_complet, $titre, $email, $telephone, $ville, $date_naissance,
    $linkedin, $github, $photo, $biographie, $langues_json, $projets_json,
    $centres_json, $domaines_json, $donnees_json, $etudiant_id
  );

  if (!$stmt->execute()) {
    http_response_code(500);
    echo json_encode(["error" => "Erreur: " . $stmt->error]);
    exit;
  }
  $stmt->close();

  $connection->query("DELETE FROM experiences WHERE etudiant_id = $etudiant_id");
  $connection->query("DELETE FROM formations WHERE etudiant_id = $etudiant_id");
  $connection->query("DELETE FROM competences WHERE etudiant_id = $etudiant_id");

  if (!empty($data['experiences'])) {
    $stmt_exp = $connection->prepare(
      "INSERT INTO experiences (etudiant_id, entreprise, poste, date_debut, date_fin, description)
       VALUES (?, ?, ?, ?, ?, ?)"
    );
    foreach ($data['experiences'] as $exp) {
      $t = $exp['titre'] ?? '';
      $parts = explode(' - ', $t, 2);
      $entreprise = trim($parts[0]);
      $poste = isset($parts[1]) ? trim($parts[1]) : '';
      preg_match_all('/\d{4}/', $exp['dates'] ?? '', $m);
      $date_debut = isset($m[0][0]) ? $m[0][0] . '-01-01' : null;
      $date_fin = isset($m[0][1]) ? $m[0][1] . '-12-31' : null;
      $description = $exp['description'] ?? '';
      $stmt_exp->bind_param("isssss", $etudiant_id, $entreprise, $poste, $date_debut, $date_fin, $description);
      $stmt_exp->execute();
    }
    $stmt_exp->close();
  }

  if (!empty($data['formations'])) {
    $stmt_form = $connection->prepare(
      "INSERT INTO formations (etudiant_id, ecole, diplome, date_fin, description)
       VALUES (?, ?, ?, ?, ?)"
    );
    foreach ($data['formations'] as $form) {
      $t = $form['titre'] ?? '';
      $parts = explode(' - ', $t, 2);
      $diplome = trim($parts[0]);
      $ecole = isset($parts[1]) ? trim($parts[1]) : '';
      preg_match_all('/\d{4}/', $form['dates'] ?? '', $m);
      $date_fin_form = !empty($m[0]) ? end($m[0]) . '-12-31' : null;
      $description = $form['description'] ?? '';
      $stmt_form->bind_param("issss", $etudiant_id, $ecole, $diplome, $date_fin_form, $description);
      $stmt_form->execute();
    }
    $stmt_form->close();
  }

  if (!empty($data['competences'])) {
    $stmt_comp = $connection->prepare(
      "INSERT INTO competences (etudiant_id, competence) VALUES (?, ?)"
    );
    foreach ($data['competences'] as $comp) {
      $stmt_comp->bind_param("is", $etudiant_id, $comp);
      $stmt_comp->execute();
    }
    $stmt_comp->close();
  }

  http_response_code(200);
  echo json_encode(["success" => true, "message" => "CV enregistré!"]);
?>
