<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../models/JadwalDokter.php';
require_once __DIR__ . '/../models/JanjiTemu.php';
require_once __DIR__ . '/../models/Pasien.php';

class JanjitemuController {

    /**
     * Menampilkan halaman form untuk membuat janji temu baru.
     */
    public function buat() {
        $this->checkRole(4);
        $jadwalModel = new JadwalDokter();
        $daftar_dokter = $jadwalModel->getDokterTersedia();
        require __DIR__ . '/../views/janjitemu/buat.php';
    }

    /**
     * Memproses dan menyimpan data dari form pembuatan janji temu.
     */
    public function simpan() {
        $this->checkRole(4);

        // 1. Validasi input: sekarang memeriksa 'tanggal' dan 'waktu' yang terpisah
        if ($_SERVER['REQUEST_METHOD'] !== 'POST' || empty($_POST['id_staf_dokter']) || empty($_POST['tanggal']) || empty($_POST['waktu'])) {
            header('Location: ?url=janjitemu/buat&error=Data tidak lengkap. Harap isi semua field.');
            exit;
        }

        // 2. Siapkan semua model yang dibutuhkan
        $jadwalModel = new JadwalDokter();
        $janjiTemuModel = new JanjiTemu();
        $pasienModel = new Pasien();

        // 3. Ambil data dari form
        $id_staf_dokter = $_POST['id_staf_dokter'];
        $tanggal = $_POST['tanggal'];
        $waktu = $_POST['waktu'];
        $keluhan = $_POST['keluhan'];

        // 4. Gabungkan tanggal dan waktu menjadi satu string datetime standar
        $datetime_string = $tanggal . ' ' . $waktu;

        // 5. Dapatkan ID Pasien yang sedang login dari session
        $data_pasien = $pasienModel->getPasienByPenggunaId($_SESSION['user']['id_pengguna']);
        if (!$data_pasien) {
            header('Location: ?url=janjitemu/buat&error=Data pasien tidak ditemukan.');
            exit;
        }
        $id_pasien = $data_pasien['id_pasien'];

        // 6. Proses tanggal dan waktu dari input untuk mencari jadwal
        $timestamp = strtotime($datetime_string);
        $tanggal_janji_ymd = date('Y-m-d', $timestamp);
        $jam_janji = date('H:i:s', $timestamp);
        $hari_janji_en = date('l', $timestamp);
        
        // Konversi nama hari ke Bahasa Indonesia agar cocok dengan data di database
        $nama_hari_en = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
        $nama_hari_id = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
        $hari_janji_id = str_replace($nama_hari_en, $nama_hari_id, $hari_janji_en);

        // 7. Cari ID Jadwal yang valid berdasarkan dokter, hari, dan jam
        $id_jadwal = $jadwalModel->findJadwalId($id_staf_dokter, $hari_janji_id, $jam_janji);
        if ($id_jadwal === null) {
            header('Location: ?url=janjitemu/buat&error=Dokter tidak tersedia pada jadwal yang dipilih.');
            exit;
        }

        // 8. Buat nomor antrian baru
        $jumlah_antrian_sebelumnya = $janjiTemuModel->countAntrian($id_jadwal, $tanggal_janji_ymd);
        $nomor_antrian_baru = 'A-' . ($jumlah_antrian_sebelumnya + 1);

        // 9. Siapkan data lengkap untuk disimpan ke database
        $data_janji_baru = [
            'id_pasien'     => $id_pasien,
            'id_jadwal'     => $id_jadwal,
            'tanggal_janji' => $datetime_string, // Simpan tanggal dan waktu lengkap
            'nomor_antrian' => $nomor_antrian_baru,
            'status'        => 'Direncanakan',
            'keluhan'       => $keluhan
        ];

        // 10. Simpan ke database melalui model
        if ($janjiTemuModel->simpanJanjiBaru($data_janji_baru)) {
            // Jika berhasil, arahkan kembali ke dashboard dengan pesan sukses
            header('Location: ?url=dashboard/pasien&success=Janji temu berhasil dibuat.');
        } else {
            // Jika gagal, arahkan kembali ke form dengan pesan error
            header('Location: ?url=janjitemu/buat&error=Gagal menyimpan janji temu ke database.');
        }
        exit;
    }

    /**
     * Fungsi internal untuk memeriksa keamanan sesi dan peran.
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
}
