<?php
require 'db.php';
require 'email_template.php';

// Handle delete action
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_id'])) {
    $stmt = $sql->prepare("DELETE FROM contact_messages WHERE id = ?");
    $stmt->execute([$_POST['delete_id']]);
    header("Location: contact_messages.php?deleted=true");
    exit;
}

// Handle reply submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['reply_submit'])) {
    $id = $_POST['reply_id'];
    $reply = $_POST['reply_message'];

    // Fetch email and name for the user
    $stmt = $sql->prepare("SELECT name, email FROM contact_messages WHERE id = ?");
    $stmt->execute([$id]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        // Save reply to database
        $stmt = $sql->prepare("UPDATE contact_messages SET reply = ? WHERE id = ?");
        $stmt->execute([$reply, $id]);

        // Compose email using template
        $to = $user['email'];
        $subject = "Reply to Your Message";
        $headers = "MIME-Version: 1.0" . "\r\n";
        $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
        $headers .= "From: support@swiftaffiliates.cloud\r\n";

        $message = render_email_template($user['name'], $reply);

        // Send email
        mail($to, $subject, $message, $headers);
    }

    header("Location: contact_messages.php?replied=true");
    exit;
}

// Fetch all contact messages
$stmt = $sql->query("SELECT * FROM contact_messages ORDER BY created_at DESC");
$messages = $stmt->fetchAll(PDO::FETCH_ASSOC);
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
        <div class="section-title">Contact Messages</div>
        <div class="row">

    <?php if (isset($_GET['deleted'])): ?>
        <div class="alert alert-success">Message deleted successfully.</div>
    <?php elseif (isset($_GET['replied'])): ?>
        <div class="alert alert-info">Reply sent and saved successfully.</div>
    <?php endif; ?>

    <table class="table table-bordered table-striped bg-white shadow-sm">
        <thead class="table-dark">
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Email</th>
                <th>Message (Preview)</th>
                <th>Created At</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
        <?php foreach ($messages as $msg): ?>
            <tr>
                <td><?= htmlspecialchars($msg['id']) ?></td>
                <td><?= htmlspecialchars($msg['name']) ?></td>
                <td><?= htmlspecialchars($msg['email']) ?></td>
                <td><?= htmlspecialchars(substr($msg['message'], 0, 50)) ?>...</td>
                <td><?= htmlspecialchars($msg['created_at']) ?></td>
                <td>
                    <!-- View -->
                    <button class="btn btn-sm btn-primary mb-1" data-bs-toggle="modal" data-bs-target="#viewModal<?= $msg['id'] ?>">View</button>

                    <!-- Reply -->
                    <button class="btn btn-sm btn-warning mb-1" data-bs-toggle="modal" data-bs-target="#replyModal<?= $msg['id'] ?>">Reply</button>

                    <!-- Delete -->
                    <form method="POST" action="" style="display:inline;" onsubmit="return confirm('Are you sure you want to delete this message?');">
                        <input type="hidden" name="delete_id" value="<?= $msg['id'] ?>">
                        <button class="btn btn-sm btn-danger mb-1">Delete</button>
                    </form>
                </td>
            </tr>

            <!-- View Modal -->
            <div class="modal fade" id="viewModal<?= $msg['id'] ?>" tabindex="-1" aria-labelledby="viewModalLabel<?= $msg['id'] ?>" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Message from <?= htmlspecialchars($msg['name']) ?></h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <p><strong>Email:</strong> <?= htmlspecialchars($msg['email']) ?></p>
                            <p><strong>Message:</strong><br><?= nl2br(htmlspecialchars($msg['message'])) ?></p>
                            <p><strong>Received:</strong> <?= htmlspecialchars($msg['created_at']) ?></p>
                            <?php if (!empty($msg['reply'])): ?>
                                <hr>
                                <p><strong>Your Reply:</strong><br><?= nl2br(htmlspecialchars($msg['reply'])) ?></p>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Reply Modal -->
            <div class="modal fade" id="replyModal<?= $msg['id'] ?>" tabindex="-1" aria-labelledby="replyModalLabel<?= $msg['id'] ?>" aria-hidden="true">
                <div class="modal-dialog">
                    <form method="POST" action="contact_messages.php">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Reply to <?= htmlspecialchars($msg['name']) ?></h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <input type="hidden" name="reply_id" value="<?= $msg['id'] ?>">
                                <div class="mb-3">
                                    <label for="replyMessage<?= $msg['id'] ?>" class="form-label">Your Reply</label>
                                    <textarea name="reply_message" class="form-control" rows="5" required><?= htmlspecialchars($msg['reply'] ?? '') ?></textarea>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="submit" name="reply_submit" class="btn btn-success">Send Reply</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        <?php endforeach; ?>
        </tbody>
    </table>
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