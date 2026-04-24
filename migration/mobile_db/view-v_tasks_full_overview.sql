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

-- Removing temporary table and create final VIEW structure
DROP TABLE IF EXISTS `v_tasks_full_overview`;
CREATE ALGORITHM=UNDEFINED SQL SECURITY DEFINER VIEW `v_tasks_full_overview` AS select 1 AS `id`,1 AS `task_code`,1 AS `title`,1 AS `description`,1 AS `priority`,1 AS `status`,1 AS `start_date`,1 AS `due_date`,1 AS `completion_percentage`,1 AS `estimated_hours`,1 AS `actual_hours`,1 AS `kpi_weight`,1 AS `category_name`,1 AS `category_color`,1 AS `category_icon`,1 AS `employee_number`,1 AS `department_name`,1 AS `days_remaining`,1 AS `urgency_status`,1 AS `comment_count`,1 AS `attachment_count`,1 AS `total_checklist`,1 AS `completed_checklist`,1 AS `tags`,1 AS `created_at`,1 AS `updated_at`
;

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
