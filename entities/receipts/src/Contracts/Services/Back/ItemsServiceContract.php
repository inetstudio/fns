<?php

namespace InetStudio\Fns\Receipts\Contracts\Services\Back;

use InetStudio\Fns\Receipts\Contracts\Models\ReceiptModelContract;
use InetStudio\AdminPanel\Base\Contracts\Services\BaseServiceContract;

/**
 * Interface ItemsServiceContract.
 */
interface ItemsServiceContract extends BaseServiceContract
{
    /**
     * Сохраняем модель.
     *
     * @param  array  $data
     * @param  int  $id
     *
     * @return ReceiptModelContract
     */
    public function save(array $data, int $id): ReceiptModelContract;
}
