-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Dec 17, 2025 at 01:36 PM
-- Server version: 8.0.30
-- PHP Version: 8.2.20

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `phonezy_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `bad_words`
--

CREATE TABLE `bad_words` (
  `id` bigint NOT NULL,
  `word` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Từ ngữ cấm',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Danh sách từ ngữ cấm - Nếu comment chứa từ này sẽ bị từ chối';

--
-- Dumping data for table `bad_words`
--

INSERT INTO `bad_words` (`id`, `word`, `created_at`, `updated_at`) VALUES
(272, 'đụ', '2025-11-21 05:37:21', '2025-11-21 05:37:21'),
(273, 'địt', '2025-11-21 05:37:21', '2025-11-21 05:37:21'),
(274, 'đĩ', '2025-11-21 05:37:21', '2025-11-21 05:37:21'),
(275, 'lồn', '2025-11-21 05:37:21', '2025-11-21 05:37:21'),
(276, 'buồi', '2025-11-21 05:37:21', '2025-11-21 05:37:21'),
(277, 'cặc', '2025-11-21 05:37:21', '2025-11-21 05:37:21'),
(278, 'cứt', '2025-11-21 05:37:21', '2025-11-21 05:37:21'),
(279, 'đéo', '2025-11-21 05:37:21', '2025-11-21 05:37:21'),
(280, 'vãi', '2025-11-21 05:37:21', '2025-11-21 05:37:21'),
(281, 'đm', '2025-11-21 05:37:21', '2025-11-21 05:37:21'),
(282, 'dm', '2025-11-21 05:37:21', '2025-11-21 05:37:21'),
(283, 'đmm', '2025-11-21 05:37:21', '2025-11-21 05:37:21'),
(284, 'dmm', '2025-11-21 05:37:21', '2025-11-21 05:37:21'),
(285, 'vcl', '2025-11-21 05:37:21', '2025-11-21 05:37:21'),
(286, 'vkl', '2025-11-21 05:37:21', '2025-11-21 05:37:21'),
(287, 'cc', '2025-11-21 05:37:21', '2025-11-21 05:37:21'),
(288, 'clgt', '2025-11-21 05:37:21', '2025-11-21 05:37:21'),
(289, 'đcm', '2025-11-21 05:37:21', '2025-11-21 05:37:21'),
(290, 'dcm', '2025-11-21 05:37:21', '2025-11-21 05:37:21'),
(291, 'đkm', '2025-11-21 05:37:21', '2025-11-21 05:37:21'),
(292, 'dkm', '2025-11-21 05:37:21', '2025-11-21 05:37:21'),
(293, 'đb', '2025-11-21 05:37:21', '2025-11-21 05:37:21'),
(294, 'db', '2025-11-21 05:37:21', '2025-11-21 05:37:21'),
(295, 'vl', '2025-11-21 05:37:21', '2025-11-21 05:37:21'),
(296, 'loz', '2025-11-21 05:37:21', '2025-11-21 05:37:21'),
(297, 'cmm', '2025-11-21 05:37:21', '2025-11-21 05:37:21'),
(298, 'cmn', '2025-11-21 05:37:21', '2025-11-21 05:37:21'),
(299, 'cmt', '2025-11-21 05:37:21', '2025-11-21 05:37:21'),
(300, 'ngu', '2025-11-21 05:37:21', '2025-11-21 05:37:21'),
(301, 'chó', '2025-11-21 05:37:21', '2025-11-21 05:37:21'),
(302, 'súc vật', '2025-11-21 05:37:21', '2025-11-21 05:37:21'),
(303, 'đồ ngu', '2025-11-21 05:37:21', '2025-11-21 05:37:21'),
(304, 'đồ khốn', '2025-11-21 05:37:21', '2025-11-21 05:37:21'),
(305, 'thằng ngu', '2025-11-21 05:37:21', '2025-11-21 05:37:21'),
(306, 'con ngu', '2025-11-21 05:37:21', '2025-11-21 05:37:21'),
(307, 'đồ điên', '2025-11-21 05:37:21', '2025-11-21 05:37:21'),
(308, 'ngu ngốc', '2025-11-21 05:37:21', '2025-11-21 05:37:21'),
(309, 'óc chó', '2025-11-21 05:37:21', '2025-11-21 05:37:21'),
(310, 'não cá vàng', '2025-11-21 05:37:21', '2025-11-21 05:37:21'),
(311, 'đồ rác', '2025-11-21 05:37:21', '2025-11-21 05:37:21'),
(312, 'đồ bẩn', '2025-11-21 05:37:21', '2025-11-21 05:37:21'),
(313, 'thối', '2025-11-21 05:37:21', '2025-11-21 05:37:21'),
(314, 'bố láo', '2025-11-21 05:37:21', '2025-11-21 05:37:21'),
(315, 'đ.ụ', '2025-11-21 05:37:21', '2025-11-21 05:37:21'),
(316, 'đ.ị.t', '2025-11-21 05:37:21', '2025-11-21 05:37:21'),
(317, 'l.ồ.n', '2025-11-21 05:37:21', '2025-11-21 05:37:21'),
(318, 'c.ặ.c', '2025-11-21 05:37:21', '2025-11-21 05:37:21'),
(319, 'đ.m', '2025-11-21 05:37:21', '2025-11-21 05:37:21'),
(320, 'đ.m.m', '2025-11-21 05:37:21', '2025-11-21 05:37:21'),
(321, 'v.c.l', '2025-11-21 05:37:21', '2025-11-21 05:37:21'),
(322, 'c.l.g.t', '2025-11-21 05:37:21', '2025-11-21 05:37:21'),
(323, 'đ.c.m', '2025-11-21 05:37:21', '2025-11-21 05:37:21'),
(324, 'fuck', '2025-11-21 05:37:21', '2025-11-21 05:37:21'),
(325, 'shit', '2025-11-21 05:37:21', '2025-11-21 05:37:21'),
(326, 'bitch', '2025-11-21 05:37:21', '2025-11-21 05:37:21'),
(327, 'ass', '2025-11-21 05:37:21', '2025-11-21 05:37:21'),
(328, 'damn', '2025-11-21 05:37:21', '2025-11-21 05:37:21'),
(329, 'wtf', '2025-11-21 05:37:21', '2025-11-21 05:37:21'),
(330, 'stfu', '2025-11-21 05:37:21', '2025-11-21 05:37:21'),
(331, 'idiot', '2025-11-21 05:37:21', '2025-11-21 05:37:21'),
(332, 'stupid', '2025-11-21 05:37:21', '2025-11-21 05:37:21'),
(333, 'hell', '2025-11-21 05:37:21', '2025-11-21 05:37:21'),
(334, 'inbox ngay', '2025-11-21 05:37:21', '2025-11-21 05:37:21'),
(335, 'mua ngay kẻo hết', '2025-11-21 05:37:21', '2025-11-21 05:37:21'),
(336, 'zalo mua hàng', '2025-11-21 05:37:21', '2025-11-21 05:37:21'),
(337, 'liên hệ mua', '2025-11-21 05:37:21', '2025-11-21 05:37:21');

-- --------------------------------------------------------

--
-- Table structure for table `brands`
--

CREATE TABLE `brands` (
  `id` bigint NOT NULL,
  `name` varchar(120) NOT NULL,
  `logo` varchar(255) DEFAULT NULL,
  `slug` varchar(150) NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `brands`
--

INSERT INTO `brands` (`id`, `name`, `logo`, `slug`, `created_at`, `updated_at`) VALUES
(1, 'Apple', 'https://example.com/apple.png', 'apple', '2025-10-31 14:14:22', '2025-10-31 14:14:22'),
(2, 'Samsung', 'https://example.com/samsung.png', 'samsung', '2025-10-31 14:14:22', '2025-10-31 14:14:22'),
(3, 'Xiaomi', 'https://example.com/xiaomi.png', 'xiaomi', '2025-10-31 14:14:22', '2025-10-31 14:14:22'),
(4, 'OPPO', 'brands/2uWBJaVR7yC40KhNnODxLy1anQuZ6NVL5pkp6NYQ.jpg', 'oppo', '2025-10-31 14:14:22', '2025-11-07 06:29:22');

-- --------------------------------------------------------

--
-- Table structure for table `carts`
--

CREATE TABLE `carts` (
  `id` bigint NOT NULL,
  `user_id` bigint NOT NULL,
  `status` enum('active','abandoned','converted') DEFAULT 'active',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `carts`
--

INSERT INTO `carts` (`id`, `user_id`, `status`, `created_at`, `updated_at`) VALUES
(1, 2, 'active', '2025-10-31 14:14:23', '2025-10-31 14:14:23'),
(2, 1, 'converted', '2025-11-21 03:09:40', '2025-12-04 05:45:18'),
(9, 9, 'converted', '2025-11-27 06:49:20', '2025-12-04 10:51:15'),
(10, 1, 'converted', '2025-12-04 05:52:11', '2025-12-04 05:52:34'),
(11, 1, 'active', '2025-12-04 06:02:34', '2025-12-04 06:02:34'),
(12, 9, 'converted', '2025-12-04 11:00:34', '2025-12-04 11:01:15'),
(13, 9, 'converted', '2025-12-04 11:15:05', '2025-12-04 11:16:53'),
(14, 9, 'converted', '2025-12-04 11:16:54', '2025-12-04 11:23:10'),
(15, 9, 'converted', '2025-12-04 11:23:35', '2025-12-08 20:18:20'),
(16, 9, 'converted', '2025-12-08 21:11:31', '2025-12-11 08:10:54'),
(17, 9, 'converted', '2025-12-11 08:11:27', '2025-12-11 08:11:39'),
(18, 9, 'converted', '2025-12-11 09:12:18', '2025-12-11 09:12:32'),
(19, 9, 'converted', '2025-12-11 09:12:48', '2025-12-11 09:13:05'),
(20, 9, 'converted', '2025-12-11 09:14:22', '2025-12-11 09:23:52'),
(21, 9, 'converted', '2025-12-11 09:25:48', '2025-12-11 09:26:07'),
(22, 9, 'converted', '2025-12-11 10:16:18', '2025-12-11 10:16:34'),
(23, 9, 'converted', '2025-12-11 10:20:33', '2025-12-11 10:21:03'),
(24, 9, 'converted', '2025-12-11 10:23:20', '2025-12-11 10:23:53'),
(25, 9, 'converted', '2025-12-11 10:26:47', '2025-12-11 10:27:21'),
(26, 9, 'converted', '2025-12-11 10:28:59', '2025-12-11 10:29:30'),
(27, 9, 'converted', '2025-12-11 10:30:41', '2025-12-11 10:31:06'),
(28, 9, 'converted', '2025-12-11 10:35:26', '2025-12-11 10:35:59'),
(29, 9, 'converted', '2025-12-11 10:38:25', '2025-12-11 10:39:18'),
(30, 9, 'converted', '2025-12-11 10:44:15', '2025-12-11 10:45:40'),
(31, 9, 'converted', '2025-12-11 10:49:38', '2025-12-11 10:49:57'),
(32, 9, 'converted', '2025-12-11 10:55:06', '2025-12-11 10:58:41'),
(33, 9, 'converted', '2025-12-11 11:01:10', '2025-12-11 11:01:40'),
(34, 9, 'converted', '2025-12-11 11:05:30', '2025-12-11 11:06:08'),
(35, 9, 'converted', '2025-12-11 11:08:28', '2025-12-11 11:09:19'),
(36, 9, 'converted', '2025-12-11 11:10:07', '2025-12-11 11:10:21'),
(37, 9, 'converted', '2025-12-11 11:12:22', '2025-12-11 11:12:45'),
(38, 9, 'converted', '2025-12-11 11:12:46', '2025-12-11 11:13:31'),
(39, 9, 'converted', '2025-12-11 11:13:31', '2025-12-11 11:15:46'),
(40, 9, 'converted', '2025-12-11 11:17:00', '2025-12-11 11:18:25'),
(41, 9, 'converted', '2025-12-11 11:18:25', '2025-12-11 11:28:08'),
(42, 9, 'converted', '2025-12-11 11:28:09', '2025-12-11 11:31:55'),
(43, 9, 'converted', '2025-12-11 11:31:55', '2025-12-12 03:00:35'),
(44, 9, 'converted', '2025-12-12 03:26:35', '2025-12-12 03:27:01'),
(45, 9, 'converted', '2025-12-12 03:29:56', '2025-12-12 03:32:58'),
(46, 9, 'converted', '2025-12-12 04:31:01', '2025-12-16 05:16:54'),
(47, 9, 'converted', '2025-12-16 05:19:52', '2025-12-17 03:26:23'),
(48, 9, 'converted', '2025-12-17 03:27:10', '2025-12-17 03:27:36'),
(49, 9, 'active', '2025-12-17 04:02:03', '2025-12-17 04:02:03');

-- --------------------------------------------------------

--
-- Table structure for table `cart_items`
--

CREATE TABLE `cart_items` (
  `id` bigint NOT NULL,
  `cart_id` bigint NOT NULL,
  `product_variant_id` bigint NOT NULL,
  `quantity` int NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `cart_items`
--

INSERT INTO `cart_items` (`id`, `cart_id`, `product_variant_id`, `quantity`, `created_at`, `updated_at`) VALUES
(1, 1, 5, 2, '2025-10-31 14:14:23', '2025-10-31 14:14:23'),
(2, 1, 8, 1, '2025-10-31 14:14:23', '2025-10-31 14:14:23'),
(30, 11, 17, 9, '2025-12-04 13:16:38', '2025-12-11 07:43:22'),
(69, 49, 11, 10, '2025-12-17 04:02:03', '2025-12-17 04:02:03');

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` bigint NOT NULL,
  `name` varchar(120) NOT NULL,
  `slug` varchar(150) NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `name`, `slug`, `created_at`, `updated_at`) VALUES
