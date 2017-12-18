<?php
/**
 * Created by PhpStorm.
 * User: hlogeon
 * Date: 01/12/2017
 * Time: 01:01
 */

namespace App\Core\Providers;

use App\Core\Interfaces\WalletsServiceInterface;
use App\Core\Services\WalletsService;
use Illuminate\Support\ServiceProvider;

class WalletsServiceProvider extends ServiceProvider
{

    public function register()
    {
        $this->app->bind(WalletsServiceInterface::class, WalletsService::class);
    }
}