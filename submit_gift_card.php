<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();
include 'db.php';

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    die("Invalid request");
}

$user_id = $_SESSION['user_id'] ?? null;
if (!$user_id) {
    die("User not logged in");
}

// Collect form data
$country_code = $_POST['country_code'] ?? '';
$gift_card_id = $_POST['gift_card_id'] ?? '';
$card_type = $_POST['card_type'] ?? '';
$amount = $_POST['amount'] ?? '';
$exchange_rate = $_POST['rate'] ?? '';
$total_value = $amount * $exchange_rate;
$status = "Pending";

// Generate transaction reference
$transaction_ref = "GC" . time() . rand(1000, 9999);

if (!$country_code || !$gift_card_id || !$card_type || !$amount || !$exchange_rate) {
    die("Missing required fields");
}

// Fetch gift card name
$stmt = $sql->prepare("SELECT name FROM gift_cards WHERE id = ?");
$stmt->execute([$gift_card_id]);
$gift_card = $stmt->fetch(PDO::FETCH_ASSOC);
$gift_card_name = $gift_card['name'] ?? "Unknown";

// Handle file uploads
$uploaded_images = [];
$upload_dir = "uploads/giftcards/";
if (!file_exists($upload_dir)) {
    mkdir($upload_dir, 0777, true);
}

foreach ($_FILES['giftcard_images']['tmp_name'] as $key => $tmp_name) {
    $filename = time() . "_" . basename($_FILES['giftcard_images']['name'][$key]);
    $filepath = $upload_dir . $filename;
    if (move_uploaded_file($tmp_name, $filepath)) {
        $uploaded_images[] = $filepath;
    }
}
$image_paths = implode(",", $uploaded_images);

// Insert into gift_card_sales table
$query = "INSERT INTO gift_card_sales (transaction_ref, user_id, country_code, gift_card_id, card_type, amount, exchange_rate, total_value, status, images) 
          VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
$stmt = $sql->prepare($query);
$inserted = $stmt->execute([$transaction_ref, $user_id, $country_code, $gift_card_id, $card_type, $amount, $exchange_rate, $total_value, $status, $image_paths]);

if ($inserted) {
    // Store transaction details in session
    $_SESSION['gift_card_transaction'] = [
        'transaction_ref' => $transaction_ref,
        'country_code'    => $country_code,
        'gift_card_name'  => $gift_card_name,
        'card_type'       => $card_type,
        'amount'          => $amount,
        'exchange_rate'   => $exchange_rate,
        'total_value'     => $total_value,
        'status'          => $status,
        'uploaded_images' => $uploaded_images
    ];

    // Redirect to success page
    header("Location: gift_card_success.php");
    exit;
} else {
    echo "Failed to submit request.";
}
?>