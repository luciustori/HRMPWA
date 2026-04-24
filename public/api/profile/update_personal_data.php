<?php
// File: api/profile/update_personal_data.php

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') { http_response_code(200); exit(); }

if (file_exists('../config/database.php')) require_once '../config/database.php';
elseif (file_exists('../../api/config/database.php')) require_once '../../api/config/database.php';
else require_once '../../config/database.php';

$data = json_decode(file_get_contents("php://input"));

// 1. Validasi Input
if (empty($data->user_id)) {
    echo json_encode(["status" => false, "message" => "User ID Wajib!"]);
    exit();
}

try {
    $database = new Database();
    $db = $database->getConnection();

    // 2. Cari Employee ID dulu (Karena parameter kita User ID)
    $stmtCheck = $db->prepare("SELECT employee_id FROM users WHERE id = ?");
    $stmtCheck->execute([$data->user_id]);
    $userRow = $stmtCheck->fetch(PDO::FETCH_ASSOC);

    if (!$userRow) {
        echo json_encode(["status" => false, "message" => "User invalid"]);
        exit();
    }

    $empId = $userRow['employee_id'];

    // 3. Eksekusi Update (Hanya 3 kolom ini yang boleh berubah via Mobile)
    $query = "UPDATE employees SET 
                email = :email, 
                phone = :phone, 
                address = :address 
              WHERE id = :id";
    
    $stmt = $db->prepare($query);
    
    // Binding biar aman dari SQL Injection
    $stmt->bindParam(':email', $data->email);
    $stmt->bindParam(':phone', $data->phone);
    $stmt->bindParam(':address', $data->address);
    $stmt->bindParam(':id', $empId);

    if ($stmt->execute()) {
        echo json_encode(["status" => true, "message" => "Data berhasil diperbarui!"]);
    } else {
        echo json_encode(["status" => false, "message" => "Gagal menyimpan data"]);
    }

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(["status" => false, "message" => "Server Error: " . $e->getMessage()]);
}
?>