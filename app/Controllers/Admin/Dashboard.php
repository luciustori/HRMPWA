<?php

class Dashboard extends Controller {
    
    private $db; 

    public function __construct() {
        parent::__construct(); 
        if (!session_id()) session_start();

        if (!isset($_SESSION['user_id'])) {
            header('Location: ' . BASEURL . '/Admin/LoginController');
            exit;
        }

        $role = strtolower($_SESSION['role'] ?? '');
        if ($role !== 'admin' && $role !== 'super_admin') {
            header('Location: ' . BASEURL . '/staff/dashboard');
            exit;
        }

        $this->db = new Database;
    }

    public function index() {
        // ... (Logic index tetap sama seperti sebelumnya, tidak perlu diubah) ...
        // Agar tidak kepanjangan, saya skip bagian index() karena fokus kita di print_report()
        // Silakan gunakan method index() dari kode revisi terakhir.
        
        // SAYA TULIS ULANG FULL AGAR BISA LANGSUNG COPY-PASTE 1 FILE
        $data['title'] = 'Executive Dashboard';
        $selected_month = $_GET['month'] ?? date('m');
        $selected_year  = $_GET['year'] ?? date('Y');
        $start_date = date('Y-m-d', strtotime("$selected_year-$selected_month-25 -1 month"));
        $end_date   = "$selected_year-$selected_month-24";
        $period_text = date('d M Y', strtotime($start_date)) . " - " . date('d M Y', strtotime($end_date));
        
        $d1 = new DateTime($start_date); $d2 = new DateTime($end_date);
        $total_period_days = $d1->diff($d2)->days + 1;

        // 1. STATS
        $stats = ['total_emp' => 0, 'attendance_rate' => 0, 'total_late' => 0, 'payroll_est' => 0];
        try {
            $this->db->query("SELECT COUNT(*) as t FROM employees WHERE is_active=1");
            $stats['total_emp'] = $this->db->single()['t']??0;
            $this->db->query("SELECT COUNT(*) as t, SUM(CASE WHEN status IN ('present','late','early_out') THEN 1 ELSE 0 END) as p FROM attendance_records WHERE attendance_date BETWEEN :s AND :e");
            $this->db->bind(':s',$start_date); $this->db->bind(':e',$end_date);
            $r=$this->db->single();
            if($r['t']>0) $stats['attendance_rate'] = round(($r['p']/$r['t'])*100, 1);
            $this->db->query("SELECT COUNT(*) as t FROM attendance_records WHERE attendance_date BETWEEN :s AND :e AND is_late=1");
            $this->db->bind(':s',$start_date); $this->db->bind(':e',$end_date);
            $stats['total_late'] = $this->db->single()['t']??0;
            $this->db->query("SELECT SUM(salary) as t FROM employees WHERE is_active=1");
            $stats['payroll_est'] = $this->db->single()['t']??0;
        } catch(Exception $e){}

        // 2. CHARTS
        $chart_attendance = ['labels'=>[], 'present'=>[], 'late'=>[]];
        try{
            $this->db->query("SELECT attendance_date, COUNT(CASE WHEN status IN ('present','late','early_out') THEN 1 END) as p, COUNT(CASE WHEN is_late=1 THEN 1 END) as l FROM attendance_records WHERE attendance_date BETWEEN :s AND :e GROUP BY attendance_date ORDER BY attendance_date ASC");
            $this->db->bind(':s',$start_date); $this->db->bind(':e',$end_date);
            $logs=$this->db->resultSet();
            foreach($logs as $l){
                $chart_attendance['labels'][] = date('d/m', strtotime($l['attendance_date']));
                $chart_attendance['present'][] = $l['p'];
                $chart_attendance['late'][] = $l['l'];
            }
        }catch(Exception $e){}

        $chart_tasks = [0,0,0];
        try{
            $this->db->query("SELECT SUM(CASE WHEN status='completed' THEN 1 ELSE 0 END) as c, SUM(CASE WHEN status='in_progress' THEN 1 ELSE 0 END) as i, SUM(CASE WHEN status='pending' THEN 1 ELSE 0 END) as p FROM tasks WHERE due_date BETWEEN :s AND :e");
            $this->db->bind(':s',$start_date); $this->db->bind(':e',$end_date);
            $r=$this->db->single();
            if($r) $chart_tasks = [$r['c'], $r['i'], $r['p']];
        }catch(Exception $e){}

        $chart_age = ['labels'=>['<25','25-34','35-44','>45'], 'data'=>[0,0,0,0]];
        try{
            $this->db->query("SELECT CASE WHEN TIMESTAMPDIFF(YEAR,date_of_birth,CURDATE())<25 THEN 0 WHEN TIMESTAMPDIFF(YEAR,date_of_birth,CURDATE()) BETWEEN 25 AND 34 THEN 1 WHEN TIMESTAMPDIFF(YEAR,date_of_birth,CURDATE()) BETWEEN 35 AND 44 THEN 2 ELSE 3 END as g, COUNT(*) as t FROM employees WHERE is_active=1 AND date_of_birth IS NOT NULL GROUP BY g");
            $ages=$this->db->resultSet();
            foreach($ages as $a) $chart_age['data'][$a['g']] = $a['t'];
        }catch(Exception $e){}

        // 3. TOP 5
        $top_employees = [];
        try{
            $this->db->query("SELECT e.first_name, e.last_name, e.position, e.profile_photo_path, d.department_name, COALESCE(AVG(t.kpi_score_final),0) as avg_task_score, (SELECT COUNT(*) FROM attendance_records ar WHERE ar.employee_id=e.id AND ar.attendance_date BETWEEN :s1 AND :e1 AND ar.status IN ('present','late','early_out')) as present_days FROM employees e LEFT JOIN departments d ON e.department_id=d.id LEFT JOIN tasks t ON t.assigned_to=e.id AND t.status='completed' AND t.completed_at BETWEEN :s2 AND :e2 WHERE e.is_active=1 GROUP BY e.id");
            $this->db->bind(':s1',$start_date); $this->db->bind(':e1',$end_date);
            $this->db->bind(':s2',$start_date); $this->db->bind(':e2',$end_date);
            $rows=$this->db->resultSet();
            foreach($rows as $emp){
                $att_pct = ($emp['present_days']/max(1,$total_period_days))*100;
                $score = (floatval($emp['avg_task_score'])*0.6) + ($att_pct*0.4);
                $emp['att_pct']=round($att_pct,1);
                $emp['avg_task_score']=round($emp['avg_task_score'],1);
                $emp['kpi_score']=round($score,1);
                $top_employees[]=$emp;
            }
            usort($top_employees, function($a,$b){return $b['kpi_score']<=>$a['kpi_score'];});
            $top_employees = array_slice($top_employees,0,5);
        }catch(Exception $e){}

        // 4. BIRTHDAYS & APPROVALS (Index Only)
        $birthdays = []; $approvals = ['tasks'=>0,'requests'=>0,'attendance'=>0];
        try {
            $this->db->query("SELECT full_name, profile_photo_path, date_of_birth, DAY(date_of_birth) as tgl FROM employees WHERE is_active=1 AND MONTH(date_of_birth)=:m ORDER BY tgl ASC");
            $this->db->bind(':m',$selected_month);
            $birthdays=$this->db->resultSet();

            $this->db->query("SELECT COUNT(*) as t FROM tasks WHERE approval_status='pending'");
            $approvals['tasks']=$this->db->single()['t'];
            $this->db->query("SELECT ((SELECT COUNT(*) FROM leave_requests WHERE status='pending')+(SELECT COUNT(*) FROM overtime_requests WHERE status='pending')) as t");
            $approvals['requests']=$this->db->single()['t'];
            $this->db->query("SELECT COUNT(*) as t FROM attendance_issues WHERE status='pending'");
            $approvals['attendance']=$this->db->single()['t'];
        }catch(Exception $e){}

        $data['selected_month'] = $selected_month;
        $data['selected_year'] = $selected_year;
        $data['period_text'] = $period_text;
        $data['total_days'] = $total_period_days;
        $data['stats'] = $stats;
        $data['chart_attendance'] = $chart_attendance;
        $data['chart_tasks'] = $chart_tasks;
        $data['chart_age'] = $chart_age;
        $data['top_employees'] = $top_employees;
        $data['birthdays'] = $birthdays;
        $data['approvals'] = $approvals;
        
        $data['content_view'] = 'admin/dashboard/index';
        $this->view('admin/layouts/admin-layout', $data);
    }

