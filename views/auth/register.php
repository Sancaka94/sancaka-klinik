<?php 
// File: views/auth/register.php
require_once __DIR__ . '/../layouts/header_public.php'; 
?>

<style>
    /* CSS Kustom untuk formulir multi-langkah */
    .form-step { display: none; animation: fadeIn 0.5s; }
    .form-step.active { display: block; }
    .progress-bar-container { position: relative; display: flex; justify-content: space-between; counter-reset: step; margin-bottom: 3rem; }
    .progress-bar-container::before, .progress-line { content: ""; position: absolute; top: 50%; transform: translateY(-50%); height: 4px; width: 100%; background-color: #e0e0e0; z-index: -1; }
    .progress-line { background-color: #0d6efd; /* Bootstrap Primary Color */ width: 0%; transition: 0.3s; }
    .progress-step { width: 2.5rem; height: 2.5rem; background-color: #e0e0e0; border-radius: 50%; display: flex; justify-content: center; align-items: center; font-weight: bold; color: white; transition: background-color 0.3s; position: relative; z-index: 1; }
    .progress-step::before { counter-increment: step; content: counter(step); }
    .progress-step::after { content: attr(data-title); position: absolute; top: calc(100% + 0.5rem); font-size: 0.8rem; color: #6c757d; width: 120px; text-align: center; }
    .progress-step.active { background-color: #0d6efd; }
    #signature-pad { border: 2px dashed #ccc; cursor: crosshair; border-radius: 0.5rem; }
    @keyframes fadeIn { from { opacity: 0; } to { opacity: 1; } }
</style>

<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="card shadow-lg border-0 rounded-4">
                <div class="card-body p-4 p-md-5">
                    <h1 class="card-title text-center fw-bold mb-2">Pendaftaran Pasien Baru</h1>
                    <p class="card-text text-center text-muted mb-5">Lengkapi data Anda untuk mendapatkan pelayanan terbaik.</p>

                    <!-- Progress Bar -->
                    <div class="progress-bar-container">
                        <div class="progress-line" id="progress"></div>
                        <div class="progress-step active" data-title="Data Pribadi"></div>
                        <div class="progress-step" data-title="Alamat & Kontak"></div>
                        <div class="progress-step" data-title="Kesehatan"></div>
                        <div class="progress-step" data-title="Dokumen"></div>
                        <div class="progress-step" data-title="Selesai"></div>
                    </div>

                    <!-- Form Utama -->
                     <form action="?url=auth/processRegister" method="POST" enctype="multipart/form-data">
                    <!-- Step 1: Data Pribadi -->
                        <div class="form-step active">
                            <h4 class="text-center fw-semibold mb-4">Langkah 1: Data Pribadi</h4>
                            <div class="row g-3">
                                <div class="col-md-6"><label for="nama_lengkap" class="form-label">Nama Lengkap (Sesuai KTP)</label><input type="text" class="form-control form-control-lg" id="nama_lengkap" name="nama_lengkap" required></div>
                                <div class="col-md-6"><label for="nik" class="form-label">NIK / No. KTP</label><input type="text" class="form-control form-control-lg" id="nik" name="nik" required minlength="16" maxlength="16"></div>
                                <div class="col-md-6"><label for="tempat_lahir" class="form-label">Tempat Lahir</label><input type="text" class="form-control form-control-lg" id="tempat_lahir" name="tempat_lahir" required></div>
                                <div class="col-md-6"><label for="tanggal_lahir" class="form-label">Tanggal Lahir</label><input type="date" class="form-control form-control-lg" id="tanggal_lahir" name="tanggal_lahir" required></div>
                                <div class="col-md-6"><label class="form-label">Jenis Kelamin</label><select class="form-select form-select-lg" name="jenis_kelamin" required><option value="" disabled selected>-- Pilih --</option><option>Laki-laki</option><option>Perempuan</option></select></div>
                                <div class="col-md-6"><label class="form-label">Status Perkawinan</label><select class="form-select form-select-lg" name="status_perkawinan" required><option value="" disabled selected>-- Pilih --</option><option>Belum Menikah</option><option>Menikah</option><option>Cerai Hidup</option><option>Cerai Mati</option></select></div>
                                <div class="col-md-6"><label class="form-label">Pendidikan Terakhir</label><select class="form-select form-select-lg" name="pendidikan_terakhir" required><option value="" disabled selected>-- Pilih --</option><option>Tidak Sekolah</option><option>SD</option><option>SMP</option><option>SMA/SMK</option><option>D3</option><option>S1</option><option>S2</option><option>S3</option></select></div>
                                <div class="col-md-6"><label class="form-label">Pekerjaan</label><input type="text" class="form-control form-control-lg" name="pekerjaan" required></div>
                            </div>
                            <div class="text-end mt-4"><a href="#" class="btn btn-primary btn-lg next-btn">Selanjutnya &rarr;</a></div>
                        </div>

                        <!-- Step 2: Alamat & Kontak -->
                        <div class="form-step">
                            <h4 class="text-center fw-semibold mb-4">Langkah 2: Alamat & Kontak</h4>
                            <div class="mb-3"><label for="alamat" class="form-label">Alamat Lengkap (Sesuai KTP)</label><textarea class="form-control form-control-lg" id="alamat" name="alamat" rows="3" required></textarea></div>
                            <div class="row g-3">
                                <div class="col-md-6"><label for="nomor_hp" class="form-label">Nomor HP Aktif (WhatsApp)</label><input type="tel" class="form-control form-control-lg" id="nomor_hp" name="nomor_telepon" required></div>
                                <div class="col-md-6"><label for="email" class="form-label">Alamat Email</label><input type="email" class="form-control form-control-lg" id="email" name="email" required></div>
                                <div class="col-md-6"><label for="kontak_darurat" class="form-label">Kontak Darurat (Keluarga)</label><input type="tel" class="form-control form-control-lg" id="kontak_darurat" name="kontak_darurat" required></div>
                                <div class="col-md-6"><label for="penanggung_jawab" class="form-label">Penanggung Jawab</label><select class="form-select form-select-lg" name="penanggung_jawab" required><option value="" disabled selected>-- Pilih --</option><option>Diri Sendiri</option><option>Suami</option><option>Istri</option><option>Anak</option><option>Bapak</option><option>Ibu</option><option>Saudara</option><option>Lainnya</option></select></div>
                            </div>
                            <div class="d-flex justify-content-between mt-4"><a href="#" class="btn btn-light btn-lg prev-btn">&larr; Sebelumnya</a><a href="#" class="btn btn-primary btn-lg next-btn">Selanjutnya &rarr;</a></div>
                        </div>

                        <!-- Step 3: Riwayat Kesehatan & Asuransi -->
                        <div class="form-step">
                            <h4 class="text-center fw-semibold mb-4">Langkah 3: Riwayat Kesehatan & Asuransi</h4>
                            <div class="row g-3">
                                <div class="col-md-6"><label class="form-label">Golongan Darah</label><select class="form-select form-select-lg" name="golongan_darah" required><option value="" disabled selected>-- Pilih --</option><option>A</option><option>B</option><option>AB</option><option>O</option><option>Tidak Tahu</option></select></div>
                                <div class="col-md-6"><label class="form-label">Agama</label><select class="form-select form-select-lg" name="agama" required><option value="" disabled selected>-- Pilih --</option><option>Islam</option><option>Kristen</option><option>Katolik</option><option>Hindu</option><option>Buddha</option><option>Konghucu</option></select></div>
                            </div>
                            <div class="mb-3"><label for="riwayat_penyakit" class="form-label">Riwayat Penyakit Terdahulu (Contoh: Diabetes, Hipertensi)</label><textarea class="form-control form-control-lg" id="riwayat_penyakit" name="riwayat_penyakit" rows="2" placeholder="Kosongkan jika tidak ada"></textarea></div>
                            <div class="mb-3"><label for="riwayat_alergi" class="form-label">Riwayat Alergi (Obat atau Makanan)</label><textarea class="form-control form-control-lg" id="riwayat_alergi" name="riwayat_alergi" rows="2" placeholder="Kosongkan jika tidak ada"></textarea></div>
                            <div class="row g-3">
                                <div class="col-md-6"><label class="form-label">Status BPJS/Asuransi Lain</label><select class="form-select form-select-lg" name="status_bpjs" id="status_bpjs" required><option value="Tidak Ada" selected>Tidak Ada</option><option value="BPJS Kesehatan">BPJS Kesehatan</option><option value="Asuransi Lain">Asuransi Lain</option></select></div>
                                <div class="col-md-6" id="nomor-bpjs-group" style="display: none;"><label for="nomor_bpjs" class="form-label">Nomor Kartu BPJS/Asuransi</label><input type="text" class="form-control form-control-lg" id="nomor_bpjs" name="nomor_bpjs"></div>
                            </div>
                            <div class="d-flex justify-content-between mt-4"><a href="#" class="btn btn-light btn-lg prev-btn">&larr; Sebelumnya</a><a href="#" class="btn btn-primary btn-lg next-btn">Selanjutnya &rarr;</a></div>
                        </div>

                        <!-- Step 4: Upload Dokumen -->
                        <div class="form-step">
                            <h4 class="text-center fw-semibold mb-4">Langkah 4: Upload Dokumen</h4>
                            <div class="mb-3"><label for="file_ktp" class="form-label">Upload KTP (Wajib)</label><input class="form-control form-control-lg" type="file" id="file_ktp" name="file_ktp" accept=".jpg,.jpeg,.png,.pdf" required></div>
                            <div class="mb-3"><label for="file_kk" class="form-label">Upload Kartu Keluarga (Opsional)</label><input class="form-control form-control-lg" type="file" id="file_kk" name="file_kk" accept=".jpg,.jpeg,.png,.pdf"></div>
                            <div class="mb-4"><label for="foto_profil" class="form-label">Upload Foto Diri (Opsional)</label><input class="form-control form-control-lg" type="file" id="foto_profil" name="foto_profil" accept="image/png, image/jpeg"></div>
                            <div class="d-flex justify-content-between mt-4"><a href="#" class="btn btn-light btn-lg prev-btn">&larr; Sebelumnya</a><a href="#" class="btn btn-primary btn-lg next-btn">Selanjutnya &rarr;</a></div>
                        </div>

                        <!-- Step 5: Akun & Persetujuan -->
                        <div class="form-step">
                            <h4 class="text-center fw-semibold mb-4">Langkah 5: Akun & Persetujuan</h4>
                            <div class="mb-3"><label for="password" class="form-label">Buat Kata Sandi untuk Akun Anda</label><input type="password" class="form-control form-control-lg" id="password" name="password" required></div>
                            <div class="mb-3">
                                <label class="form-label">Tanda Tangan Digital</label>
                                <div class="bg-light p-2 rounded"><canvas id="signature-pad" class="w-100" height="150"></canvas></div>
                                <button type="button" id="clear-signature" class="btn btn-sm btn-outline-secondary mt-2">Ulangi</button>
                                <input type="hidden" name="tanda_tangan" id="tanda_tangan_data">
                            </div>
                            <div class="form-check"><input class="form-check-input" type="checkbox" id="terms" required><label class="form-check-label" for="terms">Saya menyatakan bahwa semua data yang saya isi adalah benar dan dapat dipertanggungjawabkan.</label></div>
                            <div class="d-flex justify-content-between mt-4"><a href="#" class="btn btn-light btn-lg prev-btn">&larr; Sebelumnya</a><button type="submit" class="btn btn-success btn-lg">Selesaikan Pendaftaran</button></div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Library untuk Tanda Tangan Digital -->
<script src="https://cdn.jsdelivr.net/npm/signature_pad@4.0.0/dist/signature_pad.umd.min.js"></script>

<script>
document.addEventListener("DOMContentLoaded", function() {
    const regForm = document.getElementById('regForm');
    const nextBtns = document.querySelectorAll(".next-btn");
    const prevBtns = document.querySelectorAll(".prev-btn");
    const formSteps = document.querySelectorAll(".form-step");
    const progressSteps = document.querySelectorAll(".progress-step");
    
    let formStepsNum = 0;

    // **PENAMBAHAN:** Fungsi untuk menyimpan data ke localStorage
    function saveData() {
        const formData = new FormData(regForm);
        const data = {};
        for (let [key, value] of formData.entries()) {
            // Kita tidak menyimpan file atau password di localStorage
            if (key !== 'password' && key !== 'file_ktp' && key !== 'file_kk' && key !== 'foto_profil') {
                data[key] = value;
            }
        }
        localStorage.setItem('klinikFormData', JSON.stringify(data));
    }

    // **PENAMBAHAN:** Fungsi untuk memuat data dari localStorage
    function loadData() {
        const savedData = localStorage.getItem('klinikFormData');
        if (savedData) {
            const data = JSON.parse(savedData);
            for (const key in data) {
                if (regForm.elements[key]) {
                    regForm.elements[key].value = data[key];
                }
            }
        }
    }

    // Tambahkan event listener untuk menyimpan data setiap kali ada input
    regForm.addEventListener('input', saveData);

    // Panggil loadData saat halaman pertama kali dimuat
    loadData();

    nextBtns.forEach((btn) => {
        btn.addEventListener("click", (e) => {
            e.preventDefault();
            if (validateStep()) {
                formStepsNum++;
                updateFormSteps();
                updateProgressbar();
            }
        });
    });

    prevBtns.forEach((btn) => {
        btn.addEventListener("click", (e) => {
            e.preventDefault();
            formStepsNum--;
            updateFormSteps();
            updateProgressbar();
        });
    });

    function updateFormSteps() {
        formSteps.forEach((formStep) => formStep.classList.remove("active"));
        formSteps[formStepsNum].classList.add("active");
    }

    function updateProgressbar() {
        progressSteps.forEach((progressStep, idx) => {
            progressStep.classList.toggle("active", idx < formStepsNum + 1);
        });
        const progress = document.getElementById("progress");
        const progressActive = document.querySelectorAll(".progress-step.active");
        progress.style.width = ((progressActive.length - 1) / (progressSteps.length - 1)) * 100 + "%";
    }

    function validateStep() {
        let isValid = true;
        const currentStep = formSteps[formStepsNum];
        const inputs = currentStep.querySelectorAll("input[required], select[required], textarea[required]");
        inputs.forEach(input => {
            input.classList.remove('is-invalid');
            if ((input.type === 'checkbox' && !input.checked) || (input.type !== 'checkbox' && !input.value.trim())) {
                isValid = false;
                input.classList.add('is-invalid');
            }
        });
        if (!isValid) alert('Harap isi semua kolom yang wajib diisi pada langkah ini.');
        return isValid;
    }

    // Logika untuk Tanda Tangan Digital
    const canvas = document.getElementById('signature-pad');
    const signaturePad = new SignaturePad(canvas, { backgroundColor: 'rgb(248, 249, 250)' });
    document.getElementById('clear-signature').addEventListener('click', () => signaturePad.clear());
    
    regForm.addEventListener('submit', function(event) {
        if (signaturePad.isEmpty()) {
            alert("Harap berikan tanda tangan Anda.");
            event.preventDefault(); // Mencegah form disubmit
        } else {
            document.getElementById('tanda_tangan_data').value = signaturePad.toDataURL();
            // **PENAMBAHAN:** Hapus data dari localStorage setelah submit berhasil
            localStorage.removeItem('klinikFormData');
        }
    });
});
</script>

<?php 
require_once __DIR__ . '/../layouts/footer_public.php'; 
?>
