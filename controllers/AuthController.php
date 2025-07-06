<?php
// File: app/controllers/AuthController.php
ini_set('display_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../models/User.php';

class AuthController {
    private $conn;

    public function __construct() {
        $database = new Database();
        $this->conn = $database->conn;
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
    }

    /**
     * [MODE DEBUG PINTAR] Memproses permintaan lupa password dengan output debug.
     */
    public function send_reset_link() {
        // Header untuk memastikan output adalah HTML
        header('Content-Type: text/html; charset=utf-8');

        echo "<h1><pre>-- DEBUGGING MODE AKTIF --</pre></h1>";
        echo "<hr>";
        echo "<p><strong>Analisis Data yang Diterima Server:</strong></p>";
        echo "<ul>";
        echo "<li>Metode Request Server: <strong>" . htmlspecialchars($_SERVER['REQUEST_METHOD']) . "</strong></li>";
        echo "<li>Isi dari \$_POST: <pre>" . htmlspecialchars(print_r($_POST, true)) . "</pre></li>";
        echo "</ul><hr>";

        $email = $_POST['email'] ?? '';
        if (empty($email)) {
            echo "<h3><font color='red'>ANALISIS GAGAL:</font></h3>";
            echo "<p>Server tidak menerima 'email' dari form. Pastikan form di `views/auth/forgot_password.php` memiliki `method=\"POST\"` dan input email memiliki `name=\"email\"`.</p>";
            die();
        }
        
        echo "<p><strong>Analisis Proses di Server:</strong></p>";
        echo "<ul>";
        echo "<li>Email yang diterima dari form: <strong>" . htmlspecialchars($email) . "</strong></li>";

        $userModel = new User($this->conn);
        echo "<li>Mencoba membuat token untuk email di database...</li>";
        
        $token = $userModel->generateResetToken($email);

        if ($token) {
            echo "<li><font color='green'><strong>SUKSES:</strong></font> Token berhasil dibuat.</li>";
            echo "<li>Token yang dihasilkan: <code>" . htmlspecialchars($token) . "</code></li>";
            
            $resetLink = "https://sancaka.biz.id/apps/klinik-app/?url=auth/reset_password&token=" . $token;
            $adminWhatsApp = "6285745808809";
            
            $message = "Permintaan reset password untuk email: " . $email . "\n\n";
            $message .= "Silakan klik link berikut untuk melanjutkan:\n";
            $message .= $resetLink;

            $waLink = "https://wa.me/" . $adminWhatsApp . "?text=" . urlencode($message);

            echo "<li>Link WhatsApp yang akan diarahkan: <a href='" . $waLink . "' target='_blank'>" . $waLink . "</a></li>";
            echo "</ul><hr>";
            echo "<h3><pre>-- PROSES DEBUG SELESAI --</pre></h3>";
            echo "<p>Jika ini bukan mode debug, Anda akan otomatis diarahkan ke link WhatsApp di atas.</p>";

        } else {
            echo "<li><font color='red'><strong>GAGAL:</strong></font> Fungsi `generateResetToken()` di User Model mengembalikan `false`.</li>";
            echo "</ul><hr>";
            echo "<h3><pre>-- PROSES DEBUG SELESAI --</pre></h3>";
            echo "<p><strong>Kemungkinan Penyebab:</strong> Email '<strong>" . htmlspecialchars($email) . "</strong>' tidak ditemukan di dalam tabel `pengguna` di database Anda.</p>";
        }
        
        // Hentikan eksekusi setelah menampilkan debug
        exit;
    }
    
    // ... (Semua metode lain seperti login, authenticate, dll. tetap sama) ...
    
    public function login() { require_once __DIR__ . '/../views/auth/login.php'; }
    public function authenticate() { /* ... */ }
    public function logout() { /* ... */ }
    public function register() { /* ... */ }
    public function register_dokter() { /* ... */ }
    public function processRegister() { /* ... */ }
    public function processRegisterDokter() { /* ... */ }
    public function forgot_password() { require_once __DIR__ . '/../views/auth/forgot_password.php'; }
    public function reset_password() { /* ... */ }
    public function update_password() { /* ... */ }
    private function handleFileUpload($file, $uploadDir) { /* ... */ }
}
