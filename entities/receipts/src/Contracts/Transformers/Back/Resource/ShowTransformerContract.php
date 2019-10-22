<?php

namespace InetStudio\Fns\Receipts\Contracts\Transformers\Back\Resource;

use InetStudio\Fns\Receipts\Contracts\Models\ReceiptModelContract;

/**
 * Interface ShowTransformerContract.
 */
interface ShowTransformerContract
{
    /**
     * Трансформация данных.
     *
     * @param  ReceiptModelContract  $item
     *
     * @return array
     */
    public function transform(ReceiptModelContract $item): array;
}
