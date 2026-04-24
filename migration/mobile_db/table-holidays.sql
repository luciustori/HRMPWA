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

-- Dumping structure for table mobile_db.holidays
CREATE TABLE IF NOT EXISTS `holidays` (
  `id` int NOT NULL AUTO_INCREMENT,
  `company_id` int NOT NULL DEFAULT '1',
  `holiday_date` date NOT NULL,
  `holiday_name` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `holiday_type` enum('national','company','religious') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT 'national',
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `is_active` tinyint(1) DEFAULT '1',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `holiday_year` int GENERATED ALWAYS AS (year(`holiday_date`)) STORED,
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_holiday` (`company_id`,`holiday_date`),
  KEY `company_id` (`company_id`),
  KEY `holiday_date` (`holiday_date`),
  KEY `holiday_type` (`holiday_type`),
  KEY `idx_holiday_date` (`holiday_date`),
  KEY `idx_holiday_year` (`holiday_year`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table mobile_db.holidays: ~27 rows (approximately)
DELETE FROM `holidays`;
INSERT INTO `holidays` (`id`, `company_id`, `holiday_date`, `holiday_name`, `holiday_type`, `description`, `is_active`, `created_at`, `updated_at`) VALUES
	(1, 1, '2026-01-01', 'Tahun Baru 2026', 'national', NULL, 1, '2025-12-31 13:27:26', '2025-12-31 13:27:26'),
	(2, 1, '2026-03-22', 'Isra Miraj Nabi Muhammad SAW', 'religious', NULL, 1, '2025-12-31 13:27:26', '2025-12-31 13:27:26'),
	(4, 1, '2026-04-03', 'Wafat Isa Al-Masih', 'religious', NULL, 1, '2025-12-31 13:27:26', '2025-12-31 13:27:26'),
	(7, 1, '2026-05-01', 'Hari Buruh Internasional', 'national', NULL, 1, '2025-12-31 13:27:26', '2025-12-31 13:27:26'),
	(8, 1, '2026-05-14', 'Kenaikan Isa Al-Masih', 'religious', NULL, 1, '2025-12-31 13:27:26', '2025-12-31 13:27:26'),
	(9, 1, '2026-05-21', 'Hari Raya Waisak', 'religious', NULL, 1, '2025-12-31 13:27:26', '2025-12-31 13:27:26'),
	(10, 1, '2026-06-01', 'Hari Lahir Pancasila', 'national', NULL, 1, '2025-12-31 13:27:26', '2025-12-31 13:27:26'),
	(11, 1, '2026-06-11', 'Idul Adha 1447 H', 'religious', NULL, 1, '2025-12-31 13:27:26', '2025-12-31 13:27:26'),
	(12, 1, '2026-07-01', 'Tahun Baru Islam 1448 H', 'religious', NULL, 1, '2025-12-31 13:27:26', '2025-12-31 13:27:26'),
	(13, 1, '2026-08-17', 'Hari Kemerdekaan RI', 'national', NULL, 1, '2025-12-31 13:27:26', '2025-12-31 13:27:26'),
	(15, 1, '2026-12-25', 'Hari Raya Natal', 'religious', NULL, 1, '2025-12-31 13:27:26', '2025-12-31 13:27:26'),
	(16, 1, '2026-02-17', 'Tahun Baru Imlek 2577 Kongzili', 'religious', '', 1, '2025-12-31 14:01:09', '2025-12-31 14:01:09'),
	(17, 1, '2026-01-16', 'Isra Mi\'raj Nabi Muhammad SAW', 'religious', '', 0, '2025-12-31 14:01:45', '2026-02-11 06:01:55'),
	(18, 1, '2026-12-22', 'HUT PERUSAHAAN', 'company', 'HUT PERUSAHAAN', 1, '2026-02-11 06:04:20', '2026-02-11 06:04:20'),
	(19, 1, '2026-02-16', 'Tahun Baru Imlek 2577 Kongzili', 'national', 'Libur Nasional (Auto Sync API)', 1, '2026-03-25 05:49:13', '2026-03-25 05:49:13'),
	(20, 1, '2026-03-18', 'Hari Suci Nyepi (Tahun Baru Saka 1948)', 'national', 'Libur Nasional (Auto Sync API)', 1, '2026-03-25 05:49:13', '2026-03-25 05:49:13'),
	(21, 1, '2026-03-19', 'Hari Suci Nyepi (Tahun Baru Saka 1948)', 'national', 'Libur Nasional (Auto Sync API)', 1, '2026-03-25 05:49:13', '2026-03-25 05:49:13'),
	(22, 1, '2026-03-20', 'Idul Fitri 1447 Hijriah', 'national', 'Libur Nasional (Auto Sync API)', 1, '2026-03-25 05:49:13', '2026-03-25 05:49:13'),
	(23, 1, '2026-03-21', 'Idul Fitri 1447 Hijriah', 'national', 'Libur Nasional (Auto Sync API)', 1, '2026-03-25 05:49:13', '2026-03-25 05:49:13'),
	(24, 1, '2026-03-23', 'Idul Fitri 1447 Hijriah', 'national', 'Libur Nasional (Auto Sync API)', 1, '2026-03-25 05:49:13', '2026-03-25 05:49:13'),
	(25, 1, '2026-03-24', 'Idul Fitri 1447 Hijriah', 'national', 'Libur Nasional (Auto Sync API)', 1, '2026-03-25 05:49:13', '2026-03-25 05:49:13'),
	(26, 1, '2026-05-15', 'Kenaikan Yesus Kristus', 'national', 'Libur Nasional (Auto Sync API)', 1, '2026-03-25 05:49:13', '2026-03-25 05:49:13'),
	(27, 1, '2026-05-27', 'Idul Adha 1447 Hijriah', 'national', 'Libur Nasional (Auto Sync API)', 1, '2026-03-25 05:49:13', '2026-03-25 05:49:13'),
	(28, 1, '2026-05-28', 'Idul Adha 1447 Hijriah', 'national', 'Libur Nasional (Auto Sync API)', 1, '2026-03-25 05:49:13', '2026-03-25 05:49:13'),
	(29, 1, '2026-05-31', 'Hari Raya Waisak 2570 BE', 'national', 'Libur Nasional (Auto Sync API)', 1, '2026-03-25 05:49:13', '2026-03-25 05:49:13'),
	(30, 1, '2026-06-16', '1 Muharam Tahun Baru Islam 1448 Hijriah', 'national', 'Libur Nasional (Auto Sync API)', 1, '2026-03-25 05:49:13', '2026-03-25 05:49:13'),
	(31, 1, '2026-08-25', 'Maulid Nabi Muhammad S.A.W.', 'national', 'Libur Nasional (Auto Sync API)', 1, '2026-03-25 05:49:13', '2026-03-25 05:49:13');

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
