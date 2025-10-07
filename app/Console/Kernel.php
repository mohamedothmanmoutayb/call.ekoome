<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use App\Models\Sheet;
use App\Models\Stock;
use App\Models\Product;
use App\Models\Countrie;
use App\Models\User;
use App\Models\Zipcode;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\ShopifyIntegration;
use Illuminate\Support\Facades\Http;
use App\Http\Services\GoogleSheetServices;
use Google\Service\Sheets;
use App\Models\Lead;
use App\Models\Client;
use App\Models\LeadProduct;
use Auth;
use DateTime;

class Kernel extends ConsoleKernel
{
    protected $command = [
        \App\Console\Commands\LeadCron::class,
        ];
    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        //$schedule->command('lead::cron')->everyFiveMinutes();

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
