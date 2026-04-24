<script>
    const CALENDAR_DATA = <?= json_encode($data['calendar']['data']) ?>;
    const MONTH_NAMES = ["Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember"];
    const DAY_NAMES = ["Minggu", "Senin", "Selasa", "Rabu", "Kamis", "Jumat", "Sabtu"];
</script>

<div class="container mx-auto px-4 py-8 max-w-7xl font-sans">
    
    <div class="flex flex-col md:flex-row justify-between items-end mb-8 gap-4">
        <div>
            <h1 class="text-3xl font-black text-slate-800 tracking-tight">Kalender Kerja</h1>
            <p class="text-slate-500 mt-1">Kelola jadwal, shift, dan agenda kegiatan Anda.</p>
        </div>
        
        <div class="flex items-center bg-white rounded-xl shadow-sm border border-slate-200 p-1.5">
            <?php 
                $currM = $data['calendar']['month'];
                $currY = $data['calendar']['year'];
                $prev = mktime(0, 0, 0, $currM - 1, 1, $currY);
                $next = mktime(0, 0, 0, $currM + 1, 1, $currY);
                $monthLabel = date('F Y', mktime(0, 0, 0, $currM, 1, $currY));
            ?>
            <a href="?month=<?= date('m', $prev) ?>&year=<?= date('Y', $prev) ?>" class="w-9 h-9 flex items-center justify-center text-slate-500 hover:bg-slate-100 hover:text-indigo-600 rounded-lg transition-colors">
                <i class="ri-arrow-left-s-line text-xl"></i>
            </a>
            <span class="px-6 font-bold text-slate-700 min-w-[160px] text-center select-none">
                <?= $monthLabel ?>
            </span>
            <a href="?month=<?= date('m', $next) ?>&year=<?= date('Y', $next) ?>" class="w-9 h-9 flex items-center justify-center text-slate-500 hover:bg-slate-100 hover:text-indigo-600 rounded-lg transition-colors">
                <i class="ri-arrow-right-s-line text-xl"></i>
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">

        <div class="lg:col-span-8">
            <div class="bg-white rounded-3xl shadow-lg shadow-slate-200/50 border border-slate-100 overflow-hidden">
                
                <div class="grid grid-cols-7 border-b border-slate-100 bg-slate-50/50">
                    <?php foreach(['Sen','Sel','Rab','Kam','Jum','Sab','Min'] as $d): ?>
                        <div class="py-4 text-center text-xs font-bold text-slate-400 uppercase tracking-wider">
                            <?= $d ?>
                        </div>
                    <?php endforeach; ?>
                </div>

                <div class="grid grid-cols-7">
                    <?php
                        $daysInMonth = $data['calendar']['days_in_month'];
                        $offset = $data['calendar']['start_day_offset'];
                        $calData = $data['calendar']['data'];
                        
                        // Offset Sel Kosong
                        for($i=0; $i<$offset; $i++) echo '<div class="h-32 bg-slate-50/30 border-b border-r border-slate-50"></div>';

                        // Loop Hari
                        for($day=1; $day<=$daysInMonth; $day++): 
                            $dateKey = sprintf('%s-%02d-%02d', $data['calendar']['year'], $data['calendar']['month'], $day);
                            $item = $calData[$dateKey] ?? null;
                            $isToday = ($dateKey == date('Y-m-d'));
                            
                            // Determine Status Color for Indicator
                            $indicator = '';
                            if(isset($item['is_holiday'])) $indicator = 'bg-red-500';
                            elseif(isset($item['request_type'])) $indicator = 'bg-blue-400';
                            elseif(isset($item['status']) && $item['status'] == 'present') {
                                $indicator = $item['is_late'] ? 'bg-yellow-400' : 'bg-green-500';
                            }
                            elseif(isset($item['shift_name'])) $indicator = 'bg-slate-300';
                    ?>
                        <div onclick="selectDate('<?= $dateKey ?>')" id="cell-<?= $dateKey ?>" 
                             class="h-32 relative border-b border-r border-slate-100 p-2 cursor-pointer transition-all duration-200 hover:bg-indigo-50/50 group">
                            
                            <div class="flex justify-between items-start">
                                <span class="text-sm font-semibold w-7 h-7 flex items-center justify-center rounded-full transition-colors 
                                    <?= $isToday ? 'bg-indigo-600 text-white shadow-md shadow-indigo-200' : 'text-slate-700 group-hover:text-indigo-600' ?>">
                                    <?= $day ?>
                                </span>
                                
                                <?php if(isset($item['status']) && $item['status'] == 'present'): ?>
                                    <i class="ri-checkbox-circle-fill text-emerald-400 text-base"></i>
                                <?php endif; ?>
                            </div>

                            <div class="mt-3 flex flex-col gap-1.5 px-1">
                                <?php if(isset($item['events'])): ?>
                                    <div class="flex items-center gap-1.5">
                                        <div class="w-2 h-2 rounded-full bg-purple-500 animate-pulse"></div>
                                        <span class="text-[10px] font-medium text-purple-600 truncate w-full">
                                            <?= count($item['events']) ?> Agenda
                                        </span>
                                    </div>
                                <?php endif; ?>

                                <?php if(isset($item['is_holiday'])): ?>
                                    <div class="bg-red-50 text-red-600 px-1.5 py-0.5 rounded text-[9px] font-bold truncate border border-red-100">
                                        Libur
                                    </div>
                                <?php elseif(isset($item['shift_code'])): ?>
                                    <div class="bg-slate-100 text-slate-500 px-1.5 py-0.5 rounded text-[9px] font-bold truncate group-hover:bg-white group-hover:shadow-sm transition-all">
                                        <?= $item['shift_code'] ?> 
                                        <span class="font-normal opacity-70"><?= substr($item['shift_start'],0,5) ?></span>
                                    </div>
                                <?php endif; ?>
                            </div>

                            <div class="absolute inset-0 border-2 border-indigo-500 rounded-lg opacity-0 scale-95 pointer-events-none transition-all duration-200" id="ring-<?= $dateKey ?>"></div>
                        </div>
                    <?php endfor; ?>
                </div>
            </div>
            
            <div class="mt-6 flex flex-wrap gap-6 justify-center text-xs font-medium text-slate-500">
                <div class="flex items-center gap-2"><span class="w-2.5 h-2.5 rounded-full bg-green-500"></span> Hadir</div>
                <div class="flex items-center gap-2"><span class="w-2.5 h-2.5 rounded-full bg-yellow-400"></span> Telat</div>
                <div class="flex items-center gap-2"><span class="w-2.5 h-2.5 rounded-full bg-purple-500"></span> Event</div>
                <div class="flex items-center gap-2"><span class="w-2.5 h-2.5 rounded-full bg-red-500"></span> Libur</div>
            </div>
        </div>

        <div class="lg:col-span-4 space-y-6">

            <div class="sticky top-6">
                <div class="bg-white rounded-3xl shadow-xl shadow-slate-200/60 border border-slate-100 p-6 min-h-[400px] relative overflow-hidden">
                    
                    <div class="absolute top-0 right-0 w-40 h-40 bg-indigo-50 rounded-full blur-3xl -mr-10 -mt-10 opacity-60"></div>
                    <div class="absolute bottom-0 left-0 w-32 h-32 bg-purple-50 rounded-full blur-3xl -ml-10 -mb-10 opacity-60"></div>

                    <div class="relative z-10 mb-6">
                        <h4 class="text-xs font-bold text-indigo-500 uppercase tracking-widest mb-1" id="detail-day">HARI INI</h4>
                        <h2 class="text-3xl font-black text-slate-800" id="detail-date">-</h2>
                    </div>

                    <div id="detail-content" class="relative z-10 space-y-5">
                        <div class="animate-pulse space-y-3">
                            <div class="h-20 bg-slate-100 rounded-xl"></div>
                            <div class="h-10 bg-slate-50 rounded-lg"></div>
                        </div>
                    </div>

                </div>

                <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-5 mt-6">
                    <h3 class="font-bold text-slate-800 text-sm mb-4">Statistik Bulan Ini</h3>
                    <div class="grid grid-cols-2 gap-3">
                        <div class="bg-green-50 p-3 rounded-xl border border-green-100 text-center">
                            <div class="text-xl font-black text-green-600"><?= $data['stats']['total_present'] ?></div>
                            <div class="text-[10px] font-bold text-green-700 uppercase">Hadir</div>
                        </div>
                        <div class="bg-yellow-50 p-3 rounded-xl border border-yellow-100 text-center">
                            <div class="text-xl font-black text-yellow-600"><?= $data['stats']['total_late'] ?></div>
                            <div class="text-[10px] font-bold text-yellow-700 uppercase">Telat</div>
                        </div>
                        <div class="bg-red-50 p-3 rounded-xl border border-red-100 text-center">
                            <div class="text-xl font-black text-red-600"><?= $data['stats']['total_alpha'] ?></div>
                            <div class="text-[10px] font-bold text-red-700 uppercase">Alpha</div>
                        </div>
                        <div class="bg-blue-50 p-3 rounded-xl border border-blue-100 text-center">
                            <div class="text-xl font-black text-blue-600"><?= $data['stats']['total_leave'] ?></div>
                            <div class="text-[10px] font-bold text-blue-700 uppercase">Cuti</div>
                        </div>
                    </div>
                </div>
            </div>

        </div> 
    </div>
