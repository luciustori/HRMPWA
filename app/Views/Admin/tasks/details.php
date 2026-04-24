<div class="max-w-[1600px] mx-auto p-6">

    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-6">
        <div>
            <div class="flex items-center gap-2 text-sm text-gray-500 mb-1">
                <a href="<?= BASEURL ?>/admin/tasks" class="hover:text-indigo-600">Tasks</a>
                <i class="fas fa-chevron-right text-xs"></i>
                <span class="text-gray-900 font-medium"><?= $task['task_code'] ?? 'TSK-000' ?></span>
            </div>
            <h1 class="text-3xl font-bold text-gray-900"><?= htmlspecialchars($task['title'] ?? '') ?></h1>
        </div>
        
        <div class="flex gap-2">
            <a href="<?= BASEURL ?>/admin/tasks" class="px-4 py-2 bg-white border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition font-medium text-sm">
                <i class="fas fa-arrow-left mr-2"></i> Kembali
            </a>
            <a href="<?= BASEURL ?>/admin/tasks/edit?id=<?= $task['id'] ?>" class="px-4 py-2 bg-indigo-50 text-indigo-700 rounded-lg hover:bg-indigo-100 transition font-medium text-sm border border-indigo-200">
                <i class="fas fa-edit mr-2"></i> Edit
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 xl:grid-cols-3 gap-8">
        
        <div class="xl:col-span-2 space-y-8">
            
            <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-6 relative overflow-hidden">
                <div class="flex justify-between items-start mb-6 border-b border-gray-100 pb-4">
                    <h3 class="font-bold text-gray-800 flex items-center gap-2">
                        <i class="fas fa-align-left text-gray-400"></i> Deskripsi Tugas
                    </h3>
                    
                    <?php 
                    $status = $task['status'] ?? 'pending';
                    $statusColor = match($status) {
                        'completed' => 'bg-green-100 text-green-700 border-green-200',
                        'in_progress' => 'bg-blue-100 text-blue-700 border-blue-200',
                        'review' => 'bg-purple-100 text-purple-700 border-purple-200',
                        'submitted' => 'bg-yellow-100 text-yellow-700 border-yellow-200',
                        default => 'bg-gray-100 text-gray-600 border-gray-200'
                    };
                    $statusLabel = str_replace('_', ' ', $status);
                    if($status == 'submitted') $statusLabel = 'Waiting Approval';
                    ?>
                    <span id="statusBadge" class="px-3 py-1 rounded-full text-xs font-bold border <?= $statusColor ?> uppercase tracking-wide">
                        <?= $statusLabel ?>
                    </span>
                </div>
                
                <div class="prose max-w-none text-gray-600 text-sm leading-relaxed whitespace-pre-line">
                    <?= !empty($task['description']) ? htmlspecialchars($task['description']) : '<em class="text-gray-400">Tidak ada deskripsi tambahan.</em>' ?>
                </div>
            </div>

            <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-6">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="font-bold text-gray-800 flex items-center gap-2">
                        <i class="fas fa-check-square text-indigo-500"></i> Checklist Item
                    </h3>
                    <span class="text-xs font-bold text-gray-500" id="progressText">0% Selesai</span>
                </div>

                <div class="w-full bg-gray-100 rounded-full h-2 mb-6">
                    <div id="progressBar" class="bg-green-500 h-2 rounded-full transition-all duration-500" style="width: 0%"></div>
                </div>

                <div id="checklistContainer" class="space-y-2 mb-4">
                    <?php if(!empty($checklist)): ?>
                        <?php foreach($checklist as $item): ?>
                        <label class="flex items-center gap-3 p-3 rounded-lg border border-transparent hover:bg-gray-50 cursor-pointer transition group">
                            <input type="checkbox" 
                                   class="w-5 h-5 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500 checklist-input" 
                                   value="<?= $item['id'] ?>" 
                                   <?= $item['is_completed'] ? 'checked' : '' ?>
                                   onchange="toggleChecklist(this)">
                            <span class="text-sm text-gray-700 group-hover:text-gray-900 checklist-text <?= $item['is_completed'] ? 'line-through text-gray-400' : '' ?>">
                                <?= htmlspecialchars($item['item_text']) ?>
                            </span>
                        </label>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <p class="text-sm text-gray-400 italic pl-2">Tidak ada item checklist.</p>
                    <?php endif; ?>
                </div>

                <div class="flex gap-2 mt-4 pt-4 border-t border-gray-100">
                    <input type="text" id="newChecklistInput" placeholder="Tambah item pekerjaan..." class="flex-1 px-4 py-2 bg-gray-50 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-indigo-200 outline-none">
                    <button onclick="addChecklist()" class="px-4 py-2 bg-white border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 text-sm font-bold">
                        <i class="fas fa-plus"></i> Tambah
                    </button>
                </div>
            </div>

            <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-6">
                <div class="flex justify-between items-center mb-6">
                    <h3 class="font-bold text-gray-800 flex items-center gap-2">
                        <i class="fas fa-history text-orange-500"></i> Log Pengerjaan
                    </h3>
                    <button onclick="document.getElementById('logForm').classList.toggle('hidden')" class="text-xs font-bold text-indigo-600 bg-indigo-50 px-3 py-1.5 rounded-lg hover:bg-indigo-100 transition">
                        <i class="fas fa-plus mr-1"></i> Catat Log
                    </button>
                </div>

                <div id="logForm" class="hidden mb-6 bg-gray-50 p-4 rounded-xl border border-gray-200 animate-fade-in">
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-3 mb-3">
                        <div class="md:col-span-1">
                            <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Tanggal</label>
                            <input type="date" id="logDate" value="<?= date('Y-m-d') ?>" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm bg-white">
                        </div>
                        <div class="md:col-span-1">
                            <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Jam Kerja</label>
                            <div class="relative">
                                <input type="number" id="logHours" value="1" min="0.5" step="0.5" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm bg-white">
                                <span class="absolute right-3 top-2 text-xs text-gray-400">Jam</span>
                            </div>
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Keterangan Aktivitas</label>
                            <input type="text" id="logDesc" placeholder="Contoh: Riset kompetitor, coding fitur login..." class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm bg-white">
                        </div>
                    </div>
                    <div class="flex justify-end">
                        <button onclick="addLog()" class="px-4 py-2 bg-gray-800 text-white rounded-lg text-xs font-bold hover:bg-black transition shadow-sm">
                            <i class="fas fa-save mr-1"></i> Simpan Log
                        </button>
                    </div>
                </div>

                <div class="space-y-0 relative border-l-2 border-gray-100 ml-3">
                    <?php if(empty($logs)): ?>
                        <div class="pl-6 pb-2">
                            <p class="text-sm text-gray-400 italic">Belum ada catatan log pengerjaan.</p>
                        </div>
                    <?php else: ?>
                        <?php foreach($logs as $log): ?>
                        <div class="mb-6 pl-6 relative group">
                            <div class="absolute -left-[9px] top-1 w-4 h-4 rounded-full border-4 border-white bg-orange-400 shadow-sm group-hover:scale-110 transition"></div>
                            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-1 mb-2">
                                <div class="flex items-center gap-2">
                                    <span class="text-xs font-bold text-gray-600 bg-gray-100 px-2 py-0.5 rounded border border-gray-200">
                                        <?= date('d M Y', strtotime($log['log_date'])) ?>
                                    </span>
                                    <span class="text-xs font-bold text-indigo-600 bg-indigo-50 px-2 py-0.5 rounded border border-indigo-100">
                                        <i class="far fa-clock mr-1"></i> <?= floatval($log['hours_spent']) ?> Jam
                                    </span>
                                </div>
                                <span class="text-[10px] text-gray-400">
                                    By: <?= $log['first_name'] ?? 'User' ?>
                                </span>
                            </div>
                            <div class="text-sm text-gray-700 leading-relaxed bg-white p-3 rounded-lg border border-gray-100 shadow-sm">
                                <?= htmlspecialchars($log['description']) ?>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>

            <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-6">
                <h3 class="font-bold text-gray-800 mb-6 flex items-center gap-2">
                    <i class="fas fa-comments text-blue-500"></i> Diskusi Tim
                </h3>
                <div class="space-y-6 mb-8 max-h-[400px] overflow-y-auto pr-2 custom-scrollbar">
                    <?php if(empty($comments)): ?>
                        <div class="text-center py-8 text-gray-400">
                            <i class="far fa-comment-dots text-4xl mb-2 opacity-30"></i>
                            <p class="text-sm">Belum ada diskusi. Mulai percakapan sekarang!</p>
                        </div>
                    <?php else: ?>
                        <?php foreach($comments as $com): ?>
                        <div class="flex gap-4">
                            <div class="flex-shrink-0">
                                <?php if(!empty($com['profile_photo_path'])): ?>
                                    <img src="<?= BASEURL ?>/uploads/profiles/<?= $com['profile_photo_path'] ?>" class="w-10 h-10 rounded-full object-cover border border-gray-100">
                                <?php else: ?>
                                    <div class="w-10 h-10 rounded-full bg-indigo-100 flex items-center justify-center text-indigo-600 font-bold text-sm border border-indigo-50">
                                        <?= substr($com['first_name'] ?? 'U', 0, 1) ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                            <div class="flex-1">
                                <div class="flex items-center gap-2 mb-1">
                                    <span class="font-bold text-gray-900 text-xs"><?= ($com['first_name'] ?? 'User') . ' ' . ($com['last_name'] ?? '') ?></span>
                                    <span class="text-[10px] text-gray-400"><?= date('d M H:i', strtotime($com['created_at'])) ?></span>
                                </div>
                                <div class="bg-gray-50 p-3 rounded-r-xl rounded-bl-xl text-gray-700 text-sm border border-gray-100 leading-relaxed">
                                    <?= nl2br(htmlspecialchars($com['comment'])) ?>
                                </div>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
                <div class="flex gap-3 items-start">
                    <div class="w-8 h-8 rounded-full bg-gray-200 flex items-center justify-center text-xs font-bold text-gray-500 flex-shrink-0">Me</div>
                    <div class="flex-1">
                        <textarea id="commentInput" rows="2" class="w-full p-3 bg-white border border-gray-300 rounded-xl text-sm focus:ring-2 focus:ring-indigo-200 outline-none resize-none" placeholder="Tulis update atau pertanyaan..."></textarea>
                        <div class="flex justify-end mt-2">
                            <button onclick="postComment()" class="px-6 py-2 bg-indigo-600 text-white rounded-lg text-sm font-bold hover:bg-indigo-700 transition shadow-sm">
                                Kirim <i class="fas fa-paper-plane ml-1"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>

        </div>

        <div class="xl:col-span-1 space-y-6">

            <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-5">
                <h4 class="text-xs font-bold text-gray-400 uppercase mb-3">Tindakan</h4>
                
                <?php 
                    $currStatus = $task['status']; 
                    $isAdmin = in_array($_SESSION['role'] ?? '', ['admin', 'super_admin', 'manager']);
                ?>

                <div class="grid grid-cols-1 gap-2">
                    <?php if(in_array($currStatus, ['pending', 'in_progress', 'review'])): ?>
                        <?php if($currStatus != 'in_progress'): ?>
                            <button onclick="updateStatus('in_progress')" class="px-3 py-2 text-xs font-bold rounded-lg border border-blue-200 bg-blue-50 text-blue-600 hover:bg-blue-100 transition text-center">
                                <i class="fas fa-play mr-1"></i> Kerjakan (In Progress)
                            </button>
                        <?php endif; ?>
                        
                        <button onclick="updateStatus('completed')" class="px-3 py-2 text-xs font-bold rounded-lg border border-green-200 bg-green-50 text-green-600 hover:bg-green-100 transition text-center">
                            <i class="fas fa-paper-plane mr-1"></i> Selesai & Ajukan Approval
                        </button>
                    <?php endif; ?>

                    <?php if($currStatus == 'submitted'): ?>
                        <div class="text-center p-3 bg-yellow-50 rounded-lg border border-yellow-100 mb-2">
                            <p class="text-xs text-yellow-700 font-bold mb-1"><i class="fas fa-clock"></i> Menunggu Approval</p>
                            <p class="text-[10px] text-yellow-600">Menunggu manajer mereview tugas ini.</p>
                        </div>
                        
                        <?php if($isAdmin): ?>
                            <div class="grid grid-cols-2 gap-2 mt-2">
                                <button onclick="updateStatus('completed')" class="px-3 py-2 text-xs font-bold rounded-lg bg-green-600 text-white hover:bg-green-700 transition">
                                    <i class="fas fa-check"></i> Approve
                                </button>
                                <button onclick="updateStatus('review')" class="px-3 py-2 text-xs font-bold rounded-lg bg-red-100 text-red-600 hover:bg-red-200 transition">
                                    <i class="fas fa-times"></i> Reject
                                </button>
                            </div>
                        <?php endif; ?>
                    <?php endif; ?>

                    <?php if($currStatus == 'completed'): ?>
                        <div class="text-center p-3 bg-green-50 rounded-lg border border-green-100">
                            <p class="text-xs text-green-700 font-bold"><i class="fas fa-check-circle"></i> Tugas Selesai</p>
                            <p class="text-[10px] text-green-600 mt-1">Disetujui pada: <?= date('d M Y H:i', strtotime($task['completed_at'] ?? 'now')) ?></p>
                            
                            <?php if($isAdmin): ?>
                                <button onclick="updateStatus('in_progress')" class="mt-2 text-[10px] text-gray-400 hover:text-red-500 underline">
                                    Buka kembali tugas
                                </button>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-5 space-y-5">
                <div class="space-y-3">
                    <div class="flex gap-3">
                        <div class="w-8 h-8 rounded-lg bg-gray-50 flex items-center justify-center text-gray-400"><i class="far fa-calendar-plus"></i></div>
                        <div>
                            <p class="text-xs text-gray-400">Tanggal Mulai</p>
                            <p class="text-sm font-bold text-gray-800"><?= date('d M Y', strtotime($task['start_date'] ?? 'now')) ?></p>
                        </div>
                    </div>
                    <div class="flex gap-3">
                        <div class="w-8 h-8 rounded-lg bg-red-50 flex items-center justify-center text-red-500"><i class="far fa-calendar-times"></i></div>
                        <div>
                            <p class="text-xs text-gray-400">Tenggat Waktu</p>
                            <p class="text-sm font-bold text-gray-800 <?= (strtotime($task['due_date'] ?? '') < time() && $task['status'] != 'completed') ? 'text-red-600' : '' ?>">
                                <?= date('d M Y', strtotime($task['due_date'] ?? 'now')) ?>
                            </p>
                        </div>
                    </div>
                </div>
                <hr class="border-gray-100">
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <p class="text-xs text-gray-400 font-bold uppercase mb-1">Prioritas</p>
                        <?php 
                        $prio = $task['priority'] ?? 'medium';
                        $prioColor = match($prio) { 'urgent' => 'text-red-600 bg-red-50', 'high' => 'text-orange-600 bg-orange-50', default => 'text-blue-600 bg-blue-50' };
                        ?>
                        <span class="text-xs font-bold px-2 py-1 rounded uppercase <?= $prioColor ?> border border-transparent"><?= $prio ?></span>
                    </div>
                    <div>
                        <p class="text-xs text-gray-400 font-bold uppercase mb-1">Kategori</p>
                        <span class="text-xs font-bold text-gray-700 truncate block"><?= $task['category_name'] ?? 'General' ?></span>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-5">
                <h3 class="font-bold text-gray-800 text-sm mb-4">Tim Bertugas</h3>
                <div class="flex items-center gap-3 mb-4">
                    <?php if(!empty($task['profile_photo_path'])): ?>
                        <img src="<?= BASEURL ?>/uploads/profiles/<?= $task['profile_photo_path'] ?>" class="w-10 h-10 rounded-full object-cover border-2 border-white shadow-sm">
                    <?php else: ?>
                        <div class="w-10 h-10 rounded-full bg-indigo-100 flex items-center justify-center text-indigo-600 font-bold"><?= substr($task['first_name'] ?? 'U', 0, 1) ?></div>
                    <?php endif; ?>
                    <div class="overflow-hidden">
                        <p class="text-xs text-gray-400">PIC (Penanggung Jawab)</p>
                        <p class="font-bold text-gray-800 truncate"><?= ($task['first_name'] ?? '-') . ' ' . ($task['last_name'] ?? '') ?></p>
                        <p class="text-xs text-gray-500 truncate"><?= $task['position_name'] ?? 'Employee' ?></p>
                    </div>
                </div>
                <hr class="border-gray-100 my-3">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 rounded-full bg-gray-100 flex items-center justify-center text-gray-500 text-xs"><i class="fas fa-user-edit"></i></div>
                    <div>
                        <p class="text-xs text-gray-400">Dibuat Oleh</p>
                        <p class="text-sm font-medium text-gray-700"><?= $task['creator_first'] ?? ($task['creator_username'] ?? 'System') ?></p>
                    </div>
                </div>
            </div>

            <?php if(!empty($task['is_recurring'])): ?>
            <div class="bg-gradient-to-br from-purple-600 to-indigo-600 rounded-2xl p-5 text-white shadow-lg relative overflow-hidden">
                <div class="absolute right-0 top-0 opacity-10"><i class="fas fa-sync-alt text-8xl -mr-4 -mt-4"></i></div>
                <div class="flex items-center gap-2 mb-2">
                    <i class="fas fa-redo-alt"></i> <span class="font-bold text-sm">Tugas Rutin</span>
                </div>
                <p class="text-xs text-purple-100">
                    Diulang setiap <strong><?= ucfirst($task['recurrence_type']) ?></strong> 
                    pada tanggal/hari ke-<strong><?= $task['recurrence_day'] ?></strong>.
                </p>
            </div>
            <?php endif; ?>

            <button onclick="deleteTask(<?= $task['id'] ?>)" class="w-full py-3 rounded-xl border border-red-200 text-red-600 bg-red-50 hover:bg-red-100 transition font-bold text-sm">
                <i class="fas fa-trash-alt mr-2"></i> Hapus Tugas
            </button>
        </div>
    </div>
