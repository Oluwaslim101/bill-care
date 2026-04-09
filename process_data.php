<?php
header('Content-Type: application/json');
$action = $_GET['action'] ?? '';

function connectDB() {
    $host = 'localhost';
    $db = 'u822915062_billpay';
    $user = 'u822915062_billpay';
    $pass = 'Lotanna@2024';
    $pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8mb4", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    return $pdo;
}

// === GET PLANS ===
if ($action === 'getPlans') {
    $network = $_GET['network'] ?? '';
    $pdo = connectDB();

    $tableMap = [
        'mtn' => 'mtn_data',
        'glo' => 'glo_data',
        '9mobile' => '9mobile_data',
        'airtel' => 'airtel_data'
    ];

    if (!isset($tableMap[$network])) {
        echo json_encode(['error' => 'Invalid network selected']);
        exit;
    }

    $table = $tableMap[$network];
    $stmt = $pdo->prepare("SELECT code, description, price FROM $table ORDER BY price ASC");
    $stmt->execute();
    $plans = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode(['plans' => $plans]);
    exit;
}

// === PURCHASE DATA ===
if ($action === 'purchaseData') {
    $input = json_decode(file_get_contents('php://input'), true);
    if (!$input) {
        echo json_encode(['error' => 'Invalid input']);
        exit;
    }

    $network = $input['network'] ?? '';
    $inputMobile = $input['mobileNumber'] ?? '';
    $dataPlanCode = $input['dataPlanCode'] ?? '';
    $planName = $input['planName'] ?? 'Unknown Plan';
    $amount = (float)($input['amount'] ?? 0);

    if (!$network || !$inputMobile || !$dataPlanCode || !$amount) {
        echo json_encode(['error' => 'Missing required fields']);
        exit;
    }

    session_start();
    $user_id = $_SESSION['user_id'] ?? null;
    if (!$user_id) {
        echo json_encode(['error' => 'User not logged in']);
        exit;
    }

    $pdo = connectDB();

    // Check user balance BEFORE calling the API
    $stmt = $pdo->prepare("SELECT balance FROM users WHERE id = ?");
    $stmt->execute([$user_id]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$user || $user['balance'] < $amount) {
        echo json_encode(['error' => 'Insufficient wallet balance']);
        exit;
    }

    // === Proceed to call the API ===
    $networkMap = [
        'mtn' => '01',
        'glo' => '02',
        '9mobile' => '03',
        'airtel' => '04'
    ];

    if (!isset($networkMap[$network])) {
        echo json_encode(['error' => 'Invalid network']);
        exit;
    }

    $mobileNetworkCode = $networkMap[$network];
    $userid = 'CK100028738';
    $apikey = 'ID15QMGW5714U1Y8T0YAKM0SY3784ZV59AYH31QX6RCG008Q52H4X33D19NOT650';

    $apiUrl = "https://www.nellobytesystems.com/APIDatabundleV1.asp";
    $params = [
        'UserID' => $userid,
        'APIKey' => $apikey,
        'MobileNetwork' => $mobileNetworkCode,
        'DataPlan' => $dataPlanCode,
        'MobileNumber' => $inputMobile,
        'RequestID' => uniqid('', true),
        'CallBackURL' => 'https://billcare.shop/nelobyte_data_callback.php'
    ];

    $urlWithParams = $apiUrl . '?' . http_build_query($params);

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $urlWithParams);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 30);
    $response = curl_exec($ch);
    if (curl_errno($ch)) {
        echo json_encode(['error' => 'Request error: ' . curl_error($ch)]);
        curl_close($ch);
        exit;
    }
    curl_close($ch);

    $respData = json_decode($response, true);
    if (!$respData) {
        echo json_encode(['error' => 'Invalid response from API']);
        exit;
    }

    if ($respData['statuscode'] === "100" && $respData['status'] === "ORDER_RECEIVED") {
        $newBalance = $user['balance'] - $amount;
        $pdo->prepare("UPDATE users SET balance = ? WHERE id = ?")->execute([$newBalance, $user_id]);

        $order_id = $respData['orderid'];
        $networkName = $respData['mobilenetwork'];
        $status = 'success';

        // Log transaction
        $stmt = $pdo->prepare("INSERT INTO data_transactions 
            (user_id, order_id, network, mobile_number, plan_code, plan_name, amount, status) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([
            $user_id,
            $order_id,
            $networkName,
            $inputMobile,
            $dataPlanCode,
            $planName,
            $amount,
            $status
        ]);

        // Notification
        $message = "You successfully purchased {$planName} for ₦" . number_format($amount, 2) . " on {$inputMobile}.";
        $stmt2 = $pdo->prepare("INSERT INTO notifications (user_id, action_type, message, status) VALUES (?, 'data_purchase', ?, 'unread')");
        $stmt2->execute([$user_id, $message]);

        // Return response
        echo json_encode([
            'status' => 'success',
            'redirect' => 'data_receipt.php?ref=' . urlencode($order_id)
        ]);
        exit;
    }

    echo json_encode(['error' => $respData['status'] ?? 'Unknown error']);
    exit;
}

echo json_encode(['error' => 'Invalid action']);
