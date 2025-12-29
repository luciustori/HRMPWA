<?php
// app/Models/Department.php

namespace App\Models;

use PDO;

class Department extends BaseModel {
    protected $table = 'departments';

    public function allWithManager() {
        $sql = "SELECT d.*, u.full_name as manager_name
                FROM {$this->table} d
                LEFT JOIN employees u ON d.manager_id = u.id
                WHERE d.company_id = :company_id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':company_id' => $_SESSION['company_id'] ?? 1]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function save(array $data) {
        if (!empty($data['id'])) {
            $sql = "UPDATE {$this->table} SET 
                    name = :name,
                    manager_id = :manager_id,
                    description = :description
                    WHERE id = :id";
        } else {
            $sql = "INSERT INTO {$this->table} (company_id, name, manager_id, description)
                    VALUES (:company_id, :name, :manager_id, :description)";
        }

        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':id'           => $data['id'] ?? null,
            ':company_id'   => $_SESSION['company_id'] ?? 1,
            ':name'         => $data['name'],
            ':manager_id'   => $data['manager_id'] ?? null,
            ':description'  => $data['description'] ?? null,
        ]);
    }

    public function delete($id) {
        return $this->db->prepare("DELETE FROM {$this->table} WHERE id = :id")
                       ->execute([':id' => $id]);
    }
}
?>
