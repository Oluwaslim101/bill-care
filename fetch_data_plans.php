<?php
header("Content-Type: application/json");

// Data plans mapped to their respective codes
$data_plans = [
    "MTN" => [
        ["data_plan" => "500.0", "description" => "500MB - 30 days (SME) @ N330.00"],
        ["data_plan" => "1000.0", "description" => "1GB - 30 days (SME) @ N660.00"],
        ["data_plan" => "2000.0", "description" => "2GB - 30 days (SME) @ N1,320.00"],
        ["data_plan" => "350.01", "description" => "1GB Daily Plan + 3mins - 1 day @ N339.50"],
        ["data_plan" => "20000.01", "description" => "75GB Monthly Plan - 30 days @ N19,400.00"]
    ],
    "Glo" => [
        ["data_plan" => "500.02", "description" => "750MB+500 talktime - 7 days (XtraValue) @ N485.00"],
        ["data_plan" => "2000.02", "description" => "4.5GB+2000 talktime - 30 days (XtraValue) @ N1,940.00"]
    ],
    "Airtel" => [
        ["data_plan" => "1500.01", "description" => "5GB Weekly Plan - 7 days @ N1,455.00"],
        ["data_plan" => "4500.01", "description" => "8GB+25mins Monthly Plan - 30 days @ N4,365.00"]
    ],
    "9Mobile" => [
        ["data_plan" => "6500.01", "description" => "15GB+25mins Monthly Plan - 30 days @ N6,305.00"],
        ["data_plan" => "11000.01", "description" => "32GB Monthly Plan - 30 days @ N10,670.00"]
    ]
];

// Get selected network
$network = $_GET['network'] ?? '';

// Return matching plans or empty array
echo json_encode($data_plans[$network] ?? []);
exit();