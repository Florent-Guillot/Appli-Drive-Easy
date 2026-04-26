<?php
require_once 'database.php';

// Récupération des paramètres envoyés
$nom      = htmlspecialchars($_GET['nom'] ?? 'Client');
$vehicule = htmlspecialchars($_GET['vehicule'] ?? '');
$debut    = htmlspecialchars($_GET['debut'] ?? '');
$fin      = htmlspecialchars($_GET['fin'] ?? '');
$montant  = htmlspecialchars($_GET['montant'] ?? '0');
$jours    = intval($_GET['jours'] ?? 0);

// Récupération du nom du véhicule
$stmt = $pdo->prepare("SELECT marque, modele FROM vehicule WHERE id_vehicule = ?");
$stmt->execute([$vehicule]);
$car = $stmt->fetch();

$vehiculeNom = $car ? $car['marque'] . ' ' . $car['modele'] : '—';

// Format FR
function formatFr($date) {
    return $date ? date('d/m/Y', strtotime($date)) : '—';
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Réservation confirmée</title>
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

    <div class="confirmation-card-vintage">

        <div class="confirmation-icon">✓</div>

        <h1>Réservation confirmée</h1>
        <p class="confirmation-text">
            Merci <strong><?= $nom ?></strong>, votre réservation a bien été enregistrée.
        </p>

        <div class="confirmation-details-vintage">
            <p><span>Véhicule :</span> <?= $vehiculeNom ?></p>
            <p><span>Début :</span> <?= formatFr($debut) ?></p>
            <p><span>Fin :</span> <?= formatFr($fin) ?></p>
            <p><span>Durée :</span> <?= $jours ?> jour<?= $jours > 1 ? 's' : '' ?></p>
            <p><span>Total :</span> <?= $montant ?> €</p>
        </div>

        <a href="index.php" class="btn">Retour à l'accueil</a>
        <a href="vehicules.php" class="btn" style="margin-top:.5rem;">Voir d'autres véhicules</a>

    </div>

</div>

</body>
</html>
