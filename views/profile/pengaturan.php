<?php
// File: views/profile/pengaturan.php

// Memanggil header
require_once __DIR__ . '/../layouts/header.php';

// Variabel $user sudah dikirim dari ProfileController
// Kita siapkan path foto profil untuk ditampilkan
$default_avatar = 'https://placehold.co/150x150/E2E8F0/4A5568?text=Profil';
$foto_profil_path = (!empty($user['foto_profil'])) 
    ? 'uploads/profiles/' . htmlspecialchars($user['foto_profil']) 
    : $default_avatar;
?>

<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0">Pengaturan Profil</h4>
                </div>
                <div class="card-body p-4">

                    <?php
                    // Menampilkan pesan notifikasi
                    if (isset($_GET['error'])) {
                        echo '<div class="alert alert-danger" role="alert">' . htmlspecialchars($_GET['error']) . '</div>';
                    }
                    if (isset($_GET['status']) && $_GET['status'] === 'update_sukses') {
                        echo '<div class="alert alert-success" role="alert">Profil berhasil diperbarui!</div>';
                    }
                    ?>

                    <form action="?url=profile/update" method="POST" enctype="multipart/form-data">
                        <!-- Foto Profil -->
                        <div class="text-center mb-4">
                            <img src="<?php echo $foto_profil_path; ?>" alt="Foto Profil" class="rounded-circle mb-2" style="width: 120px; height: 120px; object-fit: cover;" onerror="this.onerror=null;this.src='<?php echo $default_avatar; ?>';">
                            <h5 class="card-title"><?php echo htmlspecialchars($user['nama_lengkap']); ?></h5>
                            <p class="text-muted"><?php echo htmlspecialchars($user['email']); ?></p>
                        </div>

                        <div class="row">
                            <!-- Nama Lengkap -->
                            <div class="col-md-6 mb-3">
                                <label for="nama_lengkap" class="form-label">Nama Lengkap</label>
                                <input type="text" class="form-control" id="nama_lengkap" name="nama_lengkap" value="<?php echo htmlspecialchars($user['nama_lengkap']); ?>" required>
                            </div>

                            <!-- Email -->
                            <div class="col-md-6 mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" class="form-control" id="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" required>
                            </div>
                        </div>

                        <!-- Ganti Password -->
                        <div class="mb-3">
                            <label for="password" class="form-label">Ganti Password (Opsional)</label>
                            <input type="password" class="form-control" id="password" name="password" placeholder="Kosongkan jika tidak ingin mengubah password">
                        </div>

                        <!-- Ganti Foto Profil -->
                        <div class="mb-4">
                            <label for="foto_profil" class="form-label">Ganti Foto Profil (Opsional)</label>
                            <input class="form-control" type="file" id="foto_profil" name="foto_profil">
                        </div>

                        <!-- Tombol Submit -->
                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
// Memanggil footer
require_once __DIR__ . '/../layouts/footer.php';
?>
