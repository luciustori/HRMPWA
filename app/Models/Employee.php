<?php
// File: app/Models/Employee.php

class Employee {
    private $db;

    public function __construct() {
        $this->db = new Database;
    }

    // 1. GET ALL (Untuk Halaman List)
    public function getAllEmployees() {
        // Query Join Lengkap
        $sql = "SELECT e.*, 
                       d.department_name, 
                       d.department_code,
                       u.username as has_account
                FROM employees e
                LEFT JOIN departments d ON e.department_id = d.id
                LEFT JOIN users u ON e.id = u.employee_id
                ORDER BY e.first_name ASC";
        
        $this->db->query($sql);
        return $this->db->resultSet();
    }

    // 2. GET BY ID (Untuk Halaman Edit)
    public function getById($id) {
        $this->db->query("SELECT * FROM employees WHERE id = :id");
        $this->db->bind(':id', $id);
        return $this->db->single();
    }

    // 3. GET DEPARTMENTS (Untuk Dropdown)
    public function getAllDepartments() {
        $this->db->query("SELECT * FROM departments ORDER BY department_name ASC");
        return $this->db->resultSet();
    }

    // 4. CREATE (Simpan Data Baru - Full Field)
    public function create($data) {
        $sql = "INSERT INTO employees 
                (company_id, employee_number, first_name, last_name, 
                 gender, place_of_birth, date_of_birth, marital_status, number_of_dependents, identity_number,
                 email, phone, address,
                 department_id, position, employee_level, employee_status, hire_date,
                 bank_name, bank_account_number, bank_account_name, npwp,
                 is_active)
                VALUES 
                (1, :nik, :fname, :lname, 
                 :gender, :pob, :dob, :marital, :dependents, :ktp,
                 :email, :phone, :address,
                 :dept, :position, :level, :status, :hire,
                 :bank_name, :bank_no, :bank_acc, :npwp,
                 1)";
        
        $this->db->query($sql);
        $this->bindParams($data); // Panggil helper binding

        return $this->db->execute();
    }

    // 5. UPDATE (Simpan Perubahan - Full Field)
    public function update($id, $data) {
        $sql = "UPDATE employees SET 
                employee_number = :nik,
                first_name = :fname,
                last_name = :lname,
                gender = :gender,
                place_of_birth = :pob,
                date_of_birth = :dob,
                marital_status = :marital,
                number_of_dependents = :dependents,
                identity_number = :ktp,
                email = :email,
                phone = :phone,
                address = :address,
                department_id = :dept,
                position = :position,
                employee_level = :level,
                employee_status = :status,
                hire_date = :hire,
                bank_name = :bank_name,
                bank_account_number = :bank_no,
                bank_account_name = :bank_acc,
                npwp = :npwp
                WHERE id = :id";
        
        $this->db->query($sql);
        $this->bindParams($data);
        $this->db->bind(':id', $id);

        return $this->db->execute();
    }

    // 6. DELETE (Hapus Data)
    public function delete($id) {
        // Hapus user login dulu
        $this->db->query("DELETE FROM users WHERE employee_id = :id");
        $this->db->bind(':id', $id);
        $this->db->execute();

        // Hapus karyawan
        $this->db->query("DELETE FROM employees WHERE id = :id");
        $this->db->bind(':id', $id);
        return $this->db->execute();
    }

    // HELPER: Binding Parameter (Agar tidak nulis ulang)
    private function bindParams($data) {
        $this->db->bind(':nik', strtoupper($data['employee_number']));
        $this->db->bind(':fname', $data['first_name']);
        $this->db->bind(':lname', $data['last_name']);
        $this->db->bind(':gender', $data['gender']);
        $this->db->bind(':pob', $data['place_of_birth'] ?? null);
        $this->db->bind(':dob', !empty($data['date_of_birth']) ? $data['date_of_birth'] : null);
        $this->db->bind(':marital', $data['marital_status']);
        $this->db->bind(':dependents', $data['number_of_dependents'] ?? 0);
        $this->db->bind(':ktp', $data['identity_number']);
        $this->db->bind(':email', $data['email']);
        $this->db->bind(':phone', $data['phone']);
        $this->db->bind(':address', $data['address']);
        $this->db->bind(':dept', $data['department_id']);
        $this->db->bind(':position', $data['position']);
        $this->db->bind(':level', $data['employee_level']);
        $this->db->bind(':status', $data['employee_status']);
        $this->db->bind(':hire', !empty($data['hire_date']) ? $data['hire_date'] : null);
        $this->db->bind(':bank_name', $data['bank_name']);
        $this->db->bind(':bank_no', $data['bank_account_number']);
        $this->db->bind(':bank_acc', $data['bank_account_name']);
        $this->db->bind(':npwp', $data['npwp']);
    }
    // Tambahkan ini di dalam class Employee (app/Models/Employee.php)

    // STATISTIK SIMPLE
    public function getStats() {
        $stats = [
            'total' => 0,
            'active' => 0,
            'inactive' => 0
        ];

        // Total
        $this->db->query("SELECT COUNT(*) as count FROM employees");
        $stats['total'] = $this->db->single()['count'];

        // Active
        $this->db->query("SELECT COUNT(*) as count FROM employees WHERE employee_status = 'active'");
        $stats['active'] = $this->db->single()['count'];

        // Inactive (Non-Aktif / Suspended)
        $this->db->query("SELECT COUNT(*) as count FROM employees WHERE employee_status != 'active'");
        $stats['inactive'] = $this->db->single()['count'];

        return $stats;
    }
}