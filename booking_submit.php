<?php
// booking_submit.php
require 'includes/db.php';
require 'includes/auth.php';

if (!is_logged_in() || $_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: booking.php');
    exit;
}

$user_id = $_SESSION['user']['id'];
$plate = trim($_POST['plate']);
$service_id = intval($_POST['service_id']);
$booking_date = $_POST['booking_date'];
$time_slot = $_POST['time_slot'] ?: null;
$location = $_POST['location'] ?? 'workshop';

// create or find vehicle
$stmt = $pdo->prepare("SELECT id FROM vehicles WHERE user_id = ? AND plate = ?");
$stmt->execute([$user_id, $plate]);
$veh = $stmt->fetch();
if (!$veh) {
    $stmt = $pdo->prepare("INSERT INTO vehicles (user_id, plate) VALUES (?,?)");
    $stmt->execute([$user_id, $plate]);
    $vehicle_id = $pdo->lastInsertId();
} else {
    $vehicle_id = $veh['id'];
}

// get service price
$stmt = $pdo->prepare("SELECT price FROM services WHERE id = ?");
$stmt->execute([$service_id]);
$service = $stmt->fetch();
$estimate = $service ? $service['price'] : 0;

// insert booking
$stmt = $pdo->prepare("INSERT INTO bookings (user_id, vehicle_id, service_id, booking_date, time_slot, location, estimate_price) VALUES (?,?,?,?,?,?,?)");
$stmt->execute([$user_id, $vehicle_id, $service_id, $booking_date, $time_slot, $location, $estimate]);

$booking_id = $pdo->lastInsertId();

// buat transaksi pending (sederhana)
$stmt = $pdo->prepare("INSERT INTO transactions (booking_id, amount, method, status) VALUES (?,?,?,?)");
$stmt->execute([$booking_id, $estimate, 'pending', 'pending']);

header("Location: status.php?plate=" . urlencode($plate));
exit;
