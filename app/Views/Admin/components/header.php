<!-- File: app/Views/Admin/components/header.php -->
<?php
// Header Component - TIDAK INLINE!
$company_name = isset($company['name']) ? $company['name'] : 'HRM System';
$company_logo = isset($company['logo_path']) ? $company['logo_path'] : '/assets/images/logo.png';
?>

<header class="bg-white shadow">
    <div class="max-w-7xl mx-auto px-4 py-4 flex justify-between items-center">
        <div class="flex items-center space-x-4">
            <img src="<?= $company_logo ?>" alt="Logo" class="h-10 w-auto">
            <h1 class="text-2xl font-bold text-gray-800"><?= $company_name ?></h1>
        </div>
    </div>
</header>
