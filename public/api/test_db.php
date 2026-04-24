<?php
// 1. Tampilkan semua error (biar gak ada dusta di antara kita)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

echo "<h1>🔍 Diagnosa Koneksi Database</h1>";
echo "<hr>";

// 2. Cek Path File Database
// Karena file ini ada di folder 'api', kita panggil 'config/database.php'
$path_db = 'config/database.php';

if (file_exists($path_db)) {
    echo "✅ File <code>database.php</code> DITEMUKAN.<br>";
    
    // Cek Permission Read
    if(is_readable($path_db)){
        echo "✅ File bisa dibaca (Readable).<br>";
    } else {
        die("❌ File ada tapi TIDAK BISA DIBACA (Cek Permission).<br>");
    }

} else {
    // Coba cek path alternatif (siapa tau lo taruh di tempat lain)
    die("❌ <b>FATAL:</b> File <code>config/database.php</code> TIDAK DITEMUKAN di: " . realpath('.') . "/" . $path_db);
}

// 3. Include File Database
echo "🔄 Mencoba require_once...<br>";
try {
    require_once $path_db;
    echo "✅ Berhasil include database.php.<br>";
} catch (Exception $e) {
    die("❌ Gagal include: " . $e->getMessage());
}

// 4. Test Koneksi Real
echo "🔌 Mencoba koneksi ke MySQL...<br>";

// Pastikan class Database ada
if (class_exists('Database')) {
    $database = new Database();
    $db = $database->getConnection();

    if ($db) {
        echo "<h2 style='color:green;'>✅ KONEKSI SUKSES! 🚀</h2>";
        echo "Database siap menerima perintah.";
    } else {
        echo "<h2 style='color:red;'>❌ KONEKSI GAGAL (Return NULL).</h2>";
        echo "Cek username, password, atau nama database di file <code>database.php</code>.";
    }
} else {
    die("❌ Class 'Database' tidak ditemukan di dalam file database.php!");
}
?>