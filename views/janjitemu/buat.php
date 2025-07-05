<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Buat Janji Temu Baru</title>
    <style>
        body { font-family: sans-serif; background-color: #f4f7f9; display: flex; justify-content: center; align-items: center; min-height: 100vh; margin: 0; }
        .form-container { background: white; padding: 40px; border-radius: 12px; box-shadow: 0 8px 16px rgba(0,0,0,0.1); width: 100%; max-width: 600px; }
        h1 { text-align: center; color: #333; }
        .form-group { margin-bottom: 20px; }
        label { display: block; margin-bottom: 8px; font-weight: bold; color: #555; }
        input, select, textarea { width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 8px; box-sizing: border-box; font-size: 1em; }
        .row { display: flex; gap: 20px; }
        .row .form-group { flex: 1; }
        .button-group { display: flex; justify-content: flex-end; gap: 10px; margin-top: 30px; }
        .btn { padding: 12px 25px; border: none; border-radius: 8px; cursor: pointer; font-weight: bold; }
        .btn-primary { background-color: #007bff; color: white; }
        .btn-secondary { background-color: #6c757d; color: white; }
    </style>
</head>
<body>
    <div class="form-container">
        <h1>Buat Janji Temu Baru</h1>
        <p style="text-align:center; color: #666;">Silakan isi form di bawah ini untuk menjadwalkan konsultasi Anda.</p>
        
        <form action="?url=janjitemu/simpan" method="POST">
            <!-- Hidden input untuk id_pasien -->
            <input type="hidden" name="id_pasien" value="<?php echo htmlspecialchars($data_pasien['id_pasien']); ?>">

            <div class="form-group">
                <label for="id_dokter">Pilih Dokter</label>
                <select id="id_dokter" name="id_dokter" required>
                    <option value="">-- Pilih salah satu dokter --</option>
                    <?php foreach ($daftar_dokter as $dokter): ?>
                        <option value="<?php echo htmlspecialchars($dokter['id_pengguna']); ?>">
                            <?php echo htmlspecialchars($dokter['nama_lengkap']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="row">
                <div class="form-group">
                    <label for="tanggal_temu">Pilih Tanggal</label>
                    <input type="date" id="tanggal_temu" name="tanggal_temu" required>
                </div>
                <div class="form-group">
                    <label for="waktu_temu">Pilih Waktu</label>
                    <input type="time" id="waktu_temu" name="waktu_temu" required>
                </div>
            </div>

            <div class="form-group">
                <label for="keluhan">Keluhan</label>
                <textarea id="keluhan" name="keluhan" rows="4" placeholder="Jelaskan keluhan utama Anda..."></textarea>
            </div>

            <div class="button-group">
                <button type="button" class="btn btn-secondary" onclick="window.history.back();">Batal</button>
                <button type="submit" class="btn btn-primary">Buat Janji Temu</button>
            </div>
        </form>
    </div>
</body>
</html>
