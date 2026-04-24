<div class="max-w-6xl mx-auto">
    
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Tambah Lokasi Kantor</h1>
            <p class="text-sm text-gray-500">Tentukan titik koordinat (Geotagging) untuk absensi.</p>
        </div>
        <a href="<?= BASEURL ?>/admin/offices" class="bg-white border px-4 py-2 rounded-lg text-sm font-medium hover:bg-gray-50 shadow-sm">
            <i class="fas fa-arrow-left mr-2"></i> Kembali
        </a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        
        <div class="lg:col-span-2">
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <form action="<?= BASEURL ?>/admin/offices/store" method="POST" class="space-y-6">
                    
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">Nama Kantor / Cabang</label>
                        <input type="text" name="office_name" placeholder="Contoh: Kantor Pusat, Pabrik A" required class="w-full px-3 py-2 border rounded-lg border-slate-300 focus:ring-2 focus:ring-purple-500">
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">Perusahaan Induk</label>
                        <select name="company_id" class="w-full px-3 py-2 border rounded-lg border-slate-300 focus:ring-2 focus:ring-purple-500">
                            <?php foreach($companies as $comp): ?>
                                <option value="<?= $comp['id'] ?>"><?= $comp['company_name'] ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">Latitude</label>
                            <input type="text" name="latitude" placeholder="Contoh: -7.782..." required class="w-full px-3 py-2 border rounded-lg border-slate-300 focus:ring-2 focus:ring-purple-500 font-mono">
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">Longitude</label>
                            <input type="text" name="longitude" placeholder="Contoh: 110.36..." required class="w-full px-3 py-2 border rounded-lg border-slate-300 focus:ring-2 focus:ring-purple-500 font-mono">
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">Radius Absensi (Meter)</label>
                        <input type="number" name="radius_meters" value="100" required class="w-full px-3 py-2 border rounded-lg border-slate-300 focus:ring-2 focus:ring-purple-500">
                        <p class="text-xs text-gray-500 mt-1">Karyawan hanya bisa absen jika berada dalam jarak ini dari titik pusat.</p>
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">Alamat Lengkap</label>
                        <textarea name="address" rows="3" class="w-full px-3 py-2 border rounded-lg border-slate-300 focus:ring-2 focus:ring-purple-500"></textarea>
                    </div>

                    <div class="pt-4 border-t flex justify-end">
                        <button type="submit" class="px-6 py-2 bg-indigo-600 text-white font-bold rounded-lg hover:bg-indigo-700 shadow-lg">
                            <i class="fas fa-save mr-2"></i> Simpan Lokasi
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <div class="lg:col-span-1">
            <div class="bg-green-50 border border-green-100 rounded-xl p-6 sticky top-24">
                <h3 class="font-bold text-green-800 flex items-center gap-2 mb-4">
                    <i class="fas fa-map-marked-alt text-xl"></i> Cara Cari Koordinat
                </h3>
                <ol class="text-sm text-green-900 space-y-3 list-decimal pl-4">
                    <li>Buka <strong>Google Maps</strong>.</li>
                    <li>Klik kanan tepat di lokasi kantor Anda.</li>
                    <li>Pilih angka paling atas (itu adalah Lat & Long).</li>
                    <li>Klik angka tersebut untuk menyalinnya.</li>
                    <li>Angka pertama adalah <strong>Latitude</strong> (biasanya minus untuk Indonesia).</li>
                    <li>Angka kedua adalah <strong>Longitude</strong>.</li>
                </ol>
                <div class="mt-4 p-3 bg-white border border-green-200 rounded text-xs text-gray-600">
                    Contoh: <strong>-6.175392, 106.827153</strong> (Monas)
                </div>
            </div>
        </div>
    </div>
</div>