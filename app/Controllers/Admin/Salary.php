<?php
// File: app/Controllers/Admin/Salary.php

class Salary extends Controller {
    private $db;

    public function __construct() {
        parent::__construct();
        if (!session_id()) session_start();
        if (!isset($_SESSION['user_id'])) {
            header('Location: ' . BASEURL . '/Admin/LoginController');
            exit;
        }
        $this->db = new Database;
    }

    public function index() {
        // 1. DATA KARYAWAN (JOIN ke Tabel Divisi)
        $sql = "SELECT e.id, e.employee_number, e.first_name, e.last_name, 
                       d.department_name, 
                       divs.id as division_id, divs.division_name, -- FIELD BARU
                       sg.id as grade_id, sg.grade_code, sg.grade_name, sg.base_salary as grade_base,
                       
                       (SELECT GROUP_CONCAT(
                            CONCAT(
                                CASE WHEN pc.component_type = 'deduction' THEN '[POT] ' ELSE '' END,
                                pc.component_name, 
                                ' (Rp ', FORMAT(sgc.amount, 0, 'id_ID'), ')'
                            ) SEPARATOR '<br>')
                        FROM salary_grade_components sgc
                        JOIN payroll_components pc ON sgc.component_id = pc.id
                        WHERE sgc.grade_id = sg.id) as component_list,

                       (SELECT COALESCE(SUM(amount), 0) FROM salary_grade_components sgc JOIN payroll_components pc ON sgc.component_id = pc.id WHERE sgc.grade_id = sg.id AND pc.component_type = 'earning') as total_grade_earning,
                       (SELECT COALESCE(SUM(amount), 0) FROM salary_grade_components sgc JOIN payroll_components pc ON sgc.component_id = pc.id WHERE sgc.grade_id = sg.id AND pc.component_type = 'deduction') as total_grade_deduction,
                       (SELECT amount FROM employee_salary_components esc JOIN payroll_components pc ON esc.component_id = pc.id WHERE esc.employee_id = e.id AND pc.component_code = 'BASIC_SALARY' AND esc.is_active = 1 LIMIT 1) as custom_base,
                       (SELECT COUNT(*) FROM employee_salary_components esc WHERE esc.employee_id = e.id AND esc.is_active = 1) as total_custom

                FROM employees e
                LEFT JOIN salary_grades sg ON e.salary_grade_id = sg.id
                LEFT JOIN departments d ON e.department_id = d.id
                LEFT JOIN divisions divs ON e.division_id = divs.id -- JOIN WAJIB
                WHERE e.is_active = 1
                ORDER BY d.department_name ASC, e.first_name ASC";

        $this->db->query($sql);
        $employees = $this->db->resultSet();

        // 2. DATA UTILITY (Grades, Components, & Divisions)
        $this->db->query("SELECT * FROM salary_grades ORDER BY grade_code ASC");
        $grades = $this->db->resultSet();

        $this->db->query("SELECT * FROM payroll_components WHERE is_active = 1 ORDER BY component_name ASC");
        $components = $this->db->resultSet();

        // Ambil Data Divisi untuk Dropdown
        try {
            $this->db->query("SELECT * FROM divisions ORDER BY division_name ASC");
            $divisions = $this->db->resultSet();
        } catch (Exception $e) {
            $divisions = []; 
        }

        $data = [
            'title' => 'Master Gaji Karyawan',
            'content_view' => 'admin/salary/index',
            'employees' => $employees,
            'grades_list' => $grades,
            'component_list' => $components,
            'divisions_list' => $divisions
        ];
        $this->view('admin/layouts/admin-layout', $data);
    }

