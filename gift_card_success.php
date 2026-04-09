<?php
session_start();

if (!isset($_SESSION['gift_card_transaction'])) {
    die("Invalid access. No transaction data found.");
}

$transaction = $_SESSION['gift_card_transaction'];
unset($_SESSION['gift_card_transaction']); // Remove session after use

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Transaction Successful</title>
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f8f9fa;
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            margin: 0;
            padding: 20px;
        }
        
        .receipt-container {
            background: white;
            padding: 25px;
            max-width: 500px;
            width: 100%;
            box-shadow: 0px 4px 12px rgba(0, 0, 0, 0.1);
            border-radius: 12px;
            text-align: center;
        }

        h2 {
            color: #333;
            margin-bottom: 15px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }

        th, td {
            padding: 10px;
            border-bottom: 1px solid #eee;
            text-align: left;
        }

        th {
            background-color: #f4f4f4;
            font-weight: 600;
            color: #555;
        }

        .status {
            font-weight: bold;
            text-transform: capitalize;
        }

        .status.Pending {
            color: #f39c12;
        }

        .status.Approved {
            color: #27ae60;
        }

        .status.Rejected {
            color: #e74c3c;
        }

        .image-gallery {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
            justify-content: center;
            margin-top: 15px;
        }

        .image-gallery img {
            width: 80px;
            height: 80px;
            border-radius: 8px;
            border: 1px solid #ddd;
            object-fit: cover;
        }

        .buttons {
            margin-top: 20px;
            display: flex;
            gap: 10px;
            justify-content: center;
        }

        .buttons button {
            padding: 10px 15px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-size: 14px;
            font-weight: bold;
            transition: 0.3s;
        }

        .print {
            background-color: #28a745;
            color: white;
        }

        .dashboard {
            background-color: #007bff;
            color: white;
        }

        .buttons button:hover {
            opacity: 0.8;
        }

        @media (max-width: 500px) {
            .receipt-container {
                padding: 20px;
            }

            .image-gallery img {
                width: 60px;
                height: 60px;
            }
        }
    </style>
</head>
<body>

<div class="receipt-container">
    <h2>Gift Card Transaction Receipt</h2>

    <table>
        <tr>
            <th>Transaction Reference:</th>
            <td><?= htmlspecialchars($transaction['transaction_ref']) ?></td>
        </tr>
        <tr>
            <th>Country:</th>
            <td><?= htmlspecialchars($transaction['country_code']) ?></td>
        </tr>
        <tr>
            <th>Gift Card:</th>
            <td><?= htmlspecialchars($transaction['gift_card_name']) ?></td>
        </tr>
        <tr>
            <th>Card Type:</th>
            <td><?= ucfirst(htmlspecialchars($transaction['card_type'])) ?></td>
        </tr>
        <tr>
            <th>Amount ($):</th>
            <td>$<?= number_format($transaction['amount'], 2) ?></td>
        </tr>
        <tr>
            <th>Exchange Rate (₦ per $):</th>
            <td>₦<?= number_format($transaction['exchange_rate'], 2) ?></td>
        </tr>
        <tr>
            <th>Total Value (₦):</th>
            <td>₦<?= number_format($transaction['total_value'], 2) ?></td>
        </tr>
        <tr>
            <th>Status:</th>
            <td class="status <?= htmlspecialchars($transaction['status']) ?>">
                <?= htmlspecialchars($transaction['status']) ?>
            </td>
        </tr>
    </table>

    <?php if (!empty($transaction['uploaded_images'])): ?>
        <h3>Uploaded Gift Card Images</h3>
        <div class="image-gallery">
            <?php foreach ($transaction['uploaded_images'] as $image): ?>
                <img src="<?= htmlspecialchars($image) ?>" alt="Gift Card">
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <div class="buttons">
        <button class="print" onclick="window.print()">Print Receipt</button>
        <a href="index.php"><button class="dashboard">Back to Dashboard</button></a>
    </div>
</div>

</body>
</html>