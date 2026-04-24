<?php
// Tampilkan semua error PHP
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<h1>🔍 DIAGNOSA DATABASE LOCALHOST</h1>";
echo "<hr>";

// Cek File Ada Gak?
$file_db = 'config/database.php';
if (!file_exists($file_db)) {
    die("<h3 style='color:red'>❌ File config/database.php TIDAK DITEMUKAN!</h3>Pastikan struktur folder bener: api/config/database.php");
}

echo "✅ File database.php ditemukan.<br>";
require_once $file_db;

if (class_exists('Database')) {
    echo "✅ Class Database ditemukan.<br>";
    
    $database = new Database();
    
    echo "🔄 Sedang mencoba menghubungi MySQL...<br>";
    
    // Panggil fungsi koneksi
    $db = $database->getConnection();

    if ($db) {
        echo "<h2 style='color:green'>🎉 KONEKSI SUKSES!</h2>";
        echo "Database Localhost siap digunakan.";
    } else {
        echo "<h2 style='color:red'>💀 KONEKSI GAGAL (Return Null)</h2>";
    }

} else {
    echo "<h3 style='color:red'>❌ Class 'Database' tidak ada di dalam file!</h3>";
}
?>