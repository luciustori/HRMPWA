<div class="max-w-3xl mx-auto p-6">
    <div class="mb-6 flex items-center gap-3">
        <a href="<?= BASEURL ?>/admin/salary" class="text-gray-500 hover:text-gray-900 transition flex items-center gap-1 group">
            <i class="fas fa-arrow-left group-hover:-translate-x-1 transition"></i> <span class="text-sm font-semibold">Kembali</span>
        </a>
    </div>

    <div class="bg-white rounded-xl shadow-lg border border-gray-200 overflow-hidden">
        <div class="bg-gradient-to-r from-indigo-600 to-indigo-800 p-6 text-white relative overflow-hidden">
            <div class="relative z-10">
                <h2 class="text-2xl font-bold tracking-tight"><?= htmlspecialchars($employee['first_name'] . ' ' . $employee['last_name']) ?></h2>
                <div class="flex items-center gap-3 mt-2 text-indigo-100 text-sm">
                    <span class="bg-white/20 px-2 py-0.5 rounded text-white font-mono"><?= $employee['employee_number'] ?></span>
                    <span>&bull;</span>
                    <span><?= $employee['position'] ?></span>
                </div>
            </div>
            <div class="absolute right-0 top-0 h-full w-32 bg-white/5 skew-x-12"></div>
        </div>

        <form action="<?= BASEURL ?>/admin/salary/update" method="POST" class="p-6">
            <input type="hidden" name="employee_id" value="<?= $employee['id'] ?>">

            <?php if(empty($components)): ?>
                <div class="p-4 bg-yellow-50 text-yellow-700 rounded-lg text-sm flex items-center gap-2">
                    <i class="fas fa-info-circle text-lg"></i> 
                    <span>Belum ada komponen gaji tipe 'Fixed' (Gaji Pokok, dll) di Master Komponen.</span>
                </div>
            <?php else: ?>
                <div class="grid gap-6">
                    <div class="flex items-center justify-between border-b pb-3 mb-2">
                        <div>
                            <h3 class="text-lg font-bold text-gray-800">Komponen Tetap</h3>
                            <p class="text-xs text-gray-500">Fixed Allowances (Diterima setiap bulan)</p>
                        </div>
                    </div>

                    <?php foreach($components as $comp): ?>
                        <?php 
                            // Ambil value lama jika ada
                            $val = $current_salaries[$comp['id']] ?? 0;
                            // Cek apakah ini gaji pokok (biasanya ID 1)
                            $is_basic = ($comp['id'] == 1 || $comp['component_code'] == 'BASIC_SALARY');
                        ?>
                        <div class="group <?= $is_basic ? 'bg-indigo-50 p-4 rounded-xl border border-indigo-100' : '' ?>">
                            <label class="block text-sm font-semibold text-gray-700 mb-2 flex justify-between">
                                <span><?= htmlspecialchars($comp['component_name']) ?></span>
                                <span class="text-xs text-gray-400 font-mono bg-gray-100 px-2 py-0.5 rounded border">
                                    <?= $comp['component_code'] ?>
                                </span>
                            </label>
                            
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <span class="text-gray-500 font-bold">Rp</span>
                                </div>
                                <input type="text" 
                                       name="amount[<?= $comp['id'] ?>]" 
                                       value="<?= number_format($val, 0, ',', '.') ?>"
                                       class="currency-input pl-10 w-full rounded-lg border-gray-300 focus:ring-indigo-500 focus:border-indigo-500 font-mono text-lg py-2.5 transition shadow-sm"
                                       placeholder="0">
                            </div>
                            
                            <?php if($is_basic): ?>
                                <p class="text-xs text-indigo-600 mt-2 font-medium flex items-center gap-1">
                                    <i class="fas fa-info-circle"></i> Digunakan sebagai dasar perhitungan Lembur & BPJS.
                                </p>
                            <?php endif; ?>
                        </div>
                    <?php endforeach; ?>
                </div>

                <div class="mt-8 pt-6 border-t border-gray-100 flex justify-end gap-3 bg-gray-50 -mx-6 -mb-6 p-6">
                    <a href="<?= BASEURL ?>/admin/salary" class="px-5 py-2.5 rounded-lg border border-gray-300 text-gray-700 font-bold hover:bg-white transition">Batal</a>
                    <button type="submit" class="px-6 py-2.5 rounded-lg bg-indigo-600 text-white font-bold hover:bg-indigo-700 shadow-lg transform hover:-translate-y-0.5 transition flex items-center gap-2">
                        <i class="fas fa-save"></i> Simpan Perubahan
                    </button>
                </div>
            <?php endif; ?>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const inputs = document.querySelectorAll('.currency-input');
    
    inputs.forEach(inp => {
        inp.addEventListener('keyup', function(e) {
            // Hapus karakter selain angka
            let val = this.value.replace(/[^0-9]/g, '');
            
            // Format ke Ribuan Indonesia
            if(val !== '') {
                this.value = new Intl.NumberFormat('id-ID').format(val);
            } else {
                this.value = '';
            }
        });
    });
});
</script>