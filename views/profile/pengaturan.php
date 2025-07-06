<?php
// File: views/profile/pengaturan.php

// Memanggil header
require_once __DIR__ . '/../layouts/header.php';

// Variabel $user sudah dikirim dari ProfileController
$default_avatar = 'https://placehold.co/150x150/E2E8F0/4A5568?text=Profil';
$foto_profil_path = (!empty($user['foto_profil'])) 
    ? 'uploads/profiles/' . htmlspecialchars($user['foto_profil']) 
    : $default_avatar;
?>

<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-lg-8">

            <!-- Tombol Kembali -->
            <div class="mb-3">
                <a href="?url=dashboard/pasien" class="btn btn-outline-secondary btn-sm">
                    &larr; Kembali ke Dashboard
                </a>
            </div>

            <div class="card shadow-sm border-0">
                <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">Pengaturan Profil</h4>
                    <div id="edit-button-container">
                         <button id="edit-btn" class="btn btn-light btn-sm">Edit Profil</button>
                    </div>
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

                    <form id="profile-form" action="?url=profile/update" method="POST" enctype="multipart/form-data">
                        <div class="text-center mb-4">
                            <img src="<?php echo $foto_profil_path; ?>" alt="Foto Profil" class="rounded-circle mb-2" style="width: 120px; height: 120px; object-fit: cover;" onerror="this.onerror=null;this.src='<?php echo $default_avatar; ?>';">
                            <h5 class="card-title"><?php echo htmlspecialchars($user['nama_lengkap']); ?></h5>
                            <p class="text-muted"><?php echo htmlspecialchars($user['email']); ?></p>
                        </div>

                        <fieldset id="profile-fieldset" disabled>
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

                            <?php // PERBAIKAN: Tampilkan field khusus hanya jika peran adalah Dokter (id_peran = 3) ?>
                            <?php if (isset($user['id_peran']) && $user['id_peran'] == 3): ?>
                                <div class="row">
                                    <!-- Spesialisasi -->
                                    <div class="col-md-6 mb-3">
                                        <label for="spesialisasi" class="form-label">Spesialisasi</label>
                                        <input type="text" class="form-control" id="spesialisasi" name="spesialisasi" value="<?php echo htmlspecialchars($user['spesialisasi'] ?? ''); ?>">
                                    </div>
                                    <!-- Nomor STR -->
                                    <div class="col-md-6 mb-3">
                                        <label for="nomor_str" class="form-label">Nomor STR</label>
                                        <input type="text" class="form-control" id="nomor_str" name="nomor_str" value="<?php echo htmlspecialchars($user['nomor_str'] ?? ''); ?>">
                                    </div>
                                </div>
                            <?php endif; ?>

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
                        </fieldset>

                        <!-- Tombol Aksi (Simpan & Batal) -->
                        <div id="action-buttons-container" class="d-grid gap-2 d-md-flex justify-content-md-end d-none">
                            <button id="cancel-btn" type="button" class="btn btn-secondary">Batal</button>
                            <button id="save-btn" type="submit" class="btn btn-primary">Simpan Perubahan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// ... (JavaScript tetap sama) ...
document.addEventListener('DOMContentLoaded', function () {
    const fieldset = document.getElementById('profile-fieldset');
    const editBtnContainer = document.getElementById('edit-button-container');
    const actionBtnsContainer = document.getElementById('action-buttons-container');
    const editBtn = document.getElementById('edit-btn');
    const cancelBtn = document.getElementById('cancel-btn');
    const profileForm = document.getElementById('profile-form');
    const initialFormState = new FormData(profileForm);
    function enableEditing() {
        fieldset.disabled = false;
        editBtnContainer.classList.add('d-none');
        actionBtnsContainer.classList.remove('d-none');
    }
    function disableEditing() {
        fieldset.disabled = true;
        editBtnContainer.classList.remove('d-none');
        actionBtnsContainer.classList.add('d-none');
        for (let [key, value] of initialFormState.entries()) {
            const field = profileForm.elements[key];
            if (field) {
                if (field.type === 'file') {
                    field.value = '';
                } else {
                    field.value = value;
                }
            }
        }
    }
    if (editBtn) { editBtn.addEventListener('click', enableEditing); }
    if (cancelBtn) { cancelBtn.addEventListener('click', disableEditing); }
});
</script>

<?php
// Memanggil footer
require_once __DIR__ . '/../layouts/footer.php';
?>
