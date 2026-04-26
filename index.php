<?php
require_once 'database.php';

// Récupérer 3 véhicules disponibles
$stmt = $pdo->query("SELECT * FROM vehicule WHERE disponibilite = 'Disponible' LIMIT 3");
$vedettes = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>DriveEasy — Accueil</title>
    <link rel="stylesheet" href="style2.css">
</head>
<body>

<nav class="nav">
    <a href="index.php" class="nav__logo">Drive<span>Easy</span></a>
    <ul class="nav__links">
        <li><a class="active" href="index.php">Accueil</a></li>
        <li><a href="vehicules.php">Nos véhicules</a></li>
        <li><a href="reservation.php">Réservation</a></li>
    </ul>
</nav>

<div class="page-wrapper">

    <section class="hero">
        <h1>L'excellence automobile</h1>
        <p>Location de véhicules de collection et de prestige.</p>
        <a href="vehicules.php" class="btn btn--primary">Voir la collection</a>
    </section>

    <h2 style="padding:1.5rem;font-family:'Playfair Display',serif;">Véhicules à la une</h2>

    <div class="vehicules-grid">
        <?php foreach ($vedettes as $v): ?>
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
<footer class="footer">
    <div class="footer__logo">DriveEasy</div>
    <p>Location de véhicules de collection • Depuis 1984</p>
    <p class="footer__copy">© 2026 DriveEasy. Tous droits réservés.</p>
</footer>

</body>
</html>
