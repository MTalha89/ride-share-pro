<?php
session_start();
require_once __DIR__ . '/../includes/db.php';
if ($_SESSION['role'] !== 'admin') {
    header("Location: /ride-sharing-app/login.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $pricing_model = $_POST['pricing_model'];
    $payment_methods = implode(',', $_POST['payment_methods'] ?? []);
    $pdo->prepare("UPDATE settings SET setting_value = ? WHERE setting_key = 'pricing_model'")->execute([$pricing_model]);
    $pdo->prepare("UPDATE settings SET setting_value = ? WHERE setting_key = 'payment_methods'")->execute([$payment_methods]);
    $success = "Settings updated successfully.";
}

$settings = $pdo->query("SELECT setting_key, setting_value FROM settings")->fetchAll(PDO::FETCH_KEY_PAIR);
$payment_methods = explode(',', $settings['payment_methods']);
?>

<?php include __DIR__ . '/../includes/header.php'; ?>
<div class="row">
    <nav id="sidebar" class="col-md-3 col-lg-2 d-md-block sidebar">
        <div class="position-sticky pt-3">
            <h4 class="text-white text-center fw-bold">Admin Panel</h4>
            <ul class="nav flex-column">
                <li class="nav-item"><a class="nav-link" href="/ride-sharing-app/admin/dashboard.php"><i class="bi bi-house-fill me-2"></i> Dashboard</a></li>
                <li class="nav-item"><a class="nav-link" href="/ride-sharing-app/admin/manage_users.php"><i class="bi bi-people-fill me-2"></i> Users</a></li>
                <li class="nav-item"><a class="nav-link" href="/ride-sharing-app/admin/manage_rides.php"><i class="bi bi-car-front-fill me-2"></i> Rides</a></li>
                <li class="nav-item"><a class="nav-link" href="/ride-sharing-app/admin/manage_bookings.php"><i class="bi bi-calendar-check-fill me-2"></i> Bookings</a></li>
                <li class="nav-item"><a class="nav-link active" href="/ride-sharing-app/admin/settings.php"><i class="bi bi-gear-fill me-2"></i> Settings</a></li>
            </ul>
        </div>
    </nav>
    <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
        <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
            <h1 class="h2 fw-bold text-primary">Platform Settings</h1>
        </div>
        <div class="card">
            <div class="card-body">
                <h5 class="card-title fw-semibold">Configure Platform</h5>
                <?php if (isset($success)) echo "<div class='alert alert-success'>$success</div>"; ?>
                <form method="POST">
                    <div class="mb-4">
                        <label class="form-label fw-semibold">Pricing Model</label>
                        <select name="pricing_model" class="form-select">
                            <option value="fixed" <?php echo $settings['pricing_model'] === 'fixed' ? 'selected' : ''; ?>>Fixed</option>
                            <option value="per_km" <?php echo $settings['pricing_model'] === 'per_km' ? 'selected' : ''; ?>>Per Kilometer</option>
                            <option value="both" <?php echo $settings['pricing_model'] === 'both' ? 'selected' : ''; ?>>Both</option>
                        </select>
                    </div>
                    <div class="mb-4">
                        <label class="form-label fw-semibold">Payment Methods</label>
                        <div>
                            <?php foreach (['cash', 'jazzcash', 'easypaisa', 'coupon', 'card'] as $method): ?>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="checkbox" name="payment_methods[]" value="<?php echo $method; ?>" <?php echo in_array($method, $payment_methods) ? 'checked' : ''; ?>>
                                    <label class="form-check-label"><?php echo ucfirst($method); ?></label>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary">Save Settings</button>
                </form>
            </div>
        </div>
    </main>
</div>
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
<?php include __DIR__ . '/../includes/footer.php'; ?>