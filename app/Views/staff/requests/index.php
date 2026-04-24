<div class="max-w-7xl mx-auto">
    <!-- Header Stats -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-8">
        <div class="bg-white rounded-xl p-5 border border-slate-100 shadow-sm flex items-center justify-between">
            <div>
                <p class="text-xs text-slate-400 font-bold uppercase tracking-wider">Menunggu</p>
                <h3 class="text-3xl font-bold text-orange-500 mt-1"><?= $data['stats']['pending'] ?></h3>
            </div>
            <div class="w-10 h-10 rounded-full bg-orange-50 text-orange-500 flex items-center justify-center text-xl">
                <i class="ri-loader-2-line"></i>
            </div>
        </div>
        <div class="bg-white rounded-xl p-5 border border-slate-100 shadow-sm flex items-center justify-between">
            <div>
                <p class="text-xs text-slate-400 font-bold uppercase tracking-wider">Disetujui</p>
                <h3 class="text-3xl font-bold text-green-500 mt-1"><?= $data['stats']['approved'] ?></h3>
            </div>
            <div class="w-10 h-10 rounded-full bg-green-50 text-green-500 flex items-center justify-center text-xl">
                <i class="ri-check-double-line"></i>
            </div>
        </div>
        <div class="bg-white rounded-xl p-5 border border-slate-100 shadow-sm flex items-center justify-between">
            <div>
                <p class="text-xs text-slate-400 font-bold uppercase tracking-wider">Ditolak</p>
                <h3 class="text-3xl font-bold text-red-500 mt-1"><?= $data['stats']['rejected'] ?></h3>
            </div>
            <div class="w-10 h-10 rounded-full bg-red-50 text-red-500 flex items-center justify-center text-xl">
                <i class="ri-close-line"></i>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="bg-white rounded-xl border border-slate-200 shadow-sm">
        <div class="p-6 border-b border-slate-100 flex flex-col md:flex-row justify-between items-center gap-4">
            <div>
                <h2 class="text-lg font-bold text-slate-800">Riwayat Pengajuan</h2>
                <p class="text-sm text-slate-400">Semua permohonan ijin, cuti, dan lembur Anda.</p>
            </div>
            <a href="<?= BASEURL ?>/staff/requests/create" class="px-4 py-2 bg-indigo-600 text-white text-sm font-bold rounded-lg shadow-lg shadow-indigo-200 hover:bg-indigo-700 transition-all flex items-center gap-2">
                <i class="ri-add-line text-lg"></i> Buat Pengajuan
            </a>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50 text-slate-500 text-xs uppercase tracking-wider">
                        <th class="p-4 font-bold border-b border-slate-100">Tipe</th>
                        <th class="p-4 font-bold border-b border-slate-100">Keterangan</th>
                        <th class="p-4 font-bold border-b border-slate-100">Tanggal</th>
                        <th class="p-4 font-bold border-b border-slate-100 text-center">Status</th>
                        <th class="p-4 font-bold border-b border-slate-100">Aksi</th>
                    </tr>
                </thead>
                <tbody class="text-sm text-slate-700 divide-y divide-slate-100">
                    <?php 
                    $all_requests = array_merge($data['leaves'], $data['overtimes'], $data['trips']);
                    
                    // Sort by Created At Descending (Manual Sort karena array merge ngacak urutan)
                    usort($all_requests, function($a, $b) {
                        return strtotime($b['created_at']) - strtotime($a['created_at']);
                    });

                    if(empty($all_requests)): 
                    ?>
                        <tr>
                            <td colspan="5" class="p-8 text-center text-slate-400 italic">Belum ada riwayat pengajuan.</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach($all_requests as $req): ?>
                        <tr class="hover:bg-slate-50 transition-colors group">
                            <!-- Kolom Tipe -->
                            <td class="p-4 font-bold text-indigo-900">
                                <?php 
                                    if(isset($req['leave_type_name'])) echo $req['leave_type_name'];
                                    elseif(isset($req['destination'])) echo "SPPD";
                                    else echo "Lembur";
                                ?>
                            </td>
                            
                            <!-- Kolom Keterangan -->
                            <td class="p-4 max-w-xs truncate text-slate-500">
                                <?= isset($req['reason']) ? $req['reason'] : ($req['trip_purpose'] ?? '-') ?>
                            </td>

                            <!-- Kolom Tanggal -->
                            <td class="p-4 whitespace-nowrap text-slate-500">
                                <?php if(isset($req['start_date'])): ?>
                                    <?= date('d M', strtotime($req['start_date'])) ?> - <?= date('d M Y', strtotime($req['end_date'])) ?>
                                    <span class="text-xs bg-slate-100 px-1.5 rounded ml-1 text-slate-400"><?= $req['total_days'] ?> Hari</span>
                                <?php else: ?>
                                    <?= date('d M Y', strtotime($req['overtime_date'])) ?>
                                    <span class="text-xs bg-slate-100 px-1.5 rounded ml-1 text-slate-400"><?= $req['total_hours'] ?> Jam</span>
                                <?php endif; ?>
                            </td>

                            <!-- Kolom Status -->
                            <td class="p-4 text-center">
                                <?php 
                                    $statusColor = match($req['status']) {
                                        'approved' => 'bg-green-100 text-green-700 border border-green-200',
                                        'rejected' => 'bg-red-50 text-red-600 border border-red-100',
                                        default => 'bg-orange-50 text-orange-600 border border-orange-100'
                                    };
                                    $icon = match($req['status']) {
                                        'approved' => 'ri-check-line',
                                        'rejected' => 'ri-close-line',
                                        default => 'ri-time-line'
                                    };
                                ?>
                                <span class="px-3 py-1 rounded-full text-xs font-bold <?= $statusColor ?> inline-flex items-center gap-1 capitalize">
                                    <i class="<?= $icon ?>"></i> <?= $req['status'] ?>
                                </span>
                            </td>

                            <!-- Kolom Aksi -->
                            <td class="p-4">
                                <?php if($req['status'] == 'pending'): ?>
                                    <button class="text-slate-400 hover:text-red-500 transition-colors" title="Batalkan">
                                        <i class="ri-delete-bin-line"></i>
                                    </button>
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
