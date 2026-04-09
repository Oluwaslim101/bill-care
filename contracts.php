<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include('db.php');
session_start();

// Ensure user is logged in
if (!isset($_SESSION['user_id']) || empty($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$user_id = $_SESSION['user_id'];

// Generate 6-character transaction reference
function generateTransactionRef($length = 6) {
    $characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
    $ref = '';
    for ($i = 0; $i < $length; $i++) {
        $ref .= $characters[random_int(0, strlen($characters) - 1)];
    }
    return $ref;
}

// Ensure uniqueness of transaction_ref
function generateUniqueTransactionRef($sql) {
    do {
        $ref = generateTransactionRef();
        $stmt = $sql->prepare("SELECT COUNT(*) FROM user_contracts WHERE transaction_ref = ?");
        $stmt->execute([$ref]);
        $exists = $stmt->fetchColumn();
    } while ($exists > 0);
    return $ref;
}

// Fetch user data
$query = "SELECT * FROM users WHERE id = ?";
$stmt = $sql->prepare($query);
$stmt->execute([$user_id]);
$user = $stmt->fetch();

if (!$user) {
    header('Location: login.php');
    exit();
}

$balance = $user['balance'];
$referred_by = $user['referred_by'];

// Fetch earnings from completed contracts (total profit)
$earnings_query = "SELECT SUM(purchased_amount * profit / 100) AS total_earnings 
                   FROM user_contracts 
                   WHERE user_id = ? AND status = 'completed'";

$earnings_stmt = $sql->prepare($earnings_query);
$earnings_stmt->execute([$user_id]);
$earnings = $earnings_stmt->fetchColumn() ?? 0;

// Display earnings
echo number_format($earnings, 2);

// Notifications
$notifications_query = "SELECT * FROM notifications WHERE user_id = ? AND status = 'unread'";
$notifications_stmt = $sql->prepare($notifications_query);
$notifications_stmt->execute([$user_id]);
$unread_count = $notifications_stmt->rowCount();

// Fetch available contracts
$contracts_query = "SELECT * FROM contracts WHERE status = 'active'";
$contracts_stmt = $sql->prepare($contracts_query);
$contracts_stmt->execute();
$contracts = $contracts_stmt->fetchAll();

// Process contract purchase
if (isset($_POST['purchase_contract'])) {
    $contract_id = $_POST['contract_id'];
    $amount = $_POST['amount'];

    // Fetch contract details
    $contract_query = "SELECT * FROM contracts WHERE id = ?";
    $contract_stmt = $sql->prepare($contract_query);
    $contract_stmt->execute([$contract_id]);
    $contract = $contract_stmt->fetch();

    if ($contract && $contract['status'] === 'active') {
        if ($amount <= $balance) {
            // Deduct balance
            $new_balance = $balance - $amount;
            $sql->prepare("UPDATE users SET balance = ? WHERE id = ?")->execute([$new_balance, $user_id]);

            $start_date = date('Y-m-d H:i:s');
            $end_date = date('Y-m-d H:i:s', strtotime("+{$contract['duration']} days"));
            $purchase_date = date('Y-m-d H:i:s');
            $status = 'active';

            // Calculate daily earnings
            $daily_earnings = ($amount * $contract['profit'] / 100) / $contract['duration'];
            
            // Generate unique transaction reference
            $transaction_ref = generateUniqueTransactionRef($sql);

            // Insert contract into user_contracts
            $sql->prepare("INSERT INTO user_contracts 
                (user_id, contract_id, purchased_amount, profit, start_date, end_date, status, purchase_date, transaction_ref, daily_earnings, days_paid, last_paid_date) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 0, NULL)")
                ->execute([$user_id, $contract_id, $amount, $contract['profit'], $start_date, $end_date, $status, $purchase_date, $transaction_ref, $daily_earnings]);

            // Referral bonus
            if ($referred_by) {
                $bonus = $amount * 0.05;
                $sql->prepare("UPDATE users SET bonus = bonus + ? WHERE id = ?")->execute([$bonus, $referred_by]);

                $message = "You earned a referral bonus of $" . number_format($bonus, 2) . " for a successful contract purchase by your referral.";
                $sql->prepare("INSERT INTO notifications (user_id, action_type, message, status, created_at) 
                    VALUES (?, 'referral_bonus', ?, 'unread', NOW())")
                    ->execute([$referred_by, $message]);
            }

            // Notify purchaser
            $message = $referred_by 
                ? "Contract Purchase of $" . number_format($amount, 2) . " is successful <br>Your Referee has received a bonus for this Transaction."
                : "You have successfully purchased a contract of $" . number_format($amount, 2) . " Thank you!";
            $sql->prepare("INSERT INTO notifications (user_id, action_type, message, status, created_at) 
                VALUES (?, 'contract_purchase', ?, 'unread', NOW())")
                ->execute([$user_id, $message]);

            // Send email notification
            include('send_contract_email.php');
            sendContractPurchaseEmail($user['email'], $user['full_name'], $amount, $transaction_ref);

            // ✅ Success SweetAlert with ref
            echo "<script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>";
            echo "<script>
                Swal.fire({
                    icon: 'success',
                    title: 'Purchase Successful',
                    html: 'Your contract purchase was successful.<br><strong>Transaction Ref:</strong> {$transaction_ref}',
                    confirmButtonText: 'OK'
                }).then(() => {
                    window.location.href = 'index.php';
                });
            </script>";
            exit();
        } else {
            // ❌ Insufficient balance
            echo "<script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>";
            echo "<script>
                Swal.fire({
                    icon: 'error',
                    title: 'Insufficient Balance',
                    text: 'You do not have enough balance to purchase this contract.',
                    confirmButtonText: 'OK'
                }).then(() => {
                    window.location.href = 'index.php';
                });
            </script>";
            exit();
        }
    } else {
        echo "<script>alert('Invalid contract selected.'); window.location.href='index.php';</script>";
        exit();
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
    <title>Swift - Contract</title>
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

    </style>
</head>
<body>

<!-- Header -->
 <!-- App Header -->
<div class="appHeader">
    <div class="left">
        <a href="#" class="headerButton goBack">
            <ion-icon name="chevron-back-outline"></ion-icon>
        </a>
    </div>
    <div class="pageTitle">
        Available Contracts
    </div>
    <div class="right">
        <div style="position: relative;">
            <i class="fas fa-bell" style="font-size: 25px; color: blue;"></i>
            <?php if ($unread_count > 0): ?>
                <span style="
                    position: absolute;
                    top: -5px;
                    right: -5px;
                    background: red;
                    color: white;
                    font-size: 10px;
                    font-weight: bold;
                    padding: 2px 6px;
                    border-radius: 50%;
                ">
                    <?= $unread_count ?>
                </span>
            <?php endif; ?>
        </div>
    </div>
</div>

    <!-- App Capsule -->
    <div id="appCapsule">

    
  <!-- Stats -->
<div class="section full mt-0">
        <div class="row mt-0">
            <div class="col-6">
                <div class="stat-box">
                    <div class="title text-muted">Balance</div>
                      <h3 class="balance-amount text-success">$<?= number_format($balance, 2); ?></h3>
                </div>
            </div>
            <div class="col-6">
                <div class="stat-box ">
                    <div class="title text-muted"> Earnings</div>
 <h3 class="balance-amount text-success">$<?= number_format($earnings ?? 0, 2) ?></h3>
                </div>
            </div>
        </div>
    </div>
<!-- * Stats -->

<!-- Contracts Styled Like Transactions -->
<div class="section mt-1 full">
    <div class="section-title">Available Contracts</div>
    <div class="transactions">
        <?php foreach ($contracts as $index => $contract): ?>
            <a href="#" class="item" data-bs-toggle="modal" data-bs-target="#contractModal<?= $index; ?>">
                <div class="detail">
                    <img src="assets/img/sample/brand/3.jpg" alt="Contract" class="image-block imaged w48">
                    <div>
                        <strong><?= htmlspecialchars($contract['investment_name']); ?></strong>
                        <p>
                            <?= htmlspecialchars($contract['duration']); ?> days |
                            <?= htmlspecialchars($contract['profit']); ?>% profit
                        </p>
                    </div>
                </div>
                <div class="right">
                    <div class="price text-warning" style="font-size: 14px; font-weight: 600;">
                                    
              <p>Minimum Amt</p>
                    
                         $<?= number_format($contract['amount_gauge'], 2); ?>
                    </div>
                </div>
            </a>

            <!-- Contract Modal -->
            <div class="modal fade action-sheet" id="contractModal<?= $index; ?>" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content rounded-top">
                        <div class="modal-header pb-0 border-0">
                            <h5 class="modal-title"><?= htmlspecialchars($contract['investment_name']); ?></h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>

                        <div class="modal-body">
                            <div class="action-sheet-content">
                                <ul class="listview flush transparent no-line image-listview detailed-list mb-2">
                                    <li>
                                        <div class="in">
                                            <div><strong>Amount Gauge (Min.)</strong></div>
                                            <div class="text-end">$<?= number_format($contract['amount_gauge'], 2); ?></div>
                                        </div>
                                    </li>
                                    <li>
                                        <div class="in">
                                            <div><strong>Profit Rate</strong></div>
                                            <div class="text-end"><?= htmlspecialchars($contract['profit']); ?>%</div>
                                        </div>
                                    </li>
                                    <li>
                                        <div class="in">
                                            <div><strong>Duration</strong></div>
                                            <div class="text-end"><?= htmlspecialchars($contract['duration']); ?> days</div>
                                        </div>
                                    </li>
                                </ul>

                                <form method="POST" action="">
                                    <input type="hidden" name="contract_id" value="<?= $contract['id']; ?>">
                                    <input type="hidden" id="profitRate<?= $index; ?>" value="<?= $contract['profit']; ?>">
                                    <input type="hidden" id="minAmount<?= $index; ?>" value="<?= $contract['amount_gauge']; ?>">

                                    <div class="form-group basic">
                                        <label class="label" for="amount<?= $index; ?>">Enter Amount</label>
                                        <div class="input-group">
                                            <span class="input-group-text">$</span>
                                            <input type="number" id="amount<?= $index; ?>" name="amount" class="form-control" placeholder="Enter amount" oninput="calculateProfit(<?= $index; ?>)" required>
                                        </div>
                                        <div class="input-info">
                                            Minimum $<?= number_format($contract['amount_gauge']); ?>
                                        </div>
                                    </div>

                                    <div class="form-group basic">
                                        <div id="profitPreview<?= $index; ?>" class="text-success text-center mb-1"></div>
                                        <div id="errorPreview<?= $index; ?>" class="text-danger text-center mb-2"></div>
                                    </div>

                                    <div class="form-group basic">
                                        <button type="submit" id="purchaseBtn<?= $index; ?>" name="purchase_contract" class="btn btn-primary btn-block btn-lg" disabled>
                                            Purchase Contract
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- * Contract Modal -->
        <?php endforeach; ?>
    </div>
</div>

<script>
    
    // Function to fetch notifications and update the modal

function calculateProfit(index) {
    var amountInput = document.getElementById('amount' + index);
    var profitRate = document.getElementById('profitRate' + index).value;
    var minAmount = document.getElementById('minAmount' + index).value;
    var profitPreview = document.getElementById('profitPreview' + index);
    var errorPreview = document.getElementById('errorPreview' + index);
    var purchaseBtn = document.getElementById('purchaseBtn' + index);

    var amount = parseFloat(amountInput.value);

    if (!isNaN(amount)) {
        if (amount >= minAmount) {
            var profit = (amount * profitRate / 100).toFixed(2);
            profitPreview.innerHTML = "Expected Profit: <strong>$" + profit + " </strong>";
            errorPreview.innerHTML = "";
            purchaseBtn.disabled = false;
        } else {
            profitPreview.innerHTML = "";
            errorPreview.innerHTML = "Amount must be at least " + parseFloat(minAmount).toLocaleString() + " $.";
            purchaseBtn.disabled = true;
        }
    } else {
        profitPreview.innerHTML = "";
        errorPreview.innerHTML = "";
        purchaseBtn.disabled = true;
    }
}
</script>
    
    <!-- Bottom Navigation -->
    <br>
<nav class="nav">   
<a href="index.php" >    
    <i class="fas fa-home"></i>    
    <span>Home</span>    
</a>    
<a href="rewards.php">    
    <i class="fas fa-gift"></i>    
    <span>Rewards</span>    
</a>    
<a href="contracts.php" class="active">    
    <i class="fas fa-receipt"></i>    
    <span>Contracts</span>    
</a>   

<a href="transactions.php">    
    <i class="fas fa-receipt"></i>    
    <span>Transactions</span>    
</a>    
<a href="profile.php">    
    <i class="fas fa-user"></i>    
    <span>Profile</span>    
</a>

</nav>    
    <!-- * App Bottom Menu -->


    <!-- ========= JS Files =========  -->
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