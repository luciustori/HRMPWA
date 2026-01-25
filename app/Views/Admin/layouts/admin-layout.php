<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'AbsenPWA Admin' ?></title>
    
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/gh/alpinejs/alpine@v2.x.x/dist/alpine.min.js" defer></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    
    <style>
        [x-cloak] { display: none !important; }
        /* Transisi halus untuk lebar element */
        .transition-width { transition-property: width, margin; transition-duration: 300ms; }
    </style>
</head>

<body class="bg-gray-50 font-sans antialiased text-gray-900" 
      x-data="{ sidebarOpen: false, sidebarCondensed: false }">

    <?php require_once __DIR__ . '/sidebar.php'; ?>

    <div class="relative min-h-screen flex flex-col transition-all duration-300 ease-in-out"
         :class="sidebarCondensed ? 'md:ml-20' : 'md:ml-64'">
        
        <?php require_once __DIR__ . '/topnavbar.php'; ?>

        <main class="flex-1 p-6 mt-16">
            <?php 
                if (isset($content_view) && file_exists('../app/Views/' . $content_view . '.php')) {
                    require_once '../app/Views/' . $content_view . '.php';
                } else {
                    echo "<div class='bg-red-50 text-red-600 p-4 rounded border border-red-200'>
                            Error: View file <b>" . ($content_view ?? 'UNKNOWN') . "</b> not found.
                          </div>";
                }
            ?>
        </main>

        <?php require_once __DIR__ . '/footer.php'; ?>
        
    </div>

</body>
</html>