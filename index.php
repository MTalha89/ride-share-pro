<?php
session_start();
if (isset($_SESSION['user_id'])) {
    header("Location: /ride-sharing-app/{$_SESSION['role']}/dashboard.php");
    exit;
}
require_once __DIR__ . '/includes/db.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>RideSharePro - Your Journey Starts Here</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <link href="/ride-sharing-app/assets/css/style.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        .hero-slider { height: 100vh; background: linear-gradient(45deg, #1A3C34, #F4A261); animation: gradientBG 15s ease infinite; }
        @keyframes gradientBG { 0% { background: linear-gradient(45deg, #1A3C34, #F4A261); } 50% { background: linear-gradient(45deg, #F4A261, #E76F51); } 100% { background: linear-gradient(45deg, #1A3C34, #F4A261); } }
        .hero-content h1 { font-size: 3.5rem; animation: fadeInUp 1s ease; }
        .hero-content p { font-size: 1.5rem; animation: fadeInUp 1.5s ease; }
        .auth-form { background: rgba(255, 255, 255, 0.9); padding: 30px; border-radius: 15px; box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2); animation: slideIn 1s ease; max-width: 400px; }
        @keyframes slideIn { from { transform: translateY(50px); opacity: 0; } to { transform: translateY(0); opacity: 1; } }
        @keyframes fadeInUp { from { transform: translateY(20px); opacity: 0; } to { transform: translateY(0); opacity: 1; } }
        .feature-card { border: none; border-radius: 15px; box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1); transition: transform 0.3s; }
        .feature-card:hover { transform: translateY(-10px); box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2); }
        .feature-card i { font-size: 2.5rem; color: #F4A261; transition: color 0.3s; }
        .feature-card:hover i { color: #E76F51; }
        .graph-section { background: #fff; padding: 50px 0; }
    </style>
</head>
<body>
    <section class="hero-slider">
        <div class="hero-content text-center text-white">
            <h1>Welcome to RideSharePro</h1>
            <p>Your Journey Starts Here – Affordable, Safe, and Social!</p>
            <div class="auth-form mt-4">
                <div id="login-form" style="display: block;">
                    <h3 class="text-center" style="color: #1A3C34;">Login</h3>
                    <form method="POST" action="/ride-sharing-app/login.php">
                        <div class="mb-3"><input type="email" name="email" class="form-control" placeholder="Email" required></div>
                        <div class="mb-3"><input type="password" name="password" class="form-control" placeholder="Password" required></div>
                        <button type="submit" class="btn btn-primary w-100">Login</button>
                    </form>
                    <p class="text-center mt-2">New here? <span class="toggle-form" onclick="toggleForms()">Register</span></p>
                </div>
                <div id="register-form" style="display: none;">
                    <h3 class="text-center" style="color: #1A3C34;">Register</h3>
                    <form method="POST" action="/ride-sharing-app/register.php">
                        <div class="mb-3"><input type="text" name="name" class="form-control" placeholder="Name" required></div>
                        <div class="mb-3"><input type="email" name="email" class="form-control" placeholder="Email" required></div>
                        <div class="mb-3"><input type="password" name="password" class="form-control" placeholder="Password" required></div>
                        <div class="mb-3">
                            <select name="role" class="form-select" required>
                                <option value="rider">Rider</option>
                                <option value="driver">Driver</option>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary w-100">Register</button>
                    </form>
                    <p class="text-center mt-2">Have an account? <span class="toggle-form" onclick="toggleForms()">Login</span></p>
                </div>
            </div>
        </div>
    </section>

    <section class="graph-section">
        <div class="container">
            <h2 class="text-center mb-5" style="color: #1A3C34;">Our Growth</h2>
            <canvas id="growthChart" height="100"></canvas>
        </div>
    </section>

    <section class="py-5" style="background-color: #F7F7F7;">
        <div class="container">
            <h2 class="text-center mb-5" style="color: #1A3C34;">Why Choose RideSharePro?</h2>
            <div class="row g-4">
                <div class="col-md-4"><div class="feature-card text-center p-4"><i class="bi bi-car-front-fill mb-3"></i><h5>Easy Ride Posting</h5><p>Drivers set their own prices and schedules.</p></div></div>
                <div class="col-md-4"><div class="feature-card text-center p-4"><i class="bi bi-search mb-3"></i><h5>Smart Ride Search</h5><p>Riders find rides with real-time availability.</p></div></div>
                <div class="col-md-4"><div class="feature-card text-center p-4"><i class="bi bi-chat-fill mb-3"></i><h5>Connect & Chat</h5><p>Message drivers or riders directly.</p></div></div>
            </div>
        </div>
    </section>

    <footer class="bg-dark text-white text-center py-3">
        <p>© 2025 RideSharePro. All rights reserved.</p>
    </footer>

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js"></script>
    <script src="/ride-sharing-app/assets/js/app.js"></script>
    <script>
        function toggleForms() {
            const loginForm = document.getElementById('login-form');
            const registerForm = document.getElementById('register-form');
            loginForm.style.display = loginForm.style.display === 'block' ? 'none' : 'block';
            registerForm.style.display = registerForm.style.display === 'none' ? 'block' : 'none';
        }

        // Sample Chart Data
        const ctx = document.getElementById('growthChart').getContext('2d');
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
                datasets: [{
                    label: 'Users',
                    data: [50, 100, 200, 350, 500, 700],
                    borderColor: '#F4A261',
                    tension: 0.4,
                    fill: true,
                    backgroundColor: 'rgba(244, 162, 97, 0.2)'
                }]
            },
            options: { scales: { y: { beginAtZero: true } } }
        });
    </script>
</body>
</html>