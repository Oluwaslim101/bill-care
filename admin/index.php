<?php
session_start();
require_once("db.php");

// Check if admin is logged in
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: admin-login.php");
    exit();
}

// Get dashboard stats
$stats = [];

try {
    // Total users
    $stmt = $sql->query("SELECT COUNT(*) FROM users");
    $stats['total_users'] = $stmt->fetchColumn();

    // Verified users (assuming KYC table)
    $stmt = $sql->query("SELECT COUNT(*) FROM kyc WHERE status = 'verified'");
    $stats['verified_users'] = $stmt->fetchColumn();

    // Total wallet balance (from users table balance column)
    $stmt = $sql->query("SELECT SUM(balance) FROM users");
    $stats['wallet_balance'] = $stmt->fetchColumn() ?: 0;

    // Total contracts (assuming contracts table exists)
    $stmt = $sql->query("SELECT COUNT(*) FROM contracts");
    $stats['contracts'] = $stmt->fetchColumn();

    // Purchased contracts (assuming user_contracts table exists)
    $stmt = $sql->query("SELECT COUNT(*) FROM user_contracts");
    $stats['purchased_contracts'] = $stmt->fetchColumn();

    // Total deposits (assuming transactions table for deposits with 'confirmed' status)
    $stmt = $sql->query("SELECT SUM(amount) FROM transactions WHERE type = 'deposit' AND status = 'confirmed'");
    $stats['total_deposits'] = $stmt->fetchColumn() ?: 0;

    // Total withdrawals (assuming transactions table for withdrawals with 'approved' status)
    $stmt = $sql->query("SELECT SUM(amount) FROM transactions WHERE type = 'withdrawal' AND status = 'approved'");
    $stats['total_withdrawals'] = $stmt->fetchColumn() ?: 0;

    // Points redeemed (assuming users table with points field)
    $stmt = $sql->query("SELECT SUM(points_used) FROM users");
    $stats['points_redeemed'] = $stmt->fetchColumn() ?: 0;

    // Notifications sent (assuming notifications table)
    $stmt = $sql->query("SELECT COUNT(*) FROM notifications");
    $stats['notifications'] = $stmt->fetchColumn();

} catch (PDOException $e) {
    die("Error fetching dashboard stats: " . $e->getMessage());
}
?>

<!doctype html>
<html lang="en">

<head>

    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport"
        content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1, viewport-fit=cover" />
    <meta name="apple-mobile-web-app-capable" content="yes" />
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <meta name="theme-color" content="#000000">
    <title>DtheHub</title>
    <meta name="description" content="Finapp HTML Mobile Template">
    <meta name="keywords"
        content="bootstrap, wallet, banking, fintech mobile template, cordova, phonegap, mobile, html, responsive" />
    <link rel="icon" type="image/png" href="assets/img/favicon.png" sizes="32x32">
    <link rel="apple-touch-icon" sizes="180x180" href="assets/img/icon/192x192.png">
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="manifest" href="__manifest.json">
           <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<link rel="stylesheet" href="assets/css/swiper-bundle.min.css">
<script src="assets/js/lib/swiper-bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script type="module" src="firebase.js"></script>

</head>

<body>

    <!-- loader -->
    <div id="loader">
        <img src="assets/img/loading-icon.png" alt="icon" class="loading-icon">
    </div>
    <!-- * loader -->

<!-- App Header -->
<div class="appHeader bg-primary text-light">
    <div class="left">
        <a href="#" class="headerButton" data-bs-toggle="modal" data-bs-target="#sidebarPanel">
            <ion-icon name="menu-outline"></ion-icon>
        </a>
    </div>
    <div class="pageTitle">Admin Dashboard</div>
    <div class="right">
        <a href="logout.php" class="headerButton">
            <ion-icon name="log-out-outline"></ion-icon>
        </a>
    </div>
</div>

<!-- * App Header -->

   <!-- Main Dashboard Content -->
