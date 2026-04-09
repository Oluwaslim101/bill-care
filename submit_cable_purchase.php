<?php
require_once 'db.php'; // database connection

$data = json_decode(file_get_contents('php://input'), true);

$provider = $data['provider'] ?? '';
$smartcard = $data['smartcard'] ?? '';
$package = $data['package'] ?? '';
$phone = $data['phone'] ?? '';

if (!$provider || !$smartcard || !$package || !$phone) {
    echo json_encode(['success' => false, 'message' => 'Missing required fields.']);
    exit;
}

// NeloByte API credentials
$userID = 'CK100028738';
$apiKey = 'ID15QMGW5714U1Y8T0YAKM0SY3784ZV59AYH31QX6RCG008Q52H4X33D19NOT650';
$callbackURL = 'https://billcare.shop/nelobyte_cable_callback.php';

// API Request URL
$url = "https://www.nellobytesystems.com/APICableTVV1.asp?UserID=$userID&APIKey=$apiKey&CableTV=$provider&Package=$package&SmartCardNo=$smartcard&PhoneNo=$phone&CallBackURL=$callbackURL";

// Get response
$response = file_get_contents($url);
$result = json_decode($response, true);

// Extract response data
$status = strtolower($result['status'] ?? '');
$statusCode = $result['statuscode'] ?? '';
$orderId = $result['orderid'] ?? '';
$message = $status ?: 'No response received from API';

// Interpret result
if ($status === 'order_received') {
    // Optional: Get amount from your own database
    $stmtPkg = $pdo->prepare("SELECT price FROM cable_packages WHERE code = ?");
    $stmtPkg->execute([$package]);
    $pkgData = $stmtPkg->fetch();
    $amount = $pkgData['price'] ?? 0;

    // Log transaction
    $stmt = $pdo->prepare("INSERT INTO cable_transactions (provider, smartcard, package_code, phone, amount, status, request_id) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->execute([
        $provider,
        $smartcard,
        $package,
        $phone,
        $amount,
        $status,
        $orderId
    ]);

    echo json_encode(['success' => true, 'message' => 'Subscription submitted successfully.']);
} else {
    echo json_encode(['success' => false, 'message' => "Purchase failed: " . $message]);
}