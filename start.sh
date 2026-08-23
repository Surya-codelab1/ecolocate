#!/bin/bash
set -e

if [ ! -f database/database.sqlite ]; then
    touch database/database.sqlite
fi

php artisan config:clear
php artisan migrate --force
php artisan storage:link || true

php artisan serve --host 0.0.0.0 --port ${PORT:-10000}