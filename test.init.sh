#!/bin/bash
composer install

cp .env.test .env
chmod -R 0777 storage
chmod -R 0777 app/Core/DoctrineProxies

php artisan search:index:company
php artisan db:seed

./vendor/bin/codecept run unit