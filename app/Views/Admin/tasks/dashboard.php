<?php
/**
 * Tasks Dashboard - Analytics & Overview
 */

// Instantiate database if not exists
if (!isset($db)) {
    $db = new Database();
}

// Date range filter (default: this month)
$start_date = isset($_GET['start_date']) ? $_GET['start_date'] : date('Y-m-01');
$end_date = isset($_GET['end_date']) ? $_GET['end_date'] : date('Y-m-t');

// ==================================
// 1. OVERVIEW STATISTICS
// ==================================

// Total tasks
$total_tasks = $db->fetchOne("
    SELECT COUNT(*) as total 
    FROM tasks 
    WHERE created_at BETWEEN ? AND ?
", [$start_date . ' 00:00:00', $end_date . ' 23:59:59']);

// Completed tasks
$completed_tasks = $db->fetchOne("
    SELECT COUNT(*) as total 
    FROM tasks 
    WHERE status = 'completed' 
    AND created_at BETWEEN ? AND ?
", [$start_date . ' 00:00:00', $end_date . ' 23:59:59']);

// In Progress tasks
$in_progress_tasks = $db->fetchOne("
    SELECT COUNT(*) as total 
    FROM tasks 
    WHERE status = 'in_progress'
    AND created_at BETWEEN ? AND ?
", [$start_date . ' 00:00:00', $end_date . ' 23:59:59']);

// Overdue tasks
$overdue_tasks = $db->fetchOne("
    SELECT COUNT(*) as total 
    FROM tasks 
    WHERE due_date < CURDATE() 
    AND status NOT IN ('completed', 'cancelled')
");

// Pending approval
$pending_approval = $db->fetchOne("
    SELECT COUNT(*) as total 
    FROM tasks 
    WHERE approval_status = 'submitted'
");

// Completion rate
$completion_rate = $total_tasks['total'] > 0 
    ? round(($completed_tasks['total'] / $total_tasks['total']) * 100, 1) 
    : 0;

// Total hours logged
$total_hours = $db->fetchOne("
    SELECT SUM(actual_hours) as total 
    FROM tasks 
    WHERE created_at BETWEEN ? AND ?
", [$start_date . ' 00:00:00', $end_date . ' 23:59:59']);

// ==================================
// 2. CHART DATA - Status Distribution
// ==================================

$status_distribution = $db->fetchAll("
    SELECT 
        status,
        COUNT(*) as count
    FROM tasks
    WHERE created_at BETWEEN ? AND ?
    GROUP BY status
    ORDER BY count DESC
", [$start_date . ' 00:00:00', $end_date . ' 23:59:59']);

// ==================================
// 3. CHART DATA - Priority Breakdown
// ==================================

$priority_breakdown = $db->fetchAll("
    SELECT 
        priority,
        COUNT(*) as count
    FROM tasks
    WHERE created_at BETWEEN ? AND ?
    GROUP BY priority
    ORDER BY 
        FIELD(priority, 'urgent', 'high', 'medium', 'low')
", [$start_date . ' 00:00:00', $end_date . ' 23:59:59']);

// ==================================
// 4. CHART DATA - Tasks by Category
// ==================================

$tasks_by_category = $db->fetchAll("
    SELECT 
        tc.category_name,
        tc.color,
        COUNT(t.id) as count
    FROM task_categories tc
    LEFT JOIN tasks t ON tc.id = t.category_id 
        AND t.created_at BETWEEN ? AND ?
    GROUP BY tc.id, tc.category_name, tc.color
    ORDER BY count DESC
    LIMIT 10
", [$start_date . ' 00:00:00', $end_date . ' 23:59:59']);

// ==================================
// 5. TOP PERFORMERS
// ==================================

$top_performers = $db->fetchAll("
    SELECT 
        CONCAT(e.first_name, ' ', e.last_name) as name,
        COUNT(t.id) as total_tasks,
        SUM(CASE WHEN t.status = 'completed' THEN 1 ELSE 0 END) as completed_tasks,
        ROUND(AVG(t.completion_percentage), 1) as avg_progress,
        SUM(t.actual_hours) as total_hours
    FROM users u
    JOIN employees e ON u.employee_id = e.id
    LEFT JOIN tasks t ON u.id = t.assigned_to 
        AND t.created_at BETWEEN ? AND ?
    WHERE u.role IN ('employee', 'supervisor')
    GROUP BY u.id, e.first_name, e.last_name
    HAVING total_tasks > 0
    ORDER BY completed_tasks DESC, avg_progress DESC
    LIMIT 5
", [$start_date . ' 00:00:00', $end_date . ' 23:59:59']);

// ==================================
// 6. DEPARTMENT STATISTICS
// ==================================

$department_stats = $db->fetchAll("
    SELECT 
        d.department_name,
        COUNT(t.id) as total_tasks,
        SUM(CASE WHEN t.status = 'completed' THEN 1 ELSE 0 END) as completed_tasks,
        ROUND(AVG(t.completion_percentage), 1) as avg_progress
    FROM departments d
    LEFT JOIN tasks t ON d.id = t.department_id 
        AND t.created_at BETWEEN ? AND ?
    GROUP BY d.id, d.department_name
    HAVING total_tasks > 0
    ORDER BY total_tasks DESC
", [$start_date . ' 00:00:00', $end_date . ' 23:59:59']);

// ==================================
// 7. RECENT ACTIVITIES
// ==================================

$recent_activities = $db->fetchAll("
    SELECT 
        th.*,
        CONCAT(e.first_name, ' ', e.last_name) as user_name,
        t.title as task_title,
        t.task_code
    FROM task_history th
    JOIN users u ON th.user_id = u.id
    JOIN employees e ON u.employee_id = e.id
    JOIN tasks t ON th.task_id = t.id
    ORDER BY th.created_at DESC
    LIMIT 10
");

// ==================================
// 8. UPCOMING DEADLINES
// ==================================

$upcoming_deadlines = $db->fetchAll("
    SELECT 
        t.*,
        tc.category_name,
        tc.color as category_color,
        CONCAT(e.first_name, ' ', e.last_name) as assignee_name,
        DATEDIFF(t.due_date, CURDATE()) as days_remaining
    FROM tasks t
    LEFT JOIN task_categories tc ON t.category_id = tc.id
    LEFT JOIN users u ON t.assigned_to = u.id
    LEFT JOIN employees e ON u.employee_id = e.id
    WHERE t.status NOT IN ('completed', 'cancelled')
    AND t.due_date BETWEEN CURDATE() AND DATE_ADD(CURDATE(), INTERVAL 7 DAY)
    ORDER BY t.due_date ASC
    LIMIT 10
");

// ==================================
// 9. TASKS TIMELINE (Last 7 Days)
// ==================================

$timeline_data = $db->fetchAll("
    SELECT 
        DATE(created_at) as date,
        COUNT(*) as tasks_created,
        SUM(CASE WHEN status = 'completed' THEN 1 ELSE 0 END) as tasks_completed
    FROM tasks
    WHERE created_at >= DATE_SUB(CURDATE(), INTERVAL 7 DAY)
    GROUP BY DATE(created_at)
    ORDER BY date ASC
");

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tasks Dashboard - Analytics</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
</head>
<body>

<div class="max-w-[1800px] mx-auto p-6">

<!-- Header -->
<div class="mb-8">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold text-gray-900 mb-2">
                <i class="fas fa-chart-line text-blue-600"></i>
                Tasks Dashboard
            </h1>
            <p class="text-gray-600">Real-time analytics and performance metrics</p>
        </div>
        
        <div class="flex items-center gap-3">
            <!-- View Toggle -->
            <div class="flex items-center gap-2 bg-gray-100 rounded-lg p-1">
                <a href="/hrm-pwa/public/admin/tasks" 
                   class="px-4 py-2 text-sm font-medium text-gray-600 hover:text-gray-900 rounded-lg transition-colors">
                    List
                </a>
                <a href="/hrm-pwa/public/admin/tasks/kanban" 
                   class="px-4 py-2 text-sm font-medium text-gray-600 hover:text-gray-900 rounded-lg transition-colors">
                    Kanban
                </a>
                <a href="/hrm-pwa/public/admin/tasks/dashboard" 
                   class="px-4 py-2 text-sm font-medium bg-white text-blue-600 rounded-lg shadow-sm">
                    Analytics
                </a>
            </div>
            
            <!-- Date Range Filter -->
            <form method="GET" action="" class="flex items-center gap-2">
                <input type="date" 
                       name="start_date" 
                       value="<?= $start_date ?>"
                       class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                <span class="text-gray-500">to</span>
                <input type="date" 
                       name="end_date" 
                       value="<?= $end_date ?>"
                       class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                <button type="submit" 
                        class="px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition-colors">
                    <i class="fas fa-filter mr-2"></i>Filter
                </button>
            </form>
        </div>
    </div>
</div>


    <!-- Overview Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 xl:grid-cols-6 gap-4 mb-8">
        
        <!-- Total Tasks -->
        <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl shadow-lg p-6 text-white">
            <div class="flex items-center justify-between mb-2">
                <div class="w-12 h-12 bg-white bg-opacity-20 rounded-lg flex items-center justify-center">
                    <i class="fas fa-tasks text-2xl"></i>
                </div>
                <span class="text-xs font-semibold bg-white bg-opacity-20 px-2 py-1 rounded-full">Total</span>
            </div>
            <p class="text-3xl font-bold mb-1"><?= number_format($total_tasks['total']) ?></p>
            <p class="text-sm opacity-90">Total Tasks</p>
        </div>
        
        <!-- Completed -->
        <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-xl shadow-lg p-6 text-white">
            <div class="flex items-center justify-between mb-2">
                <div class="w-12 h-12 bg-white bg-opacity-20 rounded-lg flex items-center justify-center">
                    <i class="fas fa-check-circle text-2xl"></i>
                </div>
                <span class="text-xs font-semibold bg-white bg-opacity-20 px-2 py-1 rounded-full"><?= $completion_rate ?>%</span>
            </div>
            <p class="text-3xl font-bold mb-1"><?= number_format($completed_tasks['total']) ?></p>
            <p class="text-sm opacity-90">Completed</p>
        </div>
        
        <!-- In Progress -->
        <div class="bg-gradient-to-br from-orange-500 to-orange-600 rounded-xl shadow-lg p-6 text-white">
            <div class="flex items-center justify-between mb-2">
                <div class="w-12 h-12 bg-white bg-opacity-20 rounded-lg flex items-center justify-center">
                    <i class="fas fa-spinner text-2xl"></i>
                </div>
            </div>
            <p class="text-3xl font-bold mb-1"><?= number_format($in_progress_tasks['total']) ?></p>
            <p class="text-sm opacity-90">In Progress</p>
        </div>
        
        <!-- Overdue -->
        <div class="bg-gradient-to-br from-red-500 to-red-600 rounded-xl shadow-lg p-6 text-white">
            <div class="flex items-center justify-between mb-2">
                <div class="w-12 h-12 bg-white bg-opacity-20 rounded-lg flex items-center justify-center">
                    <i class="fas fa-exclamation-triangle text-2xl"></i>
                </div>
            </div>
            <p class="text-3xl font-bold mb-1"><?= number_format($overdue_tasks['total']) ?></p>
            <p class="text-sm opacity-90">Overdue</p>
        </div>
        
        <!-- Pending Approval -->
        <div class="bg-gradient-to-br from-purple-500 to-purple-600 rounded-xl shadow-lg p-6 text-white">
            <div class="flex items-center justify-between mb-2">
                <div class="w-12 h-12 bg-white bg-opacity-20 rounded-lg flex items-center justify-center">
                    <i class="fas fa-clock text-2xl"></i>
                </div>
            </div>
            <p class="text-3xl font-bold mb-1"><?= number_format($pending_approval['total']) ?></p>
            <p class="text-sm opacity-90">Pending Approval</p>
        </div>
        
        <!-- Total Hours -->
        <div class="bg-gradient-to-br from-indigo-500 to-indigo-600 rounded-xl shadow-lg p-6 text-white">
            <div class="flex items-center justify-between mb-2">
                <div class="w-12 h-12 bg-white bg-opacity-20 rounded-lg flex items-center justify-center">
                    <i class="fas fa-clock text-2xl"></i>
                </div>
            </div>
            <p class="text-3xl font-bold mb-1"><?= number_format($total_hours['total'] ?? 0, 1) ?>h</p>
            <p class="text-sm opacity-90">Hours Logged</p>
        </div>
        
    </div>

<!-- Charts Row -->
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
    
    <!-- Status Distribution Chart -->
    <div class="bg-white rounded-xl shadow-lg border border-gray-200 p-6">
        <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2">
            <i class="fas fa-chart-pie text-blue-600"></i>
            Status Distribution
        </h3>
        <div style="position: relative; height: 300px;">
            <canvas id="statusChart"></canvas>
        </div>
    </div>
    
    <!-- Priority Breakdown Chart -->
    <div class="bg-white rounded-xl shadow-lg border border-gray-200 p-6">
        <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2">
            <i class="fas fa-chart-bar text-orange-600"></i>
            Priority Breakdown
        </h3>
        <div style="position: relative; height: 300px;">
            <canvas id="priorityChart"></canvas>
        </div>
    </div>
    
    <!-- Category Distribution Chart -->
    <div class="bg-white rounded-xl shadow-lg border border-gray-200 p-6">
        <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2">
            <i class="fas fa-chart-donut text-purple-600"></i>
            Tasks by Category
        </h3>
        <div style="position: relative; height: 300px;">
            <canvas id="categoryChart"></canvas>
        </div>
    </div>
    
</div>

<!-- Timeline Chart (Full Width) -->
<div class="bg-white rounded-xl shadow-lg border border-gray-200 p-6 mb-8">
    <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2">
        <i class="fas fa-chart-line text-green-600"></i>
        Tasks Timeline (Last 7 Days)
    </h3>
    <div style="position: relative; height: 300px;">
        <canvas id="timelineChart"></canvas>
    </div>
</div>


<!-- Timeline Chart (Full Width) -->
<div class="bg-white rounded-xl shadow-lg border border-gray-200 p-6 mb-8">
    <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2">
        <i class="fas fa-chart-line text-green-600"></i>
        Tasks Timeline (Last 7 Days)
    </h3>
    <div style="position: relative; height: 300px;">
        <canvas id="timelineChart"></canvas>
    </div>
</div>

    <!-- Bottom Section: 3 Columns -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        <!-- Top Performers -->
        <div class="bg-white rounded-xl shadow-lg border border-gray-200 p-6">
            <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2">
                <i class="fas fa-trophy text-yellow-500"></i>
                Top Performers
            </h3>
            
            <?php if (empty($top_performers)): ?>
            <p class="text-center text-gray-500 py-8 text-sm">No data available</p>
            <?php else: ?>
            <div class="space-y-3">
                <?php foreach ($top_performers as $index => $performer): ?>
                <?php
                $medal_colors = ['text-yellow-500', 'text-gray-400', 'text-orange-600'];
                $medal_color = $medal_colors[$index] ?? 'text-blue-500';
                ?>
                <div class="flex items-center gap-3 p-3 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors">
                    <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-blue-600 rounded-full flex items-center justify-center text-white font-bold text-sm flex-shrink-0">
                        <?= $index + 1 ?>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="font-semibold text-gray-900 text-sm truncate"><?= htmlspecialchars($performer['name']) ?></p>
                        <div class="flex items-center gap-3 text-xs text-gray-500 mt-1">
                            <span><i class="fas fa-tasks"></i> <?= $performer['total_tasks'] ?> tasks</span>
                            <span><i class="fas fa-check-circle text-green-600"></i> <?= $performer['completed_tasks'] ?> done</span>
                        </div>
                    </div>
                    <div class="text-right">
                        <p class="text-lg font-bold text-blue-600"><?= $performer['avg_progress'] ?>%</p>
                        <p class="text-xs text-gray-500"><?= number_format($performer['total_hours'], 1) ?>h</p>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
            <?php endif; ?>
        </div>
        
        <!-- Recent Activities -->
        <div class="bg-white rounded-xl shadow-lg border border-gray-200 p-6">
            <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2">
                <i class="fas fa-history text-indigo-600"></i>
                Recent Activities
            </h3>
            
            <?php if (empty($recent_activities)): ?>
            <p class="text-center text-gray-500 py-8 text-sm">No activities yet</p>
            <?php else: ?>
            <div class="space-y-2 max-h-[400px] overflow-y-auto">
                <?php foreach ($recent_activities as $activity): ?>
                <div class="flex gap-3 p-2 hover:bg-gray-50 rounded-lg transition-colors">
                    <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center flex-shrink-0">
                        <i class="fas fa-circle text-blue-600 text-xs"></i>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm text-gray-900 leading-tight">
                            <span class="font-semibold"><?= htmlspecialchars($activity['user_name']) ?></span>
                            <span class="text-gray-600"><?= ucfirst(str_replace('_', ' ', $activity['action'] ?? 'updated')) ?></span>
                        </p>
                        <p class="text-xs text-gray-500 mt-0.5 truncate">
                            <?= htmlspecialchars($activity['task_code']) ?> - <?= htmlspecialchars($activity['task_title']) ?>
                        </p>
                        <p class="text-xs text-gray-400 mt-0.5">
                            <?= date('d M, H:i', strtotime($activity['created_at'])) ?>
                        </p>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
            <?php endif; ?>
        </div>
        
        <!-- Upcoming Deadlines -->
        <div class="bg-white rounded-xl shadow-lg border border-gray-200 p-6">
            <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2">
                <i class="fas fa-calendar-alt text-red-600"></i>
                Upcoming Deadlines
            </h3>
            
            <?php if (empty($upcoming_deadlines)): ?>
            <p class="text-center text-gray-500 py-8 text-sm">No upcoming deadlines</p>
            <?php else: ?>
            <div class="space-y-2 max-h-[400px] overflow-y-auto">
                <?php foreach ($upcoming_deadlines as $task): ?>
                <?php
                $urgency_class = $task['days_remaining'] <= 1 ? 'bg-red-50 border-red-200' : 
                                ($task['days_remaining'] <= 3 ? 'bg-orange-50 border-orange-200' : 'bg-yellow-50 border-yellow-200');
                ?>
                <a href="/hrm-pwa/public/admin/tasks/view?id=<?= $task['id'] ?>" 
                   class="block p-3 border rounded-lg <?= $urgency_class ?> hover:shadow-md transition-all">
                    <div class="flex items-start justify-between gap-2">
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-semibold text-gray-900 truncate"><?= htmlspecialchars($task['title']) ?></p>
                            <p class="text-xs text-gray-600 mt-1"><?= htmlspecialchars($task['assignee_name'] ?? 'Unassigned') ?></p>
                        </div>
                        <div class="text-right flex-shrink-0">
                            <p class="text-xs font-bold <?= $task['days_remaining'] <= 1 ? 'text-red-600' : 'text-orange-600' ?>">
                                <?= $task['days_remaining'] ?> days
                            </p>
                            <p class="text-xs text-gray-500"><?= date('d M', strtotime($task['due_date'])) ?></p>
                        </div>
                    </div>
                </a>
                <?php endforeach; ?>
            </div>
            <?php endif; ?>
        </div>
        
    </div>

</div>

<script>
// Chart.js Global Config
Chart.defaults.responsive = true;
Chart.defaults.maintainAspectRatio = false;

// Status Distribution Chart
const statusCtx = document.getElementById('statusChart').getContext('2d');
new Chart(statusCtx, {
    type: 'doughnut',
    data: {
        labels: [<?php echo "'" . implode("','", array_map(function($item) {
            return ucfirst(str_replace('_', ' ', $item['status']));
        }, $status_distribution)) . "'"; ?>],
        datasets: [{
            data: [<?php echo implode(',', array_column($status_distribution, 'count')); ?>],
            backgroundColor: [
                '#3B82F6', // blue - pending
                '#F59E0B', // orange - in_progress
                '#8B5CF6', // purple - review
                '#10B981', // green - completed
                '#EF4444'  // red - cancelled
            ],
            borderWidth: 2,
            borderColor: '#fff'
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: {
                position: 'bottom',
                labels: {
                    padding: 15,
                    font: { size: 11 }
                }
            }
        }
    }
});

// Priority Breakdown Chart
const priorityCtx = document.getElementById('priorityChart').getContext('2d');
new Chart(priorityCtx, {
    type: 'bar',
    data: {
        labels: [<?php echo "'" . implode("','", array_map('ucfirst', array_column($priority_breakdown, 'priority'))) . "'"; ?>],
        datasets: [{
            label: 'Tasks',
            data: [<?php echo implode(',', array_column($priority_breakdown, 'count')); ?>],
            backgroundColor: ['#EF4444', '#F59E0B', '#FBBF24', '#10B981'],
            borderWidth: 0,
            borderRadius: 8
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: { display: false }
        },
        scales: {
            y: {
                beginAtZero: true,
                ticks: { stepSize: 1 }
            }
        }
    }
});

// Category Chart
const categoryCtx = document.getElementById('categoryChart').getContext('2d');
new Chart(categoryCtx, {
    type: 'pie',
    data: {
        labels: [<?php echo "'" . implode("','", array_column($tasks_by_category, 'category_name')) . "'"; ?>],
        datasets: [{
            data: [<?php echo implode(',', array_column($tasks_by_category, 'count')); ?>],
            backgroundColor: [<?php echo "'" . implode("','", array_column($tasks_by_category, 'color')) . "'"; ?>],
            borderWidth: 2,
            borderColor: '#fff'
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: {
                position: 'bottom',
                labels: {
                    padding: 15,
                    font: { size: 11 }
                }
            }
        }
    }
});

// Timeline Chart
const timelineCtx = document.getElementById('timelineChart').getContext('2d');
new Chart(timelineCtx, {
    type: 'line',
    data: {
        labels: [<?php echo "'" . implode("','", array_map(function($item) {
            return date('d M', strtotime($item['date']));
        }, $timeline_data)) . "'"; ?>],
        datasets: [
            {
                label: 'Created',
                data: [<?php echo implode(',', array_column($timeline_data, 'tasks_created')); ?>],
                borderColor: '#3B82F6',
                backgroundColor: 'rgba(59, 130, 246, 0.1)',
                tension: 0.4,
                fill: true,
                borderWidth: 3
            },
            {
                label: 'Completed',
                data: [<?php echo implode(',', array_column($timeline_data, 'tasks_completed')); ?>],
                borderColor: '#10B981',
                backgroundColor: 'rgba(16, 185, 129, 0.1)',
                tension: 0.4,
                fill: true,
                borderWidth: 3
            }
        ]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: {
                position: 'top',
                labels: {
                    usePointStyle: true,
                    padding: 20
                }
            }
        },
        scales: {
            y: {
                beginAtZero: true,
                ticks: { stepSize: 1 }
            }
        }
    }
});

console.log('✅ Dashboard charts initialized');
</script>


</body>
</html>
