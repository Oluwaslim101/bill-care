<?php
session_start();

// Database connection
$servername = "localhost";
$username = "u822915062_billpay";
$password = "Lotanna@2024";
$dbname = "u822915062_billpay";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get referrer
$referred_code = $_GET['ref'] ?? null;
$referred_by_id = null;
$referred_by_name = null;

if ($referred_code) {
    $stmt = $conn->prepare("SELECT id, full_name FROM users WHERE referral_code = ?");
    $stmt->bind_param("s", $referred_code);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($row = $result->fetch_assoc()) {
        $referred_by_id = $row['id'];
        $referred_by_name = $row['full_name'];
    }
}

function normalizePhone($phone) {
    $phone = preg_replace('/\D/', '', $phone);
    if (substr($phone, 0, 1) === '0') {
        $phone = '234' . substr($phone, 1);
    }
    if (substr($phone, 0, 3) !== '234') {
        $phone = '234' . $phone;
    }
    return '+' . $phone;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $full_name = mysqli_real_escape_string($conn, $_POST['full_name']);
    $nick_name = mysqli_real_escape_string($conn, $_POST['nick_name']);
    $phone_number = preg_replace('/[^0-9]/', '', $_POST['phone_number']);
    if (substr($phone_number, 0, 1) === '0') {
        $phone_number = '+234' . substr($phone_number, 1);
    } elseif (!str_starts_with($phone_number, '+234')) {
        $phone_number = '+234' . $phone_number;
    }
    $email = trim(filter_var($_POST['email'], FILTER_SANITIZE_EMAIL));
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $_SESSION['error'] = "Invalid email address.";
        header('Location: signup.php');
        exit();
    }
    $pin = mysqli_real_escape_string($conn, $_POST['pin']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);
    $referred_by_id = isset($_POST['referred_by_id']) && is_numeric($_POST['referred_by_id']) ? intval($_POST['referred_by_id']) : null;

    if (!preg_match('/^\d{4}$/', $pin)) {
        $_SESSION['error'] = "PIN must be exactly 4 digits.";
        header('Location: signup.php');
        exit();
    }

    $check_sql = "SELECT * FROM users WHERE email = '$email' OR phone_number = '$phone_number'";
    $result = $conn->query($check_sql);
    if ($result->num_rows > 0) {
        $_SESSION['error'] = "Email or Phone number already exists.";
        header('Location: signup.php');
        exit();
    }

    $norm_phone = normalizePhone($phone_number);
    $nameParts = explode(' ', trim($full_name));
    $firstName = $nameParts[0] ?? '';
    $lastName = isset($nameParts[1]) ? implode(' ', array_slice($nameParts, 1)) : '';

    $secretKey = 'sk_live_5dfd636941e51a27446ee4adcbeff427055bf827';

    // Paystack Customer
    $ch = curl_init("https://api.paystack.co/customer");
    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_TIMEOUT => 15,
        CURLOPT_POST => true,
        CURLOPT_POSTFIELDS => json_encode([
            "email" => $email,
            "first_name" => $firstName,
            "last_name" => $lastName,
            "phone" => $norm_phone
        ]),
        CURLOPT_HTTPHEADER => [
            "Authorization: Bearer $secretKey",
            "Content-Type: application/json"
        ]
    ]);
    $customerResRaw = curl_exec($ch);
    curl_close($ch);
    $customerRes = json_decode($customerResRaw, true);
    if (!$customerRes || !$customerRes['status']) {
        $_SESSION['error'] = "Failed to create Paystack customer: " . ($customerResRaw ?? 'No response');
        header('Location: signup.php');
        exit();
    }
    $customerCode = $customerRes['data']['customer_code'];

    // Paystack Dedicated Account
    $ch = curl_init("https://api.paystack.co/dedicated_account");
    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_TIMEOUT => 15,
        CURLOPT_POST => true,
        CURLOPT_POSTFIELDS => json_encode([
            "customer" => $customerCode,
            "preferred_bank" => "wema-bank"
        ]),
        CURLOPT_HTTPHEADER => [
            "Authorization: Bearer $secretKey",
            "Content-Type: application/json"
        ]
    ]);
    $acctResRaw = curl_exec($ch);
    curl_close($ch);
    $acctRes = json_decode($acctResRaw, true);
    if (!$acctRes || !$acctRes['status']) {
        $_SESSION['error'] = "Failed to create Paystack virtual account: " . ($acctResRaw ?? 'No response');
        header('Location: signup.php');
        exit();
    }

    // Insert user after successful Paystack setup
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
    $otp_code = mt_rand(100000, 999999);
    $referral_code = uniqid('ref_', true);

    $stmt = $conn->prepare("INSERT INTO users (full_name, nick_name, phone_number, email, pin, password, otp_code, otp_verified, referral_code, referred_by, paystack_customer_code, virtual_account_number, bank_name, account_name) VALUES (?, ?, ?, ?, ?, ?, ?, 0, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param(
        "sssssssssssss",
        $full_name,
        $nick_name,
        $phone_number,
        $email,
        $pin,
        $hashed_password,
        $otp_code,
        $referral_code,
        $referred_by_id,
        $customerCode,
        $acctRes['data']['account_number'],
        $acctRes['data']['bank']['name'],
        $acctRes['data']['account_name']
    );
    $stmt->execute();
    $stmt->close();

    // Send OTP SMS
    $termii_payload = array(
        "to" => $phone_number,
        "from" => "Bill Care",
        "sms" => "$otp_code .",
        "type" => "plain",
        "channel" => "generic",
        "api_key" => "TLZugUiorQTxeDaIyTOScmNBcoTnuzYFKQiPHHSptRcwLsNTdzwRFDHVWptPOm"
    );
    $curl = curl_init();
    curl_setopt_array($curl, array(
        CURLOPT_URL => "https://v3.api.termii.com/api/sms/send",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_POST => true,
        CURLOPT_POSTFIELDS => json_encode($termii_payload),
        CURLOPT_HTTPHEADER => array("Content-Type: application/json")
    ));
    curl_exec($curl);
    curl_close($curl);

    // Send OTP email
    $headers = "MIME-Version: 1.0\r\n" .
        "Content-type:text/html;charset=UTF-8\r\n" .
        'From: no_reply@billcare.shop' . "\r\n";
    mail($email, "Your Verification OTP Code", "<html><body><h2>Hello $full_name,</h2><p>Your OTP:</p><h1>$otp_code</h1><p>Enter it to verify your account.</p></body></html>", $headers);

    // --- REDIRECT TO VERIFY OTP ---
    $_SESSION['email'] = $email;
    $_SESSION['referral_code'] = $referral_code;
    header('Location: verify_otp.php');
    exit();
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Sign Up - DtheHub</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/zxcvbn/4.4.2/zxcvbn.js"></script> <!-- Password strength -->

