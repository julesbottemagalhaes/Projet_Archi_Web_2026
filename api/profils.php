<?php
  require __DIR__ . '/../inc/db.php';

  $page = max(1, intval($_GET['page'] ?? 1));
  $limit = 10;
  $offset = ($page - 1) * $limit;
  $domaine = strtolower(clean_string($_GET['domaine'] ?? '', 30));
  $search = clean_string($_GET['search'] ?? '', 100);

  if ($domaine !== '' && !in_array($domaine, allowed_cv_domaines(), true)) {
    json_response(["error" => "Domaine invalide"], 400);
  }

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
      "photo" => $row['photo'] ?: null,
      "domaines" => normalise_cv_domaines($row['domaines_recherche'] ?? [])
    ];
  }

  $stmt->close();
  json_response($profils);
