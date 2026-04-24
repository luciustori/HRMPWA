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

-- Dumping structure for trigger mobile_db.update_leave_balance_on_approval
SET @OLDTMP_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO';
DELIMITER //
CREATE TRIGGER `update_leave_balance_on_approval` AFTER UPDATE ON `leave_requests` FOR EACH ROW BEGIN

    

    IF OLD.status = 'pending' AND NEW.status = 'approved' THEN

        

        INSERT INTO employee_leave_balance 

        (employee_id, leave_type_id, year, total_used, total_remaining)

        VALUES 

        (NEW.employee_id, NEW.leave_type_id, YEAR(NEW.start_date), NEW.total_days, 0)

        ON DUPLICATE KEY UPDATE

            total_used = total_used + NEW.total_days,

            total_remaining = total_entitled - (total_used + NEW.total_days);

    END IF;



    

    IF OLD.status = 'approved' AND (NEW.status = 'cancelled' OR NEW.status = 'rejected') THEN

        UPDATE employee_leave_balance

        SET total_used = total_used - NEW.total_days,

            total_remaining = total_remaining + NEW.total_days

        WHERE employee_id = NEW.employee_id 

          AND leave_type_id = NEW.leave_type_id 

          AND year = YEAR(NEW.start_date);

    END IF;

END//
DELIMITER ;
SET SQL_MODE=@OLDTMP_SQL_MODE;

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
