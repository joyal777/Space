-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 31, 2025 at 05:23 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `space`
--

-- --------------------------------------------------------

--
-- Table structure for table `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `cache`
--

INSERT INTO `cache` (`key`, `value`, `expiration`) VALUES
('laravel_cache_15d8671e881778a1c15d7a56c24870f3', 'i:1;', 1761752496),
('laravel_cache_15d8671e881778a1c15d7a56c24870f3:timer', 'i:1761752496;', 1761752496),
('laravel_cache_e4a5d4c398673d7742b4b9846c5a3eb7', 'i:1;', 1761926436),
('laravel_cache_e4a5d4c398673d7742b4b9846c5a3eb7:timer', 'i:1761926436;', 1761926436);

-- --------------------------------------------------------

--
-- Table structure for table `cache_locks`
--

CREATE TABLE `cache_locks` (
  `key` varchar(255) NOT NULL,
  `owner` varchar(255) NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `companies`
--

CREATE TABLE `companies` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `logo` varchar(255) DEFAULT NULL,
  `website` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `phone` varchar(255) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `city` varchar(255) DEFAULT NULL,
  `state` varchar(255) DEFAULT NULL,
  `country` varchar(255) DEFAULT NULL,
  `postal_code` varchar(255) DEFAULT NULL,
  `status` enum('active','inactive') NOT NULL DEFAULT 'active',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `companies`
--

INSERT INTO `companies` (`id`, `name`, `slug`, `description`, `logo`, `website`, `email`, `phone`, `address`, `city`, `state`, `country`, `postal_code`, `status`, `created_at`, `updated_at`) VALUES
(1, 'Tech Solutions Inc.', 'tech-solutions-inc-wgrmyo', 'A technology company specializing in web development and digital solutions.', NULL, 'https://techsolutions.com', 'info@techsolutions.com', '+1-555-0123', '123 Tech Street', 'San Francisco', 'CA', 'USA', '94105', 'active', '2025-10-30 11:49:56', '2025-10-30 11:49:56'),
(2, 'Creative Design Studio', 'creative-design-studio-qbylse', 'A creative agency focused on branding, design, and marketing.', NULL, 'https://creativedesign.studio', 'hello@creativedesign.studio', '+1-555-0124', '456 Design Ave', 'New York', 'NY', 'USA', '10001', 'active', '2025-10-30 11:49:56', '2025-10-30 11:49:56'),
(3, 'Global Consulting Group', 'global-consulting-group-gnvm27', 'International business consulting and strategy services.', NULL, 'https://globalconsulting.group', 'contact@globalconsulting.group', '+1-555-0125', '789 Business Blvd', 'Chicago', 'IL', 'USA', '60601', 'active', '2025-10-30 11:49:57', '2025-10-30 11:49:57');

-- --------------------------------------------------------

--
-- Table structure for table `company_user`
--

CREATE TABLE `company_user` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `company_id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `role` enum('owner','admin','member') NOT NULL DEFAULT 'member',
  `status` enum('active','inactive','pending') NOT NULL DEFAULT 'active',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `company_user`
--

INSERT INTO `company_user` (`id`, `company_id`, `user_id`, `role`, `status`, `created_at`, `updated_at`) VALUES
(1, 1, 4, 'owner', 'active', '2025-10-30 11:49:56', '2025-10-30 11:49:56'),
(2, 2, 4, 'owner', 'active', '2025-10-30 11:49:56', '2025-10-30 11:49:56'),
(3, 3, 4, 'owner', 'active', '2025-10-30 11:49:57', '2025-10-30 11:49:57');

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uuid` varchar(255) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `general_settings`
--

CREATE TABLE `general_settings` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `site_name` varchar(255) DEFAULT NULL,
  `logo` varchar(255) DEFAULT NULL,
  `favicon` varchar(255) DEFAULT NULL,
  `tagline` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `phone` varchar(255) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `general_settings`
--

INSERT INTO `general_settings` (`id`, `site_name`, `logo`, `favicon`, `tagline`, `email`, `phone`, `address`, `created_at`, `updated_at`) VALUES
(1, 'space', 'logo.png', 'logo.png', 'A workspace platform', 'joyaltony78@gmail.com', '+91 9188828360', 'Kochi , Kerala', '2025-10-21 15:18:52', '2025-10-21 15:18:57');

-- --------------------------------------------------------

--
-- Table structure for table `jobs`
--

CREATE TABLE `jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `queue` varchar(255) NOT NULL,
  `payload` longtext NOT NULL,
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
  `id` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `total_jobs` int(11) NOT NULL,
  `pending_jobs` int(11) NOT NULL,
  `failed_jobs` int(11) NOT NULL,
  `failed_job_ids` longtext NOT NULL,
  `options` mediumtext DEFAULT NULL,
  `cancelled_at` int(11) DEFAULT NULL,
  `created_at` int(11) NOT NULL,
  `finished_at` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '0001_01_01_000000_create_users_table', 1),
