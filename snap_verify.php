<?php
// snap_verify.php
session_start();
header('Content-Type: application/json');

if(isset($_POST['image_data'])){
    $data = $_POST['image_data'];
    $data = str_replace('data:image/png;base64,', '', $data);
    $data = str_replace(' ', '+', $data);
    $image_data = base64_decode($data);

    $upload_dir = 'uploads/';
    if(!is_dir($upload_dir)) mkdir($upload_dir, 0777, true);
    $file_path = $upload_dir . 'snap_' . time() . '.png';
    file_put_contents($file_path, $image_data);

    // OCR
    $ocr_text = strtolower(shell_exec("tesseract " . escapeshellarg($file_path) . " stdout"));
    file_put_contents($upload_dir.'ocr_debug.txt', $ocr_text);

    // Extract account number
    preg_match_all('/\d{9,12}/', $ocr_text, $matches);
    $account_number = $matches[0][0] ?? null;

    // Extract name
    preg_match('/[a-z ]{3,}/', $ocr_text, $name_match);
    $account_name = trim($name_match[0] ?? '');

    // Bank detection
    $bank_codes = [
        'gtbank' => '058',
        'zenith' => '057',
        'firstbank' => '011',
        'uba' => '033',
        'access' => '044',
        'wema' => '035',
        'polaris' => '076'
        // add more banks as needed
    ];

    $bank_code = null;
    foreach($bank_codes as $bank => $code){
        if(strpos($ocr_text, $bank) !== false){
            $bank_code = $code;
            break;
        }
    }

    if(!$account_number || !$account_name || !$bank_code){
        echo json_encode([
            'success'=>false,
            'message'=>'Could not extract account details or bank. Please resnap.'
        ]);
        exit;
    }

    // Ensure 10-digit account number
    $account_number = substr($account_number, 0, 10);

    // Paystack API
    $paystack_secret_key = "sk_live_5dfd636941e51a27446ee4adcbeff427055bf827";
    $curl = curl_init();
    curl_setopt_array($curl, [
        CURLOPT_URL => "https://api.paystack.co/bank/resolve?account_number=$account_number&bank_code=$bank_code",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_HTTPHEADER => [
            "Authorization: Bearer $paystack_secret_key",
            "Cache-Control: no-cache"
        ],
    ]);

    $response = curl_exec($curl);
    $http_status = curl_getinfo($curl, CURLINFO_HTTP_CODE);
    curl_close($curl);

    if($http_status !== 200){
        echo json_encode(['success'=>false,'message'=>'Error verifying account with Paystack']);
        exit;
    }

    $paystack_data = json_decode($response, true);
    $resolved_name = strtolower(trim($paystack_data['data']['account_name'] ?? ''));

    $name_match = (strtolower($account_name) === $resolved_name);

    echo json_encode([
        'success'=>true,
        'account_number'=>$account_number,
        'account_name'=>$account_name,
        'bank_code'=>$bank_code,
        'resolved_name'=>$paystack_data['data']['account_name'] ?? '',
        'name_match'=>$name_match,
        'verification_result'=>$paystack_data['status'] ?? 'unknown'
    ]);
    exit;
}
?>
