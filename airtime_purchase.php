<?php

// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Include database connection
include('db.php');

// Start session
session_start();

// Check if user is logged in
if (!isset($_SESSION['user_id']) || empty($_SESSION['user_id'])) {
    echo json_encode(['status' => 'error', 'message' => 'User not logged in.']);
    exit();
}

// Get user ID from session
$user_id = $_SESSION['user_id'];

// Fetch user data
$query = "SELECT * FROM users WHERE id = ?";
$stmt = $sql->prepare($query);
$stmt->execute([$user_id]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user) {
    echo json_encode(['status' => 'error', 'message' => 'User not found.']);
    exit();
}

// Fetch unread notifications
$notifications_query = "SELECT * FROM notifications WHERE user_id = ? AND status = 'unread'";
$notifications_stmt = $sql->prepare($notifications_query);
$notifications_stmt->execute([$user_id]);
$unread_count = $notifications_stmt->rowCount();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $entered_pin = trim($_POST['pin'] ?? '');
    $phone_number = trim($_POST['phone_number'] ?? '');
    $amount = floatval($_POST['amount'] ?? 0);
    $network = trim($_POST['network'] ?? '');

    if (empty($entered_pin) || empty($phone_number) || empty($amount) || empty($network)) {
        echo json_encode(['status' => 'error', 'message' => 'All fields are required!']);
        exit();
    } elseif ($entered_pin !== $user['pin']) {
        echo json_encode(['status' => 'error', 'message' => 'Incorrect PIN. Please try again.']);
        exit();
    } elseif ($amount > $user['balance']) {
        echo json_encode(['status' => 'error', 'message' => 'Insufficient balance!']);
        exit();
    } else {
        try {
            $transaction_ref = "TXN_" . time();
            $url = "https://api.flutterwave.com/v3/bills";
            $data = [
                "country" => "NG",
                "customer" => $phone_number,
                "amount" => $amount,
                "recurrence" => "ONCE",
                "type" => "AIRTIME",
                "reference" => $transaction_ref,
                "biller_name" => $network
            ];

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
            curl_setopt($ch, CURLOPT_HTTPHEADER, [
                "Authorization: Bearer " . FLW_SECRET_KEY,
                "Content-Type: application/json"
            ]);

            $response = curl_exec($ch);
            curl_close($ch);
            $result = json_decode($response, true);

            if ($result && isset($result['status']) && $result['status'] == "success") {
                $new_balance = $user['balance'] - $amount;
                $stmt = $sql->prepare("UPDATE users SET balance = ? WHERE id = ?");
                $stmt->execute([$new_balance, $user_id]);
                $stmt = $sql->prepare("INSERT INTO transactions (user_id, transaction_ref, type, amount, status, mobile_number, network_code, created_at) VALUES (?, ?, ?, ?, ?, ?, ?, NOW())");
                $stmt->execute([
                    $user_id,
                    $transaction_ref,
                    "airtime",
                    $amount,
                    "successful",
                    $phone_number,
                    $network
                ]);

                echo json_encode(['status' => 'success', 'message' => 'Airtime purchased successfully!', 'transaction_ref' => $transaction_ref]);
                exit();
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Transaction failed! Please try again.']);
                exit();
            }
        } catch (Exception $e) {
            echo json_encode(['status' => 'error', 'message' => 'An error occurred: ' . $e->getMessage()]);
            exit();
        }
    }
}
?>