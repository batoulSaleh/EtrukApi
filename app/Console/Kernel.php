<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        // $schedule->command('inspire')->hourly();
        // $schedule->call(
        //     function () {
        //         $mazads = Mazad::all();
        //         foreach ($mazads as $mazad) {
        //             if ($mazad->end_date == '2023-05-14') {
        //                 $mazad->status = 'finished';
        //                 $mazad->save();
        //             }
        //         }
        //     }
        // )->everyMinute();
        
    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
