<div class="max-w-[1600px] mx-auto p-6">
    
    <div class="mb-6">
        <div class="flex items-center gap-3 mb-2">
            <a href="<?= BASEURL ?>/admin/tasks" class="text-gray-600 hover:text-gray-900 transition">
                <i class="fas fa-arrow-left text-xl"></i>
            </a>
            <h1 class="text-3xl font-bold text-gray-900">Create New Task</h1>
        </div>
        <p class="text-gray-600 ml-11">Assign a task to an employee with specific timeline and requirements</p>
    </div>
    
    <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">
        
        <div class="lg:col-span-3">
            <form method="POST" action="<?= BASEURL ?>/admin/tasks/store" class="space-y-6">
                
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h2 class="text-xl font-bold text-gray-900 mb-6 flex items-center gap-2">
                        <i class="fas fa-info-circle text-blue-600"></i>
                        Task Information
                    </h2>
                    
                    <div class="space-y-5">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Task Title <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="title" required placeholder="e.g., Monthly Sales Report Q1 2026"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition">
                        </div>
                        
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Description <span class="text-red-500">*</span>
                            </label>
                            <textarea name="description" rows="4" required placeholder="Describe the task details..."
                                      class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition"></textarea>
                        </div>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Category</label>
                                <select name="category_id" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                                    <option value="">-- Select Category --</option>
                                    <?php foreach ($categories as $cat): ?>
                                    <option value="<?= $cat['id'] ?>"><?= htmlspecialchars($cat['category_name']) ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Priority <span class="text-red-500">*</span></label>
                                <select name="priority" required class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                                    <option value="medium">🟡 Medium</option>
                                    <option value="low">🟢 Low</option>
                                    <option value="high">🟠 High</option>
                                    <option value="urgent">🔴 Urgent</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h2 class="text-xl font-bold text-gray-900 mb-6 flex items-center gap-2">
                        <i class="fas fa-user-plus text-blue-600"></i>
                        Assignment
                    </h2>
                    
                    <div class="space-y-5">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                <i class="fas fa-filter text-gray-400"></i> Filter by Department
                            </label>
                            <select id="departmentFilter" class="w-full px-4 py-3 border border-gray-300 rounded-lg bg-gray-50">
                                <option value="">All Departments</option>
                                <?php foreach ($departments as $dept): ?>
                                <option value="<?= $dept['id'] ?>"><?= htmlspecialchars($dept['department_name']) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Assign to Employee <span class="text-red-500">*</span>
                            </label>
                            <select name="assigned_to" id="assignedTo" required class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 transition">
                                <option value="">-- Select Employee --</option>
                                <?php foreach ($employees as $emp): ?>
                                <option value="<?= $emp['employee_number'] ?>" data-department="<?= $emp['department_id'] ?>">
                                    <?= htmlspecialchars($emp['first_name'] . ' ' . $emp['last_name']) ?>
                                    (<?= htmlspecialchars($emp['department_name'] ?? '-') ?>)
                                </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                </div>
                
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h2 class="text-xl font-bold text-gray-900 mb-6 flex items-center gap-2">
                        <i class="fas fa-calendar-alt text-blue-600"></i>
                        Timeline & Estimation
                    </h2>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Start Date <span class="text-red-500">*</span></label>
                            <input type="date" name="start_date" id="startDate" required value="<?= date('Y-m-d') ?>" class="w-full px-4 py-3 border border-gray-300 rounded-lg">
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Due Date <span class="text-red-500">*</span></label>
                            <input type="date" name="due_date" id="dueDate" required value="<?= date('Y-m-d', strtotime('+3 days')) ?>" class="w-full px-4 py-3 border border-gray-300 rounded-lg">
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Estimated Hours</label>
                            <input type="number" name="estimated_hours" min="0" step="0.5" class="w-full px-4 py-3 border border-gray-300 rounded-lg">
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">KPI Weight (0-100)</label>
                            <input type="number" name="kpi_weight" min="0" max="100" value="10" class="w-full px-4 py-3 border border-gray-300 rounded-lg">
                        </div>
                    </div>
                    
                    <div id="durationInfo" class="mt-5 p-4 bg-blue-50 border border-blue-200 rounded-lg hidden">
                        <div class="flex items-center gap-2 text-blue-800">
                            <i class="fas fa-clock"></i>
                            <span class="text-sm font-medium">Duration: <strong id="durationDays">0</strong> days</span>
                        </div>
                    </div>
                </div>
                
                <div class="flex gap-4">
                    <button type="submit" class="flex-1 bg-blue-600 hover:bg-blue-700 text-white font-bold py-3.5 px-6 rounded-lg shadow-lg transition flex justify-center items-center gap-2">
                        <i class="fas fa-save"></i> Create Task
                    </button>
                    <a href="<?= BASEURL ?>/admin/tasks" class="flex-1 bg-gray-200 hover:bg-gray-300 text-gray-700 font-bold py-3.5 px-6 rounded-lg transition text-center">
                        Cancel
                    </a>
                </div>
            </form>
        </div>
        
        <div class="lg:col-span-1 hidden lg:block">
            <div class="sticky top-6 space-y-4">
                <div class="bg-blue-50 border border-blue-100 rounded-xl p-5">
                    <h3 class="font-bold text-blue-800 mb-3">Quick Guide</h3>
                    <ul class="text-sm text-blue-700 space-y-2 list-disc list-inside">
                        <li><strong>Title:</strong> Gunakan nama yang spesifik.</li>
                        <li><strong>Priority:</strong> Gunakan 'Urgent' hanya untuk darurat.</li>
                        <li><strong>Filter:</strong> Gunakan filter departemen untuk mencari karyawan lebih cepat.</li>
                    </ul>
                </div>
            </div>
        </div>
        
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // 1. Department Filter
    const deptFilter = document.getElementById('departmentFilter');
    const assignedSelect = document.getElementById('assignedTo');
    const options = Array.from(assignedSelect.querySelectorAll('option[data-department]'));

    deptFilter.addEventListener('change', function() {
        const selectedDept = this.value;
        assignedSelect.value = "";
        options.forEach(opt => {
            opt.style.display = (selectedDept === "" || opt.dataset.department === selectedDept) ? "" : "none";
        });
    });

    // 2. Duration Calculator
    const startInp = document.getElementById('startDate');
    const dueInp = document.getElementById('dueDate');
    const durInfo = document.getElementById('durationInfo');
    const durDays = document.getElementById('durationDays');

    function calcDuration() {
        if(startInp.value && dueInp.value) {
            const start = new Date(startInp.value);
            const due = new Date(dueInp.value);
            const diff = due - start;
            const days = Math.ceil(diff / (1000 * 60 * 60 * 24));

            if(days >= 0) {
                durDays.innerText = days;
                durInfo.classList.remove('hidden');
            } else {
                durInfo.classList.add('hidden');
            }
        }
    }
    startInp.addEventListener('change', calcDuration);
    dueInp.addEventListener('change', calcDuration);
});
</script>