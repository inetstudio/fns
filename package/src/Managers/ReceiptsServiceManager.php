<?php

namespace InetStudio\Fns\Managers;

use Illuminate\Support\Manager;
use InetStudio\Fns\Contracts\Drivers\FnsDriverContract;
use InetStudio\Fns\Contracts\Drivers\BrandCashDriverContract;
use InetStudio\Fns\Contracts\Managers\ReceiptsServiceManagerContract;

class ReceiptsServiceManager extends Manager implements ReceiptsServiceManagerContract
{
    public function with($driver)
    {
        return $this->driver($driver);
    }

    protected function createFnsDriver(): FnsDriverContract
    {
        return resolve('InetStudio\Fns\Contracts\Drivers\FnsDriverContract');
    }

    protected function createBrandcashDriver(): BrandCashDriverContract
    {
        return resolve('InetStudio\Fns\Contracts\Drivers\BrandCashDriverContract');
    }

    public function getDefaultDriver()
    {
        return 'fns';
    }
}