(1, 'Điện thoại', 'dien-thoai', '2025-10-31 14:14:22', '2025-10-31 14:14:22'),
(2, 'Máy tính bảng', 'may-tinh-bang', '2025-10-31 14:14:22', '2025-10-31 14:14:22'),
(3, 'Phụ kiện', 'phu-kien', '2025-10-31 14:14:22', '2025-10-31 14:14:22'),
(4, 'Âm thanh', 'am-thanh', '2025-10-31 14:14:22', '2025-10-31 14:14:22'),
(5, 'Đồng hồ', 'dong-ho', '2025-10-31 14:14:22', '2025-10-31 14:14:22');

-- --------------------------------------------------------

--
-- Table structure for table `colors`
--

CREATE TABLE `colors` (
  `id` bigint NOT NULL,
  `name` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `hex_code` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `colors`
--

INSERT INTO `colors` (`id`, `name`, `hex_code`) VALUES
(1, 'Đen', '#000000'),
(2, 'Trắng', '#FFFFFF'),
(3, 'Xanh', '#CED5D9'),
(4, 'Hồng', '#E3C8CA'),
(5, 'Đỏ', '#FF0000'),
(6, 'Xanh lá cây', '#CAD4C5'),
(7, 'Vàng', '#E5E0C1'),
(8, 'Xanh Titan', '#2F4452'),
(9, 'Xám bóng', '#D6D7D8'),
(10, 'Đen Tuyền', '#0A0A0A'),
(11, 'Xanh bóng', '#D6D7D8');

-- --------------------------------------------------------

--
-- Table structure for table `comments`
--

CREATE TABLE `comments` (
  `id` bigint UNSIGNED NOT NULL,
  `product_id` bigint NOT NULL COMMENT 'ID của sản phẩm được bình luận',
  `user_id` bigint DEFAULT NULL COMMENT 'ID của người dùng bình luận (NULL nếu là khách)',
  `parent_id` bigint DEFAULT NULL COMMENT 'ID của bình luận cha (dùng cho trả lời bình luận)',
  `content` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Nội dung bình luận',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Bảng lưu trữ bình luận sản phẩm';

--
-- Dumping data for table `comments`
--

INSERT INTO `comments` (`id`, `product_id`, `user_id`, `parent_id`, `content`, `created_at`, `updated_at`) VALUES
(1, 2, 4, NULL, 'hihi', '2025-11-13 23:40:23', '2025-11-13 23:40:23'),
(15, 2, 5, 1, 'Hi?', '2025-11-14 00:29:24', '2025-11-14 00:29:24'),
(16, 2, 4, NULL, 'hello', '2025-11-14 00:37:43', '2025-11-14 00:37:43'),
(17, 2, 4, 16, 'Hello', '2025-11-14 00:37:51', '2025-11-14 00:37:51'),
(18, 2, 4, NULL, 'Ôi', '2025-11-14 01:15:19', '2025-11-14 01:15:19'),
(19, 9, 9, NULL, 'hihihaha', '2025-12-04 10:02:13', '2025-12-04 10:02:13');

-- --------------------------------------------------------

--
-- Table structure for table `coupons`
--

CREATE TABLE `coupons` (
  `id` bigint NOT NULL,
  `code` varchar(50) NOT NULL,
  `type` enum('public','private') NOT NULL DEFAULT 'public',
  `discount_type` enum('percent','fixed') NOT NULL,
  `discount_value` decimal(10,2) NOT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `coupons`
--

INSERT INTO `coupons` (`id`, `code`, `type`, `discount_type`, `discount_value`, `expires_at`, `created_at`, `updated_at`) VALUES
(1, 'NEW10', 'public', 'percent', '10.00', '2025-12-29 17:00:00', '2025-10-31 14:14:23', '2025-12-08 20:15:47'),
(2, 'SALE100K', 'public', 'fixed', '100000.00', '2025-12-14 17:00:00', '2025-10-31 14:14:23', '2025-12-08 20:15:57'),
(3, 'test1122', 'public', 'percent', '10.00', '2025-12-06 17:00:00', '2025-12-04 06:41:50', '2025-12-04 06:41:50');

-- --------------------------------------------------------

--
-- Table structure for table `coupon_user`
--

CREATE TABLE `coupon_user` (
  `id` bigint UNSIGNED NOT NULL,
  `coupon_id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `coupon_user`
--

INSERT INTO `coupon_user` (`id`, `coupon_id`, `user_id`, `created_at`, `updated_at`) VALUES
(1, 3, 1, '2025-12-04 06:41:50', '2025-12-04 06:41:50'),
(2, 3, 2, '2025-12-04 06:41:50', '2025-12-04 06:41:50'),
(3, 3, 3, '2025-12-04 06:41:50', '2025-12-04 06:41:50'),
(4, 3, 5, '2025-12-04 06:41:50', '2025-12-04 06:41:50'),
(5, 3, 9, '2025-12-04 06:41:50', '2025-12-04 06:41:50'),
(6, 3, 8, '2025-12-04 06:41:50', '2025-12-04 06:41:50');

-- --------------------------------------------------------

--
-- Table structure for table `inventory_logs`
--

CREATE TABLE `inventory_logs` (
  `id` bigint NOT NULL,
  `warehouse_id` bigint NOT NULL,
  `product_variant_id` bigint NOT NULL,
  `quantity_change` int NOT NULL,
  `type` enum('stock_in','stock_out') NOT NULL,
  `reason` text,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `inventory_logs`
--

INSERT INTO `inventory_logs` (`id`, `warehouse_id`, `product_variant_id`, `quantity_change`, `type`, `reason`, `created_at`, `updated_at`) VALUES
(1, 1, 1, 10, 'stock_in', 'Nhập đầu kỳ', '2025-10-31 14:14:23', '2025-10-31 14:14:23'),
(2, 1, 2, 8, 'stock_in', 'Nhập đầu kỳ', '2025-10-31 14:14:23', '2025-10-31 14:14:23'),
(3, 1, 3, 15, 'stock_in', 'Nhập đầu kỳ', '2025-10-31 14:14:23', '2025-10-31 14:14:23'),
(4, 2, 4, 12, 'stock_in', 'Nhập đầu kỳ', '2025-10-31 14:14:23', '2025-10-31 14:14:23'),
(5, 2, 6, 9, 'stock_in', 'Nhập đầu kỳ', '2025-10-31 14:14:23', '2025-10-31 14:14:23'),
(6, 1, 1, -2, 'stock_out', 'Bán lẻ đơn #1', '2025-10-31 14:14:23', '2025-10-31 14:14:23');

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int UNSIGNED NOT NULL,
  `migration` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(2, '2025_01_20_000000_create_wishlists_table', 1),
(3, '2025_01_15_000000_add_type_to_coupons_table', 2);

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` bigint UNSIGNED NOT NULL,
  `cart_id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED DEFAULT NULL,
  `coupon_id` bigint DEFAULT NULL,
  `subtotal` decimal(12,2) NOT NULL,
  `shipping_fee` decimal(12,2) NOT NULL DEFAULT '0.00',
  `discount_amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `total` decimal(12,2) NOT NULL,
  `payment_method` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `transaction_id` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `payment_status` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `status` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `shipping_full_name` varchar(120) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `shipping_email` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `shipping_phone` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `shipping_city` varchar(120) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `shipping_district` varchar(120) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `shipping_ward` varchar(120) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `shipping_address` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `notes` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `paid_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `cart_id`, `user_id`, `coupon_id`, `subtotal`, `shipping_fee`, `discount_amount`, `total`, `payment_method`, `transaction_id`, `payment_status`, `status`, `shipping_full_name`, `shipping_email`, `shipping_phone`, `shipping_city`, `shipping_district`, `shipping_ward`, `shipping_address`, `notes`, `paid_at`, `created_at`, `updated_at`) VALUES
(2, 2, 1, NULL, '16990000.00', '30000.00', '0.00', '17020000.00', 'cod', NULL, 'pending', 'pending', 'Quân Trương M', 'quanm9677@gmail.com', '12345678', 'hà nội', 'thanh xuân', 'khương đình', '236 khương đình', NULL, NULL, '2025-12-04 05:45:18', '2025-12-04 05:45:18'),
(3, 10, 1, NULL, '16990000.00', '30000.00', '0.00', '17020000.00', 'cod', NULL, 'pending', 'pending', 'Quân Trương M', 'quanm9677@gmail.com', '12345678', 'hà nội', 'thanh xuân', 'khương đình', '236 khương đình', NULL, NULL, '2025-12-04 05:52:34', '2025-12-04 05:52:34'),
(4, 9, 9, 3, '78470000.00', '30000.00', '7847000.00', '70653000.00', 'cod', NULL, 'pending', 'pending', 'Quân Trương M', 'tam@gmail.com', '12345678', 'hà nội', 'thanh xuân', 'khương đình', '236 khương đình', NULL, NULL, '2025-12-04 10:51:15', '2025-12-04 10:51:15'),
(5, 12, 9, 3, '5690000.00', '30000.00', '569000.00', '5151000.00', 'cod', NULL, 'pending', 'pending', 'Quân Trương M', 'tam@gmail.com', '12345678', 'hà nội', NULL, 'khương đình', '236 khương đình', NULL, NULL, '2025-12-04 11:01:15', '2025-12-04 11:01:15'),
(6, 13, 9, 3, '16990000.00', '30000.00', '1699000.00', '15321000.00', 'cod', NULL, 'pending', 'pending', 'Quân Trương M', 'quanm9677@gmail.com', '12345678', 'hà nội', 'thanh xuân', 'khương đình', '236 khương đình', NULL, NULL, '2025-12-04 11:16:53', '2025-12-04 11:16:53'),
(7, 14, 9, 3, '16990000.00', '30000.00', '1699000.00', '15321000.00', 'cod', NULL, 'paid', 'completed', 'Quân Trương M', 'tam@gmail.com', '12345678', 'hà nội', 'thanh xuân', 'khương đình', '236 khương đình', NULL, '2025-12-16 03:13:32', '2025-12-04 11:23:10', '2025-12-16 03:13:32'),
(8, 15, 9, 2, '40990000.00', '30000.00', '100000.00', '40920000.00', 'cod', NULL, 'paid', 'completed', 'Quân Trương M', 'tam@gmail.com', '0384438946', 'hà nội', 'thanh xuân', NULL, '236 khwong ddinh', NULL, '2025-12-08 20:19:31', '2025-12-08 20:18:20', '2025-12-08 20:19:31'),
(9, 16, 9, 2, '45520000.00', '30000.00', '100000.00', '45450000.00', 'cod', NULL, 'pending', 'pending', 'Quân Trương M', 'tam@gmail.com', '12345678', 'hà nội', NULL, NULL, '236 khương đình', NULL, NULL, '2025-12-11 08:10:54', '2025-12-11 08:10:54'),
(10, 17, 9, NULL, '101940000.00', '30000.00', '0.00', '101970000.00', 'cod', NULL, 'pending', 'pending', 'Quân Trương M', 'tam@gmail.com', '12345678', 'hà nội', NULL, NULL, '236 khương đình', NULL, NULL, '2025-12-11 08:11:39', '2025-12-11 08:11:39'),
(11, 18, 9, 2, '16990000.00', '30000.00', '100000.00', '16920000.00', 'cod', NULL, 'pending', 'pending', 'Quân Trương M', 'tam@gmail.com', '12345678', 'hà nội', NULL, NULL, '236 khương đình', NULL, NULL, '2025-12-11 09:12:32', '2025-12-11 09:12:32'),
(12, 19, 9, 2, '16990000.00', '30000.00', '100000.00', '16920000.00', 'vnpay', NULL, 'pending', 'pending', 'Quân Trương M', 'tam@gmail.com', '12345678', 'hà nội', NULL, NULL, '236 khương đình', NULL, NULL, '2025-12-11 09:13:05', '2025-12-11 09:13:05'),
(13, 20, 9, 1, '16990000.00', '30000.00', '1699000.00', '15321000.00', 'vnpay', NULL, 'pending', 'pending', 'Quân Trương M', 'tam@gmail.com', '12345678', 'hà nội', NULL, NULL, '236 khương đình', NULL, NULL, '2025-12-11 09:23:52', '2025-12-11 09:23:52'),
(14, 21, 9, 2, '5690000.00', '30000.00', '100000.00', '5620000.00', 'vnpay', NULL, 'pending', 'pending', 'Quân Trương M', 'tam@gmail.com', '12345678', 'hà nội', NULL, NULL, '236 khương đình', NULL, NULL, '2025-12-11 09:26:07', '2025-12-11 09:26:07'),
(15, 22, 9, 2, '16990000.00', '30000.00', '100000.00', '16920000.00', 'vnpay', NULL, 'pending', 'pending', 'Quân Trương M', 'tam@gmail.com', '12345678', 'hà nội', NULL, NULL, '236 khương đình', NULL, NULL, '2025-12-11 10:16:34', '2025-12-11 10:16:34'),
(16, 23, 9, NULL, '17990000.00', '30000.00', '0.00', '18020000.00', 'vnpay', NULL, 'pending', 'pending', 'Quân Trương M', 'tam@gmail.com', '12345678', 'hà nội', NULL, NULL, '236 khương đình', NULL, NULL, '2025-12-11 10:21:03', '2025-12-11 10:21:03'),
(17, 24, 9, NULL, '40990000.00', '30000.00', '0.00', '41020000.00', 'vnpay', NULL, 'pending', 'pending', 'Quân Trương M', 'tam@gmail.com', '12345678', 'hà nội', NULL, NULL, '236 khương đình', NULL, NULL, '2025-12-11 10:23:53', '2025-12-11 10:23:53'),
(18, 25, 9, NULL, '5690000.00', '30000.00', '0.00', '5720000.00', 'vnpay', NULL, 'pending', 'pending', 'Quân Trương M', 'tam@gmail.com', '12345678', 'hà nội', NULL, NULL, '236 khương đình', NULL, NULL, '2025-12-11 10:27:21', '2025-12-11 10:27:21'),
(19, 26, 9, NULL, '16990000.00', '30000.00', '0.00', '17020000.00', 'vnpay', NULL, 'pending', 'pending', 'Quân Trương M', 'tam@gmail.com', '12345678', 'hà nội', NULL, NULL, '236 khương đình', NULL, NULL, '2025-12-11 10:29:30', '2025-12-11 10:29:30'),
(20, 27, 9, 1, '22760000.00', '30000.00', '2276000.00', '20514000.00', 'vnpay', NULL, 'pending', 'pending', 'Quân Trương M', 'tam@gmail.com', '12345678', 'hà nội', 'thanh xuân', 'khương đình', '236 khương đình', NULL, NULL, '2025-12-11 10:31:06', '2025-12-11 10:31:06'),
(21, 28, 9, 1, '16990000.00', '30000.00', '1699000.00', '15321000.00', 'vnpay', NULL, 'pending', 'pending', 'Quân Trương M', 'tam@gmail.com', '12345678', 'hà nội', NULL, NULL, '236 khương đình', NULL, NULL, '2025-12-11 10:35:59', '2025-12-11 10:35:59'),
(22, 29, 9, NULL, '16990000.00', '30000.00', '0.00', '17020000.00', 'vnpay', NULL, 'pending', 'pending', 'Quân Trương M', 'tam@gmail.com', '12345678', 'hà nội', NULL, NULL, '236 khương đình', NULL, NULL, '2025-12-11 10:39:18', '2025-12-11 10:39:18'),
(23, 30, 9, 1, '40990000.00', '30000.00', '4099000.00', '36921000.00', 'vnpay', NULL, 'pending', 'pending', 'Quân Trương M', 'tam@gmail.com', '12345678', 'hà nội', NULL, NULL, '236 khương đình', NULL, NULL, '2025-12-11 10:45:40', '2025-12-11 10:45:40'),
(24, 31, 9, 1, '17990000.00', '30000.00', '1799000.00', '16221000.00', 'vnpay', NULL, 'pending', 'pending', 'Quân Trương M', 'tam@gmail.com', '12345678', 'hà nội', NULL, NULL, '236 khương đình', NULL, NULL, '2025-12-11 10:49:57', '2025-12-11 10:49:57'),
(25, 32, 9, NULL, '17990000.00', '30000.00', '0.00', '18020000.00', 'vnpay', NULL, 'pending', 'pending', 'Quân Trương M', 'tam@gmail.com', '12345678', 'hà nội', NULL, NULL, '236 khương đình', NULL, NULL, '2025-12-11 10:58:41', '2025-12-11 10:58:41'),
(26, 33, 9, NULL, '17990000.00', '30000.00', '0.00', '18020000.00', 'vnpay', NULL, 'pending', 'pending', 'Quân Trương M', 'tam@gmail.com', '12345678', 'hà nội', NULL, NULL, '236 khương đình', NULL, NULL, '2025-12-11 11:01:40', '2025-12-11 11:01:40'),
(27, 34, 9, 1, '17990000.00', '30000.00', '1799000.00', '16221000.00', 'vnpay', NULL, 'pending', 'pending', 'Quân Trương M', 'tam@gmail.com', '12345678', 'hà nội', 'thanh xuân', 'khương đình', '236 khương đình', NULL, NULL, '2025-12-11 11:06:08', '2025-12-11 11:06:08'),
(28, 35, 9, NULL, '16990000.00', '30000.00', '0.00', '17020000.00', 'vnpay', NULL, 'pending', 'pending', 'Quân Trương M', 'tam@gmail.com', '12345678', 'hà nội', NULL, NULL, '236 khương đình', NULL, NULL, '2025-12-11 11:09:19', '2025-12-11 11:09:19'),
(29, 36, 9, NULL, '16990000.00', '30000.00', '0.00', '17020000.00', 'vnpay', NULL, 'pending', 'pending', 'Quân Trương M', 'tam@gmail.com', '12345678', 'hà nội', NULL, NULL, '236 khương đình', NULL, NULL, '2025-12-11 11:10:21', '2025-12-11 11:10:21'),
(30, 37, 9, NULL, '16990000.00', '30000.00', '0.00', '17020000.00', 'vnpay', NULL, 'pending', 'pending', 'Quân Trương M', 'tam@gmail.com', '12345678', 'hà nội', NULL, NULL, '236 khương đình', NULL, NULL, '2025-12-11 11:12:45', '2025-12-11 11:12:45'),
(31, 38, 9, NULL, '16990000.00', '30000.00', '0.00', '17020000.00', 'vnpay', NULL, 'pending', 'pending', 'Quân Trương M', 'tam@gmail.com', '12345678', 'hà nội', NULL, NULL, '236 khương đình', NULL, NULL, '2025-12-11 11:13:31', '2025-12-11 11:13:31'),
(32, 39, 9, NULL, '16990000.00', '30000.00', '0.00', '17020000.00', 'vnpay', NULL, 'pending', 'pending', 'Quân Trương M', 'tam@gmail.com', '12345678', 'hà nội', NULL, NULL, '236 khương đình', NULL, NULL, '2025-12-11 11:15:46', '2025-12-11 11:15:46'),
(33, 40, 9, NULL, '16990000.00', '30000.00', '0.00', '17020000.00', 'vnpay', NULL, 'paid', 'pending', 'Quân Trương M', 'tam@gmail.com', '12345678', 'hà nội', NULL, NULL, '236 khương đình', NULL, '2025-12-16 02:53:30', '2025-12-11 11:18:25', '2025-12-16 02:59:33'),
(34, 41, 9, NULL, '16990000.00', '30000.00', '0.00', '17020000.00', 'vnpay', NULL, 'pending', 'pending', 'Quân Trương M', 'tam@gmail.com', '12345678', 'hà nội', NULL, NULL, '236 khương đình', NULL, NULL, '2025-12-11 11:28:08', '2025-12-11 11:28:08'),
(35, 42, 9, NULL, '16990000.00', '30000.00', '0.00', '17020000.00', 'vnpay', NULL, 'pending', 'pending', 'Quân Trương M', 'tam@gmail.com', '12345678', 'hà nội', NULL, NULL, '236 khương đình', NULL, NULL, '2025-12-11 11:31:55', '2025-12-11 11:31:55'),
(38, 43, 9, 1, '16990000.00', '30000.00', '1699000.00', '15321000.00', 'vnpay', NULL, 'pending', 'pending', 'Quân Trương M', 'tam@gmail.com', '12345678', 'hà nội', NULL, NULL, '236 khương đình', NULL, NULL, '2025-12-12 03:00:35', '2025-12-12 03:00:35'),
(39, 44, 9, 1, '16990000.00', '30000.00', '1699000.00', '15321000.00', 'vnpay', NULL, 'paid', 'shipping', 'Quân Trương M', 'tam@gmail.com', '12345678', 'hà nội', NULL, NULL, '236 khương đình', NULL, '2025-12-16 03:12:04', '2025-12-12 03:27:01', '2025-12-16 03:12:12'),
(40, 45, 9, NULL, '16990000.00', '30000.00', '0.00', '17020000.00', 'vnpay', NULL, 'paid', 'completed', 'Quân Trương M', 'tam@gmail.com', '12345678', 'hà nội', NULL, NULL, '236 khương đình', NULL, '2025-12-16 03:05:16', '2025-12-12 03:32:58', '2025-12-16 03:05:16'),
(41, 46, 9, 1, '16990000.00', '30000.00', '1699000.00', '15321000.00', 'vnpay', NULL, 'paid', 'completed', 'Quân Trương M', 'tam@gmail.com', '12345678', 'hà nội', NULL, NULL, '236 khương đình', NULL, '2025-12-16 05:17:34', '2025-12-16 05:16:54', '2025-12-16 05:17:58'),
(42, 47, 9, NULL, '51900000.00', '30000.00', '0.00', '51930000.00', 'cod', NULL, 'pending', 'pending', 'Quân Trương M', 'tam@gmail.com', '12345678', 'hà nội', NULL, NULL, '236 khương đình', NULL, NULL, '2025-12-17 03:26:23', '2025-12-17 03:26:23'),
(43, 48, 9, NULL, '31140000.00', '30000.00', '0.00', '31170000.00', 'cod', NULL, 'pending', 'pending', 'Quân Trương M', 'tam@gmail.com', '12345678', 'hà nội', NULL, NULL, '236 khương đình', NULL, NULL, '2025-12-17 03:27:36', '2025-12-17 03:27:36');

-- --------------------------------------------------------

--
-- Table structure for table `order_items`
--

CREATE TABLE `order_items` (
  `id` bigint UNSIGNED NOT NULL,
  `order_id` bigint UNSIGNED NOT NULL,
  `product_id` bigint UNSIGNED DEFAULT NULL,
  `product_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `product_image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `quantity` int NOT NULL DEFAULT '1',
  `unit_price` decimal(12,2) NOT NULL,
  `total_price` decimal(12,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `order_items`
--

INSERT INTO `order_items` (`id`, `order_id`, `product_id`, `product_name`, `product_image`, `quantity`, `unit_price`, `total_price`, `created_at`, `updated_at`) VALUES
(1, 2, 9, 'iPhone 15', 'products/UejXoqYAFA1Ir0sONBAhYoS83SWkSNWVkuijOo24.webp', 1, '16990000.00', '16990000.00', '2025-12-04 05:45:18', '2025-12-04 05:45:18'),
(2, 3, 9, 'iPhone 15', 'products/UejXoqYAFA1Ir0sONBAhYoS83SWkSNWVkuijOo24.webp', 1, '16990000.00', '16990000.00', '2025-12-04 05:52:34', '2025-12-04 05:52:34'),
(3, 4, 9, 'iPhone 15', 'products/UejXoqYAFA1Ir0sONBAhYoS83SWkSNWVkuijOo24.webp', 1, '16990000.00', '16990000.00', '2025-12-04 10:51:15', '2025-12-04 10:51:15'),
(4, 4, 9, 'iPhone 15', 'products/UejXoqYAFA1Ir0sONBAhYoS83SWkSNWVkuijOo24.webp', 1, '20490000.00', '20490000.00', '2025-12-04 10:51:15', '2025-12-04 10:51:15'),
(5, 4, 5, 'Samsung Galaxy Z Fold7 12GB', 'products/ehEGZYvwnnWHumJ96IPHZXfE22RglAO0P4poat4D.jpg', 1, '40990000.00', '40990000.00', '2025-12-04 10:51:15', '2025-12-04 10:51:15'),
(6, 5, 6, 'AirPods Pro 2', 'products/FdV6RDER1jlgAvZZ8DxdlYL9jg84qyQkjN3QOlxX.png', 1, '5690000.00', '5690000.00', '2025-12-04 11:01:15', '2025-12-04 11:01:15'),
(7, 6, 9, 'iPhone 15', 'products/UejXoqYAFA1Ir0sONBAhYoS83SWkSNWVkuijOo24.webp', 1, '16990000.00', '16990000.00', '2025-12-04 11:16:53', '2025-12-04 11:16:53'),
(8, 7, 9, 'iPhone 15', 'products/UejXoqYAFA1Ir0sONBAhYoS83SWkSNWVkuijOo24.webp', 1, '16990000.00', '16990000.00', '2025-12-04 11:23:10', '2025-12-04 11:23:10'),
(9, 8, 5, 'Samsung Galaxy Z Fold7 12GB', 'products/ehEGZYvwnnWHumJ96IPHZXfE22RglAO0P4poat4D.jpg', 1, '40990000.00', '40990000.00', '2025-12-08 20:18:20', '2025-12-08 20:18:20'),
(10, 9, 6, 'AirPods Pro 2', 'products/FdV6RDER1jlgAvZZ8DxdlYL9jg84qyQkjN3QOlxX.png', 8, '5690000.00', '45520000.00', '2025-12-11 08:10:54', '2025-12-11 08:10:54'),
(11, 10, 9, 'iPhone 15', 'products/UejXoqYAFA1Ir0sONBAhYoS83SWkSNWVkuijOo24.webp', 6, '16990000.00', '101940000.00', '2025-12-11 08:11:39', '2025-12-11 08:11:39'),
(12, 11, 9, 'iPhone 15', 'products/UejXoqYAFA1Ir0sONBAhYoS83SWkSNWVkuijOo24.webp', 1, '16990000.00', '16990000.00', '2025-12-11 09:12:32', '2025-12-11 09:12:32'),
(13, 12, 9, 'iPhone 15', 'products/UejXoqYAFA1Ir0sONBAhYoS83SWkSNWVkuijOo24.webp', 1, '16990000.00', '16990000.00', '2025-12-11 09:13:05', '2025-12-11 09:13:05'),
(14, 13, 9, 'iPhone 15', 'products/UejXoqYAFA1Ir0sONBAhYoS83SWkSNWVkuijOo24.webp', 1, '16990000.00', '16990000.00', '2025-12-11 09:23:52', '2025-12-11 09:23:52'),
(15, 14, 6, 'AirPods Pro 2', 'products/FdV6RDER1jlgAvZZ8DxdlYL9jg84qyQkjN3QOlxX.png', 1, '5690000.00', '5690000.00', '2025-12-11 09:26:07', '2025-12-11 09:26:07'),
(16, 15, 9, 'iPhone 15', 'products/UejXoqYAFA1Ir0sONBAhYoS83SWkSNWVkuijOo24.webp', 1, '16990000.00', '16990000.00', '2025-12-11 10:16:34', '2025-12-11 10:16:34'),
(17, 16, 9, 'iPhone 15', 'products/UejXoqYAFA1Ir0sONBAhYoS83SWkSNWVkuijOo24.webp', 1, '17990000.00', '17990000.00', '2025-12-11 10:21:03', '2025-12-11 10:21:03'),
(18, 17, 5, 'Samsung Galaxy Z Fold7 12GB', 'products/ehEGZYvwnnWHumJ96IPHZXfE22RglAO0P4poat4D.jpg', 1, '40990000.00', '40990000.00', '2025-12-11 10:23:53', '2025-12-11 10:23:53'),
(19, 18, 6, 'AirPods Pro 2', 'products/FdV6RDER1jlgAvZZ8DxdlYL9jg84qyQkjN3QOlxX.png', 1, '5690000.00', '5690000.00', '2025-12-11 10:27:21', '2025-12-11 10:27:21'),
(20, 19, 9, 'iPhone 15', 'products/UejXoqYAFA1Ir0sONBAhYoS83SWkSNWVkuijOo24.webp', 1, '16990000.00', '16990000.00', '2025-12-11 10:29:30', '2025-12-11 10:29:30'),
(21, 20, 6, 'AirPods Pro 2', 'products/FdV6RDER1jlgAvZZ8DxdlYL9jg84qyQkjN3QOlxX.png', 4, '5690000.00', '22760000.00', '2025-12-11 10:31:06', '2025-12-11 10:31:06'),
(22, 21, 9, 'iPhone 15', 'products/UejXoqYAFA1Ir0sONBAhYoS83SWkSNWVkuijOo24.webp', 1, '16990000.00', '16990000.00', '2025-12-11 10:35:59', '2025-12-11 10:35:59'),
(23, 22, 9, 'iPhone 15', 'products/UejXoqYAFA1Ir0sONBAhYoS83SWkSNWVkuijOo24.webp', 1, '16990000.00', '16990000.00', '2025-12-11 10:39:18', '2025-12-11 10:39:18'),
(24, 23, 5, 'Samsung Galaxy Z Fold7 12GB', 'products/ehEGZYvwnnWHumJ96IPHZXfE22RglAO0P4poat4D.jpg', 1, '40990000.00', '40990000.00', '2025-12-11 10:45:40', '2025-12-11 10:45:40'),
(25, 24, 9, 'iPhone 15', 'products/UejXoqYAFA1Ir0sONBAhYoS83SWkSNWVkuijOo24.webp', 1, '17990000.00', '17990000.00', '2025-12-11 10:49:57', '2025-12-11 10:49:57'),
(26, 25, 9, 'iPhone 15', 'products/UejXoqYAFA1Ir0sONBAhYoS83SWkSNWVkuijOo24.webp', 1, '17990000.00', '17990000.00', '2025-12-11 10:58:41', '2025-12-11 10:58:41'),
(27, 26, 9, 'iPhone 15', 'products/UejXoqYAFA1Ir0sONBAhYoS83SWkSNWVkuijOo24.webp', 1, '17990000.00', '17990000.00', '2025-12-11 11:01:40', '2025-12-11 11:01:40'),
(28, 27, 9, 'iPhone 15', 'products/UejXoqYAFA1Ir0sONBAhYoS83SWkSNWVkuijOo24.webp', 1, '17990000.00', '17990000.00', '2025-12-11 11:06:08', '2025-12-11 11:06:08'),
(29, 28, 9, 'iPhone 15', 'products/UejXoqYAFA1Ir0sONBAhYoS83SWkSNWVkuijOo24.webp', 1, '16990000.00', '16990000.00', '2025-12-11 11:09:19', '2025-12-11 11:09:19'),
(30, 29, 9, 'iPhone 15', 'products/UejXoqYAFA1Ir0sONBAhYoS83SWkSNWVkuijOo24.webp', 1, '16990000.00', '16990000.00', '2025-12-11 11:10:21', '2025-12-11 11:10:21'),
(31, 30, 9, 'iPhone 15', 'products/UejXoqYAFA1Ir0sONBAhYoS83SWkSNWVkuijOo24.webp', 1, '16990000.00', '16990000.00', '2025-12-11 11:12:45', '2025-12-11 11:12:45'),
(32, 31, 9, 'iPhone 15', 'products/UejXoqYAFA1Ir0sONBAhYoS83SWkSNWVkuijOo24.webp', 1, '16990000.00', '16990000.00', '2025-12-11 11:13:31', '2025-12-11 11:13:31'),
(33, 32, 9, 'iPhone 15', 'products/UejXoqYAFA1Ir0sONBAhYoS83SWkSNWVkuijOo24.webp', 1, '16990000.00', '16990000.00', '2025-12-11 11:15:46', '2025-12-11 11:15:46'),
(34, 33, 9, 'iPhone 15', 'products/UejXoqYAFA1Ir0sONBAhYoS83SWkSNWVkuijOo24.webp', 1, '16990000.00', '16990000.00', '2025-12-11 11:18:25', '2025-12-11 11:18:25'),
(35, 34, 9, 'iPhone 15', 'products/UejXoqYAFA1Ir0sONBAhYoS83SWkSNWVkuijOo24.webp', 1, '16990000.00', '16990000.00', '2025-12-11 11:28:08', '2025-12-11 11:28:08'),
(36, 35, 9, 'iPhone 15', 'products/UejXoqYAFA1Ir0sONBAhYoS83SWkSNWVkuijOo24.webp', 1, '16990000.00', '16990000.00', '2025-12-11 11:31:55', '2025-12-11 11:31:55'),
(39, 38, 9, 'iPhone 15', 'products/UejXoqYAFA1Ir0sONBAhYoS83SWkSNWVkuijOo24.webp', 1, '16990000.00', '16990000.00', '2025-12-12 03:00:35', '2025-12-12 03:00:35'),
(40, 39, 9, 'iPhone 15', 'products/UejXoqYAFA1Ir0sONBAhYoS83SWkSNWVkuijOo24.webp', 1, '16990000.00', '16990000.00', '2025-12-12 03:27:01', '2025-12-12 03:27:01'),
(41, 40, 9, 'iPhone 15', 'products/UejXoqYAFA1Ir0sONBAhYoS83SWkSNWVkuijOo24.webp', 1, '16990000.00', '16990000.00', '2025-12-12 03:32:58', '2025-12-12 03:32:58'),
(42, 41, 9, 'iPhone 15', 'products/UejXoqYAFA1Ir0sONBAhYoS83SWkSNWVkuijOo24.webp', 1, '16990000.00', '16990000.00', '2025-12-16 05:16:54', '2025-12-16 05:16:54'),
(43, 42, 6, 'AirPods Pro 2', 'products/FdV6RDER1jlgAvZZ8DxdlYL9jg84qyQkjN3QOlxX.png', 10, '5190000.00', '51900000.00', '2025-12-17 03:26:23', '2025-12-17 03:26:23'),
(44, 43, 6, 'AirPods Pro 2', 'products/FdV6RDER1jlgAvZZ8DxdlYL9jg84qyQkjN3QOlxX.png', 6, '5190000.00', '31140000.00', '2025-12-17 03:27:36', '2025-12-17 03:27:36');

-- --------------------------------------------------------

--
-- Table structure for table `order_returns`
--

CREATE TABLE `order_returns` (
  `id` bigint NOT NULL,
  `order_item_id` bigint NOT NULL,
  `reason` text,
  `status` enum('pending','approved','rejected') DEFAULT 'pending',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `order_returns`
--

INSERT INTO `order_returns` (`id`, `order_item_id`, `reason`, `status`, `created_at`, `updated_at`) VALUES
(1, 2, 'Hàng lỗi mic', 'pending', '2025-10-31 14:14:23', '2025-10-31 14:14:23');

-- --------------------------------------------------------

--
-- Table structure for table `payments`
--

CREATE TABLE `payments` (
  `id` bigint NOT NULL,
  `order_id` bigint NOT NULL,
  `payment_method` enum('cash','momo','vnpay') NOT NULL,
  `payment_status` enum('pending','paid','failed') DEFAULT 'pending',
  `paid_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `payments`
--

INSERT INTO `payments` (`id`, `order_id`, `payment_method`, `payment_status`, `paid_at`, `created_at`, `updated_at`) VALUES
(1, 1, 'momo', 'paid', '2025-10-31 14:14:23', '2025-10-31 14:14:23', '2025-10-31 14:14:23');

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` bigint NOT NULL,
  `name` varchar(200) NOT NULL,
  `image` varchar(255) DEFAULT NULL,
  `price` decimal(12,0) NOT NULL,
  `slug` varchar(200) NOT NULL,
  `description` longtext,
  `has_variant` tinyint(1) DEFAULT '0',
  `category_id` bigint NOT NULL,
  `brand_id` bigint NOT NULL,
  `views` bigint DEFAULT '0',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `name`, `image`, `price`, `slug`, `description`, `has_variant`, `category_id`, `brand_id`, `views`, `created_at`, `updated_at`) VALUES
(1, 'iPhone 15 Pro 256GB', 'products/75B73Bgvi6D8y6j2WtvMFjr6aD4GZ1ZXVKJa5FBl.webp', '27990000', 'iphone-15-pro-256gb', 'Flagship Apple', 0, 1, 1, 134, '2025-10-31 14:14:23', '2025-11-20 04:19:38'),
(2, 'Samsung Galaxy S24 256GB', 'https://example.com/s24.jpg', '20990000', 'samsung-galaxy-s24-256gb', 'Flagship Samsung', 0, 1, 2, 90, '2025-10-31 14:14:23', '2025-10-31 14:14:23'),
(3, 'Xiaomi Redmi Note 13', 'https://example.com/rn13.jpg', '5990000', 'xiaomi-redmi-note-13', 'Giá tốt', 0, 1, 3, 83, '2025-10-31 14:14:23', '2025-12-16 02:32:42'),
(4, 'OPPO Reno 12', 'products/itw42M7gY1n0eHYbIKIOaK1LmKIRYtp9ILG5I6qa.jpg', '12990000', 'oppo-reno-12', 'Thiết kế đẹp', 0, 1, 4, 67, '2025-10-31 14:14:23', '2025-12-16 02:32:02'),
(5, 'Samsung Galaxy Z Fold7 12GB', 'products/ehEGZYvwnnWHumJ96IPHZXfE22RglAO0P4poat4D.jpg', '46990000', 'samsung-galaxy-z-fold7-12gb', 'Samsung Galaxy Z Fold7 12GB', 0, 1, 2, 56, '2025-10-31 14:14:23', '2025-12-15 03:57:25'),
(6, 'AirPods Pro 2', 'products/FdV6RDER1jlgAvZZ8DxdlYL9jg84qyQkjN3QOlxX.png', '6190000', 'airpods-pro-2', 'Tai nghe chống ồn', 0, 4, 1, 190, '2025-10-31 14:14:23', '2025-12-17 03:29:01'),
(9, 'iPhone 15', 'products/UejXoqYAFA1Ir0sONBAhYoS83SWkSNWVkuijOo24.webp', '17990000', 'iphone-15', '📱 Thông số cơ bản & Thiết kế\r\n\r\nMàn hình: 6.1 inch OLED “Super Retina XDR”, độ phân giải 2556×1179 px, mật độ điểm ảnh ~460 ppi. Màn hình hỗ trợ HDR, True Tone, dải màu rộng P3, Haptic Touch, và có “Dynamic Island” thay cho notch. \r\nHỗ Trợ Apple\r\n+1\r\n\r\nChất liệu: Khung nhôm, mặt trước kính bảo vệ “Ceramic Shield”, mặt sau là kính pha màu. \r\nHỗ Trợ Apple\r\n\r\nKích thước & trọng lượng: 147.6 mm × 71.6 mm × 7.80 mm; nặng khoảng 171 g. \r\nHỗ Trợ Apple\r\n+1\r\n\r\nMàu sắc: Có các lựa chọn màu: Đen, Xanh Dương, Xanh Lá, Vàng, Hồng. \r\nHỗ Trợ Apple\r\n\r\nBộ nhớ: 128 GB, 256 GB hoặc 512 GB. \r\nHỗ Trợ Apple\r\n\r\n⚙️ Phần cứng & Hiệu năng\r\n\r\nChip xử lý: A16 Bionic — CPU 6 lõi (2 lõi hiệu năng + 4 lõi tiết kiệm điện), GPU 5 lõi, Neural Engine 16 lõi. \r\nHỗ Trợ Apple\r\n+1\r\n\r\nNhờ vậy máy đủ mạnh để xử lý đa nhiệm, chỉnh ảnh/video, chơi game, và các tác vụ nặng mà vẫn tiết kiệm pin. \r\nApple\r\n+1\r\n\r\nCổng/ Sạc: chuyển sang sử dụng USB-C thay cho Lightning — thuận tiện, phổ quát hơn. \r\nTechRadar\r\n+1\r\n\r\n📸 Camera & Ảnh / Video\r\n\r\nCamera sau kép:\r\n\r\nCamera chính: 48 MP, khẩu độ ƒ/1.6, hỗ trợ chống rung cảm biến (sensor-shift OIS), cho ảnh chất lượng cao, chi tiết tốt. \r\nHỗ Trợ Apple\r\n+1\r\n\r\nCamera góc rộng (Ultra Wide): 12 MP, giúp chụp ảnh góc rộng, phong cảnh, nhóm người. \r\nHỗ Trợ Apple\r\n\r\nHỗ trợ nhiều tính năng nhiếp ảnh: HDR, chế độ ban đêm (Night mode), Smart HDR, Photonic Engine, chụp panorama, Portrait mode, Live Photos, v.v. \r\nHỗ Trợ Apple\r\n+1\r\n\r\nCamera trước (selfie) chuẩn chất lượng — đủ tốt cho video call, selfie, Face ID,... \r\n9meters\r\n+1\r\n\r\n🌐 Các tính năng khác\r\n\r\nKháng nước & bụi: đạt chuẩn IP68 — tức có thể chịu nước ở độ sâu tới 6 mét trong 30 phút. \r\nHỗ Trợ Apple\r\n+1\r\n\r\nKết nối hiện đại: hỗ trợ 5G, Wi-Fi, Bluetooth, eSIM — thuận tiện khi đi du lịch hoặc di chuyển quốc tế. \r\nApple\r\n+1\r\n\r\nTính năng an toàn & định vị: Có chip Ultra Wideband thế hệ 2, giúp cải thiện khả năng định vị chính xác trong các tính năng như “Find My” / tìm bạn bè, tìm thiết bị. \r\nApple\r\n+1\r\n\r\n🎯 Ưu & nhược điểm tổng quan\r\n\r\nƯu điểm\r\n\r\nMàn hình OLED sáng, hiển thị đẹp, có Dynamic Island — trải nghiệm hiện đại.\r\n\r\nHiệu năng mạnh mẽ, đáp ứng tốt cả công việc, giải trí, chỉnh ảnh/video.\r\n\r\nCamera cải tiến rõ với cảm biến 48 MP — ảnh chi tiết, đa dụng.\r\n\r\nUSB-C phổ quát, dễ sạc & đồng bộ với nhiều thiết bị.\r\n\r\nThiết kế gọn, nhẹ, màu sắc đa dạng, kháng nước/bụi.\r\n\r\nHạn chế / điểm cần lưu ý\r\n\r\nMàn hình ở mức 60 Hz — không “mượt” bằng các máy 120 Hz / 144 Hz khi cuộn/lướt.\r\n\r\nNếu bạn cần zoom quang học mạnh hoặc tính năng Pro nâng cao — bản tiêu chuẩn có thể không đủ; cần bản Pro / Pro Max.\r\n\r\nMột số tính năng “Pro-level” sẽ chỉ có ở bản cao hơn.', 1, 1, 1, 141, '2025-11-19 13:58:20', '2025-12-17 04:01:13');

-- --------------------------------------------------------

--
-- Table structure for table `product_images`
--

CREATE TABLE `product_images` (
  `id` bigint NOT NULL,
  `product_id` bigint NOT NULL,
  `image_url` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `product_images`
--

INSERT INTO `product_images` (`id`, `product_id`, `image_url`, `created_at`, `updated_at`) VALUES
(1, 1, 'https://example.com/ip15pro_1.jpg', '2025-10-31 14:14:23', '2025-10-31 14:14:23'),
(2, 1, 'https://example.com/ip15pro_2.jpg', '2025-10-31 14:14:23', '2025-10-31 14:14:23'),
(3, 2, 'https://example.com/s24_1.jpg', '2025-10-31 14:14:23', '2025-10-31 14:14:23'),
(4, 3, 'https://example.com/rn13_1.jpg', '2025-10-31 14:14:23', '2025-10-31 14:14:23'),
(5, 4, 'https://example.com/reno12_1.jpg', '2025-10-31 14:14:23', '2025-10-31 14:14:23'),
(6, 5, 'https://example.com/ipadair_1.jpg', '2025-10-31 14:14:23', '2025-10-31 14:14:23'),
(7, 6, 'https://example.com/airpodspro2_1.jpg', '2025-10-31 14:14:23', '2025-10-31 14:14:23'),
(8, 9, 'https://example.com/ip15pm_1.jpg', '2025-11-19 13:58:20', '2025-11-19 13:58:20'),
(10, 9, 'product_images/1Np6XJeZFrl9zQFghP7a6KccBVjUET95hfLPDSEJ.webp', '2025-11-21 05:38:41', '2025-11-21 05:38:41'),
(11, 9, 'product_images/Vd0GjI8G07pIq2ZS41ALI7wIkEXrTivFZPOcCmRI.webp', '2025-11-21 05:38:41', '2025-11-21 05:38:41'),
(12, 9, 'product_images/Qb69fZBcvsNbASnaci3e0BBsSrDEst95Mr3uCgLa.webp', '2025-11-21 05:38:41', '2025-11-21 05:38:41'),
(13, 9, 'product_images/igJHwfyfWMBMWqXs5968fHibPKoI8sZkDiVwaZ2B.webp', '2025-11-21 05:38:41', '2025-11-21 05:38:41'),
(14, 9, 'product_images/NADoOR21lEG9PwJhZQEIGq8A5CZvXT3oCXXtNxbe.webp', '2025-11-21 05:38:41', '2025-11-21 05:38:41'),
(15, 6, 'product_images/tROXQsYtznSrKdQvkz4Huw0ipmFpk382tiWVMsa9.webp', '2025-12-01 06:55:12', '2025-12-01 06:55:12'),
(16, 6, 'product_images/XWL2y1QYL5MaiN4qbfQU7SN1tnIG4MaxWQ0nSkgY.webp', '2025-12-01 06:55:12', '2025-12-01 06:55:12'),
(17, 6, 'product_images/DweoVEbm00dovt5uFxo4CQCy60ifffh6pSNNbUaq.webp', '2025-12-01 06:55:12', '2025-12-01 06:55:12'),
(18, 6, 'product_images/dEB0AK8qS5CC0qwxo1MXGlvKFw6KlCqQt4BQqXf1.webp', '2025-12-01 06:55:12', '2025-12-01 06:55:12'),
(19, 6, 'product_images/VgedpqzYdYmHjiel09kM9fxbqbbETnHD7CHdNhyf.webp', '2025-12-01 06:55:12', '2025-12-01 06:55:12'),
(20, 6, 'product_images/ud2S1CxoW3BI5iMiW9ZiuUT6A1OU32iGarYef0KV.webp', '2025-12-01 06:55:12', '2025-12-01 06:55:12'),
(21, 5, 'product_images/jOWheu1PH1aH5QaZtiaE6tNEZrPvt40SAtKSrJQM.webp', '2025-12-04 10:26:02', '2025-12-04 10:26:02'),
(22, 5, 'product_images/yg1M9cmcpnuCZAQSyDSJ4P9KM8oT31mfn1FRTjZe.webp', '2025-12-04 10:26:02', '2025-12-04 10:26:02'),
(23, 5, 'product_images/2lvMaOUhOovgasDITq3KrhYx3TP01LJ48itSWz4a.webp', '2025-12-04 10:26:02', '2025-12-04 10:26:02'),
(24, 5, 'product_images/KTctOCwV7pIfTNuisxIKgM3OtaGCvyTHr29D1S9A.webp', '2025-12-04 10:26:02', '2025-12-04 10:26:02'),
(25, 5, 'product_images/M8w2PAo0QsPhFiEfRDWU1yBCgGaCL0zEH9YYMFeT.webp', '2025-12-04 10:26:02', '2025-12-04 10:26:02'),
(26, 5, 'product_images/igx3La1aYpOekDFNLwa85sZo2t19PEtvVTQCuaf3.webp', '2025-12-04 10:26:02', '2025-12-04 10:26:02'),
(27, 5, 'product_images/LQnWOLRq0YDFOy3CKMY3Y2IUsX79V1DfmAUtzJ56.webp', '2025-12-04 10:26:02', '2025-12-04 10:26:02'),
(28, 5, 'product_images/sl2oRbv3XzH6GuAMoPxNQXOB24lyPpFNwYY5xwcA.webp', '2025-12-04 10:26:02', '2025-12-04 10:26:02'),
(29, 5, 'product_images/2F8FJSp2veN4q9skY0ENB9kn8fV9Tak2FdHDB9XI.webp', '2025-12-04 10:26:02', '2025-12-04 10:26:02'),
(30, 5, 'product_images/VH7AQ4XjZucbKsZHObxrAcOSmmrdv1GP0I2GwP9B.webp', '2025-12-04 10:26:02', '2025-12-04 10:26:02');

-- --------------------------------------------------------

--
-- Table structure for table `product_variants`
--

CREATE TABLE `product_variants` (
  `id` bigint NOT NULL,
  `product_id` bigint NOT NULL,
  `price` decimal(12,0) NOT NULL,
  `price_sale` decimal(12,0) DEFAULT NULL,
  `stock` int NOT NULL DEFAULT '0',
  `sold` int NOT NULL DEFAULT '0',
  `sku` varchar(100) NOT NULL,
  `barcode` varchar(100) DEFAULT NULL,
  `storage_id` bigint DEFAULT NULL,
  `version_id` bigint DEFAULT NULL,
  `color_id` bigint DEFAULT NULL,
  `description` text,
  `image` varchar(256) DEFAULT NULL,
  `status` enum('available','out_of_stock','discontinued') DEFAULT 'available',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `product_variants`
--

INSERT INTO `product_variants` (`id`, `product_id`, `price`, `price_sale`, `stock`, `sold`, `sku`, `barcode`, `storage_id`, `version_id`, `color_id`, `description`, `image`, `status`, `created_at`, `updated_at`) VALUES
(1, 1, '27990000', '26990000', 20, 5, 'iPhone 15 Pro Max', '1111111111111', NULL, NULL, NULL, 'Titan tự nhiên 256GB', 'product_variants/hjngb8qozLYIdjOJEwVbdjWwHM9KSVGTc6YWhIZS.jpg', 'available', '2025-10-31 14:14:23', '2025-11-14 07:17:57'),
(2, 1, '27990000', NULL, 15, 2, 'IP15P-256-BLK', '1111111111112', NULL, NULL, NULL, 'Titan đen 256GB', NULL, 'available', '2025-10-31 14:14:23', '2025-10-31 14:14:23'),
(3, 2, '20990000', '19990000', 30, 10, 'S24-256-BLK', '2222222222221', NULL, NULL, NULL, 'Đen 256GB', NULL, 'available', '2025-10-31 14:14:23', '2025-10-31 14:14:23'),
(4, 2, '20990000', NULL, 25, 6, 'S24-256-VIO', '2222222222222', NULL, NULL, NULL, 'Tím 256GB', NULL, 'available', '2025-10-31 14:14:23', '2025-10-31 14:14:23'),
(5, 3, '5990000', NULL, 40, 20, 'RN13-128-BLU', '3333333333331', NULL, NULL, NULL, 'Xanh 128GB', NULL, 'available', '2025-10-31 14:14:23', '2025-12-16 02:32:53'),
(6, 4, '12990000', NULL, 18, 4, 'RENO12-256-SLV', '4444444444441', NULL, NULL, NULL, 'Bạc 256GB', NULL, 'available', '2025-10-31 14:14:23', '2025-10-31 14:14:23'),
(7, 5, '50990000', '44990000', 10, 1, 'ZFL7-12-SIL-512', '5555555555551', 4, NULL, 9, 'Samsung Galaxy Z Fold7 12GB', 'product_variants/rx8mqz5v8olJC3vt73iIAlp658qb3O4XZ8DePNTc.webp', 'available', '2025-10-31 14:14:23', '2025-12-04 10:39:55'),
(8, 6, '6190000', '5690000', 26, 49, 'AirPods Pro 2 (Lightning)', '6666666666661', NULL, 6, NULL, 'AirPods Pro 2 (Lightning)', 'product_variants/XqJOdABcgk4sN4cbQBX9OCDnJVWtPY20znVjn9sP.webp', 'available', '2025-10-31 14:14:23', '2025-12-11 10:31:06'),
(9, 6, '6190000', '5190000', 100, 26, 'AirPods Pro 2 (USB-C)', '132456789', NULL, 5, NULL, 'Đạt chuẩn IP54 (chống bụi và nước tốt hơn)\r\nHỗ trợ Âm thanh thích ứng (Adaptive Audio), Nhận biết cuộc hội thoại (Conversation Awareness) (yêu cầu iOS 17 trở lên)\r\nHỗ trợ âm thanh Lossless với độ trễ cực thấp khi kết nối với Apple Vision Pro', 'product_variants/5qeFUNz71ygSmEi0HTIIIhhXPxPaehIvqsjoM6gr.webp', 'available', '2025-11-13 01:58:15', '2025-12-17 03:29:26'),
(11, 9, '19990000', '16990000', 10, 8, 'IP15-128-H', '7777777777771', 2, 4, 4, 'Màu Hồng 128GB', 'product_variants/xkgyrNRJiXXgejQdPGIkc3L32TWBh432YbuNTHZX.webp', 'available', '2025-11-19 13:58:20', '2025-12-17 03:30:09'),
(12, 9, '19990000', '16990000', 10, 2, 'IP15-128-D', '7777777777772', 2, 4, 1, NULL, 'product_variants/at0JZh7RTFba3k3iDp0aHL9VZgCGeNXvuEA4DlIO.webp', 'available', '2025-11-19 13:58:20', '2025-11-21 05:46:26'),
(13, 9, '19990000', '16990000', 10, 2, 'IP15-128-X', '20112025', 2, 4, 3, NULL, 'product_variants/Ks3h4mqYQuw6781ZZG6HrH7WpFf1rtc07kywROjt.webp', 'available', '2025-11-21 05:47:54', '2025-11-21 05:47:54'),
(14, 9, '22990000', '20490000', 14, 3, 'IP15-256-H', '155622', 3, 4, 4, NULL, 'product_variants/FnJPAjaEGl3sqSag8QSFtyt76Asayht9jx0K83IC.webp', 'available', '2025-11-21 05:50:52', '2025-11-27 07:57:33'),
(15, 9, '19990000', '16990000', 75, 28, 'IP15-128-V', '01122025', 2, 4, 7, NULL, 'product_variants/lMd9nRUqikC3AEjdKruD27CVMBjP0jMJ1tTbLPte.webp', 'available', '2025-12-01 06:40:38', '2025-12-17 03:44:28'),
(16, 9, '19990000', '16990000', 9, 11, 'IP15-128-XL', '20112025', 2, 4, 6, NULL, 'product_variants/t8OpRNjoVTWEcXrB6GLY31EbWpekuwN9dOSvCadT.png', 'available', '2025-12-01 06:41:55', '2025-12-17 03:44:17'),
(17, 5, '46990000', '40990000', 7, 6, 'ZFL7-12-BLK-256', '0105122005', 3, NULL, 10, NULL, 'product_variants/dyWIfAy2TJhzn78uANao1hMB1PHPY7933h2GKWvS.webp', 'available', '2025-12-04 10:32:29', '2025-12-11 10:45:40'),
(18, 5, '50990000', '44990000', 10, 3, 'ZFL7-12-BLK-512', '0205122005', 4, NULL, 10, NULL, 'product_variants/aC0l74ta7y2gNeWUuWdQarCjSQz4o2wbPkoMp7q9.webp', 'available', '2025-12-04 10:37:34', '2025-12-04 10:37:34'),
(19, 5, '46990000', '40990000', 10, 2, 'ZFL7-12-SIL-256', '0305122005', 3, NULL, 9, NULL, 'product_variants/o02gA8UIkQgKS2RYUSd1bwtkCCnk0aEAaAoAMZtr.webp', 'available', '2025-12-04 10:38:50', '2025-12-04 10:39:28'),
(20, 5, '46990000', '40990000', 10, 3, 'ZFL7-12-BLS-256', '0405122005', 3, NULL, 11, NULL, 'product_variants/GGdpehVEId7Njr5e3cQeS0PKUlhbYWjZEopCOFER.webp', 'available', '2025-12-04 10:40:49', '2025-12-04 10:40:49'),
(21, 5, '50990000', '44990000', 10, 4, 'ZFL7-12-BLS-512', '0505122005', 4, NULL, 11, NULL, 'product_variants/HbhEBNMoJz34Cn23bIh2ul0AUtymXj1zUXd21ZuO.webp', 'available', '2025-12-04 10:41:34', '2025-12-04 10:41:34');

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

CREATE TABLE `roles` (
  `id` bigint NOT NULL,
  `name` varchar(50) NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `roles`
--

INSERT INTO `roles` (`id`, `name`, `created_at`, `updated_at`) VALUES
(1, 'admin', '2025-10-31 14:14:22', '2025-10-31 14:14:22'),
(2, 'customer', '2025-10-31 14:14:22', '2025-10-31 14:14:22');

-- --------------------------------------------------------

--
-- Table structure for table `role_user`
--

CREATE TABLE `role_user` (
  `id` bigint NOT NULL,
  `role_id` bigint DEFAULT NULL,
  `user_id` bigint DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `sessions`
--

CREATE TABLE `sessions` (
  `id` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `payload` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_activity` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sessions`
--

INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
('YtQltVXpEMpRLDKZX2wkO5VohY1J5Z0viMUbxNvN', 9, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiT1NIY1FOMnZyMUp0ZkVQMFpnV0pBS3ExclJuY1h3U2FhdVRJOXZWWCI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6Mjg6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9jbGllbnQiO3M6NToicm91dGUiO3M6MTI6ImNsaWVudC5pbmRleCI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fXM6NTA6ImxvZ2luX3dlYl81OWJhMzZhZGRjMmIyZjk0MDE1ODBmMDE0YzdmNThlYTRlMzA5ODlkIjtpOjk7fQ==', 1765970223);

-- --------------------------------------------------------

--
-- Table structure for table `shipping_tracking`
--

CREATE TABLE `shipping_tracking` (
  `id` bigint NOT NULL,
  `order_id` bigint NOT NULL,
  `status` enum('pending','shipped','in_transit','delivered','failed') DEFAULT 'pending',
  `tracking_number` varchar(100) DEFAULT NULL,
  `shipped_at` timestamp NULL DEFAULT NULL,
  `delivered_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `shipping_tracking`
--

INSERT INTO `shipping_tracking` (`id`, `order_id`, `status`, `tracking_number`, `shipped_at`, `delivered_at`, `created_at`, `updated_at`) VALUES
(1, 1, 'shipped', 'VNPOST-ABC123', '2025-10-31 14:14:23', NULL, '2025-10-31 14:14:23', '2025-10-31 14:14:23');

-- --------------------------------------------------------

--
-- Table structure for table `storages`
--

CREATE TABLE `storages` (
  `id` bigint NOT NULL,
  `storage` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `storages`
--

INSERT INTO `storages` (`id`, `storage`) VALUES
(1, '64GB'),
(2, '128GB'),
(3, '256GB'),
(4, '512GB');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `avatar` varchar(255) DEFAULT NULL,
  `phone` varchar(30) DEFAULT NULL,
  `password_hash` varchar(255) DEFAULT NULL,
  `role_id` tinyint DEFAULT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `status` enum('active','banned','inactive') DEFAULT 'active',
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `avatar`, `phone`, `password_hash`, `role_id`, `email_verified_at`, `status`, `deleted_at`, `created_at`, `updated_at`) VALUES
(1, 'Nguyen Van A', 'vana@example.com', NULL, NULL, 'hashed_password_123', 0, '2025-11-16 04:07:16', 'active', NULL, '2025-11-07 08:09:49', '2025-11-16 05:01:56'),
(2, 'Nguyen Van A', 'duyhiha4@gmail.com', NULL, NULL, '$2y$12$d9TwyFEP95B3H1T09ychfOpJ8odvdGHp69oTZehVja43XbBqAeUzi', 1, '2025-11-16 05:02:10', 'active', NULL, '2025-11-14 06:19:09', '2025-11-16 05:02:10'),
(3, 'Nguyen Van A', 'duyhiha5@gmail.com', NULL, NULL, '$2y$12$aLo3c/3F0wLwo8W6/ofhYulRroMQTJUO/D4u8vmj/KeerUwlsiBfK', 2, NULL, 'active', NULL, '2025-11-16 04:13:07', '2025-11-16 04:13:07'),
(4, 'Admin', 'admin@phonezy.local', NULL, '0900000000', '$2y$10$hash_admin', 1, NULL, 'active', NULL, '2025-10-31 14:14:22', '2025-10-31 14:14:22'),
(5, 'Nguyen Van A', 'a@example.com', NULL, '0911111111', '$2y$10$hash_user_a', 2, NULL, 'active', NULL, '2025-10-31 14:14:22', '2025-10-31 14:14:22'),
(6, 'Trư', 'quanm9677@gmail.com', NULL, NULL, '$2y$12$XRCrkLezethU7XshddSNAuCCQwGIF7k5Gg.pie0apE3Mt6LGU3k4W', 2, NULL, 'active', NULL, '2025-11-07 07:08:41', '2025-11-07 07:08:41'),
(8, 'tester', 'tester@gmail.com', NULL, '1234567891', '12345678', 1, '2025-11-21 11:58:36', 'active', NULL, NULL, NULL),
(9, 'Quân Trương M', 'tam@gmail.com', NULL, NULL, '$2y$12$VKxqZa8.JDuJyvfPScGre.ep.yTQNKIM7fTsH8yYaC7eLR3YadXrW', 1, NULL, 'active', NULL, '2025-11-21 06:59:48', '2025-11-21 06:59:48');

-- --------------------------------------------------------

--
-- Table structure for table `variant_images`
--

CREATE TABLE `variant_images` (
  `id` bigint NOT NULL,
  `variant_id` bigint NOT NULL,
  `image_url` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `variant_images`
--

INSERT INTO `variant_images` (`id`, `variant_id`, `image_url`, `created_at`) VALUES
(1, 11, 'https://example.com/ip15pm_black_variant1.jpg', '2025-11-19 13:58:20'),
(2, 12, 'https://example.com/ip15pm_silver_variant1.jpg', '2025-11-19 13:58:20');

-- --------------------------------------------------------

--
-- Table structure for table `versions`
--

CREATE TABLE `versions` (
  `id` bigint NOT NULL,
  `name` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `versions`
--

INSERT INTO `versions` (`id`, `name`) VALUES
(1, 'Standard'),
(2, 'Pro'),
(3, 'Pro Max'),
(4, 'Thường'),
(5, 'USB-C'),
(6, 'Lightning');

-- --------------------------------------------------------

--
-- Table structure for table `warehouses`
--

CREATE TABLE `warehouses` (
  `id` bigint NOT NULL,
  `name` varchar(120) NOT NULL,
  `location` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `warehouses`
--

INSERT INTO `warehouses` (`id`, `name`, `location`, `created_at`, `updated_at`) VALUES
(1, 'Kho Tổng HCM', 'Quận 7, TP.HCM', '2025-10-31 14:14:22', '2025-10-31 14:14:22'),
(2, 'Kho Hà Nội', 'Cầu Giấy, Hà Nội', '2025-10-31 14:14:22', '2025-10-31 14:14:22');

-- --------------------------------------------------------

--
-- Table structure for table `warehouse_stock`
--

CREATE TABLE `warehouse_stock` (
  `id` bigint NOT NULL,
  `warehouse_id` bigint NOT NULL,
  `product_variant_id` bigint NOT NULL,
  `stock_quantity` int NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `warehouse_stock`
--

INSERT INTO `warehouse_stock` (`id`, `warehouse_id`, `product_variant_id`, `stock_quantity`, `created_at`, `updated_at`) VALUES
(1, 1, 1, 10, '2025-10-31 14:14:23', '2025-10-31 14:14:23'),
(2, 1, 2, 8, '2025-10-31 14:14:23', '2025-10-31 14:14:23'),
(3, 1, 3, 15, '2025-10-31 14:14:23', '2025-10-31 14:14:23'),
(4, 1, 5, 20, '2025-10-31 14:14:23', '2025-10-31 14:14:23'),
(5, 1, 8, 25, '2025-10-31 14:14:23', '2025-10-31 14:14:23'),
(6, 2, 1, 10, '2025-10-31 14:14:23', '2025-10-31 14:14:23'),
(7, 2, 4, 12, '2025-10-31 14:14:23', '2025-10-31 14:14:23'),
(8, 2, 6, 9, '2025-10-31 14:14:23', '2025-10-31 14:14:23'),
(9, 2, 7, 6, '2025-10-31 14:14:23', '2025-10-31 14:14:23'),
(10, 2, 8, 25, '2025-10-31 14:14:23', '2025-10-31 14:14:23');

-- --------------------------------------------------------

--
-- Table structure for table `wishlists`
--

CREATE TABLE `wishlists` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint NOT NULL,
  `product_id` bigint NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `wishlists`
--

INSERT INTO `wishlists` (`id`, `user_id`, `product_id`, `created_at`, `updated_at`) VALUES
(5, 9, 6, '2025-12-15 04:01:26', '2025-12-15 04:01:26'),
(7, 9, 9, '2025-12-15 04:14:18', '2025-12-15 04:14:18'),
(8, 9, 5, '2025-12-16 02:33:09', '2025-12-16 02:33:09');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `bad_words`
--
ALTER TABLE `bad_words`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `word` (`word`);

--
-- Indexes for table `brands`
--
ALTER TABLE `brands`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `slug` (`slug`);

--
-- Indexes for table `carts`
--
ALTER TABLE `carts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_carts_user` (`user_id`);

--
-- Indexes for table `cart_items`
--
ALTER TABLE `cart_items`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uq_cart_variant` (`cart_id`,`product_variant_id`),
  ADD KEY `fk_citems_variant` (`product_variant_id`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `slug` (`slug`);

--
-- Indexes for table `colors`
--
ALTER TABLE `colors`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `comments`
--
ALTER TABLE `comments`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `coupons`
--
ALTER TABLE `coupons`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `code` (`code`);

--
-- Indexes for table `coupon_user`
--
ALTER TABLE `coupon_user`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `inventory_logs`
--
ALTER TABLE `inventory_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_il_wh` (`warehouse_id`),
  ADD KEY `fk_il_variant` (`product_variant_id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD KEY `orders_coupon_id_foreign` (`coupon_id`);

--
-- Indexes for table `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_oitems_order` (`order_id`);

--
-- Indexes for table `order_returns`
--
ALTER TABLE `order_returns`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_returns_item` (`order_item_id`);

--
-- Indexes for table `payments`
--
ALTER TABLE `payments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_pay_order` (`order_id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `slug` (`slug`),
  ADD KEY `fk_products_category` (`category_id`),
  ADD KEY `fk_products_brand` (`brand_id`),
  ADD KEY `idx_products_name` (`name`),
  ADD KEY `idx_products_slug` (`slug`);

--
-- Indexes for table `product_images`
--
ALTER TABLE `product_images`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_images_product` (`product_id`);

--
-- Indexes for table `product_variants`
--
ALTER TABLE `product_variants`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `sku` (`sku`),
  ADD KEY `fk_variants_product` (`product_id`),
  ADD KEY `idx_variants_sku` (`sku`),
  ADD KEY `storage_id` (`storage_id`),
  ADD KEY `version_id` (`version_id`),
  ADD KEY `color_id` (`color_id`);

--
-- Indexes for table `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Indexes for table `shipping_tracking`
--
ALTER TABLE `shipping_tracking`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_ship_order` (`order_id`);

--
-- Indexes for table `storages`
--
ALTER TABLE `storages`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `variant_images`
--
ALTER TABLE `variant_images`
  ADD PRIMARY KEY (`id`),
  ADD KEY `variant_id` (`variant_id`);

--
-- Indexes for table `versions`
--
ALTER TABLE `versions`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `warehouses`
--
ALTER TABLE `warehouses`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `warehouse_stock`
--
ALTER TABLE `warehouse_stock`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uq_wh_variant` (`warehouse_id`,`product_variant_id`),
  ADD KEY `fk_wstock_variant` (`product_variant_id`);

--
-- Indexes for table `wishlists`
--
ALTER TABLE `wishlists`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `wishlists_user_id_product_id_unique` (`user_id`,`product_id`),
  ADD KEY `wishlists_product_id_foreign` (`product_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `bad_words`
--
ALTER TABLE `bad_words`
  MODIFY `id` bigint NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=338;

--
-- AUTO_INCREMENT for table `brands`
--
ALTER TABLE `brands`
  MODIFY `id` bigint NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `carts`
--
ALTER TABLE `carts`
  MODIFY `id` bigint NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=50;

--
-- AUTO_INCREMENT for table `cart_items`
--
ALTER TABLE `cart_items`
  MODIFY `id` bigint NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=70;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` bigint NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `colors`
--
ALTER TABLE `colors`
  MODIFY `id` bigint NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `comments`
--
ALTER TABLE `comments`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `coupons`
--
ALTER TABLE `coupons`
  MODIFY `id` bigint NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `coupon_user`
--
ALTER TABLE `coupon_user`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `inventory_logs`
--
ALTER TABLE `inventory_logs`
  MODIFY `id` bigint NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=44;

--
-- AUTO_INCREMENT for table `order_items`
--
ALTER TABLE `order_items`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=45;

--
-- AUTO_INCREMENT for table `order_returns`
--
ALTER TABLE `order_returns`
  MODIFY `id` bigint NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `payments`
--
ALTER TABLE `payments`
  MODIFY `id` bigint NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` bigint NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `product_images`
--
ALTER TABLE `product_images`
  MODIFY `id` bigint NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT for table `product_variants`
--
ALTER TABLE `product_variants`
  MODIFY `id` bigint NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `roles`
--
ALTER TABLE `roles`
  MODIFY `id` bigint NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `shipping_tracking`
--
ALTER TABLE `shipping_tracking`
  MODIFY `id` bigint NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `storages`
--
ALTER TABLE `storages`
  MODIFY `id` bigint NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `variant_images`
--
ALTER TABLE `variant_images`
  MODIFY `id` bigint NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `versions`
--
ALTER TABLE `versions`
  MODIFY `id` bigint NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `warehouses`
--
ALTER TABLE `warehouses`
  MODIFY `id` bigint NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `warehouse_stock`
--
ALTER TABLE `warehouse_stock`
  MODIFY `id` bigint NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `wishlists`
--
ALTER TABLE `wishlists`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `carts`
--
ALTER TABLE `carts`
  ADD CONSTRAINT `fk_carts_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `cart_items`
--
ALTER TABLE `cart_items`
  ADD CONSTRAINT `fk_citems_cart` FOREIGN KEY (`cart_id`) REFERENCES `carts` (`id`),
  ADD CONSTRAINT `fk_citems_variant` FOREIGN KEY (`product_variant_id`) REFERENCES `product_variants` (`id`);

--
-- Constraints for table `inventory_logs`
--
ALTER TABLE `inventory_logs`
  ADD CONSTRAINT `fk_il_variant` FOREIGN KEY (`product_variant_id`) REFERENCES `product_variants` (`id`),
  ADD CONSTRAINT `fk_il_wh` FOREIGN KEY (`warehouse_id`) REFERENCES `warehouses` (`id`);

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_coupon_id_foreign` FOREIGN KEY (`coupon_id`) REFERENCES `coupons` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `products`
--
ALTER TABLE `products`
  ADD CONSTRAINT `fk_products_brand` FOREIGN KEY (`brand_id`) REFERENCES `brands` (`id`),
  ADD CONSTRAINT `fk_products_category` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`);

--
-- Constraints for table `product_images`
--
ALTER TABLE `product_images`
  ADD CONSTRAINT `fk_images_product` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`);

--
-- Constraints for table `product_variants`
--
ALTER TABLE `product_variants`
  ADD CONSTRAINT `fk_variants_product` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`),
  ADD CONSTRAINT `product_variants_ibfk_1` FOREIGN KEY (`storage_id`) REFERENCES `storages` (`id`),
  ADD CONSTRAINT `product_variants_ibfk_2` FOREIGN KEY (`version_id`) REFERENCES `versions` (`id`),
  ADD CONSTRAINT `product_variants_ibfk_3` FOREIGN KEY (`color_id`) REFERENCES `colors` (`id`);

--
-- Constraints for table `variant_images`
--
ALTER TABLE `variant_images`
  ADD CONSTRAINT `variant_images_ibfk_1` FOREIGN KEY (`variant_id`) REFERENCES `product_variants` (`id`);

--
-- Constraints for table `warehouse_stock`
--
ALTER TABLE `warehouse_stock`
  ADD CONSTRAINT `fk_wstock_variant` FOREIGN KEY (`product_variant_id`) REFERENCES `product_variants` (`id`),
  ADD CONSTRAINT `fk_wstock_wh` FOREIGN KEY (`warehouse_id`) REFERENCES `warehouses` (`id`);

--
-- Constraints for table `wishlists`
--
ALTER TABLE `wishlists`
  ADD CONSTRAINT `wishlists_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `wishlists_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
