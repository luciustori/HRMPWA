<?php
// File: app/Controllers/Admin/Announcements.php

class Announcements extends Controller {
    
    private $db;

    public function __construct() {
        parent::__construct();
        if (!session_id()) session_start();

        if (!isset($_SESSION['user_id'])) {
            header('Location: ' . BASEURL . '/Admin/LoginController');
            exit;
        }

        $role = strtolower($_SESSION['role'] ?? '');
        if ($role !== 'admin' && $role !== 'super_admin') {
            header('Location: ' . BASEURL . '/staff/dashboard');
            exit;
        }

        $this->db = new Database;
    }

    public function index() {
        $query = "SELECT a.*, 
                         d.department_name, 
                         u.username as creator_name,
                         (
                            SELECT GROUP_CONCAT(CONCAT(emp.first_name, ' ', emp.last_name) SEPARATOR ', ')
                            FROM users us
                            JOIN employees emp ON us.employee_id = emp.id
                            WHERE FIND_IN_SET(us.id, a.target_employee_ids)
                         ) as target_names
                  FROM announcements a 
                  LEFT JOIN departments d ON a.target_id = d.id AND a.target_type = 'department'
                  LEFT JOIN users u ON a.created_by = u.id 
                  ORDER BY a.created_at DESC";
                  
        $this->db->query($query);
        $announcements = $this->db->resultSet();

        $stats = ['total' => count($announcements), 'info' => 0, 'warning' => 0, 'danger' => 0, 'success' => 0];
        foreach ($announcements as $row) {
            $type = $row['type'] ?? 'info';
            if(isset($stats[$type])) $stats[$type]++;
        }

        $data = [
            'title' => 'Kelola Pengumuman',
            'content_view' => 'admin/announcements/index',
            'announcements' => $announcements,
            'stats' => $stats
        ];
        $this->view('admin/layouts/admin-layout', $data);
    }

    public function create() {
        $this->db->query("SELECT id, department_name FROM departments ORDER BY department_name ASC");
        $departments = $this->db->resultSet();
        
        $this->db->query("SELECT u.id, e.first_name, e.last_name FROM users u JOIN employees e ON u.employee_id = e.id WHERE u.is_active=1 ORDER BY e.first_name ASC");
        $employees = $this->db->resultSet();

        $data = [
            'title' => 'Buat Pengumuman Baru',
            'content_view' => 'admin/announcements/create',
            'departments' => $departments,
            'employees' => $employees
        ];
        $this->view('admin/layouts/admin-layout', $data);
    }

