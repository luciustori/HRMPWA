<div class="max-w-5xl mx-auto" x-data="{ activeTab: 'identitas' }">

    <div class="flex flex-col md:flex-row items-center justify-between gap-6 mb-8">
        <div>
            <h1 class="text-2xl font-bold text-slate-900 tracking-tight">Edit Karyawan</h1>
            <p class="text-slate-500 text-sm mt-1">
                Update data untuk: <span class="font-bold text-indigo-600"><?= htmlspecialchars($employee['first_name'] . ' ' . $employee['last_name']) ?></span>
            </p>
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

        <form action="<?= BASEURL ?>/admin/employees/update/<?= $employee['id'] ?>" method="POST" class="p-8">
            <input type="hidden" name="id" value="<?= $employee['id'] ?>">

            <div x-show="activeTab === 'identitas'" x-transition:enter="transition ease-out duration-300">
                <h3 class="text-lg font-bold text-slate-800 mb-6 border-b pb-3 flex items-center gap-2">
                    <span class="w-8 h-8 rounded-lg bg-indigo-100 text-indigo-600 flex items-center justify-center"><i class="ri-user-line"></i></span>
                    Informasi Pribadi
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase mb-2">NIK</label>
                        <input type="text" name="employee_number" value="<?= $employee['employee_number'] ?? $employee['nik'] ?? '' ?>" required class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm font-mono font-bold">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase mb-2">No KTP</label>
                        <input type="text" name="identity_number" value="<?= $employee['identity_number'] ?? '' ?>" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase mb-2">Nama Depan</label>
                        <input type="text" name="first_name" value="<?= $employee['first_name'] ?? '' ?>" required class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm font-bold">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase mb-2">Nama Belakang</label>
                        <input type="text" name="last_name" value="<?= $employee['last_name'] ?? '' ?>" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm font-bold">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase mb-2">Tgl Lahir</label>
                        <input type="date" name="date_of_birth" value="<?= $employee['date_of_birth'] ?? '' ?>" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm">
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-slate-500 uppercase mb-2">Gender</label>
                            <select name="gender" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm">
                                <option value="male" <?= ($employee['gender']??'')=='male'?'selected':'' ?>>Laki-laki</option>
                                <option value="female" <?= ($employee['gender']??'')=='female'?'selected':'' ?>>Perempuan</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-500 uppercase mb-2">Status Nikah</label>
                            <select name="marital_status" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm">
                                <option value="single" <?= ($employee['marital_status']??'')=='single'?'selected':'' ?>>Lajang</option>
                                <option value="married" <?= ($employee['marital_status']??'')=='married'?'selected':'' ?>>Menikah</option>
                            </select>
                        </div>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase mb-2">Jml Anak</label>
                        <input type="number" name="number_of_dependents" value="<?= $employee['number_of_dependents'] ?? 0 ?>" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm">
                    </div>
                </div>
            </div>

            <div x-show="activeTab === 'kepegawaian'" style="display: none;" x-transition:enter="transition ease-out duration-300">
                <h3 class="text-lg font-bold text-slate-800 mb-6 border-b pb-3 flex items-center gap-2">
                    <span class="w-8 h-8 rounded-lg bg-blue-100 text-blue-600 flex items-center justify-center"><i class="ri-building-line"></i></span>
                    Struktur Organisasi
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase mb-2">Departemen</label>
                        <select name="department_id" id="department_select" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm">
                            <option value="">-- Pilih --</option>
                            <?php foreach($departments as $dept): ?>
                                <option value="<?= $dept['id'] ?>" <?= ($employee['department_id']??0) == $dept['id'] ? 'selected' : '' ?>>
                                    <?= $dept['department_name'] ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase mb-2">Divisi</label>
                        <select name="division_id" id="division_select" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm">
                            <option value="">-- Pilih --</option>
                            <?php foreach($divisions as $div): ?>
                                <option value="<?= $div['id'] ?>" <?= ($employee['division_id']??0) == $div['id'] ? 'selected' : '' ?>>
                                    <?= $div['division_name'] ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase mb-2">Posisi</label>
                        <input type="text" name="position" value="<?= $employee['position'] ?? '' ?>" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase mb-2">Level</label>
                        <select name="employee_level" id="level_select" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm">
                        <option value="direktur" <?= ($employee['employee_level']??'')=='direktur'?'selected':'' ?>>Direktur</option>
                        <option value="manager" <?= ($employee['employee_level']??'')=='manager'?'selected':'' ?>>Manager</option>
                        <option value="supervisor" <?= ($employee['employee_level']??'')=='supervisor'?'selected':'' ?>>Supervisor</option>
                        <option value="staff" <?= ($employee['employee_level']??'')=='staff'?'selected':'' ?>>Staff</option>
                        <option value="harian" <?= ($employee['employee_level']??'')=='harian'?'selected':'' ?>>Harian</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase mb-2">Status Aktif</label>
                        <select name="is_active" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm font-bold">
                            <option value="1" <?= ($employee['is_active']??1)==1?'selected':'' ?>>Active</option>
                            <option value="0" <?= ($employee['is_active']??1)==0?'selected':'' ?>>Inactive</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase mb-2">Tgl Gabung</label>
                        <input type="date" name="hire_date" value="<?= $employee['hire_date'] ?? '' ?>" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm">
                    </div>
                </div>
            </div>

            <div x-show="activeTab === 'kontak'" style="display: none;" x-transition:enter="transition ease-out duration-300">
                <h3 class="text-lg font-bold text-slate-800 mb-6 border-b pb-3 flex items-center gap-2">
                    <span class="w-8 h-8 rounded-lg bg-orange-100 text-orange-600 flex items-center justify-center"><i class="ri-phone-line"></i></span>
                    Kontak
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase mb-2">Email</label>
                        <input type="email" name="email" value="<?= $employee['email'] ?? '' ?>" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase mb-2">No HP</label>
                        <input type="text" name="phone" value="<?= $employee['phone'] ?? $employee['phone_number'] ?? $employee['no_hp'] ?? '' ?>" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm">
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-xs font-bold text-slate-500 uppercase mb-2">Alamat Domisili</label>
                        <textarea name="address" rows="3" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm"><?= $employee['address'] ?? '' ?></textarea>
                    </div>
                </div>
            </div>

            <div x-show="activeTab === 'payroll'" style="display: none;" x-transition:enter="transition ease-out duration-300">
                <h3 class="text-lg font-bold text-slate-800 mb-6 border-b pb-3 flex items-center gap-2">
                    <span class="w-8 h-8 rounded-lg bg-emerald-100 text-emerald-600 flex items-center justify-center"><i class="ri-wallet-3-line"></i></span>
                    Gaji & Bank
                </h3>
                
                <div class="bg-indigo-50 border border-indigo-100 rounded-2xl p-6 mb-6">
                    <label class="block text-xs font-bold text-indigo-800 uppercase mb-2">Golongan Gaji</label>
                    <select name="salary_grade_id" class="w-full bg-white border border-indigo-200 rounded-xl px-4 py-3 text-sm font-bold text-indigo-900 focus:ring-2 focus:ring-indigo-500 outline-none transition">
                        <option value="">-- Manual / Tidak Ada --</option>
                        <?php foreach($grades as $grade): ?>
                            <option value="<?= $grade['id'] ?>" <?= ($employee['salary_grade_id']??0) == $grade['id'] ? 'selected' : '' ?>>
                                <?= $grade['grade_name'] ?> (<?= $grade['grade_code'] ?>)
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase mb-2">Nama Bank</label>
                        <select name="bank_name" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm">
                            <?php foreach(['BCA','MANDIRI','BNI','BRI'] as $b): ?>
                                <option value="<?= $b ?>" <?= ($employee['bank_name']??'')==$b?'selected':'' ?>><?= $b ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase mb-2">No Rekening</label>
                        <input type="text" name="bank_account_number" value="<?= $employee['bank_account_number'] ?? '' ?>" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase mb-2">Atas Nama</label>
                        <input type="text" name="bank_account_name" value="<?= $employee['bank_account_name'] ?? '' ?>" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase mb-2">NPWP</label>
                        <input type="text" name="npwp" value="<?= $employee['npwp'] ?? '' ?>" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase mb-2">Override Gaji Pokok</label>
                        <input type="text" name="salary" value="<?= number_format($employee['salary']??0, 0, ',', '.') ?>" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm font-mono font-bold currency-input">
                    </div>
                </div>
            </div>

            <div class="mt-10 pt-6 border-t border-slate-100 flex items-center justify-between">
                <a href="<?= BASEURL ?>/admin/employees" class="text-slate-500 hover:text-slate-800 font-medium text-sm transition flex items-center gap-2">
                    <i class="ri-arrow-left-line"></i> Kembali
                </a>
                <button type="submit" class="bg-slate-900 hover:bg-slate-800 text-white px-8 py-3.5 rounded-xl font-bold shadow-xl hover:shadow-2xl shadow-slate-200 transform hover:-translate-y-0.5 transition-all flex items-center gap-3">
                    <i class="ri-check-line text-lg text-emerald-400"></i>
                    <span>Simpan Perubahan</span>
                </button>
            </div>

        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // 1. FORMAT CURRENCY (Aman, tidak diubah)
    const inputs = document.querySelectorAll('.currency-input');
    inputs.forEach(inp => {
        inp.addEventListener('keyup', function(e) {
            let val = this.value.replace(/[^0-9]/g, '');
            if(val !== '') this.value = new Intl.NumberFormat('id-ID').format(val);
        });
    });

    // 2. DEKLARASI ELEMEN (Cukup panggil sekali saja!)
    const deptSelect = document.getElementById('department_select');
    const divSelect = document.getElementById('division_select');
    const levelSelect = document.getElementById('level_select'); 

    if(deptSelect && divSelect) {
        
        // 3. LOGIC AJAX FETCH DIVISI (Aman, tidak diubah)
        deptSelect.addEventListener('change', function() {
            const deptId = this.value;
            divSelect.innerHTML = '<option value="">Loading...</option>';
            if(deptId) {
                fetch(`<?= BASEURL ?>/admin/employees/get_divisions/${deptId}`)
                    .then(response => response.json())
                    .then(data => {
                        divSelect.innerHTML = '<option value="">-- Pilih --</option>';
                        data.forEach(div => {
                            const selected = div.id == '<?= $employee['division_id']??0 ?>' ? 'selected' : '';
                            divSelect.innerHTML += `<option value="${div.id}" ${selected}>${div.division_name}</option>`;
                        });
                    });
            } else {
                divSelect.innerHTML = '<option value="">-- Pilih Departemen Dulu --</option>';
            }
        });

        // 4. LOGIC OTOMATIS DIREKTUR (Diperbaiki & Disempurnakan)
        if(levelSelect) {
            const handleLevelChange = () => {
                if(levelSelect.value === 'direktur') {
                    // JIKA DIREKTUR: Hapus required, matikan dropdown, kosongkan nilai
                    deptSelect.removeAttribute('required');
                    divSelect.removeAttribute('required'); // Opsional jika divisi wajib
                    
                    deptSelect.disabled = true;
                    divSelect.disabled = true;
                    
                    deptSelect.value = ""; 
                    
                    // Trigger change biar AJAX jalan mereset divisi ke "-- Pilih Departemen Dulu --"
                    deptSelect.dispatchEvent(new Event('change'));
                    
                    // Tambahkan efek visual biar kelihatan mati
                    deptSelect.classList.add('opacity-50', 'cursor-not-allowed', 'bg-gray-100');
                    divSelect.classList.add('opacity-50', 'cursor-not-allowed', 'bg-gray-100');
                } else {
                    // JIKA SELAIN DIREKTUR: Wajib pilih departemen & nyalakan dropdown
                    deptSelect.setAttribute('required', 'required');
                    
                    deptSelect.disabled = false;
                    divSelect.disabled = false;
                    
                    // Kembalikan efek visual ke normal
                    deptSelect.classList.remove('opacity-50', 'cursor-not-allowed', 'bg-gray-100');
                    divSelect.classList.remove('opacity-50', 'cursor-not-allowed', 'bg-gray-100');
                }
            };

            levelSelect.addEventListener('change', handleLevelChange);
            
            // Jalankan sekali saat halaman load (penting untuk halaman Edit!)
            handleLevelChange();
        }
    }
});
</script>