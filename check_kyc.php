<?php
// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Include the database connection
include('db.php');

// Start the session to get user_id
session_start();
$user_id = $_SESSION['user_id'] ?? null;

if (!$user_id) {
    echo json_encode(['status' => 'unauthorized']);
    exit;
}

// Check the latest KYC status
$stmt = $sql->prepare("SELECT status FROM kyc WHERE user_id = :user_id ORDER BY submitted_at DESC LIMIT 1");
$stmt->execute([':user_id' => $user_id]);

$kycStatus = $stmt->fetchColumn();

// Normalize response
if ($kycStatus === 'verified') {
    echo json_encode(['status' => 'verified']);
} elseif ($kycStatus === 'pending') {
    echo json_encode(['status' => 'pending']);
} else {
    echo json_encode(['status' => 'not_submitted']);
}
?>