<div id="appCapsule">
    <div class="section mt-2 mb-2">
        <div class="section-title">Platform Analytics</div>
        <div class="row">

            <!-- Total Users -->
            <div class="col-6">
                <div class="stat-box">
                    <div class="title">Total Users</div>
                    <div class="value"><?= $stats['total_users'] ?></div>
                </div>
            </div>

            <!-- Verified Users -->
            <div class="col-6">
                <div class="stat-box">
                    <div class="title">Verified Users</div>
                    <div class="value"><?= $stats['verified_users'] ?></div>
                </div>
            </div>

            <!-- Wallet Balance -->
            <div class="col-6">
                <div class="stat-box">
                    <div class="title">Wallet Balance</div>
                    <div class="value">₦<?= number_format($stats['wallet_balance'], 2) ?></div>
                </div>
            </div>

            <!-- Contracts -->
            <div class="col-6">
                <div class="stat-box">
                    <div class="title">Contracts</div>
                    <div class="value"><?= $stats['contracts'] ?></div>
                </div>
            </div>

            <!-- Purchased Contracts -->
            <div class="col-6">
                <div class="stat-box">
                    <div class="title">Purchased Contracts</div>
                    <div class="value"><?= $stats['purchased_contracts'] ?></div>
                </div>
            </div>

            <!-- Deposits -->
            <div class="col-6">
                <div class="stat-box">
                    <div class="title">Total Deposits</div>
                    <div class="value">₦<?= number_format($stats['total_deposits'], 2) ?></div>
                </div>
            </div>

            <!-- Withdrawals -->
            <div class="col-6">
                <div class="stat-box">
                    <div class="title">Total Withdrawals</div>
                    <div class="value">₦<?= number_format($stats['total_withdrawals'], 2) ?></div>
                </div>
            </div>

            <!-- Points Redeemed -->
            <div class="col-6">
                <div class="stat-box">
                    <div class="title">Points Redeemed</div>
                    <div class="value"><?= $stats['points_redeemed'] ?></div>
                </div>
            </div>

            <!-- Notifications Sent -->
            <div class="col-6">
                <div class="stat-box">
                    <div class="title">Notifications</div>
                    <div class="value"><?= $stats['notifications'] ?></div>
                </div>
            </div>

            <!-- Support Tickets -->
            <div class="col-6">
                <div class="stat-box">
                    <div class="title">Support Tickets</div>
                    <div class="value"><?= $stats['tickets'] ?></div>
                </div>
            </div>

        </div>
    </div>
</div>

<!-- Scripts -->


  <!-- App Sidebar (Admin) -->
