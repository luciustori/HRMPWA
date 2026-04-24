<div class="max-w-7xl mx-auto px-4 py-6">
    <form action="<?= BASEURL ?>/admin/paysliptemplates/store" method="POST">
        <?php if(isset($template['id'])): ?>
            <input type="hidden" name="id" value="<?= $template['id'] ?>">
        <?php endif; ?>

        <div class="flex justify-between items-center mb-4">
            <div class="flex items-center gap-4">
                <a href="<?= BASEURL ?>/admin/paysliptemplates" class="text-gray-500 hover:text-gray-700"><i class="fas fa-arrow-left"></i></a>
                <input type="text" name="template_name" value="<?= $template['template_name'] ?? '' ?>" placeholder="Nama Template (misal: Modern Blue)" class="text-xl font-bold border-none focus:ring-0 bg-transparent placeholder-gray-400 w-full" required>
            </div>
            <button type="submit" class="px-6 py-2 bg-indigo-600 text-white font-bold rounded shadow hover:bg-indigo-700">
                <i class="fas fa-save mr-2"></i> Simpan
            </button>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 h-[80vh]">
            <div class="lg:col-span-2 flex flex-col">
                <div class="bg-gray-800 text-gray-300 text-xs px-4 py-2 rounded-t-lg flex justify-between">
                    <span>HTML & PHP Editor</span>
                    <span>Variables: $header, $details</span>
                </div>
                <textarea name="content" class="w-full h-full bg-gray-900 text-gray-100 font-mono text-sm p-4 rounded-b-lg focus:outline-none resize-none leading-relaxed" spellcheck="false"><?= htmlspecialchars($template['content'] ?? '') ?></textarea>
            </div>

            <div class="bg-white rounded-xl shadow p-4 overflow-y-auto border border-gray-200">
                <h3 class="font-bold text-gray-800 mb-4 border-b pb-2">Daftar Variabel Tersedia</h3>
                
                <div class="space-y-4 text-xs">
                    <div>
                        <p class="font-bold text-indigo-600 mb-1">$header['...']</p>
                        <ul class="list-disc pl-4 space-y-1 text-gray-600 font-mono">
                            <li>period_name</li>
                            <li>employee_number</li>
                            <li>first_name, last_name</li>
                            <li>department_name</li>
                            <li>position_name</li>
                            <li>payment_method</li>
                            <li>basic_salary</li>
                            <li>net_salary</li>
                            <li>present_days</li>
                            <li>overtime_hours</li>
                        </ul>
                    </div>

                    <div>
                        <p class="font-bold text-indigo-600 mb-1">Looping Komponen Gaji</p>
                        <pre class="bg-gray-50 p-2 rounded text-gray-500 overflow-x-auto">
&lt;?php foreach($details as $d): ?&gt;
  &lt;?= $d['component_name'] ?&gt;
  &lt;?= $d['amount'] ?&gt;
  &lt;?= $d['component_type'] ?&gt;
&lt;?php endforeach; ?&gt;</pre>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>