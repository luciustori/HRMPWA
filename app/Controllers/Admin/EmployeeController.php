<?php
// app/Controllers/Admin/EmployeeController.php

namespace App\Controllers\Admin;

use App\Models\Employee;
use App\Models\Department;
use App\Models\EmployeeLevel;

class EmployeeController {
    public function index() {
        $empModel = new Employee();
        $employees = $empModel->allWithRelations();

        $page_title = 'Kepegawaian';
        $page_content = '../app/Views/Admin/pages/employees.php';
        require '../app/Views/Admin/layouts/main.php';
    }

    public function create() {
        $deptModel = new Department();
        $levelModel = new EmployeeLevel();

        $departments = $deptModel->allWithManager();
        $levels = $levelModel->all();

        $page_title = 'Tambah Karyawan';
        $page_content = '../app/Views/Admin/pages/employee-form.php';
        require '../app/Views/Admin/layouts/main.php';
    }

    public function edit($id) {
        $empModel = new Employee();
        $deptModel = new Department();
        $levelModel = new EmployeeLevel();

        $employee = $empModel->find($id);
        $departments = $deptModel->allWithManager();
        $levels = $levelModel->all();

        if (!$employee) {
            http_response_code(404);
            echo 'Employee not found';
            return;
        }

        $page_title = 'Edit Karyawan';
        $page_content = '../app/Views/Admin/pages/employee-form.php';
        require '../app/Views/Admin/layouts/main.php';
    }

    public function store() {
        try {
            $empModel = new Employee();
            
            if (!empty($_POST['id'])) {
                $empModel->update($_POST['id'], $_POST);
                $_SESSION['success'] = 'Karyawan berhasil diperbarui';
            } else {
                $empModel->create($_POST);
                $_SESSION['success'] = 'Karyawan berhasil ditambahkan';
            }

            header('Location: /admin/employees');
        } catch (\Exception $e) {
            $_SESSION['error'] = 'Error: ' . $e->getMessage();
            header('Location: /admin/employees');
        }
        exit;
    }

    public function delete($id) {
        try {
            $empModel = new Employee();
            $empModel->db->prepare("DELETE FROM employees WHERE id = :id")
                         ->execute([':id' => $id]);
            
            $_SESSION['success'] = 'Karyawan berhasil dihapus';
        } catch (\Exception $e) {
            $_SESSION['error'] = 'Error: ' . $e->getMessage();
        }

        header('Location: /admin/employees');
        exit;
    }
}
?>
