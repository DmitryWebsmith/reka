#!/bin/sh

set -e

composer install
cp .env.example .env
chmod 777 /var/www/storage
chmod 777 /var/www/storage/framework/views/
chmod 777 /var/www/bootstrap/cache
php artisan key:generate
php artisan migrate
php artisan storage:link

# После выполнения необходимых команд, запускаем основной процесс
exec "$@"
