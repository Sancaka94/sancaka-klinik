<?php
// Aktifkan error reporting untuk debugging
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
     * Membuat koneksi database sekali saat controller dibuat.
     */
    public function __construct() {
        $database = new Database();
        $this->conn = $database->conn;
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

        $user = $userModel->login($username, $password, $id_peran);

        if ($user) {
            if (session_status() == PHP_SESSION_NONE) session_start();
            $_SESSION['user'] = $user;
            
            // Arahkan ke dasbor yang sesuai dengan peran
            switch ($user['id_peran']) {
                case 1: header("Location: ?url=dashboard/superadmin"); break;
                case 2: header("Location: ?url=dashboard/admin"); break;
                case 3: header("Location: ?url=dashboard/dokter"); break;
                case 4: header("Location: ?url=dashboard/pasien"); break;
                case 5: header("Location: ?url=dashboard/owner"); break;
                default: header("Location: ?url=home");
            }
        } else {
            header("Location: ?url=auth/login&error=Username, password, atau peran yang Anda pilih salah.");
        }
        exit;
    }

    /**
     * Menghapus session dan logout pengguna.
     */
    public function logout() {
        if (session_status() == PHP_SESSION_NONE) session_start();
        session_destroy();
        header("Location: ?url=home");
        exit;
    }

    /**
     * Menampilkan halaman form pendaftaran untuk pasien.
     */
    public function register() {
        require_once __DIR__ . '/../views/auth/register.php';
    }

    /**
     * Memproses data dari form pendaftaran pasien.
     */
    public function processRegister() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header("Location: ?url=auth/register");
            exit;
        }

        $userModel = new User($this->conn);
        $email = $_POST['email'] ?? '';

        if ($userModel->emailExists($email)) {
            header("Location: ?url=auth/register&error=Email sudah terdaftar.");
            exit;
        }

        $data = [
            'nama_lengkap' => $_POST['nama_lengkap'] ?? '',
            'email'        => $email,
            'password'     => $_POST['password'] ?? '',
            'id_peran'     => 4 // ID Peran untuk Pasien
        ];

        if ($userModel->register($data)) {
            header("Location: ?url=auth/login&status=registrasi_sukses");
        } else {
            header("Location: ?url=auth/register&error=Registrasi gagal.");
        }
        exit;
    }

    /**
     * Menampilkan halaman form pendaftaran untuk dokter.
     */
    public function register_dokter() {
        require_once __DIR__ . '/../views/auth/register_dokter.php';
    }

    /**
     * Memproses data dari form pendaftaran dokter.
     */
    public function processRegisterDokter() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header("Location: ?url=auth/register_dokter");
            exit;
        }

        $userModel = new User($this->conn);
        $email = $_POST['email'] ?? '';

        if ($userModel->emailExists($email)) {
            header("Location: ?url=auth/register_dokter&error=Email sudah terdaftar.");
            exit;
        }
        
        $fotoProfilName = $this->handleFileUpload($_FILES['foto_profil'] ?? null, 'uploads/profiles/');

        $data = [
            'nama_lengkap'  => $_POST['nama_lengkap'] ?? '',
            'email'         => $email,
            'password'      => $_POST['password'] ?? '',
            'spesialisasi'  => $_POST['spesialisasi'] ?? 'Dokter Umum',
            'nomor_str'     => $_POST['nomor_str'] ?? '',
            'foto_profil'   => $fotoProfilName,
            'id_peran'      => 3 // ID Peran untuk Dokter
        ];

        if ($userModel->registerDokter($data)) {
            header("Location: ?url=auth/login&status=registrasi_sukses");
        } else {
            header("Location: ?url=auth/register_dokter&error=Registrasi gagal.");
        }
        exit;
    }

    /**
     * Fungsi helper pribadi untuk menangani upload file.
     */
    private function handleFileUpload($file, $uploadDir) {
        if ($file === null || $file['error'] !== UPLOAD_ERR_OK) {
            return null;
        }
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }
        $fileExtension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        $uniqueFilename = uniqid('profile_', true) . '.' . $fileExtension;
        $targetPath = $uploadDir . $uniqueFilename;
        if (move_uploaded_file($file['tmp_name'], $targetPath)) {
            return $uniqueFilename;
        }
        return null;
    }
}
