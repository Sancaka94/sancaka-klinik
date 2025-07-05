<?php 
// File: views/auth/login.php
// Menggunakan layout untuk halaman publik
require_once __DIR__ . '/../layouts/header_public.php'; 
?>

<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-lg-6 text-center">
            <i class="bi bi-shield-lock-fill" style="font-size: 5rem; color: var(--primary-color);"></i>
            <h1 class="display-5 fw-bold mt-3">Akses Terbatas</h1>
            <p class="lead text-muted mb-4">Silakan login untuk melanjutkan ke dashboard Anda. Halaman ini hanya untuk pengguna terdaftar.</p>
            
            <!-- Tombol untuk memicu modal -->
            <button type="button" class="btn btn-primary btn-lg px-5" data-bs-toggle="modal" data-bs-target="#loginModal">
                Login
            </button>

            <div class="mt-4">
                <p class="text-muted">Belum punya akun? <a href="?url=auth/register">Daftar sebagai Pasien</a></p>
            </div>
        </div>
    </div>
</div>

<!-- Modal Login -->
<div class="modal fade" id="loginModal" tabindex="-1" aria-labelledby="loginModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rounded-4 shadow">
            <div class="modal-header p-5 pb-4 border-bottom-0">
                <h2 class="fw-bold mb-0 fs-3" id="loginModalLabel">Login ke Akun Anda</h2>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body p-5 pt-0">
                <form action="?url=auth/authenticate" method="POST">
                    
                    <!-- **FITUR BARU:** Pertanyaan validasi peran -->
                    <div class="form-floating mb-3">
                        <select class="form-select" id="id_peran" name="id_peran" required>
                            <option value="" disabled selected>-- Pilih peran Anda --</option>
                            <option value="4">Saya adalah Pasien</option>
                            <option value="3">Saya adalah Dokter</option>
                            <option value="2">Saya adalah Admin</option>
                            <option value="5">Saya adalah Owner Klinik</option>
                            <option value="1">Saya adalah Super Admin</option>
                        </select>
                        <label for="id_peran">Anda login sebagai...</label>
                    </div>

                    <div class="form-floating mb-3">
                        <input type="text" class="form-control rounded-3" id="username" name="username" placeholder="Email atau Username" required>
                        <label for="username">Email atau Username</label>
                    </div>
                    <div class="form-floating mb-3">
                        <input type="password" class="form-control rounded-3" id="password" name="password" placeholder="Password" required>
                        <label for="password">Password</label>
                    </div>
                    <button class="w-100 mb-2 btn btn-lg rounded-3 btn-primary" type="submit">Login</button>
                    <small class="text-muted">Dengan login, Anda menyetujui syarat dan ketentuan.</small>
                </form>
            </div>
        </div>
    </div>
</div>

<?php 
// Memanggil footer layout publik
require_once __DIR__ . '/../layouts/footer_public.php'; 
?>
