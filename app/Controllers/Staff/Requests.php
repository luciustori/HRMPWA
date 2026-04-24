<?php

class Requests extends Controller {

    // 1. HALAMAN LIST PERMOHONAN SAYA
    public function index() {
        $db = new Database;
        $userId = $_SESSION['user_id'];
        
        // Ambil ID Employee dari User yang login
        $db->query("SELECT employee_id FROM users WHERE id = :uid");
        $db->bind(':uid', $userId);
        $user = $db->single();
        $myEmpId = $user['employee_id'];

        // Query: Ambil Semua Request milik SAYA
        $query_leave = "SELECT r.*, lt.leave_type_name, lt.leave_code 
                        FROM leave_requests r
                        JOIN leave_types lt ON r.leave_type_id = lt.id
                        WHERE r.employee_id = :eid
                        ORDER BY r.created_at DESC";

        $query_ot = "SELECT r.* FROM overtime_requests r 
                     WHERE r.employee_id = :eid 
                     ORDER BY r.created_at DESC";

        $query_trip = "SELECT r.* FROM business_trip_requests r 
                       WHERE r.employee_id = :eid 
                       ORDER BY r.created_at DESC";

        $db->query($query_leave); 
        $db->bind(':eid', $myEmpId);
        $leaves = $db->resultSet();

        $db->query($query_ot); 
        $db->bind(':eid', $myEmpId);
        $overtimes = $db->resultSet();

        $db->query($query_trip); 
        $db->bind(':eid', $myEmpId);
        $trips = $db->resultSet();
        
        // Hitung Statistik Saya
        $stats = [
            'pending' => 0,
            'approved' => 0,
            'rejected' => 0
        ];
        
        $all = array_merge($leaves, $overtimes, $trips);
        foreach($all as $req) {
            $stats[$req['status']]++;
        }

        $data = [
            'title' => 'Permohonan Saya',
            'user_name' => $_SESSION['full_name'],
            'content_view' => 'staff/requests/index',
            'leaves' => $leaves,
            'overtimes' => $overtimes,
            'trips' => $trips,
            'stats' => $stats
        ];

        $this->view('staff/layouts/staff-layout', $data);
    }

