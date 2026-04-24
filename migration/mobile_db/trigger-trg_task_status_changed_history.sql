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

-- Dumping structure for trigger mobile_db.trg_task_status_changed_history
SET @OLDTMP_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO';
DELIMITER //
CREATE TRIGGER `trg_task_status_changed_history` AFTER UPDATE ON `tasks` FOR EACH ROW BEGIN

    

    IF OLD.status != NEW.status THEN

        INSERT INTO task_history (

            task_id, 

            user_id, 

            action_type, 

            field_changed, 

            old_value, 

            new_value, 

            description

        ) VALUES (

            NEW.id,

            NEW.assigned_by,

            'status_changed',

            'status',

            OLD.status,

            NEW.status,

            CONCAT('Status: ', OLD.status, ' â†’ ', NEW.status)

        );

    END IF;

    

    

    IF OLD.priority != NEW.priority THEN

        INSERT INTO task_history (

            task_id, 

            user_id, 

            action_type, 

            field_changed, 

            old_value, 

            new_value, 

            description

        ) VALUES (

            NEW.id,

            NEW.assigned_by,

            'priority_changed',

            'priority',

            OLD.priority,

            NEW.priority,

            CONCAT('Priority: ', OLD.priority, ' â†’ ', NEW.priority)

        );

    END IF;

    

    

    IF OLD.due_date != NEW.due_date THEN

        INSERT INTO task_history (

            task_id, 

            user_id, 

            action_type, 

            field_changed, 

            old_value, 

            new_value, 

            description

        ) VALUES (

            NEW.id,

            NEW.assigned_by,

            'deadline_changed',

            'due_date',

            OLD.due_date,

            NEW.due_date,

            CONCAT('Deadline: ', OLD.due_date, ' â†’ ', NEW.due_date)

        );

    END IF;

    

    

    IF OLD.assigned_to != NEW.assigned_to THEN

        INSERT INTO task_history (

            task_id, 

            user_id, 

            action_type, 

            field_changed, 

            old_value, 

            new_value, 

            description

        ) VALUES (

            NEW.id,

            NEW.assigned_by,

            'reassigned',

            'assigned_to',

            OLD.assigned_to,

            NEW.assigned_to,

            CONCAT('Task reassigned: ', OLD.assigned_to, ' â†’ ', NEW.assigned_to)

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
