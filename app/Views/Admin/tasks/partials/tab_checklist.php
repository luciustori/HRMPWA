<div class="space-y-4">
    <div class="flex justify-between items-center mb-4">
        <h4 class="text-sm font-bold text-gray-700 uppercase">Sub-tasks</h4>
        <button onclick="addChecklist()" class="text-xs bg-indigo-50 text-indigo-600 px-3 py-1 rounded-full font-bold hover:bg-indigo-100 transition">
            <i class="fas fa-plus mr-1"></i> Add Item
        </button>
    </div>

    <?php if (empty($checklist_items)): ?>
        <p class="text-sm text-gray-400 italic text-center">Belum ada item.</p>
    <?php else: ?>
        <ul class="space-y-2">
            <?php foreach ($checklist_items as $item): ?>
            <li class="flex items-start gap-3 p-3 bg-gray-50 rounded-lg group">
                <input type="checkbox" 
                       onchange="toggleChecklist(<?= $item['id'] ?>, this)"
                       class="mt-1 rounded text-indigo-600 cursor-pointer"
                       <?= $item['is_completed'] ? 'checked' : '' ?>>
                
                <span class="text-sm text-gray-700 flex-1 <?= $item['is_completed'] ? 'line-through text-gray-400' : '' ?>">
                    <?= htmlspecialchars($item['item_text']) ?>
                </span>
                
                <button onclick="deleteChecklist(<?= $item['id'] ?>)" class="text-gray-300 hover:text-red-500 opacity-0 group-hover:opacity-100">
                    <i class="fas fa-trash"></i>
                </button>
            </li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>
</div>