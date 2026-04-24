<?php
// FILE: public/api/tasks/add_comment.php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");

require_once '../config/database.php';

try {
    $db = (new Database())->getConnection();
    $data = json_decode(file_get_contents("php://input"));

    if(empty($data->task_id) || empty($data->user_id) || empty($data->comment)) {
        throw new Exception("Komentar kosong");
    }

    $sql = "INSERT INTO task_comments (task_id, user_id, comment, created_at) 
            VALUES (:tid, :uid, :comm, NOW())";
    
    $stmt = $db->prepare($sql);
    $stmt->execute([
        ':tid' => $data->task_id,
        ':uid' => $data->user_id,
        ':comm' => $data->comment
    ]);

    echo json_encode(["status" => "success", "message" => "Komentar terkirim"]);

} catch (Exception $e) {
    echo json_encode(["status" => "error", "message" => $e->getMessage()]);
}
?>