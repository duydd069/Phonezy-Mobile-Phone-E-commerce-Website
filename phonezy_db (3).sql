-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Nov 28, 2025 at 07:40 AM
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
-- Table structure for table `attributes`
--

CREATE TABLE `attributes` (
  `id` bigint NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `slug` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

--
-- Dumping data for table `attributes`
--

INSERT INTO `attributes` (`id`, `name`, `slug`, `created_at`, `updated_at`) VALUES
(1, 'Màu sắc', 'color', '2025-11-07 08:09:49', '2025-11-07 08:09:49'),
(2, 'Dung lượng', 'storage', '2025-11-07 08:09:49', '2025-11-07 08:09:49');

-- --------------------------------------------------------

--
-- Table structure for table `attribute_values`
--

CREATE TABLE `attribute_values` (
  `id` bigint NOT NULL,
  `attribute_id` bigint DEFAULT NULL,
  `value` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

--
-- Dumping data for table `attribute_values`
--

INSERT INTO `attribute_values` (`id`, `attribute_id`, `value`, `created_at`, `updated_at`) VALUES
(1, 1, 'Titan Tự Nhiên', '2025-11-07 08:09:49', '2025-11-07 08:09:49'),
(2, 1, 'Titan Xanh', '2025-11-07 08:09:49', '2025-11-07 08:09:49'),
(3, 2, '256GB', '2025-11-07 08:09:49', '2025-11-07 08:09:49'),
(4, 2, '512GB', '2025-11-07 08:09:49', '2025-11-07 08:09:49');

-- --------------------------------------------------------

--
-- Table structure for table `brands`
--

CREATE TABLE `brands` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(120) COLLATE utf8mb4_unicode_ci NOT NULL,
  `logo` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `slug` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `brands`
--

INSERT INTO `brands` (`id`, `name`, `logo`, `slug`, `created_at`, `updated_at`) VALUES
(1, 'Apple', 'https://example.com/apple.png', 'apple', '2025-11-01 21:10:02', '2025-11-01 21:10:02'),
(2, 'Samsung', 'https://example.com/samsung.png', 'samsung', '2025-11-01 21:10:02', '2025-11-01 21:10:02'),
(3, 'Xiaomi', 'https://example.com/xiaomi.png', 'xiaomi', '2025-11-01 21:10:02', '2025-11-01 21:10:02'),
(4, 'OPPO', 'https://example.com/oppo.png', 'oppo', '2025-11-01 21:10:02', '2025-11-01 21:10:02');

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
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `status` enum('active','abandoned','converted') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'active',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `carts`
--

INSERT INTO `carts` (`id`, `user_id`, `status`, `created_at`, `updated_at`) VALUES
(2, 1, 'converted', '2025-11-20 10:14:21', '2025-11-20 10:16:01'),
(3, 2, 'converted', '2025-11-20 10:17:55', '2025-11-20 10:18:14'),
(4, 2, 'converted', '2025-11-20 10:18:18', '2025-11-20 22:51:45'),
(5, 1, 'converted', '2025-11-20 22:50:50', '2025-11-20 22:51:06'),
(6, 2, 'converted', '2025-11-21 06:37:45', '2025-11-21 06:37:57'),
(7, 1, 'converted', '2025-11-21 06:48:18', '2025-11-21 07:26:00'),
(8, 2, 'active', '2025-11-21 06:49:38', '2025-11-21 06:49:38'),
(9, 1, 'converted', '2025-11-21 07:26:17', '2025-11-21 07:27:11'),
(10, 3, 'active', '2025-11-23 01:43:50', '2025-11-23 01:43:50');

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `cart_items`
--

INSERT INTO `cart_items` (`id`, `cart_id`, `product_variant_id`, `quantity`, `created_at`, `updated_at`) VALUES
(1, 1, 5, 2, '2025-11-01 21:10:02', '2025-11-01 21:10:02'),
(2, 1, 8, 1, '2025-11-01 21:10:02', '2025-11-01 21:10:02'),
(10, 8, 4, 2, '2025-11-21 06:49:38', '2025-11-21 07:04:38'),
(14, 10, 4, 1, '2025-11-23 01:43:50', '2025-11-23 01:43:50');

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `name`, `slug`, `created_at`, `updated_at`) VALUES
(1, 'Điện thoại', 'dien-thoai', '2025-11-01 21:10:02', '2025-11-01 21:10:02'),
(2, 'Máy tính bảng', 'may-tinh-bang', '2025-11-01 21:10:02', '2025-11-01 21:10:02'),
(3, 'Phụ kiện', 'phu-kien', '2025-11-01 21:10:02', '2025-11-01 21:10:02'),
(4, 'Âm thanh', 'am-thanh', '2025-11-01 21:10:02', '2025-11-01 21:10:02'),
(5, 'Đồng hồ', 'dong-ho', '2025-11-01 21:10:02', '2025-11-01 21:10:02');

-- --------------------------------------------------------

--
-- Table structure for table `comments`
--

CREATE TABLE `comments` (
  `id` bigint UNSIGNED NOT NULL,
  `product_id` int UNSIGNED NOT NULL,
  `user_id` int UNSIGNED NOT NULL,
  `parent_id` bigint UNSIGNED DEFAULT NULL,
  `content` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `coupons`
--

CREATE TABLE `coupons` (
  `id` bigint NOT NULL,
  `code` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `discount_type` enum('percent','fixed') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `discount_value` decimal(10,2) NOT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `coupons`
--

INSERT INTO `coupons` (`id`, `code`, `discount_type`, `discount_value`, `expires_at`, `created_at`, `updated_at`) VALUES
(1, 'NEW10', 'percent', '10.00', '2025-12-01 21:10:02', '2025-11-01 21:10:02', '2025-11-01 21:10:02'),
(2, 'SALE100K', 'fixed', '100000.00', '2025-11-16 21:10:02', '2025-11-01 21:10:02', '2025-11-01 21:10:02');

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
  `type` enum('stock_in','stock_out') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `reason` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `inventory_logs`
--

INSERT INTO `inventory_logs` (`id`, `warehouse_id`, `product_variant_id`, `quantity_change`, `type`, `reason`, `created_at`, `updated_at`) VALUES
(1, 1, 1, 10, 'stock_in', 'Nhập đầu kỳ', '2025-11-01 21:10:02', '2025-11-01 21:10:02'),
(2, 1, 2, 8, 'stock_in', 'Nhập đầu kỳ', '2025-11-01 21:10:02', '2025-11-01 21:10:02'),
(3, 1, 3, 15, 'stock_in', 'Nhập đầu kỳ', '2025-11-01 21:10:02', '2025-11-01 21:10:02'),
(4, 2, 4, 12, 'stock_in', 'Nhập đầu kỳ', '2025-11-01 21:10:02', '2025-11-01 21:10:02'),
(5, 2, 6, 9, 'stock_in', 'Nhập đầu kỳ', '2025-11-01 21:10:02', '2025-11-01 21:10:02'),
(6, 1, 1, -2, 'stock_out', 'Bán lẻ đơn #1', '2025-11-01 21:10:02', '2025-11-01 21:10:02');

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
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '0001_01_01_000000_create_users_table', 1),
(2, '0001_01_01_000001_create_cache_table', 1),
(3, '0001_01_01_000002_create_jobs_table', 1),
(4, '2025_11_04_000000_create_brands_table', 1),
(5, '2025_11_06_000010_create_sessions_table', 1),
(6, '2025_11_06_000011_add_password_to_users_table', 1),
(7, '2025_11_07_081054_create_categories_table', 1),
(8, '2025_11_20_000050_create_carts_table', 1),
(9, '2025_11_20_000100_create_orders_table', 1),
(10, '2025_11_20_000061_add_role_id_to_users_table', 2),
(11, '2025_11_13_000000_add_image_to_product_variants_table', 3),
(12, '2025_11_20_000000_create_comments_table', 1),
(13, '2025_11_20_000101_create_order_items_table', 1);

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` bigint UNSIGNED NOT NULL,
  `cart_id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `subtotal` decimal(12,2) NOT NULL,
  `shipping_fee` decimal(12,2) NOT NULL DEFAULT '0.00',
  `discount_amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `total` decimal(12,2) NOT NULL,
  `payment_method` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payment_status` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `status` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `shipping_full_name` varchar(120) COLLATE utf8mb4_unicode_ci NOT NULL,
  `shipping_email` varchar(150) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `shipping_phone` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  `shipping_city` varchar(120) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `shipping_district` varchar(120) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `shipping_ward` varchar(120) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `shipping_address` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `notes` text COLLATE utf8mb4_unicode_ci,
  `paid_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `cart_id`, `user_id`, `subtotal`, `shipping_fee`, `discount_amount`, `total`, `payment_method`, `payment_status`, `status`, `shipping_full_name`, `shipping_email`, `shipping_phone`, `shipping_city`, `shipping_district`, `shipping_ward`, `shipping_address`, `notes`, `paid_at`, `created_at`, `updated_at`) VALUES
(1, 2, 1, '22980000.00', '30000.00', '0.00', '23010000.00', 'cod', 'pending', 'pending', 'Thanh Tú', 'tuantu091005@gmail.com', '0326857005', 'Hà Nội', 'hihi', 'haha', 'NGÕ 72 HOA BẰNG, CẦU GIẤY, HÀ NỘI', 'sdzvzdv', NULL, '2025-11-20 10:16:01', '2025-11-20 10:16:01'),
(2, 3, 2, '16990000.00', '30000.00', '0.00', '17020000.00', 'bank_transfer', 'pending', 'pending', 'Thanh Tú', 'tuantu091005@gmail.com', '0326857005', 'Hà Nội', 'hihi', 'haha', 'NGÕ 72 HOA BẰNG, CẦU GIẤY, HÀ NỘI', 'hihohohohh', NULL, '2025-11-20 10:18:14', '2025-11-20 10:18:14'),
(3, 5, 1, '16990000.00', '30000.00', '0.00', '17020000.00', 'cod', 'pending', 'pending', 'Thanh Tú', 'tuantu091005@gmail.com', '0326857005', 'Hà Nội', 'hihi', 'haha', 'NGÕ 72 HOA BẰNG, CẦU GIẤY, HÀ NỘI', 'srgsre', NULL, '2025-11-20 22:51:06', '2025-11-20 22:51:06'),
(4, 4, 2, '12990000.00', '30000.00', '0.00', '13020000.00', 'cod', 'pending', 'pending', 'Thanh Tú', 'tuantu091005@gmail.com', '0326857005', 'Hà Nội', 'hihi', 'haha', 'NGÕ 72 HOA BẰNG, CẦU GIẤY, HÀ NỘI', 'dfasfsf', NULL, '2025-11-20 22:51:45', '2025-11-20 22:51:45'),
(5, 6, 2, '12990000.00', '30000.00', '0.00', '13020000.00', 'cod', 'pending', 'processing', 'Thanh Tú', 'tuantu091005@gmail.com', '0326857005', 'Hà Nội', 'hihi', 'haha', 'NGÕ 72 HOA BẰNG, CẦU GIẤY, HÀ NỘI', 'sdfadfad', NULL, '2025-11-21 06:37:57', '2025-11-21 06:50:45'),
(6, 7, 1, '33980000.00', '30000.00', '0.00', '34010000.00', 'cod', 'pending', 'pending', 'Thanh Tú', 'hhh@gmail.com', '0326857005', 'Hà Nội', 'hihi', 'haha', 'NGÕ 72 HOA BẰNG, CẦU GIẤY, HÀ NỘI', 'dfdadfasd', NULL, '2025-11-21 07:26:00', '2025-11-21 07:26:00'),
(7, 9, 1, '31970000.00', '30000.00', '0.00', '32000000.00', 'cod', 'pending', 'pending', 'Thanh Tú', 'tuantu091005@gmail.com', '0326857005', 'Hà Nội', 'ffffff', 'ffffffff', 'NGÕ 72 HOA BẰNG, CẦU GIẤY, HÀ NỘI', 'fffffffff', NULL, '2025-11-21 07:27:11', '2025-11-21 07:27:11');

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
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `order_items`
--

INSERT INTO `order_items` (`id`, `order_id`, `product_id`, `product_name`, `product_image`, `quantity`, `unit_price`, `total_price`, `created_at`, `updated_at`) VALUES
(1, 1, 3, 'Xiaomi Redmi Note 13', 'https://example.com/rn13.jpg', 1, '5990000.00', '5990000.00', '2025-11-20 10:16:01', '2025-11-20 10:16:01'),
(2, 1, 5, 'iPad Air M2 11\"', 'https://example.com/ipadair-m2.jpg', 1, '16990000.00', '16990000.00', '2025-11-20 10:16:01', '2025-11-20 10:16:01'),
(3, 2, 5, 'iPad Air M2 11\"', 'https://example.com/ipadair-m2.jpg', 1, '16990000.00', '16990000.00', '2025-11-20 10:18:14', '2025-11-20 10:18:14'),
(4, 3, 5, 'iPad Air M2 11\"', 'https://example.com/ipadair-m2.jpg', 1, '16990000.00', '16990000.00', '2025-11-20 22:51:06', '2025-11-20 22:51:06'),
(5, 4, 4, 'OPPO Reno 12', 'https://example.com/reno12.jpg', 1, '12990000.00', '12990000.00', '2025-11-20 22:51:45', '2025-11-20 22:51:45'),
(6, 5, 4, 'OPPO Reno 12', 'https://example.com/reno12.jpg', 1, '12990000.00', '12990000.00', '2025-11-21 06:37:57', '2025-11-21 06:37:57'),
(7, 6, 4, 'OPPO Reno 12', 'https://example.com/reno12.jpg', 1, '12990000.00', '12990000.00', '2025-11-21 07:26:00', '2025-11-21 07:26:00'),
(8, 6, 2, 'Samsung Galaxy S24 256GB', 'https://example.com/s24.jpg', 1, '20990000.00', '20990000.00', '2025-11-21 07:26:00', '2025-11-21 07:26:00'),
(9, 7, 4, 'OPPO Reno 12', 'https://example.com/reno12.jpg', 2, '12990000.00', '25980000.00', '2025-11-21 07:27:11', '2025-11-21 07:27:11'),
(10, 7, 3, 'Xiaomi Redmi Note 13', 'https://example.com/rn13.jpg', 1, '5990000.00', '5990000.00', '2025-11-21 07:27:11', '2025-11-21 07:27:11');

-- --------------------------------------------------------

--
-- Table structure for table `order_returns`
--

CREATE TABLE `order_returns` (
  `id` bigint NOT NULL,
  `order_item_id` bigint NOT NULL,
  `reason` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `status` enum('pending','approved','rejected') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT 'pending',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `order_returns`
--

INSERT INTO `order_returns` (`id`, `order_item_id`, `reason`, `status`, `created_at`, `updated_at`) VALUES
(1, 2, 'Hàng lỗi mic', 'pending', '2025-11-01 21:10:02', '2025-11-01 21:10:02');

-- --------------------------------------------------------

--
-- Table structure for table `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `payments`
--

CREATE TABLE `payments` (
  `id` bigint NOT NULL,
  `order_id` bigint NOT NULL,
  `payment_method` enum('cash','momo','vnpay') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `payment_status` enum('pending','paid','failed') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT 'pending',
  `paid_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `payments`
--

INSERT INTO `payments` (`id`, `order_id`, `payment_method`, `payment_status`, `paid_at`, `created_at`, `updated_at`) VALUES
(1, 1, 'momo', 'paid', '2025-11-01 21:10:02', '2025-11-01 21:10:02', '2025-11-01 21:10:02');

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` bigint NOT NULL,
  `name` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `image` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `price` decimal(12,0) NOT NULL,
  `slug` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `gender` enum('male','female','unisex') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT 'unisex',
  `category_id` bigint NOT NULL,
  `brand_id` bigint NOT NULL,
  `views` bigint DEFAULT '0',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `name`, `image`, `price`, `slug`, `description`, `gender`, `category_id`, `brand_id`, `views`, `created_at`, `updated_at`) VALUES
(1, 'iPhone 15 Pro 256GB', 'https://example.com/ip15pro.jpg', '27990000', 'iphone-15-pro-256gb', 'Flagship Apple', 'unisex', 1, 1, 122, '2025-11-01 21:10:02', '2025-11-21 06:53:21'),
(2, 'Samsung Galaxy S24 256GB', 'https://example.com/s24.jpg', '20990000', 'samsung-galaxy-s24-256gb', 'Flagship Samsung', 'unisex', 1, 2, 91, '2025-11-01 21:10:02', '2025-11-21 07:22:33'),
(3, 'Xiaomi Redmi Note 13', 'https://example.com/rn13.jpg', '5990000', 'xiaomi-redmi-note-13', 'Giá tốt', 'unisex', 1, 3, 80, '2025-11-01 21:10:02', '2025-11-01 21:10:02'),
(4, 'OPPO Reno 12', 'https://example.com/reno12.jpg', '12990000', 'oppo-reno-12', 'Thiết kế đẹp', 'unisex', 1, 4, 67, '2025-11-01 21:10:02', '2025-11-22 02:03:12'),
(5, 'iPad Air M2 11\"', 'https://example.com/ipadair-m2.jpg', '16990000', 'ipad-air-m2-11', 'iPad Air chip M2', 'unisex', 2, 1, 40, '2025-11-01 21:10:02', '2025-11-01 21:10:02'),
(6, 'AirPods Pro 2 USB‑C', 'https://example.com/airpodspro2.jpg', '5290000', 'airpods-pro-2', 'Tai nghe chống ồn', 'unisex', 4, 1, 154, '2025-11-01 21:10:02', '2025-11-23 01:42:52');

-- --------------------------------------------------------

--
-- Table structure for table `product_images`
--

CREATE TABLE `product_images` (
  `id` bigint NOT NULL,
  `product_id` bigint NOT NULL,
  `image_url` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `product_images`
--

INSERT INTO `product_images` (`id`, `product_id`, `image_url`, `created_at`, `updated_at`) VALUES
(1, 1, 'https://example.com/ip15pro_1.jpg', '2025-11-01 21:10:02', '2025-11-01 21:10:02'),
(2, 1, 'https://example.com/ip15pro_2.jpg', '2025-11-01 21:10:02', '2025-11-01 21:10:02'),
(3, 2, 'https://example.com/s24_1.jpg', '2025-11-01 21:10:02', '2025-11-01 21:10:02'),
(4, 3, 'https://example.com/rn13_1.jpg', '2025-11-01 21:10:02', '2025-11-01 21:10:02'),
(5, 4, 'https://example.com/reno12_1.jpg', '2025-11-01 21:10:02', '2025-11-01 21:10:02'),
(6, 5, 'https://example.com/ipadair_1.jpg', '2025-11-01 21:10:02', '2025-11-01 21:10:02'),
(7, 6, 'https://example.com/airpodspro2_1.jpg', '2025-11-01 21:10:02', '2025-11-01 21:10:02');

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
  `sku` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `barcode` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` enum('available','out_of_stock','discontinued') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT 'available',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `product_variants`
--

INSERT INTO `product_variants` (`id`, `product_id`, `price`, `price_sale`, `stock`, `sold`, `sku`, `barcode`, `description`, `image`, `status`, `created_at`, `updated_at`) VALUES
(1, 1, '27990000', '26990000', 20, 5, 'IP15P-256-NAT', '1111111111111', 'Titan tự nhiên 256GB', NULL, 'available', '2025-11-01 21:10:02', '2025-11-01 21:10:02'),
(2, 1, '27990000', NULL, 15, 2, 'IP15P-256-BLK', '1111111111112', 'Titan đen 256GB', NULL, 'available', '2025-11-01 21:10:02', '2025-11-01 21:10:02'),
(3, 2, '20990000', '19990000', 30, 10, 'S24-256-BLK', '2222222222221', 'Đen 256GB', NULL, 'available', '2025-11-01 21:10:02', '2025-11-01 21:10:02'),
(4, 2, '20990000', NULL, 25, 6, 'S24-256-VIO', '2222222222222', 'Tím 256GB', NULL, 'available', '2025-11-01 21:10:02', '2025-11-01 21:10:02'),
(5, 3, '5990000', '5490000', 40, 20, 'RN13-128-BLU', '3333333333331', 'Xanh 128GB', NULL, 'available', '2025-11-01 21:10:02', '2025-11-01 21:10:02'),
(6, 4, '12990000', NULL, 18, 4, 'RENO12-256-SLV', '4444444444441', 'Bạc 256GB', NULL, 'available', '2025-11-01 21:10:02', '2025-11-01 21:10:02'),
(7, 5, '16990000', NULL, 10, 1, 'IPAD-AIR-M2-11-128', '5555555555551', 'M2 11-inch 128GB', NULL, 'available', '2025-11-01 21:10:02', '2025-11-01 21:10:02'),
(8, 6, '5290000', '4990000', 50, 35, 'APPRO2-USBC', '6666666666661', 'AirPods Pro 2 USB-C', NULL, 'available', '2025-11-01 21:10:02', '2025-11-01 21:10:02');

-- --------------------------------------------------------

--
-- Table structure for table `product_variant_attribute_value`
--

CREATE TABLE `product_variant_attribute_value` (
  `id` bigint NOT NULL,
  `product_variant_id` bigint DEFAULT NULL,
  `attribute_id` bigint DEFAULT NULL,
  `attribute_value_id` bigint DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

--
-- Dumping data for table `product_variant_attribute_value`
--

INSERT INTO `product_variant_attribute_value` (`id`, `product_variant_id`, `attribute_id`, `attribute_value_id`, `created_at`, `updated_at`) VALUES
(1, 1, 1, 1, '2025-11-07 08:09:49', '2025-11-07 08:09:49'),
(2, 1, 2, 3, '2025-11-07 08:09:49', '2025-11-07 08:09:49'),
(3, 2, 1, 1, '2025-11-07 08:09:49', '2025-11-07 08:09:49'),
(4, 2, 2, 4, '2025-11-07 08:09:49', '2025-11-07 08:09:49');

-- --------------------------------------------------------

--
-- Table structure for table `reviews`
--

CREATE TABLE `reviews` (
  `id` bigint NOT NULL,
  `user_id` bigint DEFAULT NULL,
  `product_id` bigint DEFAULT NULL,
  `rating` int DEFAULT NULL,
  `comment` longtext CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

CREATE TABLE `roles` (
  `id` bigint NOT NULL,
  `name` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `roles`
--

INSERT INTO `roles` (`id`, `name`, `created_at`, `updated_at`) VALUES
(1, 'admin', '2025-11-01 21:10:02', '2025-11-01 21:10:02'),
(2, 'customer', '2025-11-01 21:10:02', '2025-11-01 21:10:02');

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
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` text COLLATE utf8mb4_unicode_ci,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_activity` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sessions`
--

INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
('1CS54WSAEMWzcp1YC1IsWrlyCClllFKWd0FemiD2', 3, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiQjVnOGQwQVlaUW9EaGZ3TDBhb0V6cUxKdWkwemIyWVlzZnNuNlJMUiI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6Mzc6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9jbGllbnQvY2hlY2tvdXQiO3M6NToicm91dGUiO3M6MTU6ImNsaWVudC5jaGVja291dCI7fXM6NTA6ImxvZ2luX3dlYl81OWJhMzZhZGRjMmIyZjk0MDE1ODBmMDE0YzdmNThlYTRlMzA5ODlkIjtpOjM7fQ==', 1763887434),
('5f4sES43NeMtKVAvNBYV02jn6rU6bOGA8XODwt3e', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoicjViTFhEd2tQTGhTRFlyTWE5ZlkzSG5FdHlPSW1BVTliS09ZanhmeiI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6Mjg6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9jbGllbnQiO3M6NToicm91dGUiO3M6MTI6ImNsaWVudC5pbmRleCI7fX0=', 1763735275),
('5JwNHq6iJGyNtKAwBfytNkXD8Omegv91k6C8fF3Y', 2, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoidE91b0RkTEJyZ1RuSksyc0NLWGRaaGtMUTVFWUl5ekZ0dEtrTXJRSiI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzQ6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9jbGllbnQvbG9naW4iO3M6NToicm91dGUiO3M6MTI6ImNsaWVudC5sb2dpbiI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fXM6NTA6ImxvZ2luX3dlYl81OWJhMzZhZGRjMmIyZjk0MDE1ODBmMDE0YzdmNThlYTRlMzA5ODlkIjtpOjI7fQ==', 1763884594),
('J1JWXdqGp2hZxedIjbGurhta4pSRZ5H8CvrvhCXH', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiZmdMU1Ftc21USThKU1d4TjNTZWdXWkR2bktsdzE0ck96MFp0ZkRlUyI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6NDM6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9jbGllbnQvcC9vcHBvLXJlbm8tMTIiO3M6NToicm91dGUiO3M6MTk6ImNsaWVudC5wcm9kdWN0LnNob3ciO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19', 1763803099);

-- --------------------------------------------------------

--
-- Table structure for table `shipping_methods`
--

CREATE TABLE `shipping_methods` (
  `id` bigint NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `price` decimal(10,2) DEFAULT NULL,
  `expected_delivery_date` date DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `shipping_tracking`
--

CREATE TABLE `shipping_tracking` (
  `id` bigint NOT NULL,
  `order_id` bigint NOT NULL,
  `status` enum('pending','shipped','in_transit','delivered','failed') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT 'pending',
  `tracking_number` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `shipped_at` timestamp NULL DEFAULT NULL,
  `delivered_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `shipping_tracking`
--

INSERT INTO `shipping_tracking` (`id`, `order_id`, `status`, `tracking_number`, `shipped_at`, `delivered_at`, `created_at`, `updated_at`) VALUES
(1, 1, 'shipped', 'VNPOST-ABC123', '2025-11-01 21:10:02', NULL, '2025-11-01 21:10:02', '2025-11-01 21:10:02');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password_hash` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `role_id` bigint UNSIGNED DEFAULT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password`, `email_verified_at`, `password_hash`, `role_id`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'Thanh Tú', 'hhh@gmail.com', NULL, NULL, '$2y$12$KEU2RJTe0ogguVlQ6t6LNei4ZAWMUOLRHjvtsMMZamAjeriIi6yNi', 1, NULL, '2025-11-20 10:13:09', '2025-11-20 10:13:09'),
(2, 'Thanh Tú', 'tuantu091005@gmail.com', NULL, NULL, '$2y$12$VXYeA/XVMgGHwzcavhclGu423dS9TU87Zk60wUKSl6NOUlF7WJsmK', 2, NULL, '2025-11-20 10:17:52', '2025-11-20 10:17:52'),
(3, 'Thanh Tú', 'tuantu09100505@gmail.com', NULL, NULL, '$2y$12$lXfVocFkdu50riUFsIV1Iec49BUNCvHs/xoBZNEyyYxRU69DqZjJK', 2, NULL, '2025-11-23 00:59:28', '2025-11-23 00:59:28');

-- --------------------------------------------------------

--
-- Table structure for table `variant_images`
--

CREATE TABLE `variant_images` (
  `id` bigint UNSIGNED NOT NULL,
  `variant_id` bigint UNSIGNED NOT NULL,
  `image_url` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Stand-in structure for view `v_product_stock`
-- (See below for the actual view)
--
CREATE TABLE `v_product_stock` (
);

-- --------------------------------------------------------

--
-- Structure for view `v_product_stock`
--
DROP TABLE IF EXISTS `v_product_stock`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_product_stock`  AS SELECT `pv`.`id` AS `variant_id`, `p`.`name` AS `product_name`, `pv`.`sku` AS `sku`, sum(`ws`.`stock_quantity`) AS `total_stock` FROM ((`product_variants` `pv` join `products` `p` on((`p`.`id` = `pv`.`product_id`))) left join `warehouse_stock` `ws` on((`ws`.`product_variant_id` = `pv`.`id`))) GROUP BY `pv`.`id`, `p`.`name`, `pv`.`sku` ;
--
-- Indexes for dumped tables
--

--
-- Indexes for table `brands`
--
ALTER TABLE `brands`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `brands_slug_unique` (`slug`);

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
  ADD KEY `carts_user_id_foreign` (`user_id`);

--
-- Indexes for table `cart_items`
--
ALTER TABLE `cart_items`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `categories_slug_unique` (`slug`);

--
-- Indexes for table `comments`
--
ALTER TABLE `comments`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

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
  ADD KEY `orders_cart_id_foreign` (`cart_id`),
  ADD KEY `orders_user_id_foreign` (`user_id`);

--
-- Indexes for table `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_items_order_id_foreign` (`order_id`);

--
-- Indexes for table `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- Indexes for table `variant_images`
--
ALTER TABLE `variant_images`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `brands`
--
ALTER TABLE `brands`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `carts`
--
ALTER TABLE `carts`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `cart_items`
--
ALTER TABLE `cart_items`
  MODIFY `id` bigint NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `comments`
--
ALTER TABLE `comments`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `order_items`
--
ALTER TABLE `order_items`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `variant_images`
--
ALTER TABLE `variant_images`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `carts`
--
ALTER TABLE `carts`
  ADD CONSTRAINT `carts_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_cart_id_foreign` FOREIGN KEY (`cart_id`) REFERENCES `carts` (`id`),
  ADD CONSTRAINT `orders_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `order_items`
--
ALTER TABLE `order_items`
  ADD CONSTRAINT `order_items_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
