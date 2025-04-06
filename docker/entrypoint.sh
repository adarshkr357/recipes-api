#!/bin/bash
set -e

# Start PHP-FPM in daemon mode.
php-fpm -D

# Wait for PHP-FPM to start and listen on port 9000.
sleep 2

# Start Nginx in the foreground.
nginx -g "daemon off;"