(2, '0001_01_01_000001_create_cache_table', 1),
(3, '0001_01_01_000002_create_jobs_table', 1),
(4, '2025_08_14_170933_add_two_factor_columns_to_users_table', 1),
(5, '2025_10_20_140844_create_general_settings_table', 2),
(6, '2025_10_23_145255_create_projects_table', 3),
(7, '2025_10_25_072420_image_column_in_projects', 4),
(8, '2025_10_26_112848_create_tasks_table', 5),
(9, '2025_10_29_150734_create_project_user_table', 6),
(10, '2025_10_30_171035_create_companies_table', 7),
(11, '2025_10_30_171232_create_company_user_table', 8),
(12, '2025_10_30_171442_add_company_fields_to_projects_table', 9);

-- --------------------------------------------------------

--
-- Table structure for table `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `projects`
--

CREATE TABLE `projects` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `project_code` varchar(255) NOT NULL,
  `project_name` varchar(255) NOT NULL,
  `project_title` varchar(255) DEFAULT NULL,
  `project_description` text DEFAULT NULL,
  `project_status` enum('pending','in_progress','completed','on_hold','cancelled') NOT NULL DEFAULT 'pending',
  `project_update` text DEFAULT NULL,
  `start_date` date DEFAULT NULL,
  `end_date` text DEFAULT NULL,
  `type` enum('personal','company') NOT NULL DEFAULT 'personal',
  `project_image` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `company_id` bigint(20) UNSIGNED DEFAULT NULL,
  `created_by` bigint(20) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `projects`
--

INSERT INTO `projects` (`id`, `project_code`, `project_name`, `project_title`, `project_description`, `project_status`, `project_update`, `start_date`, `end_date`, `type`, `project_image`, `created_at`, `updated_at`, `company_id`, `created_by`) VALUES
(1, 'PROJ-WEBSITE24', 'Website Redesign', 'Company Website Redesign 2024', 'Complete redesign of the company website with modern UI/UX and improved performance.', 'in_progress', 'Homepage design completed. Working on about page!', '2024-01-15', '2024-03-30', 'personal', 'photo-1.webp', '2025-10-23 10:03:46', '2025-10-31 10:43:33', NULL, NULL),
(2, 'PROJ-MOBILEAPP', 'Mobile App Development', 'Customer Mobile Application', 'Development of a cross-platform mobile application for customer engagement.', 'pending', 'Requirements gathering phase. Waiting for client approval.', '2024-02-01', '2024-06-15', 'personal', 'photo-2.webp', '2025-10-23 10:03:46', '2025-10-26 05:25:49', NULL, NULL),
(3, 'PROJ-ECOMM', 'E-commerce Platform', 'Online Store Development', 'Build a complete e-commerce platform with payment integration and inventory management.', 'completed', 'Project completed and deployed to production. Client training done.', '2023-11-01', '2024-01-20', 'personal', 'photo-3.webp', '2025-10-23 10:03:46', '2025-10-26 05:25:49', NULL, NULL),
(4, 'PROJ-CRMSYS', 'CRM System', 'Customer Relationship Management', 'Custom CRM system for sales team with lead tracking and reporting features.', 'in_progress', 'Database design completed. Starting frontend development.', '2024-01-10', '2024-04-30', 'personal', 'photo-4.webp', '2025-10-23 10:03:46', '2025-10-26 05:25:49', NULL, NULL),
(5, 'PROJ-APIINT', 'API Integration', 'Third-party API Integration Project', 'Integrate multiple third-party APIs for data synchronization and automationnnnnnnnnn', 'on_hold', 'Waiting for API documentation from third-party vendors.', '2024-01-20', 'TBD - Waiting for vendor response', 'personal', 'Ot78vIF2ohQLfSbMxkSSyptGSNCDCGBWMyaV3icE.jpg', '2025-10-23 10:03:46', '2025-10-27 12:57:52', NULL, NULL),
(6, 'PROJ-SECAUDIT', 'Security Audit', 'System Security Assessment', 'Comprehensive security audit and vulnerability assessment of all systems.', 'pending', 'Scheduled for next quarter. Preparing audit checklist.', '2024-03-01', '2024-03-15', 'personal', 'photo-6.webp', '2025-10-23 10:03:46', '2025-10-26 05:25:49', NULL, NULL),
(7, 'PROJ-DATAMIG', 'Data Migration', 'Legacy System Data Migration', 'Migrate data from legacy systems to new cloud-based platform.', 'cancelled', 'Project cancelled due to budget constraints.', '2024-01-05', 'N/A', 'personal', 'photo-7.webp', '2025-10-23 10:03:46', '2025-10-26 05:25:49', NULL, NULL),
(9, 'PROJ-T4UORARR', 'X-card', 'xcard for genz', 'A genz based project', 'pending', NULL, '2025-10-24', '2025-11-25', 'personal', 'photo-8.png', '2025-10-24 11:44:59', '2025-10-26 05:25:49', NULL, NULL),
(10, 'PROJ-GSDPXBJ8', 'Project Tailwind.css', 'customize with tailwind', 'Convert existing styles to tailwind.css', 'in_progress', '\"Debi Configured tailwind.css \r\nteam 48 can start working.\"', '2025-10-29', 'no', 'personal', '1olp9gMJf2MfFoVfE9eZEoNn525YtTWjunaPQER1.png', '2025-10-29 11:52:22', '2025-10-29 11:52:22', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `project_user`
--

CREATE TABLE `project_user` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `project_id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `role` varchar(255) NOT NULL DEFAULT 'member',
  `status` varchar(255) NOT NULL DEFAULT 'pending',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `project_user`
