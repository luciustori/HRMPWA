<?php
// ==========================================
// FIX FULL: login.php (Anti "Unexpected token")
// ==========================================

// 1. HEADER CORS (Wajib Paling Atas)
header("Access-Control-Allow-Origin: *"); 
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

// Handle Preflight
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    http_response_code(200);
    exit();
}

// Error Reporting (Boleh dimatikan saat production)
ini_set('display_errors', 0); // Ubah 0 biar gak ngerusak JSON
error_reporting(E_ALL);

// 2. KONEKSI DATABASE
$databasePath = __DIR__ . '/../config/database.php';
if (!file_exists($databasePath)) {
    http_response_code(500);
    echo json_encode(["status" => false, "message" => "File database.php hilang!"]);
    exit();
}

require_once $databasePath;

$database = new Database();
$db = $database->getConnection();

// 3. CEK KONEKSI (Disini kita tangkap errornya jadi JSON)
if (!$db) {
    http_response_code(500);
    echo json_encode([
        "status" => false, 
        "message" => "Gagal konek ke Database! Cek username/password di database.php"
    ]);
    exit();
}

// 4. AMBIL INPUT JSON
$data = json_decode(file_get_contents("php://input"));

if (empty($data->username) || empty($data->password)) {
    http_response_code(400);
    echo json_encode(["status" => false, "message" => "Username dan Password harus diisi!"]);
    exit();
}

$username = $data->username;
$password = $data->password;

try {
    // 5. QUERY USER
    // Join ke employees untuk ambil data lengkap
    $query = "SELECT 
                u.id as user_id, 
                u.username, 
                u.password, 
                u.role, 
                u.is_active,
                e.id as employee_id,
                e.first_name,
                e.last_name,
                e.employee_number,
                e.profile_photo_path,
                d.department_name
            FROM users u
            JOIN employees e ON u.employee_id = e.id
            LEFT JOIN departments d ON e.department_id = d.id
            WHERE u.username = :username 
            LIMIT 1";

    $stmt = $db->prepare($query);
    $stmt->bindParam(":username", $username);
    $stmt->execute();
    
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    // Cek User Ada?
    if (!$user) {
        http_response_code(401); 
        echo json_encode(["status" => false, "message" => "Username tidak ditemukan."]);
        exit();
    }

    // Cek Aktif?
    if ($user['is_active'] == 0) {
        http_response_code(403); 
        echo json_encode(["status" => false, "message" => "Akun dinonaktifkan."]);
        exit();
    }

    // 6. VERIFIKASI PASSWORD
    // Cek Hash dulu (Standar), kalau gagal cek Plain Text (Jaga-jaga data manual)
    $isPasswordValid = password_verify($password, $user['password']);
    
    // Fallback: Kalau password di DB bukan hash (masih plain text "password")
    if (!$isPasswordValid && $password === $user['password']) {
        $isPasswordValid = true;
    }

    if ($isPasswordValid) {
        // Hapus password dari hasil
        unset($user['password']);

        // Format Nama
        $fullName = trim($user['first_name'] . ' ' . ($user['last_name'] ?? ''));
        
        // Response JSON User Data
        $userData = [
            'id' => $user['user_id'],
            'employee_id' => $user['employee_id'],
            'username' => $user['username'],
            'full_name' => $fullName,
            'role' => $user['role'],
            'nik' => $user['employee_number'],
            'department' => $user['department_name'] ?? '-',
            'photo' => $user['profile_photo_path']
        ];

        http_response_code(200);
        echo json_encode([
            "status" => true,
            "message" => "Login Berhasil!",
            "user" => $userData
        ]);

    } else {
        http_response_code(401);
        echo json_encode(["status" => false, "message" => "Password Salah!"]);
    }

} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(["status" => false, "message" => "Database Error: " . $e->getMessage()]);
}
?>