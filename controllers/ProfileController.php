<?php
// File: controllers/ProfileController.php

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../models/User.php'; // Model User mungkin akan dibutuhkan

class ProfileController {
    
    private $conn;

    /**
     * Constructor untuk membuat koneksi database sekali.
     */
    public function __construct() {
        $database = new Database();
        $this->conn = $database->conn;

        // Memulai session di awal agar tersedia untuk semua metode
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
    }

    /**
     * Menampilkan halaman pengaturan profil.
     */
    public function pengaturan() {
        // Pastikan pengguna sudah login sebelum mengakses halaman ini
        if (!isset($_SESSION['user'])) {
            header('Location: ?url=auth/login&error=Anda harus login untuk mengakses halaman ini.');
            exit;
        }

        // PERBAIKAN: Di sinilah error Anda terjadi sebelumnya.
        // Kita tidak perlu membuat model User baru di sini,
        // karena data user sudah ada di dalam $_SESSION.
        $user = $_SESSION['user'];

        // Memuat view yang menampilkan form pengaturan
        require_once __DIR__ . '/../views/profile/pengaturan.php';
    }

    /**
     * Memproses pembaruan data profil dari form.
     */
    public function update() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header("Location: ?url=profile/pengaturan");
            exit;
        }

        if (!isset($_SESSION['user'])) {
            header('Location: ?url=auth/login');
            exit;
        }

        // Membuat instance model User dengan koneksi database
        // Kita membutuhkannya untuk memanggil fungsi update di model
        $userModel = new User($this->conn);

        // Handle upload file foto profil baru jika ada
        $newProfilePhoto = $this->handleFileUpload($_FILES['foto_profil'] ?? null, 'uploads/profiles/');

        // Siapkan data untuk di-update
        $data = [
            'id_pengguna'  => $_SESSION['user']['id_pengguna'],
            'nama_lengkap' => $_POST['nama_lengkap'] ?? $_SESSION['user']['nama_lengkap'],
            'email'        => $_POST['email'] ?? $_SESSION['user']['email'],
            'password'     => $_POST['password'] ?? '', // Hanya di-update jika diisi
            'foto_profil'  => $newProfilePhoto ?? $_SESSION['user']['foto_profil']
        ];

        // Panggil metode update dari model
        if ($userModel->updateProfile($data)) {
            // Jika berhasil, perbarui juga data di session
            $_SESSION['user']['nama_lengkap'] = $data['nama_lengkap'];
            $_SESSION['user']['email'] = $data['email'];
            if ($newProfilePhoto) {
                $_SESSION['user']['foto_profil'] = $newProfilePhoto;
            }
            
            header("Location: ?url=profile/pengaturan&status=update_sukses");
        } else {
            header("Location: ?url=profile/pengaturan&error=Gagal memperbarui profil.");
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
