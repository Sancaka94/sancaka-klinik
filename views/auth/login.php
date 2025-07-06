<?php
// File: views/auth/login.php
// Versi ini menggunakan Bootstrap 5 untuk tampilan yang bersih dan profesional.

// Memanggil header. Pastikan header.php memuat file bootstrap.min.css.
require_once __DIR__ . '/../layouts/header.php';
?>

<div class="container">
    <div class="row justify-content-center align-items-center" style="min-height: 80vh;">
        <div class="col-md-6 col-lg-5">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-primary text-white text-center py-3">
                    <h4 class="mb-0">Login Akun</h4>
                </div>
                <div class="card-body p-4">
                    <p class="text-center text-muted mb-4">Silakan masuk untuk melanjutkan.</p>

                    <?php
                    // Menampilkan pesan error jika ada
                    if (isset($_GET['error'])) {
                        echo '<div class="alert alert-danger" role="alert">' . htmlspecialchars($_GET['error']) . '</div>';
                    }
                    // Menampilkan pesan sukses jika ada
                    if (isset($_GET['status']) && $_GET['status'] === 'registrasi_sukses') {
                        echo '<div class="alert alert-success" role="alert">Registrasi berhasil! Silakan login.</div>';
                    }
                    ?>

                    <form action="?url=auth/authenticate" method="POST">
                        <!-- Input Username/Email -->
                        <div class="mb-3">
                            <label for="username" class="form-label">Username atau Email</label>
                            <input type="text" class="form-control" id="username" name="username" placeholder="Masukkan username atau email" required>
                        </div>

                        <!-- Input Password -->
                        <div class="mb-3">
                            <label for="password" class="form-label">Password</label>
                            <input type="password" class="form-control" id="password" name="password" placeholder="Masukkan password" required>
                        </div>

                        <!-- Pilihan Peran/Role -->
                        <div class="mb-4">
                            <label for="id_peran" class="form-label">Login sebagai</label>
                            <select class="form-select" id="id_peran" name="id_peran" required>
                                <option value="" disabled selected>-- Pilih Peran --</option>
                                <option value="4">Pasien</option>
                                <option value="3">Dokter</option>
                                <option value="2">Admin</option>
                                <option value="1">Super Admin</option>
                                <option value="5">Owner</option>
                            </select>
                        </div>

                        <!-- Tombol Submit -->
                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary">Login</button>
                        </div>
                    </form>
                    
                    <hr class="my-4">

                    <div class="text-center">
                        <p class="text-muted mb-2">Belum punya akun?</p>
                        <a href="?url=auth/register" class="btn btn-outline-secondary btn-sm">Daftar sebagai Pasien</a>
                        <a href="?url=auth/register_dokter" class="btn btn-outline-success btn-sm">Daftar sebagai Dokter</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
// Memanggil footer. Pastikan footer.php memuat file bootstrap.bundle.min.js.
require_once __DIR__ . '/../layouts/footer.php';
?>
