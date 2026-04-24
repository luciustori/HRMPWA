<?php
// File: app/Controllers/Admin/Payroll.php

class Payroll extends Controller {

    private $db;

    public function __construct() {
        parent::__construct(); 
        if (!session_id()) session_start();

        if (!isset($_SESSION['user_id'])) {
            header('Location: ' . BASEURL . '/Admin/LoginController');
            exit;
        }

        $role = strtolower($_SESSION['role'] ?? '');
        if ($role !== 'admin' && $role !== 'super_admin') {
            header('Location: ' . BASEURL . '/staff/dashboard');
            exit;
        }

        $this->db = new Database;
    }

    public function index() {
        $this->db->query("SELECT * FROM payroll_periods ORDER BY period_year DESC, period_month DESC");
        $periods = $this->db->resultSet();
        $data = ['title' => 'Payroll System', 'content_view' => 'admin/payroll/index', 'periods' => $periods];
        $this->view('admin/layouts/admin-layout', $data);
    }

    public function create() {
        $data = ['title' => 'Generate Payroll', 'content_view' => 'admin/payroll/create'];
        $this->view('admin/layouts/admin-layout', $data);
    }

    // 1. FUNGSI GENERATE PAYROLL (FLASHER FIXED 2 PARAMETERS)
    public function generate() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $month_input = $_POST['month'] ?? date('Y-m');
            list($year, $month) = explode('-', $month_input);
            
            $period_name = date("F Y", strtotime("$year-$month-01"));
            $start_date = date('Y-m-d', strtotime("$year-$month-25 -1 month"));
            $end_date = "$year-$month-24";

