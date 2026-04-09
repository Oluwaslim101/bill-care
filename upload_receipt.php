<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $ref = $_POST['ref'] ?? '';
    if (!$ref) die("Invalid request: missing reference");

    if (!isset($_FILES['receipt']) || $_FILES['receipt']['error'] !== UPLOAD_ERR_OK) {
        die("Error uploading receipt.");
    }

    $allowedTypes = ['image/jpeg', 'image/png', 'application/pdf'];
    $fileType = $_FILES['receipt']['type'];

    if (!in_array($fileType, $allowedTypes)) {
        die("Invalid file type. Only PDF, JPG, PNG allowed.");
    }

    $ext = pathinfo($_FILES['receipt']['name'], PATHINFO_EXTENSION);
    $filename = "receipt_" . time() . "_" . rand(1000, 9999) . "." . $ext;
    $uploadPath = "receipts/" . $filename;

    if (!move_uploaded_file($_FILES['receipt']['tmp_name'], $uploadPath)) {
        die("Failed to save uploaded file.");
    }

    // Save to DB
    $stmt = $sql->prepare("INSERT INTO receipts (order_reference, file_path) VALUES (?, ?)");
    $stmt->execute([$ref, $uploadPath]);

    // Fetch order details
    $stmt = $sql->prepare("SELECT * FROM orders WHERE reference = ?");
    $stmt->execute([$ref]);
    $order = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$order) die("Order not found.");

    // Decode cart items
    $items = json_decode($order['cart_data'], true);
    $itemLines = "";
    foreach ($items as $item) {
        $itemLines .= "{$item['name']} (₦" . number_format($item['price']) . " x {$item['quantity']})\n";
    }

   $shopId = $order['shop_id']; // Get shop ID from the order
$stmt = $sql->prepare("SELECT * FROM shop_banks WHERE shop_id = ? LIMIT 1");
$stmt->execute([$shopId]);
$bank = $stmt->fetch(PDO::FETCH_ASSOC);
    $bankDetails = $bank
        ? "{$bank['bank_name']} - {$bank['account_number']} ({$bank['account_name']})"
        : "Bank details not available";

    // WhatsApp Message
    $receiptLink = "https://swiftaffiliates.cloud/app/$uploadPath"; // Replace with real URL
    $message = "*🧾 New Receipt Upload - DtheHub*\n\n"
        . "*Ref:* $ref\n"
        . "*Delivery Address:* {$order['delivery_address']}\n"
        . "*Items:*\n$itemLines\n"
        . "*Delivery Fee:* ₦" . number_format($order['delivery_fee']) . "\n"
        . "*Paid:* ₦" . number_format($order['paid_amount']) . " of ₦" . number_format($order['full_amount']) . "\n"
        . "*Payment Method:* {$order['payment_method']}\n\n"
        . "*Money was sent to:*\n$bankDetails\n\n"
        . "*📎 View Receipt:* $receiptLink\n"
        . "*Confirm and update order status in your dashboard.*";

    $whatsText = urlencode($message);
    $shopWhatsApp = "2348148622359";
    $adminWhatsApp = "2347076690090";
    $shopLink = "https://wa.me/$shopWhatsApp?text=$whatsText";
    $adminLink = "https://wa.me/$adminWhatsApp?text=$whatsText";


// Output HTML
echo '
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>DtheHub | Receipt Uploaded</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link rel="icon" type="image/png" href="assets/img/favicon.png">
    <style>
        .nav {
            position: fixed;
            bottom: 0;
            left: 50%;
            transform: translateX(-50%);
            width: 100%;
            max-width: 410px;
            display: flex;
            justify-content: space-around;
            background: white;
            padding: 12px 7px;
            border-radius: 8px 8px 0 0;
            box-shadow: 0px -2px 10px rgba(0, 0, 0, 0.1);
            z-index: 1000;
        }
        .nav a {
            text-decoration: none;
            color: gray;
            font-size: 12px;
            text-align: center;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 3px;
            flex: 1;
            transition: color 0.3s ease;
        }
        .nav a i {
            font-size: 20px;
            color: gray;
        }
        .nav a.active i, .nav a.active span {
            color: green;
            font-weight: bold;
        }
    </style>
</head>
<body>

<!-- App Header -->
<div class="appHeader d-flex justify-content-between align-items-center p-2 bg-light shadow-sm">
    <a href="index.php" class="headerButton text-dark">
        <i class="fas fa-arrow-left"></i>
    </a>
    <div class="pageTitle fw-bold">Receipt Upload</div>
    <div style="padding-right: 12px;">
        <i class="fas fa-shopping-cart fa-lg position-relative">
            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" style="font-size:10px;">0</span>
        </i>
    </div>
</div>

<!-- App Capsule -->
<div id="appCapsule" class="pt-8 pb-5 px-3">

    <div class="card border-success shadow-lg">
        <div class="card-header bg-success text-white text-center">
            <h4 class="mb-0">✅ Receipt Uploaded Successfully</h4>
        </div>
        <div class="card-body text-center">
            <p class="lead mb-4">
                Your payment receipt has been uploaded and is awaiting confirmation.
            </p>
            <div class="d-grid gap-2">
                <a href="' . $shopLink . '" target="_blank" class="btn btn-outline-primary">
                    📩 Notify Shop Owner via WhatsApp
                </a>
                <a href="' . $adminLink . '" target="_blank" class="btn btn-outline-secondary">
                    🛡️ Notify Admin via WhatsApp
                </a>
                <a href="index.php" class="btn btn-success mt-2">
                    🏠 Back to Home
                </a>
            </div>
        </div>
    </div>

</div>

<!-- Bottom Navigation -->
<nav class="nav">
    <a href="index.php" class="active">
        <i class="fas fa-home"></i>
        <span>Home</span>
    </a>
    <a href="rewards.php">
        <i class="fas fa-gift"></i>
        <span>Rewards</span>
    </a>
    <a href="cards.html">
        <i class="fas fa-receipt"></i>
        <span>Cards</span>
    </a>
    <a href="transactions.php">
        <i class="fas fa-clock-rotate-left"></i>
        <span>Transactions</span>
    </a>
    <a href="profile.php">
        <i class="fas fa-user"></i>
        <span>Profile</span>
    </a>
</nav>

<!-- JS Files -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>
<script src="assets/js/base.js"></script>
</body>
</html>
';
} else {
    echo "Invalid request method.";
}