<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo defined('PROJECT_NAME') ? PROJECT_NAME : 'RideShare'; ?></title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link href="/ride-sharing-app/assets/css/style.css" rel="stylesheet">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top shadow-sm">
        <div class="container-fluid">
            <a class="navbar-brand fw-bold text-primary" href="/ride-sharing-app/"><?php echo PROJECT_NAME; ?></a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <?php if (isset($_SESSION['user_id'])): ?>
                        <?php if (!in_array(basename($_SERVER['PHP_SELF']), ['login.php', 'register.php'])): ?>
                            <li class="nav-item"><a class="nav-link text-light" href="/ride-sharing-app/<?php echo $_SESSION['role']; ?>/dashboard.php"><i class="bi bi-house-fill me-1"></i> Dashboard</a></li>
                            <li class="nav-item"><a class="nav-link text-light" href="/ride-sharing-app/edit_profile.php"><i class="bi bi-person-fill me-1"></i> Profile</a></li>
                            <li class="nav-item"><a class="nav-link text-light" href="/ride-sharing-app/logout.php"><i class="bi bi-box-arrow-right me-1"></i> Logout</a></li>
                        <?php endif; ?>
                    <?php else: ?>
                        <li class="nav-item"><a class="nav-link text-light" href="/ride-sharing-app/login.php"><i class="bi bi-box-arrow-in-right me-1"></i> Login</a></li>
                        <li class="nav-item"><a class="nav-link text-light" href="/ride-sharing-app/register.php"><i class="bi bi-person-plus-fill me-1"></i> Register</a></li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>
    <div class="container-fluid mt-5">