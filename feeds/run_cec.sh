#!/bin/bash
SRV="tweetedjobs@jobalarm.com"
CSV="C:/Users/rstre/Documents/My Web Sites/jobalarm.biz/feeds/cec.csv"

echo "Uploading cec.csv..."
scp "$CSV" $SRV:/home/tweetedjobs/feeds/cec.csv

echo "Running cec.php..."
ssh $SRV "php /home/tweetedjobs/public_html/feeds/cec.php"
