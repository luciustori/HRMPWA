<div class="max-w-4xl mx-auto" x-data="{ 
    activeCategory: 'ijin', 
    selectedEmployee: '', 
    leaveBalance: 0, 
    isLoading: false,

    setCategory(cat) {
        this.activeCategory = cat;
        if(cat === 'cuti' && this.selectedEmployee) {
            this.fetchBalance();
        }
    },

    fetchBalance() {
        if(!this.selectedEmployee) return;
        this.isLoading = true;
        fetch('<?= BASEURL ?>/admin/requests/get_leave_balance/' + this.selectedEmployee)
            .then(res => res.json())
            .then(res => {
                if(res.success) {
                    this.leaveBalance = res.data.total_remaining !== undefined ? res.data.total_remaining : 12;
                } else {
                    this.leaveBalance = 0;
                }
            })
            .catch(() => { this.leaveBalance = 0; })
            .finally(() => { this.isLoading = false; });
    }
}">
    
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Buat Pengajuan</h1>
            <p class="text-sm text-gray-500">Pilih kategori pengajuan di bawah ini.</p>
        </div>
        <a href="<?= BASEURL ?>/admin/requests" class="text-gray-500 hover:text-gray-700 font-bold text-sm">
            <i class="fas fa-arrow-left"></i> Kembali
        </a>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-2 mb-6">
        <div class="flex flex-wrap gap-2">
            
            <button type="button" @click="setCategory('ijin')" 
                    :class="activeCategory === 'ijin' ? 'bg-indigo-600 text-white shadow-md' : 'bg-gray-50 text-gray-600 hover:bg-gray-100'"
                    class="flex-1 px-4 py-3 rounded-lg text-sm font-bold transition flex items-center justify-center gap-2 whitespace-nowrap">
                <i class="fas fa-user-injured"></i> Ijin
            </button>

            <button type="button" @click="setCategory('dinas_luar')" 
                    :class="activeCategory === 'dinas_luar' ? 'bg-blue-600 text-white shadow-md' : 'bg-gray-50 text-gray-600 hover:bg-gray-100'"
                    class="flex-1 px-4 py-3 rounded-lg text-sm font-bold transition flex items-center justify-center gap-2 whitespace-nowrap">
                <i class="fas fa-briefcase"></i> Dinas Luar
            </button>

            <button type="button" @click="setCategory('cuti')" 
                    :class="activeCategory === 'cuti' ? 'bg-emerald-600 text-white shadow-md' : 'bg-gray-50 text-gray-600 hover:bg-gray-100'"
                    class="flex-1 px-4 py-3 rounded-lg text-sm font-bold transition flex items-center justify-center gap-2 whitespace-nowrap">
                <i class="fas fa-umbrella-beach"></i> Cuti Tahunan
            </button>

            <button type="button" @click="setCategory('sppd')" 
                    :class="activeCategory === 'sppd' ? 'bg-purple-600 text-white shadow-md' : 'bg-gray-50 text-gray-600 hover:bg-gray-100'"
                    class="flex-1 px-4 py-3 rounded-lg text-sm font-bold transition flex items-center justify-center gap-2 whitespace-nowrap">
                <i class="fas fa-plane-departure"></i> SPPD
            </button>

            <button type="button" @click="setCategory('lembur')" 
                    :class="activeCategory === 'lembur' ? 'bg-orange-600 text-white shadow-md' : 'bg-gray-50 text-gray-600 hover:bg-gray-100'"
                    class="flex-1 px-4 py-3 rounded-lg text-sm font-bold transition flex items-center justify-center gap-2 whitespace-nowrap">
                <i class="fas fa-stopwatch"></i> Lembur
            </button>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-lg border border-gray-100 overflow-hidden">
        
        <form action="<?= BASEURL ?>/admin/requests/store" method="POST" enctype="multipart/form-data" class="p-8">
            <input type="hidden" name="category" x-model="activeCategory">

            <div class="mb-8 bg-gray-50 p-4 rounded-lg border border-gray-200">
                <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Pilih Karyawan <span class="text-red-500">*</span></label>
                <select name="employee_id" x-model="selectedEmployee" @change="fetchBalance()" class="w-full border-gray-300 rounded-lg p-2.5 focus:ring-2 focus:ring-indigo-500" required>
                    <option value="">-- Pilih Nama Karyawan --</option>
                    <?php foreach($employees as $emp): ?>
                        <option value="<?= $emp['id'] ?>"><?= $emp['first_name'] . ' ' . $emp['last_name'] ?></option>
                    <?php endforeach; ?>
                </select>
                
                <div x-show="activeCategory === 'cuti' && selectedEmployee" class="mt-3 flex items-center gap-2 text-sm animate-pulse" style="display: none;">
                    <span class="text-gray-500">Sisa Jatah Cuti:</span>
                    <span x-text="isLoading ? '...' : leaveBalance + ' Hari'" class="font-bold px-2 py-0.5 rounded bg-emerald-100 text-emerald-700"></span>
                </div>
            </div>

            <div x-show="activeCategory === 'ijin'" class="space-y-5">
                <div class="p-4 bg-indigo-50 border-l-4 border-indigo-500 text-indigo-700 text-sm mb-4 rounded-r">
                    <i class="fas fa-info-circle mr-1"></i> Form untuk Sakit, Keperluan Keluarga, atau Ijin Sosial.
                </div>
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Jenis Ijin</label>
                    <select name="leave_type_id" :disabled="activeCategory !== 'ijin'" class="w-full px-3 py-2 border rounded-lg border-slate-300 focus:ring-2 focus:ring-purple-500">
                        <?php foreach($permit_types as $pt): ?>
                            <option value="<?= $pt['id'] ?>"><?= $pt['leave_type_name'] ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="grid grid-cols-2 gap-6">
                    <div><label class="block text-sm font-bold text-gray-700 mb-2">Mulai</label><input type="date" name="start_date" :disabled="activeCategory !== 'ijin'" class="w-full px-3 py-2 border rounded-lg border-slate-300 focus:ring-2 focus:ring-purple-500" required></div>
                    <div><label class="block text-sm font-bold text-gray-700 mb-2">Sampai</label><input type="date" name="end_date" :disabled="activeCategory !== 'ijin'" class="w-full px-3 py-2 border rounded-lg border-slate-300 focus:ring-2 focus:ring-purple-500" required></div>
                </div>
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Alasan</label>
                    <textarea name="reason" :disabled="activeCategory !== 'ijin'" rows="2" class="w-full px-3 py-2 border rounded-lg border-slate-300 focus:ring-2 focus:ring-purple-500" required></textarea>
                </div>
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Upload Bukti</label>
                    <input type="file" name="document" :disabled="activeCategory !== 'ijin'" class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100"/>
                </div>
            </div>

            <div x-show="activeCategory === 'dinas_luar'" class="space-y-5" style="display: none;">
                <div class="p-4 bg-blue-50 border-l-4 border-blue-500 text-blue-700 text-sm mb-4 rounded-r">
                    <i class="fas fa-briefcase mr-1"></i> Bekerja di luar kantor tanpa menginap.
                </div>
                <input type="hidden" name="leave_type_id" value="<?= $duty_leave['id'] ?? '' ?>" :disabled="activeCategory !== 'dinas_luar'">
                <div class="grid grid-cols-2 gap-6">
                    <div><label class="block text-sm font-bold text-gray-700 mb-2">Tanggal</label><input type="date" name="start_date" :disabled="activeCategory !== 'dinas_luar'" class="w-full px-3 py-2 border rounded-lg border-slate-300 focus:ring-2 focus:ring-purple-500" required></div>
                    <div><label class="block text-sm font-bold text-gray-700 mb-2">Sampai</label><input type="date" name="end_date" :disabled="activeCategory !== 'dinas_luar'" class="w-full px-3 py-2 border rounded-lg border-slate-300 focus:ring-2 focus:ring-purple-500" required></div>
                </div>
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Lokasi & Keperluan</label>
                    <textarea name="reason" :disabled="activeCategory !== 'dinas_luar'" rows="3" class="w-full px-3 py-2 border rounded-lg border-slate-300 focus:ring-2 focus:ring-purple-500" required></textarea>
                </div>
            </div>

            <div x-show="activeCategory === 'cuti'" class="space-y-5" style="display: none;">
                <div class="p-4 bg-emerald-50 border-l-4 border-emerald-500 text-emerald-700 text-sm mb-4 rounded-r">
                    <i class="fas fa-umbrella-beach mr-1"></i> Memotong jatah cuti tahunan.
                </div>
                <input type="hidden" name="leave_type_id" value="<?= $annual_leave['id'] ?? '' ?>" :disabled="activeCategory !== 'cuti'">
                <div class="grid grid-cols-2 gap-6">
                    <div><label class="block text-sm font-bold text-gray-700 mb-2">Mulai</label><input type="date" name="start_date" :disabled="activeCategory !== 'cuti'" class="w-full px-3 py-2 border rounded-lg border-slate-300 focus:ring-2 focus:ring-purple-500" required></div>
                    <div><label class="block text-sm font-bold text-gray-700 mb-2">Selesai</label><input type="date" name="end_date" :disabled="activeCategory !== 'cuti'" class="w-full px-3 py-2 border rounded-lg border-slate-300 focus:ring-2 focus:ring-purple-500" required></div>
                </div>
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Keperluan</label>
                    <textarea name="reason" :disabled="activeCategory !== 'cuti'" rows="3" class="w-full px-3 py-2 border rounded-lg border-slate-300 focus:ring-2 focus:ring-purple-500" required></textarea>
                </div>
            </div>

            <div x-show="activeCategory === 'sppd'" class="space-y-5" style="display: none;">
                <div class="p-4 bg-purple-50 border-l-4 border-purple-500 text-purple-700 text-sm mb-4 rounded-r">
                    <i class="fas fa-plane mr-1"></i> Perjalanan Dinas Luar Kota. <strong>Wajib Upload Surat Tugas</strong>.
                </div>
                <div class="grid grid-cols-2 gap-6">
                    <div><label class="block text-sm font-bold text-gray-700 mb-2">Tujuan</label><input type="text" name="destination" :disabled="activeCategory !== 'sppd'" class="w-full px-3 py-2 border rounded-lg border-slate-300 focus:ring-2 focus:ring-purple-500" required></div>
                    <div><label class="block text-sm font-bold text-gray-700 mb-2">Estimasi Biaya</label><input type="text" name="estimated_budget" :disabled="activeCategory !== 'sppd'" class="w-full px-3 py-2 border rounded-lg border-slate-300 focus:ring-2 focus:ring-purple-500" required></div>
                </div>
                <div class="grid grid-cols-2 gap-6">
                    <div><label class="block text-sm font-bold text-gray-700 mb-2">Berangkat</label><input type="date" name="start_date" :disabled="activeCategory !== 'sppd'" class="w-full px-3 py-2 border rounded-lg border-slate-300 focus:ring-2 focus:ring-purple-500" required></div>
                    <div><label class="block text-sm font-bold text-gray-700 mb-2">Pulang</label><input type="date" name="end_date" :disabled="activeCategory !== 'sppd'" class="w-full px-3 py-2 border rounded-lg border-slate-300 focus:ring-2 focus:ring-purple-500" required></div>
                </div>
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Keperluan</label>
                    <textarea name="trip_purpose" :disabled="activeCategory !== 'sppd'" rows="2" class="w-full px-3 py-2 border rounded-lg border-slate-300 focus:ring-2 focus:ring-purple-500" required></textarea>
                </div>
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Upload Surat Tugas <span class="text-red-500">*</span></label>
                    <input type="file" name="document" :disabled="activeCategory !== 'sppd'" class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:bg-purple-50 file:text-purple-700 hover:file:bg-purple-100" required/>
                </div>
            </div>

            <div x-show="activeCategory === 'lembur'" class="space-y-5" style="display: none;">
                <div class="p-4 bg-orange-50 border-l-4 border-orange-500 text-orange-700 text-sm mb-4 rounded-r">
                    <i class="fas fa-stopwatch mr-1"></i> Pengajuan Lembur (Overtime).
                </div>
                <div><label class="block text-sm font-bold text-gray-700 mb-2">Tanggal</label><input type="date" name="overtime_date" :disabled="activeCategory !== 'lembur'" class="w-full px-3 py-2 border rounded-lg border-slate-300 focus:ring-2 focus:ring-purple-500" required></div>
                <div class="grid grid-cols-2 gap-6">
                    <div><label class="block text-sm font-bold text-gray-700 mb-2">Mulai</label><input type="time" name="start_time" :disabled="activeCategory !== 'lembur'" class="w-full px-3 py-2 border rounded-lg border-slate-300 focus:ring-2 focus:ring-purple-500" required></div>
                    <div><label class="block text-sm font-bold text-gray-700 mb-2">Selesai</label><input type="time" name="end_time" :disabled="activeCategory !== 'lembur'" class="w-full px-3 py-2 border rounded-lg border-slate-300 focus:ring-2 focus:ring-purple-500" required></div>
                </div>
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Pekerjaan</label>
                    <textarea name="reason" :disabled="activeCategory !== 'lembur'" rows="2" class="w-full px-3 py-2 border rounded-lg border-slate-300 focus:ring-2 focus:ring-purple-500" required></textarea>
                </div>
            </div>

            <div class="mt-8 pt-6 border-t border-gray-100 flex justify-end gap-3">
                <button type="button" onclick="history.back()" class="px-6 py-2.5 text-gray-600 font-bold hover:bg-gray-100 rounded-lg transition">Batal</button>
                <button type="submit" class="px-8 py-2.5 bg-gray-800 text-white font-bold rounded-lg hover:bg-gray-900 shadow-lg transform active:scale-95 transition flex items-center gap-2">
                    <i class="fas fa-paper-plane"></i> Ajukan & Setujui
                </button>
            </div>
        </form>
    </div>
</div>

<script src="//unpkg.com/alpinejs" defer></script>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<?php if (isset($_SESSION['flash'])): ?>
    <script>
        Swal.fire({
            icon: '<?= $_SESSION['flash']['tipe'] === "danger" ? "error" : $_SESSION['flash']['tipe'] ?>',
            title: '<?= $_SESSION['flash']['pesan'] ?>',
            text: '<?= $_SESSION['flash']['aksi'] ?>',
            confirmButtonColor: '#4f46e5'
        });
    </script>
    <?php unset($_SESSION['flash']); ?>
<?php elseif (isset($_SESSION['flash_message'])): ?>
    <script>
        Swal.fire({
            icon: 'info',
            title: 'Informasi',
            text: '<?= $_SESSION['flash_message'] ?>',
            confirmButtonColor: '#4f46e5'
        });
    </script>
    <?php unset($_SESSION['flash_message']); ?>
<?php endif; ?>