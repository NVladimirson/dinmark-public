<?php

namespace App\Console;

use App\Jobs\ProductOptions;
use App\Models\Queue;
use App\Services\News\NewsServices;
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
        $schedule->call(function () {
            $queue = Queue::first();
            if($queue){
                switch ($queue->name){
                    case 'new_news':
                        NewsServices::sendNotification($queue);
                        break;
                }
            }
            info('other');
        })->everyMinute();
        $schedule->job(new ProductOptions('uk'))->weeklyOn(1, '1:00');
        $schedule->job(new ProductOptions('ru'))->weeklyOn(1, '1:00');
    }

    /**
     * Register the Closure based commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        require base_path('routes/console.php');
    }
}
