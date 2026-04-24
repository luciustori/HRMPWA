<?php
// File: app/Controllers/Admin/Schedules.php

class Schedules extends Controller {

    private $db; 

    public function __construct() { 
        if (session_status() === PHP_SESSION_NONE) session_start();
        
        if (!isset($_SESSION['user_id'])) {
            header('Location: ' . BASEURL . '/Auth');
            exit;
        }

        $role = strtolower($_SESSION['user_role'] ?? '');
        $allowed_roles = ['super_admin', 'admin', 'coordinator'];
        if (!in_array($role, $allowed_roles)) {
            header('Location: ' . BASEURL . '/Staff/Dashboard');
            exit;
        }

        $this->db = new Database;
    }

    public function index() { $this->calendar(); }

    public function calendar() {
        $month = $_GET['month'] ?? date('m');
        $year  = $_GET['year'] ?? date('Y');
        
        // Scope Logic
        $role = strtolower($_SESSION['user_role'] ?? '');
        $scope = 'global';
        $my_div = 0;

        if ($role === 'coordinator') {
            $scope = 'division';
            $emp = $this->db->fetchOne("SELECT division_id FROM employees WHERE id = :id", [':id' => $_SESSION['employee_id']]);
            $my_div = $emp['division_id'] ?? 0;
        }

        // Query Employees
        $sql = "SELECT e.id, e.first_name, e.last_name, e.department_id, e.division_id, 
                       d.department_name, v.division_name 
                FROM employees e
                LEFT JOIN departments d ON e.department_id = d.id
                LEFT JOIN divisions v ON e.division_id = v.id
                WHERE e.is_active = 1";
        if ($scope == 'division' && $my_div) {
            $sql .= " AND e.division_id = $my_div";
        }
        $sql .= " ORDER BY d.department_name ASC, v.division_name ASC, e.first_name ASC";
        
        $employees = $this->db->fetchAll($sql);

        // Query Assignments
        $assignments = $this->db->fetchAll("SELECT * FROM shift_assignments WHERE MONTH(assignment_date) = :m AND YEAR(assignment_date) = :y", [':m' => $month, ':y' => $year]);

        // Map Data
        $assignment_map = [];
        foreach ($assignments as $a) {
            $shift = $this->db->fetchOne("SELECT shift_code FROM work_shifts WHERE id = :id", [':id' => $a['shift_id']]);
            $a['shift_code'] = $shift ? $shift['shift_code'] : '?';
            $assignment_map[$a['assignment_date'] . '_' . $a['employee_id']] = $a;
        }

        // Shifts List
        $shifts = $this->db->fetchAll("SELECT * FROM work_shifts WHERE is_active = 1");

        $data = [
            'title' => 'Kalender Shift',
            'employees' => $employees,
            'assignment_map' => $assignment_map,
            'shifts' => $shifts,
            'month' => $month,
            'year' => $year,
            'content_view' => 'admin/schedules/calendar'
        ];
        
        $this->view('admin/layouts/admin-layout', $data);
    }

    public function assign_store() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            
            $is_bulk = isset($_POST['employee_ids']) && is_array($_POST['employee_ids']);
            $shift_id = $_POST['shift_id']; // Bisa berisi angka ID atau string kosong "" (Libur)
            $is_mod = isset($_POST['is_mod']) ? 1 : 0;
            $user_id = $_SESSION['user_id'];

