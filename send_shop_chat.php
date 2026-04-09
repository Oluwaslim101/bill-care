<?php
// Error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
include 'db.php';
header('Content-Type: application/json');

$userId = $_SESSION['user_id'] ?? 0;
$shopId = intval($_POST['shop_id'] ?? 0);
$message = trim($_POST['message'] ?? '');

if (!$shopId || !$userId || !$message) {
    echo json_encode(['success' => false, 'error' => 'Invalid input.']);
    exit;
}

try {
    $stmt = $sql->prepare("INSERT INTO shop_chats (shop_id, customer_id, sender, message, is_read) 
                           VALUES (:shop_id, :customer_id, 'customer', :message, 0)");

    $stmt->execute([
        ':shop_id' => $shopId,
        ':customer_id' => $userId,
        ':message' => $message
    ]);

    echo json_encode(['success' => true]);
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}