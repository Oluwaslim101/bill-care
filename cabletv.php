<?php
// Include your database connection file if needed
 include('db_connect.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $tvProvider = $_POST['tv_provider'];
    $smartcardNo = $_POST['smartcard_number'];
    $selectedPlan = $_POST['plan'];
    $amount = $_POST['amount']; // Fetched automatically from dropdown

    // Process Payment (Deduct from user balance and call API)
    // Assuming you have a function to get user balance
    $userBalance = 5000; // Example balance, replace with actual balance from DB

    if ($userBalance >= $amount) {
        // Deduct balance and store transaction
        $transactionRef = "TXN" . time();
       // Save to database (example query)
        $sql = "INSERT INTO transactions (user_id, type, amount, transaction_ref, status) VALUES ('$userId', 'cabletv', '$amount', '$transactionRef', 'pending')";
         mysqli_query($conn, $sql);

        echo "<script>alert('Payment Successful! Transaction Ref: $transactionRef');</script>";
    } else {
        echo "<script>alert('Insufficient Balance');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cable TV Payment</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f4f4f4;
            text-align: center;
            padding: 20px;
        }
        .container {
            background: #fff;
            padding: 20px;
            max-width: 400px;
            margin: auto;
            border-radius: 10px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
        }
        select, input, button {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border-radius: 5px;
            border: 1px solid #ccc;
        }
        button {
            background: #28a745;
            color: white;
            cursor: pointer;
        }
        button:hover {
            background: #218838;
        }
    </style>
</head>
<body>

<div class="container">
    <h2>Cable TV Payment</h2>

    <label for="tv-provider">Select Cable TV:</label>
    <select id="tv-provider">
        <option value="">Select Cable TV</option>
        <option value="dstv">DStv</option>
        <option value="gotv">GOtv</option>
        <option value="startimes">StarTimes</option>
    </select>

    <label for="smartcard-number">Smart Card Number:</label>
    <input type="text" id="smartcard-number" placeholder="Enter Smart Card Number">

    <button id="verify-smartcard">Verify</button>

    <p id="subscriber-name"></p>

    <form method="POST">
        <input type="hidden" id="tv-provider-hidden" name="tv_provider">
        <input type="hidden" id="smartcard-hidden" name="smartcard_number">

        <label for="plan-dropdown">Select Plan:</label>
        <select id="plan-dropdown" name="plan"></select>

        <input type="hidden" id="amount-hidden" name="amount">

        <button type="submit">Pay</button>
    </form>
</div>

<script>
document.addEventListener("DOMContentLoaded", function () {
    const smartcardInput = document.getElementById("smartcard-number");
    const tvProviderSelect = document.getElementById("tv-provider");
    const verifyButton = document.getElementById("verify-smartcard");
    const subscriberName = document.getElementById("subscriber-name");
    const planDropdown = document.getElementById("plan-dropdown");
    const hiddenProvider = document.getElementById("tv-provider-hidden");
    const hiddenSmartcard = document.getElementById("smartcard-hidden");
    const hiddenAmount = document.getElementById("amount-hidden");

    verifyButton.addEventListener("click", function () {
        const smartcardNo = smartcardInput.value.trim();
        const cableTV = tvProviderSelect.value;

        if (smartcardNo === "" || cableTV === "") {
            alert("Please enter a smart card number and select a TV provider.");
            return;
        }

        verifySmartCard(cableTV, smartcardNo);
    });

    function verifySmartCard(cableTV, smartcardNo) {
    const userID = "CK100028738"; // Replace with actual UserID
    const apiKey = "U66B634EJ9AE5227OEP008IMIQ6V895814L0E38G90RKV1DFH9ASHARH6876YZH8"; // Replace with actual APIKey
    const url = `https://www.nellobytesystems.com/APIVerifyCableTVV1.0.asp?UserID=${userID}&APIKey=${apiKey}&CableTV=${cableTV}&SmartCardNo=${smartcardNo}`;

    fetch(url)
        .then(response => response.json())
        .then(data => {
            if (data.status === "200") {
                subscriberName.textContent = `Subscriber: ${data.CustomerName}`;
                fetchPlans(cableTV);
                hiddenProvider.value = cableTV;
                hiddenSmartcard.value = smartcardNo;
            } else {
                subscriberName.textContent = "Invalid Smart Card Number";
                planDropdown.innerHTML = "";
            }
        })
        .catch(error => {
            console.error("Error verifying smart card:", error);
            subscriberName.textContent = "Verification failed. Try again.";
            planDropdown.innerHTML = "";
        });
}

    function fetchPlans(cableTV) {
        const plans = {
            dstv: [
                { name: "DStv Padi", amount: 2500 },
                { name: "DStv Yanga", amount: 3950 },
                { name: "DStv Confam", amount: 6800 },
            ],
            gotv: [
                { name: "GOtv Jinja", amount: 2650 },
                { name: "GOtv Jolli", amount: 3950 },
                { name: "GOtv Max", amount: 4990 },
            ],
            startimes: [
                { name: "StarTimes Nova", amount: 1200 },
                { name: "StarTimes Basic", amount: 2200 },
                { name: "StarTimes Classic", amount: 3200 },
            ]
        };

        const availablePlans = plans[cableTV] || [];
        planDropdown.innerHTML = "";

        if (availablePlans.length > 0) {
            availablePlans.forEach(plan => {
                const option = document.createElement("option");
                option.value = plan.amount;
                option.textContent = `${plan.name} - ₦${plan.amount}`;
                planDropdown.appendChild(option);
            });

            planDropdown.addEventListener("change", function () {
                hiddenAmount.value = planDropdown.value;
            });
        } else {
            planDropdown.innerHTML = "<option>No plans available</option>";
        }
    }
});
</script>

</body>
</html>