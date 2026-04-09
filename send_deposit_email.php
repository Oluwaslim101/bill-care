<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'error' => 'Method not allowed']);
    exit;
}

$email = $_POST['email'] ?? '';
$name = $_POST['name'] ?? 'Customer';
$amount = floatval($_POST['amount'] ?? 0);
$ref = $_POST['ref'] ?? '';

if (empty($email) || $amount <= 0 || empty($ref)) {
    echo json_encode(['success' => false, 'error' => 'Invalid input']);
    exit;
}

$subject = "Deposit Request Received";

$message = "
<html>
<head>
    <style>
        body { font-family: Arial, sans-serif; background-color: #f7f7f7; color: #333; }
        .container {
            max-width: 600px;
            margin: auto;
            background: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        }
        .header { text-align: center; padding-bottom: 20px; }
        .footer { text-align: center; padding-top: 20px; font-size: 12px; color: #999; }
        .content { margin-bottom: 20px; }
    </style>
</head>
<body>
    <div class='container'>
        <div class='header'>
            <h2>Swift Contract</h2>
        </div>
        <div class='content'>
            <p>Dear <strong>$name</strong>,</p>
            <p>Your deposit request of <strong>$" . number_format($amount, 2) . "</strong> has been successfully received.</p>
            <p>Transaction Reference: <strong>$ref</strong></p>
            <p>We will notify you once it is processed and approved.</p>
        </div>
        <div class='footer'>
            <p>This is an automated message from Swift Contract. Do not reply to this email.</p>
        </div>
    </div>
</body>
</html>
";

// Email headers
$headers  = "MIME-Version: 1.0\r\n";
$headers .= "Content-type: text/html; charset=UTF-8\r\n";
$headers .= "From: Swift Contract <support@swiftaffiliates.cloud>\r\n";

// Send mail
if (mail($email, $subject, $message, $headers)) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'error' => 'Email sending failed']);
}
?>
