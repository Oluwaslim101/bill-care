<?php
// get_unread_notifications.php

// Include the database connection
include('db.php');
session_start();

if (!isset($_SESSION['user_id']) || empty($_SESSION['user_id'])) {
    echo json_encode(['unread_count' => 0, 'notifications' => []]); // No user session
    exit();
}

$user_id = $_SESSION['user_id'];

// Query to fetch unread notifications
$query = "SELECT id, message, created_at FROM notifications WHERE user_id = ? AND status = 'unread' ORDER BY created_at DESC";
$stmt = $sql->prepare($query);
$stmt->execute([$user_id]);
$notifications = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Query to fetch unread notifications count
$countQuery = "SELECT COUNT(*) FROM notifications WHERE user_id = ? AND status = 'unread'";
$countStmt = $sql->prepare($countQuery);
$countStmt->execute([$user_id]);
$unread_count = $countStmt->fetchColumn();

// Return the unread notification count and messages as JSON
echo json_encode(['unread_count' => $unread_count, 'notifications' => $notifications]);
?>
