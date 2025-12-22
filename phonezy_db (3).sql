-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Dec 22, 2025 at 04:02 PM
-- Server version: 8.0.30
-- PHP Version: 8.3.6

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
-- Table structure for table `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cache_locks`
--

CREATE TABLE `cache_locks` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `owner` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
(1, 2, 'converted', '2025-10-31 14:14:23', '2025-12-17 07:01:55'),
(2, 1, 'converted', '2025-11-21 03:09:40', '2025-12-04 05:45:18'),
(9, 9, 'converted', '2025-11-27 06:49:20', '2025-12-04 10:51:15'),
(10, 1, 'converted', '2025-12-04 05:52:11', '2025-12-04 05:52:34'),
(11, 1, 'converted', '2025-12-04 06:02:34', '2025-12-18 04:07:48'),
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
(49, 9, 'active', '2025-12-17 04:02:03', '2025-12-17 04:02:03'),
(50, 2, 'converted', '2025-12-17 07:02:33', '2025-12-17 07:04:22'),
(51, 10, 'converted', '2025-12-17 07:32:14', '2025-12-17 07:33:11'),
(52, 10, 'converted', '2025-12-17 07:40:04', '2025-12-17 07:40:29'),
(53, 10, 'converted', '2025-12-17 07:41:21', '2025-12-17 07:41:31'),
(54, 10, 'converted', '2025-12-17 07:47:18', '2025-12-17 07:47:42'),
(55, 10, 'converted', '2025-12-17 07:53:27', '2025-12-17 07:55:08'),
(56, 10, 'converted', '2025-12-17 07:55:30', '2025-12-17 07:55:45'),
(57, 10, 'converted', '2025-12-17 08:15:49', '2025-12-17 08:16:01'),
(58, 10, 'converted', '2025-12-17 08:33:51', '2025-12-17 08:34:00'),
(59, 10, 'converted', '2025-12-17 08:36:28', '2025-12-17 08:36:48'),
(60, 10, 'converted', '2025-12-17 08:52:54', '2025-12-17 08:53:16'),
(61, 10, 'converted', '2025-12-17 08:59:14', '2025-12-17 09:07:55'),
(62, 10, 'converted', '2025-12-17 09:23:24', '2025-12-17 10:33:05'),
(63, 10, 'converted', '2025-12-17 20:03:47', '2025-12-17 20:03:59'),
(64, 10, 'converted', '2025-12-17 20:06:20', '2025-12-17 20:06:31'),
(65, 10, 'converted', '2025-12-17 20:09:31', '2025-12-17 20:09:41'),
(66, 10, 'converted', '2025-12-17 20:10:13', '2025-12-17 20:10:22'),
(67, 10, 'converted', '2025-12-17 20:11:12', '2025-12-17 20:11:20'),
(68, 10, 'converted', '2025-12-17 20:13:34', '2025-12-17 20:14:05'),
(69, 10, 'converted', '2025-12-17 20:18:54', '2025-12-17 20:19:08'),
(70, 11, 'active', '2025-12-18 00:12:37', '2025-12-18 00:12:37'),
(71, 1, 'converted', '2025-12-18 04:08:13', '2025-12-18 04:08:37'),
(72, 1, 'converted', '2025-12-18 07:17:57', '2025-12-18 07:18:55'),
(73, 10, 'converted', '2025-12-18 07:20:18', '2025-12-18 07:20:55'),
(74, 1, 'converted', '2025-12-18 23:42:36', '2025-12-18 23:43:24'),
(75, 10, 'converted', '2025-12-19 01:33:01', '2025-12-19 01:35:45'),
(76, 10, 'converted', '2025-12-19 01:38:52', '2025-12-19 10:10:51'),
(77, 10, 'converted', '2025-12-19 10:14:09', '2025-12-19 10:15:04'),
(78, 13, 'converted', '2025-12-20 13:32:41', '2025-12-20 13:33:22'),
(79, 13, 'active', '2025-12-20 13:34:07', '2025-12-20 13:34:07'),
(80, 14, 'converted', '2025-12-22 00:37:49', '2025-12-22 00:38:48'),
(81, 14, 'converted', '2025-12-22 00:39:33', '2025-12-22 04:29:50'),
(82, 14, 'converted', '2025-12-22 04:48:43', '2025-12-22 04:49:06');

-- --------------------------------------------------------

--
-- Table structure for table `cart_items`
--

