<?php
// public/api/attendance/profile_data.php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");

// Matikan display error agar tidak merusak format JSON
ini_set('display_errors', 0);
error_reporting(E_ALL);

try {
    // Sesuaikan path ini dengan dashboard_data.php yang sudah jalan
    // Jika dashboard_data.php pakai '../config/database.php', gunakan yang sama.
    $path_to_db = '../config/database.php';
    
    if (!file_exists($path_to_db)) {
        // Coba path alternatif jika file tidak ketemu (mundur 2 langkah)
        $path_to_db = '../../config/database.php';
    }
    
    if (!file_exists($path_to_db)) {
        throw new Exception("File database.php tidak ditemukan di ../config/ atau ../../config/");
    }

    require_once $path_to_db;

    $db = (new Database())->getConnection();
    
    // Default User ID 1 jika tidak ada parameter
    $user_id = isset($_GET['user_id']) ? $_GET['user_id'] : 1;

    // 1. QUERY UTAMA
    // Pastikan nama kolom 'employee_number' dan 'annual_leave_balance' sesuai info kamu
    $query = "SELECT 
                u.id as user_id,
                u.employee_id, 
                e.full_name, 
                e.position,
                e.employee_number,      
                e.annual_leave_balance  
              FROM users u
              JOIN employees e ON u.employee_id = e.id
              WHERE u.id = ?";
    
    $stmt = $db->prepare($query);
    $stmt->execute([$user_id]);
    $data = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$data) {
        throw new Exception("User ID $user_id tidak ditemukan di database.");
    }

    $eid = $data['employee_id'];

    // 2. QUERY STATISTIK (Hadir & Terlambat Bulan Ini)
    $month = date('m');
    $year = date('Y');

    $queryStats = "SELECT 
        SUM(CASE WHEN status IN ('present', 'on_time') THEN 1 ELSE 0 END) as total_present,
        SUM(CASE WHEN status = 'late' THEN 1 ELSE 0 END) as total_late
        FROM attendance_records 
        WHERE employee_id = ? AND MONTH(attendance_date) = ? AND YEAR(attendance_date) = ?";
        
    $stmtStats = $db->prepare($queryStats);
    $stmtStats->execute([$eid, $month, $year]);
    $stats = $stmtStats->fetch(PDO::FETCH_ASSOC);

    // 3. HITUNG PERSENTASE
    $work_days = (int)date('d'); // Asumsi hari kerja = tanggal hari ini
    $pct = 0;
    if ($work_days > 0) {
        $present = (int)($stats['total_present'] ?? 0);
        $pct = round(($present / $work_days) * 100);
        if ($pct > 100) $pct = 100;
    }

    // 4. GENERATE RESPONSE
    echo json_encode([
        "status" => true,
        "data" => [
            "full_name" => $data['full_name'],
            "position"  => $data['position'] ?? 'Karyawan',
            "nik"       => $data['employee_number'] ?? '-',
            // Gunakan API UI Avatars biar otomatis ada fotonya kalau tidak ada upload
            "photo_url" => "https://ui-avatars.com/api/?name=" . urlencode($data['full_name']) . "&background=0D8ABC&color=fff&size=128",
            "stats" => [
                "leave_balance" => (int)($data['annual_leave_balance'] ?? 0),
                "late_count"    => (int)($stats['total_late'] ?? 0),
                "attendance_pct"=> $pct
            ]
        ]
    ]);

} catch (Exception $e) {
    // Tangkap error dan kirim sebagai JSON
    http_response_code(500);
    echo json_encode([
        "status" => false, 
        "message" => $e->getMessage()
    ]);
}
?>