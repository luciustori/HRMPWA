<?php
// File: public/api/inbox/get_users.php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");
require_once '../config/database.php';

try {
    $db = (new Database())->getConnection();
    $exclude_id = $_GET['exclude_id'] ?? 0;

    // Ambil user aktif, urutkan berdasarkan nama
    $sql = "SELECT u.id, e.first_name, e.last_name, e.position, d.department_name
            FROM users u
            JOIN employees e ON u.employee_id = e.id
            LEFT JOIN departments d ON e.department_id = d.id
            WHERE u.is_active = 1 AND u.id != ?
            ORDER BY e.first_name ASC";
            
    $stmt = $db->prepare($sql);
    $stmt->execute([$exclude_id]);
    $users = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $data = [];
    foreach($users as $u) {
        $data[] = [
            'id' => $u['id'],
            'name' => $u['first_name'] . ' ' . $u['last_name'],
            'info' => ($u['department_name'] ?? '-') . ' • ' . ($u['position'] ?? 'Staff')
        ];
    }

    echo json_encode(["status" => "success", "data" => $data]);

} catch (Exception $e) {
    echo json_encode(["status" => "error", "message" => $e->getMessage()]);
}
?>