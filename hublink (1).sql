-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Oct 14, 2025 at 03:50 PM
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
-- Database: `hublink`
--

-- --------------------------------------------------------

--
-- Table structure for table `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` mediumtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cache_locks`
--

CREATE TABLE `cache_locks` (
  `key` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `owner` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `components`
--

CREATE TABLE `components` (
  `id` bigint UNSIGNED NOT NULL,
  `domain_id` bigint UNSIGNED NOT NULL,
  `type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `properties` json NOT NULL,
  `digital_product_path` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `order` int NOT NULL,
  `is_published` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `components`
--

INSERT INTO `components` (`id`, `domain_id`, `type`, `properties`, `digital_product_path`, `order`, `is_published`, `created_at`, `updated_at`) VALUES
(15, 4, 'button', '{\"url\": \"#\", \"text\": \"Button Text\"}', NULL, 1, 0, '2025-10-05 21:40:16', '2025-10-05 21:40:16'),
(16, 4, 'image', '{\"alt\": \"Image\", \"src\": \"https://placehold.co/400x200\"}', NULL, 0, 0, '2025-10-05 21:40:20', '2025-10-05 21:40:21'),
(42, 7, 'template', '{\"image\": \"https://placehold.co/400x300\", \"price\": 0, \"title\": \"Template Title\", \"buttonText\": \"Buy Now\", \"description\": \"Template description goes here.\", \"digitalProduct\": {\"path\": null, \"fileSize\": 0, \"fileType\": null, \"originalName\": null}}', NULL, 0, 1, '2025-10-14 03:11:43', '2025-10-14 03:47:30'),
(43, 7, 'text', '{\"bold\": false, \"size\": \"text-base\", \"italic\": false, \"content\": \"<p>Edit this text content</p>\", \"alignment\": \"left\", \"underline\": false}', NULL, 1, 1, '2025-10-14 03:44:03', '2025-10-14 03:47:30'),
(44, 7, 'link', '{\"url\": \"#\", \"text\": \"Link text\", \"target\": \"_blank\", \"fontSize\": \"text-base\", \"textColor\": \"#00c499\", \"fontWeight\": \"font-normal\", \"textDecoration\": \"underline\"}', NULL, 2, 1, '2025-10-14 03:44:15', '2025-10-14 03:47:30'),
(45, 7, 'divider', '{\"color\": \"#e5e7eb\", \"style\": \"solid\", \"thickness\": \"1px\"}', NULL, 3, 1, '2025-10-14 03:45:13', '2025-10-14 03:47:30'),
(46, 6, 'button', '{\"url\": \"#\", \"text\": \"Button Text\", \"padding\": \"px-4 py-2\", \"fontSize\": \"text-base\", \"textColor\": \"#ffffff\", \"fontWeight\": \"font-normal\", \"borderColor\": \"#00c499\", \"borderWidth\": \"0\", \"borderRadius\": \"0.5rem\", \"backgroundColor\": \"#00c499\"}', NULL, 0, 1, '2025-10-14 03:54:27', '2025-10-14 08:46:03'),
(47, 6, 'text', '{\"bold\": false, \"size\": \"text-base\", \"italic\": false, \"content\": \"<p>Edit this text content</p>\", \"alignment\": \"left\", \"underline\": false}', NULL, 2, 1, '2025-10-14 03:54:29', '2025-10-14 08:46:03'),
(48, 6, 'template', '{\"image\": \"/storage/builder-images/hrFkPIXNhcNfefwwIq07ckyrXNXxW0u9qNSAv8ow.jpg\", \"price\": 0, \"title\": \"Template Title\", \"buttonText\": \"Buy Now\", \"description\": \"Template description goes here.\", \"digitalProduct\": {\"path\": \"digital-products/Kt16x43iGeyTySDLFi4woWjKmr24Ulp4dA9jkWOG.pdf\", \"fileSize\": 191205, \"fileType\": \"application/pdf\", \"originalName\": \"Robitik Gedung B.pdf\"}}', 'digital-products/Kt16x43iGeyTySDLFi4woWjKmr24Ulp4dA9jkWOG.pdf', 1, 1, '2025-10-14 03:55:48', '2025-10-14 08:46:03'),
(49, 6, 'template', '{\"image\": \"/storage/builder-images/VNsqFrGkEdOLlLgqp2DKhqIM1DtUOLFb3c5CB3ig.jpg\", \"price\": 0, \"title\": \"Template Title\", \"buttonText\": \"Buy Now\", \"description\": \"Template description goes here.\", \"digitalProduct\": {\"path\": \"digital-products/K8D5Zfnijoye3PqSJUeoXHyRDe1foGGSRa0GyLUR.pdf\", \"fileSize\": 18148, \"fileType\": \"application/pdf\", \"originalName\": \"Brief Aplikasi Kasir Pdf.pdf\"}}', 'digital-products/K8D5Zfnijoye3PqSJUeoXHyRDe1foGGSRa0GyLUR.pdf', 3, 1, '2025-10-14 08:43:32', '2025-10-14 08:46:03');

