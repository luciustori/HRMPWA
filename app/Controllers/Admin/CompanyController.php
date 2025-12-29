<?php
// app/Controllers/Admin/CompanyController.php

namespace App\Controllers\Admin;

use App\Models\Company;

class CompanyController {
    public function index() {
        $companyModel = new Company();
        $company = $companyModel->getFirst() ?? [];

        $page_title = 'Pengaturan Perusahaan';
        $page_content = '../app/Views/Admin/pages/company.php';
        require '../app/Views/Admin/layouts/main.php';
    }

    public function save() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            return;
        }

        try {
            $companyModel = new Company();
            $companyModel->save($_POST);

            $_SESSION['success'] = 'Pengaturan perusahaan berhasil diperbarui';
            header('Location: /admin/company');
        } catch (\Exception $e) {
            $_SESSION['error'] = 'Error: ' . $e->getMessage();
            header('Location: /admin/company');
        }
        exit;
    }
}
?>
