<?php
require_once __DIR__ . '/../config/database.php';

class JanjiTemu {
    private $conn;

    public function __construct() {
        $database = new Database();
        $this->conn = $database->connect();
    }

    /**
     * Membuat janji temu baru.
     */
    public function create($data) {
        $query = "INSERT INTO janji_temu (id_pasien, id_dokter, tanggal_temu, waktu_temu, keluhan, status) 
                  VALUES (?, ?, ?, ?, ?, ?)";
        
        $stmt = $this->conn->prepare($query);
        if ($stmt === false) {
            return false;
        }

        $stmt->bind_param(
            "iissss",
            $data['id_pasien'],
            $data['id_dokter'],
            $data['tanggal_temu'],
            $data['waktu_temu'],
            $data['keluhan'],
            $data['status']
        );

        return $stmt->execute();
    }
    
    /**
     * Mengambil semua janji temu untuk dokter tertentu pada hari ini.
     */
    public function getAppointmentsForDoctorToday($id_dokter) {
        $query = "SELECT jt.*, p.nama_lengkap, p.foto_profil 
                  FROM janji_temu jt
                  JOIN pasien p ON jt.id_pasien = p.id_pasien
                  WHERE jt.id_dokter = ? AND jt.tanggal_temu = CURDATE()
                  ORDER BY jt.waktu_temu ASC";
                  
        $stmt = $this->conn->prepare($query);
        if ($stmt === false) return [];

        $stmt->bind_param("i", $id_dokter);
        $stmt->execute();
        $result = $stmt->get_result();

        $appointments = [];
        if ($result) {
            while ($row = $result->fetch_assoc()) {
                $appointments[] = $row;
            }
        }
        return $appointments;
    }

    /**
     * Menghitung statistik janji temu untuk dokter pada hari ini.
     */
    public function getAppointmentStatsForDoctorToday($id_dokter) {
        $query = "SELECT 
                    COUNT(*) as total_today,
                    SUM(CASE WHEN status = 'Selesai' THEN 1 ELSE 0 END) as total_selesai,
                    SUM(CASE WHEN status = 'Dijadwalkan' THEN 1 ELSE 0 END) as total_menunggu
                  FROM janji_temu
                  WHERE id_dokter = ? AND tanggal_temu = CURDATE()";

        $stmt = $this->conn->prepare($query);
        $default_stats = ['total_today' => 0, 'total_selesai' => 0, 'total_menunggu' => 0];
        if ($stmt === false) return $default_stats;

        $stmt->bind_param("i", $id_dokter);
        $stmt->execute();
        $result = $stmt->get_result();
        
        return $result ? $result->fetch_assoc() : $default_stats;
    }

    /**
     * [FUNGSI BARU] Mengambil janji temu berikutnya untuk pasien.
     * @param int $id_pasien ID pasien
     * @return array|null Data janji temu jika ada, atau null
     */
    public function getUpcomingAppointmentForPatient($id_pasien) {
        $query = "SELECT jt.*, u.nama_lengkap as nama_dokter
                  FROM janji_temu jt
                  JOIN pengguna u ON jt.id_dokter = u.id_pengguna
                  WHERE jt.id_pasien = ? AND jt.tanggal_temu >= CURDATE()
                  ORDER BY jt.tanggal_temu ASC, jt.waktu_temu ASC
                  LIMIT 1";
                  
        $stmt = $this->conn->prepare($query);
        if ($stmt === false) return null;

        $stmt->bind_param("i", $id_pasien);
        $stmt->execute();
        $result = $stmt->get_result();
        
        return $result ? $result->fetch_assoc() : null;
    }

    /**
     * [FUNGSI BARU] Mengambil riwayat janji temu untuk pasien.
     * @param int $id_pasien ID pasien
     * @return array Daftar riwayat janji temu
     */
    public function getHistoryForPatient($id_pasien) {
        $query = "SELECT jt.*, u.nama_lengkap as nama_dokter
                  FROM janji_temu jt
                  JOIN pengguna u ON jt.id_dokter = u.id_pengguna
                  WHERE jt.id_pasien = ?
                  ORDER BY jt.tanggal_temu DESC, jt.waktu_temu DESC";

        $stmt = $this->conn->prepare($query);
        if ($stmt === false) return [];

        $stmt->bind_param("i", $id_pasien);
        $stmt->execute();
        $result = $stmt->get_result();
        
        $history = [];
        if ($result) {
            while ($row = $result->fetch_assoc()) {
                $history[] = $row;
            }
        }
        return $history;
    }
}
