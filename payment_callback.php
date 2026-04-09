<?php
require 'db.php';
header("Content-Type: application/json");

// Secure Flutterwave API Key (DO NOT EXPOSE IN PRODUCTION)
$FLW_SECRET_KEY = "FLWSECK-e64fa04c69f8cc3af508862b529669cc-19742b447b3vt-X";

// Get and log Flutterwave response
$payload = file_get_contents("php://input");
file_put_contents("flutterwave_webhook_log.txt", $payload . PHP_EOL, FILE_APPEND);

$data = json_decode($payload, true);
file_put_contents("debug_log.txt", print_r($data, true), FILE_APPEND);

// Extract necessary transaction details
$transaction_ref = $data['data']['tx_ref'] ?? null;  // Standardized key
$transaction_id = $data['data']['id'] ?? null;
$payment_status = strtolower($data['data']['status'] ?? '');
$amount_paid = $data['data']['amount'] ?? 0;

// Validate transaction reference
if (!$transaction_ref || !$transaction_id || !$payment_status) {
    die(json_encode(["status" => "error", "message" => "Invalid transaction data received."]));
}

// Check if the payment is successful
if ($payment_status !== "successful") {
    die(json_encode(["status" => "error", "message" => "Transaction was not successful."]));
}

// Fetch user_id from transactions
$stmt = $sql->prepare("SELECT user_id FROM transactions WHERE transaction_ref = ?");
$stmt->execute([$transaction_ref]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user) {
    die(json_encode(["status" => "error", "message" => "Transaction reference not found."]));
}

$user_id = $user['user_id'];

// Get current wallet balance
$stmt = $sql->prepare("SELECT balance FROM users WHERE id = ?");
$stmt->execute([$user_id]);
$user_wallet = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user_wallet) {
    die(json_encode(["status" => "error", "message" => "User wallet not found."]));
}

$current_balance = $user_wallet['balance'];
$new_balance = $current_balance + $amount_paid;

// Begin database transaction
$sql->beginTransaction();

try {
    // Update transaction status
    $update = $sql->prepare("UPDATE transactions SET status = 'successful' WHERE transaction_ref = ?");
    $update->execute([$transaction_ref]);

    // Add funds to user's balance
    $updateBalance = $sql->prepare("UPDATE users SET balance = ? WHERE id = ?");
    $updateBalance->execute([$new_balance, $user_id]);

    // Commit transaction
    $sql->commit();

    echo json_encode(["status" => "success", "message" => "Payment verified and balance updated."]);
    exit;
} catch (Exception $e) {
    $sql->rollBack();
    file_put_contents("error_log.txt", $e->getMessage() . PHP_EOL, FILE_APPEND);
    die(json_encode(["status" => "error", "message" => "Database update failed."]));
}
?>