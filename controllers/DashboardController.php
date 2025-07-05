<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../models/Pasien.php';
require_once __DIR__ . '/../models/JanjiTemu.php';
require_once __DIR__ . '/../models/Notifikasi.php'; // Memuat model notifikasi

class DashboardController {

    /**
     * Menyiapkan dan menampilkan halaman dashboard pasien (pemuatan awal).
     */
    public function pasien() {
        $this->checkRole(4);
        
        // Data awal yang dibutuhkan oleh view
        $notifikasiModel = new Notifikasi();
        $unread_notifications = $notifikasiModel->getUnreadCount($_SESSION['user']['id_pengguna']);
        
        // Memuat file view. Data janji temu akan dimuat oleh JavaScript.
        require __DIR__ . '/../views/dashboard/pasien.php';
    }

    /**
     * **ENDPOINT BARU:** Menyediakan data dashboard dalam format JSON untuk JavaScript.
     */
    public function api() {
        $this->checkRole(4);
        
        // Siapkan model
        $pasienModel = new Pasien();
        $janjiTemuModel = new JanjiTemu();
        $notifikasiModel = new Notifikasi();
        
        // Ambil data pasien
        $pasien_data = $pasienModel->getPasienByPenggunaId($_SESSION['user']['id_pengguna']);
        
        $response_data = [
            'riwayat_janji_temu' => [],
            'janji_berikutnya' => null,
            'rekam_medis_terakhir' => null,
            'unread_notifications' => 0
        ];

        if ($pasien_data) {
            // Ambil data janji temu dan notifikasi
            $riwayat = $janjiTemuModel->getRiwayatByPasienId($pasien_data['id_pasien']);
            $unread_count = $notifikasiModel->getUnreadCount($_SESSION['user']['id_pengguna']);
            
            $response_data['riwayat_janji_temu'] = $riwayat;
            $response_data['unread_notifications'] = $unread_count;

            // Proses data untuk menemukan janji berikutnya dan rekam medis terakhir
            foreach (array_reverse($riwayat) as $janji) {
                if ($janji['status'] == 'Direncanakan' && strtotime($janji['tanggal_booking']) >= time()) {
                    $response_data['janji_berikutnya'] = $janji;
                    break;
                }
            }
            foreach ($riwayat as $janji) {
                if ($janji['status'] == 'Selesai') {
                    $response_data['rekam_medis_terakhir'] = $janji;
                    break;
                }
            }
        }
        
        // Atur header sebagai JSON dan kirimkan data
        header('Content-Type: application/json');
        echo json_encode($response_data);
        exit;
    }

    // ... (fungsi dokter, admin, dll. tetap sama)
    public function dokter() { $this->checkRole(3); require __DIR__ . '/../views/dashboard/dokter.php'; }
    public function admin() { $this->checkRole(2); require __DIR__ . '/../views/dashboard/admin.php'; }
    public function superadmin() { $this->checkRole(1); require __DIR__ . '/../views/dashboard/superadmin.php'; }

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
}
