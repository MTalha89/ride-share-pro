<?php
session_start();
require_once __DIR__ . '/../includes/db.php';
if ($_SESSION['role'] !== 'driver') {
    header("Location: /ride-sharing-app/login.php");
    exit;
}

$settings = $pdo->query("SELECT setting_key, setting_value FROM settings")->fetchAll(PDO::FETCH_KEY_PAIR);
$pricing_model = $settings['pricing_model'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $pickup = $_POST['pickup'];
    $dropoff = $_POST['dropoff'];
    $date = $_POST['date'];
    $time = $_POST['time'];
    $seats = $_POST['seats'];
    $pricing_model = $_POST['pricing_model'];
    $price = $_POST['price'];
    $distance = $_POST['distance'];

    $stmt = $pdo->prepare("INSERT INTO rides (driver_id, pickup_location, dropoff_location, date, time, seats_available, pricing_model, price, distance) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->execute([$_SESSION['user_id'], $pickup, $dropoff, $date, $time, $seats, $pricing_model, $price, $distance]);
    header("Location: /ride-sharing-app/driver/my_rides.php");
    exit;
}
?>

<?php include __DIR__ . '/../includes/header.php'; ?>
<div class="row">
    <nav id="sidebar" class="col-md-3 col-lg-2 d-md-block sidebar">
        <div class="position-sticky pt-3">
            <h4 class="text-white text-center fw-bold">Driver Panel</h4>
            <ul class="nav flex-column">
                <li class="nav-item"><a class="nav-link" href="/ride-sharing-app/driver/dashboard.php"><i class="bi bi-house-fill me-2"></i> Dashboard</a></li>
                <li class="nav-item"><a class="nav-link active" href="/ride-sharing-app/driver/post_ride.php"><i class="bi bi-plus-circle-fill me-2"></i> Post Ride</a></li>
                <li class="nav-item"><a class="nav-link" href="/ride-sharing-app/driver/my_rides.php"><i class="bi bi-car-front-fill me-2"></i> My Rides</a></li>
                <li class="nav-item"><a class="nav-link" href="/ride-sharing-app/driver/bookings.php"><i class="bi bi-calendar-check-fill me-2"></i> Bookings</a></li>
                <li class="nav-item"><a class="nav-link" href="/ride-sharing-app/messages.php"><i class="bi bi-chat-fill me-2"></i> Messages</a></li>
                <li class="nav-item"><a class="nav-link" href="/ride-sharing-app/edit_profile.php"><i class="bi bi-person-fill me-2"></i> Profile</a></li>
            </ul>
        </div>
    </nav>
    <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
        <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
            <h1 class="h2 fw-bold text-primary">Post a Ride</h1>
        </div>
        <div class="card">
            <div class="card-body">
                <h5 class="card-title fw-semibold">Create a New Ride</h5>
                <form method="POST">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold">Pickup Location</label>
                            <input type="text" id="pickup" name="pickup" class="form-control" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold">Drop-off Location</label>
                            <input type="text" id="dropoff" name="dropoff" class="form-control" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold">Date</label>
                            <input type="date" name="date" class="form-control" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold">Time</label>
                            <input type="time" name="time" class="form-control" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold">Seats Available</label>
                            <input type="number" name="seats" class="form-control" min="1" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold">Pricing Model</label>
                            <select name="pricing_model" class="form-select" required>
                                <?php if ($pricing_model === 'fixed' || $pricing_model === 'both'): ?>
                                    <option value="fixed">Fixed</option>
                                <?php endif; ?>
                                <?php if ($pricing_model === 'per_km' || $pricing_model === 'both'): ?>
                                    <option value="per_km">Per Kilometer</option>
                                <?php endif; ?>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold">Price (PKR)</label>
                            <input type="number" step="0.01" name="price" class="form-control" required>
                        </div>
                        <input type="hidden" id="distance" name="distance">
                    </div>
                    <div class="mb-3">
                        <div id="map" style="height: 400px; border-radius: 10px;"></div>
                    </div>
                    <button type="submit" class="btn btn-primary">Post Ride</button>
                </form>
            </div>
        </div>
    </main>
</div>
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
<script src="https://maps.googleapis.com/maps/api/js?key=YOUR_API_KEY&callback=initMap" async defer></script>
<?php include __DIR__ . '/../includes/footer.php'; ?>