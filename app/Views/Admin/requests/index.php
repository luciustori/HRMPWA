<?php
// File: app/Views/admin/requests/index.php

$leaves = $leaves ?? []; 
$annual_leaves = array_filter($leaves, function($r) {
    return in_array(strtoupper($r['leave_code'] ?? ''), ['ANNUAL', 'CUTI_TAHUNAN']);
});
$duty_leaves = array_filter($leaves, function($r) {
    return in_array(strtoupper($r['leave_code'] ?? ''), ['DL', 'DINAS_LUAR']);
});
$permit_leaves = array_filter($leaves, function($r) {
    return !in_array(strtoupper($r['leave_code'] ?? ''), ['ANNUAL', 'CUTI_TAHUNAN', 'DL', 'DINAS_LUAR']);
});

$base_url_filter = BASEURL . "/admin/requests?month={$period_month}&year={$period_year}";
?>

<div class="max-w-7xl mx-auto" x-data="{ 
    activeTab: 'semua', 
    modalOpen: false, 
    detailModalOpen: false,  
    isLoadingDetail: false,  
    detailData: {},          
    detailType: '',          
    action: {id:null, type:'', status:'', name:'', notes:''},

    createModalOpen: false,
    activeCreateCat: 'ijin',
    selectedEmp: '',
    leaveBal: 0,
    isLoadingBal: false,
    isSubmitting: false,

    openModal(id, type, status, name) {
        this.action.id = id;
        this.action.type = type;
        this.action.status = status;
        this.action.name = name;
        this.action.notes = '';
        this.modalOpen = true;
        this.detailModalOpen = false;
    },

    setCreateCat(cat) {
        this.activeCreateCat = cat;
        if(cat === 'cuti' && this.selectedEmp) this.fetchBal();
    },

    fetchBal() {
        if(!this.selectedEmp) return;
        this.isLoadingBal = true;
        fetch('<?= BASEURL ?>/admin/requests/get_leave_balance/' + this.selectedEmp)
            .then(res => res.json())
            .then(res => { this.leaveBal = res.success && res.data.total_remaining !== undefined ? res.data.total_remaining : 12; })
            .catch(() => { this.leaveBal = 0; })
            .finally(() => { this.isLoadingBal = false; });
    },

    closeCreateModal() {
        this.createModalOpen = false;
    },

    submitAction() {
        if(!this.action.id || !this.action.type) return alert('Error: ID/Type tidak valid.');

        let formData = new FormData();
        formData.append('id', this.action.id);
        formData.append('type', this.action.type);
        formData.append('status', this.action.status);
        formData.append('notes', this.action.notes);

        fetch('<?= BASEURL ?>/admin/requests/update_status', { method: 'POST', body: formData })
        .then(res => res.json()) 
        .then(data => {
            if(data.success) location.reload();
            else alert('Gagal: ' + (data.message || 'Unknown Error'));
        }).catch(err => { alert('Terjadi kesalahan jaringan.'); });
    },

    submitCreateForm(e) {
        if (this.isSubmitting) return; 
        
        let form = document.getElementById('pengajuanForm');
        if (!form.reportValidity()) return; 

        this.isSubmitting = true; 
        
        let submitBtn = e.submitter;
        if (submitBtn) submitBtn.disabled = true;

        let formData = new FormData(form);
        formData.append('is_ajax', '1');

        fetch('<?= BASEURL ?>/admin/requests/store', { method: 'POST', body: formData })
        .then(res => res.json()) 
        .then(data => {
            if(data.success) {
                this.createModalOpen = false;
                
                Swal.fire({ 
                    icon: 'success', 
                    title: 'Berhasil!', 
                    text: 'Data pengajuan telah disimpan.', 
                    timer: 1500, 
                    showConfirmButton: false,
                    backdrop: 'rgba(0,0,123,0.4)'
                });

                setTimeout(() => {
                    window.location.reload();
                }, 1500);

            } else {
                Swal.fire('Gagal Menyimpan', data.message || 'Terjadi error.', 'error');
                this.isSubmitting = false; 
                if (submitBtn) submitBtn.disabled = false;
            }
        }).catch(err => { 
            Swal.fire('Error', 'Kesalahan jaringan atau server.', 'error'); 
            this.isSubmitting = false; 
            if (submitBtn) submitBtn.disabled = false;
        });
    },

    openDetailModal(id, type) {
        this.detailType = type;
        this.detailModalOpen = true;
        this.isLoadingDetail = true;
        this.detailData = {};

        fetch('<?= BASEURL ?>/admin/requests/get_detail/' + type + '/' + id)
        .then(res => res.json())
        .then(res => {
            if(res.success) {
                let data = res.data;
                
                const formatTgl = (dateStr) => {
                    if (!dateStr) return '-';
                    let d = new Date(dateStr);
                    const months = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
                    return d.getDate() + ' ' + months[d.getMonth()] + ' ' + d.getFullYear();
                };
                
                data.formatted_start = formatTgl(data.start_date);
                data.formatted_end = formatTgl(data.end_date);
                data.formatted_ot_date = formatTgl(data.overtime_date);

                this.detailData = data;
            } else { 
                alert('Gagal: ' + res.message); 
                this.detailModalOpen = false; 
            }
        }).catch(err => { 
            this.detailModalOpen = false; 
        }).finally(() => { 
            this.isLoadingDetail = false; 
        });
    }
}">
    
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
        <div>
            <h1 class="text-3xl font-bold text-gray-900 tracking-tight">
                <?php 
                    if($status_filter == 'pending') echo "Menunggu Persetujuan";
                    elseif($status_filter == 'approved') echo "Riwayat Disetujui";
                    elseif($status_filter == 'rejected') echo "Riwayat Ditolak";
                    else echo "Pusat Persetujuan";
                ?>
            </h1>
            <p class="text-gray-500 mt-1">Pusat kontrol pengajuan karyawan.</p>
        </div>

        <div class="flex flex-wrap items-center gap-3">
            <form method="GET" action="<?= BASEURL ?>/admin/requests" class="flex items-center bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden h-11">
                <?php if(!empty($status_filter)): ?>
                    <input type="hidden" name="status" value="<?= $status_filter ?>">
                <?php endif; ?>
                
                <select name="month" class="bg-transparent text-sm font-bold text-gray-700 outline-none pl-4 pr-2 py-2 cursor-pointer hover:bg-gray-50 focus:ring-0 border-none">
                    <?php 
                    $months = ['01'=>'Januari', '02'=>'Februari', '03'=>'Maret', '04'=>'April', '05'=>'Mei', '06'=>'Juni', '07'=>'Juli', '08'=>'Agustus', '09'=>'September', '10'=>'Oktober', '11'=>'November', '12'=>'Desember'];
                    foreach($months as $m => $name): ?>
                        <option value="<?= $m ?>" <?= ($m == $period_month) ? 'selected' : '' ?>><?= $name ?></option>
                    <?php endforeach; ?>
                </select>
                
                <div class="w-px h-6 bg-gray-200 mx-1"></div>
                
                <select name="year" class="bg-transparent text-sm font-bold text-gray-700 outline-none pl-2 pr-4 py-2 cursor-pointer hover:bg-gray-50 focus:ring-0 border-none">
                    <?php 
                    $currentYear = date('Y');
                    for($y = $currentYear - 2; $y <= $currentYear + 1; $y++): ?>
                        <option value="<?= $y ?>" <?= ($y == $period_year) ? 'selected' : '' ?>><?= $y ?></option>
                    <?php endfor; ?>
                </select>
                
                <button type="submit" class="bg-gray-800 hover:bg-gray-900 text-white text-sm font-bold px-5 h-full transition flex items-center gap-2">
                    <i class="fas fa-filter"></i> Filter
                </button>
            </form>

            <button @click="createModalOpen = true" class="group relative inline-flex items-center justify-center px-5 h-11 text-sm font-bold text-white transition-all duration-200 bg-indigo-600 rounded-xl hover:bg-indigo-700 shadow-sm hover:shadow-indigo-500/30 active:scale-95">
                <i class="fas fa-plus-circle mr-2 text-lg group-hover:rotate-90 transition-transform"></i>
                Buat Pengajuan Baru
            </button>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <a href="<?= $base_url_filter ?>&status=pending" class="block bg-white rounded-2xl p-6 shadow-sm border transition relative overflow-hidden group <?= $status_filter == 'pending' ? 'border-orange-500 ring-2 ring-orange-200 bg-orange-50/30' : 'border-orange-100 hover:shadow-md' ?>">
            <div class="absolute right-0 top-0 h-full w-24 bg-gradient-to-l from-orange-50 to-transparent opacity-50"></div>
            <div class="flex justify-between items-start relative z-10">
                <div>
                    <p class="text-xs font-bold text-orange-600 uppercase tracking-wider mb-1">Menunggu Persetujuan</p>
                    <h3 class="text-4xl font-extrabold text-gray-900"><?= $stats['pending'] ?></h3>
                </div>
                <div class="w-12 h-12 rounded-xl <?= $status_filter == 'pending' ? 'bg-orange-500 text-white' : 'bg-orange-100 text-orange-600' ?> flex items-center justify-center text-xl transition-colors"><i class="fas fa-hourglass-half"></i></div>
            </div>
        </a>
        <a href="<?= $base_url_filter ?>&status=approved" class="block bg-white rounded-2xl p-6 shadow-sm border transition group <?= $status_filter == 'approved' ? 'border-green-500 ring-2 ring-green-200 bg-green-50/30' : 'border-green-100 hover:shadow-md' ?>">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-xs font-bold text-green-600 uppercase tracking-wider mb-1">Total Disetujui</p>
                    <h3 class="text-4xl font-extrabold text-gray-900"><?= $stats['approved'] ?></h3>
                </div>
                <div class="w-12 h-12 rounded-xl <?= $status_filter == 'approved' ? 'bg-green-500 text-white' : 'bg-green-50 text-green-600' ?> flex items-center justify-center text-xl transition-colors"><i class="fas fa-check-circle"></i></div>
            </div>
        </a>
        <a href="<?= $base_url_filter ?>&status=rejected" class="block bg-white rounded-2xl p-6 shadow-sm border transition group <?= $status_filter == 'rejected' ? 'border-red-500 ring-2 ring-red-200 bg-red-50/30' : 'border-red-100 hover:shadow-md' ?>">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-xs font-bold text-red-600 uppercase tracking-wider mb-1">Total Ditolak</p>
                    <h3 class="text-4xl font-extrabold text-gray-900"><?= $stats['rejected'] ?></h3>
                </div>
                <div class="w-12 h-12 rounded-xl <?= $status_filter == 'rejected' ? 'bg-red-500 text-white' : 'bg-red-50 text-red-600' ?> flex items-center justify-center text-xl transition-colors"><i class="fas fa-times-circle"></i></div>
            </div>
        </a>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden min-h-[500px]">
        <div class="flex border-b border-gray-100 overflow-x-auto">
            <button @click="activeTab = 'semua'" :class="activeTab === 'semua' ? 'text-gray-900 border-gray-900 bg-gray-50/50' : 'text-gray-500 hover:text-gray-700 hover:bg-gray-50 border-transparent'" class="px-6 py-4 text-sm font-bold border-b-2 transition whitespace-nowrap flex items-center gap-2">
                <i class="fas fa-layer-group"></i> Semua
            </button>
            <button @click="activeTab = 'ijin'" :class="activeTab === 'ijin' ? 'text-indigo-600 border-indigo-600 bg-indigo-50/50' : 'text-gray-500 hover:text-gray-700 hover:bg-gray-50 border-transparent'" class="px-6 py-4 text-sm font-bold border-b-2 transition whitespace-nowrap flex items-center gap-2">
                <i class="fas fa-user-injured"></i> Ijin
            </button>
            <button @click="activeTab = 'dinas_luar'" :class="activeTab === 'dinas_luar' ? 'text-blue-600 border-blue-600 bg-blue-50/50' : 'text-gray-500 hover:text-gray-700 hover:bg-gray-50 border-transparent'" class="px-6 py-4 text-sm font-bold border-b-2 transition whitespace-nowrap flex items-center gap-2">
                <i class="fas fa-briefcase"></i> Dinas Luar
            </button>
            <button @click="activeTab = 'cuti'" :class="activeTab === 'cuti' ? 'text-emerald-600 border-emerald-600 bg-emerald-50/50' : 'text-gray-500 hover:text-gray-700 hover:bg-gray-50 border-transparent'" class="px-6 py-4 text-sm font-bold border-b-2 transition whitespace-nowrap flex items-center gap-2">
                <i class="fas fa-umbrella-beach"></i> Cuti Tahunan
            </button>
            <button @click="activeTab = 'sppd'" :class="activeTab === 'sppd' ? 'text-purple-600 border-purple-600 bg-purple-50/50' : 'text-gray-500 hover:text-gray-700 hover:bg-gray-50 border-transparent'" class="px-6 py-4 text-sm font-bold border-b-2 transition whitespace-nowrap flex items-center gap-2">
                <i class="fas fa-plane-departure"></i> SPPD
            </button>
            <button @click="activeTab = 'lembur'" :class="activeTab === 'lembur' ? 'text-orange-600 border-orange-600 bg-orange-50/50' : 'text-gray-500 hover:text-gray-700 hover:bg-gray-50 border-transparent'" class="px-6 py-4 text-sm font-bold border-b-2 transition whitespace-nowrap flex items-center gap-2">
                <i class="fas fa-stopwatch"></i> Lembur
            </button>
        </div>

        <div class="p-0">
            <div x-show="activeTab === 'semua'" class="animate-fade-in"><?php renderTable($all_requests, 'mixed', $period_month, $period_year, $status_filter); ?></div>
            <div x-show="activeTab === 'ijin'" style="display: none;" class="animate-fade-in"><?php renderTable($permit_leaves, 'leave', $period_month, $period_year, $status_filter); ?></div>
            <div x-show="activeTab === 'dinas_luar'" style="display: none;" class="animate-fade-in"><?php renderTable($duty_leaves, 'leave', $period_month, $period_year, $status_filter); ?></div>
            <div x-show="activeTab === 'cuti'" style="display: none;" class="animate-fade-in"><?php renderTable($annual_leaves, 'leave', $period_month, $period_year, $status_filter); ?></div>
            <div x-show="activeTab === 'sppd'" style="display: none;" class="animate-fade-in"><?php renderTable($trips, 'trip', $period_month, $period_year, $status_filter); ?></div>
            <div x-show="activeTab === 'lembur'" style="display: none;" class="animate-fade-in"><?php renderTable($overtimes, 'overtime', $period_month, $period_year, $status_filter); ?></div>
        </div>
    </div>

    <div x-show="createModalOpen" style="display: none;" class="fixed inset-0 z-50 overflow-y-auto" x-cloak>
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 transition-opacity bg-gray-900 bg-opacity-75" @click="closeCreateModal()"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen">&#8203;</span>
            
            <div class="inline-block align-bottom bg-white rounded-2xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-3xl w-full">
                
                <div class="bg-white px-6 py-5 border-b border-gray-100 flex justify-between items-center sticky top-0 z-10">
                    <div>
                        <h3 class="text-xl font-bold text-gray-800">Buat Pengajuan</h3>
                        <p class="text-xs text-gray-500 mt-1">Input data pengajuan tanpa batas (AJAX Mode).</p>
                    </div>
                    <button @click="closeCreateModal()" class="text-gray-400 hover:text-red-500 bg-gray-50 hover:bg-red-50 w-8 h-8 rounded-full transition flex items-center justify-center">
                        <i class="fas fa-times"></i>
                    </button>
                </div>

                <div class="p-6 bg-gray-50/50">
                    <div class="flex flex-wrap gap-2 mb-6">
                        <button type="button" @click="setCreateCat('ijin')" :class="activeCreateCat === 'ijin' ? 'bg-indigo-600 text-white shadow-md' : 'bg-white text-gray-600 border border-gray-200 hover:bg-gray-50'" class="flex-1 px-3 py-2.5 rounded-lg text-xs font-bold transition flex items-center justify-center gap-2 whitespace-nowrap"><i class="fas fa-user-injured"></i> Ijin</button>
                        <button type="button" @click="setCreateCat('dinas_luar')" :class="activeCreateCat === 'dinas_luar' ? 'bg-blue-600 text-white shadow-md' : 'bg-white text-gray-600 border border-gray-200 hover:bg-gray-50'" class="flex-1 px-3 py-2.5 rounded-lg text-xs font-bold transition flex items-center justify-center gap-2 whitespace-nowrap"><i class="fas fa-briefcase"></i> Dinas Luar</button>
                        <button type="button" @click="setCreateCat('cuti')" :class="activeCreateCat === 'cuti' ? 'bg-emerald-600 text-white shadow-md' : 'bg-white text-gray-600 border border-gray-200 hover:bg-gray-50'" class="flex-1 px-3 py-2.5 rounded-lg text-xs font-bold transition flex items-center justify-center gap-2 whitespace-nowrap"><i class="fas fa-umbrella-beach"></i> Cuti Tahunan</button>
                        <button type="button" @click="setCreateCat('sppd')" :class="activeCreateCat === 'sppd' ? 'bg-purple-600 text-white shadow-md' : 'bg-white text-gray-600 border border-gray-200 hover:bg-gray-50'" class="flex-1 px-3 py-2.5 rounded-lg text-xs font-bold transition flex items-center justify-center gap-2 whitespace-nowrap"><i class="fas fa-plane-departure"></i> SPPD</button>
                        <button type="button" @click="setCreateCat('lembur')" :class="activeCreateCat === 'lembur' ? 'bg-orange-600 text-white shadow-md' : 'bg-white text-gray-600 border border-gray-200 hover:bg-gray-50'" class="flex-1 px-3 py-2.5 rounded-lg text-xs font-bold transition flex items-center justify-center gap-2 whitespace-nowrap"><i class="fas fa-stopwatch"></i> Lembur</button>
                    </div>

                    <form id="pengajuanForm" @submit.prevent="submitCreateForm($event)" class="bg-white p-6 rounded-xl border border-gray-100 shadow-sm">
                        <input type="hidden" name="category" x-model="activeCreateCat">

                        <div class="mb-6 pb-6 border-b border-gray-100">
                            <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Pilih Karyawan <span class="text-red-500">*</span></label>
                            <select name="employee_id" x-model="selectedEmp" @change="fetchBal()" class="w-full border-gray-300 rounded-lg p-2.5 text-sm focus:ring-2 focus:ring-indigo-500" required>
                                <option value="">-- Cari Nama Karyawan --</option>
                                <?php foreach($employees as $emp): ?>
                                    <option value="<?= $emp['id'] ?>"><?= $emp['first_name'] . ' ' . $emp['last_name'] . ' (' . $emp['employee_number'] . ')' ?></option>
                                <?php endforeach; ?>
                            </select>
                            <div x-show="activeCreateCat === 'cuti' && selectedEmp" class="mt-2 flex items-center gap-2 text-xs" style="display: none;">
                                <span class="text-gray-500">Sisa Jatah Cuti:</span>
                                <span x-text="isLoadingBal ? '...' : leaveBal + ' Hari'" class="font-bold px-2 py-0.5 rounded bg-emerald-100 text-emerald-700"></span>
                            </div>
                        </div>

                        <div x-show="activeCreateCat === 'ijin'" class="space-y-4">
                            <div>
                                <label class="block text-xs font-bold text-gray-700 mb-1">Jenis Ijin</label>
                                <select name="leave_type_id" :disabled="activeCreateCat !== 'ijin'" class="w-full px-3 py-2 text-sm border rounded-lg border-slate-300 focus:ring-2 focus:ring-indigo-500">
                                    <?php foreach($permit_types as $pt): ?><option value="<?= $pt['id'] ?>"><?= $pt['leave_type_name'] ?></option><?php endforeach; ?>
                                </select>
                            </div>
                            <div class="grid grid-cols-2 gap-4">
                                <div><label class="block text-xs font-bold text-gray-700 mb-1">Mulai</label><input type="date" name="start_date" :disabled="activeCreateCat !== 'ijin'" class="w-full px-3 py-2 text-sm border rounded-lg border-slate-300" required></div>
                                <div><label class="block text-xs font-bold text-gray-700 mb-1">Sampai</label><input type="date" name="end_date" :disabled="activeCreateCat !== 'ijin'" class="w-full px-3 py-2 text-sm border rounded-lg border-slate-300" required></div>
                            </div>
                            <div><label class="block text-xs font-bold text-gray-700 mb-1">Alasan</label><textarea name="reason" :disabled="activeCreateCat !== 'ijin'" rows="2" class="w-full px-3 py-2 text-sm border rounded-lg border-slate-300" required></textarea></div>
                            <div><label class="block text-xs font-bold text-gray-700 mb-1">Upload Bukti</label><input type="file" name="document" :disabled="activeCreateCat !== 'ijin'" class="block w-full text-xs text-gray-500 file:mr-4 file:py-1.5 file:px-4 file:rounded-full file:border-0 file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100"/></div>
                        </div>

                        <div x-show="activeCreateCat === 'dinas_luar'" class="space-y-4" style="display: none;">
                            <input type="hidden" name="leave_type_id" value="<?= $duty_leave['id'] ?? '' ?>" :disabled="activeCreateCat !== 'dinas_luar'">
                            <div class="grid grid-cols-2 gap-4">
                                <div><label class="block text-xs font-bold text-gray-700 mb-1">Tanggal</label><input type="date" name="start_date" :disabled="activeCreateCat !== 'dinas_luar'" class="w-full px-3 py-2 text-sm border rounded-lg border-slate-300" required></div>
                                <div><label class="block text-xs font-bold text-gray-700 mb-1">Sampai</label><input type="date" name="end_date" :disabled="activeCreateCat !== 'dinas_luar'" class="w-full px-3 py-2 text-sm border rounded-lg border-slate-300" required></div>
                            </div>
                            <div><label class="block text-xs font-bold text-gray-700 mb-1">Lokasi & Keperluan</label><textarea name="reason" :disabled="activeCreateCat !== 'dinas_luar'" rows="2" class="w-full px-3 py-2 text-sm border rounded-lg border-slate-300" required></textarea></div>
                        </div>

                        <div x-show="activeCreateCat === 'cuti'" class="space-y-4" style="display: none;">
                            <input type="hidden" name="leave_type_id" value="<?= $annual_leave['id'] ?? '' ?>" :disabled="activeCreateCat !== 'cuti'">
                            <div class="grid grid-cols-2 gap-4">
                                <div><label class="block text-xs font-bold text-gray-700 mb-1">Mulai</label><input type="date" name="start_date" :disabled="activeCreateCat !== 'cuti'" class="w-full px-3 py-2 text-sm border rounded-lg border-slate-300" required></div>
                                <div><label class="block text-xs font-bold text-gray-700 mb-1">Selesai</label><input type="date" name="end_date" :disabled="activeCreateCat !== 'cuti'" class="w-full px-3 py-2 text-sm border rounded-lg border-slate-300" required></div>
                            </div>
                            <div><label class="block text-xs font-bold text-gray-700 mb-1">Keperluan</label><textarea name="reason" :disabled="activeCreateCat !== 'cuti'" rows="2" class="w-full px-3 py-2 text-sm border rounded-lg border-slate-300" required></textarea></div>
                        </div>

                        <div x-show="activeCreateCat === 'sppd'" class="space-y-4" style="display: none;">
                            <div class="grid grid-cols-2 gap-4">
                                <div><label class="block text-xs font-bold text-gray-700 mb-1">Tujuan</label><input type="text" name="destination" :disabled="activeCreateCat !== 'sppd'" class="w-full px-3 py-2 text-sm border rounded-lg border-slate-300" required></div>
                                <div><label class="block text-xs font-bold text-gray-700 mb-1">Estimasi Biaya</label><input type="text" name="estimated_budget" :disabled="activeCreateCat !== 'sppd'" class="w-full px-3 py-2 text-sm border rounded-lg border-slate-300" required></div>
                            </div>
                            <div class="grid grid-cols-2 gap-4">
                                <div><label class="block text-xs font-bold text-gray-700 mb-1">Berangkat</label><input type="date" name="start_date" :disabled="activeCreateCat !== 'sppd'" class="w-full px-3 py-2 text-sm border rounded-lg border-slate-300" required></div>
                                <div><label class="block text-xs font-bold text-gray-700 mb-1">Pulang</label><input type="date" name="end_date" :disabled="activeCreateCat !== 'sppd'" class="w-full px-3 py-2 text-sm border rounded-lg border-slate-300" required></div>
                            </div>
                            <div><label class="block text-xs font-bold text-gray-700 mb-1">Keperluan</label><textarea name="trip_purpose" :disabled="activeCreateCat !== 'sppd'" rows="2" class="w-full px-3 py-2 text-sm border rounded-lg border-slate-300" required></textarea></div>
                            <div><label class="block text-xs font-bold text-gray-700 mb-1">Upload Surat Tugas <span class="text-red-500">*</span></label><input type="file" name="document" :disabled="activeCreateCat !== 'sppd'" class="block w-full text-xs text-gray-500 file:mr-4 file:py-1.5 file:px-4 file:rounded-full file:border-0 file:bg-purple-50 file:text-purple-700 hover:file:bg-purple-100" required/></div>
                        </div>

                        <div x-show="activeCreateCat === 'lembur'" class="space-y-4" style="display: none;">
                            <div><label class="block text-xs font-bold text-gray-700 mb-1">Tanggal</label><input type="date" name="overtime_date" :disabled="activeCreateCat !== 'lembur'" class="w-full px-3 py-2 text-sm border rounded-lg border-slate-300" required></div>
                            <div class="grid grid-cols-2 gap-4">
                                <div><label class="block text-xs font-bold text-gray-700 mb-1">Mulai</label><input type="time" name="start_time" :disabled="activeCreateCat !== 'lembur'" class="w-full px-3 py-2 text-sm border rounded-lg border-slate-300" required></div>
                                <div><label class="block text-xs font-bold text-gray-700 mb-1">Selesai</label><input type="time" name="end_time" :disabled="activeCreateCat !== 'lembur'" class="w-full px-3 py-2 text-sm border rounded-lg border-slate-300" required></div>
                            </div>
                            <div><label class="block text-xs font-bold text-gray-700 mb-1">Pekerjaan</label><textarea name="reason" :disabled="activeCreateCat !== 'lembur'" rows="2" class="w-full px-3 py-2 text-sm border rounded-lg border-slate-300" required></textarea></div>
                        </div>

                        <div class="mt-6 pt-4 border-t border-gray-100 flex justify-end gap-2">
                            <button type="button" @click="closeCreateModal()" class="px-5 py-2 text-gray-600 text-sm font-bold bg-gray-100 hover:bg-gray-200 rounded-lg transition">Batal</button>
                            <button type="submit" :disabled="isSubmitting" class="px-6 py-2 bg-indigo-600 text-white text-sm font-bold rounded-lg hover:bg-indigo-700 shadow-md transition flex items-center disabled:opacity-50 disabled:cursor-not-allowed">
                                <i class="fas" :class="isSubmitting ? 'fa-circle-notch fa-spin mr-2' : 'fa-save mr-2'"></i>
                                <span x-text="isSubmitting ? 'Menyimpan...' : 'Simpan'"></span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div x-show="detailModalOpen" style="display: none;" class="fixed inset-0 z-50 overflow-y-auto" x-cloak>
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 transition-opacity bg-gray-900 bg-opacity-75" @click="detailModalOpen = false"></div>

            <div class="inline-block align-bottom bg-white rounded-2xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-2xl w-full">
                
                <div class="bg-gray-50 px-6 py-4 border-b border-gray-100 flex justify-between items-center">
                    <h3 class="text-lg font-bold text-gray-800">Detail Pengajuan</h3>
                    <button @click="detailModalOpen = false" class="text-gray-400 hover:text-gray-600"><i class="fas fa-times"></i></button>
                </div>

                <div class="p-6">
                    <div x-show="isLoadingDetail" class="text-center py-10">
                        <i class="fas fa-circle-notch fa-spin text-3xl text-indigo-500 mb-3"></i>
                        <p class="text-gray-500 text-sm">Mengambil data...</p>
                    </div>

                    <div x-show="!isLoadingDetail && detailData.id">
                        
                        <div class="flex items-start gap-4 mb-6">
                            <div class="w-14 h-14 rounded-full bg-indigo-100 flex items-center justify-center text-indigo-600 font-bold text-xl">
                                <span x-text="detailData.first_name ? detailData.first_name.charAt(0) : ''"></span>
                            </div>
                            <div>
                                <h4 class="text-lg font-bold text-gray-900" x-text="detailData.first_name + ' ' + detailData.last_name"></h4>
                                <p class="text-sm text-gray-500" x-text="detailData.department_name + ' • ' + detailData.employee_number"></p>
                                <div class="mt-1">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold capitalize"
                                          :class="{
                                              'bg-green-100 text-green-800': detailData.status == 'approved',
                                              'bg-red-100 text-red-800': detailData.status == 'rejected',
                                              'bg-yellow-100 text-yellow-800': detailData.status == 'pending'
                                          }"
                                          x-text="detailData.status"></span>
                                </div>
                            </div>
                        </div>

                        <div class="bg-gray-50 p-5 rounded-xl border border-gray-100 mb-6">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                                <div>
                                    <p class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-1">Kategori :</p>
                                    <p class="font-bold text-gray-800 text-base capitalize" x-text="detailType == 'leave' ? (detailData.leave_type_name || 'Izin') : (detailType == 'trip' ? 'SPPD' : 'Lembur')"></p>
                                </div>

                                <div>
                                    <div x-show="detailType == 'leave' || detailType == 'trip'" class="space-y-1 text-sm">
                                        <div class="flex justify-between">
                                            <span class="text-gray-500 font-medium">Tanggal mulai :</span>
                                            <span class="font-bold text-gray-800" x-text="detailData.formatted_start"></span>
                                        </div>
                                        <div class="flex justify-between">
                                            <span class="text-gray-500 font-medium">Tanggal selesai :</span>
                                            <span class="font-bold text-gray-800" x-text="detailData.formatted_end"></span>
                                        </div>
                                    </div>
                                    <div x-show="detailType == 'overtime'" class="space-y-1 text-sm" style="display: none;">
                                        <div class="flex justify-between">
                                            <span class="text-gray-500 font-medium">Tanggal :</span>
                                            <span class="font-bold text-gray-800" x-text="detailData.formatted_ot_date"></span>
                                        </div>
                                        <div class="flex justify-between">
                                            <span class="text-gray-500 font-medium">Jam :</span>
                                            <span class="font-bold text-gray-800" x-text="(detailData.start_time || '').substring(0,5) + ' - ' + (detailData.end_time || '').substring(0,5)"></span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <hr class="border-gray-200 mb-4">

                            <div class="mb-4">
                                <p x-show="detailType == 'leave' || detailType == 'trip'" class="text-sm font-bold text-indigo-700">
                                    Jumlah hari <span x-text="detailType == 'trip' ? 'SPPD' : 'Pengajuan'"></span> : <span x-text="detailData.total_days + ' hari'"></span>
                                </p>
                                
                                <p x-show="detailType == 'leave' && detailData.sisa_cuti !== undefined" class="text-sm font-bold text-emerald-600 mt-1" style="display: none;">
                                    Sisa Cuti Karyawan : <span x-text="detailData.sisa_cuti + ' hari'"></span>
                                </p>
                                <p x-show="detailType == 'overtime'" class="text-sm font-bold text-indigo-700" style="display: none;">
                                    Jumlah jam Lembur : <span x-text="detailData.total_hours + ' jam'"></span>
                                </p>
                            </div>
                            
                            <div x-show="detailType == 'trip'" class="mb-2 space-y-1 text-sm" style="display: none;">
                                 <div class="flex">
                                     <span class="text-gray-500 font-medium w-32">Tujuan :</span>
                                     <span class="font-bold text-gray-800" x-text="detailData.destination"></span>
                                 </div>
                                 <div class="flex">
                                     <span class="text-gray-500 font-medium w-32">Estimasi Biaya :</span>
                                     <span class="font-bold text-gray-800" x-text="'Rp ' + new Intl.NumberFormat('id-ID').format(detailData.estimated_budget || 0)"></span>
                                 </div>
                            </div>
                        </div>

                        <div class="mb-6">
                            <p class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Keperluan / Alasan :</p>
                            <div class="bg-white border border-gray-200 p-4 rounded-lg text-sm text-gray-700 leading-relaxed" x-text="detailData.reason || detailData.trip_purpose || '-'"></div>
                        </div>

                        <div class="mb-6" x-show="detailData.supporting_document">
                            <p class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Lampiran Bukti</p>
                            <a :href="'<?= BASEURL ?>/' + detailData.supporting_document" target="_blank" class="flex items-center p-3 border border-gray-200 rounded-lg hover:bg-gray-50 transition group">
                                <div class="w-10 h-10 bg-red-100 text-red-600 rounded flex items-center justify-center mr-3">
                                    <i class="fas fa-file-pdf"></i>
                                </div>
                                <div>
                                    <p class="text-sm font-bold text-gray-800 group-hover:text-indigo-600">Lihat Dokumen</p>
                                    <p class="text-xs text-gray-500">Klik untuk membuka</p>
                                </div>
                            </a>
                        </div>
                        
                        <div class="flex gap-3 pt-4 border-t border-gray-100" x-show="detailData.status == 'pending'">
                            <button @click="openModal(detailData.id, detailType, 'rejected', detailData.first_name)" class="flex-1 px-4 py-2 border border-red-200 text-red-600 font-bold rounded-lg hover:bg-red-50">
                                Tolak
                            </button>
                            <button @click="openModal(detailData.id, detailType, 'approved', detailData.first_name)" class="flex-1 px-4 py-2 bg-indigo-600 text-white font-bold rounded-lg hover:bg-indigo-700 shadow-md">
                                Setujui
                            </button>
                        </div>

                        <div x-show="detailData.status != 'pending'" class="bg-gray-50 p-4 rounded-lg text-sm">
                            <p class="font-bold text-gray-900 mb-1">Catatan Reviewer:</p>
                            <p class="text-gray-600 italic" x-text="detailData.reviewer_notes || 'Tidak ada catatan.'"></p>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>

    <div x-show="modalOpen" style="display: none;" class="fixed inset-0 z-50 overflow-y-auto" x-cloak>
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 transition-opacity bg-gray-900 bg-opacity-75" @click="modalOpen = false"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen">&#8203;</span>
            <div class="inline-block align-bottom bg-white rounded-2xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg w-full p-6 animate-fade-in-up">
                <h3 class="text-xl font-bold text-gray-900 mb-2 text-center">Konfirmasi Tindakan</h3>
                <p class="text-sm text-gray-500 mb-6 text-center">
                    Anda akan <span x-text="action.status == 'approved' ? 'MENYETUJUI' : 'MENOLAK'" class="font-bold uppercase" :class="action.status == 'approved' ? 'text-green-600' : 'text-red-600'"></span> pengajuan dari <strong x-text="action.name"></strong>.
                </p>
                <form @submit.prevent="submitAction">
                    <div class="mb-5">
                        <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Catatan (Opsional)</label>
                        <textarea x-model="action.notes" rows="3" class="w-full border-gray-300 rounded-xl shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm p-3"></textarea>
                    </div>
                    <div class="grid grid-cols-2 gap-3">
                        <button type="button" @click="modalOpen = false" class="w-full px-4 py-3 bg-gray-100 text-gray-700 font-bold rounded-xl hover:bg-gray-200 transition">Batal</button>
                        <button type="submit" :class="action.status == 'approved' ? 'bg-green-600 hover:bg-green-700' : 'bg-red-600 hover:bg-red-700'" class="w-full px-4 py-3 text-white font-bold rounded-xl shadow-lg transition">Konfirmasi</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

