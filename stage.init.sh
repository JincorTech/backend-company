#!/bin/bash
composer install
cp .env.example .env
chmod -R 0777 storage
chmod -R 0777 app/Core/DoctrineProxies
chmod -R 0777 app/Core/DoctrineHydrators


php artisan search:index:company
php artisan db:seed
