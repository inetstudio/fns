<?php

namespace InetStudio\Fns\Providers;

use Illuminate\Support\ServiceProvider as BaseServiceProvider;

class ServiceProvider extends BaseServiceProvider
{
    public function boot(): void
    {
        $this->registerConsoleCommands();
        $this->registerPublishes();
    }

    protected function registerConsoleCommands(): void
    {
        if (! $this->app->runningInConsole()) {
            return;
        }

        $this->commands(
            [
                'InetStudio\Fns\Console\Commands\SetupCommand',
            ]
        );
    }

    protected function registerPublishes(): void
    {
        $this->publishes([
            __DIR__.'/../../config/fns.php' => config_path('fns.php'),
        ], 'config');

        $this->mergeConfigFrom(__DIR__.'/../../config/services.php', 'services');
    }
}
