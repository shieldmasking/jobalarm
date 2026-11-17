#!/bin/bash
rsync -avz --exclude='.git' \
  --exclude='deploy.sh' \
  --exclude='node_modules' \
  ./ tweetedjobs@jobalarm.com:/home/tweetedjobs/public_html/jobalarm/
