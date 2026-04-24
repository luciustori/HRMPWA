<?php
// File: public/api/inbox/get_messages.php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");
require_once '../config/database.php';

try {
    $db = (new Database())->getConnection();
    $user_id = $_GET['user_id'] ?? 0;

    if(empty($user_id)) {
        throw new Exception("User ID Required");
    }

    // Query mirip dengan Controller Messages.php tapi optimized buat JSON
    $query = "SELECT m.id, m.subject, m.body, m.is_read, m.created_at, m.attachment,
                     u.username as sender_username, 
                     e.first_name, e.last_name
              FROM messages m
              JOIN users u ON m.sender_id = u.id
              LEFT JOIN employees e ON u.employee_id = e.id
              WHERE m.recipient_id = ? AND m.parent_id IS NULL
              ORDER BY m.created_at DESC";

    $stmt = $db->prepare($query);
    $stmt->execute([$user_id]);
    $messages = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Formatting Data biar enak dibaca di JS
    $data = [];
    foreach($messages as $row) {
        $sender = !empty($row['first_name']) ? $row['first_name'] . ' ' . $row['last_name'] : $row['sender_username'];
        
        $data[] = [
            'id' => $row['id'],
            'subject' => $row['subject'],
            'preview' => substr(strip_tags($row['body']), 0, 60) . '...',
            'sender' => $sender,
            'initial' => strtoupper(substr($sender, 0, 1)),
            'date' => date('d M H:i', strtotime($row['created_at'])),
            'is_read' => $row['is_read'],
            'has_attachment' => !empty($row['attachment'])
        ];
    }

    echo json_encode(["status" => "success", "data" => $data]);

} catch (Exception $e) {
    echo json_encode(["status" => "error", "message" => $e->getMessage()]);
}
?>