<?php
// File: models/RekamMedis.php

class RekamMedis {
    private $conn;
    private $table_name = "rekam_medis";

    public function __construct($db) {
        $this->conn = $db;
    }

    /**
     * Menghitung total kunjungan seorang pasien.
     */
    public function countKunjungan($id_pasien) {
        try {
            $query = "SELECT COUNT(*) as total FROM " . $this->table_name . " WHERE id_pasien = :id_pasien";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':id_pasien', $id_pasien, PDO::PARAM_INT);
            $stmt->execute();
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            return $row['total'] ?? 0;
        } catch (PDOException $e) {
            error_log("Error di RekamMedis->countKunjungan(): " . $e->getMessage());
            return 0;
        }
    }

    /**
     * Mengambil riwayat rekam medis seorang pasien.
     */
    public function getRiwayatByPasien($id_pasien) {
        try {
            // Menggunakan JOIN untuk mendapatkan nama dokter
            $query = "SELECT rm.*, p.nama_lengkap as nama_dokter 
                      FROM " . $this->table_name . " rm
                      JOIN pengguna p ON rm.id_dokter = p.id_pengguna
                      WHERE rm.id_pasien = :id_pasien 
                      ORDER BY rm.tanggal_kunjungan DESC";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':id_pasien', $id_pasien, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error di RekamMedis->getRiwayatByPasien(): " . $e->getMessage());
            return [];
        }
    }
}
?>
