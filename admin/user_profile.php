<?php
session_start();
require_once("db.php");

// Ensure admin is logged in
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: admin-login.php");
    exit();
}

// Validate user_id
$user_id = isset($_GET['id']) ? intval($_GET['id']) : null;
if (!$user_id) {
    die("User ID is required.");
}

// Fetch user
$stmt = $sql->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$user_id]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$user) {
    die("User not found.");
}

// Fetch KYC
$kyc = $sql->prepare("SELECT * FROM kyc WHERE user_id = ?");
$kyc->execute([$user_id]);
$kyc_data = $kyc->fetch(PDO::FETCH_ASSOC);

// Fetch Deposits
$deposits = $sql->prepare("SELECT * FROM deposits WHERE user_id = ? ORDER BY id DESC");
$deposits->execute([$user_id]);

// Fetch Withdrawals
$withdrawals = $sql->prepare("SELECT * FROM withdrawals WHERE user_id = ? ORDER BY id DESC");
$withdrawals->execute([$user_id]);

// Fetch Contracts
$contracts = $sql->prepare("SELECT * FROM user_contracts WHERE user_id = ? ORDER BY id DESC");
$contracts->execute([$user_id]);
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

<!-- Flash Alerts -->
<?php if (isset($_GET['update']) && $_GET['update'] === 'success'): ?>
    <div class="alert alert-success">Profile updated successfully!</div>
<?php endif; ?>
<?php if (isset($_GET['update_wallet']) && $_GET['update_wallet'] === 'success'): ?>
    <div class="alert alert-success">Wallet updated successfully!</div>
<?php endif; ?>
<?php if (isset($_GET['update_kyc']) && $_GET['update_kyc'] === 'success'): ?>
    <div class="alert alert-success">KYC updated successfully!</div>
<?php endif; ?>

