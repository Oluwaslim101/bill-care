<?php
require 'db.php';

// Handle add/update alert
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['save_alert'])) {
    $id = $_POST['id'];
    $title = $_POST['title'];
    $message = $_POST['message'];
    $alert_type = $_POST['alert_type'];
    $start = $_POST['display_start'];
    $end = $_POST['display_end'];
    $is_active = isset($_POST['is_active']) ? 1 : 0;

    if ($id) {
        $stmt = $sql->prepare("UPDATE banner_alerts SET title=?, message=?, alert_type=?, display_start=?, display_end=?, is_active=? WHERE id=?");
        $stmt->execute([$title, $message, $alert_type, $start, $end, $is_active, $id]);
    } else {
        $stmt = $sql->prepare("INSERT INTO banner_alerts (title, message, alert_type, display_start, display_end, is_active) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->execute([$title, $message, $alert_type, $start, $end, $is_active]);
    }
    header("Location: admin_banner_alerts.php?saved=true");
    exit;
}

// Handle delete
if (isset($_POST['delete_id'])) {
    $stmt = $sql->prepare("DELETE FROM banner_alerts WHERE id = ?");
    $stmt->execute([$_POST['delete_id']]);
    header("Location: admin_banner_alerts.php?deleted=true");
    exit;
}

// Fetch alerts
$stmt = $sql->query("SELECT * FROM banner_alerts ORDER BY display_start DESC");
$alerts = $stmt->fetchAll(PDO::FETCH_ASSOC);
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

    <?php if (isset($_GET['saved'])): ?>
        <div class="alert alert-success">Alert saved successfully.</div>
    <?php elseif (isset($_GET['deleted'])): ?>
        <div class="alert alert-danger">Alert deleted successfully.</div>
    <?php endif; ?>

    <!-- Add New Button -->
    <button class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#alertModal">Add New Alert</button>

    <table class="table table-bordered table-striped bg-white">
        <thead class="table-dark">
            <tr>
                <th>ID</th>
                <th>Title</th>
                <th>Message</th>
                <th>Type</th>
                <th>Start</th>
                <th>End</th>
                <th>Active</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
        <?php foreach ($alerts as $alert): ?>
            <tr>
                <td><?= $alert['id'] ?></td>
                <td><?= htmlspecialchars($alert['title']) ?></td>
                <td><?= htmlspecialchars(substr($alert['message'], 0, 40)) ?>...</td>
                <td><?= htmlspecialchars($alert['alert_type']) ?></td>
                <td><?= $alert['display_start'] ?></td>
                <td><?= $alert['display_end'] ?></td>
                <td><?= $alert['is_active'] ? 'Yes' : 'No' ?></td>
                <td>
                    <!-- Edit -->
                    <button class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#editModal<?= $alert['id'] ?>">Edit</button>

                    <!-- Delete -->
                    <form method="POST" style="display:inline;" onsubmit="return confirm('Delete this alert?');">
                        <input type="hidden" name="delete_id" value="<?= $alert['id'] ?>">
                        <button class="btn btn-sm btn-danger">Delete</button>
                    </form>
                </td>
            </tr>

            <!-- Edit Modal -->
            <div class="modal fade" id="editModal<?= $alert['id'] ?>" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog">
                    <form method="POST">
                        <input type="hidden" name="id" value="<?= $alert['id'] ?>">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Edit Alert</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>
                            <div class="modal-body">
                                <?php include 'alert_form_fields.php'; ?>
                            </div>
                            <div class="modal-footer">
                                <button type="submit" name="save_alert" class="btn btn-success">Save Changes</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        <?php endforeach; ?>
        </tbody>
    </table>
</div>

<!-- New Alert Modal -->
<div class="modal fade" id="alertModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <form method="POST">
            <input type="hidden" name="id" value="">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add New Alert</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <?php
                    $alert = ['title' => '', 'message' => '', 'alert_type' => '', 'display_start' => '', 'display_end' => '', 'is_active' => 1];
                    include 'alert_form_fields.php';
                    ?>
                </div>
                <div class="modal-footer">
                    <button type="submit" name="save_alert" class="btn btn-primary">Add Alert</button>
                </div>
            </div>
        </form>
    </div>
</div>

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
                        <a href="contracts.php" class="item">
                            <div class="icon-box bg-primary">
                                <ion-icon name="briefcase-outline"></ion-icon>
                            </div>
                            <div class="in">Contracts</div>
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