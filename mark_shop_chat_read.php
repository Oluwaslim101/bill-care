<?php
session_start();
include 'db.php';

header('Content-Type: application/json');

$userId = $_SESSION['user_id'] ?? 0;
$shopId = intval($_POST['shop_id'] ?? 0);

if (!$userId || !$shopId) {
    echo json_encode(['success' => false, 'error' => 'Missing IDs']);
    exit;
}

// Mark shop owner messages as read
$stmt = $sql->prepare("UPDATE shop_chats SET is_read = 1 
                       WHERE shop_id = ? AND customer_id = ? AND sender = 'Shop Owner' AND is_read = 0");
$stmt->execute([$shopId, $userId]);

echo json_encode(['success' => true]);