<?php
// File: app/Models/KpiCalculator.php

class KpiCalculator {
    private $db;

    public function __construct() {
        $this->db = new Database;
    }

    /**
     * HELPER 1: Tentukan Start & End Date (Siklus 25 - 24)
     */
    private function getCycleDateRange($month, $year) {
        $start_month = $month - 1;
        $start_year  = $year;

        // Mundur satu tahun jika bulannya Januari
        if ($start_month == 0) {
            $start_month = 12;
            $start_year  = $year - 1;
        }

        // Format tanggal: YYYY-MM-DD
        $start_date = "$start_year-$start_month-25";
        $end_date   = "$year-$month-24";

        return ['start' => $start_date, 'end' => $end_date];
    }

    /**
     * HELPER 2: Hitung Hari Kerja Efektif (Senin-Jumat - Libur)
     */
    private function countWorkingDays($startDate, $endDate) {
        $start = new DateTime($startDate);
        $end = new DateTime($endDate);
        $end->modify('+1 day'); 
        
        $interval = new DateInterval('P1D');
        $period = new DatePeriod($start, $interval, $end);

        $workingDays = 0;
        foreach ($period as $dt) {
            // 'N' => 1 (Senin) s/d 7 (Minggu). Sabtu(6) Minggu(7) Libur
            if ($dt->format('N') < 6) { 
                $workingDays++;
            }
        }

        // Kurangi Libur Nasional (Exclude Sabtu Minggu)
        $query = "SELECT COUNT(*) as total_holidays 
                  FROM holidays 
                  WHERE holiday_date BETWEEN :start AND :end 
                  AND is_active = 1
                  AND DAYOFWEEK(holiday_date) NOT IN (1, 7)"; 
        
        $this->db->query($query);
        $this->db->bind(':start', $startDate);
        $this->db->bind(':end', $endDate);
        $hol = $this->db->single();
        
        $netWorkingDays = $workingDays - ($hol['total_holidays'] ?? 0);
        return ($netWorkingDays > 0) ? $netWorkingDays : 22; 
    }

