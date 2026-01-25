<div class="flex justify-between items-center mb-6">
    <div>
        <h1 class="text-2xl font-bold text-gray-800">Profil Perusahaan</h1>
        <p class="text-sm text-gray-500">Informasi identitas instansi / perusahaan.</p>
    </div>
</div>

<div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
    <table class="w-full text-sm text-left">
        <thead class="bg-gray-50 text-gray-700 font-bold border-b border-gray-100">
            <tr>
                <th class="px-6 py-4">Identitas Perusahaan</th>
                <th class="px-6 py-4">Kontak</th>
                <th class="px-6 py-4 text-center">Aksi</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
            <?php foreach ($companies as $comp): ?>
            <tr class="hover:bg-gray-50 transition">
                <td class="px-6 py-4">
                    <div class="flex items-center gap-4">
                        <?php if(!empty($comp['logo_path'])): ?>
                            <img src="<?= BASEURL ?>/<?= $comp['logo_path'] ?>" class="w-16 h-16 object-contain border rounded bg-white p-1">
                        <?php else: ?>
                            <div class="w-16 h-16 bg-gray-100 rounded flex items-center justify-center text-gray-400">No Logo</div>
                        <?php endif; ?>
                        <div>
                            <div class="font-bold text-lg text-gray-800"><?= $comp['company_name'] ?></div>
                            <span class="bg-indigo-100 text-indigo-700 px-2 py-0.5 rounded text-xs font-bold"><?= $comp['company_code'] ?></span>
                            <p class="text-gray-500 text-xs mt-1 max-w-xs"><?= $comp['address'] ?></p>
                        </div>
                    </div>
                </td>
                <td class="px-6 py-4">
                    <div class="text-gray-600 space-y-1">
                        <div class="flex items-center gap-2"><i class="fas fa-phone w-4 text-center"></i> <?= $comp['phone'] ?></div>
                        <div class="flex items-center gap-2"><i class="fas fa-envelope w-4 text-center"></i> <?= $comp['email'] ?></div>
                        <div class="flex items-center gap-2"><i class="fas fa-globe w-4 text-center"></i> <?= $comp['website'] ?></div>
                    </div>
                </td>
                <td class="px-6 py-4 text-center">
                    <a href="<?= BASEURL ?>/admin/company/edit/<?= $comp['id'] ?>" class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-lg text-gray-700 font-medium hover:bg-gray-50 shadow-sm transition">
                        <i class="fas fa-cog mr-2 text-gray-400"></i> Setting
                    </a>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>