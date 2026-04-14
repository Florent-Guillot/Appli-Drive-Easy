<?php
// ============================================================
// DriveEasy — Connexion à la base de données (PDO)
// Fichier : config/database.php
// ============================================================

define('DB_HOST', '127.0.0.1');
define('DB_NAME', 'driveeasy');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_CHARSET', 'utf8mb4');

$dsn = 'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=' . DB_CHARSET;

$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

try {
    $pdo = new PDO($dsn, DB_USER, DB_PASS, $options);
} catch (PDOException $e) {
    // En production, ne jamais afficher le message d'erreur brut
    die('<p style="color:red;font-family:sans-serif;">Erreur de connexion à la base de données. Vérifiez que XAMPP est lancé.</p>');
}
