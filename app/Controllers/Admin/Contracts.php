<?php
// File: app/Controllers/Admin/Contracts.php

class Contracts extends Controller {

    private $db;

    public function __construct() {
        parent::__construct();
        if (!session_id()) session_start();

        // 1. Cek Login & Role (Hanya Admin & HR)
        if (!isset($_SESSION['user_id'])) {
            header('Location: ' . BASEURL . '/Admin/LoginController');
            exit;
        }

        // Cek Role Manual
        $allowed = ['super_admin', 'admin', 'hr_manager', 'hr_staff'];
        if (!in_array($_SESSION['role'], $allowed)) {
            header('Location: ' . BASEURL . '/admin/dashboard');
            exit;
        }

        $this->db = new Database;
    }

    public function index() {
        // Ambil filter status
        $status = $_GET['status'] ?? 'active';
        
        // Query Join Karyawan + Departemen
        $sql = "SELECT c.*, e.first_name, e.last_name, e.employee_number, d.department_name,
                       DATEDIFF(c.end_date, CURDATE()) as days_remaining
                FROM employment_contracts c
                JOIN employees e ON c.employee_id = e.id
                LEFT JOIN departments d ON e.department_id = d.id
                WHERE 1=1 ";

        if ($status !== 'all') {
            $sql .= " AND c.status = :status";
        }

        $sql .= " ORDER BY c.end_date ASC"; // Yang mau expired duluan paling atas

        $this->db->query($sql);
        if ($status !== 'all') $this->db->bind(':status', $status);
        $contracts = $this->db->resultSet();

        // Hitung Statistik Ringan
        $stats = [
            'active' => 0,
            'expiring_soon' => 0, // < 60 Hari
            'expired' => 0
        ];

        // Loop untuk hitung stats real-time
        foreach ($contracts as $c) {
            if ($c['status'] == 'active') {
                $stats['active']++;
                if ($c['contract_type'] != 'PKWTT' && $c['days_remaining'] > 0 && $c['days_remaining'] <= 60) {
                    $stats['expiring_soon']++;
                }
            }
            if ($c['status'] == 'expired' || ($c['end_date'] && $c['end_date'] < date('Y-m-d'))) {
                $stats['expired']++;
            }
        }

        $data = [
            'title' => 'Kontrak & Dokumen',
            'content_view' => 'admin/contracts/index',
            'contracts' => $contracts,
            'stats' => $stats,
            'filter_status' => $status
        ];
        $this->view('admin/layouts/admin-layout', $data);
    }

    public function create() {
        // Ambil Data Karyawan untuk Dropdown
        $this->db->query("SELECT id, first_name, last_name, employee_number FROM employees WHERE is_active = 1 ORDER BY first_name ASC");
        $employees = $this->db->resultSet();

        $data = [
            'title' => 'Tambah Kontrak Baru',
            'content_view' => 'admin/contracts/create',
            'employees' => $employees
        ];
        $this->view('admin/layouts/admin-layout', $data);
    }

    public function store() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            try {
                // Upload File PDF/JPG
                $doc_path = null;
                if (!empty($_FILES['document']['name'])) {
                    $doc_path = $this->uploadFile($_FILES['document']);
                }

                $data = [
                    'employee_id' => $_POST['employee_id'],
                    'contract_number' => $_POST['contract_number'],
                    'contract_type' => $_POST['contract_type'],
                    'start_date' => $_POST['start_date'],
                    'end_date' => empty($_POST['end_date']) ? NULL : $_POST['end_date'], // PKWTT bisa null
                    'document_path' => $doc_path,
                    'notes' => $_POST['notes'],
                    'status' => 'active'
                ];

                $sql = "INSERT INTO employment_contracts (employee_id, contract_number, contract_type, start_date, end_date, document_path, notes, status) 
                        VALUES (:emp, :no, :type, :start, :end, :doc, :notes, :status)";
                
                $this->db->query($sql);
                $this->db->bind(':emp', $data['employee_id']);
                $this->db->bind(':no', $data['contract_number']);
                $this->db->bind(':type', $data['contract_type']);
                $this->db->bind(':start', $data['start_date']);
                $this->db->bind(':end', $data['end_date']);
                $this->db->bind(':doc', $data['document_path']);
                $this->db->bind(':notes', $data['notes']);
                $this->db->bind(':status', $data['status']);

                $this->db->execute();

                Flasher::setFlash('Berhasil', 'Kontrak kerja berhasil ditambahkan', 'success');
                header('Location: ' . BASEURL . '/admin/contracts');

            } catch (Exception $e) {
                Flasher::setFlash('Gagal', $e->getMessage(), 'error');
                header('Location: ' . BASEURL . '/admin/contracts/create');
            }
        }
    }

    private function uploadFile($file) {
        $targetDir = "../public/uploads/contracts/";
        if (!file_exists($targetDir)) mkdir($targetDir, 0777, true);
        
        $ext = strtolower(pathinfo($file["name"], PATHINFO_EXTENSION));
        
        // Validasi Ekstensi
        if (!in_array($ext, ['pdf', 'jpg', 'jpeg', 'png'])) {
            throw new Exception("Format file harus PDF atau Gambar");
        }

        $fileName = time() . '_' . uniqid() . '.' . $ext;
        move_uploaded_file($file["tmp_name"], $targetDir . $fileName);
        
        return "uploads/contracts/" . $fileName;
    }
    
    // Fitur Download/View File Aman
    public function view_file($id) {
        // (Opsional) Logic untuk secure file download
        // Untuk sekarang kita link langsung di View saja biar cepat
    }
}