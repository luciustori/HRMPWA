<div class="max-w-7xl mx-auto px-6 py-8">

    <div class="flex flex-col md:flex-row justify-between items-end mb-8 gap-4">
        <div>
            <div class="flex items-center gap-2 mb-1">
                <a href="<?= BASEURL ?>/admin/kpi" class="text-slate-400 hover:text-slate-600 transition"><i class="ri-arrow-left-line"></i> Dashboard</a>
            </div>
            <h1 class="text-2xl font-bold text-slate-800">Laporan Kinerja Karyawan</h1>
            <p class="text-slate-500 text-sm">
                Rekapitulasi performa periode: 
                <span class="font-semibold text-indigo-600">
                    25 <?= date('M', mktime(0, 0, 0, $data['filter_month']-1, 10)) ?> - 
                    24 <?= date('M Y', mktime(0, 0, 0, $data['filter_month'], 10, $data['filter_year'])) ?>
                </span>
            </p>
        </div>

        <form class="flex items-center gap-3 bg-white p-2 rounded-xl border border-slate-200 shadow-sm">
            
            <select name="month" onchange="this.form.submit()" class="bg-slate-50 border-none text-sm font-bold text-slate-700 rounded-lg focus:ring-0 cursor-pointer py-2 pl-3 pr-8">
                <?php 
                $months = [1=>'Januari', 2=>'Februari', 3=>'Maret', 4=>'April', 5=>'Mei', 6=>'Juni', 7=>'Juli', 8=>'Agustus', 9=>'September', 10=>'Oktober', 11=>'November', 12=>'Desember'];
                foreach($months as $num => $name): 
                ?>
                    <option value="<?= $num ?>" <?= $data['filter_month'] == $num ? 'selected' : '' ?>><?= $name ?></option>
                <?php endforeach; ?>
            </select>

            <div class="w-px h-6 bg-slate-200"></div>

            <select name="year" onchange="this.form.submit()" class="bg-slate-50 border-none text-sm font-bold text-slate-700 rounded-lg focus:ring-0 cursor-pointer py-2 pl-3 pr-8">
                <?php for($y=date('Y'); $y>=2023; $y--): ?>
                    <option value="<?= $y ?>" <?= $data['filter_year'] == $y ? 'selected' : '' ?>><?= $y ?></option>
                <?php endfor; ?>
            </select>

            <div class="w-px h-6 bg-slate-200"></div>

            <select name="dept_id" onchange="this.form.submit()" class="bg-transparent border-none text-sm text-slate-600 font-medium focus:ring-0 cursor-pointer py-2 pl-2 pr-8 min-w-[150px]">
                <option value="all">Semua Departemen</option>
                <?php foreach($data['departments'] as $dept): ?>
                    <option value="<?= $dept['id'] ?>" <?= $data['filter_dept'] == $dept['id'] ? 'selected' : '' ?>>
                        <?= $dept['department_name'] ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </form>

        <button onclick="window.print()" class="bg-indigo-600 text-white px-4 py-2.5 rounded-xl text-sm font-bold shadow hover:bg-indigo-700 transition flex items-center gap-2">
            <i class="ri-printer-line"></i> Cetak / PDF
        </button>
    </div>

    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50 text-slate-500 text-xs uppercase tracking-wider border-b border-slate-200">
                        <th class="px-6 py-4 font-bold text-center w-16">#</th>
                        <th class="px-6 py-4 font-bold">Karyawan</th>
                        <th class="px-6 py-4 font-bold">Departemen</th>
                        <th class="px-6 py-4 font-bold text-center">Total Tugas</th>
                        <th class="px-6 py-4 font-bold text-center">Final Score</th>
                        <th class="px-6 py-4 font-bold text-center">Grade</th>
                        <th class="px-6 py-4 font-bold text-center w-20">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    <?php if(empty($data['employees'])): ?>
                        <tr><td colspan="7" class="text-center py-12 text-slate-400">
                            Data KPI belum digenerate untuk periode ini.<br>
                            <a href="<?= BASEURL ?>/admin/kpi" class="text-indigo-600 hover:underline">Ke Dashboard untuk Generate</a>
                        </td></tr>
                    <?php else: ?>
                        <?php foreach($data['employees'] as $idx => $emp): ?>
                            <?php 
                                // Kalkulasi Grade (Sekarang ambil dari kpi_score summary)
                                $score = $emp['kpi_score'] ?? 0; // REVISI: Pakai kolom kpi_score
                                $grade = '-';
                                $gradeClass = 'bg-slate-100 text-slate-500';

                                if ($score >= 90) { $grade = 'A+'; $gradeClass = 'bg-emerald-100 text-emerald-700 border border-emerald-200'; }
                                elseif ($score >= 80) { $grade = 'A'; $gradeClass = 'bg-green-100 text-green-700 border border-green-200'; }
                                elseif ($score >= 70) { $grade = 'B'; $gradeClass = 'bg-blue-100 text-blue-700 border border-blue-200'; }
                                elseif ($score >= 60) { $grade = 'C'; $gradeClass = 'bg-amber-100 text-amber-700 border border-amber-200'; }
                                elseif ($score > 0) { $grade = 'D'; $gradeClass = 'bg-rose-100 text-rose-700 border border-rose-200'; }
                            ?>
                        <tr class="hover:bg-slate-50 transition">
                            <td class="px-6 py-4 text-center font-mono text-slate-400 font-bold"><?= $idx + 1 ?></td>
                            
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded bg-indigo-50 text-indigo-600 flex items-center justify-center font-bold text-xs">
                                        <?= substr($emp['first_name'], 0, 1) ?>
                                    </div>
                                    <div>
                                        <div class="font-bold text-slate-700 text-sm"><?= $emp['first_name'] . ' ' . $emp['last_name'] ?></div>
                                        <div class="text-[10px] font-mono text-slate-400"><?= $emp['employee_number'] ?></div>
                                    </div>
                                </div>
                            </td>

                            <td class="px-6 py-4 text-sm text-slate-600">
                                <?= $emp['department_name'] ?? '-' ?>
                            </td>

                            <td class="px-6 py-4 text-center">
                                <span class="font-mono font-bold text-slate-700"><?= $emp['total_done'] ?></span>
                                <span class="text-xs text-slate-400">/ <?= $emp['total_tasks'] ?></span>
                            </td>

                            <td class="px-6 py-4 text-center">
                                <span class="font-bold text-slate-800"><?= number_format($score, 1) ?></span>
                            </td>

                            <td class="px-6 py-4 text-center">
                                <span class="inline-block w-8 h-8 leading-8 rounded-full text-xs font-bold text-center <?= $gradeClass ?>">
                                    <?= $grade ?>
                                </span>
                            </td>

                            <td class="px-6 py-4 text-center">
                                <a href="<?= BASEURL ?>/admin/kpi/detail/<?= $emp['id'] ?>?month=<?= $data['filter_month'] ?>&year=<?= $data['filter_year'] ?>" class="text-slate-400 hover:text-indigo-600 transition" title="Lihat Detail">
                                    <i class="ri-file-list-3-line text-lg"></i>
                                </a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
        
        <div class="bg-slate-50 px-6 py-3 border-t border-slate-200 flex justify-between items-center text-xs text-slate-500">
            <span>Menampilkan <?= count($data['employees']) ?> data</span>
        </div>
    </div>
</div>