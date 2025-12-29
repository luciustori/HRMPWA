<?php
// app/Controllers/Admin/ApprovalController.php

namespace App\Controllers\Admin;

use App\Models\Approval;

class ApprovalController {
    public function index() {
        $approvalModel = new Approval();
        $approvals = $approvalModel->pendingForUser($_SESSION['user_id']);

        $page_title = 'Persetujuan Pengajuan';
        $page_content = '../app/Views/Admin/pages/approvals.php';
        require '../app/Views/Admin/layouts/main.php';
    }

    public function approve($id) {
        try {
            $approvalModel = new Approval();
            $approvalModel->updateStatus($id, 'approved', $_POST['note'] ?? '');

            $_SESSION['success'] = 'Pengajuan disetujui';
            header('Location: /admin/approvals');
        } catch (\Exception $e) {
            $_SESSION['error'] = 'Error: ' . $e->getMessage();
            header('Location: /admin/approvals');
        }
        exit;
    }

    public function reject($id) {
        try {
            $approvalModel = new Approval();
            $approvalModel->updateStatus($id, 'rejected', $_POST['note'] ?? '');

            $_SESSION['success'] = 'Pengajuan ditolak';
            header('Location: /admin/approvals');
        } catch (\Exception $e) {
            $_SESSION['error'] = 'Error: ' . $e->getMessage();
            header('Location: /admin/approvals');
        }
        exit;
    }
}
?>
