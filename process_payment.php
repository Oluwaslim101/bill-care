<?php

session_start();

include('db.php'); // Ensure database connection

$flutterwave_secret_key = FLW_SECRET_KEY;

// Handle deposit form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['amount'])) {
    if (!isset($_SESSION['user_id'])) {
        die("User not logged in.");
    }

    $amount = floatval($_POST['amount']);
    $transaction_ref = "TXN_" . time() . "_" . rand(1000, 9999);

    if ($amount < 10) {
        die("Minimum deposit amount is ₦10.");
    }

    // Flutterwave API call to initialize payment
    $data = [
        "tx_ref" => $transaction_ref,
        "amount" => $amount,
        "currency" => "NGN",
        "redirect_url" => "https://swiftaffiliates.cloud/current/process_payment.php?tx_ref=$transaction_ref",
        "customer" => [
            "email" => $_SESSION['email'],
            "name" => $_SESSION['full_name']
        ],
        "customizations" => [
            "title" => "Wallet Deposit",
            "description" => "Fund your wallet"
        ]
    ];

    $ch = curl_init("https://api.flutterwave.com/v3/payments");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        "Authorization: Bearer $flutterwave_secret_key",
        "Content-Type: application/json"
    ]);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));

    $response = json_decode(curl_exec($ch), true);
    curl_close($ch);

    if ($response && $response['status'] === "success") {
        header("Location: " . $response['data']['link']); // Redirect to Flutterwave
        exit;
    } else {
        die("Payment initialization failed.");
    }
}

// Handle transaction verification after redirect
if (isset($_GET['tx_ref'])) {
    $tx_ref = $_GET['tx_ref'];

    // Verify transaction via Flutterwave API
    $ch = curl_init("https://api.flutterwave.com/v3/transactions/verify_by_reference?tx_ref=$tx_ref");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        "Authorization: Bearer $flutterwave_secret_key",
        "Content-Type: application/json"
    ]);

    $response = json_decode(curl_exec($ch), true);
    curl_close($ch);

    if ($response && $response['status'] === "success") {
        $amount = $response['data']['amount'];
        $user_id = $_SESSION['user_id'];

        // Check if transaction already exists
        $stmt = $sql->prepare("SELECT id FROM transactions WHERE transaction_ref = ?");
        $stmt->execute([$tx_ref]);

        if ($stmt->rowCount() == 0) {
            // Update user balance
            $stmt = $sql->prepare("UPDATE users SET balance = balance + ? WHERE id = ?");
            $stmt->execute([$amount, $user_id]);

            // Insert transaction record
            $stmt = $sql->prepare("INSERT INTO transactions (user_id, amount, transaction_ref, type, created_at) 
                                   VALUES (?, ?, ?, 'deposit', NOW())");
            $stmt->execute([$user_id, $amount, $tx_ref]);
        }

        // Fetch transaction details for receipt
        $stmt = $sql->prepare("SELECT * FROM transactions WHERE transaction_ref = ?");
        $stmt->execute([$tx_ref]);
        $transaction = $stmt->fetch(PDO::FETCH_ASSOC);
    } else {
        die("Transaction verification failed.");
    }
}
?>

<!DOCTYPE html>  <html lang="en">    <head>    
    <meta charset="UTF-8">    
<meta name="viewport"
        content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1, viewport-fit=cover" />
    <meta name="apple-mobile-web-app-capable" content="yes" />
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <meta name="theme-color" content="#000000">
    <title>Finapp</title>
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
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <link rel="stylesheet" href="styles.css"> 

    <style>
        body {
            text-align: center;
            font-family: Arial, sans-serif;
            background-color: #f8f9fa;
            padding: 20px;
        }

        .container {
            max-width: 400px;
            margin: auto;
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
        }

        .btn {
            margin-top: 15px;
            padding: 10px;
            background: green;
            color: white;
            text-decoration: none;
            display: inline-block;
            border-radius: 6px;
            border: none;
            width: 100%;
        }

        .btn:hover {
            background: darkgreen;
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

<!-- Loader -->
<div id="loader">
    <img src="assets/img/loading-icon.png" alt="icon" class="loading-icon">
</div>

 <!-- App Header -->
<div class="appHeader">
    <div class="left">
        <a href="#" class="headerButton goBack">
            <ion-icon name="chevron-back-outline"></ion-icon>
        </a>
    </div>
    <div class="pageTitle">
        Add Funds
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



<?php if (!isset($_GET['tx_ref'])): ?>
    <!-- Deposit Form -->

        <h2>Deposit Funds</h2>
        <form action="process_payment.php" method="POST">
            <label for="amount"><strong>Enter Amount (₦):</strong></label>
            <input type="number" name="amount" id="amount" class="form-control" min="10" required placeholder="₦10 Minimum">
            <button type="submit" class="btn">Proceed to Payment</button>
        </form>
    </div>
<?php else: ?>

    <!-- Deposit Receipt -->
    <div class="container">
        <h2>Deposit Successful! 🎉</h2>
        <p><strong>Amount:</strong> ₦<?= number_format($transaction['amount'], 2) ?></p>
        <p><strong>Transaction ID:</strong> <?= htmlspecialchars($transaction['transaction_ref']) ?></p>
        <p><strong>Date:</strong> <?= $transaction['created_at'] ?></p>
        <a href="index.php" class="btn">Back to Home</a>
    </div>
<?php endif; ?>




<!-- Bottom Navigation -->
<nav class="nav">   
<a href="index.php" class="active">    
    <i class="fas fa-home"></i>    
    <span>Home</span>    
</a>    
<a href="rewards.php">    
    <i class="fas fa-gift"></i>    
    <span>Rewards</span>    
</a>    
<a href="contracts.php">    
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