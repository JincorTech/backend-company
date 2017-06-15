<?php
/**
 * Created by PhpStorm.
 * User: Artemii
 * Date: 05.06.2017
 * Time: 20:19
 */

namespace App\Core\Providers;

use Illuminate\Support\ServiceProvider;
use App\Core\Interfaces\IdentityInterface;
use App\Core\Services\IdentityService;

class IdentityServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->bind(IdentityInterface::class, IdentityService::class);
    }
}
