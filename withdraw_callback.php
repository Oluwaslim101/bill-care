<?php

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Include database connection
include('db.php');

// Get the raw POST data
$payload = file_get_contents("php://input");

// Decode JSON payload
$data = json_decode($payload, true);

// Verify Flutterwave Signature (for security)
$secretHash = "Lotanna@2024"; // Set this in Flutterwave dashboard
$headers = getallheaders();

if (!isset($headers['verif-hash']) || $headers['verif-hash'] !== $secretHash) {
    die("Invalid Webhook Signature");
}

// Check if the event is a transfer completion
if (isset($data['event']) && $data['event'] == "transfer.completed") {
    $tx_ref = $data['data']['reference']; // Transaction reference
    $status = $data['data']['status']; // Transaction status (e.g., successful, failed)

    // If transaction was successful, update status in database
    if ($status == "SUCCESS") {
        $stmt = $sql->prepare("UPDATE transactions SET status = 'success' WHERE transaction_ref = ?");
        $stmt->execute([$tx_ref]);
    }
}

http_response_code(200); // Respond to Flutterwave
?>