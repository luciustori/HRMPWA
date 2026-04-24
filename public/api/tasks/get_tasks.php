<?php
// FILE: public/api/tasks/get_tasks.php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

require_once '../config/database.php';

try {
    $db = (new Database())->getConnection();
    $user_id = $_GET['user_id'] ?? 0;
    $filter  = $_GET['filter'] ?? 'active'; // 'active' or 'completed'
    
    // PARAMETER PERIODE
    $month = $_GET['month'] ?? date('n');
    $year  = $_GET['year'] ?? date('Y');

    if (empty($user_id)) throw new Exception("User ID Required");

    // --- LOGIC CUTOFF (SAMA DENGAN STATS) ---
    $endDate = "$year-$month-24";
    $prevMonth = $month - 1;
    $prevYear  = $year;
    if ($prevMonth == 0) { 
        $prevMonth = 12;
        $prevYear  = $year - 1;
    }
    $startDate = "$prevYear-$prevMonth-25";

    // --- FILTER STATUS ---
    // Pastikan ini match dengan tab di Frontend
    $statusClause = "";
    if ($filter == 'completed') {
        $statusClause = "t.status IN ('completed', 'submitted', 'review')";
    } else {
        $statusClause = "t.status IN ('pending', 'in_progress')";
    }

    // --- QUERY ---
    // Menggunakan DATE() untuk memastikan perbandingan aman
    $sql = "SELECT t.id, t.task_code, t.title, t.priority, t.due_date, t.status, t.created_at,
                   u.username as assignor_name,
                   (SELECT COUNT(*) FROM task_checklist WHERE task_id = t.id) as total_check,
                   (SELECT COUNT(*) FROM task_checklist WHERE task_id = t.id AND is_completed = 1) as done_check
            FROM tasks t
            LEFT JOIN users u ON t.assigned_by = u.id
            WHERE t.assigned_to = :uid 
            AND $statusClause
            AND (DATE(t.due_date) BETWEEN :start AND :end) 
            ORDER BY t.priority DESC, t.due_date ASC";

    $stmt = $db->prepare($sql);
    $stmt->execute([
        ':uid'   => $user_id,
        ':start' => $startDate,
        ':end'   => $endDate
    ]);
    $tasks = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode(["status" => "success", "data" => $tasks]);

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(["status" => "error", "message" => $e->getMessage()]);
}
?>