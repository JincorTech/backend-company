#!/bin/bash
composer install
cp .env.example .env
php artisan db:seed
