<?php
// Simulating a newly registered user with email
$user_email = "theceo@digishubb.com";  // Replace with dynamically fetched email from DB

// Send welcome email
$to = $user_email;
$subject = "So Easy -Claim Your 50% Profit Offer!";
$headers = "MIME-Version: 1.0" . "\r\n";
$headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
$headers .= "From: Palmpay <palmpay_service@s.com>" . "\r\n";

// Welcome email content (HTML)
$message = "
<html>
<head>
    <title>Welcome to Swift Contract</title>
</head>
<body style='font-family: Arial, sans-serif; background-color: #f4f8fb; margin: 0; padding: 0;'>
    <table width='100%' cellpadding='0' cellspacing='0'>
        <tr>
            <td align='center' style='padding: 30px;'>
                <table width='600' cellpadding='0' cellspacing='0' style='background-color: #ffffff; border-radius: 8px; overflow: hidden;'>
                    <tr>
                        <td align='center' style='background-color: #1f2937; padding: 20px;'>
                            <img src='https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQ3OE1WxIBw1X_YN0bdyA8PRDD7EXu026kOSyi0z6c6H4EwoW1FS8gdjvs&s=10' alt='Swift Contract' style='height: 60px;'>
                            <h1 style='color: #ffffff; margin: 10px 0 0;'>Welcome to Swift Contract</h1>
                        </td>
                    </tr>
                    <tr>
                        <td style='padding: 30px;'>
                            <h2 style='color: #10b981;'>Welcome to Swift Contract!</h2>
                            <p style='font-size: 15px; color: #333333;'>
                                Thank you for signing up with Swift Contract! We're thrilled to have you on board. 
                                Our platform is designed to help you grow your funds in no time. 
                            </p>
                            <h3 style='color: #FF6347;'>Exclusive Offer: 50% Profit in 30 Hours!</h3>
                            <p style='font-size: 15px; color: #333333;'>
                                As a welcome gift, we’re offering you a <strong>50% profit</strong> on your first contract purchase within the first 30 hours of registration! 
                                It’s an incredible opportunity to start your journey with us and see your funds grow quickly.
                            </p>
                            <p style='font-size: 15px; color: #333333;'>
                                But hurry, this offer is only valid for a limited time! Don’t miss out!
                            </p>
                            <h3 style='color: #10b981;'>How It Works in 4 Easy Steps:</h3>
                            <ul style='font-size: 15px; color: #333333;'>
                                                            <li>Step 1: Download our Mobile App, if you haven't</li>
                                <li>Step 2: Log into your account using the link below.</li>
                                <li>Step 3: Purchase a contract to activate the 50% profit offer.</li>
                                <li>Step 4: Watch your investment grow with swift returns.</li>
                            </ul>
                            <div style='text-align: center; margin: 30px 0;'>
                                <a href='https://swiftaffiliates.cloud/index.php' style='background-color: #10b981; color: #ffffff; padding: 14px 25px; text-decoration: none; border-radius: 5px; font-weight: bold;'>Download Now and Claim Your Profit</a>
                            </div>
                            <p style='font-size: 14px; color: #999999;'>If you have any questions or need assistance, feel free to reply to this email. Our support team is here to help you every step of the way!</p>
                        </td>
                    </tr>
                    <tr>
                        <td align='center' style='background-color: #f0f0f0; padding: 20px; font-size: 12px; color: #888;'>
                            &copy; <?=date('Y')?> Swift Contract. All rights reserved.
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>";

// Send the email
if (mail($to, $subject, $message, $headers)) {
    echo "Welcome email sent to: $user_email";
} else {
    echo "Error: Email not sent. Please try again later.";
}
?>