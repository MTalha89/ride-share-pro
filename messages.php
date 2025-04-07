<?php
session_start();
require_once __DIR__ . '/includes/db.php';
if (!isset($_SESSION['user_id'])) {
    header("Location: /ride-sharing-app/login.php");
    exit;
}

$receiver_id = isset($_GET['receiver_id']) ? $_GET['receiver_id'] : null;
$messages = [];
$receiver_name = '';

if ($receiver_id) {
    $stmt = $pdo->prepare("SELECT name FROM users WHERE id = ?");
    $stmt->execute([$receiver_id]);
    $receiver_name = $stmt->fetchColumn();

    $stmt = $pdo->prepare("
        SELECT * FROM messages 
        WHERE (sender_id = ? AND receiver_id = ?) OR (sender_id = ? AND receiver_id = ?) 
        ORDER BY timestamp ASC
    ");
    $stmt->execute([$_SESSION['user_id'], $receiver_id, $receiver_id, $_SESSION['user_id']]);
    $messages = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $message = $_POST['message'];
        $stmt = $pdo->prepare("INSERT INTO messages (sender_id, receiver_id, message) VALUES (?, ?, ?)");
        $stmt->execute([$_SESSION['user_id'], $receiver_id, $message]);
        header("Location: /ride-sharing-app/messages.php?receiver_id=$receiver_id");
        exit;
    }
}

$contacts = $pdo->query("
    SELECT DISTINCT u.id, u.name 
    FROM users u 
    JOIN messages m ON (u.id = m.sender_id OR u.id = m.receiver_id) 
    WHERE (m.sender_id = {$_SESSION['user_id']} OR m.receiver_id = {$_SESSION['user_id']}) 
    AND u.id != {$_SESSION['user_id']}
")->fetchAll(PDO::FETCH_ASSOC);
?>

<?php include __DIR__ . '/includes/header.php'; ?>
<div class="container-fluid">
    <div class="row">
        <nav id="sidebar" class="col-md-3 col-lg-2 d-md-block sidebar">
            <div class="position-sticky pt-3">
                <h4 class="text-center"><?php echo $_SESSION['role'] === 'rider' ? 'Rider' : 'Driver'; ?> Panel</h4>
                <ul class="nav flex-column">
                    <li class="nav-item"><a class="nav-link" href="/ride-sharing-app/<?php echo $_SESSION['role']; ?>/dashboard.php"><i class="bi bi-house-fill me-2"></i>Dashboard</a></li>
                    <?php if ($_SESSION['role'] === 'rider'): ?>
                        <li class="nav-item"><a class="nav-link" href="/ride-sharing-app/rider/search_rides.php"><i class="bi bi-search me-2"></i>Search Rides</a></li>
                        <li class="nav-item"><a class="nav-link" href="/ride-sharing-app/rider/my_bookings.php"><i class="bi bi-calendar-check-fill me-2"></i>My Bookings</a></li>
                    <?php else: ?>
                        <li class="nav-item"><a class="nav-link" href="/ride-sharing-app/driver/post_ride.php"><i class="bi bi-plus-circle-fill me-2"></i>Post Ride</a></li>
                        <li class="nav-item"><a class="nav-link" href="/ride-sharing-app/driver/my_rides.php"><i class="bi bi-car-front-fill me-2"></i>My Rides</a></li>
                        <li class="nav-item"><a class="nav-link" href="/ride-sharing-app/driver/bookings.php"><i class="bi bi-calendar-check-fill me-2"></i>Bookings</a></li>
                    <?php endif; ?>
                    <li class="nav-item"><a class="nav-link active" href="/ride-sharing-app/messages.php"><i class="bi bi-chat-fill me-2"></i>Messages</a></li>
                    <li class="nav-item"><a class="nav-link" href="/ride-sharing-app/edit_profile.php"><i class="bi bi-person-fill me-2"></i>Profile</a></li>
                    <li class="nav-item"><a class="nav-link" href="/ride-sharing-app/logout.php"><i class="bi bi-box-arrow-right me-2"></i>Logout</a></li>
                </ul>
            </div>
        </nav>
        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
            <div class="d-flex justify-content-between align-items-center pt-3 pb-2 mb-4 border-bottom">
                <h1 class="h2">Messages</h1>
            </div>
            <div class="row">
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Contacts</h5>
                            <ul class="list-group">
                                <?php foreach ($contacts as $contact): ?>
                                    <li class="list-group-item">
                                        <a href="/ride-sharing-app/messages.php?receiver_id=<?php echo $contact['id']; ?>" class="text-decoration-none <?php echo $receiver_id == $contact['id'] ? 'fw-bold' : ''; ?>">
                                            <?php echo htmlspecialchars($contact['name']); ?>
                                        </a>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="col-md-8">
                    <?php if ($receiver_id): ?>
                        <div class="card">
                            <div class="card-header">
                                <h5 class="mb-0">Chat with <?php echo htmlspecialchars($receiver_name); ?></h5>
                            </div>
                            <div class="card-body chat-box">
                                <?php foreach ($messages as $msg): ?>
                                    <div class="mb-3 <?php echo $msg['sender_id'] == $_SESSION['user_id'] ? 'text-end' : ''; ?>">
                                        <span class="badge <?php echo $msg['sender_id'] == $_SESSION['user_id'] ? 'bg-primary' : 'bg-secondary'; ?>">
                                            <?php echo htmlspecialchars($msg['message']); ?>
                                        </span>
                                        <small class="text-muted d-block"><?php echo $msg['timestamp']; ?></small>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                            <div class="card-footer">
                                <form method="POST">
                                    <div class="input-group">
                                        <input type="text" name="message" class="form-control" placeholder="Type a message..." required>
                                        <button type="submit" class="btn btn-primary">Send</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    <?php else: ?>
                        <div class="card text-center">
                            <div class="card-body">
                                <h5>Select a contact to start messaging</h5>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </main>
    </div>
</div>
<?php include __DIR__ . '/includes/footer.php'; ?>