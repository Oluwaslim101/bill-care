<?php  
$paystackSecret = 'sk_live_5dfd636941e51a27446ee4adcbeff427055bf827';  
$logFile = __DIR__ . '/paystack_webhook.log';  
  
// Log entry hit  
file_put_contents($logFile, date('Y-m-d H:i:s') . " - Webhook hit\n", FILE_APPEND);  
  
// Read webhook body  
$input = @file_get_contents("php://input");  
file_put_contents($logFile, date('Y-m-d H:i:s') . " - Raw payload: $input" . PHP_EOL, FILE_APPEND);  
  
// Decode payload  
$event = json_decode($input, true);  
if (json_last_error() !== JSON_ERROR_NONE) {  
    http_response_code(400);  
    file_put_contents($logFile, date('Y-m-d H:i:s') . " - ERROR: Invalid JSON\n", FILE_APPEND);  
    exit('Invalid JSON');  
}  
  
// Signature validation  
$signature = $_SERVER['HTTP_X_PAYSTACK_SIGNATURE'] ?? '';  
$hash = hash_hmac('sha512', $input, $paystackSecret);  
if (!$signature || !hash_equals($hash, $signature)) {  
    http_response_code(400);  
    file_put_contents($logFile, date('Y-m-d H:i:s') . " - ERROR: Signature mismatch\n", FILE_APPEND);  
    exit('Invalid signature');  
}  
  
// Accept both charge.success and dedicated_account.transaction  
$eventType = $event['event'];  
$data = $event['data'];  
  
