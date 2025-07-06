<?php
class Resep {
    public static function findById($id) {
        global $conn;
        $stmt = $conn->prepare("SELECT * FROM resep WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    public static function getDetail($resep_id) {
        global $conn;
        $stmt = $conn->prepare("SELECT * FROM resep_detail WHERE resep_id = ?");
        $stmt->bind_param("i", $resep_id);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }
}
