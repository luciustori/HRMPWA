<div class="max-w-7xl mx-auto p-6">
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-3xl font-bold text-gray-900 tracking-tight">Payroll & Penggajian</h1>
            <p class="text-sm text-gray-500 mt-1">Kelola gaji bulanan, slip gaji, dan tunjangan karyawan.</p>
        </div>
        <a href="<?= BASEURL ?>/admin/payroll/create" 
           class="bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-2.5 rounded-lg font-bold shadow-lg transform hover:-translate-y-0.5 transition flex items-center gap-2">
            <i class="fas fa-magic"></i> Generate Payroll Baru
        </a>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <?php if (empty($periods)): ?>
            <div class="text-center py-12 px-4">
                <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-file-invoice-dollar text-2xl text-gray-400"></i>
                </div>
                <h3 class="text-lg font-bold text-gray-900 mb-1">Belum Ada Riwayat Payroll</h3>
                <p class="text-gray-500">Klik tombol "Generate Payroll Baru" untuk memulai perhitungan bulan ini.</p>
            </div>
        <?php else: ?>
            <table class="w-full text-sm text-left">
                <thead class="bg-gray-50 border-b border-gray-200 text-gray-600 font-bold uppercase tracking-wider text-xs">
                    <tr>
                        <th class="p-4">Bulan / Periode</th>
                        <th class="p-4 text-center">Jml Pegawai</th>
                        <th class="p-4 text-right">Total Net (Take Home Pay)</th>
                        <th class="p-4 text-center">Status</th>
                        <th class="p-4 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    <?php foreach ($periods as $p): ?>
                    <tr class="hover:bg-gray-50 transition">
                        <td class="p-4">
                            <div class="font-bold text-gray-900 text-base"><?= htmlspecialchars($p['period_name']) ?></div>
                            <div class="text-xs text-gray-500 mt-1">
                                <?= date('d M', strtotime($p['start_date'])) ?> - <?= date('d M Y', strtotime($p['end_date'])) ?>
                            </div>
                        </td>
                        <td class="p-4 text-center font-bold text-gray-700">
                            <span class="bg-gray-100 px-3 py-1 rounded-full"><?= $p['total_employees'] ?> Orang</span>
                        </td>
                        <td class="p-4 text-right">
                            <div class="font-mono font-bold text-indigo-700 text-lg">
                                Rp <?= number_format($p['total_net_salary'], 0, ',', '.') ?>
                            </div>
                        </td>
                        <td class="p-4 text-center">
                            <?php 
                            $status_cls = match($p['status']) {
                                'paid', 'closed', 'approved' => 'bg-green-100 text-green-700 border-green-200',
                                default => 'bg-yellow-100 text-yellow-700 border-yellow-200'
                            };
                            ?>
                            <span class="px-3 py-1 rounded-full text-xs font-bold uppercase border <?= $status_cls ?>">
                                <?= $p['status'] ?>
                            </span>
                        </td>
                        <td class="p-4 text-center">
                            <div class="flex justify-center items-center gap-2">
                                <a href="<?= BASEURL ?>/admin/payroll/detail/<?= $p['id'] ?>" 
                                   class="bg-white border border-gray-300 text-gray-700 hover:bg-gray-50 px-3 py-1.5 rounded-lg font-bold text-xs shadow-sm transition flex items-center">
                                    <i class="fas fa-eye mr-1"></i> Detail
                                </a>
                                
                                <?php if (!in_array($p['status'], ['approved', 'paid', 'closed'])): ?>
                                    <a href="<?= BASEURL ?>/admin/payroll/delete/<?= $p['id'] ?>" 
                                       class="swal-link bg-white border border-red-200 text-red-600 hover:bg-red-50 hover:text-red-700 px-3 py-1.5 rounded-lg font-bold text-xs shadow-sm transition flex items-center"
                                       data-action="delete" data-msg="Hapus draf Payroll <?= $p['period_name'] ?>? Data akan terhapus permanen.">
                                        <i class="fas fa-trash"></i>
                                    </a>
                                <?php endif; ?>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>
</div>