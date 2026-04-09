<?php
session_start();
ini_set('display_errors', 1);
error_reporting(E_ALL);

require 'db.php';
require 'vendor/autoload.php'; // PHPMailer autoload

// PHPMailer configuration
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Function to generate a unique transaction reference
function generateTransactionRef($prefix = 'SC') {
    return $prefix . strtoupper(bin2hex(random_bytes(3))); // 5 characters for random part
}

// Function to insert notifications into the database
function insertNotification($user_id, $message) {
    global $sql; // Use the PDO connection object globally
    
    $query = "INSERT INTO notifications (user_id, action_type, message, status, created_at) 
              VALUES (:user_id, :action_type, :message, :status, NOW())";
    $stmt = $sql->prepare($query);
    $stmt->execute([
        ':user_id' => $user_id,
        ':action_type' => 'withdrawal',  // Define action type
        ':message' => $message,
        ':status' => 'unread'
    ]);
}

// Function to send email
function sendWithdrawalEmail($user_email, $amount, $transaction_ref) {
    $mail = new PHPMailer(true);
    try {
        //Server settings
        $mail->isSMTP();
        $mail->Host = 'smtp.hostinger.com'; // Set mail server host (e.g., Gmail, SendGrid, etc.)
        $mail->SMTPAuth = true;
        $mail->Username = 'support@swiftaffiliates.cloud'; // SMTP username
        $mail->Password = 'Lotanna@2024'; // SMTP password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        //Recipients
        $mail->setFrom('support@swiftaffiliates.cloud', 'Swift Contract');
        $mail->addAddress($user_email); // User's email

        // Content
        $mail->isHTML(true);
        $mail->Subject = 'Withdrawal Request Received';
        $mail->Body    = 'Dear User,<br><br>Your withdrawal request of $' . number_format($amount, 2) . ' has been received. Your transaction reference is: <strong>' . $transaction_ref . '</strong>.<br><br>We will process your request shortly.<br><br>Thank you for using our service.';

        $mail->send();
    } catch (Exception $e) {
        echo "Email could not be sent. Mailer Error: {$mail->ErrorInfo}";
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = $_SESSION['user_id'] ?? null;
    if (!$user_id) {
        echo json_encode(['success' => false, 'error' => 'User not logged in.']);
        exit;
    }

    $amount = floatval($_POST['withdrawAmount'] ?? 0);
    $wallet = $_POST['withdrawFrom'] ?? '';
    $method_id = intval($_POST['withdrawMethod'] ?? 0);
    $receiver_details = trim($_POST['receiverDetails'] ?? '');
    $status = 'pending';
    $transaction_ref = generateTransactionRef();

    if ($amount <= 0 || !in_array($wallet, ['balance', 'earnings']) || empty($receiver_details) || $method_id <= 0) {
        echo json_encode(['success' => false, 'error' => 'Invalid input.']);
        exit;
    }

    try {
        // Fetch user's wallet balance and email
        $stmt = $sql->prepare("SELECT balance, earnings, email FROM users WHERE id = :id");
        $stmt->execute([':id' => $user_id]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$user || $user[$wallet] < $amount) {
            echo json_encode(['success' => false, 'error' => 'Insufficient ' . $wallet]);
            exit;
        }

        // Start transaction to ensure atomicity
        $sql->beginTransaction();

        // Deduct the amount from the selected wallet (balance or earnings)
        $stmt = $sql->prepare("UPDATE users SET $wallet = $wallet - :amount WHERE id = :id");
        $stmt->execute([':amount' => $amount, ':id' => $user_id]);

        // Insert withdrawal record
        $stmt = $sql->prepare("INSERT INTO withdrawals (user_id, source, amount, method_id, method_details, status, transaction_ref, created_at)
            VALUES (:user_id, :source, :amount, :method_id, :method_details, :status, :transaction_ref, NOW())");
        $stmt->execute([
            ':user_id' => $user_id,
            ':source' => $wallet,
            ':amount' => $amount,
            ':method_id' => $method_id,
            ':method_details' => $receiver_details,
            ':status' => $status,
            ':transaction_ref' => $transaction_ref
        ]);

        // Insert notification about the withdrawal request
        insertNotification($user_id, "Your withdrawal request of $" . number_format($amount, 2) . " is being processed.");

        // Send withdrawal email notification
        sendWithdrawalEmail($user['email'], $amount, $transaction_ref);

        // Commit transaction
        $sql->commit();
        
        echo json_encode(['success' => true, 'transaction_ref' => $transaction_ref]);
        exit;

    } catch (Exception $e) {
        // If anything goes wrong, roll back the transaction
        $sql->rollBack();
        echo json_encode(['success' => false, 'error' => 'Transaction failed: ' . $e->getMessage()]);
        exit;
    }
}

echo json_encode(['success' => false, 'error' => 'Invalid request']);
?>
