#!/bin/bash
composer install
cp .env.test .env

/var/www/companies/vendor/bin/codecept run api