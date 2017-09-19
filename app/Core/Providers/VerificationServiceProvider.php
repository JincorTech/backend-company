<?php
/**
 * Copyright (c) 2017  Universal Business Network - All rights reserved.
 *
 * Created by alekns <email: alexander.sedelnikov@gmail.com>
 * Date: 13.09.17
 * Time: 21:41
 */

namespace App\Core\Providers;

use App\Core\Services\Verification\DummyVerificationService;
use App\Core\Services\Verification\RestVerificationService;
use App\Core\Services\Verification\VerificationService;
use Illuminate\Support\ServiceProvider;

class VerificationServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->bind(VerificationService::class, DummyVerificationService::class);
    }
}
