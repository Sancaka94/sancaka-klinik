<?php
// File: views/profile/pengaturan.php

// Memanggil header standar aplikasi Anda
// Asumsi header.php sudah memuat file CSS Bootstrap dari lokal
require_once __DIR__ . '/../layouts/header.php';

// Variabel $data_profil seharusnya sudah dikirim dari ProfileController
// Contoh: $data_profil = $this->userModel->find($id_pengguna); 
?>

<div class="container my-4 my-md-5">
    
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0">Pengaturan Profil</h1>
        <a href="?url=dashboard" class="btn btn-outline-secondary btn-sm">
            <i class="fas fa-arrow-left me-2"></i>Kembali ke Dashboard
        </a>
    </div>

    <!-- Notifikasi -->
    <?php if (isset($_GET['status']) && $_GET['status'] == 'update_success'): ?>
        <div id="alert-sukses-profil" class="alert alert-success alert-dismissible fade show" role="alert">
            <strong>Sukses!</strong> Profil Anda berhasil diperbarui.
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>
    <?php if (isset($_GET['error'])): ?>
        <div id="alert-error-profil" class="alert alert-danger alert-dismissible fade show" role="alert">
            <strong>Gagal!</strong> <?= htmlspecialchars(urldecode($_GET['error'])) ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <div class="row g-4">
        
        <!-- Kolom Kiri: Foto Profil & Form Update Profil -->
        <div class="col-lg-8">
            <div class="card shadow-sm">
                <div class="card-header bg-white">
                    <h5 class="mb-0">Informasi Akun</h5>
                </div>
                <div class="card-body p-4">
                    <form action="?url=profile/update" method="post" enctype="multipart/form-data" id="form-profil">
                        <div class="row align-items-center">
                            <div class="col-md-4 text-center mb-4 mb-md-0">
                                <img id="preview-container" src="<?= isset($data_profil['foto']) && $data_profil['foto'] ? 'uploads/profil/' . htmlspecialchars($data_profil['foto']) : 'https://placehold.co/150x150/e2e8f0/a0aec0?text=Foto' ?>" class="img-thumbnail rounded-circle" style="width: 150px; height: 150px; object-fit: cover;" alt="Foto Profil">
                                <label for="foto" class="btn btn-sm btn-outline-primary mt-3">
                                    <i class="fas fa-upload me-1"></i> Ubah Foto
                                </label>
                                <input type="file" name="foto" id="foto" class="d-none" onchange="previewImage(event)">
                                <p class="form-text mt-2">JPG, PNG, GIF (Maks 2MB)</p>
                            </div>
                            <div class="col-md-8">
                                <div class="mb-3">
                                    <label for="nama_lengkap" class="form-label">Nama Lengkap</label>
                                    <input type="text" name="nama_lengkap" id="nama_lengkap" value="<?= htmlspecialchars($data_profil['nama_lengkap'] ?? '') ?>" class="form-control">
                                </div>
                                <div class="mb-3">
                                    <label for="username" class="form-label">Username</label>
                                    <input type="text" id="username" value="<?= htmlspecialchars($data_profil['username'] ?? '') ?>" class="form-control" disabled>
                                </div>
                                <div class="mb-3">
                                    <label for="email" class="form-label">Email</label>
                                    <input type="email" name="email" id="email" value="<?= htmlspecialchars($data_profil['email'] ?? '') ?>" class="form-control">
                                </div>
                                <div class="mb-3">
                                    <label for="no_telepon" class="form-label">No. Telepon</label>
                                    <input type="tel" name="no_telepon" id="no_telepon" value="<?= htmlspecialchars($data_profil['no_telepon'] ?? '') ?>" class="form-control">
                                </div>
                            </div>
                        </div>

                        <!-- Field khusus untuk Dokter -->
                        <?php if (isset($data_profil['id_peran']) && $data_profil['id_peran'] == 3): ?>
                            <hr class="my-4">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="spesialisasi" class="form-label">Spesialisasi</label>
                                    <input type="text" name="spesialisasi" id="spesialisasi" value="<?= htmlspecialchars($data_profil['spesialisasi'] ?? '') ?>" class="form-control">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="nomor_str" class="form-label">Nomor STR</label>
                                    <input type="text" name="nomor_str" id="nomor_str" value="<?= htmlspecialchars($data_profil['nomor_str'] ?? '') ?>" class="form-control">
                                </div>
                            </div>
                        <?php endif; ?>

                        <div class="mb-3">
                           <label for="alamat" class="form-label">Alamat</label>
                           <textarea name="alamat" id="alamat" rows="3" class="form-control"><?= htmlspecialchars($data_profil['alamat'] ?? '') ?></textarea>
                        </div>
                        <div class="d-flex justify-content-end mt-4">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-2"></i>Simpan Perubahan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Kolom Kanan: Form Ubah Password -->
        <div class="col-lg-4">
             <div class="card shadow-sm">
                <div class="card-header bg-white">
                    <h5 class="mb-0">Ubah Password</h5>
                </div>
                <div class="card-body p-4">
                    <!-- Notifikasi Password -->
                    <?php if (isset($_GET['status_pass']) && $_GET['status_pass'] == 'update_success'): ?>
                        <div id="alert-sukses-pass" class="alert alert-success" role="alert">
                            Password berhasil diperbarui.
                        </div>
                    <?php endif; ?>
                    <?php if (isset($_GET['error_pass'])): ?>
                        <div id="alert-error-pass" class="alert alert-danger" role="alert">
                            <?= htmlspecialchars(urldecode($_GET['error_pass'])) ?>
                        </div>
                    <?php endif; ?>

                    <form action="?url=profile/updatePassword" method="post">
                        <div class="mb-3">
                            <label for="old_password" class="form-label">Password Lama</label>
                            <input type="password" name="old_password" id="old_password" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label for="new_password" class="form-label">Password Baru</label>
                            <input type="password" name="new_password" id="new_password" class="form-control" required>
                            <div class="form-text">Minimal 8 karakter.</div>
                        </div>
                        <div class="mb-3">
                            <label for="confirm_password" class="form-label">Konfirmasi Password Baru</label>
                            <input type="password" name="confirm_password" id="confirm_password" class="form-control" required>
                        </div>
                        <div class="d-grid mt-4">
                            <button type="submit" class="btn btn-dark">
                                <i class="fas fa-key me-2"></i>Ubah Password
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // Fungsi untuk menampilkan preview gambar sebelum diunggah
    function previewImage(event) {
        const reader = new FileReader();
        reader.onload = function(){
            const output = document.getElementById('preview-container');
            output.src = reader.result;
        };
        reader.readAsDataURL(event.target.files[0]);
    }

    // Fungsi untuk menghilangkan notifikasi setelah beberapa detik
    window.setTimeout(function() {
        const alerts = document.querySelectorAll('#alert-sukses-pass, #alert-error-pass');
        alerts.forEach(alert => {
            if (alert) {
                new bootstrap.Alert(alert).close();
            }
        });
    }, 5000); // Notifikasi hilang setelah 5 detik
</script>

<?php
// Memanggil footer standar aplikasi Anda
// Asumsi footer.php sudah memuat file JS Bootstrap dari lokal
require_once __DIR__ . '/../layouts/footer.php';
?>