-- --------------------------------------------------------

--
-- Table structure for table `domains`
--

CREATE TABLE `domains` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `slug` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `bio` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `avatar_path` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `settings` json DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `domains`
--

INSERT INTO `domains` (`id`, `user_id`, `slug`, `title`, `bio`, `avatar_path`, `settings`, `created_at`, `updated_at`) VALUES
(4, 7, 'blitzzz', 'bla', 'sfbfs', NULL, '{\"theme\": \"default\", \"accent\": \"#030303\"}', '2025-10-02 00:10:25', '2025-10-02 00:47:51'),
(6, 8, 'aprik', NULL, NULL, NULL, NULL, '2025-10-05 11:25:54', '2025-10-05 11:25:54'),
(7, 9, 'apri', NULL, NULL, NULL, NULL, '2025-10-14 03:11:19', '2025-10-14 03:11:19');

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint UNSIGNED NOT NULL,
  `uuid` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `jobs`
--

CREATE TABLE `jobs` (
  `id` bigint UNSIGNED NOT NULL,
  `queue` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
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
  `id` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `total_jobs` int NOT NULL,
  `pending_jobs` int NOT NULL,
  `failed_jobs` int NOT NULL,
  `failed_job_ids` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `options` mediumtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
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
(1, '0001_01_01_000000_create_users_table', 1),
(2, '0001_01_01_000001_create_cache_table', 1),
(3, '0001_01_01_000002_create_jobs_table', 1),
(4, '2025_09_30_063347_add_phone_to_users_table', 2),
(5, '2025_10_01_000000_create_domains_table', 3),
(6, '2025_10_04_053218_create_components_table', 4),
(7, '2025_10_06_035814_add_profile_photo_to_users_table', 5),
(8, '2025_10_13_064542_create_orders_table', 5),
(9, '2025_10_13_072510_add_digital_product_path_to_components_table', 5),
(10, '2025_10_13_074500_fix_midtrans_response_json', 5);

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` bigint UNSIGNED NOT NULL,
  `order_id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `domain_id` bigint UNSIGNED NOT NULL,
  `component_id` bigint UNSIGNED NOT NULL,
  `customer_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `customer_email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `customer_phone` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `customer_address` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `product_title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `product_description` text COLLATE utf8mb4_unicode_ci,
  `amount` decimal(15,2) NOT NULL,
  `payment_type` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `transaction_id` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `transaction_status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `paid_at` timestamp NULL DEFAULT NULL,
  `snap_token` text COLLATE utf8mb4_unicode_ci,
  `midtrans_response` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `order_id`, `domain_id`, `component_id`, `customer_name`, `customer_email`, `customer_phone`, `customer_address`, `product_title`, `product_description`, `amount`, `payment_type`, `transaction_id`, `transaction_status`, `paid_at`, `snap_token`, `midtrans_response`, `created_at`, `updated_at`) VALUES
