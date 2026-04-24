<?php
// File: api/profile/get_personal_data.php

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') { http_response_code(200); exit(); }

// 1. Load Database Config
if (file_exists('../config/database.php')) require_once '../config/database.php';
elseif (file_exists('../../api/config/database.php')) require_once '../../api/config/database.php';
else require_once '../../config/database.php';

$user_id = $_GET['user_id'] ?? 0;

if (empty($user_id)) {
    echo json_encode(["status" => false, "message" => "User ID diperlukan"]);
    exit();
}

try {
    $database = new Database();
    $db = $database->getConnection();

    // 2. QUERY COMPLEX (JOIN Users -> Employees -> Departments)
    // Kita ambil semua kolom employees (e.*) dan nama departemen
    $query = "SELECT 
                e.*,
                d.department_name,
                u.username -- Tambahan info login
              FROM users u
              JOIN employees e ON u.employee_id = e.id
              LEFT JOIN departments d ON e.department_id = d.id
              WHERE u.id = ?";

    $stmt = $db->prepare($query);
    $stmt->execute([$user_id]);
    $data = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$data) {
        echo json_encode(["status" => false, "message" => "Data karyawan tidak ditemukan"]);
        exit();
    }

    // 3. FORMATTING RESPONSE (Biar rapi di Frontend)
    $response = [
        "status" => true,
        "data" => [
            // BAGIAN A: Informasi Pekerjaan (Read Only)
            "job_info" => [
                "nik" => $data['employee_number'],
                "position" => $data['position'] ?? 'Staff',
                "department" => $data['department_name'] ?? '-',
                "hire_date" => $data['hire_date'],
                "status" => "Active", // Hardcode sementara atau ambil dari kolom status
                "leave_balance" => $data['annual_leave_balance'] ?? 0
            ],
            // BAGIAN B: Data Pribadi (Editable)
            "personal_info" => [
                "first_name" => $data['first_name'],
                "last_name" => $data['last_name'],
                "email" => $data['email'],
                "phone" => $data['phone'],
                "address" => $data['address'],
                "photo" => $data['profile_photo_path']
            ],
            // BAGIAN C: Data Keuangan (Read Only)
            "financial_info" => [
                "bank_name" => $data['bank_name'] ?? '-',
                "account_number" => $data['bank_account_number'] ?? '-',
                "account_holder" => $data['bank_account_name'] ?? '-',
                "npwp" => $data['npwp'] ?? '-'
            ]
        ]
    ];

    echo json_encode($response);

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(["status" => false, "message" => "Error: " . $e->getMessage()]);
}
?>