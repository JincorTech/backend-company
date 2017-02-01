<?php

namespace App\Core\Providers;

use App\Domains\Employee\Events\EmployeeRegistered;
use App\Domains\Employee\Events\PasswordChanged;
use App\Domains\Employee\Events\RestorePasswordRequested;
use App\Domains\Employee\Handlers\EmployeeRegistered as EmployeeRegisteredHandler;
use App\Domains\Employee\Handlers\PasswordChanged as PasswordChangedHandler;
use App\Domains\Employee\Handlers\SendRestorePasswordEmail;
use App\Domains\Employee\Handlers\SendVerificationEmail;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use App\Domains\Employee\Events\VerificationEmailRequested;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        EmployeeRegistered::class => [
            EmployeeRegisteredHandler::class,
        ],
        PasswordChanged::class => [
            PasswordChangedHandler::class,
        ],
        VerificationEmailRequested::class => [
            SendVerificationEmail::class,
        ],
        RestorePasswordRequested::class => [
            SendRestorePasswordEmail::class
        ]
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();

        //
    }
}
