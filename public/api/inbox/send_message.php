<?php
// File: public/api/inbox/send_message.php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");
require_once '../config/database.php';

try {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        throw new Exception("Invalid Method");
    }

    $db = (new Database())->getConnection();

    // Ambil Data POST
    $sender_id = $_POST['sender_id'] ?? 0;
    $recipient_id = $_POST['recipient_id'] ?? 0; // Jika reply, ini ID lawan bicara
    $subject = $_POST['subject'] ?? 'No Subject';
    $body = $_POST['body'] ?? '';
    $parent_id = !empty($_POST['parent_id']) ? $_POST['parent_id'] : null;

    if(empty($sender_id) || empty($body)) {
        throw new Exception("Data tidak lengkap");
    }

    // Handle File Upload (Optional - Simple Version)
    $attachment = null;
    if (isset($_FILES['attachment']) && $_FILES['attachment']['error'] === 0) {
        $targetDir = "../../uploads/messages/";
        if (!file_exists($targetDir)) mkdir($targetDir, 0777, true);
        
        $fileName = time() . '_' . basename($_FILES["attachment"]["name"]);
        if(move_uploaded_file($_FILES["attachment"]["tmp_name"], $targetDir . $fileName)) {
            $attachment = $fileName;
        }
    }

    $sql = "INSERT INTO messages (parent_id, sender_id, recipient_id, subject, body, attachment, is_read, created_at) 
            VALUES (?, ?, ?, ?, ?, ?, 0, NOW())";
    
    $stmt = $db->prepare($sql);
    $stmt->execute([$parent_id, $sender_id, $recipient_id, $subject, $body, $attachment]);

    echo json_encode(["status" => "success", "message" => "Pesan terkirim"]);

} catch (Exception $e) {
    echo json_encode(["status" => "error", "message" => $e->getMessage()]);
}
?>