    // --- PRINT PREVIEW (FIXED) ---
    public function print_report() {
        $selected_month = $_GET['month'] ?? date('m');
        $selected_year  = $_GET['year'] ?? date('Y');
        $start_date = date('Y-m-d', strtotime("$selected_year-$selected_month-25 -1 month"));
        $end_date   = "$selected_year-$selected_month-24";
        $period_text = date('d M Y', strtotime($start_date)) . " - " . date('d M Y', strtotime($end_date));
        
        // Ambil Data Perusahaan
        try {
            $this->db->query("SELECT * FROM companies LIMIT 1");
            $company = $this->db->single();
        } catch (Exception $e) { $company = []; }

        // --- 1. RE-QUERY STATS ---
        $stats = ['total_emp'=>0, 'attendance_rate'=>0, 'total_late'=>0, 'payroll_est'=>0];
        try {
            $this->db->query("SELECT COUNT(*) as total FROM employees WHERE is_active = 1");
            $stats['total_emp'] = $this->db->single()['total'] ?? 0;
            $this->db->query("SELECT COUNT(*) as t, SUM(CASE WHEN status IN ('present', 'late', 'early_out') THEN 1 ELSE 0 END) as p FROM attendance_records WHERE attendance_date BETWEEN :s AND :e");
            $this->db->bind(':s',$start_date); $this->db->bind(':e',$end_date);
            $r = $this->db->single();
            if($r['t']>0) $stats['attendance_rate'] = round(($r['p']/$r['t'])*100, 1);
            $this->db->query("SELECT COUNT(*) as t FROM attendance_records WHERE attendance_date BETWEEN :s AND :e AND is_late=1");
            $this->db->bind(':s',$start_date); $this->db->bind(':e',$end_date);
            $stats['total_late'] = $this->db->single()['t']??0;
            $this->db->query("SELECT SUM(salary) as t FROM employees WHERE is_active = 1");
            $stats['payroll_est'] = $this->db->single()['t']??0;
        } catch (Exception $e) {}

        // --- 2. RE-QUERY CHARTS ---
        // A. Trend Kehadiran
        $chart_attendance = ['labels'=>[], 'present'=>[], 'late'=>[]];
        try{
            $this->db->query("SELECT attendance_date, COUNT(CASE WHEN status IN ('present','late','early_out') THEN 1 END) as p, COUNT(CASE WHEN is_late=1 THEN 1 END) as l FROM attendance_records WHERE attendance_date BETWEEN :s AND :e GROUP BY attendance_date ORDER BY attendance_date ASC");
            $this->db->bind(':s',$start_date); $this->db->bind(':e',$end_date);
            $logs=$this->db->resultSet();
            foreach($logs as $l){
                $chart_attendance['labels'][] = date('d', strtotime($l['attendance_date'])); 
                $chart_attendance['present'][] = $l['p'];
                $chart_attendance['late'][] = $l['l'];
            }
        }catch(Exception $e){}

        // B. Task Stats
        $chart_tasks = [0,0,0];
        try{
            $this->db->query("SELECT SUM(CASE WHEN status='completed' THEN 1 ELSE 0 END) as c, SUM(CASE WHEN status='in_progress' THEN 1 ELSE 0 END) as i, SUM(CASE WHEN status='pending' THEN 1 ELSE 0 END) as p FROM tasks WHERE due_date BETWEEN :s AND :e");
            $this->db->bind(':s',$start_date); $this->db->bind(':e',$end_date);
            $r=$this->db->single();
            if($r) $chart_tasks = [$r['c'], $r['i'], $r['p']];
        }catch(Exception $e){}

        // C. Age Distribution
        $chart_age = ['labels'=>['<25','25-34','35-44','>45'], 'data'=>[0,0,0,0]];
        try{
            $this->db->query("SELECT CASE WHEN TIMESTAMPDIFF(YEAR,date_of_birth,CURDATE())<25 THEN 0 WHEN TIMESTAMPDIFF(YEAR,date_of_birth,CURDATE()) BETWEEN 25 AND 34 THEN 1 WHEN TIMESTAMPDIFF(YEAR,date_of_birth,CURDATE()) BETWEEN 35 AND 44 THEN 2 ELSE 3 END as g, COUNT(*) as t FROM employees WHERE is_active=1 AND date_of_birth IS NOT NULL GROUP BY g");
            $ages=$this->db->resultSet();
            foreach($ages as $a) $chart_age['data'][$a['g']] = $a['t'];
        }catch(Exception $e){}

        // --- 3. RE-QUERY TOP 5 (COPY LOGIC) ---
        $top_employees = [];
        $d1 = new DateTime($start_date); $d2 = new DateTime($end_date);
        $total_period_days = $d1->diff($d2)->days + 1;
        try {
            $this->db->query("SELECT e.first_name, e.last_name, e.position, d.department_name,
                    COALESCE(AVG(t.kpi_score_final), 0) as avg_task_score,
                    (SELECT COUNT(*) FROM attendance_records ar WHERE ar.employee_id = e.id AND ar.attendance_date BETWEEN :s1 AND :e1 AND ar.status IN ('present','late','early_out')) as present_days
                FROM employees e
                LEFT JOIN departments d ON e.department_id = d.id
                LEFT JOIN tasks t ON t.assigned_to = e.id AND t.status='completed' AND t.completed_at BETWEEN :s2 AND :e2
                WHERE e.is_active = 1 GROUP BY e.id");
            $this->db->bind(':s1',$start_date); $this->db->bind(':e1',$end_date);
            $this->db->bind(':s2',$start_date); $this->db->bind(':e2',$end_date);
            $rows = $this->db->resultSet();
            foreach($rows as $emp) {
                $att_pct = ($emp['present_days'] / max(1, $total_period_days)) * 100;
                $emp['att_pct'] = round($att_pct, 1);
                $emp['avg_task_score'] = round($emp['avg_task_score'], 1);
                $emp['kpi_score'] = round((floatval($emp['avg_task_score']) * 0.6) + ($att_pct * 0.4), 1);
                $top_employees[] = $emp;
            }
            usort($top_employees, function($a, $b) { return $b['kpi_score'] <=> $a['kpi_score']; });
            $top_employees = array_slice($top_employees, 0, 5);
        } catch(Exception $e){}
        
        $data = [
            'period_text' => $period_text,
            'company' => $company,
            'stats' => $stats,
            'chart_attendance' => $chart_attendance,
            'chart_tasks' => $chart_tasks,
            'chart_age' => $chart_age,
            'top_employees' => $top_employees,
            'generated_at' => date('d M Y H:i'),
            // --- FIX ERROR DISINI ---
            'generated_by' => $_SESSION['full_name'] ?? 'Administrator' 
        ];

        $this->view('admin/dashboard/print', $data);
    }
}