</div>

<script>
const TASK_ID = <?= $task['id'] ?? 0 ?>;
const BASE_URL = '<?= BASEURL ?>';

// --- CHECKLIST LOGIC ---
function updateProgress() {
    const checks = document.querySelectorAll('.checklist-input');
    if(checks.length === 0) return;
    const checked = document.querySelectorAll('.checklist-input:checked');
    const percent = Math.round((checked.length / checks.length) * 100);
    document.getElementById('progressBar').style.width = percent + '%';
    document.getElementById('progressText').innerText = percent + '% Selesai';
}
updateProgress();

function toggleChecklist(el) {
    const span = el.nextElementSibling;
    if(el.checked) span.classList.add('line-through', 'text-gray-400');
    else span.classList.remove('line-through', 'text-gray-400');
    updateProgress();
    fetch(BASE_URL + '/admin/tasks/ajax_toggle_checklist', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ id: el.value, checked: el.checked })
    });
}

function addChecklist() {
    const input = document.getElementById('newChecklistInput');
    const text = input.value.trim();
    if(!text) return;
    fetch(BASE_URL + '/admin/tasks/ajax_add_checklist', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ task_id: TASK_ID, item_text: text })
    }).then(r => r.json()).then(res => { if(res.success) location.reload(); });
}

// --- LOG TIME & COMMENTS ---
function addLog() {
    const date = document.getElementById('logDate').value;
    const hours = document.getElementById('logHours').value;
    const desc = document.getElementById('logDesc').value.trim();
    if(!desc) { alert('Isi keterangan aktivitas.'); return; }
    fetch(BASE_URL + '/admin/tasks/ajax_add_log', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ task_id: TASK_ID, log_date: date, hours_spent: hours, description: desc })
    }).then(r => r.json()).then(res => { if(res.success) location.reload(); else alert('Gagal.'); });
}

