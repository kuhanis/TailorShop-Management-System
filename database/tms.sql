/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

CREATE TABLE `body_measurements` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `customer_id` bigint(20) unsigned DEFAULT NULL,
  `body_name` varchar(255) DEFAULT NULL,
  `shoulder` decimal(10,2) DEFAULT NULL,
  `chest` decimal(10,2) DEFAULT NULL,
  `waist` decimal(10,2) DEFAULT NULL,
  `hips` decimal(10,2) DEFAULT NULL,
  `dress_length` decimal(10,2) DEFAULT NULL,
  `wrist` decimal(10,2) DEFAULT NULL,
  `skirt_length` decimal(10,2) DEFAULT NULL,
  `armpit` decimal(10,2) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `customer_id` (`customer_id`),
  CONSTRAINT `body_measurements_ibfk_1` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=55 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `customers` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `fullname` varchar(255) NOT NULL,
  `address` varchar(255) NOT NULL,
  `phone` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `customers_phone_unique` (`phone`)
) ENGINE=InnoDB AUTO_INCREMENT=48 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `measurement_parts` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `migrations` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=40 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `order_histories` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `customer_id` bigint(20) unsigned NOT NULL,
  `customer_name` varchar(255) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `received_on` date NOT NULL,
  `amount_charged` varchar(255) NOT NULL,
  `processed_by` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `staff_id` bigint(20) unsigned DEFAULT NULL,
  `fullname` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `order_histories_customer_id_foreign` (`customer_id`),
  KEY `staff_id` (`staff_id`),
  CONSTRAINT `order_histories_customer_id_foreign` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`id`),
  CONSTRAINT `order_histories_ibfk_1` FOREIGN KEY (`staff_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=91 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `orders` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `customer_id` bigint(20) unsigned DEFAULT NULL,
  `description` text DEFAULT NULL,
  `received_on` date NOT NULL,
  `amount_charged` varchar(255) NOT NULL,
  `access_token` varchar(255) DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `status` enum('in_progress','to_collect','paid') DEFAULT 'in_progress',
  `processed_by` varchar(255) DEFAULT NULL,
  `link_status` enum('active','revoked') DEFAULT 'active',
  `link_activated_at` timestamp NULL DEFAULT NULL,
  `paid_at` timestamp NULL DEFAULT NULL,
  `is_ready_to_collect` tinyint(1) DEFAULT 0,
  `image_path` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `idx_access_token` (`access_token`),
  KEY `orders_customer_id_foreign` (`customer_id`),
  KEY `idx_orders_status_retained` (`status`),
  KEY `idx_created_at` (`created_at`),
  KEY `idx_status_customer` (`status`,`customer_id`),
  KEY `idx_link_activated` (`link_activated_at`),
  KEY `idx_customer_status` (`customer_id`,`status`),
  KEY `idx_link_status` (`link_status`),
  CONSTRAINT `orders_customer_id_foreign` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=164 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `password_resets` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  KEY `password_resets_email_index` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `settings` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `val` text DEFAULT NULL,
  `group` varchar(255) NOT NULL DEFAULT 'default',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `staff` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) unsigned NOT NULL,
  `address` varchar(255) DEFAULT NULL,
  `phone` varchar(255) DEFAULT NULL,
  `salary` decimal(10,2) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `role` varchar(50) DEFAULT 'staff',
  PRIMARY KEY (`id`),
  KEY `staff_user_id_foreign` (`user_id`),
  CONSTRAINT `staff_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=58 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `users` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `username` varchar(255) NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `avatar` varchar(255) DEFAULT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `first_login` tinyint(1) NOT NULL DEFAULT 1,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=91 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `body_measurements` (`id`, `customer_id`, `body_name`, `shoulder`, `chest`, `waist`, `hips`, `dress_length`, `wrist`, `skirt_length`, `armpit`, `created_at`, `updated_at`) VALUES
(51, 46, 'wani', '15.00', '41.00', '35.00', '41.00', '31.00', '10.00', '35.00', '18.00', '2025-01-04 13:16:39', '2025-01-04 13:16:39');
INSERT INTO `body_measurements` (`id`, `customer_id`, `body_name`, `shoulder`, `chest`, `waist`, `hips`, `dress_length`, `wrist`, `skirt_length`, `armpit`, `created_at`, `updated_at`) VALUES
(52, 46, 'anak', '10.00', '22.00', '21.00', '23.00', '21.00', '7.00', '26.00', '11.00', '2025-01-04 13:17:01', '2025-01-04 13:17:01');
INSERT INTO `body_measurements` (`id`, `customer_id`, `body_name`, `shoulder`, `chest`, `waist`, `hips`, `dress_length`, `wrist`, `skirt_length`, `armpit`, `created_at`, `updated_at`) VALUES
(53, 47, 'aisyah', '16.00', '42.00', '36.00', '44.00', '28.00', '10.00', '40.00', '22.00', '2025-01-04 13:29:54', '2025-01-04 13:29:54');
INSERT INTO `body_measurements` (`id`, `customer_id`, `body_name`, `shoulder`, `chest`, `waist`, `hips`, `dress_length`, `wrist`, `skirt_length`, `armpit`, `created_at`, `updated_at`) VALUES
(54, 47, 'anak', '10.00', '22.00', '21.00', '23.00', '21.00', '7.00', '26.00', '11.00', '2025-01-04 13:30:21', '2025-01-04 13:30:21');

INSERT INTO `customers` (`id`, `fullname`, `address`, `phone`, `email`, `created_at`, `updated_at`, `deleted_at`) VALUES
(46, 'nurul syazwani', 'taman cempaka jaya', '0152468579', 'wanikamarudin43@gmail.com', '2025-01-04 13:15:04', '2025-01-04 13:15:04', NULL);
INSERT INTO `customers` (`id`, `fullname`, `address`, `phone`, `email`, `created_at`, `updated_at`, `deleted_at`) VALUES
(47, 'siti aisyah binti muhammad', 'jalan 4, taman sentosa', '0139933651', 'aisyah342@gmail.com', '2025-01-04 13:29:28', '2025-01-04 13:29:28', NULL);


INSERT INTO `measurement_parts` (`id`, `name`, `deleted_at`, `created_at`, `updated_at`) VALUES
(1, 'Shoulder', NULL, '2024-11-29 09:12:15', '2024-11-29 09:14:49');
INSERT INTO `measurement_parts` (`id`, `name`, `deleted_at`, `created_at`, `updated_at`) VALUES
(2, 'Bust', NULL, '2024-11-29 09:12:44', '2024-11-29 09:12:44');
INSERT INTO `measurement_parts` (`id`, `name`, `deleted_at`, `created_at`, `updated_at`) VALUES
(3, 'Waist', NULL, '2024-11-29 09:13:01', '2024-11-29 09:13:01');
INSERT INTO `measurement_parts` (`id`, `name`, `deleted_at`, `created_at`, `updated_at`) VALUES
(4, 'Hips', NULL, '2024-11-29 09:13:13', '2024-11-29 09:13:13'),
(5, 'Dress Length', NULL, '2024-11-29 09:13:36', '2024-11-29 09:13:36'),
(6, 'Wrist', '2024-11-29 09:15:04', '2024-11-29 09:13:46', '2024-11-29 09:15:04'),
(7, 'Skirt Length', NULL, '2024-11-29 09:14:02', '2024-11-29 09:14:02'),
(8, 'Armpit', NULL, '2024-11-29 09:14:12', '2024-11-29 09:14:12'),
(9, 'Wrist', NULL, '2024-11-29 09:15:14', '2024-11-29 09:15:14');

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(19, '2014_10_00_000000_create_settings_table', 1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(20, '2014_10_00_000001_add_group_column_on_settings_table', 1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(21, '2014_10_12_000000_create_users_table', 1);
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(22, '2014_10_12_100000_create_password_resets_table', 1),
(23, '2019_08_19_000000_create_failed_jobs_table', 1),
(24, '2021_06_03_121029_create_customers_table', 1),
(25, '2021_06_03_142112_add_username_and_avatar_to_users_table', 1),
(26, '2021_06_04_203158_create_orders_table', 1),
(27, '2021_06_04_205635_create_designations_table', 1),
(28, '2021_06_05_170153_create_staff_table', 1),
(29, '2021_06_05_185652_create_expense_categories_table', 1),
(30, '2021_06_05_191904_create_expenses_table', 1),
(31, '2021_06_05_200934_create_income_categories_table', 1),
(32, '2021_06_05_203352_create_incomes_table', 1),
(33, '2021_06_05_212437_create_cloth_types_table', 1),
(34, '2021_06_06_002448_create_measurement_parts_table', 1),
(35, '2024_11_10_030457_add_first_login_to_users_table', 1),
(36, '2024_11_22_152941_update_staff_table', 1),
(37, '2024_11_27_041705_update_customers_table', 2),
(38, '2024_11_29_090143_create_measurement_table', 3),
(39, '2024_12_08_070247_add_status_to_orders_table', 4);

INSERT INTO `order_histories` (`id`, `customer_id`, `customer_name`, `description`, `received_on`, `amount_charged`, `processed_by`, `created_at`, `updated_at`, `staff_id`, `fullname`) VALUES
(88, 44, 'zahirah malik', 'baju kurung tradisional', '2025-01-04', '90', 'Ku Nur Hanis', '2025-01-04 13:34:59', '2025-01-04 13:34:59', 90, NULL);
INSERT INTO `order_histories` (`id`, `customer_id`, `customer_name`, `description`, `received_on`, `amount_charged`, `processed_by`, `created_at`, `updated_at`, `staff_id`, `fullname`) VALUES
(89, 46, 'nurul syazwani', 'baju kurung moden', '2025-01-04', '120', 'admin', '2025-01-04 13:39:03', '2025-01-04 13:39:03', 1, NULL);
INSERT INTO `order_histories` (`id`, `customer_id`, `customer_name`, `description`, `received_on`, `amount_charged`, `processed_by`, `created_at`, `updated_at`, `staff_id`, `fullname`) VALUES
(90, 46, 'nurul syazwani', 'baju kurung tradisional', '2025-01-04', '60', 'admin', '2025-01-04 13:53:12', '2025-01-04 13:53:12', 1, NULL);

INSERT INTO `orders` (`id`, `customer_id`, `description`, `received_on`, `amount_charged`, `access_token`, `deleted_at`, `created_at`, `updated_at`, `status`, `processed_by`, `link_status`, `link_activated_at`, `paid_at`, `is_ready_to_collect`, `image_path`) VALUES
(117, 16, 'kurung', '2024-12-25', '90', NULL, NULL, '2024-12-25 01:54:35', '2025-01-04 13:07:01', 'paid', NULL, 'revoked', '2024-12-25 01:54:35', '2024-12-25 02:01:31', 0, 'order-images/JMiWujtAcjDLVuEtkYePufW6r5ZlNd4yvNpMA12e.jpg');
INSERT INTO `orders` (`id`, `customer_id`, `description`, `received_on`, `amount_charged`, `access_token`, `deleted_at`, `created_at`, `updated_at`, `status`, `processed_by`, `link_status`, `link_activated_at`, `paid_at`, `is_ready_to_collect`, `image_path`) VALUES
(118, 16, 'kurung', '2024-12-25', '50', NULL, NULL, '2024-12-25 03:06:28', '2025-01-04 13:07:01', 'paid', NULL, 'revoked', '2024-12-25 03:06:28', '2024-12-25 03:11:29', 0, 'order-images/BpqkJ7eGwIK9pBeRevV26LXR4785SIhmUOGqpob5.jpg');
INSERT INTO `orders` (`id`, `customer_id`, `description`, `received_on`, `amount_charged`, `access_token`, `deleted_at`, `created_at`, `updated_at`, `status`, `processed_by`, `link_status`, `link_activated_at`, `paid_at`, `is_ready_to_collect`, `image_path`) VALUES
(119, 17, 'kuurng', '2024-12-26', '50', NULL, NULL, '2024-12-26 23:32:43', '2025-01-04 13:07:01', 'paid', NULL, 'revoked', '2024-12-26 23:32:43', '2024-12-26 23:32:59', 0, 'order-images/eKWHTvjfXu3GzTiDwQLp3lgtFAQn5Z3QDHWlFOV5.jpg');
INSERT INTO `orders` (`id`, `customer_id`, `description`, `received_on`, `amount_charged`, `access_token`, `deleted_at`, `created_at`, `updated_at`, `status`, `processed_by`, `link_status`, `link_activated_at`, `paid_at`, `is_ready_to_collect`, `image_path`) VALUES
(120, 18, 'dwef', '2024-12-26', '50', NULL, NULL, '2024-12-26 23:47:49', '2025-01-04 13:07:01', 'paid', NULL, 'revoked', '2024-12-26 23:47:49', '2024-12-26 23:48:02', 0, 'order-images/80t2IEX54abxe5s5SxNvQTKJyoYjJtSTUmJQBRn4.jpg'),
(121, 19, 'dw', '2024-12-28', '50', NULL, NULL, '2024-12-28 05:20:10', '2025-01-04 13:07:01', 'paid', NULL, 'revoked', '2024-12-28 05:20:10', '2024-12-28 05:20:45', 0, 'order-images/dmL9rQZHG7CK1SES6ajg8SHgnVLhNYjuNkKyS9Xc.jpg'),
(122, 20, 'reg', '2024-12-28', '50', NULL, NULL, '2024-12-28 05:40:55', '2025-01-04 13:07:01', 'paid', NULL, 'revoked', '2024-12-28 05:40:55', '2024-12-28 05:41:06', 0, 'order-images/9cPvZ4bLXa5kfDD4k8BIs7UOkU2CjQrwVYtRwd9o.jpg'),
(123, 21, 'vdfv', '2024-12-28', '40', NULL, NULL, '2024-12-28 05:45:08', '2025-01-04 13:07:01', 'paid', NULL, 'revoked', '2024-12-28 05:45:08', '2024-12-28 05:46:26', 0, 'order-images/KFUkO5o1w2edMK7CM9RK0EhrbofmSBURybLiNgAk.jpg'),
(124, 22, 'vfdv', '2024-12-28', '60', NULL, NULL, '2024-12-28 05:48:28', '2025-01-04 13:07:01', 'paid', NULL, 'revoked', '2024-12-28 05:48:28', '2024-12-28 05:48:37', 0, 'order-images/FUbjoB6n4b9l1UQnoouqTpxl0FkOzxxtVT0Mbr1k.jpg'),
(125, 23, 'tgg', '2024-12-28', '245', NULL, NULL, '2024-12-28 05:52:06', '2025-01-04 13:07:01', 'paid', NULL, 'revoked', '2024-12-28 05:52:06', '2024-12-28 05:52:17', 0, 'order-images/g11HKGSGiP1LOp2IPBiTt5Kpm7Nb46K3yQ4YdFBN.jpg'),
(126, 23, 'bfg', '2024-12-28', '85', NULL, NULL, '2024-12-28 05:52:59', '2025-01-04 13:07:01', 'paid', NULL, 'revoked', '2024-12-28 05:52:59', '2024-12-28 05:53:25', 0, 'order-images/ZJk6eFiuBEK42f5q3L1BS3bGcaUs34OsE58o7JoV.jpg'),
(127, 24, 'gvy', '2024-12-28', '50', NULL, NULL, '2024-12-28 14:31:46', '2025-01-04 13:07:01', 'paid', NULL, 'revoked', '2024-12-28 14:31:46', '2024-12-28 14:32:09', 0, 'order-images/FPH7BaY7wKZ01re6p72Ru4wobvzb5yivGiWMUQTo.jpg'),
(128, 31, 'fdh', '2024-12-28', '50', NULL, NULL, '2024-12-28 14:42:05', '2025-01-04 13:07:01', 'paid', NULL, 'revoked', '2024-12-28 14:42:05', '2024-12-28 14:42:15', 0, 'order-images/iMlxJTKVOWNxtFfrdL4Pgq7KO0FFhXXs52bXWMN1.jpg'),
(129, 31, 'ftjt', '2024-12-28', '50', NULL, NULL, '2024-12-28 14:44:11', '2025-01-04 13:07:01', 'paid', NULL, 'revoked', '2024-12-28 14:44:11', '2024-12-28 14:44:53', 0, 'order-images/JWj46fRQwkdszKtauHaQc5xSoXq5IzYvtFsg3kLW.jpg'),
(130, 31, 'nfh', '2024-12-28', '80', NULL, NULL, '2024-12-28 14:50:39', '2025-01-04 13:07:01', 'paid', NULL, 'revoked', '2024-12-28 14:50:39', '2024-12-28 14:50:49', 0, 'order-images/ROmV1nzXCwDqreWYCtUUQB39Y6kwhuM3wLz4JEAd.jpg'),
(131, 31, 'dsv', '2024-12-28', '60', NULL, NULL, '2024-12-28 14:57:04', '2025-01-04 13:07:01', 'paid', NULL, 'revoked', '2024-12-28 14:57:04', '2024-12-28 14:57:12', 0, 'order-images/tlaVmc4zV0kkMXJzVRMNfEBRQAQHOfmAhpyFEhHX.jpg'),
(132, 31, 'nyj', '2024-12-28', '6', NULL, NULL, '2024-12-28 15:01:56', '2025-01-04 13:07:01', 'paid', NULL, 'revoked', '2024-12-28 15:01:56', '2024-12-28 15:02:05', 0, 'order-images/LV9T2dkHl8ssQkqclDhpq7cdsYyrEMk4GR8XNuLB.jpg'),
(133, 31, 'nbcng', '2024-12-28', '80', NULL, NULL, '2024-12-28 15:07:42', '2025-01-04 13:07:01', 'paid', NULL, 'revoked', '2024-12-28 15:07:42', '2024-12-28 15:07:52', 0, 'order-images/dPmEPAGIKYVNaQJi5pjyfCZCWq35tgK6tdK5RZK7.jpg'),
(134, 32, 'brtg', '2024-12-28', '70', NULL, NULL, '2024-12-28 15:12:13', '2025-01-04 13:07:01', 'paid', NULL, 'revoked', '2024-12-28 15:12:13', '2024-12-28 15:12:36', 0, 'order-images/GkI1MwLBYCcFmRE55jRlLYwbPqyEeDb8KCTSYiFH.jpg'),
(135, 33, 'gege', '2024-12-28', '50', NULL, NULL, '2024-12-28 15:19:01', '2025-01-04 13:07:01', 'paid', NULL, 'revoked', '2024-12-28 15:19:01', '2024-12-28 15:19:19', 0, 'order-images/Q1ZtWVl5qvtwrzQgmglG5AaXRZqw9LilI1SC1W2g.jpg'),
(136, 34, 'ftuu', '2024-12-28', '90', NULL, NULL, '2024-12-28 15:25:19', '2025-01-04 13:07:01', 'paid', NULL, 'revoked', '2024-12-28 15:25:19', '2024-12-28 15:25:44', 0, 'order-images/NM5IfYYXk5YLNmYgAn2ctorVK9p8syL22BRyTNmK.jpg'),
(137, 34, 'vhgj', '2024-12-28', '40', NULL, NULL, '2024-12-28 15:34:27', '2025-01-04 13:07:01', 'paid', NULL, 'revoked', '2024-12-28 15:34:27', '2024-12-28 15:34:36', 0, 'order-images/ulmBGQibSqwLGyk0ECmqR5LnL8HB5q4jyR05UadX.jpg'),
(138, 34, 'htdtj', '2024-12-28', '90', NULL, NULL, '2024-12-28 15:38:35', '2025-01-04 13:07:01', 'paid', NULL, 'revoked', '2024-12-28 15:38:35', '2024-12-28 15:40:01', 0, 'order-images/ZbgW7RsfwErWLxxf8DQAVjayJHSymII5XjcFD1sa.jpg'),
(139, 35, 'baju kurung', '2024-12-28', '65', NULL, NULL, '2024-12-28 17:46:13', '2025-01-04 13:07:02', 'paid', NULL, 'revoked', '2024-12-28 17:46:13', '2024-12-28 17:46:35', 0, 'order-images/Mhat3qMX7ywPtVOsHFGahaoBfiwtqBYyrjbwI8MD.jpg'),
(140, 36, 'kurung kedah', '2024-12-28', '60', NULL, NULL, '2024-12-28 17:52:05', '2025-01-04 13:07:02', 'paid', NULL, 'revoked', '2024-12-28 17:52:05', '2024-12-28 17:52:28', 0, 'order-images/E9o0J6kI6x9Q2rvZJC2KravoLsvtyczTNM3AJ1RQ.jpg'),
(141, 37, 'yuff', '2024-12-28', '80', NULL, NULL, '2024-12-28 18:05:07', '2025-01-04 13:07:02', 'paid', NULL, 'revoked', '2024-12-28 18:05:07', '2024-12-28 18:05:18', 0, 'order-images/jnHrJAU9EgMCCVswUB2BZ3mRzoE46vV352YJZAdR.jpg'),
(142, 37, 'regwrhw', '2024-12-28', '66', NULL, NULL, '2024-12-28 18:12:01', '2025-01-04 13:07:02', 'paid', NULL, 'revoked', '2024-12-28 18:12:01', '2024-12-28 18:12:10', 0, 'order-images/Ce1jA2kHEWsnRhuUMJBWXlyY8AbFD92sLx7RHeDQ.jpg'),
(143, 37, 'erh', '2024-12-28', '80', NULL, NULL, '2024-12-28 18:13:13', '2025-01-04 13:07:02', 'paid', NULL, 'revoked', '2024-12-28 18:13:13', '2024-12-28 18:13:59', 0, 'order-images/ahy2wqGC6eK2mo9YUnVYCWW0w9OersNVvN0vP4az.jpg'),
(144, 37, 'fbsrsh', '2024-12-28', '60', NULL, NULL, '2024-12-28 18:17:52', '2025-01-04 13:07:02', 'paid', NULL, 'revoked', '2024-12-28 18:17:52', '2024-12-28 18:18:21', 0, 'order-images/ZVDFedxrqHXIn75ArJMGSQgeShMsPGUJwxBXRXTL.jpg'),
(145, 37, 'dcw', '2024-12-28', '60', NULL, NULL, '2024-12-28 18:35:53', '2025-01-04 13:07:02', 'paid', NULL, 'revoked', '2024-12-28 18:35:53', '2024-12-28 18:36:35', 0, 'order-images/L2zvptFgGdH7SqnUd4Xz2jd09bU8305BQYXP8Goj.jpg'),
(146, 37, 'nkn', '2024-12-28', '60', NULL, NULL, '2024-12-28 18:39:29', '2025-01-04 13:07:02', 'paid', NULL, 'revoked', '2024-12-28 18:39:29', '2024-12-28 18:39:41', 0, 'order-images/nKlvBlRZzFaiUngoTNv0Tit8wllKVhLc4t6PjwAp.jpg'),
(147, 37, 'dfhsrh', '2024-12-28', '40', NULL, NULL, '2024-12-28 19:01:51', '2025-01-04 13:07:02', 'paid', NULL, 'revoked', '2024-12-28 19:01:51', '2024-12-28 19:31:54', 0, 'order-images/3Wip5lHIn9itfFOYJQkcf5lO84MxKLEomArfAg0w.jpg'),
(148, 37, 'ngdgn', '2024-12-28', '40', NULL, NULL, '2024-12-28 19:35:00', '2025-01-04 13:07:02', 'paid', NULL, 'revoked', '2024-12-28 19:35:00', '2024-12-28 19:35:12', 0, 'order-images/aVDuoCQXz45JJ5Ac780Nt1KJvLiu74myKqNRLqAR.jpg'),
(149, 37, 'bsfb', '2024-12-28', '12.00', NULL, NULL, '2024-12-28 20:00:58', '2025-01-04 13:07:02', 'paid', NULL, 'revoked', '2024-12-28 20:00:58', '2024-12-28 20:01:46', 0, 'order-images/4C9bJPkv2EnhBlsKALPLs7tUS9S8UDztO9fgBuJw.jpg'),
(150, 38, 'aewsd', '2024-12-29', '60', NULL, NULL, '2024-12-29 11:20:13', '2025-01-04 13:07:02', 'paid', NULL, 'revoked', '2024-12-29 11:20:13', '2024-12-29 11:28:06', 0, 'order-images/NXRVH9LyBaw0iKLBnRYf317QXsXmUtmTuW04rucA.jpg'),
(151, 39, 'bjkb', '2024-12-29', '80', NULL, NULL, '2024-12-29 11:20:38', '2025-01-04 13:07:02', 'paid', NULL, 'revoked', '2024-12-29 11:20:38', '2024-12-29 11:20:51', 0, 'order-images/vyyGA2eZSmXlrpvolV3V37OE21DlfQZsSNTYINhT.jpg'),
(152, 39, 'ngjr', '2024-12-29', '60', NULL, NULL, '2024-12-29 11:25:47', '2025-01-04 13:07:02', 'paid', NULL, 'revoked', '2024-12-29 11:25:47', '2024-12-31 09:14:21', 0, 'order-images/dXoTkOHsN3qGUPMRl2508NQR6j1M9R3VBmvkllja.jpg'),
(153, 40, 'bsfgs', '2024-12-29', '60', NULL, NULL, '2024-12-29 12:22:16', '2025-01-04 13:07:02', 'paid', NULL, 'revoked', '2024-12-29 12:22:16', '2024-12-29 12:33:14', 0, 'order-images/Fh8jBJjbIHCXiDWZFrQEQX7uLrIk1otUaVWCaGQb.jpg'),
(154, 40, 'yfuy', '2024-12-29', '60', NULL, NULL, '2024-12-29 12:35:51', '2025-01-04 13:07:02', 'paid', NULL, 'revoked', '2024-12-29 12:35:51', '2024-12-29 12:38:11', 0, 'order-images/LFn32heWEfFopTZzVZpLtRod7AyoZVQleVVz5dwz.jpg'),
(155, 40, 'gcytcyg', '2024-12-29', '50', NULL, NULL, '2024-12-29 12:42:23', '2025-01-04 13:07:02', 'paid', NULL, 'revoked', '2024-12-29 12:42:23', '2024-12-29 12:44:22', 0, 'order-images/zT2CHgN3zsw3dmwuFIIHdUJe8bFm2Bm8Oee38xmb.jpg'),
(156, 40, 'bdgbd', '2024-12-29', '60', NULL, NULL, '2024-12-29 12:47:13', '2025-01-04 13:07:02', 'paid', NULL, 'revoked', '2024-12-29 12:47:13', '2024-12-29 12:56:23', 0, 'order-images/v2WiVRxrJqFCdwwjr3XunVhrm36bB8bujgpGqytn.jpg'),
(157, 40, 'bfg', '2024-12-29', '50', NULL, NULL, '2024-12-29 13:02:47', '2025-01-04 13:07:02', 'paid', NULL, 'revoked', '2024-12-29 13:02:47', '2024-12-29 13:03:41', 0, 'order-images/vnvVwH6YW1xZAan80BuOZWbH8U0ibdybVyZW15Nf.jpg'),
(158, 40, 'gerge', '2024-12-29', '60', NULL, NULL, '2024-12-29 13:11:18', '2025-01-04 13:07:02', 'paid', NULL, 'revoked', '2024-12-29 13:11:18', '2024-12-29 13:11:42', 0, 'order-images/u4IEzrhtr37sgAU5uDi6Kurw6N7IRalWApKpjiOv.jpg'),
(159, 39, 'kurung', '2024-12-31', '60', NULL, NULL, '2024-12-31 09:16:41', '2025-01-04 13:07:02', 'paid', NULL, 'revoked', '2024-12-31 09:16:41', '2024-12-31 09:26:00', 0, 'order-images/GAwmmEUNm37wHo8YzAsMNl49r4IoRy7gQTeeKlku.jpg'),
(160, 42, 'kurung kedah', '2025-01-01', '80', NULL, NULL, '2025-01-01 00:47:38', '2025-01-04 13:07:02', 'paid', NULL, 'revoked', '2025-01-01 00:47:38', '2025-01-01 00:47:51', 0, 'order-images/zkdocGVygYfXwFvISmUDReFsdPVg89fY70MQB0WK.jpg'),
(161, 44, 'baju kurung tradisional', '2025-01-04', '90', '50694a02-2302-4bce-99a6-d6556b63e1a6', NULL, '2025-01-04 13:32:36', '2025-01-04 13:36:33', 'paid', NULL, 'revoked', '2025-01-04 13:32:36', '2025-01-04 13:34:59', 0, 'order-images/GZnA0A9EJCCNUlUECTMazuLke01HjSXzzwr55YBo.jpg'),
(162, 46, 'baju kurung moden', '2025-01-04', '120', NULL, NULL, '2025-01-04 13:33:34', '2025-01-04 13:39:05', 'paid', NULL, 'revoked', '2025-01-04 13:33:34', '2025-01-04 13:39:03', 0, 'order-images/Qt13N8BobeOYTk6oVYqoUW0Uo2q84GVMRuDJIHBF.jpg'),
(163, 46, 'baju kurung tradisional', '2025-01-04', '60', 'b929464b-6db1-43b1-aefd-cc2120994c17', NULL, '2025-01-04 13:53:04', '2025-01-04 13:57:23', 'paid', NULL, 'revoked', '2025-01-04 13:53:04', '2025-01-04 13:53:12', 0, 'order-images/8uatZmCfrnk2V2UE7qPXEOKaGBcxW28BADG5vSmY.jpg');

INSERT INTO `password_resets` (`email`, `token`, `created_at`) VALUES
('kuhusna31@gmail.com', '9qK78y7aqVTQtzQHvNn91JCXl2N9OfAFIOFKTPjh9MLm0Az1xZkVzo81ugL6Kay5', '2024-12-22 16:04:06');
INSERT INTO `password_resets` (`email`, `token`, `created_at`) VALUES
('kuhusna31@gmail.com', 'puaRTdQS6S1y3rPNXdgafUmWMmShCzzuBECGKhWbSSNoabqk7AvzfJ0G0jhQor0u', '2024-12-22 16:04:40');
INSERT INTO `password_resets` (`email`, `token`, `created_at`) VALUES
('kuhusna31@gmail.com', 'uxTWhdDKFHjnTJu6mEnLUlJ61hM4G6M5WXqyRNUnj9pZkYelOtOLxx3tNDGTY1Is', '2024-12-22 16:12:44');
INSERT INTO `password_resets` (`email`, `token`, `created_at`) VALUES
('kuhusna31@gmail.com', 'fiEwOdZk69CJBd58uU6SM8QCNwNUJXuyJ7A966HrXnpb2esItnRZdkPqF37tvnD2', '2024-12-22 16:23:10'),
('kuhusna31@gmail.com', 'a1nmvKyZFMiCnFH40YLrQ7d5jFHO4xPkmZiS9Ob97XpuQ1JfDLsBnwwIwyXJa2U7', '2024-12-22 16:23:27'),
('kuhusna31@gmail.com', 'gj72SWsib4mU2YkYFSuryQx1hESzDSlBnrpn6d8YeraidAoFlAnYgq9uFP1zYfg9', '2024-12-22 16:23:43'),
('kuhusna31@gmail.com', 'SV3vi71J82dWAEcLFgclmtS7Rc0BSH2hSzdiowtHBkO9DsQRUA1B54NDicDkL58N', '2024-12-22 16:24:11'),
('kuhusna31@gmail.com', 'YhTSJWfWeo6rdR3p1gl4vGGw1dcrXlU3e90jnwXiaPMNhLkWMlNGKgt7KxyNZfmY', '2024-12-22 16:33:14'),
('kuhusna31@gmail.com', 'Kjqj2MBwGr0rGGYvcSNr5wIf2eXYOshKf58HnN96cBNIrBUwDnuQp6dWOyt55J4X', '2024-12-22 16:37:43'),
('di210059@student.uthm.edu.my', 'jWYPeiCZxBGY1ATtKlRFWRgQzzuSZslLxuu5s1yCnwMGzmKcaaKehnlk708KUNIS', '2024-12-24 09:06:07'),
('di210059@student.uthm.edu.my', '6ZuZNHwwEbWwl8v08lJmcSsFmYMnYGMQqWPpXJUX22vCpiH8TlJ67SEi8jLy11qA', '2024-12-24 10:11:29');

INSERT INTO `settings` (`id`, `name`, `val`, `group`, `created_at`, `updated_at`) VALUES
(1, 'app_name', 'Nadimah Tailor', 'default', '2024-12-20 15:16:44', '2024-12-20 15:16:44');
INSERT INTO `settings` (`id`, `name`, `val`, `group`, `created_at`, `updated_at`) VALUES
(2, 'app_currency', 'RM', 'default', '2024-12-20 15:16:45', '2024-12-20 15:16:45');
INSERT INTO `settings` (`id`, `name`, `val`, `group`, `created_at`, `updated_at`) VALUES
(3, 'logo', 'app/HooZnOxhTGcV9CUoHqyIkY2QBtUS1JG0ibBzhh3q.png', 'default', '2024-12-20 15:16:45', '2024-12-21 16:49:46');
INSERT INTO `settings` (`id`, `name`, `val`, `group`, `created_at`, `updated_at`) VALUES
(4, 'favicon', 'app/THcxUbaMqlK4r4bK4jKHhlWhoFHt4QnXKwh0HfFR.png', 'default', '2024-12-20 15:16:45', '2024-12-21 16:49:46');

INSERT INTO `staff` (`id`, `user_id`, `address`, `phone`, `salary`, `created_at`, `updated_at`, `deleted_at`, `role`) VALUES
(1, 3, 'kl, selangor', '017 376 8409', '1200.00', '2024-11-22 16:23:48', '2024-11-22 16:24:00', NULL, 'staff');
INSERT INTO `staff` (`id`, `user_id`, `address`, `phone`, `salary`, `created_at`, `updated_at`, `deleted_at`, `role`) VALUES
(2, 4, 'nilai, n sembilan', '017 923 8882', '1300.00', '2024-11-22 16:29:02', '2024-11-22 16:29:19', NULL, 'staff');
INSERT INTO `staff` (`id`, `user_id`, `address`, `phone`, `salary`, `created_at`, `updated_at`, `deleted_at`, `role`) VALUES
(28, 1, 'Nadimah Tailor', '0152436987', '3000.00', '2024-11-29 23:22:36', '2024-12-24 09:38:24', NULL, 'admin');
INSERT INTO `staff` (`id`, `user_id`, `address`, `phone`, `salary`, `created_at`, `updated_at`, `deleted_at`, `role`) VALUES
(40, 73, 'batu pahat', '0152468579', '1200.00', '2024-12-22 05:32:37', '2024-12-22 05:32:44', NULL, 'staff'),
(45, 78, 'kluang', '0139933915', '1200.00', '2024-12-22 16:02:07', '2024-12-22 16:02:53', NULL, 'staff'),
(55, 88, 'pahang', '0142536987', '1500.00', '2024-12-24 10:10:39', '2024-12-24 10:10:57', NULL, 'staff'),
(56, 89, '', '', '0.00', '2024-12-31 09:03:21', '2024-12-31 09:03:21', NULL, 'staff'),
(57, 90, 'precint 17, putrajaya', '0139933914', '1500.00', '2025-01-04 13:25:27', '2025-01-04 13:26:03', NULL, 'staff');

INSERT INTO `users` (`id`, `name`, `email`, `username`, `email_verified_at`, `password`, `avatar`, `remember_token`, `created_at`, `updated_at`, `first_login`) VALUES
(1, 'admin', 'admin@admin.com', 'admin', NULL, '$2y$10$wdT5CM3mprDGzOx/qkjAXeVuPCRi6h9mvV/q00iYHl6QOcoEMXtIy', 'avatars/4uWdE9DSy35osO8PbUixazL4Nt21Bb3X2VcwntNb.png', NULL, NULL, '2024-12-31 08:06:35', 0);
INSERT INTO `users` (`id`, `name`, `email`, `username`, `email_verified_at`, `password`, `avatar`, `remember_token`, `created_at`, `updated_at`, `first_login`) VALUES
(2, 'syahir', 'syahir@gmail.com', 'syahir', NULL, '$2y$10$pR/rOo9X41YvW2DNdK6h4.7ciQTv3j/cqPEh0aiSVRvTFUZkrqKnm', NULL, NULL, '2024-11-22 16:01:40', '2024-11-22 16:01:40', 1);
INSERT INTO `users` (`id`, `name`, `email`, `username`, `email_verified_at`, `password`, `avatar`, `remember_token`, `created_at`, `updated_at`, `first_login`) VALUES
(3, 'hanis', 'hanis@gmail.com', 'hanis', NULL, '$2y$10$XTJHIy/TiENaqkxroRi97Oz.ZkSC/brAlwyMoRWcB1p3oO0PbJYlu', NULL, NULL, '2024-11-22 16:23:48', '2024-11-22 16:23:48', 1);
INSERT INTO `users` (`id`, `name`, `email`, `username`, `email_verified_at`, `password`, `avatar`, `remember_token`, `created_at`, `updated_at`, `first_login`) VALUES
(4, 'iskandar', 'iskandar@gmail.com', 'iskandar', NULL, '$2y$10$8fs47J4vfB7xgfl60nsZFuprcgMAAiVKpe3gquihz7fVi7NrQb582', NULL, NULL, '2024-11-22 16:29:02', '2024-11-22 16:29:02', 1),
(73, 'is', 'is@gmail.com', 'is', NULL, '$2y$10$HM7d7JE9f0.9887cBngvYOILNLhIj7T84jYPnkbJykHpXlLfMS/5a', NULL, NULL, '2024-12-22 05:32:37', '2024-12-22 05:32:37', 1),
(78, 'siti', 'kuhusna31@gmail.com', 'siti', NULL, '$2y$10$CVqRk.MtPFEmdBPRey/H8ufMT./esAm4blxfZDtk9mnhNpsqhbv/S', NULL, NULL, '2024-12-22 16:02:07', '2024-12-22 16:03:35', 0),
(88, 'ku', 'di210059@student.uthm.edu.my', 'ku', NULL, '$2y$10$sx1u9sPcu4YScwoDss3UO.roKRMKMLi8rHS5jfGeUTlfcNh9BHaFq', NULL, NULL, '2024-12-24 10:10:39', '2024-12-28 14:30:07', 0),
(89, 'asma', 'asma@gmail.com', 'asmawahidah', NULL, '$2y$10$XH8z.izZWPxUtEmobpyHf.MUgA2rKXiks983MzpEN527txDKyjC7.', NULL, NULL, '2024-12-31 09:03:21', '2024-12-31 09:03:21', 1),
(90, 'Ku Nur Hanis', 'kuhanis380@gmail.com', 'kuhanis', NULL, '$2y$10$QIs6VXEqjDdKod1TTTTBvuDR9vURo2qEA6WsJQNQ3PqsVi2EuBaF.', NULL, NULL, '2025-01-04 13:25:27', '2025-01-04 13:27:44', 0);


/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;