<?php
// Here you can initialize variables that will be available to your tests

require 'bootstrap/autoload.php';
/** @var \Illuminate\Foundation\Application $app */
$app = require 'bootstrap/app.php';
$app->loadEnvironmentFrom('.env.test');
$app->instance('request', new \Illuminate\Http\Request);
$app->make('Illuminate\Contracts\Http\Kernel')->bootstrap();
