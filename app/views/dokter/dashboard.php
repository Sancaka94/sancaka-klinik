<?php
// File: app/views/dokter/dashboard.php
require_once BASE_PATH . '/app/views/shared/header.php';

// Data ini seharusnya sudah disiapkan oleh DokterController
// $janji_hari_ini = ...
?>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2"><div class="col-sm-6"><h1 class="m-0">Dashboard Dokter</h1></div></div>
        </div>
    </div>

    <section class="content">
        <div class="container-fluid">
             <div class="row">
                <div class="col-12">
                    <div class="card card-primary card-outline">
                        <div class="card-header"><h3 class="card-title"><i class="fas fa-list-ul mr-1"></i>Daftar Janji Temu Anda Hari Ini</h3></div>
                        <div class="card-body table-responsive p-0">
                            <table class="table table-hover text-nowrap">
                                <thead><tr><th>Waktu</th><th>Nama Pasien</th><th>Keluhan</th><th>Status</th><th>Aksi</th></tr></thead>
                                <tbody>
                                    <?php if (!empty($janji_hari_ini)): ?>
                                        <?php foreach($janji_hari_ini as $janji): ?>
                                            <tr>
                                                <td><?= htmlspecialchars($janji['waktu_janji']) ?></td>
                                                <td><?= htmlspecialchars($janji['nama_pasien']) ?></td>
                                                <td><?= htmlspecialchars($janji['keluhan']) ?></td>
                                                <td><span class="badge bg-warning"><?= htmlspecialchars($janji['status']) ?></span></td>
                                                <td><a href="/konsultasi/mulai/<?= $janji['id_janji'] ?>" class="btn btn-sm btn-primary">Mulai Konsultasi</a></td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <tr><td colspan="5" class="text-center py-4">Tidak ada janji temu hari ini.</td></tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
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
