<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil Pasien - <?php echo htmlspecialchars($data_pasien['nama_lengkap']); ?></title>
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
            background-color: #007bff;
            color: white;
            padding: 30px;
            text-align: center;
        }
        .profile-header h1 {
            margin: 0;
            font-size: 2em;
        }
        .profile-header p {
            margin: 5px 0 0;
            opacity: 0.9;
        }

        /* Content */
        .profile-content {
            padding: 30px;
        }
        .section {
            margin-bottom: 30px;
        }
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
        .data-item a {
            color: #0056b3;
            text-decoration: none;
        }
        .data-item a:hover {
            text-decoration: underline;
        }
        .signature-img {
            max-width: 100%;
            height: auto;
            background: #fff;
            border: 1px solid #ccc;
            border-radius: 4px;
            margin-top: 5px;
        }
    </style>
</head>
<body>

    <div class="container">
        <div class="profile-header">
            <!-- Menampilkan nama lengkap di header -->
            <h1><?php echo htmlspecialchars($data_pasien['nama_lengkap']); ?></h1>
            <p>Profil Pasien Klinik</p>
        </div>

        <div class="profile-content">

            <!-- Bagian Data Diri -->
            <div class="section">
                <h2 class="section-title">Data Diri</h2>
                <div class="data-grid">
                    <div class="data-item"><strong>NIK</strong> <span><?php echo htmlspecialchars($data_pasien['nik'] ?? 'Tidak ada data'); ?></span></div>
                    <div class="data-item"><strong>Email</strong> <span><?php echo htmlspecialchars($data_pasien['email']); ?></span></div>
                    <div class="data-item"><strong>Tempat, Tanggal Lahir</strong> <span><?php echo htmlspecialchars($data_pasien['tempat_lahir'] ?? 'Tidak ada data') . ', ' . htmlspecialchars($data_pasien['tanggal_lahir'] ?? 'Tidak ada data'); ?></span></div>
                    <div class="data-item"><strong>Jenis Kelamin</strong> <span><?php echo htmlspecialchars($data_pasien['jenis_kelamin'] ?? 'Tidak ada data'); ?></span></div>
                    <div class="data-item"><strong>Agama</strong> <span><?php echo htmlspecialchars($data_pasien['agama'] ?? 'Tidak ada data'); ?></span></div>
                    <div class="data-item"><strong>Status Perkawinan</strong> <span><?php echo htmlspecialchars($data_pasien['status_perkawinan'] ?? 'Tidak ada data'); ?></span></div>
                    <div class="data-item"><strong>Pendidikan Terakhir</strong> <span><?php echo htmlspecialchars($data_pasien['pendidikan_terakhir'] ?? 'Tidak ada data'); ?></span></div>
                    <div class="data-item"><strong>Pekerjaan</strong> <span><?php echo htmlspecialchars($data_pasien['pekerjaan'] ?? 'Tidak ada data'); ?></span></div>
                </div>
            </div>

            <!-- Bagian Kontak & Alamat -->
            <div class="section">
                <h2 class="section-title">Kontak & Alamat</h2>
                <div class="data-grid">
                    <div class="data-item"><strong>Nomor Telepon</strong> <span><?php echo htmlspecialchars($data_pasien['nomor_telepon'] ?? 'Tidak ada data'); ?></span></div>
                    <div class="data-item"><strong>Kontak Darurat</strong> <span><?php echo htmlspecialchars($data_pasien['kontak_darurat'] ?? 'Tidak ada data'); ?></span></div>
                    <div class="data-item"><strong>Penanggung Jawab</strong> <span><?php echo htmlspecialchars($data_pasien['penanggung_jawab'] ?? 'Tidak ada data'); ?></span></div>
                    <div class="data-item" style="grid-column: 1 / -1;"><strong>Alamat Lengkap</strong> <span><?php echo nl2br(htmlspecialchars($data_pasien['alamat'] ?? 'Tidak ada data')); ?></span></div>
                </div>
            </div>

            <!-- Bagian Data Medis -->
            <div class="section">
                <h2 class="section-title">Data Medis</h2>
                <div class="data-grid">
                    <div class="data-item"><strong>Golongan Darah</strong> <span><?php echo htmlspecialchars($data_pasien['golongan_darah'] ?? 'Tidak ada data'); ?></span></div>
                    <div class="data-item"><strong>Status BPJS</strong> <span><?php echo htmlspecialchars($data_pasien['status_bpjs'] ?? 'Tidak ada data'); ?></span></div>
                    <div class="data-item"><strong>Nomor BPJS</strong> <span><?php echo htmlspecialchars($data_pasien['nomor_bpjs'] ?? 'Tidak ada data'); ?></span></div>
                    <div class="data-item" style="grid-column: 1 / -1;"><strong>Riwayat Penyakit</strong> <span><?php echo nl2br(htmlspecialchars($data_pasien['riwayat_penyakit'] ?? 'Tidak ada data')); ?></span></div>
                    <div class="data-item" style="grid-column: 1 / -1;"><strong>Riwayat Alergi</strong> <span><?php echo nl2br(htmlspecialchars($data_pasien['riwayat_alergi'] ?? 'Tidak ada data')); ?></span></div>
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
    </div>

</body>
</html>
