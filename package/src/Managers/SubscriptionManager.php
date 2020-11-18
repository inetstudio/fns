<?php

namespace InetStudio\Fns\Managers;

use Illuminate\Support\Manager;
use InetStudio\Fns\Contracts\Services\Back\FnsServiceContract;
use InetStudio\Fns\Contracts\Services\Back\BrandCashServiceContract;
use InetStudio\Fns\Contracts\Managers\ReceiptsServiceManagerContract;

class ReceiptsServiceManager extends Manager implements ReceiptsServiceManagerContract
{
    public function with($driver)
    {
        return $this->driver($driver);
    }

    protected function createFnsDriver(): FnsServiceContract
    {
        return resolve('InetStudio\Fns\Contracts\Services\Back\FnsServiceContract');
    }

    protected function createBrandcashDriver(): BrandCashServiceContract
    {
        return resolve('InetStudio\Fns\Contracts\Services\Back\BrandCashServiceContract');
    }

    public function getDefaultDriver()
    {
        return 'fns';
    }
}
