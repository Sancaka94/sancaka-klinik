<?php
require_once __DIR__ . '/../models/User.php';

class AuthController {

    // ... (method login, register, authenticate, logout tetap sama) ...
    public function login() {
        require __DIR__ . '/../views/auth/login.php';
    }
    public function register() {
        require __DIR__ . '/../views/auth/register.php';
    }
    public function authenticate() {
        // Logika otentikasi yang sudah ada
        $userModel = new User();
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
            switch ($user['id_peran']) {
                case 1: header("Location: ?url=dashboard/superadmin"); break;
                case 2: header("Location: ?url=dashboard/admin"); break;
                case 3: header("Location: ?url=dashboard/dokter"); break;
                case 4: header("Location: ?url=dashboard/pasien"); break;
                case 5: header("Location: ?url=dashboard/owner"); break;
                default: echo "Role tidak dikenali.";
            }
        } else {
            header("Location: ?url=auth/login&error=Username, password, atau peran yang Anda pilih salah.");
        }
        exit;
    }
    public function logout() {
        if (session_status() == PHP_SESSION_NONE) session_start();
        session_destroy();
        header("Location: ?url=home");
        exit;
    }


    /**
     * Memproses semua data dari form registrasi multi-langkah.
     */
    public function processRegister() {
        // Validasi dasar
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header("Location: ?url=auth/register&error=Invalid request.");
            exit;
        }

        $userModel = new User();
        
        // Mengumpulkan semua data dari $_POST
        $data = [
            'email'             => $_POST['email'] ?? '',
            'nomor_telepon'     => $_POST['nomor_telepon'] ?? '',
            'password'          => $_POST['password'] ?? '',
            'nama_lengkap'      => $_POST['nama_lengkap'] ?? '',
            'tanggal_lahir'     => $_POST['tanggal_lahir'] ?? '',
            'usia'              => filter_var($_POST['usia'], FILTER_SANITIZE_NUMBER_INT) ?: null,
            'jenis_kelamin'     => $_POST['jenis_kelamin'] ?? '',
            'alamat'            => $_POST['alamat'] ?? '',
            'berat_badan'       => $_POST['berat_badan'] ?? null,
            'tinggi_badan'      => $_POST['tinggi_badan'] ?? null,
            'keluhan'           => $_POST['keluhan'] ?? '',
            'sejak_kapan_sakit' => $_POST['sejak_kapan_sakit'] ?? null,
            'poli_tujuan'       => $_POST['poli_tujuan'] ?? '',
            'penanggung_jawab'  => $_POST['penanggung_jawab'] ?? '',
            'foto_profil_file'  => $_FILES['foto_profil'] ?? null // Menyertakan file foto
        ];

        // Validasi email dan nomor telepon sebelum mencoba mendaftar
        if ($userModel->emailExists($data['email'])) {
            header("Location: ?url=auth/register&error=Email ini sudah digunakan.");
            exit;
        }
        if ($userModel->phoneExists($data['nomor_telepon'])) {
            header("Location: ?url=auth/register&error=Nomor telepon ini sudah digunakan.");
            exit;
        }

        // Panggil fungsi register di model dengan data yang sudah lengkap
        if ($userModel->register($data)) {
            header("Location: ?url=auth/login&success=Registrasi berhasil. Silakan login.");
        } else {
            header("Location: ?url=auth/register&error=Registrasi gagal karena kesalahan server.");
        }
        exit;
    }
}
