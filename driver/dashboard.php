<?php
session_start();
require_once __DIR__ . '/../includes/db.php';
if ($_SESSION['role'] !== 'driver' || !$pdo->query("SELECT verified FROM users WHERE id = {$_SESSION['user_id']}")->fetchColumn()) {
    header("Location: /ride-sharing-app/login.php");
    exit;
}

$total_earnings = $pdo->query("SELECT SUM(total_fare) FROM ride_bookings WHERE ride_id IN (SELECT id FROM rides WHERE driver_id = {$_SESSION['user_id']}) AND status = 'approved'")->fetchColumn() ?? 0;
$total_commission = $pdo->query("SELECT SUM(commission) FROM ride_bookings WHERE ride_id IN (SELECT id FROM rides WHERE driver_id = {$_SESSION['user_id']}) AND status = 'approved'")->fetchColumn() ?? 0;
$net_earnings = $total_earnings - $total_commission;
$monthly_earnings = $pdo->query("SELECT DATE_FORMAT(created_at, '%Y-%m') AS month, SUM(total_fare - commission) AS net FROM ride_bookings WHERE ride_id IN (SELECT id FROM rides WHERE driver_id = {$_SESSION['user_id']}) AND status = 'approved' GROUP BY month ORDER BY month")->fetchAll(PDO::FETCH_ASSOC);
$page_title = "Driver Dashboard";
?>

<?php include __DIR__ . '/../includes/header.php'; ?>
<div class="row">
    <div class="col-md-12">
        <div class="row g-4 mb-4">
            <div class="col-md-4"><div class="card text-center p-3"><h5>Total Earnings</h5><p class="display-6"><?php echo number_format($total_earnings, 2); ?></p></div></div>
            <div class="col-md-4"><div class="card text-center p-3"><h5>Total Commission</h5><p class="display-6"><?php echo number_format($total_commission, 2); ?></p></div></div>
            <div class="col-md-4"><div class="card text-center p-3"><h5>Net Earnings</h5><p class="display-6"><?php echo number_format($net_earnings, 2); ?></p></div></div>
        </div>
        <div class="card">
            <div class="card-body">
                <h5>Earnings Over Time</h5>
                <canvas id="earningsChart" height="100"></canvas>
            </div>
        </div>
    </div>
</div>
<script>
const ctx = document.getElementById('earningsChart').getContext('2d');
new Chart(ctx, {
    type: 'line',
    data: {
        labels: [<?php echo "'" . implode("','", array_column($monthly_earnings, 'month')) . "'"; ?>],
        datasets: [{
            label: 'Net Earnings',
            data: [<?php echo implode(',', array_column($monthly_earnings, 'net')); ?>],
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