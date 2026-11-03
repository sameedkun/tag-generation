#!/bin/bash
set -e
cd /var/www/html

mkdir -p storage/framework/{cache,sessions,views} storage/logs bootstrap/cache

if [ "${RUN_SCHEDULER:-true}" = "true" ]; then
    export SCHEDULER_AUTOSTART=true
else
    export SCHEDULER_AUTOSTART=false
fi

# Worker count is read by supervisord via %(ENV_QUEUE_WORKERS)s
export QUEUE_WORKERS="${QUEUE_WORKERS:-1}"

php artisan storage:link --force >/dev/null 2>&1 || true
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan event:cache

# Run migrations from ONE replica only (set RUN_MIGRATIONS=false on extra replicas)
if [ "${RUN_MIGRATIONS:-true}" = "true" ]; then
    php artisan migrate --force
fi

# Artisan above runs as root; hand any files it created back to php-fpm's user
chown -R www-data:www-data storage bootstrap/cache

exec "$@"
