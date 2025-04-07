<?php
session_start();
require_once __DIR__ . '/includes/db.php';
if (isset($_SESSION['user_id'])) {
    header("Location: /ride-sharing-app/{$_SESSION['role']}/dashboard.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $role = $_POST['role'];
    $stmt = $pdo->prepare("INSERT INTO users (name, email, password, role) VALUES (?, ?, ?, ?)");
    try {
        $stmt->execute([$name, $email, $password, $role]);
        $_SESSION['user_id'] = $pdo->lastInsertId();
        $_SESSION['role'] = $role;
        header("Location: /ride-sharing-app/{$role}/dashboard.php");
        exit;
    } catch (PDOException $e) {
        $error = "Email already exists.";
    }
}
?>

<?php include __DIR__ . '/includes/header.php'; ?>
<div class="row justify-content-center mt-5">
    <div class="col-md-6 col-lg-4">
        <div class="card p-4">
            <h2 class="text-center mb-4 fw-bold text-primary">Register</h2>
            <?php if (isset($error)) echo "<div class='alert alert-danger'>$error</div>"; ?>
            <form method="POST">
                <div class="mb-3">
                    <label class="form-label fw-semibold">Name</label>
                    <input type="text" name="name" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-semibold">Email</label>
                    <input type="email" name="email" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-semibold">Password</label>
                    <input type="password" name="password" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-semibold">Role</label>
                    <select name="role" class="form-select" required>
                        <option value="rider">Rider</option>
                        <option value="driver">Driver</option>
                    </select>
                </div>
                <button type="submit" class="btn btn-primary w-100">Register</button>
            </form>
            <p class="text-center mt-3">Already have an account? <a href="/ride-sharing-app/login.php" class="text-primary">Login</a></p>
        </div>
    </div>
</div>
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
<?php include __DIR__ . '/includes/footer.php'; ?>