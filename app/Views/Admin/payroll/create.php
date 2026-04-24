<div class="min-h-[80vh] flex items-center justify-center bg-gray-50 p-6">
    <div class="bg-white w-full max-w-lg rounded-2xl shadow-xl border border-gray-100 overflow-hidden">
        
        <div class="bg-indigo-600 p-8 text-center">
            <div class="w-16 h-16 bg-white/20 rounded-full flex items-center justify-center mx-auto mb-4 backdrop-blur-sm">
                <i class="fas fa-calculator text-3xl text-white"></i>
            </div>
            <h2 class="text-2xl font-bold text-white mb-1">Generate Payroll</h2>
            <p class="text-indigo-100 text-sm">Hitung otomatis gaji, lembur & potongan.</p>
        </div>

        <form action="<?= BASEURL ?>/admin/payroll/generate" method="POST" class="p-8">
            <div class="mb-6">
                <label class="block text-sm font-bold text-gray-700 mb-2 uppercase tracking-wider">Pilih Periode Bulan</label>
                <input type="month" name="month" value="<?= date('Y-m') ?>" required 
                       class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:ring-4 focus:ring-indigo-100 focus:border-indigo-500 font-bold text-gray-800 transition outline-none">
                <p class="text-xs text-gray-500 mt-2">
                    <i class="fas fa-info-circle"></i> Sistem akan mengambil data Absensi & Lembur bulan tersebut.
                </p>
            </div>

            <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 mb-8 rounded-r-lg">
                <div class="flex gap-3">
                    <i class="fas fa-exclamation-triangle text-yellow-600 mt-1"></i>
                    <div class="text-sm text-yellow-800">
                        <strong>Perhatian:</strong> Jika payroll untuk bulan ini sudah ada, data lama akan <span class="underline">dihapus dan dihitung ulang</span>.
                    </div>
                </div>
            </div>

            <div class="flex flex-col gap-3">
                <button type="submit" class="w-full py-3.5 bg-indigo-600 text-white rounded-xl font-bold hover:bg-indigo-700 shadow-lg transform active:scale-95 transition flex justify-center items-center gap-2">
                    <i class="fas fa-cogs"></i> Proses Hitung Sekarang
                </button>
                <a href="<?= BASEURL ?>/admin/payroll" class="w-full py-3.5 bg-white text-gray-600 border border-gray-300 rounded-xl font-bold hover:bg-gray-50 text-center transition">
                    Batal
                </a>
            </div>
        </form>
    </div>
</div>