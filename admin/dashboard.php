<?php
session_start();
require_once __DIR__ . '/../includes/db.php';
if ($_SESSION['role'] !== 'admin') {
    header("Location: /ride-sharing-app/login.php");
    exit;
}

$total_users = $pdo->query("SELECT COUNT(*) FROM users")->fetchColumn();
$total_rides = $pdo->query("SELECT COUNT(*) FROM rides")->fetchColumn();
$total_bookings = $pdo->query("SELECT COUNT(*) FROM ride_bookings")->fetchColumn();
?>

<?php include __DIR__ . '/../includes/header.php'; ?>
<div class="row">
    <nav id="sidebar" class="col-md-3 col-lg-2 d-md-block sidebar">
        <div class="position-sticky pt-3">
            <h4 class="text-white text-center fw-bold">Admin Panel</h4>
            <ul class="nav flex-column">
                <li class="nav-item"><a class="nav-link active" href="/ride-sharing-app/admin/dashboard.php"><i class="bi bi-house-fill me-2"></i> Dashboard</a></li>
                <li class="nav-item"><a class="nav-link" href="/ride-sharing-app/admin/manage_users.php"><i class="bi bi-people-fill me-2"></i> Users</a></li>
                <li class="nav-item"><a class="nav-link" href="/ride-sharing-app/admin/manage_rides.php"><i class="bi bi-car-front-fill me-2"></i> Rides</a></li>
                <li class="nav-item"><a class="nav-link" href="/ride-sharing-app/admin/manage_bookings.php"><i class="bi bi-calendar-check-fill me-2"></i> Bookings</a></li>
                <li class="nav-item"><a class="nav-link" href="/ride-sharing-app/admin/settings.php"><i class="bi bi-gear-fill me-2"></i> Settings</a></li>
            </ul>
        </div>
    </nav>
    <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
        <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
            <h1 class="h2 fw-bold text-primary">Admin Dashboard</h1>
        </div>
        <div class="row g-4">
            <div class="col-md-4">
                <div class="card text-center bg-primary text-white">
                    <div class="card-body">
                        <h5 class="card-title">Total Users</h5>
                        <p class="card-text display-6 fw-bold"><?php echo $total_users; ?></p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card text-center bg-success text-white">
                    <div class="card-body">
                        <h5 class="card-title">Total Rides</h5>
                        <p class="card-text display-6 fw-bold"><?php echo $total_rides; ?></p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card text-center bg-info text-white">
                    <div class="card-body">
                        <h5 class="card-title">Total Bookings</h5>
                        <p class="card-text display-6 fw-bold"><?php echo $total_bookings; ?></p>
                    </div>
                </div>
            </div>
        </div>
    </main>
</div>
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
<?php include __DIR__ . '/../includes/footer.php'; ?>