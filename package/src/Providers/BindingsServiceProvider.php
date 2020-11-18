<?php

namespace InetStudio\Fns\Providers;

use Illuminate\Contracts\Support\DeferrableProvider;
use Illuminate\Support\ServiceProvider as BaseServiceProvider;

class BindingsServiceProvider extends BaseServiceProvider implements DeferrableProvider
{
    public function register()
    {
        $this->app->bind('InetStudio\Fns\Contracts\Managers\ReceiptsServiceManagerContract', 'InetStudio\Fns\Managers\ReceiptsServiceManager');
        $this->app->bind('InetStudio\Fns\Contracts\Services\Back\BrandCashServiceContract', 'InetStudio\Fns\Services\Back\BrandCashService');
        $this->app->bind('InetStudio\Fns\Contracts\Services\Back\FnsServiceContract', 'InetStudio\Fns\Services\Back\FnsService');

        $this->app->singleton('InetStudio\Fns\Contracts\Services\Back\ReceiptsServiceContract', function ($app) {
            $driver = config('fns.driver');

            return resolve('InetStudio\Fns\Managers\ReceiptsServiceManager', compact('app'))->with($driver);
        });
    }

    public function provides()
    {
        return [
            'InetStudio\Fns\Contracts\Managers\ReceiptsServiceManagerContract',
            'InetStudio\Fns\Contracts\Services\Back\BrandCashServiceContract',
            'InetStudio\Fns\Contracts\Services\Back\FnsServiceContract',
            'InetStudio\Fns\Contracts\Services\Back\ReceiptsServiceContract'
        ];
    }
}
