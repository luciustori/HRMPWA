<?php
// SAFETY FIRST: Pastikan variabel array terdefinisi sebagai array kosong jika null
$tasks = $tasks ?? [];
$stats = $stats ?? [];
?>

<div class="max-w-[1920px] mx-auto p-6 space-y-6">

    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Manajemen Tugas</h1>
            <p class="text-gray-500 text-sm">Monitor seluruh pekerjaan tim dalam satu layar.</p>
        </div>
        <div class="flex gap-3">
            <a href="<?= BASEURL ?>/admin/tasks/kanban" class="bg-white border border-gray-300 text-gray-700 px-4 py-2 rounded-lg text-sm font-medium hover:bg-gray-50 transition shadow-sm flex items-center gap-2">
                <i class="fas fa-columns"></i> Kanban Board
            </a>
            <a href="<?= BASEURL ?>/admin/tasks/create" class="bg-indigo-600 text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-indigo-700 transition shadow-lg shadow-indigo-200 flex items-center gap-2">
                <i class="fas fa-plus"></i> Buat Tugas Baru
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 xl:grid-cols-4 gap-6">
        
        <div class="xl:col-span-3 space-y-6">
            
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                <div class="bg-white p-4 rounded-xl border border-gray-200 shadow-sm flex items-center justify-between">
                    <div>
                        <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider">Total Tugas</p>
                        <h3 class="text-2xl font-bold text-gray-800 mt-1"><?= $stats['total'] ?? 0 ?></h3>
                    </div>
                    <div class="w-10 h-10 rounded-full bg-gray-100 flex items-center justify-center text-gray-600">
                        <i class="fas fa-layer-group"></i>
                    </div>
                </div>
                <div class="bg-white p-4 rounded-xl border border-gray-200 shadow-sm flex items-center justify-between border-l-4 border-l-blue-500">
                    <div>
                        <p class="text-[10px] font-bold text-blue-500 uppercase tracking-wider">On Progress</p>
                        <h3 class="text-2xl font-bold text-blue-700 mt-1"><?= $stats['in_progress'] ?? 0 ?></h3>
                    </div>
                    <div class="w-10 h-10 rounded-full bg-blue-50 flex items-center justify-center text-blue-600">
                        <i class="fas fa-spinner fa-spin"></i>
                    </div>
                </div>
                <div class="bg-white p-4 rounded-xl border border-gray-200 shadow-sm flex items-center justify-between border-l-4 border-l-green-500">
                    <div>
                        <p class="text-[10px] font-bold text-green-500 uppercase tracking-wider">Selesai</p>
                        <h3 class="text-2xl font-bold text-green-700 mt-1"><?= $stats['completed'] ?? 0 ?></h3>
                    </div>
                    <div class="w-10 h-10 rounded-full bg-green-50 flex items-center justify-center text-green-600">
                        <i class="fas fa-check"></i>
                    </div>
                </div>
                <div class="bg-red-50 p-4 rounded-xl border border-red-100 shadow-sm flex items-center justify-between">
                    <div>
                        <p class="text-[10px] font-bold text-red-500 uppercase tracking-wider">Terlambat</p>
                        <h3 class="text-2xl font-bold text-red-600 mt-1"><?= $stats['overdue'] ?? 0 ?></h3>
                    </div>
                    <div class="w-10 h-10 rounded-full bg-white flex items-center justify-center text-red-500 shadow-sm">
                        <i class="fas fa-exclamation-triangle"></i>
                    </div>
                </div>
            </div>

            <div class="bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden min-h-[500px] flex flex-col">
                <div class="p-4 border-b border-gray-100 flex flex-col md:flex-row justify-between gap-4 bg-gray-50/50">
                    
                    <div class="flex items-center gap-4 flex-1">
                        <div class="relative w-full md:w-64">
                            <input type="text" id="tableSearch" placeholder="Cari tugas..." class="w-full pl-10 pr-4 py-2 rounded-lg border border-gray-300 focus:ring-2 focus:ring-indigo-200 focus:border-indigo-500 text-sm">
                            <i class="fas fa-search absolute left-3 top-2.5 text-gray-400"></i>
                        </div>
                        
                        <div id="activeDateFilter" class="hidden flex items-center gap-2 bg-indigo-50 text-indigo-700 px-3 py-1.5 rounded-lg text-xs font-bold border border-indigo-100 animate-fade-in">
                            <i class="fas fa-calendar-check"></i>
                            <span id="activeDateText"></span>
                            <button onclick="clearDateFilter()" class="hover:text-red-500 ml-1"><i class="fas fa-times"></i></button>
                        </div>
                    </div>

                    <div class="flex gap-2">
                        <select id="statusFilter" class="px-3 py-2 bg-white border border-gray-300 rounded-lg text-sm text-gray-600 focus:ring-2 focus:ring-indigo-200">
                            <option value="">Semua Status</option>
                            <option value="pending">Pending</option>
                            <option value="in_progress">In Progress</option>
                            <option value="completed">Completed</option>
                        </select>
                    </div>
                </div>

                <div class="overflow-x-auto flex-1">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-gray-50 text-gray-500 text-xs uppercase font-bold tracking-wider border-b border-gray-200">
                                <th class="p-4 w-12">#</th>
                                <th class="p-4">Info Tugas</th>
                                <th class="p-4">PIC / Creator</th>
                                <th class="p-4">Status</th>
                                <th class="p-4">Deadline</th>
                                <th class="p-4 text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody id="tasksTableBody" class="divide-y divide-gray-100 text-sm">
                            <?php if(empty($tasks)): ?>
                                <tr>
                                    <td colspan="6" class="p-10 text-center text-gray-500">
                                        <div class="flex flex-col items-center justify-center">
                                            <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mb-3 text-gray-400 text-2xl">
                                                <i class="far fa-folder-open"></i>
                                            </div>
                                            <p class="font-medium">Belum ada data tugas.</p>
                                            <p class="text-xs text-gray-400 mt-1">Buat tugas baru untuk memulai.</p>
                                        </div>
                                    </td>
                                </tr>
                            <?php else: ?>
                                <?php foreach($tasks as $index => $task): ?>
                                <tr class="hover:bg-gray-50 transition group status-row" 
                                    data-status="<?= $task['status'] ?? '' ?>"
                                    data-date="<?= $task['due_date'] ?? '' ?>">
                                    
                                    <td class="p-4 text-gray-400 font-mono text-xs"><?= $index + 1 ?></td>
                                    <td class="p-4">
                                        <div class="flex items-center gap-2 mb-1">
                                            <?php if(!empty($task['category_name'])): ?>
                                                <span class="text-[10px] px-1.5 rounded border" 
                                                      style="color:<?= $task['cat_color'] ?? '#666' ?>; 
                                                             border-color:<?= ($task['cat_color'] ?? '#666').'30' ?>; 
                                                             background:<?= ($task['cat_color'] ?? '#666').'10' ?>">
                                                    <?= htmlspecialchars($task['category_name']) ?>
                                                </span>
                                            <?php endif; ?>
                                            <span class="text-xs text-gray-400 font-mono">#<?= $task['task_code'] ?? 'N/A' ?></span>
                                        </div>
                                        <a href="<?= BASEURL ?>/admin/tasks/details?id=<?= $task['id'] ?>" class="font-bold text-gray-800 hover:text-indigo-600 line-clamp-1">
                                            <?= htmlspecialchars($task['title'] ?? 'No Title') ?>
                                        </a>
                                        <?php if(!empty($task['is_recurring'])): ?>
                                            <div class="mt-1 inline-flex items-center gap-1 text-[10px] text-purple-600 bg-purple-50 px-1.5 py-0.5 rounded border border-purple-100">
                                                <i class="fas fa-sync-alt"></i> Rutin: <?= ucfirst($task['recurrence_type'] ?? '') ?>
                                            </div>
                                        <?php endif; ?>
                                    </td>
                                    <td class="p-4">
                                        <div class="flex items-center gap-3">
                                            <?php if(!empty($task['profile_photo_path'])): ?>
                                                <img src="<?= BASEURL ?>/uploads/profiles/<?= $task['profile_photo_path'] ?>" class="w-8 h-8 rounded-full object-cover border border-gray-200">
                                            <?php else: ?>
                                                <div class="w-8 h-8 rounded-full bg-indigo-100 flex items-center justify-center text-indigo-600 text-xs font-bold">
                                                    <?= substr($task['first_name'] ?? 'U', 0, 1) ?>
                                                </div>
                                            <?php endif; ?>
                                            <div class="leading-tight">
                                                <p class="font-bold text-gray-700 text-xs"><?= ($task['first_name'] ?? '-') ?></p>
                                                <p class="text-[10px] text-gray-400">By: <?= $task['creator_name'] ?? 'System' ?></p>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="p-4">
                                        <?php 
                                        $status = $task['status'] ?? 'pending';
                                        $statusClass = match($status) {
                                            'completed' => 'bg-green-100 text-green-700',
                                            'in_progress' => 'bg-blue-100 text-blue-700',
                                            'review' => 'bg-purple-100 text-purple-700',
                                            default => 'bg-gray-100 text-gray-600'
                                        };
                                        ?>
                                        <span class="px-2 py-0.5 rounded text-xs font-bold <?= $statusClass ?> uppercase">
                                            <?= str_replace('_', ' ', $status) ?>
                                        </span>
                                    </td>
                                    <td class="p-4">
                                        <span class="text-xs font-medium text-gray-600">
                                            <?= date('d M', strtotime($task['due_date'] ?? 'now')) ?>
                                        </span>
                                    </td>
                                    <td class="p-4 text-center">
                                        <a href="<?= BASEURL ?>/admin/tasks/edit?id=<?= $task['id'] ?>" class="text-gray-400 hover:text-indigo-600 mx-1"><i class="fas fa-pencil-alt"></i></a>
                                        <button onclick="deleteTask(<?= $task['id'] ?>)" class="text-gray-400 hover:text-red-600 mx-1"><i class="fas fa-trash"></i></button>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                    
                    <div id="noDataMessage" class="hidden p-8 text-center text-gray-400">
                        <i class="far fa-calendar-times text-4xl mb-2 text-gray-300"></i>
                        <p class="text-sm">Tidak ada tugas pada filter ini.</p>
                        <button onclick="clearDateFilter()" class="text-indigo-600 text-xs font-bold mt-2 hover:underline">Reset Filter</button>
                    </div>

                </div>
            </div>
        </div>

        <div class="xl:col-span-1 space-y-6">
            
            <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-5">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="font-bold text-gray-800 text-sm">Kalender Tugas</h3>
                    <div class="text-xs font-bold text-indigo-600" id="currentMonthYear"></div>
                </div>
                
                <div class="grid grid-cols-7 gap-1 text-center mb-2">
                    <div class="text-[10px] text-gray-400 font-bold">Mo</div>
                    <div class="text-[10px] text-gray-400 font-bold">Tu</div>
                    <div class="text-[10px] text-gray-400 font-bold">We</div>
                    <div class="text-[10px] text-gray-400 font-bold">Th</div>
                    <div class="text-[10px] text-gray-400 font-bold">Fr</div>
                    <div class="text-[10px] text-gray-400 font-bold text-red-400">Sa</div>
                    <div class="text-[10px] text-gray-400 font-bold text-red-400">Su</div>
                </div>
                <div id="calendarDays" class="grid grid-cols-7 gap-1 text-center text-sm">
                    </div>

                <div class="mt-4 pt-4 border-t border-gray-100">
                    <div class="flex items-center gap-2 text-xs text-gray-500">
                        <span class="w-2 h-2 rounded-full bg-red-500"></span> Overdue
                        <span class="w-2 h-2 rounded-full bg-blue-500"></span> Active
                        <span class="w-2 h-2 rounded-full border border-indigo-500"></span> Selected
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-5">
                <h3 class="font-bold text-gray-800 text-sm mb-4">Segera Tenggat (H-3)</h3>
                <div class="space-y-3">
                    <?php 
                    // FILTERING LOGIC (SAFE MODE)
                    // Menggunakan array_filter hanya jika $tasks array valid
                    $upcoming = [];
                    if (!empty($tasks) && is_array($tasks)) {
                        $upcoming = array_filter($tasks, function($t) {
                            if (!isset($t['due_date'])) return false;
                            $diff = (strtotime($t['due_date']) - time()) / (60 * 60 * 24);
                            return $diff >= 0 && $diff <= 3 && ($t['status'] ?? '') != 'completed';
                        });
                    }
                    
                    if(empty($upcoming)): ?>
                        <div class="text-center py-4 text-gray-400 text-xs">
                            <i class="fas fa-mug-hot mb-2 text-xl"></i>
                            <p>Aman! Tidak ada deadline dekat.</p>
                        </div>
                    <?php else: ?>
                        <?php foreach(array_slice($upcoming, 0, 4) as $urgent): ?>
                        <div class="flex gap-3 items-start p-2 hover:bg-gray-50 rounded-lg transition border border-transparent hover:border-gray-100 cursor-pointer" onclick="window.location='<?= BASEURL ?>/admin/tasks/details?id=<?= $urgent['id'] ?>'">
                            <div class="w-10 h-10 rounded-lg bg-red-50 text-red-500 flex items-center justify-center flex-shrink-0 font-bold text-xs flex-col leading-none">
                                <span><?= date('d', strtotime($urgent['due_date'])) ?></span>
                                <span class="text-[8px] uppercase"><?= date('M', strtotime($urgent['due_date'])) ?></span>
                            </div>
                            <div>
                                <h4 class="text-xs font-bold text-gray-800 line-clamp-1"><?= htmlspecialchars($urgent['title'] ?? 'Task') ?></h4>
                                <p class="text-[10px] text-gray-500">PIC: <?= htmlspecialchars($urgent['first_name'] ?? '-') ?></p>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>

        </div>
    </div>
