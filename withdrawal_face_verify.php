<?php
header('Content-Type: application/json');
error_reporting(E_ALL);
ini_set('display_errors', 0);
include('db.php');
session_start();

$api_key = 'JDtgDskaTEIvehWVCjQoEjOh96Kefm1u';
$api_secret = 'd88nvO2hyLRrUMByHKXoKA0bwVofZq6y';

$user_id = $_SESSION['user_id'] ?? null;
if (!$user_id) {
    echo json_encode(['status' => 'error', 'message' => 'User not logged in']);
    exit;
}

// 1. Fetch KYC image
$stmt = $sql->prepare("SELECT id_image FROM kyc_verifications WHERE user_id = ? ORDER BY id DESC LIMIT 1");
$stmt->execute([$user_id]);
$row = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$row || !file_exists($row['id_image'])) {
    echo json_encode(['status' => 'error', 'message' => 'KYC image not found']);
    exit;
}

$kycImagePath = $row['id_image'];

// 2. Validate selfie input
if (!isset($_POST['selfie_image']) || !preg_match('/^data:image\/(\w+);base64,/', $_POST['selfie_image'])) {
    echo json_encode(['status' => 'error', 'message' => 'Invalid selfie format']);
    exit;
}

$selfieData = base64_decode(explode(',', $_POST['selfie_image'])[1]);
$selfiePath = 'uploads/selfie_' . uniqid() . '.jpg';
if (!file_put_contents($selfiePath, $selfieData)) {
    echo json_encode(['status' => 'error', 'message' => 'Failed to save selfie']);
    exit;
}

// 3. Face++ comparison
$api_url = 'https://api-us.faceplusplus.com/facepp/v3/compare';
$postData = [
    'api_key' => $api_key,
    'api_secret' => $api_secret,
    'image_file1' => new CURLFile($kycImagePath),
    'image_file2' => new CURLFile($selfiePath)
];

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $api_url);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
$response = curl_exec($ch);
curl_close($ch);

// 4. Delete temp selfie
unlink($selfiePath);

// 5. Handle response
$result = json_decode($response, true);
if (!$result || isset($result['error_message'])) {
    echo json_encode(['status' => 'error', 'message' => $result['error_message'] ?? 'Face++ error']);
    exit;
}

$confidence = $result['confidence'] ?? 0.0;

// 6. Check confidence score
if ($confidence >= 70) {
    // ✅ Match – reset face attempts
    $sql->prepare("UPDATE users SET failed_face_attempts = 0 WHERE id = ?")->execute([$user_id]);
    echo json_encode(['status' => 'success', 'confidence' => $confidence]);
} else {
    // ❌ Fail – increment face attempts
    $stmt = $sql->prepare("SELECT failed_face_attempts FROM users WHERE id = ?");
    $stmt->execute([$user_id]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    $failedAttempts = ($user['failed_face_attempts'] ?? 0) + 1;

    if ($failedAttempts >= 3) {
        // Lock user
        $sql->prepare("UPDATE users SET failed_face_attempts = ?, withdrawal_locked = 1 WHERE id = ?")
            ->execute([$failedAttempts, $user_id]);

        echo json_encode([
            'status' => 'locked',
            'confidence' => $confidence,
            'message' => 'Face mismatch 3 times. Withdrawals disabled. Contact support.'
        ]);
    } else {
        // Just warn
        $sql->prepare("UPDATE users SET failed_face_attempts = ? WHERE id = ?")
            ->execute([$failedAttempts, $user_id]);

        echo json_encode([
            'status' => 'fail',
            'confidence' => $confidence,
            'attempts_left' => 3 - $failedAttempts,
            'message' => "Face mismatch. Attempts left: " . (3 - $failedAttempts)
        ]);
    }
}
