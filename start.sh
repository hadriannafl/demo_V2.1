#!/bin/bash

echo "==> PHP: $(php --version | head -1)"
echo "==> PORT: ${PORT:-80}"
echo "==> APP_KEY set: $([ -n "$APP_KEY" ] && echo YES || echo NO)"
echo "==> DB_HOST: ${DB_HOST:-not set}"

# Generate APP_KEY jika belum diset
if [ -z "$APP_KEY" ]; then
    echo "==> Generating APP_KEY..."
    APP_KEY=$(php -r "echo 'base64:'.base64_encode(random_bytes(32));")
    export APP_KEY
    echo "APP_KEY=${APP_KEY}" >> .env
fi

# Update Apache port sesuai PORT dari Railway
PORT=${PORT:-80}
sed -i "s/Listen \${PORT:-80}/Listen ${PORT}/" /etc/apache2/ports.conf 2>/dev/null || true
sed -i "s/\*:\${PORT:-80}/*:${PORT}/" /etc/apache2/sites-available/000-default.conf 2>/dev/null || true

echo "==> Caching config..."
php artisan config:cache 2>&1 || true
php artisan route:cache  2>&1 || true
php artisan view:cache   2>&1 || true
php artisan storage:link        2>/dev/null || true

echo "==> Running migrations..."
php artisan migrate --force 2>&1 || echo "Migration failed - check DB vars"

echo "==> Seeding (skip jika sudah ada)..."
php artisan db:seed --force 2>&1 || echo "Seed skipped"

echo "==> Starting Apache on port ${PORT}..."
exec apache2-foreground
