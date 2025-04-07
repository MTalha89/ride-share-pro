<?php
session_start();
require_once __DIR__ . '/../includes/db.php';
if ($_SESSION['role'] !== 'rider') {
    header("Location: /ride-sharing-app/login.php");
    exit;
}

$rides = $pdo->query("SELECT r.*, u.name AS driver_name, u.chattiness FROM rides r JOIN users u ON r.driver_id = u.id WHERE r.status = 'available' AND r.seats_available > 0")->fetchAll(PDO::FETCH_ASSOC);
?>

<?php include __DIR__ . '/../includes/header.php'; ?>
<div class="row">
    <nav id="sidebar" class="col-md-3 col-lg-2 d-md-block sidebar">
        <div class="position-sticky pt-3">
            <h4 class="text-white text-center fw-bold">Rider Panel</h4>
            <ul class="nav flex-column">
                <li class="nav-item"><a class="nav-link" href="/ride-sharing-app/rider/dashboard.php"><i class="bi bi-house-fill me-2"></i> Dashboard</a></li>
                <li class="nav-item"><a class="nav-link active" href="/ride-sharing-app/rider/search_rides.php"><i class="bi bi-search me-2"></i> Search Rides</a></li>
                <li class="nav-item"><a class="nav-link" href="/ride-sharing-app/rider/my_bookings.php"><i class="bi bi-calendar-check-fill me-2"></i> My Bookings</a></li>
                <li class="nav-item"><a class="nav-link" href="/ride-sharing-app/messages.php"><i class="bi bi-chat-fill me-2"></i> Messages</a></li>
                <li class="nav-item"><a class="nav-link" href="/ride-sharing-app/edit_profile.php"><i class="bi bi-person-fill me-2"></i> Profile</a></li>
            </ul>
        </div>
    </nav>
    <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
        <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
            <h1 class="h2 fw-bold text-primary">Search Rides</h1>
        </div>
        <div class="card">
            <div class="card-body">
                <h5 class="card-title fw-semibold">Available Rides</h5>
                <?php if (empty($rides)): ?>
                    <div class="text-center py-4">
                        <p class="text-muted">No rides available right now.</p>
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
                                <th>Price</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($rides as $ride): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($ride['driver_name']) . " (" . $ride['chattiness'] . ")"; ?></td>
                                    <td><?php echo htmlspecialchars($ride['pickup_location']); ?></td>
                                    <td><?php echo htmlspecialchars($ride['dropoff_location']); ?></td>
                                    <td><?php echo $ride['date']; ?></td>
                                    <td><?php echo $ride['time']; ?></td>
                                    <td><?php echo $ride['seats_available']; ?></td>
                                    <td><?php echo $ride['pricing_model'] === 'fixed' ? $ride['price'] : $ride['price'] * $ride['distance']; ?></td>
                                    <td>
                                        <a href="/ride-sharing-app/rider/book_ride.php?ride_id=<?php echo $ride['id']; ?>" class="btn btn-primary btn-sm">Book</a>
                                        <a href="/ride-sharing-app/messages.php?receiver_id=<?php echo $ride['driver_id']; ?>" class="btn btn-info btn-sm">Message</a>
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