</div>

<?php 
// FUNGSI RENDER TABLE DIBUAT DENGAN HTML RAW MURNI, ANTI PARSE ERROR!
function renderTable($data, $type, $month, $year, $status = '') {
    $query_string = "?month={$month}&year={$year}";
    if ($status) {
        $query_string .= "&status={$status}";
    }
    ?>
    <div class="overflow-x-auto">
        <table class="w-full text-sm text-left">
            <thead class="bg-gray-50/50 text-gray-500 font-bold uppercase text-xs tracking-wider border-b border-gray-100">
                <tr>
                    <th class="p-6">Karyawan</th>
                    <th class="p-6">Detail</th>
                    <th class="p-6">Tanggal</th>
                    <th class="p-6 text-center">Status</th>
                    <th class="p-6 text-center">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                <?php if (empty($data)): ?>
                    <tr>
                        <td colspan="5" class="p-10 text-center text-gray-400 italic">Tidak ada data pengajuan di bulan ini.</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($data as $row): 
                        $current_type = ($type == 'mixed') ? ($row['general_type'] ?? 'leave') : $type;
                        
                        $desc = $row['reason'] ?? $row['trip_purpose'] ?? '-';
                        $title = $row['req_label'] ?? ($current_type == 'trip' ? 'SPPD: '.$row['destination'] : 'Lembur');
                        if (isset($row['leave_type_name']) && $type !== 'mixed') {
                            $title = $row['leave_type_name'];
                        }
                        
                        $dateStr = date('d M Y', strtotime($row['start_date'] ?? $row['overtime_date']));
                        if (isset($row['end_date'])) {
                            $dateStr .= ' - ' . date('d M Y', strtotime($row['end_date']));
                        }
                        
                        if ($current_type == 'overtime') {
                            $duration = ($row['total_hours'] ?? 0) . ' Jam';
                        } else {
                            $duration = ($row['total_days'] ?? 0) . ' Hari';
                        }
                    ?>
                    <tr class="hover:bg-gray-50 transition">
                        <td class="p-6">
                            <div class="font-bold text-gray-900"><?= $row['first_name'] . ' ' . $row['last_name'] ?></div>
                            <div class="text-xs text-gray-500"><?= $row['department_name'] ?></div>
                        </td>
                        <td class="p-6">
                            <div class="font-bold text-gray-800"><?= $title ?></div>
                            <div class="text-xs text-gray-500 truncate max-w-[150px]" title="<?= htmlspecialchars($desc) ?>"><?= $desc ?></div>
                        </td>
                        <td class="p-6">
                            <div class="font-mono text-gray-700"><?= $dateStr ?></div>
                            <div class="text-xs font-bold text-indigo-600"><?= $duration ?></div>
                        </td>
                        <td class="p-6 text-center">
                            <?= statusBadge($row['status']) ?>
                        </td>
                        <td class="p-6 text-center flex items-center justify-center gap-2">
                            <button @click="openDetailModal(<?= $row['id'] ?>, '<?= $current_type ?>')" class="text-gray-500 hover:text-indigo-600 hover:bg-gray-100 p-2 rounded transition" title="Lihat Detail">
                                <i class="fas fa-eye"></i>
                            </button>
                            
                            <?php if ($row['status'] == 'pending'): ?>
                                <div class="h-4 w-px bg-gray-300 mx-1"></div>
                                <?= renderActionButtons($row, $current_type) ?>
                            <?php endif; ?>

                            <div class="h-4 w-px bg-gray-300 mx-1"></div>
                            <a href="<?= BASEURL ?>/admin/requests/delete/<?= $current_type ?>/<?= $row['id'] ?><?= $query_string ?>" onclick="return confirm('Yakin ingin menghapus pengajuan ini selamanya?')" class="text-red-400 hover:text-red-600 hover:bg-red-50 p-2 rounded transition" title="Hapus">
                                <i class="fas fa-trash"></i>
                            </a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
    <?php
}

