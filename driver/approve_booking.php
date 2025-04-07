<?php
session_start();
require_once __DIR__ . '/../includes/db.php';
if ($_SESSION['role'] !== 'driver') {
    header("Location: /ride-sharing-app/login.php");
    exit;
}

$booking_id = $_GET['booking_id'];
$stmt = $pdo->prepare("SELECT rb.ride_id, rb.seats_booked FROM ride_bookings rb JOIN rides r ON rb.ride_id = r.id WHERE rb.id = ? AND r.driver_id = ?");
$stmt->execute([$booking_id, $_SESSION['user_id']]);
$booking = $stmt->fetch(PDO::FETCH_ASSOC);

if ($booking) {
    $pdo->prepare("UPDATE ride_bookings SET status = 'approved' WHERE id = ?")->execute([$booking_id]);
    $stmt = $pdo->prepare("UPDATE rides SET seats_available = seats_available - ?, status = IF(seats_available - ? = 0, 'fully booked', 'available') WHERE id = ?");
    $stmt->execute([$booking['seats_booked'], $booking['seats_booked'], $booking['ride_id']]);
}

header("Location: /ride-sharing-app/driver/bookings.php");
exit;
?>