    // 2. HALAMAN FORM BARU
    public function create() {
        $db = new Database;
        $userId = $_SESSION['user_id'];
        
        // Ambil Employee ID User
        $db->query("SELECT employee_id FROM users WHERE id = :uid");
        $db->bind(':uid', $userId);
        $me = $db->single();
        $myId = $me['employee_id'];

        // A. FILTER Tipe Ijin (Exclude Cuti & Dinas Luar)
        $db->query("SELECT * FROM leave_types 
                    WHERE is_active = 1 
                    AND leave_code NOT IN ('ANNUAL', 'CUTI_TAHUNAN', 'DL', 'DINAS_LUAR') 
                    AND leave_type_name NOT LIKE '%Tahunan%'");
        $permit_types = $db->resultSet();

        // B. Ambil ID Cuti Tahunan (Utk hidden input)
        $db->query("SELECT * FROM leave_types WHERE leave_code IN ('ANNUAL', 'CUTI_TAHUNAN') LIMIT 1");
        $annual_leave = $db->single();
        
        // C. AMBIL SISA CUTI DARI TABEL EMPLOYEES
        $db->query("SELECT annual_leave_balance FROM employees WHERE id = :eid");
        $db->bind(':eid', $myId);
        $empData = $db->single();
        $remainingQuota = $empData ? $empData['annual_leave_balance'] : 0;

        // D. Ambil ID Dinas Luar
        $db->query("SELECT * FROM leave_types WHERE leave_code IN ('DL', 'DINAS_LUAR') LIMIT 1");
        $duty_leave = $db->single();

        $data = [
            'title' => 'Buat Pengajuan Baru',
            'content_view' => 'staff/requests/create',
            'permit_types' => $permit_types,
            'annual_leave' => $annual_leave,
            'duty_leave' => $duty_leave,
            'remaining_quota' => $remainingQuota
        ];

        $this->view('staff/layouts/staff-layout', $data);
    }

    // 3. PROSES SIMPAN (FULL FIXED)
    public function store() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $db = new Database;
            
            // Ambil Employee ID sendiri
            $db->query("SELECT employee_id FROM users WHERE id = :uid");
            $db->bind(':uid', $_SESSION['user_id']);
            $emp = $db->single();
            $employee_id = $emp['employee_id'];

            $cat = $_POST['category'];
            $doc_path = null;

            // Handle Upload
            if (!empty($_FILES['document']['name'])) {
                $target_dir = "../public/uploads/docs/";
                if (!is_dir($target_dir)) mkdir($target_dir, 0777, true);
                
                $file_ext = strtolower(pathinfo($_FILES["document"]["name"], PATHINFO_EXTENSION));
                $new_name = uniqid() . '.' . $file_ext;
                $target_file = $target_dir . $new_name;
                
                if (move_uploaded_file($_FILES["document"]["tmp_name"], $target_file)) {
                    $doc_path = $new_name;
                }
            }

            try {
                // GROUP 1: IJIN / CUTI / DINAS LUAR (Tabel leave_requests)
                if (in_array($cat, ['ijin', 'dinas_luar', 'cuti'])) {
                    
                    // Logic Auto-ID
                    if($cat == 'dinas_luar') {
                        $leave_type_id = $_POST['duty_id_hidden']; 
                    } elseif ($cat == 'cuti') {
                        $leave_type_id = $_POST['annual_id_hidden'];
                    } else {
                        $leave_type_id = $_POST['leave_type_id'];
                    }

                    $start = $_POST['start_date'];
                    $end = $_POST['end_date'];
                    $reason = $_POST['reason'];
                    
                    // Logic Dinas Luar Jam (Jika diisi)
                    if($cat == 'dinas_luar' && !empty($_POST['dl_start_time'])) {
                        $reason .= " [Jam Dinas: " . $_POST['dl_start_time'] . " - " . $_POST['dl_end_time'] . "]";
                    }

                    $diff = strtotime($end) - strtotime($start);
                    $days = round($diff / (60 * 60 * 24)) + 1;

                    $query = "INSERT INTO leave_requests (employee_id, leave_type_id, request_date, start_date, end_date, total_days, reason, supporting_document, status) 
                              VALUES (:eid, :ltid, CURDATE(), :start, :end, :days, :reason, :doc, 'pending')";
                    
                    $db->query($query);
                    $db->bind(':eid', $employee_id);
                    $db->bind(':ltid', $leave_type_id);
                    $db->bind(':start', $start);
                    $db->bind(':end', $end);
                    $db->bind(':days', $days);
                    $db->bind(':reason', $reason);
                    $db->bind(':doc', $doc_path);
                    $db->execute();
                }
                
                // GROUP 2: SPPD (Business Trip) - FIXED SECTION
                elseif ($cat == 'sppd') {
                    $dest = $_POST['destination'];
                    $purpose = $_POST['trip_purpose'];
                    
                    // [FIX] Mengambil input name yang benar dari create.php
                    $start = $_POST['start_date_trip']; 
                    $end = $_POST['end_date_trip'];
                    
                    $budget = str_replace(['Rp', '.', ' '], '', $_POST['estimated_budget']);
                    
                    // Validasi tanggal wajib diisi
                    if(empty($start) || empty($end)) {
                        // Redirect atau handle error
                        echo "<script>alert('Tanggal SPPD harus diisi!'); window.history.back();</script>";
                        return; 
                    }

                    $diff = strtotime($end) - strtotime($start);
                    $days = round($diff / (60 * 60 * 24)) + 1;

                    $query = "INSERT INTO business_trip_requests (employee_id, trip_purpose, destination, start_date, end_date, total_days, estimated_budget, supporting_document, status) 
                              VALUES (:eid, :purpose, :dest, :start, :end, :days, :budget, :doc, 'pending')";

                    $db->query($query);
                    $db->bind(':eid', $employee_id);
                    $db->bind(':purpose', $purpose);
                    $db->bind(':dest', $dest);
                    $db->bind(':start', $start);
                    $db->bind(':end', $end);
                    $db->bind(':days', $days);
                    $db->bind(':budget', $budget);
                    $db->bind(':doc', $doc_path);
                    $db->execute();
                }

                // GROUP 3: LEMBUR
                elseif ($cat == 'lembur') {
                    $date = $_POST['overtime_date'];
                    $start = $_POST['start_time'];
                    $end = $_POST['end_time'];
                    $reason = $_POST['reason'];

                    $t1 = strtotime($start); $t2 = strtotime($end);
                    $hours = round(abs($t2 - $t1) / 3600, 2);

                    $query = "INSERT INTO overtime_requests (employee_id, overtime_date, start_time, end_time, total_hours, reason, status) 
                              VALUES (:eid, :date, :start, :end, :hours, :reason, 'pending')";

                    $db->query($query);
                    $db->bind(':eid', $employee_id);
                    $db->bind(':date', $date);
                    $db->bind(':start', $start);
                    $db->bind(':end', $end);
                    $db->bind(':hours', $hours);
                    $db->bind(':reason', $reason);
                    $db->execute();
                }

                header('Location: ' . BASEURL . '/staff/requests');
                exit;

            } catch (Exception $e) {
                die("Error: " . $e->getMessage());
            }
        }
    }
}