    public function update_division() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $emp_id = $_POST['employee_id'];
            $div_id = $_POST['division_id'];
            $this->db->query("UPDATE employees SET division_id = :div WHERE id = :eid");
            $this->db->bind(':div', $div_id);
            $this->db->bind(':eid', $emp_id);
            $this->db->execute();
            Flasher::setFlash('Berhasil', 'Divisi karyawan diperbarui', 'success');
            header('Location: ' . BASEURL . '/admin/salary');
        }
    }

    public function update_grade() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $emp_id = $_POST['employee_id']; $grade_id = $_POST['grade_id'];
            $this->db->query("UPDATE employees SET salary_grade_id = :gid WHERE id = :eid");
            $this->db->bind(':gid', $grade_id); $this->db->bind(':eid', $emp_id); $this->db->execute();
            Flasher::setFlash('Berhasil', 'Golongan diperbarui', 'success'); header('Location: ' . BASEURL . '/admin/salary');
        }
    }

    // --- DI SINI LETAK ERRORNYA KEMAREN (LUPA BIND PARAMETER :id) ---
    public function get_employee_data($id) {
         if (ob_get_length()) ob_clean(); header('Content-Type: application/json');
         try {
            // Query 1
            $this->db->query("SELECT e.id, e.first_name, e.last_name, e.employee_number, sg.grade_name, sg.base_salary as grade_base FROM employees e LEFT JOIN salary_grades sg ON e.salary_grade_id = sg.id WHERE e.id = :id");
            $this->db->bind(':id', $id); // FIX: Tambahkan ini
            $emp = $this->db->single();
            
            if(!$emp) throw new Exception("Data karyawan tidak ditemukan");

            // Query 2
            $this->db->query("SELECT sgc.amount, pc.component_name, pc.component_type, 'grade' as source FROM employees e JOIN salary_grade_components sgc ON e.salary_grade_id = sgc.grade_id JOIN payroll_components pc ON sgc.component_id = pc.id WHERE e.id = :id");
            $this->db->bind(':id', $id); // FIX: Tambahkan ini
            $grade_comps = $this->db->resultSet();

            // Query 3
            $this->db->query("SELECT esc.id, esc.amount, pc.component_name, pc.component_type, 'custom' as source FROM employee_salary_components esc JOIN payroll_components pc ON esc.component_id = pc.id WHERE esc.employee_id = :id AND esc.is_active = 1");
            $this->db->bind(':id', $id); // FIX: Tambahkan ini
            $custom_comps = $this->db->resultSet();

            echo json_encode(['status'=>'success', 'employee' => $emp, 'grade_components' => $grade_comps, 'custom_components' => $custom_comps]);
         } catch(Exception $e) { 
             echo json_encode(['status'=>'error', 'message'=>$e->getMessage()]); 
         } 
         exit;
    }

    public function store_component() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $emp_id = $_POST['employee_id']; $amount = str_replace('.', '', $_POST['amount']);
            if (!empty($_POST['new_component_name'])) {
                $new_name = $_POST['new_component_name']; $new_type = $_POST['new_component_type']; $code = strtoupper(str_replace(' ', '_', $new_name)) . '_CUST_' . time();
                $this->db->query("INSERT INTO payroll_components (component_name, component_code, component_type, calculation_method, is_active) VALUES (:name, :code, :type, 'fixed', 1)");
                $this->db->bind(':name', $new_name); $this->db->bind(':code', $code); $this->db->bind(':type', $new_type); $this->db->execute(); $comp_id = $this->db->lastInsertId();
            } else { $comp_id = $_POST['component_id']; }
            $this->db->query("INSERT INTO employee_salary_components (employee_id, component_id, amount, effective_date, is_active) VALUES (:eid, :cid, :amt, CURDATE(), 1)");
            $this->db->bind(':eid', $emp_id); $this->db->bind(':cid', $comp_id); $this->db->bind(':amt', $amount); $this->db->execute();
            Flasher::setFlash('Berhasil', 'Komponen custom ditambahkan', 'success'); header('Location: ' . BASEURL . '/admin/salary');
        }
    }

    public function delete_component($id) {
        $this->db->query("DELETE FROM employee_salary_components WHERE id = :id"); $this->db->bind(':id', $id); $this->db->execute();
        Flasher::setFlash('Berhasil', 'Komponen custom dihapus', 'success'); header('Location: ' . BASEURL . '/admin/salary');
    }
}