<style>
    .tech-card {
        background: white;
        border: 1px solid #e2e8f0;
        border-radius: 1rem;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
    }
    .tech-blue-card {
        background: linear-gradient(135deg, #1e40af 0%, #3b82f6 100%);
        color: white;
        border-radius: 1rem;
        position: relative;
        overflow: hidden;
    }
    .stat-box {
        background: #f8fafc;
        border: 1px solid #f1f5f9;
        border-radius: 0.75rem;
        padding: 1rem;
        text-align: center;
        transition: all 0.2s;
    }
    .stat-box:hover { transform: translateY(-2px); border-color: #cbd5e1; }
    
    /* Table Styling */
    .history-table th { font-size: 0.75rem; text-transform: uppercase; color: #64748b; font-weight: 600; padding: 12px 16px; background: #f8fafc; }
    .history-table td { padding: 16px; border-bottom: 1px solid #f1f5f9; font-size: 0.875rem; color: #334155; }
    .history-table tr:last-child td { border-bottom: none; }
</style>

<div class="max-w-7xl mx-auto px-4 py-8 font-sans">

    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold text-slate-800">Absensi Saya</h1>
            <p class="text-sm text-slate-500">Rekapitulasi kehadiran & lembur periode ini.</p>
        </div>
        
        <form class="flex gap-2">
            <select name="month" class="bg-white border border-slate-300 text-slate-700 text-sm rounded-lg px-3 py-2 outline-none focus:ring-2 focus:ring-blue-500" onchange="this.form.submit()">
                <?php for($m=1; $m<=12; $m++): ?>
                    <option value="<?= $m ?>" <?= $data['filter_month'] == $m ? 'selected' : '' ?>>
                        <?= date('F', mktime(0, 0, 0, $m, 10)) ?>
                    </option>
                <?php endfor; ?>
            </select>
            <select name="year" class="bg-white border border-slate-300 text-slate-700 text-sm rounded-lg px-3 py-2 outline-none focus:ring-2 focus:ring-blue-500" onchange="this.form.submit()">
                <option value="2025" <?= $data['filter_year'] == '2025' ? 'selected' : '' ?>>2025</option>
                <option value="2026" <?= $data['filter_year'] == '2026' ? 'selected' : '' ?>>2026</option>
                <option value="2027" <?= $data['filter_year'] == '2027' ? 'selected' : '' ?>>2027</option>
            </select>
        </form>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
        
        <div class="tech-blue-card p-6 flex flex-col justify-between shadow-lg">
            <div class="absolute top-0 right-0 w-32 h-32 bg-white opacity-10 rounded-full blur-3xl -mr-10 -mt-10"></div>
            
            <div>
                <span class="inline-block px-2 py-1 bg-white/20 rounded text-[10px] font-bold uppercase tracking-wider mb-2">Hari Ini</span>
                <h2 class="text-xl font-bold text-white"><?= date('l, d M Y') ?></h2>
            </div>

            <div class="my-6 text-center">
                <div class="text-5xl font-mono font-black tracking-tighter" id="realtime-clock">00:00:00</div>
                <p class="text-blue-200 text-xs mt-1 font-bold uppercase">Waktu Indonesia Barat</p>
            </div>

            <div class="bg-black/20 rounded-lg p-3 flex justify-between items-center backdrop-blur-sm border border-white/10">
                <div>
                    <p class="text-[10px] text-blue-200 uppercase font-bold tracking-wider mb-0.5">Jadwal Shift</p>
                    <?php if(isset($data['today']['is_off']) && $data['today']['is_off']): ?>
                        <p class="text-sm font-bold text-red-300">LIBUR / OFF</p>
                    <?php else: ?>
                        <p class="font-bold text-sm">
                            <?= substr($data['today']['start_time'],0,5) ?> - <?= substr($data['today']['end_time'],0,5) ?>
                        </p>
                    <?php endif; ?>
                </div>
                <div class="text-right">
                    <?php 
                    $is_off = isset($data['today']['is_off']) ? $data['today']['is_off'] : false;
                    
                    if ($is_off && empty($data['today']['clock_in'])): ?>
                        <span class="px-2 py-1 border border-white/20 text-red-200 rounded text-xs font-bold bg-white/10">Sedang Libur</span>
                    <?php elseif(!empty($data['today']['clock_in'])): ?>
                        <span class="px-2 py-1 bg-emerald-500 text-white rounded text-xs font-bold shadow-sm">Hadir</span>
                    <?php else: ?>
                        <span class="px-2 py-1 bg-slate-700/80 text-white rounded text-xs font-bold border border-white/10">Belum Absen</span>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <div class="lg:col-span-2 tech-card p-6">
            <h3 class="font-bold text-slate-700 mb-6 flex items-center gap-2">
                <i class="far fa-chart-bar text-slate-400"></i> Statistik Bulan Ini
            </h3>
            
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                <div class="bg-emerald-50 border border-emerald-100 rounded-xl p-4 text-center">
                    <p class="text-3xl font-black text-emerald-600"><?= $data['stats']['total_ontime'] ?? 0 ?></p>
                    <p class="text-[10px] font-bold text-emerald-600 uppercase mt-1">Tepat Waktu</p>
                </div>
                <div class="bg-amber-50 border border-amber-100 rounded-xl p-4 text-center">
                    <p class="text-3xl font-black text-amber-600"><?= $data['stats']['total_late'] ?? 0 ?></p>
                    <p class="text-[10px] font-bold text-amber-600 uppercase mt-1">Terlambat</p>
                </div>
                <div class="bg-rose-50 border border-rose-100 rounded-xl p-4 text-center">
                    <p class="text-3xl font-black text-rose-600"><?= $data['stats']['total_alpha'] ?? 0 ?></p>
                    <p class="text-[10px] font-bold text-rose-600 uppercase mt-1">Tidak Masuk</p>
                </div>
                <div class="bg-blue-50 border border-blue-100 rounded-xl p-4 text-center">
                    <p class="text-3xl font-black text-blue-600"><?= $data['stats']['total_masuk'] ?? 0 ?></p>
                    <p class="text-[10px] font-bold text-blue-600 uppercase mt-1">Total Hadir</p>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-2 md:grid-cols-5 gap-4 mb-8">
        <div class="stat-box hover:border-amber-200">
            <p class="text-2xl font-bold text-slate-800"><?= $data['stats']['total_izin'] ?? 0 ?></p>
            <p class="text-[10px] font-bold text-slate-400 uppercase mt-1">Izin / Sakit</p>
        </div>
        <div class="stat-box hover:border-blue-200">
            <p class="text-2xl font-bold text-slate-800"><?= $data['stats']['total_dinas'] ?? 0 ?></p>
            <p class="text-[10px] font-bold text-slate-400 uppercase mt-1">Dinas Luar</p>
        </div>
        <div class="stat-box hover:border-emerald-200">
            <p class="text-2xl font-bold text-emerald-600"><?= $data['extra']['sisa_cuti'] ?> <span class="text-sm font-medium text-slate-400">/ 12</span></p>
            <p class="text-[10px] font-bold text-slate-400 uppercase mt-1">Sisa Cuti</p>
        </div>
        <div class="stat-box hover:border-purple-200">
            <p class="text-2xl font-bold text-slate-800"><?= $data['stats']['total_sppd'] ?? 0 ?></p> 
            <p class="text-[10px] font-bold text-slate-400 uppercase mt-1">SPPD</p>
        </div>
        <div class="stat-box bg-slate-800 border-slate-700 shadow-md">
            <p class="text-2xl font-bold text-white"><?= $data['extra']['jam_lembur'] ?> <span class="text-sm font-normal text-slate-400">Jam</span></p>
            <p class="text-[10px] font-bold text-slate-400 uppercase mt-1">Total Lembur</p>
        </div>
    </div>

    <div class="tech-card overflow-hidden">
        <div class="p-6 border-b border-slate-100 flex justify-between items-center bg-white">
            <h3 class="font-bold text-slate-800 flex items-center gap-2">
                <i class="fas fa-history text-slate-400"></i> History Absensi
            </h3>
            <button class="text-xs font-bold text-indigo-600 hover:bg-indigo-50 px-3 py-1.5 rounded transition">Download Excel</button>
        </div>
        
        <div class="overflow-x-auto">
            <table class="w-full text-left history-table">
                <thead>
                    <tr>
                        <th>Tanggal</th>
                        <th>Jam Masuk</th>
                        <th>Jam Pulang</th>
                        <th>Durasi</th>
                        <th>Status</th>
                        <th>Ket</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-slate-50">
                    <?php if(empty($data['history'])): ?>
                        <tr><td colspan="6" class="text-center py-8 text-slate-400 italic">Belum ada data absensi bulan ini.</td></tr>
                    <?php else: ?>
                        <?php foreach($data['history'] as $row): 
                            $statusBadge = match($row['status']) {
                                'present' => '<span class="px-2 py-1 rounded bg-emerald-50 text-emerald-600 text-xs font-bold border border-emerald-100">Hadir</span>',
                                'late' => '<span class="px-2 py-1 rounded bg-amber-50 text-amber-600 text-xs font-bold border border-amber-100">Telat</span>',
                                'leave' => '<span class="px-2 py-1 rounded bg-blue-50 text-blue-600 text-xs font-bold border border-blue-100">Cuti/Ijin</span>',
                                'alpha' => '<span class="px-2 py-1 rounded bg-rose-50 text-rose-600 text-xs font-bold border border-rose-100">Alpha</span>',
                                default => '<span class="px-2 py-1 rounded bg-slate-100 text-slate-600 text-xs font-bold border border-slate-200">'.ucfirst($row['status']).'</span>'
                            };
                            
                            // Hitung Durasi Kerja
                            $durasi = '-';
                            if($row['clock_in'] && $row['clock_out']) {
                                $t1 = strtotime($row['clock_in']);
                                $t2 = strtotime($row['clock_out']);
                                $diff = $t2 - $t1;
                                $hours = floor($diff / (60 * 60));
                                $minutes = floor(($diff - $hours * 60 * 60) / 60);
                                $durasi = "<span class='font-bold text-slate-700'>{$hours}j</span> {$minutes}m";
                            }
                        ?>
                        <tr class="hover:bg-slate-50/50 transition">
                            <td class="font-medium text-slate-700">
                                <?= date('d M Y', strtotime($row['attendance_date'])) ?>
                                <div class="text-[10px] text-slate-400 font-bold uppercase tracking-wider mt-0.5"><?= date('l', strtotime($row['attendance_date'])) ?></div>
                            </td>
                            <td class="font-mono text-slate-600 font-medium"><?= $row['clock_in'] ? date('H:i', strtotime($row['clock_in'])) : '--:--' ?></td>
                            <td class="font-mono text-slate-600 font-medium"><?= $row['clock_out'] ? date('H:i', strtotime($row['clock_out'])) : '--:--' ?></td>
                            <td class="text-slate-500 text-xs"><?= $durasi ?></td>
                            <td><?= $statusBadge ?></td>
                            <td class="text-[11px] text-slate-400 italic max-w-[150px] truncate" title="<?= $row['notes'] ?? '-' ?>"><?= $row['notes'] ?? '-' ?></td>
                        </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

</div>

<script>
    // Realtime Clock Script
    function updateClock() {
        const now = new Date();
        const timeString = now.toLocaleTimeString('en-GB', { hour: '2-digit', minute: '2-digit', second: '2-digit' });
        document.getElementById('realtime-clock').innerText = timeString;
    }
    setInterval(updateClock, 1000);
    updateClock();
</script>