#!/bin/bash

echo "==> NexaERP startup..."

php artisan config:cache  || echo "config:cache failed, continuing..."
php artisan route:cache   || echo "route:cache failed, continuing..."
php artisan view:cache    || echo "view:cache failed, continuing..."

php artisan storage:link --quiet 2>/dev/null || true

echo "==> Running migrations..."
php artisan migrate --force || echo "Migration failed, continuing..."

echo "==> Seeding initial data (if needed)..."
php artisan db:seed --force || echo "Seed failed, continuing..."

echo "==> Starting server on port ${PORT:-8000}..."
exec php artisan serve --host=0.0.0.0 --port=${PORT:-8000}
