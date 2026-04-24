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

-- Dumping structure for table mobile_db.pwa_auth
CREATE TABLE IF NOT EXISTS `pwa_auth` (
  `id` int NOT NULL AUTO_INCREMENT,
  `employee_number` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Username untuk login (NIP)',
  `password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Hashed password',
  `last_login` datetime DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT '1' COMMENT '1 = Aktif, 0 = Suspended',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_employee_number` (`employee_number`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='PWA Login Credentials';

-- Dumping data for table mobile_db.pwa_auth: ~7 rows (approximately)
DELETE FROM `pwa_auth`;
INSERT INTO `pwa_auth` (`id`, `employee_number`, `password`, `last_login`, `is_active`, `created_at`, `updated_at`) VALUES
	(1, 'EMP001', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '2026-01-14 18:36:58', 1, '2026-01-07 12:53:15', '2026-01-14 04:36:58'),
	(2, 'EMP002', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '2026-01-08 23:48:47', 1, '2026-01-07 12:53:15', '2026-01-08 09:48:47'),
	(3, 'EMP003', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', NULL, 1, '2026-01-07 12:53:15', '2026-01-07 12:53:15'),
	(9, 'EMP004', '$2y$10$wETOGfUPiquJE6r7mGqKCeubbS6s79j7.X9ZCCC1.WVoc8W5Tftxe', NULL, 1, '2026-01-10 00:44:22', '2026-01-10 00:44:22'),
	(10, 'EMP005', '$2y$10$wETOGfUPiquJE6r7mGqKCeubbS6s79j7.X9ZCCC1.WVoc8W5Tftxe', '2026-01-10 18:51:51', 1, '2026-01-10 00:44:22', '2026-01-10 04:51:51'),
	(11, 'T202020', '$2y$10$wETOGfUPiquJE6r7mGqKCeubbS6s79j7.X9ZCCC1.WVoc8W5Tftxe', NULL, 1, '2026-01-10 00:44:22', '2026-01-10 00:44:22'),
	(12, 'T20002', '$2y$10$wETOGfUPiquJE6r7mGqKCeubbS6s79j7.X9ZCCC1.WVoc8W5Tftxe', '2026-01-10 14:45:05', 1, '2026-01-10 00:44:22', '2026-01-10 00:45:05');

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
