<div class="max-w-[1600px] mx-auto p-6">
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Menunggu Persetujuan</h1>
            <p class="text-gray-500 text-sm">Review hasil pekerjaan karyawan sebelum diselesaikan.</p>
        </div>
        <a href="<?= BASEURL ?>/admin/tasks" class="text-gray-500 hover:text-indigo-600 font-bold text-sm">
            <i class="fas fa-arrow-left mr-1"></i> Kembali ke Task Board
        </a>
    </div>

    <?php if(empty($tasks)): ?>
        <div class="bg-white rounded-2xl border border-dashed border-gray-300 p-12 text-center">
            <div class="w-16 h-16 bg-green-50 text-green-500 rounded-full flex items-center justify-center mx-auto mb-4 text-2xl">
                <i class="fas fa-check"></i>
            </div>
            <h3 class="font-bold text-gray-800">Semua Beres!</h3>
            <p class="text-gray-500 text-sm">Tidak ada tugas yang menunggu persetujuan saat ini.</p>
        </div>
    <?php else: ?>
        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
            <?php foreach($tasks as $task): ?>
            <div class="bg-white rounded-2xl border border-gray-200 shadow-sm hover:shadow-md transition p-6 relative overflow-hidden group">
                <div class="absolute top-0 left-0 w-1.5 h-full" style="background-color: <?= $task['cat_color'] ?? '#cbd5e1' ?>"></div>

                <div class="pl-4">
                    <div class="flex justify-between items-start mb-2">
                        <span class="text-xs font-bold text-gray-400"><?= $task['task_code'] ?></span>
                        <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-yellow-100 text-yellow-700 uppercase">
                            Butuh Approval
                        </span>
                    </div>

                    <h3 class="font-bold text-gray-800 text-lg mb-2 leading-tight">
                        <a href="<?= BASEURL ?>/admin/tasks/details?id=<?= $task['id'] ?>" class="hover:text-indigo-600 transition">
                            <?= htmlspecialchars($task['title']) ?>
                        </a>
                    </h3>

                    <div class="flex items-center gap-3 mb-4">
                        <div class="flex items-center gap-2">
                            <?php if(!empty($task['profile_photo_path'])): ?>
                                <img src="<?= BASEURL ?>/uploads/profiles/<?= $task['profile_photo_path'] ?>" class="w-6 h-6 rounded-full object-cover">
                            <?php else: ?>
                                <div class="w-6 h-6 rounded-full bg-gray-100 flex items-center justify-center text-[10px] font-bold">
                                    <?= substr($task['first_name'] ?? 'U', 0, 1) ?>
                                </div>
                            <?php endif; ?>
                            <span class="text-xs text-gray-600 font-medium">
                                <?= ($task['first_name'] ?? 'User') . ' ' . ($task['last_name'] ?? '') ?>
                            </span>
                        </div>
                        <span class="text-gray-300 text-xs">|</span>
                        <span class="text-xs text-gray-400">
                            Due: <?= date('d M', strtotime($task['due_date'])) ?>
                        </span>
                    </div>

                    <div class="flex gap-2 pt-4 border-t border-gray-100">
                        <button onclick="processApproval(<?= $task['id'] ?>, 'completed')" class="flex-1 py-2 bg-green-50 text-green-600 rounded-lg text-xs font-bold hover:bg-green-100 transition flex items-center justify-center gap-1">
                            <i class="fas fa-check"></i> Terima
                        </button>
                        <button onclick="processApproval(<?= $task['id'] ?>, 'review')" class="flex-1 py-2 bg-red-50 text-red-600 rounded-lg text-xs font-bold hover:bg-red-100 transition flex items-center justify-center gap-1">
                            <i class="fas fa-times"></i> Tolak (Revisi)
                        </button>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

<script>
const BASE_URL = '<?= BASEURL ?>';

function processApproval(id, status) {
    const action = status === 'completed' ? 'MENYETUJUI' : 'MENOLAK';
    if(!confirm('Apakah Anda yakin ingin ' + action + ' tugas ini?')) return;

    fetch(BASE_URL + '/admin/tasks/ajax_update_status', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ id: id, status: status })
    })
    .then(r => r.json())
    .then(res => {
        if(res.success) {
            alert('Status berhasil diperbarui!');
            location.reload();
        } else {
            alert('Gagal memperbarui status.');
        }
    });
}
</script>