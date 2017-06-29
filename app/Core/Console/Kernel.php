<?php

namespace App\Core\Console;

use App\Applications\Company\Console\Commands\BuildIndex;
use App\Applications\Company\Console\Commands\DropCompanies;
use App\Applications\Company\Console\Commands\DropIndex;
use App\Applications\Company\Console\Commands\GetIndex;
use App\Applications\Company\Console\Commands\SeedRandomIndex;
use App\Applications\Company\Console\Commands\SeedRandomCompanies;
use Illuminate\Console\Scheduling\Schedule;
use App\Applications\Company\Console\Commands\SeedRealIndex;
use App\Applications\Company\Console\Commands\SeedEmployeesIdentity;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        GetIndex::class,
        SeedRandomIndex::class,
        BuildIndex::class,
        DropIndex::class,
        SeedRandomCompanies::class,
        DropCompanies::class,
        SeedRealIndex::class,
        SeedEmployeesIdentity::class
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // $schedule->command('inspire')
        //          ->hourly();
    }
}
