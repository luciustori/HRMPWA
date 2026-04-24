<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Executive Report - <?= $data['period_text'] ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        @media print {
            body { -webkit-print-color-adjust: exact; print-color-adjust: exact; background: white !important; }
            .no-print { display: none !important; }
            .sheet { box-shadow: none !important; border: none !important; margin: 0 !important; width: 100% !important; padding: 0 !important; }
            /* Paksa Warna Background Saat Print */
            .bg-indigo-600 { background-color: #4f46e5 !important; color: white !important; }
            .bg-gray-50 { background-color: #f9fafb !important; }
            .bg-green-50 { background-color: #f0fdf4 !important; }
            .bg-red-50 { background-color: #fef2f2 !important; }
            .bg-blue-50 { background-color: #eff6ff !important; }
        }
        body { font-family: 'Segoe UI', sans-serif; background: #525659; padding: 20px; }
        .sheet { background: white; width: 210mm; min-height: 297mm; margin: 0 auto; padding: 15mm; position: relative; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1); }
        .chart-container { position: relative; height: 200px; width: 100%; }
        .chart-container-sm { position: relative; height: 120px; width: 100%; display:flex; justify-content:center; }
    </style>
</head>
<body>

    <div class="no-print fixed top-4 right-4 flex gap-2 z-50">
        <button onclick="window.print()" class="bg-blue-600 text-white px-4 py-2 rounded font-bold shadow hover:bg-blue-700 transition">🖨️ Print PDF</button>
        <button onclick="window.close()" class="bg-gray-600 text-white px-4 py-2 rounded font-bold shadow hover:bg-gray-700 transition">Tutup</button>
    </div>

    <div class="sheet">
        
        <div class="flex justify-between items-end border-b-2 border-gray-800 pb-6 mb-8">
            <div class="flex items-center gap-4">
                <?php if(!empty($data['company']['logo_path'])): ?>
                    <img src="<?= BASEURL . '/' . $data['company']['logo_path'] ?>" class="h-16 w-auto object-contain">
                <?php else: ?>
                    <div class="h-14 w-14 bg-gray-900 text-white flex items-center justify-center font-bold text-2xl rounded">M</div>
                <?php endif; ?>
                
                <div>
                    <h1 class="text-2xl font-bold text-gray-900 uppercase tracking-tight"><?= $data['company']['company_name'] ?? 'NAMA PERUSAHAAN' ?></h1>
                    <p class="text-sm text-gray-500 leading-tight max-w-sm"><?= $data['company']['address'] ?? 'Alamat Perusahaan' ?></p>
                </div>
            </div>
            <div class="text-right">
                <h2 class="text-3xl font-black text-gray-200 uppercase tracking-widest leading-none">REPORT</h2>
                <p class="text-sm font-bold text-indigo-600 uppercase mt-1">Executive Dashboard</p>
            </div>
        </div>

        <div class="bg-gray-50 rounded-lg p-4 mb-8 border border-gray-100 flex justify-between items-center">
            <div>
                <p class="text-xs text-gray-500 uppercase tracking-wider font-bold">Periode Data</p>
                <p class="text-lg font-bold text-gray-900"><?= $data['period_text'] ?></p>
            </div>
            <div class="text-right">
                <p class="text-xs text-gray-500 uppercase tracking-wider font-bold">Generated At</p>
                <p class="text-sm font-medium text-gray-700"><?= $data['generated_at'] ?></p>
            </div>
        </div>

        <div class="grid grid-cols-4 gap-4 mb-8">
            <div class="border border-gray-200 rounded-lg p-4 flex flex-col justify-between">
                <p class="text-xs font-bold text-gray-500 uppercase">Total Pegawai</p>
                <p class="text-3xl font-black text-gray-800 mt-2"><?= $data['stats']['total_emp'] ?></p>
            </div>
            <div class="border border-gray-200 rounded-lg p-4 flex flex-col justify-between bg-blue-50/50">
                <p class="text-xs font-bold text-blue-600 uppercase">Attendance Rate</p>
                <p class="text-3xl font-black text-blue-700 mt-2"><?= $data['stats']['attendance_rate'] ?>%</p>
            </div>
            <div class="border border-gray-200 rounded-lg p-4 flex flex-col justify-between bg-red-50/50">
                <p class="text-xs font-bold text-red-600 uppercase">Total Late</p>
                <p class="text-3xl font-black text-red-700 mt-2"><?= $data['stats']['total_late'] ?></p>
            </div>
            <div class="border border-gray-200 rounded-lg p-4 flex flex-col justify-between bg-gray-900 text-white">
                <p class="text-xs font-bold text-gray-400 uppercase">Est. Payroll</p>
                <p class="text-xl font-bold mt-2">Rp <?= number_format($data['stats']['payroll_est']/1000000, 1) ?> Jt</p>
            </div>
        </div>

        <div class="grid grid-cols-3 gap-6 mb-8">
            <div class="col-span-2 border border-gray-200 rounded-lg p-4">
                <h3 class="text-xs font-bold text-gray-500 uppercase mb-4 border-b pb-2">Trend Kehadiran Harian</h3>
                <div class="chart-container">
                    <canvas id="printAttChart"></canvas>
                </div>
            </div>
            
            <div class="border border-gray-200 rounded-lg p-4">
                <h3 class="text-xs font-bold text-gray-500 uppercase mb-4 border-b pb-2">Task Completion</h3>
                <div class="chart-container-sm mb-2">
                    <canvas id="printTaskChart"></canvas>
                </div>
                <div class="text-center text-xs space-y-1">
                    <div class="flex justify-between px-4"><span class="text-green-600 font-bold">Done</span> <span><?= $data['chart_tasks'][0] ?></span></div>
                    <div class="flex justify-between px-4"><span class="text-blue-600 font-bold">Process</span> <span><?= $data['chart_tasks'][1] ?></span></div>
                    <div class="flex justify-between px-4"><span class="text-gray-400 font-bold">Pending</span> <span><?= $data['chart_tasks'][2] ?></span></div>
                </div>
            </div>
        </div>

        <div class="mb-12">
            <div class="bg-indigo-600 text-white px-4 py-2 rounded-t-lg flex justify-between items-center">
                <span class="text-xs font-bold uppercase tracking-wider">Top 5 KPI Performers</span>
                <span class="text-xs italic opacity-75">Based on Task & Attendance</span>
            </div>
            <div class="border border-gray-200 rounded-b-lg overflow-hidden">
                <table class="w-full text-sm">
                    <thead class="bg-gray-50 text-gray-500 border-b border-gray-200">
                        <tr>
                            <th class="py-2 px-4 text-center w-12">#</th>
                            <th class="py-2 px-4 text-left">Nama Karyawan</th>
                            <th class="py-2 px-4 text-left">Jabatan</th>
                            <th class="py-2 px-4 text-center">Kehadiran</th>
                            <th class="py-2 px-4 text-center">Task Score</th>
                            <th class="py-2 px-4 text-center font-bold text-indigo-600">KPI Score</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        <?php if(!empty($data['top_employees'])): foreach($data['top_employees'] as $idx => $emp): ?>
                        <tr>
                            <td class="py-3 px-4 text-center font-bold text-gray-400"><?= $idx+1 ?></td>
                            <td class="py-3 px-4 font-bold text-gray-800"><?= $emp['first_name'] . ' ' . $emp['last_name'] ?></td>
                            <td class="py-3 px-4 text-gray-500 text-xs"><?= $emp['position'] ?></td>
                            <td class="py-3 px-4 text-center">
                                <span class="bg-green-50 text-green-700 px-2 py-1 rounded text-xs font-bold"><?= $emp['att_pct'] ?>%</span>
                            </td>
                            <td class="py-3 px-4 text-center font-mono text-gray-600"><?= number_format($emp['avg_task_score'], 1) ?></td>
                            <td class="py-3 px-4 text-center">
                                <span class="text-base font-black text-indigo-700"><?= $emp['kpi_score'] ?></span>
                            </td>
                        </tr>
                        <?php endforeach; endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="grid grid-cols-2 gap-12 text-center text-sm mt-auto">
            <div>
                <p class="text-gray-500 mb-16">Dibuat Oleh,</p>
                <p class="font-bold text-gray-900 border-b border-gray-300 inline-block pb-1 min-w-[200px] uppercase"><?= $data['generated_by'] ?></p>
                <p class="text-xs text-gray-400 mt-1">Administrator</p>
            </div>
            <div>
                <p class="text-gray-500 mb-16">Mengetahui,</p>
                <p class="font-bold text-gray-900 border-b border-gray-300 inline-block pb-1 min-w-[200px]">DIREKTUR UTAMA</p>
            </div>
        </div>

    </div>

    <script>
        Chart.defaults.font.family = "'Segoe UI', sans-serif";
        Chart.defaults.color = '#6b7280';
        Chart.defaults.scale.grid.color = '#f3f4f6';

        // 1. Attendance Line
        new Chart(document.getElementById('printAttChart'), {
            type: 'line',
            data: {
                labels: <?= json_encode($data['chart_attendance']['labels']) ?>,
                datasets: [{
                    label: 'Hadir',
                    data: <?= json_encode($data['chart_attendance']['present']) ?>,
                    borderColor: '#4f46e5', borderWidth: 2, pointRadius: 0, fill: false, tension: 0.3
                }]
            },
            options: {
                animation: false,
                responsive: true, maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: { x: { grid: { display: false }, ticks: { font: { size: 9 } } }, y: { beginAtZero: true } }
            }
        });

        // 2. Task Doughnut
        new Chart(document.getElementById('printTaskChart'), {
            type: 'doughnut',
            data: {
                labels: ['Done', 'Process', 'Pending'],
                datasets: [{
                    data: <?= json_encode($data['chart_tasks']) ?>,
                    backgroundColor: ['#22c55e', '#3b82f6', '#e5e7eb'], borderWidth: 0, cutout: '70%'
                }]
            },
            options: { animation: false, responsive: true, maintainAspectRatio: false, plugins: { legend: { display: false } } }
        });

        window.onload = () => setTimeout(() => window.print(), 800);
    </script>

</body>
</html>