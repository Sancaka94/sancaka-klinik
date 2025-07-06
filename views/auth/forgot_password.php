<?php
// File: views/auth/forgot_password.php
require_once __DIR__ . '/../layouts/header.php';
?>
<style>
    .forgot-container { min-height: 80vh; display: flex; align-items: center; justify-content: center; }
</style>
<div class="container forgot-container">
    <div class="col-md-6 col-lg-5">
        <div class="card shadow-sm border-0">
            <div class="card-body p-4 p-md-5">
                <div class="text-center mb-4">
                    <h3 class="fw-bold">Lupa Password</h3>
                    <p class="text-muted">Masukkan email Anda. Kami akan mengirimkan link reset password ke WhatsApp Admin.</p>
                </div>
                <form action="?url=auth/send_reset_link" method="POST">
                    <div class="mb-3">
                        <label for="email" class="form-label">Email Terdaftar</label>
                        <input type="email" class="form-control" id="email" name="email" placeholder="contoh@email.com" required>
                    </div>
                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary">Kirim Link Reset</button>
                    </div>
                </form>
                <div class="text-center mt-3">
                    <a href="?url=auth/login" class="text-muted small">&larr; Kembali ke Login</a>
                </div>
            </div>
        </div>
    </div>
</div>
<?php
require_once __DIR__ . '/../layouts/footer.php';
?>
