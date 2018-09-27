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
chown nginx:nginx /var/www/ -R

fi
cd /var/www
php artisan migrate
# ----------------------------------------------------------------------
# Start supervisord
# ----------------------------------------------------------------------

exec /usr/bin/supervisord -n -c /etc/supervisord.conf