    public function store() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            try {
                $attachmentName = null;
                if (isset($_FILES['attachment']) && $_FILES['attachment']['error'] === 0) {
                    $attachmentName = $this->uploadFile($_FILES['attachment']);
                }

                $target_scope = $_POST['target_scope'];
                $target_type = 'all'; 
                $target_id = 0;           
                $target_employee_ids = null;

                if ($target_scope == 'department_all') {
                    $target_type = 'department';
                    $target_id = $_POST['department_id'];
                } elseif ($target_scope == 'department_specific') {
                    $target_type = 'specific'; 
                    if(isset($_POST['employee_ids']) && is_array($_POST['employee_ids'])) {
                        $target_employee_ids = implode(',', $_POST['employee_ids']);
                    }
                }

                $eventDate = !empty($_POST['event_date']) ? $_POST['event_date'] : null;
                $isEvent   = !empty($eventDate) ? 1 : 0; 
                $eventTime = ($isEvent && !empty($_POST['event_time'])) ? $_POST['event_time'] : null;
                $location  = ($isEvent && !empty($_POST['event_location'])) ? $_POST['event_location'] : null;

                $query = "INSERT INTO announcements 
                          (company_id, title, content, attachment, type, target_type, target_id, target_employee_ids, 
                           is_event, event_date, event_time, event_location, created_by, created_at) 
                          VALUES (:cid, :title, :content, :att, :type, :ttype, :tid, :eids, 
                           :is_evt, :edate, :etime, :loc, :uid, NOW())";
                
                $this->db->query($query);
                $this->db->bind(':cid', 1);
                $this->db->bind(':title', $_POST['title']);
                $this->db->bind(':content', $_POST['content']);
                $this->db->bind(':att', $attachmentName);
                $this->db->bind(':type', $_POST['type']);
                
                $this->db->bind(':ttype', $target_type);
                $this->db->bind(':tid', $target_id);
                $this->db->bind(':eids', $target_employee_ids);
                
                $this->db->bind(':is_evt', $isEvent);
                $this->db->bind(':edate', $eventDate);
                $this->db->bind(':etime', $eventTime);
                $this->db->bind(':loc', $location); 
                $this->db->bind(':uid', $_SESSION['user_id']);

                if ($this->db->execute()) {
                    header('Location: ' . BASEURL . '/admin/announcements');
                    exit;
                }
            } catch (Exception $e) {
                die("Error: " . $e->getMessage());
            }
        }
    }

    public function update() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            try {
                $id = $_POST['id'];
                $old_attachment = $_POST['old_attachment'] ?? null;
                $attachmentName = $old_attachment;

                if (isset($_FILES['attachment']) && $_FILES['attachment']['error'] === 0) {
                    $newFile = $this->uploadFile($_FILES['attachment']);
                    if ($newFile) {
                        $attachmentName = $newFile;
                        if ($old_attachment && file_exists('uploads/announcements/' . $old_attachment)) {
                            unlink('uploads/announcements/' . $old_attachment);
                        }
                    }
                }

                $target_scope = $_POST['target_scope'];
                $target_type = 'all';
                $target_id = 0;
                $target_employee_ids = null;

                if ($target_scope == 'department_all') {
                    $target_type = 'department';
                    $target_id = $_POST['department_id'];
                } elseif ($target_scope == 'department_specific') {
                    $target_type = 'specific';
                    if(isset($_POST['employee_ids']) && is_array($_POST['employee_ids'])) {
                        $target_employee_ids = implode(',', $_POST['employee_ids']);
                    }
                }

                $eventDate = !empty($_POST['event_date']) ? $_POST['event_date'] : null;
                $isEvent   = !empty($eventDate) ? 1 : 0;
                $eventTime = ($isEvent && !empty($_POST['event_time'])) ? $_POST['event_time'] : null;
                $location  = ($isEvent && !empty($_POST['event_location'])) ? $_POST['event_location'] : null;

                $query = "UPDATE announcements SET 
                            title = :title, 
                            content = :content, 
                            attachment = :att,
                            type = :type, 
                            target_type = :ttype, 
                            target_id = :tid, 
                            target_employee_ids = :eids,
                            is_event = :is_evt,
                            event_date = :edate,
                            event_time = :etime,
                            event_location = :loc,
                            updated_at = NOW() 
                          WHERE id = :id";
                
                $this->db->query($query);
                $this->db->bind(':id', $id);
                $this->db->bind(':title', $_POST['title']);
                $this->db->bind(':content', $_POST['content']);
                $this->db->bind(':att', $attachmentName);
                $this->db->bind(':type', $_POST['type']);
                
                $this->db->bind(':ttype', $target_type);
                $this->db->bind(':tid', $target_id);
                $this->db->bind(':eids', $target_employee_ids);

                $this->db->bind(':is_evt', $isEvent);
                $this->db->bind(':edate', $eventDate);
                $this->db->bind(':etime', $eventTime);
                $this->db->bind(':loc', $location);

                if ($this->db->execute()) {
                    header('Location: ' . BASEURL . '/admin/announcements');
                    exit;
                }
            } catch (Exception $e) {
                die("Error Update: " . $e->getMessage());
            }
        }
    }
    
    public function edit() {
        $id = $_GET['id'] ?? null;
        if(!$id) { header('Location: ' . BASEURL . '/admin/announcements'); exit; }
        
        $this->db->query("SELECT * FROM announcements WHERE id = :id");
        $this->db->bind(':id', $id);
        $announcement = $this->db->single();
        
        $this->db->query("SELECT id, department_name FROM departments ORDER BY department_name ASC");
        $departments = $this->db->resultSet();

        // TAMBAHAN: Tarik semua data karyawan untuk form checkboxes
        $this->db->query("SELECT u.id, e.first_name, e.last_name FROM users u JOIN employees e ON u.employee_id = e.id WHERE u.is_active=1 ORDER BY e.first_name ASC");
        $employees = $this->db->resultSet();

        $data = [
            'title' => 'Edit Pengumuman', 
            'content_view' => 'admin/announcements/edit', 
            'announcement' => $announcement, 
            'departments' => $departments,
            'employees' => $employees
        ];
        $this->view('admin/layouts/admin-layout', $data);
    }
    
    public function delete() {
        $id = $_GET['id'] ?? 0;
        if($id) {
            $this->db->query("SELECT attachment FROM announcements WHERE id = :id");
            $this->db->bind(':id', $id);
            $row = $this->db->single();
            if ($row && $row['attachment'] && file_exists('uploads/announcements/' . $row['attachment'])) {
                unlink('uploads/announcements/' . $row['attachment']);
            }
            $this->db->query("DELETE FROM announcements WHERE id = :id");
            $this->db->bind(':id', $id);
            $this->db->execute();
        }
        header('Location: ' . BASEURL . '/admin/announcements');
    }

    public function ajax_get_employees() {
        $dept_id = $_GET['dept_id'] ?? 0;
        $this->db->query("SELECT u.id as user_id, e.first_name, e.last_name FROM employees e JOIN users u ON u.employee_id = e.id WHERE e.department_id = :did AND e.is_active = 1 ORDER BY e.first_name ASC");
        $this->db->bind(':did', $dept_id);
        $employees = $this->db->resultSet();
        header('Content-Type: application/json');
        echo json_encode($employees);
        exit;
    }

    private function uploadFile($file) {
        $targetDir = "uploads/announcements/";
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