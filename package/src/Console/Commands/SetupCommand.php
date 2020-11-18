<?php

namespace InetStudio\Fns\Console\Commands;

use InetStudio\AdminPanel\Base\Console\Commands\BaseSetupCommand;

class SetupCommand extends BaseSetupCommand
{
    protected $name = 'inetstudio:fns:setup';

    protected $description = 'Setup fns package';

    protected function initCommands(): void
    {
        $this->calls = [
            [
                'type' => 'artisan',
                'description' => 'Fns receipts setup',
                'command' => 'inetstudio:fns:receipts:setup',
            ],
        ];
    }
}
