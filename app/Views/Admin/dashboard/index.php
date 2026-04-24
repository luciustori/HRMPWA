<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<div class="flex flex-col md:flex-row justify-between items-center mb-8 gap-4">
    <div>
        <h1 class="text-3xl font-black text-gray-800 tracking-tight">Executive Dashboard</h1>
        <p class="text-sm text-gray-500">Overview performa & statistik perusahaan.</p>
    </div>
    <div class="flex items-center gap-3">
        <form method="GET" class="bg-white px-3 py-2 rounded-lg border border-gray-200 shadow-sm flex items-center">
            <i class="ri-calendar-line text-gray-400 mr-2"></i>
            <input type="month" name="filter_period" value="<?= $selected_year . '-' . $selected_month ?>" 
                   onchange="const [y, m] = this.value.split('-'); window.location.href='?year='+y+'&month='+m"
                   class="text-sm font-bold text-gray-700 border-none focus:ring-0 p-0 cursor-pointer bg-transparent">
        </form>
        <a href="<?= BASEURL ?>/admin/dashboard/print_report?year=<?= $selected_year ?>&month=<?= $selected_month ?>" target="_blank" class="bg-slate-800 text-white px-4 py-2 rounded-lg text-sm font-bold shadow hover:bg-slate-700 transition flex items-center gap-2">
            <i class="ri-printer-line"></i> Report
        </a>
    </div>
</div>

