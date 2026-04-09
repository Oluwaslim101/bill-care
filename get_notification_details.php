<?php
session_start();
require_once 'db.php';
header('Content-Type: application/json');

$user_id = $_SESSION['user_id'] ?? null;
$notif_id = $_GET['id'] ?? null;
$type = $_GET['type'] ?? '';

if (!$user_id || !$notif_id) {
    echo json_encode(['success' => false]);
    exit;
}

// You can switch by type if needed
$stmt = $sql->prepare("SELECT * FROM notifications WHERE id = ? AND user_id = ?");
$stmt->execute([$notif_id, $user_id]);
$notif = $stmt->fetch(PDO::FETCH_ASSOC);

if ($notif) {
    echo json_encode([
        'success' => true,
        'title' => ucfirst($notif['action_type']) . " Notification",
        'message' => $notif['message'],
        'created_at' => $notif['created_at']
    ]);
} else {
    echo json_encode(['success' => false]);
}
