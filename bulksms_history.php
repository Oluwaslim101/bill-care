<?php
// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

include('db.php');
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$user_id = $_SESSION['user_id'];

// Fetch user
$stmt = $sql->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$user_id]);
$user = $stmt->fetch();

if (!$user) {
    header('Location: login.php');
    exit();
}

$full_name = $user['full_name'];
$balance = $user['balance'];
$avatar_url = !empty($user['avatar_url']) ? $user['avatar_url'] : 'default-avatar.png';

// Fetch unread notifications
$notifications_stmt = $sql->prepare("SELECT COUNT(*) FROM notifications WHERE user_id = ? AND status = 'unread'");
$notifications_stmt->execute([$user_id]);
$unread_count = $notifications_stmt->fetchColumn();

// -----------------------------
// Filters and Pagination Setup
// -----------------------------
$perPage = 10;
$page = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
$start = ($page - 1) * $perPage;

$filterDate = $_GET['date'] ?? '';
$filterStatus = $_GET['status'] ?? '';

$filterConditions = "user_id = ?";
$params = [$user_id];

if (!empty($filterDate)) {
    $filterConditions .= " AND DATE(created_at) = ?";
    $params[] = $filterDate;
}

if (!empty($filterStatus)) {
    $filterConditions .= " AND status = ?";
    $params[] = $filterStatus;
}

// -----------------------------
// Total Count for Pagination
// -----------------------------
$count_stmt = $sql->prepare("SELECT COUNT(*) FROM sms_logs WHERE $filterConditions");
$count_stmt->execute($params);
$total = $count_stmt->fetchColumn();
$totalPages = ceil($total / $perPage);

// -----------------------------
// Fetch Paginated Filtered Logs
// -----------------------------
$start = (int) $start;
$perPage = (int) $perPage;
$query = "SELECT * FROM sms_logs WHERE $filterConditions ORDER BY created_at DESC LIMIT $start, $perPage";

$logs_stmt = $sql->prepare($query);
$logs_stmt->execute($params);
$sms_logs = $logs_stmt->fetchAll();
?>


<!DOCTYPE html> 

<html lang="en">    

<head> 
<meta charset="UTF-8">

<meta name="viewport"
content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1, viewport-fit=cover" /> 
<meta name="apple-mobile-web-app-capable" content="yes" /> 
<meta name="apple-mobile-web-app-status-bar-style" content="black-translucent"> <meta name="theme-color" content="#000000">
<title>DtheHub</title> 
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
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script><link rel="stylesheet" href="styles.css"> 
  <style>
  .sms-card {
    border-left: 4px solid #007bff;
    border-bottom: 1px solid #ddd;
    background: #fff;
    border-radius: 6px;
    padding: 12px;
    margin-bottom: 12px;
    box-shadow: 0 1px 3px rgba(0,0,0,0.05);
    transition: all 0.3s ease-in-out;
}

.sms-card:hover {
    background: #f9f9f9;
}

.sms-card .status {
    font-size: 13px;
    font-weight: bold;
    margin-bottom: 5px;
}

.sms-card .recipients,
.sms-card .timestamp {
    font-size: 13px;
    color: #777;
    margin-bottom: 4px;
}

.sms-card .message {
    font-size: 14px;
    margin-top: 6px;
    color: #222;
}

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
    <div class="pageTitle">
     <h3> Bulk SMS History</h3>
    </div>
   <div class="right">
    <div style="position: relative; padding-right: 12px;" onclick="showCartModal()">
        <i class="fas fa-receipt fa-lg"></i>
        <span id="cartCountBadge"
              class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger"
              style="right: -6px;">
            0
        </span>
    </div>
</div>
</div>
<!-- App Capsule -->
<div id="appCapsule">
    <div class="section mt-2 mb-2">
        <div class="card">
            <div class="card-body">
                
                <form method="GET" class="row g-2 mb-3">
    <div class="col-6">
        <input type="date" name="date" class="form-control" value="<?= htmlspecialchars($_GET['date'] ?? '') ?>">
    </div>
    <div class="col-6">
        <select name="status" class="form-control">
            <option value="">All Statuses</option>
            <option value="Successfully Sent" <?= ($_GET['status'] ?? '') == 'Successfully Sent' ? 'selected' : '' ?>>Successfully Sent</option>
            <option value="Failed" <?= ($_GET['status'] ?? '') == 'Failed' ? 'selected' : '' ?>>Failed</option>
        </select>
    </div>
    <div class="col-12">
        <button class="btn btn-primary btn-block" type="submit">Filter</button>
    </div>
