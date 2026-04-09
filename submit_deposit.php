<?php
session_start();
ini_set('display_errors', 1);
error_reporting(E_ALL);

require 'db.php';

header('Content-Type: application/json');

// Generate unique transaction reference
function generateTransactionRef($prefix = 'SC') {
    return $prefix . strtoupper(bin2hex(random_bytes(3)));
}

// Insert user notification
function insertNotification($user_id, $message) {
    global $sql;
    $query = "INSERT INTO notifications (user_id, action_type, message, status, created_at) 
              VALUES (:user_id, :action_type, :message, :status, NOW())";
    $stmt = $sql->prepare($query);
    $stmt->execute([
        ':user_id' => $user_id,
        ':action_type' => 'deposit',
        ':message' => $message,
        ':status' => 'unread'
    ]);
}

// Main handler
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = $_SESSION['user_id'] ?? null;
    if (!$user_id) {
        echo json_encode(['success' => false, 'error' => 'User not logged in.']);
        exit;
    }

    $amount = floatval($_POST['depositAmount'] ?? 0);
    $wallet = $_POST['depositWallet'] ?? '';
    $method_id = intval($_POST['depositMethod'] ?? 0);
    $status = 'pending';
    $proof_path = '';
    $transaction_ref = generateTransactionRef();

    // Handle uploaded proof
    if (isset($_FILES['proofUpload']) && $_FILES['proofUpload']['error'] === UPLOAD_ERR_OK) {
        $uploadDir = 'uploads/deposit_proofs/';
        if (!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);
        $filename = time() . '_' . basename($_FILES['proofUpload']['name']);
        $targetFile = $uploadDir . $filename;
        if (move_uploaded_file($_FILES['proofUpload']['tmp_name'], $targetFile)) {
            $proof_path = $targetFile;
        }
    }

    // Validate inputs
    if ($amount <= 0 || empty($wallet) || $method_id <= 0) {
        echo json_encode(['success' => false, 'error' => 'Invalid input.']);
        exit;
    }

    try {
        $sql->beginTransaction();

        // Save deposit
        $stmt = $sql->prepare("INSERT INTO deposits (user_id, wallet, amount, method_id, proof_url, status, transaction_ref)
            VALUES (:user_id, :wallet, :amount, :method_id, :proof_url, :status, :transaction_ref)");
        $stmt->execute([
            ':user_id' => $user_id,
            ':wallet' => $wallet,
            ':amount' => $amount,
            ':method_id' => $method_id,
            ':proof_url' => $proof_path,
            ':status' => $status,
            ':transaction_ref' => $transaction_ref
        ]);

        // Notify user
        insertNotification($user_id, "Your deposit request of $" . number_format($amount, 2) . " is being processed.");

        // Get user info
        $stmt = $sql->prepare("SELECT phone_number, email, full_name FROM users WHERE id = :user_id");
        $stmt->execute([':user_id' => $user_id]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        $phone = $user['phone_number'] ?? '';
        $email = $user['email'] ?? '';
        $full_name = $user['full_name'] ?? 'User';

        // Send SMS
        if ($phone && preg_match('/^(\+234|0)[789][01]\d{8}$/', $phone)) {
            $smsData = http_build_query([
                'phone' => $phone,
                'amount' => $amount,
                'ref' => $transaction_ref
            ]);

            $smsURL = 'https:/swiftaffiliates.cloud/send_deposit_sms.php';

            $smsCurl = curl_init($smsURL);
            curl_setopt($smsCurl, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($smsCurl, CURLOPT_POST, true);
            curl_setopt($smsCurl, CURLOPT_POSTFIELDS, $smsData);
            $smsResponse = curl_exec($smsCurl);
            $smsError = curl_error($smsCurl);
            curl_close($smsCurl);

            file_put_contents('sms_log.txt', date('Y-m-d H:i:s') . " | SMS sent to $phone | Ref: $transaction_ref | Response: $smsResponse | Error: $smsError\n", FILE_APPEND);
        }

        // Send email via external endpoint
        if (!empty($email)) {
            $emailData = http_build_query([
                'email' => $email,
                'name' => $full_name,
                'amount' => $amount,
                'ref' => $transaction_ref
            ]);

            $emailURL = 'https://swiftaffiliates.cloud/send_deposit_email.php';

            $emailCurl = curl_init($emailURL);
            curl_setopt($emailCurl, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($emailCurl, CURLOPT_POST, true);
            curl_setopt($emailCurl, CURLOPT_POSTFIELDS, $emailData);
            $emailResponse = curl_exec($emailCurl);
            $emailError = curl_error($emailCurl);
            curl_close($emailCurl);

            file_put_contents('email_log.txt', date('Y-m-d H:i:s') . " | Email sent to $email | Ref: $transaction_ref | Response: $emailResponse | Error: $emailError\n", FILE_APPEND);
        }

        $sql->commit();

        echo json_encode(['success' => true, 'transaction_ref' => $transaction_ref]);
        exit;

    } catch (Exception $e) {
        $sql->rollBack();
        echo json_encode(['success' => false, 'error' => 'Transaction failed: ' . $e->getMessage()]);
        exit;
    }
}

echo json_encode(['success' => false, 'error' => 'Invalid request']);
?>
