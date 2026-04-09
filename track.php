<?php
include 'db.php';

// Sanitize inputs
$name = $_POST['name'] ?? 'Guest';
$email = $_POST['email'] ?? 'unknown';
$page = $_POST['page'] ?? 'unknown';

$ip = $_SERVER['REMOTE_ADDR'];
$userAgent = $_SERVER['HTTP_USER_AGENT'];
$time = date('Y-m-d H:i:s');

// Get location
$geoData = @file_get_contents("http://ip-api.com/json/$ip");
$location = "Unknown";
if ($geoData !== false) {
    $geo = json_decode($geoData, true);
    if ($geo['status'] === 'success') {
        $location = "{$geo['city']}, {$geo['regionName']}, {$geo['country']}";
    }
}

// Check if user already exists
$stmt = $sql->prepare("SELECT id FROM visitor_activity WHERE email = ?");
$stmt->execute([$email]);

if ($stmt->rowCount() > 0) {
    // Update
    $sql->prepare("UPDATE visitor_activity SET name=?, location=?, current_page=?, user_agent=?, ip_address=?, last_active=? WHERE email=?")
        ->execute([$name, $location, $page, $userAgent, $ip, $time, $email]);
} else {
    // Insert
    $sql->prepare("INSERT INTO visitor_activity (name, email, location, current_page, user_agent, ip_address, last_active)
        VALUES (?, ?, ?, ?, ?, ?, ?)")
        ->execute([$name, $email, $location, $page, $userAgent, $ip, $time]);
}
?>