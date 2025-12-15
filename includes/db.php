<?php
// includes/db.php
// ubah credential sesuai environment
define('DB_HOST','sql309.infinityfree.com');
define('DB_NAME','if0_40686652_pitstop');
define('DB_USER','if0_40686652');
define('DB_PASS','Kelompok3Anjay');

$options = [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
];

try {
    $pdo = new PDO("mysql:host=".DB_HOST.";dbname=".DB_NAME.";charset=utf8mb4", DB_USER, DB_PASS, $options);
} catch (Exception $e) {
    die("Connection failed: " . $e->getMessage());
}