</head>
<body class="min-h-screen flex items-center justify-center bg-gray-100 p-4">

<div class="w-full max-w-lg bg-white shadow-lg rounded-2xl p-6 md:p-8">

    <div class="text-center mb-6">
        <img src="https://digishubb.com/uploads/20250118_125516_0000.png" 
             alt="Logo" class="mx-auto w-28 mb-4">
        <h2 class="text-2xl font-bold text-gray-800">Create Account</h2>
        <p class="text-sm text-gray-500">Join DtheHub today</p>
    </div>

    <?php
    if (isset($_SESSION['success'])) {
        echo '<div class="bg-green-100 text-green-700 p-3 rounded mb-4 text-center">'
            . $_SESSION['success'] . '</div>';
        unset($_SESSION['success']);
    }
    if (isset($_SESSION['error'])) {
        echo '<div class="bg-red-100 text-red-700 p-3 rounded mb-4 text-center">'
            . $_SESSION['error'] . '</div>';
        unset($_SESSION['error']);
    }
    ?>

    <!-- SOCIAL LOGIN -->
    <div class="grid grid-cols-2 gap-3 mb-6">
        <!-- Google OAuth -->
        <a href="oauth/google.php"
           class="border rounded-lg py-2 flex items-center justify-center gap-2 hover:bg-gray-50 transition">
            <img src="https://img.icons8.com/color/24/google-logo.png"/>
            Sign in with Google
        </a>

        <!-- Facebook OAuth -->
        <a href="oauth/facebook.php"
           class="border rounded-lg py-2 flex items-center justify-center gap-2 hover:bg-gray-50 transition">
            <img src="https://img.icons8.com/color/24/facebook-new.png"/>
            Sign in with Facebook
        </a>
    </div>

    <div class="flex items-center mb-4">
        <hr class="flex-grow border-gray-300">
        <span class="px-2 text-gray-400 text-sm">OR</span>
        <hr class="flex-grow border-gray-300">
    </div>

    <form method="POST" action="" class="space-y-4" id="signupForm">

        <input type="text" name="full_name"
               class="w-full p-3 border rounded-lg focus:ring-2 focus:ring-blue-400 outline-none"
               placeholder="Full Name" required>

        <input type="text" name="nick_name"
               class="w-full p-3 border rounded-lg focus:ring-2 focus:ring-blue-400 outline-none"
               placeholder="Nick Name" required>

        <input type="text" name="phone_number"
               class="w-full p-3 border rounded-lg focus:ring-2 focus:ring-blue-400 outline-none"
               placeholder="Phone Number" required
               oninput="this.value=this.value.replace(/[^0-9]/g,'');">

        <input type="email" name="email"
               class="w-full p-3 border rounded-lg focus:ring-2 focus:ring-blue-400 outline-none"
               placeholder="Email Address" required>

        <!-- PASSWORD + SHOW/HIDE + STRENGTH -->
        <div class="relative">
            <input type="password" name="password" id="password"
                   class="w-full p-3 border rounded-lg focus:ring-2 focus:ring-blue-400 outline-none"
                   placeholder="Password" required oninput="checkPasswordStrength()">

            <button type="button" id="togglePassword"
                    class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-500 hover:text-gray-700">
                Show
            </button>
        </div>

        <div class="text-sm mt-1">
            <span id="passwordStrength" class="font-semibold"></span>
        </div>

        <input type="number" name="pin"
               class="w-full p-3 border rounded-lg focus:ring-2 focus:ring-blue-400 outline-none"
               placeholder="4 Digit PIN" required
               oninput="this.value=this.value.replace(/[^0-9]/g,'').slice(0,4);">

        <?php if ($referred_by_name): ?>
        <div>
            <input type="text"
                   class="w-full p-3 border rounded-lg bg-gray-100 text-gray-600"
                   value="<?= htmlspecialchars($referred_by_name); ?>"
                   readonly>

            <input type="hidden" name="referred_by_id" value="<?= $referred_by_id ?>">
            <small class="text-gray-500">
                Referred by <strong><?= htmlspecialchars($referred_by_name); ?></strong>
            </small>
        </div>
        <?php endif; ?>

        <!-- TERMS CHECKBOX -->
        <div class="flex items-start gap-2">
            <input type="checkbox" id="terms" name="terms" required class="mt-1">
            <label for="terms" class="text-sm text-gray-600">
                I agree to the 
                <a href="terms.php" class="text-blue-600 underline">Terms & Conditions</a>
                and
                <a href="privacy.php" class="text-blue-600 underline">Privacy Policy</a>
            </label>
        </div>

        <button type="submit"
                class="w-full bg-blue-600 text-white py-3 rounded-lg font-semibold hover:bg-blue-700 transition">
            Create Account
        </button>

    </form>

    <div class="text-center mt-5">
        <p class="text-sm text-gray-600">
            Already have an account?
            <a href="login.php" class="text-blue-600 font-semibold">Login</a>
        </p>
    </div>

</div>

<script>
    // Show / Hide Password
    const passwordInput = document.getElementById('password');
    const toggleBtn = document.getElementById('togglePassword');

    toggleBtn.addEventListener('click', () => {
        if (passwordInput.type === 'password') {
            passwordInput.type = 'text';
            toggleBtn.textContent = 'Hide';
        } else {
            passwordInput.type = 'password';
            toggleBtn.textContent = 'Show';
        }
    });

    // Password strength check using zxcvbn
    function checkPasswordStrength() {
        const pass = passwordInput.value;
        const result = zxcvbn(pass);
        const strengthText = ['Very Weak', 'Weak', 'Fair', 'Good', 'Strong'];
        const colorClass = ['text-red-500','text-orange-500','text-yellow-500','text-blue-500','text-green-600'];
        const strengthElem = document.getElementById('passwordStrength');

        if(pass.length === 0){
            strengthElem.textContent = '';
        } else {
            strengthElem.textContent = strengthText[result.score];
            strengthElem.className = 'font-semibold ' + colorClass[result.score];
        }
    }
</script>

</body>
</html>
