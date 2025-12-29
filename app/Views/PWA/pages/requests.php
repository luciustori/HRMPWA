<!-- app/Views/PWA/pages/requests.php -->
<div class="space-y-4">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold">Pengajuan Saya</h2>
        <a href="/pwa/requests/create" class="px-4 py-2 bg-blue-500 hover:bg-blue-600 text-white rounded-lg text-sm font-medium transition">
            + Buat
        </a>
    </div>

    <?php if (empty($requests)): ?>
    <div class="bg-gray-800 border border-gray-700 rounded-xl p-8 text-center">
        <p class="text-gray-400 text-sm">Belum ada pengajuan</p>
    </div>
    <?php else: ?>
    <div class="space-y-3">
        <?php foreach ($requests as $req): ?>
        <div class="bg-gray-800 border border-gray-700 rounded-lg p-4 hover:border-gray-600 transition">
            <div class="flex justify-between items-start mb-2">
                <div>
                    <h3 class="font-semibold">
                        <?php 
                        $types = ['ijin' => 'Ijin', 'cuti' => 'Cuti', 'lembur' => 'Lembur', 'sakit' => 'Sakit'];
                        echo $types[$req['permission_type']] ?? ucfirst($req['permission_type']);
                        ?>
                    </h3>
                    <p class="text-xs text-gray-400">
                        <?= date('d/m/Y', strtotime($req['start_date'])) ?> - 
                        <?= date('d/m/Y', strtotime($req['end_date'])) ?>
                    </p>
                </div>
                <span class="px-2 py-1 text-xs font-medium rounded-full 
                    <?php
                    if ($req['status'] == 'approved') echo 'bg-green-500/20 text-green-400';
                    elseif ($req['status'] == 'rejected') echo 'bg-red-500/20 text-red-400';
                    else echo 'bg-yellow-500/20 text-yellow-400';
                    ?>
                ">
                    <?php 
                    $statuses = ['pending' => 'Menunggu', 'approved' => 'Disetujui', 'rejected' => 'Ditolak'];
                    echo $statuses[$req['status']] ?? ucfirst($req['status']);
                    ?>
                </span>
            </div>
            <p class="text-xs text-gray-400"><?= htmlspecialchars(substr($req['reason'], 0, 50)) ?>...</p>
        </div>
        <?php endforeach; ?>
    </div>
    <?php endif; ?>
</div>
