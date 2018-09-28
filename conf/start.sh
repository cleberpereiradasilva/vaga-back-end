#!/bin/bash

# ----------------------------------------------------------------------
# Create the .env file if it does not exist.
# ----------------------------------------------------------------------

if [[ ! -f "/var/www/.env" ]] && [[ -f "/var/www/.env.example" ]];
then
cp /var/www/.env.example /var/www/.env
fi

# ----------------------------------------------------------------------
# Run Composer
# ----------------------------------------------------------------------

if [[ ! -d "/var/www/vendor" ]];
then
rm -rf /var/www/vendor
cd /var/www
composer update
composer dump-autoload -o
fi
chown nginx:nginx /var/www/ -R
cd /var/www
php artisan migrate
php artisan passport:install
php artisan db:seed --class=AdminSeeder
echo '-------------------------------------------'
echo '-----  TUDO PRONTO PARA PODER USAR    -----'
echo '-----  API: http://localhost:8080/api -----'
echo '----- MyAdmin: http://localhost:8081  -----'
echo '-----    Mysql database: dbserver     -----'
echo '-----   Mysql root pass: root1pass    -----'
echo '-------------------------------------------'
# ----------------------------------------------------------------------
# Start supervisord
# ----------------------------------------------------------------------
exec /usr/bin/supervisord -n -c /etc/supervisord.conf
