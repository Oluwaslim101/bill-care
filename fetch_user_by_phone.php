<?php
require 'db.php'; // your PDO connection

$rawPhone = $_GET['phone'] ?? '';
$rawPhone = preg_replace('/[^0-9]/', '', $rawPhone); // remove non-digits

// Normalize Nigerian number to +234 format
if (strlen($rawPhone) === 11 && substr($rawPhone, 0, 1) === '0') {
    $phone = '+234' . substr($rawPhone, 1); // e.g. 08012345678 => +2348012345678
} elseif (strlen($rawPhone) === 13 && substr($rawPhone, 0, 3) === '234') {
    $phone = '+' . $rawPhone; // e.g. 2348012345678 => +2348012345678
} elseif (strlen($rawPhone) === 14 && substr($rawPhone, 0, 4) === '2340') {
    $phone = '+234' . substr($rawPhone, 4); // just in case
} elseif (strlen($rawPhone) === 13 && substr($rawPhone, 0, 4) === '080') {
    $phone = '+234' . substr($rawPhone, 1); // fallback
} else {
    $phone = '+234' . $rawPhone; // assume raw digits are missing prefix
}

// Lookup
$stmt = $sql->prepare("SELECT id, full_name FROM users WHERE phone_number = ?");
$stmt->execute([$phone]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if ($user) {
    echo json_encode([
        'success' => true,
        'name' => $user['full_name'],
        'user_id' => $user['id']
    ]);
} else {
    echo json_encode(['success' => false]);
}
