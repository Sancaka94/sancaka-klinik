#!/bin/bash

# Script: git-auto-push.sh
# Fungsi: Menambahkan, commit dan push otomatis ke branch main
# Penggunaan: ./git-auto-push.sh "pesan commit"

# Ambil pesan commit dari argumen pertama, default ke "auto update"
COMMIT_MSG=${1:-"auto update"}

# Jalankan perintah Git
echo "⏳ Menambahkan perubahan ke Git..."
git add .

echo "📝 Commit dengan pesan: \"$COMMIT_MSG\""
git commit -m "$COMMIT_MSG"

echo "🚀 Push ke branch main..."
git push origin main

echo "✅ Selesai!"
