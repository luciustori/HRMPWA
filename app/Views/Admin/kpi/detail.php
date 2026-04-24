<?php
// MAPPING TEMA PREMIUM
$theme = $metrics['theme'] ?? 'blue';
$t = [
    'emerald' => ['bg' => 'bg-emerald-50', 'text' => 'text-emerald-700', 'badge' => 'bg-emerald-100 text-emerald-800', 'bar' => 'bg-emerald-600', 'icon' => 'text-emerald-500'],
    'green'   => ['bg' => 'bg-green-50',   'text' => 'text-green-700',   'badge' => 'bg-green-100 text-green-800',   'bar' => 'bg-green-600',   'icon' => 'text-green-500'],
    'blue'    => ['bg' => 'bg-blue-50',    'text' => 'text-blue-700',    'badge' => 'bg-blue-100 text-blue-800',    'bar' => 'bg-blue-600',    'icon' => 'text-blue-500'],
    'yellow'  => ['bg' => 'bg-amber-50',   'text' => 'text-amber-700',   'badge' => 'bg-amber-100 text-amber-800',   'bar' => 'bg-amber-500',   'icon' => 'text-amber-500'],
    'red'     => ['bg' => 'bg-rose-50',    'text' => 'text-rose-700',    'badge' => 'bg-rose-100 text-rose-800',    'bar' => 'bg-rose-600',    'icon' => 'text-rose-500'],
][$theme];

// Hitung Kontribusi Poin
$point_prod = round($metrics['prod'] * 0.3, 1);
$point_qual = round($metrics['qual'] * 0.3, 1);
$point_disc = round($metrics['disc'] * 0.2, 1);
$point_speed = round($metrics['speed'] * 0.2, 1);

