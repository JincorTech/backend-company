<?php
/**
 * Created by PhpStorm.
 * User: Artemii
 * Date: 05.06.2017
 * Time: 20:19
 */

namespace App\Core\Providers;

use Illuminate\Support\ServiceProvider;

class IdentityServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->bind(\App\Core\Interfaces\IdentityInterface::class, \App\Core\Services\IdentityService::class);
    }
}