<?php
require_once 'config.php';
// Temp debug: Enable error display
error_reporting(E_ALL);
ini_set('display_errors', 1);
// Handle form submission
$message = '';
$messageType = ''; // Initialize to prevent undefined variable
$debug = ''; // Temp debug output
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $full_name = trim($_POST['full_name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $caswallet_id = trim($_POST['caswallet_id'] ?? '');
    $password = trim($_POST['password'] ?? '');
    $profession = trim($_POST['profession'] ?? '');
   
    $debug .= "<p><strong>Debug Info:</strong><br>";
    $debug .= "Full Name: '$full_name'<br>";
    $debug .= "Email: '$email'<br>";
    $debug .= "CasWallet ID: '$caswallet_id'<br>";
    $debug .= "Password length: " . strlen($password) . "<br>";
    $debug .= "Profession: '$profession'<br>";
   
    if (empty($full_name) || empty($email) || empty($caswallet_id) || empty($password) || empty($profession)) {
        $message = 'Please fill in all fields.';
        $messageType = 'error';
        $debug .= "Error: Empty fields.</p>";
    } elseif (strlen($password) < 6) {
        $message = 'Password must be at least 6 characters long.';
        $messageType = 'error';
        $debug .= "Error: Password too short.</p>";
    } else {
        // Check if caswallet_id already exists
        try {
            $check_stmt = $pdo->prepare("SELECT id FROM users WHERE caswallet_id = ?");
            $check_stmt->execute([$caswallet_id]);
            if ($check_stmt->fetch()) {
                $message = 'CasWallet ID already exists. Please choose another.';
                $messageType = 'error';
                $debug .= "Error: CasWallet ID exists.</p>";
            } else {
                $hashed_password = password_hash($password, PASSWORD_DEFAULT);
                $debug .= "Hashed password (first 20 chars): " . substr($hashed_password, 0, 20) . "...<br>";
                
                $stmt = $pdo->prepare("INSERT INTO users (name, email, caswallet_id, password, profession) VALUES (?, ?, ?, ?, ?)");
                $result = $stmt->execute([$full_name, $email, $caswallet_id, $hashed_password, $profession]);
               
                $debug .= "Insert executed. Result: " . ($result ? 'SUCCESS' : 'FAILED') . "</p>";
               
                if ($result) {
                    $message = 'Account created successfully! You can now sign in.';
                    $messageType = 'success';
                } else {
                    $message = 'Error creating account. Please try again.';
                    $messageType = 'error';
                }
            }
        } catch (PDOException $e) {
            $debug .= "DB Error: " . $e->getMessage() . "</p>";
            $message = 'Database error occurred.';
            $messageType = 'error';
        }
    }
}
?>