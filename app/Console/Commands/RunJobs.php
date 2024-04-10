<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Log;

class RunJobs extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'run:jobs';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Run Queue jobs in shared hosting';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        Artisan::call('queue:restart');
        Artisan::call('queue:work');
        Log::info('Queue restarted');
    }
}
