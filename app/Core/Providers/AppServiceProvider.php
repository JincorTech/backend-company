<?php

namespace App\Core\Providers;

use App\Core\Services\ImageService;
use App\Core\Services\JWTService;
use Illuminate\Support\ServiceProvider;
use Storage;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(ImageService::class, function() {
            $storage = Storage::disk('s3');
            return new ImageService($storage);
        });

        $this->app->singleton(JWTService::class, function() {
           return new JWTService(config('jwt.key'));
        });
    }
}
