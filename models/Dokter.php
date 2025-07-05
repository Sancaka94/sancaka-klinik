<?php
require_once __DIR__ . '/../config/database.php';

class Dokter {
    private $conn;

    public function __construct() {
        $database = new Database();
        $this->conn = $database->connect();
    }

    /**
     * Mengambil semua data dokter yang aktif.
     */
    public function getAllDokter() {
        // Query untuk mengambil data dokter dari tabel 'pengguna' yang perannya adalah dokter (misal: id_peran = 3)
        $query = "SELECT id_pengguna, nama_lengkap FROM pengguna WHERE id_peran = 3";
        
        $stmt = $this->conn->prepare($query);
        if ($stmt === false) {
            return [];
        }

        $stmt->execute();
        $result = $stmt->get_result();
        
        $dokters = [];
        while ($row = $result->fetch_assoc()) {
            $dokters[] = $row;
        }
        return $dokters;
    }
}
