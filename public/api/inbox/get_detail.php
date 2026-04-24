<?php
// File: public/api/inbox/get_detail.php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");
require_once '../config/database.php';

try {
    $db = (new Database())->getConnection();
    
    $msg_id = $_GET['id'] ?? 0;
    $user_id = $_GET['user_id'] ?? 0;

    if(empty($msg_id) || empty($user_id)) throw new Exception("Invalid params");

    // 1. Ambil Pesan Utama
    $sql = "SELECT m.*, 
                   u.username as sender_username, e.first_name, e.last_name
            FROM messages m
            JOIN users u ON m.sender_id = u.id
            LEFT JOIN employees e ON u.employee_id = e.id
            WHERE m.id = ? AND (m.sender_id = ? OR m.recipient_id = ?)";
    
    $stmt = $db->prepare($sql);
    $stmt->execute([$msg_id, $user_id, $user_id]);
    $msg = $stmt->fetch(PDO::FETCH_ASSOC);

    if(!$msg) throw new Exception("Pesan tidak ditemukan");

    // 2. Tandai Read jika saya adalah penerima
    if ($msg['recipient_id'] == $user_id && $msg['is_read'] == 0) {
        $upd = $db->prepare("UPDATE messages SET is_read = 1 WHERE id = ?");
        $upd->execute([$msg_id]);
    }

    // 3. Ambil Balasan (Thread)
    $sqlReplies = "SELECT r.*, 
                          u.username as sender_username, e.first_name, e.last_name
                   FROM messages r
                   JOIN users u ON r.sender_id = u.id
                   LEFT JOIN employees e ON u.employee_id = e.id
                   WHERE r.parent_id = ?
                   ORDER BY r.created_at ASC";
    $stmtRep = $db->prepare($sqlReplies);
    $stmtRep->execute([$msg_id]);
    $replies = $stmtRep->fetchAll(PDO::FETCH_ASSOC);

    // 4. Format Output
    $senderName = !empty($msg['first_name']) ? $msg['first_name'] . ' ' . $msg['last_name'] : $msg['sender_username'];
    
    $result = [
        'id' => $msg['id'],
        'subject' => $msg['subject'],
        'body' => $msg['body'],
        'date' => date('d M Y, H:i', strtotime($msg['created_at'])),
        'sender_name' => $senderName,
        'sender_initial' => strtoupper(substr($senderName, 0, 1)),
        'attachment' => $msg['attachment'],
        'is_me' => ($msg['sender_id'] == $user_id),
        'replies' => []
    ];

    foreach($replies as $r) {
        $rName = !empty($r['first_name']) ? $r['first_name'] . ' ' . $r['last_name'] : $r['sender_username'];
        $result['replies'][] = [
            'id' => $r['id'],
            'body' => $r['body'],
            'date' => date('d M, H:i', strtotime($r['created_at'])),
            'sender_name' => $rName,
            'is_me' => ($r['sender_id'] == $user_id),
            'attachment' => $r['attachment']
        ];
    }

    // Tentukan ID lawan bicara untuk keperluan Reply
    $result['reply_recipient_id'] = ($msg['sender_id'] == $user_id) ? $msg['recipient_id'] : $msg['sender_id'];

    echo json_encode(["status" => "success", "data" => $result]);

} catch (Exception $e) {
    echo json_encode(["status" => "error", "message" => $e->getMessage()]);
}
?>