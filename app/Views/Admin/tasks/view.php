<script>
function switchTab(tabName) {
    // Hide all tabs
    document.querySelectorAll('.tab-content').forEach(tab => tab.classList.remove('active'));
    document.querySelectorAll('.tab-button').forEach(btn => btn.classList.remove('active'));
    
    // Show selected
    document.getElementById('tab-' + tabName).classList.add('active');
    
    // Highlight button (cari button yang punya onclick sesuai tabName)
    document.querySelectorAll('.tab-button').forEach(btn => {
        if(btn.getAttribute('onclick').includes(tabName)) {
            btn.classList.add('active');
        }
    });
}

function updateStatus(newStatus) {
    if (!confirm('Update status task ini?')) return;
    
    fetch('<?= BASEURL ?>/admin/tasks/ajax/update-status', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ task_id: <?= $task['id'] ?>, status: newStatus })
    })
    .then(r => r.json())
    .then(data => {
        if(data.success) location.reload();
        else alert('Error: ' + data.message);
    });
}
</script>

<style>
.tab-content { display: none; }
.tab-content.active { display: block; }
.tab-button.active { border-bottom: 3px solid #3B82F6; color: #3B82F6; font-weight: 600; }
</style>

<div class="max-w-[1600px] mx-auto p-6">
    <div class="mb-6">
        <div class="flex items-center gap-2 text-sm text-gray-600 mb-3">
            <a href="<?= BASEURL ?>/admin/dashboard" class="hover:text-blue-600"><i class="fas fa-home"></i></a>
            <i class="fas fa-chevron-right text-xs"></i>
            <a href="<?= BASEURL ?>/admin/tasks" class="hover:text-blue-600">Tasks</a>
            <i class="fas fa-chevron-right text-xs"></i>
            <span class="font-mono text-gray-900"><?= $task['task_code'] ?></span>
        </div>

        <div class="flex justify-between items-start">
            <div>
                <h1 class="text-3xl font-bold text-gray-900 mb-2"><?= htmlspecialchars($task['title']) ?></h1>
                <div class="flex items-center gap-3">
                    <?php if ($task['category_name']): ?>
                    <span class="px-3 py-1 text-xs font-medium rounded-full border" 
                          style="background-color: <?= $task['cat_color'] ?>20; color: <?= $task['cat_color'] ?>; border-color: <?= $task['cat_color'] ?>50">
                        <i class="fas <?= $task['cat_icon'] ?> mr-1"></i> <?= $task['category_name'] ?>
                    </span>
                    <?php endif; ?>
                    
                    <span class="px-3 py-1 text-xs font-medium rounded-full border bg-gray-100">
                        <?= ucfirst($task['priority']) ?> Priority
                    </span>
                    
                    <span class="px-3 py-1 text-xs font-medium rounded-full border bg-blue-50 text-blue-700">
                        <?= ucfirst(str_replace('_', ' ', $task['status'])) ?>
                    </span>
                </div>
            </div>
            
            <div class="flex gap-2">
                <a href="<?= BASEURL ?>/admin/tasks/edit?id=<?= $task['id'] ?>" class="px-4 py-2 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 text-gray-700 shadow-sm transition">
                    <i class="fas fa-edit mr-2"></i> Edit
                </a>
                <?php if($task['status'] != 'completed'): ?>
                <button onclick="updateStatus('completed')" class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 shadow-sm transition">
                    <i class="fas fa-check mr-2"></i> Mark Complete
                </button>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 xl:grid-cols-4 gap-6">
        
        <div class="xl:col-span-3 space-y-6">
            
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h3 class="text-sm font-bold text-gray-500 uppercase mb-3">Description</h3>
                <div class="prose max-w-none text-gray-700">
                    <?= nl2br(htmlspecialchars($task['description'])) ?>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="border-b border-gray-200 px-6">
                    <nav class="flex gap-6">
                        <button class="tab-button active py-4 text-sm font-medium text-gray-500 hover:text-blue-600 transition" onclick="switchTab('checklist')">
                            <i class="fas fa-tasks mr-2"></i> Checklist
                        </button>
                        <button class="tab-button py-4 text-sm font-medium text-gray-500 hover:text-blue-600 transition" onclick="switchTab('comments')">
                            <i class="fas fa-comments mr-2"></i> Comments
                        </button>
                        <button class="tab-button py-4 text-sm font-medium text-gray-500 hover:text-blue-600 transition" onclick="switchTab('files')">
                            <i class="fas fa-paperclip mr-2"></i> Files
                        </button>
                        <button class="tab-button py-4 text-sm font-medium text-gray-500 hover:text-blue-600 transition" onclick="switchTab('history')">
                            <i class="fas fa-history mr-2"></i> History
                        </button>
                    </nav>
                </div>
                
                <div class="p-6 bg-gray-50 min-h-[300px]">
                    <div id="tab-checklist" class="tab-content active">
                        <?php include APP_PATH . '/Views/admin/tasks/partials/tab_checklist.php'; ?>
                    </div>

                    <div id="tab-comments" class="tab-content">
                        <?php include APP_PATH . '/Views/admin/tasks/partials/tab_comments.php'; ?>
                    </div>

                    <div id="tab-files" class="tab-content">
                        <?php include APP_PATH . '/Views/admin/tasks/partials/tab_attachments.php'; ?>
                    </div>

                    <div id="tab-history" class="tab-content">
                        <?php include APP_PATH . '/Views/admin/tasks/partials/tab_history.php'; ?>
                    </div>
                </div>
            </div>
        </div>

        <div class="xl:col-span-1 space-y-6">
            
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-5">
                <h3 class="text-xs font-bold text-gray-400 uppercase mb-4">Assigned To</h3>
                <div class="flex items-center gap-3 mb-4">
                    <?php if($task['profile_photo_path']): ?>
                        <img src="<?= BASEURL ?>/uploads/profiles/<?= $task['profile_photo_path'] ?>" class="w-10 h-10 rounded-full object-cover">
                    <?php else: ?>
                        <div class="w-10 h-10 rounded-full bg-blue-100 flex items-center justify-center text-blue-600 font-bold">
                            <?= substr($task['first_name'], 0, 1) ?>
                        </div>
                    <?php endif; ?>
                    <div>
                        <p class="font-bold text-gray-900 text-sm"><?= htmlspecialchars($task['first_name'] . ' ' . $task['last_name']) ?></p>
                        <p class="text-xs text-gray-500"><?= $task['employee_number'] ?></p>
                    </div>
                </div>
                
                <div class="border-t pt-4 space-y-3">
                    <div>
                        <p class="text-xs text-gray-500 mb-1">Department</p>
                        <p class="font-medium text-sm text-gray-900"><?= $task['department_name'] ?? '-' ?></p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500 mb-1">Due Date</p>
                        <p class="font-medium text-sm text-red-600">
                            <i class="far fa-calendar-alt mr-1"></i> <?= date('d M Y', strtotime($task['due_date'])) ?>
                        </p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-5">
                <h3 class="text-xs font-bold text-gray-400 uppercase mb-4">Stats</h3>
                <div class="space-y-4">
                    <div>
                        <div class="flex justify-between text-xs mb-1">
                            <span>Completion</span>
                            <span class="font-bold"><?= $task['completion_percentage'] ?>%</span>
                        </div>
                        <div class="w-full bg-gray-100 rounded-full h-2">
                            <div class="bg-green-500 h-2 rounded-full" style="width: <?= $task['completion_percentage'] ?>%"></div>
                        </div>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500 mb-1">Estimated Hours</p>
                        <p class="font-bold text-gray-900"><?= $task['estimated_hours'] ?> Hours</p>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>