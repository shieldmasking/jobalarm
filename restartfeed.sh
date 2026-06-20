#!/bin/bash
kill $(ps aux | grep '[n]ode' | awk '{print $2}')
forever start -o twitapp.log -e twiterr.log -m 10000 --minUptime=1 --spinSleepTime=1 /home/tweetedjobs/twitapp/app.js

