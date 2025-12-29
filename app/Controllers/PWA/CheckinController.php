<?php
// app/Controllers/PWA/CheckinController.php

namespace App\Controllers\PWA;

use App\Models\Attendance;
use App\Models\Employee;
use App\Helpers\GeoHelper;
use App\Helpers\FileHelper;

class CheckinController {
    public function index() {
        $userId = $_SESSION['user_id'] ?? null;
        if (!$userId) {
            header('Location: /pwa/login');
            exit;
        }

        // Get employee with today's shift
        $empModel = new Employee();
        $stmt = $empModel->db->prepare(
            "SELECT e.*, s.start_time, s.end_time, s.work_hours
             FROM employees e
             LEFT JOIN employee_shifts es ON e.id = es.employee_id AND es.shift_date = CURDATE()
             LEFT JOIN shifts s ON es.shift_id = s.id
             WHERE e.user_id = :user_id"
        );
        $stmt->execute([':user_id' => $userId]);
        $employee = $stmt->fetch(\PDO::FETCH_ASSOC);

        $attendanceModel = new Attendance();
        $today_attendance = $attendanceModel->getTodayStatus($employee['id']);

        $page_title = 'Check-In/Out';
        $page_content = '../app/Views/PWA/pages/checkin.php';
        require '../app/Views/PWA/layouts/main.php';
    }

    public function store() {
        // ENDPOINT API untuk checkin
        header('Content-Type: application/json');

        $payload = json_decode(file_get_contents('php://input'), true);
        $userId = $_SESSION['user_id'] ?? null;

        if (!$userId) {
            http_response_code(401);
            echo json_encode(['success' => false, 'message' => 'Unauthorized']);
            return;
        }

        // Get employee
        $empModel = new Employee();
        $stmt = $empModel->db->prepare("SELECT id, company_id FROM employees WHERE user_id = :uid");
        $stmt->execute([':uid' => $userId]);
        $emp = $stmt->fetch(\PDO::FETCH_ASSOC);

        if (!$emp) {
            http_response_code(404);
            echo json_encode(['success' => false, 'message' => 'Employee not found']);
            return;
        }

        // Validasi geo
        $lat = $payload['latitude'] ?? null;
        $lng = $payload['longitude'] ?? null;

        if (!GeoHelper::withinOfficeRadius($emp['company_id'], $lat, $lng)) {
            http_response_code(400);
            echo json_encode([
                'success' => false,
                'message' => 'Anda berada di luar area kantor. Check-in dibatalkan.'
            ]);
            return;
        }

        // Process photo if provided
        $photoPath = null;
        if (!empty($payload['photo'])) {
            $photoPath = FileHelper::saveBase64Image(
                $payload['photo'],
                'checkin',
                $emp['id']
            );
        }

        // Checkin
        $attendanceModel = new Attendance();
        $result = $attendanceModel->checkIn($emp['id'], $lat, $lng, $photoPath);

        if ($result) {
            http_response_code(200);
            echo json_encode([
                'success' => true,
                'message' => 'Check-in berhasil',
                'data' => [
                    'check_in_time' => $result['check_in'],
                    'lateness' => $result['lateness_minutes'] . ' menit terlambat'
                ]
            ]);
        } else {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'Check-in gagal']);
        }
    }

    public function storeCheckout() {
        header('Content-Type: application/json');

        $payload = json_decode(file_get_contents('php://input'), true);
        $userId = $_SESSION['user_id'] ?? null;

        if (!$userId) {
            http_response_code(401);
            echo json_encode(['success' => false, 'message' => 'Unauthorized']);
            return;
        }

        $empModel = new Employee();
        $stmt = $empModel->db->prepare("SELECT id FROM employees WHERE user_id = :uid");
        $stmt->execute([':uid' => $userId]);
        $emp = $stmt->fetch(\PDO::FETCH_ASSOC);

        $photoPath = null;
        if (!empty($payload['photo'])) {
            $photoPath = FileHelper::saveBase64Image($payload['photo'], 'checkout', $emp['id']);
        }

        $attendanceModel = new Attendance();
        $result = $attendanceModel->checkOut($emp['id'], $payload['latitude'], $payload['longitude'], $photoPath);

        if ($result) {
            echo json_encode(['success' => true, 'message' => 'Check-out berhasil']);
        } else {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'Check-out gagal']);
        }
    }
}
?>
