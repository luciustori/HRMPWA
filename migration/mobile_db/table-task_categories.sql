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

-- Dumping structure for table mobile_db.task_categories
CREATE TABLE IF NOT EXISTS `task_categories` (
  `id` int NOT NULL AUTO_INCREMENT,
  `category_name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `category_code` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `color` varchar(7) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT '#3B82F6' COMMENT 'Hex color for UI',
  `icon` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT 'briefcase' COMMENT 'Icon identifier',
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `is_active` tinyint(1) DEFAULT '1',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `category_code` (`category_code`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table mobile_db.task_categories: ~8 rows (approximately)
DELETE FROM `task_categories`;
INSERT INTO `task_categories` (`id`, `category_name`, `category_code`, `color`, `icon`, `description`, `is_active`, `created_at`) VALUES
	(1, 'Development', 'DEV', '#10B981', 'code', 'Software development tasks', 1, '2026-01-02 14:32:43'),
	(2, 'Design', 'DES', '#8B5CF6', 'palette', 'UI/UX design tasks', 1, '2026-01-02 14:32:43'),
	(3, 'Marketing', 'MKT', '#F59E0B', 'megaphone', 'Marketing and promotion', 1, '2026-01-02 14:32:43'),
	(4, 'Sales', 'SAL', '#EF4444', 'trending-up', 'Sales activities', 1, '2026-01-02 14:32:43'),
	(5, 'Support', 'SUP', '#3B82F6', 'headphones', 'Customer support', 1, '2026-01-02 14:32:43'),
	(6, 'Admin', 'ADM', '#6B7280', 'clipboard', 'Administrative tasks', 1, '2026-01-02 14:32:43'),
	(7, 'Meeting', 'MTG', '#EC4899', 'users', 'Meetings and discussions', 1, '2026-01-02 14:32:43'),
	(8, 'Research', 'RES', '#14B8A6', 'search', 'Research and analysis', 1, '2026-01-02 14:32:43');

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
