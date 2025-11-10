<?php
session_start();  // Always start session

// DB Config (update with your details)
define('DB_HOST', 'localhost');
define('DB_NAME', '');
define('DB_USER', '');  // Default XAMPP
define('DB_PASS', '');      // Default empty

try {
    $pdo = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME, DB_USER, DB_PASS);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("DB Connection failed: " . $e->getMessage());
}

// Function to check if logged in
function isLoggedIn() {
    return isset($_SESSION['user']);
}
function isAdmin() {
    global $pdo;
    if (!isset($_SESSION['user_id'])) return false;
    $stmt = $pdo->prepare("SELECT role FROM users WHERE id = ?");
    $stmt->execute([$_SESSION['user_id']]);
    return $stmt->fetchColumn() === 'admin';
}
?>