</div>

<script>
// STATE VARIABLES
let selectedDate = null;
const searchInput = document.getElementById('tableSearch');
const statusFilter = document.getElementById('statusFilter');

// 1. INIT CALENDAR
document.addEventListener('DOMContentLoaded', () => {
    const date = new Date();
    const month = date.getMonth();
    const year = date.getFullYear();
    
    // Set Header
    const months = ["Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember"];
    document.getElementById('currentMonthYear').innerText = `${months[month]} ${year}`;
    
    // Render Days
    const firstDay = new Date(year, month, 1).getDay();
    const daysInMonth = new Date(year, month + 1, 0).getDate();
    const calendarEl = document.getElementById('calendarDays');
    
    let startGap = firstDay === 0 ? 6 : firstDay - 1; 
    
    // SAFE JS ARRAY GENERATION
    const taskDates = [
        <?php 
        if (!empty($tasks) && is_array($tasks)) {
            foreach($tasks as $t) { 
                if(($t['status'] ?? '') != 'completed' && isset($t['due_date'])) {
                    echo "'".$t['due_date']."',"; 
                }
            }
        }
        ?>
    ];

    let html = '';
    for(let i=0; i<startGap; i++) html += `<div></div>`;
    
    for(let i=1; i<=daysInMonth; i++) {
        let currentDateStr = `${year}-${String(month+1).padStart(2, '0')}-${String(i).padStart(2, '0')}`;
        
        let isToday = (i === date.getDate()) ? 'bg-indigo-50 font-bold text-indigo-600' : 'text-gray-600 hover:bg-gray-50';
        let hasTask = taskDates.includes(currentDateStr);
        let dot = hasTask ? '<div class="mx-auto mt-0.5 w-1 h-1 bg-red-500 rounded-full"></div>' : '<div class="h-1.5"></div>';

        html += `<div id="day-${currentDateStr}" 
                      onclick="selectDate('${currentDateStr}')"
                      class="py-2 ${isToday} rounded-lg cursor-pointer flex flex-col items-center justify-center relative transition border border-transparent hover:border-gray-200">
                    <span class="z-10">${i}</span>
                    ${dot}
                 </div>`;
    }
    calendarEl.innerHTML = html;
});

