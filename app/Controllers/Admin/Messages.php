<?php
// File: app/Controllers/Admin/Messages.php

class Messages extends Controller {
    
    private $db;

    public function __construct() {
        parent::__construct();
        if (!session_id()) session_start();

        if (!isset($_SESSION['user_id'])) {
            header('Location: ' . BASEURL . '/Admin/LoginController');
            exit;
        }
        $this->db = new Database;
    }

    // --- 1. INBOX (Pesan Masuk) ---
    public function index() {
        $userId = $_SESSION['user_id'];
        
        // Ambil pesan dimana user ini adalah PENERIMA
        // FIX: Tidak mengambil u.avatar biar gak error
        $query = "SELECT m.*, 
                         u.username as sender_username, 
                         emp.first_name, emp.last_name,
                         (SELECT COUNT(*) FROM messages r WHERE r.parent_id = m.id) as reply_count
                  FROM messages m
                  JOIN users u ON m.sender_id = u.id
                  LEFT JOIN employees emp ON u.employee_id = emp.id
                  WHERE m.recipient_id = :uid AND m.parent_id IS NULL
                  ORDER BY m.created_at DESC";
                  
        $this->db->query($query);
        $this->db->bind(':uid', $userId);
        $messages = $this->db->resultSet();

        $data = [
            'title' => 'Kotak Masuk',
            'content_view' => 'admin/messages/index',
            'messages' => $messages,
            'active_tab' => 'inbox'
        ];
        $this->view('admin/layouts/admin-layout', $data);
    }

    // --- 2. SENT (Pesan Terkirim) ---
    public function sent() {
        $userId = $_SESSION['user_id'];
        
        // Ambil pesan dimana user ini adalah PENGIRIM
        $query = "SELECT m.*, 
                         u.username as recipient_username,
                         emp.first_name, emp.last_name
                  FROM messages m
                  JOIN users u ON m.recipient_id = u.id
                  LEFT JOIN employees emp ON u.employee_id = emp.id
                  WHERE m.sender_id = :uid AND m.parent_id IS NULL
                  ORDER BY m.created_at DESC";
                  
        $this->db->query($query);
        $this->db->bind(':uid', $userId);
        $messages = $this->db->resultSet();

        $data = [
            'title' => 'Pesan Terkirim',
            'content_view' => 'admin/messages/sent', // Pastikan view ini ada (bisa copas index.php)
            'messages' => $messages,
            'active_tab' => 'sent'
        ];
        $this->view('admin/layouts/admin-layout', $data);
    }

    // --- 3. CREATE (Form Tulis Pesan) ---
    public function create() {
        // Ambil Departemen untuk filter user
        $this->db->query("SELECT id, department_name FROM departments ORDER BY department_name ASC");
        $departments = $this->db->resultSet();

        $data = [
            'title' => 'Tulis Pesan',
            'content_view' => 'admin/messages/create',
            'departments' => $departments
        ];
        $this->view('admin/layouts/admin-layout', $data);
    }

