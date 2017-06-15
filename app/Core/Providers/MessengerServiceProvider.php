<?php
/**
 * Created by PhpStorm.
 * User: hlogeon
 * Date: 14/06/2017
 * Time: 05:15
 */

namespace App\Core\Providers;


use Illuminate\Support\ServiceProvider;
use App\Core\Interfaces\MessengerServiceInterface;
use App\Core\Services\MessengerService;

class MessengerServiceProvider extends ServiceProvider
{

    public function register()
    {
        $this->app->bind(MessengerServiceInterface::class, MessengerService::class);
    }

}