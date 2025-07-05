<?php
// Memuat model-model yang diperlukan
require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../models/JanjiTemu.php';
require_once __DIR__ . '/../models/Notifikasi.php'; // Model baru untuk notifikasi

class DashboardController {

    public function __construct() {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
    }

    /**
     * Memeriksa apakah pengguna memiliki peran yang sesuai.
     */
    private function checkRole($requiredRoleId) {
        if (!isset($_SESSION['user'])) {
            header('Location: ?url=auth/login&error=Anda harus login terlebih dahulu.');
            exit;
        }
        if ($_SESSION['user']['id_peran'] != $requiredRoleId) {
            header('Location: ?url=auth/login&error=Anda tidak memiliki akses ke halaman ini.');
            exit;
        }
    }

    /**
     * Menampilkan halaman dasbor dokter (hanya kerangka).
     */
    public function dokter() {
        $this->checkRole(3); // Asumsi id_peran 3 adalah Dokter
        require __DIR__ . '/../views/dashboard/dokter.php';
    }

    /**
     * [DIPERBARUI] Menyediakan data untuk dasbor dokter, termasuk notifikasi.
     */
    public function api_dokter() {
        $this->checkRole(3);

        $id_dokter = $_SESSION['user']['id_pengguna'];

        $userModel = new User();
        $janjiTemuModel = new JanjiTemu();
        $notifikasiModel = new Notifikasi(); // Instance model notifikasi

        // Mengambil semua data yang diperlukan
        $info_dokter = $userModel->getPatientProfileById($id_dokter);
        $janji_temu_hari_ini = $janjiTemuModel->getAppointmentsForDoctorToday($id_dokter);
        $statistik = $janjiTemuModel->getAppointmentStatsForDoctorToday($id_dokter);
        $notifikasi = $notifikasiModel->getUnreadByUserId($id_dokter); // Ambil notifikasi

        // Menggabungkan semua data ke dalam satu array response
        $response_data = [
            'info_dokter' => $info_dokter,
            'janji_temu_hari_ini' => $janji_temu_hari_ini,
            'statistik' => $statistik,
            'notifikasi' => $notifikasi // Tambahkan data notifikasi ke response
        ];

        header('Content-Type: application/json');
        echo json_encode($response_data);
        exit;
    }

    /**
     * [FUNGSI BARU] Menampilkan halaman dasbor pasien (hanya kerangka).
     */
    public function pasien() {
        $this->checkRole(4); // Asumsi id_peran 4 adalah Pasien
        require __DIR__ . '/../views/dashboard/pasien.php';
    }

    /**
     * [ENDPOINT API BARU] Menyediakan data untuk dasbor pasien dalam format JSON.
     */
    public function api_pasien() {
        $this->checkRole(4);

        $id_pengguna = $_SESSION['user']['id_pengguna'];

        $userModel = new User();
        $janjiTemuModel = new JanjiTemu();
        $notifikasiModel = new Notifikasi();

        // Mengambil data profil pasien
        $info_pasien = $userModel->getPatientProfileById($id_pengguna);
        $janji_berikutnya = null;
        $riwayat_janji = [];

        if ($info_pasien) {
            $janji_berikutnya = $janjiTemuModel->getUpcomingAppointmentForPatient($info_pasien['id_pasien']);
            $riwayat_janji = $janjiTemuModel->getHistoryForPatient($info_pasien['id_pasien']);
        }
        
        $notifikasi = $notifikasiModel->getUnreadByUserId($id_pengguna);

        $response_data = [
            'info_pasien' => $info_pasien,
            'janji_berikutnya' => $janji_berikutnya,
            'riwayat_janji' => $riwayat_janji,
            'notifikasi' => $notifikasi
        ];

        header('Content-Type: application/json');
        echo json_encode($response_data);
        exit;
    }
}
