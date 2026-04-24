<?php
// FILE: public/api/tasks/get_stats.php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

require_once '../config/database.php';

try {
    $db = (new Database())->getConnection();
    $user_id = $_GET['user_id'] ?? 0;
    
    // Tambahan Filter Bulan & Tahun
    $month   = $_GET['month'] ?? date('m');
    $year    = $_GET['year'] ?? date('Y');

    if (empty($user_id)) throw new Exception("User ID Required");

    // Hitung Statistik berdasarkan Due Date di bulan terpilih
    $sql = "SELECT 
                COUNT(*) as total,
                COALESCE(SUM(CASE WHEN status IN ('pending', 'in_progress') THEN 1 ELSE 0 END), 0) as active,
                COALESCE(SUM(CASE WHEN status IN ('completed', 'submitted', 'review') THEN 1 ELSE 0 END), 0) as completed,
                COALESCE(SUM(CASE WHEN due_date < CURDATE() AND status NOT IN ('completed', 'submitted', 'review') THEN 1 ELSE 0 END), 0) as overdue
            FROM tasks 
            WHERE assigned_to = :uid
              AND MONTH(due_date) = :m
              AND YEAR(due_date) = :y";

    $stmt = $db->prepare($sql);
    $stmt->execute([
        ':uid' => $user_id,
        ':m'   => $month,
        ':y'   => $year
    ]);
    $stats = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$stats) {
        $stats = ['total' => 0, 'active' => 0, 'completed' => 0, 'overdue' => 0];
    }

    // Casting ke Integer
    $cleanStats = [
        'total'     => (int)$stats['total'],
        'active'    => (int)$stats['active'],
        'completed' => (int)$stats['completed'],
        'overdue'   => (int)$stats['overdue']
    ];

    // Hitung Persentase
    $total = $cleanStats['total'] > 0 ? $cleanStats['total'] : 1;
    $percent = round(($cleanStats['completed'] / $total) * 100);

    echo json_encode([
        "status" => "success", 
        "data" => array_merge($cleanStats, ['percent' => $percent])
    ]);

} catch (Exception $e) {
    echo json_encode(["status" => "error", "message" => $e->getMessage()]);
}
?>