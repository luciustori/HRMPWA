<?php 
    $is_locked = in_array($period['status'], ['approved', 'paid', 'closed']); 
?>
<div class="max-w-[1600px] mx-auto p-6" x-data="{ 
    editModal: false, 
    trx: { id: '', period_id: '<?= $period['id'] ?>', name: '', gross: 0, allow: 0, deduct: 0, net: 0 },
    
    openEdit(data) {
        this.trx.id = data.id;
        this.trx.name = data.first_name + ' ' + (data.last_name || '');
        this.trx.gross = parseFloat(data.gross_salary);
        this.trx.allow = parseFloat(data.total_allowances);
        this.trx.deduct = parseFloat(data.total_deductions);
        this.calcNet();
        this.editModal = true;
    },

    calcNet() {
        this.trx.net = (parseFloat(this.trx.gross) || 0) + (parseFloat(this.trx.allow) || 0) - (parseFloat(this.trx.deduct) || 0);
    }
}">
    
    <div class="flex items-center gap-3 text-sm text-gray-500 mb-4">
        <a href="<?= BASEURL ?>/admin/payroll" class="hover:text-indigo-600"><i class="fas fa-arrow-left"></i> Kembali</a>
        <span>/</span>
        <span>Detail Payroll</span>
    </div>

    <div class="flex flex-col md:flex-row justify-between items-center mb-8 gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">
                Payroll: <span class="text-indigo-600"><?= htmlspecialchars($period['period_name']) ?></span>
            </h1>
            <p class="text-sm text-gray-500 mt-1">
                Status: <span class="font-bold uppercase <?= $is_locked ? 'text-green-600' : 'text-yellow-600' ?>"><?= $period['status'] ?></span> &bull; 
                Total Karyawan: <?= $period['total_employees'] ?> &bull; 
                Total Net: <strong class="text-indigo-600">Rp <?= number_format($period['total_net_salary'], 0, ',', '.') ?></strong>
            </p>
        </div>
        
        <?php if (!$is_locked): ?>
        <a href="<?= BASEURL ?>/admin/payroll/finalize/<?= $period['id'] ?>" 
           class="swal-link bg-green-600 text-white px-5 py-2.5 rounded-lg font-bold shadow-md hover:bg-green-700 transition text-sm flex items-center"
           data-action="sync" data-msg="Data yang sudah di-Finalize & Lock TIDAK BISA DIUBAH LAGI. Lanjutkan?">
            <i class="fas fa-lock mr-2"></i> Finalize & Lock
        </a>
        <?php else: ?>
        <div class="bg-gray-100 text-gray-500 px-5 py-2.5 rounded-lg font-bold text-sm flex items-center border border-gray-200">
            <i class="fas fa-lock mr-2"></i> Payroll Locked
        </div>
        <?php endif; ?>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead class="bg-gray-50 text-gray-600 border-b border-gray-200">
                    <tr>
                        <th class="p-4 font-bold uppercase tracking-wider">Karyawan</th>
                        <th class="p-4 text-right font-bold uppercase tracking-wider">Gaji Pokok</th>
                        <th class="p-4 text-right font-bold uppercase tracking-wider text-green-600">Tunjangan</th>
                        <th class="p-4 text-right font-bold uppercase tracking-wider text-red-600">Potongan</th>
                        <th class="p-4 text-right font-bold uppercase tracking-wider text-indigo-700">Total Net</th>
                        <th class="p-4 text-center font-bold uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    <?php if(empty($transactions)): ?>
                        <tr><td colspan="6" class="text-center py-8 text-gray-400">Belum ada data transaksi untuk bulan ini.</td></tr>
                    <?php endif; ?>

                    <?php foreach($transactions as $row): ?>
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="p-4">
                            <div class="font-bold text-gray-900"><?= $row['first_name'] . ' ' . $row['last_name'] ?></div>
                            <div class="text-xs text-gray-500 mt-1">
                                <?= $row['employee_number'] ?> &bull; <?= $row['department_name'] ?? '-' ?>
                            </div>
                        </td>
                        <td class="p-4 text-right font-mono text-gray-600">
                            <?= number_format($row['gross_salary'], 0, ',', '.') ?>
                        </td>
                        <td class="p-4 text-right font-mono text-green-600 font-medium">
                            + <?= number_format($row['total_allowances'], 0, ',', '.') ?>
                        </td>
                        <td class="p-4 text-right font-mono text-red-600 font-medium">
                            - <?= number_format($row['total_deductions'], 0, ',', '.') ?>
                        </td>
                        <td class="p-4 text-right font-mono font-bold text-lg text-indigo-700 bg-indigo-50/50">
                            <?= number_format($row['net_salary'], 0, ',', '.') ?>
                        </td>
                        <td class="p-4 text-center">
                            <div class="flex justify-center items-center gap-2">
                                <?php if (!$is_locked): ?>
                                <button @click="openEdit(<?= htmlspecialchars(json_encode($row)) ?>)" 
                                        class="text-yellow-500 hover:text-yellow-700 hover:bg-yellow-50 p-2 rounded-lg transition" title="Penyesuaian Manual">
                                    <i class="fas fa-edit text-lg"></i>
                                </button>
                                <?php endif; ?>
                                
                                <a href="<?= BASEURL ?>/admin/payroll/slip/<?= $row['id'] ?>" target="_blank" 
                                   class="text-gray-400 hover:text-indigo-600 hover:bg-indigo-50 p-2 rounded-lg transition" title="Cetak Slip">
                                    <i class="fas fa-print text-lg"></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

    <div x-show="editModal" class="fixed inset-0 z-50 flex items-center justify-center bg-gray-900 bg-opacity-60 backdrop-blur-sm" style="display: none;" x-transition>
        <div class="bg-white rounded-2xl w-full max-w-md p-6 shadow-2xl" @click.away="editModal = false">
            <div class="flex justify-between items-center mb-6 border-b border-gray-100 pb-3">
                <h3 class="text-xl font-bold text-gray-800">Penyesuaian Gaji</h3>
                <button @click="editModal=false" class="text-gray-400 hover:text-red-500"><i class="fas fa-times text-xl"></i></button>
            </div>
            
            <form class="swal-form" data-title="Simpan Penyesuaian?" data-msg="Data gaji karyawan ini akan diperbarui." action="<?= BASEURL ?>/admin/payroll/update_transaction" method="POST">
                <input type="hidden" name="transaction_id" x-model="trx.id">
                <input type="hidden" name="period_id" x-model="trx.period_id">

                <div class="mb-4 bg-gray-50 p-3 rounded-lg border border-gray-200">
                    <p class="text-xs text-gray-500 font-bold uppercase tracking-wider">Karyawan</p>
                    <p class="font-bold text-gray-900" x-text="trx.name"></p>
                </div>

                <div class="space-y-4">
                    <div>
                        <label class="block text-xs font-bold text-gray-600 uppercase mb-1">Gaji Pokok / Kotor (Rp)</label>
                        <input type="number" name="gross_salary" x-model="trx.gross" @input="calcNet()" required class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 font-mono font-bold text-gray-700">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-green-600 uppercase mb-1">Tunjangan Tambahan (Rp)</label>
                        <input type="number" name="total_allowances" x-model="trx.allow" @input="calcNet()" class="w-full rounded-lg border-gray-300 focus:border-green-500 focus:ring-green-500 font-mono font-bold text-green-600">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-red-600 uppercase mb-1">Total Potongan (Rp)</label>
                        <input type="number" name="total_deductions" x-model="trx.deduct" @input="calcNet()" class="w-full rounded-lg border-gray-300 focus:border-red-500 focus:ring-red-500 font-mono font-bold text-red-600">
                    </div>
                    
                    <div class="pt-4 border-t border-gray-200">
                        <label class="block text-xs font-bold text-indigo-800 uppercase mb-1">Gaji Bersih / Take Home Pay</label>
                        <div class="w-full bg-indigo-50 border border-indigo-200 rounded-lg p-3 text-2xl font-black font-mono text-indigo-700 text-right">
                            Rp <span x-text="trx.net.toLocaleString('id-ID')"></span>
                        </div>
                    </div>
                </div>

                <div class="mt-8 flex justify-end gap-3">
                    <button type="button" @click="editModal=false" class="px-5 py-2.5 bg-gray-100 text-gray-600 font-bold rounded-lg hover:bg-gray-200 transition-colors">Batal</button>
                    <button type="submit" class="px-5 py-2.5 bg-indigo-600 text-white font-bold rounded-lg shadow-md hover:bg-indigo-700 transition-colors">Simpan Data</button>
                </div>
            </form>
        </div>
    </div>
</div>