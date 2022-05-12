#!/bin/bash

# install dependencies
echo "Installing dependencies"
composer install

# ask for webhook url
echo "Enter webhook url:"
read webhook_url

# generate .env file
echo "Generating .env file..."
echo "SLACK_CHANNEL_HOOK_INFO=$webhook_url" > .env

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

echo "Installation complete."
