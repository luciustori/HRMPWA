<?php
// File: config/config.php

if (!defined('BASEURL')) {
    // Sesuaikan URL ini saat pindah ke hosting
    //define('BASEURL', 'https://hris.xtsquare.co.id');
    define('BASEURL', 'https://absenpwa.test'); 
}

define('APP_NAME', 'AbsenPWA - HRM System');

// Pengaturan Path Absolut (Penting untuk Linux/Hosting)
define('APP_ROOT', dirname(__DIR__) . '/app');

// Pengaturan Database Terpusat
define('DB_HOST', 'localhost');
define('DB_USER', 'root');      // Ganti dengan user DB hosting saat upload
define('DB_PASS', '');          // Ganti dengan password DB hosting saat upload
define('DB_NAME', 'mobile_db'); // Ganti dengan nama DB hosting saat upload

