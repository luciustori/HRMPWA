<?php
// File: app/Core/App.php

class App {
    // Default Controller jika URL kosong
    // protected $controller = 'AuthController'; 
    protected $controller = 'Auth'; 
    protected $method = 'index';
    protected $params = [];

    public function __construct() {
        $url = $this->parseURL();
        $folder = ''; // Default di root Controllers/

        // --- LOGIC BARU: DETEKSI FOLDER ADMIN/PWA ---
        
        // 1. Cek apakah segmen pertama adalah 'admin' atau 'pwa'
        if (isset($url[0])) {
            $prefix = strtolower($url[0]);
            
            if ($prefix == 'admin') {
                $folder = 'Admin/';
                array_shift($url); // Buang 'admin' dari URL, sisanya ['dashboard', ...]
                
                // Jika setelah /admin tidak ada apa-apa, default ke Dashboard
                if (empty($url)) {
                    $url[0] = 'Dashboard';
                }
            } 
            elseif ($prefix == 'pwa') {
                $folder = 'Pwa/';
                array_shift($url); // Buang 'pwa' dari URL
                
                if (empty($url)) {
                    $url[0] = 'Home';
                }
            }
        }

        // 2. Cek Controller di dalam folder yang sudah ditentukan
        if (isset($url[0])) {
            if (file_exists('../app/Controllers/' . $folder . ucfirst($url[0]) . '.php')) {
                $this->controller = ucfirst($url[0]);
                unset($url[0]);
            }
        }

        // 3. Require Controller
        // Fallback: Jika controller tidak ditemukan di folder tersebut, script akan error.
        // Kita perlu pastikan filenya ada.
        $controllerFile = '../app/Controllers/' . $folder . $this->controller . '.php';
        
        if (file_exists($controllerFile)) {
            require_once $controllerFile;
            $this->controller = new $this->controller;
        } else {
            // Error Handling Sederhana: Tampilkan 404 jika file controller tidak ada
            // Tapi khusus AuthController (Default) pasti ada.
            die("Controller not found: " . $controllerFile);
        }

        // 4. Cek Method
        if (isset($url[1])) {
            if (method_exists($this->controller, $url[1])) {
                $this->method = $url[1];
                unset($url[1]);
            }
        }

        // 5. Params
        if (!empty($url)) {
            $this->params = array_values($url);
        }

        // 6. Jalankan
        call_user_func_array([$this->controller, $this->method], $this->params);
    }

    public function parseURL() {
        if (isset($_GET['url'])) {
            $url = rtrim($_GET['url'], '/');
            $url = filter_var($url, FILTER_SANITIZE_URL);
            return explode('/', $url);
        }
        return []; // Return array kosong jika tidak ada URL
    }
}