</form>


                <?php if (count($sms_logs) === 0): ?>
                    <div class="text-center mt-4">
                        <p>No SMS logs found.</p>
                    </div>
                <?php else: ?>
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead class="table-light">
                                <tr>
                                    <th>Description</th>
                                    <th>Receiver(s)</th>
                                    <th>Pages</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($sms_logs as $log): ?>
                                    <tr>
                                        <td><?= nl2br(htmlspecialchars($log['message'])) ?></td>
                                        <td><?= htmlspecialchars($log['recipients']) ?></td>
                                        <td>
                                            <?php
                                                $chars = strlen($log['message']);
                                                $pages = ceil($chars / 160);
                                                echo $pages;
                                            ?>
                                        </td>
                                        <td>
                                            <span class="badge bg-<?= strtolower($log['status']) === 'successfully sent' ? 'success' : 'danger' ?>">
                                                <?= htmlspecialchars($log['status']) ?>
                                            </span>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>

            </div>
            <nav>
  <ul class="pagination justify-content-center mt-3">
    <?php for ($i = 1; $i <= $totalPages; $i++): ?>
      <li class="page-item <?= ($i === $page) ? 'active' : '' ?>">
        <a class="page-link" href="?page=<?= $i ?>"><?= $i ?></a>
      </li>
    <?php endfor; ?>
  </ul>
</nav>
        </div>
    </div>
</div>

  <!-- App Sidebar -->
    <div class="modal fade panelbox panelbox-left" id="sidebarPanel" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-body p-0">
                    <!-- profile box -->
                    <div class="profileBox pt-2 pb-2">
                        <div class="image-wrapper">
                           <img src="<?= $avatar_url ?>" alt="User Avatar" class="imaged w32" style='h32 w32'>
                        </div>
                        <div class="in">
                             <span>Welcome, <b><?= htmlspecialchars($user['nick_name']) ?></b></span>
                         
                        </div>
                        <a href="#" class="btn btn-link btn-icon sidebar-close" data-bs-dismiss="modal">
                            <ion-icon name="close-outline"></ion-icon>
                        </a>
                    </div>
                    <!-- * profile box -->
                    <!-- balance -->
                    <div class="sidebar-balance">
                        <div class="listview-title">Balance</div>
                        <div class="in">
                        <h2 id="balance-amount" style="font-size: 20px; margin: 2px 0;">₦<?= number_format($balance, 2); ?></h2>
                        </div>
                    </div>
                    <!-- * balance -->




                    <!-- menu -->
                    <div class="listview-title mt-1">Menu</div>
                    <ul class="listview flush transparent no-line image-listview">
                        <li>
                            <a href="index.php" class="item">
                                <div class="icon-box bg-primary">
                               <ion-icon name="storefront-outline"></ion-icon>
                                </div>
                                <div class="in">
                                    Home Dashboard
                                   
                                </div>
                            </a>
                        </li>
                        
                         <li>
                            <a href="bulksms.php" class="item">
                                <div class="icon-box bg-primary">
                                    <ion-icon name="receipt-outline"></ion-icon>
                                </div>
                                <div class="in">
                                   Send SMS
                                </div>
                            </a>
                        </li>
                        
                         <li>
                            <a href="bulksms_history.php" class="item">
                                <div class="icon-box bg-primary">
                                    <ion-icon name="receipt-outline"></ion-icon>
                                </div>
                                <div class="in">
                                   SMS History
                                </div>
                            </a>
                        </li>
                        

                    <!-- others -->
                    <div class="listview-title mt-1">Others</div>
                    <ul class="listview flush transparent no-line image-listview">
                       <li>
                            <a href="support.php" class="item">
                                <div class="icon-box bg-primary">
                                    <ion-icon name="chatbubble-outline"></ion-icon>
                                </div>
                                <div class="in">
                                    Support
                                </div>
                            </a>
                        </li>
                        <li>
                            <a href="logout.php" class="item">
                                <div class="icon-box bg-primary">
                                    <ion-icon name="log-out-outline"></ion-icon>
                                </div>
                                <div class="in">
                                    Log out
                                </div>
                            </a>
                        </li>


                    </ul>
                    <!-- * others -->

                   

                </div>
            </div>
        </div>
    </div>
    <!-- * App Sidebar -->


<?php include 'footer.php'; ?>

</body>
</html>
