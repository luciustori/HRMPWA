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

-- Dumping structure for table mobile_db.users
CREATE TABLE IF NOT EXISTS `users` (
  `id` int NOT NULL AUTO_INCREMENT,
  `employee_id` int NOT NULL,
  `username` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `role` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT 'staff',
  `permissions` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin COMMENT 'Granular permissions per module',
  `is_active` tinyint(1) DEFAULT '1',
  `last_login` datetime DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `can_view_department_only` tinyint(1) DEFAULT '0' COMMENT 'Manager/Supervisor hanya bisa lihat department sendiri',
  PRIMARY KEY (`id`),
  UNIQUE KEY `employee_id` (`employee_id`),
  UNIQUE KEY `username` (`username`),
  KEY `idx_role` (`role`),
  KEY `idx_active` (`is_active`),
  CONSTRAINT `users_chk_1` CHECK (json_valid(`permissions`))
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table mobile_db.users: ~23 rows (approximately)
DELETE FROM `users`;
INSERT INTO `users` (`id`, `employee_id`, `username`, `password`, `email`, `role`, `permissions`, `is_active`, `last_login`, `created_at`, `updated_at`, `can_view_department_only`) VALUES
	(1, 1, 'A-10.001', '$2y$10$DacSz/888it4D.VVJ.DJAu.DBvoyUdROfoe6lIHzDrpW7P61z8X3a', NULL, 'super_admin', NULL, 1, '2026-01-24 09:23:34', '2026-01-23 19:19:56', '2026-02-10 01:29:22', 0),
	(5, 2, 'T-20.001', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', NULL, 'admin', NULL, 1, NULL, '2026-01-24 18:05:30', '2026-01-24 18:05:30', 0),
	(6, 3, 'T-21.003', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', NULL, 'staff', NULL, 1, NULL, '2026-01-24 18:05:30', '2026-01-27 05:01:11', 0),
	(7, 4, 'T-21.004', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', NULL, 'admin', NULL, 1, NULL, '2026-01-24 18:05:30', '2026-01-24 18:05:30', 0),
	(8, 5, 'T-22.005', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', NULL, 'staff', NULL, 1, NULL, '2026-01-24 18:05:30', '2026-01-24 18:05:30', 0),
	(9, 6, 'T-22.006', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', NULL, 'staff', NULL, 1, NULL, '2026-01-24 18:05:30', '2026-01-24 18:05:30', 0),
	(10, 7, 'T-23.010', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', NULL, 'staff', NULL, 1, NULL, '2026-01-24 18:05:30', '2026-01-24 18:05:30', 0),
	(11, 8, 'T-23.011', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', NULL, 'staff', NULL, 1, NULL, '2026-01-24 18:05:30', '2026-01-24 18:05:30', 0),
	(12, 9, 'T-25.012', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', NULL, 'staff', NULL, 1, NULL, '2026-01-24 18:05:30', '2026-01-24 18:05:30', 0),
	(13, 10, 'T-25.013', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', NULL, 'staff', NULL, 1, NULL, '2026-01-24 18:05:30', '2026-01-24 18:05:30', 0),
	(14, 11, 'K-25.060', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', NULL, 'staff', NULL, 1, NULL, '2026-01-24 18:05:30', '2026-01-24 18:05:30', 0),
	(15, 12, 'T-25.015', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', NULL, 'staff', NULL, 1, NULL, '2026-01-24 18:05:30', '2026-01-24 18:05:30', 0),
	(16, 13, 'T-20.002', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', NULL, 'admin', NULL, 1, NULL, '2026-01-24 18:05:30', '2026-01-24 18:05:30', 0),
	(17, 14, 'T-22.007', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', NULL, 'staff', NULL, 1, NULL, '2026-01-24 18:05:30', '2026-01-24 18:05:30', 0),
	(18, 15, 'T-22.008', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', NULL, 'staff', NULL, 1, NULL, '2026-01-24 18:05:30', '2026-01-24 18:05:30', 0),
	(19, 16, 'T-22.009', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', NULL, 'admin', NULL, 1, NULL, '2026-01-24 18:05:30', '2026-01-24 18:05:30', 0),
	(20, 17, 'T-25.014', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', NULL, 'staff', NULL, 1, NULL, '2026-01-24 18:05:30', '2026-01-24 18:05:30', 0),
	(21, 18, 'K-19.058', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', NULL, 'staff', NULL, 1, NULL, '2026-01-24 18:05:30', '2026-01-24 18:05:30', 0),
	(22, 19, 'K-25.059', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', NULL, 'staff', NULL, 1, NULL, '2026-01-24 18:05:30', '2026-01-24 18:05:30', 0),
	(23, 20, 'H-22.008', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', NULL, 'staff', NULL, 1, NULL, '2026-01-24 18:05:30', '2026-01-24 18:05:30', 0),
	(24, 21, 'H-22.009', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', NULL, 'staff', NULL, 1, NULL, '2026-01-24 18:05:30', '2026-01-24 18:05:30', 0),
	(25, 22, 'H-22.010', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', NULL, 'staff', NULL, 1, NULL, '2026-01-24 18:05:30', '2026-01-24 18:05:30', 0),
	(26, 23, 'H-22.011', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', NULL, 'staff', NULL, 1, NULL, '2026-01-24 18:05:30', '2026-01-24 18:05:30', 0),
	(27, 25, 'D-26.004', '$2y$10$MaGEe7R4i6PFImo3D24JL.QlKwYSczQ5VGQt20uc/0kBqAaCPCUky', NULL, 'super_admin', NULL, 1, NULL, '2026-04-02 10:05:47', '2026-04-02 10:05:47', 0);

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
