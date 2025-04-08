<?php
session_start();
if (isset($_SESSION['user_id'])) {
    header("Location: /ride-sharing-app/{$_SESSION['role']}/dashboard.php");
    exit;
}
require_once __DIR__ . '/includes/db.php';
$page_title = "Welcome";
?>

<?php include __DIR__ . '/includes/header.php'; ?>
<!-- Hero Section -->
<section class="hero position-relative overflow-hidden" style="min-height: 100vh; background: linear-gradient(135deg, #1A3C34 0%, #F4A261 100%);">
    <div class="container h-100 d-flex align-items-center justify-content-center text-white text-center">
        <div class="hero-content">
            <h1 class="display-3 fw-bold animate__animated animate__fadeInDown">RideSharePro</h1>
            <p class="lead animate__animated animate__fadeInUp">Ride Smart, Save Big, Connect Easily</p>
            <div class="row justify-content-center mt-5">
                <div class="col-md-5">
                    <div class="card p-4 shadow-lg animate__animated animate__zoomIn" style="background: rgba(255, 255, 255, 0.95); border-radius: 20px;">
                        <ul class="nav nav-pills mb-3 justify-content-center" id="authTab" role="tablist">
                            <li class="nav-item"><a class="nav-link active" id="login-tab" data-bs-toggle="pill" href="#login" role="tab">Login</a></li>
                            <li class="nav-item"><a class="nav-link" id="register-tab" data-bs-toggle="pill" href="#register" role="tab">Register</a></li>
                        </ul>
                        <div class="tab-content">
                            <div class="tab-pane fade show active" id="login" role="tabpanel">
                                <form method="POST" action="/ride-sharing-app/login.php">
                                    <div class="mb-3"><input type="email" name="email" class="form-control rounded-pill" placeholder="Email" required></div>
                                    <div class="mb-3"><input type="password" name="password" class="form-control rounded-pill" placeholder="Password" required></div>
                                    <button type="submit" class="btn btn-primary w-100 rounded-pill">Login</button>
                                </form>
                            </div>
                            <div class="tab-pane fade" id="register" role="tabpanel">
                                <form method="POST" action="/ride-sharing-app/register.php">
                                    <div class="mb-3"><input type="text" name="name" class="form-control rounded-pill" placeholder="Full Name" required></div>
                                    <div class="mb-3"><input type="email" name="email" class="form-control rounded-pill" placeholder="Email" required></div>
                                    <div class="mb-3"><input type="password" name="password" class="form-control rounded-pill" placeholder="Password" required></div>
                                    <div class="mb-3">
                                        <select name="role" class="form-select rounded-pill">
                                            <option value="rider">Rider</option>
                                            <option value="driver">Driver</option>
                                        </select>
                                    </div>
                                    <button type="submit" class="btn btn-primary w-100 rounded-pill">Register</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Stats Section with Graphs -->
<section class="py-5 bg-light">
    <div class="container">
        <h2 class="text-center mb-5" style="color: #1A3C34;">Our Impact</h2>
        <div class="row g-4">
            <div class="col-md-4"><canvas id="usersChart" height="150"></canvas></div>
            <div class="col-md-4"><canvas id="ridesChart" height="150"></canvas></div>
            <div class="col-md-4"><canvas id="revenueChart" height="150"></canvas></div>
        </div>
    </div>
</section>

<!-- Features Section -->
<section class="py-5">
    <div class="container">
        <h2 class="text-center mb-5" style="color: #1A3C34;">Why RideSharePro?</h2>
        <div class="row g-4">
            <div class="col-md-4"><div class="card shadow-sm text-center p-4"><i class="bi bi-car-front-fill text-primary fs-1 mb-3"></i><h5>Ride Your Way</h5><p>Flexible options for drivers and riders.</p></div></div>
            <div class="col-md-4"><div class="card shadow-sm text-center p-4"><i class="bi bi-wallet2 text-primary fs-1 mb-3"></i><h5>Affordable Pricing</h5><p>Competitive rates with no hidden fees.</p></div></div>
            <div class="col-md-4"><div class="card shadow-sm text-center p-4"><i class="bi bi-shield-lock text-primary fs-1 mb-3"></i><h5>Safe & Secure</h5><p>Verified drivers and secure payments.</p></div></div>
        </div>
    </div>
</section>

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js"></script>
<script>
const usersChart = new Chart(document.getElementById('usersChart').getContext('2d'), {
    type: 'line', data: { labels: ['Jan', 'Feb', 'Mar', 'Apr'], datasets: [{ label: 'Users', data: [100, 150, 200, 300], borderColor: '#F4A261', tension: 0.4, fill: true, backgroundColor: 'rgba(244, 162, 97, 0.2)' }] }, options: { scales: { y: { beginAtZero: true } } }
});
const ridesChart = new Chart(document.getElementById('ridesChart').getContext('2d'), {
    type: 'line', data: { labels: ['Jan', 'Feb', 'Mar', 'Apr'], datasets: [{ label: 'Rides', data: [50, 80, 120, 180], borderColor: '#1A3C34', tension: 0.4, fill: true, backgroundColor: 'rgba(26, 60, 52, 0.2)' }] }, options: { scales: { y: { beginAtZero: true } } }
});
const revenueChart = new Chart(document.getElementById('revenueChart').getContext('2d'), {
    type: 'line', data: { labels: ['Jan', 'Feb', 'Mar', 'Apr'], datasets: [{ label: 'Revenue', data: [5000, 8000, 12000, 18000], borderColor: '#E76F51', tension: 0.4, fill: true, backgroundColor: 'rgba(231, 111, 81, 0.2)' }] }, options: { scales: { y: { beginAtZero: true } } }
});
</script>
<?php include __DIR__ . '/includes/footer.php'; ?>