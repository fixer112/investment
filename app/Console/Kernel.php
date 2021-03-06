<?php

namespace App\Console;

use App\Traits\Sms;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    use Sms;
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
        // $schedule->command('inspire')
        //          ->hourly();
        /* $schedule->call(function(){

        })->everyMinute(); */
       /* ß */
        /* $schedule->call(function(){
        $result = $this->send('08106813749','Sms Still working');

        })->dailyAt('09:00'); */
        $schedule->command('migrate:fresh --seed')->dailyAt('00:00')->when(function () {
            return env('APP_ENV') == 'local';
            //return false;
        });
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__ . '/Commands');

        require base_path('routes/console.php');
    }
}