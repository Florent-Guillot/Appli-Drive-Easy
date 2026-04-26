<?php
require_once 'database.php';

$filtre = $_GET['type'] ?? '';

if ($filtre) {
    $stmt = $pdo->prepare("SELECT * FROM vehicule WHERE type = ?");
    $stmt->execute([$filtre]);
} else {
    $stmt = $pdo->query("SELECT * FROM vehicule");
}
$vehicules = $stmt->fetchAll();

$types = $pdo->query("SELECT DISTINCT type FROM vehicule")->fetchAll(PDO::FETCH_COLUMN);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Nos véhicules</title>
    <link rel="stylesheet" href="style2.css">
</head>
<body>

<nav class="nav">
    <a href="index.php" class="nav__logo">Drive<span>Easy</span></a>
    <ul class="nav__links">
        <li><a href="index.php">Accueil</a></li>
        <li><a class="active" href="vehicules.php">Nos véhicules</a></li>
        <li><a href="reservation.php">Réservation</a></li>
    </ul>
</nav>

<div class="page-wrapper">

    <h1 style="padding:1.5rem;font-family:'Playfair Display',serif;">Tous les véhicules</h1>

    <div style="padding:1rem 1.5rem;display:flex;gap:.5rem;flex-wrap:wrap;">
        <a href="vehicules.php" class="btn btn--outline <?= !$filtre ? 'active' : '' ?>">Tous</a>
        <?php foreach ($types as $t): ?>
            <a href="vehicules.php?type=<?= urlencode($t) ?>"
               class="btn btn--outline <?= $filtre === $t ? 'active' : '' ?>">
               <?= htmlspecialchars($t) ?>
            </a>
        <?php endforeach; ?>
    </div>

    <div class="vehicules-grid">
        <?php foreach ($vehicules as $v): ?>
            <a href="vehicule.php?id=<?= $v['id_vehicule'] ?>" class="vehicule-card">
                <img src="images/<?= $v['image'] ?>" alt="">
                <div class="vehicule-card__body">
                    <p><?= htmlspecialchars($v['marque']) ?></p>
                    <h3><?= htmlspecialchars($v['modele']) ?></h3>
                    <p class="prix"><?= number_format($v['prix_jour'], 2, ',', ' ') ?> €/jour</p>
                </div>
            </a>
        <?php endforeach; ?>
    </div>

</div>

</body>
</html>
