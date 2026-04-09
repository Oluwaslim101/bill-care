<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php'; // Or manual inclusion path

function emailHeader() {
    return '
    <div style="padding:20px; background:#f7f7f7; font-family:Arial, sans-serif;">
        <div style="max-width:600px; margin:auto; background:#fff; border-radius:8px; padding:20px; box-shadow:0 2px 10px rgba(0,0,0,0.05);">
            <h2 style="color:#333;">Swift Contract</h2>
            <hr style="border:none; border-top:1px solid #eee;">
    ';
}

function emailFooter() {
    return '
            <hr style="border:none; border-top:1px solid #eee; margin-top:30px;">
            <p style="color:#999; font-size:12px;">This is an automated message from Swift Contract. Do not reply.</p>
        </div>
    </div>
    ';
}

function sendDepositEmail($to, $name, $amount, $ref) {
    $subject = "Deposit Request Received";
    $body = emailHeader() . "
        <p>Dear <strong>$name</strong>,</p>
        <p>Your deposit request of <strong>$" . number_format($amount, 2) . "</strong> has been received successfully.</p>
        <p>Transaction Reference: <strong>$ref</strong></p>
        <p>We will notify you once it is processed and approved.</p>
    " . emailFooter();

    $mail = new PHPMailer(true);

    try {
        // Server settings
        $mail->isSMTP();
        $mail->Host       = 'smtp.hostinger.com'; // e.g., smtp.gmail.com
        $mail->SMTPAuth   = true;
        $mail->Username   = 'admin@digishubb.com';
        $mail->Password   = 'Lotanna@2024';
        $mail->SMTPSecure = 'tls';
        $mail->Port       = 587;

        // Recipients
        $mail->setFrom('admin@digishubb.com', 'DtheHub');
        $mail->addAddress($to, $name);

        // Content
        $mail->isHTML(true);
        $mail->Subject = $subject;
        $mail->Body    = $body;

        $mail->send();
        // Optionally log success
    } catch (Exception $e) {
        // Optionally log error: $mail->ErrorInfo
        file_put_contents('email_errors.log', date('Y-m-d H:i:s') . " - Error sending email to $to: " . $mail->ErrorInfo . "\n", FILE_APPEND);
    }
}
