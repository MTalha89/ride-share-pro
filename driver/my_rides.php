<?php
session_start();
require_once __DIR__ . '/../includes/db.php';
if ($_SESSION['role'] !== 'driver') {
    header("Location: /ride-sharing-app/login.php");
    exit;
}

$stmt = $pdo->prepare("SELECT * FROM rides WHERE driver_id = ?");
$stmt->execute([$_SESSION['user_id']]);
$rides = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<?php include __DIR__ . '/../includes/header.php'; ?>
<div class="row">
    <nav id="sidebar" class="col-md-3 col-lg-2 d-md-block sidebar">
        <div class="position-sticky pt-3">
            <h4 class="text-white text-center fw-bold">Driver Panel</h4>
            <ul class="nav flex-column">
                <li class="nav-item"><a class="nav-link" href="/ride-sharing-app/driver/dashboard.php"><i class="bi bi-house-fill me-2"></i> Dashboard</a></li>
                <li class="nav-item"><a class="nav-link" href="/ride-sharing-app/driver/post_ride.php"><i class="bi bi-plus-circle-fill me-2"></i> Post Ride</a></li>
                <li class="nav-item"><a class="nav-link active" href="/ride-sharing-app/driver/my_rides.php"><i class="bi bi-car-front-fill me-2"></i> My Rides</a></li>
                <li class="nav-item"><a class="nav-link" href="/ride-sharing-app/driver/bookings.php"><i class="bi bi-calendar-check-fill me-2"></i> Bookings</a></li>
                <li class="nav-item"><a class="nav-link" href="/ride-sharing-app/messages.php"><i class="bi bi-chat-fill me-2"></i> Messages</a></li>
                <li class="nav-item"><a class="nav-link" href="/ride-sharing-app/edit_profile.php"><i class="bi bi-person-fill me-2"></i> Profile</a></li>
            </ul>
        </div>
    </nav>
    <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
        <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
            <h1 class="h2 fw-bold text-primary">My Rides</h1>
        </div>
        <div class="card">
            <div class="card-body">
                <h5 class="card-title fw-semibold">Your Posted Rides</h5>
                <?php if (empty($rides)): ?>
                    <div class="text-center py-4">
                        <p class="text-muted">No rides posted yet.</p>
                        <a href="/ride-sharing-app/driver/post_ride.php" class="btn btn-primary">Post a Ride</a>
                    </div>
                <?php else: ?>
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Pickup</th>
                                <th>Drop-off</th>
                                <th>Date</th>
                                <th>Time</th>
                                <th>Seats</th>
                                <th>Price</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($rides as $ride): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($ride['pickup_location']); ?></td>
                                    <td><?php echo htmlspecialchars($ride['dropoff_location']); ?></td>
                                    <td><?php echo $ride['date']; ?></td>
                                    <td><?php echo $ride['time']; ?></td>
                                    <td><?php echo $ride['seats_available']; ?></td>
                                    <td><?php echo $ride['price']; ?></td>
                                    <td><span class="badge <?php echo $ride['status'] === 'available' ? 'bg-success' : ($ride['status'] === 'fully booked' ? 'bg-warning' : 'bg-danger'); ?>"><?php echo ucfirst($ride['status']); ?></span></td>
                                    <td>
                                        <?php
                                        $stmt = $pdo->prepare("SELECT COUNT(*) FROM ride_bookings WHERE ride_id = ? AND status != 'cancelled'");
                                        $stmt->execute([$ride['id']]);
                                        $has_bookings = $stmt->fetchColumn();
                                        if ($has_bookings == 0 && $ride['status'] === 'available'): ?>
                                            <a href="/ride-sharing-app/driver/edit_ride.php?ride_id=<?php echo $ride['id']; ?>" class="btn btn-warning btn-sm">Edit</a>
                                        <?php endif; ?>
                                        <?php if ($ride['status'] !== 'completed' && $ride['status'] !== 'cancelled'): ?>
                                            <a href="/ride-sharing-app/driver/cancel_ride.php?ride_id=<?php echo $ride['id']; ?>" class="btn btn-danger btn-sm">Cancel</a>
                                        <?php endif; ?>
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