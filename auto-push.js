const fs = require('fs');
const { exec } = require('child_process');

let timeout;

const watchDir = '.'; // Pantau folder utama
console.log('⏱️ Memantau perubahan file...');

fs.watch(watchDir, { recursive: true }, (eventType, filename) => {
  if (filename && filename.endsWith('.php')) {
    console.log(`💾 File disimpan: ${filename}`);

    // Delay 2 detik agar tidak terlalu sering
    clearTimeout(timeout);
    timeout = setTimeout(() => {
      exec(`git add . && git commit -m "Auto save ${new Date().toISOString()}" && git push`, (err, stdout, stderr) => {
        if (err) {
          console.error('❌ Error:', stderr);
        } else {
          console.log('✅ Git push berhasil:\n', stdout);
        }
      });
    }, 2000);
  }
});
