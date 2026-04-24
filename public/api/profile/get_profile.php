<?php
// FILE: public/api/profile/get_profile.php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");

if (file_exists('../config/database.php')) require_once '../config/database.php';
elseif (file_exists('../../config/database.php')) require_once '../../config/database.php';
else require_once '../../config/database.php';

try {
    $db = (new Database())->getConnection();
    $user_id = $_GET['user_id'] ?? 0;

    if(empty($user_id)) { throw new Exception("User ID Required"); }

    // 1. AMBIL DATA USER & EMPLOYEE
    // Pastikan kolom profile_photo_path diambil
    $query = "SELECT 
                u.id, u.username, u.role,
                e.employee_number, 
                e.first_name, 
                e.last_name, 
                e.full_name,
                e.position,
                e.profile_photo_path,
                e.annual_leave_balance
              FROM users u
              JOIN employees e ON u.employee_id = e.id
              WHERE u.id = ?";

    $stmt = $db->prepare($query);
    $stmt->execute([$user_id]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$user) { echo json_encode(["status" => false, "message" => "User not found"]); exit(); }

    // Fix Nama
    $fullName = $user['full_name'];
    if(empty($fullName)) { $fullName = trim($user['first_name'] . ' ' . $user['last_name']); }

    // 2. HITUNG STATISTIK (Realtime dari Attendance)
    $month = date('m'); $year = date('Y');
    
    // Cari Employee ID (Integer) untuk query ke tabel attendance
    $stmtEid = $db->prepare("SELECT employee_id FROM users WHERE id = ?");
    $stmtEid->execute([$user_id]);
    $rowEid = $stmtEid->fetch(PDO::FETCH_ASSOC);
    $realEmployeeId = $rowEid['employee_id'];

    $stmtStats = $db->prepare("SELECT 
        SUM(CASE WHEN status IN ('present', 'on_time') THEN 1 ELSE 0 END) as total_present,
        SUM(CASE WHEN status = 'late' THEN 1 ELSE 0 END) as total_late
        FROM attendance_records 
        WHERE employee_id = ? AND MONTH(attendance_date) = ? AND YEAR(attendance_date) = ?");
    
    $stmtStats->execute([$realEmployeeId, $month, $year]);
    $stats = $stmtStats->fetch(PDO::FETCH_ASSOC);

    $hadir = $stats['total_present'] ?? 0;
    $telat = $stats['total_late'] ?? 0;
    
    // Hitung Persentase (Asumsi 20 hari kerja)
    $persentase = ($hadir / 20) * 100;
    if($persentase > 100) $persentase = 100;

    // 3. RESPONSE
    echo json_encode([
        "status" => "success", 
        "data" => [
            'full_name' => $fullName,
            'position' => $user['position'] ?? 'Karyawan',
            'employee_number' => $user['employee_number'],
            'profile_photo_path' => $user['profile_photo_path'], // <--- INI KUNCINYA
            
            'stats' => [
                'leave_balance' => $user['annual_leave_balance'] ?? 0,
                'late_count' => $telat,
                'attendance_pct' => round($persentase)
            ],
            'kpi' => [ // Dummy KPI
                'grade' => 'B', 'score' => 3.8, 'prod_score' => 90, 'disc_score' => 100
            ]
        ]
    ]);

} catch (Exception $e) {
    echo json_encode(["status" => "error", "message" => $e->getMessage()]);
}
?>