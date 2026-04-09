<?php
// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

include('db.php');
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

// Fetch user info
$stmt = $sql->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$user_id]);
$user = $stmt->fetch();

if (!$user) {
    header("Location: login.php");
    exit;
}

// Fetch sender ID request history
$history_stmt = $sql->prepare("SELECT * FROM sender_id_requests WHERE user_id = ? ORDER BY created_at DESC");
$history_stmt->execute([$user_id]);
$requests = $history_stmt->fetchAll(PDO::FETCH_ASSOC);
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

        table {
            width: 100%;
            font-size: 14px;
            border-collapse: collapse;
        }
        table th, table td {
            padding: 10px;
            border: 1px solid #ddd;
        }
        .badge {
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 12px;
            color: white;
        }
        .badge-pending { background-color: #f0ad4e; }
        .badge-approved { background-color: #5cb85c; }
        .badge-rejected { background-color: #d9534f; }
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
     <h3> Bulk SMS</h3>
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
            <div class="card-header">
                <h4>Sender ID Request History</h4>
            </div>
            <div class="card-body">
                <?php if (empty($requests)): ?>
                    <p>No sender ID requests found.</p>
                <?php else: ?>
                    <div class="table-responsive">
                        <table>
                            <thead>
                                <tr>
                                    <th>Sender ID</th>
                                    <th>Purpose</th>
                                    <th>Status</th>
                                    <th>Requested On</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($requests as $row): ?>
                                    <tr>
                                        <td><?= htmlspecialchars($row['sender_id']) ?></td>
                                        <td><?= nl2br(htmlspecialchars($row['purpose'])) ?></td>
                                        <td>
                                            <span class="badge badge-<?= strtolower($row['status']) ?>">
                                                <?= ucfirst($row['status']) ?>
                                            </span>
                                        </td>
                                        <td><?= date("d M Y, h:i A", strtotime($row['created_at'])) ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?php include 'footer.php'; ?>


</body>
</html>
