<?php
// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

include('db.php');

session_start();

// Ensure user is logged in
if (!isset($_SESSION['user_id']) || empty($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$user_id = $_SESSION['user_id'];

// Fetch user balance
$user_balance = 0;
$stmt = $sql->prepare("SELECT full_name, balance FROM users WHERE id = ?");
$stmt->execute([$user_id]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if ($user) {
    $full_name = $user['full_name'];
    $user_balance = (float)$user['balance'];
} else {
    $full_name = "Unknown";
    $user_balance = 0;
}

// Get order reference
$ref = $_GET['ref'] ?? '';
if (!$ref) die("Invalid request: missing reference");

$stmt = $sql->prepare("
    SELECT c.*, u.full_name, u.email, u.phone_number
    FROM cart_orders c
    LEFT JOIN users u ON c.user_id = u.id
    WHERE c.reference = ? AND c.user_id = ?
");
$stmt->execute([$ref, $user_id]);
$order = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$order) {
    die("Order not found or access denied.");
}


// Decode cart JSON
$cart = json_decode($order['cart_data'], true);

// Fetch shop bank details filtered by shop_id
$stmt = $sql->prepare("SELECT * FROM shop_banks WHERE shop_id = ? LIMIT 1");
$stmt->execute([$order['shop_id']]);
$bank = $stmt->fetch(PDO::FETCH_ASSOC);

// Prepare order totals
$subtotal = 0;
foreach ($cart as $item) {
    $subtotal += $item['price'] * $item['quantity'];
}
$delivery_fee = $order['delivery_fee'];
$pay_amount = $order['pay_amount'];
$paid_amount = $order['pay_amount'];
?>




<!DOCTYPE html> 

<html lang="en">    

<head> 
<meta charset="UTF-8">

<meta name="viewport"
content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1, viewport-fit=cover" /> <meta name="apple-mobile-web-app-capable" content="yes" /> <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent"> <meta name="theme-color" content="#000000"> <title>YenTown Hub</title> <meta name="description" content="Finapp HTML Mobile Template"> <meta name="keywords" content="bootstrap, wallet, banking, fintech mobile template, cordova, phonegap, mobile, html, responsive" /> <link rel="icon" type="image/png" href="assets/img/favicon.png" sizes="32x32"> <link rel="apple-touch-icon" sizes="180x180" href="assets/img/icon/192x192.png"> <link rel="stylesheet" href="assets/css/style.css"> <link rel="manifest" href="__manifest.json"> <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script><link rel="stylesheet" href="styles.css"> 
  <style>
   body {
      font-family: 'Segoe UI', sans-serif;
      background-color: #f8f9fa;
      padding-bottom: 100px; /* space for footer */
    }
    .stamp {
      font-size: 1.5rem;
      font-weight: bold;
      color: #3f6ad8;
      border: 2px dashed #3f6ad8;
      display: inline-block;
      padding: 6px 12px;
      border-radius: 5px;
      margin-bottom: 15px;
    }
    .footer {
      position: fixed;
      left: 0;
      bottom: 0;
      width: 100%;
      background: #fff;
      border-top: 1px solid #ccc;
      text-align: center;
      padding: 10px 15px;
      font-size: 14px;
      color: #555;
    }
    .table th, .table td {
      vertical-align: middle;
    }
    .badge {
      font-size: 0.9rem;
    }
/* Fixed Bottom Navigation */ 
.nav 
{ 
    position: fixed; 
    bottom: 0; left: 50%; 
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
    flex-direction: 
    column; align-items: 
    center; gap: 3px; 
    flex: 1; transition: 
    color 0.3s ease; 
}

.nav a i 
{ font-size: 20px; 
color: gray; transition: 
color 0.3s ease; 
}

.nav a span {
    font-size: 12px; 
    font-weight: 500; 
    
}

.nav a.active i, .nav a.active span {
    color: green;
    font-weight: bold;
    }

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
<div class="appHeader">
    <div class="left">
        <a href="#" class="headerButton goBack">
            <ion-icon name="chevron-back-outline"></ion-icon>
        </a>
    </div>
    <div class="pageTitle">
     <h3> Shopping Invoice Payment</h3>
    </div>
   <div class="right">
    <div style="position: relative; padding-right: 12px;" onclick="showCartModal()">
        <i class="fas fa-shopping-cart fa-lg"></i>
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

  <div class="container mt-0">
  <div class="card shadow-sm mb-4">
    <div class="card-body">
      <div class="d-flex justify-content-between align-items-center mb-3">
        <div class="stamp">DtheHub Shopping Order</div>
        <!-- Optional Logo -->
        <!-- <img src="assets/img/logo.png" alt="DtheHub Logo" style="height:40px;"> -->
      </div>

      <h4 class="mb-2">Invoice Reference: <?= htmlspecialchars($ref); ?></h4>
      <p><strong>Tracking ID:</strong> <?= htmlspecialchars($order['tracking_id'] ?? 'N/A'); ?></p>

      <hr>

      <h5 class="fw-bold mt-3">User Details</h5>
      <p class="mb-1">
        <strong>Name:</strong> <?= htmlspecialchars($order['full_name'] ?? 'N/A'); ?><br>
        <strong>Email:</strong> <?= htmlspecialchars($order['email'] ?? 'N/A'); ?><br>
        <strong>Phone:</strong> <?= htmlspecialchars($order['phone_number'] ?? 'N/A'); ?>
      </p>

      <p class="mb-2"><strong>Delivery Address:</strong><br><?= nl2br(htmlspecialchars($order['delivery_address'])); ?></p>
      <p class="mb-0"><strong>Payment Method:</strong> <?= ucfirst($order['payment_method']); ?></p>
    </div>
  </div>
</div>


    <div class="card shadow-sm mb-2">
      <div class="card-body p-0">
        <div class="table-responsive">
          <table class="table table-bordered mb-0">
            <thead class="table-light">
              <tr>
                <th>Product</th>
                <th class="text-center" style="width: 70px;">Qty</th>
                <th class="text-end" style="width: 120px;">Unit Price</th>
                <th class="text-end" style="width: 120px;">Total</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($cart as $item): ?>
              <tr>
                <td><?= htmlspecialchars($item['name']); ?></td>
                <td class="text-center"><?= (int)$item['quantity']; ?></td>
                <td class="text-end">₦<?= number_format($item['price'], 2); ?></td>
                <td class="text-end">₦<?= number_format($item['price'] * $item['quantity'], 2); ?></td>
              </tr>
              <?php endforeach; ?>
              <tr>
                <td colspan="3" class="text-end fw-bold">Subtotal</td>
                <td class="text-end">₦<?= number_format($subtotal, 2); ?></td>
              </tr>
              <tr>
                <td colspan="3" class="text-end fw-bold">Delivery Fee</td>
                <td class="text-end">₦<?= number_format($delivery_fee, 2); ?></td>
              </tr>
              <tr>
                <td colspan="3" class="text-end fw-bold">Total</td>
                <td class="text-end"><strong>₦<?= number_format($pay_amount, 2); ?></strong></td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>

    <div class="mb-4">
      <p>Payment Status: 
        <?php if ($order['status'] == 'pending'): ?>
          <span class="badge bg-warning text-dark">Pending</span>
        <?php elseif ($order['status'] == 'approved'): ?>
          <span class="badge bg-success">Approved</span>
        <?php else: ?>
          <span class="badge bg-secondary"><?= htmlspecialchars($order['status']); ?></span>
        <?php endif; ?>
      </p>

     <?php if ($order['status'] == 'pending'): ?>
    <?php if ($user_balance >= $pay_amount): ?>
     <button class="btn btn-primary" onclick="completePayment('<?= $ref ?>')">
    Complete Payment</button>
        <p class="text-success mt-2">Wallet Balance: ₦<?= number_format($user_balance, 2); ?></p>
    <?php else: ?>
        <button class="btn btn-secondary" disabled> Insufficient Balance </button>
        <p class="text-danger mt-2">Wallet Balance: ₦<?= number_format($user_balance, 2); ?> (Required: ₦<?= number_format($pay_amount, 2); ?>)</p>
    <?php endif; ?>
<?php endif; ?>


  <!-- Payment Modal -->
  <div class="modal fade" id="paymentModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content p-3">
        <h5>Make Payment to this Account</h5>
        <?php if ($bank): ?>
          <p><strong>Bank:</strong> <?= htmlspecialchars($bank['bank_name']); ?></p>
          <p><strong>Account Number:</strong> <?= htmlspecialchars($bank['account_number']); ?></p>
          <p><strong>Account Name:</strong> <?= htmlspecialchars($bank['account_name']); ?></p>
        <?php else: ?>
          <p>No bank details found for this shop.</p>
        <?php endif; ?>

        <p>Please pay <strong>₦<?= number_format($pay_amount, 2); ?></strong> and upload your payment receipt below.</p>

        <form action="upload_receipt.php" method="POST" enctype="multipart/form-data">
          <input type="hidden" name="ref" value="<?= htmlspecialchars($ref); ?>">
          <div class="mb-3">
            <label for="receipt" class="form-label">Upload Payment Receipt (PDF/JPG/PNG)</label>
            <input type="file" class="form-control" id="receipt" name="receipt" required />
          </div>
          <button type="submit" class="btn btn-success">Upload Receipt</button>
        </form>
      </div>
    </div>
  </div>

  <!-- Footer -->
  <div class="footer">
    &copy; <?= date('Y'); ?> DtheHub &mdash; Empowering Local Commerce
  </div>

<script>
function completePayment(ref) {
    Swal.fire({
        title: "Confirm Payment",
        text: "Pay for this order from your wallet?",
        icon: "question",
        showCancelButton: true,
        confirmButtonText: "Yes, Pay Now",
        cancelButtonText: "Cancel"
    }).then((result) => {
        if (result.isConfirmed) {
            $.post('pay_order.php', { reference: ref }, function(res) {
                if (res.success) {
                    Swal.fire("Success", res.message, "success").then(() => {
                        window.location.href = "transactions.php";
                    });
                } else if (res.status === 'insufficient') {
                    Swal.fire("Error", "Insufficient wallet balance.", "error");
                } else {
                    Swal.fire("Error", res.message, "error");
                }
            }, "json");
        }
    });
}

</script>


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
