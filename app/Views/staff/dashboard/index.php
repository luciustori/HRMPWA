<div class="max-w-7xl mx-auto px-4 py-8 font-sans bg-gray-50 min-h-screen">

    <div class="mb-8">
        <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest pl-1">MeZone Portal</span>
        <h1 class="text-3xl font-black text-slate-800 tracking-tight">
            Halo, <?= explode(' ', $data['employee']['first_name'])[0] ?> <span class="text-yellow-500 animate-pulse inline-block">👋</span>
        </h1>
        <p class="text-slate-500 text-sm mt-1 pl-1">
            Have a productive day! | <span id="header-clock" class="font-mono text-indigo-600 font-bold">--:--:--</span>
        </p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 mb-8">
        
        <div class="lg:col-span-3 bg-white rounded-2xl p-5 shadow-sm border border-slate-100 flex flex-col justify-between h-full">
            <div class="flex justify-between items-center mb-4">
                <h3 class="font-bold text-slate-700 flex items-center gap-2 text-sm">
                    <i class="ri-calendar-event-fill text-indigo-500"></i> Kalender
                </h3>
                <span class="text-[10px] font-bold bg-slate-100 text-slate-500 px-2 py-1 rounded">
                    <?= date('M Y') ?>
                </span>
            </div>
            <div class="grid grid-cols-7 text-center gap-y-3 text-xs">
                <span class="text-slate-400 font-bold">M</span>
                <span class="text-slate-400 font-bold">S</span>
                <span class="text-slate-400 font-bold">S</span>
                <span class="text-slate-400 font-bold">R</span>
                <span class="text-slate-400 font-bold">K</span>
                <span class="text-slate-400 font-bold">J</span>
                <span class="text-slate-400 font-bold">S</span>
                
                <?php
                $daysInMonth = date('t');
                $startDay = date('w', strtotime(date('Y-m-01')));
                $map = $data['calendar_map'];

                for($i=0; $i<$startDay; $i++) echo "<span></span>";

                for($d=1; $d<=$daysInMonth; $d++) {
                    $isToday = ($d == date('j'));
                    $baseClass = "w-7 h-7 flex items-center justify-center rounded-lg font-bold transition cursor-default mx-auto text-[10px]";
                    $colorClass = "text-slate-600 hover:bg-slate-50"; 

                    if (isset($map[$d])) {
                        if ($map[$d]['is_late'] == 1) {
                            $colorClass = "bg-amber-100 text-amber-700 border border-amber-200";
                        } elseif ($map[$d]['status'] == 'present') {
                            $colorClass = "bg-emerald-100 text-emerald-700 border border-emerald-200";
                        } elseif ($map[$d]['status'] == 'absent') {
                            $colorClass = "bg-red-100 text-red-700 border border-red-200";
                        }
                    }

                    if ($isToday) {
                        $colorClass = "bg-indigo-600 text-white shadow-md shadow-indigo-200";
                    }
                    echo "<span class='$baseClass $colorClass'>$d</span>";
                }
                ?>
            </div>
            <div class="mt-4 flex gap-3 justify-center text-[9px] text-slate-400 font-bold uppercase tracking-wider">
                <span class="flex items-center gap-1"><span class="w-1.5 h-1.5 rounded-full bg-emerald-400"></span> Hadir</span>
                <span class="flex items-center gap-1"><span class="w-1.5 h-1.5 rounded-full bg-amber-400"></span> Telat</span>
            </div>
        </div>

        <div class="lg:col-span-5 bg-gradient-to-br from-blue-600 to-indigo-700 rounded-2xl p-6 text-white shadow-xl shadow-blue-200 relative overflow-hidden flex flex-col justify-between min-h-[300px]">
            <div class="absolute top-0 right-0 w-48 h-48 bg-white opacity-5 rounded-full blur-3xl -mr-10 -mt-10"></div>
            
            <div class="relative z-10">
                <span class="inline-block px-2.5 py-1 bg-white/20 backdrop-blur-sm rounded text-[9px] font-black uppercase tracking-widest mb-2 border border-white/10">
                    HARI INI
                </span>
                <h2 class="text-xl font-bold tracking-tight"><?= $data['date_indo'] ?></h2>
            </div>

            <div class="relative z-10 text-center my-4">
                <div id="realtime-clock" class="text-6xl font-mono font-bold tracking-tighter drop-shadow-md">00:00:00</div>
                <p class="text-blue-200 text-[10px] font-bold tracking-[0.2em] uppercase mt-2">Waktu Indonesia Barat</p>
            </div>

            <div class="relative z-10 bg-black/20 backdrop-blur-md rounded-xl p-3 flex justify-between items-center border border-white/10">
                <div>
                    <p class="text-[9px] text-blue-200 uppercase font-bold tracking-wider mb-0.5">Jadwal Shift</p>
                    <?php if(isset($data['shift']['is_off']) && $data['shift']['is_off']): ?>
                        <p class="text-sm font-bold text-red-300">LIBUR / OFF</p>
                    <?php else: ?>
                        <p class="text-sm font-bold">
                            <?= substr($data['shift']['start_time'], 0, 5) ?> - <?= substr($data['shift']['end_time'], 0, 5) ?>
                        </p>
                    <?php endif; ?>
                </div>
                
                <?php 
                    $att = $data['attendance'];
                    $is_off = isset($data['shift']['is_off']) ? $data['shift']['is_off'] : false;
                    
                    if ($is_off && !$att) {
                        // KONDISI LIBUR: Matikan tombol
                        $btnClass = 'bg-red-500/20 text-red-200 border border-red-500/30 cursor-not-allowed pointer-events-none';
                        $btnText = 'Sedang Libur';
                        $linkUrl = '#';
                    } else {
                        // KONDISI KERJA: Hidupkan tombol sesuai status
                        $btnClass = $att ? 'bg-emerald-500 text-white cursor-default pointer-events-none' : 'bg-slate-700 text-white hover:bg-slate-600';
                        $btnText = 'Belum Absen';
                        $linkUrl = BASEURL . '/staff/attendance';
                        
                        if ($att) {
                            if ($att['check_out_time']) {
                                $btnText = 'Sudah Pulang';
                            } else {
                                $btnText = 'Sudah Masuk';
                            }
                        }
                    }
                ?>
                <a href="<?= $linkUrl ?>" class="px-4 py-2 rounded-lg font-bold text-xs shadow transition active:scale-95 <?= $btnClass ?>">
                    <?= $btnText ?>
                </a>
            </div>
        </div>

        <div class="lg:col-span-4 flex flex-col gap-4">
            <a href="<?= BASEURL ?>/staff/requests/create?type=leave" class="flex-1 bg-white p-4 rounded-2xl border border-slate-100 shadow-sm hover:shadow-md hover:-translate-y-1 transition group flex items-center gap-4">
                <div class="w-10 h-10 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center text-lg group-hover:bg-blue-600 group-hover:text-white transition">
                    <i class="ri-calendar-check-fill"></i>
                </div>
                <div>
                    <h4 class="font-bold text-slate-700 text-sm">Cuti</h4>
                    <p class="text-[10px] text-slate-400">Ajukan Cuti Baru</p>
                </div>
            </a>

            <a href="<?= BASEURL ?>/staff/requests/create?type=permit" class="flex-1 bg-white p-4 rounded-2xl border border-slate-100 shadow-sm hover:shadow-md hover:-translate-y-1 transition group flex items-center gap-4">
                <div class="w-10 h-10 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center text-lg group-hover:bg-amber-500 group-hover:text-white transition">
                    <i class="ri-time-fill"></i>
                </div>
                <div>
                    <h4 class="font-bold text-slate-700 text-sm">Izin / Sakit</h4>
                    <p class="text-[10px] text-slate-400">Form Izin Kerja</p>
                </div>
            </a>

            <a href="#" class="flex-1 bg-white p-4 rounded-2xl border border-slate-100 shadow-sm hover:shadow-md hover:-translate-y-1 transition group flex items-center gap-4">
                <div class="w-10 h-10 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-lg group-hover:bg-emerald-500 group-hover:text-white transition">
                    <i class="ri-bill-fill"></i>
                </div>
                <div>
                    <h4 class="font-bold text-slate-700 text-sm">Reimburse</h4>
                    <p class="text-[10px] text-slate-400">Klaim Pengeluaran</p>
                </div>
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        
        <div class="bg-slate-900 rounded-2xl p-5 text-white shadow-lg relative overflow-hidden group">
            <div class="absolute right-0 top-0 p-4 opacity-10"><i class="ri-bar-chart-fill text-6xl"></i></div>
            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Skor Kinerja</p>
            <div class="flex items-end gap-3 mt-2">
                <span class="text-4xl font-black text-white"><?= $data['stats']['score_grade'] ?></span>
                <span class="text-sm text-slate-400 font-medium mb-1">/ <?= $data['stats']['score_val'] ?></span>
            </div>
            <div class="mt-4 flex gap-4 text-[10px] font-bold uppercase tracking-wider">
                <div class="bg-white/10 px-2 py-1 rounded">
                    Bulan Lalu
                </div>
            </div>
        </div>

        <div class="bg-white rounded-2xl p-5 shadow-sm border border-slate-100 flex flex-col justify-center items-center text-center hover:border-blue-200 transition">
            <div class="w-10 h-10 rounded-full bg-blue-50 text-blue-600 flex items-center justify-center mb-2">
                <i class="ri-umbrella-fill text-xl"></i>
            </div>
            <h3 class="text-2xl font-black text-slate-800"><?= $data['stats']['sisa_cuti'] ?></h3>
            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mt-1">Sisa Cuti</p>
        </div>

        <div class="bg-white rounded-2xl p-5 shadow-sm border border-slate-100 flex flex-col justify-center items-center text-center hover:border-violet-200 transition">
            <div class="w-10 h-10 rounded-full bg-violet-50 text-violet-600 flex items-center justify-center mb-2">
                <i class="ri-hourglass-fill text-xl"></i>
            </div>
            <h3 class="text-2xl font-black text-slate-800"><?= $data['stats']['jam_kerja'] ?></h3>
            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mt-1">Jam Kerja (Bln)</p>
        </div>

        <div class="bg-white rounded-2xl p-5 shadow-sm border border-slate-100 flex flex-col justify-center items-center text-center hover:border-emerald-200 transition">
            <div class="w-10 h-10 rounded-full bg-emerald-50 text-emerald-600 flex items-center justify-center mb-2">
                <i class="ri-check-double-fill text-xl"></i>
            </div>
            <h3 class="text-2xl font-black text-slate-800"><?= $data['stats']['hari_masuk'] ?></h3>
            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mt-1">Hari Masuk</p>
        </div>

    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        
        <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-6">
            <div class="flex justify-between items-center mb-6">
                <h3 class="font-bold text-slate-700 text-sm">Tugas Pending</h3>
                <a href="<?= BASEURL ?>/staff/tasks" class="text-xs font-bold text-indigo-600 hover:underline">Lihat Semua</a>
            </div>
            
            <div class="space-y-3">
                <?php if (empty($data['tasks'])): ?>
                    <div class="text-center py-4 text-slate-400 text-xs italic">
                        Tidak ada tugas pending. Bagus!
                    </div>
                <?php else: ?>
                    <?php foreach($data['tasks'] as $task): ?>
                    <div class="flex items-start gap-3 p-3 rounded-xl bg-slate-50 border border-slate-100 hover:bg-indigo-50 transition cursor-pointer">
                        <div class="w-8 h-8 rounded-lg bg-indigo-100 text-indigo-600 flex items-center justify-center shrink-0">
                            <i class="ri-checkbox-circle-line"></i>
                        </div>
                        <div class="flex-1">
                            <h4 class="text-sm font-bold text-slate-700"><?= $task['title'] ?></h4>
                            <p class="text-xs text-slate-400 mt-0.5">
                                Due: <?= date('d M', strtotime($task['due_date'])) ?>
                                <?php if($task['priority'] == 'urgent' || $task['priority'] == 'high'): ?>
                                    <span class="text-red-500 font-bold ml-1">• <?= ucfirst($task['priority']) ?></span>
                                <?php endif; ?>
                            </p>
                        </div>
                    </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>

        <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-6 relative">
            <div class="flex justify-between items-center mb-6">
                <h3 class="font-bold text-slate-700 text-sm">Pengumuman Terbaru</h3>
                <?php if($data['announcement']): ?>
                    <span class="w-2 h-2 rounded-full bg-red-500 animate-pulse"></span>
                <?php endif; ?>
            </div>
            
            <?php if ($data['announcement']): ?>
                <div class="bg-yellow-50 p-4 rounded-xl border border-yellow-100">
                    <h4 class="font-bold text-slate-800 text-sm mb-2"><?= $data['announcement']['title'] ?></h4>
                    <p class="text-xs text-slate-600 line-clamp-3 leading-relaxed">
                        <?= $data['announcement']['content'] ?>
                    </p>
                    <p class="text-[10px] text-slate-400 mt-3 font-bold">
                        Diposting: <?= date('d M Y, H:i', strtotime($data['announcement']['created_at'])) ?>
                    </p>
                </div>
            <?php else: ?>
                <div class="h-32 flex items-center justify-center text-center">
                    <p class="text-sm text-slate-400 italic">Tidak ada pengumuman baru.</p>
                </div>
            <?php endif; ?>
        </div>

    </div>

</div>

<script>
    function updateClock() {
        const now = new Date();
        const timeString = now.toLocaleTimeString('id-ID', { hour12: false });
        const formattedTime = timeString.replace(/\./g, ':');
        
        const clockEl = document.getElementById('realtime-clock');
        const headerClockEl = document.getElementById('header-clock');
        
        if(clockEl) clockEl.innerText = formattedTime;
        if(headerClockEl) headerClockEl.innerText = formattedTime + " WIB";
    }
    setInterval(updateClock, 1000);
    updateClock();
</script>