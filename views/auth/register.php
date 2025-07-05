<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Register Pasien</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">

  <style>
    body { background-color: #f8f9fa; }
    .register-box {
      max-width: 500px;
      margin: 60px auto;
      padding: 30px;
      background: #fff;
      border-radius: 10px;
      box-shadow: 0 0 15px rgba(0,0,0,0.1);
    }
  </style>
</head>
<body>

<div class="container">
  <div class="register-box">
    <?php if (!empty($_GET['error'])): ?>
        <div class="alert alert-danger text-center">
        <?= htmlspecialchars($_GET['error']) ?>
        </div>
    <?php elseif (!empty($_GET['success'])): ?>
  <div class="alert alert-success text-center">
    <?= htmlspecialchars($_GET['success']) ?>
  </div>
    <?php endif; ?>

    <h3 class="text-center mb-4">Registrasi Pasien Baru</h3>

    <form action="?url=auth/processRegister" method="POST">
      <div class="mb-3">
        <label class="form-label">Alamat Email</label>
        <input type="email" name="email" class="form-control" required>
      </div>

      <div class="mb-3">
        <label class="form-label">Nomor Telepon</label>
        <input type="text" name="nomor_telepon" class="form-control" required>
      </div>

      <div class="mb-3">
        <label class="form-label">Kata Sandi</label>
        <div class="input-group">
          <input type="password" name="password" id="password" class="form-control" required>
          <button type="button" class="btn btn-outline-secondary" onclick="togglePassword()">
            <i class="bi bi-eye" id="toggleIcon"></i>
          </button>
        </div>
      </div>

      <div class="d-grid mb-3">
        <button type="submit" class="btn btn-success">Daftar Sekarang</button>
      </div>

      <div class="text-center">
        <a href="?url=auth/login" class="text-decoration-none">Sudah punya akun? Login di sini</a>
      </div>
    </form>
  </div>
</div>

<script>
  function togglePassword() {
    const input = document.getElementById("password");
    const icon = document.getElementById("toggleIcon");
    if (input.type === "password") {
      input.type = "text";
      icon.classList.remove("bi-eye");
      icon.classList.add("bi-eye-slash");
    } else {
      input.type = "password";
      icon.classList.remove("bi-eye-slash");
      icon.classList.add("bi-eye");
    }
  }
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