            try {
                $this->db->beginTransaction();

                $this->db->query("SELECT id, status FROM payroll_periods WHERE period_month = :m AND period_year = :y");
                $this->db->bind(':m', $month);
                $this->db->bind(':y', $year);
                $existing = $this->db->single();

                if ($existing) {
                    if (in_array($existing['status'], ['approved', 'paid', 'closed'])) {
                        throw new Exception("Payroll bulan $period_name sudah di-LOCK dan tidak bisa di-generate ulang!");
                    }
                    $period_id = $existing['id'];
                    
                    $this->db->query("SELECT id FROM payroll_transactions WHERE period_id = :pid");
                    $this->db->bind(':pid', $period_id);
                    $trxs = $this->db->resultSet();
                    foreach ($trxs as $t) {
                        $this->db->query("DELETE FROM payroll_transaction_details WHERE transaction_id = :tid");
                        $this->db->bind(':tid', $t['id']);
                        if(!$this->db->execute()) throw new Exception("Gagal menghapus detail transaksi lama.");
                    }

                    $this->db->query("DELETE FROM payroll_transactions WHERE period_id = :pid");
                    $this->db->bind(':pid', $period_id);
                    if(!$this->db->execute()) throw new Exception("Gagal menghapus transaksi lama.");
                } else {
                    $this->db->query("INSERT INTO payroll_periods (period_name, period_month, period_year, start_date, end_date, status) 
                                      VALUES (:name, :m, :y, :start, :end, 'draft')");
                    $this->db->bind(':name', $period_name);
                    $this->db->bind(':m', $month);
                    $this->db->bind(':y', $year);
                    $this->db->bind(':start', $start_date);
                    $this->db->bind(':end', $end_date);
                    if(!$this->db->execute()) throw new Exception("Gagal membuat periode master bulan ini.");
                    
                    $period_id = $this->db->lastInsertId();
                }

                $this->db->query("SELECT id, salary_grade_id FROM employees WHERE is_active = 1");
                $employees = $this->db->resultSet();

                $total_gross = 0; $total_net = 0; $emp_count = count($employees);

                foreach ($employees as $emp) {
                    $eid = $emp['id'];
                    $gid = $emp['salary_grade_id'];

                    $base_salary = 0;
                    $allowances = 0;
                    $deductions = 0;
                    $components_detail = [];

                    if (!empty($gid)) {
                        $this->db->query("SELECT base_salary FROM salary_grades WHERE id = :gid");
                        $this->db->bind(':gid', $gid);
                        $grade = $this->db->single();
                        if ($grade) {
                            $base_salary = floatval($grade['base_salary']);
                        }

                        $this->db->query("SELECT pc.component_name, pc.component_type, sgc.amount 
                                          FROM salary_grade_components sgc 
                                          JOIN payroll_components pc ON sgc.component_id = pc.id 
                                          WHERE sgc.grade_id = :gid");
                        $this->db->bind(':gid', $gid);
                        $grade_comps = $this->db->resultSet();
                        
                        foreach($grade_comps as $gc) {
                            $amt = floatval($gc['amount']);
                            if ($gc['component_type'] == 'earning') $allowances += $amt;
                            else $deductions += $amt;
                            
                            $components_detail[] = [
                                'name' => $gc['component_name'],
                                'type' => $gc['component_type'],
                                'amount' => $amt
                            ];
                        }
                    }

                    $this->db->query("SELECT pc.component_name, pc.component_code, pc.component_type, esc.amount 
                                      FROM employee_salary_components esc 
                                      JOIN payroll_components pc ON esc.component_id = pc.id 
                                      WHERE esc.employee_id = :eid AND esc.is_active = 1");
                    $this->db->bind(':eid', $eid);
                    $custom_comps = $this->db->resultSet();
                    
                    foreach($custom_comps as $cc) {
                        $amt = floatval($cc['amount']);
                        if ($cc['component_code'] == 'BASIC_SALARY' && $base_salary == 0) {
                            $base_salary = $amt; continue; 
                        }
                        if ($cc['component_type'] == 'earning') $allowances += $amt;
                        else $deductions += $amt;
                        
                        $components_detail[] = [
                            'name' => $cc['component_name'] . ' (Custom)',
                            'type' => $cc['component_type'],
                            'amount' => $amt
                        ];
                    }

                    $net_salary = floatval($base_salary + $allowances - $deductions);
                    $total_gross += $base_salary;
                    $total_net += $net_salary;

                    $this->db->query("INSERT INTO payroll_transactions (period_id, employee_id, gross_salary, total_allowances, total_deductions, net_salary, status) 
                                      VALUES (:pid, :eid, :gross, :allow, :deduct, :net, 'draft')");
                    $this->db->bind(':pid', $period_id);
                    $this->db->bind(':eid', $eid);
                    $this->db->bind(':gross', $base_salary);
                    $this->db->bind(':allow', $allowances);
                    $this->db->bind(':deduct', $deductions);
                    $this->db->bind(':net', $net_salary);
                    
                    if(!$this->db->execute()) throw new Exception("Gagal insert transaksi karyawan ID: $eid.");
                    
                    $trx_id = $this->db->lastInsertId();

                    if ($base_salary > 0) {
                        $this->db->query("INSERT INTO payroll_transaction_details (transaction_id, component_name, component_type, amount) VALUES (:tid, 'Gaji Pokok', 'earning', :amt)");
                        $this->db->bind(':tid', $trx_id);
                        $this->db->bind(':amt', $base_salary);
                        if(!$this->db->execute()) throw new Exception("Gagal insert detail Gaji Pokok.");
                    }

                    foreach ($components_detail as $comp) {
                        if ($comp['amount'] > 0) {
                            $this->db->query("INSERT INTO payroll_transaction_details (transaction_id, component_name, component_type, amount) VALUES (:tid, :name, :type, :amt)");
                            $this->db->bind(':tid', $trx_id);
                            $this->db->bind(':name', $comp['name']);
                            $this->db->bind(':type', $comp['type']);
                            $this->db->bind(':amt', $comp['amount']);
                            if(!$this->db->execute()) throw new Exception("Gagal insert rincian komponen: " . $comp['name']);
                        }
                    }
                }

                $this->db->query("UPDATE payroll_periods SET total_employees = :cnt, total_gross_salary = :gross, total_net_salary = :net WHERE id = :id");
                $this->db->bind(':cnt', $emp_count);
                $this->db->bind(':gross', $total_gross);
                $this->db->bind(':net', $total_net);
                $this->db->bind(':id', $period_id);
                if(!$this->db->execute()) throw new Exception("Gagal mengupdate total rekapitulasi periode.");

                $this->db->commit();
                
                // MENGGUNAKAN 2 ARGUMEN FLASHER YANG BENAR
                Flasher::setFlash("Payroll $period_name berhasil di-generate.", 'success');
                header('Location: ' . BASEURL . '/admin/payroll/detail/' . $period_id);
                exit;

            } catch (Exception $e) {
                $this->db->rollBack();
                // PESAN ERROR ASLI DARI DATABASE AKAN MUNCUL DI SINI! (WARNA MERAH)
                Flasher::setFlash("Gagal: " . $e->getMessage(), 'error');
                header('Location: ' . BASEURL . '/admin/payroll/create');
                exit;
            }
        }
    }

    public function detail($period_id) {
        $this->db->query("SELECT * FROM payroll_periods WHERE id = :id");
        $this->db->bind(':id', $period_id);
        $period = $this->db->single();

        if (!$period) {
            Flasher::setFlash('Periode tidak ditemukan', 'error');
            header('Location: ' . BASEURL . '/admin/payroll');
            exit;
        }

        $this->db->query("SELECT pt.*, e.first_name, e.last_name, e.employee_number, e.position, d.department_name
                          FROM payroll_transactions pt
                          JOIN employees e ON pt.employee_id = e.id
                          LEFT JOIN departments d ON e.department_id = d.id
                          WHERE pt.period_id = :pid ORDER BY d.department_name ASC, e.first_name ASC");
        $this->db->bind(':pid', $period_id);
        $transactions = $this->db->resultSet();

        $data = [
            'title' => 'Detail Payroll',
            'content_view' => 'admin/payroll/detail',
            'period' => $period,
            'transactions' => $transactions
        ];
        $this->view('admin/layouts/admin-layout', $data);
    }

    public function update_transaction() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $trx_id = $_POST['transaction_id'];
            $period_id = $_POST['period_id'];
            $gross = $_POST['gross_salary'];
            $allow = $_POST['total_allowances'];
            $deduct = $_POST['total_deductions'];
            $net = $gross + $allow - $deduct;

            try {
                $this->db->beginTransaction();

                $this->db->query("UPDATE payroll_transactions SET gross_salary = :gross, total_allowances = :allow, total_deductions = :deduct, net_salary = :net WHERE id = :id");
                $this->db->bind(':gross', $gross);
                $this->db->bind(':allow', $allow);
                $this->db->bind(':deduct', $deduct);
                $this->db->bind(':net', $net);
                $this->db->bind(':id', $trx_id);
                if(!$this->db->execute()) throw new Exception();

                $this->db->query("SELECT SUM(gross_salary) as tot_gross, SUM(net_salary) as tot_net FROM payroll_transactions WHERE period_id = :pid");
                $this->db->bind(':pid', $period_id);
                $sums = $this->db->single();

                $this->db->query("UPDATE payroll_periods SET total_gross_salary = :gross, total_net_salary = :net WHERE id = :id");
                $this->db->bind(':gross', $sums['tot_gross']);
                $this->db->bind(':net', $sums['tot_net']);
                $this->db->bind(':id', $period_id);
                if(!$this->db->execute()) throw new Exception();

                $this->db->commit();
                Flasher::setFlash('Penyesuaian gaji berhasil disimpan.', 'success');
            } catch (Exception $e) {
                $this->db->rollBack();
                Flasher::setFlash('Gagal mengubah data.', 'error');
            }
            header('Location: ' . BASEURL . '/admin/payroll/detail/' . $period_id);
            exit;
        }
    }

    public function finalize($period_id) {
        try {
            $this->db->beginTransaction();
            
            $this->db->query("UPDATE payroll_periods SET status = 'approved', approved_by = :uid, approved_at = NOW() WHERE id = :id");
            $this->db->bind(':uid', $_SESSION['user_id']);
            $this->db->bind(':id', $period_id);
            if(!$this->db->execute()) throw new Exception();

            $this->db->query("UPDATE payroll_transactions SET status = 'approved', is_locked = 1 WHERE period_id = :id");
            $this->db->bind(':id', $period_id);
            if(!$this->db->execute()) throw new Exception();

            $this->db->commit();
            Flasher::setFlash('Payroll berhasil di-Finalisasi dan di-Lock!', 'success');
        } catch(Exception $e) {
            $this->db->rollBack();
            Flasher::setFlash('Gagal melakukan Finalisasi.', 'error');
        }
        header('Location: ' . BASEURL . '/admin/payroll');
        exit;
    }

    public function delete($period_id) {
        try {
            $this->db->query("SELECT status, period_name FROM payroll_periods WHERE id = :id");
            $this->db->bind(':id', $period_id);
            $period = $this->db->single();

            if (!$period) throw new Exception("Data periode tidak ditemukan.");
            if (in_array($period['status'], ['approved', 'paid', 'closed'])) {
                throw new Exception("Ditolak! Payroll bulan {$period['period_name']} sudah di-Lock.");
            }

            $this->db->beginTransaction();
            
            $this->db->query("SELECT id FROM payroll_transactions WHERE period_id = :pid");
            $this->db->bind(':pid', $period_id);
            $trxs = $this->db->resultSet();
            foreach ($trxs as $t) {
                $this->db->query("DELETE FROM payroll_transaction_details WHERE transaction_id = :tid");
                $this->db->bind(':tid', $t['id']);
                $this->db->execute();
            }

            $this->db->query("DELETE FROM payroll_transactions WHERE period_id = :pid");
            $this->db->bind(':pid', $period_id);
            $this->db->execute();

            $this->db->query("DELETE FROM payroll_periods WHERE id = :pid");
            $this->db->bind(':pid', $period_id);
            $this->db->execute();

            $this->db->commit();
            Flasher::setFlash("Draft Payroll {$period['period_name']} berhasil dihapus permanen.", 'success');
        } catch (Exception $e) {
            $this->db->rollBack();
            Flasher::setFlash($e->getMessage(), 'error');
        }
        header('Location: ' . BASEURL . '/admin/payroll'); 
        exit;
    }

    public function slip($id) {
        $this->db->query("SELECT pt.*, e.first_name, e.last_name, e.employee_number, e.position, d.department_name, pp.period_name 
                          FROM payroll_transactions pt JOIN employees e ON pt.employee_id = e.id LEFT JOIN departments d ON e.department_id = d.id JOIN payroll_periods pp ON pt.period_id = pp.id WHERE pt.id = :id");
        $this->db->bind(':id', $id);
        $header = $this->db->single();
        if (!$header) die("Data slip gaji tidak ditemukan.");

        $this->db->query("SELECT * FROM payroll_transaction_details WHERE transaction_id = :id ORDER BY component_type DESC, id ASC"); 
        $this->db->bind(':id', $id);
        $details = $this->db->resultSet();

        $this->db->query("SELECT setting_key, setting_value FROM app_settings");
        $s = []; foreach($this->db->resultSet() as $r) $s[$r['setting_key']] = $r['setting_value'];
        $format = $s['payslip_format'] ?? 'modern';

        if (defined('APP_ROOT')) {
            require_once APP_ROOT . '/Views/admin/payroll/slip_print.php';
        } else {
            require_once '../app/Views/admin/payroll/slip_print.php';
        }
    }
}