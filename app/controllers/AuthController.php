<?php
// File: app/controllers/AuthController.php

// Muat file-file yang diperlukan
require_once BASE_PATH . '/config/database.php';
require_once BASE_PATH . '/app/models/User.php';

class AuthController {
    private $userModel;

    public function __construct() {
        $database = new Database();
        $conn = $database->getConnection();
        $this->userModel = new User($conn);
    }

    /**
     * Menampilkan halaman/view form login.
     */
    public function login() {
        if (isset($_SESSION['user'])) {
            // Arahkan ke URL dashboard yang sesuai dengan peran
            $this->redirectToDashboard($_SESSION['user']['id_peran']);
        }
        require_once BASE_PATH . '/app/views/auth/login.php';
    }

    /**
     * Memproses data yang dikirim dari form login.
     */
    public function authenticate() {
        $username = $_POST['username'] ?? '';
        $password = $_POST['password'] ?? '';
        $id_peran = $_POST['id_peran'] ?? '';

        if (empty($username) || empty($password) || empty($id_peran)) {
            header("Location: /auth/login?error=Semua field harus diisi.");
            exit;
        }

        $loginResult = $this->userModel->login($username, $password, $id_peran);

        if (is_array($loginResult)) {
            $_SESSION['user'] = $loginResult;
            $this->redirectToDashboard($loginResult['id_peran']);
        } else {
            $errorMessage = "Terjadi kesalahan.";
            if ($loginResult === 'WRONG_PASSWORD') {
                $errorMessage = "Password yang Anda masukkan salah.";
            } elseif ($loginResult === 'USER_NOT_FOUND') {
                $errorMessage = "Kombinasi username/email dan peran tidak ditemukan.";
            } elseif ($loginResult === 'DB_ERROR') {
                $errorMessage = "Terjadi masalah koneksi ke server.";
            }
            header("Location: /auth/login?error=" . urlencode($errorMessage));
            exit;
        }
    }

    /**
     * Menghapus session dan logout pengguna.
     */
    public function logout() {
        session_destroy();
        header("Location: /"); // Kembali ke halaman utama
        exit;
    }

    // --- METODE REGISTRASI ---
    public function register() { require_once BASE_PATH . '/app/views/auth/register.php'; }
    public function processRegister() { /* Logika untuk memproses registrasi pasien baru ada di sini */ }

    // --- METODE LUPA PASSWORD ---
    public function forgot_password() {
        require_once BASE_PATH . '/app/views/auth/forgot_password.php';
    }

    public function send_reset_link() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') { exit('Invalid request'); }
        
        $email = $_POST['email'] ?? '';
        $token = $this->userModel->generateResetToken($email);

        if ($token) {
            // Di aplikasi nyata, link ini akan dikirim melalui email.
            // Untuk sekarang, kita bisa arahkan ke WhatsApp atau tampilkan linknya.
            $resetLink = "http://sancaka.biz.id/auth/reset_password?token=" . $token;
            $adminWhatsApp = "6285745808809";
            $message = "Permintaan reset password untuk email: " . $email . "\n\nSilakan klik link berikut:\n" . $resetLink;
            $waLink = "https://wa.me/" . $adminWhatsApp . "?text=" . urlencode($message);
            header("Location: " . $waLink);
        } else {
            header("Location: /auth/forgot_password?error=" . urlencode("Email tidak ditemukan di sistem kami."));
        }
        exit;
    }

    public function reset_password() {
        $token = $_GET['token'] ?? '';
        if (empty($token) || !$this->userModel->validateResetToken($token)) {
            die('Token tidak valid atau sudah kedaluwarsa.');
        }
        require_once BASE_PATH . '/app/views/auth/reset_password.php';
    }

    public function update_password() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') { exit('Invalid request'); }

        $token = $_POST['token'] ?? '';
        $password = $_POST['password'] ?? '';
        $confirmPassword = $_POST['confirm_password'] ?? '';

        if (empty($token) || empty($password) || $password !== $confirmPassword) {
            header("Location: /auth/reset_password?token=" . $token . "&error=" . urlencode("Password tidak cocok atau kosong."));
            exit;
        }

        if ($this->userModel->resetPassword($token, $password)) {
            header("Location: /auth/login?status=reset_success");
        } else {
            header("Location: /auth/reset_password?token=" . $token . "&error=" . urlencode("Gagal memperbarui password."));
        }
        exit;
    }
    
    // --- FUNGSI HELPER ---
    private function redirectToDashboard($id_peran) {
        switch ($id_peran) {
            case 1: header("Location: /superadmin/dashboard"); break;
            case 2: header("Location: /admin/dashboard"); break;
            case 3: header("Location: /dokter/dashboard"); break;
            case 4: header("Location: /pasien/dashboard"); break;
            case 5: header("Location: /owner/dashboard"); break;
            default: header("Location: /auth/login");
        }
        exit;
    }

    private function handleFileUpload($file, $uploadDir) {
        if ($file['error'] !== UPLOAD_ERR_OK) { return false; }

        // Path upload sekarang mengarah ke dalam folder 'public'
        $targetPath = BASE_PATH . '/public/' . $uploadDir;
        if (!file_exists($targetPath)) {
            mkdir($targetPath, 0777, true);
        }

        $fileExtension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        $newFileName = uniqid() . '.' . $fileExtension;
        $destination = $targetPath . $newFileName;

        $allowedTypes = ['jpg', 'jpeg', 'png', 'gif'];
        if (!in_array($fileExtension, $allowedTypes)) { return false; }

        $maxFileSize = 2 * 1024 * 1024; // 2 MB
        if ($file['size'] > $maxFileSize) { return false; }
        
        return move_uploaded_file($file['tmp_name'], $destination) ? $newFileName : false;
    }
}
