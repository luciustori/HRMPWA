<?php
// File: app/Controllers/Admin/SalaryGrade.php

class SalaryGrade extends Controller {
    private $db;

    public function __construct() {
        parent::__construct();
        if (!session_id()) session_start();
        // Cek Login...
        $this->db = new Database;
    }

    public function index() {
        // 1. Ambil Data Grade
        $this->db->query("SELECT * FROM salary_grades ORDER BY grade_code ASC");
        $grades = $this->db->resultSet();

        // 2. Ambil List Semua Karyawan (Untuk Dropdown di Modal)
        // Kita ambil id, nama, dan grade saat ini (untuk info)
        $this->db->query("SELECT id, first_name, last_name, employee_number, salary_grade_id 
                          FROM employees WHERE is_active = 1 ORDER BY first_name ASC");
        $all_employees = $this->db->resultSet();

        // 3. Ambil Master Komponen (Untuk Dropdown di Modal)
        $this->db->query("SELECT * FROM payroll_components WHERE is_active = 1 ORDER BY component_name ASC");
        $master_components = $this->db->resultSet();

        $groupedData = [];

        foreach ($grades as $grade) {
            // Ambil Komponen Grade ini
            $this->db->query("SELECT sgc.*, pc.component_name, pc.component_type, pc.component_code
                              FROM salary_grade_components sgc
                              JOIN payroll_components pc ON sgc.component_id = pc.id
                              WHERE sgc.grade_id = :gid");
            $this->db->bind(':gid', $grade['id']);
            $components = $this->db->resultSet();

            // Pisahkan Earning vs Deduction
            $earnings = array_filter($components, fn($c) => $c['component_type'] == 'earning');
            $deductions = array_filter($components, fn($c) => $c['component_type'] == 'deduction');

            // Hitung Total
            $total_earning = $grade['base_salary'] + array_sum(array_column($earnings, 'amount'));
            $total_deduction = array_sum(array_column($deductions, 'amount'));

            // Hitung Staff
            $this->db->query("SELECT COUNT(id) as total FROM employees WHERE salary_grade_id = :gid AND is_active = 1");
            $this->db->bind(':gid', $grade['id']);
            $count = $this->db->single();

            // Gabungkan
            $grade['earnings'] = $earnings;
            $grade['deductions'] = $deductions;
            $grade['employee_count'] = $count['total'];
            $grade['total_take_home'] = $total_earning - $total_deduction;

            $groupedData[] = $grade;
        }

        $data = [
            'title' => 'Master Salary Grade',
            'content_view' => 'admin/salary_grade/index',
            'grades' => $groupedData,
            'employees_list' => $all_employees,
            'components_list' => $master_components
        ];
        $this->view('admin/layouts/admin-layout', $data);
    }

    // --- ACTION: CREATE/UPDATE GRADE (Via Modal) ---
    public function store() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $id = $_POST['id'] ?? null;
            $code = $_POST['grade_code'];
            $name = $_POST['grade_name'];
            $base = str_replace('.', '', $_POST['base_salary']);
            $desc = $_POST['description'];

            if ($id) {
                $this->db->query("UPDATE salary_grades SET grade_code=:c, grade_name=:n, base_salary=:b, description=:d WHERE id=:id");
                $this->db->bind(':id', $id);
            } else {
                $this->db->query("INSERT INTO salary_grades (grade_code, grade_name, base_salary, description) VALUES (:c, :n, :b, :d)");
            }
            $this->db->bind(':c', $code);
            $this->db->bind(':n', $name);
            $this->db->bind(':b', $base);
            $this->db->bind(':d', $desc);
            $this->db->execute();

            Flasher::setFlash('Berhasil', 'Data Grade disimpan', 'success');
            header('Location: ' . BASEURL . '/admin/salary_grade');
        }
    }

    // --- ACTION: ASSIGN KARYAWAN (Dropdown) ---
    public function assign_employee() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $grade_id = $_POST['grade_id'];
            $emp_id = $_POST['employee_id'];

            $this->db->query("UPDATE employees SET salary_grade_id = :gid WHERE id = :eid");
            $this->db->bind(':gid', $grade_id);
            $this->db->bind(':eid', $emp_id);
            $this->db->execute();

            Flasher::setFlash('Berhasil', 'Karyawan berhasil dimasukkan ke grade ini', 'success');
            header('Location: ' . BASEURL . '/admin/salary_grade');
        }
    }

    // --- ACTION: ADD KOMPONEN (Flexible) ---
    public function store_component() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $grade_id = $_POST['grade_id'];
            $amount = str_replace('.', '', $_POST['amount']);
            
            // Cek: User pilih Template atau Bikin Baru?
            if (!empty($_POST['new_component_name'])) {
                // 1. Buat Komponen Baru di Master
                $new_name = $_POST['new_component_name'];
                $new_type = $_POST['new_component_type']; // earning/deduction
                $code = strtoupper(str_replace(' ', '_', $new_name)); // Auto Generate Code

                $this->db->query("INSERT INTO payroll_components (component_name, component_code, component_type, calculation_method, is_active) 
                                  VALUES (:name, :code, :type, 'fixed', 1)");
                $this->db->bind(':name', $new_name);
                $this->db->bind(':code', $code);
                $this->db->bind(':type', $new_type);
                $this->db->execute();
                
                $comp_id = $this->db->lastInsertId(); // Ambil ID baru
            } else {
                // 2. Pakai yang sudah ada
                $comp_id = $_POST['component_id'];
            }

            // 3. Masukkan ke Grade
            $this->db->query("INSERT INTO salary_grade_components (grade_id, component_id, amount) VALUES (:gid, :cid, :amt)");
            $this->db->bind(':gid', $grade_id);
            $this->db->bind(':cid', $comp_id);
            $this->db->bind(':amt', $amount);
            $this->db->execute();

            Flasher::setFlash('Berhasil', 'Komponen ditambahkan', 'success');
            header('Location: ' . BASEURL . '/admin/salary_grade');
        }
    }

    public function delete_component($id) {
        $this->db->query("DELETE FROM salary_grade_components WHERE id = :id");
        $this->db->bind(':id', $id);
        $this->db->execute();
        
        Flasher::setFlash('Berhasil', 'Komponen dihapus', 'success');
        header('Location: ' . BASEURL . '/admin/salary_grade');
    }
}