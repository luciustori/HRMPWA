<?php
// File: app/Controllers/Admin/Departments.php

class Departments extends Controller {

    public function __construct() {
        if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] != 'admin') {
            header('Location: ' . BASEURL . '/auth');
            exit;
        }
    }

    public function index() {
        $db = new Database;
        // Ambil data departemen + Nama Manajer (Join ke employees)
        // Dan hitung jumlah karyawan di setiap departemen (Subquery/Join count)
        $query = "SELECT d.*, 
                         CONCAT(e.first_name, ' ', COALESCE(e.last_name, '')) as manager_name,
                         (SELECT COUNT(*) FROM employees WHERE department_id = d.id AND is_active = 1) as total_members
                  FROM departments d
                  LEFT JOIN employees e ON d.manager_id = e.id
                  ORDER BY d.department_name ASC";
        
        $db->query($query);
        $departments = $db->resultSet();

        $data = [
            'title' => 'Manajemen Departemen',
            'content_view' => 'admin/departments/index',
            'departments' => $departments
        ];
        $this->view('admin/layouts/admin-layout', $data);
    }

    public function create() {
        $db = new Database;
        // Ambil list karyawan untuk dijadikan kandidat Manajer
        $db->query("SELECT id, first_name, last_name, position FROM employees WHERE is_active = 1 ORDER BY first_name ASC");
        $employees = $db->resultSet();

        $data = [
            'title' => 'Tambah Departemen',
            'content_view' => 'admin/departments/create',
            'employees' => $employees
        ];
        $this->view('admin/layouts/admin-layout', $data);
    }

    public function store() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $db = new Database;
            
            // Insert Data
            $query = "INSERT INTO departments (company_id, department_name, department_code, manager_id, created_at, updated_at) 
                      VALUES (1, :name, :code, :manager, NOW(), NOW())";
            
            $db->query($query);
            $db->bind(':name', $_POST['department_name']);
            $db->bind(':code', strtoupper($_POST['department_code'])); // Paksa Huruf Besar
            $db->bind(':manager', !empty($_POST['manager_id']) ? $_POST['manager_id'] : NULL);

            if ($db->execute()) {
                header('Location: ' . BASEURL . '/admin/departments');
                exit;
            } else {
                die("Gagal menyimpan departemen.");
            }
        }
    }

    public function edit($id) {
        $db = new Database;
        
        // Ambil data departemen
        $db->query("SELECT * FROM departments WHERE id = :id");
        $db->bind(':id', $id);
        $dept = $db->single();

        // Ambil list karyawan untuk dropdown manager
        $db->query("SELECT id, first_name, last_name FROM employees WHERE is_active = 1 ORDER BY first_name ASC");
        $employees = $db->resultSet();

        $data = [
            'title' => 'Edit Departemen',
            'content_view' => 'admin/departments/edit',
            'department' => $dept,
            'employees' => $employees
        ];
        $this->view('admin/layouts/admin-layout', $data);
    }

    public function update($id) {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $db = new Database;
            
            $query = "UPDATE departments SET 
                        department_name = :name,
                        department_code = :code,
                        manager_id = :manager,
                        updated_at = NOW()
                      WHERE id = :id";
            
            $db->query($query);
            $db->bind(':id', $id);
            $db->bind(':name', $_POST['department_name']);
            $db->bind(':code', strtoupper($_POST['department_code']));
            $db->bind(':manager', !empty($_POST['manager_id']) ? $_POST['manager_id'] : NULL);

            if ($db->execute()) {
                header('Location: ' . BASEURL . '/admin/departments');
                exit;
            } else {
                die("Gagal update departemen.");
            }
        }
    }

    public function delete($id) {
        $db = new Database;
        
        // Cek dulu apakah ada karyawan di departemen ini?
        // (Optional: Mencegah hapus departemen yang masih ada isinya)
        $db->query("SELECT COUNT(*) as count FROM employees WHERE department_id = :id");
        $db->bind(':id', $id);
        $check = $db->single();

        if ($check['count'] > 0) {
            echo "<script>alert('Gagal! Masih ada karyawan di departemen ini. Pindahkan dulu karyawan tersebut.'); window.history.back();</script>";
            return;
        }

        $db->query("DELETE FROM departments WHERE id = :id");
        $db->bind(':id', $id);
        $db->execute();
        
        header('Location: ' . BASEURL . '/admin/departments');
    }
}