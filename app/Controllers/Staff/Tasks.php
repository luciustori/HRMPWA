<?php
// File: app/Controllers/Staff/Tasks.php

class Tasks extends Controller {
    
    // 1. HALAMAN LIST TUGAS SAYA
    public function index() {
        $db = new Database;
        $userId = $_SESSION['user_id'];
        
        $filterStatus = $_GET['status'] ?? 'all';
        
        // Query List Task
        $sql = "
            SELECT t.*, 
                   tc.category_name, 
                   tc.color as cat_color,
                   COALESCE(e.first_name, u.username) as assigner_name
            FROM tasks t
            LEFT JOIN task_categories tc ON t.category_id = tc.id
            LEFT JOIN users u ON t.assigned_by = u.id
            LEFT JOIN employees e ON u.employee_id = e.id
            WHERE t.assigned_to = :uid
        ";

        // Logic Filter Tab
        if ($filterStatus != 'all') {
            if ($filterStatus == 'in_progress') {
                // Tab 'Proses' menampilkan: Sedang dikerjakan, Menunggu Approval, dan Revisi
                $sql .= " AND t.status IN ('in_progress', 'submitted', 'review')";
            } else {
                $sql .= " AND t.status = '$filterStatus'";
            }
        }
        
        $sql .= " ORDER BY t.due_date ASC, t.priority DESC";

        $tasks = $db->fetchAll($sql, [':uid' => $userId]);

        // Stats Ringkas (Update hitungan Submitted & Review masuk ke 'on_going')
        $stats = $db->fetchOne("
            SELECT 
                COUNT(*) as total,
                SUM(CASE WHEN status = 'pending' THEN 1 ELSE 0 END) as pending,
                SUM(CASE WHEN status IN ('in_progress', 'submitted', 'review') THEN 1 ELSE 0 END) as on_going,
                SUM(CASE WHEN status = 'completed' THEN 1 ELSE 0 END) as completed
            FROM tasks WHERE assigned_to = :uid
        ", [':uid' => $userId]);

        $data = [
            'title' => 'Pekerjaan Saya',
            'user_name' => $_SESSION['full_name'],
            'tasks' => $tasks,
            'stats' => $stats,
            'filter' => $filterStatus,
            'content_view' => 'staff/tasks/index'
        ];

        $this->view('staff/layouts/staff-layout', $data);
    }

    // 2. HALAMAN DETAIL TUGAS
    public function detail($id) {
        $db = new Database;
        $userId = $_SESSION['user_id'];

        // Detail Task & User Info
        $sql = "
            SELECT 
                t.*, 
                tc.category_name, 
                COALESCE(e_creator.first_name, u_creator.username) as creator_name,
                e_creator.first_name as creator_first,
                e_pic.first_name as pic_first,
                e_pic.last_name as pic_last,
                e_pic.position as pic_position,
                e_pic.profile_photo_path as pic_photo
            FROM tasks t
            LEFT JOIN task_categories tc ON t.category_id = tc.id
            LEFT JOIN users u_creator ON t.assigned_by = u_creator.id
            LEFT JOIN employees e_creator ON u_creator.employee_id = e_creator.id
            LEFT JOIN users u_pic ON t.assigned_to = u_pic.id
            LEFT JOIN employees e_pic ON u_pic.employee_id = e_pic.id
            WHERE t.id = :tid AND t.assigned_to = :uid
        ";
        $task = $db->fetchOne($sql, [':tid' => $id, ':uid' => $userId]);

        if (!$task) die("Tugas tidak ditemukan atau akses ditolak.");

        // Checklist
        $checklist = $db->fetchAll("SELECT * FROM task_checklist WHERE task_id = :tid ORDER BY sort_order ASC", [':tid' => $id]);
        
        // Time Logs
        $timeLogs = $db->fetchAll("
            SELECT l.*, u.username 
            FROM task_time_logs l 
            JOIN users u ON l.user_id = u.id 
            WHERE l.task_id = :tid 
            ORDER BY l.created_at DESC
        ", [':tid' => $id]);

        // Comments
        $comments = $db->fetchAll("
            SELECT c.*, u.username, e.first_name
            FROM task_comments c
            JOIN users u ON c.user_id = u.id
            LEFT JOIN employees e ON u.employee_id = e.id
            WHERE c.task_id = :tid
            ORDER BY c.created_at ASC
        ", [':tid' => $id]);
        
        // Logs History
        $logs = $db->fetchAll("SELECT * FROM task_history WHERE task_id = :tid ORDER BY created_at DESC LIMIT 10", [':tid' => $id]);

        $data = [
            'title' => 'Detail Tugas - ' . $task['task_code'],
            'user_name' => $_SESSION['full_name'],
            'task' => $task,
            'checklist' => $checklist,
            'time_logs' => $timeLogs,
            'comments' => $comments,
            'logs' => $logs,
            'content_view' => 'staff/tasks/detail'
        ];

        $this->view('staff/layouts/staff-layout', $data);
    }

    // 3. UPDATE STATUS TUGAS (LOGIC UTAMA APPROVAL DISINI)
    public function update_status() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $db = new Database;
            $id = $_POST['task_id'];
            $status = $_POST['status'];
            $userId = $_SESSION['user_id'];
            
            // Verifikasi Kepemilikan Task
            $task = $db->fetchOne("SELECT status FROM tasks WHERE id = :id AND assigned_to = :uid", [':id' => $id, ':uid' => $userId]);
            
            if ($task) {
                // --- INTERCEPT: Jika staff klik Selesai -> Ubah jadi Submitted ---
                if ($status == 'completed') {
                    $status = 'submitted';
                }
                
                // Update Status
                $db->query("UPDATE tasks SET status = :status WHERE id = :id");
                $db->bind(':status', $status);
                $db->bind(':id', $id);
                $db->execute();
                
                // Catat di History
                $labelStatus = ($status == 'submitted') ? 'Mengajukan Approval (Selesai)' : $status;
                $desc = "Mengubah status menjadi {$labelStatus}";
                
                $db->query("INSERT INTO task_history (task_id, user_id, action_type, description, created_at) VALUES (:tid, :uid, 'status_changed', :desc, NOW())");
                $db->bind(':tid', $id);
                $db->bind(':uid', $userId);
                $db->bind(':desc', $desc);
                $db->execute();
            }
            
            header('Location: ' . BASEURL . '/staff/tasks/detail/' . $id);
            exit;
        }
    }
    
    // 4. TOGGLE CHECKLIST
    public function toggle_checklist($checkId, $taskId) {
        $db = new Database;
        $db->query("UPDATE task_checklist SET is_completed = NOT is_completed WHERE id = :cid");
        $db->bind(':cid', $checkId);
        $db->execute();
        header('Location: ' . BASEURL . '/staff/tasks/detail/' . $taskId);
    }

    // 5. CATAT LOG WAKTU
    public function add_time_log() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $db = new Database;
            $taskId = $_POST['task_id'];
            $desc = $_POST['description'];
            
            $hoursInput = $_POST['hours'];
            $hours = (is_numeric($hoursInput) && $hoursInput != '') ? floatval($hoursInput) : 0;
            
            $logDateOnly = $_POST['log_date']; 
            $logDateTime = $_POST['log_date'] . ' ' . date('H:i:s');
            
            $sql = "INSERT INTO task_time_logs (task_id, user_id, description, hours_spent, log_date, created_at) 
                    VALUES (:tid, :uid, :desc, :hours, :logDate, :createdAt)";
            
            $db->query($sql);
            $db->bind(':tid', $taskId);
            $db->bind(':uid', $_SESSION['user_id']);
            $db->bind(':desc', $desc);
            $db->bind(':hours', $hours);
            $db->bind(':logDate', $logDateOnly);
            $db->bind(':createdAt', $logDateTime);
            
            if ($db->execute()) {
                header('Location: ' . BASEURL . '/staff/tasks/detail/' . $taskId);
                exit;
            } else {
                die("Gagal menyimpan. Cek error log.");
            }
        }
    }

    // 6. TAMBAH KOMENTAR
    public function add_comment() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $db = new Database;
            $taskId = $_POST['task_id'];
            $text = $_POST['comment'];
            
            if(!empty(trim($text))) {
                $db->query("INSERT INTO task_comments (task_id, user_id, comment, created_at) VALUES (:tid, :uid, :text, NOW())");
                $db->bind(':tid', $taskId);
                $db->bind(':uid', $_SESSION['user_id']);
                $db->bind(':text', $text);
                $db->execute();
                
                $desc = "Menambahkan komentar";
                $db->query("INSERT INTO task_history (task_id, user_id, action_type, description, created_at) VALUES (:tid, :uid, 'commented', :desc, NOW())");
                $db->bind(':tid', $taskId);
                $db->bind(':uid', $_SESSION['user_id']);
                $db->bind(':desc', $desc);
                $db->execute();
            }

            header('Location: ' . BASEURL . '/staff/tasks/detail/' . $taskId);
        }
    }
}