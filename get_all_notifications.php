<?php
// get_all_notifications.php

// Include the database connection
include('db.php');
session_start();

if (!isset($_SESSION['user_id']) || empty($_SESSION['user_id'])) {
    echo json_encode(['notifications' => []]); // No user session
    exit();
}

$user_id = $_SESSION['user_id'];

// Query to fetch all notifications
$query = "SELECT * FROM notifications WHERE user_id = ? ORDER BY created_at DESC";
$stmt = $sql->prepare($query);
$stmt->execute([$user_id]);
$notifications = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Return notifications as JSON
echo json_encode(['notifications' => $notifications]);
?>
