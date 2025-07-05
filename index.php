<?php
// File: index.php

// 1. Pengaturan Error Reporting yang Ketat
error_reporting(E_ALL);
ini_set('display_errors', 1); // Aktifkan untuk development, matikan (set ke 0) untuk production

// 2. Custom Exception Handler (untuk menangkap error fatal dan mengarahkan ke halaman debug)
set_exception_handler(function($exception) {
    // Anda bisa menambahkan logika logging ke file di sini jika perlu
    
    // Alihkan ke halaman debug dengan pesan error
    $redirectUrl = '?url=debug&error=' . urlencode($exception->getMessage());
    header('Location: ' . $redirectUrl);
    exit;
});

// 3. Memulai Session
// Session harus dimulai setelah semua pengaturan error agar bisa menangani session-related errors
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// 4. Logika Router Utama
// **PERBAIKAN:** Halaman default sekarang adalah 'home' untuk pengunjung baru
$url = $_GET['url'] ?? 'home'; 
$urlParts = explode('/', rtrim($url, '/'));

$controllerName = ucfirst($urlParts[0]) . 'Controller';
$method = $urlParts[1] ?? 'index';
$controllerFile = __DIR__ . '/controllers/' . $controllerName . '.php';

if (file_exists($controllerFile)) {
    require_once $controllerFile;

    if (class_exists($controllerName)) {
        $controller = new $controllerName();

        if (method_exists($controller, $method)) {
            // Menggunakan try-catch untuk menangkap exception dari dalam controller
            try {
                $controller->$method();
            } catch (Throwable $t) {
                // Jika ada exception, panggil handler yang sudah kita set di atas
                throw $t;
            }
        } else {
            // Melempar exception jika method tidak ditemukan
            throw new Exception("Method <strong>$method</strong> tidak ditemukan di <strong>$controllerName</strong>.");
        }
    } else {
        // Melempar exception jika class tidak ditemukan
        throw new Exception("Class <strong>$controllerName</strong> tidak tersedia di file <strong>$controllerFile</strong>.");
    }
} else {
    // Melempar exception jika file controller tidak ditemukan
    throw new Exception("File controller <strong>$controllerFile</strong> tidak ditemukan.");
}
