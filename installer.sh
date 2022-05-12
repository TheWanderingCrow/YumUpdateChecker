#!/bin/bash

# create backup of crontab
crontab -l > /tmp/cron_bkp

# get pwd
pwd=$(pwd)

# add new cronjob
echo "@daily $pwd/runner.sh >/dev/null 2>&1" >> /tmp/cron_bkp

# update crontab
crontab /tmp/cron_bkp

# remove backup
rm /tmp/cron_bkp