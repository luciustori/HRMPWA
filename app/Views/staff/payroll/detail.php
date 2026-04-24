<?php
// --- 1. DATA PREPARATION ---
$h = $data['payroll']; 
$d = $data['details'] ?? [];
$c = $data['company']; 

// Helper Safe Value
function safe($val, $def = '-') { return !empty($val) ? $val : $def; }

// Format Data
$periode = safe($h['period_name']);
$nik = safe($h['employee_number']);
$nama = strtoupper(safe($h['first_name']) . ' ' . safe($h['last_name']));
$jabatan = safe($h['position'] ?? $h['position_name']);
$departemen = safe($h['department_name']);
$metode = strtoupper(safe($h['payment_method'], 'Transfer'));
$tgl_bayar = !empty($h['paid_at']) ? date('d M Y', strtotime($h['paid_at'])) : '-';

// Hitung Angka
$gaji_pokok = $h['gross_salary'] ?? 0; 
$total_terima = 0; // Hitung ulang dari detail agar akurat
$total_potong = 0;

// Pisahkan Detail
$earnings = [];
$deductions = [];

if(!empty($d)) {
    foreach($d as $item) {
        if($item['component_type'] == 'earning') {
            $earnings[] = $item;
            $total_terima += $item['amount'];
        } else {
            $deductions[] = $item;
            $total_potong += $item['amount'];
        }
    }
} else {
    // Fallback jika detail kosong
    $total_terima = $h['total_allowances'] + $gaji_pokok;
    $total_potong = $h['total_deductions'];
}

// Net Salary (Pastikan hitungan benar)
$thp = $h['net_salary']; 

