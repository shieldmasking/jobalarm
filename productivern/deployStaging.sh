#!/bin/bash

REPO_DIR="/home/tweetedjobs/public_html/staging"

cd "$REPO_DIR" || { echo "ERROR: Cannot cd to $REPO_DIR"; exit 1; }

echo "Pulling latest from GitHub (staging branch)..."
git pull origin staging

echo "Done."
