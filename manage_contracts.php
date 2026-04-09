<!DOCTYPE html><html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Contracts Chart</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
</head>
<body>

<?php
// Error reporting 
error_reporting('E_ALL', 1);

include 'db.php';
session_start();
$user_id = $_SESSION['user_id'] ?? 0;

$stmt = $pdo->prepare("SELECT purchase_date, purchased_amount FROM user_contracts WHERE user_id = ? ORDER BY purchase_date ASC");
$stmt->execute([$user_id]);
$contracts = $stmt->fetchAll(PDO::FETCH_ASSOC);

$dates = [];
$amounts = [];
foreach ($contracts as $row) {
    $dates[] = date('M d', strtotime($row['purchase_date']));
    $amounts[] = (float)$row['purchased_amount'];
}
?><div class="container mt-5">
    <h2 class="text-center mb-4">Your Contract Investment Over Time</h2>
    <div id="userContractChart"></div>
</div><script>
    var options = {
        series: [{
            name: 'Invested Amount',
            data: <?= json_encode($amounts) ?>
        }],
        chart: {
            type: 'area',
            height: 300,
            toolbar: { show: false },
        },
        dataLabels: { enabled: false },
        stroke: {
            curve: 'smooth'
        },
        xaxis: {
            categories: <?= json_encode($dates) ?>,
            title: {
                text: 'Date'
            }
        },
        yaxis: {
            labels: {
                formatter: val => "₦" + val.toLocaleString()
            },
            title: {
                text: 'Amount (NGN)'
            }
        },
        tooltip: {
            x: {
                format: 'dd MMM'
            },
            y: {
                formatter: function (val) {
                    return "₦" + val.toLocaleString();
                }
            }
        },
        colors: ['#1DCC70']
    };

    var chart = new ApexCharts(document.querySelector("#userContractChart"), options);
    chart.render();
</script></body>
</html>