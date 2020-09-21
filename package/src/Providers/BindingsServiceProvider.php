<?php

namespace InetStudio\Fns\Providers;

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
        'InetStudio\Fns\Contracts\Services\Back\BrandCashServiceContract' => 'InetStudio\Fns\Services\Back\BrandCashService',
        'InetStudio\Fns\Contracts\Services\Back\FnsServiceContract' => 'InetStudio\Fns\Services\Back\FnsService',
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
