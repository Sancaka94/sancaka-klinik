<?php
require_once __DIR__ . '/../config/database.php';

class JanjiTemu {
    private $conn;

    public function __construct() {
        $db = new Database();
        $this->conn = $db->connect();
    }

    /**
     * Mengambil seluruh riwayat janji temu seorang pasien.
     * Dibuat kompatibel dengan server yang tidak memiliki mysqlnd.
     */
    public function getRiwayatByPasienId($id_pasien) {
        $query = "
            SELECT 
                jt.id_janji_temu, jt.tanggal_janji, s.nama_lengkap AS dokter, 
                jt.status, jt.nomor_antrian,
                CASE WHEN rm.id_rekam_medis IS NOT NULL THEN 1 ELSE 0 END AS rekam_medis_tersedia
            FROM JanjiTemu jt
            JOIN JadwalDokter jd ON jt.id_jadwal = jd.id_jadwal
            JOIN Staf s ON jd.id_staf = s.id_staf
            LEFT JOIN RekamMedis rm ON jt.id_janji_temu = rm.id_janji_temu
            WHERE jt.id_pasien = ? ORDER BY jt.tanggal_janji DESC
        ";
        
        $stmt = $this->conn->prepare($query);
        if (!$stmt) return [];
        
        $stmt->bind_param("i", $id_pasien);
        $stmt->execute();
        
        $stmt->bind_result($id, $tanggal_booking, $dokter, $status, $nomor_antrian, $rekam_medis_tersedia);
        
        $riwayat = [];
        while ($stmt->fetch()) {
            $riwayat[] = [
                'id' => $id,
                'tanggal_booking' => $tanggal_booking,
                'dokter' => $dokter,
                'status' => $status,
                'nomor_antrian' => $nomor_antrian,
                'rekam_medis_tersedia' => (bool)$rekam_medis_tersedia
            ];
        }
        $stmt->close();
        return $riwayat;
    }

    /**
     * Menyimpan janji temu baru ke database.
     * @param array $data Data janji temu dari controller.
     * @return bool True jika berhasil, false jika gagal.
     */
    public function simpanJanjiBaru($data) {
        $query = "INSERT INTO JanjiTemu (id_pasien, id_jadwal, tanggal_janji, nomor_antrian, status, keluhan) 
                  VALUES (?, ?, ?, ?, ?, ?)";
        
        $stmt = $this->conn->prepare($query);
        if (!$stmt) return false;

        $stmt->bind_param("iissss", 
            $data['id_pasien'], 
            $data['id_jadwal'], 
            $data['tanggal_janji'], 
            $data['nomor_antrian'], 
            $data['status'], 
            $data['keluhan']
        );
        
        $success = $stmt->execute();
        if (!$success) {
            error_log("Execute failed: (" . $stmt->errno . ") " . $stmt->error);
        }
        $stmt->close();
        return $success;
    }

    /**
     * Menghitung jumlah antrian untuk jadwal dan tanggal tertentu untuk membuat nomor antrian baru.
     * @param int $id_jadwal
     * @param string $tanggal_janji (format Y-m-d)
     * @return int Jumlah antrian yang sudah ada.
     */
    public function countAntrian($id_jadwal, $tanggal_janji) {
        $query = "SELECT COUNT(id_janji_temu) as total FROM JanjiTemu WHERE id_jadwal = ? AND DATE(tanggal_janji) = ?";
        $stmt = $this->conn->prepare($query);
        if (!$stmt) return 0;

        $stmt->bind_param("is", $id_jadwal, $tanggal_janji);
        $stmt->execute();
        $stmt->bind_result($total);
        $stmt->fetch();
        $stmt->close();
        return $total;
    }
}
