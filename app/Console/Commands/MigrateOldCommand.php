<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

/**
 * @author Ali J. Kazemi
 *
 * What this command does is basically converting
 * old database structure to new structure, in technical
 * point of view, it migrates migrations in migrations/base/ dir
 * then waits for you to import your old data, and then continues to
 * modify table structure, etc. to the new version
 */
class MigrateOldCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'migrate:old';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Migrate old IRMCTracker database structure to new one';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $continue = strtolower($this->ask('This process will refresh the database (drop all existing data). Are you sure you want to continue?', 'y')) === 'y';

        if (! $continue) {
            return;
        }

        $this->info('Migrating basic structure of the database...');

        Artisan::call('migrate:fresh', [
            '--path' => 'database/migrations/base/'
        ]);

        $dbName = DB::getDatabaseName();

        $this->ask("Please import your old tables data into database named $dbName, then press any key to continue.",);

        Artisan::call('migrate');

        $this->info('Migration complete!');
    }
}
