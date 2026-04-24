<div class="max-w-3xl mx-auto px-4 py-8" x-data="{ contractType: 'PKWT' }">
    
    <div class="mb-6 flex items-center justify-between">
        <h1 class="text-2xl font-bold text-gray-800">Tambah Kontrak Kerja</h1>
        <a href="<?= BASEURL ?>/admin/contracts" class="text-gray-500 hover:text-gray-700 font-medium text-sm">
            &larr; Kembali
        </a>
    </div>

    <div class="bg-white rounded-xl shadow-lg border border-gray-100 overflow-hidden">
        <div class="p-6 md:p-8">
            <form action="<?= BASEURL ?>/admin/contracts/store" method="POST" enctype="multipart/form-data">
                
                <div class="mb-6">
                    <label class="block text-sm font-bold text-gray-700 mb-2">Pilih Karyawan <span class="text-red-500">*</span></label>
                    <select name="employee_id" class="w-full px-3 py-2 border rounded-lg border-slate-300 focus:ring-2 focus:ring-purple-500" required>
                        <option value="">-- Cari Nama Karyawan --</option>
                        <?php foreach($employees as $emp): ?>
                            <option value="<?= $emp['id'] ?>"><?= $emp['first_name'] . ' ' . $emp['last_name'] ?> (<?= $emp['employee_number'] ?>)</option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">Nomor Kontrak</label>
                        <input type="text" name="contract_number" class="w-full px-3 py-2 border rounded-lg border-slate-300 focus:ring-2 focus:ring-purple-500" placeholder="Contoh: 001/HRD/PKWT/2024">
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">Jenis Kontrak <span class="text-red-500">*</span></label>
                        <select name="contract_type" x-model="contractType" class="w-full px-3 py-2 border rounded-lg border-slate-300 focus:ring-2 focus:ring-purple-500">
                            <option value="PKWT">PKWT (Kontrak Berjangka)</option>
                            <option value="PKWTT">PKWTT (Karyawan Tetap)</option>
                            <option value="Probation">Probation (Masa Percobaan)</option>
                            <option value="Internship">Internship (Magang)</option>
                        </select>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6 bg-gray-50 p-4 rounded-lg border border-gray-200">
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">Tanggal Mulai <span class="text-red-500">*</span></label>
                        <input type="date" name="start_date" class="w-full px-3 py-2 border rounded-lg border-slate-300 focus:ring-2 focus:ring-purple-500" required>
                    </div>

                    <div x-show="contractType != 'PKWTT'" x-transition>
                        <label class="block text-sm font-bold text-gray-700 mb-2">Tanggal Selesai <span class="text-red-500">*</span></label>
                        <input type="date" name="end_date" class="w-full px-3 py-2 border rounded-lg border-slate-300 focus:ring-2 focus:ring-purple-500" :required="contractType != 'PKWTT'">
                        <p class="text-xs text-gray-500 mt-1">Wajib diisi untuk PKWT/Probation</p>
                    </div>

                    <div x-show="contractType == 'PKWTT'" class="flex items-center text-emerald-600 font-bold text-sm">
                        <i class="ri-infinite-line mr-2 text-xl"></i> Masa kerja tidak terbatas (Permanent)
                    </div>
                </div>

                <div class="mb-6">
                    <label class="block text-sm font-bold text-gray-700 mb-2">Upload Dokumen Fisik (PDF/Gambar)</label>
                    <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-lg hover:bg-gray-50 transition">
                        <div class="space-y-1 text-center">
                            <i class="ri-file-upload-line text-4xl text-gray-400"></i>
                            <div class="flex text-sm text-gray-600">
                                <label for="file-upload" class="relative cursor-pointer bg-white rounded-md font-medium text-indigo-600 hover:text-indigo-500 focus-within:outline-none">
                                    <span>Upload file</span>
                                    <input id="file-upload" name="document" type="file" class="sr-only" accept=".pdf,.jpg,.jpeg,.png">
                                </label>
                                <p class="pl-1">atau drag & drop</p>
                            </div>
                            <p class="text-xs text-gray-500">PDF, PNG, JPG up to 5MB</p>
                        </div>
                    </div>
                </div>

                <div class="mb-8">
                    <label class="block text-sm font-bold text-gray-700 mb-2">Catatan Tambahan</label>
                    <textarea name="notes" rows="3" class="w-full px-3 py-2 border rounded-lg border-slate-300 focus:ring-2 focus:ring-purple-500" placeholder="Contoh: Promosi jabatan, perpanjangan kontrak ke-2, dll."></textarea>
                </div>

                <div class="flex items-center justify-end gap-3 border-t border-gray-100 pt-5">
                    <button type="button" onclick="history.back()" class="px-5 py-2.5 bg-white border border-gray-300 rounded-lg text-gray-700 font-bold hover:bg-gray-50 transition">
                        Batal
                    </button>
                    <button type="submit" class="px-5 py-2.5 bg-indigo-600 text-white rounded-lg font-bold shadow-lg hover:bg-indigo-700 hover:shadow-xl transition transform active:scale-95">
                        <i class="ri-save-line mr-1"></i> Simpan Kontrak
                    </button>
                </div>

            </form>
        </div>
    </div>
</div>