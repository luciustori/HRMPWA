<?php
namespace App\Controllers\Staff;

use App\Core\Controller; // Sesuaikan dengan Base Controller kamu

class SchedulesController extends Controller
{
    public function __construct()
    {
        // Cek apakah session role-nya 'staff'
        if (!isset($_SESSION['user_id']) || !isset($_SESSION['role']) || $_SESSION['role'] !== 'staff') {
            // Redirect ke controller StaffAuth yang baru kita buat
            header('Location: /staff/staffauth/login'); 
            exit;
        }
    }
    

    public function index()
    {
        // 1. Ambil User ID dari Session
        $userId = $_SESSION['user_id'];
        
        // 2. Filter Tanggal (Default: Bulan & Tahun Ini)
        $month = $_GET['month'] ?? date('m');
        $year  = $_GET['year']  ?? date('Y');

        // Validasi input agar tidak error di query
        $month = sprintf("%02d", min(12, max(1, $month))); 
        $year  = (int)$year;

        // 3. Query Statistik Bulan Ini
        // Hitung total hari, tepat waktu, telat, dan cuti
        $statsSql = "
            SELECT 
                COUNT(*) as total_days,
                SUM(CASE WHEN status = 'PRESENT' AND is_late = 0 THEN 1 ELSE 0 END) as on_time,
                SUM(CASE WHEN is_late = 1 THEN 1 ELSE 0 END) as late,
                SUM(CASE WHEN status IN ('SICK', 'PERMIT', 'ANNUAL', 'MARRIAGE_SELF', 'MARRIAGE_CHILD', 'CIRCUM_BAPTISM', 'PATERNITY', 'DEATH_CORE', 'DEATH_HOME', 'MATERNITY', 'MISCARRIAGE', 'MENSTRUAL') THEN 1 ELSE 0 END) as leave_taken
            FROM attendance 
            WHERE user_id = ? 
            AND MONTH(date) = ? AND YEAR(date) = ?
        ";
        
        // Eksekusi Query Stats (Gunakan Prepared Statement agar aman)
        $stmtStats = $this->db->prepare($statsSql);
        $stmtStats->bind_param('iss', $userId, $month, $year);
        $stmtStats->execute();
        $statsResult = $stmtStats->get_result();
        $stats = $statsResult->fetch_assoc();
        $stmtStats->close();

        // 4. Query Data Kalender (Ambil semua absen bulan ini)
        $calendarSql = "
            SELECT date, status, clock_in, clock_out, is_late 
            FROM attendance 
            WHERE user_id = ? AND MONTH(date) = ? AND YEAR(date) = ?
        ";
        
        $stmtCal = $this->db->prepare($calendarSql);
        $stmtCal->bind_param('iss', $userId, $month, $year);
        $stmtCal->execute();
        $attendanceRaw = $stmtCal->get_result()->fetch_all(MYSQLI_ASSOC);
        $stmtCal->close();

        // Mapping data ke array dengan key Tanggal (biar mudah dipanggil di View)
        $calendarData = [];
        foreach ($attendanceRaw as $row) {
            $calendarData[$row['date']] = $row;
        }

        // 5. Logika Helper Kalender
        $daysInMonth = cal_days_in_month(CAL_GREGORIAN, $month, $year);
        // Cari tahu tanggal 1 hari apa (1=Senin ... 7=Minggu)
        $firstDayName = date('N', strtotime("$year-$month-01"));
        $startDayOffset = $firstDayName - 1; // Jika Senin(1), offset 0. Jika Selasa(2), offset 1.

        // 6. Query History Terakhir (Limit 10)
        $historySql = "
            SELECT * FROM attendance 
            WHERE user_id = ? 
            ORDER BY date DESC LIMIT 10
        ";
        $stmtHist = $this->db->prepare($historySql);
        $stmtHist->bind_param('i', $userId);
        $stmtHist->execute();
        $history = $stmtHist->get_result()->fetch_all(MYSQLI_ASSOC);
        $stmtHist->close();

        // 7. Kirim Data ke View
        $data = [
            'title' => 'Jadwal & Riwayat Absensi',
            'stats' => $stats,
            'calendar' => [
                'month' => $month,
                'year' => $year,
                'days_in_month' => $daysInMonth,
                'start_day_offset' => $startDayOffset,
                'data' => $calendarData
            ],
            'history' => $history
        ];

        // Render View
        $this->view('staff/schedules/index', $data);
    }
}
