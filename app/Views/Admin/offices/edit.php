<div class="max-w-6xl mx-auto">
    
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Edit Lokasi Kantor</h1>
            <p class="text-sm text-gray-500">Update koordinat: <strong><?= $office['office_name'] ?></strong></p>
        </div>
        <a href="<?= BASEURL ?>/admin/offices" class="bg-white border px-4 py-2 rounded-lg text-sm font-medium hover:bg-gray-50 shadow-sm">
            <i class="fas fa-arrow-left mr-2"></i> Kembali
        </a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        
        <div class="lg:col-span-2">
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <form action="<?= BASEURL ?>/admin/offices/update/<?= $office['id'] ?>" method="POST" class="space-y-6">
                    
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">Nama Kantor</label>
                        <input type="text" name="office_name" value="<?= $office['office_name'] ?>" required class="w-full rounded-lg border-gray-300 focus:ring-indigo-500 shadow-sm">
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">Perusahaan</label>
                        <select name="company_id" class="w-full rounded-lg border-gray-300 focus:ring-indigo-500 shadow-sm">
                            <?php foreach($companies as $comp): ?>
                                <option value="<?= $comp['id'] ?>" <?= $comp['id'] == $office['company_id'] ? 'selected' : '' ?>>
                                    <?= $comp['company_name'] ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">Latitude</label>
                            <input type="text" name="latitude" value="<?= $office['latitude'] ?>" required class="w-full rounded-lg border-gray-300 focus:ring-indigo-500 shadow-sm font-mono">
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">Longitude</label>
                            <input type="text" name="longitude" value="<?= $office['longitude'] ?>" required class="w-full rounded-lg border-gray-300 focus:ring-indigo-500 shadow-sm font-mono">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">Radius (Meter)</label>
                            <input type="number" name="radius_meters" value="<?= $office['radius_meters'] ?>" required class="w-full rounded-lg border-gray-300 focus:ring-indigo-500 shadow-sm">
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">Status</label>
                            <select name="is_active" class="w-full rounded-lg border-gray-300 font-bold text-gray-700">
                                <option value="1" <?= $office['is_active'] == 1 ? 'selected' : '' ?>>Aktif</option>
                                <option value="0" <?= $office['is_active'] == 0 ? 'selected' : '' ?>>Non-Aktif</option>
                            </select>
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">Alamat</label>
                        <textarea name="address" rows="3" class="w-full rounded-lg border-gray-300 shadow-sm"><?= $office['address'] ?></textarea>
                    </div>

                    <div class="pt-4 border-t flex justify-end gap-3">
                        <button type="button" onclick="history.back()" class="px-4 py-2 border border-gray-300 rounded-lg text-gray-600 hover:bg-gray-50">Batal</button>
                        <button type="submit" class="px-6 py-2 bg-indigo-600 text-white font-bold rounded-lg hover:bg-indigo-700 shadow-lg">
                            <i class="fas fa-check-circle mr-2"></i> Update Lokasi
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <div class="lg:col-span-1">
            <div class="bg-yellow-50 border border-yellow-200 rounded-xl p-6 sticky top-24">
                <h3 class="font-bold text-yellow-800 flex items-center gap-2 mb-4">
                    <i class="fas fa-satellite-dish text-xl"></i> Peringatan
                </h3>
                <p class="text-sm text-yellow-900 mb-3">
                    Mengubah koordinat akan mempengaruhi validasi lokasi saat karyawan melakukan absensi.
                </p>
                <a href="https://www.google.com/maps/search/?api=1&query=<?= $office['latitude'] ?>,<?= $office['longitude'] ?>" target="_blank" class="block w-full text-center py-2 bg-white border border-yellow-200 rounded text-yellow-700 text-xs font-bold hover:bg-yellow-100">
                    <i class="fas fa-external-link-alt mr-1"></i> Cek Lokasi di Maps
                </a>
            </div>
        </div>

    </div>
</div>