<?php
require_once __DIR__ . '/../config/database.php';

class Notifikasi {
    private $conn;

    public function __construct() {
        $db = new Database();
        $this->conn = $db->connect();
    }

    /**
     * Mengambil semua notifikasi untuk pengguna tertentu.
     * Dibuat kompatibel dengan server yang tidak memiliki mysqlnd.
     * @param int $id_pengguna ID dari pengguna yang sedang login.
     * @return array Daftar notifikasi.
     */
    public function getNotifikasiByPenggunaId($id_pengguna) {
        $query = "SELECT id_notifikasi, id_pengguna, judul, pesan, link, sudah_dibaca, tanggal_dibuat FROM Notifikasi WHERE id_pengguna = ? ORDER BY tanggal_dibuat DESC";
        $stmt = $this->conn->prepare($query);
        if (!$stmt) {
            return [];
        }
        $stmt->bind_param("i", $id_pengguna);
        $stmt->execute();
        
        // **PERBAIKAN:** Menggunakan bind_result sebagai pengganti get_result()
        $stmt->bind_result($id_notifikasi, $id_pengguna_db, $judul, $pesan, $link, $sudah_dibaca, $tanggal_dibuat);
        
        $notifikasi = [];
        // Ambil data baris per baris
        while ($stmt->fetch()) {
            // Buat array secara manual
            $notifikasi[] = [
                'id_notifikasi' => $id_notifikasi,
                'id_pengguna'   => $id_pengguna_db,
                'judul'         => $judul,
                'pesan'         => $pesan,
                'link'          => $link,
                'sudah_dibaca'  => $sudah_dibaca,
                'tanggal_dibuat'=> $tanggal_dibuat
            ];
        }
        $stmt->close();
        return $notifikasi;
    }

    /**
     * Menghitung jumlah notifikasi yang belum dibaca.
     * @param int $id_pengguna ID dari pengguna.
     * @return int Jumlah notifikasi belum dibaca.
     */
    public function getUnreadCount($id_pengguna) {
        $query = "SELECT COUNT(id_notifikasi) as count FROM Notifikasi WHERE id_pengguna = ? AND sudah_dibaca = FALSE";
        $stmt = $this->conn->prepare($query);
        if (!$stmt) {
            return 0;
        }
        $stmt->bind_param("i", $id_pengguna);
        $stmt->execute();
        $stmt->bind_result($count);
        $stmt->fetch();
        $stmt->close();
        return $count;
    }
}
