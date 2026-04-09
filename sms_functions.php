<?php
// sms_functions.php

define('D7_API_TOKEN', 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJhdWQiOiJhdXRoLWJhY2tlbmQ6YXBwIiwic3ViIjoiODIwODllY2QtODkzYy00ZWQxLTg2YWQtYjUwYWNiMGQ5ZTc3In0.7KH81RN3Rye5koMa8IsPKGDWeVDD-oia5BjOocyPM20');  // Replace with your token
define('D7_SENDER_ID', 'SwiftContra');           // Replace with your approved sender ID

function sendSMS($to, $message) {
    $url = 'https://api.d7networks.com/messages/v1/send';

    $data = [
        'to' => $to,
        'content' => $message,
        'from' => D7_SENDER_ID
    ];

    $headers = [
        'Authorization: Bearer ' . D7_API_TOKEN,
        'Content-Type: application/json'
    ];

    $curl = curl_init($url);
    curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($data));
    $response = curl_exec($curl);
    curl_close($curl);

    return $response;
}

/* ================================
   ACCOUNT & PROFILE NOTIFICATIONS
   ================================ */

function smsRegistration($to, $name) {
    return sendSMS($to, "Hi $name, welcome to YourApp! Thanks for signing up.");
}

function smsProfileUpdated($to) {
    return sendSMS($to, "Your profile has been updated successfully.");
}

function smsAccountDeactivated($to) {
    return sendSMS($to, "Your account has been deactivated. Contact support to reactivate.");
}

function smsAccountReactivated($to) {
    return sendSMS($to, "Your account has been reactivated. Welcome back!");
}

/* ===============
   SECURITY ALERTS
   =============== */

function smsLoginAlert($to, $ip) {
    return sendSMS($to, "Login alert: Access from IP $ip. Was this you?");
}

function sms2FACode($to, $code) {
    return sendSMS($to, "Your 2FA code is: $code. Do not share this code.");
}

function smsPasswordChanged($to) {
    return sendSMS($to, "Your password was changed. If not you, reset it immediately.");
}

function smsForgotPassword($to, $code) {
    return sendSMS($to, "Reset code: $code. Use this to reset your password.");
}

function smsAccountLocked($to) {
    return sendSMS($to, "Your account is locked after failed logins. Contact support.");
}

/* =================
   TRANSACTION SMS
   ================= */
function smsDepositInitiated($to, $amount, $ref) {
    $formattedAmount = number_format($amount, 2);
    $message = "Your deposit of $$formattedAmount has been initiated. Ref: $ref. Awaiting approval.";
    return sendSMS($to, $message);
}

function smsDepositApproved($to, $amount, $ref) {
    return sendSMS($to, "Deposit of ₦$amount approved. Ref: $ref.");
}

function smsWithdrawalSuccess($to, $amount, $bank) {
    return sendSMS($to, "₦$amount has been sent to your $bank account.");
}

function smsWithdrawalFailed($to, $reason) {
    return sendSMS($to, "Withdrawal failed. Reason: $reason.");
}

/* =============================
   CONTRACTS & INVESTMENTS SMS
   ============================= */

function smsContractPurchased($to, $contract, $amount) {
    return sendSMS($to, "You bought '$contract' for ₦$amount.");
}

function smsContractComplete($to, $contract, $earnings) {
    return sendSMS($to, "Contract '$contract' completed. You earned ₦$earnings.");
}

function smsContractExpiring($to, $contract, $date) {
    return sendSMS($to, "'$contract' contract will expire on $date.");
}

/* ===========================
   REFERRAL & REWARDS SMS
   =========================== */

function smsReferralSignup($to, $refName) {
    return sendSMS($to, "$refName signed up using your referral code.");
}

function smsReferralBonus($to, $amount) {
    return sendSMS($to, "You received ₦$amount referral bonus!");
}

function smsPointsRedeemed($to, $points, $amount) {
    return sendSMS($to, "You redeemed $points points for ₦$amount.");
}

/* =======================
   TASK & ENGAGEMENT SMS
   ======================= */

function smsTaskAvailable($to, $taskTitle) {
    return sendSMS($to, "New task available: '$taskTitle'. Earn points now!");
}

function smsTaskCompleted($to, $points) {
    return sendSMS($to, "Task completed! You've earned $points points.");
}

function smsDailyReminder($to) {
    return sendSMS($to, "Don't forget to complete today's task and earn points!");
}

/* ==========================
   SYSTEM & PROMO ALERTS SMS
   ========================== */

function smsNewsletterNotice($to, $subject) {
    return sendSMS($to, "Newsletter: $subject. Check your email for details.");
}

function smsPromoOffer($to, $title) {
    return sendSMS($to, "Promo: $title is live! Open app for full details.");
}

function smsMaintenanceNotice($to, $time) {
    return sendSMS($to, "Notice: Maintenance scheduled at $time. Temporary downtime expected.");
}