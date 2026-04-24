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

-- Dumping structure for trigger mobile_db.notify_task_update
SET @OLDTMP_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO';
DELIMITER //
CREATE TRIGGER `notify_task_update` AFTER UPDATE ON `tasks` FOR EACH ROW BEGIN

    

    IF OLD.due_date != NEW.due_date THEN

        INSERT INTO notifications (

            employee_id, 

            type, 

            title, 

            message, 

            reference_id, 

            reference_table, 

            icon, 

            priority

        )

        VALUES (

            NEW.assigned_to,

            'task',

            'Deadline Berubah â°',

            CONCAT('Deadline tugas "', NEW.title, '" diubah menjadi ', DATE_FORMAT(NEW.due_date, '%d %b %Y')),

            NEW.id,

            'tasks',

            'calendar-alt',

            'high'

        );

    END IF;

    

    

    IF OLD.priority != NEW.priority THEN

        INSERT INTO notifications (

            employee_id, 

            type, 

            title, 

            message, 

            reference_id, 

            reference_table, 

            icon, 

            priority

        )

        VALUES (

            NEW.assigned_to,

            'task',

            'Prioritas Berubah ?',

            CONCAT('Prioritas tugas "', NEW.title, '" diubah menjadi ', UPPER(NEW.priority)),

            NEW.id,

            'tasks',

            'exclamation-triangle',

            'high'

        );

    END IF;

END//
DELIMITER ;
SET SQL_MODE=@OLDTMP_SQL_MODE;

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
