<?php

class App {
    protected $controller = 'Auth'; // Default Controller (Ganti LoginController kalo perlu)
    protected $method = 'index';
    protected $params = [];

    public function __construct() {
        $url = $this->parseURL();
        
        $folder = ''; // Default di root Controllers/
        
        // --- LOGIC BARU: DETEKSI FOLDER ADMIN/PWA/STAFF ---
        
        // 1. Cek apakah segmen pertama adalah 'admin', 'pwa', atau 'staff'
        if (isset($url[0])) {
            $prefix = strtolower($url[0]);
            
            if ($prefix == 'admin') {
                $folder = 'Admin/';
                array_shift($url); // Buang 'admin' dari URL
                // Jika setelah /admin kosong, default ke Dashboard
                if (empty($url)) {
                    $url[0] = 'Dashboard';
                }
            } elseif ($prefix == 'pwa') {
                $folder = 'Pwa/';
                array_shift($url); // Buang 'pwa'
                if (empty($url)) {
                    $url[0] = 'Home';
                }
            } elseif ($prefix == 'staff') {
                // --- INI BLOK STAFF BARU ---
                $folder = 'Staff/';
                array_shift($url); // Buang 'staff'
                if (empty($url)) {
                    $url[0] = 'Dashboard';
                }
            }
        }

        // 2. Cek Controller di dalam folder yang sudah ditentukan
        if (isset($url[0])) {
            // FIX: Ubah snake_case jadi PascalCase (cth: salary_grade -> SalaryGrade)
            $formatted_name = str_replace('_', ' ', $url[0]);
            $formatted_name = ucwords($formatted_name);
            $className = str_replace(' ', '', $formatted_name);
            
            if (file_exists('../app/Controllers/' . $folder . $className . '.php')) {
                $this->controller = $className;
                unset($url[0]);
            }
            elseif (!empty($folder) && file_exists('../app/Controllers/' . $className . '.php')) {
                $folder = ''; // Reset ke root
                $this->controller = $className;
                unset($url[0]);
            }
        }

        // 3. Require Controller
        $controllerFile = '../app/Controllers/' . $folder . $this->controller . '.php';
        
        if (file_exists($controllerFile)) {
            require_once $controllerFile;
            $this->controller = new $this->controller;
        } else {
            // Error Handling: Tampilkan pesan controller not found
            // Khusus Auth/Login default mungkin aman, tapi kalau URL ngaco:
            // die("Controller not found: " . $controllerFile);
                        // Error Handling Lebih Baik
            // Bisa diganti redirect ke 404 page:
            // require_once '../app/Views/errors/404.php'; die();
            
            echo "<div style='font-family:sans-serif; padding:20px; text-align:center; margin-top:50px;'>";
            echo "<h1>404 Not Found</h1>";
            echo "<p>Controller <b>{$this->controller}</b> tidak ditemukan di <code>{$controllerFile}</code></p>";
            echo "<a href='" . BASEURL . "'>Kembali ke Home</a>";
            echo "</div>";
            die();
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

        // 6. Jalankan Controller & Method
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
