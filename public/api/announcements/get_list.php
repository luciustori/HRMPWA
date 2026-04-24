<?php
// File: public/api/announcements/get_list.php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");
require_once '../config/database.php';

try {
    $db = (new Database())->getConnection();
    $user_id = $_GET['user_id'] ?? 0;

    if(empty($user_id)) throw new Exception("User ID Required");

    // 1. Ambil Data User (untuk tahu Departemen & Employee ID)
    $stmtUser = $db->prepare("SELECT u.id, u.employee_id, e.department_id 
                              FROM users u 
                              JOIN employees e ON u.employee_id = e.id 
                              WHERE u.id = ?");
    $stmtUser->execute([$user_id]);
    $user = $stmtUser->fetch(PDO::FETCH_ASSOC);

    if(!$user) throw new Exception("User not found");

    $empId = $user['employee_id'];
    $deptId = $user['department_id'];

    // 2. Query Pengumuman (Filter Target)
    // - target_type = 'all_company' (atau kosong/default)
    // - target_type = 'department' AND target_id = User Dept ID
    // - target_type = 'specific' AND FIND_IN_SET(User ID, target_employee_ids)
    
    $sql = "SELECT id, title, content, type, created_at, is_event, event_date 
            FROM announcements 
            WHERE 
               (target_type IS NULL OR target_type = 'all_company' OR target_type = 'all') 
               OR (target_type = 'department' AND target_id = ?)
               OR (target_type = 'specific' AND FIND_IN_SET(?, target_employee_ids))
            ORDER BY created_at DESC 
            LIMIT 50";

    $stmt = $db->prepare($sql);
    $stmt->execute([$deptId, $user_id]);
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $data = [];
    foreach($rows as $r) {
        // Tentukan warna badge berdasarkan tipe
        $badgeColor = 'bg-blue-100 text-blue-700'; // Info default
        if($r['type'] == 'warning') $badgeColor = 'bg-orange-100 text-orange-700';
        if($r['type'] == 'danger') $badgeColor = 'bg-red-100 text-red-700';
        if($r['type'] == 'success') $badgeColor = 'bg-green-100 text-green-700';

        $data[] = [
            'id' => $r['id'],
            'title' => $r['title'],
            'excerpt' => substr(strip_tags($r['content']), 0, 80) . '...',
            'date' => date('d M Y', strtotime($r['created_at'])),
            'type_label' => ucfirst($r['type']),
            'badge_color' => $badgeColor,
            'is_event' => $r['is_event'] == 1,
            'event_date_fmt' => $r['is_event'] ? date('d M', strtotime($r['event_date'])) : ''
        ];
    }

    echo json_encode(["status" => "success", "data" => $data]);

} catch (Exception $e) {
    echo json_encode(["status" => "error", "message" => $e->getMessage()]);
}
?>