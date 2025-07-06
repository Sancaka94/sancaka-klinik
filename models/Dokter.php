<?php
// File: models/Dokter.php

class Dokter {
    // Properti untuk menyimpan koneksi database dan nama tabel
    private $conn;
    private $table_name = "pengguna"; // Data dokter ada di tabel 'pengguna'

    /**
     * Constructor untuk class Dokter.
     * Menerima koneksi database sebagai parameter.
     * @param object $db Objek koneksi database.
     */
    public function __construct($db) {
        $this->conn = $db;
    }

    /**
     * [CREATE] Menambahkan data dokter baru ke database.
     * Mirip dengan registerDokter, tapi bisa digunakan oleh admin.
     * @param array $data Data dokter dari form.
     * @return bool True jika berhasil, false jika gagal.
     */
    public function create($data) {
        $query = "INSERT INTO " . $this->table_name . " 
                  (username, email, password, id_peran, nama_lengkap, spesialisasi, nomor_str, foto_profil) 
                  VALUES (?, ?, ?, 3, ?, ?, ?, ?)"; // id_peran di-hardcode ke 3 untuk Dokter

        try {
            $stmt = $this->conn->prepare($query);
            if ($stmt === false) {
                throw new Exception("Gagal prepare query create dokter: " . $this->conn->error);
            }

            $password_hash = password_hash($data['password'], PASSWORD_BCRYPT);
            $username = $data['email']; // Menggunakan email sebagai username default

            $stmt->bind_param(
                "sssssss",
                $username,
                $data['email'],
                $password_hash,
                $data['nama_lengkap'],
                $data['spesialisasi'],
                $data['nomor_str'],
                $data['foto_profil']
            );

            return $stmt->execute();

        } catch (Exception $e) {
            error_log("Error di Dokter->create(): " . $e->getMessage());
            return false;
        }
    }

    /**
     * [READ] Mengambil semua data dokter dari database.
     * @return array|null Daftar dokter atau null jika terjadi error.
     */
    public function getAll() {
        $query = "SELECT id_pengguna, nama_lengkap, spesialisasi, email, nomor_str FROM " . $this->table_name . " WHERE id_peran = 3 ORDER BY nama_lengkap ASC";

        try {
            $stmt = $this->conn->prepare($query);
            if ($stmt === false) {
                throw new Exception("Gagal prepare query: " . $this->conn->error);
            }
            
            $stmt->execute();
            $result = $stmt->get_result();
            
            return $result->fetch_all(MYSQLI_ASSOC);

        } catch (Exception $e) {
            error_log("Error di Dokter->getAll(): " . $e->getMessage());
            return null;
        }
    }

    /**
     * [READ] Mengambil data satu dokter berdasarkan ID.
     * @param int $id ID dokter.
     * @return array|null Data dokter atau null jika tidak ditemukan.
     */
    public function getById($id) {
        $query = "SELECT * FROM " . $this->table_name . " WHERE id_pengguna = ? AND id_peran = 3 LIMIT 1";

        try {
            $stmt = $this->conn->prepare($query);
            $stmt->bind_param("i", $id);
            $stmt->execute();
            $result = $stmt->get_result();
            
            return $result->fetch_assoc();

        } catch (Exception $e) {
            error_log("Error di Dokter->getById(): " . $e->getMessage());
            return null;
        }
    }

    /**
     * [UPDATE] Memperbarui data dokter di database.
     * @param array $data Data dokter yang akan diperbarui, harus berisi 'id_pengguna'.
     * @return bool True jika berhasil, false jika gagal.
     */
    public function update($data) {
        // Query dasar untuk update data dokter
        $query = "UPDATE " . $this->table_name . " SET 
                  nama_lengkap = ?, 
                  email = ?, 
                  spesialisasi = ?, 
                  nomor_str = ? ";

        // Jika password baru disediakan, tambahkan ke query
        $params = [
            $data['nama_lengkap'],
            $data['email'],
            $data['spesialisasi'],
            $data['nomor_str']
        ];
        $types = "ssss";

        if (!empty($data['password'])) {
            $query .= ", password = ? ";
            $params[] = password_hash($data['password'], PASSWORD_BCRYPT);
            $types .= "s";
        }

        // Tambahkan kondisi WHERE
        $query .= " WHERE id_pengguna = ? AND id_peran = 3";
        $params[] = $data['id_pengguna'];
        $types .= "i";

        try {
            $stmt = $this->conn->prepare($query);
            if ($stmt === false) {
                throw new Exception("Gagal prepare query update dokter: " . $this->conn->error);
            }

            $stmt->bind_param($types, ...$params);
            return $stmt->execute();

        } catch (Exception $e) {
            error_log("Error di Dokter->update(): " . $e->getMessage());
            return false;
        }
    }

    /**
     * [DELETE] Menghapus data dokter dari database.
     * @param int $id ID dokter yang akan dihapus.
     * @return bool True jika berhasil, false jika gagal.
     */
    public function delete($id) {
        $query = "DELETE FROM " . $this->table_name . " WHERE id_pengguna = ? AND id_peran = 3";

        try {
            $stmt = $this->conn->prepare($query);
            if ($stmt === false) {
                throw new Exception("Gagal prepare query delete dokter: " . $this->conn->error);
            }

            $stmt->bind_param("i", $id);
            return $stmt->execute();

        } catch (Exception $e) {
            error_log("Error di Dokter->delete(): " . $e->getMessage());
            return false;
        }
    }
}
