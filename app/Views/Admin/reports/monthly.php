<style>
@media print {
    @page { size: landscape; margin: 10mm; }
    body, main { background-color: #ffffff !important; margin: 0 !important; padding: 0 !important; }
    /* Sembunyikan Sidebar & Top Navbar dari admin-layout */
    aside, nav.sticky { display: none !important; }
    /* Paksa lebar konten 100% dan hilangkan scrollbar */
    .max-w-full { max-width: 100% !important; padding: 0 !important; }
    .overflow-x-auto { overflow: visible !important; }
    /* Hilangkan shadow agar tinta lebih hemat dan rapi */
    .shadow-sm, .shadow-md, .shadow-xl { box-shadow: none !important; }
    .border { border-color: #e5e7eb !important; }
    /* Paksa warna background tabel tetap tercetak */
    * { -webkit-print-color-adjust: exact !important; print-color-adjust: exact !important; }
    /* Cegah baris tabel terpotong di antara dua halaman */
    table { page-break-inside: auto; width: 100% !important; }
    tr { page-break-inside: avoid; page-break-after: auto; }
    thead { display: table-header-group; }
}
</style>

<div class="max-w-full mx-auto px-4 py-6">
    
    <div class="flex gap-2 mb-6 border-b border-gray-200 pb-2 print:hidden">
        <a href="<?= BASEURL ?>/Admin/Reports/monthly" class="px-4 py-2 text-sm font-bold rounded-t-lg bg-indigo-50 text-indigo-700 border-b-2 border-indigo-600 transition">
            <i class="ri-calendar-event-line mr-1"></i> Bulanan (Payroll)
        </a>
        <a href="<?= BASEURL ?>/Admin/Reports/annually" class="px-4 py-2 text-sm font-bold rounded-t-lg text-gray-500 hover:bg-gray-50 transition">
            <i class="ri-bar-chart-box-line mr-1"></i> Tahunan (Detail 12 Bulan)
        </a>
    </div>

    <div class="flex flex-col md:flex-row justify-between items-center mb-6 gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Laporan Rekapitulasi (Cut-Off)</h1>
            <p class="text-sm text-gray-500">
                Periode Payroll: <span class="font-bold text-indigo-600 bg-indigo-50 px-2 py-1 rounded print:bg-transparent print:px-0"><?= $period_text ?></span>
            </p>
        </div>
        
        <form method="GET" class="flex flex-wrap items-center gap-2 bg-white p-2 rounded-lg shadow-sm border border-gray-200 print:hidden">
            <select name="dept_id" class="text-sm border-none focus:ring-0 text-gray-600 bg-transparent font-medium cursor-pointer" onchange="this.form.submit()">
                <option value="all">Semua Departemen</option>
                <?php foreach($departments as $d): ?>
                    <option value="<?= $d['id'] ?>" <?= $selected_dept == $d['id'] ? 'selected' : '' ?>>
                        <?= $d['department_name'] ?>
                    </option>
                <?php endforeach; ?>
            </select>
            
            <div class="h-6 w-px bg-gray-300"></div>
            
            <input type="month" name="month_year" value="<?= $month_year ?>" 
                   onchange="this.form.submit()"
                   class="text-sm border-none focus:ring-0 text-gray-600 bg-transparent font-bold cursor-pointer">
            
            <button type="button" onclick="window.open('<?= BASEURL ?>/Admin/Reports/monthly?dept_id=<?= $selected_dept ?>&month_year=<?= $month_year ?>&export=print', '_blank')" class="ml-2 p-2 text-gray-500 hover:text-indigo-600" title="Print Laporan">
    <i class="ri-printer-line text-lg"></i>
</button>
        </form>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden print:border-none">
        <div class="overflow-x-auto">
            <table class="w-full text-[11px] text-left print:text-[10px]">
                <thead class="bg-gray-50 text-gray-600 font-bold uppercase tracking-wider border-b border-gray-200">
                    <tr>
                        <th class="px-4 py-3 sticky left-0 bg-gray-50 z-10 w-56 border-r border-gray-200 print:static print:w-auto">Karyawan</th>
                        <th class="px-2 py-3 text-center bg-indigo-50 text-indigo-700 border-r border-indigo-100">MOD</th>
                        <th class="px-2 py-3 text-center">Hadir</th>
                        <th class="px-2 py-3 text-center text-red-600">Telat</th>
                        <th class="px-2 py-3 text-center text-yellow-600">Plg Awal</th>
                        <th class="px-2 py-3 text-center text-orange-600">Lembur</th>
                        <th class="px-2 py-3 text-center text-emerald-600 bg-emerald-50/30">Cuti</th>
                        <th class="px-2 py-3 text-center text-orange-500">Ijin</th>
                        <th class="px-2 py-3 text-center text-blue-600">Dinas</th>
                        <th class="px-2 py-3 text-center text-purple-600">SPPD</th>
                        <th class="px-2 py-3 text-center text-gray-800 bg-red-50 border-r border-gray-200">Alpha</th>
                        <th class="px-3 py-3 text-center border-r border-gray-200">Jam Kerja</th>
                        <th class="px-2 py-3 text-center">% Rate</th>
                        <th class="px-2 py-3 text-center print:hidden">Aksi</th> </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    <?php if(empty($reports)): ?>
                        <tr><td colspan="14" class="p-8 text-center text-gray-400 text-sm">Belum ada data rekap untuk periode ini.</td></tr>
                    <?php else: ?>
                        <?php foreach($reports as $row): ?>
                        <tr class="hover:bg-gray-50 transition-colors group">
                            
                            <td class="px-4 py-3 font-medium text-gray-900 sticky left-0 bg-white group-hover:bg-gray-50 z-10 border-r border-gray-100 print:static">
                                <div class="truncate w-48 text-xs font-bold print:w-auto print:whitespace-normal" title="<?= $row['full_name'] ?>"><?= $row['full_name'] ?></div>
                                <div class="text-[9px] text-gray-500 truncate mt-0.5 print:whitespace-normal"><?= $row['department_name'] ?></div>
                            </td>

                            <td class="px-2 py-3 text-center bg-indigo-50/30 group-hover:bg-indigo-50/60 font-bold text-indigo-700 border-r border-indigo-50">
                                <?= $row['total_mod'] > 0 ? $row['total_mod'] : '-' ?>
                            </td>

                            <td class="px-2 py-3 text-center font-bold text-gray-700"><?= $row['present_days'] ?></td>

                            <td class="px-2 py-3 text-center">
                                <?php if($row['late_days'] > 0): ?>
                                    <span class="text-red-600 font-bold"><?= $row['late_days'] ?></span>
                                    <div class="text-[8px] text-red-400 leading-tight"><?= $row['total_late_minutes'] ?>m</div>
                                <?php else: ?>
                                    <span class="text-gray-300">-</span>
                                <?php endif; ?>
                            </td>

                            <td class="px-2 py-3 text-center">
                                <?= $row['early_out_days'] > 0 ? '<span class="text-yellow-600 font-bold">'.$row['early_out_days'].'</span>' : '<span class="text-gray-300">-</span>' ?>
                            </td>

                            <td class="px-2 py-3 text-center">
                                <?= $row['total_lembur_requests'] > 0 ? '<span class="text-orange-600 font-bold">'.$row['total_lembur_requests'].'</span>' : '<span class="text-gray-300">-</span>' ?>
                            </td>

                            <td class="px-2 py-3 text-center bg-emerald-50/10">
                                <?= $row['total_cuti'] > 0 ? '<span class="text-emerald-600 font-bold">'.$row['total_cuti'].'</span>' : '<span class="text-gray-300">-</span>' ?>
                            </td>

                            <td class="px-2 py-3 text-center">
                                <?= $row['total_ijin_sakit'] > 0 ? '<span class="text-orange-500 font-bold">'.$row['total_ijin_sakit'].'</span>' : '<span class="text-gray-300">-</span>' ?>
                            </td>

                            <td class="px-2 py-3 text-center">
                                <?= $row['total_dl'] > 0 ? '<span class="text-blue-600 font-bold">'.$row['total_dl'].'</span>' : '<span class="text-gray-300">-</span>' ?>
                            </td>

                            <td class="px-2 py-3 text-center">
                                <?= $row['total_sppd'] > 0 ? '<span class="text-purple-600 font-bold">'.$row['total_sppd'].'</span>' : '<span class="text-gray-300">-</span>' ?>
                            </td>

                            <td class="px-2 py-3 text-center bg-red-50/50 border-r border-gray-100">
                                <?= $row['total_absent'] > 0 ? '<span class="text-red-700 font-black">'.$row['total_absent'].'</span>' : '<span class="text-gray-300">-</span>' ?>
                            </td>

                            <td class="px-3 py-3 text-center font-mono text-xs border-r border-gray-100">
                                <div><?= number_format($row['total_work_minutes'] / 60, 1) ?>h</div>
                                <?php if($row['total_overtime_minutes'] > 0): ?>
                                    <div class="text-[9px] text-emerald-600 font-bold">+<?= number_format($row['total_overtime_minutes'] / 60, 1) ?>h OT</div>
                                <?php endif; ?>
                            </td>

                            <td class="px-2 py-3 text-center">
                                <?php 
                                    $total_days = $row['total_days'];
                                    if ($total_days > 0) {
                                        $rate = round(($row['present_days'] / $total_days) * 100);
                                    } else {
                                        $rate = $row['present_days'] > 0 ? 100 : 0;
                                    }
                                    if ($rate > 100) $rate = 100;
                                    $color = $rate >= 95 ? 'text-emerald-600' : ($rate >= 80 ? 'text-yellow-600' : 'text-red-600');
                                ?>
                                <span class="font-bold text-xs <?= $color ?>"><?= $rate ?>%</span>
                            </td>

                            <td class="px-2 py-3 text-center print:hidden"> <a href="<?= BASEURL ?>/admin/attendance?employee_id=<?= $row['employee_id'] ?>&start=<?= $start_date ?>&end=<?= $end_date ?>" 
                                   class="text-indigo-600 hover:text-indigo-800 p-1.5 hover:bg-indigo-50 rounded transition" 
                                   title="Lihat Detail Harian">
                                    <i class="ri-file-list-3-line text-lg"></i>
                                </a>
                            </td>

                        </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
    
    <div class="mt-4 text-[10px] text-gray-400 italic flex justify-between">
        <span>* Data Pengajuan (Lembur, Cuti, Ijin, Dinas, SPPD) hanya menghitung yang berstatus <strong>DISETUJUI / APPROVED</strong>.</span>
        <span>Generated at: <?= date('d M Y H:i') ?></span>
    </div>
</div>