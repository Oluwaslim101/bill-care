<?php
header('Content-Type: application/json');

$provider = $_GET['provider'] ?? '';
$smartcard = $_GET['smartcard'] ?? '';

if (!$provider || !$smartcard) {
    echo json_encode(['success' => false, 'name' => '', 'message' => 'Missing parameters']);
    exit;
}

$userID = 'CK100028738';
$apiKey = 'ID15QMGW5714U1Y8T0YAKM0SY3784ZV59AYH31QX6RCG008Q52H4X33D19NOT650';


$url = "https://www.nellobytesystems.com/APIVerifyCableTVV1.0.asp?UserID=$userID&APIKey=$apiKey&cabletv=$provider&smartcardno=$smartcard";

$response = file_get_contents($url);
$result = json_decode($response, true);

$name = $result['customer_name'] ?? '';
$status = $result['status'] ?? '';

if ($status === '00' && $name !== '') {
    echo json_encode(['success' => true, 'name' => $name]);
} else {
    echo json_encode(['success' => false, 'name' => '', 'message' => 'Invalid smartcard']);
}
?>
