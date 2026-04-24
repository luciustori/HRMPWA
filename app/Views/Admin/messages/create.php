<div class="max-w-[1200px] mx-auto p-6">
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Tulis Pesan Baru</h1>
        </div>
        <a href="<?= BASEURL ?>/admin/messages" class="text-gray-500 hover:text-gray-700 text-sm font-bold">
            <i class="fas fa-arrow-left mr-1"></i> Batal
        </a>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-8">
        <form action="<?= BASEURL ?>/admin/messages/store" method="POST" enctype="multipart/form-data">
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Departemen Tujuan</label>
                    <select id="deptSelect" onchange="fetchUsers()" class="w-full px-4 py-3 border border-gray-200 rounded-xl bg-gray-50 focus:bg-white focus:ring-2 focus:ring-indigo-100 transition">
                        <option value="">- Pilih Departemen -</option>
                        <?php foreach($departments as $d): ?>
                            <option value="<?= $d['id'] ?>"><?= $d['department_name'] ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Penerima <span class="text-red-500">*</span></label>
                    <select name="recipient_id" id="userSelect" required class="w-full px-4 py-3 border border-gray-200 rounded-xl bg-gray-50 focus:bg-white focus:ring-2 focus:ring-indigo-100 transition disabled:bg-gray-100 disabled:text-gray-400" disabled>
                        <option value="">Pilih departemen dulu...</option>
                    </select>
                    <p id="loadingUser" class="hidden text-xs text-indigo-500 mt-1">Mengambil data user...</p>
                </div>
            </div>

            <div class="mb-6">
                <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Subjek / Judul <span class="text-red-500">*</span></label>
                <input type="text" name="subject" required class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-indigo-100 focus:border-indigo-500 transition" placeholder="Contoh: Diskusi Laporan Bulanan">
            </div>

            <div class="mb-6">
                <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Isi Pesan <span class="text-red-500">*</span></label>
                <textarea name="body" rows="8" required class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-indigo-100 resize-none" placeholder="Tulis pesan Anda..."></textarea>
            </div>

            <div class="mb-8">
                <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Lampiran (Opsional)</label>
                <input type="file" name="attachment" class="w-full px-4 py-3 border border-gray-200 rounded-xl text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
                <p class="text-[10px] text-gray-400 mt-1">Max 5MB. PDF, DOCX, JPG, PNG.</p>
            </div>

            <div class="flex justify-end pt-6 border-t border-gray-100">
                <button type="submit" class="bg-indigo-600 text-white px-8 py-3 rounded-xl font-bold shadow-lg shadow-indigo-200 hover:bg-indigo-700 transition transform hover:-translate-y-0.5 flex items-center gap-2">
                    <i class="fas fa-paper-plane"></i> Kirim Pesan
                </button>
            </div>
        </form>
    </div>
</div>

<script>
const BASE_URL = '<?= BASEURL ?>';

function fetchUsers() {
    const deptId = document.getElementById('deptSelect').value;
    const userSelect = document.getElementById('userSelect');
    const loading = document.getElementById('loadingUser');

    if(!deptId) {
        userSelect.innerHTML = "<option value=''>Pilih departemen dulu...</option>";
        userSelect.disabled = true;
        return;
    }

    loading.classList.remove('hidden');
    userSelect.disabled = true;
    userSelect.innerHTML = "<option value=''>Loading...</option>";

    fetch(`${BASE_URL}/admin/messages/ajax_get_users?dept_id=${deptId}`)
        .then(response => response.json())
        .then(data => {
            loading.classList.add('hidden');
            userSelect.disabled = false;
            userSelect.innerHTML = "<option value=''>- Pilih Penerima -</option>";

            if(data.length > 0) {
                data.forEach(user => {
                    const option = document.createElement('option');
                    option.value = user.id;
                    option.text = `${user.first_name} ${user.last_name} (${user.position})`;
                    userSelect.add(option);
                });
            } else {
                userSelect.innerHTML = "<option value=''>Tidak ada user lain di sini</option>";
            }
        })
        .catch(err => {
            console.error(err);
            loading.innerText = "Gagal memuat.";
        });
}
</script>