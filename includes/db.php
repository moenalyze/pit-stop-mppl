<?php
// includes/db.php
// ubah credential sesuai environment
define('DB_HOST','DB_HOST');
define('DB_NAME','DB_NAME');
define('DB_USER','DB_USER');
define('DB_PASS','DB_PASS');

$options = [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
];

try {
    $pdo = new PDO("mysql:host=".DB_HOST.";dbname=".DB_NAME.";charset=utf8mb4", DB_USER, DB_PASS, $options);
} catch (Exception $e) {
    die("Connection failed: " . $e->getMessage());
}
