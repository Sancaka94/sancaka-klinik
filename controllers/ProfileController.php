<?php
require_once __DIR__ . '/../models/User.php';

class ProfileController {

    public function __construct() {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
    }
    
    public function index() {
        $this->pengaturan();
    }

    public function pengaturan() {
        if (!isset($_SESSION['user'])) {
            header("Location: ?url=auth/login&error=Anda harus login untuk mengakses halaman ini.");
            exit;
        }
        $userModel = new User();
        $id_pengguna = $_SESSION['user']['id_pengguna'];
        $data_pasien = $userModel->getPatientProfileById($id_pengguna);
        if ($data_pasien) {
            require __DIR__ . '/../views/profile/pengaturan.php';
        } else {
            die("Error: Data pasien tidak ditemukan.");
        }
    }
    
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

    public function update() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header("Location: ?url=profile/pengaturan");
            exit;
        }
        if (!isset($_SESSION['user'])) {
            header("Location: ?url=auth/login&error=Sesi Anda telah berakhir.");
            exit;
        }
        
        $data = $_POST;
        
        $newPhotoName = $this->handleFileUpload($_FILES['foto_profil'] ?? null, 'uploads/profil/');
        
        if ($newPhotoName) {
            $data['foto_profil'] = $newPhotoName;
            $oldPhoto = $_POST['foto_profil_lama'];
            if (!empty($oldPhoto) && file_exists('uploads/profil/' . $oldPhoto)) {
                unlink('uploads/profil/' . $oldPhoto);
            }
        } else {
            $data['foto_profil'] = $_POST['foto_profil_lama'];
        }

        $userModel = new User();
        if ($userModel->updatePatientProfile($data)) {
            header("Location: ?url=profile/pengaturan&status=sukses");
        } else {
            header("Location: ?url=profile/pengaturan&status=gagal");
        }
        exit;
    }
}
