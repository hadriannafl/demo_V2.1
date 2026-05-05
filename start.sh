#!/bin/bash

echo "==> PHP: $(php --version | head -1)"
echo "==> PORT: ${PORT:-8000}"
echo "==> APP_KEY: $([ -n "$APP_KEY" ] && echo SET || echo MISSING)"
echo "==> DB_HOST: ${DB_HOST:-not set}"

php artisan config:cache 2>&1 || true
php artisan route:cache  2>&1 || true
php artisan view:cache   2>&1 || true
php artisan storage:link 2>/dev/null || true

php artisan migrate --force 2>&1 || echo "Migration failed"
php artisan db:seed --force  2>&1 || echo "Seed skipped"

echo "==> Starting php artisan serve on 0.0.0.0:${PORT:-8000}..."
exec php artisan serve --host=0.0.0.0 --port=${PORT:-8000}
