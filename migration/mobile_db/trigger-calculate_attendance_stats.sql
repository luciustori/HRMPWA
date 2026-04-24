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

-- Dumping structure for trigger mobile_db.calculate_attendance_stats
SET @OLDTMP_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO';
DELIMITER //
CREATE TRIGGER `calculate_attendance_stats` BEFORE UPDATE ON `attendance_records` FOR EACH ROW BEGIN

    DECLARE shift_start TIME;

    DECLARE shift_end TIME;

    DECLARE shift_tolerance INT;



    

    IF NEW.check_out_time IS NOT NULL AND OLD.check_out_time IS NULL THEN



        

        SELECT start_time, end_time, late_tolerance_minutes 

        INTO shift_start, shift_end, shift_tolerance

        FROM work_shifts 

        WHERE id = NEW.shift_id;



        IF shift_start IS NOT NULL THEN

            

            IF TIME(NEW.check_in_time) > ADDTIME(shift_start, SEC_TO_TIME(shift_tolerance * 60)) THEN

                SET NEW.is_late = 1;

                SET NEW.late_duration_minutes = TIMESTAMPDIFF(MINUTE, 

                    CONCAT(NEW.attendance_date, ' ', shift_start), 

                    NEW.check_in_time

                );

            END IF;



            

            IF TIME(NEW.check_out_time) < shift_end THEN

                SET NEW.is_early_out = 1;

                SET NEW.early_out_minutes = TIMESTAMPDIFF(MINUTE, 

                    NEW.check_out_time,

                    CONCAT(NEW.attendance_date, ' ', shift_end)

                );

            END IF;



            

            SET NEW.work_duration_minutes = TIMESTAMPDIFF(MINUTE, NEW.check_in_time, NEW.check_out_time);



            

            IF TIME(NEW.check_out_time) > shift_end THEN

                SET NEW.overtime_minutes = TIMESTAMPDIFF(MINUTE, 

                    CONCAT(NEW.attendance_date, ' ', shift_end),

                    NEW.check_out_time

                );

            END IF;



            

            IF NEW.is_late = 1 OR NEW.is_early_out = 1 THEN

                SET NEW.requires_approval = 1;

                SET NEW.approval_status = 'pending';

            END IF;

        END IF;

    END IF;

END//
DELIMITER ;
SET SQL_MODE=@OLDTMP_SQL_MODE;

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
