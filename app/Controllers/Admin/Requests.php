<?php
// File: app/Controllers/Admin/Requests.php

class Requests extends Controller {
    private $db;

    public function __construct() {
        parent::__construct(); 

        if (!session_id()) session_start();

        if (!isset($_SESSION['user_id'])) {
            header('Location: ' . BASEURL . '/Auth');
            exit;
        }

        $role = strtolower($_SESSION['role'] ?? '');
        if ($role !== 'admin' && $role !== 'superadmin' && $role !== 'super_admin') {
            header('Location: ' . BASEURL . '/staff/dashboard');
            exit;
        }

        $this->db = new Database;
    }

    // --- 1. INDEX (HALAMAN UTAMA) ---
    public function index() {
        $access = $this->getDepartmentAccess();
        $dept_filter = ($access['type'] === 'restricted') ? " AND e.department_id = " . $access['dept_id'] : "";

        $period_month = str_pad($_GET['month'] ?? date('m'), 2, '0', STR_PAD_LEFT);
        $period_year = $_GET['year'] ?? date('Y');
        
        $status_filter = $_GET['status'] ?? '';
        $status_sql = $status_filter ? " AND r.status = :status " : "";

        $query_leave = "SELECT r.*, e.first_name, e.last_name, lt.leave_type_name, lt.leave_code, d.department_name
                        FROM leave_requests r
                        JOIN employees e ON r.employee_id = e.id
                        LEFT JOIN departments d ON e.department_id = d.id
                        JOIN leave_types lt ON r.leave_type_id = lt.id
                        WHERE MONTH(r.start_date) = :m AND YEAR(r.start_date) = :y 
                        $dept_filter $status_sql
                        ORDER BY FIELD(r.status, 'pending', 'approved', 'rejected'), r.created_at DESC";
        
        $this->db->query($query_leave);
        $this->db->bind(':m', $period_month);
        $this->db->bind(':y', $period_year);
        if($status_filter) $this->db->bind(':status', $status_filter);
        $leaves = $this->db->resultSet();

        $query_ot = "SELECT r.*, e.first_name, e.last_name, d.department_name
                     FROM overtime_requests r
                     JOIN employees e ON r.employee_id = e.id
                     LEFT JOIN departments d ON e.department_id = d.id
                     WHERE MONTH(r.overtime_date) = :m AND YEAR(r.overtime_date) = :y 
                     $dept_filter $status_sql
                     ORDER BY FIELD(r.status, 'pending', 'approved', 'rejected'), r.created_at DESC";
        
        $this->db->query($query_ot);
        $this->db->bind(':m', $period_month);
        $this->db->bind(':y', $period_year);
        if($status_filter) $this->db->bind(':status', $status_filter);
        $overtimes = $this->db->resultSet();

        $query_trip = "SELECT r.*, e.first_name, e.last_name, d.department_name
                       FROM business_trip_requests r
                       JOIN employees e ON r.employee_id = e.id
                       LEFT JOIN departments d ON e.department_id = d.id
                       WHERE MONTH(r.start_date) = :m AND YEAR(r.start_date) = :y 
                       $dept_filter $status_sql
                       ORDER BY FIELD(r.status, 'pending', 'approved', 'rejected'), r.created_at DESC";

        $this->db->query($query_trip);
        $this->db->bind(':m', $period_month);
        $this->db->bind(':y', $period_year);
        if($status_filter) $this->db->bind(':status', $status_filter);
        $trips = $this->db->resultSet();

        $all_requests = [];
        foreach($leaves as $k => $v) {
            $leaves[$k]['general_type'] = 'leave';
            $leaves[$k]['req_label'] = $v['leave_type_name'];
            $all_requests[] = $leaves[$k];
        }
        foreach($overtimes as $k => $v) {
            $overtimes[$k]['general_type'] = 'overtime';
            $overtimes[$k]['req_label'] = 'Lembur';
            $all_requests[] = $overtimes[$k];
        }
        foreach($trips as $k => $v) {
            $trips[$k]['general_type'] = 'trip';
            $trips[$k]['req_label'] = 'SPPD: ' . $v['destination'];
            $all_requests[] = $trips[$k];
        }

        usort($all_requests, function($a, $b) {
            return strtotime($b['created_at'] ?? 'now') - strtotime($a['created_at'] ?? 'now');
        });

        $this->db->query("SELECT status FROM leave_requests r JOIN employees e ON r.employee_id = e.id WHERE MONTH(r.start_date) = :m AND YEAR(r.start_date) = :y $dept_filter");
        $this->db->bind(':m', $period_month); $this->db->bind(':y', $period_year);
        $all_leaves = $this->db->resultSet();
        
        $this->db->query("SELECT status FROM overtime_requests r JOIN employees e ON r.employee_id = e.id WHERE MONTH(r.overtime_date) = :m AND YEAR(r.overtime_date) = :y $dept_filter");
        $this->db->bind(':m', $period_month); $this->db->bind(':y', $period_year);
        $all_ots = $this->db->resultSet();

        $this->db->query("SELECT status FROM business_trip_requests r JOIN employees e ON r.employee_id = e.id WHERE MONTH(r.start_date) = :m AND YEAR(r.start_date) = :y $dept_filter");
        $this->db->bind(':m', $period_month); $this->db->bind(':y', $period_year);
        $all_trips = $this->db->resultSet();

        $all_reqs = array_merge($all_leaves, $all_ots, $all_trips);
        $stats = [
            'pending'  => count(array_filter($all_reqs, fn($i) => $i['status'] == 'pending')),
            'approved' => count(array_filter($all_reqs, fn($i) => $i['status'] == 'approved')),
            'rejected' => count(array_filter($all_reqs, fn($i) => $i['status'] == 'rejected'))
        ];

        $this->db->query("SELECT id, first_name, last_name, employee_number FROM employees WHERE is_active = 1 ORDER BY first_name ASC");
        $employees = $this->db->resultSet();

        $this->db->query("SELECT * FROM leave_types WHERE is_active = 1 AND leave_code NOT IN ('ANNUAL', 'CUTI_TAHUNAN', 'DL', 'DINAS_LUAR') AND leave_type_name NOT LIKE '%Tahunan%'"); 
        $permit_types = $this->db->resultSet();

        $this->db->query("SELECT * FROM leave_types WHERE is_active = 1 AND leave_code IN ('ANNUAL', 'CUTI_TAHUNAN') LIMIT 1");
        $annual_leave = $this->db->single();

        $this->db->query("SELECT * FROM leave_types WHERE is_active = 1 AND leave_code IN ('DL', 'DINAS_LUAR') LIMIT 1");
        $duty_leave = $this->db->single();

        $data = [
            'title' => 'Pusat Persetujuan',
            'content_view' => 'admin/requests/index',
            'all_requests' => $all_requests,
            'leaves' => $leaves,
            'overtimes' => $overtimes,
            'trips' => $trips,
            'stats' => $stats,
            'employees' => $employees,
            'permit_types' => $permit_types,
            'annual_leave' => $annual_leave,
            'duty_leave' => $duty_leave,
            'period_month' => $period_month,
            'period_year' => $period_year,
            'status_filter' => $status_filter
        ];
        $this->view('admin/layouts/admin-layout', $data);
    }

