<?php
include('db.php');
session_start();

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['status' => 'error', 'message' => 'Unauthorized access.']);
    exit();
}

$user_id = $_SESSION['user_id'];

$query = "SELECT email_auth_enabled FROM users WHERE id=?";
$stmt = $sql->prepare($query);
$stmt->execute([$user_id]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

echo json_encode([
    'email_auth_enabled' => (bool) $user['email_auth_enabled']
]);
?>