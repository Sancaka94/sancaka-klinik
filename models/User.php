<?php
require_once __DIR__ . '/../config/database.php';

class User {
    private $conn;

    public function __construct() {
        $db = new Database();
        $this->conn = $db->connect();
    }

    // ... (fungsi login, emailExists, phoneExists tetap sama) ...
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
        $this->conn->begin_transaction();

        try {
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
            
            // --- LANGKAH 2: Handle File Uploads ---
            $upload_dir = __DIR__ . '/../../uploads/';
            if (!is_dir($upload_dir . 'profiles/')) mkdir($upload_dir . 'profiles/', 0755, true);
            if (!is_dir($upload_dir . 'documents/')) mkdir($upload_dir . 'documents/', 0755, true);

            $nama_file_foto = $this->uploadFile($data['foto_profil'], $upload_dir . 'profiles/', 'user-' . $new_user_id);
            $nama_file_ktp = $this->uploadFile($data['file_ktp'], $upload_dir . 'documents/', 'ktp-' . $new_user_id);
            $nama_file_kk = $this->uploadFile($data['file_kk'], $upload_dir . 'documents/', 'kk-' . $new_user_id);

            // --- LANGKAH 3: Insert ke tabel Pasien dengan semua data baru ---
            $nomor_rekam_medis = 'RM-' . date('Ym') . '-' . str_pad($new_user_id, 5, '0', STR_PAD_LEFT);
            
            $queryPasien = "INSERT INTO Pasien (
                                id_pengguna, nomor_rekam_medis, nama_lengkap, nik, tempat_lahir, tanggal_lahir, 
                                jenis_kelamin, status_perkawinan, pekerjaan, pendidikan_terakhir, agama, golongan_darah, 
                                riwayat_penyakit, riwayat_alergi, status_bpjs, nomor_bpjs, kontak_darurat, 
                                foto_profil, file_ktp, file_kk, tanda_tangan
                            ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
            
            $stmtPasien = $this->conn->prepare($queryPasien);
            if (!$stmtPasien) throw new Exception("Prepare Pasien gagal: " . $this->conn->error);
            
            $stmtPasien->bind_param("issssssssssssssssssss", 
                $new_user_id, $nomor_rekam_medis, $data['nama_lengkap'], $data['nik'], $data['tempat_lahir'], $data['tanggal_lahir'],
                $data['jenis_kelamin'], $data['status_perkawinan'], $data['pekerjaan'], $data['pendidikan_terakhir'], $data['agama'], $data['golongan_darah'],
                $data['riwayat_penyakit'], $data['riwayat_alergi'], $data['status_bpjs'], $data['nomor_bpjs'], $data['kontak_darurat'],
                $nama_file_foto, $nama_file_ktp, $nama_file_kk, $data['tanda_tangan']
            );

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
     * Helper function untuk menangani upload file.
     * @param array|null $file Data dari $_FILES.
     * @param string $destination_path Path folder tujuan.
     * @param string $base_filename Nama dasar untuk file baru.
     * @return string|null Nama file yang disimpan, atau null jika gagal.
     */
    private function uploadFile($file, $destination_path, $base_filename) {
        if ($file && $file['error'] == 0) {
            $file_extension = pathinfo($file['name'], PATHINFO_EXTENSION);
            $new_filename = $base_filename . '-' . time() . '.' . $file_extension;
            if (move_uploaded_file($file['tmp_name'], $destination_path . $new_filename)) {
                return $new_filename;
            }
        }
        return null;
    }
}
