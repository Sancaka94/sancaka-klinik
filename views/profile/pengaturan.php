<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pengaturan Profil</title>
    <style>
        body { font-family: sans-serif; line-height: 1.6; padding: 20px; }
        .profile-container { max-width: 800px; margin: auto; padding: 20px; border: 1px solid #ddd; border-radius: 8px; }
        h1 { color: #333; }
        .profile-field { margin-bottom: 10px; }
        .profile-field strong { display: inline-block; width: 150px; }
    </style>
</head>
<body>

    <div class="profile-container">
        <h1>Profil Pasien</h1>
        <p>Berikut adalah data profil Anda yang tersimpan di sistem kami.</p>
        <hr>

        <!-- Variabel $data_pasien berasal dari ProfileController -->
        <div class="profile-field">
            <strong>Nama Lengkap:</strong>
            <span><?php echo htmlspecialchars($data_pasien['nama_lengkap']); ?></span>
        </div>
        <div class="profile-field">
            <strong>Email:</strong>
            <span><?php echo htmlspecialchars($data_pasien['email']); ?></span>
        </div>
        <div class="profile-field">
            <strong>NIK:</strong>
            <span><?php echo htmlspecialchars($data_pasien['nik']); ?></span>
        </div>
        <div class="profile-field">
            <strong>Nomor Telepon:</strong>
            <span><?php echo htmlspecialchars($data_pasien['nomor_telepon']); ?></span>
        </div>
        <div class="profile-field">
            <strong>Alamat:</strong>
            <span><?php echo htmlspecialchars($data_pasien['alamat']); ?></span>
        </div>
        
        <!-- Tambahkan field lain sesuai kebutuhan -->

    </div>

</body>
</html>
