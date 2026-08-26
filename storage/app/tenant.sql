-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Jan 23, 2025 at 10:55 AM
-- Server version: 10.4.24-MariaDB
-- PHP Version: 8.1.6

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `test`
--


-- --------------------------------------------------------


--
-- Table structure for table `activity_log`
--

CREATE TABLE `activity_log` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `log_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `subject_type` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `event` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `subject_id` bigint(20) UNSIGNED DEFAULT NULL,
  `causer_type` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `causer_id` bigint(20) UNSIGNED DEFAULT NULL,
  `properties` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL,
  `batch_uuid` char(36) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ip` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `os` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `browser` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `url` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `admin_login_activity_log`
--

CREATE TABLE `admin_login_activity_log` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `login_at` timestamp NULL DEFAULT NULL,
  `logout_at` timestamp NULL DEFAULT NULL,
  `ip` text DEFAULT NULL,
  `os` text DEFAULT NULL,
  `browser` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uuid` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `jobs`
--

CREATE TABLE `jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `queue` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `attempts` tinyint(3) UNSIGNED NOT NULL,
  `reserved_at` int(10) UNSIGNED DEFAULT NULL,
  `available_at` int(10) UNSIGNED NOT NULL,
  `created_at` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `job_batches`
--

CREATE TABLE `job_batches` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `total_jobs` int(11) NOT NULL,
  `pending_jobs` int(11) NOT NULL,
  `failed_jobs` int(11) NOT NULL,
  `failed_job_ids` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `options` mediumtext COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `cancelled_at` int(11) DEFAULT NULL,
  `created_at` int(11) NOT NULL,
  `finished_at` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `model_has_permissions`
--

CREATE TABLE `model_has_permissions` (
  `permission_id` bigint(20) UNSIGNED NOT NULL,
  `model_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `model_id` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `model_has_roles`
--

CREATE TABLE `model_has_roles` (
  `role_id` bigint(20) UNSIGNED NOT NULL,
  `model_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `model_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Dumping data for table `model_has_roles`
--

INSERT INTO `model_has_roles` (`role_id`, `model_type`, `model_id`) VALUES
(4, 'Core\\Models\\User', 1);

-- --------------------------------------------------------
--
-- Table structure for table `notifications`
--

CREATE TABLE `notifications` (
  `id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `notifiable_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `notifiable_id` bigint(20) UNSIGNED NOT NULL,
  `data` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `read_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `notifications`
--

INSERT INTO `notifications` (`id`, `type`, `notifiable_type`, `notifiable_id`, `data`, `read_at`, `created_at`, `updated_at`) VALUES
('9b3ae26e-a3dc-4b3c-84b1-900485583d87', 'Plugin\\TlcommerceCore\\Notifications\\CustomerOrderCreateNotification', 'Core\\Models\\User', 1, '{\"message\":\"New order\",\"link\":\"\\/orders\\/order-details\\/1\"}', NULL, '2023-03-12 18:27:58', '2023-03-12 18:27:58'),
('ec2f904c-0902-42d7-986e-27af6cdb063b', 'Plugin\\TlcommerceCore\\Notifications\\CustomerOrderCreateNotification', 'Core\\Models\\User', 3, '{\"message\":\"New order\",\"link\":\"\\/orders\\/order-details\\/1\"}', NULL, '2023-03-12 18:27:58', '2023-03-12 18:27:58'),
('f14f37e5-a5aa-4c1f-bed6-b2f64ea1a0bd', 'Plugin\\TlcommerceCore\\Notifications\\CustomerOrderCreateNotification', 'Core\\Models\\User', 2, '{\"message\":\"New order\",\"link\":\"\\/orders\\/order-details\\/1\"}', NULL, '2023-03-12 18:27:58', '2023-03-12 18:27:58');

-- --------------------------------------------------------

--
-- Table structure for table `page_builder_sections`
--

CREATE TABLE `page_builder_sections` (
  `id` int(11) NOT NULL,
  `page_id` bigint(20) UNSIGNED NOT NULL,
  `theme_id` int(11) DEFAULT NULL,
  `ordering` int(11) NOT NULL DEFAULT 0,
  `status` int(11) NOT NULL DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `page_builder_sections_layout_widget_properties`
--

CREATE TABLE `page_builder_sections_layout_widget_properties` (
  `id` int(11) NOT NULL,
  `layout_has_widget_id` int(11) DEFAULT NULL,
  `properties` longtext DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `page_builder_sections_properties`
--

CREATE TABLE `page_builder_sections_properties` (
  `id` int(11) NOT NULL,
  `section_id` int(11) DEFAULT NULL,
  `properties` longtext DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `page_builder_section_layouts`
--

CREATE TABLE `page_builder_section_layouts` (
  `id` int(11) NOT NULL,
  `section_id` int(11) DEFAULT NULL,
  `col_index` int(3) DEFAULT NULL,
  `col_value` int(3) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `page_builder_section_layout_widgets`
--

CREATE TABLE `page_builder_section_layout_widgets` (
  `id` int(11) NOT NULL,
  `section_layout_id` int(11) DEFAULT NULL,
  `page_widget_id` int(11) DEFAULT NULL,
  `serial` int(11) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `page_builder_widgets`
--

CREATE TABLE `page_builder_widgets` (
  `id` int(11) NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `full_name` varchar(255) DEFAULT NULL,
  `theme_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `page_builder_widget_translations`
--

CREATE TABLE `page_builder_widget_translations` (
  `id` int(11) NOT NULL,
  `lang` varchar(150) DEFAULT NULL,
  `layout_widget_properties_id` int(11) DEFAULT NULL,
  `properties` longtext DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `password_resets`
--

CREATE TABLE `password_resets` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `password_resets`
--

INSERT INTO `password_resets` (`email`, `token`, `created_at`) VALUES
('admin@example.com', 'lsgpOmR0hXGsKD14p8x8HQS3hw5IE88ow5BUmAgJl8xfNuhfajUCIhUzwG9vae2z', '2023-03-11 14:34:39');

-- --------------------------------------------------------

--
-- Table structure for table `permissions`
--

CREATE TABLE `permissions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `module_id` int(11) NOT NULL DEFAULT 0,
  `guard_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `permissions`
--

INSERT INTO `permissions` (`id`, `name`, `module_id`, `guard_name`, `created_at`, `updated_at`) VALUES
(3, 'Create Role', 1, 'web', '2022-06-01 03:36:18', '2022-06-01 03:36:18'),
(4, 'Edit Role', 1, 'web', '2022-06-01 03:36:26', '2022-06-01 03:36:26'),
(5, 'Delete Role', 1, 'web', '2022-06-01 03:36:28', '2022-06-01 03:41:47'),
(10, 'Show Role', 1, 'web', '2023-01-15 00:09:02', '2023-01-15 00:41:11'),
(11, 'Create User', 2, 'web', '2023-01-15 00:09:31', '2023-01-15 00:09:31'),
(12, 'Edit User', 2, 'web', '2023-01-15 00:09:47', '2023-01-15 00:09:47'),
(13, 'Delete User', 2, 'web', '2023-01-15 00:09:55', '2023-01-15 00:09:55'),
(14, 'Show User', 2, 'web', '2023-01-15 00:10:03', '2023-01-15 00:10:03'),
(15, 'Show Permission', 3, 'web', '2023-01-15 00:41:27', '2023-01-15 00:41:35'),
(17, 'Manage Menus', 8, 'web', '2023-01-17 05:18:11', '2023-01-17 05:18:11'),
(18, 'Manage Media', 5, 'web', '2023-01-15 02:09:20', '2023-01-15 02:09:20'),
(20, 'Manage Themes', 9, 'web', '2023-01-17 05:21:51', '2023-01-17 05:21:51'),
(25, 'Create Blog', 19, 'web', '2023-01-17 22:04:30', '2023-01-17 22:04:30'),
(26, 'Show Blog', 19, 'web', '2023-01-17 22:05:43', '2023-01-17 22:05:43'),
(27, 'Edit Blog', 19, 'web', '2023-01-17 22:06:31', '2023-01-17 22:06:31'),
(28, 'Delete Blog', 19, 'web', '2023-01-17 22:06:46', '2023-01-17 22:06:46'),
(29, 'Manage Category', 20, 'web', '2023-01-17 22:07:25', '2023-01-17 22:07:25'),
(30, 'Manage Tag', 21, 'web', '2023-01-17 22:07:44', '2023-01-17 22:07:44'),
(31, 'Manage Comment', 22, 'web', '2023-01-17 22:08:21', '2023-01-17 22:08:21'),
(32, 'Create Page', 23, 'web', '2023-01-17 22:16:45', '2023-01-17 22:16:45'),
(33, 'Show Page', 23, 'web', '2023-01-17 22:17:26', '2023-01-17 22:17:26'),
(34, 'Edit Page', 23, 'web', '2023-01-17 22:17:44', '2023-01-17 22:17:44'),
(35, 'Delete Page', 23, 'web', '2023-01-17 22:18:03', '2023-01-17 22:18:03'),
(36, 'Manage Widget', 24, 'web', '2023-01-19 05:46:41', '2023-01-19 05:46:41'),
(41, 'Manage Dashboard', 28, 'web', '2023-01-25 22:53:11', '2023-01-25 22:53:11'),
(43, 'Manage Home Page Builder', 30, 'web', '2023-01-25 23:27:20', '2023-01-25 23:27:20'),
(44, 'Manage Slider Settings', 31, 'web', '2023-01-25 23:28:26', '2023-01-25 23:28:26'),
(45, 'Manage Plugins', 32, 'web', '2023-01-25 23:42:52', '2023-01-25 23:42:52'),
(46, 'Manage General Settings', 33, 'web', '2023-01-25 23:53:23', '2023-01-25 23:53:23'),
(47, 'Manage Email Settings', 34, 'web', '2023-01-25 23:54:26', '2023-01-25 23:54:26'),
(48, 'Manage Email Templates', 35, 'web', '2023-01-25 23:55:17', '2023-01-25 23:55:17'),
(49, 'Manage Language', 36, 'web', '2023-01-25 23:56:03', '2023-01-25 23:56:03'),
(50, 'Manage Media Settings', 37, 'web', '2023-01-25 23:56:31', '2023-01-25 23:56:31'),
(51, 'Manage Seo Settings', 38, 'web', '2023-01-25 23:57:09', '2023-01-25 23:57:09'),
(52, 'Manage Theme Translations', 39, 'web', '2023-01-25 23:58:01', '2023-01-25 23:58:01'),
(53, 'Manage Inhouse Orders', 40, 'web', '2023-01-26 02:30:05', '2023-01-26 02:30:05'),
(54, 'Manage Pickup Point Order', 41, 'web', '2023-01-26 02:31:26', '2023-01-26 02:31:26'),
(58, 'Manage Customers', 42, 'web', '2023-01-26 03:47:23', '2023-01-26 03:47:23'),
(59, 'Manage Payment Methods', 43, 'web', '2023-01-26 03:52:40', '2023-01-26 03:52:40'),
(60, 'Manage Transaction history', 44, 'web', '2023-01-26 03:53:23', '2023-01-26 03:53:23'),
(61, 'Manage Product Reports', 45, 'web', '2023-01-26 04:03:18', '2023-01-26 04:03:18'),
(62, 'Manage Keyword Search Reports', 46, 'web', '2023-01-26 04:04:19', '2023-01-26 04:04:19'),
(63, 'Manage Wishlist Reports', 48, 'web', '2023-01-26 04:06:37', '2023-01-26 04:06:37'),
(64, 'Manage Taxes', 49, 'web', '2023-01-27 23:05:34', '2023-01-27 23:05:34'),
(65, 'Manage Settings', 50, 'web', '2023-01-27 23:06:05', '2023-01-27 23:06:05'),
(66, 'Manage Currencies', 51, 'web', '2023-01-27 23:07:03', '2023-01-27 23:07:03'),
(67, 'Manage Product Share Options', 52, 'web', '2023-01-27 23:08:16', '2023-01-27 23:08:16'),
(68, 'Manage Wallet Transactions', 53, 'web', '2023-01-27 23:25:38', '2023-01-27 23:25:38'),
(69, 'Manage Offline Payment Methods', 54, 'web', '2023-01-27 23:26:15', '2023-01-27 23:26:15'),
(70, 'Manage Refund Requests', 55, 'web', '2023-01-27 23:30:07', '2023-01-27 23:30:07'),
(71, 'Manage Refund reasons', 56, 'web', '2023-01-27 23:31:00', '2023-01-27 23:31:00'),
(72, 'Manage Login activity', 57, 'web', '2023-01-27 23:33:54', '2023-01-27 23:33:54'),
(86, 'Manage Shipping & Delivery', 66, 'web', '2023-01-28 00:49:14', '2023-01-28 00:49:14'),
(87, 'Manage Pickup Points', 67, 'web', '2023-01-28 00:49:52', '2023-01-28 00:49:52'),
(88, 'Manage Carriers', 68, 'web', '2023-01-28 00:50:29', '2023-01-28 00:50:29'),
(89, 'Manage Locations', 69, 'web', '2023-01-28 00:51:12', '2023-01-28 00:51:12'),
(90, 'Manage Flash Deals', 70, 'web', '2023-01-28 02:18:09', '2023-01-28 02:18:09'),
(91, 'Manage Coupons', 71, 'web', '2023-01-28 02:20:35', '2023-01-28 02:20:35'),
(92, 'Manage Custom notification', 72, 'web', '2023-01-28 02:21:24', '2023-01-28 02:21:24'),
(93, 'Manage Add New Product', 73, 'web', '2023-01-28 02:43:04', '2023-01-28 02:43:04'),
(94, 'Manage Inhouse Products', 74, 'web', '2023-01-28 02:43:41', '2023-01-28 02:43:41'),
(95, 'Manage Colors', 75, 'web', '2023-01-28 02:44:20', '2023-01-28 02:44:20'),
(96, 'Manage Brands', 76, 'web', '2023-01-28 02:44:45', '2023-01-28 02:44:45'),
(97, 'Manage Categories', 77, 'web', '2023-01-28 02:46:04', '2023-01-28 02:46:04'),
(98, 'Manage Attributes', 78, 'web', '2023-01-28 02:46:54', '2023-01-28 02:46:54'),
(99, 'Manage Units', 79, 'web', '2023-01-28 02:47:26', '2023-01-28 02:47:26'),
(100, 'Manage Product Reviews', 80, 'web', '2023-01-28 02:48:37', '2023-01-28 02:48:37'),
(101, 'Manage Product collections', 81, 'web', '2023-01-28 02:49:28', '2023-01-28 02:49:28'),
(102, 'Manage Product Tags', 82, 'web', '2023-01-28 02:50:38', '2023-01-28 02:50:38'),
(103, 'Manage Product Conditions', 83, 'web', '2023-01-28 02:51:33', '2023-01-28 02:51:33'),
(104, 'Manage Theme General settings', 29, 'web', '2023-01-25 23:53:23', '2023-01-25 23:53:23'),
(105, 'Manage Order Details', 84, 'web', '0000-00-00 00:00:00', NULL),
(106, 'Manage Seller Orders', 85, 'web', '2023-01-26 02:30:05', '2023-01-26 02:30:05'),
(107, 'Manage Seller Products', 86, 'web', NULL, NULL),
(108, 'Manage Sellers', 87, 'web', NULL, NULL),
(109, 'Manage Payouts', 88, 'web', NULL, NULL),
(110, 'Manage Payouts Requests', 89, 'web', NULL, NULL),
(111, 'Manage Earning History', 90, 'web', NULL, NULL),
(112, 'Manage Seller Settings', 91, 'web', NULL, NULL),
(113, 'Manage Tlcommerce Page Builder', 92, 'web', NULL, NULL),
(114, 'Manage Layout Settings', 93, 'web', NULL, NULL),
(115, 'Manage Quiz', 94, 'web', NOW(), NOW());

-- --------------------------------------------------------

--
-- Table structure for table `permission_module`
--

CREATE TABLE `permission_module` (
  `id` int(11) NOT NULL,
  `parent_module` varchar(510) DEFAULT NULL,
  `module_name` varchar(150) DEFAULT NULL,
  `module_type` varchar(150) DEFAULT NULL,
  `location` varchar(50) DEFAULT NULL,
  `order` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `permission_module`
--

INSERT INTO `permission_module` (`id`, `parent_module`, `module_name`, `module_type`, `location`, `order`, `created_at`, `updated_at`) VALUES
(1, 'Users', 'Role', 'base', NULL, 20, '2023-01-15 09:41:37', '2023-01-29 04:29:56'),
(2, 'Users', 'User', 'base', NULL, 20, '2023-01-15 09:41:43', '2023-01-29 04:29:58'),
(3, 'Users', 'Permission', 'base', NULL, 20, '2023-01-15 09:41:50', '2023-01-29 04:30:00'),
(5, 'Media', 'Media', 'base', NULL, 2, '2023-01-15 09:42:20', '2023-01-29 04:24:38'),
(8, 'Appearences', 'Menus', 'base', NULL, 15, '2023-01-17 05:18:11', '2023-01-29 04:28:34'),
(9, 'Appearences', 'Themes', 'base', NULL, 15, '2023-01-17 05:21:51', '2023-01-29 04:28:37'),
(19, 'Blogs', 'Blog', 'base', NULL, 3, '2023-01-17 22:04:30', '2023-01-29 04:24:46'),
(20, 'Blogs', 'Category', 'base', NULL, 3, '2023-01-17 22:07:25', '2023-01-29 04:24:48'),
(21, 'Blogs', 'Tag', 'base', NULL, 3, '2023-01-17 22:07:44', '2023-01-29 04:24:49'),
(22, 'Blogs', 'Comment', 'base', NULL, 3, '2023-01-17 22:08:21', '2023-01-29 04:24:50'),
(23, 'Pages', 'Page', 'base', NULL, 4, '2023-01-17 22:16:45', '2023-01-29 04:24:59'),
(24, 'Widgets', 'Widget', 'theme', 'tlcommerce', 16, '2023-01-19 05:46:41', '2023-01-29 04:28:48'),
(28, 'Dashboard', 'Dashboard', 'base', NULL, 1, '2023-01-25 22:53:11', '2023-01-29 04:24:07'),
(29, 'Theme Options', 'Theme General settings', 'theme', 'tlcommerce', 17, '2023-01-25 23:26:36', '2023-01-29 04:29:06'),
(30, 'Theme Options', 'Home Page Builder', 'theme', 'tlcommerce', 17, '2023-01-25 23:27:20', '2023-01-29 04:29:08'),
(31, 'Theme Options', 'Slider Settings', 'theme', 'tlcommerce', 17, '2023-01-25 23:28:26', '2023-01-29 04:29:09'),
(32, 'Plugins', 'Plugins', 'base', NULL, 18, '2023-01-25 23:42:52', '2023-01-29 04:29:22'),
(33, 'Settings', 'General settings', 'base', NULL, 19, '2023-01-25 23:53:23', '2023-01-29 04:29:34'),
(34, 'Settings', 'Email Settings', 'base', NULL, 19, '2023-01-25 23:54:26', '2023-01-29 04:29:36'),
(35, 'Settings', 'Email Templates', 'base', NULL, 19, '2023-01-25 23:55:17', '2023-01-29 04:29:38'),
(36, 'Settings', 'Language', 'base', NULL, 19, '2023-01-25 23:56:03', '2023-01-29 04:29:40'),
(37, 'Settings', 'Media Settings', 'base', NULL, 19, '2023-01-25 23:56:31', '2023-01-29 04:29:43'),
(38, 'Settings', 'SEO Settings', 'base', NULL, 19, '2023-01-25 23:57:09', '2023-01-29 04:29:45'),
(39, 'Settings', 'Theme Translations', 'base', NULL, 19, '2023-01-25 23:58:01', '2023-01-29 04:29:47'),
(40, 'Orders', 'Inhouse Orders', 'plugin', 'tlecommercecore', 6, '2023-01-26 02:30:05', '2023-01-29 04:25:54'),
(41, 'Orders', 'Pickup Point Order', 'plugin', 'pickuppoint', 6, '2023-01-26 02:31:26', '2023-01-29 04:25:54'),
(42, 'Customers', 'Customers', 'plugin', 'tlecommercecore', 7, '2023-01-26 03:47:23', '2023-01-29 04:26:05'),
(43, 'Payments', 'Payment Methods', 'plugin', 'tlecommercecore', 9, '2023-01-26 03:52:40', '2023-01-29 04:27:04'),
(44, 'Payments', 'Transaction history', 'plugin', 'tlecommercecore', 9, '2023-01-26 03:53:23', '2023-01-29 04:27:04'),
(45, 'Reports', 'Product Reports', 'plugin', 'tlecommercecore', 11, '2023-01-26 04:03:18', '2023-01-29 04:27:31'),
(46, 'Reports', 'Keyword Search Reports', 'plugin', 'tlecommercecore', 11, '2023-01-26 04:04:19', '2023-01-29 04:27:32'),
(48, 'Reports', 'Wishlist Reports', 'plugin', 'tlecommercecore', 11, '2023-01-26 04:04:19', '2023-01-29 04:27:33'),
(49, 'Ecommerce Settings', 'Taxes', 'plugin', 'tlecommercecore', 12, '2023-01-27 23:05:34', '2023-01-29 04:27:46'),
(50, 'Ecommerce Settings', 'Settings', 'plugin', 'tlecommercecore', 12, '2023-01-27 23:06:05', '2023-01-29 04:27:49'),
(51, 'Ecommerce Settings', 'Currencies', 'plugin', 'tlecommercecore', 12, '2023-01-27 23:07:03', '2023-01-29 04:27:51'),
(52, 'Ecommerce Settings', 'Product Share Options', 'plugin', 'tlecommercecore', 12, '2023-01-27 23:08:16', '2023-01-29 04:27:53'),
(53, 'Wallet', 'Wallet Transactions', 'plugin', 'wallet', 13, '2023-01-27 23:25:38', '2023-01-29 04:28:04'),
(54, 'Wallet', 'Offline Payment Methods', 'plugin', 'wallet', 13, '2023-01-27 23:26:15', '2023-01-29 04:28:06'),
(55, 'Refunds', 'Refund Requests', 'plugin', 'refund', 14, '2023-01-27 23:30:07', '2023-01-29 04:28:21'),
(56, 'Refunds', 'Refund reasons', 'plugin', 'refund', 14, '2023-01-27 23:31:00', '2023-01-29 04:28:23'),
(57, 'Activity Logs', 'Login activity', 'base', NULL, 21, '2023-01-27 23:33:54', '2023-01-29 04:30:10'),
(66, 'Shippings', 'Shipping & Delivery', 'plugin', 'tlecommercecore', 8, '2023-01-28 00:49:14', '2023-01-29 04:26:18'),
(67, 'Shippings', 'Pickup Points', 'plugin', 'pickuppoint', 8, '2023-01-28 00:49:52', '2023-01-29 04:26:17'),
(68, 'Shippings', 'Carriers', 'plugin', 'carrier', 8, '2023-01-28 00:50:29', '2023-01-29 04:26:17'),
(69, 'Shippings', 'Locations', 'plugin', 'tlecommercecore', 8, '2023-01-28 00:51:12', '2023-01-29 04:26:19'),
(70, 'Marketing', 'Flash Deals', 'plugin', 'flashdeal', 10, '2023-01-28 02:18:09', '2023-01-29 04:27:20'),
(71, 'Marketing', 'Coupons', 'plugin', 'coupon', 10, '2023-01-28 02:20:35', '2023-01-29 04:27:19'),
(72, 'Marketing', 'Custom notification', 'plugin', 'tlecommercecore', 10, '2023-01-28 02:21:24', '2023-01-29 04:27:21'),
(73, 'Products', 'Add New Product', 'plugin', 'tlecommercecore', 5, '2023-01-28 02:43:04', '2023-01-29 04:25:35'),
(74, 'Products', 'Inhouse Products', 'plugin', 'tlecommercecore', 5, '2023-01-28 02:43:41', '2023-07-19 03:44:10'),
(75, 'Products', 'Colors', 'plugin', 'tlecommercecore', 5, '2023-01-28 02:44:20', '2023-01-29 04:25:36'),
(76, 'Products', 'Brands', 'plugin', 'tlecommercecore', 5, '2023-01-28 02:44:45', '2023-01-29 04:25:37'),
(77, 'Products', 'Categories', 'plugin', 'tlecommercecore', 5, '2023-01-28 02:46:04', '2023-01-29 04:25:37'),
(78, 'Products', 'Attributes', 'plugin', 'tlecommercecore', 5, '2023-01-28 02:46:54', '2023-01-29 04:25:38'),
(79, 'Products', 'Units', 'plugin', 'tlecommercecore', 5, '2023-01-28 02:47:26', '2023-01-29 04:25:39'),
(80, 'Products', 'Product Reviews', 'plugin', 'tlecommercecore', 5, '2023-01-28 02:48:37', '2023-01-29 04:25:40'),
(81, 'Products', 'Product collections', 'plugin', 'tlecommercecore', 5, '2023-01-28 02:49:28', '2023-01-29 04:25:41'),
(82, 'Products', 'Product Tags', 'plugin', 'tlecommercecore', 5, '2023-01-28 02:50:38', '2023-01-29 04:25:42'),
(83, 'Products', 'Product Conditions', 'plugin', 'tlecommercecore', 5, '2023-01-28 02:51:33', '2023-01-29 04:25:43'),
(84, 'Orders', 'Order Details', 'plugin', 'tlecommercecore', 6, '2023-03-07 08:17:36', '2023-03-07 08:17:36'),
(85, 'Orders', 'Seller Orders', 'plugin', 'multivendor', 6, '2023-01-26 02:31:26', '2023-06-05 10:13:32'),
(86, 'Products', 'Seller Products', 'plugin', 'multivendor', 5, '2023-01-28 02:43:41', '2023-06-05 09:39:43'),
(87, 'Sellers', 'Sellers', 'plugin', 'multivendor', 22, '2023-06-05 09:41:44', '2023-06-05 09:42:27'),
(88, 'Sellers', 'Payouts', 'plugin', 'multivendor', 22, '2023-06-05 09:41:44', '2023-06-05 09:42:27'),
(89, 'Sellers', 'Payouts Requests', 'plugin', 'multivendor', 22, '2023-06-05 09:41:44', '2023-06-05 09:42:27'),
(90, 'Sellers', 'Earning History', 'plugin', 'multivendor', 22, '2023-06-05 09:41:44', '2023-06-05 09:43:55'),
(91, 'Sellers', 'Seller Settings', 'plugin', 'multivendor', 22, '2023-06-05 09:41:44', '2023-06-05 10:33:16'),
(92, 'Tlcommerce Page Builder', 'Tlcommerce Page Builder', 'plugin', 'tlcommerce-pagebuilder', 4, '2023-06-05 09:41:44', '2023-06-05 10:33:16'),
(93, 'Theme Options', 'Layout Settings', 'theme', 'tlcommerce', 17, '2026-01-27 16:12:00', NULL),
(94, 'Theme Options', 'Quiz', 'theme', 'tlcommerce', 17, '2026-01-27 16:12:00', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `personal_access_tokens`
--

CREATE TABLE `personal_access_tokens` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `tokenable_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tokenable_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `abilities` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

CREATE TABLE `roles` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `guard_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `roles`
--

INSERT INTO `roles` (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES
(1, 'Super Admin', 'web', '2022-06-06 02:07:19', '2023-01-30 05:36:19'),
(3, 'Manager', 'web', '2023-02-13 20:42:51', '2023-02-13 20:42:51'),
(4, 'Demo Admin', 'web', '2023-02-16 21:23:22', '2023-02-16 21:23:22');

-- --------------------------------------------------------

--
-- Table structure for table `role_has_permissions`
--

CREATE TABLE `role_has_permissions` (
  `permission_id` bigint(20) UNSIGNED NOT NULL,
  `role_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `role_has_permissions`
--

INSERT INTO `role_has_permissions` (`permission_id`, `role_id`) VALUES
(3, 4),
(4, 4),
(5, 4),
(10, 4),
(11, 4),
(12, 4),
(13, 4),
(14, 4),
(15, 4),
(17, 4),
(18, 4),
(20, 4),
(25, 4),
(26, 4),
(27, 4),
(28, 4),
(29, 4),
(30, 4),
(31, 4),
(32, 4),
(33, 4),
(34, 4),
(35, 4),
(36, 4),
-- (41, 4),
(41, 4),
(43, 4),
(44, 4),
(45, 4),
(46, 4),
(47, 4),
(48, 4),
(49, 4),
(50, 4),
(51, 4),
(52, 4),
(53, 4),
(54, 4),
(58, 4),
(59, 4),
(60, 4),
(61, 4),
(62, 4),
(64, 4),
(65, 4),
(66, 4),
(67, 4),
(68, 4),
(69, 4),
(70, 4),
(71, 4),
(72, 4),
(86, 4),
(87, 4),
(88, 4),
(89, 4),
(90, 4),
(91, 4),
(92, 4),
(93, 4),
(94, 4),
(95, 4),
(96, 4),
(97, 4),
(98, 4),
(99, 4),
(100, 4),
(101, 4),
(102, 4),
(103, 4),
(104, 4),
(114, 4),
(115, 4);

-- --------------------------------------------------------

--
-- Table structure for table `timezones`
--

CREATE TABLE `timezones` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `offset` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `diff_from_gtm` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tl_amount_types`
--

CREATE TABLE `tl_amount_types` (
  `id` int(11) NOT NULL,
  `name` varchar(50) DEFAULT NULL,
  `sign` varchar(50) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `tl_amount_types`
--

INSERT INTO `tl_amount_types` (`id`, `name`, `sign`, `created_at`, `updated_at`) VALUES
(1, 'percent', '%', '2022-07-21 04:27:03', NULL),
(2, 'flat', '$', '2022-07-21 04:27:21', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `tl_blogs`
--

CREATE TABLE `tl_blogs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(225) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `permalink` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_id` int(11) NOT NULL DEFAULT 0,
  `image` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `short_description` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `reading_time` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `content` longtext COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `visibility` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_sticky` smallint(2) DEFAULT NULL,
  `blog_password` longtext COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `publish_at` datetime DEFAULT NULL,
  `is_featured` smallint(6) DEFAULT NULL,
  `is_publish` smallint(6) DEFAULT NULL,
  `meta_title` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `meta_description` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `meta_image` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tl_blogs_categories`
--

CREATE TABLE `tl_blogs_categories` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `blog_id` bigint(20) UNSIGNED NOT NULL,
  `category_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tl_blogs_tags`
--

CREATE TABLE `tl_blogs_tags` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `blog_id` bigint(20) UNSIGNED NOT NULL,
  `tag_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tl_blog_categories`
--

CREATE TABLE `tl_blog_categories` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `permalink` text DEFAULT NULL,
  `parent` bigint(20) UNSIGNED DEFAULT NULL,
  `banner` text DEFAULT NULL,
  `icon` text DEFAULT NULL,
  `short_description` text DEFAULT NULL,
  `meta_title` varchar(50) DEFAULT NULL,
  `meta_image` text DEFAULT NULL,
  `meta_description` text DEFAULT NULL,
  `is_featured` int(3) DEFAULT NULL,
  `is_publish` int(3) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `tl_blog_category_translations`
--

CREATE TABLE `tl_blog_category_translations` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `short_description` text DEFAULT NULL,
  `category_id` bigint(20) UNSIGNED DEFAULT NULL,
  `lang` varchar(150) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `tl_blog_comments`
--

CREATE TABLE `tl_blog_comments` (
  `id` bigint(20) NOT NULL,
  `blog_id` bigint(20) UNSIGNED NOT NULL,
  `user_type` varchar(50) DEFAULT NULL,
  `user_id` int(6) DEFAULT NULL,
  `user_ip_address` varchar(255) DEFAULT NULL,
  `user_name` varchar(255) DEFAULT NULL,
  `user_email` varchar(150) DEFAULT NULL,
  `user_website` varchar(255) DEFAULT NULL,
  `comment` longtext NOT NULL,
  `parent` bigint(20) DEFAULT NULL,
  `status` tinyint(3) DEFAULT 2,
  `previous_status` tinyint(3) DEFAULT NULL,
  `comment_date` datetime DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `tl_blog_tags`
--

CREATE TABLE `tl_blog_tags` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `permalink` text DEFAULT NULL,
  `banner` text DEFAULT NULL,
  `icon` text DEFAULT NULL,
  `short_description` text DEFAULT NULL,
  `meta_title` varchar(50) DEFAULT NULL,
  `meta_image` text DEFAULT NULL,
  `meta_description` text DEFAULT NULL,
  `is_publish` int(3) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `tl_blog_tag_translations`
--

CREATE TABLE `tl_blog_tag_translations` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `short_description` text DEFAULT NULL,
  `tag_id` bigint(20) UNSIGNED DEFAULT NULL,
  `lang` varchar(150) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `tl_blog_translations`
--

CREATE TABLE `tl_blog_translations` (
  `id` bigint(20) NOT NULL,
  `blog_id` bigint(20) UNSIGNED NOT NULL,
  `lang` varchar(150) NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `short_description` text DEFAULT NULL,
  `content` longtext DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `tl_com_attributes`
--

-- CREATE TABLE `tl_com_attributes` (
--   `id` int(11) NOT NULL,
--   `name` varchar(150) DEFAULT NULL,
--   `status` int(11) DEFAULT NULL,
--   `created_at` timestamp NULL DEFAULT current_timestamp(),
--   `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp()
-- ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE `tl_com_attributes` (
  `id` int(11) NOT NULL,
  `name` varchar(150) DEFAULT NULL,
  `status` int(11) DEFAULT NULL,
  `multi_select` TINYINT(1) NULL DEFAULT NULL,
  `multi_select_limit` INT UNSIGNED NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `tl_com_attribute_values`
--

CREATE TABLE `tl_com_attribute_values` (
  `id` int(11) NOT NULL,
  `name` varchar(150) DEFAULT NULL,
  `value` varchar(150) DEFAULT NULL,
  `status` int(11) DEFAULT NULL,
  `attribute_id` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `tl_com_bank_payments`
--

CREATE TABLE `tl_com_bank_payments` (
  `id` int(11) NOT NULL,
  `order_id` int(11) DEFAULT NULL,
  `bank_name` varchar(150) DEFAULT NULL,
  `branch_name` varchar(150) DEFAULT NULL,
  `account_number` varchar(150) DEFAULT NULL,
  `account_name` varchar(150) DEFAULT NULL,
  `transaction_number` varchar(150) DEFAULT NULL,
  `receipt` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `tl_com_brands`
--

CREATE TABLE `tl_com_brands` (
  `id` int(11) NOT NULL,
  `name` varchar(150) NOT NULL,
  `permalink` text DEFAULT NULL,
  `logo` text DEFAULT NULL,
  `meta_title` text DEFAULT NULL,
  `meta_description` text DEFAULT NULL,
  `meta_image` text DEFAULT NULL,
  `is_featured` text DEFAULT NULL,
  `status` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `tl_com_brand_translations`
--

CREATE TABLE `tl_com_brand_translations` (
  `id` int(11) NOT NULL,
  `name` varchar(150) NOT NULL,
  `brand_id` int(11) DEFAULT NULL,
  `lang` varchar(150) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `tl_com_cart_items`
--

CREATE TABLE `tl_com_cart_items` (
  `id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `image` text DEFAULT NULL,
  `customer_id` int(11) NOT NULL,
  `uid` text DEFAULT NULL,
  `variant` varchar(250) DEFAULT NULL,
  `variant_code` varchar(250) DEFAULT NULL,
  `unitPrice` double NOT NULL DEFAULT 0,
  `oldPrice` double NOT NULL DEFAULT 0,
  `quantity` int(11) NOT NULL DEFAULT 1,
  `min_item` int(11) NOT NULL DEFAULT 1,
  `max_item` int(11) NOT NULL DEFAULT 1,
  `attachment` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `tl_com_cash_back_configs`
--

CREATE TABLE `tl_com_cash_back_configs` (
  `id` int(11) NOT NULL,
  `maximum_limit_status` int(11) DEFAULT NULL,
  `maximum_limit` int(11) DEFAULT NULL,
  `cashback_on_cod` int(11) DEFAULT NULL,
  `cashback_to_account` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `tl_com_cash_back_offer`
--

CREATE TABLE `tl_com_cash_back_offer` (
  `id` int(11) NOT NULL,
  `amount_type` int(11) DEFAULT 0,
  `amount` double DEFAULT 0,
  `product_id` int(11) DEFAULT 0,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `tl_com_categories`
--

CREATE TABLE `tl_com_categories` (
  `id` int(11) NOT NULL,
  `name` varchar(150) DEFAULT NULL,
  `permalink` text NOT NULL,
  `parent` int(11) DEFAULT NULL,
  `banner` text DEFAULT NULL,
  `icon` text DEFAULT NULL,
  `meta_title` varchar(50) DEFAULT NULL,
  `meta_image` text DEFAULT NULL,
  `meta_description` text DEFAULT NULL,
  `ordering_level` int(11) DEFAULT 0,
  `is_featured` int(11) DEFAULT 2,
  `status` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `tl_com_categories`
--

-- INSERT INTO `tl_com_categories` (`id`, `name`, `permalink`, `parent`, `banner`, `icon`, `meta_title`, `meta_image`, `meta_description`, `ordering_level`, `is_featured`, `status`, `created_at`, `updated_at`) VALUES
-- (1, 'Category 1', 'category-1', NULL, NULL, '31', NULL, NULL, NULL, 0, 2, 1, '2023-03-11 16:43:54', '2023-03-23 01:11:36'),
-- (2, 'Category 2', 'category-2', NULL, NULL, '31', NULL, NULL, NULL, 0, 2, 1, '2023-03-11 16:44:11', '2023-03-23 01:10:57'),
-- (3, 'Category 3', 'category-3', NULL, NULL, '31', NULL, NULL, NULL, 0, 2, 1, '2023-03-11 16:44:26', '2023-03-23 01:11:25'),
-- (4, 'Category 4', 'category-4', NULL, NULL, '31', NULL, NULL, NULL, 0, 2, 1, '2023-03-11 16:44:35', '2023-03-23 01:10:45'),
-- (5, 'Category 5', 'category-5', NULL, NULL, '31', NULL, NULL, NULL, 0, 2, 1, '2023-03-11 16:44:49', '2023-03-23 01:10:34'),
-- (6, 'Category 6', 'category-6', NULL, NULL, '31', NULL, NULL, NULL, 0, 2, 1, '2023-03-11 19:36:44', '2023-03-23 01:10:22');

-- --------------------------------------------------------

--
-- Table structure for table `tl_com_category_has_commission`
--

CREATE TABLE `tl_com_category_has_commission` (
  `id` int(11) NOT NULL,
  `category_id` int(11) NOT NULL,
  `rate` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `tl_com_category_translations`
--

CREATE TABLE `tl_com_category_translations` (
  `id` int(11) NOT NULL,
  `lang` varchar(150) NOT NULL,
  `name` varchar(150) NOT NULL,
  `category_id` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `tl_com_cities`
--

CREATE TABLE `tl_com_cities` (
  `id` int(11) NOT NULL,
  `state_id` int(11) DEFAULT NULL,
  `name` varchar(150) DEFAULT NULL,
  `status` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `tl_com_cities`
--

INSERT INTO `tl_com_cities` (`id`, `state_id`, `name`, `status`, `created_at`, `updated_at`) VALUES
(1,8,'al-Andalus',1,'2021-04-06 07:13:48','2021-04-06 07:13:48'),
(2,8,'al-Farwaniyah',1,'2021-04-06 07:13:48','2021-04-06 07:13:48'),
(3,8,'Rabiya',1,'2021-04-06 07:13:48','2026-07-06 04:49:20'),
(4,9,'Amgarah',1,'2021-04-06 07:13:48','2021-04-06 07:13:48'),
(5,9,'Desert Area',1,'2021-04-06 07:13:48','2021-04-06 07:13:48'),
(6,9,'Al-Naseem',1,'2021-04-06 07:13:48','2021-04-06 07:13:48'),
(7,9,'Taima',1,'2021-04-06 07:13:48','2021-04-06 07:13:48'),
(8,9,'Al-Ouyoun',1,'2021-04-06 07:13:48','2021-04-06 07:13:48'),
(9,9,'Waha',1,'2021-04-06 07:13:48','2021-04-06 07:13:48'),
(10,9,'al-Jahra',1,'2021-04-06 07:13:48','2021-04-06 07:13:48'),
(11,9,'al-Qusayr',1,'2021-04-06 07:13:48','2021-04-06 07:13:48'),
(12,9,'as-Sulaybiyah',1,'2021-04-06 07:13:48','2021-04-06 07:13:48'),
(48357,1,'Sharq',1,'2026-07-06 04:37:31','2026-07-06 04:37:31'),
(48358,1,'Abdulla Al-Salem',1,'2026-07-06 04:41:42','2026-07-06 04:41:42'),
(48359,1,'Adailiya',1,'2026-07-06 04:41:57','2026-07-06 04:41:57'),
(48360,1,'Al-Sour Gardens (Hadaeq Al-Sour)',1,'2026-07-06 04:42:13','2026-07-06 04:42:13'),
(48361,1,'Bneid Al-Qar',1,'2026-07-06 04:42:46','2026-07-06 04:42:46'),
(48362,1,'Daiya',1,'2026-07-06 04:43:00','2026-07-06 04:43:00'),
(48363,1,'Dasma',1,'2026-07-06 04:43:14','2026-07-06 04:43:14'),
(48364,1,'Dasman',1,'2026-07-06 04:43:29','2026-07-06 04:43:29'),
(48365,1,'Doha',1,'2026-07-06 04:43:44','2026-07-06 04:43:44'),
(48366,1,'Doha Port',1,'2026-07-06 04:44:01','2026-07-06 04:44:01'),
(48367,1,'Faiha',1,'2026-07-06 04:44:16','2026-07-06 04:44:16'),
(48368,1,'Failaka Island',1,'2026-07-06 04:44:28','2026-07-06 04:44:28'),
(48369,1,'Granada',1,'2026-07-06 04:44:44','2026-07-06 04:44:44'),
(48370,1,'Jaber Al-Ahmad',1,'2026-07-06 04:45:00','2026-07-06 04:45:00'),
(48371,1,'Jibla (Qibla)',1,'2026-07-06 04:45:16','2026-07-06 04:45:16'),
(48372,1,'Kaifan',1,'2026-07-06 04:45:31','2026-07-06 04:45:31'),
(48373,1,'Khaldiya',1,'2026-07-06 04:45:47','2026-07-06 04:45:47'),
(48374,1,'Kuwait City',1,'2026-07-06 04:46:02','2026-07-06 04:46:02'),
(48375,1,'Mansouriya',1,'2026-07-06 04:46:35','2026-07-06 04:46:35'),
(48376,1,'Mirqab',1,'2026-07-06 04:46:57','2026-07-06 04:46:57'),
(48377,1,'Mubarakiyah Camps',1,'2026-07-06 04:47:11','2026-07-06 04:47:11'),
(48378,1,'Nahdha',1,'2026-07-06 04:47:28','2026-07-06 04:47:28'),
(48379,1,'North West Sulaibikhat',1,'2026-07-06 04:47:44','2026-07-06 04:47:44'),
(48380,1,'Nuzha',1,'2026-07-06 04:48:05','2026-07-06 04:48:05'),
(48381,1,'Qadsiya',1,'2026-07-06 04:48:22','2026-07-06 04:48:22'),
(48382,1,'Qortuba',1,'2026-07-06 04:48:36','2026-07-06 04:48:36'),
(48383,1,'Rawda',1,'2026-07-06 04:48:50','2026-07-06 04:48:50'),
(48384,1,'Salhiya',1,'2026-07-06 04:49:04','2026-07-06 04:49:04'),
(48385,1,'Shamiya',1,'2026-07-06 04:49:20','2026-07-06 04:49:20'),
(48386,1,'Shuwaikh Administrative',1,'2026-07-06 04:49:35','2026-07-06 04:49:35'),
(48387,1,'Shuwaikh Industrial',1,'2026-07-06 04:49:51','2026-07-06 04:49:51'),
(48388,8,'Rehab',1,'2026-07-06 04:49:55','2026-07-06 04:49:55'),
(48389,1,'Shuwaikh Educational',1,'2026-07-06 04:50:06','2026-07-06 04:50:06'),
(48390,8,'Omariya',1,'2026-07-06 04:50:10','2026-07-06 04:50:10'),
(48391,1,'Shuwaikh Health',1,'2026-07-06 04:50:20','2026-07-06 04:50:20'),
(48392,1,'Shuwaikh Residential',1,'2026-07-06 04:50:34','2026-07-06 04:50:34'),
(48393,1,'Sulaibikhat',1,'2026-07-06 04:50:47','2026-07-06 04:50:47'),
(48394,1,'Yarmouk',1,'2026-07-06 04:51:01','2026-07-06 04:51:01'),
(48395,8,'Al Shadadiya',1,'2026-07-06 04:51:38','2026-07-06 04:51:38'),
(48396,8,'Al Riggae',1,'2026-07-06 04:52:00','2026-07-06 04:52:00'),
(48398,8,'Al-Dajeej',1,'2026-07-06 04:52:37','2026-07-06 04:52:37'),
(48399,8,'Al-Rai',1,'2026-07-06 04:52:50','2026-07-06 04:52:50'),
(48400,8,'Al-Ardiya',1,'2026-07-06 04:53:10','2026-07-06 04:53:10'),
(48401,2,'Bayan',1,'2026-07-06 04:53:27','2026-07-06 04:53:27'),
(48402,2,'Hawalli',1,'2026-07-06 04:53:43','2026-07-06 04:53:43'),
(48403,2,'Hitteen (Hittin)',1,'2026-07-06 04:53:56','2026-07-06 04:53:56'),
(48404,2,'Jabriya',1,'2026-07-06 04:54:11','2026-07-06 04:54:11'),
(48405,2,'Mishrif',1,'2026-07-06 04:54:25','2026-07-06 04:54:25'),
(48406,2,'Mubarak Al-Abdullah',1,'2026-07-06 04:54:39','2026-08-06 08:14:27'),
(48407,2,'Ministries Area (Manṭiqat al-Wuzarāt)',1,'2026-07-06 04:54:53','2026-07-06 04:54:53'),
(48408,2,'Rumaithiya',1,'2026-07-06 04:55:09','2026-07-06 04:55:09'),
(48409,2,'Salam',1,'2026-07-06 04:55:22','2026-07-06 04:55:22'),
(48410,2,'Salmiya',1,'2026-07-06 04:55:36','2026-07-06 04:55:36'),
(48411,2,'Salwa',1,'2026-07-06 04:55:49','2026-07-06 04:55:49'),
(48412,2,'Shaab',1,'2026-07-06 04:56:04','2026-07-06 04:56:04'),
(48413,2,'Shuhada',1,'2026-07-06 04:56:16','2026-07-06 04:56:16'),
(48414,2,'Al-Bida\'a (Bid\'a)',1,'2026-07-06 04:56:29','2026-07-06 04:56:29'),
(48415,2,'Al-Siddiq',1,'2026-07-06 04:56:41','2026-07-06 04:56:41'),
(48416,2,'Anjafa',1,'2026-07-06 04:56:53','2026-07-06 04:56:53'),
(48417,2,'Zahra',1,'2026-07-06 04:57:10','2026-07-06 04:57:10'),
(48418,8,'Sabah Al-Nasser',1,'2026-07-06 04:57:34','2026-07-06 04:57:34'),
(48419,8,'Firdous',1,'2026-07-06 04:57:57','2026-07-06 04:57:57'),
(48420,7,'Abu Halifa',1,'2026-07-06 04:58:08','2026-07-06 04:58:08'),
(48421,8,'Ishbiliya',1,'2026-07-06 04:58:12','2026-07-06 04:58:12'),
(48422,7,'Ahmadi',1,'2026-07-06 04:58:22','2026-07-06 04:58:22'),
(48423,7,'Ali Sabah Al-Salem (Umm Al-Hayman)',1,'2026-07-06 04:58:36','2026-07-06 04:58:36'),
(48424,7,'Al-Eqaila',1,'2026-07-06 04:58:49','2026-07-06 04:58:49'),
(48425,8,'West & South Abdullah Al-Mubbarak',1,'2026-07-06 04:58:53','2026-07-06 04:58:53'),
(48426,7,'Fahaheel',1,'2026-07-06 04:59:02','2026-07-06 04:59:02'),
(48427,8,'Abdullah Al-Mubarak',1,'2026-07-06 04:59:15','2026-07-06 04:59:15'),
(48428,7,'Fintas',1,'2026-07-06 04:59:17','2026-07-06 04:59:17'),
(48429,7,'Hadiya',1,'2026-07-06 04:59:28','2026-07-06 04:59:28'),
(48431,7,'Jaber Al-Ali',1,'2026-07-06 04:59:41','2026-07-06 04:59:41'),
(48432,7,'Mahboula',1,'2026-07-06 04:59:54','2026-07-06 04:59:54'),
(48433,7,'Mangaf',1,'2026-07-06 05:00:09','2026-07-06 05:00:09'),
(48434,7,'Riqqa (Riggae/Ar Riqqah)',1,'2026-07-06 05:00:22','2026-07-06 05:00:42'),
(48435,8,'Khaitan',1,'2026-07-06 05:00:41','2026-07-06 05:00:41'),
(48436,7,'Sabah Al-Ahmad',1,'2026-07-06 05:01:12','2026-07-06 05:01:12'),
(48438,8,'Jleeb Al-Shuyoukh',1,'2026-07-06 05:01:34','2026-07-06 05:01:34'),
(48439,7,'Wafra',1,'2026-07-06 05:01:48','2026-07-06 05:01:48'),
(48440,7,'Wara',1,'2026-07-06 05:02:03','2026-07-06 05:02:03'),
(48441,7,'Zoor',1,'2026-07-06 05:02:15','2026-07-06 05:02:15'),
(48442,7,'Khairan',1,'2026-07-06 05:02:36','2026-07-06 05:02:36'),
(48443,7,'Bnaider',1,'2026-07-06 05:02:51','2026-07-06 05:02:51'),
(48444,7,'Julai\'a',1,'2026-07-06 05:03:05','2026-07-06 05:03:05'),
(48445,7,'Mina Abdullah',1,'2026-07-06 05:03:17','2026-07-06 05:03:17'),
(48446,7,'Shuaiba',1,'2026-07-06 05:03:29','2026-07-06 05:03:29'),
(48447,7,'South Sabahiya',1,'2026-07-06 05:03:45','2026-07-06 05:03:45'),
(48448,4122,'Abu Hasaniya',1,'2026-07-06 05:03:58','2026-08-06 08:19:28'),
(48449,7,'Al-Nuwaiseeb',1,'2026-07-06 05:04:13','2026-07-06 05:04:13'),
(48455,8,'Messila',1,'2026-07-06 05:06:38','2026-08-06 08:27:29'),
(48456,4122,'Mubarak Al-Kabeer',1,'2026-07-06 05:06:48','2026-08-06 08:17:50'),
(48457,4122,'Sabah Al-Salem',1,'2026-07-06 05:07:02','2026-07-06 05:07:02'),
(48458,4122,'Sabhan',1,'2026-07-06 05:07:12','2026-07-06 05:07:12'),
(48459,4122,'South Wista',1,'2026-07-06 05:07:23','2026-07-06 05:07:23'),
(48460,4122,'West Abu Fatira Al-Hirafiya',1,'2026-07-06 05:07:35','2026-07-06 05:07:35'),
(48461,4122,'Fnaitees (Fnaitis)',1,'2026-07-06 05:07:48','2026-07-06 05:07:48'),
(48462,4122,'Abu Al Hasaniya',1,'2026-07-06 05:08:00','2026-07-06 05:08:00'),
(48463,4122,'Abu Futaira',1,'2026-07-06 05:08:10','2026-07-06 05:08:10'),
(48464,4122,'Adan',1,'2026-07-06 05:08:21','2026-07-06 05:08:21'),
(48465,4122,'Al-Qurain',1,'2026-07-06 05:08:32','2026-07-06 05:08:32'),
(48466,4122,'Al-Qusour',1,'2026-07-06 05:08:45','2026-07-06 05:08:45'),
(48467,9,'Saad Al-Abdullah',1,'2026-07-06 05:12:01','2026-07-06 05:12:01'),
(48468,9,'Al-Kabd',1,'2026-07-06 05:12:21','2026-07-06 05:12:21'),
(48469,9,'Al-Subiya',1,'2026-07-06 05:12:48','2026-07-06 05:12:48'),
(48470,9,'Kazma',1,'2026-07-06 05:12:58','2026-07-06 05:12:58'),
(48471,9,'Al-Naeem',1,'2026-07-06 05:14:04','2026-07-06 05:14:04'),
(48472,9,'Al-Nahda',1,'2026-08-06 08:24:09','2026-08-06 08:24:09'),
(48473,9,'Sulaibiya Industrial Area',1,'2026-08-06 08:24:30','2026-08-06 08:24:30'),
(48474,9,'Al-Salmi',1,'2026-08-06 08:24:46','2026-08-06 08:24:46'),
(48475,9,'Umm Al-Aish',1,'2026-08-06 08:24:59','2026-08-06 08:24:59');
-- --------------------------------------------------------

--
-- Table structure for table `tl_com_city_translations`
--

CREATE TABLE `tl_com_city_translations` (
  `id` int(11) NOT NULL,
  `city_id` int(11) NOT NULL,
  `lang` varchar(11) DEFAULT NULL,
  `name` varchar(250) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `tl_com_collection_translations`
--

CREATE TABLE `tl_com_collection_translations` (
  `id` int(11) NOT NULL,
  `collection_id` int(11) NOT NULL,
  `lang` varchar(150) NOT NULL,
  `name` varchar(200) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `tl_com_colletions_has_products`
--

CREATE TABLE `tl_com_colletions_has_products` (
  `id` int(11) NOT NULL,
  `collection_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `tl_com_colors`
--

CREATE TABLE `tl_com_colors` (
  `id` int(11) NOT NULL,
  `name` varchar(150) DEFAULT NULL,
  `code` varchar(150) DEFAULT NULL,
  `status` int(11) DEFAULT 1,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `tl_com_color_translations`
--

CREATE TABLE `tl_com_color_translations` (
  `id` int(11) NOT NULL,
  `name` varchar(150) NOT NULL,
  `lang` varchar(150) DEFAULT NULL,
  `color_id` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `tl_com_country_translations`
--

CREATE TABLE `tl_com_country_translations` (
  `id` int(11) NOT NULL,
  `country_id` int(11) NOT NULL,
  `name` varchar(250) NOT NULL,
  `lang` varchar(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `tl_com_coupons`
--

CREATE TABLE `tl_com_coupons` (
  `id` int(11) NOT NULL,
  `code` varchar(50) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `alowed_email` varchar(250) DEFAULT NULL,
  `discount_type` int(11) DEFAULT NULL,
  `discount_amount` double DEFAULT NULL,
  `expire_date` date DEFAULT NULL,
  `free_shipping` int(11) DEFAULT NULL,
  `status` int(11) DEFAULT NULL,
  `minimum_spend_amount` double DEFAULT 0,
  `maximum_spend_mount` double DEFAULT 0,
  `individual_use_only` int(11) DEFAULT NULL,
  `exclude_sale_items` int(11) DEFAULT NULL,
  `usage_limit_per_coupon` int(11) DEFAULT NULL,
  `usage_limit_per_user` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `tl_com_coupon_brands`
--

CREATE TABLE `tl_com_coupon_brands` (
  `id` int(11) NOT NULL,
  `coupon_id` int(11) NOT NULL,
  `brand_id` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `tl_com_coupon_category`
--

CREATE TABLE `tl_com_coupon_category` (
  `id` int(11) NOT NULL,
  `coupon_id` int(11) NOT NULL,
  `category_id` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `tl_com_coupon_exclude_brands`
--

CREATE TABLE `tl_com_coupon_exclude_brands` (
  `id` int(11) NOT NULL,
  `coupon_id` int(11) NOT NULL,
  `brand_id` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `tl_com_coupon_exclude_category`
--

CREATE TABLE `tl_com_coupon_exclude_category` (
  `id` int(11) NOT NULL,
  `coupon_id` int(11) NOT NULL,
  `category_id` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `tl_com_coupon_exclude_products`
--

CREATE TABLE `tl_com_coupon_exclude_products` (
  `id` int(11) NOT NULL,
  `coupon_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `tl_com_coupon_products`
--

CREATE TABLE `tl_com_coupon_products` (
  `id` int(11) NOT NULL,
  `coupon_id` int(11) DEFAULT 0,
  `product_id` int(11) DEFAULT 0,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `tl_com_coupon_usages`
--

CREATE TABLE `tl_com_coupon_usages` (
  `id` int(11) NOT NULL,
  `coupon_id` int(11) DEFAULT NULL,
  `customer_id` int(11) DEFAULT NULL,
  `order_id` int(11) DEFAULT NULL,
  `discounted_amount` double NOT NULL DEFAULT 0,
  `coupon_code` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `tl_com_currencies`
--

CREATE TABLE `tl_com_currencies` (
  `id` int(11) NOT NULL,
  `name` varchar(50) DEFAULT NULL,
  `code` varchar(50) DEFAULT NULL,
  `symbol` varchar(50) DEFAULT NULL,
  `conversion_rate` double DEFAULT 0,
  `position` varchar(150) DEFAULT NULL,
  `thousand_separator` varchar(11) DEFAULT NULL,
  `decimal_separator` varchar(11) DEFAULT NULL,
  `number_of_decimal` int(11) NOT NULL DEFAULT 0,
  `status` int(11) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `tl_com_currencies`
--

INSERT INTO `tl_com_currencies` (`id`, `name`, `code`, `symbol`, `conversion_rate`, `position`, `thousand_separator`, `decimal_separator`, `number_of_decimal`, `status`, `created_at`, `updated_at`) VALUES
(1, 'Kuwaiti Dinar', 'KWD', 'KD', 3.26, '1', ',', '.', 3, 1, '2022-07-26 20:36:16', '2022-10-04 18:33:35');
-- (1, 'Us Dolar', 'USD', '$', 1, '1', ',', '.', 2, 1, '2022-07-26 20:36:16', '2022-10-04 18:33:35'),
-- (6, 'BDT', 'BD', '৳', 100, '2', ',', '.', 2, 1, '2023-02-07 21:49:35', '2023-02-09 01:44:24');

-- --------------------------------------------------------

--
-- Table structure for table `tl_com_customers`
--

CREATE TABLE `tl_com_customers` (
  `id` int(11) NOT NULL,
  `uid` varchar(250) DEFAULT NULL,
  `name` varchar(250) NOT NULL,
  `email` varchar(200) DEFAULT NULL,
  `image` int(11) DEFAULT NULL,
  `phone_code` varchar(50) DEFAULT NULL,
  `phone` varchar(250) DEFAULT NULL,
  `password` text DEFAULT NULL,
  `reset_password` varchar(250) DEFAULT NULL,
  `status` int(11) NOT NULL DEFAULT 2,
  `verified_at` timestamp NULL DEFAULT NULL,
  `varification_code` text DEFAULT NULL,
  `is_logedin` int(11) NOT NULL DEFAULT 2,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `tl_com_customers`
--

-- INSERT INTO `tl_com_customers` (`id`, `uid`, `name`, `email`, `image`, `phone_code`, `phone`, `password`, `reset_password`, `status`, `verified_at`, `varification_code`, `is_logedin`, `created_at`, `updated_at`) VALUES
-- (1, '125608230312', 'Demo Customer', 'demo.customer@test.com', 29, NULL, '995566545', '$2y$10$sluui7N.vwcFLFMtbrWv7uy4rdYRoQXDXATgqZgDJGG7ohmFeoGRG', NULL, 1, NULL, 'drc9mPIlMa6B8fYok2sW4juKxTEb7QgR', 2, '2023-03-12 16:56:08', '2023-03-23 01:15:45');

-- --------------------------------------------------------

--
-- Table structure for table `tl_com_customer_address`
--

CREATE TABLE `tl_com_customer_address` (
  `id` int(11) NOT NULL,
  `customer_id` int(11) DEFAULT NULL,
  `guest_customer` int(11) DEFAULT NULL,
  `name` varchar(250) NOT NULL,
  `country_id` int(11) DEFAULT NULL,
  `state_id` int(11) DEFAULT NULL,
  `city_id` int(11) DEFAULT NULL,
  `postal_code` varchar(150) DEFAULT NULL,
  `address` text NOT NULL,
  `phone_code` varchar(150) DEFAULT NULL,
  `phone` text NOT NULL,
  `default_shipping` int(11) NOT NULL DEFAULT 2,
  `default_billing` int(11) NOT NULL DEFAULT 2,
  `status` int(11) NOT NULL DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `tl_com_customer_address`
--

-- INSERT INTO `tl_com_customer_address` (`id`, `customer_id`, `guest_customer`, `name`, `country_id`, `state_id`, `city_id`, `postal_code`, `address`, `phone_code`, `phone`, `default_shipping`, `default_billing`, `status`, `created_at`, `updated_at`) VALUES
-- (1, 1, NULL, 'Demo Customer', 229, 3798, 41391, '10986', 'Mazamir Electronics, P.O.Box81494', 'null', '97126333354', 2, 2, 1, '2023-03-12 17:09:21', '2023-03-12 17:09:21');

-- --------------------------------------------------------

--
-- Table structure for table `tl_com_customer_wishlists`
--

CREATE TABLE `tl_com_customer_wishlists` (
  `id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `customer_id` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `tl_com_custom_notifications`
--

CREATE TABLE `tl_com_custom_notifications` (
  `id` int(11) NOT NULL,
  `to` int(11) NOT NULL,
  `type` int(11) NOT NULL,
  `details` text DEFAULT NULL,
  `sender` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Table structure for table `tl_com_social_media_integrations`
--

CREATE TABLE `tl_com_social_media_integrations` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `provider` VARCHAR(50) NOT NULL,
  `is_active` tinyint(1) DEFAULT 0,
  `settings` json DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



-- --------------------------------------------------------

--
-- Table structure for table `tl_com_deals_products`
--

CREATE TABLE `tl_com_deals_products` (
  `id` int(11) NOT NULL,
  `deal_id` int(11) DEFAULT NULL,
  `product_id` int(11) DEFAULT NULL,
  `discount` double DEFAULT NULL,
  `discount_type` varchar(150) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `tl_com_deals_products`
--

-- INSERT INTO `tl_com_deals_products` (`id`, `deal_id`, `product_id`, `discount`, `discount_type`, `created_at`, `updated_at`) VALUES
-- (1, 1, 2, 60, '2', '2023-03-11 19:55:56', '2023-03-11 19:55:56'),
-- (2, 1, 4, 60, '2', '2023-03-11 19:55:56', '2023-03-11 19:55:56'),
-- (3, 1, 3, 60, '2', '2023-03-11 19:55:56', '2023-03-11 19:55:56');

-- --------------------------------------------------------

--
-- Table structure for table `tl_com_ecommerce_settings`
--

CREATE TABLE `tl_com_ecommerce_settings` (
  `id` int(11) NOT NULL,
  `key_name` varchar(250) DEFAULT NULL,
  `key_value` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `tl_com_ecommerce_settings`
--

INSERT INTO `tl_com_ecommerce_settings` (`id`, `key_name`, `key_value`, `created_at`, `updated_at`) VALUES
(4, 'enable_product_reviews', '1', '2022-09-27 05:45:55', '2022-11-07 05:27:51'),
(6, 'enable_product_star_rating', '1', '2022-09-27 05:49:53', '2022-11-07 05:27:51'),
(7, 'required_product_star_rating', '1', '2022-09-27 05:53:10', '2022-11-02 23:17:51'),
(8, 'verified_customer_on_product_review', '1', '2022-09-27 05:53:10', '2022-09-27 05:53:10'),
(10, 'only_varified_customer_left_review', '1', '2022-09-27 05:55:24', '2022-11-02 23:17:51'),
(11, 'enable_product_compare', '1', '2022-09-27 05:56:42', '2022-12-06 21:34:25'),
(12, 'manage_product_stock', '2', '2022-09-27 05:56:42', '2022-12-06 21:34:25'),
(13, 'enable_product_discount', '1', '2022-09-27 05:56:42', '2023-01-15 07:14:58'),
(14, 'enable_billing_address', '1', '2022-09-27 06:04:25', '2023-01-08 00:35:02'),
(15, 'use_shipping_address_as_billing_address', '2', '2022-09-27 06:04:25', '2023-02-22 17:45:10'),
(16, 'enable_guest_checkout', '1', '2022-09-27 06:04:25', '2023-02-22 17:37:05'),
(17, 'create_account_in_guest_checkout', '1', '2022-09-27 06:04:25', '2023-02-22 17:36:08'),
(18, 'send_invoice_to_customer_mail', '1', '2022-09-27 06:04:25', '2022-09-28 00:17:57'),
(19, 'enable_tax_in_checkout', '1', '2022-09-27 06:04:25', '2022-10-25 03:06:07'),
(20, 'enable_coupon_in_checkout', '1', '2022-09-27 06:04:25', '2022-10-26 22:44:04'),
(21, 'enable_multiple_coupon_in_checkout', '1', '2022-09-27 06:14:01', '2022-10-26 22:46:22'),
(22, 'enable_minumun_order_amount', '2', '2022-09-27 06:14:02', '2023-01-10 00:12:32'),
(23, 'min_order_amount', '1000', '2022-09-27 06:14:02', '2022-10-17 00:26:59'),
(24, 'enable_wallet_in_checkout', '1', '2022-09-27 06:14:02', '2022-10-24 03:29:41'),
(25, 'enable_order_note_in_checkout', '1', '2022-09-27 06:14:02', '2022-10-24 05:13:34'),
(26, 'enable_document_in_checkout', '1', '2022-09-27 06:14:02', '2022-09-28 00:17:57'),
(27, 'customer_auto_approved', '1', '2022-09-28 02:39:09', '2022-10-10 23:32:25'),
(28, 'customer_social_auth', '2', '2022-09-28 02:39:09', '2023-01-15 07:06:23'),
(29, 'cancel_order_time_limit', '5', '2022-09-28 03:24:03', '2023-01-05 06:11:07'),
(30, 'cancel_order_time_limit_unit', 'Days', '2022-09-28 03:24:03', '2023-01-05 06:11:07'),
(31, 'return_order_time_limit', '100', '2022-09-28 03:40:33', '2022-11-29 22:42:03'),
(32, 'return_order_time_limit_unit', 'Days', '2022-09-28 03:40:33', '2022-11-19 21:28:49'),
(33, 'product_per_page', '15', '2022-09-28 03:53:35', '2023-01-12 03:45:01'),
(34, 'enable_carrier_in_checkout', '1', '2022-09-28 04:25:13', '2022-10-23 04:39:52'),
(35, 'order_code_prefix', 'iv', '2022-09-28 05:39:32', '2022-10-30 23:53:09'),
(36, 'enable_wallet_online_recharge', '1', '2022-09-28 06:13:03', '2022-11-27 02:20:44'),
(37, 'enable_wallet_offline_recharge', '1', '2022-09-28 06:13:03', '2022-11-27 02:20:44'),
(38, 'minimum_wallet_rechage_amount', NULL, '2022-09-28 06:15:49', '2022-09-28 06:15:49'),
(39, 'maximum_wallet_used_in_single_order', '0', '2022-09-28 06:20:35', '2022-11-27 02:16:48'),
(40, 'minimum_wallet_recharge_amount', '100', '2022-09-28 06:25:22', '2022-09-28 06:25:46'),
(41, 'default_currency', '1', '2022-09-29 03:22:42', '2023-02-08 22:15:13'),
(42, 'customer_email_varification', '0', '2022-10-11 00:28:58', '2022-10-11 00:30:39'),
(43, 'custom', NULL, '2022-10-17 00:22:00', '2022-10-17 00:22:00'),
(44, 'enable_pickuppoint_in_checkout', '1', '2022-10-17 03:11:36', '2023-01-07 22:47:18'),
(45, 'order_code_prefix_seperator', '-', '2022-10-25 02:26:27', '2022-10-30 23:53:09'),
(46, 'invoice_email', 'tlcommerce@gmail.com', '2022-11-22 05:45:35', '2022-11-22 06:07:32'),
(47, 'invoice_phone', '208465445, +0087878', '2022-11-22 05:47:46', '2022-11-22 06:07:32'),
(48, 'invoice_address', 'Shewrapara, Dhaka, Bangladesh.', '2022-11-22 05:47:47', '2022-11-22 05:53:11'),
(49, 'hide_country_state_city_in_checkout', '2', '2022-11-22 05:47:46', '2022-11-22 06:07:32');

-- --------------------------------------------------------

--
-- Table structure for table `tl_com_flash_deal`
--

CREATE TABLE `tl_com_flash_deal` (
  `id` int(11) NOT NULL,
  `title` varchar(150) DEFAULT NULL,
  `background_color` varchar(150) DEFAULT NULL,
  `text_color` varchar(150) DEFAULT NULL,
  `background_image` text DEFAULT NULL,
  `permalink` varchar(150) DEFAULT NULL,
  `start_date` timestamp NULL DEFAULT NULL,
  `end_date` timestamp NULL DEFAULT NULL,
  `status` int(11) NOT NULL DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `tl_com_flash_deal`
--

--
-- Table structure for table `tl_com_feedback`
--

CREATE TABLE `tl_com_feedback` (
    id INT NOT NULL AUTO_INCREMENT,
    name VARCHAR(100) NULL,
    satisfaction_rating TINYINT NOT NULL,
    phone VARCHAR(50) NULL,
    comment TEXT NULL,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,

    PRIMARY KEY (id),

    CHECK (satisfaction_rating BETWEEN 1 AND 5)
);

--
-- Dumping data for table `tl_com_feedback`
--

-- INSERT INTO `tl_com_flash_deal` (`id`, `title`, `background_color`, `text_color`, `background_image`, `permalink`, `start_date`, `end_date`, `status`, `created_at`, `updated_at`) VALUES
-- (1, 'Flash Deal', '#FFFFF', '#FFFFF', '33', 'flash-deal', '2023-03-11 19:42:00', '2024-03-11 18:42:00', 1, '2023-03-11 19:42:54', '2023-03-23 01:24:56');

-- --------------------------------------------------------

--
-- Table structure for table `tl_com_flash_deal_translations`
--

CREATE TABLE `tl_com_flash_deal_translations` (
  `id` int(11) NOT NULL,
  `deal_id` int(11) DEFAULT NULL,
  `lang` varchar(150) DEFAULT NULL,
  `title` varchar(200) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `tl_com_guest_customer`
--

CREATE TABLE `tl_com_guest_customer` (
  `id` int(11) NOT NULL,
  `name` varchar(250) NOT NULL,
  `email` varchar(250) NOT NULL,
  `order_id` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `tl_com_key_word_search`
--

CREATE TABLE `tl_com_key_word_search` (
  `id` int(11) NOT NULL,
  `key_word` text DEFAULT NULL,
  `ip` text DEFAULT NULL,
  `browser` text DEFAULT NULL,
  `os` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `tl_com_location_wise_shipping_rates`
--

CREATE TABLE `tl_com_location_wise_shipping_rates` (
  `id` int(11) NOT NULL,
  `title` varchar(250) DEFAULT NULL,
  `cost` double NOT NULL DEFAULT 0,
  `status` int(11) NOT NULL DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `tl_com_location_wise_shipping_rate_cities`
--

CREATE TABLE `tl_com_location_wise_shipping_rate_cities` (
  `id` int(11) NOT NULL,
  `rate_id` int(11) NOT NULL,
  `city_id` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `tl_com_location_wise_shipping_rate_countries`
--

CREATE TABLE `tl_com_location_wise_shipping_rate_countries` (
  `id` int(11) NOT NULL,
  `rate_id` int(11) DEFAULT NULL,
  `country_id` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `tl_com_location_wise_shipping_rate_states`
--

CREATE TABLE `tl_com_location_wise_shipping_rate_states` (
  `id` int(11) NOT NULL,
  `rate_id` int(11) DEFAULT NULL,
  `state_id` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `tl_com_ordered_products`
--

CREATE TABLE `tl_com_ordered_products` (
  `id` int(11) NOT NULL,
  `order_id` int(11) DEFAULT 0,
  `seller_id` int(11) DEFAULT NULL,
  `product_id` int(11) DEFAULT 0,
  `variant_id` text DEFAULT NULL,
  `quantity` int(11) DEFAULT 0,
  `purchase_price` double DEFAULT 0,
  `delivery_cost` double DEFAULT 0,
  `shipping_rate` int(11) DEFAULT NULL,
  `tax` double DEFAULT 0,
  `discount` double DEFAULT 0,
  `unit_price` double DEFAULT 0,
  `order_discount` double NOT NULL DEFAULT 0,
  `total_paid` double NOT NULL DEFAULT 0,
  `attachment` text DEFAULT NULL,
  `image` text DEFAULT NULL,
  `delivery_status` int(11) DEFAULT NULL,
  `payment_status` int(11) NOT NULL DEFAULT 2,
  `delivery_time` timestamp NULL DEFAULT NULL,
  `return_status` int(11) DEFAULT NULL,
  `tracking_id` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `tl_com_ordered_products`
--

-- INSERT INTO `tl_com_ordered_products` (`id`, `order_id`, `seller_id`, `product_id`, `variant_id`, `quantity`, `purchase_price`, `delivery_cost`, `shipping_rate`, `tax`, `discount`, `unit_price`, `order_discount`, `total_paid`, `attachment`, `image`, `delivery_status`, `payment_status`, `delivery_time`, `return_status`, `tracking_id`, `created_at`, `updated_at`) VALUES
-- (1, 1, NULL, 2, '', 1, 400, 15, 2, 0, 60, 239, 0, 0, NULL, '/public/storage/all_files/2023/Mar/trending-offers-ear-phone-02_9_32.png', 2, 2, NULL, 1, NULL, '2023-03-12 18:27:57', '2023-03-12 18:27:57');

-- --------------------------------------------------------

--
-- Table structure for table `tl_com_orders`
--

CREATE TABLE `tl_com_orders` (
  `id` int(11) NOT NULL,
  `order_code` varchar(50) DEFAULT NULL,
  `customer_id` int(11) DEFAULT NULL,
  `sub_total` double DEFAULT 0,
  `total_tax` double DEFAULT 0,
  `total_delivery_cost` double DEFAULT 0,
  `total_discount` double DEFAULT 0,
  `total_payable_amount` double DEFAULT 0,
  `total_order_amount` double NOT NULL DEFAULT 0,
  `payment_method` int(11) DEFAULT NULL,
  `wallet_payment` int(11) NOT NULL DEFAULT 2,
  `shipping_type` int(11) DEFAULT NULL,
  `pickup_point_id` int(11) DEFAULT NULL,
  `shipping_address` int(11) DEFAULT NULL,
  `billing_address` int(11) DEFAULT NULL,
  `delivery_date` timestamp NULL DEFAULT NULL,
  `note` text DEFAULT NULL,
  `payment_status` int(11) DEFAULT NULL,
  `delivery_status` int(11) DEFAULT NULL,
  `read_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp(),
  `guest_customer_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `tl_com_orders`
--

-- INSERT INTO `tl_com_orders` (`id`, `order_code`, `customer_id`, `sub_total`, `total_tax`, `total_delivery_cost`, `total_discount`, `total_payable_amount`, `total_order_amount`, `payment_method`, `wallet_payment`, `shipping_type`, `pickup_point_id`, `shipping_address`, `billing_address`, `delivery_date`, `note`, `payment_status`, `delivery_status`, `read_at`, `created_at`, `updated_at`) VALUES
-- (1, 'iv-022757230312', 1, 239, 0, 15, 0, 254, 254, 1, 2, 2, NULL, 1, 1, NULL, 'Order Note', 2, 2, NULL, '2023-03-12 18:27:57', '2023-03-12 18:27:57');

-- --------------------------------------------------------

--
-- Table structure for table `tl_com_order_package_trackings`
--

CREATE TABLE `tl_com_order_package_trackings` (
  `id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `order_package_id` int(11) NOT NULL,
  `message` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `tl_com_order_package_trackings`
--

-- INSERT INTO `tl_com_order_package_trackings` (`id`, `order_id`, `order_package_id`, `message`, `created_at`, `updated_at`) VALUES
-- (1, 1, 1, 'Thank you for shopping. Your order is being verified', '2023-03-12 18:27:57', '2023-03-12 18:27:57');

-- --------------------------------------------------------

--
-- Table structure for table `tl_com_order_refund_requests`
--

CREATE TABLE `tl_com_order_refund_requests` (
  `id` int(11) NOT NULL,
  `customer_id` int(11) DEFAULT NULL,
  `refund_code` varchar(100) NOT NULL DEFAULT '0',
  `order_id` int(11) NOT NULL DEFAULT 0,
  `ordered_product_id` int(11) NOT NULL DEFAULT 0,
  `quantity` int(11) NOT NULL DEFAULT 1,
  `total_amount` double NOT NULL DEFAULT 0,
  `total_refund_amount` int(11) NOT NULL DEFAULT 0,
  `images` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL,
  `reason_id` int(11) DEFAULT NULL,
  `comment` text DEFAULT NULL,
  `refund_status` int(11) NOT NULL DEFAULT 0,
  `return_status` int(11) NOT NULL DEFAULT 0,
  `read_at` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `tl_com_payment_methods`
--

CREATE TABLE `tl_com_payment_methods` (
  `id` int(11) NOT NULL,
  `name` varchar(50) DEFAULT NULL,
  `logo` int(11) DEFAULT NULL,
  `docs` text DEFAULT NULL,
  `status` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `tl_com_payment_methods`
--

INSERT INTO `tl_com_payment_methods` (`id`, `name`, `logo`, `docs`, `status`, `created_at`, `updated_at`) VALUES
(1, 'Payzah', NULL, NULL, 1, '2026-02-25 06:39:30', NULL),
(2, 'MyFatoorah', NULL, NULL, 1, '2026-02-25 06:39:30', NULL);
-- (1, 'Cash On Delivery', NULL, NULL, 1, '2022-09-26 06:39:30', '2022-09-26 00:39:30'),
-- (2, 'Paypal', NULL, NULL, 1, '2022-12-17 05:47:01', '2022-12-16 23:47:01'),
-- (3, 'Stripe', NULL, NULL, 1, '2022-12-18 04:05:15', '2022-12-17 22:05:15'),
-- (4, 'Paddle', NULL, NULL, 1, '2023-03-27 05:19:41', '2023-03-27 05:19:41'),
-- (5, 'Sslcommerz', NULL, NULL, 1, '2023-03-27 09:24:43', '2023-03-27 09:24:43'),
-- (6, 'Paystack', NULL, NULL, 1, '2023-04-02 08:34:29', '2023-04-02 08:34:22'),
-- (7, 'Razorpay', NULL, NULL, 1, '2023-04-03 06:36:45', '2023-04-03 05:51:05'),
-- (8, 'Mollie', NULL, NULL, 1, '2023-05-30 09:21:23', '2023-05-30 09:21:20'),
-- (9, 'Bank', NULL, NULL, 1, '2023-06-01 10:40:37', '2023-06-01 10:00:43'),
-- (10, 'Gpay', NULL, NULL, 1, '2023-06-01 10:40:37', '2023-06-01 10:00:43');

-- --------------------------------------------------------

--
-- Table structure for table `tl_com_payment_method_has_settings`
--

CREATE TABLE `tl_com_payment_method_has_settings` (
  `id` int(11) NOT NULL,
  `payment_method_id` int(11) NOT NULL,
  `key_name` varchar(200) NOT NULL,
  `key_value` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `tl_com_payment_method_has_settings`
--

INSERT INTO `tl_com_payment_method_has_settings` (`id`, `payment_method_id`, `key_name`, `key_value`, `created_at`, `updated_at`) VALUES
(1, 1, 'payzah_currency', 'KWD', '2026-02-25 04:34:35', '2023-04-05 05:42:12'),
(2, 1, 'payzah_logo', NULL, '2026-02-25 04:34:35', '2023-04-05 04:34:35'),
(3, 1, 'payzah_secret_key', NULL, '2026-02-25 04:34:35', '2023-04-05 04:34:35'),
(4, 1, 'payzah_instruction', NULL, '2026-02-25 04:34:35', '2023-04-05 04:34:35'),
(5, 2, 'myfatoorah_currency', 'KWD', '2026-02-25 04:34:35', '2023-04-05 05:42:12'),
(6, 2, 'myfatoorah_logo', NULL, '2026-02-25 04:34:35', '2023-04-05 04:34:35'),
(7, 2, 'myfatoorah_secret_key', NULL, '2026-02-25 04:34:35', '2023-04-05 04:34:35'),
(8, 2, 'myfatoorah_instruction', NULL, '2026-02-25 04:34:35', '2023-04-05 04:34:35');
-- (20, 1, 'cod_logo', '54', '2023-01-30 01:42:31', '2023-04-05 05:42:35'),
-- (21, 1, 'cod_instruction', 'Buy this product on Cash On Delivery', '2023-01-30 01:42:31', '2023-02-20 23:41:19'),
-- (22, 2, 'paypal_logo', '49', '2023-01-30 01:42:31', '2023-04-05 05:42:32'),
-- (23, 2, 'paypal_client_id', NULL, '2023-01-30 01:42:31', '2023-03-12 18:26:19'),
-- (24, 2, 'paypal_client_secret', NULL, '2023-01-30 01:42:31', '2023-03-12 18:26:19'),
-- (25, 2, 'sandbox', '1', '2023-01-30 01:42:31', '2023-02-28 22:38:15'),
-- (26, 2, 'paypal_instruction', 'Pay the amount with Paypal', '2023-01-30 01:42:31', '2023-02-20 23:42:56'),
-- (27, 3, 'stripe_logo', '53', '2023-01-30 01:42:31', '2023-04-05 05:42:29'),
-- (28, 3, 'stripe_public_key', 'public key', '2023-01-30 01:42:31', '2023-04-05 04:35:36'),
-- (29, 3, 'stripe_secret_key', 'secret key', '2023-01-30 01:42:31', '2023-04-05 04:35:36'),
-- (30, 3, 'stripe_instruction', 'Pay the amount with Stripe', '2023-01-30 01:42:31', '2023-02-20 23:43:09'),
-- (31, 4, 'paddle_logo', '48', '2023-04-05 04:34:34', '2023-04-05 05:42:26'),
-- (32, 4, 'paddle_vendor_id', NULL, '2023-04-05 04:34:34', '2023-04-05 04:34:34'),
-- (33, 4, 'paddle_public_key', NULL, '2023-04-05 04:34:34', '2023-04-05 04:34:34'),
-- (34, 4, 'paddle_vendor_auth_code', NULL, '2023-04-05 04:34:34', '2023-04-05 04:34:34'),
-- (35, 4, 'sandbox', '2', '2023-04-05 04:34:34', '2023-04-05 04:36:22'),
-- (36, 4, 'paddle_instruction', NULL, '2023-04-05 04:34:34', '2023-04-05 04:34:34'),
-- (37, 5, 'sslcommerz_logo', '52', '2023-04-05 04:34:35', '2023-04-05 05:42:21'),
-- (38, 5, 'sslcz_store_id', NULL, '2023-04-05 04:34:35', '2023-04-05 04:34:35'),
-- (39, 5, 'sslcz_store_password', NULL, '2023-04-05 04:34:35', '2023-04-05 04:34:35'),
-- (40, 5, 'sandbox', '2', '2023-04-05 04:34:35', '2023-04-05 04:36:29'),
-- (41, 5, 'sslcommerz_instruction', NULL, '2023-04-05 04:34:35', '2023-04-05 04:34:35'),
-- (42, 6, 'paystack_logo', '50', '2023-04-05 04:34:35', '2023-04-05 05:42:18'),
-- (43, 6, 'paystack_public_key', NULL, '2023-04-05 04:34:35', '2023-04-05 04:34:35'),
-- (44, 6, 'paystack_secret_key', NULL, '2023-04-05 04:34:35', '2023-04-05 04:34:35'),
-- (45, 6, 'sandbox', '2', '2023-04-05 04:34:35', '2023-04-05 04:36:39'),
-- (46, 6, 'paystack_instruction', NULL, '2023-04-05 04:34:35', '2023-04-05 04:34:35'),
-- (47, 7, 'razorpay_logo', '51', '2023-04-05 04:34:35', '2023-04-05 05:42:12'),
-- (48, 7, 'razorpay_key_id', NULL, '2023-04-05 04:34:35', '2023-04-05 04:34:35'),
-- (49, 7, 'razorpay_key_secret', NULL, '2023-04-05 04:34:35', '2023-04-05 04:34:35'),
-- (50, 7, 'sandbox', '2', '2023-04-05 04:34:35', '2023-04-05 04:36:46'),
-- (51, 7, 'razorpay_instruction', NULL, '2023-04-05 04:34:35', '2023-04-05 04:34:35');

-- --------------------------------------------------------

--
-- Table structure for table `tl_com_payment_transactions`
--

CREATE TABLE `tl_com_payment_transactions` (
  `id` int(11) NOT NULL,
  `payment_method` varchar(50) NOT NULL,
  `paid_amount` double NOT NULL,
  `payment_for` varchar(150) DEFAULT NULL,
  `payment_info` text DEFAULT NULL,
  `guest_customer` int(10) DEFAULT NULL,
  `customer_id` int(11) DEFAULT NULL,
  `user_id` int(10) DEFAULT NULL,
  `status` int(10) NOT NULL DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `tl_com_products`
--

CREATE TABLE `tl_com_products` (
  `id` int(11) NOT NULL,
  `name` varchar(200) NOT NULL,
  `brand` int(11) DEFAULT NULL,
  `summary` text DEFAULT NULL,
  `description` text DEFAULT NULL,
  `product_type` int(11) DEFAULT NULL,
  `supplier` int(11) DEFAULT NULL,
  `permalink` text DEFAULT NULL,
  `unit` int(11) DEFAULT NULL,
  `conditions` int(11) DEFAULT NULL,
  `has_variant` int(11) DEFAULT 0,
  `discount_type` int(11) DEFAULT 0,
  `discount_amount` double DEFAULT NULL,
  `pdf_specifications` text DEFAULT NULL,
  `thumbnail_image` text DEFAULT NULL,
  `video_link` text DEFAULT NULL,
  `is_featured` int(11) DEFAULT NULL,
  `max_item_on_purchase` int(11) DEFAULT NULL,
  `min_item_on_purchase` int(11) DEFAULT NULL,
  `low_stock_quantity_alert` int(11) DEFAULT NULL,
  `is_authentic` int(11) DEFAULT NULL,
  `has_warranty` int(11) DEFAULT NULL,
  `has_replacement_warranty` int(11) DEFAULT NULL,
  `warrenty_days` int(11) DEFAULT NULL,
  `is_refundable` int(11) DEFAULT NULL,
  `shipping_location_type` varchar(150) DEFAULT NULL,
  `is_active_cod` int(11) DEFAULT NULL,
  `is_active_free_shipping` int(11) NOT NULL DEFAULT 2,
  `cod_location_type` varchar(150) DEFAULT NULL,
  `is_active_attatchment` int(11) DEFAULT NULL,
  `attatchment_name` varchar(150) DEFAULT NULL,
  `shipping_cost` double DEFAULT 0,
  `is_apply_multiple_qty_shipping_cost` int(11) NOT NULL DEFAULT 1,
  `is_enable_tax` int(11) NOT NULL DEFAULT 2,
  `tax_profile` int(11) DEFAULT NULL,
  `status` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp(),
  `is_approved` int(11) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `tl_com_products`
--

-- INSERT INTO `tl_com_products` (`id`, `name`, `brand`, `summary`, `description`, `product_type`, `supplier`, `permalink`, `unit`, `conditions`, `has_variant`, `discount_type`, `discount_amount`, `pdf_specifications`, `thumbnail_image`, `video_link`, `is_featured`, `max_item_on_purchase`, `min_item_on_purchase`, `low_stock_quantity_alert`, `is_authentic`, `has_warranty`, `has_replacement_warranty`, `warrenty_days`, `is_refundable`, `shipping_location_type`, `is_active_cod`, `is_active_free_shipping`, `cod_location_type`, `is_active_attatchment`, `attatchment_name`, `shipping_cost`, `is_apply_multiple_qty_shipping_cost`, `is_enable_tax`, `tax_profile`, `status`, `created_at`, `updated_at`, `is_approved`) VALUES
-- (2, 'Product 1', NULL, '<p><span style=\"font-weight: bolder; margin: 0px; padding: 0px; font-family: &quot;Open Sans&quot;, Arial, sans-serif; font-size: 14px; text-align: justify;\">Lorem Ipsum</span><span style=\"font-family: &quot;Open Sans&quot;, Arial, sans-serif; font-size: 14px; text-align: justify;\">&nbsp;is simply dummy text of the printing and typesetting industry.</span><br></p>', '<p><strong style=\"margin: 0px; padding: 0px; font-family: &quot;Open Sans&quot;, Arial, sans-serif; font-size: 14px; text-align: justify;\">Lorem Ipsum</strong><span style=\"font-family: &quot;Open Sans&quot;, Arial, sans-serif; font-size: 14px; text-align: justify;\">&nbsp;is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.</span><br></p>', 1, NULL, 'product-1', 15, 8, 2, 2, NULL, NULL, '32', NULL, 2, NULL, 1, 1, 1, 2, 2, NULL, 2, NULL, 1, 2, 'anywhere', 2, NULL, 0, 1, 2, NULL, 1, '2023-03-11 19:48:37', '2023-03-23 01:13:15', 1),
-- (3, 'Product 2', NULL, '<p><span style=\"font-weight: bolder; margin: 0px; padding: 0px; font-family: &quot;Open Sans&quot;, Arial, sans-serif; font-size: 14px; text-align: justify;\">Lorem Ipsum</span><span style=\"font-family: &quot;Open Sans&quot;, Arial, sans-serif; font-size: 14px; text-align: justify;\">&nbsp;is simply dummy text of the printing and typesetting industry.</span><br></p>', '<p><strong style=\"margin: 0px; padding: 0px; font-family: &quot;Open Sans&quot;, Arial, sans-serif; font-size: 14px; text-align: justify;\">Lorem Ipsum</strong><span style=\"font-family: &quot;Open Sans&quot;, Arial, sans-serif; font-size: 14px; text-align: justify;\">&nbsp;is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.</span><br></p>', 1, NULL, 'product-2', 15, 9, 2, 2, NULL, NULL, '32', NULL, 2, NULL, 1, 1, 1, 2, 2, NULL, 2, NULL, 1, 2, 'anywhere', 2, NULL, 0, 1, 2, NULL, 1, '2023-03-11 19:49:43', '2023-03-23 01:12:47', 1),
-- (4, 'Product 3', NULL, '<p><span style=\"font-weight: bolder; margin: 0px; padding: 0px; font-family: &quot;Open Sans&quot;, Arial, sans-serif; font-size: 14px; text-align: justify;\">Lorem Ipsum</span><span style=\"font-family: &quot;Open Sans&quot;, Arial, sans-serif; font-size: 14px; text-align: justify;\">&nbsp;is simply dummy text of the printing and typesetting industry.&nbsp;</span><br></p>', '<p><strong style=\"margin: 0px; padding: 0px; font-family: &quot;Open Sans&quot;, Arial, sans-serif; font-size: 14px; text-align: justify;\">Lorem Ipsum</strong><span style=\"font-family: &quot;Open Sans&quot;, Arial, sans-serif; font-size: 14px; text-align: justify;\">&nbsp;is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.</span><br></p>', 1, NULL, 'product-3', 15, 8, 2, 2, NULL, NULL, '32', NULL, 2, NULL, 1, 1, 1, 2, 2, NULL, 2, NULL, 1, 2, 'anywhere', 2, NULL, 0, 1, 2, NULL, 1, '2023-03-11 19:50:39', '2023-03-23 01:12:18', 1);

-- --------------------------------------------------------

--
-- Table structure for table `tl_com_product_attribute_translations`
--

CREATE TABLE `tl_com_product_attribute_translations` (
  `id` int(11) NOT NULL,
  `attribute_id` int(11) NOT NULL,
  `lang` varchar(150) DEFAULT NULL,
  `name` varchar(150) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `tl_com_product_cod_cities`
--

CREATE TABLE `tl_com_product_cod_cities` (
  `id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `city_id` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `tl_com_product_cod_countries`
--

CREATE TABLE `tl_com_product_cod_countries` (
  `id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `country_id` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `tl_com_product_cod_states`
--

CREATE TABLE `tl_com_product_cod_states` (
  `id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `state_id` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `tl_com_product_collections`
--

CREATE TABLE `tl_com_product_collections` (
  `id` int(11) NOT NULL,
  `name` varchar(250) NOT NULL,
  `permalink` text DEFAULT NULL,
  `image` int(11) DEFAULT NULL,
  `status` int(11) NOT NULL DEFAULT 2,
  `order_number` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `tl_com_product_color_variant_image`
--

CREATE TABLE `tl_com_product_color_variant_image` (
  `id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL DEFAULT 0,
  `color_id` int(11) DEFAULT NULL,
  `image` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `tl_com_product_conditions`
--

CREATE TABLE `tl_com_product_conditions` (
  `id` int(11) NOT NULL,
  `name` varchar(150) DEFAULT NULL,
  `status` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `tl_com_product_conditions`
--

INSERT INTO `tl_com_product_conditions` (`id`, `name`, `status`, `created_at`, `updated_at`) VALUES
(8, 'Brand New', 1, '2023-02-05 20:03:38', '2023-02-05 20:03:38'),
(9, 'Export Quality', 1, '2023-02-05 20:03:50', '2023-02-05 20:03:50');

-- --------------------------------------------------------

--
-- Table structure for table `tl_com_product_condition_translations`
--

CREATE TABLE `tl_com_product_condition_translations` (
  `id` int(11) NOT NULL,
  `condition_id` int(11) NOT NULL,
  `lang` varchar(150) DEFAULT NULL,
  `name` varchar(150) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `tl_com_product_condition_translations`
--

-- INSERT INTO `tl_com_product_condition_translations` (`id`, `condition_id`, `lang`, `name`, `created_at`, `updated_at`) VALUES
-- (1, 9, 'bd', 'রপ্তানি গুণমান', '2023-02-11 22:42:29', '2023-02-11 22:42:29'),
-- (2, 9, 'sa', 'جودة الصادرات', '2023-02-11 22:42:43', '2023-02-11 22:42:43'),
-- (3, 8, 'bd', 'একদম নতুন', '2023-02-11 22:43:01', '2023-02-11 22:43:01'),
-- (4, 8, 'sa', 'علامة تجارية جديدة', '2023-02-11 22:43:19', '2023-02-11 22:43:19');

-- --------------------------------------------------------

--
-- Table structure for table `tl_com_product_gallery_images`
--

CREATE TABLE `tl_com_product_gallery_images` (
  `id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `image_id` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `tl_com_product_has_categories`
--

CREATE TABLE `tl_com_product_has_categories` (
  `id` int(11) NOT NULL,
  `product_id` int(11) DEFAULT NULL,
  `category_id` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `tl_com_product_has_categories`
--

-- INSERT INTO `tl_com_product_has_categories` (`id`, `product_id`, `category_id`, `created_at`, `updated_at`) VALUES
-- (2, 2, 1, '2023-03-11 19:48:37', '2023-03-11 19:48:37'),
-- (3, 3, 2, '2023-03-11 19:49:43', '2023-03-11 19:49:43'),
-- (4, 4, 3, '2023-03-11 19:50:39', '2023-03-11 19:50:39');

-- --------------------------------------------------------

--
-- Table structure for table `tl_com_product_has_choices`
--

CREATE TABLE `tl_com_product_has_choices` (
  `id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `choice_id` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `tl_com_product_has_choice_options`
--

CREATE TABLE `tl_com_product_has_choice_options` (
  `id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `choice_id` int(11) NOT NULL,
  `option_id` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `tl_com_product_has_colors`
--

CREATE TABLE `tl_com_product_has_colors` (
  `id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `color_id` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `tl_com_product_has_tags`
--

CREATE TABLE `tl_com_product_has_tags` (
  `id` int(11) NOT NULL,
  `tag_id` int(11) DEFAULT NULL,
  `product_id` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `tl_com_product_refund_reasons`
--

CREATE TABLE `tl_com_product_refund_reasons` (
  `id` int(11) NOT NULL,
  `name` varchar(250) NOT NULL,
  `status` int(11) NOT NULL DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `tl_com_product_refund_reasons`
--

INSERT INTO `tl_com_product_refund_reasons` (`id`, `name`, `status`, `created_at`, `updated_at`) VALUES
(2, 'The product do not match the description', 1, '2023-02-15 15:46:24', '2023-02-15 15:46:24'),
(3, 'The product arrived too late', 1, '2023-02-15 15:46:39', '2023-02-15 15:46:39'),
(4, 'The product is damaged or defective', 1, '2023-02-15 15:46:45', '2023-02-15 15:46:45'),
(5, 'The merchant shipped the wrong product', 1, '2023-02-15 15:46:53', '2023-02-15 15:46:53'),
(6, 'Ordered the wrong product', 1, '2023-02-15 15:47:12', '2023-02-15 15:47:12');

-- --------------------------------------------------------

--
-- Table structure for table `tl_com_product_reviews`
--

CREATE TABLE `tl_com_product_reviews` (
  `id` int(11) NOT NULL,
  `product_id` int(11) DEFAULT NULL,
  `customer_id` int(11) DEFAULT NULL,
  `order_id` int(11) DEFAULT NULL,
  `review` text DEFAULT NULL,
  `rating` double DEFAULT NULL,
  `images` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL,
  `status` int(11) NOT NULL DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `tl_com_product_seo`
--

CREATE TABLE `tl_com_product_seo` (
  `id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `meta_title` varchar(200) DEFAULT NULL,
  `meta_description` text DEFAULT NULL,
  `meta_image` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `tl_com_product_seo`
--

-- INSERT INTO `tl_com_product_seo` (`id`, `product_id`, `meta_title`, `meta_description`, `meta_image`, `created_at`, `updated_at`) VALUES
-- (2, 2, NULL, NULL, NULL, '2023-03-11 19:48:37', '2023-03-11 19:48:37'),
-- (3, 3, NULL, NULL, NULL, '2023-03-11 19:49:43', '2023-03-11 19:49:43'),
-- (4, 4, NULL, NULL, NULL, '2023-03-11 19:50:39', '2023-03-11 19:50:39');

-- --------------------------------------------------------

--
-- Table structure for table `tl_com_product_share_options`
--

CREATE TABLE `tl_com_product_share_options` (
  `id` int(11) NOT NULL,
  `network` varchar(150) NOT NULL,
  `network_name` varchar(150) NOT NULL,
  `icon` text DEFAULT NULL,
  `status` int(10) NOT NULL DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `tl_com_product_share_options`
--

INSERT INTO `tl_com_product_share_options` (`id`, `network`, `network_name`, `icon`, `status`, `created_at`, `updated_at`) VALUES
(1, 'facebook', 'Facebook', 'fb.svg', 1, '2021-06-22 04:08:48', '2022-11-08 02:46:57'),
(2, 'linkedin', 'linkedin', 'lnk.svg', 1, '2021-06-22 05:02:37', '2023-01-05 03:28:39'),
(3, 'messenger', 'messenger', 'massenger.svg', 1, '2021-06-22 05:02:37', '2021-06-22 05:02:37'),
(4, 'pinterest', 'pinterest', 'pinterest.svg', 1, '2021-06-22 05:05:56', '2021-06-22 05:05:56'),
(5, 'twitter', 'twitter', 'twitter.svg', 1, '2021-06-22 05:05:56', '2021-06-22 05:05:56'),
(6, 'viber', 'viber', 'viber.svg', 1, '2021-06-22 05:05:56', '2021-06-22 05:05:56'),
(7, 'whatsapp', 'whatsapp', 'whtsapps.svg', 1, '2021-06-22 05:05:56', '2021-06-22 05:05:56'),
(8, 'skype', 'skype', 'skype.svg', 1, '2021-06-22 05:05:56', '2021-06-22 05:05:56'),
(9, 'sms', 'sms', 'sms.svg', 1, '2021-06-22 05:05:56', '2023-01-05 03:29:16'),
(10, 'email', 'email', 'gmail.svg', 1, '2021-06-23 05:48:32', '2023-01-05 03:29:22'),
(11, 'line', 'line', 'line.svg', 1, '2021-06-23 05:49:07', '2023-01-05 03:29:29');

-- --------------------------------------------------------

--
-- Table structure for table `tl_com_product_shipping_info`
--

CREATE TABLE `tl_com_product_shipping_info` (
  `id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `weight` double DEFAULT NULL,
  `height` double DEFAULT NULL,
  `width` double DEFAULT NULL,
  `length` double DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `tl_com_product_shipping_info`
--

-- INSERT INTO `tl_com_product_shipping_info` (`id`, `product_id`, `weight`, `height`, `width`, `length`, `created_at`, `updated_at`) VALUES
-- (2, 2, NULL, NULL, NULL, NULL, '2023-03-11 19:48:37', '2023-03-11 19:48:37'),
-- (3, 3, NULL, NULL, NULL, NULL, '2023-03-11 19:49:43', '2023-03-11 19:49:43'),
-- (4, 4, NULL, NULL, NULL, NULL, '2023-03-11 19:50:39', '2023-03-11 19:50:39');

-- --------------------------------------------------------

--
-- Table structure for table `tl_com_product_tags`
--

CREATE TABLE `tl_com_product_tags` (
  `id` int(11) NOT NULL,
  `name` varchar(150) DEFAULT NULL,
  `permalink` text DEFAULT NULL,
  `status` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `tl_com_product_translation`
--

CREATE TABLE `tl_com_product_translation` (
  `id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `lang` varchar(100) NOT NULL,
  `name` varchar(250) DEFAULT NULL,
  `summary` text DEFAULT NULL,
  `description` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `tl_com_product_types`
--

CREATE TABLE `tl_com_product_types` (
  `id` int(11) NOT NULL,
  `name` varchar(110) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `tl_com_product_types`
--

INSERT INTO `tl_com_product_types` (`id`, `name`, `created_at`, `updated_at`) VALUES
(1, 'Physical Product', '2022-07-21 03:32:07', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `tl_com_product_variant_combination`
--

CREATE TABLE `tl_com_product_variant_combination` (
  `id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `product_variation_id` int(11) DEFAULT NULL,
  `attribute_id` int(11) DEFAULT NULL,
  `attribute_value_id` int(11) NOT NULL,
  `color_id` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `tl_com_recharge_type`
--

CREATE TABLE `tl_com_recharge_type` (
  `id` int(11) NOT NULL,
  `recharge_type` varchar(150) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `tl_com_recharge_type`
--

INSERT INTO `tl_com_recharge_type` (`id`, `recharge_type`) VALUES
(1, 'Online Recharge'),
(2, 'Offline Recharge');

-- --------------------------------------------------------

--
-- Table structure for table `tl_com_refund_reason_translations`
--

CREATE TABLE `tl_com_refund_reason_translations` (
  `id` int(11) NOT NULL,
  `reason_id` int(11) NOT NULL,
  `lang` varchar(11) DEFAULT NULL,
  `name` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `tl_com_refund_reason_translations`
--

-- INSERT INTO `tl_com_refund_reason_translations` (`id`, `reason_id`, `lang`, `name`, `created_at`, `updated_at`) VALUES
-- (1, 6, 'bd', 'ভুল পণ্য অর্ডার', '2023-02-15 15:48:11', '2023-02-15 15:48:11'),
-- (2, 6, 'sa', 'طلبت المنتج الخاطئ', '2023-02-15 15:48:31', '2023-02-15 15:48:31'),
-- (3, 5, 'bd', 'বণিক ভুল পণ্য প্রেরণ করেছে', '2023-02-15 15:49:02', '2023-02-15 15:55:21'),
-- (4, 5, 'sa', 'طلبت المنتج الخاطئ', '2023-02-15 15:49:27', '2023-02-15 15:49:27'),
-- (5, 4, 'bd', 'পণ্য ক্ষতিগ্রস্ত বা ত্রুটিপূর্ণ', '2023-02-15 15:50:00', '2023-02-15 15:50:00'),
-- (6, 4, 'sa', 'المنتج تالف أو به عيب', '2023-02-15 15:50:15', '2023-02-15 15:50:15'),
-- (7, 3, 'bd', 'পণ্যটি খুব দেরিতে পৌঁছেছে', '2023-02-15 15:50:34', '2023-02-15 15:50:34'),
-- (8, 3, 'sa', 'وصل المنتج بعد فوات الأوان', '2023-02-15 15:51:17', '2023-02-15 15:51:17'),
-- (9, 2, 'bd', 'পণ্য বর্ণনার সাথে মেলে না', '2023-02-15 15:51:38', '2023-02-15 15:51:38'),
-- (10, 2, 'sa', 'المنتج لا يتطابق مع الوصف', '2023-02-15 15:51:58', '2023-02-15 15:51:58');

-- --------------------------------------------------------

--
-- Table structure for table `tl_com_refund_request_tracking`
--

CREATE TABLE `tl_com_refund_request_tracking` (
  `id` int(11) NOT NULL,
  `request_id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `message` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `tl_com_related_products`
--

CREATE TABLE `tl_com_related_products` (
  `id` int(11) NOT NULL,
  `parent_product_id` int(11) DEFAULT NULL,
  `releted_product_id` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `tl_com_seller_earning`
--

CREATE TABLE `tl_com_seller_earning` (
  `id` int(11) NOT NULL,
  `seller_id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `order_package_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `earning` double NOT NULL DEFAULT 0,
  `admin_commission` double NOT NULL DEFAULT 0,
  `status` int(11) NOT NULL DEFAULT 2,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `tl_com_seller_followers`
--

CREATE TABLE `tl_com_seller_followers` (
  `id` int(11) NOT NULL,
  `seller_id` int(11) NOT NULL,
  `customer_id` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `tl_com_seller_payout_info`
--

CREATE TABLE `tl_com_seller_payout_info` (
  `id` int(11) NOT NULL,
  `seller_id` int(11) NOT NULL,
  `bank_name` varchar(150) NOT NULL,
  `bank_code` varchar(150) DEFAULT NULL,
  `account_name` varchar(150) NOT NULL,
  `account_holder_name` varchar(150) DEFAULT NULL,
  `account_number` varchar(150) DEFAULT NULL,
  `bank_routing_number` varchar(150) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `tl_com_seller_payout_request`
--

CREATE TABLE `tl_com_seller_payout_request` (
  `id` int(11) NOT NULL,
  `seller_id` int(11) NOT NULL,
  `amount` double DEFAULT NULL,
  `message` text DEFAULT NULL,
  `payment_method` int(11) DEFAULT NULL,
  `transaction_number` text DEFAULT NULL,
  `description` text DEFAULT NULL,
  `status` int(11) NOT NULL DEFAULT 2,
  `payment_date` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `tl_com_seller_shop`
--

CREATE TABLE `tl_com_seller_shop` (
  `id` int(11) NOT NULL,
  `seller_id` int(11) NOT NULL,
  `seller_phone` varchar(100) DEFAULT NULL,
  `shop_slug` varchar(100) NOT NULL,
  `shop_phone` varchar(100) DEFAULT NULL,
  `shop_name` varchar(150) NOT NULL,
  `logo` int(11) DEFAULT NULL,
  `shop_banner` int(11) DEFAULT NULL,
  `shop_address` text DEFAULT NULL,
  `meta_title` varchar(220) DEFAULT NULL,
  `meta_description` text DEFAULT NULL,
  `meta_image` int(11) DEFAULT NULL,
  `status` int(11) NOT NULL DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `tl_com_shipping_courier`
--

CREATE TABLE `tl_com_shipping_courier` (
  `id` int(11) NOT NULL,
  `name` varchar(250) NOT NULL,
  `tracking_url` text DEFAULT NULL,
  `logo` int(11) DEFAULT NULL,
  `status` int(11) NOT NULL DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `tl_com_shipping_courier_properties`
--

CREATE TABLE `tl_com_shipping_courier_properties` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `shipping_courier_id` int(11) NOT NULL,
  `api_key` text DEFAULT NULL,
  `api_secret` text DEFAULT NULL,
  `branch_id` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp(),
 PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `tl_com_shipping_courier`
--

CREATE TABLE `tl_com_shipping_courier_orders` (
  `id`                  INT(11)        NOT NULL AUTO_INCREMENT,
  `order_id`            INT(11)        NOT NULL,                        -- FK to your orders table
  `shipping_courier_id` INT(11)        NOT NULL,                        -- FK to tl_com_shipping_courier
  `code`                VARCHAR(50)    NOT NULL,                        -- e.g. "A1B2C3" (courier's reference)
  `status`              VARCHAR(50)    NOT NULL DEFAULT 'pending',      -- pending, picked_up, delivered, etc.
  `amount`              DECIMAL(10,3)  NOT NULL DEFAULT 0.000,
  `delivery_fee`        DECIMAL(10,3)  NOT NULL DEFAULT 0.000,
  `currency`            VARCHAR(10)    NOT NULL DEFAULT 'KWD',

  -- Driver info (nullable — assigned later)
  `driver_name`         VARCHAR(250)   DEFAULT NULL,
  `driver_phone`        VARCHAR(50)    DEFAULT NULL,
  `driver_latitude`     DECIMAL(10,7)  DEFAULT NULL,
  `driver_longitude`    DECIMAL(10,7)  DEFAULT NULL,

  -- Logistics
  `estimated_distance`  INT(11)        DEFAULT NULL,                    -- in meters
  `estimated_duration`  INT(11)        DEFAULT NULL,                    -- in seconds
  `tracking_url`        TEXT           DEFAULT NULL,
  `pickup_qr_url`       TEXT           DEFAULT NULL,

  `created_at`          TIMESTAMP      NOT NULL DEFAULT CURRENT_TIMESTAMP(),
  `updated_at`          TIMESTAMP      NOT NULL DEFAULT CURRENT_TIMESTAMP() ON UPDATE CURRENT_TIMESTAMP(),

  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_courier_code` (`code`),
  KEY `idx_order_id` (`order_id`),
  KEY `idx_shipping_courier_id` (`shipping_courier_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `tl_com_shipping_profiles`
--

CREATE TABLE `tl_com_shipping_profiles` (
  `id` int(11) NOT NULL,
  `name` varchar(150) DEFAULT NULL,
  `profile_type` varchar(50) DEFAULT 'custom',
  `address` text DEFAULT NULL,
  `location` varchar(50) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `tl_com_shipping_profiles`
--

-- INSERT INTO `tl_com_shipping_profiles` (`id`, `name`, `profile_type`, `address`, `location`, `created_at`, `updated_at`) VALUES
-- (4, 'General Profile', 'custom', 'New York, USA', 'USA', '2023-02-14 17:26:28', '2023-02-14 20:44:08');

-- --------------------------------------------------------

--
-- Table structure for table `tl_com_shipping_profiles_has_products`
--

CREATE TABLE `tl_com_shipping_profiles_has_products` (
  `id` int(11) NOT NULL,
  `profile_id` int(11) DEFAULT NULL,
  `product_id` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `tl_com_shipping_profiles_has_products`
--

-- INSERT INTO `tl_com_shipping_profiles_has_products` (`id`, `profile_id`, `product_id`, `created_at`, `updated_at`) VALUES
-- (2, 4, 2, '2023-03-11 19:48:37', '2023-03-11 19:48:37'),
-- (3, 4, 3, '2023-03-11 19:49:43', '2023-03-11 19:49:43'),
-- (4, 4, 4, '2023-03-11 19:50:39', '2023-03-11 19:50:39');

-- --------------------------------------------------------

--
-- Table structure for table `tl_com_shipping_times`
--

CREATE TABLE `tl_com_shipping_times` (
  `id` int(11) NOT NULL,
  `min_value` varchar(50) DEFAULT NULL,
  `min_unit` varchar(50) DEFAULT NULL,
  `max_value` varchar(50) DEFAULT NULL,
  `max_unit` varchar(150) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `tl_com_shipping_times`
--

INSERT INTO `tl_com_shipping_times` (`id`, `min_value`, `min_unit`, `max_value`, `max_unit`, `created_at`, `updated_at`) VALUES
(1, '5', 'Days', '7', 'Days', '2023-02-14 17:29:45', '2023-02-14 17:29:45'),
(2, '2', 'Days', '3', 'Days', '2023-02-14 17:30:03', '2023-02-14 17:30:03');

-- --------------------------------------------------------

--
-- Table structure for table `tl_com_shipping_zones`
--

CREATE TABLE `tl_com_shipping_zones` (
  `id` int(11) NOT NULL,
  `name` varchar(150) NOT NULL,
  `profile_id` int(11) DEFAULT NULL,
  `base_tax` float NOT NULL DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `tl_com_shipping_zones`
--

-- INSERT INTO `tl_com_shipping_zones` (`id`, `name`, `profile_id`, `base_tax`, `created_at`, `updated_at`) VALUES
-- (1, 'United Arab Emirates', 4, 0, '2023-03-12 17:05:01', '2023-03-12 17:05:01');

-- --------------------------------------------------------

--
-- Table structure for table `tl_com_shipping_zone_has_cities`
--

CREATE TABLE `tl_com_shipping_zone_has_cities` (
  `id` int(11) NOT NULL,
  `zone_id` int(11) NOT NULL,
  `city_id` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `tl_com_shipping_zone_has_cities`
--

-- INSERT INTO `tl_com_shipping_zone_has_cities` (`id`, `zone_id`, `city_id`, `created_at`, `updated_at`) VALUES
-- (1, 1, 41388, '2023-03-12 17:05:01', '2023-03-12 17:05:01'),
-- (2, 1, 41389, '2023-03-12 17:05:01', '2023-03-12 17:05:01'),
-- (3, 1, 41390, '2023-03-12 17:05:01', '2023-03-12 17:05:01'),
-- (4, 1, 41391, '2023-03-12 17:05:01', '2023-03-12 17:05:01'),
-- (5, 1, 41392, '2023-03-12 17:05:01', '2023-03-12 17:05:01'),
-- (6, 1, 41393, '2023-03-12 17:05:01', '2023-03-12 17:05:01'),
-- (7, 1, 41394, '2023-03-12 17:05:01', '2023-03-12 17:05:01'),
-- (8, 1, 41395, '2023-03-12 17:05:01', '2023-03-12 17:05:01');

-- --------------------------------------------------------

--
-- Table structure for table `tl_com_shipping_zone_has_countries`
--

CREATE TABLE `tl_com_shipping_zone_has_countries` (
  `id` int(11) NOT NULL,
  `zone_id` int(11) NOT NULL,
  `country_id` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `tl_com_shipping_zone_has_countries`
--

-- INSERT INTO `tl_com_shipping_zone_has_countries` (`id`, `zone_id`, `country_id`, `created_at`, `updated_at`) VALUES
-- (1, 1, 229, '2023-03-12 17:05:01', '2023-03-12 17:05:01');

-- --------------------------------------------------------

--
-- Table structure for table `tl_com_shipping_zone_has_rates`
--

CREATE TABLE `tl_com_shipping_zone_has_rates` (
  `id` int(11) NOT NULL,
  `zone_id` int(11) DEFAULT NULL,
  `name` varchar(150) DEFAULT NULL,
  `carrier_id` int(11) DEFAULT NULL,
  `rate_type` varchar(150) DEFAULT NULL,
  `based_on` varchar(150) DEFAULT NULL,
  `min_limit` double DEFAULT 0,
  `max_limit` double DEFAULT 0,
  `condition_unit` varchar(150) DEFAULT NULL,
  `has_condition` int(11) NOT NULL DEFAULT 2,
  `shipping_cost` double NOT NULL DEFAULT 0,
  `delivery_time` int(11) DEFAULT NULL,
  `shipping_medium` varchar(150) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `tl_com_shipping_zone_has_rates`
--

-- INSERT INTO `tl_com_shipping_zone_has_rates` (`id`, `zone_id`, `name`, `carrier_id`, `rate_type`, `based_on`, `min_limit`, `max_limit`, `condition_unit`, `has_condition`, `shipping_cost`, `delivery_time`, `shipping_medium`, `created_at`, `updated_at`) VALUES
-- (1, 1, 'Standard', NULL, 'own_rate', 'weight_based', NULL, NULL, 'gm', 2, 10, 1, NULL, '2023-03-12 17:05:15', '2023-03-12 17:05:15'),
-- (2, 1, 'Express', NULL, 'own_rate', 'weight_based', NULL, NULL, 'gm', 2, 15, 2, NULL, '2023-03-12 17:05:28', '2023-03-12 17:05:28');

-- --------------------------------------------------------

--
-- Table structure for table `tl_com_shipping_zone_has_states`
--

CREATE TABLE `tl_com_shipping_zone_has_states` (
  `id` int(11) NOT NULL,
  `zone_id` int(11) NOT NULL,
  `state_id` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `tl_com_shipping_zone_has_states`
--

-- INSERT INTO `tl_com_shipping_zone_has_states` (`id`, `zone_id`, `state_id`, `created_at`, `updated_at`) VALUES
-- (1, 1, 3796, '2023-03-12 17:05:01', '2023-03-12 17:05:01'),
-- (2, 1, 3797, '2023-03-12 17:05:01', '2023-03-12 17:05:01'),
-- (3, 1, 3798, '2023-03-12 17:05:01', '2023-03-12 17:05:01'),
-- (4, 1, 3799, '2023-03-12 17:05:01', '2023-03-12 17:05:01'),
-- (5, 1, 3800, '2023-03-12 17:05:01', '2023-03-12 17:05:01'),
-- (6, 1, 3801, '2023-03-12 17:05:01', '2023-03-12 17:05:01'),
-- (7, 1, 3802, '2023-03-12 17:05:01', '2023-03-12 17:05:01'),
-- (8, 1, 3803, '2023-03-12 17:05:01', '2023-03-12 17:05:01'),
-- (9, 1, 3804, '2023-03-12 17:05:01', '2023-03-12 17:05:01');

-- --------------------------------------------------------

--
-- Table structure for table `tl_com_shipping_zone_has_taxes`
--

CREATE TABLE `tl_com_shipping_zone_has_taxes` (
  `id` int(11) NOT NULL,
  `zone_id` int(11) DEFAULT NULL,
  `state_id` int(11) DEFAULT NULL,
  `product_collection_id` int(11) DEFAULT NULL,
  `tax` double NOT NULL DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `tl_com_single_product_price`
--

CREATE TABLE `tl_com_single_product_price` (
  `id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL DEFAULT 0,
  `sku` varchar(150) DEFAULT NULL,
  `purchase_price` double DEFAULT 1,
  `unit_price` double DEFAULT 1,
  `quantity` int(11) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `tl_com_single_product_price`
--

-- INSERT INTO `tl_com_single_product_price` (`id`, `product_id`, `sku`, `purchase_price`, `unit_price`, `quantity`, `created_at`, `updated_at`) VALUES
-- (2, 2, 'SHIRT100', 400, 299, 49, '2023-03-11 19:48:37', '2023-03-12 18:27:57'),
-- (3, 3, NULL, 500, 459, NULL, '2023-03-11 19:49:43', '2023-03-11 19:51:48'),
-- (4, 4, NULL, 400, 299, NULL, '2023-03-11 19:50:39', '2023-03-11 19:51:36');

-- --------------------------------------------------------

--
-- Table structure for table `tl_com_state`
--

CREATE TABLE `tl_com_state` (
  `id` int(11) NOT NULL,
  `country_id` int(11) DEFAULT NULL,
  `name` varchar(150) DEFAULT NULL,
  `code` varchar(150) DEFAULT NULL,
  `status` int(11) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `tl_com_state`
--

INSERT INTO `tl_com_state` (`id`, `country_id`, `name`, `code`, `status`, `created_at`, `updated_at`) VALUES
(1, 1, 'Al Asimah', NULL, 1, '2021-04-06 07:11:20', '2023-02-07 09:34:21'),
(2, 1, 'Hawalli', NULL, 1, '2021-04-06 07:11:20', '2023-02-07 09:34:21'),
(7, 1, 'al-Ahmadi', NULL, 1, '2021-04-06 07:11:20', '2023-02-07 09:34:21'),
(8, 1, 'al-Farwaniyah', NULL, 1, '2021-04-06 07:11:20', '2023-02-07 09:34:21'),
(9, 1, 'al-Jahra', NULL, 1, '2021-04-06 07:11:20', '2023-02-07 09:34:21'),
(4122, 1, 'Mubarak al Kabeer', NULL, 1, '2021-04-06 07:11:20', '2023-02-07 09:34:21');

-- --------------------------------------------------------

--
-- Table structure for table `tl_com_state_translations`
--

CREATE TABLE `tl_com_state_translations` (
  `id` int(11) NOT NULL,
  `state_id` int(11) NOT NULL,
  `name` varchar(250) DEFAULT NULL,
  `lang` varchar(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `tl_com_tax_profiles`
--

CREATE TABLE `tl_com_tax_profiles` (
  `id` int(11) NOT NULL,
  `title` varchar(250) NOT NULL,
  `status` int(11) NOT NULL DEFAULT 2,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `tl_com_tax_rates`
--

CREATE TABLE `tl_com_tax_rates` (
  `id` int(11) NOT NULL,
  `profile_id` int(11) NOT NULL,
  `country_id` int(11) DEFAULT NULL,
  `state_id` int(11) DEFAULT NULL,
  `city_id` int(11) DEFAULT NULL,
  `postal_code` varchar(150) DEFAULT NULL,
  `tax_name` varchar(150) DEFAULT NULL,
  `tax_rate` double NOT NULL DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `tl_com_units`
--

CREATE TABLE `tl_com_units` (
  `id` int(11) NOT NULL,
  `name` varchar(150) DEFAULT NULL,
  `status` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `tl_com_units`
--

INSERT INTO `tl_com_units` (`id`, `name`, `status`, `created_at`, `updated_at`) VALUES
(15, 'piece', NULL, '2023-02-05 19:45:06', '2023-02-05 19:45:37'),
(16, 'kg', NULL, '2023-02-05 19:45:14', '2023-02-05 19:45:46');

-- --------------------------------------------------------

--
-- Table structure for table `tl_com_unit_translations`
--

CREATE TABLE `tl_com_unit_translations` (
  `id` int(11) NOT NULL,
  `name` varchar(150) NOT NULL,
  `unit_id` int(11) NOT NULL,
  `lang` varchar(150) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `tl_com_unit_translations`
--

-- INSERT INTO `tl_com_unit_translations` (`id`, `name`, `unit_id`, `lang`, `created_at`, `updated_at`) VALUES
-- (3, 'পিচ', 15, 'bd', '2023-02-06 21:51:35', '2023-02-06 21:52:49'),
-- (4, 'قطعة', 15, 'sa', '2023-02-06 21:51:54', '2023-02-06 21:52:03'),
-- (5, 'কেজি', 16, 'bd', '2023-02-06 21:53:05', '2023-02-06 21:53:05'),
-- (6, 'كلغ', 16, 'sa', '2023-02-06 21:53:17', '2023-02-06 21:53:17');

-- --------------------------------------------------------

--
-- Table structure for table `tl_com_variant_product_price`
--

CREATE TABLE `tl_com_variant_product_price` (
  `id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL DEFAULT 0,
  `variant` text DEFAULT NULL,
  `sku` varchar(50) NOT NULL DEFAULT '0',
  `purchase_price` double NOT NULL DEFAULT 1,
  `unit_price` double NOT NULL DEFAULT 1,
  `quantity` int(11) NOT NULL DEFAULT 0,
  `status` int(11) NOT NULL DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 ROW_FORMAT=DYNAMIC;

-- --------------------------------------------------------

--
-- Table structure for table `tl_com_wallet_bank_information`
--

CREATE TABLE `tl_com_wallet_bank_information` (
  `id` int(11) NOT NULL,
  `payment_method_id` int(11) DEFAULT NULL,
  `bank_name` varchar(150) DEFAULT NULL,
  `account_name` varchar(200) DEFAULT NULL,
  `account_number` varchar(250) DEFAULT NULL,
  `routing_number` varchar(150) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `tl_com_wallet_payment_methods`
--

CREATE TABLE `tl_com_wallet_payment_methods` (
  `id` int(11) NOT NULL,
  `type` int(11) NOT NULL,
  `name` varchar(250) NOT NULL,
  `logo` int(11) DEFAULT NULL,
  `instruction` text DEFAULT NULL,
  `status` int(11) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `tl_com_wallet_recharges`
--

CREATE TABLE `tl_com_wallet_recharges` (
  `id` int(11) NOT NULL,
  `entry_type` int(11) DEFAULT NULL COMMENT 'credit or debit',
  `recharge_type` int(11) DEFAULT NULL COMMENT 'online or offline recharge, manual, cart, cashback, refunds',
  `customer_id` int(11) DEFAULT NULL,
  `transaction_id` varchar(400) DEFAULT NULL,
  `payment_method_id` int(11) DEFAULT NULL,
  `recharge_amount` double DEFAULT 0,
  `document` text DEFAULT NULL,
  `added_by` int(11) DEFAULT NULL,
  `status` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `tl_countries`
--

CREATE TABLE `tl_countries` (
  `id` int(11) NOT NULL,
  `name` varchar(50) DEFAULT NULL,
  `code` varchar(50) DEFAULT NULL,
  `phone_code` varchar(15) DEFAULT NULL,
  `flag` text DEFAULT NULL,
  `status` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `tl_countries`
--

INSERT INTO `tl_countries` (`id`, `name`, `code`, `phone_code`, `flag`, `status`, `created_at`, `updated_at`) VALUES
(1, 'Kuwait', 'KW', NULL, NULL, 1, '2021-04-06 07:06:30', '2023-02-07 06:27:13');

-- --------------------------------------------------------

--
-- Table structure for table `tl_email_templates`
--

CREATE TABLE `tl_email_templates` (
  `id` int(11) NOT NULL,
  `name` varchar(150) DEFAULT NULL,
  `details` varchar(150) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp(),
  `module_name` varchar(150) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `tl_email_templates`
--

INSERT INTO `tl_email_templates` (`id`, `name`, `details`, `created_at`, `updated_at`, `module_name`) VALUES
(1, 'Blog Comment', 'Blog Comment Email Template', '2022-05-19 04:58:29', '2023-01-03 04:02:48', 'admin_panel'),
(2, 'Reset Admin Passweord', 'Reset Admin Password Email Template', '2022-12-28 11:02:22', '2023-01-03 04:03:13', 'admin_panel'),
(6, 'Custom Notifications', 'Send custom notification to all kind of users', '2023-01-15 11:06:30', '2023-01-15 11:14:04', 'ecommerce'),
(7, 'Customer Forgot Password', 'Customer forgot password template', '2023-01-16 09:10:42', '2023-01-16 10:33:15', 'ecommerce'),
(8, 'Customer Reset Email', 'Customer Reset Email Mail Template', '2023-01-16 10:35:00', '2023-01-16 10:35:20', 'ecommerce'),
(9, 'Customer Email Verification', 'Customer email verification email template', '2023-01-16 10:56:51', NULL, 'ecommerce'),
(10, 'Order Confirmation ', 'Order confirmation mail send to customer', '2023-01-17 04:33:34', NULL, 'ecommerce'),
(11, 'Order Status Update Mail', 'Send mail to customer when order status updated', '2023-01-17 10:16:23', '2023-01-17 10:24:35', 'ecommerce'),
(12, 'Refund Request Email', 'Refund request status update mail', '2023-01-17 11:23:32', NULL, 'ecommerce'),
(13, 'New Order Mail', 'New order mail send to Admin', '2023-06-15 06:10:57', NULL, 'ecommerce'),
(14, 'Admin General Email', 'Email template for admin for multipurpose', '2023-06-15 09:16:58', '2023-06-15 09:21:57', 'ecommerce'),
(15, 'Customers Feedback Email', 'Email template for customers feedback', '2023-06-15 09:16:58', '2023-06-15 09:21:57', 'ecommerce'),
(16, 'Customer Review Email', 'Email template for customers review', '2023-06-15 09:16:58', '2023-06-15 09:21:57', 'ecommerce');
-- --------------------------------------------------------

--
-- Table structure for table `tl_email_template_properties`
--

CREATE TABLE `tl_email_template_properties` (
  `id` int(11) NOT NULL,
  `email_type` int(11) NOT NULL DEFAULT 0,
  `subject` text NOT NULL,
  `body` longtext NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `tl_email_template_properties`
--

INSERT INTO `tl_email_template_properties` (`id`, `email_type`, `subject`, `body`, `created_at`, `updated_at`) VALUES
(1, 1, 'Blog Comment', '<table style=\"@import url(https://fonts.googleapis.com/css?family=Rubik:300,400,500,700|Open+Sans:300,400,600,700); font-family: \'Open Sans\', sans-serif;\" width=\"100%\" cellspacing=\"0\" cellpadding=\"0\" border=\"0\" bgcolor=\"#f2f3f8\">\n        <tbody><tr>\n            <td>\n   <table style=\"background-color: #f2f3f8; max-width:670px;  margin:0 auto;\" width=\"95%\" cellspacing=\"0\" cellpadding=\"0\" border=\"0\" align=\"center\">\n                    <tbody><tr>\n                        <td style=\"height:80px;\"> </td>\n                    </tr>\n                    <tr>\n                        <td style=\"text-align:center;background-color:#ef2543;padding:10px;width:95%\">\n                            <a title=\"logo\" href=\"_site_link_\">\n                            <img alt=\"logo\" title=\"logo\" src=\"_system_logo_url_\" width=\"180\">\n                          </a>\n                        </td>\n                    </tr>\n                    <tr>\n                        <td>\n                            <table style=\"max-width:670px;background:#fff; border-radius:3px; text-align:center;-webkit-box-shadow:0 6px 18px 0 rgba(0,0,0,.06);-moz-box-shadow:0 6px 18px 0 rgba(0,0,0,.06);box-shadow:0 6px 18px 0 rgba(0,0,0,.06);\" width=\"100%\" cellspacing=\"0\" cellpadding=\"0\" border=\"0\" align=\"center\">\n                                <tbody><tr>\n                                    <td style=\"height:40px;\"> </td>\n                                </tr>\n                                <tr>\n                                    <td style=\"padding:0 35px;\">\n                                        <h1 style=\"color:#1e1e2d; font-weight:500; margin:0;font-size:25px;font-family:\'Rubik\',sans-serif; text-align: center;\">_comment_status_</h1>\n                                        <span style=\"display:inline-block; vertical-align:middle; margin:29px 0 26px; border-bottom:1px solid #cecece; width:100px;\"></span>\n                                        <p style=\"color:#455056; font-size:15px;line-height:24px; margin:0; text-align: center;\">\n                                            A new Comment post on <a style=\"color:#455056; text-decoration:none !important;\" href=\"_blog_link_\">\n                          <strong>_blog_name_ </strong>\n                        </a> by <strong>_author_name_ </strong>\n                                        </p>\n                                        <p style=\"color:#455056; font-size:15px;font-style:italic; line-height:24px; margin:10px 0px;text-align: center;\"> “_main_comment_” </p>\n                                        <a style=\"background:#ef2543;text-decoration:none !important; font-weight:500; margin-top:35px; color:#fff;text-transform:uppercase; font-size:14px;padding:10px 24px;display:inline-block;border-radius:5px;\" href=\"_comment_link_\">\n                                        View Comment\n                                        </a>\n                                    </td>\n                                </tr>\n                                <tr>\n                                    <td style=\"height:40px;\"> </td>\n                                </tr>\n                            </tbody></table>\n                        </td>\n                         \n                            </tr><tr>\n                                <td style=\"text-align: center;text-align:center;background-color:#3a3a3abf;padding:18px;width:95%\">\n                                    <a style=\"line-height:18px; margin:0 0 0; text-align: center;color:#dbe5eb; text-decoration:none !important;font-size:14px\" href=\"_site_link_\"> \n                                    _footer_text_\n                                     </a>\n                                </td>\n                            </tr>\n                         \n                        <tr>\n                            <td style=\"height:80px;\"> </td>\n                        </tr>\n                </tbody></table>\n            </td>\n            </tr>\n    </tbody></table>', '2022-12-28 11:04:02', '2023-11-28 03:25:27'),
(2, 2, 'Reset Admin Password', '<table style=\"@import url(https://fonts.googleapis.com/css?family=Rubik:300,400,500,700|Open+Sans:300,400,600,700); font-family: \'Open Sans\', sans-serif;\" width=\"100%\" cellspacing=\"0\" cellpadding=\"0\" border=\"0\" bgcolor=\"#f2f3f8\">\n        <tbody><tr>\n            <td>\n   <table style=\"background-color: #f2f3f8; max-width:670px;  margin:0 auto;\" width=\"95%\" cellspacing=\"0\" cellpadding=\"0\" border=\"0\" align=\"center\">\n                    <tbody><tr>\n                        <td style=\"height:80px;\"> </td>\n                    </tr>\n                    <tr>\n                        <td style=\"text-align:center;background-color:#ef2543;padding:10px;width:95%\">\n                            <a title=\"logo\" href=\"_site_link_\">\n                                <img alt=\"logo\" title=\"logo\" src=\"_system_logo_url_\" width=\"180\">\n                          </a>\n                        </td>\n                    </tr>\n                    <tr>\n                        <td>\n                            <table style=\"max-width:670px;background:#fff; border-radius:3px; text-align:center;-webkit-box-shadow:0 6px 18px 0 rgba(0,0,0,.06);-moz-box-shadow:0 6px 18px 0 rgba(0,0,0,.06);box-shadow:0 6px 18px 0 rgba(0,0,0,.06);\" cellspacing=\"0\" cellpadding=\"0\" border=\"0\" align=\"center\">\n                                <tbody><tr>\n                                    <td style=\"height:40px;\"> </td>\n                                </tr>\n                                <tr>\n                                    <td style=\"padding:0 35px;\">\n                                        <h1 style=\"color:#1e1e2d; font-weight:500; margin:0;font-size:25px;font-family:\'Rubik\',sans-serif;\">You have requested to reset your password</h1>\n                                        <span style=\"display:inline-block; vertical-align:middle; margin:29px 0 26px; border-bottom:1px solid #cecece; width:100px;\"></span>\n                                        <p style=\"color:#455056; font-size:15px;line-height:24px; margin:0;\">\n                                          We cannot simply send you your old password. A unique link to reset your password has been generated for you. To reset your password, click the following link and follow the instructions. \n                                        </p>\n                                        <a style=\"background:#ef2543;text-decoration:none !important; font-weight:500; margin-top:35px; color:#fff;text-transform:uppercase; font-size:14px;padding:10px 24px;display:inline-block;border-radius:5px;\" href=\"_reset_password_link_\">\n                                        Reset Password\n                                        </a>\n                                    </td>\n                                </tr>\n                                <tr>\n                                    <td style=\"height:40px;\"> </td>\n                                </tr>\n                            </tbody></table>\n                        </td>\n                         \n                            </tr><tr>\n                                <td style=\"text-align: center;text-align:center;background-color:#3a3a3abf;padding:18px;width:95%;\">\n                                    <a style=\"line-height:18px; margin:0 0 0; text-align: center;color:#dbe5eb; text-decoration:none !important;font-size:14px\" href=\"_site_link_\"> \n                                    _footer_text_\n                                     </a>\n                                </td>\n                            </tr>\n                         \n                        <tr>\n                            <td style=\"height:80px;\"> </td>\n                        </tr>\n                </tbody></table>\n            </td>\n            </tr>\n    </tbody></table>', '2023-01-02 04:11:38', '2023-11-28 03:25:49'),
(3, 6, 'Custom Notification', '<table style=\"@import url(https://fonts.googleapis.com/css?family=Rubik:300,400,500,700|Open+Sans:300,400,600,700); font-family: \'Open Sans\', sans-serif;\" width=\"100%\" cellspacing=\"0\" cellpadding=\"0\" border=\"0\" bgcolor=\"#f2f3f8\">\n        <tbody><tr>\n            <td>\n                <table style=\"background-color: #f2f3f8; max-width:670px;  margin:0 auto;\" width=\"95%\" cellspacing=\"0\" cellpadding=\"0\" border=\"0\" align=\"center\">\n                    <tbody><tr>\n                        <td style=\"height:80px;\"> </td>\n                    </tr>\n                    \n                    <tr>\n                        <td style=\"text-align:center;background-color:#ef2543;padding:10px;width:95%\">\n                            <a title=\"logo\" href=\"_site_link_\">\n                            <img alt=\"logo\" title=\"logo\" src=\"_system_logo_url_\" width=\"180\">\n                          </a>\n                        </td>\n                    </tr>\n                    \n                    <tr>\n                        <td>\n                            <table style=\"max-width:670px;background:#fff; border-radius:3px; text-align:center;-webkit-box-shadow:0 6px 18px 0 rgba(0,0,0,.06);-moz-box-shadow:0 6px 18px 0 rgba(0,0,0,.06);box-shadow:0 6px 18px 0 rgba(0,0,0,.06);\" width=\"100%\" cellspacing=\"0\" cellpadding=\"0\" border=\"0\" align=\"center\">\n                                <tbody><tr>\n                                    <td style=\"height:40px;\"> </td>\n                                </tr>\n                                <tr>\n                                    <td style=\"padding:0 35px;\">\n                                        _content_\n                                    </td>\n                                </tr>\n                                <tr>\n                                    <td style=\"height:40px;\"> </td>\n                                </tr>\n                            </tbody></table>\n                        </td>\n                    </tr>\n                    \n                    <tr>\n                        <td style=\"text-align: center;text-align:center;background-color:#3a3a3abf;padding:18px;width:95%\">\n                            <a style=\"line-height:18px; margin:0 0 0; text-align: center;color:#dbe5eb; text-decoration:none !important;font-size:14px\" href=\"_site_link_\"> \n                            _footer_text_\n                             </a>\n                        </td>\n                    </tr>\n                    \n                    <tr>\n                        <td style=\"height:80px;\"> </td>\n                    </tr>\n                </tbody></table>\n            </td>\n    </tr></tbody></table>', '2023-01-15 11:07:48', '2023-11-28 03:22:23'),
(4, 7, 'Forgot Password', '<table style=\"@import url(https://fonts.googleapis.com/css?family=Rubik:300,400,500,700|Open+Sans:300,400,600,700); font-family: \'Open Sans\', sans-serif;\" width=\"100%\" cellspacing=\"0\" cellpadding=\"0\" border=\"0\" bgcolor=\"#f2f3f8\">\n        <tbody><tr>\n            <td>\n   <table style=\"background-color: #f2f3f8; max-width:670px;  margin:0 auto;\" width=\"95%\" cellspacing=\"0\" cellpadding=\"0\" border=\"0\" align=\"center\">\n                    <tbody><tr>\n                        <td style=\"height:80px;\"> </td>\n                    </tr>\n                    <tr>\n                        <td style=\"text-align:center;background-color:#ef2543;padding:10px;width:95%\">\n                            <a title=\"logo\" href=\"_site_link_\">\n                            <img alt=\"logo\" title=\"logo\" src=\"_system_logo_url_\" width=\"180\">\n                          </a>\n                        </td>\n                    </tr>\n                    <tr>\n                        <td>\n                            <table style=\"max-width:670px;background:#fff; border-radius:3px; text-align:center;-webkit-box-shadow:0 6px 18px 0 rgba(0,0,0,.06);-moz-box-shadow:0 6px 18px 0 rgba(0,0,0,.06);box-shadow:0 6px 18px 0 rgba(0,0,0,.06);\" cellspacing=\"0\" cellpadding=\"0\" border=\"0\" align=\"center\">\n                                <tbody><tr>\n                                    <td style=\"height:40px;\"> </td>\n                                </tr>\n                                <tr>\n                                    <td style=\"padding:0 35px;\">\n                                        <h1 style=\"color:#1e1e2d; font-weight:500; margin:0;font-size:25px;font-family:\'Rubik\',sans-serif;\">You have requested to reset your password</h1>\n                                        <span style=\"display:inline-block; vertical-align:middle; margin:29px 0 26px; border-bottom:1px solid #cecece; width:100px;\"></span>\n                                        <p style=\"color:#455056; font-size:15px;line-height:24px; margin:0;\">\n                                            You‘ve requested to reset your _system_name_ password for _customer_email_. If you didn’t request this you can safely ignore this email.\n                                        </p>\n                                        <a style=\"background:#ef2543;text-decoration:none !important; font-weight:500; margin-top:35px; color:#fff;text-transform:uppercase; font-size:14px;padding:10px 24px;display:inline-block;border-radius:5px;\" href=\"_reset_url_\">\n                                        Reset Password\n                                        </a>\n                                    </td>\n                                </tr>\n                                <tr>\n                                    <td style=\"height:40px;\"> </td>\n                                </tr>\n                            </tbody></table>\n                        </td>\n                         \n                            </tr><tr>\n                                <td style=\"text-align: center;text-align:center;background-color:#3a3a3abf;padding:18px;width:95%\">\n                                    <a style=\"line-height:18px; margin:0 0 0; text-align: center;color:#dbe5eb; text-decoration:none !important;font-size:14px\" href=\"_site_link_\"> \n                                    _footer_text_\n                                     </a>\n                                </td>\n                            </tr>\n                         \n                        <tr>\n                            \n                            <td style=\"height:80px;\"> </td>\n                        </tr>\n                </tbody></table>\n            </td>\n            </tr>\n    </tbody></table>', '2023-01-16 09:23:56', '2023-11-28 03:23:14'),
(5, 8, 'Reset Email Request', '<table style=\"@import url(https://fonts.googleapis.com/css?family=Rubik:300,400,500,700|Open+Sans:300,400,600,700); font-family: \'Open Sans\', sans-serif;\" width=\"100%\" cellspacing=\"0\" cellpadding=\"0\" border=\"0\" bgcolor=\"#f2f3f8\">\n        <tbody><tr>\n            <td>\n                <table style=\"background-color: #f2f3f8; max-width:670px;  margin:0 auto;\" width=\"95%\" cellspacing=\"0\" cellpadding=\"0\" border=\"0\" align=\"center\">\n                    <tbody><tr>\n                        <td style=\"height:80px;\"> </td>\n                    </tr>\n                    <tr>\n                        <td style=\"text-align:center;background-color:#ef2543;padding:10px;width:95%\">\n                            <a title=\"logo\" href=\"_site_link_\">\n                            <img alt=\"logo\" title=\"logo\" src=\"_system_logo_url_\" width=\"180\">\n                          </a>\n                        </td>\n                    </tr>\n                    <tr>\n                        <td>\n                            <table style=\"max-width:670px;background:#fff; border-radius:3px; text-align:center;-webkit-box-shadow:0 6px 18px 0 rgba(0,0,0,.06);-moz-box-shadow:0 6px 18px 0 rgba(0,0,0,.06);box-shadow:0 6px 18px 0 rgba(0,0,0,.06);\" width=\"100%\" cellspacing=\"0\" cellpadding=\"0\" border=\"0\" align=\"center\">\n                                <tbody><tr>\n                                    <td style=\"height:40px;\"> </td>\n                                </tr>\n                                <tr>\n                                    <td style=\"padding:0 35px;\">\n                                        <h1 style=\"color:#1e1e2d; font-weight:500; margin:0;font-size:25px;font-family:\'Rubik\',sans-serif;\">You have requested to change your email</h1>\n                                        <span style=\"display:inline-block; vertical-align:middle; margin:29px 0 26px; border-bottom:1px solid #cecece; width:100px;\"></span>\n                                        <p style=\"color:#455056; font-size:15px;line-height:24px; margin:0;\">\n                                            You‘ve requested to change your _system_name_ email. If you didn’t request this you can safely ignore this email.\n                                        </p>\n                                        <a style=\"background:#ef2543;text-decoration:none !important; font-weight:500; margin-top:35px; color:#fff;text-transform:uppercase; font-size:14px;padding:10px 24px;display:inline-block;border-radius:5px;\" href=\"_reset_url_\">Reset Email</a>\n                                    </td>\n                                </tr>\n                                <tr>\n                                    <td style=\"height:40px;\"> </td>\n                                </tr>\n                            </tbody></table>\n                        </td>\n                         \n                        </tr><tr>\n                            <td style=\"text-align: center;text-align:center;background-color:#3a3a3abf;padding:18px;width:95%\">\n                                <a style=\"line-height:18px; margin:0 0 0; text-align: center;color:#dbe5eb; text-decoration:none !important;font-size:14px\" href=\"_site_link_\"> \n                                _footer_text_\n                                 </a>\n                            </td>\n                        </tr>\n                        \n                        <tr>\n                            <td style=\"height:80px;\"> </td>\n                        </tr>\n                </tbody></table>\n            </td>\n            </tr>\n    </tbody></table>', '2023-01-16 10:38:59', '2023-11-28 03:23:31'),
(6, 9, 'Email Verification', '<table style=\"@import url(https://fonts.googleapis.com/css?family=Rubik:300,400,500,700|Open+Sans:300,400,600,700); font-family: \'Open Sans\', sans-serif;\" width=\"100%\" cellspacing=\"0\" cellpadding=\"0\" border=\"0\" bgcolor=\"#f2f3f8\">\n        <tbody><tr>\n            <td>\n                   <table style=\"background-color: #f2f3f8; max-width:670px;  margin:0 auto;\" width=\"95%\" cellspacing=\"0\" cellpadding=\"0\" border=\"0\" align=\"center\">\n                    <tbody><tr>\n                        <td style=\"height:80px;\"> </td>\n                    </tr>\n                    <tr>\n                        <td style=\"text-align:center;background-color:#ef2543;padding:10px;width:95%\">\n                            <a title=\"logo\" href=\"_site_link_\">\n                            <img alt=\"logo\" title=\"logo\" src=\"_system_logo_url_\" width=\"180\">\n                          </a>\n                        </td>\n                    </tr>\n                    <tr>\n                        <td>\n                            <table style=\"max-width:670px;background:#fff; border-radius:3px; text-align:center;-webkit-box-shadow:0 6px 18px 0 rgba(0,0,0,.06);-moz-box-shadow:0 6px 18px 0 rgba(0,0,0,.06);box-shadow:0 6px 18px 0 rgba(0,0,0,.06);\" cellspacing=\"0\" cellpadding=\"0\" border=\"0\" align=\"center\">\n                                <tbody><tr>\n                                    <td style=\"height:40px;\"> </td>\n                                </tr>\n                                <tr>\n                                    <td style=\"padding:0 35px;\">\n                                        <h2 style=\"color:#1e1e2d; font-weight:500; margin:0;font-size:25px;font-family:\'Rubik\',sans-serif;\">Verify your email address</h2>\n                                        <span style=\"display:inline-block; vertical-align:middle; margin:29px 0 26px; border-bottom:1px solid #cecece; width:100px;\"></span>\n                                        <p style=\"color:#455056; font-size:15px;line-height:24px; margin:0;\">\n                                          Thanks for starting the new _system_name_ account creation process. We want to make sure it\'s really you. Please click the verify email button. If you don’t want to create an account, you can ignore this message.\n                                        </p>\n                                        <a style=\"background:#ef2543;text-decoration:none !important; font-weight:500; margin-top:35px; color:#fff;text-transform:uppercase; font-size:14px;padding:10px 24px;display:inline-block;border-radius:5px;\" href=\"_email_verify_link_\">Verify Email</a>\n                                    </td>\n                                </tr>\n                                <tr>\n                                    <td style=\"height:40px;\"> </td>\n                                </tr>\n                            </tbody></table>\n                        </td>\n                        \n                        </tr><tr>\n                            <td style=\"text-align: center;text-align:center;background-color:#3a3a3abf;padding:18px;width:95%\">\n                                <a style=\"line-height:18px; margin:0 0 0; text-align: center;color:#dbe5eb; text-decoration:none !important;font-size:14px\" href=\"_site_link_\"> \n                                _footer_text_\n                                 </a>\n                            </td>\n                        </tr>\n                        \n                        <tr>\n                            \n                            <td style=\"height:80px;\"> </td>\n                        </tr>\n                </tbody></table>\n            </td>\n            </tr>\n    </tbody></table>', '2023-01-16 11:01:54', '2023-11-28 03:23:59'),
(7, 10, 'Order Accepted', '<table style=\"@import url(https://fonts.googleapis.com/css?family=Rubik:300,400,500,700|Open+Sans:300,400,600,700); font-family: \'Open Sans\', sans-serif;\" width=\"100%\" cellspacing=\"0\" cellpadding=\"0\" border=\"0\" bgcolor=\"#f2f3f8\">\n        <tbody><tr>\n            <td>\n                <table style=\"background-color: #f2f3f8; max-width:670px;  margin:0 auto;\" width=\"95%\" cellspacing=\"0\" cellpadding=\"0\" border=\"0\" align=\"center\">\n                    <tbody><tr>\n                        <td style=\"height:80px;\"> </td>\n                    </tr>\n                    \n                    <tr>\n                        <td style=\"text-align:center;background-color:#ef2543;padding:10px;width:95%\">\n                            <a title=\"logo\" href=\"_site_link_\">\n                            <img alt=\"logo\" title=\"logo\" src=\"_system_logo_url_\" width=\"180\">\n                          </a>\n                        </td>\n                    </tr>\n                    \n                    \n                    <tr>\n                        <td style=\"padding:25px 25px;background:#fff\">\n                            <div style=\"text-align:center\">\n                                <h1 style=\"color:#1e1e2d; font-weight:500; margin:0;font-size:25px;font-family:\'Rubik\',sans-serif;\">Thank you for your order!</h1>\n                                <span style=\"display:inline-block; vertical-align:middle; margin:29px 0 26px; border-bottom:1px solid #cecece; width:100px;\"></span>\n                            </div>\n\n                            <div class=\"content-body\">\n                                <p style=\"color:#455056; font-size:15px;line-height:24px; margin:0;\">\n                                    Hi _customer_name_,\n                                </p>\n                                <p style=\"color:#455056; font-size:15px;line-height:24px; margin:0;\">\n                                    Your order _order_code_ has been placed successfully and we will let you know once your package is on its way. Check the status of your order using the tracking link below to receive real-time updates of your order.\n\n                                </p>\n                            </div>\n                            <div style=\"text-align:center\">\n                                <a style=\"background:#ef2543;text-decoration:none !important; font-weight:500; margin-top:35px; color:#fff;text-transform:uppercase; font-size:14px;padding:10px 24px;display:inline-block;border-radius:5px;\" href=\"_tracking_url_\">Track Your Order</a>\n                            </div>\n                        </td>\n\n                    </tr>\n                    <tr>\n                        \n                        <td>\n                            _order_details_\n                        </td>\n                    </tr>\n                    \n                    \n                    <tr>\n                        <td style=\"margin-top:20px;text-align: center;text-align:center;background-color:#3a3a3abf;padding:18px;width:95%\">\n                            <a style=\"line-height:18px; margin:0 0 0; text-align: center;color:#dbe5eb; text-decoration:none !important;font-size:14px\" href=\"_site_link_\"> \n                            _footer_text_\n                             </a>\n                        </td>\n                    </tr>\n                    \n                    <tr>\n                        <td style=\"height:80px;\"> </td>\n                    </tr>\n                </tbody></table>\n            </td>\n        </tr>\n    </tbody></table>', '2023-01-17 04:56:37', '2023-11-28 03:19:48'),
(8, 11, 'Order status updated', '<table style=\"@import url(https://fonts.googleapis.com/css?family=Rubik:300,400,500,700|Open+Sans:300,400,600,700); font-family: \'Open Sans\', sans-serif;\" width=\"100%\" cellspacing=\"0\" cellpadding=\"0\" border=\"0\" bgcolor=\"#f2f3f8\">\n        <tbody><tr>\n            <td>\n                <table style=\"background-color: #f2f3f8; max-width:670px;  margin:0 auto;\" width=\"95%\" cellspacing=\"0\" cellpadding=\"0\" border=\"0\" align=\"center\">\n                    <tbody><tr>\n                        <td style=\"height:80px;\"> </td>\n                    </tr>\n                    \n                    <tr>\n                        <td style=\"text-align:center;background-color:#ef2543;padding:10px;width:95%\">\n                            <a title=\"logo\" href=\"_site_link_\">\n                            <img alt=\"logo\" title=\"logo\" src=\"_system_logo_url_\" width=\"180\">\n                          </a>\n                        </td>\n                    </tr>\n                    \n                    \n                    <tr>\n                        <td style=\"padding:25px 25px;background:#fff\">\n                            <div style=\"text-align:center\">\n                                <h1 style=\"color:#1e1e2d; font-weight:500; margin:0;font-size:25px;font-family:\'Rubik\',sans-serif;\">_mail_title_</h1>\n                                <span style=\"display:inline-block; vertical-align:middle; margin:29px 0 26px; border-bottom:1px solid #cecece; width:100px;\"></span>\n                            </div>\n\n                            <div class=\"content-body\">\n                                <p style=\"color:#455056; font-size:15px;line-height:24px; margin:0;\">\n                                    Hi _customer_name_,\n                                </p>\n                                <p style=\"color:#455056; font-size:15px;line-height:24px; margin:0;\">\n                                  _message_\n                                </p>\n                            </div>\n                            <div style=\"text-align:center\">\n                                <a style=\"background:#ef2543;text-decoration:none !important; font-weight:500; margin-top:35px; color:#fff;text-transform:uppercase; font-size:14px;padding:10px 24px;display:inline-block;border-radius:5px;\" href=\"_tracking_url_\">_btn_title_</a>\n                            </div>\n                        </td>\n                    </tr>\n                    \n                    \n                    <tr>\n                        <td style=\"text-align: center;text-align:center;background-color:#3a3a3abf;padding:18px;width:95%\">\n                            <a style=\"line-height:18px; margin:0 0 0; text-align: center;color:#dbe5eb; text-decoration:none !important;font-size:14px\" href=\"_site_link_\"> \n                            _footer_text_\n                             </a>\n                        </td>\n                    </tr>\n                    \n                    <tr>\n                        <td style=\"height:80px;\"> </td>\n                    </tr>\n                </tbody></table>\n            </td>\n    </tr></tbody></table>', '2023-01-17 10:19:24', '2023-11-28 03:21:40'),
(9, 12, 'Refund Request Updated', '<table style=\"@import url(https://fonts.googleapis.com/css?family=Rubik:300,400,500,700|Open+Sans:300,400,600,700); font-family: \'Open Sans\', sans-serif;\" width=\"100%\" cellspacing=\"0\" cellpadding=\"0\" border=\"0\" bgcolor=\"#f2f3f8\">\n        <tbody><tr>\n            <td>\n                <table style=\"background-color: #f2f3f8; max-width:670px;  margin:0 auto;\" width=\"95%\" cellspacing=\"0\" cellpadding=\"0\" border=\"0\" align=\"center\">\n                    <tbody><tr>\n                        <td style=\"height:80px;\"> </td>\n                    </tr>\n                    \n                    <tr>\n                        <td style=\"text-align:center;background-color:#ef2543;padding:10px;width:95%\">\n                            <a title=\"logo\" href=\"_site_link_\">\n                            <img alt=\"logo\" title=\"logo\" src=\"_system_logo_url_\" width=\"180\">\n                          </a>\n                        </td>\n                    </tr>\n                    \n                    \n                    <tr>\n                        <td style=\"padding:25px 25px;background:#fff\">\n                            <div style=\"text-align:center\">\n                                <h1 style=\"color:#1e1e2d; font-weight:500; margin:0;font-size:25px;font-family:\'Rubik\',sans-serif;\">_mail_title_</h1>\n                                <span style=\"display:inline-block; vertical-align:middle; margin:29px 0 26px; border-bottom:1px solid #cecece; width:100px;\"></span>\n                            </div>\n\n                            <div class=\"content-body\">\n                                <p style=\"color:#455056; font-size:15px;line-height:24px; margin:0;\">\n                                    Hi _customer_name_,\n                                </p>\n                                <p style=\"color:#455056; font-size:15px;line-height:24px; margin:0;\">\n                                   _message_\n                                </p>\n                            </div>\n                            <div style=\"text-align:center\">\n                                <a style=\"background:#ef2543;text-decoration:none !important; font-weight:500; margin-top:35px; color:#fff;text-transform:uppercase; font-size:14px;padding:10px 24px;display:inline-block;border-radius:5px;\" href=\"_tracking_url_\">Track Your Request</a>\n                            </div>\n                        </td>\n                    </tr>\n                    \n                    \n                    <tr>\n                        <td style=\"text-align: center;text-align:center;background-color:#3a3a3abf;padding:18px;width:95%\">\n                            <a style=\"line-height:18px; margin:0 0 0; text-align: center;color:#dbe5eb; text-decoration:none !important;font-size:14px\" href=\"_site_link_\"> \n                            _footer_text_\n                             </a>\n                        </td>\n                    </tr>\n                    \n                    <tr>\n                        <td style=\"height:80px;\"> </td>\n                    </tr>\n                </tbody></table>\n            </td>\n    </tr></tbody></table>', '2023-01-17 11:28:20', '2023-11-28 03:20:43'),
(10, 13, 'New Order', '<table cellspacing=\"0\" border=\"0\" cellpadding=\"0\" width=\"100%\" bgcolor=\"#f2f3f8\" style=\"@import url(https://fonts.googleapis.com/css?family=Rubik:300,400,500,700|Open+Sans:300,400,600,700); font-family: \'Open Sans\', sans-serif;\">\n        <tbody><tr>\n            <td>\n                <table style=\"background-color: #f2f3f8; max-width:670px;  margin:0 auto;\" width=\"95%\" border=\"0\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\">\n                    <tbody><tr>\n                        <td style=\"height:80px;\"> </td>\n                    </tr>\n                    \n                    <tr>\n                        <td style=\"text-align:center;background-color:#ef2543;padding:10px;width:95%\">\n                            <a href=\"_site_link_\" title=\"logo\">\n                            <img width=\"180\" src=\"_system_logo_url_\" title=\"logo\" alt=\"logo\">\n                          </a>\n                        </td>\n                    </tr>\n                    \n                    \n                    <tr>\n                        <td style=\"padding:25px 25px;background:#fff\">\n                            <div style=\"text-align:center\">\n                                <h1 style=\"color:#1e1e2d; font-weight:500; margin:0;font-size:25px;font-family:\'Rubik\',sans-serif;\">New Order Placed!</h1>\n                                <span style=\"display:inline-block; vertical-align:middle; margin:29px 0 26px; border-bottom:1px solid #cecece; width:100px;\"></span>\n                            </div>\n\n                            <div class=\"content-body\">\n                                <p style=\"color:#455056; font-size:15px;line-height:24px; margin:0;\">\n                                New order  _order_code_  has been placed successfully.\n                                </p>\n                            </div>\n                            <div style=\"text-align:center\">\n                                <a href=\"_tracking_url_\" style=\"background:#ef2543;text-decoration:none !important; font-weight:500; margin-top:35px; color:#fff;text-transform:uppercase; font-size:14px;padding:10px 24px;display:inline-block;border-radius:5px;\">View Order Details</a>\n                            </div>\n                        </td>\n\n                    </tr>\n                    <tr>\n                        \n                        <td>\n                            _order_details_\n                        </td>\n                    </tr>\n                    \n                    \n                    <tr>\n                        <td style=\"margin-top:20px;text-align: center;text-align:center;background-color:#3a3a3abf;padding:18px;width:95%\">\n                            <a href=\"_site_link_\" style=\"line-height:18px; margin:0 0 0; text-align: center;color:#dbe5eb; text-decoration:none !important;font-size:14px\"> \n                            _footer_text_\n                             </a>\n                        </td>\n                    </tr>\n                    \n                    <tr>\n                        <td style=\"height:80px;\"> </td>\n                    </tr>\n                </tbody></table>\n            </td>\n        </tr>\n    </tbody></table>', '2023-06-15 06:17:34', '2023-06-15 06:25:18'),
(11, 14, 'Admin General Message', '<table style=\"@import url(https://fonts.googleapis.com/css?family=Rubik:300,400,500,700|Open+Sans:300,400,600,700); font-family: \'Open Sans\', sans-serif;\" bgcolor=\"#f2f3f8\" width=\"100%\" cellpadding=\"0\" border=\"0\" cellspacing=\"0\">\n        <tbody><tr>\n            <td>\n                <table cellspacing=\"0\" cellpadding=\"0\" align=\"center\" border=\"0\" width=\"95%\" style=\"background-color: #f2f3f8; max-width:670px;  margin:0 auto;\">\n                    <tbody><tr>\n                        <td style=\"height:80px;\"> </td>\n                    </tr>\n                    \n                    <tr>\n                        <td style=\"text-align:center;background-color:#ef2543;padding:10px;width:95%\">\n                            <a title=\"logo\" href=\"_site_link_\">\n                            <img alt=\"logo\" title=\"logo\" src=\"_system_logo_url_\" width=\"180\">\n                          </a>\n                        </td>\n                    </tr>\n                    \n                    \n                    <tr>\n                        <td style=\"padding:25px 25px;background:#fff\">\n                            <div style=\"text-align:center\">\n                                <h1 style=\"color:#1e1e2d; font-weight:500; margin:0;font-size:25px;font-family:\'Rubik\',sans-serif;\">_mail_title_</h1>\n                                <span style=\"display:inline-block; vertical-align:middle; margin:29px 0 26px; border-bottom:1px solid #cecece; width:100px;\"></span>\n                            </div>\n\n                            <div class=\"content-body\">\n                                <p style=\"color:#455056; font-size:15px;line-height:24px; margin:0;float:center\">\n                                  _message_\n                                </p>\n                            </div>\n                            <div style=\"text-align:center\">\n                                <a style=\"background:#ef2543;text-decoration:none !important; font-weight:500; margin-top:35px; color:#fff;text-transform:uppercase; font-size:14px;padding:10px 24px;display:inline-block;border-radius:5px;\" href=\"_action_url_\">_btn_title_</a>\n                            </div>\n                        </td>\n                    </tr>\n                    \n                    \n                    <tr>\n                        <td style=\"text-align: center;text-align:center;background-color:#3a3a3abf;padding:18px;width:95%\">\n                            <a style=\"line-height:18px; margin:0 0 0; text-align: center;color:#dbe5eb; text-decoration:none !important;font-size:14px\" href=\"_site_link_\"> \n                            _footer_text_\n                             </a>\n                        </td>\n                    </tr>\n                    \n                    <tr>\n                        <td style=\"height:80px;\"> </td>\n                    </tr>\n                </tbody></table>\n            </td>\n    </tr></tbody></table>', '2023-06-15 09:21:35', '2023-06-15 09:23:43'),
(12, 15, 'New Customer Feedback', '<table cellspacing="0" border="0" cellpadding="0" width="100%" bgcolor="#f2f3f8" style="@import url(https://fonts.googleapis.com/css?family=Rubik:300,400,500,700|Open+Sans:300,400,600,700); font-family: ''Open Sans'', sans-serif;">
        <tbody><tr>
            <td>
                <table style="background-color: #f2f3f8; max-width:670px;  margin:0 auto;" width="95%" border="0" align="center" cellpadding="0" cellspacing="0">
                    <tbody><tr>
                        <td style="height:80px;"> </td>
                    </tr>
                    
                    <tr>
                        <td style="text-align:center;background-color:#ef2543;padding:10px;width:95%">
                            <a href="_site_link_" title="logo">
                            <img width="180" src="_system_logo_url_" title="logo" alt="logo">
                          </a>
                        </td>
                    </tr>
                    
                    
                    <tr>
                        <td style="padding:25px 25px;background:#fff">
                            <div style="text-align:center">
                                <h1 style="color:#1e1e2d; font-weight:500; margin:0;font-size:25px;font-family:''Rubik'',sans-serif;">_mail_title_</h1>
                                <span style="display:inline-block; vertical-align:middle; margin:29px 0 26px; border-bottom:1px solid #cecece; width:100px;"></span>
                            </div>

                           <div class="content-body">
    <table style="border-collapse:collapse;" border="0" cellspacing="0" cellpadding="0" width="100%">
        <tbody><tr>
            <td style="padding:10px 0;">
                <strong style="color:#1e1e2d;">Customer Name:</strong><br>
                <span style="color:#455056;">_customer_name_</span>
            </td>
        </tr>

        <tr>
            <td style="padding:10px 0;">
                <strong style="color:#1e1e2d;">Phone:</strong><br>
                <span style="color:#455056;">_phone_</span>
            </td>
        </tr>

        <tr>
            <td style="padding:10px 0;">
                <strong style="color:#1e1e2d;">Satisfaction Rating:</strong><br>
                <span style="color:#455056;">_satisfaction_rating_</span>
            </td>
        </tr>

        <tr>
            <td style="padding:10px 0;">
                <strong style="color:#1e1e2d;">Comment:</strong><br>
                <span style="color:#455056;">_comment_</span>
            </td>
        </tr>
    </tbody></table>
</div>
                        </td>
                    </tr>
                    
                    
                    <tr>
                        <td style="text-align: center;text-align:center;background-color:#3a3a3abf;padding:18px;width:95%">
                            <a href="_site_link_" style="line-height:18px; margin:0 0 0; text-align: center;color:#dbe5eb; text-decoration:none !important;font-size:14px"> 
                            _footer_text_
                             </a>
                        </td>
                    </tr>
                    
                    <tr>
                        <td style="height:80px;"> </td>
                    </tr>
                </tbody></table>
            </td>
    </tr></tbody></table>', '2023-06-15 09:23:43', NULL),
(13, 16, 'New Customer Review', '<table style="@import url(https://fonts.googleapis.com/css?family=Rubik:300,400,500,700|Open+Sans:300,400,600,700); font-family: ''Open Sans'', sans-serif;" bgcolor="#f2f3f8" width="100%" cellpadding="0" border="0" cellspacing="0">
        <tbody><tr>
            <td>
                <table cellspacing="0" cellpadding="0" align="center" border="0" width="95%" style="background-color: #f2f3f8; max-width:670px;  margin:0 auto;">
                    <tbody><tr>
                        <td style="height:80px;"> </td>
                    </tr>
                    
                    <tr>
                        <td style="text-align:center;background-color:#ef2543;padding:10px;width:95%">
                            <a title="logo" href="_site_link_">
                            <img alt="logo" title="logo" src="_system_logo_url_" width="180">
                          </a>
                        </td>
                    </tr>
                    
                    
                    <tr>
                        <td style="padding:25px 25px;background:#fff">
                            <div style="text-align:center">
                                <h1 style="color:#1e1e2d; font-weight:500; margin:0;font-size:25px;font-family:''Rubik'',sans-serif;">_mail_title_</h1>
                                <span style="display:inline-block; vertical-align:middle; margin:29px 0 26px; border-bottom:1px solid #cecece; width:100px;"></span>
                            </div>

                           <div class="content-body">
    <table width="100%" cellpadding="0" cellspacing="0" border="0" style="border-collapse:collapse;">
        <tbody><tr>
            <td style="padding:10px 0;">
                <strong style="color:#1e1e2d;">Customer Name:</strong><br>
                <span style="color:#455056;">_customer_name_</span>
            </td>
        </tr>

        <tr>
            <td style="padding:10px 0;">
                <strong style="color:#1e1e2d;">Phone:</strong><br>
                <span style="color:#455056;">_phone_</span>
            </td>
        </tr>

        <tr>
            <td style="padding:10px 0;">
                <strong style="color:#1e1e2d;">Satisfaction Rating:</strong><br>
                <span style="color:#455056;">_satisfaction_rating_</span>
            </td>
        </tr>

        <tr>
            <td style="padding:10px 0;">
                <strong style="color:#1e1e2d;">Comment:</strong><br>
                <span style="color:#455056;">_comment_</span>
            </td>
        </tr>
    </tbody></table>
</div>
                        </td>
                    </tr>
                    
                    
                    <tr>
                        <td style="text-align: center;text-align:center;background-color:#3a3a3abf;padding:18px;width:95%">
                            <a style="line-height:18px; margin:0 0 0; text-align: center;color:#dbe5eb; text-decoration:none !important;font-size:14px" href="_site_link_"> 
                            _footer_text_
                             </a>
                        </td>
                    </tr>
                    
                    <tr>
                        <td style="height:80px;"> </td>
                    </tr>
                </tbody></table>
            </td>
    </tr></tbody></table>', '2023-06-15 09:23:43', NULL);
-- --------------------------------------------------------

--
-- Table structure for table `tl_email_template_variable`
--

CREATE TABLE `tl_email_template_variable` (
  `id` int(11) NOT NULL,
  `name` varchar(150) DEFAULT NULL,
  `details` varchar(150) DEFAULT NULL,
  `template_id` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `tl_email_template_variable`
--

INSERT INTO `tl_email_template_variable` (`id`, `name`, `details`, `template_id`, `created_at`, `updated_at`) VALUES
(1, '_reset_password_link_', 'Reset Password Link', 2, '2023-01-02 06:26:04', '2023-01-02 06:26:04'),
(3, '_blog_link_', 'Blog link', 1, '2023-01-02 06:35:54', '2023-01-02 06:35:54'),
(4, '_blog_name_', 'Blog name', 1, '2023-01-02 06:35:59', '2023-01-02 06:35:59'),
(5, '_comment_status_', 'Comment status', 1, '2023-01-02 06:36:04', '2023-01-02 06:36:04'),
(6, '_author_name_', 'Author Name', 1, '2023-01-02 06:36:10', '2023-01-02 06:36:10'),
(7, '_main_comment_', 'Main content', 1, '2023-01-02 06:36:14', '2023-01-02 06:36:14'),
(9, '_comment_link_', 'Comment Link', 1, '2023-01-02 06:36:23', '2023-01-02 06:36:23'),
(10, '_content_', 'Notification content', 6, '2023-01-15 11:10:12', '2023-01-15 11:10:12'),
(11, '_system_name_', 'System Name', 7, '2023-01-16 09:27:51', NULL),
(12, '_customer_email_', 'Customer email', 7, '2023-01-16 09:27:51', NULL),
(13, '_reset_url_', 'Password reset url link', 7, '2023-01-16 09:45:15', '2023-01-16 09:45:15'),
(14, '_reset_url_', 'Reset email link', 8, '2023-01-16 10:41:29', NULL),
(15, '_system_name_', 'Name of the website', 8, '2023-01-16 10:41:29', NULL),
(16, '_system_name_', 'Website name', 9, '2023-01-16 11:03:37', NULL),
(17, '_email_verify_link_', 'Email Verification link', 9, '2023-01-16 11:03:37', NULL),
(18, '_customer_name_', 'Name of the customer', 10, '2023-01-17 04:58:06', NULL),
(19, '_order_code_', 'Order number', 10, '2023-01-17 04:58:06', NULL),
(20, '_tracking_url_', 'Order tracking url', 10, '2023-01-17 04:59:43', NULL),
(21, '_order_details_', 'Order information', 10, '2023-01-17 04:59:43', NULL),
(23, '_tracking_url_', 'Order tracking url for customer', 11, '2023-01-17 10:24:09', NULL),
(24, '_customer_name_', 'Customer name', 11, '2023-01-17 10:24:09', NULL),
(25, '_message_', 'Status updated message', 11, '2023-01-17 10:24:09', NULL),
(26, '_btn_title_', 'Action url button title', 11, '2023-01-17 10:24:09', NULL),
(27, '_mail_title_', 'Subject and title of mail', 11, '2023-01-17 10:24:09', NULL),
(28, '_tracking_url_', 'Request tracking url', 12, '2023-01-17 11:30:45', NULL),
(29, '_customer_name_', 'Customer name', 12, '2023-01-17 11:30:45', NULL),
(30, '_message_', 'Status updated message', 12, '2023-01-17 11:30:45', NULL),
(31, '_mail_title_', 'Email Header', 12, '2023-01-17 11:30:45', NULL),
(32, '_order_code_', 'Order Code', 13, '2023-06-15 06:15:57', NULL),
(33, '_tracking_url_', 'Order details page link', 13, '2023-06-15 06:15:57', NULL),
(34, '_order_details_', 'Order Details', 13, '2023-06-15 06:16:25', NULL),
(36, '_message_', 'Mail Content', 14, '2023-06-15 09:18:50', NULL),
(37, '_mail_title_', 'Title of email', 14, '2023-06-15 09:20:29', '2023-06-15 09:20:29'),
(38, '_btn_title_', 'Button title of action button', 14, '2023-06-15 09:33:31', '2023-06-15 09:33:31'),
(39, '_action_url_', 'Action link for email', 14, '2023-06-15 09:20:02', NULL),
(40, '_customer_name_', 'Customer Name', 6, NOW(), NOW()),
(41, '_order_code_', 'Order Code', 6, NOW(), NOW()),
(42, '_table_', 'Order Items Table', 6, NOW(), NOW()),
(43, '_site_link_', 'Site Link', 6, NOW(), NOW()),
(44, '_footer_text_', 'Footer Text', 6, NOW(), NOW()),
(45, '_system_logo_url_', 'System Logo URL', 6, NOW(), NOW());

-- --------------------------------------------------------

--
-- Table structure for table `tl_general_settings`
--

CREATE TABLE `tl_general_settings` (
  `id` int(11) NOT NULL,
  `name` varchar(150) NOT NULL DEFAULT '0',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `tl_general_settings`
--

INSERT INTO `tl_general_settings` (`id`, `name`, `created_at`, `updated_at`) VALUES
(274, 'placeholder_image', '2023-01-24 22:50:54', '2023-01-24 22:50:54'),
(275, 'maximum_chunk_size', '2023-01-24 22:50:54', '2023-01-24 22:50:54'),
(276, 'site_title', '2023-01-24 22:50:57', '2023-01-24 22:50:57'),
(277, 'site_meta_title', '2023-01-24 22:50:57', '2023-01-24 22:50:57'),
(278, 'site_meta_description', '2023-01-24 22:50:57', '2023-01-24 22:50:57'),
(279, 'site_meta_keywords', '2023-01-24 22:50:57', '2023-01-24 22:50:57'),
(280, 'site_meta_image', '2023-01-24 22:50:57', '2023-01-24 22:50:57'),
(281, 'default_language', '2023-01-24 22:50:57', '2023-01-24 22:50:57'),
(282, 'system_name', '2023-01-24 22:51:24', '2023-01-24 22:51:24'),
(286, 'default_timezone', '2023-01-24 22:51:24', '2023-01-24 22:51:24'),
(287, 'date_format', '2023-01-24 22:51:24', '2023-01-24 22:51:24'),
(288, 'decimal_number_limit', '2023-01-24 22:51:24', '2023-01-24 22:51:24'),
(291, 'default_currency', '2023-01-24 22:51:24', '2023-01-24 22:51:24'),
(292, 'copyright_text', '2023-01-24 22:51:24', '2023-01-24 22:51:24'),
(305, 'google_client_id', '2023-01-24 22:51:25', '2023-01-24 22:51:25'),
(306, 'google_client_secret', '2023-01-24 22:51:25', '2023-01-24 22:51:25'),
(307, 'facebook_app_id', '2023-01-24 22:51:25', '2023-01-24 22:51:25'),
(308, 'facebook_app_secret', '2023-01-24 22:51:25', '2023-01-24 22:51:25'),
(309, 'twitter_client_id', '2023-01-24 22:51:25', '2023-01-24 22:51:25'),
(310, 'twitter_client_secret', '2023-01-24 22:51:25', '2023-01-24 22:51:25'),
(311, 'chunk_size_upload_status', '2023-01-24 22:51:25', '2023-01-24 22:51:25'),
(312, 'watermark_status', '2023-01-24 22:51:25', '2023-01-24 22:51:25'),
(313, 'watermark_image', '2023-01-24 22:51:25', '2023-01-24 22:51:25'),
(314, 'watermark_image_position', '2023-01-24 22:51:25', '2023-01-24 22:51:25'),
(315, 'water_marking_image_size', '2023-01-24 22:51:25', '2023-01-24 22:51:25'),
(316, 'water_marking_image_opacity', '2023-01-24 22:51:25', '2023-01-24 22:51:25'),
(317, 'water_marking_image_position_x', '2023-01-24 22:51:25', '2023-01-24 22:51:25'),
(318, 'water_marking_image_position_y', '2023-01-24 22:51:25', '2023-01-24 22:51:25'),
(319, 'large_thumb_image_width', '2023-01-24 22:51:25', '2023-01-24 22:51:25'),
(320, 'large_thumb_image_height', '2023-01-24 22:51:25', '2023-01-24 22:51:25'),
(321, 'medium_thumb_image_width', '2023-01-24 22:51:25', '2023-01-24 22:51:25'),
(322, 'medium_thumb_image_height', '2023-01-24 22:51:25', '2023-01-24 22:51:25'),
(323, 'small_thumb_image_width', '2023-01-24 22:51:25', '2023-01-24 22:51:25'),
(324, 'small_thumb_image_height', '2023-01-24 22:51:25', '2023-01-24 22:51:25'),
(325, 'default_comment_status', '2023-01-24 22:51:25', '2023-01-24 22:51:25'),
(326, 'require_name_email', '2023-01-24 22:51:25', '2023-01-24 22:51:25'),
(327, 'comment_registration', '2023-01-24 22:51:25', '2023-01-24 22:51:25'),
(328, 'close_comments_for_old_blogs', '2023-01-24 22:51:25', '2023-01-24 22:51:25'),
(329, 'thread_comments', '2023-01-24 22:51:25', '2023-01-24 22:51:25'),
(330, 'page_comments', '2023-01-24 22:51:25', '2023-01-24 22:51:25'),
(331, 'comments_notify_email', '2023-01-24 22:51:25', '2023-01-24 22:51:25'),
(332, 'comments_moderation_notify_email', '2023-01-24 22:51:25', '2023-01-24 22:51:25'),
(333, 'comment_moderation', '2023-01-24 22:51:25', '2023-01-24 22:51:25'),
(334, 'comment_previously_approved', '2023-01-24 22:51:25', '2023-01-24 22:51:25'),
(335, 'show_avatars', '2023-01-24 22:51:25', '2023-01-24 22:51:25'),
(336, 'close_comments_days_old', '2023-01-24 22:51:25', '2023-01-24 22:51:25'),
(337, 'thread_comments_level', '2023-01-24 22:51:25', '2023-01-24 22:51:25'),
(338, 'comments_per_page', '2023-01-24 22:51:25', '2023-01-24 22:51:25'),
(339, 'comment_order', '2023-01-24 22:51:25', '2023-01-24 22:51:25'),
(340, 'comment_max_links', '2023-01-24 22:51:25', '2023-01-24 22:51:25'),
(341, 'comment_moderation_keys', '2023-01-24 22:51:25', '2023-01-24 22:51:25'),
(342, 'comment_disallowed_keys', '2023-01-24 22:51:25', '2023-01-24 22:51:25'),
(343, 'avatar_default', '2023-01-24 22:51:25', '2023-01-24 22:51:25'),
(344, 'admin_logo', '2023-01-25 00:09:30', '2023-01-25 00:09:30'),
(345, 'admin_mobile_logo', '2023-01-25 00:09:30', '2023-01-25 00:09:30'),
(346, 'admin_dark_logo', '2023-01-25 00:09:30', '2023-01-25 00:09:30'),
(347, 'admin_dark_mobile_logo', '2023-01-25 00:09:30', '2023-01-25 00:09:30'),
(348, 'black_background_logo', '2023-02-04 20:36:34', '2023-02-04 20:36:34'),
(349, 'white_background_logo', '2023-02-04 20:36:34', '2023-02-04 20:36:34'),
(350, 'favicon', '2023-02-04 20:36:34', '2023-02-04 20:36:34'),
(351, 'black_mobile_background_logo', '2023-02-04 20:36:34', '2023-02-04 20:36:34'),
(352, 'white_mobile_background_logo', '2023-02-04 20:36:34', '2023-02-04 20:36:34'),
(353, 'sticky_mobile_background_logo', '2023-02-04 20:36:34', '2023-02-04 20:36:34'),
(354, 'sticky_background_logo', '2023-02-04 20:36:34', '2023-02-04 20:36:34'),
(355, 'sticky_black_mobile_background_logo', '2023-02-04 20:36:34', '2023-02-04 20:36:34'),
(356, 'sticky_black_background_logo', '2023-02-04 20:36:34', '2023-02-04 20:36:34'),
(357, 'site_moto', '2023-03-02 21:24:10', '2023-03-02 21:24:10'),
(359, 'tenant_id', '2023-03-02 21:24:10', '2023-03-02 21:24:10');

-- --------------------------------------------------------

--
-- Table structure for table `tl_general_settings_has_values`
--

CREATE TABLE `tl_general_settings_has_values` (
  `id` int(11) NOT NULL,
  `settings_id` int(11) DEFAULT NULL,
  `value` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `tl_general_settings_has_values`
--

INSERT INTO `tl_general_settings_has_values` (`id`, `settings_id`, `value`, `created_at`, `updated_at`) VALUES
(1366, 288, '2', '2023-01-29 12:37:16', '2023-01-29 12:37:16'),
(1619, 276, NULL, '2023-02-08 21:57:26', '2023-03-02 21:27:18'),
(1620, 277, 'Tl Commerce', '2023-02-08 21:57:26', '2023-02-08 21:57:26'),
(1621, 278, 'Tl Commerce is an E-commerce website.', '2023-02-08 21:57:26', '2023-02-08 21:57:26'),
(1622, 279, 'e-commerce, blog, online store, products, fashion', '2023-02-08 21:57:26', '2023-02-08 21:57:26'),
(1623, 280, '551', '2023-02-08 21:57:26', '2023-02-08 21:57:26'),
(1747, 325, '1', '2023-03-01 04:57:57', '2023-03-01 04:57:57'),
(1748, 326, '0', '2023-03-01 04:57:57', '2023-03-01 04:57:57'),
(1749, 327, '0', '2023-03-01 04:57:57', '2023-03-01 04:57:57'),
(1750, 328, '0', '2023-03-01 04:57:57', '2023-03-01 04:57:57'),
(1751, 329, '0', '2023-03-01 04:57:57', '2023-03-01 04:57:57'),
(1752, 330, '1', '2023-03-01 04:57:57', '2023-03-01 04:57:57'),
(1753, 331, '1', '2023-03-01 04:57:57', '2023-03-01 04:57:57'),
(1754, 332, '0', '2023-03-01 04:57:57', '2023-03-01 04:57:57'),
(1755, 333, '0', '2023-03-01 04:57:57', '2023-03-01 04:57:57'),
(1756, 334, '0', '2023-03-01 04:57:57', '2023-03-01 04:57:57'),
(1757, 335, '1', '2023-03-01 04:57:57', '2023-03-01 04:57:57'),
(1758, 336, '1', '2023-03-01 04:57:57', '2023-03-01 04:57:57'),
(1759, 337, '2', '2023-03-01 04:57:57', '2023-03-01 04:57:57'),
(1760, 338, '8', '2023-03-01 04:57:57', '2023-03-01 04:57:57'),
(1761, 339, '2', '2023-03-01 04:57:57', '2023-03-01 04:57:57'),
(1762, 340, '1', '2023-03-01 04:57:57', '2023-03-01 04:57:57'),
(1763, 341, NULL, '2023-03-01 04:57:57', '2023-03-01 04:57:57'),
(1764, 342, NULL, '2023-03-01 04:57:57', '2023-03-01 04:57:57'),
(1765, 343, 'mystery', '2023-03-01 04:57:57', '2023-03-01 04:57:57'),
(1873, 357, 'Buy more , earn more', '2023-03-06 05:53:43', '2023-03-06 05:53:43'),
(1874, 349, '846', '2023-03-06 05:53:43', '2023-03-06 05:53:43'),
(1875, 352, '846', '2023-03-06 05:53:43', '2023-03-06 05:53:43'),
(1876, 348, '553', '2023-03-06 05:53:43', '2023-03-06 05:53:43'),
(1877, 351, '553', '2023-03-06 05:53:43', '2023-03-06 05:53:43'),
(1878, 354, '846', '2023-03-06 05:53:43', '2023-03-06 05:53:43'),
(1879, 353, '846', '2023-03-06 05:53:43', '2023-03-06 05:53:43'),
(1880, 356, '553', '2023-03-06 05:53:43', '2023-03-06 05:53:43'),
(1881, 355, '553', '2023-03-06 05:53:43', '2023-03-06 05:53:43'),
(1884, 346, '553', '2023-03-06 05:53:43', '2023-03-06 05:53:43'),
(1885, 347, '553', '2023-03-06 05:53:43', '2023-03-06 05:53:43'),
(1886, 350, NULL, '2023-03-06 05:53:43', '2023-03-06 05:53:43'),
(1887, 281, '1', '2023-03-06 05:53:43', '2023-03-06 05:53:43'),
(1888, 286, 'America/Argentina/San_Luis', '2023-03-06 05:53:43', '2023-03-06 05:53:43'),
(1889, 292, 'Copyright @2023. All Rights Reserved by themelooks', '2023-03-06 05:53:43', '2023-03-06 05:53:43'),
(1892, 282, 'TL Commerce', '2023-03-12 06:45:13', '2023-03-12 06:45:13'),
(1893, 274, '17', '2023-03-12 08:27:16', '2023-03-12 08:27:16'),
(1894, 313, NULL, '2023-03-12 08:27:16', '2023-03-12 08:27:16'),
(1895, 314, 'top-left', '2023-03-12 08:27:16', '2023-03-12 08:27:16'),
(1896, 316, NULL, '2023-03-12 08:27:16', '2023-03-12 08:27:16'),
(1897, 319, '1000', '2023-03-12 08:27:16', '2023-03-12 08:27:16'),
(1898, 320, '1000', '2023-03-12 08:27:16', '2023-03-12 08:27:16'),
(1899, 321, '500', '2023-03-12 08:27:16', '2023-03-12 08:27:16'),
(1900, 322, '500', '2023-03-12 08:27:16', '2023-03-12 08:27:16'),
(1901, 323, '250', '2023-03-12 08:27:16', '2023-03-12 08:27:16'),
(1902, 324, '250', '2023-03-12 08:27:16', '2023-03-12 08:27:16');

-- --------------------------------------------------------

--
-- Table structure for table `tl_languages`
--

CREATE TABLE `tl_languages` (
  `id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL DEFAULT '',
  `native_name` varchar(50) NOT NULL DEFAULT '',
  `code` varchar(50) NOT NULL DEFAULT '0',
  `flag` text DEFAULT NULL,
  `status` int(11) NOT NULL DEFAULT 0,
  `is_rtl` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `tl_languages`
--

INSERT INTO `tl_languages` (`id`, `name`, `native_name`, `code`, `flag`, `status`, `is_rtl`, `created_at`, `updated_at`) VALUES
(1, 'English', 'English', 'en', NULL, 1, 2, '2022-05-30 09:53:37', '2022-07-26 04:17:44');
-- (18, 'Bengali', 'বাংলা', 'bd', NULL, 1, 0, '2023-02-05 20:43:50', '2023-02-15 22:40:04'),
-- (19, 'Arabic', 'عربي', 'sa', NULL, 1, 1, '2023-02-05 20:50:23', '2023-02-15 22:39:21');

-- --------------------------------------------------------

--
-- Table structure for table `tl_media_drivers`
--

CREATE TABLE `tl_media_drivers` (
  `id` int(11) NOT NULL,
  `name` varchar(150) DEFAULT NULL,
  `status` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `tl_media_drivers`
--

INSERT INTO `tl_media_drivers` (`id`, `name`, `status`, `created_at`, `updated_at`) VALUES
(1, 'Local Driver', 1, '2022-05-19 04:56:21', '2022-05-19 05:08:03'),
(2, 'Amazone S3', 4, '2022-05-19 04:56:32', '2022-05-19 05:08:06');

-- --------------------------------------------------------

--
-- Table structure for table `tl_media_driver_settings`
--

CREATE TABLE `tl_media_driver_settings` (
  `id` int(11) NOT NULL,
  `media_driver_id` int(11) DEFAULT NULL,
  `name` varchar(150) DEFAULT NULL,
  `value` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `tl_media_type`
--

CREATE TABLE `tl_media_type` (
  `id` int(11) NOT NULL,
  `name` varchar(510) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `tl_media_type`
--

INSERT INTO `tl_media_type` (`id`, `name`) VALUES
(1, 'Stuffs'),
(2, 'Products'),
(3, 'System'),
(4, 'Media Settings');

-- --------------------------------------------------------

--
-- Table structure for table `tl_menus`
--

CREATE TABLE `tl_menus` (
  `id` int(11) NOT NULL,
  `menu_group_id` int(11) DEFAULT NULL,
  `index` int(11) DEFAULT NULL,
  `parent_id` int(11) DEFAULT NULL,
  `post_id` bigint(20) UNSIGNED DEFAULT NULL,
  `category_id` int(11) DEFAULT NULL,
  `page_id` int(11) DEFAULT NULL,
  `menu_type_id` int(11) DEFAULT NULL,
  `menu_type` varchar(150) DEFAULT NULL,
  `level` int(11) NOT NULL DEFAULT 0,
  `title` varchar(150) DEFAULT NULL,
  `url` text DEFAULT NULL,
  `target` int(11) DEFAULT 1,
  `icon` text DEFAULT NULL,
  `location` varchar(150) DEFAULT NULL,
  `content` varchar(150) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `tl_menus`
--

INSERT INTO `tl_menus` (`id`, `menu_group_id`, `index`, `parent_id`, `post_id`, `category_id`, `page_id`, `menu_type_id`, `menu_type`, `level`, `title`, `url`, `target`, `icon`, `location`, `content`, `created_at`, `updated_at`) VALUES
(10, 25, 0, 0, NULL, NULL, NULL, NULL, NULL, 1, 'Home', '/', 1, NULL, NULL, NULL, '2023-03-11 19:37:21', '2023-03-11 19:37:21'),
(11, 25, 1, 0, NULL, NULL, NULL, NULL, NULL, 1, 'All Products', '/products', 1, NULL, NULL, NULL, '2023-03-11 19:37:34', '2023-03-11 19:37:34'),
(12, 25, 2, 0, NULL, NULL, NULL, NULL, NULL, 1, 'All Blogs', '/blog', 1, NULL, NULL, NULL, '2023-03-11 19:37:45', '2023-03-11 19:37:45'),
(14, 25, 4, 0, NULL, NULL, NULL, NULL, NULL, 1, 'Compare', '/compare', 1, NULL, NULL, NULL, '2023-03-11 19:38:33', '2023-03-11 19:38:33'),
(16, 23, 0, 0, NULL, NULL, NULL, NULL, NULL, 1, 'Products', '/products', 1, NULL, NULL, NULL, '2023-03-11 20:00:43', '2023-03-11 20:07:52'),
(19, 23, 1, 0, NULL, NULL, NULL, NULL, NULL, 1, 'Compare', '/compare', 1, NULL, NULL, NULL, '2023-03-11 20:01:20', '2023-03-11 20:01:20'),
(20, 24, 0, 0, NULL, NULL, NULL, NULL, NULL, 1, 'Categories', '/categories', 1, NULL, NULL, NULL, '2023-03-11 20:04:08', '2023-03-11 20:07:32'),
(21, 24, 1, 0, NULL, NULL, NULL, NULL, NULL, 1, 'Blogs', '/blog', 1, NULL, NULL, NULL, '2023-03-11 20:06:17', '2023-03-11 20:07:32'),
(22, 21, 0, 0, NULL, NULL, NULL, NULL, NULL, 1, 'Product', '/product', 1, NULL, NULL, NULL, '2023-03-11 20:15:49', '2023-03-11 20:15:49'),
(23, 22, 1, 0, NULL, NULL, NULL, NULL, NULL, 1, 'Blog', '/blog', 1, NULL, NULL, NULL, '2023-03-11 20:16:02', '2023-03-11 20:16:42'),
(24, 21, 1, 0, NULL, NULL, NULL, NULL, NULL, 1, 'Blog', '/blog', 1, NULL, NULL, NULL, '2023-03-11 20:16:28', '2023-03-11 20:16:28'),
(25, 22, 0, 0, NULL, NULL, NULL, NULL, NULL, 1, 'Product', '/product', 1, NULL, NULL, NULL, '2023-03-11 20:16:41', '2023-03-11 20:16:42');

-- --------------------------------------------------------

--
-- Table structure for table `tl_menu_groups`
--

CREATE TABLE `tl_menu_groups` (
  `id` int(11) NOT NULL,
  `name` varchar(150) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `tl_menu_groups`
--

INSERT INTO `tl_menu_groups` (`id`, `name`, `created_at`, `updated_at`) VALUES
(21, 'Our Policies', '2023-01-29 05:10:13', '2023-01-29 05:10:13'),
(22, 'Support', '2023-01-29 05:10:42', '2023-01-29 05:10:42'),
(23, 'Header Top Right Menus', '2023-01-29 05:47:50', '2023-01-29 05:47:50'),
(24, 'Header Top Left Menus', '2023-01-29 05:48:55', '2023-01-29 05:48:55'),
(25, 'Header Bottom Middle Menus', '2023-01-29 05:50:46', '2023-03-04 16:40:32');

-- --------------------------------------------------------

--
-- Table structure for table `tl_menu_groups_translations`
--

CREATE TABLE `tl_menu_groups_translations` (
  `id` int(11) NOT NULL,
  `menu_group_id` int(11) DEFAULT NULL,
  `name` varchar(50) DEFAULT NULL,
  `lang` varchar(50) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `tl_menu_groups_translations`
--

INSERT INTO `tl_menu_groups_translations` (`id`, `menu_group_id`, `name`, `lang`, `created_at`, `updated_at`) VALUES
(8, 21, 'Our Policies', 'en', '2023-01-29 05:13:03', '2023-01-29 05:13:03'),
(9, 24, 'Header Top Left Menus', 'en', '2023-01-29 05:49:46', '2023-01-29 05:49:46'),
(10, 23, 'Header Top Right Menus', 'en', '2023-01-29 05:49:55', '2023-01-29 05:49:55'),
(11, 25, 'Header Middle Menus', 'en', '2023-01-29 05:52:15', '2023-01-29 05:52:15');

-- --------------------------------------------------------

--
-- Table structure for table `tl_menu_group_has_positon`
--

CREATE TABLE `tl_menu_group_has_positon` (
  `id` int(11) NOT NULL,
  `menu_group_id` int(11) DEFAULT NULL,
  `menu_position_id` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `tl_menu_group_has_positon`
--

INSERT INTO `tl_menu_group_has_positon` (`id`, `menu_group_id`, `menu_position_id`, `created_at`, `updated_at`) VALUES
(119, 25, 1, '2023-03-11 06:45:37', NULL),
(120, 23, 2, '2023-03-11 09:02:10', NULL),
(121, 24, 3, '2023-03-11 09:06:36', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `tl_menu_items`
--

CREATE TABLE `tl_menu_items` (
  `id` int(11) NOT NULL,
  `name` varchar(150) DEFAULT NULL,
  `template` varchar(150) DEFAULT NULL,
  `plugin_location` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `tl_menu_items`
--

INSERT INTO `tl_menu_items` (`id`, `name`, `template`, `plugin_location`) VALUES
(1, 'Product Categories', 'plugin/tlecommercecore::menu.include.product_category_menu_item', 'tlecommercecore');

-- --------------------------------------------------------

--
-- Table structure for table `tl_menu_positions`
--

CREATE TABLE `tl_menu_positions` (
  `id` int(11) NOT NULL,
  `position` varchar(150) DEFAULT NULL,
  `theme_id` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `tl_menu_positions`
--

INSERT INTO `tl_menu_positions` (`id`, `position`, `theme_id`, `created_at`, `updated_at`) VALUES
(1, 'Header Bottom Middle Menu', 15, '2023-01-29 02:33:03', '2023-01-29 02:33:03'),
(2, 'Header Top Right Menu', 15, '2023-01-29 02:33:03', '2023-01-29 02:33:03'),
(3, 'Header Top Left Menu', 15, '2023-01-29 02:33:03', '2023-01-29 02:33:03');

-- --------------------------------------------------------

--
-- Table structure for table `tl_menu_translations`
--

CREATE TABLE `tl_menu_translations` (
  `id` int(11) NOT NULL,
  `menu_id` int(11) DEFAULT NULL,
  `lang` varchar(150) DEFAULT NULL,
  `name` varchar(150) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `tl_pages`
--

CREATE TABLE `tl_pages` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `title` varchar(255) DEFAULT NULL,
  `permalink` text DEFAULT NULL,
  `page_image` text DEFAULT NULL,
  `user_id` int(11) NOT NULL DEFAULT 0,
  `content` longtext DEFAULT NULL,
  `visibility` varchar(255) DEFAULT NULL,
  `page_password` longtext DEFAULT NULL,
  `publish_at` datetime DEFAULT NULL,
  `parent` bigint(20) UNSIGNED DEFAULT NULL,
  `meta_title` text DEFAULT NULL,
  `meta_description` text DEFAULT NULL,
  `meta_image` text DEFAULT NULL,
  `page_template` bigint(20) DEFAULT NULL,
  `order` bigint(20) DEFAULT NULL,
  `page_type` varchar(255) DEFAULT NULL,
  `is_home` int(11) NOT NULL DEFAULT 0,
  `publish_status` smallint(6) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `tl_page_templates`
--

CREATE TABLE `tl_page_templates` (
  `id` bigint(20) NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `tl_page_translations`
--

CREATE TABLE `tl_page_translations` (
  `id` int(11) NOT NULL,
  `title` varchar(255) DEFAULT NULL,
  `content` longtext DEFAULT NULL,
  `page_id` bigint(20) UNSIGNED DEFAULT NULL,
  `lang` varchar(150) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `tl_pick_up_points`
--

CREATE TABLE `tl_pick_up_points` (
  `id` int(11) NOT NULL,
  `name` text NOT NULL,
  `location` text NOT NULL,
  `phone` text NOT NULL,
  `status` int(11) DEFAULT NULL,
  `zone` int(11) DEFAULT NULL,
  `country_id` int(11) DEFAULT NULL,
  `state_id` int(11) DEFAULT NULL,
  `city_id` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `tl_pick_up_points_translations`
--

CREATE TABLE `tl_pick_up_points_translations` (
  `id` int(11) NOT NULL,
  `pic_up_point_id` int(11) DEFAULT NULL,
  `name` varchar(150) DEFAULT NULL,
  `lang` varchar(150) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `tl_plugins`
--

CREATE TABLE `tl_plugins` (
  `id` int(11) NOT NULL,
  `name` varchar(150) NOT NULL,
  `location` varchar(150) DEFAULT NULL,
  `author` varchar(150) NOT NULL,
  `description` text DEFAULT NULL,
  `version` varchar(11) NOT NULL DEFAULT '1',
  `unique_indentifier` text NOT NULL,
  `is_activated` int(10) NOT NULL,
  `namespace` varchar(150) NOT NULL,
  `url` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `tl_plugins`
--

INSERT INTO `tl_plugins` (`id`, `name`, `location`, `author`, `description`, `version`, `unique_indentifier`, `is_activated`, `namespace`, `url`, `created_at`, `updated_at`) VALUES
(17, 'TL Commerce Core', 'tlecommercecore', 'Themelooks', 'Core plugin of Tl-commerce', '1.0.1', 'sfjhsgdfjkshdf', 1, 'Plugin\\TlcommerceCore\\', 'http://www.themelooks.com/', '2022-06-23 00:12:52', '2023-03-09 05:36:08'),
(21, 'Coupon', 'coupon', 'Themelooks', 'Coupon plugin of Tl-commerce', '1.0.0', 'qweeqeqw', 1, 'Plugin\\Coupon\\', 'http://www.themelooks.com/', '2022-07-31 21:40:52', '2023-01-11 03:21:31'),
(22, 'Flash Deal', 'flashdeal', 'Themelooks', 'Flash Deal plugin of Tl-commerce', '1.0.0', 'qweeqeqw', 1, 'Plugin\\Flashdeal\\', 'http://www.themelooks.com/', '2022-07-31 21:41:21', '2022-08-17 02:32:03'),
(23, 'Pickup Point', 'pickuppoint', 'Themelooks', 'Pickup Point plugin of Tl-commerce', '1.0.0', 'sfjhsgdfjkshdf', 1, 'Plugin\\PickupPoint\\', 'http://www.themelooks.com/', '2022-07-31 22:15:40', '2023-01-07 22:46:31'),
(24, 'Wallet', 'wallet', 'Themelooks', 'Wallet plugin of Tl-commerce', '1.0.1', 'sfjhsgdfjkshdf', 1, 'Plugin\\Wallet\\', 'http://www.themelooks.com/', '2022-08-02 03:01:56', '2023-02-12 15:26:23'),
(26, 'Refunds', 'refund', 'Themelooks', 'Refunds plugin of Tl-commerce', '1.0.0', 'wrwerwer', 1, 'Plugin\\Refund\\', 'http://www.themelooks.com/', '2022-08-03 00:12:15', '2022-12-07 06:10:39'),
(28, '3rd Party Carrier', 'carrier', 'Themelooks', '3rd party plugin of Tl-commerce', '1.0.0', '54646546', 1, 'Plugin\\Carrier\\', 'http://www.themelooks.com/', '2022-09-25 02:24:13', '2023-02-12 22:20:13'),
(29, 'Multivendor', 'multivendor', 'Themelooks', 'Multivendor for Tlcommerce  Saas', '1.0.0', 'a199c678-86d1-11ee-8d22-180f7603497b', 1, 'Plugin\\Multivendor\\', 'http://www.themelooks.com/', '2023-11-19 11:49:01', '2023-11-19 11:49:01'),
(30, 'Tlcommerce Page Builder', 'tlcommerce-pagebuilder', 'Themelooks', 'Page Builder Plugin for Tlcommerce', '1.0.0', 'SDVeCBTrNhXvoHR', 1, 'Plugin\\TlPageBuilder\\', 'http://www.themelooks.com/', '2023-08-15 20:20:15', '2023-08-27 00:05:37');

-- --------------------------------------------------------

--
-- Table structure for table `tl_product_types`
--

CREATE TABLE `tl_product_types` (
  `id` int(11) NOT NULL,
  `name` varchar(150) DEFAULT NULL,
  `status` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `tl_sidebar_has_widgets`
--

CREATE TABLE `tl_sidebar_has_widgets` (
  `id` int(11) NOT NULL,
  `sidebar_id` int(11) DEFAULT NULL,
  `widget_id` bigint(20) DEFAULT NULL,
  `order` bigint(20) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `tl_sidebar_has_widgets`
--

INSERT INTO `tl_sidebar_has_widgets` (`id`, `sidebar_id`, `widget_id`, `order`) VALUES
(763, 4, 79, 4),
(764, 4, 96, 2),
(765, 4, 97, 3),
(766, 4, 78, 1),
(767, 5, 83, 2),
(768, 5, 84, 1),
(771, 5, 96, 3);

-- --------------------------------------------------------

--
-- Table structure for table `tl_sidebar_widget_has_translate_values`
--

CREATE TABLE `tl_sidebar_widget_has_translate_values` (
  `id` int(11) NOT NULL,
  `value` longtext DEFAULT NULL,
  `sidebar_widget_has_values_id` int(11) DEFAULT NULL,
  `lang` varchar(150) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `tl_sidebar_widget_has_translate_values`
--

INSERT INTO `tl_sidebar_widget_has_translate_values` (`id`, `value`, `sidebar_widget_has_values_id`, `lang`, `created_at`, `updated_at`) VALUES
(6, '{\"widget_title\":\"\\u09af\\u09cb\\u0997\\u09be\\u09af\\u09cb\\u0997 \\u0995\\u09b0\\u09c1\\u09a8\"}', 175, 'bd', '2023-02-11 21:57:31', '2023-02-11 21:57:31'),
(7, '{\"widget_title\":\"\\u0627\\u0628\\u0642\\u0649 \\u0639\\u0644\\u0649 \\u062a\\u0648\\u0627\\u0635\\u0644\"}', 175, 'sa', '2023-02-11 21:57:58', '2023-02-11 21:57:58'),
(8, '{\"widget_title\":\"\\u0633\\u064a\\u0627\\u0633\\u0627\\u062a\\u0646\\u0627\"}', 179, 'sa', '2023-02-11 21:58:23', '2023-02-11 21:58:23'),
(9, '{\"widget_title\":\"\\u0986\\u09ae\\u09be\\u09a6\\u09c7\\u09b0 \\u09a8\\u09c0\\u09a4\\u09bf\"}', 179, 'bd', '2023-02-11 21:58:37', '2023-02-11 21:58:37'),
(10, '{\"widget_title\":\"\\u09b8\\u09ae\\u09b0\\u09cd\\u09a5\\u09a8\"}', 180, 'bd', '2023-02-11 22:02:19', '2023-02-11 22:02:19'),
(11, '{\"widget_title\":\"\\u064a\\u062f\\u0639\\u0645\"}', 180, 'sa', '2023-02-11 22:02:32', '2023-02-11 22:02:32'),
(12, '{\"widget_title\":\"\\u0627\\u0646\\u0636\\u0645 \\u0625\\u0644\\u0649 \\u0627\\u0644\\u0646\\u0634\\u0631\\u0629 \\u0627\\u0644\\u0625\\u062e\\u0628\\u0627\\u0631\\u064a\\u0629\",\"newsletter_short_desc\":\"\\u0627\\u0634\\u062a\\u0631\\u0643 \\u0641\\u064a \\u0627\\u0644\\u0646\\u0634\\u0631\\u0629 \\u0627\\u0644\\u0625\\u062e\\u0628\\u0627\\u0631\\u064a\\u0629 \\u0644\\u062c\\u0645\\u064a\\u0639 \\u0622\\u062e\\u0631 \\u0627\\u0644\\u062a\\u062d\\u062f\\u064a\\u062b\\u0627\\u062a\"}', 176, 'sa', '2023-02-11 22:03:04', '2023-02-11 22:03:04'),
(13, '{\"widget_title\":\"\\u09a8\\u09bf\\u0989\\u099c\\u09b2\\u09c7\\u099f\\u09be\\u09b0 \\u09af\\u09cb\\u0997\\u09a6\\u09be\\u09a8\",\"newsletter_short_desc\":\"\\u09b8\\u09ac \\u09b8\\u09b0\\u09cd\\u09ac\\u09b6\\u09c7\\u09b7 \\u0986\\u09aa\\u09a1\\u09c7\\u099f\\u09c7\\u09b0 \\u099c\\u09a8\\u09cd\\u09af \\u09a8\\u09bf\\u0989\\u099c\\u09b2\\u09c7\\u099f\\u09be\\u09b0 \\u09b8\\u09a6\\u09b8\\u09cd\\u09af\\u09a4\\u09be\"}', 176, 'bd', '2023-02-11 22:05:41', '2023-02-11 22:05:41'),
(15, '{\"widget_title\":\"\\u09b8\\u09be\\u09ae\\u09cd\\u09aa\\u09cd\\u09b0\\u09a4\\u09bf\\u0995 \\u09ac\\u09cd\\u09b2\\u0997\"}', 181, 'bd', '2023-02-11 22:14:54', '2023-02-11 22:14:54'),
(16, '{\"widget_title\":\"\\u0645\\u062f\\u0648\\u0646\\u0629 \\u062d\\u062f\\u064a\\u062b\\u0629\"}', 181, 'sa', '2023-02-11 22:15:08', '2023-02-11 22:15:08'),
(17, '{\"widget_title\":\"\\u09ac\\u09c8\\u09b6\\u09bf\\u09b7\\u09cd\\u099f\\u09cd\\u09af\\u09af\\u09c1\\u0995\\u09cd\\u09a4 \\u09ac\\u09cd\\u09b2\\u0997\"}', 182, 'bd', '2023-02-11 22:15:22', '2023-02-11 22:15:22'),
(18, '{\"widget_title\":\"\\u0645\\u062f\\u0648\\u0646\\u0627\\u062a \\u0645\\u0645\\u064a\\u0632\\u0629\"}', 182, 'sa', '2023-02-11 22:15:35', '2023-02-11 22:15:35');

-- --------------------------------------------------------

--
-- Table structure for table `tl_sidebar_widget_has_values`
--

CREATE TABLE `tl_sidebar_widget_has_values` (
  `id` int(11) NOT NULL,
  `sidebar_has_widget_id` int(11) DEFAULT NULL,
  `widget_input_id` bigint(20) DEFAULT NULL,
  `value` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `tl_sidebar_widget_has_values`
--

INSERT INTO `tl_sidebar_widget_has_values` (`id`, `sidebar_has_widget_id`, `widget_input_id`, `value`) VALUES
(175, 766, NULL, '{\"widget_title\":\"Get in Touch\",\"mail\":\"support@rexeller.com\",\"mobile\":\"02 478 658 8936\",\"address\":\"53 Rain Road, Suite 41 Austin Greater NY, USA\"}'),
(176, 763, NULL, '{\"widget_title\":\"Join Newsletter\",\"newsletter_short_desc\":\"Subscribe to the newsletter for all the latest updates\"}'),
(179, 764, NULL, '{\"widget_title\":\"Our Policies\",\"menu_group_id\":\"21\"}'),
(180, 765, NULL, '{\"widget_title\":\"Support\",\"menu_group_id\":\"22\"}'),
(181, 768, NULL, '{\"widget_title\":\"Recent Blogs\",\"number_of_recent_blog\":\"3\"}'),
(182, 767, NULL, '{\"widget_title\":\"Featured Blogs\",\"number_of_featured_blog\":\"3\"}');

-- --------------------------------------------------------

--
-- Table structure for table `tl_store_layouts`
--

CREATE TABLE `tl_store_layouts` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `is_active` tinyint(1) DEFAULT 0,
  `settings` text, -- JSON for layout-specific settings
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Dumping data for table `tl_store_layouts`
--

INSERT INTO `tl_store_layouts` (`name`, `is_active`, `settings`) VALUES
('standard', 1, '{"container_width": "full", "sidebar": "none"}'),
('split_screen', 0, '{"left_section": "content", "right_section": "wallpaper", "split_ratio": "50-50"}'),
('modern', 0, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `tl_store_layouts_split_screen_properties`
--

CREATE TABLE `tl_store_layouts_split_screen_properties` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `layout_id` int(11) NOT NULL,
  `content_position` varchar(50) NOT NULL DEFAULT 'left' COMMENT 'left or right',
  `feature_type` varchar(50) DEFAULT 'banner' COMMENT 'banner, product, or video',
  `feature_image` varchar(255) DEFAULT NULL,
  `header_background_image` BIGINT UNSIGNED NULL,
  `feature_link` varchar(255) DEFAULT NULL,
  `background_color` varchar(20) DEFAULT '#ffffff',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 1) Add the modern layout (inactive; activate from admin)
INSERT INTO tl_store_layouts (`name`, `settings`, `is_active`, `created_at`)
SELECT 'modern', NULL, 0, NOW()
WHERE NOT EXISTS (
  SELECT 1 FROM tl_store_layouts WHERE `name` = 'modern'
);




-- 2) Add feature-pane props for modern (same shape as split_screen)
INSERT INTO tl_store_layouts_split_screen_properties
  (`layout_id`, `content_position`, `feature_type`, `feature_image`, `background_color`, `created_at`, `updated_at`)
SELECT
  l.id,
  'left',
  'banner',
  NULL,
  '#f8f9fa',
  NOW(),
  NOW()
FROM tl_store_layouts l
WHERE l.name = 'modern'
  AND NOT EXISTS (
    SELECT 1
    FROM tl_store_layouts_split_screen_properties p
    WHERE p.layout_id = l.id
  );

-- ALTER TABLE tl_store_layouts_split_screen_properties
--   ADD COLUMN header_background_image BIGINT UNSIGNED NULL
--   AFTER feature_image;

-- --------------------------------------------------------

--
-- Table structures for Quiz functionality
--

-- The quiz container
CREATE TABLE quiz_features (
    id            INT(11) NOT NULL AUTO_INCREMENT,
    title         VARCHAR(255) NOT NULL,
    slug          VARCHAR(255) NULL,
    description   TEXT,
    layout_config JSON,
    is_active     TINYINT(1) NOT NULL DEFAULT 1,
    created_at    TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,

    PRIMARY KEY (id),
    UNIQUE KEY uq_quiz_features_slug (slug)
);

-- Questions within a quiz
CREATE TABLE quiz_questions (
    id              INT(11) NOT NULL AUTO_INCREMENT,
    quiz_id         INT(11) NOT NULL,
    question_text   TEXT NOT NULL,
    question_type   ENUM('radio','checkbox','dropdown','image_select') NOT NULL,
    is_required     TINYINT(1) DEFAULT 1,
    sort_order      SMALLINT DEFAULT 0,
    layout_config   JSON, -- e.g. columns per row, image sizing

    PRIMARY KEY (id),
    KEY idx_quiz_questions_quiz_id (quiz_id)
);

-- Answers per question
CREATE TABLE quiz_answers (
    id                  INT(11) NOT NULL AUTO_INCREMENT,
    question_id         INT(11) NOT NULL,
    answer_text         VARCHAR(500) NOT NULL,
    answer_image        VARCHAR(255) NULL,
    answer_description  TEXT NULL,
    sort_order          SMALLINT DEFAULT 0,

    PRIMARY KEY (id),
    KEY idx_quiz_answers_question_id (question_id),

    CONSTRAINT fk_quiz_answers_question
        FOREIGN KEY (question_id)
        REFERENCES quiz_questions(id)
        ON DELETE CASCADE
);

-- THE CORE MAPPING: each answer contributes N points toward each product
CREATE TABLE quiz_answer_product_scores (
    id          INT(11) NOT NULL AUTO_INCREMENT,
    answer_id   INT(11) NOT NULL,
    product_id  INT(11) NOT NULL,
    score       DECIMAL(8,2) NOT NULL DEFAULT 0,

    PRIMARY KEY (id),
    UNIQUE KEY uq_answer_product (answer_id, product_id),

    KEY idx_answer_id (answer_id),
    KEY idx_product_id (product_id)
);

-- 5) Customer submissions
CREATE TABLE `quiz_submissions` (
  `id`                INT(11) NOT NULL AUTO_INCREMENT,
  `quiz_id`           INT(11) NOT NULL,
  `customer_id`       INT(11) NULL,
  `guest_customer_id` INT(11) NULL,
  `session_token`     VARCHAR(64) NULL,
  `submitted_at`      TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_quiz_submissions_quiz_id` (`quiz_id`),
  KEY `idx_quiz_submissions_customer_id` (`customer_id`),
  KEY `idx_quiz_submissions_guest_customer_id` (`guest_customer_id`),
  KEY `idx_quiz_submissions_session_token` (`session_token`)
);


-- 6) Selected answers per submission
CREATE TABLE `quiz_submission_answers` (
  `id`            INT(11) NOT NULL AUTO_INCREMENT,
  `submission_id` INT(11) NOT NULL,
  `question_id`   INT(11) NOT NULL,
  `answer_id`     INT(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_submission_answers_submission_id` (`submission_id`),
  KEY `idx_submission_answers_question_id` (`question_id`),
  KEY `idx_submission_answers_answer_id` (`answer_id`)
);


-- 7) Computed results
CREATE TABLE `quiz_results` (
  `id`            INT(11) NOT NULL AUTO_INCREMENT,
  `submission_id` INT(11) NOT NULL,
  `product_id`    INT(11) NOT NULL,
  `total_score`   DECIMAL(10,2) NOT NULL,
  `rank`          TINYINT NOT NULL,
  `created_at`    TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_quiz_results_submission_id` (`submission_id`),
  KEY `idx_quiz_results_product_id` (`product_id`)
);

--
-- Table structure for table `tl_smtps`
--

CREATE TABLE `tl_smtps` (
  `id` int(11) NOT NULL,
  `name` varchar(150) DEFAULT NULL,
  `status` int(11) DEFAULT 0,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `tl_smtp_configs`
--

CREATE TABLE `tl_smtp_configs` (
  `id` int(11) NOT NULL,
  `smtp_id` int(11) DEFAULT NULL,
  `name` varchar(150) DEFAULT NULL,
  `value` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `tl_themes`
--

CREATE TABLE `tl_themes` (
  `id` int(11) NOT NULL,
  `name` varchar(150) NOT NULL,
  `location` varchar(200) NOT NULL,
  `author` varchar(150) NOT NULL,
  `description` text DEFAULT NULL,
  `version` varchar(150) NOT NULL DEFAULT '1.0',
  `unique_indentifier` text NOT NULL,
  `is_activated` int(11) NOT NULL DEFAULT 1,
  `namespace` varchar(150) NOT NULL,
  `url` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `tl_themes`
--

INSERT INTO `tl_themes` (`id`, `name`, `location`, `author`, `description`, `version`, `unique_indentifier`, `is_activated`, `namespace`, `url`, `created_at`, `updated_at`) VALUES
(15, 'TL Commerce', 'tlcommerce', 'Themelooks', 'The TL Commerce theme of tl-commerce', '1.0.1', '54646546', 1, 'Theme\\TLCommerce\\', 'http://www.themelooks.com/', '2022-08-07 22:11:18', '2023-01-09 05:38:11');

-- --------------------------------------------------------

--
-- Table structure for table `tl_theme_option_settings`
--

CREATE TABLE `tl_theme_option_settings` (
  `id` bigint(20) NOT NULL,
  `theme_id` int(11) DEFAULT NULL,
  `option_name` text DEFAULT NULL,
  `field_name` text DEFAULT NULL,
  `field_value` longtext DEFAULT NULL,
  `field_reset_value` longtext DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `tl_theme_option_settings`
--

INSERT INTO `tl_theme_option_settings` (`id`, `theme_id`, `option_name`, `field_name`, `field_value`, `field_reset_value`) VALUES
(4173, 15, 'back_to_top', 'back_to_top_button', '1', NULL),
(4174, 15, 'back_to_top', 'custom_back_to_top_button', '0', NULL),
(4175, 15, 'single_blog_page', 'custom_blog_page', '1', NULL),
(4176, 15, 'single_blog_page', 'blog_post_title_position', 'below_thumbnail', NULL),
(4177, 15, 'single_blog_page', 'author', '1', NULL),
(4178, 15, 'single_blog_page', 'date', '1', NULL),
(4179, 15, 'social', 'social_field', '[{\"social_icon_title\":\"Facebook\",\"social_icon\":\"fa-facebook-f\",\"social_icon_url\":\"#\",\"order\":1},{\"social_icon_title\":\"Twitter\",\"social_icon\":\"fa-twitter\",\"social_icon_url\":\"#\",\"order\":2},{\"social_icon_title\":\"Instagram\",\"social_icon\":\"fa-instagram\",\"social_icon_url\":\"#\",\"order\":3},{\"social_icon_title\":\"Pinterest\",\"social_icon\":\"fa-pinterest-p\",\"social_icon_url\":\"#\",\"order\":4}]', NULL),
(4180, 15, 'social', 'custom_social', '0', NULL),
(4181, 15, 'header', 'custom_header', '1', NULL),
(4182, 15, 'header', 'header_bot_email_text', 'tlcommerce@gmail.com', NULL),
(4183, 15, 'header', 'header_top_bg_color', NULL, NULL),
(4184, 15, 'header', 'header_top_bg_color_transparent', '0', NULL),
(4185, 15, 'header', 'header_mid_bg_color', NULL, NULL),
(4186, 15, 'header', 'header_mid_bg_color_transparent', '0', NULL),
(4187, 15, 'header', 'header_bot_bg_color', NULL, NULL),
(4188, 15, 'header', 'header_bot_bg_color_transparent', '0', NULL),
(4189, 15, 'header', 'header_bot_text_color', NULL, NULL),
(4190, 15, 'header', 'header_bot_text_color_transparent', '0', NULL),
(4191, 15, 'header', 'sticky_header_bg_color', NULL, NULL),
(4192, 15, 'header', 'sticky_header_bg_color_transparent', '0', NULL),
(4193, 15, 'header', 'header_search_form_btn_color', NULL, NULL),
(4194, 15, 'header', 'header_search_form_btn_color_transparent', '0', NULL),
(4195, 15, 'header', 'header_search_form_btn_hover_color', NULL, NULL),
(4196, 15, 'header', 'header_search_form_btn_hover_color_transparent', '0', NULL),
(4197, 15, 'header', 'header_search_form_btn_text_color', NULL, NULL),
(4198, 15, 'header', 'header_search_form_btn_text_color_transparent', '0', NULL),
(4199, 15, 'header', 'header_search_form_btn_hover_text_color', NULL, NULL),
(4200, 15, 'header', 'header_search_form_btn_hover_text_color_transparent', '0', NULL),
(4201, 15, 'header', 'header_icon_btn_bg_color', NULL, NULL),
(4202, 15, 'header', 'header_icon_btn_bg_color_transparent', '0', NULL),
(4203, 15, 'header', 'header_icon_btn_text_color', NULL, NULL),
(4204, 15, 'header', 'header_icon_btn_text_color_transparent', '0', NULL),
(4205, 15, 'header', 'header_icon_btn_hover_bg_color', NULL, NULL),
(4206, 15, 'header', 'header_icon_btn_hover_bg_color_transparent', '0', NULL),
(4207, 15, 'header', 'header_icon_btn_hover_text_color', NULL, NULL),
(4208, 15, 'header', 'header_icon_btn_hover_text_color_transparent', '0', NULL),
(4209, 15, 'header', 'header_top_lang_btn_bg_color', NULL, NULL),
(4210, 15, 'header', 'header_top_lang_btn_bg_color_transparent', '0', NULL),
(4211, 15, 'header', 'header_top_lang_btn_text_color', NULL, NULL),
(4212, 15, 'header', 'header_top_lang_btn_text_color_transparent', '0', NULL),
(4213, 15, 'header', 'header_top_lang_btn_hover_bg_color', NULL, NULL),
(4214, 15, 'header', 'header_top_lang_btn_hover_bg_color_transparent', '0', NULL),
(4215, 15, 'header', 'header_top_lang_btn_hover_text_color', NULL, NULL),
(4216, 15, 'header', 'header_top_lang_btn_hover_text_color_transparent', '0', NULL),
(4217, 15, 'custom_fonts', 'custom_font_1', '1', NULL),
(4218, 15, 'custom_fonts', 'custom_font_1_woff', 'RubikGemstones-Regular.ttf', NULL),
(4219, 15, 'custom_fonts', 'custom_font_1_ttf', NULL, NULL),
(4220, 15, 'custom_fonts', 'custom_font_1_eot', NULL, NULL),
(4221, 15, 'custom_fonts', 'custom_font_2', '1', NULL),
(4222, 15, 'custom_fonts', 'custom_font_1_woff_file', '/tmp/phpXcgn43', NULL),
(4223, 15, 'body_typography', 'body_typography_google_link_s', NULL, NULL),
(4224, 15, 'body_typography', 'body_typography_css_i', NULL, NULL),
(4225, 15, 'body_typography', 'body_font_unit_i', NULL, NULL),
(4226, 15, 'body_typography', 'body_font_font-family', NULL, NULL),
(4227, 15, 'body_typography', 'body_font_font-style', NULL, NULL),
(4228, 15, 'body_typography', 'body_font_font-weight', NULL, NULL),
(4229, 15, 'body_typography', 'body_font_weight_style_i', NULL, NULL),
(4230, 15, 'body_typography', 'body_font_font-subsets_i', NULL, NULL),
(4231, 15, 'body_typography', 'body_font_u_font-size', NULL, NULL),
(4232, 15, 'body_typography', 'body_font_u_line-height', NULL, NULL),
(4233, 15, 'body_typography', 'body_font_u_word-spacing', NULL, NULL),
(4234, 15, 'body_typography', 'body_font_u_letter-spacing', NULL, NULL),
(4235, 15, 'body_typography', 'body_font_color', NULL, NULL),
(4236, 15, 'custom_fonts', 'custom_font_2_woff', 'CandysBwPersonalUseBold-4Bq6D.ttf', NULL),
(4237, 15, 'custom_fonts', 'custom_font_2_ttf', NULL, NULL),
(4238, 15, 'custom_fonts', 'custom_font_2_eot', NULL, NULL),
(4239, 15, 'custom_fonts', 'custom_font_2_woff_file', '/tmp/phpA0t0eX', NULL),
(4240, 15, 'subscribe', 'mailchimp_api_key', NULL, NULL),
(4241, 15, 'subscribe', 'mailchimp_list_id', NULL, NULL),
(4242, 15, 'subscribe', 'custom_subscription', '0', NULL),
(4243, 15, 'theme_color', 'theme_primary_color', NULL, NULL),
(4244, 15, 'theme_color', 'theme_primary_color_transparent', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `tl_theme_sidebars`
--

CREATE TABLE `tl_theme_sidebars` (
  `id` int(11) NOT NULL,
  `sidebar_name` varchar(255) DEFAULT NULL,
  `theme_id` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `tl_theme_sidebars`
--

INSERT INTO `tl_theme_sidebars` (`id`, `sidebar_name`, `theme_id`, `created_at`, `updated_at`) VALUES
(4, 'Footer Sidebar', 15, NULL, NULL),
(5, 'Blog Sidebar', 15, '2023-01-03 05:17:16', '2023-01-03 05:17:16');

-- --------------------------------------------------------

--
-- Table structure for table `tl_theme_tlcommerce_home_page_sections`
--

CREATE TABLE `tl_theme_tlcommerce_home_page_sections` (
  `id` int(11) NOT NULL,
  `ordering` int(11) NOT NULL DEFAULT 0,
  `status` int(11) NOT NULL DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `tl_theme_tlcommerce_home_page_sections`
--

INSERT INTO `tl_theme_tlcommerce_home_page_sections` (`id`, `ordering`, `status`, `created_at`, `updated_at`) VALUES
(1, 0, 1, '2023-03-11 19:40:07', '2023-03-11 19:40:07'),
(2, 0, 1, '2023-03-11 19:56:26', '2023-03-11 19:56:26');
 
-- --------------------------------------------------------

--
-- Table structure for table `tl_theme_tlcommerce_home_page_sections_properties`
--

CREATE TABLE `tl_theme_tlcommerce_home_page_sections_properties` (
  `id` int(11) NOT NULL,
  `section_id` int(11) DEFAULT NULL,
  `key_name` text DEFAULT NULL,
  `key_value` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `tl_theme_tlcommerce_home_page_sections_properties`
--

INSERT INTO `tl_theme_tlcommerce_home_page_sections_properties` (`id`, `section_id`, `key_name`, `key_value`, `created_at`, `updated_at`) VALUES
(257, 2, '1_1_image', NULL, '2023-03-19 20:18:50', '2023-03-19 20:18:50'),
(258, 2, '1_1_url', NULL, '2023-03-19 20:18:50', '2023-03-19 20:18:50'),
(259, 2, 'content', '1', '2023-03-19 20:18:50', '2023-03-19 20:18:50'),
(260, 2, 'title', 'Flash deal section', '2023-03-19 20:18:50', '2023-03-19 20:18:50'),
(261, 2, 'title_color', '#000000', '2023-03-19 20:18:50', '2023-03-19 20:18:50'),
(262, 2, 'bg_color', '#FFFFF', '2023-03-19 20:18:50', '2023-03-19 20:18:50'),
(263, 2, 'bg_image', NULL, '2023-03-19 20:18:50', '2023-03-19 20:18:50'),
(264, 2, 'background_size', 'cover', '2023-03-19 20:18:50', '2023-03-19 20:18:50'),
(265, 2, 'background_position', 'bottom', '2023-03-19 20:18:50', '2023-03-19 20:18:50'),
(266, 2, 'background_repeat', 'no-repeat', '2023-03-19 20:18:50', '2023-03-19 20:18:50'),
(267, 2, 'btn_title', NULL, '2023-03-19 20:18:50', '2023-03-19 20:18:50'),
(268, 2, 'btn_color', '#FFFFF', '2023-03-19 20:18:50', '2023-03-19 20:18:50'),
(269, 2, 'btn_hover_color', '#FFFFF', '2023-03-19 20:18:50', '2023-03-19 20:18:50'),
(270, 2, 'btn_bg_color', '#ffffff', '2023-03-19 20:18:50', '2023-03-19 20:18:50'),
(271, 2, 'btn_bg_hover_color', '#FFFFF', '2023-03-19 20:18:50', '2023-03-19 20:18:50'),
(272, 2, 'btn_border', NULL, '2023-03-19 20:18:50', '2023-03-19 20:18:50'),
(273, 2, 'btn_border_color', '#FFFFF', '2023-03-19 20:18:50', '2023-03-19 20:18:50'),
(274, 2, 'btn_border_hover_color', '#FFFFF', '2023-03-19 20:18:50', '2023-03-19 20:18:50'),
(275, 2, 'padding_top', '20', '2023-03-19 20:18:50', '2023-03-19 20:18:50'),
(276, 2, 'padding_right', '0', '2023-03-19 20:18:50', '2023-03-19 20:18:50'),
(277, 2, 'padding_bottom', '20', '2023-03-19 20:18:50', '2023-03-19 20:18:50'),
(278, 2, 'padding_left', '0', '2023-03-19 20:18:50', '2023-03-19 20:18:50'),
(279, 2, 'margin_top', '0', '2023-03-19 20:18:50', '2023-03-19 20:18:50'),
(280, 2, 'margin_right', '0', '2023-03-19 20:18:50', '2023-03-19 20:18:50'),
(281, 2, 'margin_bottom', '0', '2023-03-19 20:18:50', '2023-03-19 20:18:50'),
(282, 2, 'margin_left', '0', '2023-03-19 20:18:50', '2023-03-19 20:18:50'),
(283, 2, 'layout', 'flashdeal', '2023-03-19 20:18:50', '2023-03-19 20:18:50'),
(284, 1, '1__image', NULL, '2023-03-19 20:19:49', '2023-03-19 20:19:49'),
(285, 1, '1__url', NULL, '2023-03-19 20:19:49', '2023-03-19 20:19:49'),
(286, 1, 'title', 'Category Slider', '2023-03-19 20:19:49', '2023-03-19 20:19:49'),
(287, 1, 'bg_color', '#FFFFF', '2023-03-19 20:19:49', '2023-03-19 20:19:49'),
(288, 1, 'bg_image', NULL, '2023-03-19 20:19:49', '2023-03-19 20:19:49'),
(289, 1, 'background_size', 'cover', '2023-03-19 20:19:49', '2023-03-19 20:19:49'),
(290, 1, 'background_position', 'bottom', '2023-03-19 20:19:49', '2023-03-19 20:19:49'),
(291, 1, 'background_repeat', 'no-repeat', '2023-03-19 20:19:49', '2023-03-19 20:19:49'),
(292, 1, 'padding_top', '15', '2023-03-19 20:19:49', '2023-03-19 20:19:49'),
(293, 1, 'padding_right', '0', '2023-03-19 20:19:49', '2023-03-19 20:19:49'),
(294, 1, 'padding_bottom', '15', '2023-03-19 20:19:49', '2023-03-19 20:19:49'),
(295, 1, 'padding_left', '0', '2023-03-19 20:19:49', '2023-03-19 20:19:49'),
(296, 1, 'margin_top', '0', '2023-03-19 20:19:49', '2023-03-19 20:19:49'),
(297, 1, 'margin_right', '0', '2023-03-19 20:19:49', '2023-03-19 20:19:49'),
(298, 1, 'margin_bottom', '0', '2023-03-19 20:19:49', '2023-03-19 20:19:49'),
(299, 1, 'margin_left', '0', '2023-03-19 20:19:49', '2023-03-19 20:19:49'),
(300, 1, 'layout', 'category_slider', '2023-03-19 20:19:49', '2023-03-19 20:19:49');

-- --------------------------------------------------------

--
-- Table structure for table `tl_theme_tlcommerce_sliders`
--

CREATE TABLE `tl_theme_tlcommerce_sliders` (
  `id` int(11) NOT NULL,
  `title` varchar(150) DEFAULT NULL,
  `desktop` int(11) DEFAULT NULL,
  `mobile` int(11) DEFAULT NULL,
  `url` text DEFAULT NULL,
  `status` int(11) NOT NULL DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `tl_theme_tlcommerce_sliders`
--

INSERT INTO `tl_theme_tlcommerce_sliders` (`id`, `title`, `desktop`, `mobile`, `url`, `status`, `created_at`, `updated_at`) VALUES
(1, 'Banner 1', 26, 26, '/', 1, '2023-03-11 19:34:05', '2023-03-23 00:55:00'),
(2, 'Banner 2', 26, 26, '/', 1, '2023-03-11 19:35:36', '2023-03-23 00:54:45'),
(3, 'Banner 3', 26, 26, '/', 1, '2023-03-11 19:36:13', '2023-03-23 00:54:23');

-- --------------------------------------------------------

CREATE TABLE `tl_com_analytics_events` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `event_type` varchar(50) NOT NULL,
  `product_id` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `idx_analytics_event_type_created` (`event_type`, `created_at`),
  KEY `idx_analytics_product_event_created` (`product_id`, `event_type`, `created_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Table structure for table `tl_theme_translations`
--

CREATE TABLE `tl_theme_translations` (
  `id` int(11) NOT NULL,
  `lang` varchar(250) DEFAULT NULL,
  `lang_key` text DEFAULT NULL,
  `lang_value` longtext DEFAULT NULL,
  `theme` varchar(200) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `tl_theme_translations`
--

INSERT INTO `tl_theme_translations` VALUES
(360,'en','Enter your search key','Enter your search key','tlcommerce','2023-02-18 02:49:57','2023-02-18 04:25:13'),
(361,'en','Registration',NULL,'tlcommerce','2023-02-18 02:49:58','2023-02-18 02:49:58'),
(362,'en','Login',NULL,'tlcommerce','2023-02-18 02:49:58','2023-02-18 02:49:58'),
(363,'en','Search',NULL,'tlcommerce','2023-02-18 02:49:58','2023-02-18 02:49:58'),
(364,'en','Category',NULL,'tlcommerce','2023-02-18 02:49:59','2023-02-18 02:49:59'),
(365,'en','Home',NULL,'tlcommerce','2023-02-18 02:49:59','2023-02-18 02:49:59'),
(366,'en','Wishlist',NULL,'tlcommerce','2023-02-18 02:49:59','2023-02-18 02:49:59'),
(367,'en','My Account',NULL,'tlcommerce','2023-02-18 02:49:59','2023-02-18 02:49:59'),
(368,'en','Cart',NULL,'tlcommerce','2023-02-18 02:50:02','2023-02-18 02:50:02'),
(369,'en','expand_more',NULL,'tlcommerce','2023-02-18 02:50:06','2023-02-18 02:50:06'),
(370,'en','Select Categories',NULL,'tlcommerce','2023-02-18 02:50:06','2023-02-18 02:50:06'),
(371,'en','All Categories',NULL,'tlcommerce','2023-02-18 02:50:06','2023-02-18 02:50:06'),
(372,'en','All Products',NULL,'tlcommerce','2023-02-18 02:50:07','2023-02-18 02:50:07'),
(373,'en','Subscribe',NULL,'tlcommerce','2023-02-18 02:50:07','2023-02-18 02:50:07'),
(374,'en','Enter your email',NULL,'tlcommerce','2023-02-18 02:50:07','2023-02-18 02:50:07'),
(375,'en','Your Cart',NULL,'tlcommerce','2023-02-18 02:50:10','2023-02-18 02:50:10'),
(376,'en','Checkout',NULL,'tlcommerce','2023-02-18 02:50:10','2023-02-18 02:50:10'),
(377,'en','Subtotal',NULL,'tlcommerce','2023-02-18 02:50:11','2023-02-18 02:50:11'),
(378,'en','Unit Price',NULL,'tlcommerce','2023-02-18 02:50:11','2023-02-18 02:50:11'),
(379,'en','Quantity',NULL,'tlcommerce','2023-02-18 02:50:11','2023-02-18 02:50:11'),
(380,'en','Product Name',NULL,'tlcommerce','2023-02-18 02:50:11','2023-02-18 02:50:11'),
(381,'en','Total',NULL,'tlcommerce','2023-02-18 02:50:11','2023-02-18 02:50:11'),
(382,'en','Remove',NULL,'tlcommerce','2023-02-18 02:50:11','2023-02-18 02:50:11'),
(383,'en','Continue Shopping',NULL,'tlcommerce','2023-02-18 02:50:12','2023-02-18 02:50:12'),
(384,'en','Buy Now',NULL,'tlcommerce','2023-02-18 02:50:48','2023-02-18 02:50:48'),
(385,'en','View',NULL,'tlcommerce','2023-02-18 02:50:48','2023-02-18 02:50:48'),
(386,'en','Half Sleeve Jersey T-shirt for Men',NULL,'tlcommerce','2023-02-18 02:50:48','2023-02-18 02:50:48'),
(387,'en','Blogs',NULL,'tlcommerce','2023-02-18 02:50:48','2023-02-18 02:50:48'),
(388,'en','See all',NULL,'tlcommerce','2023-02-18 02:50:54','2023-02-18 02:50:54'),
(389,'en','Read more',NULL,'tlcommerce','2023-02-18 02:50:55','2023-02-18 02:50:55'),
(390,'en','day',NULL,'tlcommerce','2023-02-18 02:50:55','2023-02-18 02:50:55'),
(391,'en','minute',NULL,'tlcommerce','2023-02-18 02:50:55','2023-02-18 02:50:55'),
(392,'en','hour',NULL,'tlcommerce','2023-02-18 02:50:55','2023-02-18 02:50:55'),
(393,'en','Second',NULL,'tlcommerce','2023-02-18 02:50:55','2023-02-18 02:50:55'),
(394,'en','View All',NULL,'tlcommerce','2023-02-18 02:50:55','2023-02-18 02:50:55'),
(395,'en','You may like it',NULL,'tlcommerce','2023-02-18 02:51:47','2023-02-18 02:51:47'),
(396,'en','Tags:',NULL,'tlcommerce','2023-02-18 02:51:56','2023-02-18 02:51:56'),
(397,'en','Categories:',NULL,'tlcommerce','2023-02-18 02:51:56','2023-02-18 02:51:56'),
(398,'en','Products',NULL,'tlcommerce','2023-02-18 02:52:44','2023-02-18 02:52:44'),
(399,'en','Rating',NULL,'tlcommerce','2023-02-18 02:52:44','2023-02-18 02:52:44'),
(400,'en','Price',NULL,'tlcommerce','2023-02-18 02:52:44','2023-02-18 02:52:44'),
(401,'en','Sort By',NULL,'tlcommerce','2023-02-18 02:52:52','2023-02-18 02:52:52'),
(402,'en','Popular items',NULL,'tlcommerce','2023-02-18 02:52:52','2023-02-18 02:52:52'),
(403,'en','No item found',NULL,'tlcommerce','2023-02-18 02:52:52','2023-02-18 02:52:52'),
(404,'en','Newest items',NULL,'tlcommerce','2023-02-18 02:52:52','2023-02-18 02:52:52'),
(405,'en','Price low to high',NULL,'tlcommerce','2023-02-18 02:52:52','2023-02-18 02:52:52'),
(406,'en','Price high to low',NULL,'tlcommerce','2023-02-18 02:52:52','2023-02-18 02:52:52'),
(407,'en','Top Categories',NULL,'tlcommerce','2023-02-18 02:52:52','2023-02-18 02:52:52'),
(408,'en','Brand',NULL,'tlcommerce','2023-02-18 02:52:53','2023-02-18 02:52:53'),
(409,'en','More',NULL,'tlcommerce','2023-02-18 02:52:53','2023-02-18 02:52:53'),
(410,'en','View More',NULL,'tlcommerce','2023-02-18 02:52:53','2023-02-18 02:52:53'),
(411,'en','items found',NULL,'tlcommerce','2023-02-18 02:52:55','2023-02-18 02:52:55'),
(412,'en','Feedback',NULL,'tlcommerce','2023-02-18 02:52:55','2023-02-18 02:52:55'),
(413,'en','Filtered By',NULL,'tlcommerce','2023-02-18 02:53:17','2023-02-18 02:53:17'),
(414,'en','CLEAR ALL',NULL,'tlcommerce','2023-02-18 02:53:17','2023-02-18 02:53:17'),
(415,'en','Overview',NULL,'tlcommerce','2023-02-18 02:53:30','2023-02-18 02:53:30'),
(416,'en','Product Details',NULL,'tlcommerce','2023-02-18 02:53:30','2023-02-18 02:53:30'),
(417,'en','Quick Connect',NULL,'tlcommerce','2023-02-18 02:53:30','2023-02-18 02:53:30'),
(418,'en','Recommendations',NULL,'tlcommerce','2023-02-18 02:53:30','2023-02-18 02:53:30'),
(419,'en','Conditions',NULL,'tlcommerce','2023-02-18 02:53:39','2023-02-18 02:53:39'),
(420,'en','Product Condition',NULL,'tlcommerce','2023-02-18 02:53:39','2023-02-18 02:53:39'),
(421,'en','Authentic',NULL,'tlcommerce','2023-02-18 02:53:39','2023-02-18 02:53:39'),
(422,'en','Cash on Delivery',NULL,'tlcommerce','2023-02-18 02:53:39','2023-02-18 02:53:39'),
(423,'en','Payment Option',NULL,'tlcommerce','2023-02-18 02:53:39','2023-02-18 02:53:39'),
(424,'en','Return Options',NULL,'tlcommerce','2023-02-18 02:53:39','2023-02-18 02:53:39'),
(425,'en','Change of mind is not applicable',NULL,'tlcommerce','2023-02-18 02:53:40','2023-02-18 02:53:40'),
(426,'en','Warranty',NULL,'tlcommerce','2023-02-18 02:53:40','2023-02-18 02:53:40'),
(427,'en','Seller warranty',NULL,'tlcommerce','2023-02-18 02:53:40','2023-02-18 02:53:40'),
(428,'en','Reviews',NULL,'tlcommerce','2023-02-18 02:53:40','2023-02-18 02:53:40'),
(429,'en','Not available',NULL,'tlcommerce','2023-02-18 02:53:40','2023-02-18 02:53:40'),
(430,'en','Total Price',NULL,'tlcommerce','2023-02-18 02:53:41','2023-02-18 02:53:41'),
(431,'en','Price Range',NULL,'tlcommerce','2023-02-18 02:53:41','2023-02-18 02:53:41'),
(432,'en','are available',NULL,'tlcommerce','2023-02-18 02:53:41','2023-02-18 02:53:41'),
(433,'en','Compatible file extensions to upload: png, jpg, pdf',NULL,'tlcommerce','2023-02-18 02:53:41','2023-02-18 02:53:41'),
(434,'en','Place Order',NULL,'tlcommerce','2023-02-18 02:53:41','2023-02-18 02:53:41'),
(435,'en','Add To Cart',NULL,'tlcommerce','2023-02-18 02:53:41','2023-02-18 02:53:41'),
(436,'en','Add to Compare',NULL,'tlcommerce','2023-02-18 02:53:42','2023-02-18 02:53:42'),
(437,'en','Add to wishlist',NULL,'tlcommerce','2023-02-18 02:53:42','2023-02-18 02:53:42'),
(438,'en','Product Overview',NULL,'tlcommerce','2023-02-18 02:53:42','2023-02-18 02:53:42'),
(439,'en','Top Selling Products',NULL,'tlcommerce','2023-02-18 02:53:42','2023-02-18 02:53:42'),
(440,'en','Buyer Review',NULL,'tlcommerce','2023-02-18 02:53:43','2023-02-18 02:53:43'),
(441,'en','Recent',NULL,'tlcommerce','2023-02-18 02:53:43','2023-02-18 02:53:43'),
(442,'en','Rating: High to Low',NULL,'tlcommerce','2023-02-18 02:53:43','2023-02-18 02:53:43'),
(443,'en','Rating: Low to High',NULL,'tlcommerce','2023-02-18 02:53:43','2023-02-18 02:53:43'),
(444,'en','days',NULL,'tlcommerce','2023-02-18 02:54:34','2023-02-18 02:54:34'),
(445,'en','replacement',NULL,'tlcommerce','2023-02-18 02:54:34','2023-02-18 02:54:34'),
(446,'en','Compare',NULL,'tlcommerce','2023-02-18 02:55:04','2023-02-18 02:55:04'),
(447,'en','Name',NULL,'tlcommerce','2023-02-18 02:55:15','2023-02-18 02:55:15'),
(448,'en','Availability',NULL,'tlcommerce','2023-02-18 02:55:16','2023-02-18 02:55:16'),
(449,'en','Refund',NULL,'tlcommerce','2023-02-18 02:55:16','2023-02-18 02:55:16'),
(450,'en','Summary',NULL,'tlcommerce','2023-02-18 02:55:16','2023-02-18 02:55:16'),
(451,'en','In Stock',NULL,'tlcommerce','2023-02-18 02:55:16','2023-02-18 02:55:16'),
(452,'en','Email',NULL,'tlcommerce','2023-02-18 02:55:42','2023-02-18 02:55:42'),
(453,'en','If you have no account',NULL,'tlcommerce','2023-02-18 02:55:43','2023-02-18 02:55:43'),
(454,'en','Password',NULL,'tlcommerce','2023-02-18 02:55:43','2023-02-18 02:55:43'),
(455,'en','Forgot Password',NULL,'tlcommerce','2023-02-18 02:55:43','2023-02-18 02:55:43'),
(456,'en','Register Here',NULL,'tlcommerce','2023-02-18 02:55:43','2023-02-18 02:55:43'),
(457,'en','Phone',NULL,'tlcommerce','2023-02-18 02:56:35','2023-02-18 02:56:35'),
(458,'en','Register',NULL,'tlcommerce','2023-02-18 02:56:35','2023-02-18 02:56:35'),
(459,'en','Phone Number',NULL,'tlcommerce','2023-02-18 02:56:35','2023-02-18 02:56:35'),
(460,'en','Confirm Password',NULL,'tlcommerce','2023-02-18 02:56:35','2023-02-18 02:56:35'),
(461,'en','I have read and agree to the terms and conditions',NULL,'tlcommerce','2023-02-18 02:56:36','2023-02-18 02:56:36'),
(462,'en','Already have an account',NULL,'tlcommerce','2023-02-18 02:56:36','2023-02-18 02:56:36'),
(463,'en','Login Here',NULL,'tlcommerce','2023-02-18 02:56:36','2023-02-18 02:56:36'),
(464,'en','Review',NULL,'tlcommerce','2023-02-18 02:57:46','2023-02-18 02:57:46'),
(465,'en','Shipping',NULL,'tlcommerce','2023-02-18 02:57:46','2023-02-18 02:57:46'),
(466,'en','Payments',NULL,'tlcommerce','2023-02-18 02:57:46','2023-02-18 02:57:46'),
(467,'en','Delivery & Shipping',NULL,'tlcommerce','2023-02-18 02:57:46','2023-02-18 02:57:46'),
(468,'en','Home Delivery',NULL,'tlcommerce','2023-02-18 02:57:47','2023-02-18 02:57:47'),
(469,'en','Collect From Store',NULL,'tlcommerce','2023-02-18 02:57:47','2023-02-18 02:57:47'),
(470,'en','Personal Information',NULL,'tlcommerce','2023-02-18 02:57:47','2023-02-18 02:57:47'),
(471,'en','Your Name',NULL,'tlcommerce','2023-02-18 02:57:47','2023-02-18 02:57:47'),
(472,'en','Create an Account',NULL,'tlcommerce','2023-02-18 02:57:47','2023-02-18 02:57:47'),
(473,'en','Shipping Details',NULL,'tlcommerce','2023-02-18 02:57:47','2023-02-18 02:57:47'),
(474,'en','Email Address',NULL,'tlcommerce','2023-02-18 02:57:48','2023-02-18 02:57:48'),
(475,'en','Address',NULL,'tlcommerce','2023-02-18 02:57:48','2023-02-18 02:57:48'),
(476,'en','Postal Code',NULL,'tlcommerce','2023-02-18 02:57:48','2023-02-18 02:57:48'),
(477,'en','Previous',NULL,'tlcommerce','2023-02-18 02:57:48','2023-02-18 02:57:48'),
(478,'en','Continue',NULL,'tlcommerce','2023-02-18 02:57:48','2023-02-18 02:57:48'),
(479,'en','Product',NULL,'tlcommerce','2023-02-18 02:57:49','2023-02-18 02:57:49'),
(480,'en','Tax',NULL,'tlcommerce','2023-02-18 02:57:49','2023-02-18 02:57:49'),
(481,'en','Payable Total',NULL,'tlcommerce','2023-02-18 02:57:49','2023-02-18 02:57:49'),
(482,'en','Shipping Cost',NULL,'tlcommerce','2023-02-18 02:57:50','2023-02-18 02:57:50'),
(483,'en','Your Coupon',NULL,'tlcommerce','2023-02-18 02:57:50','2023-02-18 02:57:50'),
(484,'en','Select Country',NULL,'tlcommerce','2023-02-18 02:57:50','2023-02-18 02:57:50'),
(485,'en','Apply',NULL,'tlcommerce','2023-02-18 02:57:50','2023-02-18 02:57:50'),
(486,'en','Select pickup point',NULL,'tlcommerce','2023-02-18 02:57:50','2023-02-18 02:57:50'),
(487,'en','Select State',NULL,'tlcommerce','2023-02-18 02:57:50','2023-02-18 02:57:50'),
(488,'en','Select City',NULL,'tlcommerce','2023-02-18 02:57:50','2023-02-18 02:57:50'),
(489,'en','Pickup Point',NULL,'tlcommerce','2023-02-18 02:58:24','2023-02-18 02:58:24'),
(490,'en','Name is required',NULL,'tlcommerce','2023-02-18 03:00:05','2023-02-18 03:00:05'),
(491,'en','Email is required',NULL,'tlcommerce','2023-02-18 03:00:05','2023-02-18 03:00:05'),
(492,'en','Phone is required',NULL,'tlcommerce','2023-02-18 03:00:05','2023-02-18 03:00:05'),
(493,'en','Postal code is required',NULL,'tlcommerce','2023-02-18 03:00:05','2023-02-18 03:00:05'),
(494,'en','Address is required',NULL,'tlcommerce','2023-02-18 03:00:05','2023-02-18 03:00:05'),
(495,'en','Please select a country',NULL,'tlcommerce','2023-02-18 03:00:06','2023-02-18 03:00:06'),
(496,'en','Please select a state',NULL,'tlcommerce','2023-02-18 03:00:06','2023-02-18 03:00:06'),
(497,'en','Please select a city',NULL,'tlcommerce','2023-02-18 03:00:06','2023-02-18 03:00:06'),
(498,'en','Please wait',NULL,'tlcommerce','2023-02-18 03:05:58','2023-02-18 03:05:58'),
(499,'en','Registration successful. check your email to verify your email',NULL,'tlcommerce','2023-02-18 03:09:02','2023-02-18 03:09:02'),
(500,'en','Login successful',NULL,'tlcommerce','2023-02-18 03:12:22','2023-02-18 03:12:22'),
(501,'en','notifications',NULL,'tlcommerce','2023-02-18 03:12:22','2023-02-18 03:12:22'),
(502,'en','Dashboard',NULL,'tlcommerce','2023-02-18 03:12:43','2023-02-18 03:12:43'),
(503,'en','Logout',NULL,'tlcommerce','2023-02-18 03:12:43','2023-02-18 03:12:43'),
(504,'en','Total Purchase Amount',NULL,'tlcommerce','2023-02-18 03:12:58','2023-02-18 03:12:58'),
(505,'en','Last Purchase',NULL,'tlcommerce','2023-02-18 03:12:58','2023-02-18 03:12:58'),
(506,'en','Total Purchase in',NULL,'tlcommerce','2023-02-18 03:12:58','2023-02-18 03:12:58'),
(507,'en','Last Month Purchase',NULL,'tlcommerce','2023-02-18 03:12:58','2023-02-18 03:12:58'),
(508,'en','Latest Purchase',NULL,'tlcommerce','2023-02-18 03:12:59','2023-02-18 03:12:59'),
(509,'en','Refund Requests',NULL,'tlcommerce','2023-02-18 03:12:59','2023-02-18 03:12:59'),
(510,'en','Purchase History',NULL,'tlcommerce','2023-02-18 03:12:59','2023-02-18 03:12:59'),
(511,'en','My Wallet',NULL,'tlcommerce','2023-02-18 03:12:59','2023-02-18 03:12:59'),
(512,'en','Manage Account',NULL,'tlcommerce','2023-02-18 03:12:59','2023-02-18 03:12:59'),
(513,'en','Total Order',NULL,'tlcommerce','2023-02-18 03:13:00','2023-02-18 03:13:00'),
(514,'en','Pending Orders',NULL,'tlcommerce','2023-02-18 03:13:00','2023-02-18 03:13:00'),
(515,'en','All',NULL,'tlcommerce','2023-02-18 03:13:25','2023-02-18 03:13:25'),
(516,'en','Add new address',NULL,'tlcommerce','2023-02-18 03:14:04','2023-02-18 03:14:04'),
(517,'en','Available Balance',NULL,'tlcommerce','2023-02-18 03:15:02','2023-02-18 03:15:02'),
(518,'en','Recharge wallet',NULL,'tlcommerce','2023-02-18 03:15:03','2023-02-18 03:15:03'),
(519,'en','Pending Balance',NULL,'tlcommerce','2023-02-18 03:15:03','2023-02-18 03:15:03'),
(520,'en','Basic Information',NULL,'tlcommerce','2023-02-18 03:15:35','2023-02-18 03:15:35'),
(521,'en','Image',NULL,'tlcommerce','2023-02-18 03:15:35','2023-02-18 03:15:35'),
(522,'en','Update Profile',NULL,'tlcommerce','2023-02-18 03:15:35','2023-02-18 03:15:35'),
(523,'en','Change Password',NULL,'tlcommerce','2023-02-18 03:15:35','2023-02-18 03:15:35'),
(524,'en','Generate password reset link',NULL,'tlcommerce','2023-02-18 03:15:36','2023-02-18 03:15:36'),
(525,'en','Change Email',NULL,'tlcommerce','2023-02-18 03:15:36','2023-02-18 03:15:36'),
(526,'en','Generate email reset link',NULL,'tlcommerce','2023-02-18 03:15:36','2023-02-18 03:15:36'),
(527,'en','Categories',NULL,'tlcommerce','2023-02-18 03:17:31','2023-02-18 03:17:31'),
(528,'en','No address found',NULL,'tlcommerce','2023-02-18 03:20:33','2023-02-18 03:20:33'),
(529,'en','Manage Address',NULL,'tlcommerce','2023-02-18 03:20:34','2023-02-18 03:20:34'),
(530,'en','Address Information',NULL,'tlcommerce','2023-02-18 03:21:49','2023-02-18 03:21:49'),
(531,'en','Country',NULL,'tlcommerce','2023-02-18 03:21:50','2023-02-18 03:21:50'),
(532,'en','State',NULL,'tlcommerce','2023-02-18 03:21:50','2023-02-18 03:21:50'),
(533,'en','City',NULL,'tlcommerce','2023-02-18 03:21:51','2023-02-18 03:21:51'),
(534,'en','Save address',NULL,'tlcommerce','2023-02-18 03:21:51','2023-02-18 03:21:51'),
(535,'en','Please choose a pickup point',NULL,'tlcommerce','2023-02-18 03:28:05','2023-02-18 03:28:05'),
(536,'en','Logout successful',NULL,'tlcommerce','2023-02-18 03:31:40','2023-02-18 03:31:40'),
(537,'en','Address:',NULL,'tlcommerce','2023-02-18 03:34:58','2023-02-18 03:34:58'),
(538,'en','Postal Code:',NULL,'tlcommerce','2023-02-18 03:34:58','2023-02-18 03:34:58'),
(539,'en','Phone:',NULL,'tlcommerce','2023-02-18 03:34:58','2023-02-18 03:34:58'),
(540,'en','Delivery not available at this location',NULL,'tlcommerce','2023-02-18 03:35:10','2023-02-18 03:35:10'),
(541,'en','Status',NULL,'tlcommerce','2023-02-18 03:44:45','2023-02-18 03:44:45'),
(542,'en','Edit',NULL,'tlcommerce','2023-02-18 03:44:45','2023-02-18 03:44:45'),
(543,'en','Actions',NULL,'tlcommerce','2023-02-18 03:44:45','2023-02-18 03:44:45'),
(544,'en','ID',NULL,'tlcommerce','2023-02-18 03:50:15','2023-02-18 03:50:15'),
(545,'en','Order Date',NULL,'tlcommerce','2023-02-18 03:50:15','2023-02-18 03:50:15'),
(546,'en','Amount',NULL,'tlcommerce','2023-02-18 03:50:15','2023-02-18 03:50:15'),
(547,'en','Num of Products',NULL,'tlcommerce','2023-02-18 03:50:15','2023-02-18 03:50:15'),
(548,'en','Details',NULL,'tlcommerce','2023-02-18 03:50:15','2023-02-18 03:50:15'),
(549,'en','Order Details',NULL,'tlcommerce','2023-02-18 03:50:35','2023-02-18 03:50:35'),
(550,'en','Order Placed on',NULL,'tlcommerce','2023-02-18 03:50:47','2023-02-18 03:50:47'),
(551,'en','Order ID',NULL,'tlcommerce','2023-02-18 03:50:47','2023-02-18 03:50:47'),
(552,'en','Package',NULL,'tlcommerce','2023-02-18 03:50:47','2023-02-18 03:50:47'),
(553,'en','Shipping Address',NULL,'tlcommerce','2023-02-18 03:50:48','2023-02-18 03:50:48'),
(554,'en','Billing Address',NULL,'tlcommerce','2023-02-18 03:50:48','2023-02-18 03:50:48'),
(555,'en','Total Summary',NULL,'tlcommerce','2023-02-18 03:50:48','2023-02-18 03:50:48'),
(556,'en','Processing',NULL,'tlcommerce','2023-02-18 03:50:49','2023-02-18 03:50:49'),
(557,'en','Shipped',NULL,'tlcommerce','2023-02-18 03:50:49','2023-02-18 03:50:49'),
(558,'en','Payment method',NULL,'tlcommerce','2023-02-18 03:50:49','2023-02-18 03:50:49'),
(559,'en','Delivered',NULL,'tlcommerce','2023-02-18 03:50:49','2023-02-18 03:50:49'),
(560,'en','Update Address Information',NULL,'tlcommerce','2023-02-18 03:57:54','2023-02-18 03:57:54'),
(561,'en','Active',NULL,'tlcommerce','2023-02-18 03:57:55','2023-02-18 03:57:55'),
(562,'en','Inactive',NULL,'tlcommerce','2023-02-18 03:57:55','2023-02-18 03:57:55'),
(563,'en','Default Shipping Address',NULL,'tlcommerce','2023-02-18 03:57:55','2023-02-18 03:57:55'),
(564,'en','Default Billing Address',NULL,'tlcommerce','2023-02-18 03:57:55','2023-02-18 03:57:55'),
(565,'en','Save Changes',NULL,'tlcommerce','2023-02-18 03:57:55','2023-02-18 03:57:55'),
(566,'en','Date',NULL,'tlcommerce','2023-02-18 04:01:49','2023-02-18 04:01:49'),
(567,'en','Type',NULL,'tlcommerce','2023-02-18 04:01:49','2023-02-18 04:01:49'),
(568,'sa','Enter your search key','أدخل مفتاح البحث الخاص بك','tlcommerce','2023-02-18 04:28:44','2023-02-18 04:28:44'),
(569,'sa','Registration','تسجيل','tlcommerce','2023-02-18 04:28:44','2023-02-18 04:28:44'),
(570,'sa','Login','تسجيل الدخول','tlcommerce','2023-02-18 04:28:44','2023-02-18 04:28:44'),
(571,'sa','Search','يبحث','tlcommerce','2023-02-18 04:28:44','2023-02-18 04:28:44'),
(572,'sa','Category','فئة','tlcommerce','2023-02-18 04:28:44','2023-02-18 04:28:44'),
(573,'sa','Home','الرئيسية','tlcommerce','2023-02-18 04:28:44','2023-02-18 04:28:44'),
(574,'sa','Wishlist','قائمة الرغبات','tlcommerce','2023-02-18 04:28:44','2023-02-18 04:28:44'),
(575,'sa','My Account','حسابي','tlcommerce','2023-02-18 04:28:44','2023-02-18 04:28:44'),
(576,'sa','Cart','عربة التسوق','tlcommerce','2023-02-18 04:28:44','2023-02-18 04:28:44'),
(577,'sa','expand_more','expand_more','tlcommerce','2023-02-18 04:28:44','2023-02-18 04:28:44'),
(578,'sa','Select Categories','حدد الفئات','tlcommerce','2023-02-18 04:28:44','2023-02-18 04:28:44'),
(579,'sa','All Categories','جميع الفئات','tlcommerce','2023-02-18 04:28:44','2023-02-18 04:28:44'),
(580,'sa','All Products','جميع المنتجات','tlcommerce','2023-02-18 04:28:44','2023-02-18 04:28:44'),
(581,'sa','Subscribe','يشترك','tlcommerce','2023-02-18 04:28:44','2023-02-18 04:28:44'),
(582,'sa','Enter your email','أدخل بريدك الإلكتروني','tlcommerce','2023-02-18 04:28:44','2023-02-18 04:28:44'),
(583,'sa','Your Cart','عربتك','tlcommerce','2023-02-18 04:28:44','2023-02-18 04:28:44'),
(584,'sa','Checkout','الدفع','tlcommerce','2023-02-18 04:28:44','2023-02-18 04:28:44'),
(585,'sa','Subtotal','المجموع الفرعي','tlcommerce','2023-02-18 04:28:44','2023-02-18 04:28:44'),
(586,'sa','Unit Price','سعر الوحدة','tlcommerce','2023-02-18 04:28:44','2023-02-18 04:28:44'),
(587,'sa','Quantity','كمية','tlcommerce','2023-02-18 04:28:44','2023-02-18 04:28:44'),
(588,'sa','Product Name','اسم المنتج','tlcommerce','2023-02-18 04:28:44','2023-02-18 04:28:44'),
(589,'sa','Total','المجموع','tlcommerce','2023-02-18 04:28:44','2023-02-18 04:28:44'),
(590,'sa','Remove','يزيل','tlcommerce','2023-02-18 04:28:44','2023-02-18 04:28:44'),
(591,'sa','Continue Shopping','مواصلة التسوق','tlcommerce','2023-02-18 04:28:44','2023-02-18 04:28:44'),
(592,'sa','Buy Now','اشتري الآن','tlcommerce','2023-02-18 04:28:44','2023-02-18 04:28:44'),
(593,'sa','View','منظر','tlcommerce','2023-02-18 04:28:44','2023-02-18 04:28:44'),
(594,'sa','Half Sleeve Jersey T-shirt for Men','تي شيرت جيرسي نصف كم للرجال','tlcommerce','2023-02-18 04:28:44','2023-02-18 04:28:44'),
(595,'sa','Blogs','المدونات','tlcommerce','2023-02-18 04:28:44','2023-02-18 04:28:44'),
(596,'sa','See all','اظهار الكل','tlcommerce','2023-02-18 04:28:44','2023-02-18 04:28:44'),
(597,'sa','Read more','اقرأ أكثر','tlcommerce','2023-02-18 04:28:44','2023-02-18 04:28:44'),
(598,'sa','day','يوم','tlcommerce','2023-02-18 04:28:44','2023-02-18 04:28:44'),
(599,'sa','minute','دقيقة','tlcommerce','2023-02-18 04:28:44','2023-02-18 04:28:44'),
(600,'sa','hour','ساعة','tlcommerce','2023-02-18 04:28:44','2023-02-18 04:28:44'),
(601,'sa','Second','ثانية','tlcommerce','2023-02-18 04:28:44','2023-02-18 04:28:44'),
(602,'sa','View All','مشاهدة الكل','tlcommerce','2023-02-18 04:28:44','2023-02-18 04:28:44'),
(603,'sa','You may like it','قد تعجبك','tlcommerce','2023-02-18 04:28:44','2023-02-18 04:28:44'),
(604,'sa','Tags:','العلامات:','tlcommerce','2023-02-18 04:28:44','2023-02-18 04:28:44'),
(605,'sa','Categories:','فئات:','tlcommerce','2023-02-18 04:28:44','2023-02-18 04:28:44'),
(606,'sa','Products','منتجات','tlcommerce','2023-02-18 04:28:44','2023-02-18 04:28:44'),
(607,'sa','Rating','تقييم','tlcommerce','2023-02-18 04:28:44','2023-02-18 04:28:44'),
(608,'sa','Price','سعر','tlcommerce','2023-02-18 04:28:44','2023-02-18 04:28:44'),
(609,'sa','Sort By','ترتيب حسب','tlcommerce','2023-02-18 04:28:44','2023-02-18 04:28:44'),
(610,'sa','Popular items','العناصر الشعبية','tlcommerce','2023-02-18 04:28:44','2023-02-18 04:28:44'),
(611,'sa','No item found','لم يتم العثور على عنصر','tlcommerce','2023-02-18 04:28:44','2023-02-18 04:28:44'),
(612,'sa','Newest items','أحدث العناصر','tlcommerce','2023-02-18 04:28:44','2023-02-18 04:28:44'),
(613,'sa','Price low to high','السعر من الارخص للاعلى','tlcommerce','2023-02-18 04:28:44','2023-02-18 04:28:44'),
(614,'sa','Price high to low','السعر الاعلى الى الادنى','tlcommerce','2023-02-18 04:28:44','2023-02-18 04:28:44'),
(615,'sa','Top Categories','أعلى الفئات','tlcommerce','2023-02-18 04:28:44','2023-02-18 04:28:44'),
(616,'sa','Brand','ماركة','tlcommerce','2023-02-18 04:28:44','2023-02-18 04:28:44'),
(617,'sa','More','أكثر','tlcommerce','2023-02-18 04:28:44','2023-02-18 04:28:44'),
(618,'sa','View More','عرض المزيد','tlcommerce','2023-02-18 04:28:44','2023-02-18 04:28:44'),
(619,'sa','items found','تم العثور على العناصر','tlcommerce','2023-02-18 04:28:44','2023-02-18 04:28:44'),
(620,'sa','Feedback','تعليق','tlcommerce','2023-02-18 04:28:44','2023-02-18 04:28:44'),
(621,'sa','Filtered By','تم التصفية بواسطة','tlcommerce','2023-02-18 04:28:44','2023-02-18 04:28:44'),
(622,'sa','CLEAR ALL','امسح الكل','tlcommerce','2023-02-18 04:28:44','2023-02-18 04:28:44'),
(623,'sa','Overview','ملخص','tlcommerce','2023-02-18 04:28:44','2023-02-18 04:28:44'),
(624,'sa','Product Details','تفاصيل المنتج','tlcommerce','2023-02-18 04:28:44','2023-02-18 04:28:44'),
(625,'sa','Quick Connect','اتصال سريع','tlcommerce','2023-02-18 04:28:44','2023-02-18 04:28:44'),
(626,'sa','Recommendations','التوصيات','tlcommerce','2023-02-18 04:28:44','2023-02-18 04:28:44'),
(627,'sa','Conditions','شروط','tlcommerce','2023-02-18 04:28:44','2023-02-18 04:28:44'),
(628,'sa','Product Condition','حالة المنتج','tlcommerce','2023-02-18 04:28:44','2023-02-18 04:28:44'),
(629,'sa','Authentic','أصلي','tlcommerce','2023-02-18 04:28:44','2023-02-18 04:28:44'),
(630,'sa','Cash on Delivery','الدفع عند الاستلام','tlcommerce','2023-02-18 04:28:44','2023-02-18 04:28:44'),
(631,'sa','Payment Option','خيار الدفع','tlcommerce','2023-02-18 04:28:44','2023-02-18 04:28:44'),
(632,'sa','Return Options','خيارات العودة','tlcommerce','2023-02-18 04:28:44','2023-02-18 04:28:44'),
(633,'sa','Change of mind is not applicable','تغيير الرأي لا ينطبق','tlcommerce','2023-02-18 04:28:44','2023-02-18 04:28:44'),
(634,'sa','Warranty','ضمان','tlcommerce','2023-02-18 04:28:44','2023-02-18 04:28:44'),
(635,'sa','Seller warranty','ضمان البائع','tlcommerce','2023-02-18 04:28:44','2023-02-18 04:28:44'),
(636,'sa','Reviews','المراجعات','tlcommerce','2023-02-18 04:28:44','2023-02-18 04:28:44'),
(637,'sa','Not available','غير متاح','tlcommerce','2023-02-18 04:28:44','2023-02-18 04:28:44'),
(638,'sa','Total Price','السعر الكلي','tlcommerce','2023-02-18 04:28:44','2023-02-18 04:28:44'),
(639,'sa','Price Range','نطاق السعر','tlcommerce','2023-02-18 04:28:44','2023-02-18 04:28:44'),
(640,'sa','are available','تتوفر','tlcommerce','2023-02-18 04:28:44','2023-02-18 04:28:44'),
(641,'sa','Compatible file extensions to upload: png, jpg, pdf','امتدادات الملفات المتوافقة للتحميل: png ، jpg ، pdf','tlcommerce','2023-02-18 04:28:44','2023-02-18 04:28:44'),
(642,'sa','Place Order','مكان الامر','tlcommerce','2023-02-18 04:28:44','2023-02-18 04:28:44'),
(643,'sa','Add To Cart','أضف إلى السلة','tlcommerce','2023-02-18 04:28:44','2023-02-18 04:28:44'),
(644,'sa','Add to Compare','أضف للمقارنة','tlcommerce','2023-02-18 04:28:44','2023-02-18 04:28:44'),
(645,'sa','Add to wishlist','أضف إلى قائمة الامنيات','tlcommerce','2023-02-18 04:28:44','2023-02-18 04:28:44'),
(646,'sa','Product Overview','نظرة عامة على المنتج','tlcommerce','2023-02-18 04:28:44','2023-02-18 04:28:44'),
(647,'sa','Top Selling Products','المنتجات الأكثر مبيعًا','tlcommerce','2023-02-18 04:28:44','2023-02-18 04:28:44'),
(648,'sa','Buyer Review','مراجعة المشتري','tlcommerce','2023-02-18 04:28:44','2023-02-18 04:28:44'),
(649,'sa','Recent','مؤخرًا','tlcommerce','2023-02-18 04:28:44','2023-02-18 04:28:44'),
(650,'sa','Rating: High to Low','التصنيف: من الأعلى إلى الأقل','tlcommerce','2023-02-18 04:28:44','2023-02-18 04:28:44'),
(651,'sa','Rating: Low to High','التصنيف: من الأقل إلى الأعلى','tlcommerce','2023-02-18 04:28:44','2023-02-18 04:28:44'),
(652,'sa','days','أيام','tlcommerce','2023-02-18 04:28:44','2023-02-18 04:28:44'),
(653,'sa','replacement','إستبدال','tlcommerce','2023-02-18 04:28:44','2023-02-18 04:28:44'),
(654,'sa','Compare','يقارن','tlcommerce','2023-02-18 04:28:44','2023-02-18 04:28:44'),
(655,'sa','Name','اسم','tlcommerce','2023-02-18 04:28:44','2023-02-18 04:28:44'),
(656,'sa','Availability','التوفر','tlcommerce','2023-02-18 04:28:44','2023-02-18 04:28:44'),
(657,'sa','Refund','استرداد','tlcommerce','2023-02-18 04:28:44','2023-02-18 04:28:44'),
(658,'sa','Summary','ملخص','tlcommerce','2023-02-18 04:28:44','2023-02-18 04:28:44'),
(659,'sa','In Stock','في الأوراق المالية','tlcommerce','2023-02-18 04:28:44','2023-02-18 04:28:44'),
(660,'sa','Email','بريد إلكتروني','tlcommerce','2023-02-18 04:28:44','2023-02-18 04:28:44'),
(661,'sa','If you have no account','إذا لم يكن لديك حساب','tlcommerce','2023-02-18 04:28:44','2023-02-18 04:28:44'),
(662,'sa','Password','كلمة المرور','tlcommerce','2023-02-18 04:28:44','2023-02-18 04:28:44'),
(663,'sa','Forgot Password','هل نسيت كلمة السر','tlcommerce','2023-02-18 04:28:44','2023-02-18 04:28:44'),
(664,'sa','Register Here','سجل هنا','tlcommerce','2023-02-18 04:28:44','2023-02-18 04:28:44'),
(665,'sa','Phone','هاتف','tlcommerce','2023-02-18 04:28:44','2023-02-18 04:28:44'),
(666,'sa','Register','يسجل','tlcommerce','2023-02-18 04:28:44','2023-02-18 04:28:44'),
(667,'sa','Phone Number','رقم التليفون','tlcommerce','2023-02-18 04:28:44','2023-02-18 04:28:44'),
(668,'sa','Confirm Password','تأكيد كلمة المرور','tlcommerce','2023-02-18 04:28:44','2023-02-18 04:28:44'),
(669,'sa','I have read and agree to the terms and conditions','لقد قرأت ووافقت على الشروط والأحكام','tlcommerce','2023-02-18 04:28:44','2023-02-18 04:28:44'),
(670,'sa','Already have an account','هل لديك حساب','tlcommerce','2023-02-18 04:28:44','2023-02-18 04:28:44'),
(671,'sa','Login Here','تسجيل الدخول من هنا','tlcommerce','2023-02-18 04:28:44','2023-02-18 04:28:44'),
(672,'sa','Review','مراجعة','tlcommerce','2023-02-18 04:28:44','2023-02-18 04:28:44'),
(673,'sa','Shipping','شحن','tlcommerce','2023-02-18 04:28:44','2023-02-18 04:28:44'),
(674,'sa','Payments','المدفوعات','tlcommerce','2023-02-18 04:28:44','2023-02-18 04:28:44'),
(675,'sa','Delivery & Shipping','تسليم الشحن','tlcommerce','2023-02-18 04:28:44','2023-02-18 04:28:44'),
(676,'sa','Home Delivery','توصيل منزلي','tlcommerce','2023-02-18 04:28:44','2023-02-18 04:28:44'),
(677,'sa','Collect From Store','الاستلام من المتجر','tlcommerce','2023-02-18 04:28:44','2023-02-18 04:28:44'),
(678,'sa','Personal Information','معلومات شخصية','tlcommerce','2023-02-18 04:28:44','2023-02-18 04:28:44'),
(679,'sa','Your Name','اسمك','tlcommerce','2023-02-18 04:28:44','2023-02-18 04:28:44'),
(680,'sa','Create an Account','إنشاء حساب','tlcommerce','2023-02-18 04:28:44','2023-02-18 04:28:44'),
(681,'sa','Shipping Details','تفاصيل الشحن','tlcommerce','2023-02-18 04:28:44','2023-02-18 04:28:44'),
(682,'sa','Email Address','عنوان البريد الإلكتروني','tlcommerce','2023-02-18 04:28:44','2023-02-18 04:28:44'),
(683,'sa','Address','عنوان','tlcommerce','2023-02-18 04:28:44','2023-02-18 04:28:44'),
(684,'sa','Postal Code','رمز بريدي','tlcommerce','2023-02-18 04:28:44','2023-02-18 04:28:44'),
(685,'sa','Previous','سابق','tlcommerce','2023-02-18 04:28:44','2023-02-18 04:28:44'),
(686,'sa','Continue','يكمل','tlcommerce','2023-02-18 04:28:44','2023-02-18 04:28:44'),
(687,'sa','Product','منتج','tlcommerce','2023-02-18 04:28:44','2023-02-18 04:28:44'),
(688,'sa','Tax','ضريبة','tlcommerce','2023-02-18 04:28:44','2023-02-18 04:28:44'),
(689,'sa','Payable Total','مجموع المدفوعات','tlcommerce','2023-02-18 04:28:44','2023-02-18 04:28:44'),
(690,'sa','Shipping Cost','تكلفة الشحن','tlcommerce','2023-02-18 04:28:44','2023-02-18 04:28:44'),
(691,'sa','Your Coupon','قسيمتك','tlcommerce','2023-02-18 04:28:44','2023-02-18 04:28:44'),
(692,'sa','Select Country','حدد الدولة','tlcommerce','2023-02-18 04:28:44','2023-02-18 04:28:44'),
(693,'sa','Apply','يتقدم','tlcommerce','2023-02-18 04:28:44','2023-02-18 04:28:44'),
(694,'sa','Select pickup point','حدد نقطة الالتقاط','tlcommerce','2023-02-18 04:28:44','2023-02-18 04:28:44'),
(695,'sa','Select State','اختر ولايه','tlcommerce','2023-02-18 04:28:44','2023-02-18 04:28:44'),
(696,'sa','Select City','اختر مدينة','tlcommerce','2023-02-18 04:28:44','2023-02-18 04:28:44'),
(697,'sa','Pickup Point','نقطة الالتقاط','tlcommerce','2023-02-18 04:28:44','2023-02-18 04:28:44'),
(698,'sa','Name is required','مطلوب اسم','tlcommerce','2023-02-18 04:28:44','2023-02-18 04:28:44'),
(699,'sa','Email is required','البريد الالكتروني مطلوب','tlcommerce','2023-02-18 04:28:44','2023-02-18 04:28:44'),
(700,'sa','Phone is required','الهاتف مطلوب','tlcommerce','2023-02-18 04:28:44','2023-02-18 04:28:44'),
(701,'sa','Postal code is required','الرمز البريدي مطلوب','tlcommerce','2023-02-18 04:28:44','2023-02-18 04:28:44'),
(702,'sa','Address is required','العنوان مطلوب','tlcommerce','2023-02-18 04:28:44','2023-02-18 04:28:44'),
(703,'sa','Please select a country','رجاء قم بإختيار دوله','tlcommerce','2023-02-18 04:28:44','2023-02-18 04:28:44'),
(704,'sa','Please select a state','الرجاء تحديد ولاية','tlcommerce','2023-02-18 04:28:44','2023-02-18 04:28:44'),
(705,'sa','Please select a city','الرجاء تحديد مدينة','tlcommerce','2023-02-18 04:28:44','2023-02-18 04:28:44'),
(706,'sa','Please wait','انتظر من فضلك','tlcommerce','2023-02-18 04:28:44','2023-02-18 04:28:44'),
(707,'sa','Registration successful. check your email to verify your email','تحقق من بريدك الإلكتروني للتحقق من بريدك الإلكتروني','tlcommerce','2023-02-18 04:28:44','2023-02-18 04:28:44'),
(708,'sa','Login successful','تم تسجيل الدخول بنجاح','tlcommerce','2023-02-18 04:28:44','2023-02-18 04:28:44'),
(709,'sa','notifications','إشعارات','tlcommerce','2023-02-18 04:28:44','2023-02-18 04:28:44'),
(710,'sa','Dashboard','لوحة القيادة','tlcommerce','2023-02-18 04:28:44','2023-02-18 04:28:44'),
(711,'sa','Logout','تسجيل خروج','tlcommerce','2023-02-18 04:28:44','2023-02-18 04:28:44'),
(712,'sa','Total Purchase Amount','إجمالي مبلغ الشراء','tlcommerce','2023-02-18 04:28:44','2023-02-18 04:28:44'),
(713,'sa','Last Purchase','آخر عملية شراء','tlcommerce','2023-02-18 04:28:44','2023-02-18 04:28:44'),
(714,'sa','Total Purchase in','إجمالي الشراء في','tlcommerce','2023-02-18 04:28:44','2023-02-18 04:28:44'),
(715,'sa','Last Month Purchase','الشراء الشهر الماضي','tlcommerce','2023-02-18 04:28:44','2023-02-18 04:28:44'),
(716,'sa','Latest Purchase','آخر عملية شراء','tlcommerce','2023-02-18 04:28:44','2023-02-18 04:28:44'),
(717,'sa','Refund Requests','طلبات الاسترداد','tlcommerce','2023-02-18 04:28:44','2023-02-18 04:28:44'),
(718,'sa','Purchase History','تاريخ شراء','tlcommerce','2023-02-18 04:28:45','2023-02-18 04:28:45'),
(719,'sa','My Wallet','محفظتى','tlcommerce','2023-02-18 04:28:45','2023-02-18 04:28:45'),
(720,'sa','Manage Account','إدارة الحساب','tlcommerce','2023-02-18 04:28:45','2023-02-18 04:28:45'),
(721,'sa','Total Order','من أجل الكاملة','tlcommerce','2023-02-18 04:28:45','2023-02-18 04:28:45'),
(722,'sa','Pending Orders','الأوامر المعلقة','tlcommerce','2023-02-18 04:28:45','2023-02-18 04:28:45'),
(723,'sa','All','الجميع','tlcommerce','2023-02-18 04:28:45','2023-02-18 04:28:45'),
(724,'sa','Add new address','أضف عنوان جديد','tlcommerce','2023-02-18 04:28:45','2023-02-18 04:28:45'),
(725,'sa','Available Balance','الرصيد المتوفر','tlcommerce','2023-02-18 04:28:45','2023-02-18 04:28:45'),
(726,'sa','Recharge wallet','إعادة شحن المحفظة','tlcommerce','2023-02-18 04:28:45','2023-02-18 04:28:45'),
(727,'sa','Pending Balance','رصيد معلق','tlcommerce','2023-02-18 04:28:45','2023-02-18 04:28:45'),
(728,'sa','Basic Information','معلومات اساسية','tlcommerce','2023-02-18 04:28:45','2023-02-18 04:28:45'),
(729,'sa','Image','صورة','tlcommerce','2023-02-18 04:28:45','2023-02-18 04:28:45'),
(730,'sa','Update Profile','تحديث الملف','tlcommerce','2023-02-18 04:28:45','2023-02-18 04:28:45'),
(731,'sa','Change Password','تغيير كلمة المرور','tlcommerce','2023-02-18 04:28:45','2023-02-18 04:28:45'),
(732,'sa','Generate password reset link','إنشاء رابط إعادة تعيين كلمة المرور','tlcommerce','2023-02-18 04:28:45','2023-02-18 04:28:45'),
(733,'sa','Change Email','تغيير الايميل','tlcommerce','2023-02-18 04:28:45','2023-02-18 04:28:45'),
(734,'sa','Generate email reset link','إنشاء رابط إعادة تعيين البريد الإلكتروني','tlcommerce','2023-02-18 04:28:45','2023-02-18 04:28:45'),
(735,'sa','Categories','فئات','tlcommerce','2023-02-18 04:28:45','2023-02-18 04:28:45'),
(736,'sa','No address found','لم يتم العثور على عنوان','tlcommerce','2023-02-18 04:28:45','2023-02-18 04:28:45'),
(737,'sa','Manage Address','إدارة العنوان','tlcommerce','2023-02-18 04:28:45','2023-02-18 04:28:45'),
(738,'sa','Address Information','معلومات العنوان','tlcommerce','2023-02-18 04:28:45','2023-02-18 04:28:45'),
(739,'sa','Country','دولة','tlcommerce','2023-02-18 04:28:45','2023-02-18 04:28:45'),
(740,'sa','State','ولاية','tlcommerce','2023-02-18 04:28:45','2023-02-18 04:28:45'),
(741,'sa','City','مدينة','tlcommerce','2023-02-18 04:28:45','2023-02-18 04:28:45'),
(742,'sa','Save address','حفظ العنوان','tlcommerce','2023-02-18 04:28:45','2023-02-18 04:28:45'),
(743,'sa','Please choose a pickup point','الرجاء اختيار نقطة الالتقاء','tlcommerce','2023-02-18 04:28:45','2023-02-18 04:28:45'),
(744,'sa','Logout successful','نجح تسجيل الخروج','tlcommerce','2023-02-18 04:28:45','2023-02-18 04:28:45'),
(745,'sa','Address:','عنوان:','tlcommerce','2023-02-18 04:28:45','2023-02-18 04:28:45'),
(746,'sa','Postal Code:','رمز بريدي:','tlcommerce','2023-02-18 04:28:45','2023-02-18 04:28:45'),
(747,'sa','Phone:','هاتف:','tlcommerce','2023-02-18 04:28:45','2023-02-18 04:28:45'),
(748,'sa','Delivery not available at this location','التسليم غير متوفر في هذا الموقع','tlcommerce','2023-02-18 04:28:45','2023-02-18 04:28:45'),
(749,'sa','Status','حالة','tlcommerce','2023-02-18 04:28:45','2023-02-18 04:28:45'),
(750,'sa','Edit','يحرر','tlcommerce','2023-02-18 04:28:45','2023-02-18 04:28:45'),
(751,'sa','Actions','أجراءات','tlcommerce','2023-02-18 04:28:45','2023-02-18 04:28:45'),
(752,'sa','ID','بطاقة تعريف','tlcommerce','2023-02-18 04:28:45','2023-02-18 04:28:45'),
(753,'sa','Order Date','تاريخ الطلب','tlcommerce','2023-02-18 04:28:45','2023-02-18 04:28:45'),
(754,'sa','Amount','كمية','tlcommerce','2023-02-18 04:28:45','2023-02-18 04:28:45'),
(755,'sa','Num of Products','عدد المنتجات','tlcommerce','2023-02-18 04:28:45','2023-02-18 04:28:45'),
(756,'sa','Details','تفاصيل','tlcommerce','2023-02-18 04:28:45','2023-02-18 04:28:45'),
(757,'sa','Order Details','تفاصيل الطلب','tlcommerce','2023-02-18 04:28:45','2023-02-18 04:28:45'),
(758,'sa','Order Placed on','ترتيب وضعها على','tlcommerce','2023-02-18 04:28:45','2023-02-18 04:28:45'),
(759,'sa','Order ID','رقم التعريف الخاص بالطلب','tlcommerce','2023-02-18 04:28:45','2023-02-18 04:28:45'),
(760,'sa','Package','طَرد','tlcommerce','2023-02-18 04:28:45','2023-02-18 04:28:45'),
(761,'sa','Shipping Address','عنوان الشحن','tlcommerce','2023-02-18 04:28:45','2023-02-18 04:28:45'),
(762,'sa','Billing Address','عنوان وصول الفواتير','tlcommerce','2023-02-18 04:28:45','2023-02-18 04:28:45'),
(763,'sa','Total Summary','إجمالي الملخص','tlcommerce','2023-02-18 04:28:45','2023-02-18 04:28:45'),
(764,'sa','Processing','يعالج','tlcommerce','2023-02-18 04:28:45','2023-02-18 04:28:45'),
(765,'sa','Shipped','شحنها','tlcommerce','2023-02-18 04:28:45','2023-02-18 04:28:45'),
(766,'sa','Payment method','طريقة الدفع او السداد','tlcommerce','2023-02-18 04:28:45','2023-02-18 04:28:45'),
(767,'sa','Delivered','تم التوصيل','tlcommerce','2023-02-18 04:28:45','2023-02-18 04:28:45'),
(768,'sa','Update Address Information','تحديث معلومات العنوان','tlcommerce','2023-02-18 04:28:45','2023-02-18 04:28:45'),
(769,'sa','Active','نشيط','tlcommerce','2023-02-18 04:28:45','2023-02-18 04:28:45'),
(770,'sa','Inactive','غير نشط','tlcommerce','2023-02-18 04:28:45','2023-02-18 04:28:45'),
(771,'sa','Default Shipping Address','عنوان الشحن الافتراضي','tlcommerce','2023-02-18 04:28:45','2023-02-18 04:28:45'),
(772,'sa','Default Billing Address','عنوان الفواتير الافتراضي','tlcommerce','2023-02-18 04:28:45','2023-02-18 04:28:45'),
(773,'sa','Save Changes','حفظ التغييرات','tlcommerce','2023-02-18 04:28:45','2023-02-18 04:28:45'),
(774,'sa','Date','تاريخ','tlcommerce','2023-02-18 04:28:45','2023-02-18 04:28:45'),
(775,'sa','Type','يكتب','tlcommerce','2023-02-18 04:28:45','2023-02-18 04:28:45'),
(776,'bd','Enter your search key','আপনার অনুসন্ধান কী লিখুন','tlcommerce','2023-02-18 04:31:19','2023-02-18 04:31:19'),
(777,'bd','Registration','নিবন্ধন','tlcommerce','2023-02-18 04:31:19','2023-02-18 04:31:19'),
(778,'bd','Login','প্রবেশ করুন','tlcommerce','2023-02-18 04:31:19','2023-02-18 04:31:19'),
(779,'bd','Search','অনুসন্ধান করুন','tlcommerce','2023-02-18 04:31:19','2023-02-18 04:31:19'),
(780,'bd','Category','শ্রেণী','tlcommerce','2023-02-18 04:31:19','2023-02-18 04:31:19'),
(781,'bd','Home','বাড়ি','tlcommerce','2023-02-18 04:31:19','2023-02-18 04:31:19'),
(782,'bd','Wishlist','ইচ্ছেতালিকা','tlcommerce','2023-02-18 04:31:19','2023-02-18 04:31:19'),
(783,'bd','My Account','আমার অ্যাকাউন্ট','tlcommerce','2023-02-18 04:31:19','2023-02-18 04:31:19'),
(784,'bd','Cart','কার্ট','tlcommerce','2023-02-18 04:31:19','2023-02-18 04:31:19'),
(785,'bd','expand_more','প্রসারিত_আরো','tlcommerce','2023-02-18 04:31:19','2023-02-18 04:31:19'),
(786,'bd','Select Categories','বিভাগ নির্বাচন করুন','tlcommerce','2023-02-18 04:31:19','2023-02-18 04:31:19'),
(787,'bd','All Categories','সব বিভাগ','tlcommerce','2023-02-18 04:31:19','2023-03-01 14:54:51'),
(788,'bd','All Products','সব পণ্য','tlcommerce','2023-02-18 04:31:19','2023-02-18 04:31:19'),
(789,'bd','Subscribe','সাবস্ক্রাইব','tlcommerce','2023-02-18 04:31:19','2023-02-18 04:31:19'),
(790,'bd','Enter your email','তুমার ইমেইল প্রবেশ করাও','tlcommerce','2023-02-18 04:31:19','2023-02-18 04:31:19'),
(791,'bd','Your Cart','আপনার কার্ট','tlcommerce','2023-02-18 04:31:19','2023-02-18 04:31:19'),
(792,'bd','Checkout','চেকআউট','tlcommerce','2023-02-18 04:31:19','2023-02-18 04:31:19'),
(793,'bd','Subtotal','সাবটোটাল','tlcommerce','2023-02-18 04:31:19','2023-02-18 04:31:19'),
(794,'bd','Unit Price','একক দাম','tlcommerce','2023-02-18 04:31:19','2023-02-18 04:31:19'),
(795,'bd','Quantity','পরিমাণ','tlcommerce','2023-02-18 04:31:19','2023-02-18 04:31:19'),
(796,'bd','Product Name','পণ্যের নাম','tlcommerce','2023-02-18 04:31:19','2023-02-18 04:31:19'),
(797,'bd','Total','মোট','tlcommerce','2023-02-18 04:31:19','2023-02-18 04:31:19'),
(798,'bd','Remove','অপসারণ','tlcommerce','2023-02-18 04:31:19','2023-02-18 04:31:19'),
(799,'bd','Continue Shopping','কেনাকাটা চালিয়ে যান','tlcommerce','2023-02-18 04:31:19','2023-02-18 04:31:19'),
(800,'bd','Buy Now','এখন কেন','tlcommerce','2023-02-18 04:31:19','2023-02-18 04:31:19'),
(801,'bd','View','দেখুন','tlcommerce','2023-02-18 04:31:19','2023-02-18 04:31:19'),
(802,'bd','Half Sleeve Jersey T-shirt for Men','পুরুষদের জন্য হাফ হাতা জার্সি টি-শার্ট','tlcommerce','2023-02-18 04:31:19','2023-02-18 04:31:19'),
(803,'bd','Blogs','ব্লগ','tlcommerce','2023-02-18 04:31:19','2023-02-18 04:31:19'),
(804,'bd','See all','সবগুলো দেখ','tlcommerce','2023-02-18 04:31:19','2023-02-18 04:31:19'),
(805,'bd','Read more','আরও পড়ুন','tlcommerce','2023-02-18 04:31:19','2023-02-18 04:31:19'),
(806,'bd','day','দিন','tlcommerce','2023-02-18 04:31:19','2023-02-18 04:31:19'),
(807,'bd','minute','মিনিট','tlcommerce','2023-02-18 04:31:19','2023-02-18 04:31:19'),
(808,'bd','hour','ঘন্টা','tlcommerce','2023-02-18 04:31:19','2023-02-18 04:31:19'),
(809,'bd','Second','সেকেন্ড','tlcommerce','2023-02-18 04:31:19','2023-02-18 04:44:30'),
(810,'bd','View All','সব দেখ','tlcommerce','2023-02-18 04:31:19','2023-02-18 04:31:19'),
(811,'bd','You may like it','আপনি এটা পছন্দ করতে পারে','tlcommerce','2023-02-18 04:31:19','2023-02-18 04:31:19'),
(812,'bd','Tags:','ট্যাগ:','tlcommerce','2023-02-18 04:31:19','2023-02-18 04:31:19'),
(813,'bd','Categories:','বিভাগ:','tlcommerce','2023-02-18 04:31:19','2023-02-18 04:31:19'),
(814,'bd','Products','পণ্য','tlcommerce','2023-02-18 04:31:19','2023-02-18 04:31:19'),
(815,'bd','Rating','রেটিং','tlcommerce','2023-02-18 04:31:19','2023-02-18 04:31:19'),
(816,'bd','Price','দাম','tlcommerce','2023-02-18 04:31:19','2023-02-18 04:31:19'),
(817,'bd','Sort By','ক্রমানুসার','tlcommerce','2023-02-18 04:31:19','2023-02-18 04:31:19'),
(818,'bd','Popular items','জনপ্রিয় আইটেম','tlcommerce','2023-02-18 04:31:19','2023-02-18 04:31:19'),
(819,'bd','No item found','কোন আইটেম পাওয়া যায়নি','tlcommerce','2023-02-18 04:31:19','2023-02-18 04:31:19'),
(820,'bd','Newest items','নতুন আইটেম','tlcommerce','2023-02-18 04:31:19','2023-02-18 04:31:19'),
(821,'bd','Price low to high','দাম কম থেকে বেশি','tlcommerce','2023-02-18 04:31:19','2023-02-18 04:31:19'),
(822,'bd','Price high to low','দাম বেশি থেকে কম','tlcommerce','2023-02-18 04:31:19','2023-02-18 04:31:19'),
(823,'bd','Top Categories','শীর্ষ বিভাগ','tlcommerce','2023-02-18 04:31:19','2023-02-18 04:31:19'),
(824,'bd','Brand','ব্র্যান্ড','tlcommerce','2023-02-18 04:31:19','2023-02-18 04:31:19'),
(825,'bd','More','আরও','tlcommerce','2023-02-18 04:31:19','2023-02-18 04:31:19'),
(826,'bd','View More','আরো দেখুন','tlcommerce','2023-02-18 04:31:19','2023-02-18 04:31:19'),
(827,'bd','items found','আইটেম পাওয়া গেছে','tlcommerce','2023-02-18 04:31:19','2023-03-01 14:58:40'),
(828,'bd','Feedback','প্রতিক্রিয়া','tlcommerce','2023-02-18 04:31:19','2023-02-18 04:31:19'),
(829,'bd','Filtered By','দ্বারা ফিল্টার করা','tlcommerce','2023-02-18 04:31:19','2023-02-18 04:31:19'),
(830,'bd','CLEAR ALL','সব পরিষ্কার করে দাও','tlcommerce','2023-02-18 04:31:19','2023-02-18 04:31:19'),
(831,'bd','Overview','ওভারভিউ','tlcommerce','2023-02-18 04:31:19','2023-02-18 04:31:19'),
(832,'bd','Product Details','পণ্যের বিবরণ','tlcommerce','2023-02-18 04:31:19','2023-02-18 04:31:19'),
(833,'bd','Quick Connect','দ্রত যোগাযোগ','tlcommerce','2023-02-18 04:31:19','2023-02-18 04:31:19'),
(834,'bd','Recommendations','সুপারিশ','tlcommerce','2023-02-18 04:31:19','2023-02-18 04:31:19'),
(835,'bd','Conditions','শর্তাবলী','tlcommerce','2023-02-18 04:31:19','2023-02-18 04:31:19'),
(836,'bd','Product Condition','পণ্যের অবস্থা','tlcommerce','2023-02-18 04:31:19','2023-02-18 04:31:19'),
(837,'bd','Authentic','প্রামাণিক','tlcommerce','2023-02-18 04:31:19','2023-02-18 04:31:19'),
(838,'bd','Cash on Delivery','প্রদানোত্তর পরিশোধ','tlcommerce','2023-02-18 04:31:19','2023-02-18 04:31:19'),
(839,'bd','Payment Option','পেমেন্ট অপশন','tlcommerce','2023-02-18 04:31:19','2023-02-18 04:31:19'),
(840,'bd','Return Options','রিটার্ন অপশন','tlcommerce','2023-02-18 04:31:19','2023-02-18 04:31:19'),
(841,'bd','Change of mind is not applicable','মন পরিবর্তন প্রযোজ্য নয়','tlcommerce','2023-02-18 04:31:19','2023-02-18 04:31:19'),
(842,'bd','Warranty','ওয়ারেন্টি','tlcommerce','2023-02-18 04:31:19','2023-02-18 04:31:19'),
(843,'bd','Seller warranty','বিক্রেতার ওয়ারেন্টি','tlcommerce','2023-02-18 04:31:19','2023-02-18 04:31:19'),
(844,'bd','Reviews','রিভিউ','tlcommerce','2023-02-18 04:31:19','2023-02-18 04:31:19'),
(845,'bd','Not available','পাওয়া যায় না','tlcommerce','2023-02-18 04:31:19','2023-02-18 04:31:19'),
(846,'bd','Total Price','মোট দাম','tlcommerce','2023-02-18 04:31:19','2023-02-18 04:31:19'),
(847,'bd','Price Range','মূল্য পরিসীমা','tlcommerce','2023-02-18 04:31:19','2023-02-18 04:31:19'),
(848,'bd','are available','সহজ প্রাপ্য','tlcommerce','2023-02-18 04:31:19','2023-02-18 04:31:19'),
(849,'bd','Compatible file extensions to upload: png, jpg, pdf','আপলোড করার জন্য সামঞ্জস্যপূর্ণ ফাইল এক্সটেনশন: png, jpg, pdf','tlcommerce','2023-02-18 04:31:19','2023-02-18 04:31:19'),
(850,'bd','Place Order','অর্ডার করুন','tlcommerce','2023-02-18 04:31:19','2023-02-18 04:31:19'),
(851,'bd','Add To Cart','কার্টে যোগ করুন','tlcommerce','2023-02-18 04:31:19','2023-02-18 04:31:19'),
(852,'bd','Add to Compare','তুলনা যোগ করুন','tlcommerce','2023-02-18 04:31:19','2023-02-18 04:31:19'),
(853,'bd','Add to wishlist','চাহিদাপত্রে যোগ করা','tlcommerce','2023-02-18 04:31:19','2023-02-18 04:31:19'),
(854,'bd','Product Overview','পন্যের স্বল্প বিবরনী','tlcommerce','2023-02-18 04:31:19','2023-02-18 04:31:19'),
(855,'bd','Top Selling Products','শীর্ষ বিক্রয় পণ্য','tlcommerce','2023-02-18 04:31:19','2023-02-18 04:31:19'),
(856,'bd','Buyer Review','ক্রেতা পর্যালোচনা','tlcommerce','2023-02-18 04:31:19','2023-02-18 04:31:19'),
(857,'bd','Recent','সাম্প্রতিক','tlcommerce','2023-02-18 04:31:19','2023-02-18 04:31:19'),
(858,'bd','Rating: High to Low','রেটিং: উচ্চ থেকে নিম্ন','tlcommerce','2023-02-18 04:31:19','2023-02-18 04:31:19'),
(859,'bd','Rating: Low to High','রেটিং: নিম্ন থেকে উচ্চ','tlcommerce','2023-02-18 04:31:19','2023-02-18 04:31:19'),
(860,'bd','days','দিন','tlcommerce','2023-02-18 04:31:19','2023-02-18 04:31:19'),
(861,'bd','replacement','প্রতিস্থাপন','tlcommerce','2023-02-18 04:31:19','2023-02-18 04:31:19'),
(862,'bd','Compare','তুলনা করা','tlcommerce','2023-02-18 04:31:19','2023-02-18 04:31:19'),
(863,'bd','Name','নাম','tlcommerce','2023-02-18 04:31:19','2023-02-18 04:31:19'),
(864,'bd','Availability','উপস্থিতি','tlcommerce','2023-02-18 04:31:19','2023-02-18 04:31:19'),
(865,'bd','Refund','ফেরত','tlcommerce','2023-02-18 04:31:19','2023-02-18 04:31:19'),
(866,'bd','Summary','সারসংক্ষেপ','tlcommerce','2023-02-18 04:31:19','2023-02-18 04:31:19'),
(867,'bd','In Stock','স্টকে','tlcommerce','2023-02-18 04:31:19','2023-02-18 04:31:19'),
(868,'bd','Email','ইমেইল','tlcommerce','2023-02-18 04:31:19','2023-02-18 04:31:19'),
(869,'bd','If you have no account','যদি আপনার কোন একাউন্ট না থাকে','tlcommerce','2023-02-18 04:31:19','2023-02-18 05:42:04'),
(870,'bd','Password','পাসওয়ার্ড','tlcommerce','2023-02-18 04:31:19','2023-02-18 04:31:19'),
(871,'bd','Forgot Password','পাসওয়ার্ড ভুলে গেছেন','tlcommerce','2023-02-18 04:31:19','2023-02-18 04:31:19'),
(872,'bd','Register Here','এখানে নিবন্ধন করুন','tlcommerce','2023-02-18 04:31:19','2023-02-18 04:31:19'),
(873,'bd','Phone','ফোন','tlcommerce','2023-02-18 04:31:19','2023-02-18 04:31:19'),
(874,'bd','Register','নিবন্ধন','tlcommerce','2023-02-18 04:31:19','2023-02-18 04:31:19'),
(875,'bd','Phone Number','ফোন নম্বর','tlcommerce','2023-02-18 04:31:19','2023-02-18 04:31:19'),
(876,'bd','Confirm Password','পাসওয়ার্ড নিশ্চিত করুন','tlcommerce','2023-02-18 04:31:19','2023-02-18 04:31:19'),
(877,'bd','I have read and agree to the terms and conditions','আমি শর্তাবলী পড়েছি এবং তাতে সম্মত','tlcommerce','2023-02-18 04:31:19','2023-02-18 04:31:19'),
(878,'bd','Already have an account','ইতিমধ্যে একটি সদস্যপদ আছে','tlcommerce','2023-02-18 04:31:19','2023-02-18 04:31:19'),
(879,'bd','Login Here','এখানে লগইন করুন','tlcommerce','2023-02-18 04:31:19','2023-02-18 04:31:19'),
(880,'bd','Review','পুনঃমূল্যায়ন','tlcommerce','2023-02-18 04:31:19','2023-02-18 04:31:19'),
(881,'bd','Shipping','শিপিং','tlcommerce','2023-02-18 04:31:19','2023-02-22 17:33:18'),
(882,'bd','Payments','পেমেন্ট','tlcommerce','2023-02-18 04:31:19','2023-02-18 04:31:19'),
(883,'bd','Delivery & Shipping','ডেলিভারি এবং শিপিং','tlcommerce','2023-02-18 04:31:19','2023-02-18 04:31:19'),
(884,'bd','Home Delivery','হোম ডেলিভারি','tlcommerce','2023-02-18 04:31:19','2023-02-18 04:31:19'),
(885,'bd','Collect From Store','স্টোর থেকে সংগ্রহ করুন','tlcommerce','2023-02-18 04:31:19','2023-02-18 04:31:19'),
(886,'bd','Personal Information','ব্যক্তিগত তথ্য','tlcommerce','2023-02-18 04:31:19','2023-02-18 04:31:19'),
(887,'bd','Your Name','তোমার নাম','tlcommerce','2023-02-18 04:31:19','2023-02-18 04:31:19'),
(888,'bd','Create an Account','একটি অ্যাকাউন্ট তৈরি করুন','tlcommerce','2023-02-18 04:31:19','2023-02-18 04:31:19'),
(889,'bd','Shipping Details','শিপিং বিবরণ','tlcommerce','2023-02-18 04:31:19','2023-02-22 17:33:18'),
(890,'bd','Email Address','ইমেইল ঠিকানা','tlcommerce','2023-02-18 04:31:19','2023-02-18 04:31:19'),
(891,'bd','Address','ঠিকানা','tlcommerce','2023-02-18 04:31:19','2023-02-18 04:31:19'),
(892,'bd','Postal Code','পোস্ট অফিসের নাম্বার','tlcommerce','2023-02-18 04:31:19','2023-02-18 04:31:19'),
(893,'bd','Previous','আগে','tlcommerce','2023-02-18 04:31:19','2023-02-18 04:31:19'),
(894,'bd','Continue','চালিয়ে যান','tlcommerce','2023-02-18 04:31:19','2023-02-18 04:31:19'),
(895,'bd','Product','পণ্য','tlcommerce','2023-02-18 04:31:19','2023-02-18 04:31:19'),
(896,'bd','Tax','ট্যাক্স','tlcommerce','2023-02-18 04:31:19','2023-02-18 04:31:19'),
(897,'bd','Payable Total','প্রদেয় মোট','tlcommerce','2023-02-18 04:31:19','2023-02-18 04:31:19'),
(898,'bd','Shipping Cost','শিপিং খরচ','tlcommerce','2023-02-18 04:31:19','2023-02-22 17:33:18'),
(899,'bd','Your Coupon','আপনার কুপন','tlcommerce','2023-02-18 04:31:19','2023-02-18 04:31:19'),
(900,'bd','Select Country','দেশ নির্বাচন করুন','tlcommerce','2023-02-18 04:31:19','2023-02-18 04:31:19'),
(901,'bd','Apply','আবেদন করুন','tlcommerce','2023-02-18 04:31:19','2023-02-18 04:31:19'),
(902,'bd','Select pickup point','পিকআপ পয়েন্ট নির্বাচন করুন','tlcommerce','2023-02-18 04:31:19','2023-02-18 04:31:19'),
(903,'bd','Select State','রাজ্য নির্বাচন কর','tlcommerce','2023-02-18 04:31:19','2023-02-18 04:31:19'),
(904,'bd','Select City','শহর নির্বাচন করুন','tlcommerce','2023-02-18 04:31:19','2023-02-18 04:31:19'),
(905,'bd','Pickup Point','সংগ্রহের স্থান','tlcommerce','2023-02-18 04:31:19','2023-02-18 04:31:19'),
(906,'bd','Name is required','নাম আবশ্যক','tlcommerce','2023-02-18 04:31:19','2023-02-18 04:31:19'),
(907,'bd','Email is required','ইমেল প্রয়োজন','tlcommerce','2023-02-18 04:31:19','2023-02-18 04:31:19'),
(908,'bd','Phone is required','ফোন প্রয়োজন','tlcommerce','2023-02-18 04:31:19','2023-02-18 04:31:19'),
(909,'bd','Postal code is required','পোস্টাল কোড প্রয়োজন','tlcommerce','2023-02-18 04:31:19','2023-02-18 04:31:19'),
(910,'bd','Address is required','ঠিকানা প্রয়োজন','tlcommerce','2023-02-18 04:31:19','2023-02-18 04:31:19'),
(911,'bd','Please select a country','একটি দেশ নির্বাচন করুন','tlcommerce','2023-02-18 04:31:19','2023-02-18 04:31:19'),
(912,'bd','Please select a state','একটি রাজ্য নির্বাচন করুন','tlcommerce','2023-02-18 04:31:19','2023-02-18 04:31:19'),
(913,'bd','Please select a city','একটি শহর নির্বাচন করুন','tlcommerce','2023-02-18 04:31:19','2023-02-18 04:31:19'),
(914,'bd','Please wait','অনুগ্রহপূর্বক অপেক্ষা করুন','tlcommerce','2023-02-18 04:31:19','2023-02-18 04:31:19'),
(915,'bd','Registration successful. check your email to verify your email','আপনার ইমেল যাচাই করতে আপনার ইমেল চেক করুন','tlcommerce','2023-02-18 04:31:19','2023-02-18 04:31:19'),
(916,'bd','Login successful','সফল লগইন','tlcommerce','2023-02-18 04:31:19','2023-02-18 04:31:19'),
(917,'bd','notifications','বিজ্ঞপ্তি','tlcommerce','2023-02-18 04:31:19','2023-02-18 04:31:19'),
(918,'bd','Dashboard','ড্যাশবোর্ড','tlcommerce','2023-02-18 04:31:19','2023-02-18 04:31:19'),
(919,'bd','Logout','প্রস্থান','tlcommerce','2023-02-18 04:31:19','2023-02-18 04:31:19'),
(920,'bd','Total Purchase Amount','মোট ক্রয়ের পরিমাণ','tlcommerce','2023-02-18 04:31:19','2023-02-18 04:31:19'),
(921,'bd','Last Purchase','শেষ ক্রয়','tlcommerce','2023-02-18 04:31:19','2023-02-18 04:31:19'),
(922,'bd','Total Purchase in','মধ্যে মোট ক্রয়','tlcommerce','2023-02-18 04:31:19','2023-02-18 04:31:19'),
(923,'bd','Last Month Purchase','গত মাসের কেনাকাটা','tlcommerce','2023-02-18 04:31:19','2023-02-18 04:31:19'),
(924,'bd','Latest Purchase','সর্বশেষ ক্রয়','tlcommerce','2023-02-18 04:31:19','2023-02-18 04:31:19'),
(925,'bd','Refund Requests','ফেরত অনুরোধ','tlcommerce','2023-02-18 04:31:19','2023-02-18 04:31:19'),
(926,'bd','Purchase History','ক্রয় ইতিহাস','tlcommerce','2023-02-18 04:31:19','2023-02-18 04:31:19'),
(927,'bd','My Wallet','আমার ওয়ালেট','tlcommerce','2023-02-18 04:31:19','2023-02-19 17:26:49'),
(928,'bd','Manage Account','অ্যাকাউন্ট পরিচালনা','tlcommerce','2023-02-18 04:31:19','2023-02-18 04:31:19'),
(929,'bd','Total Order','মোট অর্ডার','tlcommerce','2023-02-18 04:31:19','2023-02-18 04:31:19'),
(930,'bd','Pending Orders','পেন্ডিং অর্ডার','tlcommerce','2023-02-18 04:31:19','2023-02-22 19:46:09'),
(931,'bd','All','সব','tlcommerce','2023-02-18 04:31:19','2023-02-18 04:31:19'),
(932,'bd','Add new address','নতুন ঠিকানা যোগ করুন','tlcommerce','2023-02-18 04:31:19','2023-02-18 04:31:19'),
(933,'bd','Available Balance','পর্যাপ্ত টাকা','tlcommerce','2023-02-18 04:31:19','2023-02-18 04:31:19'),
(934,'bd','Recharge wallet','রিচার্জ ওয়ালেট','tlcommerce','2023-02-18 04:31:19','2023-02-18 04:31:19'),
(935,'bd','Pending Balance','মুলতুবি ভারসাম্য','tlcommerce','2023-02-18 04:31:19','2023-02-18 04:31:19'),
(936,'bd','Basic Information','মৌলিক তথ্য','tlcommerce','2023-02-18 04:31:19','2023-02-18 04:31:19'),
(937,'bd','Image','ছবি','tlcommerce','2023-02-18 04:31:19','2023-02-18 04:31:19'),
(938,'bd','Update Profile','প্রফাইল হালনাগাদ','tlcommerce','2023-02-18 04:31:19','2023-02-18 04:31:19'),
(939,'bd','Change Password','পাসওয়ার্ড পরিবর্তন করুন','tlcommerce','2023-02-18 04:31:19','2023-02-18 04:31:19'),
(940,'bd','Generate password reset link','পাসওয়ার্ড রিসেট লিঙ্ক তৈরি করুন','tlcommerce','2023-02-18 04:31:19','2023-02-18 04:31:19'),
(941,'bd','Change Email','ইমেইল পরিবর্তন করুন','tlcommerce','2023-02-18 04:31:19','2023-02-18 04:31:19'),
(942,'bd','Generate email reset link','ইমেল রিসেট লিঙ্ক তৈরি করুন','tlcommerce','2023-02-18 04:31:19','2023-02-18 04:31:19'),
(943,'bd','Categories','ক্যাটাগরি','tlcommerce','2023-02-18 04:31:19','2023-02-18 04:31:19'),
(944,'bd','No address found','কোনো ঠিকানা পাওয়া যায়নি','tlcommerce','2023-02-18 04:31:19','2023-02-18 04:31:19'),
(945,'bd','Manage Address','ঠিকানা পরিচালনা করুন','tlcommerce','2023-02-18 04:31:19','2023-02-18 04:31:19'),
(946,'bd','Address Information','ঠিকানার তথ্য','tlcommerce','2023-02-18 04:31:19','2023-02-18 04:31:19'),
(947,'bd','Country','দেশ','tlcommerce','2023-02-18 04:31:19','2023-02-18 04:31:19'),
(948,'bd','State','অবস্থা','tlcommerce','2023-02-18 04:31:19','2023-02-18 04:31:19'),
(949,'bd','City','শহর','tlcommerce','2023-02-18 04:31:19','2023-02-18 04:31:19'),
(950,'bd','Save address','ঠিকানা সংরক্ষণ করুন','tlcommerce','2023-02-18 04:31:19','2023-02-18 04:31:19'),
(951,'bd','Please choose a pickup point','একটি পিক আপ পয়েন্ট চয়ন করুন','tlcommerce','2023-02-18 04:31:19','2023-02-18 04:31:19'),
(952,'bd','Logout successful','লগআউট সফল হয়েছে৷','tlcommerce','2023-02-18 04:31:19','2023-02-18 04:31:19'),
(953,'bd','Address:','ঠিকানা:','tlcommerce','2023-02-18 04:31:19','2023-02-18 04:31:19'),
(954,'bd','Postal Code:','পোস্ট অফিসের নাম্বার:','tlcommerce','2023-02-18 04:31:19','2023-02-18 04:31:19'),
(955,'bd','Phone:','ফোন:','tlcommerce','2023-02-18 04:31:19','2023-02-18 04:31:19'),
(956,'bd','Delivery not available at this location','এই অবস্থানে ডেলিভারি উপলব্ধ নয়','tlcommerce','2023-02-18 04:31:19','2023-02-18 04:31:19'),
(957,'bd','Status','স্ট্যাটাস','tlcommerce','2023-02-18 04:31:19','2023-02-18 04:31:19'),
(958,'bd','Edit','সম্পাদনা করুন','tlcommerce','2023-02-18 04:31:19','2023-02-18 04:31:19'),
(959,'bd','Actions','কর্ম','tlcommerce','2023-02-18 04:31:19','2023-02-18 04:31:19'),
(960,'bd','ID','আইডি','tlcommerce','2023-02-18 04:31:19','2023-02-18 04:31:19'),
(961,'bd','Order Date','অর্ডারের তারিখ','tlcommerce','2023-02-18 04:31:19','2023-02-18 04:31:19'),
(962,'bd','Amount','পরিমাণ','tlcommerce','2023-02-18 04:31:19','2023-02-18 04:31:19'),
(963,'bd','Num of Products','পণ্যের সংখ্যা','tlcommerce','2023-02-18 04:31:19','2023-02-18 04:31:19'),
(964,'bd','Details','বিস্তারিত','tlcommerce','2023-02-18 04:31:19','2023-02-18 04:31:19'),
(965,'bd','Order Details','আদেশ বিবরণী','tlcommerce','2023-02-18 04:31:19','2023-02-18 04:31:19'),
(966,'bd','Order Placed on','অর্ডার দেওয়া হয়েছে','tlcommerce','2023-02-18 04:31:19','2023-02-18 04:31:19'),
(967,'bd','Order ID','অর্ডার আইডি','tlcommerce','2023-02-18 04:31:19','2023-02-18 04:31:19'),
(968,'bd','Package','প্যাকেজ','tlcommerce','2023-02-18 04:31:19','2023-02-18 04:31:19'),
(969,'bd','Shipping Address','শিপিং ঠিকানা','tlcommerce','2023-02-18 04:31:19','2023-02-22 17:33:18'),
(970,'bd','Billing Address','বিলিং ঠিকানা','tlcommerce','2023-02-18 04:31:19','2023-02-18 04:31:19'),
(971,'bd','Total Summary','মোট সারাংশ','tlcommerce','2023-02-18 04:31:19','2023-02-18 04:31:19'),
(972,'bd','Processing','প্রক্রিয়াকরণ','tlcommerce','2023-02-18 04:31:19','2023-02-18 04:31:19'),
(973,'bd','Shipped','পাঠানো হয়েছে','tlcommerce','2023-02-18 04:31:19','2023-02-18 04:31:19'),
(974,'bd','Payment method','মূল্যপরিশোধ পদ্ধতি','tlcommerce','2023-02-18 04:31:19','2023-02-18 04:31:19'),
(975,'bd','Delivered','বিতরণ করা হয়েছে','tlcommerce','2023-02-18 04:31:19','2023-02-18 04:31:19'),
(976,'bd','Update Address Information','ঠিকানার তথ্য আপডেট করুন','tlcommerce','2023-02-18 04:31:19','2023-02-18 04:31:19'),
(977,'bd','Active','সক্রিয়','tlcommerce','2023-02-18 04:31:19','2023-02-18 04:31:19'),
(978,'bd','Inactive','নিষ্ক্রিয়','tlcommerce','2023-02-18 04:31:19','2023-02-18 04:31:19'),
(979,'bd','Default Shipping Address','ডিফল্ট শিপিং ঠিকানা','tlcommerce','2023-02-18 04:31:19','2023-02-18 04:31:19'),
(980,'bd','Default Billing Address','ডিফল্ট বিলিং ঠিকানা','tlcommerce','2023-02-18 04:31:19','2023-02-18 04:31:19'),
(981,'bd','Save Changes','পরিবর্তনগুলোর সংরক্ষন','tlcommerce','2023-02-18 04:31:19','2023-02-18 04:31:19'),
(982,'bd','Date','তারিখ','tlcommerce','2023-02-18 04:31:19','2023-02-18 04:31:19'),
(983,'bd','Type','টাইপ','tlcommerce','2023-02-18 04:31:19','2023-02-18 04:31:19'),
(984,'en','Language',NULL,'tlcommerce','2023-02-18 04:32:56','2023-02-18 04:32:56'),
(985,'en','Currency',NULL,'tlcommerce','2023-02-18 04:32:56','2023-02-18 04:32:56'),
(986,'en','Notification',NULL,'tlcommerce','2023-02-18 04:34:38','2023-02-18 04:34:38'),
(987,'en','You have no unread notification',NULL,'tlcommerce','2023-02-18 04:34:38','2023-02-18 04:34:38'),
(988,'bd','Language','ভাষা','tlcommerce','2023-02-18 04:39:51','2023-02-18 04:39:51'),
(989,'bd','Currency','মুদ্রা','tlcommerce','2023-02-18 04:39:51','2023-02-18 04:39:51'),
(990,'bd','Notification','বিজ্ঞপ্তি','tlcommerce','2023-02-18 04:39:51','2023-02-18 04:39:51'),
(991,'bd','You have no unread notification','আপনার কোন অপঠিত বিজ্ঞপ্তি নেই','tlcommerce','2023-02-18 04:39:51','2023-02-18 04:39:51'),
(992,'sa','Language','لغة','tlcommerce','2023-02-18 04:40:34','2023-02-18 04:40:34'),
(993,'sa','Currency','عملة','tlcommerce','2023-02-18 04:40:34','2023-02-18 04:40:34'),
(994,'sa','Notification','إشعار','tlcommerce','2023-02-18 04:40:34','2023-02-18 04:40:34'),
(995,'sa','You have no unread notification','ليس لديك إشعار غير مقروء','tlcommerce','2023-02-18 04:40:34','2023-02-18 04:40:34'),
(996,'en','Return',NULL,'tlcommerce','2023-02-18 05:21:16','2023-02-18 05:21:16'),
(997,'en','Write a review',NULL,'tlcommerce','2023-02-18 05:21:16','2023-02-18 05:21:16'),
(998,'en','Review product',NULL,'tlcommerce','2023-02-18 05:21:47','2023-02-18 05:21:47'),
(999,'en','Images',NULL,'tlcommerce','2023-02-18 05:21:47','2023-02-18 05:21:47'),
(1000,'en','Add Image',NULL,'tlcommerce','2023-02-18 05:21:48','2023-02-18 05:21:48'),
(1001,'en','Submit',NULL,'tlcommerce','2023-02-18 05:21:48','2023-02-18 05:21:48'),
(1002,'en','Products Suggestions',NULL,'tlcommerce','2023-02-18 05:23:52','2023-02-18 05:23:52'),
(1003,'en','Product add to cart successfully',NULL,'tlcommerce','2023-02-18 05:24:47','2023-02-18 05:24:47'),
(1004,'en','View Cart',NULL,'tlcommerce','2023-02-18 05:24:47','2023-02-18 05:24:47'),
(1005,'en','Review Order',NULL,'tlcommerce','2023-02-18 05:26:39','2023-02-18 05:26:39'),
(1006,'en','Qty',NULL,'tlcommerce','2023-02-18 05:26:39','2023-02-18 05:26:39'),
(1007,'en','Estimated Delivery Time',NULL,'tlcommerce','2023-02-18 05:26:39','2023-02-18 05:26:39'),
(1008,'en','Shipping Options',NULL,'tlcommerce','2023-02-18 05:26:42','2023-02-18 05:26:42'),
(1009,'en','Estimated Delivery:',NULL,'tlcommerce','2023-02-18 05:26:42','2023-02-18 05:26:42'),
(1010,'en','Order Note',NULL,'tlcommerce','2023-02-18 05:26:54','2023-02-18 05:26:54'),
(1011,'en','Confirm Order',NULL,'tlcommerce','2023-02-18 05:27:10','2023-02-18 05:27:10'),
(1012,'en','Thank You Your Order',NULL,'tlcommerce','2023-02-18 05:27:14','2023-02-18 05:27:14'),
(1013,'en','Order Summery',NULL,'tlcommerce','2023-02-18 05:27:15','2023-02-18 05:27:15'),
(1014,'en','Order Code',NULL,'tlcommerce','2023-02-18 05:27:15','2023-02-18 05:27:15'),
(1015,'en','Mobile',NULL,'tlcommerce','2023-02-18 05:27:15','2023-02-18 05:27:15'),
(1016,'en','Order Status',NULL,'tlcommerce','2023-02-18 05:27:16','2023-02-18 05:27:16'),
(1017,'en','Total Amount',NULL,'tlcommerce','2023-02-18 05:27:16','2023-02-18 05:27:16'),
(1018,'en','Payment Status',NULL,'tlcommerce','2023-02-18 05:27:16','2023-02-18 05:27:16'),
(1019,'en','View Orders',NULL,'tlcommerce','2023-02-18 05:27:18','2023-02-18 05:27:18'),
(1020,'en','Shop More',NULL,'tlcommerce','2023-02-18 05:27:18','2023-02-18 05:27:18'),
(1021,'en','Cancel Order',NULL,'tlcommerce','2023-02-18 05:28:01','2023-02-18 05:28:01'),
(1022,'en','Pending',NULL,'tlcommerce','2023-02-18 05:28:03','2023-02-18 05:28:03'),
(1023,'en','Recharge amount',NULL,'tlcommerce','2023-02-18 05:29:23','2023-02-18 05:29:23'),
(1024,'en','Select a payment option',NULL,'tlcommerce','2023-02-18 05:29:23','2023-02-18 05:29:23'),
(1025,'en','Transaction ID',NULL,'tlcommerce','2023-02-18 05:29:26','2023-02-18 05:29:26'),
(1026,'en','Document',NULL,'tlcommerce','2023-02-18 05:29:27','2023-02-18 05:29:27'),
(1027,'en','Mark all as read',NULL,'tlcommerce','2023-02-18 05:30:37','2023-02-18 05:30:37'),
(1028,'en','Action',NULL,'tlcommerce','2023-02-18 05:31:03','2023-02-18 05:31:03'),
(1029,'bd','Return','রিটার্ন','tlcommerce','2023-02-18 05:35:28','2023-02-19 16:57:14'),
(1030,'bd','Write a review','একটি পর্যালোচনা লিখুন','tlcommerce','2023-02-18 05:35:28','2023-02-18 05:35:28'),
(1031,'bd','Review product','পণ্য পর্যালোচনা করুন','tlcommerce','2023-02-18 05:35:28','2023-02-18 05:35:28'),
(1032,'bd','Images','ছবি','tlcommerce','2023-02-18 05:35:28','2023-02-18 05:35:28'),
(1033,'bd','Add Image','ছবি যোগ কর','tlcommerce','2023-02-18 05:35:28','2023-02-18 05:35:28'),
(1034,'bd','Submit','জমা দিন','tlcommerce','2023-02-18 05:35:28','2023-02-18 05:35:28'),
(1035,'bd','Products Suggestions','পণ্য পরামর্শ','tlcommerce','2023-02-18 05:35:28','2023-02-18 05:35:28'),
(1036,'bd','Product add to cart successfully','পণ্য সফলভাবে কার্ট যোগ হয়েছে','tlcommerce','2023-02-18 05:35:28','2023-02-18 05:36:32'),
(1037,'bd','View Cart','কার্ট দেখুন','tlcommerce','2023-02-18 05:37:30','2023-02-18 05:37:30'),
(1038,'bd','Review Order','পর্যালোচনা আদেশ','tlcommerce','2023-02-18 05:37:30','2023-02-18 05:37:30'),
(1039,'bd','Qty','পরিমাণ','tlcommerce','2023-02-18 05:37:30','2023-02-18 05:37:30'),
(1040,'bd','Estimated Delivery Time','আনুমানিক ডেলিভারি সময়','tlcommerce','2023-02-18 05:37:30','2023-02-18 05:37:30'),
(1041,'bd','Shipping Options','শিপিং বিকল্প','tlcommerce','2023-02-18 05:37:30','2023-02-18 05:37:30'),
(1042,'bd','Estimated Delivery:','অনুমিত ডেলিভারি:','tlcommerce','2023-02-18 05:37:30','2023-02-18 06:03:39'),
(1043,'bd','Order Note','অর্ডার নোট','tlcommerce','2023-02-18 05:37:30','2023-02-18 05:37:30'),
(1044,'bd','Confirm Order','আদেশ নিশ্চিত করুন','tlcommerce','2023-02-18 05:37:30','2023-02-18 05:37:30'),
(1045,'bd','Thank You Your Order','আপনার অর্ডার ধন্যবাদ','tlcommerce','2023-02-18 05:37:30','2023-02-18 05:37:30'),
(1046,'bd','Order Summery','অর্ডার সামারী','tlcommerce','2023-02-18 05:37:30','2023-02-18 05:37:30'),
(1047,'bd','Order Code','অর্ডার কোড','tlcommerce','2023-02-18 05:37:30','2023-02-18 05:37:30'),
(1048,'bd','Mobile','মুঠোফোন','tlcommerce','2023-02-18 05:37:30','2023-02-18 05:37:30'),
(1049,'bd','Order Status','অর্ডারের অবস্থা','tlcommerce','2023-02-18 05:37:30','2023-02-18 05:37:30'),
(1050,'bd','Total Amount','সর্বমোট পরিমাণ','tlcommerce','2023-02-18 05:37:30','2023-02-18 05:37:30'),
(1051,'bd','Payment Status','লেনদেনের অবস্থা','tlcommerce','2023-02-18 05:37:30','2023-02-18 05:37:30'),
(1052,'bd','View Orders','আদেশ দেখুন','tlcommerce','2023-02-18 05:37:30','2023-02-18 05:37:30'),
(1053,'bd','Shop More','আরো কেনাকাটা','tlcommerce','2023-02-18 05:37:30','2023-02-18 05:37:30'),
(1054,'bd','Cancel Order','আদেশ বাতিল','tlcommerce','2023-02-18 05:37:30','2023-02-18 05:37:30'),
(1055,'bd','Pending','বিচারাধীন','tlcommerce','2023-02-18 05:37:30','2023-02-18 05:37:30'),
(1056,'bd','Recharge amount','রিচার্জ পরিমাণ','tlcommerce','2023-02-18 05:37:30','2023-02-18 05:37:30'),
(1057,'bd','Select a payment option','একটি পেমেন্ট বিকল্প নির্বাচন করুন','tlcommerce','2023-02-18 05:38:20','2023-02-18 05:38:20'),
(1058,'bd','Transaction ID','লেনদেন নাম্বার','tlcommerce','2023-02-18 05:38:20','2023-02-18 05:38:20'),
(1059,'bd','Document','দলিল','tlcommerce','2023-02-18 05:38:20','2023-02-18 05:38:20'),
(1060,'bd','Mark all as read','সবগুলো পঠিত বলে সনাক্ত কর','tlcommerce','2023-02-18 05:38:20','2023-02-18 05:38:20'),
(1061,'bd','Action','কর্ম','tlcommerce','2023-02-18 05:38:20','2023-02-18 05:38:20'),
(1062,'sa','Return','يعود','tlcommerce','2023-02-18 05:39:32','2023-02-18 05:39:32'),
(1063,'sa','Write a review','أكتب مراجعة','tlcommerce','2023-02-18 05:39:32','2023-02-18 05:39:32'),
(1064,'sa','Review product','مراجعة المنتج','tlcommerce','2023-02-18 05:39:32','2023-02-18 05:39:32'),
(1065,'sa','Images','الصور','tlcommerce','2023-02-18 05:39:32','2023-02-18 05:39:32'),
(1066,'sa','Add Image','إضافة صورة','tlcommerce','2023-02-18 05:39:32','2023-02-18 05:39:32'),
(1067,'sa','Submit','يُقدِّم','tlcommerce','2023-02-18 05:39:32','2023-02-18 05:39:32'),
(1068,'sa','Products Suggestions','اقتراحات المنتجات','tlcommerce','2023-02-18 05:39:32','2023-02-18 05:39:32'),
(1069,'sa','Product add to cart successfully','أضف المنتج إلى عربة التسوق بنجاح','tlcommerce','2023-02-18 05:39:32','2023-02-18 05:39:32'),
(1070,'sa','View Cart','عرض عربة التسوق','tlcommerce','2023-02-18 05:39:53','2023-02-18 05:39:53'),
(1071,'sa','Review Order','مراجعة الطلب','tlcommerce','2023-02-18 05:39:53','2023-02-18 05:39:53'),
(1072,'sa','Qty','الكمية','tlcommerce','2023-02-18 05:39:53','2023-02-18 05:39:53'),
(1073,'sa','Estimated Delivery Time','يقدر وقت التسليم','tlcommerce','2023-02-18 05:39:53','2023-02-18 05:39:53'),
(1074,'sa','Shipping Options','خيارات الشحن','tlcommerce','2023-02-18 05:39:53','2023-02-18 05:39:53'),
(1075,'sa','Estimated Delivery:','التوصيل المتوقع:','tlcommerce','2023-02-18 05:39:53','2023-02-18 05:39:53'),
(1076,'sa','Order Note','مذكرة النظام','tlcommerce','2023-02-18 05:39:53','2023-02-18 05:39:53'),
(1077,'sa','Confirm Order','أكد الطلب','tlcommerce','2023-02-18 05:39:53','2023-02-18 05:39:53'),
(1078,'sa','Thank You Your Order','شكرا لك طلبك','tlcommerce','2023-02-18 05:39:53','2023-02-18 05:39:53'),
(1079,'sa','Order Summery','ملخص الطلب','tlcommerce','2023-02-18 05:39:53','2023-02-18 05:39:53'),
(1080,'sa','Order Code','رمز الطلب','tlcommerce','2023-02-18 05:39:53','2023-02-18 05:39:53'),
(1081,'sa','Mobile','متحرك','tlcommerce','2023-02-18 05:39:53','2023-02-18 05:39:53'),
(1082,'sa','Order Status','حالة الطلب','tlcommerce','2023-02-18 05:39:53','2023-02-18 05:39:53'),
(1083,'sa','Total Amount','المبلغ الإجمالي','tlcommerce','2023-02-18 05:39:53','2023-02-18 05:39:53'),
(1084,'sa','Payment Status','حالة السداد','tlcommerce','2023-02-18 05:39:53','2023-02-18 05:39:53'),
(1085,'sa','View Orders','عرض الطلبات','tlcommerce','2023-02-18 05:39:53','2023-02-18 05:39:53'),
(1086,'sa','Shop More','تسوق أكثر','tlcommerce','2023-02-18 05:39:53','2023-02-18 05:39:53'),
(1087,'sa','Cancel Order','الغاء الطلب','tlcommerce','2023-02-18 05:39:53','2023-02-18 05:39:53'),
(1088,'sa','Pending','قيد الانتظار','tlcommerce','2023-02-18 05:39:53','2023-02-18 05:39:53'),
(1089,'sa','Recharge amount','مبلغ الشحن','tlcommerce','2023-02-18 05:39:53','2023-02-18 05:39:53'),
(1090,'sa','Select a payment option','حدد خيار الدفع','tlcommerce','2023-02-18 05:40:17','2023-02-18 05:40:17'),
(1091,'sa','Transaction ID','رقم المعاملة','tlcommerce','2023-02-18 05:40:17','2023-02-18 05:40:17'),
(1092,'sa','Document','وثيقة','tlcommerce','2023-02-18 05:40:17','2023-02-18 05:40:17'),
(1093,'sa','Mark all as read','اشر عليها بانها قرات','tlcommerce','2023-02-18 05:40:17','2023-02-18 05:40:17'),
(1094,'sa','Action','فعل','tlcommerce','2023-02-18 05:40:17','2023-02-18 05:40:17'),
(1095,'en','Delivered On',NULL,'tlcommerce','2023-02-18 05:53:37','2023-02-18 05:53:37'),
(1096,'bd','Delivered On','ডেলিভারি হয়েছে','tlcommerce','2023-02-18 05:56:42','2023-02-18 05:56:42'),
(1097,'sa','Delivered On','تم التسليم','tlcommerce','2023-02-18 05:57:18','2023-02-18 05:57:18'),
(1098,'en','Estimated Delivery By','Estimated Delivery By','tlcommerce','2023-02-18 06:01:10','2023-02-18 06:01:10'),
(1099,'sa','Estimated Delivery By','يقدر التسليم من قبل','tlcommerce','2023-02-18 06:01:40','2023-02-18 06:01:40'),
(1100,'bd','Estimated Delivery By','আনুমানিক ডেলিভারি হবে','tlcommerce','2023-02-18 06:03:39','2023-02-18 06:03:39'),
(1101,'en','এখন কেন',NULL,'tlcommerce','2023-02-18 06:43:05','2023-02-18 06:43:05'),
(1102,'en','ব্লগ',NULL,'tlcommerce','2023-02-18 06:43:05','2023-02-18 06:43:05'),
(1103,'en','Buyer Ratings and Reviews','Buyer Ratings and Reviews','tlcommerce','2023-02-19 15:04:22','2023-02-19 15:04:22'),
(1104,'sa','Buyer Ratings and Reviews','تقييمات ومراجعات المشتري','tlcommerce','2023-02-19 15:05:00','2023-02-19 15:05:00'),
(1105,'bd','Buyer Ratings and Reviews','ক্রেতা রেটিং এবং পর্যালোচনা','tlcommerce','2023-02-19 15:05:15','2023-02-19 15:05:15'),
(1106,'en','Verified Purchase','Verified Purchase','tlcommerce','2023-02-19 15:08:49','2023-02-19 15:08:49'),
(1107,'bd','Verified Purchase','যাচাইকৃত ক্রয়','tlcommerce','2023-02-19 15:09:23','2023-02-19 15:09:23'),
(1108,'sa','Verified Purchase','شراء مؤكد','tlcommerce','2023-02-19 15:10:03','2023-02-19 15:10:03'),
(1109,'en','Related Products','Related Products','tlcommerce','2023-02-19 15:22:15','2023-02-19 15:22:15'),
(1110,'sa','Related Products','منتجات ذات صله','tlcommerce','2023-02-19 15:22:41','2023-02-19 15:22:41'),
(1111,'bd','Related Products','সংশ্লিষ্ট পণ্য','tlcommerce','2023-02-19 15:22:58','2023-02-19 15:22:58'),
(1112,'en','Posted On:','Posted On:','tlcommerce','2023-02-19 15:52:41','2023-02-19 15:52:41'),
(1113,'en','Posted By:','Posted By:','tlcommerce','2023-02-19 15:52:50','2023-02-19 15:52:50'),
(1114,'bd','Posted On:','পোস্ট করা হয়েছে:','tlcommerce','2023-02-19 15:53:33','2023-02-19 15:53:33'),
(1115,'bd','Posted By:','পোস্ট করেছে:','tlcommerce','2023-02-19 15:53:33','2023-02-19 15:53:33'),
(1116,'sa','Posted On:','نشر على:','tlcommerce','2023-02-19 15:54:37','2023-02-19 16:38:22'),
(1117,'sa','Posted By:','منشور من طرف:','tlcommerce','2023-02-19 15:54:37','2023-02-19 16:38:22'),
(1118,'en','Search Here','Search Here','tlcommerce','2023-02-19 16:08:00','2023-02-19 16:08:00'),
(1119,'bd','এখন কেন',NULL,'tlcommerce','2023-02-19 16:08:54','2023-02-19 16:08:54'),
(1120,'bd','ব্লগ',NULL,'tlcommerce','2023-02-19 16:08:54','2023-02-19 16:08:54'),
(1121,'bd','Search Here','এখানে অনুসন্ধান করুন','tlcommerce','2023-02-19 16:08:54','2023-02-19 16:08:54'),
(1122,'sa','এখন কেন',NULL,'tlcommerce','2023-02-19 16:09:27','2023-02-19 16:38:32'),
(1123,'sa','ব্লগ',NULL,'tlcommerce','2023-02-19 16:09:27','2023-02-19 16:38:32'),
(1124,'sa','Search Here','ابحث هنا','tlcommerce','2023-02-19 16:09:27','2023-02-19 16:09:27'),
(1125,'en','Blog','Blog','tlcommerce','2023-02-19 16:19:35','2023-02-19 16:19:35'),
(1126,'sa','Blog','مدونة','tlcommerce','2023-02-19 16:19:50','2023-02-19 16:38:22'),
(1127,'bd','Blog','ব্লগ','tlcommerce','2023-02-19 16:20:02','2023-02-19 16:20:02'),
(1128,'en','Per Page','Per Page','tlcommerce','2023-02-19 16:23:40','2023-02-19 16:23:40'),
(1129,'bd','Per Page','প্রতি পৃষ্ঠা','tlcommerce','2023-02-19 16:25:50','2023-02-19 16:25:50'),
(1130,'sa','Per Page','لكل صفحة','tlcommerce','2023-02-19 16:26:08','2023-02-19 16:26:08'),
(1131,'en','Return product','Return product','tlcommerce','2023-02-19 16:35:11','2023-02-19 16:35:11'),
(1132,'en','Refund Reason','Refund Reason','tlcommerce','2023-02-19 16:35:20','2023-02-19 16:35:20'),
(1133,'en','Write comment','Write comment','tlcommerce','2023-02-19 16:35:27','2023-02-19 16:35:27'),
(1134,'en','Product return request submitted successfully','Product return request submitted successfully','tlcommerce','2023-02-19 16:35:35','2023-02-19 16:35:35'),
(1135,'en','Return Date','Return Date','tlcommerce','2023-02-19 16:35:45','2023-02-19 16:35:45'),
(1136,'en','Return Status','Return Status','tlcommerce','2023-02-19 16:35:55','2023-02-19 16:35:55'),
(1137,'en','Refund Details','Refund Details','tlcommerce','2023-02-19 16:36:02','2023-02-19 16:36:02'),
(1138,'en','Returned on','Returned on','tlcommerce','2023-02-19 16:36:10','2023-02-19 16:36:10'),
(1139,'en','Refund ID','Refund ID','tlcommerce','2023-02-19 16:36:17','2023-02-19 16:36:17'),
(1140,'en','Refund Request Information','Refund Request Information','tlcommerce','2023-02-19 16:36:24','2023-02-19 16:36:24'),
(1141,'en','Note','Note','tlcommerce','2023-02-19 16:36:32','2023-02-19 16:36:32'),
(1142,'en','Attachments','Attachments','tlcommerce','2023-02-19 16:36:37','2023-02-19 16:36:37'),
(1143,'en','Reason','Reason','tlcommerce','2023-02-19 16:36:49','2023-02-19 16:36:49'),
(1144,'en','Product Received','Product Received','tlcommerce','2023-02-19 16:36:57','2023-02-19 16:36:57'),
(1145,'en','Return Approved','Return Approved','tlcommerce','2023-02-19 16:37:04','2023-02-19 16:37:04'),
(1146,'en','Refunded','Refunded','tlcommerce','2023-02-19 16:37:10','2023-02-19 16:37:10'),
(1147,'sa','Return product','إرجاع المنتج','tlcommerce','2023-02-19 16:38:22','2023-02-19 16:38:22'),
(1148,'sa','Refund Reason','سبب الاسترداد','tlcommerce','2023-02-19 16:38:22','2023-02-19 16:38:22'),
(1149,'sa','Write comment','اكتب تعليق','tlcommerce','2023-02-19 16:38:22','2023-02-19 16:38:22'),
(1150,'sa','Product return request submitted successfully','تم تقديم طلب إرجاع المنتج بنجاح','tlcommerce','2023-02-19 16:39:05','2023-02-19 16:39:05'),
(1151,'sa','Return Date','تاريخ العودة','tlcommerce','2023-02-19 16:39:05','2023-02-19 16:39:05'),
(1152,'sa','Return Status','حالة العودة','tlcommerce','2023-02-19 16:39:05','2023-02-19 16:39:05'),
(1153,'sa','Refund Details','تفاصيل رد الأموال','tlcommerce','2023-02-19 16:39:05','2023-02-19 16:39:05'),
(1154,'sa','Returned on','عاد في','tlcommerce','2023-02-19 16:39:05','2023-02-19 16:39:05'),
(1155,'sa','Refund ID','معرف الاسترداد','tlcommerce','2023-02-19 16:39:05','2023-02-19 16:39:05'),
(1156,'sa','Refund Request Information','معلومات طلب الاسترداد','tlcommerce','2023-02-19 16:39:05','2023-02-19 16:39:05'),
(1157,'sa','Note','ملحوظة','tlcommerce','2023-02-19 16:39:05','2023-02-19 16:39:05'),
(1158,'sa','Attachments','المرفقات','tlcommerce','2023-02-19 16:39:05','2023-02-19 16:39:05'),
(1159,'sa','Reason','سبب','tlcommerce','2023-02-19 16:39:05','2023-02-19 16:39:05'),
(1160,'sa','Product Received','تم استلام المنتج','tlcommerce','2023-02-19 16:39:05','2023-02-19 16:39:05'),
(1161,'sa','Return Approved','تمت الموافقة على الإرجاع','tlcommerce','2023-02-19 16:39:05','2023-02-19 16:39:05'),
(1162,'sa','Refunded','معاد','tlcommerce','2023-02-19 16:39:05','2023-02-19 16:39:05'),
(1163,'bd','Product return request submitted successfully','পণ্য ফেরত অনুরোধ সফলভাবে জমা দেওয়া হয়েছে','tlcommerce','2023-02-19 16:44:53','2023-02-19 16:44:53'),
(1164,'bd','Return Date','ফেরার তারিখ','tlcommerce','2023-02-19 16:44:53','2023-02-19 16:44:53'),
(1165,'bd','Return Status','রিটার্ন স্ট্যাটাস','tlcommerce','2023-02-19 16:44:53','2023-02-19 16:44:53'),
(1166,'bd','Refund Details','ফেরত বিবরণ','tlcommerce','2023-02-19 16:44:53','2023-02-19 16:44:53'),
(1167,'bd','Returned on','ফিরে এসেছে','tlcommerce','2023-02-19 16:44:53','2023-02-19 16:44:53'),
(1168,'bd','Refund ID','রিফান্ড আইডি','tlcommerce','2023-02-19 16:44:53','2023-02-19 16:44:53'),
(1169,'bd','Refund Request Information','রিফান্ড অনুরোধ তথ্য','tlcommerce','2023-02-19 16:44:53','2023-02-19 16:44:53'),
(1170,'bd','Note','বিঃদ্রঃ','tlcommerce','2023-02-19 16:44:53','2023-02-19 16:44:53'),
(1171,'bd','Attachments','সংযুক্তি','tlcommerce','2023-02-19 16:44:53','2023-02-19 16:44:53'),
(1172,'bd','Reason','কারণ','tlcommerce','2023-02-19 16:44:53','2023-02-19 16:44:53'),
(1173,'bd','Product Received','পণ্য প্রাপ্ত','tlcommerce','2023-02-19 16:44:53','2023-02-19 16:44:53'),
(1174,'bd','Return Approved','রিটার্ন অনুমোদিত','tlcommerce','2023-02-19 16:44:53','2023-02-19 16:45:28'),
(1175,'bd','Refunded','ফেরত দেওয়া হয়েছে','tlcommerce','2023-02-19 16:44:53','2023-02-19 16:44:53'),
(1176,'bd','Return product','পণ্য ফেরত','tlcommerce','2023-02-19 16:46:34','2023-02-19 16:46:34'),
(1177,'bd','Refund Reason','রিফান্ডের কারণ','tlcommerce','2023-02-19 16:46:34','2023-02-19 16:46:34'),
(1178,'bd','Write comment','মন্তব্য লিখুন','tlcommerce','2023-02-19 16:46:34','2023-02-19 16:46:34'),
(1179,'en','Payment','Payment','tlcommerce','2023-02-19 16:58:18','2023-02-19 16:58:18'),
(1180,'bd','Payment','পেমেন্ট','tlcommerce','2023-02-19 16:58:36','2023-02-19 16:58:36'),
(1181,'en','REFUND AMOUNT','REFUND AMOUNT','tlcommerce','2023-02-19 17:01:19','2023-02-19 17:01:19'),
(1182,'bd','REFUND AMOUNT','টাকা ফেরত','tlcommerce','2023-02-19 17:01:51','2023-02-19 17:01:51'),
(1183,'sa','REFUND AMOUNT','المبلغ المسترد','tlcommerce','2023-02-19 17:02:15','2023-02-19 17:02:15'),
(1184,'en','Payment is incomplete','Payment is incomplete','tlcommerce','2023-02-19 17:08:18','2023-02-19 17:08:18'),
(1185,'sa','Payment is incomplete','الدفع غير مكتمل','tlcommerce','2023-02-19 17:08:39','2023-02-19 17:08:39'),
(1186,'bd','Payment is incomplete','পেমেন্ট অসম্পূর্ণ','tlcommerce','2023-02-19 17:09:12','2023-02-19 17:09:12'),
(1187,'en','Wallet','Wallet','tlcommerce','2023-02-19 17:26:15','2023-02-19 17:26:15'),
(1188,'bd','Wallet','ওয়ালেট','tlcommerce','2023-02-19 17:26:29','2023-02-19 17:26:49'),
(1189,'sa','Wallet','محفظتى','tlcommerce','2023-02-19 17:27:20','2023-02-19 17:27:20'),
(1190,'en','Pay Now','Pay Now','tlcommerce','2023-02-19 17:54:56','2023-02-19 17:54:56'),
(1191,'en','Offline Recharge','Offline Recharge','tlcommerce','2023-02-19 17:55:05','2023-02-19 17:55:05'),
(1192,'en','Online Recharge','Online Recharge','tlcommerce','2023-02-19 17:55:10','2023-02-19 17:55:10'),
(1193,'sa','Payment',NULL,'tlcommerce','2023-02-19 17:57:27','2023-02-19 17:57:27'),
(1194,'sa','Pay Now','ادفع الآن','tlcommerce','2023-02-19 17:57:27','2023-02-19 17:57:27'),
(1195,'sa','Offline Recharge','إعادة الشحن دون اتصال بالإنترنت','tlcommerce','2023-02-19 17:57:27','2023-02-19 17:57:27'),
(1196,'sa','Online Recharge','إعادة الشحن عبر الإنترنت','tlcommerce','2023-02-19 17:57:27','2023-02-19 17:57:27'),
(1197,'bd','Pay Now','এখন পরিশোধ করুন','tlcommerce','2023-02-19 17:58:01','2023-02-19 17:58:01'),
(1198,'bd','Offline Recharge','অফলাইন রিচার্জ','tlcommerce','2023-02-19 17:58:01','2023-02-19 17:58:01'),
(1199,'bd','Online Recharge','অনলাইন রিচার্জ','tlcommerce','2023-02-19 17:58:01','2023-02-19 17:58:01'),
(1200,'en','Compare Products','Compare Products','tlcommerce','2023-02-19 18:06:00','2023-02-19 18:06:00'),
(1201,'bd','Compare Products','পন্যের তুলনা করা','tlcommerce','2023-02-19 18:06:27','2023-02-19 18:06:27'),
(1202,'sa','Compare Products','قارن بين المنتجات','tlcommerce','2023-02-19 18:07:09','2023-02-19 18:07:09'),
(1203,'en','Unit','Unit','tlcommerce','2023-02-19 19:55:09','2023-02-19 19:55:09'),
(1204,'sa','Unit','سعر','tlcommerce','2023-02-19 19:55:20','2023-02-19 19:55:20'),
(1205,'bd','Unit','একক','tlcommerce','2023-02-19 19:55:28','2023-02-19 19:55:28'),
(1206,'en','Stock out','Stock out','tlcommerce','2023-02-19 19:56:15','2023-02-19 19:56:15'),
(1207,'en','via','via','tlcommerce','2023-02-19 19:56:22','2023-02-19 19:56:22'),
(1208,'en','Success Order','Success Order','tlcommerce','2023-02-19 19:56:27','2023-02-19 19:56:27'),
(1209,'sa','Stock out','المخزن نفذ','tlcommerce','2023-02-19 19:57:31','2023-02-19 19:57:31'),
(1210,'sa','via','عبر','tlcommerce','2023-02-19 19:57:31','2023-02-19 19:57:31'),
(1211,'sa','Success Order','ترتيب النجاح','tlcommerce','2023-02-19 19:57:31','2023-02-19 19:57:31'),
(1212,'bd','Stock out','মজুত শেষ','tlcommerce','2023-02-19 19:58:39','2023-02-19 19:58:39'),
(1213,'bd','via','মাধ্যমে','tlcommerce','2023-02-19 19:58:39','2023-02-19 19:58:39'),
(1214,'bd','Success Order','সাকসেস অর্ডার','tlcommerce','2023-02-19 19:58:39','2023-02-19 19:58:39'),
(1215,'en','All Blogs','All Blogs','tlcommerce','2023-02-19 20:00:38','2023-02-19 20:00:38'),
(1216,'bd','All Blogs','সমস্ত ব্লগ','tlcommerce','2023-02-19 20:01:29','2023-02-19 20:01:29'),
(1217,'sa','All Blogs','كل المدونات','tlcommerce','2023-02-19 20:02:00','2023-02-19 20:02:00'),
(1218,'en','Blog Search Result','Blog Search Result','tlcommerce','2023-02-19 20:03:01','2023-02-19 20:03:01'),
(1219,'sa','Blog Search Result','نتيجة بحث المدونة','tlcommerce','2023-02-19 20:03:22','2023-02-19 20:03:22'),
(1220,'bd','Blog Search Result','ব্লগ অনুসন্ধান ফলাফল','tlcommerce','2023-02-19 20:04:01','2023-02-19 20:04:01'),
(1221,'en','Email reset','Email reset','tlcommerce','2023-02-19 20:07:46','2023-02-19 20:07:46'),
(1222,'bd','Email reset','ইমেল রিসেট','tlcommerce','2023-02-19 20:08:02','2023-02-19 20:08:02'),
(1223,'sa','Email reset','إعادة تعيين البريد الإلكتروني','tlcommerce','2023-02-19 20:08:21','2023-02-19 20:08:21'),
(1224,'en','Reset password','Reset password','tlcommerce','2023-02-19 20:10:11','2023-02-19 20:10:11'),
(1225,'sa','Reset password','إعادة تعيين كلمة المرور','tlcommerce','2023-02-19 20:10:32','2023-02-19 20:10:32'),
(1226,'bd','Reset password','পাসওয়ার্ড রিসেট করুন','tlcommerce','2023-02-19 20:10:45','2023-02-19 20:10:45'),
(1227,'en','Email reset link is send to email','Email reset link is send to email','tlcommerce','2023-02-19 20:28:29','2023-02-19 20:28:29'),
(1228,'bd','Email reset link is send to email','ইমেল রিসেট লিঙ্ক ইমেল পাঠানো হয়','tlcommerce','2023-02-19 20:29:04','2023-02-19 20:29:04'),
(1229,'sa','Email reset link is send to email','يتم إرسال رابط إعادة تعيين البريد الإلكتروني إلى البريد الإلكتروني','tlcommerce','2023-02-19 20:29:27','2023-02-19 20:29:27'),
(1230,'en','Reset password link is send to email','Reset password link is send to email','tlcommerce','2023-02-19 20:30:43','2023-02-19 20:30:43'),
(1231,'sa','Reset password link is send to email','يتم إرسال رابط إعادة تعيين كلمة المرور إلى البريد الإلكتروني','tlcommerce','2023-02-19 20:31:10','2023-02-19 20:31:10'),
(1232,'bd','Reset password link is send to email','পাসওয়ার্ড রিসেট লিঙ্ক ইমেল পাঠানো হয়','tlcommerce','2023-02-19 20:31:38','2023-02-19 20:31:38'),
(1233,'en','Less','Less','tlcommerce','2023-02-19 20:39:37','2023-02-19 20:39:37'),
(1234,'bd','Less','কম','tlcommerce','2023-02-19 20:40:00','2023-02-19 20:40:00'),
(1235,'sa','Less','أقل','tlcommerce','2023-02-19 20:40:31','2023-02-19 20:40:31'),
(1236,'en','View Less','View Less','tlcommerce','2023-02-19 20:42:10','2023-02-19 20:42:10'),
(1237,'sa','View Less','عرض أقل','tlcommerce','2023-02-19 20:42:31','2023-02-19 20:42:31'),
(1238,'bd','View Less','কম দেখুন','tlcommerce','2023-02-19 20:42:56','2023-02-19 20:42:56'),
(1239,'en','Delivery not available in your location','Delivery not available in your location','tlcommerce','2023-02-19 20:47:05','2023-02-19 20:47:05'),
(1240,'bd','Delivery not available in your location','আপনার অবস্থানে ডেলিভারি উপলব্ধ নয়','tlcommerce','2023-02-19 20:47:27','2023-02-19 20:47:27'),
(1241,'sa','Delivery not available in your location','التسليم غير متوفر في موقعك','tlcommerce','2023-02-19 20:48:08','2023-02-19 20:48:08'),
(1242,'en','hours','hours','tlcommerce','2023-02-19 21:00:42','2023-02-19 21:00:42'),
(1243,'bd','hours','ঘন্টা','tlcommerce','2023-02-19 21:00:50','2023-02-19 21:00:50'),
(1244,'en','minutes','minutes','tlcommerce','2023-02-19 21:01:15','2023-02-19 21:01:15'),
(1245,'bd','minutes','মিনিট','tlcommerce','2023-02-19 21:01:34','2023-02-19 21:01:34'),
(1246,'sa','minutes','دقيقة','tlcommerce','2023-02-19 21:01:47','2023-02-19 21:01:47'),
(1247,'sa','hours','ساعة','tlcommerce','2023-02-19 21:02:29','2023-02-19 21:02:29'),
(1248,'en','seconds','seconds','tlcommerce','2023-02-19 21:02:56','2023-02-19 21:02:56'),
(1249,'sa','seconds','ثانية','tlcommerce','2023-02-19 21:03:03','2023-02-19 21:03:03'),
(1250,'bd','seconds','সেকেন্ড','tlcommerce','2023-02-19 21:03:13','2023-02-19 21:03:13'),
(1251,'en','Sold out','Sold out','tlcommerce','2023-02-19 21:40:49','2023-02-19 21:40:49'),
(1252,'sa','Sold out','نفذ','tlcommerce','2023-02-19 21:41:32','2023-02-19 21:41:32'),
(1253,'bd','Sold out','বিক্রি শেষ','tlcommerce','2023-02-19 21:42:01','2023-02-19 21:42:01'),
(1254,'en','No item found to display','No item found to display','tlcommerce','2023-02-19 21:42:11','2023-02-19 21:42:11'),
(1255,'en','No product found in wishlist','No product found in wishlist','tlcommerce','2023-02-19 21:42:17','2023-02-19 21:42:17'),
(1256,'en','Addresses','Addresses','tlcommerce','2023-02-19 21:42:41','2023-02-19 21:42:41'),
(1257,'bd','No item found to display','প্রদর্শনের জন্য কোনো আইটেম পাওয়া যায়নি','tlcommerce','2023-02-19 21:43:22','2023-02-19 21:43:22'),
(1258,'bd','No product found in wishlist','পছন্দের তালিকায় কোনো পণ্য পাওয়া যায়নি','tlcommerce','2023-02-19 21:43:22','2023-02-19 21:43:22'),
(1259,'sa','No item found to display','لا يوجد منتج في قائمة الرغبات','tlcommerce','2023-02-19 21:44:09','2023-02-19 21:44:09'),
(1260,'sa','No product found in wishlist','لا يوجد منتج في قائمة الرغبات','tlcommerce','2023-02-19 21:44:09','2023-02-19 21:44:09'),
(1261,'sa','Addresses','عناوين','tlcommerce','2023-02-19 21:44:27','2023-02-19 21:44:27'),
(1262,'bd','Addresses','ঠিকানা','tlcommerce','2023-02-19 21:44:55','2023-02-19 21:44:55'),
(1263,'en','Best Home Security Camera TP-Link Tapo C200 Pantilt','Best Home Security Camera TP-Link Tapo C200 Pantilt','tlcommerce','2023-02-19 22:06:25','2023-02-19 22:06:25'),
(1264,'en','Shop Now','Shop Now','tlcommerce','2023-02-19 22:06:25','2023-02-19 22:06:25'),
(1265,'en','TP-Link Tapo C200 Pantilt Home Security Wi-Fi Camera-2 Megapixel - Cc Camera','TP-Link Tapo C200 Pantilt Home Security Wi-Fi Camera-2 Megapixel - Cc Camera','tlcommerce','2023-02-19 22:06:25','2023-02-19 22:06:25'),
(1266,'en','Account Name','Account Name','tlcommerce','2023-02-19 22:19:01','2023-02-19 22:19:01'),
(1267,'en','Account Number','Account Number','tlcommerce','2023-02-19 22:19:09','2023-02-19 22:19:09'),
(1268,'en','Routing Number','Routing Number','tlcommerce','2023-02-19 22:19:16','2023-02-19 22:19:16'),
(1269,'en','Bank Name','Bank Name','tlcommerce','2023-02-19 22:19:23','2023-02-19 22:19:23'),
(1270,'en','Enter transaction id','Enter transaction id','tlcommerce','2023-02-19 22:20:11','2023-02-19 22:20:11'),
(1271,'en','Latest Blogs','Latest Blogs','tlcommerce','2023-02-19 22:20:19','2023-02-19 22:20:19'),
(1272,'bd','Best Home Security Camera TP-Link Tapo C200 Pantilt','সেরা হোম সিকিউরিটি ক্যামেরা TP-Link Tapo C200 Pantilt','tlcommerce','2023-02-19 22:22:40','2023-02-19 22:22:40'),
(1273,'bd','Shop Now','এখনই কিনুন','tlcommerce','2023-02-19 22:22:40','2023-02-19 22:22:40'),
(1274,'bd','TP-Link Tapo C200 Pantilt Home Security Wi-Fi Camera-2 Megapixel - Cc Camera','TP-Link Tapo C200 প্যান টিল্ট হোম সিকিউরিটি ওয়াইফাই ক্যামেরা-2 মেগাপিক্সেল - সিসি ক্যামেরা','tlcommerce','2023-02-19 22:22:40','2023-02-19 22:22:40'),
(1275,'bd','Account Name','হিসাবের নাম','tlcommerce','2023-02-19 22:22:40','2023-02-19 22:22:40'),
(1276,'bd','Account Number','হিসাব নাম্বার','tlcommerce','2023-02-19 22:22:40','2023-02-19 22:22:40'),
(1277,'bd','Routing Number','রাউটিং নম্বর','tlcommerce','2023-02-19 22:22:40','2023-02-19 22:22:40'),
(1278,'bd','Bank Name','ব্যাংকের নাম','tlcommerce','2023-02-19 22:22:40','2023-02-19 22:22:40'),
(1279,'bd','Enter transaction id','লেনদেন আইডি লিখুন','tlcommerce','2023-02-19 22:22:40','2023-02-19 22:22:40'),
(1280,'bd','Latest Blogs','সর্বশেষ ব্লগ','tlcommerce','2023-02-19 22:22:56','2023-02-19 22:22:56'),
(1281,'sa','Best Home Security Camera TP-Link Tapo C200 Pantilt','أفضل كاميرا أمنية للمنزل TP-Link Tapo C200 Pantilt','tlcommerce','2023-02-19 22:24:04','2023-02-19 22:24:04'),
(1282,'sa','Shop Now','تسوق الآن','tlcommerce','2023-02-19 22:24:04','2023-02-19 22:24:04'),
(1283,'sa','TP-Link Tapo C200 Pantilt Home Security Wi-Fi Camera-2 Megapixel - Cc Camera','TP-Link Tapo C200 Pantilt Home Securityكاميرا واي فاي - 2 ميجابيكسل - كاميرا سي سي','tlcommerce','2023-02-19 22:24:04','2023-02-19 22:24:04'),
(1284,'sa','Account Name','إسم الحساب','tlcommerce','2023-02-19 22:24:04','2023-02-19 22:24:04'),
(1285,'sa','Account Number','رقم حساب','tlcommerce','2023-02-19 22:24:04','2023-02-19 22:24:04'),
(1286,'sa','Routing Number','رقم التوصيل','tlcommerce','2023-02-19 22:24:04','2023-02-19 22:24:04'),
(1287,'sa','Bank Name','اسم البنك','tlcommerce','2023-02-19 22:24:04','2023-02-19 22:24:04'),
(1288,'sa','Enter transaction id','أدخل معرف المعاملة','tlcommerce','2023-02-19 22:24:04','2023-02-19 22:24:04'),
(1289,'sa','Latest Blogs','أحدث المدونات','tlcommerce','2023-02-19 22:24:04','2023-02-19 22:24:04'),
(1290,'en','Best of Electronics','Best of Electronics','tlcommerce','2023-02-19 22:25:32','2023-02-19 22:25:32'),
(1291,'en','Home Makeover','Home Makeover','tlcommerce','2023-02-19 22:25:55','2023-02-19 22:25:55'),
(1292,'en','Trending Offer Section','Trending Offer Section','tlcommerce','2023-02-19 22:26:08','2023-02-19 22:26:08'),
(1293,'en','Add Section','Add Section','tlcommerce','2023-02-19 22:26:21','2023-02-19 22:26:21'),
(1294,'en','Clothing Section','Clothing Section','tlcommerce','2023-02-19 22:26:31','2023-02-19 22:26:31'),
(1295,'en','Flash Deal Section','Flash Deal Section','tlcommerce','2023-02-19 22:26:42','2023-02-19 22:26:42'),
(1296,'en','Product Category Section','Product Category Section','tlcommerce','2023-02-19 22:26:52','2023-02-19 22:26:52'),
(1297,'en','Pickup Point','Pickup Point','tlcommerce','2023-02-20 21:31:41','2023-02-20 21:31:41'),
(1298,'en','Discount','Discount','tlcommerce','2023-02-20 21:32:16','2023-02-20 21:32:16'),
(1299,'en','Wallet Balance','Wallet Balance','tlcommerce','2023-02-20 21:36:38','2023-02-20 21:36:38'),
(1300,'en','OR','OR','tlcommerce','2023-02-20 21:37:01','2023-02-20 21:37:01'),
(1301,'en','Pay with wallet','Pay with wallet','tlcommerce','2023-02-20 21:37:11','2023-02-20 21:37:11'),
(1302,'bd','Best of Electronics',NULL,'tlcommerce','2023-02-20 21:40:29','2023-02-20 21:40:29'),
(1303,'bd','Home Makeover',NULL,'tlcommerce','2023-02-20 21:40:29','2023-02-20 21:40:29'),
(1304,'bd','Trending Offer Section',NULL,'tlcommerce','2023-02-20 21:40:29','2023-02-20 21:40:29'),
(1305,'bd','Add Section',NULL,'tlcommerce','2023-02-20 21:40:29','2023-02-20 21:40:29'),
(1306,'bd','Clothing Section',NULL,'tlcommerce','2023-02-20 21:40:29','2023-02-20 21:40:29'),
(1307,'bd','Flash Deal Section',NULL,'tlcommerce','2023-02-20 21:40:29','2023-02-20 21:40:29'),
(1308,'bd','Product Category Section',NULL,'tlcommerce','2023-02-20 21:40:29','2023-02-20 21:40:29'),
(1309,'bd','Discount','ছাড়','tlcommerce','2023-02-20 21:40:29','2023-02-20 21:40:29'),
(1310,'bd','Wallet Balance','ওয়ালেট ব্যালেন্স','tlcommerce','2023-02-20 21:40:29','2023-02-20 21:40:29'),
(1311,'bd','OR','বা','tlcommerce','2023-02-20 21:42:06','2023-02-20 21:42:06'),
(1312,'bd','Pay with wallet','ওয়ালেট দিয়ে পেমেন্ট করুন','tlcommerce','2023-02-20 21:42:06','2023-02-20 21:42:06'),
(1313,'sa','Best of Electronics',NULL,'tlcommerce','2023-02-20 22:10:16','2023-02-20 22:10:16'),
(1314,'sa','Home Makeover',NULL,'tlcommerce','2023-02-20 22:10:16','2023-02-20 22:10:16'),
(1315,'sa','Trending Offer Section',NULL,'tlcommerce','2023-02-20 22:10:16','2023-02-20 22:10:16'),
(1316,'sa','Add Section',NULL,'tlcommerce','2023-02-20 22:10:16','2023-02-20 22:10:16'),
(1317,'sa','Clothing Section',NULL,'tlcommerce','2023-02-20 22:10:16','2023-02-20 22:10:16'),
(1318,'sa','Flash Deal Section',NULL,'tlcommerce','2023-02-20 22:10:16','2023-02-20 22:10:16'),
(1319,'sa','Product Category Section',NULL,'tlcommerce','2023-02-20 22:10:16','2023-02-20 22:10:16'),
(1320,'sa','Discount','تخفيض','tlcommerce','2023-02-20 22:10:16','2023-02-20 22:10:16'),
(1321,'sa','Wallet Balance','رصيد المحفظة','tlcommerce','2023-02-20 22:10:16','2023-02-20 22:10:16'),
(1322,'sa','OR','أو','tlcommerce','2023-02-20 22:10:40','2023-02-20 22:10:40'),
(1323,'sa','Pay with wallet','ادفع بالمحفظة','tlcommerce','2023-02-20 22:10:40','2023-02-20 22:10:40'),
(1324,'en','Quick View','Quick View','tlcommerce','2023-02-22 17:33:56','2023-02-22 17:33:56'),
(1325,'bd','Quick View','কুইক ভিউ','tlcommerce','2023-02-22 17:34:34','2023-02-22 17:34:34'),
(1326,'sa','Quick View','نظرة سريعة','tlcommerce','2023-02-22 17:34:53','2023-02-22 17:34:53'),
(1327,'en','Billing Details','Billing Details','tlcommerce','2023-02-22 17:46:48','2023-02-22 17:46:48'),
(1328,'en','Bill to Different Address','Bill to Different Address','tlcommerce','2023-02-22 17:47:04','2023-02-22 17:47:04'),
(1329,'bd','Billing Details','বিলিং ঠিকানা','tlcommerce','2023-02-22 17:48:08','2023-02-22 17:48:08'),
(1330,'bd','Bill to Different Address','ভিন্ন ঠিকানায় বিল','tlcommerce','2023-02-22 17:48:08','2023-02-22 17:48:08'),
(1331,'sa','Billing Details','تفاصيل الفاتورة','tlcommerce','2023-02-22 17:48:32','2023-02-22 17:48:32'),
(1332,'sa','Bill to Different Address','فاتورة بعنوان مختلف؟','tlcommerce','2023-02-22 17:48:32','2023-02-22 17:48:32'),
(1333,'en','Action failed. Please try again','Action failed. Please try again','tlcommerce','2023-02-26 21:23:34','2023-02-26 21:23:34'),
(1334,'en','Cross the available quantity','Cross the available quantity','tlcommerce','2023-02-26 21:23:51','2023-02-26 21:23:51'),
(1335,'en','Please login','Please login','tlcommerce','2023-02-26 21:24:07','2023-02-26 21:24:07'),
(1336,'sa','Action failed. Please try again','العمل: فشل. حاول مرة اخرى','tlcommerce','2023-02-26 21:24:56','2023-02-26 21:24:56'),
(1337,'sa','Cross the available quantity','عبور الكمية المتاحة','tlcommerce','2023-02-26 21:24:56','2023-02-26 21:24:56'),
(1338,'sa','Please login','الرجاء تسجيل الدخول','tlcommerce','2023-02-26 21:24:56','2023-02-26 21:24:56'),
(1339,'bd','Action failed. Please try again','অ্যাকশন ব্যর্থ হয়েছে। অনুগ্রহপূর্বক আবার চেষ্টা করুন','tlcommerce','2023-02-26 21:25:57','2023-02-26 21:25:57'),
(1340,'bd','Cross the available quantity','কোয়ান্টিটি পরিমাণ অতিক্রম','tlcommerce','2023-02-26 21:25:57','2023-02-26 21:27:27'),
(1341,'bd','Please login','দয়া করে লগইন করুন','tlcommerce','2023-02-26 21:25:57','2023-02-26 21:25:57'),
(1342,'en','There is no item to show','There is no item to show','tlcommerce','2023-03-01 14:46:05','2023-03-01 14:46:05'),
(1343,'bd','There is no item to show','দেখানোর মতো কোনো আইটেম নেই','tlcommerce','2023-03-01 14:47:11','2023-03-01 14:47:11'),
(1344,'sa','There is no item to show','لا يوجد عنصر للعرض','tlcommerce','2023-03-01 14:47:24','2023-03-01 14:47:24'),
(1345,'en','New Password','New Password','tlcommerce','2023-03-01 20:03:31','2023-03-01 20:03:31'),
(1346,'bd','New Password','নতুন পাসওয়ার্ড','tlcommerce','2023-03-01 20:03:57','2023-03-01 20:03:57'),
(1347,'sa','New Password','كلمة المرور الجديدة','tlcommerce','2023-03-01 20:04:15','2023-03-01 20:04:15'),
(1348,'en','This link has expired','This link has expired','tlcommerce','2023-03-01 20:08:02','2023-03-01 20:08:02'),
(1349,'en','Regenerate Link','Regenerate Link','tlcommerce','2023-03-01 20:08:10','2023-03-01 20:08:10'),
(1350,'sa','This link has expired','انتهت صلاحية هذا الرابط','tlcommerce','2023-03-01 20:08:42','2023-03-01 20:08:42'),
(1351,'sa','Regenerate Link','إعادة إنشاء الارتباط','tlcommerce','2023-03-01 20:08:42','2023-03-01 20:08:42'),
(1352,'bd','This link has expired','এই লিঙ্ক মেয়াদ শেষ হয়েছে','tlcommerce','2023-03-01 20:09:14','2023-03-01 20:09:14'),
(1353,'bd','Regenerate Link','লিঙ্ক পুনরায় তৈরি করুন','tlcommerce','2023-03-01 20:09:14','2023-03-01 20:09:14'),
(1354,'en','New Email','New Email','tlcommerce','2023-03-01 20:11:41','2023-03-01 20:11:41'),
(1355,'en','Update Email','Update Email','tlcommerce','2023-03-01 20:11:54','2023-03-01 20:11:54'),
(1356,'en','verify your email, please wait','verify your email, please wait','tlcommerce','2023-03-01 20:33:11','2023-03-01 20:33:11'),
(1357,'bd','New Email','নতুন ইমেইল','tlcommerce','2023-03-01 20:33:57','2023-03-01 20:33:57'),
(1358,'bd','Update Email','নতুন ইমেইল','tlcommerce','2023-03-01 20:33:57','2023-03-01 20:33:57'),
(1359,'bd','verify your email, please wait','আপনার ইমেল যাচাই করা হচ্ছে, অনুগ্রহ করে অপেক্ষা করুন','tlcommerce','2023-03-01 20:33:57','2023-03-01 20:33:57'),
(1360,'sa','New Email','بريد إلكتروني جديد','tlcommerce','2023-03-01 20:34:44','2023-03-01 20:34:44'),
(1361,'sa','Update Email','تحديث البريد الإلكتروني','tlcommerce','2023-03-01 20:34:44','2023-03-01 20:34:44'),
(1362,'sa','verify your email, please wait','التحقق من بريدك الإلكتروني ، يرجى الانتظار','tlcommerce','2023-03-01 20:34:44','2023-03-01 20:34:44'),
(1363,'en','Product remove from wishlist successfully','Product remove from wishlist successfully','tlcommerce','2023-03-02 16:44:04','2023-03-02 16:44:04'),
(1364,'bd','Product remove from wishlist successfully','পণ্য সফলভাবে ইচ্ছা তালিকা থেকে সরানো হয়েছে','tlcommerce','2023-03-02 16:44:52','2023-03-02 16:45:20'),
(1365,'sa','Product remove from wishlist successfully','تمت إزالة المنتج بنجاح من قائمة الرغبات','tlcommerce','2023-03-02 16:45:37','2023-03-02 16:45:37'),
(1366,'en','Email Verification','Email Verification','tlcommerce','2023-03-02 19:17:25','2023-03-02 19:17:25'),
(1367,'bd','Email Verification','ইমেইলের সত্যতা যাচাই','tlcommerce','2023-03-02 19:18:43','2023-03-02 19:18:43'),
(1368,'sa','Email Verification','تأكيد بواسطة البريد الالكتروني','tlcommerce','2023-03-02 19:19:04','2023-03-02 19:19:04'),
(1369,'en','Package','Package','tlcommerce','2023-03-02 20:29:27','2023-03-02 20:29:27'),
(1370,'en','Category Slider','Category Slider','tlcommerce','2023-03-11 19:40:07','2023-03-11 19:40:07'),
(1371,'sa','Thank You','شكرًا لك','tlcommerce','2023-02-17 21:39:53','2023-02-17 21:39:53'),
(1372,'sa','Pending','قيد الانتظار','tlcommerce','2023-02-17 21:39:53','2023-02-17 21:39:53'),
(1373,'sa','Paid','مدفوع','tlcommerce','2023-02-17 21:39:53','2023-02-17 21:39:53'),
(1374,'sa','Unpaid','غير مدفوع','tlcommerce','2023-02-17 21:39:53','2023-02-17 21:39:53'),
(1375,'sa','Delivery Time','وقت التوصيل','tlcommerce','2023-02-17 20:28:44','2023-02-17 20:28:44'),
(1376,'sa','Now','الآن','tlcommerce','2023-02-17 20:28:44','2023-02-17 20:28:44'),
(1377,'sa','Choose Delivery Time','اختار وقت التوصيل','tlcommerce','2023-02-17 20:28:44','2023-02-17 20:28:44'),
(1378,'sa','Deliver To','التوصيل إلى','tlcommerce','2023-02-17 20:28:44','2023-02-17 20:28:44'),
(1379,'sa','Please select your preferred delivery time','يرجى اختيار وقت التوصيل المناسب لك','tlcommerce','2023-02-17 20:28:44','2023-02-17 20:28:44'),
(1380,'sa','Change','تغيير','tlcommerce','2023-02-17 20:28:44','2023-02-17 20:28:44'),
(1381,'sa','Confirm','تأكيد','tlcommerce','2023-02-17 20:28:44','2023-02-17 20:28:44'),
(1382,'sa','No delivery times available','ما في أوقات توصيل متاحة','tlcommerce','2023-02-17 20:28:44','2023-02-17 20:28:44'),
(1383,'sa','Loading','جاري التحميل','tlcommerce','2023-02-17 20:28:44','2023-02-17 20:28:44'),
(1384,'sa','Schedule Delivery','جدولة التوصيل','tlcommerce','2023-02-17 20:28:44','2023-02-17 20:28:44'),
(1385,'sa','Add','إضافة','tlcommerce','2023-02-17 20:28:44','2023-02-17 20:28:44'),
(1386,'sa','Page Not Exist','الصفحة غير موجودة','tlcommerce','2023-02-17 20:28:44','2023-02-17 20:28:44'),
(1387,'sa','Page Not Found','الصفحة غير موجودة','tlcommerce','2023-02-17 20:28:44','2023-02-17 20:28:44'),
(1388,'sa','Back to Home','العودة إلى الرئيسية','tlcommerce','2023-02-17 20:28:44','2023-02-17 20:28:44');
-- --------------------------------------------------------

--
-- Table structure for table `tl_translations`
--

CREATE TABLE `tl_translations` (
  `id` int(11) NOT NULL,
  `lang` text DEFAULT NULL,
  `lang_key` text DEFAULT NULL,
  `lang_value` longtext DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `tl_translations`
--

INSERT INTO `tl_translations` (`id`, `lang`, `lang_key`, `lang_value`, `created_at`, `updated_at`) VALUES
(1, 'en', 'forgot________________________________password', 'Forgot                                Password?', '2023-02-04 09:39:28', '2023-02-04 09:39:28'),
(2, 'en', 'log_in', 'Log In', '2023-02-04 09:39:33', '2023-02-04 09:39:33'),
(3, 'en', 'invalid_email_address', 'Invalid Email Address', '2023-02-04 09:39:47', '2023-02-04 09:39:47'),
(4, 'en', 'invalid_password', 'Invalid Password', '2023-02-04 09:39:47', '2023-02-04 09:39:47'),
(5, 'en', 'login_successful', 'Login successful', '2023-02-04 09:39:47', '2023-02-04 09:39:47'),
(6, 'en', 'dashboard', 'Dashboard', '2023-02-04 09:39:49', '2023-02-04 09:39:49'),
(7, 'en', 'customers', 'Customers', '2023-02-04 09:39:50', '2023-02-04 09:39:50'),
(8, 'en', 'orders', 'Orders', '2023-02-04 09:39:50', '2023-02-04 09:39:50'),
(9, 'en', 'products', 'Products', '2023-02-04 09:39:50', '2023-02-04 09:39:50'),
(10, 'en', 'total_sales', 'Total Sales', '2023-02-04 09:39:50', '2023-02-04 09:39:50'),
(11, 'en', 'sale_reports', 'Sale Reports', '2023-02-04 09:39:50', '2023-02-04 09:39:50'),
(12, 'en', 'monthly', 'Monthly', '2023-02-04 09:39:50', '2023-02-04 09:39:50'),
(13, 'en', 'daily', 'Daily', '2023-02-04 09:39:50', '2023-02-04 09:39:50'),
(14, 'en', 'pending', 'Pending', '2023-02-04 09:39:50', '2023-02-04 09:39:50'),
(15, 'en', 'approved', 'Approved', '2023-02-04 09:39:50', '2023-02-04 09:39:50'),
(16, 'en', 'ready_to_ship', 'Ready to Ship', '2023-02-04 09:39:50', '2023-02-04 09:39:50'),
(17, 'en', 'shipped', 'Shipped', '2023-02-04 09:39:50', '2023-02-04 09:39:50'),
(18, 'en', 'delivered', 'Delivered', '2023-02-04 09:39:50', '2023-02-04 09:39:50'),
(19, 'en', 'cancelled', 'Cancelled', '2023-02-04 09:39:50', '2023-02-04 09:39:50'),
(20, 'en', 'recent_orders', 'Recent Orders', '2023-02-04 09:39:50', '2023-02-04 09:39:50'),
(21, 'en', 'order_id', 'Order ID', '2023-02-04 09:39:50', '2023-02-04 09:39:50'),
(22, 'en', 'date', 'Date', '2023-02-04 09:39:50', '2023-02-04 09:39:50'),
(23, 'en', 'customer', 'Customer', '2023-02-04 09:39:50', '2023-02-04 09:39:50'),
(24, 'en', 'total_amount', 'Total Amount', '2023-02-04 09:39:50', '2023-02-04 09:39:50'),
(25, 'en', 'action', 'Action', '2023-02-04 09:39:50', '2023-02-04 09:39:50'),
(26, 'en', 'nothing_found', 'Nothing found', '2023-02-04 09:39:50', '2023-02-04 09:39:50'),
(27, 'en', 'top_customers', 'Top Customers', '2023-02-04 09:39:50', '2023-02-04 09:39:50'),
(28, 'en', 'top_products', 'Top Products', '2023-02-04 09:39:50', '2023-02-04 09:39:50'),
(29, 'en', 'top_categories', 'Top Categories', '2023-02-04 09:39:50', '2023-02-04 09:39:50'),
(30, 'en', 'top_brands', 'Top Brands', '2023-02-04 09:39:50', '2023-02-04 09:39:50'),
(31, 'en', 'my_profile', 'My Profile', '2023-02-04 09:39:50', '2023-02-04 09:39:50'),
(32, 'en', 'log_out', 'Log Out', '2023-02-04 09:39:50', '2023-02-04 09:39:50'),
(33, 'en', 'clear_cache', 'Clear Cache', '2023-02-04 09:39:50', '2023-02-04 09:39:50'),
(34, 'en', 'notifications', 'Notifications', '2023-02-04 09:39:50', '2023-02-04 09:39:50'),
(35, 'en', 'clear_all', 'Clear all', '2023-02-04 09:39:50', '2023-02-04 09:39:50'),
(36, 'en', 'media', 'Media', '2023-02-04 09:39:50', '2023-02-04 09:39:50'),
(37, 'en', 'blog', 'Blog', '2023-02-04 09:39:50', '2023-02-04 09:39:50'),
(38, 'en', 'all_blogs', 'All Blogs', '2023-02-04 09:39:50', '2023-02-04 09:39:50'),
(39, 'en', 'add_new_blog', 'Add New Blog', '2023-02-04 09:39:50', '2023-02-04 09:39:50'),
(40, 'en', 'categories', 'Categories', '2023-02-04 09:39:50', '2023-02-04 09:39:50'),
(41, 'en', 'tags', 'Tags', '2023-02-04 09:39:50', '2023-02-04 09:39:50'),
(42, 'en', 'comments', 'Comments', '2023-02-04 09:39:50', '2023-02-04 09:39:50'),
(43, 'en', 'settings', 'Settings', '2023-02-04 09:39:50', '2023-02-04 09:39:50'),
(44, 'en', 'comment_settings', 'Comment Settings', '2023-02-04 09:39:50', '2023-02-04 09:39:50'),
(45, 'en', 'pages', 'Pages', '2023-02-04 09:39:50', '2023-02-04 09:39:50'),
(46, 'en', 'all_pages', 'All Pages', '2023-02-04 09:39:50', '2023-02-04 09:39:50'),
(47, 'en', 'add_new_page', 'Add New Page', '2023-02-04 09:39:50', '2023-02-04 09:39:50'),
(48, 'en', 'add_new_product', 'Add New Product', '2023-02-04 09:39:50', '2023-02-04 09:39:50'),
(49, 'en', 'all_products', 'All Products', '2023-02-04 09:39:50', '2023-02-04 09:39:50'),
(50, 'en', 'colors', 'Colors', '2023-02-04 09:39:50', '2023-02-04 09:39:50'),
(51, 'en', 'brands', 'Brands', '2023-02-04 09:39:50', '2023-02-04 09:39:50'),
(52, 'en', 'attributes', 'Attributes', '2023-02-04 09:39:50', '2023-02-04 09:39:50'),
(53, 'en', 'units', 'Units', '2023-02-04 09:39:50', '2023-02-04 09:39:50'),
(54, 'en', 'product_reviews', 'Product Reviews', '2023-02-04 09:39:50', '2023-02-04 09:39:50'),
(55, 'en', 'product_collections', 'Product collections', '2023-02-04 09:39:50', '2023-02-04 09:39:50'),
(56, 'en', 'product_tags', 'Product Tags', '2023-02-04 09:39:50', '2023-02-04 09:39:50'),
(57, 'en', 'product_conditions', 'Product conditions', '2023-02-04 09:39:50', '2023-02-04 09:39:50'),
(58, 'en', 'inhouse_orders', 'Inhouse Orders', '2023-02-04 09:39:50', '2023-02-04 09:39:50'),
(59, 'en', 'pickup_point_order', 'Pickup Point Order', '2023-02-04 09:39:50', '2023-02-04 09:39:50'),
(60, 'en', 'addon', 'Addon', '2023-02-04 09:39:50', '2023-02-04 09:39:50'),
(61, 'en', 'shippings', 'Shippings', '2023-02-04 09:39:50', '2023-02-04 09:39:50'),
(62, 'en', 'shipping__delivery', 'Shipping & Delivery', '2023-02-04 09:39:50', '2023-02-04 09:39:50'),
(63, 'en', 'pickup_points', 'Pickup Points', '2023-02-04 09:39:50', '2023-02-04 09:39:50'),
(64, 'en', 'carriers', 'Carriers', '2023-02-04 09:39:50', '2023-02-04 09:39:50'),
(65, 'en', 'adoon', 'Adoon', '2023-02-04 09:39:50', '2023-02-04 09:39:50'),
(66, 'en', 'locations', 'Locations', '2023-02-04 09:39:50', '2023-02-04 09:39:50'),
(67, 'en', 'countries', 'Countries', '2023-02-04 09:39:50', '2023-02-04 09:39:50'),
(68, 'en', 'states', 'States', '2023-02-04 09:39:50', '2023-02-04 09:39:50'),
(69, 'en', 'cities', 'Cities', '2023-02-04 09:39:50', '2023-02-04 09:39:50'),
(70, 'en', 'payments', 'Payments', '2023-02-04 09:39:50', '2023-02-04 09:39:50'),
(71, 'en', 'payment_methods', 'Payment Methods', '2023-02-04 09:39:50', '2023-02-04 09:39:50'),
(72, 'en', 'transaction_history', 'Transaction history', '2023-02-04 09:39:50', '2023-02-04 09:39:50'),
(73, 'en', 'marketing', 'Marketing', '2023-02-04 09:39:50', '2023-02-04 09:39:50'),
(74, 'en', 'flash_deals', 'Flash Deals', '2023-02-04 09:39:50', '2023-02-04 09:39:50'),
(75, 'en', 'coupons', 'Coupons', '2023-02-04 09:39:50', '2023-02-04 09:39:50'),
(76, 'en', 'custom_notification', 'Custom Notification', '2023-02-04 09:39:50', '2023-02-04 09:39:50'),
(77, 'en', 'reports', 'Reports', '2023-02-04 09:39:50', '2023-02-04 09:39:50'),
(78, 'en', 'product_reports', 'Product Reports', '2023-02-04 09:39:50', '2023-02-04 09:39:50'),
(79, 'en', 'keyword_search_reports', 'Keyword Search Reports', '2023-02-04 09:39:50', '2023-02-04 09:39:50'),
(80, 'en', 'wishlist_reports', 'Wishlist Reports', '2023-02-04 09:39:50', '2023-02-04 09:39:50'),
(81, 'en', 'ecommerce_settings', 'Ecommerce Settings', '2023-02-04 09:39:50', '2023-02-04 09:39:50'),
(82, 'en', 'taxes', 'Taxes', '2023-02-04 09:39:50', '2023-02-04 09:39:50'),
(83, 'en', 'currencies', 'Currencies', '2023-02-04 09:39:50', '2023-02-04 09:39:50'),
(84, 'en', 'product_share_options', 'Product Share Options', '2023-02-04 09:39:50', '2023-02-04 09:39:50'),
(85, 'en', 'refunds', 'Refunds', '2023-02-04 09:39:50', '2023-02-04 09:39:50'),
(86, 'en', 'refund_requests', 'Refund Requests', '2023-02-04 09:39:50', '2023-02-04 09:39:50'),
(87, 'en', 'refund_reasons', 'Refund Reasons', '2023-02-04 09:39:50', '2023-02-04 09:39:50'),
(88, 'en', 'appearances', 'Appearances', '2023-02-04 09:39:50', '2023-02-04 09:39:50'),
(89, 'en', 'themes', 'Themes', '2023-02-04 09:39:50', '2023-02-04 09:39:50'),
(90, 'en', 'menus', 'Menus', '2023-02-04 09:39:50', '2023-02-04 09:39:50'),
(91, 'en', 'widgets', 'Widgets', '2023-02-04 09:39:50', '2023-02-04 09:39:50'),
(92, 'en', 'theme_options', 'Theme Options', '2023-02-04 09:39:50', '2023-02-04 09:39:50'),
(93, 'en', 'general_settings', 'General settings', '2023-02-04 09:39:50', '2023-02-04 09:39:50'),
(94, 'en', 'home_page_builder', 'Home Page Builder', '2023-02-04 09:39:50', '2023-02-04 09:39:50'),
(95, 'en', 'slider_settings', 'Slider Settings', '2023-02-04 09:39:50', '2023-02-04 09:39:50'),
(96, 'en', 'plugins', 'Plugins', '2023-02-04 09:39:50', '2023-02-04 09:39:50'),
(97, 'en', 'email_settings', 'Email settings', '2023-02-04 09:39:50', '2023-02-04 09:39:50'),
(98, 'en', 'email_templates', 'Email Templates', '2023-02-04 09:39:50', '2023-02-04 09:39:50'),
(99, 'en', 'languages', 'Languages', '2023-02-04 09:39:50', '2023-02-04 09:39:50'),
(100, 'en', 'media_settings', 'Media settings', '2023-02-04 09:39:50', '2023-02-04 09:39:50'),
(101, 'en', 'seo_settings', 'SEO settings', '2023-02-04 09:39:50', '2023-02-04 09:39:50'),
(102, 'en', 'theme_translatios', 'Theme Translatios', '2023-02-04 09:39:50', '2023-02-04 09:39:50'),
(103, 'en', 'users', 'Users', '2023-02-04 09:39:50', '2023-02-04 09:39:50'),
(104, 'en', 'roles', 'Roles', '2023-02-04 09:39:50', '2023-02-04 09:39:50'),
(105, 'en', 'permissions', 'Permissions', '2023-02-04 09:39:50', '2023-02-04 09:39:50'),
(106, 'en', 'activity_logs', 'Activity Logs', '2023-02-04 09:39:50', '2023-02-04 09:39:50'),
(107, 'en', 'login_activity', 'Login activity', '2023-02-04 09:39:50', '2023-02-04 09:39:50'),
(108, 'en', 'update_user', 'Update User', '2023-02-04 09:40:11', '2023-02-04 09:40:11'),
(109, 'en', 'update_profile', 'Update Profile', '2023-02-04 09:40:11', '2023-02-04 09:40:11'),
(110, 'en', 'profile_picture', 'Profile Picture', '2023-02-04 09:40:11', '2023-02-04 09:40:11'),
(111, 'en', 'choose_image', 'Choose image', '2023-02-04 09:40:11', '2023-02-04 09:40:11'),
(112, 'en', 'name', 'Name', '2023-02-04 09:40:11', '2023-02-04 09:40:11'),
(113, 'en', 'give_your_name', 'Give your name', '2023-02-04 09:40:11', '2023-02-04 09:40:11'),
(114, 'en', 'email', 'Email', '2023-02-04 09:40:11', '2023-02-04 09:40:11'),
(115, 'en', 'give_your_email_address', 'Give your email address', '2023-02-04 09:40:11', '2023-02-04 09:40:11'),
(116, 'en', 'old_password', 'Old Password', '2023-02-04 09:40:11', '2023-02-04 09:40:11'),
(117, 'en', 'password', 'Password', '2023-02-04 09:40:11', '2023-02-04 09:40:11'),
(118, 'en', 'give_your_password', 'Give your password', '2023-02-04 09:40:11', '2023-02-04 09:40:11'),
(119, 'en', 'confirm_password', 'Confirm Password', '2023-02-04 09:40:11', '2023-02-04 09:40:11'),
(120, 'en', 'confirm_your_password', 'Confirm your password', '2023-02-04 09:40:11', '2023-02-04 09:40:11'),
(121, 'en', 'update', 'Update', '2023-02-04 09:40:11', '2023-02-04 09:40:11'),
(122, 'en', 'media_library', 'Media Library', '2023-02-04 09:40:11', '2023-02-04 09:40:11'),
(123, 'en', 'upload_files', 'Upload Files', '2023-02-04 09:40:11', '2023-02-04 09:40:11'),
(124, 'en', 'click_or_drop_files_here_to_upload', 'Click or Drop files here to upload', '2023-02-04 09:40:11', '2023-02-04 09:40:11'),
(125, 'en', 'filter_media', 'Filter media', '2023-02-04 09:40:11', '2023-02-04 09:40:11'),
(126, 'en', 'all_file_type', 'All File Type', '2023-02-04 09:40:11', '2023-02-04 09:40:11'),
(127, 'en', 'all_dates', 'All dates', '2023-02-04 09:40:11', '2023-02-04 09:40:11'),
(128, 'en', 'insert', 'Insert', '2023-02-04 09:40:11', '2023-02-04 09:40:11'),
(129, 'en', 'attachment_details', 'ATTACHMENT DETAILS', '2023-02-04 09:40:11', '2023-02-04 09:40:11'),
(130, 'en', 'alt________________________text_', 'Alt                        Text :', '2023-02-04 09:40:11', '2023-02-04 09:40:11'),
(131, 'en', 'title_', 'Title :', '2023-02-04 09:40:11', '2023-02-04 09:40:11'),
(132, 'en', 'caption_', 'Caption :', '2023-02-04 09:40:11', '2023-02-04 09:40:11'),
(133, 'en', 'description_', 'Description :', '2023-02-04 09:40:11', '2023-02-04 09:40:11'),
(134, 'en', 'showing', 'Showing', '2023-02-04 09:40:15', '2023-02-04 09:40:15'),
(135, 'en', 'media_items', 'media items', '2023-02-04 09:40:15', '2023-02-04 09:40:15'),
(136, 'en', 'delete_permanently', 'Delete Permanently', '2023-02-04 09:40:16', '2023-02-04 09:40:16'),
(137, 'en', 'file_name', 'File Name:', '2023-02-04 09:40:16', '2023-02-04 09:40:16'),
(138, 'en', 'file_url', 'File URL:', '2023-02-04 09:40:16', '2023-02-04 09:40:16'),
(139, 'en', 'file_type', 'File Type:', '2023-02-04 09:40:16', '2023-02-04 09:40:16'),
(140, 'en', 'file_size', 'File Size:', '2023-02-04 09:40:16', '2023-02-04 09:40:16'),
(141, 'en', 'uploaded_by', 'Uploaded By:', '2023-02-04 09:40:16', '2023-02-04 09:40:16'),
(142, 'en', 'created_at', 'Created At:', '2023-02-04 09:40:16', '2023-02-04 09:40:16'),
(143, 'en', 'updated_at', 'Updated At:', '2023-02-04 09:40:16', '2023-02-04 09:40:16'),
(144, 'en', 'download', 'Download', '2023-02-04 09:40:16', '2023-02-04 09:40:16'),
(145, 'en', 'copy_url_to_clipboard', 'Copy URL to clipboard', '2023-02-04 09:40:16', '2023-02-04 09:40:16'),
(146, 'en', 'alt____________________________________________________________________________________text', 'Alt                                                                                    Text', '2023-02-04 09:40:16', '2023-02-04 09:40:16'),
(147, 'en', 'title', 'Title', '2023-02-04 09:40:16', '2023-02-04 09:40:16'),
(148, 'en', 'caption', 'Caption', '2023-02-04 09:40:16', '2023-02-04 09:40:16'),
(149, 'en', 'description', 'Description', '2023-02-04 09:40:16', '2023-02-04 09:40:16'),
(150, 'en', 'save', 'Save', '2023-02-04 09:40:16', '2023-02-04 09:40:16'),
(151, 'en', 'login', 'Login', '2023-02-04 09:44:51', '2023-02-04 09:44:51'),
(152, 'en', 'login_to_dashboard', 'Login To Dashboard', '2023-02-04 09:44:51', '2023-02-04 09:44:51'),
(153, 'en', 'email_address', 'Email Address', '2023-02-04 09:44:51', '2023-02-04 09:44:51'),
(154, 'en', '', '********', '2023-02-04 09:44:51', '2023-02-04 09:44:51'),
(155, 'en', 'remember_me', 'Remember Me', '2023-02-04 09:44:51', '2023-02-04 09:44:51'),
(156, 'en', 'sliders', 'Sliders', '2023-02-04 09:47:29', '2023-02-04 09:47:29'),
(157, 'en', 'add_new_slider', 'Add New Slider', '2023-02-04 09:47:29', '2023-02-04 09:47:29'),
(158, 'en', 'desktop', 'Desktop', '2023-02-04 09:47:29', '2023-02-04 09:47:29'),
(159, 'en', 'mobile', 'Mobile', '2023-02-04 09:47:29', '2023-02-04 09:47:29'),
(160, 'en', 'status', 'Status', '2023-02-04 09:47:29', '2023-02-04 09:47:29'),
(161, 'en', 'actions', 'Actions', '2023-02-04 09:47:29', '2023-02-04 09:47:29'),
(162, 'en', 'delete_confirmation', 'Delete Confirmation', '2023-02-04 09:47:29', '2023-02-04 09:47:29'),
(163, 'en', 'are_you_sure_to_delete_this', 'Are you sure to delete this', '2023-02-04 09:47:29', '2023-02-04 09:47:29'),
(164, 'en', 'cancel', 'cancel', '2023-02-04 09:47:29', '2023-02-04 09:47:29'),
(165, 'en', 'delete', 'Delete', '2023-02-04 09:47:29', '2023-02-04 09:47:29'),
(166, 'en', 'bulk_action', 'Bulk Action', '2023-02-04 09:47:29', '2023-02-04 09:47:29'),
(167, 'en', 'delete_selection', 'Delete selection', '2023-02-04 09:47:29', '2023-02-04 09:47:29'),
(168, 'en', 'apply', 'Apply', '2023-02-04 09:47:29', '2023-02-04 09:47:29'),
(169, 'en', 'no_item_selected', 'No Item Selected', '2023-02-04 09:47:29', '2023-02-04 09:47:29'),
(170, 'en', 'no_action_selected', 'No Action Selected', '2023-02-04 09:47:29', '2023-02-04 09:47:29'),
(171, 'en', 'new_slider', 'New Slider', '2023-02-04 09:49:46', '2023-02-04 09:49:46'),
(172, 'en', 'type_title', 'Type title', '2023-02-04 09:49:46', '2023-02-04 09:49:46'),
(173, 'en', 'desktop_image', 'Desktop Image', '2023-02-04 09:49:46', '2023-02-04 09:49:46'),
(174, 'en', 'choose_file', 'Choose File', '2023-02-04 09:49:46', '2023-02-04 09:49:46'),
(175, 'en', 'mobile_image', 'Mobile Image', '2023-02-04 09:49:46', '2023-02-04 09:49:46'),
(176, 'en', 'login', 'Login', '2023-02-04 11:58:06', '2023-02-04 11:58:06'),
(177, 'en', 'system_name', 'System Name', '2023-02-04 13:25:00', '2023-02-04 13:25:00'),
(178, 'en', 'logo', 'Logo', '2023-02-04 13:25:06', '2023-02-04 13:25:06'),
(179, 'en', 'logo_mobile', 'Logo (Mobile)', '2023-02-04 13:25:06', '2023-02-04 13:25:06'),
(180, 'en', 'dark_logo', 'Dark Logo', '2023-02-04 13:25:06', '2023-02-04 13:25:06'),
(181, 'en', 'dark_logo_mobile', 'Dark Logo (Mobile)', '2023-02-04 13:25:06', '2023-02-04 13:25:06'),
(182, 'en', 'sticky_logo', 'Sticky Logo', '2023-02-04 13:25:06', '2023-02-04 13:25:06'),
(183, 'en', 'sticky_logo_mobile', 'Sticky Logo (Mobile)', '2023-02-04 13:25:06', '2023-02-04 13:25:06'),
(184, 'en', 'dark_sticky_logo', 'Dark Sticky Logo', '2023-02-04 13:25:06', '2023-02-04 13:25:06'),
(185, 'en', 'dark_sticky_logo_mobile', 'Dark Sticky Logo (Mobile)', '2023-02-04 13:25:06', '2023-02-04 13:25:06'),
(186, 'en', 'admin_logo', 'Admin Logo', '2023-02-04 13:25:06', '2023-02-04 13:25:06'),
(187, 'en', 'admin_logo_mobile', 'Admin Logo (Mobile)', '2023-02-04 13:25:06', '2023-02-04 13:25:06'),
(188, 'en', 'admin_dark_logo', 'Admin Dark Logo', '2023-02-04 13:25:06', '2023-02-04 13:25:06'),
(189, 'en', 'admin_dark_logo_mobile', 'Admin Dark Logo (Mobile)', '2023-02-04 13:25:06', '2023-02-04 13:25:06'),
(190, 'en', 'favicon', 'Favicon', '2023-02-04 13:25:06', '2023-02-04 13:25:06'),
(191, 'en', 'default_language', 'Default Language', '2023-02-04 13:25:06', '2023-02-04 13:25:06'),
(192, 'en', 'select_default_language', 'Select default language', '2023-02-04 13:25:06', '2023-02-04 13:25:06'),
(193, 'en', 'select_default_timezone', 'Select Default Timezone', '2023-02-04 13:25:06', '2023-02-04 13:25:06'),
(194, 'en', 'copyright_text', 'Copyright Text', '2023-02-04 13:25:06', '2023-02-04 13:25:06'),
(195, 'en', 'submit', 'Submit', '2023-02-04 13:25:06', '2023-02-04 13:25:06'),
(196, 'en', 'general_settings_updated_successfully', 'General settings updated successfully', '2023-02-04 13:29:19', '2023-02-04 13:29:19'),
(197, 'en', 'placeholder_image', 'Placeholder Image', '2023-02-04 19:29:38', '2023-02-04 19:29:38'),
(198, 'en', 'watermark_settings', 'Watermark Settings', '2023-02-04 19:29:39', '2023-02-04 19:29:39'),
(199, 'en', 'enabledisable_watermark', 'Enable/Disable Watermark', '2023-02-04 19:29:39', '2023-02-04 19:29:39'),
(200, 'en', 'watermark_image', 'Watermark Image', '2023-02-04 19:29:39', '2023-02-04 19:29:39'),
(201, 'en', 'watermark_image_position', 'Watermark Image Position', '2023-02-04 19:29:39', '2023-02-04 19:29:39'),
(202, 'en', 'top_left', 'Top Left', '2023-02-04 19:29:39', '2023-02-04 19:29:39'),
(203, 'en', 'top', 'Top', '2023-02-04 19:29:39', '2023-02-04 19:29:39'),
(204, 'en', 'top_right', 'Top Right', '2023-02-04 19:29:39', '2023-02-04 19:29:39'),
(205, 'en', 'left', 'Left', '2023-02-04 19:29:39', '2023-02-04 19:29:39'),
(206, 'en', 'center', 'Center', '2023-02-04 19:29:39', '2023-02-04 19:29:39'),
(207, 'en', 'right', 'Right', '2023-02-04 19:29:39', '2023-02-04 19:29:39'),
(208, 'en', 'bottom_left', 'Bottom Left', '2023-02-04 19:29:39', '2023-02-04 19:29:39'),
(209, 'en', 'watermarking_image_opacity_', 'Watermarking image opacity (%)', '2023-02-04 19:29:39', '2023-02-04 19:29:39'),
(210, 'en', 'watermarking_image_opacity', 'Watermarking image opacity', '2023-02-04 19:29:39', '2023-02-04 19:29:39'),
(211, 'en', 'media_thumbnails_sizes', 'Media Thumbnails Sizes', '2023-02-04 19:29:39', '2023-02-04 19:29:39'),
(212, 'en', 'large_thumb_image_size', 'Large Thumb Image Size', '2023-02-04 19:29:39', '2023-02-04 19:29:39'),
(213, 'en', 'large_thumb_image_width', 'Large thumb image width', '2023-02-04 19:29:39', '2023-02-04 19:29:39'),
(214, 'en', 'large_thumb_image_height', 'Large thumb image height', '2023-02-04 19:29:39', '2023-02-04 19:29:39'),
(215, 'en', 'medium_thumb_image_size', 'Medium Thumb Image Size', '2023-02-04 19:29:39', '2023-02-04 19:29:39'),
(216, 'en', 'medium_thumb_image_width', 'Medium thumb image width', '2023-02-04 19:29:39', '2023-02-04 19:29:39'),
(217, 'en', 'medium_thumb_image_height', 'Medium thumb image height', '2023-02-04 19:29:39', '2023-02-04 19:29:39'),
(218, 'en', 'small_thumb_image_size', 'Small Thumb Image Size', '2023-02-04 19:29:39', '2023-02-04 19:29:39'),
(219, 'en', 'small_thumb_image_width', 'Small thumb image width', '2023-02-04 19:29:39', '2023-02-04 19:29:39'),
(220, 'en', 'small_thumb_image_height', 'Small thumb image height', '2023-02-04 19:29:39', '2023-02-04 19:29:39'),
(221, 'en', 'select_image_applicable_folder', 'Select image applicable folder', '2023-02-04 19:29:39', '2023-02-04 19:29:39'),
(222, 'en', 'media_settings_updated_successfully', 'Media settings updated successfully', '2023-02-04 19:29:47', '2023-02-04 19:29:47'),
(223, 'en', 'add_new_category', 'Add New Category', '2023-02-04 19:41:34', '2023-02-04 19:41:34'),
(224, 'en', 'parent', 'Parent', '2023-02-04 19:41:34', '2023-02-04 19:41:34'),
(225, 'en', 'icon', 'Icon', '2023-02-04 19:41:34', '2023-02-04 19:41:34'),
(226, 'en', 'featured', 'Featured', '2023-02-04 19:41:34', '2023-02-04 19:41:34'),
(227, 'en', 'new_category', 'New Category', '2023-02-04 19:41:57', '2023-02-04 19:41:57'),
(228, 'en', 'type_here', 'Type here', '2023-02-04 19:41:57', '2023-02-04 19:41:57'),
(229, 'en', 'permalink', 'Permalink', '2023-02-04 19:41:57', '2023-02-04 19:41:57'),
(230, 'en', 'edit', 'Edit', '2023-02-04 19:41:57', '2023-02-04 19:41:57'),
(231, 'en', 'select_a_category', 'Select a Category', '2023-02-04 19:41:57', '2023-02-04 19:41:57'),
(232, 'en', 'meta_title', 'Meta Title', '2023-02-04 19:41:57', '2023-02-04 19:41:57'),
(233, 'en', 'meta_image', 'Meta Image', '2023-02-04 19:41:57', '2023-02-04 19:41:57'),
(234, 'en', 'meta_description', 'Meta Description', '2023-02-04 19:41:57', '2023-02-04 19:41:57'),
(235, 'en', 'name_is_required', 'Name is required', '2023-02-04 19:44:17', '2023-02-04 19:44:17'),
(236, 'en', 'permalink_is_required', 'Permalink is required', '2023-02-04 19:44:17', '2023-02-04 19:44:17'),
(237, 'en', 'permalink_is_already_exists', 'Permalink is already exists', '2023-02-04 19:44:17', '2023-02-04 19:44:17'),
(238, 'en', 'selected_parent_does_not_exists', 'Selected parent does not exists', '2023-02-04 19:44:17', '2023-02-04 19:44:17'),
(239, 'en', 'new_category_added_successfully', 'New category added successfully', '2023-02-04 19:44:17', '2023-02-04 19:44:17'),
(240, 'en', 'edit_category', 'Edit Category', '2023-02-04 19:44:31', '2023-02-04 19:44:31'),
(241, 'en', 'category_updated_successfully', 'Category updated successfully', '2023-02-04 19:45:48', '2023-02-04 19:45:48'),
(242, 'en', 'tl_commerce__theme_options', 'TL Commerce | Theme Options', '2023-02-04 19:59:13', '2023-02-04 19:59:13'),
(243, 'en', 'save_changes', 'Save Changes', '2023-02-04 19:59:13', '2023-02-04 19:59:13'),
(244, 'en', 'reset_section', 'Reset Section', '2023-02-04 19:59:13', '2023-02-04 19:59:13'),
(245, 'en', 'reset_all', 'Reset All', '2023-02-04 19:59:13', '2023-02-04 19:59:13'),
(246, 'en', 'general', 'General', '2023-02-04 19:59:13', '2023-02-04 19:59:13'),
(247, 'en', 'back_to_top', 'Back To Top', '2023-02-04 19:59:13', '2023-02-04 19:59:13'),
(248, 'en', 'theme_color', 'Theme Color', '2023-02-04 19:59:13', '2023-02-04 19:59:13'),
(249, 'en', 'typography', 'Typography', '2023-02-04 19:59:13', '2023-02-04 19:59:13'),
(250, 'en', 'body_typography', 'Body Typography', '2023-02-04 19:59:13', '2023-02-04 19:59:13'),
(251, 'en', 'paragraph_typography', 'Paragraph Typography', '2023-02-04 19:59:13', '2023-02-04 19:59:13'),
(252, 'en', 'heading_typography', 'Heading Typography', '2023-02-04 19:59:13', '2023-02-04 19:59:13'),
(253, 'en', 'menu_typography', 'Menu Typography', '2023-02-04 19:59:13', '2023-02-04 19:59:13'),
(254, 'en', 'button_typography', 'Button Typography', '2023-02-04 19:59:13', '2023-02-04 19:59:13'),
(255, 'en', 'custom_fonts', 'Custom Fonts', '2023-02-04 19:59:13', '2023-02-04 19:59:13'),
(256, 'en', 'header', 'Header', '2023-02-04 19:59:13', '2023-02-04 19:59:13'),
(257, 'en', 'header_option', 'Header Option', '2023-02-04 19:59:13', '2023-02-04 19:59:13'),
(258, 'en', 'header_logo', 'Header Logo', '2023-02-04 19:59:13', '2023-02-04 19:59:13'),
(259, 'en', 'menu', 'Menu', '2023-02-04 19:59:13', '2023-02-04 19:59:13'),
(260, 'en', 'blog_option', 'Blog Option', '2023-02-04 19:59:13', '2023-02-04 19:59:13'),
(261, 'en', 'single_blog_page', 'Single Blog Page', '2023-02-04 19:59:13', '2023-02-04 19:59:13'),
(262, 'en', 'sidebar_options', 'Sidebar Options', '2023-02-04 19:59:13', '2023-02-04 19:59:13'),
(263, 'en', '404_page', '404 Page', '2023-02-04 19:59:13', '2023-02-04 19:59:13'),
(264, 'en', 'subscribe', 'Subscribe', '2023-02-04 19:59:13', '2023-02-04 19:59:13'),
(265, 'en', 'social', 'Social', '2023-02-04 19:59:13', '2023-02-04 19:59:13'),
(266, 'en', 'footer', 'Footer', '2023-02-04 19:59:13', '2023-02-04 19:59:13'),
(267, 'en', 'custom_css', 'Custom Css', '2023-02-04 19:59:13', '2023-02-04 19:59:13'),
(268, 'en', 'reset_confirmation', 'Reset Confirmation', '2023-02-04 19:59:13', '2023-02-04 19:59:13'),
(269, 'en', 'are_you_sure_to_want_to_reset', 'Are you sure to want to reset', '2023-02-04 19:59:13', '2023-02-04 19:59:13'),
(270, 'en', 'action_failed', 'Action Failed', '2023-02-04 19:59:13', '2023-02-04 19:59:13'),
(271, 'en', 'select_font_subsets', 'Select Font Subsets', '2023-02-04 19:59:13', '2023-02-04 19:59:13'),
(272, 'en', 'select_weight__style', 'Select Weight & Style', '2023-02-04 19:59:13', '2023-02-04 19:59:13'),
(273, 'en', 'new_slide', 'New Slide', '2023-02-04 19:59:13', '2023-02-04 19:59:13'),
(274, 'en', 'iconexample_fa_fafacebook', 'Icon(example: fa fa-facebook)', '2023-02-04 19:59:13', '2023-02-04 19:59:13'),
(275, 'en', 'url', 'Url', '2023-02-04 19:59:13', '2023-02-04 19:59:13'),
(276, 'en', 'back_to_top_button', 'Back To Top Button', '2023-02-04 19:59:16', '2023-02-04 19:59:16'),
(277, 'en', 'switch_on_to_display_back_to_top_button', 'Switch On to Display back to top button.', '2023-02-04 19:59:16', '2023-02-04 19:59:16'),
(278, 'en', 'custom_back_to_top_button', 'Custom Back To Top Button', '2023-02-04 19:59:16', '2023-02-04 19:59:16'),
(279, 'en', 'if_you_switch_it_off_it_will_show_default_design_for_back_to_top_button', 'If you switch it off, it will show default design for \"back to top\" button.', '2023-02-04 19:59:16', '2023-02-04 19:59:16'),
(280, 'en', 'custom_back_to_top_button_icon', 'Custom Back To Top Button Icon', '2023-02-04 19:59:16', '2023-02-04 19:59:16'),
(281, 'en', 'select_back_to_top_button_icon', 'Select Back To Top Button icon.', '2023-02-04 19:59:16', '2023-02-04 19:59:16'),
(282, 'en', 'back_to_top_button_background_color', 'Back To Top Button Background Color', '2023-02-04 19:59:16', '2023-02-04 19:59:16'),
(283, 'en', 'set_back_to_top_button_background_color', 'Set Back to top button Background Color.', '2023-02-04 19:59:16', '2023-02-04 19:59:16'),
(284, 'en', 'select_color', 'Select Color', '2023-02-04 19:59:16', '2023-02-04 19:59:16'),
(285, 'en', 'transparent', 'Transparent', '2023-02-04 19:59:16', '2023-02-04 19:59:16'),
(286, 'en', 'back_to_top_button_color', 'Back To Top Button Color', '2023-02-04 19:59:16', '2023-02-04 19:59:16'),
(287, 'en', 'set_back_to_top_button_color', 'Set Back to top button Color.', '2023-02-04 19:59:16', '2023-02-04 19:59:16'),
(288, 'en', 'back_to_top_hover_button_color', 'Back To Top Hover Button Color', '2023-02-04 19:59:16', '2023-02-04 19:59:16'),
(289, 'en', 'back_to_top_button_hover_background_color', 'Back To Top Button Hover Background Color', '2023-02-04 19:59:16', '2023-02-04 19:59:16'),
(290, 'en', 'set_back_to_top_button_hover_background_color', 'Set Back to top button hover background Color.', '2023-02-04 19:59:16', '2023-02-04 19:59:16'),
(291, 'en', 'add_new_brand', 'Add New Brand', '2023-02-04 20:00:10', '2023-02-04 20:00:10'),
(292, 'en', 'new_brand', 'New Brand', '2023-02-04 20:03:23', '2023-02-04 20:03:23'),
(293, 'en', 'invalid_logo', 'Invalid logo', '2023-02-04 20:04:10', '2023-02-04 20:04:10'),
(294, 'en', 'invalid_meta_image', 'Invalid meta image', '2023-02-04 20:04:10', '2023-02-04 20:04:10'),
(295, 'en', 'new_brand_added_successfully', 'New Brand added successfully', '2023-02-04 20:04:10', '2023-02-04 20:04:10'),
(296, 'en', 'add_new_color', 'Add New Color', '2023-02-04 20:06:11', '2023-02-04 20:06:11'),
(297, 'en', 'code', 'Code', '2023-02-04 20:06:11', '2023-02-04 20:06:11'),
(298, 'en', 'new_color', 'New Color', '2023-02-04 20:07:03', '2023-02-04 20:07:03'),
(299, 'en', 'tl_commerce__blog_category', 'TL Commerce | Blog Category', '2023-02-04 20:08:18', '2023-02-04 20:08:18'),
(300, 'en', 'blog_categories', 'Blog Categories', '2023-02-04 20:08:18', '2023-02-04 20:08:18'),
(301, 'en', 'add_blog_category', 'Add Blog Category', '2023-02-04 20:08:18', '2023-02-04 20:08:18'),
(302, 'en', 'all', 'All', '2023-02-04 20:08:18', '2023-02-04 20:08:18'),
(303, 'en', 'items_of', 'items of', '2023-02-04 20:08:18', '2023-02-04 20:08:18'),
(304, 'en', 'add_new_attribute', 'Add New Attribute', '2023-02-04 20:08:41', '2023-02-04 20:08:41'),
(305, 'en', 'values', 'Values', '2023-02-04 20:08:41', '2023-02-04 20:08:41'),
(306, 'en', 'code_is_required', 'Code is required', '2023-02-04 20:13:41', '2023-02-04 20:13:41'),
(307, 'en', 'new_color_added_successfully', 'New color added successfully', '2023-02-04 20:13:41', '2023-02-04 20:13:41'),
(308, 'en', 'new_attribute', 'New Attribute', '2023-02-04 20:19:39', '2023-02-04 20:19:39'),
(309, 'en', 'add_new_unit', 'Add New Unit', '2023-02-04 20:19:51', '2023-02-04 20:19:51'),
(310, 'en', 'new_units', 'New Units', '2023-02-04 20:19:54', '2023-02-04 20:19:54'),
(311, 'en', 'add_new_tag', 'Add New Tag', '2023-02-04 20:20:06', '2023-02-04 20:20:06'),
(312, 'en', 'new_tag', 'New Tag', '2023-02-04 20:20:09', '2023-02-04 20:20:09'),
(313, 'en', 'new_tag_added_successfully', 'New tag added successfully', '2023-02-04 20:21:02', '2023-02-04 20:21:02'),
(314, 'en', 'conditions', 'Conditions', '2023-02-04 20:22:06', '2023-02-04 20:22:06'),
(315, 'en', 'add_new_condition', 'Add New Condition', '2023-02-04 20:22:06', '2023-02-04 20:22:06'),
(316, 'en', 'product_information', 'Product Information', '2023-02-04 20:22:38', '2023-02-04 20:22:38'),
(317, 'en', 'product_name', 'Product Name', '2023-02-04 20:22:38', '2023-02-04 20:22:38'),
(318, 'en', 'brand', 'Brand', '2023-02-04 20:22:38', '2023-02-04 20:22:38'),
(319, 'en', 'unit', 'Unit', '2023-02-04 20:22:38', '2023-02-04 20:22:38'),
(320, 'en', 'condition', 'Condition', '2023-02-04 20:22:38', '2023-02-04 20:22:38'),
(321, 'en', 'product_type', 'Product Type', '2023-02-04 20:22:38', '2023-02-04 20:22:38'),
(322, 'en', 'single_product', 'Single Product', '2023-02-04 20:22:38', '2023-02-04 20:22:38'),
(323, 'en', 'variant_product', 'Variant Product', '2023-02-04 20:22:38', '2023-02-04 20:22:38'),
(324, 'en', 'product_variation', 'Product Variation', '2023-02-04 20:22:38', '2023-02-04 20:22:38'),
(325, 'en', 'choice_options', 'Choice Options', '2023-02-04 20:22:38', '2023-02-04 20:22:38'),
(326, 'en', 'product_price_and_stock', 'Product Price And Stock', '2023-02-04 20:22:38', '2023-02-04 20:22:38'),
(327, 'en', 'purchase_price', 'Purchase Price', '2023-02-04 20:22:38', '2023-02-04 20:22:38'),
(328, 'en', 'unit_price', 'Unit Price', '2023-02-04 20:22:38', '2023-02-04 20:22:38'),
(329, 'en', 'quantity', 'Quantity', '2023-02-04 20:22:38', '2023-02-04 20:22:38'),
(330, 'en', 'sku', 'Sku', '2023-02-04 20:22:38', '2023-02-04 20:22:38'),
(331, 'en', 'type_product_sku', 'Type product sku', '2023-02-04 20:22:38', '2023-02-04 20:22:38'),
(332, 'en', 'no_variant_selected_yet', 'No variant selected yet', '2023-02-04 20:22:38', '2023-02-04 20:22:38'),
(333, 'en', 'product_discount', 'Product Discount', '2023-02-04 20:22:38', '2023-02-04 20:22:38'),
(334, 'en', 'discount', 'Discount', '2023-02-04 20:22:38', '2023-02-04 20:22:38'),
(335, 'en', 'flat', 'Flat', '2023-02-04 20:22:38', '2023-02-04 20:22:38'),
(336, 'en', 'percentage', 'Percentage', '2023-02-04 20:22:38', '2023-02-04 20:22:38'),
(337, 'en', 'color_variation_images', 'Color Variation Images', '2023-02-04 20:22:38', '2023-02-04 20:22:38'),
(338, 'en', 'no_color_variant_selected_yet', 'No color variant selected yet', '2023-02-04 20:22:38', '2023-02-04 20:22:38'),
(339, 'en', 'product_description', 'Product Description', '2023-02-04 20:22:38', '2023-02-04 20:22:38'),
(340, 'en', 'summary', 'Summary', '2023-02-04 20:22:38', '2023-02-04 20:22:38'),
(341, 'en', 'product_images', 'Product Images', '2023-02-04 20:22:38', '2023-02-04 20:22:38'),
(342, 'en', 'thumbnail_image', 'Thumbnail Image', '2023-02-04 20:22:38', '2023-02-04 20:22:38'),
(343, 'en', 'gallery_images', 'Gallery Images', '2023-02-04 20:22:38', '2023-02-04 20:22:38'),
(344, 'en', 'choose_files', 'Choose Files', '2023-02-04 20:22:38', '2023-02-04 20:22:38'),
(345, 'en', 'pdf_specification', 'PDF Specification', '2023-02-04 20:22:38', '2023-02-04 20:22:38'),
(346, 'en', 'product_video', 'Product Video', '2023-02-04 20:22:38', '2023-02-04 20:22:38'),
(347, 'en', 'youtube_link', 'Youtube Link', '2023-02-04 20:22:38', '2023-02-04 20:22:38'),
(348, 'en', 'seo_meta_tags', 'Seo Meta Tags', '2023-02-04 20:22:38', '2023-02-04 20:22:38'),
(349, 'en', 'refundable', 'Refundable', '2023-02-04 20:22:38', '2023-02-04 20:22:38'),
(350, 'en', 'authentic', 'Authentic', '2023-02-04 20:22:38', '2023-02-04 20:22:38'),
(351, 'en', 'shipping_information', 'Shipping Information', '2023-02-04 20:22:38', '2023-02-04 20:22:38'),
(352, 'en', 'weight', 'Weight', '2023-02-04 20:22:38', '2023-02-04 20:22:38'),
(353, 'en', 'height', 'Height', '2023-02-04 20:22:38', '2023-02-04 20:22:38'),
(354, 'en', 'length', 'Length', '2023-02-04 20:22:38', '2023-02-04 20:22:38'),
(355, 'en', 'width', 'Width', '2023-02-04 20:22:38', '2023-02-04 20:22:38'),
(356, 'en', 'shipping_profile', 'Shipping Profile', '2023-02-04 20:22:38', '2023-02-04 20:22:38'),
(357, 'en', 'cash_on_delivery', 'Cash On Delivery', '2023-02-04 20:22:38', '2023-02-04 20:22:38'),
(358, 'en', 'anywhere', 'Anywhere', '2023-02-04 20:22:38', '2023-02-04 20:22:38'),
(359, 'en', 'custom_locations', 'Custom Locations', '2023-02-04 20:22:38', '2023-02-04 20:22:38'),
(360, 'en', 'manage_taxes', 'Manage Taxes', '2023-02-04 20:22:38', '2023-02-04 20:22:38'),
(361, 'en', 'warranty', 'Warranty', '2023-02-04 20:22:38', '2023-02-04 20:22:38'),
(362, 'en', 'replacement_warranty', 'Replacement Warranty', '2023-02-04 20:22:38', '2023-02-04 20:22:38'),
(363, 'en', 'warranty_days', 'Warranty Days', '2023-02-04 20:22:38', '2023-02-04 20:22:38'),
(364, 'en', 'low_stock_quantity', 'Low stock quantity', '2023-02-04 20:22:38', '2023-02-04 20:22:38'),
(365, 'en', 'purchase_quantity', 'Purchase Quantity', '2023-02-04 20:22:38', '2023-02-04 20:22:38'),
(366, 'en', 'minimum_quantity', 'Minimum Quantity', '2023-02-04 20:22:38', '2023-02-04 20:22:38'),
(367, 'en', 'miximum_quantity', 'Miximum Quantity', '2023-02-04 20:22:38', '2023-02-04 20:22:38'),
(368, 'en', 'attatchment_on_purchase', 'Attatchment on Purchase', '2023-02-04 20:22:38', '2023-02-04 20:22:38'),
(369, 'en', 'attatchment_name', 'Attatchment Name', '2023-02-04 20:22:38', '2023-02-04 20:22:38'),
(370, 'en', 'no_collection_avaible', 'No Collection Avaible', '2023-02-04 20:22:38', '2023-02-04 20:22:38'),
(371, 'en', 'add_new_collection', 'Add New Collection', '2023-02-04 20:22:38', '2023-02-04 20:22:38'),
(372, 'en', 'save__draft', 'Save & Draft', '2023-02-04 20:22:38', '2023-02-04 20:22:38'),
(373, 'en', 'save__publish', 'Save & Publish', '2023-02-04 20:22:38', '2023-02-04 20:22:38'),
(374, 'en', 'select_choice_option', 'Select Choice Option', '2023-02-04 20:22:38', '2023-02-04 20:22:38'),
(375, 'en', 'select_product_category', 'Select product category', '2023-02-04 20:22:38', '2023-02-04 20:22:38'),
(376, 'en', 'select_product_brand', 'Select product brand', '2023-02-04 20:22:38', '2023-02-04 20:22:38'),
(377, 'en', 'select_product_unit', 'select product unit', '2023-02-04 20:22:38', '2023-02-04 20:22:38'),
(378, 'en', 'select_product_condition', 'select product condition', '2023-02-04 20:22:38', '2023-02-04 20:22:38'),
(379, 'en', 'select_or_insert_product_tags', 'Select or insert product tags', '2023-02-04 20:22:38', '2023-02-04 20:22:38'),
(380, 'en', 'nothing_selected', 'Nothing Selected', '2023-02-04 20:22:38', '2023-02-04 20:22:38'),
(381, 'en', 'attribute_added_successfully', 'Attribute added successfully', '2023-02-04 20:26:27', '2023-02-04 20:26:27'),
(382, 'en', 'tl_commerce__add_blog', 'TL Commerce | Add Blog', '2023-02-04 20:34:26', '2023-02-04 20:34:26'),
(383, 'en', 'add_blog', 'Add Blog', '2023-02-04 20:34:26', '2023-02-04 20:34:26'),
(384, 'en', 'short_description', 'Short Description', '2023-02-04 20:34:26', '2023-02-04 20:34:26'),
(385, 'en', 'content', 'Content', '2023-02-04 20:34:26', '2023-02-04 20:34:26'),
(386, 'en', 'publish', 'Publish', '2023-02-04 20:34:26', '2023-02-04 20:34:26'),
(387, 'en', 'draft', 'Draft', '2023-02-04 20:34:26', '2023-02-04 20:34:26'),
(388, 'en', 'preview', 'Preview', '2023-02-04 20:34:26', '2023-02-04 20:34:26'),
(389, 'en', 'blog_image', 'Blog Image', '2023-02-04 20:34:26', '2023-02-04 20:34:26'),
(390, 'en', 'blog_status', 'Blog Status', '2023-02-04 20:34:26', '2023-02-04 20:34:26'),
(391, 'en', 'featured_status', 'Featured Status', '2023-02-04 20:34:26', '2023-02-04 20:34:26'),
(392, 'en', 'no_option_selected', 'No Option Selected', '2023-02-04 20:34:26', '2023-02-04 20:34:26'),
(393, 'en', 'add', 'Add', '2023-02-04 20:34:27', '2023-02-04 20:34:27'),
(394, 'en', 'only_active_categories', 'Only Active Categories', '2023-02-04 20:34:28', '2023-02-04 20:34:28'),
(395, 'en', 'select_parent', 'Select Parent', '2023-02-04 20:34:28', '2023-02-04 20:34:28'),
(396, 'en', 'select_a_parent_category', 'Select a Parent Category', '2023-02-04 20:34:28', '2023-02-04 20:34:28'),
(397, 'en', 'load_more', 'Load more', '2023-02-04 21:05:53', '2023-02-04 21:05:53'),
(398, 'en', 'per_page', 'Per page', '2023-02-04 22:02:27', '2023-02-04 22:02:27'),
(399, 'en', 'product_status', 'Product status', '2023-02-04 22:02:27', '2023-02-04 22:02:27'),
(400, 'en', 'published', 'Published', '2023-02-04 22:02:27', '2023-02-04 22:02:27'),
(401, 'en', 'unpublished', 'Unpublished', '2023-02-04 22:02:27', '2023-02-04 22:02:27'),
(402, 'en', 'product_featured', 'Product Featured', '2023-02-04 22:02:27', '2023-02-04 22:02:27'),
(403, 'en', 'regular', 'Regular', '2023-02-04 22:02:27', '2023-02-04 22:02:27'),
(404, 'en', 'no_discount', 'No Discount', '2023-02-04 22:02:27', '2023-02-04 22:02:27'),
(405, 'en', 'discounted', 'Discounted', '2023-02-04 22:02:27', '2023-02-04 22:02:27'),
(406, 'en', 'filter', 'Filter', '2023-02-04 22:02:27', '2023-02-04 22:02:27'),
(407, 'en', 'make_publish', 'Make publish', '2023-02-04 22:02:27', '2023-02-04 22:02:27'),
(408, 'en', 'make_unpublish', 'Make unpublish', '2023-02-04 22:02:27', '2023-02-04 22:02:27'),
(409, 'en', 'make_feature', 'Make feature', '2023-02-04 22:02:27', '2023-02-04 22:02:27'),
(410, 'en', 'remove_from_feature', 'Remove from feature', '2023-02-04 22:02:27', '2023-02-04 22:02:27'),
(411, 'en', 'remove_discount', 'Remove discount', '2023-02-04 22:02:27', '2023-02-04 22:02:27'),
(412, 'en', 'image', 'Image', '2023-02-04 22:02:27', '2023-02-04 22:02:27'),
(413, 'en', 'info', 'Info', '2023-02-04 22:02:27', '2023-02-04 22:02:27'),
(414, 'en', 'stock__sales', 'Stock & Sales', '2023-02-04 22:02:27', '2023-02-04 22:02:27'),
(415, 'en', 'update_product_information', 'Update Product Information', '2023-02-04 22:02:27', '2023-02-04 22:02:27'),
(416, 'en', 'processing', 'Processing', '2023-02-04 23:24:39', '2023-02-04 23:24:39'),
(417, 'en', 'to_shipped', 'To Shipped', '2023-02-04 23:24:39', '2023-02-04 23:24:39'),
(418, 'en', 'unpaid', 'Unpaid', '2023-02-04 23:24:39', '2023-02-04 23:24:39'),
(419, 'en', 'paid', 'Paid', '2023-02-04 23:24:39', '2023-02-04 23:24:39'),
(420, 'en', 'change_status_to_processing', 'Change status to processing', '2023-02-04 23:24:39', '2023-02-04 23:24:39'),
(421, 'en', 'change_status_to_ready_to_ship', 'Change status to ready to ship', '2023-02-04 23:24:39', '2023-02-04 23:24:39'),
(422, 'en', 'change_status_to_shipped', 'Change status to shipped', '2023-02-04 23:24:39', '2023-02-04 23:24:39'),
(423, 'en', 'change_status_to_delivered', 'Change status to delivered', '2023-02-04 23:24:39', '2023-02-04 23:24:39'),
(424, 'en', 'change_status_to_paid', 'Change status to paid', '2023-02-04 23:24:39', '2023-02-04 23:24:39'),
(425, 'en', 'change_status_to_unpaid', 'Change status to unpaid', '2023-02-04 23:24:39', '2023-02-04 23:24:39'),
(426, 'en', 'move_to_trash', 'Move to trash', '2023-02-04 23:24:39', '2023-02-04 23:24:39'),
(427, 'en', 'delivery_status', 'Delivery status', '2023-02-04 23:24:39', '2023-02-04 23:24:39'),
(428, 'en', 'payment_status', 'Payment status', '2023-02-04 23:24:39', '2023-02-04 23:24:39'),
(429, 'en', 'order_code', 'Order Code', '2023-02-04 23:24:39', '2023-02-04 23:24:39'),
(430, 'en', 'order_date', 'Order Date', '2023-02-04 23:24:39', '2023-02-04 23:24:39'),
(431, 'en', 'num_of_products', 'Num. of Products', '2023-02-04 23:24:39', '2023-02-04 23:24:39'),
(432, 'en', 'amount', 'Amount', '2023-02-04 23:24:39', '2023-02-04 23:24:39'),
(433, 'en', 'order_status', 'Order Status', '2023-02-04 23:24:39', '2023-02-04 23:24:39'),
(434, 'en', 'update_order_status', 'Update order status', '2023-02-04 23:24:39', '2023-02-04 23:24:39'),
(435, 'en', 'cancel_confirmation', 'Cancel Confirmation', '2023-02-04 23:24:39', '2023-02-04 23:24:39'),
(436, 'en', 'are_you_sure_to_cancel__this_order', 'Are you sure to cancel  this order', '2023-02-04 23:24:39', '2023-02-04 23:24:39'),
(437, 'en', 'confirm', 'Confirm', '2023-02-04 23:24:39', '2023-02-04 23:24:39'),
(438, 'en', 'accept_confirmation', 'Accept Confirmation', '2023-02-04 23:24:39', '2023-02-04 23:24:39'),
(439, 'en', 'are_you_sure_to_accept__this_order', 'Are you sure to accept  this order', '2023-02-04 23:24:39', '2023-02-04 23:24:39'),
(440, 'en', 'active', 'Active', '2023-02-05 14:33:17', '2023-02-05 14:33:17'),
(441, 'en', 'inactive', 'Inactive', '2023-02-05 14:33:24', '2023-02-05 14:33:24'),
(442, 'en', 'clear_filter', 'Clear Filter', '2023-02-05 14:33:24', '2023-02-05 14:33:24'),
(443, 'en', 'uid', 'Uid', '2023-02-05 14:33:24', '2023-02-05 14:33:24'),
(444, 'en', 'phone', 'Phone', '2023-02-05 14:33:24', '2023-02-05 14:33:24'),
(445, 'en', 'no_of_order', 'No. of Order', '2023-02-05 14:33:24', '2023-02-05 14:33:24'),
(446, 'en', 'are_you_sure_to_delete_this_customer', 'Are you sure to delete this customer', '2023-02-05 14:33:24', '2023-02-05 14:33:24'),
(447, 'en', 'reset_password', 'Reset Password', '2023-02-05 14:33:24', '2023-02-05 14:33:24'),
(448, 'en', 'new_password', 'New password', '2023-02-05 14:33:24', '2023-02-05 14:33:24'),
(449, 'en', 'enter_new_password', 'Enter new password', '2023-02-05 14:33:24', '2023-02-05 14:33:24'),
(450, 'en', 'customer_information', 'Customer information', '2023-02-05 14:33:24', '2023-02-05 14:33:24'),
(451, 'en', 'password_updated_successfully', 'Password updated successfully', '2023-02-05 14:33:24', '2023-02-05 14:33:24'),
(452, 'en', 'update_failed_', 'Update Failed ', '2023-02-05 14:33:24', '2023-02-05 14:33:24'),
(453, 'en', 'customer_updated_successfully', 'Customer updated successfully', '2023-02-05 14:33:24', '2023-02-05 14:33:24'),
(454, 'en', 'login_failed_', 'Login Failed ', '2023-02-05 14:33:24', '2023-02-05 14:33:24'),
(455, 'en', 'edit_brand', 'Edit Brand', '2023-02-05 15:34:51', '2023-02-05 15:34:51'),
(456, 'en', 'brand_updated_successfully', 'Brand updated successfully', '2023-02-05 15:36:19', '2023-02-05 15:36:19'),
(457, 'en', 'category_status_updated_successfully', 'Category status updated successfully', '2023-02-05 15:48:27', '2023-02-05 15:48:27'),
(458, 'en', 'cache_clear_successfully', 'Cache clear successfully', '2023-02-05 16:03:20', '2023-02-05 16:03:20'),
(459, 'en', 'category_deleted_successfully', 'Category deleted successfully', '2023-02-05 17:24:28', '2023-02-05 17:24:28'),
(460, 'en', 'tl_commerce__blog', 'TL Commerce | Blog', '2023-02-05 17:35:23', '2023-02-05 17:35:23'),
(461, 'en', 'mine', 'Mine', '2023-02-05 17:35:23', '2023-02-05 17:35:23'),
(462, 'en', 'scheduled', 'Scheduled', '2023-02-05 17:35:23', '2023-02-05 17:35:23'),
(463, 'en', 'drafts', 'Drafts', '2023-02-05 17:35:23', '2023-02-05 17:35:23'),
(464, 'en', 'author', 'Author', '2023-02-05 17:35:23', '2023-02-05 17:35:23'),
(465, 'en', 'category', 'Category', '2023-02-05 17:35:23', '2023-02-05 17:35:23'),
(466, 'en', 'comment', 'Comment', '2023-02-05 17:35:23', '2023-02-05 17:35:23'),
(467, 'en', 'tl_commerce__add_blog_category', 'TL Commerce | Add Blog Category', '2023-02-05 17:35:29', '2023-02-05 17:35:29'),
(468, 'en', 'please_insert_a_name', 'Please Insert a Name', '2023-02-05 17:35:34', '2023-02-05 17:35:34'),
(469, 'en', 'this_name_is_already_available_please_insert_another', 'This Name is Already Available Please Insert Another', '2023-02-05 17:35:34', '2023-02-05 17:35:34'),
(470, 'en', 'please_write_the_category_name_under_225_words', 'Please Write The Category Name under 225 words', '2023-02-05 17:35:34', '2023-02-05 17:35:34'),
(471, 'en', 'this_permalink_is_already_available_please_insert_another', 'This Permalink is Already Available Please Insert Another', '2023-02-05 17:35:34', '2023-02-05 17:35:34'),
(472, 'en', 'new_blog_category_created_successfully', 'New Blog Category Created Successfully!', '2023-02-05 17:35:34', '2023-02-05 17:35:34'),
(473, 'en', 'blog_category_publish_status_changed_successfully', 'Blog Category Publish Status Changed Successfully', '2023-02-05 17:35:39', '2023-02-05 17:35:39'),
(474, 'en', 'blog_category_featured_status_changed_successfully', 'Blog Category Featured Status Changed Successfully', '2023-02-05 17:35:41', '2023-02-05 17:35:41'),
(475, 'en', 'blog_category_deleted_successfully', 'Blog Category Deleted Successfully', '2023-02-05 17:35:53', '2023-02-05 17:35:53'),
(476, 'en', 'edit_menus', 'Edit Menus', '2023-02-05 17:52:16', '2023-02-05 17:52:16'),
(477, 'en', 'manage_locations', 'Manage Locations', '2023-02-05 17:52:16', '2023-02-05 17:52:16'),
(478, 'en', 'select_a_menu_to_edit', 'Select a menu to edit:', '2023-02-05 17:52:16', '2023-02-05 17:52:16'),
(479, 'en', 'create_menu_', 'Create Menu ', '2023-02-05 17:52:16', '2023-02-05 17:52:16'),
(480, 'en', 'translate_menu_into', 'Translate Menu Into:', '2023-02-05 17:52:16', '2023-02-05 17:52:16'),
(481, 'en', 'custom_links', 'Custom Links', '2023-02-05 17:52:16', '2023-02-05 17:52:16'),
(482, 'en', 'link_text', 'Link Text', '2023-02-05 17:52:16', '2023-02-05 17:52:16'),
(483, 'en', 'add_to_menu', 'Add to Menu', '2023-02-05 17:52:16', '2023-02-05 17:52:16'),
(484, 'en', 'most_recent', 'Most Recent', '2023-02-05 17:52:16', '2023-02-05 17:52:16'),
(485, 'en', 'view_all', 'View All', '2023-02-05 17:52:16', '2023-02-05 17:52:16'),
(486, 'en', 'search', 'Search', '2023-02-05 17:52:16', '2023-02-05 17:52:16'),
(487, 'en', 'select_all', 'Select All', '2023-02-05 17:52:16', '2023-02-05 17:52:16'),
(488, 'en', 'select_all_', 'Select All ', '2023-02-05 17:52:16', '2023-02-05 17:52:16'),
(489, 'en', 'posts', 'Posts', '2023-02-05 17:52:16', '2023-02-05 17:52:16'),
(490, 'en', 'menu_name', 'Menu Name', '2023-02-05 17:52:16', '2023-02-05 17:52:16'),
(491, 'en', 'give_your_menu_a_name_then_click_save_menu', 'Give your menu a name, then click Save Menu.', '2023-02-05 17:52:16', '2023-02-05 17:52:16'),
(492, 'en', 'menu_settings', 'Menu Settings', '2023-02-05 17:52:16', '2023-02-05 17:52:16'),
(493, 'en', 'display_locations', 'Display Locations', '2023-02-05 17:52:16', '2023-02-05 17:52:16'),
(494, 'en', 'currently_set_to__', 'Currently set to : ', '2023-02-05 17:52:16', '2023-02-05 17:52:16'),
(495, 'en', 'save_menu', 'Save Menu', '2023-02-05 17:52:16', '2023-02-05 17:52:16'),
(496, 'en', 'drag_the_items_into_the_order_you_prefer_click_the_arrow_on_the_right_of_the_item_to_reveal_additional_configuration_options', 'Drag the items into the order you prefer. Click the arrow on the right of the item to reveal additional configuration options.', '2023-02-05 17:52:16', '2023-02-05 17:52:16'),
(497, 'en', 'delete_menu', 'Delete Menu', '2023-02-05 17:52:16', '2023-02-05 17:52:16'),
(498, 'en', 'update_menu', 'Update Menu', '2023-02-05 17:52:16', '2023-02-05 17:52:16'),
(499, 'en', 'your_theme_supports', 'Your theme supports', '2023-02-05 17:52:16', '2023-02-05 17:52:16'),
(500, 'en', 'menus_select_which_menu_appears_in_each_location', 'menus. Select which menu appears in each location.', '2023-02-05 17:52:16', '2023-02-05 17:52:16'),
(501, 'en', 'theme_location', 'Theme Location', '2023-02-05 17:52:16', '2023-02-05 17:52:16'),
(502, 'en', 'assigned_menu', 'Assigned Menu', '2023-02-05 17:52:16', '2023-02-05 17:52:16'),
(503, 'en', '_edit', ' Edit', '2023-02-05 17:52:16', '2023-02-05 17:52:16'),
(504, 'en', 'use_new_menu', 'Use new menu', '2023-02-05 17:52:16', '2023-02-05 17:52:16'),
(505, 'en', 'comment_loading_failed', 'Comment Loading Failed', '2023-02-05 17:57:41', '2023-02-05 17:57:41'),
(506, 'en', 'title_is_required', 'Title is required', '2023-02-05 17:59:37', '2023-02-05 17:59:37'),
(507, 'en', '_image_for_desktop_is_required', ' Image for desktop is required', '2023-02-05 17:59:37', '2023-02-05 17:59:37'),
(508, 'en', 'image_for_mobile_is_required', 'Image for mobile is required', '2023-02-05 17:59:37', '2023-02-05 17:59:37'),
(509, 'en', 'slider_added_successfully', 'Slider added successfully', '2023-02-05 17:59:37', '2023-02-05 17:59:37'),
(510, 'en', 'homepage_builder', 'Homepage Builder', '2023-02-05 19:24:50', '2023-02-05 19:24:50'),
(511, 'en', 'home_page_sections', 'Home Page Sections', '2023-02-05 19:24:50', '2023-02-05 19:24:50'),
(512, 'en', 'add_new_section', 'Add New Section', '2023-02-05 19:24:50', '2023-02-05 19:24:50'),
(513, 'en', 'manage_slider', 'Manage Slider', '2023-02-05 19:24:50', '2023-02-05 19:24:50'),
(514, 'en', 'no_section_found', 'No Section Found', '2023-02-05 19:24:50', '2023-02-05 19:24:50'),
(515, 'en', 'are_you_sure_to_delete_this_section', 'Are you sure to delete this section', '2023-02-05 19:24:50', '2023-02-05 19:24:50'),
(516, 'en', 'new_section', 'New Section', '2023-02-05 19:24:53', '2023-02-05 19:24:53'),
(517, 'en', 'select_section', 'Select Section', '2023-02-05 19:24:53', '2023-02-05 19:24:53'),
(518, 'en', 'select_layout', 'Select Layout', '2023-02-05 19:24:53', '2023-02-05 19:24:53'),
(519, 'en', 'ads', 'Ads', '2023-02-05 19:24:53', '2023-02-05 19:24:53'),
(520, 'en', 'blogs', 'Blogs', '2023-02-05 19:24:53', '2023-02-05 19:24:53'),
(521, 'en', 'flash_deal', 'Flash Deal', '2023-02-05 19:24:53', '2023-02-05 19:24:53'),
(522, 'en', 'featured_product', 'Featured Product', '2023-02-05 19:24:53', '2023-02-05 19:24:53'),
(523, 'en', 'category_slider', 'Category Slider', '2023-02-05 19:24:53', '2023-02-05 19:24:53'),
(524, 'en', 'product_collection', 'Product Collection', '2023-02-05 19:24:53', '2023-02-05 19:24:53'),
(525, 'en', 'custom_product_section', 'Custom Product Section', '2023-02-05 19:24:53', '2023-02-05 19:24:53');
INSERT INTO `tl_translations` (`id`, `lang`, `lang_key`, `lang_value`, `created_at`, `updated_at`) VALUES
(526, 'en', 'section_properties', 'Section Properties', '2023-02-05 19:24:53', '2023-02-05 19:24:53'),
(527, 'en', 'background', 'Background', '2023-02-05 19:24:58', '2023-02-05 19:24:58'),
(528, 'en', 'advanced', 'Advanced', '2023-02-05 19:24:58', '2023-02-05 19:24:58'),
(529, 'en', 'title_is_not_visible_in_homepage', 'Title is not visible in homepage', '2023-02-05 19:24:58', '2023-02-05 19:24:58'),
(530, 'en', 'background_color', 'Background Color', '2023-02-05 19:24:58', '2023-02-05 19:24:58'),
(531, 'en', 'background_image', 'Background Image', '2023-02-05 19:24:58', '2023-02-05 19:24:58'),
(532, 'en', 'background_size', 'Background Size', '2023-02-05 19:24:58', '2023-02-05 19:24:58'),
(533, 'en', 'background_position', 'Background Position', '2023-02-05 19:24:58', '2023-02-05 19:24:58'),
(534, 'en', 'background_repeat', 'Background Repeat', '2023-02-05 19:24:58', '2023-02-05 19:24:58'),
(535, 'en', 'padding', 'Padding', '2023-02-05 19:24:58', '2023-02-05 19:24:58'),
(536, 'en', 'bottom', 'Bottom', '2023-02-05 19:24:58', '2023-02-05 19:24:58'),
(537, 'en', 'margin', 'Margin', '2023-02-05 19:24:58', '2023-02-05 19:24:58'),
(538, 'en', 'new_section_added_successfully', 'New Section added successfully', '2023-02-05 19:25:22', '2023-02-05 19:25:22'),
(539, 'en', 'update_section', 'Update Section', '2023-02-05 19:26:41', '2023-02-05 19:26:41'),
(540, 'en', 'section', 'Section', '2023-02-05 19:26:41', '2023-02-05 19:26:41'),
(541, 'en', 'cover', 'cover', '2023-02-05 19:26:41', '2023-02-05 19:26:41'),
(542, 'en', 'auto', 'auto', '2023-02-05 19:26:41', '2023-02-05 19:26:41'),
(543, 'en', 'contain', 'contain', '2023-02-05 19:26:41', '2023-02-05 19:26:41'),
(544, 'en', 'initial', 'initial', '2023-02-05 19:26:41', '2023-02-05 19:26:41'),
(545, 'en', 'revert', 'revert', '2023-02-05 19:26:41', '2023-02-05 19:26:41'),
(546, 'en', 'inherit', 'inherit', '2023-02-05 19:26:41', '2023-02-05 19:26:41'),
(547, 'en', 'revertlayer', 'revert-layer', '2023-02-05 19:26:41', '2023-02-05 19:26:41'),
(548, 'en', 'unset', 'unset', '2023-02-05 19:26:41', '2023-02-05 19:26:41'),
(549, 'en', 'section_updated_successfully', 'Section updated successfully', '2023-02-05 19:26:51', '2023-02-05 19:26:51'),
(550, 'en', 'visibility', 'Visibility', '2023-02-05 19:38:18', '2023-02-05 19:38:18'),
(551, 'en', 'visible', 'Visible', '2023-02-05 19:38:18', '2023-02-05 19:38:18'),
(552, 'en', 'hide', 'Hide', '2023-02-05 19:38:18', '2023-02-05 19:38:18'),
(553, 'en', 'filter_by_rating', 'Filter by rating', '2023-02-05 19:38:18', '2023-02-05 19:38:18'),
(554, 'en', 'product', 'Product', '2023-02-05 19:38:18', '2023-02-05 19:38:18'),
(555, 'en', 'order', 'Order', '2023-02-05 19:38:18', '2023-02-05 19:38:18'),
(556, 'en', 'rating', 'Rating', '2023-02-05 19:38:18', '2023-02-05 19:38:18'),
(557, 'en', 'review_details', 'Review Details', '2023-02-05 19:38:18', '2023-02-05 19:38:18'),
(558, 'en', 'new_unit_added_successfully', 'New unit added successfully', '2023-02-05 19:38:46', '2023-02-05 19:38:46'),
(559, 'en', 'attributes_values', 'Attributes Values', '2023-02-05 19:40:20', '2023-02-05 19:40:20'),
(560, 'en', 'attribute_values', 'Attribute Values', '2023-02-05 19:40:20', '2023-02-05 19:40:20'),
(561, 'en', 'new_value', 'New Value', '2023-02-05 19:40:20', '2023-02-05 19:40:20'),
(562, 'en', 'attribute', 'Attribute', '2023-02-05 19:40:20', '2023-02-05 19:40:20'),
(563, 'en', 'value', 'Value', '2023-02-05 19:40:20', '2023-02-05 19:40:20'),
(564, 'en', 'please_select_a_attribute', 'Please select a attribute', '2023-02-05 19:40:29', '2023-02-05 19:40:29'),
(565, 'en', 'invalid_attribute', 'Invalid attribute', '2023-02-05 19:40:29', '2023-02-05 19:40:29'),
(566, 'en', 'attribute_value_added_successfully', 'Attribute value added successfully', '2023-02-05 19:40:29', '2023-02-05 19:40:29'),
(567, 'en', 'variant', 'Variant', '2023-02-05 19:41:15', '2023-02-05 19:41:15'),
(568, 'en', 'selected_items_deleted_successfully', 'Selected items deleted successfully', '2023-02-05 19:44:49', '2023-02-05 19:44:49'),
(569, 'en', 'edit_unit', 'Edit Unit', '2023-02-05 19:45:33', '2023-02-05 19:45:33'),
(570, 'en', 'unit_updated_successfully', 'Unit updated successfully', '2023-02-05 19:45:37', '2023-02-05 19:45:37'),
(571, 'en', 'unit_deleted_successfully', 'Unit deleted successfully', '2023-02-05 19:46:25', '2023-02-05 19:46:25'),
(572, 'en', 'new_condition', 'New Condition', '2023-02-05 20:03:31', '2023-02-05 20:03:31'),
(573, 'en', 'new_condition_added_successfully', 'New condition added successfully', '2023-02-05 20:03:38', '2023-02-05 20:03:38'),
(574, 'en', 'color', 'Color', '2023-02-05 20:05:32', '2023-02-05 20:05:32'),
(575, 'en', 'discount_amount__must_be_a_number', 'Discount amount  must be a number', '2023-02-05 20:19:11', '2023-02-05 20:19:11'),
(576, 'en', 'purchase_price__must_be_a_number', 'Purchase price  must be a number', '2023-02-05 20:19:11', '2023-02-05 20:19:11'),
(577, 'en', 'purchase_price__is_required', 'Purchase price  is required', '2023-02-05 20:19:11', '2023-02-05 20:19:11'),
(578, 'en', 'unit_price__must_be_a_number', 'Unit price  must be a number', '2023-02-05 20:19:11', '2023-02-05 20:19:11'),
(579, 'en', 'unit_price__is_required', 'Unit price  is required', '2023-02-05 20:19:11', '2023-02-05 20:19:11'),
(580, 'en', 'quantity__must_be_a_number', 'Quantity  must be a number', '2023-02-05 20:19:11', '2023-02-05 20:19:11'),
(581, 'en', 'quantity__is_required', 'Quantity  is required', '2023-02-05 20:19:11', '2023-02-05 20:19:11'),
(582, 'en', 'new_product_created_successfully', 'New product created successfully', '2023-02-05 20:26:01', '2023-02-05 20:26:01'),
(583, 'en', 'set_discount', 'Set discount', '2023-02-05 20:26:02', '2023-02-05 20:26:02'),
(584, 'en', 'update_price', 'Update price', '2023-02-05 20:26:02', '2023-02-05 20:26:02'),
(585, 'en', 'stock', 'Stock', '2023-02-05 20:26:02', '2023-02-05 20:26:02'),
(586, 'en', 'low', 'Low', '2023-02-05 20:26:02', '2023-02-05 20:26:02'),
(587, 'en', 'num_of_sale', 'Num of Sale', '2023-02-05 20:26:02', '2023-02-05 20:26:02'),
(588, 'en', 'update_stock', 'Update stock', '2023-02-05 20:26:02', '2023-02-05 20:26:02'),
(589, 'en', 'items_deleted_successfully', 'Items Deleted Successfully', '2023-02-05 20:27:05', '2023-02-05 20:27:05'),
(590, 'en', 'add_new_country', 'Add New Country', '2023-02-05 20:37:07', '2023-02-05 20:37:07'),
(591, 'en', 'phone_code', 'Phone Code', '2023-02-05 20:37:07', '2023-02-05 20:37:07'),
(592, 'en', 'flag', 'Flag', '2023-02-05 20:37:07', '2023-02-05 20:37:07'),
(593, 'en', 'new_country', 'New Country', '2023-02-05 20:37:12', '2023-02-05 20:37:12'),
(594, 'en', 'select_a_option', 'Select a option', '2023-02-05 20:37:12', '2023-02-05 20:37:12'),
(595, 'en', 'add_new_language', 'Add New Language', '2023-02-05 20:42:39', '2023-02-05 20:42:39'),
(596, 'en', 'native_name', 'Native Name', '2023-02-05 20:42:39', '2023-02-05 20:42:39'),
(597, 'en', 'rtl', 'RTL', '2023-02-05 20:42:39', '2023-02-05 20:42:39'),
(598, 'en', 'backend_translations', 'Backend Translations', '2023-02-05 20:42:39', '2023-02-05 20:42:39'),
(599, 'en', 'frontend_translations', 'Frontend Translations', '2023-02-05 20:42:39', '2023-02-05 20:42:39'),
(600, 'en', 'cencel', 'Cencel', '2023-02-05 20:42:39', '2023-02-05 20:42:39'),
(601, 'en', 'new_language', 'New Language', '2023-02-05 20:42:44', '2023-02-05 20:42:44'),
(602, 'en', 'type_name', 'Type Name', '2023-02-05 20:42:44', '2023-02-05 20:42:44'),
(603, 'en', 'type__native_name', 'Type  Native Name', '2023-02-05 20:42:44', '2023-02-05 20:42:44'),
(604, 'en', 'new_language_added_successfully', 'New language added successfully', '2023-02-05 20:43:50', '2023-02-05 20:43:50'),
(605, 'en', 'rtl_status_updated_successfully', 'RTL status updated successfully', '2023-02-05 20:50:32', '2023-02-05 20:50:32'),
(606, 'en', 'select_countries', 'Select Countries', '2023-02-05 20:55:45', '2023-02-05 20:55:45'),
(607, 'en', 'select_states', 'Select States', '2023-02-05 20:55:47', '2023-02-05 20:55:47'),
(608, 'en', 'select_cities', 'Select Cities', '2023-02-05 20:55:47', '2023-02-05 20:55:47'),
(609, 'en', 'product_discount_updated_successfully', 'Product discount updated successfully', '2023-02-05 21:11:16', '2023-02-05 21:11:16'),
(610, 'en', 'product_discount_update_faled', 'Product discount update faled', '2023-02-05 21:11:16', '2023-02-05 21:11:16'),
(611, 'en', 'product_price_updated_successfully', 'Product price updated successfully', '2023-02-05 21:11:16', '2023-02-05 21:11:16'),
(612, 'en', 'product_price_update_faled', 'Product price update faled', '2023-02-05 21:11:16', '2023-02-05 21:11:16'),
(613, 'en', 'product_stock_updated_successfully', 'Product stock updated successfully', '2023-02-05 21:11:16', '2023-02-05 21:11:16'),
(614, 'en', 'product_stock_update_faled', 'Product stock update faled', '2023-02-05 21:11:16', '2023-02-05 21:11:16'),
(615, 'en', 'key', 'Key', '2023-02-06 16:53:39', '2023-02-06 16:53:39'),
(616, 'en', 'language', 'Language', '2023-02-06 16:53:39', '2023-02-06 16:53:39'),
(617, 'en', 'save_chnages', 'Save Chnages', '2023-02-06 16:56:10', '2023-02-06 16:56:10'),
(618, 'en', 'translations_updated_successfully', 'Translations updated successfully', '2023-02-06 16:57:32', '2023-02-06 16:57:32'),
(619, 'en', 'tl_commerce__comment_setting', 'TL Commerce | Comment Setting', '2023-02-06 17:25:08', '2023-02-06 17:25:08'),
(620, 'en', 'comment_setting', 'Comment Setting', '2023-02-06 17:25:08', '2023-02-06 17:25:08'),
(621, 'en', 'default_blog_settings', 'Default Blog settings', '2023-02-06 17:25:08', '2023-02-06 17:25:08'),
(622, 'en', 'allow_people_to_submit_comments_on_new_blogs', 'Allow people to submit comments on new blogs', '2023-02-06 17:25:08', '2023-02-06 17:25:08'),
(623, 'en', 'other_comment_settings', 'Other comment settings', '2023-02-06 17:25:08', '2023-02-06 17:25:08'),
(624, 'en', 'comment_author_must_fill_out_name_and_email', 'Comment author must fill out name and email', '2023-02-06 17:25:08', '2023-02-06 17:25:08'),
(625, 'en', 'users_must_be_registered_and_logged_in_to_comment', 'Users must be registered and logged in to comment', '2023-02-06 17:25:08', '2023-02-06 17:25:08'),
(626, 'en', 'automatically_close_comments_on_blogs_older_than', 'Automatically close comments on blogs older than', '2023-02-06 17:25:08', '2023-02-06 17:25:08'),
(627, 'en', 'days', 'days', '2023-02-06 17:25:08', '2023-02-06 17:25:08'),
(628, 'en', 'break_comments_into_pages_with', 'Break comments into pages with', '2023-02-06 17:25:08', '2023-02-06 17:25:08'),
(629, 'en', 'top_level_comments_per_page_and', 'top level comments per page and', '2023-02-06 17:25:08', '2023-02-06 17:25:08'),
(630, 'en', 'comments_should_be_displayed_with_the', 'Comments should be displayed with the', '2023-02-06 17:25:08', '2023-02-06 17:25:08'),
(631, 'en', 'older', 'older', '2023-02-06 17:25:08', '2023-02-06 17:25:08'),
(632, 'en', 'newer', 'newer', '2023-02-06 17:25:08', '2023-02-06 17:25:08'),
(633, 'en', 'comments_at_the_top_of_each_page', 'comments at the top of each page', '2023-02-06 17:25:08', '2023-02-06 17:25:08'),
(634, 'en', 'email_me_whenever', 'Email me whenever', '2023-02-06 17:25:08', '2023-02-06 17:25:08'),
(635, 'en', 'anyone_posts_a_comment', 'Anyone posts a comment', '2023-02-06 17:25:08', '2023-02-06 17:25:08'),
(636, 'en', 'a_comment_is_held_for_moderation', 'A comment is held for moderation', '2023-02-06 17:25:08', '2023-02-06 17:25:08'),
(637, 'en', 'before_a_comment_appears', 'Before a comment appears', '2023-02-06 17:25:08', '2023-02-06 17:25:08'),
(638, 'en', 'comment_must_be_manually_approved', 'Comment must be manually approved', '2023-02-06 17:25:08', '2023-02-06 17:25:08'),
(639, 'en', 'comment_author_must_have_a_previously_approved_comment', 'Comment author must have a previously approved comment', '2023-02-06 17:25:08', '2023-02-06 17:25:08'),
(640, 'en', 'comment_moderation', 'Comment Moderation', '2023-02-06 17:25:08', '2023-02-06 17:25:08'),
(641, 'en', 'hold_a_comment_in_the_queue_if_it_contains', 'Hold a comment in the queue if it contains', '2023-02-06 17:25:08', '2023-02-06 17:25:08'),
(642, 'en', 'or_more_links_a_common_characteristic_of_comment_spam_is_a_large____________________________________number_of_hyperlinks', 'or more links. (A common characteristic of comment spam is a large                                    number of hyperlinks.)', '2023-02-06 17:25:08', '2023-02-06 17:25:08'),
(643, 'en', 'when_a_comment_contains_any_of_these_words_in_its_content_author_name_url________________________________email_ip_address_or_browsers_user_agent_string_it_will_be_held_in_the_', 'When a comment contains any of these words in its content, author name, URL,                                email, IP address, or browser’s user agent string, it will be held in the ', '2023-02-06 17:25:08', '2023-02-06 17:25:08'),
(644, 'en', 'pending_queue', 'pending queue', '2023-02-06 17:25:08', '2023-02-06 17:25:08'),
(645, 'en', 'one_word_or_ip_address_per_line_it_will_match_inside_words_so_press_will_match________________________________wordpress', 'One word or IP address per line. It will match inside words, so “press” will match                                “WordPress”.', '2023-02-06 17:25:08', '2023-02-06 17:25:08'),
(646, 'en', 'disallowed_comment_keys', 'Disallowed Comment Keys', '2023-02-06 17:25:08', '2023-02-06 17:25:08'),
(647, 'en', 'when_a_comment_contains_any_of_these_words_in_its_content_author_name_url________________________________email_ip_address_or_browsers_user_agent_string_it_will_be_put_in_the_trash_one_word_or________________________________ip_address_per_line_it_will_match_inside_words_so_press_will_match_wordpress', 'When a comment contains any of these words in its content, author name, URL,                                email, IP address, or browser’s user agent string, it will be put in the Trash. One word or                                IP address per line. It will match inside words, so “press” will match “WordPress”.', '2023-02-06 17:25:08', '2023-02-06 17:25:08'),
(648, 'en', 'avatars', 'Avatars', '2023-02-06 17:25:08', '2023-02-06 17:25:08'),
(649, 'en', 'an_avatar_is_an_image_that_can_be_associated_with_a_user_across_multiple_websites_in_this_area_you_can_choose_to_display_avatars_of_users_who_interact_with_the_site', 'An avatar is an image that can be associated with a user across multiple websites. In this area, you can choose to display avatars of users who interact with the site.', '2023-02-06 17:25:08', '2023-02-06 17:25:08'),
(650, 'en', 'avatar_display', 'Avatar Display', '2023-02-06 17:25:08', '2023-02-06 17:25:08'),
(651, 'en', 'show_avatars', 'Show Avatars', '2023-02-06 17:25:08', '2023-02-06 17:25:08'),
(652, 'en', 'default_avatar', 'Default Avatar', '2023-02-06 17:25:08', '2023-02-06 17:25:08'),
(653, 'en', 'for_users_without_a_custom_avatar_of_their_own_you_can_either_display_a_generic_logo_or_a_generated_one_based_on_their_email_address', 'For users without a custom avatar of their own, you can either display a generic logo or a generated one based on their email address.', '2023-02-06 17:25:08', '2023-02-06 17:25:08'),
(654, 'en', 'mystery_person', 'Mystery Person', '2023-02-06 17:25:08', '2023-02-06 17:25:08'),
(655, 'en', 'blank', 'Blank', '2023-02-06 17:25:08', '2023-02-06 17:25:08'),
(656, 'en', 'gravatar_logo', 'Gravatar Logo', '2023-02-06 17:25:08', '2023-02-06 17:25:08'),
(657, 'en', 'identicon_generated', 'Identicon (Generated)', '2023-02-06 17:25:08', '2023-02-06 17:25:08'),
(658, 'en', 'wavatar_generated', 'Wavatar (Generated)', '2023-02-06 17:25:08', '2023-02-06 17:25:08'),
(659, 'en', 'monsterid_generated', 'MonsterID (Generated)', '2023-02-06 17:25:08', '2023-02-06 17:25:08'),
(660, 'en', 'retro_generated', 'Retro (Generated)', '2023-02-06 17:25:08', '2023-02-06 17:25:08'),
(661, 'en', 'module_name', 'Module Name', '2023-02-06 18:01:54', '2023-02-06 18:01:54'),
(662, 'en', 'permission_name', 'Permission Name', '2023-02-06 18:01:55', '2023-02-06 18:01:55'),
(663, 'en', 'edit_color', 'Edit Color', '2023-02-06 21:00:25', '2023-02-06 21:00:25'),
(664, 'en', 'color_updated_successfully', 'Color updated successfully', '2023-02-06 21:09:08', '2023-02-06 21:09:08'),
(665, 'en', 'edit_attribute_value', 'Edit Attribute value', '2023-02-06 21:47:27', '2023-02-06 21:47:27'),
(666, 'en', 'edit_attribute', 'Edit Attribute', '2023-02-06 21:47:35', '2023-02-06 21:47:35'),
(667, 'en', 'attribute_updated_successfully', 'Attribute updated successfully', '2023-02-06 21:48:07', '2023-02-06 21:48:07'),
(668, 'en', 'edit_tag', 'Edit Tag', '2023-02-06 21:54:31', '2023-02-06 21:54:31'),
(669, 'en', 'edit_country', 'Edit Country', '2023-02-06 22:02:15', '2023-02-06 22:02:15'),
(670, 'en', 'country_information', 'Country Information', '2023-02-06 22:02:15', '2023-02-06 22:02:15'),
(671, 'en', 'shipping', 'Shipping', '2023-02-07 20:07:45', '2023-02-07 20:07:45'),
(672, 'en', 'create_new_profile', 'Create new profile', '2023-02-07 20:07:51', '2023-02-07 20:07:51'),
(673, 'en', 'no_profile_created_yet', 'No Profile Created yet', '2023-02-07 20:07:51', '2023-02-07 20:07:51'),
(674, 'en', 'shipping_time', 'Shipping Time', '2023-02-07 20:07:51', '2023-02-07 20:07:51'),
(675, 'en', 'create_new_time', 'Create new Time', '2023-02-07 20:07:51', '2023-02-07 20:07:51'),
(676, 'en', 'min_shipping_time', 'Min Shipping Time', '2023-02-07 20:07:51', '2023-02-07 20:07:51'),
(677, 'en', 'max_shipping_time', 'Max Shipping Time', '2023-02-07 20:07:51', '2023-02-07 20:07:51'),
(678, 'en', 'shipping_carriers', 'Shipping Carriers', '2023-02-07 20:07:51', '2023-02-07 20:07:51'),
(679, 'en', 'add_new_shipping_time', 'Add New Shipping Time', '2023-02-07 20:07:51', '2023-02-07 20:07:51'),
(680, 'en', 'minimum_shipping_time', 'Minimum Shipping Time', '2023-02-07 20:07:51', '2023-02-07 20:07:51'),
(681, 'en', 'hours', 'Hours', '2023-02-07 20:07:51', '2023-02-07 20:07:51'),
(682, 'en', 'minutes', 'Minutes', '2023-02-07 20:07:51', '2023-02-07 20:07:51'),
(683, 'en', 'maximum_shipping_time', 'Maximum Shipping Time', '2023-02-07 20:07:51', '2023-02-07 20:07:51'),
(684, 'en', 'add_new_state', 'Add New State', '2023-02-07 20:26:49', '2023-02-07 20:26:49'),
(685, 'en', 'country', 'Country', '2023-02-07 20:26:49', '2023-02-07 20:26:49'),
(686, 'en', 'add_new_city', 'Add New City', '2023-02-07 20:26:50', '2023-02-07 20:26:50'),
(687, 'en', 'state', 'State', '2023-02-07 20:26:50', '2023-02-07 20:26:50'),
(688, 'en', 'edit_product', 'Edit Product', '2023-02-07 20:54:33', '2023-02-07 20:54:33'),
(689, 'en', 'gm', 'gm', '2023-02-07 20:54:39', '2023-02-07 20:54:39'),
(690, 'en', 'cm', 'cm', '2023-02-07 20:54:39', '2023-02-07 20:54:39'),
(691, 'en', 'create_or_manage_taxes', 'Create or manage taxes', '2023-02-07 20:54:39', '2023-02-07 20:54:39'),
(692, 'en', 'update__draft', 'Update & Draft', '2023-02-07 20:54:39', '2023-02-07 20:54:39'),
(693, 'en', 'update__publish', 'Update & Publish', '2023-02-07 20:54:39', '2023-02-07 20:54:39'),
(694, 'en', 'product_update_successfully', 'Product update successfully', '2023-02-07 20:55:57', '2023-02-07 20:55:57'),
(695, 'en', '100_authentic', '100% Authentic', '2023-02-07 21:08:14', '2023-02-07 21:08:14'),
(696, 'en', 'available', 'Available', '2023-02-07 21:08:14', '2023-02-07 21:08:14'),
(697, 'en', 'edit_discount', 'Edit discount', '2023-02-07 21:26:04', '2023-02-07 21:26:04'),
(698, 'en', 'edit_state', 'Edit State', '2023-02-07 21:45:29', '2023-02-07 21:45:29'),
(699, 'en', 'type__here', 'Type  Here', '2023-02-07 21:45:29', '2023-02-07 21:45:29'),
(700, 'en', 'showhide', 'Show/Hide', '2023-02-07 21:45:36', '2023-02-07 21:45:36'),
(701, 'en', 'create_shipping_profile', 'Create Shipping Profile', '2023-02-07 21:46:11', '2023-02-07 21:46:11'),
(702, 'en', 'profile_information', 'Profile Information', '2023-02-07 21:46:11', '2023-02-07 21:46:11'),
(703, 'en', 'profile_name', 'Profile Name', '2023-02-07 21:46:11', '2023-02-07 21:46:11'),
(704, 'en', 'shipping_from', 'Shipping From', '2023-02-07 21:46:11', '2023-02-07 21:46:11'),
(705, 'en', 'location', 'Location', '2023-02-07 21:46:11', '2023-02-07 21:46:11'),
(706, 'en', 'address', 'Address', '2023-02-07 21:46:11', '2023-02-07 21:46:11'),
(707, 'en', 'select_product', 'Select Product', '2023-02-07 21:46:11', '2023-02-07 21:46:11'),
(708, 'en', 'add_currency', 'Add Currency', '2023-02-07 21:48:41', '2023-02-07 21:48:41'),
(709, 'en', 'currency_name', 'Currency Name', '2023-02-07 21:48:41', '2023-02-07 21:48:41'),
(710, 'en', 'currency_symbol', 'Currency Symbol', '2023-02-07 21:48:41', '2023-02-07 21:48:41'),
(711, 'en', 'currency_code_', 'Currency code ', '2023-02-07 21:48:41', '2023-02-07 21:48:41'),
(712, 'en', 'conversion_rate', 'Conversion Rate', '2023-02-07 21:48:41', '2023-02-07 21:48:41'),
(713, 'en', 'edit_currency', 'Edit Currency', '2023-02-07 21:48:51', '2023-02-07 21:48:51'),
(714, 'en', 'symbol', 'Symbol', '2023-02-07 21:48:51', '2023-02-07 21:48:51'),
(715, 'en', 'exchange_rate_with_usd', 'Exchange Rate With USD', '2023-02-07 21:48:51', '2023-02-07 21:48:51'),
(716, 'en', 'currency_position', 'Currency Position', '2023-02-07 21:48:51', '2023-02-07 21:48:51'),
(717, 'en', 'select_currency_position', 'Select currency position', '2023-02-07 21:48:51', '2023-02-07 21:48:51'),
(718, 'en', 'thousand_separator', 'Thousand separator', '2023-02-07 21:48:51', '2023-02-07 21:48:51'),
(719, 'en', 'decimal_separator', 'Decimal separator', '2023-02-07 21:48:51', '2023-02-07 21:48:51'),
(720, 'en', 'number_of_decimals', 'Number of decimals', '2023-02-07 21:48:51', '2023-02-07 21:48:51'),
(721, 'en', 'currency_added_successfully', 'Currency added successfully', '2023-02-07 21:49:35', '2023-02-07 21:49:35'),
(722, 'en', 'checkout', 'Checkout', '2023-02-07 21:49:43', '2023-02-07 21:49:43'),
(723, 'en', 'wallet', 'Wallet', '2023-02-07 21:49:43', '2023-02-07 21:49:43'),
(724, 'en', 'invoice', 'Invoice', '2023-02-07 21:49:43', '2023-02-07 21:49:43'),
(725, 'en', 'defalt_currency', 'Defalt currency', '2023-02-07 21:49:43', '2023-02-07 21:49:43'),
(726, 'en', 'to_create_new_currency_or_manage_existing_currencies', 'To create new currency or manage existing currencies', '2023-02-07 21:49:43', '2023-02-07 21:49:43'),
(727, 'en', 'click_here', 'click here', '2023-02-07 21:49:43', '2023-02-07 21:49:43'),
(728, 'en', 'enable_product_reviews', 'Enable product reviews', '2023-02-07 21:49:43', '2023-02-07 21:49:43'),
(729, 'en', 'enable_star_rating_on_product_reviews', 'Enable star rating on product reviews', '2023-02-07 21:49:43', '2023-02-07 21:49:43'),
(730, 'en', 'star_rating_should_be_required_not_optional', 'Star rating should be required not optional', '2023-02-07 21:49:43', '2023-02-07 21:49:43'),
(731, 'en', 'show_verified_customer_label_on_product_reviews', 'Show Verified customer label on product reviews', '2023-02-07 21:49:43', '2023-02-07 21:49:43'),
(732, 'en', 'reviews_can_only_be_left_by_verified_customer', 'Reviews can only be left by verified customer', '2023-02-07 21:49:43', '2023-02-07 21:49:43'),
(733, 'en', 'enable_product_compare', 'Enable product compare', '2023-02-07 21:49:43', '2023-02-07 21:49:43'),
(734, 'en', 'enable_product_discount', 'Enable Product Discount', '2023-02-07 21:49:43', '2023-02-07 21:49:43'),
(735, 'en', 'display_product_perpage', 'Display product perpage', '2023-02-07 21:49:43', '2023-02-07 21:49:43'),
(736, 'en', 'enable_billing_address', 'Enable billing address', '2023-02-07 21:49:43', '2023-02-07 21:49:43'),
(737, 'en', 'use_the_shipping_address_as_the_billing_address_by_default', 'Use the shipping address as the billing address by default', '2023-02-07 21:49:43', '2023-02-07 21:49:43'),
(738, 'en', 'enable_guest_checkout', 'Enable guest checkout', '2023-02-07 21:49:43', '2023-02-07 21:49:43'),
(739, 'en', 'create_account_in_guest_checkout', 'Create account in guest checkout', '2023-02-07 21:49:43', '2023-02-07 21:49:43'),
(740, 'en', 'send_invoice_to_customer_email', 'Send invoice to customer email', '2023-02-07 21:49:43', '2023-02-07 21:49:43'),
(741, 'en', 'enable_tax_in_checkout', 'Enable tax in checkout', '2023-02-07 21:49:43', '2023-02-07 21:49:43'),
(742, 'en', 'enable_coupon_in_checkout', 'Enable coupon in checkout', '2023-02-07 21:49:43', '2023-02-07 21:49:43'),
(743, 'en', 'create_or_manage_your', 'Create or manage your', '2023-02-07 21:49:43', '2023-02-07 21:49:43'),
(744, 'en', 'enable_multiple_coupon_in_single_order', 'Enable multiple coupon in single order', '2023-02-07 21:49:43', '2023-02-07 21:49:43'),
(745, 'en', 'enable_minimum_order_amount', 'Enable minimum order amount', '2023-02-07 21:49:43', '2023-02-07 21:49:43'),
(746, 'en', 'minimum_order_amount', 'Minimum order amount', '2023-02-07 21:49:43', '2023-02-07 21:49:43'),
(747, 'en', 'enable_wallet_in_checkout', 'Enable wallet in checkout', '2023-02-07 21:49:43', '2023-02-07 21:49:43'),
(748, 'en', 'to_enable_wallet_you_need_to_active', 'To enable wallet you need to active', '2023-02-07 21:49:43', '2023-02-07 21:49:43'),
(749, 'en', 'enable_order_note', 'Enable order note', '2023-02-07 21:49:43', '2023-02-07 21:49:43'),
(750, 'en', 'enable_document_in_checkout', 'Enable document in checkout', '2023-02-07 21:49:43', '2023-02-07 21:49:43'),
(751, 'en', 'enable_carrier_in_checkout', 'Enable carrier in checkout', '2023-02-07 21:49:43', '2023-02-07 21:49:43'),
(752, 'en', 'manage_your', 'Manage your', '2023-02-07 21:49:43', '2023-02-07 21:49:43'),
(753, 'en', 'enable_pickup_point_in_checkout', 'Enable pickup point in checkout', '2023-02-07 21:49:43', '2023-02-07 21:49:43'),
(754, 'en', 'customer_auto_approval', 'Customer auto approval', '2023-02-07 21:49:43', '2023-02-07 21:49:43'),
(755, 'en', 'customer_email_verification', 'Customer email verification', '2023-02-07 21:49:43', '2023-02-07 21:49:43'),
(756, 'en', 'order_code_prefix', 'Order code prefix', '2023-02-07 21:49:43', '2023-02-07 21:49:43'),
(757, 'en', 'enter_prefix', 'Enter prefix', '2023-02-07 21:49:44', '2023-02-07 21:49:44'),
(758, 'en', 'order_code_prefix_seperator', 'Order code prefix seperator', '2023-02-07 21:49:44', '2023-02-07 21:49:44'),
(759, 'en', 'enter_prefix_seperator', 'Enter prefix seperator', '2023-02-07 21:49:44', '2023-02-07 21:49:44'),
(760, 'en', 'can_cancel_order_within', 'Can cancel order within', '2023-02-07 21:49:44', '2023-02-07 21:49:44'),
(761, 'en', 'can_return_order_within', 'Can return order within', '2023-02-07 21:49:44', '2023-02-07 21:49:44'),
(762, 'en', 'you_can_manage_payment_methods', 'You can manage payment methods', '2023-02-07 21:49:44', '2023-02-07 21:49:44'),
(763, 'en', 'form_here', 'form here', '2023-02-07 21:49:44', '2023-02-07 21:49:44'),
(764, 'en', 'you_need_to_active_or_install', 'You need to active or install', '2023-02-07 21:49:44', '2023-02-07 21:49:44'),
(765, 'en', 'to_manage_wallets', 'to manage wallets', '2023-02-07 21:49:44', '2023-02-07 21:49:44'),
(766, 'en', 'enable_online_recharge', 'Enable online recharge', '2023-02-07 21:49:44', '2023-02-07 21:49:44'),
(767, 'en', 'enable_offline_recharge', 'Enable offline recharge', '2023-02-07 21:49:44', '2023-02-07 21:49:44'),
(768, 'en', 'minimum_recharge_amount', 'Minimum recharge amount', '2023-02-07 21:49:44', '2023-02-07 21:49:44'),
(769, 'en', 'business_email', 'Business Email', '2023-02-07 21:49:44', '2023-02-07 21:49:44'),
(770, 'en', 'business_phone', 'Business Phone', '2023-02-07 21:49:44', '2023-02-07 21:49:44'),
(771, 'en', 'business_address', 'Business Address', '2023-02-07 21:49:44', '2023-02-07 21:49:44'),
(772, 'en', 'credential_updated_successfully', 'Credential updated successfully', '2023-02-07 21:49:44', '2023-02-07 21:49:44'),
(773, 'en', 'update_failed', 'Update Failed', '2023-02-07 21:49:44', '2023-02-07 21:49:44'),
(774, 'en', 'please_insert_blog_name', 'Please Insert Blog Name', '2023-02-07 22:44:10', '2023-02-07 22:44:10'),
(775, 'en', 'please_write_the_blog_name_under_225_words', 'Please Write The Blog Name under 225 words', '2023-02-07 22:44:10', '2023-02-07 22:44:10'),
(776, 'en', 'please_select_at_least_1_category', 'Please Select at least 1 Category', '2023-02-07 22:44:10', '2023-02-07 22:44:10'),
(777, 'en', 'something_went_wrong_please_select_category_again', 'Something went Wrong, Please Select Category Again', '2023-02-07 22:44:10', '2023-02-07 22:44:10'),
(778, 'en', 'please_insert_a_valid_image', 'Please Insert A Valid Image', '2023-02-07 22:44:10', '2023-02-07 22:44:10'),
(779, 'en', 'please_write_some_description', 'Please Write Some Description', '2023-02-07 22:44:10', '2023-02-07 22:44:10'),
(780, 'en', 'please_write_some_content', 'Please write some Content', '2023-02-07 22:44:10', '2023-02-07 22:44:10'),
(781, 'en', 'please_select_a_valid_image', 'Please Select a Valid Image', '2023-02-07 22:44:10', '2023-02-07 22:44:10'),
(782, 'en', 'something_went_wrong_please_select_visibility_again', 'Something went Wrong, Please Select Visibility Again', '2023-02-07 22:44:10', '2023-02-07 22:44:10'),
(783, 'en', 'new_blog_saved', 'New Blog Saved', '2023-02-07 22:44:10', '2023-02-07 22:44:10'),
(784, 'en', 'tl_commerce__edit_blog', 'TL Commerce | Edit Blog', '2023-02-07 22:44:42', '2023-02-07 22:44:42'),
(785, 'en', 'edit_blog', 'Edit Blog', '2023-02-07 22:44:42', '2023-02-07 22:44:42'),
(786, 'en', 'add_new', 'Add New', '2023-02-07 22:44:42', '2023-02-07 22:44:42'),
(787, 'en', 'blog_updated_successfully', 'Blog Updated Successfully', '2023-02-07 22:47:19', '2023-02-07 22:47:19'),
(788, 'en', 'blog_deleted_successfully', 'Blog Deleted Successfully', '2023-02-08 15:28:23', '2023-02-08 15:28:23'),
(789, 'en', 'profile_pic_is_required', 'Profile pic is required', '2023-02-08 15:43:04', '2023-02-08 15:43:04'),
(790, 'en', 'invalid_selection', 'Invalid selection', '2023-02-08 15:43:04', '2023-02-08 15:43:04'),
(791, 'en', 'profile_updated_successfully', 'Profile updated successfully', '2023-02-08 15:43:36', '2023-02-08 15:43:36'),
(792, 'en', 'product_deleted_successfully', 'Product deleted successfully', '2023-02-08 17:06:59', '2023-02-08 17:06:59'),
(793, 'en', 'button', 'Button', '2023-02-08 17:25:14', '2023-02-08 17:25:14'),
(794, 'en', 'select_option', 'Select Option', '2023-02-08 17:25:14', '2023-02-08 17:25:14'),
(795, 'en', 'latest_blogs', 'Latest Blogs', '2023-02-08 17:25:14', '2023-02-08 17:25:14'),
(796, 'en', 'featured_blogs', 'Featured Blogs', '2023-02-08 17:25:14', '2023-02-08 17:25:14'),
(797, 'en', 'category_wise', 'Category wise', '2023-02-08 17:25:14', '2023-02-08 17:25:14'),
(798, 'en', 'select_category', 'Select Category', '2023-02-08 17:25:14', '2023-02-08 17:25:14'),
(799, 'en', 'number_of_blogs', 'Number of Blogs', '2023-02-08 17:25:14', '2023-02-08 17:25:14'),
(800, 'en', 'title_is_visible_in_homepage_transalate_to_another_language', 'Title is visible in homepage. Transalate to another language', '2023-02-08 17:25:14', '2023-02-08 17:25:14'),
(801, 'en', 'title_color', 'Title Color', '2023-02-08 17:25:14', '2023-02-08 17:25:14'),
(802, 'en', 'button_title', 'Button Title', '2023-02-08 17:25:14', '2023-02-08 17:25:14'),
(803, 'en', 'button_title_is_visible_in_homepage_transalate_to_another_language', 'Button title is visible in homepage. Transalate to another language', '2023-02-08 17:25:14', '2023-02-08 17:25:14'),
(804, 'en', 'button_color', 'Button Color', '2023-02-08 17:25:14', '2023-02-08 17:25:14'),
(805, 'en', 'button_hover_color', 'Button Hover Color', '2023-02-08 17:25:14', '2023-02-08 17:25:14'),
(806, 'en', 'button_background_color', 'Button Background Color', '2023-02-08 17:25:14', '2023-02-08 17:25:14'),
(807, 'en', 'button_background_hover_color', 'Button Background Hover Color', '2023-02-08 17:25:14', '2023-02-08 17:25:14'),
(808, 'en', 'button_border', 'Button Border', '2023-02-08 17:25:14', '2023-02-08 17:25:14'),
(809, 'en', 'button_border_color', 'Button Border Color', '2023-02-08 17:25:14', '2023-02-08 17:25:14'),
(810, 'en', 'button_border_hover_color', 'Button Border Hover Color', '2023-02-08 17:25:14', '2023-02-08 17:25:14'),
(811, 'en', 'new_collection', 'New Collection', '2023-02-08 17:44:02', '2023-02-08 17:44:02'),
(812, 'en', 'invalid_image', 'Invalid image', '2023-02-08 17:44:16', '2023-02-08 17:44:16'),
(813, 'en', 'collection_added_successfully', 'Collection added successfully', '2023-02-08 17:44:16', '2023-02-08 17:44:16'),
(814, 'en', 'remove_selection', 'Remove selection', '2023-02-08 17:44:16', '2023-02-08 17:44:16'),
(815, 'en', 'add_product', 'Add Product', '2023-02-08 17:44:16', '2023-02-08 17:44:16'),
(816, 'en', 'collection', 'Collection', '2023-02-08 17:44:16', '2023-02-08 17:44:16'),
(817, 'en', 'select_products', 'Select Products', '2023-02-08 17:44:16', '2023-02-08 17:44:16'),
(818, 'en', 'are_you_sure_to_remove_this_product', 'Are you sure to remove this product', '2023-02-08 17:44:16', '2023-02-08 17:44:16'),
(819, 'en', 'no_product_selected', 'No Product Selected', '2023-02-08 17:44:16', '2023-02-08 17:44:16'),
(820, 'en', 'products_added_successfully', 'Products added successfully', '2023-02-08 17:44:23', '2023-02-08 17:44:23'),
(821, 'en', 'remove_product', 'Remove Product', '2023-02-08 17:44:23', '2023-02-08 17:44:23'),
(822, 'en', 'custom_single_blog_page_style', 'Custom Single Blog Page Style', '2023-02-08 17:54:24', '2023-02-08 17:54:24'),
(823, 'en', 'switch_on_for_custom_single_blog_page_style', 'Switch on for custom single blog page style.', '2023-02-08 17:54:24', '2023-02-08 17:54:24'),
(824, 'en', 'layout', 'Layout', '2023-02-08 17:54:24', '2023-02-08 17:54:24'),
(825, 'en', 'choose_blog_single_page_layout_from_here_if_you_use_this_option_then_you_will_able_to_change_three_type_of_blog_single_page_layout__default_right_sidebar_layout_', 'Choose blog single page layout from here. If you use this option then you will able to change three type of blog single page layout ( Default Right Sidebar Layout ).', '2023-02-08 17:54:24', '2023-02-08 17:54:24'),
(826, 'en', 'blog_post_title_position', 'Blog Post Title Position', '2023-02-08 17:54:24', '2023-02-08 17:54:24'),
(827, 'en', 'control_blog_post_title_position_from_here', 'Control blog post title position from here.', '2023-02-08 17:54:24', '2023-02-08 17:54:24'),
(828, 'en', 'switch_on_to_display_author', 'Switch On to Display Author.', '2023-02-08 17:54:24', '2023-02-08 17:54:24'),
(829, 'en', 'switch_on_to_display_date', 'Switch On to Display Date.', '2023-02-08 17:54:24', '2023-02-08 17:54:24'),
(830, 'en', 'theme_option_saved', 'Theme Option Saved', '2023-02-08 17:54:31', '2023-02-08 17:54:31'),
(831, 'en', 'tl_commerce__manage_widgets', 'TL Commerce | Manage Widgets', '2023-02-08 18:02:06', '2023-02-08 18:02:06'),
(832, 'en', 'available_widgets', 'Available Widgets', '2023-02-08 18:02:06', '2023-02-08 18:02:06'),
(833, 'en', 'add_widget', 'Add Widget', '2023-02-08 18:02:06', '2023-02-08 18:02:06'),
(834, 'en', 'adding_widget_to_sidebar_failed', 'Adding Widget To Sidebar Failed', '2023-02-08 18:02:06', '2023-02-08 18:02:06'),
(835, 'en', 'sidebar_widget_opening_failed', 'Sidebar Widget Opening Failed', '2023-02-08 18:02:06', '2023-02-08 18:02:06'),
(836, 'en', 'widget_added_to_sidebar_failed', 'Widget Added To Sidebar Failed', '2023-02-08 18:02:06', '2023-02-08 18:02:06'),
(837, 'en', 'widget_form_submit_failed_failed', 'Widget Form Submit Failed Failed', '2023-02-08 18:02:06', '2023-02-08 18:02:06'),
(838, 'en', 'sidebar_updated', 'Sidebar Updated', '2023-02-08 18:02:15', '2023-02-08 18:02:15'),
(839, 'en', 'widget_title', 'Widget Title', '2023-02-08 18:02:19', '2023-02-08 18:02:19'),
(840, 'en', 'number_of_recent_blog', 'Number of Recent Blog', '2023-02-08 18:02:19', '2023-02-08 18:02:19'),
(841, 'en', 'done', 'Done', '2023-02-08 18:02:19', '2023-02-08 18:02:19'),
(842, 'en', 'widget_form_saved', 'Widget Form Saved', '2023-02-08 18:02:33', '2023-02-08 18:02:33'),
(843, 'en', 'number_of_featured_blog', 'Number of Featured Blog', '2023-02-08 18:02:37', '2023-02-08 18:02:37'),
(844, 'en', 'blog_featured_status_changed_successfully', 'Blog Featured Status Changed Successfully', '2023-02-08 18:02:47', '2023-02-08 18:02:47'),
(845, 'en', 'blog_draft_saved', 'Blog Draft Saved', '2023-02-08 18:06:28', '2023-02-08 18:06:28'),
(846, 'en', 'tl_commerce__tag', 'TL Commerce | Tag', '2023-02-08 21:23:04', '2023-02-08 21:23:04'),
(847, 'en', 'add_tag', 'Add Tag', '2023-02-08 21:23:05', '2023-02-08 21:23:05'),
(848, 'en', 'this_tag_name_or_slug_is_already_available_please_insert_another', 'This Tag Name or Slug is Already Available Please Insert Another', '2023-02-08 21:31:45', '2023-02-08 21:31:45'),
(849, 'en', 'site_seo__settings', 'Site Seo  Settings', '2023-02-08 21:55:48', '2023-02-08 21:55:48'),
(850, 'en', 'site_title', 'Site title', '2023-02-08 21:55:48', '2023-02-08 21:55:48'),
(851, 'en', 'meta_keywords', 'Meta keywords', '2023-02-08 21:55:48', '2023-02-08 21:55:48'),
(852, 'en', 'seo_update_successfully', 'Seo update successfully', '2023-02-08 21:57:26', '2023-02-08 21:57:26'),
(853, 'en', 'no', 'No.', '2023-02-08 21:58:51', '2023-02-08 21:58:51'),
(854, 'en', 'template', 'Template', '2023-02-08 21:58:51', '2023-02-08 21:58:51'),
(855, 'en', 'details', 'Details', '2023-02-08 21:58:51', '2023-02-08 21:58:51'),
(856, 'en', 'enter_email_subject', 'Enter email subject', '2023-02-08 21:58:51', '2023-02-08 21:58:51'),
(857, 'en', 'variables', 'Variables', '2023-02-08 21:58:51', '2023-02-08 21:58:51'),
(858, 'en', 'smtp_configuration', 'Smtp Configuration', '2023-02-08 22:01:58', '2023-02-08 22:01:58'),
(859, 'en', 'email_configuration', 'Email Configuration', '2023-02-08 22:01:58', '2023-02-08 22:01:58'),
(860, 'en', 'type', 'Type', '2023-02-08 22:01:58', '2023-02-08 22:01:58'),
(861, 'en', 'smtp', 'smtp', '2023-02-08 22:01:58', '2023-02-08 22:01:58'),
(862, 'en', 'sendmail', 'Sendmail', '2023-02-08 22:01:58', '2023-02-08 22:01:58'),
(863, 'en', 'mailgun', 'Mailgun', '2023-02-08 22:01:58', '2023-02-08 22:01:58'),
(864, 'en', 'mail_host', 'MAIL HOST', '2023-02-08 22:01:58', '2023-02-08 22:01:58'),
(865, 'en', 'mail_port', 'MAIL PORT', '2023-02-08 22:01:58', '2023-02-08 22:01:58'),
(866, 'en', 'mail_username', 'MAIL USERNAME', '2023-02-08 22:01:58', '2023-02-08 22:01:58'),
(867, 'en', 'mail_password', 'MAIL PASSWORD', '2023-02-08 22:01:58', '2023-02-08 22:01:58'),
(868, 'en', 'mail_encryption', 'MAIL ENCRYPTION', '2023-02-08 22:01:58', '2023-02-08 22:01:58'),
(869, 'en', 'mail_from_address', 'MAIL FROM ADDRESS', '2023-02-08 22:01:58', '2023-02-08 22:01:58'),
(870, 'en', 'mail_from_name', 'MAIL FROM NAME', '2023-02-08 22:01:58', '2023-02-08 22:01:58'),
(871, 'en', 'mailgun_domain', 'MAILGUN DOMAIN', '2023-02-08 22:01:58', '2023-02-08 22:01:58'),
(872, 'en', 'mailgun_secret', 'MAILGUN SECRET', '2023-02-08 22:01:58', '2023-02-08 22:01:58'),
(873, 'en', 'send_test_mail', 'Send Test Mail', '2023-02-08 22:01:58', '2023-02-08 22:01:58'),
(874, 'en', 'subject', 'Subject', '2023-02-08 22:01:58', '2023-02-08 22:01:58'),
(875, 'en', 'message', 'Message', '2023-02-08 22:01:58', '2023-02-08 22:01:58'),
(876, 'en', 'send', 'Send', '2023-02-08 22:01:58', '2023-02-08 22:01:58'),
(877, 'en', 'users_login_activity', 'Users login activity', '2023-02-08 22:02:22', '2023-02-08 22:02:22'),
(878, 'en', 'user', 'User', '2023-02-08 22:02:22', '2023-02-08 22:02:22'),
(879, 'en', 'login_at', 'Login At', '2023-02-08 22:02:22', '2023-02-08 22:02:22'),
(880, 'en', 'logout_at', 'Logout At', '2023-02-08 22:02:22', '2023-02-08 22:02:22'),
(881, 'en', 'ip', 'IP', '2023-02-08 22:02:22', '2023-02-08 22:02:22'),
(882, 'en', 'operating_system', 'Operating System', '2023-02-08 22:02:22', '2023-02-08 22:02:22'),
(883, 'en', 'browser', 'Browser', '2023-02-08 22:02:22', '2023-02-08 22:02:22'),
(884, 'en', '________________________bulk_action_', '                        Bulk Action ', '2023-02-08 22:02:22', '2023-02-08 22:02:22'),
(885, 'en', '________________________delete_selection_', '                        Delete selection ', '2023-02-08 22:02:22', '2023-02-08 22:02:22'),
(886, 'en', '________________________apply_', '                        Apply ', '2023-02-08 22:02:22', '2023-02-08 22:02:22'),
(887, 'en', '________________________________________no_item_selected_', '                                        No Item Selected ', '2023-02-08 22:02:22', '2023-02-08 22:02:22'),
(888, 'en', '________________________________no_action_selected_', '                                No Action Selected ', '2023-02-08 22:02:22', '2023-02-08 22:02:22'),
(889, 'en', 'currency_updated_successfully', 'Currency updated successfully', '2023-02-09 01:44:24', '2023-02-09 01:44:24'),
(890, 'en', 'brand_featured_status_updated_successfully', 'Brand featured status updated successfully', '2023-02-09 16:22:10', '2023-02-09 16:22:10'),
(891, 'en', 'brand_featured_status_update_failed', 'Brand featured status update failed', '2023-02-09 16:22:10', '2023-02-09 16:22:10'),
(892, 'en', 'brand_status_updated_successfully', 'Brand status updated successfully', '2023-02-09 16:22:10', '2023-02-09 16:22:10'),
(893, 'en', 'brand_status_update_failed', 'Brand status update failed', '2023-02-09 16:22:10', '2023-02-09 16:22:10'),
(894, 'en', 'easy_return_available', 'easy return available', '2023-02-09 17:24:11', '2023-02-09 17:24:11'),
(895, 'en', 'edit_collection', 'Edit Collection', '2023-02-09 21:02:45', '2023-02-09 21:02:45'),
(896, 'en', 'product_remove_successfully', 'Product remove successfully', '2023-02-09 21:03:08', '2023-02-09 21:03:08'),
(897, 'en', 'select_collection', 'Select Collection', '2023-02-09 21:04:15', '2023-02-09 21:04:15'),
(898, 'en', 'collection_updated_successfully', 'Collection updated successfully', '2023-02-09 21:06:04', '2023-02-09 21:06:04'),
(899, 'en', 'successfully_rearranging', 'Successfully rearranging', '2023-02-09 21:07:06', '2023-02-09 21:07:06'),
(900, 'en', 'new_arrival', 'New Arrival', '2023-02-09 21:09:36', '2023-02-09 21:09:36'),
(901, 'en', 'featured_products', 'Featured Products', '2023-02-09 21:09:36', '2023-02-09 21:09:36'),
(902, 'en', 'top_selling', 'Top Selling', '2023-02-09 21:09:36', '2023-02-09 21:09:36'),
(903, 'en', 'top_reviewed', 'Top Reviewed', '2023-02-09 21:09:36', '2023-02-09 21:09:36'),
(904, 'en', 'select_layouts', 'Select Layouts', '2023-02-09 21:11:48', '2023-02-09 21:11:48'),
(905, 'en', 'add_new_flash_deal', 'Add New Flash Deal', '2023-02-09 21:25:41', '2023-02-09 21:25:41'),
(906, 'en', 'start_date', 'Start Date', '2023-02-09 21:25:41', '2023-02-09 21:25:41'),
(907, 'en', 'expiry_date', 'Expiry date', '2023-02-09 21:25:41', '2023-02-09 21:25:41'),
(908, 'en', 'new_deal', 'New Deal', '2023-02-09 21:25:46', '2023-02-09 21:25:46'),
(909, 'en', 'text_color', 'Text Color', '2023-02-09 21:25:46', '2023-02-09 21:25:46'),
(910, 'en', 'banner', 'Banner', '2023-02-09 21:25:46', '2023-02-09 21:25:46'),
(911, 'en', 'deal_title_is_required', 'Deal title is required', '2023-02-09 21:30:18', '2023-02-09 21:30:18'),
(912, 'en', 'permalink_is_already_taken', 'Permalink is already taken', '2023-02-09 21:30:18', '2023-02-09 21:30:18'),
(913, 'en', 'new_deal_added_successfully', 'New deal added successfully', '2023-02-09 21:30:18', '2023-02-09 21:30:18'),
(914, 'en', 'deals_products', 'Deals Products', '2023-02-09 21:30:18', '2023-02-09 21:30:18'),
(915, 'en', 'to', 'to', '2023-02-09 21:30:18', '2023-02-09 21:30:18'),
(916, 'en', 'discount_type', 'Discount Type', '2023-02-09 21:30:18', '2023-02-09 21:30:18'),
(917, 'en', 'update_product_discount', 'Update Product Discount', '2023-02-09 21:30:18', '2023-02-09 21:30:18'),
(918, 'en', 'product_details', 'Product Details', '2023-02-09 21:30:57', '2023-02-09 21:30:57'),
(919, 'en', 'select_flash_deal', 'Select Flash Deal', '2023-02-09 21:31:07', '2023-02-09 21:31:07'),
(920, 'en', 'featured_product_image', 'Featured Product Image', '2023-02-09 21:38:15', '2023-02-09 21:38:15'),
(921, 'en', 'video_url', 'Video url', '2023-02-09 21:38:15', '2023-02-09 21:38:15'),
(922, 'en', 'meta_title_is_visible_in_homepage_transalate_to_another_language', 'Meta title is visible in homepage. Transalate to another language', '2023-02-09 21:38:15', '2023-02-09 21:38:15'),
(923, 'en', 'paragraph', 'Paragraph', '2023-02-09 21:38:15', '2023-02-09 21:38:15'),
(924, 'en', 'paragraph_is_visible_in_homepage_transalate_to_another_language', 'Paragraph is visible in homepage. Transalate to another language', '2023-02-09 21:38:15', '2023-02-09 21:38:15'),
(925, 'en', 'play_button_color', 'Play Button Color', '2023-02-09 21:38:15', '2023-02-09 21:38:15'),
(926, 'en', 'play_button_border_color', 'Play Button Border Color', '2023-02-09 21:38:15', '2023-02-09 21:38:15'),
(927, 'en', 'password_is_required', 'Password is required', '2023-02-09 21:42:52', '2023-02-09 21:42:52'),
(928, 'en', 'password_does_not_match', 'Password does not match', '2023-02-09 21:42:52', '2023-02-09 21:42:52'),
(929, 'en', 'phone_is_required', 'Phone is required', '2023-02-09 21:42:52', '2023-02-09 21:42:52'),
(930, 'en', 'phone_is_already_used', 'Phone is already used', '2023-02-09 21:42:52', '2023-02-09 21:42:52'),
(931, 'en', 'email_is_required', 'Email is required', '2023-02-09 21:42:52', '2023-02-09 21:42:52'),
(932, 'en', 'incorrect_email', 'Incorrect email', '2023-02-09 21:42:52', '2023-02-09 21:42:52'),
(933, 'en', 'email_is_already_used', 'Email is already used', '2023-02-09 21:42:52', '2023-02-09 21:42:52'),
(934, 'en', 'secret_login', 'Secret Login', '2023-02-09 21:45:17', '2023-02-09 21:45:17'),
(935, 'en', 'delete_customer', 'Delete Customer', '2023-02-09 21:45:17', '2023-02-09 21:45:17'),
(936, 'en', 'status_updated_successfully', 'Status updated successfully', '2023-02-09 21:45:22', '2023-02-09 21:45:22'),
(937, 'en', 'no_account_exists_with_this_email', 'No account exists with this email', '2023-02-09 21:46:17', '2023-02-09 21:46:17'),
(938, 'en', 'image_size_is_too_large', 'Image size is too large', '2023-02-09 21:47:43', '2023-02-09 21:47:43'),
(939, 'en', 'invalid_image_format', 'Invalid image format', '2023-02-09 21:47:43', '2023-02-09 21:47:43'),
(940, 'en', 'address_is_required', 'Address is required', '2023-02-09 21:51:23', '2023-02-09 21:51:23'),
(941, 'en', 'country_is_required', 'Country is required', '2023-02-09 21:51:23', '2023-02-09 21:51:23'),
(942, 'en', 'state_is_required', 'State is required', '2023-02-09 21:51:23', '2023-02-09 21:51:23'),
(943, 'en', 'city_is_required', 'City is required', '2023-02-09 21:51:23', '2023-02-09 21:51:23'),
(944, 'en', 'country_is_invalid', 'Country is invalid', '2023-02-09 21:51:23', '2023-02-09 21:51:23'),
(945, 'en', 'state_is_invalid', 'State is invalid', '2023-02-09 21:51:23', '2023-02-09 21:51:23'),
(946, 'en', 'city_is_invalid', 'City is invalid', '2023-02-09 21:51:23', '2023-02-09 21:51:23'),
(947, 'en', 'postal_code_is_required', 'Postal code is required', '2023-02-09 21:51:23', '2023-02-09 21:51:23'),
(948, 'en', 'products_removed_successfully', 'Products removed successfully', '2023-02-09 22:18:35', '2023-02-09 22:18:35'),
(949, 'en', 'edit_deal', 'Edit Deal', '2023-02-09 22:44:33', '2023-02-09 22:44:33'),
(950, 'en', 'deal_updated_successfully', 'Deal updated successfully', '2023-02-09 22:44:56', '2023-02-09 22:44:56'),
(951, 'en', 'attribute_deleted_failed', 'Attribute deleted failed', '2023-02-11 14:43:16', '2023-02-11 14:43:16'),
(952, 'en', 'attribute_value_delete_successfully', 'Attribute value delete successfully', '2023-02-11 14:43:36', '2023-02-11 14:43:36'),
(953, 'en', 'attribute_deleted_successfully', 'Attribute deleted successfully', '2023-02-11 14:44:02', '2023-02-11 14:44:02'),
(954, 'en', 'category_featured_status_updated_successfully', 'Category featured status updated successfully', '2023-02-11 16:36:44', '2023-02-11 16:36:44'),
(955, 'en', 'category_featured_status_update_failed', 'Category featured status update failed', '2023-02-11 16:36:45', '2023-02-11 16:36:45'),
(956, 'en', 'category_status_update_failed', 'Category status update failed', '2023-02-11 16:36:45', '2023-02-11 16:36:45'),
(957, 'en', 'share_options', 'Share Options', '2023-02-11 19:48:42', '2023-02-11 19:48:42'),
(958, 'en', 'tl_commerce__edit_blog_category', 'TL Commerce | Edit Blog Category', '2023-02-11 20:48:49', '2023-02-11 20:48:49'),
(959, 'en', 'edit_blog_category', 'Edit Blog Category', '2023-02-11 20:48:49', '2023-02-11 20:48:49'),
(960, 'en', 'blog_category_updated_successfully', 'Blog Category Updated Successfully', '2023-02-11 20:49:20', '2023-02-11 20:49:20'),
(961, 'en', 'something_went_wrong', 'Something Went Wrong', '2023-02-11 20:54:39', '2023-02-11 20:54:39'),
(962, 'en', 'tl_commerce__edit_tag', 'TL Commerce | Edit Tag', '2023-02-11 21:04:53', '2023-02-11 21:04:53'),
(963, 'en', 'please_write_the_tag_name_under_225_words', 'Please Write The Tag Name under 225 words', '2023-02-11 21:05:08', '2023-02-11 21:05:08'),
(964, 'en', 'tag_updated_successfully', 'Tag Updated Successfully', '2023-02-11 21:05:08', '2023-02-11 21:05:08'),
(965, 'en', 'tag_not_found', 'Tag not found', '2023-02-11 21:10:53', '2023-02-11 21:10:53'),
(966, 'en', 'mail', 'Mail', '2023-02-11 21:12:56', '2023-02-11 21:12:56'),
(967, 'en', 'social_links_', 'Social Links: ', '2023-02-11 21:12:56', '2023-02-11 21:12:56'),
(968, 'en', 'set_social_links_from_theme_options', 'Set Social Links From Theme Options', '2023-02-11 21:12:56', '2023-02-11 21:12:56'),
(969, 'en', 'widget_input_fields_saving_failed', 'Widget Input Fields Saving Failed', '2023-02-11 21:13:09', '2023-02-11 21:13:09'),
(970, 'en', 'select_menu_group', 'Select Menu Group', '2023-02-11 21:58:04', '2023-02-11 21:58:04'),
(971, 'en', 'newsletter_short_desc', 'Newsletter Short Desc', '2023-02-11 22:02:43', '2023-02-11 22:02:43'),
(972, 'en', 'widget_removed_from_sidebar', 'Widget Removed From Sidebar', '2023-02-11 22:13:40', '2023-02-11 22:13:40'),
(973, 'en', 'tl_commerce__add_tag', 'TL Commerce | Add Tag', '2023-02-11 22:32:17', '2023-02-11 22:32:17'),
(974, 'en', 'tl_commerce__blog_comment', 'TL Commerce | Blog Comment', '2023-02-11 22:32:24', '2023-02-11 22:32:24'),
(975, 'en', 'approve', 'Approve', '2023-02-11 22:32:24', '2023-02-11 22:32:24'),
(976, 'en', 'spam', 'Spam', '2023-02-11 22:32:24', '2023-02-11 22:32:24'),
(977, 'en', 'trash', 'Trash', '2023-02-11 22:32:24', '2023-02-11 22:32:24'),
(978, 'en', 'in_response_to', 'In Response to', '2023-02-11 22:32:24', '2023-02-11 22:32:24'),
(979, 'en', 'submitted_on', 'Submitted on', '2023-02-11 22:32:24', '2023-02-11 22:32:24'),
(980, 'en', 'comment_delete_confirmation', 'Comment Delete Confirmation', '2023-02-11 22:32:24', '2023-02-11 22:32:24'),
(981, 'en', 'are_you_sure_you_want_to_permanently_delete_this_comment', 'Are you sure you want to Permanently Delete This Comment', '2023-02-11 22:32:24', '2023-02-11 22:32:24'),
(982, 'en', 'bulk_action_confirmation', 'Bulk Action Confirmation', '2023-02-11 22:32:24', '2023-02-11 22:32:24');
INSERT INTO `tl_translations` (`id`, `lang`, `lang_key`, `lang_value`, `created_at`, `updated_at`) VALUES
(983, 'en', 'are_you_sure_you_want_to_take_this_action', 'Are you sure you want to take this Action', '2023-02-11 22:32:24', '2023-02-11 22:32:24'),
(984, 'en', 'comment_reply', 'Comment Reply', '2023-02-11 22:32:24', '2023-02-11 22:32:24'),
(985, 'en', 'reply', 'Reply', '2023-02-11 22:32:24', '2023-02-11 22:32:24'),
(986, 'en', 'mark_as_spam', 'Mark as Spam', '2023-02-11 22:32:24', '2023-02-11 22:32:24'),
(987, 'en', 'unapprove', 'Unapprove', '2023-02-11 22:32:24', '2023-02-11 22:32:24'),
(988, 'en', 'not_spam', 'Not Spam', '2023-02-11 22:32:24', '2023-02-11 22:32:24'),
(989, 'en', 'delete_permanetly', 'Delete Permanetly', '2023-02-11 22:32:24', '2023-02-11 22:32:24'),
(990, 'en', 'restore', 'Restore', '2023-02-11 22:32:24', '2023-02-11 22:32:24'),
(991, 'en', 'delete_all', 'Delete All', '2023-02-11 22:32:24', '2023-02-11 22:32:24'),
(992, 'en', 'tl_commerce__page', 'TL Commerce | Page', '2023-02-11 22:32:31', '2023-02-11 22:32:31'),
(993, 'en', 'add_page', 'Add Page', '2023-02-11 22:32:31', '2023-02-11 22:32:31'),
(994, 'en', 'tl_commerce__add_page', 'TL Commerce | Add Page', '2023-02-11 22:32:35', '2023-02-11 22:32:35'),
(995, 'en', 'page_title', 'Page Title', '2023-02-11 22:32:35', '2023-02-11 22:32:35'),
(996, 'en', 'add_title', 'Add Title', '2023-02-11 22:32:35', '2023-02-11 22:32:35'),
(997, 'en', 'page_attributes', 'Page Attributes', '2023-02-11 22:32:35', '2023-02-11 22:32:35'),
(998, 'en', 'parents', 'Parents', '2023-02-11 22:32:35', '2023-02-11 22:32:35'),
(999, 'en', 'select_a_parent_page', 'Select a Parent Page', '2023-02-11 22:32:35', '2023-02-11 22:32:35'),
(1000, 'en', 'featured_image', 'Featured Image', '2023-02-11 22:32:35', '2023-02-11 22:32:35'),
(1001, 'en', 'create_or_manage_zone', 'Create or manage zone', '2023-02-11 22:33:21', '2023-02-11 22:33:21'),
(1002, 'en', 'no_shipping_zone_found', 'No shipping zone found', '2023-02-11 22:33:21', '2023-02-11 22:33:21'),
(1003, 'en', 'add_or_manage_shipping_zone', 'Add or manage shipping zone', '2023-02-11 22:33:21', '2023-02-11 22:33:21'),
(1004, 'en', 'refunded', 'Refunded', '2023-02-11 22:33:30', '2023-02-11 22:33:30'),
(1005, 'en', 'return_status', 'Return Status', '2023-02-11 22:33:30', '2023-02-11 22:33:30'),
(1006, 'en', 'product_received', 'Product Received', '2023-02-11 22:33:30', '2023-02-11 22:33:30'),
(1007, 'en', 'refund_code', 'Refund Code', '2023-02-11 22:33:30', '2023-02-11 22:33:30'),
(1008, 'en', 'price', 'Price', '2023-02-11 22:33:30', '2023-02-11 22:33:30'),
(1009, 'en', 'quick_action', 'Quick Action', '2023-02-11 22:33:30', '2023-02-11 22:33:30'),
(1010, 'en', 'refund_request_information', 'Refund Request Information', '2023-02-11 22:33:30', '2023-02-11 22:33:30'),
(1011, 'en', 'details_not_found', 'Details not found', '2023-02-11 22:33:30', '2023-02-11 22:33:30'),
(1012, 'en', 'reason', 'Reason', '2023-02-11 22:33:32', '2023-02-11 22:33:32'),
(1013, 'en', 'new_refund_reasons', 'New Refund Reasons', '2023-02-11 22:33:32', '2023-02-11 22:33:32'),
(1014, 'en', 'installupdate_theme', 'Install/Update Theme', '2023-02-11 22:33:35', '2023-02-11 22:33:35'),
(1015, 'en', 'by', 'By:', '2023-02-11 22:33:35', '2023-02-11 22:33:35'),
(1016, 'en', 'version', 'Version:', '2023-02-11 22:33:35', '2023-02-11 22:33:35'),
(1017, 'en', 'remove_confirmation', 'Remove Confirmation', '2023-02-11 22:33:35', '2023-02-11 22:33:35'),
(1018, 'en', 'are_you_sure_to_remove_this_theme', 'Are you sure to remove this theme', '2023-02-11 22:33:35', '2023-02-11 22:33:35'),
(1019, 'en', 'remove', 'Remove', '2023-02-11 22:33:35', '2023-02-11 22:33:35'),
(1020, 'en', 'activate_confirmation', 'activate Confirmation', '2023-02-11 22:33:35', '2023-02-11 22:33:35'),
(1021, 'en', 'are_you_sure_to_active_this_theme', 'Are you sure to active this theme', '2023-02-11 22:33:35', '2023-02-11 22:33:35'),
(1022, 'en', 'activate', 'Activate', '2023-02-11 22:33:35', '2023-02-11 22:33:35'),
(1023, 'en', 'theme_primary_color', 'Theme Primary Color', '2023-02-11 22:33:47', '2023-02-11 22:33:47'),
(1024, 'en', 'set_theme_primary_color', 'Set theme primary color', '2023-02-11 22:33:47', '2023-02-11 22:33:47'),
(1025, 'en', 'these_settings_control_the_typography_for_body', 'These settings control the typography for body.', '2023-02-11 22:33:50', '2023-02-11 22:33:50'),
(1026, 'en', 'font_family', 'Font Family', '2023-02-11 22:33:50', '2023-02-11 22:33:50'),
(1027, 'en', 'select__fonts', 'Select  Fonts', '2023-02-11 22:33:50', '2023-02-11 22:33:50'),
(1028, 'en', 'custom_font_1', 'Custom Font 1', '2023-02-11 22:33:50', '2023-02-11 22:33:50'),
(1029, 'en', 'custom_font_2', 'Custom Font 2', '2023-02-11 22:33:50', '2023-02-11 22:33:50'),
(1030, 'en', 'google_web_fonts', 'Google Web Fonts', '2023-02-11 22:33:50', '2023-02-11 22:33:50'),
(1031, 'en', 'font_weight__style', 'Font Weight & Style', '2023-02-11 22:33:50', '2023-02-11 22:33:50'),
(1032, 'en', 'font_subsets', 'Font Subsets', '2023-02-11 22:33:50', '2023-02-11 22:33:50'),
(1033, 'en', 'text_align', 'Text Align', '2023-02-11 22:33:50', '2023-02-11 22:33:50'),
(1034, 'en', 'text_transform', 'Text Transform', '2023-02-11 22:33:50', '2023-02-11 22:33:50'),
(1035, 'en', 'font_size', 'Font Size', '2023-02-11 22:33:50', '2023-02-11 22:33:50'),
(1036, 'en', 'size', 'Size', '2023-02-11 22:33:50', '2023-02-11 22:33:50'),
(1037, 'en', 'line_height', 'Line Height', '2023-02-11 22:33:50', '2023-02-11 22:33:50'),
(1038, 'en', 'word_spacing', 'Word Spacing', '2023-02-11 22:33:50', '2023-02-11 22:33:50'),
(1039, 'en', 'letter_spacing', 'Letter Spacing', '2023-02-11 22:33:50', '2023-02-11 22:33:50'),
(1040, 'en', 'the_quick_brown_fox_jumps_over_the_lazy_dog', 'The Quick Brown Fox Jumps Over The Lazy Dog', '2023-02-11 22:33:50', '2023-02-11 22:33:50'),
(1041, 'en', 'paragraph_typographyp', 'Paragraph Typography(P)', '2023-02-11 22:33:56', '2023-02-11 22:33:56'),
(1042, 'en', 'these_settings_control_the_typography_for_all_pparagraph', 'These settings control the typography for all (p)paragraph.', '2023-02-11 22:33:56', '2023-02-11 22:33:56'),
(1043, 'en', 'all_heading_typography', 'All Heading Typography', '2023-02-11 22:33:59', '2023-02-11 22:33:59'),
(1044, 'en', 'these_settings_control_the_typography_for_all_heading', 'These settings control the typography for all Heading.', '2023-02-11 22:33:59', '2023-02-11 22:33:59'),
(1045, 'en', 'h1_heading_typography', '(H1) Heading Typography', '2023-02-11 22:33:59', '2023-02-11 22:33:59'),
(1046, 'en', 'these_settings_control_the_typography_for_all_h1heading', 'These settings control the typography for all (H1)Heading.', '2023-02-11 22:33:59', '2023-02-11 22:33:59'),
(1047, 'en', 'h2_heading_typography', '(H2) Heading Typography', '2023-02-11 22:33:59', '2023-02-11 22:33:59'),
(1048, 'en', 'these_settings_control_the_typography_for_all_h2heading', 'These settings control the typography for all (H2)Heading.', '2023-02-11 22:33:59', '2023-02-11 22:33:59'),
(1049, 'en', 'h3_heading_typography', '(H3) Heading Typography', '2023-02-11 22:33:59', '2023-02-11 22:33:59'),
(1050, 'en', 'these_settings_control_the_typography_for_all_h3heading', 'These settings control the typography for all (H3)Heading.', '2023-02-11 22:33:59', '2023-02-11 22:33:59'),
(1051, 'en', 'h4_heading_typography', '(H4) Heading Typography', '2023-02-11 22:33:59', '2023-02-11 22:33:59'),
(1052, 'en', 'these_settings_control_the_typography_for_all_h4heading', 'These settings control the typography for all (H4)Heading.', '2023-02-11 22:33:59', '2023-02-11 22:33:59'),
(1053, 'en', 'h5_heading_typography', '(H5) Heading Typography', '2023-02-11 22:33:59', '2023-02-11 22:33:59'),
(1054, 'en', 'these_settings_control_the_typography_for_all_h5heading', 'These settings control the typography for all (H5)Heading.', '2023-02-11 22:33:59', '2023-02-11 22:33:59'),
(1055, 'en', 'h6_heading_typography', '(H6) Heading Typography', '2023-02-11 22:33:59', '2023-02-11 22:33:59'),
(1056, 'en', 'these_settings_control_the_typography_for_all_h6heading', 'These settings control the typography for all (H6)Heading.', '2023-02-11 22:33:59', '2023-02-11 22:33:59'),
(1057, 'en', 'these_settings_control_the_typography_for_menu', 'These settings control the typography for menu.', '2023-02-11 22:34:12', '2023-02-11 22:34:12'),
(1058, 'en', 'submenu_typography', 'Submenu Typography', '2023-02-11 22:34:12', '2023-02-11 22:34:12'),
(1059, 'en', 'these_settings_control_the_typography_for_submenu', 'These settings control the typography for submenu.', '2023-02-11 22:34:12', '2023-02-11 22:34:12'),
(1060, 'en', 'these_settings_control_the_typography_for_button', 'These settings control the typography for button.', '2023-02-11 22:34:18', '2023-02-11 22:34:18'),
(1061, 'en', 'after_uploading_your_fonts_you_should_select_font_family_customfont1customfont2_from_dropdown_list_in_bodyparagraphheadingsmenublog_typography_section', 'After uploading your fonts, you should select font family (custom-font-1/custom-font-2 from dropdown list in (Body/Paragraph/Headings/Menu/Blog) Typography section.', '2023-02-11 22:34:22', '2023-02-11 22:34:22'),
(1062, 'en', 'custom_font1', 'Custom Font1', '2023-02-11 22:34:22', '2023-02-11 22:34:22'),
(1063, 'en', 'please_enable_this_option_to_use_custom_font_1', 'Please Enable this option to use Custom Font 1.', '2023-02-11 22:34:22', '2023-02-11 22:34:22'),
(1064, 'en', 'custom_font_1_woff', 'Custom font 1 .woff', '2023-02-11 22:34:22', '2023-02-11 22:34:22'),
(1065, 'en', 'uploade_file', 'Uploade File', '2023-02-11 22:34:22', '2023-02-11 22:34:22'),
(1066, 'en', 'custom_font_1_ttf', 'Custom font 1 .ttf', '2023-02-11 22:34:22', '2023-02-11 22:34:22'),
(1067, 'en', 'custom_font_1_eot', 'Custom font 1 .eot', '2023-02-11 22:34:22', '2023-02-11 22:34:22'),
(1068, 'en', 'custom_font2', 'Custom Font2', '2023-02-11 22:34:22', '2023-02-11 22:34:22'),
(1069, 'en', 'please_enable_this_option_to_use_custom_font_2', 'Please Enable this option to use Custom Font 2.', '2023-02-11 22:34:22', '2023-02-11 22:34:22'),
(1070, 'en', 'custom_font_2_woff', 'Custom font 2 .woff', '2023-02-11 22:34:22', '2023-02-11 22:34:22'),
(1071, 'en', 'custom_font_2_ttf', 'Custom font 2 .ttf', '2023-02-11 22:34:22', '2023-02-11 22:34:22'),
(1072, 'en', 'custom_font_2_eot', 'Custom font 2 .eot', '2023-02-11 22:34:22', '2023-02-11 22:34:22'),
(1073, 'en', 'custom_header_style', 'Custom Header Style', '2023-02-11 22:34:27', '2023-02-11 22:34:27'),
(1074, 'en', 'switch_on_for_custom_header_style', 'Switch on for custom header style.', '2023-02-11 22:34:27', '2023-02-11 22:34:27'),
(1075, 'en', 'header_bottom_email_text', 'Header Bottom Email Text', '2023-02-11 22:34:27', '2023-02-11 22:34:27'),
(1076, 'en', 'set_header_bottom_email_text', 'Set Header Bottom Email Text.', '2023-02-11 22:34:27', '2023-02-11 22:34:27'),
(1077, 'en', 'header_top_background_color', 'Header Top Background Color', '2023-02-11 22:34:27', '2023-02-11 22:34:27'),
(1078, 'en', 'set_header_top_background_color', 'Set Header Top Background Color.', '2023-02-11 22:34:27', '2023-02-11 22:34:27'),
(1079, 'en', 'header_middle_background_color', 'Header Middle Background Color', '2023-02-11 22:34:27', '2023-02-11 22:34:27'),
(1080, 'en', 'set_header_middle_background_color', 'Set Header Middle Background Color.', '2023-02-11 22:34:27', '2023-02-11 22:34:27'),
(1081, 'en', 'header_bottom_background_color', 'Header Bottom Background Color', '2023-02-11 22:34:27', '2023-02-11 22:34:27'),
(1082, 'en', 'set_header_bottom_background_color', 'Set Header Bottom Background Color.', '2023-02-11 22:34:27', '2023-02-11 22:34:27'),
(1083, 'en', 'header_bottom_text_color', 'Header Bottom Text Color', '2023-02-11 22:34:27', '2023-02-11 22:34:27'),
(1084, 'en', 'set_header_bottom_text_color', 'Set Header Bottom Text Color.', '2023-02-11 22:34:27', '2023-02-11 22:34:27'),
(1085, 'en', 'sticky_header_background_color', 'Sticky Header Background Color', '2023-02-11 22:34:27', '2023-02-11 22:34:27'),
(1086, 'en', 'set_sticky_header_background_color', 'Set Sticky Header Background color.', '2023-02-11 22:34:27', '2023-02-11 22:34:27'),
(1087, 'en', 'header_search_form_button_color', 'Header Search Form Button Color', '2023-02-11 22:34:27', '2023-02-11 22:34:27'),
(1088, 'en', 'set_header_search_form_button_color', 'Set header search form button color', '2023-02-11 22:34:27', '2023-02-11 22:34:27'),
(1089, 'en', 'header_search_form_button_hover_color', 'Header Search Form Button Hover Color', '2023-02-11 22:34:27', '2023-02-11 22:34:27'),
(1090, 'en', 'set_header_search_form_button_hover_color', 'Set header search form button hover color', '2023-02-11 22:34:27', '2023-02-11 22:34:27'),
(1091, 'en', 'header_search_form_button_text_color', 'Header Search Form Button Text Color', '2023-02-11 22:34:27', '2023-02-11 22:34:27'),
(1092, 'en', 'set_header_search_form_button_text_color', 'Set header search form button text color', '2023-02-11 22:34:27', '2023-02-11 22:34:27'),
(1093, 'en', 'header_search_form_button_hover_text_color', 'Header Search Form Button Hover Text Color', '2023-02-11 22:34:27', '2023-02-11 22:34:27'),
(1094, 'en', 'set_header_search_form_button_hover_text_color', 'Set header search form button hover text color', '2023-02-11 22:34:27', '2023-02-11 22:34:27'),
(1095, 'en', 'header_icon_button_color', 'Header Icon Button Color', '2023-02-11 22:34:27', '2023-02-11 22:34:27'),
(1096, 'en', 'set_header_icon_button_color', 'Set header Icon Button color', '2023-02-11 22:34:27', '2023-02-11 22:34:27'),
(1097, 'en', 'header_icon_button_text_color', 'Header Icon Button Text Color', '2023-02-11 22:34:27', '2023-02-11 22:34:27'),
(1098, 'en', 'set_header_icon_button_text_color', 'Set header Icon Button Text color', '2023-02-11 22:34:27', '2023-02-11 22:34:27'),
(1099, 'en', 'header_icon_button_hover_color', 'Header Icon Button Hover Color', '2023-02-11 22:34:27', '2023-02-11 22:34:27'),
(1100, 'en', 'set_header_icon_button_hover_color', 'Set header Icon Button Hover color', '2023-02-11 22:34:27', '2023-02-11 22:34:27'),
(1101, 'en', 'header_icon_button_hover_text_color', 'Header Icon Button Hover Text Color', '2023-02-11 22:34:27', '2023-02-11 22:34:27'),
(1102, 'en', 'set_header_icon_button_hover_text_color', 'Set header Icon Button Hover Text color', '2023-02-11 22:34:27', '2023-02-11 22:34:27'),
(1103, 'en', 'header_top_language_change_button_color', 'Header Top Language Change Button Color', '2023-02-11 22:34:27', '2023-02-11 22:34:27'),
(1104, 'en', 'set_header_top_language_change_button_color', 'Set Header Top Language Change Button color', '2023-02-11 22:34:27', '2023-02-11 22:34:27'),
(1105, 'en', 'header_top_language_change_button_text_color', 'Header Top Language Change Button Text Color', '2023-02-11 22:34:27', '2023-02-11 22:34:27'),
(1106, 'en', 'set_header_top_language_change_button_text_color', 'Set Header Top Language Change Button Text color', '2023-02-11 22:34:27', '2023-02-11 22:34:27'),
(1107, 'en', 'header_top_language_change_button_hover_color', 'Header Top Language Change Button Hover Color', '2023-02-11 22:34:27', '2023-02-11 22:34:27'),
(1108, 'en', 'set_header_top_language_change_button_hover_color', 'Set Header Top Language Change Button Hover color', '2023-02-11 22:34:27', '2023-02-11 22:34:27'),
(1109, 'en', 'header_top_language_change_button_hover_text_color', 'Header Top Language Change Button Hover Text Color', '2023-02-11 22:34:27', '2023-02-11 22:34:27'),
(1110, 'en', 'set_header_top_language_change_button_hover_text_color', 'Set Header Top Language Change Button Hover Text color', '2023-02-11 22:34:27', '2023-02-11 22:34:27'),
(1111, 'en', 'custom_header_logo_style', 'Custom Header Logo Style', '2023-02-11 22:34:29', '2023-02-11 22:34:29'),
(1112, 'en', 'switch_on_for_custom_header_logo_style', 'Switch on for custom header logo style.', '2023-02-11 22:34:29', '2023-02-11 22:34:29'),
(1113, 'en', 'logo_dimensions_widthheight', 'Logo Dimensions (Width/Height).', '2023-02-11 22:34:29', '2023-02-11 22:34:29'),
(1114, 'en', 'set_logo_dimensions_to_choose_width_height_and_unit', 'Set logo dimensions to choose width, height, and unit.', '2023-02-11 22:34:29', '2023-02-11 22:34:29'),
(1115, 'en', 'logo_top_and_bottom_margin', 'Logo Top and Bottom Margin.', '2023-02-11 22:34:29', '2023-02-11 22:34:29'),
(1116, 'en', 'set_logo_top_and_bottom_margin', 'Set logo top and bottom margin.', '2023-02-11 22:34:29', '2023-02-11 22:34:29'),
(1117, 'en', 'sticky_logo_dimensions_widthheight', 'Sticky Logo Dimensions (Width/Height).', '2023-02-11 22:34:29', '2023-02-11 22:34:29'),
(1118, 'en', 'set_sticky_logo_dimensions_to_choose_width_height_and_unit', 'Set Sticky logo dimensions to choose width, height, and unit.', '2023-02-11 22:34:29', '2023-02-11 22:34:29'),
(1119, 'en', 'sticky_logo_top_and_bottom_margin', 'Sticky Logo Top and Bottom Margin.', '2023-02-11 22:34:29', '2023-02-11 22:34:29'),
(1120, 'en', 'set_sticky_logo_top_and_bottom_margin', 'Set Sticky logo top and bottom margin.', '2023-02-11 22:34:29', '2023-02-11 22:34:29'),
(1121, 'en', 'custom_menu_style', 'Custom Menu Style', '2023-02-11 22:34:30', '2023-02-11 22:34:30'),
(1122, 'en', 'switch_on_for_custom_menu_style', 'Switch on for custom menu style.', '2023-02-11 22:34:30', '2023-02-11 22:34:30'),
(1123, 'en', 'menu_color', 'Menu Color', '2023-02-11 22:34:30', '2023-02-11 22:34:30'),
(1124, 'en', 'set_header_menu_color', 'Set header menu color.', '2023-02-11 22:34:30', '2023-02-11 22:34:30'),
(1125, 'en', 'menu_hover_color', 'Menu Hover Color', '2023-02-11 22:34:30', '2023-02-11 22:34:30'),
(1126, 'en', 'set_header_menu_hover_color', 'Set header menu hover color.', '2023-02-11 22:34:30', '2023-02-11 22:34:30'),
(1127, 'en', 'sub_menu_color', 'Sub Menu Color', '2023-02-11 22:34:30', '2023-02-11 22:34:30'),
(1128, 'en', 'set_header_sub_menu_color', 'Set header sub menu color.', '2023-02-11 22:34:30', '2023-02-11 22:34:30'),
(1129, 'en', 'sub_menu_hover_color', 'Sub Menu Hover Color', '2023-02-11 22:34:30', '2023-02-11 22:34:30'),
(1130, 'en', 'set_header_sub_menu_hover_color', 'Set header sub menu hover color.', '2023-02-11 22:34:30', '2023-02-11 22:34:30'),
(1131, 'en', 'custom_blog_style', 'Custom Blog Style', '2023-02-11 22:34:33', '2023-02-11 22:34:33'),
(1132, 'en', 'switch_on_for_custom_blog_style', 'Switch on for custom blog style.', '2023-02-11 22:34:33', '2023-02-11 22:34:33'),
(1133, 'en', 'choose_blog_layout_from_here_if_you_use_this_option_then_you_will_able_to_change_three_type_of_blog_layout__default_right_sidebar_layour_', 'Choose blog layout from here. If you use this option then you will able to change three type of blog layout ( Default Right Sidebar Layour ).', '2023-02-11 22:34:33', '2023-02-11 22:34:33'),
(1134, 'en', 'blog_column', 'Blog Column', '2023-02-11 22:34:33', '2023-02-11 22:34:33'),
(1135, 'en', 'select_your_blog_post_column_from_here_if_you_use_this_option_then_you_will_able_to_select_three_type_of_blog_colum_layout__default_one_column_', 'Select your blog post column from here. If you use this option then you will able to select three type of blog colum layout ( Default One Column ).', '2023-02-11 22:34:33', '2023-02-11 22:34:33'),
(1136, 'en', 'read_more_text_setting', 'Read More Text Setting', '2023-02-11 22:34:33', '2023-02-11 22:34:33'),
(1137, 'en', 'control_read_more_text_from_here', 'Control read more text from here.', '2023-02-11 22:34:34', '2023-02-11 22:34:34'),
(1138, 'en', 'read_more_text', 'Read More Text', '2023-02-11 22:34:34', '2023-02-11 22:34:34'),
(1139, 'en', 'set_read_moer_text_here_if_you_use_this_option_then_you_will_able_to_set_your_won_text', 'Set read moer text here. If you use this option then you will able to set your won text.', '2023-02-11 22:34:34', '2023-02-11 22:34:34'),
(1140, 'en', 'blog_perpage_number', 'Blog PerPage Number', '2023-02-11 22:34:34', '2023-02-11 22:34:34'),
(1141, 'en', 'control_the_number_blogs_to_show_on_each_page__default_show_9_', 'Control the number blogs to show on each page ( Default show 9 ).', '2023-02-11 22:34:34', '2023-02-11 22:34:34'),
(1142, 'en', 'blog_pagination_position', 'Blog Pagination Position', '2023-02-11 22:34:34', '2023-02-11 22:34:34'),
(1143, 'en', 'set_blog_pagination_position', 'Set blog pagination Position.', '2023-02-11 22:34:34', '2023-02-11 22:34:34'),
(1144, 'en', 'blog_pagination_color', 'Blog Pagination Color', '2023-02-11 22:34:34', '2023-02-11 22:34:34'),
(1145, 'en', 'set_blog_pagination_color', 'Set Blog Pagination Color.', '2023-02-11 22:34:34', '2023-02-11 22:34:34'),
(1146, 'en', 'blog_pagination_background_color', 'Blog Pagination Background Color', '2023-02-11 22:34:34', '2023-02-11 22:34:34'),
(1147, 'en', 'set_blog_pagination_background_color', 'Set Blog Pagination Background Color.', '2023-02-11 22:34:34', '2023-02-11 22:34:34'),
(1148, 'en', 'blog_pagination_border_color', 'Blog Pagination Border Color', '2023-02-11 22:34:34', '2023-02-11 22:34:34'),
(1149, 'en', 'set_blog_pagination_border_color', 'Set Blog Pagination Border Color.', '2023-02-11 22:34:34', '2023-02-11 22:34:34'),
(1150, 'en', 'blog_pagination_active_color', 'Blog Pagination Active Color', '2023-02-11 22:34:34', '2023-02-11 22:34:34'),
(1151, 'en', 'set_blog_pagination_active_color', 'Set Blog Pagination Active Color.', '2023-02-11 22:34:34', '2023-02-11 22:34:34'),
(1152, 'en', 'blog_pagination_active_background_color', 'Blog Pagination Active Background Color', '2023-02-11 22:34:34', '2023-02-11 22:34:34'),
(1153, 'en', 'set_blog_pagination_active_background_color', 'Set Blog Pagination Active Background Color.', '2023-02-11 22:34:34', '2023-02-11 22:34:34'),
(1154, 'en', 'blog_pagination_active_border_color', 'Blog Pagination Active Border Color', '2023-02-11 22:34:34', '2023-02-11 22:34:34'),
(1155, 'en', 'set_blog_pagination_active_border_color', 'Set Blog Pagination Active Border Color.', '2023-02-11 22:34:34', '2023-02-11 22:34:34'),
(1156, 'en', 'blog_pagination_hover_color', 'Blog Pagination Hover Color', '2023-02-11 22:34:34', '2023-02-11 22:34:34'),
(1157, 'en', 'set_blog_pagination_hover_color', 'Set Blog Pagination Hover Color.', '2023-02-11 22:34:34', '2023-02-11 22:34:34'),
(1158, 'en', 'blog_pagination_hover_background_color', 'Blog Pagination Hover Background Color', '2023-02-11 22:34:34', '2023-02-11 22:34:34'),
(1159, 'en', 'set_blog_pagination_hover_background_color', 'Set Blog Pagination Hover Background Color.', '2023-02-11 22:34:34', '2023-02-11 22:34:34'),
(1160, 'en', 'blog_pagination_hover_border_color', 'Blog Pagination Hover Border Color', '2023-02-11 22:34:34', '2023-02-11 22:34:34'),
(1161, 'en', 'set_blog_pagination_hover_border_color', 'Set Blog Pagination Hover Border Color.', '2023-02-11 22:34:34', '2023-02-11 22:34:34'),
(1162, 'en', 'custom_sidebar_style', 'Custom Sidebar Style', '2023-02-11 22:34:39', '2023-02-11 22:34:39'),
(1163, 'en', 'switch_on_for_custom_sidebar_style', 'Switch on for custom Sidebar style.', '2023-02-11 22:34:39', '2023-02-11 22:34:39'),
(1164, 'en', 'widgets_background_color', 'Widgets Background Color', '2023-02-11 22:34:39', '2023-02-11 22:34:39'),
(1165, 'en', 'box_shadow', 'Box Shadow', '2023-02-11 22:34:39', '2023-02-11 22:34:39'),
(1166, 'en', 'offset_x', 'Offset X', '2023-02-11 22:34:39', '2023-02-11 22:34:39'),
(1167, 'en', 'offset_y', 'Offset Y', '2023-02-11 22:34:39', '2023-02-11 22:34:39'),
(1168, 'en', 'blur_radius', 'Blur Radius', '2023-02-11 22:34:39', '2023-02-11 22:34:39'),
(1169, 'en', 'spread_radius', 'Spread Radius', '2023-02-11 22:34:39', '2023-02-11 22:34:39'),
(1170, 'en', 'opcacity_11', 'Opcacity .1-1', '2023-02-11 22:34:39', '2023-02-11 22:34:39'),
(1171, 'en', 'shadow_color', 'Shadow Color', '2023-02-11 22:34:39', '2023-02-11 22:34:39'),
(1172, 'en', 'shadow_type', 'Shadow Type', '2023-02-11 22:34:39', '2023-02-11 22:34:39'),
(1173, 'en', 'widget_margin', 'Widget Margin', '2023-02-11 22:34:39', '2023-02-11 22:34:39'),
(1174, 'en', 'widget_padding', 'Widget Padding', '2023-02-11 22:34:39', '2023-02-11 22:34:39'),
(1175, 'en', 'widget_border', 'Widget Border', '2023-02-11 22:34:39', '2023-02-11 22:34:39'),
(1176, 'en', 'select_style', 'Select Style', '2023-02-11 22:34:39', '2023-02-11 22:34:39'),
(1177, 'en', 'widget_title_margin', 'Widget Title Margin', '2023-02-11 22:34:39', '2023-02-11 22:34:39'),
(1178, 'en', 'widget_title_padding', 'Widget Title Padding', '2023-02-11 22:34:40', '2023-02-11 22:34:40'),
(1179, 'en', 'widget_title_color', 'Widget Title Color', '2023-02-11 22:34:40', '2023-02-11 22:34:40'),
(1180, 'en', 'set_widget_title_color', 'Set Widget Title Color.', '2023-02-11 22:34:40', '2023-02-11 22:34:40'),
(1181, 'en', 'widget_text_color', 'Widget Text Color', '2023-02-11 22:34:40', '2023-02-11 22:34:40'),
(1182, 'en', 'set_widget_text_color', 'Set Widget Text Color.', '2023-02-11 22:34:40', '2023-02-11 22:34:40'),
(1183, 'en', 'widget_anchor_color', 'Widget Anchor Color', '2023-02-11 22:34:40', '2023-02-11 22:34:40'),
(1184, 'en', 'set_widget_anchor_color', 'Set Widget Anchor Color.', '2023-02-11 22:34:40', '2023-02-11 22:34:40'),
(1185, 'en', 'widget_anchor_hover_color', 'Widget Anchor Hover Color', '2023-02-11 22:34:40', '2023-02-11 22:34:40'),
(1186, 'en', 'set_widget_anchor_hover_color', 'Set Widget Anchor Hover Color.', '2023-02-11 22:34:40', '2023-02-11 22:34:40'),
(1187, 'en', 'custom_404_style', 'Custom 404 Style', '2023-02-11 22:34:42', '2023-02-11 22:34:42'),
(1188, 'en', 'switch_on_for_custom_404_style', 'Switch on for custom 404 style.', '2023-02-11 22:34:42', '2023-02-11 22:34:42'),
(1189, 'en', 'set_page_title', 'Set Page Title', '2023-02-11 22:34:42', '2023-02-11 22:34:42'),
(1190, 'en', '404_image', '404 Image', '2023-02-11 22:34:42', '2023-02-11 22:34:42'),
(1191, 'en', 'upload_your_site_404_image_for_header__recommendation_png_format_', 'Upload your site 404_image for header ( recommendation png format ).', '2023-02-11 22:34:42', '2023-02-11 22:34:42'),
(1192, 'en', 'button_text', 'Button Text', '2023-02-11 22:34:42', '2023-02-11 22:34:42'),
(1193, 'en', 'button_text_color', 'Button Text Color', '2023-02-11 22:34:42', '2023-02-11 22:34:42'),
(1194, 'en', 'button_hover_background_color', 'Button Hover Background Color', '2023-02-11 22:34:42', '2023-02-11 22:34:42'),
(1195, 'en', 'button_hover_text_color', 'Button Hover Text Color', '2023-02-11 22:34:42', '2023-02-11 22:34:42'),
(1196, 'en', 'mailchimp_api_key', 'Mailchimp API Key', '2023-02-11 22:34:53', '2023-02-11 22:34:53'),
(1197, 'en', 'set_mailchimp_api_key', 'Set mailchimp api key', '2023-02-11 22:34:53', '2023-02-11 22:34:53'),
(1198, 'en', 'mailchimp_list_id', 'Mailchimp List ID', '2023-02-11 22:34:53', '2023-02-11 22:34:53'),
(1199, 'en', 'set_mailchimp_list_id', 'Set mailchimp list id.', '2023-02-11 22:34:53', '2023-02-11 22:34:53'),
(1200, 'en', 'custom_subscripton_style', 'Custom Subscripton Style', '2023-02-11 22:34:53', '2023-02-11 22:34:53'),
(1201, 'en', 'switch_on_for_custom_subscripton_style', 'Switch on for custom Subscripton style.', '2023-02-11 22:34:53', '2023-02-11 22:34:53'),
(1202, 'en', 'form_button_text', 'Form Button Text', '2023-02-11 22:34:53', '2023-02-11 22:34:53'),
(1203, 'en', 'form_input_background_color', 'Form Input Background Color', '2023-02-11 22:34:53', '2023-02-11 22:34:53'),
(1204, 'en', 'form_input_text_color', 'Form Input Text Color', '2023-02-11 22:34:53', '2023-02-11 22:34:53'),
(1205, 'en', 'form_submit_button_color', 'Form Submit Button Color', '2023-02-11 22:34:53', '2023-02-11 22:34:53'),
(1206, 'en', 'form_submit_button_background_color', 'Form Submit Button Background Color', '2023-02-11 22:34:53', '2023-02-11 22:34:53'),
(1207, 'en', 'form_submit_button_hover_color', 'Form Submit Button Hover Color', '2023-02-11 22:34:53', '2023-02-11 22:34:53'),
(1208, 'en', 'form_submit_button_hover_background_color', 'Form Submit Button Hover Background Color', '2023-02-11 22:34:53', '2023-02-11 22:34:53'),
(1209, 'en', 'social_profile_links', 'Social Profile Links', '2023-02-11 22:34:54', '2023-02-11 22:34:54'),
(1210, 'en', 'add_social_icon_and_url', 'Add social icon and url.', '2023-02-11 22:34:54', '2023-02-11 22:34:54'),
(1211, 'en', 'add_slide', 'Add Slide', '2023-02-11 22:34:54', '2023-02-11 22:34:54'),
(1212, 'en', 'custom_social_style', 'Custom Social Style', '2023-02-11 22:34:54', '2023-02-11 22:34:54'),
(1213, 'en', 'set_custom_social_style', 'set custom social style.', '2023-02-11 22:34:54', '2023-02-11 22:34:54'),
(1214, 'en', '_social_background_color', ' Social Background Color', '2023-02-11 22:34:54', '2023-02-11 22:34:54'),
(1215, 'en', 'set__social_background_color', 'Set  Social Background Color', '2023-02-11 22:34:54', '2023-02-11 22:34:54'),
(1216, 'en', '_social_border_color', ' Social Border Color', '2023-02-11 22:34:54', '2023-02-11 22:34:54'),
(1217, 'en', 'set__social_border_color', 'Set  Social Border Color', '2023-02-11 22:34:54', '2023-02-11 22:34:54'),
(1218, 'en', '_social_color', ' Social Color', '2023-02-11 22:34:54', '2023-02-11 22:34:54'),
(1219, 'en', '_social_hover_color', ' Social Hover Color', '2023-02-11 22:34:54', '2023-02-11 22:34:54'),
(1220, 'en', '_social_hover_border_color', ' Social Hover Border Color', '2023-02-11 22:34:54', '2023-02-11 22:34:54'),
(1221, 'en', 'social_hover_background_color', 'Social Hover Background Color', '2023-02-11 22:34:54', '2023-02-11 22:34:54'),
(1222, 'en', 'custom_footer_style', 'Custom Footer Style', '2023-02-11 22:34:56', '2023-02-11 22:34:56'),
(1223, 'en', 'switch_on_for_custom_footer_style', 'Switch on for custom footer style.', '2023-02-11 22:34:56', '2023-02-11 22:34:56'),
(1224, 'en', 'custom_footer_padding', 'Custom Footer Padding.', '2023-02-11 22:34:56', '2023-02-11 22:34:56'),
(1225, 'en', 'set_footer_padding', 'Set Footer Padding.', '2023-02-11 22:34:56', '2023-02-11 22:34:56'),
(1226, 'en', 'footer_background_color', 'Footer Background Color', '2023-02-11 22:34:56', '2023-02-11 22:34:56'),
(1227, 'en', 'set_background_color', 'Set Background Color', '2023-02-11 22:34:56', '2023-02-11 22:34:56'),
(1228, 'en', 'footer_text_color', 'Footer Text Color', '2023-02-11 22:34:56', '2023-02-11 22:34:56'),
(1229, 'en', 'set_text_color', 'Set Text Color', '2023-02-11 22:34:56', '2023-02-11 22:34:56'),
(1230, 'en', 'footer_anchor_color', 'Footer Anchor Color', '2023-02-11 22:34:56', '2023-02-11 22:34:56'),
(1231, 'en', 'set_footer_anchor_color', 'Set Footer Anchor Color', '2023-02-11 22:34:56', '2023-02-11 22:34:56'),
(1232, 'en', 'footer_anchor_hover_color', 'Footer Anchor Hover Color', '2023-02-11 22:34:56', '2023-02-11 22:34:56'),
(1233, 'en', 'set_footer_anchor_hover_color', 'Set Footer Anchor Hover Color', '2023-02-11 22:34:56', '2023-02-11 22:34:56'),
(1234, 'en', 'css_code', 'CSS Code', '2023-02-11 22:34:57', '2023-02-11 22:34:57'),
(1235, 'en', 'paste_your_css_code_here', 'Paste your CSS code here.', '2023-02-11 22:34:57', '2023-02-11 22:34:57'),
(1236, 'en', 'plugings', 'Plugings', '2023-02-11 22:35:11', '2023-02-11 22:35:11'),
(1237, 'en', 'installupdate_plugin', 'Install/Update Plugin', '2023-02-11 22:35:11', '2023-02-11 22:35:11'),
(1238, 'en', 'are_you_sure_to_remove_this_plugin', 'Are you sure to remove this plugin', '2023-02-11 22:35:11', '2023-02-11 22:35:11'),
(1239, 'en', 'deactive_confirmation', 'Deactive Confirmation', '2023-02-11 22:35:11', '2023-02-11 22:35:11'),
(1240, 'en', 'are_you_sure_to_deactive_this_plugin', 'Are you sure to deactive this plugin', '2023-02-11 22:35:11', '2023-02-11 22:35:11'),
(1241, 'en', 'deactivate', 'Deactivate', '2023-02-11 22:35:11', '2023-02-11 22:35:11'),
(1242, 'en', 'are_you_sure_to_active_this_plugin', 'Are you sure to active this plugin', '2023-02-11 22:35:11', '2023-02-11 22:35:11'),
(1243, 'en', 'add_new_user', 'Add New User', '2023-02-11 22:36:21', '2023-02-11 22:36:21'),
(1244, 'en', 'add_user', 'Add User', '2023-02-11 22:36:24', '2023-02-11 22:36:24'),
(1245, 'en', 'assign_role', 'Assign Role', '2023-02-11 22:36:24', '2023-02-11 22:36:24'),
(1246, 'en', 'select_a_role', 'Select a Role', '2023-02-11 22:36:29', '2023-02-11 22:36:29'),
(1247, 'en', 'add_role', 'Add Role', '2023-02-11 22:36:34', '2023-02-11 22:36:34'),
(1248, 'en', 'give_role_name', 'Give role name', '2023-02-11 22:36:34', '2023-02-11 22:36:34'),
(1249, 'en', 'module', 'Module', '2023-02-11 22:36:34', '2023-02-11 22:36:34'),
(1250, 'en', 'feature', 'Feature', '2023-02-11 22:36:34', '2023-02-11 22:36:34'),
(1251, 'en', 'show', 'Show', '2023-02-11 22:36:34', '2023-02-11 22:36:34'),
(1252, 'en', 'create', 'Create', '2023-02-11 22:36:34', '2023-02-11 22:36:34'),
(1253, 'en', 'manage', 'Manage', '2023-02-11 22:36:34', '2023-02-11 22:36:34'),
(1254, 'en', 'show_', 'Show ', '2023-02-11 22:36:34', '2023-02-11 22:36:34'),
(1255, 'en', 'create_', 'Create ', '2023-02-11 22:36:34', '2023-02-11 22:36:34'),
(1256, 'en', 'edit_', 'Edit ', '2023-02-11 22:36:34', '2023-02-11 22:36:34'),
(1257, 'en', 'delete_', 'Delete ', '2023-02-11 22:36:34', '2023-02-11 22:36:34'),
(1258, 'en', 'manage_', 'Manage ', '2023-02-11 22:36:34', '2023-02-11 22:36:34'),
(1259, 'en', 'update_role', 'Update Role', '2023-02-11 22:36:34', '2023-02-11 22:36:34'),
(1260, 'en', 'products_report', 'Products Report', '2023-02-11 22:37:59', '2023-02-11 22:37:59'),
(1261, 'en', 'product_category', 'Product category', '2023-02-11 22:37:59', '2023-02-11 22:37:59'),
(1262, 'en', 'in_stock', 'In Stock', '2023-02-11 22:37:59', '2023-02-11 22:37:59'),
(1263, 'en', 'total', 'Total', '2023-02-11 22:37:59', '2023-02-11 22:37:59'),
(1264, 'en', 'user_keyword_search_report', 'User Keyword Search Report', '2023-02-11 22:38:01', '2023-02-11 22:38:01'),
(1265, 'en', 'search_key', 'Search Key', '2023-02-11 22:38:01', '2023-02-11 22:38:01'),
(1266, 'en', 'num_of_search', 'Num of search', '2023-02-11 22:38:01', '2023-02-11 22:38:01'),
(1267, 'en', 'total_search', 'Total Search', '2023-02-11 22:38:01', '2023-02-11 22:38:01'),
(1268, 'en', 'products_wishlist_report', 'Products Wishlist Report', '2023-02-11 22:38:03', '2023-02-11 22:38:03'),
(1269, 'en', 'num_of_wish', 'Num of wish', '2023-02-11 22:38:04', '2023-02-11 22:38:04'),
(1270, 'en', 'total_wishlist', 'Total Wishlist', '2023-02-11 22:38:04', '2023-02-11 22:38:04'),
(1271, 'en', 'custom_notifications', 'Custom Notifications', '2023-02-11 22:38:18', '2023-02-11 22:38:18'),
(1272, 'en', 'compose', 'Compose', '2023-02-11 22:38:18', '2023-02-11 22:38:18'),
(1273, 'en', 'delete_selected', 'Delete Selected', '2023-02-11 22:38:18', '2023-02-11 22:38:18'),
(1274, 'en', 'sender', 'Sender', '2023-02-11 22:38:18', '2023-02-11 22:38:18'),
(1275, 'en', 'new_custom_notifications', 'New Custom Notifications', '2023-02-11 22:38:21', '2023-02-11 22:38:21'),
(1276, 'en', 'all_notifications', 'All Notifications', '2023-02-11 22:38:21', '2023-02-11 22:38:21'),
(1277, 'en', 'send_to', 'Send To', '2023-02-11 22:38:21', '2023-02-11 22:38:21'),
(1278, 'en', 'all_customers', 'All Customers', '2023-02-11 22:38:21', '2023-02-11 22:38:21'),
(1279, 'en', 'specific_customers', 'Specific Customers', '2023-02-11 22:38:21', '2023-02-11 22:38:21'),
(1280, 'en', 'all_users', 'All Users', '2023-02-11 22:38:21', '2023-02-11 22:38:21'),
(1281, 'en', 'specific_users', 'Specific Users', '2023-02-11 22:38:21', '2023-02-11 22:38:21'),
(1282, 'en', 'specific_user_role', 'Specific User Role', '2023-02-11 22:38:21', '2023-02-11 22:38:21'),
(1283, 'en', 'select_customers', 'Select Customers', '2023-02-11 22:38:21', '2023-02-11 22:38:21'),
(1284, 'en', 'select_users', 'Select Users', '2023-02-11 22:38:21', '2023-02-11 22:38:21'),
(1285, 'en', 'select_user_roles', 'Select User Roles', '2023-02-11 22:38:21', '2023-02-11 22:38:21'),
(1286, 'en', 'notification_type', 'Notification Type', '2023-02-11 22:38:21', '2023-02-11 22:38:21'),
(1287, 'en', 'dashboard__email', 'Dashboard & Email', '2023-02-11 22:38:21', '2023-02-11 22:38:21'),
(1288, 'en', 'notification_subject', 'Notification subject', '2023-02-11 22:38:21', '2023-02-11 22:38:21'),
(1289, 'en', 'send_now', 'Send Now', '2023-02-11 22:38:21', '2023-02-11 22:38:21'),
(1290, 'en', 'add_new_coupon', 'Add New Coupon', '2023-02-11 22:38:26', '2023-02-11 22:38:26'),
(1291, 'en', 'amount_type', 'Amount Type', '2023-02-11 22:38:26', '2023-02-11 22:38:26'),
(1292, 'en', 'usage__limit', 'Usage / Limit', '2023-02-11 22:38:26', '2023-02-11 22:38:26'),
(1293, 'en', 'new_coupon', 'New Coupon', '2023-02-11 22:38:28', '2023-02-11 22:38:28'),
(1294, 'en', 'coupon', 'Coupon', '2023-02-11 22:38:28', '2023-02-11 22:38:28'),
(1295, 'en', 'usage_restriction', 'Usage Restriction', '2023-02-11 22:38:28', '2023-02-11 22:38:28'),
(1296, 'en', 'usage_limits', 'Usage Limits', '2023-02-11 22:38:28', '2023-02-11 22:38:28'),
(1297, 'en', 'coupon_code', 'Coupon Code', '2023-02-11 22:38:28', '2023-02-11 22:38:28'),
(1298, 'en', 'discount_amount_type', 'Discount Amount Type', '2023-02-11 22:38:28', '2023-02-11 22:38:28'),
(1299, 'en', 'discount_amount', 'Discount Amount', '2023-02-11 22:38:29', '2023-02-11 22:38:29'),
(1300, 'en', 'allow_free_shipping', 'Allow Free Shipping', '2023-02-11 22:38:29', '2023-02-11 22:38:29'),
(1301, 'en', 'coupon_expiry_date', 'Coupon Expiry Date', '2023-02-11 22:38:29', '2023-02-11 22:38:29'),
(1302, 'en', 'minimum_spend', 'Minimum Spend', '2023-02-11 22:38:29', '2023-02-11 22:38:29'),
(1303, 'en', 'no_minimum', 'No Minimum', '2023-02-11 22:38:29', '2023-02-11 22:38:29'),
(1304, 'en', 'maximum_spend', 'Maximum Spend', '2023-02-11 22:38:29', '2023-02-11 22:38:29'),
(1305, 'en', 'no_maximum', 'No Maximum', '2023-02-11 22:38:29', '2023-02-11 22:38:29'),
(1306, 'en', 'individual_use_only', 'Individual Use Only', '2023-02-11 22:38:29', '2023-02-11 22:38:29'),
(1307, 'en', 'exclude_sales_items', 'Exclude Sales Items', '2023-02-11 22:38:29', '2023-02-11 22:38:29'),
(1308, 'en', 'exclude_product', 'Exclude product', '2023-02-11 22:38:29', '2023-02-11 22:38:29'),
(1309, 'en', 'exclude_brands', 'Exclude Brands', '2023-02-11 22:38:29', '2023-02-11 22:38:29'),
(1310, 'en', 'exclude_categories', 'Exclude Categories', '2023-02-11 22:38:29', '2023-02-11 22:38:29'),
(1311, 'en', 'allowed_email', 'Allowed Email', '2023-02-11 22:38:29', '2023-02-11 22:38:29'),
(1312, 'en', 'usage_limit_per_coupon', 'Usage limit per coupon', '2023-02-11 22:38:29', '2023-02-11 22:38:29'),
(1313, 'en', 'unlimited_usage', 'Unlimited Usage', '2023-02-11 22:38:29', '2023-02-11 22:38:29'),
(1314, 'en', 'usage_limit_per_user', 'Usage limit per user', '2023-02-11 22:38:29', '2023-02-11 22:38:29'),
(1315, 'en', 'no_brand_selected', 'No Brand Selected', '2023-02-11 22:38:29', '2023-02-11 22:38:29'),
(1316, 'en', 'no_category_selected', 'No Category Selected', '2023-02-11 22:38:29', '2023-02-11 22:38:29'),
(1317, 'en', 'instruction', 'Instruction', '2023-02-11 22:38:52', '2023-02-11 22:38:52'),
(1318, 'en', 'client_id', 'Client ID', '2023-02-11 22:38:52', '2023-02-11 22:38:52'),
(1319, 'en', 'client_secret', 'Client Secret', '2023-02-11 22:38:52', '2023-02-11 22:38:52'),
(1320, 'en', 'sandbox_mode', 'Sandbox mode', '2023-02-11 22:38:52', '2023-02-11 22:38:52'),
(1321, 'en', 'stripe_public_key', 'Stripe Public Key', '2023-02-11 22:38:53', '2023-02-11 22:38:53'),
(1322, 'en', 'stripe_secret_key', 'Stripe Secret Key', '2023-02-11 22:38:53', '2023-02-11 22:38:53'),
(1323, 'en', 'payment_method', 'Payment Method', '2023-02-11 22:38:55', '2023-02-11 22:38:55'),
(1324, 'en', 'payment_for', 'Payment For', '2023-02-11 22:38:55', '2023-02-11 22:38:55'),
(1325, 'en', 'payment_details', 'Payment Details', '2023-02-11 22:38:55', '2023-02-11 22:38:55'),
(1326, 'en', 'add_pickup_point', 'Add Pickup Point', '2023-02-11 22:39:14', '2023-02-11 22:39:14'),
(1327, 'en', 'pickup_point', 'Pickup Point', '2023-02-11 22:39:14', '2023-02-11 22:39:14'),
(1328, 'en', 'city', 'City', '2023-02-11 22:39:14', '2023-02-11 22:39:14'),
(1329, 'en', '________bulk_action_', '        Bulk Action ', '2023-02-11 22:39:14', '2023-02-11 22:39:14'),
(1330, 'en', '________delete_selection_', '        Delete selection ', '2023-02-11 22:39:14', '2023-02-11 22:39:14'),
(1331, 'en', '________apply_', '        Apply ', '2023-02-11 22:39:14', '2023-02-11 22:39:14'),
(1332, 'en', '____________________no_item_selected_', '                    No Item Selected ', '2023-02-11 22:39:14', '2023-02-11 22:39:14'),
(1333, 'en', '________________no_action_selected_', '                No Action Selected ', '2023-02-11 22:39:14', '2023-02-11 22:39:14'),
(1334, 'en', 'create_pickup_points', 'Create Pickup Points', '2023-02-11 22:39:18', '2023-02-11 22:39:18'),
(1335, 'en', 'add_pickup_points', 'Add Pickup Points', '2023-02-11 22:39:18', '2023-02-11 22:39:18'),
(1336, 'en', 'pickup_point_name', 'Pickup Point Name', '2023-02-11 22:39:18', '2023-02-11 22:39:18'),
(1337, 'en', 'give_pickup_point_name', 'Give Pickup Point Name', '2023-02-11 22:39:18', '2023-02-11 22:39:18'),
(1338, 'en', 'pickup_point_phone', 'Pickup Point Phone', '2023-02-11 22:39:18', '2023-02-11 22:39:18'),
(1339, 'en', 'give_pickup_point_phone', 'Give Pickup Point Phone', '2023-02-11 22:39:18', '2023-02-11 22:39:18'),
(1340, 'en', 'give_pickup_point_location', 'Give pickup point location', '2023-02-11 22:39:18', '2023-02-11 22:39:18'),
(1341, 'en', 'select_city', 'Select City', '2023-02-11 22:39:18', '2023-02-11 22:39:18'),
(1342, 'en', 'select_a_city', 'Select a City', '2023-02-11 22:39:18', '2023-02-11 22:39:18'),
(1343, 'en', 'create_new_carrier', 'Create new Carrier', '2023-02-11 22:39:24', '2023-02-11 22:39:24'),
(1344, 'en', 'tracking_url', 'Tracking url', '2023-02-11 22:39:24', '2023-02-11 22:39:24'),
(1345, 'en', 'add_new_shipping_courier', 'Add New Shipping Courier', '2023-02-11 22:39:24', '2023-02-11 22:39:24'),
(1346, 'en', 'type_url', 'Type url', '2023-02-11 22:39:24', '2023-02-11 22:39:24'),
(1347, 'en', 'shipping_courier_information', 'Shipping Courier Information', '2023-02-11 22:39:24', '2023-02-11 22:39:24'),
(1348, 'en', 'customer_details', 'Customer Details', '2023-02-11 22:40:17', '2023-02-11 22:40:17'),
(1349, 'en', 'id', 'ID', '2023-02-11 22:40:17', '2023-02-11 22:40:17'),
(1350, 'en', 'registered_date', 'Registered Date', '2023-02-11 22:40:17', '2023-02-11 22:40:17'),
(1351, 'en', 'total_purchase', 'Total Purchase', '2023-02-11 22:40:17', '2023-02-11 22:40:17'),
(1352, 'en', 'total_orders', 'Total Orders', '2023-02-11 22:40:17', '2023-02-11 22:40:17'),
(1353, 'en', 'reviews', 'Reviews', '2023-02-11 22:40:17', '2023-02-11 22:40:17'),
(1354, 'en', 'retun_requests', 'Retun Requests', '2023-02-11 22:40:17', '2023-02-11 22:40:17'),
(1355, 'en', 'addresses', 'Addresses', '2023-02-11 22:40:17', '2023-02-11 22:40:17'),
(1356, 'en', 'wishlists', 'Wishlists', '2023-02-11 22:40:17', '2023-02-11 22:40:17'),
(1357, 'en', 'tax', 'Tax', '2023-02-11 22:40:17', '2023-02-11 22:40:17'),
(1358, 'en', 'delivery_cost', 'Delivery cost', '2023-02-11 22:40:17', '2023-02-11 22:40:17'),
(1359, 'en', 'return_date', 'Return Date', '2023-02-11 22:40:17', '2023-02-11 22:40:17'),
(1360, 'en', 'total_stock', 'Total Stock', '2023-02-11 22:40:17', '2023-02-11 22:40:17'),
(1361, 'en', 'pickup_point_orders', 'Pickup point Orders', '2023-02-11 22:40:35', '2023-02-11 22:40:35'),
(1362, 'en', 'edit_condition', 'Edit Condition', '2023-02-11 22:40:45', '2023-02-11 22:40:45'),
(1363, 'en', 'condition_updated_successfully', 'Condition updated successfully', '2023-02-11 22:42:29', '2023-02-11 22:42:29'),
(1364, 'bd', 'forgot________________________________password', 'পাসওয়ার্ড ভুলে গেছেন?', '2023-02-11 22:59:14', '2023-02-11 22:59:14'),
(1365, 'bd', 'log_in', 'লগ ইন', '2023-02-11 22:59:14', '2023-02-11 22:59:14'),
(1366, 'bd', 'invalid_email_address', 'অকার্যকর ইমেইল ঠিকানা', '2023-02-11 22:59:14', '2023-02-11 22:59:14'),
(1367, 'bd', 'invalid_password', 'অবৈধ পাসওয়ার্ড', '2023-02-11 22:59:14', '2023-02-11 22:59:14'),
(1368, 'bd', 'login_successful', 'সফল লগইন', '2023-02-11 22:59:14', '2023-02-11 22:59:14'),
(1369, 'bd', 'dashboard', 'ড্যাশবোর্ড', '2023-02-11 22:59:14', '2023-02-11 22:59:14'),
(1370, 'bd', 'customers', 'কাস্টোমার', '2023-02-11 22:59:14', '2023-02-11 22:59:14'),
(1371, 'bd', 'orders', 'অর্ডার', '2023-02-11 22:59:14', '2023-02-11 22:59:14'),
(1372, 'bd', 'products', 'পণ্য', '2023-02-11 22:59:14', '2023-02-11 22:59:14'),
(1373, 'bd', 'total_sales', 'মোট বিক্রয়', '2023-02-11 22:59:14', '2023-02-11 22:59:14'),
(1374, 'bd', 'sale_reports', 'বিক্রয় রিপোর্ট', '2023-02-11 22:59:14', '2023-02-11 22:59:14'),
(1375, 'bd', 'monthly', 'মাসিক', '2023-02-11 22:59:14', '2023-02-11 22:59:14'),
(1376, 'bd', 'daily', 'দৈনিক', '2023-02-11 22:59:14', '2023-02-11 22:59:14'),
(1377, 'bd', 'pending', 'বিচারাধীন', '2023-02-11 22:59:14', '2023-02-11 22:59:14'),
(1378, 'bd', 'approved', 'অনুমোদিত', '2023-02-11 22:59:14', '2023-02-11 22:59:14'),
(1379, 'bd', 'ready_to_ship', 'রেডি টু শিপ', '2023-02-11 22:59:14', '2023-02-11 22:59:14'),
(1380, 'bd', 'shipped', 'পাঠানো হয়েছে', '2023-02-11 22:59:14', '2023-02-11 22:59:14'),
(1381, 'bd', 'delivered', 'ডেলিভেরেদ', '2023-02-11 22:59:14', '2023-02-11 22:59:14'),
(1382, 'bd', 'cancelled', 'বাতিল', '2023-02-11 22:59:14', '2023-02-11 22:59:14'),
(1383, 'bd', 'recent_orders', 'সাম্প্রতিক অর্ডার', '2023-02-11 22:59:14', '2023-02-11 22:59:14'),
(1384, 'bd', 'order_id', 'অর্ডার আইডি', '2023-02-11 23:02:38', '2023-02-11 23:02:38'),
(1385, 'bd', 'date', 'তারিখ', '2023-02-11 23:02:38', '2023-02-11 23:02:38'),
(1386, 'bd', 'customer', 'ক্রেতা', '2023-02-11 23:02:38', '2023-02-11 23:02:38'),
(1387, 'bd', 'total_amount', 'সর্বমোট পরিমাণ', '2023-02-11 23:02:38', '2023-02-11 23:02:38'),
(1388, 'bd', 'action', 'কর্ম', '2023-02-11 23:02:38', '2023-02-11 23:02:38'),
(1389, 'bd', 'nothing_found', 'কিছুই পাওয়া যায়নি', '2023-02-11 23:02:38', '2023-02-11 23:02:38'),
(1390, 'bd', 'top_customers', 'শীর্ষ গ্রাহক', '2023-02-11 23:02:38', '2023-02-11 23:02:38'),
(1391, 'bd', 'top_products', 'শীর্ষ পণ্য', '2023-02-11 23:02:38', '2023-02-11 23:02:38'),
(1392, 'bd', 'top_categories', 'শীর্ষ বিভাগ', '2023-02-11 23:02:38', '2023-02-11 23:02:38'),
(1393, 'bd', 'top_brands', 'শীর্ষ ব্র্যান্ড', '2023-02-11 23:02:38', '2023-02-11 23:02:38'),
(1394, 'bd', 'my_profile', 'আমার প্রোফাইল', '2023-02-11 23:02:38', '2023-02-11 23:02:38'),
(1395, 'bd', 'log_out', 'প্রস্থান', '2023-02-11 23:02:38', '2023-02-11 23:02:38'),
(1396, 'bd', 'clear_cache', 'ক্যাশে সাফ করুন', '2023-02-11 23:02:38', '2023-02-11 23:02:38'),
(1397, 'bd', 'notifications', 'বিজ্ঞপ্তি', '2023-02-11 23:02:38', '2023-02-11 23:02:38'),
(1398, 'bd', 'clear_all', 'সব পরিষ্কার করে দাও', '2023-02-11 23:02:38', '2023-02-11 23:02:38'),
(1399, 'bd', 'media', 'মিডিয়া', '2023-02-11 23:02:38', '2023-02-11 23:02:38'),
(1400, 'bd', 'blog', 'ব্লগ', '2023-02-11 23:02:38', '2023-02-11 23:02:38'),
(1401, 'bd', 'all_blogs', 'সমস্ত ব্লগ', '2023-02-11 23:02:38', '2023-02-11 23:02:38'),
(1402, 'bd', 'add_new_blog', 'নতুন ব্লগ যোগ করুন', '2023-02-11 23:02:38', '2023-02-11 23:02:38'),
(1403, 'bd', 'categories', 'ক্যাটাগরি', '2023-02-11 23:02:38', '2023-02-11 23:02:38'),
(1404, 'bd', 'tags', 'ট্যাগ', '2023-02-11 23:22:34', '2023-02-11 23:22:34'),
(1405, 'bd', 'comments', 'মন্তব্য', '2023-02-11 23:22:34', '2023-02-11 23:22:34'),
(1406, 'bd', 'settings', 'সেটিংস', '2023-02-11 23:22:34', '2023-02-11 23:22:34'),
(1407, 'bd', 'comment_settings', 'মন্তব্য সেটিংস', '2023-02-11 23:22:34', '2023-02-11 23:22:34'),
(1408, 'bd', 'pages', 'পৃষ্ঠা', '2023-02-11 23:22:34', '2023-02-11 23:22:34'),
(1409, 'bd', 'all_pages', 'সমস্ত পৃষ্ঠা', '2023-02-11 23:22:34', '2023-02-11 23:22:34'),
(1410, 'bd', 'add_new_page', 'নতুন পৃষ্ঠা যোগ করুন', '2023-02-11 23:22:34', '2023-02-11 23:22:34'),
(1411, 'bd', 'add_new_product', 'নতুন পণ্য যোগ করুন', '2023-02-11 23:22:34', '2023-02-11 23:22:34'),
(1412, 'bd', 'all_products', 'সব পণ্য', '2023-02-11 23:22:34', '2023-02-11 23:22:34'),
(1413, 'bd', 'colors', 'রং', '2023-02-11 23:22:34', '2023-02-11 23:22:34'),
(1414, 'bd', 'brands', 'ব্র্যান্ড', '2023-02-11 23:22:34', '2023-02-11 23:22:34'),
(1415, 'bd', 'attributes', 'গুণাবলী', '2023-02-11 23:22:34', '2023-02-11 23:22:34'),
(1416, 'bd', 'units', 'ইউনিট', '2023-02-11 23:22:34', '2023-02-11 23:22:34'),
(1417, 'bd', 'product_reviews', 'পণ্য রিভিউ', '2023-02-11 23:22:34', '2023-02-11 23:22:34'),
(1418, 'bd', 'product_collections', 'পণ্য সংগ্রহ', '2023-02-11 23:22:34', '2023-02-11 23:22:34'),
(1419, 'bd', 'product_tags', 'পণ্য ট্যাগ', '2023-02-11 23:22:34', '2023-02-11 23:22:34'),
(1420, 'bd', 'product_conditions', 'পণ্য শর্তাবলী', '2023-02-11 23:22:34', '2023-02-11 23:22:34'),
(1421, 'bd', 'inhouse_orders', 'ইনহাউস অর্ডার', '2023-02-11 23:22:34', '2023-02-11 23:22:34'),
(1422, 'bd', 'pickup_point_order', 'পিকআপ পয়েন্ট অর্ডার', '2023-02-11 23:22:34', '2023-02-11 23:22:34'),
(1423, 'bd', 'addon', 'অ্যাডন', '2023-02-11 23:22:34', '2023-02-11 23:22:34'),
(1424, 'bd', 'shippings', 'শিপিংস', '2023-02-11 23:32:25', '2023-02-11 23:32:25'),
(1425, 'bd', 'shipping__delivery', 'শিপিংস ও ডেলিভারি', '2023-02-11 23:32:25', '2023-02-11 23:32:25'),
(1426, 'bd', 'pickup_points', 'পিকআপ পয়েন্ট', '2023-02-11 23:32:25', '2023-02-11 23:32:25'),
(1427, 'bd', 'carriers', 'বাহক', '2023-02-11 23:32:25', '2023-02-11 23:32:25'),
(1428, 'bd', 'adoon', NULL, '2023-02-11 23:32:25', '2023-02-11 23:32:25'),
(1429, 'bd', 'locations', 'লোকেশোন', '2023-02-11 23:32:25', '2023-02-11 23:32:25'),
(1430, 'bd', 'countries', 'দেশগুলো', '2023-02-11 23:32:25', '2023-02-11 23:32:25'),
(1431, 'bd', 'states', 'রাজ্যগুলি', '2023-02-11 23:32:25', '2023-02-11 23:32:25'),
(1432, 'bd', 'cities', 'শহরগুলো', '2023-02-11 23:32:25', '2023-02-11 23:32:25'),
(1433, 'bd', 'payments', 'পেমেন্ট', '2023-02-11 23:32:25', '2023-02-11 23:32:25'),
(1434, 'bd', 'payment_methods', 'পেমেন্ট মেথোডস', '2023-02-11 23:32:25', '2023-02-11 23:32:25'),
(1435, 'bd', 'transaction_history', 'লেনদেন ইতিহাস', '2023-02-11 23:32:25', '2023-02-11 23:32:25'),
(1436, 'bd', 'marketing', 'মার্কেটিং', '2023-02-11 23:32:25', '2023-02-11 23:32:25'),
(1437, 'bd', 'flash_deals', 'ফ্ল্যাশ ডিল', '2023-02-11 23:32:25', '2023-02-11 23:32:25'),
(1438, 'bd', 'coupons', 'কুপন', '2023-02-11 23:32:25', '2023-02-11 23:32:25'),
(1439, 'bd', 'custom_notification', 'কাস্টম বিজ্ঞপ্তি', '2023-02-11 23:32:25', '2023-02-11 23:32:25'),
(1440, 'bd', 'reports', 'রিপোর্ট', '2023-02-11 23:32:25', '2023-02-11 23:32:40'),
(1441, 'bd', 'product_reports', 'পণ্য রিপোর্ট', '2023-02-11 23:32:25', '2023-02-11 23:32:25'),
(1442, 'bd', 'keyword_search_reports', 'কীওয়ার্ড অনুসন্ধান প্রতিবেদন', '2023-02-11 23:32:25', '2023-02-11 23:32:25'),
(1443, 'bd', 'wishlist_reports', 'ইচ্ছা তালিকা রিপোর্ট', '2023-02-11 23:32:25', '2023-02-11 23:32:25'),
(1444, 'bd', 'ecommerce_settings', 'ইকমার্স সেটিংস', '2023-02-11 23:35:48', '2023-02-11 23:35:48'),
(1445, 'bd', 'taxes', 'কর', '2023-02-11 23:35:48', '2023-02-11 23:35:48'),
(1446, 'bd', 'currencies', 'মুদ্রা', '2023-02-11 23:35:48', '2023-02-11 23:35:48'),
(1447, 'bd', 'product_share_options', 'পণ্য ভাগ করার বিকল্প', '2023-02-11 23:35:48', '2023-02-11 23:35:48'),
(1448, 'bd', 'refunds', 'ফেরত', '2023-02-11 23:35:48', '2023-02-11 23:35:48');
INSERT INTO `tl_translations` (`id`, `lang`, `lang_key`, `lang_value`, `created_at`, `updated_at`) VALUES
(1449, 'bd', 'refund_requests', 'ফেরত অনুরোধ', '2023-02-11 23:35:48', '2023-02-11 23:35:48'),
(1450, 'bd', 'refund_reasons', 'রিফান্ডের কারণ', '2023-02-11 23:35:48', '2023-02-11 23:35:48'),
(1451, 'bd', 'appearances', 'উপস্থিতি', '2023-02-11 23:35:48', '2023-02-11 23:35:48'),
(1452, 'bd', 'themes', 'থিম', '2023-02-11 23:35:48', '2023-02-11 23:35:48'),
(1453, 'bd', 'menus', 'মেনু', '2023-02-11 23:35:48', '2023-02-11 23:35:48'),
(1454, 'bd', 'widgets', 'উইজেট', '2023-02-11 23:35:48', '2023-02-11 23:35:48'),
(1455, 'bd', 'theme_options', 'থিম অপশনগুলি', '2023-02-11 23:35:48', '2023-02-11 23:35:48'),
(1456, 'bd', 'general_settings', 'সাধারণ সেটিংস', '2023-02-11 23:35:48', '2023-02-11 23:35:48'),
(1457, 'bd', 'home_page_builder', 'হোম পেজ নির্মান', '2023-02-11 23:35:48', '2023-02-11 23:35:48'),
(1458, 'bd', 'slider_settings', 'স্লাইডার সেটিংস', '2023-02-11 23:35:48', '2023-02-11 23:35:48'),
(1459, 'bd', 'plugins', 'প্লাগইন', '2023-02-11 23:35:48', '2023-02-11 23:35:48'),
(1460, 'bd', 'email_settings', 'ইমেল সেটিংস', '2023-02-11 23:35:48', '2023-02-11 23:35:48'),
(1461, 'bd', 'email_templates', 'ইমেল টেমপ্লেট', '2023-02-11 23:35:48', '2023-02-11 23:35:48'),
(1462, 'bd', 'languages', 'ভাষা', '2023-02-11 23:35:48', '2023-02-11 23:35:48'),
(1463, 'bd', 'media_settings', 'মিডিয়া সেটিংস', '2023-02-11 23:35:48', '2023-02-11 23:35:48'),
(1464, 'en', 'edit_slider', 'Edit Slider', '2023-02-12 14:54:21', '2023-02-12 14:54:21'),
(1465, 'en', 'slider_updated_successfully', 'Slider updated successfully', '2023-02-12 14:57:00', '2023-02-12 14:57:00'),
(1466, 'en', 'plugin_activate_successfully', 'Plugin activate successfully', '2023-02-12 15:26:12', '2023-02-12 15:26:12'),
(1467, 'en', 'wallet_transactions', 'Wallet Transactions', '2023-02-12 15:26:23', '2023-02-12 15:26:23'),
(1468, 'en', 'offline_payment_methods', 'Offline Payment Methods', '2023-02-12 15:26:23', '2023-02-12 15:26:23'),
(1469, 'en', 'change_status_to_pending', 'Change status to pending', '2023-02-12 15:26:48', '2023-02-12 15:26:48'),
(1470, 'en', 'change_status_to_accept', 'Change status to accept', '2023-02-12 15:26:48', '2023-02-12 15:26:48'),
(1471, 'en', 'change_status_to_decline', 'Change status to decline', '2023-02-12 15:26:48', '2023-02-12 15:26:48'),
(1472, 'en', 'transaction_type', 'Transaction Type', '2023-02-12 15:26:48', '2023-02-12 15:26:48'),
(1473, 'en', 'credited', 'Credited', '2023-02-12 15:26:48', '2023-02-12 15:26:48'),
(1474, 'en', 'debited', 'Debited', '2023-02-12 15:26:48', '2023-02-12 15:26:48'),
(1475, 'en', 'payment_options', 'Payment options', '2023-02-12 15:26:48', '2023-02-12 15:26:48'),
(1476, 'en', 'online', 'Online', '2023-02-12 15:26:48', '2023-02-12 15:26:48'),
(1477, 'en', 'offline', 'Offline', '2023-02-12 15:26:48', '2023-02-12 15:26:48'),
(1478, 'en', 'manual', 'Manual', '2023-02-12 15:26:48', '2023-02-12 15:26:48'),
(1479, 'en', 'cart', 'Cart', '2023-02-12 15:26:48', '2023-02-12 15:26:48'),
(1480, 'en', 'cashback', 'Cashback', '2023-02-12 15:26:48', '2023-02-12 15:26:48'),
(1481, 'en', 'refund', 'Refund', '2023-02-12 15:26:48', '2023-02-12 15:26:48'),
(1482, 'en', 'accept', 'Accept', '2023-02-12 15:26:48', '2023-02-12 15:26:48'),
(1483, 'en', 'declined', 'Declined', '2023-02-12 15:26:48', '2023-02-12 15:26:48'),
(1484, 'en', 'tranaction_id', 'Tranaction Id', '2023-02-12 15:26:48', '2023-02-12 15:26:48'),
(1485, 'en', 'executed_by', 'Executed by', '2023-02-12 15:26:48', '2023-02-12 15:26:48'),
(1486, 'en', 'document', 'Document', '2023-02-12 15:26:48', '2023-02-12 15:26:48'),
(1487, 'en', 'action_applied_successfully', 'Action applied successfully', '2023-02-12 15:26:48', '2023-02-12 15:26:48'),
(1488, 'en', 'semething_wrong', 'Semething wrong', '2023-02-12 15:26:48', '2023-02-12 15:26:48'),
(1489, 'en', 'add_new_payment_method', 'Add New Payment Method', '2023-02-12 15:26:53', '2023-02-12 15:26:53'),
(1490, 'en', 'custom', 'Custom', '2023-02-12 15:26:53', '2023-02-12 15:26:53'),
(1491, 'en', 'bank', 'Bank', '2023-02-12 15:26:53', '2023-02-12 15:26:53'),
(1492, 'en', 'cheque', 'Cheque', '2023-02-12 15:26:53', '2023-02-12 15:26:53'),
(1493, 'en', 'bank_information', 'Bank Information', '2023-02-12 15:26:53', '2023-02-12 15:26:53'),
(1494, 'en', 'bank_name', 'Bank Name', '2023-02-12 15:26:53', '2023-02-12 15:26:53'),
(1495, 'en', 'account_name', 'Account Name', '2023-02-12 15:26:53', '2023-02-12 15:26:53'),
(1496, 'en', 'account_number', 'Account Number', '2023-02-12 15:26:53', '2023-02-12 15:26:53'),
(1497, 'en', 'routing_number', 'Routing Number', '2023-02-12 15:26:53', '2023-02-12 15:26:53'),
(1498, 'en', 'payment_method_information', 'Payment Method Information', '2023-02-12 15:26:53', '2023-02-12 15:26:53'),
(1499, 'en', 'new_method_added_successfully', 'New method added successfully', '2023-02-12 15:26:53', '2023-02-12 15:26:53'),
(1500, 'en', 'new_method_adding_failed_', 'New method adding Failed ', '2023-02-12 15:26:53', '2023-02-12 15:26:53'),
(1501, 'en', 'new_method_adding_failed', 'New method adding Failed', '2023-02-12 15:26:53', '2023-02-12 15:26:53'),
(1502, 'en', 'payment_method_updated_successfully', 'Payment method updated successfully', '2023-02-12 15:26:53', '2023-02-12 15:26:53'),
(1503, 'bd', 'seo_settings', 'এসইও সেটিংস', '2023-02-12 15:53:22', '2023-02-12 15:53:22'),
(1504, 'bd', 'theme_translatios', NULL, '2023-02-12 15:53:22', '2023-02-12 15:53:22'),
(1505, 'bd', 'users', 'ব্যবহারকারী', '2023-02-12 15:53:22', '2023-02-12 15:53:22'),
(1506, 'bd', 'roles', 'রোল', '2023-02-12 15:53:22', '2023-02-12 15:53:22'),
(1507, 'bd', 'permissions', 'পারমিশোন', '2023-02-12 15:53:23', '2023-02-12 15:53:23'),
(1508, 'bd', 'activity_logs', 'একটিভিটি লগ', '2023-02-12 15:53:23', '2023-02-12 15:53:23'),
(1509, 'bd', 'login_activity', NULL, '2023-02-12 15:53:23', '2023-02-12 15:53:23'),
(1510, 'bd', 'update_user', 'ব্যবহারকারী আপডেট করুন', '2023-02-12 15:53:23', '2023-02-12 15:53:23'),
(1511, 'bd', 'update_profile', 'প্রফাইল হালনাগাদ', '2023-02-12 15:53:23', '2023-02-12 15:53:23'),
(1512, 'bd', 'profile_picture', 'প্রোফাইল ছবি', '2023-02-12 15:53:23', '2023-02-12 15:53:23'),
(1513, 'bd', 'choose_image', 'ইমেজ পছন্দ করুন', '2023-02-12 15:53:23', '2023-02-12 15:53:23'),
(1514, 'bd', 'name', 'নাম', '2023-02-12 15:53:23', '2023-02-12 15:53:23'),
(1515, 'bd', 'give_your_name', 'আপনার নাম দিন', '2023-02-12 15:53:23', '2023-02-12 15:53:23'),
(1516, 'bd', 'email', 'ইমেইল', '2023-02-12 15:53:23', '2023-02-12 15:53:23'),
(1517, 'bd', 'give_your_email_address', 'আপনার ইমেইল ঠিকানা দিন', '2023-02-12 15:53:23', '2023-02-12 15:53:23'),
(1518, 'bd', 'old_password', 'পুরানো পাসওয়ার্ড', '2023-02-12 15:53:23', '2023-02-12 15:53:23'),
(1519, 'bd', 'password', 'পাসওয়ার্ড', '2023-02-12 15:53:23', '2023-02-12 15:53:23'),
(1520, 'bd', 'give_your_password', 'আপনার পাসওয়ার্ড দিন', '2023-02-12 15:53:23', '2023-02-12 15:53:23'),
(1521, 'bd', 'confirm_password', 'পাসওয়ার্ড নিশ্চিত করুন', '2023-02-12 15:53:23', '2023-02-12 15:53:23'),
(1522, 'bd', 'confirm_your_password', 'আপনার পাসওয়ার্ড নিশ্চিত করুন', '2023-02-12 15:53:23', '2023-02-12 15:53:23'),
(1523, 'bd', 'update', 'হালনাগাদ', '2023-02-12 15:57:18', '2023-02-12 15:57:18'),
(1524, 'bd', 'media_library', 'মিডিয়া লাইব্রেরি', '2023-02-12 15:57:18', '2023-02-12 15:57:18'),
(1525, 'bd', 'upload_files', 'ফাইল আপলোড', '2023-02-12 15:57:18', '2023-02-12 15:57:18'),
(1526, 'bd', 'click_or_drop_files_here_to_upload', 'আপলোড করতে এখানে ফাইলগুলি ক্লিক করুন বা ড্রপ করুন৷', '2023-02-12 15:57:18', '2023-02-12 15:57:18'),
(1527, 'bd', 'filter_media', 'ফিলটার মিডিয়া', '2023-02-12 15:57:18', '2023-02-12 15:57:18'),
(1528, 'bd', 'all_file_type', 'সমস্ত প্রকার ফাইল', '2023-02-12 15:57:18', '2023-02-12 15:57:18'),
(1529, 'bd', 'all_dates', 'সব তারিখ', '2023-02-12 15:57:18', '2023-02-12 15:57:18'),
(1530, 'bd', 'insert', 'ইনসারট', '2023-02-12 15:57:18', '2023-02-12 15:57:18'),
(1531, 'bd', 'attachment_details', 'সংযুক্তি বিবরণ', '2023-02-12 15:57:18', '2023-02-12 15:57:18'),
(1532, 'bd', 'alt________________________text_', 'বিকল্প পাঠ :', '2023-02-12 15:57:18', '2023-02-12 15:57:18'),
(1533, 'bd', 'title_', 'শিরোনাম :', '2023-02-12 15:57:18', '2023-02-12 15:57:18'),
(1534, 'bd', 'caption_', 'ক্যাপশন :', '2023-02-12 15:57:18', '2023-02-12 15:57:18'),
(1535, 'bd', 'description_', 'বর্ণনা:', '2023-02-12 15:57:18', '2023-02-12 15:57:18'),
(1536, 'bd', 'showing', 'দেখাচ্ছে', '2023-02-12 15:57:18', '2023-02-12 15:57:18'),
(1537, 'bd', 'media_items', 'মিডিয়া আইটেম', '2023-02-12 15:57:18', '2023-02-12 15:57:18'),
(1538, 'bd', 'delete_permanently', 'চিরতরে মুছে দাও', '2023-02-12 15:57:18', '2023-02-12 15:57:18'),
(1539, 'bd', 'file_name', 'ফাইলের নাম:', '2023-02-12 15:57:18', '2023-02-12 15:57:18'),
(1540, 'bd', 'file_url', 'ফাইল URL:', '2023-02-12 15:57:18', '2023-02-12 15:57:18'),
(1541, 'bd', 'file_type', 'ফাইলের ধরন:', '2023-02-12 15:57:18', '2023-02-12 15:57:18'),
(1542, 'bd', 'file_size', 'ফাইলের আকার:', '2023-02-12 15:57:18', '2023-02-12 15:57:18'),
(1543, 'bd', 'uploaded_by', NULL, '2023-02-12 15:59:34', '2023-02-12 15:59:34'),
(1544, 'bd', 'created_at', 'নির্মিত:', '2023-02-12 15:59:34', '2023-02-12 15:59:34'),
(1545, 'bd', 'updated_at', 'আপডেট:', '2023-02-12 15:59:34', '2023-02-12 15:59:34'),
(1546, 'bd', 'download', 'ডাউনলোড', '2023-02-12 15:59:34', '2023-02-12 15:59:34'),
(1547, 'bd', 'copy_url_to_clipboard', 'ক্লিপবোর্ডে URL কপি করুন', '2023-02-12 15:59:34', '2023-02-12 15:59:34'),
(1548, 'bd', 'alt____________________________________________________________________________________text', 'বিকল্প পাঠ', '2023-02-12 15:59:34', '2023-02-12 15:59:34'),
(1549, 'bd', 'title', 'শিরোনাম', '2023-02-12 15:59:34', '2023-02-12 15:59:34'),
(1550, 'bd', 'caption', 'ক্যাপশন', '2023-02-12 15:59:34', '2023-02-12 15:59:34'),
(1551, 'bd', 'description', 'বর্ণনা', '2023-02-12 15:59:34', '2023-02-12 15:59:34'),
(1552, 'bd', 'save', 'সংরক্ষণ', '2023-02-12 15:59:34', '2023-02-12 15:59:34'),
(1553, 'bd', 'login', 'প্রবেশ করুন', '2023-02-12 15:59:34', '2023-02-12 15:59:34'),
(1554, 'bd', 'login_to_dashboard', 'ড্যাশবোর্ডে লগইন করুন', '2023-02-12 15:59:34', '2023-02-12 15:59:34'),
(1555, 'bd', 'email_address', 'Email Address', '2023-02-12 15:59:34', '2023-02-12 15:59:34'),
(1556, 'bd', 'remember_me', 'আমাকে মনে কর', '2023-02-12 15:59:34', '2023-02-12 15:59:34'),
(1557, 'bd', 'sliders', 'স্লাইডার', '2023-02-12 15:59:34', '2023-02-12 15:59:34'),
(1558, 'bd', 'add_new_slider', 'নতুন স্লাইডার যোগ করুন', '2023-02-12 15:59:34', '2023-02-12 15:59:34'),
(1559, 'bd', 'desktop', 'ডেস্কটপ', '2023-02-12 15:59:34', '2023-02-12 15:59:34'),
(1560, 'bd', 'mobile', 'মুঠোফোন', '2023-02-12 15:59:34', '2023-02-12 15:59:34'),
(1561, 'bd', 'status', 'স্ট্যাটাস', '2023-02-12 15:59:34', '2023-02-12 15:59:34'),
(1562, 'bd', 'actions', 'কর্ম', '2023-02-12 16:03:26', '2023-02-12 16:06:38'),
(1563, 'bd', 'delete_confirmation', 'ডিলেট নিশ্চিতকরুণ', '2023-02-12 16:03:26', '2023-02-12 16:03:26'),
(1564, 'bd', 'are_you_sure_to_delete_this', 'আপনি মুছে ফেলার জন্য নিশ্চিত', '2023-02-12 16:03:26', '2023-02-12 16:03:26'),
(1565, 'bd', 'cancel', 'বাতিল', '2023-02-12 16:03:26', '2023-02-12 16:03:26'),
(1566, 'bd', 'delete', 'মুছে ফেলা', '2023-02-12 16:03:26', '2023-02-12 16:03:26'),
(1567, 'bd', 'bulk_action', 'বাল্ক অ্যাকশন', '2023-02-12 16:03:26', '2023-02-12 16:03:26'),
(1568, 'bd', 'delete_selection', 'নির্বাচন মুছুন', '2023-02-12 16:03:26', '2023-02-12 16:03:26'),
(1569, 'bd', 'apply', 'আবেদন করুন', '2023-02-12 16:03:26', '2023-02-12 16:03:26'),
(1570, 'bd', 'no_item_selected', 'কোন আইটেম নির্বাচন করা হয়নি', '2023-02-12 16:03:26', '2023-02-12 16:03:26'),
(1571, 'bd', 'no_action_selected', 'কোনো অ্যাকশন বেছে নেওয়া হয়নি', '2023-02-12 16:03:26', '2023-02-12 16:03:26'),
(1572, 'bd', 'new_slider', 'নতুন স্লাইডার', '2023-02-12 16:03:26', '2023-02-12 16:03:26'),
(1573, 'bd', 'type_title', 'শিরোনাম টাইপ করুন', '2023-02-12 16:03:26', '2023-02-12 16:03:26'),
(1574, 'bd', 'desktop_image', 'ডেস্কটপ ছবি', '2023-02-12 16:03:26', '2023-02-12 16:03:26'),
(1575, 'bd', 'choose_file', 'ফাইল পছন্দ কর', '2023-02-12 16:03:26', '2023-02-12 16:03:26'),
(1576, 'bd', 'mobile_image', 'মোবাইল ইমেজ', '2023-02-12 16:03:26', '2023-02-12 16:03:26'),
(1577, 'bd', 'system_name', 'সিস্টেমের নাম', '2023-02-12 16:03:26', '2023-02-12 16:03:26'),
(1578, 'bd', 'logo', 'লোগো', '2023-02-12 16:03:26', '2023-02-12 16:03:26'),
(1579, 'bd', 'logo_mobile', 'লোগো (মোবাইল)', '2023-02-12 16:03:26', '2023-02-12 16:03:26'),
(1580, 'bd', 'dark_logo', 'ডার্ক লোগো', '2023-02-12 16:03:26', '2023-02-12 16:06:38'),
(1581, 'bd', 'dark_logo_mobile', 'ডার্ক লোগো (মোবাইল)', '2023-02-12 16:06:03', '2023-02-12 16:06:03'),
(1582, 'bd', 'sticky_logo', 'স্টিকি লোগো', '2023-02-12 16:06:03', '2023-02-12 16:06:03'),
(1583, 'bd', 'sticky_logo_mobile', 'স্টিকি লোগো(মোবাইল)', '2023-02-12 16:06:03', '2023-02-12 16:06:03'),
(1584, 'bd', 'dark_sticky_logo', 'ডার্ক স্টিকি লোগো', '2023-02-12 16:06:03', '2023-02-12 16:06:03'),
(1585, 'bd', 'dark_sticky_logo_mobile', 'ডার্ক স্টিকি লোগো(মোবাইল)', '2023-02-12 16:06:03', '2023-02-12 16:06:03'),
(1586, 'bd', 'admin_logo', 'অ্যাডমিন লোগো', '2023-02-12 16:06:03', '2023-02-12 16:06:03'),
(1587, 'bd', 'admin_logo_mobile', 'অ্যাডমিন লোগো (মোবাইল)', '2023-02-12 16:06:03', '2023-02-12 16:06:03'),
(1588, 'bd', 'admin_dark_logo', 'অ্যাডমিন ডার্ক লোগো', '2023-02-12 16:06:03', '2023-02-12 16:06:03'),
(1589, 'bd', 'admin_dark_logo_mobile', 'অ্যাডমিন ডার্ক লোগো (মোবাইল)', '2023-02-12 16:06:03', '2023-02-12 16:06:03'),
(1590, 'bd', 'favicon', 'ফেভিকন', '2023-02-12 16:06:03', '2023-02-12 16:06:03'),
(1591, 'bd', 'default_language', 'নির্ধারিত ভাষা', '2023-02-12 16:06:03', '2023-02-12 16:06:03'),
(1592, 'bd', 'select_default_language', 'ডিফল্ট ভাষা নির্বাচন করুন', '2023-02-12 16:06:03', '2023-02-12 16:06:03'),
(1593, 'bd', 'select_default_timezone', 'ডিফল্ট টাইমজোন নির্বাচন করুন', '2023-02-12 16:06:03', '2023-02-12 16:06:03'),
(1594, 'bd', 'copyright_text', 'কপিরাইট টেক্সট', '2023-02-12 16:06:03', '2023-02-12 16:06:03'),
(1595, 'bd', 'submit', 'জমা দিন', '2023-02-12 16:06:03', '2023-02-12 16:06:03'),
(1596, 'bd', 'general_settings_updated_successfully', 'সাধারণ সেটিংস সফলভাবে আপডেট করা হয়েছে', '2023-02-12 16:06:03', '2023-02-12 16:06:03'),
(1597, 'bd', 'placeholder_image', 'স্থানধারক চিত্র', '2023-02-12 16:06:03', '2023-02-12 16:06:03'),
(1598, 'bd', 'watermark_settings', 'ওয়াটারমার্ক সেটিংস', '2023-02-12 16:06:03', '2023-02-12 16:06:03'),
(1599, 'bd', 'enabledisable_watermark', 'ওয়াটারমার্ক সক্ষম/অক্ষম করুন', '2023-02-12 16:06:03', '2023-02-12 16:06:03'),
(1600, 'bd', 'watermark_image', 'ওয়াটারমার্ক ইমেজ', '2023-02-12 16:06:03', '2023-02-12 16:06:03'),
(1601, 'bd', 'watermark_image_position', 'ওয়াটারমার্ক ইমেজ অবস্থান', '2023-02-12 16:13:35', '2023-02-12 16:13:35'),
(1602, 'bd', 'top_left', 'উপরে বাঁদিকে', '2023-02-12 16:13:35', '2023-02-12 16:13:35'),
(1603, 'bd', 'top', 'উপরে', '2023-02-12 16:13:35', '2023-02-12 16:13:35'),
(1604, 'bd', 'top_right', 'উপরের ডানে', '2023-02-12 16:13:35', '2023-02-12 16:13:35'),
(1605, 'bd', 'left', 'বাম', '2023-02-12 16:13:35', '2023-02-12 16:13:35'),
(1606, 'bd', 'center', 'কেন্দ্র', '2023-02-12 16:13:35', '2023-02-12 16:13:35'),
(1607, 'bd', 'right', 'ডানে', '2023-02-12 16:13:35', '2023-02-12 16:13:35'),
(1608, 'bd', 'bottom_left', 'নিচে বামে', '2023-02-12 16:13:35', '2023-02-12 16:13:35'),
(1609, 'bd', 'watermarking_image_opacity_', 'ওয়াটারমার্কিং ছবির অপাসিটি (%)', '2023-02-12 16:13:35', '2023-02-12 16:13:35'),
(1610, 'bd', 'watermarking_image_opacity', 'ওয়াটারমার্কিং ছবির অপাসিটি', '2023-02-12 16:13:35', '2023-02-12 16:13:35'),
(1611, 'bd', 'media_thumbnails_sizes', 'মিডিয়া থাম্বনেইল আকার', '2023-02-12 16:13:35', '2023-02-12 16:13:35'),
(1612, 'bd', 'large_thumb_image_size', 'বড় থাম্ব ইমেজ সাইজ', '2023-02-12 16:13:35', '2023-02-12 16:13:35'),
(1613, 'bd', 'large_thumb_image_width', 'বড় থাম্ব ইমেজ প্রস্থ', '2023-02-12 16:13:35', '2023-02-12 16:13:35'),
(1614, 'bd', 'large_thumb_image_height', 'বড় থাম্ব ছবির উচ্চতা', '2023-02-12 16:13:35', '2023-02-12 16:13:35'),
(1615, 'bd', 'medium_thumb_image_size', 'মাঝারি থাম্ব ইমেজ সাইজ', '2023-02-12 16:13:35', '2023-02-12 16:13:35'),
(1616, 'bd', 'medium_thumb_image_width', 'মাঝারি থাম্ব ছবির প্রস্থ', '2023-02-12 16:13:35', '2023-02-12 16:13:35'),
(1617, 'bd', 'medium_thumb_image_height', 'মাঝারি থাম্ব ছবির উচ্চতা', '2023-02-12 16:13:35', '2023-02-12 16:13:35'),
(1618, 'bd', 'small_thumb_image_size', 'ছোট থাম্ব ইমেজ সাইজ', '2023-02-12 16:13:35', '2023-02-12 16:13:35'),
(1619, 'bd', 'small_thumb_image_width', 'ছোট থাম্ব ইমেজ প্রস্থ', '2023-02-12 16:13:35', '2023-02-12 16:13:35'),
(1620, 'bd', 'small_thumb_image_height', 'ছোট থাম্ব ইমেজ উচ্চতা', '2023-02-12 16:13:35', '2023-02-12 16:13:35'),
(1621, 'bd', 'select_image_applicable_folder', 'ইমেজ প্রযোজ্য ফোল্ডার নির্বাচন করুন', '2023-02-12 16:16:24', '2023-02-12 16:16:24'),
(1622, 'bd', 'media_settings_updated_successfully', 'মিডিয়া সেটিংস সফলভাবে আপডেট করা হয়েছে', '2023-02-12 16:16:24', '2023-02-12 16:16:24'),
(1623, 'bd', 'add_new_category', 'নতুন বিভাগ যোগ করুন', '2023-02-12 16:16:24', '2023-02-12 16:16:24'),
(1624, 'bd', 'parent', 'অভিভাবক', '2023-02-12 16:16:24', '2023-02-12 16:16:24'),
(1625, 'bd', 'icon', 'আইকন', '2023-02-12 16:16:24', '2023-02-12 16:16:24'),
(1626, 'bd', 'featured', 'বৈশিষ্ট্যযুক্ত', '2023-02-12 16:16:24', '2023-02-12 16:16:24'),
(1627, 'bd', 'new_category', 'নতুন বিভাগ', '2023-02-12 16:16:24', '2023-02-12 16:16:24'),
(1628, 'bd', 'type_here', 'এখানে লিখুন', '2023-02-12 16:16:24', '2023-02-12 16:16:24'),
(1629, 'bd', 'permalink', 'পার্মালিঙ্ক', '2023-02-12 16:16:24', '2023-02-12 16:16:24'),
(1630, 'bd', 'edit', 'সম্পাদনা করুন', '2023-02-12 16:16:24', '2023-02-12 16:16:24'),
(1631, 'bd', 'select_a_category', 'একটি বিভাগ নির্বাচন করুন', '2023-02-12 16:16:24', '2023-02-12 16:16:24'),
(1632, 'bd', 'meta_title', 'মেটা শিরোনাম', '2023-02-12 16:16:24', '2023-02-12 16:16:24'),
(1633, 'bd', 'meta_image', 'মেটা ইমেজ', '2023-02-12 16:16:24', '2023-02-12 16:16:24'),
(1634, 'bd', 'meta_description', 'মেটা বর্ণনা', '2023-02-12 16:16:24', '2023-02-12 16:16:24'),
(1635, 'bd', 'name_is_required', 'নাম আবশ্যক', '2023-02-12 16:16:24', '2023-02-12 16:16:24'),
(1636, 'bd', 'permalink_is_required', 'পার্মালিঙ্ক প্রয়োজন', '2023-02-12 16:16:24', '2023-02-12 16:16:24'),
(1637, 'bd', 'permalink_is_already_exists', 'পার্মালিঙ্ক ইতিমধ্যেই বিদ্যমান', '2023-02-12 16:16:24', '2023-02-12 16:16:24'),
(1638, 'bd', 'selected_parent_does_not_exists', 'নির্বাচিত অভিভাবক বিদ্যমান নেই৷', '2023-02-12 16:16:24', '2023-02-12 16:16:24'),
(1639, 'bd', 'new_category_added_successfully', 'নতুন বিভাগ সফলভাবে যোগ করা হয়েছে', '2023-02-12 16:16:24', '2023-02-12 16:16:24'),
(1640, 'bd', 'edit_category', 'বিভাগ সম্পাদনা করুন', '2023-02-12 16:16:24', '2023-02-12 16:16:24'),
(1641, 'bd', 'category_updated_successfully', 'বিভাগ সফলভাবে আপডেট করা হয়েছে', '2023-02-12 16:24:44', '2023-02-12 16:24:44'),
(1642, 'bd', 'tl_commerce__theme_options', NULL, '2023-02-12 16:24:44', '2023-02-12 16:24:44'),
(1643, 'bd', 'save_changes', 'পরিবর্তনগুলোর সেভ করুন', '2023-02-12 16:24:44', '2023-02-12 16:24:44'),
(1644, 'bd', 'reset_section', 'বিভাগ রিসেট করুন', '2023-02-12 16:24:44', '2023-02-12 16:24:44'),
(1645, 'bd', 'reset_all', 'সব পুনরায় সেট করুন', '2023-02-12 16:24:44', '2023-02-12 16:24:44'),
(1646, 'bd', 'general', 'সাধারণ', '2023-02-12 16:24:44', '2023-02-12 16:24:44'),
(1647, 'bd', 'back_to_top', 'উপরে ফিরে যাও', '2023-02-12 16:24:44', '2023-02-12 16:24:44'),
(1648, 'bd', 'theme_color', 'থিম রঙ', '2023-02-12 16:24:44', '2023-02-12 16:24:44'),
(1649, 'bd', 'typography', 'টাইপোগ্রাফি', '2023-02-12 16:24:44', '2023-02-12 16:24:44'),
(1650, 'bd', 'body_typography', 'বডি টাইপোগ্রাফি', '2023-02-12 16:24:44', '2023-02-12 16:24:44'),
(1651, 'bd', 'paragraph_typography', 'অনুচ্ছেদ টাইপোগ্রাফি', '2023-02-12 16:24:44', '2023-02-12 16:24:44'),
(1652, 'bd', 'heading_typography', 'শিরোনাম টাইপোগ্রাফি', '2023-02-12 16:24:44', '2023-02-12 16:24:44'),
(1653, 'bd', 'menu_typography', 'মেনু টাইপোগ্রাফি', '2023-02-12 16:24:44', '2023-02-12 16:24:44'),
(1654, 'bd', 'button_typography', 'বোতাম টাইপোগ্রাফি', '2023-02-12 16:24:44', '2023-02-12 16:24:44'),
(1655, 'bd', 'custom_fonts', 'কাস্টম ফন্ট', '2023-02-12 16:24:44', '2023-02-12 16:24:44'),
(1656, 'bd', 'header', 'হেডার', '2023-02-12 16:24:44', '2023-02-12 16:24:44'),
(1657, 'bd', 'header_option', 'হেডার বিকল্প', '2023-02-12 16:24:44', '2023-02-12 16:24:44'),
(1658, 'bd', 'header_logo', 'হেডার লোগো', '2023-02-12 16:24:44', '2023-02-12 16:24:44'),
(1659, 'bd', 'menu', 'তালিকা', '2023-02-12 16:24:44', '2023-02-12 16:24:44'),
(1660, 'bd', 'blog_option', 'ব্লগ বিকল্প', '2023-02-12 16:24:44', '2023-02-12 16:24:44'),
(1661, 'bd', 'single_blog_page', 'একক ব্লগ পাতা', '2023-02-12 16:27:18', '2023-02-12 16:27:18'),
(1662, 'bd', 'sidebar_options', 'সাইডবার বিকল্প', '2023-02-12 16:27:18', '2023-02-12 16:27:18'),
(1663, 'bd', '404_page', '৪০৪ পৃষ্ঠা', '2023-02-12 16:27:18', '2023-02-12 16:27:18'),
(1664, 'bd', 'subscribe', 'সাবস্ক্রাইব', '2023-02-12 16:27:18', '2023-02-12 16:27:18'),
(1665, 'bd', 'social', 'সোশাল', '2023-02-12 16:27:18', '2023-02-12 16:27:18'),
(1666, 'bd', 'footer', 'ফুটার', '2023-02-12 16:27:18', '2023-02-12 16:27:18'),
(1667, 'bd', 'custom_css', 'কাস্টম সিএসএস', '2023-02-12 16:27:18', '2023-02-12 16:27:18'),
(1668, 'bd', 'reset_confirmation', 'নিশ্চিতকরণ রিসেট করুন', '2023-02-12 16:27:18', '2023-02-12 16:27:18'),
(1669, 'bd', 'are_you_sure_to_want_to_reset', 'আপনি কি নিশ্চিত রিসেট করতে চান৷', '2023-02-12 16:27:18', '2023-02-12 16:27:18'),
(1670, 'bd', 'action_failed', 'অ্যাকশন ব্যর্থ হয়েছে৷', '2023-02-12 16:27:18', '2023-02-12 16:27:18'),
(1671, 'bd', 'select_font_subsets', 'ফন্ট সাবসেট নির্বাচন করুন', '2023-02-12 16:27:18', '2023-02-12 16:27:18'),
(1672, 'bd', 'select_weight__style', 'ওজন এবং শৈলী নির্বাচন করুন', '2023-02-12 16:27:18', '2023-02-12 16:27:18'),
(1673, 'bd', 'new_slide', 'নতুন স্লাইড', '2023-02-12 16:27:18', '2023-02-12 16:27:18'),
(1674, 'bd', 'iconexample_fa_fafacebook', 'আইকন (উদাহরণ: fa fa-facebook)', '2023-02-12 16:27:18', '2023-02-12 16:27:18'),
(1675, 'bd', 'url', 'ইউআরএল', '2023-02-12 16:27:18', '2023-02-12 16:27:18'),
(1676, 'bd', 'back_to_top_button', 'ব্যাক টু টপ বোতাম', '2023-02-12 16:27:18', '2023-02-12 16:27:18'),
(1677, 'bd', 'switch_on_to_display_back_to_top_button', 'উপরের বোতামে ফিরে প্রদর্শনে স্যুইচ অন করুন।', '2023-02-12 16:27:18', '2023-02-12 16:27:18'),
(1678, 'bd', 'custom_back_to_top_button', 'কাস্টম ব্যাক টু টপ বোতাম', '2023-02-12 16:27:18', '2023-02-12 16:27:18'),
(1679, 'bd', 'if_you_switch_it_off_it_will_show_default_design_for_back_to_top_button', 'আপনি যদি এটি বন্ধ করেন, এটি \"ব্যাক টু টপ\" বোতামের জন্য ডিফল্ট ডিজাইন দেখাবে।', '2023-02-12 16:27:18', '2023-02-12 16:27:18'),
(1680, 'bd', 'custom_back_to_top_button_icon', 'কাস্টম ব্যাক টু টপ বাটন আইকন', '2023-02-12 16:27:18', '2023-02-12 16:27:18'),
(1681, 'bd', 'select_back_to_top_button_icon', 'ব্যাক টু টপ বাটন আইকন নির্বাচন করুন।', '2023-02-12 16:29:36', '2023-02-12 16:29:36'),
(1682, 'bd', 'back_to_top_button_background_color', 'ব্যাক টু টপ বোতাম ব্যাকগ্রাউন্ড কালার', '2023-02-12 16:29:36', '2023-02-12 16:29:36'),
(1683, 'bd', 'set_back_to_top_button_background_color', 'উপরের বোতামে ফিরে যান পটভূমির রঙ।', '2023-02-12 16:29:36', '2023-02-12 16:29:36'),
(1684, 'bd', 'select_color', 'রঙ নির্বাচন করুন', '2023-02-12 16:29:36', '2023-02-12 16:29:36'),
(1685, 'bd', 'transparent', 'স্বচ্ছ', '2023-02-12 16:29:36', '2023-02-12 16:29:36'),
(1686, 'bd', 'back_to_top_button_color', 'উপরের বোতামের রঙে ফিরে যান', '2023-02-12 16:29:36', '2023-02-12 16:29:36'),
(1687, 'bd', 'set_back_to_top_button_color', 'উপরের বোতামের রঙে ফিরে যান।', '2023-02-12 16:29:36', '2023-02-12 16:29:36'),
(1688, 'bd', 'back_to_top_hover_button_color', 'উপরে ফিরে যান বোতামের রঙ', '2023-02-12 16:29:36', '2023-02-12 16:29:36'),
(1689, 'bd', 'back_to_top_button_hover_background_color', 'ব্যাক টু টপ বোতাম হোভার ব্যাকগ্রাউন্ড কালার', '2023-02-12 16:29:36', '2023-02-12 16:29:36'),
(1690, 'bd', 'set_back_to_top_button_hover_background_color', 'উপরের বোতাম হোভার ব্যাকগ্রাউন্ড রঙ সেট করুন।', '2023-02-12 16:29:36', '2023-02-12 16:29:36'),
(1691, 'bd', 'add_new_brand', 'নতুন ব্র্যান্ড যোগ করুন', '2023-02-12 16:29:36', '2023-02-12 16:29:36'),
(1692, 'bd', 'new_brand', 'নতুন ব্র্যান্ড', '2023-02-12 16:29:36', '2023-02-12 16:29:36'),
(1693, 'bd', 'invalid_logo', 'অবৈধ লোগো', '2023-02-12 16:29:36', '2023-02-12 16:29:36'),
(1694, 'bd', 'invalid_meta_image', 'অবৈধ মেটা ছবি', '2023-02-12 16:29:36', '2023-02-12 16:29:36'),
(1695, 'bd', 'new_brand_added_successfully', 'নতুন ব্র্যান্ড সফলভাবে যোগ করা হয়েছে', '2023-02-12 16:29:36', '2023-02-12 16:29:36'),
(1696, 'bd', 'add_new_color', 'নতুন রঙ যোগ করুন', '2023-02-12 16:29:36', '2023-02-12 16:29:36'),
(1697, 'bd', 'code', 'কোড', '2023-02-12 16:29:36', '2023-02-12 16:29:36'),
(1698, 'bd', 'new_color', 'নতুন রঙ', '2023-02-12 16:29:36', '2023-02-12 16:29:36'),
(1699, 'bd', 'tl_commerce__blog_category', NULL, '2023-02-12 16:29:36', '2023-02-12 16:29:36'),
(1700, 'bd', 'blog_categories', 'ব্লগ বিভাগ', '2023-02-12 16:29:36', '2023-02-12 16:29:36'),
(1701, 'bd', 'add_blog_category', 'ব্লগ বিভাগ যোগ করুন', '2023-02-12 16:33:50', '2023-02-12 16:33:50'),
(1702, 'bd', 'all', 'সব', '2023-02-12 16:33:50', '2023-02-12 16:33:50'),
(1703, 'bd', 'items_of', 'আইটেম', '2023-02-12 16:33:50', '2023-02-12 16:33:50'),
(1704, 'bd', 'add_new_attribute', 'নতুন বৈশিষ্ট্য যোগ করুন', '2023-02-12 16:33:50', '2023-02-12 16:33:50'),
(1705, 'bd', 'values', 'মূল্যবোধ', '2023-02-12 16:33:50', '2023-02-12 16:33:50'),
(1706, 'bd', 'code_is_required', 'কোড প্রয়োজন', '2023-02-12 16:33:50', '2023-02-12 16:33:50'),
(1707, 'bd', 'new_color_added_successfully', 'নতুন রঙ সফলভাবে যোগ করা হয়েছে', '2023-02-12 16:33:50', '2023-02-12 16:33:50'),
(1708, 'bd', 'new_attribute', 'নতুন বৈশিষ্ট্য', '2023-02-12 16:33:50', '2023-02-12 16:33:50'),
(1709, 'bd', 'add_new_unit', 'নতুন ইউনিট যোগ করুন', '2023-02-12 16:33:50', '2023-02-12 16:33:50'),
(1710, 'bd', 'new_units', 'নতুন ইউনিট', '2023-02-12 16:33:50', '2023-02-12 16:33:50'),
(1711, 'bd', 'add_new_tag', 'নতুন ট্যাগ যোগ করুন', '2023-02-12 16:33:50', '2023-02-12 16:33:50'),
(1712, 'bd', 'new_tag', 'নতুন ট্যাগ', '2023-02-12 16:33:50', '2023-02-12 16:33:50'),
(1713, 'bd', 'new_tag_added_successfully', 'নতুন ট্যাগ সফলভাবে যোগ করা হয়েছে৷', '2023-02-12 16:33:50', '2023-02-12 16:33:50'),
(1714, 'bd', 'conditions', 'শর্তাবলী', '2023-02-12 16:33:50', '2023-02-12 16:33:50'),
(1715, 'bd', 'add_new_condition', 'নতুন শর্ত যোগ করুন', '2023-02-12 16:33:50', '2023-02-12 16:33:50'),
(1716, 'bd', 'product_information', 'পণ্যের তথ্য', '2023-02-12 16:33:50', '2023-02-12 16:33:50'),
(1717, 'bd', 'product_name', 'পণ্যের নাম', '2023-02-12 16:33:50', '2023-02-12 16:33:50'),
(1718, 'bd', 'brand', 'ব্র্যান্ড', '2023-02-12 16:33:50', '2023-02-12 16:33:50'),
(1719, 'bd', 'unit', 'ইউনিট', '2023-02-12 16:33:50', '2023-02-12 16:33:50'),
(1720, 'bd', 'condition', 'অবস্থা', '2023-02-12 16:33:50', '2023-02-12 16:33:50'),
(1721, 'bd', 'product_type', 'পণ্যের ধরন', '2023-02-12 16:48:52', '2023-02-12 16:48:52'),
(1722, 'bd', 'single_product', 'একক পণ্য', '2023-02-12 16:48:52', '2023-02-12 16:48:52'),
(1723, 'bd', 'variant_product', 'ভেরিয়েনট পণ্য', '2023-02-12 16:48:52', '2023-02-12 16:48:52'),
(1724, 'bd', 'product_variation', 'পণ্যের ভেরিয়েশন', '2023-02-12 16:48:52', '2023-02-12 16:48:52'),
(1725, 'bd', 'choice_options', 'চয়েস অপশন', '2023-02-12 16:48:52', '2023-02-12 16:48:52'),
(1726, 'bd', 'product_price_and_stock', 'পণ্যের মূল্য এবং স্টক', '2023-02-12 16:48:52', '2023-02-12 16:48:52'),
(1727, 'bd', 'purchase_price', 'ক্রয় মূল্য', '2023-02-12 16:48:52', '2023-02-12 16:48:52'),
(1728, 'bd', 'unit_price', 'একক দাম', '2023-02-12 16:48:52', '2023-02-12 16:48:52'),
(1729, 'bd', 'quantity', 'পরিমাণ', '2023-02-12 16:48:52', '2023-02-12 16:48:52'),
(1730, 'bd', 'sku', 'স্কু', '2023-02-12 16:48:52', '2023-02-12 16:48:52'),
(1731, 'bd', 'type_product_sku', 'টাইপ পণ্য স্কু', '2023-02-12 16:48:52', '2023-02-12 16:48:52'),
(1732, 'bd', 'no_variant_selected_yet', 'এখনও কোন ভেরিয়েনট নির্বাচন করা হয়নি', '2023-02-12 16:48:52', '2023-02-12 16:48:52'),
(1733, 'bd', 'product_discount', 'পণ্য ছাড়', '2023-02-12 16:48:52', '2023-02-12 16:48:52'),
(1734, 'bd', 'discount', 'ছাড়', '2023-02-12 16:48:52', '2023-02-12 16:48:52'),
(1735, 'bd', 'flat', 'সমান', '2023-02-12 16:48:52', '2023-02-12 16:48:52'),
(1736, 'bd', 'percentage', 'শতাংশ', '2023-02-12 16:48:52', '2023-02-12 16:48:52'),
(1737, 'bd', 'color_variation_images', 'রঙ ভেরিয়েনট ইমেজ', '2023-02-12 16:48:52', '2023-02-12 16:48:52'),
(1738, 'bd', 'no_color_variant_selected_yet', 'এখনও কোন রঙের ভেরিয়েনট নির্বাচন করা হয়নি', '2023-02-12 16:48:52', '2023-02-12 16:48:52'),
(1739, 'bd', 'product_description', 'পণ্যের বর্ণনা', '2023-02-12 16:48:52', '2023-02-12 16:48:52'),
(1740, 'bd', 'summary', 'সারসংক্ষেপ', '2023-02-12 16:48:52', '2023-02-12 16:48:52'),
(1741, 'bd', 'product_images', 'পণ্য ইমেজ', '2023-02-12 16:52:15', '2023-02-12 16:52:15'),
(1742, 'bd', 'thumbnail_image', 'থাম্বনেইল ছবি', '2023-02-12 16:52:15', '2023-02-12 16:52:15'),
(1743, 'bd', 'gallery_images', 'গ্যালারি ইমেজ', '2023-02-12 16:52:15', '2023-02-12 16:52:15'),
(1744, 'bd', 'choose_files', 'ফাইল বেছে নিন', '2023-02-12 16:52:15', '2023-02-12 16:52:15'),
(1745, 'bd', 'pdf_specification', 'পিডিএফ স্পেসিফিকেশন', '2023-02-12 16:52:15', '2023-02-12 16:52:15'),
(1746, 'bd', 'product_video', 'পণ্য ভিডিও', '2023-02-12 16:52:15', '2023-02-12 16:52:15'),
(1747, 'bd', 'youtube_link', 'ইউটিউব লিংক', '2023-02-12 16:52:15', '2023-02-12 16:52:15'),
(1748, 'bd', 'seo_meta_tags', 'এসইও মেটা ট্যাগ', '2023-02-12 16:52:15', '2023-02-12 16:52:15'),
(1749, 'bd', 'refundable', 'ফেরতযোগ্য', '2023-02-12 16:52:15', '2023-02-12 16:52:15'),
(1750, 'bd', 'authentic', 'প্রামাণিক', '2023-02-12 16:52:15', '2023-02-12 16:52:15'),
(1751, 'bd', 'shipping_information', 'হস্তান্তর তথ্য', '2023-02-12 16:52:15', '2023-02-12 16:52:15'),
(1752, 'bd', 'weight', 'ওজন', '2023-02-12 16:52:15', '2023-02-12 16:52:15'),
(1753, 'bd', 'height', 'উচ্চতা', '2023-02-12 16:52:15', '2023-02-12 16:52:15'),
(1754, 'bd', 'length', 'দৈর্ঘ্য', '2023-02-12 16:52:15', '2023-02-12 16:52:15'),
(1755, 'bd', 'width', 'প্রস্থ', '2023-02-12 16:52:15', '2023-02-12 16:52:15'),
(1756, 'bd', 'shipping_profile', 'শিপিং প্রোফাইল', '2023-02-12 16:52:15', '2023-02-12 16:52:15'),
(1757, 'bd', 'cash_on_delivery', 'কেশ অন ডেলিভারি', '2023-02-12 16:52:15', '2023-02-12 16:52:15'),
(1758, 'bd', 'anywhere', 'যে কোন জায়গায়', '2023-02-12 16:52:15', '2023-02-12 16:52:15'),
(1759, 'bd', 'custom_locations', 'কাস্টম অবস্থান', '2023-02-12 16:52:16', '2023-02-12 16:52:16'),
(1760, 'bd', 'manage_taxes', 'ট্যাক্স পরিচালনা করুন', '2023-02-12 16:52:16', '2023-02-12 16:52:16'),
(1761, 'bd', 'warranty', 'ওয়ারেন্টি', '2023-02-12 16:56:48', '2023-02-12 16:56:48'),
(1762, 'bd', 'replacement_warranty', 'প্রতিস্থাপন ওয়্যারেন্টি', '2023-02-12 16:56:48', '2023-02-12 16:56:48'),
(1763, 'bd', 'warranty_days', 'ওয়ারেন্টি দিন', '2023-02-12 16:56:48', '2023-02-12 16:56:48'),
(1764, 'bd', 'low_stock_quantity', 'কম স্টক পরিমাণ', '2023-02-12 16:56:48', '2023-02-12 16:56:48'),
(1765, 'bd', 'purchase_quantity', 'ক্রয় পরিমাণ', '2023-02-12 16:56:48', '2023-02-12 16:56:48'),
(1766, 'bd', 'minimum_quantity', 'ন্যূনতম পরিমাণ', '2023-02-12 16:56:48', '2023-02-12 16:56:48'),
(1767, 'bd', 'miximum_quantity', 'সর্বোচ্চ পরিমাণ', '2023-02-12 16:56:48', '2023-02-12 16:56:48'),
(1768, 'bd', 'attatchment_on_purchase', 'ক্রয় উপর সংযুক্তি', '2023-02-12 16:56:48', '2023-02-12 16:56:48'),
(1769, 'bd', 'attatchment_name', 'সংযুক্তির নাম', '2023-02-12 16:56:48', '2023-02-12 16:56:48'),
(1770, 'bd', 'no_collection_avaible', 'কোন সংগ্রহ পাওয়া যায় না', '2023-02-12 16:56:48', '2023-02-12 16:56:48'),
(1771, 'bd', 'add_new_collection', 'নতুন সংগ্রহ যোগ করুন', '2023-02-12 16:56:48', '2023-02-12 16:56:48'),
(1772, 'bd', 'save__draft', 'সংরক্ষণ এবং খসড়া', '2023-02-12 16:56:48', '2023-02-12 16:56:48'),
(1773, 'bd', 'save__publish', 'সংরক্ষণ করুন এবং প্রকাশ করুন', '2023-02-12 16:56:48', '2023-02-12 16:56:48'),
(1774, 'bd', 'select_choice_option', 'চয়েস অপশন সিলেক্ট করুন', '2023-02-12 16:56:48', '2023-02-12 16:56:48'),
(1775, 'bd', 'select_product_category', 'পণ্য বিভাগ নির্বাচন করুন', '2023-02-12 16:56:48', '2023-02-12 16:56:48'),
(1776, 'bd', 'select_product_brand', 'পণ্য ব্র্যান্ড নির্বাচন করুন', '2023-02-12 16:56:48', '2023-02-12 16:56:48'),
(1777, 'bd', 'select_product_unit', 'পণ্য ইউনিট নির্বাচন করুন', '2023-02-12 16:56:48', '2023-02-12 16:56:48'),
(1778, 'bd', 'select_product_condition', 'পণ্য শর্ত নির্বাচন করুন', '2023-02-12 16:56:48', '2023-02-12 16:56:48'),
(1779, 'bd', 'select_or_insert_product_tags', 'পণ্য ট্যাগ নির্বাচন করুন', '2023-02-12 16:56:48', '2023-02-12 16:56:48'),
(1780, 'bd', 'nothing_selected', 'কিছুই নির্বাচিত নয়', '2023-02-12 16:56:48', '2023-02-12 16:56:48'),
(1781, 'bd', 'attribute_added_successfully', 'অ্যাট্রিবিউট সফলভাবে যোগ করা হয়েছে', '2023-02-12 17:00:14', '2023-02-12 17:00:14'),
(1782, 'bd', 'tl_commerce__add_blog', NULL, '2023-02-12 17:00:14', '2023-02-12 17:00:14'),
(1783, 'bd', 'add_blog', 'ব্লগ যোগ করুন', '2023-02-12 17:00:14', '2023-02-12 17:00:14'),
(1784, 'bd', 'short_description', 'ছোট বিবরণ', '2023-02-12 17:00:14', '2023-02-12 17:00:14'),
(1785, 'bd', 'content', 'বিষয়বস্তু', '2023-02-12 17:00:14', '2023-02-12 17:00:14'),
(1786, 'bd', 'publish', 'প্রকাশ করুন', '2023-02-12 17:00:14', '2023-02-12 17:00:14'),
(1787, 'bd', 'draft', 'খসড়া', '2023-02-12 17:00:14', '2023-02-12 17:00:14'),
(1788, 'bd', 'preview', 'পূর্বরূপ', '2023-02-12 17:00:14', '2023-02-12 17:00:14'),
(1789, 'bd', 'blog_image', 'ব্লগ ইমেজ', '2023-02-12 17:00:14', '2023-02-12 17:00:14'),
(1790, 'bd', 'blog_status', 'ব্লগ স্ট্যাটাস', '2023-02-12 17:00:14', '2023-02-12 17:00:14'),
(1791, 'bd', 'featured_status', 'বৈশিষ্ট্যযুক্ত স্থিতি', '2023-02-12 17:00:14', '2023-02-12 17:00:14'),
(1792, 'bd', 'no_option_selected', 'কোন বিকল্প নির্বাচন করা হয়নি', '2023-02-12 17:00:14', '2023-02-12 17:00:14'),
(1793, 'bd', 'add', 'যোগ করুন', '2023-02-12 17:00:14', '2023-02-12 17:00:14'),
(1794, 'bd', 'only_active_categories', 'শুধুমাত্র সক্রিয় বিভাগ', '2023-02-12 17:00:14', '2023-02-12 17:00:14'),
(1795, 'bd', 'select_parent', 'অভিভাবক নির্বাচন করুন', '2023-02-12 17:00:14', '2023-02-12 17:00:14'),
(1796, 'bd', 'select_a_parent_category', 'একটি অভিভাবক বিভাগ নির্বাচন করুন', '2023-02-12 17:00:14', '2023-02-12 17:00:14'),
(1797, 'bd', 'load_more', 'আরো লোড', '2023-02-12 17:00:14', '2023-02-12 17:00:14'),
(1798, 'bd', 'per_page', 'প্রতি পাতা', '2023-02-12 17:00:14', '2023-02-12 17:00:14'),
(1799, 'bd', 'product_status', 'পণ্যের অবস্থা', '2023-02-12 17:00:14', '2023-02-12 17:00:14'),
(1800, 'bd', 'published', 'প্রকাশিত হয়েছে', '2023-02-12 17:00:14', '2023-02-12 17:00:14'),
(1801, 'bd', 'unpublished', 'অপ্রকাশিত', '2023-02-12 17:02:09', '2023-02-12 17:02:09'),
(1802, 'bd', 'product_featured', 'পণ্য বৈশিষ্ট্যযুক্ত', '2023-02-12 17:02:09', '2023-02-12 17:02:09'),
(1803, 'bd', 'regular', 'নিয়মিত', '2023-02-12 17:02:09', '2023-02-12 17:02:09'),
(1804, 'bd', 'no_discount', 'কোন ছাড় নেই', '2023-02-12 17:02:09', '2023-02-12 17:02:09'),
(1805, 'bd', 'discounted', 'ছাড়', '2023-02-12 17:02:09', '2023-02-12 17:02:09'),
(1806, 'bd', 'filter', 'ফিল্টার', '2023-02-12 17:02:09', '2023-02-12 17:02:09'),
(1807, 'bd', 'make_publish', 'প্রকাশ করুন', '2023-02-12 17:02:09', '2023-02-12 17:02:09'),
(1808, 'bd', 'make_unpublish', 'অপ্রকাশিত করুন', '2023-02-12 17:02:09', '2023-02-12 17:02:09'),
(1809, 'bd', 'make_feature', 'মেক ফিচার', '2023-02-12 17:02:09', '2023-02-12 17:02:09'),
(1810, 'bd', 'remove_from_feature', 'বৈশিষ্ট্য থেকে সরান', '2023-02-12 17:02:09', '2023-02-12 17:02:09'),
(1811, 'bd', 'remove_discount', 'ডিসকাউন্ট সরান', '2023-02-12 17:02:09', '2023-02-12 17:02:09'),
(1812, 'bd', 'image', 'ছবি', '2023-02-12 17:02:09', '2023-02-12 17:02:09'),
(1813, 'bd', 'info', 'ছবি', '2023-02-12 17:02:09', '2023-02-12 17:02:09'),
(1814, 'bd', 'stock__sales', 'স্টক ও বিক্রয়', '2023-02-12 17:02:09', '2023-02-12 17:02:09'),
(1815, 'bd', 'update_product_information', 'আপডেট পণ্য তথ্য', '2023-02-12 17:02:09', '2023-02-12 17:02:09'),
(1816, 'bd', 'processing', 'প্রক্রিয়াকরণ', '2023-02-12 17:02:09', '2023-02-12 17:02:09'),
(1817, 'bd', 'to_shipped', 'পাঠানো হয়েছে', '2023-02-12 17:02:09', '2023-02-12 17:02:09'),
(1818, 'bd', 'unpaid', 'অবৈতনিক', '2023-02-12 17:02:09', '2023-02-12 17:02:09'),
(1819, 'bd', 'paid', 'প্রদান করা হয়েছে', '2023-02-12 17:02:09', '2023-02-12 17:02:09'),
(1820, 'bd', 'change_status_to_processing', 'প্রক্রিয়াকরণে স্থিতি পরিবর্তন করুন', '2023-02-12 17:02:09', '2023-02-12 17:02:09'),
(1821, 'bd', 'change_status_to_ready_to_ship', 'প্রস্তুত থেকে শিপ এ স্ট্যাটাস পরিবর্তন করুন', '2023-02-12 17:09:22', '2023-02-12 17:09:22'),
(1822, 'bd', 'change_status_to_shipped', 'শিপে স্ট্যাটাস পরিবর্তন করুন', '2023-02-12 17:09:22', '2023-02-12 17:09:22'),
(1823, 'bd', 'change_status_to_delivered', 'ডেলিভারিতে স্ট্যাটাস পরিবর্তন করুন', '2023-02-12 17:09:22', '2023-02-12 17:09:22'),
(1824, 'bd', 'change_status_to_paid', 'পেইড স্ট্যাটাস পরিবর্তন করুন', '2023-02-12 17:09:22', '2023-02-12 17:09:22'),
(1825, 'bd', 'change_status_to_unpaid', 'আনপেইড স্ট্যাটাস পরিবর্তন করুন', '2023-02-12 17:09:22', '2023-02-12 17:09:22'),
(1826, 'bd', 'move_to_trash', 'ট্র্যাশে সরান', '2023-02-12 17:09:22', '2023-02-12 17:09:22'),
(1827, 'bd', 'delivery_status', 'ডেলিভারি স্ট্যাটাস', '2023-02-12 17:09:22', '2023-02-12 17:09:22'),
(1828, 'bd', 'payment_status', 'পেমেন্ট স্ট্যাটাস', '2023-02-12 17:09:22', '2023-02-12 17:09:22'),
(1829, 'bd', 'order_code', 'অর্ডার কোড', '2023-02-12 17:09:22', '2023-02-12 17:09:22'),
(1830, 'bd', 'order_date', 'অর্ডারের তারিখ', '2023-02-12 17:09:22', '2023-02-12 17:09:22'),
(1831, 'bd', 'num_of_products', 'পণ্যের সংখ্যা', '2023-02-12 17:09:22', '2023-02-12 17:09:22'),
(1832, 'bd', 'amount', 'পরিমাণ', '2023-02-12 17:09:23', '2023-02-12 17:09:23'),
(1833, 'bd', 'order_status', 'অর্ডার স্ট্যাটাস', '2023-02-12 17:09:23', '2023-02-12 17:09:23'),
(1834, 'bd', 'update_order_status', 'আপডেট অর্ডার স্থিতি', '2023-02-12 17:09:23', '2023-02-12 17:09:23'),
(1835, 'bd', 'cancel_confirmation', 'নিশ্চিতকরণ বাতিল করুন', '2023-02-12 17:09:23', '2023-02-12 17:09:23'),
(1836, 'bd', 'are_you_sure_to_cancel__this_order', 'নিশ্চিতকরণ বাতিল করুন', '2023-02-12 17:09:23', '2023-02-12 17:09:23'),
(1837, 'bd', 'confirm', 'নিশ্চিত করুন', '2023-02-12 17:09:23', '2023-02-12 17:09:23'),
(1838, 'bd', 'accept_confirmation', 'নিশ্চিতকরণ গ্রহণ করুন', '2023-02-12 17:09:23', '2023-02-12 17:09:23'),
(1839, 'bd', 'are_you_sure_to_accept__this_order', 'আপনি কি নিশ্চিত এই আদেশটি গ্রহণ করবেন', '2023-02-12 17:09:23', '2023-02-12 17:09:23'),
(1840, 'bd', 'active', 'সক্রিয়', '2023-02-12 17:09:23', '2023-02-12 17:09:23'),
(1841, 'bd', 'inactive', 'নিষ্ক্রিয়', '2023-02-12 17:11:48', '2023-02-12 17:11:48'),
(1842, 'bd', 'clear_filter', 'ফিল্টার পরিষ্কার', '2023-02-12 17:11:48', '2023-02-12 17:11:48'),
(1843, 'bd', 'uid', NULL, '2023-02-12 17:11:48', '2023-02-12 17:11:48'),
(1844, 'bd', 'phone', 'ফোন', '2023-02-12 17:11:48', '2023-02-12 17:11:48'),
(1845, 'bd', 'no_of_order', 'আদেশ সংখ্যা', '2023-02-12 17:11:48', '2023-02-12 17:11:48'),
(1846, 'bd', 'are_you_sure_to_delete_this_customer', 'আপনি এই গ্রাহক মুছে ফেলার বিষয়ে নিশ্চিত', '2023-02-12 17:11:48', '2023-02-12 17:11:48'),
(1847, 'bd', 'reset_password', 'পাসওয়ার্ড রিসেট করুন', '2023-02-12 17:11:48', '2023-02-12 17:11:48'),
(1848, 'bd', 'new_password', 'নতুন পাসওয়ার্ড', '2023-02-12 17:11:48', '2023-02-12 17:11:48'),
(1849, 'bd', 'enter_new_password', 'নতুন পাসওয়ার্ড লিখুন', '2023-02-12 17:11:48', '2023-02-12 17:11:48'),
(1850, 'bd', 'customer_information', 'গ্রাহকের তথ্য', '2023-02-12 17:11:48', '2023-02-12 17:11:48'),
(1851, 'bd', 'password_updated_successfully', 'পাসওয়ার্ড সফলভাবে আপডেট করা হয়েছে', '2023-02-12 17:11:48', '2023-02-12 17:11:48'),
(1852, 'bd', 'update_failed_', 'আপডেট ব্যর্থ হয়েছে', '2023-02-12 17:11:48', '2023-02-12 17:11:48'),
(1853, 'bd', 'customer_updated_successfully', 'গ্রাহক সফলভাবে আপডেট হয়েছে', '2023-02-12 17:11:48', '2023-02-12 17:11:48'),
(1854, 'bd', 'login_failed_', 'লগইন ব্যর্থ হয়েছে', '2023-02-12 17:11:48', '2023-02-12 17:11:48'),
(1855, 'bd', 'edit_brand', 'ব্র্যান্ড সম্পাদনা করুন', '2023-02-12 17:11:48', '2023-02-12 17:11:48'),
(1856, 'bd', 'brand_updated_successfully', 'ব্র্যান্ড সফলভাবে আপডেট হয়েছে', '2023-02-12 17:11:48', '2023-02-12 17:11:48'),
(1857, 'bd', 'category_status_updated_successfully', 'বিভাগের স্থিতি সফলভাবে আপডেট হয়েছে৷', '2023-02-12 17:11:48', '2023-02-12 17:11:48'),
(1858, 'bd', 'cache_clear_successfully', 'ক্যাশে সফলভাবে পরিষ্কার করা হয়েছে', '2023-02-12 17:11:48', '2023-02-12 17:11:48'),
(1859, 'bd', 'category_deleted_successfully', 'বিভাগ সফলভাবে মুছে ফেলা হয়েছে', '2023-02-12 17:11:48', '2023-02-12 17:11:48'),
(1860, 'bd', 'tl_commerce__blog', NULL, '2023-02-12 17:11:48', '2023-02-12 17:11:48'),
(1861, 'bd', 'mine', 'আমার', '2023-02-12 17:14:30', '2023-02-12 17:14:30'),
(1862, 'bd', 'scheduled', 'নির্ধারিত', '2023-02-12 17:14:30', '2023-02-12 17:14:30'),
(1863, 'bd', 'drafts', 'খসড়া', '2023-02-12 17:14:30', '2023-02-12 17:14:30'),
(1864, 'bd', 'author', 'লেখক', '2023-02-12 17:14:30', '2023-02-12 17:14:30'),
(1865, 'bd', 'category', 'বিভাগ', '2023-02-12 17:14:30', '2023-02-12 17:14:30'),
(1866, 'bd', 'comment', 'বিভাগ', '2023-02-12 17:14:30', '2023-02-12 17:14:30'),
(1867, 'bd', 'tl_commerce__add_blog_category', NULL, '2023-02-12 17:14:30', '2023-02-12 17:14:30'),
(1868, 'bd', 'please_insert_a_name', 'অনুগ্রহ করে একটি নাম সন্নিবেশ করুন', '2023-02-12 17:14:30', '2023-02-12 17:14:30'),
(1869, 'bd', 'this_name_is_already_available_please_insert_another', 'এই নামটি ইতিমধ্যেই উপলব্ধ অনুগ্রহ করে আরেকটি সন্নিবেশ করুন৷', '2023-02-12 17:14:30', '2023-02-12 17:14:30'),
(1870, 'bd', 'please_write_the_category_name_under_225_words', 'অনুগ্রহ করে 225 শব্দের নিচে বিভাগের নাম লিখুন', '2023-02-12 17:14:30', '2023-02-12 17:14:30'),
(1871, 'bd', 'this_permalink_is_already_available_please_insert_another', 'এই পার্মালিঙ্কটি ইতিমধ্যেই উপলব্ধ অনুগ্রহ করে অন্যটি প্রবেশ করান', '2023-02-12 17:14:30', '2023-02-12 17:14:30'),
(1872, 'bd', 'new_blog_category_created_successfully', 'নতুন ব্লগ বিভাগ সফলভাবে তৈরি হয়েছে!', '2023-02-12 17:14:30', '2023-02-12 17:14:30'),
(1873, 'bd', 'blog_category_publish_status_changed_successfully', 'ব্লগ বিভাগ প্রকাশের স্ট্যাটাস সফলভাবে পরিবর্তিত হয়েছে৷', '2023-02-12 17:14:30', '2023-02-12 17:14:30'),
(1874, 'bd', 'blog_category_featured_status_changed_successfully', 'ব্লগ বিভাগ বৈশিষ্ট্যযুক্ত স্ট্যাটাস সফলভাবে পরিবর্তিত হয়েছে৷', '2023-02-12 17:14:30', '2023-02-12 17:14:30'),
(1875, 'bd', 'blog_category_deleted_successfully', 'ব্লগ বিভাগ সফলভাবে মুছে ফেলা হয়েছে', '2023-02-12 17:14:30', '2023-02-12 17:14:30'),
(1876, 'bd', 'edit_menus', 'মেনু সম্পাদনা করুন', '2023-02-12 17:14:30', '2023-02-12 17:14:30'),
(1877, 'bd', 'manage_locations', 'অবস্থানগুলি পরিচালনা করুন', '2023-02-12 17:14:30', '2023-02-12 17:14:30'),
(1878, 'bd', 'select_a_menu_to_edit', 'সম্পাদনা করতে একটি মেনু নির্বাচন করুন:', '2023-02-12 17:14:30', '2023-02-12 17:14:30'),
(1879, 'bd', 'create_menu_', 'মেনু তৈরি করুন', '2023-02-12 17:14:30', '2023-02-12 17:14:30'),
(1880, 'bd', 'translate_menu_into', 'মেনু অনুবাদ:', '2023-02-12 17:14:30', '2023-02-12 17:14:30'),
(1881, 'bd', 'custom_links', 'কাস্টম লিঙ্ক', '2023-02-12 17:16:38', '2023-02-12 17:16:38'),
(1882, 'bd', 'link_text', 'লিঙ্ক টেক্সট', '2023-02-12 17:16:38', '2023-02-12 17:16:38'),
(1883, 'bd', 'add_to_menu', 'মেনুতে যোগ করুন', '2023-02-12 17:16:38', '2023-02-12 17:16:38'),
(1884, 'bd', 'most_recent', 'সাম্প্রতিক', '2023-02-12 17:16:38', '2023-02-12 17:16:38'),
(1885, 'bd', 'view_all', 'সব দেখুন', '2023-02-12 17:16:38', '2023-02-12 17:16:38'),
(1886, 'bd', 'search', 'অনুসন্ধান করুন', '2023-02-12 17:16:38', '2023-02-12 17:16:38'),
(1887, 'bd', 'select_all', 'সব নির্বাচন করুন', '2023-02-12 17:16:38', '2023-02-12 17:16:38'),
(1888, 'bd', 'select_all_', 'সব নির্বাচন করুন', '2023-02-12 17:16:38', '2023-02-12 17:16:38'),
(1889, 'bd', 'posts', 'পোস্ট', '2023-02-12 17:16:38', '2023-02-12 17:16:38'),
(1890, 'bd', 'menu_name', 'মেনু নাম', '2023-02-12 17:16:38', '2023-02-12 17:16:38'),
(1891, 'bd', 'give_your_menu_a_name_then_click_save_menu', 'আপনার মেনুকে একটি নাম দিন, তারপর সেভ মেনুতে ক্লিক করুন', '2023-02-12 17:16:38', '2023-02-12 17:16:38'),
(1892, 'bd', 'menu_settings', 'মেনু সেটিংস', '2023-02-12 17:16:38', '2023-02-12 17:16:38'),
(1893, 'bd', 'display_locations', 'প্রদর্শন অবস্থান', '2023-02-12 17:16:38', '2023-02-12 17:16:38'),
(1894, 'bd', 'currently_set_to__', 'বর্তমানে সেট করা হয়েছে:', '2023-02-12 17:16:38', '2023-02-12 17:16:38'),
(1895, 'bd', 'save_menu', 'সেভ মেনু', '2023-02-12 17:16:38', '2023-02-12 17:16:38'),
(1896, 'bd', 'drag_the_items_into_the_order_you_prefer_click_the_arrow_on_the_right_of_the_item_to_reveal_additional_configuration_options', 'আপনার পছন্দ অনুযায়ী আইটেমগুলিকে টেনে আনুন। অতিরিক্ত কনফিগারেশন বিকল্পগুলি প্রকাশ করতে আইটেমের ডানদিকে তীরটিতে ক্লিক করুন', '2023-02-12 17:16:38', '2023-02-12 17:16:38'),
(1897, 'bd', 'delete_menu', 'মেনু মুছুন', '2023-02-12 17:16:38', '2023-02-12 17:16:38'),
(1898, 'bd', 'update_menu', 'আপডেট মেনু', '2023-02-12 17:16:38', '2023-02-12 17:16:38'),
(1899, 'bd', 'your_theme_supports', 'আপনার থিম সমর্থন করে', '2023-02-12 17:16:38', '2023-02-12 17:16:38'),
(1900, 'bd', 'menus_select_which_menu_appears_in_each_location', 'মেনু। প্রতিটি অবস্থানে কোন মেনু প্রদর্শিত হবে তা নির্বাচন করুন', '2023-02-12 17:16:38', '2023-02-12 17:16:38'),
(1901, 'bd', 'theme_location', 'থিম অবস্থান', '2023-02-12 17:21:25', '2023-02-12 17:21:25'),
(1902, 'bd', 'assigned_menu', 'নির্ধারিত মেনু', '2023-02-12 17:21:25', '2023-02-12 17:21:25'),
(1903, 'bd', '_edit', 'সম্পাদনা করুন', '2023-02-12 17:21:25', '2023-02-12 17:21:25'),
(1904, 'bd', 'use_new_menu', 'নতুন মেনু ব্যবহার করুন', '2023-02-12 17:21:25', '2023-02-12 17:21:25'),
(1905, 'bd', 'comment_loading_failed', 'মন্তব্য লোডিং ব্যর্থ হয়েছে', '2023-02-12 17:21:25', '2023-02-12 17:21:25'),
(1906, 'bd', 'title_is_required', 'শিরোনাম প্রয়োজন', '2023-02-12 17:21:25', '2023-02-12 17:21:25'),
(1907, 'bd', '_image_for_desktop_is_required', 'ডেস্কটপের জন্য চিত্র প্রয়োজন', '2023-02-12 17:21:25', '2023-02-12 17:21:25'),
(1908, 'bd', 'image_for_mobile_is_required', 'মোবাইলের জন্য ইমেজ প্রয়োজন', '2023-02-12 17:21:25', '2023-02-12 17:21:25'),
(1909, 'bd', 'slider_added_successfully', 'স্লাইডার সফলভাবে যোগ করা হয়েছে', '2023-02-12 17:21:25', '2023-02-12 17:21:25'),
(1910, 'bd', 'homepage_builder', 'হোমপেজ নির্মাতা', '2023-02-12 17:21:25', '2023-02-12 17:21:25'),
(1911, 'bd', 'home_page_sections', 'হোম পেজ বিভাগ', '2023-02-12 17:21:25', '2023-02-12 17:21:25'),
(1912, 'bd', 'add_new_section', 'নতুন বিভাগ যোগ করুন', '2023-02-12 17:21:25', '2023-02-12 17:21:25'),
(1913, 'bd', 'manage_slider', 'স্লাইডার পরিচালনা করুন', '2023-02-12 17:21:25', '2023-02-12 17:21:25'),
(1914, 'bd', 'no_section_found', 'কোন বিভাগ পাওয়া যায়নি', '2023-02-12 17:21:25', '2023-02-12 17:21:25'),
(1915, 'bd', 'are_you_sure_to_delete_this_section', 'আপনি কি এই বিভাগটি মুছে ফেলার বিষয়ে নিশ্চিত', '2023-02-12 17:21:25', '2023-02-12 17:21:25'),
(1916, 'bd', 'new_section', 'নতুন বিভাগ', '2023-02-12 17:21:25', '2023-02-12 17:21:25'),
(1917, 'bd', 'select_section', 'বিভাগ নির্বাচন করুন', '2023-02-12 17:21:25', '2023-02-12 17:21:25'),
(1918, 'bd', 'select_layout', 'লেআউট নির্বাচন করুন', '2023-02-12 17:21:25', '2023-02-12 17:21:25'),
(1919, 'bd', 'ads', 'বিজ্ঞাপন', '2023-02-12 17:21:25', '2023-02-12 17:21:25'),
(1920, 'bd', 'blogs', 'ব্লগ', '2023-02-12 17:21:25', '2023-02-12 17:21:25'),
(1921, 'bd', 'flash_deal', 'ফ্ল্যাশ ডিল', '2023-02-12 17:23:47', '2023-02-12 17:23:47'),
(1922, 'bd', 'featured_product', 'বৈশিষ্ট্যযুক্ত পণ্য', '2023-02-12 17:23:47', '2023-02-12 17:23:47'),
(1923, 'bd', 'category_slider', 'বৈশিষ্ট্যযুক্ত পণ্য', '2023-02-12 17:23:47', '2023-02-12 17:23:47'),
(1924, 'bd', 'product_collection', 'পণ্য সংগ্রহ', '2023-02-12 17:23:47', '2023-02-12 17:23:47'),
(1925, 'bd', 'custom_product_section', 'কাস্টম পণ্য বিভাগ', '2023-02-12 17:23:47', '2023-02-12 17:23:47'),
(1926, 'bd', 'section_properties', 'বিভাগ বৈশিষ্ট্য', '2023-02-12 17:23:48', '2023-02-12 17:23:48'),
(1927, 'bd', 'background', 'ব্যাকগ্রাউন্ড', '2023-02-12 17:23:48', '2023-02-12 17:23:48'),
(1928, 'bd', 'advanced', 'উন্নত', '2023-02-12 17:23:48', '2023-02-12 17:23:48'),
(1929, 'bd', 'title_is_not_visible_in_homepage', 'শিরোনাম হোমপেজে দৃশ্যমান নয়', '2023-02-12 17:23:48', '2023-02-12 17:23:48'),
(1930, 'bd', 'background_color', 'ব্যাকগ্রাউন্ড কালার', '2023-02-12 17:23:48', '2023-02-12 17:23:48'),
(1931, 'bd', 'background_image', 'ব্যাকগ্রাউন্ড ইমেজ', '2023-02-12 17:23:48', '2023-02-12 17:23:48'),
(1932, 'bd', 'background_size', 'ব্যাকগ্রাউন্ড সাইজ', '2023-02-12 17:23:48', '2023-02-12 17:23:48'),
(1933, 'bd', 'background_position', 'ব্যাকগ্রাউন্ড পজিশন', '2023-02-12 17:23:48', '2023-02-12 17:23:48'),
(1934, 'bd', 'background_repeat', 'ব্যাকগ্রাউন্ড রিপিট', '2023-02-12 17:23:48', '2023-02-12 17:23:48'),
(1935, 'bd', 'padding', 'প্যাডিং', '2023-02-12 17:23:48', '2023-02-12 17:23:48'),
(1936, 'bd', 'bottom', 'নীচে', '2023-02-12 17:23:48', '2023-02-12 17:23:48'),
(1937, 'bd', 'margin', 'মার্জিন', '2023-02-12 17:23:48', '2023-02-12 17:23:48'),
(1938, 'bd', 'new_section_added_successfully', 'নতুন বিভাগ সফলভাবে যোগ করা হয়েছে', '2023-02-12 17:23:48', '2023-02-12 17:23:48'),
(1939, 'bd', 'update_section', 'আপডেট বিভাগ', '2023-02-12 17:23:48', '2023-02-12 17:23:48'),
(1940, 'bd', 'section', 'বিভাগ', '2023-02-12 17:23:48', '2023-02-12 17:23:48'),
(1941, 'bd', 'cover', 'কভার', '2023-02-12 17:25:46', '2023-02-12 17:25:46'),
(1942, 'bd', 'auto', 'অটো', '2023-02-12 17:25:46', '2023-02-12 17:25:46'),
(1943, 'bd', 'contain', 'ধারণ করে', '2023-02-12 17:25:46', '2023-02-12 17:25:46'),
(1944, 'bd', 'initial', 'প্রাথমিক', '2023-02-12 17:25:46', '2023-02-12 17:25:46'),
(1945, 'bd', 'revert', 'রিভার্ট', '2023-02-12 17:25:46', '2023-02-12 17:25:46'),
(1946, 'bd', 'inherit', 'উত্তরাধিকার', '2023-02-12 17:25:46', '2023-02-12 17:25:46'),
(1947, 'bd', 'revertlayer', 'রিভার্ট-লেয়ার', '2023-02-12 17:25:46', '2023-02-12 17:25:46'),
(1948, 'bd', 'unset', 'আনসেট', '2023-02-12 17:25:46', '2023-02-12 17:25:46'),
(1949, 'bd', 'section_updated_successfully', 'বিভাগ সফলভাবে আপডেট করা হয়েছে', '2023-02-12 17:25:46', '2023-02-12 17:25:46'),
(1950, 'bd', 'visibility', 'দৃশ্যমানতা', '2023-02-12 17:25:46', '2023-02-12 17:25:46'),
(1951, 'bd', 'visible', 'দৃশ্যমান', '2023-02-12 17:25:46', '2023-02-12 17:25:46'),
(1952, 'bd', 'hide', 'লুকান', '2023-02-12 17:25:46', '2023-02-12 17:25:46'),
(1953, 'bd', 'filter_by_rating', 'রেটিং দ্বারা ফিল্টার', '2023-02-12 17:25:46', '2023-02-12 17:25:46'),
(1954, 'bd', 'product', 'পণ্য', '2023-02-12 17:25:46', '2023-02-12 17:25:46'),
(1955, 'bd', 'order', 'অর্ডার', '2023-02-12 17:25:46', '2023-02-12 17:25:46'),
(1956, 'bd', 'rating', 'রেটিং', '2023-02-12 17:25:46', '2023-02-12 17:25:46'),
(1957, 'bd', 'review_details', 'পর্যালোচনা বিবরণ', '2023-02-12 17:25:46', '2023-02-12 17:25:46');
INSERT INTO `tl_translations` (`id`, `lang`, `lang_key`, `lang_value`, `created_at`, `updated_at`) VALUES
(1958, 'bd', 'new_unit_added_successfully', 'নতুন ইউনিট সফলভাবে যোগ করা হয়েছে', '2023-02-12 17:25:46', '2023-02-12 17:25:46'),
(1959, 'bd', 'attributes_values', 'গুণাবলী মান', '2023-02-12 17:25:46', '2023-02-12 17:25:46'),
(1960, 'bd', 'attribute_values', 'বৈশিষ্ট্যের মান', '2023-02-12 17:25:46', '2023-02-12 17:25:46'),
(1961, 'bd', 'new_value', 'নতুন মান', '2023-02-12 17:28:18', '2023-02-12 17:28:18'),
(1962, 'bd', 'attribute', 'বৈশিষ্ট্য', '2023-02-12 17:28:18', '2023-02-12 17:28:18'),
(1963, 'bd', 'value', 'মান', '2023-02-12 17:28:18', '2023-02-12 17:28:18'),
(1964, 'bd', 'please_select_a_attribute', 'অনুগ্রহ করে একটি বৈশিষ্ট্য নির্বাচন করুন', '2023-02-12 17:28:18', '2023-02-12 17:28:18'),
(1965, 'bd', 'invalid_attribute', 'অবৈধ বৈশিষ্ট্য', '2023-02-12 17:28:18', '2023-02-12 17:28:18'),
(1966, 'bd', 'attribute_value_added_successfully', 'অ্যাট্রিবিউট মান সফলভাবে যোগ করা হয়েছে', '2023-02-12 17:28:18', '2023-02-12 17:28:18'),
(1967, 'bd', 'variant', 'অ্যাট্রিবিউট মান সফলভাবে যোগ করা হয়েছে', '2023-02-12 17:28:18', '2023-02-12 17:28:18'),
(1968, 'bd', 'selected_items_deleted_successfully', 'নির্বাচিত আইটেম সফলভাবে মুছে ফেলা হয়েছে', '2023-02-12 17:28:18', '2023-02-12 17:28:18'),
(1969, 'bd', 'edit_unit', 'সম্পাদনা ইউনিট', '2023-02-12 17:28:18', '2023-02-12 17:28:18'),
(1970, 'bd', 'unit_updated_successfully', 'ইউনিট সফলভাবে আপডেট হয়েছে', '2023-02-12 17:28:18', '2023-02-12 17:28:18'),
(1971, 'bd', 'unit_deleted_successfully', 'ইউনিট সফলভাবে মুছে ফেলা হয়েছে', '2023-02-12 17:28:18', '2023-02-12 17:28:18'),
(1972, 'bd', 'new_condition', 'নতুন অবস্থা', '2023-02-12 17:28:18', '2023-02-12 17:28:18'),
(1973, 'bd', 'new_condition_added_successfully', 'নতুন শর্ত সফলভাবে যোগ করা হয়েছে', '2023-02-12 17:28:18', '2023-02-12 17:28:18'),
(1974, 'bd', 'color', 'রঙ', '2023-02-12 17:28:18', '2023-02-12 17:28:18'),
(1975, 'bd', 'discount_amount__must_be_a_number', 'ছাড়ের পরিমাণ অবশ্যই একটি সংখ্যা হতে হবে', '2023-02-12 17:28:18', '2023-02-12 17:28:18'),
(1976, 'bd', 'purchase_price__must_be_a_number', 'ক্রয় মূল্য একটি সংখ্যা হতে হবে', '2023-02-12 17:28:18', '2023-02-12 17:28:18'),
(1977, 'bd', 'purchase_price__is_required', 'ক্রয় মূল্য প্রয়োজন', '2023-02-12 17:28:18', '2023-02-12 17:28:18'),
(1978, 'bd', 'unit_price__must_be_a_number', 'ইউনিট মূল্য একটি সংখ্যা হতে হবে', '2023-02-12 17:28:18', '2023-02-12 17:28:18'),
(1979, 'bd', 'unit_price__is_required', 'ইউনিট মূল্য প্রয়োজন', '2023-02-12 17:28:18', '2023-02-12 17:28:18'),
(1980, 'bd', 'quantity__must_be_a_number', 'পরিমাণ অবশ্যই একটি সংখ্যা হতে হবে', '2023-02-12 17:28:18', '2023-02-12 17:28:18'),
(1981, 'bd', 'quantity__is_required', 'পরিমাণ প্রয়োজন', '2023-02-12 17:31:35', '2023-02-12 17:31:35'),
(1982, 'bd', 'new_product_created_successfully', 'নতুন পণ্য সফলভাবে তৈরি করা হয়েছে', '2023-02-12 17:31:35', '2023-02-12 17:31:35'),
(1983, 'bd', 'set_discount', 'সেট ডিসকাউন্ট', '2023-02-12 17:31:35', '2023-02-12 17:31:35'),
(1984, 'bd', 'update_price', 'আপডেট মূল্য', '2023-02-12 17:31:35', '2023-02-12 17:31:35'),
(1985, 'bd', 'stock', 'স্টক', '2023-02-12 17:31:35', '2023-02-12 17:31:35'),
(1986, 'bd', 'low', 'কম', '2023-02-12 17:31:35', '2023-02-12 17:31:35'),
(1987, 'bd', 'num_of_sale', 'বিক্রির সংখ্যা', '2023-02-12 17:31:35', '2023-02-12 17:31:35'),
(1988, 'bd', 'update_stock', 'আপডেট স্টক', '2023-02-12 17:31:35', '2023-02-12 17:31:35'),
(1989, 'bd', 'items_deleted_successfully', 'আইটেম সফলভাবে মুছে ফেলা হয়েছে', '2023-02-12 17:31:35', '2023-02-12 17:31:35'),
(1990, 'bd', 'add_new_country', 'নতুন দেশ যোগ করুন', '2023-02-12 17:31:35', '2023-02-12 17:31:35'),
(1991, 'bd', 'phone_code', 'ফোন কোড', '2023-02-12 17:31:35', '2023-02-12 17:31:35'),
(1992, 'bd', 'flag', 'পতাকা', '2023-02-12 17:31:35', '2023-02-12 17:31:35'),
(1993, 'bd', 'new_country', 'নতুন দেশ', '2023-02-12 17:31:35', '2023-02-12 17:31:35'),
(1994, 'bd', 'select_a_option', 'একটি বিকল্প নির্বাচন করুন', '2023-02-12 17:31:35', '2023-02-12 17:31:35'),
(1995, 'bd', 'add_new_language', 'নতুন ভাষা যোগ করুন', '2023-02-12 17:31:35', '2023-02-12 17:31:35'),
(1996, 'bd', 'native_name', 'নেটিভ নাম', '2023-02-12 17:31:35', '2023-02-12 17:31:35'),
(1997, 'bd', 'rtl', 'আরটিএল', '2023-02-12 17:31:35', '2023-02-12 17:31:35'),
(1998, 'bd', 'backend_translations', 'ব্যাকএন্ড অনুবাদ', '2023-02-12 17:31:35', '2023-02-12 17:31:35'),
(1999, 'bd', 'frontend_translations', 'ফ্রন্টএন্ড অনুবাদ', '2023-02-12 17:31:35', '2023-02-12 17:31:35'),
(2000, 'bd', 'cencel', 'সেন্সেল', '2023-02-12 17:31:35', '2023-02-12 17:31:35'),
(2001, 'bd', 'new_language', 'নতুন ভাষা', '2023-02-12 17:36:09', '2023-02-12 17:36:09'),
(2002, 'bd', 'type_name', 'টাইপ নাম', '2023-02-12 17:36:09', '2023-02-12 17:36:09'),
(2003, 'bd', 'type__native_name', 'স্থানীয় নাম টাইপ করুন', '2023-02-12 17:36:09', '2023-02-12 17:36:09'),
(2004, 'bd', 'new_language_added_successfully', 'নতুন ভাষা সফলভাবে যোগ করা হয়েছে', '2023-02-12 17:36:09', '2023-02-12 17:36:09'),
(2005, 'bd', 'rtl_status_updated_successfully', 'RTL স্ট্যাটাস সফলভাবে আপডেট হয়েছে৷', '2023-02-12 17:36:09', '2023-02-12 17:36:09'),
(2006, 'bd', 'select_countries', 'দেশ নির্বাচন করুন', '2023-02-12 17:36:09', '2023-02-12 17:36:09'),
(2007, 'bd', 'select_states', 'রাজ্য নির্বাচন করুন', '2023-02-12 17:36:09', '2023-02-12 17:36:09'),
(2008, 'bd', 'select_cities', 'শহর নির্বাচন করুন', '2023-02-12 17:36:09', '2023-02-12 17:36:09'),
(2009, 'bd', 'product_discount_updated_successfully', 'পণ্য ছাড় সফলভাবে আপডেট করা হয়েছে', '2023-02-12 17:36:09', '2023-02-12 17:36:09'),
(2010, 'bd', 'product_discount_update_faled', 'পণ্য ডিসকাউন্ট আপডেট ব্যর্থ হয়েছে', '2023-02-12 17:36:09', '2023-02-12 17:36:09'),
(2011, 'bd', 'product_price_updated_successfully', 'পণ্যের মূল্য সফলভাবে আপডেট করা হয়েছে', '2023-02-12 17:36:09', '2023-02-12 17:36:09'),
(2012, 'bd', 'product_price_update_faled', 'পণ্য মূল্য আপডেট ব্যর্থ হয়েছে', '2023-02-12 17:36:09', '2023-02-12 17:36:09'),
(2013, 'bd', 'product_stock_updated_successfully', 'পণ্য স্টক সফলভাবে আপডেট করা হয়েছে', '2023-02-12 17:36:09', '2023-02-12 17:36:09'),
(2014, 'bd', 'product_stock_update_faled', 'পণ্য স্টক আপডেট ব্যর্থ হয়েছে', '2023-02-12 17:36:09', '2023-02-12 17:36:09'),
(2015, 'bd', 'key', 'কী', '2023-02-12 17:36:09', '2023-02-12 17:36:09'),
(2016, 'bd', 'language', 'ভাষা', '2023-02-12 17:36:09', '2023-02-12 17:36:09'),
(2017, 'bd', 'save_chnages', 'পরিবর্তনগুলি সংরক্ষণ করুন', '2023-02-12 17:36:09', '2023-02-12 17:36:09'),
(2018, 'bd', 'translations_updated_successfully', 'অনুবাদ সফলভাবে আপডেট হয়েছে', '2023-02-12 17:36:09', '2023-02-12 17:36:09'),
(2019, 'bd', 'tl_commerce__comment_setting', NULL, '2023-02-12 17:36:09', '2023-02-12 17:36:09'),
(2020, 'bd', 'comment_setting', 'মন্তব্য সেটিং', '2023-02-12 17:36:09', '2023-02-12 17:36:09'),
(2021, 'bd', 'default_blog_settings', 'ডিফল্ট ব্লগ সেটিংস', '2023-02-12 17:51:38', '2023-02-12 17:52:20'),
(2022, 'bd', 'allow_people_to_submit_comments_on_new_blogs', 'লোকেদের নতুন ব্লগে মন্তব্য জমা দেওয়ার অনুমতি দিন', '2023-02-12 17:51:38', '2023-02-12 17:51:38'),
(2023, 'bd', 'other_comment_settings', 'অন্যান্য মন্তব্য সেটিংস', '2023-02-12 17:51:38', '2023-02-12 17:51:38'),
(2024, 'bd', 'comment_author_must_fill_out_name_and_email', 'মন্তব্য লেখকের নাম এবং ইমেল পূরণ করতে হবে', '2023-02-12 17:51:38', '2023-02-12 17:51:38'),
(2025, 'bd', 'users_must_be_registered_and_logged_in_to_comment', 'ব্যবহারকারীদের অবশ্যই নিবন্ধিত হতে হবে এবং মন্তব্য করতে লগ ইন করতে হবে', '2023-02-12 17:51:38', '2023-02-12 17:51:38'),
(2026, 'bd', 'automatically_close_comments_on_blogs_older_than', 'এর চেয়ে পুরানো ব্লগে স্বয়ংক্রিয়ভাবে মন্তব্য বন্ধ করুন', '2023-02-12 17:51:38', '2023-02-12 17:51:38'),
(2027, 'bd', 'days', 'দিন', '2023-02-12 17:51:38', '2023-02-12 17:51:38'),
(2028, 'bd', 'break_comments_into_pages_with', 'প্রতি পৃষ্ঠায় মন্তব্য বিরতি সাথে', '2023-02-12 17:51:38', '2023-02-12 17:51:38'),
(2029, 'bd', 'top_level_comments_per_page_and', 'প্রতি পৃষ্ঠায়  শীর্ষ স্তরের মন্তব্য এবং', '2023-02-12 17:51:38', '2023-02-12 17:51:38'),
(2030, 'bd', 'comments_should_be_displayed_with_the', 'মন্তব্যের প্রদর্শন করা উচিত', '2023-02-12 17:51:38', '2023-02-12 17:51:38'),
(2031, 'bd', 'older', 'পুরোনো', '2023-02-12 17:51:38', '2023-02-12 17:51:38'),
(2032, 'bd', 'newer', 'নতুন', '2023-02-12 17:51:38', '2023-02-12 17:51:38'),
(2033, 'bd', 'comments_at_the_top_of_each_page', 'মন্তব্য টি প্রতিটি পৃষ্ঠার শীর্ষে', '2023-02-12 17:51:38', '2023-02-12 17:51:38'),
(2034, 'bd', 'email_me_whenever', 'যখনই আমাকে ইমেল করুন', '2023-02-12 17:51:38', '2023-02-12 17:51:38'),
(2035, 'bd', 'anyone_posts_a_comment', 'যে কেউ একটি মন্তব্য পোস্ট করেছে', '2023-02-12 17:51:38', '2023-02-12 17:51:38'),
(2036, 'bd', 'a_comment_is_held_for_moderation', 'একটি মন্তব্য মোডারেশ জন্য রাখা হয়', '2023-02-12 17:51:38', '2023-02-12 17:51:38'),
(2037, 'bd', 'before_a_comment_appears', 'একটি মন্তব্য উপস্থিত হওয়ার আগে', '2023-02-12 17:51:38', '2023-02-12 17:51:38'),
(2038, 'bd', 'comment_must_be_manually_approved', 'মন্তব্য ম্যানুয়ালি অনুমোদিত হতে হবে', '2023-02-12 17:51:38', '2023-02-12 17:51:38'),
(2039, 'bd', 'comment_author_must_have_a_previously_approved_comment', 'মন্তব্য লেখক একটি পূর্বে অনুমোদিত মন্তব্য থাকতে হবে', '2023-02-12 17:51:38', '2023-02-12 17:51:38'),
(2040, 'bd', 'comment_moderation', 'মন্তব্য মোডারেশ', '2023-02-12 17:51:38', '2023-02-12 17:51:38'),
(2041, 'bd', 'hold_a_comment_in_the_queue_if_it_contains', 'সারিতে একটি মন্তব্য রাখুন যদি এটি থাকে', '2023-02-12 17:58:15', '2023-02-12 17:58:15'),
(2042, 'bd', 'or_more_links_a_common_characteristic_of_comment_spam_is_a_large____________________________________number_of_hyperlinks', 'বা তার বেশি লিঙ্ক। (মন্তব্য স্প্যামের একটি সাধারণ বৈশিষ্ট্য হল বিপুল সংখ্যক হাইপারলিঙ্ক।)', '2023-02-12 17:58:15', '2023-02-12 17:58:15'),
(2043, 'bd', 'when_a_comment_contains_any_of_these_words_in_its_content_author_name_url________________________________email_ip_address_or_browsers_user_agent_string_it_will_be_held_in_the_', 'যখন একটি মন্তব্যের বিষয়বস্তু, লেখকের নাম, URL, ইমেল, আইপি ঠিকানা, বা ব্রাউজারের ব্যবহারকারী এজেন্ট স্ট্রিং-এ এই শব্দগুলির মধ্যে যেকোনো একটি থাকে, তখন এটি থাকবে', '2023-02-12 17:58:15', '2023-02-12 17:58:15'),
(2044, 'bd', 'pending_queue', 'পেন্ডিং সারি তে।', '2023-02-12 17:58:15', '2023-02-12 17:58:15'),
(2045, 'bd', 'one_word_or_ip_address_per_line_it_will_match_inside_words_so_press_will_match________________________________wordpress', 'প্রতি লাইনে একটি শব্দ বা IP ঠিকানা। এটি শব্দের ভিতরে মিলবে, তাই \"প্রেস\" \"ওয়ার্ডপ্রেস\" এর সাথে মিলবে।', '2023-02-12 17:58:15', '2023-02-12 17:58:15'),
(2046, 'bd', 'disallowed_comment_keys', 'অননুমোদিত মন্তব্য কী', '2023-02-12 17:58:15', '2023-02-12 17:58:15'),
(2047, 'bd', 'when_a_comment_contains_any_of_these_words_in_its_content_author_name_url________________________________email_ip_address_or_browsers_user_agent_string_it_will_be_put_in_the_trash_one_word_or________________________________ip_address_per_line_it_will_match_inside_words_so_press_will_match_wordpress', 'যখন একটি মন্তব্যের বিষয়বস্তু, লেখকের নাম, URL, ইমেল, আইপি ঠিকানা, বা ব্রাউজারের ব্যবহারকারী এজেন্ট স্ট্রিং-এ এই শব্দগুলির যেকোনো একটি থাকে, তখন এটি ট্র্যাশে রাখা হবে। প্রতি লাইনে একটি শব্দ বা IP ঠিকানা। এটি শব্দের ভিতরে মিলবে, তাই \"প্রেস\" \"ওয়ার্ডপ্রেস\" এর সাথে মিলবে।', '2023-02-12 17:58:15', '2023-02-12 17:58:15'),
(2048, 'bd', 'avatars', 'অবতার', '2023-02-12 17:58:15', '2023-02-12 17:58:15'),
(2049, 'bd', 'an_avatar_is_an_image_that_can_be_associated_with_a_user_across_multiple_websites_in_this_area_you_can_choose_to_display_avatars_of_users_who_interact_with_the_site', 'কটি অবতার হল একটি ছবি যা একাধিক ওয়েবসাইট জুড়ে ব্যবহারকারীর সাথে যুক্ত হতে পারে। এই এলাকায়, আপনি সাইটের সাথে ইন্টারঅ্যাক্ট করে এমন ব্যবহারকারীদের অবতার প্রদর্শন করতে বেছে নিতে পারেন।', '2023-02-12 17:58:15', '2023-02-12 17:58:15'),
(2050, 'bd', 'avatar_display', 'অবতার ডিসপ্লে', '2023-02-12 17:58:15', '2023-02-12 17:58:15'),
(2051, 'bd', 'show_avatars', 'অবতার দেখান', '2023-02-12 17:58:15', '2023-02-12 17:58:15'),
(2052, 'bd', 'default_avatar', 'ডিফল্ট অবতার', '2023-02-12 17:58:15', '2023-02-12 17:58:15'),
(2053, 'bd', 'for_users_without_a_custom_avatar_of_their_own_you_can_either_display_a_generic_logo_or_a_generated_one_based_on_their_email_address', 'তাদের নিজস্ব একটি কাস্টম অবতার ছাড়া ব্যবহারকারীদের জন্য, আপনি তাদের ইমেল ঠিকানার উপর ভিত্তি করে একটি জেনেরিক লোগো বা জেনারেট করা একটি প্রদর্শন করতে পারেন।', '2023-02-12 17:58:15', '2023-02-12 17:58:15'),
(2054, 'bd', 'mystery_person', 'রহস্য ব্যক্তি', '2023-02-12 17:58:15', '2023-02-12 17:58:15'),
(2055, 'bd', 'blank', 'খালি', '2023-02-12 17:58:15', '2023-02-12 17:58:15'),
(2056, 'bd', 'gravatar_logo', 'আইডেন্টিকন', '2023-02-12 17:58:15', '2023-02-12 17:58:15'),
(2057, 'bd', 'identicon_generated', 'গ্রাভাটার', '2023-02-12 17:58:15', '2023-02-12 17:58:15'),
(2058, 'bd', 'wavatar_generated', 'ওয়াভাতার', '2023-02-12 17:58:15', '2023-02-12 17:58:15'),
(2059, 'bd', 'monsterid_generated', 'মনস্টার', '2023-02-12 17:58:15', '2023-02-12 17:58:15'),
(2060, 'bd', 'retro_generated', 'রেট্রো', '2023-02-12 17:58:15', '2023-02-12 17:58:15'),
(2061, 'bd', 'module_name', 'মডিউলের নাম', '2023-02-12 19:17:26', '2023-02-12 19:17:26'),
(2062, 'bd', 'permission_name', 'অনুমতির নাম', '2023-02-12 19:17:26', '2023-02-12 19:17:26'),
(2063, 'bd', 'edit_color', 'রঙ সম্পাদনা করুন', '2023-02-12 19:17:26', '2023-02-12 19:17:26'),
(2064, 'bd', 'color_updated_successfully', 'রঙ সফলভাবে আপডেট করা হয়েছে', '2023-02-12 19:17:26', '2023-02-12 19:17:26'),
(2065, 'bd', 'edit_attribute_value', 'এট্রিবিউট মান সম্পাদনা করুন', '2023-02-12 19:17:26', '2023-02-12 19:17:26'),
(2066, 'bd', 'edit_attribute', 'এট্রিবিউট সম্পাদনা করুন', '2023-02-12 19:17:26', '2023-02-12 19:17:26'),
(2067, 'bd', 'attribute_updated_successfully', 'অ্যাট্রিবিউট সফলভাবে আপডেট হয়েছে', '2023-02-12 19:17:26', '2023-02-12 19:17:26'),
(2068, 'bd', 'edit_tag', 'ট্যাগ সম্পাদনা করুন', '2023-02-12 19:17:26', '2023-02-12 19:17:26'),
(2069, 'bd', 'edit_country', 'দেশ সম্পাদনা করুন', '2023-02-12 19:17:26', '2023-02-12 19:17:26'),
(2070, 'bd', 'country_information', 'দেশের তথ্য', '2023-02-12 19:17:26', '2023-02-12 19:17:26'),
(2071, 'bd', 'shipping', 'শিপিং', '2023-02-12 19:17:26', '2023-02-12 19:17:26'),
(2072, 'bd', 'create_new_profile', 'নতুন প্রোফাইল তৈরি করুন', '2023-02-12 19:17:26', '2023-02-12 19:17:26'),
(2073, 'bd', 'no_profile_created_yet', 'এখনও কোন প্রোফাইল তৈরি করা হয়নি', '2023-02-12 19:17:26', '2023-02-12 19:17:26'),
(2074, 'bd', 'shipping_time', 'শিপিং সময়', '2023-02-12 19:17:26', '2023-02-12 19:17:26'),
(2075, 'bd', 'create_new_time', 'শিপিং সময়', '2023-02-12 19:17:26', '2023-02-12 19:17:26'),
(2076, 'bd', 'min_shipping_time', 'নূন্যতম শিপিং সময়', '2023-02-12 19:17:26', '2023-02-12 19:17:26'),
(2077, 'bd', 'max_shipping_time', 'সর্বোচ্চ শিপিং সময়', '2023-02-12 19:17:26', '2023-02-12 19:17:26'),
(2078, 'bd', 'shipping_carriers', 'শিপিং ক্যারিয়ার', '2023-02-12 19:17:26', '2023-02-12 19:17:26'),
(2079, 'bd', 'add_new_shipping_time', 'তুন শিপিং সময় যোগ করুন', '2023-02-12 19:17:26', '2023-02-12 19:17:26'),
(2080, 'bd', 'minimum_shipping_time', 'নূন্যতম শিপিং সময়', '2023-02-12 19:17:26', '2023-02-12 19:17:26'),
(2081, 'bd', 'hours', 'ঘন্টা', '2023-02-12 19:19:17', '2023-02-12 19:19:17'),
(2082, 'bd', 'minutes', 'মিনিট', '2023-02-12 19:19:17', '2023-02-12 19:19:17'),
(2083, 'bd', 'maximum_shipping_time', 'সর্বোচ্চ শিপিং সময়', '2023-02-12 19:19:17', '2023-02-12 19:19:17'),
(2084, 'bd', 'add_new_state', 'নতুন রাজ্য যোগ করুন', '2023-02-12 19:19:17', '2023-02-12 19:19:17'),
(2085, 'bd', 'country', 'দেশ', '2023-02-12 19:19:17', '2023-02-12 19:19:17'),
(2086, 'bd', 'add_new_city', 'নতুন শহর যোগ করুন', '2023-02-12 19:19:17', '2023-02-12 19:19:17'),
(2087, 'bd', 'state', 'রাজ্য', '2023-02-12 19:19:17', '2023-02-12 19:19:17'),
(2088, 'bd', 'edit_product', 'পণ্য সম্পাদনা করুন', '2023-02-12 19:19:17', '2023-02-12 19:19:17'),
(2089, 'bd', 'gm', 'গ্রাম', '2023-02-12 19:19:17', '2023-02-12 19:19:17'),
(2090, 'bd', 'cm', 'সেমি', '2023-02-12 19:19:17', '2023-02-12 19:19:17'),
(2091, 'bd', 'create_or_manage_taxes', 'কর তৈরি করুন বা পরিচালনা করুন', '2023-02-12 19:19:17', '2023-02-12 19:19:17'),
(2092, 'bd', 'update__draft', 'আপডেট এবং ড্রাফ্ট', '2023-02-12 19:19:17', '2023-02-12 19:19:17'),
(2093, 'bd', 'update__publish', 'আপডেট ও প্রকাশ করুন', '2023-02-12 19:19:17', '2023-02-12 19:19:17'),
(2094, 'bd', 'product_update_successfully', 'পণ্য আপডেট সফলভাবে', '2023-02-12 19:19:17', '2023-02-12 19:19:17'),
(2095, 'bd', '100_authentic', '100% খাঁটি', '2023-02-12 19:19:17', '2023-02-12 19:19:17'),
(2096, 'bd', 'available', 'অভায়লাবলে', '2023-02-12 19:19:17', '2023-02-12 19:19:17'),
(2097, 'bd', 'edit_discount', 'ডিসকাউন্ট সম্পাদনা করুন', '2023-02-12 19:19:17', '2023-02-12 19:19:17'),
(2098, 'bd', 'edit_state', 'রাজ্য সম্পাদনা করুন', '2023-02-12 19:19:17', '2023-02-12 19:19:17'),
(2099, 'bd', 'type__here', 'এখানে টাইপ করুন', '2023-02-12 19:19:17', '2023-02-12 19:19:17'),
(2100, 'bd', 'showhide', 'দেখান/লুকান', '2023-02-12 19:19:17', '2023-02-12 19:19:17'),
(2101, 'bd', 'create_shipping_profile', 'শিপিং প্রোফাইল তৈরি করুন', '2023-02-12 19:26:16', '2023-02-12 19:26:16'),
(2102, 'bd', 'profile_information', 'প্রোফাইল তথ্য', '2023-02-12 19:26:16', '2023-02-12 19:26:16'),
(2103, 'bd', 'profile_name', 'প্রোফাইল নাম', '2023-02-12 19:26:16', '2023-02-12 19:26:16'),
(2104, 'bd', 'shipping_from', 'শিপিং হইতে', '2023-02-12 19:26:16', '2023-02-12 19:26:16'),
(2105, 'bd', 'location', 'অবস্থান', '2023-02-12 19:26:16', '2023-02-12 19:26:16'),
(2106, 'bd', 'address', 'ঠিকানা', '2023-02-12 19:26:16', '2023-02-12 19:26:16'),
(2107, 'bd', 'select_product', 'পণ্য নির্বাচন করুন', '2023-02-12 19:26:16', '2023-02-12 19:26:16'),
(2108, 'bd', 'add_currency', 'মুদ্রা যোগ করুন', '2023-02-12 19:26:16', '2023-02-12 19:26:16'),
(2109, 'bd', 'currency_name', 'মুদ্রার নাম', '2023-02-12 19:26:16', '2023-02-12 19:26:16'),
(2110, 'bd', 'currency_symbol', 'মুদ্রার প্রতীক', '2023-02-12 19:26:16', '2023-02-12 19:26:16'),
(2111, 'bd', 'currency_code_', 'মুদ্রা কোড', '2023-02-12 19:26:16', '2023-02-12 19:26:16'),
(2112, 'bd', 'conversion_rate', 'রূপান্তর হার', '2023-02-12 19:26:16', '2023-02-12 19:26:16'),
(2113, 'bd', 'edit_currency', 'মুদ্রা সম্পাদনা করুন', '2023-02-12 19:26:16', '2023-02-12 19:26:16'),
(2114, 'bd', 'symbol', 'প্রতীক', '2023-02-12 19:26:16', '2023-02-12 19:26:16'),
(2115, 'bd', 'exchange_rate_with_usd', 'USD এর সাথে বিনিময় হার', '2023-02-12 19:26:16', '2023-02-12 19:26:16'),
(2116, 'bd', 'currency_position', 'মুদ্রার অবস্থান', '2023-02-12 19:26:16', '2023-02-12 19:26:16'),
(2117, 'bd', 'select_currency_position', 'মুদ্রা অবস্থান নির্বাচন করুন', '2023-02-12 19:26:16', '2023-02-12 19:26:16'),
(2118, 'bd', 'thousand_separator', 'হাজার বিভাজক', '2023-02-12 19:26:16', '2023-02-12 19:26:16'),
(2119, 'bd', 'decimal_separator', 'দশমিক বিভাজক', '2023-02-12 19:26:16', '2023-02-12 19:26:16'),
(2120, 'bd', 'number_of_decimals', 'দশমিক সংখ্যা', '2023-02-12 19:26:16', '2023-02-12 19:26:16'),
(2121, 'bd', 'currency_added_successfully', 'মুদ্রা সফলভাবে যোগ করা হয়েছে', '2023-02-12 19:29:25', '2023-02-12 19:29:25'),
(2122, 'bd', 'checkout', 'চেকআউট', '2023-02-12 19:29:25', '2023-02-12 19:29:25'),
(2123, 'bd', 'wallet', 'ওয়ালেট', '2023-02-12 19:29:25', '2023-02-12 19:29:25'),
(2124, 'bd', 'invoice', 'চালান', '2023-02-12 19:29:25', '2023-02-12 19:29:25'),
(2125, 'bd', 'defalt_currency', 'ডিফল্ট মুদ্রা', '2023-02-12 19:29:25', '2023-02-12 19:29:25'),
(2126, 'bd', 'to_create_new_currency_or_manage_existing_currencies', 'নতুন মুদ্রা তৈরি করতে বা বিদ্যমান মুদ্রা পরিচালনা করতে', '2023-02-12 19:29:25', '2023-02-12 19:29:25'),
(2127, 'bd', 'click_here', 'এখানে ক্লিক করুন', '2023-02-12 19:29:25', '2023-02-12 19:29:25'),
(2128, 'bd', 'enable_product_reviews', 'পণ্য পর্যালোচনা সক্ষম করুন', '2023-02-12 19:29:25', '2023-02-12 19:29:25'),
(2129, 'bd', 'enable_star_rating_on_product_reviews', 'পণ্য পর্যালোচনাগুলিতে তারকা রেটিং সক্ষম করুন', '2023-02-12 19:29:25', '2023-02-12 19:29:25'),
(2130, 'bd', 'star_rating_should_be_required_not_optional', 'স্টার রেটিং প্রয়োজন ঐচ্ছিক নয়', '2023-02-12 19:29:25', '2023-02-12 19:29:25'),
(2131, 'bd', 'show_verified_customer_label_on_product_reviews', 'পণ্য পর্যালোচনায় যাচাইকৃত গ্রাহক লেবেল দেখান', '2023-02-12 19:29:25', '2023-02-12 19:29:25'),
(2132, 'bd', 'reviews_can_only_be_left_by_verified_customer', 'রিভিউ শুধুমাত্র যাচাইকৃত গ্রাহক দ্বারা ছেড়ে দেওয়া যেতে পারে', '2023-02-12 19:29:25', '2023-02-12 19:29:25'),
(2133, 'bd', 'enable_product_compare', 'পণ্য তুলনা সক্ষম করুন', '2023-02-12 19:29:25', '2023-02-12 19:29:25'),
(2134, 'bd', 'enable_product_discount', 'পণ্য ছাড় সক্ষম করুন', '2023-02-12 19:29:25', '2023-02-12 19:29:25'),
(2135, 'bd', 'display_product_perpage', 'প্রতি পৃষ্ঠায় পণ্য প্রদর্শন করুন', '2023-02-12 19:29:25', '2023-02-12 19:29:25'),
(2136, 'bd', 'enable_billing_address', 'বিলিং ঠিকানা সক্ষম করুন', '2023-02-12 19:29:25', '2023-02-12 19:29:25'),
(2137, 'bd', 'use_the_shipping_address_as_the_billing_address_by_default', 'ডিফল্টরূপে বিলিং ঠিকানা হিসাবে শিপিং ঠিকানা ব্যবহার করুন৷', '2023-02-12 19:29:25', '2023-02-12 19:29:25'),
(2138, 'bd', 'enable_guest_checkout', 'গেস্ট চেকআউট সক্ষম করুন', '2023-02-12 19:29:25', '2023-02-12 19:29:25'),
(2139, 'bd', 'create_account_in_guest_checkout', 'গেস্ট চেকআউটে অ্যাকাউন্ট তৈরি করুন', '2023-02-12 19:29:25', '2023-02-12 19:29:25'),
(2140, 'bd', 'send_invoice_to_customer_email', 'গ্রাহকের ইমেলে ইনভয়েস পাঠান', '2023-02-12 19:29:25', '2023-02-12 19:29:25'),
(2141, 'bd', 'enable_tax_in_checkout', 'চেকআউটে ট্যাক্স সক্ষম করুন', '2023-02-12 19:33:07', '2023-02-12 19:33:07'),
(2142, 'bd', 'enable_coupon_in_checkout', 'চেকআউটে কুপন সক্রিয় করুন', '2023-02-12 19:33:07', '2023-02-12 19:33:07'),
(2143, 'bd', 'create_or_manage_your', 'তৈরি করুন বা পরিচালনা করুন আপনার', '2023-02-12 19:33:07', '2023-02-12 19:33:07'),
(2144, 'bd', 'enable_multiple_coupon_in_single_order', 'একক ক্রমে একাধিক কুপন সক্রিয় করুন', '2023-02-12 19:33:07', '2023-02-12 19:33:07'),
(2145, 'bd', 'enable_minimum_order_amount', 'সর্বনিম্ন অর্ডার পরিমাণ সক্ষম করুন', '2023-02-12 19:33:07', '2023-02-12 19:33:07'),
(2146, 'bd', 'minimum_order_amount', 'ন্যূনতম অর্ডার পরিমাণ', '2023-02-12 19:33:07', '2023-02-12 19:33:07'),
(2147, 'bd', 'enable_wallet_in_checkout', 'চেকআউটে ওয়ালেট সক্ষম করুন', '2023-02-12 19:33:07', '2023-02-12 19:33:07'),
(2148, 'bd', 'to_enable_wallet_you_need_to_active', 'ওয়ালেট সক্রিয় করতে আপনাকে সক্রিয় করতে হবে', '2023-02-12 19:33:07', '2023-02-12 19:33:07'),
(2149, 'bd', 'enable_order_note', 'অর্ডার নোট সক্ষম করুন', '2023-02-12 19:33:07', '2023-02-12 19:33:07'),
(2150, 'bd', 'enable_document_in_checkout', 'চেকআউটে নথি সক্ষম করুন', '2023-02-12 19:33:07', '2023-02-12 19:33:07'),
(2151, 'bd', 'enable_carrier_in_checkout', 'চেকআউটে ক্যরিয়ার সক্ষম করুন', '2023-02-12 19:33:07', '2023-02-12 19:33:07'),
(2152, 'bd', 'manage_your', 'পরিচালনা করুন আপনার', '2023-02-12 19:33:07', '2023-02-12 19:33:07'),
(2153, 'bd', 'enable_pickup_point_in_checkout', 'চেকআউটে পিকআপ পয়েন্ট সক্ষম করুন', '2023-02-12 19:33:07', '2023-02-12 19:33:07'),
(2154, 'bd', 'customer_auto_approval', 'গ্রাহক স্বয়ংক্রিয় অনুমোদন', '2023-02-12 19:33:07', '2023-02-12 19:33:07'),
(2155, 'bd', 'customer_email_verification', 'গ্রাহক ইমেল যাচাইকরণ', '2023-02-12 19:33:07', '2023-02-12 19:33:07'),
(2156, 'bd', 'order_code_prefix', 'অর্ডার কোড প্রিফিক্স', '2023-02-12 19:33:07', '2023-02-12 19:33:07'),
(2157, 'bd', 'enter_prefix', 'প্রিফিক্স লিখুন', '2023-02-12 19:33:07', '2023-02-12 19:33:07'),
(2158, 'bd', 'order_code_prefix_seperator', 'অর্ডার কোড প্রিফিক্স বিভাজক', '2023-02-12 19:33:07', '2023-02-12 19:33:07'),
(2159, 'bd', 'enter_prefix_seperator', 'প্রিফিক্স বিভাজক লিখুন', '2023-02-12 19:33:07', '2023-02-12 19:33:07'),
(2160, 'bd', 'can_cancel_order_within', 'অর্ডার বাতিল করতে পারেন এর মধ্যে', '2023-02-12 19:33:07', '2023-02-12 19:33:07'),
(2161, 'bd', 'can_return_order_within', 'অর্ডার ফেরত দিতে পারেন এর মধ্যে', '2023-02-12 19:39:43', '2023-02-12 19:39:43'),
(2162, 'bd', 'you_can_manage_payment_methods', 'আপনি অর্থপ্রদানের পদ্ধতি পরিচালনা করতে পারেন', '2023-02-12 19:39:43', '2023-02-12 19:39:43'),
(2163, 'bd', 'form_here', 'এখান থেকে', '2023-02-12 19:39:43', '2023-02-12 19:39:43'),
(2164, 'bd', 'you_need_to_active_or_install', 'আপনাকে সক্রিয় বা ইনস্টল করতে হবে', '2023-02-12 19:39:43', '2023-02-12 19:39:43'),
(2165, 'bd', 'to_manage_wallets', 'ওয়ালেট পরিচালনা করতে', '2023-02-12 19:39:43', '2023-02-12 19:39:43'),
(2166, 'bd', 'enable_online_recharge', 'অনলাইন রিচার্জ সক্ষম করুন', '2023-02-12 19:39:43', '2023-02-12 19:39:43'),
(2167, 'bd', 'enable_offline_recharge', 'অফলাইন রিচার্জ সক্ষম করুন', '2023-02-12 19:39:43', '2023-02-12 19:39:43'),
(2168, 'bd', 'minimum_recharge_amount', 'ন্যূনতম রিচার্জ পরিমাণ', '2023-02-12 19:39:43', '2023-02-12 19:39:43'),
(2169, 'bd', 'business_email', 'ব্যবসায়িক ইমেল', '2023-02-12 19:39:43', '2023-02-12 19:39:43'),
(2170, 'bd', 'business_phone', 'বিজনেস ফোন', '2023-02-12 19:39:43', '2023-02-12 19:39:43'),
(2171, 'bd', 'business_address', 'বিজনেস ফোন', '2023-02-12 19:39:43', '2023-02-12 19:39:43'),
(2172, 'bd', 'credential_updated_successfully', 'শংসাপত্র সফলভাবে আপডেট করা হয়েছে', '2023-02-12 19:39:43', '2023-02-12 19:39:43'),
(2173, 'bd', 'update_failed', 'আপডেট ব্যর্থ হয়েছে৷', '2023-02-12 19:39:43', '2023-02-12 19:39:43'),
(2174, 'bd', 'please_insert_blog_name', 'অনুগ্রহ করে ব্লগের নাম লিখুন', '2023-02-12 19:39:43', '2023-02-12 19:39:43'),
(2175, 'bd', 'please_write_the_blog_name_under_225_words', 'অনুগ্রহ করে 225 শব্দের নিচে ব্লগের নাম লিখুন', '2023-02-12 19:39:43', '2023-02-12 19:39:43'),
(2176, 'bd', 'please_select_at_least_1_category', 'অনুগ্রহ করে কমপক্ষে 1টি বিভাগ নির্বাচন করুন', '2023-02-12 19:39:43', '2023-02-12 19:39:43'),
(2177, 'bd', 'something_went_wrong_please_select_category_again', 'কিছু ভুল হয়েছে, অনুগ্রহ করে আবার বিভাগ নির্বাচন করুন', '2023-02-12 19:39:43', '2023-02-12 19:39:43'),
(2178, 'bd', 'please_insert_a_valid_image', 'অনুগ্রহ করে একটি বৈধ চিত্র সন্নিবেশ করুন', '2023-02-12 19:39:43', '2023-02-12 19:39:43'),
(2179, 'bd', 'please_write_some_description', 'নুগ্রহ করে কিছু বর্ণনা লিখুন', '2023-02-12 19:39:43', '2023-02-12 19:39:43'),
(2180, 'bd', 'please_write_some_content', 'অনুগ্রহ করে কিছু বিষয়বস্তু লিখুন', '2023-02-12 19:39:43', '2023-02-12 19:39:43'),
(2181, 'bd', 'please_select_a_valid_image', 'অনুগ্রহ করে একটি বৈধ ছবি নির্বাচন করুন', '2023-02-12 19:41:38', '2023-02-12 19:41:38'),
(2182, 'bd', 'something_went_wrong_please_select_visibility_again', 'কিছু ভুল হয়েছে, দয়া করে আবার দৃশ্যমানতা নির্বাচন করুন৷', '2023-02-12 19:41:38', '2023-02-12 19:41:38'),
(2183, 'bd', 'new_blog_saved', 'নতুন ব্লগ সংরক্ষিত', '2023-02-12 19:41:38', '2023-02-12 19:41:38'),
(2184, 'bd', 'tl_commerce__edit_blog', NULL, '2023-02-12 19:41:38', '2023-02-12 19:41:38'),
(2185, 'bd', 'edit_blog', 'ব্লগ সম্পাদনা করুন', '2023-02-12 19:41:38', '2023-02-12 19:41:38'),
(2186, 'bd', 'add_new', 'নতুন যোগ করুন', '2023-02-12 19:41:38', '2023-02-12 19:41:38'),
(2187, 'bd', 'blog_updated_successfully', 'ব্লগ সফলভাবে আপডেট হয়েছে', '2023-02-12 19:41:38', '2023-02-12 19:41:38'),
(2188, 'bd', 'blog_deleted_successfully', 'ব্লগ সফলভাবে মুছে ফেলা হয়েছে', '2023-02-12 19:41:38', '2023-02-12 19:41:38'),
(2189, 'bd', 'profile_pic_is_required', 'প্রোফাইল ছবি প্রয়োজন', '2023-02-12 19:41:38', '2023-02-12 19:41:38'),
(2190, 'bd', 'invalid_selection', 'অবৈধ নির্বাচন', '2023-02-12 19:41:38', '2023-02-12 19:41:38'),
(2191, 'bd', 'profile_updated_successfully', 'প্রোফাইল সফলভাবে আপডেট হয়েছে', '2023-02-12 19:41:38', '2023-02-12 19:41:38'),
(2192, 'bd', 'product_deleted_successfully', 'পণ্য সফলভাবে মুছে ফেলা হয়েছে', '2023-02-12 19:41:38', '2023-02-12 19:41:38'),
(2193, 'bd', 'button', 'বোতাম', '2023-02-12 19:41:38', '2023-02-12 19:41:38'),
(2194, 'bd', 'select_option', 'বিকল্প নির্বাচন করুন', '2023-02-12 19:41:38', '2023-02-12 19:41:38'),
(2195, 'bd', 'latest_blogs', 'সর্বশেষ ব্লগ', '2023-02-12 19:41:38', '2023-02-12 19:41:38'),
(2196, 'bd', 'featured_blogs', 'বৈশিষ্ট্যযুক্ত ব্লগ', '2023-02-12 19:41:38', '2023-02-12 19:41:38'),
(2197, 'bd', 'category_wise', 'বিভাগ অনুযায়ী', '2023-02-12 19:41:38', '2023-02-12 19:41:38'),
(2198, 'bd', 'select_category', 'বিভাগ নির্বাচন করুন', '2023-02-12 19:41:39', '2023-02-12 19:41:39'),
(2199, 'bd', 'number_of_blogs', 'ব্লগ সংখ্যা', '2023-02-12 19:41:39', '2023-02-12 19:41:39'),
(2200, 'bd', 'title_is_visible_in_homepage_transalate_to_another_language', 'শিরোনাম হোমপেজে দৃশ্যমান। অন্য ভাষায় অনুবাদ করুন', '2023-02-12 19:41:39', '2023-02-12 19:41:39'),
(2201, 'bd', 'title_color', 'শিরোনামের রঙ', '2023-02-12 19:45:06', '2023-02-12 19:45:06'),
(2202, 'bd', 'button_title', 'বোতাম শিরোনাম', '2023-02-12 19:45:06', '2023-02-12 19:45:06'),
(2203, 'bd', 'button_title_is_visible_in_homepage_transalate_to_another_language', 'বোতাম শিরোনাম হোমপেজে দৃশ্যমান। অন্য ভাষায় অনুবাদ করুন', '2023-02-12 19:45:06', '2023-02-12 19:45:06'),
(2204, 'bd', 'button_color', 'বোতামের রঙ', '2023-02-12 19:45:06', '2023-02-12 19:45:06'),
(2205, 'bd', 'button_hover_color', 'বোতাম হোভার রঙ', '2023-02-12 19:45:06', '2023-02-12 19:45:06'),
(2206, 'bd', 'button_background_color', 'বোতামের ব্যাকগ্রাউন্ড রঙ', '2023-02-12 19:45:06', '2023-02-12 19:45:06'),
(2207, 'bd', 'button_background_hover_color', 'বোতাম ব্যাকগ্রাউন্ড হোভার রঙ', '2023-02-12 19:45:06', '2023-02-12 19:45:06'),
(2208, 'bd', 'button_border', 'বোতাম বর্ডার', '2023-02-12 19:45:06', '2023-02-12 19:45:06'),
(2209, 'bd', 'button_border_color', 'বোতাম বর্ডার রঙ', '2023-02-12 19:45:06', '2023-02-12 19:45:06'),
(2210, 'bd', 'button_border_hover_color', 'বোতাম বর্ডার হোভার রঙ', '2023-02-12 19:45:06', '2023-02-12 19:45:06'),
(2211, 'bd', 'new_collection', 'নতুন সংগ্রহ', '2023-02-12 19:45:06', '2023-02-12 19:45:06'),
(2212, 'bd', 'invalid_image', 'অবৈধ ছবি', '2023-02-12 19:45:06', '2023-02-12 19:45:06'),
(2213, 'bd', 'collection_added_successfully', 'সংগ্রহ সফলভাবে যোগ করা হয়েছে', '2023-02-12 19:45:06', '2023-02-12 19:45:06'),
(2214, 'bd', 'remove_selection', 'নির্বাচন সরান', '2023-02-12 19:45:06', '2023-02-12 19:45:06'),
(2215, 'bd', 'add_product', 'পণ্য যোগ করুন', '2023-02-12 19:45:06', '2023-02-12 19:45:06'),
(2216, 'bd', 'collection', 'সংগ্রহ', '2023-02-12 19:45:06', '2023-02-12 19:45:06'),
(2217, 'bd', 'select_products', 'পণ্য নির্বাচন করুন', '2023-02-12 19:45:06', '2023-02-12 19:45:06'),
(2218, 'bd', 'are_you_sure_to_remove_this_product', 'আপনি কি এই পণ্যটি সরাতে নিশ্চিত?', '2023-02-12 19:45:06', '2023-02-12 19:45:06'),
(2219, 'bd', 'no_product_selected', 'কোন পণ্য নির্বাচন করা হয়নি', '2023-02-12 19:45:06', '2023-02-12 19:45:06'),
(2220, 'bd', 'products_added_successfully', 'পণ্য সফলভাবে যোগ করা হয়েছে', '2023-02-12 19:45:06', '2023-02-12 19:45:06'),
(2221, 'bd', 'remove_product', 'পণ্য সরান', '2023-02-12 19:49:34', '2023-02-12 19:49:34'),
(2222, 'bd', 'custom_single_blog_page_style', 'কাস্টম একক ব্লগ পৃষ্ঠা শৈলী', '2023-02-12 19:49:34', '2023-02-12 19:49:34'),
(2223, 'bd', 'switch_on_for_custom_single_blog_page_style', 'কাস্টম একক ব্লগ পৃষ্ঠা শৈলী জন্য স্যুইচ অন', '2023-02-12 19:49:34', '2023-02-12 19:49:34'),
(2224, 'bd', 'layout', 'লেআউট', '2023-02-12 19:49:34', '2023-02-12 19:49:34'),
(2225, 'bd', 'choose_blog_single_page_layout_from_here_if_you_use_this_option_then_you_will_able_to_change_three_type_of_blog_single_page_layout__default_right_sidebar_layout_', 'এখান থেকে ব্লগ সিঙ্গেল পেজ লেআউট বেছে নিন। আপনি যদি এই বিকল্পটি ব্যবহার করেন তাহলে আপনি তিন ধরনের ব্লগ একক পৃষ্ঠার বিন্যাস (ডিফল্ট ডান সাইডবার লেআউট) পরিবর্তন করতে পারবেন।', '2023-02-12 19:49:34', '2023-02-12 19:49:34'),
(2226, 'bd', 'blog_post_title_position', 'ব্লগ পোস্ট শিরোনাম অবস্থান', '2023-02-12 19:49:34', '2023-02-12 19:49:34'),
(2227, 'bd', 'control_blog_post_title_position_from_here', 'এখান থেকে ব্লগ পোস্টের শিরোনামের অবস্থান নিয়ন্ত্রণ করুন।', '2023-02-12 19:49:34', '2023-02-12 19:49:34'),
(2228, 'bd', 'switch_on_to_display_author', 'ডিসপ্লে লেখকে স্যুইচ অন করুন', '2023-02-12 19:49:34', '2023-02-12 19:49:34'),
(2229, 'bd', 'switch_on_to_display_date', 'প্রদর্শনের তারিখে স্যুইচ অন করুন', '2023-02-12 19:49:34', '2023-02-12 19:49:34'),
(2230, 'bd', 'theme_option_saved', 'থিম বিকল্প সংরক্ষিত', '2023-02-12 19:49:34', '2023-02-12 19:49:34'),
(2231, 'bd', 'tl_commerce__manage_widgets', NULL, '2023-02-12 19:49:34', '2023-02-12 19:49:34'),
(2232, 'bd', 'available_widgets', 'অভায়লাবলে উইজেট', '2023-02-12 19:49:34', '2023-02-12 19:49:34'),
(2233, 'bd', 'add_widget', 'উইজেট যোগ করুন', '2023-02-12 19:49:34', '2023-02-12 19:49:34'),
(2234, 'bd', 'adding_widget_to_sidebar_failed', 'সাইডবারে উইজেট যোগ করা ব্যর্থ হয়েছে', '2023-02-12 19:49:34', '2023-02-12 19:49:34'),
(2235, 'bd', 'sidebar_widget_opening_failed', 'সাইডবার উইজেট খোলা ব্যর্থ হয়েছে', '2023-02-12 19:49:34', '2023-02-12 19:49:34'),
(2236, 'bd', 'widget_added_to_sidebar_failed', 'উইজেট সাইডবারে যোগ করা ব্যর্থ হয়েছে', '2023-02-12 19:49:34', '2023-02-12 19:49:34'),
(2237, 'bd', 'widget_form_submit_failed_failed', 'উইজেট ফর্ম জমা দিতে ব্যর্থ হয়েছে', '2023-02-12 19:49:34', '2023-02-12 19:49:34'),
(2238, 'bd', 'sidebar_updated', 'সাইডবার আপডেট করা হয়েছে', '2023-02-12 19:49:34', '2023-02-12 19:49:34'),
(2239, 'bd', 'widget_title', 'উইজেট শিরোনাম', '2023-02-12 19:49:34', '2023-02-12 19:49:34'),
(2240, 'bd', 'number_of_recent_blog', 'সাম্প্রতিক ব্লগের সংখ্যা', '2023-02-12 19:49:34', '2023-02-12 19:49:34'),
(2241, 'bd', 'done', 'সম্পন্ন', '2023-02-12 19:52:28', '2023-02-12 19:52:28'),
(2242, 'bd', 'widget_form_saved', 'উইজেট ফর্ম সংরক্ষিত', '2023-02-12 19:52:28', '2023-02-12 19:52:28'),
(2243, 'bd', 'number_of_featured_blog', 'বৈশিষ্ট্যযুক্ত ব্লগের সংখ্যা', '2023-02-12 19:52:28', '2023-02-12 19:52:28'),
(2244, 'bd', 'blog_featured_status_changed_successfully', 'ব্লগের বৈশিষ্ট্যযুক্ত স্থিতি সফলভাবে পরিবর্তিত হয়েছে', '2023-02-12 19:52:28', '2023-02-12 19:52:28'),
(2245, 'bd', 'blog_draft_saved', 'ব্লগ খসড়া সংরক্ষিত', '2023-02-12 19:52:28', '2023-02-12 19:52:28'),
(2246, 'bd', 'tl_commerce__tag', 'ট্যাগ যোগ করুন', '2023-02-12 19:52:28', '2023-02-12 19:52:28'),
(2247, 'bd', 'add_tag', 'ট্যাগ যোগ করুন', '2023-02-12 19:52:28', '2023-02-12 19:52:28'),
(2248, 'bd', 'this_tag_name_or_slug_is_already_available_please_insert_another', 'এই ট্যাগ নাম বা স্লাগ ইতিমধ্যে উপলব্ধ অনুগ্রহ করে অন্য একটি সন্নিবেশ করুন', '2023-02-12 19:52:28', '2023-02-12 19:52:28'),
(2249, 'bd', 'site_seo__settings', 'সাইট এসইও সেটিংস', '2023-02-12 19:52:28', '2023-02-12 19:52:28'),
(2250, 'bd', 'site_title', 'সাইটের শিরোনাম', '2023-02-12 19:52:28', '2023-02-12 19:52:28'),
(2251, 'bd', 'meta_keywords', 'মেটা কীওয়ার্ড', '2023-02-12 19:52:28', '2023-02-12 19:52:28'),
(2252, 'bd', 'seo_update_successfully', 'এসইও সফলভাবে আপডেট হয়েছে', '2023-02-12 19:52:28', '2023-02-12 19:52:28'),
(2253, 'bd', 'no', 'নং', '2023-02-12 19:52:28', '2023-02-12 19:52:28'),
(2254, 'bd', 'template', 'টেমপ্লেট', '2023-02-12 19:52:28', '2023-02-12 19:52:28'),
(2255, 'bd', 'details', 'বিস্তারিত', '2023-02-12 19:52:28', '2023-02-12 19:52:28'),
(2256, 'bd', 'enter_email_subject', 'ইমেইল বিষয় লিখুন', '2023-02-12 19:52:28', '2023-02-12 19:52:28'),
(2257, 'bd', 'variables', 'ভেরিয়েবল', '2023-02-12 19:52:28', '2023-02-12 19:52:28'),
(2258, 'bd', 'smtp_configuration', 'Smtp কনফিগারেশন', '2023-02-12 19:52:28', '2023-02-12 19:52:28'),
(2259, 'bd', 'email_configuration', 'ইমেল কনফিগারেশন', '2023-02-12 19:52:28', '2023-02-12 19:52:28'),
(2260, 'bd', 'type', 'প্রকার', '2023-02-12 19:52:28', '2023-02-12 19:52:28'),
(2261, 'bd', 'smtp', NULL, '2023-02-12 19:55:44', '2023-02-12 19:55:44'),
(2262, 'bd', 'sendmail', 'সেন্ডমেইল', '2023-02-12 19:55:44', '2023-02-12 19:55:44'),
(2263, 'bd', 'mailgun', 'মেইলগান', '2023-02-12 19:55:44', '2023-02-12 19:55:44'),
(2264, 'bd', 'mail_host', 'মেল হোস্ট', '2023-02-12 19:55:44', '2023-02-12 19:55:44'),
(2265, 'bd', 'mail_port', 'মেইল ​​পোর্ট', '2023-02-12 19:55:44', '2023-02-12 19:55:44'),
(2266, 'bd', 'mail_username', 'মেল ব্যবহারকারীর নাম', '2023-02-12 19:55:44', '2023-02-12 19:55:44'),
(2267, 'bd', 'mail_password', 'মেল পাসওয়ার্ড', '2023-02-12 19:55:44', '2023-02-12 19:55:44'),
(2268, 'bd', 'mail_encryption', 'মেল এনক্রিপশন', '2023-02-12 19:55:44', '2023-02-12 19:55:44'),
(2269, 'bd', 'mail_from_address', 'ঠিকানা থেকে মেল', '2023-02-12 19:55:44', '2023-02-12 19:55:44'),
(2270, 'bd', 'mail_from_name', 'নাম থেকে মেইল', '2023-02-12 19:55:44', '2023-02-12 19:55:44'),
(2271, 'bd', 'mailgun_domain', 'নাম থেকে 870 মেইল', '2023-02-12 19:55:44', '2023-02-12 19:55:44'),
(2272, 'bd', 'mailgun_secret', 'মেলগান সিক্রেট', '2023-02-12 19:55:44', '2023-02-12 19:55:44'),
(2273, 'bd', 'send_test_mail', 'টেস্ট মেইল ​​পাঠান', '2023-02-12 19:55:44', '2023-02-12 19:55:44'),
(2274, 'bd', 'subject', 'বিষয়', '2023-02-12 19:55:44', '2023-02-12 19:55:44'),
(2275, 'bd', 'message', 'বার্তা', '2023-02-12 19:55:44', '2023-02-12 19:55:44'),
(2276, 'bd', 'send', 'পাঠান', '2023-02-12 19:55:44', '2023-02-12 19:55:44'),
(2277, 'bd', 'users_login_activity', 'ব্যবহারকারী লগইন কার্যকলাপ', '2023-02-12 19:55:44', '2023-02-12 19:55:44'),
(2278, 'bd', 'user', 'ব্যবহারকারী', '2023-02-12 19:55:44', '2023-02-12 19:55:44'),
(2279, 'bd', 'login_at', 'লগইন করুন', '2023-02-12 19:55:44', '2023-02-12 19:55:44'),
(2280, 'bd', 'logout_at', 'লগআউট এ', '2023-02-12 19:55:44', '2023-02-12 19:55:44'),
(2281, 'bd', 'ip', 'আইপি', '2023-02-12 19:59:35', '2023-02-12 19:59:35'),
(2282, 'bd', 'operating_system', 'অপারেটিং সিস্টেম', '2023-02-12 19:59:35', '2023-02-12 19:59:35'),
(2283, 'bd', 'browser', 'ব্রাউজার', '2023-02-12 19:59:35', '2023-02-12 19:59:35'),
(2284, 'bd', '________________________bulk_action_', 'বাল্ক অ্যাকশন', '2023-02-12 19:59:35', '2023-02-12 19:59:35'),
(2285, 'bd', '________________________delete_selection_', 'নির্বাচন মুছুন', '2023-02-12 19:59:35', '2023-02-12 19:59:35'),
(2286, 'bd', '________________________apply_', 'আবেদন করুন', '2023-02-12 19:59:35', '2023-02-12 19:59:35'),
(2287, 'bd', '________________________________________no_item_selected_', 'কোন আইটেম নির্বাচন করা হয়নি', '2023-02-12 19:59:35', '2023-02-12 19:59:35'),
(2288, 'bd', '________________________________no_action_selected_', 'কোন অ্যাকশন সিলেক্ট করা হয়নি', '2023-02-12 19:59:35', '2023-02-12 19:59:35'),
(2289, 'bd', 'currency_updated_successfully', 'মুদ্রা সফলভাবে আপডেট করা হয়েছে', '2023-02-12 19:59:35', '2023-02-12 19:59:35'),
(2290, 'bd', 'brand_featured_status_updated_successfully', 'ব্র্যান্ড বৈশিষ্ট্যযুক্ত স্থিতি সফলভাবে আপডেট করা হয়েছে৷', '2023-02-12 19:59:35', '2023-02-12 19:59:35'),
(2291, 'bd', 'brand_featured_status_update_failed', 'ব্র্যান্ড বৈশিষ্ট্যযুক্ত স্থিতি আপডেট ব্যর্থ হয়েছে৷', '2023-02-12 19:59:35', '2023-02-12 19:59:35'),
(2292, 'bd', 'brand_status_updated_successfully', 'ব্র্যান্ড স্ট্যাটাস সফলভাবে আপডেট করা হয়েছে', '2023-02-12 19:59:35', '2023-02-12 19:59:35'),
(2293, 'bd', 'brand_status_update_failed', 'ব্র্যান্ড স্থিতি আপডেট ব্যর্থ হয়েছে৷', '2023-02-12 19:59:35', '2023-02-12 19:59:35'),
(2294, 'bd', 'easy_return_available', 'সহজ রিটার্ন অভায়লাবলে', '2023-02-12 19:59:35', '2023-02-12 19:59:35'),
(2295, 'bd', 'edit_collection', 'সংগ্রহ সম্পাদনা করুন', '2023-02-12 19:59:35', '2023-02-12 19:59:35'),
(2296, 'bd', 'product_remove_successfully', 'পণ্য সফলভাবে অপসারণ', '2023-02-12 19:59:35', '2023-02-12 19:59:35'),
(2297, 'bd', 'select_collection', 'সংগ্রহ নির্বাচন করুন', '2023-02-12 19:59:35', '2023-02-12 19:59:35'),
(2298, 'bd', 'collection_updated_successfully', 'সংগ্রহ সফলভাবে আপডেট করা হয়েছে', '2023-02-12 19:59:35', '2023-02-12 19:59:35'),
(2299, 'bd', 'successfully_rearranging', 'সফলভাবে পুনর্বিন্যাস করা হচ্ছে', '2023-02-12 19:59:35', '2023-02-12 19:59:35'),
(2300, 'bd', 'new_arrival', 'নতুন আগমন', '2023-02-12 19:59:35', '2023-02-12 19:59:35'),
(2301, 'bd', 'featured_products', 'বৈশিষ্ট্যযুক্ত পণ্য', '2023-02-12 20:00:24', '2023-02-12 20:00:24'),
(2302, 'bd', 'top_selling', 'টপ সেলিং', '2023-02-12 20:00:24', '2023-02-12 20:00:24'),
(2303, 'bd', 'top_reviewed', 'শীর্ষ পর্যালোচনা করা হয়েছে', '2023-02-12 20:00:24', '2023-02-12 20:00:24'),
(2304, 'bd', 'select_layouts', 'লেআউট নির্বাচন করুন', '2023-02-12 20:00:24', '2023-02-12 20:00:24'),
(2305, 'bd', 'add_new_flash_deal', 'নতুন ফ্ল্যাশ ডিল যোগ করুন', '2023-02-12 20:00:24', '2023-02-12 20:00:24'),
(2306, 'bd', 'start_date', 'শুরুর তারিখ', '2023-02-12 20:00:24', '2023-02-12 20:00:24'),
(2307, 'bd', 'expiry_date', 'মেয়াদ শেষ হওয়ার তারিখ', '2023-02-12 20:00:24', '2023-02-12 20:04:52'),
(2308, 'bd', 'new_deal', 'নতুন চুক্তি', '2023-02-12 20:00:24', '2023-02-12 20:04:52'),
(2309, 'bd', 'text_color', 'লেখার রঙ', '2023-02-12 20:00:24', '2023-02-12 20:04:52'),
(2310, 'bd', 'banner', 'ব্যানার', '2023-02-12 20:00:24', '2023-02-12 20:04:52'),
(2311, 'bd', 'deal_title_is_required', 'ডিল শিরোনাম প্রয়োজন', '2023-02-12 20:00:24', '2023-02-12 20:04:52'),
(2312, 'bd', 'permalink_is_already_taken', 'পার্মালিংক ইতিমধ্যেই নেওয়া হয়েছে', '2023-02-12 20:00:24', '2023-02-12 20:04:52'),
(2313, 'bd', 'new_deal_added_successfully', 'নতুন চুক্তি সফলভাবে যোগ করা হয়েছে', '2023-02-12 20:00:24', '2023-02-12 20:04:52'),
(2314, 'bd', 'deals_products', 'পণ্য ডিল', '2023-02-12 20:00:24', '2023-02-12 20:04:52'),
(2315, 'bd', 'to', 'প্রতি', '2023-02-12 20:00:24', '2023-02-12 20:04:52'),
(2316, 'bd', 'discount_type', 'ডিসকাউন্ট টাইপ', '2023-02-12 20:00:24', '2023-02-12 20:04:52'),
(2317, 'bd', 'update_product_discount', 'পণ্য ছাড় আপডেট করুন', '2023-02-12 20:00:24', '2023-02-12 20:04:52'),
(2318, 'bd', 'product_details', 'পণ্যের বিবরণ', '2023-02-12 20:00:24', '2023-02-12 20:04:52'),
(2319, 'bd', 'select_flash_deal', 'ফ্ল্যাশ ডিল নির্বাচন করুন', '2023-02-12 20:00:24', '2023-02-12 20:04:52'),
(2320, 'bd', 'featured_product_image', 'বৈশিষ্ট্যযুক্ত পণ্য ইমেজ', '2023-02-12 20:00:24', '2023-02-12 20:04:52'),
(2321, 'bd', 'video_url', 'ভিডিও ইউআরএল', '2023-02-12 20:07:45', '2023-02-12 20:07:45'),
(2322, 'bd', 'meta_title_is_visible_in_homepage_transalate_to_another_language', 'মেটা শিরোনাম হোমপেজে দৃশ্যমান। অন্য ভাষায় অনুবাদ করুন', '2023-02-12 20:07:45', '2023-02-12 20:07:45'),
(2323, 'bd', 'paragraph', 'অনুচ্ছেদ', '2023-02-12 20:07:45', '2023-02-12 20:07:45'),
(2324, 'bd', 'paragraph_is_visible_in_homepage_transalate_to_another_language', 'অনুচ্ছেদ হোমপেজে দৃশ্যমান। অন্য ভাষায় অনুবাদ করুন', '2023-02-12 20:07:45', '2023-02-12 20:07:45'),
(2325, 'bd', 'play_button_color', 'প্লে বোতামের রঙ', '2023-02-12 20:07:45', '2023-02-12 20:07:45'),
(2326, 'bd', 'play_button_border_color', 'প্লে বোতাম বর্ডার কালার', '2023-02-12 20:07:45', '2023-02-12 20:07:45'),
(2327, 'bd', 'password_is_required', 'পাসওয়ার্ড প্রয়োজন', '2023-02-12 20:07:45', '2023-02-12 20:07:45'),
(2328, 'bd', 'password_does_not_match', 'পাসওয়ার্ড মেলে না', '2023-02-12 20:07:45', '2023-02-12 20:07:45'),
(2329, 'bd', 'phone_is_required', 'ফোন প্রয়োজন', '2023-02-12 20:07:45', '2023-02-12 20:07:45'),
(2330, 'bd', 'phone_is_already_used', 'ফোন ইতিমধ্যে ব্যবহার করা হয়', '2023-02-12 20:07:45', '2023-02-12 20:07:45'),
(2331, 'bd', 'email_is_required', 'ইমেল প্রয়োজন', '2023-02-12 20:07:45', '2023-02-12 20:07:45'),
(2332, 'bd', 'incorrect_email', 'ভুল ইমেল', '2023-02-12 20:07:45', '2023-02-12 20:07:45'),
(2333, 'bd', 'email_is_already_used', 'ইমেল ইতিমধ্যে ব্যবহার করা হয়', '2023-02-12 20:07:45', '2023-02-12 20:07:45'),
(2334, 'bd', 'secret_login', 'গোপন লগইন', '2023-02-12 20:07:45', '2023-02-12 20:07:45'),
(2335, 'bd', 'delete_customer', 'গ্রাহক মুছুন', '2023-02-12 20:07:45', '2023-02-12 20:07:45'),
(2336, 'bd', 'status_updated_successfully', 'স্থিতি সফলভাবে আপডেট করা হয়েছে', '2023-02-12 20:07:45', '2023-02-12 20:07:45'),
(2337, 'bd', 'no_account_exists_with_this_email', 'এই ইমেলের সাথে কোনো অ্যাকাউন্ট নেই', '2023-02-12 20:07:45', '2023-02-12 20:07:45'),
(2338, 'bd', 'image_size_is_too_large', 'ছবির আকার খুব বড়', '2023-02-12 20:07:45', '2023-02-12 20:07:45'),
(2339, 'bd', 'invalid_image_format', 'অবৈধ চিত্র বিন্যাস', '2023-02-12 20:07:45', '2023-02-12 20:07:45'),
(2340, 'bd', 'address_is_required', 'ঠিকানা প্রয়োজন', '2023-02-12 20:07:45', '2023-02-12 20:07:45'),
(2341, 'bd', 'country_is_required', 'দেশ প্রয়োজন', '2023-02-12 20:09:33', '2023-02-12 20:09:33'),
(2342, 'bd', 'state_is_required', 'রাষ্ট্র প্রয়োজন', '2023-02-12 20:09:33', '2023-02-12 20:09:33'),
(2343, 'bd', 'city_is_required', 'শহর প্রয়োজন', '2023-02-12 20:09:33', '2023-02-12 20:09:33'),
(2344, 'bd', 'country_is_invalid', 'দেশটি অবৈধ৷', '2023-02-12 20:09:33', '2023-02-12 20:09:33'),
(2345, 'bd', 'state_is_invalid', 'রাজ্য অবৈধ', '2023-02-12 20:09:33', '2023-02-12 20:09:33'),
(2346, 'bd', 'city_is_invalid', 'শহরটি অবৈধ', '2023-02-12 20:09:33', '2023-02-12 20:09:33'),
(2347, 'bd', 'postal_code_is_required', 'পোস্টাল কোড প্রয়োজন', '2023-02-12 20:09:33', '2023-02-12 20:09:33'),
(2348, 'bd', 'products_removed_successfully', 'পণ্য সফলভাবে সরানো হয়েছে', '2023-02-12 20:09:33', '2023-02-12 20:09:33'),
(2349, 'bd', 'edit_deal', 'চুক্তি সম্পাদনা করুন', '2023-02-12 20:09:33', '2023-02-12 20:09:33'),
(2350, 'bd', 'deal_updated_successfully', 'চুক্তি সফলভাবে আপডেট করা হয়েছে', '2023-02-12 20:09:33', '2023-02-12 20:09:33'),
(2351, 'bd', 'attribute_deleted_failed', 'বৈশিষ্ট্য মুছে ফেলা ব্যর্থ হয়েছে', '2023-02-12 20:09:33', '2023-02-12 20:09:33'),
(2352, 'bd', 'attribute_value_delete_successfully', 'অ্যাট্রিবিউট মান সফলভাবে মুছে ফেলা হয়েছে', '2023-02-12 20:09:33', '2023-02-12 20:09:33'),
(2353, 'bd', 'attribute_deleted_successfully', 'অ্যাট্রিবিউট সফলভাবে মুছে ফেলা হয়েছে', '2023-02-12 20:09:33', '2023-02-12 20:09:33'),
(2354, 'bd', 'category_featured_status_updated_successfully', 'বিভাগ বৈশিষ্ট্যযুক্ত স্থিতি সফলভাবে আপডেট করা হয়েছে৷', '2023-02-12 20:09:33', '2023-02-12 20:09:33'),
(2355, 'bd', 'category_featured_status_update_failed', 'বিভাগ বৈশিষ্ট্যযুক্ত অবস্থা আপডেট ব্যর্থ হয়েছে', '2023-02-12 20:09:33', '2023-02-12 20:09:33'),
(2356, 'bd', 'category_status_update_failed', 'বিভাগ স্থিতি আপডেট ব্যর্থ হয়েছে', '2023-02-12 20:09:33', '2023-02-12 20:09:33'),
(2357, 'bd', 'share_options', 'ভাগ বিকল্প', '2023-02-12 20:09:33', '2023-02-12 20:09:33'),
(2358, 'bd', 'tl_commerce__edit_blog_category', NULL, '2023-02-12 20:09:33', '2023-02-12 20:09:33'),
(2359, 'bd', 'edit_blog_category', 'ব্লগ বিভাগ সম্পাদনা করুন', '2023-02-12 20:09:33', '2023-02-12 20:09:33'),
(2360, 'bd', 'blog_category_updated_successfully', 'ব্লগ বিভাগ সফলভাবে আপডেট হয়েছে', '2023-02-12 20:09:33', '2023-02-12 20:09:33'),
(2361, 'bd', 'something_went_wrong', 'কিছু ভুল হয়েছে', '2023-02-12 20:10:50', '2023-02-12 20:10:50'),
(2362, 'bd', 'tl_commerce__edit_tag', NULL, '2023-02-12 20:10:50', '2023-02-12 20:10:50'),
(2363, 'bd', 'please_write_the_tag_name_under_225_words', 'অনুগ্রহ করে 225 শব্দের নিচে ট্যাগের নাম লিখুন', '2023-02-12 20:10:50', '2023-02-12 20:10:50'),
(2364, 'bd', 'tag_updated_successfully', 'ট্যাগ সফলভাবে আপডেট হয়েছে', '2023-02-12 20:10:50', '2023-02-12 20:10:50'),
(2365, 'bd', 'tag_not_found', 'ট্যাগ পাওয়া যায়নি', '2023-02-12 20:10:50', '2023-02-12 20:10:50'),
(2366, 'bd', 'mail', 'মেইল', '2023-02-12 20:10:50', '2023-02-12 20:10:50'),
(2367, 'bd', 'social_links_', 'সামাজিক বন্ধন:', '2023-02-12 20:10:50', '2023-02-12 20:10:50'),
(2368, 'bd', 'set_social_links_from_theme_options', 'থিম বিকল্প থেকে সামাজিক লিঙ্ক সেট করুন', '2023-02-12 20:10:50', '2023-02-12 20:10:50'),
(2369, 'bd', 'widget_input_fields_saving_failed', 'উইজেট ইনপুট ক্ষেত্র সংরক্ষণ ব্যর্থ হয়েছে৷', '2023-02-12 20:10:50', '2023-02-12 20:10:50'),
(2370, 'bd', 'select_menu_group', 'মেনু গ্রুপ নির্বাচন করুন', '2023-02-12 20:10:50', '2023-02-12 20:10:50'),
(2371, 'bd', 'newsletter_short_desc', 'নিউজলেটার সংক্ষিপ্ত বিবরণ', '2023-02-12 20:10:50', '2023-02-12 20:10:50'),
(2372, 'bd', 'widget_removed_from_sidebar', 'সাইডবার থেকে উইজেট সরানো হয়েছে', '2023-02-12 20:10:50', '2023-02-12 20:10:50'),
(2373, 'bd', 'tl_commerce__add_tag', NULL, '2023-02-12 20:10:50', '2023-02-12 20:10:50'),
(2374, 'bd', 'tl_commerce__blog_comment', NULL, '2023-02-12 20:10:50', '2023-02-12 20:10:50'),
(2375, 'bd', 'approve', 'অনুমোদন করুন', '2023-02-12 20:10:50', '2023-02-12 20:10:50'),
(2376, 'bd', 'spam', 'স্প্যাম', '2023-02-12 20:10:50', '2023-02-12 20:10:50'),
(2377, 'bd', 'trash', 'আবর্জনা', '2023-02-12 20:10:50', '2023-02-12 20:10:50'),
(2378, 'bd', 'in_response_to', 'জবাবে', '2023-02-12 20:10:50', '2023-02-12 20:10:50'),
(2379, 'bd', 'submitted_on', 'জমা দেওয়া হয়', '2023-02-12 20:10:50', '2023-02-12 20:10:50'),
(2380, 'bd', 'comment_delete_confirmation', 'মন্তব্য মুছুন নিশ্চিতকরণ', '2023-02-12 20:10:50', '2023-02-12 20:10:50'),
(2381, 'bd', 'are_you_sure_you_want_to_permanently_delete_this_comment', 'আপনি কি নিশ্চিত আপনি স্থায়ীভাবে এই মন্তব্য মুছে ফেলতে চান', '2023-02-12 20:12:25', '2023-02-12 20:12:25'),
(2382, 'bd', 'bulk_action_confirmation', 'বাল্ক অ্যাকশন নিশ্চিতকরণ', '2023-02-12 20:12:25', '2023-02-12 20:12:25'),
(2383, 'bd', 'are_you_sure_you_want_to_take_this_action', 'আপনি কি নিশ্চিত আপনি এই পদক্ষেপ নিতে চান', '2023-02-12 20:12:25', '2023-02-12 20:12:25'),
(2384, 'bd', 'comment_reply', 'মন্তব্য উত্তর', '2023-02-12 20:12:25', '2023-02-12 20:12:25'),
(2385, 'bd', 'reply', 'উত্তর দিন', '2023-02-12 20:12:25', '2023-02-12 20:12:25'),
(2386, 'bd', 'mark_as_spam', 'স্প্যাম হিসেবে চিহ্নিত করুন', '2023-02-12 20:12:25', '2023-02-12 20:12:25'),
(2387, 'bd', 'unapprove', 'অনুমোদন না করা', '2023-02-12 20:12:25', '2023-02-12 20:12:25'),
(2388, 'bd', 'not_spam', 'স্প্যাম না', '2023-02-12 20:12:25', '2023-02-12 20:14:11'),
(2389, 'bd', 'delete_permanetly', 'স্থায়ীভাবে মুছুন', '2023-02-12 20:12:25', '2023-02-12 20:12:25'),
(2390, 'bd', 'restore', 'পুনরুদ্ধার করুন', '2023-02-12 20:12:25', '2023-02-12 20:12:25'),
(2391, 'bd', 'delete_all', 'সব মুছে ফেলুন', '2023-02-12 20:12:25', '2023-02-12 20:12:25'),
(2392, 'bd', 'tl_commerce__page', NULL, '2023-02-12 20:12:25', '2023-02-12 20:12:25'),
(2393, 'bd', 'add_page', 'পাতা যোগ কর', '2023-02-12 20:12:25', '2023-02-12 20:12:25'),
(2394, 'bd', 'tl_commerce__add_page', NULL, '2023-02-12 20:12:25', '2023-02-12 20:12:25'),
(2395, 'bd', 'page_title', 'পাতা শিরোনাম', '2023-02-12 20:12:25', '2023-02-12 20:14:11'),
(2396, 'bd', 'add_title', 'শিরোনাম যোগ করুন', '2023-02-12 20:12:25', '2023-02-12 20:12:25'),
(2397, 'bd', 'page_attributes', 'পৃষ্ঠা বৈশিষ্ট্য', '2023-02-12 20:12:25', '2023-02-12 20:12:25'),
(2398, 'bd', 'parents', 'অভিভাবক', '2023-02-12 20:12:25', '2023-02-12 20:12:25'),
(2399, 'bd', 'select_a_parent_page', 'একটি অভিভাবক পৃষ্ঠা নির্বাচন করুন৷', '2023-02-12 20:12:25', '2023-02-12 20:12:25'),
(2400, 'bd', 'featured_image', 'বৈশিষ্ট্যযুক্ত ইমেজ', '2023-02-12 20:12:25', '2023-02-12 20:12:25'),
(2401, 'bd', 'create_or_manage_zone', 'জোন তৈরি করুন বা পরিচালনা করুন', '2023-02-12 20:16:18', '2023-02-12 20:16:18'),
(2402, 'bd', 'no_shipping_zone_found', 'কোন শিপিং জোন পাওয়া যায়নি', '2023-02-12 20:16:18', '2023-02-12 20:16:18'),
(2403, 'bd', 'add_or_manage_shipping_zone', 'শিপিং জোন যোগ করুন বা পরিচালনা করুন', '2023-02-12 20:16:18', '2023-02-12 20:16:18'),
(2404, 'bd', 'refunded', 'ফেরত দেওয়া হয়েছে', '2023-02-12 20:16:18', '2023-02-12 20:16:18'),
(2405, 'bd', 'return_status', 'রিটার্ন স্ট্যাটাস', '2023-02-12 20:16:18', '2023-02-12 20:16:18'),
(2406, 'bd', 'product_received', 'পণ্য প্রাপ্ত', '2023-02-12 20:16:18', '2023-02-12 20:16:18');



INSERT INTO `tl_translations` (`id`, `lang`, `lang_key`, `lang_value`, `created_at`, `updated_at`) VALUES
(2407, 'bd', 'refund_code', 'রিফান্ড কোড', '2023-02-12 20:16:18', '2023-02-12 20:16:18'),
(2408, 'bd', 'price', 'দাম', '2023-02-12 20:16:18', '2023-02-12 20:16:18'),
(2409, 'bd', 'quick_action', 'দ্রুত ব্যবস্থা', '2023-02-12 20:16:18', '2023-02-12 20:16:18'),
(2410, 'bd', 'refund_request_information', 'রিফান্ড অনুরোধ তথ্য', '2023-02-12 20:16:18', '2023-02-12 20:16:18'),
(2411, 'bd', 'details_not_found', 'বিস্তারিত পাওয়া যায়নি', '2023-02-12 20:16:18', '2023-02-12 20:16:18'),
(2412, 'bd', 'reason', 'কারণ', '2023-02-12 20:16:18', '2023-02-12 20:16:18'),
(2413, 'bd', 'new_refund_reasons', 'নতুন রিফান্ড কারণ', '2023-02-12 20:16:18', '2023-02-12 20:16:18'),
(2414, 'bd', 'installupdate_theme', 'থিম ইনস্টল/আপডেট করুন', '2023-02-12 20:16:18', '2023-02-12 20:16:18'),
(2415, 'bd', 'by', 'দ্বারা:', '2023-02-12 20:16:18', '2023-02-12 20:16:18'),
(2416, 'bd', 'version', 'সংস্করণ:', '2023-02-12 20:16:18', '2023-02-12 20:16:18'),
(2417, 'bd', 'remove_confirmation', 'নিশ্চিতকরণ সরান', '2023-02-12 20:16:18', '2023-02-12 20:16:18'),
(2418, 'bd', 'are_you_sure_to_remove_this_theme', 'আপনি এই থিম সরাতে নিশ্চিত', '2023-02-12 20:16:18', '2023-02-12 20:16:18'),
(2419, 'bd', 'remove', 'অপসারণ', '2023-02-12 20:16:18', '2023-02-12 20:16:18'),
(2420, 'bd', 'activate_confirmation', 'নিশ্চিতকরণ সক্রিয় করুন', '2023-02-12 20:16:18', '2023-02-12 20:16:18'),
(2421, 'bd', 'are_you_sure_to_active_this_theme', 'আপনি এই থিম সক্রিয় করতে নিশ্চিত', '2023-02-12 20:17:38', '2023-02-12 20:17:38'),
(2422, 'bd', 'activate', 'সক্রিয় করুন', '2023-02-12 20:17:38', '2023-02-12 20:17:38'),
(2423, 'bd', 'theme_primary_color', 'থিম প্রাথমিক রঙ', '2023-02-12 20:17:38', '2023-02-12 20:17:38'),
(2424, 'bd', 'set_theme_primary_color', 'থিমের প্রাথমিক রঙ সেট করুন', '2023-02-12 20:17:38', '2023-02-12 20:17:38'),
(2425, 'bd', 'these_settings_control_the_typography_for_body', 'এই সেটিংস শরীরের জন্য টাইপোগ্রাফি নিয়ন্ত্রণ.', '2023-02-12 20:17:38', '2023-02-12 20:17:38'),
(2426, 'bd', 'font_family', 'ফন্ট পরিবার', '2023-02-12 20:17:38', '2023-02-12 20:17:38'),
(2427, 'bd', 'select__fonts', 'ফন্ট নির্বাচন করুন', '2023-02-12 20:17:38', '2023-02-12 20:17:38'),
(2428, 'bd', 'custom_font_1', 'কাস্টম ফন্ট 1', '2023-02-12 20:17:38', '2023-02-12 20:17:38'),
(2429, 'bd', 'custom_font_2', 'কাস্টম ফন্ট 2', '2023-02-12 20:17:38', '2023-02-12 20:17:38'),
(2430, 'bd', 'google_web_fonts', 'গুগল ওয়েব ফন্ট', '2023-02-12 20:17:38', '2023-02-12 20:17:38'),
(2431, 'bd', 'font_weight__style', 'ফন্ট ওজন এবং শৈলী', '2023-02-12 20:17:38', '2023-02-12 20:17:38'),
(2432, 'bd', 'font_subsets', 'ফন্ট সাবসেট', '2023-02-12 20:17:38', '2023-02-12 20:17:38'),
(2433, 'bd', 'text_align', 'পাঠ্য সারিবদ্ধ', '2023-02-12 20:17:38', '2023-02-12 20:17:38'),
(2434, 'bd', 'text_transform', 'পাঠ্য রূপান্তর', '2023-02-12 20:17:38', '2023-02-12 20:17:38'),
(2435, 'bd', 'font_size', 'অক্ষরের আকার', '2023-02-12 20:17:38', '2023-02-12 20:17:38'),
(2436, 'bd', 'size', 'আকার', '2023-02-12 20:17:38', '2023-02-12 20:17:38'),
(2437, 'bd', 'line_height', 'লাইনের উচ্চতা', '2023-02-12 20:17:38', '2023-02-12 20:17:38'),
(2438, 'bd', 'word_spacing', 'শব্দ ব্যবধান', '2023-02-12 20:17:38', '2023-02-12 20:17:38'),
(2439, 'bd', 'letter_spacing', 'অক্ষর ব্যবধান', '2023-02-12 20:17:38', '2023-02-12 20:17:38'),
(2440, 'bd', 'the_quick_brown_fox_jumps_over_the_lazy_dog', 'কুইক ব্রাউন ফক্স অলস কুকুরের উপর ঝাঁপ দেয়', '2023-02-12 20:17:38', '2023-02-12 20:17:38'),
(2441, 'bd', 'paragraph_typographyp', 'অনুচ্ছেদ টাইপোগ্রাফি(P)', '2023-02-12 20:19:32', '2023-02-12 20:19:32'),
(2442, 'bd', 'these_settings_control_the_typography_for_all_pparagraph', 'এই সেটিংস সমস্ত (p) অনুচ্ছেদের জন্য টাইপোগ্রাফি নিয়ন্ত্রণ করে।', '2023-02-12 20:19:32', '2023-02-12 20:19:32'),
(2443, 'bd', 'all_heading_typography', 'সমস্ত শিরোনাম টাইপোগ্রাফি', '2023-02-12 20:19:32', '2023-02-12 20:19:32'),
(2444, 'bd', 'these_settings_control_the_typography_for_all_heading', 'এই সেটিংস সমস্ত শিরোনামের জন্য টাইপোগ্রাফি নিয়ন্ত্রণ করে।', '2023-02-12 20:19:32', '2023-02-12 20:19:32'),
(2445, 'bd', 'h1_heading_typography', '(H1) শিরোনাম টাইপোগ্রাফি', '2023-02-12 20:19:32', '2023-02-12 20:19:32'),
(2446, 'bd', 'these_settings_control_the_typography_for_all_h1heading', 'এই সেটিংস সকল (H1)শিরোনামের জন্য টাইপোগ্রাফি নিয়ন্ত্রণ করে।', '2023-02-12 20:19:32', '2023-02-12 20:19:32'),
(2447, 'bd', 'h2_heading_typography', '(H2) শিরোনাম টাইপোগ্রাফি', '2023-02-12 20:19:32', '2023-02-12 20:19:32'),
(2448, 'bd', 'these_settings_control_the_typography_for_all_h2heading', 'এই সেটিংস সকল (H2)শিরোনামের জন্য টাইপোগ্রাফি নিয়ন্ত্রণ করে।', '2023-02-12 20:19:32', '2023-02-12 20:19:32'),
(2449, 'bd', 'h3_heading_typography', '(H3) শিরোনাম টাইপোগ্রাফি', '2023-02-12 20:19:32', '2023-02-12 20:19:32'),
(2450, 'bd', 'these_settings_control_the_typography_for_all_h3heading', 'এই সেটিংস সকল (H3)শিরোনামের জন্য টাইপোগ্রাফি নিয়ন্ত্রণ করে।', '2023-02-12 20:19:32', '2023-02-12 20:19:32'),
(2451, 'bd', 'h4_heading_typography', '(H4) শিরোনাম টাইপোগ্রাফি', '2023-02-12 20:19:32', '2023-02-12 20:19:32'),
(2452, 'bd', 'these_settings_control_the_typography_for_all_h4heading', 'এই সেটিংস সকল (H4)শিরোনামের জন্য টাইপোগ্রাফি নিয়ন্ত্রণ করে।', '2023-02-12 20:19:32', '2023-02-12 20:19:32'),
(2453, 'bd', 'h5_heading_typography', '(H5) শিরোনাম টাইপোগ্রাফি', '2023-02-12 20:19:32', '2023-02-12 20:19:32'),
(2454, 'bd', 'these_settings_control_the_typography_for_all_h5heading', 'এই সেটিংস সকল (H5)শিরোনামের জন্য টাইপোগ্রাফি নিয়ন্ত্রণ করে।', '2023-02-12 20:19:32', '2023-02-12 20:19:32'),
(2455, 'bd', 'h6_heading_typography', '(H6) শিরোনাম টাইপোগ্রাফি', '2023-02-12 20:19:32', '2023-02-12 20:19:32'),
(2456, 'bd', 'these_settings_control_the_typography_for_all_h6heading', 'এই সেটিংস সকল (H6)শিরোনামের জন্য টাইপোগ্রাফি নিয়ন্ত্রণ করে।', '2023-02-12 20:19:32', '2023-02-12 20:19:32'),
(2457, 'bd', 'these_settings_control_the_typography_for_menu', 'এই সেটিংস মেনুর জন্য টাইপোগ্রাফি নিয়ন্ত্রণ করে।', '2023-02-12 20:19:32', '2023-02-12 20:19:32'),
(2458, 'bd', 'submenu_typography', 'সাবমেনু টাইপোগ্রাফি', '2023-02-12 20:19:32', '2023-02-12 20:19:32'),
(2459, 'bd', 'these_settings_control_the_typography_for_submenu', 'এই সেটিংস সাবমেনুর জন্য টাইপোগ্রাফি নিয়ন্ত্রণ করে।', '2023-02-12 20:19:32', '2023-02-12 20:19:32'),
(2460, 'bd', 'these_settings_control_the_typography_for_button', 'এই সেটিংস বোতামের জন্য টাইপোগ্রাফি নিয়ন্ত্রণ করে।', '2023-02-12 20:19:32', '2023-02-12 20:19:32'),
(2461, 'bd', 'after_uploading_your_fonts_you_should_select_font_family_customfont1customfont2_from_dropdown_list_in_bodyparagraphheadingsmenublog_typography_section', 'আপনার ফন্টগুলি আপলোড করার পরে, আপনাকে টাইপোগ্রাফি বিভাগে ড্রপডাউন তালিকা থেকে ফন্ট পরিবার (কাস্টম-ফন্ট-1/কাস্টম-ফন্ট-2) নির্বাচন করতে হবে।', '2023-02-12 20:23:37', '2023-02-12 20:23:37'),
(2462, 'bd', 'custom_font1', 'কাস্টম ফন্ট 1', '2023-02-12 20:23:37', '2023-02-12 20:23:37'),
(2463, 'bd', 'please_enable_this_option_to_use_custom_font_1', 'অনুগ্রহ করে কাস্টম ফন্ট 1 ব্যবহার করতে এই বিকল্পটি সক্ষম করুন৷', '2023-02-12 20:23:37', '2023-02-12 20:23:37'),
(2464, 'bd', 'custom_font_1_woff', 'কাস্টম ফন্ট 1 .woff', '2023-02-12 20:23:37', '2023-02-12 20:23:37'),
(2465, 'bd', 'uploade_file', 'ফাইল আপলোড করুন', '2023-02-12 20:23:37', '2023-02-12 20:23:37'),
(2466, 'bd', 'custom_font_1_ttf', 'কাস্টম ফন্ট 1 .ttf', '2023-02-12 20:23:37', '2023-02-12 20:23:37'),
(2467, 'bd', 'custom_font_1_eot', 'কাস্টম ফন্ট 1 .eot', '2023-02-12 20:23:37', '2023-02-12 20:23:37'),
(2468, 'bd', 'custom_font2', 'কাস্টম ফন্ট 2', '2023-02-12 20:23:37', '2023-02-12 20:23:37'),
(2469, 'bd', 'please_enable_this_option_to_use_custom_font_2', 'অনুগ্রহ করে কাস্টম ফন্ট 2 ব্যবহার করতে এই বিকল্পটি সক্ষম করুন৷', '2023-02-12 20:23:37', '2023-02-12 20:23:37'),
(2470, 'bd', 'custom_font_2_woff', 'কাস্টম ফন্ট 2 .woff', '2023-02-12 20:23:37', '2023-02-12 20:23:37'),
(2471, 'bd', 'custom_font_2_ttf', 'কাস্টম ফন্ট 2 .ttf', '2023-02-12 20:23:37', '2023-02-12 20:23:37'),
(2472, 'bd', 'custom_font_2_eot', 'কাস্টম ফন্ট 2 .eot', '2023-02-12 20:23:37', '2023-02-12 20:23:37'),
(2473, 'bd', 'custom_header_style', 'কাস্টম হেডার স্টাইল', '2023-02-12 20:23:37', '2023-02-12 20:23:37'),
(2474, 'bd', 'switch_on_for_custom_header_style', 'কাস্টম হেডার শৈলী জন্য সুইচ অন.', '2023-02-12 20:23:37', '2023-02-12 20:31:51'),
(2475, 'bd', 'header_bottom_email_text', 'শিরোনাম নীচে ইমেল পাঠ্য', '2023-02-12 20:23:37', '2023-02-12 20:31:51'),
(2476, 'bd', 'set_header_bottom_email_text', 'হেডার নিচের ইমেল টেক্সট সেট করুন।', '2023-02-12 20:23:37', '2023-02-12 20:31:51'),
(2477, 'bd', 'header_top_background_color', 'হেডার টপ ব্যাকগ্রাউন্ড কালার', '2023-02-12 20:23:37', '2023-02-12 20:31:51'),
(2478, 'bd', 'set_header_top_background_color', 'হেডার টপ ব্যাকগ্রাউন্ড কালার সেট করুন।', '2023-02-12 20:23:37', '2023-02-12 20:31:51'),
(2479, 'bd', 'header_middle_background_color', 'হেডার মধ্যম পটভূমির রঙ', '2023-02-12 20:23:37', '2023-02-12 20:31:51'),
(2480, 'bd', 'set_header_middle_background_color', 'হেডার মধ্য পটভূমির রঙ সেট করুন।', '2023-02-12 20:23:37', '2023-02-12 20:31:51'),
(2481, 'bd', 'header_bottom_background_color', 'হেডারের নিচের পটভূমির রঙ', '2023-02-12 20:33:19', '2023-02-12 20:33:19'),
(2482, 'bd', 'set_header_bottom_background_color', 'হেডারের নিচের পটভূমির রঙ সেট করুন।', '2023-02-12 20:33:19', '2023-02-12 20:33:19'),
(2483, 'bd', 'header_bottom_text_color', 'শিরোলেখের নীচের পাঠ্যের রঙ', '2023-02-12 20:33:19', '2023-02-12 20:33:19'),
(2484, 'bd', 'set_header_bottom_text_color', 'হেডারের নিচের টেক্সটের রঙ সেট করুন।', '2023-02-12 20:33:19', '2023-02-12 20:33:19'),
(2485, 'bd', 'sticky_header_background_color', 'স্টিকি হেডার ব্যাকগ্রাউন্ড কালার', '2023-02-12 20:33:19', '2023-02-12 20:33:19'),
(2486, 'bd', 'set_sticky_header_background_color', 'স্টিকি হেডার ব্যাকগ্রাউন্ড কালার সেট করুন।', '2023-02-12 20:33:19', '2023-02-12 20:33:19'),
(2487, 'bd', 'header_search_form_button_color', 'হেডার অনুসন্ধান ফর্ম বোতাম রঙ', '2023-02-12 20:33:19', '2023-02-12 20:33:19'),
(2488, 'bd', 'set_header_search_form_button_color', 'হেডার সার্চ ফর্ম বোতামের রঙ সেট করুন', '2023-02-12 20:33:19', '2023-02-12 20:33:19'),
(2489, 'bd', 'header_search_form_button_hover_color', 'হেডার অনুসন্ধান ফর্ম বোতাম হোভার রঙ', '2023-02-12 20:33:19', '2023-02-12 20:33:19'),
(2490, 'bd', 'set_header_search_form_button_hover_color', 'হেডার সার্চ ফর্ম বোতাম হোভার রঙ সেট করুন', '2023-02-12 20:33:19', '2023-02-12 20:33:19'),
(2491, 'bd', 'header_search_form_button_text_color', 'হেডার অনুসন্ধান ফর্ম বোতাম পাঠ্য রঙ', '2023-02-12 20:33:19', '2023-02-12 20:33:19'),
(2492, 'bd', 'set_header_search_form_button_text_color', 'হেডার অনুসন্ধান ফর্ম বোতাম পাঠ্য রঙ সেট করুন', '2023-02-12 20:33:19', '2023-02-12 20:33:19'),
(2493, 'bd', 'header_search_form_button_hover_text_color', 'হেডার সার্চ ফর্ম বোতাম হোভার টেক্সট রঙ', '2023-02-12 20:33:19', '2023-02-12 20:33:19'),
(2494, 'bd', 'set_header_search_form_button_hover_text_color', 'হেডার সার্চ ফর্ম বোতাম হোভার টেক্সট রঙ সেট করুন', '2023-02-12 20:33:19', '2023-02-12 20:33:19'),
(2495, 'bd', 'header_icon_button_color', 'হেডার আইকন বোতামের রঙ', '2023-02-12 20:33:19', '2023-02-12 20:33:19'),
(2496, 'bd', 'set_header_icon_button_color', 'হেডার আইকন বোতামের রঙ সেট করুন', '2023-02-12 20:33:19', '2023-02-12 20:33:19'),
(2497, 'bd', 'header_icon_button_text_color', 'হেডার আইকন বোতাম পাঠ্য রঙ', '2023-02-12 20:33:19', '2023-02-12 20:33:19'),
(2498, 'bd', 'set_header_icon_button_text_color', 'হেডার আইকন বোতাম টেক্সট রঙ সেট করুন', '2023-02-12 20:33:19', '2023-02-12 20:33:19'),
(2499, 'bd', 'header_icon_button_hover_color', 'হেডার আইকন বোতাম হোভার রঙ', '2023-02-12 20:33:19', '2023-02-12 20:33:19'),
(2500, 'bd', 'set_header_icon_button_hover_color', 'হেডার আইকন বোতাম হোভার রঙ সেট করুন', '2023-02-12 20:33:19', '2023-02-12 20:33:19'),
(2501, 'bd', 'header_icon_button_hover_text_color', 'হেডার আইকন বোতাম হোভার টেক্সট রঙ', '2023-02-12 20:33:54', '2023-02-12 20:33:54'),
(2502, 'bd', 'set_header_icon_button_hover_text_color', 'হেডার আইকন বোতাম হোভার টেক্সট রঙ সেট করুন', '2023-02-12 20:33:54', '2023-02-12 20:33:54'),
(2503, 'bd', 'header_top_language_change_button_color', 'শিরোনাম শীর্ষ ভাষা পরিবর্তন বোতাম রঙ', '2023-02-12 20:33:54', '2023-02-12 20:33:54'),
(2504, 'bd', 'set_header_top_language_change_button_color', 'শীর্ষস্থানীয় ভাষা পরিবর্তন বোতামের রঙ সেট করুন', '2023-02-12 20:33:54', '2023-02-12 20:33:54'),
(2505, 'bd', 'header_top_language_change_button_text_color', 'শিরোনাম শীর্ষ ভাষা পরিবর্তন বোতাম পাঠ্য রঙ', '2023-02-12 20:33:54', '2023-02-12 20:33:54'),
(2506, 'bd', 'set_header_top_language_change_button_text_color', 'শীর্ষস্থানীয় ভাষা পরিবর্তন বোতাম পাঠ্য রঙ সেট করুন', '2023-02-12 20:33:54', '2023-02-12 20:33:54'),
(2507, 'bd', 'header_top_language_change_button_hover_color', 'শিরোনাম শীর্ষ ভাষা পরিবর্তন বোতাম হোভার রঙ', '2023-02-12 20:33:54', '2023-02-12 20:33:54'),
(2508, 'bd', 'set_header_top_language_change_button_hover_color', 'শীর্ষস্থানীয় ভাষা পরিবর্তন বোতাম হোভার রঙ সেট করুন', '2023-02-12 20:33:54', '2023-02-12 20:33:54'),
(2509, 'bd', 'header_top_language_change_button_hover_text_color', 'শিরোনাম শীর্ষ ভাষা পরিবর্তন বোতাম হোভার টেক্সট রঙ', '2023-02-12 20:33:54', '2023-02-12 20:33:54'),
(2510, 'bd', 'set_header_top_language_change_button_hover_text_color', 'শীর্ষস্থানীয় ভাষা পরিবর্তন বোতাম হোভার পাঠ্য রঙ সেট করুন', '2023-02-12 20:33:54', '2023-02-12 20:33:54'),
(2511, 'bd', 'custom_header_logo_style', 'কাস্টম হেডার লোগো শৈলী', '2023-02-12 20:33:54', '2023-02-12 20:33:54'),
(2512, 'bd', 'switch_on_for_custom_header_logo_style', 'কাস্টম হেডার লোগো শৈলী জন্য সুইচ অন.', '2023-02-12 20:33:54', '2023-02-12 20:33:54'),
(2513, 'bd', 'logo_dimensions_widthheight', 'লোগোর মাত্রা (প্রস্থ/উচ্চতা)।', '2023-02-12 20:33:54', '2023-02-12 20:33:54'),
(2514, 'bd', 'set_logo_dimensions_to_choose_width_height_and_unit', 'প্রস্থ, উচ্চতা এবং একক বেছে নিতে লোগোর মাত্রা সেট করুন।', '2023-02-12 20:33:54', '2023-02-12 20:33:54'),
(2515, 'bd', 'logo_top_and_bottom_margin', 'লোগো টপ এবং বটম মার্জিন।', '2023-02-12 20:33:54', '2023-02-12 20:33:54'),
(2516, 'bd', 'set_logo_top_and_bottom_margin', 'লোগো উপরে এবং নীচের মার্জিন সেট করুন।', '2023-02-12 20:33:54', '2023-02-12 20:33:54'),
(2517, 'bd', 'sticky_logo_dimensions_widthheight', 'স্টিকি লোগোর মাত্রা (প্রস্থ/উচ্চতা)।', '2023-02-12 20:33:54', '2023-02-12 20:33:54'),
(2518, 'bd', 'set_sticky_logo_dimensions_to_choose_width_height_and_unit', 'প্রস্থ, উচ্চতা এবং ইউনিট বেছে নিতে স্টিকি লোগোর মাত্রা সেট করুন।', '2023-02-12 20:33:54', '2023-02-12 20:33:54'),
(2519, 'bd', 'sticky_logo_top_and_bottom_margin', 'স্টিকি লোগো টপ এবং বটম মার্জিন।', '2023-02-12 20:33:54', '2023-02-12 20:33:54'),
(2520, 'bd', 'set_sticky_logo_top_and_bottom_margin', 'স্টিকি লোগো উপরে এবং নীচের মার্জিন সেট করুন।', '2023-02-12 20:33:54', '2023-02-12 20:33:54'),
(2521, 'bd', 'custom_menu_style', 'কাস্টম মেনু স্টাইল', '2023-02-12 20:35:23', '2023-02-12 20:35:23'),
(2522, 'bd', 'switch_on_for_custom_menu_style', 'কাস্টম মেনু স্টাইল জন্য সুইচ অন.', '2023-02-12 20:35:23', '2023-02-12 20:35:23'),
(2523, 'bd', 'menu_color', 'মেনু রঙ', '2023-02-12 20:35:23', '2023-02-12 20:35:23'),
(2524, 'bd', 'set_header_menu_color', 'হেডার মেনু রঙ সেট করুন।', '2023-02-12 20:35:23', '2023-02-12 20:35:23'),
(2525, 'bd', 'menu_hover_color', 'মেনু হোভার রঙ', '2023-02-12 20:35:23', '2023-02-12 20:35:23'),
(2526, 'bd', 'set_header_menu_hover_color', 'হেডার মেনু হোভার রঙ সেট করুন।', '2023-02-12 20:35:23', '2023-02-12 20:35:23'),
(2527, 'bd', 'sub_menu_color', 'সাব মেনু রঙ', '2023-02-12 20:35:23', '2023-02-12 20:35:23'),
(2528, 'bd', 'set_header_sub_menu_color', 'হেডার সাব মেনু রঙ সেট করুন।', '2023-02-12 20:35:23', '2023-02-12 20:35:23'),
(2529, 'bd', 'sub_menu_hover_color', 'সাব মেনু হোভার রঙ', '2023-02-12 20:35:23', '2023-02-12 20:35:23'),
(2530, 'bd', 'set_header_sub_menu_hover_color', 'হেডার সাব মেনু হোভার রঙ সেট করুন।', '2023-02-12 20:35:23', '2023-02-12 20:35:23'),
(2531, 'bd', 'custom_blog_style', 'কাস্টম ব্লগ স্টাইল', '2023-02-12 20:35:23', '2023-02-12 20:35:23'),
(2532, 'bd', 'switch_on_for_custom_blog_style', 'কাস্টম ব্লগ স্টাইল জন্য সুইচ অন.', '2023-02-12 20:35:23', '2023-02-12 20:35:23'),
(2533, 'bd', 'choose_blog_layout_from_here_if_you_use_this_option_then_you_will_able_to_change_three_type_of_blog_layout__default_right_sidebar_layour_', 'আপনি যদি এই বিকল্পটি ব্যবহার করেন তবে আপনি তিন ধরনের ব্লগ লেআউট পরিবর্তন করতে পারবেন (ডিফল্ট ডান সাইডবার লেয়ার)।', '2023-02-12 20:35:23', '2023-02-12 20:35:23'),
(2534, 'bd', 'blog_column', 'ব্লগ কলাম', '2023-02-12 20:35:23', '2023-02-12 20:35:23'),
(2535, 'bd', 'select_your_blog_post_column_from_here_if_you_use_this_option_then_you_will_able_to_select_three_type_of_blog_colum_layout__default_one_column_', 'আপনি যদি এই বিকল্পটি ব্যবহার করেন তবে আপনি তিন ধরনের ব্লগ কলাম বিন্যাস (ডিফল্ট ওয়ান কলাম) নির্বাচন করতে পারবেন।', '2023-02-12 20:35:23', '2023-02-12 20:35:23'),
(2536, 'bd', 'read_more_text_setting', 'আরও পাঠ্য সেটিং পড়ুন', '2023-02-12 20:35:23', '2023-02-12 20:35:23'),
(2537, 'bd', 'control_read_more_text_from_here', 'এখান থেকে আরও পাঠ্য পড়ুন নিয়ন্ত্রণ করুন।', '2023-02-12 20:35:23', '2023-02-12 20:35:23'),
(2538, 'bd', 'read_more_text', 'আরও পাঠ্য পড়ুন', '2023-02-12 20:35:23', '2023-02-12 20:35:23'),
(2539, 'bd', 'set_read_moer_text_here_if_you_use_this_option_then_you_will_able_to_set_your_won_text', 'আপনি যদি এই বিকল্পটি ব্যবহার করেন তবে আপনি আপনার জয়ী পাঠ্য সেট করতে সক্ষম হবেন।', '2023-02-12 20:35:23', '2023-02-12 20:35:23'),
(2540, 'bd', 'blog_perpage_number', 'ব্লগ প্রতি পৃষ্ঠা নম্বর', '2023-02-12 20:35:23', '2023-02-12 20:35:23'),
(2541, 'bd', 'control_the_number_blogs_to_show_on_each_page__default_show_9_', 'প্রতিটি পৃষ্ঠায় দেখানোর জন্য সংখ্যা ব্লগ নিয়ন্ত্রণ করুন (ডিফল্ট শো 9)।', '2023-02-12 20:36:10', '2023-02-12 20:36:10'),
(2542, 'bd', 'blog_pagination_position', 'ব্লগ পেজিনেশন অবস্থান', '2023-02-12 20:36:10', '2023-02-12 20:36:10'),
(2543, 'bd', 'set_blog_pagination_position', 'ব্লগ পেজিনেশন অবস্থান সেট করুন.', '2023-02-12 20:36:10', '2023-02-12 20:36:10'),
(2544, 'bd', 'blog_pagination_color', 'ব্লগ পেজিনেশন রঙ', '2023-02-12 20:36:10', '2023-02-12 20:36:10'),
(2545, 'bd', 'set_blog_pagination_color', 'ব্লগ পেজিনেশন রঙ সেট করুন।', '2023-02-12 20:36:10', '2023-02-12 20:36:10'),
(2546, 'bd', 'blog_pagination_background_color', 'ব্লগ পেজিনেশন ব্যাকগ্রাউন্ড কালার', '2023-02-12 20:36:10', '2023-02-12 20:36:10'),
(2547, 'bd', 'set_blog_pagination_background_color', 'ব্লগ পেজিনেশন ব্যাকগ্রাউন্ড কালার সেট করুন।', '2023-02-12 20:36:10', '2023-02-12 20:36:10'),
(2548, 'bd', 'blog_pagination_border_color', 'ব্লগ পেজিনেশন বর্ডার রঙ', '2023-02-12 20:36:10', '2023-02-12 20:36:10'),
(2549, 'bd', 'set_blog_pagination_border_color', 'ব্লগ পেজিনেশন বর্ডার রঙ সেট করুন।', '2023-02-12 20:36:10', '2023-02-12 20:36:10'),
(2550, 'bd', 'blog_pagination_active_color', 'ব্লগ পেজিনেশন সক্রিয় রঙ', '2023-02-12 20:36:10', '2023-02-12 20:36:10'),
(2551, 'bd', 'set_blog_pagination_active_color', 'ব্লগ পেজিনেশন সক্রিয় রঙ সেট করুন।', '2023-02-12 20:36:10', '2023-02-12 20:36:10'),
(2552, 'bd', 'blog_pagination_active_background_color', 'ব্লগ পেজিনেশন সক্রিয় পটভূমির রঙ', '2023-02-12 20:36:10', '2023-02-12 20:36:10'),
(2553, 'bd', 'set_blog_pagination_active_background_color', 'ব্লগ পেজিনেশন সক্রিয় পটভূমির রঙ সেট করুন।', '2023-02-12 20:36:10', '2023-02-12 20:36:10'),
(2554, 'bd', 'blog_pagination_active_border_color', 'ব্লগ পেজিনেশন সক্রিয় সীমানা রঙ', '2023-02-12 20:36:10', '2023-02-12 20:36:10'),
(2555, 'bd', 'set_blog_pagination_active_border_color', 'ব্লগ পেজিনেশন সক্রিয় বর্ডার রঙ সেট করুন।', '2023-02-12 20:36:10', '2023-02-12 20:36:10'),
(2556, 'bd', 'blog_pagination_hover_color', 'ব্লগ পেজিনেশন হোভার রঙ', '2023-02-12 20:36:10', '2023-02-12 20:36:10'),
(2557, 'bd', 'set_blog_pagination_hover_color', 'ব্লগ পেজিনেশন হোভার রঙ সেট করুন।', '2023-02-12 20:36:10', '2023-02-12 20:36:10'),
(2558, 'bd', 'blog_pagination_hover_background_color', 'ব্লগ পেজিনেশন হোভার ব্যাকগ্রাউন্ড কালার', '2023-02-12 20:36:10', '2023-02-12 20:36:10'),
(2559, 'bd', 'set_blog_pagination_hover_background_color', 'সেট ব্লগ পেজিনেশন হোভার ব্যাকগ্রাউন্ড কালার।', '2023-02-12 20:36:10', '2023-02-12 20:36:10'),
(2560, 'bd', 'blog_pagination_hover_border_color', 'ব্লগ পেজিনেশন হোভার বর্ডার রঙ', '2023-02-12 20:36:10', '2023-02-12 20:36:10'),
(2561, 'bd', 'set_blog_pagination_hover_border_color', 'ব্লগ পেজিনেশন হোভার বর্ডার রঙ সেট করুন।', '2023-02-12 20:37:06', '2023-02-12 20:37:06'),
(2562, 'bd', 'custom_sidebar_style', 'কাস্টম সাইডবার স্টাইল', '2023-02-12 20:37:06', '2023-02-12 20:37:06'),
(2563, 'bd', 'switch_on_for_custom_sidebar_style', 'কাস্টম সাইডবার স্টাইল জন্য সুইচ অন.', '2023-02-12 20:37:06', '2023-02-12 20:37:06'),
(2564, 'bd', 'widgets_background_color', 'উইজেট পটভূমির রঙ', '2023-02-12 20:37:06', '2023-02-12 20:37:06'),
(2565, 'bd', 'box_shadow', 'বক্স ছায়া', '2023-02-12 20:37:06', '2023-02-12 20:37:06'),
(2566, 'bd', 'offset_x', 'অফসেট এক্স', '2023-02-12 20:37:06', '2023-02-12 20:37:06'),
(2567, 'bd', 'offset_y', 'অফসেট Y', '2023-02-12 20:37:06', '2023-02-12 20:37:06'),
(2568, 'bd', 'blur_radius', 'ব্লার ব্যাসার্ধ', '2023-02-12 20:37:06', '2023-02-12 20:37:06'),
(2569, 'bd', 'spread_radius', 'ব্যাসার্ধ ছড়িয়ে দিন', '2023-02-12 20:37:06', '2023-02-12 20:37:06'),
(2570, 'bd', 'opcacity_11', 'অপাপ্যাসিটি .1-1', '2023-02-12 20:37:06', '2023-02-12 20:37:06'),
(2571, 'bd', 'shadow_color', 'ছায়া রঙ', '2023-02-12 20:37:06', '2023-02-12 20:37:06'),
(2572, 'bd', 'shadow_type', 'ছায়ার ধরন', '2023-02-12 20:37:06', '2023-02-12 20:37:06'),
(2573, 'bd', 'widget_margin', 'উইজেট মার্জিন', '2023-02-12 20:37:06', '2023-02-12 20:37:06'),
(2574, 'bd', 'widget_padding', 'উইজেট প্যাডিং', '2023-02-12 20:37:06', '2023-02-12 20:37:06'),
(2575, 'bd', 'widget_border', 'উইজেট বর্ডার', '2023-02-12 20:37:06', '2023-02-12 20:37:06'),
(2576, 'bd', 'select_style', 'স্টাইল নির্বাচন করুন', '2023-02-12 20:37:06', '2023-02-12 20:37:06'),
(2577, 'bd', 'widget_title_margin', 'উইজেট শিরোনাম মার্জিন', '2023-02-12 20:37:06', '2023-02-12 20:37:06'),
(2578, 'bd', 'widget_title_padding', 'উইজেট শিরোনাম প্যাডিং', '2023-02-12 20:37:06', '2023-02-12 20:37:06'),
(2579, 'bd', 'widget_title_color', 'উইজেট শিরোনামের রঙ', '2023-02-12 20:37:07', '2023-02-12 20:37:07'),
(2580, 'bd', 'set_widget_title_color', 'উইজেট শিরোনামের রঙ সেট করুন।', '2023-02-12 20:37:07', '2023-02-12 20:37:07'),
(2581, 'bd', 'widget_text_color', 'উইজেট পাঠ্যের রঙ', '2023-02-12 20:42:04', '2023-02-12 20:42:04'),
(2582, 'bd', 'set_widget_text_color', 'উইজেট পাঠ্যের রঙ সেট করুন।', '2023-02-12 20:42:04', '2023-02-12 20:42:04'),
(2583, 'bd', 'widget_anchor_color', 'উইজেট অ্যাঙ্কর রঙ', '2023-02-12 20:42:04', '2023-02-12 20:42:04'),
(2584, 'bd', 'set_widget_anchor_color', 'উইজেট অ্যাঙ্কর রঙ সেট করুন।', '2023-02-12 20:42:04', '2023-02-12 20:42:04'),
(2585, 'bd', 'widget_anchor_hover_color', 'উইজেট অ্যাঙ্কর হোভার রঙ', '2023-02-12 20:42:04', '2023-02-12 20:42:04'),
(2586, 'bd', 'set_widget_anchor_hover_color', 'উইজেট অ্যাঙ্কর হোভার রঙ সেট করুন।', '2023-02-12 20:42:04', '2023-02-12 20:42:04'),
(2587, 'bd', 'custom_404_style', 'কাস্টম 404 স্ট্যাটাস', '2023-02-12 20:42:04', '2023-02-12 20:42:04'),
(2588, 'bd', 'switch_on_for_custom_404_style', 'কাস্টম 404 স্ট্যাটাস জন্য স্যুইচ অন করুন।', '2023-02-12 20:42:04', '2023-02-12 20:42:04'),
(2589, 'bd', 'set_page_title', 'পৃষ্ঠার শিরোনাম সেট করুন', '2023-02-12 20:42:04', '2023-02-12 20:42:04'),
(2590, 'bd', '404_image', '৪০৪ ছবি', '2023-02-12 20:42:04', '2023-02-12 20:42:04'),
(2591, 'bd', 'upload_your_site_404_image_for_header__recommendation_png_format_', 'হেডারের জন্য আপনার সাইট ৪০৪ ছবি আপলোড করুন ( সুপারিশ png বিন্যাস )।', '2023-02-12 20:42:04', '2023-02-12 20:42:04'),
(2592, 'bd', 'button_text', 'বোতাম পাঠ্য', '2023-02-12 20:42:04', '2023-02-12 20:42:04'),
(2593, 'bd', 'button_text_color', 'বোতাম পাঠ্য রঙ', '2023-02-12 20:42:04', '2023-02-12 20:42:04'),
(2594, 'bd', 'button_hover_background_color', 'বোতাম হোভার ব্যাকগ্রাউন্ডের রঙ', '2023-02-12 20:42:04', '2023-02-12 20:42:04'),
(2595, 'bd', 'button_hover_text_color', 'বোতাম হোভার টেক্সট রঙ', '2023-02-12 20:42:04', '2023-02-12 20:42:04'),
(2596, 'bd', 'mailchimp_api_key', 'মাইলচিম্প API কী', '2023-02-12 20:42:04', '2023-02-12 20:42:04'),
(2597, 'bd', 'set_mailchimp_api_key', 'মাইলচিম্প API কী সেট করুন', '2023-02-12 20:42:04', '2023-02-12 20:42:04'),
(2598, 'bd', 'mailchimp_list_id', 'মাইলচিম্প তালিকা আইডি', '2023-02-12 20:42:04', '2023-02-12 20:42:04'),
(2599, 'bd', 'set_mailchimp_list_id', 'মাইলচিম্প তালিকা আইডি সেট করুন.', '2023-02-12 20:42:04', '2023-02-12 20:42:04'),
(2600, 'bd', 'custom_subscripton_style', 'কাস্টম সদস্যতা স্ট্যাটাস', '2023-02-12 20:42:04', '2023-02-12 20:42:04'),
(2601, 'bd', 'switch_on_for_custom_subscripton_style', 'কাস্টম সাবস্ক্রিপ্টন শৈলীর জন্য স্যুইচ অন করুন।', '2023-02-12 20:42:57', '2023-02-12 20:42:57'),
(2602, 'bd', 'form_button_text', 'ফর্ম বোতাম পাঠ্য', '2023-02-12 20:42:57', '2023-02-12 20:42:57'),
(2603, 'bd', 'form_input_background_color', 'ফর্ম ইনপুট পটভূমির রঙ', '2023-02-12 20:42:57', '2023-02-12 20:42:57'),
(2604, 'bd', 'form_input_text_color', 'ফর্ম ইনপুট পাঠ্য রঙ', '2023-02-12 20:42:57', '2023-02-12 20:42:57'),
(2605, 'bd', 'form_submit_button_color', 'ফর্ম জমা বোতাম রঙ', '2023-02-12 20:42:57', '2023-02-12 20:42:57'),
(2606, 'bd', 'form_submit_button_background_color', 'ফর্ম জমা দেওয়ার বোতামের পটভূমির রঙ', '2023-02-12 20:42:57', '2023-02-12 20:42:57'),
(2607, 'bd', 'form_submit_button_hover_color', 'ফর্ম জমা দিন বোতাম হোভার রঙ', '2023-02-12 20:42:57', '2023-02-12 20:42:57'),
(2608, 'bd', 'form_submit_button_hover_background_color', 'ফর্ম জমা দিন বোতাম হোভার ব্যাকগ্রাউন্ডের রঙ', '2023-02-12 20:42:57', '2023-02-12 20:42:57'),
(2609, 'bd', 'social_profile_links', 'সামাজিক প্রোফাইল লিঙ্ক', '2023-02-12 20:42:57', '2023-02-12 20:42:57'),
(2610, 'bd', 'add_social_icon_and_url', 'সামাজিক আইকন এবং ইউআরএল যোগ করুন।', '2023-02-12 20:42:57', '2023-02-12 20:42:57'),
(2611, 'bd', 'add_slide', 'স্লাইড যোগ করুন', '2023-02-12 20:42:57', '2023-02-12 20:42:57'),
(2612, 'bd', 'custom_social_style', 'কাস্টম সামাজিক শৈলী', '2023-02-12 20:42:57', '2023-02-12 20:42:57'),
(2613, 'bd', 'set_custom_social_style', 'কাস্টম সামাজিক শৈলী সেট করুন।', '2023-02-12 20:42:57', '2023-02-12 20:42:57'),
(2614, 'bd', '_social_background_color', 'সামাজিক পটভূমির রঙ', '2023-02-12 20:42:57', '2023-02-12 20:42:57'),
(2615, 'bd', 'set__social_background_color', 'সামাজিক পটভূমির রঙ সেট করুন', '2023-02-12 20:42:57', '2023-02-12 20:42:57'),
(2616, 'bd', '_social_border_color', 'সামাজিক সীমানা রঙ', '2023-02-12 20:42:57', '2023-02-12 20:42:57'),
(2617, 'bd', 'set__social_border_color', 'সামাজিক সীমানা রঙ সেট করুন', '2023-02-12 20:42:57', '2023-02-12 20:42:57'),
(2618, 'bd', '_social_color', 'সামাজিক রঙ', '2023-02-12 20:42:57', '2023-02-12 20:42:57'),
(2619, 'bd', '_social_hover_color', 'সামাজিক হোভার রঙ', '2023-02-12 20:42:57', '2023-02-12 20:42:57'),
(2620, 'bd', '_social_hover_border_color', 'সামাজিক হোভার বর্ডার রঙ', '2023-02-12 20:42:57', '2023-02-12 20:42:57'),
(2621, 'bd', 'social_hover_background_color', 'সামাজিক হোভার পটভূমির রঙ', '2023-02-12 20:43:59', '2023-02-12 20:43:59'),
(2622, 'bd', 'custom_footer_style', 'কাস্টম ফুটার শৈলী', '2023-02-12 20:43:59', '2023-02-12 20:43:59'),
(2623, 'bd', 'switch_on_for_custom_footer_style', 'কাস্টম ফুটার শৈলী জন্য সুইচ অন.', '2023-02-12 20:43:59', '2023-02-12 20:43:59'),
(2624, 'bd', 'custom_footer_padding', 'কাস্টম ফুটার প্যাডিং.', '2023-02-12 20:43:59', '2023-02-12 20:43:59'),
(2625, 'bd', 'set_footer_padding', 'ফুটার প্যাডিং সেট করুন।', '2023-02-12 20:43:59', '2023-02-12 20:43:59'),
(2626, 'bd', 'footer_background_color', 'পাদচরণ পটভূমির রঙ', '2023-02-12 20:43:59', '2023-02-12 20:43:59'),
(2627, 'bd', 'set_background_color', 'পটভূমির রঙ সেট করুন', '2023-02-12 20:43:59', '2023-02-12 20:43:59'),
(2628, 'bd', 'footer_text_color', 'ফুটার টেক্সট রঙ', '2023-02-12 20:43:59', '2023-02-12 20:43:59'),
(2629, 'bd', 'set_text_color', 'পাঠ্যের রঙ সেট করুন', '2023-02-12 20:43:59', '2023-02-12 20:43:59'),
(2630, 'bd', 'footer_anchor_color', 'ফুটার অ্যাঙ্কর রঙ', '2023-02-12 20:43:59', '2023-02-12 20:43:59'),
(2631, 'bd', 'set_footer_anchor_color', 'ফুটার অ্যাঙ্কর রঙ সেট করুন', '2023-02-12 20:43:59', '2023-02-12 20:43:59'),
(2632, 'bd', 'footer_anchor_hover_color', 'ফুটার অ্যাঙ্কর হোভার রঙ', '2023-02-12 20:43:59', '2023-02-12 20:43:59'),
(2633, 'bd', 'set_footer_anchor_hover_color', 'ফুটার অ্যাঙ্কর হোভার রঙ সেট করুন', '2023-02-12 20:43:59', '2023-02-12 20:43:59'),
(2634, 'bd', 'css_code', 'সিএসএস কোড', '2023-02-12 20:43:59', '2023-02-12 20:43:59'),
(2635, 'bd', 'paste_your_css_code_here', 'এখানে আপনার সিএসএস কোড পেস্ট করুন।', '2023-02-12 20:43:59', '2023-02-12 20:43:59'),
(2636, 'bd', 'plugings', 'প্লাগিংস', '2023-02-12 20:43:59', '2023-02-12 20:43:59'),
(2637, 'bd', 'installupdate_plugin', 'প্লাগইন ইনস্টল/আপডেট করুন', '2023-02-12 20:43:59', '2023-02-12 20:43:59'),
(2638, 'bd', 'are_you_sure_to_remove_this_plugin', 'আপনি এই প্লাগইন অপসারণ নিশ্চিত', '2023-02-12 20:43:59', '2023-02-12 20:43:59'),
(2639, 'bd', 'deactive_confirmation', 'নিষ্ক্রিয় নিশ্চিতকরণ', '2023-02-12 20:43:59', '2023-02-12 20:43:59'),
(2640, 'bd', 'are_you_sure_to_deactive_this_plugin', 'আপনি এই প্লাগইন নিষ্ক্রিয় করতে নিশ্চিত', '2023-02-12 20:43:59', '2023-02-12 20:43:59'),
(2641, 'bd', 'deactivate', 'নিষ্ক্রিয় করুন', '2023-02-12 20:44:49', '2023-02-12 20:44:49'),
(2642, 'bd', 'are_you_sure_to_active_this_plugin', 'আপনি এই প্লাগইন সক্রিয় করতে নিশ্চিত', '2023-02-12 20:44:49', '2023-02-12 20:44:49'),
(2643, 'bd', 'add_new_user', 'নতুন ব্যবহারকারী যোগ করুন', '2023-02-12 20:44:49', '2023-02-12 20:44:49'),
(2644, 'bd', 'add_user', 'ব্যবহারকারী যোগ করুন', '2023-02-12 20:44:49', '2023-02-12 20:44:49'),
(2645, 'bd', 'assign_role', 'ভূমিকা বরাদ্দ করুন', '2023-02-12 20:44:49', '2023-02-12 20:44:49'),
(2646, 'bd', 'select_a_role', 'একটি ভূমিকা নির্বাচন করুন', '2023-02-12 20:44:49', '2023-02-12 20:44:49'),
(2647, 'bd', 'add_role', 'ভূমিকা যোগ করুন', '2023-02-12 20:44:49', '2023-02-12 20:44:49'),
(2648, 'bd', 'give_role_name', 'ভূমিকার নাম দিন', '2023-02-12 20:44:49', '2023-02-12 20:44:49'),
(2649, 'bd', 'module', 'মডিউল', '2023-02-12 20:44:49', '2023-02-12 20:44:49'),
(2650, 'bd', 'feature', 'বৈশিষ্ট্য', '2023-02-12 20:44:49', '2023-02-12 20:44:49'),
(2651, 'bd', 'show', 'দেখান', '2023-02-12 20:44:49', '2023-02-12 20:44:49'),
(2652, 'bd', 'create', 'সৃষ্টি', '2023-02-12 20:44:49', '2023-02-12 20:44:49'),
(2653, 'bd', 'manage', 'পরিচালনা করুন', '2023-02-12 20:44:49', '2023-02-12 20:44:49'),
(2654, 'bd', 'show_', 'দেখান', '2023-02-12 20:44:49', '2023-02-12 20:44:49'),
(2655, 'bd', 'create_', 'সৃষ্টি', '2023-02-12 20:44:49', '2023-02-12 20:44:49'),
(2656, 'bd', 'edit_', 'সম্পাদনা করুন', '2023-02-12 20:44:49', '2023-02-12 20:44:49'),
(2657, 'bd', 'delete_', 'মুছে ফেলা', '2023-02-12 20:44:49', '2023-02-12 20:44:49'),
(2658, 'bd', 'manage_', 'পরিচালনা করুন', '2023-02-12 20:44:49', '2023-02-12 20:44:49'),
(2659, 'bd', 'update_role', 'ভূমিকা আপডেট করুন', '2023-02-12 20:44:49', '2023-02-12 20:44:49'),
(2660, 'bd', 'products_report', 'পণ্য রিপোর্ট', '2023-02-12 20:44:49', '2023-02-12 20:44:49'),
(2661, 'bd', 'product_category', 'পণ্য তালিকা', '2023-02-12 20:45:18', '2023-02-12 20:45:18'),
(2662, 'bd', 'in_stock', 'স্টকে', '2023-02-12 20:45:18', '2023-02-12 20:45:18'),
(2663, 'bd', 'total', 'মোট', '2023-02-12 20:45:18', '2023-02-12 20:45:18'),
(2664, 'bd', 'user_keyword_search_report', 'ব্যবহারকারীর কীওয়ার্ড অনুসন্ধান প্রতিবেদন', '2023-02-12 20:45:18', '2023-02-12 20:45:18'),
(2665, 'bd', 'search_key', 'অনুসন্ধান কী', '2023-02-12 20:45:18', '2023-02-12 20:45:18'),
(2666, 'bd', 'num_of_search', 'অনুসন্ধানের সংখ্যা', '2023-02-12 20:45:18', '2023-02-12 20:45:18'),
(2667, 'bd', 'total_search', 'মোট অনুসন্ধান', '2023-02-12 20:45:18', '2023-02-12 20:45:18'),
(2668, 'bd', 'products_wishlist_report', 'পণ্য ইচ্ছা তালিকা রিপোর্ট', '2023-02-12 20:45:18', '2023-02-12 20:45:18'),
(2669, 'bd', 'num_of_wish', 'ইচ্ছার সংখ্যা', '2023-02-12 20:45:18', '2023-02-12 20:45:18'),
(2670, 'bd', 'total_wishlist', 'মোট ইচ্ছা তালিকা', '2023-02-12 20:45:18', '2023-02-12 20:45:18'),
(2671, 'bd', 'custom_notifications', 'কাস্টম বিজ্ঞপ্তি', '2023-02-12 20:45:18', '2023-02-12 20:45:18'),
(2672, 'bd', 'compose', 'রচনা করা', '2023-02-12 20:45:18', '2023-02-12 20:45:18'),
(2673, 'bd', 'delete_selected', 'মুছে নির্বাচিত', '2023-02-12 20:45:18', '2023-02-12 20:45:18'),
(2674, 'bd', 'sender', 'প্রেরক', '2023-02-12 20:45:18', '2023-02-12 20:45:18'),
(2675, 'bd', 'new_custom_notifications', 'নতুন কাস্টম বিজ্ঞপ্তি', '2023-02-12 20:45:18', '2023-02-12 20:45:18'),
(2676, 'bd', 'all_notifications', 'সমস্ত বিজ্ঞপ্তি', '2023-02-12 20:45:18', '2023-02-12 20:45:18'),
(2677, 'bd', 'send_to', 'পাঠানো', '2023-02-12 20:45:18', '2023-02-12 20:45:18'),
(2678, 'bd', 'all_customers', 'সকল গ্রাহক', '2023-02-12 20:45:18', '2023-02-12 20:45:18'),
(2679, 'bd', 'specific_customers', 'নির্দিষ্ট গ্রাহক', '2023-02-12 20:45:18', '2023-02-12 20:45:18'),
(2680, 'bd', 'all_users', 'সকল ব্যবহারকারী', '2023-02-12 20:45:18', '2023-02-12 20:45:18'),
(2681, 'bd', 'specific_users', 'নির্দিষ্ট ব্যবহারকারী', '2023-02-12 20:45:50', '2023-02-12 20:45:50'),
(2682, 'bd', 'specific_user_role', 'নির্দিষ্ট ব্যবহারকারীর ভূমিকা', '2023-02-12 20:45:50', '2023-02-12 20:45:50'),
(2683, 'bd', 'select_customers', 'গ্রাহক নির্বাচন করুন', '2023-02-12 20:45:50', '2023-02-12 20:45:50'),
(2684, 'bd', 'select_users', 'ব্যবহারকারী নির্বাচন করুন', '2023-02-12 20:45:50', '2023-02-12 20:45:50'),
(2685, 'bd', 'select_user_roles', 'ব্যবহারকারীর ভূমিকা নির্বাচন করুন', '2023-02-12 20:45:50', '2023-02-12 20:45:50'),
(2686, 'bd', 'notification_type', 'বিজ্ঞপ্তির ধরন', '2023-02-12 20:45:50', '2023-02-12 20:45:50'),
(2687, 'bd', 'dashboard__email', 'ড্যাশবোর্ড এবং ইমেল', '2023-02-12 20:45:50', '2023-02-12 20:45:50'),
(2688, 'bd', 'notification_subject', 'বিজ্ঞপ্তির বিষয়', '2023-02-12 20:45:50', '2023-02-12 20:45:50'),
(2689, 'bd', 'send_now', 'এখন পাঠান', '2023-02-12 20:45:50', '2023-02-12 20:45:50'),
(2690, 'bd', 'add_new_coupon', 'নতুন কুপন যোগ করুন', '2023-02-12 20:45:50', '2023-02-12 20:45:50'),
(2691, 'bd', 'amount_type', 'পরিমাণের ধরন', '2023-02-12 20:45:50', '2023-02-12 20:45:50'),
(2692, 'bd', 'usage__limit', 'ব্যবহার/সীমা', '2023-02-12 20:45:50', '2023-02-12 20:45:50'),
(2693, 'bd', 'new_coupon', 'নতুন কুপন', '2023-02-12 20:45:50', '2023-02-12 20:45:50'),
(2694, 'bd', 'coupon', 'কুপন', '2023-02-12 20:45:50', '2023-02-12 20:45:50'),
(2695, 'bd', 'usage_restriction', 'ব্যবহারের সীমাবদ্ধতা', '2023-02-12 20:45:50', '2023-02-12 20:45:50'),
(2696, 'bd', 'usage_limits', 'ব্যবহারের সীমা', '2023-02-12 20:45:50', '2023-02-12 20:45:50'),
(2697, 'bd', 'coupon_code', 'কুপন কোড', '2023-02-12 20:45:50', '2023-02-12 20:45:50'),
(2698, 'bd', 'discount_amount_type', 'ডিসকাউন্ট পরিমাণ প্রকার', '2023-02-12 20:45:50', '2023-02-12 20:45:50'),
(2699, 'bd', 'discount_amount', 'হ্রাসকৃত মুল্য', '2023-02-12 20:45:50', '2023-02-12 20:45:50'),
(2700, 'bd', 'allow_free_shipping', 'বিনামূল্যে শিপিং অনুমতি দিন', '2023-02-12 20:45:50', '2023-02-12 20:45:50'),
(2701, 'bd', 'coupon_expiry_date', 'কুপন মেয়াদ শেষ হওয়ার তারিখ', '2023-02-12 20:46:35', '2023-02-12 20:46:35'),
(2702, 'bd', 'minimum_spend', 'ন্যূনতম ব্যয়', '2023-02-12 20:46:35', '2023-02-12 20:46:35'),
(2703, 'bd', 'no_minimum', 'ন্যূনতম নয়', '2023-02-12 20:46:35', '2023-02-12 20:46:35'),
(2704, 'bd', 'maximum_spend', 'সর্বোচ্চ ব্যয়', '2023-02-12 20:46:35', '2023-02-12 20:46:35'),
(2705, 'bd', 'no_maximum', 'সর্বোচ্চ নেই', '2023-02-12 20:46:35', '2023-02-12 20:46:35'),
(2706, 'bd', 'individual_use_only', 'শুধুমাত্র ব্যক্তিগত ব্যবহার', '2023-02-12 20:46:35', '2023-02-12 20:46:35'),
(2707, 'bd', 'exclude_sales_items', 'বিক্রয় আইটেম বাদ', '2023-02-12 20:46:35', '2023-02-12 20:46:35'),
(2708, 'bd', 'exclude_product', 'পণ্য বাদ', '2023-02-12 20:46:35', '2023-02-12 20:46:35'),
(2709, 'bd', 'exclude_brands', 'ব্র্যান্ডগুলি বাদ দিন', '2023-02-12 20:46:35', '2023-02-12 20:46:35'),
(2710, 'bd', 'exclude_categories', 'বিভাগগুলি বাদ দিন', '2023-02-12 20:46:35', '2023-02-12 20:46:35'),
(2711, 'bd', 'allowed_email', 'অনুমোদিত ইমেল', '2023-02-12 20:46:35', '2023-02-12 20:46:35'),
(2712, 'bd', 'usage_limit_per_coupon', 'কুপন প্রতি ব্যবহারের সীমা', '2023-02-12 20:46:35', '2023-02-12 20:46:35'),
(2713, 'bd', 'unlimited_usage', 'সীমাহীন ব্যবহার', '2023-02-12 20:46:35', '2023-02-12 20:46:35'),
(2714, 'bd', 'usage_limit_per_user', 'ব্যবহারকারী প্রতি ব্যবহারের সীমা', '2023-02-12 20:46:35', '2023-02-12 20:46:35'),
(2715, 'bd', 'no_brand_selected', 'কোন ব্র্যান্ড নির্বাচিত', '2023-02-12 20:46:35', '2023-02-12 20:46:35'),
(2716, 'bd', 'no_category_selected', 'কোন শ্রেণী নির্বাচন করা হয়নি', '2023-02-12 20:46:35', '2023-02-12 20:46:35'),
(2717, 'bd', 'instruction', 'নির্দেশ', '2023-02-12 20:46:35', '2023-02-12 20:46:35'),
(2718, 'bd', 'client_id', 'ক্লায়েন্ট আইডি', '2023-02-12 20:46:35', '2023-02-12 20:46:35'),
(2719, 'bd', 'client_secret', 'ক্লায়েন্ট সিক্রেট', '2023-02-12 20:46:35', '2023-02-12 20:46:35'),
(2720, 'bd', 'sandbox_mode', 'স্যান্ডবক্স মোড', '2023-02-12 20:46:35', '2023-02-12 20:46:35'),
(2721, 'bd', 'stripe_public_key', 'স্ট্রাইপ পাবলিক কী', '2023-02-12 20:47:24', '2023-02-12 20:47:24'),
(2722, 'bd', 'stripe_secret_key', 'স্ট্রাইপ সিক্রেট কী', '2023-02-12 20:47:24', '2023-02-12 20:47:24'),
(2723, 'bd', 'payment_method', 'মূল্যপরিশোধ পদ্ধতি', '2023-02-12 20:47:24', '2023-02-12 20:47:24'),
(2724, 'bd', 'payment_for', 'এর জন্য অর্থপ্রদান', '2023-02-12 20:47:24', '2023-02-12 20:47:24'),
(2725, 'bd', 'payment_details', 'পেমেন্ট বিবরণ', '2023-02-12 20:47:24', '2023-02-12 20:47:24'),
(2726, 'bd', 'add_pickup_point', 'পিকআপ পয়েন্ট যোগ করুন', '2023-02-12 20:47:24', '2023-02-12 20:47:24'),
(2727, 'bd', 'pickup_point', 'সংগ্রহের স্থান', '2023-02-12 20:47:24', '2023-02-12 20:47:24'),
(2728, 'bd', 'city', 'শহর', '2023-02-12 20:47:24', '2023-02-12 20:47:24'),
(2729, 'bd', '________bulk_action_', 'বাল্ক অ্যাকশন', '2023-02-12 20:47:24', '2023-02-12 20:47:24'),
(2730, 'bd', '________delete_selection_', 'নির্বাচন মুছুন', '2023-02-12 20:47:24', '2023-02-12 20:47:24'),
(2731, 'bd', '________apply_', 'আবেদন করুন', '2023-02-12 20:47:24', '2023-02-12 20:47:24'),
(2732, 'bd', '____________________no_item_selected_', 'কোন আইটেম নির্বাচন করা হয়নি', '2023-02-12 20:47:24', '2023-02-12 20:47:24'),
(2733, 'bd', '________________no_action_selected_', 'কোনো অ্যাকশন বেছে নেওয়া হয়নি', '2023-02-12 20:47:24', '2023-02-12 20:47:24'),
(2734, 'bd', 'create_pickup_points', 'পিকআপ পয়েন্ট তৈরি করুন', '2023-02-12 20:47:24', '2023-02-12 20:47:24'),
(2735, 'bd', 'add_pickup_points', 'পিকআপ পয়েন্ট যোগ করুন', '2023-02-12 20:47:24', '2023-02-12 20:47:24'),
(2736, 'bd', 'pickup_point_name', 'পিকআপ পয়েন্টের নাম', '2023-02-12 20:47:24', '2023-02-12 20:47:24'),
(2737, 'bd', 'give_pickup_point_name', 'পিকআপ পয়েন্টের নাম দিন', '2023-02-12 20:47:24', '2023-02-12 20:47:24'),
(2738, 'bd', 'pickup_point_phone', 'পিকআপ পয়েন্ট ফোন', '2023-02-12 20:47:24', '2023-02-12 20:47:24'),
(2739, 'bd', 'give_pickup_point_phone', 'পিকআপ পয়েন্ট ফোন দিন', '2023-02-12 20:47:24', '2023-02-12 20:47:24'),
(2740, 'bd', 'give_pickup_point_location', 'পিকআপ পয়েন্টের অবস্থান দিন', '2023-02-12 20:47:24', '2023-02-12 20:47:24'),
(2741, 'bd', 'select_city', 'শহর নির্বাচন করুন', '2023-02-12 20:47:52', '2023-02-12 20:47:52'),
(2742, 'bd', 'select_a_city', 'একটি শহর নির্বাচন করুন', '2023-02-12 20:47:52', '2023-02-12 20:47:52'),
(2743, 'bd', 'create_new_carrier', 'নতুন ক্যারিয়ার তৈরি করুন', '2023-02-12 20:47:52', '2023-02-12 20:47:52'),
(2744, 'bd', 'tracking_url', 'ট্র্যাকিং ইউআরএল', '2023-02-12 20:47:52', '2023-02-12 20:47:52'),
(2745, 'bd', 'add_new_shipping_courier', 'নতুন শিপিং কুরিয়ার যোগ করুন', '2023-02-12 20:47:52', '2023-02-12 20:47:52'),
(2746, 'bd', 'type_url', 'ইউআরএল টাইপ করুন', '2023-02-12 20:47:52', '2023-02-12 20:47:52'),
(2747, 'bd', 'shipping_courier_information', 'শিপিং কুরিয়ার তথ্য', '2023-02-12 20:47:52', '2023-02-12 20:47:52'),
(2748, 'bd', 'customer_details', 'কাস্টমার বিস্তারিত', '2023-02-12 20:47:52', '2023-02-12 20:47:52'),
(2749, 'bd', 'id', 'আইডি', '2023-02-12 20:47:52', '2023-02-12 20:47:52'),
(2750, 'bd', 'registered_date', 'নিবন্ধিত তারিখ', '2023-02-12 20:47:52', '2023-02-12 20:47:52'),
(2751, 'bd', 'total_purchase', 'মোট ক্রয়', '2023-02-12 20:47:52', '2023-02-12 20:47:52'),
(2752, 'bd', 'total_orders', 'মোট অর্ডার', '2023-02-12 20:47:52', '2023-02-12 20:47:52'),
(2753, 'bd', 'reviews', 'রিভিউ', '2023-02-12 20:47:52', '2023-02-12 20:47:52'),
(2754, 'bd', 'retun_requests', 'রিটার্ন অনুরোধ', '2023-02-12 20:47:52', '2023-02-12 20:48:27'),
(2755, 'bd', 'addresses', 'ঠিকানা', '2023-02-12 20:47:52', '2023-02-12 20:47:52'),
(2756, 'bd', 'wishlists', 'চাহিদা তালিকা', '2023-02-12 20:47:52', '2023-02-12 20:47:52'),
(2757, 'bd', 'tax', 'ট্যাক্স', '2023-02-12 20:47:52', '2023-02-12 20:47:52'),
(2758, 'bd', 'delivery_cost', 'ডেলিভারি খরচ', '2023-02-12 20:47:52', '2023-02-12 20:47:52'),
(2759, 'bd', 'return_date', 'ফেরার তারিখ', '2023-02-12 20:47:52', '2023-02-12 20:47:52'),
(2760, 'bd', 'total_stock', 'মোট স্টক', '2023-02-12 20:47:52', '2023-02-12 20:47:52'),
(2761, 'bd', 'pickup_point_orders', 'পিকআপ পয়েন্ট অর্ডার', '2023-02-12 20:49:02', '2023-02-12 20:49:02'),
(2762, 'bd', 'edit_condition', 'এডিট কন্ডিশন', '2023-02-12 20:49:02', '2023-02-12 20:49:02'),
(2763, 'bd', 'condition_updated_successfully', 'শর্ত সফলভাবে আপডেট করা হয়েছে', '2023-02-12 20:49:02', '2023-02-12 20:49:02'),
(2764, 'bd', 'edit_slider', 'স্লাইডার সম্পাদনা করুন', '2023-02-12 20:49:02', '2023-02-12 20:49:02'),
(2765, 'bd', 'slider_updated_successfully', 'স্লাইডার সফলভাবে আপডেট হয়েছে', '2023-02-12 20:49:02', '2023-02-12 20:49:02'),
(2766, 'bd', 'plugin_activate_successfully', 'প্লাগইন সফলভাবে সক্রিয়', '2023-02-12 20:49:02', '2023-02-12 20:49:02'),
(2767, 'bd', 'wallet_transactions', 'ওয়ালেট লেনদেন', '2023-02-12 20:49:02', '2023-02-12 20:49:02'),
(2768, 'bd', 'offline_payment_methods', 'অফলাইন পেমেন্ট পদ্ধতি', '2023-02-12 20:49:02', '2023-02-12 20:49:02'),
(2769, 'bd', 'change_status_to_pending', 'মুলতুবি অবস্থা পরিবর্তন করুন', '2023-02-12 20:49:02', '2023-02-12 20:49:02'),
(2770, 'bd', 'change_status_to_accept', 'গ্রহণ করার জন্য স্থিতি পরিবর্তন করুন', '2023-02-12 20:49:02', '2023-02-12 20:49:02'),
(2771, 'bd', 'change_status_to_decline', 'প্রত্যাখ্যানে স্থিতি পরিবর্তন করুন', '2023-02-12 20:49:02', '2023-02-12 20:49:02'),
(2772, 'bd', 'transaction_type', 'লেনদেন প্রকার', '2023-02-12 20:49:02', '2023-02-12 20:49:02'),
(2773, 'bd', 'credited', 'ক্রেডিট', '2023-02-12 20:49:02', '2023-02-12 20:49:02'),
(2774, 'bd', 'debited', 'ডেবিট', '2023-02-12 20:49:02', '2023-02-12 20:49:02'),
(2775, 'bd', 'payment_options', 'পেমেন্ট অপশন', '2023-02-12 20:49:02', '2023-02-12 20:49:02'),
(2776, 'bd', 'online', 'অনলাইন', '2023-02-12 20:49:02', '2023-02-12 20:49:02'),
(2777, 'bd', 'offline', 'অফলাইন', '2023-02-12 20:49:02', '2023-02-12 20:49:02'),
(2778, 'bd', 'manual', 'ম্যানুয়াল', '2023-02-12 20:49:02', '2023-02-12 20:49:02'),
(2779, 'bd', 'cart', 'কার্ট', '2023-02-12 20:49:02', '2023-02-12 20:49:02'),
(2780, 'bd', 'cashback', 'নগদ ফেরত', '2023-02-12 20:49:02', '2023-02-12 20:49:02'),
(2781, 'bd', 'refund', 'ফেরত', '2023-02-12 20:49:56', '2023-02-12 20:49:56'),
(2782, 'bd', 'accept', 'গ্রহণ করুন', '2023-02-12 20:49:56', '2023-02-12 20:49:56'),
(2783, 'bd', 'declined', 'অস্বীকার করেছে', '2023-02-12 20:49:56', '2023-02-12 20:49:56'),
(2784, 'bd', 'tranaction_id', 'লেনদেন আইডি', '2023-02-12 20:49:56', '2023-02-12 20:49:56'),
(2785, 'bd', 'executed_by', 'দ্বারা মৃত্যুদন্ড কার্যকর করা হয়েছে', '2023-02-12 20:49:56', '2023-02-12 20:49:56'),
(2786, 'bd', 'document', 'দলিল', '2023-02-12 20:49:56', '2023-02-12 20:49:56'),
(2787, 'bd', 'action_applied_successfully', 'অ্যাকশন সফলভাবে প্রয়োগ করা হয়েছে', '2023-02-12 20:49:56', '2023-02-12 20:49:56'),
(2788, 'bd', 'semething_wrong', 'কিছু ভুল', '2023-02-12 20:49:56', '2023-02-12 20:49:56'),
(2789, 'bd', 'add_new_payment_method', 'নতুন পেমেন্ট পদ্ধতি যোগ করুন', '2023-02-12 20:49:56', '2023-02-12 20:49:56'),
(2790, 'bd', 'custom', 'কাস্টম', '2023-02-12 20:49:56', '2023-02-12 20:49:56'),
(2791, 'bd', 'bank', 'ব্যাংক', '2023-02-12 20:49:56', '2023-02-12 20:49:56'),
(2792, 'bd', 'cheque', 'চেক করুন', '2023-02-12 20:49:56', '2023-02-12 20:49:56'),
(2793, 'bd', 'bank_information', 'ব্যাংক তথ্য', '2023-02-12 20:49:56', '2023-02-12 20:49:56'),
(2794, 'bd', 'bank_name', 'ব্যাংকের নাম', '2023-02-12 20:49:56', '2023-02-12 20:49:56'),
(2795, 'bd', 'account_name', 'হিসাবের নাম', '2023-02-12 20:49:56', '2023-02-12 20:49:56'),
(2796, 'bd', 'account_number', 'হিসাব নাম্বার', '2023-02-12 20:49:56', '2023-02-12 20:49:56'),
(2797, 'bd', 'routing_number', 'রাউটিং নম্বর', '2023-02-12 20:49:56', '2023-02-12 20:49:56'),
(2798, 'bd', 'payment_method_information', 'পেমেন্ট পদ্ধতি তথ্য', '2023-02-12 20:49:56', '2023-02-12 20:49:56'),
(2799, 'bd', 'new_method_added_successfully', 'নতুন পদ্ধতি সফলভাবে যোগ করা হয়েছে', '2023-02-12 20:49:56', '2023-02-12 20:49:56'),
(2800, 'bd', 'new_method_adding_failed_', 'নতুন পদ্ধতি যোগ করা ব্যর্থ হয়েছে৷', '2023-02-12 20:49:56', '2023-02-12 20:49:56'),
(2801, 'bd', 'new_method_adding_failed', 'নতুন পদ্ধতি যোগ করা ব্যর্থ হয়েছে৷', '2023-02-12 20:50:23', '2023-02-12 20:50:23'),
(2802, 'bd', 'payment_method_updated_successfully', 'পেমেন্ট পদ্ধতি সফলভাবে আপডেট করা হয়েছে', '2023-02-12 20:50:23', '2023-02-12 20:50:23'),
(2803, 'sa', 'forgot________________________________password', 'هل نسيت كلمة السر؟', '2023-02-12 21:08:20', '2023-02-12 21:08:20'),
(2804, 'sa', 'log_in', 'تسجيل الدخول', '2023-02-12 21:08:20', '2023-02-12 21:08:20'),
(2805, 'sa', 'invalid_email_address', 'عنوان البريد الإلكتروني غير صالح', '2023-02-12 21:08:20', '2023-02-12 21:08:20'),
(2806, 'sa', 'invalid_password', 'رمز مرور خاطئ', '2023-02-12 21:08:20', '2023-02-12 21:08:20'),
(2807, 'sa', 'login_successful', 'تم تسجيل الدخول بنجاح', '2023-02-12 21:08:20', '2023-02-12 21:08:20'),
(2808, 'sa', 'dashboard', 'لوحة القيادة', '2023-02-12 21:08:20', '2023-02-12 21:08:20'),
(2809, 'sa', 'customers', 'عملاء', '2023-02-12 21:08:20', '2023-02-12 21:08:20'),
(2810, 'sa', 'orders', 'طلبات', '2023-02-12 21:08:20', '2023-02-12 21:08:20'),
(2811, 'sa', 'products', 'منتجات', '2023-02-12 21:08:20', '2023-02-12 21:08:20'),
(2812, 'sa', 'total_sales', 'إجمالي المبيعات', '2023-02-12 21:08:20', '2023-02-12 21:08:20'),
(2813, 'sa', 'sale_reports', 'تقارير البيع', '2023-02-12 21:08:20', '2023-02-12 21:08:20'),
(2814, 'sa', 'monthly', 'شهريا', '2023-02-12 21:08:20', '2023-02-12 21:08:20'),
(2815, 'sa', 'daily', 'يوميًا', '2023-02-12 21:08:20', '2023-02-12 21:08:20'),
(2816, 'sa', 'pending', 'قيد الانتظار', '2023-02-12 21:08:20', '2023-02-14 22:07:51'),
(2817, 'sa', 'approved', 'موافقة', '2023-02-12 21:08:20', '2023-02-12 21:08:20'),
(2818, 'sa', 'ready_to_ship', 'على استعداد للسفينة', '2023-02-12 21:08:20', '2023-02-12 21:08:20'),
(2819, 'sa', 'shipped', 'شحنها', '2023-02-12 21:08:20', '2023-02-12 21:08:20'),
(2820, 'sa', 'delivered', 'تم التوصيل', '2023-02-12 21:08:20', '2023-02-12 21:08:20'),
(2821, 'sa', 'cancelled', 'ألغيت', '2023-02-12 21:08:20', '2023-02-12 21:08:20'),
(2822, 'sa', 'recent_orders', 'الطلبيات الأخيرة', '2023-02-12 21:08:20', '2023-02-12 21:08:20'),
(2823, 'sa', 'order_id', 'رقم التعريف الخاص بالطلب', '2023-02-12 21:08:20', '2023-02-12 21:08:20'),
(2824, 'sa', 'date', 'تاريخ', '2023-02-12 21:08:20', '2023-02-12 21:08:20'),
(2825, 'sa', 'customer', 'عميل', '2023-02-12 21:08:20', '2023-02-12 21:08:20'),
(2826, 'sa', 'total_amount', 'المبلغ الإجمالي', '2023-02-12 21:08:20', '2023-02-12 21:08:20'),
(2827, 'sa', 'action', 'فعل', '2023-02-12 21:08:20', '2023-02-12 21:08:20'),
(2828, 'sa', 'nothing_found', 'لم يتم العثور على شيء', '2023-02-12 21:08:20', '2023-02-12 21:08:20'),
(2829, 'sa', 'top_customers', 'كبار العملاء', '2023-02-12 21:08:20', '2023-02-12 21:08:20'),
(2830, 'sa', 'top_products', 'أهم المنتجات', '2023-02-12 21:08:20', '2023-02-12 21:08:20'),
(2831, 'sa', 'top_categories', 'أعلى الفئات', '2023-02-12 21:08:20', '2023-02-12 21:08:20'),
(2832, 'sa', 'top_brands', 'ارقى الماركات', '2023-02-12 21:08:20', '2023-02-12 21:08:20'),
(2833, 'sa', 'my_profile', 'ملفي', '2023-02-12 21:08:20', '2023-02-12 21:08:20'),
(2834, 'sa', 'log_out', 'تسجيل خروج', '2023-02-12 21:08:20', '2023-02-12 21:08:20'),
(2835, 'sa', 'clear_cache', 'مسح ذاكرة التخزين المؤقت', '2023-02-12 21:08:20', '2023-02-12 21:08:20'),
(2836, 'sa', 'notifications', 'إشعارات', '2023-02-12 21:08:20', '2023-02-12 21:08:20'),
(2837, 'sa', 'clear_all', 'امسح الكل', '2023-02-12 21:08:20', '2023-02-12 21:08:20'),
(2838, 'sa', 'media', 'وسائط', '2023-02-12 21:08:20', '2023-02-12 21:08:20'),
(2839, 'sa', 'blog', 'مدونة', '2023-02-12 21:08:20', '2023-02-12 21:08:20'),
(2840, 'sa', 'all_blogs', 'كل المدونات', '2023-02-12 21:08:20', '2023-02-12 21:08:20'),
(2841, 'sa', 'add_new_blog', 'أضف مدونة جديدة', '2023-02-12 21:08:20', '2023-02-12 21:08:20'),
(2842, 'sa', 'categories', 'فئات', '2023-02-12 21:08:20', '2023-02-12 21:08:20'),
(2843, 'sa', 'tags', 'العلامات', '2023-02-12 21:08:20', '2023-02-12 21:08:20'),
(2844, 'sa', 'comments', 'تعليقات', '2023-02-12 21:08:20', '2023-02-12 21:08:20'),
(2845, 'sa', 'settings', 'إعدادات', '2023-02-12 21:08:20', '2023-02-12 21:08:20'),
(2846, 'sa', 'comment_settings', 'إعدادات التعليق', '2023-02-12 21:08:20', '2023-02-12 21:08:20'),
(2847, 'sa', 'pages', 'الصفحات', '2023-02-12 21:08:20', '2023-02-12 21:08:20'),
(2848, 'sa', 'all_pages', 'كل الصفحات', '2023-02-12 21:08:20', '2023-02-12 21:08:20'),
(2849, 'sa', 'add_new_page', 'أضف صفحة جديدة', '2023-02-12 21:08:20', '2023-02-12 21:08:20'),
(2850, 'sa', 'add_new_product', 'اضافة منتج جديد', '2023-02-12 21:08:20', '2023-02-12 21:08:20'),
(2851, 'sa', 'all_products', 'جميع المنتجات', '2023-02-12 21:08:20', '2023-02-12 21:08:20'),
(2852, 'sa', 'colors', 'الألوان', '2023-02-12 21:08:20', '2023-02-12 21:08:20'),
(2853, 'sa', 'brands', 'العلامات التجارية', '2023-02-12 21:08:20', '2023-02-12 21:08:20'),
(2854, 'sa', 'attributes', 'صفات', '2023-02-12 21:08:20', '2023-02-12 21:08:20'),
(2855, 'sa', 'units', 'الوحدات', '2023-02-12 21:08:20', '2023-02-12 21:08:20'),
(2856, 'sa', 'product_reviews', 'تعليقات المنتج', '2023-02-12 21:08:20', '2023-02-12 21:08:20'),
(2857, 'sa', 'product_collections', 'مجموعات المنتجات', '2023-02-12 21:08:20', '2023-02-12 21:08:20'),
(2858, 'sa', 'product_tags', 'علامات المنتج', '2023-02-12 21:08:20', '2023-02-12 21:08:20'),
(2859, 'sa', 'product_conditions', 'شروط المنتج', '2023-02-12 21:08:20', '2023-02-12 21:08:20'),
(2860, 'sa', 'inhouse_orders', 'أوامر داخلية', '2023-02-12 21:08:20', '2023-02-12 21:08:20'),
(2861, 'sa', 'pickup_point_order', 'طلب نقطة الاستلام', '2023-02-12 21:08:20', '2023-02-12 21:08:20'),
(2862, 'sa', 'addon', 'اضافه', '2023-02-12 21:08:20', '2023-02-12 21:08:20'),
(2863, 'sa', 'shippings', 'الشحنات', '2023-02-12 21:08:20', '2023-02-12 21:08:20'),
(2864, 'sa', 'shipping__delivery', 'الشحن و التسليم', '2023-02-12 21:08:20', '2023-02-12 21:08:20'),
(2865, 'sa', 'pickup_points', 'نقاط الالتقاط', '2023-02-12 21:08:20', '2023-02-12 21:08:20'),
(2866, 'sa', 'carriers', 'الناقلون', '2023-02-12 21:08:20', '2023-02-12 21:08:20');
INSERT INTO `tl_translations` (`id`, `lang`, `lang_key`, `lang_value`, `created_at`, `updated_at`) VALUES
(2867, 'sa', 'adoon', 'عبد', '2023-02-12 21:08:20', '2023-02-12 21:08:20'),
(2868, 'sa', 'locations', 'المواقع', '2023-02-12 21:08:20', '2023-02-12 21:08:20'),
(2869, 'sa', 'countries', 'بلدان', '2023-02-12 21:08:20', '2023-02-12 21:08:20'),
(2870, 'sa', 'states', 'تنص على', '2023-02-12 21:08:20', '2023-02-12 21:08:20'),
(2871, 'sa', 'cities', 'مدن', '2023-02-12 21:08:20', '2023-02-12 21:08:20'),
(2872, 'sa', 'payments', 'المدفوعات', '2023-02-12 21:08:20', '2023-02-12 21:08:20'),
(2873, 'sa', 'payment_methods', 'طرق الدفع', '2023-02-12 21:08:20', '2023-02-12 21:08:20'),
(2874, 'sa', 'transaction_history', 'تاريخ المعاملات', '2023-02-12 21:08:20', '2023-02-12 21:08:20'),
(2875, 'sa', 'marketing', 'تسويق', '2023-02-12 21:08:20', '2023-02-12 21:08:20'),
(2876, 'sa', 'flash_deals', 'عروض فلاش', '2023-02-12 21:08:20', '2023-02-12 21:08:20'),
(2877, 'sa', 'coupons', 'كوبونات', '2023-02-12 21:08:20', '2023-02-12 21:08:20'),
(2878, 'sa', 'custom_notification', 'إعلام مخصص', '2023-02-12 21:08:20', '2023-02-12 21:08:20'),
(2879, 'sa', 'reports', 'التقارير', '2023-02-12 21:08:20', '2023-02-12 21:08:20'),
(2880, 'sa', 'product_reports', 'تقارير المنتج', '2023-02-12 21:08:20', '2023-02-12 21:08:20'),
(2881, 'sa', 'keyword_search_reports', 'تقارير البحث عن الكلمات الرئيسية', '2023-02-12 21:08:20', '2023-02-12 21:08:20'),
(2882, 'sa', 'wishlist_reports', 'تقارير قائمة الرغبات', '2023-02-12 21:08:20', '2023-02-12 21:08:20'),
(2883, 'sa', 'ecommerce_settings', 'إعدادات التجارة الإلكترونية', '2023-02-12 21:08:20', '2023-02-12 21:08:20'),
(2884, 'sa', 'taxes', 'الضرائب', '2023-02-12 21:08:20', '2023-02-12 21:08:20'),
(2885, 'sa', 'currencies', 'العملات', '2023-02-12 21:08:20', '2023-02-12 21:08:20'),
(2886, 'sa', 'product_share_options', 'خيارات مشاركة المنتج', '2023-02-12 21:08:20', '2023-02-12 21:08:20'),
(2887, 'sa', 'refunds', 'المبالغ المستردة', '2023-02-12 21:08:20', '2023-02-12 21:08:20'),
(2888, 'sa', 'refund_requests', 'طلبات الاسترداد', '2023-02-12 21:08:20', '2023-02-12 21:08:20'),
(2889, 'sa', 'refund_reasons', 'أسباب الاسترداد', '2023-02-12 21:08:20', '2023-02-12 21:08:20'),
(2890, 'sa', 'appearances', 'ظهور', '2023-02-12 21:08:20', '2023-02-12 21:08:20'),
(2891, 'sa', 'themes', 'ثيمات', '2023-02-12 21:08:20', '2023-02-12 21:08:20'),
(2892, 'sa', 'menus', 'القوائم', '2023-02-12 21:08:20', '2023-02-12 21:08:20'),
(2893, 'sa', 'widgets', 'الحاجيات', '2023-02-12 21:08:20', '2023-02-12 21:08:20'),
(2894, 'sa', 'theme_options', 'خيارات الموضوع', '2023-02-12 21:08:20', '2023-02-12 21:08:20'),
(2895, 'sa', 'general_settings', 'الاعدادات العامة', '2023-02-12 21:08:20', '2023-02-12 21:08:20'),
(2896, 'sa', 'home_page_builder', 'منشئ الصفحة الرئيسية', '2023-02-12 21:08:20', '2023-02-12 21:08:20'),
(2897, 'sa', 'slider_settings', 'إعدادات المنزلق', '2023-02-12 21:08:20', '2023-02-12 21:08:20'),
(2898, 'sa', 'plugins', 'الإضافات', '2023-02-12 21:08:20', '2023-02-12 21:08:20'),
(2899, 'sa', 'email_settings', 'إعدادات البريد الإلكتروني', '2023-02-12 21:08:20', '2023-02-12 21:08:20'),
(2900, 'sa', 'email_templates', 'قوالب البريد الإلكتروني', '2023-02-12 21:08:20', '2023-02-12 21:08:20'),
(2901, 'sa', 'languages', 'اللغات', '2023-02-12 21:08:20', '2023-02-12 21:08:20'),
(2902, 'sa', 'media_settings', 'إعدادات الوسائط', '2023-02-12 21:08:20', '2023-02-12 21:08:20'),
(2903, 'sa', 'seo_settings', 'إعدادات تحسين محركات البحث', '2023-02-12 21:08:20', '2023-02-12 21:08:20'),
(2904, 'sa', 'theme_translatios', 'موضوع Translatios', '2023-02-12 21:08:20', '2023-02-12 21:08:20'),
(2905, 'sa', 'users', 'المستخدمون', '2023-02-12 21:08:20', '2023-02-12 21:08:20'),
(2906, 'sa', 'roles', 'الأدوار', '2023-02-12 21:08:20', '2023-02-12 21:08:20'),
(2907, 'sa', 'permissions', 'أذونات', '2023-02-12 21:08:20', '2023-02-12 21:08:20'),
(2908, 'sa', 'activity_logs', 'سجلات النشاط', '2023-02-12 21:08:20', '2023-02-12 21:08:20'),
(2909, 'sa', 'login_activity', 'نشاط تسجيل الدخول', '2023-02-12 21:08:20', '2023-02-12 21:08:20'),
(2910, 'sa', 'update_user', 'تحديث المستخدم', '2023-02-12 21:08:20', '2023-02-12 21:08:20'),
(2911, 'sa', 'update_profile', 'تحديث الملف', '2023-02-12 21:08:20', '2023-02-12 21:08:20'),
(2912, 'sa', 'profile_picture', 'الصوره الشخصيه', '2023-02-12 21:08:20', '2023-02-12 21:08:20'),
(2913, 'sa', 'choose_image', 'اختر صورة', '2023-02-12 21:08:20', '2023-02-12 21:08:20'),
(2914, 'sa', 'name', 'اسم', '2023-02-12 21:08:20', '2023-02-12 21:08:20'),
(2915, 'sa', 'give_your_name', 'ذكر اسمك', '2023-02-12 21:08:20', '2023-02-12 21:08:20'),
(2916, 'sa', 'email', 'بريد إلكتروني', '2023-02-12 21:08:20', '2023-02-12 21:08:20'),
(2917, 'sa', 'give_your_email_address', 'أعط عنوان بريدك الإلكتروني', '2023-02-12 21:08:20', '2023-02-12 21:08:20'),
(2918, 'sa', 'old_password', 'كلمة المرور القديمة', '2023-02-12 21:08:20', '2023-02-12 21:08:20'),
(2919, 'sa', 'password', 'كلمة المرور', '2023-02-12 21:08:20', '2023-02-12 21:08:20'),
(2920, 'sa', 'give_your_password', 'أدخل كلمة المرور الخاصة بك', '2023-02-12 21:08:20', '2023-02-12 21:08:20'),
(2921, 'sa', 'confirm_password', 'تأكيد كلمة المرور', '2023-02-12 21:08:20', '2023-02-12 21:08:20'),
(2922, 'sa', 'confirm_your_password', 'أكد رقمك السري', '2023-02-12 21:08:20', '2023-02-12 21:08:20'),
(2923, 'sa', 'update', 'تحديث', '2023-02-12 21:08:20', '2023-02-12 21:08:20'),
(2924, 'sa', 'media_library', 'مكتبة الوسائط', '2023-02-12 21:08:20', '2023-02-12 21:08:20'),
(2925, 'sa', 'upload_files', 'تحميل الملفات', '2023-02-12 21:08:20', '2023-02-12 21:08:20'),
(2926, 'sa', 'click_or_drop_files_here_to_upload', 'انقر أو أفلت الملفات هنا للتحميل', '2023-02-12 21:08:20', '2023-02-12 21:08:20'),
(2927, 'sa', 'filter_media', 'إعلام منقى', '2023-02-12 21:08:20', '2023-02-12 21:08:20'),
(2928, 'sa', 'all_file_type', 'كل أنواع الملفات', '2023-02-12 21:08:20', '2023-02-12 21:08:20'),
(2929, 'sa', 'all_dates', 'كل التواريخ', '2023-02-12 21:08:20', '2023-02-12 21:08:20'),
(2930, 'sa', 'insert', 'إدراج', '2023-02-12 21:08:20', '2023-02-12 21:08:20'),
(2931, 'sa', 'attachment_details', 'تفاصيل المرفقات', '2023-02-12 21:08:20', '2023-02-12 21:08:20'),
(2932, 'sa', 'alt________________________text_', 'نص بديل :', '2023-02-12 21:08:20', '2023-02-12 21:08:20'),
(2933, 'sa', 'title_', 'عنوان :', '2023-02-12 21:08:20', '2023-02-12 21:08:20'),
(2934, 'sa', 'caption_', 'التسمية التوضيحية :', '2023-02-12 21:08:20', '2023-02-12 21:08:20'),
(2935, 'sa', 'description_', 'وصف :', '2023-02-12 21:08:20', '2023-02-12 21:08:20'),
(2936, 'sa', 'showing', 'عرض', '2023-02-12 21:08:20', '2023-02-12 21:08:20'),
(2937, 'sa', 'media_items', 'عناصر الوسائط', '2023-02-12 21:08:20', '2023-02-12 21:08:20'),
(2938, 'sa', 'delete_permanently', 'الحذف بشكل نهائي', '2023-02-12 21:08:20', '2023-02-12 21:08:20'),
(2939, 'sa', 'file_name', 'اسم الملف:', '2023-02-12 21:08:20', '2023-02-12 21:08:20'),
(2940, 'sa', 'file_url', 'URL الملف:', '2023-02-12 21:08:20', '2023-02-12 21:08:20'),
(2941, 'sa', 'file_type', 'نوع الملف:', '2023-02-12 21:08:20', '2023-02-12 21:08:20'),
(2942, 'sa', 'file_size', 'حجم الملف:', '2023-02-12 21:08:20', '2023-02-12 21:08:20'),
(2943, 'sa', 'uploaded_by', 'تم الرفع بواسطة:', '2023-02-12 21:08:20', '2023-02-12 21:08:20'),
(2944, 'sa', 'created_at', 'أنشئت في:', '2023-02-12 21:08:20', '2023-02-12 21:08:20'),
(2945, 'sa', 'updated_at', 'تم التحديث في:', '2023-02-12 21:08:20', '2023-02-12 21:08:20'),
(2946, 'sa', 'download', 'تحميل', '2023-02-12 21:08:20', '2023-02-12 21:08:20'),
(2947, 'sa', 'copy_url_to_clipboard', 'انسخ URL إلى الحافظة', '2023-02-12 21:08:20', '2023-02-12 21:08:20'),
(2948, 'sa', 'alt____________________________________________________________________________________text', 'نص بديل', '2023-02-12 21:08:20', '2023-02-12 21:08:20'),
(2949, 'sa', 'title', 'عنوان', '2023-02-12 21:08:20', '2023-02-12 21:08:20'),
(2950, 'sa', 'caption', 'التسمية التوضيحية', '2023-02-12 21:08:20', '2023-02-12 21:08:20'),
(2951, 'sa', 'description', 'وصف', '2023-02-12 21:08:20', '2023-02-12 21:08:20'),
(2952, 'sa', 'save', 'يحفظ', '2023-02-12 21:08:20', '2023-02-12 21:08:20'),
(2953, 'sa', 'login', 'تسجيل الدخول', '2023-02-12 21:08:20', '2023-02-12 21:08:20'),
(2954, 'sa', 'login_to_dashboard', 'تسجيل الدخول إلى لوحة القيادة', '2023-02-12 21:08:20', '2023-02-12 21:08:20'),
(2955, 'sa', 'email_address', 'عنوان البريد الإلكتروني', '2023-02-12 21:08:20', '2023-02-12 21:08:20'),
(2956, 'sa', 'remember_me', 'تذكرنى', '2023-02-12 21:08:20', '2023-02-12 21:08:20'),
(2957, 'sa', 'sliders', 'المتزلجون', '2023-02-12 21:08:20', '2023-02-12 21:08:20'),
(2958, 'sa', 'add_new_slider', 'أضف شريط تمرير جديد', '2023-02-12 21:08:20', '2023-02-12 21:08:20'),
(2959, 'sa', 'desktop', 'سطح المكتب', '2023-02-12 21:08:20', '2023-02-12 21:08:20'),
(2960, 'sa', 'mobile', 'متحرك', '2023-02-12 21:08:20', '2023-02-12 21:08:20'),
(2961, 'sa', 'status', 'حالة', '2023-02-12 21:08:20', '2023-02-12 21:08:20'),
(2962, 'sa', 'actions', 'أجراءات', '2023-02-12 21:08:20', '2023-02-12 21:08:20'),
(2963, 'sa', 'delete_confirmation', 'تأكيد الحذف', '2023-02-12 21:08:20', '2023-02-12 21:08:20'),
(2964, 'sa', 'are_you_sure_to_delete_this', 'هل أنت متأكد من حذف هذا', '2023-02-12 21:08:20', '2023-02-12 21:08:20'),
(2965, 'sa', 'cancel', 'يلغي', '2023-02-12 21:08:20', '2023-02-12 21:08:20'),
(2966, 'sa', 'delete', 'يمسح', '2023-02-12 21:08:20', '2023-02-12 21:08:20'),
(2967, 'sa', 'bulk_action', 'العمل الجماعي', '2023-02-12 21:08:20', '2023-02-12 21:08:20'),
(2968, 'sa', 'delete_selection', 'حذف التحديد', '2023-02-12 21:08:20', '2023-02-12 21:08:20'),
(2969, 'sa', 'apply', 'يتقدم', '2023-02-12 21:08:20', '2023-02-12 21:08:20'),
(2970, 'sa', 'no_item_selected', 'لم يتم تحديد أي عنصر', '2023-02-12 21:08:20', '2023-02-12 21:08:20'),
(2971, 'sa', 'no_action_selected', 'لم يتم تحديد أي إجراء', '2023-02-12 21:08:20', '2023-02-12 21:08:20'),
(2972, 'sa', 'new_slider', 'سلايدر جديد', '2023-02-12 21:08:20', '2023-02-12 21:08:20'),
(2973, 'sa', 'type_title', 'اكتب العنوان', '2023-02-12 21:08:20', '2023-02-12 21:08:20'),
(2974, 'sa', 'desktop_image', 'صورة سطح المكتب', '2023-02-12 21:08:20', '2023-02-12 21:08:20'),
(2975, 'sa', 'choose_file', 'اختر ملف', '2023-02-12 21:08:20', '2023-02-12 21:08:20'),
(2976, 'sa', 'mobile_image', 'صورة الجوال', '2023-02-12 21:08:20', '2023-02-12 21:08:20'),
(2977, 'sa', 'system_name', 'اسم النظام', '2023-02-12 21:08:20', '2023-02-12 21:08:20'),
(2978, 'sa', 'logo', 'شعار', '2023-02-12 21:08:20', '2023-02-12 21:08:20'),
(2979, 'sa', 'logo_mobile', 'شعار (جوال)', '2023-02-12 21:08:20', '2023-02-12 21:08:20'),
(2980, 'sa', 'dark_logo', 'شعار غامق', '2023-02-12 21:08:20', '2023-02-12 21:08:20'),
(2981, 'sa', 'dark_logo_mobile', 'شعار غامق (جوال)', '2023-02-12 21:08:20', '2023-02-12 21:08:20'),
(2982, 'sa', 'sticky_logo', 'شعار مثبت', '2023-02-12 21:08:20', '2023-02-12 21:08:20'),
(2983, 'sa', 'sticky_logo_mobile', 'شعار مثبت (جوال)', '2023-02-12 21:08:20', '2023-02-12 21:08:20'),
(2984, 'sa', 'dark_sticky_logo', 'شعار لاصق غامق', '2023-02-12 21:08:20', '2023-02-12 21:08:20'),
(2985, 'sa', 'dark_sticky_logo_mobile', 'شعار لاصق غامق (للجوال)', '2023-02-12 21:08:20', '2023-02-12 21:08:20'),
(2986, 'sa', 'admin_logo', 'شعار المسؤول', '2023-02-12 21:08:20', '2023-02-12 21:08:20'),
(2987, 'sa', 'admin_logo_mobile', 'شعار المسؤول (الجوال)', '2023-02-12 21:08:20', '2023-02-12 21:08:20'),
(2988, 'sa', 'admin_dark_logo', 'شعار المشرف الداكن', '2023-02-12 21:08:20', '2023-02-12 21:08:20'),
(2989, 'sa', 'admin_dark_logo_mobile', 'شعار المشرف الداكن (الجوال)', '2023-02-12 21:08:20', '2023-02-12 21:08:20'),
(2990, 'sa', 'favicon', 'فافيكون', '2023-02-12 21:08:20', '2023-02-12 21:08:20'),
(2991, 'sa', 'default_language', 'اللغة الافتراضية', '2023-02-12 21:08:20', '2023-02-12 21:08:20'),
(2992, 'sa', 'select_default_language', 'حدد اللغة الافتراضية', '2023-02-12 21:08:20', '2023-02-12 21:08:20'),
(2993, 'sa', 'select_default_timezone', 'حدد المنطقة الزمنية الافتراضية', '2023-02-12 21:08:20', '2023-02-12 21:08:20'),
(2994, 'sa', 'copyright_text', 'نص حقوق النشر', '2023-02-12 21:08:20', '2023-02-12 21:08:20'),
(2995, 'sa', 'submit', 'يُقدِّم', '2023-02-12 21:08:20', '2023-02-12 21:08:20'),
(2996, 'sa', 'general_settings_updated_successfully', 'تم تحديث الإعدادات العامة بنجاح', '2023-02-12 21:08:20', '2023-02-12 21:08:20'),
(2997, 'sa', 'placeholder_image', 'صورة العنصر النائب', '2023-02-12 21:08:20', '2023-02-12 21:08:20'),
(2998, 'sa', 'watermark_settings', 'إعدادات العلامة المائية', '2023-02-12 21:08:20', '2023-02-12 21:08:20'),
(2999, 'sa', 'enabledisable_watermark', 'تمكين / تعطيل العلامة المائية', '2023-02-12 21:08:20', '2023-02-12 21:08:20'),
(3000, 'sa', 'watermark_image', 'صورة العلامة المائية', '2023-02-12 21:08:20', '2023-02-12 21:08:20'),
(3001, 'sa', 'watermark_image_position', 'موقف صورة العلامة المائية', '2023-02-12 21:09:10', '2023-02-12 21:09:10'),
(3002, 'sa', 'top_left', 'أعلى اليسار', '2023-02-12 21:09:10', '2023-02-12 21:09:10'),
(3003, 'sa', 'top', 'قمة', '2023-02-12 21:09:10', '2023-02-12 21:09:10'),
(3004, 'sa', 'top_right', 'اعلى اليمين', '2023-02-12 21:09:10', '2023-02-12 21:09:10'),
(3005, 'sa', 'left', 'غادر', '2023-02-12 21:09:10', '2023-02-12 21:09:10'),
(3006, 'sa', 'center', 'مركز', '2023-02-12 21:09:10', '2023-02-12 21:09:10'),
(3007, 'sa', 'right', 'يمين', '2023-02-12 21:09:10', '2023-02-12 21:09:10'),
(3008, 'sa', 'bottom_left', 'أسفل اليسار', '2023-02-12 21:09:10', '2023-02-12 21:09:10'),
(3009, 'sa', 'watermarking_image_opacity_', 'عتامة صورة العلامة المائية (٪)', '2023-02-12 21:09:10', '2023-02-12 21:09:10'),
(3010, 'sa', 'watermarking_image_opacity', 'عتامة الصورة بالماء', '2023-02-12 21:09:10', '2023-02-12 21:09:10'),
(3011, 'sa', 'media_thumbnails_sizes', 'أحجام الصور المصغرة للوسائط', '2023-02-12 21:09:10', '2023-02-12 21:09:10'),
(3012, 'sa', 'large_thumb_image_size', 'حجم صورة الإبهام الكبير', '2023-02-12 21:09:10', '2023-02-12 21:09:10'),
(3013, 'sa', 'large_thumb_image_width', 'عرض صورة إبهام كبير', '2023-02-12 21:09:10', '2023-02-12 21:09:10'),
(3014, 'sa', 'large_thumb_image_height', 'ارتفاع صورة الإبهام الكبير', '2023-02-12 21:09:10', '2023-02-12 21:09:10'),
(3015, 'sa', 'medium_thumb_image_size', 'حجم صورة الإبهام المتوسط', '2023-02-12 21:09:10', '2023-02-12 21:09:10'),
(3016, 'sa', 'medium_thumb_image_width', 'عرض صورة إبهام متوسط', '2023-02-12 21:09:10', '2023-02-12 21:09:10'),
(3017, 'sa', 'medium_thumb_image_height', 'متوسط ​​ارتفاع الصورة بالإبهام', '2023-02-12 21:09:10', '2023-02-12 21:09:10'),
(3018, 'sa', 'small_thumb_image_size', 'حجم صورة الإبهام الصغير', '2023-02-12 21:09:10', '2023-02-12 21:09:10'),
(3019, 'sa', 'small_thumb_image_width', 'عرض صورة صغيرة بالإبهام', '2023-02-12 21:09:10', '2023-02-12 21:09:10'),
(3020, 'sa', 'small_thumb_image_height', 'ارتفاع صورة الإبهام الصغير', '2023-02-12 21:09:10', '2023-02-12 21:09:10'),
(3021, 'sa', 'select_image_applicable_folder', 'حدد مجلد مناسب للصورة', '2023-02-12 21:09:10', '2023-02-12 21:09:10'),
(3022, 'sa', 'media_settings_updated_successfully', 'تم تحديث إعدادات الوسائط بنجاح', '2023-02-12 21:09:10', '2023-02-12 21:09:10'),
(3023, 'sa', 'add_new_category', 'إضافة فئة جديدة', '2023-02-12 21:09:10', '2023-02-12 21:09:10'),
(3024, 'sa', 'parent', 'الأبوين', '2023-02-12 21:09:10', '2023-02-12 21:09:10'),
(3025, 'sa', 'icon', 'أيقونة', '2023-02-12 21:09:10', '2023-02-12 21:09:10'),
(3026, 'sa', 'featured', 'متميز', '2023-02-12 21:09:10', '2023-02-12 21:09:10'),
(3027, 'sa', 'new_category', 'فئة جديدة', '2023-02-12 21:09:10', '2023-02-12 21:09:10'),
(3028, 'sa', 'type_here', 'أكتب هنا', '2023-02-12 21:09:10', '2023-02-12 21:09:10'),
(3029, 'sa', 'permalink', 'الرابط الثابت', '2023-02-12 21:09:10', '2023-02-12 21:09:10'),
(3030, 'sa', 'edit', 'يحرر', '2023-02-12 21:09:10', '2023-02-12 21:09:10'),
(3031, 'sa', 'select_a_category', 'اختر تصنيف', '2023-02-12 21:09:10', '2023-02-12 21:09:10'),
(3032, 'sa', 'meta_title', 'عنوان الفوقية', '2023-02-12 21:09:10', '2023-02-12 21:09:10'),
(3033, 'sa', 'meta_image', 'صورة ميتا', '2023-02-12 21:09:10', '2023-02-12 21:09:10'),
(3034, 'sa', 'meta_description', 'ميتا الوصف', '2023-02-12 21:09:10', '2023-02-12 21:09:10'),
(3035, 'sa', 'name_is_required', 'مطلوب اسم', '2023-02-12 21:09:10', '2023-02-12 21:09:10'),
(3036, 'sa', 'permalink_is_required', 'الرابط الثابت مطلوب', '2023-02-12 21:09:10', '2023-02-12 21:09:10'),
(3037, 'sa', 'permalink_is_already_exists', 'الرابط الثابت موجود بالفعل', '2023-02-12 21:09:10', '2023-02-12 21:09:10'),
(3038, 'sa', 'selected_parent_does_not_exists', 'الوالد المختار غير موجود', '2023-02-12 21:09:10', '2023-02-12 21:09:10'),
(3039, 'sa', 'new_category_added_successfully', 'تمت إضافة فئة جديدة بنجاح', '2023-02-12 21:09:10', '2023-02-12 21:09:10'),
(3040, 'sa', 'edit_category', 'تحرير الفئة', '2023-02-12 21:09:10', '2023-02-12 21:09:10'),
(3041, 'sa', 'category_updated_successfully', 'تم تحديث الفئة بنجاح', '2023-02-12 21:09:10', '2023-02-12 21:09:10'),
(3042, 'sa', 'tl_commerce__theme_options', 'خيارات الموضوع', '2023-02-12 21:09:10', '2023-02-12 21:09:10'),
(3043, 'sa', 'save_changes', 'حفظ التغييرات', '2023-02-12 21:09:10', '2023-02-12 21:09:10'),
(3044, 'sa', 'reset_section', 'قسم إعادة التعيين', '2023-02-12 21:09:10', '2023-02-12 21:09:10'),
(3045, 'sa', 'reset_all', 'إعادة ضبط الجميع', '2023-02-12 21:09:10', '2023-02-12 21:09:10'),
(3046, 'sa', 'general', 'عام', '2023-02-12 21:09:10', '2023-02-12 21:09:10'),
(3047, 'sa', 'back_to_top', 'العودة الى الأعلى', '2023-02-12 21:09:10', '2023-02-12 21:09:10'),
(3048, 'sa', 'theme_color', 'لون الموضوع', '2023-02-12 21:09:10', '2023-02-12 21:09:10'),
(3049, 'sa', 'typography', 'الطباعة', '2023-02-12 21:09:10', '2023-02-12 21:09:10'),
(3050, 'sa', 'body_typography', 'طباعة الجسم', '2023-02-12 21:09:10', '2023-02-12 21:09:10'),
(3051, 'sa', 'paragraph_typography', 'طباعة الفقرة', '2023-02-12 21:09:10', '2023-02-12 21:09:10'),
(3052, 'sa', 'heading_typography', 'طباعة العنوان', '2023-02-12 21:09:10', '2023-02-12 21:09:10'),
(3053, 'sa', 'menu_typography', 'طباعة القائمة', '2023-02-12 21:09:10', '2023-02-12 21:09:10'),
(3054, 'sa', 'button_typography', 'طباعة الزر', '2023-02-12 21:09:10', '2023-02-12 21:09:10'),
(3055, 'sa', 'custom_fonts', 'خطوط عادية', '2023-02-12 21:09:10', '2023-02-12 21:09:10'),
(3056, 'sa', 'header', 'رأس', '2023-02-12 21:09:10', '2023-02-12 21:09:10'),
(3057, 'sa', 'header_option', 'خيار الرأس', '2023-02-12 21:09:10', '2023-02-12 21:09:10'),
(3058, 'sa', 'header_logo', 'رأس الشعار', '2023-02-12 21:09:10', '2023-02-12 21:09:10'),
(3059, 'sa', 'menu', 'قائمة طعام', '2023-02-12 21:09:10', '2023-02-12 21:09:10'),
(3060, 'sa', 'blog_option', 'خيار المدونة', '2023-02-12 21:09:10', '2023-02-12 21:09:10'),
(3061, 'sa', 'single_blog_page', 'صفحة مدونة واحدة', '2023-02-12 21:09:10', '2023-02-12 21:09:10'),
(3062, 'sa', 'sidebar_options', 'خيارات الشريط الجانبي', '2023-02-12 21:09:10', '2023-02-12 21:09:10'),
(3063, 'sa', '404_page', '404 صفحة', '2023-02-12 21:09:10', '2023-02-12 21:09:10'),
(3064, 'sa', 'subscribe', 'يشترك', '2023-02-12 21:09:10', '2023-02-12 21:09:10'),
(3065, 'sa', 'social', 'اجتماعي', '2023-02-12 21:09:10', '2023-02-12 21:09:10'),
(3066, 'sa', 'footer', 'تذييل', '2023-02-12 21:09:10', '2023-02-12 21:09:10'),
(3067, 'sa', 'custom_css', 'لغة تنسيق ويب حسب الطلب', '2023-02-12 21:09:10', '2023-02-12 21:09:10'),
(3068, 'sa', 'reset_confirmation', 'إعادة تأكيد', '2023-02-12 21:09:10', '2023-02-12 21:09:10'),
(3069, 'sa', 'are_you_sure_to_want_to_reset', 'هل أنت متأكد أنك تريد إعادة تعيين', '2023-02-12 21:09:10', '2023-02-12 21:09:10'),
(3070, 'sa', 'action_failed', 'العمل: فشل', '2023-02-12 21:09:10', '2023-02-12 21:09:10'),
(3071, 'sa', 'select_font_subsets', 'حدد Font Subsets', '2023-02-12 21:09:10', '2023-02-12 21:09:10'),
(3072, 'sa', 'select_weight__style', 'حدد الوزن والشكل', '2023-02-12 21:09:10', '2023-02-12 21:09:10'),
(3073, 'sa', 'new_slide', 'شريحة جديدة', '2023-02-12 21:09:10', '2023-02-12 21:09:10'),
(3074, 'sa', 'iconexample_fa_fafacebook', 'الرمز (مثال: fa fa-facebook)', '2023-02-12 21:09:10', '2023-02-12 21:09:10'),
(3075, 'sa', 'url', 'عنوان Url', '2023-02-12 21:09:10', '2023-02-12 21:09:10'),
(3076, 'sa', 'back_to_top_button', 'زر العودة الى اعلى', '2023-02-12 21:09:10', '2023-02-12 21:09:10'),
(3077, 'sa', 'switch_on_to_display_back_to_top_button', 'قم بالتبديل إلى زر عرض الرجوع إلى الأعلى.', '2023-02-12 21:09:10', '2023-02-12 21:09:10'),
(3078, 'sa', 'custom_back_to_top_button', 'مخصص زر العودة إلى الأعلى', '2023-02-12 21:09:10', '2023-02-12 21:09:10'),
(3079, 'sa', 'if_you_switch_it_off_it_will_show_default_design_for_back_to_top_button', 'إذا قمت بإيقاف تشغيله ، فسيظهر التصميم الافتراضي لزر \"الرجوع إلى الأعلى\".', '2023-02-12 21:09:10', '2023-02-12 21:09:10'),
(3080, 'sa', 'custom_back_to_top_button_icon', 'مخصص رمز زر الرجوع إلى الأعلى', '2023-02-12 21:09:10', '2023-02-12 21:09:10'),
(3081, 'sa', 'select_back_to_top_button_icon', 'حدد رمز Back To Top Button.', '2023-02-12 21:09:10', '2023-02-12 21:09:10'),
(3082, 'sa', 'back_to_top_button_background_color', 'العودة إلى الأعلى لون خلفية الزر', '2023-02-12 21:09:10', '2023-02-12 21:09:10'),
(3083, 'sa', 'set_back_to_top_button_background_color', 'تعيين لون الخلفية زر الرجوع إلى الأعلى.', '2023-02-12 21:09:10', '2023-02-12 21:09:10'),
(3084, 'sa', 'select_color', 'إختر لون', '2023-02-12 21:09:10', '2023-02-12 21:09:10'),
(3085, 'sa', 'transparent', 'شفاف', '2023-02-12 21:09:10', '2023-02-12 21:09:10'),
(3086, 'sa', 'back_to_top_button_color', 'العودة إلى الأعلى لون الزر', '2023-02-12 21:09:10', '2023-02-12 21:09:10'),
(3087, 'sa', 'set_back_to_top_button_color', 'تعيين لون الزر العودة إلى الأعلى.', '2023-02-12 21:09:10', '2023-02-12 21:09:10'),
(3088, 'sa', 'back_to_top_hover_button_color', 'العودة إلى أعلى لون زر التحويم', '2023-02-12 21:09:10', '2023-02-12 21:09:10'),
(3089, 'sa', 'back_to_top_button_hover_background_color', 'العودة إلى الأعلى لون الخلفية تحوم فوق الزر', '2023-02-12 21:09:10', '2023-02-12 21:09:10'),
(3090, 'sa', 'set_back_to_top_button_hover_background_color', 'تعيين العودة إلى أعلى لون الخلفية تحوم الزر.', '2023-02-12 21:09:10', '2023-02-12 21:09:10'),
(3091, 'sa', 'add_new_brand', 'أضف علامة تجارية جديدة', '2023-02-12 21:09:10', '2023-02-12 21:09:10'),
(3092, 'sa', 'new_brand', 'العلامة التجارية الجديدة', '2023-02-12 21:09:10', '2023-02-12 21:09:10'),
(3093, 'sa', 'invalid_logo', 'شعار غير صالح', '2023-02-12 21:09:10', '2023-02-12 21:09:10'),
(3094, 'sa', 'invalid_meta_image', 'صورة وصفية غير صالحة', '2023-02-12 21:09:10', '2023-02-12 21:09:10'),
(3095, 'sa', 'new_brand_added_successfully', 'تمت إضافة علامة تجارية جديدة بنجاح', '2023-02-12 21:09:10', '2023-02-12 21:09:10'),
(3096, 'sa', 'add_new_color', 'أضف لون جديد', '2023-02-12 21:09:10', '2023-02-12 21:09:10'),
(3097, 'sa', 'code', 'شفرة', '2023-02-12 21:09:10', '2023-02-12 21:09:10'),
(3098, 'sa', 'new_color', 'لون جديد', '2023-02-12 21:09:10', '2023-02-12 21:09:10'),
(3099, 'sa', 'tl_commerce__blog_category', 'فئة المدونة', '2023-02-12 21:09:10', '2023-02-12 21:09:10'),
(3100, 'sa', 'blog_categories', 'فئات المدونة', '2023-02-12 21:09:10', '2023-02-12 21:09:10'),
(3101, 'sa', 'add_blog_category', 'أضف فئة المدونة', '2023-02-12 21:09:10', '2023-02-12 21:09:10'),
(3102, 'sa', 'all', 'الجميع', '2023-02-12 21:09:10', '2023-02-12 21:09:10'),
(3103, 'sa', 'items_of', 'من العناصر', '2023-02-12 21:09:10', '2023-02-12 21:09:10'),
(3104, 'sa', 'add_new_attribute', 'أضف سمة جديدة', '2023-02-12 21:09:10', '2023-02-12 21:09:10'),
(3105, 'sa', 'values', 'قيم', '2023-02-12 21:09:10', '2023-02-12 21:09:10'),
(3106, 'sa', 'code_is_required', 'الرمز مطلوب', '2023-02-12 21:09:10', '2023-02-12 21:09:10'),
(3107, 'sa', 'new_color_added_successfully', 'تمت إضافة اللون الجديد بنجاح', '2023-02-12 21:09:10', '2023-02-12 21:09:10'),
(3108, 'sa', 'new_attribute', 'السمة الجديدة', '2023-02-12 21:09:10', '2023-02-12 21:09:10'),
(3109, 'sa', 'add_new_unit', 'أضف وحدة جديدة', '2023-02-12 21:09:10', '2023-02-12 21:09:10'),
(3110, 'sa', 'new_units', 'وحدات جديدة', '2023-02-12 21:09:10', '2023-02-12 21:09:10'),
(3111, 'sa', 'add_new_tag', 'أضف علامة جديدة', '2023-02-12 21:09:10', '2023-02-12 21:09:10'),
(3112, 'sa', 'new_tag', 'علامة جديدة', '2023-02-12 21:09:10', '2023-02-12 21:09:10'),
(3113, 'sa', 'new_tag_added_successfully', 'تمت إضافة علامة جديدة بنجاح', '2023-02-12 21:09:10', '2023-02-12 21:09:10'),
(3114, 'sa', 'conditions', 'شروط', '2023-02-12 21:09:10', '2023-02-12 21:09:10'),
(3115, 'sa', 'add_new_condition', 'أضف شرط جديد', '2023-02-12 21:09:10', '2023-02-12 21:09:10'),
(3116, 'sa', 'product_information', 'معلومات المنتج', '2023-02-12 21:09:10', '2023-02-12 21:09:10'),
(3117, 'sa', 'product_name', 'اسم المنتج', '2023-02-12 21:09:10', '2023-02-12 21:09:10'),
(3118, 'sa', 'brand', 'ماركة', '2023-02-12 21:09:10', '2023-02-12 21:09:10'),
(3119, 'sa', 'unit', 'وحدة', '2023-02-12 21:09:10', '2023-02-12 21:09:10'),
(3120, 'sa', 'condition', 'حالة', '2023-02-12 21:09:10', '2023-02-12 21:09:10'),
(3121, 'sa', 'product_type', 'نوع المنتج', '2023-02-12 21:09:10', '2023-02-12 21:09:10'),
(3122, 'sa', 'single_product', 'منتج واحد', '2023-02-12 21:09:10', '2023-02-12 21:09:10'),
(3123, 'sa', 'variant_product', 'المنتج المتغير', '2023-02-12 21:09:10', '2023-02-12 21:09:10'),
(3124, 'sa', 'product_variation', 'تنوع المنتج', '2023-02-12 21:09:10', '2023-02-12 21:09:10'),
(3125, 'sa', 'choice_options', 'خيارات الاختيار', '2023-02-12 21:09:10', '2023-02-12 21:09:10'),
(3126, 'sa', 'product_price_and_stock', 'سعر المنتج والمخزون', '2023-02-12 21:09:10', '2023-02-12 21:09:10'),
(3127, 'sa', 'purchase_price', 'سعر الشراء', '2023-02-12 21:09:10', '2023-02-12 21:09:10'),
(3128, 'sa', 'unit_price', 'سعر الوحدة', '2023-02-12 21:09:10', '2023-02-12 21:09:10'),
(3129, 'sa', 'quantity', 'كمية', '2023-02-12 21:09:10', '2023-02-12 21:09:10'),
(3130, 'sa', 'sku', 'SKU', '2023-02-12 21:09:10', '2023-02-12 21:09:10'),
(3131, 'sa', 'type_product_sku', 'اكتب رمز SKU للمنتج', '2023-02-12 21:09:10', '2023-02-12 21:09:10'),
(3132, 'sa', 'no_variant_selected_yet', 'لم يتم تحديد متغير حتى الآن', '2023-02-12 21:09:10', '2023-02-12 21:09:10'),
(3133, 'sa', 'product_discount', 'خصم المنتج', '2023-02-12 21:09:10', '2023-02-12 21:09:10'),
(3134, 'sa', 'discount', 'تخفيض', '2023-02-12 21:09:10', '2023-02-12 21:09:10'),
(3135, 'sa', 'flat', 'مستوي', '2023-02-12 21:09:10', '2023-02-12 21:09:10'),
(3136, 'sa', 'percentage', 'نسبة مئوية', '2023-02-12 21:09:10', '2023-02-12 21:09:10'),
(3137, 'sa', 'color_variation_images', 'صور تباين الألوان', '2023-02-12 21:09:10', '2023-02-12 21:09:10'),
(3138, 'sa', 'no_color_variant_selected_yet', 'لم يتم اختيار لون متغير حتى الآن', '2023-02-12 21:09:10', '2023-02-12 21:09:10'),
(3139, 'sa', 'product_description', 'وصف المنتج', '2023-02-12 21:09:10', '2023-02-12 21:09:10'),
(3140, 'sa', 'summary', 'ملخص', '2023-02-12 21:09:10', '2023-02-12 21:09:10'),
(3141, 'sa', 'product_images', 'صور المنتج', '2023-02-12 21:09:10', '2023-02-12 21:09:10'),
(3142, 'sa', 'thumbnail_image', 'صورة مصغرة', '2023-02-12 21:09:10', '2023-02-12 21:09:10'),
(3143, 'sa', 'gallery_images', 'معرض الصور', '2023-02-12 21:09:10', '2023-02-12 21:09:10'),
(3144, 'sa', 'choose_files', 'اختر الملفات', '2023-02-12 21:09:10', '2023-02-12 21:09:10'),
(3145, 'sa', 'pdf_specification', 'مواصفات PDF', '2023-02-12 21:09:10', '2023-02-12 21:09:10'),
(3146, 'sa', 'product_video', 'فيديو المنتج', '2023-02-12 21:09:10', '2023-02-12 21:09:10'),
(3147, 'sa', 'youtube_link', 'رابط يوتيوب', '2023-02-12 21:09:10', '2023-02-12 21:09:10'),
(3148, 'sa', 'seo_meta_tags', 'العلامات الوصفية سيو', '2023-02-12 21:09:10', '2023-02-12 21:09:10'),
(3149, 'sa', 'refundable', 'مستردة', '2023-02-12 21:09:10', '2023-02-12 21:09:10'),
(3150, 'sa', 'authentic', 'أصلي', '2023-02-12 21:09:10', '2023-02-12 21:09:10'),
(3151, 'sa', 'shipping_information', 'معلومات الشحن', '2023-02-12 21:09:10', '2023-02-12 21:09:10'),
(3152, 'sa', 'weight', 'وزن', '2023-02-12 21:09:10', '2023-02-12 21:09:10'),
(3153, 'sa', 'height', 'ارتفاع', '2023-02-12 21:09:10', '2023-02-12 21:09:10'),
(3154, 'sa', 'length', 'طول', '2023-02-12 21:09:10', '2023-02-12 21:09:10'),
(3155, 'sa', 'width', 'عرض', '2023-02-12 21:09:10', '2023-02-12 21:09:10'),
(3156, 'sa', 'shipping_profile', 'ملف الشحن', '2023-02-12 21:09:10', '2023-02-12 21:09:10'),
(3157, 'sa', 'cash_on_delivery', 'الدفع عند الاستلام', '2023-02-12 21:09:10', '2023-02-12 21:09:10'),
(3158, 'sa', 'anywhere', 'في أى مكان', '2023-02-12 21:09:10', '2023-02-12 21:09:10'),
(3159, 'sa', 'custom_locations', 'مواقع مخصصة', '2023-02-12 21:09:10', '2023-02-12 21:09:10'),
(3160, 'sa', 'manage_taxes', 'إدارة الضرائب', '2023-02-12 21:09:10', '2023-02-12 21:09:10'),
(3161, 'sa', 'warranty', 'ضمان', '2023-02-12 21:09:10', '2023-02-12 21:09:10'),
(3162, 'sa', 'replacement_warranty', 'ضمان الاستبدال', '2023-02-12 21:09:10', '2023-02-12 21:09:10'),
(3163, 'sa', 'warranty_days', 'أيام الضمان', '2023-02-12 21:09:10', '2023-02-12 21:09:10'),
(3164, 'sa', 'low_stock_quantity', 'كمية مخزون منخفضة', '2023-02-12 21:09:10', '2023-02-12 21:09:10'),
(3165, 'sa', 'purchase_quantity', 'كمية الشراء', '2023-02-12 21:09:10', '2023-02-12 21:09:10'),
(3166, 'sa', 'minimum_quantity', 'الحد الأدنى من الكمية', '2023-02-12 21:09:10', '2023-02-12 21:09:10'),
(3167, 'sa', 'miximum_quantity', 'كمية المزيج', '2023-02-12 21:09:10', '2023-02-12 21:09:10'),
(3168, 'sa', 'attatchment_on_purchase', 'إرفاق عند الشراء', '2023-02-12 21:09:10', '2023-02-12 21:09:10'),
(3169, 'sa', 'attatchment_name', 'اسم المرفق', '2023-02-12 21:09:10', '2023-02-12 21:09:10'),
(3170, 'sa', 'no_collection_avaible', 'لا توجد مجموعة Avaible', '2023-02-12 21:09:10', '2023-02-12 21:09:10'),
(3171, 'sa', 'add_new_collection', 'إضافة مجموعة جديدة', '2023-02-12 21:09:10', '2023-02-12 21:09:10'),
(3172, 'sa', 'save__draft', 'حفظ المسودة', '2023-02-12 21:09:10', '2023-02-12 21:09:10'),
(3173, 'sa', 'save__publish', 'حفظ ونشر', '2023-02-12 21:09:10', '2023-02-12 21:09:10'),
(3174, 'sa', 'select_choice_option', 'حدد خيار الاختيار', '2023-02-12 21:09:10', '2023-02-12 21:09:10'),
(3175, 'sa', 'select_product_category', 'حدد فئة المنتج', '2023-02-12 21:09:10', '2023-02-12 21:09:10'),
(3176, 'sa', 'select_product_brand', 'حدد ماركة المنتج', '2023-02-12 21:09:10', '2023-02-12 21:09:10'),
(3177, 'sa', 'select_product_unit', 'حدد وحدة المنتج', '2023-02-12 21:09:10', '2023-02-12 21:09:10'),
(3178, 'sa', 'select_product_condition', 'حدد حالة المنتج', '2023-02-12 21:09:10', '2023-02-12 21:09:10'),
(3179, 'sa', 'select_or_insert_product_tags', 'حدد أو أدخل علامات المنتج', '2023-02-12 21:09:10', '2023-02-12 21:09:10'),
(3180, 'sa', 'nothing_selected', 'لا شيء محدد', '2023-02-12 21:09:10', '2023-02-12 21:09:10'),
(3181, 'sa', 'attribute_added_successfully', 'تمت إضافة السمة بنجاح', '2023-02-12 21:09:10', '2023-02-12 21:09:10'),
(3182, 'sa', 'tl_commerce__add_blog', 'أضف مدونة', '2023-02-12 21:09:10', '2023-02-12 21:09:10'),
(3183, 'sa', 'add_blog', 'أضف مدونة', '2023-02-12 21:09:10', '2023-02-12 21:09:10'),
(3184, 'sa', 'short_description', 'وصف قصير', '2023-02-12 21:09:10', '2023-02-12 21:09:10'),
(3185, 'sa', 'content', 'محتوى', '2023-02-12 21:09:10', '2023-02-12 21:09:10'),
(3186, 'sa', 'publish', 'ينشر', '2023-02-12 21:09:10', '2023-02-12 21:09:10'),
(3187, 'sa', 'draft', 'مسودة', '2023-02-12 21:09:10', '2023-02-12 21:09:10'),
(3188, 'sa', 'preview', 'معاينة', '2023-02-12 21:09:10', '2023-02-12 21:09:10'),
(3189, 'sa', 'blog_image', 'صورة المدونة', '2023-02-12 21:09:10', '2023-02-12 21:09:10'),
(3190, 'sa', 'blog_status', 'حالة المدونة', '2023-02-12 21:09:10', '2023-02-12 21:09:10'),
(3191, 'sa', 'featured_status', 'حالة مميزة', '2023-02-12 21:09:10', '2023-02-12 21:09:10'),
(3192, 'sa', 'no_option_selected', 'لا يوجد خيار محدد', '2023-02-12 21:09:10', '2023-02-12 21:09:10'),
(3193, 'sa', 'add', 'يضيف', '2023-02-12 21:09:10', '2023-02-12 21:09:10'),
(3194, 'sa', 'only_active_categories', 'الفئات النشطة فقط', '2023-02-12 21:09:10', '2023-02-12 21:09:10'),
(3195, 'sa', 'select_parent', 'حدد الأصل', '2023-02-12 21:09:10', '2023-02-12 21:09:10'),
(3196, 'sa', 'select_a_parent_category', 'حدد فئة الأصل', '2023-02-12 21:09:10', '2023-02-12 21:09:10'),
(3197, 'sa', 'load_more', 'تحميل المزيد', '2023-02-12 21:09:10', '2023-02-12 21:09:10'),
(3198, 'sa', 'per_page', 'لكل صفحة', '2023-02-12 21:09:10', '2023-02-12 21:09:10'),
(3199, 'sa', 'product_status', 'حالة المنتج', '2023-02-12 21:09:10', '2023-02-12 21:09:10'),
(3200, 'sa', 'published', 'نشرت', '2023-02-12 21:09:10', '2023-02-12 21:09:10'),
(3201, 'sa', 'unpublished', 'غير منشورة', '2023-02-12 21:10:17', '2023-02-12 21:10:17'),
(3202, 'sa', 'product_featured', 'المنتج المميز', '2023-02-12 21:10:17', '2023-02-12 21:10:17'),
(3203, 'sa', 'regular', 'عادي', '2023-02-12 21:10:17', '2023-02-12 21:10:17'),
(3204, 'sa', 'no_discount', 'لا يوجد خصم', '2023-02-12 21:10:17', '2023-02-12 21:10:17'),
(3205, 'sa', 'discounted', 'مخفضة', '2023-02-12 21:10:17', '2023-02-12 21:10:17'),
(3206, 'sa', 'filter', 'منقي', '2023-02-12 21:10:17', '2023-02-12 21:10:17'),
(3207, 'sa', 'make_publish', 'جعل النشر', '2023-02-12 21:10:17', '2023-02-12 21:10:17'),
(3208, 'sa', 'make_unpublish', 'قم بإلغاء النشر', '2023-02-12 21:10:17', '2023-02-12 21:10:17'),
(3209, 'sa', 'make_feature', 'جعل الميزة', '2023-02-12 21:10:17', '2023-02-12 21:10:17'),
(3210, 'sa', 'remove_from_feature', 'إزالة من الميزة', '2023-02-12 21:10:17', '2023-02-12 21:10:17'),
(3211, 'sa', 'remove_discount', 'إزالة الخصم', '2023-02-12 21:10:17', '2023-02-12 21:10:17'),
(3212, 'sa', 'image', 'صورة', '2023-02-12 21:10:17', '2023-02-12 21:10:17'),
(3213, 'sa', 'info', 'معلومات', '2023-02-12 21:10:17', '2023-02-12 21:10:17'),
(3214, 'sa', 'stock__sales', 'الأسهم والمبيعات', '2023-02-12 21:10:17', '2023-02-12 21:10:17'),
(3215, 'sa', 'update_product_information', 'تحديث معلومات المنتج', '2023-02-12 21:10:17', '2023-02-12 21:10:17'),
(3216, 'sa', 'processing', 'يعالج', '2023-02-12 21:10:17', '2023-02-12 21:10:17'),
(3217, 'sa', 'to_shipped', 'لشحنها', '2023-02-12 21:10:17', '2023-02-12 21:10:17'),
(3218, 'sa', 'unpaid', 'غير مدفوعة', '2023-02-12 21:10:17', '2023-02-12 21:10:17'),
(3219, 'sa', 'paid', 'مدفوع', '2023-02-12 21:10:17', '2023-02-12 21:10:17'),
(3220, 'sa', 'change_status_to_processing', 'تغيير الحالة إلى المعالجة', '2023-02-12 21:10:17', '2023-02-12 21:10:17'),
(3221, 'sa', 'change_status_to_ready_to_ship', 'تغيير الحالة إلى جاهز للشحن', '2023-02-12 21:10:17', '2023-02-12 21:10:17'),
(3222, 'sa', 'change_status_to_shipped', 'تغيير الحالة إلى الشحن', '2023-02-12 21:10:17', '2023-02-12 21:10:17'),
(3223, 'sa', 'change_status_to_delivered', 'تغيير الحالة إلى التسليم', '2023-02-12 21:10:17', '2023-02-12 21:10:17'),
(3224, 'sa', 'change_status_to_paid', 'تغيير الحالة إلى مدفوعة', '2023-02-12 21:10:17', '2023-02-12 21:10:17'),
(3225, 'sa', 'change_status_to_unpaid', 'تغيير الحالة إلى غير مدفوعة', '2023-02-12 21:10:17', '2023-02-12 21:10:17'),
(3226, 'sa', 'move_to_trash', 'ارسال الى سلة المحذوفات', '2023-02-12 21:10:17', '2023-02-12 21:10:17'),
(3227, 'sa', 'delivery_status', 'حالة التسليم', '2023-02-12 21:10:17', '2023-02-12 21:10:17'),
(3228, 'sa', 'payment_status', 'حالة السداد', '2023-02-12 21:10:17', '2023-02-12 21:10:17'),
(3229, 'sa', 'order_code', 'رمز الطلب', '2023-02-12 21:10:17', '2023-02-12 21:10:17'),
(3230, 'sa', 'order_date', 'تاريخ الطلب', '2023-02-12 21:10:17', '2023-02-12 21:10:17'),
(3231, 'sa', 'num_of_products', 'المنتجات', '2023-02-12 21:10:17', '2023-02-12 21:10:17'),
(3232, 'sa', 'amount', 'كمية', '2023-02-12 21:10:17', '2023-02-12 21:10:17'),
(3233, 'sa', 'order_status', 'حالة الطلب', '2023-02-12 21:10:17', '2023-02-12 21:10:17'),
(3234, 'sa', 'update_order_status', 'تحديث حالة الطلب', '2023-02-12 21:10:17', '2023-02-12 21:10:17'),
(3235, 'sa', 'cancel_confirmation', 'تأكيد الإلغاء', '2023-02-12 21:10:17', '2023-02-12 21:10:17'),
(3236, 'sa', 'are_you_sure_to_cancel__this_order', 'هل أنت متأكد من إلغاء هذا الطلب', '2023-02-12 21:10:17', '2023-02-12 21:10:17'),
(3237, 'sa', 'confirm', 'يتأكد', '2023-02-12 21:10:17', '2023-02-12 21:10:17'),
(3238, 'sa', 'accept_confirmation', 'قبول التأكيد', '2023-02-12 21:10:17', '2023-02-12 21:10:17'),
(3239, 'sa', 'are_you_sure_to_accept__this_order', 'هل أنت متأكد من قبول هذا الطلب', '2023-02-12 21:10:17', '2023-02-12 21:10:17'),
(3240, 'sa', 'active', 'نشيط', '2023-02-12 21:10:17', '2023-02-12 21:10:17'),
(3241, 'sa', 'inactive', 'غير نشط', '2023-02-12 21:10:17', '2023-02-12 21:10:17'),
(3242, 'sa', 'clear_filter', 'مرشح واضح', '2023-02-12 21:10:17', '2023-02-12 21:10:17'),
(3243, 'sa', 'uid', 'Uid', '2023-02-12 21:10:17', '2023-02-12 21:10:17'),
(3244, 'sa', 'phone', 'هاتف', '2023-02-12 21:10:17', '2023-02-12 21:10:17'),
(3245, 'sa', 'no_of_order', 'رقم الطلب', '2023-02-12 21:10:17', '2023-02-12 21:10:17'),
(3246, 'sa', 'are_you_sure_to_delete_this_customer', 'هل أنت متأكد من حذف هذا العميل', '2023-02-12 21:10:17', '2023-02-12 21:10:17'),
(3247, 'sa', 'reset_password', 'إعادة تعيين كلمة المرور', '2023-02-12 21:10:17', '2023-02-12 21:10:17'),
(3248, 'sa', 'new_password', 'كلمة المرور الجديدة', '2023-02-12 21:10:17', '2023-02-12 21:10:17'),
(3249, 'sa', 'enter_new_password', 'أدخل كلمة مرور جديدة', '2023-02-12 21:10:17', '2023-02-12 21:10:17'),
(3250, 'sa', 'customer_information', 'معلومات العميل', '2023-02-12 21:10:17', '2023-02-12 21:10:17'),
(3251, 'sa', 'password_updated_successfully', 'تم تحديث كلمة السر بنجاح', '2023-02-12 21:10:17', '2023-02-12 21:10:17'),
(3252, 'sa', 'update_failed_', 'فشل التحديث', '2023-02-12 21:10:17', '2023-02-12 21:10:17'),
(3253, 'sa', 'customer_updated_successfully', 'تم تحديث العميل بنجاح', '2023-02-12 21:10:17', '2023-02-12 21:10:17'),
(3254, 'sa', 'login_failed_', 'فشل تسجيل الدخول', '2023-02-12 21:10:17', '2023-02-12 21:10:17'),
(3255, 'sa', 'edit_brand', 'تحرير العلامة التجارية', '2023-02-12 21:10:17', '2023-02-12 21:10:17'),
(3256, 'sa', 'brand_updated_successfully', 'تم تحديث العلامة التجارية بنجاح', '2023-02-12 21:10:17', '2023-02-12 21:10:17'),
(3257, 'sa', 'category_status_updated_successfully', 'تم تحديث حالة الفئة بنجاح', '2023-02-12 21:10:17', '2023-02-12 21:10:17'),
(3258, 'sa', 'cache_clear_successfully', 'تم مسح ذاكرة التخزين المؤقت بنجاح', '2023-02-12 21:10:17', '2023-02-12 21:10:17'),
(3259, 'sa', 'category_deleted_successfully', 'تم حذف الفئة بنجاح', '2023-02-12 21:10:17', '2023-02-12 21:10:17'),
(3260, 'sa', 'tl_commerce__blog', 'مدونة', '2023-02-12 21:10:17', '2023-02-12 21:10:17'),
(3261, 'sa', 'mine', 'مِلكِي', '2023-02-12 21:10:17', '2023-02-12 21:10:17'),
(3262, 'sa', 'scheduled', 'المقرر', '2023-02-12 21:10:17', '2023-02-12 21:10:17'),
(3263, 'sa', 'drafts', 'المسودات', '2023-02-12 21:10:17', '2023-02-12 21:10:17'),
(3264, 'sa', 'author', 'مؤلف', '2023-02-12 21:10:17', '2023-02-12 21:10:17'),
(3265, 'sa', 'category', 'فئة', '2023-02-12 21:10:17', '2023-02-12 21:10:17'),
(3266, 'sa', 'comment', 'تعليق', '2023-02-12 21:10:17', '2023-02-12 21:10:17'),
(3267, 'sa', 'tl_commerce__add_blog_category', 'أضف فئة المدونة', '2023-02-12 21:10:17', '2023-02-12 21:10:17'),
(3268, 'sa', 'please_insert_a_name', 'الرجاء إدخال اسم', '2023-02-12 21:10:17', '2023-02-12 21:10:17'),
(3269, 'sa', 'this_name_is_already_available_please_insert_another', 'هذا الاسم متوفر بالفعل الرجاء إدخال اسم آخر', '2023-02-12 21:10:17', '2023-02-12 21:10:17'),
(3270, 'sa', 'please_write_the_category_name_under_225_words', 'يرجى كتابة اسم الفئة تحت 225 كلمة', '2023-02-12 21:10:17', '2023-02-12 21:10:17'),
(3271, 'sa', 'this_permalink_is_already_available_please_insert_another', 'هذا الرابط الثابت متاح بالفعل الرجاء إدخال آخر', '2023-02-12 21:10:17', '2023-02-12 21:10:17'),
(3272, 'sa', 'new_blog_category_created_successfully', 'تم إنشاء فئة مدونة جديدة بنجاح!', '2023-02-12 21:10:17', '2023-02-12 21:10:17'),
(3273, 'sa', 'blog_category_publish_status_changed_successfully', 'تم تغيير حالة نشر فئة المدونة بنجاح', '2023-02-12 21:10:17', '2023-02-12 21:10:17'),
(3274, 'sa', 'blog_category_featured_status_changed_successfully', 'تم تغيير الحالة المميزة لفئة المدونة بنجاح', '2023-02-12 21:10:17', '2023-02-12 21:10:17'),
(3275, 'sa', 'blog_category_deleted_successfully', 'تم حذف فئة المدونة بنجاح', '2023-02-12 21:10:17', '2023-02-12 21:10:17'),
(3276, 'sa', 'edit_menus', 'تحرير القوائم', '2023-02-12 21:10:17', '2023-02-12 21:10:17'),
(3277, 'sa', 'manage_locations', 'إدارة المواقع', '2023-02-12 21:10:17', '2023-02-12 21:10:17'),
(3278, 'sa', 'select_a_menu_to_edit', 'حدد قائمة لتحريرها:', '2023-02-12 21:10:17', '2023-02-12 21:10:17'),
(3279, 'sa', 'create_menu_', 'إنشاء قائمة', '2023-02-12 21:10:17', '2023-02-12 21:10:17'),
(3280, 'sa', 'translate_menu_into', 'قائمة الترجمة إلى:', '2023-02-12 21:10:17', '2023-02-12 21:10:17'),
(3281, 'sa', 'custom_links', 'روابط مخصصة', '2023-02-12 21:10:17', '2023-02-12 21:10:17'),
(3282, 'sa', 'link_text', 'نص الارتباط', '2023-02-12 21:10:17', '2023-02-12 21:10:17'),
(3283, 'sa', 'add_to_menu', 'أضف إلى القائمة', '2023-02-12 21:10:17', '2023-02-12 21:10:17'),
(3284, 'sa', 'most_recent', 'الأحدث', '2023-02-12 21:10:17', '2023-02-12 21:10:17'),
(3285, 'sa', 'view_all', 'مشاهدة الكل', '2023-02-12 21:10:17', '2023-02-12 21:10:17'),
(3286, 'sa', 'search', 'يبحث', '2023-02-12 21:10:17', '2023-02-12 21:10:17'),
(3287, 'sa', 'select_all', 'اختر الكل', '2023-02-12 21:10:17', '2023-02-12 21:10:17'),
(3288, 'sa', 'select_all_', 'اختر الكل', '2023-02-12 21:10:18', '2023-02-12 21:10:18'),
(3289, 'sa', 'posts', 'دعامات', '2023-02-12 21:10:18', '2023-02-12 21:10:18'),
(3290, 'sa', 'menu_name', 'اسم القائمة', '2023-02-12 21:10:18', '2023-02-12 21:10:18'),
(3291, 'sa', 'give_your_menu_a_name_then_click_save_menu', 'أدخل اسمًا لقائمتك ، ثم انقر فوق حفظ القائمة.', '2023-02-12 21:10:18', '2023-02-12 21:10:18'),
(3292, 'sa', 'menu_settings', 'إعدادات القائمة', '2023-02-12 21:10:18', '2023-02-12 21:10:18'),
(3293, 'sa', 'display_locations', 'عرض المواقع', '2023-02-12 21:10:18', '2023-02-12 21:10:18'),
(3294, 'sa', 'currently_set_to__', 'معين حاليًا على:', '2023-02-12 21:10:18', '2023-02-12 21:10:18'),
(3295, 'sa', 'save_menu', 'حفظ القائمة', '2023-02-12 21:10:18', '2023-02-12 21:10:18'),
(3296, 'sa', 'drag_the_items_into_the_order_you_prefer_click_the_arrow_on_the_right_of_the_item_to_reveal_additional_configuration_options', 'انقر فوق السهم الموجود على يمين العنصر للكشف عن خيارات التكوين الإضافية.', '2023-02-12 21:10:18', '2023-02-12 21:10:18'),
(3297, 'sa', 'delete_menu', 'قائمة الحذف', '2023-02-12 21:10:18', '2023-02-12 21:10:18'),
(3298, 'sa', 'update_menu', 'قائمة التحديث', '2023-02-12 21:10:18', '2023-02-12 21:10:18'),
(3299, 'sa', 'your_theme_supports', 'موضوعك يدعم', '2023-02-12 21:10:18', '2023-02-12 21:10:18'),
(3300, 'sa', 'menus_select_which_menu_appears_in_each_location', 'حدد القائمة التي تظهر في كل موقع.', '2023-02-12 21:10:18', '2023-02-12 21:10:18'),
(3301, 'sa', 'theme_location', 'موقع الموضوع', '2023-02-12 21:10:18', '2023-02-12 21:10:18'),
(3302, 'sa', 'assigned_menu', 'القائمة المعينة', '2023-02-12 21:10:18', '2023-02-12 21:10:18'),
(3303, 'sa', '_edit', 'يحرر', '2023-02-12 21:10:18', '2023-02-12 21:10:18'),
(3304, 'sa', 'use_new_menu', 'استخدم قائمة جديدة', '2023-02-12 21:10:18', '2023-02-12 21:10:18'),
(3305, 'sa', 'comment_loading_failed', 'التعليق فشل تحميل', '2023-02-12 21:10:18', '2023-02-12 21:10:18'),
(3306, 'sa', 'title_is_required', 'العنوان مطلوب', '2023-02-12 21:10:18', '2023-02-12 21:10:18'),
(3307, 'sa', '_image_for_desktop_is_required', 'مطلوب صورة لسطح المكتب', '2023-02-12 21:10:18', '2023-02-12 21:10:18'),
(3308, 'sa', 'image_for_mobile_is_required', 'مطلوب صورة للجوال', '2023-02-12 21:10:18', '2023-02-12 21:10:18'),
(3309, 'sa', 'slider_added_successfully', 'تمت إضافة شريط التمرير بنجاح', '2023-02-12 21:10:18', '2023-02-12 21:10:18'),
(3310, 'sa', 'homepage_builder', 'منشئ الصفحة الرئيسية', '2023-02-12 21:10:18', '2023-02-12 21:10:18'),
(3311, 'sa', 'home_page_sections', 'أقسام الصفحة الرئيسية', '2023-02-12 21:10:18', '2023-02-12 21:10:18'),
(3312, 'sa', 'add_new_section', 'إضافة قسم جديد', '2023-02-12 21:10:18', '2023-02-12 21:10:18'),
(3313, 'sa', 'manage_slider', 'إدارة شريط التمرير', '2023-02-12 21:10:18', '2023-02-12 21:10:18'),
(3314, 'sa', 'no_section_found', 'لم يتم العثور على قسم', '2023-02-12 21:10:18', '2023-02-12 21:10:18'),
(3315, 'sa', 'are_you_sure_to_delete_this_section', 'هل أنت متأكد من حذف هذا القسم', '2023-02-12 21:10:18', '2023-02-12 21:10:18'),
(3316, 'sa', 'new_section', 'قسم جديد', '2023-02-12 21:10:18', '2023-02-12 21:10:18'),
(3317, 'sa', 'select_section', 'حدد القسم', '2023-02-12 21:10:18', '2023-02-12 21:10:18'),
(3318, 'sa', 'select_layout', 'حدد التخطيط', '2023-02-12 21:10:18', '2023-02-12 21:10:18'),
(3319, 'sa', 'ads', 'إعلانات', '2023-02-12 21:10:18', '2023-02-12 21:10:18'),
(3320, 'sa', 'blogs', 'المدونات', '2023-02-12 21:10:18', '2023-02-12 21:10:18'),
(3321, 'sa', 'flash_deal', 'صفقة فلاش', '2023-02-12 21:10:18', '2023-02-12 21:10:18'),
(3322, 'sa', 'featured_product', 'المنتج المميز', '2023-02-12 21:10:18', '2023-02-12 21:10:18'),
(3323, 'sa', 'category_slider', 'فئة المنزلق', '2023-02-12 21:10:18', '2023-02-12 21:10:18'),
(3324, 'sa', 'product_collection', 'مجموعة المنتج', '2023-02-12 21:10:18', '2023-02-12 21:10:18'),
(3325, 'sa', 'custom_product_section', 'قسم المنتجات المخصصة', '2023-02-12 21:10:18', '2023-02-12 21:10:18'),
(3326, 'sa', 'section_properties', 'خصائص القسم', '2023-02-12 21:10:18', '2023-02-12 21:10:18'),
(3327, 'sa', 'background', 'خلفية', '2023-02-12 21:10:18', '2023-02-12 21:10:18'),
(3328, 'sa', 'advanced', 'متقدم', '2023-02-12 21:10:18', '2023-02-12 21:10:18'),
(3329, 'sa', 'title_is_not_visible_in_homepage', 'العنوان غير مرئي في الصفحة الرئيسية', '2023-02-12 21:10:18', '2023-02-12 21:10:18'),
(3330, 'sa', 'background_color', 'لون الخلفية', '2023-02-12 21:10:18', '2023-02-12 21:10:18'),
(3331, 'sa', 'background_image', 'الصورة الخلفية', '2023-02-12 21:10:18', '2023-02-12 21:10:18'),
(3332, 'sa', 'background_size', 'حجم الخلفية', '2023-02-12 21:10:18', '2023-02-12 21:10:18'),
(3333, 'sa', 'background_position', 'موقف الخلفية', '2023-02-12 21:10:18', '2023-02-12 21:10:18'),
(3334, 'sa', 'background_repeat', 'تكرار الخلفية', '2023-02-12 21:10:18', '2023-02-12 21:10:18'),
(3335, 'sa', 'padding', 'حشوة', '2023-02-12 21:10:18', '2023-02-12 21:10:18'),
(3336, 'sa', 'bottom', 'قاع', '2023-02-12 21:10:18', '2023-02-12 21:10:18'),
(3337, 'sa', 'margin', 'هامِش', '2023-02-12 21:10:18', '2023-02-12 21:10:18'),
(3338, 'sa', 'new_section_added_successfully', 'تمت إضافة قسم جديد بنجاح', '2023-02-12 21:10:18', '2023-02-12 21:10:18'),
(3339, 'sa', 'update_section', 'قسم التحديث', '2023-02-12 21:10:18', '2023-02-12 21:10:18'),
(3340, 'sa', 'section', 'قسم', '2023-02-12 21:10:18', '2023-02-12 21:10:18'),
(3341, 'sa', 'cover', 'غطاء', '2023-02-12 21:10:18', '2023-02-12 21:10:18'),
(3342, 'sa', 'auto', 'آلي', '2023-02-12 21:10:18', '2023-02-12 21:10:18'),
(3343, 'sa', 'contain', 'يحتوي', '2023-02-12 21:10:18', '2023-02-12 21:10:18'),
(3344, 'sa', 'initial', 'أولي', '2023-02-12 21:10:18', '2023-02-12 21:10:18'),
(3345, 'sa', 'revert', 'يرجع', '2023-02-12 21:10:18', '2023-02-12 21:10:18'),
(3346, 'sa', 'inherit', 'انت ورثت', '2023-02-12 21:10:18', '2023-02-12 21:10:18'),
(3347, 'sa', 'revertlayer', 'عودة طبقة', '2023-02-12 21:10:18', '2023-02-12 21:10:18'),
(3348, 'sa', 'unset', 'غير محدد', '2023-02-12 21:10:18', '2023-02-12 21:10:18'),
(3349, 'sa', 'section_updated_successfully', 'تم تحديث القسم بنجاح', '2023-02-12 21:10:18', '2023-02-12 21:10:18'),
(3350, 'sa', 'visibility', 'الرؤية', '2023-02-12 21:10:18', '2023-02-12 21:10:18'),
(3351, 'sa', 'visible', 'مرئي', '2023-02-12 21:10:18', '2023-02-12 21:10:18'),
(3352, 'sa', 'hide', 'يخفي', '2023-02-12 21:10:18', '2023-02-12 21:10:18'),
(3353, 'sa', 'filter_by_rating', 'تصفية حسب التصنيف', '2023-02-12 21:10:18', '2023-02-12 21:10:18'),
(3354, 'sa', 'product', 'منتج', '2023-02-12 21:10:18', '2023-02-12 21:10:18'),
(3355, 'sa', 'order', 'طلب', '2023-02-12 21:10:18', '2023-02-12 21:10:18'),
(3356, 'sa', 'rating', 'تقييم', '2023-02-12 21:10:18', '2023-02-12 21:10:18'),
(3357, 'sa', 'review_details', 'مراجعة التفاصيل', '2023-02-12 21:10:18', '2023-02-12 21:10:18'),
(3358, 'sa', 'new_unit_added_successfully', 'تمت إضافة وحدة جديدة بنجاح', '2023-02-12 21:10:18', '2023-02-12 21:10:18'),
(3359, 'sa', 'attributes_values', 'قيم السمات', '2023-02-12 21:10:18', '2023-02-12 21:10:18'),
(3360, 'sa', 'attribute_values', 'قيم السمات', '2023-02-12 21:10:18', '2023-02-12 21:10:18'),
(3361, 'sa', 'new_value', 'قيمة جديدة', '2023-02-12 21:10:18', '2023-02-12 21:10:18'),
(3362, 'sa', 'attribute', 'يصف', '2023-02-12 21:10:18', '2023-02-12 21:10:18'),
(3363, 'sa', 'value', 'قيمة', '2023-02-12 21:10:18', '2023-02-12 21:10:18'),
(3364, 'sa', 'please_select_a_attribute', 'الرجاء تحديد سمة', '2023-02-12 21:10:18', '2023-02-12 21:10:18'),
(3365, 'sa', 'invalid_attribute', 'سمة غير صالحة', '2023-02-12 21:10:18', '2023-02-12 21:10:18'),
(3366, 'sa', 'attribute_value_added_successfully', 'تمت إضافة قيمة السمة بنجاح', '2023-02-12 21:10:18', '2023-02-12 21:10:18'),
(3367, 'sa', 'variant', 'متغير', '2023-02-12 21:10:18', '2023-02-12 21:10:18'),
(3368, 'sa', 'selected_items_deleted_successfully', 'تم حذف العناصر المحددة بنجاح', '2023-02-12 21:10:18', '2023-02-12 21:10:18'),
(3369, 'sa', 'edit_unit', 'تحرير الوحدة', '2023-02-12 21:10:18', '2023-02-12 21:10:18'),
(3370, 'sa', 'unit_updated_successfully', 'تم تحديث الوحدة بنجاح', '2023-02-12 21:10:18', '2023-02-12 21:10:18'),
(3371, 'sa', 'unit_deleted_successfully', 'تم حذف الوحدة بنجاح', '2023-02-12 21:10:18', '2023-02-12 21:10:18'),
(3372, 'sa', 'new_condition', 'شرط جديد', '2023-02-12 21:10:18', '2023-02-12 21:10:18'),
(3373, 'sa', 'new_condition_added_successfully', 'تمت إضافة الشرط الجديد بنجاح', '2023-02-12 21:10:18', '2023-02-12 21:10:18'),
(3374, 'sa', 'color', 'لون', '2023-02-12 21:10:18', '2023-02-12 21:10:18'),
(3375, 'sa', 'discount_amount__must_be_a_number', 'يجب أن يكون مبلغ الخصم رقمًا', '2023-02-12 21:10:18', '2023-02-12 21:10:18'),
(3376, 'sa', 'purchase_price__must_be_a_number', 'يجب أن يكون سعر الشراء رقمًا', '2023-02-12 21:10:18', '2023-02-12 21:10:18'),
(3377, 'sa', 'purchase_price__is_required', 'سعر الشراء مطلوب', '2023-02-12 21:10:18', '2023-02-12 21:10:18'),
(3378, 'sa', 'unit_price__must_be_a_number', 'يجب أن يكون سعر الوحدة رقمًا', '2023-02-12 21:10:18', '2023-02-12 21:10:18'),
(3379, 'sa', 'unit_price__is_required', 'سعر الوحدة مطلوب', '2023-02-12 21:10:18', '2023-02-12 21:10:18'),
(3380, 'sa', 'quantity__must_be_a_number', 'يجب أن تكون الكمية رقمًا', '2023-02-12 21:10:18', '2023-02-12 21:10:18'),
(3381, 'sa', 'quantity__is_required', 'الكمية مطلوبة', '2023-02-12 21:10:18', '2023-02-12 21:10:18'),
(3382, 'sa', 'new_product_created_successfully', 'تم إنشاء منتج جديد بنجاح', '2023-02-12 21:10:18', '2023-02-12 21:10:18'),
(3383, 'sa', 'set_discount', 'تعيين الخصم', '2023-02-12 21:10:18', '2023-02-12 21:10:18'),
(3384, 'sa', 'update_price', 'تحديث سعر', '2023-02-12 21:10:18', '2023-02-12 21:10:18'),
(3385, 'sa', 'stock', 'مخزون', '2023-02-12 21:10:18', '2023-02-12 21:10:18'),
(3386, 'sa', 'low', 'قليل', '2023-02-12 21:10:18', '2023-02-12 21:10:18'),
(3387, 'sa', 'num_of_sale', 'رقم البيع', '2023-02-12 21:10:18', '2023-02-12 21:10:18');




INSERT INTO `tl_translations` (`id`, `lang`, `lang_key`, `lang_value`, `created_at`, `updated_at`) VALUES
(3388, 'sa', 'update_stock', 'تحديث المخزون', '2023-02-12 21:10:18', '2023-02-12 21:10:18'),
(3389, 'sa', 'items_deleted_successfully', 'تم حذف العناصر بنجاح', '2023-02-12 21:10:18', '2023-02-12 21:10:18'),
(3390, 'sa', 'add_new_country', 'أضف دولة جديدة', '2023-02-12 21:10:18', '2023-02-12 21:10:18'),
(3391, 'sa', 'phone_code', 'كود الهاتف', '2023-02-12 21:10:18', '2023-02-12 21:10:18'),
(3392, 'sa', 'flag', 'علَم', '2023-02-12 21:10:18', '2023-02-12 21:10:18'),
(3393, 'sa', 'new_country', 'بلد جديد', '2023-02-12 21:10:18', '2023-02-12 21:10:18'),
(3394, 'sa', 'select_a_option', 'حدد خيارًا', '2023-02-12 21:10:18', '2023-02-12 21:10:18'),
(3395, 'sa', 'add_new_language', 'أضف لغة جديدة', '2023-02-12 21:10:18', '2023-02-12 21:10:18'),
(3396, 'sa', 'native_name', 'الاسم الأصلي', '2023-02-12 21:10:18', '2023-02-12 21:10:18'),
(3397, 'sa', 'rtl', 'RTL', '2023-02-12 21:10:18', '2023-02-12 21:10:18'),
(3398, 'sa', 'backend_translations', 'ترجمات الخلفية', '2023-02-12 21:10:18', '2023-02-12 21:10:18'),
(3399, 'sa', 'frontend_translations', 'ترجمات الواجهة الأمامية', '2023-02-12 21:10:18', '2023-02-12 21:10:18'),
(3400, 'sa', 'cencel', 'سينسل', '2023-02-12 21:10:18', '2023-02-12 21:10:18'),
(3401, 'sa', 'new_language', 'لغة جديدة', '2023-02-12 21:11:58', '2023-02-12 21:11:58'),
(3402, 'sa', 'type_name', 'أكتب اسم', '2023-02-12 21:11:58', '2023-02-12 21:11:58'),
(3403, 'sa', 'type__native_name', 'اكتب الاسم الأصلي', '2023-02-12 21:11:58', '2023-02-12 21:11:58'),
(3404, 'sa', 'new_language_added_successfully', 'تمت إضافة لغة جديدة بنجاح', '2023-02-12 21:11:58', '2023-02-12 21:11:58'),
(3405, 'sa', 'rtl_status_updated_successfully', 'تم تحديث حالة RTL بنجاح', '2023-02-12 21:11:58', '2023-02-12 21:11:58'),
(3406, 'sa', 'select_countries', 'حدد البلدان', '2023-02-12 21:11:58', '2023-02-12 21:11:58'),
(3407, 'sa', 'select_states', 'حدد الدول', '2023-02-12 21:11:58', '2023-02-12 21:11:58'),
(3408, 'sa', 'select_cities', 'حدد المدن', '2023-02-12 21:11:58', '2023-02-12 21:11:58'),
(3409, 'sa', 'product_discount_updated_successfully', 'تم تحديث الخصم على المنتج بنجاح', '2023-02-12 21:11:58', '2023-02-12 21:11:58'),
(3410, 'sa', 'product_discount_update_faled', 'فشل تحديث خصم المنتج', '2023-02-12 21:11:58', '2023-02-12 21:11:58'),
(3411, 'sa', 'product_price_updated_successfully', 'تم تحديث سعر المنتج بنجاح', '2023-02-12 21:11:58', '2023-02-12 21:11:58'),
(3412, 'sa', 'product_price_update_faled', 'تعثر تحديث سعر المنتج', '2023-02-12 21:11:58', '2023-02-12 21:11:58'),
(3413, 'sa', 'product_stock_updated_successfully', 'تم تحديث مخزون المنتج بنجاح', '2023-02-12 21:11:58', '2023-02-12 21:11:58'),
(3414, 'sa', 'product_stock_update_faled', 'فشل تحديث مخزون المنتج', '2023-02-12 21:11:58', '2023-02-12 21:11:58'),
(3415, 'sa', 'key', 'مفتاح', '2023-02-12 21:11:58', '2023-02-12 21:11:58'),
(3416, 'sa', 'language', 'لغة', '2023-02-12 21:11:58', '2023-02-12 21:11:58'),
(3417, 'sa', 'save_chnages', 'حفظ التغييرات', '2023-02-12 21:11:58', '2023-02-12 21:11:58'),
(3418, 'sa', 'translations_updated_successfully', 'تم تحديث الترجمات بنجاح', '2023-02-12 21:11:58', '2023-02-12 21:11:58'),
(3419, 'sa', 'tl_commerce__comment_setting', 'إعداد التعليق', '2023-02-12 21:11:58', '2023-02-12 21:11:58'),
(3420, 'sa', 'comment_setting', 'إعداد التعليق', '2023-02-12 21:11:58', '2023-02-12 21:11:58'),
(3421, 'sa', 'default_blog_settings', 'إعدادات المدونة الافتراضية', '2023-02-12 21:11:58', '2023-02-12 21:11:58'),
(3422, 'sa', 'allow_people_to_submit_comments_on_new_blogs', 'السماح للأشخاص بإرسال تعليقات على المدونات الجديدة', '2023-02-12 21:11:58', '2023-02-12 21:11:58'),
(3423, 'sa', 'other_comment_settings', 'إعدادات التعليقات الأخرى', '2023-02-12 21:11:58', '2023-02-12 21:11:58'),
(3424, 'sa', 'comment_author_must_fill_out_name_and_email', 'كاتب التعليق يجب أن يملأ الاسم والبريد الإلكتروني', '2023-02-12 21:11:58', '2023-02-12 21:11:58'),
(3425, 'sa', 'users_must_be_registered_and_logged_in_to_comment', 'المستخدمون يجب ان يسجلوا دخولهم للتعليق', '2023-02-12 21:11:58', '2023-02-12 21:11:58'),
(3426, 'sa', 'automatically_close_comments_on_blogs_older_than', 'إغلاق التعليقات تلقائيًا على المدونات الأقدم من', '2023-02-12 21:11:58', '2023-02-12 21:11:58'),
(3427, 'sa', 'days', 'أيام', '2023-02-12 21:11:58', '2023-02-12 21:11:58'),
(3428, 'sa', 'break_comments_into_pages_with', 'قسّم التعليقات إلى صفحات باستخدام', '2023-02-12 21:11:58', '2023-02-12 21:11:58'),
(3429, 'sa', 'top_level_comments_per_page_and', 'أعلى مستوى من التعليقات في كل صفحة و', '2023-02-12 21:11:58', '2023-02-12 21:11:58'),
(3430, 'sa', 'comments_should_be_displayed_with_the', 'يجب عرض التعليقات بامتداد', '2023-02-12 21:11:58', '2023-02-12 21:11:58'),
(3431, 'sa', 'older', 'اكبر سنا', '2023-02-12 21:11:58', '2023-02-12 21:11:58'),
(3432, 'sa', 'newer', 'أحدث', '2023-02-12 21:11:58', '2023-02-12 21:11:58'),
(3433, 'sa', 'comments_at_the_top_of_each_page', 'من التعليقات أعلى كل صفحة', '2023-02-12 21:11:58', '2023-02-12 21:11:58'),
(3434, 'sa', 'email_me_whenever', 'راسلني في أي وقت', '2023-02-12 21:11:58', '2023-02-12 21:11:58'),
(3435, 'sa', 'anyone_posts_a_comment', 'أي شخص ينشر تعليق', '2023-02-12 21:11:58', '2023-02-12 21:11:58'),
(3436, 'sa', 'a_comment_is_held_for_moderation', 'يتم تعليق تعليق للاعتدال', '2023-02-12 21:11:58', '2023-02-12 21:11:58'),
(3437, 'sa', 'before_a_comment_appears', 'قبل أن يظهر التعليق', '2023-02-12 21:11:58', '2023-02-12 21:11:58'),
(3438, 'sa', 'comment_must_be_manually_approved', 'يجب الموافقة على التعليق يدويًا', '2023-02-12 21:11:58', '2023-02-12 21:11:58'),
(3439, 'sa', 'comment_author_must_have_a_previously_approved_comment', 'يجب أن يكون لدى مؤلف التعليق تعليق تمت الموافقة عليه مسبقًا', '2023-02-12 21:11:58', '2023-02-12 21:11:58'),
(3440, 'sa', 'comment_moderation', 'تعليق الاعتدال', '2023-02-12 21:11:58', '2023-02-12 21:11:58'),
(3441, 'sa', 'hold_a_comment_in_the_queue_if_it_contains', 'تعليق تعليق في قائمة الانتظار إذا كان يحتوي على', '2023-02-12 21:11:58', '2023-02-12 21:11:58'),
(3442, 'sa', 'or_more_links_a_common_characteristic_of_comment_spam_is_a_large____________________________________number_of_hyperlinks', '(من السمات الشائعة للتعليقات غير المرغوب فيها وجود عدد كبير من الارتباطات التشعبية.)', '2023-02-12 21:11:58', '2023-02-12 21:11:58'),
(3443, 'sa', 'when_a_comment_contains_any_of_these_words_in_its_content_author_name_url________________________________email_ip_address_or_browsers_user_agent_string_it_will_be_held_in_the_', 'عندما يحتوي تعليق على أي من هذه الكلمات في محتواه ، أو اسم المؤلف ، أو عنوان URL ، أو البريد الإلكتروني ، أو عنوان IP ، أو سلسلة وكيل مستخدم المتصفح ، فسيتم تعليقه في', '2023-02-12 21:11:58', '2023-02-12 21:11:58'),
(3444, 'sa', 'pending_queue', 'قائمة انتظار معلقة', '2023-02-12 21:11:58', '2023-02-12 21:11:58'),
(3445, 'sa', 'one_word_or_ip_address_per_line_it_will_match_inside_words_so_press_will_match________________________________wordpress', 'سيتطابق مع الكلمات الداخلية ، لذا فإن \"اضغط\" سيتطابق مع \"WordPress\".', '2023-02-12 21:11:58', '2023-02-12 21:11:58'),
(3446, 'sa', 'disallowed_comment_keys', 'مفاتيح التعليقات غير المسموح بها', '2023-02-12 21:11:58', '2023-02-12 21:11:58'),
(3447, 'sa', 'when_a_comment_contains_any_of_these_words_in_its_content_author_name_url________________________________email_ip_address_or_browsers_user_agent_string_it_will_be_put_in_the_trash_one_word_or________________________________ip_address_per_line_it_will_match_inside_words_so_press_will_match_wordpress', 'سيتطابق مع الكلمات الداخلية ، لذا فإن \"اضغط\" سيتطابق مع \"WordPress\".', '2023-02-12 21:11:58', '2023-02-12 21:11:58'),
(3448, 'sa', 'avatars', 'الآلهة', '2023-02-12 21:11:58', '2023-02-12 21:11:58'),
(3449, 'sa', 'an_avatar_is_an_image_that_can_be_associated_with_a_user_across_multiple_websites_in_this_area_you_can_choose_to_display_avatars_of_users_who_interact_with_the_site', 'في هذه المنطقة ، يمكنك اختيار عرض الصور الرمزية للمستخدمين الذين يتفاعلون مع الموقع.', '2023-02-12 21:11:58', '2023-02-12 21:11:58'),
(3450, 'sa', 'avatar_display', 'عرض الصورة الرمزية', '2023-02-12 21:11:58', '2023-02-12 21:11:58'),
(3451, 'sa', 'show_avatars', 'عرض الصور الرمزية', '2023-02-12 21:11:58', '2023-02-12 21:11:58'),
(3452, 'sa', 'default_avatar', 'الصورة الرمزية الافتراضية', '2023-02-12 21:11:58', '2023-02-12 21:11:58'),
(3453, 'sa', 'for_users_without_a_custom_avatar_of_their_own_you_can_either_display_a_generic_logo_or_a_generated_one_based_on_their_email_address', 'بالنسبة للمستخدمين الذين ليس لديهم صورة رمزية مخصصة خاصة بهم ، يمكنك إما عرض شعار عام أو شعار تم إنشاؤه بناءً على عنوان بريدهم الإلكتروني.', '2023-02-12 21:11:58', '2023-02-12 21:11:58'),
(3454, 'sa', 'mystery_person', 'شخص غامض', '2023-02-12 21:11:58', '2023-02-12 21:11:58'),
(3455, 'sa', 'blank', 'فارغ', '2023-02-12 21:11:58', '2023-02-12 21:11:58'),
(3456, 'sa', 'gravatar_logo', 'شعار Gravatar', '2023-02-12 21:11:58', '2023-02-12 21:11:58'),
(3457, 'sa', 'identicon_generated', 'رمز التعريف (تم إنشاؤه)', '2023-02-12 21:11:58', '2023-02-12 21:11:58'),
(3458, 'sa', 'wavatar_generated', 'وافاتار (ولدت)', '2023-02-12 21:11:58', '2023-02-12 21:11:58'),
(3459, 'sa', 'monsterid_generated', 'MonsterID (مُنشأ)', '2023-02-12 21:11:58', '2023-02-12 21:11:58'),
(3460, 'sa', 'retro_generated', 'رجعي (تم إنشاؤه)', '2023-02-12 21:11:58', '2023-02-12 21:11:58'),
(3461, 'sa', 'module_name', 'اسم وحدة', '2023-02-12 21:11:58', '2023-02-12 21:11:58'),
(3462, 'sa', 'permission_name', 'اسم الإذن', '2023-02-12 21:11:58', '2023-02-12 21:11:58'),
(3463, 'sa', 'edit_color', 'تحرير اللون', '2023-02-12 21:11:58', '2023-02-12 21:11:58'),
(3464, 'sa', 'color_updated_successfully', 'تم تحديث اللون بنجاح', '2023-02-12 21:11:58', '2023-02-12 21:11:58'),
(3465, 'sa', 'edit_attribute_value', 'تحرير قيمة السمة', '2023-02-12 21:11:58', '2023-02-12 21:11:58'),
(3466, 'sa', 'edit_attribute', 'تحرير السمة', '2023-02-12 21:11:58', '2023-02-12 21:11:58'),
(3467, 'sa', 'attribute_updated_successfully', 'تم تحديث السمة بنجاح', '2023-02-12 21:11:58', '2023-02-12 21:11:58'),
(3468, 'sa', 'edit_tag', 'تحرير العلامة', '2023-02-12 21:11:58', '2023-02-12 21:11:58'),
(3469, 'sa', 'edit_country', 'تحرير الدولة', '2023-02-12 21:11:58', '2023-02-12 21:11:58'),
(3470, 'sa', 'country_information', 'معلومات الدولة', '2023-02-12 21:11:58', '2023-02-12 21:11:58'),
(3471, 'sa', 'shipping', 'شحن', '2023-02-12 21:11:58', '2023-02-12 21:11:58'),
(3472, 'sa', 'create_new_profile', 'إنشاء ملف تعريف جديد', '2023-02-12 21:11:58', '2023-02-12 21:11:58'),
(3473, 'sa', 'no_profile_created_yet', 'لم يتم إنشاء ملف تعريف حتى الآن', '2023-02-12 21:11:58', '2023-02-12 21:11:58'),
(3474, 'sa', 'shipping_time', 'وقت الشحن', '2023-02-12 21:11:58', '2023-02-12 21:11:58'),
(3475, 'sa', 'create_new_time', 'إنشاء وقت جديد', '2023-02-12 21:11:58', '2023-02-12 21:11:58'),
(3476, 'sa', 'min_shipping_time', 'وقت الشحن دقيقة', '2023-02-12 21:11:58', '2023-02-12 21:11:58'),
(3477, 'sa', 'max_shipping_time', 'أقصى وقت الشحن', '2023-02-12 21:11:58', '2023-02-12 21:11:58'),
(3478, 'sa', 'shipping_carriers', 'شركات الشحن', '2023-02-12 21:11:58', '2023-02-12 21:11:58'),
(3479, 'sa', 'add_new_shipping_time', 'أضف وقت شحن جديد', '2023-02-12 21:11:58', '2023-02-12 21:11:58'),
(3480, 'sa', 'minimum_shipping_time', 'الحد الأدنى لوقت الشحن', '2023-02-12 21:11:58', '2023-02-12 21:11:58'),
(3481, 'sa', 'hours', 'ساعات', '2023-02-12 21:11:58', '2023-02-12 21:11:58'),
(3482, 'sa', 'minutes', 'دقائق', '2023-02-12 21:11:58', '2023-02-12 21:11:58'),
(3483, 'sa', 'maximum_shipping_time', 'وقت الشحن الأقصى', '2023-02-12 21:11:58', '2023-02-12 21:11:58'),
(3484, 'sa', 'add_new_state', 'أضف ولاية جديدة', '2023-02-12 21:11:58', '2023-02-12 21:11:58'),
(3485, 'sa', 'country', 'دولة', '2023-02-12 21:11:58', '2023-02-12 21:11:58'),
(3486, 'sa', 'add_new_city', 'أضف مدينة جديدة', '2023-02-12 21:11:58', '2023-02-12 21:11:58'),
(3487, 'sa', 'state', 'ولاية', '2023-02-12 21:11:58', '2023-02-12 21:11:58'),
(3488, 'sa', 'edit_product', 'تحرير المنتج', '2023-02-12 21:11:58', '2023-02-12 21:11:58'),
(3489, 'sa', 'gm', 'جم', '2023-02-12 21:11:58', '2023-02-12 21:11:58'),
(3490, 'sa', 'cm', 'سم', '2023-02-12 21:11:58', '2023-02-12 21:11:58'),
(3491, 'sa', 'create_or_manage_taxes', 'إنشاء أو إدارة الضرائب', '2023-02-12 21:11:58', '2023-02-12 21:11:58'),
(3492, 'sa', 'update__draft', 'تحديث ومسودة', '2023-02-12 21:11:58', '2023-02-12 21:11:58'),
(3493, 'sa', 'update__publish', 'التحديث والنشر', '2023-02-12 21:11:58', '2023-02-12 21:11:58'),
(3494, 'sa', 'product_update_successfully', 'تم تحديث المنتج بنجاح', '2023-02-12 21:11:58', '2023-02-12 21:11:58'),
(3495, 'sa', '100_authentic', '100٪ أصيل', '2023-02-12 21:11:58', '2023-02-12 21:11:58'),
(3496, 'sa', 'available', 'متاح', '2023-02-12 21:11:58', '2023-02-12 21:11:58'),
(3497, 'sa', 'edit_discount', 'تحرير الخصم', '2023-02-12 21:11:58', '2023-02-12 21:11:58'),
(3498, 'sa', 'edit_state', 'تحرير الدولة', '2023-02-12 21:11:58', '2023-02-12 21:11:58'),
(3499, 'sa', 'type__here', 'أكتب هنا', '2023-02-12 21:11:58', '2023-02-12 21:11:58'),
(3500, 'sa', 'showhide', 'اظهر المخفي', '2023-02-12 21:11:58', '2023-02-12 21:11:58'),
(3501, 'sa', 'create_shipping_profile', 'إنشاء ملف تعريف الشحن', '2023-02-12 21:11:58', '2023-02-12 21:11:58'),
(3502, 'sa', 'profile_information', 'معلومات الملف الشخصي', '2023-02-12 21:11:58', '2023-02-12 21:11:58'),
(3503, 'sa', 'profile_name', 'اسم الشخصية', '2023-02-12 21:11:58', '2023-02-12 21:11:58'),
(3504, 'sa', 'shipping_from', 'الشحن من', '2023-02-12 21:11:58', '2023-02-12 21:11:58'),
(3505, 'sa', 'location', 'موقع', '2023-02-12 21:11:58', '2023-02-12 21:11:58'),
(3506, 'sa', 'address', 'عنوان', '2023-02-12 21:11:58', '2023-02-12 21:11:58'),
(3507, 'sa', 'select_product', 'حدد المنتج', '2023-02-12 21:11:58', '2023-02-12 21:11:58'),
(3508, 'sa', 'add_currency', 'أضف العملة', '2023-02-12 21:11:58', '2023-02-12 21:11:58'),
(3509, 'sa', 'currency_name', 'اسم العملة', '2023-02-12 21:11:58', '2023-02-12 21:11:58'),
(3510, 'sa', 'currency_symbol', 'رمز العملة', '2023-02-12 21:11:58', '2023-02-12 21:11:58'),
(3511, 'sa', 'currency_code_', 'رمز العملة', '2023-02-12 21:11:58', '2023-02-12 21:11:58'),
(3512, 'sa', 'conversion_rate', 'معدل التحويل', '2023-02-12 21:11:58', '2023-02-12 21:11:58'),
(3513, 'sa', 'edit_currency', 'تحرير العملة', '2023-02-12 21:11:58', '2023-02-12 21:11:58'),
(3514, 'sa', 'symbol', 'رمز', '2023-02-12 21:11:58', '2023-02-12 21:11:58'),
(3515, 'sa', 'exchange_rate_with_usd', 'سعر الصرف بالدولار الأمريكي', '2023-02-12 21:11:59', '2023-02-12 21:11:59'),
(3516, 'sa', 'currency_position', 'وضع العملة', '2023-02-12 21:11:59', '2023-02-12 21:11:59'),
(3517, 'sa', 'select_currency_position', 'حدد مركز العملة', '2023-02-12 21:11:59', '2023-02-12 21:11:59'),
(3518, 'sa', 'thousand_separator', 'الفاصل الألف', '2023-02-12 21:11:59', '2023-02-12 21:11:59'),
(3519, 'sa', 'decimal_separator', 'الفاصل العشري', '2023-02-12 21:11:59', '2023-02-12 21:11:59'),
(3520, 'sa', 'number_of_decimals', 'عدد الكسور العشرية', '2023-02-12 21:11:59', '2023-02-12 21:11:59'),
(3521, 'sa', 'currency_added_successfully', 'تمت إضافة العملة بنجاح', '2023-02-12 21:11:59', '2023-02-12 21:11:59'),
(3522, 'sa', 'checkout', 'الدفع', '2023-02-12 21:11:59', '2023-02-12 21:11:59'),
(3523, 'sa', 'wallet', 'محفظة', '2023-02-12 21:11:59', '2023-02-12 21:11:59'),
(3524, 'sa', 'invoice', 'فاتورة', '2023-02-12 21:11:59', '2023-02-12 21:11:59'),
(3525, 'sa', 'defalt_currency', 'عملة مهجورة', '2023-02-12 21:11:59', '2023-02-12 21:11:59'),
(3526, 'sa', 'to_create_new_currency_or_manage_existing_currencies', 'لإنشاء عملة جديدة أو إدارة العملات الموجودة', '2023-02-12 21:11:59', '2023-02-12 21:11:59'),
(3527, 'sa', 'click_here', 'انقر هنا', '2023-02-12 21:11:59', '2023-02-12 21:11:59'),
(3528, 'sa', 'enable_product_reviews', 'تفعيل مراجعات المنتج', '2023-02-12 21:11:59', '2023-02-12 21:11:59'),
(3529, 'sa', 'enable_star_rating_on_product_reviews', 'تمكين تصنيف النجوم على مراجعات المنتج', '2023-02-12 21:11:59', '2023-02-12 21:11:59'),
(3530, 'sa', 'star_rating_should_be_required_not_optional', 'يجب أن يكون التقييم بالنجوم مطلوبًا وليس اختياريًا', '2023-02-12 21:11:59', '2023-02-12 21:11:59'),
(3531, 'sa', 'show_verified_customer_label_on_product_reviews', 'إظهار تسمية العميل الذي تم التحقق منه على مراجعات المنتج', '2023-02-12 21:11:59', '2023-02-12 21:11:59'),
(3532, 'sa', 'reviews_can_only_be_left_by_verified_customer', 'لا يمكن ترك التعليقات إلا من قبل العملاء الذين تم التحقق منهم', '2023-02-12 21:11:59', '2023-02-12 21:11:59'),
(3533, 'sa', 'enable_product_compare', 'تمكين مقارنة المنتج', '2023-02-12 21:11:59', '2023-02-12 21:11:59'),
(3534, 'sa', 'enable_product_discount', 'تفعيل خصم المنتج', '2023-02-12 21:11:59', '2023-02-12 21:11:59'),
(3535, 'sa', 'display_product_perpage', 'عرض المنتج لكل صفحة', '2023-02-12 21:11:59', '2023-02-12 21:11:59'),
(3536, 'sa', 'enable_billing_address', 'تمكين عنوان الفواتير', '2023-02-12 21:11:59', '2023-02-12 21:11:59'),
(3537, 'sa', 'use_the_shipping_address_as_the_billing_address_by_default', 'استخدم عنوان الشحن كعنوان إرسال الفواتير افتراضيًا', '2023-02-12 21:11:59', '2023-02-12 21:11:59'),
(3538, 'sa', 'enable_guest_checkout', 'تمكين خروج الضيف', '2023-02-12 21:11:59', '2023-02-12 21:11:59'),
(3539, 'sa', 'create_account_in_guest_checkout', 'إنشاء حساب في الخروج الضيف', '2023-02-12 21:11:59', '2023-02-12 21:11:59'),
(3540, 'sa', 'send_invoice_to_customer_email', 'إرسال الفاتورة إلى البريد الإلكتروني للعميل', '2023-02-12 21:11:59', '2023-02-12 21:11:59'),
(3541, 'sa', 'enable_tax_in_checkout', 'تمكين الضريبة في الخروج', '2023-02-12 21:11:59', '2023-02-12 21:11:59'),
(3542, 'sa', 'enable_coupon_in_checkout', 'تمكين القسيمة في الخروج', '2023-02-12 21:11:59', '2023-02-12 21:11:59'),
(3543, 'sa', 'create_or_manage_your', 'إنشاء أو إدارة الخاص بك', '2023-02-12 21:11:59', '2023-02-12 21:11:59'),
(3544, 'sa', 'enable_multiple_coupon_in_single_order', 'قم بتمكين قسيمة متعددة في طلب واحد', '2023-02-12 21:11:59', '2023-02-12 21:11:59'),
(3545, 'sa', 'enable_minimum_order_amount', 'تفعيل الحد الأدنى لمبلغ الطلب', '2023-02-12 21:11:59', '2023-02-12 21:11:59'),
(3546, 'sa', 'minimum_order_amount', 'الحد الأدنى للطلب', '2023-02-12 21:11:59', '2023-02-12 21:11:59'),
(3547, 'sa', 'enable_wallet_in_checkout', 'تمكين المحفظة في الخروج', '2023-02-12 21:11:59', '2023-02-12 21:11:59'),
(3548, 'sa', 'to_enable_wallet_you_need_to_active', 'لتمكين المحفظة تحتاج إلى تنشيط', '2023-02-12 21:11:59', '2023-02-12 21:11:59'),
(3549, 'sa', 'enable_order_note', 'تفعيل مذكرة الطلب', '2023-02-12 21:11:59', '2023-02-12 21:11:59'),
(3550, 'sa', 'enable_document_in_checkout', 'تمكين المستند في الخروج', '2023-02-12 21:11:59', '2023-02-12 21:11:59'),
(3551, 'sa', 'enable_carrier_in_checkout', 'تمكين الناقل في الخروج', '2023-02-12 21:11:59', '2023-02-12 21:11:59'),
(3552, 'sa', 'manage_your', 'إدارة حسابك', '2023-02-12 21:11:59', '2023-02-12 21:11:59'),
(3553, 'sa', 'enable_pickup_point_in_checkout', 'تمكين نقطة الالتقاط في الخروج', '2023-02-12 21:11:59', '2023-02-12 21:11:59'),
(3554, 'sa', 'customer_auto_approval', 'الموافقة التلقائية للعميل', '2023-02-12 21:11:59', '2023-02-12 21:11:59'),
(3555, 'sa', 'customer_email_verification', 'التحقق من البريد الإلكتروني للعميل', '2023-02-12 21:11:59', '2023-02-12 21:11:59'),
(3556, 'sa', 'order_code_prefix', 'بادئة كود الطلب', '2023-02-12 21:11:59', '2023-02-12 21:11:59'),
(3557, 'sa', 'enter_prefix', 'أدخل البادئة', '2023-02-12 21:11:59', '2023-02-12 21:11:59'),
(3558, 'sa', 'order_code_prefix_seperator', 'طلب فاصل بادئة كود', '2023-02-12 21:11:59', '2023-02-12 21:11:59'),
(3559, 'sa', 'enter_prefix_seperator', 'أدخل فاصل البادئة', '2023-02-12 21:11:59', '2023-02-12 21:11:59'),
(3560, 'sa', 'can_cancel_order_within', 'يمكن إلغاء الطلب في غضون', '2023-02-12 21:11:59', '2023-02-12 21:11:59'),
(3561, 'sa', 'can_return_order_within', 'يمكن إرجاع الطلب داخل', '2023-02-12 21:11:59', '2023-02-12 21:11:59'),
(3562, 'sa', 'you_can_manage_payment_methods', 'يمكنك إدارة طرق الدفع', '2023-02-12 21:11:59', '2023-02-12 21:11:59'),
(3563, 'sa', 'form_here', 'شكل هنا', '2023-02-12 21:11:59', '2023-02-12 21:11:59'),
(3564, 'sa', 'you_need_to_active_or_install', 'تحتاج إلى التنشيط أو التثبيت', '2023-02-12 21:11:59', '2023-02-12 21:11:59'),
(3565, 'sa', 'to_manage_wallets', 'لإدارة المحافظ', '2023-02-12 21:11:59', '2023-02-12 21:11:59'),
(3566, 'sa', 'enable_online_recharge', 'قم بتمكين إعادة الشحن عبر الإنترنت', '2023-02-12 21:11:59', '2023-02-12 21:11:59'),
(3567, 'sa', 'enable_offline_recharge', 'تمكين إعادة الشحن دون اتصال', '2023-02-12 21:11:59', '2023-02-12 21:11:59'),
(3568, 'sa', 'minimum_recharge_amount', 'الحد الأدنى لمبلغ إعادة الشحن', '2023-02-12 21:11:59', '2023-02-12 21:11:59'),
(3569, 'sa', 'business_email', 'بريد العمل', '2023-02-12 21:11:59', '2023-02-12 21:11:59'),
(3570, 'sa', 'business_phone', 'هاتف العمل', '2023-02-12 21:11:59', '2023-02-12 21:11:59'),
(3571, 'sa', 'business_address', 'عنوان العمل', '2023-02-12 21:11:59', '2023-02-12 21:11:59'),
(3572, 'sa', 'credential_updated_successfully', 'تم تحديث بيانات الاعتماد بنجاح', '2023-02-12 21:11:59', '2023-02-12 21:11:59'),
(3573, 'sa', 'update_failed', 'فشل التحديث', '2023-02-12 21:11:59', '2023-02-12 21:11:59'),
(3574, 'sa', 'please_insert_blog_name', 'الرجاء إدخال اسم المدونة', '2023-02-12 21:11:59', '2023-02-12 21:11:59'),
(3575, 'sa', 'please_write_the_blog_name_under_225_words', 'الرجاء كتابة اسم المدونة تحت 225 كلمة', '2023-02-12 21:11:59', '2023-02-12 21:11:59'),
(3576, 'sa', 'please_select_at_least_1_category', 'يرجى تحديد فئة واحدة على الأقل', '2023-02-12 21:11:59', '2023-02-12 21:11:59'),
(3577, 'sa', 'something_went_wrong_please_select_category_again', 'حدث خطأ ما ، يرجى تحديد الفئة مرة أخرى', '2023-02-12 21:11:59', '2023-02-12 21:11:59'),
(3578, 'sa', 'please_insert_a_valid_image', 'الرجاء إدخال صورة صالحة', '2023-02-12 21:11:59', '2023-02-12 21:11:59'),
(3579, 'sa', 'please_write_some_description', 'الرجاء كتابة بعض الوصف', '2023-02-12 21:11:59', '2023-02-12 21:11:59'),
(3580, 'sa', 'please_write_some_content', 'الرجاء كتابة بعض المحتوى', '2023-02-12 21:11:59', '2023-02-12 21:11:59'),
(3581, 'sa', 'please_select_a_valid_image', 'الرجاء تحديد صورة صالحة', '2023-02-12 21:11:59', '2023-02-12 21:11:59'),
(3582, 'sa', 'something_went_wrong_please_select_visibility_again', 'حدث خطأ ما ، يرجى تحديد الرؤية مرة أخرى', '2023-02-12 21:11:59', '2023-02-12 21:11:59'),
(3583, 'sa', 'new_blog_saved', 'تم حفظ مدونة جديدة', '2023-02-12 21:11:59', '2023-02-12 21:11:59'),
(3584, 'sa', 'tl_commerce__edit_blog', 'تحرير المدونة', '2023-02-12 21:11:59', '2023-02-12 21:11:59'),
(3585, 'sa', 'edit_blog', 'تحرير المدونة', '2023-02-12 21:11:59', '2023-02-12 21:11:59'),
(3586, 'sa', 'add_new', 'اضف جديد', '2023-02-12 21:11:59', '2023-02-12 21:11:59'),
(3587, 'sa', 'blog_updated_successfully', 'تم تحديث المدونة بنجاح', '2023-02-12 21:11:59', '2023-02-12 21:11:59'),
(3588, 'sa', 'blog_deleted_successfully', 'تم حذف المدونة بنجاح', '2023-02-12 21:11:59', '2023-02-12 21:11:59'),
(3589, 'sa', 'profile_pic_is_required', 'الملف الشخصي الموافقة المسبقة عن علم مطلوب', '2023-02-12 21:11:59', '2023-02-12 21:11:59'),
(3590, 'sa', 'invalid_selection', 'اختيار غير صحيح', '2023-02-12 21:11:59', '2023-02-12 21:11:59'),
(3591, 'sa', 'profile_updated_successfully', 'تم تحديث الملف الشخصي بنجاح', '2023-02-12 21:11:59', '2023-02-12 21:11:59'),
(3592, 'sa', 'product_deleted_successfully', 'تم حذف المنتج بنجاح', '2023-02-12 21:11:59', '2023-02-12 21:11:59'),
(3593, 'sa', 'button', 'زر', '2023-02-12 21:11:59', '2023-02-12 21:11:59'),
(3594, 'sa', 'select_option', 'حدد خيار', '2023-02-12 21:11:59', '2023-02-12 21:11:59'),
(3595, 'sa', 'latest_blogs', 'أحدث المدونات', '2023-02-12 21:11:59', '2023-02-12 21:11:59'),
(3596, 'sa', 'featured_blogs', 'مدونات مميزة', '2023-02-12 21:11:59', '2023-02-12 21:11:59'),
(3597, 'sa', 'category_wise', 'فئة الحكمة', '2023-02-12 21:11:59', '2023-02-12 21:11:59'),
(3598, 'sa', 'select_category', 'اختر الفئة', '2023-02-12 21:11:59', '2023-02-12 21:11:59'),
(3599, 'sa', 'number_of_blogs', 'عدد المدونات', '2023-02-12 21:11:59', '2023-02-12 21:11:59'),
(3600, 'sa', 'title_is_visible_in_homepage_transalate_to_another_language', 'التحويل إلى لغة أخرى', '2023-02-12 21:11:59', '2023-02-12 21:11:59'),
(3601, 'sa', 'title_color', 'لون العنوان', '2023-02-12 21:15:28', '2023-02-12 21:15:28'),
(3602, 'sa', 'button_title', 'عنوان الزر', '2023-02-12 21:15:28', '2023-02-12 21:15:28'),
(3603, 'sa', 'button_title_is_visible_in_homepage_transalate_to_another_language', 'التحويل إلى لغة أخرى', '2023-02-12 21:15:28', '2023-02-12 21:15:28'),
(3604, 'sa', 'button_color', 'لون الزر', '2023-02-12 21:15:28', '2023-02-12 21:15:28'),
(3605, 'sa', 'button_hover_color', 'لون تحوم الزر', '2023-02-12 21:15:28', '2023-02-12 21:15:28'),
(3606, 'sa', 'button_background_color', 'لون خلفية الزر', '2023-02-12 21:15:28', '2023-02-12 21:15:28'),
(3607, 'sa', 'button_background_hover_color', 'لون خلفية زر التمرير', '2023-02-12 21:15:28', '2023-02-12 21:15:28'),
(3608, 'sa', 'button_border', 'حد الزر', '2023-02-12 21:15:28', '2023-02-12 21:15:28'),
(3609, 'sa', 'button_border_color', 'لون حدود الزر', '2023-02-12 21:15:28', '2023-02-12 21:15:28'),
(3610, 'sa', 'button_border_hover_color', 'زر تحوم لون الحدود', '2023-02-12 21:15:28', '2023-02-12 21:15:28'),
(3611, 'sa', 'new_collection', 'مجموعة جديدة', '2023-02-12 21:15:28', '2023-02-12 21:15:28'),
(3612, 'sa', 'invalid_image', 'صورة غير صالحة', '2023-02-12 21:15:28', '2023-02-12 21:15:28'),
(3613, 'sa', 'collection_added_successfully', 'تمت إضافة المجموعة بنجاح', '2023-02-12 21:15:28', '2023-02-12 21:15:28'),
(3614, 'sa', 'remove_selection', 'إزالة التحديد', '2023-02-12 21:15:28', '2023-02-12 21:15:28'),
(3615, 'sa', 'add_product', 'أضف منتج', '2023-02-12 21:15:28', '2023-02-12 21:15:28'),
(3616, 'sa', 'collection', 'مجموعة', '2023-02-12 21:15:28', '2023-02-12 21:15:28'),
(3617, 'sa', 'select_products', 'حدد المنتجات', '2023-02-12 21:15:28', '2023-02-12 21:15:28'),
(3618, 'sa', 'are_you_sure_to_remove_this_product', 'هل أنت متأكد من إزالة هذا المنتج', '2023-02-12 21:15:28', '2023-02-12 21:15:28'),
(3619, 'sa', 'no_product_selected', 'لم يتم تحديد أي منتج', '2023-02-12 21:15:28', '2023-02-12 21:15:28'),
(3620, 'sa', 'products_added_successfully', 'تمت إضافة المنتجات بنجاح', '2023-02-12 21:15:28', '2023-02-12 21:15:28'),
(3621, 'sa', 'remove_product', 'إزالة المنتج', '2023-02-12 21:15:28', '2023-02-12 21:15:28'),
(3622, 'sa', 'custom_single_blog_page_style', 'نمط صفحة مدونة واحدة مخصصة', '2023-02-12 21:15:28', '2023-02-12 21:15:28'),
(3623, 'sa', 'switch_on_for_custom_single_blog_page_style', 'قم بالتبديل للحصول على نمط صفحة مدونة فردية مخصصة.', '2023-02-12 21:15:28', '2023-02-12 21:15:28'),
(3624, 'sa', 'layout', 'تَخطِيط', '2023-02-12 21:15:28', '2023-02-12 21:15:28'),
(3625, 'sa', 'choose_blog_single_page_layout_from_here_if_you_use_this_option_then_you_will_able_to_change_three_type_of_blog_single_page_layout__default_right_sidebar_layout_', 'إذا كنت تستخدم هذا الخيار ، فستتمكن من تغيير ثلاثة أنواع من تخطيط صفحة واحدة للمدونة (تخطيط الشريط الجانبي الأيمن الافتراضي).', '2023-02-12 21:15:28', '2023-02-12 21:15:28'),
(3626, 'sa', 'blog_post_title_position', 'وظيفة عنوان وظيفة المدونة', '2023-02-12 21:15:28', '2023-02-12 21:15:28'),
(3627, 'sa', 'control_blog_post_title_position_from_here', 'التحكم في موضع عنوان منشور المدونة من هنا.', '2023-02-12 21:15:28', '2023-02-12 21:15:28'),
(3628, 'sa', 'switch_on_to_display_author', 'قم بالتبديل إلى Display Author.', '2023-02-12 21:15:28', '2023-02-12 21:15:28'),
(3629, 'sa', 'switch_on_to_display_date', 'قم بالتبديل إلى تاريخ العرض.', '2023-02-12 21:15:28', '2023-02-12 21:15:28'),
(3630, 'sa', 'theme_option_saved', 'تم حفظ خيار الموضوع', '2023-02-12 21:15:28', '2023-02-12 21:15:28'),
(3631, 'sa', 'tl_commerce__manage_widgets', 'إدارة الحاجيات', '2023-02-12 21:15:28', '2023-02-12 21:15:28'),
(3632, 'sa', 'available_widgets', 'الحاجيات المتاحة', '2023-02-12 21:15:28', '2023-02-12 21:15:28'),
(3633, 'sa', 'add_widget', 'إضافة القطعة', '2023-02-12 21:15:28', '2023-02-12 21:15:28'),
(3634, 'sa', 'adding_widget_to_sidebar_failed', 'فشلت إضافة القطعة إلى الشريط الجانبي', '2023-02-12 21:15:28', '2023-02-12 21:15:28'),
(3635, 'sa', 'sidebar_widget_opening_failed', 'فشل فتح أداة الشريط الجانبي', '2023-02-12 21:15:28', '2023-02-12 21:15:28'),
(3636, 'sa', 'widget_added_to_sidebar_failed', 'فشل إضافة عنصر واجهة المستخدم إلى الشريط الجانبي', '2023-02-12 21:15:28', '2023-02-12 21:15:28'),
(3637, 'sa', 'widget_form_submit_failed_failed', 'فشل إرسال نموذج عنصر واجهة المستخدم', '2023-02-12 21:15:28', '2023-02-12 21:15:28'),
(3638, 'sa', 'sidebar_updated', 'تم تحديث الشريط الجانبي', '2023-02-12 21:15:28', '2023-02-12 21:15:28'),
(3639, 'sa', 'widget_title', 'عنوان الأداة', '2023-02-12 21:15:28', '2023-02-12 21:15:28'),
(3640, 'sa', 'number_of_recent_blog', 'عدد المدونة الأخيرة', '2023-02-12 21:15:28', '2023-02-12 21:15:28'),
(3641, 'sa', 'done', 'منتهي', '2023-02-12 21:15:28', '2023-02-12 21:15:28'),
(3642, 'sa', 'widget_form_saved', 'تم حفظ نموذج القطعة', '2023-02-12 21:15:28', '2023-02-12 21:15:28'),
(3643, 'sa', 'number_of_featured_blog', 'عدد المدونات المميزة', '2023-02-12 21:15:28', '2023-02-12 21:15:28'),
(3644, 'sa', 'blog_featured_status_changed_successfully', 'تم تغيير حالة ظهور المدونة بنجاح', '2023-02-12 21:15:28', '2023-02-12 21:15:28'),
(3645, 'sa', 'blog_draft_saved', 'تم حفظ مسودة المدونة', '2023-02-12 21:15:28', '2023-02-12 21:15:28'),
(3646, 'sa', 'tl_commerce__tag', 'بطاقة شعار', '2023-02-12 21:15:28', '2023-02-12 21:15:28'),
(3647, 'sa', 'add_tag', 'إضافة علامة', '2023-02-12 21:15:28', '2023-02-12 21:15:28'),
(3648, 'sa', 'this_tag_name_or_slug_is_already_available_please_insert_another', 'اسم العلامة أو Slug هذا متاح بالفعل الرجاء إدخال آخر', '2023-02-12 21:15:28', '2023-02-12 21:15:28'),
(3649, 'sa', 'site_seo__settings', 'إعدادات سيو الموقع', '2023-02-12 21:15:28', '2023-02-12 21:15:28'),
(3650, 'sa', 'site_title', 'عنوان الموقع', '2023-02-12 21:15:28', '2023-02-12 21:15:28'),
(3651, 'sa', 'meta_keywords', 'كلمات دلالية', '2023-02-12 21:15:28', '2023-02-12 21:15:28'),
(3652, 'sa', 'seo_update_successfully', 'تم تحديث SEO بنجاح', '2023-02-12 21:15:28', '2023-02-12 21:15:28'),
(3653, 'sa', 'no', 'لا.', '2023-02-12 21:15:28', '2023-02-12 21:15:28'),
(3654, 'sa', 'template', 'نموذج', '2023-02-12 21:15:28', '2023-02-12 21:15:28'),
(3655, 'sa', 'details', 'تفاصيل', '2023-02-12 21:15:28', '2023-02-12 21:15:28'),
(3656, 'sa', 'enter_email_subject', 'أدخل موضوع البريد الإلكتروني', '2023-02-12 21:15:28', '2023-02-12 21:15:28'),
(3657, 'sa', 'variables', 'المتغيرات', '2023-02-12 21:15:28', '2023-02-12 21:15:28'),
(3658, 'sa', 'smtp_configuration', 'تكوين SMTP', '2023-02-12 21:15:28', '2023-02-12 21:15:28'),
(3659, 'sa', 'email_configuration', 'تكوين البريد الإلكتروني', '2023-02-12 21:15:28', '2023-02-12 21:15:28'),
(3660, 'sa', 'type', 'يكتب', '2023-02-12 21:15:28', '2023-02-12 21:15:28'),
(3661, 'sa', 'smtp', 'بروتوكول SMTP', '2023-02-12 21:15:28', '2023-02-12 21:15:28'),
(3662, 'sa', 'sendmail', 'ارسل بريد', '2023-02-12 21:15:28', '2023-02-12 21:15:28'),
(3663, 'sa', 'mailgun', 'Mailgun', '2023-02-12 21:15:28', '2023-02-12 21:15:28'),
(3664, 'sa', 'mail_host', 'مضيف البريد', '2023-02-12 21:15:28', '2023-02-12 21:15:28'),
(3665, 'sa', 'mail_port', 'منفذ البريد', '2023-02-12 21:15:28', '2023-02-12 21:15:28'),
(3666, 'sa', 'mail_username', 'اسم المستخدم البريد', '2023-02-12 21:15:28', '2023-02-12 21:15:28'),
(3667, 'sa', 'mail_password', 'كلمة المرور البريدية', '2023-02-12 21:15:28', '2023-02-12 21:15:28'),
(3668, 'sa', 'mail_encryption', 'تشفير البريد', '2023-02-12 21:15:28', '2023-02-12 21:15:28'),
(3669, 'sa', 'mail_from_address', 'البريد من العنوان', '2023-02-12 21:15:28', '2023-02-12 21:15:28'),
(3670, 'sa', 'mail_from_name', 'البريد من الاسم', '2023-02-12 21:15:28', '2023-02-12 21:15:28'),
(3671, 'sa', 'mailgun_domain', 'المجال الرئيسي', '2023-02-12 21:15:28', '2023-02-12 21:15:28'),
(3672, 'sa', 'mailgun_secret', 'سر البريد', '2023-02-12 21:15:28', '2023-02-12 21:15:28'),
(3673, 'sa', 'send_test_mail', 'إرسال بريد تجريبي', '2023-02-12 21:15:28', '2023-02-12 21:15:28'),
(3674, 'sa', 'subject', 'موضوع', '2023-02-12 21:15:28', '2023-02-12 21:15:28'),
(3675, 'sa', 'message', 'رسالة', '2023-02-12 21:15:28', '2023-02-12 21:15:28'),
(3676, 'sa', 'send', 'يرسل', '2023-02-12 21:15:28', '2023-02-12 21:15:28'),
(3677, 'sa', 'users_login_activity', 'نشاط تسجيل دخول المستخدمين', '2023-02-12 21:15:28', '2023-02-12 21:15:28'),
(3678, 'sa', 'user', 'مستخدم', '2023-02-12 21:15:28', '2023-02-12 21:15:28'),
(3679, 'sa', 'login_at', 'تسجيل الدخول في', '2023-02-12 21:15:28', '2023-02-12 21:15:28'),
(3680, 'sa', 'logout_at', 'تسجيل الخروج في', '2023-02-12 21:15:28', '2023-02-12 21:15:28'),
(3681, 'sa', 'ip', 'IP', '2023-02-12 21:15:28', '2023-02-12 21:15:28'),
(3682, 'sa', 'operating_system', 'نظام التشغيل', '2023-02-12 21:15:28', '2023-02-12 21:15:28'),
(3683, 'sa', 'browser', 'المستعرض', '2023-02-12 21:15:28', '2023-02-12 21:15:28'),
(3684, 'sa', '________________________bulk_action_', 'العمل الجماعي', '2023-02-12 21:15:28', '2023-02-12 21:15:28'),
(3685, 'sa', '________________________delete_selection_', 'حذف التحديد', '2023-02-12 21:15:28', '2023-02-12 21:15:28'),
(3686, 'sa', '________________________apply_', 'يتقدم', '2023-02-12 21:15:28', '2023-02-12 21:15:28'),
(3687, 'sa', '________________________________________no_item_selected_', 'لم يتم تحديد أي عنصر', '2023-02-12 21:15:28', '2023-02-12 21:15:28'),
(3688, 'sa', '________________________________no_action_selected_', 'لم يتم تحديد أي إجراء', '2023-02-12 21:15:28', '2023-02-12 21:15:28'),
(3689, 'sa', 'currency_updated_successfully', 'تم تحديث العملة بنجاح', '2023-02-12 21:15:28', '2023-02-12 21:15:28'),
(3690, 'sa', 'brand_featured_status_updated_successfully', 'تم تحديث حالة العلامة التجارية المميزة بنجاح', '2023-02-12 21:15:28', '2023-02-12 21:15:28'),
(3691, 'sa', 'brand_featured_status_update_failed', 'فشل تحديث الحالة المميزة للعلامة التجارية', '2023-02-12 21:15:28', '2023-02-12 21:15:28'),
(3692, 'sa', 'brand_status_updated_successfully', 'تم تحديث حالة العلامة التجارية بنجاح', '2023-02-12 21:15:28', '2023-02-12 21:15:28'),
(3693, 'sa', 'brand_status_update_failed', 'فشل تحديث حالة العلامة التجارية', '2023-02-12 21:15:28', '2023-02-12 21:15:28'),
(3694, 'sa', 'easy_return_available', 'عائد سهل متاح', '2023-02-12 21:15:28', '2023-02-12 21:15:28'),
(3695, 'sa', 'edit_collection', 'تحرير المجموعة', '2023-02-12 21:15:28', '2023-02-12 21:15:28'),
(3696, 'sa', 'product_remove_successfully', 'تمت إزالة المنتج بنجاح', '2023-02-12 21:15:28', '2023-02-12 21:15:28'),
(3697, 'sa', 'select_collection', 'حدد المجموعة', '2023-02-12 21:15:28', '2023-02-12 21:15:28'),
(3698, 'sa', 'collection_updated_successfully', 'تم تحديث المجموعة بنجاح', '2023-02-12 21:15:28', '2023-02-12 21:15:28'),
(3699, 'sa', 'successfully_rearranging', 'إعادة الترتيب بنجاح', '2023-02-12 21:15:28', '2023-02-12 21:15:28'),
(3700, 'sa', 'new_arrival', 'قادم جديد', '2023-02-12 21:15:28', '2023-02-12 21:15:28'),
(3701, 'sa', 'featured_products', 'منتجات مميزة', '2023-02-12 21:15:28', '2023-02-12 21:15:28'),
(3702, 'sa', 'top_selling', 'الأكثر مبيعا', '2023-02-12 21:15:28', '2023-02-12 21:15:28'),
(3703, 'sa', 'top_reviewed', 'أعلى تقييم', '2023-02-12 21:15:28', '2023-02-12 21:15:28'),
(3704, 'sa', 'select_layouts', 'حدد التخطيطات', '2023-02-12 21:15:28', '2023-02-12 21:15:28'),
(3705, 'sa', 'add_new_flash_deal', 'إضافة عرض فلاش جديد', '2023-02-12 21:15:28', '2023-02-12 21:15:28'),
(3706, 'sa', 'start_date', 'تاريخ البدء', '2023-02-12 21:15:28', '2023-02-12 21:15:28'),
(3707, 'sa', 'expiry_date', 'تاريخ الانتهاء', '2023-02-12 21:15:28', '2023-02-12 21:15:28'),
(3708, 'sa', 'new_deal', 'صفقة جديدة', '2023-02-12 21:15:28', '2023-02-12 21:15:28'),
(3709, 'sa', 'text_color', 'لون الخط', '2023-02-12 21:15:28', '2023-02-12 21:15:28'),
(3710, 'sa', 'banner', 'لافتة', '2023-02-12 21:15:28', '2023-02-12 21:15:28'),
(3711, 'sa', 'deal_title_is_required', 'مطلوب عنوان الصفقة', '2023-02-12 21:15:28', '2023-02-12 21:15:28'),
(3712, 'sa', 'permalink_is_already_taken', 'الرابط الثابت مأخوذ بالفعل', '2023-02-12 21:15:28', '2023-02-12 21:15:28'),
(3713, 'sa', 'new_deal_added_successfully', 'تمت إضافة صفقة جديدة بنجاح', '2023-02-12 21:15:28', '2023-02-12 21:15:28'),
(3714, 'sa', 'deals_products', 'صفقات المنتجات', '2023-02-12 21:15:28', '2023-02-12 21:15:28'),
(3715, 'sa', 'to', 'ل', '2023-02-12 21:15:28', '2023-02-12 21:15:28'),
(3716, 'sa', 'discount_type', 'نوع الخصم', '2023-02-12 21:15:28', '2023-02-12 21:15:28'),
(3717, 'sa', 'update_product_discount', 'تحديث خصم المنتج', '2023-02-12 21:15:28', '2023-02-12 21:15:28'),
(3718, 'sa', 'product_details', 'تفاصيل المنتج', '2023-02-12 21:15:28', '2023-02-12 21:15:28'),
(3719, 'sa', 'select_flash_deal', 'حدد عرض فلاش', '2023-02-12 21:15:28', '2023-02-12 21:15:28'),
(3720, 'sa', 'featured_product_image', 'صورة المنتج المميزة', '2023-02-12 21:15:28', '2023-02-12 21:15:28'),
(3721, 'sa', 'video_url', 'رابط الفيديو', '2023-02-12 21:15:28', '2023-02-12 21:15:28'),
(3722, 'sa', 'meta_title_is_visible_in_homepage_transalate_to_another_language', 'التحويل إلى لغة أخرى', '2023-02-12 21:15:28', '2023-02-12 21:15:28'),
(3723, 'sa', 'paragraph', 'فقرة', '2023-02-12 21:15:28', '2023-02-12 21:15:28'),
(3724, 'sa', 'paragraph_is_visible_in_homepage_transalate_to_another_language', 'التحويل إلى لغة أخرى', '2023-02-12 21:15:28', '2023-02-12 21:15:28'),
(3725, 'sa', 'play_button_color', 'تشغيل لون زر', '2023-02-12 21:15:28', '2023-02-12 21:15:28'),
(3726, 'sa', 'play_button_border_color', 'زر تشغيل لون الحدود', '2023-02-12 21:15:28', '2023-02-12 21:15:28'),
(3727, 'sa', 'password_is_required', 'كلمة المرور مطلوبة', '2023-02-12 21:15:28', '2023-02-12 21:15:28'),
(3728, 'sa', 'password_does_not_match', 'كلمة السر غير متطابقة', '2023-02-12 21:15:28', '2023-02-12 21:15:28'),
(3729, 'sa', 'phone_is_required', 'الهاتف مطلوب', '2023-02-12 21:15:28', '2023-02-12 21:15:28'),
(3730, 'sa', 'phone_is_already_used', 'الهاتف مستخدم بالفعل', '2023-02-12 21:15:28', '2023-02-12 21:15:28'),
(3731, 'sa', 'email_is_required', 'البريد الالكتروني مطلوب', '2023-02-12 21:15:28', '2023-02-12 21:15:28'),
(3732, 'sa', 'incorrect_email', 'غير صحيح البريد الإلكتروني', '2023-02-12 21:15:28', '2023-02-12 21:15:28'),
(3733, 'sa', 'email_is_already_used', 'تم استخدام الايميل مسبقا', '2023-02-12 21:15:28', '2023-02-12 21:15:28'),
(3734, 'sa', 'secret_login', 'تسجيل الدخول السري', '2023-02-12 21:15:28', '2023-02-12 21:15:28'),
(3735, 'sa', 'delete_customer', 'حذف العميل', '2023-02-12 21:15:28', '2023-02-12 21:15:28'),
(3736, 'sa', 'status_updated_successfully', 'تم تحديث الحالة بنجاح', '2023-02-12 21:15:28', '2023-02-12 21:15:28'),
(3737, 'sa', 'no_account_exists_with_this_email', 'لا يوجد حساب موجود مع هذا البريد الإلكتروني', '2023-02-12 21:15:28', '2023-02-12 21:15:28'),
(3738, 'sa', 'image_size_is_too_large', 'حجم الصورة كبير جدًا', '2023-02-12 21:15:28', '2023-02-12 21:15:28'),
(3739, 'sa', 'invalid_image_format', 'تنسيق الصورة غير صالح', '2023-02-12 21:15:28', '2023-02-12 21:15:28'),
(3740, 'sa', 'address_is_required', 'العنوان مطلوب', '2023-02-12 21:15:28', '2023-02-12 21:15:28'),
(3741, 'sa', 'country_is_required', 'الدولة مطلوبة', '2023-02-12 21:15:28', '2023-02-12 21:15:28'),
(3742, 'sa', 'state_is_required', 'الدولة مطلوبة', '2023-02-12 21:15:28', '2023-02-12 21:15:28'),
(3743, 'sa', 'city_is_required', 'المدينة مطلوبة', '2023-02-12 21:15:28', '2023-02-12 21:15:28'),
(3744, 'sa', 'country_is_invalid', 'البلد غير صالح', '2023-02-12 21:15:28', '2023-02-12 21:15:28'),
(3745, 'sa', 'state_is_invalid', 'الدولة غير صالحة', '2023-02-12 21:15:28', '2023-02-12 21:15:28'),
(3746, 'sa', 'city_is_invalid', 'المدينة غير صالحة', '2023-02-12 21:15:28', '2023-02-12 21:15:28'),
(3747, 'sa', 'postal_code_is_required', 'الرمز البريدي مطلوب', '2023-02-12 21:15:28', '2023-02-12 21:15:28'),
(3748, 'sa', 'products_removed_successfully', 'تمت إزالة المنتجات بنجاح', '2023-02-12 21:15:28', '2023-02-12 21:15:28'),
(3749, 'sa', 'edit_deal', 'تحرير الصفقة', '2023-02-12 21:15:28', '2023-02-12 21:15:28'),
(3750, 'sa', 'deal_updated_successfully', 'تم تحديث الصفقة بنجاح', '2023-02-12 21:15:28', '2023-02-12 21:15:28'),
(3751, 'sa', 'attribute_deleted_failed', 'فشل حذف السمة', '2023-02-12 21:15:28', '2023-02-12 21:15:28'),
(3752, 'sa', 'attribute_value_delete_successfully', 'تم حذف قيمة السمة بنجاح', '2023-02-12 21:15:28', '2023-02-12 21:15:28'),
(3753, 'sa', 'attribute_deleted_successfully', 'تم حذف السمة بنجاح', '2023-02-12 21:15:28', '2023-02-12 21:15:28'),
(3754, 'sa', 'category_featured_status_updated_successfully', 'تم تحديث الحالة المميزة للفئة بنجاح', '2023-02-12 21:15:28', '2023-02-12 21:15:28'),
(3755, 'sa', 'category_featured_status_update_failed', 'فشل تحديث الحالة المميزة للفئة', '2023-02-12 21:15:28', '2023-02-12 21:15:28'),
(3756, 'sa', 'category_status_update_failed', 'فشل تحديث حالة الفئة', '2023-02-12 21:15:28', '2023-02-12 21:15:28'),
(3757, 'sa', 'share_options', 'مشاركة الخيارات', '2023-02-12 21:15:28', '2023-02-12 21:15:28'),
(3758, 'sa', 'tl_commerce__edit_blog_category', 'تحرير فئة المدونة', '2023-02-12 21:15:28', '2023-02-12 21:15:28'),
(3759, 'sa', 'edit_blog_category', 'تحرير فئة المدونة', '2023-02-12 21:15:28', '2023-02-12 21:15:28'),
(3760, 'sa', 'blog_category_updated_successfully', 'تم تحديث فئة المدونة بنجاح', '2023-02-12 21:15:28', '2023-02-12 21:15:28'),
(3761, 'sa', 'something_went_wrong', 'هناك خطأ ما', '2023-02-12 21:15:28', '2023-02-12 21:15:28'),
(3762, 'sa', 'tl_commerce__edit_tag', 'تحرير العلامة', '2023-02-12 21:15:28', '2023-02-12 21:15:28'),
(3763, 'sa', 'please_write_the_tag_name_under_225_words', 'الرجاء كتابة اسم العلامة تحت 225 كلمة', '2023-02-12 21:15:28', '2023-02-12 21:15:28'),
(3764, 'sa', 'tag_updated_successfully', 'تم تحديث العلامة بنجاح', '2023-02-12 21:15:28', '2023-02-12 21:15:28'),
(3765, 'sa', 'tag_not_found', 'العلامة غير موجودة', '2023-02-12 21:15:28', '2023-02-12 21:15:28'),
(3766, 'sa', 'mail', 'بريد', '2023-02-12 21:15:28', '2023-02-12 21:15:28'),
(3767, 'sa', 'social_links_', 'روابط اجتماعية:', '2023-02-12 21:15:28', '2023-02-12 21:15:28'),
(3768, 'sa', 'set_social_links_from_theme_options', 'تعيين الروابط الاجتماعية من خيارات الموضوع', '2023-02-12 21:15:28', '2023-02-12 21:15:28'),
(3769, 'sa', 'widget_input_fields_saving_failed', 'عنصر واجهة المستخدم حفظ حقول الإدخال فشل', '2023-02-12 21:15:28', '2023-02-12 21:15:28'),
(3770, 'sa', 'select_menu_group', 'حدد مجموعة القائمة', '2023-02-12 21:15:28', '2023-02-12 21:15:28'),
(3771, 'sa', 'newsletter_short_desc', 'نشرة إخبارية وصف مختصر', '2023-02-12 21:15:28', '2023-02-12 21:15:28'),
(3772, 'sa', 'widget_removed_from_sidebar', 'القطعة إزالتها من الشريط الجانبي', '2023-02-12 21:15:28', '2023-02-12 21:15:28'),
(3773, 'sa', 'tl_commerce__add_tag', 'إضافة علامة', '2023-02-12 21:15:28', '2023-02-12 21:15:28'),
(3774, 'sa', 'tl_commerce__blog_comment', 'تعليق المدونة', '2023-02-12 21:15:28', '2023-02-12 21:15:28'),
(3775, 'sa', 'approve', 'يعتمد', '2023-02-12 21:15:28', '2023-02-12 21:15:28'),
(3776, 'sa', 'spam', 'رسائل إلكترونية مزعجة', '2023-02-12 21:15:28', '2023-02-12 21:15:28'),
(3777, 'sa', 'trash', 'نفاية', '2023-02-12 21:15:28', '2023-02-12 21:15:28'),
(3778, 'sa', 'in_response_to', 'للإستجابة ل', '2023-02-12 21:15:28', '2023-02-12 21:15:28'),
(3779, 'sa', 'submitted_on', 'تم إرساله', '2023-02-12 21:15:28', '2023-02-12 21:15:28'),
(3780, 'sa', 'comment_delete_confirmation', 'تأكيد حذف التعليق', '2023-02-12 21:15:28', '2023-02-12 21:15:28'),
(3781, 'sa', 'are_you_sure_you_want_to_permanently_delete_this_comment', 'هل أنت متأكد من أنك تريد حذف هذا التعليق بشكل دائم', '2023-02-12 21:15:28', '2023-02-12 21:15:28'),
(3782, 'sa', 'bulk_action_confirmation', 'تأكيد الإجراء المجمع', '2023-02-12 21:15:28', '2023-02-12 21:15:28'),
(3783, 'sa', 'are_you_sure_you_want_to_take_this_action', 'هل أنت متأكد أنك تريد اتخاذ هذا الإجراء', '2023-02-12 21:15:28', '2023-02-12 21:15:28'),
(3784, 'sa', 'comment_reply', 'الرد على التعليق', '2023-02-12 21:15:28', '2023-02-12 21:15:28'),
(3785, 'sa', 'reply', 'رد', '2023-02-12 21:15:28', '2023-02-12 21:15:28'),
(3786, 'sa', 'mark_as_spam', 'علامة كدعاية', '2023-02-12 21:15:28', '2023-02-12 21:15:28'),
(3787, 'sa', 'unapprove', 'غير موافق', '2023-02-12 21:15:28', '2023-02-12 21:15:28'),
(3788, 'sa', 'not_spam', 'ليس بريدا موذيا', '2023-02-12 21:15:28', '2023-02-12 21:15:28'),
(3789, 'sa', 'delete_permanetly', 'حذف نهائيًا', '2023-02-12 21:15:28', '2023-02-12 21:15:28'),
(3790, 'sa', 'restore', 'يعيد', '2023-02-12 21:15:28', '2023-02-12 21:15:28'),
(3791, 'sa', 'delete_all', 'حذف الكل', '2023-02-12 21:15:28', '2023-02-12 21:15:28'),
(3792, 'sa', 'tl_commerce__page', 'صفحة', '2023-02-12 21:15:28', '2023-02-12 21:15:28'),
(3793, 'sa', 'add_page', 'إضافة صفحة', '2023-02-12 21:15:28', '2023-02-12 21:15:28'),
(3794, 'sa', 'tl_commerce__add_page', 'إضافة صفحة', '2023-02-12 21:15:28', '2023-02-12 21:15:28'),
(3795, 'sa', 'page_title', 'عنوان الصفحة', '2023-02-12 21:15:28', '2023-02-12 21:15:28'),
(3796, 'sa', 'add_title', 'أضف عنوانا', '2023-02-12 21:15:28', '2023-02-12 21:15:28'),
(3797, 'sa', 'page_attributes', 'سمات الصفحة', '2023-02-12 21:15:28', '2023-02-12 21:15:28'),
(3798, 'sa', 'parents', 'آباء', '2023-02-12 21:15:28', '2023-02-12 21:15:28'),
(3799, 'sa', 'select_a_parent_page', 'حدد الصفحة الرئيسية', '2023-02-12 21:15:28', '2023-02-12 21:15:28'),
(3800, 'sa', 'featured_image', 'صورة مميزة', '2023-02-12 21:15:29', '2023-02-12 21:15:29'),
(3801, 'sa', 'create_or_manage_zone', 'إنشاء أو إدارة المنطقة', '2023-02-12 21:16:52', '2023-02-12 21:16:52'),
(3802, 'sa', 'no_shipping_zone_found', 'لم يتم العثور على منطقة شحن', '2023-02-12 21:16:52', '2023-02-12 21:16:52'),
(3803, 'sa', 'add_or_manage_shipping_zone', 'إضافة أو إدارة منطقة الشحن', '2023-02-12 21:16:52', '2023-02-12 21:16:52'),
(3804, 'sa', 'refunded', 'معاد', '2023-02-12 21:16:52', '2023-02-12 21:16:52'),
(3805, 'sa', 'return_status', 'حالة العودة', '2023-02-12 21:16:52', '2023-02-12 21:16:52'),
(3806, 'sa', 'product_received', 'تم استلام المنتج', '2023-02-12 21:16:52', '2023-02-12 21:16:52'),
(3807, 'sa', 'refund_code', 'كود الاسترداد', '2023-02-12 21:16:52', '2023-02-12 21:16:52'),
(3808, 'sa', 'price', 'سعر', '2023-02-12 21:16:52', '2023-02-12 21:16:52'),
(3809, 'sa', 'quick_action', 'عمل سريع', '2023-02-12 21:16:52', '2023-02-12 21:16:52'),
(3810, 'sa', 'refund_request_information', 'معلومات طلب الاسترداد', '2023-02-12 21:16:52', '2023-02-12 21:16:52'),
(3811, 'sa', 'details_not_found', 'التفاصيل غير موجودة', '2023-02-12 21:16:52', '2023-02-12 21:16:52'),
(3812, 'sa', 'reason', 'سبب', '2023-02-12 21:16:52', '2023-02-12 21:16:52'),
(3813, 'sa', 'new_refund_reasons', 'أسباب رد الأموال الجديدة', '2023-02-12 21:16:52', '2023-02-12 21:16:52'),
(3814, 'sa', 'installupdate_theme', 'تثبيت / تحديث الموضوع', '2023-02-12 21:16:52', '2023-02-12 21:16:52'),
(3815, 'sa', 'by', 'بواسطة:', '2023-02-12 21:16:52', '2023-02-12 21:16:52'),
(3816, 'sa', 'version', 'إصدار:', '2023-02-12 21:16:52', '2023-02-12 21:16:52'),
(3817, 'sa', 'remove_confirmation', 'إزالة التأكيد', '2023-02-12 21:16:52', '2023-02-12 21:16:52'),
(3818, 'sa', 'are_you_sure_to_remove_this_theme', 'هل أنت متأكد من إزالة هذا الموضوع', '2023-02-12 21:16:52', '2023-02-12 21:16:52'),
(3819, 'sa', 'remove', 'يزيل', '2023-02-12 21:16:52', '2023-02-12 21:16:52'),
(3820, 'sa', 'activate_confirmation', 'تفعيل التأكيد', '2023-02-12 21:16:52', '2023-02-12 21:16:52'),
(3821, 'sa', 'are_you_sure_to_active_this_theme', 'هل أنت متأكد من تنشيط هذا الموضوع', '2023-02-12 21:16:52', '2023-02-12 21:16:52'),
(3822, 'sa', 'activate', 'تفعيل', '2023-02-12 21:16:52', '2023-02-12 21:16:52'),
(3823, 'sa', 'theme_primary_color', 'اللون الأساسي للسمة', '2023-02-12 21:16:52', '2023-02-12 21:16:52'),
(3824, 'sa', 'set_theme_primary_color', 'تعيين اللون الأساسي للسمة', '2023-02-12 21:16:52', '2023-02-12 21:16:52'),
(3825, 'sa', 'these_settings_control_the_typography_for_body', 'تتحكم هذه الإعدادات في طباعة الجسم.', '2023-02-12 21:16:52', '2023-02-12 21:16:52'),
(3826, 'sa', 'font_family', 'خط العائلة', '2023-02-12 21:16:52', '2023-02-12 21:16:52'),
(3827, 'sa', 'select__fonts', 'حدد الخطوط', '2023-02-12 21:16:52', '2023-02-12 21:16:52'),
(3828, 'sa', 'custom_font_1', 'الخط المخصص 1', '2023-02-12 21:16:52', '2023-02-12 21:16:52'),
(3829, 'sa', 'custom_font_2', 'الخط المخصص 2', '2023-02-12 21:16:52', '2023-02-12 21:16:52'),
(3830, 'sa', 'google_web_fonts', 'خطوط الويب من Google', '2023-02-12 21:16:52', '2023-02-12 21:16:52'),
(3831, 'sa', 'font_weight__style', 'وزن الخط ونمطه', '2023-02-12 21:16:52', '2023-02-12 21:16:52'),
(3832, 'sa', 'font_subsets', 'مجموعات الخطوط الفرعية', '2023-02-12 21:16:52', '2023-02-12 21:16:52'),
(3833, 'sa', 'text_align', 'محاذاة النص', '2023-02-12 21:16:52', '2023-02-12 21:16:52'),
(3834, 'sa', 'text_transform', 'تحويل النص', '2023-02-12 21:16:52', '2023-02-12 21:16:52'),
(3835, 'sa', 'font_size', 'حجم الخط', '2023-02-12 21:16:52', '2023-02-12 21:16:52'),
(3836, 'sa', 'size', 'مقاس', '2023-02-12 21:16:52', '2023-02-12 21:16:52'),
(3837, 'sa', 'line_height', 'ارتفاع خط', '2023-02-12 21:16:52', '2023-02-12 21:16:52'),
(3838, 'sa', 'word_spacing', 'تباعد الكلمات', '2023-02-12 21:16:52', '2023-02-12 21:16:52'),
(3839, 'sa', 'letter_spacing', 'تباعد الأحرف', '2023-02-12 21:16:52', '2023-02-12 21:16:52'),
(3840, 'sa', 'the_quick_brown_fox_jumps_over_the_lazy_dog', 'الثعلب البني السريع يقفز فوق الكلب الكسول', '2023-02-12 21:16:52', '2023-02-12 21:16:52'),
(3841, 'sa', 'paragraph_typographyp', 'طباعة الفقرة (P)', '2023-02-12 21:16:52', '2023-02-12 21:16:52'),
(3842, 'sa', 'these_settings_control_the_typography_for_all_pparagraph', 'تتحكم هذه الإعدادات في الطباعة لجميع الفقرات (p).', '2023-02-12 21:16:52', '2023-02-12 21:16:52'),
(3843, 'sa', 'all_heading_typography', 'كل طباعة العنوان', '2023-02-12 21:16:52', '2023-02-12 21:16:52'),
(3844, 'sa', 'these_settings_control_the_typography_for_all_heading', 'تتحكم هذه الإعدادات في الطباعة لكل العناوين.', '2023-02-12 21:16:52', '2023-02-12 21:16:52'),
(3845, 'sa', 'h1_heading_typography', '(H1) طباعة العنوان', '2023-02-12 21:16:52', '2023-02-12 21:16:52'),
(3846, 'sa', 'these_settings_control_the_typography_for_all_h1heading', 'تتحكم هذه الإعدادات في الطباعة لجميع عناوين (H1).', '2023-02-12 21:16:52', '2023-02-12 21:16:52'),
(3847, 'sa', 'h2_heading_typography', '(H2) طباعة العنوان', '2023-02-12 21:16:52', '2023-02-12 21:16:52'),
(3848, 'sa', 'these_settings_control_the_typography_for_all_h2heading', 'تتحكم هذه الإعدادات في الطباعة لجميع عناوين (H2).', '2023-02-12 21:16:52', '2023-02-12 21:16:52'),
(3849, 'sa', 'h3_heading_typography', '(H3) طباعة العنوان', '2023-02-12 21:16:52', '2023-02-12 21:16:52'),
(3850, 'sa', 'these_settings_control_the_typography_for_all_h3heading', 'تتحكم هذه الإعدادات في الطباعة لجميع عناوين (H3).', '2023-02-12 21:16:52', '2023-02-12 21:16:52'),
(3851, 'sa', 'h4_heading_typography', '(H4) طباعة العنوان', '2023-02-12 21:16:52', '2023-02-12 21:16:52');
INSERT INTO `tl_translations` (`id`, `lang`, `lang_key`, `lang_value`, `created_at`, `updated_at`) VALUES
(3852, 'sa', 'these_settings_control_the_typography_for_all_h4heading', 'تتحكم هذه الإعدادات في الطباعة لجميع عناوين (H4).', '2023-02-12 21:16:52', '2023-02-12 21:16:52'),
(3853, 'sa', 'h5_heading_typography', '(H5) طباعة العنوان', '2023-02-12 21:16:52', '2023-02-12 21:16:52'),
(3854, 'sa', 'these_settings_control_the_typography_for_all_h5heading', 'تتحكم هذه الإعدادات في الطباعة لجميع عناوين (H5).', '2023-02-12 21:16:52', '2023-02-12 21:16:52'),
(3855, 'sa', 'h6_heading_typography', '(H6) طباعة العنوان', '2023-02-12 21:16:52', '2023-02-12 21:16:52'),
(3856, 'sa', 'these_settings_control_the_typography_for_all_h6heading', 'تتحكم هذه الإعدادات في الطباعة لجميع عناوين (H6).', '2023-02-12 21:16:52', '2023-02-12 21:16:52'),
(3857, 'sa', 'these_settings_control_the_typography_for_menu', 'تتحكم هذه الإعدادات في طباعة القائمة.', '2023-02-12 21:16:52', '2023-02-12 21:16:52'),
(3858, 'sa', 'submenu_typography', 'طباعة القائمة الفرعية', '2023-02-12 21:16:52', '2023-02-12 21:16:52'),
(3859, 'sa', 'these_settings_control_the_typography_for_submenu', 'تتحكم هذه الإعدادات في طباعة القائمة الفرعية.', '2023-02-12 21:16:52', '2023-02-12 21:16:52'),
(3860, 'sa', 'these_settings_control_the_typography_for_button', 'تتحكم هذه الإعدادات في طباعة الزر.', '2023-02-12 21:16:52', '2023-02-12 21:16:52'),
(3861, 'sa', 'after_uploading_your_fonts_you_should_select_font_family_customfont1customfont2_from_dropdown_list_in_bodyparagraphheadingsmenublog_typography_section', 'بعد تحميل الخطوط الخاصة بك ، يجب عليك تحديد عائلة الخطوط (custom-font-1 / custom-font-2 من القائمة المنسدلة في (Body / Paragraph / Headings / Menu / Blog) قسم الطباعة.', '2023-02-12 21:16:52', '2023-02-12 21:16:52'),
(3862, 'sa', 'custom_font1', 'خط مخصص 1', '2023-02-12 21:16:52', '2023-02-12 21:16:52'),
(3863, 'sa', 'please_enable_this_option_to_use_custom_font_1', 'يرجى تمكين هذا الخيار لاستخدام Custom Font 1.', '2023-02-12 21:16:52', '2023-02-12 21:16:52'),
(3864, 'sa', 'custom_font_1_woff', 'الخط المخصص 1', '2023-02-12 21:16:52', '2023-02-12 21:16:52'),
(3865, 'sa', 'uploade_file', 'رفع ملف', '2023-02-12 21:16:52', '2023-02-12 21:16:52'),
(3866, 'sa', 'custom_font_1_ttf', 'الخط المخصص 1 .ttf', '2023-02-12 21:16:52', '2023-02-12 21:16:52'),
(3867, 'sa', 'custom_font_1_eot', 'خط مخصص 1 .eot', '2023-02-12 21:16:52', '2023-02-12 21:16:52'),
(3868, 'sa', 'custom_font2', 'خط مخصص 2', '2023-02-12 21:16:52', '2023-02-12 21:16:52'),
(3869, 'sa', 'please_enable_this_option_to_use_custom_font_2', 'يرجى تمكين هذا الخيار لاستخدام Custom Font 2.', '2023-02-12 21:16:52', '2023-02-12 21:16:52'),
(3870, 'sa', 'custom_font_2_woff', 'الخط المخصص 2', '2023-02-12 21:16:52', '2023-02-12 21:16:52'),
(3871, 'sa', 'custom_font_2_ttf', 'الخط المخصص 2 .ttf', '2023-02-12 21:16:52', '2023-02-12 21:16:52'),
(3872, 'sa', 'custom_font_2_eot', 'الخط المخصص 2 .eot', '2023-02-12 21:16:52', '2023-02-12 21:16:52'),
(3873, 'sa', 'custom_header_style', 'نمط رأس مخصص', '2023-02-12 21:16:52', '2023-02-12 21:16:52'),
(3874, 'sa', 'switch_on_for_custom_header_style', 'قم بالتبديل للحصول على نمط رأس مخصص.', '2023-02-12 21:16:52', '2023-02-12 21:16:52'),
(3875, 'sa', 'header_bottom_email_text', 'نص عنوان البريد الإلكتروني السفلي', '2023-02-12 21:16:52', '2023-02-12 21:16:52'),
(3876, 'sa', 'set_header_bottom_email_text', 'تعيين نص عنوان البريد الإلكتروني السفلي.', '2023-02-12 21:16:52', '2023-02-12 21:16:52'),
(3877, 'sa', 'header_top_background_color', 'لون الخلفية في أعلى الرأس', '2023-02-12 21:16:52', '2023-02-12 21:16:52'),
(3878, 'sa', 'set_header_top_background_color', 'قم بتعيين لون خلفية الرأس العلوي.', '2023-02-12 21:16:52', '2023-02-12 21:16:52'),
(3879, 'sa', 'header_middle_background_color', 'لون الخلفية الأوسط للرأس', '2023-02-12 21:16:52', '2023-02-12 21:16:52'),
(3880, 'sa', 'set_header_middle_background_color', 'تعيين لون الخلفية الأوسط للرأس.', '2023-02-12 21:16:52', '2023-02-12 21:16:52'),
(3881, 'sa', 'header_bottom_background_color', 'لون الخلفية أسفل الرأس', '2023-02-12 21:16:52', '2023-02-12 21:16:52'),
(3882, 'sa', 'set_header_bottom_background_color', 'تعيين لون الخلفية أسفل الرأس.', '2023-02-12 21:16:52', '2023-02-12 21:16:52'),
(3883, 'sa', 'header_bottom_text_color', 'لون نص الرأس السفلي', '2023-02-12 21:16:52', '2023-02-12 21:16:52'),
(3884, 'sa', 'set_header_bottom_text_color', 'تعيين لون نص الرأس السفلي.', '2023-02-12 21:16:52', '2023-02-12 21:16:52'),
(3885, 'sa', 'sticky_header_background_color', 'لون خلفية الرأس اللاصق', '2023-02-12 21:16:52', '2023-02-12 21:16:52'),
(3886, 'sa', 'set_sticky_header_background_color', 'تعيين لون خلفية الرأس اللاصق.', '2023-02-12 21:16:52', '2023-02-12 21:16:52'),
(3887, 'sa', 'header_search_form_button_color', 'لون زر نموذج البحث في العنوان', '2023-02-12 21:16:52', '2023-02-12 21:16:52'),
(3888, 'sa', 'set_header_search_form_button_color', 'تعيين لون زر نموذج بحث العنوان', '2023-02-12 21:16:52', '2023-02-12 21:16:52'),
(3889, 'sa', 'header_search_form_button_hover_color', 'نموذج البحث عن رأس زر لون التمرير', '2023-02-12 21:16:52', '2023-02-12 21:16:52'),
(3890, 'sa', 'set_header_search_form_button_hover_color', 'تعيين لون تحويم زر نموذج البحث عن الرأس', '2023-02-12 21:16:52', '2023-02-12 21:16:52'),
(3891, 'sa', 'header_search_form_button_text_color', 'لون نص زر نموذج البحث في الرأس', '2023-02-12 21:16:52', '2023-02-12 21:16:52'),
(3892, 'sa', 'set_header_search_form_button_text_color', 'تعيين لون نص زر نموذج بحث العنوان', '2023-02-12 21:16:52', '2023-02-12 21:16:52'),
(3893, 'sa', 'header_search_form_button_hover_text_color', 'نموذج البحث عن رأس زر تحوم لون النص', '2023-02-12 21:16:52', '2023-02-12 21:16:52'),
(3894, 'sa', 'set_header_search_form_button_hover_text_color', 'تعيين لون نص زر نموذج البحث عن رأس التمرير', '2023-02-12 21:16:52', '2023-02-12 21:16:52'),
(3895, 'sa', 'header_icon_button_color', 'لون زر رمز الرأس', '2023-02-12 21:16:52', '2023-02-12 21:16:52'),
(3896, 'sa', 'set_header_icon_button_color', 'تعيين لون زر رمز الرأس', '2023-02-12 21:16:52', '2023-02-12 21:16:52'),
(3897, 'sa', 'header_icon_button_text_color', 'لون نص زر رمز الرأس', '2023-02-12 21:16:53', '2023-02-12 21:16:53'),
(3898, 'sa', 'set_header_icon_button_text_color', 'تعيين لون نص رمز رأس الصفحة', '2023-02-12 21:16:53', '2023-02-12 21:16:53'),
(3899, 'sa', 'header_icon_button_hover_color', 'لون التمرير فوق زر رمز الرأس', '2023-02-12 21:16:53', '2023-02-12 21:16:53'),
(3900, 'sa', 'set_header_icon_button_hover_color', 'تعيين لون التمرير على زر رمز الرأس', '2023-02-12 21:16:53', '2023-02-12 21:16:53'),
(3901, 'sa', 'header_icon_button_hover_text_color', 'لون النص تحوم فوق زر رمز الرأس', '2023-02-12 21:16:53', '2023-02-12 21:16:53'),
(3902, 'sa', 'set_header_icon_button_hover_text_color', 'تعيين لون النص رمز التمرير فوق الزر', '2023-02-12 21:16:53', '2023-02-12 21:16:53'),
(3903, 'sa', 'header_top_language_change_button_color', 'لون زر تغيير لغة الرأس العليا', '2023-02-12 21:16:53', '2023-02-12 21:16:53'),
(3904, 'sa', 'set_header_top_language_change_button_color', 'تعيين لون زر تغيير لغة الرأس العليا', '2023-02-12 21:16:53', '2023-02-12 21:16:53'),
(3905, 'sa', 'header_top_language_change_button_text_color', 'لون نص الزر تغيير لغة الرأس', '2023-02-12 21:16:53', '2023-02-12 21:16:53'),
(3906, 'sa', 'set_header_top_language_change_button_text_color', 'تعيين لون نص الزر تغيير لغة الرأس العليا', '2023-02-12 21:16:53', '2023-02-12 21:16:53'),
(3907, 'sa', 'header_top_language_change_button_hover_color', 'لون التحويم على زر تغيير لغة الرأس العليا', '2023-02-12 21:16:53', '2023-02-12 21:16:53'),
(3908, 'sa', 'set_header_top_language_change_button_hover_color', 'تعيين لون التحويم على زر تغيير لغة الرأس العليا', '2023-02-12 21:16:53', '2023-02-12 21:16:53'),
(3909, 'sa', 'header_top_language_change_button_hover_text_color', 'لون النص \"تغيير لغة الرأس\"', '2023-02-12 21:16:53', '2023-02-12 21:16:53'),
(3910, 'sa', 'set_header_top_language_change_button_hover_text_color', 'تعيين لون النص \"تغيير لغة الرأس العلوي\"', '2023-02-12 21:16:53', '2023-02-12 21:16:53'),
(3911, 'sa', 'custom_header_logo_style', 'نمط شعار رأس مخصص', '2023-02-12 21:16:53', '2023-02-12 21:16:53'),
(3912, 'sa', 'switch_on_for_custom_header_logo_style', 'قم بالتبديل للحصول على نمط شعار رأس مخصص.', '2023-02-12 21:16:53', '2023-02-12 21:16:53'),
(3913, 'sa', 'logo_dimensions_widthheight', 'أبعاد الشعار (العرض / الارتفاع).', '2023-02-12 21:16:53', '2023-02-12 21:16:53'),
(3914, 'sa', 'set_logo_dimensions_to_choose_width_height_and_unit', 'اضبط أبعاد الشعار لاختيار العرض والارتفاع والوحدة.', '2023-02-12 21:16:53', '2023-02-12 21:16:53'),
(3915, 'sa', 'logo_top_and_bottom_margin', 'الهامش العلوي والسفلي للشعار.', '2023-02-12 21:16:53', '2023-02-12 21:16:53'),
(3916, 'sa', 'set_logo_top_and_bottom_margin', 'تعيين الهامش العلوي والسفلي للشعار.', '2023-02-12 21:16:53', '2023-02-12 21:16:53'),
(3917, 'sa', 'sticky_logo_dimensions_widthheight', 'أبعاد الشعار اللاصقة (العرض / الارتفاع).', '2023-02-12 21:16:53', '2023-02-12 21:16:53'),
(3918, 'sa', 'set_sticky_logo_dimensions_to_choose_width_height_and_unit', 'عيّن أبعاد الشعار اللاصقة لاختيار العرض والارتفاع والوحدة.', '2023-02-12 21:16:53', '2023-02-12 21:16:53'),
(3919, 'sa', 'sticky_logo_top_and_bottom_margin', 'الشعار اللاصق العلوي والهامش السفلي.', '2023-02-12 21:16:53', '2023-02-12 21:16:53'),
(3920, 'sa', 'set_sticky_logo_top_and_bottom_margin', 'ضع شعار Sticky على الهامش العلوي والسفلي.', '2023-02-12 21:16:53', '2023-02-12 21:16:53'),
(3921, 'sa', 'custom_menu_style', 'نمط قائمة مخصص', '2023-02-12 21:16:53', '2023-02-12 21:16:53'),
(3922, 'sa', 'switch_on_for_custom_menu_style', 'قم بتشغيل نمط القائمة المخصص.', '2023-02-12 21:16:53', '2023-02-12 21:16:53'),
(3923, 'sa', 'menu_color', 'لون القائمة', '2023-02-12 21:16:53', '2023-02-12 21:16:53'),
(3924, 'sa', 'set_header_menu_color', 'تعيين لون قائمة الرأس.', '2023-02-12 21:16:53', '2023-02-12 21:16:53'),
(3925, 'sa', 'menu_hover_color', 'لون تحوم القائمة', '2023-02-12 21:16:53', '2023-02-12 21:16:53'),
(3926, 'sa', 'set_header_menu_hover_color', 'تعيين لون التمرير لقائمة الرأس.', '2023-02-12 21:16:53', '2023-02-12 21:16:53'),
(3927, 'sa', 'sub_menu_color', 'لون القائمة الفرعية', '2023-02-12 21:16:53', '2023-02-12 21:16:53'),
(3928, 'sa', 'set_header_sub_menu_color', 'تعيين لون القائمة الفرعية للرأس.', '2023-02-12 21:16:53', '2023-02-12 21:16:53'),
(3929, 'sa', 'sub_menu_hover_color', 'لون تحوم القائمة الفرعية', '2023-02-12 21:16:53', '2023-02-12 21:16:53'),
(3930, 'sa', 'set_header_sub_menu_hover_color', 'تعيين لون تحويم القائمة الفرعية للرأس.', '2023-02-12 21:16:53', '2023-02-12 21:16:53'),
(3931, 'sa', 'custom_blog_style', 'نمط مدونة مخصص', '2023-02-12 21:16:53', '2023-02-12 21:16:53'),
(3932, 'sa', 'switch_on_for_custom_blog_style', 'قم بالتبديل للحصول على نمط مدونة مخصص.', '2023-02-12 21:16:53', '2023-02-12 21:16:53'),
(3933, 'sa', 'choose_blog_layout_from_here_if_you_use_this_option_then_you_will_able_to_change_three_type_of_blog_layout__default_right_sidebar_layour_', 'إذا كنت تستخدم هذا الخيار ، فستتمكن من تغيير ثلاثة أنواع من تخطيط المدونة (Default Right Sidebar Layour).', '2023-02-12 21:16:53', '2023-02-12 21:16:53'),
(3934, 'sa', 'blog_column', 'عمود المدونة', '2023-02-12 21:16:53', '2023-02-12 21:16:53'),
(3935, 'sa', 'select_your_blog_post_column_from_here_if_you_use_this_option_then_you_will_able_to_select_three_type_of_blog_colum_layout__default_one_column_', 'إذا كنت تستخدم هذا الخيار ، فستتمكن من تحديد ثلاثة أنواع من تخطيط عمود المدونة (افتراضي عمود واحد).', '2023-02-12 21:16:53', '2023-02-12 21:16:53'),
(3936, 'sa', 'read_more_text_setting', 'قراءة المزيد Text Setting', '2023-02-12 21:16:53', '2023-02-12 21:16:53'),
(3937, 'sa', 'control_read_more_text_from_here', 'تحكم بقراءة المزيد من النص من هنا.', '2023-02-12 21:16:53', '2023-02-12 21:16:53'),
(3938, 'sa', 'read_more_text', 'قراءة المزيد Text', '2023-02-12 21:16:53', '2023-02-12 21:16:53'),
(3939, 'sa', 'set_read_moer_text_here_if_you_use_this_option_then_you_will_able_to_set_your_won_text', 'إذا كنت تستخدم هذا الخيار ، فستتمكن من تعيين نصك الفائز.', '2023-02-12 21:16:53', '2023-02-12 21:16:53'),
(3940, 'sa', 'blog_perpage_number', 'رقم المدونة لكل صفحة', '2023-02-12 21:16:53', '2023-02-12 21:16:53'),
(3941, 'sa', 'control_the_number_blogs_to_show_on_each_page__default_show_9_', 'التحكم في عدد المدونات التي سيتم عرضها في كل صفحة (العرض الافتراضي 9).', '2023-02-12 21:16:53', '2023-02-12 21:16:53'),
(3942, 'sa', 'blog_pagination_position', 'موقف ترقيم الصفحات في المدونة', '2023-02-12 21:16:53', '2023-02-12 21:16:53'),
(3943, 'sa', 'set_blog_pagination_position', 'تعيين موضع ترقيم صفحات المدونة.', '2023-02-12 21:16:53', '2023-02-12 21:16:53'),
(3944, 'sa', 'blog_pagination_color', 'لون ترقيم الصفحات في المدونة', '2023-02-12 21:16:53', '2023-02-12 21:16:53'),
(3945, 'sa', 'set_blog_pagination_color', 'تعيين لون ترقيم صفحات المدونة.', '2023-02-12 21:16:53', '2023-02-12 21:16:53'),
(3946, 'sa', 'blog_pagination_background_color', 'لون خلفية ترقيم الصفحات في المدونة', '2023-02-12 21:16:53', '2023-02-12 21:16:53'),
(3947, 'sa', 'set_blog_pagination_background_color', 'تعيين لون خلفية ترقيم الصفحات في المدونة.', '2023-02-12 21:16:53', '2023-02-12 21:16:53'),
(3948, 'sa', 'blog_pagination_border_color', 'مدونة ترقيم الصفحات لون الحدود', '2023-02-12 21:16:53', '2023-02-12 21:16:53'),
(3949, 'sa', 'set_blog_pagination_border_color', 'تعيين لون حدود ترقيم الصفحات في المدونة.', '2023-02-12 21:16:53', '2023-02-12 21:16:53'),
(3950, 'sa', 'blog_pagination_active_color', 'مدونة ترقيم الصفحات النشطة اللون', '2023-02-12 21:16:53', '2023-02-12 21:16:53'),
(3951, 'sa', 'set_blog_pagination_active_color', 'تعيين اللون النشط لصفحات المدونة.', '2023-02-12 21:16:53', '2023-02-12 21:16:53'),
(3952, 'sa', 'blog_pagination_active_background_color', 'مدونة ترقيم الصفحات النشطة لون الخلفية', '2023-02-12 21:16:53', '2023-02-12 21:16:53'),
(3953, 'sa', 'set_blog_pagination_active_background_color', 'تعيين لون الخلفية النشط لصفحات صفحات المدونة.', '2023-02-12 21:16:53', '2023-02-12 21:16:53'),
(3954, 'sa', 'blog_pagination_active_border_color', 'مدونة ترقيم الصفحات النشط لون الحدود', '2023-02-12 21:16:53', '2023-02-12 21:16:53'),
(3955, 'sa', 'set_blog_pagination_active_border_color', 'تعيين لون المدونة لصفحات الصفحات النشط للحدود.', '2023-02-12 21:16:53', '2023-02-12 21:16:53'),
(3956, 'sa', 'blog_pagination_hover_color', 'مدونة ترقيم الصفحات اللون', '2023-02-12 21:16:53', '2023-02-12 21:16:53'),
(3957, 'sa', 'set_blog_pagination_hover_color', 'قم بتعيين لون التمرير فوق ترقيم الصفحات في المدونة.', '2023-02-12 21:16:53', '2023-02-12 21:16:53'),
(3958, 'sa', 'blog_pagination_hover_background_color', 'لون خلفية ترقيم الصفحات في المدونة', '2023-02-12 21:16:53', '2023-02-12 21:16:53'),
(3959, 'sa', 'set_blog_pagination_hover_background_color', 'قم بتعيين لون خلفية التمرير فوق صفحات المدونة.', '2023-02-12 21:16:53', '2023-02-12 21:16:53'),
(3960, 'sa', 'blog_pagination_hover_border_color', 'مدونة ترقيم الصفحات تحوم لون الحدود', '2023-02-12 21:16:53', '2023-02-12 21:16:53'),
(3961, 'sa', 'set_blog_pagination_hover_border_color', 'قم بتعيين لون حدود ترقيم الصفحات في المدونة.', '2023-02-12 21:16:53', '2023-02-12 21:16:53'),
(3962, 'sa', 'custom_sidebar_style', 'نمط الشريط الجانبي المخصص', '2023-02-12 21:16:53', '2023-02-12 21:16:53'),
(3963, 'sa', 'switch_on_for_custom_sidebar_style', 'قم بالتبديل إلى نمط الشريط الجانبي المخصص.', '2023-02-12 21:16:53', '2023-02-12 21:16:53'),
(3964, 'sa', 'widgets_background_color', 'الحاجيات لون الخلفية', '2023-02-12 21:16:53', '2023-02-12 21:16:53'),
(3965, 'sa', 'box_shadow', 'مربع الظل', '2023-02-12 21:16:53', '2023-02-12 21:16:53'),
(3966, 'sa', 'offset_x', 'تعويض X', '2023-02-12 21:16:53', '2023-02-12 21:16:53'),
(3967, 'sa', 'offset_y', 'تعويض Y', '2023-02-12 21:16:53', '2023-02-12 21:16:53'),
(3968, 'sa', 'blur_radius', 'نصف القطر الضبابي', '2023-02-12 21:16:53', '2023-02-12 21:16:53'),
(3969, 'sa', 'spread_radius', 'انتشار الشعاع', '2023-02-12 21:16:53', '2023-02-12 21:16:53'),
(3970, 'sa', 'opcacity_11', 'العتامة .1-1', '2023-02-12 21:16:53', '2023-02-12 21:16:53'),
(3971, 'sa', 'shadow_color', 'لون الظل', '2023-02-12 21:16:53', '2023-02-12 21:16:53'),
(3972, 'sa', 'shadow_type', 'نوع الظل', '2023-02-12 21:16:53', '2023-02-12 21:16:53'),
(3973, 'sa', 'widget_margin', 'هامش القطعة', '2023-02-12 21:16:53', '2023-02-12 21:16:53'),
(3974, 'sa', 'widget_padding', 'القطعة الحشو', '2023-02-12 21:16:53', '2023-02-12 21:16:53'),
(3975, 'sa', 'widget_border', 'القطعة الحدود', '2023-02-12 21:16:53', '2023-02-12 21:16:53'),
(3976, 'sa', 'select_style', 'حدد النمط', '2023-02-12 21:16:53', '2023-02-12 21:16:53'),
(3977, 'sa', 'widget_title_margin', 'هامش عنوان القطعة', '2023-02-12 21:16:53', '2023-02-12 21:16:53'),
(3978, 'sa', 'widget_title_padding', 'القطعة عنوان الحشو', '2023-02-12 21:16:53', '2023-02-12 21:16:53'),
(3979, 'sa', 'widget_title_color', 'القطعة عنوان اللون', '2023-02-12 21:16:53', '2023-02-12 21:16:53'),
(3980, 'sa', 'set_widget_title_color', 'تعيين لون عنوان القطعة.', '2023-02-12 21:16:53', '2023-02-12 21:16:53'),
(3981, 'sa', 'widget_text_color', 'لون نص القطعة', '2023-02-12 21:16:53', '2023-02-12 21:16:53'),
(3982, 'sa', 'set_widget_text_color', 'تعيين لون نص القطعة.', '2023-02-12 21:16:53', '2023-02-12 21:16:53'),
(3983, 'sa', 'widget_anchor_color', 'القطعة مرساة اللون', '2023-02-12 21:16:53', '2023-02-12 21:16:53'),
(3984, 'sa', 'set_widget_anchor_color', 'تعيين لون مرساة القطعة.', '2023-02-12 21:16:53', '2023-02-12 21:16:53'),
(3985, 'sa', 'widget_anchor_hover_color', 'القطعة مرساة تحوم اللون', '2023-02-12 21:16:53', '2023-02-12 21:16:53'),
(3986, 'sa', 'set_widget_anchor_hover_color', 'تعيين لون القطعة مرساة تحوم.', '2023-02-12 21:16:53', '2023-02-12 21:16:53'),
(3987, 'sa', 'custom_404_style', 'نمط 404 مخصص', '2023-02-12 21:16:53', '2023-02-12 21:16:53'),
(3988, 'sa', 'switch_on_for_custom_404_style', 'قم بتشغيل النمط 404 المخصص.', '2023-02-12 21:16:53', '2023-02-12 21:16:53'),
(3989, 'sa', 'set_page_title', 'تعيين عنوان الصفحة', '2023-02-12 21:16:53', '2023-02-12 21:16:53'),
(3990, 'sa', '404_image', '404 صورة', '2023-02-12 21:16:53', '2023-02-12 21:16:53'),
(3991, 'sa', 'upload_your_site_404_image_for_header__recommendation_png_format_', 'قم بتحميل موقعك 404_image للرأس (توصية بتنسيق png).', '2023-02-12 21:16:53', '2023-02-12 21:16:53'),
(3992, 'sa', 'button_text', 'زر كتابة', '2023-02-12 21:16:53', '2023-02-12 21:16:53'),
(3993, 'sa', 'button_text_color', 'لون نص الزر', '2023-02-12 21:16:53', '2023-02-12 21:16:53'),
(3994, 'sa', 'button_hover_background_color', 'زر تحوم لون الخلفية', '2023-02-12 21:16:53', '2023-02-12 21:16:53'),
(3995, 'sa', 'button_hover_text_color', 'لون النص تحوم فوق الزر', '2023-02-12 21:16:53', '2023-02-12 21:16:53'),
(3996, 'sa', 'mailchimp_api_key', 'مفتاح واجهة برمجة تطبيقات Mailchimp', '2023-02-12 21:16:53', '2023-02-12 21:16:53'),
(3997, 'sa', 'set_mailchimp_api_key', 'تعيين مفتاح واجهة برمجة تطبيقات mailchimp', '2023-02-12 21:16:53', '2023-02-12 21:16:53'),
(3998, 'sa', 'mailchimp_list_id', 'معرف قائمة Mailchimp', '2023-02-12 21:16:53', '2023-02-12 21:16:53'),
(3999, 'sa', 'set_mailchimp_list_id', 'تعيين معرف قائمة mailchimp.', '2023-02-12 21:16:53', '2023-02-12 21:16:53'),
(4000, 'sa', 'custom_subscripton_style', 'نمط مخصص للاكتتاب', '2023-02-12 21:16:53', '2023-02-12 21:16:53'),
(4001, 'sa', 'switch_on_for_custom_subscripton_style', 'قم بالتبديل إلى نمط Subscripton المخصص.', '2023-02-12 21:18:10', '2023-02-12 21:18:10'),
(4002, 'sa', 'form_button_text', 'نص زر النموذج', '2023-02-12 21:18:10', '2023-02-12 21:18:10'),
(4003, 'sa', 'form_input_background_color', 'لون خلفية إدخال النموذج', '2023-02-12 21:18:10', '2023-02-12 21:18:10'),
(4004, 'sa', 'form_input_text_color', 'لون نص إدخال النموذج', '2023-02-12 21:18:10', '2023-02-12 21:18:10'),
(4005, 'sa', 'form_submit_button_color', 'نموذج إرسال لون الزر', '2023-02-12 21:18:10', '2023-02-12 21:18:10'),
(4006, 'sa', 'form_submit_button_background_color', 'نموذج إرسال لون خلفية الزر', '2023-02-12 21:18:10', '2023-02-12 21:18:10'),
(4007, 'sa', 'form_submit_button_hover_color', 'نموذج إرسال لون زر التمرير', '2023-02-12 21:18:10', '2023-02-12 21:18:10'),
(4008, 'sa', 'form_submit_button_hover_background_color', 'إرسال نموذج لون الخلفية', '2023-02-12 21:18:10', '2023-02-12 21:18:10'),
(4009, 'sa', 'social_profile_links', 'روابط الملف الشخصي الاجتماعية', '2023-02-12 21:18:10', '2023-02-12 21:18:10'),
(4010, 'sa', 'add_social_icon_and_url', 'أضف أيقونة اجتماعية و url.', '2023-02-12 21:18:10', '2023-02-12 21:18:10'),
(4011, 'sa', 'add_slide', 'أضف شريحة', '2023-02-12 21:18:10', '2023-02-12 21:18:10'),
(4012, 'sa', 'custom_social_style', 'النمط الاجتماعي المخصص', '2023-02-12 21:18:10', '2023-02-12 21:18:10'),
(4013, 'sa', 'set_custom_social_style', 'تعيين نمط اجتماعي مخصص.', '2023-02-12 21:18:10', '2023-02-12 21:18:10'),
(4014, 'sa', '_social_background_color', 'لون الخلفية الاجتماعية', '2023-02-12 21:18:10', '2023-02-12 21:18:10'),
(4015, 'sa', 'set__social_background_color', 'تعيين لون الخلفية الاجتماعية', '2023-02-12 21:18:10', '2023-02-12 21:18:10'),
(4016, 'sa', '_social_border_color', 'لون الحدود الاجتماعية', '2023-02-12 21:18:10', '2023-02-12 21:18:10'),
(4017, 'sa', 'set__social_border_color', 'تعيين لون الحدود الاجتماعية', '2023-02-12 21:18:10', '2023-02-12 21:18:10'),
(4018, 'sa', '_social_color', 'اللون الاجتماعي', '2023-02-12 21:18:10', '2023-02-12 21:18:10'),
(4019, 'sa', '_social_hover_color', 'لون التمرير الاجتماعي', '2023-02-12 21:18:10', '2023-02-12 21:18:10'),
(4020, 'sa', '_social_hover_border_color', 'لون حدود التحويم الاجتماعي', '2023-02-12 21:18:10', '2023-02-12 21:18:10'),
(4021, 'sa', 'social_hover_background_color', 'لون خلفية التمرير الاجتماعي', '2023-02-12 21:18:10', '2023-02-12 21:18:10'),
(4022, 'sa', 'custom_footer_style', 'نمط تذييل مخصص', '2023-02-12 21:18:10', '2023-02-12 21:18:10'),
(4023, 'sa', 'switch_on_for_custom_footer_style', 'قم بالتبديل للحصول على نمط تذييل مخصص.', '2023-02-12 21:18:10', '2023-02-12 21:18:10'),
(4024, 'sa', 'custom_footer_padding', 'مساحة تذييل مخصصة.', '2023-02-12 21:18:10', '2023-02-12 21:18:10'),
(4025, 'sa', 'set_footer_padding', 'تعيين مساحة التذييل.', '2023-02-12 21:18:10', '2023-02-12 21:18:10'),
(4026, 'sa', 'footer_background_color', 'لون خلفية التذييل', '2023-02-12 21:18:10', '2023-02-12 21:18:10'),
(4027, 'sa', 'set_background_color', 'تعيين لون الخلفية', '2023-02-12 21:18:10', '2023-02-12 21:18:10'),
(4028, 'sa', 'footer_text_color', 'لون نص التذييل', '2023-02-12 21:18:10', '2023-02-12 21:18:10'),
(4029, 'sa', 'set_text_color', 'تعيين لون النص', '2023-02-12 21:18:10', '2023-02-12 21:18:10'),
(4030, 'sa', 'footer_anchor_color', 'لون مرساة التذييل', '2023-02-12 21:18:10', '2023-02-12 21:18:10'),
(4031, 'sa', 'set_footer_anchor_color', 'تعيين لون ارتساء التذييل', '2023-02-12 21:18:10', '2023-02-12 21:18:10'),
(4032, 'sa', 'footer_anchor_hover_color', 'لون مرساة تذييل الصفحة', '2023-02-12 21:18:10', '2023-02-12 21:18:10'),
(4033, 'sa', 'set_footer_anchor_hover_color', 'تعيين لون تحوم التذييل', '2023-02-12 21:18:10', '2023-02-12 21:18:10'),
(4034, 'sa', 'css_code', 'كود CSS', '2023-02-12 21:18:10', '2023-02-12 21:18:10'),
(4035, 'sa', 'paste_your_css_code_here', 'الصق كود CSS الخاص بك هنا.', '2023-02-12 21:18:10', '2023-02-12 21:18:10'),
(4036, 'sa', 'plugings', 'الوسادات', '2023-02-12 21:18:10', '2023-02-12 21:18:10'),
(4037, 'sa', 'installupdate_plugin', 'تثبيت / تحديث البرنامج المساعد', '2023-02-12 21:18:10', '2023-02-12 21:18:10'),
(4038, 'sa', 'are_you_sure_to_remove_this_plugin', 'هل أنت متأكد من إزالة هذا البرنامج المساعد', '2023-02-12 21:18:10', '2023-02-12 21:18:10'),
(4039, 'sa', 'deactive_confirmation', 'تأكيد غير نشط', '2023-02-12 21:18:10', '2023-02-12 21:18:10'),
(4040, 'sa', 'are_you_sure_to_deactive_this_plugin', 'هل أنت متأكد من إلغاء تنشيط هذا البرنامج المساعد', '2023-02-12 21:18:10', '2023-02-12 21:18:10'),
(4041, 'sa', 'deactivate', 'تعطيل', '2023-02-12 21:18:10', '2023-02-12 21:18:10'),
(4042, 'sa', 'are_you_sure_to_active_this_plugin', 'هل أنت متأكد من تنشيط هذا البرنامج المساعد', '2023-02-12 21:18:10', '2023-02-12 21:18:10'),
(4043, 'sa', 'add_new_user', 'إضافة مستخدم جديد', '2023-02-12 21:18:10', '2023-02-12 21:18:10'),
(4044, 'sa', 'add_user', 'إضافة مستخدم', '2023-02-12 21:18:10', '2023-02-12 21:18:10'),
(4045, 'sa', 'assign_role', 'تعيين الدور', '2023-02-12 21:18:10', '2023-02-12 21:18:10'),
(4046, 'sa', 'select_a_role', 'حدد دورًا', '2023-02-12 21:18:10', '2023-02-12 21:18:10'),
(4047, 'sa', 'add_role', 'أضف دورًا', '2023-02-12 21:18:10', '2023-02-12 21:18:10'),
(4048, 'sa', 'give_role_name', 'أعط اسم الدور', '2023-02-12 21:18:10', '2023-02-12 21:18:10'),
(4049, 'sa', 'module', 'وحدة', '2023-02-12 21:18:10', '2023-02-12 21:18:10'),
(4050, 'sa', 'feature', 'ميزة', '2023-02-12 21:18:10', '2023-02-12 21:18:10'),
(4051, 'sa', 'show', 'يعرض', '2023-02-12 21:18:10', '2023-02-12 21:18:10'),
(4052, 'sa', 'create', 'يخلق', '2023-02-12 21:18:10', '2023-02-12 21:18:10'),
(4053, 'sa', 'manage', 'يدير', '2023-02-12 21:18:10', '2023-02-12 21:18:10'),
(4054, 'sa', 'show_', 'يعرض', '2023-02-12 21:18:10', '2023-02-12 21:18:10'),
(4055, 'sa', 'create_', 'يخلق', '2023-02-12 21:18:10', '2023-02-12 21:18:10'),
(4056, 'sa', 'edit_', 'يحرر', '2023-02-12 21:18:10', '2023-02-12 21:18:10'),
(4057, 'sa', 'delete_', 'يمسح', '2023-02-12 21:18:10', '2023-02-12 21:18:10'),
(4058, 'sa', 'manage_', 'يدير', '2023-02-12 21:18:10', '2023-02-12 21:18:10'),
(4059, 'sa', 'update_role', 'تحديث الدور', '2023-02-12 21:18:10', '2023-02-12 21:18:10'),
(4060, 'sa', 'products_report', 'تقرير المنتجات', '2023-02-12 21:18:10', '2023-02-12 21:18:10'),
(4061, 'sa', 'product_category', 'فئة المنتج', '2023-02-12 21:18:10', '2023-02-12 21:18:10'),
(4062, 'sa', 'in_stock', 'في الأوراق المالية', '2023-02-12 21:18:10', '2023-02-12 21:18:10'),
(4063, 'sa', 'total', 'المجموع', '2023-02-12 21:18:10', '2023-02-12 21:18:10'),
(4064, 'sa', 'user_keyword_search_report', 'تقرير البحث عن الكلمات الرئيسية للمستخدم', '2023-02-12 21:18:10', '2023-02-12 21:18:10'),
(4065, 'sa', 'search_key', 'مفتاح البحث', '2023-02-12 21:18:10', '2023-02-12 21:18:10'),
(4066, 'sa', 'num_of_search', 'عدد البحث', '2023-02-12 21:18:10', '2023-02-12 21:18:10'),
(4067, 'sa', 'total_search', 'إجمالي البحث', '2023-02-12 21:18:10', '2023-02-12 21:18:10'),
(4068, 'sa', 'products_wishlist_report', 'تقرير قائمة المنتجات المفضلة', '2023-02-12 21:18:10', '2023-02-12 21:18:10'),
(4069, 'sa', 'num_of_wish', 'رقم الرغبة', '2023-02-12 21:18:10', '2023-02-12 21:18:10'),
(4070, 'sa', 'total_wishlist', 'إجمالي قائمة الرغبات', '2023-02-12 21:18:10', '2023-02-12 21:18:10'),
(4071, 'sa', 'custom_notifications', 'إخطارات مخصصة', '2023-02-12 21:18:10', '2023-02-12 21:18:10'),
(4072, 'sa', 'compose', 'مؤلف موسيقى', '2023-02-12 21:18:10', '2023-02-12 21:18:10'),
(4073, 'sa', 'delete_selected', 'احذف المختار', '2023-02-12 21:18:10', '2023-02-12 21:18:10'),
(4074, 'sa', 'sender', 'مرسل', '2023-02-12 21:18:10', '2023-02-12 21:18:10'),
(4075, 'sa', 'new_custom_notifications', 'إخطارات مخصصة جديدة', '2023-02-12 21:18:10', '2023-02-12 21:18:10'),
(4076, 'sa', 'all_notifications', 'جميع الإخطارات', '2023-02-12 21:18:10', '2023-02-12 21:18:10'),
(4077, 'sa', 'send_to', 'ارسل إلى', '2023-02-12 21:18:10', '2023-02-12 21:18:10'),
(4078, 'sa', 'all_customers', 'كل العملاء', '2023-02-12 21:18:10', '2023-02-12 21:18:10'),
(4079, 'sa', 'specific_customers', 'عملاء محددين', '2023-02-12 21:18:10', '2023-02-12 21:18:10'),
(4080, 'sa', 'all_users', 'جميع المستخدمين', '2023-02-12 21:18:10', '2023-02-12 21:18:10'),
(4081, 'sa', 'specific_users', 'مستخدمون محددون', '2023-02-12 21:18:10', '2023-02-12 21:18:10'),
(4082, 'sa', 'specific_user_role', 'دور المستخدم المحدد', '2023-02-12 21:18:10', '2023-02-12 21:18:10'),
(4083, 'sa', 'select_customers', 'حدد العملاء', '2023-02-12 21:18:10', '2023-02-12 21:18:10'),
(4084, 'sa', 'select_users', 'حدد المستخدمون', '2023-02-12 21:18:10', '2023-02-12 21:18:10'),
(4085, 'sa', 'select_user_roles', 'حدد أدوار المستخدم', '2023-02-12 21:18:10', '2023-02-12 21:18:10'),
(4086, 'sa', 'notification_type', 'نوع إعلام', '2023-02-12 21:18:10', '2023-02-12 21:18:10'),
(4087, 'sa', 'dashboard__email', 'لوحة القيادة والبريد الإلكتروني', '2023-02-12 21:18:10', '2023-02-12 21:18:10'),
(4088, 'sa', 'notification_subject', 'موضوع الإخطار', '2023-02-12 21:18:10', '2023-02-12 21:18:10'),
(4089, 'sa', 'send_now', 'ارسل الان', '2023-02-12 21:18:10', '2023-02-12 21:18:10'),
(4090, 'sa', 'add_new_coupon', 'أضف قسيمة جديدة', '2023-02-12 21:18:10', '2023-02-12 21:18:10'),
(4091, 'sa', 'amount_type', 'نوع المبلغ', '2023-02-12 21:18:10', '2023-02-12 21:18:10'),
(4092, 'sa', 'usage__limit', 'الاستخدام / الحد', '2023-02-12 21:18:10', '2023-02-12 21:18:10'),
(4093, 'sa', 'new_coupon', 'قسيمة جديدة', '2023-02-12 21:18:10', '2023-02-12 21:18:10'),
(4094, 'sa', 'coupon', 'قسيمة', '2023-02-12 21:18:10', '2023-02-12 21:18:10'),
(4095, 'sa', 'usage_restriction', 'قيود الاستخدام', '2023-02-12 21:18:10', '2023-02-12 21:18:10'),
(4096, 'sa', 'usage_limits', 'حدود الاستخدام', '2023-02-12 21:18:10', '2023-02-12 21:18:10'),
(4097, 'sa', 'coupon_code', 'رمز الكوبون', '2023-02-12 21:18:10', '2023-02-12 21:18:10'),
(4098, 'sa', 'discount_amount_type', 'نوع مبلغ الخصم', '2023-02-12 21:18:10', '2023-02-12 21:18:10'),
(4099, 'sa', 'discount_amount', 'مقدار الخصم', '2023-02-12 21:18:10', '2023-02-12 21:18:10'),
(4100, 'sa', 'allow_free_shipping', 'السماح بالشحن المجاني', '2023-02-12 21:18:10', '2023-02-12 21:18:10'),
(4101, 'sa', 'coupon_expiry_date', 'تاريخ انتهاء القسيمة', '2023-02-12 21:18:10', '2023-02-12 21:18:10'),
(4102, 'sa', 'minimum_spend', 'الحد الأدنى للإنفاق', '2023-02-12 21:18:10', '2023-02-12 21:18:10'),
(4103, 'sa', 'no_minimum', 'لا يوجد حد أدنى', '2023-02-12 21:18:10', '2023-02-12 21:18:10'),
(4104, 'sa', 'maximum_spend', 'الحد الأقصى للإنفاق', '2023-02-12 21:18:10', '2023-02-12 21:18:10'),
(4105, 'sa', 'no_maximum', 'لا يوجد حد أقصى', '2023-02-12 21:18:10', '2023-02-12 21:18:10'),
(4106, 'sa', 'individual_use_only', 'الاستخدام الفردي فقط', '2023-02-12 21:18:10', '2023-02-12 21:18:10'),
(4107, 'sa', 'exclude_sales_items', 'استبعاد بنود المبيعات', '2023-02-12 21:18:10', '2023-02-12 21:18:10'),
(4108, 'sa', 'exclude_product', 'استبعاد المنتج', '2023-02-12 21:18:10', '2023-02-12 21:18:10'),
(4109, 'sa', 'exclude_brands', 'استبعاد العلامات التجارية', '2023-02-12 21:18:10', '2023-02-12 21:18:10'),
(4110, 'sa', 'exclude_categories', 'استبعاد الفئات', '2023-02-12 21:18:10', '2023-02-12 21:18:10'),
(4111, 'sa', 'allowed_email', 'البريد الإلكتروني المسموح به', '2023-02-12 21:18:10', '2023-02-12 21:18:10'),
(4112, 'sa', 'usage_limit_per_coupon', 'حد الاستخدام لكل قسيمة', '2023-02-12 21:18:10', '2023-02-12 21:18:10'),
(4113, 'sa', 'unlimited_usage', 'استخدام غير محدود', '2023-02-12 21:18:10', '2023-02-12 21:18:10'),
(4114, 'sa', 'usage_limit_per_user', 'حد الاستخدام لكل مستخدم', '2023-02-12 21:18:10', '2023-02-12 21:18:10'),
(4115, 'sa', 'no_brand_selected', 'لم يتم تحديد علامة تجارية', '2023-02-12 21:18:10', '2023-02-12 21:18:10'),
(4116, 'sa', 'no_category_selected', 'لم يتم تحديد فئة', '2023-02-12 21:18:10', '2023-02-12 21:18:10'),
(4117, 'sa', 'instruction', 'تعليمات', '2023-02-12 21:18:10', '2023-02-12 21:18:10'),
(4118, 'sa', 'client_id', 'معرف العميل', '2023-02-12 21:18:10', '2023-02-12 21:18:10'),
(4119, 'sa', 'client_secret', 'سر العميل', '2023-02-12 21:18:10', '2023-02-12 21:18:10'),
(4120, 'sa', 'sandbox_mode', 'وضع الحماية', '2023-02-12 21:18:10', '2023-02-12 21:18:10'),
(4121, 'sa', 'stripe_public_key', 'المفتاح العام لـ Stripe', '2023-02-12 21:18:10', '2023-02-12 21:18:10'),
(4122, 'sa', 'stripe_secret_key', 'المفتاح السري الشريطي', '2023-02-12 21:18:10', '2023-02-12 21:18:10'),
(4123, 'sa', 'payment_method', 'طريقة الدفع او السداد', '2023-02-12 21:18:10', '2023-02-12 21:18:10'),
(4124, 'sa', 'payment_for', 'الدفع مقابل', '2023-02-12 21:18:10', '2023-02-12 21:18:10'),
(4125, 'sa', 'payment_details', 'بيانات الدفع', '2023-02-12 21:18:10', '2023-02-12 21:18:10'),
(4126, 'sa', 'add_pickup_point', 'أضف نقطة الالتقاط', '2023-02-12 21:18:10', '2023-02-12 21:18:10'),
(4127, 'sa', 'pickup_point', 'نقطة الالتقاط', '2023-02-12 21:18:10', '2023-02-12 21:18:10'),
(4128, 'sa', 'city', 'مدينة', '2023-02-12 21:18:10', '2023-02-12 21:18:10'),
(4129, 'sa', '________bulk_action_', 'العمل الجماعي', '2023-02-12 21:18:10', '2023-02-12 21:18:10'),
(4130, 'sa', '________delete_selection_', 'حذف التحديد', '2023-02-12 21:18:10', '2023-02-12 21:18:10'),
(4131, 'sa', '________apply_', 'يتقدم', '2023-02-12 21:18:10', '2023-02-12 21:18:10'),
(4132, 'sa', '____________________no_item_selected_', 'لم يتم تحديد أي عنصر', '2023-02-12 21:18:10', '2023-02-12 21:18:10'),
(4133, 'sa', '________________no_action_selected_', 'لم يتم تحديد أي إجراء', '2023-02-12 21:18:10', '2023-02-12 21:18:10'),
(4134, 'sa', 'create_pickup_points', 'إنشاء نقاط الالتقاط', '2023-02-12 21:18:10', '2023-02-12 21:18:10'),
(4135, 'sa', 'add_pickup_points', 'أضف نقاط الالتقاط', '2023-02-12 21:18:10', '2023-02-12 21:18:10'),
(4136, 'sa', 'pickup_point_name', 'اسم نقطة الالتقاط', '2023-02-12 21:18:10', '2023-02-12 21:18:10'),
(4137, 'sa', 'give_pickup_point_name', 'أعط اسم نقطة الالتقاط', '2023-02-12 21:18:10', '2023-02-12 21:18:10'),
(4138, 'sa', 'pickup_point_phone', 'هاتف نقطة الالتقاط', '2023-02-12 21:18:10', '2023-02-12 21:18:10'),
(4139, 'sa', 'give_pickup_point_phone', 'إعطاء هاتف نقطة الالتقاط', '2023-02-12 21:18:10', '2023-02-12 21:18:10'),
(4140, 'sa', 'give_pickup_point_location', 'إعطاء موقع نقطة الالتقاط', '2023-02-12 21:18:10', '2023-02-12 21:18:10'),
(4141, 'sa', 'select_city', 'اختر مدينة', '2023-02-12 21:18:10', '2023-02-12 21:18:10'),
(4142, 'sa', 'select_a_city', 'اختر مدينة', '2023-02-12 21:18:10', '2023-02-12 21:18:10'),
(4143, 'sa', 'create_new_carrier', 'إنشاء شركة نقل جديدة', '2023-02-12 21:18:10', '2023-02-12 21:18:10'),
(4144, 'sa', 'tracking_url', 'تتبع url', '2023-02-12 21:18:10', '2023-02-12 21:18:10'),
(4145, 'sa', 'add_new_shipping_courier', 'إضافة ساعي شحن جديد', '2023-02-12 21:18:10', '2023-02-12 21:18:10'),
(4146, 'sa', 'type_url', 'اكتب عنوان url', '2023-02-12 21:18:10', '2023-02-12 21:18:10'),
(4147, 'sa', 'shipping_courier_information', 'معلومات الشحن السريع', '2023-02-12 21:18:10', '2023-02-12 21:18:10'),
(4148, 'sa', 'customer_details', 'تفاصيل العميل', '2023-02-12 21:18:10', '2023-02-12 21:18:10'),
(4149, 'sa', 'id', 'بطاقة تعريف', '2023-02-12 21:18:10', '2023-02-12 21:18:10'),
(4150, 'sa', 'registered_date', 'تاريخ التسجيل', '2023-02-12 21:18:10', '2023-02-12 21:18:10'),
(4151, 'sa', 'total_purchase', 'إجمالي الشراء', '2023-02-12 21:18:10', '2023-02-12 21:18:10'),
(4152, 'sa', 'total_orders', 'إجمالي الطلبات', '2023-02-12 21:18:10', '2023-02-12 21:18:10'),
(4153, 'sa', 'reviews', 'المراجعات', '2023-02-12 21:18:10', '2023-02-12 21:18:10'),
(4154, 'sa', 'retun_requests', 'طلبات إعادة التشغيل', '2023-02-12 21:18:10', '2023-02-12 21:18:10'),
(4155, 'sa', 'addresses', 'عناوين', '2023-02-12 21:18:10', '2023-02-12 21:18:10'),
(4156, 'sa', 'wishlists', 'قوائم الامنيات', '2023-02-12 21:18:10', '2023-02-12 21:18:10'),
(4157, 'sa', 'tax', 'ضريبة', '2023-02-12 21:18:10', '2023-02-12 21:18:10'),
(4158, 'sa', 'delivery_cost', 'تكلفة التوصيل', '2023-02-12 21:18:10', '2023-02-12 21:18:10'),
(4159, 'sa', 'return_date', 'تاريخ العودة', '2023-02-12 21:18:10', '2023-02-12 21:18:10'),
(4160, 'sa', 'total_stock', 'إجمالي المخزون', '2023-02-12 21:18:10', '2023-02-12 21:18:10'),
(4161, 'sa', 'pickup_point_orders', 'أوامر نقطة الاستلام', '2023-02-12 21:18:10', '2023-02-12 21:18:10'),
(4162, 'sa', 'edit_condition', 'تحرير الشرط', '2023-02-12 21:18:10', '2023-02-12 21:18:10'),
(4163, 'sa', 'condition_updated_successfully', 'تم تحديث الشرط بنجاح', '2023-02-12 21:18:10', '2023-02-12 21:18:10'),
(4164, 'sa', 'edit_slider', 'تحرير شريط التمرير', '2023-02-12 21:18:10', '2023-02-12 21:18:10'),
(4165, 'sa', 'slider_updated_successfully', 'تم تحديث شريط التمرير بنجاح', '2023-02-12 21:18:10', '2023-02-12 21:18:10'),
(4166, 'sa', 'plugin_activate_successfully', 'تم تفعيل البرنامج المساعد بنجاح', '2023-02-12 21:18:10', '2023-02-12 21:18:10'),
(4167, 'sa', 'wallet_transactions', 'معاملات المحفظة', '2023-02-12 21:18:10', '2023-02-12 21:18:10'),
(4168, 'sa', 'offline_payment_methods', 'طرق الدفع دون اتصال بالإنترنت', '2023-02-12 21:18:10', '2023-02-12 21:18:10'),
(4169, 'sa', 'change_status_to_pending', 'تغيير الحالة إلى معلق', '2023-02-12 21:18:11', '2023-02-12 21:18:11'),
(4170, 'sa', 'change_status_to_accept', 'تغيير الحالة لقبول', '2023-02-12 21:18:11', '2023-02-12 21:18:11'),
(4171, 'sa', 'change_status_to_decline', 'تغيير الحالة للرفض', '2023-02-12 21:18:11', '2023-02-12 21:18:11'),
(4172, 'sa', 'transaction_type', 'نوع المعاملة', '2023-02-12 21:18:11', '2023-02-12 21:18:11'),
(4173, 'sa', 'credited', 'مصدق', '2023-02-12 21:18:11', '2023-02-12 21:18:11'),
(4174, 'sa', 'debited', 'مخصوم', '2023-02-12 21:18:11', '2023-02-12 21:18:11'),
(4175, 'sa', 'payment_options', 'خيارات الدفع', '2023-02-12 21:18:11', '2023-02-12 21:18:11'),
(4176, 'sa', 'online', 'متصل', '2023-02-12 21:18:11', '2023-02-12 21:18:11'),
(4177, 'sa', 'offline', 'غير متصل على الانترنت', '2023-02-12 21:18:11', '2023-02-12 21:18:11'),
(4178, 'sa', 'manual', 'يدوي', '2023-02-12 21:18:11', '2023-02-12 21:18:11'),
(4179, 'sa', 'cart', 'عربة التسوق', '2023-02-12 21:18:11', '2023-02-12 21:18:11'),
(4180, 'sa', 'cashback', 'استرداد النقود', '2023-02-12 21:18:11', '2023-02-12 21:18:11'),
(4181, 'sa', 'refund', 'استرداد', '2023-02-12 21:18:11', '2023-02-12 21:18:11'),
(4182, 'sa', 'accept', 'يقبل', '2023-02-12 21:18:11', '2023-02-12 21:18:11'),
(4183, 'sa', 'declined', 'انخفض', '2023-02-12 21:18:11', '2023-02-12 21:18:11'),
(4184, 'sa', 'tranaction_id', 'معرّف Tranaction', '2023-02-12 21:18:11', '2023-02-12 21:18:11'),
(4185, 'sa', 'executed_by', 'نفت بواسطة', '2023-02-12 21:18:11', '2023-02-12 21:18:11'),
(4186, 'sa', 'document', 'وثيقة', '2023-02-12 21:18:11', '2023-02-12 21:18:11'),
(4187, 'sa', 'action_applied_successfully', 'تم تطبيق الإجراء بنجاح', '2023-02-12 21:18:11', '2023-02-12 21:18:11'),
(4188, 'sa', 'semething_wrong', 'شيء خاطئ', '2023-02-12 21:18:11', '2023-02-12 21:18:11'),
(4189, 'sa', 'add_new_payment_method', 'أضف طريقة دفع جديدة', '2023-02-12 21:18:11', '2023-02-12 21:18:11'),
(4190, 'sa', 'custom', 'مخصص', '2023-02-12 21:18:11', '2023-02-12 21:18:11'),
(4191, 'sa', 'bank', 'بنك', '2023-02-12 21:18:11', '2023-02-12 21:18:11'),
(4192, 'sa', 'cheque', 'يفحص', '2023-02-12 21:18:11', '2023-02-12 21:18:11'),
(4193, 'sa', 'bank_information', 'المعلومات المصرفية', '2023-02-12 21:18:11', '2023-02-12 21:18:11'),
(4194, 'sa', 'bank_name', 'اسم البنك', '2023-02-12 21:18:11', '2023-02-12 21:18:11'),
(4195, 'sa', 'account_name', 'إسم الحساب', '2023-02-12 21:18:11', '2023-02-12 21:18:11'),
(4196, 'sa', 'account_number', 'رقم حساب', '2023-02-12 21:18:11', '2023-02-12 21:18:11'),
(4197, 'sa', 'routing_number', 'رقم التوصيل', '2023-02-12 21:18:11', '2023-02-12 21:18:11'),
(4198, 'sa', 'payment_method_information', 'معلومات طريقة الدفع', '2023-02-12 21:18:11', '2023-02-12 21:18:11'),
(4199, 'sa', 'new_method_added_successfully', 'تمت إضافة طريقة جديدة بنجاح', '2023-02-12 21:18:11', '2023-02-12 21:18:11'),
(4200, 'sa', 'new_method_adding_failed_', 'طريقة جديدة للإضافة فشلت', '2023-02-12 21:18:11', '2023-02-12 21:18:11'),
(4201, 'sa', 'new_method_adding_failed', 'طريقة جديدة للإضافة فشلت', '2023-02-12 21:18:45', '2023-02-12 21:18:45'),
(4202, 'sa', 'payment_method_updated_successfully', 'تم تحديث طريقة الدفع بنجاح', '2023-02-12 21:18:45', '2023-02-12 21:18:45'),
(4203, 'en', 'plugin_inactive_successfully', 'Plugin inactive successfully', '2023-02-12 22:20:08', '2023-02-12 22:20:08'),
(4204, 'en', 'customer_delete_successfully', 'Customer delete successfully', '2023-02-13 15:11:44', '2023-02-13 15:11:44'),
(4205, 'en', 'your_account_is_inactive', 'Your account is inactive', '2023-02-13 15:34:16', '2023-02-13 15:34:16'),
(4206, 'en', 'not_authentic', 'Not authentic', '2023-02-13 15:48:03', '2023-02-13 15:48:03'),
(4207, 'en', 'not_available', 'Not available', '2023-02-13 15:48:04', '2023-02-13 15:48:04'),
(4208, 'en', 'user_created_successfully', 'User created successfully', '2023-02-13 16:01:28', '2023-02-13 16:01:28'),
(4209, 'en', 'unsuccessful_attempt', 'Unsuccessful attempt', '2023-02-13 16:02:16', '2023-02-13 16:02:16'),
(4210, 'en', 'role_name_is_required', 'Role name is required', '2023-02-13 16:03:13', '2023-02-13 16:03:13'),
(4211, 'en', 'role_name_already_exists', 'Role name already exists', '2023-02-13 16:03:14', '2023-02-13 16:03:14'),
(4212, 'en', 'role_permission_required', 'Role permission required', '2023-02-13 16:03:14', '2023-02-13 16:03:14'),
(4213, 'en', 'role_added_successfully', 'Role added successfully', '2023-02-13 16:03:14', '2023-02-13 16:03:14'),
(4214, 'en', 'user_updated_successfully', 'User updated successfully', '2023-02-13 16:03:29', '2023-02-13 16:03:29'),
(4215, 'en', 'install_or_update_theme', 'Install or Update Theme', '2023-02-13 16:04:22', '2023-02-13 16:04:22'),
(4216, 'en', 'purchase_code', 'Purchase code', '2023-02-13 16:04:22', '2023-02-13 16:04:22'),
(4217, 'en', 'zip_file', 'Zip File', '2023-02-13 16:04:22', '2023-02-13 16:04:22'),
(4218, 'en', 'installupdate', 'Install/Update', '2023-02-13 16:04:22', '2023-02-13 16:04:22'),
(4219, 'en', 'tag_publish_status_changed_successfully', 'Tag Publish Status Changed Successfully', '2023-02-13 17:24:50', '2023-02-13 17:24:50'),
(4220, 'en', 'please_enter_a_valid_number_for_comment_close_days', 'Please Enter a Valid Number for Comment Close Days.', '2023-02-13 17:25:55', '2023-02-13 17:25:55'),
(4221, 'en', 'the_minimum_number_for_comment_close_days_is_1', 'The Minimum Number for Comment Close Days is 1', '2023-02-13 17:25:55', '2023-02-13 17:25:55'),
(4222, 'en', 'please_select_a_valid_option_for_comment_threads_level', 'Please Select a valid option for Comment Threads Level', '2023-02-13 17:25:55', '2023-02-13 17:25:55'),
(4223, 'en', 'please_enter_a_valid_number_for_per_page_comment', 'Please Enter a Valid Number for Per Page Comment', '2023-02-13 17:25:55', '2023-02-13 17:25:55'),
(4224, 'en', 'the_minimum_comments_for_per_page_is_8', 'The Minimum Comments for per Page is 8', '2023-02-13 17:25:55', '2023-02-13 17:25:55'),
(4225, 'en', 'please_select_a_valid_option_for_default_comment_page', 'Please Select a valid option for Default Comment Page', '2023-02-13 17:25:55', '2023-02-13 17:25:55'),
(4226, 'en', 'please_select_a_valid_option_for_comment_order', 'Please Select a valid option for Comment order', '2023-02-13 17:25:55', '2023-02-13 17:25:55'),
(4227, 'en', 'please_enter_a_valid_number_for_comment_links', 'Please Enter a Valid Number for Comment links', '2023-02-13 17:25:55', '2023-02-13 17:25:55'),
(4228, 'en', 'the_minimum_comment_links_number_must_be_1', 'The Minimum Comment links number must be 1', '2023-02-13 17:25:55', '2023-02-13 17:25:55'),
(4229, 'en', 'please_select_a_valid_default_avatar', 'Please Select a valid Default Avatar', '2023-02-13 17:25:55', '2023-02-13 17:25:55'),
(4230, 'en', 'comment_settings_updated_successfully', 'Comment Settings Updated Successfully', '2023-02-13 17:26:12', '2023-02-13 17:26:12'),
(4231, 'en', 'attribute_status_update_successfully', 'Attribute status update successfully', '2023-02-13 17:43:51', '2023-02-13 17:43:51'),
(4232, 'en', 'available_wallet_balance', 'Available Wallet Balance', '2023-02-13 17:58:50', '2023-02-13 17:58:50'),
(4233, 'en', 'add_money', 'Add Money', '2023-02-13 17:58:50', '2023-02-13 17:58:50'),
(4234, 'en', 'payment_option', 'Payment Option', '2023-02-13 17:58:50', '2023-02-13 17:58:50'),
(4235, 'en', 'enter_name', 'Enter Name', '2023-02-13 18:01:27', '2023-02-13 18:01:27'),
(4236, 'en', 'enter_code', 'Enter Code', '2023-02-13 18:01:27', '2023-02-13 18:01:27'),
(4237, 'en', 'reason_is_required', 'Reason is required', '2023-02-13 20:19:33', '2023-02-13 20:19:33'),
(4238, 'en', 'install_or_update_plugin', 'Install or Update Plugin', '2023-02-13 20:31:51', '2023-02-13 20:31:51'),
(4239, 'en', 'user_deleted_successfully', 'User deleted successfully', '2023-02-13 20:43:41', '2023-02-13 20:43:41'),
(4240, 'en', 'theme_activate_successfully', 'Theme activate successfully', '2023-02-13 21:06:01', '2023-02-13 21:06:01'),
(4241, 'en', 'error', 'error', '2023-02-14 15:34:28', '2023-02-14 15:34:28'),
(4242, 'en', 'error', 'error', '2023-02-14 15:34:33', '2023-02-14 15:34:33'),
(4243, 'en', 'you_have_no_unread_notification', 'You have no unread notification', '2023-02-14 17:08:05', '2023-02-14 17:08:05'),
(4244, 'en', 'new_shipping_profile_created_successfully', 'New shipping profile created successfully', '2023-02-14 17:26:28', '2023-02-14 17:26:28'),
(4245, 'en', 'manage_profile', 'Manage Profile', '2023-02-14 17:26:28', '2023-02-14 17:26:28'),
(4246, 'en', 'manage_products', 'Manage Products', '2023-02-14 17:26:28', '2023-02-14 17:26:28'),
(4247, 'en', 'update_location', 'Update Location', '2023-02-14 17:26:28', '2023-02-14 17:26:28'),
(4248, 'en', 'shipping_to', 'Shipping To', '2023-02-14 17:26:28', '2023-02-14 17:26:28'),
(4249, 'en', 'create_shipping_zone', 'Create Shipping Zone', '2023-02-14 17:26:28', '2023-02-14 17:26:28'),
(4250, 'en', 'no_shipping_zone_availble', 'No shipping zone availble', '2023-02-14 17:26:28', '2023-02-14 17:26:28'),
(4251, 'en', 'create_new_shipping_zone', 'Create New Shipping Zone', '2023-02-14 17:26:28', '2023-02-14 17:26:28'),
(4252, 'en', 'zone_name', 'Zone Name', '2023-02-14 17:26:28', '2023-02-14 17:26:28'),
(4253, 'en', 'not_visible_to_customers', 'Not visible to customers', '2023-02-14 17:26:28', '2023-02-14 17:26:28'),
(4254, 'en', 'shipping_zone_information', 'Shipping Zone Information', '2023-02-14 17:26:28', '2023-02-14 17:26:28'),
(4255, 'en', 'add_new_rate', 'Add New Rate', '2023-02-14 17:26:28', '2023-02-14 17:26:28'),
(4256, 'en', 'own_rate', 'Own Rate', '2023-02-14 17:26:28', '2023-02-14 17:26:28'),
(4257, 'en', 'carrier_rate', 'Carrier Rate', '2023-02-14 17:26:28', '2023-02-14 17:26:28'),
(4258, 'en', 'no_shipping_time_found', 'No shipping time found', '2023-02-14 17:26:28', '2023-02-14 17:26:28'),
(4259, 'en', 'carrier', 'Carrier', '2023-02-14 17:26:28', '2023-02-14 17:26:28'),
(4260, 'en', 'no_carrier_found', 'No carrier found', '2023-02-14 17:26:28', '2023-02-14 17:26:28'),
(4261, 'en', 'add_new_carrier', 'Add new carrier', '2023-02-14 17:26:28', '2023-02-14 17:26:28'),
(4262, 'en', 'shipped_by', 'Shipped By', '2023-02-14 17:26:28', '2023-02-14 17:26:28'),
(4263, 'en', 'select_a_medium', 'Select a medium', '2023-02-14 17:26:28', '2023-02-14 17:26:28'),
(4264, 'en', 'air_freight', 'Air Freight', '2023-02-14 17:26:28', '2023-02-14 17:26:28'),
(4265, 'en', 'ocean_freight', 'Ocean Freight', '2023-02-14 17:26:29', '2023-02-14 17:26:29'),
(4266, 'en', 'rail_freight', 'Rail Freight', '2023-02-14 17:26:29', '2023-02-14 17:26:29'),
(4267, 'en', 'road_freight', 'Road Freight', '2023-02-14 17:26:29', '2023-02-14 17:26:29'),
(4268, 'en', 'minimum_volumetric_weight', 'Minimum Volumetric Weight', '2023-02-14 17:26:29', '2023-02-14 17:26:29'),
(4269, 'en', 'maximum_volumetric_weight', 'Maximum Volumetric Weight', '2023-02-14 17:26:29', '2023-02-14 17:26:29'),
(4270, 'en', 'shipping_cost', 'Shipping Cost', '2023-02-14 17:26:29', '2023-02-14 17:26:29'),
(4271, 'en', 'kg', 'Kg', '2023-02-14 17:26:29', '2023-02-14 17:26:29'),
(4272, 'en', 'add_new_range', 'Add new range', '2023-02-14 17:26:29', '2023-02-14 17:26:29'),
(4273, 'en', 'rate_name', 'Rate Name', '2023-02-14 17:26:29', '2023-02-14 17:26:29'),
(4274, 'en', 'remove_condition', 'Remove Condition', '2023-02-14 17:26:29', '2023-02-14 17:26:29'),
(4275, 'en', 'based_on_item_weight', 'Based on item weight', '2023-02-14 17:26:29', '2023-02-14 17:26:29'),
(4276, 'en', 'based_on_order_price', 'Based on order price', '2023-02-14 17:26:29', '2023-02-14 17:26:29'),
(4277, 'en', 'minimum_weight', 'Minimum Weight', '2023-02-14 17:26:29', '2023-02-14 17:26:29'),
(4278, 'en', 'maximum_weight', 'Maximum Weight', '2023-02-14 17:26:29', '2023-02-14 17:26:29'),
(4279, 'en', 'minimum_price', 'Minimum Price', '2023-02-14 17:26:29', '2023-02-14 17:26:29'),
(4280, 'en', 'maximum_price', 'Maximum Price', '2023-02-14 17:26:29', '2023-02-14 17:26:29'),
(4281, 'en', 'update_rate', 'Update Rate', '2023-02-14 17:26:29', '2023-02-14 17:26:29'),
(4282, 'en', 'select_shipping_time', 'Select Shipping Time', '2023-02-14 17:26:29', '2023-02-14 17:26:29'),
(4283, 'en', 'product_list_updated_successfully', 'Product list updated successfully', '2023-02-14 17:26:48', '2023-02-14 17:26:48'),
(4284, 'en', 'please_select_a_profile', 'Please select a profile', '2023-02-14 17:28:50', '2023-02-14 17:28:50'),
(4285, 'en', 'new_zone_created_successfully', 'New zone created successfully', '2023-02-14 17:28:50', '2023-02-14 17:28:50'),
(4286, 'en', 'edit_zone', 'Edit Zone', '2023-02-14 17:28:51', '2023-02-14 17:28:51'),
(4287, 'en', 'delete_zone', 'Delete Zone', '2023-02-14 17:28:51', '2023-02-14 17:28:51'),
(4288, 'en', 'no_rates_customers_in_this_zone_wont_be_able_to_complete_checkout', 'No rates. Customers in this zone won\'t be able to complete checkout', '2023-02-14 17:28:51', '2023-02-14 17:28:51'),
(4289, 'en', 'shipping_cost_is_required', 'Shipping cost is required', '2023-02-14 17:29:04', '2023-02-14 17:29:04'),
(4290, 'en', 'please_select_a_carrier', 'Please select a carrier', '2023-02-14 17:29:04', '2023-02-14 17:29:04'),
(4291, 'en', 'please_select_a_shipping_medium', 'Please select a shipping medium', '2023-02-14 17:29:04', '2023-02-14 17:29:04'),
(4292, 'en', 'new_shipping_rate_created_successfully', 'New shipping rate created successfully', '2023-02-14 17:29:04', '2023-02-14 17:29:04'),
(4293, 'en', 'own_rates', 'Own Rates', '2023-02-14 17:29:05', '2023-02-14 17:29:05'),
(4294, 'en', 'carrier_rates', 'Carrier Rates', '2023-02-14 17:29:05', '2023-02-14 17:29:05'),
(4295, 'en', 'edit_rate', 'Edit Rate', '2023-02-14 17:29:05', '2023-02-14 17:29:05'),
(4296, 'en', 'delete_rate', 'Delete Rate', '2023-02-14 17:29:05', '2023-02-14 17:29:05'),
(4297, 'en', 'no_carrier_rates', 'No carrier rates', '2023-02-14 17:29:05', '2023-02-14 17:29:05'),
(4298, 'en', 'delete_profile', 'Delete Profile', '2023-02-14 17:29:30', '2023-02-14 17:29:30'),
(4299, 'en', 'shipping_zone', 'Shipping Zone', '2023-02-14 17:29:30', '2023-02-14 17:29:30'),
(4300, 'en', 'minimum_time_is_required', 'Minimum time is required', '2023-02-14 17:29:45', '2023-02-14 17:29:45'),
(4301, 'en', 'minimum_time_unit_is_required', 'Minimum time unit is required', '2023-02-14 17:29:45', '2023-02-14 17:29:45'),
(4302, 'en', 'maximum_time_is_required', 'Maximum time is required', '2023-02-14 17:29:45', '2023-02-14 17:29:45'),
(4303, 'en', 'maximum_time_unit_is_required', 'Maximum time unit is required', '2023-02-14 17:29:45', '2023-02-14 17:29:45'),
(4304, 'en', 'failed_create_shipping_time', 'Failed create shipping time', '2023-02-14 17:29:45', '2023-02-14 17:29:45');
INSERT INTO `tl_translations` (`id`, `lang`, `lang_key`, `lang_value`, `created_at`, `updated_at`) VALUES
(4305, 'en', 'shipping_rate_updated_successfully', 'Shipping rate updated successfully', '2023-02-14 17:30:32', '2023-02-14 17:30:32'),
(4306, 'en', 'tax_zone', 'Tax Zone', '2023-02-14 17:31:20', '2023-02-14 17:31:20'),
(4307, 'en', 'base_tax', 'Base Tax', '2023-02-14 17:31:20', '2023-02-14 17:31:20'),
(4308, 'en', 'custom_taxes', 'Custom Taxes', '2023-02-14 17:31:27', '2023-02-14 17:31:27'),
(4309, 'en', 'new_custom_tax', 'New Custom Tax', '2023-02-14 17:31:28', '2023-02-14 17:31:28'),
(4310, 'en', 'tax_rate', 'Tax Rate', '2023-02-14 17:31:28', '2023-02-14 17:31:28'),
(4311, 'en', 'add_new_tax', 'Add New Tax', '2023-02-14 17:31:28', '2023-02-14 17:31:28'),
(4312, 'en', 'you_can_select_an_existing_collection_or_', 'You can select an existing collection or ', '2023-02-14 17:31:28', '2023-02-14 17:31:28'),
(4313, 'en', 'create_new_collection', 'Create new collection', '2023-02-14 17:31:28', '2023-02-14 17:31:28'),
(4314, 'en', 'select_state', 'Select State', '2023-02-14 17:31:28', '2023-02-14 17:31:28'),
(4315, 'en', 'update_tax_rate', 'Update Tax Rate', '2023-02-14 17:31:28', '2023-02-14 17:31:28'),
(4316, 'en', 'save_change', 'Save Change', '2023-02-14 17:31:28', '2023-02-14 17:31:28'),
(4317, 'en', 'zone_base_tax_updated', 'Zone base tax updated', '2023-02-14 17:31:34', '2023-02-14 17:31:34'),
(4318, 'en', 'order_details', 'Order Details', '2023-02-14 17:39:50', '2023-02-14 17:39:50'),
(4319, 'en', 'print_shipping_label', 'Print Shipping Label', '2023-02-14 17:39:50', '2023-02-14 17:39:50'),
(4320, 'en', 'print_invoice', 'Print Invoice', '2023-02-14 17:39:50', '2023-02-14 17:39:50'),
(4321, 'en', 'accept_order', 'Accept Order', '2023-02-14 17:39:50', '2023-02-14 17:39:50'),
(4322, 'en', 'cancel_order', 'Cancel Order', '2023-02-14 17:39:50', '2023-02-14 17:39:50'),
(4323, 'en', 'paid_by', 'Paid by', '2023-02-14 17:39:50', '2023-02-14 17:39:50'),
(4324, 'en', 'shipping_info', 'Shipping Info', '2023-02-14 17:39:50', '2023-02-14 17:39:50'),
(4325, 'en', 'billing_info', 'Billing Info', '2023-02-14 17:39:50', '2023-02-14 17:39:50'),
(4326, 'en', 'package', 'Package', '2023-02-14 17:39:50', '2023-02-14 17:39:50'),
(4327, 'en', '_shipped', ' Shipped', '2023-02-14 17:39:50', '2023-02-14 17:39:50'),
(4328, 'en', 'order_note', 'Order Note', '2023-02-14 17:39:50', '2023-02-14 17:39:50'),
(4329, 'en', 'order_summary', 'Order Summary', '2023-02-14 17:39:50', '2023-02-14 17:39:50'),
(4330, 'en', 'subtotal', 'Subtotal', '2023-02-14 17:39:50', '2023-02-14 17:39:50'),
(4331, 'en', 'total_payable', 'Total Payable', '2023-02-14 17:39:50', '2023-02-14 17:39:50'),
(4332, 'en', 'select_delivery_status', 'Select delivery status', '2023-02-14 17:39:50', '2023-02-14 17:39:50'),
(4333, 'en', 'select_payment_status', 'Select payment status', '2023-02-14 17:39:50', '2023-02-14 17:39:50'),
(4334, 'en', 'are_you_sure_to_cancel__this_item', 'Are you sure to cancel  this item', '2023-02-14 17:39:50', '2023-02-14 17:39:50'),
(4335, 'en', 'shipping_label', 'Shipping Label', '2023-02-14 17:39:50', '2023-02-14 17:39:50'),
(4336, 'en', 'order_status_updated_successfully', 'Order status updated successfully', '2023-02-14 17:39:50', '2023-02-14 17:39:50'),
(4337, 'en', 'order_accept_successfully', 'Order accept successfully', '2023-02-14 17:41:01', '2023-02-14 17:41:01'),
(4338, 'en', 'no_account_found_associate_this_email', 'No account found associate this email', '2023-02-14 19:28:37', '2023-02-14 19:28:37'),
(4339, 'en', 'payment_failed', 'Payment Failed', '2023-02-14 20:40:00', '2023-02-14 20:40:00'),
(4340, 'en', 'payment_error', 'Payment Error', '2023-02-14 20:40:05', '2023-02-14 20:40:05'),
(4341, 'en', 'payment_failed_with_stripe_please_try_again', 'Payment Failed with stripe. Please try again.', '2023-02-14 20:40:05', '2023-02-14 20:40:05'),
(4342, 'en', 'back_to_home', 'Back To Home', '2023-02-14 20:40:05', '2023-02-14 20:40:05'),
(4343, 'en', 'shipping_profile_updated_successfully', 'Shipping profile updated successfully', '2023-02-14 20:44:08', '2023-02-14 20:44:08'),
(4344, 'en', 'coupon_code_is_required', 'Coupon code is required', '2023-02-14 20:47:25', '2023-02-14 20:47:25'),
(4345, 'en', 'code_is_already_taken', 'Code is already taken', '2023-02-14 20:47:25', '2023-02-14 20:47:25'),
(4346, 'en', 'coupon_created_successfully', 'Coupon created successfully', '2023-02-14 20:47:25', '2023-02-14 20:47:25'),
(4347, 'en', 'flat_discount', 'Flat discount', '2023-02-14 20:47:27', '2023-02-14 20:47:27'),
(4348, 'en', 'edit_coupon', 'Edit Coupon', '2023-02-14 20:53:51', '2023-02-14 20:53:51'),
(4349, 'en', 'coupon_updated_successfully', 'Coupon updated successfully', '2023-02-14 20:54:19', '2023-02-14 20:54:19'),
(4350, 'en', 'new', 'New', '2023-02-15 15:13:21', '2023-02-15 15:13:21'),
(4351, 'en', 'stripe_payment', 'Stripe Payment', '2023-02-15 15:23:36', '2023-02-15 15:23:36'),
(4352, 'en', 'don_not_close_the_tab_the_payment_is_being_processed', 'Don not close the tab. The payment is being processed', '2023-02-15 15:23:37', '2023-02-15 15:23:37'),
(4353, 'en', 'please_select_a_product', 'Please select a product', '2023-02-15 15:24:43', '2023-02-15 15:24:43'),
(4354, 'en', 'please_select_delivery_status', 'Please select delivery status', '2023-02-15 15:24:43', '2023-02-15 15:24:43'),
(4355, 'en', 'new_reason_added_successfully', 'New reason added successfully', '2023-02-15 15:46:20', '2023-02-15 15:46:20'),
(4356, 'en', 'new_reason_added_successfully', 'New reason added successfully', '2023-02-15 15:46:24', '2023-02-15 15:46:24'),
(4357, 'en', 'reason_deleted_successfully', 'Reason deleted successfully', '2023-02-15 15:46:31', '2023-02-15 15:46:31'),
(4358, 'en', 'edit_refund_reason', 'Edit Refund Reason', '2023-02-15 15:47:16', '2023-02-15 15:47:16'),
(4359, 'en', 'reason_updated_successfully', 'Reason updated successfully', '2023-02-15 15:48:11', '2023-02-15 15:48:11'),
(4360, 'en', 'review', 'Review', '2023-02-15 16:07:20', '2023-02-15 16:07:20'),
(4361, 'en', 'images', 'Images', '2023-02-15 16:07:26', '2023-02-15 16:07:26'),
(4362, 'en', 'review_deleted_successfully', 'Review deleted successfully', '2023-02-15 16:08:20', '2023-02-15 16:08:20'),
(4363, 'bd', 'plugin_inactive_successfully', 'প্লাগইন সফলভাবে নিষ্ক্রিয়', '2023-02-15 16:32:08', '2023-02-15 16:32:08'),
(4364, 'bd', 'customer_delete_successfully', 'গ্রাহক সফলভাবে মুছে ফেলুন', '2023-02-15 16:32:08', '2023-02-15 16:32:08'),
(4365, 'bd', 'your_account_is_inactive', 'আপনার অ্যাকাউন্ট নিষ্ক্রিয়', '2023-02-15 16:32:08', '2023-02-15 16:32:08'),
(4366, 'bd', 'not_authentic', 'খাঁটি নয়', '2023-02-15 16:32:08', '2023-02-15 16:32:08'),
(4367, 'bd', 'not_available', 'পাওয়া যায় না', '2023-02-15 16:32:08', '2023-02-15 16:32:08'),
(4368, 'bd', 'user_created_successfully', 'ব্যবহারকারী সফলভাবে তৈরি করা হয়েছে', '2023-02-15 16:32:08', '2023-02-15 16:32:08'),
(4369, 'bd', 'unsuccessful_attempt', 'ব্যর্থ প্রচেষ্টা', '2023-02-15 16:32:08', '2023-02-15 16:32:08'),
(4370, 'bd', 'role_name_is_required', 'ভূমিকার নাম প্রয়োজন', '2023-02-15 16:32:08', '2023-02-15 16:32:08'),
(4371, 'bd', 'role_name_already_exists', 'ভূমিকার নাম ইতিমধ্যেই বিদ্যমান৷', '2023-02-15 16:32:08', '2023-02-15 16:32:08'),
(4372, 'bd', 'role_permission_required', 'ভূমিকা অনুমতি প্রয়োজন', '2023-02-15 16:32:08', '2023-02-15 16:32:08'),
(4373, 'bd', 'role_added_successfully', 'ভূমিকা সফলভাবে যোগ করা হয়েছে', '2023-02-15 16:32:08', '2023-02-15 16:32:08'),
(4374, 'bd', 'user_updated_successfully', 'ব্যবহারকারী সফলভাবে আপডেট করা হয়েছে', '2023-02-15 16:32:08', '2023-02-15 16:32:08'),
(4375, 'bd', 'install_or_update_theme', 'থিম ইনস্টল বা আপডেট করুন', '2023-02-15 16:32:08', '2023-02-15 16:32:08'),
(4376, 'bd', 'purchase_code', 'ক্রয় কোড', '2023-02-15 16:32:08', '2023-02-15 16:32:08'),
(4377, 'bd', 'zip_file', 'জিপ ফাইল', '2023-02-15 16:32:08', '2023-02-15 16:32:08'),
(4378, 'bd', 'installupdate', 'ইনস্টল/আপডেট করুন', '2023-02-15 16:32:08', '2023-02-15 16:32:08'),
(4379, 'bd', 'tag_publish_status_changed_successfully', 'ট্যাগ প্রকাশের স্থিতি সফলভাবে পরিবর্তিত হয়েছে৷', '2023-02-15 16:32:08', '2023-02-15 16:32:08'),
(4380, 'bd', 'please_enter_a_valid_number_for_comment_close_days', 'মন্তব্য বন্ধ দিন জন্য একটি বৈধ নম্বর লিখুন দয়া করে.', '2023-02-15 16:32:08', '2023-02-15 16:32:08'),
(4381, 'bd', 'the_minimum_number_for_comment_close_days_is_1', 'মন্তব্য বন্ধের দিনের জন্য ন্যূনতম সংখ্যা হল 1', '2023-02-15 16:32:08', '2023-02-15 16:32:08'),
(4382, 'bd', 'please_select_a_valid_option_for_comment_threads_level', 'মন্তব্য থ্রেড স্তরের জন্য একটি বৈধ বিকল্প নির্বাচন করুন', '2023-02-15 16:32:08', '2023-02-15 16:32:08'),
(4383, 'bd', 'please_enter_a_valid_number_for_per_page_comment', 'প্রতি পৃষ্ঠা মন্তব্যের জন্য একটি বৈধ নম্বর লিখুন', '2023-02-15 16:32:08', '2023-02-15 16:32:08'),
(4384, 'bd', 'the_minimum_comments_for_per_page_is_8', 'প্রতি পৃষ্ঠার জন্য সর্বনিম্ন মন্তব্য 8', '2023-02-15 16:32:08', '2023-02-15 16:32:08'),
(4385, 'bd', 'please_select_a_valid_option_for_default_comment_page', 'ডিফল্ট মন্তব্য পৃষ্ঠার জন্য একটি বৈধ বিকল্প নির্বাচন করুন', '2023-02-15 16:32:08', '2023-02-15 16:32:08'),
(4386, 'bd', 'please_select_a_valid_option_for_comment_order', 'মন্তব্য আদেশের জন্য একটি বৈধ বিকল্প নির্বাচন করুন', '2023-02-15 16:32:08', '2023-02-15 16:32:08'),
(4387, 'bd', 'please_enter_a_valid_number_for_comment_links', 'মন্তব্য লিঙ্কের জন্য একটি বৈধ নম্বর লিখুন', '2023-02-15 16:32:08', '2023-02-15 16:32:08'),
(4388, 'bd', 'the_minimum_comment_links_number_must_be_1', 'সর্বনিম্ন মন্তব্য লিঙ্ক নম্বর 1 হতে হবে', '2023-02-15 16:32:08', '2023-02-15 16:32:08'),
(4389, 'bd', 'please_select_a_valid_default_avatar', 'অনুগ্রহ করে একটি বৈধ ডিফল্ট অবতার নির্বাচন করুন৷', '2023-02-15 16:32:08', '2023-02-15 16:32:08'),
(4390, 'bd', 'comment_settings_updated_successfully', 'মন্তব্য সেটিংস সফলভাবে আপডেট হয়েছে৷', '2023-02-15 16:32:08', '2023-02-15 16:32:08'),
(4391, 'bd', 'attribute_status_update_successfully', 'অ্যাট্রিবিউট স্ট্যাটাস সফলভাবে আপডেট করা হয়েছে', '2023-02-15 16:32:08', '2023-02-15 16:32:08'),
(4392, 'bd', 'available_wallet_balance', 'উপলব্ধ ওয়ালেট ব্যালেন্স', '2023-02-15 16:32:08', '2023-02-15 16:32:08'),
(4393, 'bd', 'add_money', 'টাকা যোগ করুন', '2023-02-15 16:32:08', '2023-02-15 16:32:08'),
(4394, 'bd', 'payment_option', 'পেমেন্ট অপশন', '2023-02-15 16:32:08', '2023-02-15 16:32:08'),
(4395, 'bd', 'enter_name', 'নাম লিখুন', '2023-02-15 16:32:08', '2023-02-15 16:32:08'),
(4396, 'bd', 'enter_code', 'কোড লিখুন', '2023-02-15 16:32:08', '2023-02-15 16:32:08'),
(4397, 'bd', 'reason_is_required', 'কারণ প্রয়োজন', '2023-02-15 16:32:08', '2023-02-15 16:32:08'),
(4398, 'bd', 'install_or_update_plugin', 'প্লাগইন ইনস্টল বা আপডেট করুন', '2023-02-15 16:32:08', '2023-02-15 16:32:08'),
(4399, 'bd', 'user_deleted_successfully', 'ব্যবহারকারী সফলভাবে মুছে ফেলা হয়েছে', '2023-02-15 16:32:08', '2023-02-15 16:32:08'),
(4400, 'bd', 'theme_activate_successfully', 'থিম সফলভাবে সক্রিয়', '2023-02-15 16:32:08', '2023-02-15 16:32:08'),
(4401, 'bd', 'error', 'ত্রুটি', '2023-02-15 16:32:08', '2023-02-15 16:32:08'),
(4402, 'bd', 'you_have_no_unread_notification', 'আপনার কোন অপঠিত বিজ্ঞপ্তি নেই', '2023-02-15 16:32:08', '2023-02-15 16:32:08'),
(4403, 'bd', 'new_shipping_profile_created_successfully', 'নতুন শিপিং প্রোফাইল সফলভাবে তৈরি করা হয়েছে৷', '2023-02-15 16:32:08', '2023-02-15 16:32:08'),
(4404, 'bd', 'manage_profile', 'প্রোফাইল পরিচালনা করুন', '2023-02-15 16:32:08', '2023-02-15 16:32:08'),
(4405, 'bd', 'manage_products', 'পণ্য পরিচালনা করুন', '2023-02-15 16:32:08', '2023-02-15 16:32:08'),
(4406, 'bd', 'update_location', 'অবস্থান আপডেট করুন', '2023-02-15 16:32:08', '2023-02-15 16:32:08'),
(4407, 'bd', 'shipping_to', 'শিপিং এ', '2023-02-15 16:32:08', '2023-02-15 16:32:08'),
(4408, 'bd', 'create_shipping_zone', 'শিপিং জোন তৈরি করুন', '2023-02-15 16:32:08', '2023-02-15 16:32:08'),
(4409, 'bd', 'no_shipping_zone_availble', 'কোন শিপিং জোন উপলব্ধ', '2023-02-15 16:32:08', '2023-02-15 16:32:08'),
(4410, 'bd', 'create_new_shipping_zone', 'নতুন শিপিং জোন তৈরি করুন', '2023-02-15 16:32:08', '2023-02-15 16:32:08'),
(4411, 'bd', 'zone_name', 'জোনের নাম', '2023-02-15 16:32:08', '2023-02-15 16:32:08'),
(4412, 'bd', 'not_visible_to_customers', 'গ্রাহকদের কাছে দৃশ্যমান নয়', '2023-02-15 16:32:08', '2023-02-15 16:32:08'),
(4413, 'bd', 'shipping_zone_information', 'শিপিং জোন তথ্য', '2023-02-15 16:32:08', '2023-02-15 16:32:08'),
(4414, 'bd', 'add_new_rate', 'নতুন হার যোগ করুন', '2023-02-15 16:32:08', '2023-02-15 16:32:08'),
(4415, 'bd', 'own_rate', 'নিজস্ব হার', '2023-02-15 16:32:08', '2023-02-15 16:32:08'),
(4416, 'bd', 'carrier_rate', 'ক্যারিয়ার রেট', '2023-02-15 16:32:08', '2023-02-15 16:32:08'),
(4417, 'bd', 'no_shipping_time_found', 'কোন শিপিং সময় পাওয়া যায়নি', '2023-02-15 16:32:08', '2023-02-15 16:32:08'),
(4418, 'bd', 'carrier', 'বাহক', '2023-02-15 16:32:08', '2023-02-15 16:32:08'),
(4419, 'bd', 'no_carrier_found', 'কোনো বাহক পাওয়া যায়নি', '2023-02-15 16:32:08', '2023-02-15 16:32:08'),
(4420, 'bd', 'add_new_carrier', 'নতুন ক্যারিয়ার যোগ করুন', '2023-02-15 16:32:08', '2023-02-15 16:32:08'),
(4421, 'bd', 'shipped_by', 'দ্বারা পাঠানো হয়েছে', '2023-02-15 16:32:08', '2023-02-15 16:32:08'),
(4422, 'bd', 'select_a_medium', 'একটি মাধ্যম নির্বাচন করুন', '2023-02-15 16:32:08', '2023-02-15 16:32:08'),
(4423, 'bd', 'air_freight', 'বিমান ভ্রমন', '2023-02-15 16:32:08', '2023-02-15 16:32:08'),
(4424, 'bd', 'ocean_freight', 'মহাসাগর মালবাহী', '2023-02-15 16:32:08', '2023-02-15 16:32:08'),
(4425, 'bd', 'rail_freight', 'রেল মালবাহী', '2023-02-15 16:32:08', '2023-02-15 16:32:08'),
(4426, 'bd', 'road_freight', 'সড়ক মালবাহী', '2023-02-15 16:32:08', '2023-02-15 16:32:08'),
(4427, 'bd', 'minimum_volumetric_weight', 'ন্যূনতম ভলিউমেট্রিক ওজন', '2023-02-15 16:32:08', '2023-02-15 16:32:08'),
(4428, 'bd', 'maximum_volumetric_weight', 'সর্বোচ্চ ভলিউমেট্রিক ওজন', '2023-02-15 16:32:08', '2023-02-15 16:32:08'),
(4429, 'bd', 'shipping_cost', 'প্রদান খরচ', '2023-02-15 16:32:08', '2023-02-15 16:32:08'),
(4430, 'bd', 'kg', 'কেজি', '2023-02-15 16:32:08', '2023-02-15 16:32:08'),
(4431, 'bd', 'add_new_range', 'নতুন পরিসীমা যোগ করুন', '2023-02-15 16:32:08', '2023-02-15 16:32:08'),
(4432, 'bd', 'rate_name', 'রেট নাম', '2023-02-15 16:32:08', '2023-02-15 16:32:08'),
(4433, 'bd', 'remove_condition', 'শর্ত সরান', '2023-02-15 16:32:08', '2023-02-15 16:32:08'),
(4434, 'bd', 'based_on_item_weight', 'আইটেম ওজন উপর ভিত্তি করে', '2023-02-15 16:32:08', '2023-02-15 16:32:08'),
(4435, 'bd', 'based_on_order_price', 'অর্ডার মূল্যের উপর ভিত্তি করে', '2023-02-15 16:32:08', '2023-02-15 16:32:08'),
(4436, 'bd', 'minimum_weight', 'ন্যূনতম ওজন', '2023-02-15 16:32:08', '2023-02-15 16:32:08'),
(4437, 'bd', 'maximum_weight', 'সর্বোচ্চ ওজন', '2023-02-15 16:32:08', '2023-02-15 16:32:08'),
(4438, 'bd', 'minimum_price', 'সর্বনিম্ন মূল্য', '2023-02-15 16:32:08', '2023-02-15 16:32:08'),
(4439, 'bd', 'maximum_price', 'সর্বোচ্চ মূল্য', '2023-02-15 16:32:08', '2023-02-15 16:32:08'),
(4440, 'bd', 'update_rate', 'হালনাগাদ হার', '2023-02-15 16:32:08', '2023-02-15 16:32:08'),
(4441, 'bd', 'select_shipping_time', 'শিপিং সময় নির্বাচন করুন', '2023-02-15 16:32:08', '2023-02-15 16:32:08'),
(4442, 'bd', 'product_list_updated_successfully', 'পণ্য তালিকা সফলভাবে আপডেট করা হয়েছে', '2023-02-15 16:32:08', '2023-02-15 16:32:08'),
(4443, 'bd', 'please_select_a_profile', 'একটি প্রোফাইল নির্বাচন করুন', '2023-02-15 16:32:08', '2023-02-15 16:32:08'),
(4444, 'bd', 'new_zone_created_successfully', 'নতুন জোন সফলভাবে তৈরি করা হয়েছে৷', '2023-02-15 16:32:08', '2023-02-15 16:32:08'),
(4445, 'bd', 'edit_zone', 'অঞ্চল সম্পাদনা করুন', '2023-02-15 16:32:08', '2023-02-15 16:32:08'),
(4446, 'bd', 'delete_zone', 'জোন মুছুন', '2023-02-15 16:32:08', '2023-02-15 16:32:08'),
(4447, 'bd', 'no_rates_customers_in_this_zone_wont_be_able_to_complete_checkout', 'এই জোনের গ্রাহকরা চেকআউট সম্পূর্ণ করতে পারবেন না', '2023-02-15 16:32:08', '2023-02-15 16:32:08'),
(4448, 'bd', 'shipping_cost_is_required', 'শিপিং খরচ প্রয়োজন', '2023-02-15 16:32:08', '2023-02-15 16:32:08'),
(4449, 'bd', 'please_select_a_carrier', 'একটি ক্যারিয়ার নির্বাচন করুন', '2023-02-15 16:32:08', '2023-02-15 16:32:08'),
(4450, 'bd', 'please_select_a_shipping_medium', 'একটি শিপিং মাধ্যম নির্বাচন করুন', '2023-02-15 16:32:09', '2023-02-15 16:32:09'),
(4451, 'bd', 'new_shipping_rate_created_successfully', 'নতুন শিপিং রেট সফলভাবে তৈরি করা হয়েছে৷', '2023-02-15 16:32:09', '2023-02-15 16:32:09'),
(4452, 'bd', 'own_rates', 'নিজস্ব হার', '2023-02-15 16:32:09', '2023-02-15 16:32:09'),
(4453, 'bd', 'carrier_rates', 'ক্যারিয়ার রেট', '2023-02-15 16:32:09', '2023-02-15 16:32:09'),
(4454, 'bd', 'edit_rate', 'হার সম্পাদনা করুন', '2023-02-15 16:32:09', '2023-02-15 16:32:09'),
(4455, 'bd', 'delete_rate', 'হার মুছুন', '2023-02-15 16:32:09', '2023-02-15 16:32:09'),
(4456, 'bd', 'no_carrier_rates', 'কোন ক্যারিয়ার রেট', '2023-02-15 16:32:09', '2023-02-15 16:32:09'),
(4457, 'bd', 'delete_profile', 'প্রোফাইল মুছুন', '2023-02-15 16:32:09', '2023-02-15 16:32:09'),
(4458, 'bd', 'shipping_zone', 'শিপিং জোন', '2023-02-15 16:32:09', '2023-02-15 16:32:09'),
(4459, 'bd', 'minimum_time_is_required', 'ন্যূনতম সময় প্রয়োজন', '2023-02-15 16:32:09', '2023-02-15 16:32:09'),
(4460, 'bd', 'minimum_time_unit_is_required', 'ন্যূনতম সময়ের ইউনিট প্রয়োজন', '2023-02-15 16:32:09', '2023-02-15 16:32:09'),
(4461, 'bd', 'maximum_time_is_required', 'সর্বোচ্চ সময় প্রয়োজন', '2023-02-15 16:32:09', '2023-02-15 16:32:09'),
(4462, 'bd', 'maximum_time_unit_is_required', 'সর্বাধিক সময় ইউনিট প্রয়োজন', '2023-02-15 16:32:09', '2023-02-15 16:32:09'),
(4463, 'bd', 'failed_create_shipping_time', 'শিপিং সময় তৈরি করতে ব্যর্থ হয়েছে৷', '2023-02-15 16:32:09', '2023-02-15 16:32:09'),
(4464, 'bd', 'shipping_rate_updated_successfully', 'শিপিং রেট সফলভাবে আপডেট হয়েছে', '2023-02-15 16:32:09', '2023-02-15 16:32:09'),
(4465, 'bd', 'tax_zone', 'কর অঞ্চল', '2023-02-15 16:32:09', '2023-02-15 16:32:09'),
(4466, 'bd', 'base_tax', 'বেস ট্যাক্স', '2023-02-15 16:32:09', '2023-02-15 16:32:09'),
(4467, 'bd', 'custom_taxes', 'কাস্টম ট্যাক্স', '2023-02-15 16:32:09', '2023-02-15 16:32:09'),
(4468, 'bd', 'new_custom_tax', 'নতুন কাস্টম ট্যাক্স', '2023-02-15 16:32:09', '2023-02-15 16:32:09'),
(4469, 'bd', 'tax_rate', 'করের হার', '2023-02-15 16:32:09', '2023-02-15 16:32:09'),
(4470, 'bd', 'add_new_tax', 'নতুন ট্যাক্স যোগ করুন', '2023-02-15 16:32:09', '2023-02-15 16:32:09'),
(4471, 'bd', 'you_can_select_an_existing_collection_or_', 'আপনি একটি বিদ্যমান সংগ্রহ নির্বাচন করতে পারেন বা', '2023-02-15 16:32:09', '2023-02-15 16:32:09'),
(4472, 'bd', 'create_new_collection', 'নতুন সংগ্রহ তৈরি করুন', '2023-02-15 16:32:09', '2023-02-15 16:32:09'),
(4473, 'bd', 'select_state', 'রাজ্য নির্বাচন কর', '2023-02-15 16:32:09', '2023-02-15 16:32:09'),
(4474, 'bd', 'update_tax_rate', 'ট্যাক্স রেট আপডেট করুন', '2023-02-15 16:32:09', '2023-02-15 16:32:09'),
(4475, 'bd', 'save_change', 'পরিবর্তন সংরক্ষণ', '2023-02-15 16:32:09', '2023-02-15 16:32:09'),
(4476, 'bd', 'zone_base_tax_updated', 'জোন বেস ট্যাক্স আপডেট করা হয়েছে', '2023-02-15 16:32:09', '2023-02-15 16:32:09'),
(4477, 'bd', 'order_details', 'আদেশ বিবরণী', '2023-02-15 16:32:09', '2023-02-15 16:32:09'),
(4478, 'bd', 'print_shipping_label', 'প্রিন্ট শিপিং লেবেল', '2023-02-15 16:32:09', '2023-02-15 16:32:09'),
(4479, 'bd', 'print_invoice', 'চালান প্রিন্ট করুন', '2023-02-15 16:32:09', '2023-02-15 16:32:09'),
(4480, 'bd', 'accept_order', 'অর্ডার গ্রহণ করুন', '2023-02-15 16:32:09', '2023-02-15 16:32:09'),
(4481, 'bd', 'cancel_order', 'আদেশ বাতিল', '2023-02-15 16:32:09', '2023-02-15 16:32:09'),
(4482, 'bd', 'paid_by', 'দ্বারা পরিশোধ করা হয়', '2023-02-15 16:32:09', '2023-02-15 16:32:09'),
(4483, 'bd', 'shipping_info', 'জাহাজীকরন তথ্য', '2023-02-15 16:32:09', '2023-02-15 16:32:09'),
(4484, 'bd', 'billing_info', 'বিলিং তথ্য', '2023-02-15 16:32:09', '2023-02-15 16:32:09'),
(4485, 'bd', 'package', 'প্যাকেজ', '2023-02-15 16:32:09', '2023-02-15 16:32:09'),
(4486, 'bd', '_shipped', 'পাঠানো হয়েছে', '2023-02-15 16:32:09', '2023-02-15 16:32:09'),
(4487, 'bd', 'order_note', 'অর্ডার নোট', '2023-02-15 16:32:09', '2023-02-15 16:32:09'),
(4488, 'bd', 'order_summary', 'অর্ডার সারাংশ', '2023-02-15 16:32:09', '2023-02-15 16:32:09'),
(4489, 'bd', 'subtotal', 'সাবটোটাল', '2023-02-15 16:32:09', '2023-02-15 16:32:09'),
(4490, 'bd', 'total_payable', 'মোট প্রদেয়', '2023-02-15 16:32:09', '2023-02-15 16:32:09'),
(4491, 'bd', 'select_delivery_status', 'ডেলিভারি স্থিতি নির্বাচন করুন', '2023-02-15 16:32:09', '2023-02-15 16:32:09'),
(4492, 'bd', 'select_payment_status', 'অর্থপ্রদানের স্থিতি নির্বাচন করুন', '2023-02-15 16:32:09', '2023-02-15 16:32:09'),
(4493, 'bd', 'are_you_sure_to_cancel__this_item', 'আপনি এই আইটেমটি বাতিল করতে নিশ্চিত', '2023-02-15 16:32:09', '2023-02-15 16:32:09'),
(4494, 'bd', 'shipping_label', 'প্রেরণ বার্তা', '2023-02-15 16:32:09', '2023-02-15 16:32:09'),
(4495, 'bd', 'order_status_updated_successfully', 'অর্ডার স্থিতি সফলভাবে আপডেট করা হয়েছে', '2023-02-15 16:32:09', '2023-02-15 16:32:09'),
(4496, 'bd', 'order_accept_successfully', 'অর্ডার সফলভাবে গ্রহণ', '2023-02-15 16:32:09', '2023-02-15 16:32:09'),
(4497, 'bd', 'no_account_found_associate_this_email', 'এই ইমেলের সাথে কোনো অ্যাকাউন্ট পাওয়া যায়নি', '2023-02-15 16:32:09', '2023-02-15 16:32:09'),
(4498, 'bd', 'payment_failed', 'পেমেন্ট ব্যর্থ হয়েছে', '2023-02-15 16:32:09', '2023-02-15 16:32:09'),
(4499, 'bd', 'payment_error', 'পেমেন্ট ত্রুটি', '2023-02-15 16:32:09', '2023-02-15 16:32:09'),
(4500, 'bd', 'payment_failed_with_stripe_please_try_again', 'অনুগ্রহপূর্বক আবার চেষ্টা করুন.', '2023-02-15 16:32:09', '2023-02-15 16:32:09'),
(4501, 'bd', 'back_to_home', 'বাড়িতে ফিরে যাও', '2023-02-15 16:32:09', '2023-02-15 16:32:09'),
(4502, 'bd', 'shipping_profile_updated_successfully', 'শিপিং প্রোফাইল সফলভাবে আপডেট করা হয়েছে', '2023-02-15 16:32:09', '2023-02-15 16:32:09'),
(4503, 'bd', 'coupon_code_is_required', 'কুপন কোড প্রয়োজন', '2023-02-15 16:32:09', '2023-02-15 16:32:09'),
(4504, 'bd', 'code_is_already_taken', 'কোড ইতিমধ্যে নেওয়া হয়েছে', '2023-02-15 16:32:09', '2023-02-15 16:32:09'),
(4505, 'bd', 'coupon_created_successfully', 'কুপন সফলভাবে তৈরি করা হয়েছে', '2023-02-15 16:32:09', '2023-02-15 16:32:09'),
(4506, 'bd', 'flat_discount', 'ফ্ল্যাট ডিসকাউন্ট', '2023-02-15 16:32:09', '2023-02-15 16:32:09'),
(4507, 'bd', 'edit_coupon', 'কুপন সম্পাদনা করুন', '2023-02-15 16:32:09', '2023-02-15 16:32:09'),
(4508, 'bd', 'coupon_updated_successfully', 'কুপন সফলভাবে আপডেট করা হয়েছে', '2023-02-15 16:32:09', '2023-02-15 16:32:09'),
(4509, 'bd', 'new', 'নতুন', '2023-02-15 16:32:09', '2023-02-15 16:32:09'),
(4510, 'bd', 'stripe_payment', 'স্ট্রাইপ পেমেন্ট', '2023-02-15 16:32:09', '2023-02-15 16:32:09'),
(4511, 'bd', 'don_not_close_the_tab_the_payment_is_being_processed', 'পেমেন্ট প্রক্রিয়া করা হচ্ছে', '2023-02-15 16:32:09', '2023-02-15 16:32:09'),
(4512, 'bd', 'please_select_a_product', 'একটি পণ্য নির্বাচন করুন', '2023-02-15 16:32:09', '2023-02-15 16:32:09'),
(4513, 'bd', 'please_select_delivery_status', 'ডেলিভারি স্থিতি নির্বাচন করুন', '2023-02-15 16:32:09', '2023-02-15 16:32:09'),
(4514, 'bd', 'new_reason_added_successfully', 'নতুন কারণ সফলভাবে যোগ করা হয়েছে', '2023-02-15 16:32:09', '2023-02-15 16:32:09'),
(4515, 'bd', 'reason_deleted_successfully', 'কারণ সফলভাবে মুছে ফেলা হয়েছে', '2023-02-15 16:32:09', '2023-02-15 16:32:09'),
(4516, 'bd', 'edit_refund_reason', 'রিফান্ডের কারণ সম্পাদনা করুন', '2023-02-15 16:32:09', '2023-02-15 16:32:09'),
(4517, 'bd', 'reason_updated_successfully', 'কারণ সফলভাবে আপডেট করা হয়েছে', '2023-02-15 16:32:09', '2023-02-15 16:32:09'),
(4518, 'bd', 'review', 'পুনঃমূল্যায়ন', '2023-02-15 16:32:09', '2023-02-15 16:32:09'),
(4519, 'bd', 'images', 'ছবি', '2023-02-15 16:32:09', '2023-02-15 16:32:09'),
(4520, 'bd', 'review_deleted_successfully', 'পর্যালোচনা সফলভাবে মুছে ফেলা হয়েছে', '2023-02-15 16:32:09', '2023-02-15 16:32:09'),
(4521, 'sa', 'plugin_inactive_successfully', 'البرنامج المساعد غير نشط بنجاح', '2023-02-15 16:34:24', '2023-02-15 16:34:24'),
(4522, 'sa', 'customer_delete_successfully', 'حذف العميل بنجاح', '2023-02-15 16:34:24', '2023-02-15 16:34:24'),
(4523, 'sa', 'your_account_is_inactive', 'حسابك غير نشط', '2023-02-15 16:34:24', '2023-02-15 16:34:24'),
(4524, 'sa', 'not_authentic', 'غير أصلية', '2023-02-15 16:34:24', '2023-02-15 16:34:24'),
(4525, 'sa', 'not_available', 'غير متاح', '2023-02-15 16:34:24', '2023-02-15 16:34:24'),
(4526, 'sa', 'user_created_successfully', 'تم إنشاء المستخدم بنجاح', '2023-02-15 16:34:24', '2023-02-15 16:34:24'),
(4527, 'sa', 'unsuccessful_attempt', 'محاولة فاشلة', '2023-02-15 16:34:24', '2023-02-15 16:34:24'),
(4528, 'sa', 'role_name_is_required', 'اسم الدور مطلوب', '2023-02-15 16:34:24', '2023-02-15 16:34:24'),
(4529, 'sa', 'role_name_already_exists', 'اسم الدور موجود بالفعل', '2023-02-15 16:34:24', '2023-02-15 16:34:24'),
(4530, 'sa', 'role_permission_required', 'مطلوب إذن الدور', '2023-02-15 16:34:24', '2023-02-15 16:34:24'),
(4531, 'sa', 'role_added_successfully', 'تمت إضافة الدور بنجاح', '2023-02-15 16:34:24', '2023-02-15 16:34:24'),
(4532, 'sa', 'user_updated_successfully', 'تم تحديث المستخدم بنجاح', '2023-02-15 16:34:24', '2023-02-15 16:34:24'),
(4533, 'sa', 'install_or_update_theme', 'تثبيت أو تحديث الموضوع', '2023-02-15 16:34:24', '2023-02-15 16:34:24'),
(4534, 'sa', 'purchase_code', 'كود شراء', '2023-02-15 16:34:24', '2023-02-15 16:34:24'),
(4535, 'sa', 'zip_file', 'ملف مضغوط', '2023-02-15 16:34:24', '2023-02-15 16:34:24'),
(4536, 'sa', 'installupdate', 'تثبيت التحديث', '2023-02-15 16:34:24', '2023-02-15 16:34:24'),
(4537, 'sa', 'tag_publish_status_changed_successfully', 'تم تغيير حالة نشر العلامة بنجاح', '2023-02-15 16:34:24', '2023-02-15 16:34:24'),
(4538, 'sa', 'please_enter_a_valid_number_for_comment_close_days', 'الرجاء إدخال رقم صالح ليوم إغلاق التعليق.', '2023-02-15 16:34:24', '2023-02-15 16:34:24'),
(4539, 'sa', 'the_minimum_number_for_comment_close_days_is_1', 'الحد الأدنى لعدد أيام إغلاق التعليقات هو 1', '2023-02-15 16:34:24', '2023-02-15 16:34:24'),
(4540, 'sa', 'please_select_a_valid_option_for_comment_threads_level', 'يرجى تحديد خيار صالح لمستوى مواضيع التعليق', '2023-02-15 16:34:24', '2023-02-15 16:34:24'),
(4541, 'sa', 'please_enter_a_valid_number_for_per_page_comment', 'الرجاء إدخال رقم صحيح للتعليق على كل صفحة', '2023-02-15 16:34:24', '2023-02-15 16:34:24'),
(4542, 'sa', 'the_minimum_comments_for_per_page_is_8', 'الحد الأدنى من التعليقات لكل صفحة هو 8', '2023-02-15 16:34:24', '2023-02-15 16:34:24'),
(4543, 'sa', 'please_select_a_valid_option_for_default_comment_page', 'الرجاء تحديد خيار صالح لصفحة التعليق الافتراضية', '2023-02-15 16:34:24', '2023-02-15 16:34:24'),
(4544, 'sa', 'please_select_a_valid_option_for_comment_order', 'الرجاء تحديد خيار صالح لترتيب التعليق', '2023-02-15 16:34:24', '2023-02-15 16:34:24'),
(4545, 'sa', 'please_enter_a_valid_number_for_comment_links', 'الرجاء إدخال رقم صحيح لارتباطات التعليق', '2023-02-15 16:34:24', '2023-02-15 16:34:24'),
(4546, 'sa', 'the_minimum_comment_links_number_must_be_1', 'يجب أن يكون الحد الأدنى لرقم روابط التعليق هو 1', '2023-02-15 16:34:24', '2023-02-15 16:34:24'),
(4547, 'sa', 'please_select_a_valid_default_avatar', 'يرجى تحديد شخصية افتراضية صالحة', '2023-02-15 16:34:24', '2023-02-15 16:34:24'),
(4548, 'sa', 'comment_settings_updated_successfully', 'تم تحديث إعدادات التعليق بنجاح', '2023-02-15 16:34:24', '2023-02-15 16:34:24'),
(4549, 'sa', 'attribute_status_update_successfully', 'تم تحديث حالة السمة بنجاح', '2023-02-15 16:34:24', '2023-02-15 16:34:24'),
(4550, 'sa', 'available_wallet_balance', 'رصيد المحفظة المتاح', '2023-02-15 16:34:24', '2023-02-15 16:34:24'),
(4551, 'sa', 'add_money', 'إضافة المال', '2023-02-15 16:34:24', '2023-02-15 16:34:24'),
(4552, 'sa', 'payment_option', 'خيار الدفع', '2023-02-15 16:34:24', '2023-02-15 16:34:24'),
(4553, 'sa', 'enter_name', 'أدخل الاسم', '2023-02-15 16:34:24', '2023-02-15 16:34:24'),
(4554, 'sa', 'enter_code', 'ادخل الرمز', '2023-02-15 16:34:24', '2023-02-15 16:34:24'),
(4555, 'sa', 'reason_is_required', 'السبب مطلوب', '2023-02-15 16:34:24', '2023-02-15 16:34:24'),
(4556, 'sa', 'install_or_update_plugin', 'تثبيت أو تحديث البرنامج المساعد', '2023-02-15 16:34:24', '2023-02-15 16:34:24'),
(4557, 'sa', 'user_deleted_successfully', 'تم حذف المستخدم بنجاح', '2023-02-15 16:34:24', '2023-02-15 16:34:24'),
(4558, 'sa', 'theme_activate_successfully', 'تم تفعيل الموضوع بنجاح', '2023-02-15 16:34:24', '2023-02-15 16:34:24'),
(4559, 'sa', 'error', 'خطأ', '2023-02-15 16:34:24', '2023-02-15 16:34:24'),
(4560, 'sa', 'you_have_no_unread_notification', 'ليس لديك إشعار غير مقروء', '2023-02-15 16:34:24', '2023-02-15 16:34:24'),
(4561, 'sa', 'new_shipping_profile_created_successfully', 'تم إنشاء ملف شحن جديد بنجاح', '2023-02-15 16:34:24', '2023-02-15 16:34:24'),
(4562, 'sa', 'manage_profile', 'إدارة الملف الشخصي', '2023-02-15 16:34:24', '2023-02-15 16:34:24'),
(4563, 'sa', 'manage_products', 'إدارة المنتجات', '2023-02-15 16:34:24', '2023-02-15 16:34:24'),
(4564, 'sa', 'update_location', 'تحديث الموقع', '2023-02-15 16:34:24', '2023-02-15 16:34:24'),
(4565, 'sa', 'shipping_to', 'يشحن إلى', '2023-02-15 16:34:24', '2023-02-15 16:34:24'),
(4566, 'sa', 'create_shipping_zone', 'إنشاء منطقة الشحن', '2023-02-15 16:34:24', '2023-02-15 16:34:24'),
(4567, 'sa', 'no_shipping_zone_availble', 'لا توجد منطقة شحن متاحة', '2023-02-15 16:34:24', '2023-02-15 16:34:24'),
(4568, 'sa', 'create_new_shipping_zone', 'إنشاء منطقة شحن جديدة', '2023-02-15 16:34:24', '2023-02-15 16:34:24'),
(4569, 'sa', 'zone_name', 'اسم المنطقة', '2023-02-15 16:34:24', '2023-02-15 16:34:24'),
(4570, 'sa', 'not_visible_to_customers', 'غير مرئي للعملاء', '2023-02-15 16:34:24', '2023-02-15 16:34:24'),
(4571, 'sa', 'shipping_zone_information', 'معلومات منطقة الشحن', '2023-02-15 16:34:24', '2023-02-15 16:34:24'),
(4572, 'sa', 'add_new_rate', 'إضافة سعر جديد', '2023-02-15 16:34:24', '2023-02-15 16:34:24'),
(4573, 'sa', 'own_rate', 'السعر الخاص', '2023-02-15 16:34:24', '2023-02-15 16:34:24'),
(4574, 'sa', 'carrier_rate', 'سعر الناقل', '2023-02-15 16:34:24', '2023-02-15 16:34:24'),
(4575, 'sa', 'no_shipping_time_found', 'لم يتم العثور على وقت الشحن', '2023-02-15 16:34:24', '2023-02-15 16:34:24'),
(4576, 'sa', 'carrier', 'الناقل', '2023-02-15 16:34:24', '2023-02-15 16:34:24'),
(4577, 'sa', 'no_carrier_found', 'لم يتم العثور على ناقل', '2023-02-15 16:34:24', '2023-02-15 16:34:24'),
(4578, 'sa', 'add_new_carrier', 'إضافة شركة نقل جديدة', '2023-02-15 16:34:24', '2023-02-15 16:34:24'),
(4579, 'sa', 'shipped_by', 'تم الشحن بواسطة', '2023-02-15 16:34:24', '2023-02-15 16:34:24'),
(4580, 'sa', 'select_a_medium', 'اختر وسيط', '2023-02-15 16:34:24', '2023-02-15 16:34:24'),
(4581, 'sa', 'air_freight', 'الشحن الجوي', '2023-02-15 16:34:24', '2023-02-15 16:34:24'),
(4582, 'sa', 'ocean_freight', 'الشحن البحري', '2023-02-15 16:34:24', '2023-02-15 16:34:24'),
(4583, 'sa', 'rail_freight', 'النقل بالسكك الحديدية', '2023-02-15 16:34:24', '2023-02-15 16:34:24'),
(4584, 'sa', 'road_freight', 'شحن بري', '2023-02-15 16:34:24', '2023-02-15 16:34:24'),
(4585, 'sa', 'minimum_volumetric_weight', 'الوزن الحجمي الأدنى', '2023-02-15 16:34:24', '2023-02-15 16:34:24'),
(4586, 'sa', 'maximum_volumetric_weight', 'الوزن الحجمي الأقصى', '2023-02-15 16:34:24', '2023-02-15 16:34:24'),
(4587, 'sa', 'shipping_cost', 'تكلفة الشحن', '2023-02-15 16:34:24', '2023-02-15 16:34:24'),
(4588, 'sa', 'kg', 'كلغ', '2023-02-15 16:34:24', '2023-02-15 16:34:24'),
(4589, 'sa', 'add_new_range', 'أضف نطاقًا جديدًا', '2023-02-15 16:34:24', '2023-02-15 16:34:24'),
(4590, 'sa', 'rate_name', 'قيم الاسم', '2023-02-15 16:34:24', '2023-02-15 16:34:24'),
(4591, 'sa', 'remove_condition', 'إزالة الشرط', '2023-02-15 16:34:24', '2023-02-15 16:34:24'),
(4592, 'sa', 'based_on_item_weight', 'بناءً على وزن المنتج', '2023-02-15 16:34:24', '2023-02-15 16:34:24'),
(4593, 'sa', 'based_on_order_price', 'بناء على سعر الطلب', '2023-02-15 16:34:24', '2023-02-15 16:34:24'),
(4594, 'sa', 'minimum_weight', 'الوزن الأدنى', '2023-02-15 16:34:24', '2023-02-15 16:34:24'),
(4595, 'sa', 'maximum_weight', 'الحد الأقصى للوزن', '2023-02-15 16:34:24', '2023-02-15 16:34:24'),
(4596, 'sa', 'minimum_price', 'سعر الحد الأدنى', '2023-02-15 16:34:24', '2023-02-15 16:34:24'),
(4597, 'sa', 'maximum_price', 'السعر الأقصى', '2023-02-15 16:34:24', '2023-02-15 16:34:24'),
(4598, 'sa', 'update_rate', 'معدل التحديث', '2023-02-15 16:34:24', '2023-02-15 16:34:24'),
(4599, 'sa', 'select_shipping_time', 'حدد وقت الشحن', '2023-02-15 16:34:24', '2023-02-15 16:34:24'),
(4600, 'sa', 'product_list_updated_successfully', 'تم تحديث قائمة المنتجات بنجاح', '2023-02-15 16:34:24', '2023-02-15 16:34:24'),
(4601, 'sa', 'please_select_a_profile', 'الرجاء تحديد ملف تعريف', '2023-02-15 16:34:24', '2023-02-15 16:34:24'),
(4602, 'sa', 'new_zone_created_successfully', 'تم إنشاء منطقة جديدة بنجاح', '2023-02-15 16:34:24', '2023-02-15 16:34:24'),
(4603, 'sa', 'edit_zone', 'تحرير المنطقة', '2023-02-15 16:34:24', '2023-02-15 16:34:24'),
(4604, 'sa', 'delete_zone', 'حذف المنطقة', '2023-02-15 16:34:24', '2023-02-15 16:34:24'),
(4605, 'sa', 'no_rates_customers_in_this_zone_wont_be_able_to_complete_checkout', 'لن يتمكن العملاء في هذه المنطقة من إتمام عملية الدفع', '2023-02-15 16:34:24', '2023-02-15 16:34:24'),
(4606, 'sa', 'shipping_cost_is_required', 'تكلفة الشحن مطلوبة', '2023-02-15 16:34:24', '2023-02-15 16:34:24'),
(4607, 'sa', 'please_select_a_carrier', 'الرجاء تحديد شركة اتصالات', '2023-02-15 16:34:24', '2023-02-15 16:34:24'),
(4608, 'sa', 'please_select_a_shipping_medium', 'الرجاء تحديد وسيلة الشحن', '2023-02-15 16:34:24', '2023-02-15 16:34:24'),
(4609, 'sa', 'new_shipping_rate_created_successfully', 'تم إنشاء سعر الشحن الجديد بنجاح', '2023-02-15 16:34:24', '2023-02-15 16:34:24'),
(4610, 'sa', 'own_rates', 'الأسعار الخاصة', '2023-02-15 16:34:24', '2023-02-15 16:34:24'),
(4611, 'sa', 'carrier_rates', 'أسعار الناقل', '2023-02-15 16:34:24', '2023-02-15 16:34:24'),
(4612, 'sa', 'edit_rate', 'معدل التحرير', '2023-02-15 16:34:24', '2023-02-15 16:34:24'),
(4613, 'sa', 'delete_rate', 'معدل الحذف', '2023-02-15 16:34:24', '2023-02-15 16:34:24'),
(4614, 'sa', 'no_carrier_rates', 'لا توجد أسعار شركات النقل', '2023-02-15 16:34:24', '2023-02-15 16:34:24'),
(4615, 'sa', 'delete_profile', 'حذف الملف الشخصي', '2023-02-15 16:34:24', '2023-02-15 16:34:24'),
(4616, 'sa', 'shipping_zone', 'منطقة الشحن', '2023-02-15 16:34:24', '2023-02-15 16:34:24'),
(4617, 'sa', 'minimum_time_is_required', 'الحد الأدنى من الوقت المطلوب', '2023-02-15 16:34:24', '2023-02-15 16:34:24'),
(4618, 'sa', 'minimum_time_unit_is_required', 'الحد الأدنى من الوحدة الزمنية المطلوبة', '2023-02-15 16:34:24', '2023-02-15 16:34:24'),
(4619, 'sa', 'maximum_time_is_required', 'الحد الأقصى للوقت المطلوب', '2023-02-15 16:34:24', '2023-02-15 16:34:24'),
(4620, 'sa', 'maximum_time_unit_is_required', 'مطلوب وحدة زمنية قصوى', '2023-02-15 16:34:24', '2023-02-15 16:34:24'),
(4621, 'sa', 'failed_create_shipping_time', 'فشل إنشاء وقت الشحن', '2023-02-15 16:34:24', '2023-02-15 16:34:24'),
(4622, 'sa', 'shipping_rate_updated_successfully', 'تم تحديث سعر الشحن بنجاح', '2023-02-15 16:34:24', '2023-02-15 16:34:24'),
(4623, 'sa', 'tax_zone', 'المنطقة الضريبية', '2023-02-15 16:34:24', '2023-02-15 16:34:24'),
(4624, 'sa', 'base_tax', 'الضريبة الأساسية', '2023-02-15 16:34:24', '2023-02-15 16:34:24'),
(4625, 'sa', 'custom_taxes', 'الضرائب الجمركية', '2023-02-15 16:34:24', '2023-02-15 16:34:24'),
(4626, 'sa', 'new_custom_tax', 'ضريبة مخصصة جديدة', '2023-02-15 16:34:24', '2023-02-15 16:34:24'),
(4627, 'sa', 'tax_rate', 'معدل الضريبة', '2023-02-15 16:34:24', '2023-02-15 16:34:24'),
(4628, 'sa', 'add_new_tax', 'أضف ضريبة جديدة', '2023-02-15 16:34:24', '2023-02-15 16:34:24'),
(4629, 'sa', 'you_can_select_an_existing_collection_or_', 'يمكنك تحديد مجموعة موجودة أو', '2023-02-15 16:34:24', '2023-02-15 16:34:24'),
(4630, 'sa', 'create_new_collection', 'إنشاء مجموعة جديدة', '2023-02-15 16:34:24', '2023-02-15 16:34:24'),
(4631, 'sa', 'select_state', 'اختر ولايه', '2023-02-15 16:34:24', '2023-02-15 16:34:24'),
(4632, 'sa', 'update_tax_rate', 'تحديث معدل الضريبة', '2023-02-15 16:34:24', '2023-02-15 16:34:24'),
(4633, 'sa', 'save_change', 'حفظ التغيير', '2023-02-15 16:34:24', '2023-02-15 16:34:24'),
(4634, 'sa', 'zone_base_tax_updated', 'تم تحديث ضريبة المنطقة الأساسية', '2023-02-15 16:34:24', '2023-02-15 16:34:24'),
(4635, 'sa', 'order_details', 'تفاصيل الطلب', '2023-02-15 16:34:24', '2023-02-15 16:34:24'),
(4636, 'sa', 'print_shipping_label', 'إطبع ملصق الشحن', '2023-02-15 16:34:24', '2023-02-15 16:34:24'),
(4637, 'sa', 'print_invoice', 'فاتورة طباعة', '2023-02-15 16:34:24', '2023-02-15 16:34:24'),
(4638, 'sa', 'accept_order', 'قبول الطلب', '2023-02-15 16:34:25', '2023-02-15 16:34:25'),
(4639, 'sa', 'cancel_order', 'الغاء الطلب', '2023-02-15 16:34:25', '2023-02-15 16:34:25'),
(4640, 'sa', 'paid_by', 'مدفوعة', '2023-02-15 16:34:25', '2023-02-15 16:34:25'),
(4641, 'sa', 'shipping_info', 'معلومات الشحن', '2023-02-15 16:34:25', '2023-02-15 16:34:25'),
(4642, 'sa', 'billing_info', 'معلومات الفواتير', '2023-02-15 16:34:25', '2023-02-15 16:34:25'),
(4643, 'sa', 'package', 'طَرد', '2023-02-15 16:34:25', '2023-02-15 16:34:25'),
(4644, 'sa', '_shipped', 'شحنها', '2023-02-15 16:34:25', '2023-02-15 16:34:25'),
(4645, 'sa', 'order_note', 'مذكرة النظام', '2023-02-15 16:34:25', '2023-02-15 16:34:25'),
(4646, 'sa', 'order_summary', 'ملخص الطلب', '2023-02-15 16:34:25', '2023-02-15 16:34:25'),
(4647, 'sa', 'subtotal', 'المجموع الفرعي', '2023-02-15 16:34:25', '2023-02-15 16:34:25'),
(4648, 'sa', 'total_payable', 'إجمالي المدفوعات', '2023-02-15 16:34:25', '2023-02-15 16:34:25'),
(4649, 'sa', 'select_delivery_status', 'حدد حالة التسليم', '2023-02-15 16:34:25', '2023-02-15 16:34:25'),
(4650, 'sa', 'select_payment_status', 'حدد حالة الدفع', '2023-02-15 16:34:25', '2023-02-15 16:34:25'),
(4651, 'sa', 'are_you_sure_to_cancel__this_item', 'هل أنت متأكد من إلغاء هذا البند', '2023-02-15 16:34:25', '2023-02-15 16:34:25'),
(4652, 'sa', 'shipping_label', 'بطاقة شحن', '2023-02-15 16:34:25', '2023-02-15 16:34:25'),
(4653, 'sa', 'order_status_updated_successfully', 'تم تحديث حالة الطلب بنجاح', '2023-02-15 16:34:25', '2023-02-15 16:34:25'),
(4654, 'sa', 'order_accept_successfully', 'قبول الطلب بنجاح', '2023-02-15 16:34:25', '2023-02-15 16:34:25'),
(4655, 'sa', 'no_account_found_associate_this_email', 'لم يتم العثور على حساب يقرن هذا البريد الإلكتروني', '2023-02-15 16:34:25', '2023-02-15 16:34:25'),
(4656, 'sa', 'payment_failed', 'عملية الدفع فشلت', '2023-02-15 16:34:25', '2023-02-15 16:34:25'),
(4657, 'sa', 'payment_error', 'خطأ الدفع', '2023-02-15 16:34:25', '2023-02-15 16:34:25'),
(4658, 'sa', 'payment_failed_with_stripe_please_try_again', 'حاول مرة اخرى.', '2023-02-15 16:34:25', '2023-02-15 16:34:25'),
(4659, 'sa', 'back_to_home', 'العودة إلى المنزل', '2023-02-15 16:34:25', '2023-02-15 16:34:25'),
(4660, 'sa', 'shipping_profile_updated_successfully', 'تم تحديث ملف الشحن بنجاح', '2023-02-15 16:34:25', '2023-02-15 16:34:25'),
(4661, 'sa', 'coupon_code_is_required', 'كود القسيمة مطلوب', '2023-02-15 16:34:25', '2023-02-15 16:34:25'),
(4662, 'sa', 'code_is_already_taken', 'تم أخذ الرمز بالفعل', '2023-02-15 16:34:25', '2023-02-15 16:34:25'),
(4663, 'sa', 'coupon_created_successfully', 'تم إنشاء القسيمة بنجاح', '2023-02-15 16:34:25', '2023-02-15 16:34:25'),
(4664, 'sa', 'flat_discount', 'خصم ثابت', '2023-02-15 16:34:25', '2023-02-15 16:34:25'),
(4665, 'sa', 'edit_coupon', 'تحرير القسيمة', '2023-02-15 16:34:25', '2023-02-15 16:34:25'),
(4666, 'sa', 'coupon_updated_successfully', 'تم تحديث القسيمة بنجاح', '2023-02-15 16:34:25', '2023-02-15 16:34:25'),
(4667, 'sa', 'new', 'جديد', '2023-02-15 16:34:25', '2023-02-15 16:34:25'),
(4668, 'sa', 'stripe_payment', 'دفع الشريط', '2023-02-15 16:34:25', '2023-02-15 16:34:25'),
(4669, 'sa', 'don_not_close_the_tab_the_payment_is_being_processed', 'جاري معالجة الدفع', '2023-02-15 16:34:25', '2023-02-15 16:34:25'),
(4670, 'sa', 'please_select_a_product', 'الرجاء اختيار منتج', '2023-02-15 16:34:25', '2023-02-15 16:34:25'),
(4671, 'sa', 'please_select_delivery_status', 'الرجاء تحديد حالة التسليم', '2023-02-15 16:34:25', '2023-02-15 16:34:25'),
(4672, 'sa', 'new_reason_added_successfully', 'تمت إضافة سبب جديد بنجاح', '2023-02-15 16:34:25', '2023-02-15 16:34:25'),
(4673, 'sa', 'reason_deleted_successfully', 'تم حذف السبب بنجاح', '2023-02-15 16:34:25', '2023-02-15 16:34:25'),
(4674, 'sa', 'edit_refund_reason', 'تحرير سبب رد الأموال', '2023-02-15 16:34:25', '2023-02-15 16:34:25'),
(4675, 'sa', 'reason_updated_successfully', 'تم تحديث السبب بنجاح', '2023-02-15 16:34:25', '2023-02-15 16:34:25'),
(4676, 'sa', 'review', 'مراجعة', '2023-02-15 16:34:25', '2023-02-15 16:34:25'),
(4677, 'sa', 'images', 'الصور', '2023-02-15 16:34:25', '2023-02-15 16:34:25'),
(4678, 'sa', 'review_deleted_successfully', 'تم حذف المراجعة بنجاح', '2023-02-15 16:34:25', '2023-02-15 16:34:25'),
(4679, 'en', 'product_shipping_cost_and_shipping_time_depends_on_shipping_profile', 'Product shipping cost and shipping time depends on shipping profile', '2023-02-15 21:57:57', '2023-02-15 21:57:57'),
(4680, 'en', 'edit_language', 'Edit Language', '2023-02-15 22:38:58', '2023-02-15 22:38:58'),
(4681, 'en', 'update_language_information', 'Update Language Information', '2023-02-15 22:38:58', '2023-02-15 22:38:58'),
(4682, 'en', 'language_updated_successfully', 'Language updated successfully', '2023-02-15 22:39:21', '2023-02-15 22:39:21'),
(4683, 'en', 'manage_widgets', 'Manage Widgets', '2023-02-16 15:31:43', '2023-02-16 15:31:43'),
(4684, 'en', 'pickup_point_created_successfully', 'Pickup point created successfully', '2023-02-16 16:37:16', '2023-02-16 16:37:16'),
(4685, 'en', 'product_featured_status_updated_successfully', 'Product featured status updated successfully', '2023-02-16 17:25:15', '2023-02-16 17:25:15'),
(4686, 'en', 'blog_category', 'Blog Category', '2023-02-16 17:48:31', '2023-02-16 17:48:31'),
(4687, 'en', 'tag', 'Tag', '2023-02-16 17:49:30', '2023-02-16 17:49:30'),
(4688, 'en', 'blog_comment', 'Blog Comment', '2023-02-16 17:50:54', '2023-02-16 17:50:54'),
(4689, 'en', 'enable_threaded_nested_comments', 'Enable threaded (nested) comments', '2023-02-16 17:53:20', '2023-02-16 17:53:20'),
(4690, 'en', 'levels_deep', 'levels deep', '2023-02-16 17:53:20', '2023-02-16 17:53:20'),
(4691, 'en', 'page', 'Page', '2023-02-16 17:54:23', '2023-02-16 17:54:23'),
(4692, 'en', 'plugin_error', 'Plugin error', '2023-02-16 20:42:58', '2023-02-16 20:42:58'),
(4693, 'en', 'return_reason_is_required', 'Return reason is required', '2023-02-16 21:02:22', '2023-02-16 21:02:22'),
(4694, 'en', 'this_item_has_been_cancelled', 'This item has been cancelled', '2023-02-16 21:03:20', '2023-02-16 21:03:20'),
(4695, 'en', 'url_is_required', 'Url is required', '2023-02-16 22:38:28', '2023-02-16 22:38:28'),
(4696, 'en', 'note', 'Note', '2023-02-18 15:41:57', '2023-02-18 15:41:57'),
(4697, 'en', 'attachements', 'Attachements', '2023-02-18 15:41:57', '2023-02-18 15:41:57'),
(4698, 'en', 'request_status_successfully', 'Request status successfully', '2023-02-18 15:41:57', '2023-02-18 15:41:57'),
(4699, 'en', 'request_status_update_failed', 'Request status update failed', '2023-02-18 15:41:57', '2023-02-18 15:41:57'),
(4700, 'en', 'refund_amount', 'Refund Amount', '2023-02-18 15:42:03', '2023-02-18 15:42:03'),
(4701, 'en', 'refund_request_redetails', 'Refund Request Redetails', '2023-02-18 15:42:10', '2023-02-18 15:42:10'),
(4702, 'en', 'update_request_status', 'Update Request status', '2023-02-18 15:42:10', '2023-02-18 15:42:10'),
(4703, 'en', 'request_details', 'Request Details', '2023-02-18 15:42:10', '2023-02-18 15:42:10'),
(4704, 'en', 'refunded_amount', 'Refunded Amount', '2023-02-18 15:42:10', '2023-02-18 15:42:10'),
(4705, 'en', 'return_approved', 'Return Approved', '2023-02-18 15:42:10', '2023-02-18 15:42:10'),
(4706, 'en', 'status_update', 'Status Update', '2023-02-18 15:42:10', '2023-02-18 15:42:10'),
(4707, 'en', 'coupon_not_found', 'Coupon Not Found', '2023-02-20 21:02:55', '2023-02-20 21:02:55'),
(4708, 'en', 'enter_a_valid_coupon', 'Enter a valid coupon', '2023-02-20 21:04:46', '2023-02-20 21:04:46'),
(4709, 'en', 'zone', 'Zone', '2023-02-20 21:07:26', '2023-02-20 21:07:26'),
(4710, 'en', 'select_zone', 'Select Zone', '2023-02-20 21:07:34', '2023-02-20 21:07:34'),
(4711, 'en', 'select_a_shipping_zone', 'Select a Shipping Zone', '2023-02-20 21:07:34', '2023-02-20 21:07:34'),
(4712, 'en', 'logo_is_required', 'Logo is required', '2023-02-20 22:23:55', '2023-02-20 22:23:55'),
(4713, 'en', 'new_courier_added_successfully', 'New courier added successfully', '2023-02-20 22:40:04', '2023-02-20 22:40:04'),
(4714, 'en', 'courier_status_updated_successfully', 'Courier status updated successfully', '2023-02-20 22:41:50', '2023-02-20 22:41:50'),
(4715, 'en', 'select_a_carrier', 'Select a carrier', '2023-02-20 22:44:47', '2023-02-20 22:44:47'),
(4716, 'en', 'shipping_medium', 'Shipping Medium', '2023-02-20 22:45:47', '2023-02-20 22:45:47'),
(4717, 'en', 'weight_range', 'Weight Range', '2023-02-20 22:45:47', '2023-02-20 22:45:47'),
(4718, 'en', 'postal_code', 'Postal Code', '2023-02-20 23:29:12', '2023-02-20 23:29:12'),
(4719, 'en', 'accepted', 'Accepted', '2023-02-20 23:29:18', '2023-02-20 23:29:18'),
(4720, 'en', 'amount_is_required', 'Amount is required', '2023-02-20 23:29:25', '2023-02-20 23:29:25'),
(4721, 'en', 'invalid_amount', 'Invalid amount', '2023-02-20 23:29:25', '2023-02-20 23:29:25'),
(4722, 'en', 'customer_wallet_updated_successfully', 'Customer wallet updated successfully', '2023-02-20 23:29:26', '2023-02-20 23:29:26'),
(4723, 'en', 'deduct_money', 'Deduct money', '2023-02-20 23:29:26', '2023-02-20 23:29:26'),
(4724, 'en', 'public_key_is_required', 'Public key is required', '2023-02-20 23:41:13', '2023-02-20 23:41:13'),
(4725, 'en', 'public_key_is_required', 'Public key is required', '2023-02-20 23:41:18', '2023-02-20 23:41:18'),
(4726, 'en', 'private_key_is_required', 'Private key is required', '2023-02-20 23:41:19', '2023-02-20 23:41:19'),
(4727, 'en', 'private_key_is_required', 'Private key is required', '2023-02-20 23:41:19', '2023-02-20 23:41:19'),
(4728, 'en', 'guest', 'Guest', '2023-02-22 17:44:13', '2023-02-22 17:44:13'),
(4729, 'en', 'menu_list_updated_successfully', 'Menu list updated successfully', '2023-02-26 21:31:34', '2023-02-26 21:31:34'),
(4730, 'en', 'product_categories', 'Product Categories', '2023-02-26 22:52:09', '2023-02-26 22:52:09'),
(4731, 'en', 'login_activity_log_deleted_successfully', 'Login activity log deleted successfully', '2023-02-28 16:18:56', '2023-02-28 16:18:56'),
(4732, 'en', 'unable_to_delete_login_activity_log', 'Unable to delete login activity log', '2023-02-28 16:19:00', '2023-02-28 16:19:00'),
(4733, 'en', 'mail_driver_is_required', 'Mail driver is required', '2023-03-01 15:06:48', '2023-03-01 15:06:48'),
(4734, 'en', 'smtp_configuration_updated_successfully', 'SMTP configuration updated successfully', '2023-03-01 15:06:48', '2023-03-01 15:06:48'),
(4735, 'en', 'subject_is_required', 'Subject is required', '2023-03-01 15:07:32', '2023-03-01 15:07:32'),
(4736, 'en', 'message_is_required', 'Message is required', '2023-03-01 15:07:32', '2023-03-01 15:07:32'),
(4737, 'en', 'email_sending_failed', 'Email sending failed', '2023-03-01 15:07:40', '2023-03-01 15:07:40'),
(4738, 'en', 'a_new_email_sending_successfully', 'A new email sending successfully', '2023-03-01 15:08:48', '2023-03-01 15:08:48'),
(4739, 'en', 'password_is_too_short', 'Password is too short', '2023-03-01 15:40:04', '2023-03-01 15:40:04'),
(4740, 'en', 'try_forgot_password', 'Try Forgot Password', '2023-03-01 15:54:58', '2023-03-01 15:54:58'),
(4741, 'en', 'send_password_reset________________________________________________________________________link', 'Send Password Reset                                                                        Link', '2023-03-01 15:54:58', '2023-03-01 15:54:58'),
(4742, 'en', 'we_have_emailed_your_password_reset_link', 'We have e-mailed your password reset link', '2023-03-01 15:55:34', '2023-03-01 15:55:34'),
(4743, 'en', 'a_new_comment_added', 'A New Comment Added.', '2023-03-01 15:58:22', '2023-03-01 15:58:22'),
(4744, 'en', 'view_blog', 'View Blog', '2023-03-01 16:14:54', '2023-03-01 16:14:54'),
(4745, 'en', 'comment_added_failed', 'Comment Added Failed', '2023-03-01 16:15:49', '2023-03-01 16:15:49'),
(4746, 'en', 'customer_delete_failed', 'Customer delete failed', '2023-03-01 20:38:48', '2023-03-01 20:38:48'),
(4747, 'en', 'mailchimp_api_key_or_list_id_missing', 'Mailchimp Api key Or List id Missing.', '2023-03-01 20:41:18', '2023-03-01 20:41:18'),
(4748, 'en', 'this_email_is_already_subscribed', 'This Email is already Subscribed', '2023-03-01 20:47:29', '2023-03-01 20:47:29'),
(4749, 'en', 'please_enter_a_valid_email', 'Please Enter a Valid Email', '2023-03-01 20:49:34', '2023-03-01 20:49:34'),
(4750, 'bd', 'product_shipping_cost_and_shipping_time_depends_on_shipping_profile', 'পণ্য শিপিং খরচ এবং শিপিং সময় শিপিং প্রোফাইলের উপর নির্ভর করে', '2023-03-01 21:11:55', '2023-03-01 21:11:55'),
(4751, 'bd', 'edit_language', 'ভাষা সম্পাদনা করুন', '2023-03-01 21:11:55', '2023-03-01 21:11:55'),
(4752, 'bd', 'update_language_information', 'ভাষার তথ্য আপডেট করুন', '2023-03-01 21:11:55', '2023-03-01 21:11:55'),
(4753, 'bd', 'language_updated_successfully', 'ভাষা সফলভাবে আপডেট হয়েছে', '2023-03-01 21:11:55', '2023-03-01 21:11:55'),
(4754, 'bd', 'manage_widgets', 'উইজেট পরিচালনা করুন', '2023-03-01 21:11:55', '2023-03-01 21:11:55'),
(4755, 'bd', 'pickup_point_created_successfully', 'পিকআপ পয়েন্ট সফলভাবে তৈরি করা হয়েছে', '2023-03-01 21:11:55', '2023-03-01 21:11:55'),
(4756, 'bd', 'product_featured_status_updated_successfully', 'পণ্য বৈশিষ্ট্যযুক্ত স্থিতি সফলভাবে আপডেট করা হয়েছে৷', '2023-03-01 21:11:55', '2023-03-01 21:11:55'),
(4757, 'bd', 'blog_category', 'ব্লগ বিভাগ', '2023-03-01 21:11:55', '2023-03-01 21:11:55'),
(4758, 'bd', 'tag', 'ট্যাগ', '2023-03-01 21:11:55', '2023-03-01 21:11:55'),
(4759, 'bd', 'blog_comment', 'ব্লগ মন্তব্য', '2023-03-01 21:11:55', '2023-03-01 21:11:55'),
(4760, 'bd', 'enable_threaded_nested_comments', 'থ্রেডেড (নেস্টেড) মন্তব্য সক্ষম করুন', '2023-03-01 21:11:55', '2023-03-01 21:11:55'),
(4761, 'bd', 'levels_deep', 'গভীর স্তর', '2023-03-01 21:11:55', '2023-03-01 21:11:55'),
(4762, 'bd', 'page', 'পাতা', '2023-03-01 21:11:55', '2023-03-01 21:11:55'),
(4763, 'bd', 'plugin_error', 'প্লাগইন ত্রুটি', '2023-03-01 21:11:55', '2023-03-01 21:11:55'),
(4764, 'bd', 'return_reason_is_required', 'রিটার্ন কারণ প্রয়োজন', '2023-03-01 21:11:55', '2023-03-01 21:11:55'),
(4765, 'bd', 'this_item_has_been_cancelled', 'এই আইটেমটি বাতিল করা হয়েছে', '2023-03-01 21:11:55', '2023-03-01 21:11:55'),
(4766, 'bd', 'url_is_required', 'ইউআরএল প্রয়োজন', '2023-03-01 21:11:55', '2023-03-01 21:11:55'),
(4767, 'bd', 'note', 'বিঃদ্রঃ', '2023-03-01 21:11:55', '2023-03-01 21:11:55'),
(4768, 'bd', 'attachements', 'সংযুক্তি', '2023-03-01 21:13:15', '2023-03-01 21:13:15'),
(4769, 'bd', 'request_status_successfully', 'স্থিতির অনুরোধ সফলভাবে', '2023-03-01 21:13:15', '2023-03-01 21:13:15');
INSERT INTO `tl_translations` (`id`, `lang`, `lang_key`, `lang_value`, `created_at`, `updated_at`) VALUES
(4770, 'bd', 'request_status_update_failed', 'অনুরোধ স্থিতি আপডেট ব্যর্থ হয়েছে', '2023-03-01 21:13:15', '2023-03-01 21:13:15'),
(4771, 'bd', 'refund_amount', 'ফেরতের পরিমাণ', '2023-03-01 21:13:15', '2023-03-01 21:13:15'),
(4772, 'bd', 'refund_request_redetails', 'ফেরত অনুরোধ পুনঃ বিবরণ', '2023-03-01 21:13:15', '2023-03-01 21:13:15'),
(4773, 'bd', 'update_request_status', 'অনুরোধের স্থিতি আপডেট করুন', '2023-03-01 21:13:15', '2023-03-01 21:13:15'),
(4774, 'bd', 'request_details', 'বিস্তারিত অনুরোধ', '2023-03-01 21:13:15', '2023-03-01 21:13:15'),
(4775, 'bd', 'refunded_amount', 'ফেরত দেওয়া পরিমাণ', '2023-03-01 21:13:15', '2023-03-01 21:13:15'),
(4776, 'bd', 'return_approved', 'প্রত্যাবর্তন অনুমোদিত', '2023-03-01 21:13:15', '2023-03-01 21:13:15'),
(4777, 'bd', 'status_update', 'অবস্থা হালনাগাদ', '2023-03-01 21:13:15', '2023-03-01 21:13:15'),
(4778, 'bd', 'coupon_not_found', 'কুপন পাওয়া যায়নি', '2023-03-01 21:13:15', '2023-03-01 21:13:15'),
(4779, 'bd', 'enter_a_valid_coupon', 'একটি বৈধ কুপন লিখুন', '2023-03-01 21:13:15', '2023-03-01 21:13:15'),
(4780, 'bd', 'zone', 'মণ্ডল', '2023-03-01 21:13:15', '2023-03-01 21:13:15'),
(4781, 'bd', 'select_zone', 'জোন নির্বাচন করুন', '2023-03-01 21:13:15', '2023-03-01 21:13:15'),
(4782, 'bd', 'select_a_shipping_zone', 'একটি শিপিং জোন নির্বাচন করুন', '2023-03-01 21:13:15', '2023-03-01 21:13:15'),
(4783, 'bd', 'logo_is_required', 'লোগো প্রয়োজন', '2023-03-01 21:13:15', '2023-03-01 21:13:15'),
(4784, 'bd', 'new_courier_added_successfully', 'নতুন কুরিয়ার সফলভাবে যোগ করা হয়েছে', '2023-03-01 21:13:15', '2023-03-01 21:13:15'),
(4785, 'bd', 'courier_status_updated_successfully', 'কুরিয়ার স্ট্যাটাস সফলভাবে আপডেট করা হয়েছে', '2023-03-01 21:13:15', '2023-03-01 21:13:15'),
(4786, 'bd', 'select_a_carrier', 'একটি ক্যারিয়ার নির্বাচন করুন', '2023-03-01 21:13:15', '2023-03-01 21:13:15'),
(4787, 'bd', 'shipping_medium', 'শিপিং মাধ্যম', '2023-03-01 21:13:15', '2023-03-01 21:13:15'),
(4788, 'bd', 'weight_range', 'ওজন পরিসীমা', '2023-03-01 21:19:50', '2023-03-01 21:19:50'),
(4789, 'bd', 'postal_code', 'পোস্ট অফিসের নাম্বার', '2023-03-01 21:19:50', '2023-03-01 21:19:50'),
(4790, 'bd', 'accepted', 'গৃহীত', '2023-03-01 21:19:50', '2023-03-01 21:19:50'),
(4791, 'bd', 'amount_is_required', 'পরিমাণ প্রয়োজন', '2023-03-01 21:19:50', '2023-03-01 21:19:50'),
(4792, 'bd', 'invalid_amount', 'অকার্যকর পরিমাণ', '2023-03-01 21:19:50', '2023-03-01 21:19:50'),
(4793, 'bd', 'customer_wallet_updated_successfully', 'গ্রাহক ওয়ালেট সফলভাবে আপডেট করা হয়েছে', '2023-03-01 21:19:50', '2023-03-01 21:19:50'),
(4794, 'bd', 'deduct_money', 'টাকা কেটে নিন', '2023-03-01 21:19:50', '2023-03-01 21:19:50'),
(4795, 'bd', 'public_key_is_required', 'সর্বজনীন কী প্রয়োজন', '2023-03-01 21:19:50', '2023-03-01 21:19:50'),
(4796, 'bd', 'private_key_is_required', 'ব্যক্তিগত কী প্রয়োজন', '2023-03-01 21:19:50', '2023-03-01 21:19:50'),
(4797, 'bd', 'guest', 'অতিথি', '2023-03-01 21:19:50', '2023-03-01 21:19:50'),
(4798, 'bd', 'menu_list_updated_successfully', 'মেনু তালিকা সফলভাবে আপডেট করা হয়েছে', '2023-03-01 21:19:50', '2023-03-01 21:19:50'),
(4799, 'bd', 'product_categories', 'পণের ধরন', '2023-03-01 21:19:50', '2023-03-01 21:19:50'),
(4800, 'bd', 'login_activity_log_deleted_successfully', 'লগইন কার্যকলাপ লগ সফলভাবে মুছে ফেলা হয়েছে', '2023-03-01 21:19:50', '2023-03-01 21:19:50'),
(4801, 'bd', 'unable_to_delete_login_activity_log', 'লগইন কার্যকলাপ লগ মুছে ফেলতে অক্ষম', '2023-03-01 21:19:50', '2023-03-01 21:19:50'),
(4802, 'bd', 'mail_driver_is_required', 'মেইল ড্রাইভার প্রয়োজন', '2023-03-01 21:19:50', '2023-03-01 21:19:50'),
(4803, 'bd', 'smtp_configuration_updated_successfully', 'SMTP কনফিগারেশন সফলভাবে আপডেট হয়েছে৷', '2023-03-01 21:19:50', '2023-03-01 21:19:50'),
(4804, 'bd', 'subject_is_required', 'বিষয় আবশ্যক', '2023-03-01 21:19:50', '2023-03-01 21:19:50'),
(4805, 'bd', 'message_is_required', 'বার্তা প্রয়োজন', '2023-03-01 21:19:50', '2023-03-01 21:19:50'),
(4806, 'bd', 'email_sending_failed', 'ইমেল পাঠানো ব্যর্থ হয়েছে', '2023-03-01 21:20:41', '2023-03-01 21:20:41'),
(4807, 'bd', 'a_new_email_sending_successfully', 'একটি নতুন ইমেল সফলভাবে পাঠানো হচ্ছে', '2023-03-01 21:20:41', '2023-03-01 21:20:41'),
(4808, 'bd', 'password_is_too_short', 'পাসওয়ার্ড অত্যন্ত ছোট', '2023-03-01 21:20:41', '2023-03-01 21:20:41'),
(4809, 'bd', 'try_forgot_password', 'ভুলে যাওয়া পাসওয়ার্ড চেষ্টা করুন', '2023-03-01 21:20:41', '2023-03-01 21:20:41'),
(4810, 'bd', 'send_password_reset________________________________________________________________________link', 'পাসওয়ার্ড রিসেট লিঙ্ক পাঠান', '2023-03-01 21:20:41', '2023-03-01 21:20:41'),
(4811, 'bd', 'we_have_emailed_your_password_reset_link', 'আমরা আপনার পাসওয়ার্ড রিসেট লিঙ্ক ই-মেইল করেছি', '2023-03-01 21:20:41', '2023-03-01 21:20:41'),
(4812, 'bd', 'a_new_comment_added', 'একটি নতুন মন্তব্য যোগ করা হয়েছে.', '2023-03-01 21:20:41', '2023-03-01 21:20:41'),
(4813, 'bd', 'view_blog', 'ব্লগ দেখুন', '2023-03-01 21:20:41', '2023-03-01 21:20:41'),
(4814, 'bd', 'comment_added_failed', 'মন্তব্য যোগ করা ব্যর্থ হয়েছে', '2023-03-01 21:20:41', '2023-03-01 21:20:41'),
(4815, 'bd', 'customer_delete_failed', 'গ্রাহক মুছে ফেলতে ব্যর্থ হয়েছে', '2023-03-01 21:20:41', '2023-03-01 21:20:41'),
(4816, 'bd', 'mailchimp_api_key_or_list_id_missing', 'Mailchimp Api কী বা তালিকা List id অনুপস্থিত.', '2023-03-01 21:20:41', '2023-03-01 21:21:02'),
(4817, 'bd', 'this_email_is_already_subscribed', 'এই ইমেলটি ইতিমধ্যেই সাবস্ক্রাইব করা হয়েছে', '2023-03-01 21:20:41', '2023-03-01 21:20:41'),
(4818, 'bd', 'please_enter_a_valid_email', 'একটি বৈধ ইমেইল প্রবেশ করুন', '2023-03-01 21:20:41', '2023-03-01 21:20:41'),
(4819, 'sa', 'product_shipping_cost_and_shipping_time_depends_on_shipping_profile', 'تعتمد تكلفة شحن المنتج ووقت الشحن على ملف تعريف الشحن', '2023-03-01 21:24:17', '2023-03-01 21:24:17'),
(4820, 'sa', 'edit_language', 'تحرير اللغة', '2023-03-01 21:24:17', '2023-03-01 21:24:17'),
(4821, 'sa', 'update_language_information', 'تحديث معلومات اللغة', '2023-03-01 21:24:17', '2023-03-01 21:24:17'),
(4822, 'sa', 'language_updated_successfully', 'تم تحديث اللغة بنجاح', '2023-03-01 21:24:17', '2023-03-01 21:24:17'),
(4823, 'sa', 'manage_widgets', 'إدارة الحاجيات', '2023-03-01 21:24:17', '2023-03-01 21:24:17'),
(4824, 'sa', 'pickup_point_created_successfully', 'تم إنشاء نقطة الالتقاط بنجاح', '2023-03-01 21:24:17', '2023-03-01 21:24:17'),
(4825, 'sa', 'product_featured_status_updated_successfully', 'تم تحديث حالة المنتج المميز بنجاح', '2023-03-01 21:24:17', '2023-03-01 21:24:17'),
(4826, 'sa', 'blog_category', 'فئة المدونة', '2023-03-01 21:24:17', '2023-03-01 21:24:17'),
(4827, 'sa', 'tag', 'بطاقة شعار', '2023-03-01 21:24:17', '2023-03-01 21:24:17'),
(4828, 'sa', 'blog_comment', 'تعليق المدونة', '2023-03-01 21:24:17', '2023-03-01 21:24:17'),
(4829, 'sa', 'enable_threaded_nested_comments', 'تفعيل التعليقات المترابطة (المتداخلة)', '2023-03-01 21:24:17', '2023-03-01 21:24:17'),
(4830, 'sa', 'levels_deep', 'مستويات عميقة', '2023-03-01 21:24:17', '2023-03-01 21:24:17'),
(4831, 'sa', 'page', 'صفحة', '2023-03-01 21:24:17', '2023-03-01 21:24:17'),
(4832, 'sa', 'plugin_error', 'خطأ في البرنامج المساعد', '2023-03-01 21:24:17', '2023-03-01 21:24:17'),
(4833, 'sa', 'return_reason_is_required', 'مطلوب سبب الإرجاع', '2023-03-01 21:24:17', '2023-03-01 21:24:17'),
(4834, 'sa', 'this_item_has_been_cancelled', 'تم إلغاء هذا العنصر', '2023-03-01 21:24:17', '2023-03-01 21:24:17'),
(4835, 'sa', 'url_is_required', 'مطلوب عنوان Url', '2023-03-01 21:24:17', '2023-03-01 21:24:17'),
(4836, 'sa', 'note', 'ملحوظة', '2023-03-01 21:24:17', '2023-03-01 21:24:17'),
(4837, 'sa', 'attachements', 'المرفقات', '2023-03-01 21:24:41', '2023-03-01 21:24:41'),
(4838, 'sa', 'request_status_successfully', 'حالة الطلب بنجاح', '2023-03-01 21:24:41', '2023-03-01 21:24:41'),
(4839, 'sa', 'request_status_update_failed', 'فشل تحديث حالة الطلب', '2023-03-01 21:24:41', '2023-03-01 21:24:41'),
(4840, 'sa', 'refund_amount', 'المبلغ المسترد', '2023-03-01 21:24:41', '2023-03-01 21:24:41'),
(4841, 'sa', 'refund_request_redetails', 'إعادة تفصيل طلب استرداد الأموال', '2023-03-01 21:24:41', '2023-03-01 21:24:41'),
(4842, 'sa', 'update_request_status', 'تحديث حالة الطلب', '2023-03-01 21:24:41', '2023-03-01 21:24:41'),
(4843, 'sa', 'request_details', 'طلب تفاصيل', '2023-03-01 21:24:41', '2023-03-01 21:24:41'),
(4844, 'sa', 'refunded_amount', 'المبلغ المسترد', '2023-03-01 21:24:41', '2023-03-01 21:24:41'),
(4845, 'sa', 'return_approved', 'تمت الموافقة على الإرجاع', '2023-03-01 21:24:41', '2023-03-01 21:24:41'),
(4846, 'sa', 'status_update', 'تحديث الحالة', '2023-03-01 21:24:41', '2023-03-01 21:24:41'),
(4847, 'sa', 'coupon_not_found', 'القسيمة غير موجودة', '2023-03-01 21:24:41', '2023-03-01 21:24:41'),
(4848, 'sa', 'enter_a_valid_coupon', 'أدخل قسيمة صالحة', '2023-03-01 21:24:41', '2023-03-01 21:24:41'),
(4849, 'sa', 'zone', 'منطقة', '2023-03-01 21:24:41', '2023-03-01 21:24:41'),
(4850, 'sa', 'select_zone', 'حدد المنطقة', '2023-03-01 21:24:41', '2023-03-01 21:24:41'),
(4851, 'sa', 'select_a_shipping_zone', 'حدد منطقة الشحن', '2023-03-01 21:24:41', '2023-03-01 21:24:41'),
(4852, 'sa', 'logo_is_required', 'الشعار مطلوب', '2023-03-01 21:24:41', '2023-03-01 21:24:41'),
(4853, 'sa', 'new_courier_added_successfully', 'تمت إضافة ساعي جديد بنجاح', '2023-03-01 21:24:41', '2023-03-01 21:24:41'),
(4854, 'sa', 'courier_status_updated_successfully', 'تم تحديث حالة البريد السريع بنجاح', '2023-03-01 21:24:41', '2023-03-01 21:24:41'),
(4855, 'sa', 'select_a_carrier', 'حدد شركة اتصالات', '2023-03-01 21:24:41', '2023-03-01 21:24:41'),
(4856, 'sa', 'shipping_medium', 'متوسط ​​الشحن', '2023-03-01 21:24:41', '2023-03-01 21:24:41'),
(4857, 'sa', 'weight_range', 'مجموعة الوزن', '2023-03-01 21:25:10', '2023-03-01 21:25:10'),
(4858, 'sa', 'postal_code', 'رمز بريدي', '2023-03-01 21:25:10', '2023-03-01 21:25:10'),
(4859, 'sa', 'accepted', 'قبلت', '2023-03-01 21:25:10', '2023-03-01 21:25:10'),
(4860, 'sa', 'amount_is_required', 'المبلغ مطلوب', '2023-03-01 21:25:10', '2023-03-01 21:25:10'),
(4861, 'sa', 'invalid_amount', 'مبلغ غير صحيح', '2023-03-01 21:25:10', '2023-03-01 21:25:10'),
(4862, 'sa', 'customer_wallet_updated_successfully', 'تم تحديث محفظة العميل بنجاح', '2023-03-01 21:25:10', '2023-03-01 21:25:10'),
(4863, 'sa', 'deduct_money', 'اقتطاع المال', '2023-03-01 21:25:10', '2023-03-01 21:25:10'),
(4864, 'sa', 'public_key_is_required', 'المفتاح العمومي مطلوب', '2023-03-01 21:25:10', '2023-03-01 21:25:10'),
(4865, 'sa', 'private_key_is_required', 'مطلوب مفتاح خاص', '2023-03-01 21:25:10', '2023-03-01 21:25:10'),
(4866, 'sa', 'guest', 'ضيف', '2023-03-01 21:25:10', '2023-03-01 21:25:10'),
(4867, 'sa', 'menu_list_updated_successfully', 'تم تحديث قائمة القوائم بنجاح', '2023-03-01 21:25:10', '2023-03-01 21:25:10'),
(4868, 'sa', 'product_categories', 'فئات المنتجات', '2023-03-01 21:25:10', '2023-03-01 21:25:10'),
(4869, 'sa', 'login_activity_log_deleted_successfully', 'تم حذف سجل نشاط تسجيل الدخول بنجاح', '2023-03-01 21:25:10', '2023-03-01 21:25:10'),
(4870, 'sa', 'unable_to_delete_login_activity_log', 'تعذر حذف سجل نشاط تسجيل الدخول', '2023-03-01 21:25:10', '2023-03-01 21:25:10'),
(4871, 'sa', 'mail_driver_is_required', 'مطلوب سائق البريد', '2023-03-01 21:25:10', '2023-03-01 21:25:10'),
(4872, 'sa', 'smtp_configuration_updated_successfully', 'تم تحديث تكوين SMTP بنجاح', '2023-03-01 21:25:10', '2023-03-01 21:25:10'),
(4873, 'sa', 'subject_is_required', 'الموضوع مطلوب', '2023-03-01 21:25:10', '2023-03-01 21:25:10'),
(4874, 'sa', 'message_is_required', 'الرسالة مطلوبة', '2023-03-01 21:25:10', '2023-03-01 21:25:10'),
(4875, 'sa', 'email_sending_failed', 'فشل إرسال البريد الإلكتروني', '2023-03-01 21:25:36', '2023-03-01 21:25:36'),
(4876, 'sa', 'a_new_email_sending_successfully', 'إرسال بريد إلكتروني جديد بنجاح', '2023-03-01 21:25:36', '2023-03-01 21:25:36'),
(4877, 'sa', 'password_is_too_short', 'كلمة المرور قصيرة جدا', '2023-03-01 21:25:36', '2023-03-01 21:25:36'),
(4878, 'sa', 'try_forgot_password', 'حاول نسيت كلمة المرور', '2023-03-01 21:25:36', '2023-03-01 21:25:36'),
(4879, 'sa', 'send_password_reset________________________________________________________________________link', 'إرسال رابط إعادة تعيين كلمة السر', '2023-03-01 21:25:36', '2023-03-01 21:25:36'),
(4880, 'sa', 'we_have_emailed_your_password_reset_link', 'لقد أرسلنا رابط إعادة تعيين كلمة المرور بالبريد الإلكتروني', '2023-03-01 21:25:36', '2023-03-01 21:25:36'),
(4881, 'sa', 'a_new_comment_added', 'تمت إضافة تعليق جديد.', '2023-03-01 21:25:36', '2023-03-01 21:25:36'),
(4882, 'sa', 'view_blog', 'مشاهدة المدونة', '2023-03-01 21:25:36', '2023-03-01 21:25:36'),
(4883, 'sa', 'comment_added_failed', 'فشل إضافة التعليق', '2023-03-01 21:25:36', '2023-03-01 21:25:36'),
(4884, 'sa', 'customer_delete_failed', 'فشل حذف العميل', '2023-03-01 21:25:36', '2023-03-01 21:25:36'),
(4885, 'sa', 'mailchimp_api_key_or_list_id_missing', 'مفتاح Mailchimp Api أو معرف القائمة مفقود.', '2023-03-01 21:25:36', '2023-03-01 21:25:36'),
(4886, 'sa', 'this_email_is_already_subscribed', 'هذا البريد الإلكتروني مشترك بالفعل', '2023-03-01 21:25:36', '2023-03-01 21:25:36'),
(4887, 'sa', 'please_enter_a_valid_email', 'يرجى إدخال البريد الإلكتروني الصحيح', '2023-03-01 21:25:36', '2023-03-01 21:25:36'),
(4888, 'en', 'updated_successfully', 'Updated successfully', '2023-03-01 21:42:47', '2023-03-01 21:42:47'),
(4889, 'en', 'percentage_discount', 'Percentage discount', '2023-03-01 21:43:10', '2023-03-01 21:43:10'),
(4890, 'en', 'update_pickup_points', 'Update Pickup Points', '2023-03-01 23:05:27', '2023-03-01 23:05:27'),
(4891, 'en', 'shipping_zones', 'Shipping Zones', '2023-03-01 23:05:27', '2023-03-01 23:05:27'),
(4892, 'en', 'login_to', 'Login To', '2023-03-02 21:25:15', '2023-03-02 21:25:15'),
(4893, 'en', 'forgot_password', 'Forgot Password?', '2023-03-02 21:25:15', '2023-03-02 21:25:15'),
(4894, 'en', 'login_credentials_does_not_match', 'Login Credentials Does not Match', '2023-03-02 21:25:25', '2023-03-02 21:25:25'),
(4895, 'en', 'site_motto', 'Site Motto', '2023-03-02 21:26:56', '2023-03-02 21:26:56'),
(4896, 'en', 'site_moto', 'Site Moto', '2023-03-02 21:26:56', '2023-03-02 21:26:56'),
(4897, 'en', 'registration', 'Registration', '2023-03-04 05:44:29', '2023-03-04 05:44:29'),
(4898, 'en', 'return_requests', 'Return Requests', '2023-03-07 11:18:47', '2023-03-07 11:18:47'),
(4899, 'en', 'attribute_value_delete_failed', 'Attribute value delete failed', '2023-03-07 11:23:58', '2023-03-07 11:23:58'),
(4900, 'en', 'permalink_must_be_unique', 'Permalink must be unique', '2023-03-07 11:38:08', '2023-03-07 11:38:08'),
(4901, 'en', 'license_activate', 'License activate', '2023-03-09 22:56:42', '2023-03-09 22:56:42'),
(4902, 'en', 'license_key', 'License Key', '2023-03-09 22:56:42', '2023-03-09 22:56:42'),
(4903, 'en', 'enter_license_key', 'Enter License Key', '2023-03-09 22:56:42', '2023-03-09 22:56:42'),
(4904, 'en', 'unable_to_update_media_file', 'Unable to update media file', '2023-03-11 16:42:12', '2023-03-11 16:42:12'),
(4905, 'en', 'unable_to_update_menu_list', 'Unable to update menu list', '2023-03-11 17:01:56', '2023-03-11 17:01:56'),
(4906, 'en', 'media_file_uploaded_successful', 'Media file uploaded successful', '2023-03-11 19:17:33', '2023-03-11 19:17:33'),
(4907, 'en', 'menu_deleted_successfully', 'Menu deleted successfully', '2023-03-11 19:37:02', '2023-03-11 19:37:02'),
(4908, 'en', 'no_deal_found', 'No Deal Found', '2023-03-11 19:40:46', '2023-03-11 19:40:46'),
(4909, 'en', 'create_new_deal', 'Create New Deal', '2023-03-11 19:40:46', '2023-03-11 19:40:46'),
(4910, 'en', 'media_file_deleted_successfully', 'Media file deleted successfully', '2023-03-11 19:41:50', '2023-03-11 19:41:50'),
(4911, 'en', 'welcome', 'Welcome', '2023-03-12 16:45:41', '2023-03-12 16:45:41'),
(4912, 'en', 'no_shipping_rates_available_for_products_in_this_profile', 'No shipping rates available for products in this profile', '2023-03-12 17:03:31', '2023-03-12 17:03:31'),
(4913, 'en', 'not_found', 'Not Found', '2023-03-12 17:03:45', '2023-03-12 17:03:45'),
(4914, 'sa', 'file_manager', 'إدارة الملفات', '2023-03-12 17:03:45', '2023-03-12 17:03:45');

-- --------------------------------------------------------

--
-- Table structure for table `tl_translation_modules`
--

CREATE TABLE `tl_translation_modules` (
  `id` int(11) NOT NULL,
  `name` varchar(150) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


CREATE TABLE `tl_com_delivery_schedule_slots` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `schedule_date` date NOT NULL,
  `start_time` time NOT NULL,
  `end_time` time NOT NULL,
  `max_orders` int unsigned DEFAULT NULL,
  `status` tinyint NOT NULL DEFAULT 1,
  `label` varchar(100) DEFAULT NULL,
  `recurrence_group_id` CHAR(36) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_schedule_date` (`schedule_date`),
  KEY `idx_date_status` (`schedule_date`, `status`),
  UNIQUE KEY `uniq_date_start_end` (`schedule_date`, `start_time`, `end_time`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;





CREATE TABLE `tl_com_order_delivery_schedules` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `order_id` int NOT NULL,
  `slot_id` int unsigned DEFAULT NULL,
  `schedule_date` date NOT NULL,
  `start_time` time NOT NULL,
  `end_time` time NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uniq_order_id` (`order_id`),
  KEY `idx_slot_id` (`slot_id`),
  CONSTRAINT `fk_order_delivery_schedule_order`
    FOREIGN KEY (`order_id`) REFERENCES `tl_com_orders` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_order_delivery_schedule_slot`
    FOREIGN KEY (`slot_id`) REFERENCES `tl_com_delivery_schedule_slots` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;






CREATE TABLE tl_com_order_delivery_status_logs (
    id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    order_id BIGINT UNSIGNED NOT NULL,
    delivery_status TINYINT NOT NULL,
    created_at TIMESTAMP NULL DEFAULT NULL,
    updated_at TIMESTAMP NULL DEFAULT NULL,
    PRIMARY KEY (id),
    KEY idx_order_id (order_id),
    KEY idx_order_status (order_id, delivery_status, id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



-- --------------------------------------------------------

--
-- Table structure for table `tl_uploaded_files`
--

CREATE TABLE `tl_uploaded_files` (
  `id` int(11) NOT NULL,
  `media_type` int(11) NOT NULL DEFAULT 1,
  `disk` varchar(150) DEFAULT 'public',
  `name` text DEFAULT NULL,
  `title` text DEFAULT NULL,
  `alt` text DEFAULT NULL,
  `caption` text DEFAULT NULL,
  `description` text DEFAULT NULL,
  `path` text NOT NULL,
  `size` double DEFAULT NULL,
  `variant` text DEFAULT NULL,
  `file_type` varchar(150) DEFAULT NULL,
  `extension` varchar(150) DEFAULT NULL,
  `folder_name` varchar(200) DEFAULT NULL,
  `uploaded_by` varchar(150) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp(),
  `user_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `tl_uploaded_files`
--

INSERT INTO `tl_uploaded_files` (`id`, `media_type`, `disk`, `name`, `title`, `alt`, `caption`, `description`, `path`, `size`, `variant`, `file_type`, `extension`, `folder_name`, `uploaded_by`, `created_at`, `updated_at`, `user_id`) VALUES
(17, 3, 'public', 'img-demo_17', NULL, NULL, NULL, NULL, 'storage/all_files/2023/Mar/img-demo_17.jpg', 2972, '{\"large_size\":[\"1000\",\"1000\"],\"medium_size\":[\"500\",\"500\"],\"small_size\":[\"250\",\"250\"]}', 'image', 'jpg', 'storage/all_files/2023/Mar', 'Admin', '2023-03-12 18:24:25', '2023-03-12 18:24:26', NULL),
(26, 3, 'public', 'banner_21', NULL, NULL, NULL, NULL, 'storage/all_files/2023/Mar/banner_21.png', 6388, '{\"large_size\":[\"1000\",\"1000\"],\"medium_size\":[\"500\",\"500\"],\"small_size\":[\"250\",\"250\"]}', 'image', 'png', 'storage/all_files/2023/Mar', 'Emarat Hossain', '2023-03-23 00:52:49', '2023-03-23 00:52:49', NULL),
(29, 3, 'public', 'images (3)_16_27', NULL, NULL, NULL, NULL, 'storage/all_files/2023/Mar/images (3)_16_27.jpeg', 2658, '{\"large_size\":[\"1000\",\"1000\"],\"medium_size\":[\"500\",\"500\"],\"small_size\":[\"250\",\"250\"]}', 'image', 'jpeg', 'storage/all_files/2023/Mar', 'Emarat Hossain', '2023-03-23 01:09:20', '2023-03-23 01:09:20', NULL),
(30, 3, 'public', '3_14_30', NULL, NULL, NULL, NULL, 'storage/all_files/2023/Mar/3_14_30.jpg', 32083, '{\"large_size\":[\"1000\",\"1000\"],\"medium_size\":[\"500\",\"500\"],\"small_size\":[\"250\",\"250\"]}', 'image', 'jpg', 'storage/all_files/2023/Mar', 'Emarat Hossain', '2023-03-23 01:09:20', '2023-03-23 01:09:20', NULL),
(31, 3, 'public', 'c3_10_31', NULL, NULL, NULL, NULL, 'storage/all_files/2023/Mar/c3_10_31.png', 415, '{\"large_size\":[\"1000\",\"1000\"],\"medium_size\":[\"500\",\"500\"],\"small_size\":[\"250\",\"250\"]}', 'image', 'png', 'storage/all_files/2023/Mar', 'Emarat Hossain', '2023-03-23 01:09:20', '2023-03-23 01:09:21', NULL),
(32, 3, 'public', 'trending-offers-ear-phone-02_9_32', NULL, NULL, NULL, NULL, 'storage/all_files/2023/Mar/trending-offers-ear-phone-02_9_32.png', 6600, '{\"large_size\":[\"1000\",\"1000\"],\"medium_size\":[\"500\",\"500\"],\"small_size\":[\"250\",\"250\"]}', 'image', 'png', 'storage/all_files/2023/Mar', 'Emarat Hossain', '2023-03-23 01:09:21', '2023-03-23 01:09:21', NULL),
(33, 3, 'public', 'imageonline-co-placeholder-image_33', NULL, NULL, NULL, NULL, 'storage/all_files/2023/Mar/imageonline-co-placeholder-image_33.jpg', 22571, '{\"large_size\":[\"1000\",\"1000\"],\"medium_size\":[\"500\",\"500\"],\"small_size\":[\"250\",\"250\"]}', 'image', 'jpg', 'storage/all_files/2023/Mar', 'Emarat Hossain', '2023-03-23 01:24:21', '2023-03-23 01:24:21', NULL),
(48, 3, 'public', 'paddle_34', NULL, NULL, NULL, NULL, 'storage/all_files/2023/Apr/paddle_34.png', 1682, '{\"large_size\":[\"1000\",\"1000\"],\"medium_size\":[\"500\",\"500\"],\"small_size\":[\"250\",\"250\"]}', 'image', 'png', 'storage/all_files/2023/Apr', 'Emarat Hossain', '2023-04-05 05:40:31', '2023-04-05 05:40:31', NULL),
(49, 3, 'public', 'paypal_49', NULL, NULL, NULL, NULL, 'storage/all_files/2023/Apr/paypal_49.png', 1604, '{\"large_size\":[\"1000\",\"1000\"],\"medium_size\":[\"500\",\"500\"],\"small_size\":[\"250\",\"250\"]}', 'image', 'png', 'storage/all_files/2023/Apr', 'Emarat Hossain', '2023-04-05 05:40:31', '2023-04-05 05:40:31', NULL),
(50, 3, 'public', 'paystack_50', NULL, NULL, NULL, NULL, 'storage/all_files/2023/Apr/paystack_50.png', 1577, '{\"large_size\":[\"1000\",\"1000\"],\"medium_size\":[\"500\",\"500\"],\"small_size\":[\"250\",\"250\"]}', 'image', 'png', 'storage/all_files/2023/Apr', 'Emarat Hossain', '2023-04-05 05:40:31', '2023-04-05 05:40:31', NULL),
(51, 3, 'public', 'razorpay_51', NULL, NULL, NULL, NULL, 'storage/all_files/2023/Apr/razorpay_51.png', 1995, '{\"large_size\":[\"1000\",\"1000\"],\"medium_size\":[\"500\",\"500\"],\"small_size\":[\"250\",\"250\"]}', 'image', 'png', 'storage/all_files/2023/Apr', 'Emarat Hossain', '2023-04-05 05:40:31', '2023-04-05 05:40:31', NULL),
(52, 3, 'public', 'sslcommerz_52', NULL, NULL, NULL, NULL, 'storage/all_files/2023/Apr/sslcommerz_52.png', 1982, '{\"large_size\":[\"1000\",\"1000\"],\"medium_size\":[\"500\",\"500\"],\"small_size\":[\"250\",\"250\"]}', 'image', 'png', 'storage/all_files/2023/Apr', 'Emarat Hossain', '2023-04-05 05:40:31', '2023-04-05 05:40:32', NULL),
(53, 3, 'public', 'stipe_53', NULL, NULL, NULL, NULL, 'storage/all_files/2023/Apr/stipe_53.png', 1701, '{\"large_size\":[\"1000\",\"1000\"],\"medium_size\":[\"500\",\"500\"],\"small_size\":[\"250\",\"250\"]}', 'image', 'png', 'storage/all_files/2023/Apr', 'Emarat Hossain', '2023-04-05 05:40:32', '2023-04-05 05:40:32', NULL),
(54, 3, 'public', 'cod_54', NULL, NULL, NULL, NULL, 'storage/all_files/2023/Apr/cod_54.png', 3877, '{\"large_size\":[\"1000\",\"1000\"],\"medium_size\":[\"500\",\"500\"],\"small_size\":[\"250\",\"250\"]}', 'image', 'png', 'storage/all_files/2023/Apr', 'Emarat Hossain', '2023-04-05 05:41:07', '2023-04-05 05:41:08', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `tl_users`
--

CREATE TABLE `tl_users` (
  `id` int(11) NOT NULL,
  `uid` varchar(150) DEFAULT NULL,
  `name` varchar(150) DEFAULT NULL,
  `email` varchar(150) DEFAULT NULL,
  `image` text DEFAULT NULL,
  `password` varchar(150) DEFAULT NULL,
  `remember_token` varchar(150) DEFAULT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `is_logged_in` int(11) DEFAULT NULL,
  `status` int(11) DEFAULT NULL,
  `user_type` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `tl_users`
--

{{DYNAMIC_USER_INSERT}}

-- --------------------------------------------------------

-- --------------------------------------------------------

--
-- Table structure for table `tl_user_types`
--

CREATE TABLE `tl_user_types` (
  `id` int(11) NOT NULL,
  `name` varchar(50) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `tl_water_mark_image_applicable_folders`
--

CREATE TABLE `tl_water_mark_image_applicable_folders` (
  `id` int(11) NOT NULL,
  `image_type` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `tl_saas_currencies`
--

CREATE TABLE `tl_saas_currencies` (
  `id` int(11) NOT NULL,
  `name` varchar(50) DEFAULT NULL,
  `code` varchar(50) DEFAULT NULL,
  `symbol` varchar(50) DEFAULT NULL,
  `conversion_rate` double DEFAULT 0,
  `position` varchar(150) DEFAULT NULL,
  `thousand_separator` varchar(11) DEFAULT NULL,
  `decimal_separator` varchar(11) DEFAULT NULL,
  `number_of_decimal` int(11) NOT NULL DEFAULT 0,
  `status` int(11) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `tl_saas_currencies`
--

INSERT INTO `tl_saas_currencies` (`id`, `name`, `code`, `symbol`, `conversion_rate`, `position`, `thousand_separator`, `decimal_separator`, `number_of_decimal`, `status`, `created_at`, `updated_at`) VALUES
(1, 'Us Dolar', 'USD', '$', 1, '1', ',', '.', 2, 1, '2022-07-26 20:36:16', '2023-03-30 06:47:21'),
(6, 'BDT', 'BDT', '৳', 100, '2', ',', '.', 2, 1, '2023-02-07 21:49:35', '2023-07-05 10:55:07'),
(8, 'INR', 'INR', 'INR', 82.15, '1', ',', '.', 2, 1, '2023-04-04 07:50:53', '2023-10-23 09:15:22'),
(10, 'Euro', 'EUR', 'EUR', 0.95, '2', ',', '.', 2, 1, '2023-10-30 06:48:33', '2023-10-30 06:52:42');

-- --------------------------------------------------------




--
-- Table structure for table `tl_widgets`
--

CREATE TABLE `tl_widgets` (
  `id` bigint(20) NOT NULL,
  `widget_name` varchar(255) DEFAULT NULL,
  `widget_short_desc` text DEFAULT NULL,
  `theme_id` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `tl_widgets`
--

INSERT INTO `tl_widgets` (`id`, `widget_name`, `widget_short_desc`, `theme_id`, `created_at`, `updated_at`) VALUES
(78, 'Address Widget', 'Display address and contact', 15, '2022-12-18 18:18:25', '2022-12-18 18:18:25'),
(79, 'Newsletter Widget', 'Display Newsletter Box', 15, '2022-12-18 18:19:38', '2022-12-18 18:19:38'),
(83, 'Featured Blog Widget', NULL, 15, '2023-01-03 05:17:40', '2023-01-03 05:17:40'),
(84, 'Recent Blog Widget', NULL, 15, '2023-01-03 05:17:40', '2023-01-03 05:17:40'),
(96, 'Footer Left Menu', NULL, 15, '2023-01-08 01:00:22', '2023-01-08 01:00:22'),
(97, 'Footer Right Menu', NULL, 15, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `user_keys`
--

CREATE TABLE `user_keys` (
  `id` int(11) NOT NULL,
  `license_key` text DEFAULT NULL,
  `item` varchar(100) DEFAULT NULL,
  `item_is` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `activity_log`
--
ALTER TABLE `activity_log`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id` (`id`),
  ADD KEY `subject` (`subject_type`,`subject_id`),
  ADD KEY `causer` (`causer_type`,`causer_id`),
  ADD KEY `activity_log_log_name_index` (`log_name`);

--
-- Indexes for table `admin_login_activity_log`
--
ALTER TABLE `admin_login_activity_log`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id` (`id`),
  ADD KEY `FK_admin_login_activity_log_tl_users` (`user_id`);

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
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `job_batches`
--
ALTER TABLE `job_batches`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `model_has_permissions`
--
ALTER TABLE `model_has_permissions`
  ADD KEY `FK_model_has_permissions_tl_users` (`model_id`),
  ADD KEY `FK_model_has_permissions_permissions` (`permission_id`);

--
-- Indexes for table `model_has_roles`
--
ALTER TABLE `model_has_roles`
  ADD KEY `FK_model_has_roles_tl_users` (`model_id`),
  ADD KEY `FK_model_has_roles_roles` (`role_id`);

--
-- Indexes for table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`id`),
  ADD KEY `notifications_notifiable_type_notifiable_id_index` (`notifiable_type`,`notifiable_id`);

--
-- Indexes for table `page_builder_sections`
--
ALTER TABLE `page_builder_sections`
  ADD PRIMARY KEY (`id`),
  ADD KEY `page_id` (`page_id`);

--
-- Indexes for table `page_builder_sections_layout_widget_properties`
--
ALTER TABLE `page_builder_sections_layout_widget_properties`
  ADD PRIMARY KEY (`id`),
  ADD KEY `section_has_widget_id` (`layout_has_widget_id`);

--
-- Indexes for table `page_builder_sections_properties`
--
ALTER TABLE `page_builder_sections_properties`
  ADD PRIMARY KEY (`id`),
  ADD KEY `section_id` (`section_id`);

--
-- Indexes for table `page_builder_section_layouts`
--
ALTER TABLE `page_builder_section_layouts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `section_id` (`section_id`),
  ADD KEY `id` (`id`);

--
-- Indexes for table `page_builder_section_layout_widgets`
--
ALTER TABLE `page_builder_section_layout_widgets`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id` (`id`),
  ADD KEY `section_layout_id` (`section_layout_id`),
  ADD KEY `page_widget_id` (`page_widget_id`);

--
-- Indexes for table `page_builder_widgets`
--
ALTER TABLE `page_builder_widgets`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `page_builder_widget_translations`
--
ALTER TABLE `page_builder_widget_translations`
  ADD PRIMARY KEY (`id`),
  ADD KEY `layout_widget_properties_id` (`layout_widget_properties_id`);

--
-- Indexes for table `password_resets`
--
ALTER TABLE `password_resets`
  ADD KEY `password_resets_email_index` (`email`);

--
-- Indexes for table `permissions`
--
ALTER TABLE `permissions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id` (`id`),
  ADD KEY `FK_permissions_permission_module` (`module_id`);

--
-- Indexes for table `permission_module`
--
ALTER TABLE `permission_module`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id` (`id`);

--
-- Indexes for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  ADD KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`);

--
-- Indexes for table `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `role_has_permissions`
--
ALTER TABLE `role_has_permissions`
  ADD PRIMARY KEY (`permission_id`,`role_id`),
  ADD KEY `role_has_permissions_role_id_foreign` (`role_id`);

--
-- Indexes for table `timezones`
--
ALTER TABLE `timezones`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tl_amount_types`
--
ALTER TABLE `tl_amount_types`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id` (`id`);

--
-- Indexes for table `tl_blogs`
--
ALTER TABLE `tl_blogs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id` (`id`),
  ADD KEY `FK_tl_blogs_tl_users` (`user_id`);

--
-- Indexes for table `tl_blogs_categories`
--
ALTER TABLE `tl_blogs_categories`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id` (`id`),
  ADD KEY `FK_tl_blogs_categories_tl_blogs` (`blog_id`),
  ADD KEY `FK_tl_blogs_categories_tl_blog_categories` (`category_id`);

--
-- Indexes for table `tl_blogs_tags`
--
ALTER TABLE `tl_blogs_tags`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id` (`id`),
  ADD KEY `FK_tl_blogs_tags_tl_blogs` (`blog_id`),
  ADD KEY `FK_tl_blogs_tags_tl_blog_tags` (`tag_id`);

--
-- Indexes for table `tl_blog_categories`
--
ALTER TABLE `tl_blog_categories`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id` (`id`),
  ADD KEY `FK_tl_blog_categories_tl_blog_categories` (`parent`);

--
-- Indexes for table `tl_blog_category_translations`
--
ALTER TABLE `tl_blog_category_translations`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id` (`id`),
  ADD KEY `FK_tl_blog_category_translations_tl_blog_categories` (`category_id`);

--
-- Indexes for table `tl_blog_comments`
--
ALTER TABLE `tl_blog_comments`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id` (`id`),
  ADD KEY `FK_tl_blog_comments_tl_blogs` (`blog_id`),
  ADD KEY `FK_tl_blog_comments_tl_blog_comments` (`parent`);

--
-- Indexes for table `tl_blog_tags`
--
ALTER TABLE `tl_blog_tags`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id` (`id`);

--
-- Indexes for table `tl_blog_tag_translations`
--
ALTER TABLE `tl_blog_tag_translations`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id` (`id`),
  ADD KEY `FK_tl_blog_tag_translations_tl_blog_tags` (`tag_id`);

--
-- Indexes for table `tl_blog_translations`
--
ALTER TABLE `tl_blog_translations`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id` (`id`),
  ADD KEY `FK_tl_blog_translations_tl_blogs` (`blog_id`);

--
-- Indexes for table `tl_com_attributes`
--
ALTER TABLE `tl_com_attributes`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id` (`id`),
  ADD KEY `FK_tl_com_attributes_tl_all_status` (`status`);

--
-- Indexes for table `tl_com_attribute_values`
--
ALTER TABLE `tl_com_attribute_values`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id` (`id`),
  ADD KEY `FK_tl_com_attribute_values_tl_com_attributes` (`attribute_id`),
  ADD KEY `FK_tl_com_attribute_values_tl_all_status` (`status`);

--
-- Indexes for table `tl_com_bank_payments`
--
ALTER TABLE `tl_com_bank_payments`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tl_com_brands`
--
ALTER TABLE `tl_com_brands`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id` (`id`),
  ADD KEY `FK_tl_com_brands_tl_all_status` (`status`);

--
-- Indexes for table `tl_com_brand_translations`
--
ALTER TABLE `tl_com_brand_translations`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id` (`id`),
  ADD KEY `FK_tl_com_brand_translations_tl_com_brands` (`brand_id`);

--
-- Indexes for table `tl_com_cart_items`
--
ALTER TABLE `tl_com_cart_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `FK_tl_com_cart_items_tl_customers` (`customer_id`),
  ADD KEY `FK_tl_com_cart_items_tl_com_products` (`product_id`);

--
-- Indexes for table `tl_com_cash_back_configs`
--
ALTER TABLE `tl_com_cash_back_configs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id` (`id`);

--
-- Indexes for table `tl_com_cash_back_offer`
--
ALTER TABLE `tl_com_cash_back_offer`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id` (`id`),
  ADD KEY `FK_tl_com_cash_back_offer_tl_com_products` (`product_id`);

--
-- Indexes for table `tl_com_categories`
--
ALTER TABLE `tl_com_categories`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id` (`id`),
  ADD KEY `FK_tl_com_categories_tl_all_status` (`status`),
  ADD KEY `FK_tl_com_categories_tl_com_categories` (`parent`);

--
-- Indexes for table `tl_com_category_has_commission`
--
ALTER TABLE `tl_com_category_has_commission`
  ADD PRIMARY KEY (`id`),
  ADD KEY `category_id` (`category_id`);

--
-- Indexes for table `tl_com_category_translations`
--
ALTER TABLE `tl_com_category_translations`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id` (`id`),
  ADD KEY `FK_tl_com_category_translations_tl_com_categories` (`category_id`);

--
-- Indexes for table `tl_com_cities`
--
ALTER TABLE `tl_com_cities`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id` (`id`),
  ADD KEY `FK__tl_all_status` (`state_id`);

--
-- Indexes for table `tl_com_city_translations`
--
ALTER TABLE `tl_com_city_translations`
  ADD PRIMARY KEY (`id`),
  ADD KEY `city_id` (`city_id`);

--
-- Indexes for table `tl_com_collection_translations`
--
ALTER TABLE `tl_com_collection_translations`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id` (`id`),
  ADD KEY `FK_tl_com_collection_translations_tl_com_product_collections` (`collection_id`);

--
-- Indexes for table `tl_com_colletions_has_products`
--
ALTER TABLE `tl_com_colletions_has_products`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id` (`id`),
  ADD KEY `FK_tl_com_colletions_has_products_tl_com_product_collections` (`collection_id`),
  ADD KEY `FK_tl_com_colletions_has_products_tl_com_products` (`product_id`);

--
-- Indexes for table `tl_com_colors`
--
ALTER TABLE `tl_com_colors`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id` (`id`),
  ADD KEY `FK_tl_com_colors_tl_all_status` (`status`);

--
-- Indexes for table `tl_com_color_translations`
--
ALTER TABLE `tl_com_color_translations`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id` (`id`),
  ADD KEY `FK_tl_com_color_translations_tl_com_colors` (`color_id`);

--
-- Indexes for table `tl_com_country_translations`
--
ALTER TABLE `tl_com_country_translations`
  ADD PRIMARY KEY (`id`),
  ADD KEY `country_id` (`country_id`);

--
-- Indexes for table `tl_com_coupons`
--
ALTER TABLE `tl_com_coupons`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id` (`id`);

--
-- Indexes for table `tl_com_coupon_brands`
--
ALTER TABLE `tl_com_coupon_brands`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id` (`id`),
  ADD KEY `FK_tl_com_coupon_brands_tl_com_coupons` (`coupon_id`),
  ADD KEY `FK_tl_com_coupon_brands_tl_com_brands` (`brand_id`);

--
-- Indexes for table `tl_com_coupon_category`
--
ALTER TABLE `tl_com_coupon_category`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id` (`id`),
  ADD KEY `FK_tl_com_coupon_category_tl_com_coupons` (`coupon_id`),
  ADD KEY `FK_tl_com_coupon_category_tl_com_categories` (`category_id`);

--
-- Indexes for table `tl_com_coupon_exclude_brands`
--
ALTER TABLE `tl_com_coupon_exclude_brands`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id` (`id`),
  ADD KEY `FK_tl_com_coupon_exclude_brands_tl_com_coupons` (`coupon_id`),
  ADD KEY `FK_tl_com_coupon_exclude_brands_tl_com_brands` (`brand_id`);

--
-- Indexes for table `tl_com_coupon_exclude_category`
--
ALTER TABLE `tl_com_coupon_exclude_category`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id` (`id`),
  ADD KEY `FK_tl_com_coupon_exclude_category_tl_com_coupons` (`coupon_id`),
  ADD KEY `FK_tl_com_coupon_exclude_category_tl_com_categories` (`category_id`);

--
-- Indexes for table `tl_com_coupon_exclude_products`
--
ALTER TABLE `tl_com_coupon_exclude_products`
  ADD PRIMARY KEY (`id`),
  ADD KEY `FK_tl_com_coupon_exclude_products_tl_com_coupons` (`coupon_id`),
  ADD KEY `FK_tl_com_coupon_exclude_products_tl_com_products` (`product_id`);

--
-- Indexes for table `tl_com_coupon_products`
--
ALTER TABLE `tl_com_coupon_products`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id` (`id`),
  ADD KEY `FK_tl_com_coupon_products_tl_com_coupons` (`coupon_id`),
  ADD KEY `FK_tl_com_coupon_products_tl_com_products` (`product_id`);

--
-- Indexes for table `tl_com_coupon_usages`
--
ALTER TABLE `tl_com_coupon_usages`
  ADD PRIMARY KEY (`id`),
  ADD KEY `coupon_id` (`coupon_id`),
  ADD KEY `customer_id` (`customer_id`),
  ADD KEY `order_id` (`order_id`);

--
-- Indexes for table `tl_com_currencies`
--
ALTER TABLE `tl_com_currencies`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id` (`id`),
  ADD KEY `FK_tl_currency_settings_tl_all_status` (`status`);

--
-- Indexes for table `tl_com_customers`
--
ALTER TABLE `tl_com_customers`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id` (`id`);

--
-- Indexes for table `tl_com_customer_address`
--
ALTER TABLE `tl_com_customer_address`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id` (`id`),
  ADD KEY `FK_tl_com_customer_address_tl_com_customers` (`customer_id`),
  ADD KEY `FK_tl_com_customer_address_tl_countries` (`country_id`),
  ADD KEY `FK_tl_com_customer_address_tl_com_state` (`state_id`),
  ADD KEY `FK_tl_com_customer_address_tl_com_cities` (`city_id`),
  ADD KEY `guest_customer` (`guest_customer`);

--
-- Indexes for table `tl_com_customer_wishlists`
--
ALTER TABLE `tl_com_customer_wishlists`
  ADD PRIMARY KEY (`id`),
  ADD KEY `FK_tl_com_customer_wishlists_tl_com_products` (`product_id`),
  ADD KEY `FK_tl_com_customer_wishlists_tl_com_customers` (`customer_id`);

--
-- Indexes for table `tl_com_custom_notifications`
--
ALTER TABLE `tl_com_custom_notifications`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tl_com_deals_products`
--
ALTER TABLE `tl_com_deals_products`
  ADD PRIMARY KEY (`id`),
  ADD KEY `FK_tl_com_deals_products_tl_com_flash_deal` (`deal_id`),
  ADD KEY `FK_tl_com_deals_products_tl_com_products` (`product_id`);

--
-- Indexes for table `tl_com_ecommerce_settings`
--
ALTER TABLE `tl_com_ecommerce_settings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tl_com_flash_deal`
--
ALTER TABLE `tl_com_flash_deal`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tl_com_flash_deal_translations`
--
ALTER TABLE `tl_com_flash_deal_translations`
  ADD PRIMARY KEY (`id`),
  ADD KEY `FK_tl_com_flash_deal_translations_tl_com_flash_deal` (`deal_id`);

--
-- Indexes for table `tl_com_guest_customer`
--
ALTER TABLE `tl_com_guest_customer`
  ADD PRIMARY KEY (`id`),
  ADD KEY `FK_tl_com_guest_customer_tl_com_orders` (`order_id`);

--
-- Indexes for table `tl_com_key_word_search`
--
ALTER TABLE `tl_com_key_word_search`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tl_com_location_wise_shipping_rates`
--
ALTER TABLE `tl_com_location_wise_shipping_rates`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tl_com_location_wise_shipping_rate_cities`
--
ALTER TABLE `tl_com_location_wise_shipping_rate_cities`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tl_com_location_wise_shipping_rate_countries`
--
ALTER TABLE `tl_com_location_wise_shipping_rate_countries`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tl_com_location_wise_shipping_rate_states`
--
ALTER TABLE `tl_com_location_wise_shipping_rate_states`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tl_com_ordered_products`
--
ALTER TABLE `tl_com_ordered_products`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id` (`id`),
  ADD KEY `FK_tl_com_ordered_products_tl_com_orders` (`order_id`),
  ADD KEY `FK_tl_com_ordered_products_tl_com_products` (`product_id`),
  ADD KEY `FK_tl_com_ordered_products_tl_com_shipping_zone_has_rates` (`shipping_rate`);

--
-- Indexes for table `tl_com_orders`
--
ALTER TABLE `tl_com_orders`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id` (`id`),
  ADD KEY `FK_tl_com_orders_tl_all_status` (`payment_status`),
  ADD KEY `FK_tl_com_orders_tl_all_status_2` (`delivery_status`),
  ADD KEY `FK_tl_com_orders_tl_com_payment_methods` (`payment_method`),
  ADD KEY `FK_tl_com_orders_tl_pick_up_points` (`pickup_point_id`),
  ADD KEY `FK_tl_com_orders_tl_com_customers` (`customer_id`);

--
-- Indexes for table `tl_com_order_package_trackings`
--
ALTER TABLE `tl_com_order_package_trackings`
  ADD PRIMARY KEY (`id`),
  ADD KEY `FK_tl_com_order_package_trackings_tl_com_orders` (`order_id`),
  ADD KEY `FK_tl_com_order_package_trackings_tl_com_ordered_products` (`order_package_id`);

--
-- Indexes for table `tl_com_order_refund_requests`
--
ALTER TABLE `tl_com_order_refund_requests`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id` (`id`),
  ADD KEY `FK_tl_com_order_refund_requests_tl_com_orders` (`order_id`),
  ADD KEY `FK_tl_com_order_refund_requests_tl_com_ordered_products` (`ordered_product_id`),
  ADD KEY `FK_tl_com_order_refund_requests_tl_all_status` (`refund_status`),
  ADD KEY `FK_tl_com_order_refund_requests_tl_all_status_2` (`return_status`),
  ADD KEY `FK_tl_com_order_refund_requests_tl_com_customers` (`customer_id`),
  ADD KEY `FK_tl_com_order_refund_requests_tl_com_product_refund_reasons` (`reason_id`);

--
-- Indexes for table `tl_com_payment_methods`
--
ALTER TABLE `tl_com_payment_methods`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id` (`id`),
  ADD KEY `FK_tl_com_payment_methods_tl_all_status` (`status`);

--
-- Indexes for table `tl_com_payment_method_has_settings`
--
ALTER TABLE `tl_com_payment_method_has_settings`
  ADD PRIMARY KEY (`id`),
  ADD KEY `FK_tl_com_payment_method_has_settings_tl_com_payment_methods` (`payment_method_id`);

--
-- Indexes for table `tl_com_payment_transactions`
--
ALTER TABLE `tl_com_payment_transactions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `FK_tl_com_payment_transactions_tl_com_customers` (`customer_id`);

--
-- Indexes for table `tl_com_products`
--
ALTER TABLE `tl_com_products`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id` (`id`),
  ADD KEY `FK_tl_com_products_tl_com_units` (`unit`),
  ADD KEY `FK_tl_com_products_tl_com_product_conditions` (`conditions`),
  ADD KEY `FK_tl_com_products_tl_com_product_types` (`product_type`),
  ADD KEY `FK_tl_com_products_tl_all_status` (`is_active_cod`);

--
-- Indexes for table `tl_com_product_attribute_translations`
--
ALTER TABLE `tl_com_product_attribute_translations`
  ADD PRIMARY KEY (`id`),
  ADD KEY `FK_tl_com_product_attribute_translations_tl_com_attributes` (`attribute_id`);

--
-- Indexes for table `tl_com_product_cod_cities`
--
ALTER TABLE `tl_com_product_cod_cities`
  ADD PRIMARY KEY (`id`),
  ADD KEY `FK_tl_com_product_cod_cities_tl_com_products` (`product_id`),
  ADD KEY `FK_tl_com_product_cod_cities_tl_com_cities` (`city_id`);

--
-- Indexes for table `tl_com_product_cod_countries`
--
ALTER TABLE `tl_com_product_cod_countries`
  ADD PRIMARY KEY (`id`),
  ADD KEY `FK_tl_com_product_cod_countries_tl_com_products` (`product_id`),
  ADD KEY `FK_tl_com_product_cod_countries_tl_countries` (`country_id`);

--
-- Indexes for table `tl_com_product_cod_states`
--
ALTER TABLE `tl_com_product_cod_states`
  ADD PRIMARY KEY (`id`),
  ADD KEY `FK_tl_com_product_cod_states_tl_com_products` (`product_id`),
  ADD KEY `FK_tl_com_product_cod_states_tl_com_state` (`state_id`);

--
-- Indexes for table `tl_com_product_collections`
--
ALTER TABLE `tl_com_product_collections`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id` (`id`),
  ADD KEY `FK_tl_product_collections_tl_all_status` (`status`);

--
-- Indexes for table `tl_com_product_color_variant_image`
--
ALTER TABLE `tl_com_product_color_variant_image`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id` (`id`),
  ADD KEY `FK_tl_com_product_color_variant_image_tl_com_products` (`product_id`),
  ADD KEY `FK_product_color_variant_image_variant_product_price` (`color_id`);

--
-- Indexes for table `tl_com_product_conditions`
--
ALTER TABLE `tl_com_product_conditions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id` (`id`),
  ADD KEY `FK_tl_com_product_conditions_tl_all_status` (`status`);

--
-- Indexes for table `tl_com_product_condition_translations`
--
ALTER TABLE `tl_com_product_condition_translations`
  ADD PRIMARY KEY (`id`),
  ADD KEY `FK_tl_com_product_condition_translations_product_conditions` (`condition_id`);

--
-- Indexes for table `tl_com_product_gallery_images`
--
ALTER TABLE `tl_com_product_gallery_images`
  ADD PRIMARY KEY (`id`),
  ADD KEY `FK_tl_com_product_gallery_images_tl_com_products` (`product_id`),
  ADD KEY `FK_tl_com_product_gallery_images_tl_uploaded_files` (`image_id`);

--
-- Indexes for table `tl_com_product_has_categories`
--
ALTER TABLE `tl_com_product_has_categories`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id` (`id`),
  ADD KEY `FK_tl_com_product_has_categories_tl_com_products` (`product_id`),
  ADD KEY `FK_tl_com_product_has_categories_tl_com_categories` (`category_id`);

--
-- Indexes for table `tl_com_product_has_choices`
--
ALTER TABLE `tl_com_product_has_choices`
  ADD PRIMARY KEY (`id`),
  ADD KEY `FK_tl_com_product_has_choices_tl_com_products` (`product_id`),
  ADD KEY `choice_id` (`choice_id`);

--
-- Indexes for table `tl_com_product_has_choice_options`
--
ALTER TABLE `tl_com_product_has_choice_options`
  ADD PRIMARY KEY (`id`),
  ADD KEY `FK_tl_com_product_has_choice_options_tl_com_products` (`product_id`),
  ADD KEY `tl_com_product_has_choice_options_ibfk_1` (`choice_id`),
  ADD KEY `option_id` (`option_id`);

--
-- Indexes for table `tl_com_product_has_colors`
--
ALTER TABLE `tl_com_product_has_colors`
  ADD PRIMARY KEY (`id`),
  ADD KEY `FK_tl_com_product_has_colors_tl_com_products` (`product_id`),
  ADD KEY `FK_tl_com_product_has_colors_tl_com_colors` (`color_id`);

--
-- Indexes for table `tl_com_product_has_tags`
--
ALTER TABLE `tl_com_product_has_tags`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id` (`id`),
  ADD KEY `FK_tl_com_product_has_tags_tl_com_product_tags` (`tag_id`),
  ADD KEY `FK_tl_com_product_has_tags_tl_com_products` (`product_id`);

--
-- Indexes for table `tl_com_product_refund_reasons`
--
ALTER TABLE `tl_com_product_refund_reasons`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tl_com_product_reviews`
--
ALTER TABLE `tl_com_product_reviews`
  ADD PRIMARY KEY (`id`),
  ADD KEY `FK_tl_com_product_reviews_tl_com_products` (`product_id`),
  ADD KEY `FK_tl_com_product_reviews_tl_com_customers` (`customer_id`),
  ADD KEY `FK_tl_com_product_reviews_tl_com_orders` (`order_id`);

--
-- Indexes for table `tl_com_product_seo`
--
ALTER TABLE `tl_com_product_seo`
  ADD PRIMARY KEY (`id`),
  ADD KEY `FK_tl_com_product_seo_tl_com_products` (`product_id`),
  ADD KEY `FK_tl_com_product_seo_tl_uploaded_files` (`meta_image`);

--
-- Indexes for table `tl_com_product_share_options`
--
ALTER TABLE `tl_com_product_share_options`
  ADD PRIMARY KEY (`id`),
  ADD KEY `FK_tl_com_product_share_options_tl_all_status` (`status`);

--
-- Indexes for table `tl_com_product_shipping_info`
--
ALTER TABLE `tl_com_product_shipping_info`
  ADD PRIMARY KEY (`id`),
  ADD KEY `FK_tl_com_product_shipping_info_tl_com_products` (`product_id`);

--
-- Indexes for table `tl_com_product_tags`
--
ALTER TABLE `tl_com_product_tags`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id` (`id`),
  ADD KEY `FK_tl_com_product_tax_tl_all_status` (`status`);

--
-- Indexes for table `tl_com_product_translation`
--
ALTER TABLE `tl_com_product_translation`
  ADD PRIMARY KEY (`id`),
  ADD KEY `FK_tl_com_product_translation_tl_com_products` (`product_id`);

--
-- Indexes for table `tl_com_product_types`
--
ALTER TABLE `tl_com_product_types`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id` (`id`);

--
-- Indexes for table `tl_com_product_variant_combination`
--
ALTER TABLE `tl_com_product_variant_combination`
  ADD PRIMARY KEY (`id`),
  ADD KEY `tl_com_product_variant_combination_ibfk_1` (`attribute_id`),
  ADD KEY `tl_com_product_variant_combination_ibfk_2` (`attribute_value_id`),
  ADD KEY `tl_com_product_variant_combination_ibfk_3` (`color_id`),
  ADD KEY `product_variation_id` (`product_variation_id`);

--
-- Indexes for table `tl_com_recharge_type`
--
ALTER TABLE `tl_com_recharge_type`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id` (`id`);

--
-- Indexes for table `tl_com_refund_reason_translations`
--
ALTER TABLE `tl_com_refund_reason_translations`
  ADD PRIMARY KEY (`id`),
  ADD KEY `FK_refund_reason_translations_tl_com_product_refund_reasons` (`reason_id`);

--
-- Indexes for table `tl_com_refund_request_tracking`
--
ALTER TABLE `tl_com_refund_request_tracking`
  ADD PRIMARY KEY (`id`),
  ADD KEY `FK_tl_com_refund_request_tracking_tl_com_order_refund_requests` (`request_id`),
  ADD KEY `FK_tl_com_refund_request_tracking_tl_com_orders` (`order_id`);

--
-- Indexes for table `tl_com_related_products`
--
ALTER TABLE `tl_com_related_products`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id` (`id`),
  ADD KEY `FK__tl_com_products` (`parent_product_id`),
  ADD KEY `FK__tl_com_products_2` (`releted_product_id`);

--
-- Indexes for table `tl_com_seller_earning`
--
ALTER TABLE `tl_com_seller_earning`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `order_package_id` (`order_package_id`),
  ADD KEY `order_id` (`order_id`),
  ADD KEY `product_id` (`product_id`),
  ADD KEY `seller_id` (`seller_id`);

--
-- Indexes for table `tl_com_seller_followers`
--
ALTER TABLE `tl_com_seller_followers`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tl_com_seller_payout_info`
--
ALTER TABLE `tl_com_seller_payout_info`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tl_com_seller_payout_request`
--
ALTER TABLE `tl_com_seller_payout_request`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tl_com_seller_shop`
--
ALTER TABLE `tl_com_seller_shop`
  ADD PRIMARY KEY (`id`),
  ADD KEY `seller_id` (`seller_id`);

--
-- Indexes for table `tl_com_shipping_courier`
--
ALTER TABLE `tl_com_shipping_courier`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tl_com_shipping_profiles`
--
ALTER TABLE `tl_com_shipping_profiles`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tl_com_shipping_profiles_has_products`
--
ALTER TABLE `tl_com_shipping_profiles_has_products`
  ADD PRIMARY KEY (`id`),
  ADD KEY `FK_tl_com_shipping_profiles_has_products_tl_com_shipping_zones` (`profile_id`),
  ADD KEY `FK_tl_com_shipping_profiles_has_products_tl_com_products` (`product_id`);

--
-- Indexes for table `tl_com_shipping_times`
--
ALTER TABLE `tl_com_shipping_times`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tl_com_shipping_zones`
--
ALTER TABLE `tl_com_shipping_zones`
  ADD PRIMARY KEY (`id`),
  ADD KEY `FK_tl_com_shipping_zones_tl_com_shipping_profiles` (`profile_id`);

--
-- Indexes for table `tl_com_shipping_zone_has_cities`
--
ALTER TABLE `tl_com_shipping_zone_has_cities`
  ADD PRIMARY KEY (`id`),
  ADD KEY `FK_tl_com_shipping_zone_has_cities_tl_com_shipping_zones` (`zone_id`),
  ADD KEY `FK_tl_com_shipping_zone_has_cities_tl_com_cities` (`city_id`);

--
-- Indexes for table `tl_com_shipping_zone_has_countries`
--
ALTER TABLE `tl_com_shipping_zone_has_countries`
  ADD PRIMARY KEY (`id`),
  ADD KEY `FK_tl_com_shipping_zone_has_countries_tl_com_shipping_zones` (`zone_id`),
  ADD KEY `FK_tl_com_shipping_zone_has_countries_tl_countries` (`country_id`);

--
-- Indexes for table `tl_com_shipping_zone_has_rates`
--
ALTER TABLE `tl_com_shipping_zone_has_rates`
  ADD PRIMARY KEY (`id`),
  ADD KEY `FK_tl_com_shipping_zone_has_rates_tl_com_shipping_times` (`delivery_time`),
  ADD KEY `FK_tl_com_shipping_zone_has_rates_tl_com_shipping_zones` (`zone_id`),
  ADD KEY `FK_tl_com_shipping_zone_has_rates_tl_com_shipping_courier` (`carrier_id`);

--
-- Indexes for table `tl_com_shipping_zone_has_states`
--
ALTER TABLE `tl_com_shipping_zone_has_states`
  ADD PRIMARY KEY (`id`),
  ADD KEY `FK_tl_com_shipping_zone_has_states_tl_com_shipping_zones` (`zone_id`),
  ADD KEY `FK_tl_com_shipping_zone_has_states_tl_com_state` (`state_id`);

--
-- Indexes for table `tl_com_shipping_zone_has_taxes`
--
ALTER TABLE `tl_com_shipping_zone_has_taxes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `FK_tl_com_shipping_zone_has_taxes_tl_com_shipping_zones` (`zone_id`),
  ADD KEY `FK_tl_com_shipping_zone_has_taxes_tl_com_state` (`state_id`),
  ADD KEY `FK_tl_com_shipping_zone_has_taxes_tl_com_product_collections` (`product_collection_id`);

--
-- Indexes for table `tl_com_single_product_price`
--
ALTER TABLE `tl_com_single_product_price`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id` (`id`),
  ADD KEY `FK_tl_com_single_product_price_tl_com_products` (`product_id`);

--
-- Indexes for table `tl_com_state`
--
ALTER TABLE `tl_com_state`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id` (`id`),
  ADD KEY `FK_tl_com_state_tl_countries` (`country_id`);

--
-- Indexes for table `tl_com_state_translations`
--
ALTER TABLE `tl_com_state_translations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tl_com_tax_profiles`
--
ALTER TABLE `tl_com_tax_profiles`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tl_com_tax_rates`
--
ALTER TABLE `tl_com_tax_rates`
  ADD PRIMARY KEY (`id`),
  ADD KEY `profile_id` (`profile_id`),
  ADD KEY `country_id` (`country_id`),
  ADD KEY `city_id` (`city_id`),
  ADD KEY `state_id` (`state_id`);

--
-- Indexes for table `tl_com_units`
--
ALTER TABLE `tl_com_units`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id` (`id`),
  ADD KEY `FK_tl_com_units_tl_all_status` (`status`);

--
-- Indexes for table `tl_com_unit_translations`
--
ALTER TABLE `tl_com_unit_translations`
  ADD PRIMARY KEY (`id`),
  ADD KEY `FK_tl_com_unit_translations_tl_com_units` (`unit_id`);

--
-- Indexes for table `tl_com_variant_product_price`
--
ALTER TABLE `tl_com_variant_product_price`
  ADD PRIMARY KEY (`id`) USING BTREE,
  ADD UNIQUE KEY `id` (`id`) USING BTREE,
  ADD KEY `FK_tl_com_variant_product_price_tl_com_products` (`product_id`),
  ADD KEY `FK_tl_com_variant_product_price_tl_all_status` (`status`);

--
-- Indexes for table `tl_com_wallet_bank_information`
--
ALTER TABLE `tl_com_wallet_bank_information`
  ADD PRIMARY KEY (`id`),
  ADD KEY `FK_tl_com_wallet_bank_information_tl_com_payment_methods` (`payment_method_id`);

--
-- Indexes for table `tl_com_wallet_payment_methods`
--
ALTER TABLE `tl_com_wallet_payment_methods`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tl_com_wallet_recharges`
--
ALTER TABLE `tl_com_wallet_recharges`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id` (`id`),
  ADD KEY `FK_tl_com_wallet_recharges_tl_com_customers` (`customer_id`),
  ADD KEY `FK_tl_com_wallet_recharges_tl_com_payment_methods` (`payment_method_id`);

--
-- Indexes for table `tl_countries`
--
ALTER TABLE `tl_countries`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id` (`id`),
  ADD KEY `FK_tl_com_countries_tl_all_status` (`status`);

--
-- Indexes for table `tl_email_templates`
--
ALTER TABLE `tl_email_templates`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id` (`id`);

--
-- Indexes for table `tl_email_template_properties`
--
ALTER TABLE `tl_email_template_properties`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id` (`id`),
  ADD KEY `FK_tl_email_template_tl_notification_type` (`email_type`) USING BTREE;

--
-- Indexes for table `tl_email_template_variable`
--
ALTER TABLE `tl_email_template_variable`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id` (`id`);

--
-- Indexes for table `tl_general_settings`
--
ALTER TABLE `tl_general_settings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tl_general_settings_has_values`
--
ALTER TABLE `tl_general_settings_has_values`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id` (`id`),
  ADD KEY `FK_tl_general_settings_has_value_tl_general_settings` (`settings_id`);

--
-- Indexes for table `tl_languages`
--
ALTER TABLE `tl_languages`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id` (`id`),
  ADD KEY `FK_tl_language_tl_all_status` (`status`);

--
-- Indexes for table `tl_media_drivers`
--
ALTER TABLE `tl_media_drivers`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id` (`id`),
  ADD KEY `FK_tl_media_drivers_tl_all_status` (`status`);

--
-- Indexes for table `tl_media_driver_settings`
--
ALTER TABLE `tl_media_driver_settings`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id` (`id`),
  ADD KEY `FK__tl_media_drivers` (`media_driver_id`);

--
-- Indexes for table `tl_media_type`
--
ALTER TABLE `tl_media_type`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id` (`id`);

--
-- Indexes for table `tl_menus`
--
ALTER TABLE `tl_menus`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id` (`id`),
  ADD KEY `FK_tl_menus_tl_menu_groups` (`menu_group_id`);

--
-- Indexes for table `tl_menu_groups`
--
ALTER TABLE `tl_menu_groups`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id` (`id`);

--
-- Indexes for table `tl_menu_groups_translations`
--
ALTER TABLE `tl_menu_groups_translations`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id` (`id`),
  ADD KEY `FK_tl_menu_groups_translations_tl_menu_groups` (`menu_group_id`);

--
-- Indexes for table `tl_menu_group_has_positon`
--
ALTER TABLE `tl_menu_group_has_positon`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id` (`id`),
  ADD KEY `FK_tl_menu_group_has_positon_tl_menu_groups` (`menu_group_id`),
  ADD KEY `FK_tl_menu_group_has_positon_tl_menu_positions` (`menu_position_id`);

--
-- Indexes for table `tl_menu_items`
--
ALTER TABLE `tl_menu_items`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id` (`id`);

--
-- Indexes for table `tl_menu_positions`
--
ALTER TABLE `tl_menu_positions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id` (`id`),
  ADD KEY `FK_tl_menu_positions_tl_themes` (`theme_id`);

--
-- Indexes for table `tl_menu_translations`
--
ALTER TABLE `tl_menu_translations`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id` (`id`),
  ADD KEY `FK_tl_menu_translations_tl_menus` (`menu_id`);

--
-- Indexes for table `tl_pages`
--
ALTER TABLE `tl_pages`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id` (`id`),
  ADD KEY `FK_tl_pages_tl_users` (`user_id`);

--
-- Indexes for table `tl_page_templates`
--
ALTER TABLE `tl_page_templates`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id` (`id`);

--
-- Indexes for table `tl_page_translations`
--
ALTER TABLE `tl_page_translations`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id` (`id`),
  ADD KEY `FK_tl_page_translations_tl_pages` (`page_id`);

--
-- Indexes for table `tl_pick_up_points`
--
ALTER TABLE `tl_pick_up_points`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id` (`id`);

--
-- Indexes for table `tl_pick_up_points_translations`
--
ALTER TABLE `tl_pick_up_points_translations`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id` (`id`),
  ADD KEY `FK__tl_pick_up_points` (`pic_up_point_id`);

--
-- Indexes for table `tl_plugins`
--
ALTER TABLE `tl_plugins`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_location` (`location`);

--
-- Indexes for table `tl_product_types`
--
ALTER TABLE `tl_product_types`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id` (`id`),
  ADD KEY `FK_tl_product_types_tl_all_status` (`status`);

--
-- Indexes for table `tl_sidebar_has_widgets`
--
ALTER TABLE `tl_sidebar_has_widgets`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id` (`id`),
  ADD KEY `FK_tl_sidebar_has_widgets_tl_theme_sidebars` (`sidebar_id`),
  ADD KEY `FK_tl_sidebar_has_widgets_tl_widgets` (`widget_id`);

--
-- Indexes for table `tl_sidebar_widget_has_translate_values`
--
ALTER TABLE `tl_sidebar_widget_has_translate_values`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id` (`id`),
  ADD KEY `sidebar_widget_has_values_id` (`sidebar_widget_has_values_id`);

--
-- Indexes for table `tl_sidebar_widget_has_values`
--
ALTER TABLE `tl_sidebar_widget_has_values`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id` (`id`),
  ADD KEY `FK_tl_sidebar_widget_has_values_tl_sidebar_has_widgets` (`sidebar_has_widget_id`);

--
-- Indexes for table `tl_smtps`
--
ALTER TABLE `tl_smtps`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id` (`id`);

--
-- Indexes for table `tl_smtp_configs`
--
ALTER TABLE `tl_smtp_configs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id` (`id`),
  ADD KEY `FK_tl_smtp_configs_tl_smtps` (`smtp_id`);

--
-- Indexes for table `tl_themes`
--
ALTER TABLE `tl_themes`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tl_theme_option_settings`
--
ALTER TABLE `tl_theme_option_settings`
  ADD PRIMARY KEY (`id`),
  ADD KEY `theme_id` (`theme_id`);

--
-- Indexes for table `tl_theme_sidebars`
--
ALTER TABLE `tl_theme_sidebars`
  ADD PRIMARY KEY (`id`),
  ADD KEY `theme_id` (`theme_id`);

--
-- Indexes for table `tl_theme_tlcommerce_home_page_sections`
--
ALTER TABLE `tl_theme_tlcommerce_home_page_sections`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tl_theme_tlcommerce_home_page_sections_properties`
--
ALTER TABLE `tl_theme_tlcommerce_home_page_sections_properties`
  ADD PRIMARY KEY (`id`),
  ADD KEY `FK_tl_home_page_sections_properties_tl_home_page_sections` (`section_id`);

--
-- Indexes for table `tl_theme_tlcommerce_sliders`
--
ALTER TABLE `tl_theme_tlcommerce_sliders`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tl_theme_translations`
--
ALTER TABLE `tl_theme_translations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tl_translations`
--
ALTER TABLE `tl_translations`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id` (`id`);

--
-- Indexes for table `tl_translation_modules`
--
ALTER TABLE `tl_translation_modules`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id` (`id`);

--
-- Indexes for table `tl_uploaded_files`
--
ALTER TABLE `tl_uploaded_files`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id` (`id`),
  ADD KEY `FK_tl_uploaded_files_tl_media_image_type` (`media_type`) USING BTREE;

--
-- Indexes for table `tl_users`
--
ALTER TABLE `tl_users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id` (`id`);

--
-- Indexes for table `tl_user_types`
--
ALTER TABLE `tl_user_types`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id` (`id`);

--
-- Indexes for table `tl_water_mark_image_applicable_folders`
--
ALTER TABLE `tl_water_mark_image_applicable_folders`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id` (`id`),
  ADD KEY `FK_tl_water_mark_image_applicable_folders_tl_media_image_type` (`image_type`);

--
-- Indexes for table `tl_widgets`
--
ALTER TABLE `tl_widgets`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id` (`id`),
  ADD KEY `theme_id` (`theme_id`);

--
-- Indexes for table `user_keys`
--
ALTER TABLE `user_keys`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `activity_log`
--
ALTER TABLE `activity_log`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `admin_login_activity_log`
--
ALTER TABLE `admin_login_activity_log`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `model_has_roles`
--
ALTER TABLE `model_has_roles`
  MODIFY `role_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `page_builder_sections`
--
ALTER TABLE `page_builder_sections`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=166;

--
-- AUTO_INCREMENT for table `page_builder_sections_layout_widget_properties`
--
ALTER TABLE `page_builder_sections_layout_widget_properties`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=212;

--
-- AUTO_INCREMENT for table `page_builder_sections_properties`
--
ALTER TABLE `page_builder_sections_properties`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=111;

--
-- AUTO_INCREMENT for table `page_builder_section_layouts`
--
ALTER TABLE `page_builder_section_layouts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=302;

--
-- AUTO_INCREMENT for table `page_builder_section_layout_widgets`
--
ALTER TABLE `page_builder_section_layout_widgets`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=317;

--
-- AUTO_INCREMENT for table `page_builder_widgets`
--
ALTER TABLE `page_builder_widgets`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `page_builder_widget_translations`
--
ALTER TABLE `page_builder_widget_translations`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `permission_module`
--
ALTER TABLE `permission_module`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=93;

--
-- AUTO_INCREMENT for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `roles`
--
ALTER TABLE `roles`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `role_has_permissions`
--
ALTER TABLE `role_has_permissions`
  MODIFY `permission_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=105;

--
-- AUTO_INCREMENT for table `timezones`
--
ALTER TABLE `timezones`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tl_amount_types`
--
ALTER TABLE `tl_amount_types`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `tl_blogs`
--
ALTER TABLE `tl_blogs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `tl_blogs_categories`
--
ALTER TABLE `tl_blogs_categories`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `tl_blogs_tags`
--
ALTER TABLE `tl_blogs_tags`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tl_blog_categories`
--
ALTER TABLE `tl_blog_categories`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `tl_blog_category_translations`
--
ALTER TABLE `tl_blog_category_translations`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tl_blog_comments`
--
ALTER TABLE `tl_blog_comments`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tl_blog_tags`
--
ALTER TABLE `tl_blog_tags`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tl_blog_tag_translations`
--
ALTER TABLE `tl_blog_tag_translations`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tl_blog_translations`
--
ALTER TABLE `tl_blog_translations`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tl_com_attributes`
--
ALTER TABLE `tl_com_attributes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tl_com_attribute_values`
--
ALTER TABLE `tl_com_attribute_values`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tl_com_bank_payments`
--
ALTER TABLE `tl_com_bank_payments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tl_com_brands`
--
ALTER TABLE `tl_com_brands`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tl_com_brand_translations`
--
ALTER TABLE `tl_com_brand_translations`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tl_com_cart_items`
--
ALTER TABLE `tl_com_cart_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `tl_com_cash_back_configs`
--
ALTER TABLE `tl_com_cash_back_configs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tl_com_cash_back_offer`
--
ALTER TABLE `tl_com_cash_back_offer`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tl_com_categories`
--
ALTER TABLE `tl_com_categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `tl_com_category_has_commission`
--
ALTER TABLE `tl_com_category_has_commission`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;

--
-- AUTO_INCREMENT for table `tl_com_category_translations`
--
ALTER TABLE `tl_com_category_translations`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tl_com_cities`
--
ALTER TABLE `tl_com_cities`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=48357;

--
-- AUTO_INCREMENT for table `tl_com_city_translations`
--
ALTER TABLE `tl_com_city_translations`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tl_com_collection_translations`
--
ALTER TABLE `tl_com_collection_translations`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tl_com_colletions_has_products`
--
ALTER TABLE `tl_com_colletions_has_products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tl_com_colors`
--
ALTER TABLE `tl_com_colors`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tl_com_color_translations`
--
ALTER TABLE `tl_com_color_translations`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tl_com_country_translations`
--
ALTER TABLE `tl_com_country_translations`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tl_com_coupons`
--
ALTER TABLE `tl_com_coupons`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tl_com_coupon_brands`
--
ALTER TABLE `tl_com_coupon_brands`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tl_com_coupon_category`
--
ALTER TABLE `tl_com_coupon_category`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tl_com_coupon_exclude_brands`
--
ALTER TABLE `tl_com_coupon_exclude_brands`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tl_com_coupon_exclude_category`
--
ALTER TABLE `tl_com_coupon_exclude_category`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tl_com_coupon_exclude_products`
--
ALTER TABLE `tl_com_coupon_exclude_products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tl_com_coupon_products`
--
ALTER TABLE `tl_com_coupon_products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tl_com_coupon_usages`
--
ALTER TABLE `tl_com_coupon_usages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tl_com_currencies`
--
ALTER TABLE `tl_com_currencies`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `tl_com_customers`
--
ALTER TABLE `tl_com_customers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `tl_com_customer_address`
--
ALTER TABLE `tl_com_customer_address`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `tl_com_customer_wishlists`
--
ALTER TABLE `tl_com_customer_wishlists`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tl_com_custom_notifications`
--
ALTER TABLE `tl_com_custom_notifications`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tl_com_deals_products`
--
ALTER TABLE `tl_com_deals_products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `tl_com_ecommerce_settings`
--
ALTER TABLE `tl_com_ecommerce_settings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=50;

--
-- AUTO_INCREMENT for table `tl_com_flash_deal`
--
ALTER TABLE `tl_com_flash_deal`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `tl_com_flash_deal_translations`
--
ALTER TABLE `tl_com_flash_deal_translations`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tl_com_guest_customer`
--
ALTER TABLE `tl_com_guest_customer`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tl_com_key_word_search`
--
ALTER TABLE `tl_com_key_word_search`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tl_com_location_wise_shipping_rates`
--
ALTER TABLE `tl_com_location_wise_shipping_rates`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tl_com_location_wise_shipping_rate_cities`
--
ALTER TABLE `tl_com_location_wise_shipping_rate_cities`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tl_com_location_wise_shipping_rate_countries`
--
ALTER TABLE `tl_com_location_wise_shipping_rate_countries`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tl_com_location_wise_shipping_rate_states`
--
ALTER TABLE `tl_com_location_wise_shipping_rate_states`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tl_com_ordered_products`
--
ALTER TABLE `tl_com_ordered_products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `tl_com_orders`
--
ALTER TABLE `tl_com_orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `tl_com_order_package_trackings`
--
ALTER TABLE `tl_com_order_package_trackings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `tl_com_order_refund_requests`
--
ALTER TABLE `tl_com_order_refund_requests`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tl_com_payment_methods`
--
ALTER TABLE `tl_com_payment_methods`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `tl_com_payment_method_has_settings`
--
ALTER TABLE `tl_com_payment_method_has_settings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=52;

--
-- AUTO_INCREMENT for table `tl_com_payment_transactions`
--
ALTER TABLE `tl_com_payment_transactions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tl_com_products`
--
ALTER TABLE `tl_com_products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `tl_com_product_attribute_translations`
--
ALTER TABLE `tl_com_product_attribute_translations`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tl_com_product_cod_cities`
--
ALTER TABLE `tl_com_product_cod_cities`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tl_com_product_cod_countries`
--
ALTER TABLE `tl_com_product_cod_countries`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tl_com_product_cod_states`
--
ALTER TABLE `tl_com_product_cod_states`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tl_com_product_collections`
--
ALTER TABLE `tl_com_product_collections`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tl_com_product_color_variant_image`
--
ALTER TABLE `tl_com_product_color_variant_image`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tl_com_product_conditions`
--
ALTER TABLE `tl_com_product_conditions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `tl_com_product_condition_translations`
--
ALTER TABLE `tl_com_product_condition_translations`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `tl_com_product_gallery_images`
--
ALTER TABLE `tl_com_product_gallery_images`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tl_com_product_has_categories`
--
ALTER TABLE `tl_com_product_has_categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `tl_com_product_has_choices`
--
ALTER TABLE `tl_com_product_has_choices`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tl_com_product_has_choice_options`
--
ALTER TABLE `tl_com_product_has_choice_options`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tl_com_product_has_colors`
--
ALTER TABLE `tl_com_product_has_colors`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tl_com_product_has_tags`
--
ALTER TABLE `tl_com_product_has_tags`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tl_com_product_refund_reasons`
--
ALTER TABLE `tl_com_product_refund_reasons`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `tl_com_product_reviews`
--
ALTER TABLE `tl_com_product_reviews`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tl_com_product_seo`
--
ALTER TABLE `tl_com_product_seo`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `tl_com_product_share_options`
--
ALTER TABLE `tl_com_product_share_options`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `tl_com_product_shipping_info`
--
ALTER TABLE `tl_com_product_shipping_info`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `tl_com_product_tags`
--
ALTER TABLE `tl_com_product_tags`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tl_com_product_translation`
--
ALTER TABLE `tl_com_product_translation`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tl_com_product_types`
--
ALTER TABLE `tl_com_product_types`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `tl_com_product_variant_combination`
--
ALTER TABLE `tl_com_product_variant_combination`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tl_com_recharge_type`
--
ALTER TABLE `tl_com_recharge_type`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `tl_com_refund_reason_translations`
--
ALTER TABLE `tl_com_refund_reason_translations`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `tl_com_refund_request_tracking`
--
ALTER TABLE `tl_com_refund_request_tracking`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tl_com_related_products`
--
ALTER TABLE `tl_com_related_products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tl_com_seller_earning`
--
ALTER TABLE `tl_com_seller_earning`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `tl_com_seller_followers`
--
ALTER TABLE `tl_com_seller_followers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `tl_com_seller_payout_info`
--
ALTER TABLE `tl_com_seller_payout_info`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `tl_com_seller_payout_request`
--
ALTER TABLE `tl_com_seller_payout_request`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `tl_com_seller_shop`
--
ALTER TABLE `tl_com_seller_shop`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `tl_com_shipping_courier`
--
ALTER TABLE `tl_com_shipping_courier`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tl_com_shipping_profiles`
--
ALTER TABLE `tl_com_shipping_profiles`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `tl_com_shipping_profiles_has_products`
--
ALTER TABLE `tl_com_shipping_profiles_has_products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `tl_com_shipping_times`
--
ALTER TABLE `tl_com_shipping_times`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `tl_com_shipping_zones`
--
ALTER TABLE `tl_com_shipping_zones`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `tl_com_shipping_zone_has_cities`
--
ALTER TABLE `tl_com_shipping_zone_has_cities`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `tl_com_shipping_zone_has_countries`
--
ALTER TABLE `tl_com_shipping_zone_has_countries`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `tl_com_shipping_zone_has_rates`
--
ALTER TABLE `tl_com_shipping_zone_has_rates`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `tl_com_shipping_zone_has_states`
--
ALTER TABLE `tl_com_shipping_zone_has_states`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `tl_com_shipping_zone_has_taxes`
--
ALTER TABLE `tl_com_shipping_zone_has_taxes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tl_com_single_product_price`
--
ALTER TABLE `tl_com_single_product_price`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `tl_com_state`
--
ALTER TABLE `tl_com_state`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4122;

--
-- AUTO_INCREMENT for table `tl_com_state_translations`
--
ALTER TABLE `tl_com_state_translations`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tl_com_tax_profiles`
--
ALTER TABLE `tl_com_tax_profiles`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tl_com_tax_rates`
--
ALTER TABLE `tl_com_tax_rates`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tl_com_units`
--
ALTER TABLE `tl_com_units`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `tl_com_unit_translations`
--
ALTER TABLE `tl_com_unit_translations`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `tl_com_variant_product_price`
--
ALTER TABLE `tl_com_variant_product_price`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tl_com_wallet_bank_information`
--
ALTER TABLE `tl_com_wallet_bank_information`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tl_com_wallet_payment_methods`
--
ALTER TABLE `tl_com_wallet_payment_methods`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tl_com_wallet_recharges`
--
ALTER TABLE `tl_com_wallet_recharges`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tl_countries`
--
ALTER TABLE `tl_countries`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=247;

--
-- AUTO_INCREMENT for table `tl_email_templates`
--
ALTER TABLE `tl_email_templates`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `tl_email_template_properties`
--
ALTER TABLE `tl_email_template_properties`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `tl_email_template_variable`
--
ALTER TABLE `tl_email_template_variable`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=40;

--
-- AUTO_INCREMENT for table `tl_general_settings`
--
ALTER TABLE `tl_general_settings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=360;

--
-- AUTO_INCREMENT for table `tl_general_settings_has_values`
--
ALTER TABLE `tl_general_settings_has_values`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1903;

--
-- AUTO_INCREMENT for table `tl_languages`
--
ALTER TABLE `tl_languages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `tl_media_drivers`
--
ALTER TABLE `tl_media_drivers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `tl_media_driver_settings`
--
ALTER TABLE `tl_media_driver_settings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tl_media_type`
--
ALTER TABLE `tl_media_type`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `tl_menus`
--
ALTER TABLE `tl_menus`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT for table `tl_menu_groups`
--
ALTER TABLE `tl_menu_groups`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT for table `tl_menu_groups_translations`
--
ALTER TABLE `tl_menu_groups_translations`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `tl_menu_group_has_positon`
--
ALTER TABLE `tl_menu_group_has_positon`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=122;

--
-- AUTO_INCREMENT for table `tl_menu_items`
--
ALTER TABLE `tl_menu_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `tl_menu_positions`
--
ALTER TABLE `tl_menu_positions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `tl_menu_translations`
--
ALTER TABLE `tl_menu_translations`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tl_pages`
--
ALTER TABLE `tl_pages`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tl_page_templates`
--
ALTER TABLE `tl_page_templates`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tl_page_translations`
--
ALTER TABLE `tl_page_translations`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tl_pick_up_points`
--
ALTER TABLE `tl_pick_up_points`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tl_pick_up_points_translations`
--
ALTER TABLE `tl_pick_up_points_translations`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tl_plugins`
--
ALTER TABLE `tl_plugins`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT for table `tl_product_types`
--
ALTER TABLE `tl_product_types`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tl_sidebar_has_widgets`
--
ALTER TABLE `tl_sidebar_has_widgets`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=774;

--
-- AUTO_INCREMENT for table `tl_sidebar_widget_has_translate_values`
--
ALTER TABLE `tl_sidebar_widget_has_translate_values`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `tl_sidebar_widget_has_values`
--
ALTER TABLE `tl_sidebar_widget_has_values`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=184;

--
-- AUTO_INCREMENT for table `tl_smtps`
--
ALTER TABLE `tl_smtps`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tl_smtp_configs`
--
ALTER TABLE `tl_smtp_configs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tl_themes`
--
ALTER TABLE `tl_themes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `tl_theme_option_settings`
--
ALTER TABLE `tl_theme_option_settings`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4245;

--
-- AUTO_INCREMENT for table `tl_theme_sidebars`
--
ALTER TABLE `tl_theme_sidebars`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `tl_theme_tlcommerce_home_page_sections`
--
ALTER TABLE `tl_theme_tlcommerce_home_page_sections`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `tl_theme_tlcommerce_home_page_sections_properties`
--
ALTER TABLE `tl_theme_tlcommerce_home_page_sections_properties`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=301;

--
-- AUTO_INCREMENT for table `tl_theme_tlcommerce_sliders`
--
ALTER TABLE `tl_theme_tlcommerce_sliders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `tl_theme_translations`
--
ALTER TABLE `tl_theme_translations`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1371;

--
-- AUTO_INCREMENT for table `tl_translations`
--
ALTER TABLE `tl_translations`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4914;

--
-- AUTO_INCREMENT for table `tl_translation_modules`
--
ALTER TABLE `tl_translation_modules`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tl_uploaded_files`
--
ALTER TABLE `tl_uploaded_files`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=55;

--
-- AUTO_INCREMENT for table `tl_users`
--
ALTER TABLE `tl_users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `tl_user_types`
--
ALTER TABLE `tl_user_types`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tl_water_mark_image_applicable_folders`
--
ALTER TABLE `tl_water_mark_image_applicable_folders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tl_widgets`
--
ALTER TABLE `tl_widgets`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=98;

--
-- AUTO_INCREMENT for table `user_keys`
--
ALTER TABLE `user_keys`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `admin_login_activity_log`
--
ALTER TABLE `admin_login_activity_log`
  ADD CONSTRAINT `FK_admin_login_activity_log_tl_users` FOREIGN KEY (`user_id`) REFERENCES `tl_users` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `model_has_permissions`
--
ALTER TABLE `model_has_permissions`
  ADD CONSTRAINT `FK_model_has_permissions_permissions` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `FK_model_has_permissions_tl_users` FOREIGN KEY (`model_id`) REFERENCES `tl_users` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `model_has_roles`
--
ALTER TABLE `model_has_roles`
  ADD CONSTRAINT `FK_model_has_roles_roles` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `FK_model_has_roles_tl_users` FOREIGN KEY (`model_id`) REFERENCES `tl_users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `page_builder_sections`
--
ALTER TABLE `page_builder_sections`
  ADD CONSTRAINT `page_builder_sections_ibfk_1` FOREIGN KEY (`page_id`) REFERENCES `tl_pages` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `page_builder_sections_layout_widget_properties`
--
ALTER TABLE `page_builder_sections_layout_widget_properties`
  ADD CONSTRAINT `page_builder_sections_layout_widget_properties_ibfk_1` FOREIGN KEY (`layout_has_widget_id`) REFERENCES `page_builder_section_layout_widgets` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `page_builder_sections_properties`
--
ALTER TABLE `page_builder_sections_properties`
  ADD CONSTRAINT `page_builder_sections_properties_ibfk_1` FOREIGN KEY (`section_id`) REFERENCES `page_builder_sections` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `page_builder_section_layouts`
--
ALTER TABLE `page_builder_section_layouts`
  ADD CONSTRAINT `page_builder_section_layouts_ibfk_1` FOREIGN KEY (`section_id`) REFERENCES `page_builder_sections` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `page_builder_section_layout_widgets`
--
ALTER TABLE `page_builder_section_layout_widgets`
  ADD CONSTRAINT `page_builder_section_layout_widgets_ibfk_1` FOREIGN KEY (`section_layout_id`) REFERENCES `page_builder_section_layouts` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `page_builder_section_layout_widgets_ibfk_2` FOREIGN KEY (`page_widget_id`) REFERENCES `page_builder_widgets` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `page_builder_widget_translations`
--
ALTER TABLE `page_builder_widget_translations`
  ADD CONSTRAINT `page_builder_widget_translations_ibfk_1` FOREIGN KEY (`layout_widget_properties_id`) REFERENCES `page_builder_sections_layout_widget_properties` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `permissions`
--
ALTER TABLE `permissions`
  ADD CONSTRAINT `FK_permissions_permission_module` FOREIGN KEY (`module_id`) REFERENCES `permission_module` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `role_has_permissions`
--
ALTER TABLE `role_has_permissions`
  ADD CONSTRAINT `FK_role_has_permissions_permissions` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `FK_role_has_permissions_roles` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `tl_blogs`
--
ALTER TABLE `tl_blogs`
  ADD CONSTRAINT `FK_tl_blogs_tl_users` FOREIGN KEY (`user_id`) REFERENCES `tl_users` (`id`) ON UPDATE CASCADE;

--
-- Constraints for table `tl_blogs_categories`
--
ALTER TABLE `tl_blogs_categories`
  ADD CONSTRAINT `FK_tl_blogs_categories_tl_blog_categories` FOREIGN KEY (`category_id`) REFERENCES `tl_blog_categories` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `FK_tl_blogs_categories_tl_blogs` FOREIGN KEY (`blog_id`) REFERENCES `tl_blogs` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `tl_blogs_tags`
--
ALTER TABLE `tl_blogs_tags`
  ADD CONSTRAINT `FK_tl_blogs_tags_tl_blog_tags` FOREIGN KEY (`tag_id`) REFERENCES `tl_blog_tags` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `FK_tl_blogs_tags_tl_blogs` FOREIGN KEY (`blog_id`) REFERENCES `tl_blogs` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `tl_blog_categories`
--
ALTER TABLE `tl_blog_categories`
  ADD CONSTRAINT `FK_tl_blog_categories_tl_blog_categories` FOREIGN KEY (`parent`) REFERENCES `tl_blog_categories` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `tl_blog_category_translations`
--
ALTER TABLE `tl_blog_category_translations`
  ADD CONSTRAINT `FK_tl_blog_category_translations_tl_blog_categories` FOREIGN KEY (`category_id`) REFERENCES `tl_blog_categories` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `tl_blog_comments`
--
ALTER TABLE `tl_blog_comments`
  ADD CONSTRAINT `FK_tl_blog_comments_tl_blog_comments` FOREIGN KEY (`parent`) REFERENCES `tl_blog_comments` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `FK_tl_blog_comments_tl_blogs` FOREIGN KEY (`blog_id`) REFERENCES `tl_blogs` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `tl_blog_tag_translations`
--
ALTER TABLE `tl_blog_tag_translations`
  ADD CONSTRAINT `FK_tl_blog_tag_translations_tl_blog_tags` FOREIGN KEY (`tag_id`) REFERENCES `tl_blog_tags` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `tl_blog_translations`
--
ALTER TABLE `tl_blog_translations`
  ADD CONSTRAINT `FK_tl_blog_translations_tl_blogs` FOREIGN KEY (`blog_id`) REFERENCES `tl_blogs` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `tl_com_attribute_values`
--
ALTER TABLE `tl_com_attribute_values`
  ADD CONSTRAINT `FK_tl_com_attribute_values_tl_com_attributes` FOREIGN KEY (`attribute_id`) REFERENCES `tl_com_attributes` (`id`);

--
-- Constraints for table `tl_com_brand_translations`
--
ALTER TABLE `tl_com_brand_translations`
  ADD CONSTRAINT `FK_tl_com_brand_translations_tl_com_brands` FOREIGN KEY (`brand_id`) REFERENCES `tl_com_brands` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION;

--
-- Constraints for table `tl_com_cart_items`
--
ALTER TABLE `tl_com_cart_items`
  ADD CONSTRAINT `FK_tl_com_cart_items_tl_com_products` FOREIGN KEY (`product_id`) REFERENCES `tl_com_products` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `FK_tl_com_cart_items_tl_customers` FOREIGN KEY (`customer_id`) REFERENCES `tl_com_customers` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `tl_com_cash_back_offer`
--
ALTER TABLE `tl_com_cash_back_offer`
  ADD CONSTRAINT `FK_tl_com_cash_back_offer_tl_com_products` FOREIGN KEY (`product_id`) REFERENCES `tl_com_products` (`id`);

--
-- Constraints for table `tl_com_categories`
--
ALTER TABLE `tl_com_categories`
  ADD CONSTRAINT `FK_tl_com_categories_tl_com_categories` FOREIGN KEY (`parent`) REFERENCES `tl_com_categories` (`id`) ON UPDATE CASCADE;

--
-- Constraints for table `tl_com_category_has_commission`
--
ALTER TABLE `tl_com_category_has_commission`
  ADD CONSTRAINT `tl_com_category_has_commission_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `tl_com_categories` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION;

--
-- Constraints for table `tl_com_category_translations`
--
ALTER TABLE `tl_com_category_translations`
  ADD CONSTRAINT `FK_tl_com_category_translations_tl_com_categories` FOREIGN KEY (`category_id`) REFERENCES `tl_com_categories` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION;

--
-- Constraints for table `tl_com_cities`
--
ALTER TABLE `tl_com_cities`
  ADD CONSTRAINT `FK_tl_com_cities_tl_com_state` FOREIGN KEY (`state_id`) REFERENCES `tl_com_state` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION;

--
-- Constraints for table `tl_com_collection_translations`
--
ALTER TABLE `tl_com_collection_translations`
  ADD CONSTRAINT `FK_tl_com_collection_translations_tl_com_product_collections` FOREIGN KEY (`collection_id`) REFERENCES `tl_com_product_collections` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION;

--
-- Constraints for table `tl_com_colletions_has_products`
--
ALTER TABLE `tl_com_colletions_has_products`
  ADD CONSTRAINT `FK_tl_com_colletions_has_products_tl_com_product_collections` FOREIGN KEY (`collection_id`) REFERENCES `tl_com_product_collections` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION,
  ADD CONSTRAINT `FK_tl_com_colletions_has_products_tl_com_products` FOREIGN KEY (`product_id`) REFERENCES `tl_com_products` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION;

--
-- Constraints for table `tl_com_color_translations`
--
ALTER TABLE `tl_com_color_translations`
  ADD CONSTRAINT `FK_tl_com_color_translations_tl_com_colors` FOREIGN KEY (`color_id`) REFERENCES `tl_com_colors` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION;

--
-- Constraints for table `tl_com_coupon_brands`
--
ALTER TABLE `tl_com_coupon_brands`
  ADD CONSTRAINT `FK_tl_com_coupon_brands_tl_com_brands` FOREIGN KEY (`brand_id`) REFERENCES `tl_com_brands` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION,
  ADD CONSTRAINT `FK_tl_com_coupon_brands_tl_com_coupons` FOREIGN KEY (`coupon_id`) REFERENCES `tl_com_coupons` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION;

--
-- Constraints for table `tl_com_coupon_category`
--
ALTER TABLE `tl_com_coupon_category`
  ADD CONSTRAINT `FK_tl_com_coupon_category_tl_com_categories` FOREIGN KEY (`category_id`) REFERENCES `tl_com_categories` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION,
  ADD CONSTRAINT `FK_tl_com_coupon_category_tl_com_coupons` FOREIGN KEY (`coupon_id`) REFERENCES `tl_com_coupons` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION;

--
-- Constraints for table `tl_com_coupon_exclude_brands`
--
ALTER TABLE `tl_com_coupon_exclude_brands`
  ADD CONSTRAINT `FK_tl_com_coupon_exclude_brands_tl_com_brands` FOREIGN KEY (`brand_id`) REFERENCES `tl_com_brands` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION,
  ADD CONSTRAINT `FK_tl_com_coupon_exclude_brands_tl_com_coupons` FOREIGN KEY (`coupon_id`) REFERENCES `tl_com_coupons` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION;

--
-- Constraints for table `tl_com_coupon_exclude_category`
--
ALTER TABLE `tl_com_coupon_exclude_category`
  ADD CONSTRAINT `FK_tl_com_coupon_exclude_category_tl_com_categories` FOREIGN KEY (`category_id`) REFERENCES `tl_com_categories` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION,
  ADD CONSTRAINT `FK_tl_com_coupon_exclude_category_tl_com_coupons` FOREIGN KEY (`coupon_id`) REFERENCES `tl_com_coupons` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION;

--
-- Constraints for table `tl_com_coupon_exclude_products`
--
ALTER TABLE `tl_com_coupon_exclude_products`
  ADD CONSTRAINT `FK_tl_com_coupon_exclude_products_tl_com_coupons` FOREIGN KEY (`coupon_id`) REFERENCES `tl_com_coupons` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION,
  ADD CONSTRAINT `FK_tl_com_coupon_exclude_products_tl_com_products` FOREIGN KEY (`product_id`) REFERENCES `tl_com_products` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION;

--
-- Constraints for table `tl_com_coupon_products`
--
ALTER TABLE `tl_com_coupon_products`
  ADD CONSTRAINT `FK_tl_com_coupon_products_tl_com_coupons` FOREIGN KEY (`coupon_id`) REFERENCES `tl_com_coupons` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION,
  ADD CONSTRAINT `FK_tl_com_coupon_products_tl_com_products` FOREIGN KEY (`product_id`) REFERENCES `tl_com_products` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION;

--
-- Constraints for table `tl_com_coupon_usages`
--
ALTER TABLE `tl_com_coupon_usages`
  ADD CONSTRAINT `tl_com_coupon_usages_ibfk_1` FOREIGN KEY (`coupon_id`) REFERENCES `tl_com_coupons` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `tl_com_coupon_usages_ibfk_2` FOREIGN KEY (`customer_id`) REFERENCES `tl_com_customers` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `tl_com_coupon_usages_ibfk_3` FOREIGN KEY (`order_id`) REFERENCES `tl_com_orders` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `tl_com_customer_address`
--
ALTER TABLE `tl_com_customer_address`
  ADD CONSTRAINT `FK_tl_com_customer_address_tl_com_cities` FOREIGN KEY (`city_id`) REFERENCES `tl_com_cities` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION,
  ADD CONSTRAINT `FK_tl_com_customer_address_tl_com_customers` FOREIGN KEY (`customer_id`) REFERENCES `tl_com_customers` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION,
  ADD CONSTRAINT `FK_tl_com_customer_address_tl_com_state` FOREIGN KEY (`state_id`) REFERENCES `tl_com_state` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION,
  ADD CONSTRAINT `FK_tl_com_customer_address_tl_countries` FOREIGN KEY (`country_id`) REFERENCES `tl_countries` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `tl_com_customer_address_ibfk_1` FOREIGN KEY (`guest_customer`) REFERENCES `tl_com_guest_customer` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `tl_com_customer_wishlists`
--
ALTER TABLE `tl_com_customer_wishlists`
  ADD CONSTRAINT `FK_tl_com_customer_wishlists_tl_com_customers` FOREIGN KEY (`customer_id`) REFERENCES `tl_com_customers` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION,
  ADD CONSTRAINT `FK_tl_com_customer_wishlists_tl_com_products` FOREIGN KEY (`product_id`) REFERENCES `tl_com_products` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION;

--
-- Constraints for table `tl_com_deals_products`
--
ALTER TABLE `tl_com_deals_products`
  ADD CONSTRAINT `FK_tl_com_deals_products_tl_com_flash_deal` FOREIGN KEY (`deal_id`) REFERENCES `tl_com_flash_deal` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION,
  ADD CONSTRAINT `FK_tl_com_deals_products_tl_com_products` FOREIGN KEY (`product_id`) REFERENCES `tl_com_products` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION;

--
-- Constraints for table `tl_com_flash_deal_translations`
--
ALTER TABLE `tl_com_flash_deal_translations`
  ADD CONSTRAINT `FK_tl_com_flash_deal_translations_tl_com_flash_deal` FOREIGN KEY (`deal_id`) REFERENCES `tl_com_flash_deal` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION;

--
-- Constraints for table `tl_com_guest_customer`
--
ALTER TABLE `tl_com_guest_customer`
  ADD CONSTRAINT `FK_tl_com_guest_customer_tl_com_orders` FOREIGN KEY (`order_id`) REFERENCES `tl_com_orders` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION;

--
-- Constraints for table `tl_com_ordered_products`
--
ALTER TABLE `tl_com_ordered_products`
  ADD CONSTRAINT `FK_tl_com_ordered_products_tl_com_orders` FOREIGN KEY (`order_id`) REFERENCES `tl_com_orders` (`id`),
  ADD CONSTRAINT `FK_tl_com_ordered_products_tl_com_products` FOREIGN KEY (`product_id`) REFERENCES `tl_com_products` (`id`),
  ADD CONSTRAINT `FK_tl_com_ordered_products_tl_com_shipping_zone_has_rates` FOREIGN KEY (`shipping_rate`) REFERENCES `tl_com_shipping_zone_has_rates` (`id`) ON UPDATE NO ACTION;

--
-- Constraints for table `tl_com_orders`
--
ALTER TABLE `tl_com_orders`
  ADD CONSTRAINT `FK_tl_com_orders_tl_com_customers` FOREIGN KEY (`customer_id`) REFERENCES `tl_com_customers` (`id`) ON UPDATE NO ACTION,
  ADD CONSTRAINT `FK_tl_com_orders_tl_com_payment_methods` FOREIGN KEY (`payment_method`) REFERENCES `tl_com_payment_methods` (`id`),
  ADD CONSTRAINT `FK_tl_com_orders_tl_pick_up_points` FOREIGN KEY (`pickup_point_id`) REFERENCES `tl_pick_up_points` (`id`);

--
-- Constraints for table `tl_com_order_package_trackings`
--
ALTER TABLE `tl_com_order_package_trackings`
  ADD CONSTRAINT `FK_tl_com_order_package_trackings_tl_com_ordered_products` FOREIGN KEY (`order_package_id`) REFERENCES `tl_com_ordered_products` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION,
  ADD CONSTRAINT `FK_tl_com_order_package_trackings_tl_com_orders` FOREIGN KEY (`order_id`) REFERENCES `tl_com_orders` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `tl_com_order_refund_requests`
--
ALTER TABLE `tl_com_order_refund_requests`
  ADD CONSTRAINT `FK_tl_com_order_refund_requests_tl_com_customers` FOREIGN KEY (`customer_id`) REFERENCES `tl_com_customers` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `FK_tl_com_order_refund_requests_tl_com_ordered_products` FOREIGN KEY (`ordered_product_id`) REFERENCES `tl_com_ordered_products` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `FK_tl_com_order_refund_requests_tl_com_orders` FOREIGN KEY (`order_id`) REFERENCES `tl_com_orders` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `FK_tl_com_order_refund_requests_tl_com_product_refund_reasons` FOREIGN KEY (`reason_id`) REFERENCES `tl_com_product_refund_reasons` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `tl_com_payment_method_has_settings`
--
ALTER TABLE `tl_com_payment_method_has_settings`
  ADD CONSTRAINT `FK_tl_com_payment_method_has_settings_tl_com_payment_methods` FOREIGN KEY (`payment_method_id`) REFERENCES `tl_com_payment_methods` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION;

--
-- Constraints for table `tl_com_payment_transactions`
--
ALTER TABLE `tl_com_payment_transactions`
  ADD CONSTRAINT `FK_tl_com_payment_transactions_tl_com_customers` FOREIGN KEY (`customer_id`) REFERENCES `tl_com_customers` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION;

--
-- Constraints for table `tl_com_products`
--
ALTER TABLE `tl_com_products`
  ADD CONSTRAINT `FK_tl_com_products_tl_com_product_conditions` FOREIGN KEY (`conditions`) REFERENCES `tl_com_product_conditions` (`id`),
  ADD CONSTRAINT `FK_tl_com_products_tl_com_product_types` FOREIGN KEY (`product_type`) REFERENCES `tl_com_product_types` (`id`),
  ADD CONSTRAINT `FK_tl_com_products_tl_com_units` FOREIGN KEY (`unit`) REFERENCES `tl_com_units` (`id`);

--
-- Constraints for table `tl_com_product_attribute_translations`
--
ALTER TABLE `tl_com_product_attribute_translations`
  ADD CONSTRAINT `FK_tl_com_product_attribute_translations_tl_com_attributes` FOREIGN KEY (`attribute_id`) REFERENCES `tl_com_attributes` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `tl_com_product_cod_cities`
--
ALTER TABLE `tl_com_product_cod_cities`
  ADD CONSTRAINT `FK_tl_com_product_cod_cities_tl_com_cities` FOREIGN KEY (`city_id`) REFERENCES `tl_com_cities` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION,
  ADD CONSTRAINT `FK_tl_com_product_cod_cities_tl_com_products` FOREIGN KEY (`product_id`) REFERENCES `tl_com_products` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION;

--
-- Constraints for table `tl_com_product_cod_countries`
--
ALTER TABLE `tl_com_product_cod_countries`
  ADD CONSTRAINT `FK_tl_com_product_cod_countries_tl_com_products` FOREIGN KEY (`product_id`) REFERENCES `tl_com_products` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION,
  ADD CONSTRAINT `FK_tl_com_product_cod_countries_tl_countries` FOREIGN KEY (`country_id`) REFERENCES `tl_countries` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION;

--
-- Constraints for table `tl_com_product_cod_states`
--
ALTER TABLE `tl_com_product_cod_states`
  ADD CONSTRAINT `FK_tl_com_product_cod_states_tl_com_products` FOREIGN KEY (`product_id`) REFERENCES `tl_com_products` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION,
  ADD CONSTRAINT `FK_tl_com_product_cod_states_tl_com_state` FOREIGN KEY (`state_id`) REFERENCES `tl_com_state` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION;

--
-- Constraints for table `tl_com_product_color_variant_image`
--
ALTER TABLE `tl_com_product_color_variant_image`
  ADD CONSTRAINT `FK_tl_com_product_color_variant_image_tl_com_colors` FOREIGN KEY (`color_id`) REFERENCES `tl_com_colors` (`id`) ON DELETE NO ACTION,
  ADD CONSTRAINT `FK_tl_com_product_color_variant_image_tl_com_products` FOREIGN KEY (`product_id`) REFERENCES `tl_com_products` (`id`) ON DELETE NO ACTION;

--
-- Constraints for table `tl_com_product_condition_translations`
--
ALTER TABLE `tl_com_product_condition_translations`
  ADD CONSTRAINT `FK_tl_com_product_condition_translations_product_conditions` FOREIGN KEY (`condition_id`) REFERENCES `tl_com_product_conditions` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION;

--
-- Constraints for table `tl_com_product_gallery_images`
--
ALTER TABLE `tl_com_product_gallery_images`
  ADD CONSTRAINT `FK_tl_com_product_gallery_images_tl_com_products` FOREIGN KEY (`product_id`) REFERENCES `tl_com_products` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION,
  ADD CONSTRAINT `FK_tl_com_product_gallery_images_tl_uploaded_files` FOREIGN KEY (`image_id`) REFERENCES `tl_uploaded_files` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION;

--
-- Constraints for table `tl_com_product_has_categories`
--
ALTER TABLE `tl_com_product_has_categories`
  ADD CONSTRAINT `FK_tl_com_product_has_categories_tl_com_categories` FOREIGN KEY (`category_id`) REFERENCES `tl_com_categories` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION,
  ADD CONSTRAINT `FK_tl_com_product_has_categories_tl_com_products` FOREIGN KEY (`product_id`) REFERENCES `tl_com_products` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION;

--
-- Constraints for table `tl_com_product_has_choices`
--
ALTER TABLE `tl_com_product_has_choices`
  ADD CONSTRAINT `FK_tl_com_product_has_choices_tl_com_products` FOREIGN KEY (`product_id`) REFERENCES `tl_com_products` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION,
  ADD CONSTRAINT `tl_com_product_has_choices_ibfk_1` FOREIGN KEY (`choice_id`) REFERENCES `tl_com_attributes` (`id`) ON UPDATE CASCADE;

--
-- Constraints for table `tl_com_product_has_choice_options`
--
ALTER TABLE `tl_com_product_has_choice_options`
  ADD CONSTRAINT `FK_tl_com_product_has_choice_options_tl_com_products` FOREIGN KEY (`product_id`) REFERENCES `tl_com_products` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION,
  ADD CONSTRAINT `tl_com_product_has_choice_options_ibfk_1` FOREIGN KEY (`choice_id`) REFERENCES `tl_com_attributes` (`id`) ON UPDATE NO ACTION,
  ADD CONSTRAINT `tl_com_product_has_choice_options_ibfk_2` FOREIGN KEY (`option_id`) REFERENCES `tl_com_attribute_values` (`id`) ON UPDATE NO ACTION;

--
-- Constraints for table `tl_com_product_has_colors`
--
ALTER TABLE `tl_com_product_has_colors`
  ADD CONSTRAINT `FK_tl_com_product_has_colors_tl_com_colors` FOREIGN KEY (`color_id`) REFERENCES `tl_com_colors` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `FK_tl_com_product_has_colors_tl_com_products` FOREIGN KEY (`product_id`) REFERENCES `tl_com_products` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION;

--
-- Constraints for table `tl_com_product_has_tags`
--
ALTER TABLE `tl_com_product_has_tags`
  ADD CONSTRAINT `FK_tl_com_product_has_tags_tl_com_product_tags` FOREIGN KEY (`tag_id`) REFERENCES `tl_com_product_tags` (`id`) ON UPDATE NO ACTION,
  ADD CONSTRAINT `FK_tl_com_product_has_tags_tl_com_products` FOREIGN KEY (`product_id`) REFERENCES `tl_com_products` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION;

--
-- Constraints for table `tl_com_product_reviews`
--
ALTER TABLE `tl_com_product_reviews`
  ADD CONSTRAINT `FK_tl_com_product_reviews_tl_com_customers` FOREIGN KEY (`customer_id`) REFERENCES `tl_com_customers` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION,
  ADD CONSTRAINT `FK_tl_com_product_reviews_tl_com_orders` FOREIGN KEY (`order_id`) REFERENCES `tl_com_orders` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `FK_tl_com_product_reviews_tl_com_products` FOREIGN KEY (`product_id`) REFERENCES `tl_com_products` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION;

--
-- Constraints for table `tl_com_product_seo`
--
ALTER TABLE `tl_com_product_seo`
  ADD CONSTRAINT `FK_tl_com_product_seo_tl_com_products` FOREIGN KEY (`product_id`) REFERENCES `tl_com_products` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION,
  ADD CONSTRAINT `FK_tl_com_product_seo_tl_uploaded_files` FOREIGN KEY (`meta_image`) REFERENCES `tl_uploaded_files` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `tl_com_product_shipping_info`
--
ALTER TABLE `tl_com_product_shipping_info`
  ADD CONSTRAINT `FK_tl_com_product_shipping_info_tl_com_products` FOREIGN KEY (`product_id`) REFERENCES `tl_com_products` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION;

--
-- Constraints for table `tl_com_product_translation`
--
ALTER TABLE `tl_com_product_translation`
  ADD CONSTRAINT `FK_tl_com_product_translation_tl_com_products` FOREIGN KEY (`product_id`) REFERENCES `tl_com_products` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION;

--
-- Constraints for table `tl_com_product_variant_combination`
--
ALTER TABLE `tl_com_product_variant_combination`
  ADD CONSTRAINT `tl_com_product_variant_combination_ibfk_1` FOREIGN KEY (`attribute_id`) REFERENCES `tl_com_attributes` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `tl_com_product_variant_combination_ibfk_3` FOREIGN KEY (`color_id`) REFERENCES `tl_com_colors` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `tl_com_product_variant_combination_ibfk_4` FOREIGN KEY (`product_variation_id`) REFERENCES `tl_com_variant_product_price` (`id`);

--
-- Constraints for table `tl_com_refund_reason_translations`
--
ALTER TABLE `tl_com_refund_reason_translations`
  ADD CONSTRAINT `FK_refund_reason_translations_tl_com_product_refund_reasons` FOREIGN KEY (`reason_id`) REFERENCES `tl_com_product_refund_reasons` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION;

--
-- Constraints for table `tl_com_refund_request_tracking`
--
ALTER TABLE `tl_com_refund_request_tracking`
  ADD CONSTRAINT `FK_tl_com_refund_request_tracking_tl_com_order_refund_requests` FOREIGN KEY (`request_id`) REFERENCES `tl_com_order_refund_requests` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION,
  ADD CONSTRAINT `FK_tl_com_refund_request_tracking_tl_com_orders` FOREIGN KEY (`order_id`) REFERENCES `tl_com_orders` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `tl_com_related_products`
--
ALTER TABLE `tl_com_related_products`
  ADD CONSTRAINT `FK__tl_com_products` FOREIGN KEY (`parent_product_id`) REFERENCES `tl_com_products` (`id`),
  ADD CONSTRAINT `FK__tl_com_products_2` FOREIGN KEY (`releted_product_id`) REFERENCES `tl_com_products` (`id`);

--
-- Constraints for table `tl_com_seller_earning`
--
ALTER TABLE `tl_com_seller_earning`
  ADD CONSTRAINT `tl_com_seller_earning_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `tl_com_orders` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `tl_com_seller_earning_ibfk_2` FOREIGN KEY (`order_package_id`) REFERENCES `tl_com_ordered_products` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `tl_com_seller_earning_ibfk_3` FOREIGN KEY (`product_id`) REFERENCES `tl_com_products` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `tl_com_seller_earning_ibfk_4` FOREIGN KEY (`seller_id`) REFERENCES `tl_users` (`id`) ON UPDATE CASCADE;

--
-- Constraints for table `tl_com_seller_shop`
--
ALTER TABLE `tl_com_seller_shop`
  ADD CONSTRAINT `tl_com_seller_shop_ibfk_1` FOREIGN KEY (`seller_id`) REFERENCES `tl_users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `tl_com_shipping_profiles_has_products`
--
ALTER TABLE `tl_com_shipping_profiles_has_products`
  ADD CONSTRAINT `FK_tl_com_shipping_profiles_has_products_tl_com_products` FOREIGN KEY (`product_id`) REFERENCES `tl_com_products` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `FK_tl_com_shipping_profiles_has_products_tl_com_shipping_zones` FOREIGN KEY (`profile_id`) REFERENCES `tl_com_shipping_profiles` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `tl_com_shipping_zones`
--
ALTER TABLE `tl_com_shipping_zones`
  ADD CONSTRAINT `FK_tl_com_shipping_zones_tl_com_shipping_profiles` FOREIGN KEY (`profile_id`) REFERENCES `tl_com_shipping_profiles` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION;

--
-- Constraints for table `tl_com_shipping_zone_has_cities`
--
ALTER TABLE `tl_com_shipping_zone_has_cities`
  ADD CONSTRAINT `FK_tl_com_shipping_zone_has_cities_tl_com_cities` FOREIGN KEY (`city_id`) REFERENCES `tl_com_cities` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION,
  ADD CONSTRAINT `FK_tl_com_shipping_zone_has_cities_tl_com_shipping_zones` FOREIGN KEY (`zone_id`) REFERENCES `tl_com_shipping_zones` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION;

--
-- Constraints for table `tl_com_shipping_zone_has_countries`
--
ALTER TABLE `tl_com_shipping_zone_has_countries`
  ADD CONSTRAINT `FK_tl_com_shipping_zone_has_countries_tl_com_shipping_zones` FOREIGN KEY (`zone_id`) REFERENCES `tl_com_shipping_zones` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION,
  ADD CONSTRAINT `FK_tl_com_shipping_zone_has_countries_tl_countries` FOREIGN KEY (`country_id`) REFERENCES `tl_countries` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `tl_com_shipping_zone_has_rates`
--
ALTER TABLE `tl_com_shipping_zone_has_rates`
  ADD CONSTRAINT `FK_tl_com_shipping_zone_has_rates_tl_com_shipping_courier` FOREIGN KEY (`carrier_id`) REFERENCES `tl_com_shipping_courier` (`id`) ON UPDATE NO ACTION,
  ADD CONSTRAINT `FK_tl_com_shipping_zone_has_rates_tl_com_shipping_times` FOREIGN KEY (`delivery_time`) REFERENCES `tl_com_shipping_times` (`id`) ON UPDATE NO ACTION,
  ADD CONSTRAINT `FK_tl_com_shipping_zone_has_rates_tl_com_shipping_zones` FOREIGN KEY (`zone_id`) REFERENCES `tl_com_shipping_zones` (`id`) ON UPDATE NO ACTION;

--
-- Constraints for table `tl_com_shipping_zone_has_states`
--
ALTER TABLE `tl_com_shipping_zone_has_states`
  ADD CONSTRAINT `FK_tl_com_shipping_zone_has_states_tl_com_shipping_zones` FOREIGN KEY (`zone_id`) REFERENCES `tl_com_shipping_zones` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION,
  ADD CONSTRAINT `FK_tl_com_shipping_zone_has_states_tl_com_state` FOREIGN KEY (`state_id`) REFERENCES `tl_com_state` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION;

--
-- Constraints for table `tl_com_shipping_zone_has_taxes`
--
ALTER TABLE `tl_com_shipping_zone_has_taxes`
  ADD CONSTRAINT `FK_tl_com_shipping_zone_has_taxes_tl_com_product_collections` FOREIGN KEY (`product_collection_id`) REFERENCES `tl_com_product_collections` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION,
  ADD CONSTRAINT `FK_tl_com_shipping_zone_has_taxes_tl_com_shipping_zones` FOREIGN KEY (`zone_id`) REFERENCES `tl_com_shipping_zones` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION,
  ADD CONSTRAINT `FK_tl_com_shipping_zone_has_taxes_tl_com_state` FOREIGN KEY (`state_id`) REFERENCES `tl_com_state` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION;

--
-- Constraints for table `tl_com_single_product_price`
--
ALTER TABLE `tl_com_single_product_price`
  ADD CONSTRAINT `FK_tl_com_single_product_price_tl_com_products` FOREIGN KEY (`product_id`) REFERENCES `tl_com_products` (`id`);

--
-- Constraints for table `tl_com_state`
--
ALTER TABLE `tl_com_state`
  ADD CONSTRAINT `FK_tl_com_state_tl_countries` FOREIGN KEY (`country_id`) REFERENCES `tl_countries` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `tl_com_tax_rates`
--
ALTER TABLE `tl_com_tax_rates`
  ADD CONSTRAINT `tl_com_tax_rates_ibfk_1` FOREIGN KEY (`profile_id`) REFERENCES `tl_com_tax_profiles` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION,
  ADD CONSTRAINT `tl_com_tax_rates_ibfk_2` FOREIGN KEY (`country_id`) REFERENCES `tl_countries` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION,
  ADD CONSTRAINT `tl_com_tax_rates_ibfk_3` FOREIGN KEY (`city_id`) REFERENCES `tl_com_cities` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION,
  ADD CONSTRAINT `tl_com_tax_rates_ibfk_4` FOREIGN KEY (`state_id`) REFERENCES `tl_com_state` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION;

--
-- Constraints for table `tl_com_unit_translations`
--
ALTER TABLE `tl_com_unit_translations`
  ADD CONSTRAINT `FK_tl_com_unit_translations_tl_com_units` FOREIGN KEY (`unit_id`) REFERENCES `tl_com_units` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION;

--
-- Constraints for table `tl_com_variant_product_price`
--
ALTER TABLE `tl_com_variant_product_price`
  ADD CONSTRAINT `FK_tl_com_variant_product_price_tl_com_products` FOREIGN KEY (`product_id`) REFERENCES `tl_com_products` (`id`);

--
-- Constraints for table `tl_com_wallet_bank_information`
--
ALTER TABLE `tl_com_wallet_bank_information`
  ADD CONSTRAINT `FK_tl_com_wallet_bank_information_tl_com_payment_methods` FOREIGN KEY (`payment_method_id`) REFERENCES `tl_com_payment_methods` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION;

--
-- Constraints for table `tl_com_wallet_recharges`
--
ALTER TABLE `tl_com_wallet_recharges`
  ADD CONSTRAINT `FK_tl_com_wallet_recharges_tl_com_customers` FOREIGN KEY (`customer_id`) REFERENCES `tl_com_customers` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION,
  ADD CONSTRAINT `FK_tl_com_wallet_recharges_tl_com_payment_methods` FOREIGN KEY (`payment_method_id`) REFERENCES `tl_com_payment_methods` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `tl_email_template_properties`
--
ALTER TABLE `tl_email_template_properties`
  ADD CONSTRAINT `FK_tl_email_templates_tl_email_types` FOREIGN KEY (`email_type`) REFERENCES `tl_email_templates` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `tl_general_settings_has_values`
--
ALTER TABLE `tl_general_settings_has_values`
  ADD CONSTRAINT `FK_tl_general_settings_has_values_tl_general_settings` FOREIGN KEY (`settings_id`) REFERENCES `tl_general_settings` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `tl_media_driver_settings`
--
ALTER TABLE `tl_media_driver_settings`
  ADD CONSTRAINT `FK__tl_media_drivers` FOREIGN KEY (`media_driver_id`) REFERENCES `tl_media_drivers` (`id`);

--
-- Constraints for table `tl_menus`
--
ALTER TABLE `tl_menus`
  ADD CONSTRAINT `FK_tl_menus_tl_menu_groups` FOREIGN KEY (`menu_group_id`) REFERENCES `tl_menu_groups` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `tl_menu_groups_translations`
--
ALTER TABLE `tl_menu_groups_translations`
  ADD CONSTRAINT `FK_tl_menu_groups_translations_tl_menu_groups` FOREIGN KEY (`menu_group_id`) REFERENCES `tl_menu_groups` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `tl_menu_group_has_positon`
--
ALTER TABLE `tl_menu_group_has_positon`
  ADD CONSTRAINT `FK_tl_menu_group_has_positon_tl_menu_groups` FOREIGN KEY (`menu_group_id`) REFERENCES `tl_menu_groups` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `FK_tl_menu_group_has_positon_tl_menu_positions` FOREIGN KEY (`menu_position_id`) REFERENCES `tl_menu_positions` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `tl_menu_positions`
--
ALTER TABLE `tl_menu_positions`
  ADD CONSTRAINT `FK_tl_menu_positions_tl_themes` FOREIGN KEY (`theme_id`) REFERENCES `tl_themes` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `tl_menu_translations`
--
ALTER TABLE `tl_menu_translations`
  ADD CONSTRAINT `FK_tl_menu_translations_tl_menus` FOREIGN KEY (`menu_id`) REFERENCES `tl_menus` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `tl_pages`
--
ALTER TABLE `tl_pages`
  ADD CONSTRAINT `FK_tl_pages_tl_users` FOREIGN KEY (`user_id`) REFERENCES `tl_users` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `tl_page_translations`
--
ALTER TABLE `tl_page_translations`
  ADD CONSTRAINT `FK_tl_page_translations_tl_pages` FOREIGN KEY (`page_id`) REFERENCES `tl_pages` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `tl_pick_up_points_translations`
--
ALTER TABLE `tl_pick_up_points_translations`
  ADD CONSTRAINT `FK__tl_pick_up_points` FOREIGN KEY (`pic_up_point_id`) REFERENCES `tl_pick_up_points` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `tl_sidebar_has_widgets`
--
ALTER TABLE `tl_sidebar_has_widgets`
  ADD CONSTRAINT `FK_tl_sidebar_has_widgets_tl_theme_sidebars` FOREIGN KEY (`sidebar_id`) REFERENCES `tl_theme_sidebars` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `FK_tl_sidebar_has_widgets_tl_widgets` FOREIGN KEY (`widget_id`) REFERENCES `tl_widgets` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `tl_sidebar_widget_has_translate_values`
--
ALTER TABLE `tl_sidebar_widget_has_translate_values`
  ADD CONSTRAINT `FK_tl_sidebar_widget_has_translate_values_tl_sidebar_has_widgets` FOREIGN KEY (`sidebar_widget_has_values_id`) REFERENCES `tl_sidebar_widget_has_values` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `tl_sidebar_widget_has_values`
--
ALTER TABLE `tl_sidebar_widget_has_values`
  ADD CONSTRAINT `FK_tl_sidebar_widget_has_values_tl_sidebar_has_widgets` FOREIGN KEY (`sidebar_has_widget_id`) REFERENCES `tl_sidebar_has_widgets` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `tl_smtp_configs`
--
ALTER TABLE `tl_smtp_configs`
  ADD CONSTRAINT `FK_tl_smtp_configs_tl_smtps` FOREIGN KEY (`smtp_id`) REFERENCES `tl_smtps` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `tl_theme_option_settings`
--
ALTER TABLE `tl_theme_option_settings`
  ADD CONSTRAINT `FK_tl_theme_option_settings_tl_themes` FOREIGN KEY (`theme_id`) REFERENCES `tl_themes` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `tl_theme_sidebars`
--
ALTER TABLE `tl_theme_sidebars`
  ADD CONSTRAINT `FK_tl_theme_sidebars_tl_themes` FOREIGN KEY (`theme_id`) REFERENCES `tl_themes` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `tl_theme_tlcommerce_home_page_sections_properties`
--
ALTER TABLE `tl_theme_tlcommerce_home_page_sections_properties`
  ADD CONSTRAINT `FK_tl_home_page_sections_properties_tl_home_page_sections` FOREIGN KEY (`section_id`) REFERENCES `tl_theme_tlcommerce_home_page_sections` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION;

--
-- Constraints for table `tl_uploaded_files`
--
ALTER TABLE `tl_uploaded_files`
  ADD CONSTRAINT `FK_tl_uploaded_files_tl_media_type` FOREIGN KEY (`media_type`) REFERENCES `tl_media_type` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `tl_water_mark_image_applicable_folders`
--
ALTER TABLE `tl_water_mark_image_applicable_folders`
  ADD CONSTRAINT `FK_tl_water_mark_image_applicable_folders_tl_media_image_type` FOREIGN KEY (`image_type`) REFERENCES `tl_media_type` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `tl_widgets`
--
ALTER TABLE `tl_widgets`
  ADD CONSTRAINT `FK_tl_widgets_tl_themes` FOREIGN KEY (`theme_id`) REFERENCES `tl_themes` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