(3, 'ORDER-1760455207-4722', 6, 48, 'Yoru', 'm.apri5428@gmail.com', '083172103014', 'fangatarang', 'Template Title', 'Template description goes here.', '777000.00', 'qris', 'd6fecf36-25fe-46ac-98e7-daf83ac54f1e', 'settlement', '2025-10-14 08:27:58', '9f07093f-06c4-434e-a77b-7e3bf3a41c0f', '{\"status_code\":\"200\",\"transaction_id\":\"d6fecf36-25fe-46ac-98e7-daf83ac54f1e\",\"gross_amount\":\"777000.00\",\"currency\":\"IDR\",\"order_id\":\"ORDER-1760455207-4722\",\"payment_type\":\"qris\",\"signature_key\":\"c83f66693088d728bde9b00812a9e281e0dd70cdc6672a4104e4a57342d7097ebd5476538c2d915a6f1cc124a98cd81800e887c0492b0a3f34f8ac9e5ebd380a\",\"transaction_status\":\"settlement\",\"fraud_status\":\"accept\",\"status_message\":\"Success, transaction is found\",\"merchant_id\":\"G480515540\",\"transaction_type\":\"on-us\",\"issuer\":\"gopay\",\"acquirer\":\"gopay\",\"transaction_time\":\"2025-10-14 22:20:20\",\"settlement_time\":\"2025-10-14 22:27:47\",\"expiry_time\":\"2025-10-14 22:35:20\",\"download_token\":\"sjdHrIKedGsNrfautu5dHtOqiBtpMLlFIKDwb1Zsm0o4ZVoZtaRdNF88apUxXSLX\",\"download_token_expires_at\":\"2025-10-15 15:28:02\"}', '2025-10-14 08:20:07', '2025-10-14 08:28:02'),
(7, 'ORDER-1760456541-3228', 6, 48, 'Yoru', 'm.apri5428@gmail.com', '083172103014', 'dwedwed', 'Template Title', 'Template description goes here.', '0.00', NULL, NULL, 'settlement', '2025-10-14 08:42:21', 'FREE_PRODUCT_ORDER-1760456541-3228', NULL, '2025-10-14 08:42:21', '2025-10-14 08:42:21'),
(8, 'ORDER-1760456545-7827', 6, 48, 'Yoru', 'm.apri5428@gmail.com', '083172103014', 'dwedwed', 'Template Title', 'Template description goes here.', '0.00', NULL, NULL, 'settlement', '2025-10-14 08:42:25', 'FREE_PRODUCT_ORDER-1760456545-7827', NULL, '2025-10-14 08:42:25', '2025-10-14 08:42:25'),
(9, 'ORDER-1760456580-3626', 6, 48, 'apri', 'm.apri5428@gmail.com', '083172103014', 'ddadadw', 'Template Title', 'Template description goes here.', '0.00', NULL, NULL, 'settlement', '2025-10-14 08:43:00', 'FREE_PRODUCT_ORDER-1760456580-3626', NULL, '2025-10-14 08:43:00', '2025-10-14 08:43:00'),
(10, 'ORDER-1760456588-1831', 6, 48, 'aprir', 'm.apri5428@gmail.comv', '083172103014', 'ddadadwdadae', 'Template Title', 'Template description goes here.', '0.00', NULL, NULL, 'settlement', '2025-10-14 08:43:08', 'FREE_PRODUCT_ORDER-1760456588-1831', NULL, '2025-10-14 08:43:08', '2025-10-14 08:43:08'),
(11, 'ORDER-1760456709-7492', 6, 48, 'Yoru', 'nugrahaabisantana@gmail.com', '083172103014', 'xacddafaasdasdas dascascasc', 'Template Title', 'Template description goes here.', '0.00', NULL, NULL, 'settlement', '2025-10-14 08:45:09', 'FREE_PRODUCT_ORDER-1760456709-7492', NULL, '2025-10-14 08:45:09', '2025-10-14 08:45:09'),
(12, 'ORDER-1760456712-7199', 6, 48, 'Yoru', 'nugrahaabisantana@gmail.com', '083172103014', 'xacddafaasdasdas dascascasc', 'Template Title', 'Template description goes here.', '0.00', NULL, NULL, 'settlement', '2025-10-14 08:45:12', 'FREE_PRODUCT_ORDER-1760456712-7199', NULL, '2025-10-14 08:45:12', '2025-10-14 08:45:12');

-- --------------------------------------------------------