// Format Tanggal Periode (Logic 25-24)
$p_start = date('d M', strtotime($metrics['period_info']['start']));
$p_end   = date('d M Y', strtotime($metrics['period_info']['end']));
?>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<div class="max-w-7xl mx-auto p-6 lg:p-8 space-y-8 font-sans">

    <div class="relative bg-white rounded-3xl p-8 shadow-[0_20px_50px_rgba(0,0,0,0.05)] border border-gray-100 overflow-hidden">
        <div class="absolute top-0 right-0 w-96 h-96 bg-gradient-to-br from-gray-50 to-slate-100 rounded-full blur-3xl opacity-50 -z-10"></div>
        
        <div class="flex flex-col md:flex-row items-center gap-8 md:gap-12">
            <div class="relative">
                <div class="absolute -inset-1 bg-gradient-to-br from-gray-200 to-gray-100 rounded-full blur opacity-75"></div>
                <img src="<?= BASEURL ?>/uploads/profiles/<?= $employee['profile_photo_path'] ?? 'default.png' ?>" 
                     onerror="this.src='https://ui-avatars.com/api/?name=<?= urlencode($employee['first_name']) ?>&background=0f172a&color=fff&size=128'"
                     class="relative w-32 h-32 rounded-full border-4 border-white shadow-xl object-cover grayscale-[20%] hover:grayscale-0 transition duration-500">
                
                <div class="absolute -bottom-3 left-1/2 transform -translate-x-1/2 bg-gray-900 text-white text-[10px] font-bold uppercase tracking-widest px-3 py-1 rounded-full shadow-lg border-2 border-white">
                    <?= $employee['position'] ?? 'STAFF' ?>
                </div>
            </div>

            <div class="text-center md:text-left flex-1 space-y-2">
                <h1 class="text-4xl font-extrabold text-gray-900 tracking-tight leading-tight">
                    <?= $employee['first_name'] . ' ' . $employee['last_name'] ?>
                </h1>
                <div class="flex flex-wrap justify-center md:justify-start gap-4 text-sm text-gray-500 font-medium">
                    <span class="flex items-center gap-1.5 px-3 py-1 bg-gray-50 rounded-lg border border-gray-200">
                        <i class="far fa-id-badge"></i> <?= $employee['employee_number'] ?>
                    </span>
                    <span class="flex items-center gap-1.5 px-3 py-1 bg-gray-50 rounded-lg border border-gray-200">
                        <i class="far fa-building"></i> <?= $employee['department_name'] ?? 'General' ?>
                    </span>
                    <span class="flex items-center gap-1.5 px-3 py-1 bg-indigo-50 text-indigo-700 rounded-lg border border-indigo-100" title="Periode Cut-Off">
                        <i class="far fa-calendar-check"></i> <?= $p_start ?> - <?= $p_end ?>
                    </span>
                </div>
            </div>

            <div class="flex items-center gap-6 border-l border-gray-100 pl-8 md:pl-12">
                <div class="text-right">
                    <p class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-1">Final Score</p>
                    <div class="text-5xl font-black text-gray-900 tracking-tighter">
                        <?= $metrics['final_score'] ?><span class="text-2xl text-gray-300 font-medium">/100</span>
                    </div>
                </div>
                <div class="w-24 h-24 flex items-center justify-center rounded-2xl <?= $t['bg'] ?> <?= $t['text'] ?> text-6xl font-black shadow-inner border border-white/50">
                    <?= $metrics['grade'] ?>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        
        <div class="space-y-8">
            <div class="bg-white rounded-3xl p-6 shadow-sm border border-gray-200">
                <div class="flex justify-between items-center mb-6">
                    <h3 class="font-bold text-gray-900">Peta Kompetensi</h3>
                </div>
                <div class="relative h-64 w-full flex justify-center">
                    <canvas id="performanceChart"></canvas>
                </div>
            </div>

            <div class="bg-gray-900 text-white rounded-3xl p-8 shadow-xl relative overflow-hidden">
                <div class="absolute top-0 right-0 p-8 opacity-10">
                    <i class="fas fa-quote-right text-8xl"></i>
                </div>
                <h3 class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-4 flex items-center gap-2">
                    <span class="w-2 h-2 rounded-full bg-blue-500"></span> Kesimpulan Evaluasi
                </h3>
                <p class="text-xl font-medium leading-relaxed italic text-gray-200">
                    "<?= $metrics['recommendation'] ?>"
                </p>
            </div>
        </div>

        <div class="lg:col-span-2 bg-white rounded-3xl p-8 shadow-sm border border-gray-200 flex flex-col">
            <div class="flex justify-between items-end mb-8">
                <div>
                    <h3 class="text-2xl font-bold text-gray-900 tracking-tight">Rincian Penilaian Kinerja</h3>
                    <p class="text-sm text-gray-500 mt-1">Breakdown kalkulasi skor berdasarkan periode 25-24.</p>
                </div>
                <div class="hidden md:block">
                    <span class="px-3 py-1 rounded-full bg-gray-100 text-xs font-bold text-gray-600 uppercase tracking-wide">
                        Weighted Average
                    </span>
                </div>
            </div>

            <div class="space-y-6 flex-1">
                
                <div class="group">
                    <div class="flex items-center justify-between mb-2">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center">
                                <i class="fas fa-rocket"></i>
                            </div>
                            <div>
                                <h4 class="font-bold text-gray-900">Produktivitas</h4>
                                <p class="text-xs text-gray-500">
                                    Selesai: <strong class="text-gray-700"><?= $metrics['raw_task']['total_completed'] ?></strong> dari <?= $metrics['raw_task']['total_assigned'] ?> tugas
                                </p>
                            </div>
                        </div>
                        <div class="text-right">
                            <span class="text-sm font-bold text-gray-900"><?= $metrics['prod'] ?>%</span>
                            <span class="text-xs text-gray-400 block">Bobot 30%</span>
                        </div>
                    </div>
                    <div class="relative h-10 bg-gray-50 rounded-lg overflow-hidden flex items-center px-3 border border-gray-100">
                        <div class="absolute left-0 top-0 bottom-0 bg-blue-50 w-[<?= $metrics['prod'] ?>%] transition-all duration-1000"></div>
                        <div class="relative z-10 w-full flex justify-between text-xs">
                            <span class="text-gray-500 font-mono">Skor: <?= $metrics['prod'] ?> x 30%</span>
                            <span class="font-bold text-blue-700 font-mono">+<?= $point_prod ?></span>
                        </div>
                    </div>
                </div>

                <div class="group">
                    <div class="flex items-center justify-between mb-2">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center">
                                <i class="fas fa-star"></i>
                            </div>
                            <div>
                                <h4 class="font-bold text-gray-900">Kualitas Kerja</h4>
                                <p class="text-xs text-gray-500">
                                    Rata-rata Rating: <strong class="text-gray-700"><?= number_format($metrics['raw_task']['avg_quality'], 1) ?></strong> / 10
                                </p>
                            </div>
                        </div>
                        <div class="text-right">
                            <span class="text-sm font-bold text-gray-900"><?= $metrics['qual'] ?>%</span>
                            <span class="text-xs text-gray-400 block">Bobot 30%</span>
                        </div>
                    </div>
                    <div class="relative h-10 bg-gray-50 rounded-lg overflow-hidden flex items-center px-3 border border-gray-100">
                        <div class="absolute left-0 top-0 bottom-0 bg-amber-50 w-[<?= $metrics['qual'] ?>%] transition-all duration-1000"></div>
                        <div class="relative z-10 w-full flex justify-between text-xs">
                            <span class="text-gray-500 font-mono">Skor: <?= $metrics['qual'] ?> x 30%</span>
                            <span class="font-bold text-amber-700 font-mono">+<?= $point_qual ?></span>
                        </div>
                    </div>
                </div>

                <div class="group">
                    <div class="flex items-center justify-between mb-2">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center">
                                <i class="fas fa-user-clock"></i>
                            </div>
                            <div>
                                <h4 class="font-bold text-gray-900">Disiplin & Kehadiran</h4>
                                <p class="text-xs text-gray-500">
                                    Hadir: <strong class="text-gray-700"><?= $metrics['raw_att']['on_time'] ?></strong> dari 
                                    <strong class="text-gray-700"><?= $metrics['period_info']['total_working_days'] ?></strong> hari kerja efektif
                                </p>
                            </div>
                        </div>
                        <div class="text-right">
                            <span class="text-sm font-bold text-gray-900"><?= $metrics['disc'] ?>%</span>
                            <span class="text-xs text-gray-400 block">Bobot 20%</span>
                        </div>
                    </div>
                    <div class="relative h-10 bg-gray-50 rounded-lg overflow-hidden flex items-center px-3 border border-gray-100">
                        <div class="absolute left-0 top-0 bottom-0 bg-emerald-50 w-[<?= $metrics['disc'] ?>%] transition-all duration-1000"></div>
                        <div class="relative z-10 w-full flex justify-between text-xs">
                            <span class="text-gray-500 font-mono">Skor: <?= $metrics['disc'] ?> x 20%</span>
                            <span class="font-bold text-emerald-700 font-mono">+<?= $point_disc ?></span>
                        </div>
                    </div>
                </div>

                <div class="group">
                    <div class="flex items-center justify-between mb-2">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl bg-purple-50 text-purple-600 flex items-center justify-center">
                                <i class="fas fa-tachometer-alt"></i>
                            </div>
                            <div>
                                <h4 class="font-bold text-gray-900">Kecepatan (On-Time)</h4>
                                <p class="text-xs text-gray-500">
                                    <?= $metrics['raw_task']['total_ontime'] ?> tugas selesai sebelum deadline.
                                </p>
                            </div>
                        </div>
                        <div class="text-right">
                            <span class="text-sm font-bold text-gray-900"><?= $metrics['speed'] ?>%</span>
                            <span class="text-xs text-gray-400 block">Bobot 20%</span>
                        </div>
                    </div>
                    <div class="relative h-10 bg-gray-50 rounded-lg overflow-hidden flex items-center px-3 border border-gray-100">
                        <div class="absolute left-0 top-0 bottom-0 bg-purple-50 w-[<?= $metrics['speed'] ?>%] transition-all duration-1000"></div>
                        <div class="relative z-10 w-full flex justify-between text-xs">
                            <span class="text-gray-500 font-mono">Skor: <?= $metrics['speed'] ?> x 20%</span>
                            <span class="font-bold text-purple-700 font-mono">+<?= $point_speed ?></span>
                        </div>
                    </div>
                </div>

            </div>

            <div class="mt-8 pt-6 border-t border-gray-100 flex justify-between items-center">
                <p class="text-xs text-gray-400 uppercase tracking-widest font-bold">Total Kalkulasi</p>
                <div class="flex gap-4 text-sm font-mono text-gray-600">
                    <span><?= $point_prod ?></span> + 
                    <span><?= $point_qual ?></span> + 
                    <span><?= $point_disc ?></span> + 
                    <span><?= $point_speed ?></span> 
                    <span class="text-gray-900 font-bold">= <?= $metrics['final_score'] ?></span>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener("DOMContentLoaded", function() {
    const ctx = document.getElementById('performanceChart');
    if(ctx) {
        new Chart(ctx, {
            type: 'radar',
            data: {
                labels: ['Produktivitas', 'Kualitas', 'Disiplin', 'Kecepatan'],
                datasets: [{
                    label: 'Skor',
                    data: [
                        <?= $metrics['prod'] ?>, 
                        <?= $metrics['qual'] ?>, 
                        <?= $metrics['disc'] ?>, 
                        <?= $metrics['speed'] ?>
                    ],
                    fill: true,
                    backgroundColor: 'rgba(59, 130, 246, 0.2)', 
                    borderColor: 'rgba(59, 130, 246, 1)',
                    pointBackgroundColor: '#fff',
                    pointBorderColor: 'rgba(59, 130, 246, 1)',
                    pointRadius: 4,
                    borderWidth: 2
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    r: {
                        angleLines: { color: 'rgba(0,0,0,0.05)' },
                        grid: { color: 'rgba(0,0,0,0.05)' },
                        pointLabels: { font: { size: 11, weight: '600', family: 'sans-serif' }, color: '#64748b' },
                        suggestedMin: 0, suggestedMax: 100,
                        ticks: { display: false, stepSize: 25 }
                    }
                },
                plugins: { legend: { display: false } }
            }
        });
    }
});
</script>