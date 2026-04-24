<?php
// FILE: public/api/tasks/get_detail.php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

require_once '../config/database.php';

try {
    $db = (new Database())->getConnection();
    $task_id = $_GET['id'] ?? 0;

    // 1. Info Utama (JOIN ke Employees untuk Nama Pemberi Tugas)
    $stmt = $db->prepare("SELECT t.*, u.username, e.first_name, e.last_name 
                          FROM tasks t 
                          LEFT JOIN users u ON t.assigned_by = u.id 
                          LEFT JOIN employees e ON u.employee_id = e.id
                          WHERE t.id = ?");
    $stmt->execute([$task_id]);
    $task = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$task) throw new Exception("Tugas tidak ditemukan");

    // Logic: Jika ada nama di employee, pakai itu. Jika tidak, pakai username (NIK)
    $assignor = $task['username'];
    if (!empty($task['first_name'])) {
        $assignor = $task['first_name'] . ' ' . $task['last_name'];
    }
    // Masukkan ke key 'assignor_name' supaya HTML tidak perlu diubah
    $task['assignor_name'] = $assignor;


    // 2. Checklist (Tidak berubah)
    $stmtCheck = $db->prepare("SELECT * FROM task_checklist WHERE task_id = ? ORDER BY created_at ASC");
    $stmtCheck->execute([$task_id]);
    $checklist = $stmtCheck->fetchAll(PDO::FETCH_ASSOC);


    // 3. Logs (JOIN ke Employees)
    $stmtLogs = $db->prepare("SELECT ttl.*, u.username, e.first_name, e.last_name 
                              FROM task_time_logs ttl 
                              JOIN users u ON ttl.user_id = u.id 
                              LEFT JOIN employees e ON u.employee_id = e.id
                              WHERE ttl.task_id = ? 
                              ORDER BY ttl.created_at DESC");
    $stmtLogs->execute([$task_id]);
    $logs = $stmtLogs->fetchAll(PDO::FETCH_ASSOC);

    // Format Nama di Logs
    foreach($logs as &$log) {
        $name = $log['username'];
        if (!empty($log['first_name'])) {
            $name = $log['first_name'] . ' ' . $log['last_name'];
        }
        $log['username'] = $name; // Override key username dengan Nama Lengkap
    }


    // 4. Komentar/Diskusi (JOIN ke Employees)
    $stmtComm = $db->prepare("SELECT tc.*, u.username, e.first_name, e.last_name 
                              FROM task_comments tc 
                              JOIN users u ON tc.user_id = u.id 
                              LEFT JOIN employees e ON u.employee_id = e.id
                              WHERE tc.task_id = ? 
                              ORDER BY tc.created_at ASC");
    $stmtComm->execute([$task_id]);
    $comments = $stmtComm->fetchAll(PDO::FETCH_ASSOC);

    // Format Nama di Komentar
    foreach($comments as &$comm) {
        $name = $comm['username'];
        if (!empty($comm['first_name'])) {
            $name = $comm['first_name'] . ' ' . $comm['last_name'];
        }
        $comm['username'] = $name; // Override key username dengan Nama Lengkap
    }

    echo json_encode([
        "status" => "success", 
        "data" => $task, 
        "checklist" => $checklist,
        "logs" => $logs,
        "comments" => $comments
    ]);

} catch (Exception $e) {
    echo json_encode(["status" => "error", "message" => $e->getMessage()]);
}
?>