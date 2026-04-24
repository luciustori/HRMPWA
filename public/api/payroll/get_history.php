<?php
// File: api/payroll/get_history.php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
require_once '../config/database.php';

try {
    $db = (new Database())->getConnection();
    $user_id = $_GET['user_id'] ?? 0;
    $year = $_GET['year'] ?? date('Y');

    // 1. Ambil Employee ID dari User ID
    $stmtUser = $db->prepare("SELECT employee_id FROM users WHERE id = ?");
    $stmtUser->execute([$user_id]);
    $user = $stmtUser->fetch(PDO::FETCH_ASSOC);

    if (!$user) {
        echo json_encode(["status" => false, "message" => "User tidak ditemukan"]);
        exit();
    }
    $eid = $user['employee_id'];

    // 2. Query History Payroll (Join ke Periode)
    $query = "SELECT 
                pt.id, pt.net_salary, pt.paid_at, pt.status,
                pp.period_name, pp.period_month
              FROM payroll_transactions pt
              JOIN payroll_periods pp ON pt.period_id = pp.id
              WHERE pt.employee_id = ? AND pp.period_year = ? 
              ORDER BY pp.period_month DESC";

    $stmt = $db->prepare($query);
    $stmt->execute([$eid, $year]);
    $history = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode([
        "status" => "success",
        "data" => $history
    ]);

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(["status" => "error", "message" => $e->getMessage()]);
}