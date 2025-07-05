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
            // Anda bisa menambahkan logging di sini
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
}
