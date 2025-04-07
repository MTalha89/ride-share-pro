<?php
session_start();
require_once __DIR__ . '/includes/db.php';
if (!isset($_SESSION['user_id'])) {
    header("Location: /ride-sharing-app/login.php");
    exit;
}

$stmt = $pdo->prepare("SELECT name, email, chattiness FROM users WHERE id = ?");
$stmt->execute([$_SESSION['user_id']]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $chattiness = $_POST['chattiness'];
    $password = !empty($_POST['password']) ? password_hash($_POST['password'], PASSWORD_DEFAULT) : $user['password'];

    $stmt = $pdo->prepare("UPDATE users SET name = ?, chattiness = ?, password = ? WHERE id = ?");
    $stmt->execute([$name, $chattiness, $password, $_SESSION['user_id']]);
    $success = "Profile updated successfully.";
}
?>

<?php include __DIR__ . '/includes/header.php'; ?>
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card p-4">
                <h2 class="text-center mb-4" style="color: #F4A261;">Edit Profile</h2>
                <?php if (isset($success)) echo "<div class='alert alert-success'>$success</div>"; ?>
                <form method="POST">
                    <div class="mb-3">
                        <label class="form-label fw-bold">Name</label>
                        <input type="text" name="name" class="form-control" value="<?php echo htmlspecialchars($user['name']); ?>" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Email</label>
                        <input type="email" class="form-control" value="<?php echo htmlspecialchars($user['email']); ?>" readonly>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Chattiness</label>
                        <select name="chattiness" class="form-select">
                            <option value="quiet" <?php echo $user['chattiness'] === 'quiet' ? 'selected' : ''; ?>>Quiet</option>
                            <option value="chatty" <?php echo $user['chattiness'] === 'chatty' ? 'selected' : ''; ?>>Chatty</option>
                            <option value="neutral" <?php echo $user['chattiness'] === 'neutral' ? 'selected' : ''; ?>>Neutral</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">New Password (leave blank to keep current)</label>
                        <input type="password" name="password" class="form-control">
                    </div>
                    <button type="submit" class="btn btn-primary w-100">Save Changes</button>
                </form>
            </div>
        </div>
    </div>
</div>
<?php include __DIR__ . '/includes/footer.php'; ?>