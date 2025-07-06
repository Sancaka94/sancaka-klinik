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
     * [DEEPER DEBUGGING] Memproses permintaan lupa password dengan output yang lebih detail.
     */
    public function send_reset_link() {
        echo "<b>DEBUGGING MODE AKTIF (LEVEL 2)</b><br><hr>";
        echo "DEBUG: Masuk ke metode send_reset_link.<br>";

        // PERBAIKAN: Cetak metode request dan seluruh isi $_POST
        echo "DEBUG: Metode Request Server: <b>" . $_SERVER['REQUEST_METHOD'] . "</b><br>";
        echo "DEBUG: Isi dari \$_POST:<br>";
        echo "<pre>";
        print_r($_POST);
        echo "</pre>";
        echo "<hr>";

        $email = $_POST['email'] ?? '';
        if (empty($email)) {
            die("<b>ANALISIS:</b> Variabel \$_POST['email'] kosong atau tidak ada. Proses dihentikan.");
        }
        
        // Kode di bawah ini hanya akan berjalan jika email diterima
        echo "DEBUG: Email yang diterima dari form: <b>" . htmlspecialchars($email) . "</b><br>";

        $userModel = new User($this->conn);
        echo "DEBUG: Mencoba membuat token untuk email...<br>";
        
        $token = $userModel->generateResetToken($email);

        if ($token) {
            echo "DEBUG: SUKSES - Token berhasil dibuat: " . htmlspecialchars($token) . "<br>";
            
            $resetLink = "https://sancaka.biz.id/apps/klinik-app/?url=auth/reset_password&token=" . $token;
            $adminWhatsApp = "6285745808809";
            
            $message = "Permintaan reset password untuk email: " . $email . "\n\n";
            $message .= "Silakan klik link berikut untuk melanjutkan:\n";
            $message .= $resetLink;

            $waLink = "https://wa.me/" . $adminWhatsApp . "?text=" . urlencode($message);

            echo "DEBUG: Link WhatsApp yang akan diarahkan: <a href='" . $waLink . "'>" . $waLink . "</a><br>";
            echo "DEBUG: Proses selesai.";
            die(); 

        } else {
            die("DEBUG: GAGAL - Email '" . htmlspecialchars($email) . "' tidak ditemukan di database.");
        }
        exit;
    }
    
    // ... (Semua metode lain seperti login, authenticate, dll. tetap sama) ...
    
    public function login() { /* ... */ }
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
