<?
// app/Console/Kernel.php
namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    protected $commands = [
        Commands\TestEmail::class,
        \App\Console\Commands\UpdateStockThreshold::class,
    ];

    protected function schedule(Schedule $schedule)
    {
        $schedule->command('stocks:update-threshold')->daily();
    }

    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
