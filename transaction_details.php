<?php
session_start();
include('db.php');

if (!isset($_GET['tx_ref'])) {
    die("Transaction reference missing.");
}

$tx_ref = $_GET['tx_ref'];

// Fetch transaction details
$query = "SELECT t.*, u.full_name, u.email FROM transactions t 
          JOIN users u ON t.user_id = u.id 
          WHERE t.transaction_ref = :tx_ref";
$stmt = $sql->prepare($query);
$stmt->bindParam(':tx_ref', $tx_ref);
$stmt->execute();
$transaction = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$transaction) {
    die("Transaction not found.");
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
    <title>Finapp</title>
    <meta name="description" content="Finapp HTML Mobile Template">
    <meta name="keywords" content="bootstrap, wallet, banking, fintech mobile template, cordova, phonegap, mobile, html, responsive" />
    <link rel="icon" type="image/png" href="assets/img/favicon.png" sizes="32x32">
    <link rel="apple-touch-icon" sizes="180x180" href="assets/img/icon/192x192.png">
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="manifest" href="__manifest.json">
</head>

<body>

<!-- App Header -->
<div class="appHeader">
    <div class="left">
        <a href="#" class="headerButton goBack">
            <ion-icon name="chevron-back-outline"></ion-icon>
        </a>
    </div>
    <div class="pageTitle">
        Transaction Detail
    </div>
    <div class="right">
        <a href="#" class="headerButton" data-bs-toggle="modal" data-bs-target="#DialogBasic">
            <ion-icon name="volume-mute-outline"></ion-icon>
        </a>
    </div>
</div>
<!-- * App Header -->

<!-- App Capsule -->
<div id="appCapsule">

    <div class="section mt-2 mb-2">

        <div class="listed-detail mt-3 text-center">
            <div class="icon-wrapper">
                <div class="iconbox bg-success">
                    <ion-icon name="checkmark-circle-outline"></ion-icon>
                </div>
            </div>
            <h3 class="text-success mt-2">✔ Successful</h3>
            <p class="text-muted small">Fast Transfer Safeguard <span class="text-primary">Completed</span></p>
        </div>

        <div class="text-center mt-2">
            <h1 class="text-primary">&#8358;<?= number_format($transaction['amount'], 2) ?></h1>
            <p class="lead mb-0">To <?= htmlspecialchars($transaction['full_name']) ?></p>
        </div>

        <ul class="listview flush transparent simple-listview no-space mt-4">
            <li>
                <strong>Transaction Type</strong>
                <span>Deposit</span>
            </li>
            <li>
                <strong>Transaction ID</strong>
                <span><?= htmlspecialchars($transaction['transaction_ref']) ?></span>
            </li>
            <li>
                <strong>Payment Method</strong>
                <span>Direct Top-up</span>
            </li>
            <li>
                <strong>Date</strong>
                <span><?= date('M d, Y H:i A', strtotime($transaction['created_at'])) ?></span>
            </li>
        </ul>

        <div class="mt-4">
            <h5 class="text-center">Recipient</h5>
            <ul class="listview flush transparent simple-listview no-space">
                <li>
                    <strong>Name</strong>
                    <span><?= htmlspecialchars($transaction['full_name']) ?></span>
                </li>
                <li>
                    <strong>Email</strong>
                    <span><?= htmlspecialchars($transaction['email']) ?></span>
                </li>
            </ul>
        </div>

        <div class="mt-4 text-center">
            <button class="btn btn-primary btn-block" onclick="downloadImage()">📄 Share as Image</button>
            <button class="btn btn-outline-primary btn-block mt-1" onclick="downloadPDF()">📥 Save as PDF</button>
            <a href="index.php" class="btn btn-secondary btn-block mt-1">🏠 Return to Dashboard</a>
        </div>

    </div>

</div>
<!-- * App Capsule -->

<!-- Dialog for Delete (Optional use) -->
<div class="modal fade dialogbox" id="DialogBasic" data-bs-backdrop="static" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Mute Sound</h5>
            </div>
            <div class="modal-body">
                Notification sound disabled.
            </div>
            <div class="modal-footer">
                <div class="btn-inline">
                    <a href="#" class="btn btn-text-secondary" data-bs-dismiss="modal">OK</a>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
function downloadPDF() {
    const { jsPDF } = window.jspdf;
    let doc = new jsPDF();

    let receipt = document.querySelector(".receipt");
    html2canvas(receipt, { scale: 2 }).then(canvas => {
        let imgData = canvas.toDataURL("image/png");
        let imgWidth = 190;
        let imgHeight = (canvas.height * imgWidth) / canvas.width;

        doc.addImage(imgData, "PNG", 10, 10, imgWidth, imgHeight);
        doc.save("Transaction_Receipt.pdf");
    });
}

function downloadImage() {
    let receipt = document.querySelector(".receipt");
    html2canvas(receipt, { scale: 2 }).then(canvas => {
        let link = document.createElement("a");
        link.href = canvas.toDataURL("image/png");
        link.download = "Transaction_Receipt.png";
        link.click();
    });
}
</script>

    <!-- ========= JS Files =========  -->
    <!-- Bootstrap -->
    <script src="assets/js/lib/bootstrap.bundle.min.js"></script>
    <!-- Ionicons -->
    <script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>
    <!-- Splide -->
    <script src="assets/js/plugins/splide/splide.min.js"></script>
    <!-- Base Js File -->
    <script src="assets/js/base.js"></script>

    <script>
        // Add to Home with 2 seconds delay.
        AddtoHome("2000", "once");
    </script>

</body>

</html>