            try {
                // Mulai Transaksi
                $this->db->beginTransaction();

                if ($is_bulk) {
                    // --- BULK MODE ---
                    $start_date = strtotime($_POST['start_date']);
                    $end_date = strtotime($_POST['end_date']);
                    $emp_ids = $_POST['employee_ids'];
                    
                    // RULE BARU: Ambil nilai opsi skip sabtu minggu (Otomatis Libur 5 Hari Kerja)
                    $skip_weekend = isset($_POST['skip_weekend']) && $_POST['skip_weekend'] == '1';

                    for ($i = $start_date; $i <= $end_date; $i += 86400) {
                        $date = date('Y-m-d', $i);
                        $dayOfWeek = date('N', $i); // 1 (Senin) sampai 7 (Minggu)

                        // JIKA HARI INI SABTU (6) ATAU MINGGU (7) DAN OPSI SKIP AKTIF, LEWATI!
                        if ($skip_weekend && ($dayOfWeek == 6 || $dayOfWeek == 7)) {
                            continue;
                        }
                        
                        foreach ($emp_ids as $emp_id) {
                            if (empty($shift_id)) {
                                // LOGIC 1: Jika Shift Kosong (Libur) -> HAPUS JADWAL
                                $this->db->query("DELETE FROM shift_assignments WHERE employee_id = :emp AND assignment_date = :date");
                                $this->db->bind(':emp', $emp_id);
                                $this->db->bind(':date', $date);
                                $this->db->execute();
                            } else {
                                // LOGIC 2: Jika Ada Shift -> INSERT/UPDATE
                                $sql = "INSERT INTO shift_assignments (employee_id, shift_id, assignment_date, is_mod, created_by) 
                                        VALUES (:emp, :shift, :date, :mod, :user)
                                        ON DUPLICATE KEY UPDATE shift_id = :shift2, is_mod = :mod2";
                                
                                $this->db->query($sql);
                                $this->db->bind(':emp', $emp_id);
                                $this->db->bind(':shift', $shift_id);
                                $this->db->bind(':date', $date);
                                $this->db->bind(':mod', $is_mod);
                                $this->db->bind(':user', $user_id);
                                $this->db->bind(':shift2', $shift_id);
                                $this->db->bind(':mod2', $is_mod);
                                $this->db->execute();
                            }
                        }
                    }
                    $this->db->commit();
                    Flasher::setFlash('Jadwal massal berhasil disimpan', 'success');
                    header('Location: ' . BASEURL . '/Admin/Schedules/calendar?month=' . date('m', strtotime($_POST['start_date'])));

                } else {
                    // --- SINGLE MODE (AJAX) ---
                    $emp_id = $_POST['employee_id'];
                    $date = $_POST['assignment_date'];

                    if (empty($shift_id)) {
                        // LOGIC 1: Jika pilih "LIBUR/OFF" -> HAPUS DATA DARI DB
                        $this->db->query("DELETE FROM shift_assignments WHERE employee_id = :emp AND assignment_date = :date");
                        $this->db->bind(':emp', $emp_id);
                        $this->db->bind(':date', $date);
                        $this->db->execute();
                    } else {
                        // LOGIC 2: Jika pilih Shift -> INSERT ATAU UPDATE
                        $sql = "INSERT INTO shift_assignments (employee_id, shift_id, assignment_date, is_mod, created_by) 
                                VALUES (:emp, :shift, :date, :mod, :user)
                                ON DUPLICATE KEY UPDATE shift_id = :shift2, is_mod = :mod2";
                        
                        $this->db->query($sql);
                        $this->db->bind(':emp', $emp_id);
                        $this->db->bind(':shift', $shift_id);
                        $this->db->bind(':date', $date);
                        $this->db->bind(':mod', $is_mod);
                        $this->db->bind(':user', $user_id);
                        $this->db->bind(':shift2', $shift_id);
                        $this->db->bind(':mod2', $is_mod);
                        $this->db->execute();
                    }
                    
                    $this->db->commit();

                    header('Content-Type: application/json');
                    echo json_encode(['success' => true]);
                    exit; 
                }

            } catch (Exception $e) {
                $this->db->rollBack();
                
                if (!$is_bulk) {
                    header('HTTP/1.1 500 Internal Server Error');
                    header('Content-Type: application/json');
                    echo json_encode(['error' => $e->getMessage()]);
                    exit;
                }

                Flasher::setFlash('Gagal: ' . $e->getMessage(), 'danger');
                header('Location: ' . BASEURL . '/Admin/Schedules/calendar');
            }
        }
    }
    
    public function shifts() {
        $data = [
            'title' => 'Master Shift',
            'content_view' => 'admin/schedules/shifts',
            'shifts' => $this->db->fetchAll("SELECT * FROM work_shifts ORDER BY is_active DESC, shift_name ASC")
        ];
        $this->view('admin/layouts/admin-layout', $data);
    }

    public function holidays() {
        $year = $_GET['year'] ?? date('Y');
        $data = [
            'title' => 'Hari Libur',
            'content_view' => 'admin/schedules/holidays',
            'holidays' => $this->db->fetchAll("SELECT * FROM holidays WHERE YEAR(holiday_date) = :y ORDER BY holiday_date ASC", [':y'=>$year]),
            'year' => $year
        ];
        $this->view('admin/layouts/admin-layout', $data);
    }

    // ==========================================
    // CRUD MASTER SHIFT
    // ==========================================

    public function shifts_store() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            try {
                $sql = "INSERT INTO work_shifts (shift_name, shift_code, start_time, end_time, late_tolerance_minutes, is_active) 
                        VALUES (:name, :code, :start, :end, :tol, :active)";
                
                $this->db->query($sql);
                $this->db->bind(':name', $_POST['shift_name']);
                $this->db->bind(':code', strtoupper($_POST['shift_code']));
                $this->db->bind(':start', $_POST['start_time']);
                $this->db->bind(':end', $_POST['end_time']);
                $this->db->bind(':tol', $_POST['late_tolerance_minutes'] ?? 15);
                $this->db->bind(':active', isset($_POST['is_active']) ? 1 : 0);
                
                $this->db->execute();
                
                Flasher::setFlash('Data shift berhasil ditambahkan', 'success');
            } catch (Exception $e) {
                Flasher::setFlash('Gagal menambah shift: ' . $e->getMessage(), 'danger');
            }
            header('Location: ' . BASEURL . '/Admin/Schedules/shifts');
            exit;
        }
    }

    public function shifts_update($id) {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            try {
                $sql = "UPDATE work_shifts 
                        SET shift_name = :name, shift_code = :code, start_time = :start, 
                            end_time = :end, late_tolerance_minutes = :tol, is_active = :active 
                        WHERE id = :id";
                
                $this->db->query($sql);
                $this->db->bind(':id', $id);
                $this->db->bind(':name', $_POST['shift_name']);
                $this->db->bind(':code', strtoupper($_POST['shift_code']));
                $this->db->bind(':start', $_POST['start_time']);
                $this->db->bind(':end', $_POST['end_time']);
                $this->db->bind(':tol', $_POST['late_tolerance_minutes'] ?? 15);
                $this->db->bind(':active', isset($_POST['is_active']) ? 1 : 0);
                
                $this->db->execute();
                
                Flasher::setFlash('Data shift berhasil diupdate', 'success');
            } catch (Exception $e) {
                Flasher::setFlash('Gagal update shift: ' . $e->getMessage(), 'danger');
            }
            header('Location: ' . BASEURL . '/Admin/Schedules/shifts');
            exit;
        }
    }

    public function shifts_delete($id) {
        try {
            $this->db->query("DELETE FROM work_shifts WHERE id = :id");
            $this->db->bind(':id', $id);
            $this->db->execute();
            Flasher::setFlash('Data shift berhasil dihapus', 'success');
        } catch (Exception $e) {
            Flasher::setFlash('Gagal menghapus shift (Kemungkinan masih dipakai di jadwal).', 'danger');
        }
        header('Location: ' . BASEURL . '/Admin/Schedules/shifts');
        exit;
    }

    // ==========================================
    // CRUD HARI LIBUR & CUTI BERSAMA
    // ==========================================

    public function holidays_store() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            try {
                $sql = "INSERT INTO holidays (holiday_date, holiday_name, holiday_type, description) VALUES (:date, :name, :type, :desc)";
                $this->db->query($sql);
                $this->db->bind(':date', $_POST['holiday_date']);
                $this->db->bind(':name', $_POST['holiday_name']);
                $this->db->bind(':type', $_POST['holiday_type']);
                $this->db->bind(':desc', $_POST['description']);
                $this->db->execute();
                
                Flasher::setFlash('Hari libur berhasil ditambahkan', 'success');
            } catch (Exception $e) {
                Flasher::setFlash('Gagal menambah hari libur: ' . $e->getMessage(), 'danger');
            }
            $year = date('Y', strtotime($_POST['holiday_date']));
            header('Location: ' . BASEURL . '/Admin/Schedules/holidays?year=' . $year);
            exit;
        }
    }

    public function holidays_update($id) {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            try {
                $sql = "UPDATE holidays SET holiday_date=:date, holiday_name=:name, holiday_type=:type, description=:desc WHERE id=:id";
                $this->db->query($sql);
                $this->db->bind(':id', $id);
                $this->db->bind(':date', $_POST['holiday_date']);
                $this->db->bind(':name', $_POST['holiday_name']);
                $this->db->bind(':type', $_POST['holiday_type']);
                $this->db->bind(':desc', $_POST['description']);
                $this->db->execute();
                
                Flasher::setFlash('Hari libur berhasil diupdate', 'success');
            } catch (Exception $e) {
                Flasher::setFlash('Gagal update hari libur: ' . $e->getMessage(), 'danger');
            }
            $year = date('Y', strtotime($_POST['holiday_date']));
            header('Location: ' . BASEURL . '/Admin/Schedules/holidays?year=' . $year);
            exit;
        }
    }

    public function holidays_delete($id) {
        try {
            $holiday = $this->db->fetchOne("SELECT holiday_date FROM holidays WHERE id=:id", [':id' => $id]);
            $year = $holiday ? date('Y', strtotime($holiday['holiday_date'])) : date('Y');

            $this->db->query("DELETE FROM holidays WHERE id=:id");
            $this->db->bind(':id', $id);
            $this->db->execute();
            
            Flasher::setFlash('Hari libur berhasil dihapus', 'success');
        } catch (Exception $e) {
            Flasher::setFlash('Gagal menghapus hari libur: ' . $e->getMessage(), 'danger');
            $year = date('Y');
        }
        header('Location: ' . BASEURL . '/Admin/Schedules/holidays?year=' . $year);
        exit;
    }

    public function holidays_sync_api() {
        $year = $_GET['year'] ?? date('Y');
        
        try {
            // Menggunakan API baru yang lebih akurat
            $apiUrl = "https://libur.deno.dev/api?year=" . $year;
            
            // Eksekusi cURL
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $apiUrl);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_USERAGENT, 'HRIS-App/1.2');
            curl_setopt($ch, CURLOPT_TIMEOUT, 10);
            
            $response = curl_exec($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);
            
            if ($httpCode !== 200 || !$response) {
                throw new Exception("Gagal terhubung ke API libur.deno.dev (HTTP $httpCode).");
            }
            
            $data = json_decode($response, true);
            if (!is_array($data)) {
                throw new Exception("Format respon API tidak valid.");
            }

            // Kumpulkan semua tanggal valid dari API
            $api_dates = [];
            foreach ($data as $item) {
                if (!empty($item['date'])) {
                    $api_dates[] = $item['date'];
                }
            }

            // 1. AUTO-HEALING: Hapus sisa tanggal dari API lama yang salah / sudah digeser
            // Kita cuma ngehapus yang ada tag 'Auto Sync API'. Libur buatan admin AMAN.
            $existing_auto = $this->db->fetchAll("SELECT id, holiday_date FROM holidays WHERE YEAR(holiday_date) = :year AND description LIKE '%Auto Sync API%'", [':year' => $year]);
            
            $deleted = 0;
            foreach ($existing_auto as $eh) {
                if (!in_array($eh['holiday_date'], $api_dates)) {
                    $this->db->query("DELETE FROM holidays WHERE id = :id");
                    $this->db->bind(':id', $eh['id']);
                    $this->db->execute();
                    $deleted++;
                }
            }
            
            $inserted = 0;
            $updated = 0;
            $skipped = 0;
            
            // 2. PROSES SYNC (INSERT / UPDATE)
            foreach ($data as $item) {
                // Schema data libur.deno.dev menggunakan 'date' dan 'name'
                $date = $item['date'] ?? null;
                $name = $item['name'] ?? null;
                
                if (!$date || !$name) continue;
                
                // Klasifikasi otomatis jika itu Cuti Bersama
                $is_cuti = (stripos($name, 'cuti bersama') !== false);
                $type = $is_cuti ? 'company' : 'national'; 
                $desc = $is_cuti ? 'Cuti Bersama (Auto Sync API)' : 'Libur Nasional (Auto Sync API)';
                
                // Cek apakah tanggal ini sudah ada di DB
                $check = $this->db->fetchOne("SELECT id, description FROM holidays WHERE holiday_date = :date", [':date' => $date]);
                
                if (!$check) {
                    // BELUM ADA -> Insert Baru
                    $this->db->query("INSERT INTO holidays (holiday_date, holiday_name, holiday_type, description) VALUES (:date, :name, :type, :desc)");
                    $this->db->bind(':date', $date);
                    $this->db->bind(':name', $name);
                    $this->db->bind(':type', $type);
                    $this->db->bind(':desc', $desc);
                    $this->db->execute();
                    $inserted++;
                } else {
                    // SUDAH ADA -> Cek apakah murni dari API
                    if (strpos($check['description'], 'Auto Sync API') !== false) {
                        // Murni dari API -> Update nama jika ada perubahan ejaan dari pemerintah
                        $this->db->query("UPDATE holidays SET holiday_name = :name, holiday_type = :type, description = :desc WHERE id = :id");
                        $this->db->bind(':id', $check['id']);
                        $this->db->bind(':name', $name);
                        $this->db->bind(':type', $type);
                        $this->db->bind(':desc', $desc);
                        $this->db->execute();
                        $updated++;
                    } else {
                        // SUDAH DIEDIT ADMIN -> Jangan diganggu (Skip)
                        $skipped++;
                    }
                }
            }
            
            $pesan = "Sync API Sukses! $inserted Ditambahkan, $updated Diperbarui, $skipped Dilewati.";
            if ($deleted > 0) $pesan .= " (Menghapus $deleted tgl lama yg digeser pemerintah).";

            Flasher::setFlash($pesan, 'success');
            
        } catch (Exception $e) {
            Flasher::setFlash('Sync API Gagal: ' . $e->getMessage(), 'danger');
        }
        
        header('Location: ' . BASEURL . '/Admin/Schedules/holidays?year=' . $year);
        exit;
    }
}