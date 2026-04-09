<?php
$host = getenv('DB_HOST') ?: 'mainline.proxy.rlwy.net';
$port = getenv('DB_PORT') ?: 14373;
$dbname = getenv('DB_NAME') ?: 'railway';
$username = getenv('DB_USER') ?: 'root';
$password = getenv('DB_PASS') ?: 'FeZDtcpXMciupbsCJrGLisMfByoHxGJS';

try {
    $sql = new PDO(
        "mysql:host=$host;port=$port;dbname=$dbname;charset=utf8mb4",
        $username,
        $password
    );
    $sql->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Database connection failed.");
}
?>
