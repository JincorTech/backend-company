#!/bin/bash
composer install

cp .env.test .env
chmod -R 0777 storage

./vendor/bin/codecept run unit