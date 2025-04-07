<?php
if (file_exists('../config.php')) {
    die("Project is already installed. Delete config.php to reinstall.");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $project_name = $_POST['project_name'];
    $admin_email = $_POST['admin_email'];
    $admin_password = password_hash($_POST['admin_password'], PASSWORD_DEFAULT);
    $db_name = $_POST['db_name'];
    $db_username = $_POST['db_username'];
    $db_password = $_POST['db_password'];

    try {
        // Test database connection
        $conn = new PDO("mysql:host=localhost;dbname=$db_name", $db_username, $db_password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Run migrations
        $sql = file_get_contents('migrations.sql');
        $conn->exec($sql);

        // Insert admin user
        $stmt = $conn->prepare("INSERT INTO users (email, password, role, name) VALUES (?, ?, 'admin', 'Admin')");
        $stmt->execute([$admin_email, $admin_password]);

        // Create config file
        $config = "<?php\n";
        $config .= "define('DB_HOST', 'localhost');\n";
        $config .= "define('DB_NAME', '$db_name');\n";
        $config .= "define('DB_USER', '$db_username');\n";
        $config .= "define('DB_PASS', '$db_password');\n";
        $config .= "define('PROJECT_NAME', '$project_name');\n";
        file_put_contents('../config.php', $config);

        header("Location: ../login.php");
        exit;
    } catch (PDOException $e) {
        $error = "Error: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Install RideShare</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h2>Project Installation</h2>
        <?php if (isset($error)) echo "<div class='alert alert-danger'>$error</div>"; ?>
        <form method="POST" class="mt-3">
            <div class="mb-3">
                <label>Project Name</label>
                <input type="text" name="project_name" class="form-control" required>
            </div>
            <div class="mb-3">
                <label>Admin Email</label>
                <input type="email" name="admin_email" class="form-control" required>
            </div>
            <div class="mb-3">
                <label>Admin Password</label>
                <input type="password" name="admin_password" class="form-control" required>
            </div>
            <div class="mb-3">
                <label>Database Name</label>
                <input type="text" name="db_name" class="form-control" required>
            </div>
            <div class="mb-3">
                <label>Database Username</label>
                <input type="text" name="db_username" class="form-control" required>
            </div>
            <div class="mb-3">
                <label>Database Password</label>
                <input type="password" name="db_password" class="form-control">
            </div>
            <button type="submit" class="btn btn-primary">Install</button>
        </form>
    </div>
</body>
</html>