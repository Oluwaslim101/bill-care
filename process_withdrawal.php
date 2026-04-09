<?php
error_reporting(E_ALL);
ini_set('display_errors', 0);
session_start();
require_once 'db.php';

$user_id = $_SESSION['user_id'] ?? null;
if (!$user_id) {
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit;
}

$required = ['withdraw_bank_name', 'withdraw_bank_code', 'withdraw_account_number', 'withdraw_amount', 'pin'];
foreach ($required as $field) {
    if (empty($_POST[$field])) {
        echo json_encode(['success' => false, 'message' => "Missing $field"]);
        exit;
    }
}

$bank_name = trim($_POST['withdraw_bank_name']);
$bank_code = trim($_POST['withdraw_bank_code']);
$account_number = trim($_POST['withdraw_account_number']);
$amount = (float) $_POST['withdraw_amount'];
$pin = $_POST['pin'];

if ($amount <= 0) {
    echo json_encode(['success' => false, 'message' => 'Invalid withdrawal amount']);
    exit;
}

// Track PIN attempts
$_SESSION['withdraw_pin_attempts'] = $_SESSION['withdraw_pin_attempts'] ?? 0;
if ($_SESSION['withdraw_pin_attempts'] >= 4) {
    echo json_encode(['success' => false, 'message' => 'Too many incorrect PIN attempts. Please verify with facial ID.']);
    exit;
}

// Fetch user
$stmt = $sql->prepare("SELECT full_name, balance, pin FROM users WHERE id = ?");
$stmt->execute([$user_id]);
$user = $stmt->fetch();

if (!$user || $pin !== $user['pin']) {
    $_SESSION['withdraw_pin_attempts'] += 1;
    echo json_encode(['success' => false, 'message' => 'Invalid PIN']);
    exit;
}

// Reset on correct PIN
$_SESSION['withdraw_pin_attempts'] = 0;

$reference = uniqid("WD_");
$status = 'DECLINED_INSUFFICIENT_FUNDS';

// Check balance before proceeding
if ($user['balance'] < $amount) {
    // Log the failed attempt due to insufficient balance
    $stmt = $sql->prepare("INSERT INTO user_withdrawals 
        (user_id, reference, bank_name, account_number, amount, status, response)
        VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->execute([$user_id, $reference, $bank_name, $account_number, $amount, $status, json_encode(['message' => 'Insufficient balance'])]);

    echo json_encode(['success' => false, 'message' => 'Insufficient balance']);
    exit;
}

// Proceed with Paystack verification
$verify_url = "https://api.paystack.co/bank/resolve?account_number=$account_number&bank_code=$bank_code";
$ch = curl_init($verify_url);
curl_setopt_array($ch, [
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_HTTPHEADER => ["Authorization: Bearer sk_live_5dfd636941e51a27446ee4adcbeff427055bf827"]
]);
$response = curl_exec($ch);
$error = curl_error($ch);
curl_close($ch);

if ($error) {
    echo json_encode(['success' => false, 'message' => 'Paystack verification failed: ' . $error]);
    exit;
}

$data = json_decode($response, true);
if (!$data['status'] || empty($data['data']['account_name'])) {
    echo json_encode(['success' => false, 'message' => 'Invalid account details']);
    exit;
}

$resolved_name = $data['data']['account_name']; // just store it for later

// Create recipient on Paystack
$recipient_payload = json_encode([
    'type' => 'nuban',
    'name' => $user['full_name'],
    'account_number' => $account_number,
    'bank_code' => $bank_code,
    'currency' => 'NGN'
]);
$ch = curl_init("https://api.paystack.co/transferrecipient");
curl_setopt_array($ch, [
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_POST => true,
    CURLOPT_POSTFIELDS => $recipient_payload,
    CURLOPT_HTTPHEADER => [
        "Authorization: Bearer sk_live_5dfd636941e51a27446ee4adcbeff427055bf827",
        "Content-Type: application/json"
    ]
]);
$recipient_response = curl_exec($ch);
$error = curl_error($ch);
curl_close($ch);

if ($error) {
    echo json_encode(['success' => false, 'message' => 'Recipient creation failed: ' . $error]);
    exit;
}

$recipient_data = json_decode($recipient_response, true);
if (!$recipient_data['status']) {
    echo json_encode(['success' => false, 'message' => 'Recipient creation error: ' . $recipient_data['message']]);
    exit;
}

$recipient_code = $recipient_data['data']['recipient_code'];

// Initiate transfer
$transfer_payload = json_encode([
    'source' => 'balance',
    'amount' => $amount * 100,
    'recipient' => $recipient_code,
    'reason' => 'Wallet withdrawal',
    'reference' => $reference
]);
$ch = curl_init("https://api.paystack.co/transfer");
curl_setopt_array($ch, [
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_POST => true,
    CURLOPT_POSTFIELDS => $transfer_payload,
    CURLOPT_HTTPHEADER => [
        "Authorization: Bearer sk_live_5dfd636941e51a27446ee4adcbeff427055bf827",
        "Content-Type: application/json"
    ]
]);
$transfer_response = curl_exec($ch);
$error = curl_error($ch);
curl_close($ch);

$transfer_data = json_decode($transfer_response, true);
$status = $transfer_data['status'] ? 'SUCCESS' : 'FAILED';

// Save withdrawal and update balance if successful
try {
    $sql->beginTransaction();

    $stmt = $sql->prepare("INSERT INTO user_withdrawals 
        (user_id, reference, recipient_code, bank_name, account_number, account_name, amount, status, response)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->execute([
        $user_id, $reference, $recipient_code, $bank_name, $account_number, $data['data']['account_name'], $amount,
        $status, json_encode($transfer_data)
    ]);

    if ($status === 'SUCCESS') {
        $update = $sql->prepare("UPDATE users SET balance = balance - ? WHERE id = ?");
        $update->execute([$amount, $user_id]);

        // Insert notification
        $notif = $sql->prepare("INSERT INTO notifications (user_id, action_type, message, status, created_at)
            VALUES (?, ?, ?, ?, NOW())");
        $notif->execute([
            $user_id,
            'withdrawal',
            'Your transfer of ₦' . number_format($amount, 2) . ' is successful.',
            'unread'
        ]);
    }

    $sql->commit();

    echo json_encode([
        'success' => $status === 'SUCCESS',
        'message' => $status === 'SUCCESS' ? 'Withdrawal transfer successful' : 'Transfer failed: ' . ($transfer_data['message'] ?? 'Unknown error'),
        'reference' => $reference
    ]);

} catch (Exception $e) {
    $sql->rollBack();
    echo json_encode(['success' => false, 'message' => 'Transaction failed: ' . $e->getMessage()]);
}
