<div class="max-w-2xl mx-auto px-6 py-12">
    
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
        
        <div class="bg-slate-50 px-8 py-6 border-b border-slate-100 flex items-center gap-4">
            <div class="w-12 h-12 bg-indigo-100 text-indigo-600 rounded-xl flex items-center justify-center text-2xl">
                <i class="ri-database-2-line"></i>
            </div>
            <div>
                <h1 class="text-xl font-bold text-slate-800">Generate KPI Report</h1>
                <p class="text-sm text-slate-500">Sinkronisasi data Tugas & Absensi ke Laporan Bulanan</p>
            </div>
        </div>

        <form action="<?= BASEURL ?>/admin/kpigenerator/process" method="POST" class="p-8 space-y-6">
            
            <div class="grid grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">Bulan Laporan</label>
                    <select name="month" class="w-full px-4 py-2.5 rounded-lg border border-slate-300 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition text-slate-600">
                        <?php 
                        $months = [1=>'Januari', 2=>'Februari', 3=>'Maret', 4=>'April', 5=>'Mei', 6=>'Juni', 7=>'Juli', 8=>'Agustus', 9=>'September', 10=>'Oktober', 11=>'November', 12=>'Desember'];
                        $currentMonth = date('n');
                        foreach($months as $num => $name): 
                        ?>
                            <option value="<?= $num ?>" <?= $currentMonth == $num ? 'selected' : '' ?>>
                                <?= $name ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">Tahun</label>
                    <select name="year" class="w-full px-4 py-2.5 rounded-lg border border-slate-300 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition text-slate-600">
                        <?php for($y=date('Y'); $y>=2023; $y--): ?>
                            <option value="<?= $y ?>"><?= $y ?></option>
                        <?php endfor; ?>
                    </select>
                </div>
            </div>

            <div class="bg-blue-50 border border-blue-100 rounded-lg p-4 flex gap-3">
                <i class="ri-information-line text-blue-600 mt-0.5"></i>
                <div class="text-sm text-blue-800">
                    <p class="font-bold mb-1">Informasi Cut-Off:</p>
                    <p>Sistem akan mengambil data mulai tanggal <strong>25 bulan sebelumnya</strong> sampai <strong>24 bulan terpilih</strong>.</p>
                </div>
            </div>

            <button type="submit" class="w-full bg-indigo-600 text-white py-3 rounded-xl font-bold shadow hover:bg-indigo-700 transition flex items-center justify-center gap-2">
                <i class="ri-refresh-line"></i> Mulai Proses Generate
            </button>

        </form>
    </div>
</div>