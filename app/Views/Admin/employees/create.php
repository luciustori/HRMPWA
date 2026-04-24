<div class="max-w-5xl mx-auto" x-data="{ activeTab: 'identitas' }">

    <div class="flex flex-col md:flex-row items-center justify-between gap-6 mb-8">
        <div>
            <h1 class="text-2xl font-bold text-slate-900 tracking-tight">Tambah Pegawai Baru</h1>
            <p class="text-slate-500 text-sm mt-1">Lengkapi formulir di bawah untuk mendaftarkan karyawan.</p>
        </div>

        <div class="bg-slate-100 p-1.5 rounded-xl inline-flex shadow-inner border border-slate-200/50">
            <button @click="activeTab = 'identitas'" 
                :class="activeTab === 'identitas' ? 'bg-slate-800 text-white shadow-lg' : 'text-slate-500 hover:text-slate-700 hover:bg-white/60'"
                class="px-5 py-2 rounded-lg text-sm font-bold transition-all duration-300 flex items-center gap-2">
                <i class="ri-id-card-line"></i> Identitas
            </button>
            <button @click="activeTab = 'kepegawaian'" 
                :class="activeTab === 'kepegawaian' ? 'bg-slate-800 text-white shadow-lg' : 'text-slate-500 hover:text-slate-700 hover:bg-white/60'"
                class="px-5 py-2 rounded-lg text-sm font-bold transition-all duration-300 flex items-center gap-2">
                <i class="ri-briefcase-line"></i> Karir
            </button>
            <button @click="activeTab = 'kontak'" 
                :class="activeTab === 'kontak' ? 'bg-slate-800 text-white shadow-lg' : 'text-slate-500 hover:text-slate-700 hover:bg-white/60'"
                class="px-5 py-2 rounded-lg text-sm font-bold transition-all duration-300 flex items-center gap-2">
                <i class="ri-contacts-line"></i> Kontak
            </button>
            <button @click="activeTab = 'payroll'" 
                :class="activeTab === 'payroll' ? 'bg-slate-800 text-white shadow-lg' : 'text-slate-500 hover:text-slate-700 hover:bg-white/60'"
                class="px-5 py-2 rounded-lg text-sm font-bold transition-all duration-300 flex items-center gap-2">
                <i class="ri-bank-card-line"></i> Payroll
            </button>
        </div>
    </div>

    <div class="bg-white rounded-3xl shadow-xl shadow-slate-200/50 border border-slate-100 overflow-hidden relative min-h-[500px]">
        
        <div class="absolute top-0 right-0 w-96 h-96 bg-indigo-50/40 rounded-full blur-3xl -z-10 translate-x-1/3 -translate-y-1/3"></div>

        <form action="<?= BASEURL ?>/admin/employees/store" method="POST" class="p-8">
            
            <div x-show="activeTab === 'identitas'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-2" x-transition:enter-end="opacity-100 translate-y-0">
                <h3 class="text-lg font-bold text-slate-800 mb-6 border-b pb-3 flex items-center gap-2">
                    <span class="w-8 h-8 rounded-lg bg-indigo-100 text-indigo-600 flex items-center justify-center"><i class="ri-user-line"></i></span>
                    Informasi Pribadi
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase mb-2">Nomor Induk (NIK)</label>
                        <input type="text" name="employee_number" placeholder="Ex: EMP-2024-001" required class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm font-mono font-bold focus:ring-2 focus:ring-indigo-500 outline-none transition">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase mb-2">No. KTP (Identity)</label>
                        <input type="number" name="identity_number" required class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-indigo-500 outline-none transition">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase mb-2">Nama Depan</label>
                        <input type="text" name="first_name" required class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm font-bold text-slate-800 focus:ring-2 focus:ring-indigo-500 outline-none transition">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase mb-2">Nama Belakang</label>
                        <input type="text" name="last_name" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm font-bold text-slate-800 focus:ring-2 focus:ring-indigo-500 outline-none transition">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase mb-2">Tempat, Tanggal Lahir</label>
                        <input type="date" name="date_of_birth" required class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-indigo-500 outline-none transition">
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-slate-500 uppercase mb-2">Gender</label>
                            <select name="gender" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-indigo-500 outline-none transition">
                                <option value="male">Laki-laki</option>
                                <option value="female">Perempuan</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-500 uppercase mb-2">Status Nikah</label>
                            <select name="marital_status" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-indigo-500 outline-none transition">
                                <option value="single">Lajang</option>
                                <option value="married">Menikah</option>
                            </select>
                        </div>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase mb-2">Jumlah Tanggungan</label>
                        <input type="number" name="number_of_dependents" value="0" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-indigo-500 outline-none transition">
                    </div>
                </div>
                <div class="flex justify-end mt-8">
                    <button type="button" @click="activeTab = 'kepegawaian'" class="text-indigo-600 font-bold text-sm hover:underline">Lanjut ke Karir &rarr;</button>
                </div>
            </div>

            <div x-show="activeTab === 'kepegawaian'" style="display: none;" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-2" x-transition:enter-end="opacity-100 translate-y-0">
                <h3 class="text-lg font-bold text-slate-800 mb-6 border-b pb-3 flex items-center gap-2">
                    <span class="w-8 h-8 rounded-lg bg-blue-100 text-blue-600 flex items-center justify-center"><i class="ri-building-line"></i></span>
                    Struktur Organisasi
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase mb-2">Departemen</label>
                        <select name="department_id" id="department_select"  class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-indigo-500 outline-none transition">
                            <option value="">-- Pilih Departemen --</option>
                            <?php foreach($departments as $dept): ?>
                                <option value="<?= $dept['id'] ?>"><?= $dept['department_name'] ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase mb-2">Divisi (Sub-Bagian)</label>
                        <select name="division_id" id="division_select" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-indigo-500 outline-none transition">
                            <option value="">-- Pilih Departemen Dulu --</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase mb-2">Posisi / Jabatan</label>
                        <input type="text" name="position" placeholder="Ex: Staff IT" required class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-indigo-500 outline-none transition">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase mb-2">Level Karyawan</label>
                        <select name="employee_level" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-indigo-500 outline-none transition">
                            <option value="direktur">Direktur</option>
                            <option value="manager">Manager</option>
                            <option value="supervisor">Supervisor</option>
                            <option value="staff">Staff</option>
                            <option value="harian">Harian / Kontrak</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase mb-2">Tanggal Bergabung</label>
                        <input type="date" name="hire_date" value="<?= date('Y-m-d') ?>" required class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-indigo-500 outline-none transition">
                    </div>
                </div>
            </div>

            <div x-show="activeTab === 'kontak'" style="display: none;" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-2" x-transition:enter-end="opacity-100 translate-y-0">
                <h3 class="text-lg font-bold text-slate-800 mb-6 border-b pb-3 flex items-center gap-2">
                    <span class="w-8 h-8 rounded-lg bg-orange-100 text-orange-600 flex items-center justify-center"><i class="ri-phone-line"></i></span>
                    Kontak & Alamat
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase mb-2">Email Kantor/Pribadi</label>
                        <input type="email" name="email" required class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-indigo-500 outline-none transition">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase mb-2">No. Handphone (WA)</label>
                        <input type="text" name="phone" required class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-indigo-500 outline-none transition">
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-xs font-bold text-slate-500 uppercase mb-2">Alamat Domisili Lengkap</label>
                        <textarea name="address" rows="3" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-indigo-500 outline-none transition"></textarea>
                    </div>
                </div>
            </div>

            <div x-show="activeTab === 'payroll'" style="display: none;" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-2" x-transition:enter-end="opacity-100 translate-y-0">
                <h3 class="text-lg font-bold text-slate-800 mb-6 border-b pb-3 flex items-center gap-2">
                    <span class="w-8 h-8 rounded-lg bg-emerald-100 text-emerald-600 flex items-center justify-center"><i class="ri-wallet-3-line"></i></span>
                    Pengaturan Gaji
                </h3>
                
                <div class="bg-indigo-50 border border-indigo-100 rounded-2xl p-6 mb-6">
                    <label class="block text-xs font-bold text-indigo-800 uppercase mb-2">Golongan Gaji (Salary Grade)</label>
                    <select name="salary_grade_id" class="w-full bg-white border border-indigo-200 rounded-xl px-4 py-3 text-sm font-bold text-indigo-900 focus:ring-2 focus:ring-indigo-500 outline-none transition cursor-pointer">
                        <option value="">-- Pilih Golongan Gaji --</option>
                        <?php foreach($grades as $grade): ?>
                            <option value="<?= $grade['id'] ?>">
                                <?= $grade['grade_name'] ?> (<?= $grade['grade_code'] ?>) - Default: Rp <?= number_format($grade['base_salary'] ?? 0, 0, ',', '.') ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <p class="text-xs text-indigo-600 mt-2">* Komponen gaji standar akan otomatis mengikuti golongan ini.</p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase mb-2">Nama Bank</label>
                        <select name="bank_name" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-indigo-500 outline-none transition">
                            <option value="">-- Pilih Bank --</option>
                            <option value="BCA">BCA</option>
                            <option value="MANDIRI">MANDIRI</option>
                            <option value="BNI">BNI</option>
                            <option value="BRI">BRI</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase mb-2">Nomor Rekening</label>
                        <input type="text" name="bank_account_number" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-indigo-500 outline-none transition">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase mb-2">Atas Nama</label>
                        <input type="text" name="bank_account_name" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-indigo-500 outline-none transition">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase mb-2">Nomor NPWP</label>
                        <input type="text" name="npwp" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-indigo-500 outline-none transition">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase mb-2">Override Gaji Pokok (Opsional)</label>
                        <input type="text" name="salary" placeholder="0" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm font-mono font-bold focus:ring-2 focus:ring-indigo-500 outline-none transition currency-input">
                    </div>
                </div>
            </div>

            <div class="mt-10 pt-6 border-t border-slate-100 flex items-center justify-between">
                <a href="<?= BASEURL ?>/admin/employees" class="text-slate-500 hover:text-slate-800 font-medium text-sm transition flex items-center gap-2">
                    <i class="ri-arrow-left-line"></i> Batal & Kembali
                </a>
                <button type="submit" class="bg-slate-900 hover:bg-slate-800 text-white px-8 py-3.5 rounded-xl font-bold shadow-xl hover:shadow-2xl shadow-slate-200 transform hover:-translate-y-0.5 transition-all flex items-center gap-3">
                    <i class="ri-save-line text-lg text-yellow-400"></i>
                    <span>Simpan Pegawai</span>
                </button>
            </div>

        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // 1. Currency
    const inputs = document.querySelectorAll('.currency-input');
    inputs.forEach(inp => {
        inp.addEventListener('keyup', function(e) {
            let val = this.value.replace(/[^0-9]/g, '');
            if(val !== '') this.value = new Intl.NumberFormat('id-ID').format(val);
        });
    });

    // 2. Chained Dropdown (Divisi)
    const deptSelect = document.getElementById('department_select');
    const divSelect = document.getElementById('division_select');
    if(deptSelect && divSelect) {
        deptSelect.addEventListener('change', function() {
            const deptId = this.value;
            divSelect.innerHTML = '<option value="">Loading...</option>';
            if(deptId) {
                fetch(`<?= BASEURL ?>/admin/employees/get_divisions/${deptId}`)
                    .then(response => response.json())
                    .then(data => {
                        divSelect.innerHTML = '<option value="">-- Pilih Divisi --</option>';
                        data.forEach(div => {
                            divSelect.innerHTML += `<option value="${div.id}">${div.division_name}</option>`;
                        });
                    });
            } else {
                divSelect.innerHTML = '<option value="">-- Pilih Departemen Dulu --</option>';
            }
        });
        // Logic otomatis untuk Level Direktur
const levelSelect = document.getElementById('level_select'); // Pastikan id="level_select" sudah ditambah di HTML select level
const deptSelect = document.getElementById('department_select');

if(levelSelect && deptSelect) {
    const handleLevelChange = () => {
        if(levelSelect.value === 'direktur') {
            // Jika Direktur: Tidak wajib pilih departemen & disable inputnya
            deptSelect.removeAttribute('required');
            deptSelect.disabled = true;
            deptSelect.value = ""; // Kosongkan pilihan
            // Trigger change event untuk reset dropdown Divisi juga
            deptSelect.dispatchEvent(new Event('change'));
            deptSelect.classList.add('opacity-50', 'cursor-not-allowed');
        } else {
            // Jika selain Direktur: Wajib pilih departemen
            deptSelect.setAttribute('required', 'required');
            deptSelect.disabled = false;
            deptSelect.classList.remove('opacity-50', 'cursor-not-allowed');
        }
    };

    levelSelect.addEventListener('change', handleLevelChange);
    // Jalankan sekali saat halaman load (penting untuk halaman Edit)
    handleLevelChange();
}
    }
    
    
});
</script>