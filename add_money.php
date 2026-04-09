<?php
// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Include database connection
include('db.php');
session_start();

// Ensure user is logged in
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['status' => 'error', 'message' => 'User not logged in']);
    exit();
}

$user_id = $_SESSION['user_id'];

// Fetch user details
$query = "SELECT * FROM users WHERE id = ?";
$stmt = $sql->prepare($query);
$stmt->execute([$user_id]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user) {
    echo json_encode(['status' => 'error', 'message' => 'User not found']);
    exit();
}

// Flutterwave API credentials
$FLW_SECRET_KEY = "FLWSECK-6f148fbb2fb4e625fa490bc9b3528647-1956425786evt-X"; // REVOKE AFTER TESTING
$CALLBACK_URL = "https://swiftaffiliates.cloud/payment_callback.php";

// Process deposit request
$data = json_decode(file_get_contents("php://input"), true);

if (!isset($data['amount']) || !is_numeric($data['amount']) || $data['amount'] <= 0) {
    echo json_encode(['status' => 'error', 'message' => 'Invalid amount']);
    exit();
}

$amount = $data['amount'];
$transaction_ref = "TX-" . uniqid(); // Generate unique transaction reference

// Prepare Flutterwave payment request
$payment_data = [
    "tx_ref" => $transaction_ref,
    "amount" => $amount,
    "currency" => "NGN",
    "redirect_url" => $CALLBACK_URL,
    "customer" => [
        "email" => $user['email'],
        "phonenumber" => $user['phone_number'],
        "name" => $user['full_name']
    ],
    "customizations" => [
        "title" => "Deposit Funds",
        "description" => "Adding funds to your wallet"
    ]
];

// Send request to Flutterwave API
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, "https://api.flutterwave.com/v3/payments");
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payment_data));
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    "Authorization: Bearer $FLW_SECRET_KEY",
    "Content-Type: application/json"
]);
$response = curl_exec($ch);
curl_close($ch);

$response_data = json_decode($response, true);

if ($response_data['status'] === "success") {
    // Log pending deposit transaction
    $sql->beginTransaction();

    try {
        $insert_query = "INSERT INTO transactions (user_id, type, amount, transaction_ref, status, created_at) 
                        VALUES (?, 'Deposit', ?, ?, 'pending', NOW())";
        $insert_stmt = $sql->prepare($insert_query);
        $insert_stmt->execute([$user_id, $amount, $transaction_ref]);

        $sql->commit();

        echo json_encode([
            'status' => 'success',
            'payment_link' => $response_data['data']['link']
        ]);
    } catch (Exception $e) {
        $sql->rollBack();
        echo json_encode([
            'status' => 'error',
            'message' => 'Database error'
        ]);
    }
} else {
    echo json_encode([
        'status' => 'error',
        'message' => 'Payment initialization failed'
    ]);
}
?>