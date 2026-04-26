<?php
require_once 'database.php';

$stmt = $pdo->query("SELECT * FROM vehicule WHERE disponibilite = 'Disponible'");
$vehicules = $stmt->fetchAll();

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $vehicule = $_POST['vehicule'] ?? '';
    $nom = trim($_POST['nom'] ?? '');
    $prenom = trim($_POST['prenom'] ?? '');
    $telephone = trim($_POST['telephone'] ?? '');
    $age = intval($_POST['age'] ?? 0);
    $date_debut = $_POST['date_debut'] ?? '';
    $date_fin = $_POST['date_fin'] ?? '';

    if ($vehicule === '') $errors[] = "Veuillez choisir un véhicule.";
    if ($nom === '') $errors[] = "Le nom est obligatoire.";
    if ($prenom === '') $errors[] = "Le prénom est obligatoire.";
    if ($telephone === '') $errors[] = "Le téléphone est obligatoire.";
    if ($age < 18) $errors[] = "Vous devez avoir au moins 18 ans.";
    if ($date_debut === '' || $date_fin === '') $errors[] = "Les dates sont obligatoires.";

    if (empty($errors)) {

        $stmt = $pdo->prepare("SELECT montant_ttc, marque, modele FROM vehicule WHERE id_vehicule = ?");
        $stmt->execute([$vehicule]);
        $car = $stmt->fetch();

        $prixJour = $car['montant_ttc'];
        $vehiculeNom = $car['marque'] . ' ' . $car['modele'];

        $jours = (strtotime($date_fin) - strtotime($date_debut)) / 86400;
        if ($jours < 1) $jours = 1;

        $total = $prixJour * $jours;

        $stmt = $pdo->prepare("INSERT INTO reservation (id_vehicule, nom, prenom, telephone, age, date_debut, date_fin, montant_ttc)
                               VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([$vehicule, $nom, $prenom, $telephone, $age, $date_debut, $date_fin, $total]);

        header("Location: confirmation.php?nom=$nom&vehicule=$vehicule&debut=$date_debut&fin=$date_fin&montant=$total&jours=$jours");
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>DriveEasy — Réservation</title>
    <link rel="stylesheet" href="style2.css">
</head>
<body>

<nav class="nav">
    <a href="index.php" class="nav__logo">Drive<span>Easy</span></a>
    <ul class="nav__links">
        <li><a href="index.php">Accueil</a></li>
        <li><a href="vehicules.php">Nos véhicules</a></li>
        <li><a class="active" href="reservation.php">Réservation</a></li>
    </ul>
</nav>

<div class="page-wrapper">

    <div class="reservation-layout">

        <div class="reservation__sidebar">
            <h2>Réservez votre véhicule</h2>
            <p>Choisissez un véhicule dans le formulaire.</p>
        </div>

        <form class="reservation__form" method="POST">

            <?php if (!empty($errors)): ?>
                <div style="background:#8B3A3A;color:white;padding:1rem;border-radius:6px;margin-bottom:1rem;">
                    <?php foreach ($errors as $e): ?>
                        <p><?= htmlspecialchars($e) ?></p>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>

            <div class="form-group">
                <label>Véhicule *</label>
                <select name="vehicule">
                    <option value="">-- Choisir --</option>
                    <?php foreach($vehicules as $v): ?>
                        <option value="<?= $v['id_vehicule'] ?>">
                            <?= htmlspecialchars($v['marque'] . ' ' . $v['modele']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="form-group">
                <label>Nom *</label>
                <input type="text" name="nom">
            </div>

            <div class="form-group">
                <label>Prénom *</label>
                <input type="text" name="prenom">
            </div>

            <div class="form-group">
                <label>Téléphone *</label>
                <input type="text" name="telephone">
            </div>

            <div class="form-group">
                <label>Âge *</label>
                <input type="number" name="age">
            </div>

            <div class="form-group">
                <label>Date début *</label>
                <input type="date" name="date_debut">
            </div>

            <div class="form-group">
                <label>Date fin *</label>
                <input type="date" name="date_fin">
            </div>

            <button class="btn" type="submit">Réserver</button>

        </form>

    </div>

</div>

</body>
</html>
