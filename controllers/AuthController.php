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
     * Memproses data login dari form dengan penanganan error yang lebih baik.
     */
    public function authenticate() {
        $userModel = new User($this->conn);
        $username = $_POST['username'] ?? '';
        $password = $_POST['password'] ?? '';
        $id_peran = $_POST['id_peran'] ?? '';

        if (empty($username) || empty($password) || empty($id_peran)) {
            header("Location: ?url=auth/login&error=Semua field harus diisi.");
            exit;
        }

        $loginResult = $userModel->login($username, $password, $id_peran);

        // PERBAIKAN: Periksa hasil dari proses login
        if (is_array($loginResult)) {
            // SUKSES: Hasilnya adalah array data pengguna
            $_SESSION['user'] = $loginResult;
            switch ($loginResult['id_peran']) {
                case 1: header("Location: ?url=dashboard/superadmin"); break;
                case 2: header("Location: ?url=dashboard/admin"); break;
                case 3: header("Location: ?url=dashboard/dokter"); break;
                case 4: header("Location: ?url=dashboard/pasien"); break;
                case 5: header("Location: ?url=dashboard/owner"); break;
                default: header("Location: ?url=home");
            }
        } else {
            // GAGAL: Tangani pesan error spesifik
            $errorMessage = "Terjadi kesalahan yang tidak diketahui.";
            if ($loginResult === 'WRONG_PASSWORD') {
                $errorMessage = "Password yang Anda masukkan salah. Silakan coba lagi.";
            } elseif ($loginResult === 'USER_NOT_FOUND') {
                $errorMessage = "Kombinasi email/username dan peran tidak ditemukan. Pastikan Anda memilih peran yang benar.";
            }
            header("Location: ?url=auth/login&error=" . urlencode($errorMessage));
        }
        exit;
    }
    
    // ... (Semua metode lain seperti login, logout, register, forgot_password, dll. tetap sama) ...

    public function login() { require_once __DIR__ . '/../views/auth/login.php'; }
    public function logout() { session_destroy(); header("Location: ?url=home"); exit; }
    public function register() { require_once __DIR__ . '/../views/auth/register.php'; }
    public function register_dokter() { require_once __DIR__ . '/../views/auth/register_dokter.php'; }
    public function processRegister() { /* ... Logika registrasi pasien ... */ }
    public function processRegisterDokter() { /* ... Logika registrasi dokter ... */ }
    public function forgot_password() { require_once __DIR__ . '/../views/auth/forgot_password.php'; }
    public function send_reset_link() { /* ... Logika kirim link reset ... */ }
    public function reset_password() { /* ... Logika tampilkan form reset ... */ }
    public function update_password() { /* ... Logika update password ... */ }
    private function handleFileUpload($file, $uploadDir) { /* ... */ }
}
