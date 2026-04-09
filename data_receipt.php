<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

include('db.php');

if (!isset($_GET['ref'])) {
    header('Location: index.php');
    exit();
}

session_start();
$ref = $_GET['ref'];
$user_id = $_SESSION['user_id'] ?? null;

$stmt = $sql->prepare("SELECT * FROM data_transactions WHERE order_id = ? LIMIT 1");
$stmt->execute([$ref]);
$tx = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$tx || ($user_id && $tx['user_id'] != $user_id)) {
    die("Transaction not found or access denied.");
}

// Format data
$amount     = number_format($tx['amount'], 2);
$network    = $tx['network'];
$number     = $tx['mobile_number'];
$desc       = $tx['plan_name'];
$status     = ucfirst($tx['status']);
$time       = date("M j, Y - g:i A", strtotime($tx['created_at'] ?? $tx['timestamp'] ?? 'now'));
$network_labels = [
    "MTN" => ["MTN", "https://upload.wikimedia.org/wikipedia/commons/9/93/New-mtn-logo.jpg"],
    "Glo" => ["Glo", "https://static-00.iconduck.com/assets.00/globacom-limited-icon-512x512-nsbqgsyf.png"],
    "9mobile" => ["9mobile", "https://upload.wikimedia.org/wikipedia/commons/6/69/9mobile_logo.png"],
    "Airtel" => ["Airtel", "https://upload.wikimedia.org/wikipedia/commons/f/f0/Airtel_logo.svg"]
];


[$networkName, $logo] = $network_labels[$network] ?? [$network, ''];
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Data Receipt</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>
  <style>
    body {
      background: #f5f5f5;
      font-family: 'Poppins', sans-serif;
    }

    .receipt-card {
      background: white;
      border-radius: 16px;
      padding: 25px;
      max-width: 430px;
      margin: 40px auto;
      box-shadow: 0 2px 10px rgba(0,0,0,0.07);
    }

    .network-logo {
      width: 60px;
      height: 60px;
      border-radius: 50%;
      object-fit: contain;
      margin-bottom: 10px;
    }

    .amount {
      font-size: 28px;
      font-weight: 600;
      color: green;
    }

    .receipt-details {
      margin-top: 25px;
    }

    .receipt-details .item {
      display: flex;
      justify-content: space-between;
      padding: 10px 0;
      border-bottom: 1px dashed #ccc;
    }

    .status-pill {
      background: #d1e7dd;
      color: #0f5132;
      padding: 6px 12px;
      font-size: 13px;
      border-radius: 30px;
      font-weight: 500;
    }

    .footer-actions {
      display: flex;
      gap: 10px;
      margin-top: 25px;
    }

    .footer-actions a,
    .footer-actions button {
      flex: 1;
    }

    .back-home {
      text-align: center;
      margin-top: 20px;
    }

    .back-home a {
      color: #dc3545;
      text-decoration: none;
    }

    .back-home a:hover {
      text-decoration: underline;
    }
  </style>
</head>
<body>

<div id="receipt-content" class="receipt-card">
  <div class="text-center">
    <?php if ($logo): ?>
      <img src="<?= htmlspecialchars($logo) ?>" class="network-logo" alt="Network Logo">
    <?php endif; ?>
    <h4><?= htmlspecialchars($networkName) ?> Data Purchase</h4>
    <div class="amount">₦<?= $amount ?></div>
    <div class="status-pill mt-2"><?= htmlspecialchars($status) ?></div>
  </div>

  <div class="receipt-details">
    <div class="item">
      <span>Network</span>
      <strong><?= htmlspecialchars($networkName) ?></strong>
    </div>
    <div class="item">
      <span>Phone Number</span>
      <strong><?= htmlspecialchars($number) ?></strong>
    </div>
    <div class="item">
      <span>Status</span>
      <strong><?= htmlspecialchars($status) ?></strong>
    </div>
    <div class="item">
      <span>Plan Description</span>
      <strong><?= htmlspecialchars($desc) ?></strong>
    </div>
    <div class="item">
      <span>Transaction Ref</span>
      <strong><?= htmlspecialchars($ref) ?></strong>
    </div>
    <div class="item">
      <span>Date & Time</span>
      <strong><?= $time ?></strong>
    </div>
  </div>

  <div class="footer-actions">
    <a href="index.php" class="btn btn-outline-primary">Back to Home</a>
    <button class="btn btn-success" onclick="downloadPDF()">Download PDF</button>
  </div>

  <div class="back-home">
    <a href="dispute_transaction.php?ref=<?= urlencode($ref) ?>">Dispute this transaction</a>
  </div>
</div>

<script>
  function downloadPDF() {
    const receipt = document.getElementById('receipt-content');
    html2pdf().from(receipt).set({
      margin: 10,
      filename: 'data_receipt_<?= htmlspecialchars($ref) ?>.pdf',
      html2canvas: { scale: 2 },
      jsPDF: { orientation: 'portrait', unit: 'mm', format: 'a4' }
    }).save();
  }
</script>

</body>
</html>

