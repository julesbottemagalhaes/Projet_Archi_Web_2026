<?php
  require __DIR__ . '/../inc/db.php';

  header("Content-Type: application/json");

  $page = max(1, intval($_GET['page'] ?? 1));
  $limit = 10;
  $offset = ($page - 1) * $limit;
  $domaine = $_GET['domaine'] ?? '';
  $search = $_GET['search'] ?? '';

  $query = "SELECT id, nom, biographie, photo, domaines_recherche FROM etudiants WHERE 1=1";

  if (!empty($domaine)) $query .= " AND domaines_recherche LIKE ?";
  if (!empty($search)) $query .= " AND (nom LIKE ? OR biographie LIKE ?)";
  $query .= " LIMIT ? OFFSET ?";

  $stmt = $connection->prepare($query);
  $domaine_like = "%$domaine%";
  $search_like = "%$search%";

  if (!empty($domaine) && !empty($search)) {
    $stmt->bind_param("sssii", $domaine_like, $search_like, $search_like, $limit, $offset);
  } elseif (!empty($domaine)) {
    $stmt->bind_param("sii", $domaine_like, $limit, $offset);
  } elseif (!empty($search)) {
    $stmt->bind_param("ssii", $search_like, $search_like, $limit, $offset);
  } else {
    $stmt->bind_param("ii", $limit, $offset);
  }

  $stmt->execute();
  $result = $stmt->get_result();
  $profils = [];

  while ($row = $result->fetch_assoc()) {
    $bio = $row['biographie'] ?? '';
    $profils[] = [
      "id" => $row['id'],
      "nom" => $row['nom'],
      "biographie" => strlen($bio) > 150 ? substr($bio, 0, 150) . '...' : $bio,
      "photo" => $row['photo'] ? '/uploads/' . $row['photo'] : null,
      "domaines" => json_decode($row['domaines_recherche'] ?? '[]') ?? []
    ];
  }

  echo json_encode($profils);
  $stmt->close();
?>