    // --- 4. STORE (Proses Kirim) ---
    public function store() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            try {
                $sender_id = $_SESSION['user_id'];
                $recipient_id = $_POST['recipient_id'];
                $subject = $_POST['subject'] ?? 'No Subject';
                $body = $_POST['body'];
                $parent_id = !empty($_POST['parent_id']) ? $_POST['parent_id'] : null;

                // Logic Upload
                $attachmentName = null;
                if (isset($_FILES['attachment']) && $_FILES['attachment']['error'] === 0) {
                    $attachmentName = $this->uploadFile($_FILES['attachment']);
                }

                $query = "INSERT INTO messages (parent_id, sender_id, recipient_id, subject, body, attachment, is_read, created_at) 
                          VALUES (:pid, :sid, :rid, :sub, :body, :att, 0, NOW())";
                
                $this->db->query($query);
                $this->db->bind(':pid', $parent_id);
                $this->db->bind(':sid', $sender_id);
                $this->db->bind(':rid', $recipient_id);
                $this->db->bind(':sub', $subject);
                $this->db->bind(':body', $body);
                $this->db->bind(':att', $attachmentName);

                if ($this->db->execute()) {
                    if($parent_id) {
                        header('Location: ' . BASEURL . '/admin/messages/show?id=' . $parent_id . '#reply-area');
                    } else {
                        header('Location: ' . BASEURL . '/admin/messages/sent');
                    }
                    exit;
                }
            } catch (Exception $e) {
                die("Error: " . $e->getMessage());
            }
        }
    }

    // --- 5. SHOW (Baca Pesan & Thread) ---
    public function show() {
        $msgId = $_GET['id'] ?? 0;
        $userId = $_SESSION['user_id'];

        // 1. Ambil Pesan Utama
        // FIX: Tidak select u.avatar
        $query = "SELECT m.*, 
                         s.username as sender_username, s_emp.first_name as s_fname, s_emp.last_name as s_lname,
                         r.username as recipient_username
                  FROM messages m
                  JOIN users s ON m.sender_id = s.id
                  LEFT JOIN employees s_emp ON s.employee_id = s_emp.id
                  JOIN users r ON m.recipient_id = r.id
                  WHERE m.id = :id AND (m.sender_id = :uid OR m.recipient_id = :uid)";
        
        $this->db->query($query);
        $this->db->bind(':id', $msgId);
        $this->db->bind(':uid', $userId);
        $message = $this->db->single();

        if (!$message) {
            die("Pesan tidak ditemukan atau akses ditolak.");
        }

        // 2. Tandai Read (Hanya jika saya penerima)
        if ($message['recipient_id'] == $userId && $message['is_read'] == 0) {
            $this->db->query("UPDATE messages SET is_read = 1 WHERE id = :id");
            $this->db->bind(':id', $msgId);
            $this->db->execute();
        }

        // 3. Ambil Balasan
        // FIX: Tidak select u.avatar
        $queryReply = "SELECT r.*, 
                              s.username as sender_username, s_emp.first_name as s_fname, s_emp.last_name as s_lname
                       FROM messages r
                       JOIN users s ON r.sender_id = s.id
                       LEFT JOIN employees s_emp ON s.employee_id = s_emp.id
                       WHERE r.parent_id = :pid
                       ORDER BY r.created_at ASC";
        $this->db->query($queryReply);
        $this->db->bind(':pid', $msgId);
        $replies = $this->db->resultSet();

        $data = [
            'title' => 'Baca Pesan',
            'content_view' => 'admin/messages/show',
            'message' => $message,
            'replies' => $replies
        ];
        $this->view('admin/layouts/admin-layout', $data);
    }

    // --- HELPER: AJAX USER LIST ---
    public function ajax_get_users() {
        $dept_id = $_GET['dept_id'] ?? 0;
        $myId = $_SESSION['user_id'];

        $this->db->query("SELECT u.id, e.first_name, e.last_name, e.position 
                          FROM users u
                          JOIN employees e ON u.employee_id = e.id
                          WHERE e.department_id = :did AND u.id != :myid AND u.is_active = 1
                          ORDER BY e.first_name ASC");
        $this->db->bind(':did', $dept_id);
        $this->db->bind(':myid', $myId);
        $users = $this->db->resultSet();
        
        header('Content-Type: application/json');
        echo json_encode($users);
        exit;
    }

    // --- HELPER: UPLOAD ---
    private function uploadFile($file) {
        $targetDir = "uploads/messages/";
        if (!file_exists($targetDir)) mkdir($targetDir, 0777, true);

        $fileName = basename($file["name"]);
        $fileType = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
        $newFileName = time() . '_' . rand(1000, 9999) . '.' . $fileType;
        $targetFilePath = $targetDir . $newFileName;

        $allowedTypes = array('jpg', 'jpeg', 'png', 'pdf', 'doc', 'docx', 'zip', 'xls', 'xlsx');
        if(in_array($fileType, $allowedTypes)) {
            if(move_uploaded_file($file["tmp_name"], $targetFilePath)) return $newFileName;
        }
        return null;
    }
}