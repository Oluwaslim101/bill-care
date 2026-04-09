<?php
header('Content-Type: application/json');
error_reporting(E_ALL);
ini_set('display_errors', 0);
ini_set('log_errors', 1);
ini_set('error_log', __DIR__ . '/face_error.log');

include('db.php');
session_start();

$user_id = $_SESSION['user_id'] ?? null;
if (!$user_id) {
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit;
}

if (!isset($_POST['image'])) {
    echo json_encode(['success' => false, 'message' => 'Image data missing']);
    exit;
}

$facepp_api_key = 'JDtgDskaTEIvehWVCjQoEjOh96Kefm1u';
$facepp_api_secret = 'd88nvO2hyLRrUMByHKXoKA0bwVofZq6y';

// 1. Get the user's KYC-stored ID image from DB
$stmt = $sql->prepare("SELECT id_image FROM kyc_verifications WHERE user_id = ?");
$stmt->execute([$user_id]);
$row = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$row || empty($row['id_image'])) {
    echo json_encode(['success' => false, 'message' => 'No KYC image found']);
    exit;
}

$live_image_base64 = $_POST['image'];
$id_image_url = $row['id_image']; // This should be a public URL accessible by Face++

// Remove base64 header
$live_image_base64 = preg_replace('#^data:image/\w+;base64,#i', '', $live_image_base64);

// 2. Upload live image to temporary file
$temp_image_path = __DIR__ . '/temp_face_' . time() . '.jpg';
file_put_contents($temp_image_path, base64_decode($live_image_base64));

// 3. Send both images to Face++ compare API
$facepp_url = 'https://api-us.faceplusplus.com/facepp/v3/compare';

$post_fields = [
    'api_key' => $facepp_api_key,
    'api_secret' => $facepp_api_secret,
    'image_file1' => new CURLFile($temp_image_path),
    'image_url2' => $id_image_url
];

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $facepp_url);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, $post_fields);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

$response = curl_exec($ch);
curl_close($ch);

// Delete temp image
unlink($temp_image_path);

// 4. Handle Face++ response
if (!$response) {
    echo json_encode(['success' => false, 'message' => 'Face++ API error']);
    exit;
}

$data = json_decode($response, true);

if (isset($data['confidence'])) {
    $confidence = $data['confidence'];
    $threshold = $data['thresholds']['1e-3'] ?? 75;

    if ($confidence >= $threshold) {
        echo json_encode(['success' => true, 'confidence' => $confidence]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Face does not match', 'confidence' => $confidence]);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Face comparison failed']);
}
