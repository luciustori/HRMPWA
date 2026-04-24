<?php
// File: public/api/announcements/get_detail.php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");
require_once '../config/database.php';

try {
    $db = (new Database())->getConnection();
    $id = $_GET['id'] ?? 0;

    // FIX: Join ke tabel employees untuk dapat nama asli
    $stmt = $db->prepare("SELECT a.*, 
                                 u.username, 
                                 e.first_name, e.last_name, e.position
                          FROM announcements a 
                          LEFT JOIN users u ON a.created_by = u.id 
                          LEFT JOIN employees e ON u.employee_id = e.id
                          WHERE a.id = ?");
    $stmt->execute([$id]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    if(!$row) throw new Exception("Data tidak ditemukan");

    // Logic Nama Penulis
    $authorName = $row['username'] ?? 'Admin'; // Default username/NIK
    if (!empty($row['first_name'])) {
        $authorName = $row['first_name'] . ' ' . $row['last_name'];
    }
    
    // Opsional: Tambah jabatan jika ada
    $authorInfo = $row['position'] ?? 'Administrator';

    // Format output
    $data = [
        'id' => $row['id'],
        'title' => $row['title'],
        'content' => $row['content'], 
        'date' => date('l, d F Y', strtotime($row['created_at'])),
        'time' => date('H:i', strtotime($row['created_at'])),
        'author' => $authorName, // Sekarang berisi Nama Lengkap
        'author_role' => $authorInfo,
        'attachment' => $row['attachment'],
        'is_event' => ($row['is_event'] == 1),
        'event_detail' => null
    ];

    if ($data['is_event']) {
        $data['event_detail'] = [
            'date' => date('d F Y', strtotime($row['event_date'])),
            'time' => substr($row['event_time'], 0, 5),
            'location' => $row['event_location'] ?? 'Online / Kantor'
        ];
    }

    echo json_encode(["status" => "success", "data" => $data]);

} catch (Exception $e) {
    echo json_encode(["status" => "error", "message" => $e->getMessage()]);
}
?>