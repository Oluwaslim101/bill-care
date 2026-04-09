<?php

<?ph
// Load environment variables (with fallback for local dev)
$host     = getenv('DB_HOST') ?: 'localhost';
$dbname   = getenv('DB_NAME') ?: 'u822915062_billpay';
$username = getenv('DB_USER') ?: 'u822915062_billpay';
$password = getenv('DB_PASS') ?: 'Lotanna@2024';

try {
    $sql = new PDO(
        "mysql:host=$host;dbname=$dbname;charset=utf8mb4",
        $username,
        $password
    );

    // Set PDO error mode
    $sql->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

} catch (PDOException $e) {
    // Safer error handling (don’t expose full details in production)
    die("Database connection failed.");
}
?>
