<?php
// Eror reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

require 'vendor/autoload.php'; // Include the Composer autoload file

// pusher_config.php
$host = 'localhost';
$db = 'u822915062_dthehub_utilit';
$user = 'u822915062_dthehub_utilit';
$pass = 'Lotanna@2024';
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (\PDOException $e) {
    throw new \PDOException($e->getMessage(), (int)$e->getCode());
}


$pusher = new Pusher\Pusher(
    'd721521425aa5a667ee5',    // Pusher app key
    '137633a690181394ed8b', // Pusher app secret
    '1909189',     // Pusher app ID
    array(
        'cluster' => 'us2',
        'useTLS' => true
    )
);
