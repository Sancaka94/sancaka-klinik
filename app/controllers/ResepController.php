<?php
require_once 'models/Resep.php';

class ResepController {
    public static function detail($resep_id) {
        $resep = Resep::findById($resep_id);
        $resep_detail = Resep::getDetail($resep_id);
        $pasien = Resep::getPasienFromResep($resep_id);
        $dokter = Resep::getDokterFromResep($resep_id);
        include 'views/resep/detail.php';
    }
}
