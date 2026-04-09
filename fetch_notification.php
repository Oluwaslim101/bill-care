<?php
// fetch_notifications.php
include 'db.php'; // Include database connection

// Assuming user_id is passed in the session or request
$user_id = $_SESSION['user_id'];  // Or get from request parameter

// Fetch all notifications for the user, sorted by created_at in descending order
$query = "SELECT * FROM notifications WHERE user_id = '$user_id' ORDER BY created_at DESC";
$result = mysqli_query($connection, $query);
$notifications = mysqli_fetch_all($result, MYSQLI_ASSOC);

// Mark notifications as read once fetched
$updateQuery = "UPDATE notifications SET status = 'read' WHERE user_id = '$user_id' AND status = 'unread'";
mysqli_query($connection, $updateQuery);

echo json_encode($notifications);
?>
