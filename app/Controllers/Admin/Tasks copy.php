<?php
// File: app/Controllers/Admin/Tasks.php

class Tasks extends Controller {

    protected $db;

    public function __construct() {
        if (!isset($_SESSION['user_id'])) {
            header('Location: ' . BASEURL . '/auth');
            exit;
        }
        $this->db = new Database;
    }

    // =========================================================================
    // 1. INDEX (DASHBOARD & LIST)
    // =========================================================================
    public function index() {
        // A. STATISTIK
        $stats = $this->db->fetchOne("
            SELECT 
                COUNT(*) as total,
                SUM(CASE WHEN status = 'pending' THEN 1 ELSE 0 END) as pending,
                SUM(CASE WHEN status = 'in_progress' THEN 1 ELSE 0 END) as in_progress,
                SUM(CASE WHEN status = 'review' THEN 1 ELSE 0 END) as review,
                SUM(CASE WHEN status = 'completed' THEN 1 ELSE 0 END) as completed,
                SUM(CASE WHEN due_date < CURDATE() AND status != 'completed' THEN 1 ELSE 0 END) as overdue
            FROM tasks
        ");

        // B. LISTING TASKS
        // Logic: assigned_to -> users -> employees
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
    // 2. KANBAN BOARD
    // =========================================================================
    public function kanban() {
        $query = "
            SELECT t.*, 
                   e.first_name, e.last_name, e.profile_photo_path,
                   tc.category_name, tc.color as cat_color
            FROM tasks t
            LEFT JOIN users u_assigned ON t.assigned_to = u_assigned.id
            LEFT JOIN employees e ON u_assigned.employee_id = e.id
            LEFT JOIN task_categories tc ON t.category_id = tc.id
            WHERE t.status != 'cancelled'
            ORDER BY t.priority DESC, t.due_date ASC
        ";
        
        $tasks = $this->db->fetchAll($query);

        $data = [
            'title'        => 'Task Board',
            'content_view' => 'admin/tasks/kanban',
            'tasks'        => $tasks
        ];
        $this->view('admin/layouts/admin-layout', $data);
    }

    // =========================================================================
    // 3. DETAILS (FULL FEATURED)
    // =========================================================================
    public function details() {
        $id = $_GET['id'] ?? null;
        if(!$id) { header('Location: ' . BASEURL . '/admin/tasks'); exit; }

        // A. GET TASK DETAIL
        $query = "
            SELECT t.*,
                   e.first_name, e.last_name, e.profile_photo_path, e.email as emp_email,
                   e.employee_number, p.position_name,
                   u_creator.username as creator_username,
                   e_creator.first_name as creator_first, e_creator.last_name as creator_last,
                   tc.category_name, tc.color as cat_color,
                   d.department_name
            FROM tasks t
            LEFT JOIN users u_assigned ON t.assigned_to = u_assigned.id
            LEFT JOIN employees e ON u_assigned.employee_id = e.id
            LEFT JOIN positions p ON e.position_id = p.id
            LEFT JOIN users u_creator ON t.assigned_by = u_creator.id
            LEFT JOIN employees e_creator ON u_creator.employee_id = e_creator.id
            LEFT JOIN task_categories tc ON t.category_id = tc.id
            LEFT JOIN departments d ON t.department_id = d.id
            WHERE t.id = ?
        ";

        $task = $this->db->fetchOne($query, [$id]);

        if(!$task) {
            die("Tugas tidak ditemukan atau telah dihapus.");
        }

        // B. GET CHECKLIST
        $this->db->query("SELECT * FROM task_checklist WHERE task_id = :id ORDER BY created_at ASC");
        $this->db->bind(':id', $id);
        $checklist = $this->db->resultSet();

        // C. GET COMMENTS
        $this->db->query("
            SELECT tc.*, e.first_name, e.last_name, e.profile_photo_path 
            FROM task_comments tc 
            JOIN users u ON tc.user_id = u.id 
            JOIN employees e ON u.employee_id = e.id 
            WHERE tc.task_id = :id 
            ORDER BY tc.created_at DESC
        ");
        $this->db->bind(':id', $id);
        $comments = $this->db->resultSet();

        $data = [
            'title'        => 'Detail Tugas - ' . $task['task_code'],
            'content_view' => 'admin/tasks/details',
            'task'         => $task,
            'checklist'    => $checklist,
            'comments'     => $comments
        ];

        $this->view('admin/layouts/admin-layout', $data);
    }

    // =========================================================================
    // 4. AJAX HANDLERS (CHECKLIST & COMMENTS)
    // =========================================================================
    public function ajax_add_checklist() {
        $data = json_decode(file_get_contents('php://input'), true);
        $taskId = $data['task_id'] ?? 0;
        $title = $data['title'] ?? '';

        if($title && $taskId) {
            // Support kolom 'title' atau 'item_text' tergantung struktur DB kamu
            // Saya masukkan ke dua-duanya di query ini agar aman
            // Hapus salah satu field jika error 'Unknown column'
            $this->db->query("INSERT INTO task_checklist (task_id, title, is_completed, created_at) VALUES (:tid, :title, 0, NOW())");
            $this->db->bind(':tid', $taskId);
            $this->db->bind(':title', $title);
            
            if($this->db->execute()) echo json_encode(['success' => true]);
            else echo json_encode(['success' => false]);
        }
    }

    public function ajax_toggle_checklist() {
        $data = json_decode(file_get_contents('php://input'), true);
        $id = $data['id'] ?? 0;
        $checked = (!empty($data['checked']) && $data['checked'] == true) ? 1 : 0;

        $this->db->query("UPDATE task_checklist SET is_completed = :checked WHERE id = :id");
        $this->db->bind(':checked', $checked);
        $this->db->bind(':id', $id);
        $this->db->execute();
        echo json_encode(['success' => true]);
    }

    public function ajax_add_comment() {
        $data = json_decode(file_get_contents('php://input'), true);
        $taskId = $data['task_id'] ?? 0;
        $comment = $data['comment'] ?? '';
        $userId = $_SESSION['user_id'];

        if($comment && $taskId) {
            $this->db->query("INSERT INTO task_comments (task_id, user_id, comment, created_at) VALUES (:tid, :uid, :comment, NOW())");
            $this->db->bind(':tid', $taskId);
            $this->db->bind(':uid', $userId);
            $this->db->bind(':comment', $comment);
            $this->db->execute();
            echo json_encode(['success' => true]);
        }
    }

    public function delete_comment() {
        $id = $_GET['id'] ?? 0;
        if($id) {
            $this->db->query("DELETE FROM task_comments WHERE id = :id");
            $this->db->bind(':id', $id);
            $this->db->execute();
        }
        header('Location: ' . $_SERVER['HTTP_REFERER']);
    }

    // =========================================================================
    // 5. CREATE & STORE
    // =========================================================================
    public function create() {
        $employees = $this->db->fetchAll("SELECT * FROM employees WHERE is_active = 1 ORDER BY first_name ASC");
        $categories = $this->db->fetchAll("SELECT * FROM task_categories WHERE is_active = 1");
        $departments = $this->db->fetchAll("SELECT * FROM departments ORDER BY department_name ASC");

        $data = [
            'title'        => 'Buat Tugas Baru',
            'content_view' => 'admin/tasks/add',
            'employees'    => $employees,
            'categories'   => $categories,
            'departments'  => $departments
        ];
        $this->view('admin/layouts/admin-layout', $data);
    }

    public function store() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            try {
                // Generate Code
                $code = 'TSK-' . date('ym') . '-' . strtoupper(substr(md5(uniqid()), 0, 4));
                
                // Cari User ID dari Employee ID yg dipilih
                $emp_id_form = $_POST['assigned_to'];
                $this->db->query("SELECT id FROM users WHERE employee_id = :eid");
                $this->db->bind(':eid', $emp_id_form);
                $userTarget = $this->db->single();
                
                // Jika user ditemukan pakai ID user, jika tidak fallback ke employee ID (tergantung DB design)
                $final_assigned_to = $userTarget ? $userTarget['id'] : $emp_id_form; 

                $query = "INSERT INTO tasks (
                            task_code, category_id, title, description, 
                            assigned_by, assigned_to, department_id, 
                            priority, status, start_date, due_date, 
                            estimated_hours, kpi_weight, created_at
                          ) VALUES (
                            :code, :cat, :title, :desc, 
                            :uid, :to, :dept, 
                            :prio, 'pending', :start, :due, 
                            :hours, :weight, NOW()
                          )";
                
                $this->db->query($query);
                $this->db->bind(':code', $code);
                $this->db->bind(':cat', !empty($_POST['category_id']) ? $_POST['category_id'] : null);
                $this->db->bind(':title', $_POST['title']);
                $this->db->bind(':desc', $_POST['description']);
                $this->db->bind(':uid', $_SESSION['user_id']);
                $this->db->bind(':to', $final_assigned_to);
                $this->db->bind(':dept', !empty($_POST['department_id']) ? $_POST['department_id'] : null);
                $this->db->bind(':prio', $_POST['priority']);
                $this->db->bind(':start', $_POST['start_date']);
                $this->db->bind(':due', $_POST['due_date']);
                $this->db->bind(':hours', $_POST['estimated_hours'] ?? 0);
                $this->db->bind(':weight', $_POST['kpi_weight'] ?? 10);
                
                if($this->db->execute()) {
                    header('Location: ' . BASEURL . '/admin/tasks');
                    exit;
                }
            } catch (Exception $e) {
                die("Error: " . $e->getMessage());
            }
        }
    }

