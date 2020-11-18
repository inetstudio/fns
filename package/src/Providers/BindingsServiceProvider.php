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

    public function register()
    {
        $this->app->singleton('InetStudio\Fns\Contracts\Services\Back\ReceiptsServiceContract', function ($app) {
            $driver = config('fns.driver');

            return resolve('InetStudio\Fns\Managers\ReceiptsServiceManager', compact('app'))->with($driver);
        });
    }

    public function provides()
    {
        return array_keys($this->bindings);
    }
}
