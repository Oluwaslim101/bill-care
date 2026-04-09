<?php
// Replace these with your actual Nelobyte API key and user ID
$api_user_id = "CK100028738";
$api_key = "U66B634EJ9AE5227OEP008IMIQ6V895814L0E38G90RKV1DFH9ASHARH6876YZH8";

$exam_type = $_POST['exam_type'];

if ($exam_type === 'waec') {
    $exam_number = $_POST['waec_exam_number'];
    $exam_year = $_POST['waec_exam_year'];
    $pin = $_POST['waec_pin'];

    $url = "https://api.nelobyte.com/v1/education/waec/result";
    $data = [
        "user_id" => $user_id,
        "exam_number" => $exam_number,
        "exam_year" => $exam_year,
        "pin" => $pin
    ];
} elseif ($exam_type === 'jamb') {
    $reg_number = $_POST['jamb_reg_number'];
    $exam_year = $_POST['jamb_year'];

    $url = "https://api.nelobyte.com/v1/education/jamb/result";
    $data = [
        "user_id" => $user_id,
        "reg_number" => $reg_number,
        "exam_year" => $exam_year
    ];
} else {
    die("Invalid exam type");
}

$headers = [
    "Content-Type: application/json",
    "Authorization: Bearer $api_key"
];

$ch = curl_init($url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

$response = curl_exec($ch);
$http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

if ($http_code === 200) {
    $result = json_decode($response, true);
    echo "<pre>";
    print_r($result);
    echo "</pre>";
} else {
    echo "Error fetching result. Please check your inputs or try again later.";
}
?>