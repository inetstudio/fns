<?php

namespace InetStudio\Fns\Accounts\Providers;

use Illuminate\Contracts\Support\DeferrableProvider;
use Illuminate\Support\ServiceProvider as BaseServiceProvider;

/**
 * Class BindingsServiceProvider.
 */
class BindingsServiceProvider extends BaseServiceProvider implements DeferrableProvider
{
    /**
     * @var array
     */
    public $bindings = [
        'InetStudio\Fns\Accounts\Contracts\Models\AccountModelContract' => 'InetStudio\Fns\Accounts\Models\AccountModel',
        'InetStudio\Fns\Accounts\Contracts\Services\Back\ItemsServiceContract' => 'InetStudio\Fns\Accounts\Services\Back\ItemsService',
    ];

    /**
     * Получить сервисы от провайдера.
     *
     * @return array
     */
    public function provides()
    {
        return array_keys($this->bindings);
    }
}
