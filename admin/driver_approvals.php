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
$page_title = "Driver Approvals";
?>

<?php include __DIR__ . '/../includes/header.php'; ?>
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <?php if (isset($success)) echo "<div class='alert alert-success'>$success</div>"; ?>
                <table class="table">
                    <thead><tr><th>ID</th><th>Name</th><th>Email</th><th>Documents</th><th>Car Details</th><th>Actions</th></tr></thead>
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
                                    <?php
                                    $car_details = $pdo->prepare("SELECT field_name, field_value FROM driver_cars WHERE driver_id = ?");
                                    $car_details->execute([$driver['id']]);
                                    foreach ($car_details->fetchAll() as $detail) {
                                        echo ucfirst(str_replace('_', ' ', $detail['field_name'])) . ": " . $detail['field_value'] . "<br>";
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
    </div>
</div>
<?php include __DIR__ . '/../includes/footer.php'; ?>