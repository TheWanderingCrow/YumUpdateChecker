#!/bin/bash

# get php path
php_path=$(which php)

# get pwd
pwd=$(pwd)

cd $pwd/YumUpdateChecker

$php_path updat0r.php &
