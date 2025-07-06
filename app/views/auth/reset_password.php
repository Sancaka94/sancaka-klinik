<?php
// File: views/auth/reset_password.php
require_once __DIR__ . '/../layouts/header.php';

// Mengambil token dari URL untuk disertakan dalam form
$token = $_GET['token'] ?? '';
?>
<style>
    .reset-container { min-height: 80vh; display: flex; align-items: center; justify-content: center; }
</style>
<div class="container reset-container">
    <div class="col-md-6 col-lg-5">
        <div class="card shadow-sm border-0">
            <div class="card-body p-4 p-md-5">
                <div class="text-center mb-4">
                    <h3 class="fw-bold">Atur Ulang Password</h3>
                    <p class="text-muted">Masukkan password baru Anda di bawah ini.</p>
                </div>

                <?php
                // Menampilkan pesan error jika ada
                if (isset($_GET['error'])) {
                    echo '<div class="alert alert-danger" role="alert">' . htmlspecialchars($_GET['error']) . '</div>';
                }
                ?>

                <form action="?url=auth/update_password" method="POST">
                    <!-- Token reset yang tersembunyi -->
                    <input type="hidden" name="token" value="<?php echo htmlspecialchars($token); ?>">

                    <!-- Input Password Baru -->
                    <div class="mb-3">
                        <label for="password" class="form-label">Password Baru</label>
                        <input type="password" class="form-control" id="password" name="password" required>
                    </div>

                    <!-- Input Konfirmasi Password Baru -->
                    <div class="mb-3">
                        <label for="confirm_password" class="form-label">Konfirmasi Password Baru</label>
                        <input type="password" class="form-control" id="confirm_password" name="confirm_password" required>
                    </div>

                    <div class="d-grid mt-4">
                        <button type="submit" class="btn btn-primary">Simpan Password Baru</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<?php
require_once __DIR__ . '/../layouts/footer.php';
?>
