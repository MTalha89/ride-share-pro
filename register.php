<?php
session_start();
require_once __DIR__ . '/includes/db.php';
if (isset($_SESSION['user_id'])) {
    header("Location: /ride-sharing-app/{$_SESSION['role']}/dashboard.php");
    exit;
}

$required_docs = $pdo->query("SELECT doc_name FROM driver_documents WHERE is_required = 1")->fetchAll(PDO::FETCH_COLUMN);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $role = $_POST['role'];
    $chattiness = $_POST['chattiness'];

    $stmt = $pdo->prepare("INSERT INTO users (name, email, password, role, chattiness) VALUES (?, ?, ?, ?, ?)");
    try {
        $stmt->execute([$name, $email, $password, $role, $chattiness]);
        $user_id = $pdo->lastInsertId();

        if ($role === 'driver' && !empty($required_docs)) {
            if (!is_dir(UPLOAD_DIR)) mkdir(UPLOAD_DIR, 0777, true);
            foreach ($required_docs as $doc) {
                if (!isset($_FILES[$doc]) || $_FILES[$doc]['error'] !== UPLOAD_ERR_OK) {
                    $error = "Missing required document: $doc";
                    break;
                }
                $file_name = $user_id . '_' . $doc . '_' . time() . '.' . pathinfo($_FILES[$doc]['name'], PATHINFO_EXTENSION);
                move_uploaded_file($_FILES[$doc]['tmp_name'], UPLOAD_DIR . $file_name);
                $pdo->prepare("INSERT INTO driver_documents_uploaded (driver_id, doc_name, file_path) VALUES (?, ?, ?)")->execute([$user_id, $doc, $file_name]);
            }
            if (!isset($error)) {
                $pdo->prepare("UPDATE users SET verified = 0 WHERE id = ?")->execute([$user_id]); // Pending approval
            }
        } else {
            $_SESSION['user_id'] = $user_id;
            $_SESSION['role'] = $role;
            header("Location: /ride-sharing-app/{$role}/dashboard.php");
            exit;
        }
    } catch (PDOException $e) {
        $error = "Email already exists or upload failed.";
    }
}
?>

<?php include __DIR__ . '/includes/header.php'; ?>
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card p-4">
                <h2 class="text-center mb-4" style="color: #F4A261;">Join <?php echo PROJECT_NAME; ?></h2>
                <?php if (isset($error)) echo "<div class='alert alert-danger'>$error</div>"; ?>
                <form method="POST" enctype="multipart/form-data">
                    <div class="mb-3">
                        <label class="form-label">Name</label>
                        <input type="text" name="name" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <input type="email" name="email" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Password</label>
                        <input type="password" name="password" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Role</label>
                        <select name="role" class="form-select" id="roleSelect" onchange="toggleDriverDocs()" required>
                            <option value="rider">Rider</option>
                            <option value="driver">Driver</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Chattiness</label>
                        <select name="chattiness" class="form-select" required>
                            <option value="quiet">Quiet</option>
                            <option value="chatty">Chatty</option>
                            <option value="neutral">Neutral</option>
                        </select>
                    </div>
                    <div id="driverDocs" style="display: none;">
                        <?php foreach ($required_docs as $doc): ?>
                            <div class="mb-3">
                                <label class="form-label"><?php echo ucfirst(str_replace('_', ' ', $doc)); ?></label>
                                <input type="file" name="<?php echo $doc; ?>" class="form-control" accept=".jpg,.png,.pdf">
                            </div>
                        <?php endforeach; ?>
                    </div>
                    <button type="submit" class="btn btn-primary w-100">Register</button>
                </form>
                <p class="text-center mt-3">Already have an account? <a href="/ride-sharing-app/login.php" style="color: #F4A261;">Login</a></p>
            </div>
        </div>
    </div>
</div>
<script>
function toggleDriverDocs() {
    const role = document.getElementById('roleSelect').value;
    document.getElementById('driverDocs').style.display = role === 'driver' ? 'block' : 'none';
}
</script>
<?php include __DIR__ . '/includes/footer.php'; ?>