<?php
require_once 'config.php';
if (!isLoggedIn()) {
    header('Location: login.php');
    exit;
}

// Get numeric user_id from session
$user_id = $_SESSION['user_id'] ?? null;
if (!$user_id) {
    // Fallback: fetch from caswallet_id
    $stmt = $pdo->prepare("SELECT id FROM users WHERE caswallet_id = ?");
    $stmt->execute([$_SESSION['user']]);
    $row = $stmt->fetch();
    if ($row) {
        $_SESSION['user_id'] = $user_id = $row['id'];
    } else {
        die("Session error. Please log in again.");
    }
}
$profession = $_SESSION['profession'];
$message = '';
$messageType = '';

// Create Event (Staff only)
if ($profession === 'Staff' && $_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['create_event'])) {
    $name = trim($_POST['event_name']);
    $date_time = $_POST['date_time'];
    $place = trim($_POST['place']);
    $places = (int)$_POST['available_places'];
    $desc = trim($_POST['description'] ?? '');

    if (empty($name) || empty($date_time) || empty($place) || $places < 1) {
        $message = "All fields required.";
        $messageType = 'error';
    } else {
        $pic = null;
        if (!empty($_FILES['picture']['name']) && $_FILES['picture']['error'] == 0) {
            $dir = 'uploads/events/';
            if (!is_dir($dir)) mkdir($dir, 0755, true);
            $ext = pathinfo($_FILES['picture']['name'], PATHINFO_EXTENSION);
            $pic = $dir . uniqid() . '.' . $ext;
            move_uploaded_file($_FILES['picture']['tmp_name'], $pic);
        }

        try {
            $stmt = $pdo->prepare("INSERT INTO events (name, picture, date_time, place, available_places, created_by, description) VALUES (?, ?, ?, ?, ?, ?, ?)");
            $stmt->execute([$name, $pic, $date_time, $place, $places, $user_id, $desc]);
            $message = "Event created!";
            $messageType = 'success';
        } catch (Exception $e) {
            $message = "Error: " . $e->getMessage();
            $messageType = 'error';
        }
    }
}

// Reserve Spot
if (isset($_POST['reserve_event'])) {
    $event_id = (int)$_POST['event_id'];
    try {
        $pdo->beginTransaction();
        $stmt = $pdo->prepare("SELECT available_places FROM events WHERE id = ? FOR UPDATE");
        $stmt->execute([$event_id]);
        $row = $stmt->fetch();
        if ($row && $row['available_places'] > 0) {
            $check = $pdo->prepare("SELECT 1 FROM reservations WHERE event_id = ? AND user_id = ?");
            $check->execute([$event_id, $user_id]);
            if ($check->fetch()) {
                $pdo->rollBack();
                $message = "Already reserved!";
                $messageType = 'error';
            } else {
                $pdo->prepare("INSERT INTO reservations (event_id, user_id) VALUES (?, ?)")->execute([$event_id, $user_id]);
                $pdo->prepare("UPDATE events SET available_places = available_places - 1 WHERE id = ?")->execute([$event_id]);
                $pdo->commit();
                $message = "Reserved!";
                $messageType = 'success';
            }
        } else {
            $pdo->rollBack();
            $message = "No spots left.";
            $messageType = 'error';
        }
    } catch (Exception $e) {
        $pdo->rollBack();
        $message = "Error: " . $e->getMessage();
        $messageType = 'error';
    }
}

// Unreserve Spot
if (isset($_POST['unreserve_event'])) {
    $event_id = (int)$_POST['event_id'];
    try {
        $pdo->beginTransaction();
        $check = $pdo->prepare("SELECT 1 FROM reservations WHERE event_id = ? AND user_id = ?");
        $check->execute([$event_id, $user_id]);
        if ($check->fetch()) {
            $pdo->prepare("DELETE FROM reservations WHERE event_id = ? AND user_id = ?")->execute([$event_id, $user_id]);
            $pdo->prepare("UPDATE events SET available_places = available_places + 1 WHERE id = ?")->execute([$event_id]);
            $pdo->commit();
            $message = "Unreserved! Spot freed up.";
            $messageType = 'success';
        } else {
            $pdo->rollBack();
            $message = "No reservation found.";
            $messageType = 'error';
        }
    } catch (Exception $e) {
        $pdo->rollBack();
        $message = "Error: " . $e->getMessage();
        $messageType = 'error';
    }
}

// Load events
$events = $pdo->query("SELECT e.*, u.name AS creator, e.id AS event_id 
                       FROM events e 
                       JOIN users u ON e.created_by = u.id 
                       ORDER BY date_time ASC");

// Load my reservations
$res = $pdo->prepare("SELECT event_id FROM reservations WHERE user_id = ?");
$res->execute([$user_id]);
$my_reservations = $res->fetchAll(PDO::FETCH_COLUMN, 0);
?>

