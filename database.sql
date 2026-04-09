-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Dec 10, 2025 at 11:47 AM
-- Server version: 11.8.3-MariaDB-log
-- PHP Version: 7.2.34

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `u822915062_dthehub_utilit`
--

-- --------------------------------------------------------

--
-- Table structure for table `9mobile_data`
--

CREATE TABLE `9mobile_data` (
  `id` int(11) NOT NULL,
  `code` decimal(10,2) NOT NULL,
  `description` varchar(255) NOT NULL,
  `duration` varchar(50) DEFAULT NULL,
  `type` varchar(50) DEFAULT NULL,
  `price` decimal(10,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `9mobile_data`
--

INSERT INTO `9mobile_data` (`id`, `code`, `description`, `duration`, `type`, `price`, `created_at`) VALUES
(1, 100.01, '100MB', '1 day', 'Awoof Data', 93.00, '2025-06-03 14:33:01'),
(2, 150.01, '180MB', '1 day', 'Awoof Data', 139.50, '2025-06-03 14:33:01'),
(3, 200.01, '250MB', '1 day', 'Awoof Data', 186.00, '2025-06-03 14:33:01'),
(4, 350.01, '450MB', '1 day', 'Awoof Data', 325.50, '2025-06-03 14:33:01'),
(5, 500.01, '650MB', '3 days', 'Awoof Data', 465.00, '2025-06-03 14:33:01'),
(6, 1500.01, '1.75GB', '7 days', 'Direct Data', 1395.00, '2025-06-03 14:33:01'),
(7, 600.01, '650MB', '14 days', 'Direct Data', 558.00, '2025-06-03 14:33:01'),
(8, 1000.01, '1.1GB', '30 days', 'Direct Data', 930.00, '2025-06-03 14:33:01'),
(9, 1200.01, '1.4GB', '30 days', 'Direct Data', 1116.00, '2025-06-03 14:33:01'),
(10, 2000.01, '2.44GB', '30 days', 'Direct Data', 1860.00, '2025-06-03 14:33:01'),
(11, 2500.01, '3.17GB', '30 days', 'Direct Data', 2325.00, '2025-06-03 14:33:01'),
(12, 3000.01, '3.91GB', '30 days', 'Direct Data', 2790.00, '2025-06-03 14:33:01'),
(13, 4000.01, '5.10GB', '30 days', 'Direct Data', 3720.00, '2025-06-03 14:33:01'),
(14, 5000.01, '6.5GB', '30 days', 'Direct Data', 4650.00, '2025-06-03 14:33:01'),
(15, 12000.01, '16GB', '30 days', 'Direct Data', 11160.00, '2025-06-03 14:33:01'),
(16, 18500.01, '24.3GB', '30 days', 'Direct Data', 17205.00, '2025-06-03 14:33:01'),
(17, 20000.01, '26.5GB', '30 days', 'Direct Data', 18600.00, '2025-06-03 14:33:01'),
(18, 30000.01, '39GB', '60 days', 'Direct Data', 27900.00, '2025-06-03 14:33:01'),
(19, 60000.01, '78GB', '90 days', 'Direct Data', 55800.00, '2025-06-03 14:33:01'),
(20, 150000.01, '190GB', '180 days', 'Direct Data', 139500.00, '2025-06-03 14:33:01');

-- --------------------------------------------------------

--
-- Table structure for table `activity_log`
--

CREATE TABLE `activity_log` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `activity` varchar(255) NOT NULL,
  `points` int(11) DEFAULT 0,
  `created_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `activity_log`
--

INSERT INTO `activity_log` (`id`, `user_id`, `activity`, `points`, `created_at`) VALUES
(37, 81, 'Claimed Daily Points', 5, '2025-06-15 13:47:59'),
(38, 75, 'Claimed Daily Points', 5, '2025-06-20 15:17:45'),
(39, 75, 'Claimed Daily Points', 5, '2025-06-21 09:04:26'),
(40, 75, 'Claimed Daily Points', 5, '2025-06-22 22:43:35'),
(41, 75, 'Claimed Daily Points', 5, '2025-06-24 22:23:54'),
(42, 75, 'Redeemed 2000 points for ₦20', 2000, '2025-06-24 22:25:01'),
(43, 75, 'Claimed Daily Points', 10000, '2025-06-27 19:38:21'),
(44, 75, 'Redeemed 1000 points for $10', 1000, '2025-06-27 19:39:26'),
(45, 75, 'Claimed Daily Points', 5, '2025-06-28 06:49:06'),
(46, 75, 'Claimed Daily Points', 5, '2025-06-29 18:44:33'),
(47, 75, 'Redeemed 0 points for ₦0', 0, '2025-07-01 01:26:13'),
(48, 75, 'Claimed Daily Points', 5, '2025-07-01 01:26:21'),
(49, 75, 'Claimed Daily Points', 5, '2025-07-04 22:03:41'),
(50, 75, 'Redeemed 0 points for ₦0', 0, '2025-07-07 21:09:17'),
(51, 84, 'Claimed Daily Points', 5, '2025-07-09 12:26:39'),
(52, 84, 'Claimed Daily Points', 5, '2025-07-11 13:21:11'),
(53, 84, 'Claimed Daily Points', 5, '2025-07-14 06:54:40'),
(54, 85, 'Claimed Daily Points', 5, '2025-07-30 09:35:17'),
(55, 85, 'Redeemed 5 points for ₦0.05', 5, '2025-07-30 09:35:23'),
(56, 75, 'Redeemed 5000 points for $50', 5000, '2025-08-06 19:47:27'),
(57, 75, 'Redeemed 533 points for $5.33', 533, '2025-08-20 23:14:38'),
(58, 75, 'Claimed Daily Points', 5, '2025-08-23 09:41:21'),
(59, 75, 'Redeemed 4000 points for ₦40', 4000, '2025-08-25 20:27:39'),
(60, 75, 'Claimed Daily Points', 10000, '2025-09-06 10:52:02'),
(61, 75, 'Redeemed 10000 points for $100', 10000, '2025-09-06 10:52:41'),
(62, 87, 'Claimed Daily Points', 5, '2025-09-23 12:20:34'),
(63, 75, 'Claimed Daily Points', 5, '2025-09-28 07:37:46'),
(64, 75, 'Claimed Daily Points', 5, '2025-09-30 01:38:42'),
(65, 75, 'Redeemed 300 points for ₦3', 300, '2025-09-30 01:39:39');

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `id` int(11) NOT NULL,
  `full_name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('super_admin','admin') DEFAULT 'admin'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`id`, `full_name`, `email`, `password`, `role`) VALUES
(1, 'Admin User', 'admin@example.com', 'hashed_password', 'super_admin'),
(2, 'Manager User', 'manager@example.com', 'hashed_password', 'admin');

-- --------------------------------------------------------

--
-- Table structure for table `admin_users`
--

CREATE TABLE `admin_users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `admin_users`
--

INSERT INTO `admin_users` (`id`, `username`, `password`, `created_at`) VALUES
(1, 'admin', '$2y$10$MKVkThLhhMyhIwNkzqimJewbam.cEKsBcKP6hypO2bdqpEdTWdJGG', '2025-05-07 13:51:19');

-- --------------------------------------------------------

--
-- Table structure for table `airtel_data`
--

CREATE TABLE `airtel_data` (
  `id` int(11) NOT NULL,
  `code` decimal(10,2) NOT NULL,
  `description` varchar(255) NOT NULL,
  `duration` varchar(50) DEFAULT NULL,
  `type` varchar(50) DEFAULT NULL,
  `price` decimal(10,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `airtel_data`
--

INSERT INTO `airtel_data` (`id`, `code`, `description`, `duration`, `type`, `price`, `created_at`) VALUES
(1, 499.91, '1GB', '1 day', 'Awoof Data', 483.91, '2025-06-03 14:28:41'),
(2, 599.91, '1.5GB', '2 days', 'Awoof Data', 580.71, '2025-06-03 14:28:41'),
(3, 749.91, '2GB', '2 days', 'Awoof Data', 725.91, '2025-06-03 14:28:41'),
(4, 999.91, '3GB', '2 days', 'Awoof Data', 967.91, '2025-06-03 14:28:41'),
(5, 1499.91, '5GB', '2 days', 'Awoof Data', 1451.91, '2025-06-03 14:28:41'),
(6, 499.92, '500MB', '7 days', 'Direct Data', 483.92, '2025-06-03 14:28:41'),
(7, 799.91, '1GB', '7 days', 'Direct Data', 774.31, '2025-06-03 14:28:41'),
(8, 999.92, '1.5GB', '7 days', 'Direct Data', 967.92, '2025-06-03 14:28:41'),
(9, 1499.92, '3.5GB', '7 days', 'Direct Data', 1451.92, '2025-06-03 14:28:41'),
(10, 2499.91, '6GB', '7 days', 'Direct Data', 2419.91, '2025-06-03 14:28:41'),
(11, 2999.91, '10GB', '7 days', 'Direct Data', 2903.91, '2025-06-03 14:28:41');

-- --------------------------------------------------------

--
-- Table structure for table `airtime_transactions`
--

CREATE TABLE `airtime_transactions` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `order_id` varchar(100) NOT NULL,
  `network` varchar(20) NOT NULL,
  `mobile_number` varchar(20) NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `status` varchar(20) DEFAULT 'pending',
  `created_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `airtime_transactions`
--

INSERT INTO `airtime_transactions` (`id`, `user_id`, `order_id`, `network`, `mobile_number`, `amount`, `status`, `created_at`) VALUES
(19, 67, '6685446226', 'MTN', '08148622359', 48.25, 'success', '2025-06-08 12:27:51'),
(20, 67, '6685446316', 'MTN', '08148622359', 48.25, 'success', '2025-06-08 12:28:42'),
(21, 68, '6685453920', 'MTN', '07039172233', 48.25, 'success', '2025-06-08 13:49:27'),
(22, 67, '6685489336', 'MTN', '07048129920', 96.50, 'success', '2025-06-08 19:00:39'),
(23, 67, '6685493761', 'MTN', '07062305595', 96.50, 'success', '2025-06-08 19:38:25'),
(24, 75, '6685571498', 'MTN', '07076690090', 96.50, 'success', '2025-06-09 12:48:05'),
(25, 76, '6685606178', 'MTN', '08102557787', 96.50, 'success', '2025-06-09 17:58:40'),
(26, 75, '6685606362', 'MTN', '08132751317', 48.25, 'success', '2025-06-09 18:00:19'),
(27, 75, '6685769551', 'AirTel', '07076690090', 48.15, 'success', '2025-06-10 23:23:46'),
(28, 75, '6685835285', 'MTN', '07076690090', 48.25, 'success', '2025-06-11 14:50:49'),
(29, 75, '6685855848', 'MTN', '07076690090', 96.50, 'success', '2025-06-11 17:50:10'),
(30, 75, '6685902657', 'MTN', '07076690090', 289.50, 'success', '2025-06-12 03:17:07'),
(31, 75, '6685902681', 'MTN', '07076690090', 96.50, 'success', '2025-06-12 03:17:47'),
(32, 81, '6686456271', 'MTN', '07076690090', 48.25, 'success', '2025-06-15 18:05:49'),
(33, 81, '6686459068', 'MTN', '08037763336', 96.50, 'success', '2025-06-15 18:23:10'),
(34, 81, '6686975659', 'MTN', '07076690090', 48.25, 'success', '2025-06-19 09:10:31'),
(35, 75, '6687166231', 'AirTel', '08087503374', 96.30, 'success', '2025-06-20 15:13:02'),
(36, 75, '6687436747', 'MTN', '07076690090', 48.25, 'success', '2025-06-22 23:29:33'),
(37, 75, '6687658892', 'MTN', '09136619819', 96.50, 'success', '2025-06-24 22:22:54'),
(38, 75, '6687709774', 'MTN', '07030679093', 482.50, 'success', '2025-06-25 11:01:28'),
(39, 75, '6687899590', 'AirTel', '09045835992', 96.30, 'success', '2025-06-27 08:48:25'),
(40, 75, '6687936090', 'MTN', '07046324949', 96.50, 'success', '2025-06-27 18:13:24'),
(41, 75, '6688165951', 'MTN', '09161287168', 96.50, 'success', '2025-06-29 21:34:43'),
(42, 75, '6688213846', 'MTN', '07076690090', 193.00, 'success', '2025-06-30 10:25:04'),
(43, 75, '6688360304', 'AirTel', '08124147875', 288.90, 'success', '2025-07-01 16:51:35'),
(44, 84, '6688362851', 'MTN', '08145853199', 193.00, 'success', '2025-07-01 17:24:45'),
(45, 75, '6688422172', 'MTN', '08142589700', 96.50, 'success', '2025-07-02 13:46:43'),
(46, 75, '6688576290', 'MTN', '07076690090', 96.50, 'success', '2025-07-04 10:56:46'),
(47, 75, '6688592069', 'MTN', '07076690090', 48.25, 'success', '2025-07-04 14:10:07'),
(48, 75, '6688820197', 'MTN', '07076690090', 96.50, 'success', '2025-07-06 19:07:11'),
(49, 84, '6689083310', 'MTN', '07076690090', 48.25, 'success', '2025-07-09 08:10:00'),
(50, 75, '6690824954', 'AirTel', '07087374940', 192.60, 'success', '2025-07-25 12:36:21'),
(51, 75, '6691058384', 'MTN', '07076690090', 193.00, 'success', '2025-07-27 21:08:01'),
(52, 75, '6691552352', 'MTN', '07076690090', 48.25, 'success', '2025-08-01 21:34:42'),
(53, 75, '6693521863', 'MTN', '08148622359', 96.50, 'success', '2025-08-24 07:23:39'),
(54, 75, '6695086516', 'MTN', '07076690090', 48.25, 'success', '2025-09-10 17:21:19'),
(55, 75, '6695243012', 'MTN', '08164429217', 96.50, 'success', '2025-09-12 19:48:27'),
(56, 75, '6695253857', 'MTN', '07076690090', 96.50, 'success', '2025-09-13 00:39:46'),
(57, 75, '6695515276', 'MTN', '08169182889', 96.50, 'success', '2025-09-16 21:40:13'),
(58, 75, '6695652225', 'AirTel', '07016658205', 192.60, 'success', '2025-09-18 14:10:09'),
(59, 75, '6696001927', 'MTN', '07076690090', 193.00, 'success', '2025-09-23 23:33:29'),
(60, 75, '6696044647', 'AirTel', '09020691119', 96.30, 'success', '2025-09-24 14:56:44'),
(61, 75, '6696383771', 'MTN', '07031562381', 482.50, 'success', '2025-09-29 20:10:50'),
(62, 75, '6696542600', 'MTN', '07076690090', 193.00, 'success', '2025-10-01 22:30:21'),
(63, 75, '6696571235', 'GLO', '09059099153', 182.00, 'success', '2025-10-02 11:37:25'),
(64, 89, '6697113297', 'MTN', '07076690090', 96.50, 'success', '2025-10-09 14:01:20');

-- --------------------------------------------------------

--
-- Table structure for table `audit_logs`
--

CREATE TABLE `audit_logs` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `action` varchar(255) NOT NULL,
  `timestamp` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `banner_alerts`
--

CREATE TABLE `banner_alerts` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `message` text NOT NULL,
  `alert_type` enum('info','warning','success','error') DEFAULT 'info',
  `display_start` timestamp NOT NULL,
  `display_end` timestamp NOT NULL,
  `is_active` tinyint(1) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `banner_alerts`
--

INSERT INTO `banner_alerts` (`id`, `title`, `message`, `alert_type`, `display_start`, `display_end`, `is_active`) VALUES
(2, 'We are excited to have you onboard!!', 'Start Earning ', 'info', '2025-05-08 20:35:00', '2025-12-11 20:35:00', 0),
(3, 'Massive Rise in ', 'Gold portfolio! Grab Now🔥', 'success', '2025-05-12 01:50:00', '2026-05-15 05:50:00', 0),
(4, 'Super Bonus:', 'Get a  5X Bonus of your First Deposit ', 'error', '2025-05-09 01:56:00', '2026-05-18 01:55:00', 0),
(5, 'Network Glitch :', 'East Asia Transactions Error', 'warning', '2025-05-19 12:30:00', '2025-05-22 12:31:00', 0);

-- --------------------------------------------------------

--
-- Table structure for table `beneficiaries`
--

CREATE TABLE `beneficiaries` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `account_number` varchar(20) NOT NULL,
  `bank_code` varchar(10) NOT NULL,
  `bank_name` varchar(100) NOT NULL,
  `account_name` varchar(150) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `beneficiaries`
--

INSERT INTO `beneficiaries` (`id`, `user_id`, `account_number`, `bank_code`, `bank_name`, `account_name`, `created_at`) VALUES
(1, 75, '8148622359', '999992', 'OPay Digital Services Limited (OPay)', 'THEO DESMOND NWOGU', '2025-06-13 18:27:12'),
(2, 75, '8148622359', '999991', 'PalmPay', 'THEO DESMOND NWOGU', '2025-06-14 16:34:40'),
(3, 75, '2032087016', '214', 'First City Monument Bank', 'NWOGU THEO DESMOND', '2025-06-14 16:49:44'),
(4, 81, '8169182889', '999992', 'OPay Digital Services Limited (OPay)', 'JOHN FEIBUA EBIKEFEI', '2025-06-14 20:45:23'),
(5, 81, '8148622359', '999991', 'PalmPay', 'THEO DESMOND NWOGU', '2025-06-14 21:14:14');

-- --------------------------------------------------------

--
-- Table structure for table `bookings`
--

CREATE TABLE `bookings` (
  `id` int(11) NOT NULL,
  `reference` varchar(50) DEFAULT NULL,
  `hotel_id` int(11) DEFAULT NULL,
  `room_type` varchar(255) DEFAULT NULL,
  `checkin_date` date DEFAULT NULL,
  `checkout_date` date DEFAULT NULL,
  `customer_name` varchar(255) DEFAULT NULL,
  `customer_email` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `checked_in_at` datetime DEFAULT NULL,
  `checked_out_at` datetime DEFAULT NULL,
  `guests` enum('1','2','3') NOT NULL DEFAULT '1',
  `purpose` enum('Business','Leisure','Honeymoon','Family Visit','Confidential') NOT NULL DEFAULT 'Leisure',
  `total_cost` decimal(10,0) DEFAULT 0,
  `status` enum('Booking Order Confirmed','Checked In','Checked Out','Cancelled') NOT NULL DEFAULT 'Booking Order Confirmed'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `bookings`
--

INSERT INTO `bookings` (`id`, `reference`, `hotel_id`, `room_type`, `checkin_date`, `checkout_date`, `customer_name`, `customer_email`, `created_at`, `checked_in_at`, `checked_out_at`, `guests`, `purpose`, `total_cost`, `status`) VALUES
(1, 'D59FB5E6DA9B', 1, 'Single Hub Room', '2025-07-09', '2025-07-11', 'Theophilus Dein', 'theo111@gmail.com', '2025-07-09 01:30:41', NULL, NULL, '1', 'Business', 20000, 'Booking Order Confirmed'),
(2, '6F5E5A6D4123', 1, 'Single Hub Room', '2025-07-09', '2025-07-12', 'Theophilus Dein', 'theo111@gmail.com', '2025-07-09 01:32:24', NULL, NULL, '1', 'Business', 30000, 'Booking Order Confirmed'),
(3, 'FAC410FD3271', 1, 'Single Hub Room', '2025-07-09', '2025-07-11', 'Theophilus Dein', 'theo111@gmail.com', '2025-07-09 12:37:29', NULL, NULL, '2', 'Honeymoon', 20000, 'Booking Order Confirmed'),
(4, '7EEAC8B30C58', 1, 'Deluxe Room', '2025-07-11', '2025-07-18', 'Theophilus Dein', 'theo111@gmail.com', '2025-07-10 17:04:25', NULL, NULL, '2', 'Leisure', 175000, 'Booking Order Confirmed'),
(5, '57174D6B6492', 1, 'Single Hub Room', '2025-07-11', '2025-07-12', 'Theophilus Dein', 'theo111@gmail.com', '2025-07-10 23:44:05', '2025-07-11 01:33:51', NULL, '3', 'Honeymoon', 10000, 'Checked In'),
(6, 'A49CB23734C4', 1, 'Single Hub Room', '2025-07-11', '2025-07-13', 'Theophilus Dein', 'theo111@gmail.com', '2025-07-11 01:02:37', '2025-07-11 01:20:06', NULL, '3', 'Honeymoon', 20000, 'Checked In'),
(7, 'C4BC2A7F2622', 1, 'Single Hub Room', '2025-07-11', '2025-07-12', 'Theophilus Dein', 'theo111@gmail.com', '2025-07-11 01:48:11', '2025-07-11 01:49:57', NULL, '3', 'Honeymoon', 10000, 'Checked In'),
(8, 'F3E5DB53FB46', 1, 'Deluxe Room', '2025-07-11', '2025-07-15', 'Theophilus Dein', 'theo111@gmail.com', '2025-07-11 02:06:23', '2025-07-11 02:08:08', NULL, '3', 'Honeymoon', 100000, 'Checked In'),
(9, '3216681466F5', 2, 'Single Room', '2025-07-11', '2025-07-13', 'Theophilus Dein', 'theo111@gmail.com', '2025-07-11 02:50:07', NULL, NULL, '2', 'Leisure', 24000, 'Booking Order Confirmed'),
(10, '0434695F8872', 1, 'Single Hub Room', '2025-07-11', '2025-07-13', 'Theophilus Dein', 'theo111@gmail.com', '2025-07-11 03:01:34', '2025-07-11 03:02:15', NULL, '2', 'Leisure', 20000, 'Checked In'),
(11, 'F5D3444BB541', 1, 'Single Hub Room', '2025-07-11', '2025-07-14', 'Theophilus Dein', 'theo111@gmail.com', '2025-07-11 18:07:22', '2025-07-11 18:34:06', NULL, '2', 'Leisure', 30000, 'Checked In'),
(12, 'C39E04818FA7', 1, 'Single Hub Room', '2025-07-11', '2025-07-13', 'Theophilus Dein', 'theo111@gmail.com', '2025-07-11 19:20:52', NULL, NULL, '2', 'Leisure', 20000, 'Booking Order Confirmed'),
(13, '4BAE80EE6DA4', 1, 'Single Hub Room', '2025-07-12', '2025-07-16', 'Theophilus Dein', 'theo111@gmail.com', '2025-07-12 16:06:51', NULL, NULL, '2', 'Leisure', 40000, 'Booking Order Confirmed'),
(14, '351C05B35CB7', 2, 'Single Room', '2025-07-13', '2025-07-15', 'Theophilus Dein', 'theo111@gmail.com', '2025-07-13 10:32:21', NULL, NULL, '2', '', 24000, 'Booking Order Confirmed'),
(15, 'D0905FFBF326', 1, 'Single Hub Room', '2025-07-14', '2025-07-15', 'Theophilus Dein', 'theo111@gmail.com', '2025-07-14 11:38:03', NULL, NULL, '1', 'Business', 10000, 'Booking Order Confirmed'),
(16, '0876A1485BD5', 1, 'Single Hub Room', '2025-07-22', '2025-07-24', 'Theophilus Dein', 'theo111@gmail.com', '2025-07-21 18:15:19', NULL, NULL, '2', 'Leisure', 20000, 'Booking Order Confirmed'),
(17, 'AE2B16CDA198', 1, 'Deluxe Room', '2025-07-25', '2025-07-27', 'Theo Desmond N.', 'asam@gmail.com', '2025-07-25 11:35:56', NULL, NULL, '2', 'Honeymoon', 50000, 'Booking Order Confirmed'),
(18, 'A7B06DCCC170', 2, 'Executive Suite', '2025-08-01', '2025-08-03', 'Theo Desmond N.', 'asam@gmail.com', '2025-08-01 21:38:11', NULL, NULL, '2', '', 60000, 'Booking Order Confirmed'),
(19, '310ED9E64512', 1, 'Deluxe Room', '2025-08-14', '2025-08-18', 'Theo Desmond N.', 'asam@gmail.com', '2025-08-13 16:42:37', NULL, NULL, '3', 'Family Visit', 100000, 'Booking Order Confirmed'),
(20, 'FCBC0E6D6016', 2, 'Executive Suite', '2025-09-07', '2025-09-13', 'Theo Desmond N.', 'asam@gmail.com', '2025-09-07 07:43:24', NULL, NULL, '3', 'Leisure', 180000, 'Booking Order Confirmed'),
(21, '57675CDB4C02', 1, 'Double Moon Room', '2025-09-11', '2025-09-14', 'Theo Desmond N.', 'asam@gmail.com', '2025-09-10 17:52:40', NULL, NULL, '2', '', 45000, 'Booking Order Confirmed');

-- --------------------------------------------------------

--
-- Table structure for table `booking_categories`
--

CREATE TABLE `booking_categories` (
  `id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `icon_url` varchar(255) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `booking_categories`
--

INSERT INTO `booking_categories` (`id`, `name`, `icon_url`, `description`, `created_at`) VALUES
(1, 'Hotel', 'icons/hotel.png', 'Book hotel rooms and accommodations.', '2025-06-03 11:10:19'),
(2, 'Event', 'icons/event.png', 'Reserve spots for shows, concerts, and more.', '2025-06-03 11:10:19'),
(3, 'Ride', 'icons/ride.png', 'Request on-demand car or bike rides.', '2025-06-03 11:10:19'),
(4, 'Flight', 'icons/flight.png', 'Book domestic and international flights.', '2025-06-03 11:10:19'),
(5, 'Logistics', 'icons/logistics.png', 'Send packages and schedule pickups.', '2025-06-03 11:10:19');

-- --------------------------------------------------------

--
-- Table structure for table `brands`
--

CREATE TABLE `brands` (
  `id` int(11) NOT NULL,
  `brand_name` varchar(255) NOT NULL,
  `services` text DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `logo` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `brands`
--

INSERT INTO `brands` (`id`, `brand_name`, `services`, `address`, `description`, `logo`) VALUES
(1, 'Rebatel Hotels and Suites', 'Hospitality and Accomodation', '#3 Chief Egba Street Azikoro_Agb Rd Yenagoa Bayelsa State', 'Offers a reputable services of Hospitality and comfort in a serene and modest environment with basic facilities to make you enjoy your stay', 'https://digishubb.com/uploads/20250118_125516_0000.png');

-- --------------------------------------------------------

--
-- Table structure for table `cable_transactions`
--

CREATE TABLE `cable_transactions` (
  `id` int(11) NOT NULL,
  `provider` varchar(20) DEFAULT NULL,
  `smartcard` varchar(20) DEFAULT NULL,
  `package_code` varchar(100) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `amount` decimal(10,2) DEFAULT NULL,
  `status` varchar(50) DEFAULT NULL,
  `request_id` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cars`
--

CREATE TABLE `cars` (
  `id` int(11) NOT NULL,
  `title` varchar(100) DEFAULT NULL,
  `slug` varchar(100) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `car_type` varchar(50) DEFAULT NULL,
  `price_per_day` decimal(10,2) DEFAULT NULL,
  `transmission` varchar(20) DEFAULT NULL,
  `fuel_type` varchar(20) DEFAULT NULL,
  `seats` int(11) DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `available` tinyint(1) DEFAULT 1,
  `created_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `cars`
--

INSERT INTO `cars` (`id`, `title`, `slug`, `description`, `car_type`, `price_per_day`, `transmission`, `fuel_type`, `seats`, `image`, `available`, `created_at`) VALUES
(1, 'Mercedes Benz C480', 'toyota-camry-2020', 'Comfortable sedan for city and highway travel', 'Sedan', 25000.00, 'Automatic', 'Petrol', 5, 'https://images.unsplash.com/photo-1514316454349-750a7fd3da3a?w=500&auto=format&fit=crop&q=60&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxzZWFyY2h8MTZ8fGNhcnN8ZW58MHx8MHx8fDA%3D', 1, '2025-06-19 22:35:40'),
(2, 'Honda CR-V 2019', 'honda-crv-2019', 'Spacious SUV for family trips and rugged roads', 'SUV', 30000.00, 'Automatic', 'Petrol', 5, 'crv.jpg', 1, '2025-06-19 22:35:40'),
(3, 'Toyota Hiace Bus', 'toyota-hiace-bus', 'Ideal for group transport and logistics', 'Van', 45000.00, 'Manual', 'Diesel', 15, 'hiace.jpg', 1, '2025-06-19 22:35:40'),
(4, 'Lexus RX 350', 'lexus-rx-350', 'Luxury SUV with advanced features', 'SUV', 60000.00, 'Automatic', 'Petrol', 5, 'lexus.jpg', 1, '2025-06-19 22:35:40'),
(5, 'Kia Rio', 'kia-rio', 'Compact economy car for everyday use', 'Sedan', 20000.00, 'Automatic', 'Petrol', 4, 'kia.jpg', 1, '2025-06-19 22:35:40'),
(6, '2025 Mercedes Benz Class 420', '2025-ercedes-enz-lass-420', 'Best Car to rent for a comfortable journey', 'Sedan', 85000.00, 'Automatic', 'Electric', 4, '1750374269_R1.jpeg', 1, '2025-06-19 23:04:29');

-- --------------------------------------------------------

--
-- Table structure for table `cart_orders`
--

CREATE TABLE `cart_orders` (
  `id` int(10) UNSIGNED NOT NULL,
  `user_id` int(11) NOT NULL,
  `reference` varchar(100) NOT NULL,
  `shop_id` int(11) NOT NULL,
  `cart_data` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL CHECK (json_valid(`cart_data`)),
  `delivery_address` text NOT NULL,
  `delivery_fee` decimal(10,2) NOT NULL DEFAULT 0.00,
  `pay_amount` decimal(10,2) NOT NULL DEFAULT 0.00,
  `full_amount` decimal(10,2) NOT NULL DEFAULT 0.00,
  `payment_method` varchar(50) NOT NULL,
  `status` enum('pending','ordered','cancelled') DEFAULT 'pending',
  `shipping_status` enum('Ready to Ship','Shipping EnRoute','Delivered','Pending Customer Confirmation','Delivery Successful','Returned') DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `cart_orders`
--

INSERT INTO `cart_orders` (`id`, `user_id`, `reference`, `shop_id`, `cart_data`, `delivery_address`, `delivery_fee`, `pay_amount`, `full_amount`, `payment_method`, `status`, `shipping_status`, `created_at`, `updated_at`) VALUES
(1, 84, 'INV1752429491952', 2, '[{\"name\":\"Vested Hoodie\",\"price\":\"100.00\",\"description\":\"Very fast processor and generative output\",\"image\":\"https:\\/\\/yentownhub.space\\/storage\\/stores\\/emmy\\/1-8-600x600.jpg\",\"shop_id\":\"2\",\"quantity\":3}]', 'teh', 100.00, 400.00, 400.00, 'full', 'ordered', 'Ready to Ship', '2025-07-13 17:58:12', '2025-07-15 11:24:36'),
(2, 84, 'INV1752429805227', 2, '[{\"name\":\"Vested Hoodie\",\"price\":\"100.00\",\"description\":\"Very fast processor and generative output\",\"image\":\"https:\\/\\/yentownhub.space\\/storage\\/stores\\/emmy\\/1-8-600x600.jpg\",\"shop_id\":\"2\",\"quantity\":3}]', 'etg', 100.00, 400.00, 400.00, 'full', 'ordered', 'Pending Customer Confirmation', '2025-07-13 18:03:26', '2025-07-16 00:41:37'),
(3, 84, 'INV1752429938792', 2, '[{\"name\":\"Vested Hoodie\",\"price\":\"100.00\",\"description\":\"Very fast processor and generative output\",\"image\":\"https:\\/\\/yentownhub.space\\/storage\\/stores\\/emmy\\/1-8-600x600.jpg\",\"shop_id\":\"2\",\"quantity\":1}]', 'aserg', 100.00, 200.00, 200.00, 'full', 'ordered', 'Returned', '2025-07-13 18:05:39', '2025-07-15 12:03:08'),
(4, 84, 'INV1752430071055', 2, '[{\"name\":\"Vested Hoodie\",\"price\":\"100.00\",\"description\":\"Very fast processor and generative output\",\"image\":\"https:\\/\\/yentownhub.space\\/storage\\/stores\\/emmy\\/1-8-600x600.jpg\",\"shop_id\":\"2\",\"quantity\":1}]', 'awaw', 100.00, 200.00, 200.00, 'full', 'ordered', 'Delivery Successful', '2025-07-13 18:07:51', '2025-07-16 09:39:34'),
(5, 84, 'INV1752430848720', 2, '[{\"name\":\"Vested Hoodie\",\"price\":\"100.00\",\"description\":\"Very fast processor and generative output\",\"image\":\"https:\\/\\/yentownhub.space\\/storage\\/stores\\/emmy\\/1-8-600x600.jpg\",\"shop_id\":\"2\",\"quantity\":9}]', 'Santiago', 100.00, 1000.00, 1000.00, 'full', 'ordered', 'Delivery Successful', '2025-07-13 18:20:49', '2025-07-16 02:24:59'),
(6, 84, 'INV1752432482185', 2, '[{\"name\":\"Vested Hoodie\",\"price\":\"100.00\",\"description\":\"Very fast processor and generative output\",\"image\":\"https:\\/\\/yentownhub.space\\/storage\\/stores\\/emmy\\/1-8-600x600.jpg\",\"shop_id\":\"2\",\"quantity\":4}]', 'grafgv', 100.00, 500.00, 500.00, 'full', 'ordered', 'Delivery Successful', '2025-07-13 18:48:04', '2025-07-16 09:39:17'),
(7, 84, 'INV1752433824460', 2, '[{\"name\":\"Vested Hoodie\",\"price\":\"100.00\",\"description\":\"Very fast processor and generative output\",\"image\":\"https:\\/\\/yentownhub.space\\/storage\\/stores\\/emmy\\/1-8-600x600.jpg\",\"shop_id\":\"2\",\"quantity\":1}]', 'Ekeki', 100.00, 200.00, 200.00, 'full', 'ordered', 'Ready to Ship', '2025-07-13 19:10:25', '2025-07-15 11:28:44'),
(8, 84, 'INV1752434351665', 2, '[{\"name\":\"Vested Hoodie\",\"price\":\"100.00\",\"description\":\"Very fast processor and generative output\",\"image\":\"https:\\/\\/yentownhub.space\\/storage\\/stores\\/emmy\\/1-8-600x600.jpg\",\"shop_id\":\"2\",\"quantity\":2}]', 'Okaka', 100.00, 300.00, 300.00, 'full', 'ordered', 'Ready to Ship', '2025-07-13 19:19:12', '2025-07-15 11:28:39'),
(9, 84, 'INV1752434864325', 2, '[{\"name\":\"Vested Hoodie\",\"price\":\"100.00\",\"description\":\"Very fast processor and generative output\",\"image\":\"https:\\/\\/yentownhub.space\\/storage\\/stores\\/emmy\\/1-8-600x600.jpg\",\"shop_id\":\"2\",\"quantity\":19}]', 'Saudi Arabia', 100.00, 2000.00, 2000.00, 'full', 'cancelled', 'Ready to Ship', '2025-07-13 19:27:45', '2025-07-14 22:00:17'),
(10, 84, 'INV1752435411980', 2, '[{\"name\":\"Oraimo Headset\",\"price\":\"28500.00\",\"description\":\"We are a digital agency that specializes in leveraging businesses and brands digitally. Our goal is to offer services like website development, SMS ads, email marketing, and graphic design to enhance businesses\",\"image\":\"https:\\/\\/yentownhub.space\\/storage\\/4-600x600.jpg\",\"shop_id\":\"2\",\"quantity\":1},{\"name\":\"Samsung iPad 2025\",\"price\":\"263200.00\",\"description\":\"Enjoy Samsung Galaxy iPad 12th Gen with WiFi, Tabular Keyboard\",\"image\":\"https:\\/\\/encrypted-tbn0.gstatic.com\\/images?q=tbn:ANd9GcRwvQW1eKK_1pgNB5QJOFfuxOh8KVkxZ7hFj_oqjfd_7Qm2_1zKkiRY2v15&s=10\",\"shop_id\":\"2\",\"quantity\":1}]', 'ASABA', 100.00, 291800.00, 291800.00, 'full', 'ordered', 'Delivery Successful', '2025-07-13 19:36:53', '2025-07-16 02:23:50'),
(11, 84, 'INV1752436419884', 2, '[{\"name\":\"HP Elitebook\",\"price\":\"895000.00\",\"description\":\"Very fast processor and generative output\",\"image\":\"https:\\/\\/yentownhub.space\\/storage\\/d43249fe-7d05-42bb-b9ef-03f922518d96-600x600.jpg\",\"shop_id\":\"2\",\"quantity\":1}]', 'sangana', 100.00, 895100.00, 895100.00, 'full', 'cancelled', 'Ready to Ship', '2025-07-13 19:53:43', '2025-07-13 21:43:32'),
(12, 84, 'INV1752440258960', 2, '[{\"name\":\"Oraimo Headset\",\"price\":\"28500.00\",\"description\":\"We are a digital agency that specializes in leveraging businesses and brands digitally. Our goal is to offer services like website development, SMS ads, email marketing, and graphic design to enhance businesses\",\"image\":\"https:\\/\\/yentownhub.space\\/storage\\/4-600x600.jpg\",\"shop_id\":\"2\",\"quantity\":3}]', 'Otango', 100.00, 85600.00, 85600.00, 'full', 'pending', 'Ready to Ship', '2025-07-13 20:57:40', '2025-07-13 21:40:00'),
(13, 84, 'INV1752530470761', 2, '[{\"name\":\"Ori Skin acer\",\"price\":\"3500.00\",\"description\":\"Ori Skin Care flames up your skin with the best arom,antic and best fragrances of riucja md \",\"image\":\"uploads\\/1752525329_Screenshot_11-7-2025_192532_swiftaffiliates.cloud.jpeg\",\"shop_id\":\"2\",\"quantity\":1}]', 'ada', 100.00, 3600.00, 3600.00, 'full', 'ordered', 'Delivered', '2025-07-14 22:01:12', '2025-07-15 12:58:44'),
(14, 84, 'INV1752530530554', 2, '[{\"name\":\"Mafuu Sundry \",\"price\":\"25390.00\",\"description\":\"Best in thr mark \",\"image\":\"uploads\\/1752526087_20250713_175154.jpg\",\"shop_id\":\"2\",\"quantity\":1}]', 'd', 100.00, 25490.00, 25490.00, 'full', 'pending', 'Ready to Ship', '2025-07-14 22:02:11', '2025-07-14 22:02:11'),
(15, 84, 'INV1752584408553', 2, '[{\"name\":\"Vested Hoodie\",\"price\":\"100.00\",\"description\":\"Very fast processor and generative output\",\"image\":\"https:\\/\\/yentownhub.space\\/storage\\/stores\\/emmy\\/1-8-600x600.jpg\",\"shop_id\":\"2\",\"quantity\":3},{\"name\":\"Oraimo Headset\",\"price\":\"28500.00\",\"description\":\"We are a digital agency that specializes in leveraging businesses and brands digitally. Our goal is to offer services like website development, SMS ads, email marketing, and graphic design to enhance businesses\",\"image\":\"https:\\/\\/yentownhub.space\\/storage\\/4-600x600.jpg\",\"shop_id\":\"2\",\"quantity\":2}]', 'Sahb', 100.00, 57400.00, 57400.00, 'full', 'ordered', 'Delivery Successful', '2025-07-15 13:00:09', '2025-07-16 01:40:36'),
(16, 84, 'INV1752677895830', 2, '[{\"name\":\"45V Power Priming Engine\",\"price\":\"34000.00\",\"description\":\"What is a Primer? A primer is a short strand of RNA or DNA (generally about 18-22 bases) that serves as a starting point for DNA synthesis. It is required for DNA replication because the \\u2026\",\"image\":\"uploads\\/1752658610_Screenshot_11-7-2025_192532_swiftaffiliates.cloud.jpeg\",\"shop_id\":\"2\",\"quantity\":1}]', 'asa a', 100.00, 34100.00, 34100.00, 'full', 'ordered', 'Returned', '2025-07-16 14:58:16', '2025-07-17 16:49:46'),
(17, 75, 'INV1752791273198', 2, '[{\"name\":\"Vested Hoodie\",\"price\":\"100.00\",\"description\":\"Very fast processor and generative output\",\"image\":\"https:\\/\\/yentownhub.space\\/storage\\/stores\\/emmy\\/1-8-600x600.jpg\",\"shop_id\":\"2\",\"quantity\":1}]', 'YenTown', 100.00, 200.00, 200.00, 'full', 'pending', NULL, '2025-07-17 22:27:52', '2025-07-17 22:27:52'),
(18, 75, 'INV1753447180006', 2, '[{\"name\":\"Vested Hoodie\",\"price\":\"100.00\",\"description\":\"Very fast processor and generative output\",\"image\":\"https:\\/\\/yentownhub.space\\/storage\\/stores\\/emmy\\/1-8-600x600.jpg\",\"shop_id\":\"2\",\"quantity\":2}]', 'Yen', 100.00, 300.00, 300.00, 'full', 'pending', NULL, '2025-07-25 12:39:40', '2025-07-25 12:39:40'),
(19, 75, 'INV1757868932251', 2, '[{\"name\":\"Oraimo Headset\",\"price\":\"28500.00\",\"description\":\"We are a digital agency that specializes in leveraging businesses and brands digitally. Our goal is to offer services like website development, SMS ads, email marketing, and graphic design to enhance businesses\",\"image\":\"https:\\/\\/yentownhub.space\\/storage\\/4-600x600.jpg\",\"shop_id\":\"2\",\"quantity\":2}]', 'rfwf', 100.00, 57100.00, 57100.00, 'full', 'ordered', NULL, '2025-09-14 16:55:32', '2025-09-14 16:55:48'),
(20, 75, 'INV1758205027582', 2, '[{\"name\":\"Ori Skin acer3q\",\"price\":\"3500.00\",\"description\":\"wf\",\"image\":\"uploads\\/1752525667_Screenshot_14-7-2025_153533_web.whatsapp.com.jpeg\",\"shop_id\":\"2\",\"quantity\":1},{\"name\":\"Adco Tube\",\"price\":\"740.00\",\"description\":\"Adco Tube for the week are you going to be able \",\"image\":\"uploads\\/1752531440_bag.png\",\"shop_id\":\"2\",\"quantity\":1},{\"name\":\"Ori Skin acer\",\"price\":\"3500.00\",\"description\":\"Ori Skin Care flames up your skin with the best arom,antic and best fragrances of riucja md \",\"image\":\"uploads\\/1752525329_Screenshot_11-7-2025_192532_swiftaffiliates.cloud.jpeg\",\"shop_id\":\"2\",\"quantity\":1}]', 'Yenagoa', 100.00, 7840.00, 7840.00, 'full', 'ordered', NULL, '2025-09-18 14:17:08', '2025-09-18 14:17:17');

-- --------------------------------------------------------

--
-- Table structure for table `car_bookings`
--

CREATE TABLE `car_bookings` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `car_id` int(11) DEFAULT NULL,
  `pickup_location` varchar(255) DEFAULT NULL,
  `dropoff_location` varchar(255) DEFAULT NULL,
  `pickup_date` datetime DEFAULT NULL,
  `dropoff_date` datetime DEFAULT NULL,
  `total_price` decimal(10,2) DEFAULT NULL,
  `status` enum('pending','confirmed','cancelled') DEFAULT 'pending',
  `created_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `car_bookings`
--

INSERT INTO `car_bookings` (`id`, `user_id`, `car_id`, `pickup_location`, `dropoff_location`, `pickup_date`, `dropoff_date`, `total_price`, `status`, `created_at`) VALUES
(12, 75, 3, 'Aba', 'Onitsha', '2025-07-04 15:14:00', '2025-07-26 15:14:00', 990000.00, 'pending', '2025-07-04 14:14:25'),
(13, 84, 3, 'Aba', 'Onitsha', '2025-07-10 17:42:00', '2025-07-12 17:47:00', 90000.00, 'pending', '2025-07-09 16:42:34');

-- --------------------------------------------------------

--
-- Table structure for table `car_payments`
--

CREATE TABLE `car_payments` (
  `id` int(11) NOT NULL,
  `booking_id` int(11) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `amount` decimal(10,2) DEFAULT NULL,
  `payment_reference` varchar(100) DEFAULT NULL,
  `status` enum('pending','successful','failed') DEFAULT NULL,
  `paid_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `contact_messages`
--

CREATE TABLE `contact_messages` (
  `id` int(11) NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `message` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `reply` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `contact_messages`
--

INSERT INTO `contact_messages` (`id`, `name`, `email`, `message`, `created_at`, `reply`) VALUES
(2, 'Theo', 'theodesmon71@gmail.com', 'Okay', '2025-05-02 13:40:34', NULL),
(3, 'Theo', 'theodesmon71@gmail.com', 'Ffh', '2025-05-02 13:42:16', 'NO way to ask for whta you needed\r\n'),
(6, 'Jayden', 'theodesmon71@gmail.com', 'Welcome to Gboard clipboard, any text you copy will be saved here.', '2025-06-02 02:07:56', NULL),
(7, 'Theo Desmond', 'theceo@digishubb.com', 'Hell', '2025-09-10 16:11:17', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `contracts`
--

CREATE TABLE `contracts` (
  `id` int(11) NOT NULL,
  `investment_name` varchar(255) NOT NULL,
  `amount_gauge` decimal(15,2) NOT NULL,
  `duration` int(11) NOT NULL,
  `profit` decimal(15,2) NOT NULL,
  `status` enum('active','completed','pending') NOT NULL DEFAULT 'pending',
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `user_id` int(11) NOT NULL,
  `amount_invested` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `contracts`
--

INSERT INTO `contracts` (`id`, `investment_name`, `amount_gauge`, `duration`, `profit`, `status`, `created_at`, `updated_at`, `user_id`, `amount_invested`) VALUES
(37, 'Agriculture Farming', 100.00, 7, 15.00, 'active', '2025-04-29 13:30:35', '2025-05-06 19:10:22', 0, 0.00),
(38, 'Real Estate Shares', 2000.00, 21, 22.00, 'active', '2025-04-29 13:30:35', '2025-05-06 19:14:10', 0, 0.00),
(39, 'Crypto Mining Pool', 3000.00, 3, 10.00, 'active', '2025-04-29 13:30:35', '2025-05-06 19:13:41', 0, 0.00),
(40, 'Oil & Gas Investment', 2500.00, 13, 25.00, 'active', '2025-04-29 13:30:35', '2025-05-06 19:14:25', 0, 0.00),
(41, 'Stock Trading Fund', 150.00, 10, 12.00, 'active', '2025-04-29 13:30:35', '2025-05-06 19:10:44', 0, 0.00),
(42, 'Renewable Energy Fund', 1300.00, 6, 17.00, 'active', '2025-04-29 13:30:35', '2025-05-06 19:10:52', 0, 0.00),
(44, 'Gold Reserves', 3000.00, 10, 20.00, 'active', '2025-04-29 13:30:35', '2025-05-06 19:13:52', 0, 0.00),
(45, 'Technology Startups', 500.00, 6, 10.00, 'active', '2025-04-29 13:30:35', '2025-05-06 19:14:42', 0, 0.00),
(49, 'Renewable Solar Projects', 1000.00, 7, 17.00, 'active', '2025-04-29 13:30:35', '2025-05-06 19:11:24', 0, 0.00),
(56, 'Waste Management', 250.00, 5, 14.00, 'active', '2025-04-29 13:30:35', '2025-05-06 19:11:43', 0, 0.00),
(60, 'Poultry Farming Investment', 500.00, 9, 15.00, 'active', '2025-04-29 13:30:35', '2025-05-06 19:15:18', 0, 0.00),
(62, 'Data Center ', 350.00, 8, 8.00, 'active', '2025-04-29 13:30:35', '2025-05-06 19:15:02', 0, 0.00),
(63, 'Film and Entertainment', 600.00, 7, 16.00, 'active', '2025-04-29 13:30:35', '2025-05-06 19:14:50', 0, 0.00);

-- --------------------------------------------------------

--
-- Table structure for table `currency_rates`
--

CREATE TABLE `currency_rates` (
  `id` int(11) NOT NULL,
  `base_currency` varchar(10) NOT NULL,
  `target_currency` varchar(10) NOT NULL,
  `rate` decimal(15,6) NOT NULL,
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `is_active` tinyint(1) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `currency_rates`
--

INSERT INTO `currency_rates` (`id`, `base_currency`, `target_currency`, `rate`, `updated_at`, `is_active`) VALUES
(1, 'USD', 'NGN', 1500.000000, '2025-05-04 11:51:57', 1),
(2, 'USD', 'EUR', 0.930000, '2025-05-04 11:51:57', 1),
(3, 'USD', 'GBP', 0.790000, '2025-05-04 11:51:57', 1),
(4, 'USD', 'JPY', 154.340000, '2025-05-04 11:51:57', 1),
(5, 'USD', 'AUD', 1.510000, '2025-05-04 11:51:57', 1),
(6, 'USD', 'CAD', 1.370000, '2025-05-04 11:51:57', 1),
(7, 'USD', 'CHF', 0.910000, '2025-05-04 11:51:57', 1),
(8, 'USD', 'CNY', 7.230000, '2025-05-04 11:51:57', 1),
(9, 'USD', 'INR', 83.300000, '2025-05-04 11:51:57', 1),
(10, 'USD', 'ZAR', 23.000000, '2025-05-07 11:38:47', 1),
(11, 'USD', 'BRL', 5.120000, '2025-05-04 11:51:57', 1),
(12, 'USD', 'MXN', 17.230000, '2025-05-04 11:51:57', 1),
(13, 'USD', 'HKD', 7.820000, '2025-05-04 11:51:57', 1),
(14, 'USD', 'SGD', 1.350000, '2025-05-04 11:51:57', 1),
(15, 'USD', 'SEK', 10.760000, '2025-05-04 11:51:57', 1),
(16, 'USD', 'NOK', 11.120000, '2025-05-04 11:51:57', 1),
(17, 'USD', 'DKK', 6.930000, '2025-05-04 11:51:57', 1),
(18, 'USD', 'KRW', 1340.500000, '2025-05-04 11:51:57', 1),
(19, 'USD', 'MYR', 4.710000, '2025-05-04 11:51:57', 1),
(20, 'USD', 'THB', 36.540000, '2025-05-04 11:51:57', 1),
(21, 'USD', 'PHP', 57.910000, '2025-05-04 11:51:57', 1),
(22, 'USD', 'IDR', 16123.000000, '2025-05-04 11:51:57', 1),
(23, 'USD', 'PLN', 4.020000, '2025-05-04 11:51:57', 1),
(24, 'USD', 'CZK', 23.150000, '2025-05-04 11:51:57', 1),
(25, 'USD', 'HUF', 357.900000, '2025-05-04 11:51:57', 1),
(26, 'USD', 'TRY', 32.140000, '2025-05-04 11:51:57', 1),
(27, 'USD', 'ILS', 3.680000, '2025-05-04 11:51:57', 1),
(28, 'USD', 'SAR', 3.750000, '2025-05-04 11:51:57', 1),
(29, 'USD', 'AED', 3.670000, '2025-05-04 11:51:57', 1),
(30, 'USD', 'KWD', 0.310000, '2025-05-04 11:51:57', 1),
(31, 'USD', 'QAR', 3.640000, '2025-05-04 11:51:57', 1),
(32, 'USD', 'OMR', 0.380000, '2025-05-04 11:51:57', 1),
(33, 'USD', 'BHD', 0.380000, '2025-05-04 11:51:57', 1),
(34, 'USD', 'PKR', 278.340000, '2025-05-04 11:51:57', 1),
(35, 'USD', 'BDT', 109.330000, '2025-05-04 11:51:57', 1),
(36, 'USD', 'LKR', 304.120000, '2025-05-04 11:51:57', 1),
(37, 'USD', 'EGP', 47.220000, '2025-05-04 11:51:57', 1),
(38, 'USD', 'KES', 132.440000, '2025-05-04 11:51:57', 1),
(39, 'USD', 'GHS', 14.580000, '2025-05-04 11:51:57', 1),
(40, 'USD', 'TZS', 2555.000000, '2025-05-04 11:51:57', 1),
(41, 'USD', 'UGX', 3812.000000, '2025-05-04 11:51:57', 1),
(42, 'USD', 'MAD', 10.100000, '2025-05-04 11:51:57', 1),
(43, 'USD', 'DZD', 134.210000, '2025-05-04 11:51:57', 1),
(44, 'USD', 'TND', 3.120000, '2025-05-04 11:51:57', 1),
(45, 'USD', 'XOF', 614.000000, '2025-05-04 11:51:57', 1),
(46, 'USD', 'XAF', 615.000000, '2025-05-04 11:51:57', 1),
(47, 'USD', 'ZMW', 25.900000, '2025-05-04 11:51:57', 1),
(48, 'USD', 'MZN', 63.250000, '2025-05-04 11:51:57', 1),
(49, 'USD', 'ETB', 56.200000, '2025-05-04 11:51:57', 1),
(50, 'USD', 'RUB', 92.300000, '2025-05-04 11:51:57', 1),
(51, 'USD', 'UAH', 39.150000, '2025-05-04 11:51:57', 1),
(52, 'USD', 'ARS', 870.000000, '2025-05-04 11:51:57', 1),
(53, 'USD', 'VES', 36.200000, '2025-05-04 11:51:57', 1);

-- --------------------------------------------------------

--
-- Table structure for table `daily_login`
--

CREATE TABLE `daily_login` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `points_awarded` int(11) NOT NULL DEFAULT 10,
  `login_date` date NOT NULL,
  `next_available` timestamp NULL DEFAULT (current_timestamp() + interval 1 day),
  `created_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `daily_login`
--

INSERT INTO `daily_login` (`id`, `user_id`, `points_awarded`, `login_date`, `next_available`, `created_at`) VALUES
(1, 1, 10, '2025-03-11', '2025-03-12 12:52:15', '2025-03-11 12:52:15');

-- --------------------------------------------------------

--
-- Table structure for table `data_plans`
--

CREATE TABLE `data_plans` (
  `id` int(11) NOT NULL,
  `network` varchar(20) NOT NULL,
  `plan_name` varchar(100) NOT NULL,
  `plan_type` enum('SME','Direct','XtraValue','Weekend','Social') NOT NULL,
  `validity_days` int(11) NOT NULL,
  `data_volume` varchar(20) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `data_plan` varchar(10) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `data_plans`
--

INSERT INTO `data_plans` (`id`, `network`, `plan_name`, `plan_type`, `validity_days`, `data_volume`, `price`, `created_at`, `data_plan`) VALUES
(1, 'MTN', '500MB SME', 'SME', 30, '500MB', 330.00, '2025-03-16 09:08:34', '500.0'),
(2, 'MTN', '1GB SME', 'SME', 30, '1GB', 660.00, '2025-03-16 09:08:34', '1000.0'),
(3, 'MTN', '2GB SME', 'SME', 30, '2GB', 1320.00, '2025-03-16 09:08:34', '2000.0'),
(4, 'MTN', '3GB SME', 'SME', 30, '3GB', 1980.00, '2025-03-16 09:08:34', '3000.0'),
(5, 'MTN', '1GB Daily + 3mins', 'Direct', 1, '1GB', 350.01, '2025-03-16 09:08:34', '350.01'),
(6, 'MTN', '1.5GB Daily + 100MB YouTube Music', 'Direct', 1, '1.5GB', 388.00, '2025-03-16 09:08:34', NULL),
(7, 'MTN', '2.5GB 2-Day Plan', 'Direct', 2, '2.5GB', 873.00, '2025-03-16 09:08:34', NULL),
(8, 'MTN', '3.2GB 2-Day Plan', 'Direct', 2, '3.2GB', 970.00, '2025-03-16 09:08:34', NULL),
(9, 'MTN', '1GB + 5mins Weekly', 'Direct', 7, '1GB', 776.00, '2025-03-16 09:08:34', NULL),
(10, 'MTN', '5GB Weekly Plan', 'Direct', 7, '5GB', 1455.00, '2025-03-16 09:08:34', NULL),
(11, 'MTN', '7GB Weekly Bundle', 'Direct', 7, '7GB', 2910.00, '2025-03-16 09:08:34', NULL),
(12, 'MTN', '1.8GB + 5mins Monthly', 'Direct', 30, '1.8GB', 1455.00, '2025-03-16 09:08:34', NULL),
(13, 'MTN', '2.7GB + 5mins Monthly', 'Direct', 30, '2.7GB', 1940.00, '2025-03-16 09:08:34', NULL),
(14, 'MTN', '8GB + 25mins Monthly', 'Direct', 30, '8GB', 4365.00, '2025-03-16 09:08:34', NULL),
(15, 'MTN', '11GB + 25mins Monthly', 'Direct', 30, '11GB', 4850.00, '2025-03-16 09:08:34', NULL),
(16, 'MTN', '15GB + 25mins Monthly', 'Direct', 30, '15GB', 6305.00, '2025-03-16 09:08:34', NULL),
(17, 'MTN', '32GB Monthly Plan', 'Direct', 30, '32GB', 10670.00, '2025-03-16 09:08:34', NULL),
(18, 'MTN', '75GB Monthly Plan', 'Direct', 30, '75GB', 19400.00, '2025-03-16 09:08:34', NULL),
(19, 'MTN', '150GB Monthly Plan', 'Direct', 30, '150GB', 33950.00, '2025-03-16 09:08:34', NULL),
(20, 'MTN', '480GB 3-Month Plan', 'Direct', 90, '480GB', 116400.00, '2025-03-16 09:08:34', NULL),
(21, 'MTN', '750MB + 500 talktime', 'XtraValue', 7, '750MB', 485.00, '2025-03-16 09:08:34', NULL),
(22, 'MTN', '4.5GB + 2000 talktime', 'XtraValue', 30, '4.5GB', 1940.00, '2025-03-16 09:08:34', NULL),
(23, 'MTN', '12GB + 3500 talktime', 'XtraValue', 30, '12GB', 3395.00, '2025-03-16 09:08:34', NULL),
(24, 'Airtel', '100MB SME', 'SME', 7, '100MB', 60.00, '2025-03-16 09:09:07', NULL),
(25, 'Airtel', '300MB SME', 'SME', 7, '300MB', 180.00, '2025-03-16 09:09:07', NULL),
(26, 'Airtel', '500MB SME', 'SME', 30, '500MB', 300.00, '2025-03-16 09:09:07', NULL),
(27, 'Airtel', '1GB SME', 'SME', 30, '1GB', 600.00, '2025-03-16 09:09:07', NULL),
(28, 'Airtel', '2GB SME', 'SME', 30, '2GB', 1200.00, '2025-03-16 09:09:07', NULL),
(29, 'Airtel', '5GB SME', 'SME', 30, '5GB', 3000.00, '2025-03-16 09:09:07', NULL),
(30, 'Airtel', '10GB SME', 'SME', 30, '10GB', 6000.00, '2025-03-16 09:09:07', NULL),
(31, 'Airtel', '15GB SME', 'SME', 30, '15GB', 9000.00, '2025-03-16 09:09:07', NULL),
(32, 'Airtel', '20GB SME', 'SME', 30, '20GB', 12000.00, '2025-03-16 09:09:07', NULL),
(33, 'Airtel', '75MB Daily', 'Direct', 1, '75MB', 72.51, '2025-03-16 09:09:07', NULL),
(34, 'Airtel', '100MB Daily', 'Direct', 1, '100MB', 96.71, '2025-03-16 09:09:07', NULL),
(35, 'Airtel', '200MB Daily', 'Direct', 1, '200MB', 193.51, '2025-03-16 09:09:07', NULL),
(36, 'Airtel', '300MB Daily', 'Direct', 1, '300MB', 290.31, '2025-03-16 09:09:07', NULL),
(37, 'Airtel', '500MB Weekly', 'Direct', 7, '500MB', 483.92, '2025-03-16 09:09:07', NULL),
(38, 'Airtel', '1GB Weekly', 'Direct', 7, '1GB', 774.31, '2025-03-16 09:09:07', NULL),
(39, 'Airtel', '1.5GB Weekly', 'Direct', 7, '1.5GB', 967.92, '2025-03-16 09:09:07', NULL),
(40, 'Airtel', '3.5GB Weekly', 'Direct', 7, '3.5GB', 1451.92, '2025-03-16 09:09:07', NULL),
(41, 'Airtel', '6GB Weekly', 'Direct', 7, '6GB', 2419.91, '2025-03-16 09:09:07', NULL),
(42, 'Airtel', '10GB Weekly', 'Direct', 7, '10GB', 2903.91, '2025-03-16 09:09:07', NULL),
(43, 'Airtel', '18GB Weekly', 'Direct', 7, '18GB', 4839.91, '2025-03-16 09:09:07', NULL),
(44, 'Airtel', '2GB Monthly', 'Direct', 30, '2GB', 1451.93, '2025-03-16 09:09:07', NULL),
(45, 'Airtel', '3GB Monthly', 'Direct', 30, '3GB', 1935.91, '2025-03-16 09:09:07', NULL),
(46, 'Airtel', '4GB Monthly', 'Direct', 30, '4GB', 2419.92, '2025-03-16 09:09:07', NULL),
(47, 'Airtel', '8GB Monthly', 'Direct', 30, '8GB', 2903.92, '2025-03-16 09:09:07', NULL),
(48, 'Airtel', '10GB Monthly', 'Direct', 30, '10GB', 3871.91, '2025-03-16 09:09:07', NULL),
(49, 'Airtel', '13GB Monthly', 'Direct', 30, '13GB', 4839.92, '2025-03-16 09:09:07', NULL),
(50, 'Airtel', '18GB Monthly', 'Direct', 30, '18GB', 5807.91, '2025-03-16 09:09:07', NULL),
(51, 'Airtel', '25GB Monthly', 'Direct', 30, '25GB', 7743.91, '2025-03-16 09:09:07', NULL),
(52, 'Airtel', '35GB Monthly', 'Direct', 30, '35GB', 9679.91, '2025-03-16 09:09:07', NULL),
(53, 'Airtel', '60GB Monthly', 'Direct', 30, '60GB', 14519.91, '2025-03-16 09:09:07', NULL),
(54, 'Airtel', '100GB Monthly', 'Direct', 30, '100GB', 19359.91, '2025-03-16 09:09:07', NULL),
(55, 'Airtel', '160GB Monthly', 'Direct', 30, '160GB', 29039.91, '2025-03-16 09:09:07', NULL),
(56, 'Airtel', '210GB Monthly', 'Direct', 30, '210GB', 38719.91, '2025-03-16 09:09:07', NULL),
(57, 'Airtel', '300GB 3-Month Plan', 'Direct', 90, '300GB', 48399.91, '2025-03-16 09:09:07', NULL),
(58, 'Airtel', '350GB 3-Month Plan', 'Direct', 90, '350GB', 58079.91, '2025-03-16 09:09:07', NULL),
(59, 'Airtel', '650GB 3-Month Plan', 'Direct', 90, '650GB', 96799.91, '2025-03-16 09:09:07', NULL),
(60, 'Glo', '200MB SME', 'SME', 14, '200MB', 90.00, '2025-03-16 09:09:07', NULL),
(61, 'Glo', '500MB SME', 'SME', 30, '500MB', 225.00, '2025-03-16 09:09:07', NULL),
(62, 'Glo', '1GB SME', 'SME', 30, '1GB', 450.00, '2025-03-16 09:09:07', NULL),
(63, 'Glo', '2GB SME', 'SME', 30, '2GB', 900.00, '2025-03-16 09:09:07', NULL),
(64, 'Glo', '3GB SME', 'SME', 30, '3GB', 1350.00, '2025-03-16 09:09:07', NULL),
(65, 'Glo', '5GB SME', 'SME', 30, '5GB', 2250.00, '2025-03-16 09:09:07', NULL),
(66, 'Glo', '10GB SME', 'SME', 30, '10GB', 4500.00, '2025-03-16 09:09:07', NULL),
(67, 'Glo', '125MB Daily', 'Direct', 1, '125MB', 95.50, '2025-03-16 09:09:07', NULL),
(68, 'Glo', '260MB 2-Day Plan', 'Direct', 2, '260MB', 191.00, '2025-03-16 09:09:07', NULL),
(69, 'Glo', '1.5GB 14-Day Plan', 'Direct', 14, '1.5GB', 477.50, '2025-03-16 09:09:07', NULL),
(70, 'Glo', '2.6GB Monthly', 'Direct', 30, '2.6GB', 955.00, '2025-03-16 09:09:07', NULL),
(71, 'Glo', '5GB Monthly', 'Direct', 30, '5GB', 1432.50, '2025-03-16 09:09:07', NULL),
(72, 'Glo', '6.25GB Monthly', 'Direct', 30, '6.25GB', 1910.00, '2025-03-16 09:09:07', NULL),
(73, 'Glo', '7.5GB Monthly', 'Direct', 30, '7.5GB', 2387.50, '2025-03-16 09:09:07', NULL),
(74, 'Glo', '11GB Monthly', 'Direct', 30, '11GB', 2865.00, '2025-03-16 09:09:07', NULL),
(75, 'Glo', '14GB Monthly', 'Direct', 30, '14GB', 3820.00, '2025-03-16 09:09:07', NULL),
(76, 'Glo', '18GB Monthly', 'Direct', 30, '18GB', 4775.00, '2025-03-16 09:09:07', NULL),
(77, 'Glo', '29GB Monthly', 'Direct', 30, '29GB', 7640.00, '2025-03-16 09:09:07', NULL),
(78, 'Glo', '40GB Monthly', 'Direct', 30, '40GB', 9550.00, '2025-03-16 09:09:07', NULL),
(79, 'Glo', '69GB Monthly', 'Direct', 30, '69GB', 14325.00, '2025-03-16 09:09:07', NULL),
(80, 'Glo', '110GB Monthly', 'Direct', 30, '110GB', 19100.00, '2025-03-16 09:09:07', NULL),
(81, 'Glo', '165GB Monthly', 'Direct', 30, '165GB', 28650.00, '2025-03-16 09:09:07', NULL),
(82, 'Glo', '220GB Monthly', 'Direct', 30, '220GB', 34380.00, '2025-03-16 09:09:07', NULL),
(83, 'Glo', '320GB Monthly', 'Direct', 30, '320GB', 47750.00, '2025-03-16 09:09:07', NULL),
(84, 'Glo', '380GB Monthly', 'Direct', 30, '380GB', 57300.00, '2025-03-16 09:09:07', NULL),
(85, 'Glo', '475GB Monthly', 'Direct', 30, '475GB', 71625.00, '2025-03-16 09:09:07', NULL),
(86, 'Glo', '2GB 1-Day Plan', 'Direct', 1, '2GB', 477.50, '2025-03-16 09:09:07', NULL),
(87, 'Glo', '6GB Weekly Plan', 'Direct', 7, '6GB', 1432.50, '2025-03-16 09:09:07', NULL),
(88, 'Glo', '2.5GB Weekend Plan', 'Direct', 2, '2.5GB', 477.50, '2025-03-16 09:09:07', NULL),
(89, 'Glo', '875MB Sunday Plan', 'Direct', 1, '875MB', 191.00, '2025-03-16 09:09:07', NULL),
(90, 'Glo', '300MB Social Bundle', 'Direct', 1, '300MB', 95.50, '2025-03-16 09:09:07', NULL),
(91, 'Glo', '1GB Social Bundle', 'Direct', 3, '1GB', 286.50, '2025-03-16 09:09:07', NULL),
(92, 'Glo', '1.5GB Social Bundle', 'Direct', 7, '1.5GB', 477.50, '2025-03-16 09:09:07', NULL),
(93, 'Glo', '3.5GB Social Bundle', 'Direct', 30, '3.5GB', 955.00, '2025-03-16 09:09:07', NULL),
(94, '9Mobile', '100MB Daily', 'Direct', 1, '100MB', 93.00, '2025-03-16 09:10:44', NULL),
(95, '9Mobile', '180MB Daily', 'Direct', 1, '180MB', 139.50, '2025-03-16 09:10:44', NULL),
(96, '9Mobile', '250MB Daily', 'Direct', 1, '250MB', 186.00, '2025-03-16 09:10:44', NULL),
(97, '9Mobile', '450MB Daily', 'Direct', 1, '450MB', 325.50, '2025-03-16 09:10:44', NULL),
(98, '9Mobile', '650MB 3 Days', 'Direct', 3, '650MB', 465.00, '2025-03-16 09:10:44', NULL),
(99, '9Mobile', '1.75GB Weekly', 'Direct', 7, '1.75GB', 1395.00, '2025-03-16 09:10:44', NULL),
(100, '9Mobile', '650MB 14 Days', 'Direct', 14, '650MB', 558.00, '2025-03-16 09:10:44', NULL),
(101, '9Mobile', '1.1GB Monthly', 'Direct', 30, '1.1GB', 930.00, '2025-03-16 09:10:44', NULL),
(102, '9Mobile', '1.4GB Monthly', 'Direct', 30, '1.4GB', 1116.00, '2025-03-16 09:10:44', NULL),
(103, '9Mobile', '2.44GB Monthly', 'Direct', 30, '2.44GB', 1860.00, '2025-03-16 09:10:44', NULL),
(104, '9Mobile', '3.17GB Monthly', 'Direct', 30, '3.17GB', 2325.00, '2025-03-16 09:10:44', NULL),
(105, '9Mobile', '3.91GB Monthly', 'Direct', 30, '3.91GB', 2790.00, '2025-03-16 09:10:44', NULL),
(106, '9Mobile', '5.10GB Monthly', 'Direct', 30, '5.10GB', 3720.00, '2025-03-16 09:10:44', NULL),
(107, '9Mobile', '6.5GB Monthly', 'Direct', 30, '6.5GB', 4650.00, '2025-03-16 09:10:44', NULL),
(108, '9Mobile', '16GB Monthly', 'Direct', 30, '16GB', 11160.00, '2025-03-16 09:10:44', NULL),
(109, '9Mobile', '24.3GB Monthly', 'Direct', 30, '24.3GB', 17205.00, '2025-03-16 09:10:44', NULL),
(110, '9Mobile', '26.5GB Monthly', 'Direct', 30, '26.5GB', 18600.00, '2025-03-16 09:10:44', NULL),
(111, '9Mobile', '39GB 60 Days', 'Direct', 60, '39GB', 27900.00, '2025-03-16 09:10:44', NULL),
(112, '9Mobile', '78GB 90 Days', 'Direct', 90, '78GB', 55800.00, '2025-03-16 09:10:44', NULL),
(113, '9Mobile', '190GB 180 Days', 'Direct', 180, '190GB', 139500.00, '2025-03-16 09:10:44', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `data_transactions`
--

CREATE TABLE `data_transactions` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `order_id` varchar(50) DEFAULT NULL,
  `network` varchar(20) DEFAULT NULL,
  `mobile_number` varchar(20) DEFAULT NULL,
  `plan_code` varchar(50) DEFAULT NULL,
  `plan_name` varchar(100) DEFAULT NULL,
  `amount` decimal(10,2) DEFAULT NULL,
  `status` varchar(20) DEFAULT NULL,
  `wallet_balance` decimal(10,2) DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `data_transactions`
--

INSERT INTO `data_transactions` (`id`, `user_id`, `order_id`, `network`, `mobile_number`, `plan_code`, `plan_name`, `amount`, `status`, `wallet_balance`, `created_at`) VALUES
(21, 75, '6685769140', 'MTN', '07076690090', '1000.01', '3.2GB 2-Day Plan', 980.00, 'success', NULL, '2025-06-10 23:13:14'),
(22, 76, '6687935926', 'MTN', '08102557787', '500.01', '1GB Daily Plan + 1.5mins.', 490.00, 'success', NULL, '2025-06-27 18:11:17'),
(23, 75, '6688146835', 'MTN', '09161287168', '500.01', '1GB Daily Plan + 1.5mins.', 490.00, 'success', NULL, '2025-06-29 18:46:08'),
(24, 84, '6688362966', 'MTN', '08145853199', '100.01', '110MB Daily Plan', 98.00, 'success', NULL, '2025-07-01 17:26:05'),
(25, 75, '6695086648', 'MTN', '07076690090', '100.01', '110MB Daily Plan', 98.00, 'success', NULL, '2025-09-10 17:23:07'),
(26, 75, '6695382042', 'MTN', '07076690090', '200.01', '230MB Daily Plan', 197.00, 'success', NULL, '2025-09-15 02:55:24'),
(27, 75, '6696349147', 'MTN', '08061504826', '1000.01', '3.2GB 2-Day Plan', 980.00, 'success', NULL, '2025-09-29 12:28:15');

-- --------------------------------------------------------

--
-- Table structure for table `deposits`
--

CREATE TABLE `deposits` (
  `id` int(11) NOT NULL,
  `transaction_ref` varchar(100) NOT NULL,
  `user_id` int(11) NOT NULL,
  `wallet` enum('balance') NOT NULL,
  `amount` decimal(12,2) NOT NULL,
  `method_id` int(11) NOT NULL,
  `proof_url` varchar(255) NOT NULL,
  `status` enum('pending','confirmed','declined') DEFAULT 'pending',
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp(6) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `deposits`
--

INSERT INTO `deposits` (`id`, `transaction_ref`, `user_id`, `wallet`, `amount`, `method_id`, `proof_url`, `status`, `created_at`, `updated_at`) VALUES
(14, 'SC55AAD1', 75, 'balance', 5556.00, 4, 'uploads/deposit_proofs/1755731658_u822915062_dthehub_utilit (1).sql', 'pending', '2025-08-20 23:14:18', '0000-00-00 00:00:00.000000'),
(15, 'SC8BFD9D', 75, 'balance', 25000.00, 5, 'uploads/deposit_proofs/1759405650_DOMAIN_LIST.txt', 'pending', '2025-10-02 11:47:30', '0000-00-00 00:00:00.000000');

-- --------------------------------------------------------

--
-- Table structure for table `deposit_methods`
--

CREATE TABLE `deposit_methods` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `deposit_methods`
--

INSERT INTO `deposit_methods` (`id`, `name`, `description`, `is_active`) VALUES
(1, 'Bank Transfer', 'Bank : First Inland \r\nAcct Name: Swift Contract \r\nAcct Numb: 01246883248935', 0),
(2, 'PayPal ', 'paydesk@swiftcontracts.paypal', 0),
(3, 'Bitcoin', '3PL4EE4vhcjs8XpC4CVkqcmRmHHwz1rWhU', 1),
(4, 'USDT (TRC-20)', 'TRk8VHNEEaUC1hkHG9pEGaE7kUnjvS8iJd', 1),
(5, 'Debit Card', 'Currently Unavailable in your Region', 1),
(6, 'Apple Pay', 'swiftcontract@icloud.com', 0);

-- --------------------------------------------------------

--
-- Table structure for table `downloads`
--

CREATE TABLE `downloads` (
  `id` int(11) NOT NULL,
  `timestamp` timestamp NULL DEFAULT current_timestamp(),
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `ecommerce_products`
--

CREATE TABLE `ecommerce_products` (
  `id` int(10) UNSIGNED NOT NULL,
  `shop_id` int(11) DEFAULT NULL,
  `product_name` varchar(255) NOT NULL,
  `product_description` text DEFAULT NULL,
  `category` varchar(100) DEFAULT NULL,
  `stock` int(11) DEFAULT 0,
  `qty` int(11) DEFAULT 1,
  `status` tinyint(1) DEFAULT 1,
  `price` decimal(10,2) NOT NULL,
  `image_url` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `ecommerce_products`
--

INSERT INTO `ecommerce_products` (`id`, `shop_id`, `product_name`, `product_description`, `category`, `stock`, `qty`, `status`, `price`, `image_url`, `created_at`) VALUES
(2, 2, 'Oraimo Headset', 'We are a digital agency that specializes in leveraging businesses and brands digitally. Our goal is to offer services like website development, SMS ads, email marketing, and graphic design to enhance businesses', 'Gadgets', 1, 1, 1, 28500.00, 'https://yentownhub.space/storage/4-600x600.jpg', '2025-06-05 22:49:01'),
(3, 2, 'HP Elitebook', 'Very fast processor and generative output', 'Laptops', 0, 1, 1, 895000.00, 'https://yentownhub.space/storage/d43249fe-7d05-42bb-b9ef-03f922518d96-600x600.jpg', '2025-06-05 23:06:16'),
(4, 2, 'Vested Hoodie', 'Very fast processor and generative output', 'Apparell', 0, 1, 1, 100.00, 'https://yentownhub.space/storage/stores/emmy/1-8-600x600.jpg', '2025-06-05 23:07:00'),
(5, 3, 'Samsung Smart TV HD', 'Quality Ultra Slim high grade Tv', 'Electronics', 0, 1, 1, 472999.00, 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcRPmZWtLsmDu_3N7-O8Wy7wCQJnWhzkYuRhUwmbg6iZYzEIu5Y10OXlH_A&s=10', '2025-06-06 00:20:17'),
(6, 2, 'Samsung iPad 2025', 'Enjoy Samsung Galaxy iPad 12th Gen with WiFi, Tabular Keyboard', 'Gadgets', 0, 1, 0, 263200.00, 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcRwvQW1eKK_1pgNB5QJOFfuxOh8KVkxZ7hFj_oqjfd_7Qm2_1zKkiRY2v15&s=10', '2025-06-06 07:26:13'),
(7, 2, 'PineCord Bond', 'SKU for PineCord ', 'Electronics', 1, 45, 0, 9088.00, 'uploads/1752525105_Screenshot_14-7-2025_153533_web.whatsapp.com.jpeg', '2025-07-14 20:31:45'),
(8, 2, 'Ori Skin acer', 'Ori Skin Care flames up your skin with the best arom,antic and best fragrances of riucja md ', 'Fashion', 1, 34, 0, 3500.00, 'uploads/1752525329_Screenshot_11-7-2025_192532_swiftaffiliates.cloud.jpeg', '2025-07-14 20:35:29'),
(9, 2, 'Ori Skin acer3', 'best evwer', 'Fashion', 1, 343, 0, 3500.00, 'uploads/1752525422_Screenshot_14-7-2025_153533_web.whatsapp.com.jpeg', '2025-07-14 20:37:02'),
(10, 2, 'Ori Skin acer3q', 'wf', 'Groceries', 1, 343, 1, 3500.00, 'uploads/1752525667_Screenshot_14-7-2025_153533_web.whatsapp.com.jpeg', '2025-07-14 20:41:07'),
(11, 2, 'Mafuu Sundry ', 'Best in thr mark ', 'Books', 1, 29, 1, 25390.00, 'uploads/1752526087_20250713_175154.jpg', '2025-07-14 20:48:07'),
(12, 2, 'Ori Skin acer3qtr', 'hfmdm', 'Mobile Phones & Accessories', 1, 34367, 1, 3500.00, 'uploads/1752528019_Screenshot_11-7-2025_192532_swiftaffiliates.cloud.jpeg', '2025-07-14 21:20:19'),
(13, 2, 'Ori Skin acer3qtr', 'hfmdm', 'Mobile Phones & Accessories', 1, 34367, 1, 3500.00, 'uploads/1752529229_Screenshot_11-7-2025_192532_swiftaffiliates.cloud.jpeg', '2025-07-14 21:40:29'),
(14, 2, 'Adco Tube', 'Adco Tube for the week are you going to be able ', 'Cosmetics', 0, 250, 1, 740.00, 'uploads/1752531440_bag.png', '2025-07-14 22:17:20'),
(15, 2, '45V Power Priming Engine', 'What is a Primer? A primer is a short strand of RNA or DNA (generally about 18-22 bases) that serves as a starting point for DNA synthesis. It is required for DNA replication because the …', 'Machiberies & Automobiles', 1, 15, 1, 34000.00, 'uploads/1752658610_Screenshot_11-7-2025_192532_swiftaffiliates.cloud.jpeg', '2025-07-16 09:36:50');

-- --------------------------------------------------------

--
-- Table structure for table `events`
--

CREATE TABLE `events` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `location` varchar(255) NOT NULL,
  `event_date` date NOT NULL,
  `event_time` time NOT NULL,
  `ticket_price` decimal(10,2) NOT NULL,
  `total_tickets` int(11) NOT NULL,
  `tickets_sold` int(11) DEFAULT 0,
  `created_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `events`
--

INSERT INTO `events` (`id`, `name`, `description`, `location`, `event_date`, `event_time`, `ticket_price`, `total_tickets`, `tickets_sold`, `created_at`) VALUES
(1, 'Rave Hangout Summer Vibes', 'Get the hotest vibes on June 25th at Monalisa Hotels Yenagoa', 'Monalisa Hotels , Saptex Yenezue-Epie Yenagoa Bayelsa State', '2025-07-25', '12:00:00', 5200.00, 47, 37, '2025-07-03 22:37:36');

-- --------------------------------------------------------

--
-- Table structure for table `event_bookings`
--

CREATE TABLE `event_bookings` (
  `id` int(11) NOT NULL,
  `reference` varchar(50) NOT NULL,
  `event_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `number_of_tickets` int(11) NOT NULL,
  `total_cost` decimal(10,2) NOT NULL,
  `status` enum('Pending','Confirmed','Cancelled') DEFAULT 'Pending',
  `booked_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `event_bookings`
--

INSERT INTO `event_bookings` (`id`, `reference`, `event_id`, `user_id`, `number_of_tickets`, `total_cost`, `status`, `booked_at`) VALUES
(1, '478627D3FB9CF450', 1, 84, 1, 5200.00, 'Confirmed', '2025-07-09 16:43:54'),
(2, 'D8BEB5070071B93C', 1, 84, 3, 15600.00, 'Confirmed', '2025-07-09 16:45:06'),
(3, '6E69AAE524A25222', 1, 84, 5, 26000.00, 'Confirmed', '2025-07-09 16:46:08'),
(4, '0122D3385C575161', 1, 84, 4, 20800.00, 'Confirmed', '2025-07-09 16:51:25'),
(5, '26A12AB8E080626A', 1, 75, 3, 15600.00, 'Confirmed', '2025-07-09 17:04:36'),
(6, '3697E2F036650ACA', 1, 75, 1, 5200.00, 'Confirmed', '2025-07-09 17:05:22'),
(7, '10EE9DD8A799AF75', 1, 75, 1, 5200.00, 'Confirmed', '2025-07-09 17:07:14'),
(8, '5110FA2BEA7BCA49', 1, 84, 1, 5200.00, 'Confirmed', '2025-07-11 12:55:45');

-- --------------------------------------------------------

--
-- Table structure for table `fixed_savings`
--

CREATE TABLE `fixed_savings` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `duration_days` int(11) NOT NULL,
  `interest_rate` decimal(5,2) NOT NULL,
  `start_date` datetime DEFAULT NULL,
  `end_date` datetime DEFAULT NULL,
  `status` enum('pending','active','completed','withdrawn','penalized') DEFAULT 'pending',
  `funding_method` enum('wallet','paystack') NOT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `fixed_savings`
--

INSERT INTO `fixed_savings` (`id`, `user_id`, `amount`, `duration_days`, `interest_rate`, `start_date`, `end_date`, `status`, `funding_method`, `created_at`) VALUES
(3, 75, 300000.00, 90, 10.00, '2025-07-02 23:52:54', '2025-09-30 23:52:54', 'active', 'wallet', '2025-07-02 23:52:54'),
(4, 75, 10000.00, 30, 3.00, '2025-07-02 23:55:27', '2025-08-01 23:55:27', 'active', 'wallet', '2025-07-02 23:55:27'),
(5, 75, 500000.00, 60, 6.00, '2025-07-02 23:56:32', '2025-08-31 23:56:32', 'active', 'wallet', '2025-07-02 23:56:32'),
(6, 75, 25000.00, 30, 3.00, '2025-07-02 23:58:23', '2025-08-01 23:58:23', 'active', 'wallet', '2025-07-02 23:58:23'),
(7, 75, 9000.00, 30, 3.00, '2025-07-03 00:09:47', '2025-08-02 00:09:47', 'active', 'wallet', '2025-07-03 00:09:47'),
(8, 75, 8400.00, 30, 3.00, '2025-07-03 00:10:02', '2025-08-02 00:10:02', 'active', 'wallet', '2025-07-03 00:10:02'),
(9, 75, 12000.00, 90, 10.00, '2025-07-03 00:33:45', '2025-10-01 00:33:45', 'active', 'wallet', '2025-07-03 00:33:45'),
(10, 75, 49000.00, 90, 10.00, '2025-07-04 19:50:24', '2025-10-02 19:50:24', 'active', 'wallet', '2025-07-04 19:50:24'),
(11, 84, 400000.00, 30, 3.00, '2025-07-07 23:06:49', '2025-08-06 23:06:49', 'active', 'wallet', '2025-07-07 23:06:49'),
(12, 75, 25000.00, 30, 3.00, '2025-07-09 02:46:59', '2025-08-08 02:46:59', 'active', 'wallet', '2025-07-09 02:46:59');

-- --------------------------------------------------------

--
-- Table structure for table `follow_links`
--

CREATE TABLE `follow_links` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `link_id` varchar(50) NOT NULL,
  `points_awarded` int(11) NOT NULL DEFAULT 15,
  `followed_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `giftcards`
--

CREATE TABLE `giftcards` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `image_url` text DEFAULT NULL,
  `status` enum('active','inactive') DEFAULT 'active',
  `created_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `giftcards`
--

INSERT INTO `giftcards` (`id`, `name`, `image_url`, `status`, `created_at`) VALUES
(1, 'Amazon', 'https://upload.wikimedia.org/wikipedia/commons/a/a9/Amazon_logo.svg', 'active', '2025-06-08 00:39:42'),
(2, 'iTunes', 'https://upload.wikimedia.org/wikipedia/commons/3/38/ITunes_logo.svg', 'active', '2025-06-08 00:39:42'),
(3, 'Steam', 'https://cdn.iconscout.com/icon/free/png-256/steam-11-569373.png', 'active', '2025-06-08 00:39:42'),
(4, 'Google Play', 'https://upload.wikimedia.org/wikipedia/commons/5/5e/Google_Play_2022_icon.svg', 'active', '2025-06-08 00:39:42'),
(5, 'eBay', 'https://upload.wikimedia.org/wikipedia/commons/1/1b/EBay_logo.svg', 'active', '2025-06-08 00:39:42'),
(8, 'Razer Gold', 'https://www.razer.com/assets/images/gold/razer-gold-logo.svg', 'active', '2025-06-08 00:39:42'),
(12, 'Sephora', 'https://upload.wikimedia.org/wikipedia/commons/4/4b/Sephora_logo.svg', 'active', '2025-06-08 00:39:42');

-- --------------------------------------------------------

--
-- Table structure for table `giftcard_rates`
--

CREATE TABLE `giftcard_rates` (
  `id` int(11) NOT NULL,
  `giftcard_id` int(11) NOT NULL,
  `country` varchar(100) NOT NULL,
  `card_type` enum('physical','e-code') NOT NULL DEFAULT 'physical',
  `rate` decimal(10,2) NOT NULL,
  `currency_symbol` varchar(5) DEFAULT NULL,
  `status` enum('active','inactive') DEFAULT 'active',
  `created_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `giftcard_rates`
--

INSERT INTO `giftcard_rates` (`id`, `giftcard_id`, `country`, `card_type`, `rate`, `currency_symbol`, `status`, `created_at`) VALUES
(1, 1, 'USA', 'physical', 740.00, '$', 'active', '2025-06-08 00:40:48'),
(2, 1, 'USA', 'e-code', 720.00, '$', 'active', '2025-06-08 00:40:48'),
(3, 1, 'UK', 'physical', 950.00, '£', 'active', '2025-06-08 00:40:48'),
(4, 1, 'UK', 'e-code', 920.00, '£', 'active', '2025-06-08 00:40:48'),
(5, 2, 'USA', 'physical', 730.00, '$', 'active', '2025-06-08 00:40:48'),
(6, 2, 'USA', 'e-code', 710.00, '$', 'active', '2025-06-08 00:40:48'),
(7, 4, 'USA', 'physical', 710.00, '$', 'active', '2025-06-08 00:40:48'),
(8, 4, 'USA', 'e-code', 690.00, '$', 'active', '2025-06-08 00:40:48'),
(9, 3, 'USA', 'physical', 700.00, '$', 'active', '2025-06-08 00:40:48'),
(10, 3, 'USA', 'e-code', 680.00, '$', 'active', '2025-06-08 00:40:48'),
(12, 8, 'USA', 'e-code', 650.00, '$', 'active', '2025-06-08 00:40:48'),
(13, 5, 'USA', 'physical', 720.00, '$', 'active', '2025-06-08 00:40:48'),
(14, 5, 'USA', 'e-code', 700.00, '$', 'active', '2025-06-08 00:40:48');

-- --------------------------------------------------------

--
-- Table structure for table `giftcard_trades`
--

CREATE TABLE `giftcard_trades` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `giftcard_id` int(11) NOT NULL,
  `country` varchar(100) NOT NULL,
  `card_type` enum('physical','e-code') NOT NULL DEFAULT 'physical',
  `amount` decimal(10,2) NOT NULL,
  `image_path` text NOT NULL,
  `status` enum('pending','approved','rejected') DEFAULT 'pending',
  `admin_note` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `giftcard_trades`
--

INSERT INTO `giftcard_trades` (`id`, `user_id`, `giftcard_id`, `country`, `card_type`, `amount`, `image_path`, `status`, `admin_note`, `created_at`, `updated_at`) VALUES
(1, 75, 1, 'UK', 'e-code', 200.00, 'uploads/1752029126_IMG-20250708-WA0017.jpg', 'pending', NULL, '2025-07-09 02:45:26', NULL),
(2, 84, 1, 'UK', 'physical', 100.00, 'uploads/1752262364_Screenshot_11-7-2025_192532_swiftaffiliates.cloud.jpeg', 'pending', NULL, '2025-07-11 19:32:44', NULL),
(3, 75, 5, 'USA', 'physical', 2500.00, 'uploads/1757884593_Screenshot_20250914-213653_Dingtone.jpg', 'pending', NULL, '2025-09-14 21:16:33', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `glo_data`
--

CREATE TABLE `glo_data` (
  `id` int(11) NOT NULL,
  `code` decimal(10,2) NOT NULL,
  `description` varchar(255) NOT NULL,
  `duration` varchar(50) DEFAULT NULL,
  `type` varchar(50) DEFAULT NULL,
  `price` decimal(10,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `glo_data`
--

INSERT INTO `glo_data` (`id`, `code`, `description`, `duration`, `type`, `price`, `created_at`) VALUES
(1, 1000.11, '1 GB', '3 days', 'SME', 330.00, '2025-06-03 14:30:11'),
(2, 3000.11, '3 GB', '3 days', 'SME', 990.00, '2025-06-03 14:30:11'),
(3, 5000.11, '5 GB', '3 days', 'SME', 1650.00, '2025-06-03 14:30:11'),
(4, 1000.12, '1 GB', '7 days', 'SME', 385.00, '2025-06-03 14:30:11'),
(5, 3000.12, '3 GB', '7 days', 'SME', 1155.00, '2025-06-03 14:30:11'),
(6, 5000.12, '5 GB', '7 days', 'SME', 1925.00, '2025-06-03 14:30:11'),
(7, 1000.21, '1 GB', '14 days Night Plan', 'SME', 385.00, '2025-06-03 14:30:11'),
(8, 3000.21, '3 GB', '14 days Night Plan', 'SME', 1155.00, '2025-06-03 14:30:11'),
(9, 5000.21, '5 GB', '14 days Night Plan', 'SME', 1925.00, '2025-06-03 14:30:11'),
(10, 10000.21, '10 GB', '14 days Night Plan', 'SME', 3850.00, '2025-06-03 14:30:11');

-- --------------------------------------------------------

--
-- Table structure for table `hotels`
--

CREATE TABLE `hotels` (
  `id` int(11) NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `logo` varchar(255) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `phone` varchar(50) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `wallet_balance` decimal(10,2) NOT NULL DEFAULT 0.00,
  `allow_qr_checkin` tinyint(1) NOT NULL DEFAULT 1 COMMENT '1 = QR check-in enabled, 0 = disabled'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `hotels`
--

INSERT INTO `hotels` (`id`, `name`, `logo`, `address`, `phone`, `email`, `password`, `created_at`, `wallet_balance`, `allow_qr_checkin`) VALUES
(1, 'The Nest Suit & Hotels', 'uploads/hotel_logos/686da78499029_Screenshot_9-7-2025_0192_www.bing.com.jpeg', '123 Beach Road, Lagos', '+2348012345678', 'info@nest.com', '$2y$10$dMNWDC3eP2nmQWOlCpovR.fFbmkanB1Ra4KXCPQeYXLAzsK8Z3KIK', '2025-06-24 23:03:16', 475000.00, 1),
(2, 'Novelty Suites & Hotel', 'https://tse4.mm.bing.net/th/id/OIP.gLycBG2WPkeqOvI59zwT-wHaHa?rs=1&pid=ImgDetMain&o=7&rm=3', '45 Sky Ave, Abuja', '+2348098765432', 'contact@anotherhotel.com', '$2y$10$PHJ8XBPQsSw6btkO3gXkyeAq5Kpk66hPGgvyy3CgLBoc5mwe.T6V.', '2025-06-24 23:03:16', 288000.00, 1);

-- --------------------------------------------------------

--
-- Table structure for table `hotel_chat_messages`
--

CREATE TABLE `hotel_chat_messages` (
  `id` int(11) NOT NULL,
  `hotel_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `sender` enum('customer','hotel') NOT NULL,
  `message` text NOT NULL,
  `status` enum('sent','read') DEFAULT 'sent',
  `typing_status` enum('idle','typing') DEFAULT 'idle',
  `created_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `hotel_chat_messages`
--

INSERT INTO `hotel_chat_messages` (`id`, `hotel_id`, `user_id`, `sender`, `message`, `status`, `typing_status`, `created_at`) VALUES
(1, 2, 75, 'customer', 'hello', 'sent', 'idle', '2025-08-23 09:16:32'),
(2, 1, 75, 'customer', 'Hello', 'sent', 'idle', '2025-09-10 17:51:44'),
(3, 1, 75, 'customer', 'Hi', 'sent', 'idle', '2025-09-15 02:52:32'),
(4, 1, 75, 'customer', 'Please', 'sent', 'idle', '2025-09-15 02:52:41'),
(5, 1, 75, 'customer', 'Vg', 'sent', 'idle', '2025-09-15 02:52:46'),
(6, 1, 75, 'customer', 'Cfs', 'sent', 'idle', '2025-09-15 02:52:52'),
(7, 1, 75, 'customer', 'Vvvksegn', 'sent', 'idle', '2025-09-15 02:53:07');

-- --------------------------------------------------------

--
-- Table structure for table `hotel_facilities`
--

CREATE TABLE `hotel_facilities` (
  `id` int(11) NOT NULL,
  `hotel_id` int(11) DEFAULT NULL,
  `facility` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `hotel_facilities`
--

INSERT INTO `hotel_facilities` (`id`, `hotel_id`, `facility`) VALUES
(1, 1, 'Free Wi-Fi'),
(2, 1, 'Swimming Pool'),
(4, 2, 'Restaurant'),
(5, 2, '24hr Front Desk'),
(6, 2, 'Airport Shuttle'),
(7, 1, 'Parking Space'),
(9, 1, 'Gym'),
(10, 1, 'Conference Room'),
(11, 1, 'Breakfast Free');

-- --------------------------------------------------------

--
-- Table structure for table `hotel_images`
--

CREATE TABLE `hotel_images` (
  `id` int(11) NOT NULL,
  `hotel_id` int(11) NOT NULL,
  `image_url` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `hotel_images`
--

INSERT INTO `hotel_images` (`id`, `hotel_id`, `image_url`) VALUES
(1, 1, 'https://tse1.mm.bing.net/th/id/OIP.YlB1Z22pHKdxHJyvH78h8gHaE8?rs=1&pid=ImgDetMain&o=7&rm=3'),
(3, 1, '../uploads/hotels/1752018260_Screenshot_9-7-2025_0157_tse3.mm.bing.net.jpeg'),
(4, 1, '../uploads/hotels/1752018293_a7b8ca2d410414d4b45fcfe198efebcd.jpg'),
(5, 1, '../uploads/hotels/1752018303_OIP.webp');

-- --------------------------------------------------------

--
-- Table structure for table `hotel_rooms`
--

CREATE TABLE `hotel_rooms` (
  `id` int(11) NOT NULL,
  `hotel_id` int(11) DEFAULT NULL,
  `room_type` varchar(255) DEFAULT NULL,
  `price` decimal(10,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `hotel_rooms`
--

INSERT INTO `hotel_rooms` (`id`, `hotel_id`, `room_type`, `price`) VALUES
(1, 1, 'Single Hub Room', 10000.00),
(2, 1, 'Deluxe Room', 25000.00),
(3, 2, 'Single Room', 12000.00),
(4, 2, 'Executive Suite', 30000.00),
(5, 1, 'Executive VIP Room', 45000.00),
(6, 1, 'Double Moon Room', 15000.00);

-- --------------------------------------------------------

--
-- Table structure for table `hotel_typing_status`
--

CREATE TABLE `hotel_typing_status` (
  `id` int(11) NOT NULL,
  `hotel_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `typing` enum('user','admin','none') DEFAULT 'none',
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `identity_verifications`
--

CREATE TABLE `identity_verifications` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `method` enum('bvn','nin') NOT NULL,
  `identifier` varchar(20) NOT NULL,
  `first_name` varchar(100) DEFAULT NULL,
  `last_name` varchar(100) DEFAULT NULL,
  `date_of_birth` date DEFAULT NULL,
  `verified` tinyint(1) DEFAULT 0,
  `created_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `kyc`
--

CREATE TABLE `kyc` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `full_name` varchar(255) NOT NULL,
  `id_number` varchar(50) NOT NULL,
  `address` text NOT NULL,
  `dob` date NOT NULL,
  `document_type` varchar(100) NOT NULL,
  `document_image_url` varchar(255) DEFAULT NULL,
  `status` enum('pending','verified','failed') DEFAULT 'pending',
  `submitted_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `kyc_verifications`
--

CREATE TABLE `kyc_verifications` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `document_type` varchar(50) DEFAULT NULL,
  `id_image` varchar(255) NOT NULL,
  `selfie_image` varchar(255) NOT NULL,
  `confidence_score` float DEFAULT NULL,
  `kyc_status` enum('pending','verified','failed') NOT NULL DEFAULT 'pending',
  `created_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `kyc_verifications`
--

INSERT INTO `kyc_verifications` (`id`, `user_id`, `document_type`, `id_image`, `selfie_image`, `confidence_score`, `kyc_status`, `created_at`) VALUES
(7, 85, NULL, 'nin_uploads/user_85_1756029719.jpg', '', 66.012, 'failed', '0000-00-00 00:00:00'),
(8, 85, NULL, 'nin_uploads/user_85_1756029752.jpg', '', 85.143, 'verified', '0000-00-00 00:00:00'),
(9, 75, NULL, 'nin_uploads/user_75_1757582648.jpg', '', 86.758, 'verified', '0000-00-00 00:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `levels`
--

CREATE TABLE `levels` (
  `id` int(11) NOT NULL,
  `level_name` varchar(255) NOT NULL,
  `target_amount` decimal(10,2) NOT NULL,
  `description` text DEFAULT NULL,
  `unlock_condition` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `levels`
--

INSERT INTO `levels` (`id`, `level_name`, `target_amount`, `description`, `unlock_condition`, `created_at`) VALUES
(1, 'Bronze', 5000.00, 'You are just starting! Keep going to unlock more levels.', 'Reach ₦5000', '2025-05-02 14:59:06'),
(2, 'Silver', 15000.00, 'You are getting better! Keep it up.', 'Reach ₦15000', '2025-05-02 14:59:06'),
(3, 'Gold', 30000.00, 'Great work! You are a top member now.', 'Reach ₦30000', '2025-05-02 14:59:06');

-- --------------------------------------------------------

--
-- Table structure for table `mtn_data`
--

CREATE TABLE `mtn_data` (
  `id` int(11) NOT NULL,
  `code` decimal(10,2) NOT NULL,
  `description` varchar(255) NOT NULL,
  `duration` varchar(50) DEFAULT NULL,
  `type` varchar(50) DEFAULT NULL,
  `price` decimal(10,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `mtn_data`
--

INSERT INTO `mtn_data` (`id`, `code`, `description`, `duration`, `type`, `price`, `created_at`) VALUES
(1, 500.00, '500 MB', '7/14 days', 'SME', 480.00, '2025-06-03 14:28:41'),
(2, 1000.00, '1 GB', '7/14 days', 'SME', 730.00, '2025-06-03 14:28:41'),
(3, 2000.00, '2 GB', '7/14 days', 'SME', 1350.00, '2025-06-03 14:28:41'),
(4, 3000.00, '3 GB', '20/30 days', 'SME', 1950.00, '2025-06-03 14:28:41'),
(5, 5000.00, '5 GB', '20/30 days', 'SME', 2690.00, '2025-06-03 14:28:41'),
(6, 100.01, '110MB Daily Plan', '1 day', 'Awoof Data', 98.00, '2025-06-03 14:28:41'),
(7, 200.01, '230MB Daily Plan', '1 day', 'Awoof Data', 197.00, '2025-06-03 14:28:41'),
(8, 350.01, '500MB Daily Plan', '1 day', 'Awoof Data', 340.00, '2025-06-03 14:28:41'),
(9, 500.01, '1GB Daily Plan + 1.5mins.', '1 day', 'Awoof Data', 490.00, '2025-06-03 14:28:41'),
(10, 900.01, '2.5GB 2-Day Plan', '2 days', 'Awoof Data', 880.00, '2025-06-03 14:28:41'),
(11, 1000.01, '3.2GB 2-Day Plan', '2 days', 'Awoof Data', 980.00, '2025-06-03 14:28:41');

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

CREATE TABLE `notifications` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `action_type` varchar(255) NOT NULL,
  `message` text NOT NULL,
  `status` enum('read','unread') DEFAULT 'unread',
  `created_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `notifications`
--

INSERT INTO `notifications` (`id`, `user_id`, `action_type`, `message`, `status`, `created_at`) VALUES
(42, 76, 'p2p_received', 'You received ₦200.00 from Theophilus Dein', 'unread', '2025-07-01 20:44:58'),
(193, 85, 'Points', 'You earned 5 points from your daily claim.', 'unread', '2025-07-30 09:35:17'),
(194, 85, 'Points Redeemed', 'You have redeemed 5 points for ₦0.05.', 'unread', '2025-07-30 09:35:23'),
(220, 85, 'sms_sent', 'Bulk SMS sent to 1 recipient(s). ₦7 deducted.', 'unread', '2025-08-24 10:41:08'),
(223, 85, 'p2p_received', 'You received ₦100.00 from Theo Desmond N.', 'unread', '2025-08-24 13:55:44'),
(224, 85, 'withdrawal', 'Your transfer of ₦100.00 is successful.', 'unread', '2025-08-24 13:56:59'),
(226, 85, 'withdrawal', 'Your transfer of ₦100,000.00 is successful.', 'unread', '2025-08-24 16:24:29'),
(227, 85, 'withdrawal', 'Your transfer of ₦100,000.00 is successful.', 'unread', '2025-08-24 17:39:16'),
(228, 85, 'wallet_credit', '₦1,000.00 was added to your wallet. Ref: 100033250824173036526367943443.', 'unread', '2025-08-24 17:49:48'),
(229, 85, 'withdrawal', 'Your transfer of ₦100.00 is successful.', 'unread', '2025-08-24 17:54:59'),
(230, 85, 'withdrawal', 'Your transfer of ₦4,000.00 is successful.', 'unread', '2025-08-24 18:15:20'),
(231, 85, 'withdrawal', 'Your transfer of ₦1,300.00 is successful.', 'unread', '2025-08-24 18:36:29'),
(232, 85, 'withdrawal', 'Your transfer of ₦4,050.00 is successful.', 'unread', '2025-08-25 09:20:37'),
(233, 85, 'sms_sent', 'Bulk SMS sent to 1 recipient(s). ₦7 deducted.', 'unread', '2025-08-25 18:24:27'),
(236, 85, 'withdrawal', 'Your transfer of ₦20,000.00 is successful.', 'unread', '2025-08-29 10:19:49'),
(273, 85, 'withdrawal', 'Your transfer of ₦5,000.00 is successful.', 'unread', '2025-09-23 18:10:26'),
(291, 89, 'wallet_credit', '₦100.00 was added to your wallet. Ref: 100004251009135941142748174046.', 'unread', '2025-10-09 14:00:05'),
(292, 89, 'airtime_purchase', '₦96.50 airtime sent to 07076690090 successfully.', 'unread', '2025-10-09 14:01:20'),
(293, 85, 'withdrawal', 'Your transfer of ₦50,000.00 is successful.', 'unread', '2025-10-12 12:53:57'),
(294, 85, 'withdrawal', 'Your transfer of ₦50,000.00 is successful.', 'unread', '2025-10-12 12:55:36'),
(295, 85, 'withdrawal', 'Your transfer of ₦50,000.00 is successful.', 'unread', '2025-10-12 12:58:38'),
(296, 85, 'withdrawal', 'Your transfer of ₦50,000.00 is successful.', 'unread', '2025-10-12 13:07:23'),
(297, 85, 'withdrawal', 'Your transfer of ₦5,700.00 is successful.', 'unread', '2025-10-13 11:06:51'),
(299, 85, 'withdrawal', 'Your transfer of ₦20,000.00 is successful.', 'unread', '2025-10-14 18:22:56'),
(304, 85, 'withdrawal', 'Your transfer of ₦12,900.00 is successful.', 'unread', '2025-10-21 07:23:56'),
(305, 85, 'withdrawal', 'Your transfer of ₦1,800.00 is successful.', 'unread', '2025-10-22 12:11:30');

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` int(11) NOT NULL,
  `reference` varchar(100) NOT NULL,
  `shop_id` int(11) NOT NULL,
  `cart_data` text NOT NULL,
  `delivery_address` text NOT NULL,
  `delivery_fee` float NOT NULL,
  `paid_amount` float NOT NULL,
  `full_amount` float NOT NULL,
  `payment_method` varchar(10) NOT NULL,
  `status` varchar(20) NOT NULL DEFAULT 'pending',
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `receipt_file` varchar(255) DEFAULT NULL,
  `tracking_id` varchar(100) DEFAULT NULL,
  `user_id` int(11) NOT NULL,
  `approved_at` datetime DEFAULT NULL,
  `confirmed_by` varchar(100) DEFAULT NULL,
  `shipping_status` varchar(20) DEFAULT 'pending',
  `due_arrival_date` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `reference`, `shop_id`, `cart_data`, `delivery_address`, `delivery_fee`, `paid_amount`, `full_amount`, `payment_method`, `status`, `created_at`, `receipt_file`, `tracking_id`, `user_id`, `approved_at`, `confirmed_by`, `shipping_status`, `due_arrival_date`) VALUES
(1, 'INV1752428054793', 2, '[{\"name\":\"Vested Hoodie\",\"price\":\"100.00\",\"description\":\"Very fast processor and generative output\",\"image\":\"https:\\/\\/yentownhub.space\\/storage\\/stores\\/emmy\\/1-8-600x600.jpg\",\"shop_id\":\"2\",\"quantity\":3}]', 'r6e', 100, 400, 400, 'full', 'approved', '2025-07-13 18:09:21', NULL, '8B7D65E5', 84, NULL, NULL, 'pending', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `p2p`
--

CREATE TABLE `p2p` (
  `id` int(11) NOT NULL,
  `sender` int(11) NOT NULL,
  `receiver` int(11) NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `reference` varchar(50) NOT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `p2p`
--

INSERT INTO `p2p` (`id`, `sender`, `receiver`, `amount`, `reference`, `created_at`) VALUES
(1, 84, 75, 400.00, 'P2P686FF1CBC8231', '2025-07-10 17:00:59'),
(2, 84, 75, 10000.00, 'P2P687165DD593CB', '2025-07-11 19:28:29'),
(3, 75, 85, 100.00, 'P2P68AB19E0E9C79', '2025-08-24 13:55:44');

-- --------------------------------------------------------

--
-- Table structure for table `password_resets`
--

CREATE TABLE `password_resets` (
  `id` int(11) NOT NULL,
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `password_resets`
--

INSERT INTO `password_resets` (`id`, `email`, `token`, `created_at`) VALUES
(1, 'marksmasabae@gmail.com', '10e5648dd18150e017c63fdeb08bc5eeb6d0171ef59071277b438c6b5b084940210191e3ec7f6805283f3823e74738e2ba97', '2025-05-10 10:44:21'),
(2, 'marksmasabae@gmail.com', 'b964057bc2d6ac85ff741d0e0d8cdb95747845fdac6ae8cd3ee2372bfedf182cdcbae30c193d339f070edbb837565a718305', '2025-05-11 09:20:11'),
(3, 'marksmasabae@gmail.com', 'f38305f84f91218f0aeac56a6c320c20e565659abc8139b9a498ceef84e2acd4e2433cc5e4e39e1a0d25bcfa50a8f0ca5569', '2025-05-12 13:52:28');

-- --------------------------------------------------------

--
-- Table structure for table `payments`
--

CREATE TABLE `payments` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `reference` varchar(100) NOT NULL,
  `payment_method` varchar(50) NOT NULL,
  `created_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `payments`
--

INSERT INTO `payments` (`id`, `user_id`, `amount`, `reference`, `payment_method`, `created_at`) VALUES
(1, 76, 500.00, '100004250625154437135484644650', 'virtual_account', '2025-06-25 15:45:03'),
(2, 75, 190.00, '100004250701112446135872540583', 'virtual_account', '2025-07-01 11:25:03'),
(3, 84, 500.00, '100033250701172319900310179218', 'virtual_account', '2025-07-01 17:23:34'),
(4, 84, 5000.00, '110006250703133443084338364001', 'virtual_account', '2025-07-03 13:35:01'),
(5, 75, 5000.00, '100033250704221033817584522217', 'virtual_account', '2025-07-04 22:11:05'),
(6, 75, 500.00, '100033250705162659901736599064', 'virtual_account', '2025-07-05 16:27:17'),
(7, 75, 500.00, '100033250705162743763639170102', 'virtual_account', '2025-07-05 16:28:02'),
(8, 84, 500.00, '100033250711192624444273178706', 'virtual_account', '2025-07-11 19:26:36'),
(9, 84, 500.00, '100033250721181306670825839017', 'virtual_account', '2025-07-21 18:13:32'),
(10, 75, 100.00, '100004250801213621138091179568', 'virtual_account', '2025-08-01 21:36:34'),
(11, 75, 500.00, '100033250823130941390912696305', 'virtual_account', '2025-08-23 13:10:06'),
(12, 85, 1000.00, '100033250824173036526367943443', 'virtual_account', '2025-08-24 17:49:48'),
(13, 75, 100.00, '100033250910171916843156940789', 'virtual_account', '2025-09-10 17:19:34'),
(14, 75, 200.00, '100004250912195022140951418079', 'virtual_account', '2025-09-12 19:50:31'),
(15, 75, 1000.00, '100033250918142029514200415305', 'virtual_account', '2025-09-18 14:20:51'),
(16, 87, 300.00, '100033250923121849102906705415', 'virtual_account', '2025-09-23 12:19:03'),
(17, 75, 1000.00, '100033250930013525478678594238', 'virtual_account', '2025-09-30 01:36:35'),
(18, 75, 1000.00, '100033251003143351922558455943', 'virtual_account', '2025-10-03 14:34:12'),
(19, 89, 100.00, '100004251009135941142748174046', 'virtual_account', '2025-10-09 14:00:05');

-- --------------------------------------------------------

--
-- Table structure for table `product_reviews`
--

CREATE TABLE `product_reviews` (
  `id` int(10) UNSIGNED NOT NULL,
  `product_id` int(10) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `rating` tinyint(3) UNSIGNED NOT NULL CHECK (`rating` between 1 and 5),
  `review_text` text DEFAULT NULL,
  `reply_text` text DEFAULT NULL,
  `status` enum('pending','approved','rejected') DEFAULT 'approved',
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `product_reviews`
--

INSERT INTO `product_reviews` (`id`, `product_id`, `user_id`, `rating`, `review_text`, `reply_text`, `status`, `created_at`, `updated_at`) VALUES
(1, 3, 84, 4, 'I Love This Laptop, the Seller has a good customer interactives session also', NULL, 'pending', '2025-07-16 10:27:11', '2025-07-16 10:27:11'),
(2, 6, 75, 2, 'i didnt expect this wack rom samsung', 'okay, non.\r\nEhu', 'approved', '2025-07-16 10:30:14', '2025-07-16 17:34:05');

-- --------------------------------------------------------

--
-- Table structure for table `promotions`
--

CREATE TABLE `promotions` (
  `id` int(11) NOT NULL,
  `image_url` varchar(255) NOT NULL,
  `link_url` varchar(255) DEFAULT NULL,
  `status` enum('active','inactive') DEFAULT 'active',
  `created_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `promotions`
--

INSERT INTO `promotions` (`id`, `image_url`, `link_url`, `status`, `created_at`) VALUES
(1, 'https://tse3.mm.bing.net/th/id/OIP.0dEKMoC-puPjFFrH3rE_vAHaEQ?rs=1&pid=ImgDetMain', 'https://digishubb.com', 'active', '2025-09-16 16:00:00'),
(2, 'https://www.nymcu.org/hs-fs/hubfs/Menu%20Banner%20Ad_Mortgage_Generic.png?width=760&height=353&name=Menu%20Banner%20Ad_Mortgage_Generic.png', 'https://digishubb.com', 'active', '2025-09-16 16:00:00'),
(3, 'https://ucsikch.ucsihotels.com/wp-content/uploads/2021/09/Website-Banner-Room-Rate-scaled-1.jpg', 'https://digishubb.com', 'active', '2025-09-16 16:11:38'),
(4, 'https://zonebetting.com/wp-content/uploads/2018/10/premierbet_first_deposit.jpg', 'https://digishubb.com', 'active', '2025-09-16 16:23:18'),
(5, 'https://i.ytimg.com/vi/aOPyd-uB9Go/maxresdefault.jpg', 'https://digishubb.com', 'active', '2025-09-16 16:30:03');

-- --------------------------------------------------------

--
-- Table structure for table `quiz_answers`
--

CREATE TABLE `quiz_answers` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `question_id` int(11) DEFAULT NULL,
  `is_correct` tinyint(1) DEFAULT NULL,
  `answered_at` datetime DEFAULT current_timestamp(),
  `points` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `quiz_failures`
--

CREATE TABLE `quiz_failures` (
  `user_id` int(11) NOT NULL,
  `fail_count` int(11) DEFAULT 0,
  `lockout_time` datetime DEFAULT NULL,
  `consecutive_fail_count` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `quiz_progress`
--

CREATE TABLE `quiz_progress` (
  `user_id` int(11) NOT NULL,
  `asked_count` int(11) DEFAULT 0,
  `fail_streak` int(11) DEFAULT 0,
  `last_fail_time` datetime DEFAULT NULL,
  `last_lockout_time` datetime DEFAULT NULL,
  `lockout_type` enum('none','limit_15','fail_3') DEFAULT 'none',
  `used_questions` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `quiz_progress`
--

INSERT INTO `quiz_progress` (`user_id`, `asked_count`, `fail_streak`, `last_fail_time`, `last_lockout_time`, `lockout_type`, `used_questions`) VALUES
(1, 0, 0, '2025-06-06 00:32:50', NULL, 'none', NULL),
(50, 0, 0, NULL, NULL, 'none', NULL),
(67, 0, 0, '2025-06-08 13:38:35', NULL, 'none', NULL),
(75, 0, 0, '2025-06-26 03:51:34', NULL, 'none', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `quiz_questions`
--

CREATE TABLE `quiz_questions` (
  `id` int(11) NOT NULL,
  `question` text NOT NULL,
  `option_a` varchar(255) NOT NULL,
  `option_b` varchar(255) NOT NULL,
  `option_c` varchar(255) NOT NULL,
  `option_d` varchar(255) NOT NULL,
  `correct_option` enum('A','B','C','D') NOT NULL,
  `points` int(11) DEFAULT 1,
  `created_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `quiz_questions`
--

INSERT INTO `quiz_questions` (`id`, `question`, `option_a`, `option_b`, `option_c`, `option_d`, `correct_option`, `points`, `created_at`) VALUES
(1, 'What year did Nigeria gain independence?', '1957', '1960', '1963', '1970', 'B', 5, '2025-06-05 12:53:07'),
(2, 'Who was Nigeria’s first president?', 'Nnamdi Azikiwe', 'Olusegun Obasanjo', 'Muhammadu Buhari', 'Goodluck Jonathan', 'A', 5, '2025-06-05 12:53:07'),
(3, 'What is the capital city of Nigeria?', 'Lagos', 'Abuja', 'Kano', 'Port Harcourt', 'B', 5, '2025-06-05 12:53:07'),
(4, 'Which is the largest ethnic group in Nigeria?', 'Yoruba', 'Igbo', 'Hausa-Fulani', 'Ijaw', 'C', 5, '2025-06-05 12:53:07'),
(5, 'Which Nigerian city is known as the Coal City?', 'Enugu', 'Jos', 'Kaduna', 'Calabar', 'A', 5, '2025-06-05 12:53:07'),
(6, 'What is the official language of Nigeria?', 'Hausa', 'Yoruba', 'English', 'Igbo', 'C', 5, '2025-06-05 12:53:07'),
(7, 'Who is known as the Father of Nigerian Nationalism?', 'Obafemi Awolowo', 'Nnamdi Azikiwe', 'Ahmadu Bello', 'Isaac Boro', 'B', 5, '2025-06-05 12:53:07'),
(8, 'Which river is the longest in Nigeria?', 'Niger River', 'Benue River', 'Cross River', 'Anambra River', 'A', 5, '2025-06-05 12:53:07'),
(9, 'Which Nigerian state is the most populous?', 'Lagos', 'Kano', 'Kaduna', 'Rivers', 'B', 5, '2025-06-05 12:53:07'),
(10, 'What does the green color on the Nigerian flag represent?', 'Wealth', 'Peace', 'Agriculture', 'Unity', 'C', 5, '2025-06-05 12:53:07'),
(11, 'Nigeria is a member of which international organization?', 'African Union', 'European Union', 'NATO', 'ASEAN', 'A', 5, '2025-06-05 12:53:07'),
(12, 'Who wrote the Nigerian national anthem?', 'Lilian Jean Williams', 'John A. Ilechukwu', 'Fela Kuti', 'Chinua Achebe', 'B', 5, '2025-06-05 12:53:07'),
(13, 'Which of these is Nigeria’s largest oil-producing region?', 'Delta', 'Kano', 'Abuja', 'Lagos', 'A', 5, '2025-06-05 12:53:07'),
(14, 'Which year did Nigeria host the African Cup of Nations for the first time?', '1980', '1994', '1976', '2000', 'C', 5, '2025-06-05 12:53:07'),
(15, 'Who was Nigeria’s first female professor?', 'Grace Alele-Williams', 'Ngozi Okonjo-Iweala', 'Funmilayo Ransome-Kuti', 'Amina J. Mohammed', 'A', 5, '2025-06-05 12:53:07'),
(16, 'What is the currency of Nigeria?', 'Naira', 'Cedi', 'Shilling', 'Dollar', 'A', 5, '2025-06-05 12:53:07'),
(17, 'Which Nigerian city is known as the “Garden City”?', 'Ibadan', 'Port Harcourt', 'Enugu', 'Calabar', 'D', 5, '2025-06-05 12:53:07'),
(18, 'Which Nigerian state is known as the “Centre of Excellence”?', 'Lagos', 'Anambra', 'Ondo', 'Rivers', 'A', 5, '2025-06-05 12:53:07'),
(19, 'What is Nigeria’s largest religious group?', 'Christianity', 'Islam', 'Traditional Religion', 'Buddhism', 'B', 5, '2025-06-05 12:53:07'),
(20, 'Who is the first Nigerian Nobel laureate?', 'Wole Soyinka', 'Chinua Achebe', 'Ngozi Okonjo-Iweala', 'Fela Kuti', 'A', 5, '2025-06-05 12:53:07'),
(21, 'Which sea borders Nigeria to the south?', 'Atlantic Ocean', 'Indian Ocean', 'Mediterranean Sea', 'Red Sea', 'A', 5, '2025-06-05 12:53:07'),
(22, 'What year was Abuja declared the capital of Nigeria?', '1990', '1976', '1986', '1991', 'C', 5, '2025-06-05 12:53:07'),
(23, 'Which Nigerian city is known for its leatherworks?', 'Kano', 'Lagos', 'Ibadan', 'Enugu', 'A', 5, '2025-06-05 12:53:07'),
(24, 'Which Nigerian leader was overthrown in a coup in 1966?', 'Nnamdi Azikiwe', 'Yakubu Gowon', 'Aguiyi Ironsi', 'Goodluck Jonathan', 'C', 5, '2025-06-05 12:53:07'),
(25, 'Which Nigerian festival is famous for masquerades?', 'Osun-Osogbo', 'Eyo Festival', 'Argungu Fishing', 'Calabar Carnival', 'B', 5, '2025-06-05 12:53:07'),
(26, 'What is the main ingredient in Nigeria’s Jollof rice?', 'Rice', 'Beans', 'Yam', 'Plantain', 'A', 5, '2025-06-05 12:53:07'),
(27, 'Who is the founder of modern Nigerian literature?', 'Chinua Achebe', 'Wole Soyinka', 'Buchi Emecheta', 'Flora Nwapa', 'A', 5, '2025-06-05 12:53:07'),
(28, 'Which Nigerian university is the oldest?', 'University of Lagos', 'University of Nigeria, Nsukka', 'Ahmadu Bello University', 'Obafemi Awolowo University', 'B', 5, '2025-06-05 12:53:07'),
(29, 'Which Nigerian city is the economic capital?', 'Lagos', 'Abuja', 'Port Harcourt', 'Kano', 'A', 5, '2025-06-05 12:53:07'),
(30, 'Which Nigerian musician is known as the King of Afrobeat?', 'Burna Boy', 'Fela Kuti', 'Davido', 'Wizkid', 'B', 5, '2025-06-05 12:53:07'),
(31, 'Which Nigerian sport is most popular?', 'Football', 'Basketball', 'Boxing', 'Wrestling', 'A', 5, '2025-06-05 12:53:07'),
(32, 'What is Nigeria’s independence day?', 'October 1', 'May 29', 'June 12', 'January 1', 'A', 5, '2025-06-05 12:53:07'),
(33, 'Which Nigerian city is famous for its textile industry?', 'Aba', 'Kano', 'Ibadan', 'Enugu', 'A', 5, '2025-06-05 12:53:07'),
(34, 'Who was Nigeria’s first military Head of State?', 'Yakubu Gowon', 'Aguiyi Ironsi', 'Murtala Mohammed', 'Olusegun Obasanjo', 'B', 5, '2025-06-05 12:53:07'),
(35, 'Which Nigerian river flows through Lagos?', 'Niger River', 'Osun River', 'Lekki Lagoon', 'Yewa River', 'C', 5, '2025-06-05 12:53:07'),
(36, 'What is the national animal of Nigeria?', 'Eagle', 'Lion', 'Elephant', 'Crocodile', 'D', 5, '2025-06-05 12:53:07'),
(37, 'Which Nigerian state is home to the Idanre Hills?', 'Ondo', 'Ekiti', 'Osun', 'Oyo', 'A', 5, '2025-06-05 12:53:07'),
(38, 'What is the name of Nigeria’s first satellite?', 'NigSat-1', 'NigeriaSat-1', 'NigiSat', 'NaijaSat', 'B', 5, '2025-06-05 12:53:07'),
(39, 'Who is Nigeria’s first female Vice President?', 'Ngozi Okonjo-Iweala', 'Feyisetan Akosile', 'Ngozi Okonjo-Iweala', 'None', 'D', 5, '2025-06-05 12:53:07'),
(40, 'Which Nigerian state is the leading producer of cocoa?', 'Ondo', 'Ekiti', 'Ogun', 'Osun', 'A', 5, '2025-06-05 12:53:07'),
(41, 'Which Nigerian city is famous for its festival of masks?', 'Ijebu Ode', 'Awka', 'Benin City', 'Ibadan', 'C', 5, '2025-06-05 12:53:07');

-- --------------------------------------------------------

--
-- Table structure for table `receipts`
--

CREATE TABLE `receipts` (
  `id` int(11) NOT NULL,
  `order_reference` varchar(100) DEFAULT NULL,
  `file_path` varchar(255) DEFAULT NULL,
  `submitted_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `receipts`
--

INSERT INTO `receipts` (`id`, `order_reference`, `file_path`, `submitted_at`) VALUES
(38, 'INV1749575012779', 'receipts/receipt_1749575026_4573.jpg', '2025-06-10 17:03:46'),
(39, 'INV1750804010397', 'receipts/receipt_1750804026_1774.jpg', '2025-06-24 22:27:06'),
(40, 'INV1751125402078', 'receipts/receipt_1751125425_7690.jpg', '2025-06-28 15:43:45');

-- --------------------------------------------------------

--
-- Table structure for table `referrals`
--

CREATE TABLE `referrals` (
  `id` int(11) NOT NULL,
  `referrer_id` int(11) NOT NULL,
  `referred_user_id` int(11) NOT NULL,
  `points_awarded` int(11) NOT NULL DEFAULT 50,
  `referral_date` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `referrals`
--

INSERT INTO `referrals` (`id`, `referrer_id`, `referred_user_id`, `points_awarded`, `referral_date`) VALUES
(1, 1, 5, 50, '2025-03-11 12:52:15');

-- --------------------------------------------------------

--
-- Table structure for table `savings_transactions`
--

CREATE TABLE `savings_transactions` (
  `id` int(11) NOT NULL,
  `savings_id` int(11) NOT NULL,
  `type` enum('deposit','interest','withdrawal','penalty') DEFAULT NULL,
  `amount` decimal(10,2) NOT NULL,
  `transaction_date` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `service_features`
--

CREATE TABLE `service_features` (
  `id` int(11) NOT NULL,
  `service_name` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `icon_class` varchar(255) DEFAULT NULL,
  `status` varchar(50) DEFAULT 'Active',
  `created_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `service_features`
--

INSERT INTO `service_features` (`id`, `service_name`, `description`, `icon_class`, `status`, `created_at`) VALUES
(1, 'Data Deals', 'Affordable data packages for various networks.', 'fas fa-signal', 'Active', '2025-02-26 21:46:33'),
(2, 'Bet Top-up', 'Top-up your betting account with ease.', 'fas fa-futbol', 'Active', '2025-02-26 21:46:33'),
(3, 'Bookings & Reservations', 'Book hotels, flights, and other reservations.', 'fas fa-calendar-alt', 'Active', '2025-02-26 21:46:33'),
(4, 'Utility Bills Payment', 'Pay for your utilities like electricity, water, and gas.', 'fas fa-bolt', 'Active', '2025-02-26 21:46:33'),
(5, 'Movie Tickets', 'Buy movie tickets directly from our platform.', 'fas fa-ticket-alt', 'Active', '2025-02-26 21:46:33'),
(6, 'Online Shopping', 'Shop for your favorite products online.', 'fas fa-shopping-cart', 'Active', '2025-02-26 21:46:33'),
(7, 'Health Insurance', 'Access health insurance services.', 'fas fa-heartbeat', 'Active', '2025-02-26 21:46:33'),
(8, 'Loan Services', 'Get personal and business loans easily.', 'fas fa-hand-holding-usd', 'Active', '2025-02-26 21:46:33'),
(9, 'Tax Payment', 'Pay taxes conveniently through our service.', 'fas fa-file-invoice', 'Active', '2025-02-26 21:46:33'),
(10, 'E-Commerce Payment', 'Pay for your goods and services from e-commerce platforms.', 'fas fa-credit-card', 'Active', '2025-02-26 21:46:33');

-- --------------------------------------------------------

--
-- Table structure for table `shipping_logs`
--

CREATE TABLE `shipping_logs` (
  `id` int(10) UNSIGNED NOT NULL,
  `order_id` int(10) UNSIGNED NOT NULL,
  `status` varchar(100) NOT NULL,
  `remarks` text DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `shops`
--

CREATE TABLE `shops` (
  `id` int(10) UNSIGNED NOT NULL,
  `user_id` int(11) NOT NULL,
  `shop_name` varchar(255) NOT NULL,
  `shop_description` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `shop_banks`
--

CREATE TABLE `shop_banks` (
  `id` int(11) NOT NULL,
  `shop_id` int(11) NOT NULL,
  `bank_name` varchar(100) DEFAULT NULL,
  `account_name` varchar(100) DEFAULT NULL,
  `account_number` varchar(30) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `shop_banks`
--

INSERT INTO `shop_banks` (`id`, `shop_id`, `bank_name`, `account_name`, `account_number`, `created_at`) VALUES
(1, 2, 'Eco', 'Dthe Hub Holgin', '4563556654', '2025-06-06 17:54:44');

-- --------------------------------------------------------

--
-- Table structure for table `shop_chats`
--

CREATE TABLE `shop_chats` (
  `id` int(11) NOT NULL,
  `shop_id` int(11) NOT NULL,
  `customer_id` int(11) DEFAULT NULL,
  `sender` enum('shop_owner','customer') NOT NULL,
  `message` text NOT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `is_read` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `shop_chats`
--

INSERT INTO `shop_chats` (`id`, `shop_id`, `customer_id`, `sender`, `message`, `created_at`, `is_read`) VALUES
(1, 2, 75, 'customer', 'Hello', '2025-07-17 23:34:02', 1),
(2, 2, 75, 'shop_owner', 'How can we help. You', '2025-07-17 23:34:27', 0),
(3, 2, 75, 'customer', 'Okay, thank you', '2025-07-17 23:34:42', 1),
(4, 3, 75, 'customer', 'Hello', '2025-07-17 23:37:31', 0),
(5, 2, 75, 'customer', 'Do you have another one size of it', '2025-07-17 23:37:48', 1),
(6, 2, 75, 'shop_owner', 'Yes which color', '2025-07-17 23:53:52', 0),
(7, 2, 75, 'shop_owner', 'Okay', '2025-07-18 00:01:42', 0),
(8, 2, 75, 'shop_owner', 'But I\'ve seen it', '2025-07-18 00:01:54', 0),
(9, 2, 75, 'customer', 'Please send the account number', '2025-07-18 00:02:39', 1),
(10, 2, 75, 'shop_owner', 'When', '2025-07-18 00:02:51', 0),
(11, 2, 75, 'customer', 'Now', '2025-07-18 00:03:09', 1),
(12, 2, 75, 'shop_owner', 'On it', '2025-07-18 00:03:51', 0),
(13, 2, 75, 'shop_owner', 'Okay sir', '2025-07-18 00:35:56', 0),
(14, 2, 75, 'shop_owner', 'Ha e you seen it ?', '2025-07-18 00:47:14', 0),
(15, 2, 75, 'shop_owner', 'Ehat of now', '2025-07-18 00:47:40', 0),
(16, 3, 75, '', 'Who are you', '2025-07-18 00:48:36', 0),
(17, 2, 75, '', 'I said who are you', '2025-07-18 00:49:02', 0),
(18, 2, 75, '', 'Okay', '2025-07-18 00:49:21', 0),
(19, 2, 75, '', 'Fixed?', '2025-07-18 00:53:06', 0),
(20, 2, 75, '', 'Fixed?', '2025-07-18 00:53:06', 0),
(21, 2, 75, '', 'As long', '2025-07-18 00:53:15', 0),
(22, 2, 75, 'customer', 'Stop', '2025-07-18 01:00:50', 1),
(23, 2, 75, 'shop_owner', 'Please be patient', '2025-07-18 01:01:17', 0),
(24, 2, 75, 'customer', 'Enough', '2025-07-18 01:01:43', 1),
(25, 2, 75, 'customer', 'Sk tour', '2025-07-18 01:01:54', 1),
(26, 2, 75, 'customer', 'Oka', '2025-07-18 01:07:34', 1),
(27, 2, 75, 'customer', 'I will', '2025-07-18 01:07:39', 1),
(28, 2, 75, 'customer', 'Plaza', '2025-07-18 01:12:17', 1),
(29, 2, 75, 'customer', 'Please', '2025-07-18 01:16:39', 1),
(30, 2, 75, 'customer', 'Ok', '2025-07-18 01:20:10', 1),
(31, 2, 75, 'customer', 'Bb', '2025-07-18 01:20:14', 1),
(32, 2, 75, 'customer', 'Ok', '2025-07-18 04:05:26', 1),
(33, 2, 75, 'customer', 'Oksaa', '2025-07-18 04:05:32', 1),
(34, 2, 75, 'customer', 'Ojs', '2025-07-18 04:05:36', 1),
(35, 2, 75, 'shop_owner', 'sorru', '2025-07-18 13:36:52', 0),
(36, 2, 84, 'customer', 'why', '2025-07-18 14:13:33', 1),
(37, 2, 84, 'customer', 'no one', '2025-07-18 14:13:47', 1),
(38, 2, 84, 'shop_owner', 'uf', '2025-07-18 14:14:04', 0),
(39, 2, 84, 'shop_owner', 'yf', '2025-07-18 14:14:06', 0),
(40, 2, 84, 'shop_owner', 'tfukt', '2025-07-18 14:14:07', 0),
(41, 2, 84, 'shop_owner', 't', '2025-07-18 14:14:09', 0),
(42, 2, 84, 'shop_owner', 'dkuk', '2025-07-18 14:14:11', 0),
(43, 2, 84, 'shop_owner', 'kdukd', '2025-07-18 14:14:14', 0),
(44, 2, 84, 'shop_owner', 'utfktufk', '2025-07-18 14:14:17', 0),
(45, 2, 84, 'shop_owner', 'dkjdj', '2025-07-18 14:14:20', 0),
(46, 2, 84, 'customer', 'okay', '2025-07-18 14:14:30', 0),
(47, 2, 84, 'customer', 'when then', '2025-07-18 14:14:37', 0);

-- --------------------------------------------------------

--
-- Table structure for table `shop_emails`
--

CREATE TABLE `shop_emails` (
  `id` int(10) UNSIGNED NOT NULL,
  `shop_id` int(10) UNSIGNED NOT NULL,
  `type` enum('inbox','sent') NOT NULL,
  `sender` varchar(255) NOT NULL,
  `recipient` varchar(255) NOT NULL,
  `subject` varchar(255) NOT NULL,
  `message` text NOT NULL,
  `is_read` tinyint(1) DEFAULT 0,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `shop_owners`
--

CREATE TABLE `shop_owners` (
  `id` int(11) NOT NULL,
  `shop_name` varchar(255) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `shop_description` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `ceo_name` varchar(100) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `mobile` varchar(20) DEFAULT NULL,
  `whatsapp_link` varchar(255) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `logo` varchar(255) DEFAULT NULL,
  `wallet_balance` decimal(10,2) NOT NULL DEFAULT 0.00
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `shop_owners`
--

INSERT INTO `shop_owners` (`id`, `shop_name`, `password_hash`, `shop_description`, `created_at`, `ceo_name`, `email`, `mobile`, `whatsapp_link`, `address`, `logo`, `wallet_balance`) VALUES
(2, 'DtheHub', '$2y$10$JWikWZtJRc04K4hgaDZxtOk8q9jlXHab/fUXu5RvlNL5Lm10QE16O', 'Fashion', '2025-06-05 19:43:40', 'Theo Desmond', 'theceo@digishubb.com', '07076690090', 'https://wa.me/2348148622359', '01 New Haven Azikoro Yenagoa Bayelsa State', 'uploads/logos/6841f36ca5f32_logo2.png', 57382.00),
(3, 'DebbyDee', '$2y$10$2w9ilUAaMF/fANTqEI/Go.n.xpws9rdhozEzXg2BOYPgNOxQ42iCm', 'Gadgets', '2025-06-05 23:59:31', 'Debby', 'debbydesmond231@gmail.com', '08148622356', 'https://wa.me/2348148622359', '1 Station rise', '', 784374.00);

-- --------------------------------------------------------

--
-- Table structure for table `shop_visitors`
--

CREATE TABLE `shop_visitors` (
  `id` int(11) NOT NULL,
  `shop_id` int(11) NOT NULL,
  `ip_address` varchar(50) NOT NULL,
  `user_agent` text DEFAULT NULL,
  `visited_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `shop_visitors`
--

INSERT INTO `shop_visitors` (`id`, `shop_id`, `ip_address`, `user_agent`, `visited_at`) VALUES
(1, 2, '102.90.101.185', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36 Edg/138.0.0.0', '2025-07-13 17:27:11'),
(2, 2, '102.90.97.13', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36 Edg/138.0.0.0', '2025-07-13 18:09:50'),
(3, 2, '102.90.82.200', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36 Edg/138.0.0.0', '2025-07-14 08:12:41'),
(4, 2, '102.90.118.200', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36 Edg/138.0.0.0', '2025-07-14 08:29:16'),
(5, 2, '102.88.108.122', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36 Edg/138.0.0.0', '2025-07-14 19:00:57'),
(6, 2, '102.90.101.238', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36 Edg/138.0.0.0', '2025-07-14 19:19:02'),
(7, 2, '102.90.98.164', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36 Edg/138.0.0.0', '2025-07-14 20:31:57'),
(8, 2, '102.90.99.97', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Mobile Safari/537.36 EdgA/138.0.0.0', '2025-07-14 22:15:08'),
(9, 2, '102.90.103.209', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36 Edg/138.0.0.0', '2025-07-15 07:52:36'),
(10, 2, '102.90.81.178', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36 Edg/138.0.0.0', '2025-07-15 10:33:35'),
(11, 2, '102.89.47.218', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36 Edg/138.0.0.0', '2025-07-15 11:51:13'),
(12, 2, '102.90.115.138', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Mobile Safari/537.36 EdgA/138.0.0.0', '2025-07-15 12:53:53'),
(13, 2, '102.90.79.138', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36 Edg/138.0.0.0', '2025-07-15 13:32:59'),
(14, 2, '102.90.96.102', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36 Edg/138.0.0.0', '2025-07-15 15:55:49'),
(15, 2, '102.90.96.102', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36 Edg/138.0.0.0', '2025-07-16 00:14:45'),
(16, 2, '102.90.102.186', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36 Edg/138.0.0.0', '2025-07-16 02:41:41'),
(17, 2, '102.90.102.109', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36 Edg/138.0.0.0', '2025-07-16 09:31:52'),
(18, 2, '102.90.97.5', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36 Edg/138.0.0.0', '2025-07-16 13:45:47'),
(19, 2, '102.90.100.147', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36 Edg/138.0.0.0', '2025-07-16 16:22:29'),
(20, 2, '102.90.80.125', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36 Edg/138.0.0.0', '2025-07-16 17:20:11'),
(21, 2, '102.90.101.56', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Mobile Safari/537.36 EdgA/138.0.0.0', '2025-07-17 16:48:07'),
(22, 2, '102.90.117.25', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Mobile Safari/537.36 EdgA/138.0.0.0', '2025-07-17 19:04:05'),
(23, 2, '102.90.79.145', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Mobile Safari/537.36 EdgA/138.0.0.0', '2025-07-17 22:22:59'),
(24, 2, '102.91.4.200', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Mobile Safari/537.36 EdgA/138.0.0.0', '2025-07-18 00:21:14'),
(25, 2, '102.90.79.172', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Mobile Safari/537.36 EdgA/138.0.0.0', '2025-07-18 00:35:32'),
(26, 2, '102.91.4.253', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Mobile Safari/537.36 EdgA/138.0.0.0', '2025-07-18 03:11:50'),
(27, 2, '102.90.103.53', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36 Edg/138.0.0.0', '2025-07-18 13:35:56');

-- --------------------------------------------------------

--
-- Table structure for table `sms_logs`
--

CREATE TABLE `sms_logs` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `sender_id` varchar(20) DEFAULT NULL,
  `recipients` text DEFAULT NULL,
  `message` text DEFAULT NULL,
  `message_id` varchar(100) DEFAULT NULL,
  `status` varchar(100) DEFAULT NULL,
  `api_response` text DEFAULT NULL,
  `balance_remaining` decimal(10,2) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sms_logs`
--

INSERT INTO `sms_logs` (`id`, `user_id`, `sender_id`, `recipients`, `message`, `message_id`, `status`, `api_response`, `balance_remaining`, `created_at`) VALUES
(1, 84, 'DtheHubb', '2347076690090', '  fmujfbn', '3017522020971026174963420', 'Successfully Sent', '{\"code\":\"ok\",\"balance\":381.41,\"message_id\":\"3017522020971026174963420\",\"message\":\"Successfully Sent\",\"user\":\"Theo Desmond\",\"message_id_str\":\"3017522020971026174963420\"}', 381.41, '2025-07-11 02:48:17'),
(2, 85, 'DtheHubb', '2348138571229', 'Jesus love you ', '3017560320686159966245988', 'Successfully Sent', '{\"code\":\"ok\",\"balance\":186.41,\"message_id\":\"3017560320686159966245988\",\"message\":\"Successfully Sent\",\"user\":\"Theo Desmond\",\"message_id_str\":\"3017560320686159966245988\"}', 186.41, '2025-08-24 10:41:08'),
(3, 85, 'DtheHubb', '2348144777061', 'I love you ', '3017561462671674133567521', 'Successfully Sent', '{\"code\":\"ok\",\"balance\":101.41,\"message_id\":\"3017561462671674133567521\",\"message\":\"Successfully Sent\",\"user\":\"Theo Desmond\",\"message_id_str\":\"3017561462671674133567521\"}', 101.41, '2025-08-25 18:24:27'),
(4, 75, 'DtheHubb', '2347076690090', 'Hello Greetings to you and your family ', '3017575205612472775593127', 'Successfully Sent', '{\"code\":\"ok\",\"balance\":51.64,\"message_id\":\"3017575205612472775593127\",\"message\":\"Successfully Sent\",\"user\":\"Theo Desmond\",\"message_id_str\":\"3017575205612472775593127\"}', 51.64, '2025-09-10 16:09:21'),
(5, 75, 'DtheHubb', '2347076690090,2349068336406', 'Hey Happy New Day ', NULL, 'The string supplied is too long to be a phone number.', '{\"code\":400,\"message\":\"The string supplied is too long to be a phone number.\",\"status\":\"error\",\"link\":\"uri=\\/sms\\/send\"}', NULL, '2025-09-18 14:48:43'),
(6, 75, 'DtheHubb', '2347016658205', 'Hey Happy New Day', '3017582069501544724128233', 'Successfully Sent', '{\"code\":\"ok\",\"balance\":152.11,\"message_id\":\"3017582069501544724128233\",\"message\":\"Successfully Sent\",\"user\":\"Theo Desmond\",\"message_id_str\":\"3017582069501544724128233\"}', 152.11, '2025-09-18 14:49:10'),
(7, 75, 'DtheHubb', '2347076690090', 'Vjrc', '3017593577345602970844308', 'Successfully Sent', '{\"code\":\"ok\",\"balance\":82.11,\"message_id\":\"3017593577345602970844308\",\"message\":\"Successfully Sent\",\"user\":\"Theo Desmond\",\"message_id_str\":\"3017593577345602970844308\"}', 82.11, '2025-10-01 22:28:54');

-- --------------------------------------------------------

--
-- Table structure for table `sms_notifications`
--

CREATE TABLE `sms_notifications` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `phone_number` varchar(15) NOT NULL,
  `message` text NOT NULL,
  `status` enum('sent','failed') DEFAULT 'sent',
  `sent_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `spin_wheel`
--

CREATE TABLE `spin_wheel` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `points_won` int(11) NOT NULL,
  `spin_time` timestamp NULL DEFAULT current_timestamp(),
  `next_available` timestamp NULL DEFAULT (current_timestamp() + interval 6 hour)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `spin_wheel`
--

INSERT INTO `spin_wheel` (`id`, `user_id`, `points_won`, `spin_time`, `next_available`) VALUES
(2, 1, 10, '2025-03-11 12:53:03', '2025-03-11 18:53:03');

-- --------------------------------------------------------

--
-- Table structure for table `tasks`
--

CREATE TABLE `tasks` (
  `id` int(11) NOT NULL,
  `title` varchar(255) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `image_url` text DEFAULT NULL,
  `badge` varchar(50) DEFAULT NULL,
  `points` int(11) DEFAULT NULL,
  `status` enum('active','soon') DEFAULT 'soon'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `tasks`
--

INSERT INTO `tasks` (`id`, `title`, `description`, `image_url`, `badge`, `points`, `status`) VALUES
(1, 'Daily Login Rewards', 'Login daily to your wallet and claim your daily rewards streak.', 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcRjinO3KdwPHKa9JKiktgLDJhZkECD7N6-ragFYRYN2rLwhZHEiGSKNaGTt&s=10', 'Soon', 10, 'active'),
(2, 'Spin the Wheel', 'Take your lucky Spin and win mega points rewards.', 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcT_TwefxV7eAJpssSggrwgTQR3U4L577YEifznSLeSHHg9RhmBovPInnMI&s=10', 'Soon', 20, 'active'),
(5, 'Referral Earnings', 'Onboard your family and earn awesome rewards.', 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcSaSzrarcFruM90rL8wp8deEaXGokdC9l6XPtqIocj82RA1hA1mYAi18mI&s=10', 'Soon', 25, 'active');

-- --------------------------------------------------------

--
-- Table structure for table `top_earners`
--

CREATE TABLE `top_earners` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `user_name` varchar(255) NOT NULL,
  `avatar_url` varchar(255) NOT NULL,
  `amount` decimal(10,2) DEFAULT 0.00,
  `week_start` date NOT NULL,
  `week_end` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `top_earners`
--

INSERT INTO `top_earners` (`id`, `user_id`, `user_name`, `avatar_url`, `amount`, `week_start`, `week_end`) VALUES
(2, 2, 'Jau_Kes', '', 564870.00, '2025-05-05', '2025-05-11');

-- --------------------------------------------------------

--
-- Table structure for table `transactions`
--

CREATE TABLE `transactions` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `wallet_id` varchar(255) NOT NULL,
  `account_number` varchar(20) NOT NULL,
  `bank_name` varchar(65) NOT NULL,
  `bank_code` varchar(10) NOT NULL,
  `account_name` varchar(500) NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `narration` varchar(255) NOT NULL,
  `transaction_ref` varchar(255) NOT NULL,
  `status` enum('pending','success','failed') NOT NULL DEFAULT 'pending',
  `payment_method` varchar(50) NOT NULL DEFAULT 'bank_transfer',
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `type` varchar(50) NOT NULL,
  `network_code` varchar(10) DEFAULT NULL,
  `data_plan` varchar(255) DEFAULT NULL,
  `mobile_number` varchar(20) DEFAULT NULL,
  `description` varchar(200) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `transactions`
--

INSERT INTO `transactions` (`id`, `user_id`, `wallet_id`, `account_number`, `bank_name`, `bank_code`, `account_name`, `amount`, `narration`, `transaction_ref`, `status`, `payment_method`, `created_at`, `type`, `network_code`, `data_plan`, `mobile_number`, `description`) VALUES
(1, 1, '', '4683746085', 'Fairmoney Microfinance Bank Ltd', '090551', 'TARIEBI NDIOMU', 8000.00, 'Services', 'ZG4NXW', '', 'bank_transfer', '2025-05-24 21:21:51', 'Withdrawal', NULL, NULL, NULL, NULL),
(2, 1, '', '', '', '', '', 10.00, '', 'TXN_1748704120', '', 'bank_transfer', '2025-05-31 15:08:44', 'airtime', 'MTN', NULL, '07076690090', NULL),
(3, 1, '', '1100723415', '9 Payment Service Bank', '120001', 'THEO NWOGU', 8000.00, 'Services', 'H8YXMV', '', 'bank_transfer', '2025-05-31 15:12:18', 'Withdrawal', NULL, NULL, NULL, NULL),
(4, 1, '', '1100723415', '9 Payment Service Bank', '120001', 'THEO NWOGU', 250.00, 'Frgg', 'QMS1CN', '', 'bank_transfer', '2025-05-31 15:14:55', 'Withdrawal', NULL, NULL, NULL, NULL),
(5, 1, '', '', '', '', '', 10.00, '', 'TXN_1748828714', '', 'bank_transfer', '2025-06-02 01:45:17', 'airtime', 'MTN', NULL, '07076690090', NULL),
(6, 1, '', '', '', '', '', 500.00, '', 'TXN_1748829675_7792', 'pending', 'bank_transfer', '2025-06-02 02:02:07', 'deposit', NULL, NULL, NULL, NULL),
(7, 1, '', '', '', '', '', 100.00, '', 'TXN_1748829779_1494', 'pending', 'bank_transfer', '2025-06-02 02:03:36', 'deposit', NULL, NULL, NULL, NULL),
(8, 1, '', '', '', '', '', 10.00, '', 'TXN_1748835283', '', 'bank_transfer', '2025-06-02 03:34:46', 'airtime', 'MTN', NULL, '07076690090', NULL),
(138, 22, '', '0100913667', 'Access Bank', '044', 'ENDURANCE ANTHONY OTETEMU', 200.00, 'Test', 'R7VSU3', '', 'bank_transfer', '2025-03-17 19:55:03', 'Withdrawal', NULL, NULL, NULL, NULL),
(139, 22, '', '', '', '', '', 100.00, '', 'TXN_1742314594', '', 'bank_transfer', '2025-03-18 16:16:39', 'airtime', NULL, NULL, NULL, NULL),
(140, 22, '', '', '', '', '', 339.50, '', 'DATA1742314657683', '', 'bank_transfer', '2025-03-18 16:17:37', 'data', NULL, NULL, NULL, NULL),
(141, 22, '', '2032087016', 'First City Monument Bank', '214', 'NWOGU THEO DESMOND', 100.00, 'Test', 'IWA6LT', '', 'bank_transfer', '2025-03-18 16:18:38', 'Withdrawal', NULL, NULL, NULL, NULL),
(142, 22, '', '', '', '', '', 2000.00, '', 'TXN_1742331652_3805', 'pending', 'bank_transfer', '2025-03-18 22:05:30', 'deposit', NULL, NULL, NULL, NULL),
(143, 22, '', '', '', '', '', 339.50, '', 'DATA1742336765469', '', 'bank_transfer', '2025-03-18 22:26:05', 'data', NULL, NULL, NULL, NULL),
(144, 22, '', '', '', '', '', 339.50, '', 'DATA1742336788879', '', 'bank_transfer', '2025-03-18 22:26:28', 'data', NULL, NULL, NULL, NULL),
(145, 22, '', '', '', '', '', 200.00, '', 'TXN_1742368625', '', 'bank_transfer', '2025-03-19 07:17:09', 'airtime', NULL, NULL, NULL, NULL),
(146, 22, '', '', '', '', '', 339.50, '', 'DATA1742378402428', '', 'bank_transfer', '2025-03-19 10:00:02', 'data', NULL, NULL, NULL, NULL),
(147, 22, '', '', '', '', '', 500.00, '', 'TXN_1742425129_9654', '', 'bank_transfer', '2025-03-19 23:00:25', 'deposit', NULL, NULL, NULL, NULL),
(148, 22, '', '', '', '', '', 200.00, '', 'TXN_1742491216', '', 'bank_transfer', '2025-03-20 17:20:19', 'airtime', NULL, NULL, NULL, NULL),
(149, 22, '', '0060192740', 'Access Bank', '044', 'EMMANUEL  AKIGHA', 200.00, 'Tf', 'BU8AZR', '', 'bank_transfer', '2025-03-20 17:22:40', 'Withdrawal', NULL, NULL, NULL, NULL),
(150, 22, '', '', '', '', '', 100.00, '', 'TXN_1742558969', '', 'bank_transfer', '2025-03-21 12:09:32', 'airtime', NULL, NULL, NULL, NULL),
(151, 22, '', '', '', '', '', 100.00, '', 'TXN_1742588233', '', 'bank_transfer', '2025-03-21 20:17:17', 'airtime', NULL, NULL, NULL, NULL),
(152, 22, '', '', '', '', '', 100.00, '', 'TXN_1742588302', '', 'bank_transfer', '2025-03-21 20:18:26', 'airtime', NULL, NULL, NULL, NULL),
(153, 22, '', '', '', '', '', 339.50, '', 'DATA1742588352820', '', 'bank_transfer', '2025-03-21 20:19:12', 'data', NULL, NULL, NULL, NULL),
(154, 22, '', '', '', '', '', 500.00, '', 'TXN_1742589422_4345', 'pending', 'bank_transfer', '2025-03-21 20:38:22', 'deposit', NULL, NULL, NULL, NULL),
(155, 22, '', '', '', '', '', 339.50, '', 'DATA1742591479506', '', 'bank_transfer', '2025-03-21 21:11:19', 'data', NULL, NULL, NULL, NULL),
(156, 22, '', '', '', '', '', 339.50, '', 'DATA1742593854929', '', 'bank_transfer', '2025-03-21 21:50:54', 'data', NULL, NULL, NULL, NULL),
(157, 22, '', '', '', '', '', 339.50, '', 'DATA1742594658331', '', 'bank_transfer', '2025-03-21 22:04:18', 'data', '01', '350.01', '07076690090', NULL),
(158, 22, '', '', '', '', '', 19400.00, '', 'DATA1742594698374', 'failed', 'bank_transfer', '2025-03-21 22:04:58', 'data', '01', '20000.01', '07076690090', NULL),
(159, 22, '', '', '', '', '', 4365.00, '', 'DATA1742594935868', '', 'bank_transfer', '2025-03-21 22:08:55', 'data', '04', '4500.01', '08023242526', NULL),
(160, 22, '', '', '', '', '', 10670.00, '', 'DATA1742599162515', '', 'bank_transfer', '2025-03-21 23:19:22', 'data', '03', '11000.01', '08098987667', NULL),
(161, 22, '', '', '', '', '', 19400.00, '', 'DATA1742599768602', '', 'bank_transfer', '2025-03-21 23:29:28', 'data', '01', '20000.01', '09136372839', '75GB Monthly Plan - 30 days @ N19,400.00'),
(162, 22, '', '', '', '', '', 6305.00, '', 'DATA1742600809461', '', 'bank_transfer', '2025-03-21 23:46:49', 'data', '03', '6500.01', '08025369521', '15GB+25mins Monthly Plan - 30 days @ N6,305.00'),
(163, 22, '', '', '', '', '', 100.00, '', 'TXN_1742606868', '', 'bank_transfer', '2025-03-22 01:27:52', 'airtime', NULL, NULL, NULL, NULL),
(164, 22, '', '', '', '', '', 100.00, '', 'TXN_1742611202', '', 'bank_transfer', '2025-03-22 02:40:05', 'airtime', NULL, NULL, NULL, NULL),
(165, 22, '', '', '', '', '', 100.00, '', 'TXN_1742611430', '', 'bank_transfer', '2025-03-22 02:43:54', 'airtime', NULL, NULL, NULL, NULL),
(166, 22, '', '', '', '', '', 100.00, '', 'TXN_1742611473', '', 'bank_transfer', '2025-03-22 02:44:36', 'airtime', NULL, NULL, NULL, NULL),
(167, 22, '', '', '', '', '', 100.00, '', 'TXN_1742612031', '', 'bank_transfer', '2025-03-22 02:53:55', 'airtime', NULL, NULL, NULL, NULL),
(168, 22, '', '', '', '', '', 100.00, '', 'TXN_1742612921', '', 'bank_transfer', '2025-03-22 03:08:45', 'airtime', NULL, NULL, NULL, NULL),
(169, 22, '', '', '', '', '', 100.00, '', 'TXN_1742623824', '', 'bank_transfer', '2025-03-22 06:10:27', 'airtime', NULL, NULL, NULL, NULL),
(170, 22, '', '', '', '', '', 150.00, '', 'TXN_1742623940', '', 'bank_transfer', '2025-03-22 06:12:23', 'airtime', NULL, NULL, NULL, NULL),
(171, 22, '', '', '', '', '', 100.00, '', 'TXN_1742656872', '', 'bank_transfer', '2025-03-22 15:21:17', 'airtime', NULL, NULL, NULL, NULL),
(172, 22, '', '', '', '', '', 100.00, '', 'TXN_1742699439', '', 'bank_transfer', '2025-03-23 03:10:43', 'airtime', NULL, NULL, NULL, NULL),
(173, 74, '', '', '', '', '', 100.00, '', 'TXN_1742816379', '', 'bank_transfer', '2025-03-24 11:39:43', 'airtime', NULL, NULL, NULL, NULL),
(174, 74, '', '', '', '', '', 500.00, '', 'TXN_1742817000_6347', 'pending', 'bank_transfer', '2025-03-24 11:51:54', 'deposit', NULL, NULL, NULL, NULL),
(175, 75, '', '', '', '', '', 100.00, '', 'TXN_1742819192_6086', 'pending', 'bank_transfer', '2025-03-24 12:27:53', 'deposit', NULL, NULL, NULL, NULL),
(176, 75, '', '', '', '', '', 100.00, '', 'TXN_1742820767', '', 'bank_transfer', '2025-03-24 12:52:52', 'airtime', NULL, NULL, NULL, NULL),
(177, 74, '', '', '', '', '', 339.50, '', 'DATA1742822699338', '', 'bank_transfer', '2025-03-24 13:24:59', 'data', '01', '350.01', '07076690090', '1GB Daily Plan + 3mins - 1 day @ N339.50'),
(178, 74, '', '', '', '', '', 339.50, '', 'DATA1742867565903', '', 'bank_transfer', '2025-03-25 01:52:45', 'data', '01', '350.01', '07076690090', '1GB Daily Plan + 3mins - 1 day @ N339.50'),
(179, 74, '', '', '', '', '', 100.00, '', 'TXN_1742927916', '', 'bank_transfer', '2025-03-25 18:38:40', 'airtime', NULL, NULL, NULL, NULL),
(180, 74, '', '', '', '', '', 339.50, '', 'DATA1742927960766', '', 'bank_transfer', '2025-03-25 18:39:20', 'data', '01', '350.01', '09135651058', '1GB Daily Plan + 3mins - 1 day @ N339.50'),
(181, 74, '', '2210875133', 'Zenith bank PLC', '057', 'DESTINY   AYE', 100.00, 'tredg', 'LCTHOR', '', 'bank_transfer', '2025-03-25 18:41:35', 'Withdrawal', NULL, NULL, NULL, NULL),
(182, 74, '', '', '', '', '', 500.00, '', 'TXN_1742928150_7330', 'pending', 'bank_transfer', '2025-03-25 18:45:14', 'deposit', NULL, NULL, NULL, NULL),
(183, 74, '', '0023209663', 'Access Bank', '044', 'DIMIE PRINCE ADDY', 200.00, 'Test', '6GQB15', '', 'bank_transfer', '2025-03-25 20:08:32', 'Withdrawal', NULL, NULL, NULL, NULL),
(184, 74, '', '', '', '', '', 100.00, '', 'TXN_1742933353', '', 'bank_transfer', '2025-03-25 20:09:17', 'airtime', NULL, NULL, NULL, NULL),
(185, 74, '', '2032087016', 'First City Monument Bank', '214', 'NWOGU THEO DESMOND', 100.00, 'Test', 'SC2VPY', '', 'bank_transfer', '2025-03-25 21:36:36', 'Withdrawal', NULL, NULL, NULL, NULL),
(186, 1, '', '1100723415', '9 Payment Service Bank', '120001', 'THEO NWOGU', 100.00, 'Rent', 'SJMI38', '', 'bank_transfer', '2025-06-02 04:49:40', 'Withdrawal', NULL, NULL, NULL, NULL),
(187, 1, '', '1100723415', '9 Payment Service Bank', '120001', 'THEO NWOGU', 8000.00, 'Services', 'SEJF97', '', 'bank_transfer', '2025-06-02 04:54:23', 'Withdrawal', NULL, NULL, NULL, NULL),
(188, 1, '', '', '', '', '', 100.00, '', 'TXN_1748841061_4790', 'pending', 'bank_transfer', '2025-06-02 05:11:56', 'deposit', NULL, NULL, NULL, NULL),
(189, 1, '', '1100723415', 'Unknown Bank', '120001', 'Unknown', 8000.00, 'Services', 'J1NCUZ', 'failed', 'bank_transfer', '2025-06-02 05:33:33', 'Withdrawal', NULL, NULL, NULL, NULL),
(190, 1, '', '1100723415', '9 Payment Service Bank', '120001', 'THEO NWOGU', 8000.00, 'Services', 'NMU1S3', '', 'bank_transfer', '2025-06-02 05:37:55', 'Withdrawal', NULL, NULL, NULL, NULL),
(191, 1, '', '1100723415', '9 Payment Service Bank', '120001', 'THEO NWOGU', 100.00, 'Servicesv', 'O24HBW', '', 'bank_transfer', '2025-06-02 12:25:49', 'Withdrawal', NULL, NULL, NULL, NULL),
(192, 27, '', '', '', '', '', 100.00, '', 'TXN_1748868738_6326', 'pending', 'bank_transfer', '2025-06-02 12:53:38', 'deposit', NULL, NULL, NULL, NULL),
(193, 27, '', '', '', '', '', 10.00, '', 'TXN_1748869010_3560', 'pending', 'bank_transfer', '2025-06-02 12:57:41', 'deposit', NULL, NULL, NULL, NULL),
(194, 1, '', '', '', '', '', 20.00, '', 'TXN_1748948442', '', 'bank_transfer', '2025-06-03 11:00:45', 'airtime', 'MTN', NULL, '07076690090', NULL),
(195, 1, '', '1100723415', '9 Payment Service Bank', '120001', 'THEO NWOGU', 100.00, 'Vvv', '28FRDK', '', 'bank_transfer', '2025-06-03 12:01:49', 'Withdrawal', NULL, NULL, NULL, NULL),
(196, 1, '', '1100723415', '9 Payment Service Bank', '120001', 'THEO NWOGU', 100.00, 'Vvvv', 'TPCU0V', '', 'bank_transfer', '2025-06-03 12:02:19', 'Withdrawal', NULL, NULL, NULL, NULL),
(197, 1, '', '2032087016', 'First City Monument Bank', '214', 'NWOGU THEO DESMOND', 50.00, 'ere', 'GZDQUW', 'failed', 'bank_transfer', '2025-06-03 13:45:26', 'Withdrawal', NULL, NULL, NULL, NULL),
(198, 1, '', '1100723415', '9 Payment Service Bank', '120001', 'THEO NWOGU', 101.00, 'Test now', 'T7KZR4', 'success', 'bank_transfer', '2025-06-03 13:54:10', 'Withdrawal', NULL, NULL, NULL, NULL),
(199, 1, '', '1100723415', '9 Payment Service Bank', '120001', 'THEO NWOGU', 100.00, 'Test now', 'R0JSCN', 'success', 'bank_transfer', '2025-06-03 13:57:31', 'Withdrawal', NULL, NULL, NULL, NULL),
(200, 1, '', '1100723415', '9 Payment Service Bank', '120001', 'THEO NWOGU', 106.00, 'Test now', 'M267OG', 'success', 'bank_transfer', '2025-06-03 14:03:35', 'Withdrawal', NULL, NULL, NULL, NULL),
(201, 1, '', '1100723415', '9 Payment Service Bank', '120001', 'THEO NWOGU', 100.00, 'Test now', 'G294AR', 'failed', 'bank_transfer', '2025-06-04 17:13:01', 'Withdrawal', NULL, NULL, NULL, NULL),
(202, 1, '', '', '', '', '', 500.00, '', 'TXN_1749066184_5878', 'pending', 'bank_transfer', '2025-06-04 19:43:47', 'deposit', NULL, NULL, NULL, NULL),
(203, 1, '', '1100723415', '9 Payment Service Bank', '120001', 'THEO NWOGU', 100.00, 'Test now', 'HPNZER', 'failed', 'bank_transfer', '2025-06-04 20:20:57', 'Withdrawal', NULL, NULL, NULL, NULL),
(204, 1, '', '2032087016', 'First City Monument Bank', '214', 'NWOGU THEO DESMOND', 100.00, 'kfjn', 'RS4FDP', 'failed', 'bank_transfer', '2025-06-05 18:37:08', 'Withdrawal', NULL, NULL, NULL, NULL),
(205, 1, '', '1100723415', '9 Payment Service Bank', '120001', 'THEO NWOGU', 500.00, 'Redd', 'TYSR47', 'failed', 'bank_transfer', '2025-06-06 00:34:06', 'Withdrawal', NULL, NULL, NULL, NULL),
(206, 1, '', '1100723415', '9 Payment Service Bank', '120001', 'THEO NWOGU', 100.00, 'Redd', '694A3S', 'failed', 'bank_transfer', '2025-06-06 00:42:17', 'Withdrawal', NULL, NULL, NULL, NULL),
(207, 1, '', '1100723415', '9 Payment Service Bank', '120001', 'THEO NWOGU', 100.00, '', 'QWBSDP', 'success', 'bank_transfer', '2025-06-06 00:43:22', 'Withdrawal', NULL, NULL, NULL, NULL),
(208, 1, '', '1100723415', '9 Payment Service Bank', '120001', 'THEO NWOGU', 100.00, 'Redd', 'CV1425', 'failed', 'bank_transfer', '2025-06-06 00:52:00', 'Withdrawal', NULL, NULL, NULL, NULL),
(209, 1, '', '1100723415', '9 Payment Service Bank', '120001', 'THEO NWOGU', 104.00, 'Redd', 'ENWV3A', 'failed', 'bank_transfer', '2025-06-06 01:13:31', 'Withdrawal', NULL, NULL, NULL, NULL),
(210, 1, '', '1100723415', '9 Payment Service Bank', '120001', 'THEO NWOGU', 104.00, 'Redd', 'L14VY3', 'failed', 'bank_transfer', '2025-06-06 01:26:24', 'Withdrawal', NULL, NULL, NULL, NULL),
(211, 1, '', '1100723415', '9 Payment Service Bank', '120001', 'THEO NWOGU', 104.00, 'Redd', 'FZRI6U', 'failed', 'bank_transfer', '2025-06-06 01:28:13', 'Withdrawal', NULL, NULL, NULL, NULL),
(212, 1, '', '1100723415', '9 Payment Service Bank', '120001', 'THEO NWOGU', 104.00, 'Redd', 'IY67BC', 'failed', 'bank_transfer', '2025-06-06 11:56:58', 'Withdrawal', NULL, NULL, NULL, NULL),
(213, 50, '', '', '', '', '', 200.00, '', 'TXN_1749280717_5317', 'pending', 'bank_transfer', '2025-06-07 07:20:45', 'deposit', NULL, NULL, NULL, NULL),
(214, 50, '', '1100723415', '9 Payment Service Bank', '120001', 'THEO NWOGU', 104.00, 'Redd', 'MKY9VT', 'failed', 'bank_transfer', '2025-06-07 07:22:04', 'Withdrawal', NULL, NULL, NULL, NULL),
(215, 50, '', '2032087016', 'First City Monument Bank', '214', 'NWOGU THEO DESMOND', 100.00, 'kfjn', 'IXY4D1', 'failed', 'bank_transfer', '2025-06-07 23:32:38', 'Withdrawal', NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `transaction_disputes`
--

CREATE TABLE `transaction_disputes` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `reference` varchar(100) NOT NULL,
  `reason` text NOT NULL,
  `session_id` varchar(255) DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `status` enum('pending','resolved','rejected') DEFAULT 'pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `transaction_disputes`
--

INSERT INTO `transaction_disputes` (`id`, `user_id`, `reference`, `reason`, `session_id`, `created_at`, `status`) VALUES
(9, 81, 'WD_684ecc2b1f248', 'wrong transfer', 'SID_684ECC2FE5189', '2025-06-15 13:35:56', 'resolved'),
(10, 75, 'WD_684ecdeff2db9', 'No funds received', 'SID_684ECDF36A55A', '2025-06-15 13:43:31', 'resolved'),
(11, 75, 'WD_68aa2fcfb9a8d', 'Not seen', 'SID_68AA2FD100805', '2025-08-23 21:17:36', 'pending'),
(12, 75, 'WD_68aac1036f11d', 'The receivers have not seen', 'SID_68AAC104B1DCD', '2025-08-24 07:37:11', 'pending'),
(13, 85, 'WD_68ab4e445f550', 'The person didn&#039;t see the money', 'SID_68AB4E461A222', '2025-08-24 17:41:34', 'pending'),
(14, 75, 'WD_68acc706a34a5', 'The owner never see am', 'SID_68ACC70849A42', '2025-08-25 20:28:42', 'pending'),
(15, 75, 'WD_68cc22d2e9051', 'Did not receive', 'SID_68CC22D4A4ED9', '2025-09-18 15:19:29', 'pending'),
(16, 75, 'WD_68db33144b524', 'The recipient hasn&#039;t received it', 'SID_68DB331602EB6', '2025-09-30 01:32:46', 'pending');

-- --------------------------------------------------------

--
-- Table structure for table `transaction_fees`
--

CREATE TABLE `transaction_fees` (
  `id` int(11) NOT NULL,
  `service_name` varchar(255) NOT NULL,
  `fee_percentage` decimal(5,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `transaction_rewards`
--

CREATE TABLE `transaction_rewards` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `transaction_id` varchar(50) NOT NULL,
  `points_awarded` int(11) NOT NULL DEFAULT 5,
  `reward_time` timestamp NULL DEFAULT current_timestamp(),
  `next_available` timestamp NULL DEFAULT (current_timestamp() + interval 12 hour)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `transaction_rewards`
--

INSERT INTO `transaction_rewards` (`id`, `user_id`, `transaction_id`, `points_awarded`, `reward_time`, `next_available`) VALUES
(1, 1, 'TXN12345', 5, '2025-03-11 12:53:27', '2025-03-12 00:53:27');

-- --------------------------------------------------------

--
-- Table structure for table `tv_packages`
--

CREATE TABLE `tv_packages` (
  `id` int(11) NOT NULL,
  `provider_id` int(11) NOT NULL,
  `code` varchar(100) NOT NULL,
  `name` varchar(255) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `duration` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `tv_packages`
--

INSERT INTO `tv_packages` (`id`, `provider_id`, `code`, `name`, `price`, `duration`) VALUES
(1, 1, 'dstv-padi', 'DStv Padi', 4400.00, '1 Month'),
(2, 1, 'dstv-yanga', 'DStv Yanga', 6000.00, '1 Month'),
(3, 1, 'dstv-confam', 'Dstv Confam', 11000.00, '1 Month'),
(4, 1, 'dstv79', 'DStv Compact', 19000.00, '1 Month'),
(5, 1, 'dstv3', 'DStv Premium', 44500.00, '1 Month'),
(6, 1, 'dstv7', 'DStv Compact Plus', 30000.00, '1 Month'),
(7, 1, 'dstv9', 'DStv Premium-French', 69000.00, '1 Month'),
(8, 1, 'dstv10', 'DStv Premium-Asia', 50500.00, '1 Month'),
(9, 1, 'confam-extra', 'DStv Confam + ExtraView', 17000.00, '1 Month'),
(10, 1, 'yanga-extra', 'DStv Yanga + ExtraView', 12000.00, '1 Month'),
(11, 1, 'padi-extra', 'DStv Padi + ExtraView', 10400.00, '1 Month'),
(12, 1, 'dstv30', 'DStv Compact + Extra View', 25000.00, '1 Month'),
(13, 1, 'com-frenchtouch', 'DStv Compact + French Touch', 26000.00, '1 Month'),
(14, 1, 'dstv33', 'DStv Premium + Extra View', 50500.00, '1 Month'),
(15, 1, 'com-frenchtouch-extra', 'DStv Compact + French Touch + ExtraView', 32000.00, '1 Month'),
(16, 1, 'dstv43', 'DStv Compact Plus + French Plus', 54500.00, '1 Month'),
(17, 1, 'complus-frenchtouch', 'DStv Compact Plus + French Touch', 37000.00, '1 Month'),
(18, 1, 'dstv45', 'DStv Compact Plus + Extra View', 36000.00, '1 Month'),
(19, 1, 'complus-french-extraview', 'DStv Compact Plus + FrenchPlus + Extra View', 60500.00, '1 Month'),
(20, 1, 'dstv47', 'DStv Compact + French Plus', 43500.00, '1 Month'),
(21, 1, 'dstv62', 'DStv Premium + French + Extra View', 75000.00, '1 Month'),
(22, 1, 'frenchplus-addon', 'DStv French Plus Add-on', 24500.00, '1 Month'),
(23, 1, 'dstv-greatwall', 'DStv Great Wall Standalone Bouquet', 3800.00, '1 Month'),
(24, 1, 'frenchtouch-addon', 'DStv French Touch Add-on', 7000.00, '1 Month'),
(25, 1, 'extraview-access', 'ExtraView Access', 6000.00, '1 Month'),
(26, 1, 'dstv-yanga-showmax', 'DStv Yanga + Showmax', 7750.00, '1 Month'),
(27, 1, 'dstv-greatwall-showmax', 'DStv Great Wall Standalone + Showmax', 7300.00, '1 Month'),
(28, 1, 'dstv-compact-plus-showmax', 'DStv Compact Plus + Showmax', 31750.00, '1 Month'),
(29, 1, 'dstv-confam-showmax', 'Dstv Confam + Showmax', 12750.00, '1 Month'),
(30, 1, 'dstv-compact-showmax', 'DStv Compact + Showmax', 20750.00, '1 Month'),
(31, 1, 'dstv-padi-showmax', 'DStv Padi + Showmax', 7900.00, '1 Month'),
(32, 1, 'dstv-asia-showmax', 'DStv Asia + Showmax', 18400.00, '1 Month'),
(33, 1, 'dstv-premium-french-showmax', 'DStv Premium + French + Showmax', 69000.00, '1 Month'),
(34, 1, 'dstv-premium-showmax', 'DStv Premium + Showmax', 44500.00, '1 Month'),
(35, 1, 'dstv-indian', 'DStv Indian', 14900.00, '1 Month'),
(36, 1, 'dstv-premium-indian', 'DStv Premium East Africa and Indian', 16530.00, '1 Month'),
(37, 1, 'dstv-fta-plus', 'DStv FTA Plus', 1600.00, '1 Month'),
(38, 1, 'dstv-premium-hd', 'DStv PREMIUM HD', 39000.00, '1 Month'),
(39, 1, 'dstv-access-1', 'DStv Access', 2000.00, '1 Month'),
(40, 1, 'dstv-family-1', 'DStv Family', 0.00, '1 Month'),
(41, 1, 'dstv-indian-add-on', 'DStv India Add-on', 14900.00, '1 Month'),
(42, 1, 'dstv-mobile-1', 'DSTV MOBILE', 790.00, '1 Month'),
(43, 1, 'dstv-movie-bundle-add-on', 'DStv Movie Bundle Add-on', 3500.00, '1 Month'),
(44, 1, 'dstv-pvr-access', 'DStv PVR Access Service', 4000.00, '1 Month'),
(45, 1, 'dstv-premium-wafr-showmax', 'DStv Premium W/Afr + Showmax', 50500.00, '1 Month'),
(46, 2, 'gotv-max', 'GOtv Max', 8500.00, '1 Month'),
(47, 2, 'gotv-jolli', 'GOtv Jolli', 5800.00, '1 Month'),
(48, 2, 'gotv-jinja', 'GOtv Jinja', 3900.00, '1 Month'),
(49, 2, 'gotv-smallie', 'GOtv Smallie - monthly', 1900.00, '1 Month'),
(50, 2, 'gotv-smallie-3months', 'GOtv Smallie - quarterly', 5100.00, '3 Months'),
(51, 2, 'gotv-smallie-1year', 'GOtv Smallie - yearly', 15000.00, '1 Year'),
(52, 2, 'gotv-supa', 'GOtv Supa - monthly', 11400.00, '1 Month'),
(53, 2, 'gotv-supa-plus', 'GOtv Supa Plus - monthly', 16800.00, '1 Month'),
(54, 3, 'nova', 'Nova (Dish)', 1900.00, '1 Month'),
(55, 3, 'basic', 'Basic (Antenna)', 3700.00, '1 Month'),
(56, 3, 'smart', 'Basic (Dish)', 4700.00, '1 Month'),
(57, 3, 'classic', 'Classic (Antenna)', 5500.00, '1 Month'),
(58, 3, 'super', 'Super (Dish)', 9000.00, '1 Month'),
(59, 3, 'nova-weekly', 'Nova (Antenna)', 600.00, '1 Week'),
(60, 3, 'basic-weekly', 'Basic (Antenna)', 1250.00, '1 Week'),
(61, 3, 'smart-weekly', 'Basic (Dish)', 1550.00, '1 Week'),
(62, 3, 'classic-weekly', 'Classic (Antenna)', 1900.00, '1 Week'),
(63, 3, 'super-weekly', 'Super (Dish)', 3000.00, '1 Week'),
(64, 3, 'uni-1', 'Chinese (Dish)', 19000.00, '1 Month'),
(65, 3, 'uni-2', 'Nova (Antenna)', 1900.00, '1 Month'),
(66, 3, 'special-weekly', 'Classic (Dish)', 2300.00, '1 Week'),
(67, 3, 'special-monthly', 'Classic (Dish)', 6800.00, '1 Month'),
(68, 3, 'nova-dish-weekly', 'Nova (Dish)', 650.00, '1 Week'),
(69, 3, 'super-antenna-weekly', 'Super (Antenna)', 3000.00, '1 Week'),
(70, 3, 'super-antenna-monthly', 'Super (Antenna)', 8800.00, '1 Month'),
(71, 3, 'global-monthly-dish', 'Global (Dish)', 19000.00, '1 Month'),
(72, 3, 'global-weekly-dish', 'Global (Dish)', 6500.00, '1 Week');

-- --------------------------------------------------------

--
-- Table structure for table `tv_providers`
--

CREATE TABLE `tv_providers` (
  `id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `code` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `tv_providers`
--

INSERT INTO `tv_providers` (`id`, `name`, `code`) VALUES
(1, 'DStv', 'dstv'),
(2, 'GOtv', 'gotv'),
(3, 'Startimes', 'startimes');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(10) UNSIGNED NOT NULL,
  `phone_number` varchar(15) NOT NULL,
  `password` varchar(255) NOT NULL,
  `pin` varchar(255) NOT NULL,
  `full_name` varchar(255) NOT NULL,
  `address` varchar(500) NOT NULL,
  `email` varchar(255) NOT NULL,
  `balance` decimal(10,2) DEFAULT 0.00,
  `withdraw_bank_name` varchar(100) DEFAULT NULL,
  `withdraw_bank_code` varchar(50) DEFAULT NULL,
  `withdraw_account_number` varchar(20) DEFAULT NULL,
  `earnings` decimal(10,2) DEFAULT 0.00,
  `bonus` decimal(10,2) DEFAULT 0.00,
  `expenses` decimal(10,2) DEFAULT 0.00,
  `deposit` decimal(10,2) DEFAULT 0.00,
  `withdrawal` decimal(10,2) DEFAULT 0.00,
  `investment` decimal(10,2) DEFAULT 0.00,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `avatar_url` varchar(255) DEFAULT 'default-avatar.png',
  `otp_code` varchar(10) DEFAULT NULL,
  `otp_verified` tinyint(1) DEFAULT 0,
  `points` int(11) DEFAULT 0,
  `nick_name` varchar(50) DEFAULT 'User',
  `gender` enum('Male','Female','Other') DEFAULT NULL,
  `dob` date DEFAULT NULL,
  `points_used` int(11) DEFAULT 0,
  `last_profile_update` timestamp NULL DEFAULT current_timestamp(),
  `spin_count` int(11) DEFAULT 0,
  `last_spin_date` date DEFAULT NULL,
  `current_progress` decimal(10,2) DEFAULT 0.00,
  `referred_by` int(11) DEFAULT NULL,
  `referral_code` varchar(100) NOT NULL,
  `status` enum('verified','pending') DEFAULT 'pending',
  `fcm_token` text DEFAULT NULL,
  `quiz_fail_count` int(11) DEFAULT 0,
  `quiz_lockout_until` datetime DEFAULT NULL,
  `country` varchar(100) DEFAULT NULL,
  `state` varchar(100) DEFAULT NULL,
  `birthday` date DEFAULT NULL,
  `avatar` varchar(255) DEFAULT NULL,
  `paystack_customer_code` varchar(100) DEFAULT NULL,
  `virtual_account_number` varchar(20) DEFAULT NULL,
  `bank_name` varchar(100) DEFAULT NULL,
  `account_name` varchar(255) DEFAULT NULL,
  `google_2fa_secret` varchar(255) DEFAULT NULL,
  `kyc_attempts` int(11) DEFAULT 0,
  `kyc_last_attempt` datetime DEFAULT NULL,
  `failed_pin_attempts` int(11) DEFAULT 0,
  `last_pin_attempt` datetime DEFAULT NULL,
  `failed_face_attempts` int(11) DEFAULT 0,
  `withdrawal_locked` tinyint(1) DEFAULT 0,
  `requires_face_verification` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `phone_number`, `password`, `pin`, `full_name`, `address`, `email`, `balance`, `withdraw_bank_name`, `withdraw_bank_code`, `withdraw_account_number`, `earnings`, `bonus`, `expenses`, `deposit`, `withdrawal`, `investment`, `created_at`, `avatar_url`, `otp_code`, `otp_verified`, `points`, `nick_name`, `gender`, `dob`, `points_used`, `last_profile_update`, `spin_count`, `last_spin_date`, `current_progress`, `referred_by`, `referral_code`, `status`, `fcm_token`, `quiz_fail_count`, `quiz_lockout_until`, `country`, `state`, `birthday`, `avatar`, `paystack_customer_code`, `virtual_account_number`, `bank_name`, `account_name`, `google_2fa_secret`, `kyc_attempts`, `kyc_last_attempt`, `failed_pin_attempts`, `last_pin_attempt`, `failed_face_attempts`, `withdrawal_locked`, `requires_face_verification`) VALUES
(5, '26777761333', '$2y$10$4J3xWCB5Yw0f9LYbrlj6vO5O5CJZvCNm1YU3c9gdVp0SYTlSyk3Pq', '6831', 'Marks Masabae', '', 'marksmasabae@gmail.com', 0.00, NULL, NULL, NULL, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, '2025-05-07 11:24:42', 'default-avatar.png', NULL, 1, 10100, NULL, NULL, NULL, 0, '2025-05-07 11:24:42', 1, '2025-05-12', 0.00, NULL, 'ref_681b42fae60c39.71952003', 'pending', NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, 0, NULL, 0, 0, 0),
(64, '+2348140995525', '$2y$10$F55/WBCdqXb8FTWb7BbHWOULEhitEpVANy0LN47hrmCN/B5CVRkSG', '1234', 'Anthony Nwogu', '', 'me@gmail.com', 1000.00, NULL, NULL, NULL, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, '2025-06-07 18:07:05', 'uploads/avatars/avatar_1749319758.jpg', NULL, 1, 5, 'Montana ', 'Male', NULL, 0, '2025-06-07 18:07:05', 0, NULL, 0.00, NULL, 'ref_68447fc9da91b0.88037817', 'pending', NULL, 0, NULL, NULL, NULL, NULL, NULL, 'CUS_f6q1xuuwy7rjva5', '9324230641', 'Wema Bank', 'DIGITALSOLUTI/NWOGU ANTHONY', NULL, 0, NULL, 0, NULL, 0, 0, 0),
(66, '+2347026624536', '$2y$10$Ezh/ILcoG998CfMdpr/QQeevoPi897slu0VBypm3eqRXwYWiZLNG6', '0770', 'Innocent akpos', '', 'akposinnocent71@gmail.com', 200.00, NULL, NULL, NULL, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, '2025-06-08 09:29:40', 'default-avatar.png', NULL, 1, 0, 'Federal', NULL, NULL, 0, '2025-06-08 09:29:40', 0, NULL, 0.00, NULL, 'ref_684558040ed8b1.37463915', 'pending', NULL, 0, NULL, NULL, NULL, NULL, NULL, 'CUS_a5ur4fpyum2lhon', '9324241803', 'Wema Bank', 'DIGITALSOLUTI/AKPOS INNOCENT', NULL, 0, NULL, 0, NULL, 0, 0, 0),
(68, '+2347039172233', '$2y$10$1z967L/Tp2xeUni4WOkm4e0OmnU70E49YVMrZZElBkO9II6CV9Guq', '1234', 'David Iduate ', '', 'davididuate11@gmail.com', 51.75, NULL, NULL, NULL, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, '2025-06-08 13:26:10', 'default-avatar.png', NULL, 1, 0, 'Iduate ', NULL, NULL, 0, '2025-06-08 13:26:10', 0, NULL, 0.00, NULL, 'ref_68458f72440db4.48878631', 'pending', NULL, 0, NULL, NULL, NULL, NULL, NULL, 'CUS_zz6sri8jeeclxm9', '9324249108', 'Wema Bank', 'DIGITALSOLUTI/IDUATE DAVID', NULL, 0, NULL, 0, NULL, 0, 0, 0),
(75, '+2348148622359', '$2y$10$u5HcoIYRv.8TP1cRP9zi5.GtD8r2qcNzGoDSA7McAc.Xvu9JUnadO', '1233', 'Theo Desmond N.', 'Old Factory Road', 'asam@gmail.com', 345273.39, NULL, NULL, NULL, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, '2025-06-09 12:00:27', 'uploads/avatars/avatar_1759196304.jpg', NULL, 1, 29, 'Investor Slim', 'Male', NULL, 26743, '2025-06-09 12:00:27', 2, '2025-09-30', 0.00, NULL, 'ref_6846ccdb3b0896.07279132', 'verified', NULL, 0, NULL, NULL, NULL, NULL, NULL, 'CUS_qvw9jfqdh9zd088', '9324273303', 'Wema Bank', 'DIGITALSOLUTI/THEO DESMOND NWOGU', NULL, 0, NULL, 0, '2025-09-18 14:11:54', 0, 0, 0),
(76, '+2348102557787', '$2y$10$GNmLVgqLP/iJ58CL5jn1dOUcu3qwS0yz57VE8A4Kl5U3t0Rv4hHxG', '1047', 'Agorowei Azana Augustine', '', 'A.Azana@protonmail.com', 365.50, NULL, NULL, NULL, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, '2025-06-09 17:42:00', 'default-avatar.png', NULL, 1, 0, 'Aza', NULL, NULL, 0, '2025-06-09 17:42:00', 0, NULL, 0.00, NULL, 'ref_68471ce8da9476.59344489', 'pending', NULL, 0, NULL, NULL, NULL, NULL, NULL, 'CUS_njsbu4rh43t6uby', '9324284596', 'Wema Bank', 'DIGITALSOLUTI/AZANA AUGUSTINE AGOROWEI', NULL, 0, NULL, 0, NULL, 0, 0, 0),
(85, '+2348144777061', '$2y$10$DOxEoykmEYRIYoqhp9DgRulJJs.ojxEX6zzGZseCs/pmdEpwWDsY.', '1234', 'Prince Ifeanyi', '', 'graceforemma@gmail.com', 99626142.99, NULL, NULL, NULL, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, '2025-07-30 09:33:28', 'https://swiftaffiliates.cloud/app/nin_uploads/user_85_1756029752.jpg', NULL, 1, 2, 'Commander', NULL, NULL, 5, '2025-07-30 09:33:28', 2, '2025-07-30', 0.00, NULL, 'ref_6889e6e8183f28.34242651', 'verified', NULL, 0, NULL, NULL, NULL, NULL, NULL, 'CUS_3s89myanibgsnrl', '9325844988', 'Wema Bank', 'DIGITALSOLUTI/Prince Ifeanyi', NULL, 0, NULL, 0, '2025-08-24 10:50:07', 0, 0, 0),
(89, '+2347076690090', '$2y$10$IfKFe7r1tOb8vWXfwIeJSui6A.GBtEkLl7Y3pAKxEVHFMmDhaUtmq', '1234', 'John Wilson', '', 'hubclink@gmail.com', 3.50, NULL, NULL, NULL, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, '2025-10-09 13:56:51', 'default-avatar.png', NULL, 1, 0, 'Lota', NULL, NULL, 0, '2025-10-09 13:56:51', 0, NULL, 0.00, NULL, 'ref_68e7bf23dc7017.88136334', 'pending', NULL, 0, NULL, NULL, NULL, NULL, NULL, 'CUS_jme4y37hh4s3sqw', '9326484211', 'Wema Bank', 'DIGITALSOLUTI/PETERS DESMOND', NULL, 0, NULL, 0, NULL, 0, 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `user_bank_accounts`
--

CREATE TABLE `user_bank_accounts` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `bank_name` varchar(100) NOT NULL,
  `bank_code` varchar(20) NOT NULL,
  `account_number` varchar(20) NOT NULL,
  `account_name` varchar(150) NOT NULL,
  `verified` tinyint(1) DEFAULT 1,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user_contracts`
--

CREATE TABLE `user_contracts` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `contract_id` int(11) NOT NULL,
  `purchased_amount` decimal(10,2) NOT NULL,
  `profit` decimal(10,2) NOT NULL,
  `start_date` timestamp NULL DEFAULT current_timestamp(),
  `end_date` timestamp NULL DEFAULT NULL,
  `status` enum('active','inactive','completed') DEFAULT 'active',
  `purchase_date` timestamp NULL DEFAULT current_timestamp(),
  `transaction_ref` varchar(10) DEFAULT NULL,
  `daily_earnings` decimal(10,2) DEFAULT 0.00,
  `days_paid` int(11) DEFAULT 0,
  `last_paid_date` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `user_contracts`
--

INSERT INTO `user_contracts` (`id`, `user_id`, `contract_id`, `purchased_amount`, `profit`, `start_date`, `end_date`, `status`, `purchase_date`, `transaction_ref`, `daily_earnings`, `days_paid`, `last_paid_date`) VALUES
(21, 1, 37, 500.00, 15.00, '2025-05-07 22:03:58', '2025-05-14 22:03:58', 'active', '2025-05-07 22:03:58', '47JXQ7', 0.00, 2, '2025-05-12'),
(22, 1, 37, 12000.00, 15.00, '2025-05-12 00:18:31', '2025-05-19 00:18:31', 'active', '2025-05-12 00:18:31', '181JM7', 0.00, 1, '2025-05-12'),
(23, 1, 37, 20000.00, 15.00, '2025-05-12 00:23:40', '2025-05-19 00:23:40', 'active', '2025-05-12 00:23:40', '32MIFE', 0.00, 1, '2025-05-12'),
(24, 1, 39, 500000.00, 10.00, '2025-05-12 00:24:30', '2025-05-15 00:24:30', 'active', '2025-05-12 00:24:30', '5XTR7S', 0.00, 1, '2025-05-12'),
(25, 1, 40, 50000.00, 25.00, '2025-05-12 00:25:19', '2025-05-25 00:25:19', 'active', '2025-05-12 00:25:19', 'U9TTYF', 0.00, 1, '2025-05-12'),
(26, 1, 40, 25000.00, 25.00, '2025-05-12 00:26:34', '2025-05-25 00:26:34', 'active', '2025-05-12 00:26:34', 'ODG5KV', 0.00, 1, '2025-05-12'),
(27, 1, 40, 200000.00, 25.00, '2025-05-22 11:29:13', '2025-06-04 11:29:13', 'active', '2025-05-22 11:29:13', 'EFQ0UZ', 3846.15, 0, NULL),
(28, 75, 44, 30000.00, 20.00, '2025-06-27 19:36:46', '2025-07-07 19:36:46', 'active', '2025-06-27 19:36:46', '6YSJUJ', 600.00, 0, NULL),
(29, 75, 40, 9000.00, 25.00, '2025-07-30 18:25:19', '2025-08-12 18:25:19', 'active', '2025-07-30 18:25:19', 'AXG1WL', 173.08, 0, NULL),
(30, 75, 40, 50000.00, 25.00, '2025-08-06 19:52:42', '2025-08-19 19:52:42', 'active', '2025-08-06 19:52:42', '2LNZC2', 961.54, 0, NULL),
(31, 75, 37, 688.00, 15.00, '2025-08-20 23:15:03', '2025-08-27 23:15:03', 'active', '2025-08-20 23:15:03', 'XW6Y6N', 14.74, 0, NULL),
(32, 75, 62, 30000.00, 8.00, '2025-08-22 16:24:51', '2025-08-30 16:24:51', 'active', '2025-08-22 16:24:51', 'M9M9Z1', 300.00, 0, NULL),
(33, 75, 41, 10000.00, 12.00, '2025-09-06 10:50:40', '2025-09-16 10:50:40', 'active', '2025-09-06 10:50:40', '8TFEZF', 120.00, 0, NULL),
(34, 75, 41, 1000.00, 12.00, '2025-10-02 11:41:08', '2025-10-12 11:41:08', 'active', '2025-10-02 11:41:08', 'F6ZRX5', 12.00, 0, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `user_earnings`
--

CREATE TABLE `user_earnings` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `amount` decimal(15,2) NOT NULL,
  `earned_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `user_earnings`
--

INSERT INTO `user_earnings` (`id`, `user_id`, `amount`, `earned_at`) VALUES
(1, 74, 5.00, '2025-04-29 11:01:37'),
(2, 74, 13.33, '2025-04-29 11:03:00'),
(3, 74, 13.33, '2025-04-29 11:03:04'),
(4, 74, 13.33, '2025-04-29 11:03:08'),
(5, 74, 13.33, '2025-04-29 11:10:15'),
(6, 74, 13.33, '2025-04-29 11:10:20'),
(7, 74, 5.00, '2025-04-29 11:10:23');

-- --------------------------------------------------------

--
-- Table structure for table `user_tasks`
--

CREATE TABLE `user_tasks` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `task_id` int(11) DEFAULT NULL,
  `completed_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user_withdrawals`
--

CREATE TABLE `user_withdrawals` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `reference` varchar(50) NOT NULL,
  `recipient_code` varchar(100) NOT NULL,
  `bank_name` varchar(100) NOT NULL,
  `account_number` varchar(20) NOT NULL,
  `account_name` varchar(100) DEFAULT NULL,
  `amount` decimal(15,2) NOT NULL,
  `status` enum('PENDING','FAILED','SUCCESS') DEFAULT 'PENDING',
  `response` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL CHECK (json_valid(`response`)),
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `session_id` varchar(40) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `user_withdrawals`
--

INSERT INTO `user_withdrawals` (`id`, `user_id`, `reference`, `recipient_code`, `bank_name`, `account_number`, `account_name`, `amount`, `status`, `response`, `created_at`, `session_id`) VALUES
(1, 84, 'WD_686f9ea643bba', 'RCP_q80q0jkt8wm1r85', 'OPay Digital Services Limited (OPay)', '8148622359', 'THEO DESMOND NWOGU', 6000.00, 'FAILED', '{\"status\":false,\"message\":\"Your balance is not enough to fulfil this request\",\"meta\":{\"nextStep\":\"Topup your Paystack Balance and try again.\"},\"type\":\"api_error\",\"code\":\"insufficient_balance\"}', '2025-07-10 11:06:16', NULL),
(2, 84, 'WD_68707ae2dc985', 'RCP_q80q0jkt8wm1r85', 'OPay Digital Services Limited (OPay)', '8148622359', 'THEO DESMOND NWOGU', 1000.00, 'SUCCESS', '{\"status\":true,\"message\":\"Transfer has been queued\",\"data\":{\"transfersessionid\":[],\"transfertrials\":[],\"domain\":\"live\",\"amount\":100000,\"currency\":\"NGN\",\"reference\":\"WD_68707ae2dc985\",\"source\":\"balance\",\"source_details\":null,\"reason\":\"Wallet withdrawal\",\"status\":\"pending\",\"failures\":null,\"transfer_code\":\"TRF_die7z2pof6ejt5ej\",\"titan_code\":null,\"transferred_at\":null,\"id\":847498862,\"integration\":1129844,\"request\":1049499767,\"recipient\":105357952,\"createdAt\":\"2025-07-11T02:45:57.000Z\",\"updatedAt\":\"2025-07-11T02:45:57.000Z\"}}', '2025-07-11 02:45:58', 'SID_68707AE804BBE'),
(3, 75, 'WD_6871047d60635', 'RCP_d07bnw66o672quh', 'OPay Digital Services Limited (OPay)', '7037237216', 'MICHAEL JOSHUA EKARIKA', 500.00, 'SUCCESS', '{\"status\":true,\"message\":\"Transfer has been queued\",\"data\":{\"transfersessionid\":[],\"transfertrials\":[],\"domain\":\"live\",\"amount\":50000,\"currency\":\"NGN\",\"reference\":\"WD_6871047d60635\",\"source\":\"balance\",\"source_details\":null,\"reason\":\"Wallet withdrawal\",\"status\":\"pending\",\"failures\":null,\"transfer_code\":\"TRF_55vk7lvmmrm13mg1\",\"titan_code\":null,\"transferred_at\":null,\"id\":847685712,\"integration\":1129844,\"request\":1049759805,\"recipient\":107333400,\"createdAt\":\"2025-07-11T12:33:04.000Z\",\"updatedAt\":\"2025-07-11T12:33:04.000Z\"}}', '2025-07-11 12:33:08', 'SID_687104857E0D3'),
(4, 84, 'WD_68710e4c68dd6', 'RCP_dnz6ailq08fcp34', 'OPay Digital Services Limited (OPay)', '8067146523', 'EBIKIENMO PROGRESS JOHN', 500.00, 'SUCCESS', '{\"status\":true,\"message\":\"Transfer has been queued\",\"data\":{\"transfersessionid\":[],\"transfertrials\":[],\"domain\":\"live\",\"amount\":50000,\"currency\":\"NGN\",\"reference\":\"WD_68710e4c68dd6\",\"source\":\"balance\",\"source_details\":null,\"reason\":\"Wallet withdrawal\",\"status\":\"pending\",\"failures\":null,\"transfer_code\":\"TRF_keg8ww6o3anpihb0\",\"titan_code\":null,\"transferred_at\":null,\"id\":847703289,\"integration\":1129844,\"request\":1049781053,\"recipient\":107335689,\"createdAt\":\"2025-07-11T13:14:54.000Z\",\"updatedAt\":\"2025-07-11T13:14:54.000Z\"}}', '2025-07-11 13:14:56', 'SID_68710E517A6E3'),
(5, 84, 'WD_68710ed419b71', 'RCP_q80q0jkt8wm1r85', 'OPay Digital Services Limited (OPay)', '8148622359', 'THEO DESMOND NWOGU', 500.00, 'SUCCESS', '{\"status\":true,\"message\":\"Transfer has been queued\",\"data\":{\"transfersessionid\":[],\"transfertrials\":[],\"domain\":\"live\",\"amount\":50000,\"currency\":\"NGN\",\"reference\":\"WD_68710ed419b71\",\"source\":\"balance\",\"source_details\":null,\"reason\":\"Wallet withdrawal\",\"status\":\"pending\",\"failures\":null,\"transfer_code\":\"TRF_89jsh73w3nbau3q8\",\"titan_code\":null,\"transferred_at\":null,\"id\":847704180,\"integration\":1129844,\"request\":1049782113,\"recipient\":105357952,\"createdAt\":\"2025-07-11T13:17:09.000Z\",\"updatedAt\":\"2025-07-11T13:17:09.000Z\"}}', '2025-07-11 13:17:10', 'SID_68710ED7D8513'),
(6, 75, 'WD_6871474ae5e53', 'RCP_b1c626iru08r2u7', 'PalmPay', '8148622359', 'THEO DESMOND NWOGU', 3000.00, 'SUCCESS', '{\"status\":true,\"message\":\"Transfer has been queued\",\"data\":{\"transfersessionid\":[],\"transfertrials\":[],\"domain\":\"live\",\"amount\":300000,\"currency\":\"NGN\",\"reference\":\"WD_6871474ae5e53\",\"source\":\"balance\",\"source_details\":null,\"reason\":\"Wallet withdrawal\",\"status\":\"pending\",\"failures\":null,\"transfer_code\":\"TRF_bz2hsq7lewjau5ax\",\"titan_code\":null,\"transferred_at\":null,\"id\":847807818,\"integration\":1129844,\"request\":1049905798,\"recipient\":96994529,\"createdAt\":\"2025-07-11T17:18:05.000Z\",\"updatedAt\":\"2025-07-11T17:18:05.000Z\"}}', '2025-07-11 17:18:08', 'SID_68714751BCA05'),
(7, 84, 'WD_6871647397bbc', 'RCP_nhf4lbhr7za81sp', 'OPay Digital Services Limited (OPay)', '8148987299', 'AYIBAPREYE  IMBUKU', 500.00, 'SUCCESS', '{\"status\":true,\"message\":\"Transfer has been queued\",\"data\":{\"transfersessionid\":[],\"transfertrials\":[],\"domain\":\"live\",\"amount\":50000,\"currency\":\"NGN\",\"reference\":\"WD_6871647397bbc\",\"source\":\"balance\",\"source_details\":null,\"reason\":\"Wallet withdrawal\",\"status\":\"pending\",\"failures\":null,\"transfer_code\":\"TRF_dyc48uxj9v1wt13x\",\"titan_code\":null,\"transferred_at\":null,\"id\":847861282,\"integration\":1129844,\"request\":1049969368,\"recipient\":107354114,\"createdAt\":\"2025-07-11T19:22:28.000Z\",\"updatedAt\":\"2025-07-11T19:22:28.000Z\"}}', '2025-07-11 19:22:32', 'SID_68716479F160F'),
(8, 75, 'WD_68717dbb6a914', 'RCP_b1c626iru08r2u7', 'PalmPay', '8148622359', 'THEO DESMOND NWOGU', 5000.00, 'SUCCESS', '{\"status\":true,\"message\":\"Transfer has been queued\",\"data\":{\"transfersessionid\":[],\"transfertrials\":[],\"domain\":\"live\",\"amount\":500000,\"currency\":\"NGN\",\"reference\":\"WD_68717dbb6a914\",\"source\":\"balance\",\"source_details\":null,\"reason\":\"Wallet withdrawal\",\"status\":\"pending\",\"failures\":null,\"transfer_code\":\"TRF_uoyxl78p4o7am4f6\",\"titan_code\":null,\"transferred_at\":null,\"id\":847911256,\"integration\":1129844,\"request\":1050028569,\"recipient\":96994529,\"createdAt\":\"2025-07-11T21:10:22.000Z\",\"updatedAt\":\"2025-07-11T21:10:22.000Z\"}}', '2025-07-11 21:10:28', 'SID_68717DC645111'),
(9, 75, 'WD_6871872e32ca6', 'RCP_fdd1rsqem6p9gg5', 'Moniepoint MFB', '5633100229', 'POS Transfer - SYLVIA NWOGU', 7500.00, 'SUCCESS', '{\"status\":true,\"message\":\"Transfer has been queued\",\"data\":{\"transfersessionid\":[],\"transfertrials\":[],\"domain\":\"live\",\"amount\":750000,\"currency\":\"NGN\",\"reference\":\"WD_6871872e32ca6\",\"source\":\"balance\",\"source_details\":null,\"reason\":\"Wallet withdrawal\",\"status\":\"pending\",\"failures\":null,\"transfer_code\":\"TRF_135rs6owb721xloo\",\"titan_code\":null,\"transferred_at\":null,\"id\":847932687,\"integration\":1129844,\"request\":1050053098,\"recipient\":106814508,\"createdAt\":\"2025-07-11T21:50:40.000Z\",\"updatedAt\":\"2025-07-11T21:50:40.000Z\"}}', '2025-07-11 21:50:43', 'SID_68718734AB4EF'),
(10, 75, 'WD_68719601466de', 'RCP_b1c626iru08r2u7', 'PalmPay', '8148622359', 'THEO DESMOND NWOGU', 4000.00, 'SUCCESS', '{\"status\":true,\"message\":\"Transfer has been queued\",\"data\":{\"transfersessionid\":[],\"transfertrials\":[],\"domain\":\"live\",\"amount\":400000,\"currency\":\"NGN\",\"reference\":\"WD_68719601466de\",\"source\":\"balance\",\"source_details\":null,\"reason\":\"Wallet withdrawal\",\"status\":\"pending\",\"failures\":null,\"transfer_code\":\"TRF_zkecd8cer2gqmp6e\",\"titan_code\":null,\"transferred_at\":null,\"id\":847956061,\"integration\":1129844,\"request\":1050081923,\"recipient\":96994529,\"createdAt\":\"2025-07-11T22:53:55.000Z\",\"updatedAt\":\"2025-07-11T22:53:55.000Z\"}}', '2025-07-11 22:53:58', 'SID_6871960753A7A'),
(11, 84, 'WD_687299a27187e', 'RCP_mqaphtc0jkzn90d', 'Moniepoint MFB', '8142589700', 'GODGIFT KOKORIFA', 3100.00, 'SUCCESS', '{\"status\":true,\"message\":\"Transfer has been queued\",\"data\":{\"transfersessionid\":[],\"transfertrials\":[],\"domain\":\"live\",\"amount\":310000,\"currency\":\"NGN\",\"reference\":\"WD_687299a27187e\",\"source\":\"balance\",\"source_details\":null,\"reason\":\"Wallet withdrawal\",\"status\":\"pending\",\"failures\":null,\"transfer_code\":\"TRF_z20lu3vgc936n37g\",\"titan_code\":null,\"transferred_at\":null,\"id\":848299398,\"integration\":1129844,\"request\":1050568452,\"recipient\":106812749,\"createdAt\":\"2025-07-12T17:21:41.000Z\",\"updatedAt\":\"2025-07-12T17:21:41.000Z\"}}', '2025-07-12 17:21:43', 'SID_687299A8B833F'),
(12, 84, 'WD_68729bfef3858', 'RCP_b1c626iru08r2u7', 'PalmPay', '8148622359', 'THEO DESMOND NWOGU', 5000.00, 'SUCCESS', '{\"status\":true,\"message\":\"Transfer has been queued\",\"data\":{\"transfersessionid\":[],\"transfertrials\":[],\"domain\":\"live\",\"amount\":500000,\"currency\":\"NGN\",\"reference\":\"WD_68729bfef3858\",\"source\":\"balance\",\"source_details\":null,\"reason\":\"Wallet withdrawal\",\"status\":\"pending\",\"failures\":null,\"transfer_code\":\"TRF_u1ftktbex8cdemt7\",\"titan_code\":null,\"transferred_at\":null,\"id\":848304021,\"integration\":1129844,\"request\":1050573888,\"recipient\":96994529,\"createdAt\":\"2025-07-12T17:31:45.000Z\",\"updatedAt\":\"2025-07-12T17:31:45.000Z\"}}', '2025-07-12 17:31:48', 'SID_6872A999D0ECD'),
(13, 84, 'WD_6872a9b003d15', 'RCP_b1c626iru08r2u7', 'PalmPay', '8148622359', 'THEO DESMOND NWOGU', 4000.00, 'SUCCESS', '{\"status\":true,\"message\":\"Transfer has been queued\",\"data\":{\"transfersessionid\":[],\"transfertrials\":[],\"domain\":\"live\",\"amount\":400000,\"currency\":\"NGN\",\"reference\":\"WD_6872a9b003d15\",\"source\":\"balance\",\"source_details\":null,\"reason\":\"Wallet withdrawal\",\"status\":\"pending\",\"failures\":null,\"transfer_code\":\"TRF_7uutpyjet5pknskg\",\"titan_code\":null,\"transferred_at\":null,\"id\":848330896,\"integration\":1129844,\"request\":1050605474,\"recipient\":96994529,\"createdAt\":\"2025-07-12T18:30:15.000Z\",\"updatedAt\":\"2025-07-12T18:30:15.000Z\"}}', '2025-07-12 18:30:19', 'SID_6872A9BCD0D8E'),
(14, 84, 'WD_6873893c3555b', 'RCP_jfi0ur4xgk4807v', 'Moniepoint MFB', '5490357293', 'JULIET JONAH', 10000.00, 'SUCCESS', '{\"status\":true,\"message\":\"Transfer has been queued\",\"data\":{\"transfersessionid\":[],\"transfertrials\":[],\"domain\":\"live\",\"amount\":1000000,\"currency\":\"NGN\",\"reference\":\"WD_6873893c3555b\",\"source\":\"balance\",\"source_details\":null,\"reason\":\"Wallet withdrawal\",\"status\":\"pending\",\"failures\":null,\"transfer_code\":\"TRF_evjfnyimo8zn9rot\",\"titan_code\":null,\"transferred_at\":null,\"id\":848614937,\"integration\":1129844,\"request\":1050992974,\"recipient\":107425128,\"createdAt\":\"2025-07-13T10:23:57.000Z\",\"updatedAt\":\"2025-07-13T10:23:57.000Z\"}}', '2025-07-13 10:23:59', 'SID_6873894064AC0'),
(15, 84, 'WD_6873896b5beb3', 'RCP_q80q0jkt8wm1r85', 'OPay Digital Services Limited (OPay)', '8148622359', 'THEO DESMOND NWOGU', 6000.00, 'SUCCESS', '{\"status\":true,\"message\":\"Transfer has been queued\",\"data\":{\"transfersessionid\":[],\"transfertrials\":[],\"domain\":\"live\",\"amount\":600000,\"currency\":\"NGN\",\"reference\":\"WD_6873896b5beb3\",\"source\":\"balance\",\"source_details\":null,\"reason\":\"Wallet withdrawal\",\"status\":\"pending\",\"failures\":null,\"transfer_code\":\"TRF_r3ddndyiq5joeh7h\",\"titan_code\":null,\"transferred_at\":null,\"id\":848615199,\"integration\":1129844,\"request\":1050993313,\"recipient\":105357952,\"createdAt\":\"2025-07-13T10:24:45.000Z\",\"updatedAt\":\"2025-07-13T10:24:45.000Z\"}}', '2025-07-13 10:24:46', 'SID_6873897026F5D'),
(16, 84, 'WD_68738aad8d806', 'RCP_1zwiv0t14l76xn4', 'OPay Digital Services Limited (OPay)', '8079791300', 'INIFIE  AKPEKI', 500.00, 'SUCCESS', '{\"status\":true,\"message\":\"Transfer has been queued\",\"data\":{\"transfersessionid\":[],\"transfertrials\":[],\"domain\":\"live\",\"amount\":50000,\"currency\":\"NGN\",\"reference\":\"WD_68738aad8d806\",\"source\":\"balance\",\"source_details\":null,\"reason\":\"Wallet withdrawal\",\"status\":\"pending\",\"failures\":null,\"transfer_code\":\"TRF_tzua3rdpa4qavw6f\",\"titan_code\":null,\"transferred_at\":null,\"id\":848617015,\"integration\":1129844,\"request\":1050995721,\"recipient\":107425362,\"createdAt\":\"2025-07-13T10:30:11.000Z\",\"updatedAt\":\"2025-07-13T10:30:11.000Z\"}}', '2025-07-13 10:30:13', 'SID_68738AB65289B'),
(17, 84, 'WD_6874044a7aee0', 'RCP_h40veqynz0lez3b', 'Moniepoint MFB', '5921308683', 'POS Transfer-ESTHER FRIDAY JIMMY', 2100.00, 'SUCCESS', '{\"status\":true,\"message\":\"Transfer has been queued\",\"data\":{\"transfersessionid\":[],\"transfertrials\":[],\"domain\":\"live\",\"amount\":210000,\"currency\":\"NGN\",\"reference\":\"WD_6874044a7aee0\",\"source\":\"balance\",\"source_details\":null,\"reason\":\"Wallet withdrawal\",\"status\":\"pending\",\"failures\":null,\"transfer_code\":\"TRF_dy7zlsidkngyfzkb\",\"titan_code\":null,\"transferred_at\":null,\"id\":848836477,\"integration\":1129844,\"request\":1051274126,\"recipient\":107449458,\"createdAt\":\"2025-07-13T19:08:59.000Z\",\"updatedAt\":\"2025-07-13T19:08:59.000Z\"}}', '2025-07-13 19:09:02', 'SID_6874044F7BF72'),
(18, 84, 'WD_6874a9810a880', 'RCP_35m97jeeeo8ynno', 'OPay Digital Services Limited (OPay)', '7026977010', 'BIBO  JOEL', 5500.00, 'SUCCESS', '{\"status\":true,\"message\":\"Transfer has been queued\",\"data\":{\"transfersessionid\":[],\"transfertrials\":[],\"domain\":\"live\",\"amount\":550000,\"currency\":\"NGN\",\"reference\":\"WD_6874a9810a880\",\"source\":\"balance\",\"source_details\":null,\"reason\":\"Wallet withdrawal\",\"status\":\"pending\",\"failures\":null,\"transfer_code\":\"TRF_1pu1y8hmjspunjb5\",\"titan_code\":null,\"transferred_at\":null,\"id\":849130823,\"integration\":1129844,\"request\":1051722043,\"recipient\":107474703,\"createdAt\":\"2025-07-14T06:53:54.000Z\",\"updatedAt\":\"2025-07-14T06:53:54.000Z\"}}', '2025-07-14 06:53:55', 'SID_6874A985395BB'),
(19, 84, 'WD_6874d28533b0b', 'RCP_b1c626iru08r2u7', 'PalmPay', '8148622359', 'THEO DESMOND NWOGU', 10000.00, 'SUCCESS', '{\"status\":true,\"message\":\"Transfer has been queued\",\"data\":{\"transfersessionid\":[],\"transfertrials\":[],\"domain\":\"live\",\"amount\":1000000,\"currency\":\"NGN\",\"reference\":\"WD_6874d28533b0b\",\"source\":\"balance\",\"source_details\":null,\"reason\":\"Wallet withdrawal\",\"status\":\"pending\",\"failures\":null,\"transfer_code\":\"TRF_2ygpraf537sst19g\",\"titan_code\":null,\"transferred_at\":null,\"id\":849212867,\"integration\":1129844,\"request\":1051820381,\"recipient\":96994529,\"createdAt\":\"2025-07-14T09:48:54.000Z\",\"updatedAt\":\"2025-07-14T09:48:54.000Z\"}}', '2025-07-14 09:48:56', 'SID_6874D289E812D'),
(20, 84, 'WD_6874eb8ec601f', 'RCP_fnup6iv0hhbzxna', 'First City Monument Bank', '3983993018', 'EMOKA JUDE EBUBECHUKWU DAVID', 1000.00, 'SUCCESS', '{\"status\":true,\"message\":\"Transfer has been queued\",\"data\":{\"transfersessionid\":[],\"transfertrials\":[],\"domain\":\"live\",\"amount\":100000,\"currency\":\"NGN\",\"reference\":\"WD_6874eb8ec601f\",\"source\":\"balance\",\"source_details\":null,\"reason\":\"Wallet withdrawal\",\"status\":\"pending\",\"failures\":null,\"transfer_code\":\"TRF_ujr012x83e9hmdki\",\"titan_code\":null,\"transferred_at\":null,\"id\":849267058,\"integration\":1129844,\"request\":1051883995,\"recipient\":107490514,\"createdAt\":\"2025-07-14T11:35:44.000Z\",\"updatedAt\":\"2025-07-14T11:35:44.000Z\"}}', '2025-07-14 11:35:46', 'SID_6874EB93C58F7'),
(21, 84, 'WD_6875264d22d04', 'RCP_h40veqynz0lez3b', 'Moniepoint MFB', '5921308683', 'POS Transfer-ESTHER FRIDAY JIMMY', 3000.00, 'SUCCESS', '{\"status\":true,\"message\":\"Transfer has been queued\",\"data\":{\"transfersessionid\":[],\"transfertrials\":[],\"domain\":\"live\",\"amount\":300000,\"currency\":\"NGN\",\"reference\":\"WD_6875264d22d04\",\"source\":\"balance\",\"source_details\":null,\"reason\":\"Wallet withdrawal\",\"status\":\"pending\",\"failures\":null,\"transfer_code\":\"TRF_9mw7s93lkioexr1c\",\"titan_code\":null,\"transferred_at\":null,\"id\":849386044,\"integration\":1129844,\"request\":1052028613,\"recipient\":107449458,\"createdAt\":\"2025-07-14T15:46:22.000Z\",\"updatedAt\":\"2025-07-14T15:46:22.000Z\"}}', '2025-07-14 15:46:23', 'SID_687526516CF76'),
(22, 84, 'WD_687543f15eb8a', 'RCP_b1c626iru08r2u7', 'PalmPay', '8148622359', 'THEO DESMOND NWOGU', 6000.00, 'SUCCESS', '{\"status\":true,\"message\":\"Transfer has been queued\",\"data\":{\"transfersessionid\":[],\"transfertrials\":[],\"domain\":\"live\",\"amount\":600000,\"currency\":\"NGN\",\"reference\":\"WD_687543f15eb8a\",\"source\":\"balance\",\"source_details\":null,\"reason\":\"Wallet withdrawal\",\"status\":\"pending\",\"failures\":null,\"transfer_code\":\"TRF_45m8agng64lk5a8v\",\"titan_code\":null,\"transferred_at\":null,\"id\":849446536,\"integration\":1129844,\"request\":1052103449,\"recipient\":96994529,\"createdAt\":\"2025-07-14T17:52:52.000Z\",\"updatedAt\":\"2025-07-14T17:52:52.000Z\"}}', '2025-07-14 17:52:56', 'SID_687543F99165E'),
(23, 84, 'WD_687565da30867', 'RCP_b1c626iru08r2u7', 'PalmPay', '8148622359', 'THEO DESMOND NWOGU', 2000.00, 'SUCCESS', '{\"status\":true,\"message\":\"Transfer has been queued\",\"data\":{\"transfersessionid\":[],\"transfertrials\":[],\"domain\":\"live\",\"amount\":200000,\"currency\":\"NGN\",\"reference\":\"WD_687565da30867\",\"source\":\"balance\",\"source_details\":null,\"reason\":\"Wallet withdrawal\",\"status\":\"pending\",\"failures\":null,\"transfer_code\":\"TRF_ylftzku93iabm669\",\"titan_code\":null,\"transferred_at\":null,\"id\":849519671,\"integration\":1129844,\"request\":1052194047,\"recipient\":96994529,\"createdAt\":\"2025-07-14T20:17:31.000Z\",\"updatedAt\":\"2025-07-14T20:17:31.000Z\"}}', '2025-07-14 20:17:33', 'SID_687565DF3EBB2'),
(24, 84, 'WD_687583ce12b9e', 'RCP_b1c626iru08r2u7', 'PalmPay', '8148622359', 'THEO DESMOND NWOGU', 1500.00, 'SUCCESS', '{\"status\":true,\"message\":\"Transfer has been queued\",\"data\":{\"transfersessionid\":[],\"transfertrials\":[],\"domain\":\"live\",\"amount\":150000,\"currency\":\"NGN\",\"reference\":\"WD_687583ce12b9e\",\"source\":\"balance\",\"source_details\":null,\"reason\":\"Wallet withdrawal\",\"status\":\"pending\",\"failures\":null,\"transfer_code\":\"TRF_iha15dqxvvrmdl87\",\"titan_code\":null,\"transferred_at\":null,\"id\":849581269,\"integration\":1129844,\"request\":1052272182,\"recipient\":96994529,\"createdAt\":\"2025-07-14T22:25:19.000Z\",\"updatedAt\":\"2025-07-14T22:25:19.000Z\"}}', '2025-07-14 22:25:23', 'SID_687583D526809'),
(25, 84, 'WD_68762fee675f7', 'RCP_b1c626iru08r2u7', 'PalmPay', '8148622359', 'THEO DESMOND NWOGU', 3000.00, 'SUCCESS', '{\"status\":true,\"message\":\"Transfer has been queued\",\"data\":{\"transfersessionid\":[],\"transfertrials\":[],\"domain\":\"live\",\"amount\":300000,\"currency\":\"NGN\",\"reference\":\"WD_68762fee675f7\",\"source\":\"balance\",\"source_details\":null,\"reason\":\"Wallet withdrawal\",\"status\":\"pending\",\"failures\":null,\"transfer_code\":\"TRF_ihwt7umz82uen150\",\"titan_code\":null,\"transferred_at\":null,\"id\":849791999,\"integration\":1129844,\"request\":1052660993,\"recipient\":96994529,\"createdAt\":\"2025-07-15T10:39:45.000Z\",\"updatedAt\":\"2025-07-15T10:39:45.000Z\"}}', '2025-07-15 10:39:50', 'SID_68762FF7C565B'),
(26, 84, 'WD_68764afed5fc3', 'RCP_lcvjzgcdmhetygl', 'OPay Digital Services Limited (OPay)', '7077716018', 'PATIENCE  ANTHONY', 3500.00, 'SUCCESS', '{\"status\":true,\"message\":\"Transfer has been queued\",\"data\":{\"transfersessionid\":[],\"transfertrials\":[],\"domain\":\"live\",\"amount\":350000,\"currency\":\"NGN\",\"reference\":\"WD_68764afed5fc3\",\"source\":\"balance\",\"source_details\":null,\"reason\":\"Wallet withdrawal\",\"status\":\"pending\",\"failures\":null,\"transfer_code\":\"TRF_efoxcd26atjdfs0z\",\"titan_code\":null,\"transferred_at\":null,\"id\":849846324,\"integration\":1129844,\"request\":1052729135,\"recipient\":107562478,\"createdAt\":\"2025-07-15T12:35:11.000Z\",\"updatedAt\":\"2025-07-15T12:35:11.000Z\"}}', '2025-07-15 12:35:13', 'SID_68764B0297236'),
(27, 84, 'WD_6876785e01755', 'RCP_cw18hgquotheco8', 'Sterling Bank', '0095811649', 'ISAAC CONSTANCE SAPELE', 500.00, 'SUCCESS', '{\"status\":true,\"message\":\"Transfer has been queued\",\"data\":{\"transfersessionid\":[],\"transfertrials\":[],\"domain\":\"live\",\"amount\":50000,\"currency\":\"NGN\",\"reference\":\"WD_6876785e01755\",\"source\":\"balance\",\"source_details\":null,\"reason\":\"Wallet withdrawal\",\"status\":\"pending\",\"failures\":null,\"transfer_code\":\"TRF_3gjilegumyqaupw8\",\"titan_code\":null,\"transferred_at\":null,\"id\":849919678,\"integration\":1129844,\"request\":1052824577,\"recipient\":107572275,\"createdAt\":\"2025-07-15T15:48:49.000Z\",\"updatedAt\":\"2025-07-15T15:48:49.000Z\"}}', '2025-07-15 15:48:51', 'SID_6876786534DEF'),
(28, 84, 'WD_6876d6032ada2', 'RCP_wkmnwllxyv8ene2', 'Moniepoint MFB', '5043826579', 'POS Transfer-OMAJI MARY OCHUOULE', 5500.00, 'FAILED', '{\"status\":false,\"message\":\"Your balance is not enough to fulfil this request\",\"meta\":{\"nextStep\":\"Topup your Paystack Balance and try again.\"},\"type\":\"api_error\",\"code\":\"insufficient_balance\"}', '2025-07-15 22:28:21', NULL),
(29, 84, 'WD_6876d609e80f9', 'RCP_wkmnwllxyv8ene2', 'Moniepoint MFB', '5043826579', 'POS Transfer-OMAJI MARY OCHUOULE', 5500.00, 'FAILED', '{\"status\":false,\"message\":\"Your balance is not enough to fulfil this request\",\"meta\":{\"nextStep\":\"Topup your Paystack Balance and try again.\"},\"type\":\"api_error\",\"code\":\"insufficient_balance\"}', '2025-07-15 22:28:27', NULL),
(30, 84, 'WD_6876d61206b52', 'RCP_wkmnwllxyv8ene2', 'Moniepoint MFB', '5043826579', 'POS Transfer-OMAJI MARY OCHUOULE', 5500.00, 'FAILED', '{\"status\":false,\"message\":\"Your balance is not enough to fulfil this request\",\"meta\":{\"nextStep\":\"Topup your Paystack Balance and try again.\"},\"type\":\"api_error\",\"code\":\"insufficient_balance\"}', '2025-07-15 22:28:36', NULL),
(31, 84, 'WD_6876d623b7196', 'RCP_wkmnwllxyv8ene2', 'Moniepoint MFB', '5043826579', 'POS Transfer-OMAJI MARY OCHUOULE', 5500.00, 'FAILED', '{\"status\":false,\"message\":\"Your balance is not enough to fulfil this request\",\"meta\":{\"nextStep\":\"Topup your Paystack Balance and try again.\"},\"type\":\"api_error\",\"code\":\"insufficient_balance\"}', '2025-07-15 22:28:53', NULL),
(32, 84, 'WD_6876d62e2735a', 'RCP_wkmnwllxyv8ene2', 'Moniepoint MFB', '5043826579', 'POS Transfer-OMAJI MARY OCHUOULE', 5500.00, 'FAILED', '{\"status\":false,\"message\":\"Your balance is not enough to fulfil this request\",\"meta\":{\"nextStep\":\"Topup your Paystack Balance and try again.\"},\"type\":\"api_error\",\"code\":\"insufficient_balance\"}', '2025-07-15 22:29:04', NULL),
(33, 84, 'WD_6876d634ab798', 'RCP_wkmnwllxyv8ene2', 'Moniepoint MFB', '5043826579', 'POS Transfer-OMAJI MARY OCHUOULE', 5500.00, 'FAILED', '{\"status\":false,\"message\":\"Your balance is not enough to fulfil this request\",\"meta\":{\"nextStep\":\"Topup your Paystack Balance and try again.\"},\"type\":\"api_error\",\"code\":\"insufficient_balance\"}', '2025-07-15 22:29:10', NULL),
(34, 84, 'WD_6876d63c5ae02', 'RCP_wkmnwllxyv8ene2', 'Moniepoint MFB', '5043826579', 'POS Transfer-OMAJI MARY OCHUOULE', 5500.00, 'FAILED', '{\"status\":false,\"message\":\"Your balance is not enough to fulfil this request\",\"meta\":{\"nextStep\":\"Topup your Paystack Balance and try again.\"},\"type\":\"api_error\",\"code\":\"insufficient_balance\"}', '2025-07-15 22:29:16', NULL),
(35, 84, 'WD_6876d669898c7', 'RCP_wkmnwllxyv8ene2', 'Moniepoint MFB', '5043826579', 'POS Transfer-OMAJI MARY OCHUOULE', 5500.00, 'FAILED', '{\"status\":false,\"message\":\"Your balance is not enough to fulfil this request\",\"meta\":{\"nextStep\":\"Topup your Paystack Balance and try again.\"},\"type\":\"api_error\",\"code\":\"insufficient_balance\"}', '2025-07-15 22:30:09', 'SID_687783B825432'),
(36, 84, 'WD_6876d6794c9b7', 'RCP_wkmnwllxyv8ene2', 'Moniepoint MFB', '5043826579', 'POS Transfer-OMAJI MARY OCHUOULE', 5500.00, 'FAILED', '{\"status\":false,\"message\":\"Your balance is not enough to fulfil this request\",\"meta\":{\"nextStep\":\"Topup your Paystack Balance and try again.\"},\"type\":\"api_error\",\"code\":\"insufficient_balance\"}', '2025-07-15 22:30:19', 'SID_687783B26995E'),
(37, 84, 'WD_6877839216496', 'RCP_b1c626iru08r2u7', 'PalmPay', '8148622359', 'THEO DESMOND NWOGU', 15000.00, 'FAILED', '{\"status\":false,\"message\":\"Your balance is not enough to fulfil this request\",\"meta\":{\"nextStep\":\"Topup your Paystack Balance and try again.\"},\"type\":\"api_error\",\"code\":\"insufficient_balance\"}', '2025-07-16 10:48:50', 'SID_687783A92BEB0'),
(38, 84, 'WD_6877839d29e26', 'RCP_b1c626iru08r2u7', 'PalmPay', '8148622359', 'THEO DESMOND NWOGU', 15000.00, 'FAILED', '{\"status\":false,\"message\":\"Your balance is not enough to fulfil this request\",\"meta\":{\"nextStep\":\"Topup your Paystack Balance and try again.\"},\"type\":\"api_error\",\"code\":\"insufficient_balance\"}', '2025-07-16 10:49:01', 'SID_687783C09A733'),
(39, 84, 'WD_6877841484ed7', 'RCP_7b6p9ilne5m18vd', 'OPay Digital Services Limited (OPay)', '8058060884', 'EBILABO  PEREDE', 10000.00, 'FAILED', '{\"status\":false,\"message\":\"Your balance is not enough to fulfil this request\",\"meta\":{\"nextStep\":\"Topup your Paystack Balance and try again.\"},\"type\":\"api_error\",\"code\":\"insufficient_balance\"}', '2025-07-16 10:51:01', 'SID_687784309B2E1'),
(40, 84, 'WD_68778420254de', 'RCP_7b6p9ilne5m18vd', 'OPay Digital Services Limited (OPay)', '8058060884', 'EBILABO  PEREDE', 7000.00, 'FAILED', '{\"status\":false,\"message\":\"Your balance is not enough to fulfil this request\",\"meta\":{\"nextStep\":\"Topup your Paystack Balance and try again.\"},\"type\":\"api_error\",\"code\":\"insufficient_balance\"}', '2025-07-16 10:51:14', 'SID_68778426B3DBE'),
(41, 84, 'WD_687e82a81809f', 'RCP_we0b67yi0l5prmg', 'Access Bank', '0028119916', 'SYLVESTER CHISOM EDEH', 1000.00, 'FAILED', '{\"status\":false,\"message\":\"Your balance is not enough to fulfil this request\",\"meta\":{\"nextStep\":\"Topup your Paystack Balance and try again.\"},\"type\":\"api_error\",\"code\":\"insufficient_balance\"}', '2025-07-21 18:10:51', NULL),
(42, 84, 'WD_687e82ce700ed', 'RCP_we0b67yi0l5prmg', 'Access Bank', '0028119916', 'SYLVESTER CHISOM EDEH', 800.00, 'SUCCESS', '{\"status\":true,\"message\":\"Transfer has been queued\",\"data\":{\"transfersessionid\":[],\"transfertrials\":[],\"domain\":\"live\",\"amount\":80000,\"currency\":\"NGN\",\"reference\":\"WD_687e82ce700ed\",\"source\":\"balance\",\"source_details\":null,\"reason\":\"Wallet withdrawal\",\"status\":\"pending\",\"failures\":null,\"transfer_code\":\"TRF_saesfkfeimv5rymu\",\"titan_code\":null,\"transferred_at\":null,\"id\":853062151,\"integration\":1129844,\"request\":1057399872,\"recipient\":107932728,\"createdAt\":\"2025-07-21T18:11:31.000Z\",\"updatedAt\":\"2025-07-21T18:11:31.000Z\"}}', '2025-07-21 18:11:33', 'SID_687E82D7C3484'),
(43, 75, 'WD_68824495f3ad9', 'RCP_vug3bp4vpn9waan', 'Moniepoint MFB', '5690014961', 'DE- BLESSED EMINENT GLOBAL RESOURCES - EMMANUEL MADUKWE ONYEMUWA', 17000.00, 'SUCCESS', '{\"status\":true,\"message\":\"Transfer has been queued\",\"data\":{\"transfersessionid\":[],\"transfertrials\":[],\"domain\":\"live\",\"amount\":1700000,\"currency\":\"NGN\",\"reference\":\"WD_68824495f3ad9\",\"source\":\"balance\",\"source_details\":null,\"reason\":\"Wallet withdrawal\",\"status\":\"pending\",\"failures\":null,\"transfer_code\":\"TRF_ip0ms5l46buri89d\",\"titan_code\":null,\"transferred_at\":null,\"id\":854465267,\"integration\":1129844,\"request\":1059614511,\"recipient\":108114430,\"createdAt\":\"2025-07-24T14:35:03.000Z\",\"updatedAt\":\"2025-07-24T14:35:03.000Z\"}}', '2025-07-24 14:35:06', 'SID_6882449B7746F'),
(44, 75, 'WD_688244a8b40d1', 'RCP_b1c626iru08r2u7', 'PalmPay', '8148622359', 'THEO DESMOND NWOGU', 10000.00, 'SUCCESS', '{\"status\":true,\"message\":\"Transfer has been queued\",\"data\":{\"transfersessionid\":[],\"transfertrials\":[],\"domain\":\"live\",\"amount\":1000000,\"currency\":\"NGN\",\"reference\":\"WD_688244a8b40d1\",\"source\":\"balance\",\"source_details\":null,\"reason\":\"Wallet withdrawal\",\"status\":\"pending\",\"failures\":null,\"transfer_code\":\"TRF_c6lpr9o9fiev72fd\",\"titan_code\":null,\"transferred_at\":null,\"id\":854465423,\"integration\":1129844,\"request\":1059614714,\"recipient\":96994529,\"createdAt\":\"2025-07-24T14:35:21.000Z\",\"updatedAt\":\"2025-07-24T14:35:21.000Z\"}}', '2025-07-24 14:35:24', 'SID_688244AE208C6'),
(45, 75, 'WD_68824df7e7da2', 'RCP_b1c626iru08r2u7', 'PalmPay', '8148622359', 'THEO DESMOND NWOGU', 8000.00, 'SUCCESS', '{\"status\":true,\"message\":\"Transfer has been queued\",\"data\":{\"transfersessionid\":[],\"transfertrials\":[],\"domain\":\"live\",\"amount\":800000,\"currency\":\"NGN\",\"reference\":\"WD_68824df7e7da2\",\"source\":\"balance\",\"source_details\":null,\"reason\":\"Wallet withdrawal\",\"status\":\"pending\",\"failures\":null,\"transfer_code\":\"TRF_cz0cpjxaleil0fj9\",\"titan_code\":null,\"transferred_at\":null,\"id\":854481656,\"integration\":1129844,\"request\":1059635657,\"recipient\":96994529,\"createdAt\":\"2025-07-24T15:15:05.000Z\",\"updatedAt\":\"2025-07-24T15:15:05.000Z\"}}', '2025-07-24 15:15:08', 'SID_68824DFE159E0'),
(46, 75, 'WD_6882a50945a2b', 'RCP_gdoxob8nqi3fazh', 'PalmPay', '8068204680', 'DIEMODE  SIMEON', 10000.00, 'SUCCESS', '{\"status\":true,\"message\":\"Transfer has been queued\",\"data\":{\"transfersessionid\":[],\"transfertrials\":[],\"domain\":\"live\",\"amount\":1000000,\"currency\":\"NGN\",\"reference\":\"WD_6882a50945a2b\",\"source\":\"balance\",\"source_details\":null,\"reason\":\"Wallet withdrawal\",\"status\":\"pending\",\"failures\":null,\"transfer_code\":\"TRF_op7105cbp4h1iq0y\",\"titan_code\":null,\"transferred_at\":null,\"id\":854643703,\"integration\":1129844,\"request\":1059846300,\"recipient\":108136505,\"createdAt\":\"2025-07-24T21:26:34.000Z\",\"updatedAt\":\"2025-07-24T21:26:34.000Z\"}}', '2025-07-24 21:26:37', 'SID_6882A50E94582'),
(47, 75, 'WD_68832b293cb3b', 'RCP_35m97jeeeo8ynno', 'OPay Digital Services Limited (OPay)', '7026977010', 'BIBO  JOEL', 3800.00, 'SUCCESS', '{\"status\":true,\"message\":\"Transfer has been queued\",\"data\":{\"transfersessionid\":[],\"transfertrials\":[],\"domain\":\"live\",\"amount\":380000,\"currency\":\"NGN\",\"reference\":\"WD_68832b293cb3b\",\"source\":\"balance\",\"source_details\":null,\"reason\":\"Wallet withdrawal\",\"status\":\"pending\",\"failures\":null,\"transfer_code\":\"TRF_ql5osfcfqhq77g5y\",\"titan_code\":null,\"transferred_at\":null,\"id\":854760723,\"integration\":1129844,\"request\":1060129128,\"recipient\":107474703,\"createdAt\":\"2025-07-25T06:58:50.000Z\",\"updatedAt\":\"2025-07-25T06:58:50.000Z\"}}', '2025-07-25 06:58:51', 'SID_68832B2EC7491'),
(48, 75, 'WD_68832b2c3a339', 'RCP_35m97jeeeo8ynno', 'OPay Digital Services Limited (OPay)', '7026977010', 'BIBO  JOEL', 3800.00, 'SUCCESS', '{\"status\":true,\"message\":\"Transfer has been queued\",\"data\":{\"transfersessionid\":[],\"transfertrials\":[],\"domain\":\"live\",\"amount\":380000,\"currency\":\"NGN\",\"reference\":\"WD_68832b2c3a339\",\"source\":\"balance\",\"source_details\":null,\"reason\":\"Wallet withdrawal\",\"status\":\"pending\",\"failures\":null,\"transfer_code\":\"TRF_210j27ehvojvlfy9\",\"titan_code\":null,\"transferred_at\":null,\"id\":854760743,\"integration\":1129844,\"request\":1060129158,\"recipient\":107474703,\"createdAt\":\"2025-07-25T06:58:53.000Z\",\"updatedAt\":\"2025-07-25T06:58:53.000Z\"}}', '2025-07-25 06:58:54', NULL),
(49, 75, 'WD_68834c2764f69', 'RCP_ekwrukv57p9xpl3', 'OPay Digital Services Limited (OPay)', '6141057436', 'Smartty Global', 15000.00, 'SUCCESS', '{\"status\":true,\"message\":\"Transfer has been queued\",\"data\":{\"transfersessionid\":[],\"transfertrials\":[],\"domain\":\"live\",\"amount\":1500000,\"currency\":\"NGN\",\"reference\":\"WD_68834c2764f69\",\"source\":\"balance\",\"source_details\":null,\"reason\":\"Wallet withdrawal\",\"status\":\"pending\",\"failures\":null,\"transfer_code\":\"TRF_x2kjp1jtxfbhhfkj\",\"titan_code\":null,\"transferred_at\":null,\"id\":854808524,\"integration\":1129844,\"request\":1060194164,\"recipient\":108153895,\"createdAt\":\"2025-07-25T09:19:36.000Z\",\"updatedAt\":\"2025-07-25T09:19:36.000Z\"}}', '2025-07-25 09:19:37', 'SID_68834C2AF153E'),
(50, 75, 'WD_68834c60b29d3', 'RCP_h40veqynz0lez3b', 'Moniepoint MFB', '5921308683', 'POS Transfer-ESTHER FRIDAY JIMMY', 3500.00, 'SUCCESS', '{\"status\":true,\"message\":\"Transfer has been queued\",\"data\":{\"transfersessionid\":[],\"transfertrials\":[],\"domain\":\"live\",\"amount\":350000,\"currency\":\"NGN\",\"reference\":\"WD_68834c60b29d3\",\"source\":\"balance\",\"source_details\":null,\"reason\":\"Wallet withdrawal\",\"status\":\"pending\",\"failures\":null,\"transfer_code\":\"TRF_qr92yxzpx6e3i484\",\"titan_code\":null,\"transferred_at\":null,\"id\":854808954,\"integration\":1129844,\"request\":1060194717,\"recipient\":107449458,\"createdAt\":\"2025-07-25T09:20:33.000Z\",\"updatedAt\":\"2025-07-25T09:20:33.000Z\"}}', '2025-07-25 09:20:35', 'SID_68834C65146DC'),
(51, 75, 'WD_68837ecfc9cad', 'RCP_0lwd2ppzwk11b9v', 'Moniepoint MFB', '8163777208', 'POS Transfer - BLESSINGS AKPOTOR', 2100.00, 'SUCCESS', '{\"status\":true,\"message\":\"Transfer has been queued\",\"data\":{\"transfersessionid\":[],\"transfertrials\":[],\"domain\":\"live\",\"amount\":210000,\"currency\":\"NGN\",\"reference\":\"WD_68837ecfc9cad\",\"source\":\"balance\",\"source_details\":null,\"reason\":\"Wallet withdrawal\",\"status\":\"pending\",\"failures\":null,\"transfer_code\":\"TRF_jw6zlqupuyivb2p3\",\"titan_code\":null,\"transferred_at\":null,\"id\":854901587,\"integration\":1129844,\"request\":1060314937,\"recipient\":108167987,\"createdAt\":\"2025-07-25T12:55:44.000Z\",\"updatedAt\":\"2025-07-25T12:55:44.000Z\"}}', '2025-07-25 12:55:47', NULL),
(52, 75, 'WD_6883a49ae1ef0', 'RCP_1twzzhbmma7nnvk', 'OPay Digital Services Limited (OPay)', '9033797062', 'PEACE MEINTA DERRI', 5000.00, 'SUCCESS', '{\"status\":true,\"message\":\"Transfer has been queued\",\"data\":{\"transfersessionid\":[],\"transfertrials\":[],\"domain\":\"live\",\"amount\":500000,\"currency\":\"NGN\",\"reference\":\"WD_6883a49ae1ef0\",\"source\":\"balance\",\"source_details\":null,\"reason\":\"Wallet withdrawal\",\"status\":\"pending\",\"failures\":null,\"transfer_code\":\"TRF_riq6xkyhz4oha8tw\",\"titan_code\":null,\"transferred_at\":null,\"id\":854966957,\"integration\":1129844,\"request\":1060406510,\"recipient\":108179130,\"createdAt\":\"2025-07-25T15:37:00.000Z\",\"updatedAt\":\"2025-07-25T15:37:00.000Z\"}}', '2025-07-25 15:37:02', 'SID_6883A49F4F7EE'),
(53, 75, 'WD_6883ab1575c91', 'RCP_08m2bv95dxpjf27', 'OPay Digital Services Limited (OPay)', '8102557787', 'AZANA  AGOROWEI', 10000.00, 'SUCCESS', '{\"status\":true,\"message\":\"Transfer has been queued\",\"data\":{\"transfersessionid\":[],\"transfertrials\":[],\"domain\":\"live\",\"amount\":1000000,\"currency\":\"NGN\",\"reference\":\"WD_6883ab1575c91\",\"source\":\"balance\",\"source_details\":null,\"reason\":\"Wallet withdrawal\",\"status\":\"pending\",\"failures\":null,\"transfer_code\":\"TRF_vnmuzvjpj6wlq64m\",\"titan_code\":null,\"transferred_at\":null,\"id\":854978664,\"integration\":1129844,\"request\":1060422983,\"recipient\":108180779,\"createdAt\":\"2025-07-25T16:04:38.000Z\",\"updatedAt\":\"2025-07-25T16:04:38.000Z\"}}', '2025-07-25 16:04:39', 'SID_6883AB1918664'),
(54, 75, 'WD_6883b82dc468c', 'RCP_vcfccb872z8un19', 'OPay Digital Services Limited (OPay)', '7042223643', 'AYEBANENGIMOTE INABIRIYAI RUFUS', 2000.00, 'SUCCESS', '{\"status\":true,\"message\":\"Transfer has been queued\",\"data\":{\"transfersessionid\":[],\"transfertrials\":[],\"domain\":\"live\",\"amount\":200000,\"currency\":\"NGN\",\"reference\":\"WD_6883b82dc468c\",\"source\":\"balance\",\"source_details\":null,\"reason\":\"Wallet withdrawal\",\"status\":\"pending\",\"failures\":null,\"transfer_code\":\"TRF_5aq3qt1bfdr0c0ay\",\"titan_code\":null,\"transferred_at\":null,\"id\":854999648,\"integration\":1129844,\"request\":1060455854,\"recipient\":108184608,\"createdAt\":\"2025-07-25T17:00:31.000Z\",\"updatedAt\":\"2025-07-25T17:00:31.000Z\"}}', '2025-07-25 17:00:33', 'SID_6883B83724046'),
(55, 75, 'WD_6883c52648bd5', 'RCP_o4w0n5cyuw6dt64', 'OPay Digital Services Limited (OPay)', '8121776376', 'DARLINGTON DON PEDRO', 5000.00, 'SUCCESS', '{\"status\":true,\"message\":\"Transfer has been queued\",\"data\":{\"transfersessionid\":[],\"transfertrials\":[],\"domain\":\"live\",\"amount\":500000,\"currency\":\"NGN\",\"reference\":\"WD_6883c52648bd5\",\"source\":\"balance\",\"source_details\":null,\"reason\":\"Wallet withdrawal\",\"status\":\"pending\",\"failures\":null,\"transfer_code\":\"TRF_hmz7fg2d52zk0l6r\",\"titan_code\":null,\"transferred_at\":null,\"id\":855022188,\"integration\":1129844,\"request\":1060490021,\"recipient\":108187920,\"createdAt\":\"2025-07-25T17:55:51.000Z\",\"updatedAt\":\"2025-07-25T17:55:51.000Z\"}}', '2025-07-25 17:55:52', 'SID_6883C52A3917B'),
(56, 75, 'WD_6883e9d596baa', 'RCP_vcfccb872z8un19', 'OPay Digital Services Limited (OPay)', '7042223643', 'AYEBANENGIMOTE INABIRIYAI RUFUS', 1000.00, 'SUCCESS', '{\"status\":true,\"message\":\"Transfer has been queued\",\"data\":{\"transfersessionid\":[],\"transfertrials\":[],\"domain\":\"live\",\"amount\":100000,\"currency\":\"NGN\",\"reference\":\"WD_6883e9d596baa\",\"source\":\"balance\",\"source_details\":null,\"reason\":\"Wallet withdrawal\",\"status\":\"pending\",\"failures\":null,\"transfer_code\":\"TRF_31so6i86w7g2wkfn\",\"titan_code\":null,\"transferred_at\":null,\"id\":855093762,\"integration\":1129844,\"request\":1060593831,\"recipient\":108184608,\"createdAt\":\"2025-07-25T20:32:22.000Z\",\"updatedAt\":\"2025-07-25T20:32:22.000Z\"}}', '2025-07-25 20:32:25', 'SID_6883E9DA56CA5'),
(57, 75, 'WD_68874a191a0be', 'RCP_35m97jeeeo8ynno', 'OPay Digital Services Limited (OPay)', '7026977010', 'BIBO  JOEL', 1000.00, 'SUCCESS', '{\"status\":true,\"message\":\"Transfer has been queued\",\"data\":{\"transfersessionid\":[],\"transfertrials\":[],\"domain\":\"live\",\"amount\":100000,\"currency\":\"NGN\",\"reference\":\"WD_68874a191a0be\",\"source\":\"balance\",\"source_details\":null,\"reason\":\"Wallet withdrawal\",\"status\":\"pending\",\"failures\":null,\"transfer_code\":\"TRF_fcmtlicv26kqjz5x\",\"titan_code\":null,\"transferred_at\":null,\"id\":856478615,\"integration\":1129844,\"request\":1062600947,\"recipient\":107474703,\"createdAt\":\"2025-07-28T09:59:54.000Z\",\"updatedAt\":\"2025-07-28T09:59:54.000Z\"}}', '2025-07-28 09:59:55', 'SID_68874A1D0E0C9'),
(58, 75, 'WD_68875bd9a1e9e', 'RCP_euiirgt2wevgmq5', 'United Bank For Africa', '2092031700', 'JULIUS IZOUKUMO JOY', 2700.00, 'SUCCESS', '{\"status\":true,\"message\":\"Transfer has been queued\",\"data\":{\"transfersessionid\":[],\"transfertrials\":[],\"domain\":\"live\",\"amount\":270000,\"currency\":\"NGN\",\"reference\":\"WD_68875bd9a1e9e\",\"source\":\"balance\",\"source_details\":null,\"reason\":\"Wallet withdrawal\",\"status\":\"pending\",\"failures\":null,\"transfer_code\":\"TRF_pi4ijxkthe19fbxi\",\"titan_code\":null,\"transferred_at\":null,\"id\":856514397,\"integration\":1129844,\"request\":1062647646,\"recipient\":108348442,\"createdAt\":\"2025-07-28T11:15:38.000Z\",\"updatedAt\":\"2025-07-28T11:15:38.000Z\"}}', '2025-07-28 11:15:40', 'SID_68875BDD9DDAC'),
(59, 75, 'WD_688cd8f2e7cea', 'RCP_q80q0jkt8wm1r85', 'OPay Digital Services Limited (OPay)', '8148622359', 'THEO DESMOND NWOGU', 600.00, 'SUCCESS', '{\"status\":true,\"message\":\"Transfer has been queued\",\"data\":{\"transfersessionid\":[],\"transfertrials\":[],\"domain\":\"live\",\"amount\":60000,\"currency\":\"NGN\",\"reference\":\"WD_688cd8f2e7cea\",\"source\":\"balance\",\"source_details\":null,\"reason\":\"Wallet withdrawal\",\"status\":\"pending\",\"failures\":null,\"transfer_code\":\"TRF_ujuveonbn38b1ibj\",\"titan_code\":null,\"transferred_at\":null,\"id\":858953326,\"integration\":1129844,\"request\":1066084000,\"recipient\":105357952,\"createdAt\":\"2025-08-01T15:10:44.000Z\",\"updatedAt\":\"2025-08-01T15:10:44.000Z\"}}', '2025-08-01 15:10:45', 'SID_688D3310C06C5'),
(60, 75, 'WD_688d344e7b3eb', 'RCP_b1c626iru08r2u7', 'PalmPay', '8148622359', 'THEO DESMOND NWOGU', 10.00, 'SUCCESS', '{\"status\":true,\"message\":\"Transfer has been queued\",\"data\":{\"transfersessionid\":[],\"transfertrials\":[],\"domain\":\"live\",\"amount\":1000,\"currency\":\"NGN\",\"reference\":\"WD_688d344e7b3eb\",\"source\":\"balance\",\"source_details\":null,\"reason\":\"Wallet withdrawal\",\"status\":\"pending\",\"failures\":null,\"transfer_code\":\"TRF_ibiom7zo3a27cly7\",\"titan_code\":null,\"transferred_at\":null,\"id\":859155925,\"integration\":1129844,\"request\":1066330754,\"recipient\":96994529,\"createdAt\":\"2025-08-01T21:40:31.000Z\",\"updatedAt\":\"2025-08-01T21:40:31.000Z\"}}', '2025-08-01 21:40:35', 'SID_688D345453E28'),
(61, 75, 'WD_68a74c9d606ea', 'RCP_b1c626iru08r2u7', 'PalmPay', '8148622359', 'THEO DESMOND NWOGU', 100.00, 'SUCCESS', '{\"status\":true,\"message\":\"Transfer has been queued\",\"data\":{\"transfersessionid\":[],\"transfertrials\":[],\"domain\":\"live\",\"amount\":10000,\"currency\":\"NGN\",\"reference\":\"WD_68a74c9d606ea\",\"source\":\"balance\",\"source_details\":null,\"reason\":\"Wallet withdrawal\",\"status\":\"pending\",\"failures\":null,\"transfer_code\":\"TRF_24barqtd877tyrp0\",\"titan_code\":null,\"transferred_at\":null,\"id\":871468722,\"integration\":1129844,\"request\":1083175995,\"recipient\":96994529,\"createdAt\":\"2025-08-21T16:43:10.000Z\",\"updatedAt\":\"2025-08-21T16:43:10.000Z\"}}', '2025-08-21 16:43:14', 'SID_68A74CA3B2ACD'),
(62, 75, 'WD_68a9be2ba8109', 'RCP_q80q0jkt8wm1r85', 'OPay Digital Services Limited (OPay)', '8148622359', 'THEO DESMOND NWOGU', 100.00, 'SUCCESS', '{\"status\":true,\"message\":\"Transfer has been queued\",\"data\":{\"transfersessionid\":[],\"transfertrials\":[],\"domain\":\"live\",\"amount\":10000,\"currency\":\"NGN\",\"reference\":\"WD_68a9be2ba8109\",\"source\":\"balance\",\"source_details\":null,\"reason\":\"Wallet withdrawal\",\"status\":\"pending\",\"failures\":null,\"transfer_code\":\"TRF_dyo8esejuy8gt2rx\",\"titan_code\":null,\"transferred_at\":null,\"id\":872532905,\"integration\":1129844,\"request\":1084738531,\"recipient\":105357952,\"createdAt\":\"2025-08-23T13:12:12.000Z\",\"updatedAt\":\"2025-08-23T13:12:12.000Z\"}}', '2025-08-23 13:12:14', 'SID_68A9BE2FE9866'),
(63, 75, 'WD_68aa2fcfb9a8d', '', 'PalmPay', '8148622359', 'THEO DESMOND NWOGU', 200000.00, 'SUCCESS', '{\"message\":\"Transfer simulated successfully\"}', '2025-08-23 21:17:03', 'SID_68AA2FD100805'),
(64, 75, 'WD_68aaace1da5f4', '', 'PalmPay', '8148622359', 'THEO DESMOND NWOGU', 2500.00, 'SUCCESS', '{\"message\":\"Transfer simulated successfully\"}', '2025-08-24 06:10:41', 'SID_68AAACE3254EF'),
(65, 75, 'WD_68aac1036f11d', '', 'PalmPay', '8144777061', 'IFEANYICHUKWU - CHIKEZIE', 100000.00, 'SUCCESS', '{\"message\":\"Transfer simulated successfully\"}', '2025-08-24 07:36:35', 'SID_68AAC104B1DCD'),
(66, 75, 'WD_68aadf65d7a35', 'RCP_q80q0jkt8wm1r85', 'OPay Digital Services Limited (OPay)', '8148622359', 'THEO DESMOND NWOGU', 150.00, 'SUCCESS', '{\"status\":true,\"message\":\"Transfer has been queued\",\"data\":{\"transfersessionid\":[],\"transfertrials\":[],\"domain\":\"live\",\"amount\":15000,\"currency\":\"NGN\",\"reference\":\"WD_68aadf65d7a35\",\"source\":\"balance\",\"source_details\":null,\"reason\":\"Wallet withdrawal\",\"status\":\"pending\",\"failures\":null,\"transfer_code\":\"TRF_wmobmpa9ggxw4itz\",\"titan_code\":null,\"transferred_at\":null,\"id\":873129622,\"integration\":1129844,\"request\":1085583939,\"recipient\":105357952,\"createdAt\":\"2025-08-24T09:46:14.000Z\",\"updatedAt\":\"2025-08-24T09:46:14.000Z\"}}', '2025-08-24 09:46:16', 'SID_68AADF69787AE'),
(67, 85, 'WD_68ab17617f200', 'RCP_b1c626iru08r2u7', 'PalmPay', '8148622359', 'THEO DESMOND NWOGU', 1000.00, 'FAILED', '{\"status\":false,\"message\":\"Your balance is not enough to fulfil this request\",\"meta\":{\"nextStep\":\"Topup your Paystack Balance and try again.\"},\"type\":\"api_error\",\"code\":\"insufficient_balance\"}', '2025-08-24 13:45:06', 'SID_68AB1796B1627'),
(68, 85, 'WD_68ab1776ac13e', 'RCP_b1c626iru08r2u7', 'PalmPay', '8148622359', 'THEO DESMOND NWOGU', 1000.00, 'FAILED', '{\"status\":false,\"message\":\"Your balance is not enough to fulfil this request\",\"meta\":{\"nextStep\":\"Topup your Paystack Balance and try again.\"},\"type\":\"api_error\",\"code\":\"insufficient_balance\"}', '2025-08-24 13:45:27', 'SID_68AB179A8B93F'),
(69, 85, 'WD_68ab18e2db97b', 'RCP_x3kz63kbx2te1v7', 'PalmPay', '8144777061', 'IFEANYICHUKWU - CHIKEZIE', 500.00, 'FAILED', '{\"status\":false,\"message\":\"Your balance is not enough to fulfil this request\",\"meta\":{\"nextStep\":\"Topup your Paystack Balance and try again.\"},\"type\":\"api_error\",\"code\":\"insufficient_balance\"}', '2025-08-24 13:51:31', NULL),
(70, 75, 'WD_68ab194b2d84f', 'RCP_nrl37mdcxgtjyxk', 'OPay Digital Services Limited (OPay)', '8144777061', 'IFEANYICHUKWU  CHIKEZIE', 100.00, 'SUCCESS', '{\"status\":true,\"message\":\"Transfer has been queued\",\"data\":{\"transfersessionid\":[],\"transfertrials\":[],\"domain\":\"live\",\"amount\":10000,\"currency\":\"NGN\",\"reference\":\"WD_68ab194b2d84f\",\"source\":\"balance\",\"source_details\":null,\"reason\":\"Wallet withdrawal\",\"status\":\"pending\",\"failures\":null,\"transfer_code\":\"TRF_eqiyc8f36lur26o7\",\"titan_code\":null,\"transferred_at\":null,\"id\":873248020,\"integration\":1129844,\"request\":1085732732,\"recipient\":110268799,\"createdAt\":\"2025-08-24T13:53:16.000Z\",\"updatedAt\":\"2025-08-24T13:53:16.000Z\"}}', '2025-08-24 13:53:17', 'SID_68AB194F5BD55'),
(71, 85, 'WD_68ab1a25a7d6b', 'RCP_x3kz63kbx2te1v7', 'PalmPay', '8144777061', 'IFEANYICHUKWU - CHIKEZIE', 100.00, 'SUCCESS', '{\"status\":true,\"message\":\"Transfer has been queued\",\"data\":{\"transfersessionid\":[],\"transfertrials\":[],\"domain\":\"live\",\"amount\":10000,\"currency\":\"NGN\",\"reference\":\"WD_68ab1a25a7d6b\",\"source\":\"balance\",\"source_details\":null,\"reason\":\"Wallet withdrawal\",\"status\":\"pending\",\"failures\":null,\"transfer_code\":\"TRF_9aru2wid6sh61wtc\",\"titan_code\":null,\"transferred_at\":null,\"id\":873250037,\"integration\":1129844,\"request\":1085735163,\"recipient\":110268691,\"createdAt\":\"2025-08-24T13:56:54.000Z\",\"updatedAt\":\"2025-08-24T13:56:54.000Z\"}}', '2025-08-24 13:56:59', 'SID_68AB1A2D2E440'),
(72, 75, 'WD_68ab2163495b6', 'RCP_vvrsy8qvs3qo8ed', 'OPay Digital Services Limited (OPay)', '8068204680', 'DIEMODE  SIMEON', 9000.00, 'FAILED', '{\"status\":false,\"message\":\"Your balance is not enough to fulfil this request\",\"meta\":{\"nextStep\":\"Topup your Paystack Balance and try again.\"},\"type\":\"api_error\",\"code\":\"insufficient_balance\"}', '2025-08-24 14:27:47', NULL),
(73, 75, 'WD_68ab216d80587', 'RCP_vvrsy8qvs3qo8ed', 'OPay Digital Services Limited (OPay)', '8068204680', 'DIEMODE  SIMEON', 9000.00, 'FAILED', '{\"status\":false,\"message\":\"Your balance is not enough to fulfil this request\",\"meta\":{\"nextStep\":\"Topup your Paystack Balance and try again.\"},\"type\":\"api_error\",\"code\":\"insufficient_balance\"}', '2025-08-24 14:27:58', 'SID_68AB21FDABA63'),
(74, 75, 'WD_68ab21758d27b', 'RCP_vvrsy8qvs3qo8ed', 'OPay Digital Services Limited (OPay)', '8068204680', 'DIEMODE  SIMEON', 9000.00, 'FAILED', '{\"status\":false,\"message\":\"Your balance is not enough to fulfil this request\",\"meta\":{\"nextStep\":\"Topup your Paystack Balance and try again.\"},\"type\":\"api_error\",\"code\":\"insufficient_balance\"}', '2025-08-24 14:28:06', NULL),
(75, 75, 'WD_68ab2183a3b2a', 'RCP_vvrsy8qvs3qo8ed', 'OPay Digital Services Limited (OPay)', '8068204680', 'DIEMODE  SIMEON', 9000.00, 'FAILED', '{\"status\":false,\"message\":\"Your balance is not enough to fulfil this request\",\"meta\":{\"nextStep\":\"Topup your Paystack Balance and try again.\"},\"type\":\"api_error\",\"code\":\"insufficient_balance\"}', '2025-08-24 14:28:20', 'SID_68AB218A8BB78'),
(76, 75, 'WD_68ab21cc76e13', 'RCP_b1c626iru08r2u7', 'PalmPay', '8148622359', 'THEO DESMOND NWOGU', 10000.00, 'FAILED', '{\"status\":false,\"message\":\"Your balance is not enough to fulfil this request\",\"meta\":{\"nextStep\":\"Topup your Paystack Balance and try again.\"},\"type\":\"api_error\",\"code\":\"insufficient_balance\"}', '2025-08-24 14:29:32', NULL),
(77, 75, 'WD_68ab21cce3e41', 'RCP_b1c626iru08r2u7', 'PalmPay', '8148622359', 'THEO DESMOND NWOGU', 10000.00, 'FAILED', '{\"status\":false,\"message\":\"Your balance is not enough to fulfil this request\",\"meta\":{\"nextStep\":\"Topup your Paystack Balance and try again.\"},\"type\":\"api_error\",\"code\":\"insufficient_balance\"}', '2025-08-24 14:29:33', NULL),
(78, 75, 'WD_68ab21d380cb8', 'RCP_b1c626iru08r2u7', 'PalmPay', '8148622359', 'THEO DESMOND NWOGU', 10000.00, 'FAILED', '{\"status\":false,\"message\":\"Your balance is not enough to fulfil this request\",\"meta\":{\"nextStep\":\"Topup your Paystack Balance and try again.\"},\"type\":\"api_error\",\"code\":\"insufficient_balance\"}', '2025-08-24 14:29:40', NULL),
(79, 75, 'WD_68ab3c21d9a6c', 'RCP_q80q0jkt8wm1r85', 'OPay Digital Services Limited (OPay)', '8148622359', 'THEO DESMOND NWOGU', 700000.00, 'SUCCESS', '{\"mode\":\"direct\",\"message\":\"Withdrawal marked successful without Paystack balance\"}', '2025-08-24 16:21:54', 'SID_68AB3C23B7787'),
(80, 75, 'WD_68ab3c47d5fba', 'RCP_b1c626iru08r2u7', 'PalmPay', '8148622359', 'THEO DESMOND NWOGU', 1000.00, 'FAILED', '{\"status\":false,\"message\":\"Invalid key\",\"meta\":{\"nextStep\":\"Ensure that you provide the correct authorization key for the request\"},\"type\":\"validation_error\",\"code\":\"invalid_Key\"}', '2025-08-24 16:22:32', NULL),
(81, 85, 'WD_68ab3cbd8a280', 'RCP_x3kz63kbx2te1v7', 'PalmPay', '8144777061', 'IFEANYICHUKWU - CHIKEZIE', 100000.00, 'SUCCESS', '{\"mode\":\"direct\",\"message\":\"Withdrawal marked successful without Paystack balance\"}', '2025-08-24 16:24:29', 'SID_68AB3CBF1B403');
INSERT INTO `user_withdrawals` (`id`, `user_id`, `reference`, `recipient_code`, `bank_name`, `account_number`, `account_name`, `amount`, `status`, `response`, `created_at`, `session_id`) VALUES
(82, 85, 'WD_68ab4e445f550', 'RCP_hdeelkqu2ji4s8g', 'OPay Digital Services Limited (OPay)', '7035118040', 'EMEM EDEM ISIN', 100000.00, 'SUCCESS', '{\"mode\":\"direct\",\"message\":\"Withdrawal marked successful without Paystack balance\"}', '2025-08-24 17:39:16', 'SID_68AB4E461A222'),
(83, 85, 'WD_68ab51837c750', 'RCP_x3kz63kbx2te1v7', 'PalmPay', '8144777061', 'IFEANYICHUKWU - CHIKEZIE', 100.00, 'FAILED', '{\"status\":false,\"message\":\"Invalid key\",\"meta\":{\"nextStep\":\"Ensure that you provide the correct authorization key for the request\"},\"type\":\"validation_error\",\"code\":\"invalid_Key\"}', '2025-08-24 17:53:07', NULL),
(84, 85, 'WD_68ab51a1d983f', 'RCP_x3kz63kbx2te1v7', 'PalmPay', '8144777061', 'IFEANYICHUKWU - CHIKEZIE', 100.00, 'FAILED', '{\"status\":false,\"message\":\"Invalid key\",\"meta\":{\"nextStep\":\"Ensure that you provide the correct authorization key for the request\"},\"type\":\"validation_error\",\"code\":\"invalid_Key\"}', '2025-08-24 17:53:38', NULL),
(85, 85, 'WD_68ab51b83847e', 'RCP_x3kz63kbx2te1v7', 'PalmPay', '8144777061', 'IFEANYICHUKWU - CHIKEZIE', 100.00, 'FAILED', '{\"status\":false,\"message\":\"Invalid key\",\"meta\":{\"nextStep\":\"Ensure that you provide the correct authorization key for the request\"},\"type\":\"validation_error\",\"code\":\"invalid_Key\"}', '2025-08-24 17:54:00', NULL),
(86, 85, 'WD_68ab51e64efb9', 'RCP_x3kz63kbx2te1v7', 'PalmPay', '8144777061', 'IFEANYICHUKWU - CHIKEZIE', 100.00, 'FAILED', '{\"status\":false,\"message\":\"Invalid key\",\"meta\":{\"nextStep\":\"Ensure that you provide the correct authorization key for the request\"},\"type\":\"validation_error\",\"code\":\"invalid_Key\"}', '2025-08-24 17:54:46', NULL),
(87, 85, 'WD_68ab51f34a549', 'RCP_x3kz63kbx2te1v7', 'PalmPay', '8144777061', 'IFEANYICHUKWU - CHIKEZIE', 100.00, 'SUCCESS', '{\"mode\":\"direct\",\"message\":\"Withdrawal marked successful without Paystack balance\"}', '2025-08-24 17:54:59', 'SID_68AB51F4D08B0'),
(88, 85, 'WD_68ab527bc4286', 'RCP_x3kz63kbx2te1v7', 'PalmPay', '8144777061', 'IFEANYICHUKWU - CHIKEZIE', 100.00, 'FAILED', '{\"status\":false,\"message\":\"Invalid key\",\"meta\":{\"nextStep\":\"Ensure that you provide the correct authorization key for the request\"},\"type\":\"validation_error\",\"code\":\"invalid_Key\"}', '2025-08-24 17:57:16', NULL),
(89, 85, 'WD_68ab52892e022', 'RCP_x3kz63kbx2te1v7', 'PalmPay', '8144777061', 'IFEANYICHUKWU - CHIKEZIE', 100.00, 'FAILED', '{\"status\":false,\"message\":\"Invalid key\",\"meta\":{\"nextStep\":\"Ensure that you provide the correct authorization key for the request\"},\"type\":\"validation_error\",\"code\":\"invalid_Key\"}', '2025-08-24 17:57:29', NULL),
(90, 85, 'WD_68ab56b82b159', 'RCP_ow5a6kbqm9eil9u', 'Moniepoint MFB', '5513292370', 'MUHAMMED ADO', 4000.00, 'SUCCESS', '{\"mode\":\"direct\",\"message\":\"Withdrawal marked successful without Paystack balance\"}', '2025-08-24 18:15:20', 'SID_68AB56B9AC88B'),
(91, 85, 'WD_68ab5bacda25d', 'RCP_konfl88ik2asvsn', 'Moniepoint MFB', '7030758566', 'PROSPER NEWYEAR', 1300.00, 'SUCCESS', '{\"mode\":\"direct\",\"message\":\"Withdrawal marked successful without Paystack balance\"}', '2025-08-24 18:36:29', 'SID_68AB5BAE77FEB'),
(92, 85, 'WD_68ac2ae57ea58', 'RCP_lmjqcv0iqxrq2it', 'OPay Digital Services Limited (OPay)', '7049546573', 'WOYENGITONBARA MICHAEL AKAH', 4050.00, 'SUCCESS', '{\"mode\":\"direct\",\"message\":\"Withdrawal marked successful without Paystack balance\"}', '2025-08-25 09:20:37', 'SID_68AC2AE71A423'),
(93, 75, 'WD_68ac86f7d0bae', 'RCP_b1c626iru08r2u7', 'PalmPay', '8148622359', 'THEO DESMOND NWOGU', 500.00, 'FAILED', '{\"status\":false,\"message\":\"Invalid key\",\"meta\":{\"nextStep\":\"Ensure that you provide the correct authorization key for the request\"},\"type\":\"validation_error\",\"code\":\"invalid_Key\"}', '2025-08-25 15:53:28', NULL),
(94, 75, 'WD_68ac8703f3c9e', 'RCP_b1c626iru08r2u7', 'PalmPay', '8148622359', 'THEO DESMOND NWOGU', 500.00, 'FAILED', '{\"status\":false,\"message\":\"Invalid key\",\"meta\":{\"nextStep\":\"Ensure that you provide the correct authorization key for the request\"},\"type\":\"validation_error\",\"code\":\"invalid_Key\"}', '2025-08-25 15:53:40', NULL),
(95, 75, 'WD_68ac879d77bdc', 'RCP_b1c626iru08r2u7', 'PalmPay', '8148622359', 'THEO DESMOND NWOGU', 600.00, 'FAILED', '{\"status\":false,\"message\":\"Invalid key\",\"meta\":{\"nextStep\":\"Ensure that you provide the correct authorization key for the request\"},\"type\":\"validation_error\",\"code\":\"invalid_Key\"}', '2025-08-25 15:56:13', 'SID_68ACC74E8D5AD'),
(96, 75, 'WD_68acc706a34a5', 'RCP_5jtjeoax6r0qe5l', 'PalmPay', '8169182889', 'JOHN FEIBUA EBIKEFEI', 1500000.00, 'SUCCESS', '{\"mode\":\"direct\",\"message\":\"Withdrawal marked successful without Paystack balance\"}', '2025-08-25 20:26:47', 'SID_68ACC70849A42'),
(97, 75, 'WD_68ad93d10027a', 'RCP_b1c626iru08r2u7', 'PalmPay', '8148622359', 'THEO DESMOND NWOGU', 1000.00, 'FAILED', '{\"status\":false,\"message\":\"Invalid key\",\"meta\":{\"nextStep\":\"Ensure that you provide the correct authorization key for the request\"},\"type\":\"validation_error\",\"code\":\"invalid_Key\"}', '2025-08-26 11:00:33', NULL),
(98, 75, 'WD_68ad93d1baad3', 'RCP_b1c626iru08r2u7', 'PalmPay', '8148622359', 'THEO DESMOND NWOGU', 1000.00, 'FAILED', '{\"status\":false,\"message\":\"Invalid key\",\"meta\":{\"nextStep\":\"Ensure that you provide the correct authorization key for the request\"},\"type\":\"validation_error\",\"code\":\"invalid_Key\"}', '2025-08-26 11:00:34', NULL),
(99, 75, 'WD_68ad93d26d1c3', 'RCP_b1c626iru08r2u7', 'PalmPay', '8148622359', 'THEO DESMOND NWOGU', 1000.00, 'FAILED', '{\"status\":false,\"message\":\"Invalid key\",\"meta\":{\"nextStep\":\"Ensure that you provide the correct authorization key for the request\"},\"type\":\"validation_error\",\"code\":\"invalid_Key\"}', '2025-08-26 11:00:35', NULL),
(100, 75, 'WD_68ad93d306a41', 'RCP_b1c626iru08r2u7', 'PalmPay', '8148622359', 'THEO DESMOND NWOGU', 1000.00, 'FAILED', '{\"status\":false,\"message\":\"Invalid key\",\"meta\":{\"nextStep\":\"Ensure that you provide the correct authorization key for the request\"},\"type\":\"validation_error\",\"code\":\"invalid_Key\"}', '2025-08-26 11:00:35', NULL),
(101, 75, 'WD_68ad93d3bba65', 'RCP_b1c626iru08r2u7', 'PalmPay', '8148622359', 'THEO DESMOND NWOGU', 1000.00, 'FAILED', '{\"status\":false,\"message\":\"Invalid key\",\"meta\":{\"nextStep\":\"Ensure that you provide the correct authorization key for the request\"},\"type\":\"validation_error\",\"code\":\"invalid_Key\"}', '2025-08-26 11:00:36', NULL),
(102, 75, 'WD_68ad93d45364b', 'RCP_b1c626iru08r2u7', 'PalmPay', '8148622359', 'THEO DESMOND NWOGU', 1000.00, 'FAILED', '{\"status\":false,\"message\":\"Invalid key\",\"meta\":{\"nextStep\":\"Ensure that you provide the correct authorization key for the request\"},\"type\":\"validation_error\",\"code\":\"invalid_Key\"}', '2025-08-26 11:00:36', NULL),
(103, 75, 'WD_68ad93d4d19ec', 'RCP_b1c626iru08r2u7', 'PalmPay', '8148622359', 'THEO DESMOND NWOGU', 1000.00, 'FAILED', '{\"status\":false,\"message\":\"Invalid key\",\"meta\":{\"nextStep\":\"Ensure that you provide the correct authorization key for the request\"},\"type\":\"validation_error\",\"code\":\"invalid_Key\"}', '2025-08-26 11:00:37', NULL),
(104, 75, 'WD_68ad93d7296b0', 'RCP_b1c626iru08r2u7', 'PalmPay', '8148622359', 'THEO DESMOND NWOGU', 1000.00, 'FAILED', '{\"status\":false,\"message\":\"Invalid key\",\"meta\":{\"nextStep\":\"Ensure that you provide the correct authorization key for the request\"},\"type\":\"validation_error\",\"code\":\"invalid_Key\"}', '2025-08-26 11:00:39', NULL),
(105, 85, 'WD_68af18fccd36d', 'RCP_x3kz63kbx2te1v7', 'PalmPay', '8144777061', 'IFEANYICHUKWU - CHIKEZIE', 100.00, 'FAILED', '{\"status\":false,\"message\":\"Invalid key\",\"meta\":{\"nextStep\":\"Ensure that you provide the correct authorization key for the request\"},\"type\":\"validation_error\",\"code\":\"invalid_Key\"}', '2025-08-27 14:41:01', NULL),
(106, 85, 'WD_68af190bf3559', 'RCP_x3kz63kbx2te1v7', 'PalmPay', '8144777061', 'IFEANYICHUKWU - CHIKEZIE', 100.00, 'FAILED', '{\"status\":false,\"message\":\"Invalid key\",\"meta\":{\"nextStep\":\"Ensure that you provide the correct authorization key for the request\"},\"type\":\"validation_error\",\"code\":\"invalid_Key\"}', '2025-08-27 14:41:16', NULL),
(107, 85, 'WD_68af673ec797d', 'RCP_x3kz63kbx2te1v7', 'PalmPay', '8144777061', 'IFEANYICHUKWU - CHIKEZIE', 500000.00, 'FAILED', '{\"status\":false,\"message\":\"Invalid key\",\"meta\":{\"nextStep\":\"Ensure that you provide the correct authorization key for the request\"},\"type\":\"validation_error\",\"code\":\"invalid_Key\"}', '2025-08-27 20:14:55', NULL),
(108, 85, 'WD_68af674919484', 'RCP_x3kz63kbx2te1v7', 'PalmPay', '8144777061', 'IFEANYICHUKWU - CHIKEZIE', 500000.00, 'FAILED', '{\"status\":false,\"message\":\"Invalid key\",\"meta\":{\"nextStep\":\"Ensure that you provide the correct authorization key for the request\"},\"type\":\"validation_error\",\"code\":\"invalid_Key\"}', '2025-08-27 20:15:05', NULL),
(109, 85, 'WD_68b06c9a9e0bb', 'RCP_x3kz63kbx2te1v7', 'PalmPay', '8144777061', 'IFEANYICHUKWU - CHIKEZIE', 500000.00, 'FAILED', '{\"status\":false,\"message\":\"Invalid key\",\"meta\":{\"nextStep\":\"Ensure that you provide the correct authorization key for the request\"},\"type\":\"validation_error\",\"code\":\"invalid_Key\"}', '2025-08-28 14:50:03', NULL),
(110, 85, 'WD_68b17ec559590', 'RCP_f4wla5cthjvijfa', 'PalmPay', '7037233381', 'FUNMI  EMMANUEL', 20000.00, 'SUCCESS', '{\"mode\":\"direct\",\"message\":\"Withdrawal marked successful without Paystack balance\"}', '2025-08-29 10:19:49', 'SID_68B17EC6E3C6B'),
(111, 85, 'WD_68b471279cdb9', 'RCP_x3kz63kbx2te1v7', 'PalmPay', '8144777061', 'IFEANYICHUKWU - CHIKEZIE', 100000.00, 'FAILED', '{\"status\":false,\"message\":\"Invalid key\",\"meta\":{\"nextStep\":\"Ensure that you provide the correct authorization key for the request\"},\"type\":\"validation_error\",\"code\":\"invalid_Key\"}', '2025-08-31 15:58:32', NULL),
(112, 85, 'WD_68b471348bc6c', 'RCP_x3kz63kbx2te1v7', 'PalmPay', '8144777061', 'IFEANYICHUKWU - CHIKEZIE', 100000.00, 'FAILED', '{\"status\":false,\"message\":\"Invalid key\",\"meta\":{\"nextStep\":\"Ensure that you provide the correct authorization key for the request\"},\"type\":\"validation_error\",\"code\":\"invalid_Key\"}', '2025-08-31 15:58:45', NULL),
(113, 75, 'WD_68b890420c053', 'RCP_b1c626iru08r2u7', 'PalmPay', '8148622359', 'THEO DESMOND NWOGU', 20000.00, 'SUCCESS', '{\"mode\":\"direct\",\"message\":\"Withdrawal marked successful without Paystack balance\"}', '2025-09-03 19:00:18', 'SID_68B89043CC4E1'),
(114, 75, 'WD_68b8af0ca00a1', 'RCP_wkmnwllxyv8ene2', 'Moniepoint MFB', '5043826579', 'POS Transfer-OMAJI MARY OCHUOULE', 17000.00, 'SUCCESS', '{\"mode\":\"direct\",\"message\":\"Withdrawal marked successful without Paystack balance\"}', '2025-09-03 21:11:40', 'SID_68B8AF0E2F4DF'),
(115, 75, 'WD_68bacdf13a273', 'RCP_b1c626iru08r2u7', 'PalmPay', '8148622359', 'THEO DESMOND NWOGU', 200.00, 'FAILED', '{\"status\":false,\"message\":\"Invalid key\",\"meta\":{\"nextStep\":\"Ensure that you provide the correct authorization key for the request\"},\"type\":\"validation_error\",\"code\":\"invalid_Key\"}', '2025-09-05 11:48:01', NULL),
(116, 75, 'WD_68bacdfca587a', 'RCP_b1c626iru08r2u7', 'PalmPay', '8148622359', 'THEO DESMOND NWOGU', 150.00, 'FAILED', '{\"status\":false,\"message\":\"Invalid key\",\"meta\":{\"nextStep\":\"Ensure that you provide the correct authorization key for the request\"},\"type\":\"validation_error\",\"code\":\"invalid_Key\"}', '2025-09-05 11:48:13', NULL),
(117, 85, 'WD_68bdebb657494', 'RCP_x3kz63kbx2te1v7', 'PalmPay', '8144777061', 'IFEANYICHUKWU - CHIKEZIE', 1000000.00, 'FAILED', '{\"status\":false,\"message\":\"Invalid key\",\"meta\":{\"nextStep\":\"Ensure that you provide the correct authorization key for the request\"},\"type\":\"validation_error\",\"code\":\"invalid_Key\"}', '2025-09-07 20:31:50', NULL),
(118, 85, 'WD_68bdebbea1046', 'RCP_x3kz63kbx2te1v7', 'PalmPay', '8144777061', 'IFEANYICHUKWU - CHIKEZIE', 1000000.00, 'FAILED', '{\"status\":false,\"message\":\"Invalid key\",\"meta\":{\"nextStep\":\"Ensure that you provide the correct authorization key for the request\"},\"type\":\"validation_error\",\"code\":\"invalid_Key\"}', '2025-09-07 20:31:59', NULL),
(119, 75, 'WD_68bf5830cc001', 'RCP_b1c626iru08r2u7', 'PalmPay', '8148622359', 'THEO DESMOND NWOGU', 100.00, 'FAILED', '{\"status\":false,\"message\":\"Invalid key\",\"meta\":{\"nextStep\":\"Ensure that you provide the correct authorization key for the request\"},\"type\":\"validation_error\",\"code\":\"invalid_Key\"}', '2025-09-08 22:26:57', 'SID_68C1AB07C81C5'),
(120, 75, 'WD_68c1af2952f6b', 'RCP_b1c626iru08r2u7', 'PalmPay', '8148622359', 'THEO DESMOND NWOGU', 100.00, 'FAILED', '{\"status\":false,\"message\":\"Invalid key\",\"meta\":{\"nextStep\":\"Ensure that you provide the correct authorization key for the request\"},\"type\":\"validation_error\",\"code\":\"invalid_Key\"}', '2025-09-10 17:02:33', NULL),
(121, 75, 'WD_68c1af3673206', 'RCP_b1c626iru08r2u7', 'PalmPay', '8148622359', 'THEO DESMOND NWOGU', 100.00, 'FAILED', '{\"status\":false,\"message\":\"Invalid key\",\"meta\":{\"nextStep\":\"Ensure that you provide the correct authorization key for the request\"},\"type\":\"validation_error\",\"code\":\"invalid_Key\"}', '2025-09-10 17:02:47', NULL),
(122, 75, 'WD_68c1b0cd916de', 'RCP_b1c626iru08r2u7', 'PalmPay', '8148622359', 'THEO DESMOND NWOGU', 100.00, 'FAILED', '{\"status\":false,\"message\":\"You cannot initiate third party payouts at this time\",\"meta\":{\"nextStep\":\"Try again later\"},\"type\":\"api_error\",\"code\":\"unknown\"}', '2025-09-10 17:09:34', 'SID_68D32E847E1E1'),
(123, 75, 'WD_68c1b22a030bd', 'RCP_q80q0jkt8wm1r85', 'OPay Digital Services Limited (OPay)', '8148622359', 'THEO DESMOND NWOGU', 150.00, 'SUCCESS', '{\"mode\":\"direct\",\"message\":\"Withdrawal marked successful without Paystack balance\"}', '2025-09-10 17:15:22', 'SID_68C1B22BA397A'),
(124, 75, 'WD_68c1b2413de6c', 'RCP_q80q0jkt8wm1r85', 'OPay Digital Services Limited (OPay)', '8148622359', 'THEO DESMOND NWOGU', 200.00, 'FAILED', '{\"status\":false,\"message\":\"You cannot initiate third party payouts at this time\",\"meta\":{\"nextStep\":\"Try again later\"},\"type\":\"api_error\",\"code\":\"unknown\"}', '2025-09-10 17:15:45', NULL),
(125, 75, 'WD_68c4784aac828', 'RCP_q80q0jkt8wm1r85', 'OPay Digital Services Limited (OPay)', '8148622359', 'THEO DESMOND NWOGU', 200.00, 'SUCCESS', '{\"status\":true,\"message\":\"Transfer has been queued\",\"data\":{\"transfersessionid\":[],\"transfertrials\":[],\"domain\":\"live\",\"amount\":20000,\"currency\":\"NGN\",\"reference\":\"WD_68c4784aac828\",\"source\":\"balance\",\"source_details\":null,\"reason\":\"Wallet withdrawal\",\"status\":\"pending\",\"failures\":null,\"transfer_code\":\"TRF_yu65gp760mnogq2p\",\"titan_code\":null,\"transferred_at\":null,\"id\":882927235,\"integration\":1129844,\"request\":1100673239,\"recipient\":105357952,\"createdAt\":\"2025-09-12T19:45:15.000Z\",\"updatedAt\":\"2025-09-12T19:45:15.000Z\"}}', '2025-09-12 19:45:17', 'SID_68C4784EADFBB'),
(126, 75, 'WD_68c478b1ca471', 'RCP_qqesimvar1ekrc0', 'OPay Digital Services Limited (OPay)', '8164429217', 'EBIPAPRE RICHARD AMGBA', 200.00, 'SUCCESS', '{\"status\":true,\"message\":\"Transfer has been queued\",\"data\":{\"transfersessionid\":[],\"transfertrials\":[],\"domain\":\"live\",\"amount\":20000,\"currency\":\"NGN\",\"reference\":\"WD_68c478b1ca471\",\"source\":\"balance\",\"source_details\":null,\"reason\":\"Wallet withdrawal\",\"status\":\"pending\",\"failures\":null,\"transfer_code\":\"TRF_sofn0pmn9y897wu5\",\"titan_code\":null,\"transferred_at\":null,\"id\":882927842,\"integration\":1129844,\"request\":1100674105,\"recipient\":111496303,\"createdAt\":\"2025-09-12T19:46:58.000Z\",\"updatedAt\":\"2025-09-12T19:46:58.000Z\"}}', '2025-09-12 19:47:00', 'SID_68C478B6093BF'),
(127, 75, 'WD_68c6e32ea390a', 'RCP_b1c626iru08r2u7', 'PalmPay', '8148622359', 'THEO DESMOND NWOGU', 100.00, 'SUCCESS', '{\"status\":true,\"message\":\"Transfer has been queued\",\"data\":{\"transfersessionid\":[],\"transfertrials\":[],\"domain\":\"live\",\"amount\":10000,\"currency\":\"NGN\",\"reference\":\"WD_68c6e32ea390a\",\"source\":\"balance\",\"source_details\":null,\"reason\":\"Wallet withdrawal\",\"status\":\"pending\",\"failures\":null,\"transfer_code\":\"TRF_5hvlygmrs0bou8h4\",\"titan_code\":null,\"transferred_at\":null,\"id\":883849755,\"integration\":1129844,\"request\":1102058443,\"recipient\":96994529,\"createdAt\":\"2025-09-14T15:45:51.000Z\",\"updatedAt\":\"2025-09-14T15:45:51.000Z\"}}', '2025-09-14 15:45:53', 'SID_68C6E332F2503'),
(128, 75, 'WD_68c960de7ffaa', 'RCP_b1c626iru08r2u7', 'PalmPay', '8148622359', 'THEO DESMOND NWOGU', 100.00, 'SUCCESS', '{\"status\":true,\"message\":\"Transfer has been queued\",\"data\":{\"transfersessionid\":[],\"transfertrials\":[],\"domain\":\"live\",\"amount\":10000,\"currency\":\"NGN\",\"reference\":\"WD_68c960de7ffaa\",\"source\":\"balance\",\"source_details\":null,\"reason\":\"Wallet withdrawal\",\"status\":\"pending\",\"failures\":null,\"transfer_code\":\"TRF_463hqv0fg19rl1vw\",\"titan_code\":null,\"transferred_at\":null,\"id\":884799858,\"integration\":1129844,\"request\":1103600672,\"recipient\":96994529,\"createdAt\":\"2025-09-16T13:06:39.000Z\",\"updatedAt\":\"2025-09-16T13:06:39.000Z\"}}', '2025-09-16 13:06:42', 'SID_68C960E3CCBFB'),
(129, 75, 'WD_68c9d9a00a618', 'RCP_1cw220na2dxpdjy', 'OPay Digital Services Limited (OPay)', '8169182889', 'JOHN FEIBUA EBIKEFEI', 200.00, 'SUCCESS', '{\"status\":true,\"message\":\"Transfer has been queued\",\"data\":{\"transfersessionid\":[],\"transfertrials\":[],\"domain\":\"live\",\"amount\":20000,\"currency\":\"NGN\",\"reference\":\"WD_68c9d9a00a618\",\"source\":\"balance\",\"source_details\":null,\"reason\":\"Wallet withdrawal\",\"status\":\"pending\",\"failures\":null,\"transfer_code\":\"TRF_4n8lnk0cseabte1j\",\"titan_code\":null,\"transferred_at\":null,\"id\":885059799,\"integration\":1129844,\"request\":1103932067,\"recipient\":105751415,\"createdAt\":\"2025-09-16T21:41:53.000Z\",\"updatedAt\":\"2025-09-16T21:41:53.000Z\"}}', '2025-09-16 21:41:54', 'SID_68C9D9A3C6E3C'),
(130, 75, 'WD_68cc13304f6b3', 'RCP_orgoaykj6tsmy6i', 'OPay Digital Services Limited (OPay)', '9068336406', 'ISHMAEL AZIBAOWONI IGONIWARI', 200.00, 'SUCCESS', '{\"status\":true,\"message\":\"Transfer has been queued\",\"data\":{\"transfersessionid\":[],\"transfertrials\":[],\"domain\":\"live\",\"amount\":20000,\"currency\":\"NGN\",\"reference\":\"WD_68cc13304f6b3\",\"source\":\"balance\",\"source_details\":null,\"reason\":\"Wallet withdrawal\",\"status\":\"pending\",\"failures\":null,\"transfer_code\":\"TRF_xi9rwmurh4irplvs\",\"titan_code\":null,\"transferred_at\":null,\"id\":885841385,\"integration\":1129844,\"request\":1105179852,\"recipient\":111849152,\"createdAt\":\"2025-09-18T14:12:01.000Z\",\"updatedAt\":\"2025-09-18T14:12:01.000Z\"}}', '2025-09-18 14:12:04', 'SID_68CC13364CF4A'),
(131, 75, 'WD_68cc22d2e9051', 'RCP_orgoaykj6tsmy6i', 'OPay Digital Services Limited (OPay)', '9068336406', 'ISHMAEL AZIBAOWONI IGONIWARI', 200000.00, 'SUCCESS', '{\"mode\":\"direct\",\"message\":\"Withdrawal marked successful without Paystack balance\"}', '2025-09-18 15:18:43', 'SID_68CC22D4A4ED9'),
(132, 75, 'WD_68cef93f38087', 'RCP_h8o7pcsjdl7cxg8', 'OPay Digital Services Limited (OPay)', '8069994237', 'OYINKURO PHILIP ESELEMO', 500.00, 'SUCCESS', '{\"status\":true,\"message\":\"Transfer has been queued\",\"data\":{\"transfersessionid\":[],\"transfertrials\":[],\"domain\":\"live\",\"amount\":50000,\"currency\":\"NGN\",\"reference\":\"WD_68cef93f38087\",\"source\":\"balance\",\"source_details\":null,\"reason\":\"Wallet withdrawal\",\"status\":\"pending\",\"failures\":null,\"transfer_code\":\"TRF_iqo25bq444kp8tz2\",\"titan_code\":null,\"transferred_at\":null,\"id\":887096205,\"integration\":1129844,\"request\":1107021491,\"recipient\":111990898,\"createdAt\":\"2025-09-20T18:58:08.000Z\",\"updatedAt\":\"2025-09-20T18:58:08.000Z\"}}', '2025-09-20 18:58:09', 'SID_68CEF942CF4FC'),
(133, 87, 'WD_68d290c377e50', 'RCP_q80q0jkt8wm1r85', 'OPay Digital Services Limited (OPay)', '8148622359', 'THEO DESMOND NWOGU', 50.00, 'SUCCESS', '{\"status\":true,\"message\":\"Transfer has been queued\",\"data\":{\"transfersessionid\":[],\"transfertrials\":[],\"domain\":\"live\",\"amount\":5000,\"currency\":\"NGN\",\"reference\":\"WD_68d290c377e50\",\"source\":\"balance\",\"source_details\":null,\"reason\":\"Wallet withdrawal\",\"status\":\"pending\",\"failures\":null,\"transfer_code\":\"TRF_s1p36gpg07vwjynh\",\"titan_code\":null,\"transferred_at\":null,\"id\":888652047,\"integration\":1129844,\"request\":1109414577,\"recipient\":105357952,\"createdAt\":\"2025-09-23T12:21:24.000Z\",\"updatedAt\":\"2025-09-23T12:21:24.000Z\"}}', '2025-09-23 12:21:27', 'SID_68D290C8A61D5'),
(134, 75, 'WD_68d2d17010e13', 'RCP_b1c626iru08r2u7', 'PalmPay', '8148622359', 'THEO DESMOND NWOGU', 1500.00, 'SUCCESS', '{\"status\":true,\"message\":\"Transfer has been queued\",\"data\":{\"transfersessionid\":[],\"transfertrials\":[],\"domain\":\"live\",\"amount\":150000,\"currency\":\"NGN\",\"reference\":\"WD_68d2d17010e13\",\"source\":\"balance\",\"source_details\":null,\"reason\":\"Wallet withdrawal\",\"status\":\"pending\",\"failures\":null,\"transfer_code\":\"TRF_wap0fzswaxdm32m9\",\"titan_code\":null,\"transferred_at\":null,\"id\":888757643,\"integration\":1129844,\"request\":1109558665,\"recipient\":96994529,\"createdAt\":\"2025-09-23T16:57:21.000Z\",\"updatedAt\":\"2025-09-23T16:57:21.000Z\"}}', '2025-09-23 16:57:26', 'SID_68D2D1785613A'),
(135, 85, 'WD_68d2e2919c237', 'RCP_ew19bdvpf0ty5tk', 'OPay Digital Services Limited (OPay)', '7088457483', 'EVANS MIMIKIZIBE GODSTIME', 5000.00, 'SUCCESS', '{\"mode\":\"direct\",\"message\":\"Withdrawal marked successful without Paystack balance\"}', '2025-09-23 18:10:26', 'SID_68D2E2936D85F'),
(136, 75, 'WD_68dae8494d1b0', 'RCP_dume0830b2j8po9', 'First Bank of Nigeria', '3136890547', 'BERNARD OYINDOUBRA RUTH', 500.00, 'SUCCESS', '{\"status\":true,\"message\":\"Transfer has been queued\",\"data\":{\"transfersessionid\":[],\"transfertrials\":[],\"domain\":\"live\",\"amount\":50000,\"currency\":\"NGN\",\"reference\":\"WD_68dae8494d1b0\",\"source\":\"balance\",\"source_details\":null,\"reason\":\"Wallet withdrawal\",\"status\":\"pending\",\"failures\":null,\"transfer_code\":\"TRF_bienqlhkopo8lryq\",\"titan_code\":null,\"transferred_at\":null,\"id\":891875487,\"integration\":1129844,\"request\":1114377043,\"recipient\":112551101,\"createdAt\":\"2025-09-29T20:12:58.000Z\",\"updatedAt\":\"2025-09-29T20:12:58.000Z\"}}', '2025-09-29 20:13:00', 'SID_68DAE84E1085C'),
(137, 75, 'WD_68db33144b524', 'RCP_dume0830b2j8po9', 'First Bank of Nigeria', '3136890547', 'BERNARD OYINDOUBRA RUTH', 550000.00, 'SUCCESS', '{\"mode\":\"direct\",\"message\":\"Withdrawal marked successful without Paystack balance\"}', '2025-09-30 01:32:04', 'SID_68DB331602EB6'),
(138, 75, 'WD_68dfd5c563d9a', 'RCP_p28eghbzzekjg94', 'Wema Bank', '0232474420', 'EREWARI TARIBARALATE ALFRED', 500.00, 'SUCCESS', '{\"status\":true,\"message\":\"Transfer has been queued\",\"data\":{\"transfersessionid\":[],\"transfertrials\":[],\"domain\":\"live\",\"amount\":50000,\"currency\":\"NGN\",\"reference\":\"WD_68dfd5c563d9a\",\"source\":\"balance\",\"source_details\":null,\"reason\":\"Wallet withdrawal\",\"status\":\"pending\",\"failures\":null,\"transfer_code\":\"TRF_giduwz04kd0abb0n\",\"titan_code\":null,\"transferred_at\":null,\"id\":893919192,\"integration\":1129844,\"request\":1117502798,\"recipient\":112784633,\"createdAt\":\"2025-10-03T13:55:18.000Z\",\"updatedAt\":\"2025-10-03T13:55:18.000Z\"}}', '2025-10-03 13:55:19', 'SID_68DFD5C9564FA'),
(139, 85, 'WD_68eba4cc35966', 'RCP_j25pfnmcmho7s0c', 'Access Bank', '1839922866', 'HOUSE ON THE MOUNTAIN MINISTRY', 50000.00, 'FAILED', '{\"status\":false,\"message\":\"Your balance is not enough to fulfil this request\",\"meta\":{\"nextStep\":\"Topup your Paystack Balance and try again.\"},\"type\":\"api_error\",\"code\":\"insufficient_balance\"}', '2025-10-12 12:53:32', NULL),
(140, 85, 'WD_68eba4e5349f8', 'RCP_j25pfnmcmho7s0c', 'Access Bank', '1839922866', 'HOUSE ON THE MOUNTAIN MINISTRY', 50000.00, 'SUCCESS', '{\"mode\":\"direct\",\"message\":\"Withdrawal marked successful without Paystack balance\"}', '2025-10-12 12:53:57', 'SID_68EBA4E6EFCA3'),
(141, 85, 'WD_68eba5480d6d8', 'RCP_x1ph8y0nn5buajl', 'Access Bank', '0711132436', 'JOSHUA OLUWASEUN ADENIJI', 50000.00, 'SUCCESS', '{\"mode\":\"direct\",\"message\":\"Withdrawal marked successful without Paystack balance\"}', '2025-10-12 12:55:36', 'SID_68EBA549D4ED1'),
(142, 85, 'WD_68eba5fe81714', 'RCP_22fbw0jgx1jdr7c', 'First City Monument Bank', '8588221012', 'CHRIST APOSTOLIC CHURCH, MOUNTAIN OF COMFORT(FS) ASSOCIATION -REMOYE AKOWONJO', 50000.00, 'SUCCESS', '{\"mode\":\"direct\",\"message\":\"Withdrawal marked successful without Paystack balance\"}', '2025-10-12 12:58:38', 'SID_68EBA60038CE8'),
(143, 85, 'WD_68eba80b6058c', 'RCP_soufxap3nsh0vg1', 'Ecobank Nigeria', '4252010304', 'MOUNTAIN OF FIRE AND MIRACLE MIN. PRAYER CITY', 50000.00, 'SUCCESS', '{\"mode\":\"direct\",\"message\":\"Withdrawal marked successful without Paystack balance\"}', '2025-10-12 13:07:23', 'SID_68EBA80D1BD4D'),
(144, 85, 'WD_68ecdd4aca758', 'RCP_8994s3ozlql8fbo', 'Ecobank Nigeria', '4803010366', 'OBUKENI  ESTHER', 5700.00, 'SUCCESS', '{\"mode\":\"direct\",\"message\":\"Withdrawal marked successful without Paystack balance\"}', '2025-10-13 11:06:51', 'SID_68ECDD4C6A150'),
(145, 75, 'WD_68ee673ccc3b6', 'RCP_maxb4w89ul968tx', 'OPay Digital Services Limited (OPay)', '8108234010', 'BRIGHT  CHRISTOPHER', 5000000.00, 'SUCCESS', '{\"mode\":\"direct\",\"message\":\"Withdrawal marked successful without Paystack balance\"}', '2025-10-14 15:07:41', 'SID_68EE673E65B0F'),
(146, 85, 'WD_68ee9500531e9', 'RCP_b7t8ocdjdz8x92x', 'Moniepoint MFB', '7046145430', 'LAMI JENNIFER ALANYI', 20000.00, 'SUCCESS', '{\"mode\":\"direct\",\"message\":\"Withdrawal marked successful without Paystack balance\"}', '2025-10-14 18:22:56', 'SID_68EE95020F223'),
(147, 75, 'WD_68efe4ace615d', 'RCP_b1c626iru08r2u7', 'PalmPay', '8148622359', 'THEO DESMOND NWOGU', 1500.00, 'FAILED', '{\"status\":false,\"message\":\"Your balance is not enough to fulfil this request\",\"meta\":{\"nextStep\":\"Topup your Paystack Balance and try again.\"},\"type\":\"api_error\",\"code\":\"insufficient_balance\"}', '2025-10-15 18:15:09', NULL),
(148, 75, 'WD_68efe4b7f1985', 'RCP_b1c626iru08r2u7', 'PalmPay', '8148622359', 'THEO DESMOND NWOGU', 1000.00, 'SUCCESS', '{\"status\":true,\"message\":\"Transfer has been queued\",\"data\":{\"transfersessionid\":[],\"transfertrials\":[],\"domain\":\"live\",\"amount\":100000,\"currency\":\"NGN\",\"reference\":\"WD_68efe4b7f1985\",\"source\":\"balance\",\"source_details\":null,\"reason\":\"Wallet withdrawal\",\"status\":\"pending\",\"failures\":null,\"transfer_code\":\"TRF_ho3qbyggjkcv9eme\",\"titan_code\":null,\"transferred_at\":null,\"id\":899515607,\"integration\":1129844,\"request\":1126244783,\"recipient\":96994529,\"createdAt\":\"2025-10-15T18:15:21.000Z\",\"updatedAt\":\"2025-10-15T18:15:21.000Z\"}}', '2025-10-15 18:15:23', 'SID_68EFE4BCB518E'),
(149, 75, 'WD_68efe4d24b10d', 'RCP_b1c626iru08r2u7', 'PalmPay', '8148622359', 'THEO DESMOND NWOGU', 300.00, 'SUCCESS', '{\"status\":true,\"message\":\"Transfer has been queued\",\"data\":{\"transfersessionid\":[],\"transfertrials\":[],\"domain\":\"live\",\"amount\":30000,\"currency\":\"NGN\",\"reference\":\"WD_68efe4d24b10d\",\"source\":\"balance\",\"source_details\":null,\"reason\":\"Wallet withdrawal\",\"status\":\"pending\",\"failures\":null,\"transfer_code\":\"TRF_p16yteqvfyaejbsa\",\"titan_code\":null,\"transferred_at\":null,\"id\":899515753,\"integration\":1129844,\"request\":1126244957,\"recipient\":96994529,\"createdAt\":\"2025-10-15T18:15:47.000Z\",\"updatedAt\":\"2025-10-15T18:15:47.000Z\"}}', '2025-10-15 18:15:50', 'SID_68EFE4D791D67'),
(150, 75, 'WD_68f5dc61921e2', 'RCP_o7fvaerxqkji93f', 'OPay Digital Services Limited (OPay)', '9066625946', 'SAMUEL UGOCHUKWU OBAOKORIE', 500.00, 'SUCCESS', '{\"status\":true,\"message\":\"Transfer has been queued\",\"data\":{\"transfersessionid\":[],\"transfertrials\":[],\"domain\":\"live\",\"amount\":50000,\"currency\":\"NGN\",\"reference\":\"WD_68f5dc61921e2\",\"source\":\"balance\",\"source_details\":null,\"reason\":\"Wallet withdrawal\",\"status\":\"pending\",\"failures\":null,\"transfer_code\":\"TRF_670cnynjhj2eu281\",\"titan_code\":null,\"transferred_at\":null,\"id\":901628140,\"integration\":1129844,\"request\":1129304240,\"recipient\":113924961,\"createdAt\":\"2025-10-20T06:53:22.000Z\",\"updatedAt\":\"2025-10-20T06:53:22.000Z\"}}', '2025-10-20 06:53:23', 'SID_68F5DC652D09D'),
(151, 75, 'WD_68f6519a5d22b', 'RCP_b1c626iru08r2u7', 'PalmPay', '8148622359', 'THEO DESMOND NWOGU', 2300000.00, 'SUCCESS', '{\"mode\":\"direct\",\"message\":\"Withdrawal marked successful without Paystack balance\"}', '2025-10-20 15:13:30', 'SID_68F6519C04C68'),
(152, 85, 'WD_68f7350be89b4', 'RCP_z3hu9d895vgcffy', 'Moniepoint MFB', '8246064522', 'EMEKA OFFOR - SONS GLOBAL VENTURE - EMEKA OFFOR AND SONS GLOBAL', 12900.00, 'SUCCESS', '{\"mode\":\"direct\",\"message\":\"Withdrawal marked successful without Paystack balance\"}', '2025-10-21 07:23:56', 'SID_68F7350DA604C'),
(153, 85, 'WD_68f8c9f26a4ca', 'RCP_8994s3ozlql8fbo', 'Ecobank Nigeria', '4803010366', 'OBUKENI  ESTHER', 1800.00, 'SUCCESS', '{\"mode\":\"direct\",\"message\":\"Withdrawal marked successful without Paystack balance\"}', '2025-10-22 12:11:30', 'SID_68F8C9F425F23');

-- --------------------------------------------------------

--
-- Table structure for table `virtual_account_topups`
--

CREATE TABLE `virtual_account_topups` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `reference` varchar(100) NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `paid_at` datetime DEFAULT NULL,
  `sender_name` varchar(255) DEFAULT NULL,
  `sender_bank` varchar(100) DEFAULT NULL,
  `sender_account` varchar(50) DEFAULT NULL,
  `receiver_bank` varchar(100) DEFAULT NULL,
  `receiver_account` varchar(50) DEFAULT NULL,
  `customer_name` varchar(255) DEFAULT NULL,
  `customer_email` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `virtual_account_topups`
--

INSERT INTO `virtual_account_topups` (`id`, `user_id`, `reference`, `amount`, `paid_at`, `sender_name`, `sender_bank`, `sender_account`, `receiver_bank`, `receiver_account`, `customer_name`, `customer_email`, `created_at`) VALUES
(1, 81, '100033250615150224240570750458', 50.00, '2025-06-15 15:06:18', 'THEO DESMOND NWOGU', 'PalmPay', 'XXXXXX2359', 'Wema Bank', '9324358486', 'Loat Man', 'lotaa@gmail.com', '2025-06-15 15:06:19'),
(2, 81, '100033250615193500435419837005', 200.00, '2025-06-15 19:36:49', 'THEO DESMOND NWOGU', 'PalmPay', 'XXXXXX2359', 'Wema Bank', '9324358486', 'Loat Man', 'lotaa@gmail.com', '2025-06-15 19:36:50'),
(3, 75, '000014250619163637277352850434', 338100.00, '2025-06-19 15:37:02', 'CHIGBO JUSTIN NNOLI', 'ALAT by WEMA', 'XXXXXX3527', 'Wema Bank', '9324273303', 'Nwogu Theo Desmond', 'asam@gmail.com', '2025-06-19 15:37:03'),
(4, 75, '000015250621222620000005647649', 144900.00, '2025-06-21 21:26:31', 'GIGG GLOBAL DIGITAL SOLUTIONS LTD', 'Zenith Bank', 'XXXXXX4230', 'Wema Bank', '9324273303', 'Nwogu Theo Desmond', 'asam@gmail.com', '2025-06-21 21:26:32'),
(5, 75, '100033250624221029249877999022', 1500.00, '2025-06-24 22:11:03', 'THEO DESMOND NWOGU', 'PalmPay', 'XXXXXX2359', 'Wema Bank', '9324273303', 'Nwogu Theo Desmond', 'asam@gmail.com', '2025-06-24 22:11:04'),
(6, 76, '100004250625154437135484644650', 500.00, '2025-06-25 15:45:03', 'AZANA AGOROWEI', 'OPay Digital Services Limited (OPay)', 'XXXXXX7787', 'Wema Bank', '9324284596', 'Agorowei Azana Augustine', 'a.azana@protonmail.com', '2025-06-25 15:45:04'),
(7, 75, '100004250701112446135872540583', 190.00, '2025-07-01 11:25:03', 'THEO DESMOND NWOGU', 'OPay Digital Services Limited (OPay)', 'XXXXXX2359', 'Wema Bank', '9324273303', 'Nwogu Theo Desmond', 'asam@gmail.com', '2025-07-01 11:25:04'),
(8, 84, '100033250701172319900310179218', 500.00, '2025-07-01 17:23:34', 'THEO DESMOND NWOGU', 'PalmPay', 'XXXXXX2359', 'Wema Bank', '9325024698', 'Theophilus Dein', 'theo111@gmail.com', '2025-07-01 17:23:35'),
(9, 84, '110006250703133443084338364001', 5000.00, '2025-07-03 13:35:01', 'Paystack', '', 'XXXXXX2813', 'Wema Bank', '9325024698', 'Theophilus Dein', 'theo111@gmail.com', '2025-07-03 13:35:02'),
(10, 75, '100033250704221033817584522217', 5000.00, '2025-07-04 22:11:05', 'THEO DESMOND NWOGU', 'PalmPay', 'XXXXXX2359', 'Wema Bank', '9324273303', 'Nwogu Theo Desmond', 'asam@gmail.com', '2025-07-04 22:11:06'),
(11, 75, '100033250705162659901736599064', 500.00, '2025-07-05 16:27:17', 'THEO DESMOND NWOGU', 'PalmPay', 'XXXXXX2359', 'Wema Bank', '9324273303', 'Nwogu Theo Desmond', 'asam@gmail.com', '2025-07-05 16:27:18'),
(12, 75, '100033250705162743763639170102', 500.00, '2025-07-05 16:28:02', 'THEO DESMOND NWOGU', 'PalmPay', 'XXXXXX2359', 'Wema Bank', '9324273303', 'Nwogu Theo Desmond', 'asam@gmail.com', '2025-07-05 16:28:03'),
(13, 84, '100033250711192624444273178706', 500.00, '2025-07-11 19:26:36', 'THEO DESMOND NWOGU', 'PalmPay', 'XXXXXX2359', 'Wema Bank', '9325024698', 'Theophilus Dein', 'theo111@gmail.com', '2025-07-11 19:26:38'),
(14, 84, '100033250721181306670825839017', 500.00, '2025-07-21 18:13:32', 'THEO DESMOND NWOGU', 'PalmPay', 'XXXXXX2359', 'Wema Bank', '9325024698', 'Theophilus Dein', 'theo111@gmail.com', '2025-07-21 18:13:32'),
(15, 75, '100004250801213621138091179568', 100.00, '2025-08-01 21:36:34', 'THEO DESMOND NWOGU', 'OPay Digital Services Limited (OPay)', 'XXXXXX2359', 'Wema Bank', '9324273303', 'Nwogu Theo Desmond', 'asam@gmail.com', '2025-08-01 21:36:34'),
(16, 75, '100033250823130941390912696305', 500.00, '2025-08-23 13:10:06', 'THEO DESMOND NWOGU', 'PalmPay', 'XXXXXX2359', 'Wema Bank', '9324273303', 'Nwogu Theo Desmond', 'asam@gmail.com', '2025-08-23 13:10:07'),
(17, 85, '100033250824173036526367943443', 1000.00, '2025-08-24 17:49:48', 'IFEANYICHUKWU - CHIKEZIE', 'PalmPay', 'XXXXXX7061', 'Wema Bank', '9325844988', 'Theo Desmond', 'digitalsolutionhub231@gmail.com', '2025-08-24 17:49:49'),
(18, 75, '100033250910171916843156940789', 100.00, '2025-09-10 17:19:34', 'THEO DESMOND NWOGU', 'PalmPay', 'XXXXXX2359', 'Wema Bank', '9324273303', 'Nwogu Theo Desmond', 'asam@gmail.com', '2025-09-10 17:19:35'),
(19, 75, '100004250912195022140951418079', 200.00, '2025-09-12 19:50:31', 'THEO DESMOND NWOGU', 'OPay Digital Services Limited (OPay)', 'XXXXXX2359', 'Wema Bank', '9324273303', 'Nwogu Theo Desmond', 'asam@gmail.com', '2025-09-12 19:50:31'),
(20, 75, '100033250918142029514200415305', 1000.00, '2025-09-18 14:20:51', 'THEO DESMOND NWOGU', 'PalmPay', 'XXXXXX2359', 'Wema Bank', '9324273303', 'Nwogu Theo Desmond', 'asam@gmail.com', '2025-09-18 14:20:52'),
(21, 87, '100033250923121849102906705415', 300.00, '2025-09-23 12:19:03', 'THEO DESMOND NWOGU', 'PalmPay', 'XXXXXX2359', 'Wema Bank', '9326085261', 'Alha Barry', 'admin@digishubb.com', '2025-09-23 12:19:04'),
(22, 75, '100033250930013525478678594238', 1000.00, '2025-09-30 01:36:35', 'THEO DESMOND NWOGU', 'PalmPay', 'XXXXXX2359', 'Wema Bank', '9324273303', 'Nwogu Theo Desmond', 'asam@gmail.com', '2025-09-30 01:36:36'),
(23, 75, '100033251003143351922558455943', 1000.00, '2025-10-03 14:34:12', 'THEO DESMOND NWOGU', 'PalmPay', 'XXXXXX2359', 'Wema Bank', '9324273303', 'Nwogu Theo Desmond', 'asam@gmail.com', '2025-10-03 14:34:12'),
(24, 89, '100004251009135941142748174046', 100.00, '2025-10-09 14:00:05', 'THEO DESMOND NWOGU', 'OPay Digital Services Limited (OPay)', 'XXXXXX2359', 'Wema Bank', '9326484211', 'Desmond Peters', 'hubclink@gmail.com', '2025-10-09 14:00:06');

-- --------------------------------------------------------

--
-- Table structure for table `visitor_activity`
--

CREATE TABLE `visitor_activity` (
  `id` int(11) NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  `email` varchar(150) DEFAULT NULL,
  `location` varchar(255) DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `current_page` text DEFAULT NULL,
  `last_active` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `visitor_activity`
--

INSERT INTO `visitor_activity` (`id`, `name`, `email`, `location`, `ip_address`, `user_agent`, `current_page`, `last_active`) VALUES
(1, 'Guest', 'theodesmon71@gmail.com', 'Port Harcourt, Rivers State, Nigeria', '102.90.118.145', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/135.0.0.0 Mobile Safari/537.36 EdgA/135.0.0.0', 'https://swiftaffiliates.cloud/index.php', '2025-06-06 01:22:11'),
(2, 'Guest', 'theceo@digishubb.com', 'Onitsha, Anambra, Nigeria', '102.90.102.203', 'Mozilla/5.0 (Linux; Android 10; SM-G960U Build/QP1A.190711.020; ) AppleWebKit/537.36 (KHTML, like Gecko) Version/4.0 Chrome/135.0.7049.111 Mobile Safari/537.36', 'https://swiftaffiliates.cloud/index.php', '2025-05-06 11:41:46'),
(3, 'Guest', 'marksmasabae@gmail.com', 'Gaborone, Gaborone, Botswana', '41.74.57.200', 'Mozilla/5.0 (Linux; Android 10; SM-A217F Build/QP1A.190711.020; ) AppleWebKit/537.36 (KHTML, like Gecko) Version/4.0 Chrome/135.0.7049.113 Mobile Safari/537.36', 'https://swiftaffiliates.cloud/index.php', '2025-05-12 15:44:41'),
(5, 'Guest', 'digitalsolutionhub231@gmail.com', 'Port Harcourt, Rivers State, Nigeria', '102.90.116.223', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/135.0.0.0 Mobile Safari/537.36 EdgA/135.0.0.0', 'https://swiftaffiliates.cloud/current/index.php', '2025-06-02 13:05:47'),
(6, 'Guest', 'theceo@yentownhub.space', 'Lagos, Lagos, Nigeria', '102.89.32.22', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/135.0.0.0 Mobile Safari/537.36 EdgA/135.0.0.0', 'https://swiftaffiliates.cloud/current/index.php', '2025-06-02 19:03:58'),
(7, 'Guest', 'asam@gmail.com', 'Owerri, Imo State, Nigeria', '102.90.103.113', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Mobile Safari/537.36 EdgA/138.0.0.0', 'https://yentownhub.space/aa/index.php', '2025-10-02 11:49:10');

-- --------------------------------------------------------

--
-- Table structure for table `wallet`
--

CREATE TABLE `wallet` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `transaction_type` enum('deposit','withdrawal','transfer') NOT NULL,
  `transaction_date` timestamp NULL DEFAULT current_timestamp(),
  `status` enum('completed','pending','failed') DEFAULT 'completed'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `wallets`
--

CREATE TABLE `wallets` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `bank_name` varchar(255) DEFAULT NULL,
  `account_number` varchar(255) DEFAULT NULL,
  `account_name` varchar(255) DEFAULT NULL,
  `card_number` varchar(255) DEFAULT NULL,
  `card_expiry` varchar(10) DEFAULT NULL,
  `card_cvv` varchar(4) DEFAULT NULL,
  `paypal_email` varchar(255) DEFAULT NULL,
  `coin_type` varchar(255) DEFAULT NULL,
  `wallet_address` varchar(255) DEFAULT NULL,
  `bank_statement` varchar(255) DEFAULT NULL,
  `credit_card_front` varchar(255) DEFAULT NULL,
  `credit_card_back` varchar(255) DEFAULT NULL,
  `crypto_qr_code` varchar(255) DEFAULT NULL,
  `bank_status` enum('pending','approved','rejected') DEFAULT 'pending',
  `card_status` enum('pending','approved','rejected') DEFAULT 'pending',
  `crypto_status` enum('pending','approved','rejected') DEFAULT 'pending',
  `admin_notes` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `wallet_verification_requests`
--

CREATE TABLE `wallet_verification_requests` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `type` enum('bank','card','crypto') NOT NULL,
  `data` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL CHECK (json_valid(`data`)),
  `front_image` varchar(255) DEFAULT NULL,
  `back_image` varchar(255) DEFAULT NULL,
  `document` varchar(255) DEFAULT NULL,
  `qr_code` varchar(255) DEFAULT NULL,
  `status` enum('not_submitted','pending','approved','rejected') DEFAULT 'not_submitted',
  `created_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `wallet_verification_requests`
--

INSERT INTO `wallet_verification_requests` (`id`, `user_id`, `type`, `data`, `front_image`, `back_image`, `document`, `qr_code`, `status`, `created_at`) VALUES
(1, 1, 'bank', '{\"accountNumber\":\"Theo Desmo monda\",\"bankName\":\"9 Payment Service Bank \"}', NULL, NULL, 'uploads/wallet/bank_1746393854.png', NULL, 'pending', '2025-05-04 21:24:14'),
(2, 1, 'crypto', '{\"cryptocurrency\":\"ethereum\",\"walletAddress\":\"Qetyuurdfghii\"}', NULL, NULL, NULL, 'uploads/wallet/crypto_qr_1746483665.jpg', 'pending', '2025-05-05 22:21:05'),
(3, 1, 'card', '{\"cardNumber\":\"5686435677545789\",\"cardHolder\":\"Theo Desmond N wof\"}', 'uploads/wallet/card_front_1746493085.png', 'uploads/wallet/card_back_1746493085.pdf', NULL, NULL, 'pending', '2025-05-06 00:58:05'),
(4, 75, 'bank', '{\"accountNumber\":\"Theo Desmo monda\",\"bankName\":\"9 Payment Service Bank \"}', NULL, NULL, 'uploads/wallet/bank_1755879810.png', NULL, 'pending', '2025-08-22 16:23:30');

-- --------------------------------------------------------

--
-- Table structure for table `watch_ads`
--

CREATE TABLE `watch_ads` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `ad_id` varchar(50) NOT NULL,
  `points_awarded` int(11) NOT NULL DEFAULT 20,
  `required_watch_time` int(11) NOT NULL DEFAULT 30,
  `started_at` timestamp NULL DEFAULT NULL,
  `completed_at` timestamp NULL DEFAULT NULL,
  `watched_at` timestamp NULL DEFAULT NULL,
  `status` enum('started','completed','rewarded') DEFAULT 'started'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `withdrawals`
--

CREATE TABLE `withdrawals` (
  `id` int(11) NOT NULL,
  `transaction_ref` varchar(100) NOT NULL,
  `user_id` int(11) NOT NULL,
  `source` enum('balance','earnings') NOT NULL,
  `amount` decimal(12,2) NOT NULL,
  `method_id` int(11) NOT NULL,
  `method_details` text DEFAULT NULL,
  `proof_url` varchar(255) DEFAULT NULL,
  `status` enum('pending','approved','declined') DEFAULT 'pending',
  `created_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `withdrawals`
--

INSERT INTO `withdrawals` (`id`, `transaction_ref`, `user_id`, `source`, `amount`, `method_id`, `method_details`, `proof_url`, `status`, `created_at`) VALUES
(1, 'SC764A02', 1, 'balance', 10.00, 1, 'dsga', NULL, 'pending', '2025-05-07 23:57:48'),
(2, 'SC118DCC', 1, 'balance', 24.00, 1, 'dG', NULL, 'pending', '2025-05-08 00:02:51'),
(3, 'SCE90A40', 1, 'balance', 35000.00, 2, '$_wryugff', NULL, 'pending', '2025-05-09 01:12:35'),
(4, 'SCFA15A2', 1, 'balance', 4000.00, 2, '1366_wrygrruoi', NULL, 'pending', '2025-05-22 11:30:31'),
(5, 'SCAE356A', 75, 'balance', 6000.00, 1, '3f4tveg', NULL, 'pending', '2025-06-27 19:35:59'),
(6, 'SC7B7AD2', 75, 'balance', 8000.00, 1, 'dfgn', NULL, 'pending', '2025-07-30 18:31:16'),
(7, 'SCC60241', 75, 'balance', 7666.00, 1, 'khhl', NULL, 'pending', '2025-08-20 23:13:48'),
(8, 'SC005273', 75, 'balance', 50000.00, 2, 'Rtuiebwvgwj', NULL, 'pending', '2025-09-06 10:50:00'),
(9, 'SC8774BD', 75, 'balance', 7555.00, 1, 'tsdth', NULL, 'pending', '2025-09-22 00:21:50'),
(10, 'SCD0B8BD', 75, 'balance', 2500.00, 2, 'Ffghh', NULL, 'pending', '2025-09-22 22:03:20'),
(11, 'SCF0DB4B', 75, 'balance', 51000.00, 1, 'Wrtyg v', NULL, 'pending', '2025-09-22 22:03:59');

-- --------------------------------------------------------

--
-- Table structure for table `withdrawal_methods`
--

CREATE TABLE `withdrawal_methods` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `withdrawal_methods`
--

INSERT INTO `withdrawal_methods` (`id`, `name`, `description`, `is_active`) VALUES
(1, 'Bank Transfer ', 'Instant bank transfer ', 1),
(2, 'Bitcoin ', 'Send Funds to your wallet ', 1);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `9mobile_data`
--
ALTER TABLE `9mobile_data`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `activity_log`
--
ALTER TABLE `activity_log`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `admin_users`
--
ALTER TABLE `admin_users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Indexes for table `airtel_data`
--
ALTER TABLE `airtel_data`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `airtime_transactions`
--
ALTER TABLE `airtime_transactions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `audit_logs`
--
ALTER TABLE `audit_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `banner_alerts`
--
ALTER TABLE `banner_alerts`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `beneficiaries`
--
ALTER TABLE `beneficiaries`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `bookings`
--
ALTER TABLE `bookings`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `reference` (`reference`),
  ADD KEY `hotel_id` (`hotel_id`);

--
-- Indexes for table `booking_categories`
--
ALTER TABLE `booking_categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `brands`
--
ALTER TABLE `brands`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `cable_transactions`
--
ALTER TABLE `cable_transactions`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `cars`
--
ALTER TABLE `cars`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `cart_orders`
--
ALTER TABLE `cart_orders`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `reference` (`reference`);

--
-- Indexes for table `car_bookings`
--
ALTER TABLE `car_bookings`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `car_id` (`car_id`);

--
-- Indexes for table `car_payments`
--
ALTER TABLE `car_payments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `booking_id` (`booking_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `contact_messages`
--
ALTER TABLE `contact_messages`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `contracts`
--
ALTER TABLE `contracts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `currency_rates`
--
ALTER TABLE `currency_rates`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_pair` (`base_currency`,`target_currency`);

--
-- Indexes for table `daily_login`
--
ALTER TABLE `daily_login`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `login_date` (`login_date`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `data_plans`
--
ALTER TABLE `data_plans`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `data_transactions`
--
ALTER TABLE `data_transactions`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `deposits`
--
ALTER TABLE `deposits`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `transaction_ref` (`transaction_ref`);

--
-- Indexes for table `deposit_methods`
--
ALTER TABLE `deposit_methods`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `downloads`
--
ALTER TABLE `downloads`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ecommerce_products`
--
ALTER TABLE `ecommerce_products`
  ADD PRIMARY KEY (`id`),
  ADD KEY `products_ibfk_1` (`shop_id`);

--
-- Indexes for table `events`
--
ALTER TABLE `events`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `event_bookings`
--
ALTER TABLE `event_bookings`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `reference` (`reference`),
  ADD KEY `event_id` (`event_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `fixed_savings`
--
ALTER TABLE `fixed_savings`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `follow_links`
--
ALTER TABLE `follow_links`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `link_id` (`link_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `giftcards`
--
ALTER TABLE `giftcards`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `giftcard_rates`
--
ALTER TABLE `giftcard_rates`
  ADD PRIMARY KEY (`id`),
  ADD KEY `giftcard_id` (`giftcard_id`);

--
-- Indexes for table `giftcard_trades`
--
ALTER TABLE `giftcard_trades`
  ADD PRIMARY KEY (`id`),
  ADD KEY `giftcard_id` (`giftcard_id`);

--
-- Indexes for table `glo_data`
--
ALTER TABLE `glo_data`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `hotels`
--
ALTER TABLE `hotels`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `hotel_chat_messages`
--
ALTER TABLE `hotel_chat_messages`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `hotel_facilities`
--
ALTER TABLE `hotel_facilities`
  ADD PRIMARY KEY (`id`),
  ADD KEY `hotel_id` (`hotel_id`);

--
-- Indexes for table `hotel_images`
--
ALTER TABLE `hotel_images`
  ADD PRIMARY KEY (`id`),
  ADD KEY `hotel_id` (`hotel_id`);

--
-- Indexes for table `hotel_rooms`
--
ALTER TABLE `hotel_rooms`
  ADD PRIMARY KEY (`id`),
  ADD KEY `hotel_id` (`hotel_id`);

--
-- Indexes for table `hotel_typing_status`
--
ALTER TABLE `hotel_typing_status`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `identity_verifications`
--
ALTER TABLE `identity_verifications`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `kyc`
--
ALTER TABLE `kyc`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `kyc_verifications`
--
ALTER TABLE `kyc_verifications`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `levels`
--
ALTER TABLE `levels`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `mtn_data`
--
ALTER TABLE `mtn_data`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `reference` (`reference`);

--
-- Indexes for table `p2p`
--
ALTER TABLE `p2p`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `password_resets`
--
ALTER TABLE `password_resets`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `payments`
--
ALTER TABLE `payments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `product_reviews`
--
ALTER TABLE `product_reviews`
  ADD PRIMARY KEY (`id`),
  ADD KEY `product_id` (`product_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `promotions`
--
ALTER TABLE `promotions`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `quiz_answers`
--
ALTER TABLE `quiz_answers`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `quiz_failures`
--
ALTER TABLE `quiz_failures`
  ADD PRIMARY KEY (`user_id`);

--
-- Indexes for table `quiz_progress`
--
ALTER TABLE `quiz_progress`
  ADD PRIMARY KEY (`user_id`);

--
-- Indexes for table `quiz_questions`
--
ALTER TABLE `quiz_questions`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `receipts`
--
ALTER TABLE `receipts`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `savings_transactions`
--
ALTER TABLE `savings_transactions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `savings_id` (`savings_id`);

--
-- Indexes for table `shipping_logs`
--
ALTER TABLE `shipping_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_id` (`order_id`);

--
-- Indexes for table `shops`
--
ALTER TABLE `shops`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `shop_banks`
--
ALTER TABLE `shop_banks`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `shop_chats`
--
ALTER TABLE `shop_chats`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `shop_emails`
--
ALTER TABLE `shop_emails`
  ADD PRIMARY KEY (`id`),
  ADD KEY `shop_id` (`shop_id`);

--
-- Indexes for table `shop_owners`
--
ALTER TABLE `shop_owners`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `shop_name` (`shop_name`);

--
-- Indexes for table `shop_visitors`
--
ALTER TABLE `shop_visitors`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sms_logs`
--
ALTER TABLE `sms_logs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sms_notifications`
--
ALTER TABLE `sms_notifications`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `tasks`
--
ALTER TABLE `tasks`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `top_earners`
--
ALTER TABLE `top_earners`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `transactions`
--
ALTER TABLE `transactions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `transaction_ref` (`transaction_ref`);

--
-- Indexes for table `transaction_disputes`
--
ALTER TABLE `transaction_disputes`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `transaction_fees`
--
ALTER TABLE `transaction_fees`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tv_packages`
--
ALTER TABLE `tv_packages`
  ADD PRIMARY KEY (`id`),
  ADD KEY `provider_id` (`provider_id`);

--
-- Indexes for table `tv_providers`
--
ALTER TABLE `tv_providers`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `code` (`code`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `phone_number` (`phone_number`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `referral_code` (`referral_code`);

--
-- Indexes for table `user_bank_accounts`
--
ALTER TABLE `user_bank_accounts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_user_id` (`user_id`);

--
-- Indexes for table `user_contracts`
--
ALTER TABLE `user_contracts`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `transaction_ref` (`transaction_ref`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `contract_id` (`contract_id`);

--
-- Indexes for table `user_earnings`
--
ALTER TABLE `user_earnings`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `user_tasks`
--
ALTER TABLE `user_tasks`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `user_withdrawals`
--
ALTER TABLE `user_withdrawals`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `reference` (`reference`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `virtual_account_topups`
--
ALTER TABLE `virtual_account_topups`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `visitor_activity`
--
ALTER TABLE `visitor_activity`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `wallets`
--
ALTER TABLE `wallets`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `wallet_verification_requests`
--
ALTER TABLE `wallet_verification_requests`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `withdrawals`
--
ALTER TABLE `withdrawals`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `transaction_ref` (`transaction_ref`);

--
-- Indexes for table `withdrawal_methods`
--
ALTER TABLE `withdrawal_methods`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `9mobile_data`
--
ALTER TABLE `9mobile_data`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `activity_log`
--
ALTER TABLE `activity_log`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=66;

--
-- AUTO_INCREMENT for table `admin_users`
--
ALTER TABLE `admin_users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `airtel_data`
--
ALTER TABLE `airtel_data`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `airtime_transactions`
--
ALTER TABLE `airtime_transactions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=65;

--
-- AUTO_INCREMENT for table `audit_logs`
--
ALTER TABLE `audit_logs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `banner_alerts`
--
ALTER TABLE `banner_alerts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `beneficiaries`
--
ALTER TABLE `beneficiaries`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `bookings`
--
ALTER TABLE `bookings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `booking_categories`
--
ALTER TABLE `booking_categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `brands`
--
ALTER TABLE `brands`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `cable_transactions`
--
ALTER TABLE `cable_transactions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `cars`
--
ALTER TABLE `cars`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `cart_orders`
--
ALTER TABLE `cart_orders`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `car_bookings`
--
ALTER TABLE `car_bookings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `car_payments`
--
ALTER TABLE `car_payments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `contact_messages`
--
ALTER TABLE `contact_messages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `contracts`
--
ALTER TABLE `contracts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=67;

--
-- AUTO_INCREMENT for table `currency_rates`
--
ALTER TABLE `currency_rates`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=54;

--
-- AUTO_INCREMENT for table `data_transactions`
--
ALTER TABLE `data_transactions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- AUTO_INCREMENT for table `deposits`
--
ALTER TABLE `deposits`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `deposit_methods`
--
ALTER TABLE `deposit_methods`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `downloads`
--
ALTER TABLE `downloads`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `ecommerce_products`
--
ALTER TABLE `ecommerce_products`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `events`
--
ALTER TABLE `events`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `event_bookings`
--
ALTER TABLE `event_bookings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `fixed_savings`
--
ALTER TABLE `fixed_savings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `giftcards`
--
ALTER TABLE `giftcards`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `giftcard_rates`
--
ALTER TABLE `giftcard_rates`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `giftcard_trades`
--
ALTER TABLE `giftcard_trades`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `glo_data`
--
ALTER TABLE `glo_data`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `hotels`
--
ALTER TABLE `hotels`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `hotel_chat_messages`
--
ALTER TABLE `hotel_chat_messages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `hotel_facilities`
--
ALTER TABLE `hotel_facilities`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `hotel_images`
--
ALTER TABLE `hotel_images`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `hotel_rooms`
--
ALTER TABLE `hotel_rooms`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `hotel_typing_status`
--
ALTER TABLE `hotel_typing_status`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `kyc`
--
ALTER TABLE `kyc`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `kyc_verifications`
--
ALTER TABLE `kyc_verifications`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `levels`
--
ALTER TABLE `levels`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `mtn_data`
--
ALTER TABLE `mtn_data`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `notifications`
--
ALTER TABLE `notifications`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=306;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `p2p`
--
ALTER TABLE `p2p`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `password_resets`
--
ALTER TABLE `password_resets`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `payments`
--
ALTER TABLE `payments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `product_reviews`
--
ALTER TABLE `product_reviews`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `promotions`
--
ALTER TABLE `promotions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `quiz_answers`
--
ALTER TABLE `quiz_answers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `quiz_questions`
--
ALTER TABLE `quiz_questions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=42;

--
-- AUTO_INCREMENT for table `receipts`
--
ALTER TABLE `receipts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=41;

--
-- AUTO_INCREMENT for table `savings_transactions`
--
ALTER TABLE `savings_transactions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `shipping_logs`
--
ALTER TABLE `shipping_logs`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `shops`
--
ALTER TABLE `shops`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `shop_banks`
--
ALTER TABLE `shop_banks`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `shop_chats`
--
ALTER TABLE `shop_chats`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=48;

--
-- AUTO_INCREMENT for table `shop_emails`
--
ALTER TABLE `shop_emails`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `shop_owners`
--
ALTER TABLE `shop_owners`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `shop_visitors`
--
ALTER TABLE `shop_visitors`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- AUTO_INCREMENT for table `sms_logs`
--
ALTER TABLE `sms_logs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `sms_notifications`
--
ALTER TABLE `sms_notifications`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tasks`
--
ALTER TABLE `tasks`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `top_earners`
--
ALTER TABLE `top_earners`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `transactions`
--
ALTER TABLE `transactions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=216;

--
-- AUTO_INCREMENT for table `transaction_disputes`
--
ALTER TABLE `transaction_disputes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `transaction_fees`
--
ALTER TABLE `transaction_fees`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tv_packages`
--
ALTER TABLE `tv_packages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=73;

--
-- AUTO_INCREMENT for table `tv_providers`
--
ALTER TABLE `tv_providers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=90;

--
-- AUTO_INCREMENT for table `user_bank_accounts`
--
ALTER TABLE `user_bank_accounts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `user_contracts`
--
ALTER TABLE `user_contracts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=35;

--
-- AUTO_INCREMENT for table `user_earnings`
--
ALTER TABLE `user_earnings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `user_tasks`
--
ALTER TABLE `user_tasks`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `user_withdrawals`
--
ALTER TABLE `user_withdrawals`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=154;

--
-- AUTO_INCREMENT for table `virtual_account_topups`
--
ALTER TABLE `virtual_account_topups`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT for table `visitor_activity`
--
ALTER TABLE `visitor_activity`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `wallets`
--
ALTER TABLE `wallets`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `wallet_verification_requests`
--
ALTER TABLE `wallet_verification_requests`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `withdrawals`
--
ALTER TABLE `withdrawals`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `withdrawal_methods`
--
ALTER TABLE `withdrawal_methods`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `bookings`
--
ALTER TABLE `bookings`
  ADD CONSTRAINT `bookings_ibfk_1` FOREIGN KEY (`hotel_id`) REFERENCES `hotels` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `car_bookings`
--
ALTER TABLE `car_bookings`
  ADD CONSTRAINT `car_bookings_ibfk_2` FOREIGN KEY (`car_id`) REFERENCES `cars` (`id`);

--
-- Constraints for table `car_payments`
--
ALTER TABLE `car_payments`
  ADD CONSTRAINT `car_payments_ibfk_1` FOREIGN KEY (`booking_id`) REFERENCES `car_bookings` (`id`);

--
-- Constraints for table `ecommerce_products`
--
ALTER TABLE `ecommerce_products`
  ADD CONSTRAINT `ecommerce_products_ibfk_1` FOREIGN KEY (`shop_id`) REFERENCES `shop_owners` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `event_bookings`
--
ALTER TABLE `event_bookings`
  ADD CONSTRAINT `event_bookings_ibfk_1` FOREIGN KEY (`event_id`) REFERENCES `events` (`id`);

--
-- Constraints for table `giftcard_rates`
--
ALTER TABLE `giftcard_rates`
  ADD CONSTRAINT `giftcard_rates_ibfk_1` FOREIGN KEY (`giftcard_id`) REFERENCES `giftcards` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `giftcard_trades`
--
ALTER TABLE `giftcard_trades`
  ADD CONSTRAINT `giftcard_trades_ibfk_1` FOREIGN KEY (`giftcard_id`) REFERENCES `giftcards` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `hotel_facilities`
--
ALTER TABLE `hotel_facilities`
  ADD CONSTRAINT `hotel_facilities_ibfk_1` FOREIGN KEY (`hotel_id`) REFERENCES `hotels` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `hotel_images`
--
ALTER TABLE `hotel_images`
  ADD CONSTRAINT `hotel_images_ibfk_1` FOREIGN KEY (`hotel_id`) REFERENCES `hotels` (`id`);

--
-- Constraints for table `hotel_rooms`
--
ALTER TABLE `hotel_rooms`
  ADD CONSTRAINT `hotel_rooms_ibfk_1` FOREIGN KEY (`hotel_id`) REFERENCES `hotels` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `product_reviews`
--
ALTER TABLE `product_reviews`
  ADD CONSTRAINT `product_reviews_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `ecommerce_products` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `product_reviews_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `savings_transactions`
--
ALTER TABLE `savings_transactions`
  ADD CONSTRAINT `savings_transactions_ibfk_1` FOREIGN KEY (`savings_id`) REFERENCES `fixed_savings` (`id`);

--
-- Constraints for table `shipping_logs`
--
ALTER TABLE `shipping_logs`
  ADD CONSTRAINT `shipping_logs_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `cart_orders` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `shop_emails`
--
ALTER TABLE `shop_emails`
  ADD CONSTRAINT `shop_emails_ibfk_1` FOREIGN KEY (`shop_id`) REFERENCES `shops` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `tv_packages`
--
ALTER TABLE `tv_packages`
  ADD CONSTRAINT `tv_packages_ibfk_1` FOREIGN KEY (`provider_id`) REFERENCES `tv_providers` (`id`);

--
-- Constraints for table `user_contracts`
--
ALTER TABLE `user_contracts`
  ADD CONSTRAINT `user_contracts_ibfk_2` FOREIGN KEY (`contract_id`) REFERENCES `contracts` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
