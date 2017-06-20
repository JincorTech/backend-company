<?php
/**
 * Created by PhpStorm.
 * User: artem
 * Date: 19.06.17
 * Time: 14:50
 */

namespace App\Core\Providers;

use Illuminate\Support\ServiceProvider;
use App\Domains\Employee\Interfaces\EmployeeServiceInterface;
use App\Domains\Employee\Services\EmployeeService;
use App\Domains\Employee\Interfaces\EmployeeRepositoryInterface;
use Doctrine\ODM\MongoDB\DocumentManager;
use App\Domains\Employee\Entities\Employee;
use App\Domains\Employee\Entities\EmployeeVerification;
use App\Domains\Employee\Interfaces\EmployeeVerificationServiceInterface;
use App\Domains\Employee\Interfaces\EmployeeVerificationRepositoryInterface;
use App\Domains\Employee\Services\EmployeeVerificationService;

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
