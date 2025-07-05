<?php
// controllers/DebugController.php

// Pastikan tidak ada output lain sebelum session_start()
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

class DebugController {

    /**
     * Menampilkan berbagai informasi untuk debugging.
     */
    public function index() {
        // Mengatur header agar browser menampilkan sebagai HTML
        header('Content-Type: text/html; charset=utf-8');

        // Ambil pesan error dari URL jika ada
        $errorFromUrl = '';
        if (isset($_GET['error'])) {
            // Membersihkan dan mendekode pesan error dari URL
            $errorFromUrl = "Terjadi error berikut di halaman sebelumnya:\n\n" . htmlspecialchars(urldecode($_GET['error']));
        }
        ?>
        <!DOCTYPE html>
        <html lang="id">
        <head>
            <meta charset="UTF-8">
            <title>Halaman Debug</title>
            <script src="https://cdn.tailwindcss.com"></script>
            <style>
                body { font-family: monospace; padding: 2rem; background-color: #f7fafc; }
                h1 { font-size: 2rem; font-weight: bold; color: #2d3748; border-bottom: 2px solid #e2e8f0; padding-bottom: 0.5rem; margin-bottom: 1rem; }
                h2 { font-size: 1.5rem; font-weight: bold; color: #4a5568; margin-top: 2rem; margin-bottom: 0.5rem; }
                pre { background-color: #1a202c; color: #a0aec0; padding: 1.5rem; border-radius: 0.5rem; white-space: pre-wrap; word-wrap: break-word; }
                .ai-response { background-color: #e2e8f0; color: #2d3748; padding: 1.5rem; border-radius: 0.5rem; margin-top: 1rem; }
            </style>
        </head>
        <body>
            <h1>Halaman Debug Aplikasi</h1>

            <!-- 1. Menampilkan Informasi Session -->
            <h2>Informasi Session (Variabel $_SESSION)</h2>
            <pre id="session-data"><?php
                if (isset($_SESSION) && !empty($_SESSION)) {
                    print_r($_SESSION);
                } else {
                    echo "Session kosong atau belum dimulai dengan benar.";
                }
            ?></pre>

            <!-- 2. Menampilkan Informasi URL (Variabel $_GET) -->
            <h2>Informasi URL (Variabel $_GET)</h2>
            <pre id="get-data"><?php
                if (isset($_GET) && !empty($_GET)) {
                    print_r($_GET);
                } else {
                    echo "Variabel GET kosong.";
                }
            ?></pre>

            <!-- 3. Menampilkan Informasi Form (Variabel $_POST) -->
            <h2>Informasi Form (Variabel $_POST)</h2>
            <pre id="post-data"><?php
                if (isset($_POST) && !empty($_POST)) {
                    print_r($_POST);
                } else {
                    echo "Tidak ada data POST yang dikirim.";
                }
            ?></pre>

            <!-- 4. Fitur Analisis AI Gemini -->
            <h2>Analisis AI Gemini</h2>
            <div class="bg-white p-6 rounded-lg shadow-md">
                <div class="space-y-4">
                    <!-- **PERBAIKAN:** Input API Key disembunyikan -->
                    <div>
                        <label for="problemDescription" class="block text-sm font-medium text-gray-700">Deskripsi Masalah</label>
                        <textarea id="problemDescription" rows="4" class="mt-1 block w-full px-3 py-2 bg-white border border-gray-300 rounded-md shadow-sm"><?php echo $errorFromUrl; ?></textarea>
                    </div>
                    <div>
                        <button id="analyzeBtn" class="w-full inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700">
                            Minta Analisis AI
                        </button>
                    </div>
                </div>
                <div id="aiResponseContainer" class="mt-4"></div>
            </div>

            <script>
                document.getElementById('analyzeBtn').addEventListener('click', async () => {
                    // **PERBAIKAN:** API Key sekarang dipasang permanen (hardcoded)
                    const apiKey = 'AIzaSyCrFKqQZfzXvguzlgIU7HSYU68KLHnf3m4';
                    const problem = document.getElementById('problemDescription').value;
                    const responseContainer = document.getElementById('aiResponseContainer');

                    // **PERBAIKAN:** Pengecekan API Key dihapus, hanya cek deskripsi masalah
                    if (!problem) {
                        responseContainer.innerHTML = `<div class="ai-response"><p class="text-red-600">Harap masukkan deskripsi masalah.</p></div>`;
                        return;
                    }

                    responseContainer.innerHTML = `<div class="ai-response"><p>Menganalisis dengan AI, mohon tunggu...</p></div>`;

                    const sessionData = document.getElementById('session-data').innerText;
                    const getData = document.getElementById('get-data').innerText;
                    const postData = document.getElementById('post-data').innerText;

                    const prompt = \`
Anda adalah asisten ahli debugging PHP. Saya memiliki masalah dengan aplikasi klinik saya.
Tolong analisis informasi berikut dan berikan solusi dalam Bahasa Indonesia.

--- DATA SESSION ---
\${sessionData}

--- DATA GET ---
\${getData}

--- DATA POST ---
\${postData}

--- DESKRIPSI MASALAH DARI SAYA ---
\${problem}

Berikan penjelasan yang jelas tentang kemungkinan penyebab masalah dan saran perbaikan, termasuk contoh kode jika perlu.
                    \`;

                    try {
                        let chatHistory = [];
                        chatHistory.push({ role: "user", parts: [{ text: prompt }] });
                        const payload = { contents: chatHistory };
                        const apiUrl = \`https://generativelanguage.googleapis.com/v1beta/models/gemini-2.0-flash:generateContent?key=\${apiKey}\`;
                        
                        const response = await fetch(apiUrl, {
                            method: 'POST',
                            headers: { 'Content-Type': 'application/json' },
                            body: JSON.stringify(payload)
                        });

                        if (!response.ok) {
                            const errorData = await response.json();
                            throw new Error(errorData.error.message || 'Respon API tidak valid.');
                        }
                        
                        const result = await response.json();
                        
                        if (result.candidates && result.candidates[0].content && result.candidates[0].content.parts[0]) {
                            const text = result.candidates[0].content.parts[0].text;
                            responseContainer.innerHTML = \`<div class="ai-response"><h2>Hasil Analisis AI:</h2><div class="mt-2 whitespace-pre-wrap">\${text.replace(/\\n/g, '<br>')}</div></div>\`;
                        } else {
                            throw new Error('Struktur respon dari API tidak diharapkan atau konten kosong.');
                        }
                    } catch (error) {
                        console.error('Error calling Gemini API:', error);
                        responseContainer.innerHTML = \`<div class="ai-response"><p class="text-red-600">Terjadi kesalahan saat menghubungi AI: \${error.message}</p></div>\`;
                    }
                });
            </script>

        </body>
        </html>
        <?php
    }
}
