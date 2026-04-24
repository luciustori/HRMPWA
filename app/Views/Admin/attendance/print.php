<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Absensi - <?= $data['period_info'] ?></title>
    
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    
    <style>
        @media print {
            body { -webkit-print-color-adjust: exact; print-color-adjust: exact; background: white !important; }
            .no-print { display: none !important; }
            .sheet { box-shadow: none !important; border: none !important; margin: 0 !important; width: 100% !important; padding: 0 !important; }
            
            /* Paksa warna background badge saat print */
            .bg-emerald-100 { background-color: #d1fae5 !important; }
            .bg-red-100 { background-color: #fee2e2 !important; }
            .bg-yellow-100 { background-color: #fef9c3 !important; }
            .bg-orange-100 { background-color: #ffedd5 !important; }
            .bg-blue-100 { background-color: #dbeafe !important; }
            .bg-purple-100 { background-color: #e0e7ff !important; }
            .bg-indigo-50 { background-color: #eef2ff !important; }
            .bg-slate-800 { background-color: #1e293b !important; color: white !important; }
        }
        
        body { font-family: 'Plus Jakarta Sans', sans-serif; background: #525659; padding: 20px; }
        .sheet { background: white; width: 210mm; min-height: 297mm; margin: 0 auto; padding: 15mm; position: relative; box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1); }
        .break-inside-avoid { break-inside: avoid; }
    </style>
</head>
<body class="text-slate-800">

    <div class="no-print text-center mb-6">
        <button onclick="window.print()" class="bg-indigo-600 text-white px-6 py-2.5 rounded-lg font-bold shadow-lg hover:bg-indigo-700 transition">
            <svg class="w-5 h-5 inline-block mr-2 -mt-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
            Cetak Dokumen
        </button>
    </div>

    <div class="sheet rounded-xl">
        
        <?php 
            $c = $data['company'] ?? [];
            $c_name = $c['company_name'] ?? 'PT. PERUSAHAAN KITA';
            $c_address = $c['address'] ?? 'Alamat Perusahaan belum diatur';
            $c_logo = $c['logo_path'] ?? '';
        ?>

        <div class="flex justify-between items-start pb-5 mb-2">
            <div class="flex items-center gap-4">
                <?php if(!empty($c_logo) && file_exists($_SERVER['DOCUMENT_ROOT'] . '/' . $c_logo)): ?>
                    <img src="<?= BASEURL ?>/<?= $c_logo ?>" class="h-14 object-contain">
                <?php else: ?>
                    <div class="h-14 w-14 bg-indigo-600 rounded-xl flex items-center justify-center text-white font-black text-2xl shadow-sm">
                        <?= substr($c_name, 0, 1) ?>
                    </div>
                <?php endif; ?>
                <div>
                    <h1 class="text-[14px] font-black text-slate-900 uppercase tracking-widest"><?= $c_name ?></h1>
                    <p class="text-[8px] text-slate-500 mt-1 max-w-sm leading-relaxed"><?= $c_address ?></p>
                </div>
            </div>
            <div class="text-right">
                <h2 class="text-[12px] font-black text-indigo-600 uppercase tracking-widest">LOG ABSENSI</h2>
                <p class="text-[10px] font-bold text-slate-600 bg-slate-100 inline-block px-3 py-1.5 rounded-lg mt-2 border border-slate-200">
                    PERIODE: <?= strtoupper($data['period_info']) ?>
                </p>
            </div>
        </div>

        <?php if(!empty($data['employee_detail'])): $e = $data['employee_detail']; ?>
        <div class="bg-indigo-50/50 rounded-xl border border-indigo-100 p-3 mb-2 flex justify-between items-center break-inside-avoid">
            <div class="flex items-center gap-5">
                <div>
                    <h3 class="text-[12px] font-bold text-slate-800 uppercase tracking-tight"><?= $e['first_name'] . ' ' . ($e['last_name'] ?? '') ?></h3>
                    <p class="text-indigo-600 font-mono font-bold text-[12px] mt-0.5 tracking-wider"><?= $e['employee_number'] ?></p>
                </div>
            </div>
            <div class="text-right">
                <table class="text-[10px] ml-auto">
                    <tr>
                        <td class="text-slate-500 text-right pr-3 py-1">Departemen :</td>
                        <td class="font-bold text-slate-800 uppercase"><?= $e['department_name'] ?? '-' ?></td>
                    </tr>
                    <tr>
                        <td class="text-slate-500 text-right pr-3 py-1">Jabatan :</td>
                        <td class="font-bold text-slate-800 uppercase"><?= $e['position'] ?? '-' ?></td>
                    </tr>
                </table>
            </div>
        </div>
        <?php endif; ?>

        <div class="mb-12">
            <table class="w-full text-[9px] border-collapse border border-slate-200">
                <thead class="bg-slate-800 text-white uppercase text-[10px] tracking-wider font-bold">
                    <tr>
                        <?php if ($data['mode'] == 'history'): ?>
                            <th class="py-1 px-4 text-left border border-slate-700">Tanggal</th>
                            <th class="py-1 px-4 text-left border border-slate-700">Shift</th>
                            <th class="py-1 px-4 text-center border border-slate-700">Masuk</th>
                            <th class="py-1 px-4 text-center border border-slate-700">Pulang</th>
                            <th class="py-1 px-4 text-center border border-slate-700 w-24">Status</th>
                            <th class="py-1 px-4 text-left border border-slate-700">Keterangan</th>
                        <?php elseif ($data['mode'] == 'daily'): ?>
                            <th class="py-1 px-4 text-left border border-slate-700">Tanggal</th>
                            <?php if(empty($data['employee_detail'])): ?><th class="py-1 px-4 text-left border border-slate-700">Pegawai</th><?php endif; ?>
                            <th class="py-1 px-4 text-center border border-slate-700">Shift</th>
                            <th class="py-1 px-4 text-center border border-slate-700">Masuk</th>
                            <th class="py-1 px-4 text-center border border-slate-700">Pulang</th>
                            <th class="py-1 px-4 text-center border border-slate-700 w-24">Status</th>
                            <th class="py-1 px-4 text-left border border-slate-700">Ket</th>
                        <?php else: ?>
                            <th class="py-1 px-4 text-center border border-slate-700 w-10">No</th>
                            <th class="py-1 px-4 text-left border border-slate-700">Pegawai</th>
                            <th class="py-1 px-4 text-left border border-slate-700">Jabatan</th>
                            <th class="py-1 px-4 text-center border border-slate-700">Hadir</th>
                            <th class="py-1 px-4 text-center border border-slate-700">Telat</th>
                            <th class="py-1 px-4 text-center border border-slate-700">Plg Awal</th>
                            <th class="py-1 px-4 text-center border border-slate-700">Total Jam</th>
                        <?php endif; ?>
                    </tr>
                </thead>
                <tbody class="text-slate-700 text-xs">
                    <?php if(empty($data['logs'])): ?>
                        <tr><td colspan="8" class="text-center italic py-8 text-slate-400 border border-slate-200">Data absensi tidak ditemukan pada periode ini.</td></tr>
                    <?php else: $no=1; foreach($data['logs'] as $row): ?>
                        <tr class="break-inside-avoid odd:bg-white even:bg-slate-50">
                            
                            <?php if ($data['mode'] == 'history'): ?>
                                <td class="py-1 px-4 font-medium whitespace-nowrap border border-slate-200">
                                    <span class="font-bold text-slate-800"><?= date('d M Y', strtotime($row['date'])) ?></span>
                                    <span class="text-[10px] text-slate-500 ml-1">(<?= date('D', strtotime($row['date'])) ?>)</span>
                                </td>
                                <td class="py-1 px-4 text-left border border-slate-200 font-medium">
                                    <?= $row['shift_name'] ?>
                                    <?php if($row['is_mod']): ?>
                                        <span class="ml-1 text-[9px] bg-indigo-100 text-indigo-700 px-1 py-0.5 rounded font-bold">MOD</span>
                                    <?php endif; ?>
                                </td>
                                <td class="py-1 px-4 text-center font-mono font-bold border border-slate-200 <?= $row['in'] !== '-' ? 'text-indigo-700' : 'text-slate-400' ?>"><?= $row['in'] ?></td>
                                <td class="py-1 px-4 text-center font-mono font-bold border border-slate-200">
                                    <span class="<?= $row['out'] !== '-' ? 'text-indigo-700' : 'text-slate-400' ?>"><?= $row['out'] ?></span>
                                    <?php if ($row['overtime_mins'] > 0): ?>
                                        <div class="text-[9px] text-orange-600 mt-0.5">+<?= number_format($row['overtime_mins']/60,1) ?>h OT</div>
                                    <?php endif; ?>
                                </td>
                                <td class="py-1 px-4 text-center border border-slate-200">
                                    <?= strip_tags($row['badge']) == 'Normal' ? '<span class="text-emerald-600 font-bold">NORMAL</span>' : $row['badge'] ?>
                                </td>
                                <td class="py-1 px-4 italic text-slate-500 border border-slate-200"><?= $row['note'] ?: '-' ?></td>

                            <?php elseif ($data['mode'] == 'daily'): ?>
                                <td class="py-1 px-4 font-medium whitespace-nowrap border border-slate-200">
                                    <?= date('d/m/Y', strtotime($row['attendance_date'])) ?>
                                </td>
                                <?php if(empty($data['employee_detail'])): ?>
                                    <td class="border border-slate-200 px-4">
                                        <div class="font-bold text-xs text-slate-900"><?= $row['first_name'] . ' ' . $row['last_name'] ?></div>
                                        <div class="text-[10px] text-slate-500 font-mono"><?= $row['employee_number'] ?></div>
                                    </td>
                                <?php endif; ?>
                                <td class="py-1 px-4 text-center text-slate-600 border border-slate-200"><?= $row['shift_name'] ?? '-' ?></td>
                                <td class="py-1 px-4 text-center font-mono font-bold text-indigo-700 border border-slate-200"><?= substr($row['check_in_time'], 11, 5) ?></td>
                                <td class="py-1 px-4 text-center font-mono font-bold text-indigo-700 border border-slate-200"><?= $row['check_out_time'] ? substr($row['check_out_time'], 11, 5) : '-' ?></td>
                                <td class="py-1 px-4 text-center font-bold uppercase border border-slate-200">
                                    <?php 
                                        if($row['is_late']) echo '<span class="text-red-600">TELAT</span>';
                                        elseif($row['is_early_out']) echo '<span class="text-orange-600">EARLY</span>';
                                        else echo '<span class="text-emerald-600">HADIR</span>';
                                    ?>
                                </td>
                                <td class="py-1 px-4 italic text-slate-500 border border-slate-200"><?= $row['check_in_notes'] ?? '-' ?></td>

                            <?php else: ?>
                                <td class="py-1 px-4 text-center text-slate-400 border border-slate-200"><?= $no++ ?></td>
                                <td class="border border-slate-200 px-4">
                                    <div class="font-bold text-slate-900"><?= $row['first_name'] . ' ' . $row['last_name'] ?></div>
                                    <div class="text-[10px] text-slate-500 font-mono"><?= $row['employee_number'] ?></div>
                                </td>
                                <td class="py-1 px-4 text-slate-600 border border-slate-200"><?= $row['position'] ?? '-' ?></td>
                                <td class="py-1 px-4 text-center font-bold text-slate-800 border border-slate-200"><?= $row['total_present'] ?></td>
                                <td class="py-1 px-4 text-center border border-slate-200 <?= $row['total_late'] > 0 ? 'text-red-600 font-bold' : 'text-slate-300' ?>"><?= $row['total_late'] > 0 ? $row['total_late'] : '-' ?></td>
                                <td class="py-1 px-4 text-center border border-slate-200 <?= $row['total_early_out'] > 0 ? 'text-orange-600 font-bold' : 'text-slate-300' ?>"><?= $row['total_early_out'] > 0 ? $row['total_early_out'] : '-' ?></td>
                                <td class="py-1 px-4 text-center font-mono border border-slate-200 font-bold text-indigo-600"><?= number_format($row['total_minutes']/60, 1) ?></td>
                            <?php endif; ?>
                        </tr>
                    <?php endforeach; endif; ?>
                </tbody>
            </table>
        </div>

        <div class="grid grid-cols-2 gap-12 text-center text-sm mt-auto break-inside-avoid">
            <div>
                <p class="text-slate-500 mb-16">Mengetahui,</p>
                <p class="font-bold text-slate-900 border-b border-slate-300 inline-block pb-1 min-w-[200px] uppercase">MANAGER / HRD</p>
                <p class="text-xs text-slate-400 mt-1">Authorized Signature</p>
            </div>
            <div>
                <p class="text-slate-500 mb-16">Dibuat Oleh,</p>
                <p class="font-bold text-slate-900 border-b border-slate-300 inline-block pb-1 min-w-[200px] uppercase"><?= $data['generated_by'] ?></p>
                <p class="text-xs text-slate-400 mt-1">Dicetak pada: <?= $data['generated_at'] ?></p>
            </div>
        </div>

    </div>
</body>
</html>