// 2. DATE SELECTION LOGIC
function selectDate(dateStr) {
    const prevSelected = document.querySelector('.selected-date');
    if (prevSelected) {
        prevSelected.classList.remove('selected-date', 'ring-2', 'ring-indigo-500', 'bg-indigo-50');
    }

    if (selectedDate === dateStr) {
        selectedDate = null;
        document.getElementById('activeDateFilter').classList.add('hidden');
    } else {
        selectedDate = dateStr;
        const cell = document.getElementById(`day-${dateStr}`);
        if(cell) cell.classList.add('selected-date', 'ring-2', 'ring-indigo-500', 'bg-indigo-50');
        
        document.getElementById('activeDateFilter').classList.remove('hidden');
        document.getElementById('activeDateText').innerText = dateStr;
    }
    
    filterTable();
}

function clearDateFilter() {
    const prevSelected = document.querySelector('.selected-date');
    if (prevSelected) prevSelected.classList.remove('selected-date', 'ring-2', 'ring-indigo-500', 'bg-indigo-50');
    
    selectedDate = null;
    document.getElementById('activeDateFilter').classList.add('hidden');
    filterTable();
}

// 3. MAIN FILTER FUNCTION
function filterTable() {
    const term = searchInput.value.toLowerCase();
    const status = statusFilter.value;
    let visibleCount = 0;
    
    document.querySelectorAll('.status-row').forEach(row => {
        const text = row.innerText.toLowerCase();
        const rowStatus = row.getAttribute('data-status');
        const rowDate = row.getAttribute('data-date'); 
        
        const matchesSearch = text.includes(term);
        const matchesStatus = status === '' || rowStatus === status;
        const matchesDate = selectedDate === null || rowDate === selectedDate;
        
        if (matchesSearch && matchesStatus && matchesDate) {
            row.style.display = '';
            visibleCount++;
        } else {
            row.style.display = 'none';
        }
    });

    const emptyState = document.getElementById('noDataMessage');
    if(visibleCount === 0) {
        emptyState.classList.remove('hidden');
    } else {
        emptyState.classList.add('hidden');
    }
}

searchInput.addEventListener('keyup', filterTable);
statusFilter.addEventListener('change', filterTable);

function deleteTask(id) {
    if(confirm('Hapus tugas ini?')) window.location.href = `<?= BASEURL ?>/admin/tasks/delete?id=${id}`;
}
</script>

<style>
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(-5px); }
        to { opacity: 1; transform: translateY(0); }
    }
    .animate-fade-in { animation: fadeIn 0.2s ease-out; }
</style>