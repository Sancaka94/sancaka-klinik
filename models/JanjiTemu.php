<?php
// File: models/JanjiTemu.php

class JanjiTemu {
    private $conn;
    private $table_name = "janji_temu";

    public function __construct($db) {
        $this->conn = $db;
    }

    /**
     * Menghitung janji temu yang masih aktif (belum selesai).
     */
    public function countJanjiAktif($id_pasien) {
        try {
            $query = "SELECT COUNT(*) as total FROM " . $this->table_name . " WHERE id_pasien = :id_pasien AND status != 'Selesai' AND status != 'Batal'";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':id_pasien', $id_pasien, PDO::PARAM_INT);
            $stmt->execute();
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            return $row['total'] ?? 0;
        } catch (PDOException $e) {
            error_log("Error di JanjiTemu->countJanjiAktif(): " . $e->getMessage());
            return 0;
        }
    }

    /**
     * Mengambil daftar janji temu seorang pasien.
     */
    public function getJanjiByPasien($id_pasien) {
        try {
            // Menggunakan JOIN untuk mendapatkan nama dokter
            $query = "SELECT jt.*, p.nama_lengkap as nama_dokter 
                      FROM " . $this->table_name . " jt
                      JOIN pengguna p ON jt.id_dokter = p.id_pengguna
                      WHERE jt.id_pasien = :id_pasien 
                      ORDER BY jt.tanggal_janji ASC, jt.waktu_janji ASC";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':id_pasien', $id_pasien, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error di JanjiTemu->getJanjiByPasien(): " . $e->getMessage());
            return [];
        }
    }
    
    /**
     * Mengambil daftar janji temu untuk seorang dokter pada tanggal tertentu.
     */
    public function getJanjiByDokter($id_dokter, $tanggal) {
        try {
            $query = "SELECT jt.*, p.nama_lengkap as nama_pasien 
                      FROM " . $this->table_name . " jt
                      JOIN pengguna p ON jt.id_pasien = p.id_pengguna
                      WHERE jt.id_dokter = :id_dokter AND jt.tanggal_janji = :tanggal
                      ORDER BY jt.waktu_janji ASC";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':id_dokter', $id_dokter, PDO::PARAM_INT);
            $stmt->bindParam(':tanggal', $tanggal);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error di JanjiTemu->getJanjiByDokter(): " . $e->getMessage());
            return [];
        }
    }

    /**
     * Menghitung total janji temu pada tanggal tertentu (untuk admin).
     */
    public function countJanjiByDate($tanggal) {
        try {
            $query = "SELECT COUNT(*) as total FROM " . $this->table_name . " WHERE tanggal_janji = :tanggal";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':tanggal', $tanggal);
            $stmt->execute();
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            return $row['total'] ?? 0;
        } catch (PDOException $e) {
            error_log("Error di JanjiTemu->countJanjiByDate(): " . $e->getMessage());
            return 0;
        }
    }
}
?>
