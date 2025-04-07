<?php
session_start();
require_once __DIR__ . '/../includes/db.php';
if ($_SESSION['role'] !== 'driver' || !$pdo->query("SELECT verified FROM users WHERE id = {$_SESSION['user_id']}")->fetchColumn()) {
    header("Location: /ride-sharing-app/login.php");
    exit;
}

$vehicle_types = $pdo->query("SELECT * FROM vehicle_types")->fetchAll(PDO::FETCH_ASSOC);
$settings = $pdo->query("SELECT setting_key, setting_value FROM settings")->fetchAll(PDO::FETCH_KEY_PAIR);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $pickup = $_POST['pickup'];
    $dropoff = $_POST['dropoff'];
    $date = $_POST['date'];
    $time = $_POST['time'];
    $seats = $_POST['seats'];
    $vehicle_type_id = $_POST['vehicle_type'];
    $pricing_model = $_POST['pricing_model'];
    $price = $_POST['price'];
    $distance = $_POST['distance'];

    $type = array_filter($vehicle_types, fn($t) => $t['id'] == $vehicle_type_id)[array_key_first(array_filter($vehicle_types, fn($t) => $t['id'] == $vehicle_type_id))];
    $min_price = $pricing_model === 'fixed' ? $type['min_price_fixed'] : $type['min_price_per_km'];
    $max_price = $pricing_model === 'fixed' ? $type['max_price_fixed'] : $type['max_price_per_km'];

    if ($price < $min_price || $price > $max_price) {
        $error = "Price must be between $min_price and $max_price for {$type['type_name']} ($pricing_model).";
    } else {
        $stmt = $pdo->prepare("INSERT INTO rides (driver_id, pickup_location, dropoff_location, date, time, seats_available, vehicle_type_id, pricing_model, price, distance) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([$_SESSION['user_id'], $pickup, $dropoff, $date, $time, $seats, $vehicle_type_id, $pricing_model, $price, $distance]);
        header("Location: /ride-sharing-app/driver/my_rides.php");
        exit;
    }
}
?>

<?php include __DIR__ . '/../includes/header.php'; ?>
<div class="container-fluid">
    <div class="row">
        <nav id="sidebar" class="col-md-3 col-lg-2 d-md-block sidebar">
            <div class="position-sticky pt-3">
                <h4 class="text-center">Driver Panel</h4>
                <ul class="nav flex-column">
                    <li class="nav-item"><a class="nav-link" href="/ride-sharing-app/driver/dashboard.php"><i class="bi bi-house-fill me-2"></i>Dashboard</a></li>
                    <li class="nav-item"><a class="nav-link active" href="/ride-sharing-app/driver/post_ride.php"><i class="bi bi-plus-circle-fill me-2"></i>Post Ride</a></li>
                    <li class="nav-item"><a class="nav-link" href="/ride-sharing-app/driver/my_rides.php"><i class="bi bi-car-front-fill me-2"></i>My Rides</a></li>
                    <li class="nav-item"><a class="nav-link" href="/ride-sharing-app/driver/bookings.php"><i class="bi bi-calendar-check-fill me-2"></i>Bookings</a></li>
                    <li class="nav-item"><a class="nav-link" href="/ride-sharing-app/messages.php"><i class="bi bi-chat-fill me-2"></i>Messages</a></li>
                    <li class="nav-item"><a class="nav-link" href="/ride-sharing-app/edit_profile.php"><i class="bi bi-person-fill me-2"></i>Profile</a></li>
                    <li class="nav-item"><a class="nav-link" href="/ride-sharing-app/logout.php"><i class="bi bi-box-arrow-right me-2"></i>Logout</a></li>
                </ul>
            </div>
        </nav>
        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
            <div class="d-flex justify-content-between align-items-center pt-3 pb-2 mb-4 border-bottom">
                <h1 class="h2">Post a Ride</h1>
            </div>
            <div class="card">
                <div class="card-body">
                    <?php if (isset($error)) echo "<div class='alert alert-danger'>$error</div>"; ?>
                    <form method="POST">
                        <div class="row g-3">
                            <div class="col-md-6"><label class="form-label fw-bold">Pickup Location</label><input type="text" id="pickup" name="pickup" class="form-control" required></div>
                            <div class="col-md-6"><label class="form-label fw-bold">Drop-off Location</label><input type="text" id="dropoff" name="dropoff" class="form-control" required></div>
                            <div class="col-md-6"><label class="form-label fw-bold">Date</label><input type="date" name="date" class="form-control" required></div>
                            <div class="col-md-6"><label class="form-label fw-bold">Time</label><input type="time" name="time" class="form-control" required></div>
                            <div class="col-md-6"><label class="form-label fw-bold">Seats Available</label><input type="number" name="seats" class="form-control" required></div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Vehicle Type</label>
                                <select name="vehicle_type" class="form-select" id="vehicleType" onchange="updatePriceRange()" required>
                                    <?php foreach ($vehicle_types as $type): ?>
                                        <option value="<?php echo $type['id']; ?>" data-min-fixed="<?php echo $type['min_price_fixed']; ?>" data-max-fixed="<?php echo $type['max_price_fixed']; ?>" data-min-per-km="<?php echo $type['min_price_per_km']; ?>" data-max-per-km="<?php echo $type['max_price_per_km']; ?>"><?php echo $type['type_name']; ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Pricing Model</label>
                                <select name="pricing_model" class="form-select" id="pricingModel" onchange="updatePriceRange()" required>
                                    <?php if ($settings['pricing_model'] === 'fixed' || $settings['pricing_model'] === 'both'): ?>
                                        <option value="fixed">Fixed</option>
                                    <?php endif; ?>
                                    <?php if ($settings['pricing_model'] === 'per_km' || $settings['pricing_model'] === 'both'): ?>
                                        <option value="per_km">Per Kilometer</option>
                                    <?php endif; ?>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Price</label>
                                <input type="number" step="0.01" name="price" id="priceInput" class="form-control" required>
                                <small id="priceRange" class="form-text text-muted"></small>
                            </div>
                            <input type="hidden" id="distance" name="distance">
                        </div>
                        <div class="mt-4"><div id="map" style="height: 300px; border-radius: 10px;"></div></div>
                        <button type="submit" class="btn btn-primary mt-4">Post Ride</button>
                    </form>
                </div>
            </div>
        </main>
    </div>
</div>
<script src="https://maps.googleapis.com/maps/api/js?key=YOUR_API_KEY&callback=initMap" async defer></script>
<script>
function initMap() {
    const map = new google.maps.Map(document.getElementById('map'), { center: { lat: 31.5204, lng: 74.3587 }, zoom: 12 });
}

function updatePriceRange() {
    const vehicleType = document.getElementById('vehicleType');
    const pricingModel = document.getElementById('pricingModel').value;
    const selectedOption = vehicleType.options[vehicleType.selectedIndex];
    const min = pricingModel === 'fixed' ? selectedOption.dataset.minFixed : selectedOption.dataset.minPerKm;
    const max = pricingModel === 'fixed' ? selectedOption.dataset.maxFixed : selectedOption.dataset.maxPerKm;
    document.getElementById('priceRange').textContent = `Range: ${min} - ${max}`;
    document.getElementById('priceInput').setAttribute('min', min);
    document.getElementById('priceInput').setAttribute('max', max);
}
updatePriceRange();
</script>
<?php include __DIR__ . '/../includes/footer.php'; ?>