<div class="grid grid-cols-1 md:grid-cols-4 gap-5 mb-8">
    <div class="bg-gradient-to-br from-indigo-600 to-blue-700 p-5 rounded-2xl text-white shadow-lg relative overflow-hidden">
        <p class="text-xs font-bold text-indigo-200 uppercase tracking-wider">Est. Payroll Expense</p>
        <h3 class="text-2xl font-black mt-1">Rp <?= number_format($stats['payroll_est'] / 1000000, 1, ',', '.') ?> Jt</h3>
        <i class="ri-wallet-3-fill absolute -bottom-4 -right-4 text-7xl text-white opacity-10"></i>
    </div>
    <div class="bg-white p-5 rounded-2xl border border-gray-100 shadow-sm">
        <div class="flex justify-between items-start">
            <div><p class="text-xs font-bold text-gray-400 uppercase">Total Pegawai</p><h3 class="text-2xl font-black text-gray-800 mt-1"><?= $stats['total_emp'] ?></h3></div>
            <div class="p-2 bg-blue-50 text-blue-600 rounded-lg"><i class="ri-team-fill text-xl"></i></div>
        </div>
    </div>
    <div class="bg-white p-5 rounded-2xl border border-gray-100 shadow-sm">
        <div class="flex justify-between items-start">
            <div><p class="text-xs font-bold text-gray-400 uppercase">Kehadiran (Avg)</p><h3 class="text-2xl font-black text-gray-800 mt-1"><?= $stats['attendance_rate'] ?>%</h3></div>
            <div class="p-2 bg-emerald-50 text-emerald-600 rounded-lg"><i class="ri-bar-chart-grouped-fill text-xl"></i></div>
        </div>
    </div>
    <div class="bg-white p-5 rounded-2xl border border-gray-100 shadow-sm">
        <div class="flex justify-between items-start">
            <div><p class="text-xs font-bold text-gray-400 uppercase">Keterlambatan</p><h3 class="text-2xl font-black text-gray-800 mt-1"><?= $stats['total_late'] ?></h3></div>
            <div class="p-2 bg-orange-50 text-orange-600 rounded-lg"><i class="ri-timer-flash-line text-xl"></i></div>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-12 gap-6 mb-8">
    
    <div class="lg:col-span-8 space-y-6">
        
        <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm">
            <div class="flex justify-between items-center mb-6">
                <h3 class="font-bold text-gray-800 text-lg">Trend Kehadiran</h3>
                <span class="text-xs font-medium text-gray-500 bg-gray-100 px-2 py-1 rounded"><?= $period_text ?></span>
            </div>
            <div class="h-64 w-full">
                <canvas id="attendanceChart"></canvas>
            </div>
        </div>

        <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm">
            <h3 class="font-bold text-gray-800 text-lg mb-4 flex items-center gap-2">
                <i class="ri-trophy-fill text-yellow-500"></i> Top 5 KPI Performers
            </h3>
            
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left">
                    <thead class="text-xs text-gray-500 uppercase bg-gray-50/50 border-b border-gray-100">
                        <tr>
                            <th class="px-4 py-3">Rank</th>
                            <th class="px-4 py-3">Karyawan</th>
                            <th class="px-4 py-3 text-center">Disiplin (40%)</th>
                            <th class="px-4 py-3 text-center">Kualitas (60%)</th>
                            <th class="px-4 py-3 text-center">Total Score</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        <?php if(empty($top_employees)): ?>
                            <tr><td colspan="5" class="px-4 py-6 text-center text-gray-400 italic">Belum ada data untuk periode ini.</td></tr>
                        <?php else: ?>
                            <?php foreach($top_employees as $idx => $emp): 
                                $rankColor = match($idx) { 0=>'bg-yellow-100 text-yellow-700', 1=>'bg-gray-100 text-gray-600', 2=>'bg-orange-50 text-orange-700', default=>'bg-slate-50 text-slate-500' };
                            ?>
                            <tr class="hover:bg-slate-50/50 transition">
                                <td class="px-4 py-3">
                                    <div class="w-8 h-8 rounded-lg flex items-center justify-center font-black text-xs <?= $rankColor ?>">
                                        #<?= $idx+1 ?>
                                    </div>
                                </td>
                                <td class="px-4 py-3">
                                    <div class="flex items-center gap-3">
                                        <?php if($emp['profile_photo_path']): ?>
                                            <img src="<?= BASEURL ?>/uploads/profiles/<?= $emp['profile_photo_path'] ?>" class="w-8 h-8 rounded-full object-cover">
                                        <?php else: ?>
                                            <div class="w-8 h-8 rounded-full bg-indigo-100 text-indigo-600 flex items-center justify-center font-bold text-xs">
                                                <?= substr($emp['first_name'], 0, 1) ?>
                                            </div>
                                        <?php endif; ?>
                                        <div>
                                            <div class="font-bold text-gray-800"><?= $emp['first_name'] . ' ' . $emp['last_name'] ?></div>
                                            <div class="text-[10px] text-gray-500"><?= $emp['position'] ?></div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-4 py-3 text-center">
                                    <div class="font-bold text-emerald-600"><?= $emp['att_pct'] ?>%</div>
                                    <div class="text-[10px] text-gray-400"><?= $emp['present_days'] ?>/<?= $total_days ?> Hari</div>
                                </td>
                                <td class="px-4 py-3 text-center">
                                    <div class="font-bold text-blue-600"><?= $emp['avg_task_score'] ?></div>
                                    <div class="text-[10px] text-gray-400">Avg. Rating</div>
                                </td>
                                <td class="px-4 py-3 text-center">
                                    <span class="text-base font-black text-indigo-700"><?= $emp['kpi_score'] ?></span>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

    </div>

    <div class="lg:col-span-4 space-y-6">
        
        <div class="bg-gradient-to-br from-pink-500 to-rose-600 p-5 rounded-2xl text-white shadow-lg relative overflow-hidden">
            <div class="relative z-10">
                <h4 class="font-bold text-white flex items-center gap-2 mb-4">
                    <i class="ri-cake-2-fill text-yellow-300"></i> Ulang Tahun (Bulan Ini)
                </h4>
                
                <div class="space-y-3 max-h-[220px] overflow-y-auto custom-scroll pr-2">
                    <?php if(empty($birthdays)): ?>
                        <div class="text-center py-4 bg-white/10 rounded-xl border border-white/10">
                            <p class="text-xs text-pink-100 italic">Tidak ada yang ultah bulan ini.</p>
                        </div>
                    <?php else: ?>
                        <?php foreach($birthdays as $bday): ?>
                        <div class="flex items-center gap-3 bg-white/10 p-2.5 rounded-xl border border-white/10 backdrop-blur-sm">
                            <?php if($bday['profile_photo_path']): ?>
                                <img src="<?= BASEURL ?>/uploads/profiles/<?= $bday['profile_photo_path'] ?>" class="w-10 h-10 rounded-full object-cover border-2 border-white/30">
                            <?php else: ?>
                                <div class="w-10 h-10 rounded-full bg-white text-pink-600 flex items-center justify-center font-bold text-xs">
                                    <?= substr($bday['full_name'], 0, 1) ?>
                                </div>
                            <?php endif; ?>
                            
                            <div class="flex-1">
                                <h5 class="text-sm font-bold text-white truncate"><?= explode(' ', $bday['full_name'])[0] ?></h5>
                                <p class="text-[10px] text-pink-200">
                                    Tanggal <?= $bday['tgl'] ?>
                                    <?php if($bday['tgl'] == date('d') && $selected_month == date('m')): ?>
                                        <span class="ml-1 bg-yellow-400 text-yellow-900 px-1.5 py-0.5 rounded text-[9px] font-bold">HARI INI!</span>
                                    <?php endif; ?>
                                </p>
                            </div>
                            <div class="text-xl">🎂</div>
                        </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>
            <i class="ri-gift-line absolute -bottom-6 -right-6 text-9xl text-white opacity-10 rotate-12"></i>
        </div>

        <div class="bg-white p-5 rounded-2xl border border-gray-100 shadow-sm">
            <h4 class="font-bold text-gray-700 text-sm mb-4 border-b pb-2">Task Progress</h4>
            <div class="flex items-center gap-4">
                <div class="w-24 h-24 relative">
                    <canvas id="taskChart"></canvas>
                </div>
                <div class="text-xs space-y-2 flex-1">
                    <div class="flex justify-between"><span class="flex items-center gap-1"><span class="w-2 h-2 rounded-full bg-green-500"></span> Selesai</span> <b><?= $chart_tasks[0] ?></b></div>
                    <div class="flex justify-between"><span class="flex items-center gap-1"><span class="w-2 h-2 rounded-full bg-blue-500"></span> Proses</span> <b><?= $chart_tasks[1] ?></b></div>
                    <div class="flex justify-between"><span class="flex items-center gap-1"><span class="w-2 h-2 rounded-full bg-gray-300"></span> Pending</span> <b><?= $chart_tasks[2] ?></b></div>
                </div>
            </div>
        </div>

        <div class="bg-white p-5 rounded-2xl border border-gray-100 shadow-sm">
            <h4 class="font-bold text-gray-700 text-sm mb-4 border-b pb-2">Demografi Usia</h4>
            <div class="flex items-center gap-4">
                <div class="w-24 h-24 relative">
                    <canvas id="ageChart"></canvas>
                </div>
                <div class="text-xs space-y-1 flex-1 text-gray-500">
                    <?php 
                        $colors = ['#6366F1', '#8B5CF6', '#EC4899', '#F43F5E'];
                        foreach($chart_age['labels'] as $i => $label): 
                    ?>
                    <div class="flex justify-between">
                        <span class="flex items-center gap-1"><span class="w-2 h-2 rounded-full" style="background:<?= $colors[$i] ?>"></span> <?= $label ?></span>
                        <b><?= $chart_age['data'][$i] ?></b>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>

    </div>