function renderActionButtons($row, $type) {
    $id = $row['id'];
    $name = addslashes($row['first_name']);
    return '
    <button @click="openModal('.$id.', \''.$type.'\', \'approved\', \''.$name.'\')" class="text-green-600 hover:bg-green-50 p-2 rounded transition" title="Approve">
        <i class="fas fa-check-circle text-lg"></i>
    </button>
    <button @click="openModal('.$id.', \''.$type.'\', \'rejected\', \''.$name.'\')" class="text-red-600 hover:bg-red-50 p-2 rounded transition" title="Reject">
        <i class="fas fa-times-circle text-lg"></i>
    </button>';
}

function statusBadge($status) {
    if($status == 'approved') return '<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-green-100 text-green-800 border border-green-200">Disetujui</span>';
    if($status == 'rejected') return '<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-red-100 text-red-800 border border-red-200">Ditolak</span>';
    return '<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-yellow-100 text-yellow-800 border border-yellow-200"><span class="w-1.5 h-1.5 bg-yellow-500 rounded-full mr-1.5 animate-pulse"></span>Pending</span>';
}
?>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<?php if (isset($_SESSION['flash'])): ?>
    <script>
        Swal.fire({
            icon: '<?= $_SESSION['flash']['tipe'] === "danger" ? "error" : $_SESSION['flash']['tipe'] ?>',
            title: '<?= $_SESSION['flash']['pesan'] ?>',
            text: '<?= $_SESSION['flash']['aksi'] ?>',
            confirmButtonColor: '#4f46e5'
        });
    </script>
    <?php unset($_SESSION['flash']); ?>
<?php elseif (isset($_SESSION['flash_message'])): ?>
    <script>
        Swal.fire({
            icon: 'info',
            title: 'Informasi',
            text: '<?= $_SESSION['flash_message'] ?>',
            confirmButtonColor: '#4f46e5'
        });
    </script>
    <?php unset($_SESSION['flash_message']); ?>
<?php endif; ?>