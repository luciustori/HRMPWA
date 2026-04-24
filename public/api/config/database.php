<?php
class Database {
    // Host biasanya sama-sama localhost (baik di XAMPP/Laragon maupun cPanel)
    private $host = "localhost";
    
    // Variabel yang bakal berubah otomatis
    private $db_name;
    private $username;
    private $password;
    
    public $conn;

    public function getConnection() {
        $this->conn = null;

        // =========================================================
        // 🕵️ AUTO-DETECT ENVIRONMENT (JURUS HYBRID)
        // =========================================================
        
        // Cek nama server yang sedang jalan
        $serverName = $_SERVER['SERVER_NAME'];

        // List domain lokal (Laptop)
        $localList = ['localhost', '127.0.0.1', '::1'];

        if (in_array($serverName, $localList)) {
            // === MODE: LOCALHOST (Laptop/Laragon) ===
            $this->db_name  = "mobile_db";  // Nama DB di Laptop
            $this->username = "root";       // User default Laptop
            $this->password = "";           // Password kosong
        } else {
            // === MODE: LIVE HOSTING (cPanel) ===
            $this->db_name  = "u3938658_sistemhr";      // Nama DB Hosting
            $this->username = "u3938658_adminsistemhr"; // User DB Hosting
            $this->password = "X70)_'9%v|2#..";         // Password Hosting
        }

        // =========================================================
        // EKSEKUSI KONEKSI
        // =========================================================
        try {
            $this->conn = new PDO(
                "mysql:host=" . $this->host . ";dbname=" . $this->db_name, 
                $this->username, 
                $this->password
            );
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->conn->exec("set names utf8");
        } catch(PDOException $exception) {
            // Kosongkan catch agar tidak merusak format JSON jika error
            // error_log("Connection error: " . $exception->getMessage()); // Opsi: log ke file error server
        }

        return $this->conn;
    }
}
// Selesai tanpa tutup PHP biar aman dari spasi ghaib