</div>

<div class="mb-8">
    <h3 class="font-bold text-gray-800 text-lg mb-4">Butuh Persetujuan</h3>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <a href="<?= BASEURL ?>/admin/tasks?status=pending" class="bg-white p-4 rounded-xl border border-gray-100 shadow-sm hover:border-indigo-300 transition flex items-center justify-between group">
            <div class="flex items-center gap-3">
                <div class="p-2 bg-indigo-50 text-indigo-600 rounded-lg group-hover:bg-indigo-600 group-hover:text-white transition"><i class="ri-task-fill"></i></div>
                <span class="text-sm font-bold text-gray-700">Task Approval</span>
            </div>
            <?php if($approvals['tasks']>0): ?><span class="bg-red-500 text-white text-xs font-bold px-2 py-1 rounded-full"><?= $approvals['tasks'] ?></span><?php endif; ?>
        </a>
        <a href="<?= BASEURL ?>/admin/approvals" class="bg-white p-4 rounded-xl border border-gray-100 shadow-sm hover:border-orange-300 transition flex items-center justify-between group">
            <div class="flex items-center gap-3">
                <div class="p-2 bg-orange-50 text-orange-600 rounded-lg group-hover:bg-orange-600 group-hover:text-white transition"><i class="ri-mail-send-fill"></i></div>
                <span class="text-sm font-bold text-gray-700">Request Cuti/Ijin</span>
            </div>
            <?php if($approvals['requests']>0): ?><span class="bg-red-500 text-white text-xs font-bold px-2 py-1 rounded-full"><?= $approvals['requests'] ?></span><?php endif; ?>
        </a>
        <a href="<?= BASEURL ?>/admin/attendance?tab=approval" class="bg-white p-4 rounded-xl border border-gray-100 shadow-sm hover:border-blue-300 transition flex items-center justify-between group">
            <div class="flex items-center gap-3">
                <div class="p-2 bg-blue-50 text-blue-600 rounded-lg group-hover:bg-blue-600 group-hover:text-white transition"><i class="ri-time-fill"></i></div>
                <span class="text-sm font-bold text-gray-700">Koreksi Absensi</span>
            </div>
            <?php if($approvals['attendance']>0): ?><span class="bg-red-500 text-white text-xs font-bold px-2 py-1 rounded-full"><?= $approvals['attendance'] ?></span><?php endif; ?>
        </a>
    </div>
