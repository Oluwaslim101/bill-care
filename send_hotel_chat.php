<?php
session_start();
require "db.php"; // PDO connection
header('Content-Type: application/json');

// Get user_id from session and POST
$user_id = $_SESSION['user_id'] ?? intval($_POST['user_id'] ?? 0);
$hotel_id = intval($_POST['hotel_id'] ?? 0);
$message = trim($_POST['message'] ?? '');

if ($user_id <= 0 || $hotel_id <= 0 || $message === '') {
    echo json_encode(['status' => 'error', 'message' => 'Invalid input']);
    exit;
}

try {
    $stmt = $sql->prepare("
        INSERT INTO hotel_chat_messages (hotel_id, user_id, sender, message, status)
        VALUES (?, ?, 'customer', ?, 'sent')
    ");
    $stmt->execute([$hotel_id, $user_id, $message]);

    echo json_encode(['status' => 'success']);
} catch (PDOException $e) {
    echo json_encode(['status' => 'error', 'message' => 'DB Error: ' . $e->getMessage()]);
}
?>
