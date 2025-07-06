<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pendaftaran Dokter Baru</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f4f7f9;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            padding: 20px;
        }
        .container {
            max-width: 600px;
            width: 100%;
            background: white;
            border-radius: 12px;
            box-shadow: 0 8px 24px rgba(0,0,0,0.1);
            overflow: hidden;
        }
        .form-header {
            background: linear-gradient(135deg, #28a745, #218838);
            color: white;
            padding: 25px 30px;
            text-align: center;
        }
        .form-header h1 {
            margin: 0;
            font-size: 1.8em;
        }
        form {
            padding: 30px;
        }
        .form-section {
            margin-bottom: 25px;
        }
        .form-group {
            margin-bottom: 20px;
        }
        .form-group label {
            display: block;
            font-weight: 600;
            margin-bottom: 8px;
            color: #495057;
        }
        .form-group input, .form-group select {
            width: 100%;
            padding: 12px;
            border: 1px solid #ced4da;
            border-radius: 8px;
            font-size: 1em;
            transition: border-color 0.2s, box-shadow 0.2s;
        }
        .form-group input:focus, .form-group select:focus {
            outline: none;
            border-color: #28a745;
            box-shadow: 0 0 0 0.2rem rgba(40,167,69,.25);
        }
        .submit-btn {
            width: 100%;
            padding: 15px;
            font-size: 1.1em;
            font-weight: bold;
            color: white;
            background-color: #007bff;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            transition: background-color 0.2s;
        }
        .submit-btn:hover {
            background-color: #0056b3;
        }
        .login-link {
            text-align: center;
            margin-top: 20px;
            font-size: 0.9em;
        }
        .login-link a {
            color: #007bff;
            text-decoration: none;
            font-weight: 600;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="form-header">
            <h1>Form Pendaftaran Dokter</h1>
        </div>
        <form action="?url=auth/processRegisterDokter" method="POST" enctype="multipart/form-data">
            <div class="form-section">
                <div class="form-group">
                    <label for="nama_lengkap">Nama Lengkap (dengan gelar)</label>
                    <input type="text" id="nama_lengkap" name="nama_lengkap" placeholder="Contoh: Dr. Agus Salim" required>
                </div>
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" required>
                </div>
                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" required>
                </div>
            </div>
            <div class="form-section">
                <div class="form-group">
                    <label for="spesialisasi">Spesialisasi</label>
                    <input type="text" id="spesialisasi" name="spesialisasi" placeholder="Contoh: Dokter Umum, Dokter Gigi" required>
                </div>
                <div class="form-group">
                    <label for="nomor_str">Nomor STR (Surat Tanda Registrasi)</label>
                    <input type="text" id="nomor_str" name="nomor_str" required>
                </div>
                 <div class="form-group">
                    <label for="foto_profil">Foto Profil</label>
                    <input type="file" id="foto_profil" name="foto_profil" accept="image/*">
                </div>
            </div>
            <button type="submit" class="submit-btn">Daftarkan Akun Dokter</button>
            <div class="login-link">
                Sudah punya akun? <a href="?url=auth/login">Login di sini</a>
            </div>
        </form>
    </div>
</body>
</html>
