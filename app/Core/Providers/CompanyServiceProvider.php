<?php
/**
 * Created by PhpStorm.
 * User: artem
 * Date: 19.06.17
 * Time: 18:12
 */

namespace App\Core\Providers;
use Illuminate\Support\ServiceProvider;
use App\Applications\Company\Interfaces\Company\CompanyServiceInterface;
use App\Applications\Company\Services\Company\CompanyService;

class CompanyServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->bind(CompanyServiceInterface::class, CompanyService::class);
    }
}
