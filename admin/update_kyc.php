<?php
// Include database connection
include('db.php');

// Start session and ensure the user is an admin
session_start();
$admin_id = $_SESSION['admin_id'] ?? null;
if (!$admin_id) {
    echo '<div class="alert alert-danger">Admin not logged in.</div>';
    exit;
}

// Handle KYC status update (must come BEFORE fetching submissions)
$feedback = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'], $_POST['kyc_id'])) {
    $action = $_POST['action'];
    $kyc_id = (int) $_POST['kyc_id'];

    try {
        // Get user info for notification/email
        $stmtUser = $sql->prepare("SELECT users.id AS user_id, users.email FROM kyc JOIN users ON kyc.user_id = users.id WHERE kyc.id = :kyc_id");
        $stmtUser->execute([':kyc_id' => $kyc_id]);
        $user = $stmtUser->fetch(PDO::FETCH_ASSOC);

        if (!$user) {
            throw new Exception('User not found.');
        }

        $user_id = $user['user_id'];
        $email = $user['email'];
        $now = date('Y-m-d H:i:s');

        if ($action === 'verify') {
            // Update KYC status
            $stmt = $sql->prepare("UPDATE kyc SET status = 'verified' WHERE id = :kyc_id");
            $stmt->execute([':kyc_id' => $kyc_id]);

            // Insert notification
            $note = $sql->prepare("INSERT INTO notifications (user_id, action_type, message, status, created_at) VALUES (:user_id, 'kyc', :message, 'unread', :created_at)");
            $note->execute([
                ':user_id' => $user_id,
                ':message' => 'Your KYC has been approved.',
                ':created_at' => $now
            ]);

            // Send approval email
            mail($email, "KYC Approved", "Your KYC has been approved. View here: https://swiftaffiliates.cloud/kyc_email_approve.php");

            $feedback = '<div class="alert alert-success">KYC verified, notification sent, and email delivered.</div>';

        } elseif ($action === 'fail') {
            // Update KYC status
            $stmt = $sql->prepare("UPDATE kyc SET status = 'failed' WHERE id = :kyc_id");
            $stmt->execute([':kyc_id' => $kyc_id]);

            // Insert notification
            $note = $sql->prepare("INSERT INTO notifications (user_id, action_type, message, status, created_at) VALUES (:user_id, 'kyc', :message, 'unread', :created_at)");
            $note->execute([
                ':user_id' => $user_id,
                ':message' => 'Your KYC has been rejected.',
                ':created_at' => $now
            ]);

            // Send rejection email
            mail($email, "KYC Rejected", "Unfortunately, your KYC was rejected. See more: https://swiftaffiliates.cloud/kyc_email_failed.php");

            $feedback = '<div class="alert alert-danger">KYC failed, notification sent, and email delivered.</div>';
        }

        // Prevent form resubmission
        header("Location: " . $_SERVER['PHP_SELF']);
        exit;

    } catch (Exception $e) {
        $feedback = '<div class="alert alert-danger">Error updating KYC status: ' . $e->getMessage() . '</div>';
    }
}

// Fetch all KYC submissions
$stmt = $sql->prepare("SELECT kyc.id, kyc.user_id, kyc.full_name, kyc.id_number, kyc.address, kyc.dob, kyc.document_type, kyc.document_image_url, kyc.status, users.email 
                       FROM kyc 
                       JOIN users ON kyc.user_id = users.id");
$stmt->execute();
$kyc_submissions = $stmt->fetchAll(PDO::FETCH_ASSOC);

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



        <!-- Feedback Message -->
        <?php echo $feedback; ?>

        <?php if (count($kyc_submissions) > 0): ?>
            <table class="table table-bordered">
                <thead class="thead-dark">
                    <tr>
                        <th>#</th>
                        <th>Full Name</th>
                        <th>ID Number</th>
                        <th>Address</th>
                        <th>Date of Birth</th>
                        <th>Document Type</th>
                        <th>Document</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($kyc_submissions as $submission): ?>
                        <tr>
                            <td><?php echo $submission['id']; ?></td>
                            <td><?php echo htmlspecialchars($submission['full_name']); ?></td>
                            <td><?php echo htmlspecialchars($submission['id_number']); ?></td>
                            <td><?php echo htmlspecialchars($submission['address']); ?></td>
                            <td><?php echo htmlspecialchars($submission['dob']); ?></td>
                            <td><?php echo htmlspecialchars($submission['document_type']); ?></td>
<td>
<a href="https://swiftaffiliates.cloud/<?= rawurlencode($submission['document_image_url']) ?>" target="_blank">
    View Document
</a>
</td>
                            <td>
                                <span class="badge badge-warning"><?php echo ucfirst($submission['status']); ?></span>
                            </td>
                            <td>
                                <form method="POST" style="display:inline-block;">
                                    <input type="hidden" name="kyc_id" value="<?php echo $submission['id']; ?>">
                                    <button type="submit" name="action" value="verify" class="btn btn-success btn-sm">Verify</button>
                                </form>
                                <form method="POST" style="display:inline-block;">
                                    <input type="hidden" name="kyc_id" value="<?php echo $submission['id']; ?>">
                                    <button type="submit" name="action" value="fail" class="btn btn-danger btn-sm">Fail</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <div class="alert alert-info">No pending KYC submissions at the moment.</div>
        <?php endif; ?>
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
                        <a href="contract.php" class="item">
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