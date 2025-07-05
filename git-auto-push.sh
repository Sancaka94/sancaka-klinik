#!/bin/bash
COMMIT_MSG=${1:-"auto update"}
git add .
git commit -m "$COMMIT_MSG"
git push origin main
