<?php
// Start the session to access user data (assuming user is logged in)
session_start();


// Set Nellobyte API credentials
$userID = "CK100028738";
$apiKey = "U66B634EJ9AE5227OEP008IMIQ6V895814L0E38G90RKV1DFH9ASHARH6876YZH8";

// Check if necessary data is received from the request
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $pin = isset($_POST['pin']) ? trim($_POST['pin']) : '';
    $amount = isset($_POST['amount']) ? floatval($_POST['amount']) : 0;
    $phoneNumber = isset($_POST['phoneNumber']) ? trim($_POST['phoneNumber']) : '';
    $network = isset($_POST['network']) ? trim($_POST['network']) : '';

    // Check if all data is present
    if (empty($pin) || empty($amount) || empty($phoneNumber) || empty($network)) {
        echo json_encode(['success' => false, 'error_title' => 'Missing Data', 'error_message' => 'Please provide all required information.']);
        exit;
    }

    // Validate the pin (This can be a check against your database or session)
    // Assuming user has a session with their PIN stored
    $userPin = isset($_SESSION['user_pin']) ? $_SESSION['user_pin'] : null;

    // Check if the PIN entered by the user matches the stored PIN
    if ($pin !== $userPin) {
        echo json_encode(['success' => false, 'error_title' => 'Invalid PIN', 'error_message' => 'The PIN entered is incorrect.']);
        exit;
    }

    // Build API request URL for airtime purchase
    $apiUrl = "https://www.nellobytesystems.com/APIAirtimeV1.asp"
        . "?UserID=" . urlencode($userID)
        . "&APIKey=" . urlencode($apiKey)
        . "&MobileNetwork=" . urlencode($network)
        . "&Amount=" . urlencode($amount)
        . "&MobileNumber=" . urlencode($phoneNumber);

    // Initialize cURL to send the request
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $apiUrl);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $response = curl_exec($ch);

    // Check for errors in cURL
    if (curl_errno($ch)) {
        echo json_encode(['success' => false, 'error_title' => 'Request Failed', 'error_message' => 'cURL Error: ' . curl_error($ch)]);
        curl_close($ch);
        exit;
    }

    // Close the cURL session
    curl_close($ch);

    // Process the API response
    $response = trim($response);
    $responseData = json_decode($response, true);

    // Check the response for success
    if (isset($responseData['statuscode']) && $responseData['statuscode'] == "100") {
        // Successful transaction
        echo json_encode([
            'success' => true,
            'message' => 'Airtime has been sent successfully!',
        ]);
    } else {
        // Handle error in the response
        $errorMessage = isset($responseData['message']) ? $responseData['message'] : 'There was an issue processing your transaction.';
        echo json_encode([
            'success' => false,
            'error_title' => 'Transaction Failed',
            'error_message' => $errorMessage,
        ]);
    }

} else {
    // Invalid request method
    echo json_encode(['success' => false, 'error_title' => 'Invalid Request', 'error_message' => 'Invalid request method.']);
}
