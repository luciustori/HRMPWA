<div class="container mx-auto px-4 py-8">
    
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
        
        <!-- KOLOM KIRI: MAIN PAYSLIP (Kertas Gaji) -->
        <div class="lg:col-span-8">
            
            <?php if (!$data['salary']): ?>
                <!-- EMPTY STATE -->
                <div class="bg-white rounded-xl shadow-sm border p-12 text-center">
                    <div class="mx-auto w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mb-4">
                        <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                    </div>
                    <h3 class="text-lg font-bold text-gray-800">Belum Ada Data Gaji</h3>
                    <p class="text-gray-500 mt-2">Slip gaji Anda belum diterbitkan atau periode ini belum tersedia.</p>
                </div>
            <?php else: ?>

                <!-- PAPER LAYOUT -->
                <div class="bg-white rounded-xl shadow-lg border overflow-hidden relative">
                    <!-- Watermark LUNAS -->
                    <?php if($data['salary']['status'] == 'paid'): ?>
                        <div class="absolute top-10 right-10 opacity-10 pointer-events-none transform -rotate-12 border-4 border-green-600 text-green-600 font-black text-6xl px-4 py-2 rounded-xl">
                            LUNAS
                        </div>
                    <?php endif; ?>

                    <!-- Header Slip -->
                    <div class="bg-gray-800 text-white p-6">
                        <div class="flex justify-between items-start">
                            <div>
                                <h1 class="text-2xl font-bold uppercase tracking-wider">Slip Gaji</h1>
                                <p class="text-gray-300 text-sm mt-1">Periode: <span class="text-white font-semibold"><?= $data['salary']['period_name'] ?></span></p>
                            </div>
                            <div class="text-right">
                                <h2 class="font-bold text-lg">PT. PERUSAHAAN ANDA</h2>
                                <p class="text-xs text-gray-400">Jl. Contoh Alamat No. 123, Jakarta</p>
                            </div>
                        </div>
                    </div>

                    <!-- Info Karyawan -->
                    <div class="p-6 bg-gray-50 border-b flex flex-wrap gap-y-4">
                        <div class="w-1/2 md:w-1/3">
                            <span class="text-xs text-gray-500 uppercase block">NIK</span>
                            <span class="font-semibold text-gray-800"><?= $data['salary']['employee_number'] ?></span>
                        </div>
                        <div class="w-1/2 md:w-1/3">
                            <span class="text-xs text-gray-500 uppercase block">Nama</span>
                            <span class="font-semibold text-gray-800"><?= $data['salary']['emp_name'] ?></span>
                        </div>
                        <div class="w-1/2 md:w-1/3">
                            <span class="text-xs text-gray-500 uppercase block">Jabatan</span>
                            <span class="font-semibold text-gray-800"><?= $data['salary']['position_name'] ?></span>
                        </div>
                        <div class="w-1/2 md:w-1/3">
                            <span class="text-xs text-gray-500 uppercase block">Departemen</span>
                            <span class="font-semibold text-gray-800"><?= $data['salary']['department_name'] ?></span>
                        </div>
                        <div class="w-1/2 md:w-1/3">
                            <span class="text-xs text-gray-500 uppercase block">Metode Bayar</span>
                            <span class="font-semibold text-gray-800 capitalize"><?= $data['salary']['payment_method'] ?></span>
                        </div>
                    </div>

                    <!-- Detail Gaji Grid -->
                    <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-8">
                        
                        <!-- PENDAPATAN (EARNINGS) -->
                        <div>
                            <h3 class="text-sm font-bold text-gray-700 uppercase border-b-2 border-green-500 pb-2 mb-4 flex justify-between">
                                <span>Pendapatan</span>
                                <span class="text-green-600">+</span>
                            </h3>
                            <div class="space-y-3">
                                <?php if(isset($data['details']['earning'])): ?>
                                    <?php foreach($data['details']['earning'] as $item): ?>
                                        <div class="flex justify-between text-sm">
                                            <span class="text-gray-600"><?= $item['component_name'] ?></span>
                                            <span class="font-medium text-gray-800"><?= number_format($item['amount'], 0, ',', '.') ?></span>
                                        </div>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </div>
                            <!-- Subtotal Income -->
                            <div class="mt-4 pt-3 border-t flex justify-between font-bold text-gray-800">
                                <span>Total Pendapatan</span>
                                <span><?= number_format($data['salary']['gross_salary'], 0, ',', '.') ?></span>
                            </div>
                        </div>

                        <!-- POTONGAN (DEDUCTIONS) -->
                        <div>
                            <h3 class="text-sm font-bold text-gray-700 uppercase border-b-2 border-red-500 pb-2 mb-4 flex justify-between">
                                <span>Potongan</span>
                                <span class="text-red-600">-</span>
                            </h3>
                            <div class="space-y-3">
                                <!-- Potongan Regular -->
                                <?php if(isset($data['details']['deduction'])): ?>
                                    <?php foreach($data['details']['deduction'] as $item): ?>
                                        <div class="flex justify-between text-sm">
                                            <span class="text-gray-600"><?= $item['component_name'] ?></span>
                                            <span class="font-medium text-red-600">(<?= number_format($item['amount'], 0, ',', '.') ?>)</span>
                                        </div>
                                    <?php endforeach; ?>
                                <?php endif; ?>

                                <!-- Pajak PPh21 -->
                                <?php if($data['salary']['total_tax'] > 0): ?>
                                    <div class="flex justify-between text-sm">
                                        <span class="text-gray-600">PPh 21</span>
                                        <span class="font-medium text-red-600">(<?= number_format($data['salary']['total_tax'], 0, ',', '.') ?>)</span>
                                    </div>
                                <?php endif; ?>
                            </div>
                            <!-- Subtotal Potongan -->
                            <div class="mt-4 pt-3 border-t flex justify-between font-bold text-gray-800">
                                <span>Total Potongan</span>
                                <span class="text-red-600">(<?= number_format($data['salary']['total_deductions'], 0, ',', '.') ?>)</span>
                            </div>
                        </div>

                    </div>

                    <!-- TOTAL TAKE HOME PAY -->
                    <div class="bg-blue-50 p-6 flex flex-col md:flex-row justify-between items-center border-t">
                        <div class="text-center md:text-left mb-4 md:mb-0">
                            <span class="text-sm text-blue-600 font-bold uppercase tracking-wider block">Gaji Bersih (Take Home Pay)</span>
                            <span class="text-xs text-gray-500">Dibayarkan pada: <?= $data['salary']['paid_at'] ? date('d M Y', strtotime($data['salary']['paid_at'])) : '-' ?></span>
                        </div>
                        <div class="text-3xl font-black text-blue-800">
                            Rp <?= number_format($data['salary']['net_salary'], 0, ',', '.') ?>
                        </div>
                    </div>

                    <!-- FOOTER ACTIONS -->
                    <div class="p-4 bg-gray-100 flex justify-end gap-3">
                        <button onclick="window.print()" class="px-4 py-2 bg-white border border-gray-300 text-gray-700 rounded-lg shadow-sm hover:bg-gray-50 text-sm font-medium flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                            Print / PDF
                        </button>
                    </div>

                </div>
            <?php endif; ?>

        </div>

        <!-- KOLOM KANAN: WIDGETS -->
        <div class="lg:col-span-4 space-y-6">
            
            <!-- WIDGET A: HISTORY BULAN -->
            <div class="bg-white rounded-xl shadow-sm border p-5">
                <h3 class="text-gray-800 font-bold mb-4 text-sm uppercase tracking-wide border-b pb-2">Arsip Slip Gaji</h3>
                <div class="space-y-2 max-h-[300px] overflow-y-auto custom-scrollbar">
                    <?php if(empty($data['history'])): ?>
                        <p class="text-sm text-gray-400 italic">Belum ada riwayat gaji.</p>
                    <?php else: ?>
                        <?php foreach($data['history'] as $hist): ?>
                            <?php 
                                $isActive = (isset($_GET['id']) && $_GET['id'] == $hist['transaction_id']) || (!isset($_GET['id']) && $hist === reset($data['history']));
                                $activeClass = $isActive ? 'bg-blue-50 border-blue-500 text-blue-700' : 'bg-gray-50 border-transparent hover:bg-gray-100 text-gray-600';
                            ?>
                            <a href="<?= BASEURL ?>/staff/payroll?id=<?= $hist['transaction_id'] ?>" class="block px-4 py-3 rounded-lg border-l-4 <?= $activeClass ?> transition flex justify-between items-center group">
                                <div>
                                    <span class="block font-bold text-sm"><?= $hist['period_name'] ?></span>
                                    <span class="text-xs opacity-70">Rp <?= number_format($hist['net_salary'], 0, ',', '.') ?></span>
                                </div>
                                <svg class="w-4 h-4 opacity-0 group-hover:opacity-100 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                            </a>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>

            <!-- WIDGET B: SUMMARY TAHUNAN -->
            <div class="bg-blue-900 rounded-xl shadow-lg p-6 text-white">
                <h3 class="text-blue-200 text-xs font-bold uppercase mb-4">Ringkasan Tahun <?= date('Y') ?></h3>
                
                <div class="mb-4">
                    <span class="text-blue-300 text-sm block">Total Pendapatan Bersih</span>
                    <span class="text-2xl font-bold">Rp <?= number_format($data['stats']['total_net'] ?? 0, 0, ',', '.') ?></span>
                </div>

                <div class="grid grid-cols-2 gap-4 mt-6 border-t border-blue-800 pt-4">
                    <div>
                        <span class="text-blue-300 text-xs block">Total Lembur</span>
                        <span class="font-semibold text-sm">Rp <?= number_format($data['stats']['total_ot'] ?? 0, 0, ',', '.') ?></span>
                    </div>
                    <div>
                        <span class="text-blue-300 text-xs block">Total Potongan</span>
                        <span class="font-semibold text-sm">Rp <?= number_format($data['stats']['total_cut'] ?? 0, 0, ',', '.') ?></span>
                    </div>
                </div>
            </div>

        </div>

    </div>
</div>

<!-- Simple Print CSS -->
<style>
    @media print {
        body * { visibility: hidden; }
        .lg\:col-span-8, .lg\:col-span-8 * { visibility: visible; }
        .lg\:col-span-8 { position: absolute; left: 0; top: 0; width: 100%; }
        button { display: none !important; }
        .bg-gray-800 { background-color: #1f2937 !important; -webkit-print-color-adjust: exact; }
    }
</style>
