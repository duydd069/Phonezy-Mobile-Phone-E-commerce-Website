-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Dec 03, 2025 at 10:31 AM
-- Server version: 8.4.3
-- PHP Version: 8.3.16

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
(2, 21, 'active', '2025-11-21 03:35:40', '2025-11-21 03:35:40'),
(3, 22, 'active', '2025-11-21 07:04:42', '2025-11-21 07:04:42');

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
(5, 3, 9, 1, '2025-11-21 07:04:42', '2025-11-21 07:04:42');

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
(3, 'Xanh', '#0000FF'),
(4, 'Bạc', '#C0C0C0'),
(5, 'Đỏ', '#FF0000');

-- --------------------------------------------------------

--
-- Table structure for table `comments`
--

CREATE TABLE `comments` (
  `id` bigint NOT NULL,
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
(1, 2, 4, NULL, 'hihi', '2025-11-14 06:40:23', '2025-11-14 06:40:23'),
(15, 2, 5, 1, 'Hi?', '2025-11-14 07:29:24', '2025-11-14 07:29:24'),
(16, 2, 4, NULL, 'hello', '2025-11-14 07:37:43', '2025-11-14 07:37:43'),
(17, 2, 4, 16, 'Hello', '2025-11-14 07:37:51', '2025-11-14 07:37:51'),
(19, 8, 4, NULL, 'hi', '2025-11-20 21:25:39', '2025-11-20 21:25:39'),
(20, 8, 4, 19, 'alo', '2025-11-20 21:25:49', '2025-11-20 21:25:49'),
(21, 5, 4, NULL, 'alo', '2025-11-20 22:00:20', '2025-11-20 22:00:20'),
(22, 9, 4, NULL, 'hi', '2025-11-20 22:48:02', '2025-11-20 22:48:02'),
(23, 9, 4, NULL, 'ô', '2025-11-20 22:48:36', '2025-11-20 22:48:36'),
(25, 9, 5, NULL, 'ô', '2025-11-20 22:58:43', '2025-11-20 22:58:43'),
(27, 9, 5, 25, 'ok', '2025-11-20 23:00:04', '2025-11-20 23:00:04'),
(28, 9, 5, 25, 'ok', '2025-11-20 23:00:15', '2025-11-20 23:00:15'),
(29, 9, 5, 25, 'ok', '2025-11-20 23:00:15', '2025-11-20 23:00:15'),
(30, 9, 5, 23, 'hi', '2025-11-20 23:01:42', '2025-11-20 23:01:42'),
(32, 9, 5, NULL, 'ok', '2025-11-20 23:19:43', '2025-11-20 23:19:43'),
(34, 9, 5, 32, 'bio', '2025-11-20 23:26:54', '2025-11-20 23:26:54'),
(36, 8, 5, 19, 'ưol', '2025-11-20 23:29:22', '2025-11-20 23:29:22'),
(38, 8, 5, 19, 'hi ngài', '2025-11-20 23:33:10', '2025-11-20 23:33:10'),
(39, 9, 5, NULL, 'hi', '2025-11-20 23:38:34', '2025-11-21 00:36:05'),
(41, 9, 5, 39, 'ok', '2025-11-20 23:56:42', '2025-11-21 00:34:25'),
(44, 7, 5, NULL, 'đẹp', '2025-11-21 00:34:13', '2025-11-21 00:34:13'),
(45, 9, 21, 39, 'hi b', '2025-11-21 07:21:44', '2025-11-21 07:21:44'),
(46, 9, 21, NULL, 'May dep qua', '2025-11-21 07:22:44', '2025-11-21 07:22:44'),
(47, 7, 22, NULL, 'd', '2025-11-21 07:36:13', '2025-11-21 07:36:13'),
(49, 7, 22, 47, 'he', '2025-11-21 07:37:33', '2025-11-21 07:37:33'),
(50, 7, 22, 47, 'ôi', '2025-11-21 07:37:37', '2025-11-21 07:37:37');

-- --------------------------------------------------------

--
-- Table structure for table `coupons`
--

CREATE TABLE `coupons` (
  `id` bigint NOT NULL,
  `code` varchar(50) NOT NULL,
  `discount_type` enum('percent','fixed') NOT NULL,
  `discount_value` decimal(10,2) NOT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `coupons`
--

INSERT INTO `coupons` (`id`, `code`, `discount_type`, `discount_value`, `expires_at`, `created_at`, `updated_at`) VALUES
(1, 'NEW10', 'percent', 10.00, '2025-11-30 14:14:23', '2025-10-31 14:14:23', '2025-10-31 14:14:23'),
(2, 'SALE100K', 'fixed', 100000.00, '2025-11-15 14:14:23', '2025-10-31 14:14:23', '2025-10-31 14:14:23');

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
(1, '2025_11_21_071900_add_deleted_at_to_comments_table', 1),
(2, '2025_11_21_074600_drop_deleted_at_from_comments_table', 2),
(3, '2025_11_21_075400_add_banned_at_to_users_table', 3),
(4, '2025_11_21_080100_change_banned_at_to_is_banned', 4),
(5, '2025_11_21_083800_add_verification_columns_to_users_table', 5);

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` bigint NOT NULL,
  `user_id` bigint NOT NULL,
  `coupon_id` bigint DEFAULT NULL,
  `total_price` decimal(12,2) NOT NULL,
  `status` enum('pending','processing','completed','cancelled') DEFAULT 'pending',
  `notes` text,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `user_id`, `coupon_id`, `total_price`, `status`, `notes`, `created_at`, `updated_at`) VALUES
(1, 2, 1, 14373000.00, 'processing', 'Giao trong giờ hành chính', '2025-10-31 14:14:23', '2025-10-31 14:14:23');

-- --------------------------------------------------------

--
-- Table structure for table `order_items`
--

CREATE TABLE `order_items` (
  `id` bigint NOT NULL,
  `order_id` bigint NOT NULL,
  `product_variant_id` bigint NOT NULL,
  `quantity` int NOT NULL,
  `price_each` decimal(12,2) NOT NULL,
  `variant_sku` varchar(100) DEFAULT NULL,
  `variant_volume_ml` int DEFAULT NULL,
  `variant_description` text,
  `variant_status` enum('available','out_of_stock','discontinued') DEFAULT NULL,
  `variant_name` varchar(200) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `order_items`
--

INSERT INTO `order_items` (`id`, `order_id`, `product_variant_id`, `quantity`, `price_each`, `variant_sku`, `variant_volume_ml`, `variant_description`, `variant_status`, `variant_name`, `created_at`, `updated_at`) VALUES
(1, 1, 5, 2, 5490000.00, 'RN13-128-BLU', NULL, 'Xanh 128GB', NULL, NULL, '2025-10-31 14:14:23', '2025-10-31 14:14:23'),
(2, 1, 8, 1, 4990000.00, 'APPRO2-USBC', NULL, 'AirPods Pro 2 USB-C', NULL, NULL, '2025-10-31 14:14:23', '2025-10-31 14:14:23');

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
(1, 'iPhone 15 Pro 256GB', 'products/75B73Bgvi6D8y6j2WtvMFjr6aD4GZ1ZXVKJa5FBl.webp', 27990000, 'iphone-15-pro-256gb', 'Flagship Apple', 0, 1, 1, 131, '2025-10-31 14:14:23', '2025-11-19 07:00:16'),
(2, 'Samsung Galaxy S24 256GB', 'https://example.com/s24.jpg', 20990000, 'samsung-galaxy-s24-256gb', 'Flagship Samsung', 0, 1, 2, 90, '2025-10-31 14:14:23', '2025-10-31 14:14:23'),
(3, 'Xiaomi Redmi Note 13', 'https://example.com/rn13.jpg', 5990000, 'xiaomi-redmi-note-13', 'Giá tốt', 0, 1, 3, 81, '2025-10-31 14:14:23', '2025-11-11 07:21:08'),
(4, 'OPPO Reno 12', 'products/itw42M7gY1n0eHYbIKIOaK1LmKIRYtp9ILG5I6qa.jpg', 12990000, 'oppo-reno-12', 'Thiết kế đẹp', 0, 1, 4, 66, '2025-10-31 14:14:23', '2025-11-21 00:31:12'),
(5, 'iPad Air M2 11\"', 'products/vAXyJQK8YrOYHzHQj69L23JjnKAEZo8Kc4nUdZ2W.png', 16990000, 'ipad-air-m2-11', 'iPad Air chip M2', 0, 2, 1, 43, '2025-10-31 14:14:23', '2025-11-20 23:20:53'),
(6, 'AirPods Pro 2', 'products/hGDWOedX6woyqsJ9NU5OizXxyvykFtVxpvT7YHbt.png', 5290000, 'airpods-pro-2', 'Tai nghe chống ồn', 0, 4, 1, 174, '2025-10-31 14:14:23', '2025-11-19 06:52:04'),
(7, 'test', 'products/6iiIfcfutY2sCFMXgCiXDz6q4OFKFR4c0m2dq21m.jpg', 1350000, 'test', NULL, 0, 1, 1, 26, '2025-11-14 07:32:17', '2025-11-21 07:37:38'),
(8, 'Samsung Galaxy Tab S9', 'https://example.com/tab_s9.jpg', 15990000, 'samsung-galaxy-tab-s9', 'Máy tính bảng cao cấp Samsung', 0, 2, 2, 39, '2025-11-19 13:58:20', '2025-11-21 00:31:04'),
(9, 'iPhone 15 Pro Max', 'products/eAYnENGzkwkN6cDxfPc8HUkpcJAAuhQfKESbKIV8.jpg', 0, 'iphone-15-pro-max', 'Flagship Apple với nhiều tùy chọn màu và bộ nhớ', 1, 1, 1, 47, '2025-11-19 13:58:20', '2025-11-21 07:35:50'),
(12, 'iPhone 15 Pro Max1', 'products/Bvora8ZKDNQvMloHMxCwd5uYD9jmYMZTzOUG1ZhL.jpg', 99999, 'iphone-15-pro-max1', NULL, 0, 1, 4, 5, '2025-11-21 03:17:01', '2025-11-21 07:05:13');

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
(9, 9, 'https://example.com/ip15pm_2.jpg', '2025-11-19 13:58:20', '2025-11-19 13:58:20'),
(10, 9, 'product_images/YiWHwk4oPmLm9sZOk7XPJ0TLMnssGhvpHvUyDL0k.jpg', '2025-11-20 21:27:24', '2025-11-20 21:27:24');

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
(1, 1, 27990000, 26990000, 20, 5, 'iPhone 15 Pro Max', '1111111111111', NULL, NULL, NULL, 'Titan tự nhiên 256GB', 'product_variants/hjngb8qozLYIdjOJEwVbdjWwHM9KSVGTc6YWhIZS.jpg', 'available', '2025-10-31 14:14:23', '2025-11-14 07:17:57'),
(2, 1, 27990000, NULL, 15, 2, 'IP15P-256-BLK', '1111111111112', NULL, NULL, NULL, 'Titan đen 256GB', NULL, 'available', '2025-10-31 14:14:23', '2025-10-31 14:14:23'),
(3, 2, 20990000, 19990000, 30, 10, 'S24-256-BLK', '2222222222221', NULL, NULL, NULL, 'Đen 256GB', NULL, 'available', '2025-10-31 14:14:23', '2025-10-31 14:14:23'),
(4, 2, 20990000, NULL, 25, 6, 'S24-256-VIO', '2222222222222', NULL, NULL, NULL, 'Tím 256GB', NULL, 'available', '2025-10-31 14:14:23', '2025-10-31 14:14:23'),
(5, 3, 5990000, 5490000, 40, 20, 'RN13-128-BLU', '3333333333331', NULL, NULL, NULL, 'Xanh 128GB', NULL, 'available', '2025-10-31 14:14:23', '2025-10-31 14:14:23'),
(6, 4, 12990000, NULL, 18, 4, 'RENO12-256-SLV', '4444444444441', NULL, NULL, NULL, 'Bạc 256GB', NULL, 'available', '2025-10-31 14:14:23', '2025-10-31 14:14:23'),
(7, 5, 16990000, NULL, 10, 1, 'IPAD-AIR-M2-11-128', '5555555555551', NULL, NULL, NULL, 'M2 11-inch 128GB', NULL, 'available', '2025-10-31 14:14:23', '2025-10-31 14:14:23'),
(8, 6, 5290000, 4990000, 50, 35, 'AirPods Pro 2 (Lightning)', '6666666666661', NULL, NULL, NULL, 'AirPods Pro 2 (Lightning)', 'product_variants/fVZ3gdad519L3TM7GXWXjwmnh16DFAKmQMoFmUeD.png', 'available', '2025-10-31 14:14:23', '2025-11-13 03:43:33'),
(9, 6, 1350000, NULL, 111, 1, 'AirPods Pro 2 (USB-C)', '132456789', NULL, NULL, NULL, 'Đạt chuẩn IP54 (chống bụi và nước tốt hơn)\r\nHỗ trợ Âm thanh thích ứng (Adaptive Audio), Nhận biết cuộc hội thoại (Conversation Awareness) (yêu cầu iOS 17 trở lên)\r\nHỗ trợ âm thanh Lossless với độ trễ cực thấp khi kết nối với Apple Vision Pro', NULL, 'available', '2025-11-13 01:58:15', '2025-11-13 03:17:47'),
(10, 7, 1000000, NULL, 2, 1, 'test biến thể sp', '111111reshgf', NULL, NULL, NULL, NULL, 'product_variants/2ode8xgWNw0P5iVQHIJEVQjvblXIOUHQapHL7o33.png', 'available', '2025-11-14 07:35:05', '2025-11-14 07:35:05'),
(11, 9, 30990000, 29990000, 15, 0, 'IP15PM-256-BLK', '7777777777771', NULL, NULL, NULL, 'Màu đen 256GB', 'https://example.com/ip15pm_black.jpg', 'available', '2025-11-19 13:58:20', '2025-11-19 13:58:20'),
(12, 9, 31990000, NULL, 10, 0, 'IP15PM-512-SLV', '7777777777772', NULL, NULL, NULL, 'Màu bạc 512GB', 'https://example.com/ip15pm_silver.jpg', 'available', '2025-11-19 13:58:20', '2025-11-19 13:58:20'),
(13, 12, 199000, 100000, 11, 2, 'dsa', NULL, 2, 3, 1, NULL, NULL, 'available', '2025-11-21 07:05:57', '2025-11-21 07:05:57');

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
  `name` varchar(100) NOT NULL,
  `email` varchar(150) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `verification_token` varchar(100) DEFAULT NULL,
  `verification_expires_at` timestamp NULL DEFAULT NULL,
  `is_banned` tinyint NOT NULL DEFAULT '0',
  `phone` varchar(30) DEFAULT NULL,
  `role_id` bigint NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password_hash`, `verification_token`, `verification_expires_at`, `is_banned`, `phone`, `role_id`, `created_at`, `updated_at`) VALUES
(2, 'Nguyen Van A', 'a@example.com', '$2y$10$hash_user_a', NULL, NULL, 0, '0911111111', 2, '2025-10-31 14:14:22', '2025-10-31 14:14:22'),
(3, 'Trư', 'quanm9677@gmail.com', '$2y$12$XRCrkLezethU7XshddSNAuCCQwGIF7k5Gg.pie0apE3Mt6LGU3k4W', NULL, NULL, 0, NULL, 2, '2025-11-07 07:08:41', '2025-11-07 07:08:41'),
(21, 'Đặng Duy', 'duyhiha4@gmail.com', '$2y$12$/7ca236qXbtiQK7ExN7rA.gL5w2Xl6EgJV3cNqK8qiqvc2x6AQovG', NULL, NULL, 0, NULL, 1, '2025-11-21 03:23:53', '2025-11-21 10:24:28'),
(22, 'Đặng Duy', 'duyhiha5@gmail.com', '$2y$12$wqnAeNJIiqPhqcsXScINFe7Y3a8RpqIF.DNwRulZRjwt2DG5Qvdye', NULL, NULL, 0, NULL, 2, '2025-11-21 03:24:54', '2025-11-21 03:25:01'),
(24, 'Nguyen Van A', 'duyhiha6@gmail.com', '$2y$12$vHYX7HS8G65YTaZScQgYhuRRJmtzP21/1mMKrZoo4DQukoo9viAMC', NULL, NULL, 0, NULL, 2, '2025-11-21 07:34:19', '2025-11-21 07:34:30');

-- --------------------------------------------------------

--
-- Table structure for table `user_images`
--

CREATE TABLE `user_images` (
  `id` bigint NOT NULL,
  `user_id` bigint NOT NULL,
  `image_url` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
(3, 'Pro Max');

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
  ADD PRIMARY KEY (`id`),
  ADD KEY `parent_id` (`parent_id`),
  ADD KEY `idx_product_id` (`product_id`),
  ADD KEY `idx_user_id` (`user_id`);

--
-- Indexes for table `coupons`
--
ALTER TABLE `coupons`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `code` (`code`);

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
  ADD KEY `fk_orders_user` (`user_id`),
  ADD KEY `fk_orders_coupon` (`coupon_id`),
  ADD KEY `idx_orders_status` (`status`);

--
-- Indexes for table `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_oitems_order` (`order_id`),
  ADD KEY `fk_oitems_variant` (`product_variant_id`);

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
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `users_verification_token_unique` (`verification_token`),
  ADD KEY `fk_users_role` (`role_id`);

--
-- Indexes for table `user_images`
--
ALTER TABLE `user_images`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uq_user_image` (`user_id`);

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
  MODIFY `id` bigint NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `cart_items`
--
ALTER TABLE `cart_items`
  MODIFY `id` bigint NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` bigint NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `colors`
--
ALTER TABLE `colors`
  MODIFY `id` bigint NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `comments`
--
ALTER TABLE `comments`
  MODIFY `id` bigint NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=51;

--
-- AUTO_INCREMENT for table `coupons`
--
ALTER TABLE `coupons`
  MODIFY `id` bigint NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `inventory_logs`
--
ALTER TABLE `inventory_logs`
  MODIFY `id` bigint NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` bigint NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `order_items`
--
ALTER TABLE `order_items`
  MODIFY `id` bigint NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

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
  MODIFY `id` bigint NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `product_variants`
--
ALTER TABLE `product_variants`
  MODIFY `id` bigint NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

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
  MODIFY `id` bigint NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT for table `user_images`
--
ALTER TABLE `user_images`
  MODIFY `id` bigint NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `variant_images`
--
ALTER TABLE `variant_images`
  MODIFY `id` bigint NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `versions`
--
ALTER TABLE `versions`
  MODIFY `id` bigint NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

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
  ADD CONSTRAINT `fk_orders_coupon` FOREIGN KEY (`coupon_id`) REFERENCES `coupons` (`id`),
  ADD CONSTRAINT `fk_orders_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `order_items`
--
ALTER TABLE `order_items`
  ADD CONSTRAINT `fk_oitems_order` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`),
  ADD CONSTRAINT `fk_oitems_variant` FOREIGN KEY (`product_variant_id`) REFERENCES `product_variants` (`id`);

--
-- Constraints for table `order_returns`
--
ALTER TABLE `order_returns`
  ADD CONSTRAINT `fk_returns_item` FOREIGN KEY (`order_item_id`) REFERENCES `order_items` (`id`);

--
-- Constraints for table `payments`
--
ALTER TABLE `payments`
  ADD CONSTRAINT `fk_pay_order` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`);

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
-- Constraints for table `shipping_tracking`
--
ALTER TABLE `shipping_tracking`
  ADD CONSTRAINT `fk_ship_order` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`);

--
-- Constraints for table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `fk_users_role` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`);

--
-- Constraints for table `user_images`
--
ALTER TABLE `user_images`
  ADD CONSTRAINT `fk_user_images_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

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
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
