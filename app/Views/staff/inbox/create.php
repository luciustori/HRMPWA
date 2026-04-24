<div class="max-w-2xl mx-auto">
    <a href="<?= BASEURL ?>/staff/inbox" class="inline-flex items-center gap-2 text-sm text-slate-500 hover:text-indigo-600 mb-6 font-medium">
        <i class="ri-arrow-left-line"></i> Batal & Kembali
    </a>

    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6 sm:p-8">
        <h2 class="text-xl font-bold text-slate-800 mb-6">
            <?= isset($data['reply_data']) ? 'Balas Pesan' : 'Tulis Pesan Baru' ?>
        </h2>

        <form action="<?= BASEURL ?>/staff/inbox/store" method="POST">
            
            <div class="mb-5">
                <label class="block text-sm font-medium text-slate-700 mb-2">Penerima</label>
                <select name="target_id" required class="w-full rounded-lg border-slate-200 text-sm focus:ring-indigo-500 focus:border-indigo-500">
                    <option value="" disabled <?= !isset($data['reply_data']) ? 'selected' : '' ?>>-- Pilih Tujuan --</option>
                    <?php foreach($data['recipients'] as $user): ?>
                        <?php 
                            // Auto Select jika Reply
                            $isSelected = '';
                            if (isset($data['reply_data']) && $data['reply_data']['target_id'] == $user['employee_id']) {
                                $isSelected = 'selected';
                            }
                        ?>
                        <option value="<?= $user['employee_id'] ?>" <?= $isSelected ?>>
                            <?= $user['full_name'] ?> (<?= ucfirst($user['role_name']) ?> - <?= $user['position'] ?>)
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="mb-5">
                <label class="block text-sm font-medium text-slate-700 mb-2">Subjek Pesan</label>
                <input type="text" name="title" required 
                       value="<?= isset($data['reply_data']) ? $data['reply_data']['title'] : '' ?>"
                       placeholder="Contoh: Izin Telat Besok" 
                       class="w-full rounded-lg border-slate-200 text-sm focus:ring-indigo-500 focus:border-indigo-500">
            </div>

            <div class="mb-6">
                <label class="block text-sm font-medium text-slate-700 mb-2">Isi Pesan</label>
                <textarea name="content" rows="6" required placeholder="Tulis pesan Anda di sini..." class="w-full rounded-lg border-slate-200 text-sm focus:ring-indigo-500 focus:border-indigo-500"></textarea>
            </div>

            <div class="flex items-center justify-end gap-3">
                <button type="submit" class="px-6 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium rounded-lg transition-colors shadow-sm flex items-center gap-2">
                    <i class="ri-send-plane-fill"></i> Kirim Pesan
                </button>
            </div>
        </form>
    </div>
</div>