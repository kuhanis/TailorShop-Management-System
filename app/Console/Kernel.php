<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        Commands\PopulateCustomerNames::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // Run cleanup every minute to ensure timely removal
        $schedule->call('App\Http\Controllers\RetentionController@cleanupExpiredData')
            ->name('cleanup-expired-data')
            ->everyMinute()
            ->withoutOverlapping();

        $schedule->command('links:revoke-expired')
            ->name('revoke-expired-links')
            ->everyMinute()
            ->withoutOverlapping();
            
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
