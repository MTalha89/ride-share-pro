<?php
session_start();
require_once __DIR__ . '/../includes/db.php';
if ($_SESSION['role'] !== 'rider') {
    header("Location: /ride-sharing-app/login.php");
    exit;
}

$total_rides = $pdo->query("SELECT COUNT(*) FROM ride_bookings WHERE rider_id = {$_SESSION['user_id']} AND status = 'approved'")->fetchColumn();
$total_spent = $pdo->query("SELECT SUM(total_fare) FROM ride_bookings WHERE rider_id = {$_SESSION['user_id']} AND status = 'approved'")->fetchColumn() ?? 0;
$monthly_spent = $pdo->query("SELECT DATE_FORMAT(created_at, '%Y-%m') AS month, SUM(total_fare) AS spent FROM ride_bookings WHERE rider_id = {$_SESSION['user_id']} AND status = 'approved' GROUP BY month ORDER BY month")->fetchAll(PDO::FETCH_ASSOC);
$page_title = "Rider Dashboard";
?>

<?php include __DIR__ . '/../includes/header.php'; ?>
<div class="row">
    <div class="col-md-12">
        <div class="row g-4 mb-4">
            <div class="col-md-6"><div class="card text-center p-3"><h5>Total Rides</h5><p class="display-6"><?php echo $total_rides; ?></p></div></div>
            <div class="col-md-6"><div class="card text-center p-3"><h5>Total Spent</h5><p class="display-6"><?php echo number_format($total_spent, 2); ?></p></div></div>
        </div>
        <div class="card">
            <div class="card-body">
                <h5>Spending Over Time</h5>
                <canvas id="spendingChart" height="100"></canvas>
            </div>
        </div>
    </div>
</div>
<script>
const ctx = document.getElementById('spendingChart').getContext('2d');
new Chart(ctx, {
    type: 'line',
    data: {
        labels: [<?php echo "'" . implode("','", array_column($monthly_spent, 'month')) . "'"; ?>],
        datasets: [{
            label: 'Spending',
            data: [<?php echo implode(',', array_column($monthly_spent, 'spent')); ?>],
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