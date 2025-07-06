<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pendaftaran Pasien Baru</title>
    <!-- Library untuk Tanda Tangan Digital -->
    <script src="https://cdn.jsdelivr.net/npm/signature_pad@4.0.0/dist/signature_pad.umd.min.js"></script>
    <style>
        body {
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
            background-color: #f4f7f9;
            color: #333;
            margin: 0;
            padding: 20px;
        }
        .container {
            max-width: 800px;
            margin: 20px auto;
            background-color: #ffffff;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.08);
        }
        .form-header {
            background: linear-gradient(135deg, #007bff, #0056b3);
            color: white;
            padding: 20px 30px;
            border-radius: 12px 12px 0 0;
            text-align: center;
        }
        .form-header h1 { margin: 0; }
        form { padding: 30px; }
        .section {
            margin-bottom: 30px;
            border-bottom: 1px solid #e9ecef;
            padding-bottom: 20px;
        }
        .section-title {
            font-size: 1.4em;
            color: #007bff;
            margin-bottom: 20px;
        }
        .grid-layout {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
        }
        .form-group {
            display: flex;
            flex-direction: column;
        }
        .form-group.full-width {
            grid-column: 1 / -1;
        }
        .form-group label {
            font-weight: bold;
            margin-bottom: 5px;
            font-size: 0.9em;
            color: #555;
        }
        .form-group input, .form-group select, .form-group textarea {
            width: 100%;
            padding: 10px;
            border: 1px solid #ced4da;
            border-radius: 6px;
            font-size: 1em;
            box-sizing: border-box;
        }
        .form-group textarea { resize: vertical; min-height: 80px; }
        
        /* Styling untuk Area Tanda Tangan */
        .signature-pad-container {
            position: relative;
            width: 100%;
            height: 200px;
            border: 1px dashed #ced4da;
            border-radius: 6px;
        }
        #signature-pad {
            width: 100%;
            height: 100%;
        }
        .signature-buttons {
            margin-top: 10px;
            text-align: right;
        }
        .signature-buttons button {
            background-color: #6c757d;
            color: white;
            border: none;
            padding: 8px 15px;
            border-radius: 5px;
            cursor: pointer;
        }

        /* Styling untuk field yang disembunyikan */
        .hidden-field {
            display: none;
        }

        .submit-btn {
            display: block;
            width: 100%;
            padding: 15px;
            font-size: 1.2em;
            font-weight: bold;
            background-color: #28a745;
            color: white;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            margin-top: 20px;
        }
    </style>
</head>
<body>

