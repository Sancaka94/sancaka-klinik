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
     * [FUNGSI BARU] Mengambil semua janji temu untuk dokter tertentu pada hari ini.
     */
    public function getAppointmentsForDoctorToday($id_dokter) {
        // Query ini menggabungkan tabel janji_temu dengan pasien dan pengguna untuk mendapatkan nama & foto pasien
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
        while ($row = $result->fetch_assoc()) {
            $appointments[] = $row;
        }
        return $appointments;
    }

    /**
     * [FUNGSI BARU] Menghitung statistik janji temu untuk dokter pada hari ini.
     */
    public function getAppointmentStatsForDoctorToday($id_dokter) {
        $query = "SELECT 
                    COUNT(*) as total_today,
                    SUM(CASE WHEN status = 'Selesai' THEN 1 ELSE 0 END) as total_selesai,
                    SUM(CASE WHEN status = 'Dijadwalkan' THEN 1 ELSE 0 END) as total_menunggu
                  FROM janji_temu
                  WHERE id_dokter = ? AND tanggal_temu = CURDATE()";

        $stmt = $this->conn->prepare($query);
        if ($stmt === false) return ['total_today' => 0, 'total_selesai' => 0, 'total_menunggu' => 0];

        $stmt->bind_param("i", $id_dokter);
        $stmt->execute();
        $result = $stmt->get_result();
        
        // Mengembalikan hasil sebagai array asosiatif
        return $result->fetch_assoc();
    }
}
