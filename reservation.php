<?php
// ============================================================
// DriveEasy — Formulaire de réservation + traitement POST
// Fichier : reservation.php
// ============================================================
require_once 'config/database.php';

$erreurs = [];
$succes  = false;

// --- TRAITEMENT DU FORMULAIRE (POST) ---
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // 1. Récupérer et nettoyer les données
    $nom        = trim($_POST['nom'] ?? '');
    $prenom     = trim($_POST['prenom'] ?? '');
    $telephone  = trim($_POST['telephone'] ?? '');
    $age_client = intval($_POST['age_client'] ?? 0);
    $id_vehicule = intval($_POST['id_vehicule'] ?? 0);
    $date_debut = $_POST['date_debut'] ?? '';
    $date_fin   = $_POST['date_fin'] ?? '';

    // 2. Validation des champs obligatoires
    if (empty($nom))        $erreurs['nom']        = 'Le nom est obligatoire.';
    if (empty($prenom))     $erreurs['prenom']     = 'Le prénom est obligatoire.';
    if (empty($telephone))  $erreurs['telephone']  = 'Le téléphone est obligatoire.';
    if ($age_client < 18)   $erreurs['age_client'] = 'Vous devez avoir au moins 18 ans.';
    if ($id_vehicule <= 0)  $erreurs['id_vehicule'] = 'Veuillez choisir un véhicule.';
    if (empty($date_debut)) $erreurs['date_debut'] = 'La date de début est obligatoire.';
    if (empty($date_fin))   $erreurs['date_fin']   = 'La date de fin est obligatoire.';

    // 3. Vérifier la cohérence des dates
    if (empty($erreurs['date_debut']) && empty($erreurs['date_fin'])) {
        $d1 = new DateTime($date_debut);
        $d2 = new DateTime($date_fin);
        if ($d2 <= $d1) {
            $erreurs['date_fin'] = 'La date de fin doit être postérieure à la date de début.';
        }
    }

    // 4. Vérifier que le véhicule existe et est disponible
    if (empty($erreurs['id_vehicule'])) {
        $stmtV = $pdo->prepare("SELECT * FROM vehicule WHERE id_vehicule = ? AND disponibilite = 'Disponible'");
        $stmtV->execute([$id_vehicule]);
        $vehiculeChoisi = $stmtV->fetch();
        if (!$vehiculeChoisi) {
            $erreurs['id_vehicule'] = 'Ce véhicule n\'est pas disponible.';
        }
    }

    // 5. Calculer le montant TTC si tout est valide
    if (empty($erreurs)) {
        $nbJours     = (new DateTime($date_debut))->diff(new DateTime($date_fin))->days;
        $montant_ttc = $nbJours * $vehiculeChoisi['prix_jour'];

        // 6. Insérer avec une requête préparée (sécurité anti-injection SQL)
        $sql = "INSERT INTO reservation
                    (nom, prenom, telephone, date_debut, date_fin, montant_ttc, age_client, id_vehicule)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?)";

        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            $nom,
            $prenom,
            $telephone,
            $date_debut,
            $date_fin,
            $montant_ttc,
            $age_client,
            $id_vehicule
        ]);

        // 7. Rediriger vers la page de confirmation
        header('Location: confirmation.php?nom=' . urlencode($prenom . ' ' . $nom)
            . '&vehicule=' . urlencode($vehiculeChoisi['marque'] . ' ' . $vehiculeChoisi['modele'])
            . '&debut='    . urlencode($date_debut)
            . '&fin='      . urlencode($date_fin)
            . '&montant='  . urlencode(number_format($montant_ttc, 2, ',', ' '))
            . '&jours='    . $nbJours);
        exit;
    }
}

// --- RÉCUPÉRER LES VÉHICULES DISPONIBLES POUR LA LISTE ---
$stmtVehicules = $pdo->query("SELECT id_vehicule, marque, modele, prix_jour FROM vehicule WHERE disponibilite = 'Disponible' ORDER BY marque");
$vehicules = $stmtVehicules->fetchAll();

// Pré-sélectionner depuis l'URL (GET depuis vehicule.php)
$preselectId = intval($_GET['id'] ?? 0);

// Pour afficher le résumé si pré-sélection
$vehiculeResume = null;
if ($preselectId > 0) {
    foreach ($vehicules as $vr) {
        if ($vr['id_vehicule'] == $preselectId) {
            $vehiculeResume = $vr;
            break;
        }
    }
}

// Date minimum = aujourd'hui
$today = date('Y-m-d');
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Réservation — DriveEasy</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

<!-- NAVIGATION -->
<nav class="nav">
    <a href="index.php" class="nav__logo">Drive<span>Easy</span></a>
    <ul class="nav__links">
        <li><a href="index.php">Accueil</a></li>
        <li><a href="vehicules.php">Nos véhicules</a></li>
        <li><a href="reservation.php" class="active">Réservation</a></li>
    </ul>