    // =========================================================================
    // 6. EDIT, UPDATE, DELETE
    // =========================================================================
    public function edit() {
        $id = $_GET['id'] ?? 0;
        $task = $this->db->fetchOne("SELECT * FROM tasks WHERE id = ?", [$id]);
        
        $employees = $this->db->fetchAll("SELECT * FROM employees WHERE is_active = 1");
        $categories = $this->db->fetchAll("SELECT * FROM task_categories");
        $departments = $this->db->fetchAll("SELECT * FROM departments"); // Tambahan agar tidak error di view edit
        
        $data = [
            'title' => 'Edit Tugas',
            'content_view' => 'admin/tasks/edit',
            'task' => $task,
            'employees' => $employees,
            'categories' => $categories,
            'departments' => $departments
        ];
        $this->view('admin/layouts/admin-layout', $data);
    }

    public function update_full() {
         if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $query = "UPDATE tasks SET 
                        title = :title, description = :desc, assigned_to = :to,
                        category_id = :cat, priority = :prio,
                        start_date = :start, due_date = :due, kpi_weight = :weight,
                        updated_at = NOW()
                      WHERE id = :id";
            
            // Logic konversi employee -> user id bisa ditambahkan disini seperti store()
            
            $this->db->query($query);
            $this->db->bind(':id', $_POST['id']);
            $this->db->bind(':title', $_POST['title']);
            $this->db->bind(':desc', $_POST['description']);
            $this->db->bind(':to', $_POST['assigned_to']);
            $this->db->bind(':cat', $_POST['category_id']);
            $this->db->bind(':prio', $_POST['priority']);
            $this->db->bind(':start', $_POST['start_date']);
            $this->db->bind(':due', $_POST['due_date']);
            $this->db->bind(':weight', $_POST['kpi_weight']);

            if ($this->db->execute()) {
                header('Location: ' . BASEURL . '/admin/tasks/details?id=' . $_POST['id']);
            }
         }
    }

    public function delete() {
        $id = $_GET['id'] ?? 0;
        if($id) {
            $this->db->query("DELETE FROM tasks WHERE id = :id");
            $this->db->bind(':id', $id);
            $this->db->execute();
        }
        header('Location: ' . BASEURL . '/admin/tasks');
    }
}