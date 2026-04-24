<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Slip Gaji - <?= $header['period_name'] ?></title>
    
    <?php if ($format == 'modern'): ?>
        <script src="https://cdn.tailwindcss.com"></script>
        <style>
            @media print {
                body { -webkit-print-color-adjust: exact; print-color-adjust: exact; background: white !important; }
                .no-print { display: none !important; }
                .sheet { box-shadow: none !important; border: none !important; margin: 0 !important; width: 100% !important; padding: 0 !important;}
                
                /* Paksa Warna Background Saat Print */
                .bg-green-50 { background-color: #f0fdf4 !important; }
                .bg-red-50 { background-color: #fef2f2 !important; }
                .bg-indigo-600 { background-color: #4f46e5 !important; color: white !important; }
            }
            body { font-family: 'Segoe UI', sans-serif; background: #525659; padding: 20px; }
            .sheet { background: white; width: 210mm; min-height: 148mm; margin: 0 auto; padding: 15mm; position: relative; }
        </style>
    <?php else: ?>
        <style>
            @media print {
                .no-print { display: none !important; }
                body { background: white !important; }
                .sheet { width: 100% !important; border: none !important; margin: 0 !important; padding: 0 !important; }
            }
            body { font-family: 'Courier New', monospace; background: #ccc; padding: 20px; font-size: 12px; color: #000; }
            .sheet { background: white; width: 210mm; min-height: 148mm; margin: 0 auto; padding: 10mm; border: 1px solid #000; }
            table { width: 100%; border-collapse: collapse; margin-bottom: 10px; }
            .table-border td, .table-border th { border: 1px solid #000; padding: 4px; }
            .text-right { text-align: right; }
            .bold { font-weight: bold; }
            .uppercase { text-transform: uppercase; }
            .border-bottom-double { border-bottom: 3px double #000; margin-bottom: 15px; padding-bottom: 10px; }
        </style>
    <?php endif; ?>
</head>
<body>

    <div class="no-print" style="position: fixed; top: 10px; right: 10px; z-index: 99; display: flex; gap: 5px;">
        <button onclick="window.print()" style="background: #2563eb; color: white; padding: 8px 15px; border: none; cursor: pointer; border-radius: 4px; font-weight: bold;">🖨️ Print PDF</button>
        <button onclick="window.close()" style="background: #4b5563; color: white; padding: 8px 15px; border: none; cursor: pointer; border-radius: 4px; font-weight: bold;">Tutup</button>
    </div>

    <div class="sheet">
        
        <?php if ($format == 'modern'): ?>
            
            <div class="flex justify-between items-end border-b-2 border-gray-800 pb-6 mb-6">
                <div class="flex items-center gap-4">
                    <?php 
                        // Logic Cek Gambar Logo (Anti-Error)
                        $logoPath = $s['company_logo'] ?? '';
                        $docRoot = $_SERVER['DOCUMENT_ROOT'];
                        $showLogo = false;
                        
                        if (!empty($logoPath)) {
                            if (file_exists($docRoot . '/' . $logoPath)) $showLogo = true;
                            elseif (file_exists($docRoot . '/public/' . $logoPath)) $showLogo = true;
                        }
                    ?>
                    
                    <?php if ($showLogo): ?>
                        <img src="<?= BASEURL . '/' . $logoPath ?>" alt="Logo" class="h-16 w-auto object-contain">
                    <?php else: ?>
                        <div class="h-14 w-14 bg-gray-900 text-white flex items-center justify-center font-bold text-2xl rounded">M</div>
                    <?php endif; ?>
                    
                    <div>
                        <h1 class="text-2xl font-bold text-gray-900 uppercase tracking-tight"><?= $s['company_name'] ?? 'NAMA PERUSAHAAN' ?></h1>
                        <p class="text-sm text-gray-500 leading-tight max-w-sm"><?= $s['company_address'] ?? 'Alamat belum disetting' ?></p>
                        <p class="text-xs text-gray-400 mt-1"><?= $s['company_phone'] ?? '' ?></p>
                    </div>
                </div>
                <div class="text-right">
                    <h2 class="text-3xl font-black text-gray-200 uppercase tracking-widest leading-none">PAYSLIP</h2>
                    <p class="text-sm font-bold text-indigo-600 uppercase mt-1"><?= $header['period_name'] ?></p>
                </div>
            </div>

            <div class="bg-gray-50 rounded-lg p-4 mb-6 border border-gray-100">
                <div class="grid grid-cols-2 gap-8 text-sm">
                    <table>
                        <tr><td class="w-24 text-gray-500 py-1">NIK</td><td class="font-bold text-gray-900">: <?= $header['employee_number'] ?></td></tr>
                        <tr><td class="text-gray-500 py-1">Nama</td><td class="font-bold text-gray-900 uppercase">: <?= $header['first_name'] . ' ' . $header['last_name'] ?></td></tr>
                        <tr><td class="text-gray-500 py-1">Jabatan</td><td class="text-gray-900">: <?= $header['position_name'] ?? '-' ?></td></tr>
                    </table>
                    <table>
                        <tr><td class="w-24 text-gray-500 py-1">Metode</td><td class="text-gray-900 capitalize">: <?= $header['payment_method'] ?? 'Transfer' ?></td></tr>
                        <tr><td class="text-gray-500 py-1">Kehadiran</td><td class="text-gray-900">: <span class="font-bold text-green-600"><?= $header['present_days'] ?? 0 ?></span> Hari</td></tr>
                        <tr><td class="text-gray-500 py-1">Lembur</td><td class="text-gray-900">: <span class="font-bold text-orange-600"><?= $header['overtime_hours'] ?? 0 ?></span> Jam</td></tr>
                    </table>
                </div>
            </div>

            <div class="grid grid-cols-2 gap-6 mb-6">
                
                <div class="border border-gray-200 rounded-lg overflow-hidden flex flex-col">
                    <div class="bg-green-50 px-4 py-2 border-b border-green-100 flex justify-between items-center">
                        <span class="text-xs font-bold text-green-800 uppercase tracking-wider">Penerimaan (Earnings)</span>
                        <span class="text-green-600 text-lg">+</span>
                    </div>
                    <div class="p-4 bg-white flex-1">
                        <table class="w-full text-sm">
                            <tr>
                                <td class="py-1 text-gray-600">Gaji Pokok</td>
                                <td class="text-right font-medium text-gray-900">Rp <?= number_format($header['gross_salary'] ?? $header['basic_salary'] ?? 0, 0, ',', '.') ?></td>
                            </tr>
                            <?php 
                            $total_earn = $header['gross_salary'] ?? $header['basic_salary'] ?? 0;
                            if(!empty($details)):
                                foreach($details as $d): 
                                    if($d['component_type'] == 'earning'):
                                        $total_earn += $d['amount'];
                            ?>
                            <tr>
                                <td class="py-1 text-gray-600"><?= $d['component_name'] ?></td>
                                <td class="text-right font-medium text-gray-900">Rp <?= number_format($d['amount'], 0, ',', '.') ?></td>
                            </tr>
                            <?php 
                                    endif; 
                                endforeach; 
                            endif;
                            ?>
                        </table>
                    </div>
                    <div class="bg-gray-50 px-4 py-2 border-t border-gray-100 flex justify-between text-sm font-bold text-gray-800">
                        <span>Total</span>
                        <span>Rp <?= number_format($total_earn, 0, ',', '.') ?></span>
                    </div>
                </div>

                <div class="border border-gray-200 rounded-lg overflow-hidden flex flex-col">
                    <div class="bg-red-50 px-4 py-2 border-b border-red-100 flex justify-between items-center">
                        <span class="text-xs font-bold text-red-800 uppercase tracking-wider">Potongan (Deductions)</span>
                        <span class="text-red-600 text-lg">-</span>
                    </div>
                    <div class="p-4 bg-white flex-1">
                        <table class="w-full text-sm">
                            <?php 
                            $total_deduct = 0;
                            if(!empty($details)):
                                foreach($details as $d): 
                                    if($d['component_type'] != 'earning'):
                                        $total_deduct += $d['amount'];
                            ?>
                            <tr>
                                <td class="py-1 text-gray-600"><?= $d['component_name'] ?></td>
                                <td class="text-right font-medium text-red-600">Rp <?= number_format($d['amount'], 0, ',', '.') ?></td>
                            </tr>
                            <?php 
                                    endif; 
                                endforeach; 
                            endif;
                            
                            // Fallback jika detail kosong tapi ada total di header
                            if($total_deduct == 0 && !empty($header['total_deductions'])) {
                                $total_deduct = $header['total_deductions'];
                                echo "<tr><td class='py-1 text-gray-600'>Total Potongan</td><td class='text-right font-medium text-red-600'>Rp ".number_format($total_deduct, 0, ',', '.')."</td></tr>";
                            }
                            ?>
                            <?php if($total_deduct == 0): ?>
                                <tr><td colspan="2" class="text-center text-gray-400 italic py-4">- Tidak ada potongan -</td></tr>
                            <?php endif; ?>
                        </table>
                    </div>
                    <div class="bg-gray-50 px-4 py-2 border-t border-gray-100 flex justify-between text-sm font-bold text-gray-800">
                        <span>Total</span>
                        <span>(Rp <?= number_format($total_deduct, 0, ',', '.') ?>)</span>
                    </div>
                </div>
            </div>

            <div class="bg-indigo-600 text-white rounded-lg p-6 mb-12 flex justify-between items-center shadow-lg">
                <div>
                    <p class="text-xs font-medium text-indigo-200 uppercase tracking-widest mb-1">Take Home Pay</p>
                    <p class="text-sm text-indigo-100 italic">Gaji Bersih yang diterima</p>
                </div>
                <div class="text-3xl font-bold font-mono tracking-tight">
                    Rp <?= number_format($header['net_salary'], 0, ',', '.') ?>
                </div>
            </div>

            <div class="grid grid-cols-2 gap-12 text-center text-sm">
                <div>
                    <p class="text-gray-500 mb-16">Penerima,</p>
                    <p class="font-bold text-gray-900 border-b border-gray-300 inline-block pb-1 uppercase"><?= $header['first_name'] . ' ' . $header['last_name'] ?></p>
                </div>
                <div>
                    <p class="text-gray-500 mb-16">Finance / HRD,</p>
                    <p class="font-bold text-gray-900 border-b border-gray-300 inline-block pb-1">ADMINISTRATOR</p>
                </div>
            </div>

        <?php else: ?>
            <div class="text-center border-bottom-double">
                <h2 style="margin:0; font-size:18px; text-transform: uppercase;"><?= $s['company_name'] ?? 'PERUSAHAAN SAYA' ?></h2>
                <div style="font-size:11px; margin-top:2px;"><?= $s['company_address'] ?? '' ?></div>
                <h3 style="margin:15px 0 5px 0; text-decoration: underline;">SLIP GAJI KARYAWAN</h3>
                <div style="font-size:11px;">Periode: <?= $header['period_name'] ?></div>
            </div>
            <?php endif; ?>

    </div>

</body>
</html>