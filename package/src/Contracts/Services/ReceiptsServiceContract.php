<?php

namespace InetStudio\Fns\Contracts\Services;

interface ReceiptsServiceContract
{
    public function checkReceipt(array $params);

    public function getReceipt(array $params);
}
