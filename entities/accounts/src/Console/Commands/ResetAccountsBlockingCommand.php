<?php

namespace InetStudio\Fns\Accounts\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Contracts\Container\BindingResolutionException;

/**
 * Class ResetAccountsBlockingCommand.
 */
class ResetAccountsBlockingCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'inetstudio:fns:accounts:blocking-reset';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Reset accounts blocking';

    /**
     * Create a new command instance.
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Запуск команды.
     *
     * @throws BindingResolutionException
     */
    public function handle()
    {
        $accountsService = app()->make('InetStudio\Fns\Accounts\Contracts\Services\Back\ItemsServiceContract');

        $accountsService->resetAccountsBlocking(1);
    }
}