--

INSERT INTO `project_user` (`id`, `project_id`, `user_id`, `role`, `status`, `created_at`, `updated_at`) VALUES
(1, 1, 1, 'owner', 'accepted', NULL, NULL),
(3, 1, 3, 'member', 'accepted', '2025-10-29 10:50:09', '2025-10-29 10:50:18'),
(4, 10, 1, 'owner', 'accepted', '2025-10-29 11:52:22', '2025-10-29 11:52:22');

-- --------------------------------------------------------

--
-- Table structure for table `sessions`
--

CREATE TABLE `sessions` (
  `id` varchar(255) NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `payload` longtext NOT NULL,
  `last_activity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sessions`
--

INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
('7dlpgXGTqTcZrosnzVMAtuWZNAKZ2GijLKzET5ah', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:144.0) Gecko/20100101 Firefox/144.0', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoidDdyYnlocEo3ZHlyd0ZoQ2s5WnVZOG9IZjhXbFZ6QXBndjh0NWM2aiI7czoyMjoiUEhQREVCVUdCQVJfU1RBQ0tfREFUQSI7YTowOnt9czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MzA6Imh0dHA6Ly9sb2NhbGhvc3Q6ODAwMC9wcm9qZWN0cyI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1761927588),
('KAmHRX9I6QQJl3fTNARLpRMZPGhLmmQYG3U0F7AE', 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:144.0) Gecko/20100101 Firefox/144.0', 'YTo1OntzOjY6Il90b2tlbiI7czo0MDoiSm5rZzZBVVBCdUV5UjJ6RUxVYWlNcDI4dE56bXR1TU1BNmFZZGw5OSI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MzA6Imh0dHA6Ly9sb2NhbGhvc3Q6ODAwMC9wcm9qZWN0cyI7fXM6NTA6ImxvZ2luX3dlYl81OWJhMzZhZGRjMmIyZjk0MDE1ODBmMDE0YzdmNThlYTRlMzA5ODlkIjtpOjE7czoyMjoiUEhQREVCVUdCQVJfU1RBQ0tfREFUQSI7YTowOnt9fQ==', 1761927748);

-- --------------------------------------------------------

--
-- Table structure for table `tasks`
--

CREATE TABLE `tasks` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `project_id` bigint(20) UNSIGNED NOT NULL,
  `task_code` varchar(255) NOT NULL,
  `task_name` varchar(255) NOT NULL,
  `task_description` text DEFAULT NULL,
  `task_status` enum('pending','in_progress','completed','on_hold','cancelled') NOT NULL DEFAULT 'pending',
  `priority` int(11) NOT NULL DEFAULT 1,
  `start_date` date DEFAULT NULL,
  `end_date` date DEFAULT NULL,
  `completed_at` date DEFAULT NULL,
  `estimated_hours` int(11) DEFAULT NULL,
  `actual_hours` int(11) DEFAULT NULL,
  `assigned_to` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`assigned_to`)),
  `notes` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `tasks`
