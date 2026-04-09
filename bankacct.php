<?php
// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Flutterwave Secret Key
$secret_key = "FLWSECK-6f148fbb2fb4e625fa490bc9b3528647-1956425786evt-X";

// Fetch banks if not submitted
function getBanks($secret_key) {
    $url = "https://api.flutterwave.com/v3/banks/NG";
    $headers = [
        "Authorization: Bearer $secret_key"
    ];

    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $response = curl_exec($ch);
    curl_close($ch);

    $result = json_decode($response, true);
    return $result['data'] ?? [];
}

// Handle verification
$verified = false;
$response_msg = "";
$account_name = "";
$banks = getBanks($secret_key);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $account_number = $_POST['account_number'];
    $bank_code = $_POST['bank_code'];

    $url = "https://api.flutterwave.com/v3/accounts/resolve";
    $data = [
        "account_number" => $account_number,
        "account_bank" => $bank_code
    ];
    $headers = [
        "Authorization: Bearer $secret_key",
        "Content-Type: application/json"
    ];

    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $response = curl_exec($ch);
    curl_close($ch);

    $result = json_decode($response, true);

    if ($result && $result['status'] === 'success') {
        $account_name = $result['data']['account_name'];
        $verified = true;
        $response_msg = "Account verified: $account_name";
        // Save to database if needed
    } else {
        $response_msg = "Verification failed. Please check details.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Bank Account Verification</title>
    <style>
        body {
            background: #121212;
            font-family: Arial, sans-serif;
            color: #fff;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .form-container {
            background: #1E1E1E;
            padding: 30px;
            border-radius: 10px;
            width: 400px;
            box-shadow: 0 0 10px rgba(0, 169, 255, 0.2);
        }

        h2 {
            text-align: center;
            margin-bottom: 20px;
        }

        .input-group {
            margin-bottom: 15px;
        }

        select, input {
            width: 100%;
            padding: 10px;
            background: #252525;
            border: none;
            border-radius: 5px;
            color: white;
        }

        input:focus, select:focus {
            outline: 2px solid #00A9FF;
        }

        .submit-btn {
            width: 100%;
            padding: 12px;
            background: #00A9FF;
            border: none;
            color: white;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
        }

        .submit-btn:hover {
            background: #008BD6;
        }

        .message {
            margin-top: 15px;
            text-align: center;
        }
    </style>
</head>
<body>

<div class="form-container">
    <h2>Verify Bank Account</h2>
    <form method="POST" action="">
        <div class="input-group">
            <label>Bank:</label>
            <select name="bank_code" required>
                <option value="">Select Bank</option>
                <?php foreach ($banks as $bank): ?>
                    <option value="<?= htmlspecialchars($bank['code']) ?>">
                        <?= htmlspecialchars($bank['name']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="input-group">
            <label>Account Number:</label>
            <input type="text" name="account_number" required pattern="\d{10}" maxlength="10" placeholder="10-digit Account Number">
        </div>
        <button type="submit" class="submit-btn">Verify</button>

        <?php if ($response_msg): ?>
            <div class="message" style="color: <?= $verified ? 'lightgreen' : 'red' ?>;">
                <?= htmlspecialchars($response_msg) ?>
            </div>
        <?php endif; ?>

        <?php if ($verified): ?>
            <div class="message">
                <strong>Account Name:</strong> <?= htmlspecialchars($account_name) ?>
            </div>
        <?php endif; ?>
    </form>
</div>

</body>
</html>