<?php
session_start();
require_once __DIR__ . '/../includes/db.php';
if ($_SESSION['role'] !== 'admin') {
    header("Location: /ride-sharing-app/login.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_doc'])) {
    $doc_name = strtolower(str_replace(' ', '_', $_POST['doc_name']));
    $is_required = isset($_POST['is_required']) ? 1 : 0;
    $pdo->prepare("INSERT INTO driver_documents (doc_name, is_required) VALUES (?, ?) ON DUPLICATE KEY UPDATE is_required = ?")->execute([$doc_name, $is_required, $is_required]);
    $success = "Document added.";
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_car_field'])) {
    $field_name = strtolower(str_replace(' ', '_', $_POST['field_name']));
    $field_type = $_POST['field_type'];
    $is_required = isset($_POST['is_required']) ? 1 : 0;
    $pdo->prepare("INSERT INTO car_fields (field_name, field_type, is_required) VALUES (?, ?, ?) ON DUPLICATE KEY UPDATE field_type = ?, is_required = ?")->execute([$field_name, $field_type, $is_required, $field_type, $is_required]);
    $success = "Car field added.";
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update'])) {
    $id = $_POST['id'];
    $table = $_POST['table'];
    $is_required = isset($_POST['is_required']) ? 1 : 0;
    $pdo->prepare("UPDATE $table SET is_required = ? WHERE id = ?")->execute([$is_required, $id]);
    $success = "Status updated.";
}

$documents = $pdo->query("SELECT * FROM driver_documents")->fetchAll(PDO::FETCH_ASSOC);
$car_fields = $pdo->query("SELECT * FROM car_fields")->fetchAll(PDO::FETCH_ASSOC);
$page_title = "Document & Car Settings";
?>

<?php include __DIR__ . '/../includes/header.php'; ?>
<div class="row">
    <div class="col-md-6">
        <div class="card mb-4">
            <div class="card-body">
                <h5>Add Document</h5>
                <?php if (isset($success)) echo "<div class='alert alert-success'>$success</div>"; ?>
                <form method="POST">
                    <div class="mb-3"><label class="form-label">Document Name</label><input type="text" name="doc_name" class="form-control" required></div>
                    <div class="form-check mb-3"><input type="checkbox" name="is_required" class="form-check-input" value="1"><label class="form-check-label">Required</label></div>
                    <button type="submit" name="add_doc" class="btn btn-primary">Add</button>
                </form>
            </div>
        </div>
        <div class="card">
            <div class="card-body">
                <h5>Documents</h5>
                <table class="table">
                    <thead><tr><th>ID</th><th>Name</th><th>Required</th></tr></thead>
                    <tbody>
                        <?php foreach ($documents as $doc): ?>
                            <tr>
                                <td><?php echo $doc['id']; ?></td>
                                <td><?php echo ucfirst(str_replace('_', ' ', $doc['doc_name'])); ?></td>
                                <td>
                                    <form method="POST" style="display:inline;">
                                        <input type="hidden" name="id" value="<?php echo $doc['id']; ?>">
                                        <input type="hidden" name="table" value="driver_documents">
                                        <input type="checkbox" name="is_required" class="form-check-input" <?php echo $doc['is_required'] ? 'checked' : ''; ?> onchange="this.form.submit()">
                                        <input type="hidden" name="update" value="1">
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card mb-4">
            <div class="card-body">
                <h5>Add Car Field</h5>
                <form method="POST">
                    <div class="mb-3"><label class="form-label">Field Name</label><input type="text" name="field_name" class="form-control" required></div>
                    <div class="mb-3"><label class="form-label">Field Type</label><select name="field_type" class="form-select"><option value="text">Text</option><option value="number">Number</option><option value="select">Select</option></select></div>
                    <div class="form-check mb-3"><input type="checkbox" name="is_required" class="form-check-input" value="1"><label class="form-check-label">Required</label></div>
                    <button type="submit" name="add_car_field" class="btn btn-primary">Add</button>
                </form>
            </div>
        </div>
        <div class="card">
            <div class="card-body">
                <h5>Car Fields</h5>
                <table class="table">
                    <thead><tr><th>ID</th><th>Name</th><th>Type</th><th>Required</th></tr></thead>
                    <tbody>
                        <?php foreach ($car_fields as $field): ?>
                            <tr>
                                <td><?php echo $field['id']; ?></td>
                                <td><?php echo ucfirst(str_replace('_', ' ', $field['field_name'])); ?></td>
                                <td><?php echo ucfirst($field['field_type']); ?></td>
                                <td>
                                    <form method="POST" style="display:inline;">
                                        <input type="hidden" name="id" value="<?php echo $field['id']; ?>">
                                        <input type="hidden" name="table" value="car_fields">
                                        <input type="checkbox" name="is_required" class="form-check-input" <?php echo $field['is_required'] ? 'checked' : ''; ?> onchange="this.form.submit()">
                                        <input type="hidden" name="update" value="1">
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<?php include __DIR__ . '/../includes/footer.php'; ?>