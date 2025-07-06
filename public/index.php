<?php
// File: public/index.php

// Mulai session di awal
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Aktifkan error reporting untuk development
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Definisikan path dasar aplikasi
define('BASE_PATH', dirname(__DIR__));

// Muat file-file inti
require_once BASE_PATH . '/core/Router.php';
require_once BASE_PATH . '/routes/web.php';

// Ambil URL dari parameter, atau set default ke halaman utama
$url = $_GET['url'] ?? '';

// Jalankan router untuk mengarahkan ke controller yang tepat
try {
    Router::route($url);
} catch (Exception $e) {
    // Tangani error jika rute tidak ditemukan atau ada masalah lain
    // Anda bisa membuat halaman error 404 yang lebih bagus di sini
    http_response_code(404);
    echo "<h1>404 Not Found</h1>";
    echo "Error: " . $e->getMessage();
}
