<?php

namespace InetStudio\Fns\Receipts\Providers;

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
        'InetStudio\Fns\Receipts\Contracts\Models\ReceiptModelContract' => 'InetStudio\Fns\Receipts\Models\ReceiptModel',
        'InetStudio\Fns\Receipts\Contracts\Services\Back\ItemsServiceContract' => 'InetStudio\Fns\Receipts\Services\Back\ItemsService',
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
