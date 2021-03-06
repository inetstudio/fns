<?php

namespace InetStudio\Fns\Receipts\Providers;

use Illuminate\Support\Facades\Schema;
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
                'InetStudio\Fns\Receipts\Console\Commands\AttachPointsToReceiptsCommand',
                'InetStudio\Fns\Receipts\Console\Commands\SetupCommand',
            ]
        );
    }

    protected function registerPublishes(): void
    {
        if (! $this->app->runningInConsole()) {
            return;
        }

        if (Schema::hasTable('fns_receipts')) {
            return;
        }

        $timestamp = date('Y_m_d_His', time());
        $this->publishes(
            [
                __DIR__.'/../../database/migrations/create_fns_receipts_tables.php.stub' => database_path(
                    'migrations/'.$timestamp.'_create_fns_receipts_tables.php'
                ),
            ],
            'migrations'
        );
    }
}