<div class="container">
    <div class="form-header">
        <h1>Form Pendaftaran Pasien Baru</h1>
    </div>

    <!-- Pastikan ada enctype untuk upload file -->
    <form id="registrationForm" action="?url=auth/processRegister" method="POST" enctype="multipart/form-data">
        
        <!-- Data Akun -->
        <div class="section">
            <h2 class="section-title">1. Informasi Akun</h2>
            <div class="grid-layout">
                <div class="form-group"><label for="email">Email (untuk login)</label><input type="email" id="email" name="email" required></div>
                <div class="form-group"><label for="password">Password</label><input type="password" id="password" name="password" required></div>
            </div>
        </div>

        <!-- Data Diri -->
        <div class="section">
            <h2 class="section-title">2. Data Diri Pasien</h2>
            <div class="grid-layout">
                <div class="form-group"><label for="nama_lengkap">Nama Lengkap</label><input type="text" id="nama_lengkap" name="nama_lengkap" required></div>
                <div class="form-group"><label for="nik">NIK</label><input type="text" id="nik" name="nik" required></div>
                <div class="form-group"><label for="tempat_lahir">Tempat Lahir</label><input type="text" id="tempat_lahir" name="tempat_lahir"></div>
                <div class="form-group"><label for="tanggal_lahir">Tanggal Lahir</label><input type="date" id="tanggal_lahir" name="tanggal_lahir"></div>
                <div class="form-group">
                    <label for="jenis_kelamin">Jenis Kelamin</label>
                    <select id="jenis_kelamin" name="jenis_kelamin"><option value="Laki-laki">Laki-laki</option><option value="Perempuan">Perempuan</option></select>
                </div>
                <div class="form-group"><label for="agama">Agama</label><input type="text" id="agama" name="agama"></div>
                <div class="form-group"><label for="status_perkawinan">Status Perkawinan</label><input type="text" id="status_perkawinan" name="status_perkawinan"></div>
                <div class="form-group"><label for="pendidikan_terakhir">Pendidikan Terakhir</label><input type="text" id="pendidikan_terakhir" name="pendidikan_terakhir"></div>
                <div class="form-group"><label for="pekerjaan">Pekerjaan</label><input type="text" id="pekerjaan" name="pekerjaan"></div>
            </div>
        </div>
        
        <!-- Kontak & Alamat -->
        <div class="section">
            <h2 class="section-title">3. Kontak & Alamat</h2>
            <div class="grid-layout">
                <div class="form-group"><label for="nomor_telepon">Nomor Telepon</label><input type="tel" id="nomor_telepon" name="nomor_telepon"></div>
                <div class="form-group"><label for="kontak_darurat">Kontak Darurat</label><input type="tel" id="kontak_darurat" name="kontak_darurat"></div>
                <div class="form-group"><label for="penanggung_jawab">Nama Penanggung Jawab</label><input type="text" id="penanggung_jawab" name="penanggung_jawab"></div>
                <div class="form-group full-width"><label for="alamat">Alamat Lengkap</label><textarea id="alamat" name="alamat"></textarea></div>
            </div>
        </div>
        
        <!-- Data Medis & Jaminan -->
        <div class="section">
            <h2 class="section-title">4. Data Medis & Jaminan</h2>
            <div class="grid-layout">
                <div class="form-group"><label for="golongan_darah">Golongan Darah</label><input type="text" id="golongan_darah" name="golongan_darah"></div>
                <div class="form-group">
                    <label for="status_bpjs">Jenis Jaminan</label>
                    <select id="status_bpjs" name="status_bpjs"><option value="Umum">Umum</option><option value="BPJS">BPJS</option><option value="Asuransi Lain">Asuransi Lain</option></select>
                </div>
                <div id="nomor_bpjs_group" class="form-group hidden-field">
                    <label for="nomor_bpjs">Nomor Jaminan (BPJS/Asuransi)</label><input type="text" id="nomor_bpjs" name="nomor_bpjs">
                </div>
                <div class="form-group full-width"><label for="riwayat_penyakit">Riwayat Penyakit</label><textarea id="riwayat_penyakit" name="riwayat_penyakit"></textarea></div>
                <div class="form-group full-width"><label for="riwayat_alergi">Riwayat Alergi</label><textarea id="riwayat_alergi" name="riwayat_alergi"></textarea></div>
            </div>
        </div>

        <!-- Dokumen & TTD -->
        <div class="section">
            <h2 class="section-title">5. Unggah Dokumen & Tanda Tangan</h2>
            <div class="grid-layout">
                <div class="form-group"><label for="file_ktp">File KTP (jpg, png, pdf)</label><input type="file" id="file_ktp" name="file_ktp" accept=".jpg, .jpeg, .png, .pdf"></div>
                <div class="form-group"><label for="file_kk">File KK</label><input type="file" id="file_kk" name="file_kk" accept=".jpg, .jpeg, .png, .pdf"></div>
                <div class="form-group"><label for="foto_profil">Foto Profil</label><input type="file" id="foto_profil" name="foto_profil" accept=".jpg, .jpeg, .png"></div>
            </div>
            <div class="form-group full-width" style="margin-top: 20px;">
                <label>Tanda Tangan Digital</label>
                <div class="signature-pad-container"><canvas id="signature-pad"></canvas></div>
                <div class="signature-buttons"><button type="button" id="clear-signature">Hapus TTD</button></div>
                <input type="hidden" name="tanda_tangan" id="tanda_tangan_input">
            </div>
        </div>

        <button type="submit" class="submit-btn">Selesai & Daftarkan Pasien</button>
    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const jaminanSelect = document.getElementById('status_bpjs');
    const nomorJaminanGroup = document.getElementById('nomor_bpjs_group');
    jaminanSelect.addEventListener('change', function() {
        if (this.value === 'BPJS' || this.value === 'Asuransi Lain') {
            nomorJaminanGroup.classList.remove('hidden-field');
        } else {
            nomorJaminanGroup.classList.add('hidden-field');
        }
    });

    const canvas = document.getElementById('signature-pad');
    const signaturePad = new SignaturePad(canvas, { backgroundColor: 'rgb(255, 255, 255)' });
    function resizeCanvas() {
        const ratio =  Math.max(window.devicePixelRatio || 1, 1);
        canvas.width = canvas.offsetWidth * ratio;
        canvas.height = canvas.offsetHeight * ratio;
        canvas.getContext("2d").scale(ratio, ratio);
        signaturePad.clear();
    }
    window.addEventListener("resize", resizeCanvas);
    resizeCanvas();
    document.getElementById('clear-signature').addEventListener('click', function() { signaturePad.clear(); });
    document.getElementById('registrationForm').addEventListener('submit', function(event) {
        if (!signaturePad.isEmpty()) {
            document.getElementById('tanda_tangan_input').value = signaturePad.toDataURL();
        }
    });
});
</script>

</body>
</html>
