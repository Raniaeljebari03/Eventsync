<?php
require_once 'config.php';
if (!isLoggedIn() || !isAdmin()) {
    header('Location: login.php?error=unauthorized');
    exit;
}

$user_id = $_SESSION['user_id'];  // Admin's ID for logs
$message = '';
$messageType = '';

// Delete User
if (isset($_POST['delete_user'])) {
    $target_id = (int)$_POST['user_id'];
    if ($target_id !== $user_id) {  // Can't delete self
        try {
            $pdo->prepare("DELETE FROM users WHERE id = ?")->execute([$target_id]);
            $pdo->prepare("INSERT INTO admin_logs (action, target_id, admin_id) VALUES ('delete_user', ?, ?)")->execute([$target_id, $user_id]);
            $message = "User deleted.";
            $messageType = 'success';
        } catch (Exception $e) {
            $message = "Error: " . $e->getMessage();
            $messageType = 'error';
        }
    } else {
        $message = "Can't delete yourself.";
        $messageType = 'error';
    }
}

// Delete Event
if (isset($_POST['delete_event'])) {
    $target_id = (int)$_POST['event_id'];
    try {
        $pdo->prepare("DELETE FROM events WHERE id = ?")->execute([$target_id]);
        $pdo->prepare("INSERT INTO admin_logs (action, target_id, admin_id) VALUES ('delete_event', ?, ?)")->execute([$target_id, $user_id]);
        $message = "Event deleted.";
        $messageType = 'success';
    } catch (Exception $e) {
        $message = "Error: " . $e->getMessage();
        $messageType = 'error';
    }
}

// Fetch data
$users = $pdo->query("SELECT id, name, email, caswallet_id, profession, created_at FROM users ORDER BY created_at DESC")->fetchAll();
$events = $pdo->query("SELECT e.*, u.name AS creator FROM events e JOIN users u ON e.created_by = u.id ORDER BY date_time DESC")->fetchAll();
$reservations = $pdo->query("SELECT r.*, u.name AS user_name, e.name AS event_name FROM reservations r JOIN users u ON r.user_id = u.id JOIN events e ON r.event_id = e.id ORDER BY reserved_at DESC")->fetchAll();
$logs = $pdo->query("SELECT l.*, u.name AS admin_name FROM admin_logs l JOIN users u ON l.admin_id = u.id ORDER BY timestamp DESC LIMIT 50")->fetchAll();

// Stats
$total_users = $pdo->query("SELECT COUNT(*) FROM users")->fetchColumn();
$total_events = $pdo->query("SELECT COUNT(*) FROM events")->fetchColumn();
$total_reservations = $pdo->query("SELECT COUNT(*) FROM reservations")->fetchColumn();
?>
