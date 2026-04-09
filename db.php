<?php
if (!defined('FLW_SECRET_KEY')) {
    define('FLW_SECRET_KEY', 'FLWSECK-e64fa04c69f8cc3af508862b529669cc-19742b447b3vt-X');
}

// Database credentials
$host = 'localhost';         // Database host (e.g., localhost or an IP address)
$dbname = 'u822915062_billpay';   // Your database name
$username = 'u822915062_billpay';          // Database username
$password = 'Lotanna@2024';              // Database password (change this to your actual password)

// Create PDO instance
try {
    $sql = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    // Set the PDO error mode to exception
    $sql->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    // If connection fails, display error message
    echo "Connection failed: " . $e->getMessage();
}
?>
