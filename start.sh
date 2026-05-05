#!/bin/bash

echo "==> PHP: $(php --version | head -1)"
echo "==> PORT: ${PORT:-8000}"

# Generate APP_KEY if missing
if [ -z "$APP_KEY" ]; then
    echo "==> Generating APP_KEY..."
    php artisan key:generate --force
fi

# Always use a fixed SQLite path regardless of DB_DATABASE env var
export DB_CONNECTION=sqlite
export DB_DATABASE=/app/database/database.sqlite

mkdir -p /app/database
touch /app/database/database.sqlite
echo "==> SQLite database ready at $DB_DATABASE"

php artisan config:clear 2>/dev/null || true
php artisan cache:clear  2>/dev/null || true
php artisan view:clear   2>/dev/null || true
php artisan storage:link 2>/dev/null || true

echo "==> Running migrations..."
php artisan migrate --force

echo "==> Seeding demo data..."
php artisan db:seed --force

echo "==> Starting server on 0.0.0.0:${PORT:-8000}..."
exec php artisan serve --host=0.0.0.0 --port=${PORT:-8000}
