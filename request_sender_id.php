<?php
include('db.php');
session_start();

if (!isset($_SESSION['user_id'])) {
    die("Unauthorized");
}

$user_id = $_SESSION['user_id'];
$sender_id = strtoupper(trim($_POST['sender_id'] ?? ''));
$purpose = trim($_POST['purpose'] ?? '');

if (strlen($sender_id) > 11 || !preg_match('/^[A-Z0-9]+$/', $sender_id)) {
    die("Invalid Sender ID.");
}

// Insert request into DB
$stmt = $sql->prepare("INSERT INTO sender_id_requests (user_id, sender_id, purpose, status) VALUES (?, ?, ?, 'pending')");
$stmt->execute([$user_id, $sender_id, $purpose]);

header("Location: bulksms.php?sender_request=success");
exit;

?>
