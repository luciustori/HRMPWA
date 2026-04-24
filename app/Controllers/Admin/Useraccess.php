<?php

class UserAccess extends Controller {
    
    // Halaman List Karyawan
    public function index() {
        $data['title'] = 'User Access Manager';
        $model = $this->model('UserAccessModel');
        $data['users'] = $model->getAllUsers();
        
        $data['content_view'] = 'admin/user_access/index';
        $this->view('admin/layouts/admin-layout', $data);
    }

    // Halaman Edit (Bento Grid)
    public function edit($user_id) {
        $model = $this->model('UserAccessModel');
        $user = $model->getUserDetail($user_id);

        if (!$user) {
            Flasher::setFlash('User tidak ditemukan!', 'danger');
            header('Location: ' . BASEURL . '/Admin/UserAccess');
            exit;
        }

        $data['title'] = 'Edit Akses: ' . $user['full_name'];
        $data['user'] = $user;
        
        // Ambil Matrix (Role vs Extra)
        $data['permissions_grouped'] = $model->getUserPermissionsMatrix($user_id, $user['role']);

        $data['content_view'] = 'admin/user_access/edit';
        $this->view('admin/layouts/admin-layout', $data);
    }

    // Proses Simpan
    public function update() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $user_id = $_POST['user_id'];
            // Ambil array permission yang dicentang (hanya yang extra)
            $extra_permissions = $_POST['extra_permissions'] ?? [];

            $model = $this->model('UserAccessModel');
            
            if ($model->updateUserExtraPermissions($user_id, $extra_permissions)) {
                Flasher::setFlash('Akses tambahan berhasil disimpan!', 'success');
            } else {
                Flasher::setFlash('Gagal menyimpan data.', 'danger');
            }

            header('Location: ' . BASEURL . '/admin/useraccess/edit/' . $user_id);
            exit;
        }
    }
}