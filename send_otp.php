<?php
// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Include database connection
include('db.php');
session_start();

// Ensure user is logged in
if (!isset($_SESSION['user_id'])) {
    die(json_encode(["status" => "error", "message" => "Unauthorized access."]));
}

$user_id = $_SESSION['user_id'];

// Generate a 7-digit OTP
$otp = rand(1000000, 9999999);
$expiry = date("Y-m-d H:i:s", strtotime("+10 minutes")); // OTP expires in 10 mins

// Save OTP in the database
try {
    $stmt = $sql->prepare("UPDATE users SET withdrawal_otp = ?, otp_expires_at = ? WHERE id = ?");
    $stmt->execute([$otp, $expiry, $user_id]);

    if ($stmt->rowCount() === 0) {
        die(json_encode(["status" => "error", "message" => "Failed to update OTP."]));
    }

    // Fetch user email
    $stmt = $sql->prepare("SELECT email FROM users WHERE id = ?");
    $stmt->execute([$user_id]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$user) {
        die(json_encode(["status" => "error", "message" => "User not found."]));
    }
    
    $user_email = $user['email'];

    // Send OTP via email
    $subject = "Your Withdrawal OTP Code";
    $message = "Your OTP for withdrawal is: $otp. It will expire in 10 minutes.";
    $headers = "From: no-reply@swiftaffiliates.cloud\r\nContent-Type: text/plain";

    if (mail($user_email, $subject, $message, $headers)) {
        echo json_encode(["status" => "success", "message" => "OTP sent successfully."]);
    } else {
        echo json_encode(["status" => "error", "message" => "Failed to send OTP email."]);
    }
} catch (Exception $e) {
    die(json_encode(["status" => "error", "message" => "Database error: " . $e->getMessage()]));
}
?>