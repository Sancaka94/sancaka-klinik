<?php
// Aktifkan error reporting untuk debugging selama development
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// File: app/controllers/AuthController.php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../models/User.php';

class AuthController {
    private $conn;

    /**
     * Constructor untuk AuthController.
     * Membuat koneksi database sekali dan memulai session.
     */
    public function __construct() {
        $database = new Database();
        $this->conn = $database->conn;
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
    }

    /**
     * Menampilkan halaman form login.
     */
    public function login() {
        require_once __DIR__ . '/../views/auth/login.php';
    }

    /**
     * Memproses data login dari form.
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

        if (is_array($loginResult)) {
            // SUKSES
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
            // GAGAL
            $errorMessage = "Terjadi kesalahan yang tidak diketahui.";
            if ($loginResult === 'WRONG_PASSWORD') {
                $errorMessage = "Password yang Anda masukkan salah. Silakan coba lagi.";
            } elseif ($loginResult === 'USER_NOT_FOUND') {
                $errorMessage = "Kombinasi email/username dan peran tidak ditemukan.";
            } elseif ($loginResult === 'DB_ERROR') {
                $errorMessage = "Terjadi masalah koneksi. Silakan hubungi administrator.";
            }
            header("Location: ?url=auth/login&error=" . urlencode($errorMessage));
        }
        exit;
    }

    /**
     * Menghapus session dan logout pengguna.
     */
    public function logout() {
        session_destroy();
        header("Location: ?url=home");
        exit;
    }

    // --- METODE REGISTRASI ---
    public function register() { require_once __DIR__ . '/../views/auth/register.php'; }
    public function register_dokter() { require_once __DIR__ . '/../views/auth/register_dokter.php'; }
    public function processRegister() { /* ... Logika registrasi pasien ... */ }
    public function processRegisterDokter() { /* ... Logika registrasi dokter ... */ }

    // --- METODE LUPA PASSWORD (Versi Final) ---

    /**
     * Menampilkan halaman form lupa password.
     */
    public function forgot_password() {
        require_once __DIR__ . '/../views/auth/forgot_password.php';
    }

    /**
     * Memproses permintaan lupa password dan mengarahkan ke WhatsApp.
     */
    public function send_reset_link() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header("Location: ?url=auth/forgot_password");
            exit;
        }

        $email = $_POST['email'] ?? '';
        $userModel = new User($this->conn);
        $token = $userModel->generateResetToken($email);

        if ($token) {
            $resetLink = "https://sancaka.biz.id/apps/klinik-app/?url=auth/reset_password&token=" . $token;
            $adminWhatsApp = "6285745808809";
            $message = "Permintaan reset password untuk email: " . $email . "\n\nSilakan klik link berikut untuk melanjutkan:\n" . $resetLink;
            $waLink = "https://wa.me/" . $adminWhatsApp . "?text=" . urlencode($message);
            header("Location: " . $waLink);
        } else {
            header("Location: ?url=auth/forgot_password&error=Email tidak ditemukan di sistem kami.");
        }
        exit;
    }

    /**
     * Menampilkan halaman untuk memasukkan password baru.
     */
    public function reset_password() {
        $token = $_GET['token'] ?? '';
        if (empty($token)) { die('Token tidak valid.'); }

        $userModel = new User($this->conn);
        if (!$userModel->validateResetToken($token)) {
            die('Token tidak valid atau sudah kedaluwarsa.');
        }
        require_once __DIR__ . '/../views/auth/reset_password.php';
    }

    /**
     * Memproses pembaruan password dari link reset.
     */
    public function update_password() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') { exit('Invalid request'); }

        $token = $_POST['token'] ?? '';
        $password = $_POST['password'] ?? '';
        $confirmPassword = $_POST['confirm_password'] ?? '';

        if (empty($token) || empty($password) || $password !== $confirmPassword) {
            header("Location: ?url=auth/reset_password&token=" . $token . "&error=Password tidak cocok atau kosong.");
            exit;
        }

        $userModel = new User($this->conn);
        if ($userModel->resetPassword($token, $password)) {
            header("Location: ?url=auth/login&status=reset_success");
        } else {
            header("Location: ?url=auth/reset_password&token=" . $token . "&error=Gagal memperbarui password.");
        }
        exit;
    }
    
    // ... (Fungsi helper seperti handleFileUpload tetap di sini) ...
    private function handleFileUpload($file, $uploadDir) { /* ... */ }
}
