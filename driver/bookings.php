<?php
session_start();
require_once __DIR__ . '/../includes/db.php';
if ($_SESSION['role'] !== 'driver') {
    header("Location: /ride-sharing-app/login.php");
    exit;
}

$stmt = $pdo->prepare("
    SELECT rb.*, r.pickup_location, r.dropoff_location, r.date, r.time, u.name AS rider_name, u.id AS rider_id 
    FROM ride_bookings rb 
    JOIN rides r ON rb.ride_id = r.id 
    JOIN users u ON rb.rider_id = u.id 
    WHERE r.driver_id = ?
");
$stmt->execute([$_SESSION['user_id']]);
$bookings = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<?php include __DIR__ . '/../includes/header.php'; ?>
<div class="row">
    <nav id="sidebar" class="col-md-3 col-lg-2 d-md-block sidebar">
        <div class="position-sticky pt-3">
            <h4 class="text-white text-center fw-bold">Driver Panel</h4>
            <ul class="nav flex-column">
                <li class="nav-item"><a class="nav-link" href="/ride-sharing-app/driver/dashboard.php"><i class="bi bi-house-fill me-2"></i> Dashboard</a></li>
                <li class="nav-item"><a class="nav-link" href="/ride-sharing-app/driver/post_ride.php"><i class="bi bi-plus-circle-fill me-2"></i> Post Ride</a></li>
                <li class="nav-item"><a class="nav-link" href="/ride-sharing-app/driver/my_rides.php"><i class="bi bi-car-front-fill me-2"></i> My Rides</a></li>
                <li class="nav-item"><a class="nav-link active" href="/ride-sharing-app/driver/bookings.php"><i class="bi bi-calendar-check-fill me-2"></i> Bookings</a></li>
                <li class="nav-item"><a class="nav-link" href="/ride-sharing-app/messages.php"><i class="bi bi-chat-fill me-2"></i> Messages</a></li>
                <li class="nav-item"><a class="nav-link" href="/ride-sharing-app/edit_profile.php"><i class="bi bi-person-fill me-2"></i> Profile</a></li>
            </ul>
        </div>
    </nav>
    <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
        <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
            <h1 class="h2 fw-bold text-primary">Bookings</h1>
        </div>
        <div class="card">
            <div class="card-body">
                <h5 class="card-title fw-semibold">Ride Bookings</h5>
                <?php if (empty($bookings)): ?>
                    <div class="text-center py-4">
                        <p class="text-muted">No bookings yet.</p>
                    </div>
                <?php else: ?>
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Rider</th>
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
                                    <td><?php echo htmlspecialchars($booking['rider_name']); ?></td>
                                    <td><?php echo htmlspecialchars($booking['pickup_location']); ?></td>
                                    <td><?php echo htmlspecialchars($booking['dropoff_location']); ?></td>
                                    <td><?php echo $booking['date']; ?></td>
                                    <td><?php echo $booking['time']; ?></td>
                                    <td><?php echo $booking['seats_booked']; ?></td>
                                    <td><?php echo $booking['total_fare']; ?></td>
                                    <td><?php echo ucfirst($booking['payment_method']); ?></td>
                                    <td><span class="badge <?php echo $booking['status'] === 'approved' ? 'bg-success' : ($booking['status'] === 'pending' ? 'bg-warning' : 'bg-danger'); ?>"><?php echo ucfirst($booking['status']); ?></span></td>
                                    <td>
                                        <?php if ($booking['status'] === 'pending'): ?>
                                            <a href="/ride-sharing-app/driver/approve_booking.php?booking_id=<?php echo $booking['id']; ?>" class="btn btn-success btn-sm">Approve</a>
                                            <a href="/ride-sharing-app/driver/reject_booking.php?booking_id=<?php echo $booking['id']; ?>" class="btn btn-danger btn-sm">Reject</a>
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
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
<?php include __DIR__ . '/../includes/footer.php'; ?>