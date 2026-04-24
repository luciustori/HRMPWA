<?php

class Departments extends Controller {
    private $db;

    public function __construct() {
        // --- BARIS WAJIB (INI YANG KURANG) ---
        parent::__construct(); 
        // -------------------------------------

        // Session start di bawah ini sebenarnya sudah di-handle oleh parent,
        // tapi dibiarkan ada juga tidak apa-apa (aman).
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

        // 3. INIT DATABASE
        $this->db = new Database;
    }

    // ========================================================================
    // A. MANAJEMEN DEPARTEMEN (INDUK)
    // ========================================================================

    public function index() {
        // 1. DATA UTAMA: Departemen + Stats
        $this->db->query("
            SELECT d.*, 
                   CONCAT(e.first_name, ' ', e.last_name) as manager_name,
                   e.employee_number as manager_code,
                   (SELECT COUNT(*) FROM divisions WHERE department_id = d.id AND is_active = 1) as total_divisions,
                   (SELECT COUNT(*) FROM employees WHERE department_id = d.id AND is_active = 1) as total_employees
            FROM departments d
            LEFT JOIN employees e ON d.manager_id = e.id
            ORDER BY d.department_name ASC
        ");
        $departments = $this->db->resultSet();
        
        // 2. DATA DIVISI (Untuk Nested Table)
        $this->db->query("
            SELECT v.*, 
                   CONCAT(e.first_name, ' ', e.last_name) as coordinator_name
            FROM divisions v
            LEFT JOIN employees e ON v.coordinator_id = e.id
            WHERE v.is_active = 1
            ORDER BY v.division_name ASC
        ");
        $all_divisions = $this->db->resultSet();
    
        // Mapping Divisi ke Departemen
        foreach ($departments as &$dept) {
            $dept['divisions_list'] = [];
            foreach ($all_divisions as $div) {
                if ($div['department_id'] == $dept['id']) {
                    $dept['divisions_list'][] = $div;
                }
            }
        }
        unset($dept);
    
        // 3. STATISTIK HEADER
        $total_dept = count($departments);
        $total_div = count($all_divisions);
        $this->db->query("SELECT COUNT(*) as total FROM employees WHERE is_active = 1");
        $total_emp = $this->db->single()['total'];
    
        // 4. DATA KARYAWAN (PENTING: Untuk Dropdown di Popup Create/Edit)
        $this->db->query("SELECT id, first_name, last_name, employee_number FROM employees WHERE is_active = 1 ORDER BY first_name ASC");
        $employees = $this->db->resultSet();
    
        $data = [
            'title' => 'Struktur Organisasi',
            'departments' => $departments,
            'employees' => $employees, // Kirim ke view index
            'stats' => [
                'total_dept' => $total_dept,
                'total_div'  => $total_div,
                'total_emp'  => $total_emp
            ],
            'content_view' => 'admin/departments/index'
        ];
        
        $this->view('admin/layouts/admin-layout', $data);
    }

    public function create() {
        // Ambil list karyawan untuk opsi Manager
        $this->db->query("SELECT id, first_name, last_name FROM employees WHERE is_active = 1 ORDER BY first_name ASC");
        $employees = $this->db->resultSet();

        $data = [
            'title' => 'Tambah Departemen',
            'employees' => $employees,
            'content_view' => 'admin/departments/create'
        ];
        $this->view('admin/layouts/admin-layout', $data);
    }

    public function store() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $this->db->query("INSERT INTO departments (company_id, department_name, department_code, manager_id, description, is_active) 
                              VALUES (:co_id, :name, :code, :mgr, :desc, 1)");
            
            $this->db->bind(':co_id', 1); // Default company ID 1
            $this->db->bind(':name', $_POST['department_name']);
            $this->db->bind(':code', strtoupper($_POST['department_code']));
            $this->db->bind(':mgr', !empty($_POST['manager_id']) ? $_POST['manager_id'] : null);
            $this->db->bind(':desc', $_POST['description']);
            
            if ($this->db->execute()) {
                header('Location: ' . BASEURL . '/admin/departments?success=created');
            } else {
                header('Location: ' . BASEURL . '/admin/departments/create?error=failed');
            }
            exit;
        }
    }

    public function edit($id) {
        // Ambil data departemen
        $this->db->query("SELECT * FROM departments WHERE id = :id");
        $this->db->bind(':id', $id);
        $dept = $this->db->single();

        if (!$dept) {
            header('Location: ' . BASEURL . '/admin/departments');
            exit;
        }

        // Ambil list karyawan untuk opsi Manager
        $this->db->query("SELECT id, first_name, last_name FROM employees WHERE is_active = 1 ORDER BY first_name ASC");
        $employees = $this->db->resultSet();

        $data = [
            'title' => 'Edit Departemen',
            'department' => $dept,
            'employees' => $employees,
            'content_view' => 'admin/departments/edit'
        ];
        $this->view('admin/layouts/admin-layout', $data);
    }

    public function update() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $id = $_POST['id'];
            
            $this->db->query("UPDATE departments SET 
                              department_name = :name,
                              department_code = :code,
                              manager_id = :mgr,
                              description = :desc,
                              is_active = :active
                              WHERE id = :id");
            
            $this->db->bind(':name', $_POST['department_name']);
            $this->db->bind(':code', strtoupper($_POST['department_code']));
            $this->db->bind(':mgr', !empty($_POST['manager_id']) ? $_POST['manager_id'] : null);
            $this->db->bind(':desc', $_POST['description']);
            $this->db->bind(':active', $_POST['is_active']);
            $this->db->bind(':id', $id);
            
            if ($this->db->execute()) {
                header('Location: ' . BASEURL . '/admin/departments?success=updated');
            } else {
                header('Location: ' . BASEURL . '/admin/departments/edit/' . $id . '?error=failed');
            }
            exit;
        }
    }

