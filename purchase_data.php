<?php
// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Include database connection
include('db.php');

// Start session
session_start();

// Check if user is logged in
if (!isset($_SESSION['user_id']) || empty($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

// Get user ID from session
$user_id = $_SESSION['user_id'];

// Fetch user data
$query = "SELECT * FROM users WHERE id = ?";
$stmt = $sql->prepare($query);
$stmt->execute([$user_id]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user) {
    header('Location: login.php');
    exit();
}

// Fetch unread notifications
$notifications_query = "SELECT * FROM notifications WHERE user_id = ? AND status = 'unread'";
$notifications_stmt = $sql->prepare($notifications_query);
$notifications_stmt->execute([$user_id]);
$unread_count = $notifications_stmt->rowCount();

// Assign user data
$wallet_id = $user['wallet_id'];
$avatar_url = !empty($user['avatar_url']) ?
$user['avatar_url'] : 'default-avatar.png';
$balance = number_format($user['balance'], 2);


if (!$user) {
    die("User not found.");
}

$balance = $user['balance'];

// Nellobytes API Constants
$api_user_id = "CK100028738";
$api_key = "U66B634EJ9AE5227OEP008IMIQ6V895814L0E38G90RKV1DFH9ASHARH6876YZH8";

// Network details
$networks = [
    "MTN" => ["code" => "01", "logo" => "https://upload.wikimedia.org/wikipedia/commons/9/93/New-mtn-logo.jpg"],
    "Glo" => ["code" => "02", "logo" => "https://static-00.iconduck.com/assets.00/globacom-limited-icon-512x512-nsbqgsyf.png"],
    "Airtel" => ["code" => "04", "logo" => "https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcSDcHFCFLodysDPJeyUq5oo_JkRWGIlibMou93EZtkISmNuo4aE_98Nzzv9&s=10"],
    "9Mobile" => ["code" => "03", "logo" => "https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcRQDuS8mApI7J3k2ZpWXBjueg9szp6xhHlTf9-yGKhook5xcOte8gu2kQ0&s=10"]
];

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $network = $_POST['network'] ?? '';
    $data_plan_code = $_POST['data_plan'] ?? '';
    $phone_number = $_POST['phone_number'] ?? '';

    // Validate inputs
    if (!isset($networks[$network]) || empty($data_plan_code) || empty($phone_number)) {
        die("Invalid input! Please select all fields.");
    }

    $network_code = $networks[$network]['code'];

    // Fetch data plans
    $data_plans_url = "https://swiftaffiliates.cloud/fetch_data_plans.php?network=" . urlencode($network);
    $response = file_get_contents($data_plans_url);
    $data_plans = json_decode($response, true);

    if (!is_array($data_plans)) {
        die("Failed to fetch data plans.");
    }

    // Find selected plan
    $selected_plan = null;
    foreach ($data_plans as $plan) {
        if ($plan['data_plan'] == $data_plan_code) {
            $selected_plan = $plan;
            break;
        }
    }

    if (!$selected_plan) {
        die("Invalid data plan selection.");
    }

    // Extract price from description (assumes price follows "@ N")
    preg_match('/@ N([\d,]+\.?\d*)/', $selected_plan['description'], $matches);
    $price = isset($matches[1]) ? floatval(str_replace(',', '', $matches[1])) : 0;

    if ($balance < $price) {
        die("Insufficient balance.");
    }

    // Deduct balance
    $new_balance = $balance - $price;
    $update_balance = $sql->prepare("UPDATE users SET balance = ? WHERE id = ?");
    $update_balance->execute([$new_balance, $user_id]);

    // Generate transaction reference
    $transaction_ref = "DATA" . time() . rand(100, 999);

  // Insert transaction with 'approved' status
$insert_transaction = $sql->prepare("INSERT INTO transactions 
    (user_id, amount, transaction_ref, type, status, network_code, data_plan, mobile_number, description) 
    VALUES (?, ?, ?, 'data', 'successful', ?, ?, ?, ?)");
$insert_success = $insert_transaction->execute([
    $user_id,
    $price,
    $transaction_ref,
    $network_code,
    $data_plan_code,
    $phone_number,
    $selected_plan['description'] // Fix: Properly defined description
]);


// Check if the transaction was successfully inserted
if (!$insert_success) {
    die("Error: Transaction was not inserted into the database.");
}


// Fetch the transaction to get network, data plan, and phone number
$transaction_query = $sql->prepare("SELECT * FROM transactions WHERE transaction_ref = ?");
$transaction_query->execute([$transaction_ref]);
$transaction = $transaction_query->fetch(PDO::FETCH_ASSOC);

if (!$transaction) {
    die("Error: Transaction not found in the database.");
}

// Extract the values from the fetched transaction
$network_code = $transaction['network_code'];
$data_plan_code = $transaction['data_plan'];
$phone_number = $transaction['mobile_number'];


    // Construct API request
    $api_url = "https://www.nellobytesystems.com/APIDatabundleV1.asp?"
        . "UserID=$api_user_id"
        . "&APIKey=$api_key"
        . "&MobileNetwork=$network_code"
        . "&DataPlan=" . urlencode($data_plan_code)
        . "&MobileNumber=" . urlencode($phone_number);

    // Execute API request
    $api_response = file_get_contents($api_url);
    $api_result = json_decode($api_response, true);

    // Log API response
    file_put_contents('nellobytes_log.txt', date('Y-m-d H:i:s') . " - Response: " . print_r($api_result, true) . "\n", FILE_APPEND);

    // Redirect to receipt page
    header("Location: data_receipt.php?ref=" . urlencode($transaction_ref));
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Buy Data Bundle</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <style>
        /* General Styling */
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f8f9fc;
            margin: 0;
            padding: 0;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        /* Container */
        .container {
            max-width: 400px;
            width: 100%;
            padding-top: 80px; /* Prevent header overlap */
            padding-bottom: 80px; /* Prevent navbar overlap */
        }

        /* Fixed Header */
        .header {
            position: fixed;
            top: 0;
            width: 100%;
            max-width: 400px;
            background: #f8f9fc;
            
          
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 10px 15px;
            border-bottom: 0px solid #ddd;
            box-shadow: 0px 2px 10px rgba(0, 0, 0, 0.1);
            z-index: 1000;
        }

        .header .user-info {
            display: flex;
            align-items: center;
            gap: 70px;
        }

        .header img {
            width: 35px;
            height: 35px;
            border-radius: 50%;
        }

        .header .notification {
            position: relative;
        }

        .notification i {
            font-size: 22px;
            color: black;
        }

        .notification .badge {
            position: absolute;
            top: -5px;
            right: -5px;
            background: red;
            color: white;
            font-size: 10px;
            font-weight: bold;
            padding: 3px 6px;
            border-radius: 50%;
        }

        h2 {
            color: #333;
        }

        .network-option {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 10px;
            border: 2px solid #ddd;
            border-radius: 8px;
            cursor: pointer;
            margin-bottom: 15px;
            transition: 0.3s;
        }

        .network-option img {
            width: 30px;
            height: 30px;
        }

        .network-option input {
            display: none;
        }

        .network-option.active {
            border-color: #007bff;
            background-color: #e9f5ff;
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

<!-- Header -->
<header class="header">
    <div class="user-info">
        <img src="<?= $avatar_url ?>" alt="User Avatar">
        <h3>Buy Data Plan</h3>
    </div>
    <div class="notification">
        <i class="fas fa-bell"></i>
        <?php if ($unread_count > 0): ?>
            <span class="badge"><?= $unread_count ?></span>
        <?php endif; ?>
    </div>
</header>

<!-- Main Content -->
<div class="container">
    <form method="POST">
        <div class="mb-3">
            <label class="form-label"><strong>Select Network:</strong></label>
            <div id="network-options">
                <?php foreach ($networks as $name => $details): ?>
                    <label class="network-option">
                        <input type="radio" name="network" value="<?= $name ?>" required>
                        <img src="<?= $details['logo'] ?>" alt="<?= $name ?>">
                        <span><?= $name ?></span>
                    </label>
                <?php endforeach; ?>
            </div>
        </div>

        <div class="mb-3">
            <label for="data_plan" class="form-label"><strong>Select Data Plan:</strong></label>
            <select name="data_plan" id="data_plan" class="form-select" required>
                <option value="">-- Select a Network First --</option>
            </select>
        </div>

        <div class="mb-3">
            <label for="phone_number" class="form-label"><strong>Enter Phone Number:</strong></label>
            <input type="tel" name="phone_number" id="phone_number" class="form-control" required placeholder="080XXXXXXXX">
        </div>

        <button type="submit" class="btn btn-primary w-100"><strong>Purchase Data</strong></button>
    </form>
</div>

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
    <a href="transactions.php">
        <i class="fas fa-receipt"></i>
        <span>Transactions</span>
    </a>
    <a href="profile.php">
        <i class="fas fa-user"></i>
        <span>Profile</span>
    </a>
</nav>

<script>
    $("input[name='network']").on("change", function() {
        $(".network-option").removeClass("active");
        $(this).closest(".network-option").addClass("active");
        let network = $(this).val();

        $("#data_plan").html("<option value=''>-- Loading Plans --</option>");

        fetch("fetch_data_plans.php?network=" + encodeURIComponent(network))
            .then(response => response.json())
            .then(data => {
                $("#data_plan").html("<option value=''>-- Select Plan --</option>");
                data.forEach(plan => {
                    $("#data_plan").append(`<option value="${plan.data_plan}">${plan.description}</option>`);
                });
            });
    });
</script>

</body>
</html>