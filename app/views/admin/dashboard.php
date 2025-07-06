<?php
// File: app/views/admin/dashboard.php
require_once BASE_PATH . '/app/views/shared/header.php';

// Data ini seharusnya sudah disiapkan oleh AdminController
// $total_pasien = ...
// $janji_hari_ini = ...
?>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6"><h1 class="m-0">Dashboard Admin</h1></div>
            </div>
        </div>
    </div>

    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-6 col-6">
                    <div class="small-box bg-info">
                        <div class="inner">
                            <h3><?= $total_pasien ?? 150 ?></h3>
                            <p>Total Pasien Terdaftar</p>
                        </div>
                        <div class="icon"><i class="fas fa-user-friends"></i></div>
                        <a href="/pasien/manage" class="small-box-footer">Lihat Detail <i class="fas fa-arrow-circle-right"></i></a>
                    </div>
                </div>
                <div class="col-lg-6 col-6">
                    <div class="small-box bg-warning">
                        <div class="inner">
                            <h3><?= $janji_hari_ini ?? 8 ?></h3>
                            <p>Janji Temu Hari Ini</p>
                        </div>
                        <div class="icon"><i class="fas fa-calendar-day"></i></div>
                        <a href="/janji/manage" class="small-box-footer">Lihat Detail <i class="fas fa-arrow-circle-right"></i></a>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-12">
                    <div class="card card-primary card-outline">
                        <div class="card-header"><h3 class="card-title"><i class="fas fa-tasks mr-2"></i>Aksi Cepat</h3></div>
                        <div class="card-body">
                            <a href="/pasien/create" class="btn btn-app bg-success"><i class="fas fa-user-plus"></i> Daftarkan Pasien</a>
                            <a href="/janji/create" class="btn btn-app bg-info"><i class="fas fa-calendar-plus"></i> Buat Janji Temu</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
<?php
require_once BASE_PATH . '/app/views/shared/footer.php';
?>
