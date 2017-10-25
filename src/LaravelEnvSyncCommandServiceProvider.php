<?php

namespace Nox0121\LaravelEnvSyncCommand;

use Illuminate\Support\ServiceProvider;
use Nox0121\LaravelEnvSyncCommand\Commands\EnvSync;

class LaravelEnvSyncCommandServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind('command.env:sync', EnvSync::class);

        $this->commands([
            'command.env:sync',
        ]);

        $this->app->singleton(ConsoleOutput::class);
    }
}
