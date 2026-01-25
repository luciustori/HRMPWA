<?php
/**
 * ABSENPWA - Entry Point
 */

if (!session_id()) session_start();

// Error Reporting
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Define Paths
define('ROOT_PATH', dirname(__DIR__)); 
define('APP_PATH', ROOT_PATH . '/app');
define('BASEURL', 'http://absenpwa.test'); // Sesuaikan domain

// Load Config
require_once ROOT_PATH . '/config/config.php';

// AUTOLOADER (Jantungnya MVC)
spl_autoload_register(function ($className) {
    $directories = [
        APP_PATH . '/Core/',
        APP_PATH . '/Controllers/',
        APP_PATH . '/Controllers/Admin/', // Tambahan buat Admin
        APP_PATH . '/Controllers/Pwa/',   // Tambahan buat PWA
        APP_PATH . '/Models/',
        APP_PATH . '/Helpers/',
        APP_PATH . '/Middleware/',
        APP_PATH . '/Services/'
    ];

    foreach ($directories as $directory) {
        $file = $directory . $className . '.php';
        if (file_exists($file)) {
            require_once $file;
            return;
        }
    }
});

// Jalankan App
$app = new App;