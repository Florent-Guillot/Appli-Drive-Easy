<?php
// ============================================================
// DriveEasy — Fiche détail d'un véhicule
// Fichier : vehicule.php
// ============================================================
require_once 'config/database.php';

// Récupérer l'id depuis l'URL et le sécuriser
$id = intval($_GET['id'] ?? 0);

if ($id <= 0) {
    header('Location: vehicules.php');
    exit;
}

// Récupérer le véhicule correspondant
$stmt = $pdo->prepare("SELECT * FROM vehicule WHERE id_vehicule = ?");
$stmt->execute([$id]);
$v = $stmt->fetch();

// Si le véhicule n'existe pas, rediriger
if (!$v) {
    header('Location: vehicules.php');
    exit;
}

$boiteLabel  = $v['boite'] ? 'Automatique' : 'Manuelle';
$dispoClass  = $v['disponibilite'] === 'Disponible' ? 'tag--ok' : 'tag--non';
$pageTitle   = htmlspecialchars($v['marque'] . ' ' . $v['modele']) . ' — DriveEasy';
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $pageTitle ?></title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

<!-- NAVIGATION -->
<nav class="nav">
    <a href="index.php" class="nav__logo">Drive<span>Easy</span></a>
    <ul class="nav__links">
        <li><a href="index.php">Accueil</a></li>
        <li><a href="vehicules.php" class="active">Nos véhicules</a></li>
        <li><a href="reservation.php">Réservation</a></li>
    </ul>
</nav>

<div class="page-wrapper">
    <div class="detail-layout">

        <!-- VISUEL GAUCHE -->
        <div class="detail__visual">
            <?php if (!empty($v['image']) && file_exists('images/' . $v['image'])) : ?>
                <img src="images/<?= htmlspecialchars($v['image']) ?>"
                     alt="<?= htmlspecialchars($v['marque'] . ' ' . $v['modele']) ?>">
            <?php else : ?>
                <span class="detail__big-icon">🚗</span>
            <?php endif; ?>

            <div class="detail__overlay">
                <span class="tag tag--type" style="position: static; display: inline-block; margin-bottom: 8px;">
                    <?= htmlspecialchars($v['type']) ?>
                </span>
                <h2 style="font-family: 'Playfair Display', serif; font-size: 1.8rem; color: white; line-height: 1.2;">
                    <?= htmlspecialchars($v['marque']) ?><br><?= htmlspecialchars($v['modele']) ?>
                </h2>
                <span class="tag tag--dispo <?= $dispoClass ?>" style="position: static; display: inline-block; margin-top: 8px;">
                    <?= htmlspecialchars($v['disponibilite']) ?>
                </span>
            </div>
        </div>

        <!-- CONTENU DROIT -->
        <div class="detail__content">
            <a href="vehicules.php" class="back-link">← Retour à la collection</a>

            <p class="detail__eyebrow"><?= htmlspecialchars($v['marque']) ?></p>
            <h1 class="detail__title"><?= htmlspecialchars($v['modele']) ?></h1>

            <!-- CARACTÉRISTIQUES -->
            <div class="detail__specs-grid">
                <div class="detail__spec-item">
                    <p class="detail__spec-label">Type</p>
                    <p class="detail__spec-val"><?= htmlspecialchars($v['type']) ?></p>
                </div>
                <div class="detail__spec-item">
                    <p class="detail__spec-label">Places</p>
                    <p class="detail__spec-val"><?= (int)$v['capacite'] ?></p>
                </div>
                <div class="detail__spec-item">
                    <p class="detail__spec-label">Boîte</p>
                    <p class="detail__spec-val"><?= $boiteLabel ?></p>
                </div>
                <div class="detail__spec-item">
                    <p class="detail__spec-label">Disponibilité</p>
                    <p class="detail__spec-val"><?= htmlspecialchars($v['disponibilite']) ?></p>
                </div>
            </div>

            <!-- DESCRIPTION -->
            <?php if (!empty($v['description'])) : ?>
            <p class="detail__desc"><?= htmlspecialchars($v['description']) ?></p>
            <?php endif; ?>

            <!-- PRIX -->
            <p class="detail__prix">
                <?= number_format($v['prix_jour'], 2, ',', ' ') ?> €<small> / jour TTC</small>
            </p>

            <!-- BOUTON RÉSERVATION -->
            <?php if ($v['disponibilite'] === 'Disponible') : ?>
                <a href="reservation.php?id=<?= $v['id_vehicule'] ?>" class="btn btn--primary btn--full">
                    Réserver ce véhicule →
                </a>
            <?php else : ?>
                <button class="btn btn--primary btn--full" disabled
                        style="opacity: 0.4; cursor: not-allowed;">
                    Véhicule non disponible
                </button>
            <?php endif; ?>
        </div>

    </div>

    <!-- FOOTER -->
    <footer>
        <div class="footer__logo">Drive<span>Easy</span></div>
        <p>© <?= date('Y') ?> DriveEasy — Location de véhicules de collection</p>
        <p>contact@driveeasy.fr &nbsp;·&nbsp; 01 23 45 67 89</p>
    </footer>
</div>

</body>
</html>
