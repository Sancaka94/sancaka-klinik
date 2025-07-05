<?php
/**
 * File: index.php
 * Deskripsi: Ini adalah titik masuk utama (front controller) untuk seluruh aplikasi.
 * Semua permintaan akan melewati file ini terlebih dahulu.
 */

// 1. Tampilkan semua error untuk mempermudah debugging selama masa pengembangan.
//    (Bisa dimatikan di server produksi nanti)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// 2. Mulai session untuk menangani data login dan pesan error.
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// 3. Ambil URL yang diminta oleh pengguna.
//    Jika tidak ada URL spesifik (misal: saat membuka halaman utama),
//    maka default akan diarahkan ke controller 'home'.
$url = $_GET['url'] ?? 'home';

// 4. Bersihkan dan pecah URL menjadi beberapa bagian berdasarkan '/'.
//    Contoh: 'auth/register' akan menjadi ['auth', 'register']
$urlParts = explode('/', filter_var(rtrim($url, '/'), FILTER_SANITIZE_URL));

// 5. Tentukan nama Controller dari bagian pertama URL.
//    Contoh: dari 'auth', nama controller menjadi 'AuthController'.
//    Jika URL kosong, default ke 'HomeController'.
$controllerName = !empty($urlParts[0]) ? ucfirst($urlParts[0]) . 'Controller' : 'HomeController';
$controllerFile = __DIR__ . '/controllers/' . $controllerName . '.php';

// 6. Tentukan nama Method (fungsi) dari bagian kedua URL.
//    Contoh: dari 'register', nama method menjadi 'register'.
//    Jika tidak ada, default ke method 'index'.
$methodName = $urlParts[1] ?? 'index';

// 7. Periksa apakah file controller-nya ada.
if (file_exists($controllerFile)) {
    require_once $controllerFile;

    // 8. Periksa apakah class controller-nya ada di dalam file tersebut.
    if (class_exists($controllerName)) {
        $controller = new $controllerName();

        // 9. Periksa apakah method-nya ada di dalam class controller.
        if (method_exists($controller, $methodName)) {
            // Ambil sisa bagian URL sebagai parameter untuk method.
            $params = array_slice($urlParts, 2);
            
            // Panggil method pada controller dengan parameter yang ada.
            call_user_func_array([$controller, $methodName], $params);
        } else {
            // Error jika method tidak ditemukan.
            http_response_code(404);
            echo "Error 404: Method '$methodName' tidak ditemukan pada controller '$controllerName'.";
        }
    } else {
        // Error jika class tidak ditemukan.
        http_response_code(404);
        echo "Error 404: Class controller '$controllerName' tidak ditemukan.";
    }
} else {
    // Error jika file controller tidak ditemukan.
    http_response_code(404);
    echo "Error 404: Halaman tidak ditemukan (Controller '$controllerName' tidak ada).";
}
