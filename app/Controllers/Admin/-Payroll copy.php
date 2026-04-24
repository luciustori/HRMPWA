<?php
// File: app/Controllers/Admin/Payroll.php

class Payroll extends Controller {

    private $db; // Pastikan ada properti ini

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

    // --- ENGINE HITUNG GAJI BERBASIS GOLONGAN ---
    public function generate() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $month_input = $_POST['month'];
            $year = substr($month_input, 0, 4);
            $month = substr($month_input, 5, 2);
            $period_name = date('F Y', strtotime($month_input . '-01'));

            // 1. Buat/Reset Periode
            $this->db->query("SELECT id FROM payroll_periods WHERE period_month = :m AND period_year = :y");
            $this->db->bind(':m', $month);
            $this->db->bind(':y', $year);
            $existing = $this->db->single();

            if ($existing) {
                $period_id = $existing['id'];
                $this->db->query("DELETE FROM payroll_transactions WHERE period_id = :pid");
                $this->db->bind(':pid', $period_id);
                $this->db->execute();
            } else {
                $this->db->query("INSERT INTO payroll_periods (period_name, period_month, period_year, start_date, end_date, status) VALUES (:name, :m, :y, :start, :end, 'processing')");
                $this->db->bind(':name', $period_name);
                $this->db->bind(':m', $month);
                $this->db->bind(':y', $year);
                $this->db->bind(':start', $month_input . '-01');
                $this->db->bind(':end', date('Y-m-t', strtotime($month_input . '-01')));
                $this->db->execute();
                $period_id = $this->db->lastInsertId();
            }

            // 2. Ambil Karyawan Aktif + Info Golongan
            $this->db->query("SELECT id, salary_grade_id FROM employees WHERE is_active = 1");
            $employees = $this->db->resultSet();
            $total_net_all = 0;

