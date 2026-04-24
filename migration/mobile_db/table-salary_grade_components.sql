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

-- Dumping structure for table mobile_db.salary_grade_components
CREATE TABLE IF NOT EXISTS `salary_grade_components` (
  `id` int NOT NULL AUTO_INCREMENT,
  `grade_id` int NOT NULL,
  `component_id` int NOT NULL,
  `amount` decimal(15,2) DEFAULT '0.00',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_grade_comp` (`grade_id`,`component_id`),
  KEY `component_id` (`component_id`),
  CONSTRAINT `salary_grade_components_ibfk_1` FOREIGN KEY (`grade_id`) REFERENCES `salary_grades` (`id`) ON DELETE CASCADE,
  CONSTRAINT `salary_grade_components_ibfk_2` FOREIGN KEY (`component_id`) REFERENCES `payroll_components` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table mobile_db.salary_grade_components: ~6 rows (approximately)
DELETE FROM `salary_grade_components`;
INSERT INTO `salary_grade_components` (`id`, `grade_id`, `component_id`, `amount`, `created_at`) VALUES
	(2, 1, 2, 200000.00, '2026-01-26 11:03:34'),
	(3, 1, 5, 200000.00, '2026-01-26 11:03:39'),
	(4, 1, 6, 200000.00, '2026-01-26 11:04:10'),
	(5, 1, 4, 50000.00, '2026-01-26 11:04:23'),
	(6, 1, 3, 400000.00, '2026-01-26 11:04:31'),
	(7, 2, 1, 2500000.00, '2026-02-02 06:19:36');

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