// Logo Logic (Auto Detect Path)
$logoUrl = '';
if (!empty($c['company_logo'])) {
    $logoPath = $c['company_logo'];
    // Cek apakah path sudah mengandung 'public/' atau tidak
    if (file_exists($_SERVER['DOCUMENT_ROOT'] . '/' . $logoPath)) {
        $logoUrl = BASEURL . '/' . $logoPath;
    } elseif (file_exists($_SERVER['DOCUMENT_ROOT'] . '/public/' . $logoPath)) {
        $logoUrl = BASEURL . '/public/' . $logoPath;
    } else {
        $logoUrl = BASEURL . '/assets/img/logo.png'; // Default jika file tidak ketemu
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Payslip - <?= $periode ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; background: #525659; }
        .sheet { background: white; max-width: 210mm; margin: 30px auto; padding: 15mm; min-height: 200mm; position: relative; box-shadow: 0 10px 30px rgba(0,0,0,0.2); }
        
        @media print {
            body { background: white; margin: 0; }
            .no-print { display: none !important; }
            .sheet { width: 100%; max-width: none; margin: 0; padding: 10mm; box-shadow: none; }
            /* Paksa Warna Cetak */
            .bg-green-50 { background-color: #f0fdf4 !important; -webkit-print-color-adjust: exact; }
            .bg-red-50 { background-color: #fef2f2 !important; -webkit-print-color-adjust: exact; }
            .bg-indigo-600 { background-color: #4f46e5 !important; color: white !important; -webkit-print-color-adjust: exact; }
            .text-indigo-600 { color: #4f46e5 !important; -webkit-print-color-adjust: exact; }
        }
    </style>
</head>
<body>

    <div class="no-print fixed top-5 right-5 flex gap-2 z-50">
        <a href="<?= BASEURL ?>/staff/payroll" class="bg-gray-700 text-white px-4 py-2 rounded shadow font-bold text-sm hover:bg-gray-800">Kembali</a>
        <button onclick="window.print()" class="bg-indigo-600 text-white px-4 py-2 rounded shadow font-bold text-sm hover:bg-indigo-700">🖨️ Cetak PDF</button>
    </div>

    <div class="sheet">
        
        <div class="flex justify-between items-start border-b-2 border-gray-900 pb-6 mb-8">
            <div class="flex gap-5 items-center">
                <?php if($logoUrl): ?>
                    <img src="<?= $logoUrl ?>" class="h-16 w-auto object-contain">
                <?php else: ?>
                    <div class="h-14 w-14 bg-gray-900 text-white flex items-center justify-center font-bold text-2xl rounded">C</div>
                <?php endif; ?>
                
                <div>
                    <h1 class="text-2xl font-black text-gray-900 uppercase leading-none"><?= $c['company_name'] ?></h1>
                    <p class="text-sm text-gray-500 mt-1 max-w-sm leading-tight"><?= $c['company_address'] ?></p>
                    <p class="text-xs text-gray-400 mt-1"><?= $c['company_phone'] ?></p>
                </div>
            </div>
            <div class="text-right">
                <h2 class="text-4xl font-black text-gray-200 uppercase tracking-widest leading-none">PAYSLIP</h2>
                <p class="text-sm font-bold text-indigo-600 uppercase mt-1 tracking-wide"><?= $periode ?></p>
            </div>
        </div>

        <div class="bg-gray-50 rounded-lg p-6 mb-8 border border-gray-100">
            <div class="grid grid-cols-2 gap-x-12 gap-y-3 text-sm">
                <div class="flex justify-between">
                    <span class="text-gray-500 w-24">NIK</span>
                    <span class="font-bold text-gray-900">: <?= $nik ?></span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-500 w-24">Nama</span>
                    <span class="font-bold text-gray-900 uppercase">: <?= $nama ?></span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-500 w-24">Jabatan</span>
                    <span class="font-medium text-gray-900">: <?= $jabatan ?></span>
                </div>

                <div class="flex justify-between">
                    <span class="text-gray-500 w-24">Metode</span>
                    <span class="font-medium text-gray-900 capitalize">: <?= $metode ?></span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-500 w-24">Kehadiran</span>
                    <span class="font-medium text-gray-900">: <span class="font-bold text-green-600"><?= $h['present_days'] ?? 0 ?></span> Hari</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-500 w-24">Lembur</span>
                    <span class="font-medium text-gray-900">: <span class="font-bold text-orange-600"><?= $h['overtime_hours'] ?? 0 ?></span> Jam</span>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-2 gap-8 mb-8">
            
            <div class="border border-gray-200 rounded-lg overflow-hidden flex flex-col">
                <div class="bg-green-50 px-4 py-2 border-b border-green-100 flex justify-between items-center">
                    <span class="text-xs font-bold text-green-800 uppercase tracking-wider">PENERIMAAN (EARNINGS)</span>
                    <span class="text-green-600 font-bold">+</span>
                </div>
                <div class="p-4 bg-white flex-1">
                    <table class="w-full text-sm">
                        <tr>
                            <td class="py-1 text-gray-600">Gaji Pokok</td>
                            <td class="text-right font-medium text-gray-900">Rp <?= number_format($gaji_pokok, 0, ',', '.') ?></td>
                        </tr>
                        <?php foreach($earnings as $e): ?>
                        <tr>
                            <td class="py-1 text-gray-600"><?= $e['component_name'] ?></td>
                            <td class="text-right font-medium text-gray-900">Rp <?= number_format($e['amount'], 0, ',', '.') ?></td>
                        </tr>
                        <?php endforeach; ?>
                        
                        <?php if(empty($earnings) && $h['total_allowances'] > 0): ?>
                        <tr>
                            <td class="py-1 text-gray-600">Tunjangan Lainnya</td>
                            <td class="text-right font-medium text-gray-900">Rp <?= number_format($h['total_allowances'], 0, ',', '.') ?></td>
                        </tr>
                        <?php endif; ?>
                    </table>
                </div>
                <div class="bg-gray-50 px-4 py-2 border-t border-gray-100 flex justify-between text-sm font-bold text-gray-800">
                    <span>Total</span>
                    <span>Rp <?= number_format($total_terima, 0, ',', '.') ?></span>
                </div>
            </div>

            <div class="border border-gray-200 rounded-lg overflow-hidden flex flex-col">
                <div class="bg-red-50 px-4 py-2 border-b border-red-100 flex justify-between items-center">
                    <span class="text-xs font-bold text-red-800 uppercase tracking-wider">POTONGAN (DEDUCTIONS)</span>
                    <span class="text-red-600 font-bold">-</span>
                </div>
                <div class="p-4 bg-white flex-1">
                    <table class="w-full text-sm">
                        <?php if(empty($deductions) && $total_potong == 0): ?>
                            <tr><td colspan="2" class="text-center text-gray-400 italic py-4">- Tidak ada potongan -</td></tr>
                        <?php else: ?>
                            <?php foreach($deductions as $d): ?>
                            <tr>
                                <td class="py-1 text-gray-600"><?= $d['component_name'] ?></td>
                                <td class="text-right font-medium text-red-600">Rp <?= number_format($d['amount'], 0, ',', '.') ?></td>
                            </tr>
                            <?php endforeach; ?>

                            <?php if(empty($deductions) && $total_potong > 0): ?>
                            <tr>
                                <td class="py-1 text-gray-600">Total Potongan</td>
                                <td class="text-right font-medium text-red-600">Rp <?= number_format($total_potong, 0, ',', '.') ?></td>
                            </tr>
                            <?php endif; ?>
                        <?php endif; ?>
                    </table>
                </div>
                <div class="bg-gray-50 px-4 py-2 border-t border-gray-100 flex justify-between text-sm font-bold text-gray-800">
                    <span>Total</span>
                    <span>(Rp <?= number_format($total_potong, 0, ',', '.') ?>)</span>
                </div>
            </div>

        </div>

        <div class="bg-indigo-600 text-white rounded-lg p-6 mb-12 flex justify-between items-center shadow-lg relative overflow-hidden">
            <div class="relative z-10">
                <p class="text-xs font-medium text-indigo-200 uppercase tracking-widest mb-1">TAKE HOME PAY</p>
                <p class="text-sm text-indigo-100 italic">Gaji Bersih yang diterima</p>
            </div>
            <div class="text-3xl font-black font-mono tracking-tight relative z-10">
                Rp <?= number_format($thp, 0, ',', '.') ?>
            </div>
            <div class="absolute right-0 top-0 opacity-10 transform translate-x-4 -translate-y-4">
                <svg class="w-32 h-32" fill="currentColor" viewBox="0 0 20 20"><path d="M4 4a2 2 0 00-2 2v1h16V6a2 2 0 00-2-2H4z"/><path fill-rule="evenodd" d="M18 9H2v5a2 2 0 002 2h12a2 2 0 002-2V9zM4 13a1 1 0 011-1h1a1 1 0 110 2H5a1 1 0 01-1-1zm5-1a1 1 0 100 2h1a1 1 0 100-2H9z" clip-rule="evenodd"/></svg>
            </div>
        </div>

        <div class="grid grid-cols-2 gap-12 text-center text-sm mt-8">
            <div>
                <p class="text-gray-500 mb-20">Penerima,</p>
                <p class="font-bold text-gray-900 border-b border-gray-300 inline-block pb-1 px-4 uppercase"><?= $nama ?></p>
            </div>
            <div>
                <p class="text-gray-500 mb-20">Finance / HRD,</p>
                <p class="font-bold text-gray-900 border-b border-gray-300 inline-block pb-1 px-4">ADMINISTRATOR</p>
            </div>
        </div>

    </div>

</body>
</html>