<?php
/**
 * Created by PhpStorm.
 * User: artem
 * Date: 19.06.17
 * Time: 14:50
 */

namespace App\Core\Providers;

use Illuminate\Support\ServiceProvider;
use App\Applications\Company\Interfaces\Employee\EmployeeServiceInterface;
use App\Applications\Company\Services\Employee\EmployeeService;
use App\Domains\Employee\Interfaces\EmployeeRepositoryInterface;
use Doctrine\ODM\MongoDB\DocumentManager;
use App\Domains\Employee\Entities\Employee;
use App\Domains\Employee\Entities\EmployeeVerification;
use App\Applications\Company\Interfaces\Employee\EmployeeVerificationServiceInterface;
use App\Domains\Employee\Interfaces\EmployeeVerificationRepositoryInterface;
use App\Applications\Company\Services\Employee\EmployeeVerificationService;

class EmployeeServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->bind(EmployeeServiceInterface::class, EmployeeService::class);

        $this->app->bind(
            EmployeeVerificationServiceInterface::class,
            EmployeeVerificationService::class
        );

        $this->app->instance(
            EmployeeRepositoryInterface::class,
            $this->app->make(DocumentManager::class)->getRepository(Employee::class)
        );

        $this->app->instance(
            EmployeeVerificationRepositoryInterface::class,
            $this->app->make(DocumentManager::class)->getRepository(EmployeeVerification::class)
        );
    }
}
