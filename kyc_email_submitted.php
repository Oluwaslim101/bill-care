<?php
// Ensure email content is served as HTML
header("Content-Type: text/html; charset=UTF-8");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>KYC Submission Received</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f6f8fa;
            color: #333;
            padding: 20px;
        }
        .container {
            max-width: 600px;
            background: #ffffff;
            margin: auto;
            padding: 30px;
            border: 1px solid #e1e4e8;
            border-radius: 8px;
        }
        .header {
            background: #007bff;
            color: #ffffff;
            padding: 10px 20px;
            text-align: center;
            border-radius: 6px 6px 0 0;
        }
        .content {
            margin-top: 20px;
        }
        .footer {
            margin-top: 30px;
            font-size: 0.9em;
            color: #777;
            text-align: center;
        }
        a.button {
            display: inline-block;
            margin-top: 20px;
            background: #28a745;
            color: white;
            padding: 10px 20px;
            text-decoration: none;
            border-radius: 6px;
        }
    </style>
</head>
<body>
<div class="container">
    <div class="header">
        <h2>Thank You for Your KYC Submission</h2>
    </div>
    <div class="content">
        <p>Dear User,</p>
        <p>We have received your KYC (Know Your Customer) documents and they are currently under review.</p>
        <p>You will be notified via email once your verification is complete or if additional information is required.</p>

        <p>If you have any questions or concerns, feel free to contact our support team.</p>

        <a href="https://swiftaffiliates.cloud" class="button">Return to Dashboard</a>
    </div>
    <div class="footer">
        &copy; <?= date('Y') ?> SwiftAffiliates. All rights reserved.
    </div>
</div>
</body>
</html>
