<?php
function render_email_template($recipient_name, $reply_message) {
    return '
    <html>
    <head>
        <style>
            body { font-family: Arial, sans-serif; background-color: #f4f4f4; padding: 20px; }
            .email-container { background-color: #ffffff; padding: 20px; border-radius: 8px; }
            .email-header { font-size: 20px; font-weight: bold; margin-bottom: 15px; }
            .email-content { font-size: 16px; line-height: 1.5; }
            .email-footer { margin-top: 30px; font-size: 12px; color: #888; }
        </style>
    </head>
    <body>
        <div class="email-container">
            <div class="email-header">Reply from Swift Contract Admin</div>
            <div class="email-content">
                <p>Dear ' . htmlspecialchars($recipient_name) . ',</p>
                <p>' . nl2br(htmlspecialchars($reply_message)) . '</p>
                <p>Best regards,<br>Swift Contract Team</p>
            </div>
            <div class="email-footer">
                You are receiving this email because you contacted us through the Swift Contract platform.
            </div>
        </div>
    </body>
    </html>';
}
?>
