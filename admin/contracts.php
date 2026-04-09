<?php
session_start();
include 'db.php';

// Check if admin is logged in
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: admin-login.php");
    exit();
}

// Fetch all contracts
$stmt = $sql->query("SELECT * FROM contracts ORDER BY created_at DESC");
$contracts = $stmt->fetchAll(PDO::FETCH_ASSOC);


// Add new contract
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action']) && $_POST['action'] == 'add') {
    // Retrieve form data
    $investment_name = $_POST['investment_name'];
    $amount_gauge = $_POST['amount_gauge'];
    $duration = $_POST['duration'];
    $profit = $_POST['profit'];
    $status = $_POST['status'];
    $user_id = $_POST['user_id'];
    $amount_invested = $_POST['amount_invested'];
    
    // Prepare and execute SQL query to insert new contract
    $stmt = $sql->prepare("INSERT INTO contracts (investment_name, amount_gauge, duration, profit, status, user_id, amount_invested, created_at, updated_at) 
                          VALUES (:investment_name, :amount_gauge, :duration, :profit, :status, :user_id, :amount_invested, NOW(), NOW())");

    $stmt->execute([
        ':investment_name' => $investment_name,
        ':amount_gauge' => $amount_gauge,
        ':duration' => $duration,
        ':profit' => $profit,
        ':status' => $status,
        ':user_id' => $user_id,
        ':amount_invested' => $amount_invested
    ]);

    // Redirect after successful insertion
    header('Location: contracts.php');
    exit;
}

// Edit contract
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action']) && $_POST['action'] == 'edit') {
    // Retrieve form data
    $contract_id = $_POST['contract_id'];
    $investment_name = $_POST['investment_name'];
    $amount_gauge = $_POST['amount_gauge'];
    $duration = $_POST['duration'];
    $profit = $_POST['profit'];
    $status = $_POST['status'];
    $user_id = $_POST['user_id'];
    $amount_invested = $_POST['amount_invested'];

    // Prepare and execute SQL query to update contract
    $stmt = $sql->prepare("UPDATE contracts 
                           SET investment_name = :investment_name, 
                               amount_gauge = :amount_gauge, 
                               duration = :duration, 
                               profit = :profit, 
                               status = :status, 
                               = :amount_invested, 
                               updated_at = NOW() 
                           WHERE id = :id");

    $stmt->execute([
        ':investment_name' => $investment_name,
        ':amount_gauge' => $amount_gauge,
        ':duration' => $duration,
        ':profit' => $profit,
        ':status' => $status,
    ]);

    // Redirect after successful update
    header('Location: contracts.php');
    exit;
}

// Delete contract
if (isset($_GET['delete_id'])) {
    $contract_id = $_GET['delete_id'];

    // Prepare and execute SQL query to delete contract
    $stmt = $sql->prepare("DELETE FROM contracts WHERE id = :id");
    $stmt->execute([':id' => $contract_id]);

    // Redirect after successful deletion
    header('Location: contracts.php');
    exit;
}

// Fetch all contracts
$stmt = $sql->query("SELECT * FROM contracts");
$contracts = $stmt->fetchAll(PDO::FETCH_ASSOC);
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
            
       <h2>Add New Contract</h2>
        <form action="contracts.php" method="POST">
            <input type="hidden" name="action" value="add">
            <div class="form-group">
                <label for="investment_name">Investment Name</label>
                <input type="text" name="investment_name" id="investment_name" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="amount_gauge">Amount Gauge</label>
                <input type="number" step="0.01" name="amount_gauge" id="amount_gauge" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="duration">Duration</label>
                <input type="text" name="duration" id="duration" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="profit">Profit</label>
                <input type="number" step="0.01" name="profit" id="profit" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="status">Status</label>
                <select name="status" id="status" class="form-control" required>
                    <option value="active">Active</option>
                    <option value="inactive">Inactive</option>
                </select>
            </div>
          
            <button type="submit" class="btn btn-primary">Add Contract</button>
        </form>

        <hr>

        <!-- Contracts List -->
        <h2>Contract List</h2>
        <table class="table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Investment Name</th>
                    <th>Amount Gauge</th>
                    <th>Duration</th>
                    <th>Profit</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($contracts as $contract): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($contract['id']); ?></td>
                        <td><?php echo htmlspecialchars($contract['investment_name']); ?></td>
                        <td><?php echo htmlspecialchars($contract['amount_gauge']); ?></td>
                        <td><?php echo htmlspecialchars($contract['duration']); ?></td>
                        <td><?php echo htmlspecialchars($contract['profit']); ?></td>
                        <td><?php echo htmlspecialchars($contract['status']); ?></td>
                        <td>
                            <a href="contracts.php?edit_id=<?php echo $contract['id']; ?>" class="btn btn-warning">Edit</a>
                            <a href="contracts.php?delete_id=<?php echo $contract['id']; ?>" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this contract?');">Delete</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <!-- Edit Contract Form -->
    <?php if (isset($_GET['edit_id'])): 
        $contract_id = $_GET['edit_id'];
        $stmt = $sql->query("SELECT * FROM contracts WHERE id = $contract_id");
        $contract = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($contract): ?>
        <div class="container">
            <h2>Edit Contract</h2>
            <form action="contracts.php" method="POST">
                <input type="hidden" name="action" value="edit">
                <input type="hidden" name="contract_id" value="<?php echo $contract['id']; ?>">
                <div class="form-group">
                    <label for="investment_name">Investment Name</label>
                    <input type="text" name="investment_name" id="investment_name" class="form-control" value="<?php echo htmlspecialchars($contract['investment_name']); ?>" required>
                </div>
                <div class="form-group">
                    <label for="amount_gauge">Amount Gauge</label>
                    <input type="number" step="0.01" name="amount_gauge" id="amount_gauge" class="form-control" value="<?php echo htmlspecialchars($contract['amount_gauge']); ?>" required>
                </div>
                <div class="form-group">
                    <label for="duration">Duration</label>
                    <input type="text" name="duration" id="duration" class="form-control" value="<?php echo htmlspecialchars($contract['duration']); ?>" required>
                </div>
                <div class="form-group">
                    <label for="profit">Profit</label>
                    <input type="number" step="0.01" name="profit" id="profit" class="form-control" value="<?php echo htmlspecialchars($contract['profit']); ?>" required>
                </div>
                <div class="form-group">
                    <label for="status">Status</label>
                    <select name="status" id="status" class="form-control" required>
                        <option value="active" <?php echo ($contract['status'] == 'active') ? 'selected' : ''; ?>>Active</option>
                        <option value="inactive" <?php echo ($contract['status'] == 'inactive') ? 'selected' : ''; ?>>Inactive</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="user_id">User ID</label>
                    <input type="number" name="user_id" id="user_id" class="form-control" value="<?php echo htmlspecialchars($contract['user_id']); ?>" required>
                </div>
                <div class="form-group">
                    <label for="amount_invested">Amount Invested</label>
                    <input type="number" step="0.01" name="amount_invested" id="amount_invested" class="form-control" value="<?php echo htmlspecialchars($contract['amount_invested']); ?>" required>
                </div>
                <button type="submit" class="btn btn-primary">Update Contract</button>
            </form>
        </div>
    <?php endif; endif; ?>

            
            
            
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
            
            
            