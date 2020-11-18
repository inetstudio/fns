<?php

namespace InetStudio\Fns\Providers;

use Illuminate\Contracts\Support\DeferrableProvider;
use Illuminate\Support\ServiceProvider as BaseServiceProvider;

class BindingsServiceProvider extends BaseServiceProvider implements DeferrableProvider
{
    public array $bindings = [
        'InetStudio\Fns\Contracts\Managers\ReceiptsServiceManagerContract' => 'InetStudio\Fns\Managers\ReceiptsServiceManager',
        'InetStudio\Fns\Contracts\Services\Back\BrandCashServiceContract' => 'InetStudio\Fns\Services\Back\BrandCashService',
        'InetStudio\Fns\Contracts\Services\Back\FnsServiceContract' => 'InetStudio\Fns\Services\Back\FnsService',
    ];

    public array $singletons = [];

    public function __construct($app)
    {
        parent::__construct($app);

        $driver = config('fns.driver');

        $this->singletons['InetStudio\Fns\Contracts\Services\Back\ReceiptsServiceContract'] = resolve('InetStudio\Fns\Contracts\Managers\ReceiptsServiceManagerContract', compact('app'))->with($driver);
    }

    public function provides()
    {
        return array_merge(
            array_keys($this->bindings),
            array_keys($this->singletons)
        );
    }
}
