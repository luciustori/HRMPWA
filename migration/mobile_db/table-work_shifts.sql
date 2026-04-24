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

-- Dumping structure for table mobile_db.work_shifts
CREATE TABLE IF NOT EXISTS `work_shifts` (
  `id` int NOT NULL AUTO_INCREMENT,
  `company_id` int NOT NULL DEFAULT '1',
  `shift_name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `shift_code` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `start_time` time NOT NULL,
  `end_time` time NOT NULL,
  `late_tolerance_minutes` int DEFAULT '10',
  `total_hours` decimal(4,2) GENERATED ALWAYS AS ((timestampdiff(MINUTE,`start_time`,`end_time`) / 60)) STORED,
  `is_active` tinyint(1) DEFAULT '1',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_shift_code` (`company_id`,`shift_code`),
  KEY `company_id` (`company_id`),
  KEY `is_active` (`is_active`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table mobile_db.work_shifts: ~4 rows (approximately)
DELETE FROM `work_shifts`;
INSERT INTO `work_shifts` (`id`, `company_id`, `shift_name`, `shift_code`, `start_time`, `end_time`, `late_tolerance_minutes`, `is_active`, `created_at`, `updated_at`) VALUES
	(1, 1, 'Shift Pagi', 'PAGI', '06:00:00', '14:00:00', 10, 1, '2025-12-31 13:27:26', '2025-12-31 14:02:35'),
	(2, 1, 'Shift Siang', 'SIANG', '12:00:00', '20:00:00', 10, 1, '2025-12-31 13:27:26', '2025-12-31 13:27:26'),
	(3, 1, 'Shift Malam', 'MALAM', '20:00:00', '04:00:00', 10, 1, '2025-12-31 13:27:26', '2025-12-31 13:27:26'),
	(4, 1, 'Shift Full Day', 'FULL', '08:00:00', '17:00:00', 15, 1, '2025-12-31 13:27:26', '2026-02-13 11:05:03'),
	(5, 1, 'COBA', 'TRY', '10:00:00', '19:00:00', 15, 1, '2026-03-25 05:19:20', '2026-03-25 05:19:20');

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
