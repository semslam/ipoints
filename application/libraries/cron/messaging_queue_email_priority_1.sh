#!/bin/bash
# run command
cd /var/www/html
php index.php cli utilities processQueueMessages "Email" 1
