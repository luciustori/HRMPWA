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

-- Dumping structure for table mobile_db.task_tags
CREATE TABLE IF NOT EXISTS `task_tags` (
  `id` int NOT NULL AUTO_INCREMENT,
  `tag_name` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `tag_slug` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `tag_color` varchar(7) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT '#64748B',
  `tag_icon` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT 'fa-tag',
  `description` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT '1',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `tag_slug` (`tag_slug`),
  KEY `is_active` (`is_active`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Tags for flexible task categorization';

-- Dumping data for table mobile_db.task_tags: ~10 rows (approximately)
DELETE FROM `task_tags`;
INSERT INTO `task_tags` (`id`, `tag_name`, `tag_slug`, `tag_color`, `tag_icon`, `description`, `is_active`, `created_at`) VALUES
	(1, 'Urgent', 'urgent', '#EF4444', 'fa-fire', 'Tugas yang sangat mendesak', 1, '2026-01-10 05:10:31'),
	(2, 'Bug Fix', 'bug-fix', '#F59E0B', 'fa-bug', 'Perbaikan bug atau error', 1, '2026-01-10 05:10:31'),
	(3, 'Feature', 'feature', '#10B981', 'fa-sparkles', 'Fitur baru', 1, '2026-01-10 05:10:31'),
	(4, 'Documentation', 'documentation', '#3B82F6', 'fa-book', 'Dokumentasi dan tutorial', 1, '2026-01-10 05:10:31'),
	(5, 'Testing', 'testing', '#8B5CF6', 'fa-vial', 'Testing dan QA', 1, '2026-01-10 05:10:31'),
	(6, 'Client Request', 'client-request', '#EC4899', 'fa-user-tie', 'Permintaan dari client', 1, '2026-01-10 05:10:31'),
	(7, 'Backend', 'backend', '#6366F1', 'fa-server', 'Backend development', 1, '2026-01-10 05:10:31'),
	(8, 'Frontend', 'frontend', '#14B8A6', 'fa-desktop', 'Frontend development', 1, '2026-01-10 05:10:31'),
	(9, 'Database', 'database', '#F97316', 'fa-database', 'Database work', 1, '2026-01-10 05:10:31'),
	(10, 'Optimization', 'optimization', '#84CC16', 'fa-tachometer-alt', 'Performance optimization', 1, '2026-01-10 05:10:31');

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
