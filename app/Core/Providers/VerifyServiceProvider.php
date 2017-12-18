<?php

namespace App\Core\Providers;

use GuzzleHttp\Client;
use Illuminate\Support\ServiceProvider;
use JincorTech\VerifyClient\Interfaces\VerifyService;
use JincorTech\VerifyClient\VerifyClient;

class VerifyServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->bind(VerifyService::class, function ($app) {
            $httpClient = new Client([
                'base_uri' => config('services.verification.uri'),
                'headers' => [
                    'Authorization' => 'Bearer ' . config('services.verification.jwt'),
                    'Accept' => 'application/vnd.jincor+json; version=' . config('services.verification.version', 1),
                    'Content-Type' => 'application/json'
                ],
                'json' => true
            ]);

            return new VerifyClient($httpClient);
        });
    }
}