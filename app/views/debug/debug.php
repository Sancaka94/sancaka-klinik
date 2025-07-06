<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Monitor Debug Aplikasi Klinik</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body { background: #f8f9fa; padding: 20px; }
    .debug-box {
      background: white;
      border-radius: 10px;
      padding: 20px;
      box-shadow: 0 0 10px rgba(0,0,0,0.1);
    }
    pre {
      background: #eee;
      padding: 10px;
      border-radius: 5px;
      overflow-x: auto;
    }
  </style>
</head>
<body>
  <div class="container">
    <div class="debug-box">
      <h3 class="mb-4">🛠 Monitor Debug Sistem Klinik</h3>

      <h5>🧩 SESSION</h5>
      <pre><?php print_r($_SESSION); ?></pre>

      <h5>🌐 GET</h5>
      <pre><?php print_r($_GET); ?></pre>

      <h5>📨 POST</h5>
      <pre><?php print_r($_POST); ?></pre>

      <h5>📂 COOKIES</h5>
      <pre><?php print_r($_COOKIE); ?></pre>

      <h5>🔧 SERVER</h5>
      <pre><?php print_r($_SERVER); ?></pre>

      <h5>📁 FILES</h5>
      <pre><?php print_r($_FILES); ?></pre>

      <h5>🔒 USER</h5>
      <pre><?php print_r($_SESSION['user'] ?? 'Belum login'); ?></pre>

    </div>
  </div>
</body>
</html>
