<?php
require_once __DIR__ . '/../config/database.php';

class User {
    private $conn;

    public function __construct() {
        $db = new Database();
        $this->conn = $db->connect();
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
        if (empty($phone)) {
            return false;
        }
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
    
    public function register($data) {
        $this->conn->begin_transaction();
        try {
            $email = $data['email'];
            $plain_password = $data['password'];
            $nomor_telepon = !empty($data['nomor_telepon']) ? $data['nomor_telepon'] : NULL;
            $hashed_password = password_hash($plain_password, PASSWORD_BCRYPT);
            $queryPengguna = "INSERT INTO Pengguna (id_peran, username, email, nomor_telepon, password_hash) VALUES (?, ?, ?, ?, ?)";
            $stmtPengguna = $this->conn->prepare($queryPengguna);
            if (!$stmtPengguna) throw new Exception("Prepare Pengguna gagal: " . $this->conn->error);
            $default_id_peran = 4;
            $default_username = $email;
            $stmtPengguna->bind_param("issss", $default_id_peran, $default_username, $email, $nomor_telepon, $hashed_password);
            if (!$stmtPengguna->execute()) throw new Exception("Execute Pengguna gagal: " . $stmtPengguna->error);
            $new_user_id = $this->conn->insert_id;
            $stmtPengguna->close();
            $nomor_rekam_medis = 'RM-' . date('Ym') . '-' . str_pad($new_user_id, 5, '0', STR_PAD_LEFT);
            $queryPasien = "INSERT INTO Pasien (id_pengguna, nomor_rekam_medis, nama_lengkap, tanggal_lahir) VALUES (?, ?, ?, ?)";
            $stmtPasien = $this->conn->prepare($queryPasien);
            if (!$stmtPasien) throw new Exception("Prepare Pasien gagal: " . $this->conn->error);
            $default_tanggal_lahir = date('Y-m-d');
            $stmtPasien->bind_param("isss", $new_user_id, $nomor_rekam_medis, $email, $default_tanggal_lahir);
            if (!$stmtPasien->execute()) throw new Exception("Execute Pasien gagal: " . $stmtPasien->error);
            $stmtPasien->close();
            $this->conn->commit();
            return true;
        } catch (Exception $e) {
            $this->conn->rollback();
            error_log($e->getMessage());
            return false;
        }
    }

    /**
     * Mengotentikasi pengguna dan mengambil data lengkap untuk session.
     * @param string $username Bisa berupa username, email, atau nomor telepon.
     * @param string $password Password teks biasa.
     * @return array|false Data pengguna jika berhasil, false jika gagal.
     */
    public function login($username, $password) {
        // **PERBAIKAN:** Query ini sekarang menggabungkan tabel Pengguna dan Pasien
        // untuk mengambil nama_lengkap dan foto_profil.
        $query = "
            SELECT 
                u.id_pengguna, u.username, u.email, u.nomor_telepon, u.password_hash, u.id_peran,
                p.nama_lengkap, p.foto_profil
            FROM Pengguna u
            LEFT JOIN Pasien p ON u.id_pengguna = p.id_pengguna
            WHERE u.username = ? OR u.email = ? OR u.nomor_telepon = ?
        ";

        $stmt = $this->conn->prepare($query);
        if ($stmt === false) {
            error_log("Query Gagal Disiapkan: " . $this->conn->error);
            return false;
        }

        $stmt->bind_param("sss", $username, $username, $username);
        $stmt->execute();
        
        // **PERBAIKAN:** Sesuaikan variabel yang akan menerima hasil query.
        $stmt->bind_result($id_pengguna, $username_db, $email, $nomor_telepon, $password_hash, $id_peran, $nama_lengkap, $foto_profil);

        if ($stmt->fetch()) {
            // **PERBAIKAN:** Buat array user yang lebih lengkap untuk disimpan di session.
            $user = [
                'id_pengguna'   => $id_pengguna,
                'username'      => $username_db,
                'email'         => $email,
                'nomor_telepon' => $nomor_telepon,
                'password_hash' => $password_hash,
                'id_peran'      => $id_peran,
                'nama_lengkap'  => $nama_lengkap,
                'foto_profil'   => $foto_profil
            ];
            
            $stmt->close();

            if (password_verify($password, $user['password_hash'])) {
                unset($user['password_hash']); 
                return $user; // Login berhasil dengan data lengkap
            }
        }
        
        if (isset($stmt) && property_exists($stmt, 'num_rows') && $stmt->num_rows !== null) $stmt->close();
        return false;
    }
}
