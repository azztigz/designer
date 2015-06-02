#!/usr/bin/env bash

echo "Fetching the repo for the current selected branch."
git fetch
echo "Pulling for any updates now.."
git pull
echo "Pulled. Doing needed things."
echo "Putting the system under maintenance mode."
php artisan down
if [ "$1" != "--skip-composer" ]
then
    echo "Updating vendor files."
    [ -e composer.lock ] && composer update
    [ ! -e composer.lock ] && composer install
fi
echo "Doing migration if there is any."
php -d memory_limit=1G artisan migrate || echo "Please fix your database connection. Maybe you forget to run database-{env}.sh file."
echo "Preparing the system."
chmod -Rf 777 storage
rm -Rf storage/framework/views/*
composer dump-autoload -o
php artisan queue:restart
php artisan up
echo "System is now online and successfully updated!"