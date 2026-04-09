<?php
session_start();
require_once 'db.php';
header('Content-Type: application/json');

$user_id = $_SESSION['user_id'] ?? null;
$inputPin = $_POST['pin'] ?? '';

if (!$user_id || !$inputPin) {
    echo json_encode(['success' => false, 'message' => 'Unauthorized or invalid request']);
    exit;
}

// Fetch user info
$stmt = $sql->prepare("SELECT pin, failed_pin_attempts, last_pin_attempt, failed_face_attempts, withdrawal_locked FROM users WHERE id = ?");
$stmt->execute([$user_id]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user) {
    echo json_encode(['success' => false, 'message' => 'User not found']);
    exit;
}

// 🔒 If permanently locked
if ($user['withdrawal_locked']) {
    echo json_encode([
        'success' => false,
        'locked' => true,
        'message' => 'Your Withdrawal Action has been locked out due to failed Authentication. Please contact Support Team for resolution.'
    ]);
    exit;
}

// ⏳ Handle 30min cooldown lock
$now = new DateTime();
$lastAttempt = $user['last_pin_attempt'] ? new DateTime($user['last_pin_attempt']) : null;

if ($user['failed_pin_attempts'] >= 4) {
    if ($lastAttempt && $now->getTimestamp() - $lastAttempt->getTimestamp() < 1800) {
        // Still in cooldown
        $retryAfter = 1800 - ($now->getTimestamp() - $lastAttempt->getTimestamp());
        echo json_encode([
            'success' => false,
            'locked' => true,
            'retry_after' => $retryAfter,
            'message' => 'Too many failed attempts. Try again in ' . ceil($retryAfter / 60) . ' minutes.'
        ]);
        exit;
    } else {
        // Cooldown expired: reset PIN attempts, but track this as a full failed cycle
        $newFaceAttempts = $user['failed_face_attempts'] + 1;

        // Lock account after 3 failed face retries
        if ($newFaceAttempts >= 3) {
            $sql->prepare("UPDATE users SET withdrawal_locked = 1 WHERE id = ?")->execute([$user_id]);
            echo json_encode([
                'success' => false,
                'locked' => true,
                'message' => 'Your Withdrawal Action has been locked out due to failed Authentication. Please contact Support Team for resolution.'
            ]);
            exit;
        }

        // Reset PIN attempts and update failed face attempt
        $sql->prepare("UPDATE users SET failed_pin_attempts = 0, failed_face_attempts = ? WHERE id = ?")
            ->execute([$newFaceAttempts, $user_id]);

        // Inform frontend to trigger face verification again
        echo json_encode([
            'success' => false,
            'face_required' => true,
            'message' => 'Too many failed attempts. Please verify your face to continue.'
        ]);
        exit;
    }
}

// ✅ Correct PIN
if ($inputPin === $user['pin']) {
    $sql->prepare("UPDATE users SET failed_pin_attempts = 0, failed_face_attempts = 0 WHERE id = ?")->execute([$user_id]);
    echo json_encode(['success' => true]);
    exit;
}

// ❌ Incorrect PIN
$newAttempts = $user['failed_pin_attempts'] + 1;
$sql->prepare("UPDATE users SET failed_pin_attempts = ?, last_pin_attempt = NOW() WHERE id = ?")
    ->execute([$newAttempts, $user_id]);

$response = [
    'success' => false,
    'message' => "Incorrect PIN. Attempts left: " . max(0, 4 - $newAttempts)
];

// After 4 wrong tries, signal face verification next
if ($newAttempts >= 4) {
    $response['face_required'] = true;
    $response['message'] = "Too many failed attempts. Please verify your face.";
}

echo json_encode($response);
