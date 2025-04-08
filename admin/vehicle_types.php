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

    $image_path = '';
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $image_path = 'vehicle_types/' . time() . '_' . $_FILES['image']['name'];
        move_uploaded_file($_FILES['image']['tmp_name'], __DIR__ . '/../assets/images/' . $image_path);
    }

    $stmt = $pdo->prepare("INSERT INTO vehicle_types (type_name, min_price_fixed, max_price_fixed, min_price_per_km, max_price_per_km, commission_type, commission_value, commission_enabled, image_path) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->execute([$type_name, $min_price_fixed, $max_price_fixed, $min_price_per_km, $max_price_per_km, $commission_type, $commission_value, $commission_enabled, $image_path]);
    $success = "Vehicle type added.";
}

$vehicle_types = $pdo->query("SELECT * FROM vehicle_types")->fetchAll(PDO::FETCH_ASSOC);
$page_title = "Vehicle Types";
?>

<?php include __DIR__ . '/../includes/header.php'; ?>
<div class="row">
    <div class="col-md-12">
        <div class="card mb-4">
            <div class="card-body">
                <?php if (isset($success)) echo "<div class='alert alert-success'>$success</div>"; ?>
                <form method="POST" enctype="multipart/form-data">
                    <div class="row g-3">
                        <div class="col-md-4"><label class="form-label">Type Name</label><input type="text" name="type_name" class="form-control" required></div>
                        <div class="col-md-2"><label class="form-label">Min Price (Fixed)</label><input type="number" step="0.01" name="min_price_fixed" class="form-control" required></div>
                        <div class="col-md-2"><label class="form-label">Max Price (Fixed)</label><input type="number" step="0.01" name="max_price_fixed" class="form-control" required></div>
                        <div class="col-md-2"><label class="form-label">Min Price (Per KM)</label><input type="number" step="0.01" name="min_price_per_km" class="form-control" required></div>
                        <div class="col-md-2"><label class="form-label">Max Price (Per KM)</label><input type="number" step="0.01" name="max_price_per_km" class="form-control" required></div>
                        <div class="col-md-3"><label class="form-label">Commission Type</label><select name="commission_type" class="form-select"><option value="percentage">Percentage</option><option value="fixed">Fixed</option></select></div>
                        <div class="col-md-3"><label class="form-label">Commission Value</label><input type="number" step="0.01" name="commission_value" class="form-control" required></div>
                        <div class="col-md-3"><label class="form-label">Image</label><input type="file" name="image" class="form-control" accept="image/*" required></div>
                        <div class="col-md-3"><div class="form-check mt-4"><input type="checkbox" name="commission_enabled" class="form-check-input" value="1"><label class="form-check-label">Enable Commission</label></div></div>
                    </div>
                    <button type="submit" class="btn btn-primary mt-3">Add Vehicle Type</button>
                </form>
            </div>
        </div>
        <div class="card">
            <div class="card-body">
                <table class="table">
                    <thead><tr><th>ID</th><th>Image</th><th>Type Name</th><th>Min Fixed</th><th>Max Fixed</th><th>Min Per KM</th><th>Max Per KM</th><th>Commission</th><th>Enabled</th></tr></thead>
                    <tbody>
                        <?php foreach ($vehicle_types as $type): ?>
                            <tr>
                                <td><?php echo $type['id']; ?></td>
                                <td><img src="/ride-sharing-app/assets/images/<?php echo $type['image_path']; ?>" alt="<?php echo $type['type_name']; ?>" style="max-width: 50px;"></td>
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
    </div>
</div>
<?php include __DIR__ . '/../includes/footer.php'; ?>