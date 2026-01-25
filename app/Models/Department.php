<?php
// File: app/Models/Department.php

class Department {
    private $db;

    public function __construct() {
        $this->db = new Database;
    }

    // UPDATE: Ambil data departemen + Jml Karyawan + Nama Manajer
    public function getAllWithStats() {
        // Kita join 2 kali ke tabel employees:
        // 1. Join 'e' untuk hitung jumlah anak buah (GROUP BY)
        // 2. Join 'm' (Manager) untuk ambil nama si bos
        $sql = "SELECT d.*, 
                       COUNT(e.id) as employee_count,
                       CONCAT(m.first_name, ' ', COALESCE(m.last_name, '')) as manager_name
                FROM departments d 
                LEFT JOIN employees e ON d.id = e.department_id 
                LEFT JOIN employees m ON d.manager_id = m.id
                GROUP BY d.id 
                ORDER BY d.department_name ASC";
        
        $this->db->query($sql);
        return $this->db->resultSet();
    }

    public function getDashboardStats() {
        $this->db->query("SELECT COUNT(*) as total FROM departments");
        $totalDept = $this->db->single()['total'];

        $sql = "SELECT d.department_name, COUNT(e.id) as total 
                FROM departments d 
                JOIN employees e ON d.id = e.department_id 
                GROUP BY d.id 
                ORDER BY total DESC LIMIT 1";
        $this->db->query($sql);
        $biggest = $this->db->single();

        return [
            'total_dept' => $totalDept,
            'biggest_dept' => $biggest['department_name'] ?? '-',
            'biggest_count' => $biggest['total'] ?? 0
        ];
    }

    public function getById($id) {
        $this->db->query("SELECT * FROM departments WHERE id = :id");
        $this->db->bind(':id', $id);
        return $this->db->single();
    }

    // UPDATE: Simpan manager_id
    public function create($data) {
        $sql = "INSERT INTO departments (company_id, department_code, department_name, manager_id) 
                VALUES (1, :code, :name, :mgr)";
        
        $this->db->query($sql);
        $this->db->bind(':code', strtoupper($data['department_code']));
        $this->db->bind(':name', $data['department_name']);
        // Jika manager_id kosong, set NULL
        $this->db->bind(':mgr', !empty($data['manager_id']) ? $data['manager_id'] : null);
        return $this->db->execute();
    }

    // UPDATE: Update manager_id
    public function update($id, $data) {
        $sql = "UPDATE departments 
                SET department_code = :code, 
                    department_name = :name, 
                    manager_id = :mgr 
                WHERE id = :id";
        
        $this->db->query($sql);
        $this->db->bind(':code', strtoupper($data['department_code']));
        $this->db->bind(':name', $data['department_name']);
        $this->db->bind(':mgr', !empty($data['manager_id']) ? $data['manager_id'] : null);
        $this->db->bind(':id', $id);
        return $this->db->execute();
    }

    public function delete($id) {
        $this->db->query("SELECT COUNT(*) as total FROM employees WHERE department_id = :id");
        $this->db->bind(':id', $id);
        $check = $this->db->single();
        if ($check['total'] > 0) return false;

        $this->db->query("DELETE FROM departments WHERE id = :id");
        $this->db->bind(':id', $id);
        return $this->db->execute();
    }
}