</nav>

<div class="page-wrapper">
    <div class="reservation-layout">

        <!-- SIDEBAR GAUCHE -->
        <div class="reservation__sidebar">
            <a href="vehicules.php" class="back-link" style="color: rgba(255,255,255,0.45);">← Retour à la collection</a>
            <h2>Réservez votre <em>expérience</em></h2>
            <p>
                Remplissez le formulaire ci-contre pour réserver votre véhicule.
                Un conseiller DriveEasy vous contactera dans les 2 heures pour confirmer votre réservation.
            </p>

            <?php if ($vehiculeResume) : ?>
            <div class="vehicule-resume">
                <div class="vehicule-resume__icon">🚗</div>
                <div>
                    <p class="vehicule-resume__marque"><?= htmlspecialchars($vehiculeResume['marque']) ?></p>
                    <p class="vehicule-resume__modele"><?= htmlspecialchars($vehiculeResume['modele']) ?></p>
                    <p class="vehicule-resume__prix">
                        <?= number_format($vehiculeResume['prix_jour'], 2, ',', ' ') ?> €<small> / jour</small>
                    </p>
                </div>
            </div>
            <?php else : ?>
            <div class="vehicule-resume">
                <div class="vehicule-resume__icon">🔑</div>
                <div>
                    <p class="vehicule-resume__marque">Étape suivante</p>
                    <p class="vehicule-resume__modele">Choisissez votre véhicule</p>
                </div>
            </div>
            <?php endif; ?>
        </div>

        <!-- FORMULAIRE DROIT -->
        <div class="reservation__form">
            <p class="form-title">Votre réservation</p>

            <?php if (!empty($erreurs)) : ?>
            <div class="alert alert--error">
                Veuillez corriger les erreurs ci-dessous avant de soumettre.
            </div>
            <?php endif; ?>

            <form method="POST" action="reservation.php" novalidate>

                <!-- VÉHICULE -->
                <div class="form-group">
                    <label for="id_vehicule">Véhicule *</label>
                    <select name="id_vehicule" id="id_vehicule"
                            class="<?= isset($erreurs['id_vehicule']) ? 'error' : '' ?>"
                            onchange="updateMontant()" required>
                        <option value="">— Choisir un véhicule —</option>
                        <?php foreach ($vehicules as $vItem) :
                            $selectionne = (isset($_POST['id_vehicule'])
                                ? $_POST['id_vehicule'] == $vItem['id_vehicule']
                                : $preselectId == $vItem['id_vehicule']);
                        ?>
                        <option value="<?= $vItem['id_vehicule'] ?>"
                                data-prix="<?= $vItem['prix_jour'] ?>"
                            <?= $selectionne ? 'selected' : '' ?>>
                            <?= htmlspecialchars($vItem['marque'] . ' ' . $vItem['modele']) ?>
                            — <?= number_format($vItem['prix_jour'], 2, ',', ' ') ?> €/j
                        </option>
                        <?php endforeach; ?>
                    </select>
                    <?php if (isset($erreurs['id_vehicule'])) : ?>
                        <span class="error-msg" style="display:block;"><?= htmlspecialchars($erreurs['id_vehicule']) ?></span>
                    <?php endif; ?>
                </div>

                <!-- NOM / PRÉNOM -->
                <div class="form-row">
                    <div class="form-group">
                        <label for="nom">Nom *</label>
                        <input type="text" id="nom" name="nom"
                               placeholder="Dupont"
                               value="<?= htmlspecialchars($_POST['nom'] ?? '') ?>"
                               class="<?= isset($erreurs['nom']) ? 'error' : '' ?>"
                               required>
                        <?php if (isset($erreurs['nom'])) : ?>
                            <span class="error-msg" style="display:block;"><?= htmlspecialchars($erreurs['nom']) ?></span>
                        <?php endif; ?>
                    </div>
                    <div class="form-group">
                        <label for="prenom">Prénom *</label>
                        <input type="text" id="prenom" name="prenom"
                               placeholder="Jean"
                               value="<?= htmlspecialchars($_POST['prenom'] ?? '') ?>"
                               class="<?= isset($erreurs['prenom']) ? 'error' : '' ?>"
                               required>
                        <?php if (isset($erreurs['prenom'])) : ?>
                            <span class="error-msg" style="display:block;"><?= htmlspecialchars($erreurs['prenom']) ?></span>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- TÉLÉPHONE / ÂGE -->
                <div class="form-row">
                    <div class="form-group">
                        <label for="telephone">Téléphone *</label>
                        <input type="tel" id="telephone" name="telephone"
                               placeholder="06 00 00 00 00"
                               value="<?= htmlspecialchars($_POST['telephone'] ?? '') ?>"
                               class="<?= isset($erreurs['telephone']) ? 'error' : '' ?>"
                               required>
                        <?php if (isset($erreurs['telephone'])) : ?>
                            <span class="error-msg" style="display:block;"><?= htmlspecialchars($erreurs['telephone']) ?></span>
                        <?php endif; ?>
                    </div>
                    <div class="form-group">
                        <label for="age_client">Âge du conducteur *</label>
                        <input type="number" id="age_client" name="age_client"
                               placeholder="25" min="18" max="99"
                               value="<?= htmlspecialchars($_POST['age_client'] ?? '') ?>"
                               class="<?= isset($erreurs['age_client']) ? 'error' : '' ?>"
                               required>
                        <?php if (isset($erreurs['age_client'])) : ?>
                            <span class="error-msg" style="display:block;"><?= htmlspecialchars($erreurs['age_client']) ?></span>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- DATES -->
                <div class="form-row">
                    <div class="form-group">
                        <label for="date_debut">Date de début *</label>
                        <input type="date" id="date_debut" name="date_debut"
                               min="<?= $today ?>"
                               value="<?= htmlspecialchars($_POST['date_debut'] ?? '') ?>"
                               class="<?= isset($erreurs['date_debut']) ? 'error' : '' ?>"
                               onchange="updateMontant()" required>
                        <?php if (isset($erreurs['date_debut'])) : ?>
                            <span class="error-msg" style="display:block;"><?= htmlspecialchars($erreurs['date_debut']) ?></span>
                        <?php endif; ?>
                    </div>
                    <div class="form-group">
                        <label for="date_fin">Date de fin *</label>
                        <input type="date" id="date_fin" name="date_fin"
                               min="<?= $today ?>"
                               value="<?= htmlspecialchars($_POST['date_fin'] ?? '') ?>"
                               class="<?= isset($erreurs['date_fin']) ? 'error' : '' ?>"
                               onchange="updateMontant()" required>
                        <?php if (isset($erreurs['date_fin'])) : ?>
                            <span class="error-msg" style="display:block;"><?= htmlspecialchars($erreurs['date_fin']) ?></span>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- MONTANT CALCULÉ -->
                <div class="montant-display" id="montant-display" style="display: none;">
                    <div>
                        <p class="montant-display__label">Total estimé TTC</p>
                        <p class="montant-display__duree" id="duree-label"></p>
                    </div>
                    <p class="montant-display__val" id="montant-val">0 €</p>
                </div>

                <button type="submit" class="btn btn--primary btn--full">
                    Confirmer la réservation →
                </button>

                <p style="font-size: 0.75rem; color: var(--gris-clair); margin-top: 1rem; text-align: center; line-height: 1.5;">
                    En soumettant ce formulaire, vous acceptez nos conditions générales de location.
                </p>

            </form>
        </div>
    </div>

    <!-- FOOTER -->
    <footer>
        <div class="footer__logo">Drive<span>Easy</span></div>
        <p>© <?= date('Y') ?> DriveEasy — Location de véhicules de collection</p>
        <p>contact@driveeasy.fr &nbsp;·&nbsp; 01 23 45 67 89</p>
    </footer>
