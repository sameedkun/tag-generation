# syntax=docker/dockerfile:1

# ---- Stage 1: PHP dependencies (no dev) ----
FROM composer:2 AS vendor
WORKDIR /app
COPY composer.json composer.lock ./
RUN composer install --no-dev --no-scripts --no-autoloader --prefer-dist --no-interaction --ignore-platform-reqs
COPY . .
RUN composer dump-autoload --no-dev --optimize --no-scripts

# ---- Stage 2: Frontend assets (Tailwind scans vendor views, so it needs vendor/) ----
FROM node:22-alpine AS assets
WORKDIR /app
COPY package.json package-lock.json ./
RUN npm ci --ignore-scripts
COPY --from=vendor /app /app
RUN npm run build

# ---- Stage 3: Runtime (nginx + php-fpm + supervisor) ----
FROM php:8.4-fpm-alpine AS app

RUN apk add --no-cache nginx supervisor curl bash tzdata \
        libpng libjpeg-turbo freetype libwebp libzip icu-libs oniguruma \
    && apk add --no-cache --virtual .build-deps $PHPIZE_DEPS \
        libpng-dev libjpeg-turbo-dev freetype-dev libwebp-dev libzip-dev icu-dev oniguruma-dev linux-headers \
    && docker-php-ext-configure gd --with-freetype --with-jpeg --with-webp \
    && docker-php-ext-install -j"$(nproc)" gd pdo_mysql bcmath intl pcntl zip opcache exif \
    && pecl install redis \
    && docker-php-ext-enable redis \
    && apk del .build-deps \
    && rm -rf /tmp/pear /var/cache/apk/*

COPY docker/php.ini /usr/local/etc/php/conf.d/zz-app.ini
COPY docker/php-fpm.conf /usr/local/etc/php-fpm.d/zz-app.conf
COPY docker/nginx.conf /etc/nginx/nginx.conf
COPY docker/supervisord.conf /etc/supervisor/supervisord.conf
COPY docker/entrypoint.sh /usr/local/bin/entrypoint.sh
RUN chmod +x /usr/local/bin/entrypoint.sh

WORKDIR /var/www/html
COPY --from=vendor --chown=www-data:www-data /app /var/www/html
COPY --from=assets --chown=www-data:www-data /app/public/build /var/www/html/public/build

RUN mkdir -p storage/framework/{cache,sessions,views} storage/logs bootstrap/cache \
    && chown -R www-data:www-data storage bootstrap/cache \
    && php artisan package:discover --ansi

# ---- Tunables (override in Dokploy > Environment) ----
ENV QUEUE_WORKERS=1 \
    QUEUE_NAME=default \
    QUEUE_TRIES=3 \
    QUEUE_TIMEOUT=60 \
    RUN_MIGRATIONS=true \
    RUN_SCHEDULER=true \
    PHP_FPM_MAX_CHILDREN=20

EXPOSE 80
HEALTHCHECK --interval=30s --timeout=5s --start-period=40s --retries=3 \
    CMD curl -fs http://127.0.0.1/up || exit 1

ENTRYPOINT ["entrypoint.sh"]
CMD ["supervisord", "-c", "/etc/supervisor/supervisord.conf"]
