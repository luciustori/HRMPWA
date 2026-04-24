<?php
// File: api/payroll/get_detail.php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
require_once '../config/database.php';

try {
    $db = (new Database())->getConnection();
    $trx_id = $_GET['transaction_id'] ?? 0;

    // 1. Ambil Data Header Gaji & Info Perusahaan
    $queryHeader = "SELECT 
                pt.*, pp.period_name, e.full_name, e.employee_number, e.position,
                c.company_name, c.address as company_address, c.logo_path
              FROM payroll_transactions pt
              JOIN payroll_periods pp ON pt.period_id = pp.id
              JOIN employees e ON pt.employee_id = e.id
              LEFT JOIN companies c ON e.company_id = c.id
              WHERE pt.id = ?";

    $stmt = $db->prepare($queryHeader);
    $stmt->execute([$trx_id]);
    $header = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$header) {
        echo json_encode(["status" => false, "message" => "Data tidak ditemukan"]);
        exit();
    }

    // 2. Ambil Rincian Komponen (Earnings & Deductions)
    $stmtDetails = $db->prepare("SELECT component_name, component_type, amount 
                                 FROM payroll_transaction_details 
                                 WHERE transaction_id = ?");
    $stmtDetails->execute([$trx_id]);
    $details = $stmtDetails->fetchAll(PDO::FETCH_ASSOC);

    $earnings = [];
    $deductions = [];

    foreach ($details as $item) {
        if ($item['component_type'] == 'earning') {
            $earnings[] = $item;
        } else {
            $deductions[] = $item;
        }
    }

    echo json_encode([
        "status" => "success",
        "header" => $header,
        "earnings" => $earnings,
        "deductions" => $deductions
    ]);

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(["status" => "error", "message" => $e->getMessage()]);
}