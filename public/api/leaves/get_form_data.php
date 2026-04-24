<?php
// FILE: public/api/leaves/get_form_data.php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

ini_set('display_errors', 0);
error_reporting(E_ALL);

// 1. KONEKSI DATABASE (Robust)
$dbPath = '../config/database.php';
if (!file_exists($dbPath)) {
    $dbPath = __DIR__ . '/../../../config/database.php';
}
if (!file_exists($dbPath)) {
    echo json_encode(["status" => false, "message" => "Database config not found."]);
    exit;
}
require_once $dbPath;

try {
    $db = (new Database())->getConnection();
    $user_id = $_GET['user_id'] ?? 0;

    // 1. Cari Employee ID & Jatah Cuti Default
    $stmtUser = $db->prepare("SELECT u.employee_id, e.annual_leave_balance FROM users u JOIN employees e ON u.employee_id = e.id WHERE u.id = ?");
    $stmtUser->execute([$user_id]);
    $user = $stmtUser->fetch(PDO::FETCH_ASSOC);
    
    if (!$user) { echo json_encode(["status" => false, "message" => "User invalid"]); exit; }
    $eid = $user['employee_id'];
    $base_quota = $user['annual_leave_balance'] ?? 12;

    // 2. Kalkulasi Pemotongan Cuti Akurat
    $year = date('Y');
    $stmtCuti = $db->prepare("SELECT COALESCE(SUM(lr.total_days), 0) as used_days
                              FROM leave_requests lr
                              JOIN leave_types lt ON lr.leave_type_id = lt.id
                              WHERE lr.employee_id = ? AND lr.status = 'approved' 
                              AND YEAR(lr.start_date) = ? AND lt.leave_code IN ('ANNUAL', 'CUTI_TAHUNAN')");
    $stmtCuti->execute([$eid, $year]);
    $cuti = $stmtCuti->fetch(PDO::FETCH_ASSOC);
    
    $sisa_cuti = max(0, $base_quota - ($cuti['used_days'] ?? 0));

    // 3. Ambil Tipe Ijin (Kecuali Cuti Tahunan & Dinas Luar)
    $sqlTypes = "SELECT id, leave_type_name FROM leave_types 
                 WHERE is_active = 1 
                 AND leave_code NOT IN ('ANNUAL','CUTI_TAHUNAN','DL','DINAS_LUAR')
                 AND leave_type_name NOT LIKE '%Tahunan%'
                 ORDER BY leave_type_name ASC";
    $stmtTypes = $db->query($sqlTypes);
    $permit_types = $stmtTypes->fetchAll(PDO::FETCH_ASSOC);

    // 4. ID Spesial (Biar PWA bisa membedakan formnya)
    $stmtAnn = $db->query("SELECT id FROM leave_types WHERE leave_code IN ('ANNUAL','CUTI_TAHUNAN') LIMIT 1");
    $ann = $stmtAnn->fetch(PDO::FETCH_ASSOC);
    
    $stmtDl = $db->query("SELECT id FROM leave_types WHERE leave_code IN ('DL','DINAS_LUAR') LIMIT 1");
    $dl = $stmtDl->fetch(PDO::FETCH_ASSOC);

    echo json_encode([
        "status" => true,
        "data" => [
            "quota" => $sisa_cuti,
            "special_ids" => [
                "annual" => $ann ? $ann['id'] : 0,
                "dl" => $dl ? $dl['id'] : 0
            ],
            "permit_types" => $permit_types
        ]
    ]);

} catch (Exception $e) {
    echo json_encode(["status" => false, "message" => $e->getMessage()]);
}
?>