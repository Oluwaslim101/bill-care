<?php
include('db.php');
$message = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $phone = $_POST['phone'];
    $otp_input = implode("", $_POST['otp_code']);

    $stmt = $sql->prepare("SELECT * FROM users WHERE phone_number = ? AND otp_code = ?");
    $stmt->execute([$phone, $otp_input]);

    if ($stmt->rowCount() > 0) {
        $update = $sql->prepare("UPDATE users SET otp_verified = 1, otp_code = NULL WHERE phone_number = ?");
        $update->execute([$phone]);
        $message = "<p style='color: green;'>OTP verified successfully! <a href='login.php'>Login now</a></p>";
    } else {
        $message = "<p style='color: red;'>Invalid OTP. Please try again.</p>";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Verify OTP</title>
    <style>
        body {
            background: #121212;
            color: white;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            font-family: Arial;
        }
        .container {
            background: #1E1E1E;
            padding: 30px;
            border-radius: 8px;
            width: 300px;
        }
        .otp-box {
            width: 40px;
            padding: 10px;
            margin: 5px;
            text-align: center;
            font-size: 18px;
            border-radius: 5px;
            border: none;
            background: #252525;
            color: white;
        }
        .submit-btn {
            width: 100%;
            padding: 12px;
            background: #00A9FF;
            border: none;
            border-radius: 5px;
            color: white;
            font-size: 16px;
            cursor: pointer;
        }
    </style>
</head>
<body>

<div class="container">
    <h3>Enter OTP</h3>
    <form method="POST" action="otp_verification_page.php">
        <input type="hidden" name="phone" value="<?= htmlspecialchars($_GET['phone'] ?? '') ?>">
        <div style="display: flex; justify-content: center;">
            <?php for ($i = 0; $i < 6; $i++): ?>
                <input type="text" name="otp_code[]" maxlength="1" class="otp-box" required>
            <?php endfor; ?>
        </div>
        <br>
        <button type="submit" class="submit-btn">Verify</button>
    </form>
    <div class="message">
        <?= $message ?>
    </div>
</div>

</body>
</html>