<?php
// ============================================================
// DriveEasy — Page d'accueil
// Fichier : index.php
// ============================================================
require_once 'config/database.php';

// Récupérer 3 véhicules disponibles en vedette
$stmt = $pdo->query("SELECT * FROM vehicule WHERE disponibilite = 'Disponible' LIMIT 3");
$vedettes = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DriveEasy — Location de véhicules de collection</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

<!-- NAVIGATION -->
<nav class="nav">
    <a href="index.php" class="nav__logo">Drive<span>Easy</span></a>
    <ul class="nav__links">
        <li><a href="index.php" class="active">Accueil</a></li>
        <li><a href="vehicules.php">Nos véhicules</a></li>
        <li><a href="reservation.php">Réservation</a></li>
    </ul>
</nav>

<div class="page-wrapper">

    <!-- HERO -->
    <section class="hero">
        <span class="hero__badge">Collection &amp; Prestige depuis 1987</span>
        <h1 class="hero__title">L'excellence automobile,<br><em>à votre service.</em></h1>
        <p class="hero__text">
            DriveEasy vous propose une sélection exclusive de véhicules de collection et de prestige,
            disponibles à la location pour vos événements, voyages ou simples escapades.
        </p>
        <div class="hero__btns">
            <a href="vehicules.php" class="btn btn--primary">Découvrir la collection</a>
            <a href="reservation.php" class="btn btn--outline">Réserver maintenant</a>
        </div>
    </section>

    <!-- STATISTIQUES -->
    <div class="stats">
        <div class="stat">
            <div class="stat__num">47</div>
            <div class="stat__label">Véhicules disponibles</div>
        </div>
        <div class="stat">
            <div class="stat__num">1 200+</div>
            <div class="stat__label">Clients satisfaits</div>
        </div>
        <div class="stat">
            <div class="stat__num">35</div>
            <div class="stat__label">Ans d'expérience</div>
        </div>
        <div class="stat">
            <div class="stat__num">100%</div>
            <div class="stat__label">Assurance incluse</div>
        </div>
    </div>

    <!-- SERVICES -->
    <section class="services">
        <p class="section__eyebrow">Notre philosophie</p>
        <h2 class="section__title">Plus qu'une location,<br><em>une expérience unique</em></h2>
        <div class="services__grid">
            <div class="service-card">
                <span class="service-card__icon">🏎️</span>
                <h3>Collection exclusive</h3>
                <p>Des véhicules soigneusement sélectionnés pour leur rareté, leur histoire et leur condition exceptionnelle.</p>
            </div>
            <div class="service-card">
                <span class="service-card__icon">🛡️</span>
                <h3>Assurance complète</h3>
                <p>Chaque location inclut une couverture complète pour rouler l'esprit tranquille, où que vous alliez.</p>
            </div>
            <div class="service-card">
                <span class="service-card__icon">🔑</span>
                <h3>Livraison à domicile</h3>
                <p>Nous livrons votre véhicule à l'adresse de votre choix, partout en France métropolitaine.</p>
            </div>
        </div>
    </section>

    <!-- VÉHICULES EN VEDETTE -->
    <?php if (!empty($vedettes)) : ?>
    <section class="services" style="padding-top: 0;">
        <p class="section__eyebrow">Aperçu de la collection</p>
        <h2 class="section__title">Véhicules <em>à la une</em></h2>
        <div class="vehicules-grid">
            <?php foreach ($vedettes as $v) :
                $dispoClass = $v['disponibilite'] === 'Disponible' ? 'tag--ok' : 'tag--non';
                $boiteLabel = $v['boite'] ? 'Automatique' : 'Manuelle';
            ?>
            <a href="vehicule.php?id=<?= $v['id_vehicule'] ?>" class="vehicule-card">
                <div class="vehicule-card__img">
                    <?php if (!empty($v['image']) && file_exists('images/' . $v['image'])) : ?>
                        <img src="images/<?= htmlspecialchars($v['image']) ?>" alt="<?= htmlspecialchars($v['marque'] . ' ' . $v['modele']) ?>">
                    <?php else : ?>
                        <span class="vehicule-card__img-placeholder">🚗</span>
                    <?php endif; ?>
                    <span class="tag tag--type"><?= htmlspecialchars($v['type']) ?></span>
                    <span class="tag tag--dispo <?= $dispoClass ?>"><?= htmlspecialchars($v['disponibilite']) ?></span>
                </div>
                <div class="vehicule-card__body">
                    <p class="vehicule-card__marque"><?= htmlspecialchars($v['marque']) ?></p>
                    <h3 class="vehicule-card__modele"><?= htmlspecialchars($v['modele']) ?></h3>
                    <div class="vehicule-card__specs">
                        <span class="spec">👥 <?= (int)$v['capacite'] ?> places</span>
                        <span class="spec">⚙️ <?= $boiteLabel ?></span>
                    </div>
                    <div class="vehicule-card__footer">
                        <div class="prix"><?= number_format($v['prix_jour'], 2, ',', ' ') ?> €<small> / jour</small></div>
                        <span class="btn btn--dark" style="padding: 8px 16px; font-size: 0.75rem;">Voir →</span>
                    </div>
                </div>
            </a>
            <?php endforeach; ?>
        </div>
        <div style="text-align: center; padding: 2.5rem 0 1rem;">
            <a href="vehicules.php" class="btn btn--primary">Voir toute la collection →</a>
        </div>
    </section>
    <?php endif; ?>

    <!-- FOOTER -->
    <footer>
        <div class="footer__logo">Drive<span>Easy</span></div>
        <p>© <?= date('Y') ?> DriveEasy — Location de véhicules de collection</p>
        <p>contact@driveeasy.fr &nbsp;·&nbsp; 01 23 45 67 89</p>
    </footer>

</div>
</body>
</html>