CREATE TABLE `cart_items` (
  `id` bigint NOT NULL,
  `cart_id` bigint NOT NULL,
  `product_variant_id` bigint NOT NULL,
  `quantity` int NOT NULL DEFAULT '1',
  `price_at_time` decimal(12,2) DEFAULT NULL COMMENT 'Giá bán tại thời điểm thêm vào giỏ',
  `price_sale_at_time` decimal(12,2) DEFAULT NULL COMMENT 'Giá khuyến mãi tại thời điểm thêm vào giỏ',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `cart_items`
--

INSERT INTO `cart_items` (`id`, `cart_id`, `product_variant_id`, `quantity`, `price_at_time`, `price_sale_at_time`, `created_at`, `updated_at`) VALUES
(69, 49, 11, 10, NULL, NULL, '2025-12-17 04:02:03', '2025-12-17 04:02:03'),
(90, 70, 11, 1, NULL, NULL, '2025-12-18 00:12:37', '2025-12-18 00:12:37');

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
(4, 'Tai nghe', 'tai-nghe', '2025-10-31 14:14:22', '2025-12-19 00:44:06'),
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
  `replied_to_user_id` bigint DEFAULT NULL,
  `content` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Nội dung bình luận',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Bảng lưu trữ bình luận sản phẩm';

--
-- Dumping data for table `comments`
--

INSERT INTO `comments` (`id`, `product_id`, `user_id`, `parent_id`, `replied_to_user_id`, `content`, `created_at`, `updated_at`) VALUES
(1, 2, 4, NULL, NULL, 'hihi', '2025-11-13 23:40:23', '2025-11-13 23:40:23'),
(15, 2, 5, 1, NULL, 'Hi?', '2025-11-14 00:29:24', '2025-11-14 00:29:24'),
(16, 2, 4, NULL, NULL, 'hello', '2025-11-14 00:37:43', '2025-11-14 00:37:43'),
(17, 2, 4, 16, NULL, 'Hello', '2025-11-14 00:37:51', '2025-11-14 00:37:51'),
(18, 2, 4, NULL, NULL, 'Ôi', '2025-11-14 01:15:19', '2025-11-14 01:15:19'),
(20, 2, 10, 17, NULL, '9', '2025-12-17 09:56:12', '2025-12-17 09:56:12'),
(23, 2, 10, NULL, NULL, 'hi', '2025-12-17 10:21:31', '2025-12-17 10:21:31'),
(34, 9, 13, NULL, NULL, 'sản phẩm được của nó đấy', '2025-12-22 00:49:20', '2025-12-22 00:49:20'),
(40, 9, 13, 34, 13, 'heheheh', '2025-12-22 06:07:58', '2025-12-22 06:07:58');

-- --------------------------------------------------------

--
-- Table structure for table `coupons`
--

CREATE TABLE `coupons` (
  `id` bigint NOT NULL,
  `code` varchar(50) NOT NULL,
  `type` enum('public','private') NOT NULL DEFAULT 'public',
  `promotion_type` enum('order','product') NOT NULL DEFAULT 'order',
  `discount_type` enum('percent','fixed') NOT NULL,
  `discount_value` decimal(10,2) NOT NULL,
  `min_order_value` decimal(12,2) DEFAULT NULL COMMENT 'Đơn hàng tối thiểu để sử dụng coupon',
  `max_discount` decimal(12,2) DEFAULT NULL COMMENT 'Giảm giá tối đa cho coupon phần trăm',
  `usage_limit` int DEFAULT NULL COMMENT 'Giới hạn số lần sử dụng toàn hệ thống',
  `usage_per_user` int DEFAULT NULL COMMENT 'Giới hạn số lần sử dụng mỗi user',
  `used_count` int NOT NULL DEFAULT '0' COMMENT 'Số lần đã sử dụng',
  `starts_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `coupons`
--

INSERT INTO `coupons` (`id`, `code`, `type`, `promotion_type`, `discount_type`, `discount_value`, `min_order_value`, `max_discount`, `usage_limit`, `usage_per_user`, `used_count`, `starts_at`, `expires_at`, `created_at`, `updated_at`) VALUES
(1, 'NEW10', 'public', 'order', 'percent', '10.00', NULL, NULL, NULL, NULL, 1, NULL, '2025-12-29 17:00:00', '2025-10-31 14:14:23', '2025-12-20 13:33:22'),
(2, 'SALE100K', 'public', 'order', 'fixed', '100000.00', NULL, NULL, NULL, NULL, 0, NULL, '2025-12-14 17:00:00', '2025-10-31 14:14:23', '2025-12-08 20:15:57'),
(3, 'test1122', 'public', 'order', 'percent', '10.00', NULL, NULL, NULL, NULL, 0, NULL, '2025-12-06 17:00:00', '2025-12-04 06:41:50', '2025-12-04 06:41:50'),
(4, 'GIAM20', 'public', 'order', 'percent', '20.00', '500000.00', '200000.00', 100, 2, 1, '2025-12-18 03:07:30', '2026-03-18 03:07:30', '2025-12-18 03:07:30', '2025-12-18 07:18:55'),
(5, 'GIAM50K', 'public', 'order', 'fixed', '50000.00', '300000.00', NULL, 50, 1, 5, '2025-12-18 03:07:31', '2026-02-18 03:07:31', '2025-12-18 03:07:31', '2025-12-18 03:07:31'),
(6, 'GIAM10SP', 'public', 'product', 'percent', '10.00', NULL, '50000.00', 200, 3, 0, '2025-12-18 03:07:31', '2026-01-18 03:07:31', '2025-12-18 03:07:31', '2025-12-18 03:07:31'),
(7, 'GIAM30KSP', 'public', 'product', 'fixed', '30000.00', '200000.00', NULL, 100, 2, 0, '2025-12-18 03:07:31', '2026-01-18 03:07:31', '2025-12-18 03:07:31', '2025-12-18 03:07:31'),
(8, 'VIP50', 'private', 'order', 'percent', '50.00', '1000000.00', '500000.00', 20, 1, 0, '2025-12-18 03:07:31', '2026-06-18 03:07:31', '2025-12-18 03:07:31', '2025-12-18 03:07:31'),
(9, 'KHONGHAN', 'public', 'order', 'percent', '5.00', NULL, '50000.00', NULL, NULL, 4, '2025-12-18 03:07:31', NULL, '2025-12-18 03:07:31', '2025-12-19 10:10:51'),
(10, 'HETHAN', 'public', 'order', 'fixed', '100000.00', NULL, NULL, 10, 1, 0, '2025-10-18 03:07:31', '2025-12-17 03:07:31', '2025-12-18 03:07:31', '2025-12-18 03:07:31'),
(11, 'CHUABATDAU', 'public', 'order', 'percent', '15.00', NULL, '100000.00', 50, 1, 0, '2025-12-25 03:07:31', '2026-02-18 03:07:31', '2025-12-18 03:07:31', '2025-12-18 03:07:31'),
(12, 'HETLUOT', 'public', 'order', 'fixed', '25000.00', '100000.00', NULL, 10, 1, 10, '2025-12-18 03:07:31', '2026-01-18 03:07:31', '2025-12-18 03:07:31', '2025-12-18 03:07:31'),
(13, 'CAOCAP', 'public', 'order', 'percent', '30.00', '5000000.00', '1500000.00', 30, 1, 2, '2025-12-18 03:07:31', '2026-03-18 03:07:31', '2025-12-18 03:07:31', '2025-12-18 07:20:55');

-- --------------------------------------------------------

--
-- Table structure for table `coupon_product`
--

CREATE TABLE `coupon_product` (
  `id` bigint UNSIGNED NOT NULL,
  `coupon_id` bigint NOT NULL,
  `product_id` bigint NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `coupon_product`
--

INSERT INTO `coupon_product` (`id`, `coupon_id`, `product_id`, `created_at`, `updated_at`) VALUES
(1, 6, 1, '2025-12-18 03:07:31', '2025-12-18 03:07:31'),
(2, 6, 2, '2025-12-18 03:07:31', '2025-12-18 03:07:31'),
(3, 6, 3, '2025-12-18 03:07:31', '2025-12-18 03:07:31'),
(4, 7, 1, '2025-12-18 03:07:31', '2025-12-18 03:07:31'),
(5, 7, 2, '2025-12-18 03:07:31', '2025-12-18 03:07:31');

-- --------------------------------------------------------

--
-- Table structure for table `coupon_usages`
--

CREATE TABLE `coupon_usages` (
  `id` bigint UNSIGNED NOT NULL,
  `coupon_id` bigint NOT NULL,
  `user_id` bigint DEFAULT NULL COMMENT 'NULL nếu là guest',
  `order_id` bigint DEFAULT NULL COMMENT 'ID đơn hàng đã sử dụng coupon',
  `used_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `coupon_usages`
--

INSERT INTO `coupon_usages` (`id`, `coupon_id`, `user_id`, `order_id`, `used_at`) VALUES
(1, 9, NULL, 71, '2025-12-18 04:07:48'),
(2, 13, NULL, 72, '2025-12-18 04:08:37'),
(3, 4, NULL, 1, '2025-12-18 07:18:55'),
(4, 13, 10, 2, '2025-12-18 07:20:55'),
(5, 9, NULL, 3, '2025-12-18 23:43:24'),
(6, 9, 10, 4, '2025-12-19 01:35:45'),
(7, 9, 10, 5, '2025-12-19 10:10:51'),
(8, 1, 13, 7, '2025-12-20 13:33:22');

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
(6, 3, 8, '2025-12-04 06:41:50', '2025-12-04 06:41:50'),
(7, 8, 1, '2025-12-18 03:07:31', '2025-12-18 03:07:31'),
(8, 8, 2, '2025-12-18 03:07:31', '2025-12-18 03:07:31'),
(9, 8, 3, '2025-12-18 03:07:31', '2025-12-18 03:07:31');

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint UNSIGNED NOT NULL,
  `uuid` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
-- Table structure for table `jobs`
--

CREATE TABLE `jobs` (
  `id` bigint UNSIGNED NOT NULL,
  `queue` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `attempts` tinyint UNSIGNED NOT NULL,
  `reserved_at` int UNSIGNED DEFAULT NULL,
  `available_at` int UNSIGNED NOT NULL,
  `created_at` int UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `job_batches`
--

CREATE TABLE `job_batches` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `total_jobs` int NOT NULL,
  `pending_jobs` int NOT NULL,
  `failed_jobs` int NOT NULL,
  `failed_job_ids` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `options` mediumtext COLLATE utf8mb4_unicode_ci,
  `cancelled_at` int DEFAULT NULL,
  `created_at` int NOT NULL,
  `finished_at` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
(3, '2025_01_15_000000_add_type_to_coupons_table', 2),
(4, '2025_12_17_171008_add_replied_to_user_id_to_comments_table', 3),
(5, '2025_12_18_083850_create_coupon_product_table', 4),
(6, '2025_12_18_091113_add_promotion_type_and_starts_at_to_coupons_table', 5),
(8, '2025_12_18_095317_add_advanced_fields_to_coupons_table', 6),
(9, '2025_12_18_095344_create_coupon_usages_table', 7),
(10, '2025_12_18_104013_add_price_snapshot_to_cart_items_table', 8),
(11, '0001_01_01_000000_create_users_table', 1),
(12, '0001_01_01_000001_create_cache_table', 9),
(13, '0001_01_01_000002_create_jobs_table', 9),
(14, '2025_01_15_000001_create_coupon_user_table', 9),
(15, '2025_11_04_000000_create_brands_table', 9),
(16, '2025_11_06_000010_create_sessions_table', 9),
(17, '2025_11_06_000011_add_password_to_users_table', 9),
(18, '2025_11_07_081054_create_categories_table', 1),
(19, '2025_11_13_000000_add_image_to_product_variants_table', 3),
(20, '2025_11_20_000000_create_comments_table', 4),
(21, '2025_12_04_144900_add_email_verified_at_to_users_table', 5),
(22, '2025_12_18_194528_remove_is_banned_from_users_table', 10),
(23, '2025_12_21_110417_add_is_banned_to_users_table', 10),
(24, '2025_12_22_113618_add_cancel_reason_to_orders_table', 10);

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
  `payment_status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0: Chưa thanh toán, 1: Đã thanh toán',
  `status` enum('cho_xac_nhan','cho_thanh_toan','da_xac_nhan','chuan_bi_hang','dang_giao_hang','giao_thanh_cong','giao_that_bai','hoan_thanh','da_huy','da_hoan_tien') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'cho_xac_nhan',
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
  `updated_at` timestamp NULL DEFAULT NULL,
  `cancel_by` enum('admin','khach_hang','he_thong') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `shipping_status` enum('chua_giao','dang_giao_hang','giao_thanh_cong','giao_that_bai') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `cancel_reason` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `cart_id`, `user_id`, `coupon_id`, `subtotal`, `shipping_fee`, `discount_amount`, `total`, `payment_method`, `transaction_id`, `payment_status`, `status`, `shipping_full_name`, `shipping_email`, `shipping_phone`, `shipping_city`, `shipping_district`, `shipping_ward`, `shipping_address`, `notes`, `paid_at`, `created_at`, `updated_at`, `cancel_by`, `shipping_status`, `cancel_reason`) VALUES
(1, 72, 1, 4, '11980000.00', '30000.00', '200000.00', '11810000.00', 'vnpay', NULL, 1, 'da_xac_nhan', 'Duy Dang', 'duyhiha4@gmail.com', '0852855899', 'Hà Nội', NULL, NULL, 'Đông Quang', NULL, NULL, '2025-12-18 07:18:55', '2025-12-18 07:19:25', 'admin', NULL, NULL),
(2, 73, 10, 13, '16990000.00', '30000.00', '1500000.00', '15520000.00', 'vnpay', NULL, 1, 'da_hoan_tien', 'Nguyen Van A', 'duyhiha3@gmail.com', '0852855899', 'Hà Nội', NULL, NULL, 'Đông Quang', NULL, NULL, '2025-12-18 07:20:55', '2025-12-18 07:24:33', 'admin', 'giao_thanh_cong', NULL),
(3, 74, 1, 9, '19990000.00', '30000.00', '50000.00', '19970000.00', 'vnpay', NULL, 1, 'giao_thanh_cong', 'Duy Dang', 'duyhiha4@gmail.com', '0852855899', 'Hà Nội', NULL, NULL, 'Đông Quang', NULL, NULL, '2025-12-18 23:43:24', '2025-12-20 15:02:04', 'admin', 'giao_thanh_cong', NULL),
(4, 75, 10, 9, '40990000.00', '30000.00', '50000.00', '40970000.00', 'vnpay', NULL, 0, 'cho_xac_nhan', 'Nguyen Van A', 'duyhiha3@gmail.com', '0852855899', 'Hà Nội', 'ok', 'ok', 'Đông QuangBa Vì', NULL, NULL, '2025-12-19 01:35:45', '2025-12-19 01:35:45', 'admin', NULL, NULL),
(5, 76, 10, 9, '39970000.00', '30000.00', '50000.00', '39950000.00', 'vnpay', NULL, 0, 'cho_xac_nhan', 'Nguyen Van A', 'duyhiha3@gmail.com', '0852855899', NULL, NULL, NULL, 'Đông QuangBa Vì', NULL, NULL, '2025-12-19 10:10:51', '2025-12-19 10:10:51', 'admin', NULL, NULL),
(6, 77, 10, NULL, '66970000.00', '30000.00', '0.00', '67000000.00', 'vnpay', NULL, 1, 'da_xac_nhan', 'Nguyen Van A', 'duyhiha3@gmail.com', '0852855899', NULL, NULL, NULL, 'Đông QuangBa Vì', NULL, NULL, '2025-12-19 10:15:04', '2025-12-19 10:15:38', 'admin', NULL, NULL),
(7, 78, 13, 1, '16990000.00', '30000.00', '1699000.00', '15321000.00', 'vnpay', NULL, 0, 'da_huy', 'Phạm Huân', 'huanpvph52348@gmail.com', '0867869431', 'nam định', 'xuân trường', 'xuân trường', 'nam định', NULL, NULL, '2025-12-20 13:33:22', '2025-12-22 04:21:51', 'admin', NULL, NULL),
(8, 80, 14, NULL, '16990000.00', '30000.00', '0.00', '17020000.00', 'cod', NULL, 0, 'chuan_bi_hang', 'Huân', 'huanpvph52348@gmail.com', '0867869431', 'nam định', 'xuân trường', 'xuân trường', 'nam định', NULL, NULL, '2025-12-22 00:38:48', '2025-12-22 04:28:02', 'admin', 'chua_giao', NULL),
(9, 81, 14, NULL, '5690000.00', '30000.00', '0.00', '5720000.00', 'cod', NULL, 0, 'da_huy', 'Huân', 'huanpvph52348@gmail.com', '0867869431', 'nam định', 'xuân trường', 'xuân trường', 'nam định', NULL, NULL, '2025-12-22 04:29:50', '2025-12-22 04:47:22', 'admin', NULL, 'Đổi ý, không muốn mua nữa'),
(10, 82, 14, NULL, '10880000.00', '30000.00', '0.00', '10910000.00', 'cod', NULL, 0, 'da_huy', 'Huân', 'huanpvph52348@gmail.com', '0867869431', 'nam định', 'xuân trường', 'xuân trường', 'nam định', NULL, NULL, '2025-12-22 04:49:06', '2025-12-22 06:26:51', 'admin', NULL, 'Đổi ý, không muốn mua nữa');

-- --------------------------------------------------------

--
-- Table structure for table `order_items`
--

CREATE TABLE `order_items` (
  `id` bigint UNSIGNED NOT NULL,
  `order_id` bigint UNSIGNED NOT NULL,
  `product_id` bigint UNSIGNED DEFAULT NULL,
  `product_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `product_image` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
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
(44, 43, 6, 'AirPods Pro 2', 'products/FdV6RDER1jlgAvZZ8DxdlYL9jg84qyQkjN3QOlxX.png', 6, '5190000.00', '31140000.00', '2025-12-17 03:27:36', '2025-12-17 03:27:36'),
(45, 44, 3, 'Xiaomi Redmi Note 13', 'https://example.com/rn13.jpg', 2, '5990000.00', '11980000.00', '2025-12-17 07:01:55', '2025-12-17 07:01:55'),
(46, 44, 6, 'AirPods Pro 2', 'products/FdV6RDER1jlgAvZZ8DxdlYL9jg84qyQkjN3QOlxX.png', 1, '5690000.00', '5690000.00', '2025-12-17 07:01:55', '2025-12-17 07:01:55'),
(47, 45, 5, 'Samsung Galaxy Z Fold7 12GB', 'products/ehEGZYvwnnWHumJ96IPHZXfE22RglAO0P4poat4D.jpg', 2, '40990000.00', '81980000.00', '2025-12-17 07:04:22', '2025-12-17 07:04:22'),
(48, 46, 1, 'iPhone 15 Pro 256GB', 'products/75B73Bgvi6D8y6j2WtvMFjr6aD4GZ1ZXVKJa5FBl.webp', 1, '26990000.00', '26990000.00', '2025-12-17 07:33:11', '2025-12-17 07:33:11'),
(49, 47, 5, 'Samsung Galaxy Z Fold7 12GB', 'products/ehEGZYvwnnWHumJ96IPHZXfE22RglAO0P4poat4D.jpg', 2, '40990000.00', '81980000.00', '2025-12-17 07:40:29', '2025-12-17 07:40:29'),
(50, 48, 6, 'AirPods Pro 2', 'products/FdV6RDER1jlgAvZZ8DxdlYL9jg84qyQkjN3QOlxX.png', 1, '5690000.00', '5690000.00', '2025-12-17 07:41:31', '2025-12-17 07:41:31'),
(51, 49, 5, 'Samsung Galaxy Z Fold7 12GB', 'products/ehEGZYvwnnWHumJ96IPHZXfE22RglAO0P4poat4D.jpg', 2, '40990000.00', '81980000.00', '2025-12-17 07:47:42', '2025-12-17 07:47:42'),
(54, 52, 5, 'Samsung Galaxy Z Fold7 12GB', 'products/ehEGZYvwnnWHumJ96IPHZXfE22RglAO0P4poat4D.jpg', 1, '40990000.00', '40990000.00', '2025-12-17 07:55:08', '2025-12-17 07:55:08'),
(55, 53, 5, 'Samsung Galaxy Z Fold7 12GB', 'products/ehEGZYvwnnWHumJ96IPHZXfE22RglAO0P4poat4D.jpg', 1, '40990000.00', '40990000.00', '2025-12-17 07:55:45', '2025-12-17 07:55:45'),
(56, 54, 3, 'Xiaomi Redmi Note 13', 'https://example.com/rn13.jpg', 1, '5990000.00', '5990000.00', '2025-12-17 08:16:01', '2025-12-17 08:16:01'),
(57, 55, 6, 'AirPods Pro 2', 'products/FdV6RDER1jlgAvZZ8DxdlYL9jg84qyQkjN3QOlxX.png', 1, '5690000.00', '5690000.00', '2025-12-17 08:34:00', '2025-12-17 08:34:00'),
(58, 56, 6, 'AirPods Pro 2', 'products/FdV6RDER1jlgAvZZ8DxdlYL9jg84qyQkjN3QOlxX.png', 2, '5690000.00', '11380000.00', '2025-12-17 08:36:48', '2025-12-17 08:36:48'),
(59, 57, 4, 'OPPO Reno 12', 'products/itw42M7gY1n0eHYbIKIOaK1LmKIRYtp9ILG5I6qa.jpg', 1, '12990000.00', '12990000.00', '2025-12-17 08:53:16', '2025-12-17 08:53:16'),
(60, 58, 6, 'AirPods Pro 2', 'products/FdV6RDER1jlgAvZZ8DxdlYL9jg84qyQkjN3QOlxX.png', 1, '5690000.00', '5690000.00', '2025-12-17 09:07:55', '2025-12-17 09:07:55'),
(61, 59, 2, 'Samsung Galaxy S24 256GB', 'https://example.com/s24.jpg', 1, '19990000.00', '19990000.00', '2025-12-17 10:33:05', '2025-12-17 10:33:05'),
(62, 60, 6, 'AirPods Pro 2', 'products/FdV6RDER1jlgAvZZ8DxdlYL9jg84qyQkjN3QOlxX.png', 1, '5690000.00', '5690000.00', '2025-12-17 20:03:59', '2025-12-17 20:03:59'),
(63, 61, 9, 'iPhone 15', 'products/UejXoqYAFA1Ir0sONBAhYoS83SWkSNWVkuijOo24.webp', 1, '16990000.00', '16990000.00', '2025-12-17 20:06:31', '2025-12-17 20:06:31'),
(64, 62, 6, 'AirPods Pro 2', 'products/FdV6RDER1jlgAvZZ8DxdlYL9jg84qyQkjN3QOlxX.png', 1, '5690000.00', '5690000.00', '2025-12-17 20:09:41', '2025-12-17 20:09:41'),
(65, 63, 6, 'AirPods Pro 2', 'products/FdV6RDER1jlgAvZZ8DxdlYL9jg84qyQkjN3QOlxX.png', 1, '5690000.00', '5690000.00', '2025-12-17 20:10:22', '2025-12-17 20:10:22'),
(66, 64, 6, 'AirPods Pro 2', 'products/FdV6RDER1jlgAvZZ8DxdlYL9jg84qyQkjN3QOlxX.png', 1, '5690000.00', '5690000.00', '2025-12-17 20:11:20', '2025-12-17 20:11:20'),
(67, 65, 5, 'Samsung Galaxy Z Fold7 12GB', 'products/ehEGZYvwnnWHumJ96IPHZXfE22RglAO0P4poat4D.jpg', 1, '40990000.00', '40990000.00', '2025-12-17 20:14:05', '2025-12-17 20:14:05'),
(68, 66, 2, 'Samsung Galaxy S24 256GB', 'https://example.com/s24.jpg', 1, '19990000.00', '19990000.00', '2025-12-17 20:19:08', '2025-12-17 20:19:08'),
(76, 71, 9, 'iPhone 15', 'products/UejXoqYAFA1Ir0sONBAhYoS83SWkSNWVkuijOo24.webp', 1, '16990000.00', '16990000.00', '2025-12-18 04:07:48', '2025-12-18 04:07:48'),
(77, 72, 9, 'iPhone 15', 'products/UejXoqYAFA1Ir0sONBAhYoS83SWkSNWVkuijOo24.webp', 1, '16990000.00', '16990000.00', '2025-12-18 04:08:37', '2025-12-18 04:08:37'),
(78, 1, 3, 'Xiaomi Redmi Note 13', 'https://example.com/rn13.jpg', 2, '5990000.00', '11980000.00', '2025-12-18 07:18:55', '2025-12-18 07:18:55'),
(79, 2, 9, 'iPhone 15', 'products/UejXoqYAFA1Ir0sONBAhYoS83SWkSNWVkuijOo24.webp', 1, '16990000.00', '16990000.00', '2025-12-18 07:20:55', '2025-12-18 07:20:55'),
(80, 3, 2, 'Samsung Galaxy S24 256GB', 'https://example.com/s24.jpg', 1, '19990000.00', '19990000.00', '2025-12-18 23:43:24', '2025-12-18 23:43:24'),
(81, 4, 5, 'Samsung Galaxy Z Fold7 12GB', 'products/mzkiQrpjWCtRd2gV6udt1rEFmXUBs4jmupkFRFdP.webp', 1, '40990000.00', '40990000.00', '2025-12-19 01:35:45', '2025-12-19 01:35:45'),
(82, 5, 3, 'Xiaomi Redmi Note 13', 'https://example.com/rn13.jpg', 1, '5990000.00', '5990000.00', '2025-12-19 10:10:51', '2025-12-19 10:10:51'),
(83, 5, 9, 'iPhone 15', 'products/ywYfTAlpzDQSEyNG1pXFhqE3DJIAGdEBI4HrU0Na.webp', 2, '16990000.00', '33980000.00', '2025-12-19 10:10:51', '2025-12-19 10:10:51'),
(84, 6, 4, 'OPPO Reno 12', 'products/itw42M7gY1n0eHYbIKIOaK1LmKIRYtp9ILG5I6qa.jpg', 2, '12990000.00', '25980000.00', '2025-12-19 10:15:04', '2025-12-19 10:15:04'),
(85, 6, 5, 'Samsung Galaxy Z Fold7 12GB', 'products/mzkiQrpjWCtRd2gV6udt1rEFmXUBs4jmupkFRFdP.webp', 1, '40990000.00', '40990000.00', '2025-12-19 10:15:04', '2025-12-19 10:15:04'),
(86, 7, 9, 'iPhone 15', 'products/ywYfTAlpzDQSEyNG1pXFhqE3DJIAGdEBI4HrU0Na.webp', 1, '16990000.00', '16990000.00', '2025-12-20 13:33:22', '2025-12-20 13:33:22'),
(87, 8, 9, 'iPhone 15', 'products/ywYfTAlpzDQSEyNG1pXFhqE3DJIAGdEBI4HrU0Na.webp', 1, '16990000.00', '16990000.00', '2025-12-22 00:38:48', '2025-12-22 00:38:48'),
(88, 9, 6, 'AirPods Pro 2', 'products/EAM90Er5TAZGcpGFS8IV1yvNX6k1EcGcx9c3Htpu.webp', 1, '5690000.00', '5690000.00', '2025-12-22 04:29:50', '2025-12-22 04:29:50'),
(89, 10, 6, 'AirPods Pro 2', 'products/EAM90Er5TAZGcpGFS8IV1yvNX6k1EcGcx9c3Htpu.webp', 1, '5690000.00', '5690000.00', '2025-12-22 04:49:06', '2025-12-22 04:49:06'),
(90, 10, 6, 'AirPods Pro 2', 'products/EAM90Er5TAZGcpGFS8IV1yvNX6k1EcGcx9c3Htpu.webp', 1, '5190000.00', '5190000.00', '2025-12-22 04:49:06', '2025-12-22 04:49:06');

-- --------------------------------------------------------

--
-- Table structure for table `order_returns`
--

CREATE TABLE `order_returns` (
  `id` bigint NOT NULL,
  `order_id` bigint UNSIGNED NOT NULL COMMENT 'ID đơn hàng',
  `return_code` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Mã hoàn trả',
  `contact_phone` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Số điện thoại liên hệ',
  `refund_method` enum('Momo','Ngân hàng') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Momo' COMMENT 'Phương thức hoàn tiền',
  `bank_name` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Tên ngân hàng',
  `bank_account_number` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Số tài khoản',
  `bank_account_name` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Tên chủ tài khoản',
  `reason` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Lý do hoàn trả',
  `returned_at` timestamp NULL DEFAULT NULL COMMENT 'Thời gian người dùng gửi hàng',
  `received_at` timestamp NULL DEFAULT NULL COMMENT 'Thời gian admin nhận được hàng',
  `refunded_at` timestamp NULL DEFAULT NULL COMMENT 'Thời gian thực hiện hoàn tiền',
  `shipping_status` enum('Chưa vận chuyển','Đang vận chuyển','Đã vận chuyển','Giao hàng thất bại') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT 'Chưa vận chuyển' COMMENT 'Trạng thái vận chuyển hàng hoàn',
  `status` enum('Chưa giải quyết','Thông qua','Từ chối','Đang vận chuyển','Đã nhận','Đã hoàn tiền') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT 'Chưa giải quyết' COMMENT 'Trạng thái yêu cầu',
  `admin_note` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci COMMENT 'Admin phản hồi hoàn trả',
  `refunded_by` bigint DEFAULT NULL COMMENT 'ID người thực hiện hoàn tiền',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `order_returns`
--

INSERT INTO `order_returns` (`id`, `order_id`, `return_code`, `contact_phone`, `refund_method`, `bank_name`, `bank_account_number`, `bank_account_name`, `reason`, `returned_at`, `received_at`, `refunded_at`, `shipping_status`, `status`, `admin_note`, `refunded_by`, `created_at`, `updated_at`) VALUES
(1, 2, 'RT-202512-00001', '0852855899', 'Ngân hàng', 'MBBANK', '0899899899', 'Duy', 'Không thích bạn ơiiiiii', '2025-12-18 07:24:16', '2025-12-18 07:24:23', '2025-12-18 07:24:33', 'Đã vận chuyển', 'Đã hoàn tiền', NULL, 2, '2025-12-18 07:23:23', '2025-12-18 07:24:33');

-- --------------------------------------------------------

--
-- Table structure for table `order_return_images`
--

CREATE TABLE `order_return_images` (
  `id` bigint NOT NULL,
  `order_return_id` bigint NOT NULL COMMENT 'ID yêu cầu hoàn trả',
  `type` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT 'evidence' COMMENT 'Loại ảnh (ví dụ: ảnh lỗi, ảnh vận đơn)',
  `path` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Đường dẫn tệp tin',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `order_return_images`
--

INSERT INTO `order_return_images` (`id`, `order_return_id`, `type`, `path`, `created_at`) VALUES
(1, 1, 'evidence', 'order_returns/5iJpKEw5UQcrflSR1Izmlszinl1CK3TKaaCyozHH.jpg', '2025-12-18 14:23:23'),
(2, 1, 'evidence', 'order_returns/DZp7heZTF3aPDKnQNkljz0ukaLoxCEZSNRFy6Wof.jpg', '2025-12-18 14:23:23'),
(3, 1, 'evidence', 'order_returns/VoVAlRPKZ4mlOEb9zIYdhgepCF7Oso6t953Vd7hQ.jpg', '2025-12-18 14:23:23'),
(4, 1, 'evidence', 'order_returns/al02E9Zcq0oH5m8pPdSkHNwkmxOHTVJxV1iMyUUB.jpg', '2025-12-18 14:23:23'),
(5, 1, 'refund_proof', 'order_returns/refund_proofs/XWkPXkr8SYxXMgnrgnG2Hc1hX4TKFBSd3un2PrLh.jpg', '2025-12-18 14:24:33');

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
(1, 'iPhone 15 Pro 256GB', 'products/75B73Bgvi6D8y6j2WtvMFjr6aD4GZ1ZXVKJa5FBl.webp', '27990000', 'iphone-15-pro-256gb', 'Flagship Apple', 0, 1, 1, 135, '2025-10-31 14:14:23', '2025-12-17 07:32:11'),
(2, 'Samsung Galaxy S24 256GB', 'https://example.com/s24.jpg', '20990000', 'samsung-galaxy-s24-256gb', 'Flagship Samsung', 0, 1, 2, 104, '2025-10-31 14:14:23', '2025-12-18 23:42:26'),
(3, 'Xiaomi Redmi Note 13', 'https://example.com/rn13.jpg', '5990000', 'xiaomi-redmi-note-13', 'Giá tốt', 0, 1, 3, 87, '2025-10-31 14:14:23', '2025-12-19 01:46:51'),
(4, 'OPPO Reno 12', 'products/itw42M7gY1n0eHYbIKIOaK1LmKIRYtp9ILG5I6qa.jpg', '12990000', 'oppo-reno-12', 'Thiết kế đẹp', 0, 1, 4, 70, '2025-10-31 14:14:23', '2025-12-19 10:14:23'),
(5, 'Samsung Galaxy Z Fold7 12GB', 'products/mzkiQrpjWCtRd2gV6udt1rEFmXUBs4jmupkFRFdP.webp', '0', 'samsung-galaxy-z-fold7-12gb', 'Samsung Galaxy Z Fold7 là điện thoại gập mỏng nhẹ với chip Snapdragon 8 Elite, màn hình chính 8 inch, camera chính 200 MP và khả năng chống nước IPX8. Máy có thiết kế cải tiến, siêu mỏng (8.9mm khi gập), trọng lượng nhẹ 215g và được trang bị bản lề mới để giảm nếp gấp màn hình. \r\n\r\n\r\n\r\nThiết kế và màn hình\r\n\r\n\r\n\r\nSiêu mỏng nhẹ: \r\n\r\n\r\n\r\nChỉ dày 8.9mm khi gập và nặng 215g, nhẹ hơn cả một số mẫu flagship thông thường. \r\n\r\n\r\n\r\nMàn hình chính: \r\n\r\n\r\n\r\nKích thước lớn 8 inch, Dynamic AMOLED 2X, tần số quét 120Hz, mang đến trải nghiệm làm việc và giải trí không giới hạn. \r\n\r\n\r\n\r\nMàn hình phụ: \r\n\r\n\r\n\r\nTỷ lệ 21:9, kích thước 6.5 inch, giúp sử dụng các tác vụ bằng một tay dễ dàng hơn. \r\n\r\n\r\n\r\nĐộ bền: \r\n\r\n\r\n\r\nKhung viền Armor Aluminum, mặt lưng Gorilla Glass Victus 2, màn hình phụ được bảo vệ bởi kính cường lực Ceramic 2, và kính siêu mỏng trên màn hình chính được gia cố bằng lưới Titan Grade 4. \r\n\r\n\r\n\r\nKhả năng chống nước: \r\n\r\n\r\n\r\nĐạt chuẩn IPX8. \r\n\r\n\r\n\r\nHiệu năng và cấu hình\r\n\r\n\r\n\r\nChip xử lý: Snapdragon 8 Elite For Galaxy.\r\n\r\n\r\n\r\nRAM: 12GB hoặc có thể lên đến 16GB.\r\n\r\n\r\n\r\nBộ nhớ: Tùy chọn 256GB, 512GB và 1TB (chuẩn UFS 4.0).\r\n\r\n\r\n\r\nHệ điều hành: Android 16. \r\n\r\n\r\n\r\nCamera và pin\r\n\r\n\r\n\r\nCamera chính: 200 MP với OIS, mang lại hình ảnh sắc nét và khả năng thu sáng tốt hơn ngay cả trong điều kiện thiếu sáng. \r\n\r\n\r\n\r\nCamera góc siêu rộng: 12 MP. \r\n\r\n\r\n\r\nCamera tele: 10 MP, hỗ trợ zoom quang 3x. \r\n\r\n\r\n\r\nCamera selfie: 10 MP cải tiến ống kính. \r\n\r\n\r\n\r\nPin: 4.400mAh, hỗ trợ sạc nhanh 25W, sạc không dây 15W và sạc ngược không dây 4.5W.', 1, 1, 2, 66, '2025-10-31 14:14:23', '2025-12-21 03:39:17'),
(6, 'AirPods Pro 2', 'products/EAM90Er5TAZGcpGFS8IV1yvNX6k1EcGcx9c3Htpu.webp', '0', 'airpods-pro-2', '⭐ Cam Kết 1 Đổi 1 Trong 30 Ngày Với Lỗi NSX\r\n\r\n\r\n\r\n ĐỂ ĐẢM BẢO QUYỀN LỢI, KHI NHẬN HÀNG CÓ BẤT KỲ VẤN ĐỀ GÌ VỀ SẢN PHẨM, KHÁCH VUI LÒNG INBOX NGAY CHO SHOP ĐỂ GIẢI QUYẾT TRƯỚC KHI ĐÁNH \r\n\r\n\r\n\r\nGIÁ TRÊN SHOPEE\r\n\r\n\r\n\r\n\r\n\r\nTÍNH NĂNG SẢN PHẨM:\r\n\r\n\r\n\r\n     Tính năng nghe gọi và mic đàm thoại ổn định\r\n\r\n\r\n\r\n\r\n\r\n     Thời lượng Pin trâu từ 5-6h liên tục\r\n\r\n\r\n\r\n\r\n\r\n     Hộp sạc có thể sạc cho tai nghe 3-4 lần\r\n\r\n\r\n\r\n\r\n\r\n     Tai nghe còn được trang bị tính năng tự động kích hoạt trong lúc đeo và ngắt kết nối lúc ta tháo ra\r\n\r\n\r\n\r\n\r\n\r\n     Tai nghe cũng có cảm ứng 1-2 chạm dễ dàng\r\n\r\n\r\n\r\n\r\n\r\n     Tích hợp chip xử lý tự động kết nối khi mở nắp\r\n\r\n\r\n\r\n\r\n\r\n     Tai nghe được trang bị thêm âm thanh không gian \r\n\r\n\r\n\r\n\r\n\r\n     Hỗ trợ sạc không dây tiện lợi sử dụng\r\n\r\n\r\n\r\n\r\n\r\n     Tính năng tìm kiếm tai nghe khi thất lạc\r\n\r\n\r\n\r\n\r\n\r\nBẠN SẼ NHẬN ĐƯỢC\r\n\r\n\r\n\r\n\r\n\r\n1. Tai nghe\r\n\r\n\r\n\r\n\r\n\r\n2. Dock sạc( Hộp Sạc)\r\n\r\n\r\n\r\n\r\n\r\n3. Dây Sạc Tai Nghe\r\n\r\n\r\n\r\n\r\n\r\n Sản Phẩm Như Mô Tả \r\n\r\n\r\n\r\n\r\n\r\n Sản Phẩm Mới 100% Nguyên Seal ( Chưa Qua Sử Dụng )\r\n\r\n\r\n\r\n\r\n\r\n Bảo hành lỗi 1 đổi 1 tất cả sản phẩm nếu lỗi do nhà sản xuất\r\n\r\n\r\n\r\n\r\n\r\n Luôn ưu đãi cho khách hàng cũ và mới\r\n\r\n\r\n\r\n\r\n\r\n Luôn cập nhật những sản phẩm mới nhất, chất lượng nhất, dẫn đầu xu hướng\r\n\r\n\r\n\r\n\r\n\r\n⭐ HƯỚNG DẪN SỬ DỤNG : \r\n\r\n\r\n\r\n\r\n\r\n- Đối với dt i:\r\n\r\n\r\n\r\n\r\n\r\nBước 1: Mở nắp thiết bị và để gần đt của bạn\r\n\r\n\r\n\r\n\r\n\r\nBước 2: Điện thoại sẽ mở ra pop up yêu cầu kết nối, bạn làm theo hướng dẫn để kết nối\r\n\r\n\r\n\r\n\r\n\r\nBước 3: Vào cài đặt bluetooth để tùy chỉnh tên và thao tác chạm \r\n\r\n\r\n\r\n\r\n\r\n- Đối với điện thoại and\r\n\r\n\r\n\r\n\r\n\r\nBước 1: Mở nắp thiết bị và vào phần cài đặt mở bluetooth điện thoại lên\r\n\r\n\r\n\r\n\r\n\r\nBước 2: Dò thiết bị và kết nối với thiết bị\r\n\r\n\r\n\r\n\r\n\r\n-Lưu ý: Đối với điện thoại and thao tác chạm là: \r\n\r\n\r\n\r\n\r\n\r\n- 2 chạm sẽ dừng nhạc\r\n\r\n\r\n\r\n\r\n\r\n- 3 chạm sẽ chuyển bài', 1, 4, 1, 203, '2025-10-31 14:14:23', '2025-12-22 04:48:39'),
(9, 'iPhone 15', 'products/ywYfTAlpzDQSEyNG1pXFhqE3DJIAGdEBI4HrU0Na.webp', '0', 'iphone-15', 'Thông số kỹ thuật\r\nMàn hình:	Super Retina XDR OLED, HDR10, Dolby Vision, 1000 nits (HBM), 2000 nits (tối đa)\r\n6.1 inches, 1.5K (1179 x 2556 pixels), tỷ lệ 19.5:9\r\nMật độ điểm ảnh ~461 ppi\r\nCeramic Shield glass\r\nHệ điều hành:	iOS 17\r\nĐược lên iOS 18\r\nCamera sau:	48 MP, f/1.6, 26mm (góc rộng), dual pixel PDAF, sensor-shift OIS\r\n12 MP, f/2.4, 13mm, 120˚ (góc siêu rộng)\r\nQuay phim: 4K@24/25/30/60fps, 1080p@25/30/60/120/240fps, HDR, Dolby Vision HDR (up to 60fps), Cinematic mode (4K@30fps), stereo sound rec.\r\nCamera trước:	12 MP, f/1.9, 23mm (góc rộng), PDAF\r\nSL 3D (độ sâu/sinh trắc học)\r\nHDR, Cinematic mode (4K@30fps)\r\nQuay phim: 4K@24/25/30/60fps, 1080p@25/30/60/120fps, gyro-EIS\r\nCPU:	Apple A16 Bionic (4 nm)\r\n6 nhân (2x3.46 GHz & 4x2.02 GHz)\r\nGPU: Apple GPU (5 lõi đồ họa)\r\nRAM:	6GB\r\nBộ nhớ trong:	128-512GB, NVMe\r\nThẻ SIM:	Nano SIM và eSIM (Quốc tế)\r\nChỉ eSIM (bản Mỹ)\r\n2 SIM,Nano SIM (Trung Quốc)\r\nDung lượng pin:	Li-Ion 3349 mAh\r\nSạc nhanh > 20W, 50% trong 30 ph (QC)\r\nSạc không dây (MagSafe) 15W\r\nSạc không dây (Qi2) 15W\r\nSạc ngược 4.5W (dây)\r\nThiết kế:	Khung nhôm vuông vức\r\nKính sau Corning-made\r\nKính trước Ceramic Shield\r\nThiết kế màn hình Dynamic Island\r\nKháng nước, bụi IP68', 1, 1, 1, 230, '2025-11-19 13:58:20', '2025-12-22 08:56:14'),
(13, 'iPad Pro chip M5 11 inch', NULL, '0', 'ipad-pro-chip-m5-11-inch', NULL, 1, 1, 1, 0, '2025-12-20 09:23:54', '2025-12-20 09:23:54');

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
(1, 1, '27990000', '26990000', 19, 6, 'iPhone 15 Pro Max', '1111111111111', NULL, NULL, NULL, 'Titan tự nhiên 256GB', 'product_variants/hjngb8qozLYIdjOJEwVbdjWwHM9KSVGTc6YWhIZS.jpg', 'available', '2025-10-31 14:14:23', '2025-12-17 07:33:11'),
(2, 1, '27990000', NULL, 15, 2, 'IP15P-256-BLK', '1111111111112', NULL, NULL, NULL, 'Titan đen 256GB', NULL, 'available', '2025-10-31 14:14:23', '2025-10-31 14:14:23'),
(3, 2, '20990000', '19990000', 27, 13, 'S24-256-BLK', '2222222222221', NULL, NULL, NULL, 'Đen 256GB', NULL, 'available', '2025-10-31 14:14:23', '2025-12-18 23:43:24'),
(4, 2, '20990000', NULL, 25, 6, 'S24-256-VIO', '2222222222222', NULL, NULL, NULL, 'Tím 256GB', NULL, 'available', '2025-10-31 14:14:23', '2025-10-31 14:14:23'),
(5, 3, '5990000', NULL, 34, 26, 'RN13-128-BLU', '3333333333331', NULL, NULL, NULL, 'Xanh 128GB', NULL, 'available', '2025-10-31 14:14:23', '2025-12-19 10:10:51'),
(6, 4, '12990000', NULL, 15, 7, 'RENO12-256-SLV', '4444444444441', NULL, NULL, NULL, 'Bạc 256GB', NULL, 'available', '2025-10-31 14:14:23', '2025-12-19 10:15:04'),
(7, 5, '50990000', '44990000', 10, 1, 'ZFL7-12-SIL-512', '5555555555551', 4, NULL, 9, 'Samsung Galaxy Z Fold7 12GB', 'product_variants/rx8mqz5v8olJC3vt73iIAlp658qb3O4XZ8DePNTc.webp', 'available', '2025-10-31 14:14:23', '2025-12-04 10:39:55'),
(8, 6, '6190000', '5690000', 14, 61, 'AirPods Pro 2 (Lightning)', '6666666666661', NULL, 6, NULL, 'AirPods Pro 2 (Lightning)', 'product_variants/XqJOdABcgk4sN4cbQBX9OCDnJVWtPY20znVjn9sP.webp', 'available', '2025-10-31 14:14:23', '2025-12-22 04:49:06'),
(9, 6, '6190000', '5190000', 99, 27, 'AirPods Pro 2 (USB-C)', '132456789', NULL, 5, NULL, 'Đạt chuẩn IP54 (chống bụi và nước tốt hơn)\r\nHỗ trợ Âm thanh thích ứng (Adaptive Audio), Nhận biết cuộc hội thoại (Conversation Awareness) (yêu cầu iOS 17 trở lên)\r\nHỗ trợ âm thanh Lossless với độ trễ cực thấp khi kết nối với Apple Vision Pro', 'product_variants/5qeFUNz71ygSmEi0HTIIIhhXPxPaehIvqsjoM6gr.webp', 'available', '2025-11-13 01:58:15', '2025-12-22 04:49:06'),
(11, 9, '19990000', '16990000', 2, 16, 'IP15-128-H', '7777777777771', 2, 4, 4, 'Màu Hồng 128GB', 'product_variants/xkgyrNRJiXXgejQdPGIkc3L32TWBh432YbuNTHZX.webp', 'available', '2025-11-19 13:58:20', '2025-12-22 00:38:48'),
(12, 9, '19990000', '16990000', 10, 2, 'IP15-128-D', '7777777777772', 2, 4, 1, NULL, 'product_variants/at0JZh7RTFba3k3iDp0aHL9VZgCGeNXvuEA4DlIO.webp', 'available', '2025-11-19 13:58:20', '2025-11-21 05:46:26'),
(13, 9, '19990000', '16990000', 10, 2, 'IP15-128-X', '20112025', 2, 4, 3, NULL, 'product_variants/Ks3h4mqYQuw6781ZZG6HrH7WpFf1rtc07kywROjt.webp', 'available', '2025-11-21 05:47:54', '2025-11-21 05:47:54'),
(14, 9, '22990000', '20490000', 14, 3, 'IP15-256-H', '155622', 3, 4, 4, NULL, 'product_variants/FnJPAjaEGl3sqSag8QSFtyt76Asayht9jx0K83IC.webp', 'available', '2025-11-21 05:50:52', '2025-11-27 07:57:33'),
(15, 9, '19990000', '16990000', 75, 28, 'IP15-128-V', '01122025', 2, 4, 7, NULL, 'product_variants/lMd9nRUqikC3AEjdKruD27CVMBjP0jMJ1tTbLPte.webp', 'available', '2025-12-01 06:40:38', '2025-12-17 03:44:28'),
(16, 9, '19990000', '16990000', 9, 11, 'IP15-128-XL', '20112025', 2, 4, 6, NULL, 'product_variants/t8OpRNjoVTWEcXrB6GLY31EbWpekuwN9dOSvCadT.png', 'available', '2025-12-01 06:41:55', '2025-12-17 03:44:17'),
(17, 5, '46990000', '40990000', 0, 13, 'ZFL7-12-BLK-256', '0105122005', 3, NULL, 10, NULL, 'product_variants/dyWIfAy2TJhzn78uANao1hMB1PHPY7933h2GKWvS.webp', 'out_of_stock', '2025-12-04 10:32:29', '2025-12-17 20:14:05'),
(18, 5, '50990000', '44990000', 10, 3, 'ZFL7-12-BLK-512', '0205122005', 4, NULL, 10, NULL, 'product_variants/aC0l74ta7y2gNeWUuWdQarCjSQz4o2wbPkoMp7q9.webp', 'available', '2025-12-04 10:37:34', '2025-12-04 10:37:34'),
(19, 5, '46990000', '40990000', 6, 6, 'ZFL7-12-SIL-256', '0305122005', 3, NULL, 9, NULL, 'product_variants/o02gA8UIkQgKS2RYUSd1bwtkCCnk0aEAaAoAMZtr.webp', 'available', '2025-12-04 10:38:50', '2025-12-19 10:15:04'),
(20, 5, '46990000', '40990000', 10, 3, 'ZFL7-12-BLS-256', '0405122005', 3, NULL, 11, NULL, 'product_variants/GGdpehVEId7Njr5e3cQeS0PKUlhbYWjZEopCOFER.webp', 'available', '2025-12-04 10:40:49', '2025-12-04 10:40:49'),
(21, 5, '50990000', '44990000', 10, 4, 'ZFL7-12-BLS-512', '0505122005', 4, NULL, 11, NULL, 'product_variants/HbhEBNMoJz34Cn23bIh2ul0AUtymXj1zUXd21ZuO.webp', 'available', '2025-12-04 10:41:34', '2025-12-04 10:41:34'),
(22, 13, '0', NULL, 0, 0, 'DEFAULT-13', NULL, NULL, NULL, NULL, NULL, NULL, 'available', '2025-12-20 09:23:54', '2025-12-20 09:23:54');

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
('SrwcReF2uxBhQbD6dOkzyNjdHT1rGo73SZu0qdX8', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiVkpwWDJwSnI2emJyNVhac0ZJdG5ZcWhXUGpiZm5rc2p1eTI3YlE2ZyI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6Mzk6Imh0dHA6Ly9sb2NhbGhvc3Q6ODAwMC92ZXJpZmljYXRpb24tc2VudCI7czo1OiJyb3V0ZSI7czoxNzoidmVyaWZpY2F0aW9uLnNlbnQiO319', 1766167669);

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
  `password` varchar(255) DEFAULT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `avatar` varchar(255) DEFAULT NULL,
  `phone` varchar(30) DEFAULT NULL,
  `address` text COMMENT 'Địa chỉ chi tiết của người dùng',
  `password_hash` varchar(255) DEFAULT NULL,
  `role_id` tinyint DEFAULT NULL,
  `is_banned` tinyint(1) NOT NULL DEFAULT '0',
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `status` enum('active','banned','inactive') DEFAULT 'active',
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `verification_token` varchar(500) DEFAULT NULL,
  `verification_expires_at` varchar(500) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password`, `remember_token`, `avatar`, `phone`, `address`, `password_hash`, `role_id`, `is_banned`, `email_verified_at`, `status`, `deleted_at`, `created_at`, `updated_at`, `verification_token`, `verification_expires_at`) VALUES
(1, 'Nguyen Van A', 'vana@example.com', NULL, NULL, NULL, NULL, NULL, 'hashed_password_123', 2, 0, '2025-11-16 04:07:16', 'active', NULL, '2025-11-07 08:09:49', '2025-12-21 04:21:15', NULL, NULL),
(2, 'Nguyen Van A', 'duyhiha4@gmail.com', NULL, NULL, NULL, NULL, NULL, '$2y$12$d9TwyFEP95B3H1T09ychfOpJ8odvdGHp69oTZehVja43XbBqAeUzi', 1, 0, '2025-11-16 05:02:10', 'active', NULL, '2025-11-14 06:19:09', '2025-11-16 05:02:10', NULL, NULL),
(3, 'Nguyen Van A', 'duyhiha5@gmail.com', NULL, NULL, NULL, NULL, NULL, '$2y$12$aLo3c/3F0wLwo8W6/ofhYulRroMQTJUO/D4u8vmj/KeerUwlsiBfK', 2, 0, NULL, 'active', NULL, '2025-11-16 04:13:07', '2025-11-16 04:13:07', NULL, NULL),
(4, 'Admin', 'admin@phonezy.local', NULL, NULL, NULL, '0900000000', NULL, '$2y$10$hash_admin', 1, 0, NULL, 'active', NULL, '2025-10-31 14:14:22', '2025-10-31 14:14:22', NULL, NULL),
(5, 'Nguyen Van A', 'a@example.com', NULL, NULL, NULL, '0911111111', NULL, '$2y$10$hash_user_a', 2, 0, NULL, 'active', NULL, '2025-10-31 14:14:22', '2025-10-31 14:14:22', NULL, NULL),
(9, 'Quân Trương M', 'tam@gmail.com', NULL, NULL, NULL, NULL, NULL, '$2y$12$VKxqZa8.JDuJyvfPScGre.ep.yTQNKIM7fTsH8yYaC7eLR3YadXrW', 1, 0, NULL, 'active', NULL, '2025-11-21 06:59:48', '2025-11-21 06:59:48', NULL, NULL),
(10, 'Nguyen Van A', 'duyhiha3@gmail.com', NULL, NULL, NULL, '0852855899', 'Đông Quang\r\nBa Vì', '$2y$12$SKtasikkK1ayU0ysN4746uDNfMGi0XoqOi9L4SVkWG5Qf5ZFOOPEW', 2, 0, '2025-12-17 07:28:36', 'active', NULL, '2025-12-17 07:28:04', '2025-12-19 01:28:30', NULL, NULL),
(11, 'quanm9677', 'quanm9677@gmail.com', NULL, NULL, NULL, NULL, NULL, '$2y$12$X5eCnmSOF9Enb5t7mLGAIOTAlnsiM4YTUN/uZVAqcnaWqoQPLDYx6', 2, 0, '2025-12-18 00:04:03', 'active', NULL, '2025-12-18 00:03:14', '2025-12-18 00:04:03', NULL, NULL),
(12, 'Đặng Duy', 'duyddph52543@gmail.com', NULL, NULL, NULL, NULL, NULL, '$2y$12$N2opTEwaRe0SfzfRTeT2r.zVyOAuqVH29gtRrYtTVj7jLqFUgwh9K', 2, 0, NULL, 'active', NULL, '2025-12-19 11:07:45', '2025-12-19 11:07:45', 'S0u8jXuU3FXkfCOfoqbdnM9KsfbtM3xukHC5ggqOrnKdl9qBzRIXErGDG8ITc51V', '2025-12-20 18:07:45'),
(13, 'Phạm Huân', 'huan@gmail.com', NULL, NULL, NULL, NULL, NULL, '$2y$12$PD/WTn/iIEaqZOeynkQ24uiPWa7lro7KQV2Zlvykwe/zKzNjGHkNm', 1, 0, NULL, 'active', NULL, '2025-12-20 09:23:03', '2025-12-20 09:23:03', NULL, NULL),
(14, 'Huân', 'huanpvph52348@gmail.com', NULL, NULL, NULL, NULL, NULL, '$2y$12$/03lnZ43.I5OfEQXgqC.JuHuebUD7VemlJevKMdjOUtLtYcagKVTi', 2, 0, NULL, 'active', NULL, '2025-12-20 13:52:48', '2025-12-22 00:17:19', NULL, NULL);

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
(8, 9, 5, '2025-12-16 02:33:09', '2025-12-16 02:33:09'),
(11, 14, 13, '2025-12-22 04:51:04', '2025-12-22 04:51:04');

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
-- Indexes for table `cache`
--
ALTER TABLE `cache`
  ADD PRIMARY KEY (`key`);

--
-- Indexes for table `cache_locks`
--
ALTER TABLE `cache_locks`
  ADD PRIMARY KEY (`key`);

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
-- Indexes for table `coupon_product`
--
ALTER TABLE `coupon_product`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `coupon_product_coupon_id_product_id_unique` (`coupon_id`,`product_id`),
  ADD KEY `coupon_product_product_id_foreign` (`product_id`);

--
-- Indexes for table `coupon_usages`
--
ALTER TABLE `coupon_usages`
  ADD PRIMARY KEY (`id`),
  ADD KEY `coupon_usages_coupon_id_user_id_index` (`coupon_id`,`user_id`),
  ADD KEY `coupon_usages_order_id_index` (`order_id`),
  ADD KEY `coupon_usages_used_at_index` (`used_at`);

--
-- Indexes for table `coupon_user`
--
ALTER TABLE `coupon_user`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indexes for table `inventory_logs`
--
ALTER TABLE `inventory_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_il_wh` (`warehouse_id`),
  ADD KEY `fk_il_variant` (`product_variant_id`);

--
-- Indexes for table `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jobs_queue_index` (`queue`);

--
-- Indexes for table `job_batches`
--
ALTER TABLE `job_batches`
  ADD PRIMARY KEY (`id`);

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
  ADD UNIQUE KEY `return_code` (`return_code`),
  ADD KEY `fk_returns_order` (`order_id`),
  ADD KEY `fk_returns_admin` (`refunded_by`);

--
-- Indexes for table `order_return_images`
--
ALTER TABLE `order_return_images`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_return_images_parent` (`order_return_id`);

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
  MODIFY `id` bigint NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=83;

--
-- AUTO_INCREMENT for table `cart_items`
--
ALTER TABLE `cart_items`
  MODIFY `id` bigint NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=106;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` bigint NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `colors`
--
ALTER TABLE `colors`
  MODIFY `id` bigint NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `comments`
--
ALTER TABLE `comments`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=41;

--
-- AUTO_INCREMENT for table `coupons`
--
ALTER TABLE `coupons`
  MODIFY `id` bigint NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `coupon_product`
--
ALTER TABLE `coupon_product`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `coupon_usages`
--
ALTER TABLE `coupon_usages`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `coupon_user`
--
ALTER TABLE `coupon_user`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `inventory_logs`
--
ALTER TABLE `inventory_logs`
  MODIFY `id` bigint NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `order_items`
--
ALTER TABLE `order_items`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=91;

--
-- AUTO_INCREMENT for table `order_returns`
--
ALTER TABLE `order_returns`
  MODIFY `id` bigint NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `order_return_images`
--
ALTER TABLE `order_return_images`
  MODIFY `id` bigint NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `payments`
--
ALTER TABLE `payments`
  MODIFY `id` bigint NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` bigint NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `product_images`
--
ALTER TABLE `product_images`
  MODIFY `id` bigint NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT for table `product_variants`
--
ALTER TABLE `product_variants`
  MODIFY `id` bigint NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

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
  MODIFY `id` bigint NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

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
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

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
-- Constraints for table `coupon_product`
--
ALTER TABLE `coupon_product`
  ADD CONSTRAINT `coupon_product_coupon_id_foreign` FOREIGN KEY (`coupon_id`) REFERENCES `coupons` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `coupon_product_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `coupon_usages`
--
ALTER TABLE `coupon_usages`
  ADD CONSTRAINT `coupon_usages_coupon_id_foreign` FOREIGN KEY (`coupon_id`) REFERENCES `coupons` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `inventory_logs`
--
ALTER TABLE `inventory_logs`
  ADD CONSTRAINT `fk_il_variant` FOREIGN KEY (`product_variant_id`) REFERENCES `product_variants` (`id`),
  ADD CONSTRAINT `fk_il_wh` FOREIGN KEY (`warehouse_id`) REFERENCES `warehouses` (`id`);

--
-- Constraints for table `order_returns`
--
ALTER TABLE `order_returns`
  ADD CONSTRAINT `fk_returns_admin` FOREIGN KEY (`refunded_by`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `fk_returns_order` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `order_return_images`
--
ALTER TABLE `order_return_images`
  ADD CONSTRAINT `fk_return_images_parent` FOREIGN KEY (`order_return_id`) REFERENCES `order_returns` (`id`) ON DELETE CASCADE;

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
