<?php
session_start();
require_once __DIR__ . '/../includes/db.php';
if ($_SESSION['role'] !== 'driver') {
    header("Location: /ride-sharing-app/login.php");
    exit;
}

$booking_id = $_GET['booking_id'];
$stmt = $pdo->prepare("SELECT rb.ride_id FROM ride_bookings rb JOIN rides r ON rb.ride_id = r.id WHERE rb.id = ? AND r.driver_id = ?");
$stmt->execute([$booking_id, $_SESSION['user_id']]);
$booking = $stmt->fetch(PDO::FETCH_ASSOC);

if ($booking) {
    $pdo->prepare("UPDATE ride_bookings SET status = 'rejected' WHERE id = ?")->execute([$booking_id]);
}

header("Location: /ride-sharing-app/driver/bookings.php");
exit;
?>