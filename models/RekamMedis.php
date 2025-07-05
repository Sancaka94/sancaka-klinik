<?php
class RekamMedis {
    public static function byPasien($pasien_id) {
        global $conn;
        $stmt = $conn->prepare("SELECT * FROM rekam_medis WHERE pasien_id = ?");
        $stmt->bind_param("i", $pasien_id);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }
}
