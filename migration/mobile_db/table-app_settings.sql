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

-- Dumping structure for table mobile_db.app_settings
CREATE TABLE IF NOT EXISTS `app_settings` (
  `id` int NOT NULL AUTO_INCREMENT,
  `setting_key` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `setting_value` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `setting_type` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT 'text',
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_key` (`setting_key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table mobile_db.app_settings: ~13 rows (approximately)
DELETE FROM `app_settings`;
INSERT INTO `app_settings` (`id`, `setting_key`, `setting_value`, `setting_type`, `updated_at`) VALUES
	(1, 'app_name', 'HRIS XT', 'text', '2026-02-11 22:38:11'),
	(2, 'app_logo', 'uploads/settings/app_logo_1770782158.png', 'image', '2026-02-11 03:55:58'),
	(3, 'app_tagline', 'Management', 'text', '2026-02-11 22:38:11'),
	(4, 'company_name', 'PT. Maju Jaya Abadi', 'text', '2026-02-11 22:38:11'),
	(5, 'primary_color', '#3f46a6', 'color', '2026-02-11 22:38:11'),
	(6, 'support_email', 'support@company.com', 'email', '2026-02-11 19:54:13'),
	(7, 'company_address', 'Jl. Jendral Sudirman No. 1, Jakarta', 'text', '2026-02-11 22:38:11'),
	(8, 'company_phone', '0812-3456-7890', 'text', '2026-02-11 22:38:11'),
	(9, 'company_logo', 'uploads/settings/logo_company_1770849491.png', 'image', '2026-02-11 22:38:11'),
	(10, 'payslip_format', 'modern', 'text', '2026-02-11 22:38:11'),
	(11, 'late_tolerance', '5', 'number', '2026-02-11 22:38:11'),
	(12, 'company_email', '', 'text', '2026-02-11 22:38:11'),
	(13, 'company_website', '', 'text', '2026-02-11 22:38:11');

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
