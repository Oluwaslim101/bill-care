<?php

// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Include database connection
include('db.php');

// Start session
session_start();

// Check if user is logged in
if (!isset($_SESSION['user_id']) || empty($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

// Get user ID from session
$user_id = $_SESSION['user_id'];

// Fetch user data
$query = "SELECT * FROM users WHERE id = ?";
$stmt = $sql->prepare($query);
$stmt->execute([$user_id]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user) {
    header('Location: login.php');
    exit();
}

// Fetch unread notifications
$notifications_query = "SELECT * FROM notifications WHERE user_id = ? AND status = 'unread'";
$notifications_stmt = $sql->prepare($notifications_query);
$notifications_stmt->execute([$user_id]);
$unread_count = $notifications_stmt->rowCount();

if (!isset($_GET['ref'])) {
    die('Transaction reference is missing.');
}

$transaction_ref = $_GET['ref'];

$stmt = $sql->prepare("SELECT w.*, u.full_name, u.email, wm.name FROM withdrawals w 
    JOIN users u ON w.user_id = u.id 
    JOIN withdrawal_methods wm ON w.method_id = wm.id 
    WHERE w.transaction_ref = :ref");
$stmt->execute([':ref' => $transaction_ref]);
$withdrawal = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$withdrawal) {
    die('Receipt not found.');
}

?>

<!DOCTYPE html>  
<html lang="en">    <head>    

    <meta charset="UTF-8">    

<meta name="viewport"
        content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1, viewport-fit=cover" />
    <meta name="apple-mobile-web-app-capable" content="yes" />
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <meta name="theme-color" content="#000000">
    <title>YenTown Hub</title>
    <meta name="description" content="Finapp HTML Mobile Template">
    <meta name="keywords" content="bootstrap, wallet, banking, fintech mobile template, cordova, phonegap, mobile, html, responsive" />
    <link rel="icon" type="image/png" href="assets/img/favicon.png" sizes="32x32">
    <link rel="apple-touch-icon" sizes="180x180" href="assets/img/icon/192x192.png">
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="manifest" href="__manifest.json">
        <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <link rel="stylesheet" href="styles.css"> 

    <style>

/* Fixed Bottom Navigation */
.nav {
position: fixed;
bottom: 0;
left: 50%;
transform: translateX(-50%);
width: 100%;
max-width: 410px;
display: flex;
justify-content: space-around;
background: white;
padding: 12px 7px;
border-radius: 8px 8px 0 0;
box-shadow: 0px -2px 10px rgba(0, 0, 0, 0.1);
z-index: 1000;
}

.nav a {
text-decoration: none;
color: gray;
font-size: 12px;
text-align: center;
display: flex;
flex-direction: column;
align-items: center;
gap: 3px;
flex: 1;
transition: color 0.3s ease;
}

.nav a i {
font-size: 20px;
color: gray;
transition: color 0.3s ease;
}

.nav a span {
font-size: 12px;
font-weight: 500;
}

.nav a.active i,
.nav a.active span {
color: green;
font-weight: bold;
}

</style>  
</head>
<body>

<!-- Loader -->
<div id="loader">
    <img src="assets/img/loading-icon.png" alt="icon" class="loading-icon">
</div>

 <!-- App Header -->
<div class="appHeader">
    <div class="left">
        <a href="#" class="headerButton goBack">
            <ion-icon name="chevron-back-outline"></ion-icon>
        </a>
    </div>
    <div class="pageTitle">Transactions Details
    </div>
    <div class="right">
        <div style="position: relative;">
            <i class="fas fa-bell" style="font-size: 25px; color: blue;"></i>
            <?php if ($unread_count > 0): ?>
                <span style="
                    position: absolute;
                    top: -5px;
                    right: -5px;
                    background: red;
                    color: white;
                    font-size: 10px;
                    font-weight: bold;
                    padding: 2px 6px;
                    border-radius: 50%;
                ">
                    <?= $unread_count ?>
                </span>
            <?php endif; ?>
        </div>
    </div>
</div>


<!-- App Capsule -->

<div id="appCapsule" >


    <div class="section full" style="padding-left: 16px; padding-right: 16px;">


        <div class="listed-detail mt-1">
            <div class="icon-wrapper">
                <div class="iconbox">
                    <ion-icon name="arrow-forward-outline"></ion-icon>
                </div>
            </div>
            <h3 class="text-center mt-2">Withdrawal Receipt</h3>
        </div>

        <ul class="listview flush transparent simple-listview no-space mt-3">
            <li>
                
    <strong>Status</strong>
    <span class="text-<?= ($withdrawal['status'] == 'pending') ? 'danger' : 'success' ?>">
        <?= ucfirst($withdrawal['status']) ?>
    </span>
            </li>
            <li>
                <strong>Transaction Reference</strong>
                <span>#<?= htmlspecialchars($withdrawal['transaction_ref']) ?></span>
            </li>
            <li>
                <strong>Full Name</strong>
                <span><?= htmlspecialchars($withdrawal['full_name']) ?></span>
            </li>
            <li>
                <strong>Email</strong>
                <span><?= htmlspecialchars($withdrawal['email']) ?></span>
            </li>
            <li>
                <strong>Wallet</strong>
                <span><?= ucfirst($withdrawal['source']) ?></span>
            </li>
            <li>
                <strong>Amount</strong>
                <h3 class="m-0">₦<?= number_format($withdrawal['amount'], 2) ?></h3>
            </li>
            <li>
                <strong>Method</strong>
                <span><?= htmlspecialchars($withdrawal['name']) ?></span>
            </li>
            <li>
                <strong>Receiver Details</strong>
                <span><?= nl2br(htmlspecialchars($withdrawal['method_details'])) ?></span>
            </li>
            <li>
                <strong>Date</strong>
                <span><?= date('j M, Y h:i A', strtotime($withdrawal['created_at'])) ?></span>
            </li>
        </ul>

    </div>
</div>
<!-- Bottom Navigation -->

<nav class="nav">   
<a href="index.php" >    
    <i class="fas fa-home"></i>    
    <span>Home</span>    
</a>    
<a href="rewards.php">    
    <i class="fas fa-gift"></i>    
    <span>Rewards</span>    
</a>    
<a href="cards.html">    
    <i class="fas fa-receipt"></i>    
    <span>Cards</span>    
</a>    

<a href="transactions.php" class="active">    
    <i class="fas fa-receipt"></i>    
    <span>Transactions</span>    
</a>    
<a href="profile.php">    
    <i class="fas fa-user"></i>    
    <span>Profile</span>    
</a>

</nav>    
    <!-- * App Bottom Menu -->
    <!-- ========= JS Files =========  -->

    <!-- Bootstrap -->

    <script src="assets/js/lib/bootstrap.bundle.min.js"></script>
    <!-- Ionicons -->
    <script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>
    <!-- Splide -->
    <script src="assets/js/plugins/splide/splide.min.js"></script>
    <!-- Base Js File -->
    <script src="assets/js/base.js"></script>
</body>
</html>