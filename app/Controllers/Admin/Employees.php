<?php
// File: app/Controllers/Admin/Employees.php

class Employees extends Controller {

    private $db;

    public function __construct() {
        parent::__construct(); 

        if (!session_id()) session_start();

        // 1. CEK LOGIN
        if (!isset($_SESSION['user_id'])) {
            header('Location: ' . BASEURL . '/Admin/LoginController');
            exit;
        }

        // 2. CEK ROLE
        $role = strtolower($_SESSION['role'] ?? '');
        if ($role !== 'admin' && $role !== 'super_admin') {
            header('Location: ' . BASEURL . '/staff/dashboard');
            exit;
        }

        $this->db = new Database;
    }

    // --- INDEX: LIST KARYAWAN & STATS ---
    public function index() {
        // 1. QUERY UTAMA
        $query = "SELECT e.*, 
                         d.department_name, 
                         divi.division_name, 
                         sg.grade_name, 
                         sg.grade_code,
                         u.username as account_username 
                  FROM employees e
                  LEFT JOIN departments d ON e.department_id = d.id
                  LEFT JOIN divisions divi ON e.division_id = divi.id
                  LEFT JOIN salary_grades sg ON e.salary_grade_id = sg.id
                  LEFT JOIN users u ON e.id = u.employee_id
                  ORDER BY e.first_name ASC";

        $this->db->query($query);
        $employees = $this->db->resultSet();

        // 2. HITUNG STATISTIK (FIX Error Undefined Variable)
        $stats = [
            'total' => count($employees),
            'active' => 0,
            'inactive' => 0
        ];

        // Loop untuk hitung status & flag akun (FIX Error has_account)
        foreach($employees as $k => $v) {
             if($v['is_active'] == 1) {
                 $stats['active']++; 
             } else {
                 $stats['inactive']++;
             }
             
             // Flagging akun login untuk tampilan tabel
             $employees[$k]['has_account'] = !empty($v['account_username']);
        }

        $data = [
            'title' => 'Direktori Karyawan', 
            'content_view' => 'admin/employees/index', 
            'employees' => $employees, 
            'stats' => $stats // Kirim variabel stats ke view
        ];
        $this->view('admin/layouts/admin-layout', $data);
    }

    // --- HELPER AJAX DIVISI ---
    public function get_divisions($dept_id) {
        header('Content-Type: application/json');
        $this->db->query("SELECT id, division_name FROM divisions WHERE department_id = :dept_id AND is_active = 1 ORDER BY division_name ASC");
        $this->db->bind(':dept_id', $dept_id);
        echo json_encode($this->db->resultSet());
        exit;
    }

    // --- CREATE FORM ---
    public function create() {
        $this->db->query("SELECT * FROM departments WHERE is_active = 1 ORDER BY department_name ASC");
        $departments = $this->db->resultSet();

        $this->db->query("SELECT * FROM salary_grades ORDER BY id ASC");
        $grades = $this->db->resultSet();

        $data = [
            'title' => 'Tambah Karyawan',
            'content_view' => 'admin/employees/create',
            'departments' => $departments,
            'grades' => $grades
        ];
        $this->view('admin/layouts/admin-layout', $data);
    }

    // --- STORE PROSES ---
    public function store() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $salary = preg_replace('/[^0-9]/', '', $_POST['salary'] ?? '0');
            