</div>

<script>
// Mise à jour dynamique du montant TTC côté client
function updateMontant() {
    const sel    = document.getElementById('id_vehicule');
    const debut  = document.getElementById('date_debut').value;
    const fin    = document.getElementById('date_fin').value;
    const block  = document.getElementById('montant-display');

    // Mettre à jour le min de date_fin en fonction de date_debut
    if (debut) {
        document.getElementById('date_fin').min = debut;
    }

    if (!sel.value || !debut || !fin) { block.style.display = 'none'; return; }

    const option  = sel.options[sel.selectedIndex];
    const prix    = parseFloat(option.dataset.prix);
    const d1      = new Date(debut);
    const d2      = new Date(fin);
    const jours   = Math.round((d2 - d1) / 86400000);

    if (jours <= 0) { block.style.display = 'none'; return; }

    const total = jours * prix;
    document.getElementById('montant-val').textContent =
        total.toLocaleString('fr-FR', { minimumFractionDigits: 2 }) + ' €';
    document.getElementById('duree-label').textContent =
        jours + ' jour' + (jours > 1 ? 's' : '') + ' × ' +
        prix.toLocaleString('fr-FR', { minimumFractionDigits: 2 }) + ' €/j';
    block.style.display = 'flex';
}

// Initialiser le calcul si des valeurs sont déjà présentes (après erreur POST)
document.addEventListener('DOMContentLoaded', updateMontant);
</script>

</body>
</html>
