<?php
// File: app/views/superadmin/dashboard.php
require_once BASE_PATH . '/app/views/shared/header.php';

// Data ini seharusnya sudah disiapkan oleh SuperadminController
// $total_pengguna = ...
// $pendapatan_bulan_ini = ...
?>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2"><div class="col-sm-6"><h1 class="m-0">Dashboard Superadmin</h1></div></div>
        </div>
    </div>

    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-6 col-6">
                    <div class="small-box bg-purple"><div class="inner"><h3><?= $total_pengguna ?? 178 ?></h3><p>Total Pengguna Sistem</p></div><div class="icon"><i class="fas fa-user-shield"></i></div></div>
                </div>
                <div class="col-lg-6 col-6">
                    <div class="small-box bg-danger"><div class="inner"><h3>Rp <?= number_format($pendapatan_bulan_ini ?? 125000000, 0, ',', '.') ?></h3><p>Pendapatan Bulan Ini</p></div><div class="icon"><i class="fas fa-wallet"></i></div></div>
                </div>
            </div>
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header"><h3 class="card-title">Manajemen Cepat</h3></div>
                        <div class="card-body">
                            <a href="/pengguna/create" class="btn btn-app bg-secondary"><i class="fas fa-user-plus"></i> Tambah Pengguna</a>
                            <a href="/laporan/harian" class="btn btn-app bg-success"><i class="fas fa-file-invoice-dollar"></i> Laporan Harian</a>
                            <a href="/laporan/bulanan" class="btn btn-app bg-info"><i class="fas fa-chart-line"></i> Laporan Bulanan</a>
                            <a href="/pengaturan/klinik" class="btn btn-app bg-danger"><i class="fas fa-cogs"></i> Pengaturan Sistem</a>
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
