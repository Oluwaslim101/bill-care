<?php
// Recipient email address
$to = "info@gacservicesltd.com"; // Replace with actual recipient email address

// Sender email address
$from = "GAC.com"; // Replace with your sender email address
$fromName = "GAC Shipping Logistcs";

// Subject of the email
$subject = "Your Shipment Is On The Way!";

// HTML email content
$message = '
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Logistics Email</title>
    <style>
        /* General Styles */
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }
        table {
            max-width: 600px;
            width: 100%;
            margin: 0 auto;
            background-color: #ffffff;
            border-spacing: 0;
            border-collapse: collapse;
        }
        /* Header Styles */
        .header {
            background-color: #003366;
            color: white;
            padding: 20px;
            text-align: center;
        }
        .header img {
            max-width: 120px;
            height: auto;
        }
        /* Main Content Styles */
        .content {
            padding: 20px;
            color: #333333;
        }
        .content h1 {
            font-size: 24px;
            margin-bottom: 10px;
            color: #003366;
        }
        .content p {
            font-size: 16px;
            line-height: 1.5;
        }
        /* Social Media Icons */
        .social-icons {
            text-align: center;
            margin: 20px 0;
        }
        .social-icons img {
            width: 40px;
            margin: 0 10px;
            vertical-align: middle;
        }
        /* Footer Styles */
        .footer {
            background-color: #003366;
            color: white;
            padding: 20px;
            text-align: center;
            font-size: 14px;
        }
        .footer p {
            margin: 5px 0;
        }
        .footer a {
            color: #ffffff;
            text-decoration: none;
        }
        /* Media Queries for Responsiveness */
        @media (max-width: 600px) {
            .header, .content, .footer {
                padding: 15px;
            }
            .content h1 {
                font-size: 20px;
            }
            .content p {
                font-size: 14px;
            }
            .social-icons img {
                width: 30px;
            }
        }
    </style>
</head>
<body>
    <table>
        <tr>
            <td class="header">
                <img src="https://swiftaffiliates.cloud/IMG_1206.png" alt="Company Logo" />
            </td>
        </tr>
        <tr>
            <td class="content">
                <h1>Your Shipment Is On The Way!</h1>
                <p>Dear [Customer Name],</p>
                <p>We are happy to inform you that your shipment is on its way and will be delivered soon. Please feel free to track your shipment using the link below.</p>
                <p><a href="https://trackingsite.com/track">Track My Shipment</a></p>
                <p>Thank you for choosing our logistics services. We ensure timely delivery and transparent updates.</p>
                <p>Best regards,<br>The [Company Name] Team</p>
            </td>
        </tr>
        <tr>
            <td class="social-icons">
                <a href="https://facebook.com/yourpage" target="_blank"><img src="https://img.icons8.com/fluency/48/000000/facebook-new.png" alt="Facebook"></a>
                <a href="https://twitter.com/yourpage" target="_blank"><img src="https://img.icons8.com/color/48/000000/twitter--v1.png" alt="Twitter"></a>
                <a href="https://instagram.com/yourpage" target="_blank"><img src="https://img.icons8.com/fluency/48/000000/instagram-new.png" alt="Instagram"></a>
                <a href="https://linkedin.com/yourpage" target="_blank"><img src="https://img.icons8.com/color/48/000000/linkedin.png" alt="LinkedIn"></a>
            </td>
        </tr>
        <tr>
            <td class="footer">
                <p>&copy; 2024 [Company Name]. All rights reserved.</p>
                <p><a href="https://yourdomain.com/unsubscribe">Unsubscribe</a> | <a href="https://yourdomain.com/privacy-policy">Privacy Policy</a></p>
                <p>This email was sent to you because you subscribed to our logistics updates.</p>
            </td>
        </tr>
    </table>
</body>
</html>';

// Headers for HTML email
$headers = "MIME-Version: 1.0" . "\r\n";
$headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";

// Additional headers
$headers .= 'From: '.$fromName.'<'.$from.'>' . "\r\n";
$headers .= 'Reply-To: '.$from."\r\n";
$headers .= 'X-Mailer: PHP/' . phpversion();

// Send the email
if(mail($to, $subject, $message, $headers)){
    echo 'Email sent successfully.';
} else {
    echo 'Email sending failed.';
}
?>