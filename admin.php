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
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AUI Security - Admin Panel</title>
    <style>
        :root { --blue: #3b82f6; --red: #ef4444; --green: #10b981; --gray: #6b7280; }
        * { margin:0; padding:0; box-sizing:border-box; font-family:system-ui,-apple-system,sans-serif; }
        body { background:#f0f8ff; color:#333; min-height:100vh; }
        .header { display:flex; justify-content:space-between; align-items:center; padding:1rem 2rem; background:rgba(255,255,255,0.9); backdrop-filter:blur(10px); border-bottom:1px solid #eee; position:sticky; top:0; z-index:100; }
        .logo { font-size:1.25rem; font-weight:600; color:var(--blue); }
        .user { display:flex; align-items:center; gap:12px; }
        .avatar { width:40px; height:40px; background:var(--blue); color:white; border-radius:50%; display:grid; place-items:center; font-weight:600; }
        .logout { background:var(--red); color:white; border:none; padding:8px 16px; border-radius:8px; cursor:pointer; font-weight:500; }
        .container { max-width:1200px; margin:2rem auto; padding:0 1rem; }
        .message { padding:1rem; border-radius:12px; margin-bottom:1.5rem; text-align:center; font-weight:500; }
        .success { background:#dcfce7; color:#166534; border:1px solid #22c55e; }
        .error { background:#fee2e2; color:#991b1b; border:1px solid #ef4444; }
        .stats { display:grid; gap:1rem; grid-template-columns:repeat(auto-fit, minmax(200px,1fr)); margin-bottom:2rem; }
        .stat-card { background:white; padding:1.5rem; border-radius:12px; text-align:center; box-shadow:0 2px 10px rgba(0,0,0,0.05); }
        .stat-num { font-size:2rem; font-weight:700; color:var(--blue); }
        .section { background:white; border-radius:16px; padding:2rem; margin-bottom:2rem; box-shadow:0 4px 20px rgba(0,0,0,0.05); overflow-x:auto; }
        h2 { font-size:1.5rem; color:#1e40af; margin-bottom:1rem; }
        table { width:100%; border-collapse:collapse; }
        th, td { padding:0.75rem; text-align:left; border-bottom:1px solid #eee; }
        th { background:var(--blue); color:white; font-weight:600; }
        .category-filter { margin-bottom:1rem; }
        select { padding:0.5rem; border:1px solid #d1d5db; border-radius:6px; }
        .btn-delete { background:var(--red); color:white; border:none; padding:0.5rem 1rem; border-radius:6px; cursor:pointer; font-size:0.85rem; }
        .btn-delete:hover { background:#dc2626; }
        .logs { font-size:0.85rem; color:var(--gray); }
        @media (max-width:640px) { .stats { grid-template-columns:1fr; } table { font-size:0.85rem; } }
    </style>
</head>
<body>
    <div class="header">
        <div class="logo">AUI Security Panel</div>
        <div class="user">
            <div class="avatar">A</div>
            <div><?php echo htmlspecialchars($_SESSION['user_name']); ?> (Admin)</div>
            <button class="logout" onclick="location='dashboard.php'">Back to Dashboard</button>
            <button class="logout" onclick="location='logout.php'">Logout</button>
        </div>
    </div>

    <div class="container">
        <?php if ($message): ?>
            <div class="message <?php echo $messageType; ?>"><?php echo htmlspecialchars($message); ?></div>
        <?php endif; ?>

        <!-- Stats -->
        <div class="stats">
            <div class="stat-card">
                <div class="stat-num"><?php echo $total_users; ?></div>
                <div>Total Users</div>
            </div>
            <div class="stat-card">
                <div class="stat-num"><?php echo $total_events; ?></div>
                <div>Total Events</div>
            </div>
            <div class="stat-card">
                <div class="stat-num"><?php echo $total_reservations; ?></div>
                <div>Total Reservations</div>
            </div>
        </div>

        <!-- Users Section -->
        <div class="section">
            <h2>Users by Category</h2>
            <div class="category-filter">
                <label>Filter by Category: </label>
                <select onchange="location = '?category=' + this.value;">
                    <option value="">All</option>
                    <option value="Student">Students</option>
                    <option value="Teacher">Teachers</option>
                    <option value="Staff">Staff/Admins</option>
                </select>
            </div>
            <table>
                <thead><tr><th>ID</th><th>Name</th><th>Email</th><th>CasWallet ID</th><th>Category</th><th>Joined</th><th>Actions</th></tr></thead>
                <tbody>
                    <?php foreach ($users as $u): ?>
                        <tr>
                            <td><?php echo $u['id']; ?></td>
                            <td><?php echo htmlspecialchars($u['name']); ?></td>
                            <td><?php echo htmlspecialchars($u['email']); ?></td>
                            <td><?php echo htmlspecialchars($u['caswallet_id']); ?></td>
                            <td><span style="color:<?php echo $u['profession'] === 'Student' ? '#3b82f6' : ($u['profession'] === 'Teacher' ? '#10b981' : '#ef4444'); ?>; font-weight:600;"><?php echo $u['profession']; ?></span></td>
                            <td><?php echo date('M j, Y', strtotime($u['created_at'])); ?></td>
                            <td>
                                <form method="POST" style="display:inline;" onsubmit="return confirm('Delete user <?php echo $u['name']; ?>?');">
                                    <input type="hidden" name="user_id" value="<?php echo $u['id']; ?>">
                                    <button type="submit" name="delete_user" class="btn-delete">Delete</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <!-- Events Section -->
        <div class="section">
            <h2>All Events</h2>
            <table>
                <thead><tr><th>ID</th><th>Name</th><th>Creator</th><th>Date/Time</th><th>Place</th><th>Spots Left</th><th>Created</th><th>Actions</th></tr></thead>
                <tbody>
                    <?php foreach ($events as $e): ?>
                        <tr>
                            <td><?php echo $e['id']; ?></td>
                            <td><?php echo htmlspecialchars($e['name']); ?></td>
                            <td><?php echo htmlspecialchars($e['creator']); ?></td>
                            <td><?php echo date('M j, Y g:i A', strtotime($e['date_time'])); ?></td>
                            <td><?php echo htmlspecialchars($e['place']); ?></td>
                            <td><?php echo $e['available_places']; ?></td>
                            <td><?php echo date('M j, Y', strtotime($e['created_at'])); ?></td>
                            <td>
                                <form method="POST" style="display:inline;" onsubmit="return confirm('Delete event <?php echo $e['name']; ?>?');">
                                    <input type="hidden" name="event_id" value="<?php echo $e['id']; ?>">
                                    <button type="submit" name="delete_event" class="btn-delete">Delete</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <!-- Reservations Section -->
        <div class="section">
            <h2>All Reservations</h2>
            <table>
                <thead><tr><th>ID</th><th>User</th><th>Event</th><th>Reserved At</th></tr></thead>
                <tbody>
                    <?php foreach ($reservations as $r): ?>
                        <tr>
                            <td><?php echo $r['id']; ?></td>
                            <td><?php echo htmlspecialchars($r['user_name']); ?></td>
                            <td><?php echo htmlspecialchars($r['event_name']); ?></td>
                            <td><?php echo date('M j, Y g:i A', strtotime($r['reserved_at'])); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <!-- Logs Section -->
        <div class="section">
            <h2>Admin Logs (Last 50 Actions)</h2>
            <table>
                <thead><tr><th>Action</th><th>Target ID</th><th>Admin</th><th>Timestamp</th></tr></thead>
                <tbody>
                    <?php foreach ($logs as $log): ?>
                        <tr class="logs">
                            <td><?php echo htmlspecialchars($log['action']); ?></td>
                            <td><?php echo $log['target_id']; ?></td>
                            <td><?php echo htmlspecialchars($log['admin_name']); ?></td>
                            <td><?php echo date('M j, Y g:i A', strtotime($log['timestamp'])); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>