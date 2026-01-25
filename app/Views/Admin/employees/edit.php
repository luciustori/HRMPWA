<div class="max-w-7xl mx-auto" x-data="{ activeTab: 'identitas' }">
    
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Edit Karyawan</h1>
            <p class="text-sm text-gray-500">Update data: <span class="font-bold text-indigo-600"><?= $employee['first_name'] ?></span></p>
        </div>
        <a href="<?= BASEURL ?>/admin/employees" class="bg-white border text-gray-600 px-4 py-2 rounded-lg text-sm font-medium hover:bg-gray-50 shadow-sm transition">
            <i class="fas fa-arrow-left mr-2"></i> Kembali
        </a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        
        <div class="lg:col-span-2">
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                
                <div class="flex border-b border-gray-200 overflow-x-auto">
                    <button @click="activeTab = 'identitas'" :class="{'border-indigo-500 text-indigo-600': activeTab === 'identitas', 'border-transparent text-gray-500 hover:text-gray-700': activeTab !== 'identitas'}" class="px-6 py-4 text-sm font-medium border-b-2 transition">Identitas</button>
                    <button @click="activeTab = 'kepegawaian'" :class="{'border-indigo-500 text-indigo-600': activeTab === 'kepegawaian', 'border-transparent text-gray-500 hover:text-gray-700': activeTab !== 'kepegawaian'}" class="px-6 py-4 text-sm font-medium border-b-2 transition">Kepegawaian</button>
                    <button @click="activeTab = 'kontak'" :class="{'border-indigo-500 text-indigo-600': activeTab === 'kontak', 'border-transparent text-gray-500 hover:text-gray-700': activeTab !== 'kontak'}" class="px-6 py-4 text-sm font-medium border-b-2 transition">Kontak</button>
                    <button @click="activeTab = 'payroll'" :class="{'border-indigo-500 text-indigo-600': activeTab === 'payroll', 'border-transparent text-gray-500 hover:text-gray-700': activeTab !== 'payroll'}" class="px-6 py-4 text-sm font-medium border-b-2 transition">Payroll</button>
                </div>

                <form action="<?= BASEURL ?>/admin/employees/update/<?= $employee['id'] ?>" method="POST" class="p-6">
                    
                    <div x-show="activeTab === 'identitas'" class="grid grid-cols-1 md:grid-cols-2 gap-5">
                         <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">NIK</label>
                            <input type="text" name="employee_number" value="<?= $employee['employee_number'] ?>" required class="w-full rounded-lg border-gray-300 bg-gray-50">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">No KTP</label>
                            <input type="text" name="identity_number" value="<?= $employee['identity_number'] ?>" class="w-full rounded-lg border-gray-300">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Nama Depan</label>
                            <input type="text" name="first_name" value="<?= $employee['first_name'] ?>" required class="w-full rounded-lg border-gray-300">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Nama Belakang</label>
                            <input type="text" name="last_name" value="<?= $employee['last_name'] ?>" class="w-full rounded-lg border-gray-300">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal Lahir</label>
                            <input type="date" name="date_of_birth" value="<?= $employee['date_of_birth'] ?>" class="w-full rounded-lg border-gray-300">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Gender</label>
                            <select name="gender" class="w-full rounded-lg border-gray-300">
                                <option value="male" <?= $employee['gender']=='male'?'selected':'' ?>>Laki-laki</option>
                                <option value="female" <?= $employee['gender']=='female'?'selected':'' ?>>Perempuan</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Status Nikah</label>
                            <select name="marital_status" class="w-full rounded-lg border-gray-300">
                                <option value="single" <?= $employee['marital_status']=='single'?'selected':'' ?>>Lajang</option>
                                <option value="married" <?= $employee['marital_status']=='married'?'selected':'' ?>>Menikah</option>
                            </select>
                        </div>
                         <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Jml Anak</label>
                            <input type="number" name="number_of_dependents" value="<?= $employee['number_of_dependents'] ?>" class="w-full rounded-lg border-gray-300">
                        </div>
                    </div>

                    <div x-show="activeTab === 'kepegawaian'" class="grid grid-cols-1 md:grid-cols-2 gap-5" style="display:none">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Departemen</label>
                            <select name="department_id" class="w-full rounded-lg border-gray-300">
                                <?php foreach($departments as $dept): ?>
                                    <option value="<?= $dept['id'] ?>" <?= $dept['id']==$employee['department_id']?'selected':'' ?>><?= $dept['department_name'] ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Posisi</label>
                            <input type="text" name="position" value="<?= $employee['position'] ?>" class="w-full rounded-lg border-gray-300">
                        </div>
                         <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Level</label>
                            <select name="employee_level" class="w-full rounded-lg border-gray-300">
                                <option value="staff" <?= $employee['employee_level']=='staff'?'selected':'' ?>>Staff</option>
                                <option value="supervisor" <?= $employee['employee_level']=='supervisor'?'selected':'' ?>>Supervisor</option>
                                <option value="manager" <?= $employee['employee_level']=='manager'?'selected':'' ?>>Manager</option>
                                <option value="harian" <?= $employee['employee_level']=='harian'?'selected':'' ?>>Harian</option>
                            </select>
                        </div>
                         <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Status Aktif</label>
                            <select name="is_active" class="w-full rounded-lg border-gray-300 font-bold text-gray-700">
                                <option value="1" <?= $employee['is_active']==1?'selected':'' ?>>Aktif (Active)</option>
                                <option value="0" <?= $employee['is_active']==0?'selected':'' ?>>Non-Aktif</option>
                            </select>
                        </div>
                         <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Tgl Gabung</label>
                            <input type="date" name="hire_date" value="<?= $employee['hire_date'] ?>" class="w-full rounded-lg border-gray-300">
                        </div>
                    </div>

                    <div x-show="activeTab === 'kontak'" class="grid grid-cols-1 md:grid-cols-2 gap-5" style="display:none">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                            <input type="email" name="email" value="<?= $employee['email'] ?>" class="w-full rounded-lg border-gray-300">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">No HP</label>
                            <input type="text" name="phone" value="<?= $employee['phone'] ?>" class="w-full rounded-lg border-gray-300">
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Alamat Domisili</label>
                            <textarea name="address" rows="3" class="w-full rounded-lg border-gray-300"><?= $employee['address'] ?></textarea>
                        </div>
                    </div>

                    <div x-show="activeTab === 'payroll'" class="grid grid-cols-1 md:grid-cols-2 gap-5" style="display:none">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Nama Bank</label>
                            <select name="bank_name" class="w-full rounded-lg border-gray-300">
                                <option value="">-- Pilih --</option>
                                <option value="BCA" <?= $employee['bank_name']=='BCA'?'selected':'' ?>>BCA</option>
                                <option value="MANDIRI" <?= $employee['bank_name']=='MANDIRI'?'selected':'' ?>>Mandiri</option>
                                <option value="BNI" <?= $employee['bank_name']=='BNI'?'selected':'' ?>>BNI</option>
                                <option value="BRI" <?= $employee['bank_name']=='BRI'?'selected':'' ?>>BRI</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">No Rekening</label>
                            <input type="text" name="bank_account_number" value="<?= $employee['bank_account_number'] ?>" class="w-full rounded-lg border-gray-300">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Atas Nama</label>
                            <input type="text" name="bank_account_name" value="<?= $employee['bank_account_name'] ?>" class="w-full rounded-lg border-gray-300">
                        </div>
                         <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">NPWP</label>
                            <input type="text" name="npwp" value="<?= $employee['npwp'] ?>" class="w-full rounded-lg border-gray-300">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Gaji Pokok</label>
                            <input type="text" name="salary" value="<?= number_format($employee['salary'],0,',','.') ?>" class="w-full rounded-lg border-gray-300 font-bold">
                        </div>
                        
                        <div class="md:col-span-2 flex justify-end pt-4 border-t mt-4">
                             <button type="submit" class="px-6 py-3 bg-indigo-600 text-white font-bold rounded-lg hover:bg-indigo-700 shadow-lg transition">
                                <i class="fas fa-check-circle mr-2"></i> Update Data
                            </button>
                        </div>
                    </div>

                </form>
            </div>
        </div>

        <div class="lg:col-span-1">
             <div class="bg-yellow-50 border border-yellow-200 rounded-xl p-6 sticky top-24">
                <h3 class="font-bold text-yellow-800 flex items-center gap-2 mb-4">
                    <i class="fas fa-exclamation-triangle text-xl"></i> Peringatan Edit
                </h3>
                <div class="text-sm text-yellow-900 space-y-4">
                    <p>Anda sedang mengedit data: <strong><?= $employee['first_name'] ?></strong>.</p>
                    <p>Perubahan pada <strong>NIK</strong> atau <strong>Email</strong> dapat menyebabkan karyawan gagal login.</p>
                    <p>Pastikan data <strong>Rekening Bank</strong> valid untuk proses payroll.</p>
                </div>
             </div>
        </div>

    </div>
</div>