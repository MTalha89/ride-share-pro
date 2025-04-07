<?php
session_start();
require_once __DIR__ . '/../includes/db.php';
if ($_SESSION['role'] !== 'admin') {
    header("Location: /ride-sharing-app/login.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $type_name = $_POST['type_name'];
    $min_price_fixed = $_POST['min_price_fixed'];
    $max_price_fixed = $_POST['max_price_fixed'];
    $min_price_per_km = $_POST['min_price_per_km'];
    $max_price_per_km = $_POST['max_price_per_km'];
    $commission_type = $_POST['commission_type'];
    $commission_value = $_POST['commission_value'];
    $commission_enabled = isset($_POST['commission_enabled']) ? 1 : 0;

    $stmt = $pdo->prepare("INSERT INTO vehicle_types (type_name, min_price_fixed, max_price_fixed, min_price_per_km, max_price_per_km, commission_type, commission_value, commission_enabled) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->execute([$type_name, $min_price_fixed, $max_price_fixed, $min_price_per_km, $max_price_per_km, $commission_type, $commission_value, $commission_enabled]);
    $success = "Vehicle type added.";
}

$vehicle_types = $pdo->query("SELECT * FROM vehicle_types")->fetchAll(PDO::FETCH_ASSOC);
?>

<?php include __DIR__ . '/../includes/header.php'; ?>
<div class="container-fluid">
    <div class="row">
        <nav id="sidebar" class="col-md-3 col-lg-2 d-md-block sidebar">
            <div class="position-sticky pt-3">
                <h4 class="text-center">Admin Panel</h4>
                <ul class="nav flex-column">
                    <li class="nav-item"><a class="nav-link" href="/ride-sharing-app/admin/dashboard.php"><i class="bi bi-house-fill me-2"></i>Dashboard</a></li>
                    <li class="nav-item"><a class="nav-link" href="/ride-sharing-app/admin/manage_users.php"><i class="bi bi-people-fill me-2"></i>Users</a></li>
                    <li class="nav-item"><a class="nav-link" href="/ride-sharing-app/admin/manage_rides.php"><i class="bi bi-car-front-fill me-2"></i>Rides</a></li>
                    <li class="nav-item"><a class="nav-link" href="/ride-sharing-app/admin/manage_bookings.php"><i class="bi bi-calendar-check-fill me-2"></i>Bookings</a></li>
                    <li class="nav-item"><a class="nav-link active" href="/ride-sharing-app/admin/vehicle_types.php"><i class="bi bi-truck me-2"></i>Vehicle Types</a></li>
                    <li class="nav-item"><a class="nav-link" href="/ride-sharing-app/admin/driver_approvals.php"><i class="bi bi-check-circle-fill me-2"></i>Driver Approvals</a></li>
                    <li class="nav-item"><a class="nav-link" href="/ride-sharing-app/admin/document_settings.php"><i class="bi bi-file-earmark-text me-2"></i>Document Settings</a></li>
                    <li class="nav-item"><a class="nav-link" href="/ride-sharing-app/admin/settings.php"><i class="bi bi-gear-fill me-2"></i>Settings</a></li>
                    <li class="nav-item"><a class="nav-link" href="/ride-sharing-app/logout.php"><i class="bi bi-box-arrow-right me-2"></i>Logout</a></li>
                </ul>
            </div>
        </nav>
        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
            <div class="d-flex justify-content-between align-items-center pt-3 pb-2 mb-4 border-bottom">
                <h1 class="h2">Vehicle Types</h1>
            </div>
            <div class="card mb-4">
                <div class="card-body">
                    <?php if (isset($success)) echo "<div class='alert alert-success'>$success</div>"; ?>
                    <form method="POST">
                        <div class="row g-3">
                            <div class="col-md-6"><label class="form-label">Type Name</label><input type="text" name="type_name" class="form-control" required></div>
                            <div class="col-md-3"><label class="form-label">Min Price (Fixed)</label><input type="number" step="0.01" name="min_price_fixed" class="form-control" required></div>
                            <div class="col-md-3"><label class="form-label">Max Price (Fixed)</label><input type="number" step="0.01" name="max_price_fixed" class="form-control" required></div>
                            <div class="col-md-3"><label class="form-label">Min Price (Per KM)</label><input type="number" step="0.01" name="min_price_per_km" class="form-control" required></div>
                            <div class="col-md-3"><label class="form-label">Max Price (Per KM)</label><input type="number" step="0.01" name="max_price_per_km" class="form-control" required></div>
                            <div class="col-md-3"><label class="form-label">Commission Type</label><select name="commission_type" class="form-select"><option value="percentage">Percentage</option><option value="fixed">Fixed</option></select></div>
                            <div class="col-md-3"><label class="form-label">Commission Value</label><input type="number" step="0.01" name="commission_value" class="form-control" required></div>
                            <div class="col-md-12"><div class="form-check"><input type="checkbox" name="commission_enabled" class="form-check-input" value="1"><label class="form-check-label">Enable Commission</label></div></div>
                        </div>
                        <button type="submit" class="btn btn-primary mt-3">Add Vehicle Type</button>
                    </form>
                </div>
            </div>
            <div class="card">
                <div class="card-body">
                    <table class="table">
                        <thead>
                            <tr><th>ID</th><th>Type Name</th><th>Min Fixed</th><th>Max Fixed</th><th>Min Per KM</th><th>Max Per KM</th><th>Commission</th><th>Enabled</th></tr>
                        </thead>
                        <tbody>
                            <?php foreach ($vehicle_types as $type): ?>
                                <tr>
                                    <td><?php echo $type['id']; ?></td>
                                    <td><?php echo $type['type_name']; ?></td>
                                    <td><?php echo $type['min_price_fixed']; ?></td>
                                    <td><?php echo $type['max_price_fixed']; ?></td>
                                    <td><?php echo $type['min_price_per_km']; ?></td>
                                    <td><?php echo $type['max_price_per_km']; ?></td>
                                    <td><?php echo $type['commission_type'] === 'percentage' ? $type['commission_value'] . '%' : $type['commission_value']; ?></td>
                                    <td><?php echo $type['commission_enabled'] ? 'Yes' : 'No'; ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </main>
    </div>
</div>
<?php include __DIR__ . '/../includes/footer.php'; ?>