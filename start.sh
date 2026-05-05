#!/bin/bash
set -e

echo "==> Caching config, routes, views..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

echo "==> Creating storage symlink..."
php artisan storage:link --quiet 2>/dev/null || true

echo "==> Running migrations..."
php artisan migrate --force

echo "==> Seeding initial data (if needed)..."
php artisan db:seed --force

echo "==> Starting server on port ${PORT:-8000}..."
php artisan serve --host=0.0.0.0 --port=${PORT:-8000}
