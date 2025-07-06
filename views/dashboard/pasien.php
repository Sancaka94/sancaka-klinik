<?php
// File: views/dashboard/pasien.php

// --- DATA DARI CONTROLLER ---
// Variabel-variabel ini seharusnya sudah disiapkan oleh DashboardController Anda.
// Saya menggunakan data placeholder di sini untuk tujuan desain.
// $user = $_SESSION['user'];
// $jumlah_kunjungan = ...
// $janji_aktif = ...
// $riwayat_rekam_medis = ...
// $janji_temu = ...
// $notifikasi_belum_dibaca = ...
// $notifikasi_list = ...

// Placeholder data untuk demonstrasi
$user = $_SESSION['user'] ?? ['nama_lengkap' => 'Nama Pasien', 'foto' => null];

// [FIXED] Menggunakan !empty() untuk memeriksa apakah 'foto' ada dan tidak kosong.
// Ini akan menghilangkan error "Undefined index".
$foto_profil_path = !empty($user['foto']) ? 'uploads/profil/' . htmlspecialchars($user['foto']) : 'https://placehold.co/128x128/343a40/ffffff?text=P';

$jumlah_kunjungan = 12;
$janji_aktif = 2;
$riwayat_rekam_medis = [
    ['id_rekam_medis' => 101, 'tanggal_kunjungan' => '2025-06-20', 'nama_dokter' => 'Dr. Budi Santoso', 'diagnosa' => 'Flu dan Batuk'],
    ['id_rekam_medis' => 95, 'tanggal_kunjungan' => '2025-05-15', 'nama_dokter' => 'Dr. Anisa Wijaya', 'diagnosa' => 'Sakit Kepala']
];
$janji_temu = [
    ['id_janji' => 205, 'tanggal_janji' => '2025-07-10', 'waktu_janji' => '10:00', 'nama_dokter' => 'Dr. Anisa Wijaya', 'status' => 'Dikonfirmasi'],
    ['id_janji' => 208, 'tanggal_janji' => '2025-07-18', 'waktu_janji' => '14:30', 'nama_dokter' => 'Dr. Budi Santoso', 'status' => 'Menunggu Konfirmasi']
];
$notifikasi_belum_dibaca = 3;
$notifikasi_list = [
    ['pesan' => 'Janji temu dikonfirmasi', 'waktu' => '3 jam lalu', 'icon' => 'fa-calendar-check', 'link' => '?url=janji/detail/205'],
    ['pesan' => 'Resep baru telah terbit', 'waktu' => '1 hari lalu', 'icon' => 'fa-file-prescription', 'link' => '?url=resep/detail/78'],
    ['pesan' => 'Pengingat jadwal kontrol', 'waktu' => '2 hari lalu', 'icon' => 'fa-info-circle', 'link' => '#']
];
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Dashboard Pasien | Klinik Sehat</title>

    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/css/adminlte.min.css">