if (  
    $eventType === 'dedicated_account.transaction' ||  
    ($eventType === 'charge.success' && ($data['channel'] ?? '') === 'dedicated_nuban')  
) {  
    // Extract account number  
    $account_number = $data['account_number'] ?? $data['metadata']['receiver_account_number'] ?? null;  
    $amount_kobo = $data['amount'] ?? 0;  
    $reference = $data['reference'] ?? '';  
    $transaction_date = $data['createdAt'] ?? $data['paid_at'] ?? date('Y-m-d H:i:s');  
  
    if (!$account_number || $amount_kobo <= 0) {  
        http_response_code(400);  
        file_put_contents($logFile, date('Y-m-d H:i:s') . " - ERROR: Missing account number or invalid amount\n", FILE_APPEND);  
        exit('Invalid data');  
    }  
  
    $amount = $amount_kobo / 100;  
  
    // DB connection  
    $db_host = 'localhost';  
    $db_user = 'u822915062_billpay';  
    $db_pass = 'Lotanna@2024';  
    $db_name = 'u822915062_billpay';  
    $conn = new mysqli($db_host, $db_user, $db_pass, $db_name);  
  
    if ($conn->connect_error) {  
        http_response_code(500);  
        file_put_contents($logFile, date('Y-m-d H:i:s') . " - ERROR: DB connect fail\n", FILE_APPEND);  
        exit('DB connection failed');  
    }  
  
    $conn->begin_transaction();  
    try {  
        $stmt = $conn->prepare("SELECT id, balance FROM users WHERE virtual_account_number = ?");  
        $stmt->bind_param("s", $account_number);  
        $stmt->execute();  
        $result = $stmt->get_result();  
  
        if ($user = $result->fetch_assoc()) {  
            // Prevent duplicate reference
$dupCheck = $conn->prepare("SELECT id FROM payments WHERE reference = ?");
$dupCheck->bind_param("s", $reference);
$dupCheck->execute();
$dupCheckResult = $dupCheck->get_result();

if ($dupCheckResult->num_rows > 0) {
    file_put_contents($logFile, date('Y-m-d H:i:s') . " - INFO: Duplicate ref $reference ignored\n", FILE_APPEND);
    http_response_code(200);
    exit('Duplicate payment');
}
$dupCheck->close();

            $userId = $user['id'];  
            $newBalance = $user['balance'] + $amount;  
  
            $update = $conn->prepare("UPDATE users SET balance = ? WHERE id = ?");  
            $update->bind_param("di", $newBalance, $userId);  
            $update->execute();  
  
            $log = $conn->prepare("INSERT INTO payments (user_id, amount, reference, payment_method, created_at) VALUES (?, ?, ?, 'virtual_account', ?)");  
            $log->bind_param("idss", $userId, $amount, $reference, $transaction_date);  
            $log->execute();  
  
            // Add to notifications table  
            $actionType = 'wallet_credit';  
            $noteMessage = "₦" . number_format($amount, 2) . " was added to your wallet. Ref: $reference.";  
            $status = 'unread';  
            $notify = $conn->prepare("INSERT INTO notifications (user_id, action_type, message, status, created_at) VALUES (?, ?, ?, ?, ?)");  
            $notify->bind_param("issss", $userId, $actionType, $noteMessage, $status, $transaction_date);  
            $notify->execute();  
            
            // Extract optional metadata for receipt logging
$sender_name = $data['authorization']['sender_name'] ?? '';
$sender_bank = $data['authorization']['sender_bank'] ?? '';
$sender_account = $data['authorization']['sender_bank_account_number'] ?? '';

$receiver_bank = $data['authorization']['receiver_bank'] ?? ($data['metadata']['receiver_bank'] ?? '');
$receiver_account = $data['authorization']['receiver_bank_account_number'] ?? ($data['metadata']['receiver_account_number'] ?? '');

$customer_name = trim(($data['customer']['first_name'] ?? '') . ' ' . ($data['customer']['last_name'] ?? ''));
$customer_email = $data['customer']['email'] ?? '';

// Log top-up transaction details for receipt generation
$topupLog = $conn->prepare("INSERT INTO virtual_account_topups 
    (user_id, reference, amount, paid_at, sender_name, sender_bank, sender_account, receiver_bank, receiver_account, customer_name, customer_email) 
    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
$topupLog->bind_param("issssssssss",
    $userId,
    $reference,
    $amount,
    $transaction_date,
    $sender_name,
    $sender_bank,
    $sender_account,
    $receiver_bank,
    $receiver_account,
    $customer_name,
    $customer_email
);

$topupLog->execute();
  
            $conn->commit();  
  
            // ==== SMS Notification ====  
            $credit_date = date('Y-m-d H:i:s', strtotime($transaction_date));  
            $description = $data['description'] ?? ($data['metadata']['sender'] ?? 'Wallet Deposit');  
            $available_balance = number_format($newBalance, 2);  
            $amount_formatted = "₦" . number_format($amount, 2);  
  
            $sms_text = "Credit Alert!\n";  
            $sms_text .= "Account: $account_number\n";  
            $sms_text .= "Date: $credit_date\n";  
            $sms_text .= "Description: $description\n";  
            $sms_text .= "Amount: $amount_formatted\n";  
            $sms_text .= "Available Bal: ₦$available_balance\n";  
            $sms_text .= "Get Free 10% Bonus when you Recharge on DtheHub Wallet";  
  
            // Send SMS via Termii (update with your Termii API key and sender)  
            $termii_api_key = "TLZugUiorQTxeDaIyTOScmNBcoTnuzYFKQiPHHSptRcwLsNTdzwRFDHVWptPOm";  
            $termii_sender_id = "Bill Care";  
  
            // Get user's phone number  
            $phone_number_sql = $conn->prepare("SELECT phone_number FROM users WHERE id = ?");  
            $phone_number_sql->bind_param("i", $userId);  
            $phone_number_sql->execute();  
            $phone_number_res = $phone_number_sql->get_result()->fetch_assoc();  
            $user_phone = $phone_number_res['phone_number'];  
  
            $termii_payload = array(  
                "to" => $user_phone,  
                "from" => $termii_sender_id,  
                "sms" => $sms_text,  
                "type" => "plain",  
                "channel" => "generic",  
                "api_key" => $termii_api_key  
            );  
  
            $curl = curl_init();  
            curl_setopt_array($curl, array(  
                CURLOPT_URL => "https://v3.api.termii.com/api/sms/send",  
                CURLOPT_RETURNTRANSFER => true,  
                CURLOPT_POST => true,  
                CURLOPT_POSTFIELDS => json_encode($termii_payload),  
                CURLOPT_HTTPHEADER => array("Content-Type: application/json")  
            ));  
            $sms_response = curl_exec($curl);  
            curl_close($curl);  
  
            file_put_contents($logFile, date('Y-m-d H:i:s') . " - SUCCESS: user_id=$userId, amount=$amount, ref=$reference, SMS sent\n", FILE_APPEND);  
  
            http_response_code(200);  
            echo 'Payment processed';  
        } else {  
            $conn->rollback();  
            file_put_contents($logFile, date('Y-m-d H:i:s') . " - ERROR: No user for acct $account_number\n", FILE_APPEND);  
            http_response_code(404);  
            exit('User not found');  
        }  
  
        $stmt->close();  
        $update->close();  
        $log->close();  
        $notify->close();  
        $phone_number_sql->close();  
    } catch (Exception $e) {  
        $conn->rollback();  
        file_put_contents($logFile, date('Y-m-d H:i:s') . " - ERROR: " . $e->getMessage() . "\n", FILE_APPEND);  
        http_response_code(500);  
        exit('Server error');  
    }  
  
    $conn->close();  
    

if ($event['event'] === 'transfer.success') {
    $reference = $event['data']['reference'] ?? '';
    $statusFromPaystack = strtolower($event['data']['status']); // usually "success"
    $gatewayResponse = strtolower($event['data']['gateway_response']); // usually "successful"

    file_put_contents('webhook.log', date('Y-m-d H:i:s') . " - Checking ref: $reference\n", FILE_APPEND);

    // Confirm the withdrawal exists and is still pending
    $stmt = $conn->prepare("SELECT id, user_id, amount, status FROM user_withdrawals WHERE reference = ? LIMIT 1");
    $stmt->bind_param("s", $reference);
    $stmt->execute();
    $result = $stmt->get_result();
    $withdrawal = $result->fetch_assoc();

    if ($withdrawal && $withdrawal['status'] === 'PENDING') {
        // Mark as successful only if Paystack confirms it
        if ($statusFromPaystack === 'success' || $gatewayResponse === 'successful') {
            $update = $conn->prepare("UPDATE user_withdrawals SET status = 'SUCCESS', response = ? WHERE reference = ?");
            $jsonResponse = json_encode($event['data']);
            $update->bind_param("ss", $jsonResponse, $reference);
            $update->execute();

            file_put_contents('webhook.log', date('Y-m-d H:i:s') . " - Withdrawal updated to SUCCESS for $reference\n", FILE_APPEND);
        } else {
            file_put_contents('webhook.log', date('Y-m-d H:i:s') . " - Status not successful for $reference: $statusFromPaystack / $gatewayResponse\n", FILE_APPEND);
        }
    } else {
        file_put_contents('webhook.log', date('Y-m-d H:i:s') . " - No pending withdrawal found for $reference\n", FILE_APPEND);
    }
}

http_response_code(200);
exit;
}