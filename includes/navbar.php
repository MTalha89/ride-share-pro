<?php
// session_start();
$role = $_SESSION['role'] ?? 'guest';
?>

<nav class="navbar navbar-expand-lg navbar-dark" style="background: linear-gradient(45deg, #1A3C34, #F4A261);">
    <div class="container-fluid">
        <a class="navbar-brand fw-bold" href="/ride-sharing-app/"><?php echo PROJECT_NAME; ?></a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <?php if ($role === 'admin'): ?>
                    <li class="nav-item"><a class="nav-link" href="/ride-sharing-app/admin/dashboard.php"><i class="bi bi-house-fill me-2"></i>Dashboard</a></li>
                    <li class="nav-item"><a class="nav-link" href="/ride-sharing-app/admin/manage_users.php"><i class="bi bi-people-fill me-2"></i>Users</a></li>
                    <li class="nav-item"><a class="nav-link" href="/ride-sharing-app/admin/manage_rides.php"><i class="bi bi-car-front-fill me-2"></i>Rides</a></li>
                    <li class="nav-item"><a class="nav-link" href="/ride-sharing-app/admin/manage_bookings.php"><i class="bi bi-calendar-check-fill me-2"></i>Bookings</a></li>
                    <li class="nav-item"><a class="nav-link" href="/ride-sharing-app/admin/vehicle_types.php"><i class="bi bi-truck me-2"></i>Vehicle Types</a></li>
                    <li class="nav-item"><a class="nav-link" href="/ride-sharing-app/admin/driver_approvals.php"><i class="bi bi-check-circle-fill me-2"></i>Approvals</a></li>
                    <li class="nav-item"><a class="nav-link" href="/ride-sharing-app/admin/document_settings.php"><i class="bi bi-file-earmark-text me-2"></i>Documents</a></li>
                    <li class="nav-item"><a class="nav-link" href="/ride-sharing-app/admin/settings.php"><i class="bi bi-gear-fill me-2"></i>Settings</a></li>
                <?php elseif ($role === 'driver'): ?>
                    <li class="nav-item"><a class="nav-link" href="/ride-sharing-app/driver/dashboard.php"><i class="bi bi-house-fill me-2"></i>Dashboard</a></li>
                    <li class="nav-item"><a class="nav-link" href="/ride-sharing-app/driver/post_ride.php"><i class="bi bi-plus-circle-fill me-2"></i>Post Ride</a></li>
                    <li class="nav-item"><a class="nav-link" href="/ride-sharing-app/driver/my_rides.php"><i class="bi bi-car-front-fill me-2"></i>My Rides</a></li>
                    <li class="nav-item"><a class="nav-link" href="/ride-sharing-app/messages.php"><i class="bi bi-chat-fill me-2"></i>Messages</a></li>
                <?php elseif ($role === 'rider'): ?>
                    <li class="nav-item"><a class="nav-link" href="/ride-sharing-app/rider/dashboard.php"><i class="bi bi-house-fill me-2"></i>Dashboard</a></li>
                    <li class="nav-item"><a class="nav-link" href="/ride-sharing-app/rider/search_rides.php"><i class="bi bi-search me-2"></i>Search Rides</a></li>
                    <li class="nav-item"><a class="nav-link" href="/ride-sharing-app/rider/my_bookings.php"><i class="bi bi-calendar-check-fill me-2"></i>My Bookings</a></li>
                    <li class="nav-item"><a class="nav-link" href="/ride-sharing-app/messages.php"><i class="bi bi-chat-fill me-2"></i>Messages</a></li>
                <?php else: ?>
                    <li class="nav-item"><a class="nav-link" href="/ride-sharing-app/login.php"><i class="bi bi-box-arrow-in-right me-2"></i>Login</a></li>
                    <li class="nav-item"><a class="nav-link" href="/ride-sharing-app/register.php"><i class="bi bi-person-plus-fill me-2"></i>Register</a></li>
                <?php endif; ?>
                <?php if ($role !== 'guest'): ?>
                    <li class="nav-item"><a class="nav-link" href="/ride-sharing-app/edit_profile.php"><i class="bi bi-person-fill me-2"></i>Profile</a></li>
                    <li class="nav-item"><a class="nav-link" href="/ride-sharing-app/logout.php"><i class="bi bi-box-arrow-right me-2"></i>Logout</a></li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</nav>