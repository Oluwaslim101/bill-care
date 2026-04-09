<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

include('db.php');
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';

    if (empty($email) || empty($password)) {
        echo json_encode(["status" => "error", "message" => "Please enter email and password."]);
        exit();
    }

    $stmt = $sql->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$user) {
        echo json_encode(["status" => "error", "message" => "User not found."]);
        exit();
    }

    if (empty($user['otp_verified']) || $user['otp_verified'] == 0) {
        echo json_encode([
            "status" => "otp_required",
            "redirect" => "verify_otp.php?email=" . urlencode($email)
        ]);
        exit();
    }

    if (!password_verify($password, $user['password'])) {
        echo json_encode(["status" => "error", "message" => "Invalid password."]);
        exit();
    }

    session_regenerate_id(true);
    $_SESSION['user_id'] = $user['id'];
    $_SESSION['email'] = $user['email'];
    $_SESSION['full_name'] = $user['full_name'];
    $_SESSION['balance'] = $user['balance'];
    $_SESSION['avatar_url'] = $user['avatar_url'];

    echo json_encode(["status" => "success", "redirect" => "index.php"]);
    exit();
}
?>


<!DOCTYPE html>
<html class="light" lang="en">
<head>
<meta charset="utf-8"/>
<meta content="width=device-width, initial-scale=1.0" name="viewport"/>
<title>Fintech Login</title>

<link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700;800&display=swap" rel="stylesheet"/>
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" rel="stylesheet"/>

<script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>

<script>
tailwind.config = {
darkMode: "class",
theme: {
extend: {
colors: {
"primary": "#13ec80",
"background-light": "#f6f8f7",
"background-dark": "#102219",
},
fontFamily: {
"display": ["Manrope", "sans-serif"]
}
}
}
}
</script>

</head>

<body class="bg-background-light dark:bg-background-dark font-display min-h-screen flex items-center justify-center">

<div class="relative w-full max-w-[480px] min-h-screen flex flex-col bg-background-light dark:bg-background-dark shadow-2xl">

<div class="w-full h-[260px] bg-center bg-cover bg-no-repeat rounded-b-[2rem]"
style="background-image: url('https://lh3.googleusercontent.com/aida-public/AB6AXuBqjJXf4gCrwV9d8Ub0bo06AfUvd1c9BTWDbLD82idx3-cnr8rogp0f7kaCDnqP8YvUscHCIMhF52A-t5FxiSGjaqqFDvjozkLESrObEOqA54e9xsjQmPmRIERXy8oXF-wGnuy86Y6V2iQbj7ZJomLsdes0qNKrvHc1scalKgdNTlBWw8HJpE7QQiT8FrqW6qETcl3qlyzi2oexRuQ2y9lCbCeycFQIwD0VcTXvnwTz_iDvOv0NOzHzmX9aOSbYi7hnHHjbPo8LJBw1');">
</div>

<div class="flex-1 px-6 -mt-10">

<div class="bg-white dark:bg-[#1a2e24] p-6 rounded-2xl shadow mb-6">
<h1 class="text-center text-3xl font-extrabold">Welcome back!</h1>
<p class="text-center text-gray-500">Pay your bills easily and securely.</p>
</div>

<form id="loginForm" class="flex flex-col gap-5">

<div>
<label class="text-sm font-semibold">Email</label>
<input name="email" required
class="w-full h-14 rounded-lg border p-4 bg-white dark:bg-[#1a2e24]"
placeholder="Enter email">
</div>

<div>
<label class="text-sm font-semibold">Password</label>
<input name="password" type="password" required
class="w-full h-14 rounded-lg border p-4 bg-white dark:bg-[#1a2e24]"
placeholder="Enter password">
</div>

<div class="text-right">
<a href="forgot_password.php" class="text-sm font-bold">Forgot Password?</a>
</div>

<button id="loginBtn"
class="bg-primary w-full h-14 rounded-lg font-bold">
<span id="loginText">Log In</span>
<span id="spinner" class="hidden">...</span>
</button>

<p id="errorMessage" class="text-center text-red-500"></p>

</form>

<div class="text-center mt-6">
<p class="text-gray-500">Don't have an account?</p>
<a href="signup.php" class="font-bold">Sign Up</a>
</div>

</div>
</div>

<script>
document.getElementById("loginForm").addEventListener("submit", async function(e) {
e.preventDefault();

let btn = document.getElementById("loginBtn");
let text = document.getElementById("loginText");
let spinner = document.getElementById("spinner");
let error = document.getElementById("errorMessage");

btn.disabled = true;
text.classList.add("hidden");
spinner.classList.remove("hidden");

let formData = new FormData(this);

try {
let res = await fetch("login.php", {
method: "POST",
body: formData
});

let data = await res.json();

if (data.status === "success" || data.status === "otp_required") {
window.location.href = data.redirect;
} else {
error.innerText = data.message;
}

} catch (e) {
error.innerText = "Login failed. Try again.";
}

btn.disabled = false;
text.classList.remove("hidden");
spinner.classList.add("hidden");
});
</script>

</body>
</html>
