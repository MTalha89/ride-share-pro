<?php
session_start();
require_once __DIR__ . '/../includes/db.php';
if ($_SESSION['role'] !== 'rider') {
    header("Location: /ride-sharing-app/login.php");
    exit;
}

$booking_id = $_GET['booking_id'];
$stmt = $pdo->prepare("SELECT ride_id, seats_booked FROM ride_bookings WHERE id = ? AND rider_id = ? AND status = 'pending'");
$stmt->execute([$booking_id, $_SESSION['user_id']]);
$booking = $stmt->fetch(PDO::FETCH_ASSOC);

if ($booking) {
    $pdo->prepare("UPDATE ride_bookings SET status = 'cancelled' WHERE id = ?")->execute([$booking_id]);
    $stmt = $pdo->prepare("UPDATE rides SET seats_available = seats_available + ?, status = 'available' WHERE id = ?");
    $stmt->execute([$booking['seats_booked'], $booking['ride_id']]);
}

header("Location: /ride-sharing-app/rider/my_bookings.php");
exit;
?>