<?php

namespace InetStudio\Fns\Accounts\Providers;

use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider as BaseServiceProvider;

/**
 * Class ServiceProvider.
 */
class ServiceProvider extends BaseServiceProvider
{
    /**
     * Загрузка сервиса.
     */
    public function boot(): void
    {
        $this->registerConsoleCommands();
        $this->registerPublishes();
    }

    /**
     * Регистрация команд.
     */
    protected function registerConsoleCommands(): void
    {
        if (! $this->app->runningInConsole()) {
            return;
        }

        $this->commands(
            [
                'InetStudio\Fns\Accounts\Console\Commands\GenerateAccountsCommand',
                'InetStudio\Fns\Accounts\Console\Commands\ResetAccountsBlockingCommand',
                'InetStudio\Fns\Accounts\Console\Commands\SetupCommand',
            ]
        );
    }

    /**
     * Регистрация ресурсов.
     */
    protected function registerPublishes(): void
    {
        if (! $this->app->runningInConsole()) {
            return;
        }

        if (Schema::hasTable('fns_accounts')) {
            return;
        }

        $timestamp = date('Y_m_d_His', time());
        $this->publishes(
            [
                __DIR__.'/../../database/migrations/create_fns_accounts_tables.php.stub' => database_path(
                    'migrations/'.$timestamp.'_create_fns_accounts_tables.php'
                ),
            ],
            'migrations'
        );
    }
}