</div>

<div class="mb-8">
    <h3 class="font-bold text-gray-800 text-lg mb-4">Quick Access</h3>
    <div class="flex gap-3 overflow-x-auto pb-2">
        <a href="<?= BASEURL ?>/admin/employees/create" class="flex-shrink-0 bg-white border border-gray-200 px-4 py-3 rounded-xl hover:bg-slate-50 transition text-sm font-bold text-gray-600 flex items-center gap-2">
            <i class="ri-user-add-line text-lg text-blue-500"></i> Add Employee
        </a>
        <a href="<?= BASEURL ?>/admin/announcements/create" class="flex-shrink-0 bg-white border border-gray-200 px-4 py-3 rounded-xl hover:bg-slate-50 transition text-sm font-bold text-gray-600 flex items-center gap-2">
            <i class="ri-megaphone-line text-lg text-purple-500"></i> Buat Pengumuman
        </a>
        <a href="<?= BASEURL ?>/admin/reports" class="flex-shrink-0 bg-white border border-gray-200 px-4 py-3 rounded-xl hover:bg-slate-50 transition text-sm font-bold text-gray-600 flex items-center gap-2">
            <i class="ri-file-chart-line text-lg text-emerald-500"></i> Lihat Laporan
        </a>
        <a href="<?= BASEURL ?>/admin/settings" class="flex-shrink-0 bg-white border border-gray-200 px-4 py-3 rounded-xl hover:bg-slate-50 transition text-sm font-bold text-gray-600 flex items-center gap-2">
            <i class="ri-settings-line text-lg text-slate-500"></i> Pengaturan
        </a>
    </div>
</div>

<script>
    // 1. Line Chart
    new Chart(document.getElementById('attendanceChart'), {
        type: 'line',
        data: {
            labels: <?= json_encode($chart_attendance['labels']) ?>,
            datasets: [{
                label: 'Hadir', data: <?= json_encode($chart_attendance['present']) ?>,
                borderColor: '#4F46E5', backgroundColor: 'rgba(79, 70, 229, 0.1)', borderWidth: 2, fill: true, tension: 0.4, pointRadius: 0
            }, {
                label: 'Telat', data: <?= json_encode($chart_attendance['late']) ?>,
                borderColor: '#F97316', backgroundColor: 'transparent', borderWidth: 2, borderDash: [4, 4], tension: 0.4, pointRadius: 0
            }]
        },
        options: { responsive: true, maintainAspectRatio: false, plugins: { legend: { display: false } }, scales: { x: { grid: { display: false } }, y: { display: false } } }
    });

    // 2. Task Chart
    new Chart(document.getElementById('taskChart'), {
        type: 'doughnut',
        data: {
            labels: ['Selesai', 'Proses', 'Pending'],
            datasets: [{
                data: <?= json_encode($chart_tasks) ?>,
                backgroundColor: ['#22c55e', '#3b82f6', '#d1d5db'], borderWidth: 0, cutout: '75%'
            }]
        },
        options: { responsive: true, maintainAspectRatio: false, plugins: { legend: { display: false } } }
    });

    // 3. Age Chart
    new Chart(document.getElementById('ageChart'), {
        type: 'doughnut',
        data: {
            labels: <?= json_encode($chart_age['labels']) ?>,
            datasets: [{
                data: <?= json_encode(array_values($chart_age['data'])) ?>,
                backgroundColor: ['#6366F1', '#8B5CF6', '#EC4899', '#F43F5E'], borderWidth: 0, cutout: '65%'
            }]
        },
        options: { responsive: true, maintainAspectRatio: false, plugins: { legend: { display: false } } }
    });
</script>