<?php
session_start();
require_once __DIR__ . '/../includes/db.php';
if ($_SESSION['role'] !== 'driver') {
    header("Location: /ride-sharing-app/login.php");
    exit;
}

$ride_id = $_GET['ride_id'];
$stmt = $pdo->prepare("SELECT * FROM rides WHERE id = ? AND driver_id = ?");
$stmt->execute([$ride_id, $_SESSION['user_id']]);
$ride = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$ride) {
    die("Ride not found or you don’t have permission.");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $pickup = $_POST['pickup'];
    $dropoff = $_POST['dropoff'];
    $date = $_POST['date'];
    $time = $_POST['time'];
    $seats = $_POST['seats'];
    $price = $_POST['price'];

    $stmt = $pdo->prepare("UPDATE rides SET pickup_location = ?, dropoff_location = ?, date = ?, time = ?, seats_available = ?, price = ? WHERE id = ? AND driver_id = ?");
    $stmt->execute([$pickup, $dropoff, $date, $time, $seats, $price, $ride_id, $_SESSION['user_id']]);
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
            <h1 class="h2 fw-bold text-primary">Edit Ride</h1>
        </div>
        <div class="card">
            <div class="card-body">
                <h5 class="card-title fw-semibold">Update Ride Details</h5>
                <form method="POST">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold">Pickup Location</label>
                            <input type="text" name="pickup" class="form-control" value="<?php echo htmlspecialchars($ride['pickup_location']); ?>" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold">Drop-off Location</label>
                            <input type="text" name="dropoff" class="form-control" value="<?php echo htmlspecialchars($ride['dropoff_location']); ?>" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold">Date</label>
                            <input type="date" name="date" class="form-control" value="<?php echo $ride['date']; ?>" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold">Time</label>
                            <input type="time" name="time" class="form-control" value="<?php echo $ride['time']; ?>" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold">Seats Available</label>
                            <input type="number" name="seats" class="form-control" value="<?php echo $ride['seats_available']; ?>" min="1" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold">Price (PKR)</label>
                            <input type="number" step="0.01" name="price" class="form-control" value="<?php echo $ride['price']; ?>" required>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary">Save Changes</button>
                </form>
            </div>
        </div>
    </main>
</div>
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
<?php include __DIR__ . '/../includes/footer.php'; ?>