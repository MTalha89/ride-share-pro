<?php
session_start();
require_once __DIR__ . '/../includes/db.php';
if ($_SESSION['role'] !== 'admin') {
    header("Location: /ride-sharing-app/login.php");
    exit;
}

$bookings = $pdo->query("SELECT rb.*, r.pickup_location, r.dropoff_location, u1.name AS rider_name, u2.name AS driver_name 
                         FROM ride_bookings rb 
                         JOIN rides r ON rb.ride_id = r.id 
                         JOIN users u1 ON rb.rider_id = u1.id 
                         JOIN users u2 ON r.driver_id = u2.id")->fetchAll(PDO::FETCH_ASSOC);
?>

<?php include __DIR__ . '/../includes/header.php'; ?>
<div class="row">
<!-- <nav id="sidebar" class="col-md-3 col-lg-2 d-md-block sidebar">
            <div class="position-sticky pt-3">
                <h4 class="text-center">Admin Panel</h4>
                <ul class="nav flex-column">
                    <li class="nav-item"><a class="nav-link" href="/ride-sharing-app/admin/dashboard.php"><i class="bi bi-house-fill me-2"></i>Dashboard</a></li>
                    <li class="nav-item"><a class="nav-link" href="/ride-sharing-app/admin/manage_users.php"><i class="bi bi-people-fill me-2"></i>Users</a></li>
                    <li class="nav-item"><a class="nav-link" href="/ride-sharing-app/admin/manage_rides.php"><i class="bi bi-car-front-fill me-2"></i>Rides</a></li>
                    <li class="nav-item"><a class="nav-link active" href="/ride-sharing-app/admin/manage_bookings.php"><i class="bi bi-calendar-check-fill me-2"></i>Bookings</a></li>
                    <li class="nav-item"><a class="nav-link" href="/ride-sharing-app/admin/vehicle_types.php"><i class="bi bi-truck me-2"></i>Vehicle Types</a></li>
                    <li class="nav-item"><a class="nav-link" href="/ride-sharing-app/admin/driver_approvals.php"><i class="bi bi-check-circle-fill me-2"></i>Driver Approvals</a></li>
                    <li class="nav-item"><a class="nav-link" href="/ride-sharing-app/admin/document_settings.php"><i class="bi bi-file-earmark-text me-2"></i>Document Settings</a></li>
                    <li class="nav-item"><a class="nav-link" href="/ride-sharing-app/admin/settings.php"><i class="bi bi-gear-fill me-2"></i>Settings</a></li>
                    <li class="nav-item"><a class="nav-link" href="/ride-sharing-app/logout.php"><i class="bi bi-box-arrow-right me-2"></i>Logout</a></li>
                </ul>
            </div>
        </nav> -->
    <main class="col-md-9 ms-sm-auto col-lg-12 px-md-4">
        <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
            <h1 class="h2 fw-bold text-primary">Manage Bookings</h1>
        </div>
        <div class="card">
            <div class="card-body">
                <h5 class="card-title fw-semibold">Booking List</h5>
                <table class="table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Rider</th>
                            <th>Driver</th>
                            <th>Pickup</th>
                            <th>Drop-off</th>
                            <th>Seats</th>
                            <th>Fare</th>
                            <th>Payment</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($bookings as $booking): ?>
                            <tr>
                                <td><?php echo $booking['id']; ?></td>
                                <td><?php echo htmlspecialchars($booking['rider_name']); ?></td>
                                <td><?php echo htmlspecialchars($booking['driver_name']); ?></td>
                                <td><?php echo htmlspecialchars($booking['pickup_location']); ?></td>
                                <td><?php echo htmlspecialchars($booking['dropoff_location']); ?></td>
                                <td><?php echo $booking['seats_booked']; ?></td>
                                <td><?php echo $booking['total_fare']; ?></td>
                                <td><?php echo ucfirst($booking['payment_method']); ?></td>
                                <td><span class="badge <?php echo $booking['status'] === 'approved' ? 'bg-success' : ($booking['status'] === 'pending' ? 'bg-warning' : 'bg-danger'); ?>"><?php echo ucfirst($booking['status']); ?></span></td>
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