<?php

namespace App\Core\Providers;

use App\Domains\Company\Events\EmployeeRegisteredEvent;
use App\Domains\Company\Handlers\EmployeeRegistered;
use Illuminate\Support\Facades\Event;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        EmployeeRegisteredEvent::class => [
            EmployeeRegistered::class,
        ],
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
