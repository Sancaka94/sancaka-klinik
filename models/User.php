<?php
require_once __DIR__ . '/../config/database.php';

class User {
    private $conn;

    public function __construct() {
        $db = new Database();
        $this->conn = $db->connect();
    }

    // Fungsi login, emailExists, dan phoneExists tetap sama seperti sebelumnya
    // Pastikan Anda memiliki versi yang sudah kompatibel dengan server Anda
    public function login($username, $password, $id_peran) {
        $query = "SELECT u.id_pengguna, u.username, u.email, u.nomor_telepon, u.password_hash, u.id_peran, p.nama_lengkap, p.foto_profil FROM Pengguna u LEFT JOIN Pasien p ON u.id_pengguna = p.id_pengguna WHERE (u.username = ? OR u.email = ? OR u.nomor_telepon = ?) AND u.id_peran = ?";
        $stmt = $this->conn->prepare($query);
        if ($stmt === false) { error_log("Query Gagal Disiapkan: " . $this->conn->error); return false; }
        $stmt->bind_param("sssi", $username, $username, $username, $id_peran);
        $stmt->execute();
        $stmt->bind_result($id_pengguna, $username_db, $email, $nomor_telepon, $password_hash, $id_peran_db, $nama_lengkap, $foto_profil);
        if ($stmt->fetch()) {
            $user = ['id_pengguna' => $id_pengguna, 'username' => $username_db, 'email' => $email, 'nomor_telepon' => $nomor_telepon, 'password_hash' => $password_hash, 'id_peran' => $id_peran_db, 'nama_lengkap' => $nama_lengkap, 'foto_profil' => $foto_profil];
            $stmt->close();
            if (password_verify($password, $user['password_hash'])) { unset($user['password_hash']); return $user; }
        }
        if (isset($stmt) && property_exists($stmt, 'num_rows') && $stmt->num_rows !== null) $stmt->close();
        return false;
    }
    public function emailExists($email) {
        $query = "SELECT id_pengguna FROM Pengguna WHERE email = ?";
        $stmt = $this->conn->prepare($query);
        if (!$stmt) return false;
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();
        $exists = $stmt->num_rows > 0;
        $stmt->close();
        return $exists;
    }
    public function phoneExists($phone) {
        if (empty($phone)) return false;
        $query = "SELECT id_pengguna FROM Pengguna WHERE nomor_telepon = ?";
        $stmt = $this->conn->prepare($query);
        if (!$stmt) return false;
        $stmt->bind_param("s", $phone);
        $stmt->execute();
        $stmt->store_result();
        $exists = $stmt->num_rows > 0;
        $stmt->close();
        return $exists;
    }


    /**
     * Mendaftarkan pengguna baru dengan semua data dari form multi-langkah.
     * @param array $data Data lengkap dari AuthController.
     * @return bool True jika berhasil, false jika gagal.
     */
    public function register($data) {
        // Memulai transaksi untuk memastikan semua query berhasil
        $this->conn->begin_transaction();

        try {
            // --- Handle File Upload ---
            $nama_file_foto = null;
            if (isset($data['foto_profil_file']) && $data['foto_profil_file']['error'] == 0) {
                $file = $data['foto_profil_file'];
                $upload_dir = __DIR__ . '/../../uploads/profiles/';
                if (!is_dir($upload_dir)) {
                    mkdir($upload_dir, 0755, true);
                }
                $file_extension = pathinfo($file['name'], PATHINFO_EXTENSION);
                // Nama file dibuat unik untuk menghindari tumpang tindih
                $nama_file_foto = 'user_new_' . time() . '.' . $file_extension;
                move_uploaded_file($file['tmp_name'], $upload_dir . $nama_file_foto);
            }

            // --- LANGKAH 1: Insert ke tabel Pengguna ---
            $hashed_password = password_hash($data['password'], PASSWORD_BCRYPT);
            $queryPengguna = "INSERT INTO Pengguna (id_peran, username, email, nomor_telepon, password_hash) VALUES (?, ?, ?, ?, ?)";
            $stmtPengguna = $this->conn->prepare($queryPengguna);
            if (!$stmtPengguna) throw new Exception("Prepare Pengguna gagal: " . $this->conn->error);
            
            $default_id_peran = 4; // Pasien
            $stmtPengguna->bind_param("issss", $default_id_peran, $data['email'], $data['email'], $data['nomor_telepon'], $hashed_password);
            if (!$stmtPengguna->execute()) throw new Exception("Execute Pengguna gagal: " . $stmtPengguna->error);

            $new_user_id = $this->conn->insert_id;
            $stmtPengguna->close();

            // --- LANGKAH 2: Insert ke tabel Pasien dengan semua data baru ---
            $nomor_rekam_medis = 'RM-' . date('Ym') . '-' . str_pad($new_user_id, 5, '0', STR_PAD_LEFT);
            
            $queryPasien = "INSERT INTO Pasien (
                                id_pengguna, nomor_rekam_medis, nama_lengkap, tanggal_lahir, usia, 
                                jenis_kelamin, alamat, berat_badan, tinggi_badan, penanggung_jawab, 
                                sejak_kapan_sakit, poli_tujuan, foto_profil
                            ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
            
            $stmtPasien = $this->conn->prepare($queryPasien);
            if (!$stmtPasien) throw new Exception("Prepare Pasien gagal: " . $this->conn->error);
            
            $stmtPasien->bind_param("isssissssssss", 
                $new_user_id,
                $nomor_rekam_medis,
                $data['nama_lengkap'],
                $data['tanggal_lahir'],
                $data['usia'],
                $data['jenis_kelamin'],
                $data['alamat'],
                $data['berat_badan'],
                $data['tinggi_badan'],
                $data['penanggung_jawab'],
                $data['sejak_kapan_sakit'],
                $data['poli_tujuan'],
                $nama_file_foto // Nama file foto yang sudah di-upload
            );

            if (!$stmtPasien->execute()) throw new Exception("Execute Pasien gagal: " . $stmtPasien->error);
            $stmtPasien->close();

            // Jika semua query berhasil, simpan perubahan
            $this->conn->commit();
            return true;

        } catch (Exception $e) {
            // Jika ada satu saja yang gagal, batalkan semua perubahan
            $this->conn->rollback();
            error_log($e->getMessage()); // Catat error untuk developer
            return false;
        }
    }
}