</div>

<script>
    let activeDate = null;

    function selectDate(dateStr) {
        // 1. Update UI Kalender (Active Ring)
        if (activeDate) {
            const prevRing = document.getElementById('ring-' + activeDate);
            if(prevRing) {
                prevRing.classList.add('opacity-0', 'scale-95');
                prevRing.classList.remove('opacity-100', 'scale-100');
            }
        }
        activeDate = dateStr;
        const nextRing = document.getElementById('ring-' + activeDate);
        if(nextRing) {
            nextRing.classList.remove('opacity-0', 'scale-95');
            nextRing.classList.add('opacity-100', 'scale-100');
        }

        // 2. Ambil Data
        const dateObj = new Date(dateStr);
        const dayName = DAY_NAMES[dateObj.getDay()];
        const fullDate = dateObj.getDate() + ' ' + MONTH_NAMES[dateObj.getMonth()] + ' ' + dateObj.getFullYear();
        const data = CALENDAR_DATA[dateStr] || {};

        // 3. Update Header Sidebar
        document.getElementById('detail-day').innerText = dayName;
        document.getElementById('detail-date').innerText = fullDate;

        // 4. Render Konten Detail
        const container = document.getElementById('detail-content');
        let html = '';

        // --- SECTION A: EVENT / AGENDA ---
        if (data.events && data.events.length > 0) {
            html += `<div class="mb-4">`;
            data.events.forEach(ev => {
                let badgeColor = 'bg-indigo-100 text-indigo-700 border-indigo-200';
                if(ev.type === 'danger') badgeColor = 'bg-red-100 text-red-700 border-red-200';
                if(ev.type === 'warning') badgeColor = 'bg-orange-100 text-orange-700 border-orange-200';

                html += `
                <div class="bg-white rounded-xl p-4 border border-slate-100 shadow-sm mb-3 relative overflow-hidden group hover:border-indigo-300 transition-colors">
                    <div class="absolute left-0 top-0 bottom-0 w-1 bg-indigo-500"></div>
                    <div class="flex justify-between items-start mb-1">
                        <span class="text-[10px] font-bold px-2 py-0.5 rounded ${badgeColor} border uppercase tracking-wide">
                            Agenda
                        </span>
                        <span class="text-xs font-mono font-bold text-slate-500">${ev.time.substring(0,5)}</span>
                    </div>
                    <h4 class="font-bold text-slate-800 leading-tight">${ev.title}</h4>
                    ${ev.location ? `<p class="text-xs text-slate-500 mt-1 flex items-center gap-1"><i class="ri-map-pin-line"></i> ${ev.location}</p>` : ''}
                </div>`;
            });
            html += `</div>`;
        }

        // --- SECTION B: LIBUR ---
        if (data.is_holiday) {
            html += `
            <div class="bg-red-50 rounded-xl p-5 border border-red-100 text-center">
                <i class="ri-hotel-bed-fill text-3xl text-red-300 mb-2 block"></i>
                <h4 class="font-bold text-red-700">Hari Libur</h4>
                <p class="text-sm text-red-500">${data.holiday_name}</p>
            </div>`;
        } 
        // --- SECTION C: CUTI / IZIN ---
        else if (data.request_type) {
            const label = data.request_type === 'leave' ? 'Cuti' : 'Dinas Luar';
            html += `
            <div class="bg-blue-50 rounded-xl p-5 border border-blue-100 text-center">
                <i class="ri-flight-takeoff-line text-3xl text-blue-300 mb-2 block"></i>
                <h4 class="font-bold text-blue-700">${label}</h4>
                <p class="text-sm text-blue-500 italic">"${data.request_note || '-'}"</p>
            </div>`;
        }
        // --- SECTION D: SHIFT & ABSENSI ---
        else {
            // Shift Info
            if (data.shift_name) {
                html += `
                <div class="flex items-center gap-4 p-4 rounded-xl bg-slate-50 border border-slate-100">
                    <div class="w-12 h-12 rounded-lg bg-white flex items-center justify-center text-xl shadow-sm">
                        📅
                    </div>
                    <div>
                        <p class="text-xs text-slate-400 font-bold uppercase tracking-wider">Jadwal Shift</p>
                        <p class="font-bold text-slate-700">${data.shift_name}</p>
                        <p class="text-xs font-mono text-slate-500">${data.shift_start.substring(0,5)} - ${data.shift_end.substring(0,5)}</p>
                    </div>
                </div>`;
            } else {
                html += `<div class="text-center text-slate-400 text-sm py-4 italic">Tidak ada jadwal shift.</div>`;
            }

            // Absensi Info (Jika sudah absen)
            if (data.status === 'present') {
                const lateBadge = data.is_late 
                    ? `<span class="bg-yellow-100 text-yellow-700 px-2 py-0.5 rounded text-[10px] font-bold border border-yellow-200">TERLAMBAT</span>`
                    : `<span class="bg-green-100 text-green-700 px-2 py-0.5 rounded text-[10px] font-bold border border-green-200">TEPAT WAKTU</span>`;

                html += `
                <div class="mt-4 border-t border-dashed border-slate-200 pt-4">
                    <div class="flex justify-between items-center mb-3">
                        <h4 class="font-bold text-slate-700 text-sm">Data Kehadiran</h4>
                        ${lateBadge}
                    </div>
                    <div class="grid grid-cols-2 gap-3">
                        <div class="bg-white p-3 rounded-lg border border-slate-100 shadow-sm">
                            <span class="text-[10px] text-slate-400 font-bold uppercase block mb-1">Masuk</span>
                            <span class="text-lg font-mono font-bold text-indigo-600">${data.clock_in ? data.clock_in.substring(0,5) : '--:--'}</span>
                        </div>
                        <div class="bg-white p-3 rounded-lg border border-slate-100 shadow-sm">
                            <span class="text-[10px] text-slate-400 font-bold uppercase block mb-1">Pulang</span>
                            <span class="text-lg font-mono font-bold text-slate-600">${data.clock_out ? data.clock_out.substring(0,5) : '--:--'}</span>
                        </div>
                    </div>
                </div>`;
            } else if (!data.is_holiday && new Date(dateStr) < new Date().setHours(0,0,0,0)) {
                // Jika hari sudah lewat dan tidak absen
                html += `
                <div class="mt-4 p-3 bg-red-50 border border-red-100 rounded-lg flex items-center gap-3">
                    <i class="ri-error-warning-fill text-red-500 text-xl"></i>
                    <div>
                        <p class="font-bold text-red-700 text-sm">Tidak Masuk</p>
                        <p class="text-xs text-red-500">Anda tidak melakukan absensi.</p>
                    </div>
                </div>`;
            }
        }

        container.innerHTML = html;
    }

    // Auto Select Hari Ini saat Load
    document.addEventListener("DOMContentLoaded", () => {
        selectDate('<?= date('Y-m-d') ?>');
    });
</script>