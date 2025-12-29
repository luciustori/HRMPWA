<?php
// app/Models/Permission.php

namespace App\Models;

use PDO;

class Permission extends BaseModel {
    protected $table = 'permissions';

    public function allByEmployee($employeeId) {
        $sql = "SELECT * FROM {$this->table}
                WHERE employee_id = :employee_id
                ORDER BY start_date DESC";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([':employee_id' => $employeeId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function create(array $data) {
        $sql = "INSERT INTO {$this->table}
                (employee_id, permission_type, start_date, end_date, reason, attachment, status)
                VALUES
                (:employee_id, :type, :start, :end, :reason, :attachment, 'pending')";

        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':employee_id' => $data['employee_id'],
            ':type'        => $data['permission_type'], // 'ijin', 'cuti', 'lembur', 'sakit'
            ':start'       => $data['start_date'],
            ':end'         => $data['end_date'],
            ':reason'      => $data['reason'],
            ':attachment'  => $data['attachment'] ?? null,
        ]);
    }

    public function countPending($employeeId) {
        $sql = "SELECT COUNT(*) as total FROM {$this->table}
                WHERE employee_id = :employee_id AND status = 'pending'";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([':employee_id' => $employeeId]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['total'] ?? 0;
    }
}
?>
