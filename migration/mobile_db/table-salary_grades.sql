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

-- Dumping structure for table mobile_db.salary_grades
CREATE TABLE IF NOT EXISTS `salary_grades` (
  `id` int NOT NULL AUTO_INCREMENT,
  `grade_code` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `grade_name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `base_salary` decimal(15,2) DEFAULT '0.00',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `grade_code` (`grade_code`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table mobile_db.salary_grades: ~6 rows (approximately)
DELETE FROM `salary_grades`;
INSERT INTO `salary_grades` (`id`, `grade_code`, `grade_name`, `description`, `base_salary`, `created_at`) VALUES
	(1, 'I', 'NON STAFF', 'Harian / Lepas', 700000.00, '2026-01-26 09:19:13'),
	(2, 'II', 'STAFF', 'Karyawan Tetap Staff', 0.00, '2026-01-26 09:19:13'),
	(3, 'III', 'SENIOR STAFF', 'Karyawan Senior / Team Lead', 0.00, '2026-01-26 09:19:13'),
	(4, 'IV', 'SUPERVISOR', 'Penyelia', 0.00, '2026-01-26 09:19:13'),
	(5, 'V', 'DEPT MANAGER', 'Manajer Departemen', 0.00, '2026-01-26 09:19:13'),
	(6, 'VI', 'DIRECTOR', 'Direktur Perusahaan', 0.00, '2026-01-26 09:19:13');

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
