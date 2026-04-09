<?php
/*******************************
 *  GLOBAL CONFIG & SETUP
 *******************************/
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('log_errors', 1);
ini_set('error_log', __DIR__ . '/error.log');

header('Content-Type: application/json');

$action = $_GET['action'] ?? '';

if ($action !== 'purchaseAirtime') {
    echo json_encode(['error' => 'Invalid action']);
    exit;
}

session_start();
$user_id = $_SESSION['user_id'] ?? null;

if (!$user_id) {
    echo json_encode(['error' => 'User not logged in']);
    exit;
}


/*******************************
 *  READ JSON INPUT
 *******************************/
$input = json_decode(file_get_contents("php://input"), true);

$network = strtolower($input['network'] ?? '');
$number  = trim($input['mobileNumber'] ?? '');
$amount  = (float)($input['amount'] ?? 0);

if (!$network || !$number || $amount < 50) {
    echo json_encode(['error' => 'Invalid input']);
    exit;
}


/*******************************
 *  NETWORK MAPPING
 *******************************/
$networkMap = [
    'mtn'      => '01',
    'glo'      => '02',
    '9mobile'  => '03',
    'airtel'   => '04'
];

if (!isset($networkMap[$network])) {
    echo json_encode(['error' => 'Invalid network']);
    exit;
}

$networkCode = $networkMap[$network];


/*******************************
 *  DATABASE CONNECTION
 *******************************/
try {
    $host     = getenv('DB_HOST') ?: 'mainline.proxy.rlwy.net';
    $port     = getenv('DB_PORT') ?: 14373;
    $dbname   = getenv('DB_NAME') ?: 'railway';
    $username = getenv('DB_USER') ?: 'root';
    $password = getenv('DB_PASS') ?: 'FeZDtcpXMciupbsCJrGLisMfByoHxGJS';

    $pdo = new PDO(
        "mysql:host=$host;port=$port;dbname=$dbname;charset=utf8mb4",
        $username,
        $password,
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
    );

} catch (PDOException $e) {
    error_log("DB ERROR: " . $e->getMessage());
    echo json_encode(['error' => 'Database connection failed']);
    exit;
}


/*******************************
 *  CHECK WALLET BALANCE
 *******************************/
$stmt = $pdo->prepare("SELECT balance FROM users WHERE id = ?");
$stmt->execute([$user_id]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user || $user['balance'] < $amount) {
    echo json_encode(['error' => 'Insufficient wallet balance']);
    exit;
}


/*******************************
 *  SEND REQUEST TO NELLOBYTES
 *******************************/
$userid   = 'CK100028738';
$apikey   = 'ID15QMGW5714U1Y8T0YAKM0SY3784ZV59AYH31QX6RCG008Q52H4X33D19NOT650';
$callback = "https://billcare.shop/nelobyte_airtime_callback.php";

$apiUrl = "https://www.nellobytesystems.com/APIAirtimeV1.asp?" . http_build_query([
    'UserID'        => $userid,
    'APIKey'        => $apikey,
    'MobileNetwork' => $networkCode,
    'Amount'        => $amount,
    'MobileNumber'  => $number,
    'CallBackURL'   => urlencode($callback)
]);

// Use cURL to prevent Hostinger blocks
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $apiUrl);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);  // avoid SSL issues
$response = curl_exec($ch);
$curlError = curl_error($ch);
curl_close($ch);

// Log raw response for debugging
error_log("NELLO RESPONSE: " . print_r($response, true));

if (!$response) {
    echo json_encode(['error' => 'API not reachable: ' . $curlError]);
    exit;
}

if (!str_contains($response, 'ORDER_RECEIVED')) {
    echo json_encode(['error' => 'API rejected request: ' . $response]);
    exit;
}


/*******************************
 *  PARSE NELLOBYTES RESPONSE
 *******************************/
$parts = array_map('trim', explode("|", $response));

/*
 Order of parts:
 0: ORDER_RECEIVED
 1: orderid
 2: network code
 3: mobile number
 4: amount
*/
/*******************************
 *  UNIVERSAL API RESPONSE PARSER
 *******************************/
$orderId = '';
$apiAmount = $amount;
$mobileNetworkName = $network;

// Try JSON first
$json = json_decode($response, true);

if ($json && isset($json['status']) && $json['status'] === 'ORDER_RECEIVED') {
    // JSON format
    $orderId = $json['orderid'] ?? '';
    $apiAmount = (float)($json['amount'] ?? $amount);
    $mobileNetworkName = $json['mobilenetwork'] ?? $network;

} else {
    // Try old pipe format
    $parts = array_map('trim', explode("|", $response));

    if (isset($parts[0]) && $parts[0] === 'ORDER_RECEIVED') {
        $orderId = $parts[1] ?? '';
        $apiAmount = (float)($parts[4] ?? $amount);
    }
}

if (!$orderId) {
    echo json_encode([
        'error' => 'Unable to parse API response',
        'raw'   => $response
    ]);
    exit;
}



/*******************************
 *  DEDUCT WALLET BALANCE
 *******************************/
$newBalance = $user['balance'] - $apiAmount;

$stmt = $pdo->prepare("UPDATE users SET balance = ? WHERE id = ?");
$stmt->execute([$newBalance, $user_id]);


/*******************************
 *  RECORD TRANSACTION
 *******************************/
$stmt = $pdo->prepare("
    INSERT INTO airtime_transactions 
    (user_id, order_id, network, mobile_number, amount, status)
    VALUES (?, ?, ?, ?, ?, 'success')
");

$stmt->execute([
    $user_id,
    $orderId,
    $network,
    $number,
    $apiAmount
]);


/*******************************
 *  SEND NOTIFICATION
 *******************************/
$message = "₦" . number_format($apiAmount, 2) . " airtime sent to $number successfully.";

$pdo->prepare("
    INSERT INTO notifications (user_id, action_type, message, status)
    VALUES (?, 'airtime_purchase', ?, 'unread')
")->execute([$user_id, $message]);


/*******************************
 *  FINAL RESPONSE
 *******************************/
echo json_encode([
    'status'   => 'success',
    'redirect' => 'airtime_receipt.php?ref=' . urlencode($orderId)
]);

exit;

?>
