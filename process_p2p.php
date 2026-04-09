<?php
session_start();
require 'db.php';

header('Content-Type: application/json');

$sender_id   = $_SESSION['user_id'] ?? null;
$receiver_id = $_POST['receiver_id'] ?? null;
$amount      = floatval($_POST['amount'] ?? 0);
$pin         = $_POST['pin'] ?? '';

if (!$sender_id || !$receiver_id || !$amount || !$pin) {
    echo json_encode(['success' => false, 'message' => 'Missing required data']);
    exit;
}

if ($sender_id == $receiver_id) {
    echo json_encode(['success' => false, 'message' => 'You cannot send money to yourself.']);
    exit;
}

try {
    // Verify sender and pin
    $stmt = $sql->prepare("SELECT full_name, balance FROM users WHERE id = ? AND pin = ?");
    $stmt->execute([$sender_id, $pin]);
    $sender = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$sender) {
        echo json_encode(['success' => false, 'message' => 'Invalid PIN']);
        exit;
    }

    if ($sender['balance'] < $amount) {
        echo json_encode(['success' => false, 'message' => 'Insufficient balance']);
        exit;
    }

    // Get receiver info
    $stmt = $sql->prepare("SELECT full_name FROM users WHERE id = ?");
    $stmt->execute([$receiver_id]);
    $receiver = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$receiver) {
        echo json_encode(['success' => false, 'message' => 'Receiver not found']);
        exit;
    }

    $reference = 'P2P' . strtoupper(uniqid());

    $sql->beginTransaction();

    // Debit sender
    $sql->prepare("UPDATE users SET balance = balance - ? WHERE id = ?")
        ->execute([$amount, $sender_id]);

    // Credit receiver
    $sql->prepare("UPDATE users SET balance = balance + ? WHERE id = ?")
        ->execute([$amount, $receiver_id]);

    // Log transaction
    $sql->prepare("INSERT INTO p2p (sender, receiver, amount, reference, created_at)
                   VALUES (?, ?, ?, ?, NOW())")
        ->execute([$sender_id, $receiver_id, $amount, $reference]);

    // Notify sender
    $sql->prepare("INSERT INTO notifications (user_id, action_type, message, status, created_at)
                   VALUES (?, ?, ?, ?, NOW())")
        ->execute([
            $sender_id,
            'p2p_sent',
            "You sent ₦" . number_format($amount, 2) . " to " . $receiver['full_name'],
            'unread'
        ]);

    // Notify receiver
    $sql->prepare("INSERT INTO notifications (user_id, action_type, message, status, created_at)
                   VALUES (?, ?, ?, ?, NOW())")
        ->execute([
            $receiver_id,
            'p2p_received',
            "You received ₦" . number_format($amount, 2) . " from " . $sender['full_name'],
            'unread'
        ]);

    $sql->commit();

    echo json_encode(['success' => true, 'message' => 'Transfer successful!', 'reference' => $reference]);

} catch (Exception $e) {
    $sql->rollBack();
    echo json_encode(['success' => false, 'message' => 'Transaction failed: ' . $e->getMessage()]);
}
