<?php
session_start();
include 'db.php';

header('Content-Type: application/json');

$userId = $_SESSION['user_id'] ?? 0;
$shopId = intval($_GET['shop_id'] ?? 0);

if (!$userId || !$shopId) {
    echo json_encode(['success' => false, 'error' => 'Missing ID']);
    exit;
}

// Fetch chat
$stmt = $sql->prepare("SELECT sender, message, created_at, is_read FROM shop_chats 
                       WHERE customer_id = ? AND shop_id = ? 
                       ORDER BY created_at ASC");
$stmt->execute([$userId, $shopId]);
$chats = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Return as JSON
echo json_encode(['success' => true, 'data' => $chats]);