--

INSERT INTO `tasks` (`id`, `project_id`, `task_code`, `task_name`, `task_description`, `task_status`, `priority`, `start_date`, `end_date`, `completed_at`, `estimated_hours`, `actual_hours`, `assigned_to`, `notes`, `created_at`, `updated_at`) VALUES
(1, 1, 'TASK-VTJFGTBB', 'Task 1 for Website Redesign', 'This is task 1 description for the project Website Redesign', 'completed', 1, '2025-10-27', '2025-10-31', NULL, 31, 41, '[]', NULL, '2025-10-26 06:11:38', '2025-10-27 10:42:15'),
(2, 1, 'TASK-XARI853S', 'Task 2 for Website Redesign', 'This is task 2 description for the project Website Redesign', 'pending', 1, '2025-10-05', '2025-12-06', NULL, 30, 16, NULL, NULL, '2025-10-26 06:11:38', '2025-10-26 06:11:38'),
(3, 1, 'TASK-T4E8P3BW', 'Task 3 for Website Redesign', 'This is task 3 description for the project Website Redesign', 'completed', 1, '2025-10-20', '2025-11-11', NULL, 38, 29, NULL, NULL, '2025-10-26 06:11:38', '2025-10-26 06:11:38'),
(4, 1, 'TASK-RMY3TM4F', 'Task 4 for Website Redesign', 'This is task 4 description for the project Website Redesign', 'in_progress', 5, '2025-09-29', '2025-12-13', NULL, 1, 7, NULL, NULL, '2025-10-26 06:11:38', '2025-10-26 06:11:38'),
(5, 1, 'TASK-EOQ1PVAO', 'Task 5 for Website Redesign', 'This is task 5 description for the project Website Redesign', 'pending', 4, '2025-10-10', '2025-11-22', NULL, 31, 22, NULL, NULL, '2025-10-26 06:11:38', '2025-10-26 06:11:38'),
(6, 2, 'TASK-ESI6JHCL', 'Task 1 for Mobile App Development', 'This is task 1 description for the project Mobile App Development', 'in_progress', 1, '2025-10-08', '2025-11-24', NULL, 29, 33, NULL, NULL, '2025-10-26 06:11:38', '2025-10-26 06:11:38'),
(7, 2, 'TASK-ZZKUZST8', 'Task 2 for Mobile App Development', 'This is task 2 description for the project Mobile App Development', 'pending', 2, '2025-10-25', '2025-11-30', NULL, 36, 3, NULL, NULL, '2025-10-26 06:11:38', '2025-10-26 06:11:38'),
(8, 2, 'TASK-DZ3V2WXT', 'Task 3 for Mobile App Development', 'This is task 3 description for the project Mobile App Development', 'on_hold', 2, '2025-09-29', '2025-10-29', NULL, 30, 32, NULL, NULL, '2025-10-26 06:11:38', '2025-10-26 06:11:38'),
(9, 2, 'TASK-QQFVSTB9', 'Task 4 for Mobile App Development', 'This is task 4 description for the project Mobile App Development', 'completed', 4, '2025-10-20', '2025-12-01', NULL, 9, 12, NULL, NULL, '2025-10-26 06:11:38', '2025-10-26 06:11:38'),
(10, 3, 'TASK-3PCHFYHS', 'Task 1 for E-commerce Platform', 'This is task 1 description for the project E-commerce Platform', 'on_hold', 5, '2025-10-05', '2025-12-09', NULL, 40, 31, NULL, NULL, '2025-10-26 06:11:38', '2025-10-26 06:11:38'),
(11, 3, 'TASK-2RRE199P', 'Task 2 for E-commerce Platform', 'This is task 2 description for the project E-commerce Platform', 'completed', 1, '2025-09-27', '2025-11-26', NULL, 30, 41, NULL, NULL, '2025-10-26 06:11:38', '2025-10-26 06:11:38'),
(12, 3, 'TASK-C4CVJR2X', 'Task 3 for E-commerce Platform', 'This is task 3 description for the project E-commerce Platform', 'in_progress', 4, '2025-09-26', '2025-11-19', NULL, 28, 24, NULL, NULL, '2025-10-26 06:11:38', '2025-10-26 06:11:38'),
(13, 3, 'TASK-WNICR5XG', 'Task 4 for E-commerce Platform', 'This is task 4 description for the project E-commerce Platform', 'pending', 5, '2025-10-25', '2025-12-11', NULL, 15, 16, NULL, NULL, '2025-10-26 06:11:38', '2025-10-26 06:11:38'),
(14, 4, 'TASK-RCEDYVO9', 'Task 1 for CRM System', 'This is task 1 description for the project CRM System', 'completed', 4, '2025-10-09', '2025-12-02', NULL, 16, 6, NULL, NULL, '2025-10-26 06:11:38', '2025-10-26 06:11:38'),
(15, 4, 'TASK-SMZ2TY3F', 'Task 2 for CRM System', 'This is task 2 description for the project CRM System', 'on_hold', 3, '2025-10-02', '2025-11-22', NULL, 12, 18, NULL, NULL, '2025-10-26 06:11:38', '2025-10-26 06:11:38'),
(16, 4, 'TASK-E427MAZN', 'Task 3 for CRM System', 'This is task 3 description for the project CRM System', 'in_progress', 5, '2025-10-23', '2025-11-29', NULL, 33, 28, NULL, NULL, '2025-10-26 06:11:38', '2025-10-26 06:11:38'),
(17, 4, 'TASK-S7RMLXVQ', 'Task 4 for CRM System', 'This is task 4 description for the project CRM System', 'pending', 4, '2025-10-08', '2025-11-06', NULL, 2, 41, NULL, NULL, '2025-10-26 06:11:38', '2025-10-26 06:11:38'),
(18, 4, 'TASK-G4Q38ANQ', 'Task 5 for CRM System', 'This is task 5 description for the project CRM System', 'completed', 5, '2025-10-07', '2025-12-14', NULL, 29, 18, NULL, NULL, '2025-10-26 06:11:38', '2025-10-26 06:11:38'),
(19, 4, 'TASK-MXCYDXJM', 'Task 6 for CRM System', 'This is task 6 description for the project CRM System', 'pending', 5, '2025-10-01', '2025-12-21', NULL, 21, 30, NULL, NULL, '2025-10-26 06:11:38', '2025-10-26 06:11:38'),
(20, 4, 'TASK-QKSM8DN2', 'Task 7 for CRM System', 'This is task 7 description for the project CRM System', 'in_progress', 2, '2025-10-20', '2025-12-25', NULL, 9, 35, NULL, NULL, '2025-10-26 06:11:38', '2025-10-26 06:11:38'),
(21, 5, 'TASK-E6CTBX5K', 'Task 1 for API Integration', 'This is task 1 description for the project API Integration', 'on_hold', 1, '2025-10-19', '2025-11-11', NULL, 38, 39, NULL, NULL, '2025-10-26 06:11:38', '2025-10-26 06:11:38'),
(22, 5, 'TASK-JMEUGMQE', 'Task 2 for API Integration', 'This is task 2 description for the project API Integration', 'on_hold', 4, '2025-10-04', '2025-11-09', NULL, 6, 18, NULL, NULL, '2025-10-26 06:11:38', '2025-10-26 06:11:38'),
(23, 5, 'TASK-WOT6MYVW', 'Task 3 for API Integration', 'This is task 3 description for the project API Integration', 'on_hold', 5, '2025-10-04', '2025-11-28', NULL, 35, 44, NULL, NULL, '2025-10-26 06:11:38', '2025-10-26 06:11:38'),
(24, 5, 'TASK-MFZAWULA', 'Task 4 for API Integration', 'This is task 4 description for the project API Integration', 'in_progress', 1, '2025-10-16', '2025-11-08', NULL, 33, 2, NULL, NULL, '2025-10-26 06:11:38', '2025-10-26 06:11:38'),
(25, 6, 'TASK-HDZKFJEJ', 'Task 1 for Security Audit', 'This is task 1 description for the project Security Audit', 'pending', 2, '2025-10-24', '2025-11-02', NULL, 18, 15, NULL, NULL, '2025-10-26 06:11:38', '2025-10-26 06:11:38'),
(26, 6, 'TASK-VLS4TRE5', 'Task 2 for Security Audit', 'This is task 2 description for the project Security Audit', 'in_progress', 1, '2025-10-08', '2025-12-03', NULL, 20, 22, NULL, NULL, '2025-10-26 06:11:38', '2025-10-26 06:11:38'),
(27, 6, 'TASK-OEJLJ1UV', 'Task 3 for Security Audit', 'This is task 3 description for the project Security Audit', 'pending', 1, '2025-10-10', '2025-11-11', NULL, 6, 38, NULL, NULL, '2025-10-26 06:11:38', '2025-10-26 06:11:38'),
(28, 6, 'TASK-JQ60KAEU', 'Task 4 for Security Audit', 'This is task 4 description for the project Security Audit', 'completed', 2, '2025-10-03', '2025-12-05', NULL, 40, 15, NULL, NULL, '2025-10-26 06:11:38', '2025-10-26 06:11:38'),
(29, 6, 'TASK-9LRP5C62', 'Task 5 for Security Audit', 'This is task 5 description for the project Security Audit', 'in_progress', 4, '2025-10-02', '2025-10-28', NULL, 2, 30, NULL, NULL, '2025-10-26 06:11:38', '2025-10-26 06:11:38'),
(30, 6, 'TASK-Z7PGTJ65', 'Task 6 for Security Audit', 'This is task 6 description for the project Security Audit', 'in_progress', 5, '2025-09-28', '2025-11-15', NULL, 33, 20, NULL, NULL, '2025-10-26 06:11:38', '2025-10-26 06:11:38'),
(31, 6, 'TASK-R4YITAD3', 'Task 7 for Security Audit', 'This is task 7 description for the project Security Audit', 'completed', 4, '2025-09-28', '2025-11-09', NULL, 12, 7, NULL, NULL, '2025-10-26 06:11:38', '2025-10-26 06:11:38'),
(32, 6, 'TASK-OICPUTTO', 'Task 8 for Security Audit', 'This is task 8 description for the project Security Audit', 'in_progress', 3, '2025-10-04', '2025-12-20', NULL, 32, 27, NULL, NULL, '2025-10-26 06:11:38', '2025-10-26 06:11:38'),
(33, 7, 'TASK-ADZIOR6V', 'Task 1 for Data Migration', 'This is task 1 description for the project Data Migration', 'pending', 1, '2025-09-29', '2025-12-09', NULL, 4, 16, NULL, NULL, '2025-10-26 06:11:38', '2025-10-26 06:11:38'),
(34, 7, 'TASK-ZKIWKXMM', 'Task 2 for Data Migration', 'This is task 2 description for the project Data Migration', 'completed', 5, '2025-10-25', '2025-12-25', NULL, 34, 6, NULL, NULL, '2025-10-26 06:11:38', '2025-10-26 06:11:38'),
(35, 7, 'TASK-DNBKSSFA', 'Task 3 for Data Migration', 'This is task 3 description for the project Data Migration', 'in_progress', 4, '2025-10-21', '2025-12-18', NULL, 33, 13, NULL, NULL, '2025-10-26 06:11:38', '2025-10-26 06:11:38'),
(36, 7, 'TASK-CFXTITE3', 'Task 4 for Data Migration', 'This is task 4 description for the project Data Migration', 'in_progress', 4, '2025-10-12', '2025-11-27', NULL, 5, 15, NULL, NULL, '2025-10-26 06:11:38', '2025-10-26 06:11:38'),
(37, 7, 'TASK-IXWAVNWY', 'Task 5 for Data Migration', 'This is task 5 description for the project Data Migration', 'pending', 1, '2025-10-22', '2025-11-24', NULL, 6, 14, NULL, NULL, '2025-10-26 06:11:38', '2025-10-26 06:11:38'),
(38, 7, 'TASK-FSRB3MWH', 'Task 6 for Data Migration', 'This is task 6 description for the project Data Migration', 'pending', 3, '2025-10-13', '2025-12-07', NULL, 13, 23, NULL, NULL, '2025-10-26 06:11:38', '2025-10-26 06:11:38'),
(39, 7, 'TASK-KMQVXCYU', 'Task 7 for Data Migration', 'This is task 7 description for the project Data Migration', 'pending', 4, '2025-10-25', '2025-11-22', NULL, 29, 43, NULL, NULL, '2025-10-26 06:11:38', '2025-10-26 06:11:38'),
(40, 9, 'TASK-ALTHCGEG', 'Task 1 for X-card', 'This is task 1 description for the project X-card', 'in_progress', 5, '2025-10-06', '2025-12-02', NULL, 20, 47, NULL, NULL, '2025-10-26 06:11:38', '2025-10-26 06:11:38'),
(41, 9, 'TASK-6KVHQBMW', 'Task 2 for X-card', 'This is task 2 description for the project X-card', 'on_hold', 4, '2025-09-29', '2025-12-04', NULL, 33, 31, NULL, NULL, '2025-10-26 06:11:38', '2025-10-26 06:11:38'),
(42, 9, 'TASK-RTXRCPK1', 'Task 3 for X-card', 'This is task 3 description for the project X-card', 'pending', 5, '2025-10-01', '2025-11-05', NULL, 23, 23, NULL, NULL, '2025-10-26 06:11:38', '2025-10-26 06:11:38'),
(43, 9, 'TASK-1VWJDRZU', 'Task 4 for X-card', 'This is task 4 description for the project X-card', 'in_progress', 3, '2025-10-25', '2025-11-26', NULL, 8, 36, NULL, NULL, '2025-10-26 06:11:38', '2025-10-26 06:11:38'),
(44, 9, 'TASK-YCBXT5BB', 'Task 5 for X-card', 'This is task 5 description for the project X-card', 'in_progress', 1, '2025-09-26', '2025-11-18', NULL, 17, 50, NULL, NULL, '2025-10-26 06:11:38', '2025-10-26 06:11:38'),
(45, 9, 'TASK-LWRQKHFD', 'Task 6 for X-card', 'This is task 6 description for the project X-card', 'on_hold', 2, '2025-10-24', '2025-11-23', NULL, 6, 13, NULL, NULL, '2025-10-26 06:11:38', '2025-10-26 06:11:38');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `two_factor_secret` text DEFAULT NULL,
  `two_factor_recovery_codes` text DEFAULT NULL,
  `two_factor_confirmed_at` timestamp NULL DEFAULT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `email_verified_at`, `password`, `two_factor_secret`, `two_factor_recovery_codes`, `two_factor_confirmed_at`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'joyal', 'joyal@gmail.com', NULL, '$2y$12$wN.bIo9WhiJOlH6Lyjv5HeSKnJt5T2aEQpi71tEVsFZcWBSVKWMLW', NULL, NULL, NULL, NULL, '2025-10-21 09:47:09', '2025-10-21 09:47:09'),
