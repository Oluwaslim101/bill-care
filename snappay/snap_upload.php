<?php
session_start();
header('Content-Type: application/json');

if($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['snap_image'])){
    $upload_dir = 'uploads/';
    if(!is_dir($upload_dir)) mkdir($upload_dir, 0777, true);
    
    $file_path = $upload_dir . basename($_FILES['snap_image']['name']);
    move_uploaded_file($_FILES['snap_image']['tmp_name'], $file_path);

    // OCR using Tesseract
    $ocr_text = shell_exec("tesseract " . escapeshellarg($file_path) . " stdout");

    preg_match('/\b\d{10,12}\b/', $ocr_text, $account_match);
    preg_match('/[A-Za-z ]+/', $ocr_text, $name_match);

    $account_number = $account_match[0] ?? null;
    $account_name = trim($name_match[0] ?? '');

    if(!$account_number || !$account_name){
        echo json_encode([
            'success' => false,
            'message' => 'Could not extract account details. Please resnap.'
        ]);
        exit;
    }

    // Forward to DtheHub verification backend
    $verification_api = 'https://swiftaffiliates.cloud/app/verify_account.php';
    $ch = curl_init($verification_api);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query([
        'account_number' => $account_number
    ]));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $verification_response = curl_exec($ch);
    curl_close($ch);

    $verification_result = json_decode($verification_response, true);
    $resolved_name = strtolower(trim($verification_result['data']['account_name'] ?? ''));

    // Compare OCRed name with bank-resolved name
    $ocr_name_lower = strtolower($account_name);
    $name_match = ($ocr_name_lower === $resolved_name);

    echo json_encode([
        'success' => true,
        'account_number' => $account_number,
        'account_name' => $account_name,
        'resolved_name' => $verification_result['data']['account_name'] ?? '',
        'name_match' => $name_match,
        'verification_result' => $verification_result['status'] ?? 'unknown'
    ]);
}
?>
