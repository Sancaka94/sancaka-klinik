<?php
require_once __DIR__ . '/../config/database.php';

class Notifikasi {
    private $conn;

    public function __construct() {
        $database = new Database();
        $this->conn = $database->connect();
    }

    /**
     * Mengambil notifikasi yang belum dibaca untuk pengguna tertentu.
     * @param int $id_pengguna ID pengguna
     * @return array Daftar notifikasi
     */
    public function getUnreadByUserId($id_pengguna) {
        $query = "SELECT * FROM notifikasi WHERE id_pengguna = ? AND is_read = 0 ORDER BY created_at DESC LIMIT 5";
        
        $stmt = $this->conn->prepare($query);
        if ($stmt === false) return [];

        $stmt->bind_param("i", $id_pengguna);
        $stmt->execute();
        $result = $stmt->get_result();

        $notifikasi = [];
        if ($result) {
            while ($row = $result->fetch_assoc()) {
                $notifikasi[] = $row;
            }
        }
        return $notifikasi;
    }

    /**
     * Membuat notifikasi baru.
     * @param int $id_pengguna ID pengguna penerima
     * @param string $pesan Isi pesan notifikasi
     * @param string|null $link URL tujuan saat notifikasi diklik
     * @return bool True jika berhasil, false jika gagal
     */
    public function create($id_pengguna, $pesan, $link = null) {
        $query = "INSERT INTO notifikasi (id_pengguna, pesan, link) VALUES (?, ?, ?)";
        
        $stmt = $this->conn->prepare($query);
        if ($stmt === false) return false;

        $stmt->bind_param("iss", $id_pengguna, $pesan, $link);
        return $stmt->execute();
    }
}
