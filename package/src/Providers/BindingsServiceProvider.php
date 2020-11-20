<?php

namespace InetStudio\Fns\Providers;

use Illuminate\Contracts\Support\DeferrableProvider;
use Illuminate\Support\ServiceProvider as BaseServiceProvider;

class BindingsServiceProvider extends BaseServiceProvider implements DeferrableProvider
{
    public function register()
    {
        $this->app->bind('InetStudio\Fns\Contracts\Managers\ReceiptsServiceManagerContract', 'InetStudio\Fns\Managers\ReceiptsServiceManager');
        $this->app->bind('InetStudio\Fns\Contracts\Drivers\BrandCashDriverContract', 'InetStudio\Fns\Drivers\BrandCash\BrandCashDriver');
        $this->app->bind('InetStudio\Fns\Contracts\Drivers\FnsDriverContract', 'InetStudio\Fns\Drivers\Fns\FnsDriver');

        $this->app->singleton('InetStudio\Fns\Contracts\Services\ReceiptsServiceContract', function ($app) {
            $driver = config('fns.driver');

            return resolve('InetStudio\Fns\Managers\ReceiptsServiceManager', compact('app'))->with($driver);
        });
    }

    public function provides()
    {
        return [
            'InetStudio\Fns\Contracts\Drivers\BrandCashDriverContract',
            'InetStudio\Fns\Contracts\Drivers\FnsDriverContract',
            'InetStudio\Fns\Contracts\Managers\ReceiptsServiceManagerContract',
            'InetStudio\Fns\Contracts\Services\ReceiptsServiceContract'
        ];
    }
}
