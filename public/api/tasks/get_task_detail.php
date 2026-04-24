<?php
// public/api/tasks/get_task_detail.php

ini_set('display_errors', 0);
error_reporting(E_ALL);

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

$dbPath = '../config/database.php';
if (!file_exists($dbPath)) { $dbPath = __DIR__ . '/../../../config/database.php'; }
require_once $dbPath;

try {
    $db = (new Database())->getConnection();
    $task_id = $_GET['id'] ?? 0;

    if (!$task_id) {
        echo json_encode(["status" => false, "message" => "ID Tugas tidak valid"]);
        exit;
    }

    // 1. AMBIL DETAIL TUGAS (Sesuai Controller Staff/Tasks.php)
    $sql = "
        SELECT t.id, t.task_code, t.title, t.description, t.priority, t.status, 
               t.start_date, t.due_date, t.created_at,
               
               -- [FIX] Gunakan completion_percentage aliased as progress biar frontend aman
               COALESCE(t.completion_percentage, 0) as progress,
               
               COALESCE(tc.category_name, 'General') as category_name, 
               COALESCE(tc.color, '#94a3b8') as cat_color,
               
               -- Info Pembuat (Creator)
               COALESCE(e_creator.first_name, u_creator.username, 'System') as creator_name,
               
               -- Info PIC (Assigned To)
               COALESCE(e_pic.first_name, u_pic.username) as pic_name
               
        FROM tasks t
        LEFT JOIN task_categories tc ON t.category_id = tc.id
        -- Join ke Pembuat
        LEFT JOIN users u_creator ON t.assigned_by = u_creator.id
        LEFT JOIN employees e_creator ON u_creator.employee_id = e_creator.id
        -- Join ke PIC
        LEFT JOIN users u_pic ON t.assigned_to = u_pic.id
        LEFT JOIN employees e_pic ON u_pic.employee_id = e_pic.id
        WHERE t.id = ?
    ";
    
    $stmt = $db->prepare($sql);
    $stmt->execute([$task_id]);
    $task = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$task) {
        echo json_encode(["status" => false, "message" => "Tugas tidak ditemukan"]);
        exit;
    }

    // 2. AMBIL CHECKLIST
    $sqlCheck = "SELECT * FROM task_checklist WHERE task_id = ? ORDER BY sort_order ASC";
    $stmtCheck = $db->prepare($sqlCheck);
    $stmtCheck->execute([$task_id]);
    $checklist = $stmtCheck->fetchAll(PDO::FETCH_ASSOC);

    // 3. AMBIL TIME LOGS (Join User)
    $sqlLogs = "
        SELECT l.*, u.username 
        FROM task_time_logs l 
        JOIN users u ON l.user_id = u.id 
        WHERE l.task_id = ? 
        ORDER BY l.created_at DESC
    ";
    $stmtLogs = $db->prepare($sqlLogs);
    $stmtLogs->execute([$task_id]);
    $timeLogs = $stmtLogs->fetchAll(PDO::FETCH_ASSOC);

    // 4. AMBIL KOMENTAR (Join User & Employee)
    $sqlComm = "
        SELECT c.*, u.username, e.first_name
        FROM task_comments c
        JOIN users u ON c.user_id = u.id
        LEFT JOIN employees e ON u.employee_id = e.id
        WHERE c.task_id = ?
        ORDER BY c.created_at DESC
    ";
    $stmtComm = $db->prepare($sqlComm);
    $stmtComm->execute([$task_id]);
    $comments = $stmtComm->fetchAll(PDO::FETCH_ASSOC);

    // Output JSON
    echo json_encode([
        "status" => true,
        "task" => $task,
        "checklist" => $checklist,
        "time_logs" => $timeLogs,
        "comments" => $comments
    ]);

} catch (Exception $e) {
    echo json_encode(["status" => false, "message" => "SQL Error: " . $e->getMessage()]);
}
?>