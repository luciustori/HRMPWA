<?php
// app/Models/Company.php

namespace App\Models;

use PDO;

class Company extends BaseModel {
    protected $table = 'companies';

    public function getFirst() {
        return $this->find(1);
    }

    public function save(array $data) {
        if (!empty($data['id'])) {
            return $this->update($data);
        }
        return $this->create($data);
    }

    private function create(array $data) {
        $sql = "INSERT INTO {$this->table} (name, logo_path, address, phone, email)
                VALUES (:name, :logo_path, :address, :phone, :email)";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':name'       => $data['name'] ?? null,
            ':logo_path'  => $data['logo_path'] ?? null,
            ':address'    => $data['address'] ?? null,
            ':phone'      => $data['phone'] ?? null,
            ':email'      => $data['email'] ?? null,
        ]);
    }

    private function update(array $data) {
        $sql = "UPDATE {$this->table} SET 
                name = :name, 
                logo_path = :logo_path,
                address = :address,
                phone = :phone,
                email = :email
                WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':id'         => $data['id'],
            ':name'       => $data['name'] ?? null,
            ':logo_path'  => $data['logo_path'] ?? null,
            ':address'    => $data['address'] ?? null,
            ':phone'      => $data['phone'] ?? null,
            ':email'      => $data['email'] ?? null,
        ]);
    }
}
?>
