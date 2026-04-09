<?php
require 'vendor/autoload.php';
use Sonata\GoogleAuthenticator\GoogleAuthenticator;
use Sonata\GoogleAuthenticator\GoogleQrUrl;

session_start();
// Example: assuming you're logged in and have user info in session
$userEmail = $_SESSION['user_email'];

$g = new GoogleAuthenticator();
$secret = $g->generateSecret(); // Save this to DB

// Example QR URL
$qrCodeUrl = GoogleQrUrl::generate('DtheHub', $secret, $userEmail);

// Save secret to DB
// Replace with your DB logic
$pdo = new PDO('mysql:host=localhost;dbname=u822915062_dthehub_utilit', 'u822915062_dthehub_utilit', 'Lotanna@2024');
$stmt = $pdo->prepare("UPDATE users SET google_2fa_secret = ? WHERE email = ?");
$stmt->execute([$secret, $userEmail]);

echo "Scan this QR code with Google Authenticator:<br>";
echo "<img src='$qrCodeUrl' />";
?>
