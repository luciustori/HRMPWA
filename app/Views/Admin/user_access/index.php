<div class="mb-6 flex justify-between items-center">
    <div>
        <h1 class="text-2xl font-bold text-slate-800 tracking-tight">User Access Manager</h1>
        <p class="text-slate-500 text-sm mt-1">Delegasikan tugas khusus ke karyawan tertentu.</p>
    </div>
</div>

<div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-left text-sm text-slate-600">
            <thead class="bg-slate-50 text-slate-900 font-bold uppercase text-xs tracking-wider border-b border-slate-100">
                <tr>
                    <th class="px-6 py-4">Nama Karyawan</th>
                    <th class="px-6 py-4">Role Utama</th>
                    <th class="px-6 py-4 text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-50">
                <?php foreach ($data['users'] as $user) : ?>
                <tr class="hover:bg-slate-50 transition-colors">
                    <td class="px-6 py-4">
                        <div class="font-bold text-slate-900"><?= $user['full_name'] ?></div>
                        <div class="text-xs text-slate-400 font-medium">@<?= $user['username'] ?></div>
                    </td>
                    <td class="px-6 py-4">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-slate-100 text-slate-700">
                            <?= $user['role_name'] ?? ucfirst($user['role']) ?>
                        </span>
                    </td>
                    <td class="px-6 py-4 text-right">
                        <a href="<?= BASEURL ?>/admin/useraccess/edit/<?= $user['id'] ?>" 
                           class="inline-flex items-center gap-2 px-4 py-2 bg-white border border-slate-200 text-slate-700 text-xs font-bold rounded-lg hover:bg-slate-50 hover:text-blue-600 transition-all shadow-sm">
                            <i class="fas fa-user-shield"></i> Atur Akses
                        </a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>