    /**
     * FUNGSI HITUNG (CALCULATE)
     */
    public function calculate($employee_id, $month, $year) {
        // 1. DAPATKAN RANGE TANGGAL (Logika 25-24)
        $range = $this->getCycleDateRange($month, $year);
        $startDate = $range['start'];
        $endDate   = $range['end'];

        // 2. HITUNG HARI KERJA EFEKTIF
        $total_working_days = $this->countWorkingDays($startDate, $endDate);

        // 3. Ambil User ID
        $this->db->query("SELECT id FROM users WHERE employee_id = :eid");
        $this->db->bind(':eid', $employee_id);
        $u = $this->db->single();
        $user_id = $u['id'] ?? 0;

        // 4. DATA TUGAS (Query pakai BETWEEN)
        $this->db->query("SELECT 
                COUNT(*) as total_assigned,
                SUM(CASE WHEN status = 'completed' THEN 1 ELSE 0 END) as total_completed,
                SUM(CASE WHEN status = 'completed' AND completed_at <= due_date THEN 1 ELSE 0 END) as total_ontime,
                SUM(CASE WHEN status = 'completed' THEN estimated_hours ELSE 0 END) as total_est_hours,
                SUM(CASE WHEN status = 'completed' THEN actual_hours ELSE 0 END) as total_act_hours,
                IFNULL(AVG(kpi_score_final), 0) as avg_quality
            FROM tasks 
            WHERE assigned_to = :uid 
            AND status != 'cancelled' 
            AND created_at BETWEEN :start AND :end");
            
        $this->db->bind(':uid', $user_id);
        $this->db->bind(':start', $startDate . ' 00:00:00');
        $this->db->bind(':end', $endDate . ' 23:59:59');
        $task = $this->db->single();

        // 5. DATA ABSENSI (Query pakai BETWEEN)
        $this->db->query("SELECT 
                SUM(CASE WHEN status = 'present' AND is_late = 0 THEN 1 ELSE 0 END) as on_time
            FROM attendance_records 
            WHERE employee_id = :eid 
            AND attendance_date BETWEEN :start AND :end");
            
        $this->db->bind(':eid', $employee_id);
        $this->db->bind(':start', $startDate);
        $this->db->bind(':end', $endDate);
        $att = $this->db->single();

        // 6. HITUNG SCORE
        $rate_prod = ($task['total_assigned'] > 0) ? ($task['total_completed'] / $task['total_assigned']) * 100 : 0;
        $rate_speed = ($task['total_completed'] > 0) ? ($task['total_ontime'] / $task['total_completed']) * 100 : 0;
        
        $raw_qual = $task['avg_quality'];
        $rate_quality = ($raw_qual <= 10 && $raw_qual > 0) ? $raw_qual * 10 : $raw_qual;

        $attendance_count = $att['on_time'] ?? 0;
        $rate_discipline = ($attendance_count / $total_working_days) * 100;
        if($rate_discipline > 100) $rate_discipline = 100;

        // Bobot: Prod 30%, Quality 30%, Discipline 20%, Speed 20%
        $final_score = ($rate_prod * 0.3) + ($rate_quality * 0.3) + ($rate_discipline * 0.2) + ($rate_speed * 0.2);

        // Grade Logic
        $grade = 'D'; $theme = 'red'; $rec = "Perlu evaluasi kinerja.";
        if ($final_score >= 90) { $grade = 'A+'; $theme='emerald'; $rec="Luar Biasa!"; }
        elseif ($final_score >= 80) { $grade = 'A'; $theme='green'; $rec="Sangat Bagus."; }
        elseif ($final_score >= 70) { $grade = 'B'; $theme='blue'; $rec="Baik, pertahankan."; }
        elseif ($final_score >= 60) { $grade = 'C'; $theme='yellow'; $rec="Cukup, tingkatkan lagi."; }

        return [
            'final_score' => round($final_score, 1),
            'grade' => $grade,
            'theme' => $theme,
            'recommendation' => $rec,
            'prod' => round($rate_prod),
            'qual' => round($rate_quality),
            'disc' => round($rate_discipline),
            'speed' => round($rate_speed),
            'raw_task' => $task,
            'raw_att' => $att,
            'period_info' => [
                'start' => $startDate,
                'end' => $endDate,
                'total_working_days' => $total_working_days
            ]
        ];
    }

    /**
     * FUNGSI SINKRONISASI KE DATABASE (PENTING UNTUK GENERATOR)
     */
    public function syncToDatabase($employee_id, $month, $year) {
        $metrics = $this->calculate($employee_id, $month, $year);
        $t = $metrics['raw_task'];

        $query = "INSERT INTO kpi_summary 
                    (employee_id, year, month, 
                     total_tasks_assigned, total_tasks_completed, 
                     total_tasks_on_time, total_tasks_late,
                     average_quality_score, total_estimated_hours, total_actual_hours,
                     productivity_score, kpi_score, calculated_at)
                  VALUES 
                    (:eid, :y, :m, 
                     :assigned, :completed, 
                     :ontime, :late,
                     :avg_qual, :est_hours, :act_hours,
                     :prod_score, :final_score, NOW())
                  ON DUPLICATE KEY UPDATE
                     total_tasks_assigned = VALUES(total_tasks_assigned),
                     total_tasks_completed = VALUES(total_tasks_completed),
                     total_tasks_on_time = VALUES(total_tasks_on_time),
                     average_quality_score = VALUES(average_quality_score),
                     total_estimated_hours = VALUES(total_estimated_hours),
                     total_actual_hours = VALUES(total_actual_hours),
                     productivity_score = VALUES(productivity_score),
                     kpi_score = VALUES(kpi_score),
                     updated_at = NOW()";

        $this->db->query($query);
        
        $this->db->bind(':eid', $employee_id);
        $this->db->bind(':y', $year);
        $this->db->bind(':m', $month);
        
        $this->db->bind(':assigned', $t['total_assigned']);
        $this->db->bind(':completed', $t['total_completed']);
        $this->db->bind(':ontime', $t['total_ontime']);
        $this->db->bind(':late', ($t['total_completed'] - $t['total_ontime']));
        
        $this->db->bind(':avg_qual', $t['avg_quality']);
        $this->db->bind(':est_hours', $t['total_est_hours'] ?? 0);
        $this->db->bind(':act_hours', $t['total_act_hours'] ?? 0);
        
        $this->db->bind(':prod_score', $metrics['prod']); 
        $this->db->bind(':final_score', $metrics['final_score']); 

        return $this->db->execute();
    }
}