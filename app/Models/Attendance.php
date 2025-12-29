<?php
// app/Models/Attendance.php

namespace App\Models;

use PDO;
use DateTime;

class Attendance extends BaseModel {
    protected $table = 'attendances';

    public function getTodayStatus($employeeId) {
        $today = date('Y-m-d');
        $sql = "SELECT * FROM {$this->table}
                WHERE employee_id = :employee_id AND DATE(check_in) = :today
                LIMIT 1";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            ':employee_id' => $employeeId,
            ':today'       => $today,
        ]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function checkIn($employeeId, $lat, $lng, $photo_path = null) {
        // 1. Hitung keterlambatan
        $lateness = $this->calculateLateness($employeeId);

        $sql = "INSERT INTO {$this->table}
                (employee_id, check_in, latitude_in, longitude_in, photo_in, status, lateness_minutes)
                VALUES
                (:employee_id, NOW(), :lat, :lng, :photo, 'checked_in', :lateness)";

        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':employee_id' => $employeeId,
            ':lat'         => $lat,
            ':lng'         => $lng,
            ':photo'       => $photo_path,
            ':lateness'    => $lateness,
        ]) ? $this->getTodayStatus($employeeId) : false;
    }

    public function checkOut($employeeId, $lat, $lng, $photo_path = null) {
        $today = date('Y-m-d');
        $sql = "UPDATE {$this->table} SET
                check_out = NOW(),
                latitude_out = :lat,
                longitude_out = :lng,
                photo_out = :photo,
                status = 'completed'
                WHERE employee_id = :employee_id AND DATE(check_in) = :today";

        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':employee_id' => $employeeId,
            ':lat'         => $lat,
            ':lng'         => $lng,
            ':photo'       => $photo_path,
            ':today'       => $today,
        ]);
    }

    private function calculateLateness($employeeId) {
        // Ambil shift karyawan hari ini
        $sql = "SELECT s.start_time
                FROM employee_shifts es
                JOIN shifts s ON es.shift_id = s.id
                WHERE es.employee_id = :employee_id
                AND es.shift_date = CURDATE()
                LIMIT 1";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([':employee_id' => $employeeId]);
        $shift = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$shift) return 0;

        $shiftTime = new DateTime($shift['start_time']);
        $now = new DateTime();

        $diff = $now->getTimestamp() - $shiftTime->getTimestamp();
        return max(0, intdiv($diff, 60)); // Minutes late
    }

    public function getMonthly($employeeId, $year, $month) {
        $sql = "SELECT * FROM {$this->table}
                WHERE employee_id = :employee_id
                AND YEAR(check_in) = :year
                AND MONTH(check_in) = :month
                ORDER BY check_in DESC";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            ':employee_id' => $employeeId,
            ':year'        => $year,
            ':month'       => $month,
        ]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getStats($employeeId, $year, $month) {
        $sql = "SELECT
                    COUNT(*) as total_days,
                    SUM(CASE WHEN status = 'completed' THEN 1 ELSE 0 END) as present_days,
                    SUM(CASE WHEN status = 'absent' THEN 1 ELSE 0 END) as absent_days,
                    SUM(CASE WHEN lateness_minutes > 0 THEN 1 ELSE 0 END) as late_days,
                    SUM(lateness_minutes) as total_lateness
                FROM {$this->table}
                WHERE employee_id = :employee_id
                AND YEAR(check_in) = :year
                AND MONTH(check_in) = :month";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            ':employee_id' => $employeeId,
            ':year'        => $year,
            ':month'       => $month,
        ]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
?>
