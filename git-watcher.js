const chokidar = require('chokidar');
const { exec } = require('child_process');

const watcher = chokidar.watch('.', {
  ignored: /(^|[\/\\])\../, // abaikan dotfiles
  persistent: true
});

console.log("👀 Watching for file changes...");

let isRunning = false;
watcher.on('change', path => {
  if (!isRunning) {
    isRunning = true;
    console.log(`📁 File changed: ${path}`);
    exec('bash ./git-auto-push.sh "auto update from watcher"', (err, stdout, stderr) => {
      if (err) {
        console.error(`❌ Error: ${stderr}`);
      } else {
        console.log(`✅ Git Push Done:
${stdout}`);
      }
      isRunning = false;
    });
  }
});
