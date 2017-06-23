#!/bin/bash
composer install

cp .env.local .env

php artisan search:index:company
php artisan db:seed
