<?php
// index.php

// 1. Pengaturan Error Reporting yang Ketat
error_reporting(E_ALL);
ini_set('display_errors', 1); // Tetap aktifkan untuk development

// 2. Membuat Folder Log Jika Belum Ada
$logDir = __DIR__ . '/logs';
if (!is_dir($logDir)) {
    // **PERBAIKAN:** Menambahkan '@' untuk menekan warning "File exists"
    // jika folder dibuat oleh proses lain secara bersamaan (race condition).
    @mkdir($logDir, 0755, true);
}
$logFile = $logDir . '/app_errors.log';

// 3. Custom Exception Handler
// Fungsi ini akan menangkap semua error fatal yang tidak tertangani (Fatal error, Uncaught Error, etc.)
set_exception_handler(function($exception) use ($logFile) {
    // Format pesan error
    $errorMessage = sprintf(
        "Uncaught Exception: %s in %s:%d\nStack trace:\n%s",
        $exception->getMessage(),
        $exception->getFile(),
        $exception->getLine(),
        $exception->getTraceAsString()
    );

    // Catat error ke dalam file log
    file_put_contents($logFile, date('[Y-m-d H:i:s] ') . $errorMessage . "\n\n", FILE_APPEND);

    // Alihkan ke halaman debug dengan pesan error
    $redirectUrl = '?url=debug&error=' . urlencode($exception->getMessage());
    header('Location: ' . $redirectUrl);
    exit;
});

// 4. Memulai Session
session_start();

// 5. Logika Router Utama (tetap sama seperti sebelumnya)
$url = $_GET['url'] ?? 'auth/login';
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
            throw new Exception("Method <strong>$method</strong> tidak ditemukan di <strong>$controllerName</strong>.");
        }
    } else {
        throw new Exception("Class <strong>$controllerName</strong> tidak tersedia di file <strong>$controllerFile</strong>.");
    }
} else {
    throw new Exception("File controller <strong>$controllerFile</strong> tidak ditemukan.");
}
