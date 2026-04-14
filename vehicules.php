<?php
// ============================================================
// DriveEasy — Liste des véhicules
// Fichier : vehicules.php
// ============================================================
require_once 'config/database.php';

// Récupérer le filtre depuis l'URL (GET)
$filtre = trim($_GET['type'] ?? '');

// Construire la requête selon le filtre
if (!empty($filtre)) {
    $stmt = $pdo->prepare("SELECT * FROM vehicule WHERE type = ? ORDER BY prix_jour ASC");
    $stmt->execute([$filtre]);
} else {
    $stmt = $pdo->query("SELECT * FROM vehicule ORDER BY prix_jour ASC");
}
$vehicules = $stmt->fetchAll();

// Récupérer les types distincts pour les filtres
$stmtTypes = $pdo->query("SELECT DISTINCT type FROM vehicule ORDER BY type ASC");
$types = $stmtTypes->fetchAll(PDO::FETCH_COLUMN);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nos véhicules — DriveEasy</title>
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

    <!-- EN-TÊTE -->
    <div class="page-header">
        <p class="page-header__eyebrow">Notre collection</p>
        <h1 class="page-header__title">Tous les véhicules</h1>
        <p class="page-header__sub">Sélectionnez votre prochaine expérience automobile</p>
    </div>

    <!-- FILTRES PAR TYPE -->
    <div class="filters">
        <span class="filters__label">Filtrer :</span>
        <a href="vehicules.php" class="filter-btn <?= empty($filtre) ? 'active' : '' ?>">Tous</a>
        <?php foreach ($types as $type) : ?>
        <a href="vehicules.php?type=<?= urlencode($type) ?>"
           class="filter-btn <?= $filtre === $type ? 'active' : '' ?>">
            <?= htmlspecialchars($type) ?>
        </a>
        <?php endforeach; ?>
    </div>

    <!-- GRILLE DE VÉHICULES -->
    <div class="vehicules-section">
        <?php if (empty($vehicules)) : ?>
            <div style="padding: 4rem; text-align: center; color: var(--gris-clair);">
                Aucun véhicule trouvé pour ce type.
            </div>
        <?php else : ?>
        <div class="vehicules-grid">
            <?php foreach ($vehicules as $v) :
                $dispoClass = $v['disponibilite'] === 'Disponible' ? 'tag--ok' : 'tag--non';
                $boiteLabel = $v['boite'] ? 'Automatique' : 'Manuelle';
            ?>
            <a href="vehicule.php?id=<?= $v['id_vehicule'] ?>" class="vehicule-card">
                <div class="vehicule-card__img">
                    <?php if (!empty($v['image']) && file_exists('images/' . $v['image'])) : ?>
                        <img src="images/<?= htmlspecialchars($v['image']) ?>"
                             alt="<?= htmlspecialchars($v['marque'] . ' ' . $v['modele']) ?>">
                    <?php else : ?>
                        <span class="vehicule-card__img-placeholder">🚗</span>
                    <?php endif; ?>
                    <span class="tag tag--type"><?= htmlspecialchars($v['type']) ?></span>
                    <span class="tag tag--dispo <?= $dispoClass ?>"><?= htmlspecialchars($v['disponibilite']) ?></span>
                </div>
                <div class="vehicule-card__body">
                    <p class="vehicule-card__marque"><?= htmlspecialchars($v['marque']) ?></p>
                    <h2 class="vehicule-card__modele"><?= htmlspecialchars($v['modele']) ?></h2>
                    <div class="vehicule-card__specs">
                        <span class="spec">👥 <?= (int)$v['capacite'] ?> places</span>
                        <span class="spec">⚙️ <?= $boiteLabel ?></span>
                    </div>
                    <div class="vehicule-card__footer">
                        <div class="prix">
                            <?= number_format($v['prix_jour'], 2, ',', ' ') ?> €<small> / jour</small>
                        </div>
                        <span class="btn btn--dark" style="padding: 8px 16px; font-size: 0.75rem;">Voir →</span>
                    </div>
                </div>
            </a>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>
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
