<!-- app/Views/Admin/pages/approvals.php -->
<div class="space-y-4">
    <h2 class="text-2xl font-bold">Persetujuan Pengajuan</h2>

    <div class="overflow-x-auto bg-gray-800 rounded-lg">
        <table class="w-full text-sm text-left">
            <thead class="bg-gray-700">
                <tr>
                    <th class="px-4 py-3">Karyawan</th>
                    <th class="px-4 py-3">Tipe Pengajuan</th>
                    <th class="px-4 py-3">Tanggal</th>
                    <th class="px-4 py-3">Alasan</th>
                    <th class="px-4 py-3">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-700">
                <?php foreach ($approvals as $appr): ?>
                <tr class="hover:bg-gray-700">
                    <td class="px-4 py-3 font-medium"><?= htmlspecialchars($appr['full_name']) ?></td>
                    <td class="px-4 py-3"><?= ucfirst($appr['permission_type']) ?></td>
                    <td class="px-4 py-3"><?= date('d/m/Y', strtotime($appr['start_date'])) ?> - <?= date('d/m/Y', strtotime($appr['end_date'])) ?></td>
                    <td class="px-4 py-3"><?= htmlspecialchars(substr($appr['reason'], 0, 30)) ?>...</td>
                    <td class="px-4 py-3 flex gap-2">
                        <form method="POST" action="/admin/approvals/<?= $appr['id'] ?>/approve" class="inline">
                            <textarea name="note" placeholder="Catatan..." class="hidden"></textarea>
                            <button type="submit" class="px-3 py-1 bg-green-500 text-white rounded text-xs">
                                Setujui
                            </button>
                        </form>
                        <form method="POST" action="/admin/approvals/<?= $appr['id'] ?>/reject" class="inline">
                            <textarea name="note" placeholder="Alasan..." class="hidden"></textarea>
                            <button type="submit" class="px-3 py-1 bg-red-500 text-white rounded text-xs">
                                Tolak
                            </button>
                        </form>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
