<?php
require_once __DIR__ . '/../models/User.php';

class AuthController {

    /**
     * Menampilkan halaman login.
     */
    public function login() {
        require __DIR__ . '/../views/auth/login.php';
    }

    /**
     * Menampilkan halaman registrasi.
     */
    public function register() {
        require __DIR__ . '/../views/auth/register.php';
    }

    /**
     * Memproses data dari form registrasi.
     */
    public function processRegister() {
        $userModel = new User();
        
        $email = $_POST['email'] ?? '';
        $telp = $_POST['nomor_telepon'] ?? '';
        $password = $_POST['password'] ?? '';

        if (empty($email) || empty($password)) {
            header("Location: ?url=auth/register&error=Email dan password tidak boleh kosong.");
            exit;
        }

        // Cek apakah email atau nomor telepon sudah ada
        if ($userModel->emailExists($email)) {
            header("Location: ?url=auth/register&error=Email ini sudah digunakan.");
            exit;
        }
        if ($userModel->phoneExists($telp)) {
            header("Location: ?url=auth/register&error=Nomor telepon ini sudah digunakan.");
            exit;
        }

        $data = [
            'email' => $email,
            'nomor_telepon' => $telp,
            'password' => $password
        ];

        if ($userModel->register($data)) {
            header("Location: ?url=auth/login&success=Registrasi berhasil. Silakan login.");
        } else {
            header("Location: ?url=auth/register&error=Registrasi gagal karena kesalahan server.");
        }
        exit;
    }

    /**
     * Mengotentikasi pengguna saat login.
     */
    public function authenticate() {
        $userModel = new User();
        $username = $_POST['username'] ?? '';
        $password = $_POST['password'] ?? '';

        $user = $userModel->login($username, $password);

        if ($user) {
            if (session_status() == PHP_SESSION_NONE) {
                session_start();
            }
            $_SESSION['user'] = $user;

            switch ($user['id_peran']) {
                case 1: header("Location: ?url=dashboard/superadmin"); break;
                case 2: header("Location: ?url=dashboard/admin"); break;
                case 3: header("Location: ?url=dashboard/dokter"); break;
                case 4: header("Location: ?url=dashboard/pasien"); break;
                default: echo "Role tidak dikenali.";
            }
        } else {
            header("Location: ?url=auth/login&error=Username atau password salah.");
        }
        exit;
    }

    /**
     * Mengeluarkan pengguna dari sistem (logout).
     */
    public function logout() {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        session_destroy();
        
        // **PERBAIKAN:** Mengarahkan ke halaman home setelah logout
        header("Location: ?url=home");
        exit;
    }
}
