<?php
require_once __DIR__ . '/../models/User.php';

class AuthController {

    public function login() {
        // Arahkan ke view login yang baru dengan modal
        require __DIR__ . '/../views/auth/login.php';
    }

    public function register() {
        require __DIR__ . '/../views/auth/register.php';
    }

    public function processRegister() {
        // Logika processRegister tetap sama
        $userModel = new User();
        $email = $_POST['email'] ?? '';
        $telp = $_POST['nomor_telepon'] ?? '';
        $password = $_POST['password'] ?? '';
        if (empty($email) || empty($password)) {
            header("Location: ?url=auth/register&error=Email dan password tidak boleh kosong.");
            exit;
        }
        if ($userModel->emailExists($email)) {
            header("Location: ?url=auth/register&error=Email ini sudah digunakan.");
            exit;
        }
        if ($userModel->phoneExists($telp)) {
            header("Location: ?url=auth/register&error=Nomor telepon ini sudah digunakan.");
            exit;
        }
        $data = ['email' => $email, 'nomor_telepon' => $telp, 'password' => $password];
        if ($userModel->register($data)) {
            header("Location: ?url=auth/login&success=Registrasi berhasil. Silakan login.");
        } else {
            header("Location: ?url=auth/register&error=Registrasi gagal karena kesalahan server.");
        }
        exit;
    }

    /**
     * Mengotentikasi pengguna berdasarkan username, password, DAN PERAN.
     */
    public function authenticate() {
        $userModel = new User();
        
        // Ambil semua data dari form, termasuk peran
        $username = $_POST['username'] ?? '';
        $password = $_POST['password'] ?? '';
        $id_peran = $_POST['id_peran'] ?? '';

        // Validasi dasar
        if (empty($username) || empty($password) || empty($id_peran)) {
            header("Location: ?url=auth/login&error=Semua field harus diisi.");
            exit;
        }

        // Panggil fungsi login di model dengan menyertakan peran
        $user = $userModel->login($username, $password, $id_peran);

        if ($user) {
            if (session_status() == PHP_SESSION_NONE) {
                session_start();
            }
            $_SESSION['user'] = $user;

            // Arahkan ke dashboard yang sesuai
            switch ($user['id_peran']) {
                case 1: header("Location: ?url=dashboard/superadmin"); break;
                case 2: header("Location: ?url=dashboard/admin"); break;
                case 3: header("Location: ?url=dashboard/dokter"); break;
                case 4: header("Location: ?url=dashboard/pasien"); break;
                case 5: header("Location: ?url=dashboard/owner"); break; // Tambahkan rute untuk owner
                default: echo "Role tidak dikenali.";
            }
        } else {
            // Jika gagal, kembalikan ke halaman login dengan pesan error yang lebih spesifik
            header("Location: ?url=auth/login&error=Username, password, atau peran yang Anda pilih salah.");
        }
        exit;
    }

    public function logout() {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        session_destroy();
        header("Location: ?url=home");
        exit;
    }
}
