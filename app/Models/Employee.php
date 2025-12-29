<?php
// app/Models/Employee.php

namespace App\Models;

use PDO;

class Employee extends BaseModel {
    protected $table = 'employees';

    public function allWithRelations() {
        $sql = "SELECT e.*, 
                       d.name as department_name,
                       l.name as level_name,
                       u.email as user_email
                FROM {$this->table} e
                LEFT JOIN departments d ON e.department_id = d.id
                LEFT JOIN employee_levels l ON e.level_id = l.id
                LEFT JOIN users u ON e.user_id = u.id
                WHERE e.company_id = :company_id
                ORDER BY e.created_at DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':company_id' => $_SESSION['company_id'] ?? 1]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function create(array $data) {
        // 1. Create user account
        $userModel = new User();
        $userId = $userModel->createEmployee($data['email'], $data['employee_number']);

        // 2. Create employee record
        $sql = "INSERT INTO {$this->table} 
                (company_id, user_id, employee_number, full_name, nik, 
                 gender, date_of_birth, phone, email, address, 
                 department_id, level_id, position, hire_date, 
                 annual_leave_quota, base_salary)
                VALUES 
                (:company_id, :user_id, :employee_number, :full_name, :nik,
                 :gender, :date_of_birth, :phone, :email, :address,
                 :department_id, :level_id, :position, :hire_date,
                 :annual_leave_quota, :base_salary)";

        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':company_id'           => $_SESSION['company_id'] ?? 1,
            ':user_id'              => $userId,
            ':employee_number'      => $data['employee_number'],
            ':full_name'            => $data['full_name'],
            ':nik'                  => $data['nik'] ?? null,
            ':gender'               => $data['gender'] ?? null,
            ':date_of_birth'        => $data['date_of_birth'] ?? null,
            ':phone'                => $data['phone'] ?? null,
            ':email'                => $data['email'],
            ':address'              => $data['address'] ?? null,
            ':department_id'        => $data['department_id'] ?? null,
            ':level_id'             => $data['level_id'] ?? null,
            ':position'             => $data['position'] ?? null,
            ':hire_date'            => $data['hire_date'] ?? null,
            ':annual_leave_quota'   => $data['annual_leave_quota'] ?? 12,
            ':base_salary'          => $data['base_salary'] ?? 0,
        ]);
    }

    public function update($id, array $data) {
        $sql = "UPDATE {$this->table} SET
                full_name = :full_name,
                nik = :nik,
                phone = :phone,
                address = :address,
                department_id = :department_id,
                level_id = :level_id,
                position = :position,
                base_salary = :base_salary
                WHERE id = :id";

        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':id'               => $id,
            ':full_name'        => $data['full_name'],
            ':nik'              => $data['nik'] ?? null,
            ':phone'            => $data['phone'] ?? null,
            ':address'          => $data['address'] ?? null,
            ':department_id'    => $data['department_id'] ?? null,
            ':level_id'         => $data['level_id'] ?? null,
            ':position'         => $data['position'] ?? null,
            ':base_salary'      => $data['base_salary'] ?? 0,
        ]);
    }
}
?>
