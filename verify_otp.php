<?php
session_start();

// Database connection
$servername = "localhost";
$username   = "u822915062_billpay";
$password   = "Lotanna@2024";
$dbname     = "u822915062_billpay";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Database Connection Failed: " . $conn->connect_error);
}

// Ensure email session exists
if (!isset($_SESSION['email'])) {
    header('Location: signup.php');
    exit();
}

$email = $_SESSION['email'];


// ====================== OTP VERIFICATION ======================
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['otp'])) {

    $entered_otp = trim($_POST['otp']);

    if (!preg_match('/^\d{6}$/', $entered_otp)) {
        $_SESSION['error'] = "Invalid OTP format.";
    } else {

        $stmt = $conn->prepare("SELECT id FROM users WHERE email = ? AND otp_code = ?");
        $stmt->bind_param("ss", $email, $entered_otp);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows == 1) {

            // OTP CORRECT
            $update = $conn->prepare("UPDATE users SET otp_verified = 1, otp_code = NULL WHERE email = ?");
            $update->bind_param("s", $email);
            $update->execute();

            unset($_SESSION['email']);

            // Set success message only – NO PHP REDIRECT
            $_SESSION['success'] = "OTP verified successfully! Redirecting to login...";

        } else {
            $_SESSION['error'] = "Invalid OTP code. Please try again.";
        }

        $stmt->close();
    }
}


// ====================== RESEND OTP ======================
if (isset($_POST['resend_otp'])) {

    $otp_code = mt_rand(100000, 999999);

    $stmt = $conn->prepare("UPDATE users SET otp_code = ? WHERE email = ?");
    $stmt->bind_param("ss", $otp_code, $email);
    $stmt->execute();
    $stmt->close();


    // Get user details
    $stmt = $conn->prepare("SELECT full_name, phone_number FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    $full_name   = $user['full_name'];
    $phoneNumber = $user['phone_number'];


    // ============ SEND OTP BY EMAIL ============
    $to = $email;
    $subject = "Your OTP Code";

    $message = "
    <html>
    <head><title>OTP Verification</title></head>
    <body>
      <h2>Hello $full_name,</h2>
      <p>Your OTP code is:</p>
      <h1>$otp_code</h1>
      <p>Please enter this code to verify your account.</p>
    </body>
    </html>
    ";

    $headers = "MIME-Version: 1.0\r\n";
    $headers .= "Content-type:text/html;charset=UTF-8\r\n";
    $headers .= "From: No_reply@billcare.shop\r\n";

    mail($to, $subject, $message, $headers);


    // ============ SEND OTP BY SMS (TERMII) ============
    $sms_message = "Hello $full_name, your OTP for BillCare verification is: $otp_code";

    $termii_api_key = "YOUR_TERMII_API_KEY";
    $termii_sender  = "Bill Care";

    $payload = [
        "to"      => $phoneNumber,
        "from"    => $termii_sender,
        "sms"     => $sms_message,
        "type"    => "plain",
        "channel" => "generic",
        "api_key" => $termii_api_key
    ];

    $curl = curl_init();

    curl_setopt_array($curl, [
        CURLOPT_URL => "https://v3.api.termii.com/api/sms/send",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_POST => true,
        CURLOPT_POSTFIELDS => json_encode($payload),
        CURLOPT_HTTPHEADER => ["Content-Type: application/json"]
    ]);

    curl_exec($curl);
    curl_close($curl);


    $_SESSION['success'] = "A new OTP has been sent to your email and phone number.";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Verify OTP - Bill Care Shop</title>
<meta name="viewport" content="width=device-width, initial-scale=1">

<script src="https://cdn.tailwindcss.com"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<style>
body { background-color: #f3f6fb; }
</style>
</head>

<body class="flex items-center justify-center min-h-screen p-4">

<div class="w-full max-w-md bg-white rounded-2xl shadow-lg p-6">

<div class="text-center mb-6">
<img src="assets/img/logo.png" class="mx-auto w-24 mb-4">
<h2 class="text-2xl font-bold">Verify Your Account</h2>
<p class="text-gray-500">Enter the OTP sent to your email & phone</p>
</div>


<form method="POST" id="otpForm" class="space-y-4">

<?php if(isset($_SESSION['error'])): ?>
<div class="bg-red-100 text-red-700 p-3 rounded text-center">
<?= $_SESSION['error']; unset($_SESSION['error']); ?>
</div>
<?php endif; ?>


<input type="text"
name="otp"
id="otp"
maxlength="6"
pattern="\d{6}"
inputmode="numeric"
required
placeholder="******"
class="w-full p-4 border rounded-lg text-center text-xl tracking-widest">


<button type="submit"
class="w-full py-3 bg-blue-600 text-white rounded-lg font-semibold">
Verify OTP
</button>

</form>



<div class="text-center mt-4">
<div id="countdown" class="text-gray-500 text-sm">
Resend OTP in <span id="timer">60</span> seconds
</div>

<form method="POST">
<button type="submit"
name="resend_otp"
id="resendBtn"
disabled
class="mt-2 text-blue-600 font-semibold">
Resend OTP
</button>
</form>
</div>

</div>



<script>

// Countdown Timer
let timer = 60;
const timerElem = document.getElementById('timer');
const resendBtn = document.getElementById('resendBtn');

const interval = setInterval(() => {
timer--;
timerElem.textContent = timer;

if(timer <= 0){
clearInterval(interval);
document.getElementById('countdown').textContent = "";
resendBtn.disabled = false;
}
}, 1000);


// Auto-submit when 6 digits entered
document.getElementById('otp').addEventListener('input', function() {
if(this.value.length === 6){
document.getElementById('otpForm').submit();
}
});


// SUCCESS MESSAGE WITH REDIRECT AFTER TOAST
<?php if(isset($_SESSION['success'])): ?>

Swal.fire({
icon: 'success',
title: 'Success!',
text: '<?= $_SESSION['success']; ?>',
timer: 2500,
showConfirmButton: false,
willClose: () => {
window.location.href = 'login.php';
}
});

<?php unset($_SESSION['success']); endif; ?>

</script>

</body>
</html>
