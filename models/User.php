<?php
// File: models/User.php

class User {
    private $conn;
    private $table_name = "pengguna";

    public function __construct($db) {
        $this->conn = $db;
    }
    
    // --- Metode yang sudah ada ---
    public function login($username, $password, $id_peran) { /* ... (kode dari sebelumnya) ... */ }
    public function register($data) { /* ... (kode dari sebelumnya) ... */ }
    public function registerDokter($data) { /* ... (kode dari sebelumnya) ... */ }
    public function emailExists($email) { /* ... (kode dari sebelumnya) ... */ }
    private function getDynamicResult($stmt) { /* ... (kode dari sebelumnya) ... */ }
    // --- Akhir metode yang sudah ada ---


    /**
     * [UPDATE - FUNGSI YANG HILANG] Memperbarui data profil pengguna di database.
     * @param array $data Data pengguna yang akan diperbarui.
     * @return bool True jika berhasil, false jika gagal.
     */
    public function updateProfile($data) {
        // Query dasar untuk update data pengguna umum
        $query = "UPDATE " . $this->table_name . " SET nama_lengkap = ?, email = ? ";

        // Siapkan array untuk parameter dan tipe data untuk bind_param
        $params = [$data['nama_lengkap'], $data['email']];
        $types = "ss";

        // Tambahkan update password ke query jika password baru diisi
        if (!empty($data['password'])) {
            $query .= ", password = ? ";
            $params[] = password_hash($data['password'], PASSWORD_BCRYPT);
            $types .= "s";
        }

        // Tambahkan update foto profil ke query jika ada file baru yang di-upload
        if (!empty($data['foto_profil'])) {
             $query .= ", foto_profil = ? ";
             $params[] = $data['foto_profil'];
             $types .= "s";
        }

        // Tambahkan update data spesifik dokter jika ada
        if (isset($data['spesialisasi']) && isset($data['nomor_str'])) {
            $query .= ", spesialisasi = ?, nomor_str = ? ";
            $params[] = $data['spesialisasi'];
            $params[] = $data['nomor_str'];
            $types .= "ss";
        }

        // Tambahkan kondisi WHERE di akhir query
        $query .= " WHERE id_pengguna = ?";
        $params[] = $data['id_pengguna'];
        $types .= "i";

        try {
            $stmt = $this->conn->prepare($query);
            if ($stmt === false) {
                throw new Exception("Gagal prepare query update profil: " . $this->conn->error);
            }

            // Menggunakan 'splat operator' (...) untuk mengirim semua parameter ke bind_param
            $stmt->bind_param($types, ...$params);
            
            // Eksekusi statement dan kembalikan hasilnya (true/false)
            return $stmt->execute();

        } catch (Exception $e) {
            error_log("Error di User->updateProfile(): " . $e->getMessage());
            return false;
        }
    }
}
