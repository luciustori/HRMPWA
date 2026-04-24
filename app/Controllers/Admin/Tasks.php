<?php
// File: app/Controllers/Admin/Tasks.php

class Tasks extends Controller {

    private $db; 

    public function __construct() {
        parent::__construct(); 
        if (!session_id()) session_start();

        // 1. CEK LOGIN
        if (!isset($_SESSION['user_id'])) {
            header('Location: ' . BASEURL . '/Admin/LoginController');
            exit;
        }

        // 2. CEK ROLE
        $role = strtolower($_SESSION['role'] ?? '');
        if ($role !== 'admin' && $role !== 'super_admin') {
            header('Location: ' . BASEURL . '/staff/dashboard');
            exit;
        }

        // 3. INIT DATABASE
        $this->db = new Database;
    }

    // =========================================================================
    // HELPER: SINKRONISASI KPI OTOMATIS
    // =========================================================================
    private function syncKPI($user_id, $task_date) {
        $this->db->query("SELECT employee_id FROM users WHERE id = :uid");
        $this->db->bind(':uid', $user_id);
        $user = $this->db->single();

        if (!$user || empty($user['employee_id'])) return;

        $timestamp = strtotime($task_date);
        $day   = date('d', $timestamp);
        $month = date('m', $timestamp);
        $year  = date('Y', $timestamp);

        // Rule Periode Gaji/KPI (Cutoff tgl 25)
        if ($day >= 25) {
            $month++;
            if ($month > 12) {
                $month = 1;
                $year++;
            }
        }

        require_once '../app/Models/KpiCalculator.php';
        $calc = new KpiCalculator();
        $calc->syncToDatabase($user['employee_id'], (int)$month, (int)$year);
    }