<!-- App Content -->
<div id="appCapsule">
    <div class="section mt-2 mb-2">
        <div class="section-title">User Management</div>
        <div class="row">

            <!-- Profile Update -->
            <div class="section full mt-2">
                <div class="wide-block pt-2 pb-2">
                    <form action="update_user.php?user_id=<?= $user['id'] ?>" method="POST" enctype="multipart/form-data">
                        <div class="d-flex align-items-center">
                            <img src="<?= $user['avatar_url'] ?: 'assets/img/default-user.png' ?>" alt="avatar" class="imaged w48 rounded me-3">
                            <div>
                                <h4 class="fw-bold mb-0">
                                    <input type="text" name="full_name" value="<?= htmlspecialchars($user['full_name']) ?>" class="form-control" required>
                                    <small>(ID: <?= $user['id'] ?>)</small>
                                </h4>
                                <input type="email" name="email" value="<?= htmlspecialchars($user['email']) ?>" class="form-control my-1" required>
                                <input type="text" name="phone_number" value="<?= htmlspecialchars($user['phone_number']) ?>" class="form-control my-1" required>
                                <input type="file" name="avatar" class="form-control my-1">
                                <button type="submit" class="btn btn-sm btn-primary">Update Profile</button>
                                <a href="login_as_user.php?id=<?= $user['id'] ?>" class="btn btn-sm btn-warning">Login as User</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Wallet Update -->
            <div class="section mt-2">
                <div class="section-title">Wallet Summary</div>
                <div class="card">
                    <form action="update_wallet.php" method="POST">
                        <input type="hidden" name="user_id" value="<?= $user['id'] ?>">
                        <ul class="listview flush transparent no-line image-listview">
                            <li><strong>Balance:</strong> <input type="number" step="0.01" name="balance" value="<?= $user['balance'] ?>" class="form-control" required></li>
                            <li><strong>Earnings:</strong> <input type="number" step="0.01" name="earnings" value="<?= $user['earnings'] ?>" class="form-control" required></li>
                            <li><strong>Bonus:</strong> <input type="number" step="0.01" name="bonus" value="<?= $user['bonus'] ?>" class="form-control" required></li>
                            <li><strong>Deposit Total:</strong> <input type="number" step="0.01" name="deposit" value="<?= $user['deposit'] ?>" class="form-control" required></li>
                            <li><strong>Withdrawal Total:</strong> <input type="number" step="0.01" name="withdrawal" value="<?= $user['withdrawal'] ?>" class="form-control" required></li>
                            <li><strong>Investment:</strong> <input type="number" step="0.01" name="investment" value="<?= $user['investment'] ?>" class="form-control" required></li>
                            <li><strong>Points:</strong> <input type="number" name="points" value="<?= $user['points'] ?>" class="form-control" required>
                                <small>(Used: <?= $user['points_used'] ?>)</small>
                            </li>
                        </ul>
                        <button type="submit" class="btn btn-sm btn-primary">Update Wallet</button>
                    </form>
                </div>
            </div>

            <!-- KYC Update -->
            <?php if ($kyc_data): ?>
            <div class="section mt-2">
                <div class="section-title">KYC Information</div>
                <div class="card">
                    <form action="update_kyc.php" method="POST">
                        <input type="hidden" name="user_id" value="<?= $user['id'] ?>">
                        <ul class="listview flush transparent no-line image-listview">
                            <li><strong>Status:</strong> <input type="text" name="status" value="<?= ucfirst($kyc_data['status']) ?>" class="form-control"></li>
                            <li><strong>Type:</strong> <input type="text" name="document_type" value="<?= $kyc_data['document_type'] ?>" class="form-control"></li>
                            <li><strong>Data:</strong> <input type="text" name="kyc_value" value="<?= htmlspecialchars($kyc_data['kyc_value']) ?>" class="form-control"></li>
                            <li><a href="<?= $kyc_data['kyc_document_url'] ?>" target="_blank">View Document</a></li>
                        </ul>
                        <button type="submit" class="btn btn-sm btn-primary">Update KYC</button>
                    </form>
                </div>
            </div>
            <?php endif; ?>

            <!-- Deposits -->
            <div class="section mt-2">
                <div class="section-title">Deposits</div>
                <div class="card">
                    <?php while ($row = $deposits->fetch(PDO::FETCH_ASSOC)): ?>
                        <div class="item">
                            <strong>₦<?= number_format($row['amount'], 2) ?></strong>
                            <p class="text-muted small"><?= $row['status'] ?> • <?= $row['created_at'] ?></p>
                        </div>
                    <?php endwhile; ?>
                </div>
            </div>

            <!-- Withdrawals -->
            <div class="section mt-2">
                <div class="section-title">Withdrawals</div>
                <div class="card">
                    <?php while ($row = $withdrawals->fetch(PDO::FETCH_ASSOC)): ?>
                        <div class="item">
                            <strong>₦<?= number_format($row['amount'], 2) ?></strong>
                            <p class="text-muted small"><?= $row['status'] ?> • <?= $row['created_at'] ?></p>
                        </div>
                    <?php endwhile; ?>
                </div>
            </div>

            <!-- Contracts -->
            <div class="section mt-2 mb-5">
                <div class="section-title">Purchased Contracts</div>
                <div class="card">
                    <?php while ($row = $contracts->fetch(PDO::FETCH_ASSOC)): ?>
                        <div class="item">
                            <strong>Contract ID: <?= $row['contract_id'] ?></strong>
                            <p class="text-muted small">Amount: ₦<?= number_format($row['purchased_amount'], 2) ?> • <?= $row['created_at'] ?></p>
                        </div>
                    <?php endwhile; ?>
                </div>
            </div>

        </div>
    </div>
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
                    <h1 id="balance-amount">$<?= number_format($balance, 2) ?></h1>
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
                    <a href="notifications.php" class="item">
                        <div class="icon-box bg-primary">
                            <ion-icon name="notifications-outline"></ion-icon>
                        </div>
                        <div class="in">Notifications</div>
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

<script src="assets/js/lib/bootstrap.bundle.min.js"></script>

<script>


    // Set the user id for deletion
    document.querySelectorAll('.btn-delete').forEach(button => {
        button.addEventListener('click', () => {
            const userId = button.getAttribute('data-id');
            document.getElementById('delete_user_id').value = userId;
        });
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
