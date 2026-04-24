<?php
// File: app/Views/admin/reports/print_monthly.php

// Fallback untuk memastikan variabel selalu ada
$period_text = $period_text ?? $_GET['month_year'] ?? date('F Y');
$selected_dept = $selected_dept ?? $_GET['dept_id'] ?? 'all';
$reports = $reports ?? [];

// AMBIL DATA PERUSAHAAN DARI DATABASE
$db = new Database;
$db->query("SELECT setting_key, setting_value FROM app_settings");
$settings_raw = $db->resultSet();
$s = [];
foreach($settings_raw as $r) {
    $s[$r['setting_key']] = $r['setting_value'];
}

$company_name = $s['company_name'] ?? $s['app_name'] ?? 'NAMA PERUSAHAAN';
$company_address = $s['company_address'] ?? 'Alamat belum disetting';
$company_phone = $s['company_phone'] ?? '';
$logoPath = $s['company_logo'] ?? $s['app_logo'] ?? '';

// Cek Keberadaan File Logo
$showLogo = false;
$logoUrl = '';
if (!empty($logoPath)) {
    $docRoot = $_SERVER['DOCUMENT_ROOT'];
    if (file_exists($docRoot . '/' . $logoPath)) {
        $showLogo = true; 
        $logoUrl = BASEURL . '/' . $logoPath;
    } elseif (file_exists($docRoot . '/public/' . $logoPath)) {
        $showLogo = true; 
        $logoUrl = BASEURL . '/public/' . $logoPath;
    }
}
$initials = substr($company_name, 0, 1);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Print Laporan Bulanan - <?= $period_text ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdn.jsdelivr.net/npm/remixicon@3.5.0/fonts/remixicon.css" rel="stylesheet">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap');
        body { font-family: 'Inter', sans-serif; background-color: #475569; }
        
        @media print {
            @page { size: landscape; margin: 10mm; }
            body { background-color: white !important; margin: 0; padding: 0; }
            .no-print { display: none !important; }
            .print-container { box-shadow: none !important; margin: 0 !important; max-width: 100% !important; padding: 0 !important; }
            * { -webkit-print-color-adjust: exact !important; print-color-adjust: exact !important; }
            table { page-break-inside: auto; }
            tr { page-break-inside: avoid; page-break-after: auto; }
            thead { display: table-header-group; }
        }
    </style>
</head>
<body class="py-10">

    <div class="fixed top-4 left-1/2 -translate-x-1/2 z-50 no-print">
        <button onclick="window.print()" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2.5 px-6 rounded-lg shadow-lg flex items-center gap-2 transition-all">
            <i class="ri-printer-line text-lg"></i> Cetak Dokumen
        </button>
    </div>

    <div class="print-container bg-white mx-auto shadow-2xl rounded-sm p-10 relative" style="max-width: 297mm; min-height: 210mm;">
        
        <div class="flex justify-between items-start border-b-2 border-slate-800 pb-6 mb-6">
            <div class="flex items-center gap-4">
                <?php if ($showLogo): ?>
                    <img src="<?= $logoUrl ?>" alt="Logo" class="h-14 w-auto object-contain">
                <?php else: ?>
                    <div class="w-14 h-14 bg-indigo-600 text-white rounded flex items-center justify-center font-black text-3xl">
                        <?= strtoupper($initials) ?>
                    </div>
                <?php endif; ?>
                
                <div>
                    <h1 class="text-xl font-extrabold text-slate-900 uppercase tracking-wide"><?= $company_name ?></h1>
                    <p class="text-xs text-slate-500 mt-1"><?= $company_address ?> <?= !empty($company_phone) ? '| Telp: ' . $company_phone : '' ?></p>
                </div>
            </div>
            <div class="text-right">
                <h2 class="text-lg font-bold text-indigo-700 uppercase tracking-widest mb-1">LAPORAN BULANAN</h2>
                <div class="inline-block bg-slate-100 text-slate-700 px-3 py-1 rounded-full text-xs font-bold border border-slate-200">
                    PERIODE: <?= strtoupper($period_text) ?>
                </div>
            </div>
        </div>

        <div class="mb-4">
            <p class="text-sm text-slate-700 font-bold">Departemen: <span class="font-normal uppercase"><?= $selected_dept == 'all' ? 'Semua Departemen' : ($reports[0]['department_name'] ?? 'Pilih Departemen') ?></span></p>
        </div>

        <table class="w-full text-[10px] text-left border-collapse border border-slate-300">
            <thead class="bg-slate-800 text-white font-bold uppercase tracking-wider">
                <tr>
                    <th class="px-3 py-3 border border-slate-700 w-48">Karyawan</th>
                    <th class="px-2 py-3 border border-slate-700 text-center">MOD</th>
                    <th class="px-2 py-3 border border-slate-700 text-center">Hadir</th>
                    <th class="px-2 py-3 border border-slate-700 text-center text-red-300">Telat</th>
                    <th class="px-2 py-3 border border-slate-700 text-center text-yellow-300">Plg Awal</th>
                    <th class="px-2 py-3 border border-slate-700 text-center text-orange-300">Lembur</th>
                    <th class="px-2 py-3 border border-slate-700 text-center text-emerald-300">Cuti</th>
                    <th class="px-2 py-3 border border-slate-700 text-center text-orange-400">Ijin</th>
                    <th class="px-2 py-3 border border-slate-700 text-center text-blue-300">Dinas</th>
                    <th class="px-2 py-3 border border-slate-700 text-center text-purple-300">SPPD</th>
                    <th class="px-2 py-3 border border-slate-700 text-center text-red-400">Alpha</th>
                    <th class="px-3 py-3 border border-slate-700 text-center">Jam Kerja</th>
                    <th class="px-2 py-3 border border-slate-700 text-center">% Rate</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-200">
                <?php if(empty($reports)): ?>
                    <tr><td colspan="13" class="p-6 text-center text-slate-500 italic">Tidak ada data untuk periode ini.</td></tr>
                <?php else: ?>
                    <?php foreach($reports as $row): ?>
                    <tr class="hover:bg-slate-50">
                        <td class="px-3 py-2 border border-slate-300">
                            <div class="font-bold text-slate-800 text-xs"><?= strtoupper($row['full_name']) ?></div>
                            <div class="text-[9px] text-slate-500 mt-0.5"><?= $row['department_name'] ?></div>
                        </td>
                        <td class="px-2 py-2 border border-slate-300 text-center font-bold text-indigo-700 bg-indigo-50/50"><?= $row['total_mod'] > 0 ? $row['total_mod'] : '-' ?></td>
                        <td class="px-2 py-2 border border-slate-300 text-center font-bold text-slate-700"><?= $row['present_days'] ?></td>
                        
                        <td class="px-2 py-2 border border-slate-300 text-center">
                            <?= $row['late_days'] > 0 ? '<span class="text-red-600 font-bold">'.$row['late_days'].'</span>' : '<span class="text-slate-300">-</span>' ?>
                        </td>
                        <td class="px-2 py-2 border border-slate-300 text-center">
                            <?= $row['early_out_days'] > 0 ? '<span class="text-yellow-600 font-bold">'.$row['early_out_days'].'</span>' : '<span class="text-slate-300">-</span>' ?>
                        </td>
                        <td class="px-2 py-2 border border-slate-300 text-center">
                            <?= $row['total_lembur_requests'] > 0 ? '<span class="text-orange-600 font-bold">'.$row['total_lembur_requests'].'</span>' : '<span class="text-slate-300">-</span>' ?>
                        </td>
                        <td class="px-2 py-2 border border-slate-300 text-center bg-emerald-50/50">
                            <?= $row['total_cuti'] > 0 ? '<span class="text-emerald-600 font-bold">'.$row['total_cuti'].'</span>' : '<span class="text-slate-300">-</span>' ?>
                        </td>
                        <td class="px-2 py-2 border border-slate-300 text-center">
                            <?= $row['total_ijin_sakit'] > 0 ? '<span class="text-orange-500 font-bold">'.$row['total_ijin_sakit'].'</span>' : '<span class="text-slate-300">-</span>' ?>
                        </td>
                        <td class="px-2 py-2 border border-slate-300 text-center">
                            <?= $row['total_dl'] > 0 ? '<span class="text-blue-600 font-bold">'.$row['total_dl'].'</span>' : '<span class="text-slate-300">-</span>' ?>
                        </td>
                        <td class="px-2 py-2 border border-slate-300 text-center">
                            <?= $row['total_sppd'] > 0 ? '<span class="text-purple-600 font-bold">'.$row['total_sppd'].'</span>' : '<span class="text-slate-300">-</span>' ?>
                        </td>
                        <td class="px-2 py-2 border border-slate-300 text-center bg-red-50">
                            <?= $row['total_absent'] > 0 ? '<span class="text-red-600 font-bold">'.$row['total_absent'].'</span>' : '<span class="text-slate-300">-</span>' ?>
                        </td>
                        
                        <td class="px-3 py-2 border border-slate-300 text-center font-mono">
                            <div><?= number_format($row['total_work_minutes'] / 60, 1) ?>h</div>
                        </td>
                        
                        <td class="px-2 py-2 border border-slate-300 text-center">
                            <?php 
                                $total_days = $row['total_days'];
                                $rate = $total_days > 0 ? round(($row['present_days'] / $total_days) * 100) : ($row['present_days'] > 0 ? 100 : 0);
                                if ($rate > 100) $rate = 100;
                                $color = $rate >= 95 ? 'text-emerald-600' : ($rate >= 80 ? 'text-yellow-600' : 'text-red-600');
                            ?>
                            <span class="font-bold <?= $color ?>"><?= $rate ?>%</span>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>

        <div class="mt-16 flex justify-between px-10 text-xs text-slate-800">
            <div class="text-center">
                <p class="mb-16">Mengetahui,</p>
                <div class="border-b border-slate-800 w-48 mx-auto mb-1"></div>
                <p class="font-bold uppercase">MANAGER / HRD</p>
                <p class="text-[10px] text-slate-500">Authorized Signature</p>
            </div>
            
            <div class="text-center">
                <p class="mb-16">Dibuat Oleh,</p>
                <div class="border-b border-slate-800 w-48 mx-auto mb-1"></div>
                <p class="font-bold uppercase"><?= $_SESSION['full_name'] ?? 'Super Admin' ?></p>
                <p class="text-[10px] text-slate-500">Dicetak pada: <?= date('d M Y H:i') ?></p>
            </div>
        </div>

    </div>
</body>
</html>