            $query = "INSERT INTO employees (
                company_id, employee_number, first_name, last_name,
                identity_number, gender, marital_status, number_of_dependents, date_of_birth,
                email, phone, address,
                department_id, division_id, position, employee_level, salary_grade_id, employee_status, hire_date, salary,
                bank_name, bank_account_number, bank_account_name, npwp,
                is_active
            ) VALUES (
                1, :nik, :fname, :lname,
                :ktp, :gender, :marital, :child, :dob,
                :email, :phone, :addr,
                :dept, :division, :pos, :level, :grade, :status_emp, :hire, :salary,
                :bank, :rek, :rek_name, :npwp,
                :active
            )";
            
            $this->db->query($query);
            
            $this->db->bind(':nik', $_POST['employee_number']);
            $this->db->bind(':fname', $_POST['first_name']);
            $this->db->bind(':lname', $_POST['last_name']);
            $this->db->bind(':ktp', $_POST['identity_number']);
            $this->db->bind(':gender', $_POST['gender']);
            $this->db->bind(':marital', $_POST['marital_status']);
            $this->db->bind(':child', $_POST['number_of_dependents'] ?? 0);
            $this->db->bind(':dob', $_POST['date_of_birth']);
            $this->db->bind(':email', $_POST['email']);
            $this->db->bind(':phone', $_POST['phone']);
            $this->db->bind(':addr', $_POST['address']);
            $this->db->bind(':dept', !empty($_POST['department_id']) ? $_POST['department_id'] : null);
            $this->db->bind(':division', !empty($_POST['division_id']) ? $_POST['division_id'] : null);
            $this->db->bind(':pos', $_POST['position']);
            $this->db->bind(':level', $_POST['employee_level']);
            $this->db->bind(':grade', !empty($_POST['salary_grade_id']) ? $_POST['salary_grade_id'] : null);
            $this->db->bind(':status_emp', 'active');
            $this->db->bind(':hire', $_POST['hire_date']);
            $this->db->bind(':salary', $salary);
            $this->db->bind(':bank', $_POST['bank_name']);
            $this->db->bind(':rek', $_POST['bank_account_number']);
            $this->db->bind(':rek_name', $_POST['bank_account_name']);
            $this->db->bind(':npwp', $_POST['npwp']);
            $this->db->bind(':active', 1);
            
            if ($this->db->execute()) {
                header('Location: ' . BASEURL . '/admin/employees?success=created');
                exit;
            } else {
                die("Gagal menyimpan data.");
            }
        }
    }

    // --- EDIT FORM ---
    public function edit($id) {
        $this->db->query("SELECT * FROM employees WHERE id = :id");
        $this->db->bind(':id', $id);
        $emp = $this->db->single();
        
        if(!$emp) { header('Location: ' . BASEURL . '/admin/employees'); exit; }

        $this->db->query("SELECT * FROM departments WHERE is_active = 1");
        $departments = $this->db->resultSet();

        // Ambil Divisi berdasarkan departemen karyawan saat ini
        $divisions = [];
        if($emp['department_id']) {
            $this->db->query("SELECT * FROM divisions WHERE department_id = :did AND is_active = 1");
            $this->db->bind(':did', $emp['department_id']);
            $divisions = $this->db->resultSet();
        }

        $this->db->query("SELECT * FROM salary_grades ORDER BY id ASC");
        $grades = $this->db->resultSet();

        $data = [
            'title' => 'Edit Karyawan',
            'content_view' => 'admin/employees/edit',
            'employee' => $emp,
            'departments' => $departments,
            'divisions' => $divisions,
            'grades' => $grades
        ];
        $this->view('admin/layouts/admin-layout', $data);
    }

    // --- UPDATE PROSES ---
    public function update($id) {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $salary = preg_replace('/[^0-9]/', '', $_POST['salary'] ?? '0');
            
            $query = "UPDATE employees SET
                employee_number = :nik, first_name = :fname, last_name = :lname,
                identity_number = :ktp, gender = :gender, marital_status = :marital, 
                number_of_dependents = :child, date_of_birth = :dob,
                email = :email, phone = :phone, address = :addr,
                department_id = :dept, division_id = :division, position = :pos, 
                employee_level = :level, salary_grade_id = :grade, hire_date = :hire, salary = :salary,
                bank_name = :bank, bank_account_number = :rek, 
                bank_account_name = :rek_name, npwp = :npwp,
                is_active = :active
            WHERE id = :id";
            
            $this->db->query($query);
            $this->db->bind(':id', $id);
            $this->db->bind(':nik', $_POST['employee_number']);
            $this->db->bind(':fname', $_POST['first_name']);
            $this->db->bind(':lname', $_POST['last_name']);
            $this->db->bind(':ktp', $_POST['identity_number']);
            $this->db->bind(':gender', $_POST['gender']);
            $this->db->bind(':marital', $_POST['marital_status']);
            $this->db->bind(':child', $_POST['number_of_dependents'] ?? 0);
            $this->db->bind(':dob', $_POST['date_of_birth']);
            $this->db->bind(':email', $_POST['email']);
            $this->db->bind(':phone', $_POST['phone']);
            $this->db->bind(':addr', $_POST['address']);
            $this->db->bind(':dept', !empty($_POST['department_id']) ? $_POST['department_id'] : null);
            $this->db->bind(':division', !empty($_POST['division_id']) ? $_POST['division_id'] : null);
            $this->db->bind(':pos', $_POST['position']);
            $this->db->bind(':level', $_POST['employee_level']);
            $this->db->bind(':grade', !empty($_POST['salary_grade_id']) ? $_POST['salary_grade_id'] : null);
            $this->db->bind(':hire', $_POST['hire_date']);
            $this->db->bind(':salary', $salary);
            $this->db->bind(':bank', $_POST['bank_name']);
            $this->db->bind(':rek', $_POST['bank_account_number']);
            $this->db->bind(':rek_name', $_POST['bank_account_name']);
            $this->db->bind(':npwp', $_POST['npwp']);
            $this->db->bind(':active', $_POST['is_active']);
            
            if ($this->db->execute()) {
                header('Location: ' . BASEURL . '/admin/employees?success=updated');
                exit;
            } else {
                die("Gagal update data.");
            }
        }
    }

    public function delete($id) {
        $this->db->query("DELETE FROM users WHERE employee_id = :id");
        $this->db->bind(':id', $id);
        $this->db->execute();

        $this->db->query("DELETE FROM employees WHERE id = :id");
        $this->db->bind(':id', $id);
        if($this->db->execute()) {
            header('Location: ' . BASEURL . '/admin/employees?success=deleted');
        }
    }
    // --- SYNC AKUN OTOMATIS (Update for Direktur) ---
    public function sync() {
        $query = "SELECT e.id, e.employee_number, e.employee_level 
                  FROM employees e 
                  LEFT JOIN users u ON e.id = u.employee_id 
                  WHERE u.employee_id IS NULL AND e.is_active = 1";
        
        $this->db->query($query);
        $unregistered_employees = $this->db->resultSet();
        $synced_count = 0;

        if (count($unregistered_employees) > 0) {
            $default_password = password_hash('password', PASSWORD_DEFAULT); 
            
            foreach ($unregistered_employees as $emp) {
                $role = 'staff'; 
                $level = strtolower($emp['employee_level'] ?? '');

                // Level Direktur / Dirut otomatis dapet akses Super Admin
                if (str_contains($level, 'direktur') || str_contains($level, 'dirut')) {
                    $role = 'super_admin';
                } elseif (str_contains($level, 'manager')) {
                    $role = 'admin'; 
                }

                $this->db->query("INSERT INTO users (employee_id, username, password, role) 
                                  VALUES (:employee_id, :username, :password, :role)");
                $this->db->bind(':employee_id', $emp['id']);
                $this->db->bind(':username', $emp['employee_number']);
                $this->db->bind(':password', $default_password);
                $this->db->bind(':role', $role); 
                
                if ($this->db->execute()) { $synced_count++; }
            }
        }

        // Pancing SweetAlert pakai Flasher 3 Parameter
        if ($synced_count > 0) {
            Flasher::setFlash('Berhasil', "$synced_count Akun baru berhasil disinkronisasi.", 'success');
        } else {
            Flasher::setFlash('Info', "Semua karyawan aktif sudah punya akun login.", 'info');
        }
        
        header('Location: ' . BASEURL . '/admin/employees');
        exit;
    }
}