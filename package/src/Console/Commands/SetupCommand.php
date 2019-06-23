<?php

namespace InetStudio\Fns\Console\Commands;

use InetStudio\AdminPanel\Base\Console\Commands\BaseSetupCommand;

/**
 * Class SetupCommand.
 */
class SetupCommand extends BaseSetupCommand
{
    /**
     * Имя команды.
     *
     * @var string
     */
    protected $name = 'inetstudio:fns:setup';

    /**
     * Описание команды.
     *
     * @var string
     */
    protected $description = 'Setup fns package';

    /**
     * Инициализация команд.
     */
    protected function initCommands(): void
    {
        $this->calls = [
            [
                'type' => 'artisan',
                'description' => 'Checks fns accounts setup',
                'command' => 'inetstudio:fns:accounts:setup',
            ],
            [
                'type' => 'artisan',
                'description' => 'Checks fns receipts setup',
                'command' => 'inetstudio:fns:receipts:setup',
            ],
        ];
    }
}
