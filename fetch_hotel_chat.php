<?php
// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
require "db.php";

header('Content-Type: application/json');

$hotel_id = intval($_GET['hotel_id'] ?? 0);
$user_id = intval($_GET['user_id'] ?? 0);
$last_id = intval($_GET['last_id'] ?? 0);

if (!$hotel_id || !$user_id) {
    echo json_encode(["messages" => []]);
    exit();
}

// Fetch messages
if ($last_id > 0) {
    // Only fetch new messages
    $stmt = $sql->prepare("
        SELECT id, sender, message, created_at
        FROM hotel_chat_messages
        WHERE hotel_id = ? AND user_id = ? AND id > ?
        ORDER BY id ASC
    ");
    $stmt->execute([$hotel_id, $user_id, $last_id]);
} else {
    // Fetch all messages
    $stmt = $sql->prepare("
        SELECT id, sender, message, created_at
        FROM hotel_chat_messages
        WHERE hotel_id = ? AND user_id = ?
        ORDER BY id ASC
    ");
    $stmt->execute([$hotel_id, $user_id]);
}

$messages = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Format time nicely
foreach ($messages as &$msg) {
    $msg['time'] = date("h:i A", strtotime($msg['created_at']));
}

echo json_encode(["messages" => $messages]);
?>
