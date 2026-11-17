#!/bin/bash

REPO_DIR="/home/tweetedjobs/public_html/productivern"

cd "$REPO_DIR" || { echo "ERROR: Cannot cd to $REPO_DIR"; exit 1; }

echo "Pulling latest from GitHub..."
git pull origin master

echo "Done."
