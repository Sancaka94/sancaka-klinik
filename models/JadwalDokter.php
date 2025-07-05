<?php
require_once __DIR__ . '/../config/database.php';

class JadwalDokter {
    private $conn;

    public function __construct() {
        $db = new Database();
        $this->conn = $db->connect();
    }

    /**
     * Mengambil daftar unik dokter yang memiliki jadwal.
     */
    public function getDokterTersedia() {
        $query = "
            SELECT DISTINCT s.id_staf, s.nama_lengkap, s.spesialisasi 
            FROM Staf s
            JOIN JadwalDokter jd ON s.id_staf = jd.id_staf
            WHERE s.spesialisasi LIKE 'Dokter%'
            ORDER BY s.nama_lengkap ASC
        ";
        
        $result = $this->conn->query($query);
        
        $dokter = [];
        if ($result) {
            while ($row = $result->fetch_assoc()) {
                $dokter[] = $row;
            }
        }
        return $dokter;
    }

    /**
     * Mencari ID jadwal yang valid berdasarkan staf, hari, dan jam.
     * @param int $id_staf
     * @param string $hari (e.g., 'Senin', 'Selasa')
     * @param string $jam (e.g., '14:30:00')
     * @return int|null ID jadwal jika ditemukan, null jika tidak.
     */
    public function findJadwalId($id_staf, $hari, $jam) {
        $query = "SELECT id_jadwal FROM JadwalDokter 
                  WHERE id_staf = ? AND hari = ? AND ? BETWEEN jam_mulai AND jam_selesai 
                  LIMIT 1";
        
        $stmt = $this->conn->prepare($query);
        if (!$stmt) return null;

        $stmt->bind_param("iss", $id_staf, $hari, $jam);
        $stmt->execute();
        $stmt->bind_result($id_jadwal);
        
        if ($stmt->fetch()) {
            $stmt->close();
            return $id_jadwal;
        }
        
        $stmt->close();
        return null;
    }
}
