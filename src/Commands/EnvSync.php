<?php

namespace Nox0121\LaravelEnvSyncCommand\Commands;

use Illuminate\Console\Command;
use Nox0121\LaravelEnvSyncCommand\Services\EnvSyncService;

class EnvSync extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'env:sync {source} {destination}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Synchronise the {source} & {destination} files';

    /**
     * @var EnvSyncService
     */
    private $sync;

    /**
     * Create a new command instance.
     *
     * @param EnvSyncService $sync
     */
    public function __construct(EnvSyncService $sync)
    {
        parent::__construct();
        $this->sync = $sync;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->info("\n" . str_repeat('-', 40) . "\n");
        list($destination, $source) = [$this->argument('destination'), $this->argument('source')];

        $diffs = $this->sync->getDiff($source, $destination);
        if (count($diffs) > 0) {
            $this->info(sprintf("The following variables have been added to \"%s\": ", basename($destination)));
            foreach ($diffs as $key => $diff) {
                $this->info(sprintf("\t- %s = %s", $key, (!is_array($diff) ? $diff : $diff['name'])));
                $this->sync->append($destination, $key, $diff);
            }
        } else {
            $this->info(sprintf("Your\"%s\" file is already synced with \"%s\"", basename($destination), basename($source)));
            return false;
        }

        $this->info("\n" . basename($destination) . ' is now synced with ' . basename($source));
    }
}
