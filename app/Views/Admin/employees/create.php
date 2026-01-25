<div class="max-w-7xl mx-auto" x-data="{ activeTab: 'identitas' }">
    
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Tambah Pegawai Baru</h1>
            <p class="text-sm text-gray-500">Lengkapi data pegawai di semua tab.</p>
        </div>
        <a href="<?= BASEURL ?>/admin/employees" class="bg-white border text-gray-600 px-4 py-2 rounded-lg text-sm font-medium hover:bg-gray-50 shadow-sm transition">
            <i class="fas fa-arrow-left mr-2"></i> Kembali
        </a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        
        <div class="lg:col-span-2">
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                
                <div class="flex border-b border-gray-200 overflow-x-auto">
                    <button @click="activeTab = 'identitas'" :class="{'border-indigo-500 text-indigo-600': activeTab === 'identitas', 'border-transparent text-gray-500 hover:text-gray-700': activeTab !== 'identitas'}" class="px-6 py-4 text-sm font-medium border-b-2 transition whitespace-nowrap">
                        <i class="far fa-id-card mr-2"></i> Data Identitas
                    </button>
                    <button @click="activeTab = 'kepegawaian'" :class="{'border-indigo-500 text-indigo-600': activeTab === 'kepegawaian', 'border-transparent text-gray-500 hover:text-gray-700': activeTab !== 'kepegawaian'}" class="px-6 py-4 text-sm font-medium border-b-2 transition whitespace-nowrap">
                        <i class="fas fa-briefcase mr-2"></i> Kepegawaian
                    </button>
                    <button @click="activeTab = 'kontak'" :class="{'border-indigo-500 text-indigo-600': activeTab === 'kontak', 'border-transparent text-gray-500 hover:text-gray-700': activeTab !== 'kontak'}" class="px-6 py-4 text-sm font-medium border-b-2 transition whitespace-nowrap">
                        <i class="fas fa-map-marker-alt mr-2"></i> Kontak
                    </button>
                    <button @click="activeTab = 'payroll'" :class="{'border-indigo-500 text-indigo-600': activeTab === 'payroll', 'border-transparent text-gray-500 hover:text-gray-700': activeTab !== 'payroll'}" class="px-6 py-4 text-sm font-medium border-b-2 transition whitespace-nowrap">
                        <i class="fas fa-money-check-alt mr-2"></i> Bank & Payroll
                    </button>
                </div>

                <form action="<?= BASEURL ?>/admin/employees/store" method="POST" class="p-6">
                    
                    <div x-show="activeTab === 'identitas'" class="space-y-5 animate-fade-in">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">NIK (Nomor Induk)</label>
                                <input type="text" name="employee_number" placeholder="T-XX.XXX" required class="w-full rounded-lg border-gray-300 focus:ring-indigo-500 focus:border-indigo-500 shadow-sm">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">No. KTP (Identity)</label>
                                <input type="number" name="identity_number" placeholder="16 digit NIK KTP" class="w-full rounded-lg border-gray-300 focus:ring-indigo-500 focus:border-indigo-500 shadow-sm">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Nama Depan</label>
                                <input type="text" name="first_name" required class="w-full rounded-lg border-gray-300 focus:ring-indigo-500 focus:border-indigo-500 shadow-sm">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Nama Belakang</label>
                                <input type="text" name="last_name" class="w-full rounded-lg border-gray-300 focus:ring-indigo-500 focus:border-indigo-500 shadow-sm">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Tempat, Tanggal Lahir</label>
                                <div class="flex gap-2">
                                    <input type="date" name="date_of_birth" class="w-full rounded-lg border-gray-300 focus:ring-indigo-500 focus:border-indigo-500 shadow-sm">
                                </div>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Jenis Kelamin</label>
                                <select name="gender" class="w-full rounded-lg border-gray-300 focus:ring-indigo-500 focus:border-indigo-500 shadow-sm">
                                    <option value="male">Laki-laki</option>
                                    <option value="female">Perempuan</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Status Pernikahan</label>
                                <select name="marital_status" class="w-full rounded-lg border-gray-300 focus:ring-indigo-500 focus:border-indigo-500 shadow-sm">
                                    <option value="single">Lajang</option>
                                    <option value="married">Menikah</option>
                                    <option value="divorced">Cerai</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Jumlah Anak</label>
                                <input type="number" name="number_of_dependents" value="0" class="w-full rounded-lg border-gray-300 focus:ring-indigo-500 focus:border-indigo-500 shadow-sm">
                            </div>
                        </div>
                        <div class="flex justify-end pt-4">
                            <button type="button" @click="activeTab = 'kepegawaian'" class="bg-indigo-50 text-indigo-700 px-4 py-2 rounded-lg text-sm font-bold hover:bg-indigo-100">Lanjut <i class="fas fa-arrow-right ml-1"></i></button>
                        </div>
                    </div>

                    <div x-show="activeTab === 'kepegawaian'" style="display: none;">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Departemen</label>
                                <select name="department_id" class="w-full rounded-lg border-gray-300 focus:ring-indigo-500 focus:border-indigo-500 shadow-sm">
                                    <?php foreach($departments as $dept): ?>
                                        <option value="<?= $dept['id'] ?>"><?= $dept['department_name'] ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Jabatan / Posisi</label>
                                <input type="text" name="position" required class="w-full rounded-lg border-gray-300 focus:ring-indigo-500 focus:border-indigo-500 shadow-sm">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Level Karyawan</label>
                                <select name="employee_level" class="w-full rounded-lg border-gray-300 focus:ring-indigo-500 focus:border-indigo-500 shadow-sm">
                                    <option value="staff">Staff</option>
                                    <option value="supervisor">Supervisor</option>
                                    <option value="manager">Manager</option>
                                    <option value="harian">Harian</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal Bergabung</label>
                                <input type="date" name="hire_date" required class="w-full rounded-lg border-gray-300 focus:ring-indigo-500 focus:border-indigo-500 shadow-sm">
                            </div>
                        </div>
                        <div class="flex justify-end pt-4 gap-2">
                             <button type="button" @click="activeTab = 'kontak'" class="bg-indigo-50 text-indigo-700 px-4 py-2 rounded-lg text-sm font-bold hover:bg-indigo-100">Lanjut <i class="fas fa-arrow-right ml-1"></i></button>
                        </div>
                    </div>

                    <div x-show="activeTab === 'kontak'" style="display: none;">
                         <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Email Kantor</label>
                                <input type="email" name="email" class="w-full rounded-lg border-gray-300 focus:ring-indigo-500 focus:border-indigo-500 shadow-sm">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">No. Handphone</label>
                                <input type="text" name="phone" class="w-full rounded-lg border-gray-300 focus:ring-indigo-500 focus:border-indigo-500 shadow-sm">
                            </div>
                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-gray-700 mb-1">Alamat Domisili Lengkap</label>
                                <textarea name="address" rows="3" class="w-full rounded-lg border-gray-300 focus:ring-indigo-500 focus:border-indigo-500 shadow-sm"></textarea>
                            </div>
                         </div>
                         <div class="flex justify-end pt-4 gap-2">
                             <button type="button" @click="activeTab = 'payroll'" class="bg-indigo-50 text-indigo-700 px-4 py-2 rounded-lg text-sm font-bold hover:bg-indigo-100">Lanjut <i class="fas fa-arrow-right ml-1"></i></button>
                        </div>
                    </div>

                    <div x-show="activeTab === 'payroll'" style="display: none;">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Nama Bank</label>
                                <select name="bank_name" class="w-full rounded-lg border-gray-300 focus:ring-indigo-500 focus:border-indigo-500 shadow-sm">
                                    <option value="">-- Pilih Bank --</option>
                                    <option value="BCA">BCA</option>
                                    <option value="MANDIRI">Mandiri</option>
                                    <option value="BNI">BNI</option>
                                    <option value="BRI">BRI</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Nomor Rekening</label>
                                <input type="text" name="bank_account_number" class="w-full rounded-lg border-gray-300 focus:ring-indigo-500 focus:border-indigo-500 shadow-sm">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Atas Nama Rekening</label>
                                <input type="text" name="bank_account_name" class="w-full rounded-lg border-gray-300 focus:ring-indigo-500 focus:border-indigo-500 shadow-sm">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Nomor NPWP</label>
                                <input type="text" name="npwp" class="w-full rounded-lg border-gray-300 focus:ring-indigo-500 focus:border-indigo-500 shadow-sm">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Gaji Pokok (Rp)</label>
                                <input type="text" name="salary" placeholder="Contoh: 3.500.000" class="w-full rounded-lg border-gray-300 focus:ring-indigo-500 focus:border-indigo-500 shadow-sm">
                            </div>
                        </div>

                        <div class="flex justify-end pt-6 mt-4 border-t">
                            <button type="submit" class="px-6 py-3 bg-indigo-600 text-white font-bold rounded-lg hover:bg-indigo-700 shadow-lg transition transform hover:-translate-y-0.5">
                                <i class="fas fa-save mr-2"></i> Simpan Data Pegawai
                            </button>
                        </div>
                    </div>

                </form>
            </div>
        </div>

        <div class="lg:col-span-1">
             <div class="bg-blue-50 border border-blue-100 rounded-xl p-6 sticky top-24">
                <h3 class="font-bold text-blue-800 flex items-center gap-2 mb-4">
                    <i class="fas fa-info-circle text-xl"></i> Petunjuk Pengisian
                </h3>
                <div class="text-sm text-blue-900 space-y-4">
                    <div x-show="activeTab === 'identitas'">
                        <p class="font-bold">Tab Identitas:</p>
                        <ul class="list-disc pl-4 space-y-1">
                            <li><strong>NIK:</strong> Gunakan format perusahaan (T-YY.NO).</li>
                            <li><strong>KTP:</strong> Wajib 16 digit angka.</li>
                            <li>Pastikan nama sesuai KTP.</li>
                        </ul>
                    </div>
                    <div x-show="activeTab === 'kepegawaian'">
                         <p class="font-bold">Tab Kepegawaian:</p>
                         <p>Data ini mempengaruhi struktur organisasi dan hak akses aplikasi.</p>
                    </div>
                     <div x-show="activeTab === 'payroll'">
                         <p class="font-bold">Tab Payroll:</p>
                         <p>Pastikan nomor rekening valid agar transfer gaji tidak terkendala.</p>
                    </div>
                    <div class="bg-white p-3 rounded border border-blue-100 text-xs text-gray-500 mt-4">
                        Data yang disimpan aman dan hanya bisa diakses oleh HR & Admin.
                    </div>
                </div>
             </div>
        </div>

    </div>
</div>