(2, 'joyal123', 'joyal123@gmail.com', NULL, '$2y$12$J2iHMaj8RHVzseesNoiJDuc0PnYn0/wJhK161zTjvPAyBCefSOr5y', NULL, NULL, NULL, NULL, '2025-10-29 09:50:33', '2025-10-29 09:50:33'),
(3, 'test', 'test@gmail.com', NULL, '$2y$12$BH.vo7VkCpHT9m6TCJaLUejznxv4OMxUW8fbSX6wLoN20BbH6vdNC', NULL, NULL, NULL, NULL, '2025-10-29 09:51:29', '2025-10-29 09:51:29'),
(4, 'Admin User', 'admin@example.com', NULL, '$2y$12$qnQRn.Qv/l50FjVI81EKAeZ/oQXjzA2M./MBbEFH6xXcfOpQ1Od4m', NULL, NULL, NULL, NULL, '2025-10-30 11:49:56', '2025-10-30 11:49:56');

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
-- Indexes for table `companies`
--
ALTER TABLE `companies`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `companies_slug_unique` (`slug`);

--
-- Indexes for table `company_user`
--
ALTER TABLE `company_user`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `company_user_company_id_user_id_unique` (`company_id`,`user_id`),
  ADD KEY `company_user_user_id_foreign` (`user_id`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indexes for table `general_settings`
--
ALTER TABLE `general_settings`
  ADD PRIMARY KEY (`id`);

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
-- Indexes for table `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indexes for table `projects`
--
ALTER TABLE `projects`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `projects_project_code_unique` (`project_code`),
  ADD KEY `projects_company_id_foreign` (`company_id`),
  ADD KEY `projects_created_by_foreign` (`created_by`),
  ADD KEY `projects_type_created_by_index` (`type`,`created_by`),
  ADD KEY `projects_type_company_id_index` (`type`,`company_id`);

--
-- Indexes for table `project_user`
--
ALTER TABLE `project_user`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `project_user_project_id_user_id_unique` (`project_id`,`user_id`),
  ADD KEY `project_user_user_id_foreign` (`user_id`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Indexes for table `tasks`
--
ALTER TABLE `tasks`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `tasks_task_code_unique` (`task_code`),
  ADD KEY `tasks_project_id_task_status_index` (`project_id`,`task_status`),
  ADD KEY `tasks_priority_index` (`priority`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `companies`
--
ALTER TABLE `companies`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `company_user`
--
ALTER TABLE `company_user`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `general_settings`
--
ALTER TABLE `general_settings`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `projects`
--
ALTER TABLE `projects`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `project_user`
--
ALTER TABLE `project_user`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `tasks`
--
ALTER TABLE `tasks`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=50;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `company_user`
--
ALTER TABLE `company_user`
  ADD CONSTRAINT `company_user_company_id_foreign` FOREIGN KEY (`company_id`) REFERENCES `companies` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `company_user_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `projects`
--
ALTER TABLE `projects`
  ADD CONSTRAINT `projects_company_id_foreign` FOREIGN KEY (`company_id`) REFERENCES `companies` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `projects_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `project_user`
--
ALTER TABLE `project_user`
  ADD CONSTRAINT `project_user_project_id_foreign` FOREIGN KEY (`project_id`) REFERENCES `projects` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `project_user_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `tasks`
--
ALTER TABLE `tasks`
  ADD CONSTRAINT `tasks_project_id_foreign` FOREIGN KEY (`project_id`) REFERENCES `projects` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