function postComment() {
    const input = document.getElementById('commentInput');
    const text = input.value.trim();
    if(!text) return;
    fetch(BASE_URL + '/admin/tasks/ajax_add_comment', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ task_id: TASK_ID, comment: text })
    }).then(r => r.json()).then(res => { if(res.success) location.reload(); });
}

// --- STATUS UPDATE (LOGIC BARU) ---
function updateStatus(newStatus) {
    // Pesan konfirmasi beda tergantung status
    let msg = 'Ubah status tugas?';
    if(newStatus == 'completed') msg = 'Apakah tugas sudah selesai dan siap diajukan untuk approval?';
    if(newStatus == 'review') msg = 'Tolak tugas ini dan minta revisi?';
    
    if(!confirm(msg)) return;

    fetch(BASE_URL + '/admin/tasks/ajax_update_status', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ id: TASK_ID, status: newStatus })
    }).then(r => r.json()).then(res => { if(res.success) location.reload(); });
}

function deleteTask(id) {
    if(confirm('Yakin hapus tugas ini?')) window.location.href = BASE_URL + '/admin/tasks/delete?id=' + id;
}
</script>

<style>
.custom-scrollbar::-webkit-scrollbar { width: 4px; }
.custom-scrollbar::-webkit-scrollbar-thumb { background-color: #cbd5e1; border-radius: 4px; }
.custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
</style>