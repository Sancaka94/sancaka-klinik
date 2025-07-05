<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pengaturan Profil - <?php echo htmlspecialchars($data_pasien['nama_lengkap']); ?></title>
    <style>
        /* General Styling */
        body {
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
            background-color: #f4f7f9;
            color: #333;
            margin: 0;
            padding: 20px;
        }
        .container {
            max-width: 900px;
            margin: 20px auto;
            background-color: #ffffff;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.08);
            overflow: hidden;
        }

        /* Header */
        .profile-header {
            background: linear-gradient(135deg, #007bff, #0056b3);
            color: white;
            padding: 30px;
            text-align: center;
        }
        .profile-header h1 { margin: 0; font-size: 2em; }
        .profile-header p { margin: 5px 0 0; opacity: 0.9; }

        /* Content & Form */
        .profile-content { padding: 30px; }
        .section { margin-bottom: 30px; }
        .section-title {
            font-size: 1.4em;
            color: #007bff;
            border-bottom: 2px solid #e9ecef;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }
        .data-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 20px;
        }
        .form-group {
            display: flex;
            flex-direction: column;
        }
        .form-group label {
            color: #555;
            font-size: 0.9em;
            margin-bottom: 5px;
            font-weight: bold;
        }
        .form-group input, .form-group select, .form-group textarea {
            font-size: 1em;
            padding: 10px;
            border: 1px solid #ced4da;
            border-radius: 6px;
            background-color: #f8f9fa;
            transition: border-color 0.2s, box-shadow 0.2s;
        }
        .form-group input:read-only, .form-group select:disabled, .form-group textarea:read-only {
            background-color: #e9ecef;
            border-color: #e9ecef;
            cursor: default;
        }
        .form-group input:focus, .form-group select:focus, .form-group textarea:focus {
            outline: none;
            border-color: #80bdff;
            box-shadow: 0 0 0 0.2rem rgba(0,123,255,.25);
        }
        textarea { resize: vertical; min-height: 80px; }
        .data-item {
            background-color: #f8f9fa;
            padding: 15px;
            border-radius: 8px;
            border: 1px solid #e9ecef;
        }
        .data-item strong {
            display: block;
            color: #555;
            font-size: 0.9em;
            margin-bottom: 5px;
        }
        .data-item span, .data-item a {
            font-size: 1em;
            color: #212529;
            word-wrap: break-word;
        }
        .signature-img {
            max-width: 100%;
            height: auto;
            background: #fff;
            border: 1px solid #ccc;
            border-radius: 4px;
            margin-top: 5px;
        }
        
        /* Buttons */
        .button-group {
            display: flex;
            gap: 10px;
            justify-content: flex-end;
            padding: 20px 30px;
            background-color: #f8f9fa;
            border-top: 1px solid #e9ecef;
        }
        .btn {
            padding: 10px 20px;
            font-size: 1em;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            transition: background-color 0.2s, transform 0.1s;
        }
        .btn:active { transform: scale(0.98); }
        .btn-primary { background-color: #007bff; color: white; }
        .btn-primary:hover { background-color: #0056b3; }
        .btn-secondary { background-color: #6c757d; color: white; }
        .btn-secondary:hover { background-color: #5a6268; }
        .btn-success { background-color: #28a745; color: white; }
        .btn-success:hover { background-color: #218838; }
        .btn-danger { background-color: #dc3545; color: white; }
        .btn-danger:hover { background-color: #c82333; }
    </style>
</head>
<body>

    <div class="container">
        <!-- Form untuk mengirim data yang diubah -->
        <form id="profileForm" action="?url=profile/update" method="POST">
            <!-- Hidden input untuk ID Pasien -->
            <input type="hidden" name="id_pasien" value="<?php echo htmlspecialchars($data_pasien['id_pasien']); ?>">

            <div class="profile-header">
                <h1><?php echo htmlspecialchars($data_pasien['nama_lengkap']); ?></h1>
                <p>Profil Pasien Klinik</p>
            </div>

            <div class="profile-content">
                <!-- Data Diri -->
                <div class="section">
                    <h2 class="section-title">Data Diri</h2>
                    <div class="data-grid">
                        <div class="form-group"><label for="nama_lengkap">Nama Lengkap</label><input type="text" id="nama_lengkap" name="nama_lengkap" value="<?php echo htmlspecialchars($data_pasien['nama_lengkap']); ?>" readonly></div>
                        <div class="form-group"><label for="nik">NIK</label><input type="text" id="nik" name="nik" value="<?php echo htmlspecialchars($data_pasien['nik']); ?>" readonly></div>
                        <div class="form-group"><label for="email">Email</label><input type="email" id="email" name="email" value="<?php echo htmlspecialchars($data_pasien['email']); ?>" readonly></div>
                        <div class="form-group"><label for="tempat_lahir">Tempat Lahir</label><input type="text" id="tempat_lahir" name="tempat_lahir" value="<?php echo htmlspecialchars($data_pasien['tempat_lahir']); ?>" readonly></div>
                        <div class="form-group"><label for="tanggal_lahir">Tanggal Lahir</label><input type="date" id="tanggal_lahir" name="tanggal_lahir" value="<?php echo htmlspecialchars($data_pasien['tanggal_lahir']); ?>" readonly></div>
                        <div class="form-group">
                            <label for="jenis_kelamin">Jenis Kelamin</label>
                            <select id="jenis_kelamin" name="jenis_kelamin" disabled>
                                <option value="Laki-laki" <?php echo ($data_pasien['jenis_kelamin'] == 'Laki-laki') ? 'selected' : ''; ?>>Laki-laki</option>
                                <option value="Perempuan" <?php echo ($data_pasien['jenis_kelamin'] == 'Perempuan') ? 'selected' : ''; ?>>Perempuan</option>
                            </select>
                        </div>
                        <div class="form-group"><label for="agama">Agama</label><input type="text" id="agama" name="agama" value="<?php echo htmlspecialchars($data_pasien['agama'] ?? ''); ?>" readonly></div>
                        <div class="form-group"><label for="status_perkawinan">Status Perkawinan</label><input type="text" id="status_perkawinan" name="status_perkawinan" value="<?php echo htmlspecialchars($data_pasien['status_perkawinan'] ?? ''); ?>" readonly></div>
                    </div>
                </div>

                <!-- Kontak & Alamat -->
                <div class="section">
                    <h2 class="section-title">Kontak & Pekerjaan</h2>
                    <div class="data-grid">
                        <div class="form-group"><label for="nomor_telepon">Nomor Telepon</label><input type="tel" id="nomor_telepon" name="nomor_telepon" value="<?php echo htmlspecialchars($data_pasien['nomor_telepon']); ?>" readonly></div>
                        <div class="form-group"><label for="kontak_darurat">Kontak Darurat</label><input type="tel" id="kontak_darurat" name="kontak_darurat" value="<?php echo htmlspecialchars($data_pasien['kontak_darurat']); ?>" readonly></div>
                        <div class="form-group"><label for="pendidikan_terakhir">Pendidikan Terakhir</label><input type="text" id="pendidikan_terakhir" name="pendidikan_terakhir" value="<?php echo htmlspecialchars($data_pasien['pendidikan_terakhir'] ?? ''); ?>" readonly></div>
                        <div class="form-group"><label for="pekerjaan">Pekerjaan</label><input type="text" id="pekerjaan" name="pekerjaan" value="<?php echo htmlspecialchars($data_pasien['pekerjaan'] ?? ''); ?>" readonly></div>
                        <div class="form-group" style="grid-column: 1 / -1;"><label for="alamat">Alamat Lengkap</label><textarea id="alamat" name="alamat" readonly><?php echo htmlspecialchars($data_pasien['alamat']); ?></textarea></div>
                    </div>
                </div>
                
                <!-- Bagian Data Medis -->
                <div class="section">
                    <h2 class="section-title">Data Medis</h2>
                    <div class="data-grid">
                        <div class="form-group"><label for="golongan_darah">Golongan Darah</label><input type="text" id="golongan_darah" name="golongan_darah" value="<?php echo htmlspecialchars($data_pasien['golongan_darah'] ?? ''); ?>" readonly></div>
                        <div class="form-group"><label for="status_bpjs">Status BPJS</label><input type="text" id="status_bpjs" name="status_bpjs" value="<?php echo htmlspecialchars($data_pasien['status_bpjs'] ?? ''); ?>" readonly></div>
                        <div class="form-group"><label for="nomor_bpjs">Nomor BPJS</label><input type="text" id="nomor_bpjs" name="nomor_bpjs" value="<?php echo htmlspecialchars($data_pasien['nomor_bpjs'] ?? ''); ?>" readonly></div>
                        <div class="form-group" style="grid-column: 1 / -1;"><label for="riwayat_penyakit">Riwayat Penyakit</label><textarea id="riwayat_penyakit" name="riwayat_penyakit" readonly><?php echo htmlspecialchars($data_pasien['riwayat_penyakit'] ?? ''); ?></textarea></div>
                        <div class="form-group" style="grid-column: 1 / -1;"><label for="riwayat_alergi">Riwayat Alergi</label><textarea id="riwayat_alergi" name="riwayat_alergi" readonly><?php echo htmlspecialchars($data_pasien['riwayat_alergi'] ?? ''); ?></textarea></div>
                    </div>
                </div>

                <!-- Bagian Dokumen & Lainnya -->
                <div class="section">
                    <h2 class="section-title">Dokumen & Lainnya</h2>
                    <div class="data-grid">
                        <div class="data-item">
                            <strong>File KTP</strong>
                            <?php if (!empty($data_pasien['file_ktp'])): ?>
                                <a href="uploads/ktp/<?php echo htmlspecialchars($data_pasien['file_ktp']); ?>" target="_blank">Lihat Dokumen</a>
                            <?php else: ?>
                                <span>Tidak ada dokumen</span>
                            <?php endif; ?>
                        </div>
                        <div class="data-item">
                            <strong>File KK</strong>
                            <?php if (!empty($data_pasien['file_kk'])): ?>
                                <a href="uploads/kk/<?php echo htmlspecialchars($data_pasien['file_kk']); ?>" target="_blank">Lihat Dokumen</a>
                            <?php else: ?>
                                <span>Tidak ada dokumen</span>
                            <?php endif; ?>
                        </div>
                        <div class="data-item">
                            <strong>Tanda Tangan</strong>
                            <?php if (!empty($data_pasien['tanda_tangan'])): ?>
                                <!-- Asumsi tanda tangan disimpan sebagai data URL base64 -->
                                <img src="<?php echo $data_pasien['tanda_tangan']; ?>" alt="Tanda Tangan Pasien" class="signature-img">
                            <?php else: ?>
                                <span>Tidak ada tanda tangan</span>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>

            <div class="button-group">
                <button type="button" class="btn btn-secondary" onclick="window.history.back();">Kembali</button>
                <button type="button" id="editBtn" class="btn btn-primary">Edit Profil</button>
                <button type="submit" id="saveBtn" class="btn btn-success" style="display:none;">Simpan Perubahan</button>
                <button type="button" id="cancelBtn" class="btn btn-danger" style="display:none;">Batal</button>
            </div>
        </form>
    </div>

    <script>
        const form = document.getElementById('profileForm');
        const editBtn = document.getElementById('editBtn');
        const saveBtn = document.getElementById('saveBtn');
        const cancelBtn = document.getElementById('cancelBtn');
        const inputs = form.querySelectorAll('input, select, textarea');

        function toggleEditMode(isEditing) {
            inputs.forEach(input => {
                // Jangan aktifkan field yang seharusnya tidak bisa diubah (seperti NIK atau email)
                if (input.name === 'nik' || input.name === 'email' || input.type === 'hidden') {
                    return;
                }
                
                if (input.tagName === 'SELECT') {
                    input.disabled = !isEditing;
                } else {
                    input.readOnly = !isEditing;
                }
            });

            editBtn.style.display = isEditing ? 'none' : 'inline-block';
            saveBtn.style.display = isEditing ? 'inline-block' : 'none';
            cancelBtn.style.display = isEditing ? 'inline-block' : 'none';
        }

        editBtn.addEventListener('click', () => {
            toggleEditMode(true);
        });

        cancelBtn.addEventListener('click', () => {
            // Memuat ulang halaman untuk membatalkan perubahan
            window.location.reload();
        });

        // Inisialisasi: semua field non-aktif
        toggleEditMode(false);
    </script>

</body>
</html>
