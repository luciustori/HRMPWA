<?php
// app/Controllers/PWA/RequestController.php

namespace App\Controllers\PWA;

use App\Models\Permission;
use App\Models\Employee;

class RequestController {
    public function index() {
        $userId = $_SESSION['user_id'] ?? null;
        if (!$userId) {
            header('Location: /pwa/login');
            exit;
        }

        // Get employee ID
        $empModel = new Employee();
        $stmt = $empModel->db->prepare("SELECT id FROM employees WHERE user_id = :uid");
        $stmt->execute([':uid' => $userId]);
        $emp = $stmt->fetch(\PDO::FETCH_ASSOC);

        // Get permissions
        $permissionModel = new Permission();
        $requests = $permissionModel->allByEmployee($emp['id']);

        $page_title = 'Pengajuan Saya';
        $page_content = '../app/Views/PWA/pages/requests.php';
        require '../app/Views/PWA/layouts/main.php';
    }

    public function create() {
        $page_title = 'Buat Pengajuan';
        $page_content = '../app/Views/PWA/pages/request-form.php';
        require '../app/Views/PWA/layouts/main.php';
    }

    public function store() {
        $userId = $_SESSION['user_id'] ?? null;
        if (!$userId) {
            header('Location: /pwa/login');
            exit;
        }

        // Get employee ID
        $empModel = new Employee();
        $stmt = $empModel->db->prepare("SELECT id FROM employees WHERE user_id = :uid");
        $stmt->execute([':uid' => $userId]);
        $emp = $stmt->fetch(\PDO::FETCH_ASSOC);

        try {
            $permissionModel = new Permission();
            $permissionModel->create([
                'employee_id'    => $emp['id'],
                'permission_type' => $_POST['permission_type'],
                'start_date'     => $_POST['start_date'],
                'end_date'       => $_POST['end_date'],
                'reason'         => $_POST['reason'],
                'attachment'     => $_FILES['attachment']['name'] ?? null,
            ]);

            $_SESSION['success'] = 'Pengajuan berhasil dikirim';
            header('Location: /pwa/requests');
        } catch (\Exception $e) {
            $_SESSION['error'] = 'Error: ' . $e->getMessage();
            header('Location: /pwa/requests/create');
        }
        exit;
    }
}
?>
