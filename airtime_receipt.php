<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
include('db.php');

session_start();
$ref = $_GET['ref'] ?? '';
$user_id = $_SESSION['user_id'] ?? null;

if (!$ref || !$user_id) {
    header('Location: index.php');
    exit();
}

$stmt = $sql->prepare("SELECT * FROM airtime_transactions WHERE order_id = ? LIMIT 1");
$stmt->execute([$ref]);
$tx = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$tx || $tx['user_id'] != $user_id) {
    die("Transaction not found or access denied.");
}

$amount     = number_format($tx['amount'], 2);
$network    = ucfirst($tx['network']);
$number     = $tx['mobile_number'];
$status     = ucfirst($tx['status']);
$time       = date("M j, Y - g:i A", strtotime($tx['created_at'] ?? 'now'));

$network_labels = [
    "mtn" => ["MTN", "https://upload.wikimedia.org/wikipedia/commons/9/93/New-mtn-logo.jpg"],
    "glo" => ["Glo", "https://static-00.iconduck.com/assets.00/globacom-limited-icon-512x512-nsbqgsyf.png"],
    "9mobile" => ["9Mobile", "https://upload.wikimedia.org/wikipedia/commons/6/69/9mobile_logo.png"],
    "airtel" => ["Airtel", "https://upload.wikimedia.org/wikipedia/commons/f/f0/Airtel_logo.svg"]
];

[$networkName, $logo] = $network_labels[strtolower($network)] ?? [$network, ''];
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Airtime Receipt</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
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

    .footer-actions a, .footer-actions button {
      flex: 1;
    }
  </style>
</head>
<body>

<div class="receipt-card" id="receiptCard">
  <div class="text-center">
    <?php if ($logo): ?>
      <img src="<?= htmlspecialchars($logo) ?>" class="network-logo" alt="Network Logo">
    <?php endif; ?>
    <h4><?= htmlspecialchars($networkName) ?> Airtime Purchase</h4>
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
      <span>Transaction Ref</span>
      <strong><?= htmlspecialchars($ref) ?></strong>
    </div>
    <div class="item">
      <span>Date & Time</span>
      <strong><?= $time ?></strong>
    </div>
  </div>

  <div class="footer-actions">
    <a href="index.php" class="btn btn-outline-primary">Home</a>
    <button class="btn btn-secondary" onclick="downloadPDF()">Download PDF</button>
    <a href="dispute.php?ref=<?= urlencode($ref) ?>" class="btn btn-danger">Dispute</a>
  </div>
</div>

<script>
  async function downloadPDF() {
    const { jsPDF } = window.jspdf;
    const doc = new jsPDF();

    const ref = "<?= $ref ?>";
    const amount = "<?= $amount ?>";
    const network = "<?= $networkName ?>";
    const number = "<?= $number ?>";
    const status = "<?= $status ?>";
    const time = "<?= $time ?>";

    doc.setFontSize(16);
    doc.text("Airtime Receipt", 20, 20);
    doc.setFontSize(12);
    doc.text(`Network: ${network}`, 20, 35);
    doc.text(`Phone Number: ${number}`, 20, 45);
    doc.text(`Amount: ₦${amount}`, 20, 55);
    doc.text(`Status: ${status}`, 20, 65);
    doc.text(`Transaction Ref: ${ref}`, 20, 75);
    doc.text(`Date & Time: ${time}`, 20, 85);

    doc.save(`Airtime_Receipt_${ref}.pdf`);
  }
</script>

</body>
</html>