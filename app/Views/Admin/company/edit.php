<div class="max-w-6xl mx-auto">
    
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Edit Profil Perusahaan</h1>
            <p class="text-sm text-gray-500">Perbarui informasi identitas dan logo.</p>
        </div>
        <a href="<?= BASEURL ?>/admin/company" class="bg-white border px-4 py-2 rounded-lg text-sm font-medium hover:bg-gray-50 shadow-sm">
            <i class="fas fa-arrow-left mr-2"></i> Kembali
        </a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        
        <div class="lg:col-span-2">
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <form action="<?= BASEURL ?>/admin/company/update/<?= $company['id'] ?>" method="POST" enctype="multipart/form-data" class="space-y-6">
                    
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div class="md:col-span-2">
                            <label class="block text-sm font-bold text-gray-700 mb-2">Nama Perusahaan</label>
                            <input type="text" name="company_name" value="<?= $company['company_name'] ?>" required class="w-full rounded-lg border-gray-300 focus:ring-indigo-500 shadow-sm">
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">Kode</label>
                            <input type="text" name="company_code" value="<?= $company['company_code'] ?>" required class="w-full rounded-lg border-gray-300 uppercase font-mono shadow-sm">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">Email Resmi</label>
                            <input type="email" name="email" value="<?= $company['email'] ?>" class="w-full rounded-lg border-gray-300 shadow-sm">
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">No. Telepon</label>
                            <input type="text" name="phone" value="<?= $company['phone'] ?>" class="w-full rounded-lg border-gray-300 shadow-sm">
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-sm font-bold text-gray-700 mb-2">Website</label>
                            <input type="url" name="website" value="<?= $company['website'] ?>" placeholder="https://" class="w-full rounded-lg border-gray-300 shadow-sm">
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">Alamat Lengkap</label>
                        <textarea name="address" rows="3" class="w-full rounded-lg border-gray-300 shadow-sm"><?= $company['address'] ?></textarea>
                    </div>

                    <div class="border-t pt-6">
                        <label class="block text-sm font-bold text-gray-700 mb-2">Logo Perusahaan</label>
                        <div class="flex items-center gap-6">
                            <?php if(!empty($company['logo_path'])): ?>
                                <div class="w-24 h-24 border rounded-lg p-2 bg-gray-50 flex items-center justify-center">
                                    <img src="<?= BASEURL ?>/<?= $company['logo_path'] ?>" class="max-h-full max-w-full">
                                </div>
                            <?php endif; ?>
                            <div class="flex-1">
                                <input type="hidden" name="old_logo" value="<?= $company['logo_path'] ?>">
                                <input type="file" name="logo" accept="image/*" class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
                                <p class="text-xs text-gray-500 mt-2">Format: PNG/JPG. Max: 2MB.</p>
                            </div>
                        </div>
                    </div>

                    <div class="pt-4 border-t flex justify-end">
                        <button type="submit" class="px-6 py-2 bg-indigo-600 text-white font-bold rounded-lg hover:bg-indigo-700 shadow-lg">
                            <i class="fas fa-save mr-2"></i> Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <div class="lg:col-span-1">
            <div class="bg-blue-50 border border-blue-100 rounded-xl p-6 sticky top-24">
                <h3 class="font-bold text-blue-800 flex items-center gap-2 mb-4">
                    <i class="fas fa-image text-xl"></i> Tips Logo
                </h3>
                <ul class="text-sm text-blue-900 space-y-3">
                    <li>Gunakan logo dengan latar belakang <strong>Transparan (PNG)</strong> agar terlihat bagus di header aplikasi.</li>
                    <li>Ukuran optimal adalah persegi (contoh: 500x500px).</li>
                    <li>Logo ini akan muncul di Kop Surat, Header Aplikasi, dan Laporan PDF.</li>
                </ul>
            </div>
        </div>
    </div>
</div>