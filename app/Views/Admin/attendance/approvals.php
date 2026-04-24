<div class="p-4 sm:p-6">
    <div class="flex justify-between items-center mb-6">
        <div>
            <h2 class="text-xl font-bold text-gray-800">Persetujuan Absensi (Pending)</h2>
            <p class="text-sm text-gray-500">Daftar keterlambatan & pulang awal yang membutuhkan review.</p>
        </div>
    </div>

    <div class="bg-white shadow-sm rounded-xl overflow-hidden border border-gray-200">
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead class="bg-gray-50 text-gray-600 font-bold uppercase tracking-wider text-[11px] border-b border-gray-200">
                    <tr>
                        <th class="p-4">Tanggal</th>
                        <th class="p-4">Pegawai</th>
                        <th class="p-4">Tipe & Keterangan</th>
                        <th class="p-4 text-center">Masuk / Pulang</th>
                        <th class="p-4 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    <?php if (empty($pending_approvals)): ?>
                        <tr><td colspan="5" class="p-8 text-center text-gray-400 italic">Tidak ada pengajuan yang butuh persetujuan saat ini.</td></tr>
                    <?php else: ?>
                        <?php foreach ($pending_approvals as $row): ?>
                        <tr class="hover:bg-gray-50 transition group">
                            <td class="p-4 font-bold text-gray-800 whitespace-nowrap">
                                <?= date('d M Y', strtotime($row['attendance_date'])) ?>
                            </td>
                            <td class="p-4">
                                <div class="font-bold text-gray-900"><?= $row['first_name'] . ' ' . $row['last_name'] ?></div>
                                <div class="text-[11px] text-gray-500 mt-0.5 font-mono"><?= $row['employee_number'] ?> &bull; <?= $row['department_name'] ?></div>
                            </td>
                            <td class="p-4">
                                <span class="inline-block px-2 py-1 text-[10px] font-bold rounded uppercase mb-1 <?= $row['issue_type'] == 'late' ? 'bg-red-100 text-red-700' : 'bg-yellow-100 text-yellow-700' ?>">
                                    <?= $row['issue_type'] == 'late' ? 'Terlambat' : 'Pulang Awal' ?>
                                </span>
                                <div class="text-xs text-gray-500">Alasan: <strong class="text-gray-800"><?= htmlspecialchars($row['employee_reason']) ?: '-' ?></strong></div>
                            </td>
                            <td class="p-4 text-center font-mono font-bold text-gray-700 text-xs">
                                <span class="text-indigo-600"><?= substr($row['check_in_time'], 11, 5) ?></span> / 
                                <span><?= $row['check_out_time'] ? substr($row['check_out_time'], 11, 5) : '-' ?></span>
                            </td>
                            <td class="p-4 text-center whitespace-nowrap">
                                <div class="flex items-center justify-center gap-2">
                                    
                                    <form class="swal-form m-0" data-title="Setujui Alasan?" data-msg="Anda akan menyetujui alasan kehadiran <?= addslashes($row['first_name']) ?>." action="<?= BASEURL ?>/admin/attendance/process_approval" method="POST">
                                        <input type="hidden" name="attendance_id" value="<?= $row['issue_id'] ?>">
                                        <input type="hidden" name="action" value="approve">
                                        <button type="submit" class="w-8 h-8 rounded bg-emerald-50 text-emerald-600 hover:bg-emerald-500 hover:text-white transition flex items-center justify-center shadow-sm" title="Approve">
                                            <i class="ri-check-line text-lg"></i>
                                        </button>
                                    </form>
                                    
                                    <form class="swal-form m-0" data-title="Tolak Alasan?" data-msg="Alasan kehadiran <?= addslashes($row['first_name']) ?> akan ditolak (Reject)." action="<?= BASEURL ?>/admin/attendance/process_approval" method="POST">
                                        <input type="hidden" name="attendance_id" value="<?= $row['issue_id'] ?>">
                                        <input type="hidden" name="action" value="reject">
                                        <button type="submit" class="w-8 h-8 rounded bg-red-50 text-red-600 hover:bg-red-500 hover:text-white transition flex items-center justify-center shadow-sm" title="Reject">
                                            <i class="ri-close-line text-lg"></i>
                                        </button>
                                    </form>
                                    
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>