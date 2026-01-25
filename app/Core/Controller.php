<?php
class Controller {
    // Load Model
    public function model($model) {
        require_once '../app/Models/' . $model . '.php';
        return new $model;
    }

    // Load View
    public function view($view, $data = []) {
        // Ekstrak data agar bisa dipanggil variabelnya langsung di view
        // misal: $data['title'] menjadi $title
        extract($data);
        
        if (file_exists('../app/Views/' . $view . '.php')) {
            require_once '../app/Views/' . $view . '.php';
        } else {
            die("View '$view' does not exist.");
        }
    }
    
    // Redirect Helper
    public function redirect($url) {
        header('Location: ' . BASEURL . $url);
        exit;
    }
}