<?php
// File: controllers/ProfileController.php

// Aktifkan error reporting untuk debugging
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Mulai session jika belum ada
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Memuat file-file yang diperlukan
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../models/User.php';

class ProfileController {
    private $conn;
    private $userModel;

    /**
     * Constructor untuk ProfileController.
     * Membuat koneksi database dan instance model.
     */
    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
        $this->userModel = new User($this->conn);
    }

    /**
     * Menampilkan halaman pengaturan profil.
     */
    public function pengaturan() {
        if (!isset($_SESSION['user']['id_pengguna'])) {
            header("Location: ?url=auth/login&error=Anda harus login untuk mengakses halaman ini.");
            exit;
        }

        $id_pengguna = $_SESSION['user']['id_pengguna'];
        
        // Ambil data profil terbaru dari database untuk ditampilkan di form
        $data_profil = $this->userModel->find($id_pengguna);

        // Jika data tidak ditemukan (misal: user dihapus saat session masih aktif)
        if (!$data_profil) {
             session_destroy();
             header("Location: ?url=auth/login&error=Sesi tidak valid.");
             exit;
        }

        // Muat halaman view untuk pengaturan. Data profil akan tersedia di dalam view.
        require_once __DIR__ . '/../views/profile/pengaturan.php';
    }

    /**
     * Memproses pembaruan data profil.
     */
    public function update() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            exit('Invalid request');
        }

        if (!isset($_SESSION['user']['id_pengguna'])) {
            header("Location: ?url=auth/login");
            exit;
        }

        // Kumpulkan data dari form ke dalam sebuah array
        $data = [
            'id_pengguna' => $_SESSION['user']['id_pengguna'],
            'nama_lengkap' => $_POST['nama_lengkap'] ?? '',
            'email' => $_POST['email'] ?? '',
            'no_telepon' => $_POST['no_telepon'] ?? null,
            'alamat' => $_POST['alamat'] ?? null,
            'foto' => $_SESSION['user']['foto'] ?? null // Ambil foto lama sebagai default
        ];

        // Proses unggah foto profil baru jika ada
        if (isset($_FILES['foto']) && $_FILES['foto']['error'] == UPLOAD_ERR_OK) {
            $namaFileFoto = $this->handleFileUpload($_FILES['foto'], 'uploads/profil/');

            if ($namaFileFoto) {
                // Jika unggah berhasil, perbarui nama file foto di data
                $data['foto'] = $namaFileFoto;
            } else {
                // Gagal unggah, kembali dengan pesan error
                header("Location: ?url=profile/pengaturan&error=" . urlencode("Gagal mengunggah foto. Pastikan format (jpg, png) dan ukuran (< 2MB) sesuai."));
                exit;
            }
        }

        // Panggil method di model untuk update data ke database
        $isSuccess = $this->userModel->updateProfile($data);

        // Arahkan kembali ke halaman pengaturan dengan pesan status
        if ($isSuccess) {
            // Perbarui juga data di session agar langsung berubah di tampilan
            $_SESSION['user']['nama_lengkap'] = $data['nama_lengkap'];
            $_SESSION['user']['email'] = $data['email'];
            $_SESSION['user']['foto'] = $data['foto'];
            header("Location: ?url=profile/pengaturan&status=update_success");
        } else {
            header("Location: ?url=profile/pengaturan&error=" . urlencode("Gagal memperbarui profil. Email mungkin sudah digunakan oleh pengguna lain."));
        }
        exit;
    }

    /**
     * [BARU] Memproses pembaruan password.
     */
    public function updatePassword() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            exit('Invalid request');
        }

        if (!isset($_SESSION['user']['id_pengguna'])) {
            header("Location: ?url=auth/login");
            exit;
        }
        
        // Ambil data dari form
        $id_pengguna = $_SESSION['user']['id_pengguna'];
        $old_password = $_POST['old_password'] ?? '';
        $new_password = $_POST['new_password'] ?? '';
        $confirm_password = $_POST['confirm_password'] ?? '';

        // Validasi dasar
        if (empty($old_password) || empty($new_password) || empty($confirm_password)) {
            header("Location: ?url=profile/pengaturan&error_pass=" . urlencode("Semua field password harus diisi."));
            exit;
        }

        if (strlen($new_password) < 8) {
            header("Location: ?url=profile/pengaturan&error_pass=" . urlencode("Password baru minimal harus 8 karakter."));
            exit;
        }

        if ($new_password !== $confirm_password) {
            header("Location: ?url=profile/pengaturan&error_pass=" . urlencode("Password baru dan konfirmasi tidak cocok."));
            exit;
        }

        // Panggil method di model untuk mengubah password
        $result = $this->userModel->changePassword($id_pengguna, $old_password, $new_password);

        // Arahkan kembali dengan pesan yang sesuai
        switch ($result) {
            case 'SUCCESS':
                header("Location: ?url=profile/pengaturan&status_pass=update_success");
                break;
            case 'WRONG_PASSWORD':
                header("Location: ?url=profile/pengaturan&error_pass=" . urlencode("Password lama yang Anda masukkan salah."));
                break;
            default:
                header("Location: ?url=profile/pengaturan&error_pass=" . urlencode("Terjadi kesalahan saat memperbarui password."));
                break;
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
        if ($file['error'] !== UPLOAD_ERR_OK) {
            return false;
        }

        $targetPath = __DIR__ . '/../../' . $uploadDir;
        if (!file_exists($targetPath)) {
            mkdir($targetPath, 0777, true);
        }

        $fileExtension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        $newFileName = uniqid('profil_') . '.' . $fileExtension;
        $destination = $targetPath . $newFileName;

        $allowedTypes = ['jpg', 'jpeg', 'png', 'gif'];
        if (!in_array($fileExtension, $allowedTypes)) {
            return false;
        }

        $maxFileSize = 2 * 1024 * 1024; // 2 MB
        if ($file['size'] > $maxFileSize) {
            return false;
        }

        if (move_uploaded_file($file['tmp_name'], $destination)) {
            return $newFileName;
        } else {
            return false;
        }
    }
}
?>
