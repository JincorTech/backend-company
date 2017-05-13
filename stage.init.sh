#!/bin/bash
composer install
cp .env.example .env
chmod -R 0777 storage
chomd -R 0777 app/Core/DoctrineProxies
chmod -R 0777 app/Core/DoctrineHydrators
php artisan db:seed
