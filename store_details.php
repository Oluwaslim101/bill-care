<?php
session_start();
include 'db.php'; // Ensure this file sets up the $sql PDO connection

$user_id = $_SESSION['user_id'] ?? 1;
$uploadDir = 'uploads/wallet/';
if (!is_dir($uploadDir)) {
    mkdir($uploadDir, 0755, true);
}

// Custom notification function with dynamic action_type
function insertNotification($user_id, $action_type, $message) {
    global $sql;
    $query = "INSERT INTO notifications (user_id, action_type, message, status, created_at) 
              VALUES (:user_id, :action_type, :message, :status, NOW())";
    $stmt = $sql->prepare($query);
    $stmt->execute([
        ':user_id' => $user_id,
        ':action_type' => $action_type,
        ':message' => $message,
        ':status' => 'unread'
    ]);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $type = $_POST['type'] ?? '';

    if ($type === 'bank') {
        $accountNumber = $_POST['bankAccountNumber'];
        $bankName = $_POST['bankName'];
        $file = $_FILES['accountStatement'];

        if ($file['error'] === UPLOAD_ERR_OK) {
            $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
            $path = $uploadDir . 'bank_' . time() . ".$ext";
            move_uploaded_file($file['tmp_name'], $path);

            $data = json_encode(['accountNumber' => $accountNumber, 'bankName' => $bankName]);
            $stmt = $sql->prepare("INSERT INTO wallet_verification_requests (user_id, type, data, document, status) VALUES (?, ?, ?, ?, 'pending')");
            $stmt->execute([$user_id, $type, $data, $path]);

            insertNotification($user_id, 'bank', 'Your bank account verification request has been submitted.');
        }

    } elseif ($type === 'card') {
        $cardNumber = $_POST['creditCardNumber'];
        $cardHolder = $_POST['cardHolderName'];
        $front = $_FILES['creditCardFront'];
        $back = $_FILES['creditCardBack'];

        if ($front['error'] === UPLOAD_ERR_OK && $back['error'] === UPLOAD_ERR_OK) {
            $frontPath = $uploadDir . 'card_front_' . time() . '.' . pathinfo($front['name'], PATHINFO_EXTENSION);
            $backPath = $uploadDir . 'card_back_' . time() . '.' . pathinfo($back['name'], PATHINFO_EXTENSION);
            move_uploaded_file($front['tmp_name'], $frontPath);
            move_uploaded_file($back['tmp_name'], $backPath);

            $data = json_encode(['cardNumber' => $cardNumber, 'cardHolder' => $cardHolder]);
            $stmt = $sql->prepare("INSERT INTO wallet_verification_requests (user_id, type, data, front_image, back_image, status) VALUES (?, ?, ?, ?, ?, 'pending')");
            $stmt->execute([$user_id, $type, $data, $frontPath, $backPath]);

            insertNotification($user_id, 'card', 'Your credit card verification request has been submitted.');
        }

    } elseif ($type === 'crypto') {
        $coin = $_POST['cryptocurrency'];
        $address = $_POST['walletAddress'];
        $qr = $_FILES['qrCode'];

        if ($qr['error'] === UPLOAD_ERR_OK) {
            $qrPath = $uploadDir . 'crypto_qr_' . time() . '.' . pathinfo($qr['name'], PATHINFO_EXTENSION);
            move_uploaded_file($qr['tmp_name'], $qrPath);

            $data = json_encode(['cryptocurrency' => $coin, 'walletAddress' => $address]);
            $stmt = $sql->prepare("INSERT INTO wallet_verification_requests (user_id, type, data, qr_code, status) VALUES (?, ?, ?, ?, 'pending')");
            $stmt->execute([$user_id, $type, $data, $qrPath]);

            insertNotification($user_id, 'crypto', 'Your cryptocurrency wallet verification request has been submitted.');
        }
    }

    
header("Location: wallet_details.php?success={$type}");
exit;
}
?>