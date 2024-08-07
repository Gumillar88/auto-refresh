<?php
require __DIR__ . '/../vendor/autoload.php';

use Glw\AutoRefresh\AutoRefreshServer;

// File-file atau direktori yang akan dipantau
$watchedFiles = [
    __DIR__ . '/../src/AutoRefresh.php',
    __DIR__ . '/../examples/', // Contoh memantau semua file dalam direktori examples
    // Tambahkan file atau direktori lain yang ingin Anda pantau
];

// Jalankan server WebSocket dan pantau perubahan file
AutoRefreshServer::runServer($watchedFiles);