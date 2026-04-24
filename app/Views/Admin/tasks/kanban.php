<script src="https://cdnjs.cloudflare.com/ajax/libs/Sortable/1.15.0/Sortable.min.js"></script>

<div class="max-w-[1600px] mx-auto p-6 h-screen flex flex-col">
    
    <div class="flex justify-between items-center mb-6 flex-shrink-0">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Task Board</h1>
            <p class="text-sm text-gray-500">Geser kartu untuk mengubah status tugas</p>
        </div>
        <div class="flex gap-3">
            <a href="<?= BASEURL ?>/admin/tasks" class="px-4 py-2 bg-white border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 shadow-sm transition">
                <i class="fas fa-list mr-2"></i> List View
            </a>
            <a href="<?= BASEURL ?>/admin/tasks/create" class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 shadow-sm transition">
                <i class="fas fa-plus mr-2"></i> New Task
            </a>
        </div>
    </div>

    <div class="flex-1 overflow-x-auto overflow-y-hidden">
        <div class="flex h-full gap-6 pb-4 min-w-[1200px]">
            
            <?php renderColumn('Pending', 'pending', 'bg-gray-100', 'border-gray-200', $tasks); ?>

            <?php renderColumn('In Progress', 'in_progress', 'bg-blue-50', 'border-blue-200', $tasks); ?>

            <?php renderColumn('Review', 'review', 'bg-yellow-50', 'border-yellow-200', $tasks); ?>

            <?php renderColumn('Completed', 'completed', 'bg-green-50', 'border-green-200', $tasks); ?>

        </div>
    </div>
</div>

<?php
// Helper Function untuk Render Kolom (Biar kode rapi)
function renderColumn($title, $status, $bgClass, $borderClass, $allTasks) {
    // Filter tasks for this column
    $columnTasks = array_filter($allTasks, function($t) use ($status) {
        return $t['status'] === $status;
    });
    ?>
    <div class="flex-1 flex flex-col h-full min-w-[300px] bg-gray-50 rounded-xl border <?= $borderClass ?> shadow-sm">
        <div class="p-4 border-b <?= $borderClass ?> <?= $bgClass ?> rounded-t-xl flex justify-between items-center">
            <h3 class="font-bold text-gray-700 uppercase text-xs tracking-wider"><?= $title ?></h3>
            <span class="bg-white px-2 py-0.5 rounded text-xs font-bold text-gray-500 shadow-sm task-count">
                <?= count($columnTasks) ?>
            </span>
        </div>

        <div id="col-<?= $status ?>" data-status="<?= $status ?>" class="kanban-col flex-1 p-3 overflow-y-auto space-y-3 custom-scrollbar">
            <?php foreach ($columnTasks as $task): ?>
                <div class="task-card bg-white p-4 rounded-lg shadow-sm border border-gray-100 cursor-move hover:shadow-md transition-all group relative" 
                     data-id="<?= $task['id'] ?>">
                    
                    <div class="flex justify-between items-start mb-2">
                        <span class="px-2 py-1 rounded text-[10px] font-bold uppercase border"
                              style="background-color: <?= $task['cat_color'] ?>10; color: <?= $task['cat_color'] ?>; border-color: <?= $task['cat_color'] ?>30;">
                            <?= $task['category_name'] ?>
                        </span>
                        
                        <?php 
                        $prioColor = match($task['priority']) {
                            'urgent' => 'text-red-500',
                            'high' => 'text-orange-500',
                            'medium' => 'text-yellow-500',
                            default => 'text-green-500'
                        };
                        ?>
                        <i class="fas fa-flag <?= $prioColor ?> text-xs" title="<?= ucfirst($task['priority']) ?>"></i>
                    </div>

                    <h4 class="font-bold text-gray-800 text-sm mb-1 line-clamp-2">
                        <a href="<?= BASEURL ?>/admin/tasks/details?id=<?= $task['id'] ?>" class="hover:text-indigo-600">
                            <?= htmlspecialchars($task['title']) ?>
                        </a>
                    </h4>
                    
                    <div class="text-xs text-gray-400 mb-3 font-mono"><?= $task['task_code'] ?></div>

                    <div class="flex justify-between items-center border-t pt-3 mt-2">
                        <div class="flex items-center gap-2">
                            <?php if($task['profile_photo_path']): ?>
                                <img src="<?= BASEURL ?>/uploads/profiles/<?= $task['profile_photo_path'] ?>" class="w-6 h-6 rounded-full object-cover">
                            <?php else: ?>
                                <div class="w-6 h-6 rounded-full bg-gray-200 flex items-center justify-center text-[10px] font-bold text-gray-500">
                                    <?= substr($task['first_name'], 0, 1) ?>
                                </div>
                            <?php endif; ?>
                            <span class="text-xs text-gray-500 truncate max-w-[80px]"><?= $task['first_name'] ?></span>
                        </div>
                        
                        <div class="text-xs <?= (strtotime($task['due_date']) < time()) ? 'text-red-500 font-bold' : 'text-gray-400' ?>">
                            <?= date('d M', strtotime($task['due_date'])) ?>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
    <?php
}
?>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const columns = document.querySelectorAll('.kanban-col');

    columns.forEach(col => {
        new Sortable(col, {
            group: 'tasks', // Agar bisa pindah antar kolom
            animation: 150,
            ghostClass: 'bg-indigo-50', // Efek visual saat drag
            delay: 100, // Mencegah drag tidak sengaja saat klik
            delayOnTouchOnly: true,
            
            // Event saat item dilepas (DROP)
            onEnd: function (evt) {
                const itemEl = evt.item;
                const newStatus = evt.to.getAttribute('data-status');
                const taskId = itemEl.getAttribute('data-id');
                const oldStatus = evt.from.getAttribute('data-status');

                // Update Counter UI
                updateCounters();

                // Hanya kirim request jika status berubah
                if (newStatus !== oldStatus) {
                    updateTaskStatus(taskId, newStatus);
                }
            }
        });
    });

    function updateCounters() {
        document.querySelectorAll('.kanban-col').forEach(col => {
            const count = col.querySelectorAll('.task-card').length;
            col.parentElement.querySelector('.task-count').innerText = count;
        });
    }

    function updateTaskStatus(taskId, status) {
        fetch('<?= BASEURL ?>/admin/tasks/ajax/update-status', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({
                task_id: taskId,
                status: status
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                console.log(`Task ${taskId} moved to ${status}`);
                // Optional: Show toast notification
            } else {
                alert('Gagal update status: ' + data.message);
                // Kembalikan kartu jika gagal (reload page simple fallback)
                location.reload();
            }
        })
        .catch(err => {
            console.error(err);
            alert('Terjadi kesalahan jaringan.');
        });
    }
});
</script>

<style>
.custom-scrollbar::-webkit-scrollbar { width: 4px; }
.custom-scrollbar::-webkit-scrollbar-thumb { background-color: #cbd5e1; border-radius: 4px; }
.custom-scrollbar::-webkit-scrollbar-track { background-color: transparent; }
</style>a