<?php
// app/Models/Shift.php

namespace App\Models;

use PDO;

class Shift extends BaseModel {
    protected $table = 'shifts';

    public function allWithMOD() {
        $sql = "SELECT * FROM {$this->table} 
                WHERE company_id = :company_id
                ORDER BY start_time";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':company_id' => $_SESSION['company_id'] ?? 1]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function save(array $data) {
        if (!empty($data['id'])) {
            $sql = "UPDATE {$this->table} SET
                    name = :name,
                    start_time = :start_time,
                    end_time = :end_time,
                    work_hours = :work_hours,
                    is_mod = :is_mod,
                    mod_bonus = :mod_bonus,
                    description = :description
                    WHERE id = :id";
        } else {
            $sql = "INSERT INTO {$this->table} 
                    (company_id, name, start_time, end_time, work_hours, is_mod, mod_bonus, description)
                    VALUES 
                    (:company_id, :name, :start_time, :end_time, :work_hours, :is_mod, :mod_bonus, :description)";
        }

        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':id'           => $data['id'] ?? null,
            ':company_id'   => $_SESSION['company_id'] ?? 1,
            ':name'         => $data['name'],
            ':start_time'   => $data['start_time'],
            ':end_time'     => $data['end_time'],
            ':work_hours'   => $data['work_hours'] ?? 8,
            ':is_mod'       => !empty($data['is_mod']) ? 1 : 0,
            ':mod_bonus'    => $data['mod_bonus'] ?? 100000,
            ':description'  => $data['description'] ?? null,
        ]);
    }
}
?>
