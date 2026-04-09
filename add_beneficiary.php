<?php
// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

header('Content-Type: application/json');


include('db.php');

session_start(); // Prevent multiple alerts per user session


// Ensure user is logged in
if (!isset($_SESSION['user_id']) || empty($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$user_id = $_SESSION['user_id'];


// Fetch user data
$query = "SELECT * FROM users WHERE id = ?";
$stmt = $sql->prepare($query);
$stmt->execute([$user_id]);
$user = $stmt->fetch();

if (!$user) {
    header('Location: login.php');
    exit();
}


if (!isset($_SESSION['user_id'])) {
    echo json_encode(['status' => 'error', 'message' => 'User not logged in']);
    exit;
}

$userId = $_SESSION['user_id'];
$accountNumber = trim($_POST['account_number'] ?? '');
$bankCode = trim($_POST['bank_code'] ?? '');
$bankName = trim($_POST['bank_name'] ?? '');
$accountName = trim($_POST['account_name'] ?? '');

if (strlen($accountNumber) !== 10 || !$bankCode || !$bankName || !$accountName) {
    echo json_encode(['status' => 'error', 'message' => 'Invalid input']);
    exit;
}

// Check if already saved
$check = $sql->prepare("SELECT id FROM beneficiaries WHERE user_id = ? AND account_number = ? AND bank_code = ?");
$check->execute([$userId, $accountNumber, $bankCode]);

if ($check->rowCount() > 0) {
    echo json_encode(['status' => 'exists', 'message' => 'Beneficiary already saved']);
    exit;
}

// Insert new beneficiary
$insert = $sql->prepare("INSERT INTO beneficiaries (user_id, account_number, bank_code, bank_name, account_name) VALUES (?, ?, ?, ?, ?)");
$inserted = $insert->execute([$userId, $accountNumber, $bankCode, $bankName, $accountName]);

if ($inserted) {
    echo json_encode(['status' => 'success', 'message' => 'Beneficiary saved']);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Could not save beneficiary']);
}
?>
