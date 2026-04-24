-- --------------------------------------------------------
-- Host:                         127.0.0.1
-- Server version:               8.4.3 - MySQL Community Server - GPL
-- Server OS:                    Win64
-- HeidiSQL Version:             12.15.0.7171
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

-- Dumping structure for table mobile_db.tasks
CREATE TABLE IF NOT EXISTS `tasks` (
  `id` int NOT NULL AUTO_INCREMENT,
  `task_code` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Auto-generated: TSK-2026-001',
  `category_id` int DEFAULT NULL,
  `title` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `assigned_by` int NOT NULL COMMENT 'Manager/Supervisor user_id',
  `assigned_to` int NOT NULL COMMENT 'Employee user_id',
  `department_id` int DEFAULT NULL,
  `priority` enum('low','medium','high','urgent') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT 'medium',
  `status` enum('pending','in_progress','submitted','review','completed','overdue','cancelled') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT 'pending',
  `start_date` date NOT NULL,
  `due_date` date NOT NULL,
  `completed_at` datetime DEFAULT NULL,
  `estimated_hours` decimal(5,2) DEFAULT '0.00',
  `actual_hours` decimal(5,2) DEFAULT '0.00',
  `completion_percentage` int DEFAULT '0' COMMENT '0-100',
  `kpi_weight` decimal(5,2) DEFAULT '0.00' COMMENT 'Weight for KPI calculation (0-100)',
  `quality_score` int DEFAULT NULL COMMENT 'Quality rating 0-100',
  `notes` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `attachment` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `requires_approval` tinyint(1) DEFAULT '1' COMMENT 'Butuh approval manager',
  `approval_status` enum('pending','submitted','approved','rejected','revision') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT 'pending',
  `approved_by` int DEFAULT NULL COMMENT 'Manager user_id',
  `approved_at` datetime DEFAULT NULL,
  `approval_notes` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci COMMENT 'Feedback dari manager',
  `submitted_for_approval_at` datetime DEFAULT NULL,
  `kpi_score_final` decimal(5,2) DEFAULT NULL COMMENT 'Final score setelah di-approve',
  `is_billable` tinyint(1) DEFAULT '0' COMMENT 'Task billable ke client',
  `tags` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Comma-separated tag IDs',
  `is_recurring` tinyint(1) DEFAULT '0' COMMENT '1=Ya, 0=Tidak',
  `recurrence_type` enum('none','daily','weekly','monthly','yearly') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT 'none',
  `recurrence_day` int DEFAULT NULL COMMENT 'Tanggal pengulangan (misal: 25)',
  `last_generated_at` date DEFAULT NULL COMMENT 'Kapan terakhir digenerate otomatis',
  PRIMARY KEY (`id`),
  UNIQUE KEY `task_code` (`task_code`),
  KEY `assigned_by` (`assigned_by`),
  KEY `assigned_to` (`assigned_to`),
  KEY `category_id` (`category_id`),
  KEY `department_id` (`department_id`),
  KEY `status` (`status`),
  KEY `due_date` (`due_date`),
  KEY `idx_employee_status` (`assigned_to`,`status`),
  KEY `idx_task_status_due` (`status`,`due_date`),
  KEY `idx_task_assigned_status` (`assigned_to`,`status`),
  KEY `idx_task_priority_status` (`priority`,`status`),
  KEY `idx_approval_status` (`approval_status`),
  KEY `idx_approved_by` (`approved_by`),
  KEY `idx_kpi_final` (`kpi_score_final`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table mobile_db.tasks: ~9 rows (approximately)
DELETE FROM `tasks`;
INSERT INTO `tasks` (`id`, `task_code`, `category_id`, `title`, `description`, `assigned_by`, `assigned_to`, `department_id`, `priority`, `status`, `start_date`, `due_date`, `completed_at`, `estimated_hours`, `actual_hours`, `completion_percentage`, `kpi_weight`, `quality_score`, `notes`, `attachment`, `created_at`, `updated_at`, `requires_approval`, `approval_status`, `approved_by`, `approved_at`, `approval_notes`, `submitted_for_approval_at`, `kpi_score_final`, `is_billable`, `tags`, `is_recurring`, `recurrence_type`, `recurrence_day`, `last_generated_at`) VALUES
	(1, 'TSK-2026-001', 4, 'Penagihan Tenan', 'Penagihan Invoice Listrik, Air dan Service', 1, 5, 1, 'high', 'in_progress', '2026-01-05', '2026-01-09', NULL, 0.00, 1.00, 0, 10.00, NULL, '', NULL, '2026-01-02 14:59:05', '2026-01-27 05:31:45', 1, 'submitted', NULL, NULL, NULL, '2026-01-14 11:27:49', NULL, 0, NULL, 0, 'none', NULL, NULL),
	(2, 'TSK-2026-002', 5, 'Laporan Progress Mingguan', 'buat Grand Report untuk meeting mingguan', 1, 1, 1, 'medium', 'in_progress', '2026-01-05', '2026-01-09', NULL, 16.00, 0.00, 0, 10.00, NULL, NULL, NULL, '2026-01-08 18:35:20', '2026-02-15 14:57:33', 1, 'pending', NULL, NULL, NULL, NULL, NULL, 0, NULL, 0, 'none', NULL, NULL),
	(3, 'TSK-2026-003', 7, 'Internal Meeting', 'Membahas tentang piknik akhir bulan ini', 1, 1, 1, 'medium', 'completed', '2026-01-09', '2026-01-09', '2026-02-09 21:33:50', 0.00, 0.00, 100, 5.00, NULL, NULL, NULL, '2026-01-08 18:49:13', '2026-02-09 14:33:50', 1, 'pending', NULL, NULL, NULL, NULL, NULL, 0, NULL, 0, 'none', NULL, NULL),
	(5, 'TSK-2026-004', 5, 'coba notif', 'coba notif aja', 1, 10, NULL, 'medium', 'review', '2026-01-19', '2026-01-22', '2026-01-12 16:50:36', 8.00, 7.00, 0, 10.00, NULL, NULL, NULL, '2026-01-12 02:09:05', '2026-01-30 19:53:50', 1, 'submitted', NULL, NULL, NULL, '2026-01-15 05:20:28', NULL, 0, NULL, 0, 'none', NULL, NULL),
	(6, 'TSK-2026-005', 5, 'coba notif', 'coba notif aja', 1, 8, NULL, 'medium', 'completed', '2026-01-19', '2026-01-22', '2026-01-14 02:43:28', 8.00, 0.00, 0, 10.00, NULL, NULL, NULL, '2026-01-12 02:14:37', '2026-02-06 08:28:14', 1, 'pending', NULL, NULL, NULL, NULL, NULL, 0, NULL, 0, 'none', NULL, NULL),
	(7, 'TSK-2026-006', 5, 'coba notif', 'coba notif aja', 1, 9, NULL, 'medium', 'completed', '2026-01-19', '2026-01-22', '2026-01-27 02:01:56', 8.00, 2.00, 0, 10.00, NULL, NULL, NULL, '2026-01-12 02:16:18', '2026-01-27 23:43:05', 1, 'submitted', NULL, NULL, NULL, '2026-01-15 05:22:03', NULL, 0, NULL, 0, 'none', NULL, NULL),
	(8, 'TSK-2026-007', 8, 'task 1', 'coba input task pertama yang lengkap', 5, 18, NULL, 'medium', 'completed', '2026-01-27', '2026-01-27', NULL, 1.00, 0.00, 0, 1.00, NULL, NULL, NULL, '2026-01-26 14:30:14', '2026-01-31 21:34:43', 1, 'pending', NULL, NULL, NULL, NULL, NULL, 0, NULL, 0, 'none', NULL, NULL),
	(9, 'TSK-2026-008', 6, 'Invoicing Service Tenant', 'Catat semua meter listrik dan air yang digunakan oleh tenant', 5, 18, NULL, 'medium', 'completed', '2026-01-01', '2026-01-10', NULL, 5.00, 17.00, 0, 10.00, NULL, NULL, NULL, '2026-01-27 22:52:56', '2026-02-02 11:57:41', 1, 'pending', NULL, NULL, NULL, NULL, NULL, 0, NULL, 1, 'monthly', 10, NULL),
	(10, 'TSK-2026-009', 7, 'Persiapan Panitia Seleksi Direktur PT. Jogjatama Vishesha', 'Persiapan acara cari ', 1, 16, NULL, 'high', 'completed', '2026-02-01', '2026-02-13', '2026-02-10 16:03:55', 15.00, 5.00, 100, 10.00, NULL, NULL, NULL, '2026-02-09 12:22:49', '2026-02-15 13:29:09', 1, 'pending', NULL, NULL, NULL, NULL, NULL, 0, NULL, 0, 'none', NULL, NULL),
	(11, 'TSK-2026-010', 1, 'Trial Test SIXTY ', 'Trial dan Test Sistem SIXTY hingga tuntas. \r\nCatat jika ada error dan ketidaksesuaian dengan yang dibutuhkan !', 1, 16, NULL, 'high', 'pending', '2026-02-18', '2026-02-20', NULL, 190.00, 0.00, 0, 10.00, NULL, NULL, NULL, '2026-02-16 03:20:17', '2026-02-16 03:20:17', 1, 'pending', NULL, NULL, NULL, NULL, NULL, 0, NULL, 0, 'none', NULL, NULL);

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
