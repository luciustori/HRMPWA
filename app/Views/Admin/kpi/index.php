<div class="max-w-7xl mx-auto font-sans text-slate-600">
    
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
        <div>
            <h1 class="text-2xl font-black text-slate-800 tracking-tight">Executive KPI Dashboard</h1>
            <p class="text-slate-500 text-sm">Overview performa dan kualitas kerja perusahaan.</p>
        </div>
        
        <form method="GET" action="<?= BASEURL ?>/admin/kpi" class="flex items-center gap-2 bg-white p-1 rounded-lg border border-slate-200 shadow-sm">
            <select name="month" onchange="this.form.submit()" class="bg-transparent text-sm font-medium text-slate-700 border-none focus:ring-0 cursor-pointer py-1.5 pl-3 pr-8 rounded-md hover:bg-slate-50">
                <option value="all" <?= $data['filter_month'] == 'all' ? 'selected' : '' ?>>Semua Bulan</option>
                <?php 
                $months = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
                foreach($months as $index => $m): 
                    $val = $index + 1;
                ?>
                    <option value="<?= $val ?>" <?= $data['filter_month'] == $val ? 'selected' : '' ?>><?= $m ?></option>
                <?php endforeach; ?>
            </select>
            <div class="w-px h-4 bg-slate-200"></div>
            <select name="year" onchange="this.form.submit()" class="bg-transparent text-sm font-medium text-slate-700 border-none focus:ring-0 cursor-pointer py-1.5 pl-3 pr-8 rounded-md hover:bg-slate-50">
                <?php for($y = date('Y'); $y >= 2024; $y--): ?>
                    <option value="<?= $y ?>" <?= $data['filter_year'] == $y ? 'selected' : '' ?>><?= $y ?></option>
                <?php endfor; ?>
            </select>
        </form>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        
        <div class="bg-white rounded-2xl p-6 border border-slate-100 shadow-sm flex flex-col justify-between relative overflow-hidden">
            <div class="relative z-10">
                <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">Skor Perusahaan</span>
                <div class="flex items-baseline gap-2 mt-1">
                    <span class="text-4xl font-black text-slate-800"><?= $data['company_score'] ?></span>
                    <span class="text-sm font-bold text-<?= $data['theme'] ?>-600 bg-<?= $data['theme'] ?>-50 px-2 py-0.5 rounded">Grade <?= $data['grade'] ?></span>
                </div>
            </div>
            <div class="absolute right-0 top-0 w-24 h-24 bg-<?= $data['theme'] ?>-50 rounded-bl-full -mr-4 -mt-4 opacity-50"></div>
        </div>

        <div class="bg-white rounded-2xl p-6 border border-slate-100 shadow-sm flex flex-col justify-between">
            <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">Total Tugas Selesai</span>
            <div class="mt-1">
                <span class="text-4xl font-black text-slate-800"><?= number_format($data['global']['completed_tasks'] ?? 0) ?></span>
                <span class="text-xs text-slate-400 ml-1">dari <?= number_format($data['global']['total_tasks'] ?? 0) ?> tugas</span>
            </div>
        </div>

        <div class="bg-white rounded-2xl p-6 border border-slate-100 shadow-sm flex flex-col justify-between">
            <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">Completion Rate</span>
            <div class="mt-1 flex items-center gap-3">
                <?php 
                    $total = $data['global']['total_tasks'] ?? 0;
                    $done = $data['global']['completed_tasks'] ?? 0;
                    $rate = ($total > 0) ? round(($done / $total) * 100) : 0;
                ?>
                <span class="text-4xl font-black text-slate-800"><?= $rate ?>%</span>
                <div class="flex-1 h-2 bg-slate-100 rounded-full overflow-hidden">
                    <div class="h-full bg-indigo-500 rounded-full" style="width: <?= $rate ?>%"></div>
                </div>
            </div>
        </div>

        <div class="bg-indigo-600 rounded-2xl p-6 shadow-lg shadow-indigo-200 text-white flex flex-col justify-center text-center items-center">
            <h3 class="font-bold text-lg mb-1">Full Report</h3>
            <p class="text-indigo-100 text-xs mb-3">Lihat detail per karyawan</p>
            <a href="<?= BASEURL ?>/admin/kpi/report" class="bg-white text-indigo-600 px-4 py-2 rounded-lg text-sm font-bold hover:bg-indigo-50 transition w-full">
                Buka Laporan &rarr;
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
        <div class="bg-white p-6 rounded-2xl border border-slate-100 shadow-sm">
            <h3 class="font-bold text-slate-800 mb-4">Tren Performa Bulanan</h3>
            <div class="h-64">
                <canvas id="monthlyChart"></canvas>
            </div>
        </div>

        <div class="bg-white p-6 rounded-2xl border border-slate-100 shadow-sm">
            <h3 class="font-bold text-slate-800 mb-4">Top 5 Departemen Terbaik</h3>
            <div class="h-64">
                <canvas id="deptChart"></canvas>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
        <div class="p-6 border-b border-slate-50 flex justify-between items-center">
            <h3 class="font-bold text-slate-800">Top 5 Karyawan Terbaik</h3>
            <span class="text-xs font-medium text-slate-400 uppercase tracking-wider">Berdasarkan KPI Score</span>
        </div>
        
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead class="bg-slate-50 text-slate-500 font-bold uppercase text-xs">
                    <tr>
                        <th class="px-6 py-3">Peringkat</th>
                        <th class="px-6 py-3">Karyawan</th>
                        <th class="px-6 py-3">Departemen</th>
                        <th class="px-6 py-3 text-center">Tugas Selesai</th>
                        <th class="px-6 py-3 text-center">Skor Akhir</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    <?php if(empty($data['top_employees'])): ?>
                        <tr><td colspan="5" class="px-6 py-8 text-center text-slate-400 italic">Belum ada data untuk periode ini.</td></tr>
                    <?php else: ?>
                        <?php foreach($data['top_employees'] as $index => $emp): ?>
                        <tr class="hover:bg-slate-50 transition">
                            <td class="px-6 py-4">
                                <?php if($index == 0): ?>
                                    <span class="w-8 h-8 rounded-full bg-yellow-100 text-yellow-600 flex items-center justify-center font-bold">1</span>
                                <?php elseif($index == 1): ?>
                                    <span class="w-8 h-8 rounded-full bg-slate-200 text-slate-600 flex items-center justify-center font-bold">2</span>
                                <?php elseif($index == 2): ?>
                                    <span class="w-8 h-8 rounded-full bg-orange-100 text-orange-700 flex items-center justify-center font-bold">3</span>
                                <?php else: ?>
                                    <span class="w-8 h-8 flex items-center justify-center font-bold text-slate-400"><?= $index + 1 ?></span>
                                <?php endif; ?>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <?php if($emp['profile_photo_path']): ?>
                                        <img src="<?= BASEURL ?>/<?= $emp['profile_photo_path'] ?>" class="w-10 h-10 rounded-full object-cover border border-slate-200">
                                    <?php else: ?>
                                        <div class="w-10 h-10 rounded-full bg-indigo-100 text-indigo-600 flex items-center justify-center font-bold text-xs">
                                            <?= substr($emp['first_name'], 0, 1) ?><?= substr($emp['last_name'], 0, 1) ?>
                                        </div>
                                    <?php endif; ?>
                                    <div>
                                        <div class="font-bold text-slate-800"><?= $emp['first_name'] ?> <?= $emp['last_name'] ?></div>
                                        <div class="text-xs text-slate-400"><?= $emp['employee_number'] ?></div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-slate-600"><?= $emp['department_name'] ?? '-' ?></td>
                            <td class="px-6 py-4 text-center font-bold text-slate-700"><?= $emp['total_done'] ?></td>
                            <td class="px-6 py-4 text-center">
                                <span class="inline-block px-3 py-1 rounded-full text-xs font-bold bg-indigo-50 text-indigo-700">
                                    <?= number_format((float)($emp['quality'] ?? 0), 1) ?>
                                </span>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    Chart.defaults.font.family = "'Inter', sans-serif";
    Chart.defaults.color = '#64748b';

    // 1. MONTHLY CHART
    new Chart(document.getElementById('monthlyChart'), {
        type: 'line',
        data: {
            labels: ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agt', 'Sep', 'Okt', 'Nov', 'Des'],
            datasets: [{
                label: 'Rata-rata Skor KPI',
                data: <?= json_encode($data['chart_monthly']) ?>,
                borderColor: '#4f46e5',
                backgroundColor: 'rgba(79, 70, 229, 0.1)',
                borderWidth: 2,
                tension: 0.4,
                fill: true,
                pointRadius: 4,
                pointHoverRadius: 6
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { display: false } },
            scales: {
                y: { min: 0, max: 100, grid: { borderDash: [2, 4] } },
                x: { grid: { display: false } }
            }
        }
    });

    // 2. DEPT CHART
    new Chart(document.getElementById('deptChart'), {
        type: 'bar',
        data: {
            labels: <?= json_encode($data['chart_dept_labels']) ?>,
            datasets: [{
                label: 'Skor Rata-rata',
                data: <?= json_encode($data['chart_dept_scores']) ?>,
                backgroundColor: ['#3b82f6', '#10b981', '#f59e0b', '#8b5cf6', '#ec4899'],
                borderRadius: 6,
                barThickness: 30
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { display: false } },
            scales: {
                y: { min: 0, max: 100 },
                x: { grid: { display: false } }
            }
        }
    });
</script>