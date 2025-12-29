<?php
// app/Models/SalaryComponent.php

namespace App\Models;

use PDO;

class SalaryComponent extends BaseModel {
    protected $table = 'salary_components';

    public function allByType($type) {
        $sql = "SELECT * FROM {$this->table}
                WHERE company_id = :company_id AND component_type = :type
                ORDER BY created_at DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            ':company_id' => $_SESSION['company_id'] ?? 1,
            ':type'       => $type,
        ]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function save(array $data) {
        if (!empty($data['id'])) {
            $sql = "UPDATE {$this->table} SET
                    name = :name,
                    component_type = :type,
                    is_fixed = :is_fixed,
                    percentage = :percentage,
                    amount = :amount,
                    apply_to_levels = :levels
                    WHERE id = :id";
        } else {
            $sql = "INSERT INTO {$this->table}
                    (company_id, name, component_type, is_fixed, percentage, amount, apply_to_levels)
                    VALUES
                    (:company_id, :name, :type, :is_fixed, :percentage, :amount, :levels)";
        }

        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':id'         => $data['id'] ?? null,
            ':company_id' => $_SESSION['company_id'] ?? 1,
            ':name'       => $data['name'],
            ':type'       => $data['component_type'],
            ':is_fixed'   => !empty($data['is_fixed']) ? 1 : 0,
            ':percentage' => $data['percentage'] ?? null,
            ':amount'     => $data['amount'] ?? null,
            ':levels'     => implode(',', $data['apply_to_levels'] ?? []),
        ]);
    }
}
?>
