#!/bin/bash

echo "==> PHP: $(php --version | head -1)"
echo "==> PORT: ${PORT:-8000}"
echo "==> APP_KEY set: $([ -n "$APP_KEY" ] && echo YES || echo NO)"
echo "==> DB_HOST: ${DB_HOST:-not set}"

# Generate APP_KEY jika belum diset
if [ -z "$APP_KEY" ]; then
    echo "==> APP_KEY kosong, generate otomatis..."
    APP_KEY=$(php -r "echo 'base64:'.base64_encode(random_bytes(32));")
    export APP_KEY
    echo "APP_KEY=${APP_KEY}" >> .env
fi

echo "==> Caching config, routes, views..."
php artisan config:cache 2>&1 || true
php artisan route:cache  2>&1 || true
php artisan view:cache   2>&1 || true
php artisan storage:link        2>/dev/null || true

echo "==> Running migrations..."
php artisan migrate --force 2>&1 || echo "Migration failed, check DB env vars"

echo "==> Seeding (skip jika data sudah ada)..."
php artisan db:seed --force 2>&1 || echo "Seed failed or skipped"

echo "==> Starting HTTP server on 0.0.0.0:${PORT:-8000}..."
exec php artisan serve --host=0.0.0.0 --port=${PORT:-8000}
