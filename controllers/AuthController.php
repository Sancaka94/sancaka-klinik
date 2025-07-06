<?php
// Aktifkan error reporting untuk debugging selama development
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Mulai session jika belum ada. Ini harus dilakukan di awal.
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Memuat file-file yang diperlukan
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../models/User.php';

class AuthController {
    private $conn;

    /**
     * Constructor untuk AuthController.
     * Membuat koneksi database sekali saat controller dibuat.
     */
    public function __construct() {
        // Membuat instance dari class Database
        $database = new Database();
        // Mengambil koneksi yang aktif dan menyimpannya di properti controller
        $this->conn = $database->getConnection();
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

    // --- METODE LUPA PASSWORD ---

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
    
    /**
     * Fungsi helper untuk menangani unggahan file.
     * @param array $file Data file dari $_FILES.
     * @param string $uploadDir Direktori tujuan untuk menyimpan file.
     * @return string|false Nama file yang baru jika berhasil, false jika gagal.
     */
    private function handleFileUpload($file, $uploadDir) {
        // Cek apakah ada error saat unggah
        if ($file['error'] !== UPLOAD_ERR_OK) {
            // Anda bisa menambahkan penanganan error yang lebih spesifik di sini
            return false;
        }

        // Pastikan direktori tujuan ada, jika tidak, coba buat.
        // __DIR__ . '/../..' akan mengarah ke root folder proyek (public_html/apps/klinik-app)
        $targetPath = __DIR__ . '/../../' . $uploadDir;
        if (!file_exists($targetPath)) {
            mkdir($targetPath, 0777, true);
        }

        // Ambil ekstensi file
        $fileExtension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        
        // Buat nama file yang unik untuk menghindari penimpaan file
        $newFileName = uniqid() . '.' . $fileExtension;
        $destination = $targetPath . $newFileName;

        // Validasi tipe file yang diizinkan (misal: hanya gambar)
        $allowedTypes = ['jpg', 'jpeg', 'png', 'gif'];
        if (!in_array($fileExtension, $allowedTypes)) {
            // Tipe file tidak diizinkan
            return false;
        }

        // Validasi ukuran file (misal: maks 2MB)
        $maxFileSize = 2 * 1024 * 1024; // 2 Megabytes
        if ($file['size'] > $maxFileSize) {
            // Ukuran file terlalu besar
            return false;
        }

        // Pindahkan file dari lokasi sementara ke direktori tujuan
        if (move_uploaded_file($file['tmp_name'], $destination)) {
            // Jika berhasil, kembalikan nama file yang baru
            return $newFileName;
        } else {
            // Gagal memindahkan file
            return false;
        }
    }
}
?>
