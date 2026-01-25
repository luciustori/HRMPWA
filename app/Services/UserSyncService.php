<?php
// File: app/Services/UserSyncService.php

require_once '../app/Core/Database.php';

class UserSyncService {
    private $db;

    public function __construct() {
        $this->db = new Database;
    }

    public function syncAllEmployees() {
        // 1. Cari karyawan yang STATUS-nya ACTIVE tapi BELUM punya akun di tabel USERS
        // Kita join users berdasarkan employee_id
        $sql = "
            SELECT 
                e.id,
                e.employee_number,
                e.first_name,
                e.last_name
            FROM employees e
            LEFT JOIN users u ON e.id = u.employee_id
            WHERE e.employee_status = 'active'
            AND u.id IS NULL
        ";

        $this->db->query($sql);
        $candidates = $this->db->resultSet();

        if (empty($candidates)) {
            return [
                'status' => 'info',
                'message' => 'Semua karyawan aktif sudah memiliki akun login.'
            ];
        }

        $inserted = 0;
        $defaultPassword = 'password123'; // Password Default
        $hashedPassword = password_hash($defaultPassword, PASSWORD_BCRYPT);

        foreach ($candidates as $emp) {
            // 2. Insert ke tabel USERS
            // Rule: Username = Employee Number (Sesuai request Anda)
            // Role: 'employee' (Default)
            
            $queryInsert = "INSERT INTO users 
                            (employee_id, username, password, role, is_active, created_at) 
                            VALUES 
                            (:emp_id, :username, :password, 'employee', 1, NOW())";
            
            $this->db->query($queryInsert);
            $this->db->bind(':emp_id', $emp['id']);
            $this->db->bind(':username', $emp['employee_number']);
            $this->db->bind(':password', $hashedPassword);

            if ($this->db->execute()) {
                $inserted++;
            }
        }

        return [
            'status' => 'success',
            'message' => "Berhasil generate akun untuk $inserted karyawan. Password default: $defaultPassword"
        ];
    }
}