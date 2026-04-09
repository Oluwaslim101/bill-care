<?php
header('Content-Type: application/json');
error_reporting(E_ALL);
ini_set('display_errors', 0);
ini_set('log_errors', 1);
ini_set('error_log', __DIR__ . '/face_error.log');

include('db.php');
session_start();

$api_key = 'JDtgDskaTEIvehWVCjQoEjOh96Kefm1u';
$api_secret = 'd88nvO2hyLRrUMByHKXoKA0bwVofZq6y';

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['status' => 'error', 'message' => 'User not logged in.']);
    exit;
}

$user_id = $_SESSION['user_id'];

// Check retry limit
$check = $sql->prepare("SELECT kyc_attempts, kyc_last_attempt FROM users WHERE id = ?");
$check->execute([$user_id]);
$userData = $check->fetch(PDO::FETCH_ASSOC);

$now = new DateTime();
$lastAttempt = new DateTime($userData['kyc_last_attempt'] ?? '2000-01-01');
$diffHours = $now->diff($lastAttempt)->h + ($now->diff($lastAttempt)->days * 24);

if ($userData['kyc_attempts'] >= 4 && $diffHours < 24) {
    echo json_encode([
        'status' => 'blocked',
        'message' => '⛔ You have reached the max 4 verification attempts. Please try again after 24 hours.'
    ]);
    exit;
}

// Prepare directories
$uploadDir = 'uploads/';
$ninDir = 'nin_uploads/';
@mkdir($uploadDir);
@mkdir($ninDir);

// Temp paths
$tempNinPath = $uploadDir . uniqid('nin_') . '.jpg';
$selfieImagePath = $uploadDir . uniqid('selfie_') . '.jpg';

// Final path for saved NIN
$finalNinPath = $ninDir . 'user_' . $user_id . '_' . time() . '.jpg';

// Validate input
if (!isset($_FILES['nin_image']) || !isset($_POST['selfie_image'])) {
    echo json_encode(['status' => 'error', 'message' => 'All fields are required.']);
    exit;
}

if (!move_uploaded_file($_FILES['nin_image']['tmp_name'], $tempNinPath)) {
    echo json_encode(['status' => 'error', 'message' => 'Failed to upload NIN image.']);
    exit;
}

// Save permanent copy
copy($tempNinPath, $finalNinPath);

if (!preg_match('/^data:image\/(\w+);base64,/', $_POST['selfie_image'])) {
    echo json_encode(['status' => 'error', 'message' => 'Invalid selfie format.']);
    exit;
}

$data = base64_decode(explode(',', $_POST['selfie_image'])[1]);
if (!$data || file_put_contents($selfieImagePath, $data) === false) {
    echo json_encode(['status' => 'error', 'message' => 'Failed to save selfie image.']);
    exit;
}

// Call Face++ API
$api_url = 'https://api-us.faceplusplus.com/facepp/v3/compare';
$postData = [
    'api_key' => $api_key,
    'api_secret' => $api_secret,
    'image_file1' => new CURLFile($tempNinPath),
    'image_file2' => new CURLFile($selfieImagePath),
];

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $api_url);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
$response = curl_exec($ch);
curl_close($ch);

// Cleanup temp files
unlink($tempNinPath);
unlink($selfieImagePath);

// Decode response
$result = json_decode($response, true);
if (!$result || isset($result['error_message'])) {
    $msg = $result['error_message'] ?? 'Invalid Face++ response.';
    error_log("Face++ Error: " . $msg);
    echo json_encode(['status' => 'error', 'message' => 'Face++ Error: ' . $msg]);
    exit;
}

$confidence = $result['confidence'] ?? 0.0;
$status = ($confidence >= 70) ? 'verified' : 'failed';

// Save KYC record
$insert = $sql->prepare("INSERT INTO kyc_verifications (user_id, confidence_score, kyc_status, id_image) VALUES (?, ?, ?, ?)");
$insert->execute([$user_id, $confidence, $status, $finalNinPath]);

// Update user
if ($status === 'verified') {
    $sql->prepare("UPDATE users SET status = 'verified', kyc_attempts = 0, kyc_last_attempt = NULL WHERE id = ?")
        ->execute([$user_id]);

    echo json_encode([
        'status' => 'success',
        'message' => "✅ Face Verified Successfully ",
        'redirect' => 'profile.php'
    ]);
} else {
    if ($diffHours >= 24) {
        $sql->prepare("UPDATE users SET kyc_attempts = 1, kyc_last_attempt = NOW() WHERE id = ?")
            ->execute([$user_id]);
    } else {
        $sql->prepare("UPDATE users SET kyc_attempts = kyc_attempts + 1, kyc_last_attempt = NOW() WHERE id = ?")
            ->execute([$user_id]);
    }

    echo json_encode([
        'status' => 'fail',
        'message' => "❌ Face Not Matched (You can retry " . (3 - $userData['kyc_attempts']) . " more time(s)."
    ]);
}
?>
