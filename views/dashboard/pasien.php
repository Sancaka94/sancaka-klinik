<?php
// File: views/dashboard/pasien.php

// Memanggil header standar aplikasi Anda
// Asumsi header.php sudah memuat semua file CSS AdminLTE, Bootstrap, dan Font Awesome dari lokal
require_once __DIR__ . '/../layouts/header.php';

// --- DATA DARI CONTROLLER ---
// Di dalam DashboardController, Anda perlu mengambil data ini dari database dan mengirimkannya ke view ini.
// Contoh:
 $user = $_SESSION['user'];
 $jumlah_kunjungan = $this->rekamMedisModel->countKunjungan($user['id_pengguna']);
 $janji_aktif = $this->janjiTemuModel->countJanjiAktif($user['id_pengguna']);
 $riwayat_rekam_medis = $this->rekamMedisModel->getRiwayat($user['id_pengguna']);
 $janji_temu = $this->janjiTemuModel->getJanji($user['id_pengguna']);

// Placeholder data untuk demonstrasi
$user = $_SESSION['user'] ?? ['nama_lengkap' => 'Nama Pasien', 'foto' => null];
$jumlah_kunjungan = 12;
$janji_aktif = 2;
$riwayat_rekam_medis = [
    ['id_rekam_medis' => 101, 'tanggal' => '2025-06-20', 'dokter' => 'Dr. Budi Santoso', 'diagnosa' => 'Flu dan Batuk', 'tindakan' => 'Pemeriksaan Umum'],
    ['id_rekam_medis' => 95, 'tanggal' => '2025-05-15', 'dokter' => 'Dr. Anisa Wijaya', 'diagnosa' => 'Sakit Kepala', 'tindakan' => 'Konsultasi']
];
$janji_temu = [
    ['id_janji' => 205, 'tanggal' => '2025-07-10', 'waktu' => '10:00', 'dokter' => 'Dr. Anisa Wijaya', 'status' => 'Dikonfirmasi'],
    ['id_janji' => 208, 'tanggal' => '2025-07-18', 'waktu' => '14:30', 'dokter' => 'Dr. Budi Santoso', 'status' => 'Menunggu Konfirmasi']
];
?>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Dashboard Pasien</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="?url=dashboard">Home</a></li>
                        <li class="breadcrumb-item active">Dashboard</li>
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <!-- Info boxes -->
            <div class="row">
                <div class="col-12 col-sm-6 col-md-6">
                    <div class="info-box">
                        <span class="info-box-icon bg-info elevation-1"><i class="fas fa-file-medical-alt"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">Total Kunjungan</span>
                            <span class="info-box-number"><?= $jumlah_kunjungan ?></span>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-sm-6 col-md-6">
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
                                    <tr>
                                        <th>Tanggal</th>
                                        <th>Dokter</th>
                                        <th>Diagnosa</th>
                                        <th>Tindakan</th>
                                        <th style="width: 120px;">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($riwayat_rekam_medis as $riwayat): ?>
                                    <tr>
                                        <td><?= htmlspecialchars($riwayat['tanggal']) ?></td>
                                        <td><?= htmlspecialchars($riwayat['dokter']) ?></td>
                                        <td><?= htmlspecialchars($riwayat['diagnosa']) ?></td>
                                        <td><?= htmlspecialchars($riwayat['tindakan']) ?></td>
                                        <td>
                                            <a href="?url=rekam_medis/detail/<?= $riwayat['id_rekam_medis'] ?>" class="btn btn-info btn-sm" title="Lihat Detail">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                    <?php if (empty($riwayat_rekam_medis)): ?>
                                        <tr><td colspan="5" class="text-center py-4">Belum ada riwayat rekam medis.</td></tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Janji Temu Akan Datang -->
                <div class="col-md-12">
                    <div class="card card-success card-outline">
                        <div class="card-header">
                            <h3 class="card-title"><i class="fas fa-calendar-alt mr-2"></i>Janji Temu Anda</h3>
                        </div>
                        <div class="card-body table-responsive p-0">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Tanggal</th>
                                        <th>Waktu</th>
                                        <th>Dokter</th>
                                        <th>Status</th>
                                        <th style="width: 150px;">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($janji_temu as $janji): ?>
                                    <tr>
                                        <td><?= htmlspecialchars($janji['tanggal']) ?></td>
                                        <td><?= htmlspecialchars($janji['waktu']) ?></td>
                                        <td><?= htmlspecialchars($janji['dokter']) ?></td>
                                        <td>
                                            <?php 
                                                $status_class = $janji['status'] == 'Dikonfirmasi' ? 'badge bg-success' : 'badge bg-warning';
                                                echo "<span class='{$status_class}'>" . htmlspecialchars($janji['status']) . "</span>";
                                            ?>
                                        </td>
                                        <td>
                                            <?php if ($janji['status'] == 'Menunggu Konfirmasi'): ?>
                                            <a href="?url=janji/edit/<?= $janji['id_janji'] ?>" class="btn btn-secondary btn-sm" title="Ubah Jadwal">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <a href="?url=janji/cancel/<?= $janji['id_janji'] ?>" class="btn btn-danger btn-sm" title="Batalkan Janji" onclick="return confirm('Apakah Anda yakin ingin membatalkan janji temu ini?')">
                                                <i class="fas fa-times-circle"></i>
                                            </a>
                                            <?php else: ?>
                                            <a href="?url=janji/detail/<?= $janji['id_janji'] ?>" class="btn btn-info btn-sm" title="Lihat Detail">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                    <?php if (empty($janji_temu)): ?>
                                        <tr><td colspan="5" class="text-center py-4">Tidak ada janji temu yang akan datang.</td></tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

        </div><!--/. container-fluid -->
    </section>
    <!-- /.content -->
</div>
<!-- /.content-wrapper -->

<?php
// Memanggil footer standar aplikasi Anda
// Asumsi footer.php sudah memuat semua file JS jQuery, Bootstrap, dan AdminLTE dari lokal
require_once __DIR__ . '/../layouts/footer.php';
?>