<div class="modal fade panelbox panelbox-left" id="sidebarPanel" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-body p-0">

                <!-- profile box -->
                <div class="profileBox pt-2 pb-2">
                    <div class="image-wrapper">
                        <img src="<?= $avatar_url ?>" alt="User Avatar" class="imaged w32 h32">
                    </div>
                    <div class="in">
                        <span>Welcome, <b><?= htmlspecialchars($user['nick_name']) ?></b></span>
                        <div class="text-muted small">Administrator</div>
                    </div>
                    <a href="#" class="btn btn-link btn-icon sidebar-close" data-bs-dismiss="modal">
                        <ion-icon name="close-outline"></ion-icon>
                    </a>
                </div>
                <!-- * profile box -->

                <!-- Admin quick balance -->
                <div class="sidebar-balance">
                    <div class="listview-title">System Balance</div>
                    <div class="in">
                        <h1 id="balance-amount">₦<?= number_format($balance, 2) ?></h1>
                    </div>
                </div>
                <!-- * balance -->

                <!-- Admin Navigation Menu -->
                <div class="listview-title mt-1">Admin Menu</div>
                <ul class="listview flush transparent no-line image-listview">

                    <li>
                        <a href="index.php" class="item">
                            <div class="icon-box bg-primary">
                                <ion-icon name="speedometer-outline"></ion-icon>
                            </div>
                            <div class="in">Dashboard</div>
                        </a>
                    </li>

                    <li>
                        <a href="users.php" class="item">
                            <div class="icon-box bg-primary">
                                <ion-icon name="people-outline"></ion-icon>
                            </div>
                            <div class="in">Users Management</div>
                        </a>
                    </li>
                    <li>
                        <a href="withdrawal.php" class="item">
                            <div class="icon-box bg-primary">
                                <ion-icon name="briefcase-outline"></ion-icon>
                            </div>
                            <div class="in">Withdrawal Req</div>
                        </a>
                    </li>

                    <li>
                        <a href="deposit.php" class="item">
                            <div class="icon-box bg-primary">
                                <ion-icon name="checkbox-outline"></ion-icon>
                            </div>
                            <div class="in">Deposit Req</div>
                        </a>
                    </li>

                    <li>
                        <a href="add_car.php" class="item">
                            <div class="icon-box bg-primary">
                                <ion-icon name="briefcase-outline"></ion-icon>
                            </div>
                            <div class="in">Add Car</div>
                        </a>
                    </li>

                    <li>
                        <a href="tasks.php" class="item">
                            <div class="icon-box bg-primary">
                                <ion-icon name="checkbox-outline"></ion-icon>
                            </div>
                            <div class="in">Tasks & Points</div>
                        </a>
                    </li>

                    <li>
                        <a href="transactions.php" class="item">
                            <div class="icon-box bg-primary">
                                <ion-icon name="swap-horizontal-outline"></ion-icon>
                            </div>
                            <div class="in">Transactions</div>
                        </a>
                    </li>

                    <li>
                        <a href="wallets.php" class="item">
                            <div class="icon-box bg-primary">
                                <ion-icon name="wallet-outline"></ion-icon>
                            </div>
                            <div class="in">Wallet Settings</div>
                        </a>
                    </li>

                    <li>
                        <a href="contact_messages.php" class="item">
                            <div class="icon-box bg-primary">
                                <ion-icon name="notifications-outline"></ion-icon>
                            </div>
                            <div class="in">Contacts Messages</div>
                        </a>
                    </li>

                    <li>
                        <a href="analytics.php" class="item">
                            <div class="icon-box bg-primary">
                                <ion-icon name="stats-chart-outline"></ion-icon>
                            </div>
                            <div class="in">Analytics</div>
                        </a>
                    </li>

                    <li>
                        <a href="settings.php" class="item">
                            <div class="icon-box bg-primary">
                                <ion-icon name="settings-outline"></ion-icon>
                            </div>
                            <div class="in">System Settings</div>
                        </a>
                    </li>
                </ul>

                <!-- Others -->
                <div class="listview-title mt-1">Others</div>
                <ul class="listview flush transparent no-line image-listview">
                    <li>
                        <a href="support.php" class="item">
                            <div class="icon-box bg-primary">
                                <ion-icon name="chatbubbles-outline"></ion-icon>
                            </div>
                            <div class="in">Support</div>
                        </a>
                    </li>
                      <li>
                        <a href="admin_banner_alerts.php" class="item">
                            <div class="icon-box bg-primary">
                                <ion-icon name="chatbubbles-outline"></ion-icon>
                            </div>
                            <div class="in">Alert Banners</div>
                        </a>
                    </li>
                     <li>
                        <a href="admin_kyc.php" class="item">
                            <div class="icon-box bg-primary">
                                <ion-icon name="chatbubbles-outline"></ion-icon>
                            </div>
                            <div class="in">KYC</div>
                        </a>
                    </li>
                    <li>
                        <a href="logout.php" class="item">
                            <div class="icon-box bg-danger">
                                <ion-icon name="log-out-outline"></ion-icon>
                            </div>
                            <div class="in">Log out</div>
                        </a>
                    </li>
                </ul>

            </div>
        </div>
    </div>
</div>
<!-- * App Sidebar -->

<!-- Pusher JS -->
  <script src="https://js.pusher.com/7.0/pusher.min.js"></script>

  <script>
    // Initialize Pusher
    Pusher.logToConsole = true;
    const pusher = new Pusher('d721521425aa5a667ee5', {
      cluster: 'us2'
    });

    // Subscribe to the 'notifications' channel
    const channel = pusher.subscribe('notifications');

    // Bind an event called 'fcm-token' (or another event name you use)
    channel.bind('fcm-token', function(data) {
      alert('New Notification: ' + data.message);
      console.log('Notification received: ', data);
    });
  </script>


    <!-- Bootstrap -->
    <script src="assets/js/lib/bootstrap.bundle.min.js"></script>
    <!-- Ionicons -->
    <script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>
    <!-- Splide -->
    <script src="assets/js/plugins/splide/splide.min.js"></script>
    <!-- Apex Charts -->
    <script src="assets/js/plugins/apexcharts/apexcharts.min.js"></script>
    <!-- Base Js File -->
    <script src="assets/js/base.js"></script>

</body>

</html>