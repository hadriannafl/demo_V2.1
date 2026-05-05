#!/bin/bash

echo "==> PHP: $(php --version | head -1)"
echo "==> PORT: ${PORT:-8000}"
echo "==> APP_KEY: $([ -n "$APP_KEY" ] && echo SET || echo MISSING)"
echo "==> DB_HOST: ${DB_HOST:-not set}"

# Bersihkan cache lama agar tidak pakai config yang salah
php artisan config:clear 2>/dev/null || true
php artisan cache:clear  2>/dev/null || true
php artisan view:clear   2>/dev/null || true

php artisan storage:link 2>/dev/null || true

echo "==> Running migrations..."
php artisan migrate --force 2>&1 || echo "Migration failed - check DB env vars"

echo "==> Seeding..."
php artisan db:seed --force 2>&1 || echo "Seed skipped"

echo "==> Starting server on 0.0.0.0:${PORT:-8000}..."
exec php artisan serve --host=0.0.0.0 --port=${PORT:-8000}
