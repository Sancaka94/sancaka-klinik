<?php
// File: models/Dokter.php

class Dokter {
    private $conn;
    private $table_name = "pengguna";

    public function __construct($db) {
        $this->conn = $db;
    }

    /**
     * [READ] Mengambil semua data dokter dari database.
     */
    public function getAll() {
        $query = "SELECT id_pengguna, nama_lengkap, spesialisasi, email, nomor_str FROM " . $this->table_name . " WHERE id_peran = 3 ORDER BY nama_lengkap ASC";
        try {
            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            
            // PERBAIKAN: Menggunakan bind_result dan fetch, bukan get_result
            $result = [];
            $stmt->bind_result($id_pengguna, $nama_lengkap, $spesialisasi, $email, $nomor_str);
            while ($stmt->fetch()) {
                $result[] = [
                    'id_pengguna' => $id_pengguna,
                    'nama_lengkap' => $nama_lengkap,
                    'spesialisasi' => $spesialisasi,
                    'email' => $email,
                    'nomor_str' => $nomor_str
                ];
            }
            $stmt->close();
            return $result;

        } catch (Exception $e) {
            error_log("Error di Dokter->getAll(): " . $e->getMessage());
            return null;
        }
    }

    /**
     * [READ] Mengambil data satu dokter berdasarkan ID.
     */
    public function getById($id) {
        $query = "SELECT * FROM " . $this->table_name . " WHERE id_pengguna = ? AND id_peran = 3 LIMIT 1";
        try {
            $stmt = $this->conn->prepare($query);
            $stmt->bind_param("i", $id);
            $stmt->execute();

            // PERBAIKAN: Menggunakan metode bind_result yang dinamis
            $result = $this->getDynamicResult($stmt);
            return $result[0] ?? null; // Mengembalikan baris pertama atau null

        } catch (Exception $e) {
            error_log("Error di Dokter->getById(): " . $e->getMessage());
            return null;
        }
    }
    
    // --- Metode CRUD lainnya tetap sama ---
    public function create($data) { /* ... */ }
    public function update($data) { /* ... */ }
    public function delete($id) { /* ... */ }

    /**
     * Fungsi helper pribadi untuk mengambil hasil query secara dinamis
     * tanpa menggunakan get_result().
     */
    private function getDynamicResult($stmt) {
        $result = [];
        $stmt->store_result();
        if ($stmt->num_rows > 0) {
            $meta = $stmt->result_metadata();
            $params = [];
            while ($field = $meta->fetch_field()) {
                $params[] = &$row[$field->name];
            }
            call_user_func_array([$stmt, 'bind_result'], $params);
            while ($stmt->fetch()) {
                $c = [];
                foreach ($row as $key => $val) {
                    $c[$key] = $val;
                }
                $result[] = $c;
            }
        }
        $stmt->close();
        return $result;
    }
}