    // =========================================================================
    // 1. DASHBOARD INDEX
    // =========================================================================
    public function index() {
        try {
            $stats = $this->db->fetchOne("
                SELECT 
                    COUNT(*) as total,
                    SUM(CASE WHEN status = 'pending' THEN 1 ELSE 0 END) as pending,
                    SUM(CASE WHEN status = 'in_progress' THEN 1 ELSE 0 END) as in_progress,
                    SUM(CASE WHEN status = 'submitted' THEN 1 ELSE 0 END) as submitted,
                    SUM(CASE WHEN status = 'review' THEN 1 ELSE 0 END) as review,
                    SUM(CASE WHEN status = 'completed' THEN 1 ELSE 0 END) as completed,
                    SUM(CASE WHEN due_date < CURDATE() AND status != 'completed' THEN 1 ELSE 0 END) as overdue
                FROM tasks
            ");
        } catch (Exception $e) {
            $stats = []; 
        }

        $query = "
            SELECT t.*, 
                   e.first_name, e.last_name, e.profile_photo_path,
                   tc.category_name, tc.color as cat_color,
                   u_creator.username as creator_name
            FROM tasks t
            LEFT JOIN users u_assigned ON t.assigned_to = u_assigned.id
            LEFT JOIN employees e ON u_assigned.employee_id = e.id
            LEFT JOIN task_categories tc ON t.category_id = tc.id
            LEFT JOIN users u_creator ON t.assigned_by = u_creator.id
            ORDER BY t.created_at DESC
        ";
        
        $tasks = $this->db->fetchAll($query);
        $employees = $this->db->fetchAll("SELECT id, first_name, last_name FROM employees WHERE is_active = 1");

        $data = [
            'title'        => 'Manajemen Tugas',
            'content_view' => 'admin/tasks/index',
            'stats'        => $stats,
            'tasks'        => $tasks,
            'employees'    => $employees
        ];

        $this->view('admin/layouts/admin-layout', $data);
    }

    // =========================================================================
    // 2. HALAMAN APPROVAL (BARU)
    // =========================================================================
    public function approvals() {
        $role = $_SESSION['role'] ?? '';
        if (!in_array($role, ['admin', 'super_admin', 'manager', 'coordinator'])) {
            header('Location: ' . BASEURL . '/admin/tasks');
            exit;
        }

        $query = "
            SELECT t.*, 
                   e.first_name, e.last_name, e.profile_photo_path,
                   tc.category_name, tc.color as cat_color,
                   u_assigned.username as assigned_username
            FROM tasks t
            LEFT JOIN users u_assigned ON t.assigned_to = u_assigned.id
            LEFT JOIN employees e ON u_assigned.employee_id = e.id
            LEFT JOIN task_categories tc ON t.category_id = tc.id
            WHERE t.status = 'submitted'
            ORDER BY t.updated_at DESC
        ";
        
        $tasks = $this->db->fetchAll($query);

        $data = [
            'title'        => 'Persetujuan Tugas',
            'content_view' => 'admin/tasks/approvals',
            'tasks'        => $tasks
        ];
        
        $this->view('admin/layouts/admin-layout', $data);
    }

    // =========================================================================
    // 3. CREATE & STORE
    // =========================================================================
    public function create() {
        $this->db->query("
            SELECT e.id as emp_id, u.id as user_id, e.first_name, e.last_name, d.department_name 
            FROM employees e 
            JOIN users u ON u.employee_id = e.id
            LEFT JOIN departments d ON e.department_id = d.id 
            WHERE e.is_active = 1 
            ORDER BY e.first_name ASC
        ");
        $employees = $this->db->resultSet();

        $this->db->query("SELECT * FROM task_categories WHERE is_active = 1");
        $categories = $this->db->resultSet();

        $data = [
            'title'        => 'Buat Tugas Baru',
            'content_view' => 'admin/tasks/add',
            'employees'    => $employees,
            'categories'   => $categories
        ];
        $this->view('admin/layouts/admin-layout', $data);
    }

    public function store() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            try {
                $code = 'TSK-' . date('ym') . '-' . strtoupper(substr(md5(uniqid()), 0, 4));

                $is_recurring = isset($_POST['is_recurring']) ? 1 : 0;
                $rec_type = $_POST['recurrence_type'] ?? 'none';
                $rec_day = $_POST['recurrence_day'] ?? null;

                if(!$is_recurring) {
                    $rec_type = 'none';
                    $rec_day = null;
                }

                $query = "INSERT INTO tasks (
                            task_code, category_id, title, description, 
                            assigned_by, assigned_to, priority, status, 
                            start_date, due_date, estimated_hours, kpi_weight,
                            is_recurring, recurrence_type, recurrence_day,
                            created_at
                          ) VALUES (
                            :code, :cat, :title, :desc, 
                            :by, :to, :prio, 'pending', 
                            :start, :due, :hours, :weight,
                            :is_rec, :rec_type, :rec_day,
                            NOW()
                          )";
                
                $this->db->query($query);
                
                $this->db->bind(':code', $code);
                $this->db->bind(':cat', !empty($_POST['category_id']) ? $_POST['category_id'] : null);
                $this->db->bind(':title', $_POST['title']);
                $this->db->bind(':desc', $_POST['description']);
                $this->db->bind(':by', $_SESSION['user_id']);
                $this->db->bind(':to', $_POST['assigned_to']);
                $this->db->bind(':prio', $_POST['priority']);
                $this->db->bind(':start', $_POST['start_date']);
                $this->db->bind(':due', $_POST['due_date']);
                $this->db->bind(':hours', $_POST['estimated_hours'] ?? 0);
                $this->db->bind(':weight', $_POST['kpi_weight'] ?? 10);
                $this->db->bind(':is_rec', $is_recurring);
                $this->db->bind(':rec_type', $rec_type);
                $this->db->bind(':rec_day', $rec_day);

                if($this->db->execute()) {
                    $taskId = $this->db->lastInsertId(); 
                    
                    if(isset($_POST['checklist_items']) && is_array($_POST['checklist_items'])) {
                        $sqlCheck = "INSERT INTO task_checklist (task_id, item_text, is_completed, created_at) VALUES (:tid, :text, 0, NOW())";
                        foreach($_POST['checklist_items'] as $item) {
                            if(trim($item) != "") {
                                $this->db->query($sqlCheck);
                                $this->db->bind(':tid', $taskId);
                                $this->db->bind(':text', trim($item)); 
                                $this->db->execute();
                            }
                        }
                    }
                    header('Location: ' . BASEURL . '/admin/tasks');
                    exit;
                }
            } catch (Exception $e) {
                die("Error: " . $e->getMessage());
            }
        }
    }

    // =========================================================================
    // 4. DETAIL, EDIT, UPDATE, KANBAN
    // =========================================================================
    public function details() {
        $id = $_GET['id'] ?? null;
        if(!$id) { header('Location: ' . BASEURL . '/admin/tasks'); exit; }

        // --- PERBAIKAN 1: Gunakan :id (Named Parameter) bukan ? ---
        $query = "
            SELECT t.*,
                   e.first_name, e.last_name, e.profile_photo_path, e.email as emp_email,
                   e.employee_number, 
                   u_creator.username as creator_username,
                   e_creator.first_name as creator_first, e_creator.last_name as creator_last,
                   tc.category_name, tc.color as cat_color,
                   d.department_name
            FROM tasks t
            LEFT JOIN users u_assigned ON t.assigned_to = u_assigned.id
            LEFT JOIN employees e ON u_assigned.employee_id = e.id
            LEFT JOIN users u_creator ON t.assigned_by = u_creator.id
            LEFT JOIN employees e_creator ON u_creator.employee_id = e_creator.id
            LEFT JOIN task_categories tc ON t.category_id = tc.id
            LEFT JOIN departments d ON e.department_id = d.id
            WHERE t.id = :id
        ";
        // Pass array dengan key ':id'
        $task = $this->db->fetchOne($query, [':id' => $id]);
        
        if(!$task) die("Tugas tidak ditemukan.");

        $this->db->query("SELECT * FROM task_checklist WHERE task_id = :id ORDER BY created_at ASC");
        $this->db->bind(':id', $id);
        $checklist = $this->db->resultSet();

        $this->db->query("
            SELECT ttl.*, e.first_name, e.last_name 
            FROM task_time_logs ttl
            JOIN users u ON ttl.user_id = u.id
            LEFT JOIN employees e ON u.employee_id = e.id
            WHERE ttl.task_id = :id 
            ORDER BY ttl.log_date DESC, ttl.created_at DESC
        ");
        $this->db->bind(':id', $id);
        $logs = $this->db->resultSet();

        $this->db->query("
            SELECT tc.*, e.first_name, e.last_name, e.profile_photo_path 
            FROM task_comments tc 
            JOIN users u ON tc.user_id = u.id 
            LEFT JOIN employees e ON u.employee_id = e.id 
            WHERE tc.task_id = :id 
            ORDER BY tc.created_at DESC
        ");
        $this->db->bind(':id', $id);
        $comments = $this->db->resultSet();

        $data = [
            'title'        => 'Detail Tugas - ' . ($task['task_code'] ?? 'NA'),
            'content_view' => 'admin/tasks/details',
            'task'         => $task,
            'checklist'    => $checklist,
            'logs'         => $logs,
            'comments'     => $comments
        ];

        $this->view('admin/layouts/admin-layout', $data);
    }

    public function edit() {
        $id = $_GET['id'] ?? null;
        if (!$id) { header('Location: ' . BASEURL . '/admin/tasks'); exit; }

        // --- PERBAIKAN 2: Gunakan :id (Named Parameter) bukan ? ---
        $query = "SELECT * FROM tasks WHERE id = :id";
        // Pass array dengan key ':id'
        $task = $this->db->fetchOne($query, [':id' => $id]);
        
        if (!$task) { header('Location: ' . BASEURL . '/admin/tasks'); exit; }

        $this->db->query("
            SELECT e.id as emp_id, u.id as user_id, e.first_name, e.last_name, d.department_name 
            FROM employees e 
            JOIN users u ON u.employee_id = e.id
            LEFT JOIN departments d ON e.department_id = d.id 
            WHERE e.is_active = 1 
            ORDER BY e.first_name ASC
        ");
        $employees = $this->db->resultSet();

        $this->db->query("SELECT * FROM task_categories WHERE is_active = 1");
        $categories = $this->db->resultSet();

        $data = [
            'title'        => 'Edit Tugas',
            'content_view' => 'admin/tasks/edit', 
            'task'         => $task,
            'employees'    => $employees,
            'categories'   => $categories
        ];
        $this->view('admin/layouts/admin-layout', $data);
    }

    public function update_full() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            try {
                $id = $_POST['id'];
                $is_recurring = isset($_POST['is_recurring']) ? 1 : 0;
                $rec_type = $_POST['recurrence_type'] ?? 'none';
                $rec_day = $_POST['recurrence_day'] ?? null;

                if(!$is_recurring) { $rec_type = 'none'; $rec_day = null; }

                $query = "UPDATE tasks SET 
                            title = :title, description = :desc, assigned_to = :to,
                            category_id = :cat, priority = :prio, start_date = :start,
                            due_date = :due, estimated_hours = :hours, kpi_weight = :weight,
                            is_recurring = :is_rec, recurrence_type = :rec_type, recurrence_day = :rec_day,
                            updated_at = NOW()
                          WHERE id = :id";
                
                $this->db->query($query);
                $this->db->bind(':id', $id);
                $this->db->bind(':title', $_POST['title']);
                $this->db->bind(':desc', $_POST['description']);
                $this->db->bind(':to', $_POST['assigned_to']);
                $this->db->bind(':cat', !empty($_POST['category_id']) ? $_POST['category_id'] : null);
                $this->db->bind(':prio', $_POST['priority']);
                $this->db->bind(':start', $_POST['start_date']);
                $this->db->bind(':due', $_POST['due_date']);
                $this->db->bind(':hours', $_POST['estimated_hours']);
                $this->db->bind(':weight', $_POST['kpi_weight']);
                $this->db->bind(':is_rec', $is_recurring);
                $this->db->bind(':rec_type', $rec_type);
                $this->db->bind(':rec_day', $rec_day);

                if ($this->db->execute()) {
                    // Update KPI juga saat edit data (karena bobot mungkin berubah)
                    $this->db->query("SELECT created_at FROM tasks WHERE id = :id");
                    $this->db->bind(':id', $id);
                    $t = $this->db->single();
                    if ($t) {
                        $this->syncKPI($_POST['assigned_to'], $t['created_at']);
                    }
                    header('Location: ' . BASEURL . '/admin/tasks/details?id=' . $id);
                    exit;
                }
            } catch (Exception $e) { die("Error: " . $e->getMessage()); }
        }
    }

    public function kanban() {
        $query = "
            SELECT t.*, e.first_name, e.last_name, e.profile_photo_path,
                   tc.category_name, tc.color as cat_color
            FROM tasks t
            LEFT JOIN users u_assigned ON t.assigned_to = u_assigned.id
            LEFT JOIN employees e ON u_assigned.employee_id = e.id
            LEFT JOIN task_categories tc ON t.category_id = tc.id
            WHERE t.status != 'cancelled'
            ORDER BY t.priority DESC, t.due_date ASC
        ";
        $tasks = $this->db->fetchAll($query);
        $data = ['title' => 'Task Board', 'content_view' => 'admin/tasks/kanban', 'tasks' => $tasks];
        $this->view('admin/layouts/admin-layout', $data);
    }

    // =========================================================================
    // 5. AJAX HANDLERS (LOGIC STATUS BARU)
    // =========================================================================

    public function ajax_update_status() {
        $data = json_decode(file_get_contents('php://input'), true);
        $id = $data['id'] ?? 0;
        $status = $data['status'] ?? 'pending';
        
        // --- LOGIC APPROVAL ---
        // Jika status diminta 'completed' tapi user BUKAN Super Admin,
        // Cek dulu apakah status sekarang 'submitted'.
        // Jika belum submitted (alias dari pending/progress), paksa jadi 'submitted'.
        if ($status == 'completed' && $_SESSION['role'] != 'super_admin') {
            $this->db->query("SELECT status FROM tasks WHERE id = :id");
            $this->db->bind(':id', $id);
            $current = $this->db->single();
            
            // Jika status sekarang belum submitted, berarti staff baru kelarin tugas -> Masuk Approval
            if ($current && $current['status'] != 'submitted') {
                $status = 'submitted'; 
            }
        }

        $completed_at = ($status == 'completed') ? date('Y-m-d H:i:s') : null;

        $this->db->query("UPDATE tasks SET status = :status, completed_at = :cat, updated_at = NOW() WHERE id = :id");
        $this->db->bind(':status', $status);
        $this->db->bind(':cat', $completed_at);
        $this->db->bind(':id', $id);
        
        if ($this->db->execute()) {
            if ($status == 'completed') {
                $this->db->query("SELECT assigned_to, created_at FROM tasks WHERE id = :id");
                $this->db->bind(':id', $id);
                $task = $this->db->single();
                if ($task) {
                    $this->syncKPI($task['assigned_to'], $task['created_at']);
                }
            }
            echo json_encode(['success' => true, 'new_status' => $status]);
        } else {
            echo json_encode(['success' => false]);
        }
    }

    public function ajax_toggle_checklist() {
        $data = json_decode(file_get_contents('php://input'), true);
        $this->db->query("UPDATE task_checklist SET is_completed = :c WHERE id = :id");
        $this->db->bind(':c', (!empty($data['checked']) && $data['checked'] == true) ? 1 : 0);
        $this->db->bind(':id', $data['id'] ?? 0);
        echo json_encode(['success' => $this->db->execute()]);
    }

    public function ajax_add_checklist() {
        $data = json_decode(file_get_contents('php://input'), true);
        if($data['item_text'] && $data['task_id']) {
            $this->db->query("INSERT INTO task_checklist (task_id, item_text, is_completed, created_at) VALUES (:tid, :text, 0, NOW())");
            $this->db->bind(':tid', $data['task_id']);
            $this->db->bind(':text', $data['item_text']);
            echo json_encode(['success' => $this->db->execute()]);
        }
    }

    public function ajax_add_log() {
        $data = json_decode(file_get_contents('php://input'), true);
        if($data['description'] && $data['task_id']) {
            $this->db->query("INSERT INTO task_time_logs (task_id, user_id, log_date, hours_spent, description, created_at) VALUES (:tid, :uid, :date, :hours, :desc, NOW())");
            $this->db->bind(':tid', $data['task_id']);
            $this->db->bind(':uid', $_SESSION['user_id']);
            $this->db->bind(':date', $data['log_date'] ?? date('Y-m-d'));
            $this->db->bind(':hours', $data['hours_spent'] ?? 0);
            $this->db->bind(':desc', $data['description']);
            echo json_encode(['success' => $this->db->execute()]);
        }
    }

    public function ajax_add_comment() {
        $data = json_decode(file_get_contents('php://input'), true);
        if($data['comment'] && $data['task_id']) {
            $this->db->query("INSERT INTO task_comments (task_id, user_id, comment, created_at) VALUES (:tid, :uid, :comment, NOW())");
            $this->db->bind(':tid', $data['task_id']);
            $this->db->bind(':uid', $_SESSION['user_id']);
            $this->db->bind(':comment', $data['comment']);
            echo json_encode(['success' => $this->db->execute()]);
        }
    }
}