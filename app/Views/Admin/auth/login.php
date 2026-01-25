<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'Login' ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        /* Hilangkan spinner pada input number */
        input[type=number]::-webkit-inner-spin-button, 
        input[type=number]::-webkit-outer-spin-button { 
            -webkit-appearance: none; 
            margin: 0; 
        }
    </style>
</head>
<body class="bg-gray-50 h-screen flex items-center justify-center font-sans">

    <div class="bg-white p-10 rounded-3xl shadow-2xl w-full max-w-md border border-gray-100">
        
        <div class="text-center mb-10">
            <div class="inline-flex items-center justify-center w-20 h-20 bg-indigo-50 rounded-full mb-6 text-indigo-600">
                <i class="fas fa-fingerprint text-4xl"></i>
            </div>
            <h1 class="text-3xl font-extrabold text-gray-800 tracking-tight">AbsenPWA</h1>
            <p class="text-sm text-gray-500 mt-2">Silakan login untuk melanjutkan</p>
        </div>

        <?php if (isset($_SESSION['flash_error'])): ?>
            <div class="bg-red-50 border border-red-200 text-red-600 px-4 py-3 rounded-xl mb-6 text-sm text-center shadow-sm">
                <i class="fas fa-circle-exclamation mr-2"></i>
                <?= $_SESSION['flash_error']; unset($_SESSION['flash_error']); ?>
            </div>
        <?php endif; ?>

        <form action="<?= BASEURL ?>/auth/login" method="POST" class="space-y-6" id="loginForm">
            
            <div>
                <label class="block text-sm font-bold text-gray-700 mb-4 text-center">Nomor Induk (NIK)</label>
                
                <input type="hidden" name="username" id="full_nik">

                <div class="flex items-center justify-center gap-2">
                    <input type="text" maxlength="1" class="nik-input w-12 h-14 text-center text-2xl font-bold border-2 border-gray-300 rounded-lg focus:border-indigo-500 focus:ring-4 focus:ring-indigo-100 outline-none uppercase transition-all text-indigo-600" data-index="0" placeholder="T">
                    
                    <span class="text-2xl font-bold text-indigo-400 select-none">-</span>
                    
                    <input type="text" inputmode="numeric" maxlength="1" class="nik-input w-12 h-14 text-center text-2xl font-bold border-2 border-gray-300 rounded-lg focus:border-indigo-500 focus:ring-4 focus:ring-indigo-100 outline-none transition-all text-gray-700" data-index="1" placeholder="2">
                    <input type="text" inputmode="numeric" maxlength="1" class="nik-input w-12 h-14 text-center text-2xl font-bold border-2 border-gray-300 rounded-lg focus:border-indigo-500 focus:ring-4 focus:ring-indigo-100 outline-none transition-all text-gray-700" data-index="2" placeholder="0">
                    
                    <span class="text-2xl font-bold text-indigo-400 select-none">.</span>

                    <input type="text" inputmode="numeric" maxlength="1" class="nik-input w-12 h-14 text-center text-2xl font-bold border-2 border-gray-300 rounded-lg focus:border-indigo-500 focus:ring-4 focus:ring-indigo-100 outline-none transition-all text-gray-700" data-index="3" placeholder="0">
                    <input type="text" inputmode="numeric" maxlength="1" class="nik-input w-12 h-14 text-center text-2xl font-bold border-2 border-gray-300 rounded-lg focus:border-indigo-500 focus:ring-4 focus:ring-indigo-100 outline-none transition-all text-gray-700" data-index="4" placeholder="0">
                    <input type="text" inputmode="numeric" maxlength="1" class="nik-input w-12 h-14 text-center text-2xl font-bold border-2 border-gray-300 rounded-lg focus:border-indigo-500 focus:ring-4 focus:ring-indigo-100 outline-none transition-all text-gray-700" data-index="5" placeholder="1">
                </div>
            </div>

            <div>
                <label class="block text-sm font-bold text-gray-700 mb-2">Password</label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-4 text-gray-400">
                        <i class="fas fa-lock"></i>
                    </span>
                    <input type="password" name="password" required placeholder="••••••••"
                           class="w-full pl-11 pr-4 py-3.5 rounded-xl border border-gray-300 focus:outline-none focus:ring-4 focus:ring-indigo-100 focus:border-indigo-500 transition font-medium text-gray-700">
                </div>
            </div>

            <button type="submit" class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-4 rounded-xl shadow-lg shadow-indigo-200 hover:shadow-xl transition-all duration-200 transform hover:-translate-y-0.5 text-lg">
                Masuk Sistem
            </button>

        </form>
        
        <p class="text-center text-xs text-gray-400 mt-10">
            &copy; <?= date('Y') ?> PT Sketz Indonesia. All rights reserved.
        </p>
    </div>

    <script>
        const inputs = document.querySelectorAll('.nik-input');
        const hiddenInput = document.getElementById('full_nik');

        inputs.forEach((input, index) => {
            // 1. Handle Input Character
            input.addEventListener('input', (e) => {
                const value = e.target.value;
                
                // Validasi: Input pertama harus Huruf, sisanya Angka
                if (index === 0) {
                    // Force Uppercase untuk huruf depan
                    e.target.value = value.replace(/[^a-zA-Z]/g, '').toUpperCase();
                } else {
                    // Force Number untuk kotak lainnya
                    e.target.value = value.replace(/[^0-9]/g, '');
                }

                // Auto Focus ke kotak selanjutnya jika sudah terisi
                if (e.target.value.length === 1 && index < inputs.length - 1) {
                    inputs[index + 1].focus();
                }

                updateHiddenValue();
            });

            // 2. Handle Backspace (Pindah ke belakang)
            input.addEventListener('keydown', (e) => {
                if (e.key === 'Backspace' && e.target.value === '' && index > 0) {
                    inputs[index - 1].focus();
                }
            });

            // 3. Handle Paste (Jika user copas "T20001")
            input.addEventListener('paste', (e) => {
                e.preventDefault();
                const pasteData = (e.clipboardData || window.clipboardData).getData('text').toUpperCase().replace(/[^A-Z0-9]/g, ''); // Ambil hanya alphanum
                
                if (!pasteData) return;

                // Loop untuk mengisi kotak
                let dataIndex = 0;
                for (let i = 0; i < inputs.length; i++) {
                    if (dataIndex < pasteData.length) {
                        inputs[i].value = pasteData[dataIndex];
                        // Validasi ulang tipe data per kotak saat paste
                        if(i === 0) inputs[i].value = inputs[i].value.replace(/[^A-Z]/, '');
                        else inputs[i].value = inputs[i].value.replace(/[^0-9]/, '');
                        
                        dataIndex++;
                    }
                }
                updateHiddenValue();
                // Focus ke kotak terakhir yang terisi
                const lastFilled = Math.min(pasteData.length - 1, inputs.length - 1);
                inputs[lastFilled >= 0 ? lastFilled : 0].focus();
            });
        });

        // Fungsi Menggabungkan Nilai ke Hidden Input
        function updateHiddenValue() {
            let fullNik = '';
            // Format: H-YY.XXX
            // Index 0 (Huruf)
            fullNik += inputs[0].value;
            
            // Tambah "-" jika Huruf dan Tahun pertama ada
            if (inputs[0].value && inputs[1].value) fullNik += '-';
            
            // Index 1-2 (Tahun)
            fullNik += inputs[1].value + inputs[2].value;
            
            // Tambah "." jika Tahun kedua dan Nomor pertama ada
            if (inputs[2].value && inputs[3].value) fullNik += '.';
            
            // Index 3-5 (Nomor Urut)
            fullNik += inputs[3].value + inputs[4].value + inputs[5].value;

            hiddenInput.value = fullNik; // Nilai ini yang dikirim: "T-20.001"
            console.log("Submit Value:", hiddenInput.value); 
        }
    </script>
</body>
</html>