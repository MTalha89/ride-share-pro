<?php
session_start();
require_once __DIR__ . '/../includes/db.php';
if ($_SESSION['role'] !== 'driver') {
    header("Location: /ride-sharing-app/login.php");
    exit;
}

$ride_id = $_GET['ride_id'];
$stmt = $pdo->prepare("UPDATE rides SET status = 'cancelled' WHERE id = ? AND driver_id = ?");
$stmt->execute([$ride_id, $_SESSION['user_id']]);

$stmt = $pdo->prepare("UPDATE ride_bookings SET status = 'cancelled' WHERE ride_id = ?");
$stmt->execute([$ride_id]);

header("Location: /ride-sharing-app/driver/my_rides.php");
exit;
?>