</head>
<body class="hold-transition sidebar-mini">
<div class="wrapper">

    <!-- Navbar -->
    <nav class="main-header navbar navbar-expand navbar-white navbar-light">
        <!-- Left navbar links -->
        <ul class="navbar-nav">
            <li class="nav-item">
                <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
            </li>
        </ul>

        <!-- Right navbar links -->
        <ul class="navbar-nav ml-auto">
            <!-- Notifications Dropdown Menu -->
            <li class="nav-item dropdown">
                <a class="nav-link" data-toggle="dropdown" href="#">
                    <i class="far fa-bell"></i>
                    <?php if ($notifikasi_belum_dibaca > 0): ?>
                    <span class="badge badge-warning navbar-badge"><?= $notifikasi_belum_dibaca ?></span>
                    <?php endif; ?>
                </a>
                <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
                    <span class="dropdown-header"><?= $notifikasi_belum_dibaca ?> Notifikasi Baru</span>
                    <div class="dropdown-divider"></div>
                    <?php foreach($notifikasi_list as $notif): ?>
                    <a href="<?= $notif['link'] ?>" class="dropdown-item">
                        <i class="fas <?= $notif['icon'] ?> mr-2"></i> <?= htmlspecialchars($notif['pesan']) ?>
                        <span class="float-right text-muted text-sm"><?= $notif['waktu'] ?></span>
                    </a>
                    <div class="dropdown-divider"></div>
                    <?php endforeach; ?>
                    <a href="?url=notifikasi" class="dropdown-item dropdown-footer">Lihat Semua Notifikasi</a>
                </div>
            </li>
            <!-- User Menu Dropdown -->
            <li class="nav-item dropdown">
                <a class="nav-link" data-toggle="dropdown" href="#">
                    <img src="<?= $foto_profil_path ?>" class="img-circle elevation-2" alt="User Image" style="width: 28px; height: 28px; object-fit: cover;">
                </a>
                <div class="dropdown-menu dropdown-menu-sm dropdown-menu-right">
                    <a href="?url=profile/pengaturan" class="dropdown-item">
                        <i class="fas fa-cog mr-2"></i> Pengaturan Profil
                    </a>
                    <div class="dropdown-divider"></div>
                    <a href="?url=auth/logout" class="dropdown-item">
                        <i class="fas fa-sign-out-alt mr-2"></i> Logout
                    </a>
                </div>
            </li>
        </ul>
    </nav>
    <!-- /.navbar -->

    <!-- Main Sidebar Container -->
    <aside class="main-sidebar sidebar-dark-primary elevation-4">
        <!-- Brand Logo -->
        <a href="?url=dashboard" class="brand-link">
            <span class="brand-text font-weight-light">Klinik Sehat</span>
        </a>

        <!-- Sidebar -->
        <div class="sidebar">
            <!-- Sidebar user panel (optional) -->
            <div class="user-panel mt-3 pb-3 mb-3 d-flex">
                <div class="image">
                    <img src="<?= $foto_profil_path ?>" class="img-circle elevation-2" alt="User Image">
                </div>
                <div class="info">
                    <a href="?url=profile/pengaturan" class="d-block"><?= htmlspecialchars($user['nama_lengkap']) ?></a>
                </div>
            </div>

            <!-- Sidebar Menu -->
            <nav class="mt-2">
                <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                    <li class="nav-item">
                        <a href="?url=dashboard" class="nav-link active">
                            <i class="nav-icon fas fa-tachometer-alt"></i>
                            <p>Dashboard</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="?url=rekam_medis" class="nav-link">
                            <i class="nav-icon fas fa-file-medical-alt"></i>
                            <p>Rekam Medis</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="?url=janji" class="nav-link">
                            <i class="nav-icon fas fa-calendar-alt"></i>
                            <p>Janji Temu</p>
                        </a>
                    </li>
                    <li class="nav-header">AKUN</li>
                    <li class="nav-item">
                        <a href="?url=profile/pengaturan" class="nav-link">
                            <i class="nav-icon fas fa-user-cog"></i>
                            <p>Pengaturan</p>
                        </a>
                    </li>
                </ul>
            </nav>
            <!-- /.sidebar-menu -->
        </div>
        <!-- /.sidebar -->
    </aside>

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0">Dashboard</h1>
                    </div>
                </div>
            </div>
        </div>
        <!-- /.content-header -->

        <!-- Main content -->
        <section class="content">
            <div class="container-fluid">
                <!-- Info boxes -->
                <div class="row">
                    <div class="col-12 col-sm-6">
                        <div class="info-box">
                            <span class="info-box-icon bg-info elevation-1"><i class="fas fa-file-medical-alt"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text">Total Kunjungan</span>
                                <span class="info-box-number"><?= $jumlah_kunjungan ?></span>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-sm-6">
                        <div class="info-box mb-3">
                            <span class="info-box-icon bg-success elevation-1"><i class="fas fa-calendar-check"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text">Janji Temu Aktif</span>
                                <span class="info-box-number"><?= $janji_aktif ?></span>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- /.row -->

                <div class="mb-4">
                    <a href="?url=janji/create" class="btn btn-primary btn-lg shadow-sm">
                        <i class="fas fa-plus-circle mr-2"></i>Buat Janji Temu Baru
                    </a>
                </div>

                <div class="row">
                    <!-- Riwayat Rekam Medis -->
                    <div class="col-md-12">
                        <div class="card card-primary card-outline">
                            <div class="card-header">
                                <h3 class="card-title"><i class="fas fa-history mr-2"></i>Riwayat Rekam Medis</h3>
                            </div>
                            <div class="card-body table-responsive p-0">
                                <table class="table table-hover table-striped">
                                    <thead>
                                        <tr><th>Tanggal</th><th>Dokter</th><th>Diagnosa</th><th>Aksi</th></tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($riwayat_rekam_medis as $riwayat): ?>
                                        <tr>
                                            <td><?= htmlspecialchars($riwayat['tanggal_kunjungan']) ?></td>
                                            <td><?= htmlspecialchars($riwayat['nama_dokter']) ?></td>
                                            <td><?= htmlspecialchars($riwayat['diagnosa']) ?></td>
                                            <td><a href="?url=rekam_medis/detail/<?= $riwayat['id_rekam_medis'] ?>" class="btn btn-info btn-sm"><i class="fas fa-eye"></i> Detail</a></td>
                                        </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <!-- Janji Temu -->
                    <div class="col-md-12">
                        <div class="card card-success card-outline">
                            <div class="card-header">
                                <h3 class="card-title"><i class="fas fa-calendar-alt mr-2"></i>Janji Temu Anda</h3>
                            </div>
                            <div class="card-body table-responsive p-0">
                                <table class="table table-hover">
                                    <thead>
                                        <tr><th>Tanggal & Waktu</th><th>Dokter</th><th>Status</th><th>Aksi</th></tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($janji_temu as $janji): ?>
                                        <tr>
                                            <td><?= htmlspecialchars($janji['tanggal_janji']) ?> - <?= htmlspecialchars($janji['waktu_janji']) ?></td>
                                            <td><?= htmlspecialchars($janji['nama_dokter']) ?></td>
                                            <td>
                                                <?php $status_class = $janji['status'] == 'Dikonfirmasi' ? 'badge badge-success' : 'badge badge-warning'; ?>
                                                <span class='<?= $status_class ?>'><?= htmlspecialchars($janji['status']) ?></span>
                                            </td>
                                            <td>
                                                <a href="?url=janji/detail/<?= $janji['id_janji'] ?>" class="btn btn-secondary btn-sm"><i class="fas fa-search"></i> Detail</a>
                                                <?php if ($janji['status'] == 'Menunggu Konfirmasi'): ?>
                                                <a href="?url=janji/edit/<?= $janji['id_janji'] ?>" class="btn btn-warning btn-sm"><i class="fas fa-edit"></i> Ubah</a>
                                                <?php endif; ?>
                                            </td>
                                        </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->

    <!-- Main Footer -->
    <footer class="main-footer">
        <strong>Copyright &copy; 2025 <a href="#">Klinik Sehat</a>.</strong> All rights reserved.
    </footer>
</div>
<!-- ./wrapper -->

<!-- REQUIRED SCRIPTS -->
<!-- jQuery -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<!-- Bootstrap 4 -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
<!-- AdminLTE App -->
<script src="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/js/adminlte.min.js"></script>
</body>
</html>
