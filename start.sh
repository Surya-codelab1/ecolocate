#!/bin/bash
set -e

echo "===> Clearing cached config..."
php artisan config:clear

echo "===> Caching fresh config..."
php artisan config:cache

echo "===> Running database migrations..."
php artisan migrate --force

echo "===> Seeding database..."
php artisan db:seed --force

echo "===> Linking storage..."
php artisan storage:link || true

echo "===> Starting queue worker in background..."
php artisan queue:work --daemon --tries=3 &

echo "===> Starting web server..."
php artisan serve --host 0.0.0.0 --port ${PORT:-10000}