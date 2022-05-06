#!/bin/bash
set -e

if [ -d vendor/bin ]; then
    php bin/console app:sync-migrate
    php bin/console cache:clear
fi

exec docker-php-entrypoint "$@"
