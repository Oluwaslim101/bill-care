<?php
session_start();
require_once("db.php");

// Check if admin is logged in
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: admin-login.php");
    exit();
}

// Fetch all users from the database
$users = [];
try {
    $stmt = $sql->query("SELECT id, phone_number, full_name, email, balance, earnings, status, avatar_url FROM users");
    $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Error fetching users: " . $e->getMessage());
}

// Handle actions for Edit or Delete
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['edit_user'])) {
        // Edit user
        $user_id = $_POST['user_id'];
        $new_email = $_POST['email'];
        $new_balance = $_POST['balance'];
        $new_full_name = $_POST['full_name'];
        $new_address = $_POST['address'];
        $new_gender = $_POST['gender'];
        $new_dob = $_POST['dob'];
        $new_points = $_POST['points'];

        try {
            $stmt = $sql->prepare("UPDATE users SET email = :email, balance = :balance, full_name = :full_name, address = :address, gender = :gender, dob = :dob, points = :points WHERE id = :id");
            $stmt->execute([
                'email' => $new_email,
                'balance' => $new_balance,
                'full_name' => $new_full_name,
                'address' => $new_address,
                'gender' => $new_gender,
                'dob' => $new_dob,
                'points' => $new_points,
                'id' => $user_id
            ]);
            header("Location: users.php");
            exit();
        } catch (PDOException $e) {
            die("Error updating user: " . $e->getMessage());
        }
    } elseif (isset($_POST['delete_user'])) {
        // Delete user
        $user_id = $_POST['user_id'];

        try {
            $stmt = $sql->prepare("DELETE FROM users WHERE id = :id");
            $stmt->execute(['id' => $user_id]);
            header("Location: users.php");
            exit();
        } catch (PDOException $e) {
            die("Error deleting user: " . $e->getMessage());
        }
    }
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
        <div class="section-title">Users Management</div>
        <div class="row">
            <div class="user-table">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Full Name</th>
                            <th>Email</th>
                            <th>Phone Number</th>
                            <th>Balance</th>
                            <th>Points</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($users as $user): ?>
                            <tr>
                                <td><?= htmlspecialchars($user['full_name']) ?></td>
                                <td><?= htmlspecialchars($user['email']) ?></td>
                                <td><?= htmlspecialchars($user['phone_number']) ?></td>
                                <td><?= number_format($user['balance'], 2) ?> NGN</td>
                                <td><?= $user['points'] ?></td>
                                <td><?= htmlspecialchars($user['status']) ?></td>
                                <td>
                                    <a href="user_profile.php?id=<?= $user['id'] ?>" class="btn btn-primary btn-sm">Edit</a>
                                    <button class="btn btn-danger btn-sm" data-id="<?= $user['id'] ?>" data-toggle="modal" data-target="#deleteUserModal">Delete</button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>


<!-- Delete User Modal -->
<div class="modal" id="deleteUserModal">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST">
                <div class="modal-header">
                    <h5 class="modal-title">Delete User</h5>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="user_id" id="delete_user_id">
                    <p>Are you sure you want to delete this user?</p>
                </div>
                <div class="modal-footer">
                    <button type="submit" name="delete_user" class="btn btn-danger">Delete</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                </div>
            </form>
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

<script src="assets/js/lib/bootstrap.bundle.min.js"></script>

<script>
    // Populate the Edit User modal with the user's details
    document.querySelectorAll('.btn-edit').forEach(button => {
        button.addEventListener('click', () => {
            const userId = button.getAttribute('data-id');
            const row = button.closest('tr');
            const fullName = row.querySelector('td:nth-child(1)').textContent;
            const email = row.querySelector('td:nth-child(2)').textContent;
            const phoneNumber = row.querySelector('td:nth-child(3)').textContent;
            const balance = row.querySelector('td:nth-child(4)').textContent.replace(' NGN', '');
            const points = row.querySelector('td:nth-child(5)').textContent;
            const address = row.querySelector('td:nth-child(6)').textContent; // Assuming address is in the 6th column
            const gender = row.querySelector('td:nth-child(7)').textContent; // Assuming gender is in the 7th column
            const dob = row.querySelector('td:nth-child(8)').textContent; // Assuming dob is in the 8th column

            document.getElementById('edit_user_id').value = userId;
            document.getElementById('edit_full_name').value = fullName;
            document.getElementById('edit_email').value = email;
            document.getElementById('edit_phone_number').value = phoneNumber;
            document.getElementById('edit_balance').value = balance;
            document.getElementById('edit_points').value = points;
            document.getElementById('edit_address').value = address;
            document.getElementById('edit_gender').value = gender.toLowerCase();
            document.getElementById('edit_dob').value = dob;
        });
    });

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