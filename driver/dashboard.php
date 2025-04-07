<?php
session_start();
require_once __DIR__ . '/../includes/db.php';
if ($_SESSION['role'] !== 'driver') {
    header("Location: /ride-sharing-app/login.php");
    exit;
}

$total_rides = $pdo->query("SELECT COUNT(*) FROM rides WHERE driver_id = {$_SESSION['user_id']}")->fetchColumn();
$total_bookings = $pdo->query("SELECT COUNT(*) FROM ride_bookings rb JOIN rides r ON rb.ride_id = r.id WHERE r.driver_id = {$_SESSION['user_id']}")->fetchColumn();
?>

<?php include __DIR__ . '/../includes/header.php'; ?>
<div class="row">
    <nav id="sidebar" class="col-md-3 col-lg-2 d-md-block sidebar">
        <div class="position-sticky pt-3">
            <h4 class="text-white text-center fw-bold">Driver Panel</h4>
            <ul class="nav flex-column">
                <li class="nav-item"><a class="nav-link active" href="/ride-sharing-app/driver/dashboard.php"><i class="bi bi-house-fill me-2"></i> Dashboard</a></li>
                <li class="nav-item"><a class="nav-link" href="/ride-sharing-app/driver/post_ride.php"><i class="bi bi-plus-circle-fill me-2"></i> Post Ride</a></li>
                <li class="nav-item"><a class="nav-link" href="/ride-sharing-app/driver/my_rides.php"><i class="bi bi-car-front-fill me-2"></i> My Rides</a></li>
                <li class="nav-item"><a class="nav-link" href="/ride-sharing-app/driver/bookings.php"><i class="bi bi-calendar-check-fill me-2"></i> Bookings</a></li>
                <li class="nav-item"><a class="nav-link" href="/ride-sharing-app/messages.php"><i class="bi bi-chat-fill me-2"></i> Messages</a></li>
                <li class="nav-item"><a class="nav-link" href="/ride-sharing-app/edit_profile.php"><i class="bi bi-person-fill me-2"></i> Profile</a></li>
            </ul>
        </div>
    </nav>
    <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
        <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
            <h1 class="h2 fw-bold text-primary">Driver Dashboard</h1>
        </div>
        <div class="row g-4">
            <div class="col-md-6">
                <div class="card text-center bg-success text-white">
                    <div class="card-body">
                        <h5 class="card-title">Total Rides Posted</h5>
                        <p class="card-text display-6 fw-bold"><?php echo $total_rides; ?></p>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card text-center bg-info text-white">
                    <div class="card-body">
                        <h5 class="card-title">Total Bookings</h5>
                        <p class="card-text display-6 fw-bold"><?php echo $total_bookings; ?></p>
                    </div>
                </div>
            </div>
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body text-center">
                        <h5 class="card-title fw-semibold">Ready to Ride?</h5>
                        <p class="card-text">Post a new ride and start earning!</p>
                        <a href="/ride-sharing-app/driver/post_ride.php" class="btn btn-primary">Post a Ride</a>
                    </div>
                </div>
            </div>
        </div>
    </main>
</div>
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
<?php include __DIR__ . '/../includes/footer.php'; ?>