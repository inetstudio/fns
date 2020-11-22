<?php

namespace InetStudio\Fns\Receipts\Providers;

use Illuminate\Contracts\Support\DeferrableProvider;
use Illuminate\Support\ServiceProvider as BaseServiceProvider;

class BindingsServiceProvider extends BaseServiceProvider implements DeferrableProvider
{
    public array $bindings = [
        'InetStudio\Fns\Receipts\Contracts\DTO\ItemDataContract' => 'InetStudio\Fns\Receipts\DTO\ItemData',
        'InetStudio\Fns\Receipts\Contracts\Models\ReceiptModelContract' => 'InetStudio\Fns\Receipts\Models\ReceiptModel',
        'InetStudio\Fns\Receipts\Contracts\Services\ItemsServiceContract' => 'InetStudio\Fns\Receipts\Services\ItemsService',
    ];

    public function provides()
    {
        return array_keys($this->bindings);
    }
}
