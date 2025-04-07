<?php
session_start();
require_once __DIR__ . '/../includes/db.php';
if ($_SESSION['role'] !== 'admin') {
    header("Location: /ride-sharing-app/login.php");
    exit;
}

$users_count = $pdo->query("SELECT COUNT(*) FROM users")->fetchColumn();
$rides_count = $pdo->query("SELECT COUNT(*) FROM rides")->fetchColumn();
$bookings_count = $pdo->query("SELECT COUNT(*) FROM ride_bookings")->fetchColumn();
$monthly_users = $pdo->query("SELECT DATE_FORMAT(created_at, '%Y-%m') AS month, COUNT(*) AS count FROM users GROUP BY month ORDER BY month")->fetchAll(PDO::FETCH_ASSOC);
?>

<?php include __DIR__ . '/../includes/header.php'; ?>
<div class="container-fluid">
    <div class="row">
        <nav id="sidebar" class="col-md-3 col-lg-2 d-md-block sidebar">
            <div class="position-sticky pt-3">
                <h4 class="text-center">Admin Panel</h4>
                <ul class="nav flex-column">
                    <li class="nav-item"><a class="nav-link active" href="/ride-sharing-app/admin/dashboard.php"><i class="bi bi-house-fill me-2"></i>Dashboard</a></li>
                    <li class="nav-item"><a class="nav-link" href="/ride-sharing-app/admin/manage_users.php"><i class="bi bi-people-fill me-2"></i>Users</a></li>
                    <li class="nav-item"><a class="nav-link" href="/ride-sharing-app/admin/manage_rides.php"><i class="bi bi-car-front-fill me-2"></i>Rides</a></li>
                    <li class="nav-item"><a class="nav-link" href="/ride-sharing-app/admin/manage_bookings.php"><i class="bi bi-calendar-check-fill me-2"></i>Bookings</a></li>
                    <li class="nav-item"><a class="nav-link" href="/ride-sharing-app/admin/vehicle_types.php"><i class="bi bi-truck me-2"></i>Vehicle Types</a></li>
                    <li class="nav-item"><a class="nav-link" href="/ride-sharing-app/admin/driver_approvals.php"><i class="bi bi-check-circle-fill me-2"></i>Driver Approvals</a></li>
                    <li class="nav-item"><a class="nav-link" href="/ride-sharing-app/admin/document_settings.php"><i class="bi bi-file-earmark-text me-2"></i>Document Settings</a></li>
                    <li class="nav-item"><a class="nav-link" href="/ride-sharing-app/admin/settings.php"><i class="bi bi-gear-fill me-2"></i>Settings</a></li>
                    <li class="nav-item"><a class="nav-link" href="/ride-sharing-app/logout.php"><i class="bi bi-box-arrow-right me-2"></i>Logout</a></li>
                </ul>
            </div>
        </nav>
        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
            <div class="d-flex justify-content-between align-items-center pt-3 pb-2 mb-4 border-bottom">
                <h1 class="h2">Admin Dashboard</h1>
            </div>
            <div class="row g-4">
                <div class="col-md-4"><div class="card text-center p-3"><h5>Total Users</h5><p class="display-6"><?php echo $users_count; ?></p></div></div>
                <div class="col-md-4"><div class="card text-center p-3"><h5>Total Rides</h5><p class="display-6"><?php echo $rides_count; ?></p></div></div>
                <div class="col-md-4"><div class="card text-center p-3"><h5>Total Bookings</h5><p class="display-6"><?php echo $bookings_count; ?></p></div></div>
            </div>
            <div class="card mt-4">
                <div class="card-body">
                    <h5>User Growth</h5>
                    <canvas id="userGrowthChart" height="100"></canvas>
                </div>
            </div>
        </main>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
const ctx = document.getElementById('userGrowthChart').getContext('2d');
new Chart(ctx, {
    type: 'line',
    data: {
        labels: [<?php echo "'" . implode("','", array_column($monthly_users, 'month')) . "'"; ?>],
        datasets: [{
            label: 'New Users',
            data: [<?php echo implode(',', array_column($monthly_users, 'count')); ?>],
            borderColor: '#F4A261',
            tension: 0.4,
            fill: true,
            backgroundColor: 'rgba(244, 162, 97, 0.2)'
        }]
    },
    options: { scales: { y: { beginAtZero: true } } }
});
</script>
<?php include __DIR__ . '/../includes/footer.php'; ?>