    // --- 2. STORE ---
    public function store() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $cat = $_POST['category']; 
            $employee_id = $_POST['employee_id'];
            $is_ajax = isset($_POST['is_ajax']) && $_POST['is_ajax'] == '1';
            
            $current_user_emp_id = $_SESSION['employee_id'] ?? 0;

            if ($employee_id == $current_user_emp_id) {
                $initial_status = 'pending';
                $reviewer_id = null;
                $reviewed_at = null;
                $reviewer_notes = null;
                $flash_msg = 'Pengajuan berhasil dibuat. Menunggu persetujuan Pimpinan.';
            } else {
                $initial_status = 'approved';
                $reviewer_id = $_SESSION['user_id'];
                $reviewed_at = date('Y-m-d H:i:s');
                $reviewer_notes = 'Diinputkan oleh Manager/Admin (Auto Approved)';
                $flash_msg = 'Pengajuan berhasil dibuat dan otomatis DISETUJUI.';
            }

            try {
                $doc_path = null;
                if (!empty($_FILES['document']['name'])) {
                    $doc_path = $this->uploadFile($_FILES['document']);
                }

                if (in_array($cat, ['ijin', 'dinas_luar', 'cuti'])) {
                    $leave_type_id = $_POST['leave_type_id'];
                    $start_date = $_POST['start_date'];
                    $end_date = $_POST['end_date'];
                    $reason = $_POST['reason'];

                    // 1. VALIDASI DOUBLE POSTING/OVERLAPPING DATES
                    if ($this->isDateOverlapping($employee_id, $start_date, $end_date)) {
                        throw new Exception("GAGAL! Karyawan sudah memiliki pengajuan pada tanggal tersebut.");
                    }
                    
                    // 2. HITUNG HARI KERJA SAJA (Skip Sabtu, Minggu, & Libur Nasional)
                    $total_days = $this->calculateWorkingDays($start_date, $end_date);
                    
                    if ($total_days <= 0) {
                        throw new Exception("Rentang tanggal yang dipilih jatuh sepenuhnya pada hari libur / akhir pekan!");
                    }

                    $query = "INSERT INTO leave_requests 
                              (employee_id, leave_type_id, request_date, start_date, end_date, total_days, reason, supporting_document, status, reviewed_by, reviewed_at, reviewer_notes) 
                              VALUES (:eid, :ltid, :req_date, :start, :end, :days, :reason, :doc, :status, :r_by, :r_at, :r_notes)";
                    
                    $this->db->query($query);
                    $this->db->bind(':eid', $employee_id);
                    $this->db->bind(':ltid', $leave_type_id);
                    $this->db->bind(':req_date', $start_date); 
                    $this->db->bind(':start', $start_date);
                    $this->db->bind(':end', $end_date);
                    $this->db->bind(':days', $total_days); // Menyimpan jumlah hari kerja saja
                    $this->db->bind(':reason', $reason);
                    $this->db->bind(':doc', $doc_path);
                    $this->db->bind(':status', $initial_status);
                    $this->db->bind(':r_by', $reviewer_id);
                    $this->db->bind(':r_at', $reviewed_at);
                    $this->db->bind(':r_notes', $reviewer_notes);
                    $this->db->execute();
                } 
                elseif ($cat == 'sppd') {
                    if (!$doc_path) throw new Exception("Bukti Surat Tugas wajib diupload untuk SPPD!");

                    $dest = $_POST['destination'];
                    $purpose = $_POST['trip_purpose'];
                    $start = $_POST['start_date'];
                    $end = $_POST['end_date'];
                    $budget = str_replace(['Rp', '.', ' '], '', $_POST['estimated_budget']);
                    
                    // VALIDASI DOUBLE POSTING SPPD
                    if ($this->isDateOverlapping($employee_id, $start, $end)) {
                        throw new Exception("GAGAL! Karyawan sudah memiliki kegiatan (SPPD/Cuti/Dinas) di tanggal tersebut.");
                    }
                    
                    // SPPD biasanya menghitung semua hari termasuk weekend karena bepergian,
                    // Tapi jika ingin hari kerja saja: ubah ke -> $days = $this->calculateWorkingDays($start, $end);
                    $diff = strtotime($end) - strtotime($start);
                    $days = round($diff / (60 * 60 * 24)) + 1;

                    $query = "INSERT INTO business_trip_requests 
                              (employee_id, trip_purpose, destination, start_date, end_date, total_days, estimated_budget, supporting_document, status, reviewed_by, reviewed_at, reviewer_notes) 
                              VALUES (:eid, :purpose, :dest, :start, :end, :days, :budget, :doc, :status, :r_by, :r_at, :r_notes)";

                    $this->db->query($query);
                    $this->db->bind(':eid', $employee_id);
                    $this->db->bind(':purpose', $purpose);
                    $this->db->bind(':dest', $dest);
                    $this->db->bind(':start', $start);
                    $this->db->bind(':end', $end);
                    $this->db->bind(':days', $days);
                    $this->db->bind(':budget', $budget);
                    $this->db->bind(':doc', $doc_path);
                    $this->db->bind(':status', $initial_status);
                    $this->db->bind(':r_by', $reviewer_id);
                    $this->db->bind(':r_at', $reviewed_at);
                    $this->db->bind(':r_notes', $reviewer_notes);
                    $this->db->execute();
                }
                elseif ($cat == 'lembur') {
                    $date = $_POST['overtime_date'];
                    $start = $_POST['start_time'];
                    $end = $_POST['end_time'];
                    $reason = $_POST['reason'];
                    
                    // VALIDASI DOUBLE POSTING LEMBUR
                    if ($this->isOvertimeDuplicate($employee_id, $date)) {
                        throw new Exception("Karyawan ini sudah memiliki jadwal lembur pada tanggal tersebut.");
                    }

                    $t1 = strtotime($start); $t2 = strtotime($end);
                    $hours = round(abs($t2 - $t1) / 3600, 2);

                    $query = "INSERT INTO overtime_requests 
                              (employee_id, overtime_date, start_time, end_time, total_hours, reason, status, reviewed_by, reviewed_at, reviewer_notes) 
                              VALUES (:eid, :date, :start, :end, :hours, :reason, :status, :r_by, :r_at, :r_notes)";

                    $this->db->query($query);
                    $this->db->bind(':eid', $employee_id);
                    $this->db->bind(':date', $date);
                    $this->db->bind(':start', $start);
                    $this->db->bind(':end', $end);
                    $this->db->bind(':hours', $hours);
                    $this->db->bind(':reason', $reason);
                    $this->db->bind(':status', $initial_status);
                    $this->db->bind(':r_by', $reviewer_id);
                    $this->db->bind(':r_at', $reviewed_at);
                    $this->db->bind(':r_notes', $reviewer_notes);
                    $this->db->execute();
                }

                if ($is_ajax) {
                    echo json_encode(['success' => true, 'message' => $flash_msg]);
                    exit;
                } else {
                    Flasher::setFlash('Berhasil', $flash_msg, 'success');
                    header('Location: ' . BASEURL . '/admin/requests');
                    exit;
                }

            } catch (Exception $e) {
                if ($is_ajax) {
                    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
                    exit;
                } else {
                    Flasher::setFlash('Gagal', $e->getMessage(), 'error');
                    header('Location: ' . BASEURL . '/admin/requests');
                    exit;
                }
            }
        }
    }

    // --- 3. UPDATE STATUS ---
    public function update_status() {
        header('Content-Type: application/json');

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo json_encode(['success' => false, 'message' => 'Invalid Request Method']);
            exit;
        }

        try {
            $type = $_POST['type'] ?? ''; 
            $id = $_POST['id'] ?? 0;
            $status = $_POST['status'] ?? ''; 
            $notes = $_POST['notes'] ?? '';
            $user_id = $_SESSION['user_id'] ?? 0; 

            if (empty($id) || empty($type) || empty($status)) {
                throw new Exception("Data tidak lengkap.");
            }

            $table = '';
            if ($type == 'leave') $table = 'leave_requests';
            elseif ($type == 'overtime') $table = 'overtime_requests';
            elseif ($type == 'trip') $table = 'business_trip_requests';
            else {
                throw new Exception("Tipe pengajuan tidak valid.");
            }

            $query = "UPDATE $table SET status = :status, reviewed_by = :uid, reviewed_at = NOW(), reviewer_notes = :notes WHERE id = :id";
            $this->db->query($query);
            $this->db->bind(':status', $status);
            $this->db->bind(':uid', $user_id);
            $this->db->bind(':notes', $notes);
            $this->db->bind(':id', $id);

            if ($this->db->execute()) {
                echo json_encode(['success' => true]);
            } else {
                throw new Exception("Gagal update database.");
            }

        } catch (Exception $e) {
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        }
        exit;
    }

    // --- 4. GET DETAIL ---
    public function get_detail($type, $id) {
        if (!isset($_SESSION['user_id'])) {
            echo json_encode(['success' => false, 'message' => 'Unauthorized']);
            exit;
        }

        $table = '';
        if ($type == 'leave') $table = 'leave_requests';
        elseif ($type == 'overtime') $table = 'overtime_requests';
        elseif ($type == 'trip') $table = 'business_trip_requests';
        else {
            echo json_encode(['success' => false, 'message' => 'Invalid Type']);
            exit;
        }

        $sql = "SELECT r.*, e.first_name, e.last_name, e.employee_number, d.department_name
                FROM $table r
                JOIN employees e ON r.employee_id = e.id
                LEFT JOIN departments d ON e.department_id = d.id
                WHERE r.id = :id";
        
        if ($type == 'leave') {
            $sql = "SELECT r.*, e.first_name, e.last_name, e.employee_number, d.department_name, lt.leave_type_name
                    FROM leave_requests r
                    JOIN employees e ON r.employee_id = e.id
                    LEFT JOIN departments d ON e.department_id = d.id
                    JOIN leave_types lt ON r.leave_type_id = lt.id
                    WHERE r.id = :id";
        }

        $this->db->query($sql);
        $this->db->bind(':id', $id);
        $data = $this->db->single();

        if ($data) {
            echo json_encode(['success' => true, 'data' => $data]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Data tidak ditemukan']);
        }
        exit;
    }

    // --- 5. DELETE ---
    public function delete($type, $id) {
        if (!isset($_SESSION['user_id'])) {
            header('Location: ' . BASEURL . '/Auth');
            exit;
        }

        $m = $_GET['month'] ?? date('m');
        $y = $_GET['year'] ?? date('Y');
        $status = $_GET['status'] ?? '';
        $query_params = "?month={$m}&year={$y}";
        if ($status) $query_params .= "&status={$status}";

        $table = '';
        if ($type == 'leave') $table = 'leave_requests';
        elseif ($type == 'overtime') $table = 'overtime_requests';
        elseif ($type == 'trip') $table = 'business_trip_requests';
        else {
            Flasher::setFlash('Error', 'Tipe pengajuan tidak dikenali.', 'error');
            header('Location: ' . BASEURL . '/admin/requests' . $query_params);
            exit;
        }

        try {
            $this->db->query("DELETE FROM $table WHERE id = :id");
            $this->db->bind(':id', $id);
            
            if ($this->db->execute()) {
                Flasher::setFlash('Berhasil', 'Data pengajuan berhasil dihapus.', 'success');
            } else {
                Flasher::setFlash('Gagal', 'Tidak dapat menghapus data.', 'error');
            }
        } catch (Exception $e) {
            Flasher::setFlash('Error', $e->getMessage(), 'error');
        }

        header('Location: ' . BASEURL . '/admin/requests' . $query_params);
        exit;
    }

    public function get_leave_balance($employee_id) {
        $year = date('Y');
        
        $this->db->query("SELECT annual_leave_balance FROM employees WHERE id = :eid");
        $this->db->bind(':eid', $employee_id);
        $emp = $this->db->single();
        $base_quota = $emp['annual_leave_balance'] ?? 12;

        $this->db->query("SELECT COALESCE(SUM(lr.total_days), 0) as used_days FROM leave_requests lr
                          JOIN leave_types lt ON lr.leave_type_id = lt.id
                          WHERE lr.employee_id = :eid AND lr.status = 'approved' AND YEAR(lr.start_date) = :year AND lt.leave_code IN ('ANNUAL', 'CUTI_TAHUNAN')");
        $this->db->bind(':eid', $employee_id);
        $this->db->bind(':year', $year);
        $cuti = $this->db->single();
        
        $sisa_cuti = max(0, $base_quota - ($cuti['used_days'] ?? 0));

        echo json_encode(['success' => true, 'data' => ['total_entitled' => $base_quota, 'total_remaining' => $sisa_cuti]]);
        exit;
    }

    private function uploadFile($file) {
        $targetDir = "../public/uploads/docs/";
        if (!file_exists($targetDir)) mkdir($targetDir, 0777, true);
        
        $fileName = time() . '_' . basename($file["name"]);
        move_uploaded_file($file["tmp_name"], $targetDir . $fileName);
        return "uploads/docs/" . $fileName;
    }

    private function getDepartmentAccess() {
        $role = strtolower($_SESSION['role'] ?? ($_SESSION['user_role'] ?? ''));
        
        if (in_array($role, ['admin', 'superadmin', 'super_admin'])) {
            return ['type' => 'all'];
        }

        $this->db->query("SELECT COUNT(*) as has_access FROM users u JOIN roles r ON u.role = r.role_slug JOIN role_permissions rp ON r.id = rp.role_id JOIN permissions p ON rp.permission_id = p.id WHERE u.id = :uid AND p.permission_slug = 'manage_all_departments'");
        $this->db->bind(':uid', $_SESSION['user_id']);
        if ($this->db->single()['has_access'] > 0) return ['type' => 'all'];

        $this->db->query("SELECT department_id FROM employees WHERE id = :eid");
        $this->db->bind(':eid', $_SESSION['employee_id']);
        $emp = $this->db->single();
        return ['type' => 'restricted', 'dept_id' => $emp['department_id'] ?? 0];
    }

    // =========================================================================
    // FUNGSI SAKTI: PENCEGAH DOUBLE POST & PENGHITUNG HARI KERJA
    // =========================================================================

    /**
     * Menghitung total hari kerja antara 2 tanggal.
     * Mengabaikan hari Sabtu, Minggu, dan Hari Libur Nasional (tabel holidays).
     */
    private function calculateWorkingDays($start_date, $end_date) {
        $start = new DateTime($start_date);
        $end = new DateTime($end_date);
        $end->modify('+1 day'); // Memastikan end_date ikut dihitung di DatePeriod

        $period = new DatePeriod($start, new DateInterval('P1D'), $end);
        
        // Tarik data hari libur dari database
        $holidays = [];
        $this->db->query("SELECT holiday_date FROM holidays WHERE holiday_date BETWEEN :start AND :end");
        $this->db->bind(':start', $start_date);
        $this->db->bind(':end', $end_date);
        $results = $this->db->resultSet();
        foreach($results as $row) {
            $holidays[] = $row['holiday_date'];
        }

        $working_days = 0;
        foreach ($period as $dt) {
            $dayOfWeek = $dt->format('N'); // 1 = Senin, 6 = Sabtu, 7 = Minggu
            $dateStr = $dt->format('Y-m-d');

            // Hitung jika BUKAN Sabtu/Minggu DAN BUKAN Hari Libur Nasional
            if ($dayOfWeek < 6 && !in_array($dateStr, $holidays)) {
                $working_days++;
            }
        }
        return $working_days;
    }

    /**
     * Mencegah Double Post / Cuti Bentrok
     */
    private function isDateOverlapping($employee_id, $start_date, $end_date) {
        // Cek apakah tanggal tumpang tindih di leave_requests
        $this->db->query("SELECT id FROM leave_requests WHERE employee_id = :eid AND status != 'rejected' AND (start_date <= :end AND end_date >= :start)");
        $this->db->bind(':eid', $employee_id);
        $this->db->bind(':start', $start_date);
        $this->db->bind(':end', $end_date);
        if ($this->db->single()) return true;

        // Cek apakah tanggal tumpang tindih di business_trip_requests
        $this->db->query("SELECT id FROM business_trip_requests WHERE employee_id = :eid AND status != 'rejected' AND (start_date <= :end AND end_date >= :start)");
        $this->db->bind(':eid', $employee_id);
        $this->db->bind(':start', $start_date);
        $this->db->bind(':end', $end_date);
        if ($this->db->single()) return true;

        return false;
    }

    /**
     * Mencegah Lembur ganda di tanggal yang sama
     */
    private function isOvertimeDuplicate($employee_id, $date) {
        $this->db->query("SELECT id FROM overtime_requests WHERE employee_id = :eid AND status != 'rejected' AND overtime_date = :date");
        $this->db->bind(':eid', $employee_id);
        $this->db->bind(':date', $date);
        if ($this->db->single()) return true;
        return false;
    }
}