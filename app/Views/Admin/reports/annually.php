<style>
@media print {
    @page { size: landscape; margin: 10mm; }
    body, main { background-color: #ffffff !important; margin: 0 !important; padding: 0 !important; }
    aside, nav.sticky { display: none !important; }
    .max-w-full { max-width: 100% !important; padding: 0 !important; }
    .overflow-x-auto { overflow: visible !important; }
    .shadow-sm, .shadow-md, .shadow-xl { box-shadow: none !important; }
    .border { border-color: #e5e7eb !important; }
    * { -webkit-print-color-adjust: exact !important; print-color-adjust: exact !important; }
    table { page-break-inside: auto; width: 100% !important; }
    tr { page-break-inside: avoid; page-break-after: auto; }
    thead { display: table-header-group; }
}
</style>

<div class="max-w-full mx-auto px-4 py-6" x-data="{ expandedRow: null }">
    
    <div class="flex gap-2 mb-6 border-b border-gray-200 pb-2 print:hidden">
        <a href="<?= BASEURL ?>/Admin/Reports/monthly" class="px-4 py-2 text-sm font-bold rounded-t-lg text-gray-500 hover:bg-gray-50 transition">
            <i class="ri-calendar-event-line mr-1"></i> Bulanan (Payroll)
        </a>
        <a href="<?= BASEURL ?>/Admin/Reports/annually" class="px-4 py-2 text-sm font-bold rounded-t-lg bg-indigo-50 text-indigo-700 border-b-2 border-indigo-600 transition">
            <i class="ri-bar-chart-box-line mr-1"></i> Tahunan (Detail 12 Bulan)
        </a>
    </div>

    <div class="flex flex-col md:flex-row justify-between items-center mb-6 gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Rekapitulasi Kinerja Tahunan</h1>
            <p class="text-sm text-gray-500 print:hidden">
                Klik nama karyawan untuk melihat detail 12 Bulan di Tahun: <span class="font-bold text-indigo-600 bg-indigo-50 px-2 py-1 rounded"><?= $year ?></span>
            </p>
            <p class="text-sm text-gray-800 hidden print:block font-bold">Periode Tahun: <?= $year ?></p>
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
            
            <select name="year" class="text-sm border-none focus:ring-0 text-indigo-700 bg-transparent font-bold cursor-pointer" onchange="this.form.submit()">
                <?php 
                $currentYear = date('Y');
                for($i = $currentYear + 1; $i >= $currentYear - 3; $i--): ?>
                    <option value="<?= $i ?>" <?= $year == $i ? 'selected' : '' ?>><?= $i ?></option>
                <?php endfor; ?>
            </select>
            
            <button type="button" onclick="window.open('<?= BASEURL ?>/Admin/Reports/annually?dept_id=<?= $selected_dept ?>&year=<?= $year ?>&export=print', '_blank')" class="ml-2 p-2 text-gray-500 hover:text-indigo-600" title="Print Laporan">
    <i class="ri-printer-line text-lg"></i>
</button>
        </form>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden print:border-none">
        <div class="overflow-x-auto">
            <table class="w-full text-xs text-left print:text-[10px]">
                <thead class="bg-gray-800 text-white font-bold uppercase text-[10px] tracking-wider print:bg-gray-200 print:text-gray-800">
                    <tr>
                        <th class="px-4 py-4 sticky left-0 bg-gray-800 z-10 w-64 border-r border-gray-700 print:static print:bg-transparent print:border-gray-300 print:w-auto">Karyawan</th>
                        <th class="px-2 py-4 text-center border-r border-gray-700 print:border-gray-300">Jadwal</th>
                        <th class="px-2 py-4 text-center border-r border-gray-700 text-emerald-400 print:text-emerald-700 print:border-gray-300">Hadir</th>
                        <th class="px-2 py-4 text-center border-r border-gray-700 text-orange-400 print:text-orange-700 print:border-gray-300">Telat</th>
                        <th class="px-2 py-4 text-center border-r border-gray-700 text-yellow-300 print:text-yellow-700 print:border-gray-300">Plg Awal</th>
                        <th class="px-2 py-4 text-center border-r border-gray-700 text-emerald-300 print:text-emerald-700 print:border-gray-300">Cuti</th>
                        <th class="px-2 py-4 text-center border-r border-gray-700 text-orange-500 print:text-orange-700 print:border-gray-300">Ijin</th>
                        <th class="px-2 py-4 text-center border-r border-gray-700 text-blue-400 print:text-blue-700 print:border-gray-300">Dinas</th>
                        <th class="px-2 py-4 text-center border-r border-gray-700 text-purple-400 print:text-purple-700 print:border-gray-300">SPPD</th>
                        <th class="px-2 py-4 text-center border-r border-gray-700 text-red-400 print:text-red-700 print:border-gray-300">Alpha</th>
                        <th class="px-2 py-4 text-center print:border-gray-300">% Rate</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    <?php if(empty($reports)): ?>
                        <tr><td colspan="11" class="p-8 text-center text-gray-400 text-sm">Belum ada data rekap untuk tahun ini.</td></tr>
                    <?php else: ?>
                        
                        <?php foreach($reports as $eid => $emp): 
                            $tot = $emp['totals'];
                            $rate_tot = $tot['jadwal'] > 0 ? round(($tot['hadir'] / $tot['jadwal']) * 100) : 0;
                            $rate_color = $rate_tot >= 95 ? 'text-emerald-600' : ($rate_tot >= 80 ? 'text-yellow-600' : 'text-red-600');
                        ?>
                        
                        <tr class="cursor-pointer bg-white hover:bg-indigo-50 transition group" 
                            @click="expandedRow === <?= $eid ?> ? expandedRow = null : expandedRow = <?= $eid ?>">
                            
                            <td class="px-4 py-3 font-medium text-gray-900 sticky left-0 bg-white group-hover:bg-indigo-50 z-10 border-r border-gray-100 print:static print:w-auto flex justify-between items-center">
                                <div>
                                    <div class="truncate w-48 font-bold text-sm group-hover:text-indigo-700 transition print:w-auto print:whitespace-normal" title="<?= $emp['info']['first_name'] ?> <?= $emp['info']['last_name'] ?>">
                                        <?= $emp['info']['first_name'] ?> <?= $emp['info']['last_name'] ?>
                                    </div>
                                    <div class="text-[10px] text-gray-500 truncate mt-0.5 print:whitespace-normal"><?= $emp['info']['department_name'] ?></div>
                                </div>
                                <i class="fas text-gray-400 print:hidden" :class="expandedRow === <?= $eid ?> ? 'fa-chevron-up text-indigo-600' : 'fa-chevron-down'"></i>
                            </td>

                            <td class="px-2 py-3 text-center border-r border-gray-100 font-bold text-indigo-600"><?= $tot['jadwal'] ?>h</td>
                            <td class="px-2 py-3 text-center border-r border-gray-100 font-bold text-emerald-600"><?= $tot['hadir'] ?></td>
                            
                            <td class="px-2 py-3 text-center border-r border-gray-100">
                                <?= $tot['telat'] > 0 ? '<span class="font-bold text-orange-500">'.$tot['telat'].'</span>' : '<span class="text-gray-300">-</span>' ?>
                            </td>
                            <td class="px-2 py-3 text-center border-r border-gray-100">
                                <?= $tot['plg_awal'] > 0 ? '<span class="font-bold text-yellow-500">'.$tot['plg_awal'].'</span>' : '<span class="text-gray-300">-</span>' ?>
                            </td>
                            <td class="px-2 py-3 text-center border-r border-gray-100 bg-emerald-50/20">
                                <?= $tot['cuti'] > 0 ? '<span class="font-bold text-emerald-600">'.$tot['cuti'].'</span>' : '<span class="text-gray-300">-</span>' ?>
                            </td>
                            <td class="px-2 py-3 text-center border-r border-gray-100">
                                <?= $tot['ijin'] > 0 ? '<span class="font-bold text-orange-500">'.$tot['ijin'].'</span>' : '<span class="text-gray-300">-</span>' ?>
                            </td>
                            <td class="px-2 py-3 text-center border-r border-gray-100">
                                <?= $tot['dl'] > 0 ? '<span class="font-bold text-blue-600">'.$tot['dl'].'</span>' : '<span class="text-gray-300">-</span>' ?>
                            </td>
                            <td class="px-2 py-3 text-center border-r border-gray-100">
                                <?= $tot['sppd'] > 0 ? '<span class="font-bold text-purple-600">'.$tot['sppd'].'</span>' : '<span class="text-gray-300">-</span>' ?>
                            </td>
                            <td class="px-2 py-3 text-center border-r border-gray-100 bg-red-50/50">
                                <?= $tot['alpha'] > 0 ? '<span class="font-bold text-red-600">'.$tot['alpha'].'</span>' : '<span class="text-gray-300">-</span>' ?>
                            </td>
                            <td class="px-2 py-3 text-center font-bold <?= $rate_color ?> bg-gray-50/50 text-sm">
                                <?= $rate_tot ?>%
                            </td>
                        </tr>

                        <tr x-show="expandedRow === <?= $eid ?>" x-transition class="bg-gray-50 print:break-inside-avoid" style="display: none;">
                            <td colspan="11" class="p-0 border-b-4 border-indigo-600 shadow-inner print:border-b print:border-gray-300">
                                <div class="pl-8 pr-4 py-4 overflow-x-auto">
                                    <table class="w-full text-[11px] text-center border bg-white rounded-lg shadow-sm">
                                        <thead class="bg-indigo-50/50 text-gray-500 font-bold uppercase text-[9px] tracking-wider">
                                            <tr>
                                                <th class="px-2 py-2 text-left w-24">Bulan</th>
                                                <th class="px-2 py-2">Jadwal (Hari)</th>
                                                <th class="px-2 py-2 text-emerald-600">Hadir</th>
                                                <th class="px-2 py-2">Telat</th>
                                                <th class="px-2 py-2">Plg Awal</th>
                                                <th class="px-2 py-2 text-orange-600">Lembur</th>
                                                <th class="px-2 py-2 text-emerald-600">Cuti</th>
                                                <th class="px-2 py-2 text-orange-500">Ijin</th>
                                                <th class="px-2 py-2 text-blue-600">Dinas</th>
                                                <th class="px-2 py-2 text-purple-600">SPPD</th>
                                                <th class="px-2 py-2 text-red-600">Alpha</th>
                                                <th class="px-2 py-2">% Hadir</th>
                                            </tr>
                                        </thead>
                                        <tbody class="divide-y divide-gray-100">
                                            <?php 
                                            $bulan_indo = ['1'=>'Januari', '2'=>'Februari', '3'=>'Maret', '4'=>'April', '5'=>'Mei', '6'=>'Juni', '7'=>'Juli', '8'=>'Agustus', '9'=>'September', '10'=>'Oktober', '11'=>'November', '12'=>'Desember'];
                                            
                                            for($m=1; $m<=12; $m++): 
                                                $m_data = $emp['months'][$m];
                                                $m_pct = $m_data['jadwal'] > 0 ? round(($m_data['hadir'] / $m_data['jadwal']) * 100) : 0;
                                                $m_color = $m_pct >= 95 ? 'text-emerald-600' : ($m_pct >= 80 ? 'text-yellow-600' : 'text-red-600');
                                                $is_current = ($m == date('n') && $year == date('Y')) ? 'bg-indigo-50/30' : '';
                                            ?>
                                            <tr class="hover:bg-gray-50 transition <?= $is_current ?>">
                                                <td class="px-2 py-2 text-left font-bold text-gray-700"><?= $bulan_indo[$m] ?></td>
                                                
                                                <td class="px-2 py-2 font-bold text-indigo-600"><?= $m_data['jadwal'] > 0 ? $m_data['jadwal'] : '-' ?></td>
                                                
                                                <td class="px-2 py-2 font-bold text-emerald-600"><?= $m_data['hadir'] > 0 ? $m_data['hadir'] : '-' ?></td>
                                                
                                                <td class="px-2 py-2"><?= $m_data['telat'] > 0 ? '<span class="font-bold text-orange-500">'.$m_data['telat'].'</span>' : '<span class="text-gray-300">-</span>' ?></td>
                                                
                                                <td class="px-2 py-2"><?= $m_data['plg_awal'] > 0 ? '<span class="font-bold text-yellow-500">'.$m_data['plg_awal'].'</span>' : '<span class="text-gray-300">-</span>' ?></td>
                                                
                                                <td class="px-2 py-2"><?= $m_data['lembur'] > 0 ? '<span class="font-bold text-orange-600">'.$m_data['lembur'].'</span>' : '<span class="text-orange-200">-</span>' ?></td>
                                                
                                                <td class="px-2 py-2 bg-emerald-50/10"><?= $m_data['cuti'] > 0 ? '<span class="font-bold text-emerald-600">'.$m_data['cuti'].'</span>' : '<span class="text-gray-300">-</span>' ?></td>
                                                
                                                <td class="px-2 py-2"><?= $m_data['ijin'] > 0 ? '<span class="font-bold text-orange-500">'.$m_data['ijin'].'</span>' : '<span class="text-gray-300">-</span>' ?></td>
                                                
                                                <td class="px-2 py-2"><?= $m_data['dl'] > 0 ? '<span class="font-bold text-blue-600">'.$m_data['dl'].'</span>' : '<span class="text-gray-300">-</span>' ?></td>
                                                
                                                <td class="px-2 py-2"><?= $m_data['sppd'] > 0 ? '<span class="font-bold text-purple-600">'.$m_data['sppd'].'</span>' : '<span class="text-gray-300">-</span>' ?></td>
                                                
                                                <td class="px-2 py-2 bg-red-50/30"><?= $m_data['alpha'] > 0 ? '<span class="font-bold text-red-600">'.$m_data['alpha'].'</span>' : '<span class="text-gray-300">-</span>' ?></td>
                                                
                                                <td class="px-2 py-2 font-bold <?= $m_color ?>"><?= $m_data['jadwal'] > 0 ? $m_pct.'%' : '-' ?></td>
                                            </tr>
                                            <?php endfor; ?>
                                        </tbody>
                                    </table>
                                </div>
                            </td>
                        </tr>

                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
    
    <div class="mt-4 text-[10px] text-gray-400 italic flex justify-between">
        <span>* Data Lembur, Cuti, Ijin, Dinas, dan SPPD dihitung berdasarkan data yang telah berstatus <strong>DISETUJUI</strong>.</span>
        <span>Generated at: <?= date('d M Y H:i') ?></span>
    </div>
</div>