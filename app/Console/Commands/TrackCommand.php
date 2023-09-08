<?php

namespace App\Console\Commands;

use App\Jobs\TrackJob;
use Illuminate\Console\Command;

class TrackCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'track';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'It will execute TrackJob once';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        TrackJob::dispatchSync();
    }
}
