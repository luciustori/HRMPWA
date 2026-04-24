<?php

class Inbox extends Controller {
    
    // -------------------------------------------------------------------------
    // 1. INDEX: Tampilkan Inbox
    // -------------------------------------------------------------------------
    public function index() {
        $db = new Database;
        $userId = $_SESSION['user_id'];
        
        // 1. Ambil Data Employee User (Untuk Inbox)
        $emp = $db->fetchOne("
            SELECT e.id, e.department_id 
            FROM employees e 
            JOIN users u ON u.employee_id = e.id 
            WHERE u.id = :uid
        ", [':uid' => $userId]);

        $empId = $emp['id'] ?? 0;
        $deptId = $emp['department_id'] ?? 0;

        // -----------------------------------------------------------
        // QUERY 1: INBOX (Pesan Masuk)
        // -----------------------------------------------------------
        $sqlInbox = "
            SELECT 
                a.*,
                u.username as author_name,
                d.department_name as dept_name,
                CASE WHEN ar.read_at IS NOT NULL THEN 1 ELSE 0 END as is_read
            FROM announcements a
            LEFT JOIN users u ON a.created_by = u.id
            LEFT JOIN departments d ON a.target_id = d.id AND a.target_type = 'department'
            LEFT JOIN announcement_reads ar ON a.id = ar.announcement_id AND ar.user_id = :uid
            WHERE 
                (a.target_type = 'all') 
                OR (a.target_type = 'department' AND a.target_id = :deptId) 
                OR (a.target_type = 'employee' AND (a.target_id = :empId OR a.target_id = :uid_personal))
            ORDER BY a.created_at DESC
        ";

        $inboxMessages = $db->fetchAll($sqlInbox, [
            ':uid'          => $userId,
            ':deptId'       => $deptId,
            ':empId'        => $empId,
            ':uid_personal' => $userId
        ]);

        // Pisahkan Inbox jadi Pengumuman & Personal
        $announcements = [];
        $personal = [];
        foreach ($inboxMessages as $msg) {
            if ($msg['target_type'] == 'employee') {
                $personal[] = $msg;
            } else {
                $announcements[] = $msg;
            }
        }

        // -----------------------------------------------------------
        // QUERY 2: SENT ITEMS (Pesan Terkirim - SEJARAH!)
        // -----------------------------------------------------------
        // Kita join ke tabel employees untuk tau pesan ini dikirim ke SIAPA
        $sqlSent = "
            SELECT 
                a.*,
                TRIM(CONCAT(e.first_name, ' ', COALESCE(e.last_name, ''))) as target_name,
                e.position as target_position
            FROM announcements a
            LEFT JOIN employees e ON a.target_id = e.id
            WHERE a.created_by = :uid 
              AND a.target_type = 'employee' -- Hanya ambil pesan pribadi yg dikirim
            ORDER BY a.created_at DESC
        ";

        $sent = $db->fetchAll($sqlSent, [':uid' => $userId]);

        $data = [
            'title' => 'Inbox & Riwayat',
            'user_name' => $_SESSION['full_name'],
            'announcements' => $announcements, 
            'personal' => $personal,
            'sent' => $sent, // <--- Data Baru
            'content_view' => 'staff/inbox/index'
        ];

        $this->view('staff/layouts/staff-layout', $data);
    }

    // -------------------------------------------------------------------------
    // 2. READ: Baca Pesan
    // -------------------------------------------------------------------------
    public function read($id) {
        $db = new Database;
        $userId = $_SESSION['user_id'];

        $check = $db->fetchOne("SELECT id FROM announcement_reads WHERE announcement_id = :aid AND user_id = :uid", [':aid' => $id, ':uid' => $userId]);

        if (!$check) {
            $db->query("INSERT INTO announcement_reads (announcement_id, user_id, read_at) VALUES (:aid, :uid, NOW())");
            $db->bind(':aid', $id);
            $db->bind(':uid', $userId);
            $db->execute();
        }

        $msg = $db->fetchOne("
            SELECT a.*, u.username as sender_name 
            FROM announcements a 
            LEFT JOIN users u ON a.created_by = u.id 
            WHERE a.id = :id
        ", [':id' => $id]);
        
        $data = [
            'title' => $msg['title'],
            'user_name' => $_SESSION['full_name'],
            'message' => $msg,
            'content_view' => 'staff/inbox/read'
        ];

        $this->view('staff/layouts/staff-layout', $data);
    }

    // -------------------------------------------------------------------------
    // 3. CREATE: Form Tulis Pesan (FIXED: Hapus JOIN Roles)
    // -------------------------------------------------------------------------
    public function create() {
        $db = new Database;
        $sql = "SELECT e.id as employee_id, TRIM(CONCAT(e.first_name, ' ', COALESCE(e.last_name, ''))) as full_name, u.role as role_name, e.position FROM users u JOIN employees e ON u.employee_id = e.id WHERE u.is_active = 1 ORDER BY e.first_name ASC";
        $users = $db->fetchAll($sql);
        $data = ['title' => 'Tulis Pesan Baru', 'user_name' => $_SESSION['full_name'], 'recipients' => $users, 'content_view' => 'staff/inbox/create'];
        $this->view('staff/layouts/staff-layout', $data);
    }

    // -------------------------------------------------------------------------
    // 4. REPLY: Balas Pesan (NEW)
    // -------------------------------------------------------------------------
    public function reply($id) {
        $db = new Database;
        $originalMsg = $db->fetchOne("SELECT a.title, u.employee_id FROM announcements a JOIN users u ON a.created_by = u.id WHERE a.id = :id", [':id' => $id]);
        if (!$originalMsg) { header('Location: ' . BASEURL . '/staff/inbox'); exit; }
        $sqlUsers = "SELECT e.id as employee_id, TRIM(CONCAT(e.first_name, ' ', COALESCE(e.last_name, ''))) as full_name, u.role as role_name, e.position FROM users u JOIN employees e ON u.employee_id = e.id WHERE u.is_active = 1 ORDER BY e.first_name ASC";
        $users = $db->fetchAll($sqlUsers);
        $replyData = ['target_id' => $originalMsg['employee_id'], 'title' => 'Re: ' . $originalMsg['title']];
        $data = ['title' => 'Balas Pesan', 'user_name' => $_SESSION['full_name'], 'recipients' => $users, 'reply_data' => $replyData, 'content_view' => 'staff/inbox/create'];
        $this->view('staff/layouts/staff-layout', $data);
    }

    // -------------------------------------------------------------------------
    // 5. STORE: Simpan Pesan (FIXED: Tambah company_id)
    // -------------------------------------------------------------------------
    public function store() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $db = new Database;
            $userId = $_SESSION['user_id'];
            $emp = $db->fetchOne("SELECT e.company_id FROM users u JOIN employees e ON u.employee_id = e.id WHERE u.id = :uid", [':uid' => $userId]);
            $companyId = $emp['company_id'] ?? 1;
            $targetId = $_POST['target_id']; $title = $_POST['title']; $content = $_POST['content'];
            if(empty($title) || empty($content) || empty($targetId)) { header('Location: ' . BASEURL . '/staff/inbox/create'); exit; }
            $sql = "INSERT INTO announcements (company_id, title, content, target_type, target_id, created_by, created_at) VALUES (:company_id, :title, :content, 'employee', :target_id, :created_by, NOW())";
            $db->query($sql);
            $db->bind(':company_id', $companyId); $db->bind(':title', $title); $db->bind(':content', $content); $db->bind(':target_id', $targetId); $db->bind(':created_by', $userId);
            if ($db->execute()) { header('Location: ' . BASEURL . '/staff/inbox'); }
        }
    }
}