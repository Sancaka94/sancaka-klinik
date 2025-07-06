<?php
class HasilLab {
    public static function byKunjungan($kunjungan_id) {
        global $conn;
        $stmt = $conn->prepare("SELECT * FROM hasil_lab WHERE kunjungan_id = ?");
        $stmt->bind_param("i", $kunjungan_id);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }
}
