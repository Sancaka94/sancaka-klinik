<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
require_once __DIR__ . '/../models/Pasien.php';

class ProfileController {

    public function pengaturan() {
        $this->checkLogin();
        $pasienModel = new Pasien();
        $pasien_data = $pasienModel->getPasienByPenggunaId($_SESSION['user']['id_pengguna']);
        if (!$pasien_data) {
            die("Error: Data pasien tidak ditemukan.");
        }
        require __DIR__ . '/../views/profile/pengaturan.php';
    }

    public function update() {
        $this->checkLogin();
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ?url=profile/pengaturan&error=Invalid request method.');
            exit;
        }

        $pasienModel = new Pasien();
        $id_pengguna = $_SESSION['user']['id_pengguna'];
        $current_data = $pasienModel->getPasienByPenggunaId($id_pengguna);
        $new_foto_profil = $current_data['foto_profil'] ?? null;

        // Logika untuk menangani upload file
        if (isset($_FILES['foto_profil']) && $_FILES['foto_profil']['error'] == 0) {
            $file = $_FILES['foto_profil'];
            $allowed_types = ['image/jpeg', 'image/png', 'image/gif'];
            $max_size = 1024 * 1024; // 1MB

            if (in_array($file['type'], $allowed_types) && $file['size'] <= $max_size) {
                $file_extension = pathinfo($file['name'], PATHINFO_EXTENSION);
                $new_foto_profil = 'user-' . $id_pengguna . '-' . time() . '.' . $file_extension;
                
                // **PERBAIKAN:** Path folder upload yang benar
                $upload_dir = __DIR__ . '/../uploads/profiles/';
                if (!is_dir($upload_dir)) {
                    mkdir($upload_dir, 0755, true);
                }
                
                if (move_uploaded_file($file['tmp_name'], $upload_dir . $new_foto_profil)) {
                    if (!empty($current_data['foto_profil']) && file_exists($upload_dir . $current_data['foto_profil'])) {
                        @unlink($upload_dir . $current_data['foto_profil']);
                    }
                } else {
                    header('Location: ?url=profile/pengaturan&error=Gagal memindahkan file yang diupload.');
                    exit;
                }
            } else {
                header('Location: ?url=profile/pengaturan&error=Tipe file tidak valid atau ukuran terlalu besar.');
                exit;
            }
        }

        // **PERBAIKAN:** Mengirim semua data dari form ke model
        $data = [
            'id_pengguna'   => $id_pengguna,
            'nama_lengkap'  => $_POST['nama_lengkap'] ?? '',
            'tanggal_lahir' => $_POST['tanggal_lahir'] ?? '',
            'jenis_kelamin' => $_POST['jenis_kelamin'] ?? '',
            'alamat'        => $_POST['alamat'] ?? '',
            'nomor_telepon' => $_POST['nomor_telepon'] ?? '',
            'foto_profil'   => $new_foto_profil
        ];

        if ($pasienModel->updateProfile($data)) {
            // Perbarui session dengan data baru
            $_SESSION['user']['nama_lengkap'] = $data['nama_lengkap'];
            $_SESSION['user']['foto_profil'] = $data['foto_profil'];
            header('Location: ?url=profile/pengaturan&success=Profil berhasil diperbarui.');
        } else {
            header('Location: ?url=profile/pengaturan&error=Gagal memperbarui profil di database.');
        }
        exit;
    }

    private function checkLogin() {
        if (!isset($_SESSION['user'])) {
            header('Location: ?url=auth/login&error=Anda harus login terlebih dahulu.');
            exit;
        }
    }
}
