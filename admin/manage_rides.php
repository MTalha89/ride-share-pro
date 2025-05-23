<?php
session_start();
require_once __DIR__ . '/../includes/db.php';
if ($_SESSION['role'] !== 'admin') {
    header("Location: /ride-sharing-app/login.php");
    exit;
}

$rides = $pdo->query("SELECT r.*, u.name AS driver_name FROM rides r JOIN users u ON r.driver_id = u.id")->fetchAll(PDO::FETCH_ASSOC);
?>

<?php include __DIR__ . '/../includes/header.php'; ?>
<div class="row">
    <nav id="sidebar" class="col-md-3 col-lg-2 d-md-block sidebar">
        <div class="position-sticky pt-3">
            <h4 class="text-white text-center fw-bold">Admin Panel</h4>
            <ul class="nav flex-column">
                <li class="nav-item"><a class="nav-link" href="/ride-sharing-app/admin/dashboard.php"><i class="bi bi-house-fill me-2"></i> Dashboard</a></li>
                <li class="nav-item"><a class="nav-link" href="/ride-sharing-app/admin/manage_users.php"><i class="bi bi-people-fill me-2"></i> Users</a></li>
                <li class="nav-item"><a class="nav-link active" href="/ride-sharing-app/admin/manage_rides.php"><i class="bi bi-car-front-fill me-2"></i> Rides</a></li>
                <li class="nav-item"><a class="nav-link" href="/ride-sharing-app/admin/manage_bookings.php"><i class="bi bi-calendar-check-fill me-2"></i> Bookings</a></li>
                <li class="nav-item"><a class="nav-link" href="/ride-sharing-app/admin/settings.php"><i class="bi bi-gear-fill me-2"></i> Settings</a></li>
            </ul>
        </div>
    </nav>
    <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
        <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
            <h1 class="h2 fw-bold text-primary">Manage Rides</h1>
        </div>
        <div class="card">
            <div class="card-body">
                <h5 class="card-title fw-semibold">Ride List</h5>
                <table class="table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Driver</th>
                            <th>Pickup</th>
                            <th>Drop-off</th>
                            <th>Date</th>
                            <th>Time</th>
                            <th>Seats</th>
                            <th>Price</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($rides as $ride): ?>
                            <tr>
                                <td><?php echo $ride['id']; ?></td>
                                <td><?php echo htmlspecialchars($ride['driver_name']); ?></td>
                                <td><?php echo htmlspecialchars($ride['pickup_location']); ?></td>
                                <td><?php echo htmlspecialchars($ride['dropoff_location']); ?></td>
                                <td><?php echo $ride['date']; ?></td>
                                <td><?php echo $ride['time']; ?></td>
                                <td><?php echo $ride['seats_available']; ?></td>
                                <td><?php echo $ride['price']; ?></td>
                                <td><span class="badge <?php echo $ride['status'] === 'available' ? 'bg-success' : ($ride['status'] === 'fully booked' ? 'bg-warning' : 'bg-danger'); ?>"><?php echo ucfirst($ride['status']); ?></span></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </main>
</div>
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
<?php include __DIR__ . '/../includes/footer.php'; ?>