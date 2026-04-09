<?php
// send_contract_email.php

function sendContractPurchaseEmail($to, $full_name, $amount, $transaction_ref) {
    $subject = "Contract Purchase Confirmation";
    $message = "Hello $full_name,\n\n".
               "Thank you for your purchase.\n".
               "Amount: \$$amount\n".
               "Transaction Reference: $transaction_ref\n\n".
               "Best regards,\nYour Company Name";

    $headers = "From: SwiftContract@swiftaffiliates.cloud";

    mail($to, $subject, $message, $headers);
}
?>