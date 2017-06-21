#!/bin/bash
composer install

cp .env.local .env

php artisan db:seed
