<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<style>
    /* Custom Scrollbar untuk Widget */
    .custom-scroll::-webkit-scrollbar { width: 4px; }
    .custom-scroll::-webkit-scrollbar-track { background: #f1f1f1; }
    .custom-scroll::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 4px; }
    .custom-scroll::-webkit-scrollbar-thumb:hover { background: #94a3b8; }
    
    /* Animation Fade In */
    .fade-in-up { animation: fadeInUp 0.5s ease-out forwards; opacity: 0; transform: translateY(20px); }
    @keyframes fadeInUp { to { opacity: 1; transform: translateY(0); } }
    
    .delay-100 { animation-delay: 100ms; }
    .delay-200 { animation-delay: 200ms; }
    .delay-300 { animation-delay: 300ms; }
</style>

<div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 fade-in-up">
    <div>
        <h1 class="text-3xl font-extrabold text-gray-800 tracking-tight">
            Dashboard <span class="text-indigo-600">Overview</span>
        </h1>
        <p class="text-gray-500 mt-1">
            Selamat datang, <span class="font-bold text-gray-700"><?= explode(' ', $_SESSION['full_name'])[0] ?></span>! Hari ini produktivitas tim terlihat bagus. 🚀
        </p>
    </div>
    <div class="mt-4 md:mt-0 flex gap-3">
        <button class="bg-white border border-gray-200 text-gray-600 px-4 py-2 rounded-xl text-sm font-semibold shadow-sm hover:bg-gray-50 transition">
            <i class="fas fa-download mr-2"></i> Report
        </button>
        <button class="bg-indigo-600 text-white px-4 py-2 rounded-xl text-sm font-bold shadow-lg shadow-indigo-200 hover:bg-indigo-700 transition transform hover:-translate-y-0.5">
            <i class="fas fa-plus mr-2"></i> New Task
        </button>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-12 gap-6">

    <div class="lg:col-span-8 space-y-6">
        
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 fade-in-up delay-100">
            <div class="bg-gradient-to-br from-blue-500 to-blue-600 p-5 rounded-2xl text-white shadow-lg relative overflow-hidden group">
                <div class="relative z-10">
                    <p class="text-blue-100 text-xs font-bold uppercase tracking-wider">Total Pegawai</p>
                    <h3 class="text-3xl font-extrabold mt-1"><?= $stats['total_emp'] ?></h3>
                </div>
                <i class="fas fa-users absolute -right-3 -bottom-3 text-6xl text-white opacity-20 group-hover:scale-110 transition-transform"></i>
            </div>

            <div class="bg-gradient-to-br from-emerald-400 to-emerald-600 p-5 rounded-2xl text-white shadow-lg relative overflow-hidden group">
                <div class="relative z-10">
                    <p class="text-emerald-100 text-xs font-bold uppercase tracking-wider">Hadir Hari Ini</p>
                    <h3 class="text-3xl font-extrabold mt-1"><?= $stats['present'] ?></h3>
                </div>
                <i class="fas fa-user-check absolute -right-3 -bottom-3 text-6xl text-white opacity-20 group-hover:scale-110 transition-transform"></i>
            </div>

            <div class="bg-gradient-to-br from-orange-400 to-orange-600 p-5 rounded-2xl text-white shadow-lg relative overflow-hidden group">
                <div class="relative z-10">
                    <p class="text-orange-100 text-xs font-bold uppercase tracking-wider">Terlambat</p>
                    <h3 class="text-3xl font-extrabold mt-1"><?= $stats['late'] ?></h3>
                </div>
                <i class="fas fa-running absolute -right-3 -bottom-3 text-6xl text-white opacity-20 group-hover:scale-110 transition-transform"></i>
            </div>

            <div class="bg-gradient-to-br from-pink-500 to-rose-600 p-5 rounded-2xl text-white shadow-lg relative overflow-hidden group">
                <div class="relative z-10">
                    <p class="text-pink-100 text-xs font-bold uppercase tracking-wider">Pending Request</p>
                    <h3 class="text-3xl font-extrabold mt-1"><?= $stats['requests'] ?></h3>
                </div>
                <i class="fas fa-bell absolute -right-3 -bottom-3 text-6xl text-white opacity-20 group-hover:scale-110 transition-transform animate-pulse"></i>
            </div>
        </div>

        <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 fade-in-up delay-200">
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-lg font-bold text-gray-800">Tren Kehadiran (7 Hari Terakhir)</h3>
                <select class="text-xs bg-gray-50 border-none rounded-lg p-2 font-medium text-gray-600 cursor-pointer hover:bg-gray-100">
                    <option>Minggu Ini</option>
                    <option>Bulan Ini</option>
                </select>
            </div>
            <div class="h-64 w-full">
                <canvas id="attendanceChart"></canvas>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 fade-in-up delay-300">
            
            <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
                <h3 class="font-bold text-gray-800 mb-4 flex items-center gap-2">
                    <i class="fas fa-rocket text-indigo-500"></i> Quick Actions
                </h3>
                <div class="grid grid-cols-2 gap-3">
                    <a href="<?= BASEURL ?>/admin/employees/create" class="flex flex-col items-center justify-center p-4 bg-indigo-50 rounded-xl hover:bg-indigo-100 transition cursor-pointer group">
                        <i class="fas fa-user-plus text-indigo-600 text-xl mb-2 group-hover:scale-110 transition"></i>
                        <span class="text-xs font-bold text-indigo-700">Add Employee</span>
                    </a>
                    <div class="flex flex-col items-center justify-center p-4 bg-emerald-50 rounded-xl hover:bg-emerald-100 transition cursor-pointer group">
                        <i class="fas fa-file-export text-emerald-600 text-xl mb-2 group-hover:scale-110 transition"></i>
                        <span class="text-xs font-bold text-emerald-700">Export Rekap</span>
                    </div>
                    <div class="flex flex-col items-center justify-center p-4 bg-amber-50 rounded-xl hover:bg-amber-100 transition cursor-pointer group">
                        <i class="fas fa-bullhorn text-amber-600 text-xl mb-2 group-hover:scale-110 transition"></i>
                        <span class="text-xs font-bold text-amber-700">Broadcast</span>
                    </div>
                    <div class="flex flex-col items-center justify-center p-4 bg-purple-50 rounded-xl hover:bg-purple-100 transition cursor-pointer group">
                        <i class="fas fa-cog text-purple-600 text-xl mb-2 group-hover:scale-110 transition"></i>
                        <span class="text-xs font-bold text-purple-700">Settings</span>
                    </div>
                </div>
            </div>

            <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
                <h3 class="font-bold text-gray-800 mb-4 flex items-center gap-2">
                    <i class="fas fa-chart-pie text-pink-500"></i> KPI & Goals
                </h3>
                <div class="space-y-5">
                    <?php foreach($kpi as $goal): ?>
                    <div>
                        <div class="flex justify-between text-xs font-bold text-gray-600 mb-1">
                            <span><?= $goal['title'] ?></span>
                            <span><?= $goal['progress'] ?>%</span>
                        </div>
                        <div class="w-full bg-gray-100 rounded-full h-2.5">
                            <div class="<?= $goal['color'] ?> h-2.5 rounded-full shadow-md transition-all duration-1000 ease-out" style="width: <?= $goal['progress'] ?>%"></div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>

    </div>

    <div class="lg:col-span-4 space-y-6">

        <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 fade-in-up delay-200">
            <div class="flex justify-between items-center mb-4">
                <h3 class="font-bold text-gray-800">Butuh Persetujuan</h3>
                <span class="bg-red-100 text-red-600 text-xs px-2 py-1 rounded-full font-bold">3 Pending</span>
            </div>
            
            <div class="space-y-4">
                <?php foreach($approvals as $req): ?>
                <div class="flex items-center gap-3 p-3 rounded-xl hover:bg-gray-50 transition border border-transparent hover:border-gray-100">
                    <div class="w-10 h-10 rounded-full bg-gradient-to-tr from-indigo-500 to-purple-500 flex items-center justify-center text-white text-xs font-bold shadow-md">
                        <?= $req['avatar'] ?>
                    </div>
                    <div class="flex-1">
                        <h4 class="text-sm font-bold text-gray-800"><?= $req['name'] ?></h4>
                        <p class="text-xs text-gray-500"><?= $req['type'] ?> • <?= $req['date'] ?></p>
                    </div>
                    <div class="flex gap-1">
                        <button class="w-8 h-8 rounded-full bg-green-50 text-green-600 hover:bg-green-500 hover:text-white transition flex items-center justify-center shadow-sm">
                            <i class="fas fa-check text-xs"></i>
                        </button>
                        <button class="w-8 h-8 rounded-full bg-red-50 text-red-600 hover:bg-red-500 hover:text-white transition flex items-center justify-center shadow-sm">
                            <i class="fas fa-times text-xs"></i>
                        </button>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
            <button class="w-full mt-4 py-2 text-xs font-bold text-indigo-600 bg-indigo-50 rounded-lg hover:bg-indigo-100 transition">
                Lihat Semua Request
            </button>
        </div>

        <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 fade-in-up delay-300">
            <h3 class="font-bold text-gray-800 mb-4">Sebaran Divisi</h3>
            <div class="relative h-48">
                <canvas id="deptChart"></canvas>
                <div class="absolute inset-0 flex items-center justify-center flex-col pointer-events-none">
                    <span class="text-3xl font-bold text-gray-800">22</span>
                    <span class="text-xs text-gray-400 uppercase">Total Staff</span>
                </div>
            </div>
        </div>

        <div class="bg-gradient-to-br from-slate-800 to-slate-900 p-6 rounded-2xl text-white shadow-lg fade-in-up delay-300">
            <h3 class="font-bold text-lg mb-4 flex items-center gap-2">
                <i class="fas fa-broadcast-tower text-indigo-400"></i> Informasi Pusat
            </h3>
            <div class="space-y-4 max-h-48 overflow-y-auto custom-scroll pr-2">
                <?php foreach($updates as $info): ?>
                <div class="relative pl-4 border-l-2 <?= $info['color'] == 'red' ? 'border-red-500' : 'border-blue-500' ?>">
                    <h5 class="text-sm font-bold text-gray-200"><?= $info['title'] ?></h5>
                    <p class="text-xs text-gray-400 mt-0.5"><?= $info['desc'] ?></p>
                    <span class="text-[10px] text-gray-500 block mt-1"><?= $info['time'] ?></span>
                </div>
                <?php endforeach; ?>
            </div>
        </div>

    </div>

</div>

<script>
    // 1. Line Chart: Absensi Mingguan
    const ctxAttendance = document.getElementById('attendanceChart').getContext('2d');
    new Chart(ctxAttendance, {
        type: 'line',
        data: {
            labels: ['Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab', 'Min'],
            datasets: [{
                label: 'Hadir',
                data: [18, 20, 19, 21, 18, 15, 0],
                borderColor: '#4F46E5', // Indigo 600
                backgroundColor: 'rgba(79, 70, 229, 0.1)',
                borderWidth: 3,
                tension: 0.4, // Kurva smooth
                fill: true,
                pointBackgroundColor: '#fff',
                pointBorderColor: '#4F46E5',
                pointRadius: 4
            }, {
                label: 'Terlambat',
                data: [2, 1, 3, 0, 2, 5, 0],
                borderColor: '#F97316', // Orange 500
                backgroundColor: 'transparent',
                borderWidth: 2,
                borderDash: [5, 5],
                tension: 0.4,
                pointRadius: 0
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { position: 'top', align: 'end', labels: { usePointStyle: true, boxWidth: 8 } }
            },
            scales: {
                y: { beginAtZero: true, grid: { borderDash: [2, 4], color: '#f3f4f6' } },
                x: { grid: { display: false } }
            }
        }
    });

    // 2. Doughnut Chart: Departemen
    const ctxDept = document.getElementById('deptChart').getContext('2d');
    new Chart(ctxDept, {
        type: 'doughnut',
        data: {
            labels: ['Marketing', 'Keuangan', 'Teknisi', 'HRGA'],
            datasets: [{
                data: [8, 5, 6, 3],
                backgroundColor: [
                    '#3B82F6', // Blue
                    '#10B981', // Emerald
                    '#F59E0B', // Amber
                    '#EC4899'  // Pink
                ],
                borderWidth: 0,
                hoverOffset: 4
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            cutout: '75%', // Lubang tengah besar
            plugins: {
                legend: { display: false } // Sembunyikan legend default
            }
        }
    });
</script>