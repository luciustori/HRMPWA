<?php
// File: config/database.php

// Security: Mencegah akses langsung
if (!defined('APP_ROOT')) exit('No direct script access allowed');

return [
    'host'     => 'localhost',
    'dbname'   => 'mobile_db', // Pastikan nama DB sesuai di PHPMyAdmin Anda
    'username' => 'root',
    'password' => '',              // Default Laragon biasanya kosong
    'charset'  => 'utf8mb4',
    'options'  => [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES   => false,
        PDO::ATTR_PERSISTENT         => true // Persistent connection untuk performa
    ]
];