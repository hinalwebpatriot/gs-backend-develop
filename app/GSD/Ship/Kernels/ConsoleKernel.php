<?php

namespace GSD\Ship\Kernels;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as LaravelConsoleKernel;

class ConsoleKernel extends LaravelConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        //
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->command('currencyRate:sync-rates')
            ->hourly();

        $schedule->command('diamonds:sitemap:index')
            ->dailyAt('10:30');

        $schedule->command('prerender:recache:featured')
            ->everyFourHours();

        /*$schedule->command('shipping:feed')
            ->dailyAt('01:15');*/

        $schedule->command('referral:alert')
            ->dailyAt('09:00')
            ->timezone('Europe/Kiev');

        $schedule->command('google-merchant:generate:products-feed')
            ->dailyAt('23:00')
            ->timezone('Europe/Kiev');
        $schedule->command('google-merchant:generate:engagements-feed')
            ->dailyAt('23:00')
            ->timezone('Europe/Kiev');
        $schedule->command('google-merchant:generate:engagements-local-feed')
            ->dailyAt('23:00')
            ->timezone('Europe/Kiev');
        $schedule->command('google-merchant:generate:weddings-feed')
            ->dailyAt('23:00')
            ->timezone('Europe/Kiev');
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__ . '/../../../Console/Commands');

        require base_path('routes/console.php');
    }
}
