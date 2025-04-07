<?php
session_start();
require_once __DIR__ . '/../includes/db.php';
if ($_SESSION['role'] !== 'rider') {
    header("Location: /ride-sharing-app/login.php");
    exit;
}

$stmt = $pdo->prepare("
    SELECT rb.*, r.pickup_location, r.dropoff_location, r.date, r.time, u.name AS driver_name 
    FROM ride_bookings rb 
    JOIN rides r ON rb.ride_id = r.id 
    JOIN users u ON r.driver_id = u.id 
    WHERE rb.rider_id = ?
");
$stmt->execute([$_SESSION['user_id']]);
$bookings = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<?php include __DIR__ . '/../includes/header.php'; ?>
<div class="container-fluid">
    <div class="row">
        <nav id="sidebar" class="col-md-3 col-lg-2 d-md-block sidebar">
            <div class="position-sticky pt-3">
                <h4 class="text-center">Rider Panel</h4>
                <ul class="nav flex-column">
                    <li class="nav-item"><a class="nav-link" href="/ride-sharing-app/rider/dashboard.php"><i class="bi bi-house-fill me-2"></i>Dashboard</a></li>
                    <li class="nav-item"><a class="nav-link" href="/ride-sharing-app/rider/search_rides.php"><i class="bi bi-search me-2"></i>Search Rides</a></li>
                    <li class="nav-item"><a class="nav-link active" href="/ride-sharing-app/rider/my_bookings.php"><i class="bi bi-calendar-check-fill me-2"></i>My Bookings</a></li>
                    <li class="nav-item"><a class="nav-link" href="/ride-sharing-app/messages.php"><i class="bi bi-chat-fill me-2"></i>Messages</a></li>
                    <li class="nav-item"><a class="nav-link" href="/ride-sharing-app/edit_profile.php"><i class="bi bi-person-fill me-2"></i>Profile</a></li>
                    <li class="nav-item"><a class="nav-link" href="/ride-sharing-app/logout.php"><i class="bi bi-box-arrow-right me-2"></i>Logout</a></li>
                </ul>
            </div>
        </nav>
        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
            <div class="d-flex justify-content-between align-items-center pt-3 pb-2 mb-4 border-bottom">
                <h1 class="h2">My Bookings</h1>
            </div>
            <div class="card">
                <div class="card-body">
                    <?php if (empty($bookings)): ?>
                        <div class="text-center">
                            <h5>No Bookings Yet</h5>
                            <p>Book a ride to get started!</p>
                            <a href="/ride-sharing-app/rider/search_rides.php" class="btn btn-primary">Search Rides</a>
                        </div>
                    <?php else: ?>
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Driver</th>
                                    <th>Pickup</th>
                                    <th>Drop-off</th>
                                    <th>Date</th>
                                    <th>Time</th>
                                    <th>Seats</th>
                                    <th>Fare</th>
                                    <th>Payment</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($bookings as $booking): ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($booking['driver_name']); ?></td>
                                        <td><?php echo htmlspecialchars($booking['pickup_location']); ?></td>
                                        <td><?php echo htmlspecialchars($booking['dropoff_location']); ?></td>
                                        <td><?php echo $booking['date']; ?></td>
                                        <td><?php echo $booking['time']; ?></td>
                                        <td><?php echo $booking['seats_booked']; ?></td>
                                        <td><?php echo $booking['total_fare']; ?></td>
                                        <td><?php echo $booking['payment_method']; ?></td>
                                        <td><?php echo $booking['status']; ?></td>
                                        <td>
                                            <?php if ($booking['status'] === 'pending'): ?>
                                                <a href="/ride-sharing-app/rider/cancel_booking.php?booking_id=<?php echo $booking['id']; ?>" class="btn btn-danger btn-sm">Cancel</a>
                                            <?php endif; ?>
                                            <a href="/ride-sharing-app/messages.php?receiver_id=<?php echo $booking['rider_id']; ?>" class="btn btn-info btn-sm">Message</a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    <?php endif; ?>
                </div>
            </div>
        </main>
    </div>
</div>
<?php include __DIR__ . '/../includes/footer.php'; ?>