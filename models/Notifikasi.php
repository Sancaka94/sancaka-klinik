<?php
// File: models/Notifikasi.php

class Notifikasi {
    private $conn;
    private $table_name = "notifikasi"; // Asumsi nama tabel notifikasi

    public function __construct($db) {
        $this->conn = $db;
    }

    /**
     * Mengambil notifikasi untuk pengguna tertentu.
     */
    public function getByUserId($id_pengguna) {
        try {
            $query = "SELECT * FROM " . $this->table_name . " WHERE id_pengguna = :id_pengguna ORDER BY created_at DESC LIMIT 15";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':id_pengguna', $id_pengguna, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error di Notifikasi->getByUserId(): " . $e->getMessage());
            return [];
        }
    }

    /**
     * Menandai notifikasi sebagai sudah dibaca.
     */
    public function markAsRead($id_notifikasi, $id_pengguna) {
        try {
            $query = "UPDATE " . $this->table_name . " SET is_read = 1 WHERE id_notifikasi = :id_notifikasi AND id_pengguna = :id_pengguna";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':id_notifikasi', $id_notifikasi, PDO::PARAM_INT);
            $stmt->bindParam(':id_pengguna', $id_pengguna, PDO::PARAM_INT);
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Error di Notifikasi->markAsRead(): " . $e->getMessage());
            return false;
        }
    }

    /**
     * [BARU] Membuat notifikasi baru.
     * @param int $id_pengguna ID pengguna penerima notifikasi.
     * @param string $pesan Isi pesan notifikasi.
     * @param string|null $link URL tujuan saat notifikasi diklik.
     * @return bool True jika berhasil, false jika gagal.
     */
    public function create($id_pengguna, $pesan, $link = null) {
        try {
            $query = "INSERT INTO " . $this->table_name . " (id_pengguna, pesan, link) VALUES (:id_pengguna, :pesan, :link)";
            $stmt = $this->conn->prepare($query);
            
            $stmt->bindParam(':id_pengguna', $id_pengguna, PDO::PARAM_INT);
            $stmt->bindParam(':pesan', $pesan);
            $stmt->bindParam(':link', $link);

            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Error di Notifikasi->create(): " . $e->getMessage());
            return false;
        }
    }

    /**
     * [BARU] Menghitung notifikasi yang belum dibaca oleh pengguna.
     * @return int Jumlah notifikasi yang belum dibaca.
     */
    public function countUnreadByUserId($id_pengguna) {
        try {
            $query = "SELECT COUNT(*) as total FROM " . $this->table_name . " WHERE id_pengguna = :id_pengguna AND is_read = 0";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':id_pengguna', $id_pengguna, PDO::PARAM_INT);
            $stmt->execute();
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            return (int)($row['total'] ?? 0);
        } catch (PDOException $e) {
            error_log("Error di Notifikasi->countUnreadByUserId(): " . $e->getMessage());
            return 0;
        }
    }

    /**
     * [BARU] Menandai semua notifikasi pengguna sebagai sudah dibaca.
     * @return bool True jika berhasil, false jika gagal.
     */
    public function markAllAsReadByUserId($id_pengguna) {
        try {
            $query = "UPDATE " . $this->table_name . " SET is_read = 1 WHERE id_pengguna = :id_pengguna AND is_read = 0";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':id_pengguna', $id_pengguna, PDO::PARAM_INT);
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Error di Notifikasi->markAllAsReadByUserId(): " . $e->getMessage());
            return false;
        }
    }
}
?>
