<?php
// dispute_logger.php
session_start();
require_once 'db.php';
header('Content-Type: application/json');

$user_id = $_SESSION['user_id'] ?? null;

// If GET request: Check dispute status
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $reference = $_GET['reference'] ?? '';
    $reference = htmlspecialchars(strip_tags(trim($reference)));

    if (!$user_id || empty($reference)) {
        echo json_encode(['status' => 'none']);
        exit;
    }

    $stmt = $sql->prepare("SELECT status FROM transaction_disputes WHERE user_id = ? AND reference = ? ORDER BY created_at DESC LIMIT 1");
    $stmt->execute([$user_id, $reference]);
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    echo json_encode(['status' => $result['status'] ?? 'none']);
    exit;
}

// If POST request: Log new dispute
$reference = $_POST['reference'] ?? '';
$reason = $_POST['reason'] ?? '';
$session_id = $_POST['session_id'] ?? '';

// Sanitize inputs
$reference = htmlspecialchars(strip_tags(trim($reference)));
$reason = htmlspecialchars(strip_tags(trim($reason)));
$session_id = htmlspecialchars(strip_tags(trim($session_id)));

if (!$user_id || empty($reference) || empty($reason)) {
    echo json_encode([
        'success' => false,
        'message' => 'Invalid submission. Please provide all required information.'
    ]);
    exit;
}

try {
    // Check for existing unresolved dispute
    $check = $sql->prepare("SELECT id FROM transaction_disputes WHERE user_id = ? AND reference = ? AND status = 'pending'");
    $check->execute([$user_id, $reference]);

    if ($check->rowCount() > 0) {
        echo json_encode([
            'success' => false,
            'message' => 'You have already initiated a dispute for this transaction. Please wait for feedback.'
        ]);
        exit;
    }

    // Insert new dispute
    $stmt = $sql->prepare("INSERT INTO transaction_disputes (user_id, reference, reason, session_id, status, created_at) VALUES (?, ?, ?, ?, 'pending', NOW())");
    $logged = $stmt->execute([$user_id, $reference, $reason, $session_id]);

    echo json_encode([
        'success' => $logged,
        'message' => $logged ? 'Dispute logged successfully.' : 'Failed to log dispute.'
    ]);
} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'message' => 'Server error: ' . $e->getMessage()
    ]);
}
