<div class="flex justify-between items-center mb-6">
    <div>
        <h1 class="text-2xl font-bold text-gray-800">Data Lokasi Kantor</h1>
        <p class="text-sm text-gray-500">Titik koordinat untuk validasi absensi (Geofencing).</p>
    </div>
    <a href="<?= BASEURL ?>/admin/offices/create" class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-lg text-sm font-medium shadow-md transition flex items-center">
        <i class="fas fa-map-marker-alt mr-2"></i> Tambah Lokasi
    </a>
</div>

<div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
    <table class="w-full text-sm text-left">
        <thead class="bg-gray-50 text-gray-700 font-bold border-b border-gray-100">
            <tr>
                <th class="px-6 py-4">Nama Kantor</th>
                <th class="px-6 py-4">Koordinat (Lat, Long)</th>
                <th class="px-6 py-4">Radius Absen</th>
                <th class="px-6 py-4">Status</th>
                <th class="px-6 py-4 text-center">Aksi</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
            <?php foreach ($offices as $off): ?>
            <tr class="hover:bg-gray-50 transition">
                <td class="px-6 py-4">
                    <div class="font-bold text-gray-800"><?= $off['office_name'] ?></div>
                    <div class="text-xs text-gray-500 mt-1"><?= $off['address'] ?></div>
                    <div class="text-[10px] text-indigo-500 font-bold mt-1"><?= $off['company_name'] ?></div>
                </td>
                <td class="px-6 py-4 font-mono text-xs text-gray-600">
                    <div>Lat: <?= $off['latitude'] ?></div>
                    <div>Long: <?= $off['longitude'] ?></div>
                </td>
                <td class="px-6 py-4">
                    <span class="bg-blue-50 text-blue-700 px-2 py-1 rounded text-xs font-bold">
                        <?= $off['radius_meters'] ?> Meter
                    </span>
                </td>
                <td class="px-6 py-4">
                    <?php if($off['is_active']): ?>
                        <span class="text-green-600 bg-green-50 px-2 py-1 rounded text-xs font-bold">Aktif</span>
                    <?php else: ?>
                        <span class="text-red-600 bg-red-50 px-2 py-1 rounded text-xs font-bold">Non-Aktif</span>
                    <?php endif; ?>
                </td>
                <td class="px-6 py-4 text-center">
                    <div class="flex justify-center gap-2">
                        <a href="<?= BASEURL ?>/admin/offices/edit/<?= $off['id'] ?>" class="w-8 h-8 rounded-full bg-yellow-50 text-yellow-600 hover:bg-yellow-100 flex items-center justify-center transition">
                            <i class="fas fa-pen text-xs"></i>
                        </a>
                        <button onclick="if(confirm('Hapus lokasi ini?')) window.location.href='<?= BASEURL ?>/admin/offices/delete/<?= $off['id'] ?>'" class="w-8 h-8 rounded-full bg-red-50 text-red-600 hover:bg-red-100 flex items-center justify-center transition">
                            <i class="fas fa-trash-alt text-xs"></i>
                        </button>
                    </div>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>