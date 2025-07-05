<?php 
// File: views/auth/register.php
require_once __DIR__ . '/../layouts/header_public.php'; 
?>

<style>
    /* CSS Kustom untuk formulir multi-langkah */
    .form-step {
        display: none;
        transform-origin: top left;
        animation: fadeIn 0.5s;
    }
    .form-step.active {
        display: block;
    }
    .progress-bar {
        position: relative;
        display: flex;
        justify-content: space-between;
        counter-reset: step;
        margin-bottom: 2rem;
    }
    .progress-bar::before, .progress {
        content: "";
        position: absolute;
        top: 50%;
        transform: translateY(-50%);
        height: 4px;
        width: 100%;
        background-color: #e0e0e0;
        z-index: -1;
    }
    .progress {
        background-color: var(--bs-primary);
        width: 0%;
        transition: 0.3s;
    }
    .progress-step {
        width: 2.5rem;
        height: 2.5rem;
        background-color: #e0e0e0;
        border-radius: 50%;
        display: flex;
        justify-content: center;
        align-items: center;
        font-weight: bold;
        color: white;
    }
    .progress-step::before {
        counter-increment: step;
        content: counter(step);
    }
    .progress-step.active {
        background-color: var(--bs-primary);
    }
    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: scale(0.95);
        }
        to {
            opacity: 1;
            transform: scale(1);
        }
    }
</style>

<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card shadow-lg border-0 rounded-4">
                <div class="card-body p-5">
                    <h1 class="card-title text-center fw-bold mb-2">Registrasi Pasien Baru</h1>
                    <p class="card-text text-center text-muted mb-5">Lengkapi data Anda dalam 3 langkah mudah.</p>

                    <!-- Progress Bar -->
                    <div class="progress-bar">
                        <div class="progress" id="progress"></div>
                        <div class="progress-step active" data-title="Akun"></div>
                        <div class="progress-step" data-title="Data Diri"></div>
                        <div class="progress-step" data-title="Selesai"></div>
                    </div>

                    <!-- Form Utama -->
                    <form action="?url=auth/processRegister" method="POST" enctype="multipart/form-data">
                        <!-- Step 1: Informasi Akun & Kontak -->
                        <div class="form-step active">
                            <h4 class="text-center fw-semibold mb-4">Langkah 1: Informasi Akun</h4>
                            <div class="mb-3">
                                <label for="email" class="form-label">Alamat Email</label>
                                <input type="email" class="form-control form-control-lg" id="email" name="email" placeholder="contoh@email.com" required>
                            </div>
                            <div class="mb-3">
                                <label for="nomor_telepon" class="form-label">Nomor Telepon</label>
                                <input type="tel" class="form-control form-control-lg" id="nomor_telepon" name="nomor_telepon" placeholder="081234567890" required>
                            </div>
                            <div class="mb-3">
                                <label for="password" class="form-label">Kata Sandi</label>
                                <input type="password" class="form-control form-control-lg" id="password" name="password" required>
                            </div>
                            <div class="text-end">
                                <a href="#" class="btn btn-primary btn-lg next-btn">Selanjutnya &rarr;</a>
                            </div>
                        </div>

                        <!-- Step 2: Data Diri -->
                        <div class="form-step">
                            <h4 class="text-center fw-semibold mb-4">Langkah 2: Data Diri</h4>
                            <div class="mb-3">
                                <label for="nama_lengkap" class="form-label">Nama Lengkap</label>
                                <input type="text" class="form-control form-control-lg" id="nama_lengkap" name="nama_lengkap" placeholder="Sesuai KTP" required>
                            </div>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="tanggal_lahir" class="form-label">Tanggal Lahir</label>
                                    <input type="date" class="form-control form-control-lg" id="tanggal_lahir" name="tanggal_lahir" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Jenis Kelamin</label>
                                    <select class="form-select form-select-lg" name="jenis_kelamin" required>
                                        <option value="" disabled selected>-- Pilih --</option>
                                        <option value="Laki-laki">Laki-laki</option>
                                        <option value="Perempuan">Perempuan</option>
                                    </select>
                                </div>
                            </div>
                             <div class="mb-3">
                                <label for="alamat" class="form-label">Alamat Lengkap</label>
                                <textarea class="form-control form-control-lg" id="alamat" name="alamat" rows="3" required></textarea>
                            </div>
                            <div class="d-flex justify-content-between">
                                <a href="#" class="btn btn-light btn-lg prev-btn">&larr; Sebelumnya</a>
                                <a href="#" class="btn btn-primary btn-lg next-btn">Selanjutnya &rarr;</a>
                            </div>
                        </div>

                        <!-- Step 3: Foto & Konfirmasi -->
                        <div class="form-step">
                            <h4 class="text-center fw-semibold mb-4">Langkah 3: Foto & Konfirmasi</h4>
                             <div class="mb-4">
                                <label for="foto_profil" class="form-label">Upload Foto Profil (Opsional)</label>
                                <input class="form-control form-control-lg" type="file" id="foto_profil" name="foto_profil" accept="image/png, image/jpeg">
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="" id="terms" required>
                                <label class="form-check-label" for="terms">
                                    Saya menyatakan bahwa semua data yang saya isi adalah benar dan dapat dipertanggungjawabkan.
                                </label>
                            </div>
                            <div class="d-flex justify-content-between mt-4">
                                <a href="#" class="btn btn-light btn-lg prev-btn">&larr; Sebelumnya</a>
                                <button type="submit" class="btn btn-success btn-lg">Daftar Sekarang</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener("DOMContentLoaded", function() {
    const nextBtns = document.querySelectorAll(".next-btn");
    const prevBtns = document.querySelectorAll(".prev-btn");
    const formSteps = document.querySelectorAll(".form-step");
    const progressSteps = document.querySelectorAll(".progress-step");

    let formStepsNum = 0;

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
        formSteps.forEach((formStep) => {
            formStep.classList.contains("active") &&
                formStep.classList.remove("active");
        });
        formSteps[formStepsNum].classList.add("active");
    }

    function updateProgressbar() {
        progressSteps.forEach((progressStep, idx) => {
            if (idx < formStepsNum + 1) {
                progressStep.classList.add("active");
            } else {
                progressStep.classList.remove("active");
            }
        });

        const progressActive = document.querySelectorAll(".progress-step.active");
        const progress = document.getElementById("progress");
        progress.style.width = ((progressActive.length - 1) / (progressSteps.length - 1)) * 100 + "%";
    }

    function validateStep() {
        let isValid = true;
        const currentStep = formSteps[formStepsNum];
        const inputs = currentStep.querySelectorAll("input[required], select[required], textarea[required]");

        inputs.forEach(input => {
            if (!input.value.trim()) {
                isValid = false;
                input.classList.add('is-invalid');
            } else {
                input.classList.remove('is-invalid');
            }
        });
        
        if (!isValid) {
            alert('Harap isi semua kolom yang wajib diisi.');
        }
        return isValid;
    }
});
</script>

<?php 
require_once __DIR__ . '/../layouts/footer_public.php'; 
?>
