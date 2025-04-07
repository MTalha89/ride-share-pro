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
    $success = "Document settings updated.";
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_doc'])) {
    $doc_id = $_POST['doc_id'];
    $is_required = isset($_POST['is_required']) ? 1 : 0;
    $pdo->prepare("UPDATE driver_documents SET is_required = ? WHERE id = ?")->execute([$is_required, $doc_id]);
    $success = "Document status updated.";
}

$documents = $pdo->query("SELECT * FROM driver_documents")->fetchAll(PDO::FETCH_ASSOC);
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
                    <li class="nav-item"><a class="nav-link" href="/ride-sharing-app/admin/vehicle_types.php"><i class="bi bi-truck me-2"></i>Vehicle Types</a></li>
                    <li class="nav-item"><a class="nav-link" href="/ride-sharing-app/admin/driver_approvals.php"><i class="bi bi-check-circle-fill me-2"></i>Driver Approvals</a></li>
                    <li class="nav-item"><a class="nav-link active" href="/ride-sharing-app/admin/document_settings.php"><i class="bi bi-file-earmark-text me-2"></i>Document Settings</a></li>
                    <li class="nav-item"><a class="nav-link" href="/ride-sharing-app/admin/settings.php"><i class="bi bi-gear-fill me-2"></i>Settings</a></li>
                    <li class="nav-item"><a class="nav-link" href="/ride-sharing-app/logout.php"><i class="bi bi-box-arrow-right me-2"></i>Logout</a></li>
                </ul>
            </div>
        </nav>
        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
            <div class="d-flex justify-content-between align-items-center pt-3 pb-2 mb-4 border-bottom">
                <h1 class="h2">Document Settings</h1>
            </div>
            <div class="card mb-4">
                <div class="card-body">
                    <?php if (isset($success)) echo "<div class='alert alert-success'>$success</div>"; ?>
                    <form method="POST">
                        <div class="row g-3">
                            <div class="col-md-6"><label class="form-label">Document Name</label><input type="text" name="doc_name" class="form-control" required></div>
                            <div class="col-md-6"><div class="form-check mt-4"><input type="checkbox" name="is_required" class="form-check-input" value="1"><label class="form-check-label">Required</label></div></div>
                        </div>
                        <button type="submit" name="add_doc" class="btn btn-primary mt-3">Add Document</button>
                    </form>
                </div>
            </div>
            <div class="card">
                <div class="card-body">
                    <table class="table">
                        <thead>
                            <tr><th>ID</th><th>Document Name</th><th>Required</th><th>Action</th></tr>
                        </thead>
                        <tbody>
                            <?php foreach ($documents as $doc): ?>
                                <tr>
                                    <td><?php echo $doc['id']; ?></td>
                                    <td><?php echo ucfirst(str_replace('_', ' ', $doc['doc_name'])); ?></td>
                                    <td><?php echo $doc['is_required'] ? 'Yes' : 'No'; ?></td>
                                    <td>
                                        <form method="POST" style="display:inline;">
                                            <input type="hidden" name="doc_id" value="<?php echo $doc['id']; ?>">
                                            <input type="checkbox" name="is_required" class="form-check-input" <?php echo $doc['is_required'] ? 'checked' : ''; ?> onchange="this.form.submit()">
                                            <input type="hidden" name="update_doc" value="1">
                                        </form>
                                    </td>
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