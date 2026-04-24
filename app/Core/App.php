<?php
// File: app/Core/App.php

class App {
    protected $controller = 'Auth'; 
    protected $method = 'index';
    protected $params = [];

    public function __construct() {
        $url = $this->parseURL();
        $folder = ''; 
        
        // 1. Deteksi Folder Prefix (admin/staff/pwa)
        if (isset($url[0])) {
            $prefix = strtolower($url[0]);
            
            if ($prefix == 'admin') {
                $folder = 'Admin/';
                array_shift($url);
                if (empty($url)) $url[0] = 'Dashboard';
            } elseif ($prefix == 'pwa') {
                $folder = 'Pwa/';
                array_shift($url);
                if (empty($url)) $url[0] = 'Home';
            } elseif ($prefix == 'staff') {
                $folder = 'Staff/';
                array_shift($url);
                if (empty($url)) $url[0] = 'Dashboard';
            }
        }

        // 2. PascalCase Converter (FIX CASE-SENSITIVE)
        if (isset($url[0])) {
            // Mengubah 'salary_grade' menjadi 'SalaryGrade'
            $formattedName = str_replace(['-', '_'], ' ', $url[0]);
            $formattedName = ucwords($formattedName);
            $controllerName = str_replace(' ', '', $formattedName);

            $controllerFile = '../app/Controllers/' . $folder . $controllerName . '.php';
            
            if (file_exists($controllerFile)) {
                $this->controller = $controllerName;
                unset($url[0]);
            } else {
                // Tampilkan error jika file fisik tidak ditemukan
                echo "<div style='font-family:sans-serif; padding:20px; border:3px solid red;'>";
                echo "<h1>404 Controller Not Found</h1>";
                echo "<p>Sistem mencari file: <code>{$controllerFile}</code></p>";
                echo "<p>Pastikan nama file di folder <b>Controllers/{$folder}</b> adalah <b>{$controllerName}.php</b></p>";
                echo "</div>";
                die();
            }
        }

        // 3. Require & Instansiasi
        require_once '../app/Controllers/' . $folder . $this->controller . '.php';
        $this->controller = new $this->controller;

        // 4. Cek Method
        if (isset($url[1])) {
            if (method_exists($this->controller, $url[1])) {
                $this->method = $url[1];
                unset($url[1]);
            }
        }

        // 5. Jalankan dengan Params
        if (!empty($url)) {
            $this->params = array_values($url);
        }

        call_user_func_array([$this->controller, $this->method], $this->params);
    }

    public function parseURL() {
        if (isset($_GET['url'])) {
            $url = rtrim($_GET['url'], '/');
            $url = filter_var($url, FILTER_SANITIZE_URL);
            return explode('/', $url);
        }
        return [];
    }
}