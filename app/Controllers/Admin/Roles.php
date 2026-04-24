<?php

class Roles extends Controller {
    
    public function index() {
        // Cek Login & Permission (Optional nanti)
        if (!isset($_SESSION['user_id'])) {
            header('Location: ' . BASEURL . '/admin/auth');
            exit;
        }

        $data['title'] = 'Access Control';
        
        // Load Model
        $roleModel = $this->model('RoleModel');

        // Ambil Data
        $data['roles'] = $roleModel->getAllRoles();
        $data['permissions_grouped'] = $roleModel->getAllPermissionsGrouped();
        
        // Default: Tampilkan permission milik Role pertama (misal Super Admin)
        // atau ambil dari parameter URL ?role_id=1
        $selected_role_id = $_GET['role_id'] ?? ($data['roles'][0]['id'] ?? 1);
        $data['selected_role_id'] = $selected_role_id;
        $data['active_permissions'] = $roleModel->getRolePermissions($selected_role_id);

        $data['content_view'] = 'admin/roles/index';
        $this->view('admin/layouts/admin-layout', $data);
    }

    public function update() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $role_id = $_POST['role_id'];
            $permissions = $_POST['permissions'] ?? []; // Array ID permission

            $roleModel = $this->model('RoleModel');
            
            if ($roleModel->updatePermissions($role_id, $permissions)) {
                Flasher::setFlash('Permissions berhasil diperbarui!', 'success');
            } else {
                Flasher::setFlash('Gagal update database.', 'danger');
            }

            header('Location: ' . BASEURL . '/admin/roles?role_id=' . $role_id);
            exit;
        }
    }
}