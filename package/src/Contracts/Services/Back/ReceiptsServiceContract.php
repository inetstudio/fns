<?php

namespace InetStudio\Fns\Contracts\Services\Back;

interface ReceiptsServiceContract
{
    public function checkReceipt(array $params): bool;

    public function getReceipt(array $params): ?array;
}
