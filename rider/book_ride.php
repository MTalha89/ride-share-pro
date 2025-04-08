<?php
session_start();
require_once __DIR__ . '/../includes/db.php';
if ($_SESSION['role'] !== 'rider') {
    header("Location: /ride-sharing-app/login.php");
    exit;
}

$ride_id = $_GET['ride_id'];
$stmt = $pdo->prepare("SELECT r.*, vt.type_name FROM rides r JOIN vehicle_types vt ON r.vehicle_type_id = vt.id WHERE r.id = ? AND r.status = 'available' AND r.seats_available > 0");
$stmt->execute([$ride_id]);
$ride = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$ride) {
    die("Ride not available.");
}

$settings = $pdo->query("SELECT setting_value FROM settings WHERE setting_key = 'payment_methods'")->fetchColumn();
$payment_methods = explode(',', $settings);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $seats = (int)$_POST['seats'];
    $payment_method = $_POST['payment_method'];

    if ($seats > $ride['seats_available']) {
        $error = "Not enough seats available.";
    } else {
        $total_fare = $ride['pricing_model'] === 'fixed' ? $ride['price'] * $seats : $ride['price'] * $ride['distance'] * $seats;
        $stmt = $pdo->prepare("INSERT INTO ride_bookings (ride_id, rider_id, seats_booked, total_fare, payment_method) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([$ride_id, $_SESSION['user_id'], $seats, $total_fare, $payment_method]);
        header("Location: /ride-sharing-app/rider/my_bookings.php");
        exit;
    }
}
$page_title = "Book Ride";
?>

<?php include __DIR__ . '/../includes/header.php'; ?>
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <?php if (isset($error)) echo "<div class='alert alert-danger'>$error</div>"; ?>
                <form method="POST">
                    <div class="row g-3">
                        <div class="col-md-6"><label class="form-label fw-bold">Pickup Location</label><input type="text" class="form-control" value="<?php echo htmlspecialchars($ride['pickup_location']); ?>" readonly></div>
                        <div class="col-md-6"><label class="form-label fw-bold">Drop-off Location</label><input type="text" class="form-control" value="<?php echo htmlspecialchars($ride['dropoff_location']); ?>" readonly></div>
                        <div class="col-md-6"><label class="form-label fw-bold">Date</label><input type="date" class="form-control" value="<?php echo $ride['date']; ?>" readonly></div>
                        <div class="col-md-6"><label class="form-label fw-bold">Time</label><input type="time" class="form-control" value="<?php echo $ride['time']; ?>" readonly></div>
                        <div class="col-md-6"><label class="form-label fw-bold">Vehicle Type</label><input type="text" class="form-control" value="<?php echo $ride['type_name']; ?>" readonly></div>
                        <div class="col-md-6"><label class="form-label fw-bold">Price <?php echo $ride['pricing_model'] === 'fixed' ? '(Fixed)' : '(Per KM)'; ?></label><input type="text" class="form-control" value="<?php echo $ride['price']; ?>" readonly></div>
                        <div class="col-md-6"><label class="form-label fw-bold">Seats Available</label><input type="text" class="form-control" value="<?php echo $ride['seats_available']; ?>" readonly></div>
                        <div class="col-md-6"><label class="form-label fw-bold">Seats to Book</label><input type="number" name="seats" class="form-control" min="1" max="<?php echo $ride['seats_available']; ?>" required></div>
                        <div class="col-md-6"><label class="form-label fw-bold">Payment Method</label><select name="payment_method" class="form-select" required><?php foreach ($payment_methods as $method): ?><option value="<?php echo $method; ?>"><?php echo ucfirst($method); ?></option><?php endforeach; ?></select></div>
                    </div>
                    <button type="submit" class="btn btn-primary mt-4">Book Now</button>
                </form>
            </div>
        </div>
    </div>
</div>
<?php include __DIR__ . '/../includes/footer.php'; ?>