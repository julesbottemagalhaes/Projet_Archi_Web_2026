<?php
  require_once __DIR__ . '/../inc/db.php';

  $id = $_GET['id'] ?? null;

  if (empty($id) || !is_numeric($id)) {
    json_response(["error" => "ID invalide"], 400);
  }

  $stmt = $connection->prepare(
    "SELECT id, nom, titre, email, telephone, ville, date_naissance,
            linkedin, github, photo, biographie, langues, projets,
            centres_interet, domaines_recherche, donnees_json
     FROM etudiants WHERE id = ?"
  );
  $stmt->bind_param("i", $id);
  $stmt->execute();
  $result = $stmt->get_result();

  if ($result->num_rows == 0) {
    json_response(["error" => "Profil non trouvé"], 404);
  }

  $row = $result->fetch_assoc();
  $stmt->close();

  if (!empty($row['donnees_json'])) {
    $cv = json_decode($row['donnees_json'], true);
    if ($cv) {
      $cv['photo'] = $row['photo'] ?? ($cv['photo'] ?? '');
      $cv['domainesRecherche'] = normalise_cv_domaines($row['domaines_recherche'] ?? ($cv['domainesRecherche'] ?? []));
      json_response($cv);
    }
  }

  $stmt_comp = $connection->prepare(
    "SELECT competence FROM competences WHERE etudiant_id = ?"
  );
  $stmt_comp->bind_param("i", $id);
  $stmt_comp->execute();
  $rows_comp = $stmt_comp->get_result()->fetch_all(MYSQLI_ASSOC);
  $competences = array_column($rows_comp, 'competence');
  $stmt_comp->close();

  $stmt_exp = $connection->prepare(
    "SELECT entreprise, poste, date_debut, date_fin, description FROM experiences WHERE etudiant_id = ?"
  );
  $stmt_exp->bind_param("i", $id);
  $stmt_exp->execute();
  $rows_exp = $stmt_exp->get_result()->fetch_all(MYSQLI_ASSOC);
  $stmt_exp->close();

  $experiences = [];
  foreach ($rows_exp as $exp) {
    $titre = $exp['entreprise'];
    if ($exp['poste']) $titre .= ' - ' . $exp['poste'];
    $dates = '';
    if ($exp['date_debut']) $dates = substr($exp['date_debut'], 0, 4);
    if ($exp['date_fin']) $dates .= ' - ' . substr($exp['date_fin'], 0, 4);
    $experiences[] = [
      "titre" => $titre,
      "dates" => $dates,
      "description" => $exp['description'] ?? ''
    ];
  }

  $stmt_form = $connection->prepare(
    "SELECT ecole, diplome, date_fin, description FROM formations WHERE etudiant_id = ?"
  );
  $stmt_form->bind_param("i", $id);
  $stmt_form->execute();
  $rows_form = $stmt_form->get_result()->fetch_all(MYSQLI_ASSOC);
  $stmt_form->close();

  $formations = [];
  foreach ($rows_form as $form) {
    $titre = $form['diplome'];
    if ($form['ecole']) $titre .= ' - ' . $form['ecole'];
    $dates = $form['date_fin'] ? substr($form['date_fin'], 0, 4) : '';
    $formations[] = [
      "titre" => $titre,
      "dates" => $dates,
      "description" => $form['description'] ?? ''
    ];
  }

  $nomParts = explode(' ', $row['nom'], 2);
  $prenom = $nomParts[0] ?? '';
  $nom = $nomParts[1] ?? '';

  $is_pro = isset($_SESSION['user_type']) && in_array($_SESSION['user_type'], ['company', 'admin']);

  echo json_encode([
    "id" => $row['id'],
    "prenom" => $prenom,
    "nom" => $nom,
    "titre" => $row['titre'] ?? '',
    "email" => $is_pro ? $row['email'] : null,
    "telephone" => $is_pro ? ($row['telephone'] ?? '') : null,
    "ville" => $row['ville'] ?? '',
    "dateNaissance" => $is_pro ? ($row['date_naissance'] ?? '') : null,
    "linkedin" => $is_pro ? ($row['linkedin'] ?? '') : null,
    "github" => $row['github'] ?? '',
    "photo" => $row['photo'] ?? '',
    "profil" => $row['biographie'] ?? '',
    "domainesRecherche" => normalise_cv_domaines($row['domaines_recherche'] ?? []),
    "competences" => $competences,
    "langues" => decode_json_array($row['langues'] ?? '[]'),
    "formations" => $formations,
    "experiences" => $experiences,
    "projets" => decode_json_array($row['projets'] ?? '[]'),
    "centresInteret" => decode_json_array($row['centres_interet'] ?? '[]')
  ]);
