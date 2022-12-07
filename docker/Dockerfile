
# ==================================
# PHP BASE

FROM php:8.1-fpm-alpine as php_base

RUN apk update \
  && apk add --no-cache bash icu-dev icu-data-full postgresql-dev libzip-dev openldap-dev libpng-dev oniguruma-dev autoconf gcc g++ make fcgi optipng jpegoptim libjpeg libpng imagemagick ghostscript git libxml2-dev gnu-libiconv screen \
  \
  && docker-php-ext-install intl mbstring opcache pdo pdo_pgsql pdo_mysql zip bcmath sockets ldap gd xml pcntl \
  \
  && echo "date.timezone = Europe/Paris" >> /usr/local/etc/php/conf.d/timezone.ini \
  \
  && echo 'memory_limit = 512M' >> /usr/local/etc/php/conf.d/docker-php-memlimit.ini \
  && echo 'short_open_tag = off' >> /usr/local/etc/php/conf.d/docker-php-short-open-tag.ini \
  && echo 'expose_php = off' >> /usr/local/etc/php/conf.d/docker-php-expose.ini \
  && echo 'post_max_size = 10M' >> /usr/local/etc/php/conf.d/docker-php-upload.ini \
  && echo 'upload_max_filesize = 10M' >> /usr/local/etc/php/conf.d/docker-php-upload.ini

WORKDIR /app

COPY assets assets
COPY bin bin
COPY config config
COPY migrations migrations

RUN mkdir public

COPY public/index.php public
COPY src src
COPY templates templates
COPY translations translations
COPY .env \
  composer.json \
  composer.lock \
  package.json \
  package-lock.json \
  symfony.lock \
  webpack.config.js \
  ./

COPY docker/php/docker-php-migrations-entrypoint.sh /usr/local/bin/docker-php-migrations-entrypoint
RUN chmod +x /usr/local/bin/docker-php-migrations-entrypoint
ENTRYPOINT ["docker-php-migrations-entrypoint"]
CMD ["php-fpm"]

# ==================================
# PHP installer

FROM php_base as php_installer

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

RUN APP_ENV=prod composer install --optimize-autoloader --no-progress --no-interaction --no-dev

# ==================================
# PHP Node builder

FROM node:16 as node_builder
ARG NPM_TOKEN

WORKDIR /app

COPY --from=php_base /app /app/

RUN printf "@fortawesome:registry=https://npm.fontawesome.com/\n//npm.fontawesome.com/:_authToken=%s" $NPM_TOKEN > .npmrc \
  && npm ci \
  && npm run build \
  && rm -f .npmrc

# ==================================
# PHP

FROM php_base as php

COPY --from=php_installer /app /app/
COPY --from=node_builder /app/public/build /app/public/build/

COPY docker/php/docker-healthcheck.sh /usr/local/bin/docker-healthcheck

RUN chmod +x /usr/local/bin/docker-healthcheck  \
  && set -xe && echo "ping.path = /ping" >> /usr/local/etc/php-fpm.d/zz-docker.conf \
  && set -xe && echo "pm.max_children = 20" >> /usr/local/etc/php-fpm.d/zz-docker.conf

RUN mkdir -p var && chown -R www-data:www-data var/

USER www-data

HEALTHCHECK --interval=10s --timeout=3s --retries=3 CMD ["docker-healthcheck"]

# ==================================
# Nginx

FROM nginx:1.21-alpine as nginx

COPY --from=php_installer /app/public/bundles /app/public/bundles/
COPY --from=node_builder /app/public/build /app/public/build/
COPY docker/nginx/default.conf.template /etc/nginx/templates/default.conf.template

HEALTHCHECK --interval=10s --timeout=3s --retries=3 CMD ["curl", "localhost"]