<?php

namespace InetStudio\Fns\Accounts\Contracts\Services\Back;

use InetStudio\Fns\Accounts\Contracts\Models\AccountModelContract;
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
     * @return AccountModelContract
     */
    public function save(array $data, int $id): AccountModelContract;
}
