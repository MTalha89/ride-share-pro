<?php
session_start();
require_once __DIR__ . '/../includes/db.php';
if ($_SESSION['role'] !== 'rider') {
    header("Location: /ride-sharing-app/login.php");
    exit;
}

$total_bookings = $pdo->query("SELECT COUNT(*) FROM ride_bookings WHERE rider_id = {$_SESSION['user_id']}")->fetchColumn();
?>

<?php include __DIR__ . '/../includes/header.php'; ?>
<div class="row">
    <nav id="sidebar" class="col-md-3 col-lg-2 d-md-block sidebar">
        <div class="position-sticky pt-3">
            <h4 class="text-white text-center fw-bold">Rider Panel</h4>
            <ul class="nav flex-column">
                <li class="nav-item"><a class="nav-link active" href="/ride-sharing-app/rider/dashboard.php"><i class="bi bi-house-fill me-2"></i> Dashboard</a></li>
                <li class="nav-item"><a class="nav-link" href="/ride-sharing-app/rider/search_rides.php"><i class="bi bi-search me-2"></i> Search Rides</a></li>
                <li class="nav-item"><a class="nav-link" href="/ride-sharing-app/rider/my_bookings.php"><i class="bi bi-calendar-check-fill me-2"></i> My Bookings</a></li>
                <li class="nav-item"><a class="nav-link" href="/ride-sharing-app/messages.php"><i class="bi bi-chat-fill me-2"></i> Messages</a></li>
                <li class="nav-item"><a class="nav-link" href="/ride-sharing-app/edit_profile.php"><i class="bi bi-person-fill me-2"></i> Profile</a></li>
            </ul>
        </div>
    </nav>
    <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
        <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
            <h1 class="h2 fw-bold text-primary">Rider Dashboard</h1>
        </div>
        <div class="row g-4">
            <div class="col-md-6">
                <div class="card text-center bg-info text-white">
                    <div class="card-body">
                        <h5 class="card-title">Total Bookings</h5>
                        <p class="card-text display-6 fw-bold"><?php echo $total_bookings; ?></p>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card text-center">
                    <div class="card-body">
                        <h5 class="card-title fw-semibold">Find a Ride</h5>
                        <p class="card-text">Explore available rides now!</p>
                        <a href="/ride-sharing-app/rider/search_rides.php" class="btn btn-primary">Search Rides</a>
                    </div>
                </div>
            </div>
        </div>
    </main>
</div>
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
<?php include __DIR__ . '/../includes/footer.php'; ?>