<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
header('Content-Type: application/json');

include('db.php');
session_start();

// Validate session and CSRF token
if (!isset($_SESSION['user_id']) || empty($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'error' => 'Unauthorized']);
    exit();
}
if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
    echo json_encode(['success' => false, 'error' => 'Invalid CSRF token']);
    exit();
}

$user_id = $_SESSION['user_id'];

// Validate user
$stmt = $sql->prepare("SELECT id FROM users WHERE id = ?");
$stmt->execute([$user_id]);
if (!$stmt->fetch()) {
    echo json_encode(['success' => false, 'error' => 'User not found']);
    exit();
}

// Validate inputs
if (
    empty($_POST['giftcard_id']) ||
    empty($_POST['country']) ||
    empty($_POST['card_type']) ||
    empty($_POST['amount']) ||
    empty($_FILES['card_images']['name'][0])
) {
    echo json_encode(['success' => false, 'error' => 'Missing required fields']);
    exit();
}

$giftcard_id = $_POST['giftcard_id'];
$country = $_POST['country'];
$card_type = $_POST['card_type'];
$amount = floatval($_POST['amount']);

// Validate uploaded images
$allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
$maxSize = 5 * 1024 * 1024;
$targetDir = 'uploads/';
$imagePaths = [];

if (!is_dir($targetDir)) {
    mkdir($targetDir, 0755, true);
}

foreach ($_FILES['card_images']['name'] as $index => $name) {
    $tmpName = $_FILES['card_images']['tmp_name'][$index];
    $type = $_FILES['card_images']['type'][$index];
    $size = $_FILES['card_images']['size'][$index];
    $error = $_FILES['card_images']['error'][$index];

    if ($error !== 0) continue;

    if (!in_array($type, $allowedTypes)) {
        echo json_encode(['success' => false, 'error' => "Unsupported image format: $type"]);
        exit();
    }

    if ($size > $maxSize) {
        echo json_encode(['success' => false, 'error' => "Image too large: $name"]);
        exit();
    }

    $safeName = time() . "_" . preg_replace("/[^a-zA-Z0-9.\-_]/", "_", basename($name));
    $targetPath = $targetDir . $safeName;

    if (!move_uploaded_file($tmpName, $targetPath)) {
        echo json_encode(['success' => false, 'error' => "Failed to save $name"]);
        exit();
    }

    $imagePaths[] = $targetPath;
}

$allPaths = implode(',', $imagePaths);

// Insert into database
$stmt = $sql->prepare("INSERT INTO giftcard_trades (user_id, giftcard_id, country, card_type, amount, image_path) VALUES (?, ?, ?, ?, ?, ?)");
$inserted = $stmt->execute([$user_id, $giftcard_id, $country, $card_type, $amount, $allPaths]);

if ($inserted) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'error' => 'Database error']);
}
?>
