<?php
require_once __DIR__ . '/../models/User.php';

class AuthController {

    // --- Metode yang sudah ada ---
    public function login() {
        require __DIR__ . '/../views/auth/login.php';
    }

    public function authenticate() {
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
            // Arahkan ke dasbor yang sesuai
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

    public function logout() {
        if (session_status() == PHP_SESSION_NONE) session_start();
        session_destroy();
        header("Location: ?url=home");
        exit;
    }

    public function register() {
        require __DIR__ . '/../views/auth/register.php';
    }

    public function processRegister() {
        // ... (Logika untuk registrasi pasien)
    }

    // --- [FUNGSI BARU] Untuk Pendaftaran Dokter ---

    /**
     * Menampilkan halaman form pendaftaran dokter.
     */
    public function register_dokter() {
        // Pastikan file view ini ada: views/auth/register_dokter.php
        $view_file = __DIR__ . '/../views/auth/register_dokter.php';
        if (file_exists($view_file)) {
            require $view_file;
        } else {
            die("Error: File view untuk pendaftaran dokter tidak ditemukan.");
        }
    }

    /**
     * Memproses data dari form pendaftaran dokter.
     */
    public function processRegisterDokter() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header("Location: ?url=auth/register_dokter");
            exit;
        }

        $fotoProfilName = $this->handleFileUpload($_FILES['foto_profil'] ?? null, 'uploads/profil/');

        $data = [
            'nama_lengkap'  => $_POST['nama_lengkap'] ?? '',
            'email'         => $_POST['email'] ?? '',
            'password'      => $_POST['password'] ?? '',
            'spesialisasi'  => $_POST['spesialisasi'] ?? 'Dokter Umum',
            'nomor_str'     => $_POST['nomor_str'] ?? '',
            'foto_profil'   => $fotoProfilName,
            'id_peran'      => 3 // ID Peran untuk Dokter
        ];

        $userModel = new User();

        if ($userModel->emailExists($data['email'])) {
            header("Location: ?url=auth/register_dokter&error=Email sudah terdaftar.");
            exit;
        }

        if ($userModel->registerDokter($data)) {
            header("Location: ?url=auth/login&status=registrasi_sukses");
        } else {
            header("Location: ?url=auth/register_dokter&error=Registrasi gagal.");
        }
        exit;
    }

    // Fungsi helper untuk upload file
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
