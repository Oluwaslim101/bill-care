<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
header('Content-Type: application/json');

include('db.php');

$userId = $_POST['user_id'] ?? null;
$amount = floatval($_POST['amount'] ?? 0);
$duration = intval($_POST['duration_days'] ?? 0);
$method = $_POST['funding_method'] ?? '';

if (!$userId || !$amount || !$duration || !$method) {
    echo json_encode(['status' => 'error', 'message' => 'Missing required fields.']);
    exit;
}

$rate = match ($duration) {
    30 => 3,
    60 => 6,
    90 => 10,
    default => 0
};

$start = date('Y-m-d H:i:s');
$end = date('Y-m-d H:i:s', strtotime("+$duration days"));

if ($method === 'user_wallet') {
    // Check wallet
    $stmt = $sql->prepare("SELECT balance FROM users WHERE id = ?");
    $stmt->execute([$userId]);
    $user_wallet = $stmt->fetchColumn();

    if ($user_wallet >= $amount) {
        try {
            $sql->beginTransaction();

            // Deduct balance
            $deduct = $sql->prepare("UPDATE users SET balance = balance - ? WHERE id = ?");
            $deduct->execute([$amount, $userId]);

            // Insert savings
            $save = $sql->prepare("INSERT INTO fixed_savings (user_id, amount, duration_days, interest_rate, start_date, end_date, status, funding_method) VALUES (?, ?, ?, ?, ?, ?, 'active', 'wallet')");
            $save->execute([$userId, $amount, $duration, $rate, $start, $end]);

            // Notification
            $note = $sql->prepare("INSERT INTO notifications (user_id, message, action_type) VALUES (?, ?, 'savings')");
            $note->execute([$userId, "₦" . number_format($amount) . " locked in Fixed Savings for $duration days."]);

            $sql->commit();

            echo json_encode([
                'status' => 'success',
                'message' => "Savings created successfully!",
                'receipt' => generateReceipt($userId, $amount, $duration, $rate, $start, $end)
            ]);
        } catch (Exception $e) {
            $sql->rollBack();
            echo json_encode(['status' => 'error', 'message' => 'Transaction failed: ' . $e->getMessage()]);
        }
    } else {
        echo json_encode(['status' => 'error', 'message' => "Insufficient wallet balance."]);
    }
} else {
    // Paystack Option
    $ref = uniqid("SAV_");
    $meta = base64_encode(json_encode([
        'user_id' => $userId,
        'amount' => $amount,
        'duration_days' => $duration,
        'interest_rate' => $rate,
        'ref' => $ref
    ]));
    $redirect = "paystack_checkout.php?meta=$meta";
    echo json_encode(['status' => 'redirect', 'redirect' => $redirect]);
}

function generateReceipt($uid, $amt, $dur, $rate, $start, $end) {
    $interest = $amt * ($rate / 100);
    $total = $amt + $interest;
    return "
==============================
       FIXED SAVINGS RECEIPT
==============================
User ID: $uid
Start Date: $start
End Date: $end
Duration: $dur days
Interest Rate: $rate%
Principal: ₦" . number_format($amt, 2) . "
Interest Earned: ₦" . number_format($interest, 2) . "
------------------------------
Expected Payout: ₦" . number_format($total, 2) . "
==============================
Status: ACTIVE
==============================";
}
?>
