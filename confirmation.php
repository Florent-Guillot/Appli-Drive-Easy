<?php
// ============================================================
// DriveEasy — Page de confirmation de réservation
// Fichier : confirmation.php
// ============================================================

// Récupérer les informations depuis l'URL (passées par reservation.php)
$nom      = htmlspecialchars($_GET['nom']      ?? 'Client');
$vehicule = htmlspecialchars($_GET['vehicule'] ?? '—');
$debut    = htmlspecialchars($_GET['debut']    ?? '—');
$fin      = htmlspecialchars($_GET['fin']      ?? '—');
$montant  = htmlspecialchars($_GET['montant']  ?? '—');
$jours    = (int)($_GET['jours']               ?? 0);

// Formater les dates en français
function formatDateFr(string $date): string {
    if ($date === '—') return '—';
    try {
        $d = new DateTime($date);
        $mois = ['janvier','février','mars','avril','mai','juin','juillet',
                 'août','septembre','octobre','novembre','décembre'];
        return $d->format('j') . ' ' . $mois[(int)$d->format('n') - 1] . ' ' . $d->format('Y');
    } catch (Exception $e) {
        return $date;
    }
}

$debutFr = formatDateFr($debut);
$finFr   = formatDateFr($fin);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Réservation confirmée — DriveEasy</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

<!-- NAVIGATION -->
<nav class="nav">
    <a href="index.php" class="nav__logo">Drive<span>Easy</span></a>
    <ul class="nav__links">
        <li><a href="index.php">Accueil</a></li>
        <li><a href="vehicules.php">Nos véhicules</a></li>
        <li><a href="reservation.php">Réservation</a></li>
    </ul>
</nav>

<div class="page-wrapper">
    <div class="confirmation-wrapper">
        <div class="confirmation-card">

            <!-- ICÔNE SUCCÈS -->
            <div class="confirmation-card__check">✓</div>

            <h1>Réservation confirmée !</h1>
            <p>
                Merci <?= $nom ?>, votre demande a bien été enregistrée.
                Un conseiller DriveEasy vous contactera dans les 2 heures pour finaliser votre réservation.
            </p>

            <!-- RÉCAPITULATIF -->
            <div class="confirmation-details">
                <div class="confirmation-row">
                    <span>Véhicule</span>
                    <span><?= $vehicule ?></span>
                </div>
                <div class="confirmation-row">
                    <span>Client</span>
                    <span><?= $nom ?></span>
                </div>
                <div class="confirmation-row">
                    <span>Date de début</span>
                    <span><?= $debutFr ?></span>
                </div>
                <div class="confirmation-row">
                    <span>Date de fin</span>
                    <span><?= $finFr ?></span>
                </div>
                <?php if ($jours > 0) : ?>
                <div class="confirmation-row">
                    <span>Durée</span>
                    <span><?= $jours ?> jour<?= $jours > 1 ? 's' : '' ?></span>
                </div>
                <?php endif; ?>
                <div class="confirmation-row confirmation-row--total">
                    <span>Total TTC</span>
                    <span><?= $montant ?> €</span>
                </div>
            </div>

            <div class="confirmation-card__btns">
                <a href="index.php" class="btn btn--primary btn--full">Retour à l'accueil</a>
                <a href="vehicules.php" class="btn btn--outline btn--full">Voir d'autres véhicules</a>
            </div>

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
