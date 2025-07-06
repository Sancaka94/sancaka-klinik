<?php
class Antrian {
    public static function hariIni() {
        global $conn;
        $tanggal = date('Y-m-d');
        $stmt = $conn->prepare("SELECT * FROM antrian WHERE tanggal = ? ORDER BY nomor ASC");
        $stmt->bind_param("s", $tanggal);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }
}
