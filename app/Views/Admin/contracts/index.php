<div class="max-w-7xl mx-auto px-4 sm:px-6 md:px-8 py-6">
    
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6 gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Manajemen Kontrak</h1>
            <p class="text-sm text-gray-500">Monitoring masa berlaku kontrak kerja karyawan (PKWT/PKWTT).</p>
        </div>
        <a href="<?= BASEURL ?>/admin/contracts/create" 
           class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-lg font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:border-indigo-900 focus:ring ring-indigo-300 disabled:opacity-25 transition ease-in-out duration-150 shadow-md">
            <i class="ri-add-line text-lg mr-2"></i> Tambah Kontrak
        </a>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
        <a href="?status=active" class="block p-4 bg-white rounded-xl border border-gray-100 shadow-sm hover:shadow-md transition">
            <div class="flex justify-between items-center">
                <div>
                    <p class="text-xs font-bold text-gray-400 uppercase">Kontrak Aktif</p>
                    <p class="text-2xl font-bold text-emerald-600 mt-1"><?= $stats['active'] ?></p>
                </div>
                <div class="w-10 h-10 rounded-full bg-emerald-50 flex items-center justify-center text-emerald-600">
                    <i class="ri-file-shield-2-line text-xl"></i>
                </div>
            </div>
        </a>

        <div class="block p-4 bg-white rounded-xl border border-yellow-100 shadow-sm relative overflow-hidden">
            <div class="absolute right-0 top-0 w-16 h-16 bg-yellow-50 rounded-bl-full -mr-8 -mt-8"></div>
            <div class="relative z-10 flex justify-between items-center">
                <div>
                    <p class="text-xs font-bold text-yellow-600 uppercase">Segera Habis (< 60 Hari)</p>
                    <p class="text-2xl font-bold text-gray-800 mt-1"><?= $stats['expiring_soon'] ?></p>
                </div>
                <div class="w-10 h-10 rounded-full bg-yellow-100 flex items-center justify-center text-yellow-600 animate-pulse">
                    <i class="ri-alarm-warning-line text-xl"></i>
                </div>
            </div>
        </div>

        <a href="?status=expired" class="block p-4 bg-white rounded-xl border border-red-100 shadow-sm hover:shadow-md transition">
            <div class="flex justify-between items-center">
                <div>
                    <p class="text-xs font-bold text-red-500 uppercase">Sudah Kadaluarsa</p>
                    <p class="text-2xl font-bold text-red-600 mt-1"><?= $stats['expired'] ?></p>
                </div>
                <div class="w-10 h-10 rounded-full bg-red-50 flex items-center justify-center text-red-600">
                    <i class="ri-close-circle-line text-xl"></i>
                </div>
            </div>
        </a>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead class="bg-gray-50 text-gray-500 font-bold uppercase text-xs border-b border-gray-200">
                    <tr>
                        <th class="px-6 py-4">Karyawan</th>
                        <th class="px-6 py-4">Jenis & Nomor</th>
                        <th class="px-6 py-4">Durasi Kontrak</th>
                        <th class="px-6 py-4">Sisa Waktu</th>
                        <th class="px-6 py-4">Status</th>
                        <th class="px-6 py-4 text-center">Dokumen</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    <?php if (empty($contracts)): ?>
                        <tr>
                            <td colspan="6" class="px-6 py-10 text-center text-gray-400 italic">
                                Belum ada data kontrak. Silakan tambah data baru.
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($contracts as $row): ?>
                            <?php
                                // Logic Baris Merah/Kuning
                                $rowClass = '';
                                $days = $row['days_remaining'];
                                if ($row['status'] == 'active' && $days !== null) {
                                    if ($days < 0) $rowClass = 'bg-red-50'; // Harusnya expired tapi status masih active
                                    elseif ($days <= 30) $rowClass = 'bg-red-50'; // Kritis
                                    elseif ($days <= 60) $rowClass = 'bg-yellow-50'; // Warning
                                }
                            ?>
                            <tr class="hover:bg-gray-50 transition <?= $rowClass ?>">
                                <td class="px-6 py-4">
                                    <div class="font-bold text-gray-900"><?= $row['first_name'] . ' ' . $row['last_name'] ?></div>
                                    <div class="text-xs text-gray-500"><?= $row['department_name'] ?> • <?= $row['employee_number'] ?></div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="font-bold text-gray-800"><?= $row['contract_type'] ?></div>
                                    <div class="text-xs font-mono text-gray-500"><?= $row['contract_number'] ?? '-' ?></div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-gray-900">
                                        <?= date('d M Y', strtotime($row['start_date'])) ?>
                                        <span class="text-gray-400 mx-1">➜</span>
                                        <?= $row['end_date'] ? date('d M Y', strtotime($row['end_date'])) : '<span class="text-emerald-600 font-bold">∞ (Permanent)</span>' ?>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <?php if ($row['end_date']): ?>
                                        <?php if ($days < 0): ?>
                                            <span class="text-red-600 font-bold text-xs">Expired <?= abs($days) ?> hari lalu</span>
                                        <?php elseif ($days <= 60): ?>
                                            <span class="text-orange-600 font-bold text-xs animate-pulse"><?= $days ?> Hari Lagi!</span>
                                        <?php else: ?>
                                            <span class="text-emerald-600 font-bold text-xs"><?= $days ?> Hari</span>
                                        <?php endif; ?>
                                    <?php else: ?>
                                        <span class="text-gray-400">-</span>
                                    <?php endif; ?>
                                </td>
                                <td class="px-6 py-4">
                                    <?php if ($row['status'] == 'active'): ?>
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">Aktif</span>
                                    <?php else: ?>
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">Non-Aktif</span>
                                    <?php endif; ?>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <?php if ($row['document_path']): ?>
                                        <a href="<?= BASEURL . '/' . $row['document_path'] ?>" target="_blank" 
                                           class="text-indigo-600 hover:text-indigo-900 hover:bg-indigo-50 p-2 rounded-lg transition" title="Lihat Dokumen">
                                            <i class="ri-file-pdf-2-line text-lg"></i>
                                        </a>
                                    <?php else: ?>
                                        <span class="text-gray-300"><i class="ri-file-warning-line text-lg"></i></span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>