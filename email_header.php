<?php
ob_start();
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>DtheHub</title>
    <style>
        body { font-family: Arial, sans-serif; background: #f5f5f5; margin: 0; padding: 0; }
        .email-container { background: #ffffff; max-width: 600px; margin: 30px auto; padding: 20px; border-radius: 10px; }
        .email-header { background: #1a73e8; color: white; padding: 15px; text-align: center; border-radius: 10px 10px 0 0; }
    </style>
</head>
<body>
<div class="email-container">
    <div class="email-header">
        <h2>DtheHub</h2>
    </div>
<?php
$emailHeader = ob_get_clean();
