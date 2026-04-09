<?php
// Process form and call Nelobyte API if POSTed
$cards = [];
$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = "CK100028738";
$api_key = "U66B634EJ9AE5227OEP008IMIQ6V895814L0E38G90RKV1DFH9ASHARH6876YZH8";

    $network = $_POST['network'];
    $amount = (int) $_POST['amount'];
    $quantity = (int) $_POST['quantity'];

    $data = [
        "user_id" => $user_id,
        "network" => strtolower($network),
        "amount" => $amount,
        "quantity" => $quantity
    ];

    $headers = [
        "Authorization: Bearer $api_key",
        "Content-Type: application/json"
    ];

    $ch = curl_init("https://api.nelobyte.com/v1/recharge/airtime/purchase-print");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

    $response = curl_exec($ch);
    $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    if ($http_code === 200) {
        $result = json_decode($response, true);
        $cards = $result['data'];
    } else {
        $error = "Error purchasing airtime. Please check your credentials, balance, or parameters.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Print Airtime Cards</title>
  <style>
    body { font-family: 'Segoe UI', sans-serif; background: #f7f7f7; padding: 2rem; }
    h2 { text-align: center; margin-bottom: 2rem; }
    .form-container { max-width: 500px; margin: auto; background: white; padding: 2rem; border-radius: 8px; box-shadow: 0 0 15px rgba(0,0,0,0.1); }
    .card-grid { display: flex; flex-wrap: wrap; gap: 20px; justify-content: center; margin-top: 30px; }
    .airtime-card {
        width: 250px;
        background: linear-gradient(145deg, #ffffff, #e5e5e5);
        border: 2px solid #ccc;
        border-radius: 10px;
        padding: 15px;
        box-shadow: 0 4px 10px rgba(0,0,0,0.15);
        text-align: center;
        font-family: monospace;
    }
    .airtime-card h4 {
        margin: 0;
        font-size: 1.2rem;
        color: #333;
    }
    .airtime-card p {
        margin: 8px 0;
        font-size: 1rem;
        color: #000;
    }
    .airtime-card small {
        color: #555;
    }
    .download-btn {
        display: block;
        margin: 2rem auto;
        padding: 10px 20px;
        background: #198754;
        color: white;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        font-size: 1rem;
        text-decoration: none;
    }
    @media print {
        .no-print { display: none; }
        .card-grid { justify-content: flex-start; }
    }
  </style>
</head>
<body>

<div class="form-container no-print">
  <h2>Buy & Print Airtime Recharge Cards</h2>
  <?php if ($error): ?>
    <p style="color:red;"><?= htmlspecialchars($error) ?></p>
  <?php endif; ?>
  <form method="POST">
    <label>Network</label><br>
    <select name="network" required>
      <option value="">Select</option>
      <option value="mtn">MTN</option>
      <option value="airtel">Airtel</option>
      <option value="glo">Glo</option>
      <option value="9mobile">9mobile</option>
    </select><br><br>

    <label>Amount</label><br>
    <input type="number" name="amount" required><br><br>

    <label>Quantity</label><br>
    <input type="number" name="quantity" value="1" required><br><br>

    <button type="submit">Generate Cards</button>
  </form>
</div>

<?php if (!empty($cards)): ?>
  <a class="download-btn no-print" href="#" onclick="window.print()">Download / Print Cards</a>
  <div class="card-grid">
    <?php foreach ($cards as $card): ?>
      <div class="airtime-card">
        <h4><?= strtoupper($card['network']) ?> - ₦<?= $card['amount'] ?></h4>
        <p><strong><?= $card['pin'] ?></strong></p>
        <small>Serial: <?= $card['serial'] ?></small><br>
        <small>Date: <?= date("d M Y") ?></small>
      </div>
    <?php endforeach; ?>
  </div>
<?php endif; ?>

</body>
</html>