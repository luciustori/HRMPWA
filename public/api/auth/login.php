<?php
// 1. HEADER ANTI-BLOKIR (CORS)
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    http_response_code(200);
    exit();
}

include_once '../config/database.php';
$database = new Database();
$db = $database->getConnection();

// ==========================================================
// 2. PENANGKAP INPUT (UNIVERSAL)
// ==========================================================
$input_user = null; // Bisa username, bisa NIK
$password = null;

// Coba Ambil dari JSON
$jsonRaw = file_get_contents("php://input");
$jsonData = json_decode($jsonRaw, true);

if (!empty($jsonData)) {
    // Kita tangkap apapun yang dikirim sebagai 'username' atau 'nik'
    $input_user = isset($jsonData['username']) ? $jsonData['username'] : (isset($jsonData['nik']) ? $jsonData['nik'] : null);
    $password = isset($jsonData['password']) ? $jsonData['password'] : null;
} 
// Coba Ambil dari POST
else {
    $input_user = isset($_POST['username']) ? $_POST['username'] : (isset($_POST['nik']) ? $_POST['nik'] : null);
    $password = isset($_POST['password']) ? $_POST['password'] : null;
}

// Validasi Kosong
if (empty($input_user) || empty($password)) {
    echo json_encode([
        "status" => false,
        "message" => "NIK/Username dan Password tidak boleh kosong!"
    ]);
    exit();
}

// ==========================================================
// 3. QUERY DATABASE (SUDAH DI-JOIN DENGAN EMPLOYEES) 🛠️
// ==========================================================
try {
    // KITA JOIN TABEL users DAN employees
    // Biar data full_name dan first_name langsung ikut terkirim ke frontend
    $query = "SELECT 
                u.id as user_id, 
                u.username, 
                u.password,
                u.role, 
                u.employee_id,
                e.employee_number,
                e.first_name, 
                e.full_name, 
                e.profile_photo_path
              FROM users u 
              JOIN employees e ON u.employee_id = e.id 
              WHERE u.username = :user LIMIT 1";

    $stmt = $db->prepare($query);
    $stmt->bindParam(":user", $input_user);
    $stmt->execute();

    if ($stmt->rowCount() > 0) {
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        
        // Cek Password
        if (password_verify($password, $row['password']) || $password == $row['password']) {
            // Hapus password dari response biar aman
            unset($row['password']); 
            
            echo json_encode([
                "status" => true,
                "message" => "Login Berhasil!",
                "user" => $row
            ]);
        } else {
            echo json_encode([
                "status" => false,
                "message" => "Password Salah!"
            ]);
        }
    } else {
        echo json_encode([
            "status" => false,
            "message" => "User / NIK tidak ditemukan!"
        ]);
    }
} catch (PDOException $e) {
    echo json_encode([
        "status" => false,
        "message" => "Database Error: " . $e->getMessage()
    ]);
}
?>