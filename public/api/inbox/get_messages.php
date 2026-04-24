<?php
// File: public/api/inbox/get_messages.php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");
require_once '../config/database.php';

try {
    $db = (new Database())->getConnection();
    $user_id = $_GET['user_id'] ?? 0;
    $folder = $_GET['folder'] ?? 'inbox'; // Menangkap parameter dari Tab frontend

    if(empty($user_id)) {
        throw new Exception("User ID Required");
    }

    $messages = [];

    if ($folder === 'inbox') {
        // KOTAK MASUK: Menampilkan thread dimana user adalah penerima pesan utama,
        // ATAU user adalah penerima dari salah satu balasan (reply) di thread tersebut.
        $query = "SELECT p.id, p.subject, p.attachment,
                         u.username as sender_username, 
                         e.first_name, e.last_name,
                         (SELECT body FROM messages WHERE id = p.id OR parent_id = p.id ORDER BY created_at DESC LIMIT 1) as last_body,
                         (SELECT created_at FROM messages WHERE id = p.id OR parent_id = p.id ORDER BY created_at DESC LIMIT 1) as last_activity,
                         (SELECT COUNT(*) FROM messages WHERE (id = p.id OR parent_id = p.id) AND recipient_id = ? AND is_read = 0) as unread_count
                  FROM messages p
                  JOIN users u ON p.sender_id = u.id
                  LEFT JOIN employees e ON u.employee_id = e.id
                  WHERE p.parent_id IS NULL
                  AND (
                      p.recipient_id = ? 
                      OR p.id IN (SELECT parent_id FROM messages WHERE recipient_id = ?)
                  )
                  ORDER BY last_activity DESC";

        $stmt = $db->prepare($query);
        $stmt->execute([$user_id, $user_id, $user_id]);
        $messages = $stmt->fetchAll(PDO::FETCH_ASSOC);

    } else {
        // TERKIRIM: Menampilkan thread yang DI-INISIASI (dimulai) oleh user.
        // Join ke tabel users/employees difokuskan pada recipient_id untuk tahu NAMA PENERIMA.
        $query = "SELECT p.id, p.subject, p.attachment,
                         u.username as recipient_username, 
                         e.first_name, e.last_name,
                         (SELECT body FROM messages WHERE id = p.id OR parent_id = p.id ORDER BY created_at DESC LIMIT 1) as last_body,
                         (SELECT created_at FROM messages WHERE id = p.id OR parent_id = p.id ORDER BY created_at DESC LIMIT 1) as last_activity
                  FROM messages p
                  JOIN users u ON p.recipient_id = u.id
                  LEFT JOIN employees e ON u.employee_id = e.id
                  WHERE p.parent_id IS NULL AND p.sender_id = ?
                  ORDER BY last_activity DESC";

        $stmt = $db->prepare($query);
        $stmt->execute([$user_id]);
        $messages = $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Formatting Data biar matching sama inbox.html yang udah kita buat
    $data = [];
    foreach($messages as $row) {
        
        if ($folder === 'inbox') {
            $displayName = !empty($row['first_name']) ? $row['first_name'] . ' ' . $row['last_name'] : $row['sender_username'];
        } else {
            $displayName = !empty($row['first_name']) ? $row['first_name'] . ' ' . $row['last_name'] : $row['recipient_username'];
        }
        
        $data[] = [
            'id' => $row['id'],
            'subject' => $row['subject'],
            'preview' => substr(strip_tags($row['last_body']), 0, 60) . '...',
            'sender' => $displayName, 
            'recipient_name' => $displayName, 
            'initial' => strtoupper(substr($displayName, 0, 1)),
            'date' => date('d M H:i', strtotime($row['last_activity'])),
            'is_read' => ($folder === 'inbox') ? ($row['unread_count'] == 0 ? 1 : 0) : 1, // Di tab Terkirim, notif merah (unread) dimatikan
            'has_attachment' => !empty($row['attachment'])
        ];
    }

    echo json_encode(["status" => "success", "data" => $data]);

} catch (Exception $e) {
    echo json_encode(["status" => "error", "message" => $e->getMessage()]);
}
?>