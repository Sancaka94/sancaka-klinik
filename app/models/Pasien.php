<?php
require_once __DIR__ . '/../config/database.php';

class Pasien {
    private $conn;
    public function __construct() {
        $db = new Database();
        $this->conn = $db->connect();
    }

    /**
     * Mengambil data satu pasien berdasarkan id_pengguna.
     * Dibuat kompatibel dengan server yang tidak memiliki mysqlnd.
     * @param int $id_pengguna ID dari pengguna yang sedang login.
     * @return array|false Data pasien jika ditemukan, false jika tidak.
     */
    public function getPasienByPenggunaId($id_pengguna) {
        // Query ini menggabungkan tabel Pasien dan Pengguna untuk mendapatkan email dan nomor telepon
        $query = "SELECT 
                    p.id_pasien, p.id_pengguna, p.nomor_rekam_medis, p.nama_lengkap, 
                    p.tanggal_lahir, p.jenis_kelamin, p.alamat, p.foto_profil, 
                    u.email, u.nomor_telepon 
                  FROM Pasien p 
                  JOIN Pengguna u ON p.id_pengguna = u.id_pengguna 
                  WHERE p.id_pengguna = ?";
                  
        $stmt = $this->conn->prepare($query);
        if (!$stmt) {
            error_log("Prepare failed: " . $this->conn->error);
            return false;
        }
        
        $stmt->bind_param("i", $id_pengguna);
        $stmt->execute();
        
        // **PERBAIKAN:** Menggunakan bind_result sebagai pengganti get_result()
        $stmt->bind_result(
            $id_pasien, $id_pengguna_db, $nomor_rekam_medis, $nama_lengkap, 
            $tanggal_lahir, $jenis_kelamin, $alamat, $foto_profil, 
            $email, $nomor_telepon
        );
        
        // Ambil data ke dalam variabel yang sudah diikat
        if ($stmt->fetch()) {
            // Buat array secara manual
            $pasien = [
                'id_pasien' => $id_pasien,
                'id_pengguna' => $id_pengguna_db,
                'nomor_rekam_medis' => $nomor_rekam_medis,
                'nama_lengkap' => $nama_lengkap,
                'tanggal_lahir' => $tanggal_lahir,
                'jenis_kelamin' => $jenis_kelamin,
                'alamat' => $alamat,
                'foto_profil' => $foto_profil,
                'email' => $email,
                'nomor_telepon' => $nomor_telepon
            ];
            $stmt->close();
            return $pasien;
        }
        
        $stmt->close();
        return false;
    }

    /**
     * Memperbarui data profil pasien, termasuk foto profil.
     * @param array $data Data baru dari form.
     * @return bool True jika berhasil, false jika gagal.
     */
    public function updateProfile($data) {
        $this->conn->begin_transaction();
        try {
            // Query ini hanya mengupdate nama dan foto, sesuai dengan data yang dikirim oleh controller
            $queryPasien = "UPDATE Pasien SET nama_lengkap = ?, foto_profil = ? WHERE id_pengguna = ?";
            $stmtPasien = $this->conn->prepare($queryPasien);
            if (!$stmtPasien) throw new Exception("Prepare Pasien gagal: " . $this->conn->error);
            $stmtPasien->bind_param("ssi", $data['nama_lengkap'], $data['foto_profil'], $data['id_pengguna']);
            if (!$stmtPasien->execute()) throw new Exception("Execute Pasien gagal: " . $stmtPasien->error);
            $stmtPasien->close();

            // Query untuk mengupdate nomor telepon di tabel Pengguna
            $queryPengguna = "UPDATE Pengguna SET nomor_telepon = ? WHERE id_pengguna = ?";
            $stmtPengguna = $this->conn->prepare($queryPengguna);
            if (!$stmtPengguna) throw new Exception("Prepare Pengguna gagal: " . $this->conn->error);
            $stmtPengguna->bind_param("si", $data['nomor_telepon'], $data['id_pengguna']);
            if (!$stmtPengguna->execute()) throw new Exception("Execute Pengguna gagal: " . $stmtPengguna->error);
            $stmtPengguna->close();

            $this->conn->commit();
            return true;
        } catch (Exception $e) {
            $this->conn->rollback();
            error_log($e->getMessage());
            return false;
        }
    }
}
