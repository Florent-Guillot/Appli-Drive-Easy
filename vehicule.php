<?php
require_once 'database.php';

$id = intval($_GET['id'] ?? 0);
if ($id <= 0) { header("Location: vehicules.php"); exit; }

$stmt = $pdo->prepare("SELECT * FROM vehicule WHERE id_vehicule = ?");
$stmt->execute([$id]);
$v = $stmt->fetch();

if (!$v) { header("Location: vehicules.php"); exit; }

$boite = $v['boite'] ? "Automatique" : "Manuelle";
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title><?= htmlspecialchars($v['marque'] . " " . $v['modele']) ?></title>
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

    <a href="vehicules.php" class="back-link">← Retour</a>

    <div class="detail-container">

        <div class="detail-image">
            <img src="images/<?= htmlspecialchars($v['image']) ?>" alt="">
        </div>

        <div class="detail-sidebar">

            <h1><?= htmlspecialchars($v['marque']) ?> <?= htmlspecialchars($v['modele']) ?></h1>

            <div class="detail-info">
                <p><strong>Type :</strong> <?= htmlspecialchars($v['type']) ?></p>
                <p><strong>Places :</strong> <?= $v['capacite'] ?></p>
                <p><strong>Boîte :</strong> <?= $boite ?></p>
                <p><strong>Prix :</strong> <?= number_format($v['montant_ttc'], 2, ',', ' ') ?> €/jour</p>
            </div>

            <?php if ($v['description']): ?>
                <p class="detail-description"><?= nl2br(htmlspecialchars($v['description'])) ?></p>
            <?php endif; ?>

            <a href="reservation.php?id=<?= $v['id_vehicule'] ?>" class="btn btn--primary">
                Réserver →
            </a>

        </div>

    </div>

</div>

</body>
</html>
