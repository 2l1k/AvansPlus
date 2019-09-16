<?php

namespace App\Console;

use App\Jobs\VerifyBorrowerTask;
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
        'App\Console\Commands\VerifyBorrowerCommand',
        'App\Console\Commands\ExamineBorrowerCommand',
        'App\Console\Commands\ExamineActiveAgreementsCommand',
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->command('VerifyBorrower:verify')->cron('* * * * *');
        $schedule->command('ExamineActiveAgreementsCommand:start')->cron('* * * * *');
        $schedule->command('ExamineBorrowerCommand:start')->cron('* * * * *');
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
