<?php
session_start();
require_once __DIR__ . '/../includes/db.php';
if ($_SESSION['role'] !== 'admin') {
    header("Location: /ride-sharing-app/login.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $driver_id = $_POST['driver_id'];
    $action = $_POST['action'];
    $status = $action === 'approve' ? 1 : 0;
    $pdo->prepare("UPDATE users SET verified = ? WHERE id = ? AND role = 'driver'")->execute([$status, $driver_id]);
    $success = "Driver " . ($action === 'approve' ? 'approved' : 'rejected') . ".";
}

$drivers = $pdo->query("SELECT u.id, u.name, u.email, u.verified FROM users u WHERE u.role = 'driver' AND u.verified = 0")->fetchAll(PDO::FETCH_ASSOC);
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
                    <li class="nav-item"><a class="nav-link active" href="/ride-sharing-app/admin/driver_approvals.php"><i class="bi bi-check-circle-fill me-2"></i>Driver Approvals</a></li>
                    <li class="nav-item"><a class="nav-link" href="/ride-sharing-app/admin/document_settings.php"><i class="bi bi-file-earmark-text me-2"></i>Document Settings</a></li>
                    <li class="nav-item"><a class="nav-link" href="/ride-sharing-app/admin/settings.php"><i class="bi bi-gear-fill me-2"></i>Settings</a></li>
                    <li class="nav-item"><a class="nav-link" href="/ride-sharing-app/logout.php"><i class="bi bi-box-arrow-right me-2"></i>Logout</a></li>
                </ul>
            </div>
        </nav>
        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
            <div class="d-flex justify-content-between align-items-center pt-3 pb-2 mb-4 border-bottom">
                <h1 class="h2">Driver Approvals</h1>
            </div>
            <div class="card">
                <div class="card-body">
                    <?php if (isset($success)) echo "<div class='alert alert-success'>$success</div>"; ?>
                    <table class="table">
                        <thead>
                            <tr><th>ID</th><th>Name</th><th>Email</th><th>Documents</th><th>Actions</th></tr>
                        </thead>
                        <tbody>
                            <?php foreach ($drivers as $driver): ?>
                                <tr>
                                    <td><?php echo $driver['id']; ?></td>
                                    <td><?php echo htmlspecialchars($driver['name']); ?></td>
                                    <td><?php echo htmlspecialchars($driver['email']); ?></td>
                                    <td>
                                        <?php
                                        $docs = $pdo->prepare("SELECT doc_name, file_path FROM driver_documents_uploaded WHERE driver_id = ?");
                                        $docs->execute([$driver['id']]);
                                        foreach ($docs->fetchAll() as $doc) {
                                            echo "<a href='/ride-sharing-app/assets/images/uploads/{$doc['file_path']}' target='_blank'>" . ucfirst($doc['doc_name']) . "</a><br>";
                                        }
                                        ?>
                                    </td>
                                    <td>
                                        <form method="POST" style="display:inline;">
                                            <input type="hidden" name="driver_id" value="<?php echo $driver['id']; ?>">
                                            <button type="submit" name="action" value="approve" class="btn btn-success btn-sm">Approve</button>
                                            <button type="submit" name="action" value="reject" class="btn btn-danger btn-sm">Reject</button>
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