<?php
  require __DIR__ . '/../inc/db.php';

  header("Content-Type: application/json");

  if ($_SERVER['REQUEST_METHOD'] != 'POST') {
    http_response_code(405);
    echo json_encode(["error" => "Method not allowed"]);
    exit;
  }

  $data = json_decode(file_get_contents("php://input"), true);

  $email = $data['email'] ?? "";
  $password = $data['password'] ?? "";
  $user_type = $data['user_type'] ?? "";

  if (empty($email) || empty($password)) {
    http_response_code(400);
    echo json_encode(["error" => "Email et mot de passe requis"]);
    exit;
  }

  $table = ($user_type == "company") ? "entreprises" : "etudiants";
  $email_field = ($user_type == "company") ? "email_contact" : "email";

  $stmt = $connection->prepare(
    "SELECT id, nom, password_hash FROM $table WHERE $email_field = ?"
  );
  $stmt->bind_param("s", $email);
  $stmt->execute();

  $result = $stmt->get_result();

  if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();

    if (password_verify($password, $user['password_hash'])) {
      $_SESSION['user_id'] = $user['id'];
      $_SESSION['user_type'] = $user_type;
      $_SESSION['nom'] = $user['nom'];

      http_response_code(200);
      echo json_encode([
        "success" => true,
        "message" => "Connecté!",
        "user" => [
          "id" => $user['id'],
          "nom" => $user['nom'],
          "type" => $user_type
        ]
      ]);
    } else {
      http_response_code(401);
      echo json_encode(["error" => "Mot de passe incorrect"]);
    }
  } else {
    http_response_code(401);
    echo json_encode(["error" => "Email non trouvé"]);
  }

  $stmt->close();
?>