            foreach ($employees as $emp) {
                $eid = $emp['id'];
                $gid = $emp['salary_grade_id'];
                $trx_items = [];

                // --- A. AMBIL KOMPONEN DARI GOLONGAN ---
                // Jika karyawan punya golongan, ambil resep gajinya
                if ($gid) {
                    $this->db->query("SELECT sgc.amount, pc.component_code, pc.component_name, pc.component_type, pc.id as comp_id
                                      FROM salary_grade_components sgc
                                      JOIN payroll_components pc ON sgc.component_id = pc.id
                                      WHERE sgc.grade_id = :gid");
                    $this->db->bind(':gid', $gid);
                    $grade_comps = $this->db->resultSet();
                    
                    foreach ($grade_comps as $gc) {
                        $trx_items[] = [
                            'comp_id' => $gc['comp_id'],
                            'code'    => $gc['component_code'],
                            'name'    => $gc['component_name'],
                            'type'    => $gc['component_type'],
                            'amount'  => $gc['amount']
                        ];
                    }
                }
                
                // Cari Basic Salary untuk perhitungan lembur/bpjs
                $basic_salary = 0;
                foreach($trx_items as $item) {
                    if($item['code'] == 'BASIC') $basic_salary = $item['amount'];
                }

                // --- B. DATA ABSENSI ---
                $this->db->query("SELECT COUNT(*) as days, SUM(late_duration_minutes) as late_mins FROM attendance_records WHERE employee_id = :eid AND DATE_FORMAT(attendance_date, '%Y-%m') = :m AND status = 'present'");
                $this->db->bind(':eid', $eid);
                $this->db->bind(':m', $month_input);
                $att = $this->db->single();
                $present_days = $att['days'] ?? 0;
                $late_days = 0; // Logic telat > 5 hari bisa ditambahkan di sini (query terpisah)

                // --- C. LEMBUR ---
                $this->db->query("SELECT SUM(total_hours) as jam FROM overtime_requests WHERE employee_id = :eid AND DATE_FORMAT(overtime_date, '%Y-%m') = :m AND status = 'approved'");
                $this->db->bind(':eid', $eid);
                $this->db->bind(':m', $month_input);
                $ot = $this->db->single();
                $ot_hours = $ot['jam'] ?? 0;

                // --- D. HITUNG VARIABEL (Makan/Transport/Lembur) ---
                // Cek apakah di resep golongan ada Uang Makan?
                // Kita modifikasi item yang sudah diambil dari Grade jika sifatnya 'Harian'
                
                foreach ($trx_items as &$item) {
                    // Logic: Uang Makan & Transport dikali Kehadiran
                    if ($item['code'] == 'TJ_MAKAN' || $item['code'] == 'TJ_TRANSPORT') {
                        // Amount di master golongan adalah "Per Hari" atau "Per Bulan"?
                        // ASUMSI: Di Master Golongan diinput Angka Per Hari (Misal: 50.000)
                        // Maka total = 50.000 * Hadir
                        $item['amount'] = $item['amount'] * $present_days;
                        $item['name'] .= " ({$present_days} hari)";
                    }
                }
                unset($item); // Break reference

                // Hitung Lembur (Jika ada jam lembur)
                if ($ot_hours > 0) {
                     // Cari ID komponen Lembur
                     $this->db->query("SELECT id FROM payroll_components WHERE component_code = 'OVERTIME'");
                     $ot_comp = $this->db->single();
                     if($ot_comp) {
                         $rate = ($basic_salary > 0) ? ($basic_salary / 173) : 20000;
                         $ot_pay = floor($ot_hours * $rate);
                         $trx_items[] = [
                             'comp_id' => $ot_comp['id'], 'code' => 'OVERTIME', 'name' => 'Lembur', 'type' => 'earning', 'amount' => $ot_pay
                         ];
                     }
                }

                // --- E. SIMPAN TRANSAKSI ---
                $total_earn = 0; $total_deduct = 0;
                foreach($trx_items as $i) {
                    if($i['type']=='earning') $total_earn += $i['amount'];
                    else $total_deduct += $i['amount'];
                }
                $net = $total_earn - $total_deduct;
                $total_net_all += $net;

                $this->db->query("INSERT INTO payroll_transactions (period_id, employee_id, gross_salary, total_allowances, total_deductions, net_salary, present_days, overtime_hours, status) VALUES (:pid, :eid, :gross, :earn, :deduct, :net, :pres, :ot, 'calculated')");
                $this->db->bind(':pid', $period_id);
                $this->db->bind(':eid', $eid);
                $this->db->bind(':gross', $total_earn);
                $this->db->bind(':earn', $total_earn - $basic_salary);
                $this->db->bind(':deduct', $total_deduct);
                $this->db->bind(':net', $net);
                $this->db->bind(':pres', $present_days);
                $this->db->bind(':ot', $ot_hours);
                $this->db->execute();
                $trx_id = $this->db->lastInsertId();

                foreach($trx_items as $i) {
                    $this->db->query("INSERT INTO payroll_transaction_details (transaction_id, component_id, component_code, component_name, component_type, amount) VALUES (:tid, :cid, :code, :name, :type, :amt)");
                    $this->db->bind(':tid', $trx_id);
                    $this->db->bind(':cid', $i['comp_id']);
                    $this->db->bind(':code', $i['code']);
                    $this->db->bind(':name', $i['name']);
                    $this->db->bind(':type', $i['type']);
                    $this->db->bind(':amt', $i['amount']);
                    $this->db->execute();
                }
            }

            // Update Total Periode
            $this->db->query("UPDATE payroll_periods SET total_net_salary = :total WHERE id = :id");
            $this->db->bind(':total', $total_net_all);
            $this->db->bind(':id', $period_id);
            $this->db->execute();

            header('Location: ' . BASEURL . '/admin/payroll');
        }
    }

    public function detail($id) {
        $this->db->query("SELECT * FROM payroll_periods WHERE id=:id"); $this->db->bind(':id', $id); $period = $this->db->single();
        $this->db->query("SELECT pt.*, e.first_name, e.last_name, e.employee_number, d.department_name FROM payroll_transactions pt JOIN employees e ON pt.employee_id=e.id LEFT JOIN departments d ON e.department_id=d.id WHERE pt.period_id=:pid"); 
        $this->db->bind(':pid', $id); 
        $list = $this->db->resultSet();
        $data = ['title'=>'Detail Payroll','content_view'=>'admin/payroll/detail','list'=>$list,'period'=>$period];
        $this->view('admin/layouts/admin-layout', $data);
    }
    
    public function slip($id) {
        if (!$id) {
            echo "ID Transaksi tidak ditemukan.";
            exit;
        }

        // 1. Ambil Data Header (Info Karyawan & Periode)
        $this->db->query("SELECT pt.*, 
                                e.employee_number, e.first_name, e.last_name, 
                                d.department_name, 
                                pp.period_name, pp.period_month, pp.period_year
                        FROM payroll_transactions pt
                        JOIN employees e ON pt.employee_id = e.id
                        LEFT JOIN departments d ON e.department_id = d.id
                        JOIN payroll_periods pp ON pt.period_id = pp.id
                        WHERE pt.id = :id");
        $this->db->bind(':id', $id);
        $header = $this->db->single();

        if (!$header) {
            die("Data slip gaji tidak ditemukan.");
        }

        // Default value jika kolom belum ada di database
        $header['position_name'] = $header['position_name'] ?? '-';
        $header['payment_method'] = $header['payment_method'] ?? 'Transfer Bank';

        // 2. Ambil Rincian Komponen
        $this->db->query("SELECT * FROM payroll_transaction_details 
                        WHERE transaction_id = :id 
                        ORDER BY component_type DESC, id ASC"); 
        $this->db->bind(':id', $id);
        $details = $this->db->resultSet();

        // 3. [BARU] Ambil Setting Aplikasi (Logo, Format, Alamat)
        // Kita pakai $this->db, jadi tidak perlu koneksi manual di View
        $this->db->query("SELECT setting_key, setting_value FROM app_settings");
        $results = $this->db->resultSet();
        
        $s = []; // Variabel ini akan dikirim ke View
        foreach($results as $r) {
            $s[$r['setting_key']] = $r['setting_value'];
        }
        
        // Tentukan Format (Modern/Classic)
        $format = $s['payslip_format'] ?? 'modern';

        // 4. Panggil View
        // Variabel $header, $details, $s, dan $format otomatis terbaca di file view
        if (defined('APP_ROOT')) {
            require_once APP_ROOT . '/Views/admin/payroll/slip_print.php';
        } else {
            require_once '../app/Views/admin/payroll/slip_print.php';
        }
    }
}