    public function delete($id) {
        // Cek ketergantungan (Divisi & Karyawan)
        $this->db->query("SELECT 
            (SELECT COUNT(*) FROM employees WHERE department_id = :id1) as emp_count,
            (SELECT COUNT(*) FROM divisions WHERE department_id = :id2) as div_count
        ");
        $this->db->bind(':id1', $id);
        $this->db->bind(':id2', $id);
        $check = $this->db->single();

        if ($check['emp_count'] > 0 || $check['div_count'] > 0) {
            header('Location: ' . BASEURL . '/admin/departments?error=has_dependencies');
            exit;
        }

        $this->db->query("DELETE FROM departments WHERE id = :id");
        $this->db->bind(':id', $id);
        $this->db->execute();
        
        header('Location: ' . BASEURL . '/admin/departments?success=deleted');
        exit;
    }

    // ========================================================================
    // B. MANAJEMEN DIVISI (SUB-BAGIAN)
    // ========================================================================

    public function divisions($dept_id) {
        // Ambil data departemen untuk header
        $this->db->query("SELECT * FROM departments WHERE id = :id");
        $this->db->bind(':id', $dept_id);
        $dept = $this->db->single();
    
        // Query Divisi + Join ke Employees untuk ambil nama Koordinator
        $this->db->query("
            SELECT v.*, 
                   CONCAT(e.first_name, ' ', e.last_name) as coordinator_name
            FROM divisions v
            LEFT JOIN employees e ON v.coordinator_id = e.id
            WHERE v.department_id = :dept_id
            ORDER BY v.division_name ASC
        ");
        $this->db->bind(':dept_id', $dept_id);
        $divisions = $this->db->resultSet();
    
        $data = [
            'title' => 'Divisi ' . $dept['department_name'],
            'department' => $dept,
            'divisions' => $divisions,
            'content_view' => 'admin/departments/divisions'
        ];
        $this->view('admin/layouts/admin-layout', $data);
    }

    /**
     * Create Division (Handle GET form & POST save)
     */
    public function create_division($dept_id) {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $this->db->query("INSERT INTO divisions (department_id, coordinator_id, division_name, division_code, description, is_active) 
                            VALUES (:dept_id, :coord, :name, :code, :desc, 1)");
            
            $this->db->bind(':dept_id', $dept_id);
            $this->db->bind(':coord', !empty($_POST['coordinator_id']) ? $_POST['coordinator_id'] : null);
            $this->db->bind(':name', $_POST['division_name']);
            $this->db->bind(':code', strtoupper($_POST['division_code']));
            $this->db->bind(':desc', $_POST['description']);
            
            if ($this->db->execute()) {
                // REDIRECT KE INDEX (Success)
                header('Location: ' . BASEURL . '/admin/departments?success=created');
            } else {
                header('Location: ' . BASEURL . '/admin/departments?error=failed');
            }
            exit;
        }
    }

    /**
     * Edit Division (Handle GET form & POST update)
     */
    public function edit_division($div_id) {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $this->db->query("UPDATE divisions 
                            SET division_name = :name, 
                                division_code = :code, 
                                coordinator_id = :coord,
                                description = :desc, 
                                is_active = :active 
                            WHERE id = :id");
            $this->db->bind(':id', $div_id);
            $this->db->bind(':name', $_POST['division_name']);
            $this->db->bind(':code', strtoupper($_POST['division_code']));
            $this->db->bind(':coord', !empty($_POST['coordinator_id']) ? $_POST['coordinator_id'] : null);
            $this->db->bind(':desc', $_POST['description']);
            $this->db->bind(':active', $_POST['is_active']);
            
            if ($this->db->execute()) {
                // REDIRECT KE INDEX (Success)
                header('Location: ' . BASEURL . '/admin/departments?success=updated');
            } else {
                header('Location: ' . BASEURL . '/admin/departments?error=failed');
            }
            exit;
        }
    }

    /**
     * Delete division
     * Cukup terima 1 parameter: div_id
     */
    public function delete_division($div_id) {
        // Cek employee dulu (Code existing...)
        $this->db->query("SELECT COUNT(*) as count FROM employees WHERE division_id = :id AND is_active = 1");
        $this->db->bind(':id', $div_id);
        if ($this->db->single()['count'] > 0) {
            header('Location: ' . BASEURL . '/admin/departments?error=has_employees');
            exit;
        }
        
        $this->db->query("DELETE FROM divisions WHERE id = :id");
        $this->db->bind(':id', $div_id);
        
        if ($this->db->execute()) {
            // REDIRECT KE INDEX (Success)
            header('Location: ' . BASEURL . '/admin/departments?success=deleted');
        } else {
            header('Location: ' . BASEURL . '/admin/departments?error=failed');
        }
        exit;
    }
}