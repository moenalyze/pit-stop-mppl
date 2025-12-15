<?php
// includes/db.php
// ubah credential sesuai environment
define('DB_HOST','127.0.0.1');
define('DB_NAME','pitstop');
define('DB_USER','root');
define('DB_PASS','');

$options = [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
];

try {
    $pdo = new PDO("mysql:host=".DB_HOST.";dbname=".DB_NAME.";charset=utf8mb4", DB_USER, DB_PASS, $options);
} catch (Exception $e) {
    die("Connection failed: " . $e->getMessage());
}
