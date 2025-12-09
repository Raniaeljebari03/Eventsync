<?php
require_once 'config.php';
session_start();

$user_id = $_SESSION['user_id'];

$stmt = $pdo->prepare("
    INSERT INTO user_preferences (user_id, likes, dislikes, preferred_event_style, preferred_time)
    VALUES (?, ?, ?, ?, ?)
    ON DUPLICATE KEY UPDATE
        likes = VALUES(likes),
        dislikes = VALUES(dislikes),
        preferred_event_style = VALUES(preferred_event_style),
        preferred_time = VALUES(preferred_time)
");

$stmt->execute([
    $user_id,
    $_POST['likes'],
    $_POST['dislikes'],
    $_POST['preferred_event_style'],
    $_POST['preferred_time']
]);

header("Location: recommended.php");
exit;
