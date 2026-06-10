<?php
  require __DIR__ . '/../inc/db.php';

  require_http_method('POST');

  $data = read_json_body();

  $email = clean_string($data['email'] ?? '', 100);
  $password = (string) ($data['password'] ?? '');
  $user_type = clean_string($data['user_type'] ?? 'student', 20);

  if ($email === '' || $password === '') {
    json_response(["error" => "Email et mot de passe requis"], 400);
  }

  $roles = [
    'student' => ['table' => 'etudiants', 'email_field' => 'email', 'nom_field' => 'nom'],
    'company' => ['table' => 'entreprises', 'email_field' => 'email_contact', 'nom_field' => 'nom'],
    'admin' => ['table' => 'admins', 'email_field' => 'email', 'nom_field' => 'nom'],
  ];

  if (!isset($roles[$user_type])) {
    json_response(["error" => "Type de compte invalide"], 400);
  }

  $table = $roles[$user_type]['table'];
  $email_field = $roles[$user_type]['email_field'];
  $nom_field = $roles[$user_type]['nom_field'];

  $stmt = $connection->prepare(
    "SELECT id, $nom_field, password_hash FROM $table WHERE $email_field = ?"
  );
  $stmt->bind_param("s", $email);
  $stmt->execute();

  $result = $stmt->get_result();

  if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();

    if (password_verify($password, $user['password_hash'])) {
      session_regenerate_id(true);

      $_SESSION['user_id'] = $user['id'];
      $_SESSION['user_role'] = $user_type;
      $_SESSION['user_type'] = $user_type;
      $_SESSION['nom'] = $user['nom'];
      $_SESSION['email'] = $email;

      json_response([
        "success" => true,
        "message" => "Connecté!",
        "user" => [
          "id" => $user['id'],
          "nom" => $user['nom'],
          "role" => $user_type,
          "type" => $user_type
        ]
      ]);
    } else {
      json_response(["error" => "Identifiants invalides"], 401);
    }
  } else {
    json_response(["error" => "Identifiants invalides"], 401);
  }

  $stmt->close();
