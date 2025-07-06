<?php
// File: controllers/ProfileController.php

// Aktifkan error reporting untuk debugging
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Memuat file-file yang diperlukan
require_once BASE_PATH . '/../config/database.php';
require_once BASE_PATH . '/../models/User.php';

class ProfileController {
    private $conn;
    private $userModel;

    public function __construct() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
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
        $data_profil = $this->userModel->find($id_pengguna);
        if (!$data_profil) {
             session_destroy();
             header("Location: ?url=auth/login&error=Sesi tidak valid.");
             exit;
        }
        require_once BASE_PATH . '/../views/profile/pengaturan.php';
    }

    /**
     * Memproses pembaruan data profil dengan data lengkap.
     */
    public function update() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            exit('Invalid request');
        }

        if (!isset($_SESSION['user']['id_pengguna'])) {
            header("Location: ?url=auth/login");
            exit;
        }

        // Kumpulkan semua data dari form ke dalam sebuah array
        $data = [
            'id_pengguna' => $_SESSION['user']['id_pengguna'],
            'nama_lengkap' => $_POST['nama_lengkap'] ?? '',
            'email' => $_POST['email'] ?? '',
            'no_telepon' => $_POST['no_telepon'] ?? null,
            'alamat' => $_POST['alamat'] ?? null,
            'foto' => $_SESSION['user']['foto'] ?? null,
            // Data Dokter
            'spesialisasi' => $_POST['spesialisasi'] ?? null,
            'nomor_str' => $_POST['nomor_str'] ?? null,
            // Data Pasien
            'tanggal_lahir' => $_POST['tanggal_lahir'] ?? null,
            'jenis_kelamin' => $_POST['jenis_kelamin'] ?? null,
            'no_ktp' => $_POST['no_ktp'] ?? null,
            'no_bpjs' => $_POST['no_bpjs'] ?? null
        ];

        // Proses unggah foto profil baru jika ada
        if (isset($_FILES['foto']) && $_FILES['foto']['error'] == UPLOAD_ERR_OK) {
            $namaFileFoto = $this->handleFileUpload($_FILES['foto'], 'uploads/profil/');
            if ($namaFileFoto) {
                $data['foto'] = $namaFileFoto;
            } else {
                header("Location: ?url=profile/pengaturan&error=" . urlencode("Gagal mengunggah foto. Pastikan format (jpg, png) dan ukuran (< 2MB) sesuai."));
                exit;
            }
        }

        $isSuccess = $this->userModel->updateProfile($data);

        if ($isSuccess) {
            // Perbarui juga data di session agar langsung berubah di tampilan
            $_SESSION['user']['nama_lengkap'] = $data['nama_lengkap'];
            $_SESSION['user']['email'] = $data['email'];
            $_SESSION['user']['foto'] = $data['foto'];
            header("Location: ?url=profile/pengaturan&status=update_success");
        } else {
            header("Location: ?url=profile/pengaturan&error=" . urlencode("Gagal memperbarui profil. Email mungkin sudah digunakan."));
        }
        exit;
    }

    /**
     * Memproses pembaruan password.
     */
    public function updatePassword() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') { exit('Invalid request'); }
        if (!isset($_SESSION['user']['id_pengguna'])) { header("Location: ?url=auth/login"); exit; }
        
        $id_pengguna = $_SESSION['user']['id_pengguna'];
        $old_password = $_POST['old_password'] ?? '';
        $new_password = $_POST['new_password'] ?? '';
        $confirm_password = $_POST['confirm_password'] ?? '';

        if (empty($old_password) || empty($new_password) || strlen($new_password) < 8 || $new_password !== $confirm_password) {
            header("Location: ?url=profile/pengaturan&error_pass=" . urlencode("Input password tidak valid atau tidak cocok."));
            exit;
        }
        
        $result = $this->userModel->changePassword($id_pengguna, $old_password, $new_password);
        
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
     */
    private function handleFileUpload($file, $uploadDir) {
        if ($file['error'] !== UPLOAD_ERR_OK) { return false; }
        
        $targetPath = __DIR__ . '/../../' . $uploadDir;
        if (!file_exists($targetPath)) {
            mkdir($targetPath, 0777, true);
        }
        
        $fileExtension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        $newFileName = uniqid('profil_') . '.' . $fileExtension;
        $destination = $targetPath . $newFileName;
        
        $allowedTypes = ['jpg', 'jpeg', 'png', 'gif'];
        if (!in_array($fileExtension, $allowedTypes)) { return false; }
        
        $maxFileSize = 2 * 1024 * 1024; // 2 MB
        if ($file['size'] > $maxFileSize) { return false; }
        
        return move_uploaded_file($file['tmp_name'], $destination) ? $newFileName : false;
    }
}
?>