--
-- Table structure for table `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
('C18cepsj9ld3iHI2YsWxFxuwaLfdg0TeVtprCLjh', 8, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', 'YTo1OntzOjY6Il90b2tlbiI7czo0MDoieFljUk1RMU1UTUxSY0ZpOFZJczUyVEZodGlJZHBsU1AwRUxmSVdPbSI7czozOiJ1cmwiO2E6MTp7czo4OiJpbnRlbmRlZCI7czoyMzoiaHR0cDovL2h1YmxpbmsudGVzdC9jbXMiO31zOjk6Il9wcmV2aW91cyI7YToxOntzOjM6InVybCI7czozMzoiaHR0cDovL2h1YmxpbmsudGVzdC82L2NoZWNrb3V0LzQ4Ijt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo1MDoibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiO2k6ODt9', 1760454008),
('CIL0FhT5yfzN34lTOz27zFZEqbI4eRNoIhLTbLBO', NULL, '127.0.0.1', 'Mozilla/5.0 (Linux; Android 6.0; Nexus 5 Build/MRA58N) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Mobile Safari/537.36', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiTndMY3dEaTBMWW5wSzJtVWV5THZyNE9EdzRCQzczckNMdzRrREx6dyI7czozOiJ1cmwiO2E6MTp7czo4OiJpbnRlbmRlZCI7czozNToiaHR0cDovLzEyNy4wLjAuMTo4MDAwL2Ntcy9idWlsZGVyLzYiO31zOjk6Il9wcmV2aW91cyI7YToxOntzOjM6InVybCI7czoyNzoiaHR0cDovLzEyNy4wLjAuMTo4MDAwL2xvZ2luIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1760455061),
('Eto1k7uKJjjc8znTMDsN3bR8igccTZHHouFsuo75', 8, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', 'YTo1OntzOjY6Il90b2tlbiI7czo0MDoiM3JKekpJMVdnSDVvemR6aXFXRlViOTBSYlFnS3kwWHdYMkNIejB4dCI7czozOiJ1cmwiO2E6MTp7czo4OiJpbnRlbmRlZCI7czoyMzoiaHR0cDovL2h1YmxpbmsudGVzdC9jbXMiO31zOjk6Il9wcmV2aW91cyI7YToxOntzOjM6InVybCI7czoyNToiaHR0cDovL2h1YmxpbmsudGVzdC9hcHJpayI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fXM6NTA6ImxvZ2luX3dlYl81OWJhMzZhZGRjMmIyZjk0MDE1ODBmMDE0YzdmNThlYTRlMzA5ODlkIjtpOjg7fQ==', 1760456766),
('mtRpTlZmLlUPnObEsJk9BWAkxREX9jdy9VXYHJv8', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoid3E1dFUxdW93WWpNQ240SHMwYlRmaWl1YmxmZXVLeVA2WEljZEZONiI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MjU6Imh0dHA6Ly9odWJsaW5rLnRlc3QvYXByaWsiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19', 1760455061);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `profile_photo` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `phone`, `profile_photo`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`) VALUES
(7, 'nugzzzzzz7', 'nugrahaabisantana@gmail.com', '+62895320904623', NULL, NULL, '$2y$12$KeGo624.cYrF/vivKchYWuXLec.LlFkyDjPmkXuO8TvHpXclCwlaa', NULL, '2025-10-02 00:10:25', '2025-10-02 00:10:25'),
(8, 'aprik', 'apri@gmail.com', '+620891929394', NULL, NULL, '$2y$12$EOsfEt5oEvT0HQW33RgDYuMF.oEd0eJjUGqyJh5JMy9oBCsp2sbJ2', NULL, '2025-10-05 11:25:54', '2025-10-05 11:25:54'),
(9, 'apri', 'aprri@gmail.com', '+6298776455', NULL, NULL, '$2y$12$dbrPI2zpB6URFTk9j8TNnOsrSp2.TwAjF9W7a7DrTiv73XiW2pLw.', NULL, '2025-10-14 03:11:18', '2025-10-14 03:11:18');

--
-- Indexes for dumped tables
--

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
-- Indexes for table `components`
--
ALTER TABLE `components`
  ADD PRIMARY KEY (`id`),
  ADD KEY `components_domain_id_foreign` (`domain_id`);

--
-- Indexes for table `domains`
--
ALTER TABLE `domains`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `domains_slug_unique` (`slug`),
  ADD KEY `domains_user_id_foreign` (`user_id`);

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
  ADD UNIQUE KEY `orders_order_id_unique` (`order_id`),
  ADD KEY `orders_domain_id_foreign` (`domain_id`),
  ADD KEY `orders_component_id_foreign` (`component_id`);

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
  ADD UNIQUE KEY `users_email_unique` (`email`),
  ADD UNIQUE KEY `users_phone_unique` (`phone`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `components`
--
ALTER TABLE `components`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=50;

--
-- AUTO_INCREMENT for table `domains`
--
ALTER TABLE `domains`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

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
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `components`
--
ALTER TABLE `components`
  ADD CONSTRAINT `components_domain_id_foreign` FOREIGN KEY (`domain_id`) REFERENCES `domains` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `domains`
--
ALTER TABLE `domains`
  ADD CONSTRAINT `domains_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_component_id_foreign` FOREIGN KEY (`component_id`) REFERENCES `components` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `orders_domain_id_foreign` FOREIGN KEY (`domain_id`) REFERENCES `domains` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
